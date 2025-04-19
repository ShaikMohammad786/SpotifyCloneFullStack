

<?php

$client_id = "9431fe8f1258468b990704001e2150e8";
$client_secret = "1d1b3a2ec3154a128d064cb3e35eb1bc";

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, "https://accounts.spotify.com/api/token");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Basic " . base64_encode("$client_id:$client_secret"),
    "Content-Type: application/x-www-form-urlencoded"
]);

$response = curl_exec($ch);
curl_close($ch);

// Return raw JSON to the browser
header('Content-Type: application/json');
echo $response;
?>
