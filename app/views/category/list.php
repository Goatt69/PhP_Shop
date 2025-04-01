<?php
// Include header.php
include __DIR__ . '/../header.php';
require_once('app/helpers/SessionHelper.php');
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
        <!-- Loading indicator -->
        <div id="loading-indicator" class="text-center py-10">
            <p class="text-gray-600">Đang tải danh mục...</p>
        </div>

        <!-- Categories grid -->
        <div id="categories-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8" style="display: none;">
            <!-- Categories will be loaded here via JavaScript -->
        </div>

        <!-- No categories message -->
        <div id="no-categories" class="text-gray-600 text-center text-lg py-10" style="display: none;">
            Hiện tại chưa có danh mục nào.
        </div>

    </div>
<?php
// Include header.php
include __DIR__ . '/../footer.php';
?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Fetch categories from API
        fetch('/ProductManager/api/categories', {
            method: 'GET',
            headers: {
                'Authorization': 'Bearer ' + localStorage.getItem('jwt_token')
            }
        })
            .then(response => response.json())
            .then(data => {
                // Update condition to match API response structure
                if (data.success) {  // Changed from data.status
                    renderCategories(data.data);
                } else {
                    showError('Failed to load categories');
                }
            })
    });

    function renderCategories(categories) {
        const categoriesGrid = document.getElementById('categories-grid');
        const noCategories = document.getElementById('no-categories');
        const loadingIndicator = document.getElementById('loading-indicator');

        // Hide loading indicator
        loadingIndicator.style.display = 'none';

        if (categories.length === 0) {
            noCategories.style.display = 'block';
            return;
        }

        categoriesGrid.innerHTML = categories.map(category => `
            <div class="relative bg-white/80 p-6 rounded-2xl shadow-xl glassmorphism hover:shadow-2xl transition-all duration-300 transform hover:scale-105 hover:rotate-1 group">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-2xl font-bold text-gray-900 group-hover:text-indigo-600 transition-colors">
                        ${escapeHtml(category.name)}
                    </h2>
                    <span class="text-sm text-gray-400 italic bg-gray-100 px-2 py-1 rounded-full">
                        #${category.id}
                    </span>
                </div>
                <p class="text-gray-600 mt-2 text-md leading-relaxed line-clamp-3 group-hover:text-gray-800 transition-colors">
                    ${escapeHtml(category.description || '')}
                </p>
                <div class="flex space-x-4 mt-6 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                    <a href="/ProductManager/Category/edit/${category.id}"
                        class="bg-gradient-to-r from-yellow-400 to-orange-500 text-white py-2 px-5 rounded-full shadow-md hover:shadow-lg hover:scale-110 transition-all duration-200 flex items-center gap-1">
                        ✏️ Sửa
                    </a>
                    <button onclick="deleteCategory(${category.id})"
                        class="bg-gradient-to-r from-red-500 to-pink-600 text-white py-2 px-5 rounded-full shadow-md hover:shadow-lg hover:scale-110 transition-all duration-200 flex items-center gap-1">
                        🗑️ Xóa
                    </button>
                </div>
                <div class="absolute top-0 right-0 w-16 h-16 bg-gradient-to-br from-indigo-400 to-transparent opacity-20 rounded-bl-full pointer-events-none"></div>
            </div>
        `).join('');

        // Show the categories grid
        categoriesGrid.style.display = 'grid';
    }

    function deleteCategory(id) {
        if (confirm('Bạn có chắc chắn muốn xóa danh mục này?')) {
            fetch(`/ProductManager/api/categories/${id}`, {
                method: 'DELETE',
                headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem('jwt_token')
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Refresh the category list
                        location.reload();
                    } else {
                        alert('Không thể xóa danh mục: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error deleting category:', error);
                    alert('Đã xảy ra lỗi khi xóa danh mục.');
                });
        }
    }

    function showError(message) {
        const loadingIndicator = document.getElementById('loading-indicator');
        loadingIndicator.innerHTML = `<p class="text-red-500">${escapeHtml(message)}</p>`;
    }

    // Helper function to escape HTML
    function escapeHtml(text) {
        if (!text) return '';
        return String(text)
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }
</script>
