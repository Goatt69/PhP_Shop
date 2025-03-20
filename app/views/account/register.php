<?php include 'app/views/header.php'; ?>

    <section class=" bg-gradient-to-br from-indigo-100 via-purple-100 to-pink-100 flex items-center justify-center p-6 md:p-12">
        <div class="max-w-md w-full bg-white/90 p-8 rounded-3xl shadow-2xl glassmorphism">
            <h2 class="text-4xl font-extrabold mb-6 text-gray-900 text-center bg-gradient-to-r from-purple-600 to-blue-600 bg-clip-text text-transparent animate-pulse">
                Đăng ký
            </h2>
            <p class="text-gray-700 text-lg text-center mb-8">Tạo tài khoản mới để bắt đầu!</p>

            <?php if (isset($errors) && count($errors) > 0): ?>
                <ul class="mb-6 text-red-500 text-center">
                    <?php foreach ($errors as $err): ?>
                        <li><?php echo $err; ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>

            <form id="registerForm" action="/ProductManager/Account/save" method="post" onsubmit="return validateRegister()">
                <div class="mb-6">
                    <input type="text" name="username" id="username" placeholder="Tên đăng nhập"
                           class="w-full p-3 rounded-full bg-gray-100 border border-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 text-gray-700 placeholder-gray-500" />
                    <span id="usernameError" class="text-red-500 text-sm hidden">Vui lòng nhập tên đăng nhập!</span>
                </div>
                <div class="mb-6">
                    <input type="text" name="fullname" id="fullname" placeholder="Họ và tên"
                           class="w-full p-3 rounded-full bg-gray-100 border border-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 text-gray-700 placeholder-gray-500" />
                    <span id="fullnameError" class="text-red-500 text-sm hidden">Vui lòng nhập họ và tên!</span>
                </div>
                <div class="mb-6">
                    <input type="password" name="password" id="password" placeholder="Mật khẩu"
                           class="w-full p-3 rounded-full bg-gray-100 border border-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 text-gray-700 placeholder-gray-500" />
                    <span id="passwordError" class="text-red-500 text-sm hidden">Vui lòng nhập mật khẩu (tối thiểu 6 ký tự)!</span>
                </div>
                <div class="mb-6">
                    <input type="password" name="confirmpassword" id="confirmpassword" placeholder="Xác nhận mật khẩu"
                           class="w-full p-3 rounded-full bg-gray-100 border border-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 text-gray-700 placeholder-gray-500" />
                    <span id="confirmPasswordError" class="text-red-500 text-sm hidden">Mật khẩu xác nhận không khớp!</span>
                </div>
                <button type="submit"
                        class="w-full bg-gradient-to-r from-indigo-600 to-blue-500 text-white py-3 rounded-full shadow-lg font-semibold hover:scale-105 hover:shadow-xl transition-all duration-300 glow-effect">
                    Đăng ký
                </button>
                <p class="text-center mt-6 text-gray-700">
                    Đã có tài khoản?
                    <a href="/ProductManager/Account/login" class="text-indigo-600 hover:text-indigo-800 font-semibold transition-colors">Đăng nhập</a>
                </p>
            </form>
        </div>
    </section>

    <script>
        function validateRegister() {
            const username = document.getElementById('username').value.trim();
            const fullname = document.getElementById('fullname').value.trim();
            const password = document.getElementById('password').value.trim();
            const confirmPassword = document.getElementById('confirmpassword').value.trim();
            let isValid = true;

            // Reset error messages
            document.getElementById('usernameError').classList.add('hidden');
            document.getElementById('fullnameError').classList.add('hidden');
            document.getElementById('passwordError').classList.add('hidden');
            document.getElementById('confirmPasswordError').classList.add('hidden');

            // Username validation
            if (!username) {
                document.getElementById('usernameError').classList.remove('hidden');
                isValid = false;
            }

            // Fullname validation
            if (!fullname) {
                document.getElementById('fullnameError').classList.remove('hidden');
                isValid = false;
            }

            // Password validation (minimum 6 characters)
            if (!password || password.length < 6) {
                document.getElementById('passwordError').classList.remove('hidden');
                isValid = false;
            }

            // Confirm password validation
            if (password !== confirmPassword) {
                document.getElementById('confirmPasswordError').classList.remove('hidden');
                isValid = false;
            }

            return isValid;
        }
    </script>

<?php include 'app/views/footer.php'; ?>