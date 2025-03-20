<?php
require_once('app/config/database.php');
require_once('app/models/AccountModel.php');

class AccountController {
    private $accountModel;
    private $db;

    public function __construct() {
        $this->db = (new Database())->getConnection();
        $this->accountModel = new AccountModel($this->db);
    }

    public function auth() {
        include_once 'app/views/account/auth.php';
    }

    public function register() {
        $this->auth(); // Redirect to auth page
    }

    public function login() {
        $this->auth(); // Redirect to auth page
    }

    public function save() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['username'] ?? '';
            $fullName = $_POST['fullname'] ?? $username;
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirmpassword'] ?? '';

            $registerErrors = [];
            if (empty($username)) {
                $registerErrors['username'] = "Vui lòng nhập tên đăng nhập!";
            }
            if (empty($fullName)) {
                $registerErrors[] = "Vui lòng nhập họ tên!";
            }
            if (empty($password)) {
                $registerErrors['password'] = "Vui lòng nhập mật khẩu!";
            } elseif (strlen($password) < 6) {
                $registerErrors['password'] = "Mật khẩu phải có ít nhất 6 ký tự!";
            }
            if ($password !== $confirmPassword) {
                $registerErrors['confirmPass'] = "Mật khẩu và xác nhận không khớp!";
            }

            $account = $this->accountModel->getAccountByUsername($username);
            if ($account) {
                $registerErrors['account'] = "Tài khoản này đã được đăng ký!";
            }

            if (count($registerErrors) > 0) {
                include_once 'app/views/account/auth.php';
            } else {
                // Removed fullName parameter since it's not stored in the database
                $result = $this->accountModel->save($username, $fullName, $password);

                if ($result) {
                    header('Location: /ProductManager/Account/auth');
                    exit;
                } else {
                    $registerErrors[] = "Đăng ký thất bại, vui lòng thử lại!";
                    include_once 'app/views/account/auth.php';
                }
            }
        }
    }

    public function checkLogin() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';
            $errors = [];

            if (empty($username)) {
                $errors['username'] = "Vui lòng nhập tên đăng nhập!";
            }
            if (empty($password)) {
                $errors['password'] = "Vui lòng nhập mật khẩu!";
            }

            if (empty($errors)) {
                $account = $this->accountModel->getAccountByUsername($username);

                // Debug information - you can remove this in production
                if (!$account) {
                    $errors[] = "Tài khoản không tồn tại!";
                } elseif (!password_verify($password, $account->password)) {
                    $errors[] = "Mật khẩu không đúng!";
                } else {
                    // Start session if not already started
                    if (session_status() == PHP_SESSION_NONE) {
                        session_start();
                    }

                    $_SESSION['username'] = $account->username;
                    $_SESSION['user_id'] = $account->id;
                    $_SESSION['fullname'] = $account->fullname;
                    $_SESSION['role'] = $account->role;

                    header('Location: /ProductManager/Product/list');
                    exit;
                }
            }

            include_once 'app/views/account/auth.php';
        }
    }

    public function logout() {
        session_start();
        unset($_SESSION['username']);
        unset($_SESSION['user_id']);
        unset($_SESSION['fullname']);
        unset($_SESSION['role']);
        header('Location: /ProductManager/Product/list');
        exit;
    }
}
