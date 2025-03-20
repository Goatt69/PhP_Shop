<?php
// Include header.php
include __DIR__ . '/../header.php';
require_once('app/helpers/SessionHelper.php');

// Redirect non-admin users
if (!SessionHelper::isAdmin()) {
    header('Location: /ProductManager/Product/list');
    exit;
}
?>

    <div class="max-w-7xl mx-auto bg-white/90 p-8 rounded-3xl shadow-2xl glassmorphism">
        <h1 class="text-4xl md:text-5xl font-extrabold mb-10 text-gray-900 flex items-center gap-4 animate-pulse">📑
            <span class="bg-gradient-to-r from-purple-600 to-blue-600 bg-clip-text text-transparent">Danh sách danh mục</span>
        </h1>
        <div class="flex flex-col md:flex-row justify-between items-center mb-12 gap-6">
            <p class="text-gray-700 text-lg md:text-xl font-medium animate-fade-in">
                Quản lý các danh mục sản phẩm của bạn!
            </p>
            <div class="flex gap-4">
                <a href="/ProductManager/Category/add"
                    class="relative bg-gradient-to-r from-indigo-600 to-blue-500 text-white py-3 px-8 rounded-full shadow-lg text-lg font-semibold overflow-hidden transition-all duration-300 transform hover:scale-110 hover:shadow-xl glow-effect">
                    <span class="relative z-10">➕ Thêm danh mục mới</span>
                    <span class="absolute inset-0 bg-blue-700 opacity-0 hover:opacity-30 transition-opacity"></span>
                </a>
            </div>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach ($categories as $category): ?>
                <div class="relative bg-white/80 p-6 rounded-2xl shadow-xl glassmorphism hover:shadow-2xl transition-all duration-300 transform hover:scale-105 hover:rotate-1 group">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-2xl font-bold text-gray-900 group-hover:text-indigo-600 transition-colors">
                            <?php echo htmlspecialchars($category['name'], ENT_QUOTES, 'UTF-8'); ?>
                        </h2>
                        <span class="text-sm text-gray-400 italic bg-gray-100 px-2 py-1 rounded-full">
                            #<?php echo $category['id']; ?>
                        </span>
                    </div>
                    <p class="text-gray-600 mt-2 text-md leading-relaxed line-clamp-3 group-hover:text-gray-800 transition-colors">
                        <?php echo htmlspecialchars($category['description'], ENT_QUOTES, 'UTF-8'); ?>
                    </p>
                    <div class="flex space-x-4 mt-6 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        <a href="/ProductManager/Category/edit/<?php echo $category['id']; ?>"
                            class="bg-gradient-to-r from-yellow-400 to-orange-500 text-white py-2 px-5 rounded-full shadow-md hover:shadow-lg hover:scale-110 transition-all duration-200 flex items-center gap-1">
                            ✏️ Sửa
                        </a>
                        <a href="/ProductManager/Category/delete/<?php echo $category['id']; ?>"
                            onclick="return confirm('Bạn có chắc chắn muốn xóa danh mục này?');"
                            class="bg-gradient-to-r from-red-500 to-pink-600 text-white py-2 px-5 rounded-full shadow-md hover:shadow-lg hover:scale-110 transition-all duration-200 flex items-center gap-1">
                            🗑️ Xóa
                        </a>
                    </div>
                    <div class="absolute top-0 right-0 w-16 h-16 bg-gradient-to-br from-indigo-400 to-transparent opacity-20 rounded-bl-full pointer-events-none"></div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php
// Include header.php
include __DIR__ . '/../footer.php';
?>