<?php
require_once __DIR__ . '/../Models/Review.php';
require_once __DIR__ . '/FlashMessageService.php';

class ReviewService {
    private $reviewModel;

    public function __construct() {
        $this->reviewModel = new Review();
    }

    public function submitReview($customerId, $productId, $rating, $comment = null) {
        // Validate rating
        if ($rating < 1 || $rating > 5) {
            FlashMessageService::setFlashMessage('error', 'Rating phải từ 1 đến 5 sao.');
            return false;
        }

        // Check if user can review this product
        if (!$this->reviewModel->canUserReviewProduct($customerId, $productId)) {
            FlashMessageService::setFlashMessage('error', 'Bạn chỉ có thể đánh giá sản phẩm từ đơn hàng đã hoàn thành.');
            return false;
        }

        // Check if user already reviewed this product
        if ($this->reviewModel->hasUserReviewedProduct($customerId, $productId)) {
            FlashMessageService::setFlashMessage('error', 'Bạn đã đánh giá sản phẩm này rồi.');
            return false;
        }

        // Create review
        if ($this->reviewModel->create($productId, $customerId, $rating, $comment)) {
            FlashMessageService::setFlashMessage('success', 'Cảm ơn bạn đã đánh giá sản phẩm!');
            return true;
        }

        FlashMessageService::setFlashMessage('error', 'Có lỗi xảy ra khi gửi đánh giá. Vui lòng thử lại.');
        return false;
    }

    public function getProductReviews($productId) {
        return $this->reviewModel->getByProductId($productId);
    }

    public function getProductRatingSummary($productId) {
        return $this->reviewModel->getAverageRating($productId);
    }

    public function canUserReviewProduct($customerId, $productId) {
        return $this->reviewModel->canUserReviewProduct($customerId, $productId) &&
               !$this->reviewModel->hasUserReviewedProduct($customerId, $productId);
    }
}