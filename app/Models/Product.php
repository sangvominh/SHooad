<?php
// app/Models/Product.php

declare(strict_types=1);

class Product
{
    public function __construct(private PDO $db) {}

    public function getTotalCount(int $sellerId): int
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) AS total FROM products WHERE seller_id = :sid AND status != 'deleted'");
        $stmt->execute([':sid' => $sellerId]);
        return (int)$stmt->fetchColumn();
    }

    public function getActiveCount(int $sellerId): int
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) AS total FROM products WHERE seller_id = :sid AND status = 'active'");
        $stmt->execute([':sid' => $sellerId]);
        return (int)$stmt->fetchColumn();
    }

    public function getRecentActiveProducts(int $sellerId, int $limit = 20): array
    {
        $limit = max(1, min(20, $limit));
        $stmt = $this->db->prepare("SELECT id, name, price, status, modified_at FROM products WHERE seller_id = :sid AND status = 'active' ORDER BY modified_at DESC LIMIT :lim");
        $stmt->bindValue(':sid', $sellerId, PDO::PARAM_INT);
        $stmt->bindValue(':lim', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public function pause(int $productId, int $sellerId): bool
    {
        $sql = "UPDATE products SET status = 'paused', modified_at = CURRENT_TIMESTAMP WHERE id = :pid AND seller_id = :sid AND status = 'active'";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':pid' => $productId, ':sid' => $sellerId]);
    }

    public function delete(int $productId, int $sellerId, string $confirmName): bool
    {
        // Fetch product name to confirm
        $stmt = $this->db->prepare("SELECT name FROM products WHERE id = :pid AND seller_id = :sid AND status IN ('active','paused')");
        $stmt->execute([':pid' => $productId, ':sid' => $sellerId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return false;
        }
        if ($row['name'] !== $confirmName) {
            throw new InvalidArgumentException('Product name does not match. Deletion cancelled.');
        }
        $sql = "UPDATE products SET status = 'deleted', modified_at = CURRENT_TIMESTAMP WHERE id = :pid AND seller_id = :sid AND status IN ('active','paused')";
        $update = $this->db->prepare($sql);
        return $update->execute([':pid' => $productId, ':sid' => $sellerId]);
    }
}
