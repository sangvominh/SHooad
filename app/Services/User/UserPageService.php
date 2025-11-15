<?php
require_once __DIR__ . '/../ProductService.php';
require_once __DIR__ . '/../../Models/Banner.php';

class UserPageService {
    private $productService;
    private $bannerModel;

    public function __construct() {
        $this->productService = new ProductService();
        $this->bannerModel = new Banner();
    }

    public function getHomePageData(): array {
        return [
            'banners' => $this->bannerModel->getActive()
        ];
    }

    public function getProductDetailData(int $productId): ?array {
        $pdo = new PDO('mysql:host=localhost;dbname=SHooad;charset=utf8', 'root', '');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "SELECT p.*, sh.name as shop_name, sh.id as shop_id,
                    GROUP_CONCAT(DISTINCT pi.filename) as images,
                    p.colors as colors,
                    p.sizes as sizes
                FROM products p
                LEFT JOIN shops sh ON p.shop_id = sh.id
                LEFT JOIN product_images pi ON p.id = pi.product_id
                WHERE p.id = :pid AND p.status = 'active'
                GROUP BY p.id";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([':pid' => $productId]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$product) {
            return null;
        }

        // Process images
        $imgStmt = $pdo->prepare('SELECT filename FROM product_images WHERE product_id = :pid ORDER BY id ASC');
        $imgStmt->execute([':pid' => $productId]);
        $imgRows = $imgStmt->fetchAll(PDO::FETCH_ASSOC);
        
        $product['images'] = [];
        $product['main_image'] = '/SHooad/public/assets/products/default.jpg';
        
        foreach ($imgRows as $img) {
            $url = '/SHooad/public/assets/products/' . $img['filename'];
            $product['images'][] = $url;
        }

        if (count($product['images']) > 0) {
            $product['main_image'] = $product['images'][0];
        }

        $product['thumbnail_images'] = array_values(array_filter($product['images'], function($img) use ($product) {
            return $img !== $product['main_image'];
        }));

        // Process colors
        $product['colors'] = $this->processColors($product['colors'] ?? '');

        // Process sizes
        $product['sizes'] = $this->processSizes($product['sizes'] ?? '');

        return $product;
    }

    private function processColors(string $rawColors): array {
        $getColorCode = function(string $colorName): string {
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
                'Gray' => '#808080'
            ];
            return $colorMap[$colorName] ?? '#000000';
        };

        $colorList = [];
        if (strlen(trim($rawColors)) > 0) {
            $parts = array_map('trim', explode(',', $rawColors));
            $parts = array_filter($parts, function($v) { return $v !== '' && $v !== null; });
            $parts = array_unique($parts);
            $colorList = array_values($parts);
        }

        return array_map(function($color) use ($getColorCode) {
            $name = (string)$color;
            $trim = trim($name);
            
            if (preg_match('/^#([A-Fa-f0-9]{3}|[A-Fa-f0-9]{6})$/', $trim)) {
                $code = $trim;
            } else {
                $normalized = ucwords(strtolower($trim));
                $code = $getColorCode($normalized);
            }
            
            return [
                'name' => $name,
                'code' => $code,
            ];
        }, $colorList);
    }

    private function processSizes(string $rawSizes): array {
        if (strlen(trim($rawSizes)) === 0) {
            return [];
        }

        $parts = array_map('trim', explode(',', $rawSizes));
        $parts = array_filter($parts, function($v) { return $v !== '' && $v !== null; });
        $parts = array_unique($parts);
        
        return array_values($parts);
    }

    public function getCartData(): array {
        // Cart data is handled by cart partials and AJAX
        // This method can be expanded if needed for server-side cart processing
        return [];
    }

    public function getProductsPageData(): array {
        // Products are loaded via partials and models directly
        return [];
    }
}
