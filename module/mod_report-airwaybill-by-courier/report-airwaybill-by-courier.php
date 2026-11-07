<section class="content-header">
	<h1>Airwaybill by Courier<small>report</small></h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-home"></i> Home</a></li>
		<li><a href="#">Reports</a></li>
		<li class="active">Airwaybill by Courier</li>
	</ol>
</section>
<section class="content">
	<?php
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$tanggal = $_POST['tanggal'];
		$id_kustomer = $_POST['id_kustomer'];
	?>
	<form class="form-horizontal" method="post" action="" autocomplete="off">
	<div class="box box-primary">
		<div class="box-header with-border">
			<h3 class="box-title">Airwaybill by Courier</h3>
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
							<select name="id_kustomer" class="form-control select2">
							<?php
							$query=mysqli_query($con,'select id_kustomer, nama_kustomer from kustomer order by nama_kustomer');
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
							<tr>
									<th rowspan="2">Pick Up</th>
									<th rowspan="2">Airwaybill</th>
									<th rowspan="2">Komoditi</th>
									<th rowspan="2">Service Type</th>
									<th rowspan="2">Requestor</th>
									<th rowspan="2">Lockcode</th>
									<th rowspan="2">Consignee</th>
									<th colspan="4"><center>Area</center></th>
									<th colspan="4"><center>Penerima</center></th>
									<th colspan="2">Berat</th>
									<th rowspan="2">Harga</th>
									<th rowspan="2">Packing</th>
									<th rowspan="2">Asuransi</th>
									<th rowspan="2">Add Charge</th>
									<th rowspan="2">Total</th>
									<th rowspan="2"><center>Print</center></th>
								</tr>
								<tr>
									<th width="300px;">Pengirim</th>
									<th>Kota Asal</th>
									<th width="800px;">Alamat Lengkap Asal</th>
									<th>Kota Tujuan</th>
									<th width="300px;">Penerima</th>
									<th width="800px;">Alamat Lengkap Tujuan</th>
									<th>Nama</th>
									<th>Tanggal</th>
									<th>Volume</th>
									<th>Actual</th>
								</tr>
						</thead>
						<tbody>
						<?php
						$id_kustomer = mysqli_real_escape_string($con, $_POST['id_kustomer']);
						$tanggal = mysqli_real_escape_string($con, $_POST['tanggal']);
						$dr = strleft($tanggal, 10);
						$sm = strright($tanggal, 10);
						$dari = jin_date_sql($dr);
						$sampai = jin_date_sql($sm);
						$cari = "select 
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
									alamat_penerima, 
									delivery_name, 
									delivery_date, 
									SUBSTRING_INDEX(ukuran_barang,'x',1) as length, 
									SUBSTRING_INDEX(SUBSTRING_INDEX(ukuran_barang,'x',2),'x',-1) as width, 
									SUBSTRING_INDEX(ukuran_barang,'x',-1) as height, 
									coalesce(berat_aktual,berat_barang) as berat_aktual, 
									case when packing_kayu='1' then 'Packing Oleh Pemilik' when packing_kayu='2' then 'Packing Kayu Oleh Cesco Log.' when packing_kayu='3' then 'Non Packing Kayu Oleh Cesco Log.' end as packing_kayu, 
									asuransi 
								FROM pengiriman 
								where id_kustomer='$id_kustomer' 
								and tanggal between '$dari' 
								and '$sampai'";
						
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
							echo'
							<tr>
								<td width="20px">'.$row['tanggal'].'</td>
								<td width="20px">'.$row['airwaybill_kode'].'</td>
								<td width="20px">'.strtoupper($row['komoditi']).'</td>
								<td width="50px">'.strtoupper($row['service']).'</td>
								<td width="20px">'.strtoupper($row['nama_pengirim']).'</td>
								<td width="20px">'.strtoupper($row['loc_code']).'</td>
								<td width="20px">'.strtoupper($row['nama_penerima']).'</td>
								<td width="20px">'.strtoupper($row['perusahaan_pengirim']).'</td>
								<td width="20px">'.strtoupper($lokasi_asal).'</td>
								<td width="20px">'.strtoupper($row['alamat_pengirim']).'</td>
								<td width="20px">'.strtoupper($lokasi_tujuan).'</td>
								<td width="20px">'.strtoupper($row['perusahaan_penerima']).'</td>
								<td width="20px">'.strtoupper($row['alamat_penerima']).'</td>
								<td width="20px">'.strtoupper($row['delivery_name']).'</td>
								<td width="20px">'.$row['delivery_date'].'</td>
								<td width="20px">'.strtoupper($volume).'</td>
								<td width="20px">'.$row['berat_aktual'].'</td>
								<td>'.$row['packing_kayu'].'</td>
								<td>'.$row['asuransi'].'</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td><center><button class="btn btn-xs btn-success" onClick="window.open(\'reports/airwaybill.php?id='.$id.'\',\'mywindow\',\'menubar=1,resizable=1,width=800,height=600\');"><i class="fa fa-print"></i></button></center></td>
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
	?>
	<form class="form-horizontal" method="post" action="" autocomplete="off">
	<div class="box box-primary">
		<div class="box-header with-border">
			<h3 class="box-title">Airwaybill by Courier</h3>
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