<?php

namespace App\Models;

use App\Config\Database;

class PriceStatistics {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function updateStatistics($categoryId, $region, $city = null) {
        // Get all completed projects for this category/region
        $sql = "SELECT AVG(budget_min) as avg_min, AVG(budget_max) as avg_max,
                COUNT(*) as count
                FROM projects
                WHERE category_id = :category_id
                AND city " . ($city ? "= :city" : "LIKE :region") . "
                AND status = 'completed'
                AND budget_min IS NOT NULL
                AND budget_max IS NOT NULL
                AND budget_min > 0";

        $stmt = $this->db->prepare($sql);
        $params = [':category_id' => $categoryId];

        if ($city) {
            $params[':city'] = $city;
        } else {
            $params[':region'] = '%' . $region . '%';
        }

        $stmt->execute($params);
        $stats = $stmt->fetch();

        if ($stats['count'] > 0) {
            // Calculate median
            $medianSql = "SELECT budget_min, budget_max
                         FROM projects
                         WHERE category_id = :category_id
                         AND city " . ($city ? "= :city" : "LIKE :region") . "
                         AND status = 'completed'
                         AND budget_min IS NOT NULL
                         ORDER BY budget_min";

            $medianStmt = $this->db->prepare($medianSql);
            $medianStmt->execute($params);
            $values = $medianStmt->fetchAll();

            $count = count($values);
            $median = 0;
            if ($count > 0) {
                $mid = floor($count / 2);
                $median = ($values[$mid]['budget_min'] + $values[$mid]['budget_max']) / 2;
            }

            // Update or insert statistics
            $updateSql = "INSERT INTO price_statistics
                         (category_id, region, city, avg_price_min, avg_price_max, median_price, sample_count)
                         VALUES (:category_id, :region, :city, :avg_min, :avg_max, :median, :count)
                         ON DUPLICATE KEY UPDATE
                         avg_price_min = :avg_min,
                         avg_price_max = :avg_max,
                         median_price = :median,
                         sample_count = :count,
                         last_updated = NOW()";

            $updateStmt = $this->db->prepare($updateSql);
            return $updateStmt->execute([
                ':category_id' => $categoryId,
                ':region' => $region,
                ':city' => $city,
                ':avg_min' => $stats['avg_min'],
                ':avg_max' => $stats['avg_max'],
                ':median' => $median,
                ':count' => $stats['count']
            ]);
        }

        return false;
    }

    public function getPriceRange($categoryId, $region, $city = null) {
        $sql = "SELECT avg_price_min, avg_price_max, median_price, sample_count, last_updated
                FROM price_statistics
                WHERE category_id = :category_id";

        $params = [':category_id' => $categoryId];

        if ($city) {
            $sql .= " AND city = :city";
            $params[':city'] = $city;
        } else {
            $sql .= " AND region = :region AND city IS NULL";
            $params[':region'] = $region;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch();
    }

    public function getAllRegionStats($categoryId) {
        $sql = "SELECT region, city, avg_price_min, avg_price_max, median_price, sample_count
                FROM price_statistics
                WHERE category_id = :category_id
                ORDER BY region, city";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':category_id' => $categoryId]);
        return $stmt->fetchAll();
    }

    public function getEstimatedPrice($categoryId, $city) {
        $stats = $this->getPriceRange($categoryId, null, $city);

        if (!$stats || $stats['sample_count'] < 3) {
            // Fallback to regional stats
            $region = $this->extractRegion($city);
            $stats = $this->getPriceRange($categoryId, $region);
        }

        return $stats;
    }

    private function extractRegion($city) {
        // Simple extraction - could be improved with a region mapping table
        $postalPrefixes = [
            '75' => 'Île-de-France',
            '69' => 'Auvergne-Rhône-Alpes',
            '13' => 'Provence-Alpes-Côte d\'Azur',
            '31' => 'Occitanie',
            '44' => 'Pays de la Loire',
            '33' => 'Nouvelle-Aquitaine'
        ];

        foreach ($postalPrefixes as $prefix => $region) {
            if (strpos($city, $prefix) === 0) {
                return $region;
            }
        }

        return 'France';
    }

    public function compareWithMarket($projectId) {
        $sql = "SELECT p.*, c.name as category_name
                FROM projects p
                JOIN categories c ON p.category_id = c.id
                WHERE p.id = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $projectId]);
        $project = $stmt->fetch();

        if (!$project) {
            return null;
        }

        $marketStats = $this->getEstimatedPrice($project['category_id'], $project['city']);

        if (!$marketStats) {
            return ['project' => $project, 'market' => null, 'comparison' => 'insufficient_data'];
        }

        $projectAvg = ($project['budget_min'] + $project['budget_max']) / 2;
        $marketAvg = ($marketStats['avg_price_min'] + $marketStats['avg_price_max']) / 2;

        $difference = (($projectAvg - $marketAvg) / $marketAvg) * 100;

        $comparison = 'average';
        if ($difference > 20) {
            $comparison = 'above_market';
        } elseif ($difference < -20) {
            $comparison = 'below_market';
        }

        return [
            'project' => $project,
            'market' => $marketStats,
            'comparison' => $comparison,
            'difference_percent' => round($difference, 2)
        ];
    }
}
