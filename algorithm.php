<?php
// Include the database.php file to establish a connection to the database
require 'database.php';
// Connect to the database
session_start();
$conn = connect_to_db();

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    
    $user = $_SESSION["user_id"];

    // Prepare SQL statement to check user credentials
    $sql = "SELECT * FROM Users WHERE user_id = '$user'";
    $result = $conn->query($sql);

    // Check if any rows were returned
    if ($result->num_rows > 0) {
        // Fetch user data
        $row = $result->fetch_assoc();
        $encoded = json_encode($row);
        // Trim JSON string if necessary (not usually needed)
        // $encoded = trim($encoded);
        echo $encoded;
        exit;
    } else {
        // Credentials are invalid, display error message or redirect to login page
        $error = ["error" => "Invalid username or password"];
        echo json_encode($error);
    }
}

// Close database connection
$conn->close();
?>
