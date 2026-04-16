<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$conn = new mysqli("localhost", "root", "", "abisobrgy");
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

if(isset($_POST['title']) && isset($_POST['sendTo']) && isset($_POST['message'])) {
  $title   = $conn->real_escape_string($_POST['title']);
  $sendTo  = $conn->real_escape_string($_POST['sendTo']);
  $message = $conn->real_escape_string($_POST['message']);
  $time    = date("Y-m-d H:i:s");

  $sql = "INSERT INTO announcements (title, sendTo, message, time) 
          VALUES ('$title', '$sendTo', '$message', '$time')";

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
