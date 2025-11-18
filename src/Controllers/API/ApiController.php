<?php

namespace App\Controllers\API;

use App\Config\Database;
use App\Utils\JWTAuth;
use App\Models\User;
use App\Models\Project;
use App\Models\Quote;
use App\Models\Message;
use App\Models\ArtisanProfile;
use App\Models\Review;
use App\Models\Notification;

class ApiController {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();

        // Set JSON headers
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization');

        // Handle preflight requests
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            http_response_code(200);
            exit;
        }
    }

    /**
     * Helper: Get JSON input
     */
    private function getJsonInput() {
        return json_decode(file_get_contents('php://input'), true);
    }

    /**
     * Helper: Send JSON response
     */
    private function json($data, $code = 200) {
        http_response_code($code);
        echo json_encode($data);
        exit;
    }

    /**
     * Helper: Error response
     */
    private function error($message, $code = 400) {
        $this->json([
            'success' => false,
            'error' => $message
        ], $code);
    }

    /**
     * Helper: Success response
     */
    private function success($data = [], $message = 'Success') {
        $this->json([
            'success' => true,
            'message' => $message,
            'data' => $data
        ]);
    }

    // ==========================================
    // AUTHENTICATION
    // ==========================================

    /**
     * POST /api/auth/register
     */
    public function register() {
        $input = $this->getJsonInput();

        $required = ['email', 'password', 'first_name', 'last_name', 'role'];
        foreach ($required as $field) {
            if (empty($input[$field])) {
                $this->error("Field '{$field}' is required");
            }
        }

        $userModel = new User();

        // Check if email exists
        if ($userModel->findByEmail($input['email'])) {
            $this->error('Email already exists', 409);
        }

        // Create user
        $userId = $userModel->create([
            'email' => $input['email'],
            'password' => password_hash($input['password'], PASSWORD_BCRYPT),
            'first_name' => $input['first_name'],
            'last_name' => $input['last_name'],
            'phone' => $input['phone'] ?? null,
            'role' => $input['role'],
            'country_code' => $input['country_code'] ?? 'FR'
        ]);

        if (!$userId) {
            $this->error('Failed to create user', 500);
        }

        // Generate token
        $token = JWTAuth::generateToken($userId, $input['email'], $input['role']);

        $this->success([
            'user_id' => $userId,
            'email' => $input['email'],
            'role' => $input['role'],
            'token' => $token
        ], 'Registration successful');
    }

    /**
     * POST /api/auth/login
     */
    public function login() {
        $input = $this->getJsonInput();

        if (empty($input['email']) || empty($input['password'])) {
            $this->error('Email and password are required');
        }

        $userModel = new User();
        $user = $userModel->findByEmail($input['email']);

        if (!$user || !password_verify($input['password'], $user['password'])) {
            $this->error('Invalid credentials', 401);
        }

        // Generate token
        $token = JWTAuth::generateToken($user['id'], $user['email'], $user['role']);

        $this->success([
            'user' => [
                'id' => $user['id'],
                'email' => $user['email'],
                'first_name' => $user['first_name'],
                'last_name' => $user['last_name'],
                'phone' => $user['phone'],
                'role' => $user['role'],
                'country_code' => $user['country_code']
            ],
            'token' => $token
        ], 'Login successful');
    }

    /**
     * POST /api/auth/refresh
     */
    public function refreshToken() {
        $user = JWTAuth::requireAuth();
        $newToken = JWTAuth::generateToken($user['user_id'], $user['email'], $user['role']);

        $this->success(['token' => $newToken]);
    }

    /**
     * GET /api/auth/me
     */
    public function getCurrentUser() {
        $authUser = JWTAuth::requireAuth();

        $userModel = new User();
        $user = $userModel->findById($authUser['user_id']);

        if (!$user) {
            $this->error('User not found', 404);
        }

        unset($user['password']);

        $this->success(['user' => $user]);
    }

    // ==========================================
    // PROJECTS
    // ==========================================

    /**
     * GET /api/projects
     */
    public function getProjects() {
        $authUser = JWTAuth::requireAuth();

        $projectModel = new Project();

        if ($authUser['role'] === 'client') {
            $projects = $projectModel->getByClientId($authUser['user_id']);
        } else {
            // For artisans, get available projects
            $status = $_GET['status'] ?? 'open';
            $projects = $projectModel->getByStatus($status);
        }

        $this->success(['projects' => $projects]);
    }

    /**
     * GET /api/projects/{id}
     */
    public function getProject($id) {
        $authUser = JWTAuth::requireAuth();

        $projectModel = new Project();
        $project = $projectModel->findById($id);

        if (!$project) {
            $this->error('Project not found', 404);
        }

        // Get custom fields
        $customFieldModel = new \App\Models\ProjectCustomField();
        $project['custom_fields'] = $customFieldModel->getProjectFields($id);

        // Get quotes
        $quoteModel = new Quote();
        $project['quotes'] = $quoteModel->getByProjectId($id);

        $this->success(['project' => $project]);
    }

    /**
     * POST /api/projects
     */
    public function createProject() {
        $authUser = JWTAuth::requireAuth();

        if ($authUser['role'] !== 'client') {
            $this->error('Only clients can create projects', 403);
        }

        $input = $this->getJsonInput();

        $required = ['title', 'description', 'category_id', 'address', 'postal_code', 'city'];
        foreach ($required as $field) {
            if (empty($input[$field])) {
                $this->error("Field '{$field}' is required");
            }
        }

        $projectModel = new Project();

        $projectId = $projectModel->create([
            'client_id' => $authUser['user_id'],
            'title' => $input['title'],
            'description' => $input['description'],
            'category_id' => $input['category_id'],
            'budget_min' => $input['budget_min'] ?? null,
            'budget_max' => $input['budget_max'] ?? null,
            'address' => $input['address'],
            'postal_code' => $input['postal_code'],
            'city' => $input['city'],
            'country_code' => $input['country_code'] ?? 'FR',
            'currency_code' => $input['currency_code'] ?? 'EUR',
            'preferred_date' => $input['preferred_date'] ?? null,
            'urgency' => $input['urgency'] ?? 'normal',
            'latitude' => $input['latitude'] ?? null,
            'longitude' => $input['longitude'] ?? null
        ]);

        if (!$projectId) {
            $this->error('Failed to create project', 500);
        }

        // Save custom fields if provided
        if (!empty($input['custom_fields'])) {
            $customFieldModel = new \App\Models\ProjectCustomField();
            $customFieldModel->saveProjectFields($projectId, $input['custom_fields']);
        }

        $this->success([
            'project_id' => $projectId
        ], 'Project created successfully');
    }

    /**
     * PUT /api/projects/{id}
     */
    public function updateProject($id) {
        $authUser = JWTAuth::requireAuth();

        $projectModel = new Project();
        $project = $projectModel->findById($id);

        if (!$project) {
            $this->error('Project not found', 404);
        }

        if ($project['client_id'] != $authUser['user_id']) {
            $this->error('Unauthorized', 403);
        }

        $input = $this->getJsonInput();

        $updated = $projectModel->update($id, $input);

        if (!$updated) {
            $this->error('Failed to update project', 500);
        }

        $this->success(['project_id' => $id], 'Project updated successfully');
    }

    /**
     * DELETE /api/projects/{id}
     */
    public function deleteProject($id) {
        $authUser = JWTAuth::requireAuth();

        $projectModel = new Project();
        $project = $projectModel->findById($id);

        if (!$project) {
            $this->error('Project not found', 404);
        }

        if ($project['client_id'] != $authUser['user_id']) {
            $this->error('Unauthorized', 403);
        }

        $deleted = $projectModel->delete($id);

        if (!$deleted) {
            $this->error('Failed to delete project', 500);
        }

        $this->success([], 'Project deleted successfully');
    }

    // ==========================================
    // QUOTES
    // ==========================================

    /**
     * GET /api/quotes
     */
    public function getQuotes() {
        $authUser = JWTAuth::requireAuth();

        $quoteModel = new Quote();

        if ($authUser['role'] === 'artisan') {
            $quotes = $quoteModel->getByArtisanId($authUser['user_id']);
        } else {
            $quotes = $quoteModel->getByClientId($authUser['user_id']);
        }

        $this->success(['quotes' => $quotes]);
    }

    /**
     * POST /api/quotes
     */
    public function createQuote() {
        $authUser = JWTAuth::requireAuth();

        if ($authUser['role'] !== 'artisan') {
            $this->error('Only artisans can create quotes', 403);
        }

        $input = $this->getJsonInput();

        $required = ['project_id', 'amount', 'description'];
        foreach ($required as $field) {
            if (empty($input[$field])) {
                $this->error("Field '{$field}' is required");
            }
        }

        $quoteModel = new Quote();

        $quoteId = $quoteModel->create([
            'project_id' => $input['project_id'],
            'artisan_id' => $authUser['user_id'],
            'amount' => $input['amount'],
            'description' => $input['description'],
            'estimated_duration' => $input['estimated_duration'] ?? null,
            'start_date' => $input['start_date'] ?? null,
            'payment_terms' => $input['payment_terms'] ?? null,
            'valid_until' => $input['valid_until'] ?? null,
            'currency_code' => $input['currency_code'] ?? 'EUR'
        ]);

        if (!$quoteId) {
            $this->error('Failed to create quote', 500);
        }

        $this->success(['quote_id' => $quoteId], 'Quote created successfully');
    }

    /**
     * PUT /api/quotes/{id}/accept
     */
    public function acceptQuote($id) {
        $authUser = JWTAuth::requireAuth();

        if ($authUser['role'] !== 'client') {
            $this->error('Only clients can accept quotes', 403);
        }

        $quoteModel = new Quote();
        $quote = $quoteModel->findById($id);

        if (!$quote) {
            $this->error('Quote not found', 404);
        }

        $updated = $quoteModel->updateStatus($id, 'accepted');

        if (!$updated) {
            $this->error('Failed to accept quote', 500);
        }

        $this->success(['quote_id' => $id], 'Quote accepted successfully');
    }

    /**
     * PUT /api/quotes/{id}/reject
     */
    public function rejectQuote($id) {
        $authUser = JWTAuth::requireAuth();

        if ($authUser['role'] !== 'client') {
            $this->error('Only clients can reject quotes', 403);
        }

        $quoteModel = new Quote();
        $updated = $quoteModel->updateStatus($id, 'rejected');

        if (!$updated) {
            $this->error('Failed to reject quote', 500);
        }

        $this->success(['quote_id' => $id], 'Quote rejected successfully');
    }

    // ==========================================
    // MESSAGES
    // ==========================================

    /**
     * GET /api/messages
     */
    public function getConversations() {
        $authUser = JWTAuth::requireAuth();

        $messageModel = new Message();
        $conversations = $messageModel->getConversations($authUser['user_id']);

        $this->success(['conversations' => $conversations]);
    }

    /**
     * GET /api/messages/{userId}
     */
    public function getMessages($userId) {
        $authUser = JWTAuth::requireAuth();

        $messageModel = new Message();
        $messages = $messageModel->getConversation($authUser['user_id'], $userId);

        // Mark as read
        $messageModel->markAsRead($authUser['user_id'], $userId);

        $this->success(['messages' => $messages]);
    }

    /**
     * POST /api/messages
     */
    public function sendMessage() {
        $authUser = JWTAuth::requireAuth();
        $input = $this->getJsonInput();

        if (empty($input['receiver_id']) || empty($input['message'])) {
            $this->error('Receiver ID and message are required');
        }

        $messageModel = new Message();

        $messageId = $messageModel->create([
            'sender_id' => $authUser['user_id'],
            'receiver_id' => $input['receiver_id'],
            'project_id' => $input['project_id'] ?? null,
            'message' => $input['message']
        ]);

        if (!$messageId) {
            $this->error('Failed to send message', 500);
        }

        $this->success(['message_id' => $messageId], 'Message sent successfully');
    }

    // ==========================================
    // ARTISANS
    // ==========================================

    /**
     * GET /api/artisans
     */
    public function searchArtisans() {
        $authUser = JWTAuth::requireAuth();

        $artisanModel = new ArtisanProfile();

        $filters = [
            'category_id' => $_GET['category_id'] ?? null,
            'city' => $_GET['city'] ?? null,
            'postal_code' => $_GET['postal_code'] ?? null,
            'rating_min' => $_GET['rating_min'] ?? null
        ];

        $artisans = $artisanModel->search($filters);

        $this->success(['artisans' => $artisans]);
    }

    /**
     * GET /api/artisans/{id}
     */
    public function getArtisan($id) {
        $authUser = JWTAuth::requireAuth();

        $artisanModel = new ArtisanProfile();
        $artisan = $artisanModel->getProfile($id);

        if (!$artisan) {
            $this->error('Artisan not found', 404);
        }

        $this->success(['artisan' => $artisan]);
    }

    // ==========================================
    // REVIEWS
    // ==========================================

    /**
     * POST /api/reviews
     */
    public function createReview() {
        $authUser = JWTAuth::requireAuth();

        if ($authUser['role'] !== 'client') {
            $this->error('Only clients can create reviews', 403);
        }

        $input = $this->getJsonInput();

        $required = ['artisan_id', 'project_id', 'rating'];
        foreach ($required as $field) {
            if (empty($input[$field])) {
                $this->error("Field '{$field}' is required");
            }
        }

        $reviewModel = new Review();

        $reviewId = $reviewModel->create([
            'client_id' => $authUser['user_id'],
            'artisan_id' => $input['artisan_id'],
            'project_id' => $input['project_id'],
            'rating' => $input['rating'],
            'quality_work' => $input['quality_work'] ?? null,
            'professionalism' => $input['professionalism'] ?? null,
            'communication' => $input['communication'] ?? null,
            'value_for_money' => $input['value_for_money'] ?? null,
            'comment' => $input['comment'] ?? null,
            'recommend' => $input['recommend'] ?? 0
        ]);

        if (!$reviewId) {
            $this->error('Failed to create review', 500);
        }

        $this->success(['review_id' => $reviewId], 'Review created successfully');
    }

    // ==========================================
    // NOTIFICATIONS
    // ==========================================

    /**
     * GET /api/notifications
     */
    public function getNotifications() {
        $authUser = JWTAuth::requireAuth();

        $notificationModel = new Notification();
        $notifications = $notificationModel->getByUserId($authUser['user_id']);

        $this->success(['notifications' => $notifications]);
    }

    /**
     * PUT /api/notifications/{id}/read
     */
    public function markNotificationRead($id) {
        $authUser = JWTAuth::requireAuth();

        $notificationModel = new Notification();
        $updated = $notificationModel->markAsRead($id);

        if (!$updated) {
            $this->error('Failed to mark notification as read', 500);
        }

        $this->success([], 'Notification marked as read');
    }

    /**
     * PUT /api/notifications/read-all
     */
    public function markAllNotificationsRead() {
        $authUser = JWTAuth::requireAuth();

        $notificationModel = new Notification();
        $notificationModel->markAllAsRead($authUser['user_id']);

        $this->success([], 'All notifications marked as read');
    }

    // ==========================================
    // UPLOAD
    // ==========================================

    /**
     * POST /api/upload
     */
    public function uploadImage() {
        $authUser = JWTAuth::requireAuth();

        if (empty($_FILES['image'])) {
            $this->error('No image file provided');
        }

        $file = $_FILES['image'];
        $uploadDir = __DIR__ . '/../../../public/uploads/';

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];

        if (!in_array($file['type'], $allowedTypes)) {
            $this->error('Invalid file type. Only JPG, JPEG, and PNG are allowed');
        }

        if ($file['size'] > 5000000) { // 5MB
            $this->error('File too large. Maximum size is 5MB');
        }

        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid() . '.' . $ext;
        $filepath = $uploadDir . $filename;

        if (!move_uploaded_file($file['tmp_name'], $filepath)) {
            $this->error('Failed to upload file', 500);
        }

        $this->success([
            'filename' => $filename,
            'url' => '/uploads/' . $filename
        ], 'Image uploaded successfully');
    }

    // ==========================================
    // STATISTICS (for dashboard)
    // ==========================================

    /**
     * GET /api/stats/dashboard
     */
    public function getDashboardStats() {
        $authUser = JWTAuth::requireAuth();

        $stats = [];

        if ($authUser['role'] === 'client') {
            $projectModel = new Project();
            $quoteModel = new Quote();

            $stats = [
                'total_projects' => $projectModel->countByClientId($authUser['user_id']),
                'active_projects' => $projectModel->countByClientId($authUser['user_id'], 'open'),
                'total_quotes' => $quoteModel->countByClientId($authUser['user_id']),
                'recent_projects' => $projectModel->getByClientId($authUser['user_id'], 5)
            ];
        } else {
            $quoteModel = new Quote();
            $analyticsModel = new \App\Models\Analytics();

            $stats = $analyticsModel->getArtisanStats($authUser['user_id'], 30);
            $stats['recent_quotes'] = $quoteModel->getByArtisanId($authUser['user_id'], 5);
        }

        $this->success(['stats' => $stats]);
    }
}
