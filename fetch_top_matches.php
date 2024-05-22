<?php
// Include the database.php file to establish a connection to the database
require 'database.php';
// Connect to the database
session_start();
$conn = connect_to_db();
$current = $_SESSION["user_id"];

// Query to fetch top 5 matches
$sql = "SELECT * FROM BestMatches WHERE user1 = $current";
$result = $conn->query($sql);

$matches = array();

// Fetching and storing matches data
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        // Decode common interests array
        $common_interests = json_decode($row['common_interests']);
        if (is_array($common_interests)) {
            $common_interests_string = '';
            foreach ($common_interests as $interest) {
                $common_interests_string .= $interest . ', '; // Add space between interests
            }
            $common_interests_string = trim($common_interests_string); // Remove trailing space
        } else {
            $common_interests_string = $common_interests;
        }

        // Decode common goals array
        $common_goals = json_decode($row['common_goals']);
        if (is_array($common_goals)) {
            $common_goals_string = '';
            foreach ($common_goals as $goal) {
                $common_goals_string .= $goal . ', '; // Add space between goals
            }
            $common_goals_string = trim($common_goals_string); // Remove trailing space
        } else {
            $common_goals_string = $common_goals;
        }

        $sql_name = "SELECT name FROM Users WHERE user_id = " . $row['user2'];
        $result_name = $conn->query($sql_name);
        if ($result_name->num_rows > 0) {
            $row_name = $result_name->fetch_assoc();
        }
        $match = array(
            'user2' => $row_name['name'],
            'match_date' => $row['match_date'],
            'common_interests' => $common_interests_string,
            'common_goals' => $common_goals_string,
            'location' => json_decode($row['location']),
            'timespan' => json_decode($row['timespan']),
            'activity' => json_decode($row['activity']),
            'match_percentage' => $row['match_percentage']
        );

        // Check if a request already exists for the current match
        $requestExists = checkRequestExists($conn, $row['user2']);
        if ($requestExists) {
            $match['request_status'] = "Request Sent";
        } else {
            $match['request_status'] = "Send Request";
        }
        
        $matches[] = $match;
    }
}

// Close database connection
$conn->close();

// Sending matches data as JSON response
header('Content-Type: application/json');
echo json_encode($matches);


// Function to check if a request exists for a given user
function checkRequestExists($conn, $recipientId) {
    $current = $_SESSION["user_id"];
    $sql = "SELECT * FROM UserRequests WHERE user_id_requester = $current AND user_id_requested = $recipientId";
    $result = $conn->query($sql);
    return $result->num_rows > 0;
}
?>
