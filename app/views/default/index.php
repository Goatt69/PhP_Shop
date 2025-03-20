<?php require_once('app/helpers/SessionHelper.php'); ?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang chủ</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .glassmorphism {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
    </style>
</head>
<body class="bg-gradient-to-br from-indigo-100 via-purple-100 to-pink-100 min-h-screen p-6 md:p-12">
    <div class="max-w-4xl mx-auto bg-white/90 p-8 rounded-3xl shadow-2xl glassmorphism text-center">
        <h1 class="text-4xl font-extrabold mb-6 text-gray-900 bg-gradient-to-r from-purple-600 to-blue-600 bg-clip-text text-transparent">
            <?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?>
        </h1>
        <div class="flex justify-center gap-6">
            <a href="/ProductManager/Product/list" class="bg-gradient-to-r from-indigo-600 to-blue-500 text-white py-3 px-6 rounded-full shadow-lg hover:scale-105 transition-all">
                Xem danh sách sản phẩm
            </a>
            <?php if (SessionHelper::isAdmin()): ?>
                <a href="/ProductManager/Category/list" class="bg-gradient-to-r from-purple-600 to-indigo-500 text-white py-3 px-6 rounded-full shadow-lg hover:scale-105 transition-all">
                    Xem danh sách danh mục
                </a>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>