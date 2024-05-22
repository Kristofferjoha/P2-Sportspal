<?php

// Include the database.php file to establish a connection to the database
require 'database.php';
// Connect to the database
session_start();
$conn = connect_to_db();

$user_id = $_SESSION['user_id'];

// Process form submission including all the data from the form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newName = $_POST['name'];
    $newAge = $_POST['ageInput'];
    $newGender = $_POST['genderInput'];
    $newPhone = $_POST['phoneInput'];
    $jsonPhone = json_encode(['phoneInput' => $newPhone]);
    $newNiveau = $_POST['experienceInput'] ? json_encode($_POST['experienceInput']) : "";
    $newGoals = isset($_POST["goals"]) ? json_encode($_POST["goals"]) : "[]";
    $newInterests = isset($_POST["interests"]) ? json_encode($_POST["interests"]) : "[]";

    $updateSql = "UPDATE Users SET name = ?, age = ?, gender = ?, phone = ?, niveau = ?, goals = ?, interests = ? WHERE user_id = ?";
    $updateStmt = $conn->prepare($updateSql);
    $updateStmt->bind_param("sissssss", $newName, $newAge, $newGender, $newPhone, $newNiveau, $newGoals, $newInterests, $user_id);
    $updateResult = $updateStmt->execute();

    setcookie("Profile", "Successful submit", time() + (86400), "/");
}

//Fetch user data and prepare it for javascript code
$sql = "SELECT name, age, gender, phone, niveau, goals, interests FROM Users WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user_id);
$stmt->execute();
$stmt->bind_result($name, $age, $gender, $phone, $niveau, $goals, $interests);
$stmt->fetch();

//Encode the data to JSON
$userGoals = json_decode($goals);
$userInterests = json_decode($interests);
$userNiveau = json_decode($niveau);


// Save user data in an array
$userData = [
    'name' => $name,
    'age' => $age,
    'gender' => $gender,
    'phone' => $phone,
    'niveau' => $userNiveau,
    'goals' => $userGoals,
    'interests' => $userInterests
];

// Close statement
header('Content-Type: application/json');
echo json_encode($userData);

$stmt->close();
$conn->close();
?>