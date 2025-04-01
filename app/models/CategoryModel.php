<?php
class CategoryModel
{
    private $conn;
    private $table_name = "category";

    public $ID;
    public $Category;
    public $Description;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Get all categories
    public function getCategories()
    {
        $query = "SELECT id, name, description FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Return as associative array
    }

    // Get category by ID
    public function getCategoryById($id)
    {
        // Validate ID
        if (!is_numeric($id) || $id <= 0) {
            return false;
        }

        $query = "SELECT id, name, description FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC); // Return single row as array
    }

    // Add new category
    public function addCategory($name, $description)
    {
        $errors = [];
        if (empty($name)) {
            $errors['name'] = 'Tên danh mục không được để trống';
        } elseif (strlen($name) > 100) {
            $errors['name'] = 'Tên danh mục không được vượt quá 100 ký tự';
        }

        // Check if category name already exists
        if (!empty($name)) {
            $checkQuery = "SELECT COUNT(*) FROM " . $this->table_name . " WHERE name = :name";
            $checkStmt = $this->conn->prepare($checkQuery);
            $checkStmt->bindParam(':name', $name);
            $checkStmt->execute();

            if ($checkStmt->fetchColumn() > 0) {
                $errors['name'] = 'Tên danh mục đã tồn tại';
            }
        }

        if (count($errors) > 0) {
            return $errors;
        }

        $query = "INSERT INTO " . $this->table_name . " (name, description) VALUES (:name, :description)";
        $stmt = $this->conn->prepare($query);
        $name = strip_tags($name);
        $description = strip_tags($description ?? ''); // TEXT allows NULL
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);

        return $stmt->execute();
    }

    // Update category
    public function updateCategory($id, $name, $description)
    {
        $errors = [];
        if (empty($name)) {
            $errors['name'] = 'Tên danh mục không được để trống';
        } elseif (strlen($name) > 100) {
            $errors['name'] = 'Tên danh mục không được vượt quá 100 ký tự';
        }

        // Check if category name already exists (excluding current category)
        if (!empty($name)) {
            $checkQuery = "SELECT COUNT(*) FROM " . $this->table_name . " WHERE name = :name AND id != :id";
            $checkStmt = $this->conn->prepare($checkQuery);
            $checkStmt->bindParam(':name', $name);
            $checkStmt->bindParam(':id', $id, PDO::PARAM_INT);
            $checkStmt->execute();

            if ($checkStmt->fetchColumn() > 0) {
                $errors['name'] = 'Tên danh mục đã tồn tại';
            }
        }

        if (count($errors) > 0) {
            return $errors;
        }

        $query = "UPDATE " . $this->table_name . " SET name = :name, description = :description WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $name = strip_tags($name);
        $description = strip_tags($description ?? ''); // TEXT allows NULL
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    // Delete category
    public function deleteCategory($id)
    {
        // Check if category is in use by any products
        $checkQuery = "SELECT COUNT(*) FROM product WHERE category_id = :id";
        $checkStmt = $this->conn->prepare($checkQuery);
        $checkStmt->bindParam(':id', $id, PDO::PARAM_INT);
        $checkStmt->execute();

        if ($checkStmt->fetchColumn() > 0) {
            // Category is in use, cannot delete
            return false;
        }

        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
?>