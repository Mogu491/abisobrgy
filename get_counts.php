<?php
$conn = new mysqli("localhost", "root", "", "abisobrgy");
if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }

$residents = $conn->query("SELECT COUNT(*) AS total FROM registrations WHERE type='resident'")->fetch_assoc()['total'];
$officials = $conn->query("SELECT COUNT(*) AS total FROM registrations WHERE type='official'")->fetch_assoc()['total'];

echo json_encode(["residents" => $residents, "officials" => $officials]);
$conn->close();
?>
