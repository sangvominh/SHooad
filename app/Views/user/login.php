<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng nhập | SHooad</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center relative">

    <!-- Nền mờ -->
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm"></div>

    <!-- Hộp đăng nhập -->
    <div class="relative z-10 bg-white p-8 rounded-2xl shadow-2xl w-[90vw] max-w-md">
        <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Đăng nhập</h2>

        <form action="login" method="POST" class="space-y-4">
            <input type="email" name="email" placeholder="Email" required
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-600">

            <input type="password" name="password" placeholder="Mật khẩu" required
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-600">

            <button type="submit"
                class="w-full bg-teal-700 hover:bg-teal-800 text-white py-3 rounded-lg font-semibold transition">
                Đăng nhập
            </button>
        </form>

        <p class="text-center text-gray-600 mt-6">
            Chưa có tài khoản?
            <a href="register" class="text-teal-700 hover:underline font-medium">Đăng ký</a>
        </p>
    </div>

</body>
</html>
