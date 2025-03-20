<?php
class DefaultController
{
    public function index()
    {
        // Hiển thị trang chủ đơn giản hoặc chuyển hướng
        $message = "Chào mừng đến với hệ thống quản lý sản phẩm và danh mục";
        include 'app/views/default/index.php'; // Gọi view tương ứng
    }
}
?>