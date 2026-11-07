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
	
	$aksi="module/mod_warehouse-rak/rak_action.php";
	
	// data
	if($module=='rak' and $act=='data'){
		
		$table = 
<<<EOT
	(
	SELECT
		id,nama_rak,posisi_rak,x_namacabang(id_gudang) as nama_cabang from warehouse_rak
	) temp
EOT;

		$primaryKey = 'id';

		$columns = array(
			array( 'db' => 'nama_rak', 'dt' => 0 ),
			array( 'db' => 'posisi_rak',  'dt' => 1 ),
			array( 'db' => 'nama_cabang',  'dt' => 2 ),
			array(
				  'db'        => 'id',
				  'dt'        => 3,
				  'formatter' => function( $d, $row ) {
					$aksi="module/mod_warehouse-rak/rak_action.php";
					$key = "asdf$1011";
					return '<td><center><button class="btn btn-xs btn-warning" ><i class="fa fa-pencil"></i></button> 

					';
					//return '<td><center><button class="btn btn-xs btn-warning" onclick="window.location.href = \'?module=rak&act=update&id='.base64_encrypt($d,$key).'\';"><i class="fa fa-pencil"></i></button> 
				  }
			)

		);

		
		require( '../../config/ssp.class.php' );

		echo json_encode(
			SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns )
		);

	// Cek Kode
	}elseif($module=='rak' and $act=='checkcode'){
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
	}elseif($module=='rak' AND $act=='create'){
		
		$nama = mysqli_real_escape_string($con, $_POST['nama']);
		$posisi = mysqli_real_escape_string($con, $_POST['posisi']);
		$gudang = mysqli_real_escape_string($con, $_POST['gudang']);
		
		/*echo "insert into warehouse_rak (nama_rak,posisi_rak,created_by,gudang)
											values (
													'$nama',
													'$posisi_rak','".$id_user."')";*/


		mysqli_query($con, "insert into warehouse_rak (nama_rak,posisi_rak,created_by,id_gudang)
											values (
													'$nama',
													'$posisi','".$id_user."','$gudang')");
		
		header('location:../../main.php?module='.$module);
	
	// update
	}elseif($module=='vendor' AND $act=='update'){
		
		$active = mysqli_real_escape_string($con, $_POST['active']);
		
		mysqli_query($con, "update manifest_vendor set 

											active='$active'
											
							where id='$_POST[id]'");
		
		header('location:../../main.php?module='.$module);
		
	}elseif($module=='vendor' and $act=='delete'){
		$key = "asdf$1011";
		$encrypt = "$_GET[id]";
		$id = base64_decrypt($encrypt,$key);
		mysqli_query($con, "delete from cabang where id_cabang='$id'");
		header('location:../../main.php?module='.$module);
	}
}
?>