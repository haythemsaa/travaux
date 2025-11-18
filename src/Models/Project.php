<?php

namespace App\Models;

use App\Config\Database;
use PDO;

class Project {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function create($data) {
        $sql = "INSERT INTO projects (client_id, category_id, title, description, address, city,
                postal_code, budget_min, budget_max, start_date, urgency, status, published_at)
                VALUES (:client_id, :category_id, :title, :description, :address, :city,
                :postal_code, :budget_min, :budget_max, :start_date, :urgency, :status, NOW())";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':client_id' => $data['client_id'],
            ':category_id' => $data['category_id'],
            ':title' => $data['title'],
            ':description' => $data['description'],
            ':address' => $data['address'] ?? null,
            ':city' => $data['city'],
            ':postal_code' => $data['postal_code'],
            ':budget_min' => $data['budget_min'] ?? null,
            ':budget_max' => $data['budget_max'] ?? null,
            ':start_date' => $data['start_date'] ?? null,
            ':urgency' => $data['urgency'] ?? 'medium',
            ':status' => $data['status'] ?? 'published'
        ]);

        return $this->db->lastInsertId();
    }

    public function findAll($filters = []) {
        $sql = "SELECT p.*, c.name as category_name, c.slug as category_slug,
                u.first_name, u.last_name,
                (SELECT COUNT(*) FROM quotes WHERE project_id = p.id) as quotes_count
                FROM projects p
                JOIN categories c ON p.category_id = c.id
                JOIN users u ON p.client_id = u.id
                WHERE p.status = 'published'";

        $params = [];

        if (!empty($filters['category_id'])) {
            $sql .= " AND p.category_id = :category_id";
            $params[':category_id'] = $filters['category_id'];
        }

        if (!empty($filters['city'])) {
            $sql .= " AND p.city LIKE :city";
            $params[':city'] = '%' . $filters['city'] . '%';
        }

        if (!empty($filters['postal_code'])) {
            $sql .= " AND p.postal_code LIKE :postal_code";
            $params[':postal_code'] = $filters['postal_code'] . '%';
        }

        if (!empty($filters['urgency'])) {
            $sql .= " AND p.urgency = :urgency";
            $params[':urgency'] = $filters['urgency'];
        }

        $sql .= " ORDER BY p.created_at DESC";

        if (!empty($filters['limit'])) {
            $sql .= " LIMIT :limit";
        }

        $stmt = $this->db->prepare($sql);

        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }

        if (!empty($filters['limit'])) {
            $stmt->bindValue(':limit', (int)$filters['limit'], PDO::PARAM_INT);
        }

        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function findById($id) {
        $sql = "SELECT p.*, c.name as category_name, c.slug as category_slug,
                u.first_name, u.last_name, u.phone, u.email,
                (SELECT COUNT(*) FROM quotes WHERE project_id = p.id) as quotes_count
                FROM projects p
                JOIN categories c ON p.category_id = c.id
                JOIN users u ON p.client_id = u.id
                WHERE p.id = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function findByClientId($clientId) {
        $sql = "SELECT p.*, c.name as category_name,
                (SELECT COUNT(*) FROM quotes WHERE project_id = p.id) as quotes_count
                FROM projects p
                JOIN categories c ON p.category_id = c.id
                WHERE p.client_id = :client_id
                ORDER BY p.created_at DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':client_id' => $clientId]);
        return $stmt->fetchAll();
    }

    public function update($id, $data) {
        $fields = [];
        $params = [':id' => $id];

        $allowed = ['title', 'description', 'address', 'city', 'postal_code',
                   'budget_min', 'budget_max', 'start_date', 'urgency', 'status'];

        foreach ($data as $key => $value) {
            if (in_array($key, $allowed)) {
                $fields[] = "$key = :$key";
                $params[":$key"] = $value;
            }
        }

        if (!empty($fields)) {
            $sql = "UPDATE projects SET " . implode(', ', $fields) . " WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute($params);
        }

        return false;
    }

    public function incrementViews($id) {
        $sql = "UPDATE projects SET views_count = views_count + 1 WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    public function addPhoto($projectId, $photoPath, $caption = null) {
        $sql = "INSERT INTO project_photos (project_id, photo_path, caption)
                VALUES (:project_id, :photo_path, :caption)";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':project_id' => $projectId,
            ':photo_path' => $photoPath,
            ':caption' => $caption
        ]);
    }

    public function getPhotos($projectId) {
        $sql = "SELECT * FROM project_photos WHERE project_id = :project_id ORDER BY display_order, id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':project_id' => $projectId]);
        return $stmt->fetchAll();
    }

    public function delete($id) {
        $sql = "DELETE FROM projects WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}
