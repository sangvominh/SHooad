<?php
/**
 * Migration script to convert colors and sizes from TEXT fields to relational tables
 */

$mysqli = new mysqli('localhost', 'root', '', 'SHooad');

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

echo "Starting migration...\n\n";

// Get all products with colors and sizes
$result = $mysqli->query("SELECT id, colors, sizes FROM products WHERE colors IS NOT NULL OR sizes IS NOT NULL");

if (!$result) {
    die("Error fetching products: " . $mysqli->error);
}

$products = $result->fetch_all(MYSQLI_ASSOC);
echo "Found " . count($products) . " products to migrate.\n\n";

foreach ($products as $product) {
    $productId = $product['id'];
    echo "Processing product ID: $productId\n";
    
    // Process colors
    if (!empty($product['colors'])) {
        $colors = array_filter(array_map('trim', explode(',', $product['colors'])));
        
        foreach ($colors as $colorName) {
            // Check if color exists in colors table
            $stmt = $mysqli->prepare("SELECT id FROM colors WHERE name = ?");
            $stmt->bind_param("s", $colorName);
            $stmt->execute();
            $colorResult = $stmt->get_result();
            
            if ($colorResult->num_rows > 0) {
                $colorRow = $colorResult->fetch_assoc();
                $colorId = $colorRow['id'];
                
                // Insert into product_colors
                $insertStmt = $mysqli->prepare("INSERT IGNORE INTO product_colors (product_id, color_id, stock) VALUES (?, ?, 10)");
                $insertStmt->bind_param("ii", $productId, $colorId);
                $insertStmt->execute();
                
                echo "  - Added color: $colorName\n";
            } else {
                echo "  - Color not found in colors table: $colorName (skipped)\n";
            }
        }
    }
    
    // Process sizes
    if (!empty($product['sizes'])) {
        $sizes = array_filter(array_map('trim', explode(',', $product['sizes'])));
        
        foreach ($sizes as $sizeName) {
            // Check if size exists in sizes table
            $stmt = $mysqli->prepare("SELECT id FROM sizes WHERE name = ?");
            $stmt->bind_param("s", $sizeName);
            $stmt->execute();
            $sizeResult = $stmt->get_result();
            
            if ($sizeResult->num_rows > 0) {
                $sizeRow = $sizeResult->fetch_assoc();
                $sizeId = $sizeRow['id'];
                
                // Insert into product_sizes
                $insertStmt = $mysqli->prepare("INSERT IGNORE INTO product_sizes (product_id, size_id, stock) VALUES (?, ?, 10)");
                $insertStmt->bind_param("ii", $productId, $sizeId);
                $insertStmt->execute();
                
                echo "  - Added size: $sizeName\n";
            } else {
                echo "  - Size not found in sizes table: $sizeName (skipped)\n";
            }
        }
    }
    
    // Create product variants for each color-size combination
    if (!empty($product['colors']) && !empty($product['sizes'])) {
        $colors = array_filter(array_map('trim', explode(',', $product['colors'])));
        $sizes = array_filter(array_map('trim', explode(',', $product['sizes'])));
        
        foreach ($colors as $colorName) {
            foreach ($sizes as $sizeName) {
                // Get color_id
                $stmt = $mysqli->prepare("SELECT id FROM colors WHERE name = ?");
                $stmt->bind_param("s", $colorName);
                $stmt->execute();
                $colorResult = $stmt->get_result();
                
                // Get size_id
                $stmt2 = $mysqli->prepare("SELECT id FROM sizes WHERE name = ?");
                $stmt2->bind_param("s", $sizeName);
                $stmt2->execute();
                $sizeResult = $stmt2->get_result();
                
                if ($colorResult->num_rows > 0 && $sizeResult->num_rows > 0) {
                    $colorId = $colorResult->fetch_assoc()['id'];
                    $sizeId = $sizeResult->fetch_assoc()['id'];
                    
                    // Insert variant
                    $variantStmt = $mysqli->prepare("INSERT IGNORE INTO product_variants (product_id, color_id, size_id, stock) VALUES (?, ?, ?, 10)");
                    $variantStmt->bind_param("iii", $productId, $colorId, $sizeId);
                    $variantStmt->execute();
                }
            }
        }
        echo "  - Created variants for all color-size combinations\n";
    }
    
    echo "\n";
}

echo "Migration completed!\n";
echo "\nNote: The old 'colors' and 'sizes' TEXT fields are still in the products table.\n";
echo "You can manually drop them after verifying the migration:\n";
echo "ALTER TABLE products DROP COLUMN colors, DROP COLUMN sizes;\n";

$mysqli->close();
