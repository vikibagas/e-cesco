<section class="content-header">
	<h1>Invoice <small>Agent</small></h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-home"></i> Home</a></li>
		<li><a href="#">Finance</a></li>
		<li><a href="#">Invoice</a></li>
		<li class="active">Agent</li>
	</ol>
</section>
<?php
if(!isset($_SESSION))
{
	session_start();
}

if (empty($_SESSION['username']) AND empty($_SESSION['password']))
{
	header('location:../../application/login.php');
}
else
{
	//cek hak akses user
	if($u['level']=='admin' || $u['id_user']== 5 || $u['id_user']== 9 || $u['level']=='agent' || $u['id_user']== 8  || $u['level']=='finance')
	{
		$aksi	= "module/mod_finance_invoice-agent/invoice-agent_action.php";	
		$act	= !empty($_GET['act']) ? $_GET['act'] : '';
		
		switch($act)
		{
			
			default:
			echo'
			<section class="content">';
				/*if (isset($_SESSION['pesan']) && $_SESSION['pesan'] == 'success') 
				{
					echo'
					<div class="callout callout-success pesan">
						<h4><i class="icon fa fa-check"></i> Success!</h4>
						<p>Draft invoice has been saved</p>
					</div>';
				}
				elseif(isset($_SESSION['pesan']) && $_SESSION['pesan'] == 'failed')
				{
					echo'
					<div class="callout callout-warning pesan">
						<h4><i class="icon fa fa-warning"></i> Failed!</h4>
						<p>Draft invoice not successfully saved</p>
					</div>';
				}
				elseif(isset($_SESSION['pesan']) && $_SESSION['pesan'] == 'duplicate')
				{
					echo'
					<div class="callout callout-danger pesan">
						<h4><i class="icon fa fa-warning"></i> Failed!</h4>
						<p>Data sudah ada</p>
					</div>';
				}
				elseif(isset($_SESSION['pesan']) && $_SESSION['pesan'] == 'delete')
				{
					echo'
					<div class="callout callout-success pesan">
						<h4><i class="icon fa fa-check"></i> Success!</h4>
						<p>Delete Success</p>
					</div>';
				}
				$_SESSION['pesan'] = '';*/
				echo'
				<div class="row">
					<div class="col-md-12">
						<div class="box box-primary">
							<div class="box-header">
								<div class="row">
									<div class="col-md-6">
										';
										if($u['level']=='admin' || $u['level']=='finance' || $u['id_user']== 8){
											/*echo '
											<div class="btn-group">
												<button class="btn btn-sm btn-default" onclick="window.location.href = \'?module=invoice-agent&act=create\';"><i class="fa fa-plus text-green"></i> Create</button>
											</div>';*/
										}
										echo '	
									</div>
									<div class="col-md-6">
									</div>
								</div>
							</div>
							<div class="box-body">

								<table id="InvoiceAgent" class="table table-bordered table-striped table-hover" width="100%" cellspacing="0" style="white-space:nowrap;">
									<thead>
										<tr>											
											<th>Tanggal Invoice</th>
											<th>Nama Agent</th>																								
											<th width="50"><center>#</center></th>
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
			
			case "create":
			
			echo'
			<section class="content">
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">Create</h3>
						<div class="box-tools pull-right">
							<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
							<button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
						</div>
					</div>
					<form class="form-horizontal" method="post" action="'.$aksi.'?module=invoice-agent&act=create" autocomplete="off">
					<div class="box-body">
						<div class="form-group">
							<label class="col-md-3 control-label">Tanggal <span class="required">* </span></label>
							<div class="col-md-6">
								<div class="input-group">
									<div class="input-group-addon">
										<i class="fa fa-calendar"></i>
									</div>
									<input type="text" class="form-control" id="tanggal" name="tanggal" value="'.$setdate.'" readonly required>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label">Date Range <span class="required">* </span></label>
							<div class="col-md-6">
								<div class="input-group">
								<div class="input-group-addon">
									<i class="fa fa-calendar"></i>
								</div>
								<input type="text" class="form-control" id="daterange" name="tgl_airwaybill" value="" required>
								<input type="hidden" value="" id="tgl_dr">
								<input type="hidden" value="" id="tgl_sampai">
								<input type="hidden" value="" id="id_agent">
							</div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label">Agent <span class="required">* </span></label>
							<div class="col-md-6">
								<select name="id_agent" class="form-control select2" required>
									';
									$query=mysqli_query($con,"select id_cabang, concat(nama_cabang,' ','(',alamat1,')') as nama_cabang from cabang where cabang_cesco = 'N' order by concat(nama_cabang,' ','(',alamat1,')')");
									while($r=mysqli_fetch_assoc($query)){
										if($r['id_cabang']==$id_cabang){
											echo'
											<option value="'.$r['id_cabang'].'" selected>'.$r['nama_cabang'].'</option>';
										}else{
											echo'
											<option value="'.$r['id_cabang'].'">'.$r['nama_cabang'].'</option>';
										}
									}
								echo'
								</select>
							</div>
						</div>

					</div>
					<div class="box-footer">
						<button type="button" class="btn btn-default" onclick="window.location.href = \'?module=invoice-agent\';">Cancel</button>
						<button type="submit" class="btn btn-primary pull-right"><i class="fa fa-send"></i> Process</button>
					</div>
					</form>
				</div>
			</section>';
				
			break;
				
			case "update":
			
			$key		= "asdf$1011";
			$encrypt	= "$_GET[id]";
			$id 		= base64_decrypt($encrypt,$key);
			//echo $id;
			
			$pieces = explode('-', $id);
			$agent_by = $pieces['0'];
			$month = implode('-', array_slice($pieces, 1, 3));

			$query 		= $mysqli->query("select * from cabang where id_cabang=$agent_by
				
				");
			$r= $query->fetch_assoc();
			$tanggal = $month;
			
			$id_inv = $r['id'];			
			
			echo'
			<section class="invoice">
      			
				<div class="row invoice-info">
					<div class="col-sm-8 invoice-col">						
						';
												
							echo'
							<address>
								<strong>'.$r['nama_cabang'].','.$r['alamat1'].'</strong><br>
	            				'.$r['alamat2'].'<br>	            				
	            			Phone: '.$r['telp'].'
	          				</address>';          				
          				echo'
        			</div>
        			<div class="col-sm-4 invoice-col">          				
          				<b>Tanggal: </b><span id="edit-duedate">'.$month.'</span> 
        			</div>
				</div>
      
	  			<div class="row">
        			<div class="box-body">
        			<form action="'.$aksi.'?module=invoice-agent&act=kunci-harga" id="kunciform" method="POST"></form>  			
						<table id="report" class="table table-striped table-bordered table-hover " width="100%" cellspacing="0" style="white-space:nowrap;">
						<thead>
							<tr>
									<th rowspan="2">Booking</th>
									<th rowspan="2">Airwaybill</th>
									<th rowspan="2">Komoditi</th>
									<th rowspan="2">Service Type</th>
									<!--<th rowspan="2">Requestor</th>-->
									<th rowspan="2">Customers Name</th>
									<th rowspan="2">Lockcode</th>
									<th rowspan="2">Consignee</th>
									<th colspan="3"><center>Area</center></th>
									<th colspan="3"><center>Penerima</center></th>
									<th rowspan="2">Alasan(Jika) Terlambat</th>
									<th rowspan="2">Berat</th>
									<th rowspan="2">Harga</th>
									<th rowspan="2">Min. Kg</th>
									<th rowspan="2">Packing</th>
									<th rowspan="2">Add Charge</th>
									<th rowspan="2">Detail Add Charge</th>
									<th rowspan="2">Total</th>
									<th rowspan="2">Paid/Reject</th>									
								</tr>	
								<tr>
									<th>Kota Asal</th>
									<th>Kota Tujuan</th>
									<th width="800px;">Alamat Lengkap Tujuan</th>
									<th>Nama</th>
									<th>Tanggal</th>
									<th>Lead time</th>																
								</tr>
						</thead>
						<tbody>';
						/*if ($r['loc_code'] != ''  and $r['loc_code'] != 'all'){
							$qloc = 'and a.loc_code ="'.$r['loc_code'].'"';
						}						
						else{
							$qloc = '';
						}*/
					//echo $r['id'];	

						//cadangan
						/*$cari = "
							SELECT 
								id_pengiriman, 
                                date(pickup_date) as tanggal, 
                                airwaybill_kode, 
                                case when a.komoditi='1' then 'Paket' else 'Dokumen' end as komoditi, 
                                x_service(service) as service, 
                                case when a.service=5 then 3 else a.service end as srv,
                                nama_pengirim, 
                                loc_code, 
                                x_namakustomer(id_kustomer) as namakustomer, 
                                nama_penerima, 
                                lokasi_asal, 
                                x_namakotabesar(lokasi_asal) as lokasi_asal, 
                                lokasi_tujuan, 
                                x_namakotabesar(lokasi_tujuan) as lokasi_tujuan, 
                                alamat_penerima, 
                                delivery_name, 
                                delivery_date, 
                                if(delivery_date is null,'-', datediff(delivery_date, date(pickup_date))) as selisih_hari,
                                SUBSTRING_INDEX(ukuran_barang,'x',1) as length, SUBSTRING_INDEX(SUBSTRING_INDEX(ukuran_barang,'x',2),'x',-1) as width, SUBSTRING_INDEX(ukuran_barang,'x',-1) as height, 
                                if(berat_aktual is null or berat_aktual < 1,  berat_barang, berat_aktual) as berat_aktual,
                                berat_aktual as brt_akt,
                            	berat_barang as brt_brg,
                                ifnull(d.price,e.price) as price, 
                                ifnull(d.min_kg,e.min_kg) as min_kg, 
                                a.biaya_packing_agent, 
                                a.biaya_tambahan_agent, 
                                a.keterangan_biaya, 
                                if(a.paid_reject = '1',if(if(berat_aktual is null or berat_aktual < 1,  berat_barang, berat_aktual) < ifnull(d.min_kg,e.min_kg), (ifnull(d.min_kg,e.min_kg)*ifnull(d.price,e.price))+a.biaya_packing_agent+a.biaya_tambahan_agent , (if(berat_aktual is null or berat_aktual < 1,  berat_barang, berat_aktual)*ifnull(d.price,e.price))+a.biaya_packing_agent+a.biaya_tambahan_agent ),0) as total, 
                                d.rateagent_id,
                            	a.paid_reject,
                            	a.reject_reason
							FROM pengiriman a 
							INNER JOIN kota b ON b.id_kota=a.lokasi_tujuan 
							OR b.id_kota=a.lokasi_asal 
							INNER JOIN cabang c ON c.id_kota=b.id_kota OR b.hub=c.id_kota 
						LEFT JOIN rate_agent d ON d.id_cabang=CASE WHEN x_intercity(a.lokasi_asal)='Y' THEN '39' ELSE c.id_cabang END AND d.id_kota=a.lokasi_tujuan AND d.id_pelayanan=CASE WHEN a.service > 3 THEN 3 else a.service end AND d.komoditi=a.komoditi 
							LEFT JOIN rate_agent_inbound e ON e.id_kota=a.lokasi_asal  and e.id_pelayanan=CASE WHEN a.service > 3 THEN 3 else a.service end and e.komoditi=a.komoditi 
							WHERE c.id_cabang=$agent_by AND a.tanggal BETWEEN '$r[dari]' AND '$r[sampai]' AND ((x_intercity(a.lokasi_asal)='Y' AND x_intercity(a.lokasi_tujuan)!='Y') OR 
(x_intercity(a.lokasi_asal)!='Y' and x_intercity(a.lokasi_tujuan)='Y')) 
							group by a.id_pengiriman";*/
                        if($r['id_agent'] == 49 || $r['id_agent'] == 58 || $r['id_agent'] == 49 || $r['id_agent'] == 21 || $r['id_agent'] == 22 || $r['id_agent'] == 42){
                         $cari = "
							SELECT 
								id_pengiriman, 
                                date(tanggal) as tanggal, 
                                airwaybill_kode, 
                                case when a.komoditi='1' then 'Paket' else 'Dokumen' end as komoditi, 
                                x_service(service) as service, 
                                case when a.service=5 then 3 else a.service end as srv,
                                nama_pengirim, 
                                loc_code, 
                                x_namakustomer(id_kustomer) as namakustomer, 
                                nama_penerima, 
                                a.lokasi_asal as id_asal, 
                                a.lokasi_tujuan as id_tujuan, 
                                x_namakotabesar(lokasi_asal) as lokasi_asal, 
                                lokasi_tujuan,
                                b.id_kota,
                                b.intercity as kota_in, 
                                x_namakotabesar(lokasi_tujuan) as lokasi_tujuan, 
                                alamat_penerima, 
                                delivery_name, 
                                delivery_date, 
                                alasan_terlambat,
                                if(delivery_date is null,'-', datediff(delivery_date, date(pickup_date))) as selisih_hari,
                                SUBSTRING_INDEX(ukuran_barang,'x',1) as length, SUBSTRING_INDEX(SUBSTRING_INDEX(ukuran_barang,'x',2),'x',-1) as width, SUBSTRING_INDEX(ukuran_barang,'x',-1) as height, 
                                 CASE 
										WHEN airwaybill_kode LIKE '001-%' THEN 0
										WHEN airwaybill_kode LIKE '002-%' THEN 1
									end as intercity,
								if( (select intercity) = 1,
										ceiling((select length) *  (select width) * (select height)/4000)
									,
										if (a.service = 5,
											ceiling((select length) *  (select width) * (select height)/4000)
										,
											ceiling((select length) *  (select width) * (select height)/6000)
										)
									) as volume,
								if(berat_aktual is null or berat_aktual < 1,  berat_barang, berat_aktual) as berat_real, 
                                if(berat_aktual is null or berat_aktual < 1,  berat_barang, berat_aktual) as berat_aktual,
                                berat_aktual as brt_akt,
                            	berat_barang as brt_brg,
                            	kunci_berat_agent,
                                ifnull(d.price,ifnull(e.price,f.price)) as price, 
                                ifnull(d.price,ifnull(e.price,f.price)) as priceee,
                                ifnull(  kunci_hargaperkg_agent ,  (select priceee) ) as pricee, 

                                ifnull(d.min_kg,ifnull(e.min_kg,f.min_kg)) as min_kg,
                                ifnull(d.min_kg,ifnull(e.min_kg,f.min_kg)) as min_kggg, 
                                ifnull(  kunci_minkg_agent ,  (select min_kggg) ) as min_kgg, 

                                 if ((select berat_real) >= (select volume), (select berat_real), (select volume) ) as berat,

									if ((select min_kgg) >= (select berat_real), (select min_kgg), (select berat_real) ) as berat_finishh,

									ifnull(  kunci_berat_agent ,  (select berat_finishh) ) as berat_finish, 

									ifnull(  kunci_berat_agent ,  (select berat_real) ) as kunci_berat, 

                                a.biaya_packing_agent, 
                                a.biaya_tambahan_agent, 
                                a.keterangan_biaya, 
                                if(a.paid_reject = '1',if(if(berat_aktual is null or berat_aktual < 1,  berat_barang, berat_aktual) < (select min_kgg), ((select min_kgg)*(select pricee))+a.biaya_packing_agent+a.biaya_tambahan_agent , (if(berat_aktual is null or berat_aktual < 1,  berat_barang, berat_aktual)*(select pricee))+a.biaya_packing_agent+a.biaya_tambahan_agent ),0) as total, 

                                ((select berat_finish) * (select pricee))+a.biaya_packing_agent+a.biaya_tambahan_agent as new_totall ,

                                ifnull(  kunci_total_agent ,  (select new_totall) ) as new_total, 

                                d.rateagent_id,
                                ifnull(d.rateagent_id,ifnull(e.rateagentinbound_id,f.rate_id)) as idrate,
                                d.rateagent_id as idrate_out,
                            	e.rateagentinbound_id as idrate_in,	
                            	f.rate_id as idrate_cross,
                            	CASE WHEN x_intercity(a.lokasi_asal)='Y' THEN '39' ELSE c.id_cabang END as cabang_asal,
                            	a.paid_reject,
                            	a.reject_reason
							FROM pengiriman a 
							INNER JOIN kota b ON b.id_kota=a.lokasi_tujuan 
							OR b.id_kota=a.lokasi_asal 
							INNER JOIN cabang c ON c.id_kota=b.id_kota OR b.hub=c.id_kota 
						LEFT JOIN rate_agent d ON d.id_cabang=CASE WHEN x_intercity(a.lokasi_asal)='Y' THEN '39' ELSE c.id_cabang END AND d.id_kota=a.lokasi_tujuan AND d.id_pelayanan=CASE WHEN a.service > 3 THEN 3 else a.service end 

							LEFT JOIN rate_agent_inbound e ON e.id_kota=a.lokasi_asal  and e.id_pelayanan=CASE WHEN a.service > 3 THEN 3 else a.service end and e.id_cabang=CASE WHEN x_intercity(a.lokasi_tujuan)='Y' THEN 0 else 99 end

							LEFT JOIN rate_agent_cross f ON f.id_kota_asal=CASE WHEN x_intercity(a.lokasi_asal)!='Y' THEN a.lokasi_asal ELSE 0 END AND f.id_kota_tujuan=CASE WHEN x_intercity(a.lokasi_tujuan)!='Y' THEN a.lokasi_tujuan ELSE 0 END AND d.id_pelayanan=CASE WHEN a.service > 3 THEN 3 else a.service end 

							WHERE (c.id_cabang=$agent_by AND a.tanggal BETWEEN '$month-01' AND '$month-31' and service != 6 and a.status != 7 ) and ( pickup_by_agent = $agent_by or delivery_by_agent = $agent_by)
							";
						
						}
						else{
						  $cari = "
							SELECT 
								id_pengiriman, 
                                date(tanggal) as tanggal, 
                                airwaybill_kode, 
                                case when a.komoditi='1' then 'Paket' else 'Dokumen' end as komoditi, 
                                x_service(service) as service, 
                                case when a.service=5 then 3 else a.service end as srv,
                                nama_pengirim, 
                                loc_code, 
                                x_namakustomer(id_kustomer) as namakustomer, 
                                nama_penerima, 
                                a.lokasi_asal as id_asal, 
                                a.lokasi_tujuan as id_tujuan, 
                                x_namakotabesar(lokasi_asal) as lokasi_asal, 
                                lokasi_tujuan,
                                b.id_kota,
                                b.intercity as kota_in, 
                                x_namakotabesar(lokasi_tujuan) as lokasi_tujuan, 
                                alamat_penerima, 
                                delivery_name, 
                                delivery_date, 
                                pickup_by_agent,
                                alasan_terlambat,
                                if(delivery_date is null,'-', datediff(delivery_date, date(pickup_date))) as selisih_hari,
                                SUBSTRING_INDEX(ukuran_barang,'x',1) as length, SUBSTRING_INDEX(SUBSTRING_INDEX(ukuran_barang,'x',2),'x',-1) as width, SUBSTRING_INDEX(ukuran_barang,'x',-1) as height, 
                                 CASE 
										WHEN airwaybill_kode LIKE '001-%' THEN 0
										WHEN airwaybill_kode LIKE '002-%' THEN 1
									end as intercity,
								if( (select intercity) = 1,
										ceiling((select length) *  (select width) * (select height)/4000)
									,
										if (a.service = 5,
											ceiling((select length) *  (select width) * (select height)/4000)
										,
											ceiling((select length) *  (select width) * (select height)/6000)
										)
									) as volume,
								if(berat_aktual is null or berat_aktual < 1,  berat_barang, berat_aktual) as berat_real, 
                                if(berat_aktual is null or berat_aktual < 1,  berat_barang, berat_aktual) as berat_aktual,
                                berat_aktual as brt_akt,
                            	berat_barang as brt_brg,
                            	c.id_cabang as cabang_id,
                            	kunci_berat_agent,
                                ifnull(d.price,ifnull(e.price,f.price)) as price, 
                                ifnull(d.price,ifnull(e.price,f.price)) as priceee,
                                ifnull(  kunci_hargaperkg_agent ,  (select priceee) ) as pricee, 

                                ifnull(d.min_kg,ifnull(e.min_kg,f.min_kg)) as min_kg,
                                ifnull(d.min_kg,ifnull(e.min_kg,f.min_kg)) as min_kggg, 
                                ifnull(  kunci_minkg_agent ,  (select min_kggg) ) as min_kgg, 

                                 if ((select berat_real) >= (select volume), (select berat_real), (select volume) ) as berat,

									if ((select min_kgg) >= (select berat_real), (select min_kgg), (select berat_real) ) as berat_finishh,

									ifnull(  kunci_berat_agent ,  (select berat_finishh) ) as berat_finish, 

									ifnull(  kunci_berat_agent ,  (select berat_real) ) as kunci_berat,

                                a.biaya_packing_agent, 
                                a.biaya_tambahan_agent, 
                                a.keterangan_biaya, 
                                if(a.paid_reject = '1',if(if(berat_aktual is null or berat_aktual < 1,  berat_barang, berat_aktual) < (select min_kgg), ((select min_kgg)*(select pricee))+a.biaya_packing_agent+a.biaya_tambahan_agent , (if(berat_aktual is null or berat_aktual < 1,  berat_barang, berat_aktual)*(select pricee))+a.biaya_packing_agent+a.biaya_tambahan_agent ),0) as total, 

                                ((select berat_finish) * (select pricee))+a.biaya_packing_agent+a.biaya_tambahan_agent as new_totall ,

                                ifnull(  kunci_total_agent ,  (select new_totall) ) as new_total,
                                d.rateagent_id,
                                ifnull(d.rateagent_id,ifnull(e.rateagentinbound_id,f.rate_id)) as idrate,
                                d.rateagent_id as idrate_out,
                            	e.rateagentinbound_id as idrate_in,	
                            	f.rate_id as idrate_cross,
                            	CASE WHEN x_intercity(a.lokasi_asal)='Y' THEN '39' ELSE c.id_cabang END as cabang_asal,
                            	a.paid_reject,
                            	a.reject_reason
							FROM pengiriman a 
							LEFT JOIN kota b ON b.id_kota=a.lokasi_tujuan 
							OR b.id_kota=a.lokasi_asal 
							LEFT JOIN cabang c ON c.id_cabang=a.pickup_by_agent OR c.id_cabang=a.delivery_by_agent
						
						LEFT JOIN rate_agent d ON d.id_cabang=CASE WHEN x_intercity(a.lokasi_asal)='Y' THEN '39' ELSE c.id_cabang END AND d.id_kota=a.lokasi_tujuan AND d.id_pelayanan=CASE WHEN a.service > 3 THEN 3 else a.service end 

							LEFT JOIN rate_agent_inbound e ON e.id_kota=a.lokasi_asal  and e.id_pelayanan=CASE WHEN a.service > 3 THEN 3 else a.service end and e.id_cabang=CASE WHEN x_intercity(a.lokasi_tujuan)='Y' THEN 0 else 99 end

							LEFT JOIN rate_agent_cross f ON case when a.pickup_by_agent=$agent_by THEN f.id_kota_asal else f.id_kota_tujuan END  = case when a.pickup_by_agent=$agent_by THEN a.lokasi_asal else a.lokasi_tujuan END  AND f.id_pelayanan=a.service 


							WHERE ( c.id_cabang =$agent_by and   a.tanggal BETWEEN '$month-01' AND '$month-31' and service != 6 and a.status != 7 ) and ( pickup_by_agent = $agent_by or delivery_by_agent = $agent_by) group by a.id_pengiriman
							";

						}
						//echo $cari;

						$sql = $mysqli->query($cari);
						$row_count= $sql->num_rows;
						$sum_total = 0;
						while($row = $sql->fetch_assoc()){
							$new_total = ($row['paid_reject'] == 1 ? $row['new_total'] : 0);
							$sum_total = $sum_total + $new_total;
							
							$key = "asdf$1011";
							$stringawal = $row["id_pengiriman"];
							$id = base64_encrypt($stringawal,$key);
							$volume = ($r['length']*$r['width']*$r['height'])/6000;
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
							
							if($row['pricee']>0){

								if($row['idrate_out']>0){
									$price = '<a href="#" class="rate_agent" data-type="text" data-pk="'.$row['idrate_out'].'">'.$row['pricee'].'</a>';
								}elseif($row['idrate_in']>0){
									$price = '<a href="#" class="rate_agent_in" data-type="text" data-pk="'.$row['idrate_in'].'">'.$row['pricee'].'</a>';
								}elseif($row['idrate_cross']>0){
									$price = '<a href="#" class="rate_agent_cross" data-type="text" data-pk="'.$row['idrate_cross'].'">'.$row['pricee'].'</a>';
								}


							}else{
								$price = '<button type="button" class="btn btn-default btn-xs custom_price_insert_agent" data-toggle="modal" data-target="#modal_price_insert_agent" data-id="'.$row['id_pengiriman'].'" id="'.($row['pickup_by_agent'] == $agent_by ? 1 : 0).'"><i class="fa fa-plus-square text-orange"></i></button>';
							}

							if($row['min_kgg']>0){

								if($row['idrate_out']>0){
									$min_kg = '<a href="#" class="min_kg_agent" data-type="text" data-pk="'.$row['idrate_out'].'">'.$row['min_kgg'].'</a>';
								}elseif($row['idrate_in']>0){
									$min_kg = '<a href="#" class="min_kg_agent_in" data-type="text" data-pk="'.$row['idrate_in'].'">'.$row['min_kgg'].'</a>';
								}elseif($row['idrate_cross']>0){
									$min_kg = '<a href="#" class="min_kg_agent_cross" data-type="text" data-pk="'.$row['idrate_cross'].'">'.$row['min_kgg'].'</a>';
								}


							}else{
								$min_kg='-';
							}
							echo'
							<tr>
								<td width="20px">'.$row['tanggal'].'</td>
								<td width="20px">'.$row['airwaybill_kode'].'</td>
								<td width="20px">'.strtoupper($row['komoditi']).'</td>
								<td width="50px">'.strtoupper($row['service']).'</td>
								'; //<td width="20px">'.strtoupper($row['nama_pengirim']).'</td>'
								echo '
								<td width="20px">'.strtoupper($row['namakustomer']).'</td>
								<td width="20px">'.strtoupper($row['loc_code']).'</td>
								<td width="20px">'.strtoupper($row['nama_penerima']).'</td>
								<td width="20px">'.strtoupper($lokasi_asal).'</td>
								<td width="20px">'.strtoupper($lokasi_tujuan).'</td>
								<td width="20px">'.strtoupper($row['alamat_penerima']).'</td>
								<td width="20px">'.strtoupper($row['delivery_name']).'</td>
								<td width="20px">'.$row['delivery_date'].'</td>
								<td width="20px"> '.$row['selisih_hari'].' hari</td>
								
								
								';/*
								<td width="20px" align="right"><a href="#" class="berat_aktual" data-type="text" data-pk="'.$row['id_pengiriman'].'">'.$row['berat_aktual'].'</a></td>
								<td align="right"><a href="#" class="price" data-type="text" data-pk="'.$row['id_pengiriman'].'">'.number_format($row['price']).'</a></td>
								<td align="right" ><a href="#" class="min_kg" data-type="text" data-pk="'.$row['rateagent_id'].'">'.number_format($row['min_kg']).'</a></td>*/echo '
								<td width="20px"> '.$row['alasan_terlambat'].'</td>
								<td width="20px" align="right">
								';
								if ($u['level']=='agent'){
									echo $row['kunci_berat'] ;
								}else{
									echo '<a href="#" class="berat_agent" data-type="text" data-pk="'.$row['id_pengiriman'].'">'.$row['kunci_berat'].'</a>';
								}
								echo '
								</td>
								<td align="right">'.($u['level']=='agent' ? $row['price'] : $price).' id rate cross='. $row['idrate_cross'] .'dan '.$row['priceee'].' </td>
								<td align="right" >'.($u['level']=='agent' ? $row['min_kgg'] : $min_kg).'</td>
								
								';
								if ($u['level']=='agent'){
									echo '
									<td align="right" align="right">'.number_format($row['biaya_packing_agent']).'</td>
									<td align="right" align="right">'.number_format($row['biaya_tambahan_agent']).'</td>
									<td>'.$row['keterangan_biaya'].'</td>
									<td align="right">'.($row['paid_reject'] == 1 ? number_format($row['new_total']) : '-').'</td>
									<td>'.($row[paid_reject] == 1 ? 'paid' : 'rejected, '.$row["reject_reason"]) .'</td>
									
									';
								}
								else{
									echo '
									<td align="right" align="right"><a href="#" class="biaya_packing" data-type="text" data-pk="'.$row['id_pengiriman'].'">'.number_format($row['biaya_packing_agent']).'</a></td>
									<td align="right" align="right"><a href="#" class="biaya_tambahan" data-type="text" data-pk="'.$row['id_pengiriman'].'">'.number_format($row['biaya_tambahan_agent']).'</a></td>
									<td><a href="#" class="keterangan_biaya" data-type="text" data-pk="'.$row['id_pengiriman'].'">'.$row['keterangan_biaya'].'</a></td>
									<td align="right">'.($row['paid_reject'] == 1 ? number_format($row['new_total']) : '-').'</td>
									<td>'.($row[paid_reject] == 1 ? 'paid | <a href="#" id="'.$row[id_pengiriman].'" class="open_reject">reject</a>' : 'rejected, '.$row["reject_reason"]) .'| <a href="#" id="'.$row[id_pengiriman].'" class="open_move">move</a> </td>
									';
								}
								echo '
							</tr>';
						}
						echo'
						</tbody>
					</table
					</div>
				</div>
			
				<div class="row">
        			<div class="col-xs-6">
						<!--
          				<p class="lead">Payment Methods:</p>
						<p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
						</p>
						-->
					</div>
					<div class="col-xs-6">
						<!--<p class="lead">Amount Due</p>-->
						<div class="table-responsive">';
						
					$key		= "asdf$1011";
					$encrypt	= "$_GET[id]";
					$id_invoice	= base64_decrypt($encrypt,$key);						 															

					if($r['id_agent'] == 49 || $r['id_agent'] == 58 || $r['id_agent'] == 49 || $r['id_agent'] == 21 || $r['id_agent'] == 22 || $r['id_agent'] == 42){
						 "SELECT                                 
                                SUM(if(a.paid_reject = '1',if(if(berat_aktual is null or berat_aktual < 1,  berat_barang, berat_aktual) < ifnull(d.min_kg,e.min_kg), (ifnull(d.min_kg,e.min_kg)*ifnull(d.price,e.price))+a.biaya_packing_agent+a.biaya_tambahan_agent , (if(berat_aktual is null or berat_aktual < 1,  berat_barang, berat_aktual)*ifnull(d.price,e.price))+a.biaya_packing_agent+a.biaya_tambahan_agent ),0)) AS total 

                            FROM pengiriman a 
							INNER JOIN kota b ON b.id_kota=a.lokasi_tujuan 
							OR b.id_kota=a.lokasi_asal 
							INNER JOIN cabang c ON c.id_kota=b.id_kota OR b.hub=c.id_kota 
						LEFT JOIN rate_agent d ON d.id_cabang=CASE WHEN x_intercity(a.lokasi_asal)='Y' THEN '39' ELSE c.id_cabang END AND d.id_kota=a.lokasi_tujuan AND d.id_pelayanan=CASE WHEN a.service > 3 THEN 3 else a.service end 
							LEFT JOIN rate_agent_inbound e ON e.id_kota=a.lokasi_asal  and e.id_pelayanan=CASE WHEN a.service > 3 THEN 3 else a.service end 
							WHERE (c.id_cabang=$agent_by AND a.tanggal BETWEEN '$r[dari]' AND '$r[sampai]' and service != 6 and a.status != 7 and paid_reject = '1') and ( pickup_by_agent = $agent_by or delivery_by_agent = $agent_by)
							

							";
						$qtotal = $mysqli->query("SELECT                                 
                                SUM(if(a.paid_reject = '1',if(if(berat_aktual is null or berat_aktual < 1,  berat_barang, berat_aktual) < ifnull(d.min_kg,ifnull(e.min_kg,f.min_kg)), (ifnull(d.min_kg,ifnull(e.min_kg,f.min_kg))*ifnull(d.price,ifnull(e.price,f.price)))+a.biaya_packing_agent+a.biaya_tambahan_agent , (if(berat_aktual is null or berat_aktual < 1,  berat_barang, berat_aktual)*ifnull(d.price,ifnull(e.price,f.price)))+a.biaya_packing_agent+a.biaya_tambahan_agent ),0)) AS total 

                           FROM pengiriman a 
                            LEFT JOIN kota b ON b.id_kota=a.lokasi_tujuan 
                            OR b.id_kota=a.lokasi_asal 
                            LEFT JOIN cabang c ON c.id_kota=b.id_kota OR b.hub=c.id_kota 
                        
                        LEFT JOIN rate_agent d ON d.id_cabang=CASE WHEN x_intercity(a.lokasi_asal)='Y' THEN '39' ELSE c.id_cabang END AND d.id_kota=a.lokasi_tujuan AND d.id_pelayanan=CASE WHEN a.service > 3 THEN 3 else a.service end 

                            LEFT JOIN rate_agent_inbound e ON e.id_kota=a.lokasi_asal  and e.id_pelayanan=CASE WHEN a.service > 3 THEN 3 else a.service end and e.id_cabang=CASE WHEN x_intercity(a.lokasi_tujuan)='Y' THEN 0 else 99 end

                            LEFT JOIN rate_agent_cross f ON f.id_kota_asal=CASE WHEN x_intercity(a.lokasi_asal)!='Y' THEN a.lokasi_asal ELSE 0 END AND f.id_kota_tujuan=CASE WHEN x_intercity(a.lokasi_tujuan)!='Y' THEN a.lokasi_tujuan ELSE 0 END AND f.id_pelayanan=CASE WHEN a.service > 3 THEN 3 else a.service end 
							WHERE (c.id_cabang=$agent_by AND a.tanggal BETWEEN '$r[dari]' AND '$r[sampai]' and service != 6 and a.status != 7 and paid_reject = '1') and ( pickup_by_agent = $agent_by or delivery_by_agent = $agent_by)
							

							");
					}
					else{
						  "SELECT                                 
                                SUM(if(a.paid_reject = '1',if(if(berat_aktual is null or berat_aktual < 1,  berat_barang, berat_aktual) < ifnull(d.min_kg,ifnull(e.min_kg,f.min_kg)), (ifnull(d.min_kg,ifnull(e.min_kg,f.min_kg))*ifnull(d.price,ifnull(e.price,f.price)))+a.biaya_packing_agent+a.biaya_tambahan_agent , (if(berat_aktual is null or berat_aktual < 1,  berat_barang, berat_aktual)*ifnull(d.price,ifnull(e.price,f.price)))+a.biaya_packing_agent+a.biaya_tambahan_agent ),0)) AS total 

                            FROM pengiriman a 
                            LEFT JOIN kota b ON b.id_kota=a.lokasi_tujuan 
                            OR b.id_kota=a.lokasi_asal 
                            LEFT JOIN cabang c ON c.id_kota=b.id_kota OR b.hub=c.id_kota 
                        
                        LEFT JOIN rate_agent d ON d.id_cabang=CASE WHEN x_intercity(a.lokasi_asal)='Y' THEN '39' ELSE c.id_cabang END AND d.id_kota=a.lokasi_tujuan AND d.id_pelayanan=CASE WHEN a.service > 3 THEN 3 else a.service end 

                            LEFT JOIN rate_agent_inbound e ON e.id_kota=a.lokasi_asal  and e.id_pelayanan=CASE WHEN a.service > 3 THEN 3 else a.service end and e.id_cabang=CASE WHEN x_intercity(a.lokasi_tujuan)='Y' THEN 0 else 99 end

                            LEFT JOIN rate_agent_cross f ON f.id_kota_asal=CASE WHEN x_intercity(a.lokasi_asal)!='Y' THEN a.lokasi_asal ELSE 0 END AND f.id_kota_tujuan=CASE WHEN x_intercity(a.lokasi_tujuan)!='Y' THEN a.lokasi_tujuan ELSE 0 END AND f.id_pelayanan=CASE WHEN a.service > 3 THEN 3 else a.service end 

                            WHERE (c.id_cabang=$agent_by AND a.tanggal BETWEEN '$month-01' AND '$month-31' and service != 6 and a.status != 7 ) and ( pickup_by_agent = $agent_by or delivery_by_agent = $agent_by or pickup_by_agent is null or delivery_by_agent is null or pickup_by_agent != $agent_by or delivery_by_agent != $agent_by)
							";

						$qtotal = $mysqli->query("SELECT                                 
                                SUM(if(a.paid_reject = '1',if(if(berat_aktual is null or berat_aktual < 1,  berat_barang, berat_aktual) < ifnull(d.min_kg,ifnull(e.min_kg,f.min_kg)), (ifnull(d.min_kg,ifnull(e.min_kg,f.min_kg))*ifnull(d.price,ifnull(e.price,f.price)))+a.biaya_packing_agent+a.biaya_tambahan_agent , (if(berat_aktual is null or berat_aktual < 1,  berat_barang, berat_aktual)*ifnull(d.price,ifnull(e.price,f.price)))+a.biaya_packing_agent+a.biaya_tambahan_agent ),0)) AS total 

                            FROM pengiriman a 
                            LEFT JOIN kota b ON b.id_kota=a.lokasi_tujuan 
                            OR b.id_kota=a.lokasi_asal 
                            LEFT JOIN cabang c ON c.id_kota=b.id_kota OR b.hub=c.id_kota 
                        
                        LEFT JOIN rate_agent d ON d.id_cabang=CASE WHEN x_intercity(a.lokasi_asal)='Y' THEN '39' ELSE c.id_cabang END AND d.id_kota=a.lokasi_tujuan AND d.id_pelayanan=CASE WHEN a.service > 3 THEN 3 else a.service end 

                            LEFT JOIN rate_agent_inbound e ON e.id_kota=a.lokasi_asal  and e.id_pelayanan=CASE WHEN a.service > 3 THEN 3 else a.service end and e.id_cabang=CASE WHEN x_intercity(a.lokasi_tujuan)='Y' THEN 0 else 99 end

                            LEFT JOIN rate_agent_cross f ON f.id_kota_asal=CASE WHEN x_intercity(a.lokasi_asal)!='Y' THEN a.lokasi_asal ELSE 0 END AND f.id_kota_tujuan=CASE WHEN x_intercity(a.lokasi_tujuan)!='Y' THEN a.lokasi_tujuan ELSE 0 END AND f.id_pelayanan=CASE WHEN a.service > 3 THEN 3 else a.service end 

                            WHERE (c.id_cabang=$agent_by AND a.tanggal BETWEEN '$month-01' AND '$month-31' and service != 6 and a.status != 7 ) and ( pickup_by_agent = $agent_by or delivery_by_agent = $agent_by or pickup_by_agent is null or delivery_by_agent is null or pickup_by_agent != $agent_by or delivery_by_agent != $agent_by)
							

							");
					}
						$st = $qtotal->fetch_assoc();
						
						echo'
							<table class="table">								
              					<tr>
                					<th>Total</th>
                					<td style="text-align:right;">Rp '.number_format($sum_total).'</td>
              					</tr>
            				</table>
          				</div>
        			</div>
				</div>
				
      			<div class="row no-print">
        			<div class="col-xs-12">          				
        			';
$key		= "asdf$1011";
			$encrypt	= "$_GET[id]";
			$id 		= base64_decrypt($encrypt,$key);
			//echo $id;
			
			$pieces = explode('-', $id);
			$agent_by = $pieces['0'];
			$month = implode('-', array_slice($pieces, 1, 3));
        			$query 		= $mysqli->query("select * from cabang where id_cabang=$agent_by
				
				");
        			//echo "select * from cabang where id_cabang=$agent_by";
			$r= $query->fetch_assoc();


			$cek_status = $mysqli->query("SELECT                                 
                               if(a.paid_reject = '1',if(if(berat_aktual is null or berat_aktual < 1,  berat_barang, berat_aktual) < ifnull(d.min_kg,ifnull(e.min_kg,f.min_kg)), (ifnull(d.min_kg,ifnull(e.min_kg,f.min_kg))*ifnull(d.price,ifnull(e.price,f.price)))+a.biaya_packing_agent+a.biaya_tambahan_agent , (if(berat_aktual is null or berat_aktual < 1,  berat_barang, berat_aktual)*ifnull(d.price,ifnull(e.price,f.price)))+a.biaya_packing_agent+a.biaya_tambahan_agent ),0) AS total 

                            FROM pengiriman a 
                            LEFT JOIN kota b ON b.id_kota=a.lokasi_tujuan 
                            OR b.id_kota=a.lokasi_asal 
                            LEFT JOIN cabang c ON c.id_kota=b.id_kota OR b.hub=c.id_kota 
                        
                        LEFT JOIN rate_agent d ON d.id_cabang=CASE WHEN x_intercity(a.lokasi_asal)='Y' THEN '39' ELSE c.id_cabang END AND d.id_kota=a.lokasi_tujuan AND d.id_pelayanan=CASE WHEN a.service > 3 THEN 3 else a.service end 

                            LEFT JOIN rate_agent_inbound e ON e.id_kota=a.lokasi_asal  and e.id_pelayanan=CASE WHEN a.service > 3 THEN 3 else a.service end and e.id_cabang=CASE WHEN x_intercity(a.lokasi_tujuan)='Y' THEN 0 else 99 end

                            LEFT JOIN rate_agent_cross f ON f.id_kota_asal=CASE WHEN x_intercity(a.lokasi_asal)!='Y' THEN a.lokasi_asal ELSE 0 END AND f.id_kota_tujuan=CASE WHEN x_intercity(a.lokasi_tujuan)!='Y' THEN a.lokasi_tujuan ELSE 0 END AND f.id_pelayanan=CASE WHEN a.service > 3 THEN 3 else a.service end 

                            WHERE (c.id_cabang=$agent_by AND a.tanggal BETWEEN '$month-01' AND '$month-31' and service != 6 and a.status != 7 ) and ( pickup_by_agent = $agent_by or delivery_by_agent = $agent_by or pickup_by_agent is null or delivery_by_agent is null or pickup_by_agent != $agent_by or delivery_by_agent != $agent_by)
							

							");

			 "SELECT                                 
                               id_pengiriman,status_inv_agent

                            FROM pengiriman a 
                            LEFT JOIN kota b ON b.id_kota=a.lokasi_tujuan 
                            OR b.id_kota=a.lokasi_asal 
                            LEFT JOIN cabang c ON c.id_kota=b.id_kota OR b.hub=c.id_kota 
                        
                        LEFT JOIN rate_agent d ON d.id_cabang=CASE WHEN x_intercity(a.lokasi_asal)='Y' THEN '39' ELSE c.id_cabang END AND d.id_kota=a.lokasi_tujuan AND d.id_pelayanan=CASE WHEN a.service > 3 THEN 3 else a.service end 

                            LEFT JOIN rate_agent_inbound e ON e.id_kota=a.lokasi_asal  and e.id_pelayanan=CASE WHEN a.service > 3 THEN 3 else a.service end and e.id_cabang=CASE WHEN x_intercity(a.lokasi_tujuan)='Y' THEN 0 else 99 end

                            LEFT JOIN rate_agent_cross f ON f.id_kota_asal=CASE WHEN x_intercity(a.lokasi_asal)!='Y' THEN a.lokasi_asal ELSE 0 END AND f.id_kota_tujuan=CASE WHEN x_intercity(a.lokasi_tujuan)!='Y' THEN a.lokasi_tujuan ELSE 0 END AND f.id_pelayanan=CASE WHEN a.service > 3 THEN 3 else a.service end 

                            WHERE (c.id_cabang=$agent_by AND a.tanggal BETWEEN '$month-01' AND '$month-31' and service != 6 and a.status != 7 ) and ( pickup_by_agent = $agent_by or delivery_by_agent = $agent_by or pickup_by_agent is null or delivery_by_agent is null or pickup_by_agent != $agent_by or delivery_by_agent != $agent_by) and (status_inv_agent is null or status_inv_agent = '')                         
							
							";
			$cek_draft = $mysqli->query("SELECT                                 
                               id_pengiriman,status_inv_agent

                            FROM pengiriman a 
                            LEFT JOIN kota b ON b.id_kota=a.lokasi_tujuan 
                            OR b.id_kota=a.lokasi_asal 
                            LEFT JOIN cabang c ON c.id_kota=b.id_kota OR b.hub=c.id_kota 
                        
                        LEFT JOIN rate_agent d ON d.id_cabang=CASE WHEN x_intercity(a.lokasi_asal)='Y' THEN '39' ELSE c.id_cabang END AND d.id_kota=a.lokasi_tujuan AND d.id_pelayanan=CASE WHEN a.service > 3 THEN 3 else a.service end 

                            LEFT JOIN rate_agent_inbound e ON e.id_kota=a.lokasi_asal  and e.id_pelayanan=CASE WHEN a.service > 3 THEN 3 else a.service end and e.id_cabang=CASE WHEN x_intercity(a.lokasi_tujuan)='Y' THEN 0 else 99 end

                            LEFT JOIN rate_agent_cross f ON f.id_kota_asal=CASE WHEN x_intercity(a.lokasi_asal)!='Y' THEN a.lokasi_asal ELSE 0 END AND f.id_kota_tujuan=CASE WHEN x_intercity(a.lokasi_tujuan)!='Y' THEN a.lokasi_tujuan ELSE 0 END AND f.id_pelayanan=CASE WHEN a.service > 3 THEN 3 else a.service end 

                            WHERE (c.id_cabang=$agent_by AND a.tanggal BETWEEN '$month-01' AND '$month-31' and service != 6 and a.status != 7 ) and ( pickup_by_agent = $agent_by or delivery_by_agent = $agent_by or pickup_by_agent is null or delivery_by_agent is null or pickup_by_agent != $agent_by or delivery_by_agent != $agent_by) and (status_inv_agent is null or status_inv_agent = '')                         
							

							");
        			
						$d = $cek_draft->fetch_assoc();
          				$draft = $cek_draft->num_rows;
        					//tombol print
          				//<a target="_blank" href="reports/inv_agent.php?id_agent='.$agent_by.'&date='.$month.'"><button type="button" class="btn btn-primary pull-right" ><i class="fa fa-file-print-o"></i> Print</button></a> 	

        			echo '
							

							<a target="_blank" href="reports/PHPExcel/excel_create_invagent.php?id_agent='.$agent_by.'&date='.$month.'"><button type="button" class="btn btn-primary pull-right" ><i class="fa fa-file-excel-o"></i> Excel</button></a>

							';

							$cek_st = $mysqli->query("SELECT                                 
                               id_pengiriman,status_inv_agent

                            FROM pengiriman a 
                            LEFT JOIN kota b ON b.id_kota=a.lokasi_tujuan 
                            OR b.id_kota=a.lokasi_asal 
                            LEFT JOIN cabang c ON c.id_kota=b.id_kota OR b.hub=c.id_kota 
                        
                        LEFT JOIN rate_agent d ON d.id_cabang=CASE WHEN x_intercity(a.lokasi_asal)='Y' THEN '39' ELSE c.id_cabang END AND d.id_kota=a.lokasi_tujuan AND d.id_pelayanan=CASE WHEN a.service > 3 THEN 3 else a.service end 

                            LEFT JOIN rate_agent_inbound e ON e.id_kota=a.lokasi_asal  and e.id_pelayanan=CASE WHEN a.service > 3 THEN 3 else a.service end and e.id_cabang=CASE WHEN x_intercity(a.lokasi_tujuan)='Y' THEN 0 else 99 end

                            LEFT JOIN rate_agent_cross f ON f.id_kota_asal=CASE WHEN x_intercity(a.lokasi_asal)!='Y' THEN a.lokasi_asal ELSE 0 END AND f.id_kota_tujuan=CASE WHEN x_intercity(a.lokasi_tujuan)!='Y' THEN a.lokasi_tujuan ELSE 0 END AND f.id_pelayanan=CASE WHEN a.service > 3 THEN 3 else a.service end 

                            WHERE (c.id_cabang=$agent_by AND a.tanggal BETWEEN '$month-01' AND '$month-31' and service != 6 and a.status != 7 ) and ( pickup_by_agent = $agent_by or delivery_by_agent = $agent_by or pickup_by_agent is null or delivery_by_agent is null or pickup_by_agent != $agent_by or delivery_by_agent != $agent_by)
							

							");	
							$c = $cek_st->fetch_assoc();


							$cek_harga = $mysqli->query("SELECT                                 
                               id_pengiriman

                            FROM pengiriman a 
                            LEFT JOIN kota b ON b.id_kota=a.lokasi_tujuan 
                            OR b.id_kota=a.lokasi_asal 
                            LEFT JOIN cabang c ON c.id_kota=b.id_kota OR b.hub=c.id_kota 
                        
                        LEFT JOIN rate_agent d ON d.id_cabang=CASE WHEN x_intercity(a.lokasi_asal)='Y' THEN '39' ELSE c.id_cabang END AND d.id_kota=a.lokasi_tujuan AND d.id_pelayanan=CASE WHEN a.service > 3 THEN 3 else a.service end 

                            LEFT JOIN rate_agent_inbound e ON e.id_kota=a.lokasi_asal  and e.id_pelayanan=CASE WHEN a.service > 3 THEN 3 else a.service end and e.id_cabang=CASE WHEN x_intercity(a.lokasi_tujuan)='Y' THEN 0 else 99 end

                            LEFT JOIN rate_agent_cross f ON f.id_kota_asal=CASE WHEN x_intercity(a.lokasi_asal)!='Y' THEN a.lokasi_asal ELSE 0 END AND f.id_kota_tujuan=CASE WHEN x_intercity(a.lokasi_tujuan)!='Y' THEN a.lokasi_tujuan ELSE 0 END AND f.id_pelayanan=CASE WHEN a.service > 3 THEN 3 else a.service end 

                            WHERE (c.id_cabang=$agent_by AND a.tanggal BETWEEN '$month-01' AND '$month-31' and service != 6 and a.status != 7 ) and (d.price is null and e.price is null and f.price is null) and ( pickup_by_agent = $agent_by or delivery_by_agent = $agent_by or pickup_by_agent is null or delivery_by_agent is null or pickup_by_agent != $agent_by or delivery_by_agent != $agent_by) 
							

							");	
							$h =  $cek_harga->num_rows;
						
							$sql = $mysqli->query($cari);					
							while($row = $sql->fetch_assoc()){
								echo '
								<input type="hidden" name="berat['.$row[id_pengiriman].']" form="kunciform" value="'.$row['berat_real'].'" > 
								<input type="hidden" name="hargaperkg['.$row[id_pengiriman].']" form="kunciform" value="'.$row['pricee'].'" > 
								<input type="hidden" name="minkg['.$row[id_pengiriman].']" form="kunciform" value="'.$row['min_kgg'].'" > 
								<input type="hidden" name="total['.$row[id_pengiriman].']" form="kunciform" value="'.$row['new_total'].'" > 

											';
							}
							if($u['level']!='agent'){          				
							echo '<button type="submit" form="kunciform" class="btn btn-success" id="kunci" Value="Kunci Harga"> Kunci Harga </button>';

						
								echo '	
									<a href="module/mod_finance_invoice-agent/invoice-agent_action.php?module=invoice-agent&act=draft&agent='.$agent_by.'&status='.$c['status_inv_agent'].'&awal='.$month.'-01&akhir='.$month.'-31"><button type="button" class="btn btn-primary" '.($h != 0 ? 'disabled' : '').'><i class="fa fa-key"></i>';

									if($c['status_inv_agent'] == ""){
											echo "klik to draft";
										}
										elseif($c['status_inv_agent'] == 1){
											echo "klik to final";
										}
										elseif($c['status_inv_agent'] == 2){
											echo "final";
										}

									echo $h.' status ='.$c['status_inv_agent'].'</button></a>';
							}


						//$row_cek =mysqli_num_rows($rowcek);	

						//<a href="../e-cesco/module/mod_invoice-customer/invoice-customer_action.php?module=invoice-customer&act=nomor&idinvoice='.$id_inv.'"  class="btn btn-primary pull-right " '.($r['nomor'] == 'DRAFT' ? ($row_cek == 0 ? 'enabled' : 'disabled') : 'disabled' ).'>'.$r['nomor'].$row_count.$row_cek.' Buat No Invoice </a>

 					 echo '         				
        			</div>
      			</div>
    		</section>
    		<div class="clearfix"></div>
			
			<div id="modal_price_insert_agent" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
							<h4 class="modal-title" id="myModalLabel">Buat Harga</h4>
						</div>
						<div class="modal-content">
							<div class="modal-body">
								
							</div>
							
						</div>
					</div>
				</div>
			</div>

			<div id="modal-rejectreason" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
							<h4 class="modal-title" id="myModalLabel">Reject</h4>
						</div>
						<div class="modal-content">
							<div class="modal-body">
								<form action="'.$aksi.'?module=invoice-agent&act=reject" name="form_costing" method="POST">
									<input type="hidden" name="id" value="" id="idpengiriman">
									<input type="text" class="form-control" id="reject_reason" placeholder="reject reason" name="reject_reason" required>
							</div>
							<div class="modal-footer">
									<button class="btn btn-success" type="submit">Confirm</button>
									<button type="reset" class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Cancel</button>
									</div>
								</form>
						</div>
					</div>
				</div>
			</div>

			<div id="modal-movecesco" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
							<h4 class="modal-title" id="myModalLabel">Move </h4>
						</div>
						<div class="modal-content">
							<div class="modal-body">
								<form action="'.$aksi.'?module=invoice-agent&act=move" name="form_costing" method="POST">
									<input type="hidden" name="id" value="" id="idpengiriman">
									<div class="form-group">
										<label class="col-md-3 control-label">Move Date <span class="required">* </span></label>
										<div class="input-group col-md-9">
													<div class="input-group-addon">
														<i class="fa fa-calendar"></i>
													</div>
													<input type="text" class="form-control" id="tanggal" name="tanggal" >
												</div>
									
									<div class="form-group">
										<label class="col-md-3 control-label">Move Agent <span class="required">* </span></label>

										<div class="col-md-9">											
												<select " class="form-control" name="move_agent" >
													';
														$select_agent = mysqli_query($con,"select id_cabang,nama_cabang from cabang");
														while($c=mysqli_fetch_assoc($select_agent)){
															echo '<option value="'.$c['id_cabang'].'" '.($c['id_cabang'] == $agent_by ? 'selected' : '').'> '.$c['nama_cabang'].'</option>';
														}
													echo '
												</select>											
										</div>
									</div>
							</div>
							<div class="modal-footer">
									<button class="btn btn-success" type="submit">Confirm</button>
									<button type="reset" class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Cancel</button>
									</div>
								</form>
						</div>
					</div>
				</div>
			</div>

			<div id="airwaybill_detail" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
							<h4 class="modal-title" id="myModalLabel">Airwaybill Detail</h4>
						</div>
						<div class="modal-content">
							<div class="modal-body"></div>
						</div>
					</div>
				</div>
			</div>
			
			<div id="modal_price_edit" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
							<h4 class="modal-title" id="myModalLabel">Edit Price</h4>
						</div>
						<div class="modal-content">
							<div class="alert alert-warning alert-dismissible">
			                	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
			               		<h4><i class="icon fa fa-warning"></i> Perhatian!</h4>
			                	penambahan harga berlaku untuk semua pengiriman dan customer yang sama.
			              	</div>
							<div class="modal-body"></div>
						</div>
					</div>
				</div>
			</div>			
			<div id="modal_tambah_biaya" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
							<h4 class="modal-title" id="myModalLabel">Tambah biaya invoice</h4>
						</div>
						<div class="modal-content">
							<div class="modal-body">
								<form action="'.$aksi.'?module=invoice-customer&act=tambah-biaya" method="POST">
								<input type="hidden" name="id_invoice" id="idinvoice" class="form-control" value="" />
									<div class="form-group" style="padding-bottom: 25px;">
										<label class="col-md-3 control-label">Detail biaya:</label>
										<div class="col-md-8">
											<input type="text" name="detail_biaya" class="form-control" value="" placeholder="contoh biaya pickup" />
										</div>
									</div>
									<div class="form-group" style="padding-bottom: 25px;">
										<label class="col-md-3 control-label">biaya</label>
										<div class="col-md-8">
											<input type="number" name="biaya" class="form-control" value="0" />
										</div>
									</div>
									<div class="checkbox">
                  						<label>
                    						<input type="checkbox" name="pajak" value"Ya" checked> pajak 1% ?
                  						</label>
                					</div>
									<div class="modal-footer">
										<button class="btn btn-success" type="submit">Tambah</button>
										<button type="reset" class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Cancel</button>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div id="modal_duedate" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
							<h4 class="modal-title" id="myModalLabel">Edit due date</h4>
						</div>
						<div class="modal-content">
							<div class="modal-body">
								<form action="'.$aksi.'?module=invoice-customer&act=edit-duedate" method="POST">
									<input type="hidden" name="id_invoice" class="form-control" value="'.$id_invoice.'" />
									<div class="form-group">
										<label class="col-md-3 control-label">Tanggal <span class="required">* </span></label>
										<div class="col-md-9">
											<div class="input-group">
												<div class="input-group-addon">
													<i class="fa fa-calendar"></i>
												</div>
												<input type="text" class="form-control" id="tanggal" name="tanggal" >
											</div>
										</div>
									</div>

									<div class="modal-footer">
										<button class="btn btn-success" type="submit">edit</button>
										<button type="reset" class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Cancel</button>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div id="modal_subtotaljudul" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
							<h4 class="modal-title" id="myModalLabel">Edit due date</h4>
						</div>
						<div class="modal-content">
							<div class="modal-body">
								<form action="'.$aksi.'?module=invoice-customer&act=edit-subtotaljudul" method="POST">
									<input type="hidden" name="id_invoice" class="form-control" value="'.$id_invoice.'" />
									<div class="form-group">
										<label class="col-md-3 control-label">judul subtotal <span class="required">* </span></label>

										<div class="col-md-9">											
												<input type="text" class="form-control" id="editsubtotaljudul" name="subtotaljudul" >											
										</div>
									</div>

									<div class="modal-footer">
										<button class="btn btn-success" type="submit">edit</button>
										<button type="reset" class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Cancel</button>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
			';
			
			break;
			
			case "update2":
			
			$key = "asdf$1011";
			$encrypt = "$_GET[id]";
			$id = base64_decrypt($encrypt,$key);

			$query = mysqli_query($con, "select * from user_client where id_user='$id'");
			$r = mysqli_fetch_assoc($query);
									
			echo'
			<section class="content">
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">Detail User Client</h3>
						<div class="box-tools pull-right">
							<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
							<button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
						</div>
					</div><!-- /.box-header -->
					<form class="form-horizontal" id="form-user" method="post" action="'.$aksi.'?module=users&act=update2" autocomplete="off">
					<input type="hidden" name="id" value="'.$r['id_user'].'">
					<div class="box-body">
						<div class="form-group">
							<label class="col-md-3 control-label">Username <span class="required">* </span></label>
							<div class="col-md-6">
								<input type="text" name="username" placeholder="Username..." class="form-control" value="'.$r['username'].'" disabled>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label">Name <span class="required">* </span></label>
							<div class="col-md-6">
								<input type="text" name="namalengkap" placeholder="Name..." class="form-control" value="'.$r['fullname'].'" disabled>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label">Email <span class="required">* </span></label>
							<div class="col-md-6">
								<input type="text" name="email" placeholder="Email..." class="form-control" value="'.$r['email'].'" disabled>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label">Company <span class="required">* </span></label>
							<div class="col-md-6">
								<select name="id_kustomer" data-placeholder="Choose a customer..." class="form-control select2">
									<option value="">Select...</option>';
									$cabang = mysqli_query($con, "select id_kustomer,nama_kustomer from kustomer order by nama_kustomer");
									while($c=mysqli_fetch_assoc($cabang)){
										if($r['id_kustomer']==$c['id_kustomer']){
											echo'
											<option value="'.$c['id_kustomer'].'" selected>'.$c['nama_kustomer'].'</option>';
										}else{
											echo'
											<option value="'.$c['id_kustomer'].'">'.$c['nama_kustomer'].'</option>';
										}
									}
									echo'
								</select>
								<div class="val-message"></div>
							</div>
						</div>';
						if($r['blocked']=='Y'){
							echo'
							<div class="form-group">
								<label class="col-md-3 control-label"></label>
								<div class="col-md-6">
									<label><input type="checkbox" class="minimal" name="blocked" value="N"> Unblocked</label>
								</div>
							</div>';							
						}
						echo'
					</div><!-- /.box-body -->
					<div class="box-footer">
						<button type="button" class="btn btn-default" onclick="window.location.href = \'?module=tarif-regular\';">Cancel</button>
						<button type="submit" class="btn btn-primary pull-right">Save</button>
					</div><!-- /.box-footer -->
					</form>
				</div><!-- /.box -->
			</section>
			
			

			';
			
			break;
			
		}
	}else{
		echo akses_salah();
	}
}
?>