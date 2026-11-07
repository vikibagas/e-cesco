<section class="content-header">
	<h1>Data pemakaian rak<small>data</small></h1>
	<ol class="breadcrumb">
		
		<li><a href="#">Warehouse</a></li>
		<li class="active">Data pemakaian rak</li>
	</ol>
</section>
<br>
<?php

if( isset($_GET['id_kustomer']) && $_GET['id_kustomer'] > 0){
$id_kustomer = $_GET['id_kustomer'];
$gudang = $_GET['gudang'];
?>
<form class="form-horizontal" method="get" action="" autocomplete="off">
	<div class="box box-primary">
		<div class="box-header with-border">
			<!--<h3 class="box-title">Warehouse Report</h3>-->	
			<div class="box-tools pull-right">
				<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
				<button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
			</div>
		</div><!-- /.box-header -->
		<div class="box-body">
			<div class="row">
				
				<div class="col-md-6">
					<div class="form-group">
						<label class="col-sm-3 control-label full-right">Select Customer</label>
						<div class="col-sm-8">
							<input type="hidden" name="module" value="pemakaian-rak">
							
							<select name="id_kustomer" id="id_kustomer" class="form-control select2">
							<?php
							
								$query=mysqli_query($con,"select id_kustomer, nama_kustomer,callname from kustomer where warehouse !='N' order by nama_kustomer ");	
							
							while($r=mysqli_fetch_assoc($query)){
								if($r['id_kustomer']==$id_kustomer){
									echo'
									<option value="'.$r['id_kustomer'].'" selected>'.$r['callname'].'</option>';
								}else{
									echo'
									<option value="'.$r['id_kustomer'].'">'.$r['callname'].'</option>';
								}
							}

							?>

							</select>
							
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label class="col-md-2 control-label">GUDANG <span class="required">* </span></label>
							<div class="col-md-10">
								<select name="gudang" id="gudang" data-placeholder="Gudang..." class="form-control select2" required >
									<?php
										$result = mysqli_query($con,"select id_cabang, nama_cabang, REPLACE(x_namakotanew(id_kota_baru),'KOTA ','' ) as nama_kota from cabang where warehouse='Y' order by nama_cabang");
										echo '<option value="">Select...</option>';
										while ($row = mysqli_fetch_assoc($result)){
												if($row['id_cabang']==$gudang){
												echo '<option  value="' . $row['id_cabang'] . '" selected>' . $row['nama_kota'] . '</option>';	
												}
												else{
													echo '<option  value="' . $row['id_cabang'] . '">' . $row['nama_kota'] . '</option>';	
												}										
										}
									?>
								</select>
							<div class="val-message"></div>					
							</div>
					</div>
				</div>
			</div><!-- /.row -->
		</div><!-- /.box-body -->
        <div class="box-footer">
        	<input type="hidden" name="act" value="data">
			<button type="submit" class="btn btn-primary pull-right"> <i class="fa fa-search"></i> View</button>
			 <a  readonly target="_blank" href="<?php echo "reports/PHPExcel/excel_warehouse.php?id_kustomer=".$_GET['id_kustomer']."&gudang=".$gudang.""?> "><button type="button" class="btn btn-primary pull-right" ><i class="fa fa-file-excel-o"></i> Excel</button></a>
		</div>
	</div><!-- /.box -->
</form>

<!-- mulai tabel-->



<div class="row">
		<div class="col-md-12">
			<div class="box box-primary">
                <div class="box-header">
					<div class="row">
					</div>
				</div>
				<div class="box-body">
					<table id="pemakaian-rak" class="table table-striped table-bordered table-hover " width="100%" cellspacing="0" style="white-space:nowrap;">
						<thead>
						
								<tr style="text-transform: uppercase;">
									<th>NAMA RAK</th>
									
									<th>Nama departement</th>
									<th>Jumlah Pemakaian</th>
								</tr>
						</thead>
						<tbody>
						<?php
						
						?>
						</tbody>
					</table>
				</div><!-- /.box-body -->
			</div><!-- /.box -->
		</div>
	</div><!-- /.row -->


<!--akhir tabel-->
<?php	
}else{

?>
<form class="form-horizontal" method="get" action="" autocomplete="off">
	<div class="box box-primary">
		<div class="box-header with-border">
			<!--<h3 class="box-title">Warehouse Report</h3>-->	
			<div class="box-tools pull-right">
				<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
				<button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
			</div>
		</div><!-- /.box-header -->
		<div class="box-body">
			<div class="row">
				
				<div class="col-md-6">
					<div class="form-group">
						<label class="col-sm-5 control-label full-right">Select Customer</label>
						<div class="col-sm-7">

							<input type="hidden" name="module" value="pemakaian-rak">
							<select name="id_kustomer" id="id_kustomer" class="form-control select2">
							<?php
							
								$query=mysqli_query($con,"select id_kustomer, nama_kustomer,callname from kustomer where warehouse !='N' order by nama_kustomer ");	
							
							while($r=mysqli_fetch_assoc($query)){
								echo'
								<option value="'.$r['id_kustomer'].'">'.$r['callname'].'</option>';
							}

							?>
							</select>
							
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label class="col-md-2 control-label">GUDANG <span class="required">* </span></label>
							<div class="col-md-10">
								<select name="gudang" id="gudang" data-placeholder="Gudang..." class="form-control select2" required >
									<?php
										$result = mysqli_query($con,"select id_cabang, nama_cabang, REPLACE(x_namakotanew(id_kota_baru),'KOTA ','' ) as nama_kota from cabang where warehouse='Y' order by nama_cabang");
										echo '<option value="">Select...</option>';
										while ($row = mysqli_fetch_assoc($result)){
												
												echo '<option '.($u['id_cabang'] == $row['id_cabang'] ? 'selected' : '').' value="' . $row['id_cabang'] . '">' . $row['nama_kota'] . '</option>';											
										}
									?>
								</select>
							<div class="val-message"></div>					
							</div>
					</div>
				</div>
			</div><!-- /.row -->

			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="box box-success">
						<div class="box-header with-border">
							<h3 class="box-title">Penggunaan jumlah rak per departement</h3>
							<div class="box-tools pull-right">
								<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
								<button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
							</div>
						</div><!-- /.box-header -->
						<div class="box-body">
							<!-- DONUT CHART -->
				            
				              <div class="card-body">
				                <canvas id="chartDepartemen" style="min-height: 250px; height: 400px; max-height: 400px; max-width: 100%;"></canvas>
				              </div>
				              <!-- /.card-body -->
				           
						</div>
					</div>	
				</div>
			</div>
		</div><!-- /.box-body -->
        <div class="box-footer">
        	<input type="hidden" name="act" value="data">
			<button type="submit" class="btn btn-primary pull-right"> <i class="fa fa-search"></i> View</button>
		</div>
	</div><!-- /.box -->
</form>
<?php	
}

?>