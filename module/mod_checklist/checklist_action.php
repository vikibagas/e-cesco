<?php
if(!isset($_SESSION)){
    session_start();
}

if (empty($_SESSION['username']) AND empty($_SESSION['password'])){
	header('location:../application/login.php');
}
else{
	include "../../config/connection.php";
	
	$module	= !empty($_GET['module']) ? $_GET['module'] : '';
	$act	= !empty($_GET['act']) ? $_GET['act'] : '';
	
	// UPDATE
	if($module=='checklist' AND $act=='update'){
		
		$id = mysqli_real_escape_string($con, $_POST['id']);
		$berat_aktual = mysqli_real_escape_string($con, $_POST['berat_aktual']);
		$ukuran_barang = '';
		if(isset($_POST['length'])){
			$ukuran_barang .= mysqli_real_escape_string($con, $_POST['length']);
		}
		if(isset($_POST['width'])){
			$ukuran_barang .= 'x' . mysqli_real_escape_string($con, $_POST['width']);
		}
		if(isset($_POST['height'])){
			$ukuran_barang .= 'x' . mysqli_real_escape_string($con, $_POST['height']);
		}
		$collie = mysqli_real_escape_string($con, $_POST['collie']);
		
		mysqli_query ($con, "update pengiriman set berat_aktual='$berat_aktual',
													ukuran_barang='$ukuran_barang',
													collie='$collie',
													status='3',
													pickup='Y',
													manifest='Y'
								where id_pengiriman='$id'");
		
		header('location:../../main.php?module='.$module);
	}
}
?>