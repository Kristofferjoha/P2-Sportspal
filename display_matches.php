<?php
// Include the database.php file to establish a connection to the database
require 'database.php';
// Connect to the database
session_start();
$conn = connect_to_db();

// Retrieve the current user's user_ID
$current_user = $_SESSION["user_id"];

// Fetch match data from database
$sql = "SELECT * FROM MatchingHistory WHERE UserID1 = '$current_user' OR UserID2 = '$current_user' ";
$result = $conn->query($sql);

// Display the match data in a table
if ($result->num_rows > 0) {
    echo "<table style='width: 100%;'>";
    echo "<tr><th style='text-align: center;'>Matched User's Name</th><th style='text-align: center;'>Match Date</th><th>Rating</th></tr>";
    while($row = $result->fetch_assoc()) {
        $yourUserID = $row["UserID1"];
        $matchedUserID = $row["UserID2"];
        if ($yourUserID != $current_user) {
            $matchedUserID = $row["UserID1"];
            $yourUserID = $row["UserID2"];
        }
        $yourUserQuery = "SELECT * FROM Users WHERE user_id = '$yourUserID'";
        $matchedUserQuery = "SELECT * FROM Users WHERE user_id = '$matchedUserID'";
        $yourUserResult = $conn->query($yourUserQuery);
        $matchedUserResult = $conn->query($matchedUserQuery);
        if ($yourUserResult->num_rows > 0 && $matchedUserResult->num_rows > 0) {
            $yourUserRow = $yourUserResult->fetch_assoc();
            $matchedUserRow = $matchedUserResult->fetch_assoc();
            echo "<tr>";
            echo "<td style='text-align: center;'>" . $matchedUserRow["name"] . "</td>";
            echo "<td style='text-align: center;'>" . $row["MatchDate"] . "</td>";
            $raterusername = $matchedUserRow["username"];
            // Check if rating exists for the match
            $rating_query = "SELECT * FROM Ratings WHERE user_id = '$current_user' AND rater_username = '$raterusername'";
            $rating_result = $conn->query($rating_query);
            if ($rating_result->num_rows > 0) {
                // If rating exists, display it
                $rating_row = $rating_result->fetch_assoc();
                echo "<td style='text-align: center;'>Rating: " . $rating_row["score"] . "</td>";
            } else {
                // If rating does not exist, display the form for rating submission
                echo "<td style='text-align: center;'>";
                echo "<form action='update_ratings.php' method='post'>";
                echo "<input type='hidden' name='match_id' value='" . $row["MatchID"] . "'>";
                echo "<input type='hidden' name='user2_username' value='" . $matchedUserRow["username"] . "'>";
                echo "<input type='hidden' name='current_user' value='" . $row["UserID1"] . "'>";
                echo "<select name='rating'>";
                // Add options for ratings
                for ($i = 0; $i <= 10; $i++) {
                    echo "<option value='$i'>$i</option>";
                }
                echo "</select>";
                echo "<input type='submit' value='Submit Rating'>";
                echo "</form>";
                echo "</td>";
            }
            echo "</tr>";
        }
    }
    echo "</table>";
} else {
    echo "No matches found.";
}

$conn->close();
?>
