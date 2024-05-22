<?php

// Include the database.php file to establish a connection to the database
require 'database.php';
// Connect to the database
session_start();
$conn = connect_to_db();
$current_user = [$_SESSION['user_id']];
// Define the valid page requests
$validPages = ['home', 'profile', 'activity', 'history', 'matches', 'index'];

// Check if the 'page' query parameter exists and is valid
if (isset($_GET['page']) && in_array($_GET['page'], $validPages)) {
    $page = $_GET['page'];
    $location = navigation($current_user, $page);
    header("Location: $location");
} else {
    // Invalid or no page requested, redirect to the home or error page
    header("Location: error.php");
}

function navigation($current_user, $page) {
    // Check for a valid session
    if (isset($current_user)) {
        // User has a valid session id
        // Route to the requested page
        switch ($page) {
            case 'home':
                return "home.html";
                break;
            case 'profile':
                return "profile.html";
                break;
            case 'activity':
                return"activity.html";
                break;
            case 'setting':
                return"setting.html";
                break;
            case 'matches':
                return "requestAndMatches.html";
                break;
            case 'history':
                return "test.html";
                break;
            case 'index':
                session_destroy();
                return "index.html";
                exit;
            default:
                // Optionally handle unknown pages
                return "HTTP/1.0 404 Not Found";
                break;
        }}
    else {
        // User does not have a valid session id, redirect to login page
        return "index.html";
        exit;
    }}

$conn->close();
?>
