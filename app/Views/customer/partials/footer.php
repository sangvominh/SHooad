<?php
$footer_columns = [
    [
        'title' => 'Customer Service',
        'links' => [
            ['text' => 'Contact Us', 'href' => '#contact'],
            ['text' => 'FAQs', 'href' => '#faqs'],
            ['text' => 'Order Lookup', 'href' => '#order-lookup'],
            ['text' => 'Returns', 'href' => '#returns'],
            ['text' => 'Shipping & Delivery', 'href' => '#shipping'],
            ['text' => 'Corporate Gifting', 'href' => '#corporate-gifting']
        ]
    ],
    [
        'title' => 'About Us',
        'links' => [
            ['text' => 'Careers', 'href' => '#careers'],
            ['text' => 'News & Blog', 'href' => '#news-blog'],
            ['text' => 'Press Center', 'href' => '#press-center'],
            ['text' => 'Investors', 'href' => '#investors'],
            ['text' => 'Suppliers', 'href' => '#suppliers'],
            ['text' => 'Terms & Conditions', 'href' => '#terms'],
            ['text' => 'Privacy Policy', 'href' => '#privacy']
        ]
    ],
    [
        'title' => 'Credit Card',
        'links' => [
            ['text' => 'Gift Cards', 'href' => '#gift-cards'],
            ['text' => 'Gift Cards Balance', 'href' => '#gift-cards-balance'],
            ['text' => 'Shop with Points', 'href' => '#shop-points'],
            ['text' => 'Reload Your Balance', 'href' => '#reload-balance']
        ]
    ],
    [
        'title' => 'Sell',
        'links' => [
            ['text' => 'Start Selling', 'href' => '#start-selling'],
            ['text' => 'Learn to Sell', 'href' => '#learn-sell'],
            ['text' => 'Affiliates & Partners', 'href' => '#affiliates']
        ]
    ],
    [
        'title' => 'Follow us',
        'is_social' => true,
        'socials' => [
            ['icon' => 'f', 'label' => 'Facebook', 'href' => '#facebook'],
            ['icon' => 'in', 'label' => 'LinkedIn', 'href' => '#linkedin'],
            ['icon' => 'tw', 'label' => 'Twitter', 'href' => '#twitter']
        ]
    ]
];
?>

<footer class="bg-gray-900 text-gray-300">
    <!-- Footer Top Section -->
    <div class="max-w-7xl mx-auto px-4 py-16">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-8">
            <?php foreach ($footer_columns as $column): ?>
                <?php include 'footer-column.php'; ?>
            <?php endforeach; ?>
        </div>
    </div>
    
    <!-- Footer Bottom Section -->
    <?php include 'footer-bottom.php'; ?>
</footer>
