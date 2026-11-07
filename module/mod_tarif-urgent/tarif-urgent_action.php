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
	
	$aksi="module/mod_tarif-urgent/tarif-urgent_action.php";
	
	// List paket
	if($module=='tarif-urgent' and $act=='readtop'){
		$table = 
<<<EOT
	(
		SELECT
			b.nama_kota, 
			CASE WHEN a.komoditi='1' THEN 'Paket' ELSE 'Dokumen' END AS komoditi, 
			a.berat_awal, 
			REPLACE(REPLACE(REPLACE(FORMAT(a.harga_awal, 0), ".", "@"), ",", "."), "@", ",") AS harga_awal, 
			REPLACE(REPLACE(REPLACE(FORMAT(a.harga_berikutnya, 0), ".", "@"), ",", "."), "@", ",") AS harga_berikutnya, 
			a.berat_minimal, 
			a.rate_id 
		FROM rate a 
		INNER JOIN kota b ON b.id_kota=a.id_kota 
		WHERE id_pelayanan='1' AND b.intercity!='Y'
	) temp
EOT;
		$primaryKey = 'rate_id';
		$columns = array(
			array( 'db' => 'nama_kota', 'dt' => 0 ),
			array( 'db' => 'komoditi', 'dt' => 1 ),
			array( 'db' => 'berat_awal', 'dt' => 2 ),
			array( 'db' => 'harga_awal', 'dt' => 3 ),
			array( 'db' => 'harga_berikutnya', 'dt' => 4 ),
			array( 'db' => 'berat_minimal', 'dt' => 5 ),
			array( 'db' => 'rate_id', 'dt' => 6, 'formatter' => function( $d, $row ) {
				$aksi	= "module/mod_tarif-urgent/tarif-urgent_action.php";
				$key	= "asdf$1011";
				return '<td><center>
				<button class="btn btn-xs btn-default" onclick="window.location.href = \'?module=tarif-urgent&act=update&id='.base64_encrypt($d,$key).'\';"><i class="fa fa-pencil text-yellow"></i> UPDATE</button></center></td>';
			})
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
	
	// list urgent
	} elseif ($module=='tarif-urgent' AND $act=='readurgent'){
		$table = 
<<<EOT
	(
		SELECT
			b.nama_kota, 
			CASE WHEN a.komoditi='1' THEN 'Paket' ELSE 'Dokumen' END AS komoditi, 
			a.berat_awal, 
			REPLACE(REPLACE(REPLACE(FORMAT(a.harga_awal, 0), ".", "@"), ",", "."), "@", ",") AS harga_awal, 
			REPLACE(REPLACE(REPLACE(FORMAT(a.harga_berikutnya, 0), ".", "@"), ",", "."), "@", ",") AS harga_berikutnya, 
			a.berat_minimal, 
			a.rate_id 
		FROM rate a 
		INNER JOIN kota b ON b.id_kota=a.id_kota 
		WHERE id_pelayanan='2' AND b.intercity!='Y' 
	) temp
EOT;
		$primaryKey = 'rate_id';
		$columns = array(
			array( 'db' => 'nama_kota', 'dt' => 0 ),
			array( 'db' => 'komoditi', 'dt' => 1 ),
			array( 'db' => 'berat_awal', 'dt' => 2 ),
			array( 'db' => 'harga_awal', 'dt' => 3 ),
			array( 'db' => 'harga_berikutnya', 'dt' => 4 ),
			array( 'db' => 'berat_minimal', 'dt' => 5 ),
			array( 'db' => 'rate_id', 'dt' => 6, 'formatter' => function( $d, $row ) {
				$aksi	= "module/mod_tarif-urgent/tarif-urgent_action.php";
				$key	= "asdf$1011";
				return '<td><center>
				<button class="btn btn-xs btn-default" onclick="window.location.href = \'?module=tarif-urgent&act=update&id='.base64_encrypt($d,$key).'\';"><i class="fa fa-pencil text-yellow"></i> UPDATE</button></center></td>';
			})
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
		
	// update
	}elseif($module=='tarif-urgent' AND $act=='update'){
		$rate_id 			= $mysqli->real_escape_string($_POST['rate_id']);
		$id_cabang 			= $mysqli->real_escape_string($_POST['id_cabang']);
		$id_kota 			= $mysqli->real_escape_string($_POST['id_kota']);
		$komoditi 			= $mysqli->real_escape_string($_POST['komoditi']);
		$berat_awal 		= $mysqli->real_escape_string($_POST['berat_awal']);
		$harga_awal 		= $mysqli->real_escape_string($_POST['harga_awal']);
		$harga_berikutnya	= $mysqli->real_escape_string($_POST['harga_berikutnya']);
		$berat_minimal		= $mysqli->real_escape_string($_POST['berat_minimal']);
		
		$result				= $mysqli->query("UPDATE rate SET id_cabang='$id_cabang', id_kota='$id_kota', komoditi='$komoditi', berat_awal='$berat_awal', harga_awal='$harga_awal', harga_berikutnya='$harga_berikutnya', berat_minimal='$berat_minimal' WHERE rate_id='$rate_id'");
		
		if($result)
		{
			$_SESSION['pesan'] = 'success';
		}
		else{
			$_SESSION['pesan'] = 'failed';
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
			'user' => 'cescolog_root',
			'pass' => 'asdf$1011',
			'db'   => 'cescolog_courier',
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