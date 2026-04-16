<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$conn = new mysqli("localhost", "root", "", "abisobrgy");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$api_secret = "sk_b4230f1e-81b3-4878-9978-3da23be70cb8";
$url = "https://unismsapi.com/api/sms";
$auth = base64_encode($api_secret . ":");

if (!empty($_POST['title']) && !empty($_POST['sendTo']) && !empty($_POST['message'])) {
    $title    = trim($conn->real_escape_string($_POST['title']));
    $audience = trim($conn->real_escape_string($_POST['sendTo']));
    $message  = trim($conn->real_escape_string($_POST['message']));
    $zone     = !empty($_POST['zone']) ? $conn->real_escape_string($_POST['zone']) : ''; // FIXED
    $time     = date("Y-m-d H:i:s");

    if (!$conn->query("INSERT INTO announcements (title, sendTo, zone, message, time)
                       VALUES ('$title', '$audience', '$zone', '$message', '$time')")) {
        echo "Announcement insert error: " . $conn->error;
        exit;
    }

    if ($audience === 'residents') {
        if (!empty($zone)) {
            $sqlRecipients = "SELECT phone FROM registrations WHERE type='resident' AND zone='$zone'";
        } else {
            $sqlRecipients = "SELECT phone FROM registrations WHERE type='resident'";
        }
    } elseif ($audience === 'officials') {
        $sqlRecipients = "SELECT phone FROM registrations WHERE type='official'";
    } else {
        $sqlRecipients = "SELECT phone FROM registrations";
    }

    $result = $conn->query($sqlRecipients);
    if (!$result) {
        echo "Recipients query error: " . $conn->error;
        exit;
    }

    if ($result->num_rows === 0) {
        echo "No recipients found.";
        exit;
    }

    while ($row = $result->fetch_assoc()) {
        $number = $row['phone'];
        if (substr($number, 0, 1) === '0') {
            $number = '+63' . substr($number, 1);
        }

        $data = [
            "recipient" => $number,
            "content"   =>  $title . " : " . $message,
            
            
        ];
        $payload = json_encode($data);

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json",
                "Authorization: Basic " . $auth
            ],
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_TIMEOUT => 20,
        ]);

        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            $response = "Curl error: " . curl_error($ch);
        }
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $logSql = "INSERT INTO announcement_logs (title, audience, zone, number, response, created_at)
                   VALUES ('$title', '$audience', '$zone', '$number', '".$conn->real_escape_string($response)."', NOW())";
        if (!$conn->query($logSql)) {
            echo "Log insert error: " . $conn->error . "<br>";
        }
    }

    echo "success";
} else {
    echo "error: missing POST keys";
}
?>
