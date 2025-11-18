<?php

namespace App\Models;

use App\Config\Database;

class Analytics {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function trackEvent($userId, $eventType, $eventCategory, $eventData = []) {
        $sql = "INSERT INTO analytics_events (user_id, event_type, event_category, event_data, ip_address, user_agent)
                VALUES (:user_id, :event_type, :event_category, :event_data, :ip, :user_agent)";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':user_id' => $userId,
            ':event_type' => $eventType,
            ':event_category' => $eventCategory,
            ':event_data' => json_encode($eventData),
            ':ip' => $_SERVER['REMOTE_ADDR'] ?? null,
            ':user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? null
        ]);
    }

    public function getArtisanStats($artisanId, $days = 30) {
        $sql = "SELECT
                COUNT(DISTINCT CASE WHEN event_type = 'project_view' THEN event_data->>'$.project_id' END) as projects_viewed,
                COUNT(DISTINCT CASE WHEN event_type = 'quote_sent' THEN event_data->>'$.quote_id' END) as quotes_sent,
                COUNT(DISTINCT CASE WHEN event_type = 'quote_accepted' THEN event_data->>'$.quote_id' END) as quotes_accepted,
                COUNT(DISTINCT CASE WHEN event_type = 'profile_view' THEN id END) as profile_views,
                COUNT(DISTINCT CASE WHEN event_type = 'message_sent' THEN id END) as messages_sent
                FROM analytics_events
                WHERE user_id = :user_id
                AND created_at >= DATE_SUB(NOW(), INTERVAL :days DAY)";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':user_id' => $artisanId,
            ':days' => $days
        ]);
        return $stmt->fetch();
    }

    public function getClientStats($clientId, $days = 30) {
        $sql = "SELECT
                COUNT(DISTINCT CASE WHEN event_type = 'project_created' THEN event_data->>'$.project_id' END) as projects_created,
                COUNT(DISTINCT CASE WHEN event_type = 'quote_received' THEN event_data->>'$.quote_id' END) as quotes_received,
                COUNT(DISTINCT CASE WHEN event_type = 'artisan_view' THEN event_data->>'$.artisan_id' END) as artisans_viewed,
                COUNT(DISTINCT CASE WHEN event_type = 'message_sent' THEN id END) as messages_sent
                FROM analytics_events
                WHERE user_id = :user_id
                AND created_at >= DATE_SUB(NOW(), INTERVAL :days DAY)";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':user_id' => $clientId,
            ':days' => $days
        ]);
        return $stmt->fetch();
    }

    public function getConversionRate($artisanId, $days = 30) {
        $sql = "SELECT
                COUNT(DISTINCT CASE WHEN event_type = 'quote_sent' THEN event_data->>'$.quote_id' END) as quotes_sent,
                COUNT(DISTINCT CASE WHEN event_type = 'quote_accepted' THEN event_data->>'$.quote_id' END) as quotes_accepted
                FROM analytics_events
                WHERE user_id = :user_id
                AND created_at >= DATE_SUB(NOW(), INTERVAL :days DAY)";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':user_id' => $artisanId, ':days' => $days]);
        $result = $stmt->fetch();

        if ($result['quotes_sent'] > 0) {
            $rate = ($result['quotes_accepted'] / $result['quotes_sent']) * 100;
            return round($rate, 2);
        }
        return 0;
    }

    public function getActivityTimeline($userId, $limit = 10) {
        $sql = "SELECT event_type, event_category, event_data, created_at
                FROM analytics_events
                WHERE user_id = :user_id
                ORDER BY created_at DESC
                LIMIT :limit";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':user_id', $userId);
        $stmt->bindValue(':limit', (int)$limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getPopularCategories($days = 30) {
        $sql = "SELECT c.id, c.name, COUNT(*) as project_count
                FROM projects p
                JOIN categories c ON p.category_id = c.id
                WHERE p.created_at >= DATE_SUB(NOW(), INTERVAL :days DAY)
                GROUP BY c.id, c.name
                ORDER BY project_count DESC
                LIMIT 10";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':days' => $days]);
        return $stmt->fetchAll();
    }
}
