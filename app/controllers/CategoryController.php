<?php
require_once ('app/config/database.php');
require_once ('app/models/CategoryModel.php');
require_once('app/helpers/SessionHelper.php');

class CategoryController
{
    private $categoryModel;
    private $db;

    public function __construct()
    {
        $this->db = (new Database())->getConnection();
        $this->categoryModel = new CategoryModel($this->db);
    }

    public function list()
    {
        if (!SessionHelper::isAdmin()) {
            header('Location: /ProductManager/Product/list');
            exit;
        }
        $categories = $this->categoryModel->getCategories();
        include 'app/views/category/list.php';
    }

    public function add()
    {
        if (!SessionHelper::isAdmin()) {
            header('Location: /ProductManager/Product/list');
            exit;
        }
        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';
            $result = $this->categoryModel->addCategory($name, $description);

            if (is_array($result)) {
                $errors = $result;
            } elseif ($result) {
                header('Location: /ProductManager/Category/list');
                exit;
            } else {
                $errors['general'] = 'Đã xảy ra lỗi khi thêm danh mục.';
            }
        }
        include 'app/views/category/add.php';
    }

    public function edit($id)
    {
        if (!SessionHelper::isAdmin()) {
            header('Location: /ProductManager/Product/list');
            exit;
        }
        $category = $this->categoryModel->getCategoryById($id);
        if (!$category) {
            echo "Không tìm thấy danh mục.";
            return;
        }

        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';
            $result = $this->categoryModel->updateCategory($id, $name, $description);

            if (is_array($result)) {
                $errors = $result;
            } elseif ($result) {
                header('Location: /ProductManager/Category/list');
                exit;
            } else {
                $errors['general'] = 'Đã xảy ra lỗi khi cập nhật danh mục.';
            }
        }
        include 'app/views/category/edit.php';
    }

    public function delete($id)
    {
        if (!SessionHelper::isAdmin()) {
            header('Location: /ProductManager/Product/list');
            exit;
        }
        if ($this->categoryModel->deleteCategory($id)) {
            header('Location: /ProductManager/Category/list');
            exit;
        } else {
            echo "Đã xảy ra lỗi khi xóa danh mục.";
        }
    }
}
?>