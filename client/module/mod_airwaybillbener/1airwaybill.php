<section class="content-header">
	<h1>Airwaybill<small></small></h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-home"></i> Home</a></li>
		<li><a href="#">Application</a></li>
		<li class="active">Airwaybill</li>
	</ol>
</section>
<?php
if(!isset($_SESSION)){
    session_start();
}

if (empty($_SESSION['usernamex']) AND empty($_SESSION['passwordx'])){
	header('location:../main.php?module=home');
}
else{
	$act = !empty($_GET['act']) ? $_GET['act'] : '';
		
	switch($act){
			
		default:
		echo'
		<section class="content">
			<div class="row">
				<div class="col-xs-12">
					<div class="box box-primary">
						<div class="box-body">
							<table id="airwaybill" class="table table-bordered table-striped table-hover" width="100%" cellspacing="0" style="white-space:nowrap;">
								<thead>
									<tr>
										<th>Airwaybill</th>
										<th>Booking Time</th>
										<th>Customer</th>
										<th>Origin</th>
										<th>Destination</th>
										<th>Commodity</th>
										<th>Status</th>
										<th>Pickup Date</th>
										<th>Pickup By</th>
										<th>Delivery Date</th>
										<th>Delivery By</th>
										<th>Recipient</th>
										<th width="50px">Action</th>
									</tr>
								</thead>
								<tbody>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</section>';
			
		break;

		case "outbound":
			
			$key = "asdf$1011";
			$encrypt = "$_GET[id]";
			$id = base64_decrypt($encrypt,$key);

			$query = mysqli_query($con, "select *,SUBSTRING_INDEX(ukuran_barang,'x',1) as length, SUBSTRING_INDEX(SUBSTRING_INDEX(ukuran_barang,'x',2),'x',-1) as width, SUBSTRING_INDEX(ukuran_barang,'x',-1) as height, DATE_FORMAT(created_date,'%Y%m%d') as folder from pengiriman where id_pengiriman='$id'");
			$r = mysqli_fetch_assoc($query);
			
			$tanggal = tgl_indo($r['tanggal']);
									
			echo'
			<section class="content">
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">Update</h3>
						<div class="box-tools pull-right">
							<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
							<button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
						</div>
					</div><!-- /.box-header -->
					<form method="post" action="'.$aksi.'?module=booking&act=update" class="form-horizontal form-bordered" id="form-booking" autocomplete="off">
					<input type="hidden" name="id" value="'.$r['id_pengiriman'].'">
					<div class="box-body">
						<div class="form-group">
							<label class="col-sm-3 control-label full-right">Tanggal <span class="required">* </span></label>
							<div class="col-sm-3">
								<div class="input-group">
									<div class="input-group-addon">
										<i class="fa fa-calendar"></i>
									</div>
									<input type="text" class="form-control" id="tanggal" name="tanggal" value="'.$tanggal.'" readonly>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label">Airwaybill <span class="required">* </span></label>
							<div class="col-md-6"><input type="text" name="airwaybill_kode" class="form-control" value="'.$r['airwaybill_kode'].'"></div>
						</div>
						<hr>
						<div class="form-group">
							<label class="col-md-3 control-label">Pengirim <span class="required">* </span></label>
							<div class="col-md-6">
								<select name="id_kustomer" id="id_kustomer" data-placeholder="Choose a Customer..." class="form-control select2">';
								$result = mysqli_query($con,"select id_kustomer, nama_kustomer from kustomer order by nama_kustomer");
								echo '<option value="">Select...</option>';
								while ($row = mysqli_fetch_assoc($result)) {
									if($r['id_kustomer']==$row['id_kustomer']){
										echo '<option value="' . $row['id_kustomer'] . '" selected>' . $row['nama_kustomer'] . '</option>';
									}else{
										echo '<option value="' . $row['id_kustomer'] . '">' . $row['nama_kustomer'] . '</option>';
									}
								}
								echo'
								</select>
								<div class="val-message"></div>
								<input type="text" name="nama_pengirim" placeholder="Nama pengirim..." class="form-control" value="'.$r['nama_pengirim'].'">
								<textarea name="alamat_pengirim" placeholder="Alamat pengirim..." id="alamat_pengirim" class="form-control" rows="2">'.$r['alamat_pengirim'].'</textarea>
								<input type="text" name="hp_pengirim" placeholder="No. Handphone..." class="form-control" value="'.$r['hp_pengirim'].'">';
								$loccode = mysqli_query($con, 'select loc_code from kustomer where id_kustomer="'.$r['id_kustomer'].'"');
								$l = mysqli_fetch_assoc($loccode);
								if($l['loc_code']=='Y'){
									echo'
									<input type="text" id="loc_code" name="loc_code" placeholder="Loc Code..." class="form-control" value="'.$r['loc_code'].'">';
								}
								echo'
								<input type="hidden" name="lokasi_asal" id="id_kota" value="'.$r['lokasi_asal'].'">
								<input type="hidden" name="id_cabang" id="id_cabang" value="'.$r['id_cabang'].'">
								<input type="hidden" name="intercityasal" id="intercityasal">
							</div>
						</div>
						<hr>
						<div class="form-group">
							<label class="col-md-3 control-label">Penerima <span class="required">* </span></label>
							<div class="col-md-6">
								<input type="text" name="nama_penerima" id="nama_penerima" placeholder="Nama penerima..." class="form-control" value="'.$r['nama_penerima'].'">
								<textarea name="alamat_penerima" id="alamat_penerima" placeholder="Alamat penerima..." class="form-control" rows="2">'.$r['alamat_penerima'].'</textarea>
								<input type="text" name="hp_penerima" id="hp_penerima" placeholder="No. Handphone..." class="form-control" value="'.$r['hp_penerima'].'">
								<input type="text" name="perusahaan_penerima" id="perusahaan_penerima" placeholder="Perusahaan..." class="form-control" value="'.$r['perusahaan_penerima'].'">
							</div>
						</div>
						<hr>
						<div class="form-group">
							<label class="col-md-3 control-label">Kota Tujuan <span class="required">* </span></label>
							<div class="col-md-6">
								<select name="lokasi_tujuan" id="lokasi_tujuan" data-placeholder="Choose a City..." class="form-control select2">
									<option value="">Select...</option>';
									$kotatujuan = mysqli_query($con, "select a.id_kota, case when b.nama_kota is null then a.nama_kota when x_namakota(b.induk) is null then concat(a.nama_kota,', ',b.nama_kota) else concat(a.nama_kota,', ',b.nama_kota,', ',x_namakota(b.induk)) end as nama_kota from kota a left join kota b on b.id_kota=a.induk where a.kota_tujuan='Y'");
									while($t=mysqli_fetch_assoc($kotatujuan)){
										if($t['id_kota']==$r['lokasi_tujuan']){
											echo'
											<option value="'.$t['id_kota'].'" selected>'.$t['nama_kota'].'</option>';
										}else{
											echo'
											<option value="'.$t['id_kota'].'">'.$t['nama_kota'].'</option>';
										}
									}
									echo'
								</select>
								<input type="hidden" name="intercitytujuan" id="intercitytujuan">
								<div class="val-message"></div>
							</div>
						</div>
						<hr>
						<div class="form-group">
							<label class="control-label col-md-3">Tipe Service <span class="required">* </span></label>
							<div class="col-md-6">
								<div class="radio radio-info">';
								$service = mysqli_query($con, "select id_pelayanan, nama_pelayanan from pelayanan where active='Y'");
								while($s=mysqli_fetch_assoc($service)){
									if($r['service']==$s['id_pelayanan']){
										echo'
										<label><input type="radio" name="service" value="'.$s['id_pelayanan'].'" checked/> '.$s['nama_pelayanan'].' </label><br>';
									}else{
										echo'
										<label><input type="radio" name="service" value="'.$s['id_pelayanan'].'"/> '.$s['nama_pelayanan'].' </label><br>';
									}
								}
								echo'
								</div>
							</div>
						</div>
						<hr>
						<div class="form-group">
							<label class="col-md-3 control-label">Komoditi <small class="required">*</small></label>
							<div class="col-md-6">
								<div class="radio radio-info">';
								if($r['komoditi']=='1'){
									echo'
									<label><input type="radio" value="1" name="komoditi" id="yesCheck1" checked> Paket</label><br>
									<label><input type="radio" value="2" name="komoditi" id="yesCheck2"> Dokumen</label>';
								}else{
									echo'
									<label><input type="radio" value="1" name="komoditi" id="yesCheck1"> Paket</label><br>
									<label><input type="radio" value="2" name="komoditi" id="yesCheck2" checked> Dokumen</label>';
								}
								echo'
								</div>
							</div>
						</div>';
						if($r['komoditi']=='1'){
						echo'
						<div id="ifYes">
							<div class="form-group">
								<label class="col-md-3 control-label">Jenis Barang</label>
								<div class="col-md-6">
									<input type="text" name="jenis_barang" class="form-control" value="'.$r['jenis_barang'].'">
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label">Estimasi Berat Barang</label>
								<div class="col-md-6">
									<div class="input-group">
										<input type="text" name="berat_barang" class="form-control" value="'.$r['berat_barang'].'"> <span class="input-group-addon">Kg</span>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label">Ukuran Barang</label>
								<div class="col-md-6">
									<div class="row">
										<div class="col-md-4">
											<div class="input-group">
												<input type="text" name="length" placeholder="Length..." class="form-control number" value="'.$r['length'].'"> <span class="input-group-addon">cm</span>
											</div>
										</div>
										<div class="col-md-4">
											<div class="input-group">
												<input type="text" name="width" placeholder="Width..." class="form-control number" value="'.$r['width'].'"> <span class="input-group-addon">cm</span>
											</div>
										</div>
										<div class="col-md-4">
											<div class="input-group">
												<input type="text" name="height" placeholder="Height..." class="form-control number" value="'.$r['height'].'"> <span class="input-group-addon">cm</span>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label">Jumlah Collie/Pcs</label>
								<div class="col-md-6">
									<input type="text" name="jumlah_collie" class="form-control number" value="'.$r['collie'].'">
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label">Packing</label>
								<div class="col-md-6">
									<div class="radio radio-info">';
									if($r['packing_kayu']=='1'){
										echo'
										<label><input type="radio" value="1" name="packing_kayu" checked> Packing Oleh Pemilik</label><br>
										<label><input type="radio" value="2" name="packing_kayu"> Packing Kayu Oleh Cesco Log.</label><br>
										<label><input type="radio" value="3" name="packing_kayu"> Non Packing Kayu Oleh Cesco Log.</label>';
									}elseif($r['packing_kayu']=='2'){
										echo'
										<label><input type="radio" value="1" name="packing_kayu"> Packing Oleh Pemilik</label><br>
										<label><input type="radio" value="2" name="packing_kayu" checked> Packing Kayu Oleh Cesco Log.</label><br>
										<label><input type="radio" value="3" name="packing_kayu"> Non Packing Kayu Oleh Cesco Log.</label>';
									}else{
										echo'
										<label><input type="radio" value="1" name="packing_kayu"> Packing Oleh Pemilik</label><br>
										<label><input type="radio" value="2" name="packing_kayu"> Packing Kayu Oleh Cesco Log.</label><br>
										<label><input type="radio" value="3" name="packing_kayu" checked> Non Packing Kayu Oleh Cesco Log.</label>';
									}
									echo'
									</div>
								</div>
							</div>
						</div>';
						}else{
						echo'
						<div id="ifYes" style="display:none">
							<div class="form-group">
								<label class="col-md-3 control-label">Jenis Barang</label>
								<div class="col-md-6">
									<input type="text" name="jenis_barang" class="form-control" value="'.$r['jenis_barang'].'">
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label">Estimasi Berat Barang</label>
								<div class="col-md-6">
									<div class="input-group">
										<input type="text" name="berat_barang" class="form-control" value="'.$r['berat_barang'].'"> <span class="input-group-addon">Kg</span>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label">Ukuran Barang</label>
								<div class="col-md-6">
									<div class="row">
										<div class="col-md-4">
											<div class="input-group">
												<input type="text" name="length" placeholder="Length..." class="form-control number" value="'.$r['length'].'"> <span class="input-group-addon">cm</span>
											</div>
										</div>
										<div class="col-md-4">
											<div class="input-group">
												<input type="text" name="width" placeholder="Width..." class="form-control number" value="'.$r['width'].'"> <span class="input-group-addon">cm</span>
											</div>
										</div>
										<div class="col-md-4">
											<div class="input-group">
												<input type="text" name="height" placeholder="Height..." class="form-control number" value="'.$r['height'].'"> <span class="input-group-addon">cm</span>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label">Jumlah Collie/Pcs</label>
								<div class="col-md-6">
									<input type="text" name="jumlah_collie" class="form-control number" value="'.$r['collie'].'">
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label">Packing</label>
								<div class="col-md-6">
									<div class="radio radio-info">';
									if($r['packing_kayu']=='1'){
										echo'
										<label><input type="radio" value="1" name="packing_kayu" checked> Packing Oleh Pemilik</label><br>
										<label><input type="radio" value="2" name="packing_kayu"> Packing Kayu Oleh Cesco Log.</label><br>
										<label><input type="radio" value="3" name="packing_kayu"> Non Packing Kayu Oleh Cesco Log.</label>';
									}elseif($r['packing_kayu']=='2'){
										echo'
										<label><input type="radio" value="1" name="packing_kayu"> Packing Oleh Pemilik</label><br>
										<label><input type="radio" value="2" name="packing_kayu" checked> Packing Kayu Oleh Cesco Log.</label><br>
										<label><input type="radio" value="3" name="packing_kayu"> Non Packing Kayu Oleh Cesco Log.</label>';
									}else{
										echo'
										<label><input type="radio" value="1" name="packing_kayu"> Packing Oleh Pemilik</label><br>
										<label><input type="radio" value="2" name="packing_kayu"> Packing Kayu Oleh Cesco Log.</label><br>
										<label><input type="radio" value="3" name="packing_kayu" checked> Non Packing Kayu Oleh Cesco Log.</label>';
									}
									echo'
									</div>
								</div>
							</div>
						</div>';
						}
						echo'
						<hr>
						<div class="form-group">
							<label class="col-md-3 control-label">Asuransi <small class="required">*</small></label>
							<div class="col-md-6">
								<div class="radio radio-info">';
								if($r['asuransi']=='Y'){
									echo'
									<label><input type="radio" value="Y" name="asuransi" id="yesAsuransi" checked>Ya </label><br>
									<label><input type="radio" value="N" name="asuransi" id="noAsuransi">Tidak </label>';
								}else{
									echo'
									<label><input type="radio" value="Y" name="asuransi" id="yesAsuransi">Ya </label><br>
									<label><input type="radio" value="N" name="asuransi" id="noAsuransi" checked>Tidak </label>';
								}
								echo'
								</div>
							</div>
						</div>';
						if($r['asuransi']=='Y'){
						echo'
						<div id="ifAsuransi">
							<div class="form-group">
								<label class="col-md-3 control-label">Input Nilai Barang</label>
								<div class="col-md-6">
									<div class="input-group m-b">
										<span class="input-group-addon">Rp</span> <input type="text" name="nilai_barang" class="form-control number" value="'.number_format($r['nilai_barang']).'">
									</div>
								</div>
							</div>
						</div>';
						}else{
						echo'
						<div id="ifAsuransi" style="display:none">
							<div class="form-group">
								<label class="col-md-3 control-label">Input Nilai Barang</label>
								<div class="col-md-6">
									<div class="input-group m-b">
										<span class="input-group-addon">Rp</span> <input type="text" name="nilai_barang" class="form-control number" value="'.number_format($r['nilai_barang']).'">
									</div>
								</div>
							</div>
						</div>';
						}
						echo'
						<hr>
						<div class="form-group">
							<label class="col-md-3 control-label">Surat Jalan Kembali</label>
							<div class="col-md-6">
								<div class="radio radio-info">';
								if($r['surat_jalan']=='Y'){
									echo'
									<label><input type="radio" value="Y" name="surat_jalan" checked>Ya </label><br>
									<label><input type="radio" value="N" name="surat_jalan">Tidak </label>';
								}else{
									echo'
									<label><input type="radio" value="Y" name="surat_jalan">Ya </label><br>
									<label><input type="radio" value="N" name="surat_jalan" checked>Tidak </label>';
								}
								echo'
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label">Catatan Khusus Kiriman</label>
							<div class="col-md-6">
								<textarea name="catatan" placeholder="Catatan khusus kiriman ..." class="form-control" rows="2">'.$r['catatan'].'</textarea>
							</div>
						</div>
						<hr>
						<div class="form-group">
							<label class="col-md-3 control-label">Pembayaran Pengiriman<br/>
							<small class="required">Dilakukan Oleh</small></label>
							<div class="col-md-6">
								<div class="radio radio-info">';
								if($r['biaya_beban']=='1'){
									echo'
									<label><input type="radio" value="1" name="biaya_beban" id="pengirim" checked>Pengirim </label><br>
									<label><input type="radio" value="2" name="biaya_beban" id="penerima"> Penerima</label><br>
									<label><input type="radio" value="3" name="biaya_beban" id="pihak3"> Pihak Ketiga </label><br>
									<label><input type="radio" value="4" name="biaya_beban" id="tunai"> Tunai</label>';
								}elseif($r['biaya_beban']=='2'){
									echo'
									<label><input type="radio" value="1" name="biaya_beban" id="pengirim">Pengirim </label><br>
									<label><input type="radio" value="2" name="biaya_beban" id="penerima" checked> Penerima</label><br>
									<label><input type="radio" value="3" name="biaya_beban" id="pihak3"> Pihak Ketiga </label><br>
									<label><input type="radio" value="4" name="biaya_beban" id="tunai"> Tunai</label>';
								}elseif($r['biaya_beban']=='3'){
									echo'
									<label><input type="radio" value="1" name="biaya_beban" id="pengirim">Pengirim </label><br>
									<label><input type="radio" value="2" name="biaya_beban" id="penerima"> Penerima</label><br>
									<label><input type="radio" value="3" name="biaya_beban" id="pihak3" checked> Pihak Ketiga </label><br>
									<label><input type="radio" value="4" name="biaya_beban" id="tunai"> Tunai</label>';
								}else{
									echo'
									<label><input type="radio" value="1" name="biaya_beban" id="pengirim">Pengirim </label><br>
									<label><input type="radio" value="2" name="biaya_beban" id="penerima"> Penerima</label><br>
									<label><input type="radio" value="3" name="biaya_beban" id="pihak3"> Pihak Ketiga </label><br>
									<label><input type="radio" value="4" name="biaya_beban" id="tunai" checked> Tunai</label>';
								}
								echo'
								</div>
							</div>
						</div>';
						if($r['biaya_beban']=='3' || $r['biaya_beban']=='4'){
						echo'
						<div id="ifYes2">
							<div class="form-group">
								<label class="col-md-3 control-label">Nama Perusahaan/Perorangan</label>
								<div class="col-md-6">
									<input type="text" name="nama_pembayaran" class="form-control" value="'.$r['nama_pembayaran'].'">
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label">Alamat</label>
								<div class="col-md-6">
									<textarea class="form-control" name="alamat_pembayaran" rows="2">'.$r['alamat_pembayaran'].'</textarea>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label">Contact Person</label>
								<div class="col-md-6">
									<input type="text" name="cp_pembayaran" class="form-control" value="'.$r['cp_pembayaran'].'">
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label">Telp</label>
								<div class="col-md-6">
									<input type="text" name="telp_pembayaran" class="form-control" value="'.$r['telp_pembayaran'].'">
								</div>
							</div>
						</div>';
						}else{
						echo'
						<div id="ifYes2" style="display:none">
							<div class="form-group">
								<label class="col-md-3 control-label">Nama Perusahaan/Perorangan</label>
								<div class="col-md-6">
									<input type="text" name="nama_pembayaran" class="form-control">
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label">Alamat</label>
								<div class="col-md-6">
									<textarea class="form-control" name="alamat_pembayaran" rows="2"></textarea>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label">Contact Person</label>
								<div class="col-md-6">
									<input type="text" name="cp_pembayaran" class="form-control">
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label">Telp</label>
								<div class="col-md-6">
									<input type="text" name="telp_pembayaran" class="form-control">
								</div>
							</div>
						</div>';
						}
						echo'
						<hr>
					</div><!-- /.box-body -->
					<div class="box-footer">
						<button type="button" class="btn btn-default" onclick="window.location.href = \'?module=booking\';">Cancel</button>
						<button type="submit" class="btn btn-primary pull-right">Save</button>
					</div><!-- /.box-footer -->
					</form>
				</div><!-- /.box -->
			</section>';
			
			break;
			
		}
}
?>