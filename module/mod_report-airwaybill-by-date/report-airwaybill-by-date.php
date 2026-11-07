<section class="content-header">
	<h1>Airwaybill<small>report</small></h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-home"></i> Home</a></li>
		<li><a href="#">Reports</a></li>
		<li class="active">Airwaybill by Date</li>
	</ol>
</section>
<section class="content">
	<?php
	$key = 'asdf$1011';
	$encrypt = $_SESSION['id_user'];
	$id = base64_decrypt($encrypt,$key);
	$useraktif = mysqli_query($con, 'select * from user where id_user="'.$id.'"');
	$u = mysqli_fetch_assoc($useraktif);

	if ($_SERVER["REQUEST_METHOD"] == "POST") {

		$tanggal = $_POST['tanggal'];
		$tanggal = mysqli_real_escape_string($con, $_POST['tanggal']);
						$dr = strleft($tanggal, 10);
						$sm = strright($tanggal, 10);
						$dari = jin_date_sql($dr);
						$sampai = jin_date_sql($sm);
		$tipe = $_POST['tipe'];
		
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
						<label class="col-sm-4 control-label full-right">Booking Type</label>
						<div class="col-sm-8">
							<select name="tipe" class="form-control select2">
							<?php
							if($tipe=="0"){
								echo'
								<option value="0" selected>All</option>';
							}else{
								echo'
								<option value="0">All</option>';
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
			<a  readonly target="_blank" href="<?php echo "reports/PHPExcel/report-by-date.php?dari=$dari&sampai=$sampai&tipe=$tipe"?> "><button type="button" class="btn btn-primary pull-right" ><i class="fa fa-file-excel-o"></i> Excel</button></a>
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
				<div class="table-responsive">
					<table id="report" class="table table-striped table-bordered table-hover " style="white-space:nowrap;">
						<thead>
							<tr>
									<th rowspan="2">No</th>
									<th colspan="5"><center>Data</center></th>		
									<th colspan="6"><center>Pengirim</center></th>
									<th colspan="6"><center>Penerima</center></th>												
									<th colspan="2">Berat</th>
									<th rowspan="2">Packing</th>
									<th rowspan="2">Asuransi</th>
									<th rowspan="2">Invoice</th>
									<th rowspan="2" width="150px"><center>Print</center></th>									
								</tr>
								<tr>
									<th>Order</th>
									<th>Pick Up</th>
									<th>Airwaybill</th>
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
									<th>Volume</th>
									<th>Actual</th>
								</tr>
						</thead>
						<tbody>
						<?php
						$no = 1;
						
						$tipe = mysqli_real_escape_string($con, $_POST['tipe']);
						
						if($tipe=="1"){
							$cari = "SELECT 
							 id_pengiriman, 
									date(pickup_date) as tanggal, 
									airwaybill_kode, 
									case when komoditi='1' then 'Paket' else 'Dokumen' end as komoditi, 
									x_service(service) as service, 
									nama_pengirim, 
									loc_code, 
									x_namakustomer(id_kustomer) as namakustomer, 
									nama_penerima, 
									case when inbound='Y' then 'INBOUND' when inbound='N' then 'OUTBOUND' else '' end as inbound, 
									perusahaan_pengirim,
									lokasi_asal, 
									x_namakotabesar(lokasi_asal) as lokasi_asal, 
									alamat_pengirim, 
									perusahaan_penerima,
									lokasi_tujuan, 
									x_namakotabesar(lokasi_tujuan) as lokasi_tujuan, 
									jenis_barang,
									alamat_penerima, 
									delivery_name, 
									delivery_date, 
									SUBSTRING_INDEX(ukuran_barang,'x',1) as length, 
									SUBSTRING_INDEX(SUBSTRING_INDEX(ukuran_barang,'x',2),'x',-1) as width, 
									SUBSTRING_INDEX(ukuran_barang,'x',-1) as height, 
									if(berat_aktual is null or berat_aktual < 1,  berat_barang, berat_aktual) as berat_aktual, 
									case when packing_kayu='1' then 'Packing Oleh Pemilik' when packing_kayu='2' then 'Packing Kayu Oleh Cesco Log.' when packing_kayu='3' then 'Non Packing Kayu Oleh Cesco Log.' end as packing_kayu, 
									asuransi ,
						status FROM pengiriman where inbound='N' and tanggal between '$dari' and '$sampai' and id_kustomer='".$u['id_kustomer']."'";
						}elseif($tipe=="2"){
							$cari = "SELECT 
							id_pengiriman, 
									date(pickup_date) as tanggal, 
									airwaybill_kode, 
									case when komoditi='1' then 'Paket' else 'Dokumen' end as komoditi, 
									x_service(service) as service, 
									nama_pengirim, 
									loc_code, 
									x_namakustomer(id_kustomer) as namakustomer, 
									nama_penerima, 
									case when inbound='Y' then 'INBOUND' when inbound='N' then 'OUTBOUND' else '' end as inbound, 
									perusahaan_pengirim,
									lokasi_asal, 
									x_namakotabesar(lokasi_asal) as lokasi_asal, 
									alamat_pengirim, 
									perusahaan_penerima,
									lokasi_tujuan, 
									x_namakotabesar(lokasi_tujuan) as lokasi_tujuan, 
									jenis_barang,
									alamat_penerima, 
									delivery_name, 
									delivery_date, 
									SUBSTRING_INDEX(ukuran_barang,'x',1) as length, 
									SUBSTRING_INDEX(SUBSTRING_INDEX(ukuran_barang,'x',2),'x',-1) as width, 
									SUBSTRING_INDEX(ukuran_barang,'x',-1) as height, 
									if(berat_aktual is null or berat_aktual < 1,  berat_barang, berat_aktual) as berat_aktual, 
									case when packing_kayu='1' then 'Packing Oleh Pemilik' when packing_kayu='2' then 'Packing Kayu Oleh Cesco Log.' when packing_kayu='3' then 'Non Packing Kayu Oleh Cesco Log.' end as packing_kayu, 
									asuransi ,
						status FROM pengiriman where inbound='Y' and tanggal between '$dari' and '$sampai' and id_kustomer='".$u['id_kustomer']."'";
						}else{
							$cari = "SELECT 
									a.id_pengiriman,
									airwaybill_kode,
									a.tanggal,
									DATE_FORMAT(a.created_date, '%H:%i') as jam,
									pickup_date,
									case
										when a.komoditi = '1' then 'Paket'
										else 'Dokumen'
									end as komoditi,
									jenis_barang,
									perusahaan_pengirim,
									x_namakustomer(a.id_kustomer) as namakustomer,
									alamat_pengirim,
									nama_pengirim,
									a.id_pengiriman,
									date(pickup_date) as tanggal,
									airwaybill_kode,
									x_service(service) as service,
									nama_pengirim,
									a.loc_code,
									x_namakustomer(a.id_kustomer) as namakustomer,
									nama_penerima,
									case
										when inbound = 'Y' then 'INBOUND'
										when inbound = 'N' then 'OUTBOUND'
										else ''
									end as inbound,
									perusahaan_pengirim,
									a.lokasi_asal as id_lokasi_asal,
									if(a.id_kota_tujuan > 0,
										a.kota_asal,
										x_namakotabesar(lokasi_asal)) as lokasi_asal,
									alamat_pengirim,
									perusahaan_penerima,
									lokasi_tujuan,
									x_namakotabesar(lokasi_tujuan) as lokasi_tujuan,
									jenis_barang,
									alamat_penerima,
									delivery_name,
									if(a.status=6,delivery_date,'') as delivery_date,
									SUBSTRING_INDEX(ukuran_barang, 'x', 1) as length,
									SUBSTRING_INDEX(SUBSTRING_INDEX(ukuran_barang, 'x', 2),
											'x',
											- 1) as width,
									SUBSTRING_INDEX(ukuran_barang, 'x', - 1) as height,
									if(berat_aktual is null or berat_aktual < 1,
										berat_barang,
										berat_aktual) as berat_aktual,
									case
										when packing_kayu = '1' then 'Packing Oleh Pemilik'
										when packing_kayu = '2' then 'Packing Kayu Oleh Cesco Log.'
										when packing_kayu = '3' then 'Non Packing Kayu Oleh Cesco Log.'
									end as packing_kayu,
									asuransi,
									a.status,
									CASE
									WHEN airwaybill_kode LIKE '001-%' THEN 0
									WHEN airwaybill_kode LIKE '002-%' THEN 1
								end as intercity,
									case when upper(cs.kode_kustomer) in ('CIS','DEL') then
									  if((select intercity) = 1,
										((select length) * (select width) * (select height) / 4000),
										if(a.service = 5,
											((select length) * (select width) * (select height) / 4000),
											((select length) * (select width) * (select height) / 6000)))
									else
										if((select intercity) = 1,
											ceiling((select length) * (select width) * (select height) / 4000),
											if(a.service = 5,
												ceiling((select length) * (select width) * (select height) / 4000),
												ceiling((select length) * (select width) * (select height) / 6000)))
									end as volume,
									kode_kustomer,
									i.status as status_invoice ,
									GROUP_CONCAT(
								        CONCAT(dc.p, 'x', dc.l, 'x', dc.t)
								        SEPARATOR '<br>'
								    ) as dimensi
								FROM
									pengiriman a
										
									left  JOIN invoice i ON i.id_invoice = a.id_invoice 
									left join kustomer cs on cs.id_kustomer = a.id_kustomer 
									left join detail_collie dc on dc.id_pengiriman = a.id_pengiriman 
								where a.tanggal between '$dari' and '$sampai'  
								group by a.id_pengiriman
								";
						}


						
						//echo $cari;
						$sql = mysqli_query($con, $cari);
						while($row = mysqli_fetch_assoc($sql)){
							$key = "asdf$1011";
							$stringawal = $row["id_pengiriman"];
							$id = base64_encrypt($stringawal,$key);
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
							
							if($row['status']=='1'){
								$status = '<button class="btn btn-block btn-warning btn-xs">Booking</button>';
							}elseif($row['status']=='2'){
								$status = '<button class="btn btn-block btn-info btn-xs">Pickup</button>';
							}elseif($row['status']=='3'){
								$status = '<button class="btn btn-block btn-primary btn-xs">On Outbound</button>';
							}elseif($row['status']=='4'){
								$status = '<button class="btn btn-block btn-primary btn-xs">On Inbound</button>';
							}elseif($row['status']=='5'){
								$status = '<button class="btn btn-block btn-primary btn-xs">On Delivery</button>';
							}elseif($row['status']=='6'){
								$status = '<button class="btn btn-block btn-success btn-xs">Delivered</button>';
							}elseif($row['status']=='7'){
								$status = '<button class="btn btn-block btn-danger btn-xs">Cancel</button>';
							}elseif($row['status']=='8'){
								$status = '<button class="btn btn-block btn-danger btn-xs">Return</button>';
							}
							
							$volume = ($row['kode_kustomer'] == 'CIS' || $row['kode_kustomer'] == 'DEL') ? round($row['volume'],2) : ceil($row['volume']);
							
							$sttsInv = ($row['status_invoice'] == 'final') ? 'Y' : 'N';
							
							echo'
							<tr>
								<td width="20px">'.$no.'</td>
								<td width="20px">'.$row['tanggal'].'</td>
								<td width="20px">'.$row['pickup_date'].'</td>
								<td width="20px">'.$row['airwaybill_kode'].'</td>
								<td width="20px">'.strtoupper($row['komoditi']).', '.$row['jenis_barang'].'</td>
								<td width="50px">'.strtoupper($row['service']).'</td>
								<td width="20px">'.strtoupper($row['namakustomer']).'</td>
								<td width="20px">'.strtoupper($row['loc_code']).'</td>
								<td width="20px">'.strtoupper($row['nama_pengirim']).'</td>
								<td width="20px">'.strtoupper($row['perusahaan_pengirim']).'</td>
								<td width="20px">'.strtoupper($lokasi_asal).'</td>
								<td width="20px">'.strtoupper($row['alamat_pengirim']).'</td>
								
								<td width="20px">'.strtoupper($row['perusahaan_penerima']).'</td>
								<td width="20px">'.strtoupper($row['nama_penerima']).'</td>										
								<td width="20px">'.strtoupper($lokasi_tujuan).'</td>								
								<td width="20px">'.strtoupper($row['alamat_penerima']).'</td>
								<td width="20px">'.strtoupper($row['delivery_name']).'</td>
								<td width="20px">'.$row['delivery_date'].'</td>
								<td width="20px">'.$row['dimensi'].'</td>
								<td width="20px">'.$row['berat_aktual'].'</td>
								<td>'.$row['packing_kayu'].'</td>
								<td>'.$row['asuransi'].'</td>								
								<td>'.$sttsInv.'</td>								
                           <td><button class="btn btn-xs btn-success" onClick="window.open(\'reports/airwaybill.php?id='.$id.'\',\'mywindow\',\'menubar=1,resizable=1,width=800,height=600\');"><i class="fa fa-print"></i></button></center></td>
							</tr>';
						$no++;
						}?>

						</tbody>
					</table>
				</div><!-- /.box-body -->
			</div><!-- /.box -->
		</div>
	</div><!-- /.row -->
	<?php
	}else{
	?>
	<form class="form-horizontal" method="post" action="" autocomplete="off">
	<div class="box box-primary">
		<div class="box-header with-border">
			<h3 class="box-title">Airwaybill</h3>
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
				<div class="col-md-5">
							<div class="form-group">
								<label class="col-sm-4 control-label full-right">Booking Type</label>
								<div class="col-sm-8">
									<select name="tipe" class="form-control select2">
								<option value="0">All</option>
								
							</select>
								</div>
							</div>
						</div>
				</div>
			</div><!-- /.row -->
		</div><!-- /.box-body -->
        <div class="box-footer">
			<button type="submit" class="btn btn-primary pull-right"> <i class="fa fa-search"></i> View</button>
		</div>
		<script>
			$(document).ready(function() {
    		$('#airwaybill-cust').DataTable( {
       		 dom: 'Bfrtip',
        		buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
      		  ]
   			 } );
			} );
		</script>
	</div><!-- /.box -->
	</form>
	<?php
	}
	?>

</section><!-- /.content -->