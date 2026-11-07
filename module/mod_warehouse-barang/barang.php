<section class="content-header">
	<h1>Data Barang<small></small></h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-home"></i> Home</a></li>
		<li><a href="#">Warehouse</a></li>
		<li class="active">Data Barang</li>
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
	if($u['level']=='admin' || $u['level']=='domex' || $u['level']=='operational' || $u['level']=='network' || $u['level']=='finance' || $u['level']=='inbound' || $u['level']=='kurir'){

		$aksi	= "module/mod_warehouse-barang/barang_action.php";
		
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
											<button class="btn btn-sm btn-primary" onclick="window.location.href = \'?module=barang&act=create\';">Add New <i class="fa fa-plus"></i></button> 
											<button class="btn btn-sm btn-primary" onclick="window.location.href = \'?module=barang&act=out\';">Out <i class="fa fa-plus"></i></button>
											<button class="btn btn-sm btn-primary" onclick="window.location.href = \'?module=barang&act=out-berulang\';">Out Berulang <i class="fa fa-plus"></i></button>

											<button class="btn btn-sm btn-primary" onclick="window.location.href = \'?module=barang&act=in\';">In <i class="fa fa-plus"></i></button>
											
										</div>
									</div>
									
								</div>
							</div>
							<div class="box-body">
								<table class="table table-striped table-bordered table-hover" id="barang">
									<thead>
										<tr>
											<th>Nama barang</th>
											<th>Kode barang</th>
											<th>Pemilik</th>
											<th>Stok</th>
											<th>Tanggal masuk</th>
											<th>Lokasi rak</th>
											<th>Lokasi baru</th>
											<th>Gudang</th>
											<th>Expired</th>
											<th>departemen</th>
											<th>SN</th>
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
					<form name="form-barangcreate" method="post" action="'.$aksi.'?module=barang&act=create" class="form-horizontal form-bordered" id="form-barangcreate" autocomplete="off" enctype="multipart/form-data">
					<div class="box-body">
						
						<div class="form-group">
							<label class="col-md-3 control-label">Nama Barang<span class="required">* </span></label>
							<div class="col-md-6">
								<input type="text" name="nama" placeholder="Nama..." class="form-control" required>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label">Kode Barang<span class="required">* </span></label>
							<div class="col-md-6">
								<input type="text" name="kode_barang" placeholder="kode barang..." class="form-control" maxlength="45" required>
							</div>
						</div>
						
						<div class="form-group">
							<label class="control-label col-md-3" >SERIAL NUMBER <span class="required">* </span></label>
							<div class="col-md-6">
								<div class="radio radio-info">
								
									<label><input type="radio" name="dengan_serial" value="Y" onchange="create_dengan_serial_y()" id="dengan_serial_y" /> Ya
									<label><input type="radio" name="dengan_serial" value="N" onchange="create_dengan_serial_n()" id="dengan_serial_n" checked/> Tidak 
								</div>
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-md-3 control-label">Stok Awal  <span class="required">* </span></label>
							<div class="col-md-6">
								<input type="number" name="stok" placeholder="stok awal..." class="form-control create_stok_awal" required>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label">Satuan <span class="required">* </span></label>
							<div class="col-md-6">
								<input type="text" name="satuan" placeholder="satuan contoh pcs,buah,set,box dll" class="form-control" required>
							</div>
						</div>
						
						

						<div class="form-group">
							<label class="col-md-3 control-label">Customer <span class="required">* </span></label>
							<div class="col-md-6">
								<select name="pemilik" id="kustomer" data-placeholder="Pemilik..." class="form-control select2" required>';
								$result = mysqli_query($con,"select id_kustomer, nama_kustomer from kustomer where status!='N' and warehouse != 'N' order by nama_kustomer");
								echo '<option value="">Select...</option>';
								while ($row = mysqli_fetch_assoc($result)){
									
										echo '<option value="' . $row['id_kustomer'] . '">' . $row['nama_kustomer'] . '</option>';
									
								}
								echo'
								</select>
								<div class="val-message"></div>
								
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label">Rak <span class="required">* </span></label>
							<div class="col-md-6">
								<select name="rak" id="select_rak" data-placeholder="rak..." class="form-control select2">';
								$rak = mysqli_query($con,"select id, nama_rak, x_namacabang(id_gudang) as nama_gudang from warehouse_rak order by nama_rak");
								echo '<option value="">Select...</option>';
								while ($r_rak = mysqli_fetch_assoc($rak)){
									
										echo '<option value="' . $r_rak['id'] . '">' . $r_rak['nama_rak'] .'('.$r_rak['nama_gudang'].')</option>';
									
								}
								echo'
								</select>
								<div class="val-message"></div>
								
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label">Foto  <span class="required">* </span></label>
				        	<div class="col-md-6">
				        		<input type="file" name="foto" required accept="image/*">
				        	</div>
				        </div>
				        <div class="form-group">
							<label class="col-md-3 control-label">Expired Date  <span class="required">* </span></label>
				        	<div class="col-md-6">
				        		<input type="text" class="form-control" id="tanggal" name="expired_date" required value="'.$setdate.'">
				        	</div>
				        	<div class="val-message"></div>
				        </div>
				        <div class="form-group">
							<label class="col-md-3 control-label">Action After Expired <span class="required"> </span></label>
							<div class="col-md-6">
								<select name="action_expired" id="action_expired" data-placeholder="Action After Expired..." class="form-control select2">
									<option value="">Select...</option>
									<option value="destroy">destroy</option>
									<option value="keep">keep</option>
								</select>
								
								
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label">Departement <span class="required"></span></label>
							<div class="col-md-6">
								<select name="departement" id="departement" data-placeholder="Departement...(kosongkan jika tidak ada departement)" class="form-control select2">';
								$sql_departemen = mysqli_query($con,"select id, id_kustomer, x_namakustomer(id_kustomer) as nama_kustomer,nama_departement from kustomer_departement order by nama_departement");
								echo '<option value="">Select...(pilih select jika tidak ada Departement)</option>';
								while ($r_d = mysqli_fetch_assoc($sql_departemen)){
									
										echo '<option value="' . $r_d['id'] . '">' . $r_d['nama_departement'] .'('.$r_d['nama_kustomer'].')</option>';
									
								}
								echo'
								</select>
								
								
							</div>
						</div>
						';

					echo '	
					</div><!-- /.box-body -->
					<div class="box-footer">
						<button type="button" class="btn btn-default" onclick="window.location.href = \'?module=barang\';">Cancel</button>
						<input type="submit" class="btn btn-primary pull-right" value="Save">

					</div><!-- /.box-footer -->
					</form>
				</div><!-- /.box -->
			</section>';
			
			break;

			case "edit":

			$key = "asdf$1011";
			$encrypt = "$_GET[id]";
			$id = base64_decrypt($encrypt,$key);

			$query = mysqli_query($con, "select *,DATE_FORMAT(expired_date,'%d-%m-%Y') as expired, x_namakustomer(pemilik_customer) as nama_kustomer from warehouse_barang where id='$id'");
			$r = mysqli_fetch_assoc($query);
			
			echo'

			<section class="content">
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">Edit</h3>
						<div class="box-tools pull-right">
							<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
							<button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
						</div>
					</div><!-- /.box-header -->
					<form name="demo" method="post" action="'.$aksi.'?module=barang&act=update" class="form-horizontal form-bordered" id="form-barang" autocomplete="off" enctype="multipart/form-data">
					<input type="hidden" name="id" value="'.$r['id'].'">
					<div class="box-body">
						
						<div class="form-group">
							<label class="col-md-3 control-label">Nama Barang<span class="required">* </span></label>
							<div class="col-md-6">
								<input type="text" name="nama" placeholder="Nama..." value="'.$r['nama_barang'].'" class="form-control" required>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label">Kode Barang<span class="required">* </span></label>
							<div class="col-md-6">
								<input type="text" name="kode_barang" placeholder="kode barang..." value="'.$r['kode_barang'].'" disabled class="form-control" required>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3">SERIAL NUMBER <span class="required">* </span></label>
							<div class="col-md-3">
								<div class="radio radio-info">
								
									<label><input type="radio" name="dengan_serial" value="Y" disabled class="dengan_serial" '.($r['dengan_serial'] == 'Y' ? 'checked' : '').' /> Ya
									<label><input type="radio" name="dengan_serial" value="N" disabled class="dengan_serial" '.($r['dengan_serial'] == 'N' ? 'checked' : '').'/> Tidak 
								</div>
							</div>';

							if($r['dengan_serial'] == 'Y'){
								$select = mysqli_query($con, "SELECT * FROM warehouse_barang_serial WHERE id_produk='$id' and aktif='Y'");
								//echo "SELECT * FROM warehouse_barang_serial WHERE id_produk='$id' and aktif='Y'";
								$cek = mysqli_num_rows($select);
								if($cek != $r['stok_terkini']){
									$btn_danger = '<a href="#" class="btn btn-danger btn-xs" title="SN TIDAK SESUAI JUMLAH DENGAN STOK!"><i class="fa fa-exclamation-circle"></i> </a>';
								}else{
									$btn_danger = '';
								}
								echo '<div class="col-md-3"><a href="#" class="btn btn-primary" id="open_serial_number" data-id="'.$r['id'].'"> Edit Serial Number ('.$cek.' SN) </a></div>'.$btn_danger;
							}
							
						echo '
						</div>

						';
						



						/*<div class="form-group">
							<label class="col-md-3 control-label">Serial Number<span class="required">* </span></label>
							<div class="col-md-6">
								
								<textarea id="serial_number" name="serial_number" rows="7" cols="50" placeholder="Serial number..." class="form-control">'.$r['serial_number'].'</textarea>
							</div>
						</div>*/
						

						$q_cek =  mysqli_query($con,"select id from warehouse_history  where id_produk=".$id."");
						$cek_history = mysqli_num_rows($q_cek);


						echo '
						<div class="form-group">
							<label class="col-md-3 control-label">Stok   <span class="required">* </span></label>
							<div class="col-md-6">
								<input type="number" '.($cek_history > 0 || $r['dengan_serial'] == 'Y' ? 'disabled' : '').' name="stok" placeholder="stok awal..." value="'.$r['stok_terkini'].'" class="form-control" required>
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-md-3 control-label">Satuan <span class="required">* </span></label>
							<div class="col-md-6">
								<input type="text" name="satuan" placeholder="satuan contoh pcs,buah,set,box dll" class="form-control" value="'.$r['satuan'].'" required>
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-3 control-label">Pemilik <span class="required">* </span></label>
							<div class="col-md-6">
								<input type="text" name="pemilik" placeholder="satuan contoh pcs,buah,set,box dll" class="form-control" value="'.$r['nama_kustomer'].'" disabled >
							</div>
						</div>

				
						<div class="form-group">
							<label class="col-md-3 control-label">Rak <span class="required">* </span></label>
							<div class="col-md-6">
								<select name="rak" id="select_rak" data-placeholder="rak..." class="form-control select2">';
								$rak = mysqli_query($con,"select id, nama_rak,x_namacabang(id_gudang) as nama_gudang  from warehouse_rak  order by nama_rak");
								echo '<option value="">Select...</option>';
								while ($r_rak = mysqli_fetch_assoc($rak)){
									
										echo '<option '. ($r['id_rak'] == $r_rak['id'] ? 'selected' : '').' value="' . $r_rak['id'] . '">' . $r_rak['nama_rak'] . ' ('.$r_rak['nama_gudang'].')</option>';
									
								}
								echo'
								</select>
								<div class="val-message"></div>
								
							</div>
						</div>
						<div class="form-group" id="form-serial" >
													<label class="col-md-3 control-label"> Rak</label>
													<div class="col-md-6">
														<select name="pilih_rak[]" id="pilih_rak" multiple="multiple" data-placeholder="Choose..." class="form-control select2"  style="width: 100%; max-height: 300px !important;">
															';

																$rak_terpilih = [];
																//$q_rak = "SELECT id_rak FROM rak_barang WHERE id_barang = $id";
																$result_rak = mysqli_query($con, "SELECT id_rak FROM rak_barang WHERE id_barang = $id");

																while($row = mysqli_fetch_assoc($result_rak)){
																    $rak_terpilih[] = $row['id_rak'];
																}

																$rak = mysqli_query($con,"select id, nama_rak,x_namacabang(id_gudang) as nama_gudang  from warehouse_rak  order by nama_rak");
																	echo '<option value="">Select...</option>';
																	
																	while ($r_rak = mysqli_fetch_assoc($rak)){
																		
																			echo '<option  value="' . $r_rak['id'] . '" ';
																			?>
																				<?= in_array($r_rak['id'], $rak_terpilih) ? 'selected' : '' ?>

																			<?php
																			echo '>' 


																			. $r_rak['nama_rak'] . ' ('.$r_rak['nama_gudang'].')</option>';
																		
																	}

															echo '
														</select>
														<div class="val-message"></div>
													</div>
												</div>
												
						<div class="form-group">
							<label class="col-md-3 control-label">Expired Date  <span class="required">* </span></label>
				        	<div class="col-md-6">
				        		<input type="text" class="form-control" id="tanggal" name="expired_date" value="'.$r['expired'].'">
				        	</div>
				        </div>
				        <div class="form-group">
							<label class="col-md-3 control-label">Action After Expired <span class="required"> </span></label>
							<div class="col-md-6">
								<select name="action_expired" id="action_expired" data-placeholder="Action After Expired..." class="form-control select2">
									<option value="">Select...</option>
									<option '.($r['expired_date_action'] == 'destroy' ? 'selected' : '').' value="destroy">destroy</option>
									<option '.($r['expired_date_action'] == 'keep' ? 'selected' : '').' value="keep">keep</option>
								</select>
								
								
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label">Departement <span class="required"></span></label>
							<div class="col-md-6">
								<select name="departement" id="departement" data-placeholder="Departement...(kosongkan jika tidak ada departement)" class="form-control select2">';
								$sql_departemen = mysqli_query($con,"select id, id_kustomer, x_namakustomer(id_kustomer) as nama_kustomer,nama_departement from kustomer_departement where id_kustomer=".$r['pemilik_customer']." order by nama_departement");
								echo '<option value="">Select...(pilih select jika tidak ada Departement)</option>';
								while ($r_d = mysqli_fetch_assoc($sql_departemen)){
									
										echo '<option '. ($r['departement'] == $r_d['id'] ? 'selected' : '').' value="' . $r_d['id'] . '">' . $r_d['nama_departement'] .'('.$r_d['nama_kustomer'].')</option>';
									
								}
								echo'
								</select>
								
								
							</div>
						</div>
						<div class="form-group">
				        	<label class="col-md-3 control-label">Ganti Foto Barang <span class="required"></span></label>
				        	<div class="col-md-6">
								<input type="file" name="gambar"  >
							</div>

						</div>
						
						';

					echo '	
					</div><!-- /.box-body -->
					<div class="box-footer">
						<button type="button" class="btn btn-default" onclick="window.location.href = \'?module=barang\';">Cancel</button>
						<button type="submit" class="btn btn-primary pull-right">Save</button>
						';
						if($cek_history > 0){
							echo '<a class="btn btn-sm btn-danger" href="#" disabled>delete </a> ';
						}else{
							echo '<a class="btn btn-sm btn-danger" href="'.$aksi.'?module=barang&act=delete&id='.$encrypt.'" >delete </a> ';
						}
						echo '
					</div><!-- /.box-footer -->
					</form>
				</div><!-- /.box -->
			</section>

			<div id="modal_sn" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
							<h4 class="modal-title" id="myModalLabel">Serial number List</h4>
						</div>
						<div class="modal-content">
							<div class="modal-body"></div>
						</div>
					</div>
				</div>
			</div>

			';
			
			break;

			case "out":
			
			$key = "asdf$1011";
			$encrypt = "$_SESSION[id_user]";
			$id = base64_decrypt($encrypt,$key);

			echo '
			<section class="content">
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">Barang Keluar</h3>
						<div class="box-tools pull-right">
							<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
							<button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
						</div>
					</div><!-- /.box-header -->
					<form name="demo" method="post" action="'.$aksi.'?module=barang&act=update_out" class="form-horizontal form-bordered" id="form-barang" autocomplete="off" enctype="multipart/form-data">
					<div class="box-body">
						';
						/*
						<div class="form-group">
							<label class="col-md-3 control-label">Barang <span class="required">* </span></label>
							<div class="col-md-6">
								<select name="id_barang" id="barang" data-placeholder="Pilih barang..." class="form-control select2" required>';
								$result = mysqli_query($con,"select id, nama_barang,kode_barang from warehouse_barang  order by nama_barang");
								echo '<option value="">Select...</option>';
								while ($row = mysqli_fetch_assoc($result)){
									
										echo '<option value="' . $row['id'] . '">' . $row['nama_barang'] .'( '.$row['kode_barang'].'  )'. '</option>';
									
								}
								echo'
								</select>
								<div class="val-message"></div>	
								<input type="hidden" name="id_user" value="'.$id.'">
							</div>
						</div>*/


						echo '
						<div class="form-group">
							<label class="col-md-3 control-label">KODE BARANG<span class="required">* </span></label>
							<div class="col-md-6">
								<input type="text" onkeydown="scanproduk_out(this)"id="scan_kodebarang" name="scan_kodebarang" placeholder="SCAN KODE BARANG..." class="form-control" required>
								<h3 class="col-md-8 " id="scan-massage"></h3>
							</div>
						</div>
						
								<input type="hidden" name="id_barang" id="id_barang" placeholder="ID BARANG..." class="form-control" readonly >
							
						<div class="form-group">
							<label class="col-md-3 control-label">NAMA BARANG<span class="required">* </span></label>
							<div class="col-md-6">
								<input type="text" name="nama_barang" id="nama_barang" placeholder="NAMA BARANG..." class="form-control" disabled >
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label">CUSTOMER <span class="required">* </span></label>
							<div class="col-md-6">
								<input type="text" name="nama_customer" id="nama_customer" placeholder="Customer..." class="form-control" disabled >
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label">STOK <span class="required">* </span></label>
							<div class="col-md-6">
								<input type="text" name="stok_terkini" id="stok_terkini" placeholder="STOK..." class="form-control" disabled >
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label">DENGAN SERIAL NUMBER <span class="required">* </span></label>
							<div class="col-md-6">
								<input type="text" name="dengan_serial" id="dengan_serial"  placeholder="SERIAL..." class="form-control" readonly >
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label">Out <span class="required">* </span></label>
							<div class="col-md-6">
								<select name="out" id="out" data-placeholder="Pilih Out / In..." class="form-control select2" disabled required>
									
									
									<option value="out">Out</option>
								</select>
								<div class="val-message"></div>	
							</div>
						</div>

						<div class="form-group" id="form-serial" style="display:none;">
													<label class="col-md-3 control-label"> Pilih Serial Number </label>
													<div class="col-md-6">
														<select name="pilih_serial_number[]" id="pilih_serial_number" multiple="multiple" data-placeholder="Choose..." class="form-control select2" onchange="rubah_jumlah()" style="width: 100%;">

														</select>
														<div class="val-message"></div>
													</div>
												</div>
						
						<div class="form-group">
							<label class="col-md-3 control-label">Jumlah<span class="required">* </span></label>
							<div class="col-md-6">
								<input type="number" name="jumlah" id="jumlah" placeholder="Jumlah..." value="0" min="1" max="" class="form-control" required>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label">Requestor <span class="required">* </span></label>
							<div class="col-md-6">
								<select name="requestor" id="requestor" data-placeholder="Requestor." class="form-control " disabled required>
									
									
									
								</select>
								<div class="val-message"></div>	
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label">DOKUMEN<span class="required">* </span></label>
							<div class="col-md-6">
								<input type="hidden" name="dokumen" placeholder="Dokumen..." value="-" class="form-control" required>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label">KETERANGAN<span class="required">* </span></label>
							<div class="col-md-6">
								<input type="text" name="keterangan" placeholder="keterangan..." class="form-control" required>
							</div>
						</div>
						
					
						
						
						
						';

					echo '	
					</div><!-- /.box-body -->
					<div class="box-footer">
						<button type="button" class="btn btn-default" onclick="window.location.href = \'?module=barang\';">Cancel</button>
						<button type="submit" class="btn btn-primary pull-right">Save</button>
					</div><!-- /.box-footer -->
					</form>
				</div><!-- /.box -->
			</section>';
			
			break;

			case "out-berulang":
			
			$key = "asdf$1011";
			$encrypt = "$_SESSION[id_user]";
			$id = base64_decrypt($encrypt,$key);

			echo '
			<section class="content">
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">Barang Keluar berulang</h3>
						<div class="box-tools pull-right">
							<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
							<button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
						</div>
					</div><!-- /.box-header -->
					<form name="demo" method="post" action="'.$aksi.'?module=barang&act=update_out_berulang" class="form-horizontal form-bordered" id="form-barang" autocomplete="off" enctype="multipart/form-data">
					<div class="box-body">
						
						<div class="form-group">
							<label class="col-md-3 control-label">KODE BARANG<span class="required">* </span></label>
							<div class="col-md-6">
								<input type="text" onkeydown="scanproduk_out_berulang(this)"id="scan_kodebarang" name="scan_kodebarang" placeholder="SCAN KODE BARANG..." class="form-control" required>
								<h3 class="col-md-8 " id="scan-massage"></h3>
							</div>
						</div>
						
								<input type="hidden" name="id_barang" id="id_barang" placeholder="ID BARANG..." class="form-control" readonly >
							
						<div class="form-group">
							<label class="col-md-3 control-label">NAMA BARANG<span class="required">* </span></label>
							<div class="col-md-6">
								<input type="text" name="nama_barang" id="nama_barang" placeholder="NAMA BARANG..." class="form-control" disabled >
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label">CUSTOMER <span class="required">* </span></label>
							<div class="col-md-6">
								<input type="text" name="nama_customer" id="nama_customer" placeholder="Customer..." class="form-control" disabled >
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label">STOK <span class="required">* </span></label>
							<div class="col-md-6">
								<input type="text" name="stok_terkini" id="stok_terkini" placeholder="STOK..." class="form-control" disabled >
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label">DENGAN SERIAL NUMBER <span class="required">* </span></label>
							<div class="col-md-6">
								<input type="text" name="dengan_serial" id="dengan_serial"  placeholder="SERIAL..." class="form-control" readonly >
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label">Out <span class="required">* </span></label>
							<div class="col-md-6">
								<select name="out" id="out" data-placeholder="Pilih Out / In..." class="form-control select2" disabled required>
									
									
									<option value="out">Out</option>
								</select>
								<div class="val-message"></div>	
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label">Requestor <span class="required">* </span></label>
							<div class="col-md-6">
								<select name="requestor" id="requestor" data-placeholder="Requestor." class="form-control " disabled required>
									
									
									
								</select>
								<div class="val-message"></div>	
							</div>
						</div>

						<div class="form-group" id="form-serial" style="display:none;">
													<label class="col-md-3 control-label"> Pilih Serial Number </label>
													<div class="col-md-6">
														<select name="pilih_serial_number[]" id="pilih_serial_number" multiple="multiple" data-placeholder="Choose..." class="form-control select2" onchange="rubah_jumlah()" style="width: 100%;">

														</select>
														<div class="val-message"></div>
													</div>
												</div>
						
						<div class="form-group">
							<label class="col-md-3 control-label">Jumlah<span class="required">* </span></label>
							<div class="col-md-6">
								<input type="number" name="jumlah" id="jumlah" placeholder="Jumlah barang..." value="0" min="1" max="" class="form-control" required>
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-3 control-label">Pengulangan<span class="required">* </span></label>
							<div class="col-md-6">
								<input type="number" name="pengulangan" id="pengulangan" placeholder="pengulangan berapa kali..." value="" min="2" max="" class="form-control" required>
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-md-3 control-label">KETERANGAN<span class="required">* </span></label>
							<div class="col-md-6">
								<input type="text" name="keterangan" placeholder="keterangan..." class="form-control" required>
							</div>
						</div>
						
					
						
						
						
						';

					echo '	
					</div><!-- /.box-body -->
					<div class="box-footer">
						<button type="button" class="btn btn-default" onclick="window.location.href = \'?module=barang\';">Cancel</button>
						<button type="submit" class="btn btn-primary pull-right">Save</button>
					</div><!-- /.box-footer -->
					</form>
				</div><!-- /.box -->
			</section>';
			
			break;

			case "in":
			
			$key = "asdf$1011";
			$encrypt = "$_SESSION[id_user]";
			$id = base64_decrypt($encrypt,$key);

			echo '
			<section class="content">
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">Barang Masuk</h3>
						<div class="box-tools pull-right">
							<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
							<button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
						</div>
					</div><!-- /.box-header -->
					<form name="demo" method="post" action="'.$aksi.'?module=barang&act=update_in" class="form-horizontal form-bordered" id="form-barang" autocomplete="off" enctype="multipart/form-data">
					<div class="box-body">
						';
						/*
						<div class="form-group">
							<label class="col-md-3 control-label">Barang <span class="required">* </span></label>
							<div class="col-md-6">
								<select name="id_barang" id="barang" data-placeholder="Pilih barang..." class="form-control select2" required>';
								$result = mysqli_query($con,"select id, nama_barang,kode_barang from warehouse_barang  order by nama_barang");
								echo '<option value="">Select...</option>';
								while ($row = mysqli_fetch_assoc($result)){
									
										echo '<option value="' . $row['id'] . '">' . $row['nama_barang'] .'( '.$row['kode_barang'].'  )'. '</option>';
									
								}
								echo'
								</select>
								<div class="val-message"></div>	
								<input type="hidden" name="id_user" value="'.$id.'">
							</div>
						</div>*/


						echo '
						<div class="form-group">
							<label class="col-md-3 control-label">KODE BARANG<span class="required">* </span></label>
							<div class="col-md-6">
								<input type="text" onkeydown="scanproduk_in(this)"id="scan_kodebarang" name="scan_kodebarang" placeholder="SCAN KODE BARANG..." class="form-control" required>
								<h3 class="col-md-8 " id="scan-massage"></h3>
							</div>
						</div>
						
								<input type="hidden" name="id_barang" id="id_barang" placeholder="ID BARANG..." class="form-control" readyonly >
							
						<div class="form-group">
							<label class="col-md-3 control-label">NAMA BARANG<span class="required">* </span></label>
							<div class="col-md-6">
								<input type="text" name="nama_barang" id="nama_barang" placeholder="NAMA BARANG..." class="form-control" disabled >
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label">CUSTOMER <span class="required">* </span></label>
							<div class="col-md-6">
								<input type="text" name="nama_customer" id="nama_customer" placeholder="Customer..." class="form-control" disabled >
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label">STOK <span class="required">* </span></label>
							<div class="col-md-6">
								<input type="text" name="stok_terkini" id="stok_terkini" placeholder="STOK..." class="form-control" disabled >
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label">DENGAN SERIAL NUMBER <span class="required">* </span></label>
							<div class="col-md-6">
								<input type="text" name="dengan_serial" id="dengan_serial"  placeholder="SERIAL..." class="form-control" readonly >
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label">IN <span class="required">* </span></label>
							<div class="col-md-6">
								<select name="in" id="in" data-placeholder="Pilih  In..." class="form-control select2" disabled required>
									
									
									<option value="in">in</option>
								</select>
								<div class="val-message"></div>	
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label">Requestor <span class="required">* </span></label>
							<div class="col-md-6">
								<select name="requestor" id="requestor" data-placeholder="Requestor." class="form-control " disabled required>
									
									
									
								</select>
								<div class="val-message"></div>	
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-md-3 control-label">Jumlah<span class="required">* </span></label>
							<div class="col-md-6">
								<input type="number" name="jumlah" id="jumlah_in" onchange="rubah_jumlah_in()" placeholder="Jumlah..." value="1" min="1" max="" class="form-control" required>
							</div>
						</div>

						<div class="form-group grup_sn" style="display:none;">
							<label class="col-md-3 control-label">SERIAL NUMBER<span class="required">* </span></label>
							<div class="col-md-6">
								<input type="text" name="serial_number_in[1]" id="in_sn1" placeholder="serial number 1" class="form-control" required disabled>
							
						

						';
						//$i=2;
						for($i=2;$i <= 50; $i++){
							echo '<input type="text" name="serial_number_in['.$i.']" id="in_sn'.$i.'" placeholder="serial number '.$i.'" class="form-control" style="display:none;" required disabled>';
						}


						echo '
							</div>
						</div>
						

						<div class="form-group">
							<label class="col-md-3 control-label">DOKUMEN<span class="required">* </span></label>
							<div class="col-md-6">
								<input type="hidden" name="dokumen" placeholder="Dokumen..." value="-" class="form-control" required>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label">KETERANGAN<span class="required">* </span></label>
							<div class="col-md-6">
								<input type="text" name="keterangan" placeholder="keterangan..." class="form-control" required>
							</div>
						</div>
						
					
						
						
						
						

						
					</div><!-- /.box-body -->
					<div class="box-footer">
						<button type="button" class="btn btn-default" onclick="window.location.href = \'?module=barang\';">Cancel</button>
						<button type="submit" class="btn btn-primary pull-right">Save</button>
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
	function create_dengan_serial_y(){
		//alert('ok');
		var serial = $('#dengan_serial_y').val();

		 $('.create_stok_awal').val(0);
		 $('.create_stok_awal').prop('readonly', true);
		 

		//alert(serial);
		

	}
	function create_dengan_serial_n(){
		//alert('ok');
		var serial = $('#dengan_serial_n').val();
		 $('.create_stok_awal').val(1);
		 $('.create_stok_awal').prop('readonly', false);
		//alert(serial);
		

	}
	function rubah_jumlah(){
		//alert('ok');
		var count = $("#pilih_serial_number :selected").length;
		$('#jumlah').val(count);

	}

	function rubah_jumlah_in(){
		//alert('ok');
		var dengan_serial = $('#dengan_serial').val();
		
		var jumlah = $('#jumlah_in').val();

		for (i = 2; i <= 50; i++) {
		  $('#in_sn'+i).hide();
		  $('#in_sn'+i).prop('disabled', true);
		}

		if(dengan_serial == 'Y'){
			for (i = 2; i <= jumlah; i++) {
			  $('#in_sn'+i).show();
			  $('#in_sn'+i).prop('disabled', false);
			}
		}

		

	}
	
	function scanproduk_in(ele) {
    	
	    if(event.key === 'Enter') {
	    	//alert('asda');
	    	var kode_barang = $("#scan_kodebarang").val();
	    	//var nama_barang = $("#nama_barang").val();
	    	//alert(kode_barang);
	    	$('#jumlah_in').prop('max');
	    	$('#jumlah').val(0);

	    	

			$.ajax({
				type: 'POST',
				dataType: 'json',
				data: {
					kode : kode_barang
				},
				url: 'module/mod_warehouse-barang/barang_action.php?module=barang&act=scan_kodebarang',
				success: function (result) {
					//alert('asda');
					//$('#intercityasal').val(result['intercity']);
					if(result.success > 0){
						
						$("#id_barang").val(result.barang.id);
						$("#nama_barang").val(result.barang.nama_barang);
						$("#nama_customer").val(result.barang.nama_customer);
						$("#stok_terkini").val(result.barang.stok_terkini);
						$("#dengan_serial").val(result.barang.dengan_serial);
						$("#scan-massage").text('');

						$('select#requestor').empty().prop('disabled', false);
						
						$.each(result.user, function(i, value) {
							$('select#requestor').append(
							  $('<option>').text(value.username).attr('value', value.username)
							);
						});
						
						
						if(result.barang.dengan_serial == 'Y'){
							$('#in_sn1').prop('disabled', false);
							$('select#pilih_serial_number').prop('disabled', false);
							$("#form-serial").show();
							$('#jumlah').prop('readonly', true);
							$('.grup_sn').show();

							$('#jumlah_in').attr('max',50);

							
							//$('select#pilih_serial_number option').prop('selected', false);
							$//('#pilih_serial_number option:selected').prop("selected", false); 

							//$('select[multiple]').multiselect( 'reload' );

							$('select#pilih_serial_number').empty();


							//mulai result serial number
							$.ajax({
								type: 'POST',
								dataType: 'json',
								data: {
									id_barang: result.barang.id
								},
								url: 'module/mod_warehouse-barang/barang_action.php?module=barang&act=serial_number_result',
								success: function (result) {
									$('select#data_pengiriman_inv').empty();
									$.each(result, function(i, value) {
										$('select#pilih_serial_number').append($('<option>').text(value.serial_number).attr('value', value.id));
									});
								}
							});
							//selesai result serial number


						}else{
							$("#form-serial").hide();
							$('#jumlah').prop('readonly', false);
							$('select#pilih_serial_number').empty();
							$('select#pilih_serial_number').prop('disabled', true);
							//$("#pilih_serial_number").multiselect('refresh');
						}
						
					}else{

						$("#scan-massage").text(result.message);
						$("#scan-massage").css({ 'color': 'red'});

						$("#form-serial").hide();
						$('select#pilih_serial_number').empty();
						$('select#pilih_serial_number').prop('disabled', true);

						$("#id_barang").val('');
						$("#nama_barang").val('');
						$("#nama_customer").val('');
						$("#stok_terkini").val('');
						$("#dengan_serial").val('');
						

					}

						//$("#inputawb").val('');
						//$("#scan-loading").hide();

						//$('#inputawb').prop("disabled", false);

					//$('#intercityasal').val(result['intercity']);
					//alert(result.kota_asal);

				}
			});

	        

	        


	    }
	    
	}


	function scanproduk_out(ele) {
    	
	    if(event.key === 'Enter') {
	    	//alert('asda');
	    	var kode_barang = $("#scan_kodebarang").val();
	    	//var nama_barang = $("#nama_barang").val();
	    	//alert(kode_barang);

	    	$('#jumlah').val(0);

	    	

			$.ajax({
				type: 'POST',
				dataType: 'json',
				data: {
					kode : kode_barang
				},
				url: 'module/mod_warehouse-barang/barang_action.php?module=barang&act=scan_kodebarang',
				success: function (result) {
					//alert('asda');
					//$('#intercityasal').val(result['intercity']);
					if(result.success > 0){
						
						$("#id_barang").val(result.barang.id);
						$("#nama_barang").val(result.barang.nama_barang);
						$("#nama_customer").val(result.barang.nama_customer);
						$("#stok_terkini").val(result.barang.stok_terkini);
						$("#dengan_serial").val(result.barang.dengan_serial);
						$("#scan-massage").text('');
						
						$('#jumlah').attr('max',result.barang.stok_terkini);

						$('select#requestor').empty().prop('disabled', false);
						
						$.each(result.user, function(i, value) {
							$('select#requestor').append(
							  $('<option>').text(value.username).attr('value', value.username)
							);
						});




						if(result.barang.dengan_serial == 'Y'){
							$('select#pilih_serial_number').prop('disabled', false);
							$("#form-serial").show();
							$('#jumlah').prop('readonly', true);
							

							
							//$('select#pilih_serial_number option').prop('selected', false);
							$//('#pilih_serial_number option:selected').prop("selected", false); 

							//$('select[multiple]').multiselect( 'reload' );

							$('select#pilih_serial_number').empty();


							//mulai result serial number
							$.ajax({
								type: 'POST',
								dataType: 'json',
								data: {
									id_barang: result.barang.id
								},
								url: 'module/mod_warehouse-barang/barang_action.php?module=barang&act=serial_number_result',
								success: function (result) {
									$('select#data_pengiriman_inv').empty();
									$.each(result, function(i, value) {
										$('select#pilih_serial_number').append($('<option>').text(value.serial_number).attr('value', value.id));
									});
								}
							});
							//selesai result serial number


						}else{
							$("#form-serial").hide();
							$('#jumlah').prop('readonly', false);
							$('select#pilih_serial_number').empty();
							$('select#pilih_serial_number').prop('disabled', true);
							//$("#pilih_serial_number").multiselect('refresh');
						}
						
					}else{

						$("#scan-massage").text(result.message);
						$("#scan-massage").css({ 'color': 'red'});

						$("#form-serial").hide();
						$('select#pilih_serial_number').empty();
						$('select#pilih_serial_number').prop('disabled', true);

						$("#id_barang").val('');
						$("#nama_barang").val('');
						$("#nama_customer").val('');
						$("#stok_terkini").val('');
						$("#dengan_serial").val('');
						

					}

						//$("#inputawb").val('');
						//$("#scan-loading").hide();

						//$('#inputawb').prop("disabled", false);

					//$('#intercityasal').val(result['intercity']);
					//alert(result.kota_asal);

				}
			});

	        

	        


	    }
	    
	}

	function scanproduk_out_berulang(ele) {
    	
	    if(event.key === 'Enter') {
	    	//alert('asda');
	    	var kode_barang = $("#scan_kodebarang").val();
	    	//var nama_barang = $("#nama_barang").val();
	    	//alert(kode_barang);

	    	$('#jumlah').val(0);

	    	

			$.ajax({
				type: 'POST',
				dataType: 'json',
				data: {
					kode : kode_barang
				},
				url: 'module/mod_warehouse-barang/barang_action.php?module=barang&act=scan_kodebarang_berulang',
				success: function (result) {
					//alert('asda');
					//$('#intercityasal').val(result['intercity']);
					if(result.success > 0){
						
						$("#id_barang").val(result.barang.id);
						$("#nama_barang").val(result.barang.nama_barang);
						$("#nama_customer").val(result.barang.nama_customer);
						$("#stok_terkini").val(result.barang.stok_terkini);
						$("#dengan_serial").val(result.barang.dengan_serial);
						$("#scan-massage").text('');
						
						$('#jumlah').attr('max',result.barang.stok_terkini);
						
							$("#form-serial").hide();
							$('#jumlah').prop('readonly', false);
							$('select#pilih_serial_number').empty();
							$('select#pilih_serial_number').prop('disabled', true);
							//$("#pilih_serial_number").multiselect('refresh');
						
						$('select#requestor').empty().prop('disabled', false);
						
						$.each(result.user, function(i, value) {
							$('select#requestor').append(
							  $('<option>').text(value.username).attr('value', value.username)
							);
						});

						
					}else{

						$("#scan-massage").text(result.message);
						$("#scan-massage").css({ 'color': 'red'});

						$("#form-serial").hide();
						$('select#pilih_serial_number').empty();
						$('select#pilih_serial_number').prop('disabled', true);

						$("#id_barang").val('');
						$("#nama_barang").val('');
						$("#nama_customer").val('');
						$("#stok_terkini").val('');
						$("#dengan_serial").val('');
						

					}

						//$("#inputawb").val('');
						//$("#scan-loading").hide();

						//$('#inputawb').prop("disabled", false);

					//$('#intercityasal').val(result['intercity']);
					//alert(result.kota_asal);

				}
			});

	        

	        


	    }
	    
	}



</script>