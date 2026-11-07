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
		
		$pieces = explode(' ', $_POST['id']);

		$id_pengiriman = $pieces[0];
		$sla = $pieces[1];

		$key = "asdf$1011";
		$encrypt = "$pieces[0]";
		$id = base64_decrypt($encrypt,$key);
		$query = mysqli_query($con,"select airwaybill_kode, service, if(id_kota_tujuan>0,x_inter(id_kota_tujuan),x_intercity(lokasi_tujuan)) as intercity, lokasi_tujuan,id_kota_tujuan, delivery_by_agent,courier_by from pengiriman where id_pengiriman='$id'");
		$q = mysqli_fetch_assoc($query);

		$tujuan = ($q['id_kota_tujuan'] > 0 ? 'id_kota_baru':'id_kota');
		$tujuan_hub = ($q['id_kota_tujuan'] > 0 ? 'x_hubnew':'x_hub');

		echo'

		'.$q['airwaybill_kode'].'
		<link rel="stylesheet" href="plugins/daterangepicker/daterangepicker-bs3.css">
		<form action="'.$aksi.'?module=delivery&act=update" name="modal_popup" method="POST" enctype="multipart/form-data">
			<input type="hidden" name="id" class="form-control" value="'.$id.'" />
			
			';

			
				echo'
				<div class="form-group" style="padding-bottom: 5px;">
					<label>Tanggal '. $setdate.'</label>
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
					if($u['level']=='admin' || $u['level']=='operator' || $u['level']=='admin_network' || $u['level']=='network' || $u['level']=='domex' || $u['level']=='inbound' || $u['id_user'] == 34 ){
							if($q['service']!=6){
								if($q['intercity']=='Y'){
									$result = mysqli_query($con,"select * from user where  blokir != 'Y' and (level='kurir' or level='driver')  order by namalengkap");
										while ($row = mysqli_fetch_assoc($result)) {
										echo '<option value="' . $row['id_user'] . '" '.($row['id_user'] == $q['courier_by'] ? 'selected' : '').'>'.strtoupper($row['namalengkap']).'</option>';
									}
								}else{

									$result = mysqli_query($con,"select a.id_user, concat(b.nama_cabang,' ','(',b.alamat1,')',' / ',a.namalengkap) as namalengkap from user a left join cabang b on b.id_cabang=a.id_cabang left join kota c on c.id_kota=b.id_kota where  blokir != 'Y' and (a.level='agent' or a.level='kurir_agent')


										and blokir != 'Y' 
										 
										 and a.id_cabang='".$q['delivery_by_agent']."'

										order by a.id_user desc");

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
							echo '<option value="56">'.strtoupper($row['namalengkap']). 'Andry HANOMAN LOGISTICS</option>';
						}elseif($u['level']=='agent'){
							$result = mysqli_query($con,"select a.id_user, concat(b.nama_cabang,' ','(',b.alamat1,')',' / ',a.namalengkap) as namalengkap from user a left join cabang b on b.id_cabang=a.id_cabang left join kota c on c.id_kota=b.id_kota where (a.level='agent' or a.level='kurir_agent')


										and blokir != 'Y' 
										 
										 and a.id_cabang='".$q['delivery_by_agent']."'

										order by a.id_user desc");

										while ($row = mysqli_fetch_assoc($result)){
											echo '<option value="' . $row['id_user'] . '">'.strtoupper($row['namalengkap']).'</option>';
										}
						}elseif($u['level']=='kurir_agent' || $u['level'] == 'kurir' || $u['level'] == 'driver'){
							echo '<option value="' . $u['id_user'] . '" selected>'.strtoupper($u['namalengkap']).'</option>';
						}	

							echo '

					</select>
				</div>';
			
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

			';
			if($sla==1){
				
					echo '<div class="form-group" style="padding-bottom: 5px;">
	                	<label>ALASAN TERLAMBAT:</label>
	                	<select name="reason" id="reason" data-placeholder="Choose a Customer..." class="form-control select2" required>
		               		<option value="1">KESALAHAN KURIR AGENT</option>		               		
	                		<option value="2">KESALAHAN KURIR CESCO</option>	                					        		
				        </select>
				        <label>DETAIL ALASAN</label>
				        <input type="text" style="text-transform:uppercase" class="form-control style="width: 100%;" name="reason_manual"  placeholder="Isi hanya jika Alasan diatas tidak ada">
	            	</div>';	
					
				

	         }

	         echo '
	        <div class="form-group">
	        	<label>Foto Penerima* (tidak boleh lebih dari 1 MB, HANYA BISA PNG,JPG,JEPG)</label>
	        	<input type="file" name="gambar" required>
	        </div>
	        <div class="form-group">
	        	<label>Foto Awb* (tidak boleh lebih dari 1 MB, HANYA BISA PNG,JPG,JEPG)</label>
	        	<input type="file" name="gambarawb" required>
	        </div>

	        <div class="row">
		        <div class="form-group">
					<label class="col-md-3 control-label">Kondisi packing saat diterima <small class="required">*</small></label>
					<div class="col-md-6">
						<div class="radio radio-info">
							<label><input type="radio" value="baik" name="kondisi_packing" checked>Baik </label><br>
							<label><input type="radio" value="rusak" name="kondisi_packing" >Rusak </label>
						</div>
										
					</div>
				</div>
			</div>
			<div class="row">
				<div class="form-group">
					<label class="col-md-3 control-label">Kiriman diperiksa saat diterima <small class="required">*</small></label>
					<div class="col-md-6">
						<div class="radio radio-info">
							<label><input type="radio" value="iya" name="kiriman_diperiksa" checked>Diperiksa </label><br>
							<label><input type="radio" value="tidak" name="kiriman_diperiksa" >Tidak </label>
						</div>
										
					</div>
				</div>
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
		$reason_pilih = mysqli_real_escape_string($con, $_POST['reason']);
		$reason_ketik = mysqli_real_escape_string($con, $_POST['reason_manual']);
		$kondisi_packing = mysqli_real_escape_string($con, $_POST['kondisi_packing']);
		$kiriman_diperiksa = mysqli_real_escape_string($con, $_POST['kiriman_diperiksa']);

		if($reason_pilih > 0){
			$diskon_deliv=20;
		}else{
			$diskon_deliv=0;
		}
			

		$pengiriman 		= mysqli_query($con, "select airwaybill_kode
				 from pengiriman where id_pengiriman='$_POST[id]'
				
				");
			$p= mysqli_fetch_assoc($pengiriman);


		if($u['level']=='kurir' || $u['level']=='driver' || $u['level']=='agent' || $u['level']=='logistics' || $u['level']=='kurir_agent'){
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
														delivery_by_agent=$r[id_cabang],
														alasan_terlambat_delivery='$reason_ketik',
														terlambat_delivery='$reason_pilih',
														diskon_deliv=$diskon_deliv,
														kiriman_diperiksa='$kiriman_diperiksa',
														packing_diterima='$kondisi_packing'

								where id_pengiriman='$_POST[id]'");

				echo "update pengiriman set delivery_name='$delivery_name',
														delivery_status='$delivery_status',
														delivery_phone='$delivery_phone', 
														delivery='Y',
														status='6',
														delivery_date='$tgl_sekarang',
														delivery_time='$jam_sekarang',
														delivery_by='$u[id_user]',
														courier_date='$tgl_sekarang',
														courier_by='$u[id_user]',
														delivery_by_agent=$r[id_cabang],
														alasan_terlambat_delivery='$reason_ketik',
														terlambat_delivery='$reason_pilih',
														diskon_deliv=$diskon_deliv,
														kiriman_diperiksa='$kiriman_diperiksa',
														packing_diterima='$kondisi_packing'

								where id_pengiriman='$_POST[id]'";

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
														delivery_by_agent=$r[id_cabang],
														alasan_terlambat_delivery='$reason_ketik',
														terlambat_delivery='$reason_pilih',
														diskon_deliv=$diskon_deliv,
														kiriman_diperiksa=$kiriman_diperiksa,
														packing_diterima=$kondisi_packing

								where id_pengiriman='$_POST[id]'");
			}

			


								echo 'oke';
		}elseif($u['level']=='admin' || $u['level']=='operator' || $u['level']=='intercity' || $u['level']=='network' || $u['level']=='domex' || $u['level']=='inbound' || $u['level']=='admin_network'){
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
														delivery_by='$u[id_user]',
														courier_date='$delivery_date',
														courier_by='$delivery_by',
														delivery_by_agent=$r[id_cabang],
														alasan_terlambat_delivery='$reason_ketik',
														terlambat_delivery='$reason_pilih',
														diskon_deliv=$diskon_deliv
								where id_pengiriman='$_POST[id]'");

				

			}else{
				mysqli_query($con, "update pengiriman set delivery_name='$delivery_name',
														delivery_status='$delivery_status',
														delivery_phone='$delivery_phone',
														delivery='Y',
														status='8',
														delivery_date='$delivery_date',
														delivery_time='$jam_sekarang',
														delivery_by='$u[id_user]',
														courier_date='$delivery_date',
														courier_by='$delivery_by',
														delivery_note='$delivery_note',
														delivery_by_agent=$r[id_cabang],
														alasan_terlambat_delivery='$reason_ketik',
														terlambat_delivery='$reason_pilih',
														diskon_deliv=$diskon_deliv
								where id_pengiriman='$_POST[id]'");
			}
		}

		if($_FILES['gambar']){



			$ekstensi_diperbolehkan	= array('png','jpg','jpeg');
			$nama = $_FILES['gambar']['name'];
			$x = explode('.', $nama);
			$ekstensi = strtolower(end($x));
			$ukuran	= $_FILES['gambar']['size'];
			$file_tmp = $_FILES['gambar']['tmp_name'];	
 
			if(in_array($ekstensi, $ekstensi_diperbolehkan) === true){
				if($ukuran < 9044070){			
					if(move_uploaded_file($file_tmp, '../../reportsnew/delivery_photo/'.$p['airwaybill_kode'].'.jpg')){					
						echo "Foto penerima BERHASIL UPLOAD";
					}
					else{
						echo " Foto penerima gagal upload";
					}
				}else{
					echo 'UKURAN FILE TERLALU BESAR, max 1mb';
				}
			}else{
				echo 'EKSTENSI FILE YANG DI UPLOAD TIDAK DI PERBOLEHKAN';
			}
		}

		if($_FILES['gambarawb']){



			$ekstensi_diperbolehkan	= array('png','jpg','jpeg');
			$nama = $_FILES['gambarawb']['name'];
			$x = explode('.', $nama);
			$ekstensi = strtolower(end($x));
			$ukuran	= $_FILES['gambarawb']['size'];
			$file_tmp = $_FILES['gambarawb']['tmp_name'];	
 
			if(in_array($ekstensi, $ekstensi_diperbolehkan) === true){
				if($ukuran < 9044070){			
					if(move_uploaded_file($file_tmp, '../../reportsnew/awbdelivery_photo/'.$p['airwaybill_kode'].'.jpg')){					
						echo "Foto AWB BERHASIL UPLOAD";
					}
					else{
						echo "Foto AWB  gagal";
					}
				}else{
					echo 'Foto AWB  UKURAN FILE TERLALU BESAR, max 1mb';
				}
			}else{
				echo 'Foto AWB  EKSTENSI FILE YANG DI UPLOAD TIDAK DI PERBOLEHKAN';
			}
		}
		
		header('location:../../main.php?module='.$module);
		
	}elseif($module=='delivery' and $act=='switch'){
		$key = "asdf$1011";
		$encrypt = "$_POST[id]";
		$id = base64_decrypt($encrypt,$key);
		
		$query = mysqli_query($con, "SELECT if(id_kota_tujuan>0,x_inter(id_kota_tujuan),x_intercity(lokasi_tujuan)) as intercity, service,lokasi_tujuan,id_kota_tujuan, id_kustomer FROM pengiriman WHERE id_pengiriman='".$id."'");
		$r = mysqli_fetch_assoc($query);
		
		echo'
		<form action="'.$aksi.'?module=delivery&act=switchkurir" name="modal_popup" method="POST">
			<div class="form-group" style="padding-bottom: 5px;">
			<input type="hidden" name="id" class="form-control" value="'.$id.'" />';
			if($r['intercity']=='Y' || $r['service'] == 6){
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
				if($u['level']=='admin' or $u['level']=='operator' or $u['level']=='network' or $u['level']=='inbound' or $u['level']=='domex' || $u['id_user'] == 34){
					$result = mysqli_query($con,"select a.id_user, concat(b.nama_cabang,' ','(',b.alamat1,')',' / ',a.namalengkap) as namalengkap from user a left join cabang b on b.id_cabang=a.id_cabang left join regencies c on c.id=b.id_kota_baru where (a.level='agent' or a.level='kurir_agent')and a.blokir != 'Y' and c.id=x_hubnew('".$r['id_kota_tujuan']."')");
				}else{
					$result = mysqli_query($con,"select a.id_user, concat(b.nama_cabang,' ','(',b.alamat1,')',' / ',a.namalengkap) as namalengkap from user a left join cabang b on b.id_cabang=a.id_cabang left join regencies c on c.id=b.id_kota_baru where (a.level='agent' or a.level='kurir_agent') and a.blokir != 'Y' and a.id_cabang='".$u['id_cabang']."'");
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
	elseif ($module=='delivery' AND $act=='plus'){
		$tanggal		= jin_date_sql($_POST['tanggalplus']);
		$awb			= $_POST['selectawb'];
		$id_awb			= implode(',',$awb);
		$alasan 		= $_POST['alasan_plus'];

		mysqli_query($con, "update pengiriman set courier_date='$tanggal',alasan_plus_delivery='$alasan' where id_pengiriman in ($id_awb)");


		header('location:../../main.php?module='.$module);
	}

	elseif ($module=='delivery' AND $act=='wakilkan'){
		
		$secret = $_GET['id_pengiriman'];
		$key = "asdf$1011";
		 $id_pengiriman = base64_decrypt($secret,$key);

		mysqli_query($con, "update pengiriman set wakilkan_deliv='Y' where id_pengiriman = $id_pengiriman");
		header('location:../../main.php?module='.$module);
		
	}

	elseif ($module=='delivery' AND $act=='backtoinbound'){
		
		$secret = $_GET['id_pengiriman'];
		$key = "asdf$1011";
		 $id_pengiriman = base64_decrypt($secret,$key);
		// echo 'masuk';
		mysqli_query($con, "update pengiriman set status='3' ,manifest = 'N',manifest_received= null,delivery_date=null,delivery_time = null, courier_date = null, courier_by = null, delivery_by = null where id_pengiriman = $id_pengiriman");
		header('location:../../main.php?module='.$module);
		
	}

}
?>