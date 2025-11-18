<?php

namespace App\Models;

use App\Config\Database;

class Favorite {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function add($clientId, $artisanId) {
        $sql = "INSERT IGNORE INTO favorites (client_id, artisan_id)
                VALUES (:client_id, :artisan_id)";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':client_id' => $clientId,
            ':artisan_id' => $artisanId
        ]);
    }

    public function remove($clientId, $artisanId) {
        $sql = "DELETE FROM favorites
                WHERE client_id = :client_id AND artisan_id = :artisan_id";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':client_id' => $clientId,
            ':artisan_id' => $artisanId
        ]);
    }

    public function isFavorite($clientId, $artisanId) {
        $sql = "SELECT COUNT(*) as count FROM favorites
                WHERE client_id = :client_id AND artisan_id = :artisan_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':client_id' => $clientId,
            ':artisan_id' => $artisanId
        ]);
        $result = $stmt->fetch();
        return $result['count'] > 0;
    }

    public function findByClientId($clientId) {
        $sql = "SELECT f.*, a.company_name, a.description, a.city, a.rating_average, a.total_reviews,
                u.first_name, u.last_name, u.avatar
                FROM favorites f
                JOIN artisan_profiles a ON f.artisan_id = a.id
                JOIN users u ON a.user_id = u.id
                WHERE f.client_id = :client_id
                ORDER BY f.created_at DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':client_id' => $clientId]);
        return $stmt->fetchAll();
    }
}
