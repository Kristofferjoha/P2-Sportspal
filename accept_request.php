<?php
// Include the database.php file to establish a connection to the database
require 'database.php';
// Connect to the database
$conn = connect_to_db();

// Check if request_id is provided in the POST request
if (isset($_POST['request_id'])) {
    // Get the request ID from the POST request
    $request_id = $_POST['request_id'];

    // SQL to move the request to MatchingHistory
    $sqlfirst = "INSERT INTO MatchingHistory (UserID1, UserID2, MatchDate) SELECT user_id_requester, user_id_requested, date FROM UserRequests WHERE request_id = $request_id";

    // SQL to delete the request from UserRequests
    $sql = "DELETE FROM UserRequests WHERE request_id = $request_id";

    if ($conn->query($sqlfirst) === TRUE) {
        // Once the request is moved to MatchingHistory, proceed to delete it from UserRequests
        if ($conn->query($sql) === TRUE) {
            echo "Request deleted successfully";
        } else {
            echo "Error deleting request: " . $conn->error;
        }
    } else {
        echo "Error moving request to MatchingHistory: " . $conn->error;
    }
} else {
    echo "Error: request_id is not provided in the POST request.";
}

$conn->close();
?>
