<section class="content-header">
	<h1>Manifest Pickup<small>report</small></h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-home"></i> Home</a></li>
		<li><a href="#">Reports</a></li>
		<li><a href="#">Manifest</a></li>
		<li class="active">Pickup</li>
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
	if($u['level']=='admin' || $u['level']=='kurir' || $u['level']=='domex' || $u['level']=='driver' || $u['level']=='operator' || $u['level']=='intercity' || $u['level']=='finance' || $u['level']=='agent'){

		$aksi	= "module/mod_branch/branch_action.php";
		
		$act = !empty($_GET['act']) ? $_GET['act'] : '';
		
		switch($act){
			
			default:
			echo'
			<section class="content">
				<div class="row">
					<div class="col-xs-12">
						<div class="box box-primary">
							<div class="box-body">
								<table class="table table-striped table-bordered table-hover" id="manifest-pickup" style="white-space:nowrap;">
									<thead>
										<tr>
											<th>Pickup Date</th>
											<th>Pickup By</th>
											<th>Qty</th>
											<th>Weight</th>
											<th>Collie</th>
											<th>Action</th>
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
			
			case "view":
			
			$key = "asdf$1011";
			$encrypt = "$_GET[id]";
			$id = base64_decrypt($encrypt,$key);
			
			$pieces = explode('-', $id);
			$pickup_by = $pieces['0'];
			$pickup_date = implode('-', array_slice($pieces, 1, 3));
			
			$query = mysqli_query($con, "select b.id_user,b.namalengkap from pengiriman a 
				inner join user b on b.id_user=a.pickup_by 
				where a.pickup_by='$pickup_by' and DATE_FORMAT(a.pickup_date,'%Y-%m-%d')='$pickup_date'");
			$r = mysqli_fetch_assoc($query);


			$query_kode_manifest = mysqli_query($con, "select kode_manifest from manifest 
				where tanggal='$pickup_date' and manifest_pickup_by = '$pickup_by'");
			$r_kode = mysqli_fetch_assoc($query_kode_manifest);

			$tanggal = tgl_indo($pickup_date);

			echo'
			<section class="content">
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">Manifest Pickup</h3>
						<div class="box-tools pull-right">
							<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
							<button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
						</div>
					</div><!-- /.box-header -->
					<form class="form-horizontal form-bordered">
					<div class="box-body">
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label class="col-md-3 control-label">Date</label>
									<div class="col-md-9">
										<div class="input-group">
											<div class="input-group-addon">
												<i class="fa fa-calendar"></i>
											</div>
											<input type="text" class="form-control" value="'.$tanggal.'" readonly>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-8">
								<div class="form-group">
									<label class="col-md-2 control-label">Pickup By</label>
									<div class="col-md-10">
										<input type="text" class="form-control" value="'.$r['namalengkap'].'" readonly>
									</div>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label class="col-md-5 control-label">Manifest Code</label>
									<div class="col-md-7">
										<input type="text" class="form-control" value="'.(isset($r_kode['kode_manifest']) ? $r_kode['kode_manifest'] : '').'" readonly>
									</div>
								</div>
							</div>
						</div>
						<table class="table table-striped table-bordered table-hover" id="manifest-pickup-detail" style="white-space:nowrap;">
						<thead>
							<tr>
								<th style="width: 10px">#</th>
								<th>Airwaybill</th>
								<th>Customer</th>
								<th>Origin</th>
								<th>Destination</th>
								<th>Collie</th>
								<th>Weight</th>
								<th>Comodity</th>								
								<th>Aksi</th>
							</tr>
						</thead>
						<tbody>';
							$no=1;
							$result = mysqli_query($con,"select id_pengiriman, airwaybill_kode, x_namakustomer(id_kustomer) as kustomer, kota_asal, kota_tujuan, collie, berat_barang, case when komoditi='1' then 'Paket' else 'Dokumen' end as komoditi,pickup_date from pengiriman where pickup_by='$pickup_by' and DATE_FORMAT(pickup_date,'%Y-%m-%d')='$pickup_date' order by created_date asc");
							
							while ($row = mysqli_fetch_assoc($result)){
								if($row['kota_asal']=='Jakarta Selatan' || $row['kota_asal']=='Jakarta Timur' || $row['kota_asal']=='Jakarta Barat' || $row['kota_asal']=='Jakarta Utara' || $row['kota_asal']=='Jakarta Pusat'){
									$lokasi_asal = 'Jakarta';
								}else{
									$lokasi_asal = $row['kota_asal'];
								}
								if($row['kota_tujuan']=='Jakarta Selatan' || $row['kota_tujuan']=='Jakarta Timur' || $row['kota_tujuan']=='Jakarta Barat' || $row['kota_tujuan']=='Jakarta Utara' || $row['kota_tujuan']=='Jakarta Pusat'){
									$lokasi_tujuan = 'Jakarta';
								}else{
									$lokasi_tujuan = $row['kota_tujuan'];
								}
								$now = new DateTime();
								$batas = new DateTime($row['pickup_date']);
								$batas->modify('+1 day');
								
								$now->setTime(0, 0, 0);
								$batas->setTime(0, 0, 0);
								
								if($now > $batas){
									$btnchange = '';
								}else{
									$btnchange = '<button type="button" class="btn btn-xs btn-warning open_switch_pickupchange" onclick="switchkurir(this.dataset.id)" data-id='.base64_encrypt($row['id_pengiriman'],$key).'"><i class="fa fa-exchange"></i></button>';
									
								}

								echo'
								<tr>
									<td><center>'.$no.'</center></td>
									<td>'.$row['airwaybill_kode'].'</td>
									<td>'.$row['kustomer'].'</td>
									<td>'.strtoupper($row['kota_asal']).'</td>
									<td>'.strtoupper($row['kota_tujuan']).'</td>
									<td>'.$row['collie'].'</td>
									<td>'.$row['berat_barang'].'</td>
									<td>'.$row['komoditi'].'</td>									
									<td>'.$btnchange.'</td>
								</tr>';
								$no++;
							}
							
							echo'
							</tbody>
						</table>
					</div><!-- /.box-body -->
					<div class="box-footer">
						<button type="button" class="btn btn-default" onclick="window.location.href = \'?module=report-manifest-pickup\';">Cancel</button>
						<button type="button" class="btn btn-primary pull-right" onClick="window.open(\'reports/manifest-pickup.php?id='.$encrypt.'\',\'mywindow\',\'menubar=1,resizable=1,width=800,height=600\');"><i class="fa fa-print"></i> Print</button>

						<button type="button" class="btn btn-primary pull-right" onClick="window.open(\'reports/costing-pickup.php?id='.$encrypt.'\',\'mywindow\',\'menubar=1,resizable=1,width=800,height=600\');"><i class="fa fa-print"></i> Print Costing</button>
					</div><!-- /.box-footer -->
					</form>
				</div><!-- /.box -->
			</section>
			<div id="switch-pickup" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
							<h4 class="modal-title" id="myModalLabel">Switch Courier & Date</h4>
						</div>
						<div class="modal-content">
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

<script>
	function switchkurir(idKurir) {
		$("#switch-pickup").modal('show');
		var idkiriman = idKurir;
		//alert (idkiriman);
	
		$.post('module/mod_report-manifest-pickup/report-manifest-pickup_action.php?module=pickup&act=switch', {
			id: idkiriman
			},
			function (html) {
				$("#switch-pickup .modal-body").html(html);
			}
		);

		

	}
	

			

</script>