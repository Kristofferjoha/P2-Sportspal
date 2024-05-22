<?php
// Include the database.php file to establish a connection to the database
require 'database.php';
// Connect to the database
$conn = connect_to_db();

// Get the request ID from the POST request
$request_id = $_POST['request_id'];

// SQL function to delete the request from the database
$sql = "DELETE FROM UserRequests WHERE request_id = $request_id";

if ($conn->query($sql) === TRUE) {
    echo "Request deleted successfully";
} else {
    echo "Error deleting request: " . $conn->error;
}

// Close the connection
$conn->close();
?>
