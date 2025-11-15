<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? 'SHooad'; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />
    <link rel="stylesheet" href="/SHooad/public/assets/css/custom.css">
    <?php if (isset($additionalStyles)): ?>
        <?php echo $additionalStyles; ?>
    <?php endif; ?>
</head>
<body class="<?php echo $bodyClass ?? 'bg-white'; ?>">
    <!-- Header & Navigation -->
    <?php include __DIR__ . '/partials/header.php'; ?>
    
    <!-- Main Content -->
    <main>
        <?php echo $content ?? ''; ?>
    </main>
    
    <!-- Footer -->
    <?php include __DIR__ . '/partials/footer.php'; ?>
    
    <!-- Default Scripts -->
    <script src="/SHooad/public/assets/js/customer/dropdown.js"></script>
    <?php if (isset($additionalScripts)): ?>
        <?php echo $additionalScripts; ?>
    <?php endif; ?>
</body>
</html>
