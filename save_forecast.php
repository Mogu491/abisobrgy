<?php
$conn = new mysqli("localhost", "root", "", "abisobrgy");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$csvFile = fopen("population.csv", "r");
fgetcsv($csvFile);

$years = [];
$population = [];
while(($row = fgetcsv($csvFile, 1000, ",")) !== FALSE) {
    $years[] = (int)$row[0];
    $population[] = (int)$row[1];
}
fclose($csvFile);

function linearRegression($x, $y) {
    $n = count($x);
    $sumX = array_sum($x);
    $sumY = array_sum($y);
    $sumXY = 0; $sumXX = 0;
    for ($i=0; $i<$n; $i++) {
        $sumXY += $x[$i]*$y[$i];
        $sumXX += $x[$i]*$x[$i];
    }
    $slope = ($n*$sumXY - $sumX*$sumY)/($n*$sumXX - $sumX*$sumX);
    $intercept = ($sumY - $slope*$sumX)/$n;
    return [$slope, $intercept];
}

list($slope, $intercept) = linearRegression($years, $population);
$predicted = [];
foreach ($years as $year) {
    $predicted[] = round($slope*$year + $intercept);
}

$conn->query("TRUNCATE TABLE population_forecast");

$stmt = $conn->prepare("INSERT INTO population_forecast (year, actual_population, predicted_population, growth_rate) VALUES (?,?,?,?)");
for ($i=0; $i<count($years); $i++) {
    $growthRate = $i==0 ? 0 : (($predicted[$i]-$predicted[$i-1])/$predicted[$i-1])*100;
    $stmt->bind_param("iiid", $years[$i], $population[$i], $predicted[$i], $growthRate);
    $stmt->execute();
}

echo "Forecast saved from CSV!";
?>
