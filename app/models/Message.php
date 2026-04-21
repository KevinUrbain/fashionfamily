<?php
require_once __DIR__ . '/../../core/Database.php';

class Message
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Envoie un message et retourne son id.
     */
    public function send(int $senderId, int $receiverId, string $body, ?int $articleId = null): int
    {
        $stmt = $this->db->prepare("
            INSERT INTO messages (sender_id, receiver_id, article_id, body)
            VALUES (?, ?, ?, ?)
        ");
        $stmt->execute([$senderId, $receiverId, $articleId ?: null, $body]);
        return (int) $this->db->lastInsertId();
    }

    /**
     * Liste des conversations uniques de l'utilisateur (dernier message par contact).
     */
    public function getConversations(int $userId): array
    {
        $stmt = $this->db->prepare("
            SELECT
                m.*,
                u.name AS partner_name,
                u.id   AS partner_id,
                (
                    SELECT COUNT(*) FROM messages m2
                    WHERE m2.sender_id = u.id
                      AND m2.receiver_id = ?
                      AND m2.read_at IS NULL
                ) AS unread_count
            FROM messages m
            JOIN users u ON u.id = IF(m.sender_id = ?, m.receiver_id, m.sender_id)
            WHERE (m.sender_id = ? OR m.receiver_id = ?)
              AND m.id IN (
                SELECT MAX(id) FROM messages
                WHERE sender_id = ? OR receiver_id = ?
                GROUP BY LEAST(sender_id, receiver_id), GREATEST(sender_id, receiver_id)
              )
            ORDER BY m.created_at DESC
        ");
        $stmt->execute([$userId, $userId, $userId, $userId, $userId, $userId]);
        return $stmt->fetchAll();
    }

    /**
     * Tous les messages entre deux utilisateurs, ordre chronologique.
     */
    public function getConversation(int $userId, int $otherId): array
    {
        $stmt = $this->db->prepare("
            SELECT
                m.*,
                u.name  AS sender_name,
                a.title AS article_title,
                a.id    AS article_ref_id
            FROM messages m
            JOIN users u ON u.id = m.sender_id
            LEFT JOIN articles a ON a.id = m.article_id
            WHERE (m.sender_id = ? AND m.receiver_id = ?)
               OR (m.sender_id = ? AND m.receiver_id = ?)
            ORDER BY m.created_at ASC
        ");
        $stmt->execute([$userId, $otherId, $otherId, $userId]);
        return $stmt->fetchAll();
    }

    /**
     * Marque comme lus tous les messages envoyés par $senderId à $receiverId.
     */
    public function markRead(int $receiverId, int $senderId): void
    {
        $stmt = $this->db->prepare("
            UPDATE messages
            SET read_at = NOW()
            WHERE receiver_id = ? AND sender_id = ? AND read_at IS NULL
        ");
        $stmt->execute([$receiverId, $senderId]);
    }

    /**
     * Nombre total de messages non lus pour un utilisateur.
     */
    public function countUnread(int $userId): int
    {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) FROM messages
            WHERE receiver_id = ? AND read_at IS NULL
        ");
        $stmt->execute([$userId]);
        return (int) $stmt->fetchColumn();
    }

    /**
     * Indique si au moins un message existe entre les deux utilisateurs.
     */
    public function hasConversation(int $userA, int $userB): bool
    {
        $stmt = $this->db->prepare("
            SELECT 1 FROM messages
            WHERE (sender_id = ? AND receiver_id = ?)
               OR (sender_id = ? AND receiver_id = ?)
            LIMIT 1
        ");
        $stmt->execute([$userA, $userB, $userB, $userA]);
        return (bool) $stmt->fetchColumn();
    }

    /**
     * Vérifie que l'article appartient bien à l'utilisateur donné.
     */
    public function articleBelongsTo(int $articleId, int $userId): bool
    {
        $stmt = $this->db->prepare("
            SELECT 1 FROM articles WHERE id = ? AND user_id = ? LIMIT 1
        ");
        $stmt->execute([$articleId, $userId]);
        return (bool) $stmt->fetchColumn();
    }

    /**
     * Informations basiques sur un utilisateur (pour vérifier qu'il existe).
     */
    public function getPartner(int $partnerId): ?array
    {
        $stmt = $this->db->prepare("SELECT id, name FROM users WHERE id = ?");
        $stmt->execute([$partnerId]);
        $row = $stmt->fetch();
        return $row ?: null;
    }
}
