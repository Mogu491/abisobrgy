<?php
header('Content-Type: application/json');
$conn = new mysqli("localhost", "root", "", "abisobrgy");
if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}

$sql = "SELECT id, title, sendTo, zone, message, time 
        FROM announcements 
        ORDER BY time DESC";
$result = $conn->query($sql);

$announcements = [];
while ($row = $result->fetch_assoc()) {
    $announcements[] = $row;
}

echo json_encode($announcements);
?>
