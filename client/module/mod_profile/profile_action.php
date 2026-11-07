<?php
if(!isset($_SESSION)){
    session_start();
}

if (empty($_SESSION['usernamex']) AND empty($_SESSION['passwordx'])){
	header('location:../application/index.php');
}
else{
	include "../../../config/connection.php";
	include "../../../config/thumb.php";
	
	$module	= !empty($_GET['module']) ? $_GET['module'] : '';
	$act	= !empty($_GET['act']) ? $_GET['act'] : '';
	
	$aksi="module/mod_branch/branch_action.php";
	
	// update
	if($module=='profile' AND $act=='update'){
		$lokasi_file    = $_FILES['fupload']['tmp_name'];
		$tipe_file      = $_FILES['fupload']['type'];
		$nama_file      = $_FILES['fupload']['name'];
		$acak           = rand(1,99);
		$nama_file_unik = $acak.$nama_file;
  
		$fullname = mysqli_real_escape_string($con, $_POST['namalengkap']);
		$email = mysqli_real_escape_string($con, $_POST['email']);
		$password = mysqli_real_escape_string($con, md5($_POST['password']));
		
		if(!empty($lokasi_file)){
			UploadPhoto($nama_file_unik);
			mysqli_query($con, "update user_client set fullname='$fullname',
													email='$email',
													password='$password',
													foto='$nama_file_unik'
								where id_user='$_POST[id]'");
		}else{
			mysqli_query($con, "update user_client set fullname='$fullname',
													email='$email',
													password='$password'
								where id_user='$_POST[id]'");
		}
		
		header('location:../../main.php?module='.$module);
		
	}

}
?>