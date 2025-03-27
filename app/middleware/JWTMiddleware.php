<?php
require_once('app/utils/JWTHandler.php');

class JWTMiddleware
{
    private $jwtHandler;

    public function __construct()
    {
        $this->jwtHandler = new JWTHandler();
    }

    /**
     * Authenticate the request using JWT
     *
     * @return array|null User data if authenticated, null otherwise
     */
    public function authenticate()
    {
        $headers = getallheaders();
        $authHeader = $headers['Authorization'] ?? '';

        if (empty($authHeader) || !preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
            return null;
        }

        $jwt = $matches[1];
        $userData = $this->jwtHandler->decode($jwt);

        if (isset($userData['error'])) {
            return null;
        }

        return $userData;
    }

    /**
     * Check if user has admin role
     *
     * @param array $userData User data from JWT
     * @return bool True if user is admin, false otherwise
     */
    public function isAdmin($userData)
    {
        return isset($userData['role']) && $userData['role'] === 'admin';
    }
}
