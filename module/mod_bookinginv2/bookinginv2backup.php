<section class="content-header">
	<h1>Booking<small>outbound/inbound</small></h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-home"></i> Home</a></li>
		<li><a href="#">Application</a></li>
		<li class="active">Booking</li>
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

if (empty($_SESSION['username']) AND empty($_SESSION['password'])){
	header('location:../application/login.php');
}
else{
	//cek hak akses user
	if($u['level']=='admin' || $u['level']=='network' || $u['level']=='operator' || $u['level']=='intercity' || $u['level']=='domex' || $u['level']=='logistics' || $u['id_user']==9 ||strpos($u['menu_tambahan'], 'booking') !== false || $u['level']=='finance' || $u['level']=='inbound' || $u['level']=='admin_network'){

		$aksi	= "module/mod_bookinginv2/bookinginv2_action.php";
		
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
											';
											if ($u['level'] != 'finance'){
												echo '<button class="btn btn-sm btn-primary" onclick="window.location.href = \'?module=bookinginv2&act=create\';">Add New <i class="fa fa-plus"></i></button>';
											}
											echo '
										</div>
									</div>
									<div class="col-md-6">
										<div class="btn-group pull-right">
											<button class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown">Tools <i class="fa fa-angle-down"></i></button>
											<ul class="dropdown-menu pull-right">
												<li><a href="javascript:;" onclick="window.location.href = \'?module=bookinginv2&act=upload\';"><i class="fa fa-upload"></i> Upload Excel </a></li>
												<li><a href="main.php?module=bookinginv2&act=handover"><i class="fa fa-print"></i>Handover Courier</a></li>
											</ul>
										</div>
									</div>
								</div>
							</div>
							<div class="box-body">
								<table id="bookinginv2" class="table table-bordered table-striped table-hover" width="100%" cellspacing="0" style="white-space:nowrap;">
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
			</section>


			<div id="modal_sla_insert" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
							<h4 class="modal-title" id="myModalLabel">Insert SLA</h4>
						</div>
						<div class="modal-content">							
							<div class="modal-body"></div>
						</div>
					</div>
				</div>
			</div>


			';
			
			break;
			
			case "test":
			
			echo 
			'<form method="post" action="'.$aksi.'?module=bookinginv2&act=testing" class="form-horizontal form-bordered" " autocomplete="off" >

				<span class="input-group-addon">Rp</span> <input type="text" val="100000" min="100000" name="nilai_barang" id="nilai_barang" class="form-control">
				
			</form>';
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
					<form method="post" action="'.$aksi.'?module=bookinginv2&act=create" class="form-horizontal form-bordered" id="form-bookinginv2" autocomplete="off" enctype="multipart/form-data">
					<div class="box-body">
						<div class="form-group">
							<label class="col-sm-3 control-label full-right">Tanggal <span class="required">* </span></label>
							<div class="col-sm-3">
								<div class="input-group">
									<div class="input-group-addon">
										<i class="fa fa-calendar"></i>
									</div>
									<input type="text" class="form-control" id="tanggal" name="tanggal" value="'.$setdate.'" readonly>
									<input type="text" class="form-control" id="jam" name="jam" value="'.date('h:i:sa').'" readonly>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label">Customer <span class="required">* </span></label>
							<div class="col-md-6">
								<select name="id_kustomer" id="id_kustomerin" data-placeholder="Choose a Customer..." class="form-control select2" required>';
								$result = mysqli_query($con,"select id_kustomer, nama_kustomer from kustomer where status!='N' order by nama_kustomer");
								echo '<option value="">Select...</option>';
								while ($row = mysqli_fetch_assoc($result)) {
									echo '<option value="' . $row['id_kustomer'] . '">' . $row['nama_kustomer'] . '</option>';
								}
								echo'
								</select>
								<div class="val-message"></div> <input type="checkbox" id="outbound" name="outbound" value="N"> Ceklist jika pengirim adalah customer / Outbound
								<input type="hidden" name="id_cabang" id="id_cabang">
							</div>
						</div>
						<div id="loc_code" style="display:none;">
							<div class="form-group">
								<label class="col-md-3 control-label">Loc Code <span class="required">* </span></label>
								<div class="col-md-6">
									<input type="text" name="loc_code" id="input_loccode" disabled placeholder="Loc Code..." class="form-control">
								</div>
							</div>
						</div>

						';
							//if($u['id_kustomer'] == 13 || $u['id_kustomer'] == 29 || $u['id_kustomer'] == 31){
								echo '
								<div id="pidkolom"  style="display:none;">
									<div class="form-group" >
										<label class="col-md-3 control-label">NO PID  <span class="required">* </span></label>
										<div class="col-md-6">

											<input type="text" name="pid" id="pid" maxlength="20" placeholder="NO PID" class="form-control"  maxlength="20" required disabled>

										</div>
									</div>
								</div>
								';

							//}
					

							echo '
						<hr>
						<div class="form-group">
							<label class="control-label col-md-3">Tipe Service <span class="required">* </span></label>
							<div class="col-md-6">
								<div class="radio radio-info">';
								$service = mysqli_query($con, "select id_pelayanan, nama_pelayanan from pelayanan where active='Y'");
								while($s=mysqli_fetch_assoc($service)){
									echo'
									<label><input type="radio" name="service" value="'.$s['id_pelayanan'].'" class="seafreight"/> '.$s['nama_pelayanan'].' </label><br>';
								}
								echo'
								</div>
							</div>
						</div>
						<hr>

						<div class="form-group">
							<label class="col-md-3 control-label">Pengirim <span class="required">* </span></label>
							<div class="col-md-6">
								<input type="text" name="nama_pengirim" id="nama_pengirim" maxlength="40" placeholder="Nama pengirim..." class="form-control" required>

								<input type="text"  name="hp_pengirim" id="hp_pengirim" placeholder="No. Handphone..." maxlength="13" class="form-control" required>
								<input type="text" name="perusahaan_pengirim" id="perusahaan_pengirim" maxlength="60" placeholder="Perusahaan..." class="form-control" required>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label">Alamat Pengirim <span class="required">* </span></label>
							<div class="col-md-6">
								<input type="text" name="alamat_pengirim1" id="alamat_pengirim1" maxlength="52"  class="form-control" placeholder="Detail Alamat Pengirim" required>
								<input type="text" name="alamat_pengirim2" id="alamat_pengirim2"  maxlength="52"  class="form-control" placeholder="Detail Alamat Pengirim Lanjutan">
							</div>
						</div>
						<div class="form-group" id="kotaasall">
							<label class="col-md-3 control-label">Kota Asal  <span class="required">* </span></label>
							<div class="col-md-6">
								<select name="provinsiasal" id="provinsiasal" data-placeholder="Choose a provinces..." class="form-control select2">
									<option value="">Select...</option>';
									/*$kotatujuan = mysqli_query($con, "select a.id_kota, case when b.nama_kota is null then a.nama_kota when x_namakota(b.induk) is null then concat(a.nama_kota,', ',b.nama_kota) else concat(a.nama_kota,', ',b.nama_kota,', ',x_namakota(b.induk)) end as nama_kota from kota a left join kota b on b.id_kota=a.induk where a.kota_tujuan='Y'");
									while($t=mysqli_fetch_assoc($kotatujuan)){
										if(!empty($t['kode_kota'])){
											echo'
											<option value="'.$t['id_kota'].'">'.$t['nama_kota'].' ('.$t['kode_kota'].')</option>';
										}else{
											echo'
											<option value="'.$t['id_kota'].'">'.$t['nama_kota'].'</option>';
										}
									}*/

									$sql_provinsi = mysqli_query($con,"SELECT * FROM provinces ORDER BY name ASC");

									while($rs_provinsi = mysqli_fetch_assoc($sql_provinsi)){ 
										
											echo'
											<option value="'.$rs_provinsi['id'].'">'.$rs_provinsi['name'].'</option>';
										
									}


									echo'
								</select>
								<input type="hidden" name="provinsiasal_text" id="provinsiasal_text"   class="form-control" >
								
								

								<select name="kotaasal" id="kotaasal" data-placeholder="Choose a City..." class="form-control select2">
									<option value="">Select...</option>';
									/*$kotatujuan = mysqli_query($con, "select a.id_kota, case when b.nama_kota is null then a.nama_kota when x_namakota(b.induk) is null then concat(a.nama_kota,', ',b.nama_kota) else concat(a.nama_kota,', ',b.nama_kota,', ',x_namakota(b.induk)) end as nama_kota from kota a left join kota b on b.id_kota=a.induk where a.kota_tujuan='Y'");
									while($t=mysqli_fetch_assoc($kotatujuan)){
										if(!empty($t['kode_kota'])){
											echo'
											<option value="'.$t['id_kota'].'">'.$t['nama_kota'].' ('.$t['kode_kota'].')</option>';
										}else{
											echo'
											<option value="'.$t['id_kota'].'">'.$t['nama_kota'].'</option>';
										}
									}*/

									


									echo'
								</select>
								<input type="hidden" name="kotaasal_text" id="kotaasal_text"   class="form-control" >
								<select name="kecamatanasal" id="kecamatanasal" data-placeholder="Choose a Kecamatan..." class="form-control select2" required>
									<option value="">Select...</option>';
									/*$kotatujuan = mysqli_query($con, "select a.id_kota, case when b.nama_kota is null then a.nama_kota when x_namakota(b.induk) is null then concat(a.nama_kota,', ',b.nama_kota) else concat(a.nama_kota,', ',b.nama_kota,', ',x_namakota(b.induk)) end as nama_kota from kota a left join kota b on b.id_kota=a.induk where a.kota_tujuan='Y'");
									while($t=mysqli_fetch_assoc($kotatujuan)){
										if(!empty($t['kode_kota'])){
											echo'
											<option value="'.$t['id_kota'].'">'.$t['nama_kota'].' ('.$t['kode_kota'].')</option>';
										}else{
											echo'
											<option value="'.$t['id_kota'].'">'.$t['nama_kota'].'</option>';
										}
									}*/

									

									echo'
								</select>
								<input type="hidden" name="kecamatanasal_text" id="kecamatanasal_text"   class="form-control" >
								<select name="kelurahanasal" id="kelurahanasal" data-placeholder="Choose a Desa/Kelurahan..." class="form-control select2">
									<option value="">Select...</option>';
									/*$kotatujuan = mysqli_query($con, "select a.id_kota, case when b.nama_kota is null then a.nama_kota when x_namakota(b.induk) is null then concat(a.nama_kota,', ',b.nama_kota) else concat(a.nama_kota,', ',b.nama_kota,', ',x_namakota(b.induk)) end as nama_kota from kota a left join kota b on b.id_kota=a.induk where a.kota_tujuan='Y'");
									while($t=mysqli_fetch_assoc($kotatujuan)){
										if(!empty($t['kode_kota'])){
											echo'
											<option value="'.$t['id_kota'].'">'.$t['nama_kota'].' ('.$t['kode_kota'].')</option>';
										}else{
											echo'
											<option value="'.$t['id_kota'].'">'.$t['nama_kota'].'</option>';
										}
									}*/

									


									echo'
								</select>
								
							</div>

						</div>
						
						<hr>
						<div class="form-group">
							<label class="col-md-3 control-label">Penerima <span class="required">* </span></label>
							<div class="col-md-6">
								<input type="text" name="nama_penerima" id="nama_penerima" maxlength="40" placeholder="Nama penerima..." class="form-control">

								<input type="text" name="hp_penerima" id="hp_penerima" maxlength="13" placeholder="No. Handphone..." class="form-control" required>
								<input type="text" name="perusahaan_penerima" id="perusahaan_penerima" maxlength="60" placeholder="Perusahaan..." class="form-control" required>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label">Alamat Penerima <span class="required">* </span></label>
							<div class="col-md-6">
								<input type="text" name="alamat_penerima1" id="alamat_penerima1"  maxlength="52"  class="form-control" required  placeholder="Detail Alamat Penerima">
								<input type="text" name="alamat_penerima2" id="alamat_penerima2"  maxlength="52"  class="form-control"  placeholder="Detail Alamat Penerima Lanjutan">
							</div>
						</div>
						
						<div class="form-group" id="kotatujuan">
							<label class="col-md-3 control-label">Kota Tujuan  <span class="required">* </span></label>
							<div class="col-md-6">
								<select name="provinsi" id="provinsi" data-placeholder="Choose a provinces..." class="form-control select2">
									<option value="">Select...</option>';
									/*$kotatujuan = mysqli_query($con, "select a.id_kota, case when b.nama_kota is null then a.nama_kota when x_namakota(b.induk) is null then concat(a.nama_kota,', ',b.nama_kota) else concat(a.nama_kota,', ',b.nama_kota,', ',x_namakota(b.induk)) end as nama_kota from kota a left join kota b on b.id_kota=a.induk where a.kota_tujuan='Y'");
									while($t=mysqli_fetch_assoc($kotatujuan)){
										if(!empty($t['kode_kota'])){
											echo'
											<option value="'.$t['id_kota'].'">'.$t['nama_kota'].' ('.$t['kode_kota'].')</option>';
										}else{
											echo'
											<option value="'.$t['id_kota'].'">'.$t['nama_kota'].'</option>';
										}
									}*/

									$sql_provinsi = mysqli_query($con,"SELECT * FROM provinces ORDER BY name ASC");

									while($rs_provinsi = mysqli_fetch_assoc($sql_provinsi)){ 
										
											echo'
											<option value="'.$rs_provinsi['id'].'">'.$rs_provinsi['name'].'</option>';
										
									}


									echo'
								</select>
								<input type="hidden" name="provinsi_text"  id="provinsi_text">
								';
								//if($u['id_user'] == 83){
								echo '
									<input type="hidden" name="serviceshow" id="serviceshow">
									<input type="hidden" name="intercityasal" id="intercityasal">
									
									<input type="hidden" name="intercitytujuan" id="intercitytujuan">';
								//}
								echo '
								<div class="val-message"></div>

								<select name="kota" id="kota" data-placeholder="Choose a City..." class="form-control select2">
									<option value="">Select...</option>';
									/*$kotatujuan = mysqli_query($con, "select a.id_kota, case when b.nama_kota is null then a.nama_kota when x_namakota(b.induk) is null then concat(a.nama_kota,', ',b.nama_kota) else concat(a.nama_kota,', ',b.nama_kota,', ',x_namakota(b.induk)) end as nama_kota from kota a left join kota b on b.id_kota=a.induk where a.kota_tujuan='Y'");
									while($t=mysqli_fetch_assoc($kotatujuan)){
										if(!empty($t['kode_kota'])){
											echo'
											<option value="'.$t['id_kota'].'">'.$t['nama_kota'].' ('.$t['kode_kota'].')</option>';
										}else{
											echo'
											<option value="'.$t['id_kota'].'">'.$t['nama_kota'].'</option>';
										}
									}*/

									


									echo'
								</select>
								<input type="hidden" name="kota_text"  id="kota_text">

								<select name="kecamatan" id="kecamatan" data-placeholder="Choose a Kecamatan..." class="form-control select2">
									<option value="">Select...</option>';
									/*$kotatujuan = mysqli_query($con, "select a.id_kota, case when b.nama_kota is null then a.nama_kota when x_namakota(b.induk) is null then concat(a.nama_kota,', ',b.nama_kota) else concat(a.nama_kota,', ',b.nama_kota,', ',x_namakota(b.induk)) end as nama_kota from kota a left join kota b on b.id_kota=a.induk where a.kota_tujuan='Y'");
									while($t=mysqli_fetch_assoc($kotatujuan)){
										if(!empty($t['kode_kota'])){
											echo'
											<option value="'.$t['id_kota'].'">'.$t['nama_kota'].' ('.$t['kode_kota'].')</option>';
										}else{
											echo'
											<option value="'.$t['id_kota'].'">'.$t['nama_kota'].'</option>';
										}
									}*/

									

									echo'
								</select>
								<input type="hidden" name="kecamatan_text"  id="kecamatan_text">
								<select name="kelurahan" id="kelurahan" data-placeholder="Choose a Desa/Kelurahan..." class="form-control select2">
									<option value="">Select...</option>';
									/*$kotatujuan = mysqli_query($con, "select a.id_kota, case when b.nama_kota is null then a.nama_kota when x_namakota(b.induk) is null then concat(a.nama_kota,', ',b.nama_kota) else concat(a.nama_kota,', ',b.nama_kota,', ',x_namakota(b.induk)) end as nama_kota from kota a left join kota b on b.id_kota=a.induk where a.kota_tujuan='Y'");
									while($t=mysqli_fetch_assoc($kotatujuan)){
										if(!empty($t['kode_kota'])){
											echo'
											<option value="'.$t['id_kota'].'">'.$t['nama_kota'].' ('.$t['kode_kota'].')</option>';
										}else{
											echo'
											<option value="'.$t['id_kota'].'">'.$t['nama_kota'].'</option>';
										}
									}*/

									


									echo'
								</select>
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
								<div class="col-md-6"><input type="text" name="jenis_barang" class="form-control" maxlength="25"></div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label">Jumlah Collie/Pcs</label>
								<div class="col-md-6"><input type="number" onchange="hitung1();" id="collie" name="jumlah_collie" class="form-control " value="1" min="1" max="40"></div>
								
							</div>
							
							';
							if($u['id_user'] != '9999'){

								echo '						
									<div class="form-group"  id="detail_collie1">
										<label class="col-md-2 control-label">Detail Collie 1</label>
										<div class="col-md-10">
											<div class="row">
													<div class="col-md-2">
														<div class="input-group">
															<input type="number" id="weight1" onchange="hitung1();"  min="1" value="1" name="cweight[1]" placeholder="berat..." class="form-control" max="350"> <span class="input-group-addon">kg</span>
														</div>
													</div>
													<div class="col-md-2">
														<div class="input-group">
															<input type="number" id="width1" onchange="hitung1();" min="1" name="cwidth[1]" placeholder="Width..." class="form-control" max="350" value="1"> <span class="input-group-addon">cm</span>
														</div>
													</div>
													<div class="col-md-2">
														<div class="input-group">
															<input type="number" onchange="hitung1();" id="length1" min="1" value="1" name="clength[1]" placeholder="Length..." class="form-control" max="350"> <span class="input-group-addon">cm</span>
														</div>
													</div>
													
													<div class="col-md-2">
														<div class="input-group">
															<input type="number" onchange="hitung1();" id="height1" min="1" name="cheight[1]" placeholder="Height..." class="form-control" max="350" value="1"> <span class="input-group-addon">cm</span>
														</div>
													</div>
													<div class="col-md-4">
														<div class="input-group">
															<input type="number" onchange="hitungtotal();"  id="total1" readonly name="ctotal[1]" min="1" placeholder="total..." class="form-control" max="350" value="1" > <span class="input-group-addon">kg</span>
														</div>
													</div>
												
											</div>	

										</div>
									</div>';

								for ($i = 2; $i < 41; $i++) {	

								echo '						
									<div class="form-group" style="display:none;" id="detail_collie'.$i.'">
										<label class="col-md-2 control-label">Detail Collie '.$i.'</label>
										<div class="col-md-10">
											<div class="row">
													<div class="col-md-2">
														<div class="input-group">
															<input type="number" onchange="hitung'.$i.'();" id="weight'.$i.'" min="1" value="1" name="cweight['.$i.']" placeholder="berat..." class="form-control" max="350"> <span class="input-group-addon">kg</span>
														</div>
													</div>
													<div class="col-md-2">
														<div class="input-group">
															<input type="number" onchange="hitung'.$i.'();" id="width'.$i.'" min="1" name="cwidth['.$i.']" placeholder="Width..." class="form-control" max="350" value="1"> <span class="input-group-addon">cm</span>
														</div>
													</div>
													<div class="col-md-2">
														<div class="input-group">
															<input type="number" onchange="hitung'.$i.'();" id="length'.$i.'" min="1" value="1" name="clength['.$i.']" placeholder="Length..." class="form-control" max="350"> <span class="input-group-addon">cm</span>
														</div>
													</div>
													
													<div class="col-md-2">
														<div class="input-group">
															<input type="number" onchange="hitung'.$i.'();" id="height'.$i.'" min="1" name="cheight['.$i.']" placeholder="Height..." class="form-control" max="350" value="1"> <span class="input-group-addon">cm</span>
														</div>
													</div>
													<div class="col-md-4">
														<div class="input-group">
															<input type="number" readonly id="total'.$i.'" name="ctotal['.$i.']" min="1" placeholder="total..." class="form-control" max="350" value="1" onchange="total'.$i.'();"> <span class="input-group-addon">kg</span>
														</div>
													</div>
												
											</div>	

										</div>
									</div>';
								}
							}
							echo '
							<div class="form-group">
								<label class="col-md-3 control-label">Total Estimasi Berat Barang</label>
								<div class="col-md-6">
									<div class="input-group">
										<input type="text" id="berat_barang" name="berat_barang" class="form-control" value="1" readonly max="10000"> <span class="input-group-addon">Kg</span>
									</div>
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-3 control-label">Detail Catatan Barang <small class="required">*</small></label>
								<div class="col-md-6">
									<div class="radio radio-info">
										<label><input type="radio" value="Y" name="detail_barang" id="yesdetailbarang">Ya </label><br>
										<label><input type="radio" value="N" name="detail_barang" checked id="nodetailbarang">Tidak </label>
									</div>
									
								</div>
							</div>
							<div id="ifdetailbarang" style="display:none">
								<div class="form-group" style="display:none">
									<label class="col-md-3 control-label">Jumlah Detail Barang</label>
									<div class="col-md-6"><input type="number" id="jumlah_detailbarang" name="jumlah_detailbarang" readonly class="form-control " value="1" min="1" max="40"></div>
								</div>

								';
								echo '
									<div class="form-group"  ">
										
										<label class="col-md-3 control-label">Koli ke</label>
										<label class="col-md-2 control-label">Detail isi catatan barang</label>
									</div>
								';
								echo '				
									
									<div class="form-group"  id="detail_barang1">
										<label class="col-md-2 control-label">Detail Catatan Barang 1</label>
										<div class="col-md-8">
											<div class="row">
													
													<div class="col-md-2">
														
															<input type="number" id="koli_ke1"  min="1" name="d_koli[1]" placeholder="koli ke" value="1" class="form-control d_koli_ke"  value="1" onkeyup="if(this.value > this.max) this.value = null;"> 
														
													</div>
													<div class="col-md-10">
														
															<input type="text" id="detail_catatan_barang1"   value="" maxlength="130" name="d_barang[1]" placeholder="detail catatan..." class="form-control"> 
														
													</div>
													
												
											</div>	

										</div>
									</div>';

								for ($i = 2; $i < 41; $i++) {	

								echo '						
									<div class="form-group" style="display:none;" id="barisdetail_barang'.$i.'">
										<label class="col-md-2 control-label">Detail Catatan Barang '.$i.'</label>
										<div class="col-md-8">
											<div class="row">
													<div class="col-md-2">
														
															<input type="number" id="koli_ke'.$i.'" min="1"  name="d_koli['.$i.']" placeholder="koli ke..." class="form-control d_koli_ke" max="1" value="1" onkeyup="if(this.value > this.max) this.value = null;">
														
													</div>
													<div class="col-md-10">
														
															<input type="text"  id="detail_catatan_barang'.$i.'"   value="" name="d_barang['.$i.']" placeholder="detail catatan...." class="form-control" >
														
													</div>
													
													
												
											</div>	

										</div>
									</div>';
								}

								echo '
								<div class="row">
									<div class="col-md-10">
										<button type="button" class="btn   btn-primary pull-right" id="detailbarang_plus" style="margin-right:0px;margin-left:10px;">+</button>
										<button type="button" class="btn  pull-right" id="detailbarang_min">-</button>
									</div>
								</div>
								</>
							</div>


							
							
							';
							

							echo '
							
							<div class="form-group">
								<label class="col-md-3 control-label">Packing</label>
								<div class="col-md-6">
									<div class="radio radio-info">
										<label><input type="radio" value="1" name="packing_kayu" class="packing" checked>Packing Oleh Pemilik </label><br>
										<label><input type="radio" value="2" name="packing_kayu" class="packing">Packing Kayu Oleh Cesco Log.</label><br>	
										
									</div>
									<div  id="val-packing" style="display:none;">sdasd</div>	
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
										<span class="input-group-addon">Rp</span> <input type="text" val="100000" min="100000" name="nilai_barang" id="nilai_barang" class="form-control">
									</div>
									<div  id="val-asu" style="display:none;"></div>	
								</div>

							</div>
						</div>
						<hr>
						<div class="form-group">
							<label class="col-md-3 control-label">Surat Jalan Kembali</label>
							<div class="col-md-6">
								<div class="radio radio-info">
									<label><input type="radio" value="Y" name="surat_jalan" class="surat_jalan" >Ya </label><br>
									<label><input type="radio" value="N" name="surat_jalan" class="surat_jalan" checked>Tidak </label>
								</div>
							</div>
						</div>

						<div class="form-group" id="ifyessuratjalan" style="display:none;"> 
							<label class="col-md-3 control-label">Pilihan Surat Jalan Kembali</label>
							<div class="col-md-6">
								<div class="radio radio-info">
									<label><input type="radio" value="free" name="surat_jalan_charge" checked>30 Hari Kerja Dari Tanggal Terima Barang :</label><br>
									Free Of Charges <br><br>
									<label><input type="radio" value="charges" name="surat_jalan_charge"> 10 Hari Kerja Dari Tanggal Terima Barang :  </label><br>
									Area Jawa Rp 30.000 / AWB
									<br>Area Sumatera Rp 40.000 / AWB
									<br>Area Kalimantan, Bali, Nusa Tenggara Rp 50.000 / AWB
									<br>Area Maluku dan Jayapura Rp 70.000/ AWB
								</div>
							</div>
						</div>
						<hr>
						<div class="form-group">
							<label class="col-md-3 control-label">Pembayaran Khusus</label>
							<div class="col-md-6">
								<div class="radio radio-info">
									<label><input type="radio" value="1" name="pembayaran_khusus" class="pembayaran_khusus" >Ya </label><br>
									<label><input type="radio" value="0" name="pembayaran_khusus" class="pembayaran_khusus" checked>Tidak </label>
								</div>
							</div>
						</div>
						<hr>
						';
						if($u['id_user'] != '9999' ){
							echo '
								<div class="form-group">
									<label class="col-md-3 control-label">Catatan Khusus Kiriman</label>
									<div class="col-md-6">
										<input type="text" name="catatanbaru[1]" id="catatanbaru1" placeholder="Catatan kiriman 1" class="form-control" maxlength="80" required>
										
										';
										for($i=2;$i<41;$i++){
											echo '
											<input type="text"  disabled style="display:none;" name="catatanbaru['.$i.']" id="catatanbaru'.$i.'" placeholder="Catatan kiriman '.$i.'" class="form-control" maxlength="80" required>';
										}
										echo '

									</div>
								</div>
							';	
						}
						


						echo '
						<hr>
						<div class="form-group">
							<label class="col-md-3 control-label">Upload Data Isi Catatan (Khusus Asus) </label>
							<div class="col-md-6">
								<input type="file" name="uploadcatatan">
								<p class="help-block">Hanya bisa file ber-type .xls</p>
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-md-3 control-label">Pembayaran Pengiriman<br/>
							<small class="required">Dilakukan Oleh</small></label>
							<div class="col-md-6">
								<div class="radio radio-info">
									<label><input type="radio" value="1" name="biaya_beban" id="pengirim" checked>Pengirim </label><br>
									<label><input type="radio" value="2" name="biaya_beban" id="penerima"> Penerima</label><br>';
									//<label><input type="radio" value="3" name="biaya_beban" id="pihak3"> Pihak Ketiga </label><br>
									echo '
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
						<button type="button" class="btn btn-default" onclick="window.location.href = \'?module=bookinginv2\';">Cancel</button>
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

			$query = mysqli_query($con, "select *,SUBSTRING_INDEX(ukuran_barang,'x',1) as length, SUBSTRING_INDEX(SUBSTRING_INDEX(ukuran_barang,'x',2),'x',-1) as width, SUBSTRING_INDEX(ukuran_barang,'x',-1) as height, DATE_FORMAT(created_date,'%Y%m%d') as folder, x_inter(id_kota_asal) as intercityasal, x_inter(id_kota_tujuan) as intercitytujuan from pengiriman where id_pengiriman='$id'");
			$r = mysqli_fetch_assoc($query);
			
			$tanggal = tgl_indo($r['tanggal']);
									
			echo'
			<section class="content">
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">UPDATE AWB '.$r['airwaybill_kode'].'</h3>
						<div class="box-tools pull-right">
							<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
							<button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
						</div>
					</div><!-- /.box-header -->
					<form method="post" action="'.$aksi.'?module=bookinginv2&act=update" class="form-horizontal form-bordered" id="form-bookinginv2" autocomplete="off"  enctype="multipart/form-data">
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
								$result = mysqli_query($con,"select id_kustomer, nama_kustomer from kustomer where status!='N' order by nama_kustomer");
								echo '<option value="">Select...</option>';
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
						$loccode = mysqli_query($con, 'select loc_code,pid from kustomer where id_kustomer="'.$r['id_kustomer'].'"');
						$l = mysqli_fetch_assoc($loccode);
						if($l['loc_code']=='Y'){
							echo'
							<div id="loc_code">
								<div class="form-group">
									<label class="col-md-3 control-label">Loc Code <span class="required">* </span></label>
									<div class="col-md-6">
										<input type="text" name="loc_code" placeholder="Loc Code..." class="form-control" value="'.$r['loc_code'].'">
									</div>
								</div>
							</div>';
						}else{
							echo'
							<div id="loc_code" style="display:none;">
								<div class="form-group">
									<label class="col-md-3 control-label">Loc Code <span class="required">* </span></label>
									<div class="col-md-6">
										<input type="text" name="loc_code" placeholder="Loc Code..." class="form-control" disabled>
									</div>
								</div>
							</div>';
						}
						if($l['pid']=='Y' || $r['id_kustomer'] == 13 || $r['id_kustomer'] == 29 || $r['id_kustomer'] == 31){
								echo '
								<div class="form-group">
									<label class="col-md-3 control-label">NO PID  <span class="required">* </span></label>
									<div class="col-md-6">

										<input type="text" name="pid" id="pid" maxlength="20" placeholder="NO PID" class="form-control" required maxlength="20"  value="'.$r['pid'].'">

									</div>
								</div>
								';
							}
						echo'
						<hr>
						<div class="form-group">
							<label class="col-md-3 control-label">Pengirim <span class="required">* </span></label>
							<div class="col-md-6">
								<input type="text" name="nama_pengirim" value="'.$r['nama_pengirim'].'" id="nama_pengirim" maxlength="40" placeholder="Nama pengirim..." class="form-control" required>

								<input type="text"  name="hp_pengirim" id="hp_pengirim" placeholder="No. Handphone..." maxlength="13" class="form-control" value="'.$r['hp_pengirim'].'" required>
								<input type="text" name="perusahaan_pengirim"  id="perusahaan_pengirim" value="'.$r['perusahaan_pengirim'].'" maxlength="60" placeholder="Perusahaan..." class="form-control" required>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label">Alamat Pengirim <span class="required">* </span></label>
							<div class="col-md-6">
								<input type="text" name="alamat_pengirim1" id="alamat_pengirim1" maxlength="52" value="'.$r['alamat_pengirim'].'"  class="form-control" placeholder="Detail Alamat Pengirim" required>
								<input type="text" name="alamat_pengirim2" id="alamat_pengirim2"  maxlength="52" value="'.$r['alamat_pengirim2'].'" class="form-control" placeholder="Detail Alamat Pengirim Lanjutan">
							</div>
						</div>
						<hr>
						<div class="form-group" id="kotaasall">
							<label class="col-md-3 control-label">Kota Asal  <span class="required">* </span></label>
							<div class="col-md-6">
								<select name="provinsiasal" id="provinsiasal" data-placeholder="Choose a provinces..." class="form-control select2">
									<option value="">Select...</option>';
									/*$kotatujuan = mysqli_query($con, "select a.id_kota, case when b.nama_kota is null then a.nama_kota when x_namakota(b.induk) is null then concat(a.nama_kota,', ',b.nama_kota) else concat(a.nama_kota,', ',b.nama_kota,', ',x_namakota(b.induk)) end as nama_kota from kota a left join kota b on b.id_kota=a.induk where a.kota_tujuan='Y'");
									while($t=mysqli_fetch_assoc($kotatujuan)){
										if(!empty($t['kode_kota'])){
											echo'
											<option value="'.$t['id_kota'].'">'.$t['nama_kota'].' ('.$t['kode_kota'].')</option>';
										}else{
											echo'
											<option value="'.$t['id_kota'].'">'.$t['nama_kota'].'</option>';
										}
									}*/

									$sql_provinsi = mysqli_query($con,"SELECT * FROM provinces ORDER BY name ASC");

									while($rs_provinsi = mysqli_fetch_assoc($sql_provinsi)){ 
										
											echo'
											<option value="'.$rs_provinsi['id'].'" '.($r['id_provinsi_asal']==$rs_provinsi['id'] ? 'selected' : '' ).'>'.$rs_provinsi['name'].'</option>';
										
									}


									echo'
								</select>
								<input type="hidden" name="provinsiasal_text" id="provinsiasal_text" value="'.$r['provinsi_asal'].'"  class="form-control" >
								
								

								<select name="kotaasal" id="kotaasal" data-placeholder="Choose a City..." class="form-control select2">
									<option value="">Select...</option>';
									/*$kotatujuan = mysqli_query($con, "select a.id_kota, case when b.nama_kota is null then a.nama_kota when x_namakota(b.induk) is null then concat(a.nama_kota,', ',b.nama_kota) else concat(a.nama_kota,', ',b.nama_kota,', ',x_namakota(b.induk)) end as nama_kota from kota a left join kota b on b.id_kota=a.induk where a.kota_tujuan='Y'");
									while($t=mysqli_fetch_assoc($kotatujuan)){
										if(!empty($t['kode_kota'])){
											echo'
											<option value="'.$t['id_kota'].'">'.$t['nama_kota'].' ('.$t['kode_kota'].')</option>';
										}else{
											echo'
											<option value="'.$t['id_kota'].'">'.$t['nama_kota'].'</option>';
										}
									}*/

									$sql_kota = mysqli_query($con,"SELECT * FROM regencies where province_id = ".$r['id_provinsi_asal']." ORDER BY name ASC");

											while($rs_kota = mysqli_fetch_assoc($sql_kota)){ 
										
											echo'
											<option value="'.$rs_kota['id'].'" '.($r['id_kota_asal']==$rs_kota['id'] ? 'selected' : '' ).'>'.$rs_kota['name'].'</option>';
										
									}


									echo'
								</select>
								
								<input type="hidden" name="kotaasal_text" id="kotaasal_text" value="'.$r['kota_asal'].'"   class="form-control" >
								<select name="kecamatanasal" id="kecamatanasal" data-placeholder="Choose a Kecamatan..." class="form-control select2" required>
									<option value="">Select...</option>';
									
									$sql_kec = mysqli_query($con,"SELECT * FROM districts where regency_id = ".$r['id_kota_asal']." ORDER BY name ASC");

											while($rs_kec = mysqli_fetch_assoc($sql_kec)){ 
										
											echo'
											<option value="'.$rs_kec['id'].'" '.($r['id_kecamatan_asal']==$rs_kec['id'] ? 'selected' : '' ).'>'.$rs_kec['name'].'</option>';
										
									}
									

									echo'
								</select>

								<input type="hidden" name="kecamatanasal_text" id="kecamatanasal_text" value="'.$r['kecamatan_asal'].'"  class="form-control" >
								<select name="kelurahanasal" id="kelurahanasal" data-placeholder="Choose a Desa/Kelurahan..." class="form-control select2">
									<option value="">Select...</option>';
									/*$sql_kota = mysqli_query($con,"SELECT * FROM regencies where province_id = ".$r['id_provinsi_asal']." ORDER BY name ASC");

											while($rs_kota = mysqli_fetch_assoc($sql_kota)){ 
										
											echo'
											<option value="'.$rs_kota['id'].'" '.($r['id_kota_asal']==$rs_kota['id'] ? 'selected' : '' ).'>'.$rs_kota['name'].'</option>';
										
									}*/

									$sql_kel = mysqli_query($con,"SELECT * FROM villages where district_id = ".$r['id_kecamatan_asal']." ORDER BY name ASC");

											while($rs_kel = mysqli_fetch_assoc($sql_kel)){ 
										
											echo'
											<option value="'.$rs_kel['name'].'" '.($r['kelurahan_asal']==$rs_kel['name'] ? 'selected' : '' ).'>'.$rs_kel['name'].'</option>';
										
									}

									
									echo'
								</select>
								
							</div>

						</div>
						<hr>
						<div class="form-group">
							<label class="col-md-3 control-label">Penerima <span class="required">* </span></label>
							<div class="col-md-6">
								<input type="text" name="nama_penerima" value="'.$r['nama_penerima'].'" id="nama_penerima" maxlength="40" placeholder="Nama penerima..." class="form-control" required>

								<input type="text"  name="hp_penerima" id="hp_penerima" placeholder="No. Handphone..." maxlength="13" class="form-control" value="'.$r['hp_penerima'].'" required>
								<input type="text" name="perusahaan_penerima"  id="perusahaan_penerima" value="'.$r['perusahaan_penerima'].'" maxlength="60" placeholder="Perusahaan..." class="form-control" required>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label">Alamat Penerima <span class="required">* </span></label>
							<div class="col-md-6">
								<input type="text" name="alamat_penerima1" id="alamat_penerima1" maxlength="52" value="'.$r['alamat_penerima'].'"  class="form-control" placeholder="Detail Alamat Penerima" required>
								<input type="text" name="alamat_penerima2" id="alamat_penerima2"  maxlength="52" value="'.$r['alamat_penerima2'].'" class="form-control" placeholder="Detail Alamat Penerima Lanjutan">
							</div>
						</div>
						<hr>
						';
						if($r['service'] != 6){

						echo '
						<div class="form-group" id="kotatujuan">
							<label class="col-md-3 control-label">Kota Tujuan  <span class="required">* </span></label>
							<div class="col-md-6">
								<select name="provinsi" id="provinsi" data-placeholder="Choose a provinces..." class="form-control select2">
									<option value="">Select...</option>';
									/*$kotatujuan = mysqli_query($con, "select a.id_kota, case when b.nama_kota is null then a.nama_kota when x_namakota(b.induk) is null then concat(a.nama_kota,', ',b.nama_kota) else concat(a.nama_kota,', ',b.nama_kota,', ',x_namakota(b.induk)) end as nama_kota from kota a left join kota b on b.id_kota=a.induk where a.kota_tujuan='Y'");
									while($t=mysqli_fetch_assoc($kotatujuan)){
										if(!empty($t['kode_kota'])){
											echo'
											<option value="'.$t['id_kota'].'">'.$t['nama_kota'].' ('.$t['kode_kota'].')</option>';
										}else{
											echo'
											<option value="'.$t['id_kota'].'">'.$t['nama_kota'].'</option>';
										}
									}*/

									$sql_provinsi = mysqli_query($con,"SELECT * FROM provinces ORDER BY name ASC");

									while($rs_provinsi = mysqli_fetch_assoc($sql_provinsi)){ 
										
											echo'
											<option value="'.$rs_provinsi['id'].'" '.($r['id_provinsi_tujuan']==$rs_provinsi['id'] ? 'selected' : '' ).'>'.$rs_provinsi['name'].'</option>';
										
									}


									echo'
								</select>
								<input type="hidden" name="provinsitujuan_text" id="provinsitujuan_text" value="'.$r['provinsi_tujuan'].'"  class="form-control" >
								
								

								<select name="kota" id="kota" data-placeholder="Choose a City..." class="form-control select2">
									<option value="">Select...</option>';
									/*$kotatujuan = mysqli_query($con, "select a.id_kota, case when b.nama_kota is null then a.nama_kota when x_namakota(b.induk) is null then concat(a.nama_kota,', ',b.nama_kota) else concat(a.nama_kota,', ',b.nama_kota,', ',x_namakota(b.induk)) end as nama_kota from kota a left join kota b on b.id_kota=a.induk where a.kota_tujuan='Y'");
									while($t=mysqli_fetch_assoc($kotatujuan)){
										if(!empty($t['kode_kota'])){
											echo'
											<option value="'.$t['id_kota'].'">'.$t['nama_kota'].' ('.$t['kode_kota'].')</option>';
										}else{
											echo'
											<option value="'.$t['id_kota'].'">'.$t['nama_kota'].'</option>';
										}
									}*/

									$sql_kota = mysqli_query($con,"SELECT * FROM regencies where province_id = ".$r['id_provinsi_tujuan']." ORDER BY name ASC");

											while($rs_kota = mysqli_fetch_assoc($sql_kota)){ 
										
											echo'
											<option value="'.$rs_kota['id'].'" '.($r['id_kota_tujuan']==$rs_kota['id'] ? 'selected' : '' ).'>'.$rs_kota['name'].'</option>';
										
									}


									echo'
								</select>
								
								<input type="hidden" name="kota_text" id="kota_text" value="'.$r['kota_tujuan'].'"   class="form-control" >
								<select name="kecamatan" id="kecamatan" data-placeholder="Choose a Kecamatan..." class="form-control select2" required>
									<option value="">Select...</option>';
									
									$sql_kec = mysqli_query($con,"SELECT * FROM districts where regency_id = ".$r['id_kota_tujuan']." ORDER BY name ASC");

											while($rs_kec = mysqli_fetch_assoc($sql_kec)){ 
										
											echo'
											<option value="'.$rs_kec['id'].'" '.($r['id_kecamatan_tujuan']==$rs_kec['id'] ? 'selected' : '' ).'>'.$rs_kec['name'].'</option>';
										
									}
									

									echo'
								</select>

								<input type="hidden" name="kecamatan_text" id="kecamatan_text" value="'.$r['kecamatan_tujuan'].'"  class="form-control" >
								<select name="kelurahan" id="kelurahan" data-placeholder="Choose a Desa/Kelurahan..." class="form-control select2">
									<option value="">Select...</option>';
									/*$sql_kota = mysqli_query($con,"SELECT * FROM regencies where province_id = ".$r['id_provinsi_asal']." ORDER BY name ASC");

											while($rs_kota = mysqli_fetch_assoc($sql_kota)){ 
										
											echo'
											<option value="'.$rs_kota['id'].'" '.($r['id_kota_asal']==$rs_kota['id'] ? 'selected' : '' ).'>'.$rs_kota['name'].'</option>';
										
									}*/

									$sql_kel = mysqli_query($con,"SELECT * FROM villages where district_id = ".$r['id_kecamatan_tujuan']." ORDER BY name ASC");

											while($rs_kel = mysqli_fetch_assoc($sql_kel)){ 
										
											echo'
											<option value="'.$rs_kel['name'].'" '.($r['kelurahan_tujuan']==$rs_kel['name'] ? 'selected' : '' ).'>'.$rs_kel['name'].'</option>';
										
									}

									
									echo'
								</select>
								
							</div>

						</div>
						';
						}else{

							echo '
							<div class="form-group" id="negara">
							<label class="col-md-3 control-label">Negara Tujuan  <span class="required">* </span></label>
							<div class="col-md-6">

								<select name="negara" id="negara" data-placeholder="Choose a negara..." class="form-control select2">
									<option value="">Select...</option>';
									/*$kotatujuan = mysqli_query($con, "select a.id_kota, case when b.nama_kota is null then a.nama_kota when x_namakota(b.induk) is null then concat(a.nama_kota,', ',b.nama_kota) else concat(a.nama_kota,', ',b.nama_kota,', ',x_namakota(b.induk)) end as nama_kota from kota a left join kota b on b.id_kota=a.induk where a.kota_tujuan='Y'");
									while($t=mysqli_fetch_assoc($kotatujuan)){
										if(!empty($t['kode_kota'])){
											echo'
											<option value="'.$t['id_kota'].'">'.$t['nama_kota'].' ('.$t['kode_kota'].')</option>';
										}else{
											echo'
											<option value="'.$t['id_kota'].'">'.$t['nama_kota'].'</option>';
										}
									}*/

									$sql_negara = mysqli_query($con,"SELECT * FROM negara  ORDER BY nama_negara ASC");

											while($n = mysqli_fetch_assoc($sql_negara)){ 
										
											echo'
											<option value="'.$n['id_negara'].'" '.($r['lokasi_tujuan']==$n['id_negara'] ? 'selected' : '' ).'>'.$n['nama_negara'].'</option>';
										
									}


									echo'
								</select>
							</div>
							</div>

								';
						}
						echo 

						'
						<input type="hidden" name="serviceshow" id="serviceshow" value="'.$r['service'].'">
						<input type="hidden" name="intercityasal" id="intercityasal" value="'.$r['intercityasal'].'">
									
						<input type="hidden" name="intercitytujuan" id="intercitytujuan" value="'.$r['intercitytujuan'].'">
						<hr>
						<div class="form-group">
							<label class="control-label col-md-3">Tipe Service <span class="required">* </span></label>
							<div class="col-md-6">
								<div class="radio radio-info">';
								$service = mysqli_query($con, "select id_pelayanan, nama_pelayanan from pelayanan where active='Y'");
								while($s=mysqli_fetch_assoc($service)){
									if($r['service']==$s['id_pelayanan']){
										echo'
										<label><input type="radio" class="seafreight" name="service" value="'.$s['id_pelayanan'].'" checked/> '.$s['nama_pelayanan'].' </label><br>';
									}else{
										echo'
										<label><input type="radio" class="seafreight" name="service" value="'.$s['id_pelayanan'].'"/> '.$s['nama_pelayanan'].' </label><br>';
									}
								}
								echo'
								</div>
							</div>
						</div>';
						if($r['service']=='10' || $r['service']=='11'){
							echo'
							<div id="ifseafreight">
								<div class="form-group">
									<label class="col-md-3 control-label">20ft x</label>
									<div class="col-md-6"><input type="text" name="20ft" class="form-control" value="'.$r['20ft'].'"></div>
								</div>
								<div class="form-group">
									<label class="col-md-3 control-label">40ft x</label>
									<div class="col-md-6"><input type="text" name="40ft" class="form-control" value="'.$r['40ft'].'"></div>
								</div>
								<div class="form-group">
									<label class="col-md-3 control-label">40ft HC x</label>
									<div class="col-md-6"><input type="text" name="40ft_hc" class="form-control" value="'.$r['40ft_hc'].'"></div>
								</div>
								<div class="form-group">
									<label class="col-md-3 control-label">LCL</label>
									<div class="col-md-6">
										<div class="input-group">
											<input type="text" name="lcl" class="form-control" value="'.$r['lcl'].'"> <span class="input-group-addon">m<sup>3</sup></span>
										</div>
									</div>
								</div>
							</div>';
						} else {
							echo'
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
							</div>';
						}
						echo'
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
								<label class="col-md-3 control-label">Jumlah Collie/Pcs</label>
								<div class="col-md-6"><input type="number" id="collie" name="jumlah_collie" onchange="hitung1();" class="form-control " value="'.$r['collie'].'" min="1" max="40"></div>
							</div>

							';
							$q = mysqli_query($con, "select * from detail_collie where id_pengiriman='".$id."' order by koli_ke asc ");
							$ketemu = mysqli_num_rows($q);
							if ($ketemu < 1){
								echo '
								
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
								</div>';
							}else{
								$i=1;
								while($row = mysqli_fetch_assoc($q)){
									echo '						
									<div class="form-group"  id="detail_collie'.$i.'">
										<label class="col-md-2 control-label">Detail Collie '.$i.'</label>
										<div class="col-md-10">
											<div class="row">
													<div class="col-md-2">
														<div class="input-group">
															<input type="number" onchange="hitung'.$i.'();" id="weight'.$i.'" min="1" value="'.$row['berat'].'" name="cweight['.$i.']" placeholder="berat..." class="form-control" max="350"> <span class="input-group-addon">kg</span>
														</div>
													</div>
													<div class="col-md-2">
														<div class="input-group">
															<input type="number" onchange="hitung'.$i.'();" id="width'.$i.'" min="1" name="cwidth['.$i.']" placeholder="Width..." class="form-control" max="350" value="'.$row['p'].'"> <span class="input-group-addon">cm</span>
														</div>
													</div>
													<div class="col-md-2">
														<div class="input-group">
															<input type="number" onchange="hitung'.$i.'();" id="length'.$i.'" min="1" value="'.$row['l'].'" name="clength['.$i.']" placeholder="Length..." class="form-control" max="350"> <span class="input-group-addon">cm</span>
														</div>
													</div>
													
													<div class="col-md-2">
														<div class="input-group">
															<input type="number" onchange="hitung'.$i.'();" id="height'.$i.'" min="1" name="cheight['.$i.']" placeholder="Height..." class="form-control" max="350" value="'.$row['t'].'"> <span class="input-group-addon">cm</span>
														</div>
													</div>
													<div class="col-md-4">
														<div class="input-group">
															<input type="number" readonly id="total'.$i.'" name="ctotal['.$i.']" min="1" placeholder="total..." class="form-control" max="350" value="'.$row['berat_real'].'" onchange="total'.$i.'();"> <span class="input-group-addon">kg</span>
														</div>
													</div>
												
											</div>	

										</div>
									</div>';
									$i=$i+1;
								}
								$mulai=$ketemu+1;
								for ($i = $mulai; $i < 41; $i++) {
									echo '						
									<div class="form-group" style="display:none;" id="detail_collie'.$i.'">
										<label class="col-md-2 control-label">Detail Collie '.$i.'</label>
										<div class="col-md-10">
											<div class="row">
													<div class="col-md-2">
														<div class="input-group">
															<input type="number" onchange="hitung'.$i.'();" id="weight'.$i.'" min="1" value="1" name="cweight['.$i.']" placeholder="berat..." class="form-control" max="350"> <span class="input-group-addon">kg</span>
														</div>
													</div>
													<div class="col-md-2">
														<div class="input-group">
															<input type="number" onchange="hitung'.$i.'();" id="width'.$i.'" min="1" name="cwidth['.$i.']" placeholder="Width..." class="form-control" max="350" value="1"> <span class="input-group-addon">cm</span>
														</div>
													</div>
													<div class="col-md-2">
														<div class="input-group">
															<input type="number" onchange="hitung'.$i.'();" id="length'.$i.'" min="1" value="1" name="clength['.$i.']" placeholder="Length..." class="form-control" max="350"> <span class="input-group-addon">cm</span>
														</div>
													</div>
													
													<div class="col-md-2">
														<div class="input-group">
															<input type="number" onchange="hitung'.$i.'();" id="height'.$i.'" min="1" name="cheight['.$i.']" placeholder="Height..." class="form-control" max="350" value="1"> <span class="input-group-addon">cm</span>
														</div>
													</div>
													<div class="col-md-4">
														<div class="input-group">
															<input type="number" readonly id="total'.$i.'" name="ctotal['.$i.']" min="1" placeholder="total..." class="form-control" max="350" value="1" onchange="total'.$i.'();"> <span class="input-group-addon">kg</span>
														</div>
													</div>
												
											</div>	

										</div>
									</div>';
									
								}
							}
							echo '

							<div class="form-group">
								<label class="col-md-3 control-label">Estimasi Berat Barang</label>
								<div class="col-md-6">
									<div class="input-group">
										<input type="text" name="berat_barang" readonly id="berat_barang" class="form-control" value="'.($r['berat_aktual']>0 ? $r['berat_aktual'] : $r['berat_barang']).'"> <span class="input-group-addon">Kg</span>
									</div>
								</div>
							</div>';

							/*
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
							</div>*/
							echo '
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
								<label class="col-md-3 control-label">Jumlah Collie/Pcs</label>
								<div class="col-md-6"><input type="number" id="collie" name="jumlah_collie" class="form-control " value="'.$r['collie'].'" min="1" max="20"></div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label">Estimasi Berat Barang</label>
								<div class="col-md-6">
									<div class="input-group">
										<input type="text" name="berat_barang" class="form-control" value="'.($r['berat_aktual']>0 ? $r['berat_aktual'] : $r['berat_barang']).'"> <span class="input-group-addon">Kg</span>
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
										<span class="input-group-addon">Rp</span> <input type="number" value="'.$r['nilai_barang'].'" name="nilai_barang" id="nilai_barang" class="form-control">
									</div>
									<div  id="val-asu" ></div>	
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
										<span class="input-group-addon">Rp</span> <input type="number" value="0" min="0" name="nilai_barang" id="nilai_barang" class="form-control">
									</div>
									<div  id="val-asu" style="display:none;"></div>	
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
						<hr>
						<div class="form-group">
							<label class="col-md-3 control-label">Pembayaran Khusus</label>
							<div class="col-md-6">
								<div class="radio radio-info">
									';
										if($r['pembayaran_khusus']=='1'){
											echo'
											<label><input type="radio" value="1" name="pembayaran_khusus" class="pembayaran_khusus" checked>Ya </label><br>
											<label><input type="radio" value="0" name="pembayaran_khusus" class="pembayaran_khusus" >Tidak </label>';
										}else{
											echo'
											<label><input type="radio" value="1" name="pembayaran_khusus" class="pembayaran_khusus" >Ya </label><br>
									<label><input type="radio" value="0" name="pembayaran_khusus" class="pembayaran_khusus" checked>Tidak </label>';
										}
										echo'								
								</div>
							</div>
						</div>
						<hr>
						';
						$q = mysqli_query($con, "select * from detail_collie where id_pengiriman='".$id."' order by koli_ke asc ");
							$ketemu = mysqli_num_rows($q);
						if($ketemu > 0 ){
							echo '
								<div class="form-group">
									<label class="col-md-3 control-label">Catatan Khusus Kiriman</label>
									<div class="col-md-6">
										
										
										';
										$i=1;
										while($row = mysqli_fetch_assoc($q)){
											echo '
											<input type="text" required name="catatanbaru['.$i.']" id="catatanbaru'.$i.'" value="'.$row['catatan'].'" placeholder="Catatan kiriman '.$i.'" class="form-control" maxlength="80">';
											$i=$i+1;
										}
										$m= $ketemu+1;

										for($i=$m;$i<41;$i++){
											echo '
											<input type="text" style="display:none;" disabled name="catatanbaru['.$i.']" id="catatanbaru'.$i.'" placeholder="Catatan kiriman '.$i.'" class="form-control" maxlength="80">';
										}
										echo '

									</div>
								</div>
							';	
						}
						else{
							echo '
						<div class="form-group">
							<label class="col-md-3 control-label">Catatan Khusus Kiriman</label>
							<div class="col-md-6">
								<input type="text" name="catatan1" id="catatan1" value="'.$r['catatan1'].'" placeholder="Catatan khusus kiriman 1" class="form-control" maxlength="80" required>
								
								<input type="text" style="display:'.($r['collie']>1 ? '' : 'none' ).';" name="catatan2" value="'.$r['catatan2'].'" id="catatan2" placeholder="Catatan khusus kiriman 2" class="form-control" maxlength="80">
								
								<input type="text" style="display:'.($r['collie']>2 ? '' : 'none' ).';" name="catatan3" value="'.$r['catatan3'].'" id="catatan3" placeholder="Catatan khusus kiriman 3" class="form-control" maxlength="80">
								
								<input type="text" style="display:'.($r['collie']>3 ? '' : 'none' ).';" name="catatan4" value="'.$r['catatan4'].'" id="catatan4" placeholder="Catatan khusus kiriman 4" class="form-control" maxlength="80">
								<input type="text" style="display:'.($r['collie']>4 ? '' : 'none' ).';" name="catatan5" value="'.$r['catatan5'].'" id="catatan5" placeholder="Catatan khusus kiriman 5" class="form-control" maxlength="80">
								<input type="text" style="display:'.($r['collie']>5 ? '' : 'none' ).';" name="catatan6" value="'.$r['catatan6'].'" id="catatan6" placeholder="Catatan khusus kiriman 6" class="form-control" maxlength="80">
								<input type="text" style="display:'.($r['collie']>6 ? '' : 'none' ).';" name="catatan7" value="'.$r['catatan7'].'" id="catatan7" placeholder="Catatan khusus kiriman 7" class="form-control" maxlength="80">
								<input type="text" style="display:'.($r['collie']>7 ? '' : 'none' ).';" name="catatan8" value="'.$r['catatan8'].'" id="catatan8" placeholder="Catatan khusus kiriman 8" class="form-control" maxlength="80">
								<input type="text" style="display:'.($r['collie']>8 ? '' : 'none' ).';" name="catatan9" value="'.$r['catatan9'].'" id="catatan9" placeholder="Catatan khusus kiriman 9" class="form-control" maxlength="80">
								<input type="text" style="display:'.($r['collie']>9 ? '' : 'none' ).';" name="catatan10" value="'.$r['catatan10'].'" id="catatan10" placeholder="Catatan khusus kiriman 10" class="form-control" maxlength="80">

							</div>
						</div>
						';
						}
						if ($r['id_kustomer'] == 106){
						echo '
						<div class="form-group">
							<label class="col-md-3 control-label">Upload Data Isi Catatan </label>
							<div class="col-md-6">
								<input type="file" name="uploadcatatan">
								<p class="help-block">Hanya bisa file ber-type .xls</p>
							</div>
						</div>

						';
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
						}
						$sql = mysqli_query($con, "select id_pengiriman,product,aprc,part_n,part_n_desc,rma_no,qty,remark from asus_detail_catatan  where id_pengiriman='".$id."'");

						$cek = mysqli_num_rows($sql);
						if ($cek > 0){
							echo '

							<div class="table-responsive">
							<table class="table" style="">
								<tr>
								<td><a href="'.$aksi.'?module=bookinginv2&act=delete_catatan&id_catatan='.$id.' ">delete all</a></td>
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
									<label><input type="radio" value="2" name="biaya_beban" id="penerima"> Penerima</label><br>';
									//<label><input type="radio" value="3" name="biaya_beban" id="pihak3"> Pihak Ketiga </label><br> 
									echo '
									<label><input type="radio" value="4" name="biaya_beban" id="tunai"> Tunai</label>';
								}elseif($r['biaya_beban']=='2'){
									echo'
									<label><input type="radio" value="1" name="biaya_beban" id="pengirim">Pengirim </label><br>
									<label><input type="radio" value="2" name="biaya_beban" id="penerima" checked> Penerima</label><br>
									';
									//<label><input type="radio" value="3" name="biaya_beban" id="pihak3"> Pihak Ketiga </label><br>
									echo '<label><input type="radio" value="4" name="biaya_beban" id="tunai"> Tunai</label>';
								}elseif($r['biaya_beban']=='3'){
									echo'
									<label><input type="radio" value="1" name="biaya_beban" id="pengirim">Pengirim </label><br>
									<label><input type="radio" value="2" name="biaya_beban" id="penerima"> Penerima</label><br>';
									//<label><input type="radio" value="3" name="biaya_beban" id="pihak3" checked> Pihak Ketiga </label><br>
									echo '<label><input type="radio" value="4" name="biaya_beban" id="tunai"> Tunai</label>';
								}else{
									echo'
									<label><input type="radio" value="1" name="biaya_beban" id="pengirim">Pengirim </label><br>
									<label><input type="radio" value="2" name="biaya_beban" id="penerima"> Penerima</label><br>';
									//<label><input type="radio" value="3" name="biaya_beban" id="pihak3"> Pihak Ketiga </label><br>
									echo '<label><input type="radio" value="4" name="biaya_beban" id="tunai" checked> Tunai</label>';
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
					<div > 
						<input type="hidden" value="'.($r["updated_by_operational"] == NULL ? 0 : $r["updated_by_operational"]).'" name="updated_by_operational"> </div>
						<div > <input type="hidden" value="'.$u["level"].'" name="level"> </div>
						<div > <input type="hidden" value="'.($r["updated_by_finance"] == NULL ? 0 : $r["updated_by_finance"]).'" name="updated_by_finance"> 
					</div>
					<div class="box-footer">
						<button type="button" class="btn btn-default" onclick="window.location.href = \'?module=bookinginv2\';">Cancel</button>
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
					<form method="post" action="'.$aksi.'?module=bookinginv2&act=upload" class="form-horizontal form-bordered" id="form-bookinginv2" autocomplete="off" enctype="multipart/form-data">
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
						<div class="form-group">
							<label class="col-md-3 control-label">Customer <span class="required">* </span></label>
							<div class="col-md-6">
								<select name="id_kustomer" id="id_kustomerin" data-placeholder="Choose a Customer..." class="form-control select2">';
								$result = mysqli_query($con,"select id_kustomer, nama_kustomer from kustomer where status != 'N' order by nama_kustomer");
								echo '<option value="">Select...</option>';
								while ($row = mysqli_fetch_assoc($result)) {
									echo '<option value="' . $row['id_kustomer'] . '">' . $row['nama_kustomer'] . '</option>';
								}
								echo'
								</select>
								<div class="val-message"></div>
								<input type="checkbox" id="outbound" name="outbound" value="N"> Ceklist jika pengirim adalah customer
								<input type="hidden" name="id_cabang" id="id_cabang">
							</div>
						</div>
						<div id="loc_code" style="display:none;">
							<div class="form-group">
								<label class="col-md-3 control-label">Loc Code <span class="required">* </span></label>
								<div class="col-md-6">
									<input type="text" name="loc_code" id="input_loccode" disabled placeholder="Loc Code..." class="form-control">
								</div>
							</div>
						</div>
						';
							if($u['id_kustomer'] == 13 || $u['id_kustomer'] == 29 || $u['id_kustomer'] == 31){
								echo '
								<div class="form-group pid">
									<label class="col-md-3 control-label">NO PID  <span class="required">* </span></label>
									<div class="col-md-6">

										<input type="text" name="pid" id="pid" maxlength="20" placeholder="NO PID" class="form-control"  maxlength="20" required>

									</div>
								</div>
								';
							}
					

							echo '
						<hr>
						<div class="form-group">
							<label class="col-md-3 control-label">Pengirim <span class="required">* </span></label>
							<div class="col-md-6">
								<input type="text" name="nama_pengirim" id="nama_pengirim" placeholder="Nama pengirim..." class="form-control">
								
								<input type="text" name="hp_pengirim" id="hp_pengirim" placeholder="No. Handphone..." class="form-control">
								<input type="text" name="perusahaan_pengirim" id="perusahaan_pengirim" placeholder="Perusahaan..." class="form-control">
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label">Alamat Pengirim <span class="required">* </span></label>
							<div class="col-md-6">
								<input type="text" name="alamat_pengirim1" id="alamat_pengirim1" maxlength="52"  class="form-control" placeholder="Detail Alamat Pengirim" required>
								<input type="text" name="alamat_pengirim2" id="alamat_pengirim2"  maxlength="52"  class="form-control" placeholder="Detail Alamat Pengirim Lanjutan">
							</div>
						</div>
						<hr>
						<div class="form-group" id="kotaasall">
							<label class="col-md-3 control-label">Kota Asal  <span class="required">* </span></label>
							<div class="col-md-6">
								<select name="provinsiasal" id="provinsiasal" data-placeholder="Choose a provinces..." class="form-control select2">
									<option value="">Select...</option>';
									/*$kotatujuan = mysqli_query($con, "select a.id_kota, case when b.nama_kota is null then a.nama_kota when x_namakota(b.induk) is null then concat(a.nama_kota,', ',b.nama_kota) else concat(a.nama_kota,', ',b.nama_kota,', ',x_namakota(b.induk)) end as nama_kota from kota a left join kota b on b.id_kota=a.induk where a.kota_tujuan='Y'");
									while($t=mysqli_fetch_assoc($kotatujuan)){
										if(!empty($t['kode_kota'])){
											echo'
											<option value="'.$t['id_kota'].'">'.$t['nama_kota'].' ('.$t['kode_kota'].')</option>';
										}else{
											echo'
											<option value="'.$t['id_kota'].'">'.$t['nama_kota'].'</option>';
										}
									}*/

									$sql_provinsi = mysqli_query($con,"SELECT * FROM provinces ORDER BY name ASC");

									while($rs_provinsi = mysqli_fetch_assoc($sql_provinsi)){ 
										
											echo'
											<option value="'.$rs_provinsi['id'].'">'.$rs_provinsi['name'].'</option>';
										
									}


									echo'
								</select>
								<input type="hidden" name="provinsiasal_text" id="provinsiasal_text"   class="form-control" >
								
								

								<select name="kotaasal" id="kotaasal" data-placeholder="Choose a City..." class="form-control select2">
									<option value="">Select...</option>';
									/*$kotatujuan = mysqli_query($con, "select a.id_kota, case when b.nama_kota is null then a.nama_kota when x_namakota(b.induk) is null then concat(a.nama_kota,', ',b.nama_kota) else concat(a.nama_kota,', ',b.nama_kota,', ',x_namakota(b.induk)) end as nama_kota from kota a left join kota b on b.id_kota=a.induk where a.kota_tujuan='Y'");
									while($t=mysqli_fetch_assoc($kotatujuan)){
										if(!empty($t['kode_kota'])){
											echo'
											<option value="'.$t['id_kota'].'">'.$t['nama_kota'].' ('.$t['kode_kota'].')</option>';
										}else{
											echo'
											<option value="'.$t['id_kota'].'">'.$t['nama_kota'].'</option>';
										}
									}*/

									


									echo'
								</select>
								<input type="hidden" name="kotaasal_text" id="kotaasal_text"   class="form-control" >
								<select name="kecamatanasal" id="kecamatanasal" data-placeholder="Choose a Kecamatan..." class="form-control select2" required>
									<option value="">Select...</option>';
									/*$kotatujuan = mysqli_query($con, "select a.id_kota, case when b.nama_kota is null then a.nama_kota when x_namakota(b.induk) is null then concat(a.nama_kota,', ',b.nama_kota) else concat(a.nama_kota,', ',b.nama_kota,', ',x_namakota(b.induk)) end as nama_kota from kota a left join kota b on b.id_kota=a.induk where a.kota_tujuan='Y'");
									while($t=mysqli_fetch_assoc($kotatujuan)){
										if(!empty($t['kode_kota'])){
											echo'
											<option value="'.$t['id_kota'].'">'.$t['nama_kota'].' ('.$t['kode_kota'].')</option>';
										}else{
											echo'
											<option value="'.$t['id_kota'].'">'.$t['nama_kota'].'</option>';
										}
									}*/

									

									echo'
								</select>
								<input type="hidden" name="kecamatanasal_text" id="kecamatanasal_text"   class="form-control" >
								<select name="kelurahanasal" id="kelurahanasal" data-placeholder="Choose a Desa/Kelurahan..." class="form-control select2">
									<option value="">Select...</option>';
									/*$kotatujuan = mysqli_query($con, "select a.id_kota, case when b.nama_kota is null then a.nama_kota when x_namakota(b.induk) is null then concat(a.nama_kota,', ',b.nama_kota) else concat(a.nama_kota,', ',b.nama_kota,', ',x_namakota(b.induk)) end as nama_kota from kota a left join kota b on b.id_kota=a.induk where a.kota_tujuan='Y'");
									while($t=mysqli_fetch_assoc($kotatujuan)){
										if(!empty($t['kode_kota'])){
											echo'
											<option value="'.$t['id_kota'].'">'.$t['nama_kota'].' ('.$t['kode_kota'].')</option>';
										}else{
											echo'
											<option value="'.$t['id_kota'].'">'.$t['nama_kota'].'</option>';
										}
									}*/

									


									echo'
								</select>
								
							</div>

						</div>
						<hr>
						<div class="form-group">
							<label class="col-md-3 control-label">Upload Data Penerima <span class="required">* </span></label>
							<div class="col-md-6">
								<input type="file" name="bookingfile" required>
								<p class="help-block">Hanya bisa file ber-type .xls</p>
							</div>
						</div>
						<hr>
						
						<hr>
						
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
										<span class="input-group-addon">Rp</span> <input type="number" val="100000" min="100000" name="nilai_barang" id="nilai_barang" class="form-control">
									</div>
									<div  id="val-asu" style="display:none;"></div>	
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
						<button type="button" class="btn btn-default" onclick="window.location.href = \'?module=bookinginv2\';">Cancel</button>
						<button type="submit" id="upload-save" class="btn btn-primary pull-right">Save</button>
					</div><!-- /.box-footer -->
					</form>
				</div><!-- /.box -->
			</section>';
			
			break;

			//mulau handover

			case "handover":
				?>

				<form class="form-horizontal" method="post" action="reportsnew/handover_list.php" autocomplete="off" target="blank">
					<div class="box box-primary">
						<div class="box-header with-border">
							<h3 class="box-title">Handover Courier</h3>
							<div class="box-tools pull-right">
								<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
								<button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
							</div>
						</div><!-- /.box-header -->
						<div class="box-body">
							<div class="row">
								<div class="col-md-5">
									<div class="form-group">
										<label class="col-sm-5 control-label full-right">Date Range</label>
										<div class="col-sm-7">
											<div class="input-group">
												<div class="input-group-addon">
													<i class="fa fa-calendar"></i>
												</div>
												<input type="text" class="form-control" id="daterange" name="tanggal" value="<?php echo''.$setdate.' - '.$setdate.'';?>" readonly>
											</div>
										</div>
									</div>
								</div>
								<div class="col-md-4">
											<div class="form-group">
												<label class="col-sm-4 control-label full-right">Kustomer</label>
												<div class="col-sm-8">
													<select name="kustomer" class="form-control select2">
												<?php 

													$result = mysqli_query($con,"select id_kustomer, nama_kustomer from kustomer order by nama_kustomer");
													while ($row = mysqli_fetch_assoc($result)) {
														if($u['id_kustomer']==$row['id_kustomer']){
															echo '<option value="'.$row['id_kustomer'].'" selected>'.$row['nama_kustomer'].'</option>';
														}else{
															echo '<option value="'.$row['id_kustomer'].'">'.$row['nama_kustomer'].'</option>';
														}
													}
												?>
												
													
													</select>
												</div>
											</div>
								</div>
								<!--<div class="col-md-3">
											<div class="form-group">
												<label class="col-sm-4 control-label full-right">Bookingan</label>
												<div class="col-sm-8">
													<select name="bookingan" class="form-control select2">
														<option value="0">Semua</option>
														<option value="1">Sendiri</option>
												
												
													
													</select>
												</div>
											</div>
								</div>-->
							</div>

							</div><!-- /.row -->
						</div><!-- /.box-body -->
				        <div class="box-footer">
							<button type="submit" class="btn btn-primary pull-right"> <i class="fa fa-print"></i> Print</button>
						</div>
						<script>
							$(document).ready(function() {
				    		$('#airwaybill-cust').DataTable( {
				       		 dom: 'Bfrtip',
				        		buttons: [
				            'copy', 'csv', 'excel', 'pdf', 'print'
				      		  ]
				   			 } );
							} );
						</script>
					</div><!-- /.box -->
					</form>

				<?php
			break;	

			//selesai handover
			
		}
	}else{
		echo akses_salah();
	}
}
?>

<script>

$(document).ready(function(){
	// asuransi

});


function hitung1() {

	var we = $("#weight1").val();
    var w = $("#width1").val();
    var l = $("#length1").val();
    var h = $("#height1").val();
    var intercityasal = $("#intercityasal").val();
    var intercitytujuan = $("#intercitytujuan").val();
    var service = $("#serviceshow").val();

   if(intercityasal== 'Y' && intercitytujuan == 'Y'){
    	bagiv = 4000;
    }else{
    	if(service== '5'){
    		bagiv = 4000;
    	}else{
    		bagiv = 6000;
    	}
    }
    //alert(bagiv);

    var collie = $("#collie").val();
     

    t = w * l * h / bagiv;
    if(we > t){
    	total = we;
    }else{
    	total = t;
    }
    //alert(total);
    
    $.post('module/mod_bookinginv2/bookinginv2_action.php?module=bookinginv2&act=pembulatan', {
				nomer: total
			},
			function (html) {
				$("#total1").val(html);
				var collie = $("#collie").val();
			    totalll = 0;
			    for (i = 1; i <= collie; i++) {
					  totalll = Number(totalll) + Number($('#total'+i).val());
					//  alert(i);

					}

				$("#berat_barang").val(totalll);

				totalll = 0;
				for (i = 1; i <= collie; i++) {
					  totalll = Number(totalll) + Number($('#total'+i).val());
					//  alert(i);

					}

				$("#berat_barang").val(totalll);
			}
		);

    //$("#total1").val(Math.round(total));
   // alert ('aktif1');

    var t1 = $("#total1").val();
    //
	var t2 = $("#total2").val();
	var t3 = $("#total3").val();

	var t4 = $("#total4").val();
	var t5 = $("#total5").val();
	var t6 = $("#total6").val();
	var t7 = $("#total7").val();
	var t8 = $("#total8").val();
	var t9 = $("#total9").val();
	var t10 = $("#total10").val();
	var t11 = $("#total11").val();
	var t12 = $("#total12").val();
	var t13 = $("#total13").val();
	var t14 = $("#total14").val();
	var t15 = $("#total15").val();
	var t16 = $("#total16").val();
	var t17 = $("#total17").val();
	var t18 = $("#total18").val();
	var t19 = $("#total19").val();
	var t20 = $("#total20").val();
	var t21 = $("#total21").val();
	var t22 = $("#total22").val();
	var t23 = $("#total23").val();
	var t24 = $("#total24").val();
	var t25 = $("#total25").val();
	var t20 = $("#total26").val();
	var t20 = $("#total27").val();
	var t20 = $("#total28").val();
	var t20 = $("#total29").val();
	var t20 = $("#total30").val();
	var t20 = $("#total31").val();
	var t20 = $("#total32").val();
	var t20 = $("#total33").val();
	var t20 = $("#total34").val();
	var t20 = $("#total35").val();
	var t20 = $("#total36").val();
	var t20 = $("#total37").val();
	var t20 = $("#total38").val();
	var t20 = $("#total39").val();
	var t20 = $("#total40").val();
	
	
   

	ttl = Number(t1)+Number(t2)+Number(t3)+Number(t4)+Number(t5)+Number(t6)+Number(t7)+Number(t8)+Number(t9)+Number(t10)+Number(t11)+Number(t12)+Number(t13)+Number(t14)+Number(t15)+Number(t16)+Number(t17)+Number(t18)+Number(t19)+Number(t20);
	//alert ('aktif2');



}
function hitung2() {

	var we = $("#weight2").val();
    var w = $("#width2").val();
    var l = $("#length2").val();
    var h = $("#height2").val();

        var intercityasal = $("#intercityasal").val();
    var intercitytujuan = $("#intercitytujuan").val();
    var service = $("#serviceshow").val();

   if(intercityasal== 'Y' && intercitytujuan == 'Y'){
        bagiv = 4000;
    }else{
        if(service== '5'){
            bagiv = 4000;
        }else{
            bagiv = 6000;
        }
    }
    //alert(bagiv);

    var collie = $("#collie").val();
     

    t = w * l * h / bagiv;
    if(we > t){
    	total = we;
    }else{
    	total = t;
    }
   $.post('module/mod_bookinginv2/bookinginv2_action.php?module=bookinginv2&act=pembulatan', {
				nomer: total
			},
			function (html) {
				$("#total2").val(html);
				var collie = $("#collie").val();
			    totalll = 0;
			    for (i = 1; i <= collie; i++) {
					  totalll = Number(totalll) + Number($('#total'+i).val());
					//  alert(i);

					}

				$("#berat_barang").val(totalll);
			}
		);

}
function hitung3() {

	var we = $("#weight3").val();
    var w = $("#width3").val();
    var l = $("#length3").val();
    var h = $("#height3").val();

        var intercityasal = $("#intercityasal").val();
    var intercitytujuan = $("#intercitytujuan").val();
    var service = $("#serviceshow").val();

   if(intercityasal== 'Y' && intercitytujuan == 'Y'){
        bagiv = 4000;
    }else{
        if(service== '5'){
            bagiv = 4000;
        }else{
            bagiv = 6000;
        }
    }
    //alert(bagiv);

    var collie = $("#collie").val();
     

    t = w * l * h / bagiv;
    if(we > t){
    	total = we;
    }else{
    	total = t;
    }
    $.post('module/mod_bookinginv2/bookinginv2_action.php?module=bookinginv2&act=pembulatan', {
				nomer: total
			},
			function (html) {
				$("#total3").val(html);
				var collie = $("#collie").val();
			    totalll = 0;
			    for (i = 1; i <= collie; i++) {
					  totalll = Number(totalll) + Number($('#total'+i).val());
					//  alert(i);

					}

				$("#berat_barang").val(totalll);
			}
		);

}
function hitung4() {

	var we = $("#weight4").val();
    var w = $("#width4").val();
    var l = $("#length4").val();
    var h = $("#height4").val();

        var intercityasal = $("#intercityasal").val();
    var intercitytujuan = $("#intercitytujuan").val();
    var service = $("#serviceshow").val();

   if(intercityasal== 'Y' && intercitytujuan == 'Y'){
        bagiv = 4000;
    }else{
        if(service== '5'){
            bagiv = 4000;
        }else{
            bagiv = 6000;
        }
    }
    //alert(bagiv);

    var collie = $("#collie").val();
     

    t = w * l * h / bagiv;
    if(we > t){
    	total = we;
    }else{
    	total = t;
    }
    $.post('module/mod_bookinginv2/bookinginv2_action.php?module=bookinginv2&act=pembulatan', {
				nomer: total
			},
			function (html) {
				$("#total4").val(html);
				var collie = $("#collie").val();
			    totalll = 0;
			    for (i = 1; i <= collie; i++) {
					  totalll = Number(totalll) + Number($('#total'+i).val());
					//  alert(i);

					}

				$("#berat_barang").val(totalll);
			}
		);

}
function hitung5() {

	var we = $("#weight5").val();
    var w = $("#width5").val();
    var l = $("#length5").val();
    var h = $("#height5").val();

        var intercityasal = $("#intercityasal").val();
    var intercitytujuan = $("#intercitytujuan").val();
    var service = $("#serviceshow").val();

   if(intercityasal== 'Y' && intercitytujuan == 'Y'){
        bagiv = 4000;
    }else{
        if(service== '5'){
            bagiv = 4000;
        }else{
            bagiv = 6000;
        }
    }
    //alert(bagiv);

    var collie = $("#collie").val();
     

    t = w * l * h / bagiv;
    if(we > t){
    	total = we;
    }else{
    	total = t;
    }
    $.post('module/mod_bookinginv2/bookinginv2_action.php?module=bookinginv2&act=pembulatan', {
				nomer: total
			},
			function (html) {
				$("#total5").val(html);
				var collie = $("#collie").val();
			    totalll = 0;
			    for (i = 1; i <= collie; i++) {
					  totalll = Number(totalll) + Number($('#total'+i).val());
					//  alert(i);

					}

				$("#berat_barang").val(totalll);
			}
		);


}
function hitung6() {

	var we = $("#weight6").val();
    var w = $("#width6").val();
    var l = $("#length6").val();
    var h = $("#height6").val();

        var intercityasal = $("#intercityasal").val();
    var intercitytujuan = $("#intercitytujuan").val();
    var service = $("#serviceshow").val();

   if(intercityasal== 'Y' && intercitytujuan == 'Y'){
        bagiv = 4000;
    }else{
        if(service== '5'){
            bagiv = 4000;
        }else{
            bagiv = 6000;
        }
    }
    //alert(bagiv);

    var collie = $("#collie").val();
     

    t = w * l * h / bagiv;
    if(we > t){
    	total = we;
    }else{
    	total = t;
    }
   $.post('module/mod_bookinginv2/bookinginv2_action.php?module=bookinginv2&act=pembulatan', {
				nomer: total
			},
			function (html) {
				$("#total6").val(html);
				var collie = $("#collie").val();
			    totalll = 0;
			    for (i = 1; i <= collie; i++) {
					  totalll = Number(totalll) + Number($('#total'+i).val());
					//  alert(i);

					}

				$("#berat_barang").val(totalll);
			}
		);

 
}
function hitung7() {

	var we = $("#weight7").val();
    var w = $("#width7").val();
    var l = $("#length7").val();
    var h = $("#height7").val();

        var intercityasal = $("#intercityasal").val();
    var intercitytujuan = $("#intercitytujuan").val();
    var service = $("#serviceshow").val();

   if(intercityasal== 'Y' && intercitytujuan == 'Y'){
        bagiv = 4000;
    }else{
        if(service== '5'){
            bagiv = 4000;
        }else{
            bagiv = 6000;
        }
    }
    //alert(bagiv);

    var collie = $("#collie").val();
     

    t = w * l * h / bagiv;
    if(we > t){
    	total = we;
    }else{
    	total = t;
    }
    $.post('module/mod_bookinginv2/bookinginv2_action.php?module=bookinginv2&act=pembulatan', {
				nomer: total
			},
			function (html) {
				$("#total7").val(html);
				var collie = $("#collie").val();
			    totalll = 0;
			    for (i = 1; i <= collie; i++) {
					  totalll = Number(totalll) + Number($('#total'+i).val());
					//  alert(i);

					}

				$("#berat_barang").val(totalll);
			}
		);

    
 
}
function hitung8() {

	var we = $("#weight8").val();
    var w = $("#width8").val();
    var l = $("#length8").val();
    var h = $("#height8").val();

        var intercityasal = $("#intercityasal").val();
    var intercitytujuan = $("#intercitytujuan").val();
    var service = $("#serviceshow").val();

   if(intercityasal== 'Y' && intercitytujuan == 'Y'){
        bagiv = 4000;
    }else{
        if(service== '5'){
            bagiv = 4000;
        }else{
            bagiv = 6000;
        }
    }
    //alert(bagiv);

    var collie = $("#collie").val();
     

    t = w * l * h / bagiv;
    if(we > t){
    	total = we;
    }else{
    	total = t;
    }
    $.post('module/mod_bookinginv2/bookinginv2_action.php?module=bookinginv2&act=pembulatan', {
				nomer: total
			},
			function (html) {
				$("#total8").val(html);
				var collie = $("#collie").val();
			    totalll = 0;
			    for (i = 1; i <= collie; i++) {
					  totalll = Number(totalll) + Number($('#total'+i).val());
					//  alert(i);

					}

				$("#berat_barang").val(totalll);
			}
		);

}
function hitung9() {

	var we = $("#weight9").val();
    var w = $("#width9").val();
    var l = $("#length9").val();
    var h = $("#height9").val();

        var intercityasal = $("#intercityasal").val();
    var intercitytujuan = $("#intercitytujuan").val();
    var service = $("#serviceshow").val();

   if(intercityasal== 'Y' && intercitytujuan == 'Y'){
        bagiv = 4000;
    }else{
        if(service== '5'){
            bagiv = 4000;
        }else{
            bagiv = 6000;
        }
    }
    //alert(bagiv);

    var collie = $("#collie").val();
     

    t = w * l * h / bagiv;
    if(we > t){
    	total = we;
    }else{
    	total = t;
    }
    $.post('module/mod_bookinginv2/bookinginv2_action.php?module=bookinginv2&act=pembulatan', {
				nomer: total
			},
			function (html) {
				$("#total9").val(html);
				var collie = $("#collie").val();
			    totalll = 0;
			    for (i = 1; i <= collie; i++) {
					  totalll = Number(totalll) + Number($('#total'+i).val());
					//  alert(i);

					}

				$("#berat_barang").val(totalll);
			}
		);
   
}
function hitung10() {

	var we = $("#weight10").val();
    var w = $("#width10").val();
    var l = $("#length10").val();
    var h = $("#height10").val();

        var intercityasal = $("#intercityasal").val();
    var intercitytujuan = $("#intercitytujuan").val();
    var service = $("#serviceshow").val();

   if(intercityasal== 'Y' && intercitytujuan == 'Y'){
        bagiv = 4000;
    }else{
        if(service== '5'){
            bagiv = 4000;
        }else{
            bagiv = 6000;
        }
    }
    //alert(bagiv);

    var collie = $("#collie").val();
     

    t = w * l * h / bagiv;
    if(we > t){
    	total = we;
    }else{
    	total = t;
    }
    $.post('module/mod_bookinginv2/bookinginv2_action.php?module=bookinginv2&act=pembulatan', {
				nomer: total
			},
			function (html) {
				$("#total10").val(html);
				var collie = $("#collie").val();
			    totalll = 0;
			    for (i = 1; i <= collie; i++) {
					  totalll = Number(totalll) + Number($('#total'+i).val());
					//  alert(i);

					}

				$("#berat_barang").val(totalll);
			}
		);
      
}
function hitung11() {

	var we = $("#weight11").val();
    var w = $("#width11").val();
    var l = $("#length11").val();
    var h = $("#height11").val();

        var intercityasal = $("#intercityasal").val();
    var intercitytujuan = $("#intercitytujuan").val();
    var service = $("#serviceshow").val();

   if(intercityasal== 'Y' && intercitytujuan == 'Y'){
        bagiv = 4000;
    }else{
        if(service== '5'){
            bagiv = 4000;
        }else{
            bagiv = 6000;
        }
    }
    //alert(bagiv);

    var collie = $("#collie").val();
     

    t = w * l * h / bagiv;
    if(we > t){
    	total = we;
    }else{
    	total = t;
    }
    $.post('module/mod_bookinginv2/bookinginv2_action.php?module=bookinginv2&act=pembulatan', {
				nomer: total
			},
			function (html) {
				$("#total11").val(html);
				var collie = $("#collie").val();
			    totalll = 0;
			    for (i = 1; i <= collie; i++) {
					  totalll = Number(totalll) + Number($('#total'+i).val());
					//  alert(i);

					}

				$("#berat_barang").val(totalll);
			}
		);

}
function hitung12() {

	var we = $("#weight12").val();
    var w = $("#width12").val();
    var l = $("#length12").val();
    var h = $("#height12").val();

        var intercityasal = $("#intercityasal").val();
    var intercitytujuan = $("#intercitytujuan").val();
    var service = $("#serviceshow").val();

   if(intercityasal== 'Y' && intercitytujuan == 'Y'){
        bagiv = 4000;
    }else{
        if(service== '5'){
            bagiv = 4000;
        }else{
            bagiv = 6000;
        }
    }
    //alert(bagiv);

    var collie = $("#collie").val();
     

    t = w * l * h / bagiv;
    if(we > t){
    	total = we;
    }else{
    	total = t;
    }
    $.post('module/mod_bookinginv2/bookinginv2_action.php?module=bookinginv2&act=pembulatan', {
				nomer: total
			},
			function (html) {
				$("#total12").val(html);
				var collie = $("#collie").val();
			    totalll = 0;
			    for (i = 1; i <= collie; i++) {
					  totalll = Number(totalll) + Number($('#total'+i).val());
					//  alert(i);

					}

				$("#berat_barang").val(totalll);
			}
		);

}
function hitung13() {

	var we = $("#weight13").val();
    var w = $("#width13").val();
    var l = $("#length13").val();
    var h = $("#height13").val();

        var intercityasal = $("#intercityasal").val();
    var intercitytujuan = $("#intercitytujuan").val();
    var service = $("#serviceshow").val();

   if(intercityasal== 'Y' && intercitytujuan == 'Y'){
        bagiv = 4000;
    }else{
        if(service== '5'){
            bagiv = 4000;
        }else{
            bagiv = 6000;
        }
    }
    //alert(bagiv);

    var collie = $("#collie").val();
     

    t = w * l * h / bagiv;
    if(we > t){
    	total = we;
    }else{
    	total = t;
    }
    $.post('module/mod_bookinginv2/bookinginv2_action.php?module=bookinginv2&act=pembulatan', {
				nomer: total
			},
			function (html) {
				$("#total13").val(html);
				var collie = $("#collie").val();
			    totalll = 0;
			    for (i = 1; i <= collie; i++) {
					  totalll = Number(totalll) + Number($('#total'+i).val());
					//  alert(i);

					}

				$("#berat_barang").val(totalll);
			}
		);

}
function hitung14() {

	var we = $("#weight14").val();
    var w = $("#width14").val();
    var l = $("#length14").val();
    var h = $("#height14").val();

        var intercityasal = $("#intercityasal").val();
    var intercitytujuan = $("#intercitytujuan").val();
    var service = $("#serviceshow").val();

   if(intercityasal== 'Y' && intercitytujuan == 'Y'){
        bagiv = 4000;
    }else{
        if(service== '5'){
            bagiv = 4000;
        }else{
            bagiv = 6000;
        }
    }
    //alert(bagiv);

    var collie = $("#collie").val();
     

    t = w * l * h / bagiv;
    if(we > t){
    	total = we;
    }else{
    	total = t;
    }
    $.post('module/mod_bookinginv2/bookinginv2_action.php?module=bookinginv2&act=pembulatan', {
				nomer: total
			},
			function (html) {
				$("#total14").val(html);
				var collie = $("#collie").val();
			    totalll = 0;
			    for (i = 1; i <= collie; i++) {
					  totalll = Number(totalll) + Number($('#total'+i).val());
					//  alert(i);

					}

				$("#berat_barang").val(totalll);
			}
		);

}
function hitung15() {

	var we = $("#weight15").val();
    var w = $("#width15").val();
    var l = $("#length15").val();
    var h = $("#height15").val();

        var intercityasal = $("#intercityasal").val();
    var intercitytujuan = $("#intercitytujuan").val();
    var service = $("#serviceshow").val();

   if(intercityasal== 'Y' && intercitytujuan == 'Y'){
        bagiv = 4000;
    }else{
        if(service== '5'){
            bagiv = 4000;
        }else{
            bagiv = 6000;
        }
    }
    //alert(bagiv);

    var collie = $("#collie").val();
     

    t = w * l * h / bagiv;
    if(we > t){
    	total = we;
    }else{
    	total = t;
    }
    $.post('module/mod_bookinginv2/bookinginv2_action.php?module=bookinginv2&act=pembulatan', {
				nomer: total
			},
			function (html) {
				$("#total15").val(html);
				var collie = $("#collie").val();
			    totalll = 0;
			    for (i = 1; i <= collie; i++) {
					  totalll = Number(totalll) + Number($('#total'+i).val());
					//  alert(i);

					}

				$("#berat_barang").val(totalll);
			}
		);

}
function hitung16() {

	var we = $("#weight16").val();
    var w = $("#width16").val();
    var l = $("#length16").val();
    var h = $("#height16").val();

        var intercityasal = $("#intercityasal").val();
    var intercitytujuan = $("#intercitytujuan").val();
    var service = $("#serviceshow").val();

   if(intercityasal== 'Y' && intercitytujuan == 'Y'){
        bagiv = 4000;
    }else{
        if(service== '5'){
            bagiv = 4000;
        }else{
            bagiv = 6000;
        }
    }
    //alert(bagiv);

    var collie = $("#collie").val();
     

    t = w * l * h / bagiv;
    if(we > t){
    	total = we;
    }else{
    	total = t;
    }
    $.post('module/mod_bookinginv2/bookinginv2_action.php?module=bookinginv2&act=pembulatan', {
				nomer: total
			},
			function (html) {
				$("#total16").val(html);
				var collie = $("#collie").val();
			    totalll = 0;
			    for (i = 1; i <= collie; i++) {
					  totalll = Number(totalll) + Number($('#total'+i).val());
					//  alert(i);

					}

				$("#berat_barang").val(totalll);
			}
		);

}
function hitung17() {

	var we = $("#weight17").val();
    var w = $("#width17").val();
    var l = $("#length17").val();
    var h = $("#height17").val();

        var intercityasal = $("#intercityasal").val();
    var intercitytujuan = $("#intercitytujuan").val();
    var service = $("#serviceshow").val();

   if(intercityasal== 'Y' && intercitytujuan == 'Y'){
        bagiv = 4000;
    }else{
        if(service== '5'){
            bagiv = 4000;
        }else{
            bagiv = 6000;
        }
    }
    //alert(bagiv);

    var collie = $("#collie").val();
     

    t = w * l * h / bagiv;
    if(we > t){
    	total = we;
    }else{
    	total = t;
    }
    $.post('module/mod_bookinginv2/bookinginv2_action.php?module=bookinginv2&act=pembulatan', {
				nomer: total
			},
			function (html) {
				$("#total17").val(html);
				var collie = $("#collie").val();
			    totalll = 0;
			    for (i = 1; i <= collie; i++) {
					  totalll = Number(totalll) + Number($('#total'+i).val());
					//  alert(i);

					}

				$("#berat_barang").val(totalll);
			}
		);

}
function hitung18() {

	var we = $("#weight18").val();
    var w = $("#width18").val();
    var l = $("#length18").val();
    var h = $("#height18").val();

        var intercityasal = $("#intercityasal").val();
    var intercitytujuan = $("#intercitytujuan").val();
    var service = $("#serviceshow").val();

   if(intercityasal== 'Y' && intercitytujuan == 'Y'){
        bagiv = 4000;
    }else{
        if(service== '5'){
            bagiv = 4000;
        }else{
            bagiv = 6000;
        }
    }
    //alert(bagiv);

    var collie = $("#collie").val();
     

    t = w * l * h / bagiv;
    if(we > t){
    	total = we;
    }else{
    	total = t;
    }
    $.post('module/mod_bookinginv2/bookinginv2_action.php?module=bookinginv2&act=pembulatan', {
				nomer: total
			},
			function (html) {
				$("#total18").val(html);
				var collie = $("#collie").val();
			    totalll = 0;
			    for (i = 1; i <= collie; i++) {
					  totalll = Number(totalll) + Number($('#total'+i).val());
					//  alert(i);

					}

				$("#berat_barang").val(totalll);
			}
		);


}
function hitung19() {

	var we = $("#weight19").val();
    var w = $("#width19").val();
    var l = $("#length19").val();
    var h = $("#height19").val();

        var intercityasal = $("#intercityasal").val();
    var intercitytujuan = $("#intercitytujuan").val();
    var service = $("#serviceshow").val();

   if(intercityasal== 'Y' && intercitytujuan == 'Y'){
        bagiv = 4000;
    }else{
        if(service== '5'){
            bagiv = 4000;
        }else{
            bagiv = 6000;
        }
    }
    //alert(bagiv);

    var collie = $("#collie").val();
     

    t = w * l * h / bagiv;
    if(we > t){
    	total = we;
    }else{
    	total = t;
    }
    $.post('module/mod_bookinginv2/bookinginv2_action.php?module=bookinginv2&act=pembulatan', {
				nomer: total
			},
			function (html) {
				$("#total19").val(html);
				var collie = $("#collie").val();
			    totalll = 0;
			    for (i = 1; i <= collie; i++) {
					  totalll = Number(totalll) + Number($('#total'+i).val());
					//  alert(i);

					}

				$("#berat_barang").val(totalll);
			}
		);


}
function hitung20() {

	var we = $("#weight20").val();
    var w = $("#width20").val();
    var l = $("#length20").val();
    var h = $("#height20").val();

        var intercityasal = $("#intercityasal").val();
    var intercitytujuan = $("#intercitytujuan").val();
    var service = $("#serviceshow").val();

   if(intercityasal== 'Y' && intercitytujuan == 'Y'){
        bagiv = 4000;
    }else{
        if(service== '5'){
            bagiv = 4000;
        }else{
            bagiv = 6000;
        }
    }
    //alert(bagiv);

    var collie = $("#collie").val();
     

    t = w * l * h / bagiv;
    if(we > t){
    	total = we;
    }else{
    	total = t;
    }
    $.post('module/mod_bookinginv2/bookinginv2_action.php?module=bookinginv2&act=pembulatan', {
				nomer: total
			},
			function (html) {
				$("#total20").val(html);
				var collie = $("#collie").val();
			    totalll = 0;
			    for (i = 1; i <= collie; i++) {
					  totalll = Number(totalll) + Number($('#total'+i).val());
					//  alert(i);

					}

				$("#berat_barang").val(totalll);
			}
		);

}

function hitung21() {

	var we = $("#weight21").val();
    var w = $("#width21").val();
    var l = $("#length21").val();
    var h = $("#height21").val();

        var intercityasal = $("#intercityasal").val();
    var intercitytujuan = $("#intercitytujuan").val();
    var service = $("#serviceshow").val();

   if(intercityasal== 'Y' && intercitytujuan == 'Y'){
        bagiv = 4000;
    }else{
        if(service== '5'){
            bagiv = 4000;
        }else{
            bagiv = 6000;
        }
    }
    //alert(bagiv);

    var collie = $("#collie").val();
     

    t = w * l * h / bagiv;
    if(we > t){
    	total = we;
    }else{
    	total = t;
    }
    $.post('module/mod_bookinginv2/bookinginv2_action.php?module=bookinginv2&act=pembulatan', {
				nomer: total
			},
			function (html) {
				$("#total21").val(html);
				var collie = $("#collie").val();
			    totalll = 0;
			    for (i = 1; i <= collie; i++) {
					  totalll = Number(totalll) + Number($('#total'+i).val());
					//  alert(i);

					}

				$("#berat_barang").val(totalll);
			}
		);

}

function hitung22() {

	var we = $("#weight22").val();
    var w = $("#width22").val();
    var l = $("#length22").val();
    var h = $("#height22").val();

        var intercityasal = $("#intercityasal").val();
    var intercitytujuan = $("#intercitytujuan").val();
    var service = $("#serviceshow").val();

   if(intercityasal== 'Y' && intercitytujuan == 'Y'){
        bagiv = 4000;
    }else{
        if(service== '5'){
            bagiv = 4000;
        }else{
            bagiv = 6000;
        }
    }
    //alert(bagiv);

    var collie = $("#collie").val();
     

    t = w * l * h / bagiv;
    if(we > t){
    	total = we;
    }else{
    	total = t;
    }
    $.post('module/mod_bookinginv2/bookinginv2_action.php?module=bookinginv2&act=pembulatan', {
				nomer: total
			},
			function (html) {
				$("#total22").val(html);
				var collie = $("#collie").val();
			    totalll = 0;
			    for (i = 1; i <= collie; i++) {
					  totalll = Number(totalll) + Number($('#total'+i).val());
					//  alert(i);

					}

				$("#berat_barang").val(totalll);
			}
		);

}
function hitung23() {

	var we = $("#weight21").val();
    var w = $("#width21").val();
    var l = $("#length21").val();
    var h = $("#height21").val();

        var intercityasal = $("#intercityasal").val();
    var intercitytujuan = $("#intercitytujuan").val();
    var service = $("#serviceshow").val();

   if(intercityasal== 'Y' && intercitytujuan == 'Y'){
        bagiv = 4000;
    }else{
        if(service== '5'){
            bagiv = 4000;
        }else{
            bagiv = 6000;
        }
    }
    //alert(bagiv);

    var collie = $("#collie").val();
     

    t = w * l * h / bagiv;
    if(we > t){
    	total = we;
    }else{
    	total = t;
    }
    $.post('module/mod_bookinginv2/bookinginv2_action.php?module=bookinginv2&act=pembulatan', {
				nomer: total
			},
			function (html) {
				$("#total23").val(html);
				var collie = $("#collie").val();
			    totalll = 0;
			    for (i = 1; i <= collie; i++) {
					  totalll = Number(totalll) + Number($('#total'+i).val());
					//  alert(i);

					}

				$("#berat_barang").val(totalll);
			}
		);

}
function hitung24() {

	var we = $("#weight24").val();
    var w = $("#width24").val();
    var l = $("#length24").val();
    var h = $("#height24").val();

        var intercityasal = $("#intercityasal").val();
    var intercitytujuan = $("#intercitytujuan").val();
    var service = $("#serviceshow").val();

   if(intercityasal== 'Y' && intercitytujuan == 'Y'){
        bagiv = 4000;
    }else{
        if(service== '5'){
            bagiv = 4000;
        }else{
            bagiv = 6000;
        }
    }
    //alert(bagiv);

    var collie = $("#collie").val();
     

    t = w * l * h / bagiv;
    if(we > t){
    	total = we;
    }else{
    	total = t;
    }
    $.post('module/mod_bookinginv2/bookinginv2_action.php?module=bookinginv2&act=pembulatan', {
				nomer: total
			},
			function (html) {
				$("#total24").val(html);
				var collie = $("#collie").val();
			    totalll = 0;
			    for (i = 1; i <= collie; i++) {
					  totalll = Number(totalll) + Number($('#total'+i).val());
					//  alert(i);

					}

				$("#berat_barang").val(totalll);
			}
		);

}

function hitung21() {

	var we = $("#weight25").val();
    var w = $("#width25").val();
    var l = $("#length25").val();
    var h = $("#height25").val();

        var intercityasal = $("#intercityasal").val();
    var intercitytujuan = $("#intercitytujuan").val();
    var service = $("#serviceshow").val();

   if(intercityasal== 'Y' && intercitytujuan == 'Y'){
        bagiv = 4000;
    }else{
        if(service== '5'){
            bagiv = 4000;
        }else{
            bagiv = 6000;
        }
    }
    //alert(bagiv);

    var collie = $("#collie").val();
     

    t = w * l * h / bagiv;
    if(we > t){
    	total = we;
    }else{
    	total = t;
    }
    $.post('module/mod_bookinginv2/bookinginv2_action.php?module=bookinginv2&act=pembulatan', {
				nomer: total
			},
			function (html) {
				$("#total25").val(html);
				var collie = $("#collie").val();
			    totalll = 0;
			    for (i = 1; i <= collie; i++) {
					  totalll = Number(totalll) + Number($('#total'+i).val());
					//  alert(i);

					}

				$("#berat_barang").val(totalll);
			}
		);

}
function hitung26() {

	var we = $("#weight26").val();
    var w = $("#width26").val();
    var l = $("#length26").val();
    var h = $("#height26").val();

        var intercityasal = $("#intercityasal").val();
    var intercitytujuan = $("#intercitytujuan").val();
    var service = $("#serviceshow").val();

   if(intercityasal== 'Y' && intercitytujuan == 'Y'){
        bagiv = 4000;
    }else{
        if(service== '5'){
            bagiv = 4000;
        }else{
            bagiv = 6000;
        }
    }
    //alert(bagiv);

    var collie = $("#collie").val();
     

    t = w * l * h / bagiv;
    if(we > t){
    	total = we;
    }else{
    	total = t;
    }
    $.post('module/mod_bookinginv2/bookinginv2_action.php?module=bookinginv2&act=pembulatan', {
				nomer: total
			},
			function (html) {
				$("#total26").val(html);
				var collie = $("#collie").val();
			    totalll = 0;
			    for (i = 1; i <= collie; i++) {
					  totalll = Number(totalll) + Number($('#total'+i).val());
					//  alert(i);

					}

				$("#berat_barang").val(totalll);
			}
		);

}
function hitung27() {

	var we = $("#weight27").val();
    var w = $("#width27").val();
    var l = $("#length27").val();
    var h = $("#height27").val();

        var intercityasal = $("#intercityasal").val();
    var intercitytujuan = $("#intercitytujuan").val();
    var service = $("#serviceshow").val();

   if(intercityasal== 'Y' && intercitytujuan == 'Y'){
        bagiv = 4000;
    }else{
        if(service== '5'){
            bagiv = 4000;
        }else{
            bagiv = 6000;
        }
    }
    //alert(bagiv);

    var collie = $("#collie").val();
     

    t = w * l * h / bagiv;
    if(we > t){
    	total = we;
    }else{
    	total = t;
    }
    $.post('module/mod_bookinginv2/bookinginv2_action.php?module=bookinginv2&act=pembulatan', {
				nomer: total
			},
			function (html) {
				$("#total27").val(html);
				var collie = $("#collie").val();
			    totalll = 0;
			    for (i = 1; i <= collie; i++) {
					  totalll = Number(totalll) + Number($('#total'+i).val());
					//  alert(i);

					}

				$("#berat_barang").val(totalll);
			}
		);

}

function hitung28() {

	var we = $("#weight28").val();
    var w = $("#width28").val();
    var l = $("#length28").val();
    var h = $("#height28").val();

        var intercityasal = $("#intercityasal").val();
    var intercitytujuan = $("#intercitytujuan").val();
    var service = $("#serviceshow").val();

   if(intercityasal== 'Y' && intercitytujuan == 'Y'){
        bagiv = 4000;
    }else{
        if(service== '5'){
            bagiv = 4000;
        }else{
            bagiv = 6000;
        }
    }
    //alert(bagiv);

    var collie = $("#collie").val();
     

    t = w * l * h / bagiv;
    if(we > t){
    	total = we;
    }else{
    	total = t;
    }
    $.post('module/mod_bookinginv2/bookinginv2_action.php?module=bookinginv2&act=pembulatan', {
				nomer: total
			},
			function (html) {
				$("#total28").val(html);
				var collie = $("#collie").val();
			    totalll = 0;
			    for (i = 1; i <= collie; i++) {
					  totalll = Number(totalll) + Number($('#total'+i).val());
					//  alert(i);

					}

				$("#berat_barang").val(totalll);
			}
		);

}
function hitung21() {

	var we = $("#weight29").val();
    var w = $("#width29").val();
    var l = $("#length29").val();
    var h = $("#height29").val();

        var intercityasal = $("#intercityasal").val();
    var intercitytujuan = $("#intercitytujuan").val();
    var service = $("#serviceshow").val();

   if(intercityasal== 'Y' && intercitytujuan == 'Y'){
        bagiv = 4000;
    }else{
        if(service== '5'){
            bagiv = 4000;
        }else{
            bagiv = 6000;
        }
    }
    //alert(bagiv);

    var collie = $("#collie").val();
     

    t = w * l * h / bagiv;
    if(we > t){
    	total = we;
    }else{
    	total = t;
    }
    $.post('module/mod_bookinginv2/bookinginv2_action.php?module=bookinginv2&act=pembulatan', {
				nomer: total
			},
			function (html) {
				$("#total29").val(html);
				var collie = $("#collie").val();
			    totalll = 0;
			    for (i = 1; i <= collie; i++) {
					  totalll = Number(totalll) + Number($('#total'+i).val());
					//  alert(i);

					}

				$("#berat_barang").val(totalll);
			}
		);

}
function hitung21() {

	var we = $("#weight30").val();
    var w = $("#width30").val();
    var l = $("#length30").val();
    var h = $("#height30").val();

        var intercityasal = $("#intercityasal").val();
    var intercitytujuan = $("#intercitytujuan").val();
    var service = $("#serviceshow").val();

   if(intercityasal== 'Y' && intercitytujuan == 'Y'){
        bagiv = 4000;
    }else{
        if(service== '5'){
            bagiv = 4000;
        }else{
            bagiv = 6000;
        }
    }
    //alert(bagiv);

    var collie = $("#collie").val();
     

    t = w * l * h / bagiv;
    if(we > t){
    	total = we;
    }else{
    	total = t;
    }
    $.post('module/mod_bookinginv2/bookinginv2_action.php?module=bookinginv2&act=pembulatan', {
				nomer: total
			},
			function (html) {
				$("#total30").val(html);
				var collie = $("#collie").val();
			    totalll = 0;
			    for (i = 1; i <= collie; i++) {
					  totalll = Number(totalll) + Number($('#total'+i).val());
					//  alert(i);

					}

				$("#berat_barang").val(totalll);
			}
		);

}
function hitung31() {

	var we = $("#weight31").val();
    var w = $("#width31").val();
    var l = $("#length31").val();
    var h = $("#height31").val();

        var intercityasal = $("#intercityasal").val();
    var intercitytujuan = $("#intercitytujuan").val();
    var service = $("#serviceshow").val();

   if(intercityasal== 'Y' && intercitytujuan == 'Y'){
        bagiv = 4000;
    }else{
        if(service== '5'){
            bagiv = 4000;
        }else{
            bagiv = 6000;
        }
    }
    //alert(bagiv);

    var collie = $("#collie").val();
     

    t = w * l * h / bagiv;
    if(we > t){
    	total = we;
    }else{
    	total = t;
    }
    $.post('module/mod_bookinginv2/bookinginv2_action.php?module=bookinginv2&act=pembulatan', {
				nomer: total
			},
			function (html) {
				$("#total31").val(html);
				var collie = $("#collie").val();
			    totalll = 0;
			    for (i = 1; i <= collie; i++) {
					  totalll = Number(totalll) + Number($('#total'+i).val());
					//  alert(i);

					}

				$("#berat_barang").val(totalll);
			}
		);

}
function hitung32() {

	var we = $("#weight32").val();
    var w = $("#width32").val();
    var l = $("#length32").val();
    var h = $("#height32").val();

        var intercityasal = $("#intercityasal").val();
    var intercitytujuan = $("#intercitytujuan").val();
    var service = $("#serviceshow").val();

   if(intercityasal== 'Y' && intercitytujuan == 'Y'){
        bagiv = 4000;
    }else{
        if(service== '5'){
            bagiv = 4000;
        }else{
            bagiv = 6000;
        }
    }
    //alert(bagiv);

    var collie = $("#collie").val();
     

    t = w * l * h / bagiv;
    if(we > t){
    	total = we;
    }else{
    	total = t;
    }
    $.post('module/mod_bookinginv2/bookinginv2_action.php?module=bookinginv2&act=pembulatan', {
				nomer: total
			},
			function (html) {
				$("#total32").val(html);
				var collie = $("#collie").val();
			    totalll = 0;
			    for (i = 1; i <= collie; i++) {
					  totalll = Number(totalll) + Number($('#total'+i).val());
					//  alert(i);

					}

				$("#berat_barang").val(totalll);
			}
		);

}
function hitung33() {

	var we = $("#weight21").val();
    var w = $("#width21").val();
    var l = $("#length21").val();
    var h = $("#height21").val();

        var intercityasal = $("#intercityasal").val();
    var intercitytujuan = $("#intercitytujuan").val();
    var service = $("#serviceshow").val();

   if(intercityasal== 'Y' && intercitytujuan == 'Y'){
        bagiv = 4000;
    }else{
        if(service== '5'){
            bagiv = 4000;
        }else{
            bagiv = 6000;
        }
    }
    //alert(bagiv);

    var collie = $("#collie").val();
     

    t = w * l * h / bagiv;
    if(we > t){
    	total = we;
    }else{
    	total = t;
    }
    $.post('module/mod_bookinginv2/bookinginv2_action.php?module=bookinginv2&act=pembulatan', {
				nomer: total
			},
			function (html) {
				$("#total33").val(html);
				var collie = $("#collie").val();
			    totalll = 0;
			    for (i = 1; i <= collie; i++) {
					  totalll = Number(totalll) + Number($('#total'+i).val());
					//  alert(i);

					}

				$("#berat_barang").val(totalll);
			}
		);

}
function hitung34() {

	var we = $("#weight34").val();
    var w = $("#width34").val();
    var l = $("#length34").val();
    var h = $("#height34").val();

        var intercityasal = $("#intercityasal").val();
    var intercitytujuan = $("#intercitytujuan").val();
    var service = $("#serviceshow").val();

   if(intercityasal== 'Y' && intercitytujuan == 'Y'){
        bagiv = 4000;
    }else{
        if(service== '5'){
            bagiv = 4000;
        }else{
            bagiv = 6000;
        }
    }
    //alert(bagiv);

    var collie = $("#collie").val();
     

    t = w * l * h / bagiv;
    if(we > t){
    	total = we;
    }else{
    	total = t;
    }
    $.post('module/mod_bookinginv2/bookinginv2_action.php?module=bookinginv2&act=pembulatan', {
				nomer: total
			},
			function (html) {
				$("#total34").val(html);
				var collie = $("#collie").val();
			    totalll = 0;
			    for (i = 1; i <= collie; i++) {
					  totalll = Number(totalll) + Number($('#total'+i).val());
					//  alert(i);

					}

				$("#berat_barang").val(totalll);
			}
		);

}
function hitung35() {

	var we = $("#weight35").val();
    var w = $("#width35").val();
    var l = $("#length35").val();
    var h = $("#height35").val();

        var intercityasal = $("#intercityasal").val();
    var intercitytujuan = $("#intercitytujuan").val();
    var service = $("#serviceshow").val();

   if(intercityasal== 'Y' && intercitytujuan == 'Y'){
        bagiv = 4000;
    }else{
        if(service== '5'){
            bagiv = 4000;
        }else{
            bagiv = 6000;
        }
    }
    //alert(bagiv);

    var collie = $("#collie").val();
     

    t = w * l * h / bagiv;
    if(we > t){
    	total = we;
    }else{
    	total = t;
    }
    $.post('module/mod_bookinginv2/bookinginv2_action.php?module=bookinginv2&act=pembulatan', {
				nomer: total
			},
			function (html) {
				$("#total35").val(html);
				var collie = $("#collie").val();
			    totalll = 0;
			    for (i = 1; i <= collie; i++) {
					  totalll = Number(totalll) + Number($('#total'+i).val());
					//  alert(i);

					}

				$("#berat_barang").val(totalll);
			}
		);

}
function hitung36() {

	var we = $("#weight36").val();
    var w = $("#width36").val();
    var l = $("#length36").val();
    var h = $("#height36").val();

        var intercityasal = $("#intercityasal").val();
    var intercitytujuan = $("#intercitytujuan").val();
    var service = $("#serviceshow").val();

   if(intercityasal== 'Y' && intercitytujuan == 'Y'){
        bagiv = 4000;
    }else{
        if(service== '5'){
            bagiv = 4000;
        }else{
            bagiv = 6000;
        }
    }
    //alert(bagiv);

    var collie = $("#collie").val();
     

    t = w * l * h / bagiv;
    if(we > t){
    	total = we;
    }else{
    	total = t;
    }
    $.post('module/mod_bookinginv2/bookinginv2_action.php?module=bookinginv2&act=pembulatan', {
				nomer: total
			},
			function (html) {
				$("#total36").val(html);
				var collie = $("#collie").val();
			    totalll = 0;
			    for (i = 1; i <= collie; i++) {
					  totalll = Number(totalll) + Number($('#total'+i).val());
					//  alert(i);

					}

				$("#berat_barang").val(totalll);
			}
		);

}
function hitung37() {

	var we = $("#weight37").val();
    var w = $("#width37").val();
    var l = $("#length37").val();
    var h = $("#height37").val();

        var intercityasal = $("#intercityasal").val();
    var intercitytujuan = $("#intercitytujuan").val();
    var service = $("#serviceshow").val();

   if(intercityasal== 'Y' && intercitytujuan == 'Y'){
        bagiv = 4000;
    }else{
        if(service== '5'){
            bagiv = 4000;
        }else{
            bagiv = 6000;
        }
    }
    //alert(bagiv);

    var collie = $("#collie").val();
     

    t = w * l * h / bagiv;
    if(we > t){
    	total = we;
    }else{
    	total = t;
    }
    $.post('module/mod_bookinginv2/bookinginv2_action.php?module=bookinginv2&act=pembulatan', {
				nomer: total
			},
			function (html) {
				$("#total37").val(html);
				var collie = $("#collie").val();
			    totalll = 0;
			    for (i = 1; i <= collie; i++) {
					  totalll = Number(totalll) + Number($('#total'+i).val());
					//  alert(i);

					}

				$("#berat_barang").val(totalll);
			}
		);

}
function hitung38() {

	var we = $("#weight38").val();
    var w = $("#width38").val();
    var l = $("#length38").val();
    var h = $("#height38").val();

        var intercityasal = $("#intercityasal").val();
    var intercitytujuan = $("#intercitytujuan").val();
    var service = $("#serviceshow").val();

   if(intercityasal== 'Y' && intercitytujuan == 'Y'){
        bagiv = 4000;
    }else{
        if(service== '5'){
            bagiv = 4000;
        }else{
            bagiv = 6000;
        }
    }
    //alert(bagiv);

    var collie = $("#collie").val();
     

    t = w * l * h / bagiv;
    if(we > t){
    	total = we;
    }else{
    	total = t;
    }
    $.post('module/mod_bookinginv2/bookinginv2_action.php?module=bookinginv2&act=pembulatan', {
				nomer: total
			},
			function (html) {
				$("#total38").val(html);
				var collie = $("#collie").val();
			    totalll = 0;
			    for (i = 1; i <= collie; i++) {
					  totalll = Number(totalll) + Number($('#total'+i).val());
					//  alert(i);

					}

				$("#berat_barang").val(totalll);
			}
		);

}
function hitung39() {

	var we = $("#weight39").val();
    var w = $("#width39").val();
    var l = $("#length39").val();
    var h = $("#height39").val();

        var intercityasal = $("#intercityasal").val();
    var intercitytujuan = $("#intercitytujuan").val();
    var service = $("#serviceshow").val();

   if(intercityasal== 'Y' && intercitytujuan == 'Y'){
        bagiv = 4000;
    }else{
        if(service== '5'){
            bagiv = 4000;
        }else{
            bagiv = 6000;
        }
    }
    //alert(bagiv);

    var collie = $("#collie").val();
     

    t = w * l * h / bagiv;
    if(we > t){
    	total = we;
    }else{
    	total = t;
    }
    $.post('module/mod_bookinginv2/bookinginv2_action.php?module=bookinginv2&act=pembulatan', {
				nomer: total
			},
			function (html) {
				$("#total39").val(html);
				var collie = $("#collie").val();
			    totalll = 0;
			    for (i = 1; i <= collie; i++) {
					  totalll = Number(totalll) + Number($('#total'+i).val());
					//  alert(i);

					}

				$("#berat_barang").val(totalll);
			}
		);

}
function hitung40() {

	var we = $("#weight40").val();
    var w = $("#width40").val();
    var l = $("#length40").val();
    var h = $("#height40").val();

        var intercityasal = $("#intercityasal").val();
    var intercitytujuan = $("#intercitytujuan").val();
    var service = $("#serviceshow").val();

   if(intercityasal== 'Y' && intercitytujuan == 'Y'){
        bagiv = 4000;
    }else{
        if(service== '5'){
            bagiv = 4000;
        }else{
            bagiv = 6000;
        }
    }
    //alert(bagiv);

    var collie = $("#collie").val();
     

    t = w * l * h / bagiv;
    if(we > t){
    	total = we;
    }else{
    	total = t;
    }
    $.post('module/mod_bookinginv2/bookinginv2_action.php?module=bookinginv2&act=pembulatan', {
				nomer: total
			},
			function (html) {
				$("#total40").val(html);
				var collie = $("#collie").val();
			    totalll = 0;
			    for (i = 1; i <= collie; i++) {
					  totalll = Number(totalll) + Number($('#total'+i).val());
					//  alert(i);

					}

				$("#berat_barang").val(totalll);
			}
		);

}

function hitungtotal(){
	alert('function aktif');
	var tsatu = $("#total1").val();
	
	    
    
    to = t1;

	$("#berat_barang").val(to);    


}




</script>
<script type="text/javascript">
		/*
		var rupiah = document.getElementById('nilai_barang');
		
		rupiah.addEventListener('keyup', function(e){
			// tambahkan 'Rp.' pada saat form di ketik
			// gunakan fungsi formatRupiah() untuk mengubah angka yang di ketik menjadi format angka
			rupiah.value = formatRupiah(this.value);
			


		});
*/

 
		/* Fungsi formatRupiah */
	/*	function formatRupiah(angka, prefix){
			var number_string = angka.replace(/[^,\d]/g, '').toString(),
			split   		= number_string.split(','),
			sisa     		= split[0].length % 3,
			rupiah     		= split[0].substr(0, sisa),
			ribuan     		= split[0].substr(sisa).match(/\d{3}/gi);
 
			// tambahkan titik jika yang di input sudah menjadi angka ribuan
			if(ribuan){
				separator = sisa ? '.' : '';
				rupiah += separator + ribuan.join('.');
			}
 
			rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
			return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
		}*/
	</script>