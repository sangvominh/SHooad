<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shipper Signup - Delivery Partner</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-slate-950 via-blue-950 to-slate-900 min-h-screen flex items-center justify-center py-8">
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-20 left-10 w-72 h-72 bg-blue-500/10 rounded-full blur-3xl"></div>
        <div class="absolute bottom-20 right-10 w-96 h-96 bg-indigo-500/10 rounded-full blur-3xl"></div>
    </div>

    <div class="relative w-full max-w-md">
        <div class="backdrop-blur-xl bg-white/10 border border-white/20 rounded-2xl p-8 shadow-2xl">
            <div class="mb-8">
                <div class="flex items-center justify-center gap-3 mb-2">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-400 to-indigo-600 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h1 class="text-2xl font-bold text-white">DeliveryHub</h1>
                </div>
                <p class="text-center text-gray-300 text-sm">Create Shipper Account</p>
            </div>

            <form class="space-y-4" action="/SHooad/public/shipper/signup" method="POST">
                <div>
                    <label class="block text-sm font-medium text-gray-200 mb-2">Full Name</label>
                    <input type="text" name="shipper_name" placeholder="John Doe" class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500/50 transition">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-200 mb-2">Email</label>
                    <input type="email" name="shipper_email" placeholder="your@email.com" class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500/50 transition">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-200 mb-2">Password</label>
                    <input type="password" name="shipper_password" placeholder="••••••••" class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500/50 transition">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-200 mb-2">Confirm Password</label>
                    <input type="password" name="shipper_confirm_password" placeholder="••••••••" class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500/50 transition">
                </div>

                <label class="flex items-start gap-3 pt-2">
                    <input type="checkbox" class="w-4 h-4 rounded border-white/20 bg-white/10 mt-1">
                    <span class="text-sm text-gray-300">
                        I agree to the
                        <a href="#" class="text-blue-400 hover:text-blue-300">Terms of Service</a> and
                        <a href="#" class="text-blue-400 hover:text-blue-300">Privacy Policy</a>
                    </span>
                </label>

                <button type="submit" class="w-full py-3 mt-6 bg-gradient-to-r from-blue-500 to-indigo-600 text-white font-semibold rounded-lg hover:from-blue-600 hover:to-indigo-700 transition shadow-lg">
                    Create Account
                </button>
            </form>

            <div class="mt-6 pt-6 border-t border-white/20">
                <p class="text-center text-gray-300 text-sm">
                    Already have an account?
                    <a href="login" class="text-blue-400 hover:text-blue-300 font-semibold transition">Sign in</a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>
