<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - Seller Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-slate-100 to-slate-200 min-h-screen flex items-center justify-center">
    <div class="w-full max-w-md bg-white rounded-lg shadow-lg p-8">
        <!-- Logo / Title -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-12 h-12 bg-teal-500 rounded-full mb-4">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-gray-800">Create Your Store</h1>
            <p class="text-gray-600 text-sm mt-2">Join us and start selling today</p>
        </div>

        <!-- Form -->
        <form method="POST" class="space-y-4" action="/SHooad/public/seller/signup">
            <!-- Full Name -->
            <div>
                <label for="seller_signup_name" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                <input type="text" id="seller_signup_name" name="seller_signup_name" placeholder="John Doe" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
            </div>

            <!-- Email -->
            <div>
                <label for="seller_signup_email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                <input type="email" id="seller_signup_email" name="seller_signup_email" placeholder="you@example.com" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
            </div>

            <!-- Password -->
            <div>
                <label for="seller_signup_password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <input type="password" id="seller_signup_password" name="seller_signup_password" placeholder="••••••••" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
            </div>

            <!-- Confirm Password -->
            <div>
                <label for="confirm_password" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password" placeholder="••••••••" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
            </div>

            <!-- Terms Checkbox -->
            <div class="flex items-start">
                <input type="checkbox" id="terms" name="terms" class="w-4 h-4 mt-1 text-teal-500 border-gray-300 rounded focus:ring-teal-500">
                <label for="terms" class="ml-2 text-sm text-gray-600">I agree to the <a href="#" class="text-teal-500 hover:underline">Terms of Service</a> and <a href="#" class="text-teal-500 hover:underline">Privacy Policy</a></label>
            </div>

            <!-- Sign Up Button -->
            <button type="submit" class="w-full bg-teal-600 hover:bg-teal-700 text-white font-semibold py-2 rounded-lg transition duration-200">
                Create Account
            </button>
        </form>

        <!-- Divider -->
        <div class="relative my-6">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-gray-300"></div>
            </div>
            <div class="relative flex justify-center text-sm">
                <span class="px-2 bg-white text-gray-500">Already have an account?</span>
            </div>
        </div>

        <!-- Sign In Link -->
        <p class="text-center text-gray-600 text-sm">
            <a href="login" class="text-teal-500 font-semibold hover:underline">Sign In</a>
            <button class="ml-2 bg-teal-600 hover:bg-teal-700 text-white font-semibold py-2 px-4 rounded-lg transition duration-200">
                Sign In
            </button>
        </p>
    </div>
</body>
</html>
