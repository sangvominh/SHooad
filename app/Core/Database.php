<?php
class Database {
    private $host = 'sql113.infinityfree.com';
    private $username = 'if0_40454116';
    private $password = '3RIrrqup4pCyko';
    private $dbname = 'if0_40454116_shooad';

    // BIẾN TĨNH (STATIC): Đây là chìa khóa để sửa lỗi max_user_connections
    // Nó sẽ lưu giữ kết nối để dùng chung cho toàn bộ trang web
    private static $mysqli_connection = null;
    private static $pdo_connection = null;

    private $connection; // Biến này để giữ tương thích với code cũ của bạn

    public function __construct() {
        $this->connect();
    }

    private function connect() {
        // KIỂM TRA: Nếu chưa có kết nối nào được tạo trước đó thì mới tạo mới
        if (self::$mysqli_connection === null) {
            self::$mysqli_connection = new mysqli(
                $this->host,
                $this->username,
                $this->password,
                $this->dbname
            );

            if (self::$mysqli_connection->connect_error) {
                die("Connection failed: " . self::$mysqli_connection->connect_error);
            }

            self::$mysqli_connection->set_charset("utf8mb4");
        }

        // Gán kết nối chung vào biến cục bộ để các hàm khác sử dụng được
        $this->connection = self::$mysqli_connection;
    }

    public function getConnection() {
        return $this->connection;
    }

    public function getPDO() {
        // Tương tự với PDO: Chỉ tạo 1 lần duy nhất
        if (self::$pdo_connection === null) {
            try {
                $pdo = new PDO(
                    "mysql:host={$this->host};dbname={$this->dbname};charset=utf8mb4",
                    $this->username,
                    $this->password
                );
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                
                self::$pdo_connection = $pdo;
            } catch (PDOException $e) {
                die("PDO Connection failed: " . $e->getMessage());
            }
        }
        
        return self::$pdo_connection;
    }
}
?>