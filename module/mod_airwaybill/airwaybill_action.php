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
	$id = base64_decrypt($encrypt,$key);
	$useraktif = mysqli_query($con, 'select * from user where id_user="'.$id.'"');
	$u = mysqli_fetch_assoc($useraktif);
	$aksi="module/mod_airwaybill/airwaybill_action.php";
	
	$level = $u['level'];
	
	
	// data
	if($module=='airwaybill' and $act=='data'){

		if($u['level'] == 'kurir' and $u['id_user'] == 87){
		
			$table = 
<<<EOT
	(
	SELECT
		a.airwaybill_kode, 
		a.created_date as tanggal, 
		x_namakustomer(a.id_kustomer) as id_kustomer,
		case when id_kota_asal>0 then kota_asal else x_namakotabesar(a.lokasi_asal) end as lokasi_asal,
		case when id_kota_tujuan>0 then kota_tujuan 
		else 
			x_namakotabesar(a.lokasi_tujuan) 
		end as lokasi_tujuan,
		a.komoditi,
		concat(if(a.terlambat_pickup > 0 && a.terlambat_pickup != 0,1,0),'-' ,if(a.terlambat_delivery <= 3 && a.terlambat_delivery != 0,1,0)) as kode_alasan,
		concat(a.status,'-',ifnull(a.transit,'N'),'-',ifnull(a.pickup_request,'N'),'-', ifnull(a.received_transit,'N'),'-',a.id_pengiriman ) as status,
		a.inbound,
		a.pickup_date,
		x_user(a.pickup_by) as pickup_by,
		x_user(a.courier_by) as courier_by,
		a.delivery_date,
		x_user(a.delivery_by) as delivery_by,
		case when status='8' then delivery_note else delivery_name end as delivery_name,
		concat(a.inbound,'-',a.id_pengiriman,'-',id_kustomer,'-','$u[level]') as id,
		id_pengiriman,
		print_label
		
		FROM pengiriman a where id_kustomer != 0 and pickup_by = 87 or courier_by = 87
	) temp
EOT;
	}


	elseif($u['level']=='domex'){

		if ($u['id_kustomer'] > 0 ){
			$table = 
<<<EOT
	(
	SELECT
		a.airwaybill_kode, 
		a.created_date as tanggal, 
		x_namakustomer(a.id_kustomer) as id_kustomer,
		case when id_kota_asal>0 then kota_asal else x_namakotabesar(a.lokasi_asal) end as lokasi_asal,
		case when id_kota_tujuan>0 then kota_tujuan 
		else 
			x_namakotabesar(a.lokasi_tujuan) 
		end as lokasi_tujuan,
		a.komoditi,
		concat(if(a.terlambat_pickup > 0 && a.terlambat_pickup != 0,1,0),'-' ,if(a.terlambat_delivery <= 3 && a.terlambat_delivery != 0,1,0)) as kode_alasan,
		concat(a.status,'-',ifnull(a.transit,'N'),'-',ifnull(a.pickup_request,'N'),'-', ifnull(a.received_transit,'N'),'-',a.id_pengiriman ) as status,
		a.inbound,
		a.pickup_date,
		x_user(a.pickup_by) as pickup_by,
		x_user(a.courier_by) as courier_by,		
		a.delivery_date,
		x_user(a.delivery_by) as delivery_by,
		case when status='8' then delivery_note else delivery_name end as delivery_name,
		concat(a.inbound,'-',a.id_pengiriman,'-',id_kustomer,'-','$u[level]') as id,
		id_pengiriman,
		print_label
		
		FROM pengiriman a where tanggal > DATE_SUB(now(), INTERVAL 5 MONTH) and id_kustomer = $u[id_kustomer]
	) temp	
EOT;

		}
		else{
			$table = 
<<<EOT
	(
	SELECT
		a.airwaybill_kode, 
		a.created_date as tanggal, 
		x_namakustomer(a.id_kustomer) as id_kustomer,
		case when id_kota_asal>0 then kota_asal else x_namakotabesar(a.lokasi_asal) end as lokasi_asal,
		case when id_kota_tujuan>0 then kota_tujuan 
		else 
			x_namakotabesar(a.lokasi_tujuan) 
		end as lokasi_tujuan,
		a.komoditi,
		concat(if(a.terlambat_pickup > 0 && a.terlambat_pickup != 0,1,0),'-' ,if(a.terlambat_delivery <= 3 && a.terlambat_delivery != 0,1,0)) as kode_alasan,
		concat(a.status,'-',ifnull(a.transit,'N'),'-',ifnull(a.pickup_request,'N'),'-', ifnull(a.received_transit,'N'),'-',a.id_pengiriman ) as status,
		a.inbound,
		a.pickup_date,
		x_user(a.pickup_by) as pickup_by,
		x_user(a.courier_by) as courier_by,		
		a.delivery_date,
		x_user(a.delivery_by) as delivery_by,
		case when status='8' then delivery_note else delivery_name end as delivery_name,
		concat(a.inbound,'-',a.id_pengiriman,'-',id_kustomer,'-','$u[level]') as id,
		id_pengiriman,
		print_label
		
		FROM pengiriman a where id_kustomer != 0 and tanggal > DATE_SUB(now(), INTERVAL 5 MONTH)
	) temp	
EOT;
		}
	}

	elseif($u['level']=='agent'){


			$table = 
<<<EOT
	(
	SELECT
		a.airwaybill_kode, 
		a.created_date as tanggal, 
		x_namakustomer(a.id_kustomer) as id_kustomer,
		case when id_kota_asal>0 then kota_asal else x_namakotabesar(a.lokasi_asal) end as lokasi_asal,
		case when a.service != 6 then x_namakotanew(a.id_kota_tujuan) else x_namanegara(a.lokasi_tujuan) end as lokasi_tujuan,
		a.komoditi,
		concat(if(a.terlambat_pickup > 0 && a.terlambat_pickup != 0,1,0),'-' ,if(a.terlambat_delivery <= 3 && a.terlambat_delivery != 0,1,0)) as kode_alasan,
		concat(a.status,'-',ifnull(a.transit,'N'),'-',ifnull(a.pickup_request,'N'),'-', ifnull(a.received_transit,'N'),'-',a.id_pengiriman ) as status,
		a.inbound,
		a.pickup_date,
		x_user(a.pickup_by) as pickup_by,
		x_user(a.courier_by) as courier_by,		
		a.delivery_date,
		x_user(a.delivery_by) as delivery_by,
		case when a.status='8' then delivery_note else delivery_name end as delivery_name,
		concat(a.inbound,'-',a.id_pengiriman,'-',id_kustomer,'-','$u[level]') as id,
		id_invoice,
		a.id_pengiriman,
		print_label
		
		FROM pengiriman a 

		left join manifest_detail c on c.id_pengiriman = a.id_pengiriman
		
		LEFT JOIN manifest d 
    ON d.id_manifest = c.id_manifest
    AND (d.manifest_to = '$u[id_cabang]' OR d.manifest_from = '$u[id_cabang]')

		where id_kustomer != 0 and a.tanggal > DATE_SUB(now(), INTERVAL 5 MONTH) AND (
    pickup_by_agent = $u[id_cabang]
    OR delivery_by_agent = $u[id_cabang]
    OR d.id_manifest IS NOT NULL
)
group by a.id_pengiriman


	) temp	
EOT;
		
		
	}

	else{
	$table = 
<<<EOT
	(
	SELECT
		a.airwaybill_kode, 
		a.created_date as tanggal, 
		x_namakustomer(a.id_kustomer) as id_kustomer,
		case when id_kota_asal>0 then kota_asal else x_namakotabesar(a.lokasi_asal) end as lokasi_asal,
		case when a.service != 6 then x_namakotanew(a.id_kota_tujuan) else x_namanegara(a.lokasi_tujuan) end as lokasi_tujuan,
		a.komoditi,
		concat(if(a.terlambat_pickup > 0 && a.terlambat_pickup != 0,1,0),'-' ,if(a.terlambat_delivery <= 3 && a.terlambat_delivery != 0,1,0)) as kode_alasan,
		concat(a.status,'-',ifnull(a.transit,'N'),'-',ifnull(a.pickup_request,'N'),'-', ifnull(a.received_transit,'N'),'-',a.id_pengiriman ) as status,
		a.inbound,
		a.pickup_date,
		x_user(a.pickup_by) as pickup_by,
		x_user(a.courier_by) as courier_by,		
		a.delivery_date,
		x_user(a.delivery_by) as delivery_by,
		case when status='8' then delivery_note else delivery_name end as delivery_name,
		concat(a.inbound,'-',a.id_pengiriman,'-',id_kustomer,'-','$u[level]') as id,
		id_invoice,
		id_pengiriman,
		print_label
		FROM pengiriman a where id_kustomer != 0 and tanggal > DATE_SUB(now(), INTERVAL 8 MONTH)
	) temp	
EOT;
	}

		$primaryKey = 'id_pengiriman';

		if ($u['level'] == 'admin' || $u['level'] == 'admin_network'){
		$columns = array(
			array( 'db' => 'airwaybill_kode', 'dt' => 0 ),
			array( 'db' => 'id_pengiriman', 'dt' => null ),
			array( 'db' => 'print_label', 'dt' => null ),
			
			array(
					'db'        => 'tanggal',
					'dt'        => 1,
					'formatter' => function( $d, $row ) {
						return date( 'd-m-Y H:i', strtotime($d));
					}
			),
			array( 'db' => 'id_kustomer', 'dt'=> 2 ),
			array(
				  'db'        => 'lokasi_asal',
				  'dt'        => 3,
				  'formatter' => function( $d, $row ) {
					if($d=='Jakarta Selatan' || $d=='Jakarta Timur' || $d=='Jakarta Barat' ||	$d=='Jakarta Utara' || $d=='Jakarta Pusat'){
						$lokasi_asal = 'Jakarta';
					}else{
						$lokasi_asal = $d;
					}
					return '<td>'.strtoupper($lokasi_asal).'</td>';
				  }
			),
			array(
				  'db'        => 'lokasi_tujuan',
				  'dt'        => 4,
				  'formatter' => function( $d, $row ) {
					if($d=='Jakarta Selatan' || $d=='Jakarta Timur' || $d=='Jakarta Barat' ||	$d=='Jakarta Utara' || $d=='Jakarta Pusat'){
						$lokasi_tujuan = 'Jakarta';
					}else{
						$lokasi_tujuan = $d;
					}
					return '<td>'.strtoupper($lokasi_tujuan).'</td>';
				  }
			),
			array(
				  'db'        => 'komoditi',
				  'dt'        => 5,
				  'formatter' => function( $d, $row ) {
					if($d=='1'){
						$komoditi = '<button class="btn btn-block btn-xs"><i class="fa fa-cube"></i> Package</button>';
					}else{
						$komoditi = '<button class="btn btn-block btn-xs"><i class="fa fa-book"></i> Document</button>';
					}
					return '<td>'.$komoditi.'</td>';
				  }
			),
			array(
				  'db'        => 'status',
				  'dt'        => 6,
				  'formatter' => function( $d, $row ) {
				  	
				  	$pieces = explode('-', $d);
					$st = $pieces['0'];
					$transit = $pieces['1'];
					$pickup_req = $pieces['2'];
					$received_transit = $pieces['3'];
					$id_pengiriman = $pieces['4'];

					if($st=='1' && $pickup_req == 'N'){
						$status = '<button class="btn btn-block btn-warning btn-xs open_status " data-id="'.$id_pengiriman.'"> Booking</button>';

					}elseif($st=='1' && $pickup_req != 'N'){
						$status = '<button class="btn btn-block btn-warning btn-xs open_status" data-id="'.$id_pengiriman.'">Proses Pick Up</button>';
					}
					elseif($st=='2' && $transit == 'Y' && $received_transit == 'N'){
						$status = '<button class="btn btn-block btn-info btn-xs open_status" data-id="'.$id_pengiriman.'">Dalam Proses Pengiriman ke Kota Transit</button>';
					}
					elseif($st=='2' && $transit == 'Y' && $received_transit == 'Y'){
						$status = '<button class="btn btn-block btn-info btn-xs open_status" data-id="'.$id_pengiriman.'">Di kota transit</button>';
					}
					elseif($st=='2' && $transit == 'N'){
						$status = '<button class="btn btn-block btn-info btn-xs open_status" data-id="'.$id_pengiriman.'">PICKUP </button>';
					}
					elseif($st=='3'){
						$status = '<button class="btn btn-block btn-info btn-xs open_status" data-id="'.$id_pengiriman.'">Dalam Proses Pengiriman ke Kota Tujuan</button>';
					}elseif($st=='4'){
						$status = '<button class="btn btn-block btn-primary btn-xs open_status" data-id="'.$id_pengiriman.'">Dalam proses di kota Penerima</button>';
					}elseif($st=='5'){
						$status = '<button class="btn btn-block btn-primary btn-xs open_status" data-id="'.$id_pengiriman.'">Proses Pengantaran</button>';
					}elseif($st=='6'){
						$status = '<button class="btn btn-block btn-success btn-xs open_status" data-id="'.$id_pengiriman.'">DELIVERED</button>';
					}elseif($st=='7'){
						$status = '<button class="btn btn-block btn-danger btn-xs open_status" data-id="'.$id_pengiriman.'">CANCEL</button>';
					}elseif($st=='8'){
						$status = '<button class="btn btn-block btn-danger btn-xs open_status" data-id="'.$id_pengiriman.'">RETURN</button>';
					}
					return '<td><center>'.$status.'</center></td>';
				  }
			),
			array(
				'db'		=> 'kode_alasan',
				'dt'		=> 7,
				'formatter'	=> function($d,$row){

					$pieces = explode('-', $d);
					$pickup = $pieces[0];
					
					$deliv = $pieces[1];
					if($pickup==1 || $deliv ==1 ){
						$warning='<button class="btn btn-xs btn-danger"><i class="fa fa-exclamation-circle"></i> </button>';
					}else{
						$warning='';
					}
					return '<td><center>'.$warning.'</center></td>';
				}
			),
			array('db' => 'pickup_date',
					'dt'		=> 8,
					'formatter'	=> function($d,$row){
						if($d==''){
							$pickup_date = '';
						}else{
							$pickup_date = '<a href="#" class="edit_pickupdate" data-type="text" data-id="'.$row['id_pengiriman'].'">'.$d.'</a>';
						}
						return '<td><center>'.$pickup_date.'</center></td>';
					}
			),
			array('db' => 'pickup_by',
					'dt'		=> 9,
					'formatter'	=> function($d,$row){
						return '<td>'.strtoupper($d ?: '').'</td>';
					}
			),
			array('db' => 'delivery_date',
					'dt'		=> 10,
					'formatter'	=> function($d,$row){
						if($d=='' || $d=='0000-00-00'){
							$courier_date = '';
						}else{
							$courier_date = date( 'd-m-Y', strtotime($d));
						}
						return '<td><center>'.$courier_date.'</center></td>';
					}
			),
			array(
				'db'        => 'courier_by',
				'dt'        => 11,
				'formatter' => function($d, $row){
					return '<td>'.strtoupper((string)$d).'</td>';
				}
			),
			array(
				'db' => 'delivery_name',
				'dt' => 12,
				'formatter' => function($d, $row){
					return '<td>'.strtoupper($d ?: '').'</td>';
				}
			),
			
			array(
				  'db'        => 'id',
				  'dt'        => 13,
				  'formatter' => function( $d, $row ) {
					$aksi="module/mod_airwaybill/airwaybill_action.php";
					$pieces = explode('-', $d);
					$inbound = $pieces['0'];
					$id = $pieces['1'];
					$key = "asdf$1011";
					if($inbound=='Y'){
						$awb='airwaybill_inbound';
						$booking='outbound';
					}else{
						$awb='airwaybill';
						$booking='outbound';
					}

					$id_kustomer = $pieces['2'];

					$level = $pieces['3'];
					
					$keyu = 'asdf$1011';
					$encryptu = $_SESSION['id_user'];
						$idu = base64_decrypt($encryptu,$keyu);
						
						if($id_kustomer == 106){
							$print_asus = 

								'<button class="btn btn-xs btn-danger" onClick="window.open(\'reportsnew/awb_asus.php?id='.base64_encrypt($id,$key).'\',\'mywindow\',\'menubar=1,resizable=1,width=800,height=600\');"><i class="fa fa-print"></i>ASUS NEW</button>'

							;
						}else{
							$print_asus = '';
						}

						$back_to_pickup = '<button class="btn btn-xs btn-danger" onclick="window.location.href = \'module/mod_airwaybill/airwaybill_action.php?module=airwaybill&act=backtopickup&id_pengiriman='.$id.'\';"><i class="fa fa-angle-left"></i>back to pickup</button>';
						// ===== FIX: tombol edit/booking dikunci sampai label di-print =====
						/*$printLabel = isset($row['print_label']) ? (int) $row['print_label'] : 0;
						$isGenuineOutbound = (
							isset($row['inter_asal']) && isset($row['inter_tujuan'])
							&& $row['inter_asal'] === 'Y'
							&& $row['inter_tujuan'] !== 'Y'
						);*/
						/*if ($isGenuineOutbound && $printLabel <= 0) {
							$btnEdit = '<button class="btn btn-xs btn-warning" onclick="alert(\'Harap print label terlebih dahulu sebelum booking/edit!\');"><i class="fa fa-pencil"></i></button>';
						} else {*/
							$btnEdit = '<button class="btn btn-xs btn-warning" onclick="window.location.href = \'?module=airwaybill&act='.$booking.'&id='.base64_encrypt($id,$key).'\';"><i class="fa fa-pencil"></i></button>';
						//}

						return '
						<td><center>
							'.$btnEdit.' 
							<button class="btn btn-xs btn-danger open_cancel" data-id="'.base64_encrypt($id,$key).'"><i class="fa fa-remove"></i></button> 
							
							<button class="btn btn-xs btn-success" onClick="window.open(\'reportsnew/airwaybillnew.php?id='.base64_encrypt($id,$key).'\',\'mywindow\',\'menubar=1,resizable=1,width=800,height=600\');"><i class="fa fa-print"></i> AWB</button>

							<button class="btn btn-xs btn-success" onClick="window.open(\'reportsnew/awb_label.php?id='.base64_encrypt($id,$key).'\',\'mywindow\',\'menubar=1,resizable=1,width=800,height=600\');"><i class="fa fa-print"></i> label</button>

							<button class="btn btn-xs btn-success" onClick="window.open(\'reportsnew/form-pemeriksaan.php?id='.base64_encrypt($id,$key).'\',\'mywindow\',\'menubar=1,resizable=1,width=800,height=600\');"><i class="fa fa-print"></i> form</button>

							'.$print_asus.' '.($level == 'admin' ? $back_to_pickup : '').'
						
							
						</center></td>
						

						';
						
					
					
				  }
			)

		);
		}
		else{
			$columns = array(
			array( 'db' => 'airwaybill_kode', 'dt' => 0 ),
			array( 'db' => 'print_label', 'dt' => null ),
			array( 'db' => 'id_pengiriman', 'dt' => null ),
			
			array(
					'db'        => 'tanggal',
					'dt'        => 1,
					'formatter' => function( $d, $row ) {
						return date( 'd-m-Y H:i', strtotime($d));
					}
			),
			array( 'db' => 'id_kustomer', 'dt'=> 2 ),
			array(
				  'db'        => 'lokasi_asal',
				  'dt'        => 3,
				  'formatter' => function( $d, $row ) {
					if($d=='Jakarta Selatan' || $d=='Jakarta Timur' || $d=='Jakarta Barat' ||	$d=='Jakarta Utara' || $d=='Jakarta Pusat'){
						$lokasi_asal = 'Jakarta';
					}else{
						$lokasi_asal = $d;
					}
					return '<td>'.strtoupper($lokasi_asal).'</td>';
				  }
			),
			array(
				  'db'        => 'lokasi_tujuan',
				  'dt'        => 4,
				  'formatter' => function( $d, $row ) {
					if($d=='Jakarta Selatan' || $d=='Jakarta Timur' || $d=='Jakarta Barat' ||	$d=='Jakarta Utara' || $d=='Jakarta Pusat'){
						$lokasi_tujuan = 'Jakarta';
					}else{
						$lokasi_tujuan = $d;
					}
					return '<td>'.strtoupper($lokasi_tujuan).'</td>';
				  }
			),
			array(
				  'db'        => 'komoditi',
				  'dt'        => 5,
				  'formatter' => function( $d, $row ) {
					if($d=='1'){
						$komoditi = '<button class="btn btn-block btn-xs"><i class="fa fa-cube"></i> Package</button>';
					}else{
						$komoditi = '<button class="btn btn-block btn-xs"><i class="fa fa-book"></i> Document</button>';
					}
					return '<td>'.$komoditi.'</td>';
				  }
			),
			array(
				  'db'        => 'status',
				  'dt'        => 6,
				  'formatter' => function( $d, $row ) {
					$pieces = explode('-', $d);
					$st = $pieces['0'];
					$transit = $pieces['1'];
					$pickup_req = $pieces['2'];
					$received_transit = $pieces['3'];
					$id_pengiriman = $pieces['4'];

					if($st=='1' && $pickup_req == 'N'){
						$status = '<button class="btn btn-block btn-warning btn-xs open_status " data-id="'.$id_pengiriman.'"> Booking</button>';

					}elseif($st=='1' && $pickup_req != 'N'){
						$status = '<button class="btn btn-block btn-warning btn-xs open_status" data-id="'.$id_pengiriman.'">Proses Pick Up</button>';
					}
					elseif($st=='2' && $transit == 'Y' && $received_transit == 'N'){
						$status = '<button class="btn btn-block btn-info btn-xs open_status" data-id="'.$id_pengiriman.'">Dalam Proses Pengiriman ke Kota Tujuan</button>';
					}
					elseif($st=='2' && $transit == 'Y' && $received_transit == 'Y'){
						$status = '<button class="btn btn-block btn-info btn-xs open_status" data-id="'.$id_pengiriman.'">Dalam Proses Pengiriman ke Kota Tujuan</button>';
					}
					elseif($st=='2' && $transit == 'N'){
						$status = '<button class="btn btn-block btn-info btn-xs open_status" data-id="'.$id_pengiriman.'">PICKUP</button>';
					}
					elseif($st=='3'){
						$status = '<button class="btn btn-block btn-info btn-xs open_status" data-id="'.$id_pengiriman.'">Dalam Proses Pengiriman ke Kota Tujuan</button>';
					}elseif($st=='4'){
						$status = '<button class="btn btn-block btn-primary btn-xs open_status" data-id="'.$id_pengiriman.'">Dalam Proses di Kota Penerima</button>';
					}elseif($st=='5'){
						$status = '<button class="btn btn-block btn-primary btn-xs open_status" data-id="'.$id_pengiriman.'">Proses Pengantaran</button>';
					}elseif($st=='6'){
						$status = '<button class="btn btn-block btn-success btn-xs open_status" data-id="'.$id_pengiriman.'">DELIVERED</button>';
					}elseif($st=='7'){
						$status = '<button class="btn btn-block btn-danger btn-xs open_status" data-id="'.$id_pengiriman.'">CANCEL</button>';
					}elseif($st=='8'){
						$status = '<button class="btn btn-block btn-danger btn-xs open_status" data-id="'.$id_pengiriman.'">RETURN</button>';
					}
					return '<td><center>'.$status.'</center></td>';
				  }
			),
			array(
				'db'		=> 'kode_alasan',
				'dt'		=> 7,
				'formatter'	=> function($d,$row){

					$pieces = explode('-', $d);
					$pickup = $pieces[0];
					
					$deliv = $pieces[1];
					if($pickup==1 || $deliv ==1 ){
						$warning='<button class="btn btn-xs btn-danger"><i class="fa fa-exclamation-circle"></i> </button>';
					}else{
						$warning='';
					}
					return '<td><center>'.$warning.'</center></td>';
				}
			),
			array('db' => 'pickup_date',
					'dt'		=> 8,
					'formatter'	=> function($d,$row){
						if($d==''){
							$pickup_date = '';
						}else{
							$pickup_date = date( 'd-m-Y', strtotime($d));
						}
						return '<td><center>'.$pickup_date.'</center></td>';
					}
			),
			array('db' => 'pickup_by',
					'dt'		=> 9,
					'formatter'	=> function($d,$row){
						return '<td>'.strtoupper($d).'</td>';
					}
			),
			array('db' => 'delivery_date',
					'dt'		=> 10,
					'formatter'	=> function($d,$row){
						if($d=='' || $d=='0000-00-00'){
							$courier_date = '';
						}else{
							$courier_date = date( 'd-m-Y', strtotime($d));
						}
						return '<td><center>'.$courier_date.'</center></td>';
					}
			),
			array('db' => 'courier_by',
					'dt'		=> 11,
					'formatter'	=> function($d,$row){
						return '<td>'.strtoupper($d).'</td>';
					}
			),
			array('db' => 'delivery_name',
					'dt'		=> 12,
					'formatter'	=> function($d,$row){
						return '<td>'.strtoupper($d).'</td>';
					}
			),
			
			array(
				  'db'        => 'id',
				  'dt'        => 13,
				  'formatter' => function( $d, $row ) {
					$aksi="module/mod_airwaybill/airwaybill_action.php";
					$pieces = explode('-', $d);
					$inbound = $pieces['0'];
					$id = $pieces['1'];
					$key = "asdf$1011";
					if($inbound=='Y'){
						$awb='airwaybill_inbound';
						$booking='inbound';
					}else{
						$awb='airwaybill';
						$booking='outbound';
					}
					
					$id_kustomer = $pieces['2'];

					if($id_kustomer == 106){
							$print_asus = '<button class="btn btn-xs btn-danger" onClick="window.open(\'client/reports/airwaybill_asus.php?id='.base64_encrypt($id,$key).'\',\'mywindow\',\'menubar=1,resizable=1,width=800,height=600\');"><i class="fa fa-print"></i>ASUS</button>'.

								'<button class="btn btn-xs btn-danger" onClick="window.open(\'reportsnew/awb_asus.php?id='.base64_encrypt($id,$key).'\',\'mywindow\',\'menubar=1,resizable=1,width=800,height=600\');"><i class="fa fa-print"></i>ASUS NEW</button>'

							;
						}else{
							$print_asus = '';
						}
					
					

						return '<td><center>
						<button class="btn btn-xs btn-warning" onclick="window.location.href = \'?module=airwaybill&act='.$booking.'&id='.base64_encrypt($id,$key).'\';"><i class="fa fa-pencil"></i></button> 						
						<button class="btn btn-xs btn-success" onClick="window.open(\'reports/'.$awb.'.php?id='.base64_encrypt($id,$key).'\',\'mywindow\',\'menubar=1,resizable=1,width=800,height=600\');"><i class="fa fa-print"></i></button>
						<button class="btn btn-xs btn-success" onClick="window.open(\'reportsnew/awb_label.php?id='.base64_encrypt($id,$key).'\',\'mywindow\',\'menubar=1,resizable=1,width=800,height=600\');"><i class="fa fa-print"></i> label</button>
						<button class="btn btn-xs btn-success" onClick="window.open(\'reportsnew/airwaybillnew.php?id='.base64_encrypt($id,$key).'\',\'mywindow\',\'menubar=1,resizable=1,width=800,height=600\');"><i class="fa fa-print"></i> AWB</button>
						
						'.$print_asus.'
						

						</center></td>';
					
					
				  }
			)

		);
		}


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
	

	}
	elseif($module=='airwaybill' and $act=='read'){
		$query = "SELECT 
					a.airwaybill_kode, 
					a.created_date as tanggal, 
					x_namakustomer(a.id_kustomer) as id_kustomer, 
					x_namakotabesar(a.lokasi_asal) as lokasi_asal, 
					case when a.service='6' then x_namanegara(a.lokasi_tujuan) else x_namakotabesar(a.lokasi_tujuan) end as lokasi_tujuan, 
					a.komoditi, 
					a.status, 
					a.inbound, 
					a.pickup_date, 
					x_user(a.pickup_by) as pickup_by, 
					a.courier_date, 
					x_user(a.delivery_by) as delivery_by, 
					case when status='8' then delivery_note else delivery_name end as delivery_name, 
					concat(a.inbound,'-',a.id_pengiriman) as id,
					id_pengiriman 
				FROM pengiriman a";
		$result = $mysqli->query($query);
		while($row = $result->fetch_assoc()){
			echo'
			<tr>
				<td>'.$row['airwaybill_kode'].'</td>
				<td>'.$row['tanggal'].'</td>
				<td>'.$row['id_kustomer'].'</td>
				<td>'.$row['lokasi_asal'].'</td>
				<td>'.$row['lokasi_tujuan'].'</td>
				<td>'.$row['komoditi'].'</td>
				<td>'.$row['status'].'</td>
				<td>'.$row['inbound'].'</td>
				<td>'.$row['pickup_date'].'</td>
				<td>'.$row['pickup_by'].'</td>
				<td>'.$row['courier_date'].'</td>
				<td>'.$row['delivery_by'].'</td>
				<td>'.$row['delivery_name'].'</td>
				<td>'.$row['id'].'</td>
			</tr>';
		}
	// update airwaybill
	}elseif($module=='airwaybill' and $act=='update'){
		function escInt($con, $value) {
			$val = mysqli_real_escape_string($con, $value);
			return ($val === '') ? 0 : $val;
		}
		$id_pengiriman = mysqli_real_escape_string($con, $_POST['id']);
		$query_packing = mysqli_query($con, "select packing_kayu from pengiriman where id_pengiriman='".$id_pengiriman."' ");
		$p = mysqli_fetch_assoc($query_packing);

		$packing_sebelum = $p['packing_kayu'];


		$tanggal_form = mysqli_real_escape_string($con, $_POST['tanggal']);
		$tanggal = jin_date_sql($tanggal_form);
		$nama_pengirim = mysqli_real_escape_string($con, $_POST['nama_pengirim']);
		
		$alamat_pengirim1 = mysqli_real_escape_string($con, $_POST['alamat_pengirim1']);
		$alamat_pengirim2 = mysqli_real_escape_string($con, $_POST['alamat_pengirim2']);

		$provinsiasal = mysqli_real_escape_string($con, $_POST['provinsiasal']);
		$provinsiasal_text = mysqli_real_escape_string($con, $_POST['provinsiasal_text']);
		
		$kotaasal = mysqli_real_escape_string($con, $_POST['kotaasal']);
		$kotaasal_text = mysqli_real_escape_string($con, $_POST['kotaasal_text']);
		$kecamatanasal = mysqli_real_escape_string($con, $_POST['kecamatanasal']);
		$kecamatanasal_text = mysqli_real_escape_string($con, $_POST['kecamatanasal_text']);
		$kelurahanasal = mysqli_real_escape_string($con, $_POST['kelurahanasal']);

		$provinsi = mysqli_real_escape_string($con, $_POST['provinsi']);
		$provinsitujuan_text = mysqli_real_escape_string($con, $_POST['provinsitujuan_text']);
		
		$kota = mysqli_real_escape_string($con, $_POST['kota']);
		$kota_text = mysqli_real_escape_string($con, $_POST['kota_text']);
		$kecamatan = mysqli_real_escape_string($con, $_POST['kecamatan']);
		$kecamatan_text = mysqli_real_escape_string($con, $_POST['kecamatan_text']);
		$kelurahan = mysqli_real_escape_string($con, $_POST['kelurahan']);
		
		

		/*$catatan1 = mysqli_real_escape_string($con, $_POST['catatan1']);
		$catatan2 = mysqli_real_escape_string($con, $_POST['catatan2']);
		$catatan3 = mysqli_real_escape_string($con, $_POST['catatan3']);
		$catatan4 = mysqli_real_escape_string($con, $_POST['catatan4']);
		$catatan5 = mysqli_real_escape_string($con, $_POST['catatan5']);
		$catatan6 = mysqli_real_escape_string($con, $_POST['catatan6']);
		$catatan7 = mysqli_real_escape_string($con, $_POST['catatan7']);
		$catatan8 = mysqli_real_escape_string($con, $_POST['catatan8']);
		$catatan9 = mysqli_real_escape_string($con, $_POST['catatan9']);
		$catatan10 = mysqli_real_escape_string($con, $_POST['catatan10']);*/
		$detail_barang = mysqli_real_escape_string($con, $_POST['detail_barang']);
		if($detail_barang == 'Y'){
			$jumlah_detailbarang = mysqli_real_escape_string($con, $_POST['jumlah_detailbarang']);
		}
		else{
			$jumlah_detailbarang = 0;
		}

		$hp_pengirim = mysqli_real_escape_string($con, $_POST['hp_pengirim']);
		$perusahaan_pengirim = mysqli_real_escape_string($con, $_POST['perusahaan_pengirim']);
		//$lokasi_asal = mysqli_real_escape_string($con, $_POST['lokasi_asal']);
		
		$id_kustomer = mysqli_real_escape_string($con, $_POST['id_kustomer']);
		$nama_penerima = mysqli_real_escape_string($con, $_POST['nama_penerima']);
		
		$alamat_penerima1 = mysqli_real_escape_string($con, $_POST['alamat_penerima1']);
		$alamat_penerima2 = mysqli_real_escape_string($con, $_POST['alamat_penerima2']);

		$hp_penerima = mysqli_real_escape_string($con, $_POST['hp_penerima']);
		$perusahaan_penerima = mysqli_real_escape_string($con, $_POST['perusahaan_penerima']);
		$loc_code	= !empty($_POST['loc_code']) ? mysqli_real_escape_string($con, $_POST['loc_code']) : '';
		//$loc_code = mysqli_real_escape_string($con, $_POST['loc_code']);
		$pid	= !empty($_POST['pid']) ? mysqli_real_escape_string($con, $_POST['pid']) : '';
		//$pid = mysqli_real_escape_string($con, $_POST['pid']);
		//$lokasi_tujuan = mysqli_real_escape_string($con, $_POST['lokasi_tujuan']);		
		$id_cabang = mysqli_real_escape_string($con, $_POST['id_cabang']);
		//$negara_tujuan = mysqli_real_escape_string($con, $_POST['negara_tujuan']);
		$negara_tujuan	= !empty($_POST['negara_tujuan']) ? mysqli_real_escape_string($con, $_POST['negara_tujuan']) : '';

		$service = mysqli_real_escape_string($con, $_POST['service']);
		$komoditi = mysqli_real_escape_string($con, $_POST['komoditi']);
		$jenis_barang = mysqli_real_escape_string($con, $_POST['jenis_barang']);
		$berat = ceil(mysqli_real_escape_string($con, $_POST['berat_barang']));
		
		if($berat > 0){
			$berat_barang = $berat;
		}else{
			$berat_barang = 1;
		}
		//$ukuran_barang = mysqli_real_escape_string($con, $_POST['ukuran_barang']);
		$ukuran_barang = '';
		if(isset($_POST['length'])){
			$ukuran_barang .= mysqli_real_escape_string($con, $_POST['length']);
		}
		if(isset($_POST['width'])){
			$ukuran_barang .= 'x' . mysqli_real_escape_string($con, $_POST['width']);
		}
		if(isset($_POST['height'])){
			$ukuran_barang .= 'x' . mysqli_real_escape_string($con, $_POST['height']);
		}
		$jumlah_collie = mysqli_real_escape_string($con, $_POST['jumlah_collie']);
		$packing_kayu = mysqli_real_escape_string($con, $_POST['packing_kayu']);
		$asuransi = mysqli_real_escape_string($con, $_POST['asuransi']);
		$surat_jalan = mysqli_real_escape_string($con, $_POST['surat_jalan']);
		$str = mysqli_real_escape_string($con, $_POST['nilai_barang']);

		$nilai_barang = preg_replace('/[.,]/', '', $str);
		$nilai_barang = ($nilai_barang === '') ? 0 : $nilai_barang;
		
		$biaya_asuransi	= !empty($_POST['biaya_asuransi']) ? mysqli_real_escape_string($con, $_POST['biaya_asuransi']) : 0;
		//$catatan = mysqli_real_escape_string($con, $_POST['catatan']);
		$biaya_beban = mysqli_real_escape_string($con, $_POST['biaya_beban']);
		$delivery_name = mysqli_real_escape_string($con, $_POST['delivery_name']);
		$delivery_date_raw = mysqli_real_escape_string($con, $_POST['delivery_date']);
		$delivery_date2 = ($delivery_date_raw === '') ? null : $delivery_date_raw;
		$delivery_date = ($delivery_date2 === null) ? 'NULL' : "'$delivery_date2'";
		$delivery_by_raw = mysqli_real_escape_string($con, $_POST['delivery_by']);
		$delivery_by = ($delivery_by_raw === '') ? 0 : $delivery_by_raw;

		
		$nama_pembayaran = mysqli_real_escape_string($con, $_POST['nama_pembayaran']);
		$alamat_pembayaran = mysqli_real_escape_string($con, $_POST['alamat_pembayaran']);
		$cp_pembayaran = mysqli_real_escape_string($con, $_POST['cp_pembayaran']);
		$telp_pembayaran = mysqli_real_escape_string($con, $_POST['telp_pembayaran']);

		if($u['level'] == 'inbound' || $u['level'] == 'network' || $u['level'] == 'domex' || $u['level'] == 'intercity' || $u['level'] == 'operator' || $u['level'] == 'admin_network'){
			$updated_by_operational = mysqli_real_escape_string($con, $_POST['updated_by_operational'])+1;	
		}
		elseif($u['level'] == 'finance'){
			$updated_by_finance = mysqli_real_escape_string($con, $_POST['updated_by_finance'])+1;	
		}
		elseif($u['level'] == 'admin'){
			$updated_by_operational = mysqli_real_escape_string($con, $_POST['updated_by_operational']);	
			$updated_by_finance = mysqli_real_escape_string($con, $_POST['updated_by_finance']);	
		}

		//echo $updated_by_operational.$u['level'];
		//return false;

		//$terlambat_pickup = mysqli_real_escape_string($con, $_POST['terlambat_pickup']);
		
		
		$terlambat_pickup = escInt($con, $_POST['terlambat_pickup']);
		
		
		$terlambat_delivery = isset($_POST['terlambat_delivery']) ? mysqli_real_escape_string($con, $_POST['terlambat_delivery']) : '';
		$terlambat_delivery = ($terlambat_delivery === '') ? 0 : $terlambat_delivery;
		

		$pickup_date_request = mysqli_real_escape_string($con, $_POST['pickup_date_request']);
		
		
		$manifest_received_raw = mysqli_real_escape_string($con, $_POST['manifest_received']);
		$manifest_received2 = ($manifest_received_raw === '') ? null : $manifest_received_raw;
		$manifest_received = ($manifest_received2 === null) ? 'NULL' : "'$manifest_received2'";
		
		$pembayaran_khusus = mysqli_real_escape_string($con, $_POST['pembayaran_khusus']);




		if($terlambat_pickup > 1){
			$diskon_pickup = '20';
		}else{
			$diskon_pickup = '0';
		}

		if($terlambat_delivery > 1){
			$diskon_delivery = '20';
		}else{
			$diskon_delivery = '0';
		}
		
		
		
		
		//$filex = mysqli_real_escape_string($con, $_POST['datafile']);
		/*$filex	= !empty($_POST['datafile']) ? mysqli_real_escape_string($con, $_POST['datafile'] : '';

		$acak = rand(1,99);
		$file = strtolower($acak.$filex);
		$today = date("Ymd");
		
		if (!file_exists('../../upload/'.$today.'/')) {
			mkdir('../../upload/'.$today.'/', 0777, true);
		}*/
		
		$key = "asdf$1011";
		$encrypt = "$_SESSION[id_user]";
		$userid = base64_decrypt($encrypt,$key);
		
		if($service=='6'){
			$tujuan = $negara_tujuan;
		}else{
			$tujuan = $kota;
		}

		$duapuluhft = mysqli_real_escape_string($con, $_POST['20ft']);
		$empatpuluhft = mysqli_real_escape_string($con, $_POST['40ft']);
		//$empatpuluhfthc = mysqli_real_escape_string($con, $_POST['40ft_hc']);
		$lcl = mysqli_real_escape_string($con, $_POST['lcl']);
		$lcl = ($lcl === '') ? 0 : $lcl;
		
		$q = mysqli_query($con, "select * from detail_collie where id_pengiriman='".$id_pengiriman."' order by koli_ke asc ");
		$ketemu = mysqli_num_rows($q);
		if($ketemu > 0){

			$dcollie = "delete FROM detail_collie 
													
													where 	id_pengiriman= '".$id_pengiriman."'
														and koli_ke > '".$jumlah_collie."'
							
															";		

															//echo $qcollie;			
														
						$dc = mysqli_query($con,$dcollie);

			for($i=1; $i<=$jumlah_collie;$i++){
				$c_weight 	= ($_POST['cweight'][$i] > 0 ? $_POST['cweight'][$i] : 1);
				$c_width 	= ($_POST['cwidth'][$i] > 0 ? $_POST['cwidth'][$i] : 1 );
				$c_height 	= ($_POST['cheight'][$i] > 0 ? $_POST['cheight'][$i] : 1);
				$c_length 	= ($_POST['clength'][$i] > 0 ? $_POST['clength'][$i] : 1);
				$c_total 	= ($_POST['ctotal'][$i] > 0 ? $_POST['ctotal'][$i] : 1);
				$cttn 		= $_POST['catatanbaru'][$i];
			
			
				$qcek = mysqli_query($con, "select * from detail_collie where id_pengiriman='".$_POST['id']."' and koli_ke = '".$i."' ");

				$ada = mysqli_num_rows($qcek);

				if($ada > 0){

				

					$qcollie = "update  detail_collie SET		
																catatan ='$cttn',
																berat ='$c_weight',
																p ='$c_width',
																l = '$c_length',
																t = '$c_height',
																berat_real = '$c_total'
														
														where 	id_pengiriman= '".$id_pengiriman."'
															and koli_ke = '".$i."'
								
																";		

																//echo $qcollie;			
															
							$qc = mysqli_query($con,$qcollie);
				}else{
					$qcollie = "insert into detail_collie (id,
																id_pengiriman,
																koli_ke,
																catatan,
																berat,
																p,
																l,
																t,
																berat_real
																																								
																)
														values ('',
																'$id_pengiriman',
																$i,
																'$cttn',
																'$c_weight',
																'$c_width',
																'$c_length',
																'$c_height',
																'$c_total'
																
																)";		

																echo $qcollie;			
															
							$qc = mysqli_query($con,$qcollie);
				}
			}

		}

		if ($jumlah_detailbarang > 0){

			$d_detail_barang = "delete FROM detail_catatan_barang 
													
													where 	id_pengiriman= '$id_pengiriman'
														
							
															";		

															//echo $qcollie;			
														
						$ddb = mysqli_query($con,$d_detail_barang);
			// habis delete di insert lagi

			for($i=1; $i<=$jumlah_detailbarang;$i++){
				
				$d_koli = $_POST['d_koli'][$i];
				$d_barang = $_POST['d_barang'][$i];
			
					$q_detail_barang = "insert into detail_catatan_barang (id,
																id_pengiriman,
																koli_ke,														
																detail_catatan
																
																																								
																)
														values ('',
																'$id_pengiriman',
																$d_koli,
																'$d_barang'
																															
																)";		

							$qdb = mysqli_query($con,$q_detail_barang);
				
			}


		}else{
			$d_detail_barang = "delete FROM detail_catatan_barang 
													
													where 	id_pengiriman= '$id_pengiriman'
														
							
															";
			$ddb = mysqli_query($con,$d_detail_barang);
		}



		
		mysqli_query($con, "update pengiriman set 
													nama_pengirim='$nama_pengirim',
													pid='$pid',
													alamat_pengirim='$alamat_pengirim1',
													alamat_pengirim2='$alamat_pengirim2',
													delivery_name='$delivery_name',
													courier_date=$delivery_date,
													delivery_date=$delivery_date,
													courier_by = '$delivery_by',
													delivery_by = '$delivery_by',


													hp_pengirim='$hp_pengirim',
													perusahaan_pengirim='$perusahaan_pengirim',
													
													id_provinsi_asal = '$provinsiasal',
													provinsi_asal = '$provinsiasal_text',
													id_kota_asal = '$kotaasal',
													kota_asal = '$kotaasal_text',
													id_kecamatan_asal = '$kecamatanasal',
													kecamatan_asal = '$kecamatanasal_text',
													kelurahan_asal = '$kelurahanasal',

													id_kustomer='$id_kustomer',
													nama_penerima='$nama_penerima',
													alamat_penerima='$alamat_penerima1',
													alamat_penerima2='$alamat_penerima2',
													hp_penerima='$hp_penerima',
													perusahaan_penerima='$perusahaan_penerima',

													id_provinsi_tujuan = '$provinsi',
													provinsi_tujuan = '$provinsitujuan_text',
													id_kota_tujuan = '$kota',
													kota_tujuan = '$kota_text',
													id_kecamatan_tujuan = '$kecamatan',
													kecamatan_tujuan = '$kecamatan_text',
													kelurahan_tujuan = '$kelurahan',
													
													loc_code='$loc_code',
													service='$service',
													komoditi='$komoditi',
													jenis_barang='$jenis_barang',
													berat_aktual='$berat_barang',
													ukuran_barang='$ukuran_barang',
													collie='$jumlah_collie',
													packing_kayu='$packing_kayu',
													asuransi='$asuransi',
													surat_jalan='$surat_jalan',
													nilai_barang='$nilai_barang',
													biaya_asuransi='$biaya_asuransi',
													catatan1 = '',
													
													jumlah_detail_barang = $jumlah_detailbarang,

													biaya_beban='$biaya_beban',
													updated_last='$tgl_jam',
													updated_by='$userid',
													pickup_date_request='$pickup_date_request',
													updated_by_operational= '$updated_by_operational',
													updated_by_finance= '$updated_by_finance',
													nama_pembayaran='$nama_pembayaran',
													alamat_pembayaran='$alamat_pembayaran',
													cp_pembayaran='$cp_pembayaran',
													telp_pembayaran='$telp_pembayaran',
													
													lcl='$lcl',
													terlambat_pickup='$terlambat_pickup',
													diskon_pickup='$diskon_pickup',
													terlambat_delivery='$terlambat_delivery',
													diskon_deliv='$diskon_delivery',
													manifest_received=$manifest_received,
													pembayaran_khusus='$pembayaran_khusus'


							WHERE id_pengiriman = '$id_pengiriman'");

		if($packing_sebelum == 1){
			$detail_packing_sebelum = "packing pemilik";
		}elseif($packing_sebelum == 2){
			$detail_packing_sebelum = "packing kayu oleh cesco";
		}elseif($packing_sebelum == 3){
			$detail_packing_sebelum = "non packing kayu oleh cesco";
		}

		if($packing_kayu == 1){
			$detail_packing_sesudah = "packing pemilik";
		}elseif($packing_kayu == 2){
			$detail_packing_sesudah = "packing kayu oleh cesco";
		}elseif($packing_kayu == 3){
			$detail_packing_sesudah = "non packing kayu oleh cesco";
		}

		$qlog = "insert into update_pengiriman (id,
															id_pengiriman,
															updated_by,
															detail,
															packing															
															)
													values (NULL,
															'$id_pengiriman',
															'$u[id_user]',
															'$u[namalengkap] mengupdate awb dengan id $id_pengiriman',
															'sebelum =  $detail_packing_sebelum dan sesudah = $detail_packing_sesudah'
															)";					
														
						$log = mysqli_query($con,$qlog);

		

		if (!empty($_FILES['uploadcatatan'])) {

			if(is_uploaded_file($_FILES['uploadcatatan']['tmp_name'])){	
				
				include "../../config/excel_reader2.php";

				$data = new Spreadsheet_Excel_Reader($_FILES['uploadcatatan']['tmp_name']);
				
				// membaca jumlah baris dari data excel
				$baris = $data->rowcount($sheet_index=0);
				
				// nilai awal counter untuk jumlah data yang sukses dan yang gagal diimport
				$sukses = 0;
				$gagal = 0;
				//echo 'test';
				
				// import data excel mulai baris ke-2 (karena baris pertama adalah nama kolom)
				for ($i=2; $i<=$baris; $i++){
					$product			 = $data->val($i, 1);
					$aprc				 = $data->val($i, 2);
					$part_n				 = $data->val($i, 3);
					$part_n_desc		 = $data->val($i, 4);
					$rma_no				 = $data->val($i, 5); 
					$qty				 = $data->val($i,6);
					$remark				 = $data->val($i,7);
					
					
					
						if(is_numeric($qty)){
							$qty = $qty;
						}else{
							$qty = 1;
						}

						
				
						
						
							$query = "insert into asus_detail_catatan (id_pengiriman,
																product,
																aprc,
																part_n,
																part_n_desc,
																rma_no,
																qty,
																remark																								
																)
														values ('$_POST[id]',
																'$product',
																'$aprc',
																'$part_n',
																'$part_n_desc',
																'$rma_no',
																'$qty',
																'$remark'
																)";					
															
							$hasil = mysqli_query($con,$query);
						
							
					if ($hasil) $sukses++;
					else $gagal++;
				}
			 'sukses = '.$sukses. '<br>';
				'gagal = '.$gagal. '<br>';

			}
		}
		//echo $surat_jalan;
		header('location:../../main.php?module='.$module);
		 echo "<script>window.location.href='../../main.php?module=airwaybill';</script>";
		
	// cancel form
	}elseif($module=='airwaybill' and $act=='cancel'){
		$key = "asdf$1011";
		$encrypt = "$_POST[id]";
		$id = base64_decrypt($encrypt,$key);
		echo'
		<form action="'.$aksi.'?module=airwaybill&act=cancelupdate" name="modal_popup" method="POST">
			<td class="form-group" style="padding-bottom: 5px;">
                <label>Alasan Dibatalkan</label>
				<input type="hidden" name="id" class="form-control" value="'.$id.'" />
     			<textarea name="cancel_reason" class="form-control" rows="3"/></textarea>
			</td>
			<td class="modal-footer">
				<button class="btn btn-success" type="submit">Confirm</button>
				<button type="reset" class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Cancel</button>
			</td>
		</form>';

	// delete
	}

	elseif($module=='airwaybill' and $act=='status'){
		//$key = "asdf$1011";
		//$encrypt = "$_POST[id]";
		$id = $_POST['id'];

		//mysqli_query($con, "update pengiriman set status='7', cancel_reason='$cancel_reason' where id_pengiriman='$id'");

		$querypd = mysqli_query($con, "select x_user(pickup_by) as pickup_by,a.created_date,airwaybill_kode,a.status,
                 		case when a.user_client IS NOT NULL THEN (select fullname FROM user_client WHERE id_user=a.user_client) ELSE (SELECT namalengkap FROM user WHERE id_user=a.created_by) END AS booking_by, delivery_name, 
                 			ifnull(pickup_date_request,pickup_date) as pickup_date_request,
                 			x_user(pickup_request) as pickup_request_by,
                 		a.manifest_received,c.manifest_to, x_user(c.manifest_outbound_by) outbound_by, alasan_terlambat,alasan_terlambat_pickup,alasan_terlambat_delivery,b.alasan_terlambat_inbound,
                 			cancel_reason,
                 		 pickup_date, courier_date,delivery_date,x_user(courier_by) as courier_by, a.status, a.transit 
                 		 , x_inter(a.id_kota_tujuan) as intercitytujuan,x_hubnew(a.id_kota_tujuan) as hub,id_kota_tujuan

                 		 from pengiriman a 
                 			left join manifest_detail b on b.id_pengiriman = a.id_pengiriman
                 			left join manifest c on c.id_manifest = b.id_manifest
                 		where a.id_pengiriman='$id' order by b.id_manifest_detail limit 1");
                 	$data_pd = mysqli_fetch_assoc($querypd);

		echo'
				
			<div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">DETAIL AIRWAYBILL '.$data_pd['airwaybill_kode'].'</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="table-responsive">
                <table class="table no-margin" style="text-transform: uppercase;">
                  <thead>
                  <tr>
                    
                    <th>TANGGAL</th>
                    <th>Status</th>
                    <th>BY</th>
                    
                  </tr>
                  </thead>
                  <tbody>
                  ';
                 	
                 	

                 	$pickup_date_request = strtotime($data_pd['pickup_date_request']);


					   $qwhilepd = "select x_user(pickup_by) as pickup_by,c.manifest_to, pickup_date, courier_date,x_user(courier_by) as courier_by, a.status, a.transit from pengiriman a 
                 			left join manifest_detail b on b.id_pengiriman = a.id_pengiriman
                 			left join manifest c on c.id_manifest = b.id_manifest
                 		where a.id_pengiriman='$id' order by b.id_manifest_detail";                
                  echo 

                  '

                  <tr>
                    
                    <td>'.$data_pd['created_date'] .'</td>
                    <td><span class="label label-success">Booking</span></td>
                    <td>'.$data_pd['booking_by'] .'</td>
                  </tr>    

                  <tr>
                    
                    <td>';
                    		$pickup_date_raw = isset($data_pd['pickup_date']) ? $data_pd['pickup_date'] : null;
							$pickup_date_req_raw = isset($data_pd['pickup_date_request']) ? $data_pd['pickup_date_request'] : null;

							// Pastikan semua dalam bentuk timestamp
							$pickup_date = is_numeric($pickup_date_raw) ? (int)$pickup_date_raw : strtotime($pickup_date_raw);
							$pickup_date_req = is_numeric($pickup_date_req_raw) ? (int)$pickup_date_req_raw : strtotime($pickup_date_req_raw);

							if ($data_pd['status'] >= 2) {
							    if ($pickup_date_req > $pickup_date) {
							        echo date('Y-m-d', $pickup_date); // Tampilkan pickup_date
							    } else {
							        echo date('Y-m-d', $pickup_date_req); // Tampilkan pickup_date_request
							    }
							} else {
							    echo ($pickup_date_req ? date('Y-m-d', $pickup_date_req) : '-');
							}
							//var_dump($data_pd['pickup_date'], $pickup_date_request);
							//var_dump(strtotime($data_pd['pickup_date']), strtotime($pickup_date_request));
	                   echo

                     '</td>
                    <td><span class="label label-success">Proses Pick Up</span></td>
                    <td>'.$data_pd['pickup_request_by'] .'</td>
                  </tr>                  

                  <tr>
                    <td>'.$data_pd['pickup_date'] .'</td></span></td>
                    <td><span class="label label-success">Pickup</span></td>
                    <td>'.$data_pd['pickup_by'] .' </td>
                  </tr>   
                  <tr>
                    
                    <td colspan="3">Alasan Terlambat Pickup : '.$data_pd['alasan_terlambat_pickup'].'</td>
                    
                  </tr>               

                  <tr>
                    
                    <td colspan="3">Alasan Terlambat Outbound: '.$data_pd['alasan_terlambat'].'</td>
                    
                  </tr>
                  
                  ';
                  	
                  	$querywhile = mysqli_query($con, "select x_user(pickup_by) as pickup_by,x_namacabang(c.manifest_to) as manifest_to,c.tanggal as manifest_tanggal,x_user(c.manifest_outbound_by) outbound_by, pickup_date, courier_date,x_user(courier_by) as courier_by, a.status, a.transit from pengiriman a 
                 			inner join manifest_detail b on b.id_pengiriman = a.id_pengiriman
                 			inner join manifest c on c.id_manifest = b.id_manifest
                 		where a.id_pengiriman='$id' and c.manifest_to is not null order by b.id_manifest_detail");

                  	 "select x_user(pickup_by) as pickup_by,x_namacabang(c.manifest_to) as manifest_to,c.tanggal as manifest_tanggal, pickup_date, courier_date,x_user(courier_by) as courier_by, a.status, a.transit from pengiriman a 
                 			left join manifest_detail b on b.id_pengiriman = a.id_pengiriman
                 			left join manifest c on c.id_manifest = b.id_manifest
                 		where a.id_pengiriman='$id' order by b.id_manifest_detail";

                  	while ($data=mysqli_fetch_assoc($querywhile)){
                  			echo '
                  			<tr>
                    
			                    <td>'.$data['manifest_tanggal'].'</td>
			                    <td><span class="label label-danger">Dalam Proses Pengiriman ke Kota Tujuan '.$data['manifest_to'].'</span> </td>
			                    <td>'.$data['outbound_by'].'</td>
			                </tr>';
                  	}
                  echo '

                  <tr>
                    
                    <td colspan="3">Alasan Terlambat Inbound: '.$data_pd['alasan_terlambat_inbound'].'</td>
                    
                  </tr>
                  
                  <tr>
                    
                    <td>'.($data_pd['manifest_received'] != '0000-00-00' ? $data_pd['manifest_received'] : '-').'</td>
                    <td><span class="label label-success">DALAM PROSES KOTA PENERIMA</span></td>
                    <td>-</td>
                  </tr>

                  <tr>
                    
                    <td>'.($data_pd['courier_date'] != '0000-00-00' ? $data_pd['courier_date'] : '-').'</td>
                    <td><span class="label label-success">Delivered | oleh : '.$data_pd['delivery_name'].'</span></td>
                    <td>'.$data_pd['courier_by'].'</td>
                  </tr>
                  
                  <tr>
                    
                    <td colspan="3">Alasan Terlambat Delivery: '.$data_pd['alasan_terlambat_delivery'].'</td>
                    
                  </tr>

                  ';

                  if($data_pd['status'] == 7){
                  	echo 
                  	' <tr>
                    
                    	<td colspan="3">Alasan Cancel : '.$data_pd['cancel_reason'].'</td>
                    
                  	  </tr>';
                  }
                  
                  
                  echo '
                  
                 
                  
                  
                  </tbody>
                </table>
            </div>
              <!-- /.table-responsive -->
                ';
                	if($data_pd['status'] > 1){
                		

                  			echo '<h4>Foto Barang Pickup</h4>';
                  			echo '<img src="../e-cesco/reportsnew/pickup_photo/'.$data_pd['airwaybill_kode'].'.jpg" style="display: block;margin-left: auto;margin-right: auto; width: 100%; margin-bottom:100px;" id="image1">
                  			';
                  			
                  		//}
                  		echo '
						
                  	';
                  }

                	if($data_pd['status'] > 5){
                		//$headers = get_headers('http://103.110.8.250/mobileapps/uploads/'.$data_pd['airwaybill_kode'].'.jpg', 1);

                  	/*	if (strpos($headers['Content-Type'], 'image/') !== false) {
                  			echo '<img src="http://103.110.8.250/mobileapps/uploads/'.$data_pd['airwaybill_kode'].'.jpg" style="display: block;margin-left: auto;margin-right: auto; width: 100%; margin-bottom:100px;" id="image1">';

                  			echo '<a target="_blank" href="http://103.110.8.250/mobileapps/uploads/'.$data_pd['airwaybill_kode'].'.jpg">lihat foto <i class="fa fa-eye"></i></a>';
                  		}else{*/
                  			echo '<h4>Foto Penerima</h4>';
                  			echo '<img src="../e-cesco/reportsnew/delivery_photo/'.$data_pd['airwaybill_kode'].'.jpg" style="display: block;margin-left: auto;margin-right: auto; width: 100%; margin-bottom:100px;" id="image1">.<br>
                  			';
                  			echo '<h4>Foto AWB</h4>';
                  			echo '<img src="../e-cesco/reportsnew/awbdelivery_photo/'.$data_pd['airwaybill_kode'].'.jpg" style="display: block;margin-left: auto;margin-right: auto; width: 100%; margin-bottom:100px;" id="image1">
                  			';

                  			if($data_pd['intercitytujuan'] != 'Y' and $data_pd['hub'] != $data_pd['id_kota_tujuan']){
								$disable_admin = ($data_pd['status'] == 6 && ($u['level'] == 'admin' || $u['level'] == 'network' || $u['level'] == 'admin_network') ? '' : 'disabled');
							}else{
								$disable_admin = ($data_pd['status'] == 6 && ($u['level'] == 'admin') ? '' : 'disabled');
							}

	                  			echo '
	                  			<form action="'.$aksi.'?module=airwaybill&act=updatefoto" name="update-foto" method="POST" enctype="multipart/form-data">
									<input type="hidden" name="id" class="form-control" value="'.$id.'" />
		                  			<div class="form-group">
							        	<label>Ganti Foto Penerima* (tidak boleh lebih dari 1 MB)</label>
							        	<input type="file" name="gambar" required >
							        </div>
							        <button class="btn btn-success" type="submit"  >Ganti Foto</button>
	                  			</form>

	                  		';
	                  		
	                  		
	                  		if ($u['level'] == 'admin'){
	                  		echo '	
	                  			<a href="'.$aksi.'?module=airwaybill&act=backtodeliv&id_pengiriman='.$id.'"><button class="btn btn-xs btn-warning">back to deliv<i class="fa fa-pencil"></i></button> </a>


	                  			<a href="'.$aksi.'?module=airwaybill&act=return-to-delivered&id_pengiriman='.$id.'"><button class="btn btn-xs btn-warning">return to devired<i class="fa fa-pencil"></i></button> </a>

	                  			<br>

	                  			'; 


                  			}
                  		//}
                  		echo '

                  		<button class="btn btn-primary btn-sm putar"  data-tipe="kiri">Rotate Left <i class="fa fa-rotate-left"></i></button>
						<button class="btn btn-primary btn-sm putar" data-tipe="kanan">Rotate Right <i class="fa fa-rotate-right"></i></button>
						
                  	';
                  }
                '
              
            </div>
            <!-- /.box-body -->
         
            <div class="box-footer clearfix">
              
            </div>
            <!-- /.box-footer -->
          </div>
          <!-- /.box -->
        </div>
			
			';
		

	// delete
	}

	elseif($module=='airwaybill' and $act=='cancelupdate'){
		$id = mysqli_real_escape_string($con,$_POST['id']);
		$cancel_reason = mysqli_real_escape_string($con,$_POST['cancel_reason']).' & dicancel oleh '.$u['namalengkap'];


		mysqli_query($con, "update pengiriman set status='7', cancel_reason='$cancel_reason' where id_pengiriman='$id'");
		header('location:../../main.php?module='.$module);
	
	// import penerima
	}elseif($module=='airwaybill' and $act=='importpenerima'){
		echo'
		<form action="'.$aksi.'?module=airwaybill&act=importpenerima_action" name="modal_popup" method="POST" enctype="multipart/form-data">
			<td class="form-group" style="padding-bottom: 5px;">
                <input type="file" name="importfile">
				<p class="help-block">Hanya bisa file ber-type .xls</p>
			</td>
			<td class="modal-footer">
				<button class="btn btn-success" type="submit">Confirm</button>
				<button type="reset" class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Cancel</button>
			</td>
		</form>';
		
	// import penerima action
	}elseif($module=='airwaybill' and $act=='importpenerima_action'){
		include "../../config/excel_reader2.php";
		// membaca file excel yang diupload
		$data = new Spreadsheet_Excel_Reader($_FILES['importfile']['tmp_name']);
		
		// membaca jumlah baris dari data excel
		$baris = $data->rowcount($sheet_index=0);
		
		// nilai awal counter untuk jumlah data yang sukses dan yang gagal diimport
		$sukses = 0;
		$gagal = 0;
		// import data excel mulai baris ke-2 (karena baris pertama adalah nama kolom)
		for ($i=2; $i<=$baris; $i++){
			$airwaybill	= $data->val($i, 1);
			$penerima	= $data->val($i, 2);
			$tanggal	= $data->val($i, 3);
			
			//airwaybill
			$query1	= mysqli_query($con, "SELECT airwaybill_kode FROM pengiriman WHERE airwaybill_kode = '$airwaybill'");
			$ketemu = mysqli_num_rows($query1);
			$rairwaybill = mysqli_fetch_assoc($query1);
			
			if($ketemu > 0){
				$query = "update pengiriman set delivery_name='$penerima', delivery_date='$tanggal',courier_date='$tanggal', status='6', delivery='Y' where airwaybill_kode='$airwaybill'";
				$hasil = mysqli_query($con,$query);
				
			}
			if ($hasil) $sukses++;
			else $gagal++;
		}
		
		header('location:../../main.php?module='.$module);
	}
	elseif($module=='airwaybill' and $act=='searchawb'){
		$key = "asdf$1011";
		$q=mysqli_real_escape_string($con,$_POST['q']);

		$query=mysqli_query($con,"select airwaybill_kode, 
		created_date as tanggal, 
		x_namakustomer(id_kustomer) as id_kustomer,
		x_namakotabesar(lokasi_asal) as lokasi_asal,
		case when service='6' then x_namanegara(lokasi_tujuan) else x_namakotabesar(lokasi_tujuan) end as lokasi_tujuan,
		komoditi,
		status,
		inbound,
		pickup_date,
		x_user(pickup_by) as pickup_by,
		x_user(courier_by) as courier_by,		
		delivery_date,
		x_user(delivery_by) as delivery_by,
		SUBSTRING_INDEX(ukuran_barang,'x',1) as length, 
		SUBSTRING_INDEX(SUBSTRING_INDEX(ukuran_barang,'x',2),'x',-1) as width, 
		SUBSTRING_INDEX(ukuran_barang,'x',-1) as height,
		if(berat_aktual is null or berat_aktual < 1,  berat_barang, berat_aktual) as berat_aktual, 
		case when status='8' then delivery_note else delivery_name end as delivery_name,
		concat(inbound,'-',id_pengiriman) as id,
		id_pengiriman from pengiriman where airwaybill_kode LIKE '%".$q."%'");
		$row=mysqli_num_rows($query);

		if ($row > 0){
			while ($data=mysqli_fetch_assoc($query)){
				$volume = ($data['length']*$data['width']*$data['height'])/6000;
				$d = $data['status']; 
				if($d=='1'){
						$status = '<button class="btn btn-block btn-warning btn-xs">Booking</button>';
					}elseif($d=='2'){
						$status = '<button class="btn btn-block btn-info btn-xs">Pickup</button>';
					}elseif($d=='3'){
						$status = '<button class="btn btn-block btn-primary btn-xs">On Outbound</button>';
					}elseif($d=='4'){
						$status = '<button class="btn btn-block btn-primary btn-xs">On Inbound</button>';
					}elseif($d=='5'){
						$status = '<button class="btn btn-block btn-primary btn-xs">On Delivery</button>';
					}elseif($d=='6'){
						$status = '<button class="btn btn-block btn-success btn-xs">Delivered</button>';
					}elseif($d=='7'){
						$status = '<button class="btn btn-block btn-danger btn-xs">Cancel</button>';
					}elseif($d=='8'){
						$status = '<button class="btn btn-block btn-danger btn-xs">Return</button>';
					}
					if($data['inbound']=='Y'){
						$tipe='Inbound';
					}else{
						$tipe='Outbound';
					}
				echo '
				<td>'.$data['airwaybill_kode'].'	</td>
				<td>'.$data['tanggal'].'	</td>							
				<td>'.$data['id_kustomer'].'	</td>
				<td>'.$data['lokasi_asal'].'	</td>
				<td>'.$data['lokasi_tujuan'].'	</td>
				<td>'.($data['komoditi'] == 1 ? 'package' : 'Dokumen').'	</td>
				<td>'.$status.'	</td>
				<td>'.$tipe.'	</td>
				<td>'.$data['pickup_date'].'	</td>
				<td>'.$data['pickup_by'].'	</td>
				<td>'.$data['delivery_date'].'	</td>
				<td>'.$data['delivery_by'].'	</td>
				<td>'.$data['delivery_name'].'	</td>
				<td>'.($row['width'] == '' ? 0 : $row['width']).'x'.($row['length'] == '' ? 0 : $row['length']).'x'.($row['height'] == '' ? 0 : $row['height']).'('. number_format($volume,2).' KG )</td>
				<td>'.$data['berat_aktual'].'	KG</td>
				<td>
								<button class="btn btn-xs btn-success" onClick="window.open(\'reportsnew/airwaybillnew.php?id='.base64_encrypt($data['id_pengiriman'],$key).'\',\'mywindow\',\'menubar=1,resizable=1,width=800,height=600\');"><i class="fa fa-print"></i></button>
								<button class="btn btn-xs btn-warning" onclick="window.location.href = \'?module=airwaybill&act=outbound&id='.base64_encrypt($data['id_pengiriman'],$key).'\';"><i class="fa fa-pencil"></i></button> 

								<button class="btn btn-xs btn-primary" onClick="window.open(\'reportsnew/delivery_photo/'.$data['airwaybill_kode'].'.jpg\',\'mywindow\',\'menubar=1,resizable=1,width=800,height=600\');"><i class="fa fa-image"></i></button>
								</center></td>



											
				'; 
			}
		}
		else{
			echo'
			<td colspan="9">
				<center><b>AIRWAYBILL NOT FOUND</b></center>
			</td>'; 
		}
	}
	elseif($module=='airwaybill' and $act=='upload-asus-all'){
		//$last_id_pengiriman = mysqli_insert_id($con);


		include "../../config/excel_reader2.php"; 

		$data = new Spreadsheet_Excel_Reader($_FILES['uploadcatatan']['tmp_name']);




		
		if(is_uploaded_file($_FILES['uploadcatatan']['tmp_name'])){	
			// membaca jumlah baris dari data excel
			$baris = $data->rowcount($sheet_index=0);
			
			// nilai awal counter untuk jumlah data yang sukses dan yang gagal diimport
			$sukses = 0;
			$gagal = 0;
			//echo 'test';
			
			// import data excel mulai baris ke-2 (karena baris pertama adalah nama kolom) 
			for ($i=2; $i<=$baris; $i++){
				$product			 = $data->val($i, 1);
				$aprc				 = $data->val($i, 2);
				$part_n				 = $data->val($i, 3);
				$part_n_desc		 = $data->val($i, 4);
				$rma_no				 = $data->val($i, 5); 
				$qty				 = $data->val($i,6);
				$remark				 = $data->val($i,7);
				$width				 = $data->val($i,8);
				$height				 = $data->val($i,9);
				$length				 = $data->val($i,10);
				$weight				 = $data->val($i,11);
				$awb				 = $data->val($i,12);					
				
				$part_desc = addslashes($part_n_desc);

				$getid_pengiriman	= mysqli_query($con, "SELECT id_pengiriman FROM pengiriman WHERE airwaybill_kode Like '%$awb%'");
				//$ketemu_id = mysqli_num_rows($getid_pengiriman);
				$r_id = mysqli_fetch_assoc($getid_pengiriman);	
				
				//echo $awb.'='.$r_id['id_pengiriman']. '<br>';
					if(is_numeric($qty)){
						$qty = $qty;
					}else{
						$qty = 1;
					}

					
			
					
					
					 	$query = "insert into asus_detail_catatan (id_pengiriman,
															product,
															aprc,
															part_n,
															part_n_desc,
															rma_no,
															qty,
															remark,
															width,
															height,
															length,
															weight																								
															)
													values ('$r_id[id_pengiriman]',
															'$product',
															'$aprc',
															'$part_n',
															'$part_desc',
															'$rma_no',
															'$qty',
															'$remark',
															'$width',
															'$height',
															'$length',
															'$weight'
															)";					
														
					$hasil = mysqli_query($con,$query);
					
						
				if ($hasil) $sukses++;
				else $gagal++;
			}
		 'sukses = '.$sukses. '<br>';
			'gagal = '.$gagal. '<br>';

		}
		header('location:../../main.php?module='.$module);

	}
	elseif($module=='airwaybill' and $act=='delete_catatan'){
		
		$id = "$_GET[id_catatan]";
		
		//mysqli_query($con, "delete from pengiriman where id_pengiriman='$id'");
		mysqli_query($con, "delete from asus_detail_catatan  where id_pengiriman='$id'");
		header('Location: ' . $_SERVER['HTTP_REFERER']);	
	}

	elseif($module=='airwaybill' and $act=='fixing_collie'){
		$key = "asdf$1011";
		$encrypt = "$_GET[id]";
		$id = base64_decrypt($encrypt,$key);

		echo $id;

		$query1	= mysqli_query($con, "SELECT airwaybill_kode FROM pengiriman WHERE id_pengiriman = '$id'");
			$ketemu = mysqli_num_rows($query1);
			$r = mysqli_fetch_assoc($query1);
			
			if($ketemu > 0){
				
				$ins = "insert into detail_collie (id,
																id_pengiriman,
																koli_ke,
																catatan,
																berat,
																p,
																l,
																t,
																berat_real
																																								
																)
														values ('',
																'$id',
																1,
																'fixing',
																1,
																1,
																1,
																1,
																1
																
																)";		

																$proses_ins = mysqli_query($con,$ins);
				
			}

		
		header('Location: ' . $_SERVER['HTTP_REFERER']);	
	}

	elseif($module=='airwaybill' and $act=='backtopickup'){
		$id_pengiriman =  $_GET['id_pengiriman'];
		
		
		echo "update pengiriman set status='2', manifest='N', transit='N', received_transit=null,delivery='N', delivery_name=null, courier_date=null, courier_by=null where id_pengiriman='$id_pengiriman'";

		echo "delete manifest_detail from manifest_detail a left join manifest b on a.id_maniest = b.id_manifest   where a.id_pengiriman=$id_pengiriman and b.tipe_manifest != 'pickup' ";

		mysqli_query($con, "update pengiriman set status='1', manifest='N', transit='N', pickup_date=null,pickup_by=null,pickup='N', received_transit=null,delivery='N', delivery_name=null, courier_date=null, courier_by=null where id_pengiriman='$id_pengiriman'");

		mysqli_query($con, "delete a from manifest_detail a left join manifest b on a.id_manifest = b.id_manifest   where a.id_pengiriman=$id_pengiriman and (b.tipe_manifest != 'pickup' or b.tipe_manifest is null ) ");

		header('location:../../main.php?module='.$module);
	}

	elseif($module=='airwaybill' and $act=='backtodeliv'){
		$id_pengiriman =  $_GET['id_pengiriman'];
		
		mysqli_query($con, "update pengiriman set status='5', delivery='N',delivery_name='',delivery_by='',delivery_date='' where id_pengiriman='$id_pengiriman'");


		echo "update pengiriman set status='5', delivery='N',delivery_name='',delivery_by='',delivery_date='' where id_pengiriman='$id_pengiriman'";

		
		header('location:../../main.php?module='.$module);
	}
	elseif($module=='airwaybill' and $act=='return-to-delivered'){
		$id_pengiriman =  $_GET['id_pengiriman'];
		
		mysqli_query($con, "update pengiriman set status='6' where id_pengiriman='$id_pengiriman'");


		//echo "update pengiriman set status='5', delivery='N',delivery_name='',delivery_by='',delivery_date='' where id_pengiriman='$id_pengiriman'";

		
		header('location:../../main.php?module='.$module);
	}

	elseif($module=='airwaybill' and $act=='update-kg'){
		include "../../config/excel_reader2.php";
		// membaca file excel yang diupload
		$data = new Spreadsheet_Excel_Reader($_FILES['uploadkg']['tmp_name']);
		
		// membaca jumlah baris dari data excel
		$baris = $data->rowcount($sheet_index=0);
		
		// nilai awal counter untuk jumlah data yang sukses dan yang gagal diimport
		$sukses = 0;
		$gagal = 0;
		// import data excel mulai baris ke-2 (karena baris pertama adalah nama kolom)
		for ($i=2; $i<=$baris; $i++){
			$airwaybill	= $data->val($i, 1);
			$kg	= $data->val($i, 2);
			
			
			//airwaybill
			$query1	= mysqli_query($con, "SELECT airwaybill_kode FROM pengiriman WHERE airwaybill_kode = '$airwaybill' and harga_kunci is null");
			$ketemu = mysqli_num_rows($query1);
			$rairwaybill = mysqli_fetch_assoc($query1);
			
			if($ketemu > 0){
				$query = "update pengiriman set berat_aktual=$kg where airwaybill_kode='$airwaybill'";
				$hasil = mysqli_query($con,$query);
				
			}
			if ($hasil) $sukses++;
			else $gagal++;
		}
		
		header('location:../../main.php?module='.$module);
	}
	elseif($module=='airwaybill' and $act=='update-service'){
		include "../../config/excel_reader2.php";
		// membaca file excel yang diupload
		$data = new Spreadsheet_Excel_Reader($_FILES['uploadservice']['tmp_name']);

		// membaca jumlah baris dari data excel
		$baris = $data->rowcount($sheet_index=0);

		// nilai awal counter untuk jumlah data yang sukses dan yang gagal diimport
		$sukses = 0;
		$gagal = 0;
		// import data excel mulai baris ke-2 (karena baris pertama adalah nama kolom)
		for ($i=2; $i<=$baris; $i++){
			$airwaybill	= $data->val($i, 1);
			$service	= $data->val($i, 2);


			//airwaybill
			$query1	= mysqli_query($con, "SELECT airwaybill_kode FROM pengiriman WHERE airwaybill_kode = '$airwaybill' and harga_kunci is null");
			$ketemu = mysqli_num_rows($query1);
			$rairwaybill = mysqli_fetch_assoc($query1);

			if($ketemu > 0){
				$query = "update pengiriman set service=$service where airwaybill_kode='$airwaybill'";
				$hasil = mysqli_query($con,$query);

			}
			if ($hasil) $sukses++;
			else $gagal++;
		}

		header('location:../../main.php?module='.$module);
	}
	elseif($module=='airwaybill' and $act=='simple'){

		
		$dcollie = "delete FROM detail_collie 
													
													where 	id_pengiriman= '$_GET[id]'
							
															";		

															//echo $qcollie;			
														
						$dc = mysqli_query($con,$dcollie);

						//header('location:../../main.php?module='.$module);
						header('Location: ' . $_SERVER['HTTP_REFERER']);

	}
	elseif($module=='airwaybill' and $act=='updatefoto'){
		if($_FILES['gambar']){

			$pengiriman 		= mysqli_query($con, "select airwaybill_kode
				 from pengiriman where id_pengiriman='$_POST[id]'
				
				");
			$p= mysqli_fetch_assoc($pengiriman);



			$ekstensi_diperbolehkan	= array('png','jpg','jpeg');
			$nama = $_FILES['gambar']['name'];
			$x = explode('.', $nama);
			$ekstensi = strtolower(end($x));
			$ukuran	= $_FILES['gambar']['size'];
			$file_tmp = $_FILES['gambar']['tmp_name'];	
 
			if(in_array($ekstensi, $ekstensi_diperbolehkan) === true){
				if($ukuran < 9044070){			
					if(move_uploaded_file($file_tmp, '../../reportsnew/delivery_photo/'.$p['airwaybill_kode'].'.jpg')){					
						echo "BERHASIL UPLOAD";
					}
					else{
						echo "gagal";
					}
				}else{
					echo 'UKURAN FILE TERLALU BESAR, max 1mb';
				}
			}else{
				echo 'EKSTENSI FILE YANG DI UPLOAD TIDAK DI PERBOLEHKAN';
			}
		}
		header('location:../../main.php?module='.$module);

	}

	elseif($module=='airwaybill' AND $act=='edit-remark'){
		$mysqli->query("update asus_detail_catatan set remark='$_POST[value]' where id_catatan='$_POST[pk]'");
	}



	elseif($module=='airwaybill' AND $act=='asus-edit-koli-ke'){
		$mysqli->query("update asus_detail_catatan set koli_ke='$_POST[value]' where id_catatan='$_POST[pk]'");
	}

	

	elseif($module=='airwaybill' and $act=='edit-pickupdate'){
		$id = $_POST['id'];
		//echo $id;

		$query = mysqli_query($con, "select pickup_date from pengiriman where id_pengiriman='$id'");
        $d = mysqli_fetch_assoc($query);

        //echo $d['pickup_date'];

        echo'
		<form action="'.$aksi.'?module=airwaybill&act=update-pickupdate"  method="POST">
			<td class="form-group" style="padding-bottom: 5px;">
                <label>Rubah tanggal pickup</label>
				<input type="hidden" name="id" class="form-control" value="'.$id.'" />
     			<input type="text" name="tanggal" value="'.$d['pickup_date'].'" class="form-control" />
			</td>
			<td class="modal-footer">
				<button class="btn btn-success" type="submit">Confirm</button>
				<button type="reset" class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Cancel</button>
			</td>
		</form>';

	}
	elseif($module=='airwaybill' AND $act=='update-pickupdate'){
		$id = $_POST['id'];
		$tanggal = $_POST['tanggal'];
		//echo "update pengiriman set pickup_date='$tanggal' where id_pengiriman='$id'";
		$mysqli->query("update pengiriman set pickup_date='$tanggal' where id_pengiriman='$id'");
		header('Location: ' . $_SERVER['HTTP_REFERER']);
	}
	
}
?>