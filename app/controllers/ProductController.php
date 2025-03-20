<?php
require_once ('app/config/database.php');
require_once ('app/models/ProductModel.php');
require_once ('app/models/CategoryModel.php');
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

        if (!empty($searchTerm)) {
            $products = $this->productModel->searchProducts($searchTerm);
        } else {
            $products = $this->productModel->getProducts();
        }

        include 'app/views/product/list.php';
    }

    public function show($id)
    {
        $product = $this->productModel->getProductById($id);
        if ($product) {
            include 'app/views/product/show.php';
        } else {
            echo "Không thấy sản phẩm.";
        }
    }

    public function add()
    {
        if (!SessionHelper::isAdmin()) {
            header('Location: /ProductManager/Product/list');
            exit;
        }
        $categories = (new CategoryModel($this->db))->getCategories();
        include 'app/views/product/add.php';
    }

    public function save()
    {
        if (!SessionHelper::isAdmin()) {
            header('Location: /ProductManager/Product/list');
            exit;
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            error_log("File info: " . print_r($_FILES, true));

            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';
            $price = $_POST['price'] ?? '';
            $category_id = $_POST['category_id'] ?? null;
            $image = $_FILES['image'] ?? null;

            $result = $this->productModel->addProduct($name, $description, $price, $category_id, $image);

            if (is_array($result)) {
                $errors = $result;
                $categories = (new CategoryModel($this->db))->getCategories();
                include 'app/views/product/add.php';
            } elseif ($result) {
                header('Location: /ProductManager/Product/list');
                exit;
            } else {
                $errors['general'] = 'Đã xảy ra lỗi khi thêm sản phẩm.';
                $categories = (new CategoryModel($this->db))->getCategories();
                include 'app/views/product/add.php';
            }
        }
    }

    public function edit($id)
    {
        if (!SessionHelper::isAdmin()) {
            header('Location: /ProductManager/Product/list');
            exit;
        }
        $product = $this->productModel->getProductById($id);
        $categories = (new CategoryModel($this->db))->getCategories();
        if ($product) {
            include 'app/views/product/edit.php';
        } else {
            echo "Không thấy sản phẩm.";
        }
    }

    public function update()
    {
        if (!SessionHelper::isAdmin()) {
            header('Location: /ProductManager/Product/list');
            exit;
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? '';
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';
            $price = $_POST['price'] ?? '';
            $category_id = $_POST['category_id'] ?? null;
            $image = $_FILES['image'] ?? null;

            $result = $this->productModel->updateProduct($id, $name, $description, $price, $category_id, $image);
            if (is_array($result)) {
                $errors = $result;
                $product = $this->productModel->getProductById($id);
                $categories = (new CategoryModel($this->db))->getCategories();
                include 'app/views/product/edit.php';
            } elseif ($result) {
                header('Location: /ProductManager/Product/list');
                exit;
            } else {
                echo "Đã xảy ra lỗi khi cập nhật sản phẩm.";
            }
        }
    }

    public function delete($id)
    {
        if (!SessionHelper::isAdmin()) {
            header('Location: /ProductManager/Product/list');
            exit;
        }
        if ($this->productModel->deleteProduct($id)) {
            header('Location: /ProductManager/Product/list');
            exit;
        } else {
            echo "Đã xảy ra lỗi khi xóa sản phẩm.";
        }
    }

    public function addToCart($id) {
        $product = $this->productModel->getProductById($id);

        if (!$product) {
            echo "Không tìm thấy sản phẩm.";
            return;
        }

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
            echo "Giỏ hàng trống.";
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
        } else {
            echo "Đã xảy ra lỗi khi xử lý đơn hàng";
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
?>