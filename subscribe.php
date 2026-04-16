<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$serviceAccount = json_decode(file_get_contents("abisobrgy-service-account.json"), true);

function getAccessToken($serviceAccount) {
    $header = base64_encode(json_encode(["alg" => "RS256", "typ" => "JWT"]));
    $now = time();
    $claims = [
        "iss" => $serviceAccount["client_email"],
        "scope" => "https://www.googleapis.com/auth/firebase.messaging",
        "aud" => $serviceAccount["token_uri"],
        "iat" => $now,
        "exp" => $now + 3600
    ];
    $payload = base64_encode(json_encode($claims));

    $signature = '';
    openssl_sign("$header.$payload", $signature, $serviceAccount["private_key"], "sha256");
    $jwt = "$header.$payload." . base64_encode($signature);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $serviceAccount["token_uri"]);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
        "grant_type" => "urn:ietf:params:oauth:grant-type:jwt-bearer",
        "assertion" => $jwt
    ]));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = json_decode(curl_exec($ch), true);
    curl_close($ch);

    return $response["access_token"] ?? null;
}

if (!empty($_POST['token']) && !empty($_POST['topic'])) {
    $token = $_POST['token'];
    $topic = $_POST['topic'];

    $accessToken = getAccessToken($serviceAccount);
    if ($accessToken) {
        $url = "https://iid.googleapis.com/iid/v1/$token/rel/topics/$topic";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer $accessToken"
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);

        echo $result;
    } else {
        echo "Error: No access token.";
    }
} else {
    echo "Error: Missing token or topic.";
}
?>
