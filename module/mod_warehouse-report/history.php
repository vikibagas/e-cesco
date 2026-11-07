<section class="content-header">
	<h1>Report History Product<small>report</small></h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-home"></i> Home</a></li>
		<li><a href="#">Reports</a></li>
		<li class="active">History Produck</li>
	</ol>
</section>
<?php

if( isset($_GET['id_barang']) && $_GET['id_barang'] > 0){
//$id_kustomer = $_GET['id_kustomer'];
$id_barang = $_GET['id_barang'];

$product = mysqli_query($con,"select nama_barang,stok_terkini,kode_barang,satuan,stok_awal_masuk from warehouse_barang where id=$id_barang");
$cek_product = mysqli_num_rows($product);
$r = mysqli_fetch_assoc($product);

$key = "asdf$1011";
$path_gambar="module/mod_warehouse-barang/foto_barang/".$r['kode_barang'].".jpg";
$aksi="module/mod_warehouse-report/";

if($cek_product > 0){
?>

<section class="content-header">

</section>

<div class="row">
		<div class="col-md-12">
			<div class="box box-primary">
                <div class="box-header">
					<div class="row">
					</div>
				</div>
				<div class="box-body">
					<h4> History <?php echo $r['nama_barang'].' ( '. $r['kode_barang'].' )'; ?></h4>
					<div class="row">
						<div class="col-lg-3 col-xs-6">
							<!-- small box -->
							
							<div class="small-box btn-danger">
								<div class="inner">
									<h2>
									
									<?php echo $r['stok_terkini'].' '.$r['satuan']; ?>
									
									</h2>
									<p>Stok Terkini <?php echo $r['nama_barang']; ?></p>
				                </div>
				                <div class="icon">
									<i class="ion ion-android-time"></i>
				                </div>
				                
							</div>
						</div>
						<div class="col-lg-3 col-xs-6">
							<?php
								if($u['level'] == "admin"){
									?>

								<div class="small-box bg-aqua">
								<div class="inner">
									<h2>
									
									<?php echo $r['stok_awal_masuk'].' '.$r['satuan']; ?> <a href="#" data-jumlah="<?php echo $r['stok_awal_masuk']; ?>" onclick="update_stok_awal(this);"  data-toggle="modal" data-target="#modal_stokawal" ><i class="fa fa-pencil"></i></a>
									
									</h2>
									<p>Stok awal <?php echo $r['nama_barang']; ?></p>
				                </div>
				                <div class="icon">
									<i class="ion ion-android-time"></i>
				                </div>
				                
							</div>
						</div>

								<?php
								}
							?>


						<!-- ./col -->
						<div class="col-lg-3 col-xs-6">
							<?php echo '<img style="width:120px; height:120px;" src="'.$path_gambar.'">'; ?>
						</div>
					</div>
					<table id="report" class="table table-striped table-bordered table-hover " width="100%" cellspacing="0" style="white-space:nowrap;">
						<thead>
						
								<tr style="text-transform: uppercase;">
									<th>No</th>
									<th>Tanggal</th>
									<th>Out/In</th>
									<th>Jumlah</th>
									<th>Stok sebelum</th>
									<th>Stok menjadi</th>
									<th>Keterangan</th>
									<th>Serial number</th>
									<th>Requestor</th>
									<th>AWB</th>
									<th>Oleh</th>
								</tr>
						</thead>
						<tbody>
						<?php

						
						$cari = "select id,out_in,id_produk,jumlah,stok_sebelum,stok_menjadi,x_user(created_by) as nama_user,x_userclient(created_by_cust) as nama_user_client,created_by,created_date,dokumen,awb,id_pengiriman,catatan, x_noawb(id_pengiriman) as no_awb,serial_number,requestor
								
							FROM warehouse_history
								
							where id_produk='$id_barang' 
								
							order by created_date desc, id desc

								";
						
						
						$sql = mysqli_query($con, $cari);
						$no = 1;
						$key = "asdf$1011";
						function delete_button($link,$link2){
							//return '<a href="'.$link.'" class="btn btn-primary"> delete</a>';

							return '<button  class="btn btn-danger btn delete_jumlah_history" onclick="delete_data_history(this);" data-option="'.$link2.'" data-id="'.$link.'">delete</button>';
						}
						function update_button($link,$link2,$link3){
							return '<button  class="btn btn-warning btn edit_jumlah_history" onclick="update_data_history(this);" data-toggle="modal" data-target="#modal_update" data-id="'.$link.'" data-option="'.$link2.'" data-outin="'.$link3.'">edit</button>';
						}

						function awb_tampil($link1,$row1,$row2){
							if ($row1 != '' || $row2 != ''){
								$awb_tampil = ($row1 != '' ? $row1 : $row2);
							}else{
								$awb_tampil =  '<button  class="btn btn-warning btn input_awb" onclick="input_awb(this);" data-toggle="modal" data-target="#modal_inputawb" data-id="'.$link1.'" >Input AWB</button>';
							}

							return $awb_tampil;
						}
						
						


						while($row = mysqli_fetch_assoc($sql)){
							
							
							echo'
							<tr style="text-transform: uppercase;">
								<td width="20px">'.$no.'</td>
								
								<td width="20px">'.$row['created_date'].'</td>
								<td width="20px">'.$row['out_in'].'</td>
								<td width="20px">'.$row['jumlah'].'</td>
								<td width="20px">'.$row['stok_sebelum'].'</td>
								<td width="20px">'.$row['stok_menjadi'].'</td>
								<td width="20px">'.$row['catatan'].'</td>
								<td width="20px">'.$row['serial_number'].'</td>
								<td width="20px">'.$row['requestor'].'</td>
								<td width="20px">'.($row['out_in'] == 'out' ? awb_tampil($row['id'],$row['no_awb'],$row['awb']) : '-') .'</td>
								<td width="20px">'.($row['created_by'] > 0 ? $row['nama_user'] : $row['nama_user_client']).' '.($u['level'] == 'admin' ? delete_button($aksi.'report_action.php?module=history&act=delete&id='.base64_encrypt($row['id'],$key),$row['jumlah']) : '' ).

								($u['level'] == 'admin' ? update_button($row['id'],$row['jumlah'],$row['out_in']) : '' ). '</td>
								
							</td>
								';
								$no++;
						}
						?>
						</tbody>
					</table>
				</div><!-- /.box-body -->
			</div><!-- /.box -->
		</div>
	</div><!-- /.row -->

			<div id="modal_update" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
							<h4 class="modal-title" id="myModalLabel">Ubah jumlah</h4>
						</div>
						<div class="modal-content">
							<div class="modal-body">
								
								<form action="module/mod_warehouse-report/report_action.php?module=history&act=update" id="frmBayarInv" method="POST">					
									<input type="hidden" class="form-control" id="id_history" name="id_history" required="">	
									<input type="hidden" class="form-control" id="id_produk" name="id_produk" value="<?php echo $_GET['id_barang']; ?>" required="">
									


									<?php
										$p = mysqli_query($con,"select id,id_produk,jumlah from warehouse_history where id=$id_barang");
										$row_p = mysqli_num_rows($p);
									?>
									<div class="form-group" style="padding-bottom: 25px;">
										<label class="col-md-3 control-label">Jumlah data <span class="required">* </span></label>
										<div class="col-md-8">
											<div class="input-group">
												<input type="text" class="form-control" id="jumlah_menjadi" name="jumlah_menjadi" required="">
												<input type="hidden" class="form-control" id="jumlah_sebelum" name="jumlah_sebelum" required="" >
												<input type="hidden" class="form-control" id="outin" name="out_in" value="" required="">	
											</div>
										</div>
									</div>
									
								
							</div>
							<div class="modal-footer">
										<button class="btn btn-success" type="submit" id="btnsubmitrubahjumlah">Submit</button>
										<button type="reset" class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Cancel</button>
									</div>
								</form>	

						</div>
					</div>
				</div>
			</div>

			<div id="modal_stokawal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
							<h4 class="modal-title" id="myModalLabel">Ubah Stok Awal</h4>
						</div>
						<div class="modal-content">
							<div class="modal-body">
								
								<form action="module/mod_warehouse-report/report_action.php?module=history&act=update_stok_awal" id="frmBayarInv" method="POST">					
									
									<input type="hidden" class="form-control" id="id_produk" name="id_produk" value="<?php echo $_GET['id_barang']; ?>" required="">
									


									
									<div class="form-group" style="padding-bottom: 25px;">
										<label class="col-md-3 control-label">Jumlah Stok Awal <span class="required">* </span></label>
										<div class="col-md-8">
											<div class="input-group">
												<input type="text" class="form-control" id="stok_awal_menjadi" name="stok_awal_menjadi" required="">
												<input type="hidden" class="form-control" id="stok_awal_sebelum" name="stok_awal_sebelum" required="" >
												
											</div>
										</div>
									</div>
									
								
							</div>
							<div class="modal-footer">
										<button class="btn btn-success" type="submit" id="">Submit</button>
										<button type="reset" class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Cancel</button>
									</div>
								</form>	

						</div>
					</div>
				</div>
			</div>


			<div id="modal_inputawb" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
							<h4 class="modal-title" id="myModalLabel">INPUT AWB</h4>
						</div>
						<div class="modal-content">
							<div class="modal-body">
								
								<form action="module/mod_warehouse-report/report_action.php?module=history&act=update_awb" id="frmBayarInv" method="POST">					
									<input type="hidden" class="form-control" id="id_history" name="id_history" required="">	
									<input type="hidden" class="form-control" id="id_produk" name="id_produk" value="<?php echo $_GET['id_barang']; ?>" required="">	

									<?php
										$p = mysqli_query($con,"select id,id_produk,jumlah from warehouse_history where id=$id_barang");
										$row_p = mysqli_num_rows($p);
									?>
									<div class="form-group" style="padding-bottom: 25px;">
										<label class="col-md-3 control-label">Input AWB<span class="required">* </span></label>
										<div class="col-md-8">
											
												
												<input type="text" class="form-control" id="no_awb" name="no_awb" required="" >

												<a class="btn btn-sm btn-primary" id="cek_awb" onclick="cek_awb();">cek awb</a><br>
												<input type="hidden"  class="form-control" id="id_awb" name="id_awb" required >
												<br>

												<input type="checkbox" id="tanpa_awb" name="tanpa_awb" value="Y" onchange="checktanpa_awb();">Checklist jika tanpa AWB
												<input type="text" style="display:none;" placeholder="contoh invoice gojek: INV/1232132141" class="form-control" id="dokumen_tanpa_awb" name="dokumen_tanpa_awb"><br>
											
										</div>
									</div>
									
								
							</div>
							<div class="modal-footer">
										<button class="btn btn-success" id="submit_setelah_cek_awb" disabled  type="submit" >Submit</button>
										<button type="reset" class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Cancel</button>
									</div>
								</form>	

						</div>
					</div>
				</div>
			</div>


	<script>
	function update_data_history(identifier) {
		
		var no = $(identifier).data('id');
		var jml = $(identifier).data('option');
		var out_in = $(identifier).data('outin');
		
		$("#modal_update #id_history").val(no);
		$("#modal_update #jumlah_menjadi").val(jml);
		$("#modal_update #jumlah_sebelum").val(jml);
		$("#modal_update #outin").val(out_in);
		
	}

	function update_stok_awal(identifier) {
		
	
		var jml = $(identifier).data('jumlah');
		
		
	
		$("#modal_stokawal #stok_awal_menjadi").val(jml);

		$("#modal_stokawal #stok_awal_sebelum").val(jml);		
		
		
	}

	function input_awb(identifier) {
		
		
		var no = $(identifier).data('id');
		
		$("#modal_inputawb #id_history").val(no);
		
		
	}

	function delete_data_history(identifier){
		var jml = $(identifier).data('option');
		var link = $(identifier).data('id');
		if (confirm("Yakin menghapus data keluar atau masuk dengan jumlah "+jml+ " ini?")) {
       	 	window.location.href = link;
	    }
	    return false;
	}

	function delete_data_history(identifier){
		var jml = $(identifier).data('option');
		var link = $(identifier).data('id');
		if (confirm("Yakin menghapus data keluar atau masuk dengan jumlah "+jml+ " ini?")) {
       	 	window.location.href = link;
	    }
	    return false;
	}

	function cek_awb() {
		//alert('tes');

	    	var no_awb = $("#no_awb").val();
			$.ajax({
				type: 'POST',
				dataType: 'json',
				data: {
					awb : no_awb
				},
				url: 'module/mod_warehouse-report/report_action.php?module=history&act=cek_awb',
				success: function (result) {
					//alert('tes');
					//alert('asda');
					//$('#intercityasal').val(result['intercity']);
					if(result.success > 0){
						$("#id_awb").val(result.awb.id_pengiriman);	
						alert('AWB OKE,Silahkan klik submit');		
						$("#submit_setelah_cek_awb").prop('disabled', false);		
					}else{
						alert(result.message);
					}


				}
			});
	}
	function checktanpa_awb(){
		//alert('tanpa_awb');
		if($('#tanpa_awb').is(":checked")){
			$("#dokumen_tanpa_awb").show();
			$("#no_awb").hide();
			$("#cek_awb").hide();
			$('#no_awb').removeAttr('required');
			$('#dokumen_tanpa_awb').prop("required", true);
			$('#submit_setelah_cek_awb').removeAttr('disabled');
		}else{
			$("#dokumen_tanpa_awb").hide();
			$("#no_awb").show();
			$("#cek_awb").show();
			$('#dokumen_tanpa_awb').removeAttr('required');
			$('#no_awb').prop("required", true);
			$('#submit_setelah_cek_awb').prop("disabled", true);
		}
			
			
			var dokumen_tanpa_awb = $("#dokumen_tanpa_awb").val();
	}
</script>
<?php
}
else{
	echo 'Tidak ada history';
}
}else{
	echo 'ga ok';
}
?>