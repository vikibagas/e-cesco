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
	if($module=='delivery' and $act=='data'){
		
		if($u['level']=='kurir' || $u['level']=='driver'){
			$table = 
<<<EOT
	(
	select
		CASE 
	        WHEN DATE(a.courier_date) > CURDATE() THEN DATE_FORMAT(CURDATE(), '%Y-%m-%d')
	        ELSE DATE_FORMAT(a.courier_date, '%Y-%m-%d')
	    END AS delivery,
		a.kode_manifest,
		b.namalengkap,
		count(id_pengiriman) as jml,
		sum(a.berat_barang) as berat,
		sum(a.collie) as collie,
		CONCAT(a.courier_by, '-', 
	        CASE 
	            WHEN DATE(a.courier_date) > CURDATE() THEN DATE_FORMAT(CURDATE(), '%Y-%m-%d')
	            ELSE DATE_FORMAT(a.courier_date, '%Y-%m-%d')
	        END
	    ) as courier_by,
		a.id_pengiriman
		from pengiriman a
	inner join user b on b.id_user=a.courier_by 
	where a.courier_date > DATE_SUB(NOW(), INTERVAL 2 MONTH) 
	and a.courier_by is not null and a.courier_by='$u[id_user]' 
	GROUP BY 1, 6
	) temp
EOT;
		}elseif($u['level']=='intercity'){
			$table = 
<<<EOT
	(
	select
		CASE 
	        WHEN DATE(a.courier_date) > CURDATE() THEN DATE_FORMAT(CURDATE(), '%Y-%m-%d')
	        ELSE DATE_FORMAT(a.courier_date, '%Y-%m-%d')
	    END AS delivery,
		b.namalengkap,
		count(id_pengiriman) as jml,
		sum(a.berat_barang) as berat,
		sum(a.collie) as collie,
		CONCAT(a.courier_by, '-', 
	        CASE 
	            WHEN DATE(a.courier_date) > CURDATE() THEN DATE_FORMAT(CURDATE(), '%Y-%m-%d')
	            ELSE DATE_FORMAT(a.courier_date, '%Y-%m-%d')
	        END
	    ) as courier_by,
		a.id_pengiriman
		from pengiriman a
	inner join user b on b.id_user=a.courier_by 
	where a.status != 7 and  a.courier_date > DATE_SUB(NOW(), INTERVAL 2 MONTH) 
	and a.courier_by is not null and if(a.id_kota_asal >0 , x_inter(a.id_kota_tujuan), x_intercity(a.lokasi_tujuan)) ='Y'
	GROUP BY 1, 6
	) temp
EOT;
		}elseif($u['level']=='agent'){
			$table = 
<<<EOT
	(
	select
		CASE 
	        WHEN DATE(a.courier_date) > CURDATE() THEN DATE_FORMAT(CURDATE(), '%Y-%m-%d')
	        ELSE DATE_FORMAT(a.courier_date, '%Y-%m-%d')
	    END AS delivery,
		b.namalengkap,
		count(id_pengiriman) as jml,
		sum(a.berat_barang) as berat,
		sum(a.collie) as collie,
		CONCAT(a.courier_by, '-', 
	        CASE 
	            WHEN DATE(a.courier_date) > CURDATE() THEN DATE_FORMAT(CURDATE(), '%Y-%m-%d')
	            ELSE DATE_FORMAT(a.courier_date, '%Y-%m-%d')
	        END
	    ) as courier_by,
		a.id_pengiriman
		from pengiriman a
	inner join user b on b.id_user=a.courier_by where 
	a.courier_date > DATE_SUB(NOW(), INTERVAL 2 MONTH) 
	and a.courier_by is not null and a.delivery_by_agent='$u[id_cabang]' 
	GROUP BY 1, 6
	) temp
EOT;
		}else{
			$table = 
<<<EOT
	(
	SELECT
	    CASE 
	        WHEN DATE(a.courier_date) > CURDATE() THEN DATE_FORMAT(CURDATE(), '%Y-%m-%d')
	        ELSE DATE_FORMAT(a.courier_date, '%Y-%m-%d')
	    END AS delivery,
	    b.namalengkap,
	    COUNT(id_pengiriman) as jml,
	    SUM(a.berat_barang) as berat,
	    SUM(a.collie) as collie,
	    CONCAT(a.courier_by, '-', 
	        CASE 
	            WHEN DATE(a.courier_date) > CURDATE() THEN DATE_FORMAT(CURDATE(), '%Y-%m-%d')
	            ELSE DATE_FORMAT(a.courier_date, '%Y-%m-%d')
	        END
	    ) as courier_by,
	    a.id_pengiriman
	FROM pengiriman a
	INNER JOIN user b ON b.id_user = a.courier_by 
	WHERE a.courier_date > DATE_SUB(NOW(), INTERVAL 2 MONTH) 
	  AND a.courier_by IS NOT NULL 
	  AND b.id_cabang IN (11,16,39,41)
	 
	GROUP BY 1, 6
    
	) temp
EOT;
		}

		$primaryKey = 'id_pengiriman';

		$columns = array(
			array( 'db' => 'delivery',  'dt' => 0 ),
			array(
					'db'        => 'delivery',
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
			array( 'db' => 'berat', 'dt'=> 3 ),
			array( 'db' => 'collie', 'dt' => 4 ),
			array( 'db' => 'courier_by', 'dt' => 5 ),
			array(
				  'db'        => 'courier_by',
				  'dt'        => 5,
				  'formatter' => function( $d, $row ) {
					$key = "asdf$1011";
					return '<td><center><a class="btn btn-xs btn-primary" target="_blank" href="main.php?module=report-manifest-delivery&act=view&id='.base64_encrypt($d,$key).'"><i class="fa fa-search"></i> View</a></center></td>';
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
	}elseif($module=='airwaybill' and $act=='cancel'){
		$key = "asdf$1011";
		$encrypt = "$_GET[id]";
		$id = base64_decrypt($encrypt,$key);
		mysqli_query($con, "update pengiriman set status='5' where id_pengiriman='$id'");
		header('location:../../main.php?module='.$module);
	}

	elseif ($module=='report-manifest-delivery' AND $act=='plus'){
		$tanggal		= jin_date_sql($_POST['tanggalplus']);
		$awb		= $_POST['selectawb'];
		$id_awb		= implode(',',$awb);
		//echo "update pengiriman set courier_date='$tanggal' where id_pengiriman in ($id_awb)";

		mysqli_query($con, "update pengiriman set manifest_delivery_date='$tanggal', courier_date='$tanggal' where id_pengiriman in ($id_awb)");
		header('Location: ' . $_SERVER['HTTP_REFERER']);
	}

	elseif ($module=='report-manifest-delivery' AND $act=='checksesuai'){
		//$tanggal		= jin_date_sql($_POST['tanggalplus']);
		$awb		= $_POST['selectdeliv'];
		$id_awb		= implode(',',$awb);
		//echo $id_awb;
		//echo "update pengiriman set photo_delivery_sesuai='Y' where id_pengiriman in ($id_awb)";

		mysqli_query($con, "update pengiriman set photo_delivery_sesuai='Y' where id_pengiriman in ($id_awb)");
		header('Location: ' . $_SERVER['HTTP_REFERER']);
	}

}
?>