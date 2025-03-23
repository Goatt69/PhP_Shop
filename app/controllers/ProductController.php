<?php
require_once ('app/config/database.php');
require_once ('app/models/ProductModel.php');
require_once('app/helpers/SessionHelper.php');

class ProductController
{
    private $productModel;
    private $db;

    public function __construct()
    {
        $this->db = (new Database())->getConnection();
        $this->productModel = new ProductModel($this->db);
    }

    public function list()
    {
        $searchTerm = isset($_GET['search']) ? trim($_GET['search']) : '';
        $products = !empty($searchTerm) ?
            $this->productModel->searchProducts($searchTerm) :
            $this->productModel->getProducts();
        include 'app/views/product/list.php';
    }

    public function show($id)
    {
        $product = $this->productModel->getProductById($id);
        include 'app/views/product/show.php';
    }

    public function addToCart($id) {
        $product = $this->productModel->getProductById($id);
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
        if (isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id]['quantity']++;
        } else {
            $_SESSION['cart'][$id] = [
                'name' => $product['name'],
                'price' => $product['price'],
                'quantity' => 1,
                'image' => $product['image']
            ];
        }
        header('Location: /ProductManager/Product/cart');
    }

    public function cart() {
        $cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
        include 'app/views/product/cart.php';
    }

    public function checkout() {
        if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
            header('Location: /ProductManager/Product/cart');
            return;
        }
        include 'app/views/product/checkout.php';
    }

    public function processCheckout() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /ProductManager/Product/checkout');
            return;
        }

        $name = $_POST['name'];
        $phone = $_POST['phone'];
        $address = $_POST['address'];

        if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
            header('Location: /ProductManager/Product/cart');
            return;
        }

        $result = $this->productModel->processOrder($name, $phone, $address, $_SESSION['cart']);

        if ($result) {
            $_SESSION['order_details'] = [
                'customer_name' => $name,
                'phone' => $phone,
                'address' => $address,
                'items' => $_SESSION['cart'],
                'total' => array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $_SESSION['cart']))
            ];
            unset($_SESSION['cart']);
            header('Location: /ProductManager/Product/orderConfirmation');
        }
    }

    public function orderConfirmation() {
        include 'app/views/product/orderConfirmation.php';
    }

    public function updateQuantity($productId, $change) {
        if (!isset($_SESSION['cart'][$productId])) {
            echo json_encode(['success' => false]);
            return;
        }

        $newQuantity = $_SESSION['cart'][$productId]['quantity'] + $change;
        if ($newQuantity > 0) {
            $_SESSION['cart'][$productId]['quantity'] = $newQuantity;
        } else {
            unset($_SESSION['cart'][$productId]);
        }
        echo json_encode(['success' => true]);
    }
}
