<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "navigation";
$destination = $_POST['destination'];

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$sql = "SELECT * FROM classes WHERE classname='$destination'";
$result = $conn->query($sql);

if (!$result) {
    die("Error executing query: " . $conn->error);
}

if (mysqli_num_rows($result) > 0) {
    $row = $result->fetch_assoc();
    $content = "<h2>" . $row['classname'] . "</h2>"
        . "<p>" . $row['description'] . "</p>";


    echo json_encode(array("content" => $content));
} else {

    echo json_encode(array("error" => "No matching record found."));
}

$conn->close();

?>