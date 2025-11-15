<?php
require_once __DIR__ . '/../middleware/AuthMiddleware.php';

class ImageController {
    
    /**
     * Serve product image - Only for authenticated sellers
     */
    public function serveProductImage(string $filename) {
        // Check authentication
        if (!isset($_SESSION['seller_id'])) {
            http_response_code(403);
            exit('Access denied');
        }

        // Sanitize filename
        $filename = basename($filename);
        $filepath = __DIR__ . '/../../database/seller_uploads/products/' . $filename;

        // Check if file exists
        if (!file_exists($filepath)) {
            http_response_code(404);
            exit('Image not found');
        }

        // Get mime type
        $mimeType = mime_content_type($filepath);
        
        // Security: Only allow image types
        if (!str_starts_with($mimeType, 'image/')) {
            http_response_code(403);
            exit('Invalid file type');
        }

        // Set headers
        header('Content-Type: ' . $mimeType);
        header('Content-Length: ' . filesize($filepath));
        header('Cache-Control: max-age=86400'); // Cache for 1 day
        
        // Output file
        readfile($filepath);
        exit;
    }
}
