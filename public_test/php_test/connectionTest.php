<?php
use PHPUnit\Framework\TestCase;

require_once 'database.php';

class connectionTest extends TestCase {
    private $conn;

    // Set up the database connection before each test
    protected function setUp(): void {
        $this->conn = connect_to_db();
    }

    public function testCorrectDatabaseConnection() {
        $name = "davidconti1";
        $sql = "SELECT username FROM Users where user_id = 1";
        $result = $this->conn->query($sql);
        $row = $result->fetch_assoc();
        $this->assertEquals($name, $row['username']);
    }

    // Close the database connection after each test
    protected function tearDown(): void {
        $this->conn->close();
    }
}
?>