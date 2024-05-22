<?php
// Include the database.php file to establish a connection to the database
require 'database.php';
// Connect to the database
session_start();
$conn = connect_to_db();
$current_user = $_SESSION['user_id']; // Set the current user's ID
$time=time();

// Query to fetch user requests where current user is the one being requested
$sql = "SELECT * FROM MatchingHistory WHERE UserID1 = $current_user or UserID2 = $current_user and UNIX_TIMESTAMP(MatchDate) > $time" ;
$result = $conn->query($sql);

// Displaying user request data in table format
if ($result->num_rows > 0) {
    echo "<table>";
    echo "<tr><th>Name of match</th><th>Timespan</th><th>Activities</th><th>Match Date</th><th>Phone Number</th></tr>";
    while ($row = $result->fetch_assoc()) {
        // Prepare statement to fetch details of the user who made the request from Users table
        if ($row['UserID1'] == $current_user) {
            $matchedUser = $row['UserID2'];
        } else {
            $matchedUser = $row['UserID1'];
        }
        $sql_activity = "SELECT name, timespan, activities, date, phone FROM Users WHERE user_id = $matchedUser";
        $activityResult = $conn->query($sql_activity);
        if ($userRow = $activityResult->fetch_assoc()) {
            $timespan = json_decode($userRow["timespan"], true);
            $activities = json_decode($userRow["activities"], true);
            $phone = json_decode($userRow["phone"], true);
            
            // Convert JSON arrays to comma-separated strings
            $timespanStr = is_array($timespan) ? implode(", ", $timespan) : $timespan;
            $activitiesStr = is_array($activities) ? implode(", ", $activities) : $activities;
            $phoneStr = is_array($phone) ? implode(", ", $phone) : $phone;
            
            echo "<tr>";
            echo "<td>" . $userRow["name"] . "</td>"; 
            echo "<td>" . $timespanStr . "</td>";
            echo "<td>" . $activitiesStr . "</td>";
            echo "<td>" . $userRow['date'] . "</td>";
            echo "<td>" . $phoneStr . "</td>";
            echo "</tr>";
            
        }
    }
    echo "</table>";
} else {
    echo "No match found.";
}

$conn->close();
?>
