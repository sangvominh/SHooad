<link
  rel="stylesheet"
  href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
/>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SHooad</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="/SHooad/public/assets/css/custom.css">
</head>
<body class="bg-white overflow-x-hidden">
    <div class="w-full overflow-x-hidden">
        <!-- Header -->
        <?php include 'partials/header.php'; ?>
        
        <!-- Sticky Navigation -->
        <?php include 'partials/navigation.php'; ?>
        
        <!-- Banner Section -->
        <?php include 'partials/banner.php'; ?>
        
        <!-- Categories Section -->
        <?php include 'partials/categories-section.php'; ?>

        <!-- Popular Products Section -->
        <?php include 'partials/products-section.php'; ?>

        <!-- Footer -->
        <?php include 'partials/footer.php'; ?>
    </div>
    
    <!-- Scripts -->
    <script src="/SHooad/public/assets/js/customer/dropdown.js"></script>
    <script>
        // Show/hide search icon in navigation on scroll
        let lastScrollTop = 0;
        const navSearchIcon = document.getElementById('navSearchIcon');
        const header = document.querySelector('header');
        
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
        
        // Scroll to top and focus search input
        function scrollToSearch() {
            window.scrollTo({ top: 0, behavior: 'smooth' });
            
            // Wait for scroll to complete, then focus
            setTimeout(() => {
                const searchInput = document.getElementById('searchInput');
                if (searchInput) {
                    searchInput.focus();
                }
            }, 500);
        }
    </script>
</body>
</html>

