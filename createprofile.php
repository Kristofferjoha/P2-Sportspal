<?php
// Include the database.php file to establish a connection to the database
require 'database.php';
// Connect to the database
session_start();
$conn = connect_to_db();

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Retrieve form data
    $age = isset($_POST["ageInput"]) ? ($_POST["ageInput"]) : "";
    $phone = isset($_POST["phoneInput"]) ? $_POST["phoneInput"] : "";
    $gender = isset($_POST["genderInput"]) ? $_POST["genderInput"] : "";
    $niveau = isset($_POST["experienceInput"]) ? json_encode($_POST["experienceInput"]) : "";
    $goals = isset($_POST["goals"]) ? json_encode($_POST["goals"]) : "[]";
    $interests = isset($_POST["interests"]) ? json_encode($_POST["interests"]) : "[]";
    
    // Combine music, movies, sports, and hobbies into a single array
    // Retrieve user_id from session

    $user_id = $_SESSION['user_id'];

    // Prepare SQL statement to update the user's profile
    $sql = "UPDATE Users SET age=?, phone=?, gender=?, niveau=?, goals=?, interests=? WHERE user_id=?";
    
    // Prepare and bind parameters
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Error preparing statement: " . $conn->error);
    }
    $stmt->bind_param("isssssi", $age, $phone, $gender, $niveau, $goals, $interests, $user_id);
    
    // Run SQL statement
    if ($stmt->execute()) {
        // Redirect user to home page or any other desired location
        header("Location: home.html");
        exit;
    } else {
        echo "Error executing statement: " . $stmt->error;
    }
}

// Close statement
$stmt->close();

// Close database connection
$conn->close();
?>
