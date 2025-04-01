<?php
require_once('app/config/database.php');
require_once('app/models/CategoryModel.php');
require_once('app/middleware/JWTMiddleware.php');

class CategoryApiController
{
    private $categoryModel;
    private $db;
    private $jwtMiddleware;
    private $currentUser;

    public function __construct()
    {
        $this->db = (new Database())->getConnection();
        $this->categoryModel = new CategoryModel($this->db);
        $this->jwtMiddleware = new JWTMiddleware();

        // Set content type for all responses
        header('Content-Type: application/json');

        // Authenticate for protected routes
        if ($this->requiresAuth()) {
            $this->authenticate();
        }
    }

    // Helper method to determine if the current route requires authentication
    private function requiresAuth()
    {
        global $url; // Use the $url array from index.php
        $method = $_SERVER['REQUEST_METHOD'];

        // Public endpoints (no auth required)
        if ($method === 'GET' &&
            isset($url[0]) && $url[0] === 'api' &&
            isset($url[1]) && $url[1] === 'categories') {
            return false;
        }

        return true;
    }

    // Helper method to authenticate requests
    private function authenticate()
    {
        $this->currentUser = $this->jwtMiddleware->authenticate();

        if (!$this->currentUser) {
            $this->sendResponse(['success' => false, 'message' => 'Unauthorized - Invalid or missing token'], 401);
        }
    }

    // Helper method to check admin permissions
    private function requireAdmin()
    {
        if (!$this->jwtMiddleware->isAdmin($this->currentUser)) {
            $this->sendResponse(['success' => false, 'message' => 'Forbidden - Admin access required'], 403);
        }
    }

    // Helper method to send JSON responses
    private function sendResponse($data, $statusCode = 200)
    {
        http_response_code($statusCode);
        echo json_encode($data);
        exit;
    }

    // GET /api/categories
    public function index()
    {
        $categories = $this->categoryModel->getCategories();
        $this->sendResponse(['success' => true, 'data' => $categories]);
    }

    // GET /api/categories/{id}
    public function show($id)
    {
        $category = $this->categoryModel->getCategoryById($id);

        if ($category) {
            $this->sendResponse(['success' => true, 'data' => $category]);
        } else {
            $this->sendResponse(['success' => false, 'message' => 'Category not found'], 404);
        }
    }

    // POST /api/categories
    public function store()
    {
        $this->requireAdmin();

        // Get JSON input
        $data = json_decode(file_get_contents('php://input'), true);

        if (!$data) {
            // If no JSON, try POST data
            $data = $_POST;
        }

        if (empty($data)) {
            $this->sendResponse(['success' => false, 'message' => 'No data provided'], 400);
        }

        $name = $data['name'] ?? '';
        $description = $data['description'] ?? '';

        $result = $this->categoryModel->addCategory($name, $description);

        if (is_array($result)) {
            // Validation errors
            $this->sendResponse(['success' => false, 'errors' => $result], 422);
        } elseif ($result) {
            $this->sendResponse(['success' => true, 'message' => 'Category created successfully'], 201);
        } else {
            $this->sendResponse(['success' => false, 'message' => 'Failed to create category'], 500);
        }
    }

    // PUT /api/categories/{id}
    public function update($id)
    {
        $this->requireAdmin();

        // Get JSON input
        $data = json_decode(file_get_contents('php://input'), true);

        if (!$data) {
            // If no JSON, try POST data
            $data = $_POST;
        }

        if (empty($data)) {
            $this->sendResponse(['success' => false, 'message' => 'No data provided'], 400);
        }

        // Check if category exists
        $category = $this->categoryModel->getCategoryById($id);
        if (!$category) {
            $this->sendResponse(['success' => false, 'message' => 'Category not found'], 404);
        }

        $name = $data['name'] ?? '';
        $description = $data['description'] ?? '';

        $result = $this->categoryModel->updateCategory($id, $name, $description);

        if (is_array($result)) {
            // Validation errors
            $this->sendResponse(['success' => false, 'errors' => $result], 422);
        } elseif ($result) {
            $this->sendResponse(['success' => true, 'message' => 'Category updated successfully']);
        } else {
            $this->sendResponse(['success' => false, 'message' => 'Failed to update category'], 500);
        }
    }

    // DELETE /api/categories/{id}
    public function destroy($id)
    {
        $this->requireAdmin();

        // Check if category exists
        $category = $this->categoryModel->getCategoryById($id);
        if (!$category) {
            $this->sendResponse(['success' => false, 'message' => 'Category not found'], 404);
        }

        if ($this->categoryModel->deleteCategory($id)) {
            $this->sendResponse(['success' => true, 'message' => 'Category deleted successfully']);
        } else {
            $this->sendResponse(['success' => false, 'message' => 'Failed to delete category'], 500);
        }
    }
}
?>
