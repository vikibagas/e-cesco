<section class="content-header">
	<h1>Delivery<small></small></h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-home"></i> Home</a></li>
		<li><a href="#">Application</a></li>
		<li class="active">Delivery</li>
	</ol>
</section>
<?php
if(!isset($_SESSION)){
    session_start();
}

if (empty($_SESSION['username']) AND empty($_SESSION['password'])){
	header('location:../../application/login.php');
}
else{

	$level = $u['level'];
	//cek hak akses user
	if($u['level']=='admin' OR $u['level']=='operator' OR $u['level']=='network' OR $u['level']=='inbound' OR $u['level']=='finance' OR $u['level']=='intercity' OR $u['level']=='kurir' OR $u['level']=='agent' OR $u['level']=='driver' OR $u['level']=='domex' OR $u['level']=='logistics' or $u['level']=='kurir_agent' or $u['level']=='admin_network'){
		
		$aksi	= "module/mod_delivery/delivery_action.php";
		
		$act = !empty($_GET['act']) ? $_GET['act'] : '';
		
		switch($act){
			
			default:
			echo'
			<section class="content">
				<div class="row">
					<div class="col-xs-12">
						<div class="box box-primary">
							<div class="box-body">
							<form action="'.$aksi.'?module=delivery&act=plus" id="plustanggal" method="POST"></form>  			
							<table class="table table-striped table-bordered table-hover" width="100%" id="delivery" cellspacing="0" style="white-space:nowrap;">
								<thead>
									<tr>
										<th>Airwaybill</th>
										<th>Manifest Received - ETA</th>
										<th>Customer</th>
										<th>Kota Asal</th>
										<th>Kota Tujuan</th>
										<th>Service</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>';

								if($u['level']=='admin'  || $u['level'] == 'finance'){
									$query = mysqli_query($con,"
										select id_pengiriman, 
										id_cabang, 
										created_date,pickup_date_request,
										a.status,

										airwaybill_kode, 
										x_namakustomer(id_kustomer) as nama_kustomer, 
										x_kodekota(lokasi_asal) as kode_kota_asal, 
										x_idcabanguser(courier_by) as cabang_deliv, 
										
										case when a.id_kota_asal>0 then a.kota_asal else x_namakotabesar(a.lokasi_asal) end as nama_kota_asal,

										x_kodekota(lokasi_tujuan) as kode_kota_tujuan, 

										case when a.id_kota_tujuan>0 then a.kota_tujuan else x_namakotabesar(lokasi_tujuan) end as nama_kota_tujuan,

										x_namanegara(a.lokasi_tujuan) as negara,
										x_service(a.service) as nama_pelayanan,

										
										x_namakotadetail(lokasi_tujuan) as nama_kota_tujuan_detail, 
										a.service, 
										tanggal, courier_date, wakilkan_deliv,
										
										

										lokasi_asal, 
										lokasi_tujuan, 
										x_kurir(courier_by) as kurir,

										a.pickup_date,
										a.manifest_received,

										CASE 
    WHEN a.manifest_received IS NULL 
         OR a.manifest_received = '0000-00-00'
    THEN a.pickup_date
    ELSE a.manifest_received
END AS start,
										case 
											when a.service = 1 then ifnull(case when x_dhl(a.id_kustomer) = 'Y' then c.sla else b.sla end,0) 
											when a.service = 2 then ifnull(case when x_dhl(a.id_kustomer) = 'Y' then c.sla else b.sla end,1) 
											else
												ifnull(case when x_dhl(a.id_kustomer) = 'Y' then c.sla else b.sla end,if (x_hubnew(a.id_kota_tujuan) = a.id_kota_tujuan,1,2) ) 
											end
										as sla_max
										

										from pengiriman  a

										left join sla_deliv b on b.kota_tujuan =  a.id_kota_tujuan AND b.service = a.service	

										left join sla_deliv_dhl c on c.kota_tujuan =  a.id_kota_tujuan AND c.service = a.service	
										

										 where status != 6 and status > 4 and pickup='Y' and delivery='N' and (courier_by is not null or courier_by !=0 ) and status!='7' or (a.service = 6  and delivery_name is null and status != 7) 

										 or(a.service = 4 and delivery_name is null and status !=7 and status=2)  group by a.id_pengiriman");
									

								}elseif($u['level']=='operator' OR $u['level']=='intercity'){
									if($u['level']=='intercity' && $u['tambahan_admin'] == '1') {
										$query = mysqli_query($con,"
											select id_pengiriman, 
										id_cabang, 
										created_date,pickup_date_request, 
										airwaybill_kode, 
										x_namakustomer(id_kustomer) as nama_kustomer, 
										x_kodekota(lokasi_asal) as kode_kota_asal, 
										case when a.id_kota_asal>0 then a.kota_asal else x_namakotabesar(a.lokasi_asal) end as nama_kota_asal,
										a.status,

										x_kodekota(lokasi_tujuan) as kode_kota_tujuan, 
										x_idcabanguser(courier_by) as cabang_deliv, 

										case when a.id_kota_tujuan>0 then a.kota_tujuan else x_namakotabesar(lokasi_tujuan) end as nama_kota_tujuan,
										x_namakotadetail(lokasi_tujuan) as nama_kota_tujuan_detail, 
										a.service, 
										tanggal, courier_date, wakilkan_deliv,
										lokasi_asal, 
										lokasi_tujuan, 
										x_kurir(courier_by) as kurir,
										x_namanegara(a.lokasi_tujuan) as negara,
										x_service(a.service) as nama_pelayanan,
										a.pickup_date,
										a.manifest_received,

										CASE 
    WHEN a.manifest_received IS NULL 
         OR a.manifest_received = '0000-00-00'
    THEN a.pickup_date
    ELSE a.manifest_received
END AS start,
										
										case 
											when a.service = 1 then ifnull(case when x_dhl(a.id_kustomer) = 'Y' then c.sla else b.sla end,0) 
											when a.service = 2 then ifnull(case when x_dhl(a.id_kustomer) = 'Y' then c.sla else b.sla end,1) 
											else
												ifnull(case when x_dhl(a.id_kustomer) = 'Y' then c.sla else b.sla end,if (x_hubnew(a.id_kota_tujuan) = a.id_kota_tujuan,1,2)) 
											end
										as sla_max
										

										from pengiriman  a

										left join sla_deliv b on b.kota_tujuan =  a.id_kota_tujuan AND b.service = a.service	

										left join sla_deliv_dhl c on c.kota_tujuan =  a.id_kota_tujuan AND c.service = a.service

											where status != 6 and pickup='Y' and delivery='N' and (courier_by is not null or courier_by !=0 ) and status!='7' or (a.service = 6 and delivery_name is null and status != 7 and  pickup='Y') group by a.id_pengiriman ");
									}
									else{
										$query = mysqli_query($con,"
											select id_pengiriman, 
										id_cabang, 
										created_date,pickup_date_request, 
										airwaybill_kode, 
										x_namakustomer(id_kustomer) as nama_kustomer, 
										x_kodekota(lokasi_asal) as kode_kota_asal, 
										case when a.id_kota_asal>0 then a.kota_asal else x_namakotabesar(a.lokasi_asal) end as nama_kota_asal,

										x_kodekota(lokasi_tujuan) as kode_kota_tujuan, 
										x_namanegara(a.lokasi_tujuan) as negara,
										x_idcabanguser(courier_by) as cabang_deliv, 
										a.status,

										case when a.id_kota_tujuan>0 then a.kota_tujuan else x_namakotabesar(lokasi_tujuan) end as nama_kota_tujuan,
										x_namakotadetail(lokasi_tujuan) as nama_kota_tujuan_detail, 
										a.service, 
										tanggal, courier_date, wakilkan_deliv,
										lokasi_asal, 
										lokasi_tujuan, 
										x_kurir(courier_by) as kurir,
										a.pickup_date,
										a.manifest_received,
										x_service(a.service) as nama_pelayanan,

										CASE 
    WHEN a.manifest_received IS NULL 
         OR a.manifest_received = '0000-00-00'
    THEN a.pickup_date
    ELSE a.manifest_received
END AS start,
										
										case 
											when a.service = 1 then ifnull(case when x_dhl(a.id_kustomer) = 'Y' then c.sla else b.sla end,0) 
			when a.service = 2 then ifnull(case when x_dhl(a.id_kustomer) = 'Y' then c.sla else b.sla end,1) 
											else
												ifnull(case when x_dhl(a.id_kustomer) = 'Y' then c.sla else b.sla end,if (x_hubnew(a.id_kota_tujuan) = a.id_kota_tujuan,1,2)) 
											end
										as sla_max
										

										from pengiriman  a

										left join sla_deliv b on b.kota_tujuan =  a.id_kota_tujuan AND b.service = a.service	

										left join sla_deliv_dhl c on c.kota_tujuan =  a.id_kota_tujuan AND c.service = a.service

										where status != 6 and pickup='Y' and delivery='N' and (courier_by is not null or courier_by !=0 ) and status!='7' and x_inter(id_kota_asal) = 'Y'
										and if(a.id_kota_tujuan>0,x_inter(a.id_kota_tujuan)='Y',x_intercity(lokasi_tujuan)='Y')  
										or (a.service = 6  and delivery_name is null and status != 7)  group by a.id_pengiriman");

										
									}

								}elseif($u['id_cabang']=='39' AND $u['level']=='kurir'){
									$query = mysqli_query($con,"
										select id_pengiriman, 
										id_cabang, 
										created_date,pickup_date_request, 
										airwaybill_kode, 
										x_namakustomer(id_kustomer) as nama_kustomer, 
										x_kodekota(lokasi_asal) as kode_kota_asal, 
										x_idcabanguser(courier_by) as cabang_deliv, 
										case when a.id_kota_asal>0 then a.kota_asal else x_namakotabesar(a.lokasi_asal) end as nama_kota_asal,

										x_kodekota(lokasi_tujuan) as kode_kota_tujuan, 
										a.status,

										case when a.id_kota_tujuan>0 then a.kota_tujuan else x_namakotabesar(lokasi_tujuan) end as nama_kota_tujuan,
										x_namakotadetail(lokasi_tujuan) as nama_kota_tujuan_detail, 
										a.service, 
										tanggal, courier_date, wakilkan_deliv,
										lokasi_asal, 
										lokasi_tujuan, 
										x_kurir(courier_by) as kurir,
										a.pickup_date,
										a.manifest_received,
										x_service(a.service) as nama_pelayanan,

										CASE 
    WHEN a.manifest_received IS NULL 
         OR a.manifest_received = '0000-00-00'
    THEN a.pickup_date
    ELSE a.manifest_received
END AS start,
										
										case 
											when a.service = 1 then ifnull(case when x_dhl(a.id_kustomer) = 'Y' then b.sla else c.sla end,0) 
			when a.service = 2 then ifnull(case when x_dhl(a.id_kustomer) = 'Y' then b.sla else c.sla end,1) 
											else
												ifnull(case when x_dhl(a.id_kustomer) = 'Y' then c.sla else b.sla end,if (x_hubnew(a.id_kota_tujuan) = a.id_kota_tujuan,1,2)) 
											end
										as sla_max
										

										from pengiriman  a

										left join sla_deliv b on b.kota_tujuan =  a.id_kota_tujuan AND b.service = a.service	

										left join sla_deliv_dhl c on c.kota_tujuan =  a.id_kota_tujuan AND c.service = a.service

										where status != 6 and pickup='Y' and delivery='N' and courier_by='".$u['id_user']."' and status!='7' group by a.id_pengiriman");

									
								}elseif($u['id_cabang']=='39' AND $u['level']=='driver'){
									$query = mysqli_query($con,"
										select id_pengiriman, 
										id_cabang, 
										created_date,pickup_date_request, 
										airwaybill_kode, 
										x_namakustomer(id_kustomer) as nama_kustomer, 
										x_kodekota(lokasi_asal) as kode_kota_asal, 
										case when a.id_kota_asal>0 then a.kota_asal else x_namakotabesar(a.lokasi_asal) end as nama_kota_asal,

										x_kodekota(lokasi_tujuan) as kode_kota_tujuan,
										a.status, 

										case when a.id_kota_tujuan>0 then a.kota_tujuan else x_namakotabesar(lokasi_tujuan) end as nama_kota_tujuan,
										x_namakotadetail(lokasi_tujuan) as nama_kota_tujuan_detail, 
										a.service, 
										tanggal, courier_date, wakilkan_deliv,
										x_idcabanguser(courier_by) as cabang_deliv, 
										lokasi_asal, 
										lokasi_tujuan, 
										x_kurir(courier_by) as kurir,
										a.pickup_date,
										a.manifest_received,
										x_service(a.service) as nama_pelayanan,

										CASE 
    WHEN a.manifest_received IS NULL 
         OR a.manifest_received = '0000-00-00'
    THEN a.pickup_date
    ELSE a.manifest_received
END AS start,
										
										case 
											when a.service = 1 then ifnull(case when x_dhl(a.id_kustomer) = 'Y' then b.sla else c.sla end,0) 
			when a.service = 2 then ifnull(case when x_dhl(a.id_kustomer) = 'Y' then b.sla else c.sla end,1) 
											else
												ifnull(case when x_dhl(a.id_kustomer) = 'Y' then c.sla else b.sla end,if (x_hubnew(a.id_kota_tujuan) = a.id_kota_tujuan,1,2)) 
											end
										as sla_max
										

										from pengiriman  a

										left join sla_deliv b on b.kota_tujuan =  a.id_kota_tujuan AND b.service = a.service	

										left join sla_deliv_dhl c on c.kota_tujuan =  a.id_kota_tujuan AND c.service = a.service

										where status != 6 and pickup='Y' and delivery='N' and courier_by='".$u['id_user']."' and status!='7' group by a.id_pengiriman");
								}

								elseif($u['level']=='admin_network'){

									$query = mysqli_query($con,"
										select a.id_pengiriman, 
										id_cabang, 
										a.created_date,pickup_date_request, 
										airwaybill_kode, 
										x_namakustomer(id_kustomer) as nama_kustomer, 
										x_kodekota(lokasi_asal) as kode_kota_asal, 
										case when a.id_kota_asal>0 then a.kota_asal else x_namakotabesar(a.lokasi_asal) end as nama_kota_asal,

										x_kodekota(lokasi_tujuan) as kode_kota_tujuan, 
										a.status,

										case when a.id_kota_tujuan>0 then a.kota_tujuan else x_namakotabesar(lokasi_tujuan) end as nama_kota_tujuan,
										x_namakotadetail(lokasi_tujuan) as nama_kota_tujuan_detail, 
										a.service, 
										a.tanggal, courier_date, wakilkan_deliv,
										x_idcabanguser(courier_by) as cabang_deliv, 
										lokasi_asal, 
										lokasi_tujuan, 
										x_kurir(courier_by) as kurir,
										x_service(a.service) as nama_pelayanan,
										a.pickup_date,
										a.manifest_received,

										CASE 
    WHEN a.manifest_received IS NULL 
         OR a.manifest_received = '0000-00-00'
    THEN a.pickup_date
    ELSE a.manifest_received
END AS start,
										
										case 
											when a.service = 1 then ifnull(case when x_dhl(a.id_kustomer) = 'Y' then e.sla else b.sla end,0) 
											when a.service = 2 then ifnull(case when x_dhl(a.id_kustomer) = 'Y' then e.sla else b.sla end,1) 
											else
												ifnull(case when x_dhl(a.id_kustomer) = 'Y' then e.sla else b.sla end,if (x_hubnew(a.id_kota_tujuan) = a.id_kota_tujuan,1,2)) 
											end
										as sla_max
										

										from pengiriman  a

										left join sla_deliv b on b.kota_tujuan =  a.id_kota_tujuan AND b.service = a.service	

										left join sla_deliv_dhl e on e.kota_tujuan =  a.id_kota_tujuan AND e.service = a.service

										

										where status != 6 and pickup='Y' and delivery='N' and courier_by is not null and courier_by !=0 
										
										and  a.airwaybill_kode not like '002-%'
										
										and status!='7' 
										

										

										or (status='5' and a.service=4)

										

										  group by a.id_pengiriman" );
								}

								elseif($u['id_cabang']=='39' AND $u['level']=='network'){

									$query = mysqli_query($con,"
										select a.id_pengiriman, 
										id_cabang, 
										a.created_date,pickup_date_request, 
										airwaybill_kode, 
										x_namakustomer(id_kustomer) as nama_kustomer, 
										x_kodekota(lokasi_asal) as kode_kota_asal, 
										case when a.id_kota_asal>0 then a.kota_asal else x_namakotabesar(a.lokasi_asal) end as nama_kota_asal,

										x_kodekota(lokasi_tujuan) as kode_kota_tujuan, 
										a.status,

										case when a.id_kota_tujuan>0 then a.kota_tujuan else x_namakotabesar(lokasi_tujuan) end as nama_kota_tujuan,
										x_namakotadetail(lokasi_tujuan) as nama_kota_tujuan_detail, 
										a.service, 
										a.tanggal, courier_date, wakilkan_deliv,
										x_idcabanguser(courier_by) as cabang_deliv, 
										lokasi_asal, 
										lokasi_tujuan, 
										x_kurir(courier_by) as kurir,
										x_service(a.service) as nama_pelayanan,
										a.pickup_date,
										a.manifest_received,

										CASE 
    WHEN a.manifest_received IS NULL 
         OR a.manifest_received = '0000-00-00'
    THEN a.pickup_date
    ELSE a.manifest_received
END AS start,
										
										case 
											when a.service = 1 then ifnull(case when x_dhl(a.id_kustomer) = 'Y' then e.sla else b.sla end,0) 
											when a.service = 2 then ifnull(case when x_dhl(a.id_kustomer) = 'Y' then e.sla else b.sla end,1) 
											else
												ifnull(case when x_dhl(a.id_kustomer) = 'Y' then e.sla else b.sla end,if (x_hubnew(a.id_kota_tujuan) = a.id_kota_tujuan,1,2)) 
											end
										as sla_max
										

										from pengiriman  a

										left join sla_deliv b on b.kota_tujuan =  a.id_kota_tujuan AND b.service = a.service	

										left join sla_deliv_dhl e on e.kota_tujuan =  a.id_kota_tujuan AND e.service = a.service

										left join kota c on c.id_kota=a.lokasi_tujuan 

										left join regencies d on d.id=a.id_kota_tujuan

										where status != 6 and pickup='Y' and delivery='N' and courier_by is not null and courier_by !=0 
										
										and if(id_kota_asal>0,d.province_id in ($u[area_prov]),c.kode_pulau in ($u[area_pulau]))
										and status!='7' 
										

										and if(a.id_kota_asal>0,x_inter(a.id_kota_asal)='Y',x_intercity(a.lokasi_asal)='Y') 

										and if(a.id_kota_tujuan>0,x_inter(a.id_kota_tujuan)!='Y',x_intercity(a.lokasi_tujuan)!='Y') 

										or (status='5' and a.service=4)

										

										  group by a.id_pengiriman" );
								}
								elseif($u['id_cabang']=='39' AND $u['level']=='inbound'){

									$query = mysqli_query($con,"
										select a.id_pengiriman, 
										id_cabang, 
										a.created_date,pickup_date_request, 
										airwaybill_kode, 
										x_namakustomer(id_kustomer) as nama_kustomer, 
										x_kodekota(lokasi_asal) as kode_kota_asal, 
										case when a.id_kota_asal>0 then a.kota_asal else x_namakotabesar(a.lokasi_asal) end as nama_kota_asal,

										x_kodekota(lokasi_tujuan) as kode_kota_tujuan, 
										a.status,

										case when a.id_kota_tujuan>0 then a.kota_tujuan else x_namakotabesar(lokasi_tujuan) end as nama_kota_tujuan,
										x_namakotadetail(lokasi_tujuan) as nama_kota_tujuan_detail, 
										a.service, 
										a.tanggal, courier_date, wakilkan_deliv,
										x_idcabanguser(courier_by) as cabang_deliv, 
										lokasi_asal, 
										lokasi_tujuan, 
										x_kurir(courier_by) as kurir,
										a.pickup_date,
										a.manifest_received,
										x_service(a.service) as nama_pelayanan,

										CASE 
    WHEN a.manifest_received IS NULL 
         OR a.manifest_received = '0000-00-00'
    THEN a.pickup_date
    ELSE a.manifest_received
END AS start,
										
										case 
											when a.service = 1 then ifnull(case when x_dhl(a.id_kustomer) = 'Y' then e.sla else b.sla end,0) 
											when a.service = 2 then ifnull(case when x_dhl(a.id_kustomer) = 'Y' then e.sla else b.sla end,1) 
											else
												ifnull(case when x_dhl(a.id_kustomer) = 'Y' then e.sla else b.sla end,if (x_hubnew(a.id_kota_tujuan) = a.id_kota_tujuan,1,2)) 
											end
										as sla_max
										

										from pengiriman  a

										left join sla_deliv b on b.kota_tujuan =  a.id_kota_tujuan AND b.service = a.service	

										left join sla_deliv_dhl e on e.kota_tujuan =  a.id_kota_tujuan AND e.service = a.service

										left join kota c on c.id_kota=a.lokasi_asal
										left join regencies d on d.id=a.id_kota_asal

										where status != 6 and pickup='Y' and delivery='N' and courier_by is not null and courier_by !=0 
										and if(id_kota_asal>0,x_inter(a.id_kota_asal)!='Y',x_intercity(a.lokasi_asal)!='Y')
										and if(id_kota_asal>0,d.province_id in ($u[area_prov]),c.kode_pulau in ($u[area_pulau]))
										and status!='7' 
										  group by a.id_pengiriman ");
								}
								elseif($u['level']=='domex'){

									if($u['level']=='domex' && $u['tambahan_admin'] == '1') {

										$query = mysqli_query($con,"
											select id_pengiriman, 
										id_cabang, 
										created_date,pickup_date_request, 
										airwaybill_kode, 
										x_namakustomer(id_kustomer) as nama_kustomer, 
										x_kodekota(lokasi_asal) as kode_kota_asal, 
										case when a.id_kota_asal>0 then a.kota_asal else x_namakotabesar(a.lokasi_asal) end as nama_kota_asal,

										x_kodekota(lokasi_tujuan) as kode_kota_tujuan, 
										a.status,

										case when a.id_kota_tujuan>0 then a.kota_tujuan else x_namakotabesar(lokasi_tujuan) end as nama_kota_tujuan,
										x_namakotadetail(lokasi_tujuan) as nama_kota_tujuan_detail, 
										a.service, 
										x_service(a.service) as nama_pelayanan,
										tanggal, courier_date, wakilkan_deliv,
										x_idcabanguser(courier_by) as cabang_deliv, 
										lokasi_asal, 
										lokasi_tujuan, 
										x_kurir(courier_by) as kurir,
										a.pickup_date,
										a.manifest_received,

										CASE 
    WHEN a.manifest_received IS NULL 
         OR a.manifest_received = '0000-00-00'
    THEN a.pickup_date
    ELSE a.manifest_received
END AS start,
										
										case 
											when a.service = 1 then ifnull(case when x_dhl(a.id_kustomer) = 'Y' then c.sla else b.sla end,0) 
											when a.service = 2 then ifnull(case when x_dhl(a.id_kustomer) = 'Y' then c.sla else b.sla end,1) 
											else
												ifnull(case when x_dhl(a.id_kustomer) = 'Y' then c.sla else b.sla end,if (x_hubnew(a.id_kota_tujuan) = a.id_kota_tujuan,1,2)) 
											end
										as sla_max
										

										from pengiriman  a

										left join sla_deliv b on b.kota_tujuan =  a.id_kota_tujuan AND b.service = a.service	

										left join sla_deliv_dhl c on c.kota_tujuan =  a.id_kota_tujuan AND c.service = a.service

											where status != 6 and pickup='Y' and delivery='N' and courier_by is not null and courier_by !=0 and status!='7' or (a.service = 6 and delivery_name is null) or (status =4 and manifest='Y' and x_intercity(lokasi_tujuan) != 'Y') group by a.id_pengiriman" );
									}else{

										if ($u['id_kustomer'] > 0 ){
														
											$query = mysqli_query($con,"
											select id_pengiriman, 
										id_cabang, 
										created_date,pickup_date_request, 
										airwaybill_kode, 
										x_namakustomer(id_kustomer) as nama_kustomer, 
										x_kodekota(lokasi_asal) as kode_kota_asal, 
										case when a.id_kota_asal>0 then a.kota_asal else x_namakotabesar(a.lokasi_asal) end as nama_kota_asal,

										x_kodekota(lokasi_tujuan) as kode_kota_tujuan, 
										a.status,

										case when a.id_kota_tujuan>0 then a.kota_tujuan else x_namakotabesar(lokasi_tujuan) end as nama_kota_tujuan,
										x_namakotadetail(lokasi_tujuan) as nama_kota_tujuan_detail, 
										a.service, 
										x_service(a.service) as nama_pelayanan,
										tanggal, courier_date, wakilkan_deliv,
										x_idcabanguser(courier_by) as cabang_deliv, 
										lokasi_asal, 
										lokasi_tujuan, 
										x_kurir(courier_by) as kurir,
										a.pickup_date,
										a.manifest_received,

										CASE 
    WHEN a.manifest_received IS NULL 
         OR a.manifest_received = '0000-00-00'
    THEN a.pickup_date
    ELSE a.manifest_received
END AS start,
										
										case 
											when a.service = 1 then ifnull(case when x_dhl(a.id_kustomer) = 'Y' then c.sla else b.sla end,0) 
											when a.service = 2 then ifnull(case when x_dhl(a.id_kustomer) = 'Y' then c.sla else b.sla end,1) 
											else
												ifnull(case when x_dhl(a.id_kustomer) = 'Y' then c.sla else b.sla end,if (x_hubnew(a.id_kota_tujuan) = a.id_kota_tujuan,1,2)) 
											end
										as sla_max
										

										from pengiriman  a

										left join sla_deliv b on b.kota_tujuan =  a.id_kota_tujuan AND b.service = a.service	

										left join sla_deliv_dhl c on c.kota_tujuan =  a.id_kota_tujuan AND c.service = a.service

											where status != 6 and pickup='Y' and delivery='N' and a.id_kustomer = $u[id_kustomer]
											and (courier_by is not null or courier_by!='0') and status!='7' or (status =4 and manifest='Y' and a.id_kustomer = $u[id_kustomer]) group by a.id_pengiriman");
										}
										else{
											$query = mysqli_query($con,"
											select id_pengiriman, 
										id_cabang, 
										created_date,pickup_date_request, 
										airwaybill_kode, 
										x_namakustomer(id_kustomer) as nama_kustomer, 
										x_kodekota(lokasi_asal) as kode_kota_asal, 
										case when a.id_kota_asal>0 then a.kota_asal else x_namakotabesar(a.lokasi_asal) end as nama_kota_asal,

										x_kodekota(lokasi_tujuan) as kode_kota_tujuan, 

										case when a.id_kota_tujuan>0 then a.kota_tujuan else x_namakotabesar(lokasi_tujuan) end as nama_kota_tujuan,
										x_namakotadetail(lokasi_tujuan) as nama_kota_tujuan_detail, 
										a.service, 
										tanggal, courier_date, wakilkan_deliv,
										x_idcabanguser(courier_by) as cabang_deliv, 
										a.status,
										lokasi_asal, 
										x_service(a.service) as nama_pelayanan,
										lokasi_tujuan, 
										x_kurir(courier_by) as kurir,
										a.pickup_date,
										a.manifest_received,

										CASE 
    WHEN a.manifest_received IS NULL 
         OR a.manifest_received = '0000-00-00'
    THEN a.pickup_date
    ELSE a.manifest_received
END AS start,
										
										case 
											when a.service = 1 then ifnull(case when x_dhl(a.id_kustomer) = 'Y' then c.sla else b.sla end,0) 
											when a.service = 2 then ifnull(case when x_dhl(a.id_kustomer) = 'Y' then c.sla else b.sla end,1) 
											else
												ifnull(case when x_dhl(a.id_kustomer) = 'Y' then c.sla else b.sla end,if (x_hubnew(a.id_kota_tujuan) = a.id_kota_tujuan,1,2)) 
											end
										as sla_max
										

										from pengiriman  a

										left join sla_deliv b on b.kota_tujuan =  a.id_kota_tujuan AND b.service = a.service	

										left join sla_deliv_dhl c on c.kota_tujuan =  a.id_kota_tujuan AND c.service = a.service

											where status != 6 and pickup='Y' and delivery='N' 
											and (courier_by is not null or courier_by!='0') and ( x_intercity(lokasi_asal) != 'Y' ) and status!='7' or (status =4 and manifest='Y' and x_intercity(lokasi_asal) != 'Y') group by a.id_pengiriman");	
										}
										
									}
											

								}elseif($u['level']=='logistics'){
									$query = mysqli_query($con,"
										select id_pengiriman, 
										id_cabang, 
										created_date,pickup_date_request, 
										airwaybill_kode, 
										x_namakustomer(id_kustomer) as nama_kustomer, 
										x_kodekota(lokasi_asal) as kode_kota_asal, 
										case when a.id_kota_asal>0 then a.kota_asal else x_namakotabesar(a.lokasi_asal) end as nama_kota_asal,

										x_kodekota(lokasi_tujuan) as kode_kota_tujuan, 

										case when a.id_kota_tujuan>0 then a.kota_tujuan else x_namakotabesar(lokasi_tujuan) end as nama_kota_tujuan,
										x_namakotadetail(lokasi_tujuan) as nama_kota_tujuan_detail, 
										a.service, 
										tanggal, courier_date, wakilkan_deliv,
										x_idcabanguser(courier_by) as cabang_deliv, 
										lokasi_asal, 
										x_service(a.service) as nama_pelayanan,
										a.status,
										lokasi_tujuan, 
										x_kurir(courier_by) as kurir,
										a.pickup_date,
										a.manifest_received,

										CASE 
    WHEN a.manifest_received IS NULL 
         OR a.manifest_received = '0000-00-00'
    THEN a.pickup_date
    ELSE a.manifest_received
END AS start,
										
										case 
											when a.service = 1 then ifnull(case when x_dhl(a.id_kustomer) = 'Y' then c.sla else b.sla end,0) 
											when a.service = 2 then ifnull(case when x_dhl(a.id_kustomer) = 'Y' then c.sla else b.sla end,1) 
											else
												ifnull(case when x_dhl(a.id_kustomer) = 'Y' then c.sla else b.sla end,if (x_hubnew(a.id_kota_tujuan) = a.id_kota_tujuan,1,2)) 
											end
										as sla_max
										

										from pengiriman  a

										left join sla_deliv b on b.kota_tujuan =  a.id_kota_tujuan AND b.service = a.service	

										left join sla_deliv_dhl c on c.kota_tujuan =  a.id_kota_tujuan AND c.service = a.service

										where status != 6 and pickup='Y' and delivery='N' and service in (4,5,10,11) and status!='7' group by a.id_pengiriman");
								}elseif($u['level']=='agent' || $u['level']=='kurir_agent'){
									$agent = mysqli_query($con, 'select id_kota,id_kota_baru from cabang where id_cabang="'.$u['id_cabang'].'"');
									$a = mysqli_fetch_assoc($agent);
												
									$query = mysqli_query($con,"

										select a.id_pengiriman, 
										id_cabang, 
										a.created_date,pickup_date_request, 
										airwaybill_kode, 
										x_namakustomer(id_kustomer) as nama_kustomer, 
										
										a.kota_asal as nama_kota_asal,
										a.status,

										
										a.kota_tujuan as nama_kota_tujuan,
										
										a.service, 
										a.tanggal, courier_date, wakilkan_deliv,
										
										x_service(a.service) as nama_pelayanan,
								 
										x_kurir(courier_by) as kurir,
										x_idcabanguser(courier_by) as cabang_deliv, 
										a.pickup_date,
										a.manifest_received,

										CASE 
    WHEN a.manifest_received IS NULL 
         OR a.manifest_received = '0000-00-00'
    THEN a.pickup_date
    ELSE a.manifest_received
END AS start,
										
										case 
											when a.service = 1 then ifnull(case when x_dhl(a.id_kustomer) = 'Y' then i.sla else b.sla end,0) 
											when a.service = 2 then ifnull(case when x_dhl(a.id_kustomer) = 'Y' then i.sla else b.sla end,1) 
											else
												ifnull(case when x_dhl(a.id_kustomer) = 'Y' then i.sla else b.sla end,if (x_hubnew(a.id_kota_tujuan) = a.id_kota_tujuan,1,2)) 
											end
										as sla_max
										

										from pengiriman  a

										left join sla_deliv b on b.kota_tujuan =  a.id_kota_tujuan AND b.service = a.service	

										left join sla_deliv_dhl i on i.kota_tujuan =  a.id_kota_tujuan AND i.service = a.service

										inner join manifest_detail d on d.id_pengiriman=a.id_pengiriman 
										inner join manifest e on e.id_manifest=d.id_manifest 
										
										left join regencies g on g.id=a.id_kota_tujuan
										left join provinces h on h.id=g.province_id		

										where a.status != 6 and a.status > 3 and a.manifest='Y' and pickup='Y' and delivery='N' and delivery_by_agent = $u[id_cabang]
										

										and 
										if( a.id_kota_tujuan>0, 
											(a.id_kota_tujuan=$a[id_kota_baru] or ifnull(g.hub,h.ibu_kota) =x_hubnew($a[id_kota_baru]) 
												or (e.manifest_to=$u[id_cabang] and ifnull(g.hub,h.ibu_kota) =x_hubnew($a[id_kota_baru])) )
											,(a.lokasi_tujuan=$a[id_kota] )
										)  
										
										 and a.status!='7' and e.manifest_to=$u[id_cabang] 
										 or  (e.manifest_to=$u[id_cabang] and (a.status=4 or a.status=5) and delivery_by_agent = $u[id_cabang] )
										 group by a.id_pengiriman ");

									


								}
								$i = 0;
								while($q=mysqli_fetch_assoc($query)){
									
									$key = "asdf$1011";
									$stringawal = "$q[id_pengiriman]";
									$id = base64_encrypt($stringawal,$key);
									
									//$tanggal = tgl_indo($q['created_date']);
									$service = service($q['service']);
									$awal =  $q['start'];
									$id_pengiriman = $q['id_pengiriman'];

									echo'
									<tr>
										<td><center>'.$q['airwaybill_kode'];
											
											if($u['level'] == 'admin' and $u['id_user'] != 17){
												echo '<input type="checkbox" form="plustanggal" name="selectawb[]" value="'.$id_pengiriman.'">';
											}

											

											echo '</center>
										</td>
										<td>'.date("Y-m-d",strtotime($awal)).' - ';

											
											

											
											

											
											

											$courier_date = date('Y-m-d',strtotime( $q['courier_date']  ));
											
											
											//$date_request= $awal;

											//$id_pengiriman= $pieces[3];

											$jumlah_hari = $q['sla_max'];
											$tanggal =  $awal;
											for($i=$jumlah_hari;$i>=1;$i--){
												$tanggal = date('Y-m-d',strtotime($tanggal.'+ 1 day'));
												$hari = date('l' ,strtotime($tanggal));
												if($hari== 'Sunday'){
													$i=$i+1;
													continue;
												}
											}
											$sla_seharusnya = date('Y-m-d',strtotime( $tanggal  ));


									        

									       

									        

									        if ($courier_date != '' && $courier_date > $sla_seharusnya){
									        	$sla = $courier_date.'<span title="ada perubahan request tanggal pengiriman yang seharusnya tanggal'.$sla_seharusnya.'">*</span>';
									        }else{
									        	$sla = $sla_seharusnya;
									        }

									        

											$date_now =  date("Y-m-d");

											if($sla != '1970-01-01'){
							
												if ($date_now > $sla){

													$created_date =  $sla.' <button class="btn btn-danger btn-xs"><i class="fa fa-exclamation-circle"></i> </button>';
												}else{
													$created_date =   $sla;
												}
											}else{
												$created_date =  $booking_date.  ' <button data-toggle="modal" data-target="#modal_sla_insert" data-id="'.$id_pengiriman.'" class="btn btn-xs btn-warning custom_sla_insert"><i class="fa fa-plus"></i> </button>';
											}
											$btn = ' <button class="btn btn-xs btn-primary detailawb" title="detail" onclick="detailwaktu(this.id)" id="'.$id_pengiriman.'"><i class="fa fa-clock-o"></i> </button>';

											echo $created_date.$btn  ;

										echo '
										</td>
										<td>'.$q['nama_kustomer'].'</td>';
										if($q['nama_kota_asal']=='Jakarta Selatan' || $q['nama_kota_asal']=='Jakarta Barat' || $q['nama_kota_asal']=='Jakarta Timur' || $q['nama_kota_asal']=='Jakarta Pusat' || $q['nama_kota_asal']=='Jakarta Utara'){
											echo'
											<td>JAKARTA</td>'	;
										}else{
											echo'
											<td>'.strtoupper($q['nama_kota_asal']).'</td>';
										}
										echo '
											<td>'.strtoupper( ($q['service'] !=6 ? $q['nama_kota_tujuan'] : $q['negara'] ) ).'</td>';
										
										echo'
										<td class="center">'.strtoupper($q['nama_pelayanan']).'</td>
										<td><center>
										';

										$wakilkan_deliv = $q['wakilkan_deliv'];
										
										if($level == 'admin'){
							
											if($wakilkan_deliv == 'Y'){
												$button_wakilkan = '';
											}else{
												$button_wakilkan = '<button class="btn btn-xs btn-primary" title="wakilkan" onclick="window.location.href = \'module/mod_delivery/delivery_action.php?module=delivery&act=wakilkan&id_pengiriman='.$id.'\';" >W</button>';
											}

										}elseif($level == 'network' || $level == 'inbound'){
											if($wakilkan_deliv == 'Y'){
												$button_wakilkan = '<button class="btn btn-xs btn-info open_modal" data-id="'.$id.' 1"><i class="fa fa-check"></i> Delivered</button> ';
												
											}else{
												$button_wakilkan = '';
											}
										}else{
											$button_wakilkan = '';
										}
										

										$button_back_inbound =  '<button class="btn btn-xs btn-primary" title="back to inbound" onclick="window.location.href = \'module/mod_delivery/delivery_action.php?module=delivery&act=backtoinbound&id_pengiriman='.$id.'\';" >back inbound</button>';


										if ( $u['level'] == 'finance'){
											echo '-'.$button_back_inbound;
										}else{
											if ($u['level'] == 'admin' || $u['level'] == 'kurir' || $u['level'] == 'driver' || $u['level'] == 'agent' || $u['level'] == 'kurir_agent' ){

												if($sla != '1970-01-01'){
								
													if ($date_now > $sla){

														echo '<button class="btn btn-xs btn-info open_modal" data-id="'.$id.' 1"><i class="fa fa-check"></i> Delivered</button> '.$button_wakilkan.$wakilkan_deliv.($u['level'] == 'admin' ? $button_back_inbound : '');
													}else{
														echo '<button class="btn btn-xs btn-info open_modal" data-id="'.$id.' 0"><i class="fa fa-check"></i> Delivered</button> '.$button_wakilkan.$wakilkan_deliv.($u['level'] == 'admin' ? $button_back_inbound : '');
													}
												}else{
													echo '<button class="btn btn-xs btn-info open_modal" data-id="'.$id.' 0"><i class="fa fa-check"></i> Delivered</button> '.$button_wakilkan.$wakilkan_deliv.($u['level'] == 'admin' ? $button_back_inbound : '');
												}											
											}else{
												echo $button_wakilkan.$wakilkan_deliv.($u['level'] == 'admin' ? $button_back_inbound : '');
											}
										}
										

										if($u['level']=='admin' || $u['level']=='operator' || $u['level']=='intercity' || $u['level']=='domex' || $u['level']=='agent' || $u['level']=='inbound'){
											echo'<button class="btn btn-xs btn-warning open_switch" data-id='.$id.' "><i class="fa fa-exchange"></i></button>';
										}
										echo'</center></td>
									</tr>';
									$i++;
								}
								echo'
								</tbody>
							</table>
							';
							if ($u['level']=='admin'){

								echo '
								<input type="text" id="tanggal" name="tanggalplus" form="plustanggal">
								<input type="text" id="alasan_plus" name="alasan_plus" form="plustanggal" placeholder="alasan" required>
								<button type="submit"  form="plustanggal" class="btn btn-success"  Value="Plus Tanggal"> Plus Tanggal </button>';
							}

							echo '
							</div>
						</div>
					</div>
				</div>
			</section>
			
			<div id="confirm-delivery" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
							<h4 class="modal-title" id="myModalLabel">Confirmation Delivery Airwaybill</h4>
						</div>
						<div class="modal-content">
							<div class="modal-body"></div>
						</div>
					</div>
				</div>
			</div>

			<div id="detail_modal_waktu" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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

			<div id="modal_sla_insert" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
							<h4 class="modal-title" id="myModalLabel">Insert SLA</h4>
						</div>
						<div class="modal-content">							
							<div class="modal-body"></div>
						</div>
					</div>
				</div>
			</div>
			
			<div id="switch-courier" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
							<h4 class="modal-title" id="myModalLabel">Switch Courier/Driver</h4>
						</div>
						<div class="modal-content">
							<div class="modal-body"></div>
						</div>
					</div>
				</div>
			</div>';
			
			break;
			
		}
	}else{
		echo akses_salah();
	}
}
?>


<script>
	function detailwaktu(clicked_id) {
		$("#detail_modal_waktu").modal('show');
		var detailawb = clicked_id;


		$.post('module/mod_pickup/pickup_action.php?module=pickup&act=status', {
			id: detailawb
			},
			function (html) {
				$("#detail_modal_waktu .modal-body").html(html);
			}
		);

		

	}
</script> 