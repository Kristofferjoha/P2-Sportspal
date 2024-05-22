<?php
// Include the database.php file to establish a connection to the database
require 'database.php';
// Connect to the database
$conn = connect_to_db();

// Check if user_id is provided in the query string
if(isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];

    // Fetch ratings for the user
    $sql = "SELECT * FROM Ratings WHERE user_id = '$user_id'";

    // Execute SQL query
    $result = $conn->query($sql);

    // Check if there are any ratings for the user
    if ($result->num_rows > 0) {
        // Fetch ratings data
        $ratings = array();
        while($row = $result->fetch_assoc()) {
            $ratings[] = $row;
        }

        // Encode ratings data as JSON
        echo json_encode($ratings);
    } else {
        // No ratings found for the user
        echo json_encode(array());
    }
} else {
    // user_id is not provided in the query string
    echo json_encode(array('error' => 'User ID is required'));
}

// Close database connection
$conn->close();
?>
