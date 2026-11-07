<section class="content-header">
	<h1>Destroy Barang<small></small></h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-home"></i> Home</a></li>
		<li><a href="#">Warehouse</a></li>
		<li class="active">Destroy barang</li>
	</ol>
</section>
<?php
if(!isset($_SESSION)){
    session_start();
}

if (empty($_SESSION['usernamex']) AND empty($_SESSION['passwordx'])){
	header('location:../application/login.php');
}
else{
	
		$aksi	= "module/mod_warehouse-report/report_action.php";		
			$key = "asdf$1011";
			$encrypt = $_GET['id_barang'];
			$id = base64_decrypt($encrypt,$key);

			

			$cari = "select id,kode_barang,nama_barang,serial_number, pemilik_customer,x_namakustomer(pemilik_customer) as nama_kustomer,stok_awal_masuk, stok_terkini,satuan,x_namarak(id_rak) as rak,created_date,expired_date,expired_date_action,
							x_namadepartement(departement) as nama_departement,dengan_serial
								
							FROM warehouse_barang
								
							where id=".$id."
								
							limit 1

								";

								
						
						
						$sql = mysqli_query($con, $cari);

						$r = mysqli_fetch_assoc($sql);

			echo '
			<section class="content">
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">Barang Keluar destroy</h3>
						<div class="box-tools pull-right">
							<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
							<button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
						</div>
					</div><!-- /.box-header -->
					<form name="demo" method="post" action="'.$aksi.'?module=barang&act=update_out" class="form-horizontal form-bordered" id="form-barang" autocomplete="off" enctype="multipart/form-data">
					<div class="box-body">
						';
						


						echo '
						<div class="form-group">
							<label class="col-md-3 control-label">KODE BARANG<span class="required">* </span></label>
							<div class="col-md-6">
								<input type="text" id="kode_barang" name="kode_barang" placeholder="kode barang" value="'.$r['kode_barang'].'" class="form-control"  readonly>
								<h3 class="col-md-8 " id="scan-massage"></h3>
							</div>
						</div>
						
								<input type="hidden" name="id_barang" id="id_barang" placeholder="ID BARANG..." class="form-control" value="'.$id.'" readonly >
							
						<div class="form-group">
							<label class="col-md-3 control-label">NAMA BARANG<span class="required">* </span></label>
							<div class="col-md-6">
								<input type="text" name="nama_barang" id="nama_barang" placeholder="NAMA BARANG..." class="form-control" value="'.$r['nama_barang'].'" disabled >
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label">CUSTOMER <span class="required">* </span></label>
							<div class="col-md-6">
								<input type="text" name="nama_customer" id="nama_customer" placeholder="Customer..." class="form-control" disabled value="'.$r['nama_kustomer'].'">
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label">STOK <span class="required">* </span></label>
							<div class="col-md-6">
								<input type="text" name="stok_terkini" id="stok_terkini" placeholder="STOK..." class="form-control" disabled value="'.$r['stok_terkini'].'">
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label">DENGAN SERIAL NUMBER <span class="required">* </span></label>
							<div class="col-md-6">
								<input type="text" name="dengan_serial" id="dengan_serial"  placeholder="SERIAL..." class="form-control" readonly value="'.$r['dengan_serial'].'">
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
								<input type="number" name="jumlah" id="jumlah" placeholder="Jumlah..." value="1" min="1" max="'.$r['stok_terkini'].'" class="form-control" required>
							</div>
						</div>
						
						
						<div class="form-group">
							<label class="col-md-3 control-label">KETERANGAN<span class="required">* </span></label>
							<div class="col-md-6">
								<input type="text" name="keterangan" placeholder="keterangan..." class="form-control" value="Destroy oleh '.$_SESSION['usernamex'].'" required>
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
			
		

			
			
		
}
?>
<script>
	


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