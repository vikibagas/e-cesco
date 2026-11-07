<section class="content-header">
	<h1>Costing outbound<small>airwaybill costing</small></h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-home"></i> Home</a></li>
		<li><a href="#">Costing</a></li>
		<li class="active">outbound</li>
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
	$id_user = $u['id_user'];
	if($u['level']=='admin' || $u['id_user']== 5 || $u['id_user']== 77 ||  $u['level']=='kurir' || $u['level']=='driver' || $u['level']=='operator' || $u['level']=='intercity' || $u['level']=='finance' || $u['level']=='domex' || $u['level']=='network' || $u['level']=='agent' || ($u['level']=='agent' && $u['cabang_cesco'] == 'Y')){

		$aksi	= "module/mod_costing-outbound/costing-outbound_action.php";

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
											<button class="btn btn-sm btn-default" onclick="window.location.href = \'?module=costing-outbound&act=create\';"><i class="fa fa-plus text-green"></i> Create</button>
										</div>
									</div>
									<div class="col-md-6">
										<div class="btn-group pull-right">
											<button class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown">Tools <i class="fa fa-angle-down"></i></button>
											<ul class="dropdown-menu pull-right">

												<li><a href="main.php?module=costing-outbound&act=search" class=""><i class="fa fa-search"></i>Search By Airwaybill</a></li>
												';
												if ($u['level'] == 'admin' || $u['level'] == 'finance'){
													echo '
													<li><a href="main.php?module=costing-outbound&act=report" class=""><i class="fa fa-file-excel-o"></i>Report</a></li>
													<li><a href="main.php?module=costing-outbound&act=report-vendor" class=""><i class="fa fa-file-excel-o"></i>Report Vendor</a></li>
													<li><a href="main.php?module=costing-outbound&act=cari-merah" target="_blank" class=""><i class="fa fa-search"></i>Cari Merah</a></li>
													';
												}
												echo '

											</ul>
										</div>
									</div>
								</div>
							</div>
							<div class="box-body">
								<table class="table table-striped table-bordered table-hover" id="costing-outbound" style="white-space:nowrap;">
									<thead>
										<tr>
											<th>Manifest Date</th>
											<th>Manifest By</th>
											<th>Manifest From</th>
											<th>Manifest To</th>
											<th>Manifest via</th>
											<th>Qty</th>
											<th>Weight</th>
											<th>Parking Fee</th>
											<th>Cost outbound</th>
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

			case "create":
			echo'
			<section class="content">
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">Manifest outbound</h3>
						<div class="box-tools pull-right">
							<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
							<button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
						</div>
					</div>
					<form class="form-horizontal form-bordered" method="post" action="main.php?module=costing-outbound&act=create" autocomplete="off">
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
												<input type="text" class="form-control" id="daterange" name="tanggalrange" value="'.$tanggal.'">
										</div>
										</div>
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<label class="col-md-2 control-label">Manifest By</label>
										<div class="col-md-10">
											<select name="id_user" id="kurir-outbound-costing" data-placeholder="Choose..." class="form-control select2">
												<option value="0">all</option>';
												$kurir = $mysqli->query("SELECT * FROM user WHERE level IN ('kurir','driver','finance','operator','domex','network','agent','intercity','network','logistics') AND blokir='N' ORDER BY namalengkap");
												while($k = $kurir->fetch_assoc()){
													echo'<option value="'.$k['id_user'].'">'.$k['namalengkap'].'</option>';
												}
												echo'
											</select>
										</div>
									</div>
								</div>
							</div>

						</div>
						<div class="box-footer">
							<button type="button" class="btn btn-default" onclick="window.location.href=\'?module=costing-outbound\'">Cancel</button>
							<button type="submit" class="btn btn-primary pull-right"><i class="fa fa-send"></i> Submit</button>
						</div>
					</form>
					<div id="table-outbound-kurir"></div>
				</div>
			';

			if ($_SERVER["REQUEST_METHOD"] == "POST") {
						$tanggaldari = $_POST['tanggaldari'];
										$tanggalke = $_POST['tanggalke'];
										$tanggalrange = $_POST['tanggalrange'];
										$tanggalrange = mysqli_real_escape_string($con,$_POST['tanggalrange']);

											$dr = strleft($tanggalrange, 10);
											$sm = strright($tanggalrange, 10);
											$dari = jin_date_sql($dr);
											$sampai = jin_date_sql($sm);
										$id_user = $_POST['id_user'];

						$query = $mysqli->query("select sum(coalesce(a.costing_outbound_fee,0)) + sum(coalesce(a.costing_outbound_parking,0)) + sum(coalesce(a.costing_outbound_toll,0)) + sum(coalesce(a.costing_outbound_menpower,0)) + sum(coalesce(a.costing_outbound_smu,0)) + sum(coalesce(a.costing_outbound_handling,0)) as total_cost, a.costing_outbound_save, c.namalengkap as manifest_by, sum(coalesce(a.costing_outbound_fee,0)) as costing_outbound_fee, sum(coalesce(a.costing_outbound_parking,0)) as costing_outbound_parking, sum(coalesce(a.costing_outbound_toll,0)) as costing_outbound_toll, sum(coalesce(a.costing_outbound_menpower,0)) as costing_outbound_menpower, sum(coalesce(a.costing_outbound_smu,0)) as costing_outbound_smu, sum(coalesce(a.costing_outbound_handling,0)) as costing_outbound_handling from pengiriman a  left join user c on (c.id_user=a.created_by) where created_by='$id_user' and DATE_FORMAT(courier_date,'%Y-%m-%d') BETWEEN '$dari' AND '$sampai'  and x_intercity(a.lokasi_asal) ='Y' and x_intercity(a.lokasi_tujuan)!='Y'  ");

							/*echo "select sum(coalesce(a.costing_outbound_fee,0)) + sum(coalesce(a.costing_outbound_parking,0)) + sum(coalesce(a.costing_outbound_toll,0)) + sum(coalesce(a.costing_outbound_menpower,0)) + sum(coalesce(a.costing_outbound_smu,0)) + sum(coalesce(a.costing_outbound_handling,0)) as total_cost, a.costing_outbound_save, c.namalengkap as manifest_by, sum(coalesce(a.costing_outbound_fee,0)) as costing_outbound_fee, sum(coalesce(a.costing_outbound_parking,0)) as costing_outbound_parking, sum(coalesce(a.costing_outbound_toll,0)) as costing_outbound_toll, sum(coalesce(a.costing_outbound_menpower,0)) as costing_outbound_menpower, sum(coalesce(a.costing_outbound_smu,0)) as costing_outbound_smu, sum(coalesce(a.costing_outbound_handling,0)) as costing_outbound_handling from pengiriman a  left join user c on (c.id_user=a.created_by) where created_by='$id_user' and DATE_FORMAT(courier_date,'%Y-%m-%d') BETWEEN '$dari' AND '$sampai'  and x_intercity(a.lokasi_asal) ='Y' and x_intercity(a.lokasi_tujuan)!='Y' ";*/
							$r = $query->fetch_assoc();
				?>

			<div class="row">
				<div class="col-md-12">
					<div class="box box-primary">
                		<div class="box-header">
							<h3 class="box-title">List</h3>
						</div>
						<div class="box-body">

							<table id="invoice" class="table table-striped table-bordered table-hover " width="100%" cellspacing="0" style="white-space:nowrap;">
								<thead>
									<tr>
										<th>Airwaybill</th>
										<th>outbound date</th>
										<th>Customer</th>
										<th>Origin</th>
										<th>Destination</th>
										<th>Collie</th>
										<th>Weight</th>
										<th>Comodity</th>
										<th>outbound Cost</th>
										<th>Parking Fee</th>
										<th>Toll Fee</th>
										<th>SMU Cost</th>
										<th>Handling Cost</th>
										<th>Men Power</th>
										<th>Total cost</th>
										<th>action </th>
									</tr>
								</thead>
								<tbody>
									<?php


										if ($id_user != 0){
											$id_user = $id_user;
										}
										else{
											$id_user = 'is not null';
										}

										$no=1;

										$result_list = $mysqli->query("select id_pengiriman as id_pengiriman,created_date, costing_outbound_fee as costing_outbound_fee, costing_outbound_parking as costing_outbound_parking, costing_outbound_toll as costing_outbound_toll, costing_outbound_menpower as costing_outbound_menpower, costing_outbound_smu as costing_outbound_smu, costing_outbound_handling as costing_outbound_handling, airwaybill_kode, x_namakustomer(id_kustomer) as kustomer, x_namakotabesar(lokasi_asal) as lokasi_asal, x_namakotabesar(lokasi_tujuan) as lokasi_tujuan, collie, if(berat_aktual > 0, berat_aktual, berat_barang) as berat_barang, case when komoditi='1' then 'paket' else 'Dokumen' end as komoditi, x_service(service) as service, ifnull(costing_outbound_fee, 0) + ifnull(costing_outbound_parking, 0) + ifnull(costing_outbound_toll, 0) + ifnull(costing_outbound_menpower, 0) + ifnull(costing_outbound_smu, 0) + ifnull(costing_outbound_handling, 0) as total_cost from pengiriman  where created_by='$id_user' and DATE_FORMAT(courier_date,'%Y-%m-%d') BETWEEN '$dari' AND '$sampai'  and x_intercity(lokasi_asal) !='Y' and x_intercity(lokasi_tujuan) ='Y'  order by created_date asc");

										/*echo "select id_pengiriman as id_pengiriman,created_date, costing_outbound_fee as costing_outbound_fee, costing_outbound_parking as costing_outbound_parking, costing_outbound_toll as costing_outbound_toll, costing_outbound_menpower as costing_outbound_menpower, costing_outbound_smu as costing_outbound_smu, costing_outbound_handling as costing_outbound_handling, airwaybill_kode, x_namakustomer(id_kustomer) as kustomer, x_namakotabesar(lokasi_asal) as lokasi_asal, x_namakotabesar(lokasi_tujuan) as lokasi_tujuan, collie, if(berat_aktual > 0, berat_aktual, berat_barang) as berat_barang, case when komoditi='1' then 'paket' else 'Dokumen' end as komoditi, x_service(service) as service, ifnull(costing_outbound_fee, 0) + ifnull(costing_outbound_parking, 0) + ifnull(costing_outbound_toll, 0) + ifnull(costing_outbound_menpower, 0) + ifnull(costing_outbound_smu, 0) + ifnull(costing_outbound_handling, 0) as total_cost from pengiriman  where created_by='$id_user' and DATE_FORMAT(courier_date,'%Y-%m-%d') BETWEEN '$dari' AND '$sampai'  and x_intercity(a.lokasi_asal) !='Y' and x_intercity(a.lokasi_tujuan) ='Y'  order by created_date asc";*/
										while ($rowlist = $result_list->fetch_assoc()){
											echo '
												<tr>
													<td>'.$rowlist['airwaybill_kode'].'</td>
													<td>'.$rowlist['created_date'].'</td>
													<td>'.$rowlist['kustomer'].'</td>
													<td>'.strtoupper($rowlist['lokasi_asal']).'</td>
													<td>'.strtoupper($rowlist['lokasi_tujuan']).'</td>
													<td>'.$rowlist['collie'].'</td>
													<td>'.$rowlist['berat_barang'].'</td>
													<td>'.$rowlist['komoditi'].'</td>
													<td>'.round($rowlist['costing_outbound_fee']). '</td>
													<td>'.round($rowlist['costing_outbound_parking']). '</td>
													<td>'.round($rowlist['costing_outbound_toll']). '</td>
													<td>'.round($rowlist['costing_outbound_smu']). '</td>
													<td>'.round($rowlist['costing_outbound_handling']). '</td>
													<td>'.round($rowlist['costing_outbound_menpower']). '</td>
													<td>'.round($rowlist['total_cost']). '</td>
													<td>
														<center>

									<a href="#" class="btn btn-xs btn-warning open_switch_outbound_detail" data-id="'.$row['id_manifest_detail'].'"><i class="fa fa-exchange"></i></a></center>
													</td>
												</tr>
											';
											$no++;
										}
									?>
								</tbody>
							</table>
							<div class"row">
								<div class="col-md-offset-6 col-md-6">
									<div class="form-group">
										<label class="col-md-4 control-label">Total Cost Fee</label>
										<div class="col-md-8">
											<div class="input-group">
												<div class="input-group-addon">
													IDR
												</div>
												<input type="text" name="total_cost" class="form-control" value=" <?php echo round($r['total_cost']); ?> " readonly>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<a target="_blank" href="<?php echo "reports/PHPExcel/excel_createcosting_outbound.php?id_user=$id_user&datefrom=$dari&dateuntil=$sampai"?> "><button type="button" class="btn btn-primary pull-right" ><i class="fa fa-file-excel-o"></i> Excel</button></a>
							</div>
						</div>
					</div>
				</div>
			</div>
				<?php
			}
			echo '</section>';
			break;

			case "view":

			$key = "asdf$1011";
			$encrypt = "$_GET[id]";
			$id = base64_decrypt($encrypt,$key);

			$query = mysqli_query($con, "select a.id_manifest, a.tanggal, DATE_FORMAT(a.tanggal,'%Y-%m-%d') as tgloutbound, a.kode_manifest, x_user(a.created_by) as manifest_by, case when x_intercity(b.id_kota)='Y' then 'JAKARTA' else b.nama_cabang end as manifest_from, case when x_intercity(b.id_kota)='Y' then 'CESCO LOGISTICS' else b.alamat1 end as manifestfrom, case when x_intercity(c.id_kota)='Y' then 'JAKARTA' else c.nama_cabang end as manifest_to, case when x_intercity(c.id_kota)='Y' then 'CESCO LOGISTICS' else c.alamat1 end as manifestto,
			case when a.manifest_via = 1 then 'AMTL' else a.manifest_via end as manifest_via,
			a.no_smu, a.created_by
			from manifest a
			inner join cabang b on b.id_cabang=a.manifest_from
			inner join cabang c on c.id_cabang=a.manifest_to
			where
			a.id_manifest='$id'");
			$r = mysqli_fetch_assoc($query);

			$queryCh = mysqli_query($con, "select count(manifest_id) as change_manifest_via from manifest_via_change where manifest_id ='$id'");
			$rChange = mysqli_fetch_assoc($queryCh);

			$disableChange = ($rChange['change_manifest_via'] > 0) ? 'disabled' : '';

			$result = mysqli_query($con,"select sum(coalesce(a.costing_outbound_fee,0)) + sum(coalesce(a.costing_outbound_parking,0)) + sum(coalesce(a.costing_outbound_toll,0)) + sum(coalesce(a.costing_outbound_menpower,0)) + sum(coalesce(a.costing_outbound_smu,0)) + sum(coalesce(a.costing_outbound_handling,0)) + sum(coalesce(a.costing_outbound_insurance,0)) as total_cost, 

				a.costing_outbound_save, 
				c.namalengkap as nama_costing, 
				sum(coalesce(a.costing_outbound_fee,0)) as costing_outbound_fee, 
				sum(coalesce(a.costing_outbound_parking,0)) as costing_outbound_parking, sum(coalesce(a.costing_outbound_toll,0)) as costing_outbound_toll, sum(coalesce(a.costing_outbound_menpower,0)) as costing_outbound_menpower, sum(coalesce(a.costing_outbound_smu,0)) as costing_outbound_smu, sum(coalesce(a.costing_outbound_handling,0)) as costing_outbound_handling 

				from pengiriman a inner join manifest_detail b on a.id_pengiriman = b.id_pengiriman left join user c on (c.id_user=a.costing_outbound_by) where b.id_manifest='$r[id_manifest]' ");
			$r_cost = mysqli_fetch_assoc($result);

			$tanggal = tgl_indo($r['tanggal']);

			echo'
			<section class="content">
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">Manifest outbound</h3>
						<div class="box-tools pull-right">
							<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
							<button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
						</div>
					</div><!-- /.box-header -->
					<form class="form-horizontal form-bordered" method="post" action="'.$aksi.'?module=costing-outbound&act=calculate">
					<input type="hidden" name="id_manifest" value="'.$r['id_manifest'].'">
					<input type="hidden" name="tgloutbound" class="form-control" value="'.$r['tgloutbound'].'">
					<input type="hidden" name="tgloutbound" class="form-control" value="'.$r['tgloutbound'].'">
					<div class="box-body">
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label class="col-md-4 control-label">Manifest Code</label>
									<div class="col-md-8">
											<input type="text" id="kode_manifest" class="form-control" value="'.$r['kode_manifest'].'"readonly>
									</div>
								</div>
							</div>
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
									<label class="col-md-4 control-label">outbound Cost</label>
									<div class="col-md-8">
										<div class="input-group">
											<div class="input-group-addon">
												IDR
											</div>
											<input type="text" name="outbound_cost" class="form-control" value=" '.round($r_cost['costing_outbound_fee']).'" '.($u['level'] != 'admin' or $u['id_user'] != '77' ? ($r_cost['costing_outbound_save'] != null ? 'readonly' : '') : 'enable admin' ).'>
										</div>
									</div>
								</div>
							</div>
						</div>
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
									<label class="col-md-4 control-label">Manifest To</label>
									<div class="col-md-8">
										<input type="text" class="form-control" value="'.strtoupper($r['manifest_to']).' ('.strtoupper($r['manifestto']).')" readonly>
									</div>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label class="col-md-4 control-label">Toll Fee</label>
									<div class="col-md-8">
										<div class="input-group">
											<div class="input-group-addon">
												IDR
											</div>
											<input type="text" name="toll_fee" class="form-control" value=" '.round($r_cost['costing_outbound_toll']).'" '.($u['level'] != 'admin' or $u['id_user'] != '77' ? ($r_cost['costing_outbound_save'] != null ? 'readonly' : '') : 'enable admin' ).'>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label class="col-md-4 control-label">Manifest By</label>
									<div class="col-md-8">
										<input type="text" class="form-control" value="'.strtoupper($r['manifest_by']).'" readonly>
										<input type="hidden" name="created_by" value="'.$r['created_by'].'">
									</div>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label class="col-md-4 control-label">Manifest Via</label>
									<div class="col-md-8">
									<select class="form-control select2" name="manifest_via" id="cost_outbond_manifest_via" '. $disableChange .'>
											';
													$select_agent = mysqli_query($con,"select id,name from manifest_vendor where active = 'Y' order by name");
													while($c=mysqli_fetch_assoc($select_agent)){
														  $sel = (strtoupper($r['manifest_via']) == strtoupper($c['name'])) ? 'selected="true"' : "";
															echo '<option value="'.$c['name'].'" '.$sel.'> '.$c['name'].'</option>';
													}
											echo '
									</select>
									<input type="hidden" name="manifest_via_asal" id="manifest_via_asal" value="'.$r['manifest_via'].'">
									<input type="hidden" name="id_manifest" id="id_manifest" value="'.$id.'">

									</div>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label class="col-md-4 control-label">SMU Cost</label>
									<div class="col-md-8">
										<div class="input-group">
											<div class="input-group-addon">
												IDR
											</div>
											<input type="text" name="smu_cost" class="form-control" value=" '.round($r_cost['costing_outbound_smu']).'" '.($u['level'] != 'admin' or $u['id_user'] != '77' ? ($r_cost['costing_outbound_save'] != null ? 'readonly' : '') : 'enable admin' ).'>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label class="col-md-4 control-label">Costing by</label>
									<div class="col-md-8">
										<input type="text" readonly name="costing_by" class="form-control" value="'.$r_cost['nama_costing'].'">
									</div>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label class="col-md-4 control-label">No. SMU/Resi</label>
									<div class="col-md-8">
										<input type="text" class="form-control" value="'.$r['no_smu'].'" name="no_smu" readonly>
									</div>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label class="col-md-4 control-label">Handling fee</label>
									<div class="col-md-8">
										<div class="input-group">
											<div class="input-group-addon">
												IDR
											</div>
											<input type="text" name="handling_cost" class="form-control" value=" '.round($r_cost['costing_outbound_handling']).'" '.($u['level'] != 'admin' or $u['id_user'] != '77' ? ($r_cost['costing_outbound_save'] != null ? 'readonly' : '') : 'enable admin' ).'>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
							</div>
							<div class="col-md-4">
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label class="col-md-4 control-label">Parking fee</label>
									<div class="col-md-8">
										<div class="input-group">
											<div class="input-group-addon">
												IDR
											</div>
											<input type="text" name="parking_fee" class="form-control" value=" '.round($r_cost['costing_outbound_parking']).'" '.($u['level'] != 'admin' or $u['id_user'] != '77' ? ($r_cost['costing_outbound_save'] != null ? 'readonly' : '') : 'enable admin' ).'>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label class="col-md-4 control-label">Total Cost</label>
									<div class="col-md-8">
										<div class="input-group">
											<div class="input-group-addon">
												IDR
											</div>
											<input type="text" name="total_cost" class="form-control" value=" '.round($r_cost['total_cost']).'" readonly>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label class="col-md-4 control-label">Men Power</label>
									<div class="col-md-8">
										<div class="input-group">
											<div class="input-group-addon">
												IDR
											</div>
											<input type="text" name="menpower_cost" class="form-control" value=" '.round($r_cost['costing_outbound_menpower']).'" '.($u['level'] != 'admin' or $u['id_user'] != '77' ? ($r_cost['costing_outbound_save'] != null ? 'readonly' : '') : 'enable admin' ).'>
										</div>
									</div>
								</div>
							</div>
						</div>
						<table class="table table-striped table-bordered table-hover" id="CostingInDetail" style="white-space:nowrap;">
						<thead>
							<tr>
								<th style="width: 10px">#</th>
								<th>Airwaybill</th>
								<th>Customer</th>
								<th>Origin</th>
								<th>Destination</th>
								<th>Collie</th>
								<th>Weight</th>
								<th>Volume</th>
								<th>Comodity</th>
								<th>outbound Cost</th>
								<th>Toll fee</th>
								<th>SMU Cost</th>
								<th>Handling fee</th>
								<th>Parking fee</th>
								<th>menpower fee</th>
								<th>Insurance fee</th>
								<th>Total cost</th>
								<th>Action</th>

							</tr>
						</thead>
						<tbody>';
							$no=1;

							$sql = "select
									a.id_manifest_detail,
									b.id_pengiriman as id_pengiriman,
									b.costing_outbound_fee as costing_outbound_fee,
									b.costing_outbound_parking as costing_outbound_parking,
									b.costing_outbound_toll as costing_outbound_toll,
									b.costing_outbound_menpower as costing_outbound_menpower,
									b.costing_outbound_smu as costing_outbound_smu,
									ifnull(b.costing_outbound_handling, 0) as costing_outbound_handling,
									ifnull(b.costing_outbound_insurance, 0) as costing_outbound_insurance,
									airwaybill_kode,
									x_namakustomer(b.id_kustomer) as kustomer,
									b.kota_asal as lokasi_asal,
									b.kota_tujuan as lokasi_tujuan,
									b.id_pengiriman,
									/*x_namakotabesar(lokasi_asal) as lokasi_asal,
									x_namakotabesar(lokasi_tujuan) as lokasi_tujuan,*/
								cs.kode_kustomer,
								CASE
										WHEN airwaybill_kode LIKE '001-%' THEN 0
										WHEN airwaybill_kode LIKE '002-%' THEN 1
									end as intercity,
									SUBSTRING_INDEX(ukuran_barang, 'x', 1) as length,
									SUBSTRING_INDEX(SUBSTRING_INDEX(ukuran_barang, 'x', 2),
											'x',
											- 1) as width,
									SUBSTRING_INDEX(ukuran_barang, 'x', - 1) as height,
									case when upper(cs.kode_kustomer) in ('CIS','DEL') then
									  if((select intercity) = 1,
										((select length) * (select width) * (select height) / 4000),
										if(b.service = 5,
											((select length) * (select width) * (select height) / 4000),
											((select length) * (select width) * (select height) / 6000)))
									else
										if((select intercity) = 1,
											ceiling((select length) * (select width) * (select height) / 4000),
											if(b.service = 5,
												ceiling((select length) * (select width) * (select height) / 4000),
												ceiling((select length) * (select width) * (select height) / 6000)))
									end as volume,
									collie,
									if(berat_aktual > 0,
										berat_aktual,
										berat_barang) as berat_barang,
									case
										when komoditi = '1' then '<button class=\"btn btn-block btn-xs\"><i class=\"fa fa-cube\"></i> Paket</button>'
										else '<button class=\"btn btn-block btn-xs\"><i class=\"fa fa-book\"></i> Dokumen</button>'
									end as komoditi,
									x_service(service) as service,
									ifnull(b.costing_outbound_fee, 0) + ifnull(b.costing_outbound_parking, 0) + ifnull(b.costing_outbound_toll, 0) + ifnull(b.costing_outbound_menpower, 0) + ifnull(b.costing_outbound_smu, 0) + ifnull(b.costing_outbound_handling, 0) + ifnull(b.costing_outbound_insurance, 0) as total_cost
								from
									manifest_detail a
										left join
									pengiriman b ON b.id_pengiriman = a.id_pengiriman
								left join kustomer cs on cs.id_kustomer = b.id_kustomer
								where
									a.id_manifest = '$r[id_manifest]'
								group by a.id_pengiriman
								order by b.created_date asc";

							$result = mysqli_query($con,$sql);
//echo "select a.id_manifest_detail,b.id_pengiriman as id_pengiriman,  b.costing_outbound_fee as costing_outbound_fee, b.costing_outbound_parking as costing_outbound_parking, b.costing_outbound_toll as costing_outbound_toll, b.costing_outbound_menpower as costing_outbound_menpower, b.costing_outbound_smu as costing_outbound_smu, b.costing_outbound_handling as costing_outbound_handling, airwaybill_kode, x_namakustomer(id_kustomer) as kustomer, x_namakotabesar(lokasi_asal) as lokasi_asal, x_namakotabesar(lokasi_tujuan) as lokasi_tujuan, collie, if(berat_aktual > 0, berat_aktual, berat_barang) as berat_barang, case when komoditi='1' then '<button class=\"btn btn-block btn-xs\"><i class=\"fa fa-cube\"></i> Paket</button>' else '<button class=\"btn btn-block btn-xs\"><i class=\"fa fa-book\"></i> Dokumen</button>' end as komoditi, x_service(service) as service, ifnull(b.costing_outbound_fee, 0) + ifnull(b.costing_outbound_parking, 0) + ifnull(b.costing_outbound_toll, 0) + ifnull(b.costing_outbound_menpower, 0) + ifnull(b.costing_outbound_smu, 0) + ifnull(b.costing_outbound_handling, 0) as total_cost from manifest_detail a left join pengiriman b on b.id_pengiriman=a.id_pengiriman where a.id_manifest='$r[id_manifest]' group by a.id_pengiriman  order by created_date asc ";
							while ($row = mysqli_fetch_assoc($result)){
								$key = "asdf$1011";
								$stringawal = $row["id_pengiriman"];
								$id = base64_encrypt($stringawal,$key);
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
									<td>'.$row['collie'].'</td>
									<td>'.$row['berat_barang'].'</td>
									<td><a href="#" class="open_koli_detail" data-id="'.$row['id_pengiriman'].'">view detail <i class="fa fa-eye"></i></a></td>
									<td>'.$row['komoditi'].'</td>
									<td>'.number_format(round($row['costing_outbound_fee']),0).'</td>
									<td>'.number_format(round($row['costing_outbound_toll']),0).'</td>
									
									<td><span id="spSmu'.$row['id_pengiriman'].'">'.round($row['costing_outbound_smu']).'</span> <a href="#" class="biaya-smu-outbound" data-type="text" data-pk="'.$row['id_pengiriman'].'" title="edit">  <i class="fa fa-edit"> </i> </a>
									</td>
									
									<td><span id="spHandling'.$row['id_pengiriman'].'">'.round($row['costing_outbound_handling']).'</span> <a href="#" class="biaya-handling-outbound" data-type="text" data-pk="'.$row['id_pengiriman'].'" title="edit">  <i class="fa fa-edit"> </i> </a>
									</td>
									
									

									<td>'.number_format(round($row['costing_outbound_parking']),0).'</td>
									<td>'.number_format(round($row['costing_outbound_menpower']),0).'</td>
									<td><span id="spInsurance'.$row['id_pengiriman'].'">'.round($row['costing_outbound_insurance']).'</span> <a href="#" class="biaya-insurance-outbound" data-type="number" data-pk="'.$row['id_pengiriman'].'" data-value="'.$row['costing_outbound_insurance'].'" title="edit">  <i class="fa fa-edit"> </i> </a>
									</td>
									<td>'.number_format(round($row['total_cost']),0).'</td>
									<td><center>
									<a href="#" class="btn btn-xs btn-warning open_switch_outbound_detail" data-id="'.$row['id_manifest_detail'].'"><i class="fa fa-exchange"></i></a></center>
									</td>
								</tr>';
								$no++;
							}

							echo'
							</tbody>
						</table>
					</div><!-- /.box-body -->
					<div class="box-footer">
						<button type="button" class="btn btn-default" onclick="window.location.href = \'?module=costing-outbound\';">back</button>
						<input type="submit" class="btn btn-success" id="calculate-costing-outbound" value="calculate" '.($u['level'] != 'admin' or $u['id_user'] != '77' ? ($r_cost['costing_outbound_save'] != null ? 'disabled' : '') : 'enable admin' ).'>
						<button type="button" class="btn btn-success" id="save" onclick="window.location.href = \'module/mod_costing-outbound/costing-outbound_action.php?module=costing-outbound&act=save&idmanifest='.$r['id_manifest'].'\';" '.($r_cost['costing_outbound_menpower'] == 0  ? 'disabled' : '') .' '.($u['level'] != 'admin' ? ($r_cost['costing_outbound_save'] != null ? 'disabled' : '') : 'enable admin' ).'> save</button>
						<button type="button" class="btn btn-primary pull-right" '.($r_cost['costing_outbound_save'] != null ? '' : 'disabled').' onClick="window.open(\'reports/costing-outbound.php?id='.$encrypt.'\',\'mywindow\',\'menubar=1,resizable=1,width=800,height=600\');" ><i class="fa fa-print"></i> Print</button>

						<a target="_blank" href="reports/PHPExcel/excel_costing_outbound.php?id='.$encrypt.'"><button type="button" class="btn btn-primary pull-right" '.($r_cost['costing_outbound_save'] != null ? '' : 'disabled').'><i class="fa fa-file-excel-o"></i> Excel</button></a>

						<a target="_blank" href="reports/reportpdf/costing_outbound_pdf.php?id='.$encrypt.'"><button type="button" class="btn btn-primary pull-right" '.($r_cost['costing_outbound_save'] != null ? '' : 'disabled').' ><i class="fa fa-file-pdf-o"></i> PDF</button></a>

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
			<div id="koli-detail" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
							<h4 class="modal-title" id="myModalLabel">Detail Collie</h4>
						</div>
						<div class="modal-content">
							<div class="modal-body"></div>
						</div>
					</div>
				</div>
			</div>
			<div id="modal-inputparkingcost" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
							<h4 class="modal-title" id="myModalLabel">Input Parking fee</h4>
						</div>
						<div class="modal-content">
							<div class="modal-body">
								<form action="'.$aksi.'?module=costing-outbound&act=costingparking" name="form_costing" method="POST">
									<input type="hidden" name="id" value="" id="idpengiriman">
									<input type="number" class="form-control" id="parking_fee" name="parking_fee" value="1000" min="1000">
							</div>
							<div class="modal-footer">
									<button class="btn btn-success" type="submit">Confirm</button>
									<button type="reset" class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Cancel</button>
									</div>
								</form>
						</div>
					</div>
				</div>
			</div>

			<div id="modal-inputmencost" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
							<h4 class="modal-title" id="myModalLabel">Input Men Power fee</h4>
						</div>
						<div class="modal-content">
							<div class="modal-body">
								<form action="'.$aksi.'?module=costing-outbound&act=costing_men_power" name="form_costing" method="POST">
									<input type="hidden" name="id" value="" id="idpengiriman">
									<input type="number" class="form-control" id="men_power" name="men_power" value="1000" min="1000">
							</div>
							<div class="modal-footer">
									<button class="btn btn-success" type="submit">Confirm</button>
									<button type="reset" class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Cancel</button>
									</div>
								</form>
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
									<button type="button" name="costing-outbound"  data-id="costing-outbound" id="btnsearchcosting" class="btn form-control btn-primary">search</button>
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

			case "cari-merah":
			echo'
			<section class="content">
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">Search</h3>

					</div><!-- /.box-header -->
					<div class="box-body">
						<div class="form-group">
							<label class="col-sm-3 control-label full-right">Cari Merah data 1 tahun terakhir</label>
							<div class="col-sm-6">
								<div class="input-group">
									
									<button type="button" name="costing-outbound" data-id="costing-outbound" id="btnsearchmerah" class="btn form-control btn-primary">search</button>
							</div>
						</div>
						<div id="load" style="display:none;"><img src="dist/img/Loading_icon.gif"/ style="display:block; margin: 0 auto; "></div>

						<table id="airwaybillsrc-merah" class="table table-bordered table-striped table-hover" width="100%" cellspacing="0" style="white-space:nowrap; margin-top:50px;">
									<thead>
										<tr style="text-transform: uppercase;">
											<th>Tanggal</th>
											<th>AWB</th>
											<th>From > To (via)</th>
											<th width="90px">View</th>
										</tr>
									<thead>
									<tbody id="hasil">
										<tr  style="text-transform: uppercase;">
											<td></td>
											<td></td>
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

			case "report":
			if ($_SERVER["REQUEST_METHOD"] == "POST") {
				$tanggal = $_POST['tanggal'];
				$id_user = $_POST['id_user'];
				$dr = strleft($tanggal, 10);
						$sm = strright($tanggal, 10);
						$dari = jin_date_sql($dr);
						$sampai = jin_date_sql($sm);
			}
			echo'
				<section class="content">
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">Report Costing Outbound</h3>

					</div><!-- /.box-header -->
					<form class="form-horizontal" method="post" action="" autocomplete="off">
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
											<input type="text" class="form-control" id="daterange" name="tanggal" value="'.$tanggal.'">
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-5">
								<div class="form-group">
									<label class="col-sm-4 control-label full-right">Kurir</label>
									<div class="col-sm-8">
										<select name="id_user" class="form-control select2">
										';
										$query=mysqli_query($con,'select id_user, namalengkap from user where level in("kurir","driver","agent","kurir_agent") and id_cabang in (11,16,39)  order by namalengkap');
										while($r=mysqli_fetch_assoc($query)){
											if($r['id_user']==$id_user){
												echo'
												<option value="'.$r['id_user'].'" selected>'.$r['namalengkap'].'</option>';
											}else{
												echo'
												<option value="'.$r['id_user'].'">'.$r['namalengkap'].'</option>';
											}
										}
										echo '
										</select>
										</select>
									</div>
								</div>
							</div>
						</div><!-- /.row -->

					</div>
					<div class="box-footer">
						<button type="submit" class="btn btn-primary pull-right"> <i class="fa fa-search"></i> View</button>
						';
						if ($_SERVER["REQUEST_METHOD"] == "POST") {
							echo '
							<a  readonly target="_blank" href="reports/PHPExcel/excel_report_costing_outbound_peruser.php?id_user='.$id_user.'&dari='.$dari.'&sampai='.$sampai.' "><button type="button" class="btn btn-primary pull-right" ><i class="fa fa-file-excel-o"></i> Excel</button></a>
							';
						}
							echo '
					</div>
					</form>
				</div>
				';

				if ($_SERVER["REQUEST_METHOD"] == "POST") {
					//$tanggal = $_POST['tanggal'];
					$id_user = $_POST['id_user'];

					$id_kustomer = mysqli_real_escape_string($con, $_POST['id_kustomer']);
						$tanggal = mysqli_real_escape_string($con, $_POST['tanggal']);
						$dr = strleft($tanggal, 10);
						$sm = strright($tanggal, 10);
						$dari = jin_date_sql($dr);
						$sampai = jin_date_sql($sm);


				echo '
				<!-- /. mulai view-->
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">Report Manifest Outbound</h3>
						<div class="box-tools pull-right">
							<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
							<button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
						</div>
					</div><!-- /.box-header -->
					<div class="box-body">


						<table class="table table-striped table-bordered table-hover" id="report" style="white-space:nowrap;">
						<thead>
							<tr>
								<th>Outbound date</th>
								<th>Airwaybill</th>
								<th>Customer</th>
								<th>Origin</th>
								<th>Destination</th>
								<th>Collie</th>
								<th>Weight</th>
								<th>Comodity</th>
								<th>Outbound Cost</th>
								<th>Parking Fee</th>
								<th>Toll Fee</th>
								<th>SMU Cost</th>
								<th>Handling Fee</th>
								<th>Men Power</th>
								<th>Total cost</th>
							</tr>
						</thead>
						<tbody>';
						/*echo "select id_pengiriman, airwaybill_kode, x_namakustomer(id_kustomer) as kustomer, x_namakotabesar(lokasi_asal) as lokasi_asal, x_namakotabesar(lokasi_tujuan) as lokasi_tujuan, collie, if(berat_aktual > 0, berat_aktual, berat_barang) as berat_barang , case when komoditi='1' then 'Paket' else 'Dokumen' end as komoditi, costing_outbound_fee, costing_outbound_parking, costing_outbound_toll ,costing_men_power , ifnull(costing_outbound_fee, 0) + ifnull(costing_outbound_parking, 0) + ifnull(costing_outbound_toll, 0) + ifnull(costing_men_power, 0) as cost_sum from pengiriman where (outbound_by='$outbound_by' or outbound_request='outbound_by') and DATE_FORMAT(outbound_date,'%Y-%m-%d')='$outbound_date'   and id_cabang in (11,16,39) order by created_date asc";*/
							$no=1;

							$result = $mysqli->query("select b.tanggal as outbound_date,c.airwaybill_kode,x_namakustomer(id_kustomer) as kustomer,x_namakotabesar(lokasi_asal) as lokasi_asal, x_namakotabesar(lokasi_tujuan) as lokasi_tujuan,collie,berat_barang,case when komoditi='1' then 'P' else 'D' end as komoditi,costing_outbound_fee, costing_outbound_parking, costing_outbound_toll,costing_outbound_smu,costing_outbound_handling, costing_outbound_menpower,ifnull(costing_outbound_fee, 0) + ifnull(costing_outbound_parking, 0) + ifnull(costing_outbound_toll, 0) + ifnull(costing_outbound_smu, 0) + ifnull(costing_outbound_handling, 0) + ifnull(costing_outbound_menpower, 0) as cost_sum from

								manifest_detail a

								left join manifest b on a.id_manifest=b.id_manifest

								left join pengiriman c on a.id_pengiriman = c.id_pengiriman


							 where b.manifest_outbound_by='".$id_user."' and b.tanggal BETWEEN '".$dari."' AND '".$sampai."'");
							$rowcount=mysqli_num_rows($result);
							//echo 'count '.$rowcount;
							while ($row = $result->fetch_assoc()){
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
									<td>'.$row['outbound_date'].'</td>
									<td>'.$row['airwaybill_kode'].'</td>
									<td>'.$row['kustomer'].'</td>
									<td>'.strtoupper($lokasi_asal).'</td>
									<td>'.strtoupper($lokasi_tujuan).'</td>
									<td>'.$row['collie'].'</td>
									<td>'.$row['berat_barang'].'</td>
									<td>'.$row['komoditi'].'</td>
									<td>Rp. '.number_format(round($row['costing_outbound_fee']),0). '</td>

									<td>Rp. '.number_format(round($row['costing_outbound_parkir']),0). '</td>

									<td>Rp. '.number_format(round($row['costing_outbound_toll'])).'</td>
									<td>Rp. '.number_format(round($row['costing_outbound_smu'])).'</td>
									<td>Rp. '.round($row['costing_outbound_handling']).'</td>

									<td>Rp. '.number_format(round($row['costing_outbound_menpower']),0). '</td>
									<td> Rp. '.number_format(round($row['cost_sum']),0).'</td>
								</tr>';
								$no++;
							}
							/*$cek_all = mysqli_query($con, "select * from pengiriman where outbound_by='$outbound_by' and DATE_FORMAT(outbound_date,'%Y-%m-%d')='$outbound_date' and x_intercity(lokasi_asal)='Y' and id_cabang in (11,16,39) and costing_outbound_parking is not null and costing_men_power is not null order by created_date  asc");
							//echo "select * from pengiriman where outbound_by='$outbound_by' and DATE_FORMAT(outbound_date,'%Y-%m-%d')='$outbound_date' and x_intercity(lokasi_asal)='Y' and id_cabang in (11,16,39) and costing_outbound_parking is not null and costing_men_power is not null order by created_date  asc";
							$rowcount_input=mysqli_num_rows($cek_all);
							$rowcek=mysqli_fetch_assoc($cek_all);
							//echo 'count = '.$rowcount_input;*/
							echo'
							</tbody>
						</table>
					</div><!-- /.box-body -->
					<div class="box-footer">


					</div><!-- /.box-footer -->
					</form>
				</div><!-- /.box -->
				<!-- /. akhir view -->
				';
				}
				echo '

			</section>
			';
			break;

			case "report-vendor":

			$tanggal = "";
			$id_vendor = "";

			if ($_SERVER["REQUEST_METHOD"] == "POST") {
				$tanggal = $_POST['tanggal'];
				$id_vendor = $_POST['id_vendor'];
				$dr = strleft($tanggal, 10);
						$sm = strright($tanggal, 10);
						$dari = jin_date_sql($dr);
						$sampai = jin_date_sql($sm);
			}
			echo'
				<section class="content">
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">Report Costing Outbound</h3>

					</div><!-- /.box-header -->
					<form class="form-horizontal" method="post" action="" autocomplete="off">
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
											<input type="text" class="form-control" id="daterange" name="tanggal" value="'.$tanggal.'">
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-5">
								<div class="form-group">
									<label class="col-sm-4 control-label full-right">Vendor</label>
									<div class="col-sm-8">
										<select name="id_vendor" class="form-control select2">
										';
										$query=mysqli_query($con,'select id, name from manifest_vendor where active ="Y" order by name asc');
										while($r=mysqli_fetch_assoc($query)){
											if($r['id']==$id_vendor){
												echo'
												<option value="'.$r['id'].'" selected>'.$r['name'].'</option>';
											}else{
												echo'
												<option value="'.$r['id'].'">'.$r['name'].'</option>';
											}
										}
										echo '
										</select>
										</select>
									</div>
								</div>
							</div>
						</div><!-- /.row -->

					</div>
					<div class="box-footer">
						<button type="submit" class="btn btn-primary pull-right"> <i class="fa fa-search"></i> View</button>
						';
						if ($_SERVER["REQUEST_METHOD"] == "POST") {
							echo '
							<a  readonly target="_blank" href="  reports/PHPExcel/excel_report_costing_outbound_pervendor.php?id_vendor='.$id_vendor.'&dari='.$dari.'&sampai='.$sampai.' "><button type="button" class="btn btn-primary pull-right" ><i class="fa fa-file-excel-o"></i> Excel</button></a>
							';
						}
							echo '
					</div>
					</form>
				</div>
				';

				if ($_SERVER["REQUEST_METHOD"] == "POST") {
					//$tanggal = $_POST['tanggal'];
					//$id_user = $_POST['id_user'];

					//$id_kustomer = mysqli_real_escape_string($con, $_POST['id_kustomer']);
						$tanggal = mysqli_real_escape_string($con, $_POST['tanggal']);
						$dr = strleft($tanggal, 10);
						$sm = strright($tanggal, 10);
						$dari = jin_date_sql($dr);
						$sampai = jin_date_sql($sm);


				echo '
				<!-- /. mulai view-->
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">Report Manifest Outbound</h3>
						<div class="box-tools pull-right">
							<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
							<button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
						</div>
					</div><!-- /.box-header -->
					<div class="box-body">


						<table class="table table-striped table-bordered table-hover" id="report" style="white-space:nowrap;">
						<thead>
							<tr>
								<th>Outbound date</th>
								<th>Airwaybill</th>
								<th>Customer</th>
								<th>NO SMU</th>
								<th>Destination</th>
								<th>Collie</th>
								<th>Weight</th>
								<th>Dimensi</th>

								<th>SMU Cost</th>

							</tr>
						</thead>
						<tbody>';
						/*echo "select id_pengiriman, airwaybill_kode, x_namakustomer(id_kustomer) as kustomer, x_namakotabesar(lokasi_asal) as lokasi_asal, x_namakotabesar(lokasi_tujuan) as lokasi_tujuan, collie, if(berat_aktual > 0, berat_aktual, berat_barang) as berat_barang , case when komoditi='1' then 'Paket' else 'Dokumen' end as komoditi, costing_outbound_fee, costing_outbound_parking, costing_outbound_toll ,costing_men_power , ifnull(costing_outbound_fee, 0) + ifnull(costing_outbound_parking, 0) + ifnull(costing_outbound_toll, 0) + ifnull(costing_men_power, 0) as cost_sum from pengiriman where (outbound_by='$outbound_by' or outbound_request='outbound_by') and DATE_FORMAT(outbound_date,'%Y-%m-%d')='$outbound_date'   and id_cabang in (11,16,39) order by created_date asc";*/
							$no=1;

							$sql_vendor = $mysqli->query("select name from manifest_vendor where id='".$id_vendor."'");

							$rv = $sql_vendor->fetch_assoc();



							$result = $mysqli->query("select b.tanggal as outbound_date,c.airwaybill_kode,x_namakustomer(id_kustomer) as kustomer,x_namakotabesar(lokasi_asal) as lokasi_asal, x_namakotabesar(lokasi_tujuan) as lokasi_tujuan,collie,berat_barang, costing_outbound_smu , c.ukuran_barang, b.no_smu,c.kota_tujuan

								from

								manifest_detail a

								left join manifest b on a.id_manifest=b.id_manifest

								left join pengiriman c on a.id_pengiriman = c.id_pengiriman


							 where b.manifest_via='".$rv['name']."' and b.manifest_to <> 39 and b.tanggal BETWEEN '".$dari."' AND '".$sampai."' group by c.id_pengiriman");
							$rowcount=mysqli_num_rows($result);
							//echo 'count '.$rowcount;
							while ($row = $result->fetch_assoc()){
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
									<td>'.$row['outbound_date'].'</td>
									<td>'.$row['airwaybill_kode'].'</td>
									<td>'.$row['kustomer'].'</td>
									<td>'.$row['no_smu'].'</td>

									<td>'.$row['kota_tujuan'].'</td>
									<td>'.$row['collie'].'</td>
									<td>'.$row['berat_barang'].'</td>
									<td>'.$row['ukuran_barang'].'</td>




									<td>Rp. '.number_format(round($row['costing_outbound_smu'])).'</td>

								</tr>';
								$no++;
							}
							/*$cek_all = mysqli_query($con, "select * from pengiriman where outbound_by='$outbound_by' and DATE_FORMAT(outbound_date,'%Y-%m-%d')='$outbound_date' and x_intercity(lokasi_asal)='Y' and id_cabang in (11,16,39) and costing_outbound_parking is not null and costing_men_power is not null order by created_date  asc");
							//echo "select * from pengiriman where outbound_by='$outbound_by' and DATE_FORMAT(outbound_date,'%Y-%m-%d')='$outbound_date' and x_intercity(lokasi_asal)='Y' and id_cabang in (11,16,39) and costing_outbound_parking is not null and costing_men_power is not null order by created_date  asc";
							$rowcount_input=mysqli_num_rows($cek_all);
							$rowcek=mysqli_fetch_assoc($cek_all);
							//echo 'count = '.$rowcount_input;*/
							echo'
							</tbody>
						</table>
					</div><!-- /.box-body -->
					<div class="box-footer">


					</div><!-- /.box-footer -->
					</form>
				</div><!-- /.box -->
				<!-- /. akhir view -->
				';
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
