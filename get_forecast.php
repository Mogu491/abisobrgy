<?php
header('Content-Type: application/json');
$conn = new mysqli("localhost", "root", "", "abisobrgy");
if ($conn->connect_error) {
    die(json_encode(["error"=>"Connection failed: ".$conn->connect_error]));
}

$result = $conn->query("SELECT year, actual_population, predicted_population, growth_rate FROM population_forecast ORDER BY year ASC");
$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}
echo json_encode($data);
?>
