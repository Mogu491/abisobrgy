<?php
$ch = curl_init();
$parameters = array(
    'apikey' => '4763ed687ab50efeec23d1cb8158d0da',
    'number' => '+639918726085',
    'message' => 'Test message from Semaphore',
);

curl_setopt($ch, CURLOPT_URL, 'https://semaphore.co/api/v4/messages');
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($parameters));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);

if ($response === false) {

    echo "cURL Error: " . curl_error($ch);
} else {

    echo "<h3>Raw Response:</h3>";
    echo "<pre>" . htmlspecialchars($response) . "</pre>";

    $data = json_decode($response, true);

    echo "<h3>Decoded Array:</h3>";
    echo "<pre>";
    print_r($data);
    echo "</pre>";

    if (isset($data[0]['status'])) {
        echo "<p><strong>Status:</strong> " . $data[0]['status'] . "</p>";
        echo "<p><strong>Message:</strong> " . $data[0]['message'] . "</p>";
        echo "<p><strong>Number:</strong> " . $data[0]['number'] . "</p>";
    } elseif (isset($data['error'])) {
        echo "<p><strong>Error:</strong> " . $data['error'] . "</p>";
    } else {
        echo "<p><strong>Note:</strong> Walang nakuhang data mula sa API.</p>";
    }
}

curl_close($ch);
?>
