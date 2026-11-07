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
		concat(a.status,'-',a.transit,'-','a.pickup_reqeust') as status,
		a.inbound,
		a.pickup_date,
		x_user(a.pickup_by) as pickup_by,
		x_user(a.courier_by) as courier_by,
		a.courier_date,
		x_user(a.delivery_by) as delivery_by,
		case when status='8' then delivery_note else delivery_name end as delivery_name,
		concat(a.inbound,'-',a.id_pengiriman) as id,
		id_pengiriman
		FROM pengiriman a where pickup_by = 87 or courier_by = 87
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
		concat(a.status,'-',a.transit,'-','a.pickup_reqeust') as status,
		a.inbound,
		a.pickup_date,
		x_user(a.pickup_by) as pickup_by,
		x_user(a.courier_by) as courier_by,		
		a.courier_date,
		x_user(a.delivery_by) as delivery_by,
		case when status='8' then delivery_note else delivery_name end as delivery_name,
		concat(a.inbound,'-',a.id_pengiriman) as id,
		id_pengiriman
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
		concat(a.status,'-',a.transit,'-','a.pickup_reqeust') as status,
		a.inbound,
		a.pickup_date,
		x_user(a.pickup_by) as pickup_by,
		x_user(a.courier_by) as courier_by,		
		a.courier_date,
		x_user(a.delivery_by) as delivery_by,
		case when status='8' then delivery_note else delivery_name end as delivery_name,
		concat(a.inbound,'-',a.id_pengiriman) as id,
		id_pengiriman
		FROM pengiriman a where tanggal > DATE_SUB(now(), INTERVAL 5 MONTH)
	) temp	
EOT;
		}
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
		concat(a.status,'-',a.transit,'-','a.pickup_reqeust') as status,
		a.inbound,
		a.pickup_date,
		x_user(a.pickup_by) as pickup_by,
		x_user(a.courier_by) as courier_by,		
		a.courier_date,
		x_user(a.delivery_by) as delivery_by,
		case when status='8' then delivery_note else delivery_name end as delivery_name,
		concat(a.inbound,'-',a.id_pengiriman) as id,
		id_pengiriman
		FROM pengiriman a where tanggal > DATE_SUB(now(), INTERVAL 5 MONTH)
	) temp	
EOT;
	}

		$primaryKey = 'id_pengiriman';

		if ($u['level'] == 'admin' || $u['id_user'] == '17'){
		$columns = array(
			array( 'db' => 'airwaybill_kode', 'dt' => 0 ),
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

					if($st=='1' && $pickup_req == ''){
						$status = '<button class="btn btn-block btn-warning btn-xs">Booking</button>';

					}if($st=='1' && $pickup_req != ''){
						$status = '<button class="btn btn-block btn-warning btn-xs">On pickup</button>';
					}
					elseif($st=='2' && $transit == 'Y'){
						$status = '<button class="btn btn-block btn-info btn-xs">On transit</button>';
					}
					elseif($st=='2' && $transit == 'N'){
						$status = '<button class="btn btn-block btn-info btn-xs">Pickup</button>';
					}
					elseif($st=='3'){
						$status = '<button class="btn btn-block btn-primary btn-xs">On Outbound</button>';
					}elseif($st=='4'){
						$status = '<button class="btn btn-block btn-primary btn-xs">On Inbound</button>';
					}elseif($st=='5'){
						$status = '<button class="btn btn-block btn-primary btn-xs">On Delivery</button>';
					}elseif($st=='6'){
						$status = '<button class="btn btn-block btn-success btn-xs">Delivered</button>';
					}elseif($st=='7'){
						$status = '<button class="btn btn-block btn-danger btn-xs">Cancel</button>';
					}elseif($st=='8'){
						$status = '<button class="btn btn-block btn-danger btn-xs">Return</button>';
					}
					return '<td><center>'.$status.'</center></td>';
				  }
			),
			array(
				'db'		=> 'inbound',
				'dt'		=> 7,
				'formatter'	=> function($d,$row){
					if($d=='Y'){
						$tipe='Inbound';
					}else{
						$tipe='Outbound';
					}
					return '<td><center>'.$tipe.$u['level'].'</center></td>';
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
			array('db' => 'courier_date',
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
					$id = implode('-', array_slice($pieces, 1));
					$key = "asdf$1011";
					if($inbound=='Y'){
						$awb='airwaybill_inbound';
						$booking='outbound';
					}else{
						$awb='airwaybill';
						$booking='outbound';
					}
					
					$keyu = 'asdf$1011';
					$encryptu = $_SESSION['id_user'];
						$idu = base64_decrypt($encryptu,$keyu);
						$useraktifu = mysqli_query($con, 'select * from user where id_user="'.$idu.'"');
						$uu = mysqli_fetch_assoc($useraktifu);
					

						return '<td><center>
						<button class="btn btn-xs btn-warning" onclick="window.location.href = \'?module=airwaybill&act='.$booking.'&id='.base64_encrypt($id,$key).'\';"><i class="fa fa-pencil"></i></button> 
						<button class="btn btn-xs btn-danger open_cancel" data-id="'.base64_encrypt($id,$key).'"><i class="fa fa-remove"></i></button> 
						
						<button class="btn btn-xs btn-success" onClick="window.open(\'reportsnew/airwaybill.php?id='.base64_encrypt($id,$key).'\',\'mywindow\',\'menubar=1,resizable=1,width=800,height=600\');"><i class="fa fa-print"></i> new</button>
						<button class="btn btn-xs btn-danger" onClick="window.open(\'client/reports/airwaybill_asus.php?id='.$id.'\',\'mywindow\',\'menubar=1,resizable=1,width=800,height=600\');"><i class="fa fa-print"></i>ASUS</button>

						<button class="btn btn-xs btn-danger" onclick="window.location.href = \'module/mod_airwaybill/airwaybill_action.php?module=airwaybill&act=backtopickup&id_pengiriman='.$id.'\';"><i class="fa fa-angle-left"></i>back to pickup</button>
						</center></td>';
						
					
				  }
			)

		);
		}
		else{
			$columns = array(
			array( 'db' => 'airwaybill_kode', 'dt' => 0 ),
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

					if($st=='1' && $pickup_req == ''){
						$status = '<button class="btn btn-block btn-warning btn-xs">Booking</button>';

					}if($st=='1' && $pickup_req != ''){
						$status = '<button class="btn btn-block btn-warning btn-xs">On pickup</button>';
					}
					elseif($st=='2' && $transit == 'Y'){
						$status = '<button class="btn btn-block btn-info btn-xs">On transit</button>';
					}
					elseif($st=='2' && $transit == 'N'){
						$status = '<button class="btn btn-block btn-info btn-xs">Pickup</button>';
					}
					elseif($st=='3'){
						$status = '<button class="btn btn-block btn-primary btn-xs">On Outbound</button>';
					}elseif($st=='4'){
						$status = '<button class="btn btn-block btn-primary btn-xs">On Inbound</button>';
					}elseif($st=='5'){
						$status = '<button class="btn btn-block btn-primary btn-xs">On Delivery</button>';
					}elseif($st=='6'){
						$status = '<button class="btn btn-block btn-success btn-xs">Delivered</button>';
					}elseif($st=='7'){
						$status = '<button class="btn btn-block btn-danger btn-xs">Cancel</button>';
					}elseif($st=='8'){
						$status = '<button class="btn btn-block btn-danger btn-xs">Return</button>';
					}
					return '<td><center>'.$status.'</center></td>';
				  }
			),
			array(
				'db'		=> 'inbound',
				'dt'		=> 7,
				'formatter'	=> function($d,$row){
					if($d=='Y'){
						$tipe='Inbound';
					}else{
						$tipe='Outbound';
					}
					return '<td><center>'.$tipe.$u['level'].'</center></td>';
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
			array('db' => 'courier_date',
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
					$id = implode('-', array_slice($pieces, 1));
					$key = "asdf$1011";
					if($inbound=='Y'){
						$awb='airwaybill_inbound';
						$booking='inbound';
					}else{
						$awb='airwaybill';
						$booking='outbound';
					}
					
					
					

						return '<td><center>
						<button class="btn btn-xs btn-warning" onclick="window.location.href = \'?module=airwaybill&act='.$booking.'&id='.base64_encrypt($id,$key).'\';"><i class="fa fa-pencil"></i></button> 						
						<button class="btn btn-xs btn-success" onClick="window.open(\'reports/'.$awb.'.php?id='.base64_encrypt($id,$key).'\',\'mywindow\',\'menubar=1,resizable=1,width=800,height=600\');"><i class="fa fa-print"></i></button>
						<button class="btn btn-xs btn-success" onClick="window.open(\'reportsnew/airwaybill.php?id='.base64_encrypt($id,$key).'\',\'mywindow\',\'menubar=1,resizable=1,width=800,height=600\');"><i class="fa fa-print"></i> new</button>
						<button class="btn btn-xs btn-danger" onClick="window.open(\'client/reports/airwaybill_asus.php?id='.$id.'\',\'mywindow\',\'menubar=1,resizable=1,width=800,height=600\');"><i class="fa fa-print"></i>ASUS</button>
						</center></td>';
					
					
				  }
			)

		);
		}


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
		$provinsi_text = mysqli_real_escape_string($con, $_POST['provinsi_text']);
		
		$kota = mysqli_real_escape_string($con, $_POST['kota']);
		$kota_text = mysqli_real_escape_string($con, $_POST['kota_text']);
		$kecamatan = mysqli_real_escape_string($con, $_POST['kecamatan']);
		$kecamatan_text = mysqli_real_escape_string($con, $_POST['kecamatan_text']);
		$kelurahan = mysqli_real_escape_string($con, $_POST['kelurahan']);
		
		

		$catatan1 = mysqli_real_escape_string($con, $_POST['catatan1']);
		$catatan2 = mysqli_real_escape_string($con, $_POST['catatan2']);
		$catatan3 = mysqli_real_escape_string($con, $_POST['catatan3']);
		$catatan4 = mysqli_real_escape_string($con, $_POST['catatan4']);
		$catatan5 = mysqli_real_escape_string($con, $_POST['catatan5']);
		$catatan6 = mysqli_real_escape_string($con, $_POST['catatan6']);
		$catatan7 = mysqli_real_escape_string($con, $_POST['catatan7']);
		$catatan8 = mysqli_real_escape_string($con, $_POST['catatan8']);
		$catatan9 = mysqli_real_escape_string($con, $_POST['catatan9']);
		$catatan10 = mysqli_real_escape_string($con, $_POST['catatan10']);

		$hp_pengirim = mysqli_real_escape_string($con, $_POST['hp_pengirim']);
		$perusahaan_pengirim = mysqli_real_escape_string($con, $_POST['perusahaan_pengirim']);
		$lokasi_asal = mysqli_real_escape_string($con, $_POST['lokasi_asal']);
		
		$id_kustomer = mysqli_real_escape_string($con, $_POST['id_kustomer']);
		$nama_penerima = mysqli_real_escape_string($con, $_POST['nama_penerima']);
		
		$alamat_penerima1 = mysqli_real_escape_string($con, $_POST['alamat_penerima1']);
		$alamat_penerima2 = mysqli_real_escape_string($con, $_POST['alamat_penerima2']);

		$hp_penerima = mysqli_real_escape_string($con, $_POST['hp_penerima']);
		$perusahaan_penerima = mysqli_real_escape_string($con, $_POST['perusahaan_penerima']);
		$loc_code = mysqli_real_escape_string($con, $_POST['loc_code']);
		$pid = mysqli_real_escape_string($con, $_POST['pid']);
		$lokasi_tujuan = mysqli_real_escape_string($con, $_POST['lokasi_tujuan']);		
		$id_cabang = mysqli_real_escape_string($con, $_POST['id_cabang']);
		$negara_tujuan = mysqli_real_escape_string($con, $_POST['negara_tujuan']);

		$service = mysqli_real_escape_string($con, $_POST['service']);
		$komoditi = mysqli_real_escape_string($con, $_POST['komoditi']);
		$jenis_barang = mysqli_real_escape_string($con, $_POST['jenis_barang']);
		$berat_barang = mysqli_real_escape_string($con, $_POST['berat_barang']);
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
		$nilai_barang = preg_replace('#(\d),(\d)#','$1$2',$str);
		$catatan = mysqli_real_escape_string($con, $_POST['catatan']);
		$biaya_beban = mysqli_real_escape_string($con, $_POST['biaya_beban']);
		$delivery_name = mysqli_real_escape_string($con, $_POST['delivery_name']);
		$delivery_date = mysqli_real_escape_string($con, $_POST['delivery_date']);
		
		$nama_pembayaran = mysqli_real_escape_string($con, $_POST['nama_pembayaran']);
		$alamat_pembayaran = mysqli_real_escape_string($con, $_POST['alamat_pembayaran']);
		$cp_pembayaran = mysqli_real_escape_string($con, $_POST['cp_pembayaran']);
		$telp_pembayaran = mysqli_real_escape_string($con, $_POST['telp_pembayaran']);
		
		$filex = mysqli_real_escape_string($con, $_POST['datafile']);
		$acak = rand(1,99);
		$file = strtolower($acak.$filex);
		$today = date("Ymd");
		
		if (!file_exists('../../upload/'.$today.'/')) {
			mkdir('../../upload/'.$today.'/', 0777, true);
		}
		
		$key = "asdf$1011";
		$encrypt = "$_SESSION[id_user]";
		$userid = base64_decrypt($encrypt,$key);
		
		if($service=='6'){
			$tujuan = $negara_tujuan;
		}else{
			$tujuan = $lokasi_tujuan;
		}

		$duapuluhft = mysqli_real_escape_string($con, $_POST['20ft']);
		$empatpuluhft = mysqli_real_escape_string($con, $_POST['40ft']);
		$empatpuluhfthc = mysqli_real_escape_string($con, $_POST['40ft_hc']);
		$lcl = mysqli_real_escape_string($con, $_POST['lcl']);
		
		mysqli_query($con, "update pengiriman set tanggal='$tanggal',
													nama_pengirim='$nama_pengirim',
													pid='$pid',
													alamat_pengirim='$alamat_pengirim1',
													alamat_pengirim2='$alamat_pengirim2',
													delivery_name='$delivery_name',
													courier_date='$delivery_date',


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
													provinsi_tujuan = '$provinsi_text',
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
													catatan1 = '$catatan1',
													catatan2 = '$catatan2',
													catatan3 = '$catatan3',
													catatan4 = '$catatan4',
													catatan5 = '$catatan5',
													catatan6 = '$catatan6',
													catatan7 = '$catatan7',
													catatan8 = '$catatan8',
													catatan9 = '$catatan9',
													catatan10 = '$catatan10',

													biaya_beban='$biaya_beban',
													updated_last='$tgl_jam',
													updated_by='$userid',
													nama_pembayaran='$nama_pembayaran',
													alamat_pembayaran='$alamat_pembayaran',
													cp_pembayaran='$cp_pembayaran',
													telp_pembayaran='$telp_pembayaran',
													20ft='$duapuluhft',
													40ft='$empatpuluhft',
													40ft_hc='$empatpuluhfthc',
													lcl='$lcl'
							WHERE id_pengiriman = '$_POST[id]'");

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
		//echo $surat_jalan;
		header('location:../../main.php?module='.$module);
		
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
	}elseif($module=='airwaybill' and $act=='cancelupdate'){
		$id = mysqli_real_escape_string($con,$_POST['id']);
		$cancel_reason = mysqli_real_escape_string($con,$_POST['cancel_reason']);
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

	elseif($module=='airwaybill' and $act=='backtopickup'){
		$id_pengiriman =  $_GET['id_pengiriman'];
		
		
		echo "update pengiriman set status='2', manifest='N', transit='N', received_transit=null,delivery='N', delivery_name=null, courier_date=null, courier_by=null where id_pengiriman='$id_pengiriman'";

		echo "delete manifest_detail from manifest_detail a left join manifest b on a.id_maniest = b.id_manifest   where a.id_pengiriman=$id_pengiriman and b.tipe_manifest != 'pickup' ";

		mysqli_query($con, "update pengiriman set status='2', manifest='N', transit='N', received_transit=null,delivery='N', delivery_name=null, courier_date=null, courier_by=null where id_pengiriman='$id_pengiriman'");

		mysqli_query($con, "delete a from manifest_detail a left join manifest b on a.id_manifest = b.id_manifest   where a.id_pengiriman=$id_pengiriman and (b.tipe_manifest != 'pickup' or b.tipe_manifest is null ) ");

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
			$query1	= mysqli_query($con, "SELECT airwaybill_kode FROM pengiriman WHERE airwaybill_kode = '$airwaybill'");
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
	
}
?>