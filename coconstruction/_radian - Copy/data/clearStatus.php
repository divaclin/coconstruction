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




if (isset($_POST['device'])){
	$device = $_POST['device'] ;
	$sql = "DELETE FROM status WHERE device = '$device'" ;
}else if ($_POST['statusid']){
	$statusid = $_POST['statusid'];
	$sql = "DELETE FROM status WHERE statusid = '$statusid'" ;
}else {
	echo "!" ;
	$sql = "DELETE FROM status WHERE statusid = 1" ;
}

if ($conn->query($sql) === TRUE) {
    echo "true" ;
} else {
    echo "Error: " . $sql . "<br>" . $conn->error ;
}

$conn->close();

?>
