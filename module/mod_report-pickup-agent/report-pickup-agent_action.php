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
	
	$key = 'asdf$1011';
	$encrypt = $_SESSION['id_user'];
	$id = base64_decrypt($encrypt,$key);
	$useraktif = mysqli_query($con, 'select * from user where id_user="'.$id.'"');
	$u = mysqli_fetch_assoc($useraktif);
			
	// data
	if($module=='pickup' and $act=='data'){
		
			$table = 
<<<EOT
	(
	select
		DATE_FORMAT(a.pickup_date,'%Y-%m-%d') as pickup,
		x_namacabang(pickup_by_agent) as namalengkap,
		count(id_pengiriman) as jml,
		count(if(photo_pickup_sesuai='Y', 1, NULL)) as sesuai,
		sum(a.berat_barang) as berat,
		sum(a.collie) as collie,
		concat(a.pickup_by_agent,'-', DATE_FORMAT(a.pickup_date,'%Y-%m-%d')) as pickup_by,
		a.id_pengiriman
		from pengiriman a
	  where a.tanggal > DATE_SUB(now(), INTERVAL 2 MONTH) and a.pickup_by is not null and pickup_by_agent != 39 and status > 1 and status != 7
	group by DATE_FORMAT(a.pickup_date,'%y-%m-%d'), pickup_by_agent
	) temp
EOT;
		

		$primaryKey = 'id_pengiriman';

		$columns = array(
			array( 'db' => 'pickup',  'dt' => 0 ),
			array(
					'db'        => 'pickup',
					'dt'        => 0,
					'formatter' => function( $d, $row ) {
						return date( 'd-m-Y', strtotime($d));
					}
			),
			array( 'db' => 'namalengkap', 'dt'=> 1 ),
			array(
					'db'		=> 'namalengkap',
					'dt'		=> 1,
					'formatter'	=> function($d,$row){
						return '<td>'.strtoupper($d).'</td>';
					}
			),
			
			array( 'db' => 'jml', 'dt'=> 2 ),
			array(
				  'db'        => 'sesuai',
				  'dt'        => 3,
				  'formatter' => function( $d, $row ) {
					if($d < $row['jml']){
						$warning=' <button class="btn btn-xs btn-danger"><i class="fa fa-exclamation-circle"></i> </button>';
					}else{
						$warning = '';
					}
					return $d.' foto sesuai dari '. $row['jml'] .' awb'.$warning;
				  }
			),
			
			array( 'db' => 'collie', 'dt' => 4 ),
			array( 'db' => 'pickup_by', 'dt' => 5 ),
			array(
				  'db'        => 'pickup_by',
				  'dt'        => 5,
				  'formatter' => function( $d, $row ) {
					$key = "asdf$1011";
					return '<td><center><a class="btn btn-xs btn-primary" target="_blank" href="main.php?module=report-pickup-agent&act=view&id='.base64_encrypt($d,$key).'"><i class="fa fa-search"></i> View</a></center></td>';
				  }
			)
			

		);

		/*$sql_details = array(
			'user' => 'cesco_user',
			'pass' => 'srnGRkhl3c',
			'db'   => 'cesco_logistics',
			'host' => '127.0.0.1'
		);*/
		require( '../../config/ssp.class.php' );

		echo json_encode(
			SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns )
		);
	
	// delete
	}

	elseif ($module=='report-pickup-agent' AND $act=='checksesuai'){
		//$tanggal		= jin_date_sql($_POST['tanggalplus']);
		$awb		= $_POST['selectdeliv'];
		$id_awb		= implode(',',$awb);
		//echo $id_awb;
		//echo "update pengiriman set photo_pickup_sesuai='Y' where id_pengiriman in ($id_awb)";

		mysqli_query($con, "update pengiriman set photo_pickup_sesuai='Y' where id_pengiriman in ($id_awb)");
		header('Location: ' . $_SERVER['HTTP_REFERER']);
	}

}
?>