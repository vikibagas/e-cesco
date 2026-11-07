<section class="content-header">
	<h1>Invoice OR<small>Finance</small></h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-home"></i> Home</a></li>
		<li><a href="#">Finance</a></li>
		<li class="active">Invoice OR</li>
	</ol>
</section>
<?php
if(!isset($_SESSION)){
    session_start();
}

if (empty($_SESSION['username']) AND empty($_SESSION['password'])){
	header('location:../application/login.php');
}
else{
	//cek hak akses user
	if($u['level']=='admin' || $u['id_user']== 5 || $u['level'] == 'finance' || $u['level'] == 'agent')
	{

		$aksi	= "module/mod_invoice-or/invoice-or_action.php";
		
		$act = !empty($_GET['act']) ? $_GET['act'] : '';
		
		switch($act){
			
			default:
			echo'
			<section class="content">
				<div class="row">
					<div class="col-xs-12">
						<div class="box box-primary">
						<form action="reportsnew/print_or.php" target="_blank" id="printor" method="POST"> </form>
							<div class="box-body">
								<table id="invoice-or" class="table table-bordered table-striped table-hover" width="100%" cellspacing="0" style="white-space:nowrap;">
									<thead>
										<tr>
											<th width="10px">Check</th>
											<th>Airwaybill</th>
											<th>Customer</th>
											<th>Nama Pengirim</th>
											<th>Kota Pengirim</th>
											<th>Nama Penerima</th>
											<th>Kota Penerima</th>
											<th>tanggal</th>
											<th>pembayaran</th>
											<th>action</th>
										</tr>
									</thead>
									<tbody>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</section>

			<div class="row">				
				<div class="col-md-12">
						<div class="col-sm-4">
							
							<button id="printor" type="submit" form="printor" class="btn btn-primary"><i class="fa fa-print"></i> Print OR</button>
							
						</div>
				</div>
			</div><br>
							
			<div id="detail-status" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
							<h4 class="modal-title" id="myModalLabel">STATUS AIRWAYBILL</h4>
						</div>
						<div class="modal-content">
							<div class="modal-body"></div>
						</div>
					</div>
				</div>
			</div>

			<div id="cancel-airwaybill" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
							<h4 class="modal-title" id="myModalLabel">Cancel Confirmation</h4>
						</div>
						<div class="modal-content">
							<div class="modal-body"></div>
						</div>
					</div>
				</div>
			</div>

			<div id="cancel-airwaybill" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
							<h4 class="modal-title" id="myModalLabel">Cancel Confirmation</h4>
						</div>
						<div class="modal-content">
							<div class="modal-body"></div>
						</div>
					</div>
				</div>
			</div>
			
			<div id="import-penerima" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
							<h4 class="modal-title" id="myModalLabel">Import Penerima</h4>
						</div>
						<div class="modal-content">
							<div class="modal-body"></div>
						</div>
					</div>
				</div>
			</div>
			
			<div id="modal_bayar" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
							<h4 class="modal-title" id="myModalLabel">Bayar Cash</h4>
						</div>
						<div class="modal-content">
							<div class="modal-body">
								';
								//echo "SELECT airwaybill_kode, id_pengiriman FROM pengiriman where id_kustomer= '$r[id_kustomer]' and tanggal BETWEEN (CURRENT_DATE() - INTERVAL 3 MONTH) AND CURRENT_DATE() and (id_invoice is null or id_invoice = 0) ORDER BY id_pengiriman";
														

								echo '
								<form action="'.$aksi.'?module=belum-tertagih&act=bayar" id="frmBayarOR" method="POST">						
									<div class="form-group" style="padding-bottom: 25px;">
										<label class="col-md-3 control-label">Airwaybill <span class="required">* </span></label>
										<div class="col-md-8">
											<div class="input-group">
												<input type="text" class="form-control" id="awb" readonly>
											</div>
										</div>
									</div>
									<div class="form-group" style="padding-bottom: 25px;">
										<label class="col-md-3 control-label">Tanggal Bayar <span class="required">* </span></label>
										<div class="col-md-8">
											<div class="input-group">
												<div class="input-group-addon">
													<i class="fa fa-calendar"></i>
												</div>
												<input type="text" class="form-control" id="tanggal" name="tanggal" required>
											</div>
										</div>
									</div>
									<div class="form-group" style="padding-bottom: 25px;">
										<label class="col-md-3 control-label">Nilai Rupiah <span class="required">* </span></label>
										<div class="col-md-8">
											<input type="number" id="nilai" name="nilai" value="0" class="form-control" required>
										</div>
									</div>
									<div class="form-group" style="padding-bottom: 25px;">
										<label class="col-md-3 control-label">Diterima Oleh <span class="required">* </span></label>
										<div class="col-md-8">
											<input type="text" name="penerima" id="penerima" value="" class="form-control" required>
										</div>
									</div>
								
									<input type="text" name="id_pengiriman" id="id_pengiriman" class="form-control" />
									<input type="text" name="id_bayar" id="id_bayar" class="form-control" />
									
									<div class="modal-footer">
										<button class="btn btn-success" type="submit" id="btnSubmitBayarOR">Submit</button>
										<button type="reset" class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Cancel</button>
									</div>
								</form>
							</div>

						</div>
					</div>
				</div>
			</div>';
			
			echo '
			<style>
			.select2{
				width: 80% !important;
			}
			</style>
			<div class="row">				
				<div class="col-md-12">
					<div class="form-group">
						<label class="col-sm-1 control-label full-right">Customer:</label>
						<div class="col-sm-4 input-group">
							<select name="kustomer" id="kustomer"  class="form-control select2">
							';
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
							echo '
							</select>
							<button type="button" id="excel" class="btn btn-primary pull-right"><i class="fa fa-file-excel-o"></i> Excel</button>
							
						</div>
					</div>
				</div>
			</div><br>';
			
			break;

			
			
		
		}
	}else{
		echo akses_salah();
	}
}
?>

