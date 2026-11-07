<section class="content-header">
	<h1>Invoice Agent<small>report</small></h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-home"></i> Home</a></li>
		<li><a href="#">Finance</a></li>
		<li class="active">Invoice Agent</li>
	</ol>
</section>
<section class="content">
	<?php
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$tanggal = $_POST['tanggal'];
		$id_cabang = $_POST['id_cabang'];
		
		$id_cabang = mysqli_real_escape_string($con, $_POST['id_cabang']);
		$tanggal = mysqli_real_escape_string($con, $_POST['tanggal']);
		$dr = strleft($tanggal, 10);
		$sm = strright($tanggal, 10);
		$dari = jin_date_sql($dr);
		$sampai = jin_date_sql($sm);
	?>
	<form class="form-horizontal" method="post" action="" autocomplete="off">
	<div class="box box-primary">
		<div class="box-header with-border">
			<h3 class="box-title">Invoice Agent</h3>
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
								<input type="text" class="form-control" id="daterange" name="tanggal" value="<?php echo''.$tanggal.'';?>">
								<input type="hidden" value="<?php echo $dari; ?>" id="tgl_dr">
								<input type="hidden" value="<?php echo $sampai; ?>" id="tgl_sampai">
								<input type="hidden" value="<?php echo $id_cabang; ?>" id="id_agent">
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-5">
					<div class="form-group">
						<label class="col-sm-4 control-label full-right">Agent</label>
						<div class="col-sm-8">
							<select name="id_cabang" class="form-control select2">
							<?php
							$query=mysqli_query($con,"select id_cabang, concat(nama_cabang,' ','(',alamat1,')') as nama_cabang from cabang order by concat(nama_cabang,' ','(',alamat1,')')");
							while($r=mysqli_fetch_assoc($query)){
								if($r['id_cabang']==$id_cabang){
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
			</div><!-- /.row -->
		</div><!-- /.box-body -->
        <div class="box-footer">
			<button type="submit" class="btn btn-primary pull-right"> <i class="fa fa-search"></i> View</button>
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
					<table id="invoice" class="table table-striped table-bordered table-hover " width="100%" cellspacing="0" style="white-space:nowrap;">
						<thead>
							<tr>
									<th rowspan="2">Pick Up</th>
									<th rowspan="2">Airwaybill</th>
									<th rowspan="2">Komoditi</th>
									<th rowspan="2">Service Type</th>
									<!--<th rowspan="2">Requestor</th>-->
									<th rowspan="2">Customer's Name</th>
									<th rowspan="2">Lockcode</th>
									<th rowspan="2">Consignee</th>
									<th colspan="3"><center>Area</center></th>
									<th colspan="3"><center>Penerima</center></th>
									<th colspan="2">Berat</th>
									<th rowspan="2">Harga</th>
									<th rowspan="2">Min. Kg</th>
									<th rowspan="2">Packing</th>
									<th rowspan="2">Add Charge</th>
									<th rowspan="2">Detail Add Charge</th>
									<th rowspan="2">Total</th>									
								</tr>	
								<tr>
									<th>Kota Asal</th>
									<th>Kota Tujuan</th>
									<th width="800px;">Alamat Lengkap Tujuan</th>
									<th>Nama</th>
									<th>Tanggal</th>
									<th>Lead time</th>
									<th>Vol.</th>
									<th>Act.</th>
								</tr>
						</thead>
						<tfoot>
						<?php				
						$total = "
                            SELECT 
                                id_pengiriman, 
                                SUM(coalesce(berat_aktual,berat_barang)) AS berat_aktual, 
                                SUM(ifnull(d.price,e.price)) AS harga, 
                                SUM(ifnull(d.min_kg,e.min_kg)) AS min_kg, 
                                SUM(a.biaya_packing) AS biaya_packing, 
                                SUM(a.biaya_tambahan) AS biaya_tambahan, 
                                SUM(if(if(berat_aktual is null or berat_aktual < 1,  berat_barang, berat_aktual) < ifnull(d.min_kg,e.min_kg), (ifnull(d.min_kg,e.min_kg)*ifnull(d.price,e.price))+a.biaya_packing+a.biaya_tambahan , (if(berat_aktual is null or berat_aktual < 1,  berat_barang, berat_aktual)*ifnull(d.price,e.price))+a.biaya_packing+a.biaya_tambahan )) AS total 

                            FROM pengiriman a 
							INNER JOIN kota b ON b.id_kota=a.lokasi_tujuan 
							OR b.id_kota=a.lokasi_asal 
							INNER JOIN cabang c ON c.id_kota=b.id_kota OR b.hub=c.id_kota 
						LEFT JOIN rate_agent d ON d.id_cabang=CASE WHEN x_intercity(a.lokasi_asal)='Y' THEN '39' ELSE c.id_cabang END AND d.id_kota=a.lokasi_tujuan AND d.id_pelayanan=CASE WHEN a.service=5 THEN 3 else a.service end AND d.komoditi=a.komoditi 
							LEFT JOIN rate_agent_inbound e ON e.id_cabang='$id_cabang'  and e.id_pelayanan=CASE WHEN a.service > 3 THEN 3 else a.service end and e.komoditi=a.komoditi 
							WHERE c.id_cabang='$id_cabang' AND a.tanggal BETWEEN '$dari' AND '$sampai' AND ((x_intercity(a.lokasi_asal)='Y' AND x_intercity(a.lokasi_tujuan)!='Y') OR 
(x_intercity(a.lokasi_asal)!='Y' and x_intercity(a.lokasi_tujuan)='Y'))  
							ORDER BY airwaybill_kode";


						
						$sqlt = mysqli_query($con, $total);
						while($t = mysqli_fetch_assoc($sqlt)){
							echo'
							<tr>
								<th colspan="17" style="text-align:right">Total</th>
														
								<th style="text-align:right">'.number_format($t['total']).'</th>
								
							</tr>';
						}
						?>
						</thead>
						<tbody>
						<?php
                        $cari = "
							SELECT 
								id_pengiriman, 
                                date(pickup_date) as tanggal, 
                                airwaybill_kode, 
                                case when a.komoditi='1' then 'Paket' else 'Dokumen' end as komoditi, 
                                x_service(service) as service, 
                                case when a.service=5 then 3 else a.service end as srv,
                                nama_pengirim, 
                                loc_code, 
                                x_namakustomer(id_kustomer) as namakustomer, 
                                nama_penerima, 
                                lokasi_asal, 
                                x_namakotabesar(lokasi_asal) as lokasi_asal, 
                                lokasi_tujuan, 
                                x_namakotabesar(lokasi_tujuan) as lokasi_tujuan, 
                                alamat_penerima, 
                                delivery_name, 
                                delivery_date, 
                                if(delivery_date is null,'-', datediff(delivery_date, date(pickup_date))) as selisih_hari,
                                SUBSTRING_INDEX(ukuran_barang,'x',1) as length, SUBSTRING_INDEX(SUBSTRING_INDEX(ukuran_barang,'x',2),'x',-1) as width, SUBSTRING_INDEX(ukuran_barang,'x',-1) as height, 
                                if(berat_aktual is null or berat_aktual < 1,  berat_barang, berat_aktual) as berat_aktual,
                                berat_aktual as brt_akt,
                            	berat_barang as brt_brg,
                                ifnull(d.price,e.price) as price, 
                                ifnull(d.min_kg,e.min_kg) as min_kg, 
                                a.biaya_packing, 
                                a.biaya_tambahan, 
                                a.keterangan_biaya, 
                                if(if(berat_aktual is null or berat_aktual < 1,  berat_barang, berat_aktual) < ifnull(d.min_kg,e.min_kg), (ifnull(d.min_kg,e.min_kg)*ifnull(d.price,e.price))+a.biaya_packing+a.biaya_tambahan , (if(berat_aktual is null or berat_aktual < 1,  berat_barang, berat_aktual)*ifnull(d.price,e.price))+a.biaya_packing+a.biaya_tambahan ) as total, 
                                d.rateagent_id
							FROM pengiriman a 
							INNER JOIN kota b ON b.id_kota=a.lokasi_tujuan 
							OR b.id_kota=a.lokasi_asal 
							INNER JOIN cabang c ON c.id_kota=b.id_kota OR b.hub=c.id_kota 
						LEFT JOIN rate_agent d ON d.id_cabang=CASE WHEN x_intercity(a.lokasi_asal)='Y' THEN '39' ELSE c.id_cabang END AND d.id_kota=a.lokasi_tujuan AND d.id_pelayanan=CASE WHEN a.service > 3 THEN 3 else a.service end AND d.komoditi=a.komoditi 
							LEFT JOIN rate_agent_inbound e ON e.id_kota=a.lokasi_asal  and e.id_pelayanan=CASE WHEN a.service > 3 THEN 3 else a.service end and e.komoditi=a.komoditi 
							WHERE c.id_cabang='$id_cabang' AND a.tanggal BETWEEN '$dari' AND '$sampai' AND ((x_intercity(a.lokasi_asal)='Y' AND x_intercity(a.lokasi_tujuan)!='Y') OR 
(x_intercity(a.lokasi_asal)!='Y' and x_intercity(a.lokasi_tujuan)='Y')) 
							ORDER BY airwaybill_kode";
						
						$sql = mysqli_query($con, $cari);
						while($row = mysqli_fetch_assoc($sql)){
							$key = "asdf$1011";
							$stringawal = $row["id_pengiriman"];
							$id = base64_encrypt($stringawal,$key);
							$volume = ($r['length']*$r['width']*$r['height'])/6000;
							if($row['lokasi_asal']=='Jakarta Selatan' || $row['lokasi_asal']=='Jakarta Timur' || $row['lokasi_asal']=='Jakarta Barat' ||		$row['lokasi_asal']=='Jakarta Utara' || $row['lokasi_asal']=='Jakarta Pusat'){
								$lokasi_asal = 'Jakarta';
							}else{
								$lokasi_asal = $row['lokasi_asal'];
							}
			
							if($row['lokasi_tujuan']=='Jakarta Selatan' || $row['lokasi_tujuan']=='Jakarta Timur' || $row['lokasi_tujuan']=='Jakarta Barat' ||		$row['lokasi_tujuan']=='Jakarta Utara' || $row['lokasi_tujuan']=='Jakarta Pusat'){
								$lokasi_tujuan = 'Jakarta';
							}else{
								$lokasi_tujuan = $row['lokasi_tujuan'];
							}
							
							if($row['price']>0){
								$price = '<a href="#" class="rate_agent" data-type="text" data-pk="'.$row['rateagent_id'].'">'.number_format($row['price']).'</a>';
							}else{
								$price = '<a href="#" class="rate_agent_create" data-type="text" data-pk="'.$row['id_pengiriman'].'">'.number_format($row['price']).'</a>';
							}
							echo'
							<tr>
								<td width="20px">'.$row['tanggal'].'</td>
								<td width="20px">'.$row['airwaybill_kode'].'</td>
								<td width="20px">'.strtoupper($row['komoditi']).'</td>
								<td width="50px">'.$row['srv'].' - '.strtoupper($row['service']).'</td>
								'; //<td width="20px">'.strtoupper($row['nama_pengirim']).'</td>'
								echo '
								<td width="20px">'.strtoupper($row['namakustomer']).'</td>
								<td width="20px">'.strtoupper($row['loc_code']).'</td>
								<td width="20px">'.strtoupper($row['nama_penerima']).'</td>
								<td width="20px">'.strtoupper($lokasi_asal).'</td>
								<td width="20px">'.strtoupper($lokasi_tujuan).'</td>
								<td width="20px">'.strtoupper($row['alamat_penerima']).'</td>
								<td width="20px">'.strtoupper($row['delivery_name']).'</td>
								<td width="20px">'.$row['delivery_date'].'</td>
								<td width="20px"> '.$row['selisih_hari'].' hari</td>
								<td width="20px" align="right">'.strtoupper($volume).'</td>
								';/*
								<td width="20px" align="right"><a href="#" class="berat_aktual" data-type="text" data-pk="'.$row['id_pengiriman'].'">'.$row['berat_aktual'].'</a></td>
								<td align="right"><a href="#" class="price" data-type="text" data-pk="'.$row['id_pengiriman'].'">'.number_format($row['price']).'</a></td>
								<td align="right" ><a href="#" class="min_kg" data-type="text" data-pk="'.$row['rateagent_id'].'">'.number_format($row['min_kg']).'</a></td>*/echo '
								<td width="20px" align="right">'.$row['brt_brg'].' - '.$row['brt_akt'].' - '.$row['berat_aktual'].'</td>
								<td align="right">'.number_format($row['price']).'</td>
								<td align="right" >'.number_format($row['min_kg']).'</td>
								
								<td align="right" align="right"><a href="#" class="biaya_packing" data-type="text" data-pk="'.$row['id_pengiriman'].'">'.number_format($row['biaya_packing']).'</a></td>
								<td align="right" align="right"><a href="#" class="biaya_tambahan" data-type="text" data-pk="'.$row['id_pengiriman'].'">'.number_format($row['biaya_tambahan']).'</a></td>
								<td><a href="#" class="keterangan_biaya" data-type="text" data-pk="'.$row['id_pengiriman'].'">'.$row['keterangan_biaya'].'</a></td>
								<td align="right">'.number_format($row['total']).'</td>
								
							</tr>';
						}
						?>
						</tbody>						
					</table>
				</div><!-- /.box-body -->
				<div class="box-footer">	
							<button type="button" class="btn btn-success" id="save_invagent" '.($rowcount != $rowcount_input ? 'disabled' : '') .' '.($u['level'] != 'admin' ? ($r['costing_pickup_save'] != null ? 'disabled' : '') : 'enable admin' ).'> save </button>
							<a target="_blank" href="<?php echo "reports/inv_agent.php?id_agent=$id_cabang&datefrom=$dari&dateuntil=$sampai"?> "><button type="button" class="btn btn-primary pull-right" ><i class="fa fa-file-print-o"></i> Print</button></a> 

							<a target="_blank" href="<?php echo "reports/PHPExcel/excel_create_invagent.php?id_agent=$id_cabang&datefrom=$dari&dateuntil=$sampai"?> "><button type="button" class="btn btn-primary pull-right" ><i class="fa fa-file-excel-o"></i> Excel</button></a>

							<a target="_blank" href="<?php echo "reports/reportpdf/inv_agent_pdf.php?id_agent=$id_cabang&datefrom=$dari&dateuntil=$sampai"?> "><button type="button" class="btn btn-primary pull-right" ><i class="fa fa-file-pdf-o"></i> PDF</button></a>
						
						</div><!-- /.box-footer -->
			</div><!-- /.box -->
		</div>
	</div><!-- /.row -->
	<?php
	}else{
	?>
	<form class="form-horizontal" method="post" action="" autocomplete="off">
	<div class="box box-primary">
		<div class="box-header with-border">
			<h3 class="box-title">Invoice Agent</h3>
			<div class="box-tools pull-right">
				<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
				<button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
			</div>
		</div><!-- /.box-header -->
		<div class="box-body">
			<div class="row">
				<div class="col-md-5">
					<div class="form-group">
						<label class="col-sm-5 control-label full-right">Date Range</label>
						<div class="col-sm-7">
							<div class="input-group">
								<div class="input-group-addon">
									<i class="fa fa-calendar"></i>
								</div>
								<input type="text" class="form-control" id="daterange" name="tanggal" value="<?php echo''.$setdate.' - '.$setdate.'';?>" readonly>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label class="col-sm-3 control-label full-right">Agent</label>
						<div class="col-sm-8">
							<select name="id_cabang" class="form-control select2">
							<?php
							$query=mysqli_query($con,"select id_cabang, concat(nama_cabang,' ','(',alamat1,')') as nama_cabang from cabang order by concat(nama_cabang,' ','(',alamat1,')')");
							while($r=mysqli_fetch_assoc($query)){
								echo'
								<option value="'.$r['id_cabang'].'">'.$r['nama_cabang'].'</option>';
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
		</div>
	</div><!-- /.box -->
	</form>
	<?php
	}
	?>
</section><!-- /.content -->
<div id="outbound-assignment" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
				<h4 class="modal-title" id="myModalLabel">Create Rate Agent</h4>
			</div>
			<div class="modal-content">
				<div class="modal-body"></div>
			</div>
		</div>
	</div>
</div>