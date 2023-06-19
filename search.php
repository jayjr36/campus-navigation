<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "navigation";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve the location name from the GET request
$location_name = $_POST['location'];

$sql = "SELECT location FROM classes WHERE classname = '$location_name'";

$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $location = $row['location'];
    $location_array = explode(',', $location);
    $latitude = $location_array[0];
    $longitude = $location_array[1];

    $response = array('latitude' => $latitude, 'longitude' => $longitude);
    echo json_encode($response);
} else {
    echo "Location not found";
}

$conn->close();
?>