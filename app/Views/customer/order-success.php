<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đặt hàng thành công - SHooad</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex items-center justify-center px-4 py-8">
        <div class="max-w-md w-full bg-white rounded-lg shadow-xl p-8 text-center">
            <!-- Success Icon -->
            <div class="mb-6">
                <div class="mx-auto w-20 h-20 bg-green-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-check-circle text-green-500 text-5xl"></i>
                </div>
            </div>
            
            <!-- Success Message -->
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Đặt hàng thành công!</h1>
            <p class="text-gray-600 mb-6">Cảm ơn bạn đã đặt hàng tại SHooad</p>
            
            <!-- Order Info -->
            <?php
            $order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
            if ($order_id > 0):
            ?>
            <div class="bg-gray-50 rounded-lg p-4 mb-6">
                <p class="text-sm text-gray-500 mb-1">Mã đơn hàng</p>
                <p class="text-2xl font-bold text-blue-600">#<?= str_pad($order_id, 6, '0', STR_PAD_LEFT) ?></p>
            </div>
            <?php endif; ?>
            
            <p class="text-gray-600 mb-8">
                Chúng tôi đã nhận được đơn hàng của bạn và sẽ xử lý trong thời gian sớm nhất. 
                Bạn sẽ nhận được email xác nhận đơn hàng ngay lập tức.
            </p>
            
            <!-- Action Buttons -->
            <div class="space-y-3">
                <a href="/SHooad/public/customer/orders" class="block w-full bg-blue-600 text-white font-semibold py-3 rounded-lg hover:bg-blue-700 transition">
                    Xem đơn hàng của tôi
                </a>
                <a href="/SHooad/public/customer" class="block w-full bg-gray-200 text-gray-700 font-semibold py-3 rounded-lg hover:bg-gray-300 transition">
                    Tiếp tục mua sắm
                </a>
            </div>
        </div>
    </div>
    
    <style>
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        body > div {
            animation: fadeIn 0.5s ease-out;
        }
    </style>
</body>
</html>
