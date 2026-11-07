<?php
if(!isset($_SESSION)){
    session_start();
}

if (empty($_SESSION['username']) AND empty($_SESSION['password'])){
	header('location:../application/login.php');
}else{
	include "../../config/connection.php";
	include "../../config/library.php";
	include "../../config/encrypt.php";
	
	$key = 'asdf$1011';
	$encrypt = $_SESSION['id_user'];
	$id = base64_decrypt($encrypt,$key);
	$useraktif = mysqli_query($con, 'select * from user where id_user="'.$id.'"');
	$u = mysqli_fetch_assoc($useraktif);

	$module	= !empty($_GET['module']) ? $_GET['module'] : '';
	$act	= !empty($_GET['act']) ? $_GET['act'] : '';
	
	$aksi="module/mod_manifest/manifest_action.php";
	
	if($module=='manifest' and $act=='data'){
		if($u['level']=='agent'){
		$agent = mysqli_query($con, 'select id_kota from cabang where id_cabang='.$u['id_cabang'].'');
		$a = mysqli_fetch_assoc($agent);
		$table = 
<<<EOT
	(
	select 
		a.airwaybill_kode,
		a.created_date,
		x_namakustomer(a.id_kustomer) as nama_kustomer,
		x_namakotabesar(a.lokasi_asal) as kota_asal,
		case when service='6' then x_namanegara(lokasi_tujuan) else x_namakotabesar(lokasi_tujuan) end as kota_tujuan,
		x_service(a.service) as service,
		a.id_pengiriman
	from pengiriman a inner join kota b on b.id_kota=a.lokasi_asal where a.status='4' and a.pickup='Y' and a.courier_by is null and (a.lokasi_tujuan=$a[id_kota] or b.hub=$a[id_kota]) and status!='7' and service != 6
	) temp
EOT;
		}

		elseif($u['level']=='admin' || $u['tambahan_admin'] == '1'){
		$table = 
<<<EOT
	(
	select 
		airwaybill_kode,
		created_date,
		x_namakustomer(id_kustomer) as nama_kustomer,
		x_namakotabesar(lokasi_asal) as kota_asal,
		case when service='6' then x_namanegara(lokasi_tujuan) else x_namakotabesar(lokasi_tujuan) end as kota_tujuan,
		x_service(service) as service,
		id_pengiriman
	from pengiriman where pickup='Y' and delivery='N' and courier_by is null and status!='7' and case when x_intercity(lokasi_asal)='Y' and x_intercity(lokasi_tujuan)='Y' then status='2' when x_intercity(lokasi_asal)='Y' and x_intercity(lokasi_tujuan)!='Y' then status='4' when x_intercity(lokasi_asal)!='Y' and x_intercity(lokasi_tujuan)='Y' then status='4' when x_intercity(lokasi_asal)!='Y' and x_intercity(lokasi_tujuan)='Y' then status='4' and service != 6 end
	) temp
EOT;
		}elseif($u['level']=='operator' or $u['level']=='intercity' || strpos($u['menu_tambahan'], 'manifest') !== false){
		$table = 
<<<EOT
	(
	select 
		airwaybill_kode,
		created_date,
		x_namakustomer(id_kustomer) as nama_kustomer,
		x_namakotabesar(lokasi_asal) as kota_asal,
		case when service='6' then x_namanegara(lokasi_tujuan) else x_namakotabesar(lokasi_tujuan) end as kota_tujuan,
		x_service(service) as service,
		id_pengiriman
	from pengiriman where pickup='Y' and delivery='N' and courier_by is null and status!='7' and case when x_intercity(lokasi_asal)='Y' and x_intercity(lokasi_tujuan)='Y' then status='2' when x_intercity(lokasi_asal)='Y' and x_intercity(lokasi_tujuan)!='Y' then status='4' when x_intercity(lokasi_asal)!='Y' and x_intercity(lokasi_tujuan)='Y' then status='4' end
	and x_intercity(lokasi_tujuan)='Y' and service != 6
	) temp
EOT;
		}elseif($u['level']=='network'){
		$table = 
<<<EOT
	(
	select 
		airwaybill_kode,
		a.created_date,
		x_namakustomer(id_kustomer) as nama_kustomer,
		x_namakotabesar(lokasi_asal) as kota_asal,
		case when service='6' then x_namanegara(lokasi_tujuan) else x_namakotabesar(lokasi_tujuan) end as kota_tujuan,
		x_service(service) as service,
		id_pengiriman
	from pengiriman a 
			inner join kota b on b.id_kota=a.lokasi_tujuan 
	where status='4' and pickup='Y' and service != 6 and courier_by is null and b.kode_pulau in ($u[area_pulau])  and status!='7' and service != 6 and x_intercity(a.lokasi_tujuan)!='Y'
	) temp
EOT;
		}elseif($u['level']=='domex'){
		
		$table = 
<<<EOT
	(
	select 
		airwaybill_kode,
		created_date,
		x_namakustomer(id_kustomer) as nama_kustomer,
		x_namakotabesar(lokasi_asal) as kota_asal,
		case when service='6' then x_namanegara(lokasi_tujuan) else x_namakotabesar(lokasi_tujuan) end as kota_tujuan,
		x_service(service) as service,
		id_pengiriman
	from pengiriman where status='4' and pickup='Y' and courier_by is null and x_intercity(lokasi_tujuan)!='Y' and status!='7'and service != 6
	) temp
EOT;

		}

		
		$primaryKey = 'id_pengiriman';

		$columns = array(
			array('db'		=> 'airwaybill_kode',
					'dt'	=> 0,
					'formatter'	=> function($d,$row){
						return '<td><center>'.$d.'</center></td>';
					}
			),
			array('db'	=> 'created_date',
					'dt'		=> 1,
					'formatter' => function( $d, $row ) {
						return date( 'd-m-Y H:i', strtotime($d));
					}
			),
			array('db'			=> 'nama_kustomer', 'dt'=> 2 ),
			array('db'			=> 'kota_asal',
					'dt'		=> 3,
					'formatter' => function($d,$row){
						if($d=='Jakarta Selatan' || $d=='Jakarta Timur' || $d=='Jakarta Barat' || $d=='Jakarta Utara' || $d=='Jakarta Pusat'){
							$kota_asal = 'Jakarta';
						}else{
							$kota_asal = $d;
						}
						return '<td>'.strtoupper($kota_asal).'</td>';
					}
			),
			array('db'			=> 'kota_tujuan',
					'dt'		=> 4,
					'formatter'	=> function($d,$row){
						if($d=='Jakarta Selatan' || $d=='Jakarta Timur' || $d=='Jakarta Barat' || $d=='Jakarta Utara' || $d=='Jakarta Pusat'){
							$kota_tujuan = 'Jakarta';
						}else{
							$kota_tujuan = $d;
						}
						return '<td>'.strtoupper($kota_tujuan).'</td>';
					}
			),
			array( 'db' => 'service', 'dt'=> 5 ),
			array(
				  'db'        => 'id_pengiriman',
				  'dt'        => 6,
				  'formatter' => function( $d, $row ) {
					$key = "asdf$1011";
					return '<td><center><button class="btn btn-xs btn-primary open_manifest-delivery" data-id="'.base64_encrypt($d,$key).'">Assignment</button></center></td>';
				  }
			)

		);

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
	
	// manifest delivery assignment
	}elseif($module=='manifest' and $act=='assignment'){
		$key = "asdf$1011";
		$encrypt = "$_POST[id]";
		$id = base64_decrypt($encrypt,$key);
		
		$query = mysqli_query($con, "SELECT x_intercity(lokasi_tujuan) as intercity, lokasi_tujuan, id_kustomer FROM pengiriman WHERE id_pengiriman='".$id."'");
		$r = mysqli_fetch_assoc($query);
		
		if($u['level']=='admin' or $u['level']=='operator' or $u['level']=='intercity' or $u['level']=='network' or $u['level']=='domex'){
		echo'
		<form action="'.$aksi.'?module=manifest&act=update" name="modal_popup" method="POST">
				<input type="hidden" name="id" class="form-control" value="'.$id.'" />';
		if($r['intercity']=='Y'){
			echo'
			<div class="form-group" style="padding-bottom: 5px;">
                <label>Assignment to:</label>
     			<select name="courier_by" class="form-control">';
				$result = mysqli_query($con,"select id_user, namalengkap from user where (level='kurir' or level='driver') AND blokir != 'Y' order by namalengkap");
				while ($row = mysqli_fetch_assoc($result)){
					echo '<option value="'.$row['id_user'].'">'.strtoupper($row['namalengkap']).'</option>';
				}
				echo'
				</select>
			</div>';
		}else{
			echo'
				<label>Assignment to:</label>
     			<select name="courier_by" class="form-control select2" width="100%">';
					$result = mysqli_query($con,"select a.id_user, concat(b.nama_cabang,' ','(',b.alamat1,')',' / ',a.namalengkap) as namalengkap from user a inner join cabang b on b.id_cabang=a.id_cabang inner join kota c on c.id_kota=b.id_kota where (a.level='agent' or a.level='kurir_agent') AND a.blokir != 'Y' and c.id_kota=x_hub('".$r['lokasi_tujuan']."') and b.status='Y' order by a.id_user desc ");
					while ($row = mysqli_fetch_assoc($result)) {
						echo '<option value="' . $row['id_user'] . '">'.strtoupper($row['namalengkap']).'</option>';
					}
					echo '
				</select>';
		}
		echo'
		<div class="form-group" style="padding-bottom: 5px;">
                <label>Manifest for next day '.date('Y-m-d',strtotime(' +1 day')).'</label>
                <input type="checkbox" name="nextday" value="1"> *abaikan jika tidak  

                
        </div>
        <input type="hidden" name="m_date" class="form-control" value="'.date('Y-m-d',strtotime(' +1 day')).'" />

			<div class="modal-footer">
				<button class="btn btn-success" type="submit">Confirm</button>
				<button type="reset" class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Cancel</button>
			</div>
		</form>';
		}
		
	// update delivery
	}elseif ($module=='manifest' AND $act=='update'){

		//library buat kode manifest otomatis
		$today = date("Ymd");
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

		//cek SET TANGGAL

		$datetime = new DateTime('tomorrow');
		$tomorrow =  $datetime->format('Y-m-d');
		
		$tgl_jam = date("Y-m-d H:i:s"); 

		$format_awal = "Y-m-d 19:00:00";
		$batas_awal = date($format_awal);
		$format_ahir = "Y-m-d 23:59:59";
		$batas_ahir = date($format_ahir);

		$nextday = mysqli_real_escape_string($con, $_POST['nextday']);

		if ($tgl_jam >= $batas_awal && $tgl_jam <= $batas_ahir) {
			$set_tgl = $tomorrow;
		}
		else{
			if($nextday == 1){
				$set_tgl = $tomorrow;
			}else{
				$set_tgl = $tgl_sekarang;
			}

		}
		
		if($u['level']=='admin' || $u['level']=='operator'){
			mysqli_query($con,"UPDATE pengiriman set courier_by='" . $_POST["courier_by"] . "', courier_date='$set_tgl', status='5' WHERE id_pengiriman='" . $_POST["id"] . "'");
			$m_delivery_by = $_POST["courier_by"];
		}else{
			mysqli_query($con,"UPDATE pengiriman set courier_by='" . $_POST["courier_by"] . "', courier_date='$set_tgl', status='5' WHERE id_pengiriman='" . $_POST["id"] . "'");
			$m_delivery_by = $_POST["courier_by"];
		}



		$cek = mysqli_query($con, "SELECT * FROM manifest WHERE tanggal='".$set_tgl."' AND manifest_delivery_by ='".$m_delivery_by."' AND tipe_manifest ='delivery'");
		$ketemu	= mysqli_num_rows($cek);
		$r = mysqli_fetch_assoc($cek);
		
		if($ketemu==0){
				mysqli_query($con, "INSERT INTO manifest (kode_manifest,tanggal,manifest_delivery_by,tipe_manifest,created_date,created_by) VALUES ('$nextNoTransaksi','$set_tgl','$m_delivery_by','delivery','$tgl_jam','$u[id_user]')");
				$id_manifest = mysqli_insert_id($con);
				// insert to manifest detail
				mysqli_query($con, "insert into manifest_detail (id_manifest,id_pengiriman) values ('$id_manifest','". $_POST["id"] ."')");
				// update pengiriman
				//mysqli_query($con, "update pengiriman set status='3', berat_aktual='$berat_aktual', collie='$collie', pcs='$pcs', packing_kayu='$packing_kayu', transit='N' where id_pengiriman='$_POST[id]'");
		}	
		else{
			mysqli_query($con, "insert into manifest_detail (id_manifest,id_pengiriman) values ('$r[id_manifest]','". $_POST["id"] ."')");
		}
		
		header('location:../../main.php?module='.$module);
	}
	elseif ($module=='manifest' AND $act=='test') {
		$datetime = new DateTime('tomorrow');
		$tomorrow =  $datetime->format('Y-m-d');
		
		$tgl_jam = date("Y-m-d H:i:s"); 

		$format_awal = "Y-m-d 16:00:00";
		$batas_awal = date($format_awal);
		$format_ahir = "Y-m-d 23:59:59";
		$batas_ahir = date($format_ahir);

		if ($tgl_jam >= $batas_awal && $tgl_jam <= $batas_ahir) {
			$set_tgl = $tomorrow;
		}
		else{
			$set_tgl = $tgl_sekarang;
		}
		
		
			echo "UPDATE pengiriman set courier_by='" . $_POST["courier_by"] . "', courier_date='$set_tgl', status='5' WHERE id_pengiriman='" . $_POST["id"] . "'";
		



		echo "SELECT * FROM manifest WHERE tanggal='".$set_tgl."' AND manifest_delivery_by ='".$m_delivery_by."' AND tipe_manifest ='delivery'";
		
		
				echo "INSERT INTO manifest (kode_manifest,tanggal,manifest_delivery_by,tipe_manifest,created_date,created_by) VALUES ('$nextNoTransaksi','$set_tgl','$m_delivery_by','delivery','$tgl_jam','$u[id_user]')";
		
	}
}
?>