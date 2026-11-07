<section class="content-header">
	<h1>Manifest Inbound<small>report</small></h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-home"></i> Home</a></li>
		<li><a href="#">Reports</a></li>
		<li><a href="#">Manifest</a></li>
		<li class="active">Inbound</li>
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
	if($u['level']=='admin' || $u['level']=='finance' || $u['level']=='kurir' || $u['level']=='driver' || $u['level']=='operator' || $u['level']=='intercity' || $u['level']=='domex' || $u['level']=='network' || $u['level']=='agent' || $u['level']=='inbound'){

		$aksi	= "module/mod_report-manifest-inbound/report-manifest-inbound_action.php";
		
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
											<button class="btn btn-sm btn-default" onclick="window.location.href = \'?module=report-manifest-inbound&act=create\';"><i class="fa fa-plus text-green"></i> Create</button>
										</div>
									</div>
									<div class="col-md-6">
										<div class="btn-group pull-right">
											<button class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown">Tools <i class="fa fa-angle-down"></i></button>
											<ul class="dropdown-menu pull-right">
												
												<li><a href="main.php?module=report-manifest-inbound&act=search" class=""><i class="fa fa-search"></i>Search By Airwaybill</a></li>
												
											</ul>
										</div>										
									</div>									
								</div>
							</div>
							<div class="box-body">
								<table class="table table-striped table-bordered table-hover" id="manifest-inbound" style="white-space:nowrap;">
									<thead>
										<tr>
											<th>Manifest Code</th>
											<th>Manifest Date</th>
											<th>Manifest By</th>
											<th>Manifest From</th>
											<th>Manifest To</th>
											<th>Qty</th>
											<th>Weight</th>
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
			
			$query = mysqli_query($con, "select a.id_manifest, a.tanggal, a.kode_manifest, x_user(a.created_by) as manifest_by, case when x_intercity(b.id_kota)='Y' then 'JAKARTA' else b.nama_cabang end as manifest_from, case when x_intercity(b.id_kota)='Y' then 'CESCO LOGISTICS' else b.alamat1 end as manifestfrom, case when x_intercity(c.id_kota)='Y' then 'JAKARTA' else c.nama_cabang end as manifest_to, case when x_intercity(c.id_kota)='Y' then 'CESCO LOGISTICS' else c.alamat1 end as manifestto, a.no_smu, case when a.manifest_via='1' then 'UDARA' when a.manifest_via='2' then 'DARAT' when a.manifest_via='3' then 'LAUT' when a.manifest_via='4' then 'TOTO' when a.manifest_via='5' then 'HERONA' when a.manifest_via='6' then 'BES' when a.manifest_via='7' then 'Indah Cargo' when a.manifest_via='8' then 'JNE' when a.manifest_via='9' then 'TIKI' else a.manifest_via end as manifest_via from manifest a inner join cabang b on b.id_cabang=a.manifest_from inner join cabang c on c.id_cabang=a.manifest_to  where a.id_manifest='$id'");
			$r = mysqli_fetch_assoc($query);

			$tanggal = tgl_indo($r['tanggal']);

			echo'
			<section class="content">
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">Manifest Inbound</h3>
						<div class="box-tools pull-right">
							<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
							<button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
						</div>
					</div><!-- /.box-header -->
					<form class="form-horizontal form-bordered" method="post" action="'.$aksi.'?module=outbound&act=update">
					<input type="hidden" name="id_manifest" value="'.$r['id_manifest'].'">
					
					<div class="box-body">
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label class="col-md-4 control-label">Date</label>
									<div class="col-md-8">
										<div class="input-group">
											<div class="input-group-addon">
												<i class="fa fa-calendar"></i>
											</div>
											<input type="text" class="form-control" value="'.$tanggal.'" readonly>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label class="col-md-4 control-label">Manifest Code</label>
									<div class="col-md-8">
										<input type="text" class="form-control" value="'.$r['kode_manifest'].'" readonly>
									</div>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label class="col-md-4 control-label">Manifest By</label>
									<div class="col-md-8">
										<input type="text" class="form-control" value="'.strtoupper($r['manifest_by']).'" readonly>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label class="col-md-4 control-label">Manifest From</label>
									<div class="col-md-8">
										<input type="text" class="form-control" value="'.strtoupper($r['manifest_from']).' ('.strtoupper($r['manifestfrom']).')" readonly>
									</div>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label class="col-md-4 control-label">Manifest To</label>
									<div class="col-md-8">
										<input type="text" class="form-control" value="'.strtoupper($r['manifest_to']).' ('.strtoupper($r['manifestto']).')'.$r['manifest_via'].'" readonly>
									</div>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label class="col-md-4 control-label">No. SMU</label>
									<div class="col-md-8">
										<input type="text" class="form-control" value="'.$r['no_smu'].'" name="no_smu">
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							
							<div class="col-md-4">
								<div class="form-group">
									<label class="col-md-4 control-label">Manifest Via</label>
									<div class="col-md-8">
										<input type="text" class="form-control" value="'.$r['manifest_via'].'" readonly>
									</div>
								</div>
							</div>
							
						</div>
						<table class="table table-striped table-bordered table-hover" id="manifest-inbound-detail" style="white-space:nowrap;">
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
								<th>Service</th>
								<th>Received Date</th>
								<th>Received By</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>';
							$no=1;
							// "select a.id_pengiriman, airwaybill_kode, x_namakustomer(id_kustomer) as kustomer, x_namakotabesar(lokasi_asal) as lokasi_asal, x_namakotabesar(lokasi_tujuan) as lokasi_tujuan, collie, coalesce(berat_aktual,berat_barang) as berat_barang, case when komoditi='1' then 'paket' else 'dokumen' end as komoditi, x_service(service) as service, manifest_received, x_user(manifest_by) as received_by from pengiriman a left join manifest_detail b on a.id_pengiriman=b.id_pengiriman where id_manifest='$r[id_manifest]' order by created_date asc";

							$result = mysqli_query($con,"select a.id_pengiriman, airwaybill_kode, x_namakustomer(id_kustomer) as kustomer, kota_asal, kota_tujuan, collie, coalesce(berat_aktual,berat_barang) as berat_barang, case when komoditi='1' then '<button class=\"btn btn-block btn-xs\"><i class=\"fa fa-cube\"></i> Paket</button>' else '<button class=\"btn btn-block btn-xs\"><i class=\"fa fa-book\"></i> Dokumen</button>' end as komoditi, x_service(service) as service, delivery_date as manifest_received, delivery_name as received_by from pengiriman a left join manifest_detail b on a.id_pengiriman=b.id_pengiriman where b.id_manifest='$r[id_manifest]' order by a.created_date asc");
							while ($row = mysqli_fetch_assoc($result)){
								$key = "asdf$1011";
								$stringawal = $row["id_pengiriman"];
								$id = base64_encrypt($stringawal,$key);
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
									<td>'.strtoupper($row['service']).'</td>
									<td>'.tgl_indo($row['manifest_received']).'</td>
									<td>'.strtoupper($row['received_by']).'</td>
									<td><center><button data-href="'.$aksi.'?module=inbound&act=delete&id='.$id.'" class="btn btn-xs btn-danger" type="button" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-remove"></i></button></center></td>
								</tr>';
								$no++;
							}
							
							echo'
							</tbody>
						</table>
					</div><!-- /.box-body -->
					<div class="box-footer">
						<button type="button" class="btn btn-default" onclick="window.location.href = \'?module=report-manifest-inbound\';">Back</button>
						<button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Save</button> 
						<button type="button" class="btn btn-primary pull-right" onClick="window.open(\'reports/manifest-inbound.php?id='.$encrypt.'\',\'mywindow\',\'menubar=1,resizable=1,width=800,height=600\');"><i class="fa fa-print"></i> Print</button>

						<button type="button" class="btn btn-primary pull-right" onClick="window.open(\'reports/costing-inbound.php?id='.$encrypt.'\',\'mywindow\',\'menubar=1,resizable=1,width=800,height=600\');"><i class="fa fa-print"></i> Print Costing</button>
					</div><!-- /.box-footer -->
					</form>
				</div><!-- /.box -->
			</section>';

			break;

			case "create":
				echo 'CREATE';
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
									<input type="text" class="form-control" id="searchawb" name="searchawb" value="" >			
									<button type="button" name="report-manifest-inbound"  data-id="report-manifest-inbound" id="btnsearchcosting" class="btn form-control btn-primary">search</button>
							</div>
						</div>
						<div id="load" style="display:none;"><img src="dist/img/Loading_icon.gif"/ style="display:block; margin: 0 auto; "></div>
												
						<table id="airwaybillsrc" class="table table-bordered table-striped table-hover" width="100%" cellspacing="0" style="white-space:nowrap; margin-top:50px;">
									<thead>
										<tr style="text-transform: uppercase;">
											<th>Airwaybill</th>
											<th width="90px">view</th>																		
										</tr>
									<thead>
									<tbody>
										<tr id="hasil" style="text-transform: uppercase;">
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
		}
	}else{
		echo akses_salah();
	}
}
?>