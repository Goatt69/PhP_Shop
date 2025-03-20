<?php include __DIR__ . '/../header.php'; ?>
    <div class="max-w-7xl mx-auto bg-white/90 p-8 rounded-3xl shadow-2xl glassmorphism">
        <h1 class="text-4xl md:text-5xl font-extrabold mb-10 text-gray-900 flex items-center gap-4 animate-pulse">
            <span class="text-4xl font-bold mb-8 bg-gradient-to-r from-indigo-600 to-blue-500 bg-clip-text text-transparent" >Giỏ hàng</span>
        </h1>
        <?php if (empty($cart)): ?>
            <div class="text-center py-12">
                <p class="text-gray-600 text-lg">Giỏ hàng của bạn đang trống</p>
                <a href="/ProductManager/product/list" class="mt-4 inline-block text-indigo-600 hover:text-indigo-800">Tiếp tục mua sắm</a>
            </div>
        <?php else: ?>
            <div class="space-y-6">
                <?php
                $total = 0;
                foreach ($cart as $id => $item):
                    $itemTotal = $item['price'] * $item['quantity'];
                    $total += $itemTotal;
                    ?>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 items-center p-6 bg-gray-50 hover:bg-gray-100 transition-colors rounded-xl border border-gray-100 shadow-sm hover:shadow-md">
                        <!-- Product Image -->
                        <div class="relative">
                            <img src="/ProductManager/public/<?php echo $item['image']; ?>"
                                 alt="<?php echo $item['name']; ?>"
                                 class="w-24 h-24 object-cover rounded-lg shadow-sm transform hover:scale-105 transition-transform">
                        </div>

                        <!-- Product Info -->
                        <div class="md:col-span-2">
                            <h3 class="text-xl font-semibold text-gray-800 hover:text-indigo-600 transition-colors">
                                <?php echo $item['name']; ?>
                            </h3>
                            <p class="text-gray-600 mt-1">Đơn giá: <?php echo number_format($item['price']); ?> đ</p>
                            <p class="text-indigo-600 font-semibold mt-1">Thành tiền: <?php echo number_format($itemTotal); ?> đ</p>
                        </div>

                        <!-- Quantity Controls -->
                        <div class="flex items-center justify-end gap-4">
                            <div class="flex items-center bg-white border border-gray-200 rounded-full p-1 shadow-sm">
                                <button onclick="updateQuantity(<?php echo $id; ?>, -1)"
                                        class="w-10 h-10 flex items-center justify-center  rounded-full transition-colors">
                                    <span class="text-xl">-</span>
                                </button>
                                <span class="w-12 text-center font-medium quantity"><?php echo $item['quantity']; ?></span>
                                <button onclick="updateQuantity(<?php echo $id; ?>, 1)"
                                        class="w-10 h-10 flex items-center justify-center  rounded-full transition-colors">
                                    <span class="text-xl">+</span>
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>

                <!-- Checkout Section -->
                <div class="mt-10 flex justify-end items-center gap-8">
                    <div class="text-right">
                        <span class="text-gray-600">Tổng cộng:</span>
                        <span class="text-2xl font-bold text-indigo-600"><?php echo number_format($total); ?> đ</span>
                    </div>
                    <a href="/ProductManager/Product/checkout"
                       class="bg-gradient-to-r from-indigo-600 to-blue-500 text-white py-3 px-10 rounded-full shadow-lg hover:shadow-xl transform hover:scale-105 transition-all">
                        Thanh toán
                    </a>
                </div>
            </div>

            <script>
                function updateQuantity(productId, change) {
                    fetch(`/ProductManager/Product/updateQuantity/${productId}/${change}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                location.reload();
                            } else {
                                alert('Cập nhật số lượng thất bại!');
                            }
                        })
                        .catch(error => console.error('Error:', error));
                }
            </script>
        <?php endif; ?>
    </div>
<?php include __DIR__ . '/../footer.php'; ?>