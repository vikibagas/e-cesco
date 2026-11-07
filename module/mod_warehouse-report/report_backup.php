<section class="content-header">
	<h1>Report warehouse<small>report</small></h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-home"></i> Home</a></li>
		<li><a href="#">Reports</a></li>
		<li class="active">Warehouse</li>
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
							<input type="hidden" name="module" value="report-warehouse">
							<select name="id_kustomer" class="form-control select2">
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
					<table id="report" class="table table-striped table-bordered table-hover " width="100%" cellspacing="0" style="white-space:nowrap;">
						<thead>
						
								<tr style="text-transform: uppercase;">
									<th>No</th>
									<th>Foto Produk</th>
									<th>Nama Produk</th>
									<th>Kode Produk</th>
									<th>Serial number</th>
									<th>Pemilik</th>
									<th>Stok Awal </th>
									<th>Stok Terkini</th>
									<th>Posisi Rak</th>
									<th>Exipered Date-Action</th>
									<th>Departement</th>
									<th>Tanggal Entry</th>
								</tr>
						</thead>
						<tbody>
						<?php

						
						$cari = "select id,kode_barang,nama_barang,serial_number, pemilik_customer,x_namakustomer(pemilik_customer) as nama_kustomer,stok_awal_masuk, stok_terkini,satuan,x_namarak(id_rak) as rak,created_date,expired_date,expired_date_action,
							x_namadepartement(departement) as nama_departement
								
							FROM warehouse_barang
								
							where pemilik_customer='$id_kustomer' and x_idgudang(id_rak)='$gudang' and aktif='Y'
								
							order by nama_barang

								";
						
						
						$sql = mysqli_query($con, $cari);
						$no = 1;
						$key = "asdf$1011";
						while($row = mysqli_fetch_assoc($sql)){
							
							
							echo'
							<tr style="text-transform: uppercase;">
								<td width="20px">'.$no.'</td>
								<td width="20px"><img src="module/mod_warehouse-barang/foto_barang/'.$row['kode_barang'].'.jpg" style="width:50px;height:50px;"></td>
								<td width="20px">'.$row['nama_barang'].'</td>
								<td width="20px">'.$row['kode_barang'].'</td>
								<td width="20px"><a href="#">lihat serial number <a/></td>
								<td width="20px">'.$row['nama_kustomer'].'</td>
								<td width="20px">'.$row['stok_awal_masuk'].' '.$row['satuan'].'</td>
								<td width="20px">'.$row['stok_terkini'].' '.$row['satuan'].' <a class="btn btn-sm btn-primary" href="main.php?module=warehouse-history&id_kustomer='.$id_kustomer.'&id_barang='.$row['id'].'" target="_blank"> history</td>
								<td width="20px">'.$row['rak'].'</td>
								<td width="20px">'.$row['expired_date'].'-'.$row['expired_date_action'].'</td>
								<td width="20px">'.$row['nama_departement'].'</td>

								<td width="20px">'.$row['created_date'].'</td>
							</td>
								';
								$no++;
						}
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
							<input type="hidden" name="module" value="report-warehouse">
							<select name="id_kustomer" class="form-control select2">
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
		</div><!-- /.box-body -->
        <div class="box-footer">
			<button type="submit" class="btn btn-primary pull-right"> <i class="fa fa-search"></i> View</button>
		</div>
	</div><!-- /.box -->
</form>
<?php	
}

?>