#!/usr/bin/env php
<?php
/**
 * Travaux Pro - Send Pending Notifications
 * Run every 5 minutes via cron: */5 * * * * /var/www/travaux/scripts/send-notifications.php
 */

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/Config/Database.php';

use App\Config\Database;

$db = Database::getInstance()->getConnection();

echo "========================================\n";
echo "  Travaux Pro - Send Notifications\n";
echo "  " . date('Y-m-d H:i:s') . "\n";
echo "========================================\n\n";

try {
    // Get pending email notifications (not sent yet)
    $stmt = $db->prepare("
        SELECT n.*, u.email, u.first_name, u.language_preference
        FROM notifications n
        JOIN users u ON n.user_id = u.id
        WHERE n.is_read = 0
        AND n.created_at >= DATE_SUB(NOW(), INTERVAL 1 HOUR)
        AND (n.email_sent = 0 OR n.email_sent IS NULL)
        LIMIT 100
    ");
    $stmt->execute();
    $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $sent = 0;
    $failed = 0;

    foreach ($notifications as $notification) {
        echo "Sending notification to {$notification['email']}... ";

        // Prepare email
        $subject = $notification['title'];
        $message = $notification['message'];

        // Add HTML template
        $html = "
        <!DOCTYPE html>
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: #3b82f6; color: white; padding: 20px; text-align: center; }
                .content { padding: 30px; background: #f9fafb; }
                .footer { text-align: center; padding: 20px; font-size: 12px; color: #6b7280; }
                .button { display: inline-block; padding: 12px 24px; background: #3b82f6; color: white; text-decoration: none; border-radius: 6px; margin: 20px 0; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>Travaux Pro</h1>
                </div>
                <div class='content'>
                    <h2>{$subject}</h2>
                    <p>{$message}</p>
                    <a href='https://your-domain.com' class='button'>Voir sur Travaux Pro</a>
                </div>
                <div class='footer'>
                    <p>© " . date('Y') . " Travaux Pro. Tous droits réservés.</p>
                    <p><a href='https://your-domain.com/unsubscribe'>Se désabonner</a></p>
                </div>
            </div>
        </body>
        </html>
        ";

        // Send email (using PHP mail or SMTP)
        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=UTF-8\r\n";
        $headers .= "From: Travaux Pro <noreply@travauxpro.com>\r\n";

        if (mail($notification['email'], $subject, $html, $headers)) {
            // Mark as sent
            $updateStmt = $db->prepare("UPDATE notifications SET email_sent = 1, email_sent_at = NOW() WHERE id = ?");
            $updateStmt->execute([$notification['id']]);

            echo "✓\n";
            $sent++;
        } else {
            echo "✗\n";
            $failed++;
        }

        // Rate limiting - wait 100ms between emails
        usleep(100000);
    }

    echo "\n========================================\n";
    echo "  Summary:\n";
    echo "  - Sent: $sent\n";
    echo "  - Failed: $failed\n";
    echo "  - Total: " . count($notifications) . "\n";
    echo "========================================\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}

exit(0);
