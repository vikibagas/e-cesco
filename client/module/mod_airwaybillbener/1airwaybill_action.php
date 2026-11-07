<?php
if(!isset($_SESSION)){
    session_start();
}

if (empty($_SESSION['usernamex']) AND empty($_SESSION['passwordx'])){
	header('location:../main.php?module=home');
}
else{
	include "../../../config/connection.php";
	include "../../../config/library.php";
	include "../../../config/encrypt.php";
	
	$module	= !empty($_GET['module']) ? $_GET['module'] : '';
	$act	= !empty($_GET['act']) ? $_GET['act'] : '';
	
	$aksi="module/mod_airwaybill/airwaybill_action.php";
	
	$key = 'asdf$1011';
	$encrypt = $_SESSION['id_userx'];
	$id = base64_decrypt($encrypt,$key);
	$useraktif = mysqli_query($con, 'select * from user_client where id_user="'.$id.'"');
	$u = mysqli_fetch_assoc($useraktif);

	// data
	if($module=='airwaybill' and $act=='data'){
		
		$table = 
<<<EOT
	(
	SELECT
		a.airwaybill_kode, 
		a.created_date as tanggal, 
		x_namakustomer(a.id_kustomer) as id_kustomer,
		x_namakotabesar(a.lokasi_asal) as lokasi_asal,
		x_namakotabesar(a.lokasi_tujuan) as lokasi_tujuan,
		a.komoditi,
		a.status,
		a.pickup_date,
		x_user(a.pickup_by) as pickup_by,
		a.delivery_date,
		x_user(a.delivery_by) as delivery_by,
		case when status='8' then delivery_note else delivery_name end as delivery_name,
		concat(a.inbound,'-',a.id_pengiriman) as id,
		id_pengiriman
		FROM pengiriman a
	) temp
EOT;

		$primaryKey = 'id_pengiriman';

		$columns = array(
			array('db' => 'airwaybill_kode','dt' => 0),
			array('db' => 'tanggal',
					'dt'        => 1,
					'formatter' => function($d,$row){
						return date('d-m-Y H:i', strtotime($d));
					}
			),
			array('db' => 'id_kustomer','dt' => 2),
			array('db' => 'lokasi_asal',
					'dt'        => 3,
					'formatter' => function($d,$row){
					if($d=='Jakarta Selatan' || $d=='Jakarta Timur' || $d=='Jakarta Barat' ||	$d=='Jakarta Utara' || $d=='Jakarta Pusat'){
						$lokasi_asal = 'Jakarta';
					}else{
						$lokasi_asal = $d;
					}
					return '<td>'.strtoupper($lokasi_asal).'</td>';
				  }
			),
			array('db' => 'lokasi_tujuan',
					'dt'        => 4,
					'formatter' => function($d,$row){
					if($d=='Jakarta Selatan' || $d=='Jakarta Timur' || $d=='Jakarta Barat' ||	$d=='Jakarta Utara' || $d=='Jakarta Pusat'){
						$lokasi_tujuan = 'Jakarta';
					}else{
						$lokasi_tujuan = $d;
					}
					return '<td>'.strtoupper($lokasi_tujuan).'</td>';
				  }
			),
			array('db' => 'komoditi',
					'dt'        => 5,
					'formatter' => function($d,$row){
						if($d=='1'){
							$komoditi = '<button class="btn btn-block btn-xs"><i class="fa fa-cube"></i> Package</button>';
						}else{
							$komoditi = '<button class="btn btn-block btn-xs"><i class="fa fa-book"></i> Document</button>';
						}
						return '<td>'.$komoditi.'</td>';
					}
			),
			array('db' => 'status',
					'dt'        => 6,
					'formatter' => function($d,$row){
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
						}
						return '<td><center>'.$status.'</center></td>';
					}
			),
			array('db' => 'pickup_date',
					'dt'		=> 7,
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
					'dt'		=> 8,
					'formatter'	=> function($d,$row){
						return '<td>'.strtoupper($d).'</td>';
					}
			),
			array('db' => 'delivery_date',
					'dt'		=> 9,
					'formatter'	=> function($d,$row){
						if($d==''){
							$delivery_date = '';
						}else{
							$delivery_date = date( 'd-m-Y', strtotime($d));
						}
						return '<td><center>'.$delivery_date.'</center></td>';
					}
			),
			array('db' => 'delivery_by',
					'dt'		=> 10,
					'formatter'	=> function($d,$row){
						return '<td>'.strtoupper($d).'</td>';
					}
			),
			array('db' => 'delivery_name',
					'dt'		=> 11,
					'formatter'	=> function($d,$row){
						return '<td>'.strtoupper($d).'</td>';
					}
			),
			array(
				  'db'        => 'id',
				  'dt'        => 12,
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
					<button class="btn btn-xs btn-success" onClick="window.open(\'reports/'.$awb.'.php?id='.base64_encrypt($id,$key).'\',\'mywindow\',\'menubar=1,resizable=1,width=800,height=600\');"><i class="fa fa-print"></i></button></center></td>';
				  }
			)

		);

		$sql_details = array(
			'user' => 'cescolog_root',
			'pass' => 'asdf$1011',
			'db'   => 'cescolog_courier',
			'host' => '127.0.0.1'
		);
		require( '../../../config/ssp.class.php' );

		echo json_encode(
			SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns )
		);
	
	// cancel form
	}elseif($module=='airwaybill' and $act=='cancel'){
		$key = "asdf$1011";
		$encrypt = "$_POST[id]";
		$id = base64_decrypt($encrypt,$key);
		echo'
		<form action="'.$aksi.'?module=airwaybill&act=cancelupdate" name="modal_popup" method="POST">
			<div class="form-group" style="padding-bottom: 5px;">
                <label>Alasan Dibatalkan</label>
				<input type="hidden" name="id" class="form-control" value="'.$id.'" />
     			<textarea name="cancel_reason" class="form-control" rows="3"/></textarea>
			</div>
			<div class="modal-footer">
				<button class="btn btn-success" type="submit">Confirm</button>
				<button type="reset" class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Cancel</button>
			</div>
		</form>';

	// delete
	}elseif($module=='airwaybill' and $act=='cancelupdate'){
		$id = mysqli_real_escape_string($con,$_POST['id']);
		$cancel_reason = mysqli_real_escape_string($con,$_POST['cancel_reason']);
		mysqli_query($con, "update pengiriman set status='5', cancel_reason='$cancel_reason' where id_pengiriman='$id'");
		header('location:../../main.php?module='.$module);
	}

}
?>