<?php
// app/Core/Database.php

declare(strict_types=1);

class Database
{
    private static ?PDO $connection = null;

    public static function getConnection(): PDO
    {
        if (self::$connection instanceof PDO) {
            return self::$connection;
        }

        $config = self::loadEnv(__DIR__ . '/../../config/.env');
        $host = $config['DB_HOST'] ?? 'localhost';
        $db   = $config['DB_NAME'] ?? '';
        $user = $config['DB_USER'] ?? '';
        $pass = $config['DB_PASS'] ?? '';
        $charset = 'utf8mb4';

        $dsn = "mysql:host={$host};dbname={$db};charset={$charset}";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        try {
            self::$connection = new PDO($dsn, $user, $pass, $options);
            return self::$connection;
        } catch (PDOException $e) {
            error_log('Database connection failed: ' . $e->getMessage());
            http_response_code(500);
            echo '<h1>Database Error</h1><p>Unable to connect to the database.</p>';
            exit;
        }
    }

    private static function loadEnv(string $path): array
    {
        $vars = [];
        if (!file_exists($path)) {
            return $vars;
        }
        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (strpos(trim($line), '#') === 0) {
                continue;
            }
            [$key, $value] = array_pad(explode('=', $line, 2), 2, '');
            $vars[trim($key)] = trim($value);
        }
        return $vars;
    }
}
