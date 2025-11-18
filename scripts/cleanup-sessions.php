#!/usr/bin/env php
<?php
/**
 * Travaux Pro - Cleanup Expired Sessions and Data
 * Run daily via cron: 0 2 * * * /var/www/travaux/scripts/cleanup-sessions.php
 */

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/Config/Database.php';

use App\Config\Database;

$db = Database::getInstance()->getConnection();

echo "========================================\n";
echo "  Travaux Pro - Cleanup Script\n";
echo "  " . date('Y-m-d H:i:s') . "\n";
echo "========================================\n\n";

$totalDeleted = 0;

try {
    // 1. Delete expired quote validity
    echo "Cleaning expired quotes... ";
    $stmt = $db->prepare("
        UPDATE quotes
        SET status = 'expired'
        WHERE status = 'pending'
        AND valid_until < NOW()
    ");
    $stmt->execute();
    $expired = $stmt->rowCount();
    echo "$expired expired\n";
    $totalDeleted += $expired;

    // 2. Delete old read notifications (older than 90 days)
    echo "Cleaning old notifications... ";
    $stmt = $db->prepare("
        DELETE FROM notifications
        WHERE is_read = 1
        AND created_at < DATE_SUB(NOW(), INTERVAL 90 DAY)
    ");
    $stmt->execute();
    $deleted = $stmt->rowCount();
    echo "$deleted deleted\n";
    $totalDeleted += $deleted;

    // 3. Delete old analytics events (older than 1 year)
    echo "Cleaning old analytics... ";
    $stmt = $db->prepare("
        DELETE FROM analytics_events
        WHERE created_at < DATE_SUB(NOW(), INTERVAL 1 YEAR)
    ");
    $stmt->execute();
    $deleted = $stmt->rowCount();
    echo "$deleted deleted\n";
    $totalDeleted += $deleted;

    // 4. Delete unverified users (older than 7 days)
    echo "Cleaning unverified users... ";
    $stmt = $db->prepare("
        DELETE FROM users
        WHERE email_verified = 0
        AND created_at < DATE_SUB(NOW(), INTERVAL 7 DAY)
    ");
    $stmt->execute();
    $deleted = $stmt->rowCount();
    echo "$deleted deleted\n";
    $totalDeleted += $deleted;

    // 5. Delete orphaned messages (projects deleted)
    echo "Cleaning orphaned messages... ";
    $stmt = $db->prepare("
        DELETE FROM messages
        WHERE project_id IS NOT NULL
        AND project_id NOT IN (SELECT id FROM projects)
    ");
    $stmt->execute();
    $deleted = $stmt->rowCount();
    echo "$deleted deleted\n";
    $totalDeleted += $deleted;

    // 6. Optimize tables
    echo "Optimizing database tables...\n";
    $tables = ['users', 'projects', 'quotes', 'messages', 'notifications', 'reviews', 'analytics_events'];
    foreach ($tables as $table) {
        $db->exec("OPTIMIZE TABLE $table");
        echo "  - $table optimized\n";
    }

    // 7. Update statistics cache
    echo "Updating statistics cache... ";
    $stmt = $db->prepare("
        INSERT INTO cache (key_name, value, expires_at)
        VALUES ('stats_last_cleanup', NOW(), DATE_ADD(NOW(), INTERVAL 1 DAY))
        ON DUPLICATE KEY UPDATE value = NOW(), expires_at = DATE_ADD(NOW(), INTERVAL 1 DAY)
    ");
    $stmt->execute();
    echo "✓\n";

    echo "\n========================================\n";
    echo "  Cleanup completed successfully!\n";
    echo "  Total items deleted: $totalDeleted\n";
    echo "========================================\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}

exit(0);
