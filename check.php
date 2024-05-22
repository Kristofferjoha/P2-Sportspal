<?php
require_once 'database.php';

session_start(); // Add this line to start the session
connect_to_db();

// Check if user is logged in
function session_check() {
    // Check if user is logged in
    if (!isset($_SESSION['user_id'])) {
        return "redirect:index.html";
    } else {
        return "no-redirect";
    }
}

echo session_check();