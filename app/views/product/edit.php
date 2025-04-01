<?php 
// Include header.php
include __DIR__ . '/../header.php';
if (!SessionHelper::isAdmin()) {
    header('Location: /ProductManager/Product/list');
    exit;
}
?>
    <!-- Main Content -->
    <div class="max-w-2xl mx-auto bg-white/90 p-8 rounded-3xl shadow-2xl glassmorphism">
        <h1 class="text-3xl md:text-4xl font-extrabold mb-8 text-gray-900 flex items-center gap-3">
            <span class="bg-gradient-to-r from-green-600 to-teal-600 bg-clip-text text-transparent float-effect">✏️ Sửa sản phẩm</span>
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
        <form id="edit-product-form" method="POST" action="/ProductManager/Product/update" enctype="multipart/form-data" onsubmit="return validateForm();" class="space-y-6">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($product['id'], ENT_QUOTES, 'UTF-8'); ?>">
            <div>
                <label for="name" class="block text-lg font-medium text-gray-800 mb-2">Tên sản phẩm:</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($product['name'] ?? $_POST['name'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required
                       class="w-full p-3 bg-white/50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 transition-all duration-300 glassmorphism">
            </div>
            <div>
                <label for="description" class="block text-lg font-medium text-gray-800 mb-2">Mô tả:</label>
                <textarea id="description" name="description" required
                          class="w-full p-3 bg-white/50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 transition-all duration-300 glassmorphism min-h-[120px]"><?php echo htmlspecialchars($product['description'] ?? $_POST['description'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
            </div>
            <div>
                <label for="price" class="block text-lg font-medium text-gray-800 mb-2">Giá:</label>
                <input type="number" id="price" name="price" step="0.01" value="<?php echo htmlspecialchars($product['price'] ?? $_POST['price'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required
                       class="w-full p-3 bg-white/50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 transition-all duration-300 glassmorphism">
            </div>
            <div>
                <label for="category_id" class="block text-lg font-medium text-gray-800 mb-2">Danh mục:</label>
                <select id="category_id" name="category_id" required
                        class="w-full p-3 bg-white/50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 transition-all duration-300 glassmorphism">
                    <option value="" disabled <?php echo !isset($product['category_id']) && !isset($_POST['category_id']) ? 'selected' : ''; ?>>Chọn danh mục</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?php echo $category['id']; ?>" <?php echo ($product['category_id'] ?? $_POST['category_id'] ?? '') == $category['id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($category['name'], ENT_QUOTES, 'UTF-8'); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label for="image" class="block text-lg font-medium text-gray-800 mb-2">Hình ảnh:</label>
                <?php if (!empty($product['image'])): ?>
                    <div class="mb-4">
                        <img src="/ProductManager/public/<?php echo htmlspecialchars($product['image'], ENT_QUOTES, 'UTF-8'); ?>"
                             alt="Current image"
                             class="w-48 h-auto object-cover rounded-xl">
                    </div>
                <?php endif; ?>
                <input type="file" id="image" name="image" accept="image/*"
                       class="w-full p-3 bg-white/50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 transition-all duration-300 glassmorphism">
            </div>
            <button type="submit" class="relative bg-gradient-to-r from-green-500 to-teal-600 text-white py-3 px-8 rounded-full shadow-lg text-lg font-semibold overflow-hidden transition-all duration-300 transform hover:scale-105 hover:shadow-xl glow-effect">
                <span class="relative z-10">Lưu thay đổi</span>
                <span class="absolute inset-0 bg-teal-700 opacity-0 hover:opacity-30 transition-opacity"></span>
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
    document.getElementById('edit-product-form').addEventListener('submit', function(e) {
        e.preventDefault();

        console.log('Form submitted');

        const productId = document.querySelector('input[name="id"]').value;
        console.log('Product ID:', productId);

        // Create FormData object to handle file uploads
        const formData = new FormData(this);
        formData.append('_method', 'PUT'); // Add method override

        // Add CSRF token if available
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        if (csrfToken) {
            formData.append('csrf_token', csrfToken);
        }

        // Show loading state
        const submitButton = this.querySelector('button[type="submit"]');
        const originalButtonText = submitButton.innerHTML;
        submitButton.innerHTML = '<span class="relative z-10 animate-pulse">Đang xử lý...</span>';
        submitButton.disabled = true;

        console.log('Sending request to:', `/ProductManager/api/products/${productId}`);
        const token = '<?php echo $_SESSION["jwt_token"] ?? ""; ?>';

        fetch(`/ProductManager/api/products/${productId}`, {
            method: 'POST',
            body: formData,
            headers: {
                'Authorization': 'Bearer ' + token
            }
        })
        .then(response => {
            console.log('Response status:', response.status);
            if (!response.ok) {
                console.log('Response not OK');
            }
            return response.json();
        })
        .then(data => {
            console.log('Response data:', data);
            if (data.success) {
                // Show success message before redirecting
                const successMessage = document.createElement('div');
                successMessage.className = 'bg-green-100 text-green-800 p-4 rounded-xl mb-6 animate-fade-in';
                successMessage.innerHTML = '<p>Sản phẩm đã được cập nhật thành công! Đang chuyển hướng...</p>';

                this.parentNode.insertBefore(successMessage, this);

                // Redirect after a short delay
                setTimeout(() => {
                    window.location.href = '/ProductManager/Product/list';
                }, 1500);
            } else {
                // Display errors
                let errorContainer = document.getElementById('error-messages');

                if (!errorContainer) {
                    errorContainer = document.createElement('div');
                    errorContainer.id = 'error-messages';
                    errorContainer.className = 'bg-red-100/80 text-red-800 p-4 rounded-xl mb-6 glassmorphism animate-fade-in';
                    this.parentNode.insertBefore(errorContainer, this);
                }

                let errorList = '<ul id="error-list" class="list-disc pl-5">';

                if (data.errors) {
                    Object.values(data.errors).forEach(error => {
                        errorList += `<li>${error}</li>`;
                    });
                } else if (data.message) {
                    errorList += `<li>${data.message}</li>`;
                } else {
                    errorList += '<li>Đã xảy ra lỗi khi cập nhật sản phẩm</li>';
                }

                errorList += '</ul>';
                errorContainer.innerHTML = errorList;
                errorContainer.style.display = 'block';

                // Restore button state
                submitButton.innerHTML = originalButtonText;
                submitButton.disabled = false;

                // Scroll to error messages
                errorContainer.scrollIntoView({ behavior: 'smooth' });
            }
        })
        .catch(error => {
            console.error('Error:', error);

            // Display generic error
            let errorContainer = document.getElementById('error-messages');

            if (!errorContainer) {
                errorContainer = document.createElement('div');
                errorContainer.id = 'error-messages';
                errorContainer.className = 'bg-red-100/80 text-red-800 p-4 rounded-xl mb-6 glassmorphism animate-fade-in';
                this.parentNode.insertBefore(errorContainer, this);
            }

            errorContainer.innerHTML = `
                <ul id="error-list" class="list-disc pl-5">
                    <li>Đã xảy ra lỗi khi cập nhật sản phẩm. Vui lòng thử lại sau.</li>
                    <li>Chi tiết lỗi: ${error.message}</li>
                </ul>
            `;
            errorContainer.style.display = 'block';

            // Restore button state
            submitButton.innerHTML = originalButtonText;
            submitButton.disabled = false;

            // Scroll to error messages
            errorContainer.scrollIntoView({ behavior: 'smooth' });
        });
    });

    // Optional: Add client-side validation
    function validateForm() {
        const name = document.getElementById('name').value.trim();
        const price = document.getElementById('price').value.trim();
        const category = document.getElementById('category_id').value;

        let isValid = true;
        let errorMessages = [];

        if (name === '') {
            errorMessages.push('Tên sản phẩm không được để trống');
            isValid = false;
        }

        if (price === '' || isNaN(price) || Number(price) <= 0) {
            errorMessages.push('Giá sản phẩm phải là số dương');
            isValid = false;
        }

        if (category === '') {
            errorMessages.push('Vui lòng chọn danh mục');
            isValid = false;
        }

        if (!isValid) {
            let errorContainer = document.getElementById('error-messages');

            if (!errorContainer) {
                errorContainer = document.createElement('div');
                errorContainer.id = 'error-messages';
                errorContainer.className = 'bg-red-100/80 text-red-800 p-4 rounded-xl mb-6 glassmorphism animate-fade-in';
                document.getElementById('edit-product-form').parentNode.insertBefore(errorContainer, document.getElementById('edit-product-form'));
            }

            let errorList = '<ul id="error-list" class="list-disc pl-5">';
            errorMessages.forEach(msg => {
                errorList += `<li>${msg}</li>`;
            });
            errorList += '</ul>';

            errorContainer.innerHTML = errorList;
            errorContainer.style.display = 'block';
            errorContainer.scrollIntoView({ behavior: 'smooth' });
        }

        return isValid;
    }
</script>
