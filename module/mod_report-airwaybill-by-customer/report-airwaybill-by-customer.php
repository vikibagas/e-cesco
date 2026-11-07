<section class="content-header">
	<h1>Airwaybill by Customer<small>report</small></h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-home"></i> Home</a></li>
		<li><a href="#">Reports</a></li>
		<li class="active">Airwaybill by Customer</li>
	</ol>
</section>
<?php
	if($u['id_user'] == 17 || $u['id_user'] == 81) {
		echo '<section class="content">';
	}
	else {
		echo '<section class="content" oncopy="return false" oncut="return false" onpaste="return false">';
	}

?>

	<?php
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$tanggal = $_POST['tanggal'];
		$id_kustomer = $_POST['id_kustomer'];

		$id_kustomer = mysqli_real_escape_string($con, $_POST['id_kustomer']);
						$tanggal = mysqli_real_escape_string($con, $_POST['tanggal']);
						$dr = strleft($tanggal, 10);
						$sm = strright($tanggal, 10);
						$dari = jin_date_sql($dr);
						$sampai = jin_date_sql($sm);
	?>
	<form class="form-horizontal" method="post" action="" autocomplete="off">
	<div class="box box-primary">
		<div class="box-header with-border">
			<h3 class="box-title">Airwaybill by Customer</h3>
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
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-5">
					<div class="form-group">
						<label class="col-sm-4 control-label full-right">Customer</label>
						<div class="col-sm-8">
							<select name="id_kustomer" class="form-control select2">
							<?php
							if($u['id_kustomer']> 0){
								$query=mysqli_query($con,'select id_kustomer, nama_kustomer from kustomer where id_kustomer=$u[id_kustomer] order by nama_kustomer');
								echo 'select id_kustomer, nama_kustomer from kustomer where id_kustomer=$u[id_kustomer] order by nama_kustomer';
							}else{
								$query=mysqli_query($con,'select id_kustomer, nama_kustomer from kustomer order by nama_kustomer');	
							}
							

							while($r=mysqli_fetch_assoc($query)){
								if($r['id_kustomer']==$id_kustomer){
									echo'
									<option value="'.$r['id_kustomer'].'" selected>'.$r['nama_kustomer'].'</option>';
								}else{
									echo'
									<option value="'.$r['id_kustomer'].'">'.$r['nama_kustomer'].'</option>';
								}
							}
							?>
							</select>
							</select>
						</div>
					</div>
				</div>
			</div><!-- /.row -->
		</div><!-- /.box-body -->
        <div class="box-footer">
			<button type="submit" class="btn btn-primary pull-right"> <i class="fa fa-search"></i> View</button>
			<?php 
				if($u['id_user'] == 83 || $u['id_user'] == 3 || $u['id_user'] == 199 || $u['id_user'] == 289 || $u['id_user'] == 12 || $u['id_user'] == 4 || $u['level'] == 'admin_network' || $u['id_user'] == '80' || $u['level'] == 'inbound' || $u['level'] == 'network' || $u['level'] == 'finance' ){
			 ?>
				<a  readonly target="_blank" href="<?php echo "reports/PHPExcel/excel_report_percustomer.php?id_kustomer=".$_POST['id_kustomer']."&dari=$dari&sampai=$sampai"?> "><button type="button" class="btn btn-primary pull-right" ><i class="fa fa-file-excel-o"></i> Excel</button></a>


				<a  readonly target="_blank" href="<?php echo "config/phpspreadsheet/new_report_per_customer.php?id_kustomer=".$_POST['id_kustomer']."&dari=$dari&sampai=$sampai"?> "><button type="button" class="btn btn-primary pull-right" ><i class="fa fa-file-excel-o"></i> XLSX</button></a>
			<?php
			}
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
					<table id="report" class="table table-striped table-bordered table-hover " width="100%" cellspacing="0" style="white-space:nowrap;">
						<thead>
							<tr style="text-transform: uppercase;">
									<th colspan="5"><center>Data</center></th>		
									<th colspan="6"><center>Pengirim</center></th>
									<th colspan="6"><center>Penerima</center></th>												
									<th colspan="2">Berat</th>
									<th rowspan="2">Packing</th>
									<th rowspan="2">Asuransi</th>
									<th rowspan="2">Invoice</th>
									<th rowspan="2" width="150px"><center>Action#</center></th>									
								</tr>
								<tr style="text-transform: uppercase;">
									<th>Booking</th>
									<th>Pick Up</th>
									<th>Airwaybill</th>
									<th>Booking by</th>
									<th>Komoditi</th>
									<th>Service Type</th>
									<th>Requestor</th>
									<th>Loc Code</th>
									<th>Pengirim</th>
									<th>Nama Perusahaan</th>
									<th>Kota Asal</th>
									<th width="800px;">Alamat Lengkap Asal</th>
									<th>Nama Perusahaan</th>
									<th>consignee</th>
									<th>kota tujuan</th>
									<th>alamat lengkap penerima</th>
									<th>nama penerima</th>
									<th>Tanggal</th>																		
									<th>Berat | VOL</th>
									<th>Berat</th>
								</tr>
						</thead>
						<tbody>
						<?php

						
						$cari = "select 
								id_pengiriman,
								date(pickup_date) as tanggal,
								airwaybill_kode,
								case
									when a.komoditi = '1' then 'Paket'
									else 'Dokumen'
								end as komoditi,
								x_service(service) as service,
								a.service as id_pelayanan,
								nama_pengirim,
								a.loc_code,
								pid,
								a.created_date as tanggal_booking,
								IF(created_by != '',x_user(created_by),x_userclient(user_client)) as nama_booking,
								x_namakustomer(a.id_kustomer) as namakustomer,
								nama_penerima,
								case
									when inbound = 'Y' then 'INBOUND'
									when inbound = 'N' then 'OUTBOUND'
									else ''
								end as inbound,
								perusahaan_pengirim,
								lokasi_asal,
								if(id_kota_asal > 0,
									kota_asal,
									x_namakotabesar(lokasi_asal)) as lokasi_asal,
								alamat_pengirim,
								perusahaan_penerima,
								lokasi_tujuan,
								if(id_kota_tujuan > 0,
									kota_tujuan,
									x_namakotabesar(lokasi_tujuan)) as lokasi_tujuan,
								jenis_barang,
								alamat_penerima,
								delivery_name,
								a.status,
								delivery_date,
								a.id_invoice,
								o.id_pembayaran,
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
									(select length) * (select width) * (select height) / 4000,
									if(service = 5,
										(select length) * (select width) * (select height) / 4000,
										(select length) * (select width) * (select height) / 6000)) as volume,
								if(berat_aktual is null or berat_aktual < 1,
									berat_barang,
									berat_aktual) as berat_aktual,
								case
									when a.packing_kayu = '1' then 'Packing Oleh Pemilik'
									when a.packing_kayu = '2' then 'Packing Kayu Oleh Cesco Log.'
									when a.packing_kayu = '3' then 'Non Packing Kayu Oleh Cesco Log.'
								end as packing_kayu,
								asuransi,
								i.status as status_invoice
							FROM
								pengiriman a
									left outer JOIN
								invoice i ON i.id_invoice = a.id_invoice
								left join pembayaran_or o  on a.airwaybill_kode = o.airwaybill
							where a.id_kustomer='$id_kustomer' 
								and a.tanggal between '$dari' 
								and '$sampai'";
						
						
						$sql = mysqli_query($con, $cari);
						while($row = mysqli_fetch_assoc($sql)){
							$key = "asdf$1011";
							$stringawal = $row["id_pengiriman"];
							$id = base64_encrypt($stringawal,$key);
							$volume = ($row['length']*$row['width']*$row['height'])/6000;
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
							
							$sttsInv = ($row['status_invoice'] == 'final') ? 'Y' : 'N';
							
							echo'
							<tr style="text-transform: uppercase;">
								<td width="20px">'.$row['tanggal_booking'].'</td>
								<td width="20px">'.$row['tanggal'].'</td>
								<td width="20px">'.$row['airwaybill_kode'].'</td>
								<td width="20px">'.$row['nama_booking'].'</td>
								<td width="20px">'.strtoupper($row['komoditi']).', '.$row['jenis_barang'].'</td>
								<td width="50px">'.strtoupper($row['service']).'</td>
								<td width="20px">'.strtoupper($row['namakustomer']).'</td>
								<td width="20px">'.strtoupper($row['loc_code']).($row['pid'] != '' ? ', PID = '. $row['pid'] : '').'</td>
								<td width="20px">'.strtoupper($row['nama_pengirim']).'</td>
								<td width="20px">'.strtoupper($row['perusahaan_pengirim']).'</td>
								<td width="20px">'.strtoupper($lokasi_asal).'</td>
								<td width="20px">'.strtoupper($row['alamat_pengirim']).'</td>
								
								<td width="20px">'.strtoupper($row['perusahaan_penerima']).'</td>
								<td width="20px">'.strtoupper($row['nama_penerima']).'</td>										
								<td width="20px">'.strtoupper($lokasi_tujuan).'</td>								
								<td width="20px">'.strtoupper($row['alamat_penerima']).'</td>
								<td width="20px">'.($row['status'] == 7 ? '<button class="btn btn-block btn-danger btn-xs">Cancel</button>' : strtoupper($row['delivery_name']))  .'</td>
								<td width="20px">'.$row['delivery_date'].'</td>
								<td width="20px">';

								 $j=0;
								 $tb = 0;
								 
								 $q = mysqli_query($con, "select * from detail_collie where id_pengiriman='".$row['id_pengiriman']."' order by koli_ke asc ");
								 $ketemu = mysqli_num_rows($q);

								 if($ketemu > 0){
								 	while($roww = mysqli_fetch_assoc($q)){
			 //for($i=1;$i<41;$i++){

									 	if($row['intercity'] == 1){
						              			$beratvol = $roww['p']*$roww['l']*$roww['t']/4000;
						              	}
						             	else{
						           			if($row['id_pelayanan'] == 5){
						           				$beratvol = $roww['p']*$roww['l']*$roww['t']/4000;
						           			}
						              		else{
						              			$beratvol = $roww['p']*$roww['l']*$roww['t']/6000;
						              		}
						              	}

									 	echo '
									 		
											 	
											 	'.$roww['berat'].' KG | 
											 	 '.$roww['p'].' x '.$roww['l'].' x '.$roww['t'].' ( '.number_format($beratvol,2).' )'.'<br>
											 	
											 
									 	';
									 	$j=$j+1;
									 	//$tb=$tb+$roww['berat_real'];
									 }
									
								}else{
									echo ($row['length'] == '' ? 0 : $row['length']).'x'.($row['width'] == '' ? 0 : $row['width']).'x'.($row['height'] == '' ? 0 : $row['height']).'('. number_format($row['volume'],2).' KG )';
								}

								echo 
								'</td>
								<td width="20px">'.$row['berat_aktual'].' KG</td>
								<td>'.strtoupper($row['packing_kayu']).'</td>
								<td>'.$row['asuransi'].'</td>								
								<td>'.$sttsInv.'</td>								
								<td>
									<center>	
								';
								if ($row['id_invoice'] != '' && $row['status'] != 7){
								echo '<a href="https://cesco-logistics.com/e-cesco/main.php?module=invoice-customer&act=update&id='.base64_encrypt($row['id_invoice'],$key).'" class="btn btn-xs btn-success" title="lihat invoice"  target="_blank"><i class="fa fa-share-square-o"></i></a>';	


								

								}
								if($row['id_pembayaran'] > 0 && $row['status'] != 7){
									echo '<a href="#" class="btn btn-xs btn-success"  >OR  <i class="fa fa-money"></i></a>';	
								}
								

								echo '
								<button class="btn btn-xs btn-success" onClick="window.open(\'reportsnew/airwaybillnew.php?id='.$id.'\',\'mywindow\',\'menubar=1,resizable=1,width=800,height=600\');"><i class="fa fa-print"></i></button></center></td>
							</tr>';
						}
						?>
						</tbody>
					</table>
				</div><!-- /.box-body -->
			</div><!-- /.box -->
		</div>
	</div><!-- /.row -->
	<?php
	}else{
	//	echo 'select id_kustomer, nama_kustomer from kustomer where id_kustomer=$u[id_kustomer] order by nama_kustomer';
	?>
	<form class="form-horizontal" method="post" action="" autocomplete="off">
	<div class="box box-primary">
		<div class="box-header with-border">
			<h3 class="box-title">Airwaybill by Customer</h3>
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
			<button type="submit" class="btn btn-primary pull-right"> <i class="fa fa-search"></i> View</button>
		</div>
	</div><!-- /.box -->
	</form>
	<?php
	}
	?>
</section><!-- /.content -->