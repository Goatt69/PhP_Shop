<?php
class ProductModel
{
    private $conn;
    private $table_name = "product";

    public $ID;
    public $Name;
    public $Description;
    public $Price;
    public $CategoryID;
    public $Image;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getProducts()
    {
        $query = "SELECT p.id, p.name, p.description, p.price, p.image, p.category_id, c.name as category_name
                  FROM " . $this->table_name . " p
                  LEFT JOIN category c ON p.category_id = c.id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getProductById($id)
    {
        $query = "SELECT id, name, description, price, image, category_id 
                  FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function addProduct($name, $description, $price, $category_id, $image = null)
    {
        $errors = [];
        if (empty($name)) {
            $errors['name'] = 'Tên sản phẩm không được để trống';
        } elseif (strlen($name) > 100) {
            $errors['name'] = 'Tên sản phẩm không được vượt quá 100 ký tự';
        }
        if (!is_numeric($price) || $price < 0) {
            $errors['price'] = 'Giá sản phẩm phải là số dương';
        } elseif ($price > 9999999999.99) { // Max for DECIMAL(12,2)
            $errors['price'] = 'Giá sản phẩm không được vượt quá 9,999,999,999.99';
        }
        if (count($errors) > 0) {
            return $errors;
        }

        $query = "INSERT INTO " . $this->table_name . " (name, description, price, image, category_id) 
                  VALUES (:name, :description, :price, :image, :category_id)";
        $stmt = $this->conn->prepare($query);
        $name = strip_tags($name);
        $description = strip_tags($description ?? '');
        $price = floatval($price); // DECIMAL(12,2)
        $imagePath = $this->handleImageUpload($image);
        $category_id = $category_id ? intval($category_id) : null; // Allow NULL for foreign key

        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':price', $price); // Bind as float
        $stmt->bindParam(':image', $imagePath);
        $stmt->bindParam(':category_id', $category_id, PDO::PARAM_INT);

        return $stmt->execute() ? $this->conn->lastInsertId() : false;
    }

    public function updateProduct($id, $name, $description, $price, $category_id, $image = null)
    {
        $errors = [];
        if (empty($name)) {
            $errors['name'] = 'Tên sản phẩm không được để trống';
        } elseif (strlen($name) > 100) {
            $errors['name'] = 'Tên sản phẩm không được vượt quá 100 ký tự';
        }
        if (!is_numeric($price) || $price < 0) {
            $errors['price'] = 'Giá sản phẩm phải là số dương';
        } elseif ($price > 9999999999) {
            $errors['price'] = 'Giá sản phẩm không được vượt quá 9,999,999,999';
        }
        if (count($errors) > 0) {
            return $errors;
        }

        // Handle new image upload if provided
        $imagePath = null;
        if ($image) {
            $imagePath = $this->handleImageUpload($image);
        }

        // Build the query dynamically based on whether we have a new image
        $query = "UPDATE " . $this->table_name . " SET 
              name = :name, 
              description = :description, 
              price = :price, 
              category_id = :category_id";

        if ($imagePath) {
            $query .= ", image = :image";
        }

        $query .= " WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $name = strip_tags($name);
        $description = strip_tags($description ?? '');
        $price = floatval($price);
        $category_id = $category_id ? intval($category_id) : null;

        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':category_id', $category_id, PDO::PARAM_INT);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        if ($imagePath) {
            $stmt->bindParam(':image', $imagePath);
        }

        return $stmt->execute();
    }

    public function deleteProduct($id)
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    private function handleImageUpload($image)
    {
        if ($image && $image['error'] === UPLOAD_ERR_OK) {
            $uploadDir = 'public/uploads/';
            $fileName = uniqid() . '-' . basename($image['name']);
            $targetPath = $uploadDir . $fileName;
            $dbPath = 'uploads/' . $fileName;

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            if (move_uploaded_file($image['tmp_name'], $targetPath)) {
                return $dbPath; // File uploaded successfully
            } else {
                error_log("File upload failed: " . print_r($image, true));
            }
        }
        return null;
    }

    public function processOrder($name, $phone, $address, $cart) {
        try {
            $this->conn->beginTransaction();

            $query = "INSERT INTO orders (name, phone, address, created_at) VALUES (:name, :phone, :address, NOW())";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindParam(':phone', $phone, PDO::PARAM_STR);
            $stmt->bindParam(':address', $address, PDO::PARAM_STR);
            if (!$stmt->execute()) {
                throw new Exception("Failed to create order");
            }
            $orderId = $this->conn->lastInsertId();

            foreach ($cart as $productId => $item) {
                if (!is_numeric($productId) || !isset($item['quantity']) || !is_numeric($item['quantity']) || !isset($item['price']) || !is_numeric($item['price'])) {
                    throw new Exception("Invalid cart data for product ID: $productId");
                }
                $query = "INSERT INTO order_details (order_id, product_id, quantity, price) 
                      VALUES (:order_id, :product_id, :quantity, :price)";
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(':order_id', $orderId, PDO::PARAM_INT);
                $stmt->bindParam(':product_id', $productId, PDO::PARAM_INT);
                $stmt->bindParam(':quantity', $item['quantity'], PDO::PARAM_INT);
                $stmt->bindParam(':price', $item['price'], PDO::PARAM_STR); // DECIMAL(12,0) as string
                if (!$stmt->execute()) {
                    throw new Exception("Failed to create order detail for product ID: $productId");
                }
            }

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            error_log("Order processing error: " . $e->getMessage());
            echo "Error: " . $e->getMessage(); // Temporary for debugging
            return false;
        }
    }

    public function searchProducts($searchTerm)
    {
        $searchTerm = "%$searchTerm%";
        $query = "SELECT p.id, p.name, p.description, p.price, p.image, p.category_id, c.name as category_name
              FROM " . $this->table_name . " p
              LEFT JOIN category c ON p.category_id = c.id
              WHERE p.name LIKE :search 
              OR p.description LIKE :search";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':search', $searchTerm, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}



