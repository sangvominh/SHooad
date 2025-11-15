<?php
require_once __DIR__ . '/LanguageHelper.php';

class BreadcrumbHelper {
    public static function generate(): array {
        $uri = $_SERVER['REQUEST_URI'] ?? '';
        $path = parse_url($uri, PHP_URL_PATH);
        $query = parse_url($uri, PHP_URL_QUERY);
        
        // Parse query string
        parse_str($query ?? '', $params);
        
        // Remove base path
        $path = str_replace('/SHooad/public', '', $path);
        $segments = array_filter(explode('/', $path));
        
        $breadcrumbs = [
            ['label' => LanguageHelper::t('breadcrumb.home'), 'url' => '/SHooad/public/customer']
        ];
        
        // If on home page, return only home
        if (empty($segments) || (count($segments) == 1 && $segments[1] == 'customer')) {
            return $breadcrumbs;
        }
        
        // Get the main section
        $section = $segments[2] ?? '';
        
        switch ($section) {
            case 'products':
                $breadcrumbs[] = ['label' => LanguageHelper::t('breadcrumb.products'), 'url' => '/SHooad/public/customer/products'];
                
                if (isset($params['category'])) {
                    $breadcrumbs[] = ['label' => $params['category'], 'url' => null];
                } elseif (isset($params['brand'])) {
                    $breadcrumbs[] = ['label' => $params['brand'], 'url' => null];
                } elseif (isset($params['sort'])) {
                    $sortLabel = match($params['sort']) {
                        'new-arrivals' => LanguageHelper::t('breadcrumb.new_arrivals'),
                        'best-sellers' => LanguageHelper::t('breadcrumb.best_sellers'),
                        'on-sale' => LanguageHelper::t('breadcrumb.on_sale'),
                        default => LanguageHelper::t('breadcrumb.all_products')
                    };
                    $breadcrumbs[] = ['label' => $sortLabel, 'url' => null];
                }
                break;
                
            case 'product-detail':
                $breadcrumbs[] = ['label' => LanguageHelper::t('breadcrumb.products'), 'url' => '/SHooad/public/customer/products'];
                $breadcrumbs[] = ['label' => LanguageHelper::t('breadcrumb.product_detail'), 'url' => null];
                break;
                
            case 'cart':
                $breadcrumbs[] = ['label' => LanguageHelper::t('breadcrumb.cart'), 'url' => null];
                break;
                
            case 'checkout':
                $breadcrumbs[] = ['label' => LanguageHelper::t('breadcrumb.cart'), 'url' => '/SHooad/public/customer/cart'];
                $breadcrumbs[] = ['label' => LanguageHelper::t('breadcrumb.checkout'), 'url' => null];
                break;
                
            case 'order-success':
                $breadcrumbs[] = ['label' => LanguageHelper::t('breadcrumb.cart'), 'url' => '/SHooad/public/customer/cart'];
                $breadcrumbs[] = ['label' => LanguageHelper::t('breadcrumb.checkout'), 'url' => '/SHooad/public/customer/checkout'];
                $breadcrumbs[] = ['label' => LanguageHelper::t('breadcrumb.order_success'), 'url' => null];
                break;
                
            case 'profile':
                $breadcrumbs[] = ['label' => LanguageHelper::t('breadcrumb.profile'), 'url' => null];
                break;
                
            case 'orders':
                $breadcrumbs[] = ['label' => LanguageHelper::t('breadcrumb.orders'), 'url' => null];
                
                if (isset($params['status'])) {
                    $statusLabel = str_replace('_', ' ', $params['status']);
                    $breadcrumbs[] = ['label' => $statusLabel, 'url' => null];
                }
                break;
                
            case 'order-detail':
                $breadcrumbs[] = ['label' => LanguageHelper::t('breadcrumb.orders'), 'url' => '/SHooad/public/customer/orders'];
                $breadcrumbs[] = ['label' => LanguageHelper::t('breadcrumb.order_detail'), 'url' => null];
                break;
                
            default:
                if (!empty($section)) {
                    $label = ucwords(str_replace('-', ' ', $section));
                    $breadcrumbs[] = ['label' => $label, 'url' => null];
                }
                break;
        }
        
        return $breadcrumbs;
    }
    
    public static function render(): string {
        $breadcrumbs = self::generate();
        
        if (count($breadcrumbs) <= 1) {
            return '';
        }
        
        $html = '<div class="flex items-center gap-2 text-sm">';
        
        foreach ($breadcrumbs as $index => $crumb) {
            $isLast = $index === count($breadcrumbs) - 1;
            
            if (!$isLast && $crumb['url']) {
                $html .= '<a href="' . htmlspecialchars($crumb['url']) . '" class="text-gray-600 hover:text-blue-600 transition">';
                $html .= htmlspecialchars($crumb['label']);
                $html .= '</a>';
                $html .= '<i class="fas fa-chevron-right text-gray-400 text-xs"></i>';
            } else {
                $html .= '<span class="text-gray-900 font-medium">' . htmlspecialchars($crumb['label']) . '</span>';
            }
        }
        
        $html .= '</div>';
        
        return $html;
    }
}
