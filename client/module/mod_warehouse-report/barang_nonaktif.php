<section class="content-header">
	<h1>Warehouse<small></small></h1>
	<ol class="breadcrumb">
		<li><a href="#">Warehouse</a></li>
		<li class="active">Barang non aktif <?php //echo $u['level'].$u['id_user']; ?></li>
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
	//cek hak akses user
	

		$aksi	= "module/mod_warehouse/report_action.php";
		
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
									
								</div>
							</div>
							<div class="box-body">
								<table id="barangnonaktif" class="table table-bordered table-striped table-hover" width="100%" cellspacing="0" style="white-space:nowrap;">
									<thead>
										<tr>
											<th>Foto barang</th>
											<th>Nama barang</th>
											<th>kode barang</th>
											<th>Departement</th>
											<th>Aktifkan</th>
											
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

		';
			
			break;

		
		}
	
}
?>

