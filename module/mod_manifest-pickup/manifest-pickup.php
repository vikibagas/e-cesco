<section class="content-header">
	<h1>Manifest<small>pickup</small></h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-home"></i> Home</a></li>
		<li><a href="#">Application</a></li>
		<li>Manifest</li>
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
	if($u['level']=='admin' OR $u['level']=='operator' OR $u['level']=='finance' OR $u['level']=='intercity' OR $u['level']=='network' OR $u['level']=='inbound' OR ($u['level']=='agent' and $u['cabang_cesco'] == 'Y') OR $u['level']=='domex'  || strpos($u['menu_tambahan'], 'manifest') !== false or $u['level'] == 'admin_network'){
		
		$aksi	= "module/mod_manifest-pickup/manifest-pickup_action.php";
		
		$act = !empty($_GET['act']) ? $_GET['act'] : '';
		
		switch($act){
			
			default:

			
				
				

			if (isset($_SESSION['pesan']) && $_SESSION['pesan'] == 'success') 
				{
					echo'
					<div class="callout callout-success pesan">
						<h4><i class="icon fa fa-check"></i> Success!</h4>
						<p>Success</p>
					</div>';
						unset($_SESSION['pesan']);
				}
				
				if(isset($_SESSION['pesan']) && $_SESSION['pesan'] == 'null')
				{
					echo'
					<div class="callout callout-danger pesan">
						<h4><i class="icon fa fa-warning"></i> Failed!</h4>
						<p>No One Airwaybill Selected</p>
					</div>';
						unset($_SESSION['pesan']);
				}

				
			echo'

			<form action="'.$aksi.'?module=manifest-pickup&act=assignment-all" id="form-all" method="POST">
			<section class="content">
				<div class="row">
					<div class="col-xs-12">
						<div class="box-header">
								<div class="row">
									<div class="col-md-6">
										<div class="btn-group">
											';
											if ($u['level'] != 'finance'){
												echo '<button class="btn btn-sm btn-primary" onclick="window.location.href = \'?module=manifest-pickup&act=create\';">Create <i class="fa fa-plus"></i></button>';
											}
											echo '
										</div>
									</div>
									
								</div>
							</div>
						<div class="box box-primary">
							<div class="box-body">
							<table id="manifestp" class="table table-bordered table-striped table-hover" width="100%" cellspacing="0" style="white-space:nowrap;">
								<thead>
									<tr>
										<th>select</th>
										<th>Airwaybill</th>
										<th>Booking Date -- Sla Booking</th>
										<th>Customer</th>
										<th>Kota Asal</th>
										<th>Kota Tujuan</th>
										<th>Service</th>';
										if($u['level']=='admin' || $u['level']=='operator' || $u['level']=='intercity' || $u['level']=='network' || $u['level']=='agent' || $u['level']=='domex' OR $u['level']=='inbound' OR $u['level']=='finance' OR $u['level']=='admin_network'){
											echo'<th>Action</th>';
										}
										echo'
									</tr>
								</thead>
								<tbody>
								</tbody>
							</table>
							</div>
						</div>
					</div>
				</div>
				<div class="form-group" id="assignment-all">					
					<div class="col-sm-4">
						<select name="kurir_all" class="form-control">
							';
							$result = mysqli_query($con,"select id_user, namalengkap, id_kustomer, area from user where (level='kurir' or level='driver') and blokir='N'");
							while ($row = mysqli_fetch_assoc($result)) {
								echo '<option value="' . $row['id_user'] . '">'.strtoupper($row['namalengkap']).'</option>';
							}
							echo '
						</select>
					</div>
					<button class="btn btn-danger" type="submit" id="submitall"> Assignment All</button>
				</div>
			</form>
			</section>

			<div id="pickup-assignment" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
							<h4 class="modal-title" id="myModalLabel">Pickup Assignment</h4>
						</div>
						<div class="modal-content">
							<form action="'.$aksi.'?module=manifest-pickup&act=update" name="modal_popup" method="POST" id="assignment">
								<div class="modal-body"></div>
								<div class="modal-footer">
									<button class="btn btn-success" id="btnSave" type="submit">Confirm</button>
									<button type="reset" class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Cancel</button>
								</div>
							</form>


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
			</div>';
			
			break;

			case "create":

				$tanggal = tgl_indo(date("Y-m-d"));

			echo '<section class="content">
					<div class="box box-primary">
						<div class="box-header with-border">
							<h2 class="box-title"><b>Create Manifest</b></h2>
							
						</div><!-- /.box-header -->
						<form method="post" action="'.$aksi.'?module=manifest-pickup&act=createmanifest" class="form-horizontal form-bordered" id="form-pickup" autocomplete="off">
						
						<div class="box-body">
							
							<form class="form-horizontal form-bordered" method="post" action="'.$aksi.'?module=manifest&act=update">

								
								
								<div class="box-body">

									
									<div class="row">
										<div class="col-md-3">
											<div class="form-group">
												<label class="col-md-4 control-label">Date</label>
												<div class="col-md-8">
													<div class="input-group">
														<div class="input-group-addon">
															<i class="fa fa-calendar"></i>
														</div>
														
														<input type="text" id="tanggal" name="tanggal" class="form-control" value="'.$tanggal.'">
														<input type="hidden" class="form-control" value="0"  id="no-table">
													</div>
												</div>
											</div>
										</div>
										<div class="col-md-3">
											<div class="form-group">
												<label class="col-md-5 control-label">Manifest Code</label>
												<div class="col-md-7">
													<input type="text" class="form-control" value="" id="kode-manifest" readonly>
													<input type="hidden" name="id_manifest" id="id-manifest" value="">
												</div>
											</div>
										</div>
										<div class="col-md-3">
											<div class="form-group">
												<label class="col-md-5 control-label">Manifest By</label>
												<div class="col-md-7">
													<input type="text" class="form-control" value="'.$u['namalengkap'].'" readonly>
												</div>
											</div>
										</div>
										
									</div>
									<div class="row">
										
										<div class="col-md-4">
											<div class="form-group">
												<label class="col-md-4 control-label">Manifest To Courier</label>
												<div class="col-md-8">
													<select name="id_courier" id="id_courier" data-placeholder="Pilih Kurir..." class="form-control select2" >';
														$result = mysqli_query($con,"select id_user, namalengkap, x_namacabang(id_cabang) as nama_cabang from user where blokir !='Y' and (level = 'kurir' or level='driver' or level='agent' ) order by namalengkap");
														echo '<option value="">Select...</option>';
														while ($row = mysqli_fetch_assoc($result)) {
															
																echo '<option value="' . $row['id_user'] . '">' . $row['namalengkap'] .' ('.$row['nama_cabang']. ')</option>';
															
														}
														echo'
													</select>
												</div>
											</div>
										</div>
										
										<input type="button" class="btn btn-primary" id="buttonnext" onclick="cekmanifest()" value="next">
										
									</div>
									<hr>
									<div class="row">
										<div class="col-md-12">
											<div class="form-group">
												<label class="col-md-4 control-label">Scan Airwaybill Number</label>
												<div class="col-md-4">
												
   

													<input type="text" id="inputawb" class="form-control inputawb" name="inputawb" value="" onkeydown="scanawb(this)" placeholder="Enter Airwaybill" disabled> 

													
												
												</div>
												<div class="col-md-4"><img src="dist/img/Loading_icon.gif" style="width:15%; display:none;" id="scan-loading"></div>
											</div>
										</div>

									</div>
									
									<div class="row">
										<div class="col-md-12">
										<h3 class="col-md-4" ></h3>
											<h3 class="col-md-8 " id="scan-massage"></h3>

										</div>
									</div>

									<div class="table-responsive">
									  			
									<table id="manifest-pickupcreate" class="table table-bordered table-striped table-hover" width="100%" cellspacing="0" style="white-space:nowrap;">
										<thead>
											<tr>
												<th>No</th>
												<th>Airwaybill</th>
												<th>Customer</th>
												<th>Kota Asal</th>
												<th>Kota Tujuan</th>
												<th>Service</th>																													
												
											</tr>
										</thead>
										<tbody>
										</tbody>
									</table>			

						</div>
					<div>
				</section>

				';

			break;
		}
	}else{
		echo akses_salah();
	}
}
?>

<script type="text/javascript">
	
	document.getElementById('btnSave').addEventListener('click', function () {
    const btn = this;
    btn.disabled = true;          // Disable tombol
    btn.innerText = "Saving...";  // Opsional: beri feedback ke user

    // Kirim form atau AJAX
    document.getElementById('assignment').submit();
});

	document.getElementById('submitall').addEventListener('click', function () {
    const btn = this;
    btn.disabled = true;          // Disable tombol
    btn.innerText = "Saving...";  // Opsional: beri feedback ke user

    // Kirim form atau AJAX
    document.getElementById('form-all').submit();
});


	function cekmanifest() {
		var id_kurir = $('#id_courier').val();
		if (id_kurir == ''){
			alert('harap isi data kurir');
		}else{
			alert('Silahkan Scan AWB dan arahkan kursos dikolom bawah ini');

			$("#inputawb").prop("disabled",false);
			$("#inputawb").focus();


		}
		
	}

	function scanawb(ele) {
    	
	    if(event.key === 'Enter') {
	    	//var awb = $("#inputawb").val();

	    	var awb = $("#inputawb").val();
	    	


	    	//$('#inputawb').prop("disabled", true);
	    	var notable = $("#no-table").val();
    		$("#scan-loading").show();

    		//alert('asda');

	    	var id_kurir = $("#id_courier").val();

			
		
    		var tanggal = $("#tanggal").val();

    		//alert('asda');

	    	

			$.ajax({
				type: 'POST',
				dataType: 'json',
				data: {
					kode_awb: awb,
					kurir:id_kurir,
					tgl:tanggal
				},
				url: 'module/mod_manifest-pickup/manifest-pickup_action.php?module=manifest-pickup&act=scanawb',
				success: function (result) {
					//$('#intercityasal').val(result['intercity']);
					//alert('asda');
					if(result.success > 0){
						var table = document.getElementById("manifest-pickupcreate");
						var row = table.insertRow();
						var cell1 = row.insertCell(0);
						var cell2 = row.insertCell(1);
						var cell3 = row.insertCell(2);
						var cell4 = row.insertCell(3);
						var cell5 = row.insertCell(4);
						var cell6 = row.insertCell(5);
						
						
						cell1.innerHTML = Number(notable)+1;
						cell2.innerHTML = awb;
						cell3.innerHTML = result.pengiriman.nama_kustomer;
						cell4.innerHTML = result.pengiriman.kota_asal;
						cell5.innerHTML = result.pengiriman.kota_tujuan;
						cell6.innerHTML = result.pengiriman.nama_service;
						
						

						$("#no-table").val(Number(notable)+1);

						$("#scan-massage").text(result.message);
						$("#scan-massage").css({ 'color': 'green'});

						//$("#no-table").val(Number(notable)+1);
					}else{

						$("#scan-massage").text(result.message);
						$("#scan-massage").css({ 'color': 'red'});

					}

						$("#inputawb").val('');
						$("#scan-loading").hide();

						//$('#inputawb').prop("disabled", false);

					//$('#intercityasal').val(result['intercity']);
					//alert(result.kota_asal);

				}
			});


	        //alert(ele.value);

	        


	    }
	}

</script>