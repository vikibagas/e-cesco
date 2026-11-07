<?php
if(!isset($_SESSION)){
    session_start();
}

if (empty($_SESSION['username']) AND empty($_SESSION['password'])){
	header('location:../application/index.php');
}
else{
	include "../../config/connection.php";
	include "../../config/library.php";
	include "../../config/encrypt.php";
	include "../../config/db_connect.php";

	$key = "asdf$1011";
	$encrypt = $_SESSION['id_user'];
	$id_user = base64_decrypt($encrypt,$key);
	
	
	
	$module	= !empty($_GET['module']) ? $_GET['module'] : '';
	$act	= !empty($_GET['act']) ? $_GET['act'] : '';
	
	$aksi="module/mod_warehouse-barang/barang_action.php";
	
	
	

	if ($module == 'tracking' && $act == 'tampil_history') {
	    $id_user = $_POST['id_user'];

	    $sql = mysqli_query($con, "
	        SELECT 
	            MIN(id) AS id,
	            DATE_FORMAT(MIN(timestamp), '%Y-%m-%d %H:%i:%s') AS timestamp
	        FROM location_history
	        WHERE user_id = '$id_user'
	        GROUP BY 
	            DATE_FORMAT(timestamp, '%Y-%m-%d %H'), 
	            FLOOR(MINUTE(timestamp) / 30)  -- ✅ tiap 30 menit
	        ORDER BY timestamp DESC
	        LIMIT 100
	    ");

	    $data = [];
	    while ($row = mysqli_fetch_assoc($sql)) {
	        $data[] = $row;
	    }

	    echo json_encode([
	        'success' => count($data) > 0,
	        'data' => $data
	    ]);
	}

	elseif ($module == 'tracking' && $act == 'view_tracking') {
	    $id = $_POST['id_location_history'];

	    $sql = mysqli_query($con, "
	        SELECT lh.*, u.namalengkap as nama
	        FROM location_history lh
	        LEFT JOIN user u ON lh.user_id = u.id_user
	        WHERE lh.id = '$id'
	        LIMIT 1
	    ");
	    $data = mysqli_fetch_assoc($sql);

	    echo json_encode([
	        'success' => $data ? true : false,
	        'data' => $data
	    ]);
	}

	
}
?>