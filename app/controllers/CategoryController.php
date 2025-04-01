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

    private function checkAdminAccess()
    {
        if (!SessionHelper::isAdmin()) {
            // Redirect non-admin users to product list
            header('Location: /ProductManager/Product/list');
            exit;
        }
    }

    // List all categories
    public function list()
    {
        // The actual data will be loaded via API in the view
        include 'app/views/category/list.php';
    }

    // Show add category form
    public function add()
    {
        // Form submission will be handled by the API
        include 'app/views/category/add.php';
    }

    // Show edit category form
    public function edit($id)
    {
        $this->checkAdminAccess();

        // Get category data for the form
        $category = $this->categoryModel->getCategoryById($id);

        if (!$category) {
            echo "Không tìm thấy danh mục.";
            return;
        }

        // Form submission will be handled by the API
        include 'app/views/category/edit.php';
    }

    // Delete category (redirect to API)
    public function delete($id)
    {
        // Redirect to the list page after deletion
        // The actual deletion will be handled by JavaScript in the view
        header('Location: /ProductManager/Category/list');
        exit;
    }
}
?>