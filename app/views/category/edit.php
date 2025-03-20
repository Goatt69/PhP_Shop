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
    <div class="max-w-2xl mx-auto bg-white/90 p-8 rounded-3xl shadow-2xl glassmorphism">
        <h1 class="text-3xl md:text-4xl font-extrabold mb-8 text-gray-900 flex items-center gap-3">
            <span class="bg-gradient-to-r from-green-600 to-teal-600 bg-clip-text text-transparent float-effect">✏️ Sửa danh mục</span>
        </h1>
        <form method="POST" action="/ProductManager/Category/edit/<?php echo $category['id']; ?>" class="space-y-6">
            <div>
                <label for="name" class="block text-lg font-medium text-gray-800 mb-2">Tên danh mục:</label>
                <input type="text" id="name" name="name"
                    value="<?php echo htmlspecialchars($category['name'], ENT_QUOTES, 'UTF-8'); ?>" required
                    class="w-full p-3 bg-white/50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 transition-all duration-300 glassmorphism">
            </div>
            <div>
                <label for="description" class="block text-lg font-medium text-gray-800 mb-2">Mô tả:</label>
                <textarea id="description" name="description"
                    class="w-full p-3 bg-white/50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 transition-all duration-300 glassmorphism min-h-[120px]"><?php echo htmlspecialchars($category['description'], ENT_QUOTES, 'UTF-8'); ?>
                </textarea>
            </div>
            <button type="submit"
                class="relative bg-gradient-to-r from-green-500 to-teal-600 text-white py-3 px-8 rounded-full shadow-lg text-lg font-semibold overflow-hidden transition-all duration-300 transform hover:scale-105 hover:shadow-xl glow-effect">
                <span class="relative z-10">Lưu thay đổi</span>
                <span class="absolute inset-0 bg-teal-700 opacity-0 hover:opacity-30 transition-opacity"></span>
            </button>
        </form>
        <a href="/ProductManager/Category/list"
            class="block mt-6 text-indigo-600 font-medium hover:text-indigo-800 transition-colors duration-300 flex items-center gap-2">
            <span class="float-effect">↩</span> Quay lại danh sách danh mục
        </a>
    </div>
<?php
// Include header.php
include __DIR__ . '/../footer.php';
?>