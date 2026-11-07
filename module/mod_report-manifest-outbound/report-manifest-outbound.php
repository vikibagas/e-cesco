<section class="content-header">
	<h1>Manifest Outbound<small>report</small></h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-home"></i> Home</a></li>
		<li><a href="#">Reports</a></li>
		<li><a href="#">Manifest</a></li>
		<li class="active">Outbound</li>
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
	if($u['level']=='admin' || $u['level']=='operator' || $u['level']=='finance' || $u['level']=='kurir' || $u['level']=='driver' || $u['level']=='network' || $u['level']=='domex' || $u['level']=='agent' || $u['level']=='inbound' || $u['id_user'] == 17 || $u['level'] == 'admin_network'){

		$aksi	= "module/mod_report-manifest-outbound/report-manifest-outbound_action.php";
		
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
									
									<div class="col-md-12 col-offset-md-6">
										<div class="btn-group pull-right">
											<button class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown">Tools <i class="fa fa-angle-down"></i></button>
											<ul class="dropdown-menu pull-right">
												
												<li><a href="main.php?module=report-manifest-outbound&act=search" class=""><i class="fa fa-search"></i>Search By Airwaybill</a></li>
												<li><a href="main.php?module=report-manifest-outbound&act=report-vendor" class=""><i class="fa fa-search"></i>Report per vendor</a></li>
												<li><a href="main.php?module=report-manifest-outbound&act=report-agent-sla" class=""><i class="fa fa-search"></i>Report AGENT SLA</a></li>
												';
												echo '
												
											</ul>
										</div>										
									</div>
								</row>
							</div>
							<div class="box-body">
								<table class="table table-striped table-bordered table-hover" id="manifest-outbound" style="white-space:nowrap;">
									<thead>
										<tr>
											<th>Date</th>
											<th>Manifest By</th>
											<th>Manifest From</th>
											<th>Manifest To</th>
											<th>Manifest Via</th>
											<th>Qty</th>
											<th>Weight</th>
											<th>Transit</th>
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
			</section>
			<div id="switch-courier-outbound" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
							<h4 class="modal-title" id="myModalLabel">Switch Assignment</h4>
						</div>
						<div class="modal-content">
							<div class="modal-body"></div>
						</div>
					</div>
				</div>
			</div>
			';
			
			break;
			
			case "view":
			
			$key = "asdf$1011";
			$encrypt = "$_GET[id]";
			$id = base64_decrypt($encrypt,$key);
			
			$query = mysqli_query($con, "select a.id_manifest, a.tanggal, a.kode_manifest, x_user(a.manifest_outbound_by) as manifest_by, case when x_intercity(b.id_kota)='Y' then 'JAKARTA' else b.nama_cabang end as manifest_from, case when x_intercity(b.id_kota)='Y' then 'CESCO LOGISTICS' else b.alamat1 end as manifestfrom, case when x_intercity(c.id_kota)='Y' then 'JAKARTA' else c.nama_cabang end as manifest_to, case when x_intercity(c.id_kota)='Y' then 'CESCO LOGISTICS' else c.alamat1 end as manifestto, case when a.manifest_via='1' then 'UDARA' when a.manifest_via='2' then 'DARAT' when a.manifest_via='3' then 'LAUT' when a.manifest_via='4' then 'TOTO' when a.manifest_via='5' then 'HERONA' when a.manifest_via='7' then 'Indah Cargo' when a.manifest_via='8' then 'JNE' when a.manifest_via='9' then 'TIKI' else manifest_via end as manifest_via, a.no_smu ,a.berat_aktual_volume,a.collie_barang



				from manifest a left join cabang b on b.id_cabang=a.manifest_from left join cabang c on c.id_cabang=a.manifest_to  where a.id_manifest='$id'");

			
			$r = mysqli_fetch_assoc($query);

			$tanggal = tgl_indo($r['tanggal']);

			echo'
			<section class="content">
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">Manifest Outbound</h3>
						<div class="box-tools pull-right">
							<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
							<button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
						</div>
					</div><!-- /.box-header -->
					<form class="form-horizontal form-bordered" method="post" action="'.$aksi.'?module=outbound&act=update">
					<input type="hidden" name="id_manifest" value="'.$r['id_manifest'].'">
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
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label class="col-md-5 control-label">Manifest Code</label>
									<div class="col-md-7">
										<input type="text" class="form-control" value="'.$r['kode_manifest'].'" readonly>
									</div>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label class="col-md-5 control-label">Manifest By</label>
									<div class="col-md-7">
										<input type="text" class="form-control" value="'.strtoupper($r['manifest_by']).'" readonly>
									</div>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label class="col-md-5 control-label">Manifest Via</label>
									<div class="col-md-7">
										<input type="text" class="form-control" value="'.strtoupper($r['manifest_via']).'" readonly>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label class="col-md-4 control-label">Manifest From</label>
									<div class="col-md-8">
										<input type="text" class="form-control" value="'.strtoupper($r['manifest_from']).' ('.strtoupper($r['manifestfrom']).')" readonly> <a href="#" class="btn btn-xs btn-warning " onclick="ganti_manifest_from(this.id)" id="'.base64_encrypt($id,"asdf$1011").'" >ganti</a>
									</div>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label class="col-md-4 control-label">Manifest To</label>
									<div class="col-md-8">
										<input type="text" class="form-control" value="'.strtoupper($r['manifest_to']).' ('.strtoupper($r['manifestto']).')" readonly> <a href="#" class="btn btn-xs btn-warning " onclick="ganti_manifest_to(this.id)" id="'.base64_encrypt($id,"asdf$1011").'" >ganti</a>
									</div>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label class="col-md-4 control-label">No. SMU/Resi</label>
									<div class="col-md-8">
										<input type="text" class="form-control" value="'.$r['no_smu'].'" name="no_smu">
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
								<th>Pickup Agent</th>
								<th>Deliv Agent</th>
								<th>Perusahaan penerima</th>
								<th>alamat penerima</th>
								<th>packing kayu</th>
								<th>Collie</th>
								<th>Weight</th>
								<th>Comodity</th>
								<th>Service</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>';
							$no=1;
							$result = mysqli_query($con,"select a.id_pengiriman, a.id_manifest_detail,airwaybill_kode, x_namakustomer(id_kustomer) as kustomer, 

								if(b.id_kota_asal>0,b.kota_asal,x_namakotabesar(lokasi_asal)) as lokasi_asal, if(b.id_kota_tujuan>0,b.kota_tujuan,x_namakotabesar(lokasi_tujuan)) as lokasi_tujuan, collie, coalesce(berat_aktual,berat_barang) as berat_barang, case when komoditi='1' then '<button class=\"btn btn-block btn-xs\"><i class=\"fa fa-cube\"></i> Paket</button>' else '<button class=\"btn btn-block btn-xs\"><i class=\"fa fa-book\"></i> Dokumen</button>' end as komoditi, x_service(service) as service, alamat_penerima,perusahaan_penerima, case when packing_kayu='1' then 'Packing Oleh Pemilik' when packing_kayu='2' then 'Packing Kayu Oleh Cesco Log.' when packing_kayu='3' then 'Non Packing Kayu Oleh Cesco Log.' end as packing_kayu  , x_namacabang(pickup_by_agent) as pickup_agent, x_namacabang(delivery_by_agent) as delivery_agent

								from manifest_detail a left join pengiriman b on b.id_pengiriman=a.id_pengiriman where a.id_manifest='$r[id_manifest]' group by a.id_pengiriman order by a.created_date asc");
							while ($row = mysqli_fetch_assoc($result)){
								//$id_manifest_detail= $row["id_manifest_detail"];
								$key = "asdf$1011";
								$stringawal = $row["id_manifest_detail"];
								$id_manifest_detail = base64_encrypt($stringawal,$key);
								if($row['lokasi_asal']=='Jakarta Selatan' || $row['lokasi_asal']=='Jakarta Timur' || $row['lokasi_asal']=='Jakarta Barat' || $row['lokasi_asal']=='Jakarta Utara' || $row['lokasi_asal']=='Jakarta Pusat'){
									$lokasi_asal = 'Jakarta';
								}else{
									$lokasi_asal = $row['lokasi_asal'];
								}
								if($row['lokasi_tujuan']=='Jakarta Selatan' || $row['lokasi_tujuan']=='Jakarta Timur' || $row['lokasi_tujuan']=='Jakarta Barat' || $row['lokasi_tujuan']=='Jakarta Utara' || $row['lokasi_tujuan']=='Jakarta Pusat'){
									$lokasi_tujuan = 'Jakarta';
								}else{
									$lokasi_tujuan = $row['lokasi_tujuan'];
								}
								echo'
								<tr>
									<td><center>'.$no.'</center></td>
									<td>'.$row['airwaybill_kode'].'</td>
									<td>'.$row['kustomer'].'</td>
									<td>'.strtoupper($lokasi_asal).'</td>
									<td>'.strtoupper($lokasi_tujuan).'</td>
									<td>'.strtoupper($row['pickup_agent']).'</td>
									<td>'.strtoupper($row['delivery_agent']).'</td>
									<td>'.$row['perusahaan_penerima'].'</td>
									<td>'.$row['alamat_penerima'].'</td>
									<td>'.$row['packing_kayu'].'</td>
									<td>'.$row['collie'].'</td>
									<td>'.$row['berat_barang'].'</td>
									<td>'.$row['komoditi'].'</td>
									<td>'.strtoupper($row['service']).'</td>
									<td><center><button data-href="'.$aksi.'?module=outbound&act=delete&id='.$id_manifest_detail.'" class="btn btn-xs btn-danger" type="button" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-remove"></i></button>

									<a href="#" class="btn btn-xs btn-warning open_switch_outbound_detail" data-id='.$row['id_manifest_detail'].'"><i class="fa fa-exchange"></i></a></center>
									</td>
								</tr>';
								$no++;
							}
							
							echo'
							</tbody>
						</table>
					</div><!-- /.box-body -->
					<div class="box-footer">
						<button type="button" class="btn btn-default" onclick="window.location.href = \'?module=report-manifest-outbound\';">Cancel</button>
						<button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Save</button>
						';

						
							echo '<button type="button" class="btn btn-primary pull-right" onClick="window.open(\'reports/manifest-outbound.php?id='.$encrypt.'\',\'mywindow\',\'menubar=1,resizable=1,width=800,height=600\');"><i class="fa fa-print"></i> View</button> ';

							/*echo '<button type="button" class="btn btn-primary pull-right" onClick="window.open(\'/e-cesco/module/mod_report-manifest-outbound/report_vendor_per_destination.php?id='.$encrypt.'\',\'mywindow\',\'menubar=1,resizable=1,width=800,height=600\');"><i class="fa fa-print"></i> Print untuk vendor</button> ';*/


							$q_vendor = mysqli_query($con,"select koli_ke,berat_aktual,volume from manifest_collie_vendor where id_manifest =".base64_decrypt($_GET['id'],$key)."");
							//echo "select koli_ke,berat_aktual,volume from manifest_collie_vendor where id_manifest =".base64_decrypt($_GET['id'],$key)."";
		  					$cek_vendor = mysqli_num_rows($q_vendor);
		  					if($cek_vendor > 0){
		  						echo ' <a href="module/mod_report-manifest-outbound/report_vendor_per_destination.php?id='.$_GET['id'].'" class="btn btn-success pull-right" target="_blank"  id="'.$encrypt.'" >Print Untuk Vendor</a>';
		  					}
		  					else{


								echo ' <a href="#" class="btn btn-success pull-right" onclick="popup_print_vendor(this.id)" id="'.$encrypt.'" >Print Untuk Vendor</a>';
							}
							
						
						
						echo '
						
						<button type="button" class="btn btn-primary pull-right" onClick="window.open(\'reports/costing-outbound.php?id='.$encrypt.'\',\'mywindow\',\'menubar=1,resizable=1,width=800,height=600\');"><i class="fa fa-print"></i> Print Costing</button>
						<button type="button" class="btn btn-primary pull-right" onClick="window.open(\'reportsnew/awb_label_outbound.php?id='.$encrypt.'\',\'mywindow\',\'menubar=1,resizable=1,width=800,height=600\');"><i class="fa fa-print"></i> Print Label</button>
					
					</div><!-- /.box-footer -->
					</form>
				</div><!-- /.box -->
			</section>

			<div id="switch-courier-outbound-detail" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
							<h4 class="modal-title" id="myModalLabel">Switch Assignment</h4>
						</div>
						<div class="modal-content">
							<div class="modal-body"></div>
						</div>
					</div>
				</div>
			</div>

			<div id="switch-manifest-from" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
							<h4 class="modal-title" id="myModalLabel">Switch manifest from</h4>
						</div>
						<div class="modal-content">
							<div class="modal-body"></div>
						</div>
					</div>
				</div>
			</div>
			<div id="switch-manifest-to" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
							<h4 class="modal-title" id="myModalLabel">Switch manifest to</h4>
						</div>
						<div class="modal-content">
							<div class="modal-body"></div>
						</div>
					</div>
				</div>
			</div>

			<div id="modal_print_vendor" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
							<h4 class="modal-title" id="myModalLabel">Masukan jumlah koli</h4>
						</div>
						<div class="modal-content">
							<div class="modal-body"></div>
						</div>
					</div>
				</div>
			</div>
			
			';
			

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
									<button type="button" name="report-manifest-outbound"  data-id="report-manifest-outbound" id="btnsearchcosting" class="btn form-control btn-primary">search</button>
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

			case "report-vendor":
			echo'
			<section class="content">
				';

				?>

				<?php
				if ($_SERVER["REQUEST_METHOD"] == "POST") {
					

					$vendor = $_POST['vendor'];




					$tanggal = mysqli_real_escape_string($con, $_POST['tanggal']);
						$dr = strleft($tanggal, 10);
						$sm = strright($tanggal, 10);
						$dari = jin_date_sql($dr);
						$sampai = jin_date_sql($sm);
				
					
				
				?>

				<form class="form-horizontal" method="post" action="" autocomplete="off">
						<div class="box box-primary">
							<div class="box-header with-border">
								<h3 class="box-title">Report outbound by vendor</h3>
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
													<input type="text" class="form-control" id="daterange" required name="tanggal" value="<?php echo''.$tanggal.'';?>">
												</div>
											</div>
										</div>
									</div>
									<div class="col-md-5">
										<div class="form-group">
											<label class="col-sm-4 control-label full-right">Vendor</label>
											<div class="col-sm-8">
												<select name="vendor" class="form-control select2">
												<?php
												
													$query=mysqli_query($con,'select name from manifest_vendor where active="Y" ORDER BY name ASC');
												
												$vendor = $_POST['vendor'] ;

												while($r=mysqli_fetch_assoc($query)){
													
													if($r['name']==$vendor){
														echo'
														<option value="'.$r['name'].'" selected>'.$r['name'].'</option>';
													}else{
														echo'
														<option value="'.$r['name'].'">'.$r['name'].'</option>';
													}
													
												}
												?>
												</select>
												
											</div>
										</div>
									</div>
								</div><!-- /.row -->
							</div><!-- /.box-body -->
					        <div class="box-footer">
					        	<?php 
					        	$action_excel = 'reports/PHPExcel/report-vendor.php?tanggal='.$_POST['tanggal'].'&vendor='.$_POST['vendor'];
					        	?>
								<button type="submit" class="btn btn-primary pull-right"> <i class="fa fa-search"></i> View</button> 
								<a href="<?php echo $action_excel; ?>" target="_blank"  class="btn btn-primary pull-right"> <i class="fa fa-Excel"></i> Excel</a>
								<?php 
									
								 ?>
									


									
								<?php
								
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
								<table id="reportvendor" class="table table-striped table-bordered table-hover " width="100%" cellspacing="0" style="white-space:nowrap;">
									<thead>
										
											<tr style="text-transform: uppercase;">
												<th>Date</th>
												<th>AWB CESCO</th>
												<th>COMMODITY</th>
												<th>ISI BARANG</th>
												<th>DESTINATION</th>
												
												<th>3LC</th>
												<th>RECEPTIENT</th>
												<th>RECEIPT HANDPHONE</th>
												<th>RECEIPT ADDRESS</th>
												<th>CATATAN PENGANTARAN</th>
												
												<th>DESTINASI KOTA</th>
												<th>DESTINATION KECAMATAN</th>
												<th>BERAT AKTUAL</th>
												<th>DIMENSI</th>
												
												<th>SERVICE</th>
												
											</tr>
									</thead>
									<tbody id="databody">
									<?php

									
									$cari_old = "select b.tanggal,c.airwaybill_kode,c.kota_asal,c.kota_tujuan,c.berat_aktual,c.collie,case when c.komoditi='1' then 'P' else 'D' end as komoditi,jenis_barang,b.manifest_to, d.alamat2,d.telp, b.manifest_to, x_namacabang(b.manifest_to) as nama_cabang,d.kode_cabang,d.pic,x_namakotanew(d.id_kota_baru) as kota_cabang,d.kecamatan_text

							from manifest_detail a 
							inner join manifest b on a.id_manifest=b.id_manifest
							inner join pengiriman c on a.id_pengiriman=c.id_pengiriman 
							inner join cabang d on b.manifest_to = d.id_cabang

							where b.manifest_via='$vendor' and b.tanggal BETWEEN '$dari' AND '$sampai' and b.manifest_from = 39 and b.manifest_to !=39 order by b.manifest_to,c.kota_tujuan asc 
				
						";

						$cari = "
							select b.id_manifest,b.tanggal, d.alamat2,d.telp, b.manifest_to, x_namacabang(b.manifest_to) as nama_cabang,d.kode_cabang,d.pic,x_namakotanew(d.id_kota_baru) as kota_cabang,
							b.berat_smu,b.collie_smu,b.manifest_remarks,d.kecamatan_text
							from manifest b
							inner join cabang d on b.manifest_to = d.id_cabang

							where b.manifest_via='$vendor' and b.tanggal BETWEEN '$dari' AND '$sampai' and b.manifest_from = 39 and b.manifest_to !=39 and b.manifest_to !=109
						";
									
									$rowspanCounter = 1; 
									$_same = '';
									$sql = mysqli_query($con, $cari);
									while($row = mysqli_fetch_assoc($sql)){
										
										
										
										echo'
										<tr style="text-transform: uppercase;">
											<td width="20px">'.$row['tanggal'].'</td>
											
											';
											$query_detail=mysqli_query($con,"select  b.airwaybill_kode
												from manifest_detail a
												inner join pengiriman b on a.id_pengiriman = b.id_pengiriman
												where a.id_manifest='".$row['id_manifest']."' ");
													
												echo "<td>";
													while($d_detail=mysqli_fetch_assoc($query_detail)){
														
														echo $d_detail['airwaybill_kode'].'<br>';
														
												
														
														
													}
												echo "</td>";

											echo "<td>";
												$query_detail=mysqli_query($con,"select  case when b.komoditi='1' then 'P' else 'D' end as komoditi
												from manifest_detail a
												inner join pengiriman b on a.id_pengiriman = b.id_pengiriman
												where a.id_manifest='".$row['id_manifest']."' ");
													while($d_detail=mysqli_fetch_assoc($query_detail)){
														
														echo $d_detail['komoditi'].'<br>';
														
												
														
														
													}
												echo "</td>";
											$query_detail=mysqli_query($con,"select  jenis_barang
												from manifest_detail a
												inner join pengiriman b on a.id_pengiriman = b.id_pengiriman
												where a.id_manifest='".$row['id_manifest']."' ");
													
												echo "<td>";
													while($d_detail=mysqli_fetch_assoc($query_detail)){
														
														echo $d_detail['jenis_barang'].'<br>';
														
												
														
														
													}
												echo "</td>";

											echo '
											
											<td width="20px" >'.$row['nama_cabang'].'</td>
											';
											
											echo '
											<td width="20px">'.$row['kode_cabang'].'</td>
											<td width="20px">'.$row['pic'].'</td>
											<td width="20px">'.$row['telp'].'</td>
											<td width="20px">'.$row['alamat2'].'</td>
											<td><a href="#" class="manifest-remarks" data-type="textarea" data-pk="'.$row['id_manifest'].'">'.$row['manifest_remarks'].'</a></td>
											
											
											
											<td width="20px">'.$row['kota_cabang'].'</td>
											<td width="20px">'.$row['kecamatan_text'].'</td>
											<td width="20px"><a href="#" class="berat-smu" data-type="number" data-pk="'.$row['id_manifest'].'">'.$row['berat_smu'].'</a></td>
											<td width="20px"><a href="#" class="collie-smu" data-type="textarea" data-pk="'.$row['id_manifest'].'">'.$row['collie_smu'].'</a></td>
											';
											
											echo '
											<td width="20px">Reguler</td>
											
										</tr>
											
											';
									}
									?>
									</tbody>
								</table>
							</div><!-- /.box-body -->
						</div><!-- /.box -->
					</div>
				</div><!-- /.row -->


				<?php
				}
				else{


				?>


					<!--<form class="form-horizontal" method="get" target="_blank" action="reports/PHPExcel/report-vendor.php" autocomplete="off">-->
					<form class="form-horizontal" method="post" action="" autocomplete="off">
						<div class="box box-primary">
							<div class="box-header with-border">
								<h3 class="box-title">Report outbound by vendor</h3>
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
													<input type="text" class="form-control" id="daterange" required name="tanggal" value="">
												</div>
											</div>
										</div>
									</div>
									<div class="col-md-5">
										<div class="form-group">
											<label class="col-sm-4 control-label full-right">Vendor</label>
											<div class="col-sm-8">
												<select name="vendor" class="form-control select2">
												<?php
												
													$query=mysqli_query($con,'select name from manifest_vendor where active="Y" ORDER BY name ASC');
													

												while($r=mysqli_fetch_assoc($query)){
													
													echo	'<option value="'.$r['name'].'" >'.$r['name'].'</option>';
													
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
								<?php 
									
								 ?>
									


									
								<?php
								
								?>

							</div>
						</div><!-- /.box -->
						</form>

				<?php
				}
				echo '
				
			</section>			
			';
			break;

			case "report-agent-sla":
			echo'
			<section class="content">
				';

				?>

				<?php
				if ($_SERVER["REQUEST_METHOD"] == "POST") {
					

					$cabang = $_POST['cabang'] ;



					$tanggal = mysqli_real_escape_string($con, $_POST['tanggal']);
						$dr = strleft($tanggal, 10);
						$sm = strright($tanggal, 10);
						$dari = jin_date_sql($dr);
						$sampai = jin_date_sql($sm);
				
					
				
				?>

				<form class="form-horizontal" method="post" action="" autocomplete="off">
						<div class="box box-primary">
							<div class="box-header with-border">
								<h3 class="box-title">Report Agent SLA</h3>
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
													<input type="text" class="form-control" id="daterange" required name="tanggal" value="<?php echo''.$tanggal.'';?>">
												</div>
											</div>
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<label class="col-sm-4 control-label full-right">Cabang</label>
											<div class="col-sm-8">
												<select name="cabang" class="form-control select2">
												<?php
												
													$query=mysqli_query($con,'select nama_cabang,id_cabang from cabang where status="Y" ORDER BY nama_cabang ASC');

												

												while($r=mysqli_fetch_assoc($query)){
													if($r['nama_cabang']==$cabang){
														echo'
														<option value="'.$r['id_cabang'].'" selected>'.$r['nama_cabang'].'</option>';
													}else{
														echo'
														<option value="'.$r['id_cabang'].'">'.$r['nama_cabang'].'</option>';
													}
													
												}
												?>
												</select>
												
											</div>
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<label class="col-sm-4 control-label full-right">Type</label>
											<div class="col-sm-8">
												<select name="type" class="form-control select2">
													<option value="all">all</option>
													<option value="outbound">outbound</option>
													<option value="inbound">inbound</option>
												</select>
												
											</div>
										</div>
									</div>
									
								</div><!-- /.row -->
							</div><!-- /.box-body -->
					        <div class="box-footer">
					        	<?php 
					        	//$action_excel = 'reports/PHPExcel/report-vendor.php?tanggal='.$_POST['tanggal'].'&vendor='.$_POST['vendor'];
					        	?>
								<button type="submit" class="btn btn-primary pull-right"> <i class="fa fa-search"></i> View</button> 
								<!--<a href="<?php //echo $action_excel; ?>" target="_blank"  class="btn btn-primary pull-right"> <i class="fa fa-Excel"></i> Excel</a>-->
								<?php 
									
								 ?>
									


									
								<?php
								
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
								<table id="reportvendor" class="table table-striped table-bordered table-hover " width="100%" cellspacing="0" style="white-space:nowrap;">
									<thead>
										
											<tr style="text-transform: uppercase;">
												
												<th>AWB</th>
												<th>KOTA ASAL</th>
												<th>KOTA TUJUAN</th>
												
												
												<th>TANGGAL MANIFEST OUTBOUND</th>
												<th>TANGGAL INBOUND</th>

												<th>TANGGAL PICKUP</th>
												<th>TANGGAL DELIVERY</th>
												
												
											</tr>
									</thead>
									<tbody id="databody">
									<?php

									$type = $_POST['type'];
									$cabang = $_POST['cabang'] ;

									
									if($type == 'all'){
										$where_type = '(b.manifest_to ='.$cabang.' or b.manifest_from ='.$cabang.')';
									}elseif ($type == 'outbound') {
										$where_type = '(b.manifest_to ='.$cabang.' )';
									}elseif ($type == 'inbound') {
										$where_type = '(b.manifest_from ='.$cabang.' )';
									}
									


									 $cari = "select b.tanggal as tanggal_manifest,c.airwaybill_kode,c.kota_asal,c.kota_tujuan,c.berat_aktual,c.collie,case when c.komoditi='1' then 'P' else 'D' end as komoditi,jenis_barang,b.manifest_to, d.alamat2,d.telp, b.manifest_to, x_namacabang(b.manifest_to) as nama_cabang,d.kode_cabang,d.pic,x_namakotanew(d.id_kota_baru) as kota_cabang,d.kecamatan_text,pickup_date,courier_date,inbound_received

							from manifest_detail a 
							inner join manifest b on a.id_manifest=b.id_manifest
							inner join pengiriman c on a.id_pengiriman=c.id_pengiriman 
							inner join cabang d on b.manifest_to = d.id_cabang

							where $where_type and b.tanggal BETWEEN '$dari' AND '$sampai' 
				
						";

						
									
									
									$sql = mysqli_query($con, $cari);
									while($row = mysqli_fetch_assoc($sql)){
										
										
										
										echo'
										<tr style="text-transform: uppercase;">
											
											
											
											<td width="20px">'.$row['airwaybill_kode'].'</td>
											<td width="20px">'.$row['kota_asal'].'</td>
											<td width="20px">'.$row['kota_tujuan'].'</td>
											
											<td width="20px">'.$row['tanggal_manifest'].'</td>
											<td width="20px">'.$row['inbound_received'].'</td>
											
											
											<td width="20px">'.$row['pickup_date'].'</td>
											<td width="20px">'.$row['courier_date'].'</td>
											
											
										</tr>
											
											';
									}
									?>
									</tbody>
								</table>
							</div><!-- /.box-body -->
						</div><!-- /.box -->
					</div>
				</div><!-- /.row -->


				<?php
				}
				else{


				?>


					<!--<form class="form-horizontal" method="get" target="_blank" action="reports/PHPExcel/report-vendor.php" autocomplete="off">-->
					<form class="form-horizontal" method="post" action="" autocomplete="off">
						<div class="box box-primary">
							<div class="box-header with-border">
								<h3 class="box-title">Report Aget SLA</h3>
								<div class="box-tools pull-right">
									<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
									<button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
								</div>
							</div><!-- /.box-header -->
							<div class="box-body">
								<div class="row">
									<div class="col-md-4">
										<div class="form-group">
											<label class="col-sm-4 control-label full-right">Date Range</label>
											<div class="col-sm-8">
												<div class="input-group">
													<div class="input-group-addon">
														<i class="fa fa-calendar"></i>
													</div>
													<input type="text" class="form-control" id="daterange" required name="tanggal" value="">
												</div>
											</div>
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<label class="col-sm-4 control-label full-right">Cabang</label>
											<div class="col-sm-8">
												<select name="cabang" class="form-control select2">
												<?php
												
													$query=mysqli_query($con,'select nama_cabang,id_cabang from cabang where status="Y" ORDER BY nama_cabang ASC');
													

												while($r=mysqli_fetch_assoc($query)){
													
													echo	'<option value="'.$r['id_cabang'].'" >'.$r['nama_cabang'].'</option>';
													
												}
												?>
												</select>
												
											</div>
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<label class="col-sm-4 control-label full-right">Type</label>
											<div class="col-sm-8">
												<select name="type" class="form-control select2">
													<option value="all">all</option>
													<option value="outbound">outbound</option>
													<option value="inbound">inbound</option>
												</select>
												
											</div>
										</div>
									</div>
								</div><!-- /.row -->
								
							</div><!-- /.box-body -->
					        <div class="box-footer">
								<button type="submit" class="btn btn-primary pull-right"> <i class="fa fa-search"></i> View</button>
								<?php 
									
								 ?>
									


									
								<?php
								
								?>

							</div>
						</div><!-- /.box -->
						</form>

				<?php
				}
				echo '
				
			</section>			
			';
			break;
		
		}


	}else{
		echo akses_salah();
	}
}
?>

<script>
	function ganti_manifest_from(clicked_id) {
			var id_manifest = clicked_id;
			//alert(id_manifest);
			$("#switch-manifest-from").modal('show');
			$.post('module/mod_report-manifest-outbound/report-manifest-outbound_action.php?module=outbound&act=switch-manifest-from', {
					id: id_manifest
				},
				function (html) {
					$(".modal-body").html(html);
				}
			);
	}

	function ganti_manifest_to(clicked_id) {
			var id_manifest = clicked_id;
			//alert(id_manifest);
			$("#switch-manifest-to").modal('show');
			$.post('module/mod_report-manifest-outbound/report-manifest-outbound_action.php?module=outbound&act=switch-manifest-to', {
					id: id_manifest
				},
				function (html) {
					$(".modal-body").html(html);
				}
			);
	}

	function popup_print_vendor(clicked_id) {
			var id_manifest = clicked_id;
			//alert(id_manifest);
			//alert(id_manifest);
			$("#modal_print_vendor").modal('show');
			$.post('module/mod_report-manifest-outbound/report-manifest-outbound_action.php?module=outbound&act=print_vendor', {
					id: id_manifest
				},
				function (html) {
					$(".modal-body").html(html);
				}
			);
	}

	/*function mergeCells() {
	  let db = document.getElementById("databody");
	  let dbRows = db.rows;
	  let lastValue = "";
	  let lastCounter = 1;
	  let lastRow = 0;
	  for (let i = 0; i < dbRows.length; i++) {
	    let thisValue = dbRows[i].cells[3].innerHTML;
	    if (thisValue == lastValue) {
	      lastCounter++;
	      dbRows[lastRow].cells[3].rowSpan = lastCounter;
	      dbRows[i].cells[3].style.display = "none";
	    } else {
	      dbRows[i].cells[3].style.display = "table-cell";
	      lastValue = thisValue;
	      lastCounter = 1;
	      lastRow = i;
	    }
	  }

	  for (let i = 0; i < dbRows.length; i++) {
	    let thisValue = dbRows[i].cells[4].innerHTML;
	    if (thisValue == lastValue) {
	      lastCounter++;
	      dbRows[lastRow].cells[4].rowSpan = lastCounter;
	      dbRows[i].cells[4].style.display = "none";
	    } else {
	      dbRows[i].cells[4].style.display = "table-cell";
	      lastValue = thisValue;
	      lastCounter = 1;
	      lastRow = i;
	    }
	  }

	  for (let i = 0; i < dbRows.length; i++) {
	    let thisValue = dbRows[i].cells[5].innerHTML;
	    if (thisValue == lastValue) {
	      lastCounter++;
	      dbRows[lastRow].cells[5].rowSpan = lastCounter;
	      dbRows[i].cells[5].style.display = "none";
	    } else {
	      dbRows[i].cells[5].style.display = "table-cell";
	      lastValue = thisValue;
	      lastCounter = 1;
	      lastRow = i;
	    }
	  }
	  for (let i = 0; i < dbRows.length; i++) {
	    let thisValue = dbRows[i].cells[6].innerHTML;
	    if (thisValue == lastValue) {
	      lastCounter++;
	      dbRows[lastRow].cells[6].rowSpan = lastCounter;
	      dbRows[i].cells[6].style.display = "none";
	    } else {
	      dbRows[i].cells[6].style.display = "table-cell";
	      lastValue = thisValue;
	      lastCounter = 1;
	      lastRow = i;
	    }
	  }
	  for (let i = 0; i < dbRows.length; i++) {
	    let thisValue = dbRows[i].cells[7].innerHTML;
	    if (thisValue == lastValue) {
	      lastCounter++;
	      dbRows[lastRow].cells[7].rowSpan = lastCounter;
	      dbRows[i].cells[7].style.display = "none";
	    } else {
	      dbRows[i].cells[7].style.display = "table-cell";
	      lastValue = thisValue;
	      lastCounter = 1;
	      lastRow = i;
	    }
	  }
	  for (let i = 0; i < dbRows.length; i++) {
	    let thisValue = dbRows[i].cells[7].innerHTML;
	    if (thisValue == lastValue) {
	      lastCounter++;
	      dbRows[lastRow].cells[7].rowSpan = lastCounter;
	      dbRows[i].cells[7].style.display = "none";
	    } else {
	      dbRows[i].cells[7].style.display = "table-cell";
	      lastValue = thisValue;
	      lastCounter = 1;
	      lastRow = i;
	    }
	  }
	  for (let i = 0; i < dbRows.length; i++) {
	    let thisValue = dbRows[i].cells[8].innerHTML;
	    if (thisValue == lastValue) {
	      lastCounter++;
	      dbRows[lastRow].cells[8].rowSpan = lastCounter;
	      dbRows[i].cells[8].style.display = "none";
	    } else {
	      dbRows[i].cells[8].style.display = "table-cell";
	      lastValue = thisValue;
	      lastCounter = 1;
	      lastRow = i;
	    }
	  }
	  for (let i = 0; i < dbRows.length; i++) {
	    let thisValue = dbRows[i].cells[10].innerHTML;
	    if (thisValue == lastValue) {
	      lastCounter++;
	      dbRows[lastRow].cells[10].rowSpan = lastCounter;
	      dbRows[i].cells[10].style.display = "none";
	    } else {
	      dbRows[i].cells[10].style.display = "table-cell";
	      lastValue = thisValue;
	      lastCounter = 1;
	      lastRow = i;
	    }
	  }
	  for (let i = 0; i < dbRows.length; i++) {
	    let thisValue = dbRows[i].cells[9].innerHTML;
	    if (thisValue == lastValue) {
	      lastCounter++;
	      dbRows[lastRow].cells[9].rowSpan = lastCounter;
	      dbRows[i].cells[9].style.display = "none";
	    } else {
	      dbRows[i].cells[9].style.display = "table-cell";
	      lastValue = thisValue;
	      lastCounter = 1;
	      lastRow = i;
	    }
	  }
	  for (let i = 0; i < dbRows.length; i++) {
	    let thisValue = dbRows[i].cells[11].innerHTML;
	    if (thisValue == lastValue) {
	      lastCounter++;
	      dbRows[lastRow].cells[11].rowSpan = lastCounter;
	      dbRows[i].cells[11].style.display = "none";
	    } else {
	      dbRows[i].cells[11].style.display = "table-cell";
	      lastValue = thisValue;
	      lastCounter = 1;
	      lastRow = i;
	    }
	  }
	}

	window.onload = mergeCells;*/

	
	


</script>	