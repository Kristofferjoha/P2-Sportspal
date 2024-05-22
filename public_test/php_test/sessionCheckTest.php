<?php
use PHPUnit\Framework\TestCase;

require_once 'check.php';

class sessionCheckTest extends TestCase {

    protected function setUp(): void {
        if (!session_id()) {
            session_start();
        }
    }

    public function testSessionCheckWithID() {

        $_SESSION['user_id'] = 1;

        $result = session_check();

        $this->assertEquals("no-redirect", $result);
    }

    public function testSessionCheckWithoutID() {

        unset($_SESSION['user_id']);
 
        $result = session_check();

        $this->assertEquals("redirect:index.html", $result);
    }

    protected function tearDown(): void {
        session_destroy();
    }
}

?>