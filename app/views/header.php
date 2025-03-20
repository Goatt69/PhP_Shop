<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?? 'Product Manager'; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        @keyframes float { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-10px); } }
        @keyframes glow { 0%, 100% { box-shadow: 0 0 5px rgba(59, 130, 246, 0.5); } 50% { box-shadow: 0 0 20px rgba(59, 130, 246, 0.8); } }
        .float-effect { animation: float 3s ease-in-out infinite; }
        .glow-effect:hover { animation: glow 1.5s ease-in-out infinite; }
        .glassmorphism { background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.2); }
    </style>
</head>
<body class="bg-gradient-to-br from-indigo-100 via-purple-100 to-pink-100 min-h-screen">
<header class="max-w-7xl mx-auto p-6 md:p-12">
    <nav class="flex justify-between items-center bg-white/90 p-4 rounded-3xl shadow-lg glassmorphism">
        <!-- Logo -->
        <a href="/ProductManager/" class="text-2xl font-bold text-indigo-600 hover:text-indigo-800 transition-colors">
            <span class="float-effect">🏠</span> Product Manager
        </a>

        <!-- Search Bar -->
        <form action="/ProductManager/Product/list" method="GET" class="flex-1 max-w-md mx-6 relative">
            <input type="text" name="search" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>"
                   placeholder="Tìm kiếm sản phẩm..."
                   class="w-full p-2 pl-10 rounded-full bg-gray-100 border border-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 text-gray-700 placeholder-gray-500" />
            <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">🔍</span>
        </form>

        <!-- Navigation and User Actions -->
        <div class="flex items-center gap-6">
            <a href="/ProductManager/Product/list" class="text-indigo-600 hover:text-indigo-800 font-medium transition-colors">Sản phẩm</a>
            <?php
            require_once 'app/helpers/SessionHelper.php';
            if (SessionHelper::isAdmin()):
                ?>
                <a href="/ProductManager/Category/list" class="text-indigo-600 hover:text-indigo-800 font-medium transition-colors">Danh mục</a>
            <?php endif; ?>

            <!-- Cart Icon - Preserved -->
            <a href="/ProductManager/Product/cart" class="relative text-indigo-600 hover:text-indigo-800 transition-colors glow-effect">
                <span class="text-2xl float-effect">🛒</span>
                <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center">
                    <?php echo isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0; ?>
                </span>
            </a>

            <!-- User Authentication -->
            <?php if (SessionHelper::isLoggedIn()): ?>
                <div class="flex items-center gap-2">
                    <div class="text-indigo-600 font-medium">
                        <?php echo htmlspecialchars($_SESSION['fullname'] ?? $_SESSION['username']); ?>
                        <?php if (SessionHelper::isAdmin()): ?>
                            <span class="bg-indigo-100 text-indigo-800 text-xs px-2 py-1 rounded-full ml-1">Admin</span>
                        <?php endif; ?>
                    </div>
                    <a href="/ProductManager/Account/logout" class="text-red-500 hover:text-red-700 transition-colors">
                        <i class="fas fa-sign-out-alt"></i>
                    </a>
                </div>
            <?php else: ?>
                <a href="/ProductManager/Account/login" class="text-indigo-600 hover:text-indigo-800 transition-colors">
                    <i class="fas fa-sign-in-alt"></i> Login
                </a>
            <?php endif; ?>
        </div>
    </nav>
</header>
