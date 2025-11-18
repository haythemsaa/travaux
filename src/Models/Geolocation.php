<?php

namespace App\Models;

use App\Config\Database;
use PDO;

class Geolocation {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Calculate distance between two points (Haversine formula)
     * Returns distance in kilometers
     */
    public static function calculateDistance($lat1, $lon1, $lat2, $lon2) {
        $earthRadius = 6371; // Earth's radius in kilometers

        $lat1 = deg2rad($lat1);
        $lon1 = deg2rad($lon1);
        $lat2 = deg2rad($lat2);
        $lon2 = deg2rad($lon2);

        $deltaLat = $lat2 - $lat1;
        $deltaLon = $lon2 - $lon1;

        $a = sin($deltaLat / 2) * sin($deltaLat / 2) +
             cos($lat1) * cos($lat2) *
             sin($deltaLon / 2) * sin($deltaLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        $distance = $earthRadius * $c;

        return round($distance, 2);
    }

    /**
     * Find artisans within radius of a location
     */
    public function findArtisansNearby($latitude, $longitude, $radiusKm = 50, $categoryId = null) {
        // First, get a rough bounding box to limit the search
        // 1 degree latitude ≈ 111 km
        // 1 degree longitude ≈ 111 km * cos(latitude)
        $latDelta = $radiusKm / 111;
        $lonDelta = $radiusKm / (111 * cos(deg2rad($latitude)));

        $minLat = $latitude - $latDelta;
        $maxLat = $latitude + $latDelta;
        $minLon = $longitude - $lonDelta;
        $maxLon = $longitude + $lonDelta;

        $sql = "
            SELECT
                ap.*,
                u.first_name,
                u.last_name,
                u.email,
                u.phone,
                (
                    6371 * acos(
                        cos(radians(:latitude)) * cos(radians(ap.latitude)) *
                        cos(radians(ap.longitude) - radians(:longitude)) +
                        sin(radians(:latitude)) * sin(radians(ap.latitude))
                    )
                ) AS distance
            FROM artisan_profiles ap
            JOIN users u ON ap.user_id = u.id
            WHERE ap.latitude BETWEEN :min_lat AND :max_lat
            AND ap.longitude BETWEEN :min_lon AND :max_lon
            AND ap.latitude IS NOT NULL
            AND ap.longitude IS NOT NULL
        ";

        $params = [
            'latitude' => $latitude,
            'longitude' => $longitude,
            'min_lat' => $minLat,
            'max_lat' => $maxLat,
            'min_lon' => $minLon,
            'max_lon' => $maxLon
        ];

        // Add category filter if provided
        if ($categoryId) {
            $sql .= " AND EXISTS (
                SELECT 1 FROM artisan_specialties
                WHERE artisan_id = ap.user_id
                AND category_id = :category_id
            )";
            $params['category_id'] = $categoryId;
        }

        $sql .= " HAVING distance <= :radius
                  ORDER BY distance ASC";

        $params['radius'] = $radiusKm;

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Find projects within radius for an artisan
     */
    public function findProjectsNearby($latitude, $longitude, $radiusKm = 50, $categoryId = null) {
        $latDelta = $radiusKm / 111;
        $lonDelta = $radiusKm / (111 * cos(deg2rad($latitude)));

        $minLat = $latitude - $latDelta;
        $maxLat = $latitude + $latDelta;
        $minLon = $longitude - $lonDelta;
        $maxLon = $longitude + $lonDelta;

        $sql = "
            SELECT
                p.*,
                c.name as category_name,
                u.first_name as client_first_name,
                u.last_name as client_last_name,
                (
                    6371 * acos(
                        cos(radians(:latitude)) * cos(radians(p.latitude)) *
                        cos(radians(p.longitude) - radians(:longitude)) +
                        sin(radians(:latitude)) * sin(radians(p.latitude))
                    )
                ) AS distance
            FROM projects p
            JOIN categories c ON p.category_id = c.id
            JOIN users u ON p.client_id = u.id
            WHERE p.latitude BETWEEN :min_lat AND :max_lat
            AND p.longitude BETWEEN :min_lon AND :max_lon
            AND p.latitude IS NOT NULL
            AND p.longitude IS NOT NULL
            AND p.status = 'open'
        ";

        $params = [
            'latitude' => $latitude,
            'longitude' => $longitude,
            'min_lat' => $minLat,
            'max_lat' => $maxLat,
            'min_lon' => $minLon,
            'max_lon' => $maxLon
        ];

        if ($categoryId) {
            $sql .= " AND p.category_id = :category_id";
            $params['category_id'] = $categoryId;
        }

        $sql .= " HAVING distance <= :radius
                  ORDER BY distance ASC";

        $params['radius'] = $radiusKm;

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Geocode address to coordinates using Nominatim (OpenStreetMap)
     */
    public static function geocodeAddress($address, $city = null, $postalCode = null, $country = 'France') {
        $query = $address;

        if ($city) {
            $query .= ', ' . $city;
        }

        if ($postalCode) {
            $query .= ', ' . $postalCode;
        }

        if ($country) {
            $query .= ', ' . $country;
        }

        $url = 'https://nominatim.openstreetmap.org/search?' . http_build_query([
            'q' => $query,
            'format' => 'json',
            'limit' => 1,
            'addressdetails' => 1
        ]);

        $options = [
            'http' => [
                'method' => 'GET',
                'header' => 'User-Agent: TravauxPro/1.0'
            ]
        ];

        $context = stream_context_create($options);
        $response = @file_get_contents($url, false, $context);

        if ($response === false) {
            return null;
        }

        $data = json_decode($response, true);

        if (empty($data)) {
            return null;
        }

        return [
            'latitude' => (float)$data[0]['lat'],
            'longitude' => (float)$data[0]['lon'],
            'display_name' => $data[0]['display_name']
        ];
    }

    /**
     * Reverse geocode coordinates to address
     */
    public static function reverseGeocode($latitude, $longitude) {
        $url = 'https://nominatim.openstreetmap.org/reverse?' . http_build_query([
            'lat' => $latitude,
            'lon' => $longitude,
            'format' => 'json',
            'addressdetails' => 1
        ]);

        $options = [
            'http' => [
                'method' => 'GET',
                'header' => 'User-Agent: TravauxPro/1.0'
            ]
        ];

        $context = stream_context_create($options);
        $response = @file_get_contents($url, false, $context);

        if ($response === false) {
            return null;
        }

        $data = json_decode($response, true);

        if (empty($data)) {
            return null;
        }

        return [
            'display_name' => $data['display_name'],
            'address' => $data['address']
        ];
    }

    /**
     * Get artisans within service radius of a project
     */
    public function findArtisansForProject($projectId) {
        // Get project location
        $stmt = $this->db->prepare("
            SELECT latitude, longitude, category_id
            FROM projects
            WHERE id = :project_id
        ");
        $stmt->execute(['project_id' => $projectId]);
        $project = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$project || !$project['latitude'] || !$project['longitude']) {
            return [];
        }

        // Find artisans who:
        // 1. Have the required specialty
        // 2. Are within their service radius of the project
        $sql = "
            SELECT DISTINCT
                ap.*,
                u.first_name,
                u.last_name,
                u.email,
                (
                    6371 * acos(
                        cos(radians(:latitude)) * cos(radians(ap.latitude)) *
                        cos(radians(ap.longitude) - radians(:longitude)) +
                        sin(radians(:latitude)) * sin(radians(ap.latitude))
                    )
                ) AS distance
            FROM artisan_profiles ap
            JOIN users u ON ap.user_id = u.id
            JOIN artisan_specialties asp ON ap.user_id = asp.artisan_id
            WHERE asp.category_id = :category_id
            AND ap.latitude IS NOT NULL
            AND ap.longitude IS NOT NULL
            HAVING distance <= ap.service_radius_km
            ORDER BY distance ASC
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'latitude' => $project['latitude'],
            'longitude' => $project['longitude'],
            'category_id' => $project['category_id']
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Update project coordinates from address
     */
    public function updateProjectCoordinates($projectId) {
        $stmt = $this->db->prepare("
            SELECT address, city, postal_code, country_code
            FROM projects
            WHERE id = :project_id
        ");
        $stmt->execute(['project_id' => $projectId]);
        $project = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$project) {
            return false;
        }

        $coords = self::geocodeAddress(
            $project['address'],
            $project['city'],
            $project['postal_code'],
            $project['country_code']
        );

        if (!$coords) {
            return false;
        }

        $stmt = $this->db->prepare("
            UPDATE projects
            SET latitude = :latitude, longitude = :longitude
            WHERE id = :project_id
        ");

        return $stmt->execute([
            'latitude' => $coords['latitude'],
            'longitude' => $coords['longitude'],
            'project_id' => $projectId
        ]);
    }

    /**
     * Update artisan profile coordinates
     */
    public function updateArtisanCoordinates($userId) {
        $stmt = $this->db->prepare("
            SELECT address, city, postal_code, country_code
            FROM artisan_profiles
            WHERE user_id = :user_id
        ");
        $stmt->execute(['user_id' => $userId]);
        $profile = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$profile) {
            return false;
        }

        $coords = self::geocodeAddress(
            $profile['address'],
            $profile['city'],
            $profile['postal_code'],
            $profile['country_code']
        );

        if (!$coords) {
            return false;
        }

        $stmt = $this->db->prepare("
            UPDATE artisan_profiles
            SET latitude = :latitude, longitude = :longitude
            WHERE user_id = :user_id
        ");

        return $stmt->execute([
            'latitude' => $coords['latitude'],
            'longitude' => $coords['longitude'],
            'user_id' => $userId
        ]);
    }

    /**
     * Get statistics for a location (average prices, number of projects, etc.)
     */
    public function getLocationStats($latitude, $longitude, $radiusKm = 20, $categoryId = null) {
        $sql = "
            SELECT
                COUNT(*) as total_projects,
                AVG(budget_max) as avg_budget,
                MIN(budget_max) as min_budget,
                MAX(budget_max) as max_budget
            FROM projects
            WHERE latitude IS NOT NULL
            AND longitude IS NOT NULL
            AND (
                6371 * acos(
                    cos(radians(:latitude)) * cos(radians(latitude)) *
                    cos(radians(longitude) - radians(:longitude)) +
                    sin(radians(:latitude)) * sin(radians(latitude))
                )
            ) <= :radius
        ";

        $params = [
            'latitude' => $latitude,
            'longitude' => $longitude,
            'radius' => $radiusKm
        ];

        if ($categoryId) {
            $sql .= " AND category_id = :category_id";
            $params['category_id'] = $categoryId;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
