<?php
// Database connection parameters
$servername = "mysql43.unoeuro.com";
$username = "sportspal_p2_dk";
$password = "ckd4Grgyabmn9B2wReh5";
$database = "sportspal_p2_dk_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Start session
session_start();

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve username and password from the form
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare SQL statement to fetch hashed password for the given username
    $sql = "SELECT user_id, password FROM Users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if any rows were returned
    if ($result->num_rows > 0) {
        // Fetch user data
        $row = $result->fetch_assoc();
        // Verify password
        $hashedPassword = $row['password'];

        if (password_verify($password, $hashedPassword)) {
            // Password is correct, set user authentication status in session

            $_SESSION['user_id'] = $row['user_id'];
            $_SESSION['username'] = $username;
            
            // Redirect user to home.html if credentials are valid
            header("Location: home.html");
            exit;
        } else {
            setcookie("invalid_password", "Invalid password", time() + (86400), "/"); //Cookie gemmes i 1 dag. * 86400 med antal dage der skal gemmes
            header("Location: index.html"); 
            exit;
        }
    } else {
        setcookie("invalid_password", "Invalid username", time() + (86400), "/");
        header("Location: index.html");
    }
}

// Close statement
$stmt->close();

// Close database connection
$conn->close();
?>
