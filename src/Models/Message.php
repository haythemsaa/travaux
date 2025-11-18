<?php

namespace App\Models;

use App\Config\Database;

class Message {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function create($data) {
        $sql = "INSERT INTO messages (project_id, sender_id, recipient_id, message)
                VALUES (:project_id, :sender_id, :recipient_id, :message)";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':project_id' => $data['project_id'],
            ':sender_id' => $data['sender_id'],
            ':recipient_id' => $data['recipient_id'],
            ':message' => $data['message']
        ]);
    }

    public function getConversation($projectId, $userId1, $userId2) {
        $sql = "SELECT m.*,
                sender.first_name as sender_first_name,
                sender.last_name as sender_last_name,
                recipient.first_name as recipient_first_name,
                recipient.last_name as recipient_last_name
                FROM messages m
                JOIN users sender ON m.sender_id = sender.id
                JOIN users recipient ON m.recipient_id = recipient.id
                WHERE m.project_id = :project_id
                AND ((m.sender_id = :user1 AND m.recipient_id = :user2)
                OR (m.sender_id = :user2 AND m.recipient_id = :user1))
                ORDER BY m.created_at ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':project_id' => $projectId,
            ':user1' => $userId1,
            ':user2' => $userId2
        ]);

        return $stmt->fetchAll();
    }

    public function getConversations($userId) {
        $sql = "SELECT DISTINCT
                p.id as project_id,
                p.title as project_title,
                CASE
                    WHEN m.sender_id = :user_id THEN m.recipient_id
                    ELSE m.sender_id
                END as other_user_id,
                CASE
                    WHEN m.sender_id = :user_id THEN CONCAT(recipient.first_name, ' ', recipient.last_name)
                    ELSE CONCAT(sender.first_name, ' ', sender.last_name)
                END as other_user_name,
                MAX(m.created_at) as last_message_date,
                (SELECT COUNT(*) FROM messages
                 WHERE project_id = p.id
                 AND recipient_id = :user_id
                 AND is_read = 0) as unread_count
                FROM messages m
                JOIN projects p ON m.project_id = p.id
                JOIN users sender ON m.sender_id = sender.id
                JOIN users recipient ON m.recipient_id = recipient.id
                WHERE m.sender_id = :user_id OR m.recipient_id = :user_id
                GROUP BY p.id, other_user_id
                ORDER BY last_message_date DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetchAll();
    }

    public function markAsRead($projectId, $userId) {
        $sql = "UPDATE messages SET is_read = 1
                WHERE project_id = :project_id
                AND recipient_id = :user_id
                AND is_read = 0";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':project_id' => $projectId,
            ':user_id' => $userId
        ]);
    }

    public function getUnreadCount($userId) {
        $sql = "SELECT COUNT(*) as count FROM messages
                WHERE recipient_id = :user_id AND is_read = 0";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':user_id' => $userId]);
        $result = $stmt->fetch();
        return $result['count'];
    }
}
