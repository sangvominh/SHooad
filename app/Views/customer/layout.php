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
<body class="<?php echo $bodyClass ?? 'bg-white'; ?> overflow-x-hidden">
    <?php if (session_status() == PHP_SESSION_NONE) session_start(); ?>
    
    <div class="w-full">
        <!-- Header -->
        <?php include __DIR__ . '/partials/header.php'; ?>
        
        <!-- Navigation -->
        <?php include __DIR__ . '/partials/navigation.php'; ?>
        
        <!-- Main Content -->
        <main class="w-full">
            <?php echo $content ?? ''; ?>
        </main>
    </div>
    
    <!-- Default Scripts -->
    <script src="/SHooad/public/assets/js/customer/dropdown.js"></script>
    <script>
        // Show/hide search icon in navigation on scroll
        const navSearchIcon = document.getElementById('navSearchIcon');
        const header = document.querySelector('header');
        
        if (navSearchIcon) {
            window.addEventListener('scroll', function() {
                const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
                const headerHeight = header ? header.offsetHeight : 100;
                
                // Show icon when scrolled past header
                if (scrollTop > headerHeight) {
                    navSearchIcon.style.opacity = '1';
                } else {
                    navSearchIcon.style.opacity = '0';
                }
            });
        }
        
        // Scroll to top and focus search input
        function scrollToSearch() {
            window.scrollTo({ top: 0, behavior: 'smooth' });
            setTimeout(() => {
                const searchInput = document.getElementById('searchInput');
                if (searchInput) searchInput.focus();
            }, 500);
        }
    </script>
    <?php if (isset($additionalScripts)): ?>
        <?php echo $additionalScripts; ?>
    <?php endif; ?>
</body>
</html>
