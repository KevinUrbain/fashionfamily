<?php
require_once __DIR__ . '/../../core/Database.php';

class Article
{
    private PDO $db;

    // Slug → label (utilisé partout : header, vues, filtres)
    public const CATEGORIES = [
        'vetements'    => 'Vêtements',
        'chaussures'   => 'Chaussures',
        'accessoires'  => 'Accessoires',
        'electronique' => 'Électronique',
        'informatique' => 'Informatique',
        'mobilier'     => 'Mobilier',
        'maison'       => 'Maison',
        'sport'        => 'Sport',
        'jeux-video'   => 'Jeux vidéo',
        'sacs'         => 'Sacs',
        'bijoux'       => 'Bijoux',
        'sous-vetements' => 'Sous-vêtements',
        'autre'        => 'Autre',
    ];

    /**
     * Initialise le modèle avec la connexion PDO singleton.
     */
    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Retourne le label d'une catégorie depuis son slug.
     * Gère aussi les anciens articles où le label était stocké directement.
     *
     * @param string $slug Slug ou label de la catégorie.
     * @return string Label lisible (ex. "Vêtements") ou le slug capitalisé si inconnu.
     */
    public static function categoryLabel(string $slug): string
    {
        return self::CATEGORIES[$slug] ?? ucfirst($slug);
    }

    /**
     * Retourne tous les articles avec un tri optionnel.
     *
     * @param string $sort Critère de tri : newest, oldest, price_asc ou price_desc.
     * @return array<int, array<string, mixed>>
     */
    public function getAll(string $sort = 'newest'): array
    {
        $order = match($sort) {
            'price_asc'  => 'price ASC',
            'price_desc' => 'price DESC',
            'oldest'     => 'created_at ASC',
            default      => 'created_at DESC', // newest
        };
        $stmt = $this->db->query("SELECT * FROM articles ORDER BY $order");
        return $stmt->fetchAll();
    }

    /**
     * Retourne les N articles les plus récents.
     *
     * @param int $limit Nombre maximum d'articles à retourner.
     * @return array<int, array<string, mixed>>
     */
    public function getLatest(int $limit): array
    {
        $stmt = $this->db->prepare("SELECT * FROM articles ORDER BY created_at DESC LIMIT ?");
        $stmt->execute([$limit]);
        return $stmt->fetchAll();
    }

    /**
     * Retourne N articles choisis aléatoirement.
     *
     * @param int $limit Nombre maximum d'articles à retourner.
     * @return array<int, array<string, mixed>>
     */
    public function getRandom(int $limit): array
    {
        $stmt = $this->db->prepare("SELECT * FROM articles ORDER BY RAND() LIMIT ?");
        $stmt->execute([$limit]);
        return $stmt->fetchAll();
    }

    /**
     * Retourne un article par son identifiant avec le nom du vendeur joint.
     *
     * @param int $id Identifiant de l'article.
     * @return array<string, mixed>|null null si introuvable.
     */
    public function getById(int $id): ?array
    {
        $stmt = $this->db->prepare("
            SELECT articles.*, users.name AS seller_name
            FROM articles
            JOIN users ON articles.user_id = users.id
            WHERE articles.id = ?
        ");
        $stmt->execute([$id]);
        $result = $stmt->fetch();
        return $result !== false ? $result : null;
    }

    /**
     * Crée un nouvel article en base de données avec le statut « active » et la devise EUR.
     *
     * @param array{user_id: int, title: string, description: string, image_path: string, price: float, quantity: int, category: string, article_condition: string} $data
     * @return int Identifiant du nouvel article.
     */
    public function create(array $data): int
    {
        $stmt = $this->db->prepare("
            INSERT INTO articles (user_id, title, description, image_path, price, currency, quantity, category, article_condition, status)
            VALUES (?, ?, ?, ?, ?, 'EUR', ?, ?, ?, 'active')
        ");
        $stmt->execute([
            $data['user_id'],
            $data['title'],
            $data['description'],
            $data['image_path'],
            $data['price'],
            $data['quantity'],
            $data['category'],
            $data['article_condition'],
        ]);
        return (int) $this->db->lastInsertId();
    }

    /**
     * Retourne tous les articles publiés par un utilisateur donné.
     *
     * @param int $userId Identifiant du vendeur.
     * @return array<int, array<string, mixed>>
     */
    public function getByUserId(int $userId): array
    {
        $stmt = $this->db->prepare("SELECT * FROM articles WHERE user_id = ? ORDER BY created_at DESC");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    /**
     * Met à jour les informations modifiables d'un article (titre, description, prix, quantité, condition, statut).
     *
     * @param int   $id   Identifiant de l'article.
     * @param array{title: string, description: string, price: float, quantity: int, article_condition: string, status: string} $data
     * @return bool true si la mise à jour a réussi.
     */
    public function update(int $id, array $data): bool
    {
        $stmt = $this->db->prepare("
            UPDATE articles
            SET title = ?, description = ?, price = ?, quantity = ?, article_condition = ?, status = ?
            WHERE id = ?
        ");
        return $stmt->execute([
            $data['title'],
            $data['description'],
            $data['price'],
            $data['quantity'],
            $data['article_condition'],
            $data['status'],
            $id,
        ]);
    }

    /**
     * Supprime un article par son identifiant.
     *
     * @param int $id Identifiant de l'article à supprimer.
     * @return bool true si la suppression a réussi.
     */
    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM articles WHERE id = ?");
        return $stmt->execute([$id]);
    }

    /**
     * Retourne les N articles les plus chers.
     * Utilisé dans le dashboard admin pour la mise en avant.
     *
     * @param int $limit Nombre maximum d'articles à retourner.
     * @return array<int, array<string, mixed>>
     */
    public function getTopByPrice(int $limit): array
    {
        $stmt = $this->db->prepare("SELECT * FROM articles ORDER BY price DESC LIMIT ?");
        $stmt->execute([$limit]);
        return $stmt->fetchAll();
    }

    /**
     * Retourne les articles d'une catégorie.
     * Gère les deux formats stockés en DB : slug ('vetements') ou label ('Vêtements').
     *
     * @param string $slug Slug de la catégorie (ex. "vetements").
     * @return array<int, array<string, mixed>>
     */
    public function getByCategory(string $slug): array
    {
        $label = self::CATEGORIES[$slug] ?? $slug;

        $stmt = $this->db->prepare("
            SELECT articles.*, users.name AS seller_name
            FROM articles
            JOIN users ON articles.user_id = users.id
            WHERE articles.category = ? OR articles.category = ?
            ORDER BY articles.created_at DESC
        ");
        $stmt->execute([$slug, $label]);
        return $stmt->fetchAll();
    }

    /**
     * Recherche des articles par mots-clés dans le titre, la description et la catégorie.
     * Inclut le nom du vendeur dans les résultats.
     *
     * @param string $query Terme de recherche.
     * @return array<int, array<string, mixed>>
     */
    public function search(string $query): array
    {
        $stmt = $this->db->prepare("
            SELECT articles.*, users.name AS seller_name
            FROM articles
            JOIN users ON articles.user_id = users.id
            WHERE articles.title LIKE ? OR articles.description LIKE ? OR articles.category LIKE ?
            ORDER BY articles.created_at DESC
        ");
        $like = "%$query%";
        $stmt->execute([$like, $like, $like]);
        return $stmt->fetchAll();
    }

    /**
     * Retourne le nombre total d'articles en base de données.
     *
     * @return int
     */
    public function count(): int
    {
        return (int) $this->db->query("SELECT COUNT(*) FROM articles")->fetchColumn();
    }

    /**
     * Retourne le nombre de publications par mois sur les 6 derniers mois.
     * Utilisé pour les graphiques du dashboard admin.
     *
     * @return array<int, array{month: string, count: int}>
     */
    public function countByMonth(): array
    {
        $stmt = $this->db->prepare("
            SELECT DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(*) as count
            FROM articles
            WHERE created_at >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
            GROUP BY month
            ORDER BY month ASC
        ");
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
