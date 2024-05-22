<?php

// Include the database.php file to establish a connection to the database
require 'database.php';
// Connect to the database
session_start();
$conn = connect_to_db();
$current_user = $_SESSION['user_id']; // Set the current user's ID

// Query to fetch match data where current_user is either user1 or user2
$sql = "SELECT * FROM MatchingHistory WHERE UserID1 = $current_user OR UserID2 = $current_user";
$result = $conn->query($sql);

$matchesDisplayed = false;
$missingRatingsCount = 0;

// Iterate over each match
while ($row = $result->fetch_assoc()) {
    // Determine the other user's ID
    $other_user_id = $row['UserID1'] == $current_user ? $row['UserID2'] : $row['UserID1'];

    // Fetch the username for the other user
    $user_query = "SELECT username, name FROM Users WHERE user_id = $other_user_id";
    $user_result = $conn->query($user_query);
    $matched_user = $user_result->fetch_assoc();
    $matched_user_username = $matched_user['username'];
    // Check if there is an existing rating by this user for the match
    $rating_query = "SELECT score FROM Ratings WHERE user_id = $current_user AND rater_username =";
    $rating_result = $conn->query($rating_query);

    if ($rating_result->num_rows == 0) {
        // Begin table output if first valid match and less than 3 missing ratings
        if (!$matchesDisplayed && $missingRatingsCount < 3) {
            echo "<table>";
            echo "<tr><th>Matched User's Name</th><th>Match Date</th><th>Rating</th></tr>";
            $matchesDisplayed = true;
        }

        // Display the match if less than 3 missing ratings
        if ($missingRatingsCount < 3) {
            echo "<tr>";
            echo "<td>" . $matched_user["name"] . "</td>";
            echo "<td>" . $row["MatchDate"] . "</td>";
            echo "<td>";
            echo "<form action='update_ratings.php' method='post'>";
            echo "<input type='hidden' name='match_id' value='" . $row["MatchID"] . "'>";
            echo "<input type='hidden' name='user2_username' value='" . $matched_user["username"] . "'>";
            echo "<input type='hidden' name='current_user' value='$current_user'>";
            echo "<select name='rating' class='dropdown'>";
            for ($i = 0; $i <= 10; $i++) {
                echo "<option value='$i'>$i</option>";
            }
            echo "</select>";
            echo "<input type='submit'  class='button' value='Submit Rating'>";
            echo "</form>";
            echo "</td>";
            echo "</tr>";

            $missingRatingsCount++;
        }
    }
}

// End the table if any matches were displayed
if ($matchesDisplayed) {
    echo "</table>";
} else {
    echo "No unrated matches found.";
}

$conn->close();
?>
