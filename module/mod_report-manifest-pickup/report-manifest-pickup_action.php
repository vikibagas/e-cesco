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
	$aksi="module/mod_report-manifest-pickup/report-manifest-pickup_action.php";
	$key = 'asdf$1011';
	$encrypt = $_SESSION['id_user'];
	$id = base64_decrypt($encrypt,$key);
	$useraktif = mysqli_query($con, 'select * from user where id_user="'.$id.'"');
	$u = mysqli_fetch_assoc($useraktif);
	
	// data
	if($module=='pickup' and $act=='data'){
		if($u['level']=='admin' || $u['level']=='operator' || $u['level']=='finance'){
		$table = 
<<<EOT
	(
	select
		DATE_FORMAT(a.pickup_date,'%Y-%m-%d') as pickup,
		b.namalengkap,
		count(id_pengiriman) as jml,
		sum(a.berat_barang) as berat,
		sum(a.collie) as collie,
		concat(a.pickup_by,'-', DATE_FORMAT(a.pickup_date,'%Y-%m-%d')) as pickup_by,
		a.id_pengiriman
		from pengiriman a
	inner join user b on b.id_user=a.pickup_by where a.tanggal > DATE_SUB(now(), INTERVAL 2 MONTH) and  a.pickup='Y' and a.pickup_by is not null 

	and b.id_cabang in (11,16,39,41)
	group by DATE_FORMAT(a.pickup_date,'%y-%m-%d'), a.pickup_by
	) temp
EOT;
		}elseif($u['level']=='intercity'){
		$table = 
<<<EOT
	(
	select
		DATE_FORMAT(a.pickup_date,'%Y-%m-%d') as pickup,
		b.namalengkap,
		count(id_pengiriman) as jml,
		sum(a.berat_barang) as berat,
		sum(a.collie) as collie,
		concat(a.pickup_by,'-', DATE_FORMAT(a.pickup_date,'%Y-%m-%d')) as pickup_by,
		a.id_pengiriman
		from pengiriman a
	inner join user b on b.id_user=a.pickup_by where a.tanggal > DATE_SUB(now(), INTERVAL 2 MONTH) and  a.pickup='Y' and a.pickup_by is not null and x_inter(a.id_kota_asal)='Y'
	group by DATE_FORMAT(a.pickup_date,'%y-%m-%d'), a.pickup_by
	) temp
EOT;
		}else{
		$table = 

<<<EOT
	(
	select
		DATE_FORMAT(a.pickup_date,'%Y-%m-%d') as pickup,
		b.namalengkap,
		count(id_pengiriman) as jml,
		sum(a.berat_barang) as berat,
		sum(a.collie) as collie,
		concat(a.pickup_by,'-', DATE_FORMAT(a.pickup_date,'%Y-%m-%d')) as pickup_by,
		a.id_pengiriman
		from pengiriman a
	inner join user b on b.id_user=a.pickup_by where a.tanggal > DATE_SUB(now(), INTERVAL 2 MONTH) and  a.pickup='Y' and a.pickup_by is not null and a.pickup_by_agent='$u[id_cabang]' 
	group by DATE_FORMAT(a.pickup_date,'%y-%m-%d'), a.pickup_by
	) temp
EOT;
		}

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
			array( 'db' => 'berat', 'dt'=> 3 ),
			array( 'db' => 'collie', 'dt' => 4 ),
			array( 'db' => 'pickup_by', 'dt' => 5 ),
			array(
				  'db'        => 'pickup_by',
				  'dt'        => 5,
				  'formatter' => function( $d, $row ) {
					$aksi="module/mod_report-manifest-pickup/report-manifest-pickup_action.php";
					$key = "asdf$1011";
					return '<td><center><a class="btn btn-xs btn-primary" target="_blank" href="main.php?module=report-manifest-pickup&act=view&id='.base64_encrypt($d,$key).'"><i class="fa fa-search"></i> View</a></center></td>';
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
	elseif($module=='pickup' and $act=='switch'){
		$key = "asdf$1011";
		$encrypt = "$_POST[id]";
		$id = base64_decrypt($encrypt,$key);
		// lihat pickup request sebelumnya
		$pickup = mysqli_query($con,"select b.id_user, b.namalengkap from pengiriman a inner join user b on b.id_user=a.pickup_by where a.id_pengiriman='".$id."'");
		$p = mysqli_fetch_assoc($pickup);
		
		$query = mysqli_query($con, "SELECT if(id_kota_asal>0,x_inter(id_kota_asal),x_intercity(lokasi_asal)) as intercity, lokasi_asal,id_kota_asal, id_kustomer,pickup_date FROM pengiriman WHERE id_pengiriman='".$id."'");
		$r = mysqli_fetch_assoc($query);
		
		if($u['level']=='admin' or $u['level']=='operator' or $u['level']=='intercity' or $u['level']=='network' or $u['level']=='domex' or $u['level']=='agent'  or $u['level']=='inbound' or $u['level']=='admin_network'){
		echo'
		<form action="'.$aksi.'?module=pickup&act=switchkurir" name="modal_popup" method="POST">
		<input type="hidden" name="id" class="form-control" value="'.$id.'" />';
		if(!empty($p['namalengkap'])){
			echo'
			<div class="form-group" style="padding-bottom: 5px;">
                <label>Pickup By:</label>
				<input type="text" class="form-control" value="'.$p['namalengkap'].'" disabled/>
			</div>';
		}
		if($r['intercity']=='Y'){
			echo'
			<div class="form-group" style="padding-bottom: 5px;">
                <label>Switch to:</label>
     			<select name="pickup_by" class="form-control">';
				$result = mysqli_query($con,"select id_user, namalengkap from user where (level='kurir' or level='driver') and blokir='N' and id_user!='$p[id_user]' order by namalengkap");
				while ($row = mysqli_fetch_assoc($result)){
					echo '<option value="'.$row['id_user'].'">'.strtoupper($row['namalengkap']).'</option>';
				}
				echo'
				</select>
			</div>
			
			<div class="form-group" style="padding-bottom: 5px;">
                <label>Tanggal to:</label>
     			<select name="tanggal" class="form-control">';
				
				$pickup_date = new DateTime($r['pickup_date']);
				$pickup_date->modify('-1 day');
				$batasStr = $pickup_date->format('Y-m-d');
				$formatsecond = $pickup_date->format('H:m:s');
				$pickupdate1 = new DateTime($r['pickup_date']);
				$pickupdate = $pickupdate1->format('Y-m-d');
				echo '<option value="'.$pickupdate.' '.$formatsecond.'">'.$pickupdate.' '.$formatsecond.'</option>';
				echo '<option value="'.$batasStr.' '.$formatsecond.'">'.$batasStr.' '.$formatsecond.'</option>';
				echo '
				</select>
			</div>
			
			';
		}
		echo'
			<div class="modal-footer">
				<button class="btn btn-success" type="submit">Confirm</button>
				<button type="reset" class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Cancel</button>
			</div>
		</form>';
		}
	
	}
	// switch action
	elseif($module=='pickup' AND $act=='switchkurir'){
		 if (empty($_POST['pickup_by']) || empty($_POST['tanggal']) || empty($_POST['id'])) {
			die('Data tidak lengkap.'.$_POST['tanggal']);
		}

		$pickup_change = mysqli_real_escape_string($con, $_POST['pickup_by']);
		$pickup_date   = mysqli_real_escape_string($con, $_POST['tanggal']);
		$id            = mysqli_real_escape_string($con, $_POST['id']);
		
		mysqli_query($con, "update pengiriman set pickup_by=$pickup_change,pickup_date='$pickup_date' where id_pengiriman='$id'");
		
		header('Location: ' . $_SERVER['HTTP_REFERER']);
	}

}
?>