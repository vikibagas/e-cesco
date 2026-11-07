<?php
// misal token sudah kamu dapat saat login
$token = "YOUR_TOKEN";

// ambil imei dari POST form
$imei = isset($_POST['imei']) ? $_POST['imei'] : null;

$url = "https://portal.gps.id/backend/seen/public/share_location/create_share";

// payload JSON
$data = json_encode([
    "imei" => $imei,
    "expiration" => 10
]);

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

// header request
$headers = [
    "Authorization: " . $token,
    "Accept: application/json",
    "Content-Type: application/json",
];
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$response = curl_exec($ch);
if ($response === false) {
    die("cURL Error: " . curl_error($ch));
}
curl_close($ch);

// tampilkan response
echo $response;