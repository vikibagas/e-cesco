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
	
	$aksi="module/mod_tarif-intercity/tarif-intercity_action.php";
	
	// List paket
	if($module=='tarif-intercity' and $act=='read'){
		$table = 
<<<EOT
	(
		SELECT
			b.nama_kota, 
			x_service(a.id_pelayanan) as service, 
			CASE WHEN a.komoditi='1' THEN 'Paket' ELSE 'Dokumen' END AS komoditi, 
			REPLACE(REPLACE(REPLACE(FORMAT(a.harga_awal, 0), ".", "@"), ",", "."), "@", ",") AS harga_awal, 
			a.berat_minimal, 
			a.leadtime,
			a.rate_id 
		FROM rate a 
		INNER JOIN kota b ON b.id_kota=a.id_kota 
		WHERE a.id_cabang='39' AND b.intercity='Y'
	) temp
EOT;
		$primaryKey = 'rate_id';
		$columns = array(
			array( 'db' => 'nama_kota', 'dt' => 0 ),
			array( 'db' => 'service', 'dt' => 1 ),
			array( 'db' => 'komoditi', 'dt' => 2 ),
			array( 'db' => 'harga_awal', 'dt' => 3 ),
			array( 'db' => 'berat_minimal', 'dt' => 4 ),
			array( 'db' => 'leadtime', 'dt' => 5, 'formatter' => function($d,$row) {
				return	'<td><center>'.$d.' Days</center></td>';
			}),
			array( 'db' => 'rate_id', 'dt' => 6, 'formatter' => function($d,$row) {
				$aksi	= "module/mod_tarif-regular/tarif-regular_action.php";
				$key	= "asdf$1011";
				return '<td><center>
				<div class="btn-group">
					<button class="btn btn-xs btn-default" onclick="window.location.href = \'?module=tarif-intercity&act=update&id='.base64_encrypt($d,$key).'\';"><i class="fa fa-pencil text-yellow"></i></button>
					<button class="btn btn-xs btn-default"><i class="fa fa-trash text-red"></i></button>
				</div></center></td>';
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
	}elseif($module=='tarif-intercity' AND $act=='update'){
		$rate_id 			= $mysqli->real_escape_string($_POST['rate_id']);
		$id_cabang 			= $mysqli->real_escape_string($_POST['id_cabang']);
		$id_kota 			= $mysqli->real_escape_string($_POST['id_kota']);
		$komoditi 			= $mysqli->real_escape_string($_POST['komoditi']);
		$harga_awal 		= $mysqli->real_escape_string($_POST['harga_awal']);
		$berat_minimal		= $mysqli->real_escape_string($_POST['berat_minimal']);
		
		$result				= $mysqli->query("UPDATE rate SET id_cabang='$id_cabang', id_kota='$id_kota', komoditi='$komoditi', harga_awal='$harga_awal', berat_minimal='$berat_minimal' WHERE rate_id='$rate_id'");
		
		if($result)
		{
			$_SESSION['pesan'] = 'success';
		}
		else{
			$_SESSION['pesan'] = 'failed';
		}
		
		header('location:../../main.php?module='.$module);		
	
	}

}
?>