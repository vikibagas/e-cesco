<?php
session_start();

/* config */
define('BASE_LOGIN_URL', 'https://portal.gps.id/backend/seen/public/login');
define('CREATE_SHARE_URL', 'https://portal.gps.id/backend/seen/public/share_location/create_share');
$username = 'cesco';
$password = 'cesco123';

/* helper: base64 url decode */
function base64url_decode($data) {
    $remainder = strlen($data) % 4;
    if ($remainder) {
        $padlen = 4 - $remainder;
        $data .= str_repeat('=', $padlen);
    }
    $data = strtr($data, '-_', '+/');
    return base64_decode($data);
}

/* helper: check JWT exp (returns true jika token valid sekarang) */
function jwt_not_expired($token) {
    if (! $token) return false;
    $parts = explode('.', $token);
    if (count($parts) < 2) return false;
    $payload = base64url_decode($parts[1]);
    if ($payload === false) return false;
    $data = json_decode($payload, true);
    if (!is_array($data) || !isset($data['exp'])) return false;
    return (time() < intval($data['exp']));
}

/* function login -> returns token string or false */
function do_login($username, $password) {
    $payload = json_encode(['username' => $username, 'password' => $password]);

    $ch = curl_init(BASE_LOGIN_URL);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $payload,
        CURLOPT_HTTPHEADER => [
            "Accept: application/json",
            "Content-Type: application/json"
        ],
    ]);
    $resp = curl_exec($ch);
    if ($resp === false) {
        curl_close($ch);
        return false;
    }
    $http = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($http < 200 || $http >= 300) return false;

    $decoded = json_decode($resp, true);
    // struktur: message -> data -> token
    if (isset($decoded['message']['data']['token'])) {
        return $decoded['message']['data']['token'];
    }
    return false;
}

/* function create share (mengembalikan associative array hasil) */
function create_share_request($token, $imei) {
    $payload = json_encode(['imei' => $imei, 'expiration' => 10]);

    $ch = curl_init(CREATE_SHARE_URL);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: " . $token,
        "Accept: application/json",
        "Content-Type: application/json",
    ]);

    $resp = curl_exec($ch);
    if ($resp === false) {
        $err = curl_error($ch);
        curl_close($ch);
        return ['ok' => false, 'http' => 0, 'error' => $err];
    }
    $http = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    $decoded = json_decode($resp, true);
    return ['ok' => true, 'http' => $http, 'body' => $decoded, 'raw' => $resp];
}

/* MAIN FLOW: pastikan ada token valid di session */
$imei = isset($_POST['imei']) ? $_POST['imei'] : null;
if (!$imei) {
    echo json_encode(['success' => 0, 'message' => 'IMEI tidak diberikan']);
    exit;
}

/* pastikan token ada dan belum expired */
$token = isset($_SESSION['gps_token']) ? $_SESSION['gps_token'] : null;

if (! $token || ! jwt_not_expired($token)) {
    // login dan simpan token
    $newToken = do_login($username, $password);
    if ($newToken === false) {
        echo json_encode(['success' => 0, 'message' => 'Login gagal atau API tidak respons']);
        exit;
    }
    $_SESSION['gps_token'] = $newToken;
    $token = $newToken;
}

/* lakukan create_share */
$result = create_share_request($token, $imei);

/* jika server menolak (401) — coba login ulang sekali dan ulangi request */
if ($result['ok'] && $result['http'] == 401) {
    // hapus token dan login lagi
    unset($_SESSION['gps_token']);
    $newToken = do_login($username, $password);
    if ($newToken === false) {
        echo json_encode(['success' => 0, 'message' => 'Login ulang gagal']);
        exit;
    }
    $_SESSION['gps_token'] = $newToken;
    $token = $newToken;
    $result = create_share_request($token, $imei);
}

/* tangani hasil */
if (! $result['ok']) {
    echo json_encode(['success' => 0, 'message' => 'cURL error: ' . $result['error']]);
    exit;
}

$http = $result['http'];
$body = $result['body'];

if ($http >= 200 && $http < 300 && isset($body['data']['url'])) {
    $url = $body['data']['url'];
    echo json_encode(['success' => 1, 'message' => 'URL BERHASIL DI DAPAT', 'url' => $url], JSON_UNESCAPED_SLASHES);
    exit;
} else {
    // bisa mengembalikan detail error dari API
    echo json_encode(['success' => 0, 'message' => 'Gagal mendapatkan URL', 'http' => $http, 'body' => $result['raw']]);
    exit;
}