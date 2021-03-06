<?php
$servername = "localhost";
$username = "root";
$password = "ConstructCity";
$dbname = "city";
date_default_timezone_set('Asia/Taipei');
header('Content-Type: application/json; charset=utf-8');


// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
mysqli_set_charset($conn, "utf8");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$sql = "SELECT * FROM status";
$result = $conn->query($sql);


if ($result->num_rows > 0) {
  $rows = urlencode(array());
  while($r = mysqli_fetch_assoc($result)) {
    foreach ($r as $key => $value)
      $r[$key] = ($key == "object") ?  urlencode(str_replace("\"","'",$value)) : urlencode($value) ;
    $rows[] = $r;
  }

  print urldecode(json_encode($rows));
} else {
  echo json_encode(array());
}
$conn->close();

?>
