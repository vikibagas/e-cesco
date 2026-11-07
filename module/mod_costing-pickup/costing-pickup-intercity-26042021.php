<section class="content-header">
	<h1>Costing Pickup Intercity<small>airwaybill costing</small></h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-home"></i> Home</a></li>
		<li><a href="#">Finance</a></li>
		<li><a href="#">Airwaybill Costing</a></li> 
		<li class="active">Pickup Intercity</li>		
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
	if($u['level']=='admin' || $u['level']=='finance' || $u['level']=='domex' || $u['id_user']== 5 || $u['id_user']== 8 || $u['id_user']== 77 || ($u['level']=='agent' && $u['cabang_cesco'] == 'Y')){

		$aksi	= "module/mod_costing-pickup/costing-pickup-intercity_action.php";
		$aksi_post	= "module/mod_costing-pickup/costing-pickup-intercity.php";
		
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
											<button class="btn btn-sm btn-default" onclick="window.location.href = \'?module=costing-pickup-intercity&act=create\';"><i class="fa fa-plus text-green"></i> Create</button>
										</div>
									</div>
									<div class="col-md-6">
										<div class="btn-group pull-right">
											<button class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown">Tools <i class="fa fa-angle-down"></i></button>
											<ul class="dropdown-menu pull-right">
												
												<li><a href="main.php?module=costing-pickup-intercity&act=search" class=""><i class="fa fa-search"></i>Search By Airwaybill</a></li>
												';
												if ($u['level'] == 'admin' || $u['id_user'] == 4 || $u['id_user'] == 8 || $u['id_user'] == 77){
													echo '
													<li><a href="main.php?module=costing-pickup-intercity&act=report" class=""><i class="fa fa-file-excel-o"></i>Report</a></li>';
												}
												echo '
												
											</ul>
										</div>										
									</div>
								</div>
							</div>
							<div class="box-body">
								<table class="table table-striped table-bordered table-hover" id="costing-pickup-intercity" style="white-space:nowrap;">
									<thead>
										<tr>
											<th>Pickup Date</th>
											<th>Pickup By</th>
											<th>Qty</th>
											<th>Weight</th>
											<th>Collie</th>
											<th>Total Cost</th>										
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
						<h3 class="box-title">Manifest Pickup</h3>
						<div class="box-tools pull-right">
							<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
							<button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
						</div>
					</div>
					<form class="form-horizontal form-bordered" method="post" action="main.php?module=costing-pickup-intercity&act=create" autocomplete="off">
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
										<label class="col-md-2 control-label">Pickup By</label>
										<div class="col-md-10">
											<select name="id_user" id="kurir-pickup-costing" data-placeholder="Choose..." class="form-control select2">
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
							<button type="button" class="btn btn-default" onclick="window.location.href=\'?module=costing-pickup-intercity\'">Cancel</button>
							<button type="submit" class="btn btn-primary pull-right"><i class="fa fa-send"></i> Submit</button>
						</div>
					</form>
					<div id="table-pickup-kurir"></div>
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

//						$query = $mysqli->query("select sum(coalesce(a.costing_pickup_fee,0)) + sum(coalesce(a.costing_pickup_parking,0)) + sum(coalesce(a.costing_pickup_toll,0)) + sum(coalesce(a.biaya_packing,0)) + sum(coalesce(a.costing_men_power,0)) as total_cost from pengiriman a left join user b on (b.id_user=a.pickup_by) left join user c on (c.id_user=a.costing_pickup_by) where pickup_by='$id_user' and DATE_FORMAT(pickup_date,'%Y-%m-%d') BETWEEN '$dari' AND '$sampai' and x_intercity(lokasi_asal)='Y'  and a.id_cabang in (11,16,39) order by created_date asc");
//
//							$r = $query->fetch_assoc();
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
										<th>Pickup date</th>
										<th>Customer</th>
										<th>Origin</th>
										<th>Destination</th>
										<th>Collie</th>
										<th>Weight</th>
										<th>Comodity</th>
										<th>Pickup Cost</th>
										<th>Packing Fee</th>
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
										
										$result_list = $mysqli->query("select id_pengiriman, airwaybill_kode, DATE_FORMAT(pickup_date,'%Y-%m-%d') as pickup_date , x_namakustomer(id_kustomer) as kustomer, x_namakotabesar(lokasi_asal) as lokasi_asal, x_namakotabesar(lokasi_tujuan) as lokasi_tujuan, collie, if(berat_aktual > 0, berat_aktual, berat_barang) as berat_barang , case when komoditi='1' then 'Paket' else 'Dokumen' end as komoditi, costing_pickup_fee, biaya_packing, costing_pickup_parking, costing_pickup_toll ,costing_men_power , ifnull(costing_pickup_fee, 0) + ifnull(costing_pickup_parking, 0) + ifnull(costing_pickup_toll, 0) + ifnull(biaya_packing, 0) + ifnull(costing_men_power, 0) as cost_sum from pengiriman where pickup_by='$id_user' and DATE_FORMAT(pickup_date,'%Y-%m-%d') BETWEEN '$dari' AND '$sampai'  and id_cabang in (11,16,39) order by created_date asc");
										while ($rowlist = $result_list->fetch_assoc()){
											echo '
												<tr>
													<td>'.$rowlist['airwaybill_kode'].'</td>
													<td>'.$rowlist['pickup_date'].'</td>
													<td>'.$rowlist['kustomer'].'</td>
													<td>'.strtoupper($rowlist['lokasi_asal']).'</td>
													<td>'.strtoupper($rowlist['lokasi_tujuan']).'</td>
													<td>'.$rowlist['collie'].'</td>
													<td>'.$rowlist['berat_barang'].'</td>
													<td>'.$rowlist['komoditi'].'</td>
													<td>'.round($rowlist['costing_pickup_fee']). '</td>	
													<td>'.round($rowlist['biaya_packing']). '</td>	
													<td>'.round($rowlist['costing_pickup_parking']). '</td>	
													<td>'.round($rowlist['costing_pickup_toll']). '</td>	
													<td>'.round($rowlist['costing_men_power']). '</td>
													<td> Rp. '.number_format(round($rowlist['cost_sum']),2). '</td>							
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
								<a target="_blank" href="<?php echo "reports/PHPExcel/excel_createcosting_pickup.php?id_user=$id_user&datefrom=$dari&dateuntil=$sampai"?> "><button type="button" class="btn btn-primary pull-right" ><i class="fa fa-file-excel-o"></i> Excel</button></a>
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
			$pickup_by = $pieces['0'];
			$pickup_date = implode('-', array_slice($pieces, 1, 3));
			
			$query = $mysqli->query("select DATE_FORMAT(a.pickup_date,'%Y-%m-%d') as tglpickup, a.costing_pickup_by , a.costing_pickup_save,b.namalengkap as namalengkap, c.namalengkap as nama_costing ,sum(coalesce(a.costing_pickup_fee,0)) as costing_pickup_fee, sum(coalesce(a.costing_pickup_parking,0)) as costing_pickup_parking, sum(coalesce(a.costing_pickup_toll,0)) as costing_pickup_toll , sum(coalesce(a.costing_pickup_fee,0)) + sum(coalesce(a.costing_pickup_parking,0)) + sum(coalesce(a.costing_pickup_toll,0)) + sum(coalesce(a.costing_men_power,0)) as total_cost from pengiriman a left join user b on (b.id_user=a.pickup_by) left join user c on (c.id_user=a.costing_pickup_by) where (a.pickup_by='$pickup_by')  and DATE_FORMAT(a.pickup_date,'%Y-%m-%d')='$pickup_date' ");

			/*echo "select DATE_FORMAT(a.pickup_date,'%Y-%m-%d') as tglpickup, a.costing_pickup_by , a.costing_pickup_save,b.namalengkap as namalengkap, c.namalengkap as nama_costing ,sum(coalesce(a.costing_pickup_fee,0)) as costing_pickup_fee, sum(coalesce(a.costing_pickup_parking,0)) as costing_pickup_parking, sum(coalesce(a.costing_pickup_toll,0)) as costing_pickup_toll , sum(coalesce(a.costing_pickup_fee,0)) + sum(coalesce(a.costing_pickup_parking,0)) + sum(coalesce(a.costing_pickup_toll,0)) + sum(coalesce(a.costing_men_power,0)) as total_cost from pengiriman a left join user b on (b.id_user=a.pickup_by) left join user c on (c.id_user=a.costing_pickup_by) where a.pickup_by='$pickup_by' or pickup_request='$pickup_by'  and DATE_FORMAT(a.pickup_date,'%Y-%m-%d')='$pickup_date'  and a.id_cabang in (11,16,39) ";*/
			$r = $query->fetch_assoc();

                $sqD = "select coalesce(sum(diskon),0) as total_diskon from (select 
                            coalesce(a.costing_pickup_fee, 0) + coalesce(a.costing_pickup_parking, 0) + coalesce(a.costing_pickup_toll, 0) + coalesce(a.costing_men_power, 0) as total_cost,
                            (select total_cost*(diskon_pickup/100)) as diskon
                        from
                            pengiriman a
                                left join
                            user b ON (b.id_user = a.pickup_by)
                                left join
                            user c ON (c.id_user = a.costing_pickup_by)
                        where
                            (a.pickup_by = '$pickup_by')
                                and DATE_FORMAT(a.pickup_date, '%Y-%m-%d') = '$pickup_date') x";

                $qD = mysqli_query($con, $sqD);
                $rD = mysqli_fetch_assoc($qD);

                $totalDiskon = $rD['total_diskon'];
			
			$query_kode_manifest = mysqli_query($con, "select id_manifest, kode_manifest from manifest 
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
					<form class="form-horizontal form-bordered"  method="post" action="'.$aksi.'?module=costing-pickup-intercity&act=calculate">
					<input type="hidden" id="tglpickup" name="tglpickup" class="form-control" value="'.$r['tglpickup'].'">
					<input type="hidden" id="costing_pickup_by" class="form-control" value="'.$r['costing_pickup_by'].'">
					<div class="box-body">
						
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label class="col-md-4 control-label">Manifest Code</label>
									<div class="col-md-8">																					
											<input type="text" id="kode_manifest" class="form-control" value="'.$r_kode['kode_manifest'].','.$r_kode['id_manifest'].'"readonly>
									</div>
								</div>
							</div>
							
							<div class="col-md-4">
								<div class="form-group">
									<label class="col-md-4 control-label">Pickup Cost</label>
									<div class="col-md-8">
										<div class="input-group">
											<div class="input-group-addon">
												IDR
											</div>
											<input type="text" name="pickup_cost" id="pickup_cost" onkeyup="editPickupCost(this.value)" class="form-control" value="'.number_format(round($r['costing_pickup_fee'])).'" '.($u['level'] != 'admin' or $u['user'] != '77' ? ($r['costing_pickup_save'] != null ? 'readonly' : '') : 'enable admin' ).'>
											<input type="hidden" name="pickup_cost_real" id="pickup_cost_real" class="form-control" value="'.round($r['costing_pickup_fee']).'">
											<script language="javascript">										                                            
                                            function editPickupCost(val){                                                
                                                document.getElementById("pickup_cost_real").value = val.replace(/\,/g,\'\');
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
											<input type="text" name="toll_fee" id="toll_fee" onkeyup="editTollFee(this.value)"  class="form-control" value="'.number_format(round($r['costing_pickup_toll'])).'" '.($u['level'] != 'admin' or $u['user'] != '77' ? ($r['costing_pickup_save'] != null ? 'readonly' : '') : 'enable admin' ).'>
											<input type="hidden" name="toll_fee_real" id="toll_fee_real" value="'.round($r['costing_pickup_toll']).'">
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
									<label class="col-md-4 control-label">Total Cost Fee</label>
									<div class="col-md-8">
										<div class="input-group">
											<div class="input-group-addon">
												IDR
											</div>
											<input type="text" name="total_cost" class="form-control" value="'.number_format(round($r['total_cost']-$totalDiskon)).'" readonly>
										</div>
									</div>
								</div>
							</div>	
						
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label class="col-md-4 control-label">Pickup By</label>
									<div class="col-md-8">
										<input type="text" class="form-control" value="'.$r['namalengkap'].'" readonly>
										<input type="hidden" name="pickup_by" value="'.$pickup_by.'">
									</div>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label class="col-md-4 control-label">Costing by</label>
									<div class="col-md-8">										
										<input type="text" readonly name="costing_by" class="form-control" value="'.$r['nama_costing'].'" if>										
									</div>
								</div>
							</div>
						</div>
						<hr>
						<table class="table table-striped table-bordered table-hover" id="report" style="white-space:nowrap;">
						<thead>
							<tr>
								<th>Pickup date</th>
								<th>Airwaybill</th>
								<th>Customer</th>
								<th>Origin</th>
								<th>Destination</th>
								<th>Delivered Date</th>
								<th>Lead Time Pickup</th>
								<th>Reason Late</th>
								<th>Collie</th>
								<th>Weight</th>
								<th>Comodity</th>
								<th>Pickup Cost</th>
								<th>Packing Fee</th>
								<th>Parking Fee</th>
								<th>Toll Fee</th>
								<th>Men Power</th>
								<th>Total cost</th>
								<th>Discount (%)</th>
								<th>Total after Discount</th>
							</tr>
						</thead>
						<tbody>';
						/*echo "select id_pengiriman, airwaybill_kode, x_namakustomer(id_kustomer) as kustomer, x_namakotabesar(lokasi_asal) as lokasi_asal, x_namakotabesar(lokasi_tujuan) as lokasi_tujuan, collie, if(berat_aktual > 0, berat_aktual, berat_barang) as berat_barang , case when komoditi='1' then 'Paket' else 'Dokumen' end as komoditi, costing_pickup_fee, costing_pickup_parking, costing_pickup_toll ,costing_men_power , ifnull(costing_pickup_fee, 0) + ifnull(costing_pickup_parking, 0) + ifnull(costing_pickup_toll, 0) + ifnull(costing_men_power, 0) as cost_sum from pengiriman where (pickup_by='$pickup_by' or pickup_request='pickup_by') and DATE_FORMAT(pickup_date,'%Y-%m-%d')='$pickup_date'   and id_cabang in (11,16,39) order by created_date asc";*/
							$no=1;

							$sql = "select 
                                    pickup_date,
                                    id_pengiriman,
                                    airwaybill_kode,
                                    x_namakustomer(id_kustomer) as kustomer,
                                    ifnull(x_namakotabesar(lokasi_asal), a.kota_asal) as lokasi_asal,
                                    ifnull(x_namakotabesar(lokasi_tujuan), a.kota_tujuan) as lokasi_tujuan,
                                    delivery_date, 
                                    if(pickup_date is null,'-', datediff(pickup_date, date(created_date))) as selisih_hari,
                                    alasan_terlambat_pickup,
                                    collie,
                                    if(berat_aktual > 0,
                                        berat_aktual,
                                        berat_barang) as berat_barang,
                                    case
                                        when komoditi = '1' then 'Paket'
                                        else 'Dokumen'
                                    end as komoditi,
                                    costing_pickup_fee,
                                    costing_pickup_parking,
                                    costing_pickup_toll,
                                    costing_men_power,
                                    ifnull(costing_pickup_fee, 0) + ifnull(costing_pickup_parking, 0) + ifnull(costing_pickup_toll, 0) + ifnull(costing_men_power, 0) + ifnull(biaya_packing, 0) as cost_sum,
                                    diskon_pickup,
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
                                    terlambat_pickup,
                                    biaya_packing,
                                    datediff(a.delivery_date, (select sla_max2)) as selisih_hari2
                                from
                                    pengiriman a
                                    left join sla s on s.kota_asal =  a.id_kota_asal   AND s.kota_tujuan=  a.id_kota_tujuan  AND s.service = a.service
                                where
                                    (pickup_by = '$pickup_by')
                                        and DATE_FORMAT(pickup_date, '%Y-%m-%d') = '$pickup_date'
                                order by created_date asc";

							$result = $mysqli->query($sql);
							$rowcount=mysqli_num_rows($result);
							//echo 'count '.$rowcount;


                            $listAreaBdg = array(3204,3217,3273);
                            $listAreaSby= array(3578);

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

                                $total = $row['cost_sum'];
                                $diskon = $row['diskon_pickup'];
                                $totalAfterDiskon = $total-(($diskon/100)*$total);
                                //$sum_total = $sum_total + $totalAfterDiskon;

                                //$diskonCell = ($telat == "") ? $diskon : $diskon.' <a href="#" class="diskon-pickup" data-type="text" data-pk="'.$row['id_pengiriman'].'~'.$total.'" title="edit">  <i class="fa fa-edit"> </i> </a>';
                                $diskonCell = ($telat != "" || $row['terlambat_pickup'] == 3 || $row['terlambat_pickup'] == 4 || $_SESSION['level'] == 'admin') ? $diskon.' <a href="#" class="diskon-pickup" data-type="text" data-pk="'.$row['id_pengiriman'].'~'.$total.'" title="edit">  <i class="fa fa-edit"> </i> </a>' : $diskon;

								echo'
								<tr>
									<td>'.$row['pickup_date'].'</td>
									<td>'.$row['airwaybill_kode'].'</td>
									<td>'.$row['kustomer'].'</td>
									<td>'.strtoupper($lokasi_asal).'</td>
									<td>'.strtoupper($lokasi_tujuan).'</td>
									
									<td>'.$row['delivery_date'].'</td>
								    <td> '.$row['selisih_hari'].' hari '.$telat.'</td>
								    <td> '.$row['alasan_terlambat_pickup'].'</td>
									
									<td>'.$row['collie'].'</td>
									<td>'.$row['berat_barang'].'</td>
									<td>'.$row['komoditi'].'</td>
									<td>Rp. '.number_format(round($row['costing_pickup_fee']),0). '</td>
									<td><span id="spPacking'.$row['id_pengiriman'].'">'.round($row['biaya_packing']).'</span> <a href="#" class="biaya-packing-pickup" data-type="text" data-pk="'.$row['id_pengiriman'].'" title="edit">  <i class="fa fa-edit"> </i> </a></td>
									<td>'.($row['costing_pickup_fee'] != NULL ? ($row['costing_pickup_parking'] == NULL ? '<button type="button" id="'.$row['id_pengiriman'].'" class="btn btn-xs btn-warning open_parkingcost" ><i class="fa fa-input"></i> input</button>' : $row['costing_pickup_parking']) : '' ).' '.($u['level'] == 'admin' || $u['id_user'] == '77' || $u['id_cabang'] == '16' ? ($row['costing_pickup_parking'] != null ? '<a href="#" class="edit_parking_cost" id="'.$row['id_pengiriman'].'" data-id="'.$row['costing_pickup_parking'].'" title="edit">  <i class="fa fa-edit"> </i> </a>' : '' ) : ($row['costing_pickup_parking'] != null ? ($r['costing_pickup_save'] == null ? '<a href="#" class="edit_parking_cost" id="'.$row['id_pengiriman'].'" data-id="'.$row['costing_pickup_parking'].'" title="edit">  <i class="fa fa-edit"> </i> </a>' : '') : '')).'</td>
									
									
									<td>'.round($row['costing_pickup_toll']).'</td>

									<td>'.($row['costing_pickup_fee'] != NULL ? ($row['costing_men_power'] == NULL ? '<button type="button" id="'.$row['id_pengiriman'].'" class="btn btn-xs btn-warning open_mencost" ><i class="fa fa-input"></i> input</button>' : $row['costing_men_power']) : '') .' '.($u['level'] == 'admin' || $u['id_user'] == '77'  || $u['id_cabang'] == '16' ? ($row['costing_men_power'] != null ? '<a href="#" class="edit_men_cost" id="'.$row['id_pengiriman'].'" data-id="'.$row['costing_men_power'].'" title="edit">  <i class="fa fa-edit"> </i> </a>' : '' ) : ($row['costing_men_power'] != null ? ($r['costing_pickup_save'] == null ? '<a href="#" class="edit_men_cost" id="'.$row['id_pengiriman'].'" data-id="'.$row['costing_men_power'].'" title="edit">  <i class="fa fa-edit"> </i> </a>' : '') : '')).'</td>
									<td> Rp. '.number_format(round($row['cost_sum']),0).'</td>
									<td>'.$diskonCell.'</td>
									<td align="right" align="right" id="totAfterDisc'.$row['id_pengiriman'].'"> Rp. '.number_format(round($totalAfterDiskon),0).'</td>
								</tr>';
								$no++;
							}
							$cek_all = mysqli_query($con, "select * from pengiriman where pickup_by='$pickup_by' and DATE_FORMAT(pickup_date,'%Y-%m-%d')='$pickup_date' and x_intercity(lokasi_asal)='Y' and id_cabang in (11,16,39) and costing_pickup_parking is not null and costing_men_power is not null order by created_date  asc");
							//echo "select * from pengiriman where pickup_by='$pickup_by' and DATE_FORMAT(pickup_date,'%Y-%m-%d')='$pickup_date' and x_intercity(lokasi_asal)='Y' and id_cabang in (11,16,39) and costing_pickup_parking is not null and costing_men_power is not null order by created_date  asc";
							$rowcount_input=mysqli_num_rows($cek_all);
							$rowcek=mysqli_fetch_assoc($cek_all);
							//echo 'count = '.$rowcount_input;
							echo'
							</tbody>
						</table>
					</div><!-- /.box-body -->
					<div class="box-footer">
						<button type="button" class="btn btn-default" onclick="window.location.href = \'?module=costing-pickup-intercity\';">back</button>						
						<input type="submit" class="btn btn-success" id="calculate-costing-pickup" value="calculate" '.$r['costing_pickup_fee'].'" '.($u['level'] != 'admin' or $u['user'] != '77' ? ($r['costing_pickup_save'] != null ? 'disabled' : '') : 'enable admin' ).'>
						<button type="button" class="btn btn-success" id="save" onclick="window.location.href = \'module/mod_costing-pickup/costing-pickup-intercity_action.php?module=costing-pickup-intercity&act=save&pickupby='.$pickup_by.'&date='.$r['tglpickup'].'\';" '.($rowcount != $rowcount_input ? 'disabled' : '') .' '.($u['level'] != 'admin' or $u['user'] != '77' ? ($r['costing_pickup_save'] != null ? 'disabled' : '') : 'enable admin' ).'> save </button>
						<button type="button" class="btn btn-primary pull-right"  onClick="window.open(\'reports/costing-pickup.php?id='.$encrypt.'\',\'mywindow\',\'menubar=1,resizable=1,width=800,height=600\');" ><i class="fa fa-print"></i> Print</button> 

						<a target="_blank" href="reports/PHPExcel/excel_costing_pickup.php?id='.$encrypt.'"><button type="button" class="btn btn-primary pull-right" '.($r['costing_pickup_save'] != null ? '' : 'disabled').'"><i class="fa fa-file-excel-o"></i> Excel</button></a>

						<a target="_blank" href="reports/reportpdf/costing_pickup_pdf.php?id='.$encrypt.'"><button type="button" class="btn btn-primary pull-right" '.($r['costing_pickup_save'] != null ? '' : 'disabled').' ><i class="fa fa-file-pdf-o"></i> PDF</button></a>
						
					</div><!-- /.box-footer -->
					</form>
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
								<form action="'.$aksi.'?module=costing-pickup-intercity&act=costingparking" name="form_costing" method="POST">
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
								<form action="'.$aksi.'?module=costing-pickup-intercity&act=costing_men_power" name="form_costing" method="POST">
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
								<form action="'.$aksi.'?module=costing-pickup-intercity&act=costingparking_edit" name="edit_form_costing" method="POST">
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
								<form action="'.$aksi.'?module=costing-pickup-intercity&act=costing_men_power_edit" name="edit_form_costing" method="POST">
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
									<button type="button" name="costing-pickup-intercity"  data-id="costing-pickup" id="btnsearchcosting" class="btn form-control btn-primary">search</button>
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
						<h3 class="box-title">Report Costing Pickup</h3>
						
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
							<a  readonly target="_blank" href="  reports/PHPExcel/excel_report_costing_pickup_peruser.php?id_user='.$id_user.'&dari='.$dari.'&sampai='.$sampai.' "><button type="button" class="btn btn-primary pull-right" ><i class="fa fa-file-excel-o"></i> Excel</button></a>						
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
						<h3 class="box-title">Report Manifest Pickup</h3>
						<div class="box-tools pull-right">
							<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
							<button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
						</div>
					</div><!-- /.box-header -->					
					<div class="box-body">
						
						
						<table class="table table-striped table-bordered table-hover" id="report" style="white-space:nowrap;">
						<thead>
							<tr>
								<th>Pickup date</th>
								<th>Airwaybill</th>
								<th>Customer</th>
								<th>Origin</th>
								<th>Destination</th>
								<th>Collie</th>
								<th>Weight</th>
								<th>Comodity</th>
								<th>Pickup Cost</th>
								<th>Parking Fee</th>
								<th>Toll Fee</th>
								<th>Men Power</th>
								<th>Total cost</th>
							</tr>
						</thead>
						<tbody>';
						/*echo "select id_pengiriman, airwaybill_kode, x_namakustomer(id_kustomer) as kustomer, x_namakotabesar(lokasi_asal) as lokasi_asal, x_namakotabesar(lokasi_tujuan) as lokasi_tujuan, collie, if(berat_aktual > 0, berat_aktual, berat_barang) as berat_barang , case when komoditi='1' then 'Paket' else 'Dokumen' end as komoditi, costing_pickup_fee, costing_pickup_parking, costing_pickup_toll ,costing_men_power , ifnull(costing_pickup_fee, 0) + ifnull(costing_pickup_parking, 0) + ifnull(costing_pickup_toll, 0) + ifnull(costing_men_power, 0) as cost_sum from pengiriman where (pickup_by='$pickup_by' or pickup_request='pickup_by') and DATE_FORMAT(pickup_date,'%Y-%m-%d')='$pickup_date'   and id_cabang in (11,16,39) order by created_date asc";*/
							$no=1;

							$result = $mysqli->query("select pickup_date,airwaybill_kode,x_namakustomer(id_kustomer) as kustomer,x_namakotabesar(lokasi_asal) as lokasi_asal, x_namakotabesar(lokasi_tujuan) as lokasi_tujuan,collie,berat_barang,case when komoditi='1' then 'P' else 'D' end as komoditi,costing_pickup_fee, costing_pickup_parking, costing_pickup_toll, costing_men_power,ifnull(costing_pickup_fee, 0) + ifnull(costing_pickup_parking, 0) + ifnull(costing_pickup_toll, 0) + ifnull(costing_men_power, 0) as cost_sum from pengiriman where pickup_by='".$id_user."' and tanggal BETWEEN '".$dari."' AND '".$sampai."'");
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
									<td>'.$row['pickup_date'].'</td>
									<td>'.$row['airwaybill_kode'].'</td>
									<td>'.$row['kustomer'].'</td>
									<td>'.strtoupper($lokasi_asal).'</td>
									<td>'.strtoupper($lokasi_tujuan).'</td>
									<td>'.$row['collie'].'</td>
									<td>'.$row['berat_barang'].'</td>
									<td>'.$row['komoditi'].'</td>
									<td>Rp. '.number_format(round($row['costing_pickup_fee']),0). '</td>
									
									<td>Rp. '.number_format(round($row['costing_pickup_parkir']),0). '</td>
									
									<td>'.round($row['costing_pickup_toll']).'</td>

									<td>Rp. '.number_format(round($row['costing_men_power']),0). '</td>
									<td> Rp. '.number_format(round($row['cost_sum']),0).'</td>
								</tr>';
								$no++;
							}
							/*$cek_all = mysqli_query($con, "select * from pengiriman where pickup_by='$pickup_by' and DATE_FORMAT(pickup_date,'%Y-%m-%d')='$pickup_date' and x_intercity(lokasi_asal)='Y' and id_cabang in (11,16,39) and costing_pickup_parking is not null and costing_men_power is not null order by created_date  asc");
							//echo "select * from pengiriman where pickup_by='$pickup_by' and DATE_FORMAT(pickup_date,'%Y-%m-%d')='$pickup_date' and x_intercity(lokasi_asal)='Y' and id_cabang in (11,16,39) and costing_pickup_parking is not null and costing_men_power is not null order by created_date  asc";
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