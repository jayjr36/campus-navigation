<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "navigation";
$name = $_POST['username'];
$discrepancy = 5;

$conn = mysqli_connect($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$input_chars = str_split($name);
$conditions = array();

for($i =0; $i<strlen($name); $i++){
    $sub_str = substr($name, max(0, $i-$discrepancy), $discrepancy*2 +1);
    $conditions[] = "name LIKE '%$sub_str%'";
}

$condition = implode(" OR ", $conditions);

$sql = "SELECT * FROM employees WHERE $condition";
$result = mysqli_query($conn,$sql);
    if(mysqli_num_rows($result) > 0){

        while ($row = mysqli_fetch_assoc($result)){
            echo $row["name"]."<br>".$row["title"]."<br>".$row["description"];
        }
    }else{
        echo  "0 results";
    }

$conn->close();

?>