<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$conn = new mysqli("localhost", "root", "", "abisobrgy");

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

if(isset($_POST['phone']) && isset($_POST['zone']) && isset($_POST['type'])) {
  $phone = $conn->real_escape_string($_POST['phone']);
  $zone  = $conn->real_escape_string($_POST['zone']);
  $type  = $conn->real_escape_string($_POST['type']);

  $sql = "INSERT INTO registrations (phone, zone, type) VALUES ('$phone', '$zone', '$type')";

  if ($conn->query($sql) === TRUE) {
    echo "success";
  } else {
    echo "error: " . $conn->error;
  }
} else {
  echo "error: missing fields";
}

$conn->close();
?>
