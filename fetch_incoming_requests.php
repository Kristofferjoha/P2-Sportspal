<?php
// Include the database.php file to establish a connection to the database
require 'database.php';
// Connect to the database
session_start();
$conn = connect_to_db();

$current = $_SESSION["user_id"];

$sql = "SELECT * FROM UserRequests WHERE user_id_requested = $current";
$result = $conn->query($sql);

$requests = array();

if ($result->num_rows > 0) {

    // Output data of each row
    while ($row = $result->fetch_assoc()) {
        $user_requester = $row["user_id_requester"];
        $secondsql = "SELECT * FROM Users WHERE user_id = $user_requester";
        $results = $conn->query($secondsql);
        $rows = $results->fetch_assoc();
        $request = array(
            "user_id_requester" => $rows["name"],
            "date" => $row["date"],
            "common_interests" => json_decode($row["matching_interests"]),
            "common_goals" => json_decode($row["matching_goals"]),
            "location" => json_decode($row["location"]),
            "timespan" => json_decode($row["timespan"]),
            "activities" => json_decode($row["activities"]),
            "match_percentage" => $row["match_percentage"],
            "request_id" => $row["request_id"]
                         
        );
        $requests[] = $request;
    }
} else {
    echo "0 results";
}

$conn->close();

// Return the JSON response
header('Content-Type: application/json');
echo json_encode($requests);
?>
