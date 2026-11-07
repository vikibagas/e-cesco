<section class="content-header">
	<h1>Costing Delivery<small>Airwaybill Costing</small></h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-home"></i> Home</a></li>
		<li><a href="#">Reports</a></li>
		<li class="active">Delivery Costing</li>
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
	if($u['level']=='admin' || $u['level']=='kurir' || $u['level']=='driver' || $u['level']=='operator' || $u['level']=='intercity' || $u['level']=='domex' || $u['id_user']== 5 || $u['id_user']== 77 || $u['level']=='finance' || ($u['level']=='agent' && $u['cabang_cesco'] == 'Y')){

		$aksi	= "module/mod_costing-delivery/costing-delivery-intercity_action.php";

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
											<button class="btn btn-sm btn-default" onclick="window.location.href = \'?module=costing-delivery-intercity&act=create\';"><i class="fa fa-plus text-green"></i> Create</button>
										</div>
									</div>
									<div class="col-md-6">
										<div class="btn-group pull-right">
											<button class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown">Tools <i class="fa fa-angle-down"></i></button>
											<ul class="dropdown-menu pull-right">

												<li><a href="main.php?module=costing-delivery-intercity&act=search" class=""><i class="fa fa-search"></i>Search By Airwaybill</a></li>
												S
												<li><a href="main.php?module=costing-delivery-intercity&act=cari-merah" target="_blank" class=""><i class="fa fa-search"></i>Cari Merah</a></li>
												<li><a href="main.php?module=costing-delivery-intercity&act=report-per-customer" class=""><i class="fa fa-file-excel-o"></i>Report per customer</a></li>
												';
												if ($u['level'] == 'admin' || $u['id_user'] == 4 || $u['id_user'] == 8 || $u['id_user'] == 77){
													echo '
													<li><a href="main.php?module=costing-delivery-intercity&act=report" class=""><i class="fa fa-file-excel-o"></i>Report</a></li>';
												}
												echo '

											</ul>
										</div>
									</div>
								</div>
							</div>
							<div class="box-body">
								 
								<table class="table table-striped table-bordered table-hover" id="costing-delivery-intercity" style="white-space:nowrap;">
									<thead>
										<tr>
											<th>Delivery Date</th>
											<th>Delivery By</th>
											<th>Qty</th>
											<th>Weight</th>
											<th>Collie</th>
											<th>Delivery Cost</th>
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
						<h3 class="box-title">Manifest delivery</h3>
						<div class="box-tools pull-right">
							<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
							<button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
						</div>
					</div>
					<form class="form-horizontal form-bordered" method="post" action="main.php?module=costing-delivery-intercity&act=create" autocomplete="off">
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
										<label class="col-md-2 control-label">delivery By</label>
										<div class="col-md-10">
											<select name="id_user" id="kurir-delivery-costing" data-placeholder="Choose..." class="form-control select2">
												<option value="0">all</option>';
												$kurir = $mysqli->query("SELECT * FROM user WHERE level IN ('kurir','driver') AND blokir='N' ORDER BY namalengkap");
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
							<button type="button" class="btn btn-default" onclick="window.location.href=\'?module=costing-delivery-intercity\'">Cancel</button>
							<button type="submit" class="btn btn-primary pull-right"><i class="fa fa-send"></i> Submit</button>
						</div>
					</form>
					<div id="table-delivery-kurir"></div>
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

						//$query = $mysqli->query("select sum(coalesce(a.costing_delivery_fee,0)) + sum(coalesce(a.costing_delivery_parking,0))+ sum(coalesce(a.biaya_packing,0)) + sum(coalesce(a.costing_delivery_toll,0)) + sum(coalesce(a.costing_delivery_menpower,0)) as total_cost from pengiriman a left join user b on (b.id_user=a.courier_by)  where courier_by='$id_user' and DATE_FORMAT(courier_date,'%Y-%m-%d') BETWEEN '$dari' AND '$sampai' and x_intercity(lokasi_tujuan)='Y'  order by created_date asc");

						//	$r = $query->fetch_assoc();
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
										<th>delivery date</th>
										<th>Customer</th>
										<th>Origin</th>
										<th>Destination</th>
										<th>Collie</th>
										<th>Weight</th>
										<th>Comodity</th>
										<th>Delivery Cost</th>
										<th>Parking Fee</th>
										<th>Toll Fee</th>
										<th>Men Power</th>
										<th>Total cost</th>
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

										$result_list = $mysqli->query("select id_pengiriman, airwaybill_kode, DATE_FORMAT(courier_date,'%Y-%m-%d') as courier_date , x_namakustomer(id_kustomer) as kustomer, x_namakotabesar(lokasi_asal) as lokasi_asal, x_namakotabesar(lokasi_tujuan) as lokasi_tujuan, collie, if(berat_aktual > 0, berat_aktual, berat_barang) as berat_barang , case when komoditi='1' then 'Paket' else 'Dokumen' end as komoditi, costing_delivery_fee, costing_delivery_parking, costing_delivery_toll ,costing_men_power , ifnull(costing_delivery_fee, 0) + ifnull(costing_delivery_parking, 0) + ifnull(costing_delivery_toll, 0)  + ifnull(costing_men_power, 0) as cost_sum from pengiriman where courier_by='$id_user' and DATE_FORMAT(courier_date,'%Y-%m-%d') and (status=6 or status = 5) BETWEEN '$dari' AND '$sampai' and x_intercity(lokasi_tujuan)='Y' order by created_date asc");
										while ($rowlist = $result_list->fetch_assoc()){
											echo '
												<tr>
													<td>'.$rowlist['airwaybill_kode'].'</td>
													<td>'.$rowlist['courier_date'].'</td>
													<td>'.$rowlist['kustomer'].'</td>
													<td>'.strtoupper($rowlist['lokasi_asal']).'</td>
													<td>'.strtoupper($rowlist['lokasi_tujuan']).'</td>
													<td>'.$rowlist['collie'].'</td>
													<td>'.$rowlist['berat_barang'].'</td>
													<td>'.$rowlist['komoditi'].'</td>
													<td>'.round($rowlist['costing_delivery_fee']). '</td>
													<td>'.round($rowlist['costing_delivery_parking']). '</td>
													<td>'.round($rowlist['costing_delivery_toll']). '</td>
													<td>'.round($rowlist['costing_delivery_menpower']). '</td>
													<td>'.round($rowlist['cost_sum']). '</td>
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
								<a target="_blank" href="<?php echo "reports/PHPExcel/excel_createcosting_delivery.php?id_user=$id_user&datefrom=$dari&dateuntil=$sampai"?> "><button type="button" class="btn btn-primary pull-right" ><i class="fa fa-file-excel-o"></i> Excel</button></a>
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

			$pieces = explode('-', $id);
			$courier_by = $pieces['0'];
			$courier_date = implode('-', array_slice($pieces, 1, 3));

			$sql = "select
                    DATE_FORMAT(a.courier_date, '%Y-%m-%d') as tgldelivery,
                    a.costing_delivery_save,
                    c.namalengkap as nama_costing,
                    sum(coalesce(a.costing_delivery_fee, 0)) as costing_delivery_fee,
                    sum(coalesce(a.costing_delivery_toll, 0)) as costing_delivery_toll,
                    b.namalengkap,
                    sum(coalesce(a.costing_delivery_fee, 0)) + sum(coalesce(a.costing_delivery_parking, 0)) + sum(coalesce(a.costing_delivery_toll, 0)) + sum(coalesce(a.costing_delivery_menpower, 0)) as total_cost
                from
                    pengiriman a
                        left join
                    user b ON b.id_user = a.courier_by
                        left join
                    user c ON (c.id_user = a.costing_delivery_by)
                where
                    a.courier_by = '$courier_by'
                        and (status = 6  or status=8 or a.status =5)
                        and DATE_FORMAT(a.courier_date, '%Y-%m-%d') = '$courier_date'";

			$query = mysqli_query($con, $sql);

			/*echo "select DATE_FORMAT(a.courier_date,'%Y-%m-%d') as tgldelivery,a.costing_delivery_save, c.namalengkap as nama_costing, sum(coalesce(a.costing_delivery_fee,0)) as costing_delivery_fee, sum(coalesce(a.costing_delivery_toll,0)) as costing_delivery_toll, b.namalengkap, sum(coalesce(a.costing_delivery_fee,0)) + sum(coalesce(a.costing_delivery_parking,0)) + sum(coalesce(a.costing_delivery_toll,0)) + sum(coalesce(a.costing_delivery_menpower,0)) as total_cost from pengiriman a left join user b on b.id_user=a.courier_by left join user c on (c.id_user=a.costing_delivery_by) where a.courier_by='$courier_by' and DATE_FORMAT(a.courier_date,'%Y-%m-%d')='$courier_date' and x_intercity(a.lokasi_tujuan)='Y'";*/
			$r = mysqli_fetch_assoc($query);


			$sqD = "select coalesce(sum(diskon),0) as total_diskon from (select
                    coalesce(a.costing_delivery_fee, 0) + coalesce(a.costing_delivery_parking, 0) + coalesce(a.costing_delivery_toll, 0) + coalesce(a.costing_delivery_menpower, 0) as total_cost,
                    (select total_cost*(diskon_deliv/100)) as diskon
                from
                    pengiriman a
                        left join
                    user b ON b.id_user = a.courier_by
                        left join
                    user c ON (c.id_user = a.costing_delivery_by)
                where
                    a.courier_by = '$courier_by'
                        and (status = 6 or status = 8)
                        and DATE_FORMAT(a.courier_date, '%Y-%m-%d') = '$courier_date') x";

            $qD = mysqli_query($con, $sqD);
			$rD = mysqli_fetch_assoc($qD);

			$totalDiskon = $rD['total_diskon'];

			$query_kode_manifest = mysqli_query($con, "select kode_manifest from manifest
				where tanggal='$courier_date' and manifest_delivery_by = '$courier_by'");

			$r_kode = mysqli_fetch_assoc($query_kode_manifest);

			$tanggal = tgl_indo($courier_date);

			echo'
			<section class="content">
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">Costing Delivery</h3>
						<div class="box-tools pull-right">
							<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
							<button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
						</div>
					</div><!-- /.box-header -->
					<form action="'.$aksi.'?module=costing-delivery-intercity&act=plus" id="plustanggal" name="plustanggal" method="POST"></form>
					<form class="form-horizontal form-bordered"  method="post" action="'.$aksi.'?module=costing-delivery-intercity&act=calculate">
					<input type="hidden" name="tgldelivery" class="form-control" value="'.$courier_date.'">
					<input type="hidden" name="costing_delivery_by" class="form-control" value="'.$courier_by.'">
					<div class="box-body">
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label class="col-md-4 control-label">Manifest Code</label>
									<div class="col-md-8">
											<input type="text" id="kode_manifest" class="form-control" value="'.$r_kode['kode_manifest'].'"readonly>
									</div>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label class="col-md-4 control-label">delivery Costtt</label>
									<div class="col-md-8">
										<div class="input-group">
											<div class="input-group-addon">
												IDR
											</div>
											<input type="text" name="delivery_cost" onkeyup="editDeliveryCost(this.value)"  class="form-control" value="'.number_format(round($r['costing_delivery_fee'])).'" '.($u['level'] != 'admin' or $u['id_user'] != '77' ? ($r['costing_delivery_save'] != null ? 'readonly' : '') : 'enable admin' ).'>
											<input type="hidden" name="delivery_cost_real" id="delivery_cost_real" value="'.round($r['costing_delivery_fee']).'">
											<script language="javascript">
                                            function editDeliveryCost(val){
                                                document.getElementById("delivery_cost_real").value = val.replace(/\,/g,\'\');
                                            }
											</script>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label class="col-md-4 control-label">Total Discount</label>
									<div class="col-md-8">
										<div class="input-group">
											<div class="input-group-addon">
												IDR
											</div>
											<input type="text" name="total_diskon" class="form-control" value="'.number_format(round($totalDiskon)).'" readonly>
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
									<label class="col-md-4 control-label">Toll Fee</label>
									<div class="col-md-8">
										<div class="input-group">
											<div class="input-group-addon">
												IDR
											</div>
											<input type="text" name="toll_fee" class="form-control" onkeyup="editTollFee(this.value)" value="'.number_format(round($r['costing_delivery_toll'])).'" '.($u['level'] != 'admin' or $u['id_user'] != '77' ? ($r['costing_delivery_save'] != null ? 'readonly' : '') : 'enable admin' ).'>
											<input type="hidden" name="toll_fee_real" id="toll_fee_real" value="'.round($r['costing_delivery_toll']).'">
											<script language="javascript">
                                            function editTollFee(val){
                                                document.getElementById("toll_fee_real").value = val.replace(/\,/g,\'\');
                                            }
											</script>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label class="col-md-4 control-label">Total Cost</label>
									<div class="col-md-8">
										<div class="input-group">
											<div class="input-group-addon">
												IDR
											</div>
											<input type="text" name="total_cost" class="form-control" value=" '.number_format(round($r['total_cost']-$totalDiskon)).'" readonly>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label class="col-md-4 control-label">delivery By</label>
									<div class="col-md-8">
										<input type="text" class="form-control" value="'.$r['namalengkap'].'" readonly>
										<input type="hidden" name="courier_by" value="'.$courier_by.'">
									</div>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label class="col-md-4 control-label">Costing by</label>
									<div class="col-md-8">
										<input type="text" readonly name="costing_by" class="form-control" value="'.$r['nama_costing'].'">
									</div>
								</div>
							</div>
						</div>
						
						<table class="table table-striped table-bordered table-hover" id="costing-delivery-detail" style="white-space:nowrap;">
						<thead>
							<tr>
								<th style="width: 10px">#</th>
								<th>Airwaybill</th>
								<th>service</th>
								<th>Customer</th>
								<th>Origin</th>
								<th>Destination</th>
								<th>Delivered Date</th>
								<th>Lead Time</th>
								<th>Reason Late</th>
								<th>Collie</th>
								<th>Weight</th>
								<th>volume</th>
								<th>Comodity</th>
								<th>Delivery Cost</th>
								<th>Parking Fee</th>
								<th>Toll Fee</th>
								<th>Men Power</th>
								<th>Total cost</th>
								<th>Discount (%)</th>
								<th>Total after Discount</th>
							</tr>
						</thead>
						<tbody>';
							$no=1;

							$sql = "select
                                    id_pengiriman,
                                    x_service(a.service) as service,
                                    airwaybill_kode,
                                    x_namakustomer(id_kustomer) as kustomer,
                                    ifnull(x_namakotabesar(lokasi_asal), a.kota_asal) as lokasi_asal,
                                    ifnull(x_namakotabesar(lokasi_tujuan), a.kota_tujuan) as lokasi_tujuan,
                                    delivery_date,
                                    if(delivery_date is null,'-', datediff(delivery_date, date(pickup_date))) as selisih_hari,
                                    alasan_terlambat_delivery,
                                    collie,
                                    if(berat_aktual > 0,
                                        berat_aktual,
                                        berat_barang) as berat_barang,
                                    case
                                        when komoditi = '1' then 'Paket'
                                        else 'Dokumen'
                                    end as komoditi,
                                    costing_delivery_fee,
                                    costing_delivery_parking,
                                    costing_delivery_toll,
                                    costing_delivery_menpower,
                                    delivery_name,
                                    courier_date,
                                  	a.status as status_kiriman,

                                    
                                    CASE
                                        WHEN airwaybill_kode LIKE '001-%' THEN 0
                                        WHEN airwaybill_kode LIKE '002-%' THEN 1
                                    end as intercity,
                                    SUBSTRING_INDEX(ukuran_barang, 'x', 1) as length,
                                    SUBSTRING_INDEX(SUBSTRING_INDEX(ukuran_barang, 'x', 2),
                                            'x',
                                            - 1) as width,
                                    SUBSTRING_INDEX(ukuran_barang, 'x', - 1) as height,
                                    if((select intercity) = 1,
                                        ceiling((select length) * (select width) * (select height) / 4000),
                                        if(a.service = 5,
                                            ceiling((select length) * (select width) * (select height) / 4000),
                                            ceiling((select length) * (select width) * (select height) / 6000))) as volume,

                                    ifnull(costing_delivery_fee, 0) + ifnull(costing_delivery_parking, 0) + ifnull(costing_delivery_toll, 0) + ifnull(costing_delivery_menpower, 0)  as cost_sum,
                                    diskon_deliv,
                                    ifnull( DATE_FORMAT( DATE_ADD(a.created_date, INTERVAL case when a.service=1 then
                                        CASE
                                        WHEN airwaybill_kode LIKE '001-%' THEN 1
                                        WHEN airwaybill_kode LIKE '002-%' THEN 0
                                        END
                                    when a.service=2 then 1 else s.sla_max end DAY), '%Y-%m-%d' ) ,'0' ) as sla_max2,
                                    CONCAT(DATE_FORMAT(a.created_date,'%Y-%m-%d'),' ',
                                        ifnull( DATE_FORMAT( DATE_ADD(a.created_date, INTERVAL case when a.service=1 then CASE
                                        WHEN airwaybill_kode LIKE '001-%' THEN 1
                                        WHEN airwaybill_kode LIKE '002-%' THEN 0
                                        END  when a.service=2 then 1 else s.sla_max end DAY), '%Y-%m-%d' ) ,'0' )
                                        ) as sla_date,
                                    substring(airwaybill_kode,1,3) as awb_code,
                                    lokasi_asal as id_lokasi_asal,
                                    lokasi_tujuan as id_lokasi_tujuan,
                                    terlambat_delivery,
                                    biaya_packing,
                                    datediff(a.delivery_date, (select sla_max2)) as selisih_hari2
                                from
                                    pengiriman a
                                    left join sla s on s.kota_asal =  a.id_kota_asal   AND s.kota_tujuan=  a.id_kota_tujuan  AND s.service = a.service
                                where
                                    courier_by = '$courier_by'
                                        and (a.status = 6 or a.status =8 or a.status =5) 
                                        and DATE_FORMAT(a.courier_date, '%Y-%m-%d') = '$courier_date'
                                order by created_date asc";


                            $listAreaBdg = array(3204,3217,3273);
                            $listAreaSby= array(3578);

							$result = mysqli_query($con,$sql);
							$rowcount=mysqli_num_rows($result);
                            $sum_total = 0;
							while ($row = mysqli_fetch_assoc($result)){
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

                                $telat = "";
                                if (($row['awb_code'] == '001' || $row['awb_code'] == '002') && (in_array( $row['id_lokasi_asal'] ,$listAreaBdg) || in_array( $row['id_lokasi_tujuan'] ,$listAreaBdg) || in_array( $row['id_lokasi_tujuan'] ,$listAreaSby) || in_array( $row['id_lokasi_tujuan'] ,$listAreaSby))) {
                                    ///SLA
                                    $d = $row['sla_date'];
                                    $pieces = explode(' ', $d);

                                    $booking_date = $pieces[0];

                                    $d1 = strtotime($pieces[1]);
                                    $d2 = date('l', $d1);
                                    $dt3 = strtolower($d2);

                                    if ($dt3 == "saturday") {
                                        $sla = date('Y-m-d', strtotime($pieces[1] . '+2 day'));
                                    } elseif ($dt3 == "sunday") {
                                        $sla = date('Y-m-d', strtotime($pieces[1] . '+1 day'));
                                    } else {
                                        $sla = date('Y-m-d', strtotime($pieces[1]));
                                    }

                                    $date_now = date("Y-m-d");

                                    if ($sla != '1970-01-01') {

                                        if ($date_now > $sla && $row['selisih_hari2'] > 0) {

                                            $telat = '<button type="button" class="btn btn-xs btn-danger" title="TERLAMBAT"><i class="fa fa-exclamation-circle"></i> </button>';

                                            $created_date = $booking_date . ' - ' . $sla . '<button class="btn btn-xs btn-danger" title="TERLAMBAT"><i class="fa fa-exclamation-circle"></i> </button>';
                                        } else {
                                            $created_date = $booking_date . ' - ' . $sla;
                                        }
                                    } else {
                                        $created_date = $booking_date;
                                    }
                                    ////
                                }
                                $date_now = date("Y-m-d");
                                $total = $row['cost_sum'];
                                $diskon = ($row['diskon_deliv'] > 0 ? $row['diskon_deliv'] : ($row['courier_date'] < $date_now && $row['status_kiriman'] < 6 ? 20 : 0) );
                                $totalAfterDiskon = $total-(($diskon/100)*$total);
                                $sum_total = $sum_total + $totalAfterDiskon;


                                //$diskonCell = ($telat == "") ? $diskon : $diskon.' <a href="#" class="diskon-delivery" data-type="text" data-pk="'.$row['id_pengiriman'].'~'.$total.'" title="edit">  <i class="fa fa-edit"> </i> </a>';
                                $diskonCell = ($telat != "" || $row['terlambat_delivery'] == 3 || $row['terlambat_delivery'] == 4 || $_SESSION['level'] == 'admin') ? $diskon.' <a href="#" class="diskon-delivery" data-type="text" data-pk="'.$row['id_pengiriman'].'~'.$total.'" title="edit">  <i class="fa fa-edit"> </i> </a>' : $diskon;
                                $date_now = date("Y-m-d");
                                echo'
								<tr>
									<td><center>'.$no.'</center></td>
									<td>'.$row['airwaybill_kode']. ($row['diskon_deliv'] > 0 ? '<i class="fa fa-warning"> </i>' : '' ).'


									';
									if($row['courier_date'] < $date_now && $row['status_kiriman'] < 6){ 
										echo '<i class="fa fa-warning"> </i>'; 
									} 

									echo '<input type="checkbox" form="plustanggal" name="selectawb[]" value="'.$row['id_pengiriman'].'">';

									echo '

									</td>

									<td>'.$row['service'].'</td>
									<td>'.$row['kustomer'].'</td>
									<td>'.strtoupper($lokasi_asal).'</td>
									<td>'.strtoupper($lokasi_tujuan).'</td>

									<td>'.$row['delivery_date'].'</td>
								    <td> '.$row['selisih_hari'].' hari '.$telat.'</td>
								    <td> '.$row['alasan_terlambat_delivery'].'</td>

									<td>'.$row['collie'].'</td>
									<td>'.$row['berat_barang'].'</td>
									<td>'.$row['volume'].'</td>
									<td>'.$row['komoditi'].'</td>
									<td> Rp. <span id="spBiayadelivery'.$row['id_pengiriman'].'">'.number_format(round($row['costing_delivery_fee']),0).'</span>
										<a href="#" class="biaya-delivery" data-type="text" data-pk="'.$row['id_pengiriman'].'" title="edit">  <i class="fa fa-edit"> </i> </a>
									</td>
									';
									/*<td><span id="spPacking'.$row['id_pengiriman'].'">'.round($row['biaya_packing']).'</span> 

									<a href="#" class="biaya-packing-delivery" data-type="text" data-pk="'.$row['id_pengiriman'].'" title="edit">  <i class="fa fa-edit"> </i> </a>

									</td>*/
									echo '
									<td>'
										.($row['costing_delivery_fee'] != NULL ? 
											($row['costing_delivery_parking'] == NULL ?
												'<button type="button" id="'.$row['id_pengiriman'].'" class="btn btn-xs btn-warning open_parkingcost" ><i class="fa fa-input"></i> input</button>' 
												: $row['costing_delivery_parking']) 
											: '' )
										.' '
										.($u['level'] == 'admin' || $u['level'] == 'finance' ? 
											($row['costing_delivery_parking'] != null ?
											 	'<a href="#" class="edit_parking_cost" id="'.$row['id_pengiriman'].'" data-id="'.$row['costing_delivery_parking'].'" title="edit">  <i class="fa fa-edit"> </i> </a>' 
											 	: '' ) 
											: ($row['costing_delivery_parking'] != null ?
												 	($r['costing_delivery_save'] == null ?
												 	 '<a href="#" class="edit_parking_cost" id="'.$row['id_pengiriman'].'" data-id="'.$row['costing_delivery_parking'].'" title="edit">  <i class="fa fa-edit"> </i> </a>' 
												 	 : '') 
													: '')).
									'</td>
									

									<td>'.round($row['costing_delivery_toll']).'</td>
									<td>'.($row['costing_delivery_fee'] != NULL ? ($row['costing_delivery_menpower'] == NULL ? '<button type="button" id="'.$row['id_pengiriman'].'" class="btn btn-xs btn-warning open_mencost" ><i class="fa fa-input"></i> input</button>' : $row['costing_delivery_menpower']) : '') .' '.($u['level'] == 'admin' || $u['level'] == 'finance' ? ($row['costing_delivery_menpower'] != null ? '<a href="#" class="edit_men_cost" id="'.$row['id_pengiriman'].'" data-id="'.$row['costing_delivery_menpower'].'" title="edit">  <i class="fa fa-edit"> </i> </a>' : '' ) : ($row['costing_delivery_menpower'] != null ? ($r['costing_delivery_save'] == null ? '<a href="#" class="edit_men_cost" id="'.$row['id_pengiriman'].'" data-id="'.$row['costing_delivery_menpower'].'" title="edit">  <i class="fa fa-edit"> </i> </a>' : '') : '')).'</td>
									<td> Rp. '.number_format(round($row['cost_sum']),0).'</td>
									
									<td>'.$diskonCell.'</td>
									
									<td align="right" align="right" id="totAfterDisc'.$row['id_pengiriman'].'"> Rp. '.number_format(round($totalAfterDiskon),0).'</td>
								</tr>';
								$no++;
							}

							$cek_all = mysqli_query($con, "select * from pengiriman where courier_by='$courier_by' and DATE_FORMAT(courier_date,'%Y-%m-%d')='$courier_date' and x_intercity(lokasi_tujuan)='Y' and  costing_delivery_parking is not null and costing_delivery_menpower is not null order by created_date  asc");
							//echo "select * from pengiriman where courier_by='$courier_by' and DATE_FORMAT(courier_date,'%Y-%m-%d')='$courier_date' and x_intercity(lokasi_tujuan)='Y' and  costing_delivery_parking is not null and costing_delivery_menpower is not null order by created_date  asc";

							$rowcount_input=mysqli_num_rows($cek_all);
							//echo "<br>".$rowcount_input;
							$rowcek=mysqli_fetch_assoc($cek_all);

							echo'
							</tbody>
						</table>
					</div><!-- /.box-body -->

					<div class="box-footer">
						
						<button type="button" class="btn btn-default" onclick="window.location.href = \'?module=costing-delivery-intercity\';">back</button>
						<input type="submit" class="btn btn-success" id="calculate-costing-delivery" value="calculate" '.$r['costing_delivery_fee'].'" '.($u['level'] != 'admin' or $u['id_user'] != '77' ? ($r['costing_delivery_save'] != null ? 'disabled' : '') : 'enable admin' ).'>
						<button type="button" class="btn btn-success" id="save" onclick="window.location.href = \'module/mod_costing-delivery/costing-delivery-intercity_action.php?module=costing-delivery-intercity&act=save&deliveryby='.$courier_by.'&date='.$r['tgldelivery'].'\';" '.($rowcount != $rowcount_input ? 'disabled' : '') .' '.($u['level'] != 'admin' or $u['id_user'] != '77' ? ($r['costing_delivery_save'] != null ? 'disabled' : '') : 'enable admin' ).'> save </button>
						<button type="button" class="btn btn-primary pull-right" onClick="window.open(\'reports/costing-delivery.php?id='.$encrypt.'\',\'mywindow\',\'menubar=1,resizable=1,width=800,height=600\');" ><i class="fa fa-print"></i> Print</button>

						<a target="_blank" href="reports/PHPExcel/excel_costing_delivery.php?id='.$encrypt.'"><button type="button" class="btn btn-primary pull-right" '.($r['costing_delivery_save'] != null ? '' : 'disabled').'"><i class="fa fa-file-excel-o"></i> Excel</button></a>

						<a target="_blank" href="reports/reportpdf/costing_delivery_pdf.php?id='.$encrypt.'"><button type="button" class="btn btn-primary pull-right" '.($r['costing_delivery_save'] != null ? '' : 'disabled').' ><i class="fa fa-file-pdf-o"></i> PDF</button></a>
					</div><!-- /.box-footer -->
					</form>
					<div class="box-footer">
						<input type="text" id="tanggal" name="tanggalplus" form="plustanggal">
								<button type="submit"  form="plustanggal" class="btn btn-success"  Value="Plus Tanggal"> Ubah Tanggal </button>
					</div>
				</div><!-- /.box -->
			</section>
			<div id="modal-inputparkingcost" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
							<h4 class="modal-title" id="myModalLabel">Input Parking fee</h4>
						</div>
						<div class="modal-content">
							<div class="modal-body">
								<form action="'.$aksi.'?module=costing-delivery-intercity&act=costingparking" name="form_costing" method="POST">
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
								<form action="'.$aksi.'?module=costing-delivery-intercity&act=costing_men_power" name="form_costing" method="POST">
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
			<div id="modal-editparkingcost" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
							<h4 class="modal-title" id="myModalLabel">Edit Parking fee</h4>
						</div>
						<div class="modal-content">
							<div class="modal-body">
								<form action="'.$aksi.'?module=costing-delivery-intercity&act=costingparking_edit" name="edit_form_costing" method="POST">
									<input type="hidden" name="id" value="" id="idpengiriman">
									<input type="number" class="form-control" id="parking_fee_edit" name="parking_fee"  min="1000">
							</div>
							<div class="modal-footer">
									<button class="btn btn-success" type="submit">update</button>
									<button type="reset" class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Cancel</button>
									</div>
								</form>
						</div>
					</div>
				</div>
			</div>

			<div id="modal-editmencost" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
							<h4 class="modal-title" id="myModalLabel">Edit Men Power fee</h4>
						</div>
						<div class="modal-content">
							<div class="modal-body">
								<form action="'.$aksi.'?module=costing-delivery-intercity&act=costing_men_power_edit" name="edit_form_costing" method="POST">
									<input type="hidden" name="id" value="" id="idpengiriman">
									<input type="number" class="form-control" id="men_power_edit" name="men_power"  min="1000">
							</div>
							<div class="modal-footer">
									<button class="btn btn-success" type="submit">update</button>
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
									<button type="button" name="costing-delivery-intercity"  data-id="costing-delivery" id="btnsearchcosting" class="btn form-control btn-primary">search</button>
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
									
									<button type="button" name="costing-delivery-intercity" data-id="costing-delivery" id="btnsearchmerah" class="btn form-control btn-primary">search</button>
							</div>
						</div>
						<div id="load" style="display:none;"><img src="dist/img/Loading_icon.gif"/ style="display:block; margin: 0 auto; "></div>

						<table id="airwaybillsrc-merah" class="table table-bordered table-striped table-hover" width="100%" cellspacing="0" style="white-space:nowrap; margin-top:50px;">
									<thead>
										<tr style="text-transform: uppercase;">
											<th>Tanggal</th>
											<th>Delivery By</th>
											<th>AWB</th>
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
				//$id_user = $_POST['id_user'];
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
											<input type="text" class="form-control" id="daterange" name="tanggal" value="'.($_SERVER["REQUEST_METHOD"] == "POST" ? $tanggal : '').'">
										</div>
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
							<a  readonly target="_blank" href="reports/PHPExcel/excel_report_costing_outbound_peruser.phpdari='.$dari.'&sampai='.$sampai.' "><button type="button" class="btn btn-primary pull-right" disabled><i class="fa fa-file-excel-o"></i> Excel</button></a>
							';
						}
							echo '
					</div>
					</form>
				</div>
				';

				if ($_SERVER["REQUEST_METHOD"] == "POST") {
					//$tanggal = $_POST['tanggal'];
//					$id_user = $_POST['id_user'];

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
						<h3 class="box-title">Report Manifest Delivery</h3>
						<div class="box-tools pull-right">
							<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
							<button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
						</div>
					</div><!-- /.box-header -->
					<div class="box-body">


						<table class="table table-striped table-bordered table-hover" id="report" style="white-space:nowrap;">
						<thead>
							<tr>
								<th>Delivery date</th>
								<th>Airwaybill</th>
								
								<th>Customer</th>
								<th>Origin</th>
								<th>Destination</th>
								<th>Collie</th>
								<th>Weight</th>
								<th>Asuransi</th>
								<th>Packing Kayu</th>
								<th>Total cost</th>
							</tr>
						</thead>
						<tbody>';
						
							$no=1;

							$result = $mysqli->query("select delivery_date,airwaybill_kode,x_namakustomer(id_kustomer) as kustomer,kota_asal,kota_tujuan,collie,berat_barang,case when komoditi='1' then 'P' else 'D' end as komoditi,costing_delivery_fee, costing_delivery_parking, costing_delivery_toll, costing_delivery_menpower,ifnull(costing_delivery_fee, 0) + ifnull(costing_delivery_parking, 0) + ifnull(costing_delivery_toll, 0) + ifnull(costing_delivery_menpower, 0) as cost_sum ,
								tanggal,
								asuransi,
								packing_kayu
								from pengiriman where status = 6 and  delivery_date BETWEEN '".$dari."' AND '".$sampai."' and x_inter(id_kota_asal) = 'Y' and x_inter(id_kota_tujuan) = 'Y' order by delivery_date asc ");
							$rowcount=mysqli_num_rows($result);
							//echo 'count '.$rowcount;
							while ($row = $result->fetch_assoc()){
								
								echo'
								<tr>
									<td>'.($row['delivery_date'] != '0000-00-00' ? $row['delivery_date'] : '').'</td>
									<td>'.$row['airwaybill_kode'].'</td>
									
									<td>'.$row['kustomer'].'</td>
									<td>'.strtoupper($row['kota_asal']).'</td>
									<td>'.strtoupper($row['kota_tujuan']).'</td>
									<td>'.$row['collie'].'</td>
									<td>'.$row['berat_barang'].'</td>
									<td>'.$row['asuransi'].'</td>
									<td>'.($row['packing_kayu'] == 2 ? 'Y' : 'N').'</td>
									
									
									<td> Rp. '.number_format(round($row['cost_sum']),0).'</td>
								</tr>';
								$no++;
							}
							
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

			case "report-per-customer":
			?>

				<form class="form-horizontal" method="GET" target="_blank" action="reports/PHPExcel/excel_costing_delivery_per_customer.php" autocomplete="off">
					<div class="box box-primary">
						<div class="box-header with-border">
							<h3 class="box-title">Profit Loss per Customer</h3>
							<div class="box-tools pull-right">
								<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
								<button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
							</div>
						</div><!-- /.box-header -->
						<div class="box-body">
							<div class="row">
								<div class="col-md-4">
									<div class="form-group">
										<label class="col-sm-5 control-label full-right">Tahun</label>
										<div class="col-sm-7">
											<div class="input-group">
												<div class="input-group-addon">
													<i class="fa fa-calendar"></i>
												</div>
												<select name="bulan" class="form-control select2">
													<?php
													    $month = !empty( $_GET['month'] ) ? $_GET['month'] : 0;
									                    for ($i = 0; $i <= 12; ++$i) {
									                        $time = strtotime(sprintf('+%d months', $i));
									                        $label = date('F ', $time);
									                        $value = date('m', $time);

									                        $selected = ( $value==$month ) ? ' selected=true' : '';

									                        printf('<option value="%s"%s>%s</option>', $value, $selected, $label );
									                    }
													?>
													
												</select> 
											</div>
										</div>
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<label class="col-sm-5 control-label full-right">Tahun</label>
										<div class="col-sm-7">
											<div class="input-group">
												<div class="input-group-addon">
													<i class="fa fa-calendar"></i>
												</div>
												<select name="tahun" class="form-control select2">
													
														<?php
														
															for($i = date("Y"); $i >=date("Y")-5; $i--){
															    echo '<option value="' . $i . '">' . $i . '</option>' ;
															}
															

														?>
													
												</select> 
											</div>
										</div>
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<label class="col-sm-3 control-label full-right">Customer</label>
										<div class="col-sm-8">
											<select name="id_kustomer" class="form-control select2">
											<?php
											if($u['id_kustomer']> 0){
												$query=mysqli_query($con,'select id_kustomer, nama_kustomer from kustomer  order by nama_kustomer');
												
											}else{
												$query=mysqli_query($con,'select id_kustomer, nama_kustomer from kustomer order by nama_kustomer');	
											}
											while($r=mysqli_fetch_assoc($query)){
												echo'
												<option value="'.$r['id_kustomer'].'">'.$r['nama_kustomer'].'</option>';
											}

											?>
											</select>
										</div>
									</div>
								</div>
							</div><!-- /.row -->
						</div><!-- /.box-body -->
				        <div class="box-footer">
							<button type="submit" class="btn btn-primary pull-right"> <i class="fa fa-search"></i> Excel</button>
						</div>
					</div><!-- /.box -->
					</form>

			<?php

			break;
		}
	}else{
		echo akses_salah();
	}
}
?>
