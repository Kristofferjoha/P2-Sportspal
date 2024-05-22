<?php

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    //Initiate connection to the database
    $servername = "mysql43.unoeuro.com";
    $username = "sportspal_p2_dk";
    $password = "ckd4Grgyabmn9B2wReh5";
    $database = "sportspal_p2_dk_db";

    $activity = $_POST['exerciseType'];
    $date = $_POST['date'];
    $time = $_POST['timeOfDay'];
    
    //If location is set and not empty, then the selected location is stored in the variable $selectedLocation
    if (isset($_POST["location"]) && !empty($_POST["location"])) {
        //henter den valgte location
        $selectedLocation = $_POST['location'];
    } else {
        //Intet valgt
        //echo "Please select an option!";
    }

    $userId = $_SESSION['user_id'];

    $conn = new mysqli($servername, $username, $password, $database);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $encodedActivity = json_encode($activity);
    $encodedDate = json_encode($date);
    $encodedTime = json_encode($time);
    $encodedLocation = json_encode($selectedLocation);

    //Update the database with the new activity
    $sql = "UPDATE Users SET activities = '$encodedActivity', date = '$date', timespan = '$encodedTime', location = '$encodedLocation' WHERE user_id = '$userId'";


    if ($conn->query($sql) === TRUE) {
        header("Location: requestAndMatches.html"); 
        exit;
    } else {
        //echo "Error updating fields: " . $conn->error;
        //Lav exec("node navnetPåJsFilen.js"); 
        echo '<script type="text/JavaScript">  
        alert("Error"); 
        </script>' 
        ; 
    }

    //Close connection
    $conn->close();
    

    //Laver response
    $response = [
        'message' => 'POST request processed successfully',
        'activityForm' => $data
    ];

} else {
    echo 'Invalid request method';
}
?>
