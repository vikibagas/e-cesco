<?php
if(!isset($_SESSION)){
    session_start();
}

if (empty($_SESSION['username']) AND empty($_SESSION['password'])){
	header('location:../login.php');
}else{
	include "../../config/connection.php";
	include "../../config/library.php";
	include "../../config/encrypt.php";
	include "../../config/db_connect.php";
	include "../../../mobileapps/customer/courier/test_vdi_new.php";
	
	$module	= !empty($_GET['module']) ? $_GET['module'] : '';
	$act	= !empty($_GET['act']) ? $_GET['act'] : '';
	
	$aksi="module/mod_pickup/pickup_action.php";

	$key = 'asdf$1011';
	$encrypt = $_SESSION['id_user'];
	$id = base64_decrypt($encrypt,$key);
	$useraktif = mysqli_query($con, 'select * from user where id_user="'.$id.'"');
	$u = mysqli_fetch_assoc($useraktif);
	
	// load data
	if($module=='pickup' and $act=='data'){
		if($u['level']=='admin' || $u['tambahan_admin'] == '1' || $u['level'] == 'finance'){
		$table = 
<<<EOT
	(
	select 
		id_pengiriman,
		airwaybill_kode, 
		CONCAT(DATE_FORMAT(a.created_date,"%Y-%m-%d"),' ', 
		
			ifnull( DATE_FORMAT( DATE_ADD(a.created_date, INTERVAL case when a.service=1 then 0 when a.service=2 then 0 else b.sla_max end DAY), "%Y-%m-%d" ) ,'0' ), ' ', a.id_kota_asal, ' ',a.service, ' ',if(a.pickup_date_request is not null,a.pickup_date_request,0),' ',a.id_pengiriman,' ','$u[level]'
			) as created_date,
		x_namakustomer(id_kustomer) as nama_kustomer, 
		case when a.lokasi_asal=0 then a.kota_asal else x_namakotabesar(a.lokasi_asal) end as kota_asal,
		a.kota_tujuan as kota_tujuan,
		x_service(a.service) as service,

		CONCAT(id_pengiriman, ' ' ,DATE_FORMAT(a.created_date,"%Y-%m-%d"), ' ', a.id_kota_asal, ' ',a.service,' ', '$u[level]',' ',
			ifnull( DATE_FORMAT( DATE_ADD(a.created_date, INTERVAL case when a.service=1 then 0 when a.service=2 then 0 else b.sla_max end DAY), "%Y-%m-%d" ) ,'0' ),
			' ',if(a.pickup_date_request is not null,a.pickup_date_request,0),' ', if(a.service=6,a.lokasi_tujuan,a.id_kota_tujuan), ' ',a.id_kustomer,' ',ifnull(wakilkan_pickup,'N')
		) as assignment,

		ifnull( DATE_FORMAT( DATE_ADD(a.created_date, INTERVAL case when a.service=1 then 0 when a.service=2 then 0 else b.sla_max end DAY), "%Y-%m-%d" ) ,'0' ) as sla_max
	from pengiriman a 
	
	left join sla b on b.kota_asal = case when x_inter(a.id_kota_asal)='Y' then 9999 else a.id_kota_asal end  AND b.kota_tujuan= case when x_inter(a.id_kota_tujuan)='Y' then 9999 else a.id_kota_tujuan end AND b.service = a.service

	where status='1' and pickup='N' and pickup_request is not null and status!='7'  group by id_pengiriman
	) temp
EOT;
		}elseif( $u['level']=='intercity' or $u['level']=='outbound'){
		$table = 
<<<EOT
	(
	select 
		id_pengiriman,
		airwaybill_kode, 
		CONCAT(DATE_FORMAT(a.created_date,"%Y-%m-%d"),' ', 
		
			ifnull( DATE_FORMAT( DATE_ADD(a.created_date, INTERVAL case when a.service=1 then 0 when a.service=2 then 0 else b.sla_max end DAY), "%Y-%m-%d" ) ,'0' ), ' ', a.id_kota_asal, ' ',a.service, ' ',if(a.pickup_date_request is not null,a.pickup_date_request,0),' ',a.id_pengiriman,' ','$u[level]'
			) as created_date,
		x_namakustomer(id_kustomer) as nama_kustomer, 
		case when a.lokasi_asal=0 then a.kota_asal else x_namakotabesar(a.lokasi_asal) end as kota_asal,
		a.kota_tujuan as kota_tujuan,
		x_service(a.service) as service, 
		CONCAT(id_pengiriman, ' ' ,DATE_FORMAT(a.created_date,"%Y-%m-%d"), ' ', a.id_kota_asal, ' ',a.service,' ', '$u[level]',' ',
			ifnull( DATE_FORMAT( DATE_ADD(a.created_date, INTERVAL case when a.service=1 then 0 when a.service=2 then 0 else b.sla_max end DAY), "%Y-%m-%d" ) ,'0' ),
			' ',if(a.pickup_date_request is not null,a.pickup_date_request,0),' ',a.id_kota_tujuan, ' ',a.id_kustomer,' ',ifnull(wakilkan_pickup,'N')
		) as assignment,
		ifnull( DATE_FORMAT( DATE_ADD(a.created_date, INTERVAL case when a.service=1 then 0 when a.service=2 then 0 else b.sla_max end DAY), "%Y-%m-%d" ) ,'0' ) as sla_max
	from pengiriman a 
	
	left join sla b on b.kota_asal = case when x_inter(a.id_kota_asal)='Y' then 9999 else a.id_kota_asal end  AND b.kota_tujuan= case when x_inter(a.id_kota_tujuan)='Y' then 9999 else a.id_kota_tujuan end AND b.service = a.service

	where status='1' 
	and pickup='N' 
	and pickup_request is not null 
	and x_inter(a.id_kota_asal)='Y' 
	 
	and status!='7' 
	or(a.service = 6 and delivery='N' and status!='7' and pickup_request is not null  and pickup='N' 
	and x_inter(a.id_kota_asal)='Y' ) group by id_pengiriman
	) temp
EOT;
		}elseif($u['level']=='operator' or $u['level']=='domex'){
			if ($u['id_kustomer'] > 0 ){
							$table = 
<<<EOT
	(
	select 
		id_pengiriman,
		airwaybill_kode, 
		CONCAT(DATE_FORMAT(a.created_date,"%Y-%m-%d"),' ', 
		
			ifnull( DATE_FORMAT( DATE_ADD(a.created_date, INTERVAL case when a.service=1 then 0 when a.service=2 then 0 else b.sla_max end DAY), "%Y-%m-%d" ) ,'0' ), ' ', a.id_kota_asal, ' ',a.service, ' ',if(a.pickup_date_request is not null,a.pickup_date_request,0),' ',a.id_pengiriman,' ','$u[level]'
			) as created_date,
		x_namakustomer(id_kustomer) as nama_kustomer, 
		case when a.lokasi_asal=0 then a.kota_asal else x_namakotabesar(a.lokasi_asal) end as kota_asal,
		case when a.lokasi_tujuan=0 then a.kota_tujuan else x_namakotabesar(a.lokasi_tujuan) end as kota_tujuan,
		x_service(a.service) as service, 
		CONCAT(id_pengiriman, ' ' ,DATE_FORMAT(a.created_date,"%Y-%m-%d"), ' ', a.id_kota_asal, ' ',a.service,' ', '$u[level]',' ',
			ifnull( DATE_FORMAT( DATE_ADD(a.created_date, INTERVAL case when a.service=1 then 0 when a.service=2 then 0 else b.sla_max end DAY), "%Y-%m-%d" ) ,'0' ),
			' ',if(a.pickup_date_request is not null,a.pickup_date_request,0),' ', if(a.service=6,a.lokasi_tujuan,a.id_kota_tujuan), ' ',a.id_kustomer,' ',ifnull(wakilkan_pickup,'N')
		) as assignment,
		ifnull( DATE_FORMAT( DATE_ADD(a.created_date, INTERVAL case when a.service=1 then 0 when a.service=2 then 0 else b.sla_max end DAY), "%Y-%m-%d" ) ,'0' ) as sla_max
	from pengiriman a 
	
	left join sla b on b.kota_asal = case when x_inter(a.id_kota_asal)='Y' then 9999 else a.id_kota_asal end  AND b.kota_tujuan= case when x_inter(a.id_kota_tujuan)='Y' then 9999 else a.id_kota_tujuan end AND b.service = a.service

	where status='1' and pickup='N' and pickup_request is not null and x_intercity(lokasi_asal)='Y' and status!='7' and id_kustomer = $u[id_kustomer] group by id_pengiriman
	) temp
EOT;
			}
			else{
				$table = 
<<<EOT
	(
	select 
		id_pengiriman,
		airwaybill_kode, 
		CONCAT(DATE_FORMAT(a.created_date,"%Y-%m-%d"),' ', 
		
			ifnull( DATE_FORMAT( DATE_ADD(a.created_date, INTERVAL case when a.service=1 then 0 when a.service=2 then 0 else b.sla_max end DAY), "%Y-%m-%d" ) ,'0' ), ' ', a.id_kota_asal, ' ',a.service, ' ',if(a.pickup_date_request is not null,a.pickup_date_request,0),' ',a.id_pengiriman,' ','$u[level]'
			) as created_date,
		x_namakustomer(id_kustomer) as nama_kustomer, 
		case when a.lokasi_asal=0 then a.kota_asal else x_namakotabesar(a.lokasi_asal) end as kota_asal,
		case when a.lokasi_tujuan=0 then a.kota_tujuan else x_namakotabesar(a.lokasi_tujuan) end as kota_tujuan,
		x_service(a.service) as service, 
		CONCAT(id_pengiriman, ' ' ,DATE_FORMAT(a.created_date,"%Y-%m-%d"), ' ', a.id_kota_asal, ' ',a.service,' ', '$u[level]',' ',
			ifnull( DATE_FORMAT( DATE_ADD(a.created_date, INTERVAL case when a.service=1 then 0 when a.service=2 then 0 else b.sla_max end DAY), "%Y-%m-%d" ) ,'0' ),
			' ',if(a.pickup_date_request is not null,a.pickup_date_request,0),' ', if(a.service=6,a.lokasi_tujuan,a.id_kota_tujuan), ' ',a.id_kustomer,' ',ifnull(wakilkan_pickup,'N')
		) as assignment,
		ifnull( DATE_FORMAT( DATE_ADD(a.created_date, INTERVAL case when a.service=1 then 0 when a.service=2 then 0 else b.sla_max end DAY), "%Y-%m-%d" ) ,'0' ) as sla_max
	from pengiriman a 
	
	left join sla b on b.kota_asal = case when x_inter(a.id_kota_asal)='Y' then 9999 else a.id_kota_asal end  AND b.kota_tujuan= case when x_inter(a.id_kota_tujuan)='Y' then 9999 else a.id_kota_tujuan end AND b.service = a.service

	where status='1' and pickup='N' and pickup_request is not null and x_intercity(lokasi_asal)='Y' and status!='7' group by id_pengiriman
	) temp
EOT;
			}
		
		}elseif($u['level']=='admin_network'){
		$table = 
<<<EOT
	(
	select 
		id_pengiriman,
		airwaybill_kode, 
		CONCAT(DATE_FORMAT(a.created_date,"%Y-%m-%d"),' ', 
		
			ifnull( DATE_FORMAT( DATE_ADD(a.created_date, INTERVAL case when a.service=1 then 0 when a.service=2 then 0 else b.sla_max end DAY), "%Y-%m-%d" ) ,'0' ), ' ', a.id_kota_asal, ' ',a.service, ' ',if(a.pickup_date_request is not null,a.pickup_date_request,0),' ',a.id_pengiriman,' ','$u[level]'
			) as created_date,
		x_namakustomer(id_kustomer) as nama_kustomer, 
		case when a.lokasi_asal=0 then a.kota_asal else x_namakotabesar(a.lokasi_asal) end as kota_asal,
		case when a.lokasi_tujuan=0 then a.kota_tujuan else x_namakotabesar(a.lokasi_tujuan) end as kota_tujuan,
		x_service(a.service) as service, 
		CONCAT(id_pengiriman, ' ' ,DATE_FORMAT(a.created_date,"%Y-%m-%d"), ' ', a.id_kota_asal, ' ',a.service,' ', '$u[level]',' ',
			ifnull( DATE_FORMAT( DATE_ADD(a.created_date, INTERVAL case when a.service=1 then 0 when a.service=2 then 0 else b.sla_max end DAY), "%Y-%m-%d" ) ,'0' ),
			' ',if(a.pickup_date_request is not null,a.pickup_date_request,0),' ', if(a.service=6,a.lokasi_tujuan,a.id_kota_tujuan), ' ',a.id_kustomer,' ',ifnull(wakilkan_pickup,'N')
		) as assignment,
		ifnull( DATE_FORMAT( DATE_ADD(a.created_date, INTERVAL case when a.service=1 then 0 when a.service=2 then 0 else b.sla_max end DAY), "%Y-%m-%d" ) ,'0' ) as sla_max
	from pengiriman a 
	
	left join sla b on b.kota_asal = case when x_inter(a.id_kota_asal)='Y' then 9999 else a.id_kota_asal end  AND b.kota_tujuan= case when x_inter(a.id_kota_tujuan)='Y' then 9999 else a.id_kota_tujuan end AND b.service = a.service

			
			
	 where status='1' and pickup='N' and pickup_request is not null 

	



	  and a.airwaybill_kode not like '002-%'

	 and status!='7'  or (status='1' and pickup='N' and pickup_request is not null  and a.service=4) group by id_pengiriman
	) temp
EOT;
		}

		elseif($u['level']=='network'){
		$table = 
<<<EOT
	(
	select 
		id_pengiriman,
		airwaybill_kode, 
		CONCAT(DATE_FORMAT(a.created_date,"%Y-%m-%d"),' ', 
		
			ifnull( DATE_FORMAT( DATE_ADD(a.created_date, INTERVAL case when a.service=1 then 0 when a.service=2 then 0 else b.sla_max end DAY), "%Y-%m-%d" ) ,'0' ), ' ', a.id_kota_asal, ' ',a.service, ' ',if(a.pickup_date_request is not null,a.pickup_date_request,0),' ',a.id_pengiriman,' ','$u[level]'
			) as created_date,
		x_namakustomer(id_kustomer) as nama_kustomer, 
		case when a.lokasi_asal=0 then a.kota_asal else x_namakotabesar(a.lokasi_asal) end as kota_asal,
		case when a.lokasi_tujuan=0 then a.kota_tujuan else x_namakotabesar(a.lokasi_tujuan) end as kota_tujuan,
		x_service(a.service) as service, 
		CONCAT(id_pengiriman, ' ' ,DATE_FORMAT(a.created_date,"%Y-%m-%d"), ' ', a.id_kota_asal, ' ',a.service,' ', '$u[level]',' ',
			ifnull( DATE_FORMAT( DATE_ADD(a.created_date, INTERVAL case when a.service=1 then 0 when a.service=2 then 0 else b.sla_max end DAY), "%Y-%m-%d" ) ,'0' ),
			' ',if(a.pickup_date_request is not null,a.pickup_date_request,0),' ', if(a.service=6,a.lokasi_tujuan,a.id_kota_tujuan), ' ',a.id_kustomer,' ',ifnull(wakilkan_pickup,'N')
		) as assignment,
		ifnull( DATE_FORMAT( DATE_ADD(a.created_date, INTERVAL case when a.service=1 then 0 when a.service=2 then 0 else b.sla_max end DAY), "%Y-%m-%d" ) ,'0' ) as sla_max
	from pengiriman a 
	
	left join sla b on b.kota_asal = case when x_inter(a.id_kota_asal)='Y' then 9999 else a.id_kota_asal end  AND b.kota_tujuan= case when x_inter(a.id_kota_tujuan)='Y' then 9999 else a.id_kota_tujuan end AND b.service = a.service

			left join kota c on c.id_kota=a.lokasi_tujuan
			left join regencies d on d.id=a.id_kota_tujuan
	 where status='1' and pickup='N' and pickup_request is not null 

	 and if(a.id_kota_asal>0,x_inter(a.id_kota_asal)='Y',x_intercity(a.lokasi_asal)='Y') 

		and if(a.id_kota_tujuan>0,x_inter(a.id_kota_tujuan)!='Y',x_intercity(a.lokasi_tujuan)!='Y') 

	 and if(id_kota_asal>0,d.province_id in ($u[area_prov]),c.kode_pulau in ($u[area_pulau])) and status!='7' and (a.service in ($u[handle_service]) ) or (status='1' and pickup='N' and pickup_request is not null  and a.service=4) group by id_pengiriman
	) temp
EOT;
		}
		elseif($u['level']=='inbound'){
		$table = 
<<<EOT
	(
	select 
		id_pengiriman,
		airwaybill_kode, 
		CONCAT(DATE_FORMAT(a.created_date,"%Y-%m-%d"),' ', 
		
			ifnull( DATE_FORMAT( DATE_ADD(a.created_date, INTERVAL case when a.service=1 then 0 when a.service=2 then 0 else b.sla_max end DAY), "%Y-%m-%d" ) ,'0' ), ' ', a.id_kota_asal, ' ',a.service, ' ',if(a.pickup_date_request is not null,a.pickup_date_request,0),' ',a.id_pengiriman,' ','$u[level]'
			) as created_date,
		x_namakustomer(id_kustomer) as nama_kustomer, 
		case when a.lokasi_asal=0 then a.kota_asal else x_namakotabesar(a.lokasi_asal) end as kota_asal,
		case when a.lokasi_tujuan=0 then a.kota_tujuan else x_namakotabesar(a.lokasi_tujuan) end as kota_tujuan,
		x_service(a.service) as service, 
		CONCAT(id_pengiriman, ' ' ,DATE_FORMAT(a.created_date,"%Y-%m-%d"), ' ', a.id_kota_asal, ' ',a.service,' ', '$u[level]',' ',
			ifnull( DATE_FORMAT( DATE_ADD(a.created_date, INTERVAL case when a.service=1 then 0 when a.service=2 then 0 else b.sla_max end DAY), "%Y-%m-%d" ) ,'0' ),
			' ',if(a.pickup_date_request is not null,a.pickup_date_request,0),' ', if(a.service=6,a.lokasi_tujuan,a.id_kota_tujuan), ' ',a.id_kustomer,' ',ifnull(wakilkan_pickup,'N')
		) as assignment,
		ifnull( DATE_FORMAT( DATE_ADD(a.created_date, INTERVAL case when a.service=1 then 0 when a.service=2 then 0 else b.sla_max end DAY), "%Y-%m-%d" ) ,'0' ) as sla_max
	from pengiriman a 
	
	left join sla b on b.kota_asal = case when x_inter(a.id_kota_asal)='Y' then 9999 else a.id_kota_asal end  AND b.kota_tujuan= case when x_inter(a.id_kota_tujuan)='Y' then 9999 else a.id_kota_tujuan end AND b.service = a.service

			left join kota c on c.id_kota=a.lokasi_asal
			left join regencies d on d.id=a.id_kota_asal
	 where status='1' and pickup='N' and pickup_request is not null  and if(id_kota_asal>0,d.province_id in ($u[area_prov]),c.kode_pulau in ($u[area_pulau])) and status!='7' and if(id_kota_asal>0,x_inter(a.id_kota_asal)!='Y',x_intercity(a.lokasi_asal)!='Y') group by id_pengiriman
	) temp
EOT;
		}
		elseif($u['level']=='logistics'){
		$table = 
<<<EOT
	(
	select 
		id_pengiriman,
		airwaybill_kode, 
		CONCAT(DATE_FORMAT(a.created_date,"%Y-%m-%d"),' ', 
		
			ifnull( DATE_FORMAT( DATE_ADD(a.created_date, INTERVAL case when a.service=1 then 0 when a.service=2 then 0 else b.sla_max end DAY), "%Y-%m-%d" ) ,'0' ), ' ', a.id_kota_asal, ' ',a.service, ' ',if(a.pickup_date_request is not null,a.pickup_date_request,0),' ',a.id_pengiriman,' ','$u[level]'
			) as created_date,
		x_namakustomer(id_kustomer) as nama_kustomer, 
		case when a.lokasi_asal=0 then a.kota_asal else x_namakotabesar(a.lokasi_asal) end as kota_asal,
		case when a.lokasi_tujuan=0 then a.kota_tujuan else x_namakotabesar(a.lokasi_tujuan) end as kota_tujuan,
		x_service(a.service) as service, 
		CONCAT(id_pengiriman, ' ' ,DATE_FORMAT(a.created_date,"%Y-%m-%d"), ' ', a.id_kota_asal, ' ',a.service,' ', '$u[level]',' ',
			ifnull( DATE_FORMAT( DATE_ADD(a.created_date, INTERVAL case when a.service=1 then 0 when a.service=2 then 0 else b.sla_max end DAY), "%Y-%m-%d" ) ,'0' ),
			' ',if(a.pickup_date_request is not null,a.pickup_date_request,0),' ', if(a.service=6,a.lokasi_tujuan,a.id_kota_tujuan), ' ',a.id_kustomer,' ',ifnull(wakilkan_pickup,'N')
		) as assignment,
		ifnull( DATE_FORMAT( DATE_ADD(a.created_date, INTERVAL case when a.service=1 then 0 when a.service=2 then 0 else b.sla_max end DAY), "%Y-%m-%d" ) ,'0' ) as sla_max
	from pengiriman a 
	
	left join sla b on b.kota_asal = case when x_inter(a.id_kota_asal)='Y' then 9999 else a.id_kota_asal end  AND b.kota_tujuan= case when x_inter(a.id_kota_tujuan)='Y' then 9999 else a.id_kota_tujuan end AND b.service = a.service

	where status='1' and pickup='N' and a.service in (4,5,10,11) and status!='7'
	) temp
EOT;
		}elseif($u['level']=='kurir' or $u['level']=='driver'){
		$table = 
<<<EOT
	(
	select 
		id_pengiriman,
		airwaybill_kode, 
		CONCAT(DATE_FORMAT(a.created_date,"%Y-%m-%d"),' ', 
		
			ifnull( DATE_FORMAT( DATE_ADD(a.created_date, INTERVAL case when a.service=1 then 0 when a.service=2 then 0 else b.sla_max end DAY), "%Y-%m-%d" ) ,'0' ), ' ', a.id_kota_asal, ' ',a.service, ' ',if(a.pickup_date_request is not null,a.pickup_date_request,0),' ',a.id_pengiriman,' ','$u[level]'
			) as created_date,
		x_namakustomer(id_kustomer) as nama_kustomer, 
		case when a.lokasi_asal=0 then a.kota_asal else x_namakotabesar(a.lokasi_asal) end as kota_asal,
		case when a.lokasi_tujuan=0 then a.kota_tujuan else x_namakotabesar(a.lokasi_tujuan) end as kota_tujuan,
		x_service(a.service) as service, 
		CONCAT(id_pengiriman, ' ' ,DATE_FORMAT(a.created_date,"%Y-%m-%d"), ' ', a.id_kota_asal, ' ',a.service,' ', '$u[level]',' ',
			ifnull( DATE_FORMAT( DATE_ADD(a.created_date, INTERVAL case when a.service=1 then 0 when a.service=2 then 0 else b.sla_max end DAY), "%Y-%m-%d" ) ,'0' ),
			' ',if(a.pickup_date_request is not null,a.pickup_date_request,0),' ', if(a.service=6,a.lokasi_tujuan,a.id_kota_tujuan), ' ',a.id_kustomer,' ',ifnull(wakilkan_pickup,'N')
		) as assignment,
		ifnull( DATE_FORMAT( DATE_ADD(a.created_date, INTERVAL case when a.service=1 then 0 when a.service=2 then 0 else b.sla_max end DAY), "%Y-%m-%d" ) ,'0' ) as sla_max
	from pengiriman a 
	
	left join sla b on b.kota_asal = case when x_inter(a.id_kota_asal)='Y' then 9999 else a.id_kota_asal end  AND b.kota_tujuan= case when x_inter(a.id_kota_tujuan)='Y' then 9999 else a.id_kota_tujuan end AND b.service = a.service

	where status='1' and pickup='N' and pickup_request=$u[id_user] and status!='7'
	) temp
EOT;
		}elseif($u['level']=='agent' || $u['level']=='kurir_agent'){
		$agent = mysqli_query($con, 'select id_kota, id_kota_baru from cabang where id_cabang="'.$u['id_cabang'].'"');
		$a = mysqli_fetch_assoc($agent);
		$table = 
<<<EOT
	(
	select 
		id_pengiriman,
		airwaybill_kode, 
		CONCAT(DATE_FORMAT(a.created_date,"%Y-%m-%d"),' ', 
		
			ifnull( DATE_FORMAT( DATE_ADD(a.created_date, INTERVAL case when a.service=1 then 0 when a.service=2 then 0 else b.sla_max end DAY), "%Y-%m-%d" ) ,'0' ), ' ', a.id_kota_asal, ' ',a.service, ' ',if(a.pickup_date_request is not null,a.pickup_date_request,0),' ',a.id_pengiriman,' ','$u[level]'
			) as created_date,
		x_namakustomer(id_kustomer) as nama_kustomer, 
		case when a.lokasi_asal=0 then a.kota_asal else x_namakotabesar(a.lokasi_asal) end as kota_asal,
		case when a.lokasi_tujuan=0 then a.kota_tujuan else x_namakotabesar(a.lokasi_tujuan) end as kota_tujuan,
		x_service(a.service) as service, 
		CONCAT(id_pengiriman, ' ' ,DATE_FORMAT(a.created_date,"%Y-%m-%d"), ' ', a.id_kota_asal, ' ',a.service,' ', '$u[level]',' ',
			ifnull( DATE_FORMAT( DATE_ADD(a.created_date, INTERVAL case when a.service=1 then 0 when a.service=2 then 0 else b.sla_max end DAY), "%Y-%m-%d" ) ,'0' ),
			' ',if(a.pickup_date_request is not null,a.pickup_date_request,0),' ', if(a.service=6,a.lokasi_tujuan,a.id_kota_tujuan), ' ',a.id_kustomer,' ',ifnull(wakilkan_pickup,'N')
		) as assignment,
		ifnull( DATE_FORMAT( DATE_ADD(a.created_date, INTERVAL case when a.service=1 then 0 when a.service=2 then 0 else b.sla_max end DAY), "%Y-%m-%d" ) ,'0' ) as sla_max
	from pengiriman a 
	
	left join sla b on b.kota_asal = case when x_inter(a.id_kota_asal)='Y' then 9999 else a.id_kota_asal end  AND b.kota_tujuan= case when x_inter(a.id_kota_tujuan)='Y' then 9999 else a.id_kota_tujuan end AND b.service = a.service

	left join kota c on c.id_kota=a.lokasi_asal 

	inner join regencies d on d.id=a.id_kota_asal

	left join provinces e on e.id=d.province_id	

	where a.status='1' and pickup='N' and x_inter(a.id_kota_asal)!='Y' 

	and if( a.id_kota_asal>0, (a.id_kota_asal=$a[id_kota_baru] or ifnull(d.hub,e.ibu_kota) =x_hubnew(a.id_kota_asal)),(a.lokasi_asal=$a[id_kota] or c.hub=$a[id_kota])) 

	and status!='7' and pickup_request in (select id_user from user where id_cabang = $u[id_cabang])  
	) temp
EOT;
		}
		
		$primaryKey = 'id_pengiriman';
		
		if($u['level']=='operator' ){
		$columns = array(
			array('db'		=> 'airwaybill_kode',
					'dt'	=> 0,
					'formatter'	=> function($d,$row){
						return '<td><center>'.$d.'</center></td>';
					}
			),
			array('db'	=> 'created_date',
					'dt'		=> 1,
					'formatter' => function( $d, $row ) {
						return date( 'd-m-Y H:i', strtotime($d));
					}
			),
			array('db'			=> 'nama_kustomer', 'dt'=> 2 ),
			array('db'			=> 'kota_asal',
					'dt'		=> 3,
					'formatter' => function($d,$row){
						if($d=='Jakarta Selatan' || $d=='Jakarta Timur' || $d=='Jakarta Barat' || $d=='Jakarta Utara' || $d=='Jakarta Pusat'){
							$kota_asal = 'Jakarta';
						}else{
							$kota_asal = $d;
						}
						return '<td>'.strtoupper($kota_asal).'</td>';
					}
			),
			array('db'			=> 'kota_tujuan',
					'dt'		=> 4,
					'formatter'	=> function($d,$row){
						if($d=='Jakarta Selatan' || $d=='Jakarta Timur' || $d=='Jakarta Barat' || $d=='Jakarta Utara' || $d=='Jakarta Pusat'){
							$kota_tujuan = 'Jakarta';
						}else{
							$kota_tujuan = $d;
						}
						return '<td>'.strtoupper($kota_tujuan).'</td>';
					}
			),
			array( 'db' => 'service', 'dt'=> 5 ),
			array( 'db' => 'sla_max', 'dt'=> 5 ),

			array(
				  'db'        => 'id_pengiriman',
				  'dt'        => 6,
				  'formatter' => function( $d, $row ) {
					$key = "asdf$1011";
										
					return '<td><center><button class="btn btn-xs btn-warning open_switch_pickup" data-id='.base64_encrypt($d,$key).'"><i class="fa fa-exchange"></i></button></center></td>';
				  }
			)

		);
		}else{
		$columns = array(
			array('db'		=> 'airwaybill_kode',
					'dt'	=> 0,
					'formatter'	=> function($d,$row){
						return '<td><center>'.$d.'</center></td>';
					}
			),
			array('db'	=> 'created_date',
					'dt'		=> 1,
					'formatter' => function( $d, $row ) {



						$pieces = explode(' ', $d);

						//$booking_date = $pieces[0];

						$kota_asal = $pieces[2];

						$pickup_date_request = $pieces[4];
						$level = $pieces[6];
						$id_pengiriman = $pieces[5];

						$created_booking = $pieces[0];

						$db_host = "127.0.0.1";
						$db_user = "cesco_user";
						$db_pass = "c1l4ndak33032";
						$db_name = "cesco_logistics";

						$db_akuntansi = "cesco_finance";

						$con = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

						$cekkota 		= mysqli_query($con, "select x_hubnew(id) as hub,province_id, intercity from regencies where id=$kota_asal");

						$r= mysqli_fetch_assoc($cekkota);

						$done = strtotime($pieces[0]);
										$dtwo = date('l',$done);
										$dthree = strtolower($dtwo);

										if($dthree == "sunday" )
										{
								            $booking_date = date('Y-m-d',strtotime($pieces[0].'+1 day'));
								        }
								        else{
								        	$booking_date = $pieces[0];
								        } 					

							if ($pieces[3] >= 3){
								if($r['intercity'] == 'Y'){
									if($r['province_id'] == '31'){
										$sla_booking = $booking_date;
									}else{
										if($kota_asal == 3215){
											$sla_booking = date('Y-m-d',strtotime($booking_date.'+2 day'));	
										}else{
											$sla_booking = date('Y-m-d',strtotime($booking_date.'+1 day'));	
										}
										
									}
									//$sla = date('Y-m-d',strtotime($sla_booking));

								}else{
									if($r['hub'] == $kota_asal){
										$sla_booking = $booking_date;
										$sla_booking = date('Y-m-d',strtotime($booking_date.'+1 day'));	
									}else{
										$sla_booking = date('Y-m-d',strtotime($booking_date.'+2 day'));
										//$dua = 2;	
									}

									
								}
							}else{
								$sla_booking = $booking_date;
								//$sla = date('Y-m-d',strtotime($sla_booking));
							}

							$d1 = strtotime($sla_booking);
										$d2 = date('l',$d1);
										$dt3 = strtolower($d2);

								   		if($dt3 == "sunday")
										{
								            $sla = date('Y-m-d',strtotime($sla_booking.'+1 day'));
								        }else{
								        	$sla = date('Y-m-d',strtotime($sla_booking));
								        }
						
						
						//$sla_booking = date('Y-m-d',strtotime($pieces[0].'+1 day'));
						//$sla =  $pieces[1];

						

				        //$sla = date('Y-m-d',strtotime($sla_booking));

				        if ($pickup_date_request != 0 and $pickup_date_request > $sla){
							$sla = $pickup_date_request;
							$tanda = '*';
						}else {
							$sla = $sla;
							$tanda = '';
						}

						$date_now =  date("Y-m-d");

						if($sla != '1970-01-01'){
							
							if ($date_now > $sla){

								$created_date = $created_booking.' - '. $sla.' <button class="btn btn-xs btn-danger" title="Terlambat!"><i class="fa fa-exclamation-circle"></i> </button>';
							}else{
								$created_date =  $created_booking.' - '. $sla;
							}
						}else{
							$created_date =  $created_booking;
						}
						if($level == 'admin'){
							$pluscheck = '<input type="checkbox" form="plustanggal" name="selectawb[]" value="'.$id_pengiriman.'">';
						}else{
							$pluscheck = '';
						}
						$btn = ' <button class="btn btn-xs btn-primary detailawb" title="detail" onclick="detailwaktu(this.id)" id="'.$id_pengiriman.'"><i class="fa fa-clock-o"></i> </button>';
						
						return $created_date.$tanda.$pluscheck.$btn;
					}
			),
			array('db'			=> 'nama_kustomer', 'dt'=> 2 ),
			array('db'			=> 'kota_asal',
					'dt'		=> 3,
					'formatter' => function($d,$row){
						if($d=='Jakarta Selatan' || $d=='Jakarta Timur' || $d=='Jakarta Barat' || $d=='Jakarta Utara' || $d=='Jakarta Pusat'){
							$kota_asal = 'Jakarta';
						}else{
							$kota_asal = $d;
						}
						return '<td>'.strtoupper($kota_asal).'</td>';
					}
			),
			array('db'			=> 'kota_tujuan',
					'dt'		=> 4,
					'formatter'	=> function($d,$row){
						if($d=='Jakarta Selatan' || $d=='Jakarta Timur' || $d=='Jakarta Barat' || $d=='Jakarta Utara' || $d=='Jakarta Pusat'){
							$kota_tujuan = 'Jakarta';
						}else{
							$kota_tujuan = $d;
						}
						return '<td>'.strtoupper($kota_tujuan).'</td>';
					}
			),
			array( 'db' => 'service', 'dt'=> 5 ),
			array(
				  'db'        => 'assignment',
				  'dt'        => 6,
				  'formatter' => function( $d, $row ) {
					//$key = "asdf$1011";
										
					//return '<td><center><button class="btn btn-xs btn-success" onclick="window.location.href = \'?module=pickup&act=view&id='.base64_encrypt($d,$key).'\';"><i class="fa fa-truck"></i> Pickup</button> <button class="btn btn-xs btn-warning open_switch_pickup" data-id='.base64_encrypt($d,$key).'"><i class="fa fa-exchange"></i></button></center></td>';

					$date_now = date("Y-m-d");
					$pieces = explode(' ', $d);

					$id_pengiriman = $pieces[0];

					//mulai
					$booking_date = $pieces[1];
					$kota_asal = $pieces[2];

					$id_cabang_pickup = $pieces[5];

					$pickup_date_request = $pieces[6];

					$service = $pieces[3];
					$kota_tujuan = $pieces[7];
					$id_kustomer = $pieces[8];
					$wakilkan_pickup = $pieces[9];



						$db_host = "127.0.0.1";
						$db_user = "cesco_user";
						$db_pass = "c1l4ndak33032";
						$db_name = "cesco_logistics";

						$db_akuntansi = "cesco_finance";

						$con = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

						$cekkota 		= mysqli_query($con, "select x_hubnew(id) as hub,province_id, intercity from regencies where id=$kota_asal");

						$r= mysqli_fetch_assoc($cekkota);


						$done = strtotime($pieces[1]);
										$dtwo = date('l',$done);
										$dthree = strtolower($dtwo);

										if($dthree == "sunday" )
										{
								            $booking_date = date('Y-m-d',strtotime($pieces[1].'+1 day'));
								        }
								        else{
								        	$booking_date = $pieces[1];
								        } 
						

							if ($pieces[3] >= 3){
								if($r['intercity'] == 'Y'){
									if($r['province_id'] == '31'){
										$sla_booking = $booking_date;
									}else{
										if($kota_asal == 3215){
											$sla_booking = date('Y-m-d',strtotime($booking_date.'+2 day'));	
										}else{
											$sla_booking = date('Y-m-d',strtotime($booking_date.'+1 day'));	
										}
									}
								//	$sla = date('Y-m-d',strtotime($sla_booking));

								}else{
									if($r['hub'] == $kota_asal){
										$sla_booking = $booking_date;
										//$sla = date('Y-m-d',strtotime($sla_booking));
									}else{
										$sla_booking = date('Y-m-d',strtotime($booking_date.'+2 day'));
										
									}

									
								}
							}else{
								$sla_booking = $booking_date;
								//$sla = date('Y-m-d',strtotime($sla_booking));
							}
						
						$d1 = strtotime($sla_booking);
										$d2 = date('l',$d1);
										$dt3 = strtolower($d2);

										if($dt3 == "sunday")
										{
								            $sla = date('Y-m-d',strtotime($sla_booking.'+1 day'));
								        }else{
								        	$sla = date('Y-m-d',strtotime($sla_booking));
								        }


						// id 0 booking 1 lokasi asal2 service3
					//ahir





					/*$d1 = strtotime($sla_booking);
						$d2 = date('l',$d1);
						$dt3 = strtolower($d2);

						if($dt3 == "saturday" )
						{
				            $sla = date('Y-m-d',strtotime($sla_booking.'+2 day'));
				        } 
				   		elseif($dt3 == "sunday")
						{
				            $sla = date('Y-m-d',strtotime($sla_booking.'+1 day'));
				        }else{
				        	$sla = date('Y-m-d',strtotime($sla_booking));
				        }*/

						//$booking_date = $pieces[2];

						//$sla = date('Y-m-d',strtotime($sla_booking));

				        if ($pickup_date_request != 0 and $pickup_date_request > $sla){
							$sla = $pickup_date_request;
						}else {
							$sla = $sla;
						}

					$key = "asdf$1011";
					$level = $pieces[4];


					$cek_rate		= mysqli_query($con, "select id  from rate_customer where id_kustomer = $id_kustomer AND id_pelayanan=$service AND id_lokasi_asal=$kota_asal AND id_lokasi_tujuan = $kota_tujuan ");

						$rc= mysqli_num_rows($cek_rate);

					if($rc > 0 || $id_kustomer == 79 || $id_kustomer == 80 || $id_kustomer == 81){

						if ($id_cabang_pickup == 11 || $id_cabang_pickup == 16  || $level == 'finance' ){

							$assignment = '-';
						}else{
							if($sla != '1970-01-01'){
								if($date_now > $sla){
									//if($level == 'admin'){aslinya ini
									if($level != 'finance'){
									$assignment = '
									
									<button class="btn btn-xs btn-success" onclick="window.location.href = \'?module=pickup&act=view&sla=1&id='.base64_encrypt($d,$key).'\';"><i class="fa fa-truck"></i> Pickup</button> 

									<button class="btn btn-xs btn-warning open_switch_pickup" data-id='.base64_encrypt($d,$key).'"><i class="fa fa-exchange"></i></button>';
									}elseif($level == 'kurir' || $level == 'driver' || $level == 'agent' || $level == 'kurir_agent'){
										$assignment = '
										
										<button class="btn btn-xs btn-success" onclick="window.location.href = \'?module=pickup&act=view&sla=1&id='.base64_encrypt($d,$key).'\';"><i class="fa fa-truck"></i> Pickup</button> ';
									}

									else{
										$assignment = '<button class="btn btn-xs btn-warning open_switch_pickup" data-id='.base64_encrypt($d,$key).'"><i class="fa fa-exchange"></i></button>';
									}						
								}else{
									//if($level == 'admin'){
									if($level != 'finance'){
									$assignment = '
									
									<button class="btn btn-xs btn-success" onclick="window.location.href = \'?module=pickup&act=view&sla=0&id='.base64_encrypt($d,$key).'\';"><i class="fa fa-truck"></i> Pickup</button> 

									<button class="btn btn-xs btn-warning open_switch_pickup" data-id='.base64_encrypt($d,$key).'"><i class="fa fa-exchange"></i></button>';
									}elseif($level == 'kurir' || $level == 'driver' || $level == 'agent' || $level == 'kurir_agent'){
										$assignment = '
										
										<button class="btn btn-xs btn-success" onclick="window.location.href = \'?module=pickup&act=view&sla=0&id='.base64_encrypt($d,$key).'\';"><i class="fa fa-truck"></i> Pickup</button> ';
									}

									else{
										$assignment = '<button class="btn btn-xs btn-warning open_switch_pickup" data-id='.base64_encrypt($d,$key).'"><i class="fa fa-exchange"></i></button>';
									}						
								}
							} 
							else{
								//if($level == 'admin'){
								if($level != 'finance'){
									$assignment = '
									
									<button class="btn btn-xs btn-success" onclick="window.location.href = \'?module=pickup&act=view&sla=1&id='.base64_encrypt($d,$key).'\';"><i class="fa fa-truck"></i> Pickup</button> 

									<button class="btn btn-xs btn-warning open_switch_pickup" data-id='.base64_encrypt($d,$key).'"><i class="fa fa-exchange"></i></button>';
								}elseif($level == 'kurir' || $level == 'driver' || $level == 'agent' || $level == 'kurir_agent'){
									$assignment = '
									
									<button class="btn btn-xs btn-success" onclick="window.location.href = \'?module=pickup&act=view&sla=1&id='.base64_encrypt($d,$key).'\';"><i class="fa fa-truck"></i> Pickup</button> ';
								}

								else{
									$assignment = '<button class="btn btn-xs btn-warning open_switch_pickup" data-id='.base64_encrypt($d,$key).'"><i class="fa fa-exchange"></i></button>';
								}
														
							}
						}
					}else{
						$assignment = '<button class="btn btn-xs btn-success custom_price_insert_pickup"  data-target="#modal_price_insert_pickup" data-id="'.$id_pengiriman.'" title="Belum ada Tarif Customer, Hubungi Finance!"><i class="fa fa-exclamation-circle"></i> </button>';
					}
					//button pickup

					if($level == 'admin'){
							
						if($wakilkan_pickup == 'Y'){
							$button_wakilkan = '';
						}else{
							$button_wakilkan = '<button class="btn btn-xs btn-primary" title="wakilkan" onclick="window.location.href = \'module/mod_pickup/pickup_action.php?module=pickup&act=wakilkan&id_pengiriman='.base64_encrypt($id_pengiriman,$key).'\';" >W</button>';
						}

					}else{
						if($wakilkan_pickup == 'Y'){
							$button_wakilkan = '<button class="btn btn-xs btn-success" onclick="window.location.href = \'?module=pickup&act=view&sla=1&id='.base64_encrypt($d,$key).'\';"><i class="fa fa-truck"></i> Pickup</button> ';
							
						}else{
							$button_wakilkan = '';
						}
					}
										

					return '<td><center>'.$assignment.$button_wakilkan.'</center></td>';
					
					


				}
			)

		);
		}

		/*$sql_details = array(
			'user' => 'cesco_user',
			'pass' => 'srnGRkhl3c',
			'db'   => 'cesco_logistics',
			'host' => '127.0.0.1'
		);*/
		require( '../../config/ssp.class.php' );

		echo json_encode(
			SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns )
		);
		
	// update pickup
	}elseif ($module=='pickup' AND $act=='update'){

		//$reason_pilih = mysqli_real_escape_string($con, $_POST['reason']);
			//$reason_ketik = mysqli_real_escape_string($con, $_POST['reason_manual']);

			$reason_pilih = isset($_POST['reason']) ? $_POST['reason'] : "";
			$reason_ketik = isset($_POST['reason_manual']) ? $_POST['reason_manual'] : "";
			$pickup_by = mysqli_real_escape_string($con, $_POST['pickup_by']);

			$q 		= mysqli_query($con, "select 
				airwaybill_kode, loc_code, kecamatan_asal, kota_asal,x_user(".$pickup_by.") as nama_kurir,service,id_kustomer,collie,berat_aktual

				from pengiriman where id_pengiriman= '$_POST[id]'
				
				");
			$row= mysqli_fetch_assoc($q);

			$awb = $row['airwaybill_kode'];
			$service = $row['service'];
			if($service == 1){
				$servicevdi = 2;
			}elseif($service == 2){
				$servicevdi = 1;
			}elseif($service == 3){
				$servicevdi = 0;
			}elseif($service == 5){
				$servicevdi = 3;
			}
			$id_kustomer = $row['id_kustomer'];


			
			
		if($u['level']=='admin' OR $u['level']=='network' OR $u['level']=='admin_network' OR $u['level']=='inbound' OR $u['level']=='logistics' OR $u['level']=='domex' OR $u['level']=='agent' OR $u['level']=='intercity'){
			$pickup_by = mysqli_real_escape_string($con, $_POST['pickup_by']);
			

			$query 		= mysqli_query($con, "select 
				id_cabang,namalengkap from user where id_user='$pickup_by'
				
				");
			$r= mysqli_fetch_assoc($query);

			if($reason_pilih < 3){
				if($r['id_cabang'] == 39){
					$diskon = 15;
				}else{
					$diskon = 15;
				}
				
			}else{
				$diskon = '0';
			}
			$tanggal_form = mysqli_real_escape_string($con, $_POST['pickup_date']);
			$tanggal = jin_date_sql($tanggal_form);
			$jam = mysqli_real_escape_string($con, $_POST['pickup_time']);
			$kondisi_packing = addslashes($_POST['kondisi_packing']);
  			$kondisi_barang = addslashes($_POST['kondisi_barang']);
			$pickup_date = $tanggal." ".$jam;
			mysqli_query($con, "update pengiriman set status='2',
												pickup='Y',
												pickup_date='$pickup_date',
												pickup_by='$pickup_by',
												pickup_by_agent='$r[id_cabang]',
												kondisi_barang_pickup='$kondisi_barang',
												kondisi_packing_pickup='$kondisi_packing',
												terlambat_pickup = '$reason_pilih',
												alasan_terlambat_pickup = '$reason_ketik',
												diskon_pickup = '$diskon'
							WHERE id_pengiriman = '$_POST[id]'");

			if($_FILES['gambar']){



				$ekstensi_diperbolehkan	= array('png','jpg','jpeg');
				$nama = $_FILES['gambar']['name'];
				$x = explode('.', $nama);
				$ekstensi = strtolower(end($x));
				$ukuran	= $_FILES['gambar']['size'];
				$file_tmp = $_FILES['gambar']['tmp_name'];	
	 
				if(in_array($ekstensi, $ekstensi_diperbolehkan) === true){
					if($ukuran < 9044070){			
						if(move_uploaded_file($file_tmp, '../../reportsnew/pickup_photo/'.$row['airwaybill_kode'].'.jpg')){					
							echo "BERHASIL UPLOAD";
						}
						else{
							echo "gagal";
						}
					}else{
						echo 'UKURAN FILE TERLALU BESAR, max 1mb';
					}
				}else{
					echo 'EKSTENSI FILE YANG DI UPLOAD TIDAK DI PERBOLEHKAN';
				}
			}
			if($id_kustomer == '13'){
				$now = date("Y-m-d\TH:i:s");
				/*$postDataDeliv = [
				    'noDR'=> $row['loc_code'],
				    'noAWB'=> $awb,
				    'serviceType'=> $servicevdi,
				    'statusDelivery'=> 1,
				    'statusDeliveryUpdateDate'=> $now,
				    'notes'=> '',
				    'receiverName'=> '',
				    'totalColly'=> $row['collie'],
				    'totalCost'=> 1,
				    'volumeInMtr'=> $row['berat_aktual'],
				    'cityCode'=> $row['kota_asal'],
				    'districtCode'=> $row['kecamatan_asal'],
				    'weightInKg'=> $row['berat_aktual'],
				   'courierId'=> $row['nama_kurir'],
				    'leadtime'=> 0,
				     'Content-Type: application/json'
				  ];*/

				  $postDataDeliv = [
		            '_ListDRMover' => [
		                'aWBNo' => $awb,
		                'cityCode' => $row['kota_asal'],
		                'CourierId' => 'V-00069',
		                'DistrictCode' => $row['kecamatan_asal'],
		                'drNo' => $row['loc_code'],
		                'Leadtime' => 1,
		                'notes' => 'kurir '.$row['nama_kurir'].' telah berhasil menjemput paket/dokumen',
		                'receiverName' => '',
		                'serviceType' => $servicevdi,
		                'statusDelivery' => 1,
		                'statusDeliveryUpdateDate' => $now,
		                'totColly' => $row['collie'],
		                'totCost' => 0,
		                'volInMeter' => $row['berat_aktual'],
		                'weightInKg' => $row['berat_aktual'],
		                'Content-Type: application/json'
		            ]
		        ];

				update_vdi($postDataDeliv);
			}
		
			header('location:../../main.php?module='.$module);
		}else{
			
			$query 		= mysqli_query($con, "select 
				id_cabang,namalengkap from user where id_user='$u[id_user]'
				
				");
			$r= mysqli_fetch_assoc($query);

			if($reason_pilih < 3){
				if($r['id_cabang'] == 39){
					$diskon = 15;
				}else{
					$diskon = 15;
				}
			}else{
				$diskon = '0';
			}

			mysqli_query($con, "update pengiriman set status='2',
												pickup='Y',
												pickup_date='$tgl_jam',
												pickup_by='$u[id_user]',
												pickup_by_agent='$r[id_cabang]',
												terlambat_pickup = '$reason_pilih',
												alasan_terlambat_pickup = '$reason_ketik',
												diskon_pickup = '$diskon'
							WHERE id_pengiriman = '$_POST[id]'");

			if($id_kustomer == '13'){
				$now = date("Y-m-d\TH:i:s");
				$postDataDeliv = [
				    'noDR'=> $row['loc_code'],
				    'noAWB'=> $awb,
				    'serviceType'=> $servicevdi,
				    'statusDelivery'=> 1,
				    'statusDeliveryUpdateDate'=> $now,
				    'notes'=> '',
				    'receiverName'=> '',
				    'totalColly'=> $row['collie'],
				    'totalCost'=> 1,
				    'volumeInMtr'=> $row['berat_aktual'],
				    'cityCode'=> $row['kota_asal'],
				    'districtCode'=> $row['kecamatan_asal'],
				    'weightInKg'=> $row['berat_aktual'],
				   'courierId'=> $row['nama_kurir'],
				    'leadtime'=> 0,
				     'Content-Type: application/json'
				  ];

				update_vdi($postDataDeliv);
			}


			$query_update_status_kiriman = "insert into update_status_kiriman (
															id_pengiriman,
															status_kiriman,
															created_date,
															status_by,
															created_by
																																							
															)
													values (
															'$_POST[id]',
															'Paket telah dipickup oleh $r[namalengkap]',
															now(),
															'$pickup_by',
															'$u[id_user]',
															)";					
														
						$hasil = mysqli_query($con,$query_update_status_kiriman);


			if($_FILES['gambar']){



				$ekstensi_diperbolehkan	= array('png','jpg','jpeg');
				$nama = $_FILES['gambar']['name'];
				$x = explode('.', $nama);
				$ekstensi = strtolower(end($x));
				$ukuran	= $_FILES['gambar']['size'];
				$file_tmp = $_FILES['gambar']['tmp_name'];	
	 
				if(in_array($ekstensi, $ekstensi_diperbolehkan) === true){
					if($ukuran < 9044070){			
						if(move_uploaded_file($file_tmp, '../../reportsnew/pickup_photo/'.$row['airwaybill_kode'].'.jpg')){					
							echo 'BERHASIL UPLOAD to ../../reportsnew/pickup_photo/'.$row['airwaybill_kode'].'.jpg';
						}
						else{
							echo "gagal";
						}
					}else{
						echo 'UKURAN FILE TERLALU BESAR, max 1mb';
					}
				}else{
					echo 'EKSTENSI FILE YANG DI UPLOAD TIDAK DI PERBOLEHKAN';
				}
			}
		
			header('location:../../main.php?module='.$module);
		}
	}
	// switch pickup
	elseif($module=='pickup' and $act=='switch'){
		$key = "asdf$1011";
		$encrypt = "$_POST[id]";
		$id = base64_decrypt($encrypt,$key);
		// lihat pickup request sebelumnya
		$pickup = mysqli_query($con,"select b.id_user, b.namalengkap from pengiriman a inner join user b on b.id_user=a.pickup_request where a.id_pengiriman='".$id."'");
		$p = mysqli_fetch_assoc($pickup);
		
		$query = mysqli_query($con, "SELECT if(id_kota_asal>0,x_inter(id_kota_asal),x_intercity(lokasi_asal)) as intercity, lokasi_asal,id_kota_asal, id_kustomer FROM pengiriman WHERE id_pengiriman='".$id."'");
		$r = mysqli_fetch_assoc($query);
		
		if($u['level']=='admin' or $u['level']=='operator' or $u['level']=='intercity' or $u['level']=='network' or $u['level']=='domex' or $u['level']=='agent'  or $u['level']=='inbound' or $u['level']=='admin_network'){
		echo'
		<form action="'.$aksi.'?module=pickup&act=switchkurir" name="modal_popup" method="POST">
		<input type="hidden" name="id" class="form-control" value="'.$id.'" />';
		if(!empty($p['namalengkap'])){
			echo'
			<div class="form-group" style="padding-bottom: 5px;">
                <label>Scheduled to:</label>
				<input type="text" class="form-control" value="'.$p['namalengkap'].'" disabled/>
			</div>';
		}
		if($r['intercity']=='Y'){
			echo'
			<div class="form-group" style="padding-bottom: 5px;">
                <label>Switch to:</label>
     			<select name="pickup_request" class="form-control">';
				$result = mysqli_query($con,"select id_user, namalengkap from user where (level='kurir' or level='driver') and blokir='N' and id_user!='$p[id_user]' order by namalengkap");
				while ($row = mysqli_fetch_assoc($result)){
					echo '<option value="'.$row['id_user'].'">'.strtoupper($row['namalengkap']).'</option>';
				}
				echo'
				</select>
			</div>';
		}else{
			echo'
				<label>Switch to:</label>
     			<select name="pickup_request" class="form-control select2" width="100%">';
					$result = mysqli_query($con,"select a.id_user, concat(b.nama_cabang,' ','(',b.alamat1,')',' / ',a.namalengkap) as namalengkap from user a 
						
						left join cabang b on SUBSTRING(b.id_kota_baru, 1,2) = SUBSTRING(x_hubnew('".$r['id_kota_asal']."'),1,2) 
						or b.id_kota_baru =  '".$r['id_kota_asal']."'
						
						

						where (a.level='agent' or a.level='kurir_agent') and a.blokir='N' 
						 and a.id_cabang=b.id_cabang ");
					while ($row = mysqli_fetch_assoc($result)) {
						echo '<option value="' . $row['id_user'] . '">'.strtoupper($row['namalengkap']).'</option>';
					}

					$result = mysqli_query($con,"select a.id_user, concat(b.nama_cabang,' ','(',b.alamat1,')',' / ',a.namalengkap) as namalengkap from user a 
						
						left join cabang b on a.id_cabang = b.id_cabang
						
						

						where (a.level='agent' or a.level='kurir_agent') and a.blokir='N'  and b.trucking_all_city='Y'
						 and a.id_cabang=b.id_cabang ");
					while ($row = mysqli_fetch_assoc($result)) {
						echo '<option value="' . $row['id_user'] . '">'.strtoupper($row['namalengkap']).'</option>';
					}
					echo '

				</select>';
				
		}
		echo'
			<div class="modal-footer">
				<button class="btn btn-success" type="submit">Confirm</button>
				<button type="reset" class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Cancel</button>
			</div>
		</form>';
		}
	
	}
	// switch action
	elseif($module=='pickup' AND $act=='switchkurir'){
		$pickup_request = mysqli_real_escape_string($con, $_POST['pickup_request']);
		
		mysqli_query($con, "update pengiriman set pickup_request='$pickup_request' where id_pengiriman='$_POST[id]'");
		
		header('location:../../main.php?module='.$module);
	}
	elseif($module=='pickup' AND $act=='echo'){
		echo $tgl_jam;
	}
	elseif ($module=='pickup' AND $act=='plus'){
		$tanggal		= jin_date_sql($_POST['tanggalplus']);
		$alasan 	= $_POST['alasan_plus'];
		if (isset($_POST['selectawb']) && is_array($_POST['selectawb'])) {
		    $awb 	  = $_POST['selectawb'];
		    $id_awb   = implode(',', $awb);

		    //echo "update pengiriman set pickup_date_request='$tanggal',alasan_plus_pickup='$alasan' where id_pengiriman in ($id_awb)";
		  mysqli_query($con, "update pengiriman set pickup_date_request='$tanggal',alasan_plus_pickup='$alasan' where id_pengiriman in ($id_awb)");
		} else {
		    $awb = [];
		    $id_awb   = ''; // atau bisa kasih default value lain
		}

		
		
		
		//echo "update pengiriman set manifest_received='$tanggal' where id_pengiriman in ($id_awb)";
		//echo $alasan;
		//
		header('location:../../main.php?module='.$module);
	}

	elseif ($module=='pickup' AND $act=='wakilkan'){
		
		$secret = $_GET['id_pengiriman'];
		$key = "asdf$1011";
		$id_pengiriman = base64_decrypt($secret,$key);

		mysqli_query($con, "update pengiriman set wakilkan_pickup='Y' where id_pengiriman = $id_pengiriman");
		header('location:../../main.php?module='.$module);
		
	}

	elseif ($module=='pickup' AND $act=='form-insert-price')
	{
		$id = $_POST['id'];
		
		$query = mysqli_query($con, "SELECT a.id_pengiriman, a.airwaybill_kode, a.tanggal, a.pickup_date, a.id_kustomer ,if(a.id_kota_asal > 0,a.kota_asal,x_namakotabesar(a.lokasi_asal)) as lokasi_asal, if(a.id_kota_asal>0,a.id_kota_asal,a.lokasi_asal) as id_lokasi_asal, 
			if(a.id_kota_tujuan > 0,a.kota_tujuan,x_namakotabesar(a.lokasi_tujuan)) as lokasi_tujuan, if(a.id_kota_tujuan>0,a.id_kota_tujuan,a.lokasi_tujuan) as id_lokasi_tujuan, a.kota_tujuan,
			x_service(a.service) as service, a.service as id_pelayanan, case when a.komoditi='1' then 'Paket' else 'Dokumen' end as comodity,a.komoditi as komoditi, a.berat_barang, a.berat_aktual, a.nama_pengirim, a.nama_penerima, a.alamat_pengirim, a.alamat_penerima, a.loc_code, a.delivery_name, a.delivery_date FROM pengiriman a LEFT JOIN negara f ON f.id_negara=a.lokasi_tujuan and a.service=6 WHERE a.id_pengiriman='".$id."'");
		$r = mysqli_fetch_assoc($query);
		//<form action="'.$aksi.'?module=invoice-customer&act=updateharga" method="POST">
		echo'
		<form action="module/mod_invoice-customer/invoice-customer_action.php?module=invoice-customer&act=insert-price" method="POST">
			
			<div class="form-group" style="padding-bottom: 25px;">
				<label class="col-md-3 control-label">Komoditi</label>
				<div class="col-md-8">
					<input type="text" value="'.$r['comodity'].'" class="form-control" readonly>
				</div>
			</div>
			<div class="form-group" style="padding-bottom: 25px;">
				<label class="col-md-3 control-label">Service</label>
				<div class="col-md-8">
					<input type="text" value="'.$r['service'].'" class="form-control" readonly>
				</div>
			</div>			
			<div class="form-group" style="padding-bottom: 25px;">
				<label class="col-md-3 control-label">Loc Code</label>
				<div class="col-md-8">
					<input type="text" value="'.$r['loc_code'].'" name="loc_code" class="form-control" readonly>
				</div>
			</div>			
			<div class="form-group" style="padding-bottom: 25px;">
				<label class="col-md-3 control-label">Kota Asal</label>
				<div class="col-md-8">
					<input type="text" value="'.$r['lokasi_asal'].'" class="form-control" readonly>
				</div>
			</div>			
			<div class="form-group" style="padding-bottom: 25px;">
				<label class="col-md-3 control-label">Kota Tujuan</label>
				<div class="col-md-8">
					<input type="text" value="'.$r['kota_tujuan'].'" class="form-control" readonly>
				</div>
			</div>			
			
			<div class="form-group" style="padding-bottom: 25px;">
				<label class="col-md-3 control-label">Min kg</label>
				<div class="col-md-8">
					<input type="number" value="1" name="min_kg" class="form-control" min="1">
				</div>
			</div>
			<div class="form-group" style="padding-bottom: 25px;">
				<label class="col-md-3 control-label">Harga:</label>
				<div class="col-md-8">
					<input type="number" name="price_perkg" class="form-control" value="1000" min="1000" />
				</div>
			</div>
			<div class="form-group" style="padding-bottom: 25px;">
				<label class="col-md-3 control-label">Lead Time:</label>
				<div class="col-md-8">
					<input type="number" name="sla" class="form-control" value="3" min="1" required/>
				</div>
			</div>
			<input type="hidden" name="id_pengiriman" class="form-control" value="'.$r['id_pengiriman'].'" />
			<input type="hidden" name="id_kustomer" class="form-control" value="'.$r['id_kustomer'].'" />
			<input type="hidden" name="id_lokasi_asal" class="form-control" value="'.$r['id_lokasi_asal'].'" />
			<input type="hidden" name="id_lokasi_tujuan" class="form-control" value="'.$r['id_lokasi_tujuan'].'" />			
			<input type="hidden" name="id_pelayanan" class="form-control" value="'.$r['id_pelayanan'].'" />
			<input type="hidden" name="komoditi" class="form-control" value="'.$r['komoditi'].'" />
			<div class="modal-footer">
				<button class="btn btn-success" type="submit">insert</button>
				<button type="reset" class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Cancel</button>
			</div>
		</form>';
		
	}

	elseif($module=='pickup' and $act=='status'){
		//$key = "asdf$1011";
		//$encrypt = "$_POST[id]";
		$id = $_POST['id'];

		$querypd = mysqli_query($con, "select 
			airwaybill_kode, 
			DATE_FORMAT(created_date, '%d-%m-%Y') as tanggal_booking,
			DATE_FORMAT(created_date, '%H:%i') as jam_booking,
			DATE_FORMAT(pickup_date, '%d-%m-%Y') as pickup_date,
			DATE_FORMAT(pickup_date, '%H:%i') as pickup_time,
			DATE_FORMAT(manifest_pickup_date, '%H:%i') as manifest_pickup_time,
			DATE_FORMAT(manifest_pickup_date, '%d-%m-%Y') as manifest_pickup_date,
			x_user(pickup_request) as pickup_request,
			x_user(manifest_pickup_by) as manifest_pickup_by,
			x_user(pickup_by) as pickup_by,
			kecamatan_asal,
			status,
			transit,
			pickup,
			kota_asal,
			kecamatan_tujuan,
			received_transit,
			kota_tujuan,
			DATE_FORMAT(manifest_delivery_date, '%d-%m-%Y') as manifest_delivery_date,
			DATE_FORMAT(manifest_delivery_date, '%H:%i') as manifest_delivery_time,
			x_user(manifest_delivery_by) as manifest_delivery_by,
			if(status>5,delivery_date,'-') as delivery_date,
			delivery_time,
			x_user(courier_by) as courier_by,
			if(status>5,x_user(delivery_by),'-') as delivery_by,
			delivery_name,
			kota_tujuan,
			case when user_client IS NOT NULL THEN (select fullname FROM user_client WHERE id_user=user_client) ELSE (SELECT namalengkap FROM user WHERE id_user=created_by) END AS booking_by

		from pengiriman where id_pengiriman=$id");
                 	$d = mysqli_fetch_assoc($querypd);



		echo '
			
    <h2>TRACK INFO</h2>
   <div class="row" style="text-transform: uppercase;">
      
      <div class="col-md-12 col-lg-12">
         <div id="tracking-pre"></div>
         <div id="tracking">
            <div class="text-center tracking-status-intransit">
               <p class="tracking-status text-tight">'.$d['airwaybill_kode'].' - '.$id.'</p>
            </div>
            <div class="tracking-list">


            	';

            	if ($d['status'] == 6){
            		echo '
               <div class="tracking-item">
                  <div class="tracking-icon status-intransit">
                     <svg class="svg-inline--fa fa-circle fa-w-16" aria-hidden="true" data-prefix="fas" data-icon="circle" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg="">
                        <path fill="currentColor" d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8z"></path>
                     </svg>
                     <!-- <i class="fas fa-circle"></i> -->
                  </div>
                  <div class="tracking-date">'.$d['delivery_date'].'<span>'.$d['delivery_time'].'</span></div>
                  <div class="tracking-content">Kurir <b>'.$d['delivery_by'].'</b> berhasil mengirim ke alamat tujuan <span>'.$d['kecamatan_tujuan'].', '.$d['kota_tujuan'].'</span></div>
               </div>';
           		}

               
               if($d['manifest_delivery_by'] != ''){
               		echo '<div class="tracking-item">
	                  ';if ($d['status'] == 5){

				                  	echo '<div class="tracking-icon status-intransit">
				                     <svg class="svg-inline--fa fa-circle fa-w-16" aria-hidden="true" data-prefix="fas" data-icon="circle" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg="">
				                        <path fill="#3cba51" d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8z"></path>
				                     </svg>
				                     <!-- <i class="fas fa-circle"></i> -->
				                  	</div>';
				                  }else{
				                  	echo '<div class="tracking-icon status-intransit">
				                     <svg class="svg-inline--fa fa-circle fa-w-16" aria-hidden="true" data-prefix="fas" data-icon="circle" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg="">
				                        <path fill="currentColor" d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8z"></path>
				                     </svg>
				                     <!-- <i class="fas fa-circle"></i> -->
				                  	</div>';
				                  }  
				                  echo '
	                  <div class="tracking-date">'.$d['manifest_delivery_date'].'<span>'.$d['manifest_delivery_time'].'</span></div>
	                  <div class="tracking-content">Kurir <b>'.$d['courier_by'].' </b> ditugaskan delivery	oleh <b>'.$d['manifest_delivery_by'].'</b>  <span>'.$d['kecamatan_tujuan'].', '.$d['kota_tujuan'].'</span></div>
	               </div>';
               }else{
               		$query_man_del = mysqli_query($con, "select 
						
						DATE_FORMAT(created_date, '%d-%m-%Y') as manifest_delivery_date,
						DATE_FORMAT(created_date, '%H:%i') as manifest_delivery_time,
						x_user(created_by) as manifest_delivery_by,
						x_user(kurir_by) as courier_by
						

					from update_status_kiriman where id_pengiriman=$id");
			                 	$qd = mysqli_fetch_assoc($query_man_del);


			          echo '<div class="tracking-item">
	                  ';if ($d['status'] >=4){

				                  	echo '<div class="tracking-icon status-intransit">
				                     <svg class="svg-inline--fa fa-circle fa-w-16" aria-hidden="true" data-prefix="fas" data-icon="circle" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg="">
				                        <path fill="#3cba51" d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8z"></path>
				                     </svg>
				                     <!-- <i class="fas fa-circle"></i> -->
				                  	</div>';
				                  }else{
				                  	echo '<div class="tracking-icon status-intransit">
				                     <svg class="svg-inline--fa fa-circle fa-w-16" aria-hidden="true" data-prefix="fas" data-icon="circle" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg="">
				                        <path fill="currentColor" d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8z"></path>
				                     </svg>
				                     <!-- <i class="fas fa-circle"></i> -->
				                  	</div>';
				                  }  
				                  echo '
	                  <div class="tracking-date">'.$qd['manifest_delivery_date'].'<span>'.$qd['manifest_delivery_time'].'</span></div>
	                  <div class="tracking-content">Kurir '.$qd['courier_by'].' sudah ditugaskan delivery oleh '.$qd['manifest_delivery_by'].'  <span>'.$d['kota_tujuan'].'</span></div>
	               </div>';
               }
               

                
               

               	$querywhile = mysqli_query($con, "select DATE_FORMAT(a.created_date, '%d-%m-%Y') as tanggal_manifest, 
               											 DATE_FORMAT(a.created_date, '%H:%i') as jam_manifest,
               											 x_namacabang(b.manifest_to) as manifest_to,
               											 
               											 manifest_via,
               											 x_user(b.created_by) as manifest_outbound_by ,
               											 x_user(manifest_outbound_by) as petugas_lapangan,

               											 DATE_FORMAT(a.inbound_received, '%d-%m-%Y') as tanggal_inbound, 
               											 DATE_FORMAT(a.inbound_received, '%H:%i') as jam_inbound,
               											 x_user(a.inbound_by) as inbound_by


               		from 
               			manifest_detail a inner join manifest b on a.id_manifest=b.id_manifest 

               			where a.id_pengiriman=$id and b.manifest_from > 0 order by a.id_manifest_detail desc" );

                  	while ($data=mysqli_fetch_assoc($querywhile)){
                  			echo '
                  			

			                <div class="tracking-item">
			                  


			                  ';

			                  if ( ($d['status'] > 1 && $d['transit'] == 'Y' && $d['received_transit'] == 'Y') || $d['status'] > 3){

				                  	echo '<div class="tracking-icon status-intransit">
				                     <svg class="svg-inline--fa fa-circle fa-w-16" aria-hidden="true" data-prefix="fas" data-icon="circle" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg="">
				                        <path fill="#3cba51" d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8z"></path>
				                     </svg>
				                     <!-- <i class="fas fa-circle"></i> -->
				                  	</div>';
				                  }else{
				                  	echo '<div class="tracking-icon status-intransit">
				                     <svg class="svg-inline--fa fa-circle fa-w-16" aria-hidden="true" data-prefix="fas" data-icon="circle" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg="">
				                        <path fill="currentColor" d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8z"></path>
				                     </svg>
				                     <!-- <i class="fas fa-circle"></i> -->
				                  	</div>';
				                  }  
				                  echo '
			                  <div class="tracking-date">'.$data['tanggal_inbound'].'<span>'.$data['jam_inbound'].'</span></div>
			                  <div class="tracking-content">  Sudah sampai di kota tujuan <span>'.$data['manifest_to'].'</span></div>
			               </div>
			                '


			                ;


                  	
			                echo '<div class="tracking-item">';

			                  if ( ($d['status'] > 1 && $d['transit'] == 'Y' && $d['received_transit'] == 'N') || $d['status'] > 2){

				                  	echo '<div class="tracking-icon status-intransit">
				                     <svg class="svg-inline--fa fa-circle fa-w-16" aria-hidden="true" data-prefix="fas" data-icon="circle" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg="">
				                        <path fill="#3cba51" d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8z"></path>
				                     </svg>
				                     <!-- <i class="fas fa-circle"></i> -->
				                  	</div>';
				                  }else{
				                  	echo '<div class="tracking-icon status-intransit">
				                     <svg class="svg-inline--fa fa-circle fa-w-16" aria-hidden="true" data-prefix="fas" data-icon="circle" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg="">
				                        <path fill="currentColor" d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8z"></path>
				                     </svg>
				                     <!-- <i class="fas fa-circle"></i> -->
				                  	</div>';
				                  }  
				                  echo '
			                  <div class="tracking-date">'.$data['tanggal_manifest'].'<span>'.$data['jam_manifest'].'</span></div>
			                  <div class="tracking-content"> <b>'.$data['petugas_lapangan'].'</b> ditugaskan <b>'.$data['manifest_outbound_by'].' </b>berangkatkan paket ke kota tujuan <span>Tujuan '.$data['manifest_to'].' via '.$data['manifest_via'].'</span></div>
			               </div>
			                '


			                ;


                  	}
                echo '
               
               <div class="tracking-item">
                  ';if ($d['pickup'] != 'Y'){

                  	echo '<div class="tracking-icon status-intransit">
                     <svg class="svg-inline--fa fa-circle fa-w-16" aria-hidden="true" data-prefix="fas" data-icon="circle" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg="">
                        <path fill="currentColor" d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8z"></path>
                     </svg>
                     <!-- <i class="fas fa-circle"></i> -->
                  </div>';
                  }else{
                  	echo '<div class="tracking-icon status-intransit">
                     <svg class="svg-inline--fa fa-circle fa-w-16" aria-hidden="true" data-prefix="fas" data-icon="circle" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg="">
                        <path fill="#3cba51" d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8z"></path>
                     </svg>
                     <!-- <i class="fas fa-circle"></i> -->
                  </div>';
                  }
                  
                  echo '

                  <div class="tracking-date">'.$d['pickup_date'].'<span>'.$d['pickup_time'].'</span></div>
                  <div class="tracking-content">Paket sudah pickup oleh Kurir <b>'.$d['pickup_by'].'</b> <span>'.$d['kecamatan_asal'].', '.$d['kota_asal'].'</span></div>
               </div>

               <div class="tracking-item">
                  <div class="tracking-icon status-intransit">
                     <svg class="svg-inline--fa fa-circle fa-w-16" aria-hidden="true" data-prefix="fas" data-icon="circle" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg="">
                        <path fill="#3cba51" d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8z"></path>
                     </svg>
                     <!-- <i class="fas fa-circle"></i> -->
                  </div>
                  <div class="tracking-date">'.$d['manifest_pickup_date'].'<span>'.$d['manifest_pickup_time'].'</span></div>
                  <div class="tracking-content">Kurir <b>'.$d['pickup_request'].'</b> ditugaskan pickup oleh <b>'.$d['manifest_pickup_by'].'</b><span>'.$d['kecamatan_asal'].', '.$d['kota_asal'].'</span></div>
               </div>


               <div class="tracking-item">
                  <div class="tracking-icon status-intransit">
                     <svg class="svg-inline--fa fa-circle fa-w-16" aria-hidden="true" data-prefix="fas" data-icon="circle" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg="">
                        <path fill="#3cba51" d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8z"></path>
                     </svg>
                     <!-- <i class="fas fa-circle"></i> -->
                  </div>
                  <div class="tracking-date">'.$d['tanggal_booking'].'<span>'.$d['jam_booking'].'</span></div>
                  <div class="tracking-content">Airwaybill sudah dibuat oleh <b>'.$d['booking_by'].'</b><span>'.$d['kecamatan_asal'].', '.$d['kota_asal'].'</span></div>
               </div>

            </div>
         </div>
      </div>
   </div>

		';
			
			
		

	// delete
	}
}
?>