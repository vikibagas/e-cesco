<?php
// awb_label_update.php
// Dipanggil via AJAX dari halaman label saat print (Ctrl+P atau tombol Print)

include ('../config/connection.php'); // mendefinisikan $con (bukan $mysqli)

header('Content-Type: application/json');

$id_pengiriman = isset($_POST['id_pengiriman']) ? (int) $_POST['id_pengiriman'] : 0;

if ($id_pengiriman <= 0) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'id_pengiriman tidak valid']);
    exit;
}

// Pakai prepared statement supaya aman dari SQL injection
$stmt = $con->prepare("UPDATE pengiriman SET print_label = IFNULL(print_label, 0) + 1 WHERE id_pengiriman = ?");

if (!$stmt) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Prepare gagal: ' . $con->error]);
    exit;
}

$stmt->bind_param('i', $id_pengiriman);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'id_pengiriman' => $id_pengiriman]);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $stmt->error]);
}

$stmt->close();