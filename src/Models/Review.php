<?php

namespace App\Models;

use App\Config\Database;

class Review {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function create($data) {
        $sql = "INSERT INTO reviews (project_id, artisan_id, client_id, rating, title, comment,
                quality_rating, punctuality_rating, communication_rating, price_rating)
                VALUES (:project_id, :artisan_id, :client_id, :rating, :title, :comment,
                :quality_rating, :punctuality_rating, :communication_rating, :price_rating)";

        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute([
            ':project_id' => $data['project_id'],
            ':artisan_id' => $data['artisan_id'],
            ':client_id' => $data['client_id'],
            ':rating' => $data['rating'],
            ':title' => $data['title'] ?? null,
            ':comment' => $data['comment'] ?? null,
            ':quality_rating' => $data['quality_rating'] ?? $data['rating'],
            ':punctuality_rating' => $data['punctuality_rating'] ?? $data['rating'],
            ':communication_rating' => $data['communication_rating'] ?? $data['rating'],
            ':price_rating' => $data['price_rating'] ?? $data['rating']
        ]);

        if ($result) {
            // Update artisan rating
            $this->updateArtisanRating($data['artisan_id']);
        }

        return $result;
    }

    public function findByArtisanId($artisanId) {
        $sql = "SELECT r.*, p.title as project_title,
                CONCAT(u.first_name, ' ', u.last_name) as client_name,
                u.avatar as client_avatar
                FROM reviews r
                JOIN projects p ON r.project_id = p.id
                JOIN users u ON r.client_id = u.id
                WHERE r.artisan_id = :artisan_id
                ORDER BY r.created_at DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':artisan_id' => $artisanId]);
        return $stmt->fetchAll();
    }

    public function findByClientId($clientId) {
        $sql = "SELECT r.*, p.title as project_title,
                a.company_name
                FROM reviews r
                JOIN projects p ON r.project_id = p.id
                JOIN artisan_profiles a ON r.artisan_id = a.id
                WHERE r.client_id = :client_id
                ORDER BY r.created_at DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':client_id' => $clientId]);
        return $stmt->fetchAll();
    }

    public function checkExists($projectId, $clientId) {
        $sql = "SELECT COUNT(*) as count FROM reviews
                WHERE project_id = :project_id AND client_id = :client_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':project_id' => $projectId,
            ':client_id' => $clientId
        ]);
        $result = $stmt->fetch();
        return $result['count'] > 0;
    }

    public function addArtisanResponse($reviewId, $response) {
        $sql = "UPDATE reviews SET artisan_response = :response, response_date = NOW()
                WHERE id = :review_id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':review_id' => $reviewId,
            ':response' => $response
        ]);
    }

    private function updateArtisanRating($artisanId) {
        $sql = "UPDATE artisan_profiles SET
                rating_average = (SELECT AVG(rating) FROM reviews WHERE artisan_id = :artisan_id),
                total_reviews = (SELECT COUNT(*) FROM reviews WHERE artisan_id = :artisan_id)
                WHERE id = :artisan_id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':artisan_id' => $artisanId]);
    }

    public function getArtisanStats($artisanId) {
        $sql = "SELECT
                AVG(rating) as avg_rating,
                AVG(quality_rating) as avg_quality,
                AVG(punctuality_rating) as avg_punctuality,
                AVG(communication_rating) as avg_communication,
                AVG(price_rating) as avg_price,
                COUNT(*) as total_reviews
                FROM reviews
                WHERE artisan_id = :artisan_id";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':artisan_id' => $artisanId]);
        return $stmt->fetch();
    }
}
