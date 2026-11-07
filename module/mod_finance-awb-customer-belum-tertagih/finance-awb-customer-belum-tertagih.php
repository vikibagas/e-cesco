<section class="content-header">
	<h1>AWB Belum Tertagih<small>Finance</small></h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-home"></i> Home</a></li>
		<li><a href="#">Finance</a></li>
		<li class="active">AWB Belum Tertagih</li>
	</ol>
</section>
<?php
if(!isset($_SESSION)){
    session_start();
}

if (empty($_SESSION['username']) AND empty($_SESSION['password'])){
	header('location:../application/login.php');
}
else{
	//cek hak akses user
	if($u['level']=='admin' || $u['id_user']== 5 || $u['level'] == 'finance')
	{

		$aksi	= "module/mod_finance-awb-customer-belum-tertagih/finance-awb-customer-belum-tertagih_action.php";
		
		$act = !empty($_GET['act']) ? $_GET['act'] : '';
		
		switch($act){
			
			default:
			echo'
			<section class="content">
				<div class="row">
					<div class="col-xs-12">
						<div class="box box-primary">
							
							<div class="box-body">
								<table id="belum-tertagih" class="table table-bordered table-striped table-hover" width="100%" cellspacing="0" style="white-space:nowrap;">
									<thead>
										<tr>
											<th>Airwaybill</th>
											<th>Customer</th>
											<th>Pickup Date</th>
											<th>Delivery Date</th>
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
			</section>
			
			<div id="detail-status" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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

			<div id="cancel-airwaybill" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
							<h4 class="modal-title" id="myModalLabel">Cancel Confirmation</h4>
						</div>
						<div class="modal-content">
							<div class="modal-body"></div>
						</div>
					</div>
				</div>
			</div>

			<div id="cancel-airwaybill" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
							<h4 class="modal-title" id="myModalLabel">Cancel Confirmation</h4>
						</div>
						<div class="modal-content">
							<div class="modal-body"></div>
						</div>
					</div>
				</div>
			</div>
			
			<div id="import-penerima" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
							<h4 class="modal-title" id="myModalLabel">Import Penerima</h4>
						</div>
						<div class="modal-content">
							<div class="modal-body"></div>
						</div>
					</div>
				</div>
			</div>
			
			<div id="modal_bayar" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
							<h4 class="modal-title" id="myModalLabel">Bayar Cash</h4>
						</div>
						<div class="modal-content">
							<div class="modal-body">
								';
								//echo "SELECT airwaybill_kode, id_pengiriman FROM pengiriman where id_kustomer= '$r[id_kustomer]' and tanggal BETWEEN (CURRENT_DATE() - INTERVAL 3 MONTH) AND CURRENT_DATE() and (id_invoice is null or id_invoice = 0) ORDER BY id_pengiriman";
														

								echo '
								<form action="'.$aksi.'?module=belum-tertagih&act=bayar" id="frmBayar" method="POST">						
									<div class="form-group" style="padding-bottom: 25px;">
										<label class="col-md-3 control-label">Airwaybill <span class="required">* </span></label>
										<div class="col-md-8">
											<div class="input-group">
												<input type="text" class="form-control" id="awb" readonly>
											</div>
										</div>
									</div>
									<div class="form-group" style="padding-bottom: 25px;">
										<label class="col-md-3 control-label">Tanggal Bayar <span class="required">* </span></label>
										<div class="col-md-8">
											<div class="input-group">
												<div class="input-group-addon">
													<i class="fa fa-calendar"></i>
												</div>
												<input type="text" class="form-control" id="tanggal" name="tanggal" required>
											</div>
										</div>
									</div>
									<div class="form-group" style="padding-bottom: 25px;">
										<label class="col-md-3 control-label">Nilai Rupiah <span class="required">* </span></label>
										<div class="col-md-8">
											<input type="number" id="nilai" name="nilai" value="0" class="form-control" required>
										</div>
									</div>
									<div class="form-group" style="padding-bottom: 25px;">
										<label class="col-md-3 control-label">Diterima Oleh <span class="required">* </span></label>
										<div class="col-md-8">
											<input type="text" name="penerima" id="penerima" value="" class="form-control" required>
										</div>
									</div>
								
									<input type="hidden" name="id_pengiriman" id="id_pengiriman" class="form-control" />
									<input type="hidden" name="id_bayar" id="id_bayar" class="form-control" />
									
									<div class="modal-footer">
										<button class="btn btn-success" type="submit" id="btnSubmitBayar">Submit</button>
										<button type="reset" class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Cancel</button>
									</div>
								</form>
							</div>

						</div>
					</div>
				</div>
			</div>';
			
			echo '
			<style>
			.select2{
				width: 80% !important;
			}
			</style>
			<div class="row">				
				<div class="col-md-12">
					<div class="form-group">
						<label class="col-sm-1 control-label full-right">Customer:</label>
						<div class="col-sm-4 input-group">
							<select name="kustomer" id="kustomer"  class="form-control select2">
							';
							if($u['id_kustomer']> 0){
								$query=mysqli_query($con,'select id_kustomer, nama_kustomer from kustomer where id_kustomer=$u[id_kustomer] order by nama_kustomer');
								echo 'select id_kustomer, nama_kustomer from kustomer where id_kustomer=$u[id_kustomer] order by nama_kustomer';
							}else{
								$query=mysqli_query($con,'select id_kustomer, nama_kustomer from kustomer where id_kustomer not in (79,80,81) order by nama_kustomer');	
							}
							

							while($r=mysqli_fetch_assoc($query)){
								if($r['id_kustomer']==$id_kustomer){
									echo'
									<option value="'.$r['id_kustomer'].'" selected>'.$r['nama_kustomer'].'</option>';
								}else{
									echo'
									<option value="'.$r['id_kustomer'].'">'.$r['nama_kustomer'].'</option>';
								}
							}
							echo '
							</select>
							<button type="button" id="excel" class="btn btn-primary pull-right"><i class="fa fa-file-excel-o"></i> Excel</button>
							
						</div>
					</div>
				</div>
			</div><br>';
			
			break;

			case "outbound":
			
			$key = "asdf$1011";
			$encrypt = "$_GET[id]";
			$id = base64_decrypt($encrypt,$key);

			$query = mysqli_query($con, "select *,SUBSTRING_INDEX(ukuran_barang,'x',1) as length, SUBSTRING_INDEX(SUBSTRING_INDEX(ukuran_barang,'x',2),'x',-1) as width, SUBSTRING_INDEX(ukuran_barang,'x',-1) as height, DATE_FORMAT(created_date,'%Y%m%d') as folder, x_namakotabesar(lokasi_asal) as kotalamaasal, x_namakotabesar(lokasi_tujuan) as kotalamatujuan from pengiriman where id_pengiriman='$id'");
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
					
					<form method="post" action="'.$aksi.'?module=airwaybill&act=update" class="form-horizontal form-bordered" id="form-bookinginv2" autocomplete="off"  enctype="multipart/form-data">
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
						$loccode = mysqli_query($con, 'select loc_code from kustomer where id_kustomer="'.$r['id_kustomer'].'"');
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
						if($r['id_kustomer'] == 13 || $r['id_kustomer'] == 29 || $r['id_kustomer'] == 31){
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
								<input type="text" name="alamat_pengirim1" id="alamat_pengirim1" maxlength="50" value="'.$r['alamat_pengirim'].'"  class="form-control" placeholder="Detail Alamat Pengirim" required>
								<input type="text" name="alamat_pengirim2" id="alamat_pengirim2"  maxlength="50" value="'.$r['alamat_pengirim2'].'" class="form-control" placeholder="Detail Alamat Pengirim Lanjutan">
								<br>
								';
								if ($r['id_kota_asal'] < 1 && $r['service'] != 6){
								echo '
								<input type="text" name="kotaasal_lama" id="kotalamaasal"  maxlength="75" value="'.$r['kotalamaasal'].'" class="form-control" placeholder="Kota Asal" disabled>
								<span style="color:red;">*NOTED JIKA INGIN MENGGANTI KOTA HARUS MENGGUNAKAN FORMAT BARU</span>
								';
								}
								echo '
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
								<input type="text" name="alamat_penerima1" id="alamat_penerima1" maxlength="50" value="'.$r['alamat_penerima'].'"  class="form-control" placeholder="Detail Alamat Penerima" required>
								<input type="text" name="alamat_penerima2" id="alamat_penerima2"  maxlength="50" value="'.$r['alamat_penerima2'].'" class="form-control" placeholder="Detail Alamat Penerima Lanjutan">
								<br>
								
								';
								if ($r['id_kota_tujuan'] < 1 && $r['service'] != 6){
								echo '
								<input type="text" name="kotatujuan_lama" id="kotalamatujuan"  maxlength="75" value="'.$r['kotalamatujuan'].'" class="form-control" placeholder="Kota Tujuan" disabled>
								<span style="color:red;">*NOTED JIKA INGIN MENGGANTI KOTA HARUS MENGGUNAKAN FORMAT BARU</span>
								';
								}
								echo '
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
								<label class="col-md-3 control-label">Estimasi Berat Barang</label>
								<div class="col-md-6">
									<div class="input-group">
										<input type="text" name="berat_barang" class="form-control" value="'. ($r['berat_aktual']>0 ? $r['berat_aktual'] : $r['berat_barang']) .'"> <span class="input-group-addon">Kg</span>
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
								<div class="col-md-6"><input type="number" id="collie" name="jumlah_collie" class="form-control " value="'.$r['collie'].'" min="1" max="250"></div>
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
								<label class="col-md-3 control-label">Jumlah Collie/Pcs</label>
								<div class="col-md-6"><input type="number" id="collie" name="jumlah_collie" class="form-control " value="'.$r['collie'].'" min="1" max="250"></div>
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
										<span class="input-group-addon">Rp</span> <input type="number" value="'.$r['nilai_barang'].'" min="100000" name="nilai_barang" id="nilai_barang" class="form-control">
									</div>
									<div  id="val-asu" style="display:none;"></div>	
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
										<span class="input-group-addon">Rp</span> <input type="number" val="0" min="0" name="nilai_barang" id="nilai_barang" class="form-control">
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
								<td><a href="'.$aksi.'?module=airwaybill&act=delete_catatan&id_catatan='.$id.' ">delete all</a></td>
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
								<td><a href="'.$aksi.'?module=airwaybill&act=delete_catatan&id_catatan='.$id.' ">delete all</a></td>
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
						if ($r['status'] > 1 && $r['terlambat_pickup'] > 0 ){
							echo '
								<div class="form-group">
										<label class="col-md-3 control-label">Terlambat Pickup</label>
										<div class="col-md-6">
											<select name="terlambat_pickup" id="reason" data-placeholder="Choose a Reason..." class="form-control select2" required>		             
				               					<option value="1" '.($r['terlambat_pickup'] == 1 ? 'selected' : '').' >Kesalahan Kurir</option>		            			                					
			                					<option value="2" '.($r['terlambat_pickup'] == 2 ? 'selected' : '').'>Kesalahan Internal Cabang</option>	                					
			                					';
			                					if($u['level'] == 'admin'){
			                						echo '<option value="3" '.($r['terlambat_pickup'] == 3 ? 'selected' : '').'>PERIMINTAAN CUSTOMER</option>';	
			                					}
					        					
					        					echo '
						                	</select>
										</div>
								</div>
								
								';	
						}else{
							echo '<input type="hidden" class="form-control" id="terlambat_pickup" name="terlambat_pickup" value="'.$r['terlambat_pickup'].'" >';
						}
						if($u['level'] == 'admin'){
							if ($r['pickup_date_request'] != "" ){
								echo '<div class="form-group">
										<label class="col-md-3 control-label">Pikcup Reqest Date</label>
										<div class="col-md-6">
											<input type="text" class="form-control" id="pickup_date_request" name="pickup_date_request" value="'.$r['pickup_date_request'].'" >
										</div>
									</div>';
							}
						}else{
							echo '<div class="form-group">
										<label class="col-md-3 control-label">Pikcup Reqest Date</label>
										<div class="col-md-6">
											<input type="hidden" class="form-control" id="pickup_date_request" name="pickup_date_request" value="'.$r['pickup_date_request'].'" >
										</div>
									</div>';
						}

							

						if ($r['status'] == 6){
							echo '
								<div class="form-group">
										<label class="col-md-3 control-label">Nama yang menerima</label>
										<div class="col-md-6">
											<input type="text" name="delivery_name" class="form-control" value="'.$r['delivery_name'].'">
										</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label full-right">Tanggal Diterima<span class="required">* </span></label>
									<div class="col-sm-3">
										<div class="input-group">
											<div class="input-group-addon">
												<i class="fa fa-calendar"></i>
											</div>
											<input type="text" class="form-control" id="tanggal" name="delivery_date" value="'.$r['courier_date'].'" >
										</div>
									</div>
								</div>
								';
									if ($r['terlambat_delivery'] > 0){
										echo 
											'<div class="form-group">
													<label class="col-md-3 control-label">Terlambat Delivery</label>
													<div class="col-md-6">
														<select name="terlambat_delivery" id="reason" data-placeholder="Choose a Customer..." class="form-control select2" required>		             
							               					<option value="1" '.($r['terlambat_delivery'] == 1 ? 'selected' : '').' >Kesalahan Kurir</option>		            			                					
						                					<option value="2" '.($r['terlambat_delivery'] == 2 ? 'selected' : '').'>Kesalahan Internal Cabang</option>	                					
						                					';
						                					if($u['level'] == 'admin'){
						                						echo '<option value="3" '.($r['terlambat_delivery'] == 3 ? 'selected' : '').'>PERIMINTAAN CUSTOMER</option>';	
						                					}
								        					
								        					echo '
									                	</select>
													</div>
											</div>';
										}

								echo '
							';
						}
						echo'

						<hr>
					</div><!-- /.box-body -->
					<div class="box-footer">
						<button type="button" class="btn btn-default" onclick="window.location.href = \'?module=airwaybill\';">Cancel</button>
						
						<button type="submit" class="btn btn-primary pull-right" '.(($u["level"] == "domex" || $u["level"] == "intercity" || $u["level"] == "operator" || $u["level"] == "inbound" || $u["level"] == "network") && $r["status"] == 6 || $r["harga_kunci"] != '' ? 'disabled' : '').($u["level"] == "finance"  && $r["harga_kunci"] != '' ? 'disabled' : '').'>Save</button>
						';

						if (($u["level"] == "domex" || $u["level"] == "intercity" || $u["level"] == "operator" || $u["level"] == "inbound" || $u["level"] == "network") && $r["status"] == 6){
							echo '<div class="text pull-right" style="color:red;"> maaf, awb sudah dikunci Karena Status Sudah Delivered, hubungi admin untuk update data  </div>';
						}
						if ($u["level"] == "finance" && ($r['updated_by_finance'] == 10 || $r["harga_kunci"] != '' )){
							echo '<div class="text pull-right" style="color:red;"> maaf, Status AWB Sudah Dikunci, hubungi admin untuk update data  </div>';
						}						
						echo ' 
						<div > <input type="hidden" value="'.($r["updated_by_operational"] == NULL ? 0 : $r["updated_by_operational"]).'" name="updated_by_operational"> </div>
						<div > <input type="hidden" value="'.$u["level"].'" name="level"> </div>
						<div > <input type="hidden" value="'.($r["updated_by_finance"] == NULL ? 0 : $r["updated_by_finance"]).'" name="updated_by_finance"> </div>
					</div><!-- /.box-footer -->
					</form>
				</div><!-- /.box -->
			</section>';
			
			break;
			
			case "inbound":
			
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
						<h3 class="box-title">UPDATE AWB '.$r['airwaybill_kode'].'</h3>
						<div class="box-tools pull-right">
							<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
							<button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
						</div>
					</div><!-- /.box-header -->
					';

					echo '
					<form method="post" action="'.$aksi.'?module=airwaybill&act=update" class="form-horizontal form-bordered" id="form-bookinginv2" autocomplete="off"  enctype="multipart/form-data">';

					echo '
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
								$result = mysqli_query($con,"select id_kustomer, nama_kustomer from kustomer where status !='N' order by nama_kustomer");
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
						$loccode = mysqli_query($con, 'select loc_code from kustomer where id_kustomer="'.$r['id_kustomer'].'"');
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
						if($r['id_kustomer'] == 13 || $r['id_kustomer'] == 29 || $r['id_kustomer'] == 31){
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
								<input type="text" name="alamat_pengirim1" id="alamat_pengirim1" maxlength="50" value="'.$r['alamat_pengirim'].'"  class="form-control" placeholder="Detail Alamat Pengirim" required>
								<input type="text" name="alamat_pengirim2" id="alamat_pengirim2"  maxlength="50" value="'.$r['alamat_pengirim2'].'" class="form-control" placeholder="Detail Alamat Pengirim Lanjutan">
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
								<input type="text" name="alamat_penerima1" id="alamat_penerima1" maxlength="50" value="'.$r['alamat_penerima'].'"  class="form-control" placeholder="Detail Alamat Penerima" required>
								<input type="text" name="alamat_penerima2" id="alamat_penerima2"  maxlength="50" value="'.$r['alamat_penerima2'].'" class="form-control" placeholder="Detail Alamat Penerima Lanjutan">
							</div>
						</div>
						<hr>
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
								<label class="col-md-3 control-label">Estimasi Berat Barang</label>
								<div class="col-md-6">
									<div class="input-group">
										<input type="text" name="berat_barang" class="form-control" value="'. ($r['berat_aktual']>0 ? $r['berat_aktual'] : $r['berat_barang']) .'"> <span class="input-group-addon">Kg</span>
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
								<div class="col-md-6"><input type="number" id="collie" name="jumlah_collie" class="form-control " value="'.$r['collie'].'" min="1" max="50"></div>
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
								<label class="col-md-3 control-label">Jumlah Collie/Pcs</label>
								<div class="col-md-6"><input type="number" id="collie" name="jumlah_collie" class="form-control " value="'.$r['collie'].'" min="1" max="50"></div>
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
								<td><a href="'.$aksi.'?module=airwaybill&act=delete_catatan&id_catatan='.$id.' ">delete all</a></td>
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
								<td><a href="'.$aksi.'?module=airwaybill&act=delete_catatan&id_catatan='.$id.' ">delete all</a></td>
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
						if ($r['status'] == 6){
							echo '
								<div class="form-group">
										<label class="col-md-3 control-label">Nama yang menerima</label>
										<div class="col-md-6">
											<input type="text" name="delivery_name" class="form-control" value="'.$r['delivery_name'].'">
										</div>
								</div>
							';
						}
						echo'

						<hr>
					</div><!-- /.box-body -->
					<div class="box-footer">
						<button type="button" class="btn btn-default" onclick="window.location.href = \'?module=airwaybill\';">Cancel</button>
						<button type="submit" class="btn btn-primary pull-right" '.(($u["level"] == "domex" || $u["level"] == "intercity" || $u["level"] == "operator") && $r["updated_by_operational"] == 2 ? 'disabled' : '').($u["level"] == "finance"  && $r["updated_by_finance"] == 2 ? 'disabled' : '').'>Save</button>
						';
						if (($u["level"] == "domex" || $u["level"] == "intercity" || $u["level"] == "operator") && $r['status'] == 6){
							echo '<div class="text pull-right" > maaf,Status Sudah Delivered, hubungi admin untuk update data </div>';
						}
						if ($u["level"] == "finance" && $r['harga_kunci'] != ''){
							echo '<div class="text pull-right" > maaf, Harga Sudah di kunci, hubungi admin untuk update data </div>';
						}						
						echo ' 
						<div > <input type="hidden" value="'.($r["updated_by_operational"] == NULL ? 0 : $r["updated_by_operational"]).'" name="updated_by_operational"> </div>
						<div > <input type="hidden" value="'.$u["level"].'" name="level"> </div>
						<div > <input type="hidden" value="'.($r["updated_by_finance"] == NULL ? 0 : $r["updated_by_finance"]).'" name="updated_by_finance"> </div>
					</div><!-- /.box-footer -->
					</form>
				</div><!-- /.box -->
			</section>';
			
			break;

			case "search":
			echo'
			<section class="content">
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">Search</h3>
						
					</div><!-- /.box-header -->
					<div class="box-body">
						<div class="form-group">
							<label class="col-sm-3 control-label full-right">Search By Airwaybill</label>
							<div class="col-sm-6">
								<div class="input-group">
									<input type="text" class="form-control" id="searchawb"  value="" >			
									<button type="button" id="btnsearch" class="btn form-control btn-primary">search</button>
							</div>
						</div>
						<div id="load" style="display:none;"><img src="dist/img/Loading_icon.gif"/ style="display:block; margin: 0 auto; "></div>
												
						<table id="airwaybillsrc" class="table table-bordered table-striped table-hover" width="100%" cellspacing="0" style="white-space:nowrap; margin-top:50px;">
									<thead>
										<tr style="text-transform: uppercase;">
											<th>Airwaybill</th>
											<th width="90px">Booking Time</th>
											<th>Customer</th>
											<th>Origin</th>
											<th>Destination</th>
											<th>Commodity</th>
											<th>Status</th>
											<th>Type</th>											
											<th>Pickup Date</th>
											<th>Pickup By</th>
											<th>Delivery Date</th>
											<th>Delivery By</th>
											<th>Recipient</th>
											<th>Volume</th>
											<th>Actual</th>											
											<th>Print</th>											
										</tr>
									<thead>
									<tbody>
										<tr id="hasil" style="text-transform: uppercase;">
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
										</tr>
									</tbody>
						</table>
						
					</div>
				</div>
				
			</section>			
			';
			break;			

			case "upload-asus":

			
					echo '
					<div class="box box-primary">
						<div class="box-header with-border">
							<h3 class="box-title">Upload Asus</h3>						
						</div><!-- /.box-header -->
						<div class="box-body">
							<form method="post" action="'.$aksi.'?module=airwaybill&act=upload-asus-all" class="form-horizontal form-bordered" id="form-bookingin" autocomplete="off" enctype="multipart/form-data">
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
			
			break;	

			case "edit-kg":

			
					echo '
					<div class="box box-primary">
						<div class="box-header with-border">
							<h3 class="box-title">Upload Edit KG</h3>						
						</div><!-- /.box-header -->
						<div class="box-body">
							<form method="post" action="'.$aksi.'?module=airwaybill&act=update-kg" class="form-horizontal form-bordered" id="form-bookingin" autocomplete="off" enctype="multipart/form-data">
									<div class="form-group">
										<label class="col-md-3 control-label">Upload Data  </label>
										<div class="col-md-6">
											<input type="file" name="uploadkg">
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
			
			break;
			case "edit-service":


					echo '
					<div class="box box-primary">
						<div class="box-header with-border">
							<h3 class="box-title">Upload Edit Service</h3>						
						</div><!-- /.box-header -->
						<div class="box-body">
							<form method="post" action="'.$aksi.'?module=airwaybill&act=update-service" class="form-horizontal form-bordered" id="form-bookingin" autocomplete="off" enctype="multipart/form-data">
									<div class="form-group">
										<label class="col-md-3 control-label">Upload Data  </label>
										<div class="col-md-6">
											<input type="file" name="uploadservice">
											<p class="help-block">Hanya bisa file ber-type .xls</p>
										</div>
									</div>
							
						</div>

						<div class="box-footer">
							<button type="button" class="btn btn-default" onclick="window.location.href = \'?module=airwaybill\';">Cancel</button>
							<button type="submit" id="upload-save" class="btn btn-primary pull-right">Save</button>
							</form>
						</div><!-- /.box-footer -->
					</div>
					';

			break;
		
		}
	}else{
		echo akses_salah();
	}
}
?>

