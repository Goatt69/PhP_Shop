<?php
require_once('app/utils/JWTHandler.php');

class SessionHelper {

    /**
     * Check if user is logged in
     *
     * @return bool True if user is logged in, false otherwise
     */
    public static function isLoggedIn() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
    }

    /**
     * Check if user is admin
     *
     * @return bool True if user is admin, false otherwise
     */
    public static function isAdmin() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
    }

    /**
     * Get current user data
     *
     * @return array|null User data if logged in, null otherwise
     */
    public static function getCurrentUser() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id'])) {
            return null;
        }

        return [
            'id' => $_SESSION['user_id'],
            'username' => $_SESSION['username'],
            'fullname' => $_SESSION['fullname'] ?? '',
            'role' => $_SESSION['role'] ?? 'user'
        ];
    }

    /**
     * Get JWT token from session or Authorization header
     *
     * @return string|null JWT token if available, null otherwise
     */
    public static function getToken() {
        // First check Authorization header (for API requests)
        $headers = getallheaders();
        $authHeader = $headers['Authorization'] ?? '';

        if (!empty($authHeader) && preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
            return $matches[1];
        }

        // Then check session (for web requests)
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        return $_SESSION['jwt_token'] ?? null;
    }
    /**
     * Validate JWT token and get user data
     *
     * @return array|null User data if token is valid, null otherwise
     */
    public static function validateToken() {
        $token = self::getToken();

        if (!$token) {
            return null;
        }

        $jwtHandler = new JWTHandler();
        $userData = $jwtHandler->decode($token);

        if (isset($userData['error'])) {
            return null;
        }

        return $userData;
    }
}?>