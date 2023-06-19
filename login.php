<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "navigation";
$email = $_POST['email'];
$password2 = $_POST['password'];
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM users WHERE email = '$email' && password = '$password2'";
$result = $conn->query($sql);

if($result -> num_rows>0){
    $_SESSION['email']=$email;
    $_SESSION['logged in'] = true;
    header("Location: home.html");
}else{
    echo "Invalid email or password";
}
$conn->close();
?>