<?php
if(!isset($_SESSION)){
    session_start();
}

if (empty($_SESSION['username']) AND empty($_SESSION['password'])){
	header('location:../application/index.php');
}
else{
	include "../../config/connection.php";
	include "../../config/db_connect.php";
	include "../../config/library.php";
	include "../../config/encrypt.php";
	
	$module	= !empty($_GET['module']) ? $_GET['module'] : '';
	$act	= !empty($_GET['act']) ? $_GET['act'] : '';
	
	$key = 'asdf$1011';
	$encrypt = $_SESSION['id_user'];
	$idUser = base64_decrypt($encrypt,$key);
	$useraktif = mysqli_query($con, 'select * from user where id_user="'.$idUser.'"');
	$u = mysqli_fetch_assoc($useraktif);
	$aksi="module/mod_finance-awb-customer-belum-tertagih/finance-awb-customer-belum-tertagih_action.php";
	
	$level = $u['level'];
	
	
	// data
	if($module=='belum-tertagih' and $act=='data'){

		
	$table = 
<<<EOT
	(
	SELECT
		a.airwaybill_kode, 
		a.tanggal, 
		x_namakustomer(a.id_kustomer) as kustomer,
		a.id_kustomer,
		a.pickup_date,
		a.courier_date,
		a.id_pengiriman,
		p.id_pembayaran 
		FROM pengiriman a 
		left   join pembayaran_tunai p on p.id_pengiriman = a.id_pengiriman 
		left   join pembayaran_or r on r.airwaybill = a.airwaybill_kode
		where (a.id_invoice is null or a.id_invoice = 0) and 
		ifnull(pickup_date,'') != '' and ifnull(courier_date,'') != '' 
		and pickup_date != '0000-00-00' and courier_date != '0000-00-00' and status !=7
		and a.tanggal > DATE_SUB(now(), INTERVAL 24 MONTH) 
		and (p.jumlah_bayar=0 or p.jumlah_bayar is null )
		and (r.jumlah_bayar=0 or r.jumlah_bayar is null )
		and a.id_kustomer not in (79,80,81) 
	) temp	
EOT;
	

		$primaryKey = 'id_pengiriman';

		$columns = array(
			array( 'db' => 'airwaybill_kode', 'dt' => 0 ),			
			array( 'db' => 'kustomer', 'dt'=> 1 ),
			array('db' => 'pickup_date',
					'dt'		=> 2,
					'formatter'	=> function($d,$row){
						if($d==''){
							$pickup_date = '';
						}else{
							$pickup_date = date( 'd-m-Y', strtotime($d));
						}
						return '<td><center>'.$pickup_date.'</center></td>';
					}
			),
			array('db' => 'courier_date',
					'dt'		=> 3,
					'formatter'	=> function($d,$row){
						if($d=='' || $d=='0000-00-00'){
							$courier_date = '';
						}else{
							$courier_date = date( 'd-m-Y', strtotime($d));
						}
						return '<td><center>'.$courier_date.'</center></td>';
					}
			),
			array('db' => 'id_kustomer',
					'dt'		=> 5),
			array('db' => 'tanggal',
					'dt'		=> 6),
			array('db' => 'id_pembayaran',
					'dt'		=> 7),
			
			array(
				  'db'        => 'id_pengiriman',
				  'dt'        => 4,
				  'formatter' => function( $d, $row ) {
					$id = $d;
					
					$bayarStts = ($row['id_pembayaran'] == '') ? 'Bayar Cash' : 'Sudah Bayar Cash';
					$warnaBayar = ($row['id_pembayaran'] == '') ? 'btn-danger' : 'btn-success';
					
					return '<td><center>
							<button class="btn btn-xs btn-success" onClick="window.open(\'reportsnew/airwaybillnew.php?id='.base64_encrypt($id,$GLOBALS['key']).'\',\'mywindow\',\'menubar=1,resizable=1,width=800,height=600\');"><i class="fa fa-print"></i> New</button>
							<button class="btn btn-xs btn-danger" onClick="window.open(\'client/reports/airwaybill_asus.php?id='.$id.'\',\'mywindow\',\'menubar=1,resizable=1,width=800,height=600\');"><i class="fa fa-print"></i> ASUS</button>
							<button class="btn btn-xs btn-danger" onclick="window.location.href = \'module/mod_finance-awb-customer-belum-tertagih/finance-awb-customer-belum-tertagih_action.php?module=belum-tertagih&act=tagihkan&id_pengiriman='.$id.'&id_kustomer='.$row['id_kustomer'].'&tanggal='.$row['tanggal'].'\';"><i class="fa fa-money"></i> Tagihkan</button>
							<button type="button" class="btn btn-xs '.$warnaBayar.' frm_sudah_bayar" data-toggle="modal" data-target="#modal_bayar" data-id="'.$id.'" data-id-bayar="'.$id.'" data-awb="'.$row['airwaybill_kode'].'"> <i class="fa fa-money"></i> '.$bayarStts.'</button>
							</center></td>';						
					
				  }
			)

		);
		
		

		require( '../../config/ssp.class.php' );

		echo json_encode(
			SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns )
		);
	

	}
	elseif($module=='belum-tertagih' and $act=='tagihkan'){
		$tanggal		= $mysqli->real_escape_string(jin_date_sql($_GET['tanggal']));
		$id_pengiriman = $_GET['id_pengiriman'];
		$id_kustomer = $_GET['id_kustomer'];
		
		
		$query = mysqli_query($con, "select id_invoice from invoice where id_kustomer = $id_kustomer
				and status <> 'final' and date_format(tanggal,'%Y-%m') = date_format(now(),'%Y-%m')
				order by id_invoice desc limit 1");
		$r = mysqli_fetch_assoc($query);
		
		$id_invoice = $r['id_invoice'];
		if ($id_invoice == ''){
			
			$mysqli->query("INSERT into invoice (tanggal,id_kustomer,komoditi,bank,due_date,judul_awal,method,ppn,status,lunas,id_user,created_date) values(now(),'$id_kustomer','1,2','1',now(),'Judul Invoice',1,1,'draft','N',$idUser,now())");
			$id_invoice = $mysqli->insert_id;
				
		}
		
		$mysqli->query("UPDATE pengiriman set id_invoice=$id_invoice WHERE id_pengiriman= $id_pengiriman");
			
		$_SESSION['pesan'] = 'success';
		$id	= base64_encrypt($id_invoice,$key);
		
		header('location:../../main.php?module=invoice-customer&act=update&id='.$id);
		
	}elseif($module=='belum-tertagih' and $act=='detail-bayar'){
		$id_pengiriman = $_POST['id_pengiriman'];
		
		$query = mysqli_query($con, "select * from pembayaran_tunai where id_pengiriman = $id_pengiriman");
		$r = mysqli_fetch_assoc($query);
		
		echo json_encode($r);
		
	}elseif($module=='belum-tertagih' and $act=='bayar'){		
		
		$id_pengiriman = $_POST['id_pengiriman'];
		$id_bayar = $_POST['id_bayar'];
		$tanggal = $mysqli->real_escape_string(jin_date_sql($_POST['tanggal']));
		$nilai = $_POST['nilai'];
		$penerima = $_POST['penerima'];
		
		if ($id_bayar){
			$mysqli->query("UPDATE pembayaran_tunai set jumlah_bayar=$nilai, tanggal_bayar = '$tanggal',
						diterima_oleh = '$penerima', update_dtm = now(), update_by = $idUser			
			WHERE id_pengiriman= $id_pengiriman");
			
		}else{
			$mysqli->query("INSERT into pembayaran_tunai (id_pengiriman,jumlah_bayar,tanggal_bayar,diterima_oleh,input_dtm,input_by) 
					values($id_pengiriman,$nilai,'$tanggal','$penerima',now(),$idUser)");
			$id_bayar = $mysqli->insert_id;
		}
		
		header('location:../../main.php?module=awb-belum-tertagih');
		
	}
	
}
?>