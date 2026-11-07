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
	if($module=='outbound' and $act=='data'){
		if($u['level']=='admin' || $u['level']=='finance'){
		$table = 
<<<EOT
	(
	select 
	a.tanggal, 
		x_user(a.manifest_outbound_by) as created_by, 
		x_namacabang(a.manifest_from) as manifest_from,
		x_namacabang(a.manifest_to) as manifest_to,
		a.manifest_via, 
		count(DISTINCT c.id_pengiriman) as jumlah, 
		sum(coalesce(c.berat_aktual,c.berat_barang)) as berat, 
		a.transit, 
		a.id_manifest 
	from manifest a 
	inner join manifest_detail b on b.id_manifest=a.id_manifest 
	inner join pengiriman c on c.id_pengiriman=b.id_pengiriman 
	

	where a.tanggal > DATE_SUB(now(), INTERVAL 5 MONTH) and   (a.manifest_from  in (39,41) and a.manifest_to not in (39,41) 
	)
	group by a.id_manifest
	order by a.tanggal desc, a.id_manifest desc
	) temp
EOT;
		}elseif($u['level']=='operator'){
		$table = 
<<<EOT
	(
	select 
	a.tanggal, 
		x_user(a.manifest_outbound_by) as created_by, 
		x_namacabang(a.manifest_from) as manifest_from,
		x_namacabang(a.manifest_to) as manifest_to,
		a.manifest_via, 
		count(DISTINCT c.id_pengiriman) as jumlah, 
		sum(coalesce(c.berat_aktual,c.berat_barang)) as berat, 
		a.transit, 
		a.id_manifest 
	from manifest a 
	inner join manifest_detail b on b.id_manifest=a.id_manifest 
	inner join pengiriman c on c.id_pengiriman=b.id_pengiriman 
	

	where a.tanggal > DATE_SUB(now(), INTERVAL 5 MONTH) and   (a.manifest_from  in (39,41) and a.manifest_to not in (39,41) 
	)
	group by a.id_manifest
	order by a.tanggal desc, a.id_manifest desc
	) temp
EOT;
		}elseif($u['level']=='domex'){
		$table = 
<<<EOT
	(
	select 
	a.tanggal, 
		x_user(a.manifest_outbound_by) as created_by, 
		case when x_intercity(d.id_kota)='Y' then 'JAKARTA' else d.nama_cabang end as manifest_from, 
		case when x_intercity(e.id_kota)='Y' then 'JAKARTA' else e.nama_cabang end as manifest_to, 
		case when a.manifest_via='1' then 'UDARA' when a.manifest_via='2' then 'DARAT' when a.manifest_via='3' then 'LAUT' when a.manifest_via='4' then 'TOTO' when a.manifest_via='5' then 'HERONA' when a.manifest_via='6' then 'BES'  when a.manifest_via='7' then 'Indah Cargo' when a.manifest_via='8' then 'JNE' when a.manifest_via='9' then 'TIKI' else a.manifest_via end as manifest_via, 
		count(c.id_pengiriman) as jumlah, 
		sum(coalesce(c.berat_aktual,c.berat_barang)) as berat, 
		a.transit, 
		a.id_manifest 
	from manifest a 
	inner join manifest_detail b on b.id_manifest=a.id_manifest 
	inner join pengiriman c on c.id_pengiriman=b.id_pengiriman 
	inner join cabang d on d.id_cabang=a.manifest_from 
	inner join cabang e on e.id_cabang=a.manifest_to
	where a.tanggal > DATE_SUB(now(), INTERVAL 5 MONTH) and if(c.id_kota_tujuan>0,x_inter(c.id_kota_tujuan) !='Y',x_intercity(c.lokasi_tujuan)!='Y')
	group by a.id_manifest
	order by tanggal desc, a.id_manifest desc
	) temp
EOT;
		}

		elseif($u['level']=='network'  || $u['id_user'] == 17 || $u['level']=='admin_network'){
		$table = 
<<<EOT
	(
	select 
	a.tanggal, 
		x_user(a.manifest_outbound_by) as created_by, 
		x_namacabang(a.manifest_from) as manifest_from,
		x_namacabang(a.manifest_to) as manifest_to,
		a.manifest_via, 
		count(DISTINCT c.id_pengiriman) as jumlah, 
		sum(coalesce(c.berat_aktual,c.berat_barang)) as berat, 
		a.transit, 
		a.id_manifest 
	from manifest a 
	inner join manifest_detail b on b.id_manifest=a.id_manifest 
	inner join pengiriman c on c.id_pengiriman=b.id_pengiriman 
	
	where a.tanggal > DATE_SUB(now(), INTERVAL 2 MONTH) and    a.manifest_to not in (39,41) 
	
	group by a.id_manifest
	order by tanggal desc, a.id_manifest desc
	) temp
EOT;
		}elseif($u['level']=='inbound' ){
		$table = 
<<<EOT
	(
	select 
	a.tanggal, 
		x_user(a.manifest_outbound_by) as created_by, 
		x_namacabang(a.manifest_from) as manifest_from,
		x_namacabang(a.manifest_to) as manifest_to,
		a.manifest_via, 
		count(DISTINCT c.id_pengiriman) as jumlah, 
		sum(coalesce(c.berat_aktual,c.berat_barang)) as berat, 
		a.transit, 
		a.id_manifest 
	from manifest a 
	inner join manifest_detail b on b.id_manifest=a.id_manifest 
	inner join pengiriman c on c.id_pengiriman=b.id_pengiriman 
	where a.tanggal > DATE_SUB(now(), INTERVAL 2 MONTH) and a.manifest_to in (39,41) 
	group by a.id_manifest
	order by tanggal desc, a.id_manifest desc
	) temp
EOT;
		}


		elseif($u['level']=='agent'){
		$table = 
<<<EOT
	(
	select
		a.tanggal,
		x_user(a.manifest_outbound_by) as created_by,
		case when x_intercity(b.id_kota)='Y' then 'JAKARTA' else b.nama_cabang end as manifest_from,
		case when x_intercity(c.id_kota)='Y' then 'JAKARTA' else c.nama_cabang end as manifest_to,
		case when a.manifest_via='1' then 'UDARA' when a.manifest_via='2' then 'DARAT' when a.manifest_via='3' then 'LAUT' when a.manifest_via='4' then 'TOTO' when a.manifest_via='5' then 'HERONA' when a.manifest_via='6' then 'BES'  when a.manifest_via='7' then 'Indah Cargo' when a.manifest_via='8' then 'JNE' when a.manifest_via='9' then 'TIKI' else a.manifest_via end as manifest_via,
		count(d.id_pengiriman) as jumlah,
		sum(coalesce(d.berat_aktual,d.berat_barang)) as berat,
		a.id_manifest,
		a.transit
	from manifest a 
	inner join cabang b on b.id_cabang=a.manifest_from 
	inner join cabang c on c.id_cabang=a.manifest_to 
	inner join manifest_detail e on e.id_manifest=a.id_manifest 
	inner join pengiriman d on d.id_pengiriman=e.id_pengiriman 
	where a.tanggal > DATE_SUB(now(), INTERVAL 5 MONTH) and a.manifest_from=$u[id_cabang] 
	group by a.id_manifest order by a.id_manifest desc
	) temp


EOT;
		}

		$primaryKey = 'id_manifest';

		$columns = array(
			array(
					'db'        => 'tanggal',
					'dt'        => 0,
					'formatter' => function( $d, $row ) {
						return date( 'd-m-Y', strtotime($d));
					}
			),
			array(
					'db'		=> 'created_by',
					'dt'		=> 1,
					'formatter'	=> function($d,$row){
						return '<td>'.strtoupper($d).'</td>';
					}
			),
			array( 'db' => 'manifest_from', 'dt'=> 2 ),
			array( 'db' => 'manifest_to', 'dt'=> 3 ),
			array( 'db' => 'manifest_via', 'dt'=> 4 ),
			array( 'db' => 'jumlah', 'dt'=> 5 ),
			array( 'db' => 'berat', 'dt'=> 6 ),
			array('db'			=> 'transit',
					'dt'		=> 7,
					'formatter'	=> function($d,$row){
						if($d=='Y'){
							$transit = '<i class="fa fa-exchange"></i> Transit';
						}else{
							$transit = '';
						}
						return '<td>'.$transit.'</td>';
					}
			),
			array(
				  'db'        => 'id_manifest',
				  'dt'        => 8,
				  'formatter' => function( $d, $row ) {
					$aksi="module/mod_airwaybill/airwaybill_action.php";
					$key = "asdf$1011";
					return '<td><center><a class="btn btn-xs btn-primary" target="_blank" href="main.php?module=report-manifest-outbound&act=view&id='.base64_encrypt($d,$key).'"><i class="fa fa-search"></i> View</a>

					

					<button class="btn btn-xs btn-warning open_switch_outbound" data-id='.base64_encrypt($d,$key).'"><i class="fa fa-exchange"></i></button></center></td>
					';
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
	
	// update
	}elseif($module=='outbound' and $act=='update'){
		$key = "asdf$1011";
		$encrypt = "$_POST[id_manifest]";
		$id = base64_encrypt($encrypt,$key);
		$tanggal_form = mysqli_real_escape_string($con, $_POST['tanggal']);
		$tanggal = jin_date_sql($tanggal_form);
		$nosmu = mysqli_real_escape_string($con, $_POST['no_smu']);
		
		mysqli_query($con, "update manifest set tanggal='$tanggal', no_smu='$_POST[no_smu]' where id_manifest='$_POST[id_manifest]'");
		header('location:../../main.php?module=report-manifest-outbound&act=view&id='.$id);
	
	//delete
	}elseif($module=='outbound' and $act=='delete'){
		$key = "asdf$1011";
		$encrypt = "$_GET[id]";
		$id = base64_decrypt($encrypt,$key);
		//$id_manifest = "$_GET[id_manifest]";
		//mysqli_query($con, "update pengiriman set status='2', transit='N' where id_pengiriman='$id'");
		mysqli_query($con, "delete from manifest_detail where id_manifest_detail='$id'");
		//echo "delete from manifest_detail where id_manifest_detail='$id'";
		header('Location: ' . $_SERVER['HTTP_REFERER']);
	}

	elseif($module=='outbound' and $act=='switch'){
		$key = "asdf$1011";
		$encrypt = "$_POST[id]";
		$id = base64_decrypt($encrypt,$key);
		// lihat pickup request sebelumnya
		$outbound = mysqli_query($con,"select b.id_user, b.namalengkap from manifest a inner join user b on b.id_user=a.manifest_outbound_by where a.id_manifest='".$id."'");
		$p = mysqli_fetch_assoc($outbound);
		
		$query = mysqli_query($con, "SELECT x_intercity(lokasi_asal) as intercity, lokasi_asal, id_kustomer FROM pengiriman WHERE id_pengiriman='".$id."'");
		$r = mysqli_fetch_assoc($query);
		
		if($u['level']=='admin' or $u['level']=='operator' or $u['level']=='intercity' or $u['level']=='admin_network' or $u['level']=='network' or $u['level']=='domex' or $u['level']=='agent'){
		echo'
		<form action="module/mod_report-manifest-outbound/report-manifest-outbound_action.php?module=outbound&act=switchupdate" name="modal_popup" method="POST">
		<input type="hidden" name="id" class="form-control" value="'.$id.'" />';
		if(!empty($p['namalengkap'])){
			echo'
			<div class="form-group" style="padding-bottom: 5px;">
                <label>Outbound By:</label>
				<input type="text" class="form-control" value="'.$p['namalengkap'].'" disabled/>
			</div>';
		}
		
			echo'
			<div class="form-group" style="padding-bottom: 5px;">
                <label>Switch to:</label>
     			<select name="outbound_by" class="form-control">';
				$result = mysqli_query($con,"select id_user, namalengkap from user where (level='kurir' or level='driver') and blokir='N' and id_user!='$p[id_user]' or id_cabang=16 order by namalengkap");
				while ($row = mysqli_fetch_assoc($result)){
					echo '<option value="'.$row['id_user'].'">'.strtoupper($row['namalengkap']).'</option>';
				}
				echo'
				</select>
			</div>';
		
		
		echo'
			<div class="modal-footer">
				<button class="btn btn-success" type="submit">Confirm</button>
				<button type="reset" class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Cancel</button>
			</div>
		</form>';
		}
	
	}

	elseif($module=='outbound' and $act=='switchupdate'){
		$id = mysqli_real_escape_string($con, $_POST['id']);
		$outbound_by = mysqli_real_escape_string($con, $_POST['outbound_by']);
		//echo "update manifest set manifest_outbound_by='$outbound_by' where id_manifest='$id'";
		//return false;
		mysqli_query($con, "update manifest set manifest_outbound_by='$outbound_by' where id_manifest='$id'");
		header('location:../../main.php?module=report-manifest-outbound');
	}
	

	elseif($module=='outbound' and $act=='switch-detail'){
		$id = $_POST['id'];

		
		$query = mysqli_query($con, "select  CASE 
										WHEN airwaybill_kode LIKE '001-%' THEN 0
										WHEN airwaybill_kode LIKE '002-%' THEN 1
									end as intercity, coalesce(berat_aktual,berat_barang) as berat,service, collie, transit, b.hub from pengiriman a left join kota b on a.lokasi_tujuan=b.id_kota where id_pengiriman='".$id."'");
		$r = mysqli_fetch_assoc($query);
		$transit=$r['transit'];
		$hub=$r['hub'];
		$service=$r['service'];
		$intercity = $r['intercity'];


		$from = mysqli_query($con, "SELECT x_intercity(lokasi_asal) as intercity_asal, lokasi_asal FROM pengiriman WHERE id_pengiriman='".$id."'");

		$r_from = mysqli_fetch_assoc($from);

		$intercity_asal = $r_from['intercity_asal'];

		// lihat pickup request sebelumnya
		$outbound = mysqli_query($con,"select a.id_manifest,a.id_pengiriman,b.tanggal,manifest_from,manifest_to,x_namacabang(manifest_from) as namacabang_from,x_namacabang(manifest_to) as namacabang_to,a.sla as sla  from manifest_detail a left join manifest b on a.id_manifest=b.id_manifest where id_manifest_detail='".$id."'");
		$o = mysqli_fetch_assoc($outbound);
		$date = $o['tanggal'];
		 $besok = date('Y-m-d', strtotime('+21 day', strtotime($date)));
		 $kemarin = date('Y-m-d', strtotime('-60 day', strtotime($date)));
	
			echo'
			<form action="module/mod_report-manifest-outbound/report-manifest-outbound_action.php?module=outbound&act=switchupdate-detail" name="modal_popup" method="POST">
			<input type="hidden" name="id_pengiriman" value="'.$o['id_pengiriman'].'">
			<input type="hidden" name="id_manifest_detail" value="'.$id.'">

			<div class="form-group" style="padding-bottom: 5px;">
				<label>Pindah Atau Tambah Baru</label>
				<select name="pindah_tambah" class="form-control">
					<option value="1">Pindah</option>
					<option value="2">Tambah</option>
				</select>
			</div>
			
			<div class="form-group" style="padding-bottom: 5px;">
				<div class="switch-section">
	                <label>Switch</label>
	     			<select name="id_manifest" class="form-control">';
					$result = mysqli_query($con,"select id_manifest,x_user(a.manifest_outbound_by) as outbound_by, case when a.manifest_via='1' then 'UDARA' when a.manifest_via='2' then 'DARAT' when a.manifest_via='3' then 'LAUT' when a.manifest_via='4' then 'TOTO' when a.manifest_via='5' then 'HERONA' when a.manifest_via='7' then 'Indah Cargo' when a.manifest_via='8' then 'JNE' when a.manifest_via='9' then 'TIKI' else a.manifest_via end as manifest_via,tanggal, x_namacabang(a.manifest_from) as cabang_asal, x_namacabang(a.manifest_to) as cabang_tujuan  ,a.kode_manifest

						from manifest a left join user b on b.id_user=a.manifest_outbound_by  

						where  a.tanggal BETWEEN '$kemarin' AND '$besok' 
						and ( manifest_from in( select id_cabang from cabang WHERE id_kota_baru=x_idkotacabang($o[manifest_from]) ) 
						or manifest_to in( select id_cabang from cabang WHERE id_kota_baru=x_idkotacabang($o[manifest_to]) ) 

						or manifest_to in( select id_cabang from cabang WHERE id_kota_baru=x_idkotacabang($o[manifest_from]) ) 
						or manifest_from in( select id_cabang from cabang WHERE id_kota_baru=x_idkotacabang($o[manifest_to]) ) ) 

						order by tanggal desc, manifest_from ");


			
					while ($row = mysqli_fetch_assoc($result)){
						echo '<option '.($row['id_manifest'] == $o['id_manifest'] ? 'selected' : '').' value="'.$row['id_manifest'].'">'.$row['tanggal'].', '.strtoupper($row['cabang_asal']).' ✈ '.strtoupper($row['cabang_tujuan']).', '.strtoupper($row['manifest_via']).','.strtoupper($row['kode_manifest']).'</option>';
					}
					echo'
					</select>
				</div>
				

					<label class="checkbox-inline"><input type="checkbox" name="check-new" id="checkswitchnew" value="1">Create New</label>
					
					<br><label class="checkbox-inline"><input type="checkbox" name="jadi_delivery" id="jadi_delivery" value="1">Jadikan cabang tujuan ini untuk delivery paket</label>
					<br><label class="checkbox-inline"><input type="checkbox" name="jadi_pickup" id="jadi_pickup" value="1">Jadikan cabang tujuan ini untuk pickup paket</label>

				<div class="switch-new" style="display:none;">
					

				<div class="form-group" style="padding-bottom: 5px;">
                <label>Manifest From:</label>
				<select name="manifest_from" class="form-control select2" width="100%" required>';
							echo '<option value="'.$o[manifest_to].'"><b>'.$o[namacabang_to].'</b> </option>';
							$result = mysqli_query($con,"select case when x_inter(a.id_kota_asal)='Y' then '39' else c.id_cabang end as id_cabang, case when x_inter(a.id_kota_asal)='Y' then 'JAKARTA' else c.nama_cabang end as nama_cabang, case when x_inter(a.id_kota_asal)='Y' then 'CESCO LOGISTICS' else c.alamat1 end as alamat1 from pengiriman a 

								left join kota b on b.id_kota=a.lokasi_asal 
								left join regencies d on d.id = a.id_kota_asal
								left join provinces e on e.id = d.province_id
								left join cabang c on 
								c.id_kota_baru= ifnull(d.hub,e.ibu_kota)

									or c.id_kota_baru = x_hubnew(a.id_kota_asal)

									or c.id_kota_baru =a.id_kota_asal


								where a.id_pengiriman='".$o[id_pengiriman]."' and (c.status != 'N' or c.status is null) order by c.nama_cabang");
				
						while ($row = mysqli_fetch_assoc($result)) {
							echo '<option value="' . $row['id_cabang'] . '"><b>'.strtoupper($row['nama_cabang']).'</b> ('.strtoupper($row['alamat1']).')</option>';
						}
						$result = mysqli_query($con,"select case when x_inter(a.id_kota_asal)='Y' then '39' else c.id_cabang end as id_cabang, case when x_inter(a.id_kota_asal)='Y' then 'JAKARTA' else c.nama_cabang end as nama_cabang, case when x_inter(a.id_kota_asal)='Y' then 'CESCO LOGISTICS' else c.alamat1 end as alamat1 from pengiriman a 

								left join kota b on b.id_kota=a.lokasi_asal 
								left join regencies d on d.id = a.id_kota_asal
								left join provinces e on e.id = d.province_id
								left join cabang c on 
								c.id_kota_baru= ifnull(d.hub,e.ibu_kota)

									or c.id_kota_baru = x_hubnew(a.id_kota_asal)

									or c.id_kota_baru =a.id_kota_asal


								where a.id_pengiriman='".$o[id_pengiriman]."' and (c.status != 'N' or c.status is null) order by c.nama_cabang");

						$query_all_city = mysqli_query($con,"select  id_cabang, nama_cabang,alamat1 from cabang where trucking_all_city='Y' order by nama_cabang");

								echo '<option value="'.$o[manifest_to].'"><b>'.$o[namacabang_to].'</b> </option>';

								while ($r_all_city = mysqli_fetch_assoc($query_all_city)) {
									echo '<option value="' . $r_all_city['id_cabang'] . '"><b>'.strtoupper($r_all_city['nama_cabang']).'</b> ('.strtoupper($r_all_city['alamat1']).')</option>';
								}


						
						echo '<option value="16"><b>SURABAYA CESCO LOGISTICS</b> </option>';
						echo '<option value="39"><b>JAKARTA CESCO LOGISTICS</b> </option>';
						echo '<option value="31"><b>MAKASSAR ATLAS</b> </option>';
						echo '<option value="11"><b>BANDUNG</b> </option>';
						
				
					echo '
				</select>
			</div>';
			
			
				
			
			echo'
			<div class="form-group" style="padding-bottom: 5px;">
                <label>Manifest To:</label>
				<select name="manifest_to" class="form-control select2" width="100%" required>				
				';

					
					echo '<option value="'.$o["manifest_from"].'"><b>'.$o["namacabang_from"].'</b> </option>';
					
					if($r['service'] == 5){
						$result = mysqli_query($con,"select case when x_intercity(a.lokasi_tujuan)='Y' then '39' else c.id_cabang end as id_cabang, case when x_intercity(a.lokasi_tujuan)='Y' then 'JAKARTA' else c.nama_cabang end as nama_cabang, case when x_intercity(a.lokasi_tujuan)='Y' then 'CESCO LOGISTICS' else c.alamat1 end as alamat1 from pengiriman a 
							left join kota b on b.id_kota=a.lokasi_tujuan 
							left join regencies d on d.id = a.id_kota_tujuan
								left join provinces e on e.id = d.province_id

							left join cabang c on c.id_kota_baru= ifnull(d.hub,e.ibu_kota) or c.id_kota_baru= a.id_kota_tujuan

							where a.id_pengiriman='".$o[id_pengiriman]."' and (c.status != 'N' or c.status is null)  order by c.nama_cabang");

						while ($row = mysqli_fetch_assoc($result)) {
							echo '<option value="' . $row['id_cabang'] . '"><b>'.strtoupper($row['nama_cabang']).'</b> ('.strtoupper($row['alamat1']).')</option>';
						}
						echo '<option value="70"><b>HANOMAN LOGISTICS</b> SURABAYA</option>';
							echo '<option value="102">JPM Logistics</option>';
						if($service == 3 || $service == 5 || $service == 4){
							$result = $result = mysqli_query($con,"select  id_cabang, nama_cabang,alamat1 from cabang where trucking_all_city='Y' order by nama_cabang");

								while ($row = mysqli_fetch_assoc($result)) {
									echo '<option value="' . $row['id_cabang'] . '"><b>'.strtoupper($row['nama_cabang']).'</b> ('.strtoupper($row['alamat1']).')</option>';
								}
						}
					}
					else{
						$result = mysqli_query($con,"select case when x_intercity(a.lokasi_tujuan)='Y' then '39' else c.id_cabang end as id_cabang, case when x_intercity(a.lokasi_tujuan)='Y' then 'JAKARTA' else c.nama_cabang end as nama_cabang, case when x_intercity(a.lokasi_tujuan)='Y' then 'CESCO LOGISTICS' else c.alamat1 end as alamat1 from pengiriman a 
							
							left join kota b on b.id_kota=a.lokasi_tujuan 
							left join regencies d on d.id = a.id_kota_tujuan
								left join provinces e on e.id = d.province_id

							left join cabang c on if(a.id_kota_asal>0,c.id_kota_baru,c.id_kota)= if(a.id_kota_tujuan>0,ifnull(d.hub,e.ibu_kota),b.hub) or if(a.id_kota_tujuan>0,c.id_kota_baru,c.id_kota)= if(a.id_kota_tujuan>0,a.id_kota_tujuan,a.lokasi_tujuan)

							where a.id_pengiriman='".$o[id_pengiriman]."' and (c.status != 'N' or c.status is null) order by c.nama_cabang");

						while ($row = mysqli_fetch_assoc($result)) {
							echo '<option value="' . $row['id_cabang'] . '"><b>'.strtoupper($row['nama_cabang']).'</b> ('.strtoupper($row['alamat1']).')</option>';
							
						}
						echo '<option value="70"><b>HANOMAN LOGISTICS</b> SURABAYA</option>';
							echo '<option value="102">JPM Logistics</option>';
						 $res = mysqli_query($con,"select  id_cabang, nama_cabang,alamat1 from cabang where trucking_all_city='Y' order by nama_cabang");

								while ($r_all = mysqli_fetch_assoc($res)) {
									echo '<option value="' . $r_all['id_cabang'] . '"><b>'.strtoupper($r_all['nama_cabang']).'</b> ('.strtoupper($r_all['alamat1']).')</option>';
								}
								echo '<option value="16"><b>SURABAYA CESCO LOGISTICS</b> </option>';
						echo '<option value="39"><b>JAKARTA CESCO LOGISTICS</b> </option>';
						echo '<option value="31"><b>MAKASSAR ATLAS</b> </option>';
						echo '<option value="11"><b>BANDUNG</b> </option>';
					}

					echo '
				</select>
			</div>
			<div class="form-group" style="padding-bottom: 5px;">
			';
			$datee = $o['tanggal'];
			$sla = $o['sla'];
			$sla_plus1 = $sla+1;
		 	$tomo = date('Y-m-d', strtotime('+1 day', strtotime($date)));
		 	$tomo2 = date('Y-m-d', strtotime('+2 day', strtotime($date)));
		 	$yester = date('Y-m-d', strtotime('-1 day', strtotime($date)));
		 	$yester2 = date('Y-m-d', strtotime('-2 day', strtotime($date)));
		 	$sla_plus = date('Y-m-d', strtotime('+'.$sla_plus1.' day', strtotime($date)));
			echo '
                <label>Manifest Date:</label>
				<select name="manifest_date" class="form-control select2" width="100%" required>	
<option value="'.date('Y-m-d', strtotime('-7 day', strtotime($date))).'">'.date('Y-m-d', strtotime('-7 day', strtotime($date))).' </option>		
					<option value="'.date('Y-m-d', strtotime('-6 day', strtotime($date))).'">'.date('Y-m-d', strtotime('-6 day', strtotime($date))).' </option>				
					<option value="'.$datee.'">'.$datee.' </option>
					<option value="'.$tomo2.'">'.$tomo2.' </option>
					<option value="'.$tomo.'">'.$tomo.' </option>
					<option value="'.$yester.'">'.$yester.'</option>
					<option value="'.$yester2.'">'.$yester2.'</option>
					<option value="'.$sla_plus.'">'.$sla_plus.'</option>
				</select>
			</div>
			<div class="form-group" style="padding-bottom: 5px;">
                <label>Manifest Via:</label>
				<select name="manifest_via" class="form-control select2" width="100%"> 					
					<option value="">- SELECT -</option>
				';

				$result = $result = mysqli_query($con,"select  name, id from manifest_vendor where active = 'Y' order by name asc ");

								while ($row = mysqli_fetch_assoc($result)) {
									echo '<option value="' . $row['name'] . '"><b>'.strtoupper($row['name']).'</b> </option>';
								}

				/*echo
					<option value="">- SELECT -</option>
					<option value="RIONA SUB">RIONA SUB</option>
					<option value="RIONA UPG">RIONA UPG</option>
					<option value="DEN LOGSTIK">DEN LOGSTIK</option>
					<option value="DUA PERISAI">DUA PERISAI</option>
					<option value="PANCA MANDIRI">PANCA MANDIRI</option>
					<option value="POS LAUT">POS LAUT</option>
					<option value="JAYA BAYA">JAYA BAYA</option>
					<option value="DANEX">DANEX</option>
					<option value="PANDU SIWI">PANDU SIWI</option>
					<option value="HANOMAN LOGISTIK">HANOMAN LOGISTIK</option>
					<option value="CAC WEKO">CAC / WEKO</option>
					<option value="GA SPEED">GA SPEED</option>
					<option value="GA CN">GA CN</option>
					<option value="DMK">DMK</option>
					<option value="HERONA">HERONA</option>
					<option value="KLOG">KLOG</option>
					<option value="KI8">KI8</option>
					<option value="TOTO">TOTO</option>
					<option value="RAIL EXPRESS">RAIL EXPRESS</option>					
					<option value="XTRANS">XTRANS</option>
					<option value="BES">BES</option>
					<option value="MJE">MJE</option>
					<option value="JNE">JNE</option>
					<option value="TIKI">TIKI</option>
					<option value="ARHDHL">ARH/DHL</option>
					<option value="IJX">IJX/WEKO</option>
					<option value="ANUGRAH">ANUGRAH</option>
					<option value="INDAH CARGO">INDAH CARGO</option>
					<option value="PLUIT KENCANA">PLUIT KENCANA</option>
					<option value="PUTRA BANGKA">PUTRA BANGKA EXPRESS</option>
					<option value="APM">APM</option>
					<option value="BALI PRIMA">BALI PRIMA</option>
					<option value="SMI">SMI</option>
					<option value="R2M express">R2M express</option>
					<option value="ATLAS TRUCKING">ATLAS TRUCKING</option>	
					<option value="MEDIA LOG">MEDIA LOG</option>	*/				
					echo '
				</select>
			</div>			
			<div class="form-group" style="padding-bottom: 5px;">
                <label>Manifest Outbound By: (Lapangan)</label>
				<select name="manifest_by" class="form-control select2" width="100%">
					';
					
						
				
								$result = mysqli_query($con,"select id_user, namalengkap, x_namacabang(id_cabang) as nama_cabang from user where (level='kurir' or level='driver' or level ='agent' or level='kurir_agent')  AND blokir != 'Y' order by nama_cabang asc");
								while ($row = mysqli_fetch_assoc($result)){
									echo '<option value="'.$row['id_user'].'">'.strtoupper($row['namalengkap']).'( '.$row['nama_cabang'].' )</option>';
								}
				
						
				
					echo '
					
					
				</select>
			</div>

				</div>

			</div>';
		//echo 	"select id_manifest,x_user(a.manifest_outbound_by) as outbound_by, case when a.manifest_via='1' then 'UDARA' when a.manifest_via='2' then 'DARAT' when a.manifest_via='3' then 'LAUT' when a.manifest_via='4' then 'TOTO' when a.manifest_via='5' then 'HERONA' when a.manifest_via='7' then 'Indah Cargo' when a.manifest_via='8' then 'JNE' when a.manifest_via='9' then 'TIKI' else a.manifest_via end as manifest_via,tanggal, x_namacabang(a.manifest_from) as cabang_asal, x_namacabang(a.manifest_to) as cabang_tujuan  from manifest a inner join user b on b.id_user=a.manifest_outbound_by  where manifest_outbound_by != '' and a.tanggal BETWEEN '$kemarin' AND '$besok' and ( manifest_from = $o[manifest_from] or manifest_to in( select id_cabang from cabang WHERE id_kota=x_idkotacabang($o[manifest_to]) )  ) order by id_manifest desc ";
		
		echo'
			<div class="modal-footer">

				<button class="btn btn-success" type="submit">Confirm</button>
				<button type="reset" class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Cancel</button>
			</div>
		</form>';
		
	
	}

	elseif($module=='outbound' and $act=='switchupdate-detail'){
		
		$new = mysqli_real_escape_string($con, $_POST['check-new']);

		$id_manifest_detail = mysqli_real_escape_string($con, $_POST['id_manifest_detail']);
		$id_pengiriman = mysqli_real_escape_string($con, $_POST['id_pengiriman']);
		$transit_to = mysqli_real_escape_string($con, $_POST['transit_to']);
		$pindah_tambah = mysqli_real_escape_string($con, $_POST['pindah_tambah']);

		$jadi_delivery = mysqli_real_escape_string($con, $_POST['jadi_delivery']);
		$jadi_pickup = mysqli_real_escape_string($con, $_POST['jadi_pickup']);
		//1 pindah  ,, 2 tambah
		$q = "SELECT id_manifest,id_pengiriman from manifest_detail where id_manifest_detail=$id_manifest_detail";


					$h = mysqli_query($con,$q);
					$d  = mysqli_fetch_assoc($h);
		//echo $new; 
		//return false;
		if ($new == 1){
		// 0  == pindah dan 1 = tambah baru

				
				 
					//echo "update pengiriman set transit='Y' where id_pengiriman=$d[id_pengiriman] ";
					//return false;

				$manifest_from = mysqli_real_escape_string($con, $_POST['manifest_from']);
				$transit_to = mysqli_real_escape_string($con, $_POST['transit_to']);
				$manifest_to = mysqli_real_escape_string($con, $_POST['manifest_to']);
				$manifest_date = mysqli_real_escape_string($con, $_POST['manifest_date']);		
				$manifest_via = mysqli_real_escape_string($con, $_POST['manifest_via']);
				$manifest_by =  mysqli_real_escape_string($con, $_POST['manifest_by']);
				
				$today = date("Ymd", strtotime($manifest_date));
					$tgl_sekarang = $manifest_date;
					$query = "SELECT max(kode_manifest) AS last FROM manifest WHERE kode_manifest LIKE '$today%'";
					$hasil = mysqli_query($con,$query);
					$data  = mysqli_fetch_assoc($hasil);
					$lastNoTransaksi = $data['last'];
					// baca nomor urut transaksi dari id transaksi terakhir
					$lastNoUrut = substr($lastNoTransaksi, 8, 3); 
					// nomor urut ditambah 1
					$nextNoUrut = $lastNoUrut + 1;
					// membuat format nomor transaksi berikutnya
					$nextNoTransaksi = $today.sprintf('%03s', $nextNoUrut);

				/*if ($transit_to > 0){
					mysqli_query($con, "INSERT INTO manifest (kode_manifest,tanggal,manifest_from,manifest_to,transit,manifest_via,manifest_outbound_by,created_date,created_by, created_with ) VALUES ('$nextNoTransaksi','$tgl_sekarang','$manifest_from','$transit_to','Y','$manifest_via','$manifest_by','$tgl_jam','$u[id_user]','web')");
					$id_manifest = mysqli_insert_id($con);
					// insert to manifest detail
					
					if($pindah_tambah == 1){
						mysqli_query($con, "update manifest_detail set id_manifest='$id_manifest' where id_manifest_detail ='$id_manifest_detail'");
					}
					else{
						mysqli_query($con, "insert into manifest_detail (id_manifest_detail,id_manifest,id_pengiriman,sla) VALUES ('',$id_manifest,$id_pengiriman,1) ");
					}
					
					//mysqli_query($con, "update pengiriman set transit='Y' where id_pengiriman=$d[id_pengiriman] ");
				}else{*/
					//$today = date($manifest_date,"Ymd");
					
					

					//echo "INSERT INTO manifest (kode_manifest,tanggal,manifest_from,manifest_to,manifest_via,manifest_outbound_by,created_date,created_by, created_with ) VALUES ('$nextNoTransaksi','$tgl_sekarang','$manifest_from','$manifest_to','$manifest_via','$manifest_by','$tgl_jam','$u[id_user]','web')";
					
					mysqli_query($con, "INSERT INTO manifest (kode_manifest,tanggal,manifest_from,manifest_to,manifest_via,manifest_outbound_by,created_date,created_by, created_with ) VALUES ('$nextNoTransaksi','$tgl_sekarang','$manifest_from','$manifest_to','$manifest_via','$manifest_by','$tgl_jam','$u[id_user]','web')");
					$id_manifest = mysqli_insert_id($con);
					// insert to manifest detail
					
					// 1 pindah
					if($pindah_tambah == 1){
						mysqli_query($con, "update manifest_detail set id_manifest='$id_manifest' where id_manifest_detail ='$id_manifest_detail'");
					}
					//else tambah
					else{
						mysqli_query($con, "insert into manifest_detail (id_manifest_detail,id_manifest,id_pengiriman,sla) VALUES ('',$id_manifest,$id_pengiriman,1) ");
					}
			if ($jadi_delivery == 1){
				mysqli_query($con, "update pengiriman set delivery_by_agent=$manifest_to where id_pengiriman=$id_pengiriman ");
			}
			if ($jadi_pickup == 1){
				mysqli_query($con, "update pengiriman set pickup_by_agent=$manifest_from where id_pengiriman=$id_pengiriman ");
			}
					
				//}
				header('Location: ' . $_SERVER['HTTP_REFERER']);
		}else{
		
			
			



			//$id_manifest_detail = mysqli_real_escape_string($con, $_POST['id_manifest_detail']);
			$id_manifest = mysqli_real_escape_string($con, $_POST['id_manifest']);
			$cek = mysqli_query($con,"SELECT manifest_from,manifest_to from manifest where id_manifest=$id_manifest");
			$row  = mysqli_fetch_assoc($cek);
			//$cek_transit = mysqli_query($con,"SELECT transit,manifest_to from manifest where id_manifest=$d[id_manifest]");
			//$d_transit  = mysqli_fetch_assoc($cek_transit);
			//if($d_transit['transit'] == 'Y'){
				//mysqli_query($con, "update pengiriman set transit='Y' where id_pengiriman=$d[pengiriman] ");
			//}

			//$outbound_by = mysqli_real_escape_string($con, $_POST['outbound_by']);
			//echo "update manifest set manifest_outbound_by='$outbound_by' where id_manifest='$id'";
			//return false;

			//1 pindah
			if($pindah_tambah == 1){
						mysqli_query($con, "update manifest_detail set id_manifest='$id_manifest' where id_manifest_detail ='$id_manifest_detail'");
					}
					else{
						mysqli_query($con, "insert into manifest_detail (id_manifest_detail,id_manifest,id_pengiriman,sla) VALUES ('',$id_manifest,$id_pengiriman,1) ");
					}

			if ($jadi_delivery == 1){
				mysqli_query($con, "update pengiriman set delivery_by_agent=$row[manifest_to] where id_pengiriman=$id_pengiriman ");
			}

			if ($jadi_pickup == 1){
				mysqli_query($con, "update pengiriman set pickup_by_agent=$row[manifest_from] where id_pengiriman=$id_pengiriman ");
			}
			
			header('Location: ' . $_SERVER['HTTP_REFERER']);
		}
	}

	elseif($module=='report-manifest-outbound' and $act=='searchawb'){
		$q=mysqli_real_escape_string($con,$_POST['q']);

		if($q == ''){
			echo'
				<td colspan="2">
					<center><b>Masukan No AWB</b></center>
				</td>'; 
		}else{

			$query=mysqli_query($con,"select a.id_manifest,a.id_pengiriman,b.airwaybill_kode from manifest_detail a 
				inner join pengiriman b on a.id_pengiriman  = b.id_pengiriman 
				inner JOIN manifest c ON a.id_manifest = c.id_manifest		
			where b.airwaybill_kode LIKE '%".$q."%' and c.inbound='N' AND c.tipe_manifest is null");
			// select a.id_manifest,a.id_pengiriman,b.airwaybill_kode from manifest_detail a inner join pengiriman b on a.id_pengiriman = b.id_pengiriman inner JOIN manifest c ON a.id_manifest = c.id_manifest where b.airwaybill_kode LIKE '%001-00185478%' and c.inbound='N' AND c.tipe_manifest is null
			$row=mysqli_num_rows($query);


			if ($row > 0){
				while ($data=mysqli_fetch_assoc($query)){
					$key = "asdf$1011";
						
					echo '
					<td>'.$data['airwaybill_kode'].'	</td>

					<td><center><a class="btn btn-xs btn-primary" target="_blank" href="?module=report-manifest-outbound&act=view&id='.base64_encrypt($data['id_manifest'],$key).'"><i class="fa fa-search"></i> View</a></center></td>
					'; 
				}
			}
			else{
				echo'
				<td colspan="2">
					<center><b>AIRWAYBILL NOT FOUND</b></center>
				</td>'; 
			}
		}
	}

	elseif($module=='outbound' and $act=='switch-manifest-from'){
		$key = "asdf$1011";
		$encrypt = "$_POST[id]";
		$id = base64_decrypt($encrypt,$key);


		echo '
			<form action="module/mod_report-manifest-outbound/report-manifest-outbound_action.php?module=outbound&act=switch-update-manifest-from" name="modal_popup" method="POST">
			
				<input type="hidden" name="id_manifest" value="'.$id.'">
				<div class="form-group" style="padding-bottom: 5px;">
	                <label>Manifest From:</label>
					<select name="manifest_from" class="form-control select2" width="100%" required>';
								
								
							$result = mysqli_query($con,"select id_cabang, nama_cabang,alamat1 from cabang  where status='Y' and (status != 'N' or status is null) order by nama_cabang");

							while ($r = mysqli_fetch_assoc($result)) {
								echo '<option value="' . $r['id_cabang'] . '"><b>'.strtoupper($r['nama_cabang']).'</b> ('.strtoupper($r['alamat1']).')</option>';
							}					
						echo '
					</select>
					<div class="modal-footer">

						<button class="btn btn-success" type="submit">Confirm</button>
						<button type="reset" class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Cancel</button>
					</div>
				</div>
			</form>
		';
	}
	elseif($module=='outbound' and $act=='switch-update-manifest-from'){

		$id = mysqli_real_escape_string($con, $_POST['id_manifest']);
		$manifest_from = mysqli_real_escape_string($con, $_POST['manifest_from']);
		echo "update manifest set manifest_from='$manifest_from' where id_manifest='$id'";
		mysqli_query($con, "update manifest set manifest_from='$manifest_from' where id_manifest='$id'");
		header('Location: ' . $_SERVER['HTTP_REFERER']);
	}
	// change manifest to
	elseif($module=='outbound' and $act=='switch-manifest-to'){
		$key = "asdf$1011";
		$encrypt = "$_POST[id]";
		$id = base64_decrypt($encrypt,$key);


		echo '
			<form action="module/mod_report-manifest-outbound/report-manifest-outbound_action.php?module=outbound&act=switch-update-manifest-to" name="modal_popup" method="POST">
			
				<input type="hidden" name="id_manifest" value="'.$id.'">
				<div class="form-group" style="padding-bottom: 5px;">
	                <label>Manifest to:</label>
					<select name="manifest_to" class="form-control select2" width="100%" required>';
								
								
							$result = mysqli_query($con,"select id_cabang, nama_cabang,alamat1 from cabang  where status='Y' and (status != 'N' or status is null) order by nama_cabang");

							while ($r = mysqli_fetch_assoc($result)) {
								echo '<option value="' . $r['id_cabang'] . '"><b>'.strtoupper($r['nama_cabang']).'</b> ('.strtoupper($r['alamat1']).')</option>';
							}					
						echo '
					</select>
					<div class="modal-footer">

						<button class="btn btn-success" type="submit">Confirm</button>
						<button type="reset" class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Cancel</button>
					</div>
				</div>
			</form>
		';
	}
	elseif($module=='outbound' and $act=='switch-update-manifest-to'){

		$id = mysqli_real_escape_string($con, $_POST['id_manifest']);
		$manifest_to = mysqli_real_escape_string($con, $_POST['manifest_to']);
		echo "update manifest set manifest_to='$manifest_to' where id_manifest='$id'";
		mysqli_query($con, "update manifest set manifest_to='$manifest_to' where id_manifest='$id'");
		header('Location: ' . $_SERVER['HTTP_REFERER']);
	}



	elseif($module=='outbound' and $act=='form-koli-berat'){
		$key = "asdf$1011";
		$encrypt = "$_POST[id]";
		$id = base64_decrypt($encrypt,$key);
//echo "select berat_aktual_volume,collie_barang from manifest  where id_manifest='".$id."'";
		$outbound = mysqli_query($con,"select berat_aktual_volume,collie_barang from manifest  where id_manifest='".$id."'");
		$o = mysqli_fetch_assoc($outbound);


		echo '
			<form action="module/mod_report-manifest-outbound/report-manifest-outbound_action.php?module=outbound&act=update-koli-berat" name="modal_popup" method="POST">
			
				<input type="hidden" name="id_manifest" value="'.$id.'">
				<div class="form-group" style="padding-bottom: 5px;">
	                <label>Berat/Volume:</label>
					<input type="number" class="form-control" value="'.$o['berat_aktual_volume'].'" name="berat_volume" min="1" required/>
					 <label>Koli Barang:</label>
					<input type="number" class="form-control" value="'.$o['collie_barang'].'" name="koli" min="1" required/>
					<div class="modal-footer">

						<button class="btn btn-success" type="submit">Confirm</button>
						<button type="reset" class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Cancel</button>
					</div>
				</div>
			</form>
		';
	}
	elseif($module=='outbound' and $act=='update-koli-berat'){

		$id = mysqli_real_escape_string($con, $_POST['id_manifest']);
		$berat_aktual_volume = mysqli_real_escape_string($con, $_POST['berat_volume']);
		$collie_barang = mysqli_real_escape_string($con, $_POST['koli']);
		echo "update manifest set collie_barang=$collie_barang,berat_aktual_volume=$berat_aktual_volume where id_manifest='$id'";
		mysqli_query($con, "update manifest set collie_barang=$collie_barang,berat_aktual_volume=$berat_aktual_volume where id_manifest='$id'");
		header('Location: ' . $_SERVER['HTTP_REFERER']);
	}

	elseif($module=='outbound' and $act=='print_vendor'){
		$key = "asdf$1011";
		$encrypt = "$_POST[id]";
		$id = base64_decrypt($encrypt,$key);


		echo '
			<form action="module/mod_report-manifest-outbound/report_vendor_per_destination.php" name="modal_popup" method="GET" target="_blank">
			
				<input type="hidden" name="id" value="'.$_POST['id'].'" class="form-control">

				<div class="form-group" style="padding-bottom: 5px;">
	                <label>Jumlah koli:</label>
					
					<input type="number" name="koli" value="1" class="form-control">
					<div class="modal-footer">

						<button class="btn btn-success" type="submit">PRINT</button>
						<button type="reset" class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Cancel</button>
					</div>
				</div>
			</form>
		';
	}

	elseif($module=='outbound' and $act=='proses-save-report-vendor'){
		//$key = "asdf$1011";
		$id_manifest = "$_POST[idmanifest]";
		//$id = base64_decrypt($encrypt,$key);

		$databerat = $_POST['databerat'];
		$datakoli = $_POST['datakoli'];
		$jumlahkoli = $_POST['jumlahkoli'];
		
		$j=0;
		for($i=1; $i<=$jumlahkoli;$i++){
			
			//$test[] = 'insert into manifest_collie_vendor values("","'.$id_manifest.'","'.$i.'","'.$databerat[$j].'","'.$datakoli[$j].'")';
			mysqli_query($con, "insert into manifest_collie_vendor (id,id_manifest,koli_ke,berat_aktual,Volume) VALUES ('',$id_manifest,".$i.",".$databerat[$j].",'".$datakoli[$j]."') ");


			$j=$j+1;
		}
		//$test = $jumlahkoli;


		echo json_encode(array("success"=>1,"message"=> "Berhasil diinput", "pengiriman" => 'oke'));
	}

	elseif($module=='outbound' AND $act=='update-remarks'){
		//$mysqli->query("update manifest_detail set remarks='$_POST[value]' where id_manifest_detail='$_POST[pk]'");

		mysqli_query($con, "update manifest_detail set remarks='$_POST[value]' where id_manifest_detail='$_POST[pk]'");

		//echo "update manifest_detail set remarks='$_POST[value]' where id_manifest_detail='$_POST[pk]'";
	}
	elseif($module=='outbound' AND $act=='update-manifest-remarks'){
		//$mysqli->query("update manifest_detail set remarks='$_POST[value]' where id_manifest_detail='$_POST[pk]'");

		mysqli_query($con, "update manifest set manifest_remarks='$_POST[value]' where id_manifest='$_POST[pk]'");

		//echo "update manifest_detail set remarks='$_POST[value]' where id_manifest_detail='$_POST[pk]'";
	}

	elseif($module=='outbound' AND $act=='update-berat-smu'){
		//$mysqli->query("update manifest_detail set remarks='$_POST[value]' where id_manifest_detail='$_POST[pk]'");

		mysqli_query($con, "update manifest set berat_smu='$_POST[value]' where id_manifest='$_POST[pk]'");

		//echo "update manifest_detail set remarks='$_POST[value]' where id_manifest_detail='$_POST[pk]'";
	}
	elseif($module=='outbound' AND $act=='update-collie-smu'){
		//$mysqli->query("update manifest_detail set remarks='$_POST[value]' where id_manifest_detail='$_POST[pk]'");

		mysqli_query($con, "update manifest set collie_smu='$_POST[value]' where id_manifest='$_POST[pk]'");

		//echo "update manifest_detail set remarks='$_POST[value]' where id_manifest_detail='$_POST[pk]'";
	}

}
?>