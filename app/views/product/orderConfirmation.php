<?php include __DIR__ . '/../header.php'; ?>

<?php
// Retrieve order details from session (or fallback if not set)
$order = $_SESSION['order_details'] ?? [];
$items = $order['items'] ?? [];
$total = $order['total'] ?? 0;
$customer_name = $order['customer_name'] ?? 'Khách hàng';
$phone = $order['phone'] ?? 'N/A';
$address = $order['address'] ?? 'N/A';

// Clear the order details from session after displaying
unset($_SESSION['order_details']);
?>

    <div class="max-w-3xl mx-auto bg-white/95 p-8 rounded-3xl shadow-xl glassmorphism border border-gray-100/50">
        <div class="text-center">
            <h1 class="text-4xl font-extrabold mb-6 bg-gradient-to-r from-green-600 to-teal-500 bg-clip-text text-transparent flex items-center justify-center gap-3">
                <span class="float-effect text-3xl">🎉</span> Đặt hàng thành công!
            </h1>
            <p class="text-gray-700 text-lg mb-8">Cảm ơn bạn đã mua sắm, <?php echo htmlspecialchars($customer_name); ?>! Đơn hàng của bạn đã được ghi nhận.</p>
        </div>

        <!-- Order Summary -->
        <div class="space-y-6">
            <div class="bg-gray-50/50 p-5 rounded-xl border border-gray-100 shadow-sm">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Chi tiết đơn hàng</h2>
                <div class="space-y-4 max-h-64 overflow-y-auto custom-scrollbar">
                    <?php foreach ($items as $id => $item): ?>
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

            <!-- Delivery Information -->
            <div class="p-5 bg-gray-50/50 rounded-xl border border-gray-100 shadow-sm">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Thông tin giao hàng</h2>
                <div class="text-gray-700 space-y-2">
                    <p><span class="font-medium">Họ tên:</span> <?php echo htmlspecialchars($customer_name); ?></p>
                    <p><span class="font-medium">Số điện thoại:</span> <?php echo htmlspecialchars($phone); ?></p>
                    <p><span class="font-medium">Địa chỉ:</span> <?php echo htmlspecialchars($address); ?></p>
                </div>
            </div>

            <!-- Back to Shop Button -->
            <div class="mt-8 text-center">
                <a href="/ProductManager/Product/list"
                   class="inline-block bg-gradient-to-r from-indigo-600 to-blue-500 text-white py-3 px-8 rounded-full shadow-lg hover:shadow-xl hover:from-indigo-700 hover:to-blue-600 transform hover:scale-105 transition-all duration-300">
                    Tiếp tục mua sắm
                </a>
            </div>
        </div>
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