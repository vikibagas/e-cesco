<section class="content-header">
	<h1>Airwaybill by Date<small>report</small></h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-home"></i> Home</a></li>
		<li><a href="#">Reports</a></li>
		<li class="active">Airwaybill by Date</li>
	</ol>
</section>
<section class="content">
	<?php
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$tanggal = $_POST['tanggal'];
		$tipe = $_POST['tipe'];
	?>
		<form class="form-horizontal" method="post" action="" autocomplete="off">
			<div class="box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title">Airwaybill by Date</h3>
					<div class="box-tools pull-right">
						<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
						<button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
					</div>
				</div>
				<!-- /.box-header -->
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
							if($tipe=="1"){
								echo'
								<option value="1" selected>Outbound</option>';
							}else{
								echo'
								<option value="1">Outbound</option>';
							}
							if($tipe=="2"){
								echo'
								<option value="2" selected>Inbound</option>';
							}else{
								echo'
								<option value="2">Inbound</option>';
							}
							?>
							</select>
								</div>
							</div>
						</div>
					</div>
					<!-- /.row -->
				</div>
				<!-- /.box-body -->
				<div class="box-footer">
					<button type="submit" class="btn btn-primary pull-right"> <i class="fa fa-search"></i> View</button>
				</div>
			</div>
			<!-- /.box -->
		</form>

		<div class="row">
			<div class="col-md-12">
				<div class="box box-primary">
					<div class="box-header">
						<div class="row">
						</div>
					</div>
					<div class="box-body">
						<table id="report" class="table table-striped table-bordered table-hover" width="100%" cellspacing="0">
							<!--<table class="table table-striped table-bordered table-hover" width="100%" id="report">-->
							<thead>
								<tr>
									<th>Airwaybill</th>
									<th>Booking Time</th>
									<th>Customer</th>
									<th>Kota Asal</th>
									<th>Kota Tujuan</th>
									<th>Service</th>
									<th>Status</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
								<?php
						$key = 'asdf$1011';
						$encrypt = $_SESSION['id_userx'];
						$id = base64_decrypt($encrypt,$key);
						$useraktif = mysqli_query($con, 'select * from user_client where id_user="'.$id.'"');
						$u = mysqli_fetch_assoc($useraktif);
	
						$tipe = mysqli_real_escape_string($con, $_POST['tipe']);
						$tanggal = mysqli_real_escape_string($con, $_POST['tanggal']);
						$dr = strleft($tanggal, 10);
						$sm = strright($tanggal, 10);
						$dari = jin_date_sql($dr);
						$sampai = jin_date_sql($sm);
						if($tipe=="1"){
							$cari = "SELECT airwaybill_kode, tanggal, DATE_FORMAT(created_date,'%H:%i') as jam, x_namakustomer(id_kustomer) as kustomer, x_namakotabesar(lokasi_asal) as lokasi_asal, x_namakotabesar(lokasi_tujuan) as lokasi_tujuan, x_service(service) as service, status FROM pengiriman where inbound='N' and tanggal between '$dari' and '$sampai' and id_kustomer='".$u['id_kustomer']."'";
						}elseif($tipe=="2"){
							$cari = "SELECT airwaybill_kode, tanggal, DATE_FORMAT(created_date,'%H:%i') as jam, x_namakustomer(id_kustomer) as kustomer, x_namakotabesar(lokasi_asal) as lokasi_asal, x_namakotabesar(lokasi_tujuan) as lokasi_tujuan, x_service(service) as service, status FROM pengiriman where inbound='Y' and tanggal between '$dari' and '$sampai' and id_kustomer='".$u['id_kustomer']."'";
						}else{
							$cari = "SELECT airwaybill_kode, tanggal, DATE_FORMAT(created_date,'%H:%i') as jam, x_namakustomer(id_kustomer) as kustomer, x_namakotabesar(lokasi_asal) as lokasi_asal, x_namakotabesar(lokasi_tujuan) as lokasi_tujuan, x_service(service) as service, status FROM pengiriman where tanggal between '$dari' and '$sampai' and id_kustomer='".$u['id_kustomer']."'";
						}
						$sql = mysqli_query($con, $cari);
						while($row = mysqli_fetch_assoc($sql)){
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
							
							echo'
							<tr>
								<td>'.$row['airwaybill_kode'].'</td>
								<td>'.$row['tanggal'].' '.$row['jam'].'</td>
								<td>'.$row['kustomer'].'</td>
								<td>'.strtoupper($lokasi_asal).'</td>
								<td>'.strtoupper($lokasi_tujuan).'</td>
								<td>'.$row['service'].'</td>
								<td>'.$status.'</td>
								<td><center> 
							<button class="btn btn-xs btn-success" onClick="window.open(\'reports/airwaybiill.php?id='.base64_encrypt($id,$key).'\',\'mywindow\',\'menubar=1,resizable=1,width=800,height=600\');"><i class="fa fa-print"></i></button></center></td>
							</tr>';
						}
						?>
							</tbody>
						</table>
					</div>
					<!-- /.box-body -->
				</div>
				<!-- /.box -->
			</div>
		</div>
		<!-- /.row -->
		<?php
	}else{
	?>
		<form class="form-horizontal" method="post" action="" autocomplete="off">
			<div class="box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title">Airwaybill by Date</h3>
					<div class="box-tools pull-right">
						<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
						<button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
					</div>
				</div>
				<!-- /.box-header -->
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
										<input type="text" class="form-control" id="daterange" name="tanggal" value="<?php echo''.$setdate.' - '.$setdate.'';?>">
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
								<option value="1">Outbound</option>
								<option value="2">Inbound</option>
							</select>
								</div>
							</div>
						</div>
					</div>
					<!-- /.row -->
				</div>
				<!-- /.box-body -->
				<div class="box-footer">
					<button type="submit" class="btn btn-primary pull-right"> <i class="fa fa-search"></i> View</button>
				</div>
			</div>
			<!-- /.box -->
		</form>
		<?php
	}
	?>
</section>
<!-- /.content -->
