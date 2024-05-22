<?php
// Include the database.php file to establish a connection to the database
require 'database.php';
// Connect to the database
$conn = connect_to_db();

// Check if form data is received
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get match ID and rating from the form
    $match_id = $_POST['match_id'];
    $rating = $_POST['rating'];
    $rater_user = $_POST['user2_username'];
    $current_user = $_POST['current_user'];

    // Insert the rating into the database
    $sql = "INSERT INTO Ratings (user_id, rater_username, score) VALUES ('$current_user', '$rater_user', '$rating')";

    // Execute the SQL statement
    if ($conn->query($sql) === TRUE) {
        header("Location: test.html");
    } else {
        echo "Error updating rating: " . $conn->error;
    }
}

// Close the connection
$conn->close();
?>
