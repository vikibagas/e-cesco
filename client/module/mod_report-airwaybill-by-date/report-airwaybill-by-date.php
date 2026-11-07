<section class="content-header">
	<h1>Airwaybill<small>report</small></h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-home"></i> Home</a></li>
		<li><a href="#">Reports</a></li>
		<li class="active">Airwaybill by Customer</li>
	</ol>
</section>
<section class="content">
	<?php
	$key = 'asdf$1011';
						$encrypt = $_SESSION['id_userx'];
						$id = base64_decrypt($encrypt,$key);
						$useraktif = mysqli_query($con, 'select * from user_client where id_user="'.$id.'"');
						$u = mysqli_fetch_assoc($useraktif);

	if ($_SERVER["REQUEST_METHOD"] == "POST") {

		$tanggal = $_POST['tanggal'];
		$tanggal = mysqli_real_escape_string($con, $_POST['tanggal']);
						$dr = strleft($tanggal, 10);
						$sm = strright($tanggal, 10);
						$dari = jin_date_sql($dr);
						$sampai = jin_date_sql($sm);
		$tipe = $_POST['tipe'];
		
	?>
	<form class="form-horizontal" method="post" action="" autocomplete="off">
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
				<?php
				$tipe_selected = isset($_POST['tipe']) ? $_POST['tipe'] : '';  
				$options = "";

				// jika user type "all"
				if ($u['type'] == 'all') {
				    $options .= '<option value="0" '.($tipe_selected == "0" ? "selected" : "").'>All</option>';
				    $options .= '<option value="1" '.($tipe_selected == "1" ? "selected" : "").'>Outbound</option>';
				    $options .= '<option value="2" '.($tipe_selected == "2" ? "selected" : "").'>Inbound</option>';
				}
				// jika user type outbound
				elseif ($u['type'] == 'outbound') {
				    $options .= '<option value="1" '.($tipe_selected == "1" ? "selected" : "").'>Outbound</option>';
				}
				// jika user type inbound
				elseif ($u['type'] == 'inbound') {
				    $options .= '<option value="2" '.($tipe_selected == "2" ? "selected" : "").'>Inbound</option>';
				}
				?>
				<div class="col-md-5">
				    <div class="form-group">
				        <label class="col-sm-4 control-label full-right">Booking Type</label>
				        <div class="col-sm-8">
				            <select name="tipe" class="form-control select2">
				                <?= $options ?>
				            </select>
				        </div>
				    </div>
				</div>
			</div><!-- /.row -->
		</div><!-- /.box-body -->
        <div class="box-footer">
			<button type="submit" class="btn btn-primary pull-right"> <i class="fa fa-search"></i> View</button>
			
			
			<?php
			$id_kustomer = $u['id_kustomer'];
			$id_user = $u['id_user'];

				if($u['id_kustomer'] == 106){
			?>
				<?php /*<a  readonly target="_blank" href="<?php echo "../reports/PHPExcel/create_excel_client.php?id_kustomer=".$id_kustomer."&dari=$dari&sampai=$sampai&tipe=$tipe&id_user=".$id_user.""?> "><button type="button" class="btn btn-primary pull-right" ><i class="fa fa-file-excel-o"></i> Excel Asus Aprc</button></a>*/ ?>
			<?php
			}

			?>			
			
			<a  readonly target="_blank" href="<?php echo "../reports/PHPExcel/create_excel_client2.php?id_kustomer=".$id_kustomer."&dari=$dari&sampai=$sampai&tipe=$tipe&id_user=".$id_user.""?> "><button type="button" class="btn btn-primary pull-right" ><i class="fa fa-file-excel-o"></i> Excel</button></a>
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
				<div class="table-responsive">
					<table id="airwaybill-cust" class="table table-striped table-bordered table-hover " style="white-space:nowrap;">
						<thead>
							<tr>
									<th rowspan="2">No</th>
									<th colspan="5"><center>Data</center></th>		
									<th colspan="6"><center>Pengirim</center></th>
									<th colspan="6"><center>Penerima</center></th>												
									<th colspan="2">Berat</th>
									<th rowspan="2">Packing</th>
									<th rowspan="2">Asuransi</th>
									<th rowspan="2" width="150px"><center>Print</center></th>									
								</tr>
								<tr>
									<th>Order</th>
									<th>Tanggal Pick Up</th>
									<th>Jam Pick Up</th>
									<th>Airwaybill</th>
									<th>Komoditi</th>
									<th>Service Type</th>
									<th>Requestor</th>
									<th>Loc Code</th>
									<th>Pengirim</th>
									<th>Nama Perusahaan</th>
									<th>Kota Asal</th>
									<th width="800px;">Alamat Lengkap Asal</th>
									<th>Nama Perusahaan</th>
									<th>consignee</th>
									<th>kota tujuan</th>
									<th>alamat lengkap penerima</th>
									<th>nama penerima</th>
									<th>Tanggal Diterima</th>																		
									
									<th>Actual</th>
								</tr>

						</thead>
						<tbody>
						<?php
						$no = 1;
						
						$tipe = mysqli_real_escape_string($con, $_POST['tipe']);

						//$u['report_awb_by_booking'];
						if ($u['report_awb_by_booking'] == 1) {
							
							if ($tipe=="0"){
									$cari = "SELECT 
									 id_pengiriman, 
											date(tanggal) as tanggal, 
											date(pickup_date) as pickup_date,
											TIME_FORMAT(pickup_date, '%H:%i') as jam_pickup,
											airwaybill_kode, 
											case when komoditi='1' then 'Paket' else 'Dokumen' end as komoditi, 
											x_service(service) as service, 
											nama_pengirim, 
											loc_code,
											pid, 
											x_namakustomer(id_kustomer) as namakustomer, 
											nama_penerima, 
											case when inbound='Y' then 'INBOUND' when inbound='N' then 'OUTBOUND' else '' end as inbound, 
											perusahaan_pengirim,
											lokasi_asal, 
											if(id_kota_asal>0,kota_asal,x_namakotabesar(lokasi_asal)) as lokasi_asal, 
											alamat_pengirim, 
											perusahaan_penerima,
											lokasi_tujuan, 
											if(id_kota_tujuan>0,kota_tujuan,x_namakotabesar(lokasi_tujuan)) as lokasi_tujuan, 
											jenis_barang,
											alamat_penerima, 
											delivery_name, 
											delivery_date, 
											SUBSTRING_INDEX(ukuran_barang,'x',1) as length, 
											SUBSTRING_INDEX(SUBSTRING_INDEX(ukuran_barang,'x',2),'x',-1) as width, 
											SUBSTRING_INDEX(ukuran_barang,'x',-1) as height, 
											if(berat_aktual is null or berat_aktual < 1,  berat_barang, berat_aktual) as berat_aktual, 
											case when packing_kayu='1' then 'Packing Oleh Pemilik' when packing_kayu='2' then 'Packing Kayu Oleh Cesco Log.' when packing_kayu='3' then 'Non Packing Kayu Oleh Cesco Log.' end as packing_kayu, 
											asuransi ,
								status FROM pengiriman where status !=7 and tanggal between '$dari' and '$sampai' and  user_client in  (".$u['id_user'].") ";
							}
							elseif ($tipe=="1"){
								//echo 'outbound';
									$cari = "SELECT 
									 id_pengiriman, 
											date(tanggal) as tanggal, 
											date(pickup_date) as pickup_date,
											TIME_FORMAT(pickup_date, '%H:%i') as jam_pickup,
											airwaybill_kode, 
											case when komoditi='1' then 'Paket' else 'Dokumen' end as komoditi, 
											x_service(service) as service, 
											nama_pengirim, 
											loc_code, 
											pid,
											x_namakustomer(id_kustomer) as namakustomer, 
											nama_penerima, 
											case when inbound='Y' then 'INBOUND' when inbound='N' then 'OUTBOUND' else '' end as inbound, 
											perusahaan_pengirim,
											lokasi_asal, 
											if(id_kota_asal>0,kota_asal,x_namakotabesar(lokasi_asal)) as lokasi_asal, 
											alamat_pengirim, 
											perusahaan_penerima,
											lokasi_tujuan, 
											if(id_kota_tujuan>0,kota_tujuan,x_namakotabesar(lokasi_tujuan)) as lokasi_tujuan, 
											jenis_barang,
											alamat_penerima, 
											delivery_name, 
											delivery_date, 
											SUBSTRING_INDEX(ukuran_barang,'x',1) as length, 
											SUBSTRING_INDEX(SUBSTRING_INDEX(ukuran_barang,'x',2),'x',-1) as width, 
											SUBSTRING_INDEX(ukuran_barang,'x',-1) as height, 
											if(berat_aktual is null or berat_aktual < 1,  berat_barang, berat_aktual) as berat_aktual, 
											case when packing_kayu='1' then 'Packing Oleh Pemilik' when packing_kayu='2' then 'Packing Kayu Oleh Cesco Log.' when packing_kayu='3' then 'Non Packing Kayu Oleh Cesco Log.' end as packing_kayu, 
											asuransi ,
								status FROM pengiriman where status !=7 and x_inter(id_kota_asal) = 'Y' and x_inter(id_kota_tujuan) !='Y' and tanggal between '$dari' and '$sampai' and  user_client in  (".$u['id_user'].") ";	
							}
							elseif ($tipe=="2"){
								//echo 'inbound'.$_POST['tipe'];
									$cari = "SELECT 
									 id_pengiriman, 
											date(tanggal) as tanggal, 
											date(pickup_date) as pickup_date,
											TIME_FORMAT(pickup_date, '%H:%i') as jam_pickup,
											airwaybill_kode, 
											case when komoditi='1' then 'Paket' else 'Dokumen' end as komoditi, 
											x_service(service) as service, 
											nama_pengirim, 
											loc_code, 
											pid,
											x_namakustomer(id_kustomer) as namakustomer, 
											nama_penerima, 
											case when inbound='Y' then 'INBOUND' when inbound='N' then 'OUTBOUND' else '' end as inbound, 
											perusahaan_pengirim,
											lokasi_asal, 
											if(id_kota_asal>0,kota_asal,x_namakotabesar(lokasi_asal)) as lokasi_asal, 
											alamat_pengirim, 
											perusahaan_penerima,
											lokasi_tujuan, 
											if(id_kota_tujuan>0,kota_tujuan,x_namakotabesar(lokasi_tujuan)) as lokasi_tujuan, 
											jenis_barang,
											alamat_penerima, 
											delivery_name, 
											delivery_date, 
											SUBSTRING_INDEX(ukuran_barang,'x',1) as length, 
											SUBSTRING_INDEX(SUBSTRING_INDEX(ukuran_barang,'x',2),'x',-1) as width, 
											SUBSTRING_INDEX(ukuran_barang,'x',-1) as height, 
											if(berat_aktual is null or berat_aktual < 1,  berat_barang, berat_aktual) as berat_aktual, 
											case when packing_kayu='1' then 'Packing Oleh Pemilik' when packing_kayu='2' then 'Packing Kayu Oleh Cesco Log.' when packing_kayu='3' then 'Non Packing Kayu Oleh Cesco Log.' end as packing_kayu, 
											asuransi ,
								status FROM pengiriman where status !=7 and x_inter(id_kota_asal) != 'Y' and x_inter(id_kota_tujuan) ='Y' and tanggal between '$dari' and '$sampai' and  user_client in  (".$u['id_user'].") ";	
							}
						}
						

						//jika bukan report_awb_by_booking
						else{
							//jika repoert_awb_loc != 1
							if($u['report_awb_loc'] != 1){
								if ($tipe=="0"){
									$cari = "SELECT 
									 id_pengiriman, 
											date(tanggal) as tanggal, 
											date(pickup_date) as pickup_date,
											TIME_FORMAT(pickup_date, '%H:%i') as jam_pickup,
											airwaybill_kode, 
											case when komoditi='1' then 'Paket' else 'Dokumen' end as komoditi, 
											x_service(service) as service, 
											nama_pengirim, 
											loc_code,
											pid, 
											x_namakustomer(id_kustomer) as namakustomer, 
											nama_penerima, 
											case when inbound='Y' then 'INBOUND' when inbound='N' then 'OUTBOUND' else '' end as inbound, 
											perusahaan_pengirim,
											lokasi_asal, 
											if(id_kota_asal>0,kota_asal,x_namakotabesar(lokasi_asal)) as lokasi_asal, 
											alamat_pengirim, 
											perusahaan_penerima,
											lokasi_tujuan, 
											if(id_kota_tujuan>0,kota_tujuan,x_namakotabesar(lokasi_tujuan)) as lokasi_tujuan, 
											jenis_barang,
											alamat_penerima, 
											delivery_name, 
											delivery_date, 
											SUBSTRING_INDEX(ukuran_barang,'x',1) as length, 
											SUBSTRING_INDEX(SUBSTRING_INDEX(ukuran_barang,'x',2),'x',-1) as width, 
											SUBSTRING_INDEX(ukuran_barang,'x',-1) as height, 
											if(berat_aktual is null or berat_aktual < 1,  berat_barang, berat_aktual) as berat_aktual, 
											case when packing_kayu='1' then 'Packing Oleh Pemilik' when packing_kayu='2' then 'Packing Kayu Oleh Cesco Log.' when packing_kayu='3' then 'Non Packing Kayu Oleh Cesco Log.' end as packing_kayu, 
											asuransi ,
											status FROM pengiriman where status !=7 and tanggal between '$dari' and '$sampai' and id_kustomer='".$u['id_kustomer']."' ";
								}
								elseif($tipe=="1"){
									$cari = "SELECT 
									 id_pengiriman, 
											date(tanggal) as tanggal, 
											date(pickup_date) as pickup_date,
											TIME_FORMAT(pickup_date, '%H:%i') as jam_pickup,
											airwaybill_kode, 
											case when komoditi='1' then 'Paket' else 'Dokumen' end as komoditi, 
											x_service(service) as service, 
											nama_pengirim, 
											loc_code, 
											pid,
											x_namakustomer(id_kustomer) as namakustomer, 
											nama_penerima, 
											case when inbound='Y' then 'INBOUND' when inbound='N' then 'OUTBOUND' else '' end as inbound, 
											perusahaan_pengirim,
											lokasi_asal, 
											if(id_kota_asal>0,kota_asal,x_namakotabesar(lokasi_asal)) as lokasi_asal, 
											alamat_pengirim, 
											perusahaan_penerima,
											lokasi_tujuan, 
											if(id_kota_tujuan>0,kota_tujuan,x_namakotabesar(lokasi_tujuan)) as lokasi_tujuan, 
											jenis_barang,
											alamat_penerima, 
											delivery_name, 
											delivery_date, 
											SUBSTRING_INDEX(ukuran_barang,'x',1) as length, 
											SUBSTRING_INDEX(SUBSTRING_INDEX(ukuran_barang,'x',2),'x',-1) as width, 
											SUBSTRING_INDEX(ukuran_barang,'x',-1) as height, 
											if(berat_aktual is null or berat_aktual < 1,  berat_barang, berat_aktual) as berat_aktual, 
											case when packing_kayu='1' then 'Packing Oleh Pemilik' when packing_kayu='2' then 'Packing Kayu Oleh Cesco Log.' when packing_kayu='3' then 'Non Packing Kayu Oleh Cesco Log.' end as packing_kayu, 
											asuransi ,
										status FROM pengiriman where status !=7 and x_inter(id_kota_asal) = 'Y' and x_inter(id_kota_tujuan) !='Y' and tanggal between '$dari' and '$sampai' and id_kustomer='".$u['id_kustomer']."'";
								
								}elseif($tipe=="2"){
									 $cari = "SELECT 
									id_pengiriman, 
											date(tanggal) as tanggal, 
											date(pickup_date) as pickup_date,
											TIME_FORMAT(pickup_date, '%H:%i') as jam_pickup,
											airwaybill_kode, 
											case when komoditi='1' then 'Paket' else 'Dokumen' end as komoditi, 
											x_service(service) as service, 
											nama_pengirim, 
											loc_code, 
											pid,
											x_namakustomer(id_kustomer) as namakustomer, 
											nama_penerima, 
											case when inbound='Y' then 'INBOUND' when inbound='N' then 'OUTBOUND' else '' end as inbound, 
											perusahaan_pengirim,
											lokasi_asal, 
											if(id_kota_asal>0,kota_asal,x_namakotabesar(lokasi_asal)) as lokasi_asal, 
											alamat_pengirim, 
											perusahaan_penerima,
											lokasi_tujuan, 
											if(id_kota_tujuan>0,kota_tujuan,x_namakotabesar(lokasi_tujuan)) as lokasi_tujuan, 
											jenis_barang,
											alamat_penerima, 
											delivery_name, 
											delivery_date, 
											SUBSTRING_INDEX(ukuran_barang,'x',1) as length, 
											SUBSTRING_INDEX(SUBSTRING_INDEX(ukuran_barang,'x',2),'x',-1) as width, 
											SUBSTRING_INDEX(ukuran_barang,'x',-1) as height, 
											if(berat_aktual is null or berat_aktual < 1,  berat_barang, berat_aktual) as berat_aktual, 
											case when packing_kayu='1' then 'Packing Oleh Pemilik' when packing_kayu='2' then 'Packing Kayu Oleh Cesco Log.' when packing_kayu='3' then 'Non Packing Kayu Oleh Cesco Log.' end as packing_kayu, 
											asuransi ,
								status FROM pengiriman where status !=7 and x_inter(id_kota_asal) != 'Y' and x_inter(id_kota_tujuan) ='Y' and tanggal between '$dari' and '$sampai' and id_kustomer='".$u['id_kustomer']."'";
								}else{
									$cari = "SELECT 
									id_pengiriman,
									 airwaybill_kode,
									 tanggal, 
									 DATE_FORMAT(created_date,'%H:%i') as jam, 
									date(pickup_date) as pickup_date,
									TIME_FORMAT(pickup_date, '%H:%i') as jam_pickup,
									 case when komoditi='1' then 'Paket' else 'Dokumen' end as komoditi,
									 jenis_barang,
									 perusahaan_pengirim,
									 x_namakustomer(id_kustomer) as namakustomer, 
									 alamat_pengirim,
									 nama_pengirim,id_pengiriman, 
											date(tanggal) as tanggal, 
											airwaybill_kode, 
											case when komoditi='1' then 'Paket' else 'Dokumen' end as komoditi, 
											x_service(service) as service, 
											nama_pengirim, 
											loc_code, 
											pid,
											x_namakustomer(id_kustomer) as namakustomer, 
											nama_penerima, 
											case when inbound='Y' then 'INBOUND' when inbound='N' then 'OUTBOUND' else '' end as inbound, 
											perusahaan_pengirim,
											lokasi_asal, 
											if(id_kota_asal>0,kota_asal,x_namakotabesar(lokasi_asal)) as lokasi_asal, 
											alamat_pengirim, 
											perusahaan_penerima,
											lokasi_tujuan, 
											if(id_kota_tujuan>0,kota_tujuan,x_namakotabesar(lokasi_tujuan)) as lokasi_tujuan, 
											jenis_barang,
											alamat_penerima, 
											delivery_name, 
											delivery_date, 
											SUBSTRING_INDEX(ukuran_barang,'x',1) as length, 
											SUBSTRING_INDEX(SUBSTRING_INDEX(ukuran_barang,'x',2),'x',-1) as width, 
											SUBSTRING_INDEX(ukuran_barang,'x',-1) as height, 
											if(berat_aktual is null or berat_aktual < 1,  berat_barang, berat_aktual) as berat_aktual, 
											case when packing_kayu='1' then 'Packing Oleh Pemilik' when packing_kayu='2' then 'Packing Kayu Oleh Cesco Log.' when packing_kayu='3' then 'Non Packing Kayu Oleh Cesco Log.' end as packing_kayu, 
											asuransi,  
									 status FROM pengiriman where status !=7 and tanggal   between '$dari' and '$sampai' and id_kustomer='".$u['id_kustomer']."'";
								}
							}
							//berdasarkan loc code
							else{
								if ($tipe=="0"){
									$cari = "SELECT 
									 id_pengiriman, 
											date(tanggal) as tanggal, 
											date(pickup_date) as pickup_date,
											TIME_FORMAT(pickup_date, '%H:%i') as jam_pickup,
											airwaybill_kode, 
											case when komoditi='1' then 'Paket' else 'Dokumen' end as komoditi, 
											x_service(service) as service, 
											nama_pengirim, 
											loc_code,
											pid, 
											x_namakustomer(id_kustomer) as namakustomer, 
											nama_penerima, 
											case when inbound='Y' then 'INBOUND' when inbound='N' then 'OUTBOUND' else '' end as inbound, 
											perusahaan_pengirim,
											lokasi_asal, 
											if(id_kota_asal>0,kota_asal,x_namakotabesar(lokasi_asal)) as lokasi_asal, 
											alamat_pengirim, 
											perusahaan_penerima,
											lokasi_tujuan, 
											if(id_kota_tujuan>0,kota_tujuan,x_namakotabesar(lokasi_tujuan)) as lokasi_tujuan, 
											jenis_barang,
											alamat_penerima, 
											delivery_name, 
											delivery_date, 
											SUBSTRING_INDEX(ukuran_barang,'x',1) as length, 
											SUBSTRING_INDEX(SUBSTRING_INDEX(ukuran_barang,'x',2),'x',-1) as width, 
											SUBSTRING_INDEX(ukuran_barang,'x',-1) as height, 
											if(berat_aktual is null or berat_aktual < 1,  berat_barang, berat_aktual) as berat_aktual, 
											case when packing_kayu='1' then 'Packing Oleh Pemilik' when packing_kayu='2' then 'Packing Kayu Oleh Cesco Log.' when packing_kayu='3' then 'Non Packing Kayu Oleh Cesco Log.' end as packing_kayu, 
											asuransi ,
											status FROM pengiriman where status !=7 and tanggal between '$dari' and '$sampai'  and loc_code in($u[loc_code_otomatis] and  user_client in  (".$u['id_user'].") ";
								}
								if($tipe=="1"){
									$cari = "SELECT 
									 id_pengiriman, 
											date(tanggal) as tanggal, 
											date(pickup_date) as pickup_date,
											TIME_FORMAT(pickup_date, '%H:%i') as jam_pickup,
											airwaybill_kode, 
											case when komoditi='1' then 'Paket' else 'Dokumen' end as komoditi, 
											x_service(service) as service, 
											nama_pengirim, 
											loc_code, 
											pid,
											x_namakustomer(id_kustomer) as namakustomer, 
											nama_penerima, 
											case when inbound='Y' then 'INBOUND' when inbound='N' then 'OUTBOUND' else '' end as inbound, 
											perusahaan_pengirim,
											lokasi_asal, 
											if(id_kota_asal>0,kota_asal,x_namakotabesar(lokasi_asal)) as lokasi_asal, 
											alamat_pengirim, 
											perusahaan_penerima,
											lokasi_tujuan, 
											if(id_kota_tujuan>0,kota_tujuan,x_namakotabesar(lokasi_tujuan)) as lokasi_tujuan, 
											jenis_barang,
											alamat_penerima, 
											delivery_name, 
											delivery_date, 
											SUBSTRING_INDEX(ukuran_barang,'x',1) as length, 
											SUBSTRING_INDEX(SUBSTRING_INDEX(ukuran_barang,'x',2),'x',-1) as width, 
											SUBSTRING_INDEX(ukuran_barang,'x',-1) as height, 
											if(berat_aktual is null or berat_aktual < 1,  berat_barang, berat_aktual) as berat_aktual, 
											case when packing_kayu='1' then 'Packing Oleh Pemilik' when packing_kayu='2' then 'Packing Kayu Oleh Cesco Log.' when packing_kayu='3' then 'Non Packing Kayu Oleh Cesco Log.' end as packing_kayu, 
											asuransi ,
								status FROM pengiriman where status !=7 and x_inter(id_kota_asal) = 'Y' and x_inter(id_kota_tujuan) !='Y'
 and tanggal between '$dari' and '$sampai' and id_kustomer='".$u['id_kustomer']."' and loc_code in($u[loc_code_otomatis])";
								}elseif($tipe=="2"){
									$cari = "SELECT 
									id_pengiriman, 
											date(tanggal) as tanggal, 
											date(pickup_date) as pickup_date,
											TIME_FORMAT(pickup_date, '%H:%i') as jam_pickup,
											airwaybill_kode, 
											case when komoditi='1' then 'Paket' else 'Dokumen' end as komoditi, 
											x_service(service) as service, 
											nama_pengirim, 
											loc_code, 
											pid,
											x_namakustomer(id_kustomer) as namakustomer, 
											nama_penerima, 
											case when inbound='Y' then 'INBOUND' when inbound='N' then 'OUTBOUND' else '' end as inbound, 
											perusahaan_pengirim,
											lokasi_asal, 
											if(id_kota_asal>0,kota_asal,x_namakotabesar(lokasi_asal)) as lokasi_asal, 
											alamat_pengirim, 
											perusahaan_penerima,
											lokasi_tujuan, 
											if(id_kota_tujuan>0,kota_tujuan,x_namakotabesar(lokasi_tujuan)) as lokasi_tujuan, 
											jenis_barang,
											alamat_penerima, 
											delivery_name, 
											delivery_date, 
											SUBSTRING_INDEX(ukuran_barang,'x',1) as length, 
											SUBSTRING_INDEX(SUBSTRING_INDEX(ukuran_barang,'x',2),'x',-1) as width, 
											SUBSTRING_INDEX(ukuran_barang,'x',-1) as height, 
											if(berat_aktual is null or berat_aktual < 1,  berat_barang, berat_aktual) as berat_aktual, 
											case when packing_kayu='1' then 'Packing Oleh Pemilik' when packing_kayu='2' then 'Packing Kayu Oleh Cesco Log.' when packing_kayu='3' then 'Non Packing Kayu Oleh Cesco Log.' end as packing_kayu, 
											asuransi ,
								status FROM pengiriman where status !=7   and tanggal between '$dari' and '$sampai' and x_inter(id_kota_asal) != 'Y' and x_inter(id_kota_tujuan) ='Y' and id_kustomer='".$u['id_kustomer']."' and loc_code in($u[loc_code_otomatis])";
								}else{
									 $cari = "SELECT 
									id_pengiriman,
									 airwaybill_kode,
									 tanggal, 
									 DATE_FORMAT(created_date,'%H:%i') as jam, 
									 date(pickup_date) as pickup_date,
									TIME_FORMAT(pickup_date, '%H:%i') as jam_pickup,
									 case when komoditi='1' then 'Paket' else 'Dokumen' end as komoditi,
									 jenis_barang,
									 perusahaan_pengirim,
									 x_namakustomer(id_kustomer) as namakustomer, 
									 alamat_pengirim,
									 nama_pengirim,id_pengiriman, 
											date(tanggal) as tanggal, 
											airwaybill_kode, 
											case when komoditi='1' then 'Paket' else 'Dokumen' end as komoditi, 
											x_service(service) as service, 
											nama_pengirim, 
											loc_code, 
											pid,
											x_namakustomer(id_kustomer) as namakustomer, 
											nama_penerima, 
											case when inbound='Y' then 'INBOUND' when inbound='N' then 'OUTBOUND' else '' end as inbound, 
											perusahaan_pengirim,
											lokasi_asal, 
											if(id_kota_asal>0,kota_asal,x_namakotabesar(lokasi_asal)) as lokasi_asal, 
											alamat_pengirim, 
											perusahaan_penerima,
											lokasi_tujuan, 
											if(id_kota_tujuan>0,kota_tujuan,x_namakotabesar(lokasi_tujuan)) as lokasi_tujuan, 
											jenis_barang,
											alamat_penerima, 
											delivery_name, 
											delivery_date, 
											SUBSTRING_INDEX(ukuran_barang,'x',1) as length, 
											SUBSTRING_INDEX(SUBSTRING_INDEX(ukuran_barang,'x',2),'x',-1) as width, 
											SUBSTRING_INDEX(ukuran_barang,'x',-1) as height, 
											if(berat_aktual is null or berat_aktual < 1,  berat_barang, berat_aktual) as berat_aktual, 
											case when packing_kayu='1' then 'Packing Oleh Pemilik' when packing_kayu='2' then 'Packing Kayu Oleh Cesco Log.' when packing_kayu='3' then 'Non Packing Kayu Oleh Cesco Log.' end as packing_kayu, 
											asuransi,  
									 status FROM pengiriman where status !=7 and tanggal between '$dari' and '$sampai' and id_kustomer='".$u['id_kustomer']."' and loc_code in($u[loc_code_otomatis])";
								}

							}
						}
						//echo $cari;
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
								<td width="20px">'.$no.'</td>
								<td width="20px">'.$row['tanggal'].'</td>
								<td width="20px">'.$row['pickup_date'].'</td>
								<td width="20px">'.$row['jam_pickup'].'</td>
								<td width="20px">'.$row['airwaybill_kode'].'</td>
								<td width="20px">'.strtoupper($row['komoditi']).', '.$row['jenis_barang'].'</td>
								<td width="50px">'.strtoupper($row['service']).'</td>
								<td width="20px">'.strtoupper($row['namakustomer']).'</td>
								<td width="20px">'.strtoupper($row['loc_code']). ($row['pid'] != '' ? ' , PID = '. $row['pid'] : '').'</td>
								<td width="20px">'.strtoupper($row['nama_pengirim']).'</td>
								<td width="20px">'.strtoupper($row['perusahaan_pengirim']).'</td>
								<td width="20px">'.strtoupper($lokasi_asal).'</td>
								<td width="20px">'.strtoupper($row['alamat_pengirim']).'</td>
								
								<td width="20px">'.strtoupper($row['perusahaan_penerima']).'</td>
								<td width="20px">'.strtoupper($row['nama_penerima']).'</td>										
								<td width="20px">'.strtoupper($lokasi_tujuan).'</td>								
								<td width="20px">'.strtoupper($row['alamat_penerima']).'</td>
								<td width="20px">'.strtoupper($row['delivery_name']).'</td>
								<td width="20px">'.$row['delivery_date'].'</td>
								
								<td width="20px">'.$row['berat_aktual'].'</td>
								<td>'.$row['packing_kayu'].'</td>
								<td>'.$row['asuransi'].'</td>		';
								
								$key = 'asdf$1011';
								$stringawal = $row["id_pengiriman"];
								$id = base64_encrypt($stringawal,$key);
								
								echo '						
                           <td><button class="btn btn-xs btn-success" onClick="window.open(\'../reportsnew/airwaybillnew.php?id='.$id.'\',\'mywindow\',\'menubar=1,resizable=1,width=800,height=600\');"><i class="fa fa-print"></i></button>
                           ';
                           if($u['id_kustomer'] == 106){
                           /*	echo '
                           	<button class="btn btn-xs btn-success" onClick="window.open(\'reports/airwaybill_asus.php?id='.$id.'\',\'mywindow\',\'menubar=1,resizable=1,width=800,height=600\');"><i class="fa fa-print"></i></button>';*/
                           }
                           echo '
                           </center></td>


							</tr>';
						$no++;
						}?>

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
			<h3 class="box-title">Airwaybill</h3>
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
				<?php
				$options = "";

				// Jika user type "all" → tampilkan semua
				if ($u['type'] == 'all') {
				    $options .= '<option value="0">All</option>';
				    $options .= '<option value="1">Outbound</option>';
				    $options .= '<option value="2">Inbound</option>';
				}
				// Jika user type outbound → hanya outbound
				elseif ($u['type'] == 'outbound') {
				    $options .= '<option value="1">Outbound</option>';
				}
				// Jika user type inbound → hanya inbound
				elseif ($u['type'] == 'inbound') {
				    $options .= '<option value="2">Inbound</option>';
				}
				?>
				<div class="col-md-5">
				    <div class="form-group">
				        <label class="col-sm-4 control-label full-right">Booking Type</label>
				        <div class="col-sm-8">
				            <select name="tipe" class="form-control select2">
				                <?= $options ?>
				            </select>
				        </div>
				    </div>
				</div>
			</div><!-- /.row -->
		</div><!-- /.box-body -->
        <div class="box-footer">
			<button type="submit" class="btn btn-primary pull-right"> <i class="fa fa-search"></i> View</button>
		</div>
		<script>
			$(document).ready(function() {
    		$('#airwaybill-cust').DataTable( {
       		 dom: 'Bfrtip',
        		buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
      		  ]
   			 } );
			} );
		</script>
	</div><!-- /.box -->
	</form>
	<?php
	}
	?>

</section><!-- /.content -->