<?php
// Include the database.php file to establish a connection to the database
require 'database.php';
// Connect to the database
session_start();
$conn = connect_to_db();

$current_user = $_SESSION['user_id']; // Set the current user's ID

// Query to fetch match the name of the current user
$sql = "SELECT name FROM Users WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $current_user);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    echo "<h2>" . $row['name'] . "!" . "</h2>";
}
$stmt->close();
$conn->close();
?>
