<?php
// app/Models/Seller.php

declare(strict_types=1);

class Seller
{
    public function __construct(private PDO $db) {}

    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT id, name, email, account_status FROM sellers WHERE id = ? AND account_status = 'active'");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public function findByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare("SELECT id, name, email, account_status FROM sellers WHERE email = ? AND account_status = 'active'");
        $stmt->execute([$email]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }
}
