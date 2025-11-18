<?php

namespace App\Models;

use App\Config\Database;

class Portfolio {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function create($data) {
        $sql = "INSERT INTO portfolio (artisan_id, title, description, category_id,
                photo_path, completion_date, budget_range)
                VALUES (:artisan_id, :title, :description, :category_id,
                :photo_path, :completion_date, :budget_range)";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':artisan_id' => $data['artisan_id'],
            ':title' => $data['title'],
            ':description' => $data['description'] ?? null,
            ':category_id' => $data['category_id'] ?? null,
            ':photo_path' => $data['photo_path'],
            ':completion_date' => $data['completion_date'] ?? null,
            ':budget_range' => $data['budget_range'] ?? null
        ]);
    }

    public function findByArtisanId($artisanId) {
        $sql = "SELECT p.*, c.name as category_name
                FROM portfolio p
                LEFT JOIN categories c ON p.category_id = c.id
                WHERE p.artisan_id = :artisan_id
                ORDER BY p.display_order, p.created_at DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':artisan_id' => $artisanId]);
        return $stmt->fetchAll();
    }

    public function findById($id) {
        $sql = "SELECT p.*, c.name as category_name
                FROM portfolio p
                LEFT JOIN categories c ON p.category_id = c.id
                WHERE p.id = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function update($id, $data) {
        $fields = [];
        $params = [':id' => $id];

        $allowed = ['title', 'description', 'category_id', 'completion_date',
                   'budget_range', 'display_order'];

        foreach ($data as $key => $value) {
            if (in_array($key, $allowed)) {
                $fields[] = "$key = :$key";
                $params[":$key"] = $value;
            }
        }

        if (!empty($fields)) {
            $sql = "UPDATE portfolio SET " . implode(', ', $fields) . " WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute($params);
        }

        return false;
    }

    public function delete($id) {
        $sql = "DELETE FROM portfolio WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}
