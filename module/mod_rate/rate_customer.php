<section class="content-header">
	<h1>Rate Customer</h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-home"></i> Home</a></li>
		<li><a href="#">Master Data</a></li>
		<li class="active">Rate Customer</li>
	</ol>
</section>



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
	<form class="form-horizontal" method="post" action="main.php?module=rate" autocomplete="off">
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
						<label class="col-sm-4 control-label full-right">Booking Type</label>
						<div class="col-sm-8">
							<select name="id_kustomer" id="id_kustomer_select" class="form-control select2">
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
				if($u['id_user'] == 83 || $u['id_user'] == 3 || $u['id_user'] == 199 || $u['id_user'] == 12 || $u['id_user'] == 4 ){
			 ?>
				
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
					<table id="ratecustomer" class="table table-striped table-bordered table-hover " width="100%" cellspacing="0" style="white-space:nowrap;">
						<thead>
							
								<tr style="text-transform: uppercase;">
									<th>
										Kustomer
									</th>
									<th>kota Asal</th>
									<th>kota Tujuan</th>									
									<th>Service Type</th>									
									<th>Loc Code</th>
									<th>Min KG</th>
									<th>Harga</th>
									<th>Lead Time</th>
									
								</tr>
						</thead>
						<tbody>
						<?php

						
						/*$cari = "select 
									id_kustomer,
									x_namakustomer(id_kustomer) as nama_kustomer,
									id_lokasi_asal,
									x_namakotanew(id_lokasi_asal) as kota_asal,
									id_lokasi_tujuan,
									x_namakotanew(id_lokasi_tujuan) as kota_tujuan,
									id_pelayanan,
									x_service(id_pelayanan) as service,
									loc_code,
									min_kg,
									harga,
									lead_time
								FROM rate_customer
								where id_kustomer='$id_kustomer' 
								";
						
						$sql = mysqli_query($con, $cari);
						while($row = mysqli_fetch_assoc($sql)){
							
							echo'
							<tr style="text-transform: uppercase;">
								<td width="20px">'.$row['nama_kustomer'].'</td>
								<td width="20px">'.$row['kota_asal'].'</td>
								<td width="20px">'.$row['kota_tujuan'].'</td>
								<td width="50px">'.strtoupper($row['service']).'</td>
								<td width="20px">'.strtoupper($row['loc_code']).'</td>								
								<td width="20px">'.strtoupper($row['min_kg']).'</td>
								<td width="20px">'.strtoupper($row['harga']).'</td>								
								<td width="20px">'.strtoupper($row['lead_time']).'</td>																
							</tr>';
						}*/
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
	<form class="form-horizontal" method="post" action="main.php?module=rate" autocomplete="off">
	<div class="box box-primary">
		<div class="box-header with-border">
			<h3 class="box-title">Rate Customer</h3>
			<div class="box-tools pull-right">
				<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
				<button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
			</div>
		</div><!-- /.box-header -->
		<div class="box-body">
			<div class="row">
				<!--<div class="col-md-5">
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
				</div>-->
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