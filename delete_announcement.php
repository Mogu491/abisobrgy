<?php
$conn = new mysqli("localhost", "root", "", "abisobrgy");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!empty($_POST['id'])) {
    $id = intval($_POST['id']);
    $sql = "DELETE FROM announcements WHERE id=$id";
    if ($conn->query($sql)) {
        echo "success";
    } else {
        echo "error: " . $conn->error;
    }
} else {
    echo "error: missing id";
}
?>
