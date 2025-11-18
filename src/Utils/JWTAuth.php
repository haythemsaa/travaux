<?php

namespace App\Utils;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JWTAuth {
    private static $secretKey = 'YOUR_SECRET_KEY_CHANGE_IN_PRODUCTION_12345678';
    private static $algorithm = 'HS256';
    private static $expirationTime = 86400; // 24 hours

    /**
     * Generate JWT token
     */
    public static function generateToken($userId, $email, $role) {
        $issuedAt = time();
        $expirationTime = $issuedAt + self::$expirationTime;

        $payload = [
            'iat' => $issuedAt,
            'exp' => $expirationTime,
            'user_id' => $userId,
            'email' => $email,
            'role' => $role
        ];

        return JWT::encode($payload, self::$secretKey, self::$algorithm);
    }

    /**
     * Validate JWT token
     */
    public static function validateToken($token) {
        try {
            $decoded = JWT::decode($token, new Key(self::$secretKey, self::$algorithm));
            return (array) $decoded;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Get user from token
     */
    public static function getUserFromToken($token) {
        $decoded = self::validateToken($token);

        if (!$decoded) {
            return null;
        }

        return [
            'user_id' => $decoded['user_id'],
            'email' => $decoded['email'],
            'role' => $decoded['role']
        ];
    }

    /**
     * Get token from request headers
     */
    public static function getTokenFromRequest() {
        $headers = getallheaders();

        if (isset($headers['Authorization'])) {
            $authHeader = $headers['Authorization'];

            // Bearer token format
            if (preg_match('/Bearer\s+(.*)$/i', $authHeader, $matches)) {
                return $matches[1];
            }

            return $authHeader;
        }

        return null;
    }

    /**
     * Middleware: Require authentication
     */
    public static function requireAuth() {
        $token = self::getTokenFromRequest();

        if (!$token) {
            http_response_code(401);
            echo json_encode([
                'success' => false,
                'error' => 'Token not provided'
            ]);
            exit;
        }

        $user = self::getUserFromToken($token);

        if (!$user) {
            http_response_code(401);
            echo json_encode([
                'success' => false,
                'error' => 'Invalid or expired token'
            ]);
            exit;
        }

        return $user;
    }

    /**
     * Refresh token
     */
    public static function refreshToken($oldToken) {
        $user = self::getUserFromToken($oldToken);

        if (!$user) {
            return false;
        }

        return self::generateToken(
            $user['user_id'],
            $user['email'],
            $user['role']
        );
    }
}
