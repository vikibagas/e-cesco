<section class="content-header">
	<h1>Booking<small>outbound</small></h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-home"></i> Home</a></li>
		<li><a href="#">Booking</a></li>
		<li class="active">Outbound</li>
	</ol>
</section>
<div id="modal-form" class="modal fade" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-body">
				<div class="row">
					<div class="col-sm-12">
						<p>
						<b>Definisi,</b> dengan Tanda Terima pengiriman ini kami CESCO LOGISTICS sebagai pengangkut, dan "Anda" sebagai pelanggan/pengirim/penerima serta paket kiriman di artikan sebagai paket / produk / kiriman / barang atau dokumen yang di terima oleh kami untuk di kirimkan ke alamat tujuan sesuai kesepakatan bersama.
						</p>
						<p>
						<b>Tanggung jawab kami,</b> CESCO LOGISTICS bertanggung jawab terhadap paket / produk / kiriman / barang atau dokumen kiriman milik pengirim atau penerima, yang telah membayar lunas (kecuali ada kesepakatan tertentu) serta dalam proses pengirimannya kami berhak untuk memilih sarana angkutan apapun untuk sampai di tempat tujuan tepat waktu tanpa harus ada pemberitahuan terlebih dahulu. Kiriman telah di anggap di terima dengan baik dan benar bila tidak ada keluhan saat kiriman di serahkan kepada penerima.
						</p>
						<p>
						<b>Tanggung jawab Anda,</b> Anda menanggung bahwa setiap isi paket / dokumen harus sesuai dengan informasi awal dan dinyatakan tertulis di tanda terima pengiriman. Bila terjadi ketidaksesuaian atas isi kiriman dengan tertulis di tanda terima pengiriman maka pengirim / penerima WAJIB bertanggung jawab atas hal tersebut dan pelanggaran hukum yang dilakukannya sesuai dengan peraturan pemerintah yang ada.
						</p>
						<p>
						<b>Dalam hal pengiriman CESCO LOGISTICS tidak bertanggung jawab atas hal-hal berikut ini:</b>
						<ol>
							<li>Resiko Tehnik apapun yang terjadi selama pengiriman yang menyebabkan barang yag di kirim tidak berfungsi atau berubah fungsi, hal ini berlaku untuk kiriman jenis elektronik seperti : TV, Radio, Tape, Computer, Notebook, HardDisk, Flashdisk, Server/ Router, Air Conditioner, Kulkas, Video Player, Sound System, Mesin Cuci, Dan lain sebagainya.</li>
							<li>Keterlambatan pengiriman ke alamat tujuan dan kehilangan atau kerusakan karena keadaan memaksa ( Force Majeure) misalnya Huru-Hara, Bencana Alam, PErang, Pembajakan dan Kejadian sejenis lainnya.</li>
							<li>Setelah lebih dari 1 hari dari tanggal terima pengiriman dan kiriman di tempat tujuan dengan baik maka penerima / pengirim tidak bisa menuntut dalam bentuk apapun.</li>
							<li>Kebocoran, Kerusakan atau Pembusukan jenis kiriman yang berupa barang cair, pecah belah, makanan dan lain-lain kecuali jika di kemas secara baik dan benar melalui CESCO LOGISTICS dan mendapatkan persetujuan pengiriman dari CESCO LOGISTICS.</li>
						</ol>
						</p>
						<p>
						<b>DILARANG,</b> memasukkan atau mengirimkan barang-barang tersebut di bawah ini :
						<ol>
							<li>Uang Tunai atau Surat Berharga lainnya (Cek, Giro, Obligasi, Saham, Sertifikat) yang bisa di uangkan kecuali ada persetujuan dari pihak ASURANSI dan CESCO LOGISTICS.</li>
							<li>Barang-Barang perhiasan dan barang berharga lainnnya.</li>
							<li>Barang yang dapat meledak, beracun atau yang dapat merusak barang lainnya.</li>
							<li>Narkotika, Ganja, Minuman mengandung Alkohol dan Obat-obatan terlarang lainnya.</li>
							<li>Barang Cetakan, Rekaman atau barang lain yang bertentangan dengan nilai kesusilaan dan dapat menggangu stabilitas keamanan dan ketetiban umum.</li>
							<li>Barang-Barang yang berupa makanan dan buah-buahan yang mudah busuk serta mahluk hidup seperti hewan dan tumbuhan.</li>
							<li>Barang dalam kategori berbahaya dan mengandung bahan kimia.</li>
						</ol>
						</p>
						<p>
						<b>Batas Nilai Pertanggungan,</b> Bila terjadi kehilangan atau kerusakan terhadap barang kiriman yang tidak di asuransikan melalui CESCO LOGISTICS, penggantian di lakukan dengan nilai maksimum sebesar :
						<ol>
							<li>Rp 100.000 untuk kemasan Dokumen</li>
							<li>Rp 500.000 untuk kemasan Paket hingga 1000 Kg</li>
							<li>Atau 10x biaya pengiriman untuk barang yang lebih dari 1000 Kg.</li>
							<li>Dengan syarat dan ketentuan yang berlaku dari CESCO LOGISTICS.</li>
						</ol>
						<b>Barang di atas Rp 500.000 WAJIB di asuransikan melalui CESCO LOGISTICS dan untuk barang yang mudah pecah / rusak juga WAJIB di Packing Kayu melalui CESCO LOGISTICS.</b>
						</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php
if(!isset($_SESSION)){
    session_start();
}

if (empty($_SESSION['usernamex']) AND empty($_SESSION['passwordx'])){
	header('location:../main.php?module=home');
}
else{
	//cek hak akses user
	$aksi	= "module/mod_booking/booking_action.php";
		
	$act = !empty($_GET['act']) ? $_GET['act'] : '';
		
	switch($act){
			
		default:
		echo'
		<section class="content">
			<div class="row">
				<div class="col-xs-12">
					<div class="box box-primary">
						<div class="box-header">
							<div class="row">
								<div class="col-md-6">
									<div class="btn-group">
										<button class="btn btn-sm btn-primary" onclick="window.location.href = \'?module=booking&act=create\';">Add New <i class="fa fa-plus"></i></button>
									</div>
								</div>
								<!--
								<div class="col-md-6">
									<div class="btn-group pull-right">
										<button class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown">Tools <i class="fa fa-angle-down"></i></button>
										<ul class="dropdown-menu pull-right">
											<li><a href="javascript:;" onclick="window.location.href = \'?module=booking&act=upload\';"><i class="fa fa-upload"></i> Upload Excel </a></li>
										</ul>
									</div>
								</div>
								-->
							</div>
						</div>
						<div class="box-body">
							<table class="table table-striped table-bordered table-hover" width="100%" id="bookingclient">
								<thead>
									<tr>
										<th>Airwaybill</th>
										<th>Booking Time</th>
										<th>Customer</th>
										<th>Kota Asal</th>
										<th>Kota Tujuan</th>
										<th>Service</th>
										<th width="80px">Action</th>
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
			
		case "create":
			
		echo'
		<section class="content">
			<div class="box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title">Create</h3>
					<div class="box-tools pull-right">
						<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
						<button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
					</div>
				</div><!-- /.box-header -->
				<form method="post" action="'.$aksi.'?module=booking&act=create" class="form-horizontal form-bordered" id="form-booking" autocomplete="off">
				<div class="box-body">
					<div class="form-group">
						<label class="col-sm-3 control-label full-right">Tanggal <span class="required">* </span></label>
						<div class="col-sm-3">
							<div class="input-group">
								<div class="input-group-addon">
									<i class="fa fa-calendar"></i>
								</div>
								<input type="text" class="form-control" id="tanggal" name="tanggal" value="'.$setdate.'" readonly>
							</div>
						</div>
					</div>
					<hr>
					<div class="form-group">
						<label class="col-md-3 control-label">Pengirim <span class="required">* </span></label>
						<div class="col-md-6">
							<select name="id_kustomer" id="id_kustomer" data-placeholder="Choose a Customer..." class="form-control select2">';
							$result = mysqli_query($con,"select id_kustomer, nama_kustomer from kustomer where id_kustomer='".$u['id_kustomer']."' order by nama_kustomer");
							while ($row = mysqli_fetch_assoc($result)) {
								if($u['id_kustomer']==$row['id_kustomer']){
									echo '<option value="'.$row['id_kustomer'].'" selected>'.$row['nama_kustomer'].'</option>';
								}else{
									echo '<option value="'.$row['id_kustomer'].'">'.$row['nama_kustomer'].'</option>';
								}
							}
							echo'
							</select>
							<div class="val-message"></div>
							<input type="text" name="nama_pengirim" placeholder="Nama pengirim..." class="form-control" value="'.$u['fullname'].'">
							<textarea name="alamat_pengirim" placeholder="Alamat pengirim..." id="alamat_pengirim" class="form-control" rows="2">'.$u['alamat'].'</textarea>
							<input type="text" name="hp_pengirim" placeholder="No. Handphone..." class="form-control" value="'.$u['telp'].'">';
							if($u['loc_code']=='Y'){
								echo'<input type="text" id="loc_code" name="loc_code" placeholder="Loc Code..." class="form-control">';
							}
							echo'
							<input type="hidden" name="lokasi_asal" id="id_kota" value="'.$u['kota'].'">
							<input type="hidden" name="id_cabang" id="id_cabang" value="'.$u['id_cabang'].'">
							<input type="hidden" name="intercityasal" id="intercityasal" value="'.$u['intercityasal'].'">
						</div>
					</div>
						<hr>
						<div class="form-group">
							<label class="col-md-3 control-label">Penerima <span class="required">* </span></label>
							<div class="col-md-6">
								<input type="text" name="nama_penerima" id="nama_penerima" placeholder="Nama penerima..." class="form-control">
								<textarea name="alamat_penerima" id="alamat_penerima" placeholder="Alamat penerima..." class="form-control" rows="2"></textarea>
								<input type="text" name="hp_penerima" id="hp_penerima" placeholder="No. Handphone..." class="form-control">
								<input type="text" name="perusahaan_penerima" id="perusahaan_penerima" placeholder="Perusahaan..." class="form-control">
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
										if(!empty($t['kode_kota'])){
											echo'
											<option value="'.$t['id_kota'].'">'.$t['nama_kota'].' ('.$t['kode_kota'].')</option>';
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
									echo'
									<label><input type="radio" name="service" value="'.$s['id_pelayanan'].'"/> '.$s['nama_pelayanan'].' </label><br>';
								}
								echo'
								</div>
							</div>
						</div>
						<hr>
						<div class="form-group">
							<label class="col-md-3 control-label">Komoditi <small class="required">*</small></label>
							<div class="col-md-6">
								<div class="radio radio-info">
									<label><input type="radio" value="1" name="komoditi" id="yesCheck1"> Paket</label><br>
									<label><input type="radio" value="2" name="komoditi" id="yesCheck2"> Dokumen</label>
								</div>
							</div>
						</div>
						<div id="ifYes" style="display:none">
							<div class="form-group">
								<label class="col-md-3 control-label">Jenis Barang</label>
								<div class="col-md-6"><input type="text" name="jenis_barang" class="form-control"></div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label">Estimasi Berat Barang</label>
								<div class="col-md-6">
									<div class="input-group">
										<input type="text" name="berat_barang" class="form-control" value="1"> <span class="input-group-addon">Kg</span>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label">Ukuran Barang</label>
								<div class="col-md-6">
									<div class="row">
										<div class="col-md-4">
											<div class="input-group">
												<input type="text" name="length" placeholder="Length..." class="form-control number"> <span class="input-group-addon">cm</span>
											</div>
										</div>
										<div class="col-md-4">
											<div class="input-group">
												<input type="text" name="width" placeholder="Width..." class="form-control number"> <span class="input-group-addon">cm</span>
											</div>
										</div>
										<div class="col-md-4">
											<div class="input-group">
												<input type="text" name="height" placeholder="Height..." class="form-control number"> <span class="input-group-addon">cm</span>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label">Jumlah Collie/Pcs</label>
								<div class="col-md-6"><input type="text" name="jumlah_collie" class="form-control number" value="1"></div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label">Packing</label>
								<div class="col-md-6">
									<div class="radio radio-info">
										<label><input type="radio" value="1" name="packing_kayu" checked>Packing Oleh Pemilik </label><br>
										<label><input type="radio" value="2" name="packing_kayu">Packing Kayu Oleh Cesco Log.</label><br>
										<label><input type="radio" value="3" name="packing_kayu">Non Packing Kayu Oleh Cesco Log.</label>
									</div>
								</div>
							</div>
						</div>
						<hr>
						<div class="form-group">
							<label class="col-md-3 control-label">Asuransi <small class="required">*</small></label>
							<div class="col-md-6">
								<div class="radio radio-info">
									<label><input type="radio" value="Y" name="asuransi" id="yesAsuransi">Ya </label><br>
									<label><input type="radio" value="N" name="asuransi" checked id="noAsuransi">Tidak </label>
								</div>
							</div>
						</div>
						<div id="ifAsuransi" style="display:none">
							<div class="form-group">
								<label class="col-md-3 control-label">Input Nilai Barang</label>
								<div class="col-md-6">
									<div class="input-group m-b">
										<span class="input-group-addon">Rp</span> <input type="text" name="nilai_barang" class="form-control number">
									</div>
								</div>
							</div>
						</div>
						<hr>
						<div class="form-group">
							<label class="col-md-3 control-label">Surat Jalan Kembali</label>
							<div class="col-md-6">
								<div class="radio radio-info">
									<label><input type="radio" value="Y" name="surat_jalan" checked>Ya </label><br>
									<label><input type="radio" value="N" name="surat_jalan">Tidak </label>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label">Catatan Khusus Kiriman</label>
							<div class="col-md-6">
								<textarea name="catatan" placeholder="Catatan khusus kiriman ..." class="form-control" rows="2"></textarea>
							</div>
						</div>
						<hr>
						<div class="form-group">
							<label class="col-md-3 control-label">Pembayaran Pengiriman<br/>
							<small class="required">Dilakukan Oleh</small></label>
							<div class="col-md-6">
								<div class="radio radio-info">
									<label><input type="radio" value="1" name="biaya_beban" id="pengirim" checked>Pengirim </label><br>
									<label><input type="radio" value="2" name="biaya_beban" id="penerima"> Penerima</label><br>
									<label><input type="radio" value="3" name="biaya_beban" id="pihak3"> Pihak Ketiga </label><br>
									<label><input type="radio" value="4" name="biaya_beban" id="tunai"> Tunai</label>
								</div>
							</div>
						</div>
						<div id="ifYes2" style="display:none">
							<div class="form-group">
								<label class="col-md-3 control-label">Nama Perusahaan/Perorangan</label>
								<div class="col-md-6">
									<input type="text" name="nama_pembayaran" id="nama_pembayaran" class="form-control">
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label">Alamat</label>
								<div class="col-md-6">
									<textarea class="form-control" name="alamat_pembayaran" id="alamat_pembayaran" rows="2"></textarea>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label">Contact Person</label>
								<div class="col-md-6">
									<input type="text" name="cp_pembayaran" id="cp_pembayaran" class="form-control">
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label">Telp</label>
								<div class="col-md-6">
									<input type="text" name="telp_pembayaran" id="telp_pembayaran" class="form-control">
								</div>
							</div>
						</div>
						<hr>
						<div class="form-group">
							<label class="col-md-3 control-label">Setuju pemrosesan pengiriman <small class="required">*</small></label>
							<div class="col-md-6">
								<div class="checkbox">
									<label><input type="checkbox" name="agree"> <small class="required">*</small> Dengan menyerahkan kiriman Anda, berarti Anda setuju dengan <a data-toggle="modal" class="required" href="#modal-form"><b>ketentuan dan kondisi</b></a> yang membatasi pertanggung jawaban kami.</label>
								</div>
							</div>
						</div>
					</div><!-- /.box-body -->
					<div class="box-footer">
						<button type="button" class="btn btn-default" onclick="window.location.href = \'?module=booking\';">Cancel</button>
						<button type="submit" class="btn btn-primary pull-right">Save</button>
					</div><!-- /.box-footer -->
					</form>
				</div><!-- /.box -->
			</section>';
			
			break;
			
			case "update":
			
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
							<div class="col-md-6"><input type="text" name="airwaybill_kode" class="form-control" value="'.$r['airwaybill_kode'].'" readonly></div>
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
						<div class="form-group">
							<label class="col-md-3 control-label">Setuju pemrosesan pengiriman <small class="required">*</small></label>
							<div class="col-md-6">
								<div class="checkbox">
									<label><input type="checkbox" name="agree"> <small class="required">*</small> Dengan menyerahkan kiriman Anda, berarti Anda setuju dengan <a data-toggle="modal" class="required" href="#modal-form"><b>ketentuan dan kondisi</b></a> yang membatasi pertanggung jawaban kami.</label>
								</div>
							</div>
						</div>
					</div><!-- /.box-body -->
					<div class="box-footer">
						<button type="button" class="btn btn-default" onclick="window.location.href = \'?module=booking\';">Cancel</button>
						<button type="submit" class="btn btn-primary pull-right">Save</button>
					</div><!-- /.box-footer -->
					</form>
				</div><!-- /.box -->
			</section>';
			
			break;
			
			case "upload":
			
			echo'
			<section class="content">
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">Upload</h3>
						<div class="box-tools pull-right">
							<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
							<button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
						</div>
					</div><!-- /.box-header -->
					<form method="post" action="'.$aksi.'?module=booking&act=upload" class="form-horizontal form-bordered" id="form-booking" autocomplete="off" enctype="multipart/form-data">
					<div class="box-body">
						<div class="form-group">
							<label class="col-sm-3 control-label full-right">Tanggal <span class="required">* </span></label>
							<div class="col-sm-3">
								<div class="input-group">
									<div class="input-group-addon">
										<i class="fa fa-calendar"></i>
									</div>
									<input type="text" class="form-control" id="tanggal" name="tanggal" value="'.$setdate.'" readonly>
								</div>
							</div>
						</div>
						<hr>
						<div class="form-group">
							<label class="col-md-3 control-label">Pengirim <span class="required">* </span></label>
							<div class="col-md-6">
								<select name="id_kustomer" id="id_kustomer" data-placeholder="Choose a Customer..." class="form-control select2">';
								$result = mysqli_query($con,"select id_kustomer, nama_kustomer from kustomer order by nama_kustomer");
								echo '<option value="">Select...</option>';
								while ($row = mysqli_fetch_assoc($result)) {
									echo '<option value="' . $row['id_kustomer'] . '">' . $row['nama_kustomer'] . '</option>';
								}
								echo'
								</select>
								<div class="val-message"></div>
								<input type="text" name="nama_pengirim" placeholder="Nama pengirim..." class="form-control">
								<textarea name="alamat_pengirim" placeholder="Alamat pengirim..." id="alamat_pengirim" class="form-control" rows="2"></textarea>
								<input type="text" name="hp_pengirim" placeholder="No. Handphone..." class="form-control">
								<input type="text" id="loc_code" name="loc_code" placeholder="Loc Code..." class="form-control" style="display:none;">
								<input type="hidden" name="lokasi_asal" id="id_kota">
								<input type="hidden" name="id_cabang" id="id_cabang">
								<input type="hidden" name="intercityasal" id="intercityasal">
							</div>
						</div>
						<hr>
						<div class="form-group">
							<label class="col-md-3 control-label">Upload Data Penerima <span class="required">* </span></label>
							<div class="col-md-6">
								<input type="file" name="bookingfile">
								<p class="help-block">Hanya bisa file ber-type .xls</p>
							</div>
						</div>
						<hr>
						<div class="form-group">
							<label class="control-label col-md-3">Tipe Service <span class="required">* </span></label>
							<div class="col-md-6">
								<div class="radio radio-info">';
								$service = mysqli_query($con, "select id_pelayanan, nama_pelayanan from pelayanan where active='Y'");
								while($s=mysqli_fetch_assoc($service)){
									echo'
									<label><input type="radio" name="service" value="'.$s['id_pelayanan'].'"/> '.$s['nama_pelayanan'].' </label><br>';
								}
								echo'
								</div>
							</div>
						</div>
						<hr>
						<div class="form-group">
							<label class="col-md-3 control-label">Komoditi <small class="required">*</small></label>
							<div class="col-md-6">
								<div class="radio radio-info">
									<label><input type="radio" value="1" name="komoditi" id="yesCheck1"> Paket</label><br>
									<label><input type="radio" value="2" name="komoditi" id="yesCheck2"> Dokumen</label>
								</div>
							</div>
						</div>
						<div id="ifYes" style="display:none">
							<div class="form-group">
								<label class="col-md-3 control-label">Jenis Barang</label>
								<div class="col-md-6"><input type="text" name="jenis_barang" class="form-control"></div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label">Estimasi Berat Barang</label>
								<div class="col-md-6">
									<div class="input-group">
										<input type="text" name="berat_barang" class="form-control" value="1"> <span class="input-group-addon">Kg</span>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label">Ukuran Barang</label>
								<div class="col-md-6">
									<div class="row">
										<div class="col-md-4">
											<div class="input-group">
												<input type="text" name="length" placeholder="Length..." class="form-control number"> <span class="input-group-addon">cm</span>
											</div>
										</div>
										<div class="col-md-4">
											<div class="input-group">
												<input type="text" name="width" placeholder="Width..." class="form-control number"> <span class="input-group-addon">cm</span>
											</div>
										</div>
										<div class="col-md-4">
											<div class="input-group">
												<input type="text" name="height" placeholder="Height..." class="form-control number"> <span class="input-group-addon">cm</span>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label">Jumlah Collie/Pcs</label>
								<div class="col-md-6"><input type="text" name="jumlah_collie" class="form-control number" value="1"></div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label">Packing</label>
								<div class="col-md-6">
									<div class="radio radio-info">
										<label><input type="radio" value="1" name="packing_kayu" checked>Packing Oleh Pemilik </label><br>
										<label><input type="radio" value="2" name="packing_kayu">Packing Kayu Oleh Cesco Log.</label><br>
										<label><input type="radio" value="3" name="packing_kayu">Non Packing Kayu Oleh Cesco Log.</label>
									</div>
								</div>
							</div>
						</div>
						<hr>
						<div class="form-group">
							<label class="col-md-3 control-label">Asuransi <small class="required">*</small></label>
							<div class="col-md-6">
								<div class="radio radio-info">
									<label><input type="radio" value="Y" name="asuransi" id="yesAsuransi">Ya </label><br>
									<label><input type="radio" value="N" name="asuransi" checked id="noAsuransi">Tidak </label>
								</div>
							</div>
						</div>
						<div id="ifAsuransi" style="display:none">
							<div class="form-group">
								<label class="col-md-3 control-label">Input Nilai Barang</label>
								<div class="col-md-6">
									<div class="input-group m-b">
										<span class="input-group-addon">Rp</span> <input type="text" name="nilai_barang" class="form-control number">
									</div>
								</div>
							</div>
						</div>
						<hr>
						<div class="form-group">
							<label class="col-md-3 control-label">Surat Jalan Kembali</label>
							<div class="col-md-6">
								<div class="radio radio-info">
									<label><input type="radio" value="Y" name="surat_jalan" checked>Ya </label><br>
									<label><input type="radio" value="N" name="surat_jalan">Tidak </label>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label">Catatan Khusus Kiriman</label>
							<div class="col-md-6">
								<textarea name="catatan" placeholder="Catatan khusus kiriman ..." class="form-control" rows="2"></textarea>
							</div>
						</div>
						<hr>
						<div class="form-group">
							<label class="col-md-3 control-label">Pembayaran Pengiriman<br/>
							<small class="required">Dilakukan Oleh</small></label>
							<div class="col-md-6">
								<div class="radio radio-info">
									<label><input type="radio" value="1" name="biaya_beban" id="pengirim" checked>Pengirim </label><br>
									<label><input type="radio" value="2" name="biaya_beban" id="penerima"> Penerima</label><br>
									<label><input type="radio" value="3" name="biaya_beban" id="pihak3"> Pihak Ketiga </label><br>
									<label><input type="radio" value="4" name="biaya_beban" id="tunai"> Tunai</label>
								</div>
							</div>
						</div>
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
						</div>
						<hr>
						<div class="form-group">
							<label class="col-md-3 control-label">Setuju pemrosesan pengiriman <small class="required">*</small></label>
							<div class="col-md-6">
								<div class="checkbox">
									<label><input type="checkbox" name="agree"> <small class="required">*</small> Dengan menyerahkan kiriman Anda, berarti Anda setuju dengan <a data-toggle="modal" class="required" href="#modal-form"><b>ketentuan dan kondisi</b></a> yang membatasi pertanggung jawaban kami.</label>
								</div>
							</div>
						</div>
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