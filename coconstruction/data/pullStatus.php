<?php
$servername = "localhost";
$username = "root";
$password = "ConstructCity";
$dbname = "city";
date_default_timezone_set('Asia/Taipei');
header('Content-Type: application/json; charset=utf-8');

error_reporting (0);

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
mysqli_set_charset($conn, "utf8");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$sql = "SELECT * FROM status";
$result = $conn->query($sql);

$rows = array();
while($r = mysqli_fetch_assoc($result)) {
    $rows[] = $r;
}
print json_encode($rows);
$conn->close();

?>
