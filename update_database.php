<?php
// Database configuration
$servername = "mysql43.unoeuro.com";
$username = "sportspal_p2_dk";
$password = "ckd4Grgyabmn9B2wReh5";
$dbname = "sportspal_p2_dk_db";

// Get the POST data
$data = json_decode(file_get_contents("php://input"));

session_start();
// Check if all required fields are present
if (isset($data->date, $data->interests, $data->goals, $data->location, $data->timespan, $data->activities, $data->adjustedPercentage)) {
    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    $current = $_SESSION["user_id"];

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $user2 = $data->userId;

    // Check if there are existing matches for the current user
    $stt_existing = $conn->prepare("SELECT * FROM BestMatches WHERE user2 = ?");
    $stt_existing->bind_param("i", $user2);
    $stt_existing->execute();
    $result_existing = $stt_existing->get_result();

    // If there are existing matches, delete them
    if ($result_existing->num_rows > 0) {
        $stmt_delete = $conn->prepare("DELETE FROM BestMatches WHERE user2 = ?");
        $stmt_delete->bind_param("i", $user2);
        $stmt_delete->execute();
        $stmt_delete->close();
    }
    

    // Prepare and bind the SQL statement for inserting new matches
    $stmt_insert = $conn->prepare("INSERT INTO BestMatches (user1, user2, match_date, common_interests, common_goals, location, timespan, activity, match_percentage) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt_insert->bind_param("isssssssd", $user1, $user2, $match_date, $common_interests, $common_goals, $location, $timespan, $activity, $match_percentage);

    // Set the parameters and execute the statement
    $user1 = $current;
    $user2 = $data->userId;
    $match_date = $data->date;
    $common_interests = $data->interests;
    $common_goals = $data->goals;
    $location = $data->location;
    $timespan = $data->timespan;
    $activity = $data->activities;
    $match_percentage = $data->adjustedPercentage;

    if ($stmt_insert->execute()) {
        // Return success response
        echo json_encode(array("message" => "Data inserted successfully"));
    } else {
        // Return error response
        echo json_encode(array("message" => "Error: " . $stmt_insert->error));
    }

    // Close statement and connection
    $stmt_insert->close();
    $stt_existing->close();
    $conn->close();
} else {
    // Return error response if required fields are missing
    echo json_encode(array("message" => "Missing required fields"));
}
?>
