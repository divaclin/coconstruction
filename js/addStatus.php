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

$d = $_POST['deviceid'] ;
$b = $_POST['behavior'] ;
$o = $_POST['object'] ;
$t = $_POST['object_type'];

$sql = "INSERT INTO status (device, behavior , object_type , object ) VALUES ( '$d', '$b' , '$t' , '$o')";

if ($conn->query($sql) === TRUE) {
    echo "{'status' : 'true'}" ;
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();

?>
