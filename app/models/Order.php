<?php
require_once __DIR__ . '/../../core/Database.php';

class Order
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
     * Crée une commande et insère ses lignes dans une transaction atomique.
     * En cas d'erreur, la transaction est annulée et l'exception est relancée.
     *
     * @param int   $buyerId Identifiant de l'acheteur.
     * @param array $items   Tableau d'articles du panier (chaque élément doit avoir id, quantity et price).
     * @param float $total   Montant total de la commande.
     * @return int Identifiant de la commande créée.
     * @throws \Exception En cas d'échec de l'insertion.
     */
    public function create(int $buyerId, array $items, float $total): int
    {
        $this->db->beginTransaction();
        try {
            $stmt = $this->db->prepare("
                INSERT INTO orders (buyer_id, total_price, status) VALUES (?, ?, 'pending')
            ");
            $stmt->execute([$buyerId, $total]);
            $orderId = (int) $this->db->lastInsertId();

            $itemStmt = $this->db->prepare("
                INSERT INTO order_items (order_id, article_id, quantity, price) VALUES (?, ?, ?, ?)
            ");
            foreach ($items as $item) {
                $itemStmt->execute([$orderId, $item['id'], $item['quantity'], $item['price']]);
            }

            $this->db->commit();
            return $orderId;
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    /**
     * Retourne toutes les commandes avec les informations de l'acheteur, triées par date décroissante.
     *
     * @return array<int, array<string, mixed>>
     */
    public function getAll(): array
    {
        $stmt = $this->db->query("
            SELECT o.*, u.name AS buyer_name, u.email AS buyer_email
            FROM orders o
            JOIN users u ON o.buyer_id = u.id
            ORDER BY o.created_at DESC
        ");
        return $stmt->fetchAll();
    }

    /**
     * Recherche des commandes par nom d'acheteur, email ou statut (recherche partielle).
     *
     * @param string $query Terme de recherche.
     * @return array<int, array<string, mixed>>
     */
    public function search(string $query): array
    {
        $stmt = $this->db->prepare("
            SELECT o.*, u.name AS buyer_name, u.email AS buyer_email
            FROM orders o
            JOIN users u ON o.buyer_id = u.id
            WHERE u.name LIKE ? OR u.email LIKE ? OR o.status LIKE ?
            ORDER BY o.created_at DESC
        ");
        $like = '%' . $query . '%';
        $stmt->execute([$like, $like, $like]);
        return $stmt->fetchAll();
    }

    /**
     * Retourne une commande par son identifiant avec le détail de ses lignes et les informations de l'acheteur.
     *
     * @param int $id Identifiant de la commande.
     * @return array<string, mixed>|null null si introuvable.
     */
    public function getById(int $id): ?array
    {
        $stmt = $this->db->prepare("
            SELECT o.*, u.name AS buyer_name, u.email AS buyer_email
            FROM orders o
            JOIN users u ON o.buyer_id = u.id
            WHERE o.id = ?
        ");
        $stmt->execute([$id]);
        $order = $stmt->fetch();
        if (!$order) return null;

        $itemStmt = $this->db->prepare("
            SELECT oi.*, a.title, a.image_path
            FROM order_items oi
            JOIN articles a ON oi.article_id = a.id
            WHERE oi.order_id = ?
        ");
        $itemStmt->execute([$id]);
        $order['items'] = $itemStmt->fetchAll();

        return $order;
    }

    /**
     * Retourne toutes les commandes d'un acheteur sans le détail des lignes.
     *
     * @param int $buyerId Identifiant de l'acheteur.
     * @return array<int, array<string, mixed>>
     */
    public function getByBuyerId(int $buyerId): array
    {
        $stmt = $this->db->prepare("
            SELECT * FROM orders WHERE buyer_id = ? ORDER BY created_at DESC
        ");
        $stmt->execute([$buyerId]);
        return $stmt->fetchAll();
    }

    /**
     * Retourne les commandes d'un acheteur avec le détail des articles de chaque commande.
     * Effectue une requête supplémentaire par commande pour récupérer les lignes.
     *
     * @param int $buyerId Identifiant de l'acheteur.
     * @return array<int, array<string, mixed>> Chaque commande contient une clé « items ».
     */
    public function getByBuyerIdWithItems(int $buyerId): array
    {
        $stmt = $this->db->prepare("
            SELECT o.id, o.total_price, o.status, o.created_at,
                   COUNT(oi.id) AS item_count
            FROM orders o
            LEFT JOIN order_items oi ON oi.order_id = o.id
            WHERE o.buyer_id = ?
            GROUP BY o.id
            ORDER BY o.created_at DESC
        ");
        $stmt->execute([$buyerId]);
        $orders = $stmt->fetchAll();

        $itemStmt = $this->db->prepare("
            SELECT oi.quantity, oi.price,
                   a.title, a.image_path, a.id AS article_id
            FROM order_items oi
            JOIN articles a ON a.id = oi.article_id
            WHERE oi.order_id = ?
        ");

        foreach ($orders as &$order) {
            $itemStmt->execute([$order['id']]);
            $order['items'] = $itemStmt->fetchAll();
        }

        return $orders;
    }

    /**
     * Retourne le nombre de commandes passées par un acheteur.
     *
     * @param int $buyerId Identifiant de l'acheteur.
     * @return int
     */
    public function countByBuyerId(int $buyerId): int
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM orders WHERE buyer_id = ?");
        $stmt->execute([$buyerId]);
        return (int) $stmt->fetchColumn();
    }

    /**
     * Met à jour le statut d'une commande.
     * Retourne false si le statut fourni n'est pas dans la liste des valeurs autorisées.
     *
     * @param int    $id     Identifiant de la commande.
     * @param string $status Nouveau statut : pending, paid, shipped, delivered ou cancelled.
     * @return bool true si la mise à jour a réussi, false si le statut est invalide.
     */
    public function updateStatus(int $id, string $status): bool
    {
        $allowed = ['pending', 'paid', 'shipped', 'delivered', 'cancelled'];
        if (!in_array($status, $allowed)) return false;

        $stmt = $this->db->prepare("UPDATE orders SET status = ? WHERE id = ?");
        return $stmt->execute([$status, $id]);
    }

    /**
     * Retourne le nombre total de commandes en base de données.
     *
     * @return int
     */
    public function count(): int
    {
        return (int) $this->db->query("SELECT COUNT(*) FROM orders")->fetchColumn();
    }
}
