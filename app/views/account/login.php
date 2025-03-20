<?php include 'app/views/header.php'; ?>

    <section class=" bg-gradient-to-br from-indigo-100 via-purple-100 to-pink-100 flex items-center justify-center p-6 md:p-12">
        <div class="max-w-md w-full bg-white/90 p-8 rounded-3xl shadow-2xl glassmorphism">
            <h2 class="text-4xl font-extrabold mb-6 text-gray-900 text-center bg-gradient-to-r from-purple-600 to-blue-600 bg-clip-text text-transparent animate-pulse">
                Đăng nhập
            </h2>
            <p class="text-gray-700 text-lg text-center mb-8">Vui lòng nhập thông tin đăng nhập của bạn!</p>

            <form id="loginForm" action="/ProductManager/Account/checklogin" method="post" onsubmit="return validateLogin()">
                <div class="mb-6">
                    <input type="text" name="username" id="username" placeholder="Tên đăng nhập"
                           class="w-full p-3 rounded-full bg-gray-100 border border-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 text-gray-700 placeholder-gray-500" />
                    <span id="usernameError" class="text-red-500 text-sm hidden">Vui lòng nhập tên đăng nhập!</span>
                </div>
                <div class="mb-6">
                    <input type="password" name="password" id="password" placeholder="Mật khẩu"
                           class="w-full p-3 rounded-full bg-gray-100 border border-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 text-gray-700 placeholder-gray-500" />
                    <span id="passwordError" class="text-red-500 text-sm hidden">Vui lòng nhập mật khẩu!</span>
                </div>
                <p class="text-sm text-center mb-6">
                    <a href="#!" class="text-indigo-600 hover:text-indigo-800 transition-colors">Quên mật khẩu?</a>
                </p>
                <button type="submit"
                        class="w-full bg-gradient-to-r from-indigo-600 to-blue-500 text-white py-3 rounded-full shadow-lg font-semibold hover:scale-105 hover:shadow-xl transition-all duration-300 glow-effect">
                    Đăng nhập
                </button>
                <div class="flex justify-center gap-6 mt-6">
                    <a href="#!" class="text-indigo-600 hover:text-indigo-800 transition-colors text-2xl">📘</a>
                    <a href="#!" class="text-indigo-600 hover:text-indigo-800 transition-colors text-2xl">🐦</a>
                    <a href="#!" class="text-indigo-600 hover:text-indigo-800 transition-colors text-2xl">🌐</a>
                </div>
                <p class="text-center mt-6 text-gray-700">
                    Chưa có tài khoản?
                    <a href="/ProductManager/Account/register" class="text-indigo-600 hover:text-indigo-800 font-semibold transition-colors">Đăng ký</a>
                </p>
            </form>
        </div>
    </section>

    <script>
        function validateLogin() {
            const username = document.getElementById('username').value.trim();
            const password = document.getElementById('password').value.trim();
            let isValid = true;

            // Reset error messages
            document.getElementById('usernameError').classList.add('hidden');
            document.getElementById('passwordError').classList.add('hidden');

            // Username validation
            if (!username) {
                document.getElementById('usernameError').classList.remove('hidden');
                isValid = false;
            }

            // Password validation
            if (!password) {
                document.getElementById('passwordError').classList.remove('hidden');
                isValid = false;
            }

            return isValid;
        }
    </script>

<?php include 'app/views/footer.php'; ?>