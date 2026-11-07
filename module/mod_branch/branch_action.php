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
	
	$module	= !empty($_GET['module']) ? $_GET['module'] : '';
	$act	= !empty($_GET['act']) ? $_GET['act'] : '';
	
	$aksi="module/mod_branch/branch_action.php";
	
	// data
	if($module=='branch' and $act=='data'){
		
		$table = 'cabang';

		$primaryKey = 'id_cabang';

		$columns = array(
			array( 'db' => 'kode_cabang', 'dt' => 0 ),
			array( 'db' => 'nama_cabang',  'dt' => 1 ),
			array( 'db' => 'alamat2', 'dt'=> 2 ),
			array( 'db' => 'status', 'dt' => 3 ),
			array( 'db' => 'id_cabang', 'dt' => 4 ),
			array(
				  'db'        => 'id_cabang',
				  'dt'        => 4,
				  'formatter' => function( $d, $row ) {
					$aksi="module/mod_branch/branch_action.php";
					$key = "asdf$1011";
					return '<td><center><button class="btn btn-xs btn-warning" onclick="window.location.href = \'?module=branch&act=update&id='.base64_encrypt($d,$key).'\';"><i class="fa fa-pencil"></i></button> <button data-href="'.$aksi.'?module=branch&act=delete&id='.base64_encrypt($d,$key).'" class="btn btn-xs btn-danger" type="button" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash"></i></button></center></td>';
				  }
			)

		);

		
		require( '../../config/ssp.class.php' );

		echo json_encode(
			SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns )
		);

	// Cek Kode
	}elseif($module=='branch' and $act=='checkcode'){
		if($_POST['kode_cabang']){
			$kode = mysqli_real_escape_string($con,$_POST['kode_cabang']);
			$query = mysqli_query($con, "SELECT kode_cabang FROM cabang WHERE kode_cabang='$kode'");
			$result = mysqli_num_rows($query);
			if( $result > 0 ){
				echo 'false';
			} else {
				echo 'true';
			}
		}
	
	// create
	}elseif($module=='branch' AND $act=='create'){
		$kode_cabang = mysqli_real_escape_string($con, $_POST['kode_cabang']);
		$nama_cabang = mysqli_real_escape_string($con, $_POST['nama_cabang']);
		$alamat1 = mysqli_real_escape_string($con, $_POST['alamat1']);
		$alamat2 = mysqli_real_escape_string($con, $_POST['alamat2']);
		$kota = mysqli_real_escape_string($con, $_POST['kota']);
		$kecamatan = mysqli_real_escape_string($con, $_POST['kecamatan']);
		$telp = mysqli_real_escape_string($con, $_POST['telp']);
		$email = mysqli_real_escape_string($con, $_POST['email']);
		
		mysqli_query($con, "insert into cabang (kode_cabang,
													nama_cabang,
													alamat1,
													alamat2,
													id_kota,
													id_kota_baru,
													kecamatan,
													telp,
													email,
													created_date,
													created_by)
											values ('$kode_cabang',
													'$nama_cabang',
													'$alamat1',
													'$alamat2',
													'1',
													'$kota',
													'$kecamatan',
													'$telp',
													'$email',
													'$tgl_jam',
													'$_SESSION[id_user]')");
		
		header('location:../../main.php?module='.$module);
	
	// update
	}elseif($module=='branch' AND $act=='update'){
		$id_cabang = mysqli_real_escape_string($con, $_POST['id']);
		$kode_cabang = mysqli_real_escape_string($con, $_POST['kode_cabangx']);
		$nama_cabang = mysqli_real_escape_string($con, $_POST['nama_cabang']);
		$alamat1 = mysqli_real_escape_string($con, $_POST['alamat1']);
		$alamat2 = mysqli_real_escape_string($con, $_POST['alamat2']);
		$kota = mysqli_real_escape_string($con, $_POST['kota']);
		$kecamatan = mysqli_real_escape_string($con, $_POST['kecamatan']);
		$telp = mysqli_real_escape_string($con, $_POST['telp']);
		$email = mysqli_real_escape_string($con, $_POST['email']);
		$status = mysqli_real_escape_string($con, $_POST['status']);
		
		mysqli_query($con, "update cabang set nama_cabang='$nama_cabang',
											alamat1='$alamat1',
											alamat2='$alamat2',
											id_kota='$kota',
											kecamatan='$kecamatan',
											telp='$telp',
											email='$email',
											updated_last='$tgl_jam',
											status='$status',
											updated_by='$_SESSION[id_user]'
							where id_cabang='$_POST[id]'");
		
		header('location:../../main.php?module='.$module);
		
	}elseif($module=='branch' and $act=='delete'){
		$key = "asdf$1011";
		$encrypt = "$_GET[id]";
		$id = base64_decrypt($encrypt,$key);
		mysqli_query($con, "delete from cabang where id_cabang='$id'");
		header('location:../../main.php?module='.$module);
	}
}
?>