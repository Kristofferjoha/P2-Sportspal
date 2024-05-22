<?php
function connect_to_db() {
    // Load database credentials from environment variables
    $servername = getenv('DB_SERVER');
    $username = getenv('DB_USERNAME');
    $password = getenv('DB_PASSWORD');
    $dbname = getenv('DB_NAME');

    // Establishing connection to the database
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        error_log("Connection failed: " . $conn->connect_error);
        die("Sorry, we are experiencing technical difficulties. Please try again later.");
    }

    return $conn;
}

?>
