<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - Seller Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-slate-50 to-slate-100 min-h-screen flex items-center justify-center">
    <div class="w-full max-w-md">
        <!-- Logo & Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-12 h-12 bg-teal-600 rounded-lg mb-4">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-gray-900">Forgot Password</h1>
            <p class="text-gray-600 mt-2">Enter your email to reset password</p>
        </div>

        <!-- Forgot Password Form Card -->
        <div class="bg-white rounded-2xl shadow-lg p-8 mb-6">
            <?php
            require_once __DIR__ . '/../../Services/FlashMessageService.php';
            $successMessage = FlashMessageService::getFlashMessage('success');
            $errorMessage = FlashMessageService::getFlashMessage('error');
            
            if ($successMessage): ?>
                <div class="mb-4 p-3 rounded bg-green-100 text-green-700">
                    <i class="fas fa-check-circle mr-2"></i><?php echo htmlspecialchars($successMessage); ?>
                </div>
            <?php endif; ?>
            
            <?php if ($errorMessage): ?>
                <div class="mb-4 p-3 rounded bg-red-100 text-red-700">
                    <i class="fas fa-exclamation-circle mr-2"></i><?php echo htmlspecialchars($errorMessage); ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="/SHooad/public/seller/forgot-password">
                <!-- Email Field -->
                <div class="mb-5">
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email Address</label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        placeholder="seller@example.com"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-600 focus:border-transparent"
                        required
                    >
                </div>

                <!-- Continue Button -->
                <button 
                    type="submit"
                    class="w-full bg-teal-600 hover:bg-teal-700 text-white font-semibold py-3 rounded-lg transition duration-200"
                >
                    Continue
                </button>
            </form>

            <!-- Back to Login -->
            <div class="text-center mt-4">
                <a href="/SHooad/public/seller/login" class="text-sm text-teal-600 hover:text-teal-700">
                    <i class="fas fa-arrow-left mr-1"></i> Back to Login
                </a>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center text-xs text-gray-500">
            <p>&copy; 2025 Seller Dashboard. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
