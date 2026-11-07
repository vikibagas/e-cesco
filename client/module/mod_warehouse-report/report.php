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
//$id_departement = $_GET['id_kustomer'];
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
				
				<div class="col-md-4">
					<div class="form-group">
						<label class="col-sm-3 control-label full-right"> Customer</label>
						<div class="col-sm-8"> 
							<input type="hidden" name="module" value="report-warehouse">
							<select name="id_kustomer" class="form-control select2">
							<?php
							
								$query=mysqli_query($con,"select id_kustomer, nama_kustomer,callname from kustomer where warehouse ='Y' and id_kustomer='".$u['id_kustomer']."' order by nama_kustomer ");	
							
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
				
				<div class="col-md-4">
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
				<?php 
					
						
						$q_d=mysqli_query($con,"select departement from kustomer where id_kustomer='".$u['id_kustomer']."' ");	

						$r_d=mysqli_fetch_assoc($q_d);

						//echo $r_d['departement'];
						//echo $u['departement_id'].'<br>';
						//echo $u['departement'];
						//echo $u['customer_admin'];



				 if($r_d['departement'] == 'Y'){
				 	$id_departement = $_GET['id_departement'];
				 ?>
					<div class="col-md-4">
						<div class="form-group">
							<label class="col-sm-3 control-label full-right"> Departement</label>
							<div class="col-sm-8">
								
								<select name="id_departement" class="form-control select2">
								<?php

									if($u['customer_admin'] == 1){
										$where_departement = "";
										echo '<option value="0" selected>ALL</option>';
									}else{
										$where_departement = "id in ( ".$u['departement_id']." ) and";
									}
								
									$query=mysqli_query($con,"select id_kustomer, id,nama_departement from kustomer_departement where $where_departement  id_kustomer='".$u['id_kustomer']."' and aktif = 'Y' order by nama_departement ");	
								
								while($r=mysqli_fetch_assoc($query)){
									if($r['id']==$id_departement){
										echo'
										<option value="'.$r['id'].'" selected>'.$r['nama_departement'].'</option>';
									}else{
										echo'
										<option value="'.$r['id'].'">'.$r['nama_departement'].'</option>';
									}
								}

								?>

								</select>
								
							</div>
						</div>
					</div>

					<?php 
					
					} 
					?>
			</div><!-- /.row -->
		</div><!-- /.box-body -->
        <div class="box-footer">
        	<?php
        		if(isset($_GET['id_departement'])){
        			$get_departement = "&id_departement=".$_GET['id_departement']."";
        		}else{
        			$get_departement = "";
        		}
        	?>
			<button type="submit" class="btn btn-primary pull-right"> <i class="fa fa-search"></i> View</button>&nbsp;
			 <a  readonly target="_blank" href="<?php echo "../reports/PHPExcel/excel_warehouse.php?id_kustomer=".$_GET['id_kustomer']."&gudang=".$gudang."$get_departement"?> ">
			 	<button type="button" class="btn btn-success pull-right" style="margin-right: 10px;margin-left: 10px;" ><i class="fa fa-file-excel-o"></i> Excel History out / in</button></a>&nbsp;

			 	 <a  readonly target="_blank" class="btn btn-success pull-right" href="<?php echo "../reports/PHPExcel/excel_warehouse_report.php?id_kustomer=".$_GET['id_kustomer']."&gudang=".$gudang."$get_departement"?> "><i class="fa fa-file-excel-o"></i> Excel</a>
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
									
									<th>Stok Terkini</th>
									<th>Departement</th>
									
									<th>Exipered Date-Action</th>
									<th>Posisi Rak</th>
									<th>Serial number</th>
									<th>Stok Awal </th>
									<th>Tanggal Entry</th>
								</tr>
						</thead>
						<tbody>
						<?php

						if($r_d['departement'] == 'Y'){
							if($id_departement != 0){
								$where_departement_ = "departement = ".$id_departement." and";
							}else{
								$where_departement_ = "";
							}
							
						
						}else{
							$where_departement_ = "";
						}

						
						$cari = "select a.id,kode_barang,nama_barang,serial_number, pemilik_customer,x_namakustomer(pemilik_customer) as nama_kustomer,stok_awal_masuk, stok_terkini,satuan,a.created_date,expired_date,expired_date_action,
							x_namadepartement(departement) as nama_departement,dengan_serial,
							GROUP_CONCAT(r.nama_rak ORDER BY r.nama_rak SEPARATOR ', ') AS lokasi_rak

								
							FROM warehouse_barang a 

							left JOIN rak_barang br ON a.id = br.id_barang
							left JOIN warehouse_rak r ON br.id_rak = r.id
								
							where $where_departement_ pemilik_customer='$id_kustomer'  and aktif = 'Y'
								
							GROUP BY a.id
							ORDER BY nama_barang;

								";

							//	echo $cari;
						
						
						$sql = mysqli_query($con, $cari);
						$no = 1;
						$key = "asdf$1011";
						while($row = mysqli_fetch_assoc($sql)){
							
							
							echo'
							<tr style="text-transform: uppercase;">
								<td width="20px">'.$no.'</td>
								<td width="20px">
									<div style="position: relative; display: inline-block;">
										<a href="#" onclick="open_photo(this);" class="zoomphoto" data-id="'.$row['kode_barang'].'"><img src="../../e-cesco/module/mod_warehouse-barang/foto_barang/'.$row['kode_barang'].'.jpg" style="width:50px;height:50px;" loading="lazy"></a>
									</div>

								</td>
								<td width="20px">'.$row['nama_barang'].'</td>
								<td width="20px">'.$row['kode_barang'].'</td>
								
								<td width="20px">'.
							        $row['stok_terkini'].' '.$row['satuan'].'
							        <a class="btn btn-sm btn-primary" href="main.php?module=warehouse-history&id_kustomer='.$id_kustomer.'&id_barang='.$row['id'].'" target="_blank"> history</a>
							        '.($row['stok_terkini'] == 0 ? 
							            '<a href="module/mod_warehouse-report/report_action.php?module=warehouse-report&act=nonaktifkan&id_barang='.$row['id'].'" class="btn btn-sm btn-danger" onclick="return confirm(\'Yakin ingin nonaktifkan data ini?\')">
							                <i class="fa fa-remove"></i> non aktifkan
							            </a>' 
							        : '').
							     '</td>
								<td width="20px">'.$row['nama_departement'].'</td>
								
								<td width="20px">
								<a href="#" class="expired_datechange" data-type="text" data-pk="'.$row['id'].'">'.$row['expired_date'].'</a> 
								-'.'
									<a href="#" class="expired_action" data-type="select" data-value="'.$row['expired_date_action'].'" data-pk="'.$row['id'].'">'.$row['expired_date_action'].' '.'</a>
								</td>
								<td width="20px">'.$row['lokasi_rak'].'</td>
								<td width="20px">'.($row['dengan_serial'] == 'Y' ? '<a href="#" id="open_serial_number_report_client'.$row['id'].'" onclick="open_sn(this);" data-id="'.$row['id'].'">lihat serial number <a/>'  :  '' ).'</td>
								<td width="20px">'.$row['stok_awal_masuk'].' '.$row['satuan'].'</td>
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
				
				<div class="col-md-4">
					<div class="form-group">
						<label class="col-sm-5 control-label full-right">Select Customer</label>
						<input type="hidden" name="module" value="report-warehouse">
						<div class="col-sm-7">
							
							<select name="id_kustomer" class="form-control select2">
							<?php
							
								$query=mysqli_query($con,"select id_kustomer, nama_kustomer,callname from kustomer where warehouse ='Y' and id_kustomer='".$u['id_kustomer']."' order by nama_kustomer ");	
							
							while($r=mysqli_fetch_assoc($query)){
								echo'
								<option value="'.$r['id_kustomer'].'">'.$r['callname'].'</option>';
							}

							?>
							</select>
							
						</div>
					</div>
				</div>
				<div class="col-md-4">
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
				<?php 
					
						
						$q_d=mysqli_query($con,"select departement from kustomer where id_kustomer='".$u['id_kustomer']."'  ");	

						$r_d=mysqli_fetch_assoc($q_d);

						//echo $r_d['departement'];
						//echo $u['departement_id'].'<br>';
						//echo $u['departement'];
						//echo $u['customer_admin'];



				 if($r_d['departement'] == 'Y'){
				 ?>
					<div class="col-md-4">
						<div class="form-group">
							<label class="col-sm-3 control-label full-right"> Departement</label>
							<div class="col-sm-8">
								<input type="hidden" name="module" value="report-warehouse">
								<select name="id_departement" class="form-control select2">
								<?php

									if($u['customer_admin'] == 1){
										$where_departement = "";
										echo '<option value="0" selected>ALL</option>';
									}else{
										$where_departement = "id in ( ".$u['departement_id']." ) and";
									}
								
									$query=mysqli_query($con,"select id_kustomer, id,nama_departement from kustomer_departement where $where_departement  id_kustomer='".$u['id_kustomer']."' and aktif = 'Y'  order by nama_departement ");	
								
								while($r=mysqli_fetch_assoc($query)){
									if($r['id_kustomer']==$u['id_kustomer']){
										echo'
										<option value="'.$r['id'].'" selected>'.$r['nama_departement'].'</option>';
									}else{
										echo'
										<option value="'.$r['id'].'">'.$r['nama_departement'].'</option>';
									}
								}

								?>

								</select>
								
							</div>
						</div>
					</div>

					<?php 
					
					} 
					?>

			</div><!-- /.row -->
			<?php 
				if($u['id_user'] == 1418){
			?>
			
			<?php
			}
			?>
		</div><!-- /.box-body -->
        <div class="box-footer">
			<button type="submit" class="btn btn-primary pull-right"> <i class="fa fa-search"></i> View</button>
		</div>
	</div><!-- /.box -->
</form>
<?php	
}

?>


<div id="modal_sn" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
							<h4 class="modal-title" id="myModalLabel">Serial number List</h4>
						</div>
						<div class="modal-content">
							<div class="modal-body"></div>
						</div>
					</div>
				</div>
			</div>

			<div id="modalphoto-zoom" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
							<h4 class="modal-title" id="myModalLabel">DETAIL FOTO</h4>
						</div>
						<div class="modal-content">
							<div class="modal-body">
								<div class="row">
									<div class="col-xs-12">
										<img src="" id="modal-fotozoom" style="max-width: 100%; height: auto; display: block; margin: 0 auto;">
									</div>
									
								</div>
								

							</div>
						</div>
					</div>
				</div>
			</div>



<script>
	function open_sn(ele){
		$("#modal_sn").modal('show');
		var dataid = $(ele).attr("data-id");
		//alert(dataid);
		$.post('module/mod_warehouse-report/report_action.php?module=report&act=show-sn', {
				id: dataid
			},
			function (html) {
				$(".modal-body").html(html);
			}
		);
	}

	function open_photo(ele){
		$("#modalphoto-zoom").modal('show');
		var dataid = $(ele).attr("data-id");
		 $("#modal-fotozoom").attr("src", "../../e-cesco/module/mod_warehouse-barang/foto_barang/" + dataid + ".jpg");

	    // Tampilkan modal
	    $("#modalphoto-zoom").modal('show');
		
	}
</script>