<section class="content-header">
	<h1>Delivery Agent<small>report</small></h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-home"></i> Home</a></li>
		<li><a href="#">Reports</a></li>
		<li><a href="#">Manifest</a></li>
		<li class="active">Delivery</li>
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
	if($u['level']=='admin' || $u['level']=='kurir' || $u['level']=='driver' || $u['level']=='operator' || $u['level']=='intercity' || $u['level']=='domex' || $u['level']=='finance' || $u['level']=='agent' OR $u['level']=='inbound' OR $u['level']=='admin_network' OR $u['level']=='network'){

		$aksi	= "module/mod_report-delivery-agent/report-delivery-agent_action.php";
		
		$act = !empty($_GET['act']) ? $_GET['act'] : '';
		
		switch($act){
			
			default:
			echo'
			<section class="content">
				<div class="row">
					<div class="col-xs-12">
						<div class="box box-primary">
							<div class="box-body">

								<table class="table table-striped table-bordered table-hover" id="report-delivery-agent" style="white-space:nowrap;">
									<thead>
										<tr>
											<th>Delivery Date</th>
											<th>Delivery By</th>
											<th>Qty</th>
											<th>Foto Sesuai</th>
											
											<th>Collie</th>
											<th>Action</th>
										</tr>
									</thead>
									<tbody>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</section>';
			
			break;
			
			case "view":
			
			$key = "asdf$1011";
			$encrypt = "$_GET[id]";
			$id = base64_decrypt($encrypt,$key);
			
			$pieces = explode('-', $id);
			$agent = $pieces['0'];
			$courier_date = implode('-', array_slice($pieces, 1, 3));

			$currentDate = date('Y-m-d');
			if($courier_date > $currentDate){
				$tanggal = $currentDate;
			}else{
				$tanggal = $courier_date;
			}
			
			$query = mysqli_query($con, "select nama_cabang from cabang where id_cabang = $agent ");
			$r = mysqli_fetch_assoc($query);

			
			


			//$tanggal = tgl_indo($courier_date);

			echo'
			<section class="content">
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">Delivery Agent</h3>
						<div class="box-tools pull-right">
							<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
							<button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
						</div>
					</div><!-- /.box-header -->
					
					<form action="'.$aksi.'?module=report-delivery-agent&act=checksesuai" id="checksesuai" onsubmit="return verif_security();" name="checksesuai"  method="POST"></form> 
					<form class="form-horizontal form-bordered">
					<div class="box-body">
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label class="col-md-3 control-label">Date</label>
									<div class="col-md-9">
										<div class="input-group">
											<div class="input-group-addon">
												<i class="fa fa-calendar"></i>
											</div>
											<input type="text" class="form-control" value="'.$tanggal.'" readonly>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-8">
								<div class="form-group">
									<label class="col-md-2 control-label">Courier Agent</label>
									<div class="col-md-10">
										<input type="text" class="form-control" value="'.$r['nama_cabang'].'" readonly>
									</div>
								</div>
							</div>
							
						</div>
						<table class="table table-striped table-bordered table-hover" id="manifest-pickup-detail" style="white-space:nowrap;">
						<thead>
							<tr>
								<th style="width: 10px">#</th>
								<th>Airwaybill</th>
								<th>Customer</th>
								<th>Origin</th>
								<th>Destination</th>
								
								<th>Weight & Collie</th>
								<th>Courier</th>
								<th>Received by</th>
								<th>Date</th>
								<th>Foto</th>
								<th>Checklist Sesuai</th>
							</tr>
						</thead>
						<tbody>';
							$no=1;
							
							$result = mysqli_query($con,"select id_pengiriman,airwaybill_kode, x_namakustomer(id_kustomer) as kustomer, kota_asal, kota_tujuan, collie, ifnull(berat_aktual, berat_barang) as berat_barang, case when komoditi='1' then 'Paket' else 'Dokumen' end as komoditi, delivery_name, delivery_date,photo_delivery_sesuai,x_kurir(courier_by) as courier_name from pengiriman where delivery_by_agent='$agent' and DATE_FORMAT(courier_date,'%Y-%m-%d')='$courier_date' and status != 7 and status in (6,8)  order by created_date asc");

							while ($row = mysqli_fetch_assoc($result)){
								if($row['kota_asal']=='Jakarta Selatan' || $row['kota_asal']=='Jakarta Timur' || $row['kota_asal']=='Jakarta Barat' || $row['kota_asal']=='Jakarta Utara' || $row['kota_asal']=='Jakarta Pusat'){
									$lokasi_asal = 'Jakarta';
								}else{
									$lokasi_asal = $row['kota_asal'];
								}
								if($row['kota_tujuan']=='Jakarta Selatan' || $row['kota_tujuan']=='Jakarta Timur' || $row['kota_tujuan']=='Jakarta Barat' || $row['kota_tujuan']=='Jakarta Utara' || $row['kota_tujuan']=='Jakarta Pusat'){
									$lokasi_tujuan = 'Jakarta';
								}else{
									$lokasi_tujuan = $row['kota_tujuan'];
								}
								echo'
								<tr>
									<td><center>'.$no.'</center></td>
									<td>'.$row['airwaybill_kode'].'
									<input type="checkbox" form="plustanggal" name="selectawb[]" value="'.$row['id_pengiriman'].'">
									</td>
									<td>'.$row['kustomer'].'</td>
									<td>'.strtoupper($row['kota_asal']).'</td>
									<td>'.strtoupper($row['kota_tujuan']).'</td>
									
									<td>'.$row['berat_barang'].' KG ('.$row['collie'].' koli)</td>
									
									<td>'.$row['courier_name'].'</td>

									<td>'.$row['delivery_name'].'</td>

									<td>'.tgl_indo($row['delivery_date']).'</td>
									<td><img src="https://cesco-logistics.com/e-cesco/reportsnew/delivery_photo/'.$row['airwaybill_kode'].'.jpg" style="max-width:100px; max-height:125px;" /></td>
									<td><input type="checkbox" form="checksesuai" class="checksesuai" name="selectdeliv[]" value="'.$row['id_pengiriman'].'"> '.($row['photo_delivery_sesuai'] == 'Y' ? '✅' : '').' </td>

								</tr>';
								
									
								
								$no++;
							}

							$count_delivery = mysqli_num_rows($result);

							$count = mysqli_query($con,"select id_pengiriman from pengiriman where delivery_by_agent='$agent' and delivery_date='$courier_date' and delivery_name is not null and status =6 and photo_delivery_sesuai = 'Y' and status !=7 ");

							$count_delivered = mysqli_num_rows($count);

							echo $count_delivery.$count_delivered;
							echo'
							
							</tbody>
						</table>
					</div><!-- /.box-body -->
					<div class="box-footer">
						
						
												
						';

						
						echo '

						<button type="button" class="btn btn-primary pull-right" onclick="checkall();"><i class="fa fa-check"></i> Check All Foto</button>
						
						<input type="hidden" placeholder="security password" id="security_password" value="oke123"	/>
						<button type="submit"  form="checksesuai" class="btn btn-success pull-right" id="submit_foto"   Value="ok"> Foto Sesuai</button>
						<input type="text" placeholder="security password" id="security" class="input pull-right"	/>
						';
						//}

						echo '
					</div><!-- /.box-footer -->
					</form>
				</div><!-- /.box -->
			</section>';

			break;
		}
	}else{
		echo akses_salah();
	}
}
?>


<script>
	function checkall(){
		//alert('check all');


		 $('.checksesuai').prop('checked', true);

		 
}
function verif_security() {
	//alert('Form submitted!');

    var security =  $('#security').val();
    var security_password =  $('#security_password').val();
    if(security != security_password){
    	
    	alert('Password Salah');
    	return false;
    }
    


    //return false;
}

</script>