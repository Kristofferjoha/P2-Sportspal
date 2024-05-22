<?php

// Include the database.php file to establish a connection to the database
require 'database.php';
// Connect to the database
session_start();
$conn = connect_to_db();
$current_user = $_SESSION['user_id']; // Set the current user's ID

// Query to fetch user requests where current user is the one being requested
$sql = "SELECT user_id_requester FROM UserRequests WHERE user_id_requested = $current_user";
$result = $conn->query($sql);

// Displaying user request data in table format
if ($result->num_rows > 0) {
    echo "<table>";
    echo "<tr><th>Requester's Name</th><th>Timespan</th><th>Activities</th><th>Match Date</th></tr>";
    $count = 0;
    while ($count < 5 && $row = $result->fetch_assoc()) {
        // Prepare statement to fetch details of the user who made the request from Users table
        $requester_id = $row['user_id_requester'];
        $sql_request = "SELECT name, timespan, activities, date FROM Users WHERE user_id = $requester_id";
        $requestResult = $conn->query($sql_request);
        if ($requestRow = $requestResult->fetch_assoc()) {
            $timespan = json_decode($requestRow["timespan"], true);
            $activities = json_decode($requestRow["activities"], true);
            
            echo "<tr>";
            echo "<td>" . $requestRow["name"] . "</td>"; // Display the name of the requester
            echo "<td>" . $timespan . "</td>";
            echo "<td>" . $activities . "</td>";
            echo "<td>" . $requestRow['date'] . "</td>";
            echo "</tr>";
        }
        $count++;
    }
    echo "</table>";
} else {
    echo "No requests found.";
}

$conn->close();

?>
