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
	
	$key = 'asdf$1011';
	$encrypt = $_SESSION['id_user'];
	$id = base64_decrypt($encrypt,$key);
	$useraktif = mysqli_query($con, 'select * from user where id_user="'.$id.'"');
	$u = mysqli_fetch_assoc($useraktif);

	$module	= !empty($_GET['module']) ? $_GET['module'] : '';
	$act	= !empty($_GET['act']) ? $_GET['act'] : '';
	
	$aksi="module/mod_delivery/delivery_action.php";
	
	// delivery
	if($module=='delivery' and $act=='detail'){
		$key = "asdf$1011";
		$encrypt = "$_POST[id]";
		$id = base64_decrypt($encrypt,$key);
		$query = mysqli_query($con,"select service, x_intercity(lokasi_tujuan) as intercity, lokasi_tujuan from pengiriman where id_pengiriman='$id'");
		$q = mysqli_fetch_assoc($query);
		echo'
		<link rel="stylesheet" href="plugins/daterangepicker/daterangepicker-bs3.css">
		<form action="'.$aksi.'?module=delivery&act=update" name="modal_popup" method="POST">
			<input type="hidden" name="id" class="form-control" value="'.$id.'" />
			<input type="hidden" name="update_delivery_by" class="form-control" value="'.$u['id_user'].'" />
			';

			if($u['level']=='admin' || $u['level']=='operator' || $u['level']=='network' || $u['level']=='domex'){
				echo'
				<div class="form-group" style="padding-bottom: 5px;">
					<label>Tanggal</label>
					<div class="input-group">
						<div class="input-group-addon">
							<i class="fa fa-calendar"></i>
						</div>
						<input type="text" class="form-control" name="tanggal" value="'.$setdate.'">
					</div>
				</div>
				<div class="form-group" style="padding-bottom: 5px;">
					<label>Nama Kurir</label>
					<select name="delivery_by" class="form-control" width="100%">';
					if($q['service']!=6){
						if($q['intercity']=='Y'){
							$result = mysqli_query($con,"select * from user where level='kurir' or level='driver' and blokir != 'Y' order by namalengkap");
								while ($row = mysqli_fetch_assoc($result)) {
								echo '<option value="' . $row['id_user'] . '">'.strtoupper($row['namalengkap']).'</option>';
							}
						}else{
							$result = mysqli_query($con,"select a.id_user, concat(b.nama_cabang,' ','(',b.alamat1,')',' / ',a.namalengkap) as namalengkap from user a inner join cabang b on b.id_cabang=a.id_cabang inner join kota c on c.id_kota=b.id_kota where (a.level='agent' or a.level='kurir_agent') and blokir != 'Y' and c.id_kota=x_hub('".$q['lokasi_tujuan']."') order by a.id_user desc");
								while ($row = mysqli_fetch_assoc($result)){
								echo '<option value="' . $row['id_user'] . '">'.strtoupper($row['namalengkap']).'</option>';
							}
						}
					}
					else{
						$result = mysqli_query($con,"select * from user where level='kurir' or level='driver' and blokir != 'Y' order by namalengkap");
								while ($row = mysqli_fetch_assoc($result)) {
								echo '<option value="' . $row['id_user'] . '">'.strtoupper($row['namalengkap']).'</option>';
							}
					}
					echo '
					</select>
				</div>';
			}
			echo'
			<div class="form-group" style="padding-bottom: 5px;">
                <label>Nama Penerima</label>
     			<input type="text" name="delivery_name"  class="form-control"/>
			</div>
			<div class="form-group" style="padding-bottom: 5px;">
				<label>Status Penerima</label>
     			<input type="text" name="delivery_status"  class="form-control"/>
			</div>
			<div class="form-group" style="padding-bottom: 5px;">
				<label>Telp. Penerima</label>
     			<input type="text" name="delivery_phone"  class="form-control"/>
			</div>
			<div class="form-group" style="padding-bottom: 5px;">
				<label>Catatan Pengantaran</label>
     			<select name="delivery_note" class="form-control select2">
					<option value=""></option>
					<option value="Alamat Salah">Alamat Salah</option>
					<option value="Penerima Tidak Dikenal">Penerima Tidak Dikenal</option>
					<option value="Rumah Kosong">Rumah Kosong</option>
				</select>
				<p class="help-block">Diisi hanya jika pengantaran gagal.</p>
			</div>

			<div class="modal-footer">
				<button class="btn btn-success" type="submit">Confirm</button>
				<button type="reset" class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Cancel</button>
			</div>
		</form>';
	
	// update
	}elseif($module=='delivery' AND $act=='update'){
		$delivery_name = mysqli_real_escape_string($con, $_POST['delivery_name']);
		$delivery_status = mysqli_real_escape_string($con, $_POST['delivery_status']);
		$delivery_phone = mysqli_real_escape_string($con, $_POST['delivery_phone']);
		$delivery_note = mysqli_real_escape_string($con, $_POST['delivery_note']);
		$tanggal_form = mysqli_real_escape_string($con, $_POST['tanggal']);
		$delivery_date = jin_date_sql($tanggal_form);
		$delivery_by = mysqli_real_escape_string($con, $_POST['delivery_by']);
		$update_delivery_by =  mysqli_real_escape_string($con, $_POST['update_delivery_by']);


		if($u['level']=='kurir' || $u['level']=='driver' || $u['level']=='agent' || $u['level']=='logistics'){
			$query 		= mysqli_query($con, "select 
				id_cabang from user where id_user='$u[id_user]'
				
				");
			$r= mysqli_fetch_assoc($query);

			if(empty($delivery_note)){
				mysqli_query($con, "update pengiriman set delivery_name='$delivery_name',
														delivery_status='$delivery_status',
														delivery_phone='$delivery_phone', 
														delivery='Y',
														status='6',
														delivery_date='$tgl_sekarang',
														delivery_time='$jam_sekarang',
														delivery_by='$u[id_user]',
														courier_date='$tgl_sekarang',
														courier_by='$u[id_user]',
														delivery_by_agent=$r[id_cabang]
								where id_pengiriman='$_POST[id]'");
			}else{

			
				mysqli_query($con, "update pengiriman set delivery_name='$delivery_name',
														delivery_status='$delivery_status',
														delivery_phone='$delivery_phone',
														delivery='Y',
														status='8',
														delivery_date='$tgl_sekarang',
														delivery_time='$jam_sekarang',
														delivery_by='$u[id_user]',
														courier_date='$tgl_sekarang',
														courier_by='$u[id_user]',
														delivery_note='$delivery_note',
														delivery_by_agent=$r[id_cabang]
								where id_pengiriman='$_POST[id]'");
			}
		}elseif($u['level']=='admin' || $u['level']=='operator' || $u['level']=='intercity' || $u['level']=='network' || $u['level']=='domex'){
			$query 		= mysqli_query($con, "select 
				id_cabang from user where id_user='$delivery_by'
				
				");
			$r= mysqli_fetch_assoc($query);
			if(empty($delivery_note)){
				mysqli_query($con, "update pengiriman set delivery_name='$delivery_name',
														delivery_status='$delivery_status',
														delivery_phone='$delivery_phone',
														delivery='Y',
														status='6',
														delivery_date='$delivery_date',
														delivery_time='$jam_sekarang',
														delivery_by='$delivery_by',
														courier_date='$delivery_date',
														courier_by='$delivery_by',
														delivery_by_agent=$r[id_cabang]
								where id_pengiriman='$_POST[id]'");
			}else{
				mysqli_query($con, "update pengiriman set delivery_name='$delivery_name',
														delivery_status='$delivery_status',
														delivery_phone='$delivery_phone',
														delivery='Y',
														status='8',
														delivery_date='$delivery_date',
														delivery_time='$jam_sekarang',
														delivery_by='$delivery_by',
														courier_date='$delivery_date',
														courier_by='$delivery_by',
														delivery_note='$delivery_note',
														delivery_by_agent=$r[id_cabang]
								where id_pengiriman='$_POST[id]'");
			}
		}
		
		header('location:../../main.php?module='.$module);
		
	}elseif($module=='delivery' and $act=='switch'){
		$key = "asdf$1011";
		$encrypt = "$_POST[id]";
		$id = base64_decrypt($encrypt,$key);
		
		$query = mysqli_query($con, "SELECT x_intercity(lokasi_tujuan) as intercity, lokasi_tujuan, id_kustomer FROM pengiriman WHERE id_pengiriman='".$id."'");
		$r = mysqli_fetch_assoc($query);
		
		echo'
		<form action="'.$aksi.'?module=delivery&act=switchkurir" name="modal_popup" method="POST">
			<div class="form-group" style="padding-bottom: 5px;">
			<input type="hidden" name="id" class="form-control" value="'.$id.'" />';
			if($r['intercity']=='Y'){
				echo'
                <label>Nama Kurir/Driver</label>
     			<select name="courier_by" class="form-control">';
				$result = mysqli_query($con,"select id_user, namalengkap, id_kustomer, delivery_area from user where (level='kurir' or level='driver')and blokir='N'");
				while ($row = mysqli_fetch_assoc($result)) {
					echo '<option value="' . $row['id_user'] . '">'.strtoupper($row['namalengkap']).'</option>';
				}
				echo'
				</select>';
			}else{
				echo'
                <label>Network/Agent</label>
     			<select name="courier_by" class="form-control">';
				if($u['level']=='admin' or $u['level']=='operator' or $u['level']=='network' or $u['level']=='domex'){
					$result = mysqli_query($con,"select id_user, namalengkap from user where level='network' and delivery_area like '%".$r['lokasi_tujuan']."%' union select a.id_user, concat(b.nama_cabang,' ','(',b.alamat1,')',' / ',a.namalengkap) as namalengkap from user a inner join cabang b on b.id_cabang=a.id_cabang inner join kota c on c.id_kota=b.id_kota where a.level='agent' and c.id_kota=x_hub('".$r['lokasi_tujuan']."')");
				}else{
					$result = mysqli_query($con,"select a.id_user, concat(b.nama_cabang,' ','(',b.alamat1,')',' / ',a.namalengkap) as namalengkap from user a inner join cabang b on b.id_cabang=a.id_cabang inner join kota c on c.id_kota=b.id_kota where (a.level='agent' or a.level='kurir_agent') and a.id_cabang='".$u['id_cabang']."'");
				}
				while ($row = mysqli_fetch_assoc($result)) {
					echo '<option value="' . $row['id_user'] . '">'.strtoupper($row['namalengkap']).'</option>';
				}
				echo'
				</select>';
			}
			echo'
			</div>
			<div class="modal-footer">
				
				<button class="btn btn-success" type="submit">Confirm</button>
				<button type="reset" class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Cancel</button>
			</div>
		</form>';
	
	}elseif($module=='delivery' AND $act=='switchkurir'){
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

		//update proses
		$courier_by = mysqli_real_escape_string($con, $_POST['courier_by']);
		
		mysqli_query($con, "update pengiriman set courier_by='$courier_by', courier_date='$tgl_sekarang' where id_pengiriman='$_POST[id]'");


		// cek manfiest
		$cek = mysqli_query($con, "SELECT * FROM manifest WHERE tanggal='".$tgl_sekarang."' AND manifest_delivery_by ='".$m_delivery_by."' AND tipe_manifest ='delivery'");
		$ketemu	= mysqli_num_rows($cek);
		$r = mysqli_fetch_assoc($cek);

		if($ketemu==0){
				mysqli_query($con, "INSERT INTO manifest (kode_manifest,tanggal,manifest_delivery_by,tipe_manifest,created_date,created_by) VALUES ('$nextNoTransaksi','$tgl_sekarang','$courier_by','delivery','$tgl_jam','$u[id_user]')");
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

}
?>