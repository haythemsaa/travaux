<?php

namespace App\Models;

use App\Config\Database;

class Notification {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function create($userId, $type, $title, $message, $link = null) {
        $sql = "INSERT INTO notifications (user_id, type, title, message, link)
                VALUES (:user_id, :type, :title, :message, :link)";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':user_id' => $userId,
            ':type' => $type,
            ':title' => $title,
            ':message' => $message,
            ':link' => $link
        ]);
    }

    public function findByUserId($userId, $limit = 20) {
        $sql = "SELECT * FROM notifications
                WHERE user_id = :user_id
                ORDER BY created_at DESC
                LIMIT :limit";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':user_id', $userId);
        $stmt->bindValue(':limit', (int)$limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getUnreadCount($userId) {
        $sql = "SELECT COUNT(*) as count FROM notifications
                WHERE user_id = :user_id AND is_read = 0";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':user_id' => $userId]);
        $result = $stmt->fetch();
        return $result['count'];
    }

    public function markAsRead($id) {
        $sql = "UPDATE notifications SET is_read = 1 WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    public function markAllAsRead($userId) {
        $sql = "UPDATE notifications SET is_read = 1 WHERE user_id = :user_id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':user_id' => $userId]);
    }

    public function delete($id) {
        $sql = "DELETE FROM notifications WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    // Helper methods to create specific notifications
    public static function notifyNewQuote($clientId, $projectId, $artisanName) {
        $notification = new self();
        return $notification->create(
            $clientId,
            'new_quote',
            'Nouveau devis reçu',
            "L'artisan $artisanName a soumis un devis pour votre projet",
            "/client/projects/$projectId"
        );
    }

    public static function notifyQuoteAccepted($artisanUserId, $projectTitle) {
        $notification = new self();
        return $notification->create(
            $artisanUserId,
            'quote_accepted',
            'Devis accepté',
            "Votre devis pour le projet '$projectTitle' a été accepté!",
            "/artisan/dashboard"
        );
    }

    public static function notifyNewMessage($userId, $senderName, $projectId) {
        $notification = new self();
        return $notification->create(
            $userId,
            'new_message',
            'Nouveau message',
            "$senderName vous a envoyé un message",
            "/messages?project=$projectId"
        );
    }
}
