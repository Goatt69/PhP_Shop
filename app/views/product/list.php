<?php 
// Include header.php
include __DIR__ . '/../header.php';
require_once('app/helpers/SessionHelper.php');
?>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto bg-white/90 p-8 rounded-3xl shadow-2xl glassmorphism">
        <!-- Header -->
        <h1 class="text-4xl md:text-5xl font-extrabold mb-10 text-gray-900 flex items-center gap-4 animate-pulse">
            <span>🛍️</span>
            <span class="bg-gradient-to-r from-purple-600 to-blue-600 bg-clip-text text-transparent">Danh sách sản phẩm</span>
        </h1>

        <!-- Intro and Buttons -->
        <div class="flex flex-col md:flex-row justify-between items-center mb-12 gap-6">
            <p class="text-gray-700 text-lg md:text-xl font-medium animate-fade-in">
                Khám phá bộ sưu tập sản phẩm độc đáo và chất lượng cao!
            </p>
            <div class="flex gap-4">
                <?php if (SessionHelper::isAdmin()): ?>
                    <a href="/ProductManager/Product/add" class="relative bg-gradient-to-r from-indigo-600 to-blue-500 text-white py-3 px-8 rounded-full shadow-lg text-lg font-semibold overflow-hidden transition-all duration-300 transform hover:scale-110 hover:shadow-xl glow-effect">
                        <span class="relative z-10">➕ Thêm sản phẩm mới</span>
                        <span class="absolute inset-0 bg-blue-700 opacity-0 hover:opacity-30 transition-opacity"></span>
                    </a>
                <?php endif; ?>
            </div>
        </div>

        <?php if (isset($_GET['search']) && !empty($_GET['search'])): ?>
            <div class="mb-2 text-gray-700">
                <p class="text-lg">
                    Kết quả tìm kiếm cho: <span class="font-semibold text-blue-500"><?php echo htmlspecialchars($_GET['search']); ?></span>
                    (<?php echo count($products); ?> kết quả)
                </p>
            </div>
        <?php endif; ?>

        <!-- Product Grid -->
        <?php if (empty($products)): ?>
            <p class="text-gray-600 text-center text-lg">
                <?php echo isset($_GET['search']) ? 'Không tìm thấy sản phẩm phù hợp.' : 'Hiện tại chưa có sản phẩm nào.'; ?>
            </p>
        <?php else: ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php foreach ($products as $product): ?>
                    <div class="relative bg-white/80 p-6 rounded-2xl shadow-xl glassmorphism hover:shadow-2xl transition-all duration-300 transform hover:scale-105 hover:rotate-1 group">
                        <!-- Product Header -->
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-2xl font-bold text-gray-900 group-hover:text-indigo-600 transition-colors">
                                <?php echo htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8'); ?>
                            </h2>
                            <span class="text-sm text-gray-400 italic bg-gray-100 px-2 py-1 rounded-full">
                                #<?php echo $product['id']; ?>
                            </span>
                        </div>

                        <!-- Description -->
                        <p class="text-gray-600 mt-2 text-md leading-relaxed line-clamp-3 group-hover:text-gray-800 transition-colors">
                            <?php echo htmlspecialchars($product['description'] ?? 'Không có mô tả', ENT_QUOTES, 'UTF-8'); ?>
                        </p>

                        <!-- Category -->
                        <p class="text-gray-600 mt-2 text-sm">
                            Danh mục: <?php echo htmlspecialchars($product['category_name'] ?? 'Không có', ENT_QUOTES, 'UTF-8'); ?>
                        </p>

                        <!-- Price -->
                        <p class="text-teal-600 font-bold text-2xl mt-4 flex items-center gap-2">
                            <span class="float-effect">💰</span>
                            <span class="bg-gradient-to-r from-teal-500 to-green-500 bg-clip-text text-transparent">
                                <?php echo number_format($product['price'], 0, ',', '.'); ?> đ
                            </span>
                        </p>

                        <!-- Image -->
                        <?php if (!empty($product['image'])): ?>
                            <img src="/ProductManager/public/<?php echo htmlspecialchars($product['image'], ENT_QUOTES, 'UTF-8'); ?>"
                                 alt="<?php echo htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8'); ?>" 
                                 class="mt-4 w-full h-auto object-cover rounded-xl">
                        <?php endif; ?>

                        <!-- Action Buttons -->
                        <div class="flex mt-3 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <?php if (SessionHelper::isAdmin()): ?>
                                <!-- Admin Actions -->
                                <div class="grid grid-cols-2 gap-2 w-full">
                                    <a href="/ProductManager/Product/edit/<?php echo $product['id']; ?>"
                                       class="bg-gradient-to-r from-yellow-400 to-orange-500 text-white py-2 px-5 rounded-full shadow-md hover:shadow-lg hover:scale-110 transition-all duration-200 flex items-center justify-center gap-1">
                                        ✏️ Sửa
                                    </a>
                                    <a href="/ProductManager/Product/delete/<?php echo $product['id']; ?>"
                                       onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?');"
                                       class="bg-gradient-to-r from-red-500 to-pink-600 text-white py-2 px-5 rounded-full shadow-md hover:shadow-lg hover:scale-110 transition-all duration-200 flex items-center justify-center gap-1">
                                        🗑️ Xóa
                                    </a>
                                    <a href="/ProductManager/Product/addToCart/<?php echo $product['id']; ?>"
                                       class="bg-gradient-to-r from-blue-500 to-indigo-600 text-white py-2 px-5 rounded-full col-span-2 flex items-center justify-center mt-2">
                                        🛒 Thêm vào giỏ
                                    </a>
                                </div>
                            <?php else: ?>
                                <!-- Regular User Actions -->
                                <a href="/ProductManager/Product/addToCart/<?php echo $product['id']; ?>"
                                   class="bg-gradient-to-r from-blue-500 to-indigo-600 text-white py-2 px-5 rounded-full w-full flex items-center justify-center">
                                    🛒 Thêm vào giỏ
                                </a>
                            <?php endif; ?>
                        </div>

                        <!-- Decorative Corner -->
                        <div class="absolute top-0 right-0 w-16 h-16 bg-gradient-to-br from-indigo-400 to-transparent opacity-20 rounded-bl-full pointer-events-none"></div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <?php if (isset($_GET['search']) && !empty($_GET['search'])): ?>
            <a href="/ProductManager/Product/list" class="inline-block mt-6 text-indigo-600 hover:text-indigo-800 transition-colors">
                ← Quay lại danh sách đầy đủ
            </a>
        <?php endif; ?>
    </div>

<?php 
// Include header.php
include __DIR__ . '/../footer.php'; 
?>