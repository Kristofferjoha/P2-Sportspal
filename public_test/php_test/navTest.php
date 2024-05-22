<?php
use PHPUnit\Framework\TestCase;

require_once 'nav.php';

class navTest extends TestCase {

    protected function setUp(): void {
        if (!session_id()) {
            session_start();
        }
    }
   
    public function testGoToHomeWithID () {
        $page = 'home';
        $current_user = 1;
        $result = navigation($current_user, $page);

        $this->assertEquals("home.html", $result);

    }

    public function testGoToHomeWithoutID () {
      $page = 'home';
      $current_user = null;
      $result = navigation($current_user, $page);

      $this->assertEquals("index.html", $result);

  }

    public function testLogoutButton () {
      $page = 'index';
      $current_user = 1;
      $result = navigation($current_user, $page);

      $this->assertEquals("index.html", $result);
      $this->assertFalse(session_id() !== '');

  }
  
  protected function tearDown(): void {
    if (session_id()) {
    session_destroy();
    }
  }
}
?>