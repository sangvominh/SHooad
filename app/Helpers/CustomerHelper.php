<?php
/**
 * Customer View Helpers
 * Common functions for customer views to reduce code duplication
 */

/**
 * Start output buffering and include a partial
 */
function renderPartial(string $partialName, array $data = []): string {
    extract($data);
    ob_start();
    include __DIR__ . "/../Views/customer/partials/{$partialName}.php";
    return ob_get_clean();
}

/**
 * Render a layout with content
 */
function renderLayout(string $content, array $data = []): void {
    extract($data);
    include __DIR__ . '/../Views/customer/layout.php';
}

/**
 * Get customer session data
 */
function getCustomerSession(): array {
    return [
        'customer_id' => $_SESSION['customer_id'] ?? null,
        'customer_email' => $_SESSION['customer_email'] ?? null,
        'customer_name' => $_SESSION['customer_name'] ?? null,
        'is_logged_in' => isset($_SESSION['customer_id'])
    ];
}

/**
 * Check if customer is logged in
 */
function isCustomerLoggedIn(): bool {
    return isset($_SESSION['customer_id']);
}

/**
 * Redirect to a path
 */
function redirect(string $path): void {
    header("Location: $path");
    exit;
}

/**
 * Get base URL
 */
function baseUrl(string $path = ''): string {
    return '/SHooad/public' . ($path ? '/' . ltrim($path, '/') : '');
}

/**
 * Get customer URL
 */
function customerUrl(string $path = ''): string {
    return baseUrl('customer' . ($path ? '/' . ltrim($path, '/') : ''));
}

/**
 * Get asset URL
 */
function assetUrl(string $path): string {
    return baseUrl('assets/' . ltrim($path, '/'));
}

/**
 * Escape HTML
 */
function e(string $value): string {
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

/**
 * Format price
 */
function formatPrice(float $price): string {
    return '$' . number_format($price, 2);
}

/**
 * Format date
 */
function formatDate(string $date, string $format = 'Y-m-d H:i:s'): string {
    return date($format, strtotime($date));
}

/**
 * Get product image URL
 */
function getProductImageUrl(?string $filename, string $default = '/assets/products/default.jpg'): string {
    if (empty($filename)) {
        return assetUrl($default);
    }
    return assetUrl('products/' . $filename);
}

/**
 * Calculate discount percentage
 */
function getDiscountPercentage(float $originalPrice, float $salePrice): int {
    if ($originalPrice <= 0) {
        return 0;
    }
    return (int) round((($originalPrice - $salePrice) / $originalPrice) * 100);
}

/**
 * Check if product is on sale
 */
function isOnSale(array $product): bool {
    return isset($product['original_price']) && 
           isset($product['price']) && 
           $product['original_price'] > $product['price'];
}

/**
 * Get color hex code
 */
function getColorCode(string $colorName): string {
    $colorMap = [
        'Black' => '#000000',
        'White' => '#FFFFFF',
        'Red' => '#FF0000',
        'Green' => '#008000',
        'Blue' => '#0000FF',
        'Yellow' => '#FFFF00',
        'Purple' => '#800080',
        'Pink' => '#FFC0CB',
        'Orange' => '#FFA500',
        'Brown' => '#A52A2A',
        'Gray' => '#808080',
        'Grey' => '#808080'
    ];
    
    $normalized = ucwords(strtolower(trim($colorName)));
    return $colorMap[$normalized] ?? '#000000';
}

/**
 * Parse comma-separated values
 */
function parseCSV(string $value): array {
    if (empty(trim($value))) {
        return [];
    }
    
    $parts = array_map('trim', explode(',', $value));
    $parts = array_filter($parts, fn($v) => $v !== '');
    $parts = array_unique($parts);
    
    return array_values($parts);
}

/**
 * Create pagination data
 */
function getPaginationData(int $currentPage, int $totalItems, int $perPage = 9): array {
    $totalPages = max(1, ceil($totalItems / $perPage));
    $currentPage = max(1, min($currentPage, $totalPages));
    
    return [
        'current_page' => $currentPage,
        'total_pages' => $totalPages,
        'total_items' => $totalItems,
        'per_page' => $perPage,
        'offset' => ($currentPage - 1) * $perPage,
        'has_prev' => $currentPage > 1,
        'has_next' => $currentPage < $totalPages,
        'prev_page' => max(1, $currentPage - 1),
        'next_page' => min($totalPages, $currentPage + 1)
    ];
}

/**
 * Build query string from array
 */
function buildQueryString(array $params): string {
    $filtered = array_filter($params, fn($v) => $v !== null && $v !== '');
    return empty($filtered) ? '' : '?' . http_build_query($filtered);
}

/**
 * Get flash message
 */
function getFlashMessage(string $key): ?string {
    if (isset($_SESSION['flash_messages'][$key])) {
        $message = $_SESSION['flash_messages'][$key];
        unset($_SESSION['flash_messages'][$key]);
        return $message;
    }
    return null;
}

/**
 * Set flash message
 */
function setFlashMessage(string $key, string $message): void {
    $_SESSION['flash_messages'][$key] = $message;
}
