<section class="content-header">
	<h1>Report History Product<small>report</small></h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-home"></i> Home</a></li>
		<li><a href="#">Reports</a></li>
		<li class="active">History Produck</li>
	</ol>
</section>
<?php

if( isset($_GET['id_barang']) && $_GET['id_barang'] > 0){
$id_kustomer = $_GET['id_kustomer'];
$id_barang = $_GET['id_barang'];

$product = mysqli_query($con,"select nama_barang,stok_terkini,kode_barang,satuan,created_date,stok_awal_masuk,satuan from warehouse_barang where id=$id_barang");
$cek_product = mysqli_num_rows($product);
$r = mysqli_fetch_assoc($product);

$key = "asdf$1011";
$path_gambar="../module/mod_warehouse-barang/foto_barang/".$r['kode_barang'].".jpg";
$aksi="module/mod_warehouse-report/";

if($cek_product > 0){
?>

<section class="content-header">

</section>

<div class="row">
		<div class="col-md-12">
			<div class="box box-primary">
                <div class="box-header">
					<div class="row">
					</div>
				</div>
				<div class="box-body">
					<h4> History <?php echo $r['nama_barang'].' ( '. $r['kode_barang'].' )'; ?></h4>
					<div class="row">
						<div class="col-lg-3 col-xs-6">
							<!-- small box -->
							<div class="small-box bg-aqua">
								<div class="inner">
									<h2>
									
									<?php echo $r['stok_terkini'].' '.$r['satuan']; ?>
									
									</h2>
									<p><h2>Stok terkini</h2> <?php echo $r['nama_barang']; ?></p>
									<p>&nbsp;</p>
				                </div>
				                <div class="icon">
									<i class="ion ion-android-time"></i>
				                </div>
				                
							</div>
						</div><!-- ./col -->
						<div class="col-lg-3 col-xs-6">
							
								
								<div class="small-box bg-aqua">
								<div class="inner">
									<h2><?php echo $r['stok_awal_masuk'].' '.$r['satuan'];?>
									</h2>
									<p><h2>Stok awal</h2> <?php echo $r['nama_barang']; ?></p>
									<p><?php echo $r['created_date']; ?></p>
				                </div>
				                <div class="icon">
									<i class="ion ion-android-time"></i>
				                </div>
				                
							</div>
						</div>
						<div class="col-lg-3 col-xs-6">
							<?php echo '<img 
								style="width:120px;height:120px;
												transition: transform 0.2s;
									            
									           
									            transform-origin: top left;
									            z-index: 9999;

												" 
        									 onmouseover="this.style.transform=\'scale(5)\'" 
         									onmouseout="this.style.transform=\'scale(1)\'"

							 src="'.$path_gambar.'">'; ?>
						</div>
					</div>
					<table id="report" class="table table-striped table-bordered table-hover " width="100%" cellspacing="0" style="white-space:nowrap;">
						<thead>
						
								<tr style="text-transform: uppercase;">
									<th>No</th>
									<th>Tanggal</th>
									<th>Out/In</th>
									<th>Jumlah</th>
									<th>Stok sebelum</th>
									<th>Stok menjadi</th>
									<th>Keterangan</th>
									<th>Serial number</th>
									<th>AWB</th>
									<th>COST CENTER</th>
									<th>REQUESTOR</th>
									<th>PENGIRIM</th>
									<th>PENERIMA</th>
								</tr>
						</thead>
						<tbody>
						<?php

						
						$cari = "select id,out_in,id_produk,jumlah,stok_sebelum,stok_menjadi,x_user(a.created_by) as nama_user,a.created_date,dokumen,awb,a.id_pengiriman,a.catatan, x_noawb(a.id_pengiriman) as no_awb,serial_number, nama_pengirim, delivery_name,b.loc_code,requestor
								
							FROM warehouse_history a

							left join pengiriman b on a.id_pengiriman = b.id_pengiriman
								
							where id_produk='$id_barang' 
								
							order by a.created_date desc, id desc

								";

							
						
						
						$sql = mysqli_query($con, $cari);
						$no = 1;
						$key = "asdf$1011";
						function delete_button($link){
							return '<a href="'.$link.'" class="btn btn-primary"> delete</a>';
						}
						
						while($row = mysqli_fetch_assoc($sql)){
							
							
							echo'
							<tr style="text-transform: uppercase;">
								<td width="20px">'.$no.'</td>
								
								<td width="20px">'.$row['created_date'].'</td>
								<td width="20px">'.$row['out_in'].'</td>
								<td width="20px">'.$row['jumlah'].'</td>
								<td width="20px">'.$row['stok_sebelum'].'</td>
								<td width="20px">'.$row['stok_menjadi'].'</td>
								<td width="20px">'.$row['catatan'].'</td>
								<td width="20px">'.$row['serial_number'].'</td>
								<td width="20px">'.($row['no_awb'] != '' ? $row['no_awb'] : $row['awb']).'</td>
								<td width="20px">'.($row['loc_code'] != '' ? $row['loc_code'] : '').'</td>
								<td width="20px">'.$row['requestor'].'</td>
								<td width="20px">'.$row['nama_pengirim'].'</td>
								<td width="20px">'.$row['delivery_name'].'</td>
								
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
<?php
}
else{
	echo 'Tidak ada history';
}
}else{
	echo 'ga ok';
}
?>