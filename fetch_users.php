<?php
// Database connection parameters
$servername = "mysql43.unoeuro.com";
$username = "sportspal_p2_dk";
$password = "ckd4Grgyabmn9B2wReh5";
$database = "sportspal_p2_dk_db";

try {
    // Create a new PDO instance
    $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
    
    // Set PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Check if activities are provided in the GET request
    if(isset($_GET['activities']) && isset($_GET['timespan']) && isset($_GET['location']) && isset($_GET['date']) && isset($_GET['niveau'])) {
        $activities = json_decode($_GET['activities']);
        $timespan = json_decode($_GET['timespan']);
        $location = json_decode($_GET['location']);
        $date = $_GET['date'];
        $niveau = json_decode($_GET['niveau']);
        
        // Retrieve users that match the provided activities
        $sql = "SELECT * FROM Users WHERE JSON_CONTAINS(activities, :activities) AND JSON_CONTAINS(timespan, :timespan) AND JSON_CONTAINS(location, :location) AND date = :date AND JSON_CONTAINS(niveau, :niveau)"; //Før hed: AND JSON_CONTAINS(date, :date);
        $stmt = $conn->prepare($sql);
        
        // Bind the parameter
        $stmt->bindParam(':activities', json_encode($activities));
        $stmt->bindParam(':timespan', json_encode($timespan));
        $stmt->bindParam(':location', json_encode($location));
        $stmt->bindParam(':date', $date); //Før stod der $stmt->bindParam(':date', json_encode($date));
        $stmt->bindParam(':niveau', json_encode($niveau));
        
        // Execute the query
        $stmt->execute();
        
        // Fetch all matching users
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Return the users as JSON
        echo json_encode($users);
    } else if(isset($_GET['username'])) {
        $username = $_GET['username'];

        // Prepare SQL statement with a placeholder for the username
        $stmt = $conn->prepare("SELECT * FROM Users WHERE username = ?");
        
        // Bind the parameter
        $stmt->bindParam(1, $username);
        
        // Execute the query
        $stmt->execute();
        
        // Fetch user data
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Check if any rows were returned
        if ($user) {
            // Return the user data as JSON
            echo json_encode($user);
        } else {
            // No user found with the given username
            echo json_encode(["error" => "User not found"]);
        }
    } else {
        // If activities are not provided in the GET request, return all users
        $stmt = $conn->query("SELECT * FROM Users");
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Return the users as JSON
        echo json_encode($users);
    }
} catch(PDOException $e) {
    // Handle database connection error
    echo json_encode(["error" => "Connection failed: " . $e->getMessage()]);
}
?>
