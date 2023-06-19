<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "navigation";
$email = $_POST['email'];
$password2 = $_POST['password'];
$name = $_POST['username'];
$gender = $_POST['gender'];
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$sql = "INSERT INTO `users`(`name`, `email`, `gender`, `password`) VALUES ('$name','$email','$gender','$password2')";

if($conn ->query($sql)===TRUE){
    
    header("Location:login.html");
    exit();

}else{
    echo "Error: ".$sql."<br>".$conn->error;
}
$conn->close();
?>