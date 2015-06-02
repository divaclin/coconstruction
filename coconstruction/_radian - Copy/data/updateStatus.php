<?php
$servername = "localhost";
$username = "root";
$password = "ConstructCity";
$dbname = "city";

date_default_timezone_set('Asia/Taipei');
header('Content-Type: application/plain; charset=utf-8');


// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
mysqli_set_charset($conn, "utf8");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$done = $_POST['done'] ;
$statusid = $_POST['statusid'];

$sql = "UPDATE status SET done = CONCAT (done , '$done') WHERE statusid = '$statusid'" ;

if ($conn->query($sql) === TRUE) {
    echo "true" ;
} else {
    echo "Error: " . $sql . "<br>" . $conn->error ;
}

$conn->close();

?>
