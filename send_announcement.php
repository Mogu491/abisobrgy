<?php
include 'db_connect.php';

$title = $_POST['title'];
$audience = $_POST['audience'];
$message = $_POST['message'];

if ($audience == 'residents') {
    $sql = "SELECT phone FROM registrations WHERE type='resident'";
} elseif ($audience == 'officials') {
    $sql = "SELECT phone FROM registrations WHERE type='official'";
} else {
    $sql = "SELECT phone FROM registrations";
}

$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
    $number = $row['phone'];

    $ch = curl_init();
    $parameters = array(
        'apikey' => '4763ed687ab50efeec23d1cb8158d0da',
        'number' => $number,
        'message' => $message,
    );

    curl_setopt($ch, CURLOPT_URL, 'https://semaphore.co/api/v4/messages');
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($parameters));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    curl_close($ch);

    $conn->query("INSERT INTO announcement_logs (title, audience, number, response, created_at)
                  VALUES ('$title', '$audience', '$number', '$response', NOW())");
}

echo "Announcement sent successfully!";
?>
