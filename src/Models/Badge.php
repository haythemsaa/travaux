<?php

namespace App\Models;

use App\Config\Database;

class Badge {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function findAll() {
        $sql = "SELECT * FROM badges WHERE is_active = 1 ORDER BY display_order, name";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    public function findByType($type) {
        $sql = "SELECT * FROM badges WHERE type = :type AND is_active = 1 ORDER BY display_order";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':type' => $type]);
        return $stmt->fetchAll();
    }

    public function getArtisanBadges($artisanId) {
        $sql = "SELECT b.*, ab.verified_at, ab.expires_at, ab.is_active as badge_active
                FROM artisan_badges ab
                JOIN badges b ON ab.badge_id = b.id
                WHERE ab.artisan_id = :artisan_id
                AND ab.is_active = 1
                AND (ab.expires_at IS NULL OR ab.expires_at > NOW())
                ORDER BY b.display_order";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':artisan_id' => $artisanId]);
        return $stmt->fetchAll();
    }

    public function assignBadge($artisanId, $badgeId, $expiresAt = null, $document = null) {
        $sql = "INSERT INTO artisan_badges (artisan_id, badge_id, expires_at, verification_document)
                VALUES (:artisan_id, :badge_id, :expires_at, :document)
                ON DUPLICATE KEY UPDATE
                verified_at = NOW(),
                expires_at = :expires_at,
                verification_document = :document,
                is_active = 1";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':artisan_id' => $artisanId,
            ':badge_id' => $badgeId,
            ':expires_at' => $expiresAt,
            ':document' => $document
        ]);
    }

    public function revokeBadge($artisanId, $badgeId) {
        $sql = "UPDATE artisan_badges SET is_active = 0
                WHERE artisan_id = :artisan_id AND badge_id = :badge_id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':artisan_id' => $artisanId,
            ':badge_id' => $badgeId
        ]);
    }

    public function checkExpiredBadges() {
        $sql = "UPDATE artisan_badges SET is_active = 0
                WHERE expires_at IS NOT NULL AND expires_at < NOW() AND is_active = 1";
        $stmt = $this->db->query($sql);
        return $stmt->rowCount();
    }

    public function getArtisanBadgeCount($artisanId) {
        $sql = "SELECT COUNT(*) as count FROM artisan_badges
                WHERE artisan_id = :artisan_id
                AND is_active = 1
                AND (expires_at IS NULL OR expires_at > NOW())";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':artisan_id' => $artisanId]);
        $result = $stmt->fetch();
        return $result['count'];
    }
}
