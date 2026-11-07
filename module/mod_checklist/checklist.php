<section class="content-header">
	<h1>Checklist<small>airwaybill confirmation</small></h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-home"></i> Home</a></li>
		<li><a href="#">Application</a></li>
		<li>Manifest</li>
		<li class="active">Checklist</li>
	</ol>
</section>
<?php
if(!isset($_SESSION)){
    session_start();
}
//Deteksi hanya bisa diinclude, tidak bisa langsung dibuka (direct open)
if(count(get_included_files())==1){
	echo "<meta http-equiv='refresh' content='0; url=http://$_SERVER[HTTP_HOST]'>";
	exit("Direct access not permitted.");
}
if(empty($_SESSION['username']) AND empty($_SESSION['password'])){
	header('location:../../application/login.php');
}
else{
	if($u['level']=='admin' OR $u['level']=='operator' OR $u['level']=='network' OR $u['level']=='kurir' OR $u['level']=='driver'){
	
		$aksi="module/mod_checklist/checklist_action.php";
	
		$act = !empty($_GET['act']) ? $_GET['act'] : '';
	
		switch($act){
			// Tampilksn Barcode Scan
			default:
			
			echo'
			<script language="JavaScript" type="text/JavaScript"> 
			var x=12;//nr characters 
			function submitT(t,f){ 
				if(t.value.length==x){ 
					f.submit() 
				} 
			}
			</script>
			<section class="content">
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">Scan Airwaybill</h3>
						<div class="box-tools pull-right">
							<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
							<button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
						</div>
					</div><!-- /.box-header -->
					<form method="post" action="" class="form-horizontal form-bordered" id="form-checklist" autocomplete="off">
					<div class="box-body">
						<div class="form-group">
							<label class="col-sm-3 control-label full-right">Scan or Input QRCode <span class="required">* </span></label>
							<div class="col-sm-6">
								<input type="text" class="form-control" name="airwaybill" id="scan_airwaybill" onkeyup="submitT(this,this.form)">
							</div>
						</div>
					</div>
					</form>
				</div>';
				
				if(isset($_POST['airwaybill'])){
					$id = "$_POST[airwaybill]";
					$query = mysqli_query($con, "select *, x_namakustomer(id_kustomer) as kustomer, x_namakotabesar(lokasi_asal) as asal, x_namakotabesar(lokasi_tujuan) as tujuan, x_service(service) as service, SUBSTRING_INDEX(ukuran_barang,'x',1) as length, SUBSTRING_INDEX(SUBSTRING_INDEX(ukuran_barang,'x',2),'x',-1) as width, SUBSTRING_INDEX(ukuran_barang,'x',-1) as height from pengiriman where airwaybill_kode='$id' and manifest='N' and service!='1'");
					$ketemu	= mysqli_num_rows($query);
					$r = mysqli_fetch_assoc($query);
					if ($ketemu > 0){
						echo'
						<div class="box box-primary">
							<div class="box-header with-border">
								<h3 class="box-title">Detail Airwaybill: '.$r['airwaybill_kode'].'</h3>
							</div><!-- /.box-header -->
							<form method="post" action="'.$aksi.'?module=checklist&act=update" class="form-horizontal form-bordered" id="form-checklist" autocomplete="off">
							<input type="hidden" name="id" value="'.$r['id_pengiriman'].'">
							<div class="box-body">
								<div class="form-group">
									<label class="control-label col-md-3">Nama Pengirim</label>
									<div class="col-md-9">
										<p class="form-control-static">
											'.$r['nama_pengirim'].'
										</p>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-3">Alamat Pengirim</label>
									<div class="col-md-9">
										<p class="form-control-static">
											'.$r['alamat_pengirim'].'
										</p>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-3">HP Pengirim</label>
									<div class="col-md-9">
										<p class="form-control-static">
											'.$r['hp_pengirim'].'
										</p>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-3">Perusahaan Pengirim</label>
									<div class="col-md-9">
										<p class="form-control-static">';
										if($r['inbound']=='Y'){
											echo''.$r['perusahaan_pengirim'].'';
										}else{
											echo''.$r['kustomer'].'';
										}
										echo'
										</p>
									</div>
								</div>
								<hr>
								<div class="form-group">
									<label class="control-label col-md-3">Nama Penerima</label>
									<div class="col-md-9">
										<p class="form-control-static">
											'.$r['nama_penerima'].'
										</p>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-3">Alamat Penerima</label>
									<div class="col-md-9">
										<p class="form-control-static">
											'.$r['alamat_penerima'].'
										</p>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-3">HP Penerima</label>
									<div class="col-md-9">
										<p class="form-control-static">
											'.$r['hp_penerima'].'
										</p>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-3">Perusahaan Penerima</label>
									<div class="col-md-9">
										<p class="form-control-static">
											'.$r['perusahaan_penerima'].'
										</p>
									</div>
								</div>
								<hr>
								<div class="form-group">
									<label class="control-label col-md-3">Kota Asal</label>
									<div class="col-md-9">
										<p class="form-control-static">';
											if($r['asal']=='Jakarta Selatan' || $r['asal']=='Jakarta Timur' || $r['asal']=='Jakarta Barat' ||		$r['asal']=='Jakarta Utara' || $r['asal']=='Jakarta Pusat'){
												$asal = 'Jakarta';
											}else{
												$asal = $r['asal'];
											}
											echo'
											'.$asal.'
										</p>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-3">Kota Tujuan</label>
									<div class="col-md-9">
										<p class="form-control-static">';
											if($r['tujuan']=='Jakarta Selatan' || $r['tujuan']=='Jakarta Timur' || $r['tujuan']=='Jakarta Barat' ||		$r['tujuan']=='Jakarta Utara' || $r['tujuan']=='Jakarta Pusat'){
												$tujuan = 'Jakarta';
											}else{
												$tujuan = $r['tujuan'];
											}
											echo'
											'.$tujuan.'
										</p>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-3">Service</label>
									<div class="col-md-9">
										<p class="form-control-static">
											'.$r['service'].'
										</p>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-3">Komoditi</label>
									<div class="col-md-9">
										<p class="form-control-static">';
											if($r['komoditi']=='1'){
												echo'Paket';
											}else{
												echo'Dokumen';
											}
											echo'
										</p>
									</div>
								</div>';
								if($r['komoditi']=='1'){
									echo'
									<div class="form-group">
										<label class="col-md-3 control-label">Jenis Barang</label>
										<div class="col-md-9">
											<p class="form-control-static">
												'.$r['jenis_barang'].'
											</p>
										</div>
									</div>
									<div class="form-group">
										<label class="col-md-3 control-label">Estimasi Berat Barang</label>
										<div class="col-md-9">
											<p class="form-control-static">
												'.$r['berat_barang'].' Kg
											</p>
										</div>
									</div>
									<div class="form-group">
										<label class="col-md-3 control-label">Berat Aktual Barang</label>
										<div class="col-md-6">
											<div class="input-group">
												<input type="text" name="berat_aktual" class="form-control" value="'.$r['berat_barang'].'"> <span class="input-group-addon">Kg</span>
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
										<label class="col-md-3 control-label">Jumlah Collie/Pcs:</label>
										<div class="col-md-6">
											<input type="text" name="collie" placeholder="Collie..." class="form-control number" value="'.$r['collie'].'">
										</div>
									</div>
									<div class="form-group">
										<label class="col-md-3 control-label">Packing:</label>
										<div class="col-md-9">
											<p class="form-control-static">';
											if($r['packing_kayu']=='1'){
												echo'Packing Oleh Pemilik';
											}elseif($r['packing_kayu']=='2'){
												echo'Packing Kayu Oleh Cesco Log.';
											}else{
												echo'Non Packing Kayu Oleh Cesco Log.';
											}
											echo'
											</p>
										</div>
									</div>';
								}
								echo'
								<div class="form-group">
									<label class="col-md-3 control-label">Asuransi</label>
									<div class="col-md-9">
										<p class="form-control-static">';
										if($r['asuransi']=="Y"){
											echo'Ya';
										}else{
											echo'Tidak';
										}
										echo'	
										</p>
									</div>
								</div>';
								if($r['asuransi']=='Y'){
									echo'
									<div class="form-group">
										<label class="col-md-3 control-label">Nilai Barang:</label>
										<div class="col-md-9">
											<p class="form-control-static">
												'.number_format($r['nilai_barang']).'
											</p>
										</div>
									</div>';
								}
								echo'
							</div>
							<div class="box-footer">
								<button type="button" class="btn btn-default" onclick="window.location.href = \'?module=checklist\';">Cancel</button>
								<button type="submit" class="btn btn-primary pull-right">Checklist</button>
							</div><!-- /.box-footer -->
							</form>
						</div>';
					}else{
						echo'
						<div class="box box-primary">
							<div class="box-header with-border">
								<h3 class="box-title">Airwaybill sudah di checklist</h3>
							</div>
						</div>';
					}
				}
				echo'
			</section>';
		
			break;
		
		}
	}else{
		echo akses_salah();
	}
}
?>