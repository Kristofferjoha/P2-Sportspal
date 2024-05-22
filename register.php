<?php
// Include the database.php file to establish a connection to the database
require 'database.php';
// Connect to the database
session_start();
$conn = connect_to_db();

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $name = $_POST['full_name'];
    $username = $_POST['username'];
    $password = $_POST['password']; // Note: Remember to hash the password before storing it

    // Prepare SQL statement to check if the username exists
    $sql = "SELECT * FROM Users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if any rows were returned
    if ($result->num_rows > 0) {
        // Username already exists, display error message
        setcookie("username_taken", "Username already taken", time() + (86400), "/");
        header("Location: index.html");
    } else {
        // Username is available, proceed with signup
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insert user data into the database
        $insertSql = "INSERT INTO Users (name, username, password) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($insertSql);
        $stmt->bind_param("sss", $name, $username, $hashedPassword);

        if ($stmt->execute()) {
            // Get the newly inserted user's ID
            $user_id = $stmt->insert_id;
            
            // Store user_id and username in session
            $_SESSION['user_id'] = $user_id;
            $_SESSION['name'] = $name;
            
            // Redirect user to signup profile page or any other desired location
            header("Location: signupprofile.html");
            exit;
        } else {
            header("Location: index.html");
        }
    }
}

// Close statement
$stmt->close();

// Close database connection
$conn->close();
?>
