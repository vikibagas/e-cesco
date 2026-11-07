<section class="content-header">
	<h1>Keluar barang belum tercatat</h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-home"></i> Home</a></li>
		<li><a href="#">Reports</a></li>
		<li class="active">Keluar data belum tercatat</li>
	</ol>
</section>
<br>
<?php

if( isset($_GET['id_kustomer']) && $_GET['id_kustomer'] > 0){
$id_kustomer = $_GET['id_kustomer'];
//$gudang = $_GET['gudang'];
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
							<input type="hidden" name="module" value="keluar-barang-belum-tercatat">
							
							<select name="id_kustomer" id="id_kustomer" class="form-control select2">
								<option value="9999" selected>ALL</option>';
							<?php
						/*	
								$query=mysqli_query($con,"select id_kustomer, nama_kustomer,callname from kustomer where warehouse !='N' order by nama_kustomer ");	
							
							while($r=mysqli_fetch_assoc($query)){
								if($r['id_kustomer']==$id_kustomer){
									echo'
									<option value="'.$r['id_kustomer'].'" selected>'.$r['callname'].'</option>';
								}else{
									echo'
									<option value="'.$r['id_kustomer'].'">'.$r['callname'].'</option>';
								}
							}*/

							?>

							</select>
							
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
					<table id="keluarbarangbelumtercatat" class="table table-striped table-bordered table-hover " width="100%" cellspacing="0" style="white-space:nowrap;">
						<thead>
						
								<tr style="text-transform: uppercase;">																		
									<th>Tanggal</th>
									<th>Nama Produk</th>
									
									<th>Jumlah</th>
									<th>Stok Sebelum</th>
									<th>Stok menjadi </th>
									<th>yang membuat</th>
									<th>catatan</th>
									<th>Serial Number</th>									
									<th>AWB/KETERANGAN</th>									
								</tr>
						</thead>
						<tbody>
						
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

							<input type="hidden" name="module" value="keluar-barang-belum-tercatat">
							<select name="id_kustomer" id="id_kustomer" class="form-control select2">
								<option value="9999" selected>ALL</option>';
							<?php
							
							/*	$query=mysqli_query($con,"select id_kustomer, nama_kustomer,callname from kustomer where warehouse !='N' order by nama_kustomer ");	
							
							while($r=mysqli_fetch_assoc($query)){
								echo'
								<option value="'.$r['id_kustomer'].'">'.$r['callname'].'</option>';
							}*/

							?>
							</select>
							
						</div>
					</div>
				</div>
				
			</div><!-- /.row -->
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