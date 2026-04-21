<?php
require_once __DIR__ . '/../../core/Database.php';

class User
{
    private PDO $db;

    /**
     * Initialise le modèle avec la connexion PDO singleton.
     */
    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Retourne tous les utilisateurs triés par date d'inscription décroissante.
     * Le mot de passe n'est pas inclus dans le résultat.
     *
     * @return array<int, array<string, mixed>>
     */
    public function getAll(): array
    {
        $stmt = $this->db->query("SELECT id, name, email, role, created_at FROM users ORDER BY created_at DESC");
        return $stmt->fetchAll();
    }

    /**
     * Retourne un utilisateur par son identifiant.
     * Le mot de passe n'est pas inclus dans le résultat.
     *
     * @param int $id Identifiant de l'utilisateur.
     * @return array<string, mixed>|null null si introuvable.
     */
    public function getById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT id, name, email, role, created_at FROM users WHERE id = ?");
        $stmt->execute([$id]);
        $result = $stmt->fetch();
        return $result !== false ? $result : null;
    }

    /**
     * Retourne un utilisateur par son adresse email, mot de passe inclus.
     * Utilisé uniquement pour l'authentification et la vérification de mot de passe.
     *
     * @param string $email Adresse email à rechercher.
     * @return array<string, mixed>|null null si introuvable.
     */
    public function getByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare("SELECT id, name, email, role, password, created_at FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $result = $stmt->fetch();
        return $result !== false ? $result : null;
    }

    /**
     * Crée un nouvel utilisateur avec le mot de passe hashé en bcrypt.
     *
     * @param array{name: string, email: string, password: string} $data Données du formulaire d'inscription.
     * @return int|false Identifiant du nouvel utilisateur ou false en cas d'échec.
     */
    public function create(array $data): int|false
    {
        $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
        $stmt = $this->db->prepare("INSERT INTO users (name, email, password, created_at) VALUES (?, ?, ?, NOW())");
        $stmt->execute([$data['name'], $data['email'], $hashedPassword]);
        $id = (int) $this->db->lastInsertId();
        return $id > 0 ? $id : false;
    }

    /**
     * Met à jour le nom, l'email et le rôle d'un utilisateur existant.
     *
     * @param int   $id   Identifiant de l'utilisateur.
     * @param array{name: string, email: string, role: string} $data Nouvelles valeurs.
     * @return bool true si la mise à jour a réussi.
     */
    public function update(int $id, array $data): bool
    {
        $stmt = $this->db->prepare("UPDATE users SET name = ?, email = ?, role = ? WHERE id = ?");
        return $stmt->execute([$data['name'], $data['email'], $data['role'], $id]);
    }

    /**
     * Supprime un utilisateur par son identifiant.
     *
     * @param int $id Identifiant de l'utilisateur à supprimer.
     * @return bool true si la suppression a réussi.
     */
    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM users WHERE id = ?");
        return $stmt->execute([$id]);
    }

    /**
     * Vérifie si une adresse email est déjà utilisée par un autre compte.
     *
     * @param string   $email     Adresse email à vérifier.
     * @param int|null $excludeId Identifiant à exclure de la recherche (utile lors d'une mise à jour).
     * @return bool true si l'email existe déjà.
     */
    public function emailExists(string $email, ?int $excludeId = null): bool
    {
        if ($excludeId) {
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM users WHERE email = ? AND id != ?");
            $stmt->execute([$email, $excludeId]);
        } else {
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
            $stmt->execute([$email]);
        }
        return (int) $stmt->fetchColumn() > 0;
    }

    /**
     * Authentifie un utilisateur par email et mot de passe.
     * Retourne les données de l'utilisateur sans le mot de passe en cas de succès.
     *
     * @param string $email    Adresse email.
     * @param string $password Mot de passe en clair.
     * @return array<string, mixed>|false Données utilisateur ou false si les identifiants sont invalides.
     */
    public function authenticate(string $email, string $password): array|false
    {
        $user = $this->getByEmail($email);
        if (!$user) {
            return false;
        }
        if (password_verify($password, $user['password'])) {
            unset($user['password']);
            return $user;
        }
        return false;
    }

    /**
     * Recherche des utilisateurs par nom ou email (recherche partielle).
     *
     * @param string $query Terme de recherche.
     * @return array<int, array<string, mixed>>
     */
    public function search(string $query): array
    {
        $stmt = $this->db->prepare("
            SELECT id, name, email, role, created_at FROM users
            WHERE name LIKE ? OR email LIKE ?
            ORDER BY created_at DESC
        ");
        $like = '%' . $query . '%';
        $stmt->execute([$like, $like]);
        return $stmt->fetchAll();
    }

    /**
     * Retourne le nombre total d'utilisateurs inscrits.
     *
     * @return int
     */
    public function count(): int
    {
        return (int) $this->db->query("SELECT COUNT(*) FROM users")->fetchColumn();
    }

    /**
     * Retourne le nombre d'inscriptions par mois sur les 6 derniers mois.
     * Utilisé pour les graphiques du dashboard admin.
     *
     * @return array<int, array{month: string, count: int}>
     */
    public function countByMonth(): array
    {
        $stmt = $this->db->prepare("
            SELECT DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(*) as count
            FROM users
            WHERE created_at >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
            GROUP BY month
            ORDER BY month ASC
        ");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Retourne les informations d'un utilisateur enrichies de ses statistiques agrégées :
     * nombre total de commandes, nombre d'articles publiés et montant total dépensé.
     *
     * @param int $id Identifiant de l'utilisateur.
     * @return array<string, mixed> Tableau vide si l'utilisateur est introuvable.
     */
    public function getStatsById(int $id): array
    {
        $stmt = $this->db->prepare("
            SELECT
                u.id,
                u.name,
                u.email,
                u.role,
                u.created_at,
                COUNT(DISTINCT o.id)  AS total_orders,
                COUNT(DISTINCT a.id)  AS total_articles,
                COALESCE(SUM(o.total_price), 0) AS total_spent
            FROM users u
            LEFT JOIN orders  o ON o.buyer_id = u.id
            LEFT JOIN articles a ON a.user_id  = u.id
            WHERE u.id = ?
            GROUP BY u.id
        ");
        $stmt->execute([$id]);
        $result = $stmt->fetch();
        return $result !== false ? $result : [];
    }

    /**
     * Modifie le mot de passe d'un utilisateur en le hashant en bcrypt.
     *
     * @param int    $id          Identifiant de l'utilisateur.
     * @param string $newPassword Nouveau mot de passe en clair.
     * @return bool true si la mise à jour a réussi.
     */
    public function changePassword(int $id, string $newPassword): bool
    {
        $hashed = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt = $this->db->prepare("UPDATE users SET password = ? WHERE id = ?");
        return $stmt->execute([$hashed, $id]);
    }
}
