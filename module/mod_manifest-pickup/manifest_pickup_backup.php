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

</script>