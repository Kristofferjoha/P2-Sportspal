<?php

// Include the database.php file to establish a connection to the database
require 'database.php';
// Connect to the database
session_start();
$conn = connect_to_db();
$recipientId = $_POST['recipient_id'];
$senderId = $_SESSION["user_id"];

// Query to fetch data from BestMatches
$sql = "SELECT * FROM BestMatches WHERE user1 = $senderId AND user2 = $recipientId";
$result = $conn->query($sql);

// Fetching and storing match data
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    
    // Prepare and execute SQL statement to insert request into the database
    $stmt = $conn->prepare("INSERT INTO UserRequests (user_id_requester, user_id_requested, date, timespan, activities, location, matching_interests, matching_goals, match_percentage) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iissssssd", $senderId, $recipientId, $date, $timespan, $activities, $location, $matching_interests, $matching_goals, $match_percentage);

    // Set parameters
    $date = $row['match_date'];
    $timespan = $row['timespan'];
    $activities = $row['activity'];
    $location = $row['location'];
    $matching_interests = $row['common_interests'];
    $matching_goals = $row['common_goals'];
    $match_percentage = $row['match_percentage'];

    // Execute the prepared statement
    if ($stmt->execute()) {
        echo "Request sent successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }
} else {
    echo "No matching data found in BestMatches.";
}

// Close statement and database connection
$stmt->close();
$conn->close();
?>
