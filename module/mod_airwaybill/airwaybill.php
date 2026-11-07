<section class="content-header">
	<h1>Airwaybill<small></small></h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-home"></i> Home</a></li>
		<li><a href="#">Application</a></li>
		<li class="active">Airwaybill <?php //echo $u['level'].$u['id_user']; ?></li>
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
	if($u['level']=='admin' || $u['level']=='operator' || $u['level']=='intercity' || $u['level']=='network' || $u['level']=='finance' || $u['level']=='domex' || $u['level'] == 'kurir' || $u['level'] == 'agent' || $u['level'] == 'inbound' || $u['level'] == 'admin_network' || $u['level']=='sales'){

		$aksi	= "module/mod_airwaybill/airwaybill_action.php";
		
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
										&nbsp;
									</div>
									<div class="col-md-6">
										<div class="btn-group pull-right">
											<button class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown">Tools <i class="fa fa-angle-down"></i></button>
											<ul class="dropdown-menu pull-right">
												<li><a href="javascript:;" class="open_import-penerima"><i class="fa fa-download"></i> Import Penerima </a></li>
												<li><a href="main.php?module=airwaybill&act=search" class=""><i class="fa fa-search"></i>Search By Airwaybill</a></li>
												<li><a href="javascript:;" onclick="window.location.href = \'?module=airwaybill&act=upload-asus\';"><i class="fa fa-upload"></i>Upload Detail Barang asus</a></li>
												<li><a href="javascript:;" onclick="window.location.href = \'?module=airwaybill&act=edit-service\';"><i class="fa fa-upload"></i>Upload Edit Service</a></li>
												';
												if ($u['level'] == 'kurir'){
														echo '<li><a target="_blank" href="reports/PHPExcel/download_excel_hidayat.php" class="download_excel"><i class="fa fa-file-excel-o"></i><i class="fa fa-download"></i> Download Excel </a></li>';
													}
												echo'
											</ul>
										</div>										
									</div>
								</div>
							</div>
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
											<th>Terlambat</th>
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
			<div id="edit-pickupdate" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
							<h4 class="modal-title" id="myModalLabel">EDIT PICKUP DATE</h4>
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
			</div>';
			
			break;

			case "outbound":
			
			$key = "asdf$1011";
			$encrypt = "$_GET[id]";
			$id = base64_decrypt($encrypt,$key);

			$query = mysqli_query($con, "select *,SUBSTRING_INDEX(ukuran_barang,'x',1) as length, SUBSTRING_INDEX(SUBSTRING_INDEX(ukuran_barang,'x',2),'x',-1) as width, SUBSTRING_INDEX(ukuran_barang,'x',-1) as height, DATE_FORMAT(created_date,'%Y%m%d') as folder, x_namakotabesar(lokasi_asal) as kotalamaasal, x_namakotabesar(lokasi_tujuan) as kotalamatujuan, x_inter(id_kota_asal) as intercityasal, x_inter(id_kota_tujuan) as intercitytujuan,x_hubnew(id_kota_tujuan) as hub from pengiriman where id_pengiriman='$id'");
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
										<label><input class="seafreight" type="radio" name="service" value="'.$s['id_pelayanan'].'" checked/> '.$s['nama_pelayanan'].' </label><br>';
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
						<input type="hidden" name="serviceshow" id="serviceshow" value="'.$r['service'].'">
						<input type="hidden" name="intercityasal" id="intercityasal" value="'.$r['intercityasal'].'">
									
						<input type="hidden" name="intercitytujuan" id="intercitytujuan" value="'.$r['intercitytujuan'].'">
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
							';
							
							$printLabel = isset($r['print_label']) ? (int) $r['print_label'] : 0;
							$isGenuineOutbound = (
								isset($r['intercityasal']) && isset($r['intercitytujuan'])
								&& $r['intercityasal'] === 'Y'
								&& $r['intercitytujuan'] !== 'Y'
							);
							if ($isGenuineOutbound && $printLabel <= 0) {
								$disabled_printlabel = 'readonly';
							$print_labelmessage = '<span style="color:red;">Harap Print label dulu baru bisa edit</span>';
							} else {
								$disabled_printlabel = '';
								$print_labelmessage = '';
								 
							}
							echo '
							<div class="form-group">
								<label class="col-md-3 control-label">Jumlah Collie/Pcs</label>'.$print_labelmessage.'
								<div class="col-md-6"><input type="number" id="collie" onchange="hitung1();" '.$disabled_printlabel.' name="jumlah_collie" class="form-control " value="'.$r['collie'].'" min="1" max="80" ></div>
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
								if($u['level'] == 'admin' && $r['status'] < 6){
									echo '<a class="btn btn-warning" href="'.$aksi.'?module=airwaybill&act=simple&id='.$r['id_pengiriman'].'"> Gunakan simple Koli</a>';
								}
								echo $print_labelmessage;
								$i=1;
								while($row = mysqli_fetch_assoc($q)){
									echo '						
									<div class="form-group"  id="detail_collie'.$i.'">
										<label class="col-md-2 control-label">Detail Collie '.$i.'</label>
										<div class="col-md-10">
											<div class="row">
													<div class="col-md-2">
														<div class="input-group">
															<input type="number" '.$disabled_printlabel.' onchange="hitung'.$i.'();" id="weight'.$i.'" min="1" value="'.$row['berat'].'" name="cweight['.$i.']" placeholder="berat..." class="form-control" max="450"> <span class="input-group-addon">kg</span>
														</div>
													</div>
													<div class="col-md-2">
														<div class="input-group">
															<input type="number" onchange="hitung'.$i.'();" id="width'.$i.'" min="1" name="cwidth['.$i.']" placeholder="Width..." class="form-control" max="450" value="'.$row['p'].'"> <span class="input-group-addon">cm</span>
														</div>
													</div>
													<div class="col-md-2">
														<div class="input-group">
															<input type="number" onchange="hitung'.$i.'();" id="length'.$i.'" min="1" value="'.$row['l'].'" name="clength['.$i.']" placeholder="Length..." class="form-control" max="450"> <span class="input-group-addon">cm</span>
														</div>
													</div>
													
													<div class="col-md-2">
														<div class="input-group">
															<input type="number" onchange="hitung'.$i.'();" id="height'.$i.'" min="1" name="cheight['.$i.']" placeholder="Height..." class="form-control" max="450" value="'.$row['t'].'"> <span class="input-group-addon">cm</span>
														</div>
													</div>
													<div class="col-md-4">
														<div class="input-group">
															<input type="number" readonly id="total'.$i.'" name="ctotal['.$i.']" min="1" placeholder="total..." class="form-control" max="450" value="'.$row['berat_real'].'" onchange="total'.$i.'();"> <span class="input-group-addon">kg</span>
														</div>
													</div>
												
											</div>	

										</div>
									</div>';
									$i=$i+1;
								}
								$mulai=$ketemu+1;
								for ($i = $mulai; $i < 81; $i++) {
									echo '						
									<div class="form-group" style="display:none;" id="detail_collie'.$i.'">
										<label class="col-md-2 control-label">Detail Collie '.$i.'</label>
										<div class="col-md-10">
											<div class="row">
													<div class="col-md-2">
														<div class="input-group">
															<input type="number" onchange="hitung'.$i.'();" id="weight'.$i.'" min="1" value="1" name="cweight['.$i.']" placeholder="berat..." class="form-control" max="450"> <span class="input-group-addon">kg</span>
														</div>
													</div>
													<div class="col-md-2">
														<div class="input-group">
															<input type="number" onchange="hitung'.$i.'();" id="width'.$i.'" min="1" name="cwidth['.$i.']" placeholder="Width..." class="form-control" max="450" value="1"> <span class="input-group-addon">cm</span>
														</div>
													</div>
													<div class="col-md-2">
														<div class="input-group">
															<input type="number" onchange="hitung'.$i.'();" id="length'.$i.'" min="1" value="1" name="clength['.$i.']" placeholder="Length..." class="form-control" max="450"> <span class="input-group-addon">cm</span>
														</div>
													</div>
													
													<div class="col-md-2">
														<div class="input-group">
															<input type="number" onchange="hitung'.$i.'();" id="height'.$i.'" min="1" name="cheight['.$i.']" placeholder="Height..." class="form-control" max="450" value="1"> <span class="input-group-addon">cm</span>
														</div>
													</div>
													<div class="col-md-4">
														<div class="input-group">
															<input type="number" readonly id="total'.$i.'" name="ctotal['.$i.']" min="1" placeholder="total..." class="form-control" max="450" value="1" onchange="total'.$i.'();"> <span class="input-group-addon">kg</span>
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
											<input type="text" name="berat_barang" id="berat_barang" class="form-control" value="'. ($r['berat_aktual']>0 ? $r['berat_aktual'] : $r['berat_barang']) .'" '.($ketemu > 0 ? 'readonly' : '').'> <span class="input-group-addon">Kg</span>
										</div>
									</div>
							</div>
							';
								if($ketemu < 1){
									echo '
									<div class="row">
										<div class="col-md-9">
											
											<button   onclick="window.location.href = \'module/mod_airwaybill/airwaybill_action.php?module=airwaybill&act=fixing_collie&id='.base64_encrypt($id,$key).'\';"  class="btn btn-primary pull-right" id="fixing-detail-collie" >fixing</button>

											
											
										</div>
									</div>

									';
								}
							echo '

							<div class="form-group">
								<label class="col-md-3 control-label">Detail Catatan Barang <small class="required">*</small></label>
								<div class="col-md-6">
									<div class="radio radio-info">
										<label><input type="radio" value="Y" name="detail_barang" '.($r['jumlah_detail_barang'] > 0 ? 'checked' : '').' id="yesdetailbarang">Ya </label><br>
										<label><input type="radio" value="N" name="detail_barang" '.($r['jumlah_detail_barang'] > 0 ? '' : 'checked').' id="nodetailbarang">Tidak </label>
									</div>
									
								</div>
							</div>

							<div id="ifdetailbarang" '; if($r['jumlah_detail_barang'] == 0 || $r['jumlah_detail_barang'] === null){ echo 'style="display:none"';} echo '>
								<div class="form-group" style="display:none">
									<label class="col-md-3 control-label">Jumlah Detail Barang</label>
									<div class="col-md-6"><input type="number" id="jumlah_detailbarang" name="jumlah_detailbarang" readonly class="form-control " value="'.($r['jumlah_detail_barang'] > 0 ? $r['jumlah_detail_barang'] : 1).'" min="1" max="40"></div>
								</div>

								';

								echo '				
										<div class="form-group"  ">
											
											<label class="col-md-3 control-label">Koli ke</label>
											<label class="col-md-2 control-label">Detail isi catatan barang</label>
										</div>';
									$max_collie = $r['collie'];

								$q1 = mysqli_query($con, "select * from detail_catatan_barang where id_pengiriman='".$id."'  order by id_pengiriman asc");
								
								$ketemu1 = mysqli_num_rows($q1);
							if($r['jumlah_detail_barang'] and $ketemu1 > 0 ){	
								
									
									$i=1;
								while($r1 = mysqli_fetch_assoc($q1)){
									echo '
										<div class="form-group" id="barisdetail_barang'.$i.'">
											<label class="col-md-2 control-label">Detail Catatan Barang '.$i.'</label>
											<div class="col-md-8">
												<div class="row">
														
														<div class="col-md-2">
															
																<input type="number" id="koli_ke'.$i.'"  min="1" name="d_koli['.$i.']" placeholder="koli ke" value="'.$r1[koli_ke].'" class="form-control d_koli_ke" max="'.$max_collie.'"   onkeyup="if(this.value > this.max) this.value = null;"> 
															
														</div>
														<div class="col-md-10">
															
																<input type="text" id="detail_catatan_barang'.$i.$r[collie].'"   value="'.$r1[detail_catatan].'" maxlength="130" name="d_barang['.$i.']" placeholder="detail catatan..." class="form-control"> 
															
														</div>
														
													
												</div>	

											</div>
										</div>';
										$i = $i+1;
								}
							}else{
								echo '				
									
									<div class="form-group"  id="barisdetail_barang'.$i.'">
										<label class="col-md-2 control-label">Detail Catatan Barang 1</label>
										<div class="col-md-8">
											<div class="row">
													
													<div class="col-md-2">
														
															<input type="number" id="koli_ke1"  min="1" name="d_koli[1]" placeholder="koli ke" value="1" max="'.$max_collie.'" class="form-control d_koli_ke"  value="1" onkeyup="if(this.value > this.max) this.value = null;"> 
														
													</div>
													<div class="col-md-10">
														
															<input type="text" id="detail_catatan_barang1"   value="" maxlength="130" name="d_barang[1]" placeholder="detail catatan..." class="form-control"> 
														
													</div>
													
												
											</div>	

										</div>
									</div>';
							}

								if($r['jumlah_detail_barang'] > 0){
									$awal_mulai = $r['jumlah_detail_barang']+1;
								}else{
									$awal_mulai = 2;
								}
								for ($i = $awal_mulai; $i < 76; $i++) {	

								echo '						
									<div class="form-group" style="display:none;" id="barisdetail_barang'.$i.'">
										<label class="col-md-2 control-label">Detail Catatan Barang '.$i.'</label>
										<div class="col-md-8">
											<div class="row">
													<div class="col-md-2">
														
															<input type="number" id="koli_ke'.$i.'" min="1"  name="d_koli['.$i.']" placeholder="koli ke..." class="form-control d_koli_ke"  value="1" max="'.$max_collie.'" onkeyup="if(this.value > this.max) this.value = null;">
														
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
											<input type="text" required name="catatanbaru['.$i.']" id="catatanbaru'.$i.'" value="'.$row['catatan'].'" placeholder="Catatan kiriman '.$i.'" '.($i == 1 ? 'required' : '').' class="form-control" maxlength="275">';
											$i=$i+1;
										}
										$m= $ketemu+1;

										for($i=$m;$i<76;$i++){
											echo '
											<input type="text" style="display:none;" disabled name="catatanbaru['.$i.']" id="catatanbaru'.$i.'" placeholder="Catatan kiriman '.$i.'" class="form-control" maxlength="275">';
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
								<input type="text" name="catatan1" id="catatan1" value="'.$r['catatan1'].'" placeholder="Catatan khusus kiriman 1" class="form-control" maxlength="275" required>
								
								<input type="text" style="display:'.($r['collie']>1 ? '' : 'none' ).';" name="catatan2" value="'.$r['catatan2'].'" id="catatan2" placeholder="Catatan khusus kiriman 2" class="form-control" maxlength="275">
								
								<input type="text" style="display:'.($r['collie']>2 ? '' : 'none' ).';" name="catatan3" value="'.$r['catatan3'].'" id="catatan3" placeholder="Catatan khusus kiriman 3" class="form-control" maxlength="275">
								
								<input type="text" style="display:'.($r['collie']>3 ? '' : 'none' ).';" name="catatan4" value="'.$r['catatan4'].'" id="catatan4" placeholder="Catatan khusus kiriman 4" class="form-control" maxlength="275">
								<input type="text" style="display:'.($r['collie']>4 ? '' : 'none' ).';" name="catatan5" value="'.$r['catatan5'].'" id="catatan5" placeholder="Catatan khusus kiriman 5" class="form-control" maxlength="275">
								<input type="text" style="display:'.($r['collie']>5 ? '' : 'none' ).';" name="catatan6" value="'.$r['catatan6'].'" id="catatan6" placeholder="Catatan khusus kiriman 6" class="form-control" maxlength="275">
								<input type="text" style="display:'.($r['collie']>6 ? '' : 'none' ).';" name="catatan7" value="'.$r['catatan7'].'" id="catatan7" placeholder="Catatan khusus kiriman 7" class="form-control" maxlength="275">
								<input type="text" style="display:'.($r['collie']>7 ? '' : 'none' ).';" name="catatan8" value="'.$r['catatan8'].'" id="catatan8" placeholder="Catatan khusus kiriman 8" class="form-control" maxlength="275">
								<input type="text" style="display:'.($r['collie']>8 ? '' : 'none' ).';" name="catatan9" value="'.$r['catatan9'].'" id="catatan9" placeholder="Catatan khusus kiriman 9" class="form-control" maxlength="275">
								<input type="text" style="display:'.($r['collie']>9 ? '' : 'none' ).';" name="catatan10" value="'.$r['catatan10'].'" id="catatan10" placeholder="Catatan khusus kiriman 10" class="form-control" maxlength="275">

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
						$sql = mysqli_query($con, "select id_catatan,id_pengiriman,product,aprc,part_n,part_n_desc,rma_no,qty,remark from asus_detail_catatan  where id_pengiriman='".$id."'");

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
							        <th>remark</th>
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
								        <td> 

								        <a href="#" class="edit-remark" data-type="text" data-pk="'.$row['id_catatan'].'"> '.$row['remark'].'<i class="fa fa-edit"> </i></a>

								        </td>
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
				               					<option value="1" '.($r['terlambat_pickup'] == 3 ? 'selected' : '').' >Kesalahan Kurir</option>		            			                					
			                					<option value="2" '.($r['terlambat_pickup'] == 2 ? 'selected' : '').'>Kesalahan Internal Cabang</option>	                					
			                					';
			                					if($u['level'] == 'admin'){
			                						echo '<option value="3" '.($r['terlambat_pickup'] == 1 ? 'selected' : '').'>PERMINTAAN CUSTOMER</option>';	
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
										<label class="col-md-3 control-label">Pikcup Request Date</label>
										<div class="col-md-6">
											<input type="text" class="form-control" id="pickup_date_request" name="pickup_date_request" value="'.$r['pickup_date_request'].'" >
										</div>
									</div>';
							}
						}else{
							echo '<div class="form-group">
										<label class="col-md-3 control-label">Pikcup Reqest Date</label>
										<div class="col-md-6">
											<input type="text" readonly class="form-control" id="pickup_date_request" name="pickup_date_request" value="'.$r['pickup_date_request'].'" >
										</div>
									</div>';

							
						}
						echo '<div class="form-group">
										<label class="col-md-3 control-label">Tanggal sampai di kota tujuan</label>
										<div class="col-md-6">
											<input type="text" class="form-control" id="manifest_received" name="manifest_received" '.($r['status'] >= 4 ? '' : 'readonly').' value="'.$r['manifest_received'].'" >
										</div>
									</div>';
							/*if($r['intercitytujuan'] != 'Y' and $r['hub'] != $r['id_kota_tujuan']){
								$disable_admin = ($r['status'] == 6 && in_array(trim($u['level']), ['admin', 'network', 'admin_network'])) ? '' : 'readonly';
								$tes = 'atas';
							}else{
								$disable_admin = ($r['status'] == 6 && ($u['level'] == 'admin') ? '' : 'readonly');
								$tes = 'bawah';
							}*/
							if($r['intercitytujuan'] != 'Y' and $r['hub'] != $r['id_kota_tujuan']){
								$disable_admin = ($r['status'] == 6 && ($u['level'] == 'admin' || $u['level'] == 'network' || $u['level'] == 'admin_network') ? '' : 'readonly');
							}else{
								$disable_admin = ($r['status'] == 6 && ($u['level'] == 'admin') ? '' : 'readonly');
							}
							

					
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

											';
											//disable admin lagi ya balikin
											if ($r['status'] == 6 ){
												echo '<input type="text" class="form-control" id="tanggal" '.$disable_admin.' name="delivery_date" value="'.$r['courier_date'].'" >';
												echo '
												<select name="delivery_by" class="form-control" width="100%"  '.$disable_admin.' required>';
														$result = mysqli_query($con,"select id_user,namalengkap from user where blokir !='Y' and id_cabang='$r[delivery_by_agent]' and (level = 'agent' or level = 'kurir' or level = 'kurir_agent' or level = 'driver') order by namalengkap");

															echo '<option value="">Select...</option>';
															while ($row = mysqli_fetch_assoc($result)){
																if($r['courier_by']==$row['id_user']){
																	echo '<option value="' . $row['id_user'] . '" selected>' . $row['namalengkap'] . '</option>';
																}else{
																	echo '<option value="' . $row['id_user'] . '">' . $row['namalengkap'] . '</option>';
																}
															}
																													
													echo '
													</select>';
											}else{
												$courier_date_str = ($r['courier_date'] === null || $r['courier_date'] === '') ? date('Y-m-d') : $r['courier_date'];
												echo 

												'
													<select name="delivery_date" class="form-control" width="100%" required '.$disable_admin.'>
														<option value="">- SELECT -</option>					
														<option value="'.$courier_date_str.'" selected>'.$courier_date_str.'</option>
														<option value="'. date('Y-m-d', strtotime('-1 day', strtotime($courier_date_str))).'">'.date('Y-m-d', strtotime('-1 day', strtotime($courier_date_str))).' </option>
														<option value="'. date('Y-m-d', strtotime('-1 day', strtotime($courier_date_str))).'">'.date('Y-m-d', strtotime('-2 day', strtotime($courier_date_str))).' </option>
																													

													</select>
													<input type="hidden" class="form-control" id="delivery_by" name="delivery_by" value="'.$r['courier_by'].'">
												';
											}
											echo '
										</div>
									</div>
								</div>
								';
								//	if ($r['status'] == 6 ){
									if($r['terlambat_delivery'] == 1){
										$detail_terlambat = 'Permintaan Customer';
									}elseif($r['terlambat_delivery'] == 2){
										$detail_terlambat = 'Kesalahan Internal Cabang';
									}else{
										$detail_terlambat = 'Kesalahan Kurir';
									}
									

									if ($r['terlambat_delivery'] > 0){
										if($u['level'] != 'admin'){
											echo '
											
												
												<div class="form-group">
														<label class="col-md-3 control-label">Terlambat Delivery</label>
														<div class="col-md-6">
															<input type="text" name="detail_terlambat" class="form-control" value="'.$detail_terlambat.', '.$r['alasan_terlambat_delivery']. '" disabled>
														</div>
												</div>
												<input type="hidden" name="terlambat_delivery" class="form-control" value="'.$r['terlambat_delivery'].' diskon">
											';


										}else{
											echo 
												'<div class="form-group">
														<label class="col-md-3 control-label">Terlambat Delivery</label>
														<div class="col-md-6">
															<select name="terlambat_delivery" id="reason" data-placeholder="Choose a Customer..." class="form-control " required '.($u['level'] == 'admin' ? '' : 'readonly').'>		             
								               					<option value="1" '.($r['terlambat_delivery'] == 3 ? 'selected' : '').' >Kesalahan Kurir</option>		            			                					
							                					<option value="2" '.($r['terlambat_delivery'] == 2 ? 'selected' : '').'>Kesalahan Internal Cabang</option>	                					
							                					';
							                					//if($u['level'] == 'admin'){
							                						echo '<option value="3" '.($r['terlambat_delivery'] == 1 ? 'selected' : '').'>PERMINTAAN CUSTOMER</option>';	
							                					//}
									        					
									        					echo '
										                	</select>
														</div>
												</div>';
										}
									}

								echo '
							';
						//}
						echo'

						<hr>
					</div><!-- /.box-body -->
					<div class="box-footer">
						<button type="button" class="btn btn-default" onclick="window.location.href = \'?module=airwaybill\';">Cancel</button>
						
						<button type="submit" class="btn btn-primary pull-right" '.(($u["level"] == "domex" || $u["level"] == "intercity" || $u["level"] == "operator" || $u["level"] == "inbound" || $u["level"] == "network") && $r["id_invoice"] != '' || $r["harga_kunci"] != '' ? 'disabled' : '').($u["level"] == "finance"  && $r["harga_kunci"] != '' ? 'disabled' : '').'>Save</button>
						';

						if (($u["level"] == "domex" || $u["level"] == "intercity" || $u["level"] == "operator" || $u["level"] == "inbound" || $u["level"] == "network") && $r["id_invoice"] != ''){
							echo '<div class="text pull-right" style="color:red;"> maaf, awb sudah dikunci Karena Status Sudah Masuk Invoice Finance, hubungi admin untuk update data  </div>';
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
						<input type="hidden" name="serviceshow" id="serviceshow" value="'.$r['service'].'">
						<input type="hidden" name="intercityasal" id="intercityasal" value="'.$r['intercityasal'].'">
									
						<input type="hidden" name="intercitytujuan" id="intercitytujuan" value="'.$r['intercitytujuan'].'">


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
								<input type="text" name="catatan1" id="catatan1" value="'.$r['catatan1'].'" placeholder="Catatan khusus kiriman 1" class="form-control" maxlength="275" required>
								
								<input type="text" style="display:'.($r['collie']>1 ? '' : 'none' ).';" name="catatan2" value="'.$r['catatan2'].'" id="catatan2" placeholder="Catatan khusus kiriman 2" class="form-control" maxlength="275">
								
								<input type="text" style="display:'.($r['collie']>2 ? '' : 'none' ).';" name="catatan3" value="'.$r['catatan3'].'" id="catatan3" placeholder="Catatan khusus kiriman 3" class="form-control" maxlength="275">
								
								<input type="text" style="display:'.($r['collie']>3 ? '' : 'none' ).';" name="catatan4" value="'.$r['catatan4'].'" id="catatan4" placeholder="Catatan khusus kiriman 4" class="form-control" maxlength="275">
								<input type="text" style="display:'.($r['collie']>4 ? '' : 'none' ).';" name="catatan5" value="'.$r['catatan5'].'" id="catatan5" placeholder="Catatan khusus kiriman 5" class="form-control" maxlength="275">
								<input type="text" style="display:'.($r['collie']>5 ? '' : 'none' ).';" name="catatan6" value="'.$r['catatan6'].'" id="catatan6" placeholder="Catatan khusus kiriman 6" class="form-control" maxlength="275">
								<input type="text" style="display:'.($r['collie']>6 ? '' : 'none' ).';" name="catatan7" value="'.$r['catatan7'].'" id="catatan7" placeholder="Catatan khusus kiriman 7" class="form-control" maxlength="275">
								<input type="text" style="display:'.($r['collie']>7 ? '' : 'none' ).';" name="catatan8" value="'.$r['catatan8'].'" id="catatan8" placeholder="Catatan khusus kiriman 8" class="form-control" maxlength="275">
								<input type="text" style="display:'.($r['collie']>8 ? '' : 'none' ).';" name="catatan9" value="'.$r['catatan9'].'" id="catatan9" placeholder="Catatan khusus kiriman 9" class="form-control" maxlength="275">
								<input type="text" style="display:'.($r['collie']>9 ? '' : 'none' ).';" name="catatan10" value="'.$r['catatan10'].'" id="catatan10" placeholder="Catatan khusus kiriman 10" class="form-control" maxlength="275">

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
						<button type="submit" class="btn btn-primary pull-right" '.(($u["level"] == "domex" || $u["level"] == "intercity" || $u["level"] == "operator") && $r["updated_by_operational"] == 3 ? 'disabled' : '').($u["level"] == "finance"  && $r["updated_by_finance"] == 2 ? 'disabled' : '').'>Save</button>
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
			case "report-pod":

			?>

			<section class="content-header">
				<h1>Airwaybill by Customer<small>report</small></h1>
				<ol class="breadcrumb">
					<li><a href="#"><i class="fa fa-home"></i> Home</a></li>
					<li><a href="#">Reports</a></li>
					<li class="active">Airwaybill by Customer</li>
				</ol>
			</section>
			<?php
				if($u['id_user'] == 17 || $u['id_user'] == 81) {
					echo '<section class="content">';
				}
				else {
					echo '<section class="content" oncopy="return false" oncut="return false" onpaste="return false">';
				}

			?>

				<?php
				if ($_SERVER["REQUEST_METHOD"] == "POST") {
					$tanggal = $_POST['tanggal'];
					$id_kustomer = $_POST['id_kustomer'];

					$id_kustomer = mysqli_real_escape_string($con, $_POST['id_kustomer']);
									$tanggal = mysqli_real_escape_string($con, $_POST['tanggal']);
									$dr = strleft($tanggal, 10);
									$sm = strright($tanggal, 10);
									$dari = jin_date_sql($dr);
									$sampai = jin_date_sql($sm);
				?>
				<form class="form-horizontal" method="post" action="" autocomplete="off">
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">Airwaybill by Customer</h3>
						<div class="box-tools pull-right">
							<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
							<button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
						</div>
					</div><!-- /.box-header -->
					<div class="box-body">
						<div class="row">
							<div class="col-md-5">
								<div class="form-group">
									<label class="col-sm-4 control-label full-right">Date Range</label>
									<div class="col-sm-8">
										<div class="input-group">
											<div class="input-group-addon">
												<i class="fa fa-calendar"></i>
											</div>
											<input type="text" class="form-control" id="daterange" name="tanggal" value="<?php echo''.$tanggal.'';?>">
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-5">
								<div class="form-group">
									<label class="col-sm-4 control-label full-right">Customer</label>
									<div class="col-sm-8">
										<select name="id_kustomer" class="form-control select2">
										<?php
										if($u['id_kustomer']> 0){
											$query=mysqli_query($con,'select id_kustomer, nama_kustomer from kustomer where id_kustomer=$u[id_kustomer] order by nama_kustomer');
											echo 'select id_kustomer, nama_kustomer from kustomer where id_kustomer=$u[id_kustomer] order by nama_kustomer';
										}else{
											$query=mysqli_query($con,'select id_kustomer, nama_kustomer from kustomer order by nama_kustomer');	
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
										?>
										</select>
										</select>
									</div>
								</div>
							</div>
						</div><!-- /.row -->
					</div><!-- /.box-body -->
			        <div class="box-footer">
						<button type="submit" class="btn btn-primary pull-right"> <i class="fa fa-search"></i> View</button>
						<?php 
							if($u['id_user'] == 83 || $u['id_user'] == 3 || $u['id_user'] == 199 || $u['id_user'] == 12 || $u['id_user'] == 4 ){
						 ?>
							<a  readonly target="_blank" href="<?php echo "reports/PHPExcel/excel_report_percustomer.php?id_kustomer=".$_POST['id_kustomer']."&dari=$dari&sampai=$sampai"?> "><button type="button" class="btn btn-primary pull-right" ><i class="fa fa-file-excel-o"></i> Excel</button></a>
						<?php
						}
						?>

					</div>
				</div><!-- /.box -->
				</form>

				<div class="row">
					<div class="col-md-12">
						<div class="box box-primary">
			                <div class="box-header">
								<div class="row">
								</div>
							</div>
							<div class="box-body">
								<table id="report" class="table table-striped table-bordered table-hover " width="100%" cellspacing="0" style="white-space:nowrap;">
									<thead>
										<tr style="text-transform: uppercase;">
												<th colspan="4"><center>Data</center></th>		
												<th colspan="6"><center>Pengirim</center></th>
												<th colspan="6"><center>Penerima</center></th>												
												<th colspan="2">Berat</th>
												<th rowspan="2">Packing</th>
												<th rowspan="2">Asuransi</th>
												<th rowspan="2">Invoice</th>
												<th rowspan="2" width="150px"><center>Action#</center></th>									
											</tr>
											<tr style="text-transform: uppercase;">
												<th>Pick Up</th>
												<th>Airwaybill</th>
												<th>Komoditi</th>
												<th>Service Type</th>
												<th>Requestor</th>
												<th>Loc Code</th>
												<th>Pengirim</th>
												<th>Nama Perusahaan</th>
												<th>Kota Asal</th>
												<th width="800px;">Alamat Lengkap Asal</th>
												<th>Nama Perusahaan</th>
												<th>consignee</th>
												<th>kota tujuan</th>
												<th>alamat lengkap penerima</th>
												<th>nama penerima</th>
												<th>Tanggal</th>																		
												<th>Berat | VOL</th>
												<th>Berat</th>
											</tr>
									</thead>
									<tbody>
									<?php

									
									$cari = "select 
											id_pengiriman,
											date(pickup_date) as tanggal,
											airwaybill_kode,
											case
												when a.komoditi = '1' then 'Paket'
												else 'Dokumen'
											end as komoditi,
											x_service(service) as service,
											a.service as id_pelayanan,
											nama_pengirim,
											a.loc_code,
											pid,
											x_namakustomer(a.id_kustomer) as namakustomer,
											nama_penerima,
											case
												when inbound = 'Y' then 'INBOUND'
												when inbound = 'N' then 'OUTBOUND'
												else ''
											end as inbound,
											perusahaan_pengirim,
											lokasi_asal,
											if(id_kota_asal > 0,
												kota_asal,
												x_namakotabesar(lokasi_asal)) as lokasi_asal,
											alamat_pengirim,
											perusahaan_penerima,
											lokasi_tujuan,
											if(id_kota_tujuan > 0,
												kota_tujuan,
												x_namakotabesar(lokasi_tujuan)) as lokasi_tujuan,
											jenis_barang,
											alamat_penerima,
											delivery_name,
											a.status,
											delivery_date,
											a.id_invoice,
											CASE
												WHEN airwaybill_kode LIKE '001-%' THEN 0
												WHEN airwaybill_kode LIKE '002-%' THEN 1
											end as intercity,
											SUBSTRING_INDEX(ukuran_barang, 'x', 1) as length,
											SUBSTRING_INDEX(SUBSTRING_INDEX(ukuran_barang, 'x', 2),
													'x',
													- 1) as width,
											SUBSTRING_INDEX(ukuran_barang, 'x', - 1) as height,
											if((select intercity) = 1,
												(select length) * (select width) * (select height) / 4000,
												if(service = 5,
													(select length) * (select width) * (select height) / 4000,
													(select length) * (select width) * (select height) / 6000)) as volume,
											if(berat_aktual is null or berat_aktual < 1,
												berat_barang,
												berat_aktual) as berat_aktual,
											case
												when a.packing_kayu = '1' then 'Packing Oleh Pemilik'
												when a.packing_kayu = '2' then 'Packing Kayu Oleh Cesco Log.'
												when a.packing_kayu = '3' then 'Non Packing Kayu Oleh Cesco Log.'
											end as packing_kayu,
											asuransi,
											i.status as status_invoice
										FROM
											pengiriman a
												left outer JOIN
											invoice i ON i.id_invoice = a.id_invoice
										where a.id_kustomer='$id_kustomer' 
											and a.tanggal between '$dari' 
											and '$sampai'";
									
									
									$sql = mysqli_query($con, $cari);
									while($row = mysqli_fetch_assoc($sql)){
										$key = "asdf$1011";
										$stringawal = $row["id_pengiriman"];
										$id = base64_encrypt($stringawal,$key);
										$volume = ($row['length']*$row['width']*$row['height'])/6000;
										if($row['lokasi_asal']=='Jakarta Selatan' || $row['lokasi_asal']=='Jakarta Timur' || $row['lokasi_asal']=='Jakarta Barat' ||		$row['lokasi_asal']=='Jakarta Utara' || $row['lokasi_asal']=='Jakarta Pusat'){
											$lokasi_asal = 'Jakarta';
										}else{
											$lokasi_asal = $row['lokasi_asal'];
										}
						
										if($row['lokasi_tujuan']=='Jakarta Selatan' || $row['lokasi_tujuan']=='Jakarta Timur' || $row['lokasi_tujuan']=='Jakarta Barat' ||		$row['lokasi_tujuan']=='Jakarta Utara' || $row['lokasi_tujuan']=='Jakarta Pusat'){
											$lokasi_tujuan = 'Jakarta';
										}else{
											$lokasi_tujuan = $row['lokasi_tujuan'];
										}
										
										$sttsInv = ($row['status_invoice'] == 'final') ? 'Y' : 'N';
										
										echo'
										<tr style="text-transform: uppercase;">
											<td width="20px">'.$row['tanggal'].'</td>
											<td width="20px">'.$row['airwaybill_kode'].'</td>
											<td width="20px">'.strtoupper($row['komoditi']).', '.$row['jenis_barang'].'</td>
											<td width="50px">'.strtoupper($row['service']).'</td>
											<td width="20px">'.strtoupper($row['namakustomer']).'</td>
											<td width="20px">'.strtoupper($row['loc_code']).($row['pid'] != '' ? ', PID = '. $row['pid'] : '').'</td>
											<td width="20px">'.strtoupper($row['nama_pengirim']).'</td>
											<td width="20px">'.strtoupper($row['perusahaan_pengirim']).'</td>
											<td width="20px">'.strtoupper($lokasi_asal).'</td>
											<td width="20px">'.strtoupper($row['alamat_pengirim']).'</td>
											
											<td width="20px">'.strtoupper($row['perusahaan_penerima']).'</td>
											<td width="20px">'.strtoupper($row['nama_penerima']).'</td>										
											<td width="20px">'.strtoupper($lokasi_tujuan).'</td>								
											<td width="20px">'.strtoupper($row['alamat_penerima']).'</td>
											<td width="20px">'.($row['status'] == 7 ? '<button class="btn btn-block btn-danger btn-xs">Cancel</button>' : strtoupper($row['delivery_name']))  .'</td>
											<td width="20px">'.$row['delivery_date'].'</td>
											<td width="20px">';

											 $j=0;
											 $tb = 0;
											 
											 $q = mysqli_query($con, "select * from detail_collie where id_pengiriman='".$row['id_pengiriman']."' order by koli_ke asc ");
											 $ketemu = mysqli_num_rows($q);

											 if($ketemu > 0){
											 	while($roww = mysqli_fetch_assoc($q)){
						 //for($i=1;$i<41;$i++){

												 	if($row['intercity'] == 1){
									              			$beratvol = $roww['p']*$roww['l']*$roww['t']/4000;
									              	}
									             	else{
									           			if($row['id_pelayanan'] == 5){
									           				$beratvol = $roww['p']*$roww['l']*$roww['t']/4000;
									           			}
									              		else{
									              			$beratvol = $roww['p']*$roww['l']*$roww['t']/6000;
									              		}
									              	}

												 	echo '
												 		
														 	
														 	'.$roww['berat'].' KG | 
														 	 '.$roww['p'].' x '.$roww['l'].' x '.$roww['t'].' ( '.number_format($beratvol,2).' )'.'<br>
														 	
														 
												 	';
												 	$j=$j+1;
												 	//$tb=$tb+$roww['berat_real'];
												 }
												
											}else{
												echo ($row['length'] == '' ? 0 : $row['length']).'x'.($row['width'] == '' ? 0 : $row['width']).'x'.($row['height'] == '' ? 0 : $row['height']).'('. number_format($row['volume'],2).' KG )';
											}

											echo 
											'</td>
											<td width="20px">'.$row['berat_aktual'].' KG</td>
											<td>'.strtoupper($row['packing_kayu']).'</td>
											<td>'.$row['asuransi'].'</td>								
											<td>'.$sttsInv.'</td>								
											<td>
												<center>	
											';
											if ($row['id_invoice'] != '' && $row['status'] != 7){
											echo '<a href="https://cesco-logistics.com/e-cesco/main.php?module=invoice-customer&act=update&id='.base64_encrypt($row['id_invoice'],$key).'" class="btn btn-xs btn-success" title="lihat invoice"  target="_blank"><i class="fa fa-share-square-o"></i></a>';	
											}
											

											echo '
											<button class="btn btn-xs btn-success" onClick="window.open(\'reportsnew/airwaybillnew.php?id='.$id.'\',\'mywindow\',\'menubar=1,resizable=1,width=800,height=600\');"><i class="fa fa-print"></i></button></center></td>
										</tr>';
									}
									?>
									</tbody>
								</table>
							</div><!-- /.box-body -->
						</div><!-- /.box -->
					</div>
				</div><!-- /.row -->
				<?php
				}else{
				//	echo 'select id_kustomer, nama_kustomer from kustomer where id_kustomer=$u[id_kustomer] order by nama_kustomer';
				?>
				<form class="form-horizontal" method="post" action="" autocomplete="off">
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">Airwaybill by Customer</h3>
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
							<div class="col-md-6">
								<div class="form-group">
									<label class="col-sm-3 control-label full-right">Customer</label>
									<div class="col-sm-8">
										<select name="id_kustomer" class="form-control select2">
										<?php
										if($u['id_kustomer']> 0){
											$query=mysqli_query($con,'select id_kustomer, nama_kustomer from kustomer  order by nama_kustomer');
											
										}else{
											$query=mysqli_query($con,'select id_kustomer, nama_kustomer from kustomer order by nama_kustomer');	
										}
										while($r=mysqli_fetch_assoc($query)){
											echo'
											<option value="'.$r['id_kustomer'].'">'.$r['nama_kustomer'].'</option>';
										}

										?>
										</select>
									</div>
								</div>
							</div>
						</div><!-- /.row -->
					</div><!-- /.box-body -->
			        <div class="box-footer">
						<button type="submit" class="btn btn-primary pull-right"> <i class="fa fa-search"></i> View</button>
					</div>
				</div><!-- /.box -->
				</form>
				<?php
				}
				?>
			</section><!-- /.content -->

			<?php


			break;
		
		}
	}else{
		echo akses_salah();
	}
}
?>

<script>


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
    	if(service == '5' || service == '4'){
    		bagiv = 4000;
    	}else if(service == '6'){
    		bagiv = 5000;
    	}
    	else{
    		bagiv = 6000;
    	}
    }
    //alert(bagiv);

   // var collie = $("#collie").val();
     

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
				
			}
		);
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
	var t26 = $("#total26").val();
	var t27 = $("#total27").val();
	var t28 = $("#total28").val();
	var t29 = $("#total29").val();
	var t30 = $("#total30").val();
	var t31 = $("#total31").val();
	var t32 = $("#total32").val();
	var t33 = $("#total33").val();
	var t34 = $("#total34").val();
	var t35 = $("#total35").val();
	var t36 = $("#total36").val();
	var t37 = $("#total37").val();
	var t38 = $("#total38").val();
	var t39 = $("#total39").val();
	var t40 = $("#total40").val();
	
	
   

	ttl = Number(t1)+Number(t2)+Number(t3)+Number(t4)+Number(t5)+Number(t6)+Number(t7)+Number(t8)+Number(t9)+Number(t10)+Number(t11)+Number(t12)+Number(t13)+Number(t14)+Number(t15)+Number(t16)+Number(t17)+Number(t18)+Number(t19)+Number(t20)+Number(t21)+Number(t22)+Number(t23)+Number(t24)+Number(t25)+Number(t26)+Number(t27)+Number(t28)+Number(t29)+Number(t30)+Number(t31)+Number(t32)+Number(t33)+Number(t34)+Number(t35)+Number(t36)+Number(t37)+Number(t38)+Number(t39)+Number(t40);
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
        if(service == '5' || service == '4'){
            bagiv = 4000;
        }else if(service == '6'){
    		bagiv = 5000;
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
        if(service == '5' || service == '4'){
            bagiv = 4000;
        }else if(service == '6'){
    		bagiv = 5000;
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
        if(service == '5' || service == '4'){
            bagiv = 4000;
        }else if(service == '6'){
    		bagiv = 5000;
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
        if(service == '5' || service == '4'){
            bagiv = 4000;
        }else if(service == '6'){
    		bagiv = 5000;
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
        if(service == '5' || service == '4'){
            bagiv = 4000;
        }else if(service == '6'){
    		bagiv = 5000;
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
        if(service == '5' || service == '4'){
            bagiv = 4000;
        }else if(service == '6'){
    		bagiv = 5000;
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

    

    var collie = $("#collie").val();
    totalll = 0;

    for (i = 1; i <= collie; i++) {
		  totalll = Number(totalll) + Number($('#total'+i).val());
		//  alert(i);

		}

	$("#berat_barang").val(totalll);
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
        if(service == '5' || service == '4'){
            bagiv = 4000;
        }else if(service == '6'){
    		bagiv = 5000;
    	}else{
            bagiv = 6000;
        }
    }
    //alert(bagiv);

    //var collie = $("#collie").val();
     

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
        if(service == '5' || service == '4'){
            bagiv = 4000;
        }else if(service == '6'){
    		bagiv = 5000;
    	}else{
            bagiv = 6000;
        }
    }
    //alert(bagiv);

   // var collie = $("#collie").val();
     

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
    totalll = 0;

  
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
        if(service == '5' || service == '4'){
            bagiv = 4000;
        }else if(service == '6'){
    		bagiv = 5000;
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
    totalll = 0;

  
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
        if(service == '5' || service == '4'){
            bagiv = 4000;
        }else if(service == '6'){
    		bagiv = 5000;
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
        if(service == '5' || service == '4'){
            bagiv = 4000;
        }else if(service == '6'){
    		bagiv = 5000;
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
        if(service == '5' || service == '4'){
            bagiv = 4000;
        }else if(service == '6'){
    		bagiv = 5000;
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
        if(service == '5' || service == '4'){
            bagiv = 4000;
        }else if(service == '6'){
    		bagiv = 5000;
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
        if(service == '5' || service == '4'){
            bagiv = 4000;
        }else if(service == '6'){
    		bagiv = 5000;
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
        if(service == '5' || service == '4'){
            bagiv = 4000;
        }else if(service == '6'){
    		bagiv = 5000;
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
        if(service == '5' || service == '4'){
            bagiv = 4000;
        }else if(service == '6'){
    		bagiv = 5000;
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
        if(service == '5' || service == '4'){
            bagiv = 4000;
        }else if(service == '6'){
    		bagiv = 5000;
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
        if(service == '5' || service == '4'){
            bagiv = 4000;
        }else if(service == '6'){
    		bagiv = 5000;
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
        if(service == '5' || service == '4'){
            bagiv = 4000;
        }else if(service == '6'){
    		bagiv = 5000;
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
        if(service == '5' || service == '4'){
            bagiv = 4000;
        }else if(service == '6'){
    		bagiv = 5000;
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
        if(service == '5' || service == '4'){
            bagiv = 4000;
        }else if(service == '6'){
    		bagiv = 5000;
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

	var we = $("#weight23").val();
    var w = $("#width23").val();
    var l = $("#length23").val();
    var h = $("#height23").val();

        var intercityasal = $("#intercityasal").val();
    var intercitytujuan = $("#intercitytujuan").val();
    var service = $("#serviceshow").val();

   if(intercityasal== 'Y' && intercitytujuan == 'Y'){
        bagiv = 4000;
    }else{
        if(service == '5' || service == '4'){
            bagiv = 4000;
        }else if(service == '6'){
    		bagiv = 5000;
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
        if(service == '5' || service == '4'){
            bagiv = 4000;
        }else if(service == '6'){
    		bagiv = 5000;
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

function hitung25() {

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
        if(service == '5' || service == '4'){
            bagiv = 4000;
        }else if(service == '6'){
    		bagiv = 5000;
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
        if(service == '5' || service == '4'){
            bagiv = 4000;
        }else if(service == '6'){
    		bagiv = 5000;
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
        if(service == '5' || service == '4'){
            bagiv = 4000;
        }else if(service == '6'){
    		bagiv = 5000;
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
        if(service == '5' || service == '4'){
            bagiv = 4000;
        }else if(service == '6'){
    		bagiv = 5000;
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
function hitung29() {

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
        if(service == '5' || service == '4'){
            bagiv = 4000;
        }else if(service == '6'){
    		bagiv = 5000;
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
function hitung30() {

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
        if(service == '5' || service == '4'){
            bagiv = 4000;
        }else if(service == '6'){
    		bagiv = 5000;
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
        if(service == '5' || service == '4'){
            bagiv = 4000;
        }else if(service == '6'){
    		bagiv = 5000;
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
        if(service == '5' || service == '4'){
            bagiv = 4000;
        }else if(service == '6'){
    		bagiv = 5000;
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

	var we = $("#weight33").val();
    var w = $("#width33").val();
    var l = $("#length33").val();
    var h = $("#height33").val();

        var intercityasal = $("#intercityasal").val();
    var intercitytujuan = $("#intercitytujuan").val();
    var service = $("#serviceshow").val();

   if(intercityasal== 'Y' && intercitytujuan == 'Y'){
        bagiv = 4000;
    }else{
        if(service == '5' || service == '4'){
            bagiv = 4000;
        }else if(service == '6'){
    		bagiv = 5000;
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
        if(service == '5' || service == '4'){
            bagiv = 4000;
        }else if(service == '6'){
    		bagiv = 5000;
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
        if(service == '5' || service == '4'){
            bagiv = 4000;
        }else if(service == '6'){
    		bagiv = 5000;
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
        if(service == '5' || service == '4'){
            bagiv = 4000;
        }else if(service == '6'){
    		bagiv = 5000;
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
        if(service == '5' || service == '4'){
            bagiv = 4000;
        }else if(service == '6'){
    		bagiv = 5000;
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
        if(service == '5' || service == '4'){
            bagiv = 4000;
        }else if(service == '6'){
    		bagiv = 5000;
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
        if(service == '5' || service == '4'){
            bagiv = 4000;
        }else if(service == '6'){
    		bagiv = 5000;
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
        if(service == '5' || service == '4'){
            bagiv = 4000;
        }else if(service == '6'){
    		bagiv = 5000;
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

function hitung41() {

	var we = $("#weight41").val();
    var w = $("#width41").val();
    var l = $("#length41").val();
    var h = $("#height41").val();

        var intercityasal = $("#intercityasal").val();
    var intercitytujuan = $("#intercitytujuan").val();
    var service = $("#serviceshow").val();

   if(intercityasal== 'Y' && intercitytujuan == 'Y'){
        bagiv = 4000;
    }else{
        if(service == '5' || service == '4'){
            bagiv = 4000;
        }else if(service == '6'){
    		bagiv = 5000;
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
				$("#total41").val(html);
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

function hitung42() {

	var we = $("#weight42").val();
    var w = $("#width42").val();
    var l = $("#length42").val();
    var h = $("#height42").val();

        var intercityasal = $("#intercityasal").val();
    var intercitytujuan = $("#intercitytujuan").val();
    var service = $("#serviceshow").val();

   if(intercityasal== 'Y' && intercitytujuan == 'Y'){
        bagiv = 4000;
    }else{
        if(service == '5' || service == '4'){
            bagiv = 4000;
        }else if(service == '6'){
    		bagiv = 5000;
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
				$("#total42").val(html);
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

function hitung43() {

	var we = $("#weight43").val();
    var w = $("#width43").val();
    var l = $("#length43").val();
    var h = $("#height43").val();

        var intercityasal = $("#intercityasal").val();
    var intercitytujuan = $("#intercitytujuan").val();
    var service = $("#serviceshow").val();

   if(intercityasal== 'Y' && intercitytujuan == 'Y'){
        bagiv = 4000;
    }else{
        if(service == '5' || service == '4'){
            bagiv = 4000;
        }else if(service == '6'){
    		bagiv = 5000;
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
				$("#total43").val(html);
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

function hitung44() {

	var we = $("#weight44").val();
    var w = $("#width44").val();
    var l = $("#length44").val();
    var h = $("#height44").val();

        var intercityasal = $("#intercityasal").val();
    var intercitytujuan = $("#intercitytujuan").val();
    var service = $("#serviceshow").val();

   if(intercityasal== 'Y' && intercitytujuan == 'Y'){
        bagiv = 4000;
    }else{
        if(service == '5' || service == '4'){
            bagiv = 4000;
        }else if(service == '6'){
    		bagiv = 5000;
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
				$("#total44").val(html);
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

function hitung45() {

	var we = $("#weight45").val();
    var w = $("#width45").val();
    var l = $("#length45").val();
    var h = $("#height45").val();

        var intercityasal = $("#intercityasal").val();
    var intercitytujuan = $("#intercitytujuan").val();
    var service = $("#serviceshow").val();

   if(intercityasal== 'Y' && intercitytujuan == 'Y'){
        bagiv = 4000;
    }else{
        if(service == '5' || service == '4'){
            bagiv = 4000;
        }else if(service == '6'){
    		bagiv = 5000;
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
				$("#total45").val(html);
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

function hitung46() {

	var we = $("#weight46").val();
    var w = $("#width46").val();
    var l = $("#length46").val();
    var h = $("#height46").val();

        var intercityasal = $("#intercityasal").val();
    var intercitytujuan = $("#intercitytujuan").val();
    var service = $("#serviceshow").val();

   if(intercityasal== 'Y' && intercitytujuan == 'Y'){
        bagiv = 4000;
    }else{
        if(service == '5' || service == '4'){
            bagiv = 4000;
        }else if(service == '6'){
    		bagiv = 5000;
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
				$("#total46").val(html);
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

function hitung47() {

	var we = $("#weight47").val();
    var w = $("#width47").val();
    var l = $("#length47").val();
    var h = $("#height47").val();

        var intercityasal = $("#intercityasal").val();
    var intercitytujuan = $("#intercitytujuan").val();
    var service = $("#serviceshow").val();

   if(intercityasal== 'Y' && intercitytujuan == 'Y'){
        bagiv = 4000;
    }else{
        if(service == '5' || service == '4'){
            bagiv = 4000;
        }else if(service == '6'){
    		bagiv = 5000;
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
				$("#total47").val(html);
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

function hitung48() {

	var we = $("#weight48").val();
    var w = $("#width48").val();
    var l = $("#length48").val();
    var h = $("#height48").val();

        var intercityasal = $("#intercityasal").val();
    var intercitytujuan = $("#intercitytujuan").val();
    var service = $("#serviceshow").val();

   if(intercityasal== 'Y' && intercitytujuan == 'Y'){
        bagiv = 4000;
    }else{
        if(service == '5' || service == '4'){
            bagiv = 4000;
        }else if(service == '6'){
    		bagiv = 5000;
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
				$("#total48").val(html);
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

function hitung49() {

	var we = $("#weight49").val();
    var w = $("#width49").val();
    var l = $("#length49").val();
    var h = $("#height49").val();

        var intercityasal = $("#intercityasal").val();
    var intercitytujuan = $("#intercitytujuan").val();
    var service = $("#serviceshow").val();

   if(intercityasal== 'Y' && intercitytujuan == 'Y'){
        bagiv = 4000;
    }else{
        if(service == '5' || service == '4'){
            bagiv = 4000;
        }else if(service == '6'){
    		bagiv = 5000;
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
				$("#total49").val(html);
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

function hitung50() {

	var we = $("#weight50").val();
    var w = $("#width50").val();
    var l = $("#length50").val();
    var h = $("#height50").val();

        var intercityasal = $("#intercityasal").val();
    var intercitytujuan = $("#intercitytujuan").val();
    var service = $("#serviceshow").val();

   if(intercityasal== 'Y' && intercitytujuan == 'Y'){
        bagiv = 4000;
    }else{
        if(service == '5' || service == '4'){
            bagiv = 4000;
        }else if(service == '6'){
    		bagiv = 5000;
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
				$("#total50").val(html);
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

function hitung51() {

	var we = $("#weight51").val();
    var w = $("#width51").val();
    var l = $("#length51").val();
    var h = $("#height51").val();

        var intercityasal = $("#intercityasal").val();
    var intercitytujuan = $("#intercitytujuan").val();
    var service = $("#serviceshow").val();

   if(intercityasal== 'Y' && intercitytujuan == 'Y'){
        bagiv = 4000;
    }else{
        if(service == '5' || service == '4'){
            bagiv = 4000;
        }else if(service == '6'){
    		bagiv = 5000;
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
				$("#total51").val(html);
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

function hitung52() {

	var we = $("#weight52").val();
    var w = $("#width52").val();
    var l = $("#length52").val();
    var h = $("#height52").val();

        var intercityasal = $("#intercityasal").val();
    var intercitytujuan = $("#intercitytujuan").val();
    var service = $("#serviceshow").val();

   if(intercityasal== 'Y' && intercitytujuan == 'Y'){
        bagiv = 4000;
    }else{
        if(service == '5' || service == '4'){
            bagiv = 4000;
        }else if(service == '6'){
    		bagiv = 5000;
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
				$("#total52").val(html);
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

function hitung53() {

	var we = $("#weight53").val();
    var w = $("#width53").val();
    var l = $("#length53").val();
    var h = $("#height53").val();

        var intercityasal = $("#intercityasal").val();
    var intercitytujuan = $("#intercitytujuan").val();
    var service = $("#serviceshow").val();

   if(intercityasal== 'Y' && intercitytujuan == 'Y'){
        bagiv = 4000;
    }else{
        if(service == '5' || service == '4'){
            bagiv = 4000;
        }else if(service == '6'){
    		bagiv = 5000;
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
				$("#total53").val(html);
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

function hitung54() {

	var we = $("#weight54").val();
    var w = $("#width54").val();
    var l = $("#length54").val();
    var h = $("#height54").val();

        var intercityasal = $("#intercityasal").val();
    var intercitytujuan = $("#intercitytujuan").val();
    var service = $("#serviceshow").val();

   if(intercityasal== 'Y' && intercitytujuan == 'Y'){
        bagiv = 4000;
    }else{
        if(service == '5' || service == '4'){
            bagiv = 4000;
        }else if(service == '6'){
    		bagiv = 5000;
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
				$("#total54").val(html);
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

function hitung55() {

	var we = $("#weight55").val();
    var w = $("#width55").val();
    var l = $("#length55").val();
    var h = $("#height55").val();

        var intercityasal = $("#intercityasal").val();
    var intercitytujuan = $("#intercitytujuan").val();
    var service = $("#serviceshow").val();

   if(intercityasal== 'Y' && intercitytujuan == 'Y'){
        bagiv = 4000;
    }else{
        if(service == '5' || service == '4'){
            bagiv = 4000;
        }else if(service == '6'){
    		bagiv = 5000;
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
				$("#total55").val(html);
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

function hitung56() {

	var we = $("#weight56").val();
    var w = $("#width56").val();
    var l = $("#length56").val();
    var h = $("#height56").val();

        var intercityasal = $("#intercityasal").val();
    var intercitytujuan = $("#intercitytujuan").val();
    var service = $("#serviceshow").val();

   if(intercityasal== 'Y' && intercitytujuan == 'Y'){
        bagiv = 4000;
    }else{
        if(service == '5' || service == '4'){
            bagiv = 4000;
        }else if(service == '6'){
    		bagiv = 5000;
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
				$("#total56").val(html);
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

function hitung57() {

	var we = $("#weight57").val();
    var w = $("#width57").val();
    var l = $("#length57").val();
    var h = $("#height57").val();

        var intercityasal = $("#intercityasal").val();
    var intercitytujuan = $("#intercitytujuan").val();
    var service = $("#serviceshow").val();

   if(intercityasal== 'Y' && intercitytujuan == 'Y'){
        bagiv = 4000;
    }else{
        if(service == '5' || service == '4'){
            bagiv = 4000;
        }else if(service == '6'){
    		bagiv = 5000;
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
				$("#total57").val(html);
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

function hitung58() {

	var we = $("#weight58").val();
    var w = $("#width58").val();
    var l = $("#length58").val();
    var h = $("#height58").val();

        var intercityasal = $("#intercityasal").val();
    var intercitytujuan = $("#intercitytujuan").val();
    var service = $("#serviceshow").val();

   if(intercityasal== 'Y' && intercitytujuan == 'Y'){
        bagiv = 4000;
    }else{
        if(service == '5' || service == '4'){
            bagiv = 4000;
        }else if(service == '6'){
    		bagiv = 5000;
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
				$("#total58").val(html);
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

function hitung59() {

	var we = $("#weight59").val();
    var w = $("#width59").val();
    var l = $("#length59").val();
    var h = $("#height59").val();

        var intercityasal = $("#intercityasal").val();
    var intercitytujuan = $("#intercitytujuan").val();
    var service = $("#serviceshow").val();

   if(intercityasal== 'Y' && intercitytujuan == 'Y'){
        bagiv = 4000;
    }else{
        if(service == '5' || service == '4'){
            bagiv = 4000;
        }else if(service == '6'){
    		bagiv = 5000;
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
				$("#total59").val(html);
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

function hitung60() {

	var we = $("#weight60").val();
    var w = $("#width60").val();
    var l = $("#length60").val();
    var h = $("#height60").val();

        var intercityasal = $("#intercityasal").val();
    var intercitytujuan = $("#intercitytujuan").val();
    var service = $("#serviceshow").val();

   if(intercityasal== 'Y' && intercitytujuan == 'Y'){
        bagiv = 4000;
    }else{
        if(service == '5' || service == '4'){
            bagiv = 4000;
        }else if(service == '6'){
    		bagiv = 5000;
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
				$("#total60").val(html);
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

function hitung61() {

	var we = $("#weight61").val();
    var w = $("#width61").val();
    var l = $("#length61").val();
    var h = $("#height61").val();

        var intercityasal = $("#intercityasal").val();
    var intercitytujuan = $("#intercitytujuan").val();
    var service = $("#serviceshow").val();

   if(intercityasal== 'Y' && intercitytujuan == 'Y'){
        bagiv = 4000;
    }else{
        if(service == '5' || service == '4'){
            bagiv = 4000;
        }else if(service == '6'){
    		bagiv = 5000;
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
				$("#total61").val(html);
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

function hitung62() {

	var we = $("#weight62").val();
    var w = $("#width62").val();
    var l = $("#length62").val();
    var h = $("#height62").val();

        var intercityasal = $("#intercityasal").val();
    var intercitytujuan = $("#intercitytujuan").val();
    var service = $("#serviceshow").val();

   if(intercityasal== 'Y' && intercitytujuan == 'Y'){
        bagiv = 4000;
    }else{
        if(service == '5' || service == '4'){
            bagiv = 4000;
        }else if(service == '6'){
    		bagiv = 5000;
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
				$("#total62").val(html);
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

function hitung63() {

	var we = $("#weight63").val();
    var w = $("#width63").val();
    var l = $("#length63").val();
    var h = $("#height63").val();

        var intercityasal = $("#intercityasal").val();
    var intercitytujuan = $("#intercitytujuan").val();
    var service = $("#serviceshow").val();

   if(intercityasal== 'Y' && intercitytujuan == 'Y'){
        bagiv = 4000;
    }else{
        if(service == '5' || service == '4'){
            bagiv = 4000;
        }else if(service == '6'){
    		bagiv = 5000;
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
				$("#total63").val(html);
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

function hitung64() {

	var we = $("#weight64").val();
    var w = $("#width64").val();
    var l = $("#length64").val();
    var h = $("#height64").val();

        var intercityasal = $("#intercityasal").val();
    var intercitytujuan = $("#intercitytujuan").val();
    var service = $("#serviceshow").val();

   if(intercityasal== 'Y' && intercitytujuan == 'Y'){
        bagiv = 4000;
    }else{
        if(service == '5' || service == '4'){
            bagiv = 4000;
        }else if(service == '6'){
    		bagiv = 5000;
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
				$("#total64").val(html);
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

function hitung65() {

	var we = $("#weight65").val();
    var w = $("#width65").val();
    var l = $("#length65").val();
    var h = $("#height65").val();

        var intercityasal = $("#intercityasal").val();
    var intercitytujuan = $("#intercitytujuan").val();
    var service = $("#serviceshow").val();

   if(intercityasal== 'Y' && intercitytujuan == 'Y'){
        bagiv = 4000;
    }else{
        if(service == '5' || service == '4'){
            bagiv = 4000;
        }else if(service == '6'){
    		bagiv = 5000;
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
				$("#total65").val(html);
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

function hitung66() {

	var we = $("#weight66").val();
    var w = $("#width66").val();
    var l = $("#length66").val();
    var h = $("#height66").val();

        var intercityasal = $("#intercityasal").val();
    var intercitytujuan = $("#intercitytujuan").val();
    var service = $("#serviceshow").val();

   if(intercityasal== 'Y' && intercitytujuan == 'Y'){
        bagiv = 4000;
    }else{
        if(service == '5' || service == '4'){
            bagiv = 4000;
        }else if(service == '6'){
    		bagiv = 5000;
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
				$("#total66").val(html);
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

function hitung67() {

	var we = $("#weight67").val();
    var w = $("#width67").val();
    var l = $("#length67").val();
    var h = $("#height67").val();

        var intercityasal = $("#intercityasal").val();
    var intercitytujuan = $("#intercitytujuan").val();
    var service = $("#serviceshow").val();

   if(intercityasal== 'Y' && intercitytujuan == 'Y'){
        bagiv = 4000;
    }else{
        if(service == '5' || service == '4'){
            bagiv = 4000;
        }else if(service == '6'){
    		bagiv = 5000;
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
				$("#total67").val(html);
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

function hitung68() {

	var we = $("#weight68").val();
    var w = $("#width68").val();
    var l = $("#length68").val();
    var h = $("#height68").val();

        var intercityasal = $("#intercityasal").val();
    var intercitytujuan = $("#intercitytujuan").val();
    var service = $("#serviceshow").val();

   if(intercityasal== 'Y' && intercitytujuan == 'Y'){
        bagiv = 4000;
    }else{
        if(service == '5' || service == '4'){
            bagiv = 4000;
        }else if(service == '6'){
    		bagiv = 5000;
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
				$("#total68").val(html);
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

function hitung69() {

	var we = $("#weight69").val();
    var w = $("#width69").val();
    var l = $("#length69").val();
    var h = $("#height69").val();

        var intercityasal = $("#intercityasal").val();
    var intercitytujuan = $("#intercitytujuan").val();
    var service = $("#serviceshow").val();

   if(intercityasal== 'Y' && intercitytujuan == 'Y'){
        bagiv = 4000;
    }else{
        if(service == '5' || service == '4'){
            bagiv = 4000;
        }else if(service == '6'){
    		bagiv = 5000;
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
				$("#total69").val(html);
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

function hitung70() {

	var we = $("#weight70").val();
    var w = $("#width70").val();
    var l = $("#length70").val();
    var h = $("#height70").val();

        var intercityasal = $("#intercityasal").val();
    var intercitytujuan = $("#intercitytujuan").val();
    var service = $("#serviceshow").val();

   if(intercityasal== 'Y' && intercitytujuan == 'Y'){
        bagiv = 4000;
    }else{
        if(service == '5' || service == '4'){
            bagiv = 4000;
        }else if(service == '6'){
    		bagiv = 5000;
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
				$("#total70").val(html);
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

function hitung71() {

	var we = $("#weight71").val();
    var w = $("#width71").val();
    var l = $("#length71").val();
    var h = $("#height71").val();

        var intercityasal = $("#intercityasal").val();
    var intercitytujuan = $("#intercitytujuan").val();
    var service = $("#serviceshow").val();

   if(intercityasal== 'Y' && intercitytujuan == 'Y'){
        bagiv = 4000;
    }else{
        if(service == '5' || service == '4'){
            bagiv = 4000;
        }else if(service == '6'){
    		bagiv = 5000;
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
				$("#total71").val(html);
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

function hitung72() {

	var we = $("#weight72").val();
    var w = $("#width72").val();
    var l = $("#length72").val();
    var h = $("#height72").val();

        var intercityasal = $("#intercityasal").val();
    var intercitytujuan = $("#intercitytujuan").val();
    var service = $("#serviceshow").val();

   if(intercityasal== 'Y' && intercitytujuan == 'Y'){
        bagiv = 4000;
    }else{
        if(service == '5' || service == '4'){
            bagiv = 4000;
        }else if(service == '6'){
    		bagiv = 5000;
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
				$("#total72").val(html);
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

function hitung73() {

	var we = $("#weight73").val();
    var w = $("#width73").val();
    var l = $("#length73").val();
    var h = $("#height73").val();

        var intercityasal = $("#intercityasal").val();
    var intercitytujuan = $("#intercitytujuan").val();
    var service = $("#serviceshow").val();

   if(intercityasal== 'Y' && intercitytujuan == 'Y'){
        bagiv = 4000;
    }else{
        if(service == '5' || service == '4'){
            bagiv = 4000;
        }else if(service == '6'){
    		bagiv = 5000;
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
				$("#total73").val(html);
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

function hitung74() {

	var we = $("#weight74").val();
    var w = $("#width74").val();
    var l = $("#length74").val();
    var h = $("#height74").val();

        var intercityasal = $("#intercityasal").val();
    var intercitytujuan = $("#intercitytujuan").val();
    var service = $("#serviceshow").val();

   if(intercityasal== 'Y' && intercitytujuan == 'Y'){
        bagiv = 4000;
    }else{
        if(service == '5' || service == '4'){
            bagiv = 4000;
        }else if(service == '6'){
    		bagiv = 5000;
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
				$("#total74").val(html);
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

function hitung75() {

	var we = $("#weight75").val();
    var w = $("#width75").val();
    var l = $("#length75").val();
    var h = $("#height75").val();

        var intercityasal = $("#intercityasal").val();
    var intercitytujuan = $("#intercitytujuan").val();
    var service = $("#serviceshow").val();

   if(intercityasal== 'Y' && intercitytujuan == 'Y'){
        bagiv = 4000;
    }else{
        if(service == '5' || service == '4'){
            bagiv = 4000;
        }else if(service == '6'){
    		bagiv = 5000;
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
				$("#total75").val(html);
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
function hitung76() {

	var we = $("#weight76").val();
    var w = $("#width76").val();
    var l = $("#length76").val();
    var h = $("#height76").val();

        var intercityasal = $("#intercityasal").val();
    var intercitytujuan = $("#intercitytujuan").val();
    var service = $("#serviceshow").val();

   if(intercityasal== 'Y' && intercitytujuan == 'Y'){
        bagiv = 4000;
    }else{
        if(service == '5' || service == '4'){
            bagiv = 4000;
        }else if(service == '6'){
    		bagiv = 5000;
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
				$("#total76").val(html);
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

function hitung77() {

	var we = $("#weight77").val();
    var w = $("#width77").val();
    var l = $("#length77").val();
    var h = $("#height77").val();

        var intercityasal = $("#intercityasal").val();
    var intercitytujuan = $("#intercitytujuan").val();
    var service = $("#serviceshow").val();

   if(intercityasal== 'Y' && intercitytujuan == 'Y'){
        bagiv = 4000;
    }else{
        if(service == '5' || service == '4'){
            bagiv = 4000;
        }else if(service == '6'){
    		bagiv = 5000;
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
				$("#total77").val(html);
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

function hitung78() {

	var we = $("#weight78").val();
    var w = $("#width78").val();
    var l = $("#length78").val();
    var h = $("#height78").val();

        var intercityasal = $("#intercityasal").val();
    var intercitytujuan = $("#intercitytujuan").val();
    var service = $("#serviceshow").val();

   if(intercityasal== 'Y' && intercitytujuan == 'Y'){
        bagiv = 4000;
    }else{
        if(service == '5' || service == '4'){
            bagiv = 4000;
        }else if(service == '6'){
    		bagiv = 5000;
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
				$("#total78").val(html);
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

function hitung79() {

	var we = $("#weight79").val();
    var w = $("#width79").val();
    var l = $("#length79").val();
    var h = $("#height79").val();

        var intercityasal = $("#intercityasal").val();
    var intercitytujuan = $("#intercitytujuan").val();
    var service = $("#serviceshow").val();

   if(intercityasal== 'Y' && intercitytujuan == 'Y'){
        bagiv = 4000;
    }else{
        if(service == '5' || service == '4'){
            bagiv = 4000;
        }else if(service == '6'){
    		bagiv = 5000;
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
				$("#total79").val(html);
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

function hitung80() {

	var we = $("#weight80").val();
    var w = $("#width80").val();
    var l = $("#length80").val();
    var h = $("#height80").val();

        var intercityasal = $("#intercityasal").val();
    var intercitytujuan = $("#intercitytujuan").val();
    var service = $("#serviceshow").val();

   if(intercityasal== 'Y' && intercitytujuan == 'Y'){
        bagiv = 4000;
    }else{
        if(service == '5' || service == '4'){
            bagiv = 4000;
        }else if(service == '6'){
    		bagiv = 5000;
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
				$("#total80").val(html);
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
		
		var rupiah = document.getElementById('rupiah');
		rupiah.addEventListener('keyup', function(e){
			// tambahkan 'Rp.' pada saat form di ketik
			// gunakan fungsi formatRupiah() untuk mengubah angka yang di ketik menjadi format angka
			rupiah.value = formatRupiah(this.value, 'Rp. ');
		});
 
		/* Fungsi formatRupiah */
		function formatRupiah(angka, prefix){
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
		}
	</script>
