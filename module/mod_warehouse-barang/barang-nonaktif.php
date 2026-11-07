<section class="content-header">
	<h1>Data Barang Non Aktif<small></small></h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-home"></i> Home</a></li>
		<li><a href="#">Warehouse</a></li>
		<li class="active">Data Barang Non Katif</li>
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
								<table class="table table-striped table-bordered table-hover" id="barang-nonaktif">
									<thead>
										<tr>
											<th>Nama barang</th>
											<th>Kode barang</th>
											<th>Pemilik</th>
											<th>Stok</th>
											<th>Tanggal masuk</th>
											<th>Lokasi rak</th>
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
			
			
			
			
			
		}
	}else{
		echo akses_salah();
	}
}
?>
