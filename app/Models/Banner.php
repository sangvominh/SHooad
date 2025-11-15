<?php
class Banner
{
    private $db;

    public function __construct($conn = null)
    {
        // Use provided connection (for testing) or create a Database instance
        if ($conn !== null) {
            $this->db = $conn;
        } else {
            $database = new Database();
            $this->db = $database->getConnection();
        }
    }

    /**
     * @return array<int, array{title:?string,filename:string}>
     */
    public function getActive(): array
    {
        $sql = "SELECT title, filename
                FROM banners
                WHERE is_active = 1
                ORDER BY id ASC";

        $stmt = $this->db->prepare($sql);
        if ($stmt === false) {
            return [];
        }

        $stmt->execute();
        $result = $stmt->get_result();
        if ($result === false) {
            return [];
        }

        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
