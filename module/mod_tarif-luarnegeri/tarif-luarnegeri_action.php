<?php
if (!isset($_SESSION))
{
	session_start();
}

if (empty($_SESSION['username']) AND empty($_SESSION['password']))
{
	header('location:../../application/index.php');
}
else
{
	require_once('../../config/db_connect.php');
	require_once('../../config/library.php');
	require_once('../../config/encrypt.php');
	
	$module	= !empty($_GET['module']) ? $_GET['module'] : '';
	$act	= !empty($_GET['act']) ? $_GET['act'] : '';
	
	$aksi="module/mod_tarif-luarnegeri/tarif-luarnegeri_action.php";
	
	// List paket
	if($module=='tarif-luarnegeri' and $act=='readpaket'){
		$table = 
<<<EOT
	(
		SELECT
			a.berat, 
			MAX(IF(a.zona= 1, REPLACE(REPLACE(REPLACE(FORMAT(a.harga, 0), ".", "@"), ",", "."), "@", ","), NULL)) zona1, 
			MAX(IF(a.zona= 2, REPLACE(REPLACE(REPLACE(FORMAT(a.harga, 0), ".", "@"), ",", "."), "@", ","), NULL)) zona2, 
			MAX(IF(a.zona= 3, REPLACE(REPLACE(REPLACE(FORMAT(a.harga, 0), ".", "@"), ",", "."), "@", ","), NULL)) zona3, 
			MAX(IF(a.zona= 4, REPLACE(REPLACE(REPLACE(FORMAT(a.harga, 0), ".", "@"), ",", "."), "@", ","), NULL)) zona4, 
			MAX(IF(a.zona= 5, REPLACE(REPLACE(REPLACE(FORMAT(a.harga, 0), ".", "@"), ",", "."), "@", ","), NULL)) zona5, 
			MAX(IF(a.zona= 6, REPLACE(REPLACE(REPLACE(FORMAT(a.harga, 0), ".", "@"), ",", "."), "@", ","), NULL)) zona6, 
			MAX(IF(a.zona= 7, REPLACE(REPLACE(REPLACE(FORMAT(a.harga, 0), ".", "@"), ",", "."), "@", ","), NULL)) zona7, 
			MAX(IF(a.zona= 8, REPLACE(REPLACE(REPLACE(FORMAT(a.harga, 0), ".", "@"), ",", "."), "@", ","), NULL)) zona8, 
			MAX(IF(a.zona= 9, REPLACE(REPLACE(REPLACE(FORMAT(a.harga, 0), ".", "@"), ",", "."), "@", ","), NULL)) zona9, 
			a.idtarif
		FROM rate_int a
		WHERE a.tipe=1 GROUP BY a.berat
	) temp
EOT;
		$primaryKey = 'idtarif';
		$columns = array(
			array( 'db' => 'berat', 'dt' => 0 ),
			array( 'db' => 'zona1', 'dt' => 1 ),
			array( 'db' => 'zona2', 'dt' => 2 ),
			array( 'db' => 'zona3', 'dt' => 3 ),
			array( 'db' => 'zona4', 'dt' => 4 ),
			array( 'db' => 'zona5', 'dt' => 5 ),
			array( 'db' => 'zona6', 'dt' => 6 ),
			array( 'db' => 'zona7', 'dt' => 7 ),
			array( 'db' => 'zona8', 'dt' => 8 ),
			array( 'db' => 'zona9', 'dt' => 9 )
		);
		$sql_details = array(
			'user' => 'cesco_user',
			'pass' => 'srnGRkhl3c',
			'db'   => 'cesco_logistics',
			'host' => '127.0.0.1'
		);
		require('../../config/ssp.class.php');
		echo json_encode(
			SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns )
		);
	
	// list dokumen
	} elseif ($module=='tarif-luarnegeri' AND $act=='readdokumen'){
		$table = 
<<<EOT
	(
		SELECT
			a.berat, 
			MAX(IF(a.zona= 1, REPLACE(REPLACE(REPLACE(FORMAT(a.harga, 0), ".", "@"), ",", "."), "@", ","), NULL)) zona1, 
			MAX(IF(a.zona= 2, REPLACE(REPLACE(REPLACE(FORMAT(a.harga, 0), ".", "@"), ",", "."), "@", ","), NULL)) zona2, 
			MAX(IF(a.zona= 3, REPLACE(REPLACE(REPLACE(FORMAT(a.harga, 0), ".", "@"), ",", "."), "@", ","), NULL)) zona3, 
			MAX(IF(a.zona= 4, REPLACE(REPLACE(REPLACE(FORMAT(a.harga, 0), ".", "@"), ",", "."), "@", ","), NULL)) zona4, 
			MAX(IF(a.zona= 5, REPLACE(REPLACE(REPLACE(FORMAT(a.harga, 0), ".", "@"), ",", "."), "@", ","), NULL)) zona5, 
			MAX(IF(a.zona= 6, REPLACE(REPLACE(REPLACE(FORMAT(a.harga, 0), ".", "@"), ",", "."), "@", ","), NULL)) zona6, 
			MAX(IF(a.zona= 7, REPLACE(REPLACE(REPLACE(FORMAT(a.harga, 0), ".", "@"), ",", "."), "@", ","), NULL)) zona7, 
			MAX(IF(a.zona= 8, REPLACE(REPLACE(REPLACE(FORMAT(a.harga, 0), ".", "@"), ",", "."), "@", ","), NULL)) zona8, 
			MAX(IF(a.zona= 9, REPLACE(REPLACE(REPLACE(FORMAT(a.harga, 0), ".", "@"), ",", "."), "@", ","), NULL)) zona9, 
			a.idtarif
		FROM rate_int a
		WHERE a.tipe=2 GROUP BY a.berat
	) temp
EOT;
		$primaryKey = 'idtarif';
		$columns = array(
			array( 'db' => 'berat', 'dt' => 0 ),
			array( 'db' => 'zona1', 'dt' => 1 ),
			array( 'db' => 'zona2', 'dt' => 2 ),
			array( 'db' => 'zona3', 'dt' => 3 ),
			array( 'db' => 'zona4', 'dt' => 4 ),
			array( 'db' => 'zona5', 'dt' => 5 ),
			array( 'db' => 'zona6', 'dt' => 6 ),
			array( 'db' => 'zona7', 'dt' => 7 ),
			array( 'db' => 'zona8', 'dt' => 8 ),
			array( 'db' => 'zona9', 'dt' => 9 )
		);
		$sql_details = array(
			'user' => 'cesco_user',
			'pass' => 'srnGRkhl3c',
			'db'   => 'cesco_logistics',
			'host' => '127.0.0.1'
		);
		require('../../config/ssp.class.php');
		echo json_encode(
			SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns )
		);
		
	// update user cesco
	}elseif($module=='users' AND $act=='update1'){
		$id_user = mysqli_real_escape_string($con, $_POST['id']);
		$namalengkap = mysqli_real_escape_string($con, $_POST['namalengkap']);
		$email = mysqli_real_escape_string($con, $_POST['email']);
		$id_cabang = mysqli_real_escape_string($con, $_POST['id_cabang']);
		$level = mysqli_real_escape_string($con, $_POST['level']);
		$blokir = mysqli_real_escape_string($con, $_POST['blokir']);
		$id_kustomer = mysqli_real_escape_string($con, $_POST['id_kustomer']);
		if(!empty($_POST['area'])){
			$pickup_area = $_POST['area'];
			$area=implode(',',$pickup_area);
		}
		$delivery_customer = mysqli_real_escape_string($con, $_POST['delivery_customer']);
		if(!empty($_POST['delivery_area'])){
			$delivery_area = $_POST['delivery_area'];
			$delivery=implode(',',$delivery_area);
		}
		
		if(!empty($blokir)){
			mysqli_query($con, "update user set namalengkap='$namalengkap',
												email='$email',
												id_cabang='$id_cabang',
												level='$level',
												blokir='$blokir',
												id_kustomer='$id_kustomer',
												area='$area',
												delivery_customer='$delivery_customer',
												delivery_area='$delivery'
								where id_user='$id_user'");
		}else{
			mysqli_query($con, "update user set namalengkap='$namalengkap',
												email='$email',
												id_cabang='$id_cabang',
												level='$level',
												id_kustomer='$id_kustomer',
												area='$area',
												delivery_customer='$delivery_customer',
												delivery_area='$delivery'
								where id_user='$id_user'");
		}
		
		header('location:../../main.php?module='.$module);
		
	}elseif($module=='users' and $act=='blockeduser'){
		$key = "asdf$1011";
		$encrypt = "$_GET[id]";
		$id = base64_decrypt($encrypt,$key);
		mysqli_query($con, "update user set blokir='Y' where id_user='$id'");
		header('location:../../main.php?module='.$module);
	
	// list user client
	}elseif($module=='users' and $act=='readuserclient'){
		$table = 
<<<EOT
	(
		SELECT
			a.fullname, 
			a.username, 
			b.nama_kustomer,
			a.blocked,
			a.email,
			a.created_date,
			a.id_user
		FROM user_client a
		LEFT JOIN kustomer b ON b.id_kustomer=a.id_kustomer
	) temp
EOT;
		$primaryKey = 'id_user';
		$columns = array(
			array('db'=>'fullname',
					'dt'=>0,
					'formatter'=>function($d,$row){
						return'<td>'.strtoupper($d).'</td>';
					}
			),
			array('db'=>'username','dt'=>1),
			array('db'=>'nama_kustomer','dt'=>2),
			array('db'=>'blocked','dt'=>3,
				'formatter'=>function($d,$row){
					if($d=='Y'){
						$status = '<button class="btn btn-block btn-danger btn-xs">Blocked</button>';
					}else{
						$status = '<button class="btn btn-block btn-success btn-xs">Unblocked</button>';
					}
					return '<td><center>'.$status.'</center></td>';
				}
			),
			array('db'=>'email', 
					'dt'=>4,
					'formatter'=>function($d,$row){
						return'<td><a href="mailto:'.$d.'">'.$d.'</a></td>';
					}
			),
			array('db'=>'created_date','dt'=>5),
			array(
				  'db'=>'id_user',
				  'dt'=>6,
				  'formatter'=>function( $d, $row ) {
					$aksi="module/mod_users/users_action.php";
					$key = "asdf$1011";
					return '<td><center><button class="btn btn-xs btn-warning" onclick="window.location.href = \'?module=users&act=update2&id='.base64_encrypt($d,$key).'\';"><i class="fa fa-pencil"></i></button> <button data-href="'.$aksi.'?module=users&act=blockeduserclient&id='.base64_encrypt($d,$key).'" class="btn btn-xs btn-danger" type="button" data-toggle="modal" data-target="#confirm-blocked"><i class="fa fa-close"></i></button></center></td>';
				  }
			)

		);
		$sql_details = array(
			'user' => 'cesco_user',
			'pass' => 'srnGRkhl3c',
			'db'   => 'cesco_logistics',
			'host' => '127.0.0.1'
		);
		require( '../../config/ssp.class.php' );
		echo json_encode(
			SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns )
		);
	
	}elseif($module=='users' AND $act=='update2'){
		$id_user = mysqli_real_escape_string($con, $_POST['id']);
		$blocked = mysqli_real_escape_string($con, $_POST['blocked']);
		$id_kustomer = mysqli_real_escape_string($con, $_POST['id_kustomer']);
		
		mysqli_query($con, "update user_client set blocked='$blocked',
											id_kustomer='$id_kustomer'
							where id_user='$id_user'");
		
		header('location:../../main.php?module='.$module);
		
	}elseif($module=='users' and $act=='blockeduserclient'){
		$key = "asdf$1011";
		$encrypt = "$_GET[id]";
		$id = base64_decrypt($encrypt,$key);
		mysqli_query($con, "update user_client set blocked='Y' where id_user='$id'");
		header('location:../../main.php?module='.$module);
	}
}
?>