<?php
$apikey = "4763ed687ab50ef"; 
$sendername = "AbisoBrgy";

$conn = new mysqli("localhost", "root", "", "Abisobrgy");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT phone FROM registrations"; 
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $number = $row['phone'];
        $message = "Good Day!! Congrats You're now registered on AbisoBrgy..";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://semaphore.co/api/v4/messages');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
            'apikey' => $apikey,
            'number' => $number,
            'message' => $message,
            'sendername' => $sendername
        ]));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $output = curl_exec($ch);
        curl_close($ch);

        echo "Sent to $number: $output\n";
    }
} else {
    echo "No registered residents found.";
}

$conn->close();
?>
