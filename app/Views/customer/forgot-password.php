<?php
if (session_status() == PHP_SESSION_NONE) session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password | SHooad</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="justify-center items-center min-h-screen flex relative flex-col">
    <div class="absolute inset-0 bg-[#005D63]"></div>
    <a href="/SHooad/public">
        <h1 class="text-5xl font-extrabold text-center text-white drop-shadow-lg mb-10 z-20">SHooad</h1>
    </a>
    
    <!-- Forgot Password Box -->
    <div class="relative z-10 bg-white p-8 rounded-2xl shadow-2xl w-[90vw] max-w-md">
        <h2 class="text-2xl font-bold text-center text-gray-800 mb-2">Forgot Password</h2>
        <p class="text-center text-gray-600 mb-6 text-sm">Enter your email to reset your password</p>
        
        <?php
        require_once __DIR__ . '/../../Services/FlashMessageService.php';
        $successMessage = FlashMessageService::getFlashMessage('success');
        $errorMessage = FlashMessageService::getFlashMessage('error');
        
        if ($successMessage): ?>
            <div class="mb-4 p-3 rounded bg-green-100 text-green-700">
                <?php echo htmlspecialchars($successMessage); ?>
            </div>
        <?php endif; ?>
        
        <?php if ($errorMessage): ?>
            <div class="mb-4 p-3 rounded bg-red-100 text-red-700">
                <?php echo htmlspecialchars($errorMessage); ?>
            </div>
        <?php endif; ?>

        <form action="/SHooad/public/customer/forgot-password" method="POST" class="space-y-4">
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                <input type="email" id="email" name="email" placeholder="your@email.com" required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-600">
            </div>

            <button type="submit"
                class="w-full bg-teal-700 hover:bg-teal-800 text-white py-3 rounded-lg font-semibold transition">
                Continue
            </button>
        </form>

        <div class="text-center mt-6">
            <a href="/SHooad/public/customer/login" class="text-sm text-teal-700 hover:underline">
                <i class="fas fa-arrow-left mr-1"></i> Back to Login
            </a>
        </div>
    </div>
</body>
</html>
