<?php
/**
 * Travaux Pro - Health Check Endpoint
 * Use for monitoring, load balancer health checks, uptime monitoring, etc.
 *
 * Returns JSON with system status
 * HTTP 200 = Healthy
 * HTTP 503 = Unhealthy
 */

header('Content-Type: application/json');

$health = [
    'status' => 'healthy',
    'timestamp' => date('Y-m-d H:i:s'),
    'checks' => []
];

$allHealthy = true;

// 1. Check PHP
$health['checks']['php'] = [
    'status' => 'ok',
    'version' => PHP_VERSION
];

// 2. Check Database Connection
try {
    require_once __DIR__ . '/../src/Config/Database.php';
    $db = App\Config\Database::getInstance()->getConnection();

    $stmt = $db->query('SELECT 1');
    $result = $stmt->fetch();

    $health['checks']['database'] = [
        'status' => 'ok',
        'connection' => 'connected'
    ];
} catch (Exception $e) {
    $health['checks']['database'] = [
        'status' => 'error',
        'message' => 'Database connection failed'
    ];
    $allHealthy = false;
}

// 3. Check Uploads Directory
$uploadsDir = __DIR__ . '/../uploads';
if (is_dir($uploadsDir) && is_writable($uploadsDir)) {
    $health['checks']['uploads'] = [
        'status' => 'ok',
        'writable' => true
    ];
} else {
    $health['checks']['uploads'] = [
        'status' => 'error',
        'writable' => false
    ];
    $allHealthy = false;
}

// 4. Check Storage Directory
$storageDir = __DIR__ . '/../storage';
if (is_dir($storageDir) && is_writable($storageDir)) {
    $health['checks']['storage'] = [
        'status' => 'ok',
        'writable' => true
    ];
} else {
    $health['checks']['storage'] = [
        'status' => 'error',
        'writable' => false
    ];
    $allHealthy = false;
}

// 5. Check required PHP extensions
$requiredExtensions = ['pdo', 'pdo_mysql', 'mbstring', 'json', 'curl'];
$missingExtensions = [];

foreach ($requiredExtensions as $ext) {
    if (!extension_loaded($ext)) {
        $missingExtensions[] = $ext;
    }
}

if (empty($missingExtensions)) {
    $health['checks']['php_extensions'] = [
        'status' => 'ok',
        'all_loaded' => true
    ];
} else {
    $health['checks']['php_extensions'] = [
        'status' => 'error',
        'missing' => $missingExtensions
    ];
    $allHealthy = false;
}

// 6. Check disk space
$freeSpace = disk_free_space('/');
$totalSpace = disk_total_space('/');
$usedPercent = (($totalSpace - $freeSpace) / $totalSpace) * 100;

if ($usedPercent < 90) {
    $health['checks']['disk_space'] = [
        'status' => 'ok',
        'used_percent' => round($usedPercent, 2),
        'free_gb' => round($freeSpace / 1024 / 1024 / 1024, 2)
    ];
} else {
    $health['checks']['disk_space'] = [
        'status' => 'warning',
        'used_percent' => round($usedPercent, 2),
        'free_gb' => round($freeSpace / 1024 / 1024 / 1024, 2),
        'message' => 'Disk space running low'
    ];
}

// 7. Check memory usage
$memoryUsage = memory_get_usage(true);
$memoryLimit = ini_get('memory_limit');

$health['checks']['memory'] = [
    'status' => 'ok',
    'usage_mb' => round($memoryUsage / 1024 / 1024, 2),
    'limit' => $memoryLimit
];

// Overall status
if (!$allHealthy) {
    $health['status'] = 'unhealthy';
    http_response_code(503);
} else {
    http_response_code(200);
}

echo json_encode($health, JSON_PRETTY_PRINT);
exit;
