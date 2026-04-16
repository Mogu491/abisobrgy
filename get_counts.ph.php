<?php
$conn = new mysqli("localhost", "root", "", "abisobrgy");

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$residentCount = 0;
$officialCount = 0;

$sqlResident = "SELECT COUNT(*) AS total FROM registrations WHERE type='resident'";
$sqlOfficial = "SELECT COUNT(*) AS total FROM registrations WHERE type='official'";

if ($result = $conn->query($sqlResident)) {
  $row = $result->fetch_assoc();
  $residentCount = $row['total'];
}

if ($result = $conn->query($sqlOfficial)) {
  $row = $result->fetch_assoc();
  $officialCount = $row['total'];
}

echo json_encode([
  "residents" => $residentCount,
  "officials" => $officialCount
]);

$conn->close();
?>
