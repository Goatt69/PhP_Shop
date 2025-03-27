<?php include 'app\views/header.php'; ?>

<style>
    .custom-card {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        border-radius: 30px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(0, 0, 0, 0.1);
    }
    .tab-container {
        position: relative;
        display: flex;
        justify-content: space-around;
        background: #60a5fa;
        border: 2px solid #60a5fa;
        border-radius: 50px;
        overflow: hidden;
        margin-bottom: 1.5rem;
    }
    .tab-button {
        width: 50%;
        padding: 0.75rem;
        text-align: center;
        font-size: 1.125rem;
        font-weight: 600;
        color: #fff;
        background: transparent;
        border: none;
        outline: none;
        cursor: pointer;
        position: relative;
        z-index: 1;
        transition: color 0.3s ease;
    }
    .tab-button.active {
        color: #000;
    }
    .tab-slider {
        position: absolute;
        top: 50%;
        left: 2%;
        width: 46%;
        height: 90%;
        background: #ffffff;
        border-radius: 50px;
        transform: translateY(-50%);
        transition: transform 0.3s ease;
    }
    .tab-slider.right {
        transform: translateY(-50%) translateX(110%);
    }
    .input-with-icon {
        position: relative;
    }
    .input-with-icon i {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #4f46e5;
    }
    .input-with-icon input {
        padding-left: 40px;
        border: 1px solid #ddd;
        border-radius: 5px;
        background: #f9f9f9;
    }
    .login-button {
        background: #007bff;
        border-radius: 5px;
        transition: background 0.3s;
    }
    .login-button:hover {
        background: #0056b3;
    }
    /* Tab content styles */
        .tab-content {
            position: absolute;
            top: 80px; /* Adjust based on tab-container height */
            left: 0;
            width: 100%;
            opacity: 0;
            transform: translateY(10px);
            transition: opacity 0.3s ease, transform 0.3s ease;
            visibility: hidden;
    }
    .tab-content.active {
            position: relative;
            top: 0;
            opacity: 1;
            transform: translateY(0);
            visibility: visible;
    }
</style>

<section class="bg-gradient-to-br from-indigo-100 via-purple-100 to-pink-100 flex items-center justify-center p-6 md:p-12">
    <div class="max-w-md w-full p-6 custom-card">
        <!-- Tab Navigation -->
        <div class="tab-container">
            <button id="loginTab" class="tab-button active" onclick="showTab('login')">
                Log in
            </button>
            <button id="registerTab" class="tab-button" onclick="showTab('register')">
                Sign up
            </button>
            <div class="tab-slider" id="tabSlider"></div>
        </div>

        <!-- Tab Content -->
        <div id="loginContent" class="tab-content active">
            <h2 class="text-xl font-semibold text-center text-gray-700 mb-4">LOGIN</h2>
            <p class="text-gray-600 text-sm text-center mb-6">Please enter your login details!</p>

            <?php if (isset($errors) && count($errors) > 0 && !isset($registerErrors)): ?>
                <ul class="mb-4 text-red-500 text-center">
                    <?php foreach ($errors as $err): ?>
                        <li class="text-sm"><?php echo $err; ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>

            <form id="loginForm" action="/ProductManager/Account/checklogin" method="post" onsubmit="return validateLogin()">
                <div class="input-with-icon mb-4">
                    <i class="fas fa-envelope"></i>
                        <input type="text" name="username" id="loginUsername" placeholder="Username"
                           class="w-full p-3 rounded-lg text-gray-700" />
                        <span id="loginUsernameError" class="text-red-500 text-sm hidden">Please enter your username!</span>
                </div>
                <div class="input-with-icon mb-4">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="password" id="loginPassword" placeholder="Password"
                           class="w-full p-3 rounded-lg text-gray-700" />
                    <span id="loginPasswordError" class="text-red-500 text-sm hidden">Please enter your password!</span>
                </div>
                <div class="flex items-center mb-4">
                    <input type="checkbox" id="rememberMe" name="rememberMe" class="mr-2">
                    <label for="rememberMe" class="text-sm text-gray-600">Remember me</label>
                </div>
                <button type="submit" class="w-full login-button text-white py-3 rounded-lg font-semibold">
                    Login
                </button>
                <p class="text-sm text-center mt-4 text-gray-600">
                    <a href="#!" class="text-blue-500 hover:text-blue-700">Forgot Password?</a>
                </p>
            </form>
        </div>

        <div id="registerContent" class="tab-content">
            <h2 class="text-xl font-semibold text-center text-gray-700 mb-4">SIGNUP</h2>
            <p class="text-gray-600 text-sm text-center mb-6">Create a new account to get started!</p>

            <?php if (isset($registerErrors) && count($registerErrors) > 0): ?>
                <ul class="mb-4 text-red-500 text-center">
                    <?php foreach ($registerErrors as $err): ?>
                        <li class="text-sm"><?php echo $err; ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>

            <form id="registerForm" action="/ProductManager/Account/save" method="post" onsubmit="return validateRegister()">
                <div class="input-with-icon mb-4">
                    <i class="fas fa-user"></i>
                        <input type="text" name="username" id="registerUsername" placeholder="Username"
                           class="w-full p-3 rounded-lg text-gray-700" />
                        <span id="registerUsernameError" class="text-red-500 text-sm hidden">Please enter your username!</span>
                </div>
                <div class="input-with-icon mb-4">
                    <i class="fas fa-user-circle"></i>
                    <input type="text" name="fullname" id="registerFullname" placeholder="Full Name"
                           class="w-full p-3 rounded-lg text-gray-700" />
                    <span id="registerFullnameError" class="text-red-500 text-sm hidden">Please enter your full name!</span>
                </div>
                <div class="input-with-icon mb-4">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="password" id="registerPassword" placeholder="Password"
                           class="w-full p-3 rounded-lg text-gray-700" />
                    <span id="registerPasswordError" class="text-red-500 text-sm hidden">Password must be at least 6 characters!</span>
                </div>
                <div class="input-with-icon mb-4">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="confirmpassword" id="confirmpassword" placeholder="Confirm Password"
                           class="w-full p-3 rounded-lg text-gray-700" />
                    <span id="confirmPasswordError" class="text-red-500 text-sm hidden">Passwords do not match!</span>
                </div>
                <button type="submit" class="w-full login-button text-white py-3 rounded-lg font-semibold">
                    Signup
                </button>
            </form>
        </div>
    </div>
</section>

<script src="https://kit.fontawesome.com/a076d05399.js"></script>
<script>
    function showTab(tab) {
        // Get tab elements
        const loginTab = document.getElementById('loginTab');
        const registerTab = document.getElementById('registerTab');
        const tabSlider = document.getElementById('tabSlider');
        const loginContent = document.getElementById('loginContent');
        const registerContent = document.getElementById('registerContent');

        // Remove active class from all tabs and content
        loginTab.classList.remove('active');
        registerTab.classList.remove('active');
        loginContent.classList.remove('active');
        registerContent.classList.remove('active');

        // Set active tab based on selection
        if (tab === 'login') {
            loginTab.classList.add('active');
            tabSlider.classList.remove('right');
            loginContent.classList.add('active');
        } else if (tab === 'register') {
            registerTab.classList.add('active');
            tabSlider.classList.add('right');
            registerContent.classList.add('active');
        }
    }

    function validateLogin() {
        const username = document.getElementById('loginUsername').value.trim();
        const password = document.getElementById('loginPassword').value.trim();
        let isValid = true;

        document.getElementById('loginUsernameError').classList.add('hidden');
        document.getElementById('loginPasswordError').classList.add('hidden');

        if (!username) {
            document.getElementById('loginUsernameError').classList.remove('hidden');
            isValid = false;
        }
        if (!password) {
            document.getElementById('loginPasswordError').classList.remove('hidden');
            isValid = false;
        }

        return isValid;
    }

    function validateRegister() {
        const username = document.getElementById('registerUsername').value.trim();
        const fullname = document.getElementById('registerFullname').value.trim();
        const password = document.getElementById('registerPassword').value.trim();
        const confirmPassword = document.getElementById('confirmpassword').value.trim();
        let isValid = true;

        document.getElementById('registerUsernameError').classList.add('hidden');
        document.getElementById('registerFullnameError').classList.add('hidden');
        document.getElementById('registerPasswordError').classList.add('hidden');
        document.getElementById('confirmPasswordError').classList.add('hidden');

        if (!username) {
            document.getElementById('registerUsernameError').classList.remove('hidden');
            isValid = false;
        }
        if (!fullname) {
            document.getElementById('registerFullnameError').classList.remove('hidden');
            isValid = false;
        }
        if (!password || password.length < 6) {
            document.getElementById('registerPasswordError').classList.remove('hidden');
            isValid = false;
        }
        if (password !== confirmPassword) {
            document.getElementById('confirmPasswordError').classList.remove('hidden');
            isValid = false;
        }

        return isValid;
    }

    // Default to login tab
    showTab('login');
</script>

<?php include 'app/views/footer.php'; ?>
