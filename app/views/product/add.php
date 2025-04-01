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

    <!-- Main Content -->
    <div class="max-w-2xl mx-auto bg-white/90 p-8 rounded-3xl shadow-2xl glassmorphism">
        <h1 class="text-3xl md:text-4xl font-extrabold mb-8 text-gray-900 flex items-center gap-3">
            <span class="bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent float-effect">➕ Thêm sản phẩm mới</span>
        </h1>

        <!-- Error Messages -->
        <?php if (!empty($errors)): ?>
            <div class="bg-red-100/80 text-red-800 p-4 rounded-xl mb-6 glassmorphism animate-fade-in">
                <ul class="list-disc pl-5">
                    <?php foreach ($errors as $field => $error): ?>
                        <li><?php echo htmlspecialchars(is_array($error) ? implode(', ', $error) : $error, ENT_QUOTES, 'UTF-8'); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <!-- Form -->
        <form id="add-product-form" method="POST" action="/ProductManager/Product/save" enctype="multipart/form-data" onsubmit="return validateForm();" class="space-y-6">
            <div>
                <label for="name" class="block text-lg font-medium text-gray-800 mb-2">Tên sản phẩm:</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($_POST['name'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required
                       class="w-full p-3 bg-white/50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-300 glassmorphism">
            </div>
            <div>
                <label for="description" class="block text-lg font-medium text-gray-800 mb-2">Mô tả:</label>
                <textarea id="description" name="description" required
                          class="w-full p-3 bg-white/50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-300 glassmorphism min-h-[120px]"><?php echo htmlspecialchars($_POST['description'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
            </div>
            <div>
                <label for="price" class="block text-lg font-medium text-gray-800 mb-2">Giá:</label>
                <input type="number" id="price" name="price" step="0.01" value="<?php echo htmlspecialchars($_POST['price'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required
                       class="w-full p-3 bg-white/50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-300 glassmorphism">
            </div>
            <div>
                <label for="category_id" class="block text-lg font-medium text-gray-800 mb-2">Danh mục:</label>
                <select id="category_id" name="category_id" required
                        class="w-full p-3 bg-white/50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-300 glassmorphism">
                    <option value="" disabled <?php echo !isset($_POST['category_id']) ? 'selected' : ''; ?>>Chọn danh mục</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?php echo $category['id']; ?>" <?php echo ($_POST['category_id'] ?? '') == $category['id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($category['name'], ENT_QUOTES, 'UTF-8'); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label for="image" class="block text-lg font-medium text-gray-800 mb-2">Hình ảnh:</label>
                <input type="file" id="image" name="image" accept="image/*"
                       class="w-full p-3 bg-white/50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-300 glassmorphism">
            </div>
            <button type="submit" class="relative bg-gradient-to-r from-blue-500 to-indigo-600 text-white py-3 px-8 rounded-full shadow-lg text-lg font-semibold overflow-hidden transition-all duration-300 transform hover:scale-105 hover:shadow-xl glow-effect">
                <span class="relative z-10">Thêm sản phẩm</span>
                <span class="absolute inset-0 bg-indigo-700 opacity-0 hover:opacity-30 transition-opacity"></span>
            </button>
        </form>

        <!-- Back Link -->
        <a href="/ProductManager/Product/list" class="block mt-6 text-indigo-600 font-medium hover:text-indigo-800 transition-colors duration-300 flex items-center gap-2">
            <span class="float-effect">↩</span> Quay lại danh sách sản phẩm
        </a>
    </div>
<?php 
// Include header.php
include __DIR__ . '/../footer.php'; 
?>

<script>
    document.getElementById('add-product-form').addEventListener('submit', function(e) {
        e.preventDefault();

        // Create FormData object to handle file uploads
        const formData = new FormData(this);

        const token = '<?php echo $_SESSION["jwt_token"] ?? ""; ?>';

        fetch('/ProductManager/api/products', {
            method: 'POST',
            body: formData,
            headers: {
                'Authorization': 'Bearer ' + token
            }
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = '/ProductManager/Product/list';
                } else {
                    // Display errors
                    alert('Error: ' + (data.message || 'Failed to add product'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while adding the product');
            });
    });
</script>
