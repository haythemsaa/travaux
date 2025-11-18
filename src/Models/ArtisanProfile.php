<?php

namespace App\Models;

use App\Config\Database;
use PDO;

class ArtisanProfile {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function create($userId, $data) {
        $sql = "INSERT INTO artisan_profiles (user_id, company_name, siret, description,
                address, city, postal_code, website, years_experience)
                VALUES (:user_id, :company_name, :siret, :description,
                :address, :city, :postal_code, :website, :years_experience)";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':user_id' => $userId,
            ':company_name' => $data['company_name'],
            ':siret' => $data['siret'] ?? null,
            ':description' => $data['description'] ?? null,
            ':address' => $data['address'] ?? null,
            ':city' => $data['city'] ?? null,
            ':postal_code' => $data['postal_code'] ?? null,
            ':website' => $data['website'] ?? null,
            ':years_experience' => $data['years_experience'] ?? null
        ]);

        return $this->db->lastInsertId();
    }

    public function findByUserId($userId) {
        $sql = "SELECT * FROM artisan_profiles WHERE user_id = :user_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetch();
    }

    public function findById($id) {
        $sql = "SELECT a.*, u.first_name, u.last_name, u.email, u.phone, u.avatar
                FROM artisan_profiles a
                JOIN users u ON a.user_id = u.id
                WHERE a.id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function findAll($filters = []) {
        $sql = "SELECT a.*, u.first_name, u.last_name,
                (SELECT COUNT(*) FROM reviews WHERE artisan_id = a.id) as reviews_count
                FROM artisan_profiles a
                JOIN users u ON a.user_id = u.id
                WHERE u.is_active = 1";

        $params = [];

        if (!empty($filters['city'])) {
            $sql .= " AND a.city LIKE :city";
            $params[':city'] = '%' . $filters['city'] . '%';
        }

        if (!empty($filters['specialty'])) {
            $sql .= " AND EXISTS (SELECT 1 FROM artisan_specialties
                     WHERE artisan_id = a.id AND specialty = :specialty)";
            $params[':specialty'] = $filters['specialty'];
        }

        $sql .= " ORDER BY a.rating_average DESC, a.total_reviews DESC";

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

    public function update($id, $data) {
        $fields = [];
        $params = [':id' => $id];

        $allowed = ['company_name', 'siret', 'description', 'address', 'city',
                   'postal_code', 'website', 'years_experience', 'employees_count',
                   'insurance_number', 'certifications', 'service_area'];

        foreach ($data as $key => $value) {
            if (in_array($key, $allowed)) {
                $fields[] = "$key = :$key";
                $params[":$key"] = $value;
            }
        }

        if (!empty($fields)) {
            $sql = "UPDATE artisan_profiles SET " . implode(', ', $fields) . " WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute($params);
        }

        return false;
    }

    public function addSpecialty($artisanId, $specialty) {
        $sql = "INSERT IGNORE INTO artisan_specialties (artisan_id, specialty)
                VALUES (:artisan_id, :specialty)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':artisan_id' => $artisanId,
            ':specialty' => $specialty
        ]);
    }

    public function getSpecialties($artisanId) {
        $sql = "SELECT specialty FROM artisan_specialties WHERE artisan_id = :artisan_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':artisan_id' => $artisanId]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function updateRating($artisanId) {
        $sql = "UPDATE artisan_profiles SET
                rating_average = (SELECT AVG(rating) FROM reviews WHERE artisan_id = :artisan_id),
                total_reviews = (SELECT COUNT(*) FROM reviews WHERE artisan_id = :artisan_id)
                WHERE id = :artisan_id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':artisan_id' => $artisanId]);
    }

    public function hasAccessToProject($artisanId, $projectId) {
        $sql = "SELECT COUNT(*) as count FROM project_access
                WHERE artisan_id = :artisan_id AND project_id = :project_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':artisan_id' => $artisanId,
            ':project_id' => $projectId
        ]);
        $result = $stmt->fetch();
        return $result['count'] > 0;
    }

    public function grantProjectAccess($artisanId, $projectId, $price = 0) {
        $sql = "INSERT INTO project_access (artisan_id, project_id, access_price)
                VALUES (:artisan_id, :project_id, :access_price)
                ON DUPLICATE KEY UPDATE accessed_at = NOW()";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':artisan_id' => $artisanId,
            ':project_id' => $projectId,
            ':access_price' => $price
        ]);
    }
}
