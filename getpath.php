<?php
header('Content-Type: application/json');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "navigation";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get origin and destination from form submission
$origin = $_POST['origin'];
$destination = $_POST['destination'];
// Retrieve path from database
$sql = "SELECT path FROM pathways WHERE origin='$origin' AND destination='$destination'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Output data of each row
    while($row = $result->fetch_assoc()) {
        $path_str=$row['path'];
        $path = json_decode($path_str, true);
        if($path === null){
            echo 'error decoding path';
        }else{
            $pathCoordinates = array();
            foreach($path as $coord){
                $lat = $coord['latitude'];
                $lng = $coord['longitude'];
                $pathCoordinates[] = array('lat' => $lat, 'lng' => $lng);
            }
            $response = json_encode($pathCoordinates);
            echo $response;

        }
    }
} else {
    echo "0 results";
}


$conn->close();
?>
