<?php
// app/Helpers/SecurityHelper.php

declare(strict_types=1);

class SecurityHelper
{
    public static function generateCsrfToken(): string
    {
        $token = bin2hex(random_bytes(32));
        $_SESSION['csrf_token'] = $token;
        return $token;
    }

    public static function validateCsrfToken(?string $token): bool
    {
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], (string)$token);
    }

    public static function sanitizeString(string $value, int $maxLen = 10000): string
    {
        $value = trim($value);
        if (strlen($value) > $maxLen) {
            $value = substr($value, 0, $maxLen);
        }
        return htmlspecialchars(strip_tags($value), ENT_QUOTES, 'UTF-8');
    }

    public static function escape(mixed $value): string
    {
        return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
    }
}
