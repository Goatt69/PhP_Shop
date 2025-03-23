<?php
require_once('app/config/database.php');
require_once('app/models/ProductModel.php');
require_once('app/models/CategoryModel.php');
require_once('app/helpers/SessionHelper.php');

class ProductApiController
{
    private $productModel;
    private $db;

    public function __construct()
    {
        $this->db = (new Database())->getConnection();
        $this->productModel = new ProductModel($this->db);

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

    // GET /api/products
    public function index()
    {
        $searchTerm = isset($_GET['search']) ? trim($_GET['search']) : '';

        if (!empty($searchTerm)) {
            $products = $this->productModel->searchProducts($searchTerm);
        } else {
            $products = $this->productModel->getProducts();
        }

        $this->sendResponse(['success' => true, 'data' => $products]);
    }

    // GET /api/products/{id}
    public function show($id)
    {
        $product = $this->productModel->getProductById($id);

        if ($product) {
            $this->sendResponse(['success' => true, 'data' => $product]);
        } else {
            $this->sendResponse(['success' => false, 'message' => 'Product not found'], 404);
        }
    }

    // POST /api/products
    public function store()
    {
        if (!SessionHelper::isAdmin()) {
            $this->sendResponse(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        // Get JSON input
        $data = json_decode(file_get_contents('php://input'), true);

        if (!$data) {
            $this->sendResponse(['success' => false, 'message' => 'Invalid JSON data'], 400);
        }

        $name = $data['name'] ?? '';
        $description = $data['description'] ?? '';
        $price = $data['price'] ?? '';
        $category_id = $data['category_id'] ?? null;
        $image = null; // Handle image upload separately if needed

        $result = $this->productModel->addProduct($name, $description, $price, $category_id, $image);

        if (is_array($result)) {
            // Validation errors
            $this->sendResponse(['success' => false, 'errors' => $result], 422);
        } elseif ($result) {
            $this->sendResponse(['success' => true, 'message' => 'Product created successfully', 'id' => $result], 201);
        } else {
            $this->sendResponse(['success' => false, 'message' => 'Failed to create product'], 500);
        }
    }

    // PUT /api/products/{id}
    public function update($id)
    {
        if (!SessionHelper::isAdmin()) {
            $this->sendResponse(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        // Get JSON input
        $data = json_decode(file_get_contents('php://input'), true);

        if (!$data) {
            $this->sendResponse(['success' => false, 'message' => 'Invalid JSON data'], 400);
        }

        $name = $data['name'] ?? '';
        $description = $data['description'] ?? '';
        $price = $data['price'] ?? '';
        $category_id = $data['category_id'] ?? null;
        $image = null; // Handle image upload separately if needed

        $result = $this->productModel->updateProduct($id, $name, $description, $price, $category_id, $image);

        if (is_array($result)) {
            // Validation errors
            $this->sendResponse(['success' => false, 'errors' => $result], 422);
        } elseif ($result) {
            $this->sendResponse(['success' => true, 'message' => 'Product updated successfully']);
        } else {
            $this->sendResponse(['success' => false, 'message' => 'Failed to update product'], 500);
        }
    }

    // DELETE /api/products/{id}
    public function destroy($id)
    {
        if (!SessionHelper::isAdmin()) {
            $this->sendResponse(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        if ($this->productModel->deleteProduct($id)) {
            $this->sendResponse(['success' => true, 'message' => 'Product deleted successfully']);
        } else {
            $this->sendResponse(['success' => false, 'message' => 'Failed to delete product'], 500);
        }
    }

    // POST /api/orders
    public function createOrder()
    {
        // Get JSON input
        $data = json_decode(file_get_contents('php://input'), true);

        if (!$data || !isset($data['name']) || !isset($data['phone']) || !isset($data['address']) || !isset($data['cart'])) {
            $this->sendResponse(['success' => false, 'message' => 'Invalid order data'], 400);
        }

        $name = $data['name'];
        $phone = $data['phone'];
        $address = $data['address'];
        $cart = $data['cart'];

        $result = $this->productModel->processOrder($name, $phone, $address, $cart);

        if ($result) {
            $this->sendResponse([
                'success' => true,
                'message' => 'Order processed successfully',
                'order_details' => [
                    'customer_name' => $name,
                    'phone' => $phone,
                    'address' => $address,
                    'items' => $cart,
                    'total' => array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cart))
                ]
            ], 201);
        } else {
            $this->sendResponse(['success' => false, 'message' => 'Failed to process order'], 500);
        }
    }
}
