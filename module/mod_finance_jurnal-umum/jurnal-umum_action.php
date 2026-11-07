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
	
	$module	= !empty($_GET['module']) ? $_GET['module'] : '';
	$act	= !empty($_GET['act']) ? $_GET['act'] : '';
	
	if($module=='jurnal-umum' AND $act=='rekening'){
		$sql =  "select kode_rekening, nama_rekening from tabel_master where kode_rekening like '%".$_GET['term']."%' order by kode_rekening asc";
		$result = mysqli_query($con1,$sql);
		$json = array();
		while($data = mysqli_fetch_assoc($result)){
			$data['label'] = $data['kode_rekening'];
			$data['value'] = $data['kode_rekening'];
			$json[] = $data;
		}
		header("Content-Type: application/json");
		echo json_encode($json);
	}
}
?>