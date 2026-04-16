<?php
$conn = new mysqli("localhost", "root", "", "abisobrgy");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "DELETE FROM announcements";
if ($conn->query($sql)) {
    echo "success";
} else {
    echo "error: " . $conn->error;
}
?>
