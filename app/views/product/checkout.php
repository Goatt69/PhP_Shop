<?php include __DIR__ . '/../header.php'; ?>

<?php
// Calculate total from cart
$total = 0;
foreach ($_SESSION['cart'] as $item) {
    $total += $item['price'] * $item['quantity'];
}
?>

    <div class="max-w-3xl mx-auto bg-white/95 p-8 rounded-3xl shadow-xl glassmorphism border border-gray-100/50">
        <h1 class="text-4xl md:text-5xl font-extrabold mb-2  text-gray-900 flex items-center gap-4">
            <span class="float-effect text-3xl">💳</span>
            <span class="text-4xl font-bold bg-gradient-to-r from-indigo-600 to-blue-500 bg-clip-text text-transparent">Thanh toán</span>
        </h1>

        <form action="/ProductManager/Product/processCheckout" method="POST" class="grid md:grid-cols-2 gap-8">
            <!-- Customer Information -->
            <div class="space-y-6">
                <h2 class="text-xl font-semibold text-gray-800">Thông tin giao hàng</h2>
                <div class="relative">
                    <input type="text"
                           id="name"
                           name="name"
                           required
                           class="w-full p-4 pl-10 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 text-gray-700 placeholder-gray-400 transition-all duration-300 peer"
                           placeholder="Họ tên">
                    <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 peer-focus:text-green-500 transition-colors">👤</span>
                </div>
                <div class="relative">
                    <input type="tel"
                           id="phone"
                           name="phone"
                           required
                           class="w-full p-4 pl-10 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 text-gray-700 placeholder-gray-400 transition-all duration-300 peer"
                           placeholder="Số điện thoại">
                    <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 peer-focus:text-green-500 transition-colors">📞</span>
                </div>
                <div class="relative">
                <textarea id="address"
                          name="address"
                          required
                          class="w-full p-4 pl-10 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 text-gray-700 placeholder-gray-400 transition-all duration-300 peer"
                          rows="4"
                          placeholder="Địa chỉ giao hàng"></textarea>
                    <span class="absolute left-3 top-4 text-gray-500 peer-focus:text-green-500 transition-colors">🏠</span>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="space-y-6">
                <h2 class="text-xl font-semibold text-gray-800">Tóm tắt đơn hàng</h2>
                <div class="bg-gray-50/50 p-5 rounded-xl border border-gray-100 shadow-sm">
                    <div class="space-y-4 max-h-64 overflow-y-auto custom-scrollbar">
                        <?php foreach ($_SESSION['cart'] as $id => $item): ?>
                            <div class="flex items-center justify-between p-3 bg-white rounded-lg shadow-sm hover:shadow-md transition-all duration-200">
                                <div class="flex items-center gap-3">
                                    <img src="/ProductManager/public/<?php echo $item['image']; ?>"
                                         alt="<?php echo htmlspecialchars($item['name']); ?>"
                                         class="w-12 h-12 object-cover rounded-md transform hover:scale-105 transition-transform">
                                    <span class="text-gray-800 font-medium"><?php echo htmlspecialchars($item['name']); ?>
                                    <span class="text-gray-500 text-sm">(x<?php echo $item['quantity']; ?>)</span>
                                </span>
                                </div>
                                <span class="text-green-600 font-semibold"><?php echo number_format($item['price'] * $item['quantity']); ?> đ</span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="border-t border-gray-200 mt-4 pt-4 flex justify-between items-center">
                        <span class="text-gray-700 font-semibold">Tổng cộng:</span>
                        <span class="text-2xl font-bold text-green-600"><?php echo number_format($total); ?> đ</span>
                    </div>
                </div>
                <button type="submit"
                        class="w-full bg-gradient-to-r from-green-500 to-teal-600 text-white py-3 px-6 rounded-full shadow-lg hover:shadow-xl hover:from-green-600 hover:to-teal-700 transform hover:scale-105 transition-all duration-300">
                    Xác nhận đặt hàng
                </button>
            </div>
        </form>
    </div>

    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 3px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #10b981;
            border-radius: 3px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #059669;
        }
    </style>

<?php include __DIR__ . '/../footer.php'; ?>