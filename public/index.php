<?php

// Start session
session_start();

// Autoloader
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $baseDir = __DIR__ . '/../src/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relativeClass = substr($class, $len);
    $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

// Load config
$config = require __DIR__ . '/../src/Config/config.php';

// Set timezone
date_default_timezone_set($config['app']['timezone']);

// Error reporting
if ($config['app']['debug']) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Import classes
use App\Config\Router;
use App\Controllers\HomeController;
use App\Controllers\AuthController;
use App\Controllers\ClientController;
use App\Controllers\ArtisanController;
use App\Middleware\AuthMiddleware;
use App\Middleware\GuestMiddleware;

// Create router
$router = new Router();

// Public routes
$router->get('/', [HomeController::class, 'index']);
$router->get('/projects', [HomeController::class, 'projects']);
$router->get('/projects/{id}', [HomeController::class, 'projectDetail']);
$router->get('/how-it-works', [HomeController::class, 'howItWorks']);
$router->get('/about', [HomeController::class, 'about']);
$router->get('/contact', [HomeController::class, 'contact']);

// Auth routes (guest only)
$router->get('/login', [AuthController::class, 'showLogin'], [GuestMiddleware::class]);
$router->post('/login', [AuthController::class, 'login'], [GuestMiddleware::class]);
$router->get('/register', [AuthController::class, 'showRegister'], [GuestMiddleware::class]);
$router->post('/register', [AuthController::class, 'register'], [GuestMiddleware::class]);
$router->get('/logout', [AuthController::class, 'logout']);

// Client routes (auth required)
$router->get('/client/dashboard', [ClientController::class, 'dashboard'], [AuthMiddleware::class]);
$router->get('/client/projects/create', [ClientController::class, 'createProjectForm'], [AuthMiddleware::class]);
$router->post('/client/projects/create', [ClientController::class, 'createProject'], [AuthMiddleware::class]);
$router->get('/client/projects/{id}', [ClientController::class, 'viewProject'], [AuthMiddleware::class]);
$router->post('/client/quotes/{id}/accept', [ClientController::class, 'acceptQuote'], [AuthMiddleware::class]);
$router->post('/client/quotes/{id}/reject', [ClientController::class, 'rejectQuote'], [AuthMiddleware::class]);

// Artisan routes (auth required)
$router->get('/artisan/dashboard', [ArtisanController::class, 'dashboard'], [AuthMiddleware::class]);
$router->get('/artisan/projects', [ArtisanController::class, 'projects'], [AuthMiddleware::class]);
$router->get('/artisan/projects/{id}', [ArtisanController::class, 'viewProject'], [AuthMiddleware::class]);
$router->post('/artisan/projects/{id}/unlock', [ArtisanController::class, 'unlockProject'], [AuthMiddleware::class]);
$router->get('/artisan/projects/{id}/quote', [ArtisanController::class, 'submitQuoteForm'], [AuthMiddleware::class]);
$router->post('/artisan/quotes/submit', [ArtisanController::class, 'submitQuote'], [AuthMiddleware::class]);
$router->get('/artisan/profile', [ArtisanController::class, 'profile'], [AuthMiddleware::class]);
$router->post('/artisan/profile', [ArtisanController::class, 'updateProfile'], [AuthMiddleware::class]);

// Dispatch
$router->dispatch();
