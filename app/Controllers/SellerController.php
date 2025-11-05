<?php
namespace App\Controllers;

class SellerController {
	public function dashboard() {
		// Render seller dashboard view
		require_once __DIR__ . '/../Views/seller/dashboard.php';
	}
}

?>