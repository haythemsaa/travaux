<?php

namespace App\Models;

use App\Config\Database;

class Quote {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function create($data) {
        $sql = "INSERT INTO quotes (project_id, artisan_id, amount, description,
                estimated_duration, start_date, payment_terms, valid_until)
                VALUES (:project_id, :artisan_id, :amount, :description,
                :estimated_duration, :start_date, :payment_terms, :valid_until)";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':project_id' => $data['project_id'],
            ':artisan_id' => $data['artisan_id'],
            ':amount' => $data['amount'],
            ':description' => $data['description'],
            ':estimated_duration' => $data['estimated_duration'] ?? null,
            ':start_date' => $data['start_date'] ?? null,
            ':payment_terms' => $data['payment_terms'] ?? null,
            ':valid_until' => $data['valid_until'] ?? null
        ]);

        // Update project quotes count
        $updateSql = "UPDATE projects SET quotes_count = quotes_count + 1 WHERE id = :project_id";
        $updateStmt = $this->db->prepare($updateSql);
        $updateStmt->execute([':project_id' => $data['project_id']]);

        return $this->db->lastInsertId();
    }

    public function findByProjectId($projectId) {
        $sql = "SELECT q.*, a.company_name, a.rating_average, a.total_reviews,
                u.first_name, u.last_name, u.phone, u.email
                FROM quotes q
                JOIN artisan_profiles a ON q.artisan_id = a.id
                JOIN users u ON a.user_id = u.id
                WHERE q.project_id = :project_id
                ORDER BY q.created_at DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':project_id' => $projectId]);
        return $stmt->fetchAll();
    }

    public function findByArtisanId($artisanId) {
        $sql = "SELECT q.*, p.title, p.city, p.postal_code,
                u.first_name, u.last_name
                FROM quotes q
                JOIN projects p ON q.project_id = p.id
                JOIN users u ON p.client_id = u.id
                WHERE q.artisan_id = :artisan_id
                ORDER BY q.created_at DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':artisan_id' => $artisanId]);
        return $stmt->fetchAll();
    }

    public function findById($id) {
        $sql = "SELECT q.*, p.title, p.description as project_description,
                a.company_name, u.first_name, u.last_name
                FROM quotes q
                JOIN projects p ON q.project_id = p.id
                JOIN artisan_profiles a ON q.artisan_id = a.id
                JOIN users u ON p.client_id = u.id
                WHERE q.id = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function updateStatus($id, $status) {
        $sql = "UPDATE quotes SET status = :status WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':status' => $status
        ]);
    }

    public function checkExists($projectId, $artisanId) {
        $sql = "SELECT COUNT(*) as count FROM quotes
                WHERE project_id = :project_id AND artisan_id = :artisan_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':project_id' => $projectId,
            ':artisan_id' => $artisanId
        ]);
        $result = $stmt->fetch();
        return $result['count'] > 0;
    }
}
