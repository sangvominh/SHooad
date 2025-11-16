<?php
if (session_status() == PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../../Services/FlashMessageService.php';
$successMessage = FlashMessageService::getFlashMessage('success');
$errorMessage = FlashMessageService::getFlashMessage('error');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login | SHooad</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
    <div class= "justify-center items-center min-h-screen flex relative flex-col">
        <div class="absolute inset-0 bg-[#005D63]"></div>
        <a href="/SHooad/public">
            <h1 class="text-5xl font-extrabold text-center text-white drop-shadow-lg mb-10 z-20">SHooad</h1>
        </a>
        <!-- Login Box -->
        <div class="relative z-10 bg-white p-8 rounded-2xl shadow-2xl w-[90vw] max-w-md">
            <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Login</h2>
            
            <?php if ($successMessage): ?>
                <div class="mb-4 p-3 rounded bg-green-100 text-green-700">
                    <?php echo htmlspecialchars($successMessage); ?>
                </div>
            <?php endif; ?>
            <?php if ($errorMessage): ?>
                <div class="mb-4 p-3 rounded bg-red-100 text-red-700">
                    <?php echo htmlspecialchars($errorMessage); ?>
                </div>
            <?php endif; ?>

            <form action="/SHooad/public/customer/login" method="POST" class="space-y-4">
                <input type="text" name="email" placeholder="Email" required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-600">

                <input type="password" name="password" placeholder="Password" required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-600">
                <button type="submit"
                    class="w-full bg-teal-700 hover:bg-teal-800 text-white py-3 rounded-lg font-semibold transition">
                    Submit
                </button>
            </form>

            <div class="text-center mt-4">
                <a href="/SHooad/public/customer/forgot-password" class="text-sm text-teal-700 hover:underline">Forgot Password?</a>
            </div>

            <p class="text-center text-gray-600 mt-6">
                Don't have an account?
                <a href="/SHooad/public/customer/register" class="text-teal-700 hover:underline font-medium">Register here</a>
            </p>

            <a href="/SHooad/public/seller/login" class="text-center block">
            <button class="mx-10 my-0 text-center text-gray-600 mt-6 hover: border border-gray-300 rounded-md px-4 py-2">
                Seller Login
            </button>
        </a>
        </div>
    </div>
    

</body>
</html>
