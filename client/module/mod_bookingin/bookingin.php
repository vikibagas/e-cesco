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
	
	$aksi	= "module/mod_bookingin/bookingin_action.php";
		
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
										<button class="btn btn-sm btn-primary" onclick="window.location.href = \'?module=bookingin&act=create\';">Add New <i class="fa fa-plus"></i></button>
									</div>
								</div>
								<div class="col-md-6">
									<div class="btn-group pull-right">
										<button class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown">Tools <i class="fa a-angle-down"></i></button>
										<ul class="dropdown-menu pull-right">
											<li><a href="javascript:;" onclick="window.location.href = \'?module=bookingin&act=upload\';"><i class="fa fa-upload"></i>Booking Upload Excel </a></li>
											';
											if ($u['id_kustomer'] == 106){
												echo '	
													<li><a href="javascript:;" onclick="window.location.href = \'?module=bookingin&act=upload-asus\';"><i class="fa fa-upload"></i>Upload Detail Barang asus</a></li>';
											}
											echo '
										</ul>
									</div>
								</div>
							</div>
						</div>
						<div class="box-body">
							<table class="table table-striped table-bordered table-hover" width="100%" id="bookinginclient">
								<thead>
									<tr>
										<th>Airwaybill</th>
										<th>Booking Time</th>
										<th>Customer</th>
										<th>Kota Asal</th>
										<th>Kota Tujuan</th>
										<th>Perusahaan Penerima</th>
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
				<form method="post" action="'.$aksi.'?module=bookingin&act=create" class="form-horizontal form-bordered" id="form-bookingin" autocomplete="off" enctype="multipart/form-data">
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
					';
					if ($u['other_customer'] == 'N'){
					echo '
						<div class="form-group">
							<label class="col-md-3 control-label">Customer <span class="required">* </span></label>
							<div class="col-md-6">
								<select name="id_kustomer" id="id_kustomerin" data-placeholder="Choose a Customer..." class="form-control select2">';
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
								';
								if($u['perusahaan_pengirim'] ==""){
									echo '<input type="checkbox" id="outbound" name="outbound" value="N"> Ceklist jika pengirim adalah customer';
								}
								echo '
								<input type="hidden" name="id_cabang" id="id_cabang" value="'.$u['id_cabang'].'">
							</div>
						</div>';
					}
					else {
						echo '
						<div class="form-group">
							<label class="col-md-3 control-label">Customer <span class="required">* </span></label>
							<div class="col-md-6">
								<select name="id_kustomer" id="id_kustomerin" data-placeholder="Choose a Customer..." class="form-control select2">';
								$result = mysqli_query($con,"select id_kustomer, nama_kustomer from kustomer where id_kustomer in ($u[customers]) order by nama_kustomer");
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
								
								';
								if($u['otomatis_pengirim'] != 1){
									echo '<input type="checkbox" id="outbound" name="outbound" value="N"> Ceklist jika pengirim adalah customer';
								}
								echo '
								<input type="hidden" name="id_cabang" id="id_cabang" value="'.$u['id_cabang'].'">
							</div>
						</div>';
					}
					if($u['loc_code']=='Y'){
						echo'
						<div class="form-group">
							<label class="col-md-3 control-label"> '; if($u['id_kustomer'] == 13 || $u['id_kustomer'] == 29 || $u['id_kustomer'] == 31 || $u['id_kustomer'] == 106){ echo 'NO DO';} else{ echo ' Loc Code / Network Number ';} echo ' <span class="required">* </span></label>
							<div class="col-md-6">
								

								';
								if ($u['otomatis_pengirim'] != 1){
									echo '<input type="text" name="loc_code" id="input_loccode" maxlength="20" placeholder="Loc Code / NO DO / NETWORK NUMBER..." '.($u['otomatis_pengirim'] == 1 ? "readonly" : "")  .' value="'.($u['otomatis_pengirim'] == 1 ? $u['loc_code_otomatis'] : "")  .'" class="form-control">';
								} else{
									echo '<select name="loc_code"  data-placeholder="Choose ..." class="form-control select2">
											<option value="">Select...</option>';
										$loc_code_otomatis = explode(',', $u['loc_code_otomatis']);
										//$project_name_client = array($u['perusahaan_pengirim']);
										foreach ($loc_code_otomatis as $l) {
											echo '
												<option value="'.str_replace("'","",$l).'">'.str_replace("'","",$l).'</option>
											';
										}
									echo '</select>';
								}
								echo '
							</div>
						</div>';
					}
					if($u['id_kustomer'] == 13 || $u['id_kustomer'] == 29 || $u['id_kustomer'] == 31){
						echo '
						<div class="form-group">
							<label class="col-md-3 control-label">NO PID  <span class="required">* </span></label>
							<div class="col-md-6">

								<input type="text" name="pid" id="pid" maxlength="20" placeholder="NO PID" class="form-control" required maxlength="20">

							</div>
						</div>
						';
					}
					echo'
					<hr>
						<div class="form-group">
							<label class="col-md-3 control-label">Pengirim <span class="required">* </span></label>
							<div class="col-md-6">
								';
								if ($u['otomatis_pengirim'] == 1 && $u['perusahaan_pengirim'] != ""){
									echo '<select name="nama_pengirim"  data-placeholder="Choose ..." class="form-control select2">
											<option value="">Select...</option>';
										$project_name_client = explode(',', $u['perusahaan_pengirim']);
										//$project_name_client = array($u['perusahaan_pengirim']);
										foreach ($project_name_client as $p) {
											echo '
												<option value="'.$u['fullname'].' / '.$p.'">'.$u['fullname'].' / '.$p.'</option>
											';
										}
									echo '</select>';
								} else{
									echo '<input type="text" name="nama_pengirim" id="nama_pengirim" value="'.($u['otomatis_pengirim'] == 1 ? $u['fullname']." / ".$u['perusahaan_pengirim'] : "")  .'" placeholder="Nama pengirim..." class="form-control">';
								}
								echo '
								<textarea name="alamat_pengirim" id="alamat_pengirim" placeholder="Alamat pengirim..." id="alamat_pengirim" class="form-control" rows="2"> '.($u['otomatis_pengirim'] == 1 ? $u['alamat_otomatis'] : "")  .' </textarea>
								<input type="text" id="hp_pengirim" name="hp_pengirim" value="'.($u['otomatis_pengirim'] == 1 ? $u['phone'] : "")  .'" placeholder="No. Handphone..." class="form-control">
								
								';
								if ($u['otomatis_pengirim'] == 1 && $u['perusahaan_pengirim'] != ""){
									echo '
										<select name="perusahaan_pengirim"  data-placeholder="Choose Project Name..." class="form-control select2">
									<option value="">Select...</option>
									';
									$project_name = mysqli_query($con, "select project_name from kustomer_project_name  where id_kustomer=".$u['id_kustomer']."");
									while($pn=mysqli_fetch_assoc($project_name)){
										echo '
											<option value="'.$pn['project_name'].'">'.$pn['project_name'].'</option>
										';
									}
									echo '</select>';
								}else{
									echo '<input type="text" name="perusahaan_pengirim" id="perusahaan_pengirim" placeholder="Perusahaan..." class="form-control">';
								}

								echo '
							</div>
						</div>
						<hr>
						<div class="form-group">
							<label class="col-md-3 control-label">Kota Asal <span class="required">* </span></label>
							<div class="col-md-6">
								<select name="lokasi_asal" id="lokasi_asal" data-placeholder="Choose a City..." class="form-control select2" '.($u['otomatis_pengirim'] == 1 ? "readonly" : "")  .'>
									<option value="">Select...</option>';
									$kotatujuan = mysqli_query($con, "select a.id_kota, case when b.nama_kota is null then a.nama_kota when x_namakota(b.induk) is null then concat(a.nama_kota,', ',b.nama_kota) else concat(a.nama_kota,', ',b.nama_kota,', ',x_namakota(b.induk)) end as nama_kota from kota a left join kota b on b.id_kota=a.induk where a.kota_tujuan='Y' and a.aktif=1");
									while($t=mysqli_fetch_assoc($kotatujuan)){
										if(!empty($t['kode_kota'])){
											echo'
											<option value="'.$t['id_kota'].'">'.$t['nama_kota'].' ('.$t['kode_kota'].')</option>';
										}else{
											echo'
											<option value="'.$t['id_kota'].'" '.($u['lokasi_asal'] != null && $u['lokasi_asal'] == $t['id_kota'] ? "selected" : "").'>'.$t['nama_kota'].'</option>';
										}
									}
									echo'
								</select>
								<div class="val-message"></div>
								<input type="hidden" name="intercityasal" id="intercityasal">
							</div>
						</div>
						<hr>
						<div class="form-group">
							<label class="col-md-3 control-label">Penerima <span class="required">* </span></label>
							<div class="col-md-6">
								<input type="text" name="nama_penerima" id="nama_penerimain" placeholder="Nama penerima..." class="form-control">
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
									$kotatujuan = mysqli_query($con, "select a.id_kota, case when b.nama_kota is null then a.nama_kota when x_namakota(b.induk) is null then concat(a.nama_kota,', ',b.nama_kota) else concat(a.nama_kota,', ',b.nama_kota,', ',x_namakota(b.induk)) end as nama_kota from kota a left join kota b on b.id_kota=a.induk where a.kota_tujuan='Y' and a.aktif=1");
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
								if ($u['id_kustomer'] != 106 || $u['id_user'] == 1158){
									$service = mysqli_query($con, "select id_pelayanan, nama_pelayanan from pelayanan where active='Y'");
								}
								else{
									$service = mysqli_query($con, "select id_pelayanan, nama_pelayanan from pelayanan where id_pelayanan in (3,5)");	
								}
								
								while($s=mysqli_fetch_assoc($service)){
									echo'
									<label><input type="radio" name="service" value="'.$s['id_pelayanan'].'" class="seafreight"/> '.$s['nama_pelayanan'].' </label><br>';
								}
								echo'
								</div>
							</div>
						</div>
						<div id="ifseafreight" style="display:none">
							<div class="form-group">
								<label class="col-md-3 control-label">20ft x</label>
								<div class="col-md-6"><input type="text" name="20ft" class="form-control"></div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label">40ft x</label>
								<div class="col-md-6"><input type="text" name="40ft" class="form-control"></div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label">40ft HC x</label>
								<div class="col-md-6"><input type="text" name="40ft_hc" class="form-control"></div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label">LCL</label>
								<div class="col-md-6">
									<div class="input-group">
										<input type="text" name="lcl" class="form-control"> <span class="input-group-addon">m<sup>3</sup></span>
									</div>
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
							';
							if ($u['id_kustomer'] != 106 || $u['id_user'] == 1158){
							echo '
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
							';
						}
						echo '
						</div>
						<hr>
						';
							if ($u['id_kustomer'] != 106 || $u['id_user'] == 1158){
							echo '
						<div class="form-group">
							<label class="col-md-3 control-label">Asuransi <small class="required">*</small></label>
							<div class="col-md-6">
								<div class="radio radio-info">
									<label><input type="radio" value="Y" name="asuransi" id="yesAsuransi">Ya </label><br>
									<label><input type="radio" value="N" name="asuransi" checked id="noAsuransi">Tidak </label>
								</div>
							</div>
						</div>
						';
						}
						echo '
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
						';
						
						if ($u['id_kustomer'] == 106){
						echo '
							<div class="form-group">
								<label class="col-md-3 control-label">Upload Data Isi Catatan </label>
								<div class="col-md-6">
									<input type="file" name="uploadcatatan">
									<p class="help-block">Hanya bisa file ber-type .xls</p>
								</div>
							</div>
						';
						}
						echo '
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
									<input type="text" name="nama_pembayaran" id="nama_pembayaranin" class="form-control">
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label">Alamat</label>
								<div class="col-md-6">
									<textarea class="form-control" name="alamat_pembayaran" id="alamat_pembayaranin" rows="2"></textarea>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label">Contact Person</label>
								<div class="col-md-6">
									<input type="text" name="cp_pembayaran" id="cp_pembayaranin" class="form-control">
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label">Telp</label>
								<div class="col-md-6">
									<input type="text" name="telp_pembayaran" id="telp_pembayaranin" class="form-control">
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
						<button type="button" class="btn btn-default" onclick="window.location.href = \'?module=bookingin\';">Cancel</button>
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
						<h3 class="box-title">Create</h3>
						<div class="box-tools pull-right">
							<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
							<button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
						</div>
					</div><!-- /.box-header -->
					<form method="post" action="'.$aksi.'?module=bookingin&act=update" class="form-horizontal form-bordered" id="form-bookingin" autocomplete="off" enctype="multipart/form-data">
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
							<label class="col-md-3 control-label">Customer <span class="required">* </span></label>
							<div class="col-md-6">
								<select name="id_kustomer" id="id_kustomerin" data-placeholder="Choose a Customer..." class="form-control select2">';
								$result = mysqli_query($con,"select id_kustomer, nama_kustomer from kustomer where id_kustomer='".$r['id_kustomer']."' order by nama_kustomer");
								while ($row = mysqli_fetch_assoc($result)){
									if($r['id_kustomer']==$row['id_kustomer']){
										echo '<option value="' . $row['id_kustomer'] . '" selected>' . $row['nama_kustomer'] . '</option>';
									}else{
										echo '<option value="' . $row['id_kustomer'] . '">' . $row['nama_kustomer'] . '</option>';
									}
								}
								echo'
								</select>
								<div class="val-message"></div>
								<input type="hidden" name="id_cabang" id="id_cabang" value="'.$r['id_cabang'].'">
							</div>
						</div>';
						$loccode = mysqli_query($con, 'select loc_code from kustomer where id_kustomer="'.$r['id_kustomer'].'"');
						$l = mysqli_fetch_assoc($loccode);
						if($l['loc_code']=='Y' || $r['loc_code'] != ''){
							echo'
							<div id="loc_code">
								<div class="form-group">
									<label class="col-md-3 control-label">Loc Code <span class="required">* </span></label>
									<div class="col-md-6">
										<input type="text" name="loc_code" maxlength="20" placeholder="Loc Code..." class="form-control" value="'.$r['loc_code'].'">
									</div>
								</div>
							</div>';
						}else{
							echo'
							<div id="loc_code" style="display:none;">
								<div class="form-group">
									<label class="col-md-3 control-label">Loc Code <span class="required">* </span></label>
									<div class="col-md-6">
										<input type="text" name="loc_code" maxlength="20" placeholder="Loc Code..." class="form-control" disabled>
									</div>
								</div>
							</div>';
						}
						if($u['id_kustomer'] == 13 || $u['id_kustomer'] == 29 || $u['id_kustomer'] == 31){
							echo '
							<div class="form-group">
								<label class="col-md-3 control-label">NO PID  <span class="required">* </span></label>
								<div class="col-md-6">

									<input type="text" name="pid" id="pid" maxlength="20" placeholder="NO PID" class="form-control" value="'.$r['pid'].'" required>

								</div>
							</div>
							';
						}
						echo'
						<hr>
						<div class="form-group">
							<label class="col-md-3 control-label">Pengirim <span class="required">* </span></label>
							<div class="col-md-6">
								<input type="text" name="nama_pengirim" id="nama_pengirim" placeholder="Nama pengirim..." class="form-control" value="'.$r['nama_pengirim'].'">
								<textarea name="alamat_pengirim" id="alamat_pengirim" placeholder="Alamat pengirim..." id="alamat_pengirim" class="form-control" rows="2">'.$r['alamat_pengirim'].'</textarea>
								<input type="text" name="hp_pengirim" placeholder="No. Handphone..." class="form-control" value="'.$r['hp_pengirim'].'">
								<input type="text" name="perusahaan_pengirim" id="perusahaan_pengirim" placeholder="Perusahaan..." class="form-control" value="'.$r['perusahaan_pengirim'].'">
							</div>
						</div>
						<hr>
						<div class="form-group">
							<label class="col-md-3 control-label">Kota Asal <span class="required">* </span></label>
							<div class="col-md-6">
								<select name="lokasi_asal" id="lokasi_asal" data-placeholder="Choose a City..." class="form-control select2">
									<option value="">Select...</option>';
									$kotatujuan = mysqli_query($con, "select a.id_kota, case when b.nama_kota is null then a.nama_kota when x_namakota(b.induk) is null then concat(a.nama_kota,', ',b.nama_kota) else concat(a.nama_kota,', ',b.nama_kota,', ',x_namakota(b.induk)) end as nama_kota from kota a left join kota b on b.id_kota=a.induk where a.kota_tujuan='Y' and a.aktif=1");
									while($t=mysqli_fetch_assoc($kotatujuan)){
										if($t['id_kota']==$r['lokasi_asal']){
											echo'
											<option value="'.$t['id_kota'].'" selected>'.$t['nama_kota'].'</option>';
										}else{
											echo'
											<option value="'.$t['id_kota'].'">'.$t['nama_kota'].'</option>';
										}
									}
									echo'
								</select>
								<div class="val-message"></div>
								';
								$cekintercityasal = mysqli_query($con, "select intercity from kota where id_kota='".$r['lokasi_asal']."'");
								$i = mysqli_fetch_assoc($cekintercityasal);
								echo '
								<input type="hidden" name="intercityasal" value="'.$i['intercity'].'" id="intercityasal">
							</div>
						</div>
						<hr>
						<div class="form-group">
							<label class="col-md-3 control-label">Penerima <span class="required">* </span></label>
							<div class="col-md-6">
								<input type="text" name="nama_penerima" id="nama_penerimain" placeholder="Nama penerima..." class="form-control" value="'.$r['nama_penerima'].'">
								<textarea name="alamat_penerima" id="alamat_penerima" placeholder="Alamat penerima..." class="form-control" rows="2">'.$r['alamat_penerima'].'</textarea>
								<input type="text" name="hp_penerima" id="hp_penerima" placeholder="No. Handphone..." class="form-control" value="'.$r['hp_penerima'].'">
								<input type="text" name="perusahaan_penerima" id="perusahaan_penerima" placeholder="Perusahaan..." class="form-control" value="'.$r['perusahaan_penerima'].'">
							</div>
						</div>
						<hr>
						
									<input value="';  if($r['lokasi_tujuan'] == 0 ){echo 1;}   echo '" type="hidden" name="tujuankosong" >
						
						<div class="form-group" id="kotatujuan">
							<label class="col-md-3 control-label">Kota Tujuan <span class="required">* </span></label>
							<div class="col-md-6">
								<select name="lokasi_tujuan" id="lokasi_tujuan" data-placeholder="Choose a City..." class="form-control select2">
									<option value="">Select...</option>';
									if ($r['lokasi_tujuan'] == 0){
										$kotatujuan = mysqli_query($con, "select a.id_kota, case when b.nama_kota is null then a.nama_kota when x_namakota(b.induk) is null then concat(a.nama_kota,', ',b.nama_kota) else concat(a.nama_kota,', ',b.nama_kota,', ',x_namakota(b.induk)) end as nama_kota from kota a left join kota b on b.id_kota=a.induk where a.kota_tujuan='Y' and a.aktif=1");
									}
									else{
										if (strpos($r['airwaybill_kode'], '002-') !== false){
											$kotatujuan = mysqli_query($con, "select a.id_kota, case when b.nama_kota is null then a.nama_kota when x_namakota(b.induk) is null then concat(a.nama_kota,', ',b.nama_kota) else concat(a.nama_kota,', ',b.nama_kota,', ',x_namakota(b.induk)) end as nama_kota from kota a left join kota b on b.id_kota=a.hub where a.kota_tujuan='Y' and a.intercity ='Y' and a.aktif=1");
										}
										else{
											$kotatujuan = mysqli_query($con, "select a.id_kota, case when b.nama_kota is null then a.nama_kota when x_namakota(b.induk) is null then concat(a.nama_kota,', ',b.nama_kota) else concat(a.nama_kota,', ',b.nama_kota,', ',x_namakota(b.induk)) end as nama_kota from kota a left join kota b on b.id_kota=a.induk where a.kota_tujuan='Y' and a.intercity !='y' and a.aktif=1");
											
										}
									}
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
						<div id="ifinternational" style="display:none">
							<div class="form-group">
								<label class="col-md-3 control-label">Negara Tujuan <span class="required">* </span></label>
								<div class="col-md-6">
									<select name="negara_tujuan" id="negara_tujuan" data-placeholder="Choose a Country..." class="form-control select2">
										<option value="">Select...</option>';
										$negaratujuan = mysqli_query($con, "select id_negara, nama_negara from negara order by nama_negara");
										while($n=mysqli_fetch_assoc($negaratujuan)){
											echo'
											<option value="'.$n['id_negara'].'">'.$n['nama_negara'].'</option>';
										}
										echo'
									</select>
									<div class="val-message"></div>
								</div>
							</div>
						</div>
						<hr>
						<div class="form-group">
							<label class="control-label col-md-3">Tipe Service <span class="required">* </span></label>
							<div class="col-md-6">
								<div class="radio radio-info">';
								if ($u['id_kustomer'] != 106 || $u['id_user'] == 1158){
									$service = mysqli_query($con, "select id_pelayanan, nama_pelayanan from pelayanan where active='Y'");
								}
								else{
									$service = mysqli_query($con, "select id_pelayanan, nama_pelayanan from pelayanan where id_pelayanan in (3,5)");	
								}
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
						';
						if ($u['id_kustomer'] == 106){
						echo '
							<div class="form-group">
								<label class="col-md-3 control-label">Upload Data Isi Catatan </label>
								<div class="col-md-6">
									<input type="file" name="uploadcatatan">
									<p class="help-block">Hanya bisa file ber-type .xls</p>
								</div>
							</div>
						';
						}

						
						$sql = mysqli_query($con, "select id_pengiriman,product,aprc,part_n,part_n_desc,rma_no,qty,remark from asus_detail_catatan  where id_pengiriman='".$id."'");

						$cek = mysqli_num_rows($sql);
						if ($cek > 0){
							echo '

							<div class="table-responsive">
							<table class="table" style="">
								<tr>
								<td><a href="'.$aksi.'?module=bookingin&act=delete_catatan&id_catatan='.$id.' ">delete all</a></td>
								<tr>

							    <thead>
							      <tr>
							        <th>NO</th>
							        <th>product</th>
							        <th>aprc</th>
							        <th>part n</th>
							        <th>part desc</th>
							        <th>rma no</th>
							        <th>qty</th>
							      </tr>
							    </thead>
							    <tbody>
							      ';
							      $cek2 = mysqli_num_rows($sql);
							      $no=1;
							      while ($row = mysqli_fetch_assoc($sql)) {
							      	
								    echo '  
								    <tr>
								      <td>'.$no.'</td>
								        <td>'.$row['product'].'</td> 
								        <td>'.$row['aprc'].'</td>
								        <td>'.$row['part_n'].'</td>
								        <td>'.$row['part_n_desc'].'</td>
								        <td>'.$row['rma_no'].'</td>
								        <td>'.$row['qty'].'</td>
								    </tr> 
								      ';
								      $no++;
							      }
							      echo '
							    </tbody>
							  </table>
							  </div>
							';
						}

						echo '

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
						<button type="button" class="btn btn-default" onclick="window.location.href = \'?module=bookingin\';">Cancel</button>
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
					<form method="post" action="'.$aksi.'?module=bookingin&act=upload" class="form-horizontal form-bordered" id="form-bookingin" autocomplete="off" enctype="multipart/form-data">
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
						';
						if ($u['other_customer'] == 'N'){
						echo '
							<div class="form-group">
								<label class="col-md-3 control-label">Customer <span class="required">* </span></label>
								<div class="col-md-6">
									<select name="id_kustomer" id="id_kustomerin" data-placeholder="Choose a Customer..." class="form-control select2">';
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
									';
									if($u['perusahaan_pengirim'] ==""){
										echo '<input type="checkbox" id="outbound" name="outbound" value="N"> Ceklist jika pengirim adalah customer';
									}
									echo '
									<input type="hidden" name="id_cabang" id="id_cabang" value="'.$u['id_cabang'].'">
								</div>
							</div>';
						}
						else {
							echo '
							<div class="form-group">
								<label class="col-md-3 control-label">Customer <span class="required">* </span></label>
								<div class="col-md-6">
									<select name="id_kustomer" id="id_kustomerin" data-placeholder="Choose a Customer..." class="form-control select2">';
									$result = mysqli_query($con,"select id_kustomer, nama_kustomer from kustomer where id_kustomer in ($u[customers]) order by nama_kustomer");
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
									
									';
									if($u['otomatis_pengirim'] != 1){
										echo '<input type="checkbox" id="outbound" name="outbound" value="N"> Ceklist jika pengirim adalah customer';
									}
									echo '
									<input type="hidden" name="id_cabang" id="id_cabang" value="'.$u['id_cabang'].'">
								</div>
							</div>';
						} echo '
						<div id="loc_code" style="display:none;">
							<div class="form-group">
								<label class="col-md-3 control-label">Loc Code <span class="required">* </span></label>
								<div class="col-md-6">
									<input type="text" name="loc_code" maxlength="20" id="input_loccode" disabled placeholder="Loc Code..." class="form-control">
								</div>
							</div>
						</div>
						<hr>
						<div class="form-group">
							<label class="col-md-3 control-label">Pengirim <span class="required">* </span></label>
							<div class="col-md-6">
								<input type="text" name="nama_pengirim" id="nama_pengirim" placeholder="Nama pengirim..." class="form-control">
								<textarea name="alamat_pengirim" id="alamat_pengirim" placeholder="Alamat pengirim..." id="alamat_pengirim" class="form-control" rows="2"></textarea>
								<input type="text" name="hp_pengirim" placeholder="No. Handphone..." class="form-control">
								<input type="text" name="perusahaan_pengirim" id="perusahaan_pengirim" placeholder="Perusahaan..." class="form-control">
							</div>
						</div>
						<hr>
						<div class="form-group">
							<label class="col-md-3 control-label">Kota Asal <span class="required">* </span></label>
							<div class="col-md-6">
								<select name="lokasi_asal" id="lokasi_asal" data-placeholder="Choose a City..." class="form-control select2">
									<option value="">Select...</option>';
									$kotatujuan = mysqli_query($con, "select a.id_kota, case when b.nama_kota is null then a.nama_kota when x_namakota(b.induk) is null then concat(a.nama_kota,', ',b.nama_kota) else concat(a.nama_kota,', ',b.nama_kota,', ',x_namakota(b.induk)) end as nama_kota from kota a left join kota b on b.id_kota=a.induk where a.kota_tujuan='Y' and a.aktif=1");
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
								<div class="val-message"></div>
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
								if ($u['id_kustomer'] != 106 || $u['id_user'] == 1158){
									$service = mysqli_query($con, "select id_pelayanan, nama_pelayanan from pelayanan where active='Y'");
								}
								else{
									$service = mysqli_query($con, "select id_pelayanan, nama_pelayanan from pelayanan where id_pelayanan in (3,5)");	
								}
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
							';/*
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
							</div>*/
							
							if ($u['id_kustomer'] != 106 || $u['id_user'] == 1158){
							echo '
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
							';
						}
						echo '
						</div>
						<hr>
						';
							if ($u['id_kustomer'] != 106 || $u['id_user'] == 1158){
							echo '
						<div class="form-group">
							<label class="col-md-3 control-label">Asuransi <small class="required">*</small></label>
							<div class="col-md-6">
								<div class="radio radio-info">
									<label><input type="radio" value="Y" name="asuransi" id="yesAsuransi">Ya </label><br>
									<label><input type="radio" value="N" name="asuransi" checked id="noAsuransi">Tidak </label>
								</div>
							</div>
						</div>
						';
						}
						echo '
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
						';
						/*<div class="form-group">
							<label class="col-md-3 control-label">Catatan Khusus Kiriman</label>
							<div class="col-md-6">
								<textarea name="catatan" placeholder="Catatan khusus kiriman ..." class="form-control" rows="2"></textarea>
							</div>
						</div>*/
						echo '						
						<hr>
						<div class="form-group">
							<label class="col-md-3 control-label">Pembayaran Pengiriman<br/>
							<small class="required">Dilakukan Oleh</small></label>
							<div class="col-md-6">
								<div class="radio radio-info">
									<label><input type="radio" value="1" name="biaya_beban" id="pengirim" checked>Pengirim </label><br>
									<label><input type="radio" value="2" name="biaya_beban" id="penerima"> Penerima</label><br>';
									//<label><input type="radio" value="3" name="biaya_beban" id="pihak3"> Pihak Ketiga </label><br>
									echo '<label><input type="radio" value="4" name="biaya_beban" id="tunai"> Tunai</label>
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
						<button type="button" class="btn btn-default" onclick="window.location.href = \'?module=bookingin\';">Cancel</button>
						<button type="submit" id="upload-save" class="btn btn-primary pull-right">Save</button>
					</div><!-- /.box-footer -->
					</form>
				</div><!-- /.box -->
			</section>';
			
			break;


			case "upload-asus":

				if ($u['id_kustomer'] == 106){
					echo '
					<div class="box box-primary">
						<div class="box-header with-border">
							<h3 class="box-title">Upload Asus</h3>						
						</div><!-- /.box-header -->
						<div class="box-body">
							<form method="post" action="'.$aksi.'?module=bookingin&act=upload-asus-all" class="form-horizontal form-bordered" id="form-bookingin" autocomplete="off" enctype="multipart/form-data">
									<div class="form-group">
										<label class="col-md-3 control-label">Upload Data Isi Barang </label>
										<div class="col-md-6">
											<input type="file" name="uploadcatatan">
											<p class="help-block">Hanya bisa file ber-type .xls</p>
										</div>
									</div>
							
						</div>

						<div class="box-footer">
							<button type="button" class="btn btn-default" onclick="window.location.href = \'?module=bookingin\';">Cancel</button>
							<button type="submit" id="upload-save" class="btn btn-primary pull-right">Save</button>
							</form>
						</div><!-- /.box-footer -->
					</div>
					';
				}
			break;			
		}
}
?>