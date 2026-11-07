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
	include "../../config/connection.php";
	require_once('../../config/db_connect.php');
	require_once('../../config/library.php');
	require_once('../../config/encrypt.php');
	
	$module	= !empty($_GET['module']) ? $_GET['module'] : '';
	$act	= !empty($_GET['act']) ? $_GET['act'] : '';
	
	$aksi="module/mod_rate/rate_customer_action.php";

	
	// List paket
	if ($module=='rate' and $act=='read')
	{
		$id_kustomer = $_GET['id_kustomer'];
		$table = 
<<<EOT
	(
		select 						id,
									id_kustomer,
									x_namakustomer(id_kustomer) as nama_kustomer,
									id_lokasi_asal,
									x_namakecamatan(id_lokasi_asal) as kecamatan_asal,
									x_namakotanew(id_lokasi_asal) as kota_asal,
									CONCAT( if(x_namakecamatan(id_lokasi_asal) != '',x_namakecamatan(id_lokasi_asal), '' )  ,' - ', if(x_namakotanew(id_lokasi_asal) != '',x_namakotanew(id_lokasi_asal),'' )   ) as asal,
									id_lokasi_tujuan,
									x_namakotanew(id_lokasi_tujuan) as kota_tujuan,
									CONCAT( if(x_namakecamatan(id_lokasi_tujuan) != '',x_namakecamatan(id_lokasi_tujuan), '' )  ,' - ', if(x_namakotanew(id_lokasi_tujuan) != '',x_namakotanew(id_lokasi_tujuan),'' )   ) as tujuan,
									id_pelayanan,
									x_service(id_pelayanan) as service,
									loc_code,
									min_kg,
									harga,
									lead_time
								FROM rate_customer
								
								where id_kustomer='$id_kustomer' and CHAR_LENGTH(id_lokasi_asal ) > 3 and CHAR_LENGTH(id_lokasi_tujuan ) > 3 
	) temp
EOT;
		$primaryKey = 'id';		
		$columns = array(
			
			array( 'db' => 'nama_kustomer', 'dt' => 0 ),
			array( 'db' => 'asal', 'dt' => 1 ),
			array( 'db' => 'tujuan', 'dt' => 2 ),
			array( 'db' => 'service', 'dt' => 3 ),			
			array( 'db' => 'loc_code', 'dt' => 4 ),
            array( 'db' => 'min_kg', 'dt' => 5 ),
            array( 'db' => 'harga', 'dt' => 6 ),
			array( 'db' => 'lead_time', 'dt' => 7 ),				
		);

		require('../../config/ssp.class.php');
		echo json_encode(
			SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns )
		);
	
	// create
	} 
	
}
?>