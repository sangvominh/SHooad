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

            <form action="/SHooad/public/user/login" method="POST" class="space-y-4">
                <input type="text" name="email" placeholder="Email" required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-600">

                <input type="password" name="password" placeholder="Password" required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-600">
                <button type="submit"
                    class="w-full bg-teal-700 hover:bg-teal-800 text-white py-3 rounded-lg font-semibold transition">
                    Submit
                </button>
            </form>

            <p class="text-center text-gray-600 mt-6">
                Don't have an account?
                <a href="/SHooad/app/Views/user/register.php" class="text-teal-700 hover:underline font-medium">Register here</a>
            </p>

            <a href="/SHooad/app/Views/seller/login.php" class="text-center block">
            <button class="mx-10 my-0 text-center text-gray-600 mt-6 hover: border border-gray-300 rounded-md px-4 py-2">
                Seller Login
            </button>
        </a>
        </div>
    </div>
    

</body>
</html>
