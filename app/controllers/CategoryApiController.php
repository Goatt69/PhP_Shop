<?php
require_once('app/config/database.php');
require_once('app/models/CategoryModel.php');
require_once('app/helpers/SessionHelper.php');

class CategoryApiController
{
    private $categoryModel;
    private $db;

    public function __construct()
    {
        $this->db = (new Database())->getConnection();
        $this->categoryModel = new CategoryModel($this->db);

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

    // GET /api/categories
    public function index()
    {
        $categories = $this->categoryModel->getCategories();
        $this->sendResponse([
            'status' => 'success',
            'data' => $categories
        ]);
    }

    // GET /api/categories/{id}
    public function show($id)
    {
        $category = $this->categoryModel->getCategoryById($id);

        if ($category) {
            $this->sendResponse([
                'status' => 'success',
                'data' => $category
            ]);
        } else {
            $this->sendResponse([
                'status' => 'error',
                'message' => 'Category not found'
            ], 404);
        }
    }

    // POST /api/categories
    public function store()
    {
        if (!SessionHelper::isAdmin()) {
            $this->sendResponse([
                'status' => 'error',
                'message' => 'Unauthorized access'
            ], 403);
        }

        // Get JSON input
        $data = json_decode(file_get_contents('php://input'), true);

        if (!$data) {
            // If no JSON, try POST data
            $data = $_POST;
        }

        if (empty($data)) {
            $this->sendResponse([
                'status' => 'error',
                'message' => 'No data provided'
            ], 400);
        }

        $name = $data['name'] ?? '';
        $description = $data['description'] ?? '';

        $result = $this->categoryModel->addCategory($name, $description);

        if (is_array($result)) {
            // Validation errors
            $this->sendResponse([
                'status' => 'error',
                'errors' => $result
            ], 422);
        } elseif ($result) {
            $this->sendResponse([
                'status' => 'success',
                'message' => 'Category created successfully'
            ], 201);
        } else {
            $this->sendResponse([
                'status' => 'error',
                'message' => 'Failed to create category'
            ], 500);
        }
    }

    // PUT /api/categories/{id}
    public function update($id)
    {
        if (!SessionHelper::isAdmin()) {
            $this->sendResponse([
                'status' => 'error',
                'message' => 'Unauthorized access'
            ], 403);
        }

        // Get JSON input
        $data = json_decode(file_get_contents('php://input'), true);

        if (!$data) {
            // If no JSON, try POST data
            $data = $_POST;
        }

        if (empty($data)) {
            $this->sendResponse([
                'status' => 'error',
                'message' => 'No data provided'
            ], 400);
        }

        // Check if category exists
        $category = $this->categoryModel->getCategoryById($id);
        if (!$category) {
            $this->sendResponse([
                'status' => 'error',
                'message' => 'Category not found'
            ], 404);
        }

        $name = $data['name'] ?? '';
        $description = $data['description'] ?? '';

        $result = $this->categoryModel->updateCategory($id, $name, $description);

        if (is_array($result)) {
            // Validation errors
            $this->sendResponse([
                'status' => 'error',
                'errors' => $result
            ], 422);
        } elseif ($result) {
            $this->sendResponse([
                'status' => 'success',
                'message' => 'Category updated successfully'
            ]);
        } else {
            $this->sendResponse([
                'status' => 'error',
                'message' => 'Failed to update category'
            ], 500);
        }
    }

    // DELETE /api/categories/{id}
    public function destroy($id)
    {
        if (!SessionHelper::isAdmin()) {
            $this->sendResponse([
                'status' => 'error',
                'message' => 'Unauthorized access'
            ], 403);
        }

        // Check if category exists
        $category = $this->categoryModel->getCategoryById($id);
        if (!$category) {
            $this->sendResponse([
                'status' => 'error',
                'message' => 'Category not found'
            ], 404);
        }

        if ($this->categoryModel->deleteCategory($id)) {
            $this->sendResponse([
                'status' => 'success',
                'message' => 'Category deleted successfully'
            ]);
        } else {
            $this->sendResponse([
                'status' => 'error',
                'message' => 'Failed to delete category'
            ], 500);
        }
    }
}
?>
