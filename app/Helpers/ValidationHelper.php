<?php
// app/Helpers/ValidationHelper.php

declare(strict_types=1);

class ValidationHelper
{
    public static function isValidEmail(string $email): bool
    {
        return (bool)filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    public static function isValidStringLength(string $value, int $min, int $max): bool
    {
        $len = mb_strlen($value);
        return $len >= $min && $len <= $max;
    }

    public static function isValidInt(mixed $value): bool
    {
        return filter_var($value, FILTER_VALIDATE_INT) !== false;
    }
}
