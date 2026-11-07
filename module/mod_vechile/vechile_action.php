<?php

$username = "cesco";
$password = "cesco123";

$url = "https://portal.gps.id/backend/seen/public/login";

$data = json_encode([
    "username" => $username,
    "password" => $password
]);

$ch = curl_init($url);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => $data,
    CURLOPT_HTTPHEADER => [
        "Accept: application/json",
        "Content-Type: application/json"
    ]
]);

$response = curl_exec($ch);
curl_close($ch);

$decoded = json_decode($response, true);

// Ambil token kalau ada
if (isset($decoded['message']['data']['token'])) {
    $token = $decoded['message']['data']['token'];
    //echo "Token: " . $token;
} else {
	$succes = array("success"=>0,"message"=> "TOKEN GAGAL DI DAPAT");
	echo "Response:\n$response\n";
	echo json_encode($succes);
	exit();
    //echo "Token tidak ditemukan!";
}


if ($token) {
  //  echo "\nToken: $token\n";
}

//echo '<br><br>'.$token;


?>

<?php
// misal token sudah kamu dapat saat login
//$token = "YOUR_TOKEN";

// ambil imei dari POST form
$imei_vechile = isset($_POST['imei']) ? $_POST['imei'] : null;

$imei = $imei_vechile;

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

$decoded = json_decode($response, true);

if (isset($decoded['data']['url'])) {
    $url = $decoded['data']['url'];
    //echo "URL: " . $url;
    $succes = array("success"=>1,"message"=> "URL BERHASIL DI DAPAT","url" => $url);
	echo json_encode($succes, JSON_UNESCAPED_SLASHES);

} else {
    $succes = array("success"=>0,"message"=> "url tidak ditemukan");
	echo json_encode($succes);
}

/*echo '<iframe 
  src="'.$url.'" 
  width="100%" 
  height="600" 
  style="border:0;" 
  allowfullscreen>
</iframe>'*/

//echo $response;

?>