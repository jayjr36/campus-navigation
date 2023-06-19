<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "navigation";
$email = $_POST['email'];
$password2 = $_POST['password'];
$password3 = $_POST['cpassword'];
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$sql = "update users set password='" . $password3 . "' where email='" . $email . "'";


$result = $conn->query($sql);

if($result -> num_rows>0){
    $_SESSION['email']=$email;
    $_SESSION['logged in'] = true;
    header("Location: login.html");
}else{
    echo "Invalid email or password";
}
$conn->close();
?>