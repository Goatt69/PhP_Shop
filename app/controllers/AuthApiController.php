<?php
require_once('app/config/database.php');
require_once('app/models/AccountModel.php');
require_once('app/utils/JWTHandler.php');

class AuthApiController
{
    private $accountModel;
    private $jwtHandler;
    private $db;

    public function __construct()
    {
        $this->db = (new Database())->getConnection();
        $this->accountModel = new AccountModel($this->db);
        $this->jwtHandler = new JWTHandler();

        // Set content type for all responses
        header('Content-Type: application/json');
    }

    // Helper method to send JSON responses
    private function sendResponse($data, $statusCode = 200)
    {
        http_response_code($statusCode);
        echo json_encode($data);
        exit;
    }

    // POST /api/auth/login
    public function login()
    {
        // Get JSON input
        $data = json_decode(file_get_contents('php://input'), true);

        if (!$data || !isset($data['username']) || !isset($data['password'])) {
            $this->sendResponse(['success' => false, 'message' => 'Invalid login data'], 400);
        }

        $username = $data['username'];
        $password = $data['password'];

        $account = $this->accountModel->getAccountByUsername($username);

        if (!$account) {
            $this->sendResponse(['success' => false, 'message' => 'Invalid username or password'], 401);
        }

        if (!password_verify($password, $account->password)) {
            $this->sendResponse(['success' => false, 'message' => 'Invalid username or password'], 401);
        }

        // Create JWT token
        $userData = [
            'id' => $account->id,
            'username' => $account->username,
            'fullname' => $account->fullname,
            'role' => $account->role
        ];

        $token = $this->jwtHandler->encode($userData);

        $this->sendResponse([
            'success' => true,
            'message' => 'Login successful',
            'token' => $token,
            'user' => [
                'id' => $account->id,
                'username' => $account->username,
                'fullname' => $account->fullname,
                'role' => $account->role
            ]
        ]);
    }

    // POST /api/auth/register
    public function register()
    {
        // Get JSON input
        $data = json_decode(file_get_contents('php://input'), true);

        if (!$data || !isset($data['username']) || !isset($data['password']) || !isset($data['fullname'])) {
            $this->sendResponse(['success' => false, 'message' => 'Invalid registration data'], 400);
        }

        $username = $data['username'];
        $fullname = $data['fullname'];
        $password = $data['password'];

        // Validate input
        $errors = [];
        if (empty($username)) {
            $errors['username'] = "Username is required";
        }
        if (empty($fullname)) {
            $errors['fullname'] = "Full name is required";
        }
        if (empty($password)) {
            $errors['password'] = "Password is required";
        } elseif (strlen($password) < 6) {
            $errors['password'] = "Password must be at least 6 characters";
        }

        if (count($errors) > 0) {
            $this->sendResponse(['success' => false, 'errors' => $errors], 422);
        }

        // Check if username already exists
        if ($this->accountModel->getAccountByUsername($username)) {
            $this->sendResponse(['success' => false, 'message' => 'Username already exists'], 409);
        }

        // Create account
        $result = $this->accountModel->save($username, $fullname, $password);

        if ($result) {
            $this->sendResponse(['success' => true, 'message' => 'Registration successful'], 201);
        } else {
            $this->sendResponse(['success' => false, 'message' => 'Failed to create account'], 500);
        }
    }
}
