<section class="content-header">
	<h1>Pickup<small>booking request</small></h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-home"></i> Home</a></li>
		<li><a href="#">Application</a></li>
		<li class="active">Pickup</li>
	</ol>
</section>
<?php
if(!isset($_SESSION)){
    session_start();
}

if (empty($_SESSION['username']) AND empty($_SESSION['password'])){
	header('location:../../application/login.php');
}
else{
	//cek hak akses user
	if($u['level']=='admin' OR $u['level']=='kurir' OR $u['level']=='network' OR $u['level']=='inbound' OR $u['level']=='operator' OR $u['level']=='intercity' OR $u['level']=='driver' OR $u['level']=='agent' OR $u['level']=='domex' OR $u['level']=='logistics' or $u['level']=='kurir_agent' OR $u['level']=='finance' or $u['level'] == 'admin_network'){ 
		
		$aksi	= "module/mod_pickup/pickup_action.php";
		
		$act = !empty($_GET['act']) ? $_GET['act'] : '';
		
		$id_cabang = $u['id_cabang'];
		
		switch($act){
			
			default:
			echo'
			<section class="content">
				<div class="row">
					<div class="col-xs-12">
						<div class="box box-primary">
							<div class="box-body">
							<form action="'.$aksi.'?module=pickup&act=plus" id="plustanggal" method="POST"></form> 
								<table id="pickup" class="table table-bordered table-striped table-hover" width="100%" cellspacing="0" style="white-space:nowrap;">
									<thead>
										<tr>
											<th>Airwaybill</th>
											<th>Booking - SLA PICKUP</th>
											<th>Pengirim</th>
											<th>Kota Asal</th>
											<th>Kota Tujuan</th>
											<th>Service</th>											
											<th width="60px">Action</th>
										</tr>
									</thead>
									<tbody>
									</tbody>
								</table>
								';
							if ($u['level']=='admin'){

								echo '
								<input type="text" id="tanggal" name="tanggalplus" form="plustanggal" required><input type="text" id="alasan_plus" name="alasan_plus" form="plustanggal" placeholder="alasan" required>
								<button type="submit"  form="plustanggal" class="btn btn-success"  Value="Plus Tanggal"> Plus Tanggal </button>';
							}

							echo '
							</div>
						</div>
					</div>
				</div>
			</section>
			
			<div id="switch-courier-pickup" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
							<h4 class="modal-title" id="myModalLabel">Switch Assignment</h4>
						</div>
						<div class="modal-content">
							<div class="modal-body"></div>
						</div>
					</div>
				</div>
			</div>';

			echo '<div id="modal_price_insert_pickup" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
							<h4 class="modal-title" id="myModalLabel">Insert Price</h4>
						</div>
						<div class="modal-content">
							<div class="alert alert-warning alert-dismissible">
			                	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
			               		<h4><i class="icon fa fa-warning"></i> Perhatian!</h4>
			                	penambahan harga berlaku untuk semua pengiriman dan customer yang sama.
			              	</div>
							<div class="modal-body"></div>
						</div>
					</div>
				</div>
			</div>


			<div id="detail_waktu" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
							<h4 class="modal-title" id="myModalLabel">STATUS AIRWAYBILL</h4>
						</div>
						<div class="modal-content">
							<div class="modal-body"></div>
						</div>
					</div>
				</div>
			</div>

			';


			
			break;
			
			case "view":
			
			$key = "asdf$1011";
			$encrypt = "$_GET[id]";
			$sla = $_GET['sla'];
			$id = base64_decrypt($encrypt,$key);

			$query = mysqli_query($con, "select *,coalesce(perusahaan_pengirim,x_namakustomer(x_namakustomer(id_kustomer))) as pt_pengirim, SUBSTRING_INDEX(ukuran_barang,'x',1) as length, SUBSTRING_INDEX(SUBSTRING_INDEX(ukuran_barang,'x',2),'x',-1) as width, SUBSTRING_INDEX(ukuran_barang,'x',-1) as height, DATE_FORMAT(created_date,'%Y%m%d') as folder from pengiriman where id_pengiriman='$id'");
			$r = mysqli_fetch_assoc($query);
			
			$tanggal = tgl_indo($r['tanggal']);
									
			echo'
			<section class="content">
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">View: '.$r['airwaybill_kode'].'</h3>
						<div class="box-tools pull-right">
							<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
							<button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
						</div>
					</div><!-- /.box-header -->
					<form method="post" action="'.$aksi.'?module=pickup&act=update" class="form-horizontal form-bordered" id="form-pickup" autocomplete="off" enctype="multipart/form-data">
					<input type="hidden" name="id" value="'.$r['id_pengiriman'].'">
					<div class="box-body">
						<div class="form-group">
							<label class="col-sm-3 control-label full-right">Tanggal <span class="required">* </span></label>
							<div class="col-sm-3">
								<div class="input-group">
									<div class="input-group-addon">
										<i class="fa fa-calendar"></i>
									</div>
									<input type="text" class="form-control" id="tanggal" name="tanggal" value="'.$tanggal.'" readonly disabled>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label">Airwaybill <span class="required">* </span></label>
							<div class="col-md-6">
								<input type="text" name="airwaybill_kode" class="form-control" value="'.$r['airwaybill_kode'].'" disabled>
							</div>
						</div>
						<hr>
						<div class="form-group">
							<label class="col-md-3 control-label">Pengirim <span class="required">* </span></label>
							<div class="col-md-6">
								<input type="text" name="nama_pengirim" placeholder="Nama pengirim..." class="form-control" value="'.$r['pt_pengirim'].'" disabled>
								<input type="text" name="nama_pengirim" placeholder="Nama pengirim..." class="form-control" value="'.$r['nama_pengirim'].'" disabled>
								<textarea name="alamat_pengirim" placeholder="Alamat pengirim..." id="alamat_pengirim" class="form-control" rows="2" disabled>'.$r['alamat_pengirim'].'</textarea>
								<input type="text" name="hp_pengirim" placeholder="No. Handphone..." class="form-control" value="'.$r['hp_pengirim'].'" disabled>';
								$loccode = mysqli_query($con, 'select loc_code from kustomer where id_kustomer="'.$r['id_kustomer'].'"');
								$l = mysqli_fetch_assoc($loccode);
								if($l['loc_code']=='Y'){
									echo'
									<input type="text" id="loc_code" name="loc_code" placeholder="Loc Code..." class="form-control" value="'.$r['loc_code'].'" disabled>';
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
								<input type="text" name="nama_penerima" id="nama_penerima" placeholder="Nama penerima..." class="form-control" value="'.$r['nama_penerima'].'" disabled>
								<textarea name="alamat_penerima" id="alamat_penerima" placeholder="Alamat penerima..." class="form-control" rows="2" disabled>'.$r['alamat_penerima'].'</textarea>
								<input type="text" name="hp_penerima" id="hp_penerima" placeholder="No. Handphone..." class="form-control" value="'.$r['hp_penerima'].'" disabled>
								<input type="text" name="perusahaan_penerima" id="perusahaan_penerima" placeholder="Perusahaan..." class="form-control" value="'.$r['perusahaan_penerima'].'" disabled>
							</div>
						</div>
						<hr>
						';
						if ($r['id_kota_asal'] > 0) {
							echo '<div class="form-group">
							<label class="col-md-3 control-label">Penerima <span class="required">* </span></label>
							<div class="col-md-6">
								<input type="text" name="lokasi_tujuan" id="lokasi_tujuan" placeholder="Nama penerima..." class="form-control" value="'.$r['kota_asal'].'" disabled>								
							</div>
						</div>';
						}else{
							echo '
							<div class="form-group">
								<label class="col-md-3 control-label">Kota Tujuan <span class="required">* </span></label>
								<div class="col-md-6">
									<select name="lokasi_tujuan" id="lokasi_tujuan" data-placeholder="Choose a City..." class="form-control select2" disabled>
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

							';
						}

						echo '
						<hr>
						<div class="form-group">
							<label class="control-label col-md-3">Tipe Service <span class="required">* </span></label>
							<div class="col-md-6">
								<div class="radio radio-info">';
								$service = mysqli_query($con, "select id_pelayanan, nama_pelayanan from pelayanan where active='Y'");
								while($s=mysqli_fetch_assoc($service)){
									if($r['service']==$s['id_pelayanan']){
										echo'
										<label><input type="radio" name="service" value="'.$s['id_pelayanan'].'" checked disabled/> '.$s['nama_pelayanan'].' </label><br>';
									}else{
										echo'
										<label><input type="radio" name="service" value="'.$s['id_pelayanan'].'" disabled/> '.$s['nama_pelayanan'].' </label><br>';
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
									<label><input type="radio" value="1" name="komoditi" id="yesCheck1" checked disabled> Paket</label><br>
									<label><input type="radio" value="2" name="komoditi" id="yesCheck2" disabled> Dokumen</label>';
								}else{
									echo'
									<label><input type="radio" value="1" name="komoditi" id="yesCheck1" disabled> Paket</label><br>
									<label><input type="radio" value="2" name="komoditi" id="yesCheck2" checked disabled> Dokumen</label>';
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
									<input type="text" name="jenis_barang" class="form-control" value="'.$r['jenis_barang'].'" disabled>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label">Estimasi Berat Barang</label>
								<div class="col-md-6">
									<div class="input-group">
										<input type="text" name="berat_barang" class="form-control" value="'.$r['berat_barang'].'" disabled> <span class="input-group-addon">Kg</span>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label">Ukuran Barang</label>
								<div class="col-md-6">
									<div class="row">
										<div class="col-md-4">
											<div class="input-group">
												<input type="text" name="length" placeholder="Length..." class="form-control number" value="'.$r['length'].'" disabled> <span class="input-group-addon">cm</span>
											</div>
										</div>
										<div class="col-md-4">
											<div class="input-group">
												<input type="text" name="width" placeholder="Width..." class="form-control number" value="'.$r['width'].'" disabled> <span class="input-group-addon">cm</span>
											</div>
										</div>
										<div class="col-md-4">
											<div class="input-group">
												<input type="text" name="height" placeholder="Height..." class="form-control number" value="'.$r['height'].'" disabled> <span class="input-group-addon">cm</span>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label">Jumlah Collie/Pcs</label>
								<div class="col-md-6">
									<input type="text" name="jumlah_collie" class="form-control number" value="'.$r['collie'].'" disabled>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label">Packing</label>
								<div class="col-md-6">
									<div class="radio radio-info">';
									if($r['packing_kayu']=='1'){
										echo'
										<label><input type="radio" value="1" name="packing_kayu" checked disabled> Packing Oleh Pemilik</label><br>
										<label><input type="radio" value="2" name="packing_kayu" disabled> Packing Kayu Oleh Cesco Log.</label><br>
										<label><input type="radio" value="3" name="packing_kayu" disabled> Non Packing Kayu Oleh Cesco Log.</label>';
									}elseif($r['packing_kayu']=='2'){
										echo'
										<label><input type="radio" value="1" name="packing_kayu" disabled> Packing Oleh Pemilik</label><br>
										<label><input type="radio" value="2" name="packing_kayu" checked disabled> Packing Kayu Oleh Cesco Log.</label><br>
										<label><input type="radio" value="3" name="packing_kayu" disabled> Non Packing Kayu Oleh Cesco Log.</label>';
									}else{
										echo'
										<label><input type="radio" value="1" name="packing_kayu" disabled> Packing Oleh Pemilik</label><br>
										<label><input type="radio" value="2" name="packing_kayu" disabled> Packing Kayu Oleh Cesco Log.</label><br>
										<label><input type="radio" value="3" name="packing_kayu" checked disabled> Non Packing Kayu Oleh Cesco Log.</label>';
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
									<input type="text" name="jenis_barang" class="form-control" value="'.$r['jenis_barang'].'" disabled>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label">Estimasi Berat Barang</label>
								<div class="col-md-6">
									<div class="input-group">
										<input type="text" name="berat_barang" class="form-control" value="'.$r['berat_barang'].'" disabled> <span class="input-group-addon">Kg</span>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label">Ukuran Barang</label>
								<div class="col-md-6">
									<div class="row">
										<div class="col-md-4">
											<div class="input-group">
												<input type="text" name="length" placeholder="Length..." class="form-control number" value="'.$r['length'].'" disabled> <span class="input-group-addon">cm</span>
											</div>
										</div>
										<div class="col-md-4">
											<div class="input-group">
												<input type="text" name="width" placeholder="Width..." class="form-control number" value="'.$r['width'].'" disabled> <span class="input-group-addon">cm</span>
											</div>
										</div>
										<div class="col-md-4">
											<div class="input-group">
												<input type="text" name="height" placeholder="Height..." class="form-control number" value="'.$r['height'].'" disabled> <span class="input-group-addon">cm</span>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label">Jumlah Collie/Pcs</label>
								<div class="col-md-6">
									<input type="text" name="jumlah_collie" class="form-control number" value="'.$r['collie'].'" disabled>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label">Packing</label>
								<div class="col-md-6">
									<div class="radio radio-info">';
									if($r['packing_kayu']=='1'){
										echo'
										<label><input type="radio" value="1" name="packing_kayu" checked disabled> Packing Oleh Pemilik</label><br>
										<label><input type="radio" value="2" name="packing_kayu" disabled> Packing Kayu Oleh Cesco Log.</label><br>
										<label><input type="radio" value="3" name="packing_kayu" disabled> Non Packing Kayu Oleh Cesco Log.</label>';
									}elseif($r['packing_kayu']=='2'){
										echo'
										<label><input type="radio" value="1" name="packing_kayu" disabled> Packing Oleh Pemilik</label><br>
										<label><input type="radio" value="2" name="packing_kayu" checked disabled> Packing Kayu Oleh Cesco Log.</label><br>
										<label><input type="radio" value="3" name="packing_kayu" disabled> Non Packing Kayu Oleh Cesco Log.</label>';
									}else{
										echo'
										<label><input type="radio" value="1" name="packing_kayu" disabled> Packing Oleh Pemilik</label><br>
										<label><input type="radio" value="2" name="packing_kayu" disabled> Packing Kayu Oleh Cesco Log.</label><br>
										<label><input type="radio" value="3" name="packing_kayu" checked disabled> Non Packing Kayu Oleh Cesco Log.</label>';
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
									<label><input type="radio" value="Y" name="asuransi" id="yesAsuransi" checked disabled>Ya </label><br>
									<label><input type="radio" value="N" name="asuransi" id="noAsuransi" disabled>Tidak </label>';
								}else{
									echo'
									<label><input type="radio" value="Y" name="asuransi" id="yesAsuransi" disabled>Ya </label><br>
									<label><input type="radio" value="N" name="asuransi" id="noAsuransi" checked disabled>Tidak </label>';
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
										<span class="input-group-addon">Rp</span> <input type="text" name="nilai_barang" class="form-control number" value="'.number_format($r['nilai_barang']).'" disabled>
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
										<span class="input-group-addon">Rp</span> <input type="text" name="nilai_barang" class="form-control number" value="'.number_format($r['nilai_barang']).'" disabled>
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
									<label><input type="radio" value="Y" name="surat_jalan" checked disabled>Ya </label><br>
									<label><input type="radio" value="N" name="surat_jalan" disabled>Tidak </label>';
								}else{
									echo'
									<label><input type="radio" value="Y" name="surat_jalan" disabled>Ya </label><br>
									<label><input type="radio" value="N" name="surat_jalan" checked disabled>Tidak </label>';
								}
								echo'
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label">Catatan Khusus Kiriman</label>
							<div class="col-md-6">
								<input type="text" name="catatan" class="form-control" value="'.$r['catatan'].'" disabled>
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
									<label><input type="radio" value="1" name="biaya_beban" id="pengirim" checked disabled>Pengirim </label><br>
									<label><input type="radio" value="2" name="biaya_beban" id="penerima" disabled> Penerima</label><br>
									<label><input type="radio" value="3" name="biaya_beban" id="pihak3" disabled> Pihak Ketiga </label><br>
									<label><input type="radio" value="4" name="biaya_beban" id="tunai" disabled> Tunai</label>';
								}elseif($r['biaya_beban']=='2'){
									echo'
									<label><input type="radio" value="1" name="biaya_beban" id="pengirim" disabled>Pengirim </label><br>
									<label><input type="radio" value="2" name="biaya_beban" id="penerima" checked disabled> Penerima</label><br>
									<label><input type="radio" value="3" name="biaya_beban" id="pihak3" disabled> Pihak Ketiga </label><br>
									<label><input type="radio" value="4" name="biaya_beban" id="tunai" disabled> Tunai</label>';
								}elseif($r['biaya_beban']=='3'){
									echo'
									<label><input type="radio" value="1" name="biaya_beban" id="pengirim" disabled>Pengirim </label><br>
									<label><input type="radio" value="2" name="biaya_beban" id="penerima" disabled> Penerima</label><br>
									<label><input type="radio" value="3" name="biaya_beban" id="pihak3" checked disabled> Pihak Ketiga </label><br>
									<label><input type="radio" value="4" name="biaya_beban" id="tunai" disabled> Tunai</label>';
								}else{
									echo'
									<label><input type="radio" value="1" name="biaya_beban" id="pengirim" disabled>Pengirim </label><br>
									<label><input type="radio" value="2" name="biaya_beban" id="penerima" disabled> Penerima</label><br>
									<label><input type="radio" value="3" name="biaya_beban" id="pihak3" disabled> Pihak Ketiga </label><br>
									<label><input type="radio" value="4" name="biaya_beban" id="tunai" checked disabled> Tunai</label>';
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
									<input type="text" name="nama_pembayaran" class="form-control" value="'.$r['nama_pembayaran'].'" disabled>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label">Alamat</label>
								<div class="col-md-6">
									<textarea class="form-control" name="alamat_pembayaran" rows="2" disabled>'.$r['alamat_pembayaran'].'</textarea>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label">Contact Person</label>
								<div class="col-md-6">
									<input type="text" name="cp_pembayaran" class="form-control" value="'.$r['cp_pembayaran'].'" disabled>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label">Telp</label>
								<div class="col-md-6">
									<input type="text" name="telp_pembayaran" class="form-control" value="'.$r['telp_pembayaran'].'" disabled>
								</div>
							</div>
						</div>';
						}else{
						echo'
						<div id="ifYes2" style="display:none">
							<div class="form-group">
								<label class="col-md-3 control-label">Nama Perusahaan/Perorangan</label>
								<div class="col-md-6">
									<input type="text" name="nama_pembayaran" class="form-control" disabled>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label">Alamat</label>
								<div class="col-md-6">
									<textarea class="form-control" name="alamat_pembayaran" rows="2" disabled></textarea>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label">Contact Person</label>
								<div class="col-md-6">
									<input type="text" name="cp_pembayaran" class="form-control" disabled>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label">Telp</label>
								<div class="col-md-6">
									<input type="text" name="telp_pembayaran" class="form-control" disabled>
								</div>
							</div>
						</div>';
						}
						if($u['level']=='admin' || $u['level']=='network' || $u['level']=='logistics' || $u['level']=='domex' || $u['level']=='inbound' || $u['level']=='agent' || $u['level'] == 'admin_network'){
							echo'
							<hr>
							<div class="form-group">
								<label class="col-md-3 control-label">Diambil Oleh <span class="required">* </span></label>
								<div class="col-md-6">
									<select name="pickup_by" data-placeholder="Choose a Courier..." class="form-control select2">';
									if($u['level']=='network' || $u['level']=='domex'){
										//$result = mysqli_query($con,"select id_user, namalengkap from user where id_user='$u[id_user]'");
										if($r['intercity'] != 'Y' AND $r['id_kota_asal'] > 0){
											$result = mysqli_query($con,"select a.id_user, concat(b.nama_cabang,' ','(',b.alamat1,')',' / ',a.namalengkap) as namalengkap from user a inner join cabang b on b.id_cabang=a.id_cabang inner join regencies c on c.id=b.id_kota_baru where (a.level='agent' or a.level='kurir_agent') and blokir != 'Y' and (c.id=x_hubnew('".$r['id_kota_asal']."') or c.id = '".$r['id_kota_asal']."')");
										}
										else{
										$result = mysqli_query($con,"select a.id_user, concat(b.nama_cabang,' ','(',b.alamat1,')',' / ',a.namalengkap) as namalengkap from user a inner join cabang b on b.id_cabang=a.id_cabang inner join kota c on c.id_kota=b.id_kota where (a.level='agent' or a.level='kurir_agent') and blokir != 'Y' and (c.id_kota=x_hub('".$r['lokasi_asal']."') or c.id_kota = '".$r['lokasi_asal']."')");
										}

									}
									elseif($u['level']=='agent'){
										$result = mysqli_query($con,"select id_user, namalengkap from user where  (level='kurir' or level='driver' or level='agent' or level='kurir_agent') and blokir != 'Y' and id_cabang=$u[id_cabang] GROUP BY id_user order by namalengkap");
									}
									else{
										$result = mysqli_query($con,"select id_user, namalengkap from user where  (level='kurir' or level='driver' or level='agent' or level='kurir_agent') and blokir != 'Y' GROUP BY id_user order by namalengkap");
									}
									while ($row = mysqli_fetch_assoc($result)) {
										if($row['id_user']==$r['pickup_request']){
											echo '<option value="'.$row['id_user'].'" selected>'.strtoupper($row['namalengkap']).'</option>';
										}else{
											echo '<option value="'.$row['id_user'].'">'.strtoupper($row['namalengkap']).'</option>';
										}
									}
									echo'
									</select>
									<div class="val-message"></div>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label">Diambil Pada <span class="required">* </span></label>
								<div class="col-md-2">
									<div class="input-group">
										<div class="input-group-addon">
											<i class="fa fa-calendar"></i>
										</div>
										<input type="text" class="form-control" id="" name="pickup_date" value="'.$setdate.'" '.($u['level'] != 'admin' ? 'readonly' : '').'>
										';
										//<input type="text" class="form-control" id="tanggal-diambil" name="pickup_date" value="'.$setdate.'" readonly>
										echo '
									</div>
								</div>
								<div class="col-md-2">
									<div class="bootstrap-timepicker">
										<div class="input-group">
											<div class="input-group-addon">
												<i class="fa fa-clock-o"></i>
											</div>
											<input type="text" class="form-control timepicker" name="pickup_time" value="'.$jam_sekarang.'">
										</div>
									</div>
								</div>
							</div>
							
							
							';
						}
						echo '

							<!--<div class="form-group">
								<label class="col-md-3 control-label">Pickup Oleh</label>
								<div class="col-md-6">
									<input type="text" name="pickup_by" class="form-control" value="'.$u['id_user'].'" readonly>
								</div>
							</div>-->
						
							<div class="form-group">
								<label class="col-md-3 control-label">Foto Barang* (tidak boleh lebih dari 1 MB)</label>
								<div class="col-md-6">
						        	<input type="file" name="gambar" >
						        </div>
					        </div>
					        <div class="form-group">
								<label class="col-md-3 control-label">Kondisi Packing saat pickup</label>
								<div class="col-md-6">
									<textarea class="form-control" name="kondisi_packing" rows="2" name="kondisi_packing" required></textarea>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label">Kondisi Barang saat dipickup </label>
							<div class="col-md-6">
									<textarea class="form-control" name="kondisi_barang" rows="2" name="kondisi_barang" required></textarea>
								</div>
							</div>
						';
						
						

						if($sla==1){
							if ($r['alasan_terlambat'] != ''){
								echo ' <div class="form-group"> <label class="col-md-3 control-label">ALASAN TERLAMBAT = '.$r['alasan_terlambat'].'</label> </div>';
							}else{

								echo '<div class="form-group" style="padding-bottom: 5px;">
				                	<label class="col-md-3 control-label">Alasan Terlambat  '.$r['alasan_terlambat'].'</label>
				                	<div class="col-md-6">

				                	<select name="reason" id="reason" data-placeholder="Choose a Customer..." class="form-control select2" required>		             
		               					<option value="1">Kesalahan Kurir</option>		            			                					
	                					<option value="2">Kesalahan Internal Cabang</option>	                					
	                					';
	                					if($u['level'] == 'admin'){
	                						echo '<option value="3">PERMINTAAN CUSTOMER</option>';	
	                					}
			        					
			        					echo '
				                	</select>
				                	<label>ALASAN (LAINNYA)</label>
				        			<input type="text" style="text-transform:uppercase" class="form-control style="width: 100%;" name="reason_manual"  placeholder="Isi Alasan" '.($u['level'] != 'admin' ? 'required' : '').'>
				                	</div>
				            	</div>';	
							}
							

				         }
						echo'
						</div>
					</div><!-- /.box-body -->
					<div class="box-footer">
						<button type="button" class="btn btn-default" onclick="window.location.href = \'?module=pickup\';">Cancel</button>
						<button type="submit" class="btn btn-info pull-right"><i class="fa fa-truck"></i> Pickup</button>
					</div><!-- /.box-footer -->
					</form>
				</div><!-- /.box -->
			</section>';
			
			break;

			
			
		}
	}else{
		echo akses_salah();
	}
}
?>


<script>
	function detailwaktu(clicked_id) {
		$("#detail_waktu").modal('show');
		var detailawb = clicked_id;


		$.post('module/mod_pickup/pickup_action.php?module=pickup&act=status', {
			id: detailawb
			},
			function (html) {
				$("#detail_waktu .modal-body").html(html);
			}
		);

		

	}
		//alert('oke');
		

		
					
					
					

							
						
						//alert(result.message);

			

</script>