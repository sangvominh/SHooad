<?php
require_once __DIR__ . '/../../Models/Seller.php';
require_once __DIR__ . '/../../Models/Shop.php';
require_once __DIR__ . '/../AuthService.php';
require_once __DIR__ . '/../FlashMessageService.php';

class AuthSellerService {
	private $sellerModel;
	private $shopModel;

	public function __construct() {
		$this->sellerModel = new Seller();
		$this->shopModel = new Shop();
	}

	public function login(string $email, string $password): bool {
		$seller = $this->sellerModel->findEmail($email);
		if ($seller && isset($seller['password']) && password_verify($password, $seller['password'])) {
			$shop = $this->shopModel->findShopBySellerId($seller['id']);
			if ($shop && isset($shop['id'])) {
				AuthService::setSellerAuth($seller['id'], $shop['id']);
				return true;
			}
			FlashMessageService::setFlashMessage('error', 'Shop not found for this seller.');
			return false;
		}

		FlashMessageService::setFlashMessage('error', 'Invalid email or password.');
		return false;
	}

	public function signup(string $email, string $plainPassword, string $name, string $shopName, string $shopDescription): bool {
		$hashed = password_hash($plainPassword, PASSWORD_BCRYPT);
		$newSeller = $this->sellerModel->insertSeller($email, $hashed, $name);

		if ($newSeller && isset($newSeller['id'])) {
			$newShop = $this->shopModel->insertShop($newSeller['id'], $shopName, $shopDescription);
			if ($newShop && isset($newShop['id'])) {
				AuthService::setSellerAuth($newSeller['id'], $newShop['id']);
				return true;
			}
			FlashMessageService::setFlashMessage('error', 'Failed to create shop for the seller.');
			return false;
		}

		FlashMessageService::setFlashMessage('error', 'Signup failed. Please try again.');
		return false;
	}

	public function logout(): void {
		AuthService::logout();
	}
}

