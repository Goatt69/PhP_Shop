<?php
// Include header.php
include __DIR__ . '/../header.php';
require_once('app/helpers/SessionHelper.php');
?>
<div class="max-w-2xl mx-auto bg-white/90 p-8 rounded-3xl shadow-2xl glassmorphism">
    <h1 class="text-3xl md:text-4xl font-extrabold mb-8 text-gray-900 flex items-center gap-3">
        <span class="bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent float-effect">➕ Thêm danh mục mới</span>
    </h1>
    <!-- Error Messages -->
    <div id="error-messages" class="bg-red-100/80 text-red-800 p-4 rounded-xl mb-6 glassmorphism animate-fade-in" style="display: none;">
        <ul id="error-list" class="list-disc pl-5">
        </ul>
    </div>

    <form id="add-category-form" class="space-y-6">
        <div>
            <label for="name" class="block text-lg font-medium text-gray-800 mb-2">Tên danh mục:</label>
            <input type="text" id="name" name="name" required
                   class="w-full p-3 bg-white/50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-300 glassmorphism">
        </div>
        <div>
            <label for="description" class="block text-lg font-medium text-gray-800 mb-2">Mô tả:</label>
            <textarea id="description" name="description"
                      class="w-full p-3 bg-white/50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-300 glassmorphism min-h-[120px]"></textarea>
        </div>
        <button type="submit"
                class="relative bg-gradient-to-r from-blue-500 to-indigo-600 text-white py-3 px-8 rounded-full shadow-lg text-lg font-semibold overflow-hidden transition-all duration-300 transform hover:scale-105 hover:shadow-xl glow-effect">
            <span class="relative z-10">Thêm danh mục</span>
            <span class="absolute inset-0 bg-indigo-700 opacity-0 hover:opacity-30 transition-opacity"></span>
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

<script>
    document.getElementById('add-category-form').addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);
        const data = {
            name: formData.get('name'),
            description: formData.get('description')
        };

        fetch('/ProductManager/api/categories', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    window.location.href = '/ProductManager/Category/list';
                } else {
                    // Display errors
                    const errorMessages = document.getElementById('error-messages');
                    const errorList = document.getElementById('error-list');

                    errorList.innerHTML = '';

                    if (data.errors) {
                        Object.values(data.errors).forEach(error => {
                            const li = document.createElement('li');
                            li.textContent = error;
                            errorList.appendChild(li);
                        });
                    } else if (data.message) {
                        const li = document.createElement('li');
                        li.textContent = data.message;
                        errorList.appendChild(li);
                    }

                    errorMessages.style.display = 'block';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while adding the category');
            });
    });
</script>
