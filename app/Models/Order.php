<?php
// app/Models/Order.php

declare(strict_types=1);

class Order
{
    public function __construct(private PDO $db) {}

    public function getMonthlySalesCount(int $sellerId): int
    {
        $sql = "SELECT COUNT(*) FROM orders WHERE seller_id = :sid AND order_status = 'completed' AND YEAR(order_date) = YEAR(CURRENT_DATE) AND MONTH(order_date) = MONTH(CURRENT_DATE)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':sid' => $sellerId]);
        return (int)$stmt->fetchColumn();
    }

    public function getMonthlyRevenue(int $sellerId): float
    {
        $sql = "SELECT COALESCE(SUM(total_amount), 0) FROM orders WHERE seller_id = :sid AND order_status = 'completed' AND YEAR(order_date) = YEAR(CURRENT_DATE) AND MONTH(order_date) = MONTH(CURRENT_DATE)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':sid' => $sellerId]);
        return (float)$stmt->fetchColumn();
    }
}
