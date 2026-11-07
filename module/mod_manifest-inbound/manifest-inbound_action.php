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
	
	$module	= !empty($_GET['module']) ? $_GET['module'] : '';
	$act	= !empty($_GET['act']) ? $_GET['act'] : '';
	
	$aksi="module/mod_manifest-inbound/manifest-inbound_action.php";

	$key = 'asdf$1011';
	$encrypt = $_SESSION['id_user'];
	$id = base64_decrypt($encrypt,$key);
	$useraktif = mysqli_query($con, 'select * from user where id_user="'.$id.'"');
	$u = mysqli_fetch_assoc($useraktif);
	
	if($module=='manifest-inbound' and $act=='data'){
		if($u['level']=='admin' || $u['level']=='finance'){
		$table = 
<<<EOT
	(
	select 
		a.airwaybill_kode, 
		CONCAT
		(DATE_FORMAT(c.tanggal,"%Y-%m-%d")
		,' ', 
		 
		if(b.sla > 0,b.sla, 99)

			
			,' ',a.id_pengiriman	
			,' ',ifnull(b.target_sla,0)	
			,' ',a.pickup_date	
			) as tanggal,
		
		c.no_smu, 
		x_namakustomer(a.id_kustomer) as nama_kustomer, 
		case when if(a.id_kota_asal>0,x_inter(a.id_kota_asal),x_intercity(a.lokasi_asal))='Y' then 'JAKARTA' else if(a.id_kota_asal>0,a.kota_asal,x_namakota(a.lokasi_asal)) end as kota_asal, 
		case when if(a.id_kota_tujuan>0,x_inter(a.id_kota_tujuan),x_intercity(a.lokasi_tujuan))='Y' then 'JAKARTA' else if(a.id_kota_asal>0,a.kota_tujuan,x_namakota(a.lokasi_tujuan)) end as kota_tujuan, 
		x_service(a.service) as service, 
		a.id_pengiriman ,
		concat(a.transit,',', CAST(x_namacabang(c.manifest_from) AS CHAR CHARACTER SET utf8 ) , ',', CAST(x_namacabang(c.manifest_to) AS CHAR CHARACTER SET utf8 ) ) as transit,
		

		CONCAT(a.id_pengiriman, ' ', 
		
		if(b.sla > 0,b.sla, 0)

			 , ' ' ,DATE_FORMAT(c.tanggal,"%Y-%m-%d"),' ','$u[level]',' ',ifnull(b.target_sla,0),' ',if(c.no_smu is null or c.no_smu = '',9999,c.no_smu), ' ', c.id_manifest,' ', '99' ) as assignment

	from pengiriman a 

	left join manifest_detail b on b.id_manifest_detail=(select id_manifest_detail from manifest_detail y where y.id_pengiriman = a.id_pengiriman order by y.id_manifest_detail desc limit 1) 

	inner join manifest c on c.id_manifest= b.id_manifest and c.no_smu != ''
	

	

	where a.status='3'  and a.service !=6 and a.pickup='Y' and a.delivery='N' 
	AND  (
	(x_inter(a.id_kota_asal) !='Y' and x_inter(a.id_kota_tujuan)='Y') 

	or (
		x_inter(a.id_kota_asal)='Y' 
		and x_inter(a.id_kota_tujuan)!='Y'
	) 
	or (
		x_inter(a.id_kota_asal) !='Y' 
		and x_inter(a.id_kota_tujuan) !='Y')
	) and a.transit != 'Y' 

	or (a.transit='Y' AND a.received_transit != 'Y' and a.status!=6 and a.status!=7 and a.status != 4 and a.status!=5 and a.status!=6 )   group by a.id_pengiriman
	) temp
EOT;
		}elseif($u['level']=='operator' or $u['level']=='intercity'){
		$table = 
<<<EOT
	(
	select 
		a.airwaybill_kode, 
		CONCAT
		(DATE_FORMAT(c.tanggal,"%Y-%m-%d")
		,' ', 
		 
		if(b.sla > 0,b.sla, 99)

			
			,' ',a.id_pengiriman	
			,' ',ifnull(b.target_sla,0)	
			,' ',a.pickup_date
			) as tanggal,
		
		c.no_smu, 
		x_namakustomer(a.id_kustomer) as nama_kustomer, 
		case when if(a.id_kota_asal>0,x_inter(a.id_kota_asal),x_intercity(a.lokasi_asal))='Y' then 'JAKARTA' else if(a.id_kota_asal>0,a.kota_asal,x_namakota(a.lokasi_asal)) end as kota_asal, 
		case when if(a.id_kota_tujuan>0,x_inter(a.id_kota_tujuan),x_intercity(a.lokasi_tujuan))='Y' then 'JAKARTA' else if(a.id_kota_asal>0,a.kota_tujuan,x_namakota(a.lokasi_tujuan)) end as kota_tujuan, 
		
		x_service(a.service) as service, 
		a.id_pengiriman ,
		concat(a.transit,',', CAST(x_namacabang(c.manifest_from) AS CHAR CHARACTER SET utf8 ) , ',', CAST(x_namacabang(c.manifest_to) AS CHAR CHARACTER SET utf8 ) ) as transit,
		
		CONCAT(a.id_pengiriman, ' ', 
		
		if(b.sla > 0,b.sla, if(a.service=5,3,2))

			 , ' ' ,DATE_FORMAT(c.tanggal,"%Y-%m-%d"),' ','$u[level]',' ',ifnull(b.target_sla,0),' ',if(c.no_smu is null or c.no_smu = '',9999,c.no_smu), ' ', c.id_manifest,' ', c.manifest_to	 ) as assignment

	from pengiriman a 
	left join manifest_detail b on b.id_manifest_detail=(select id_manifest_detail from manifest_detail y where y.id_pengiriman = a.id_pengiriman order by y.id_manifest_detail desc limit 1)
	inner join manifest c on c.id_manifest= b.id_manifest and c.no_smu != ''
	
	
	

	

	where a.status='3'  and a.service !=6 and a.pickup='Y' and a.delivery='N' 
	and if(a.id_kota_asal>0,x_inter(a.id_kota_asal),x_intercity(a.lokasi_asal))!='Y' 
	
	and x_inter(a.id_kota_tujuan) ='Y' 

	or (a.transit='Y' and a.status!=7 AND a.received_transit != 'Y' and a.status!=6 and a.status != 4 and a.status!=5  and if(a.id_kota_asal>0,x_inter(a.id_kota_asal),x_intercity(a.lokasi_asal))!='Y' AND c.manifest_to=39 )   group by a.id_pengiriman) temp
	
EOT;
		}

		elseif($u['level']=='admin_network'){
		$table = 
<<<EOT
	(
	select 
		a.airwaybill_kode, 
		CONCAT
		(DATE_FORMAT(c.tanggal,"%Y-%m-%d")
		,' ', 
		 
		if(b.sla > 0,b.sla, 99)

			
			,' ',a.id_pengiriman	
			,' ',ifnull(b.target_sla,0)	
			,' ',a.pickup_date
			) as tanggal,
		
		c.no_smu, 
		x_namakustomer(a.id_kustomer) as nama_kustomer, 

		case when x_inter(a.id_kota_asal) ='Y' then 'JAKARTA' else a.kota_asal end as kota_asal, 
		case when if(a.id_kota_tujuan>0,x_inter(a.id_kota_tujuan),x_intercity(a.lokasi_tujuan))='Y' then 'JAKARTA' else if(a.id_kota_asal>0,a.kota_tujuan,x_namakota(a.lokasi_tujuan)) end as kota_tujuan, 
		
		x_service(a.service) as service, 
		a.id_pengiriman ,
		concat(a.transit,',', CAST(x_namacabang(c.manifest_from) AS CHAR CHARACTER SET utf8 ) , ',', CAST(x_namacabang(c.manifest_to) AS CHAR CHARACTER SET utf8 ) ) as transit,
		
		CONCAT(a.id_pengiriman, ' ', 
		
		if(b.sla > 0,b.sla, if(a.service=5,3,2))

			 , ' ' ,DATE_FORMAT(c.tanggal,"%Y-%m-%d"),' ','$u[level]',' ',ifnull(b.target_sla,0),' ',if(c.no_smu is null or c.no_smu = '',9999,c.no_smu), ' ', c.id_manifest,' ', c.manifest_to	 ) as assignment

	from pengiriman a 
	inner join manifest_detail b on b.id_manifest_detail= (select id_manifest_detail from manifest_detail y where y.id_pengiriman = a.id_pengiriman order by y.id_manifest_detail desc limit 1)
	
	inner join manifest c on c.id_manifest= b.id_manifest and c.no_smu != ''
	
	
	

	

	where a.status='3'  and a.service !=6 and a.pickup='Y' and a.delivery='N' and a.status != 7
	
	and  a.airwaybill_kode not like '002-%'
	
	

	or (a.transit='Y' AND a.received_transit != 'Y' and a.status!=6  

	

	 
  and  a.airwaybill_kode not like '002-%')   group by a.id_pengiriman) temp
EOT;
		}

		elseif($u['level']=='network'){
		$table = 
<<<EOT
	(
	select 
		a.airwaybill_kode, 
		CONCAT
		(DATE_FORMAT(c.tanggal,"%Y-%m-%d")
		,' ', 
		 
		if(b.sla > 0,b.sla, 99)

			
			,' ',a.id_pengiriman	
			,' ',ifnull(b.target_sla,0)	
			,' ',a.pickup_date
			) as tanggal,
		
		c.no_smu, 
		x_namakustomer(a.id_kustomer) as nama_kustomer, 

		case when x_inter(a.id_kota_asal) ='Y' then 'JAKARTA' else a.kota_asal end as kota_asal, 
		case when if(a.id_kota_tujuan>0,x_inter(a.id_kota_tujuan),x_intercity(a.lokasi_tujuan))='Y' then 'JAKARTA' else if(a.id_kota_asal>0,a.kota_tujuan,x_namakota(a.lokasi_tujuan)) end as kota_tujuan, 
		
		x_service(a.service) as service, 
		a.id_pengiriman ,
		concat(a.transit,',', CAST(x_namacabang(c.manifest_from) AS CHAR CHARACTER SET utf8 ) , ',', CAST(x_namacabang(c.manifest_to) AS CHAR CHARACTER SET utf8 ) ) as transit,
		
		CONCAT(a.id_pengiriman, ' ', 
		
		if(b.sla > 0,b.sla, if(a.service=5,3,2))

			 , ' ' ,DATE_FORMAT(c.tanggal,"%Y-%m-%d"),' ','$u[level]',' ',ifnull(b.target_sla,0),' ',if(c.no_smu is null or c.no_smu = '',9999,c.no_smu), ' ', c.id_manifest,' ', c.manifest_to	 ) as assignment

	from pengiriman a 
	inner join manifest_detail b on b.id_manifest_detail= (select id_manifest_detail from manifest_detail y where y.id_pengiriman = a.id_pengiriman order by y.id_manifest_detail desc limit 1)
	
	inner join manifest c on c.id_manifest= b.id_manifest and c.no_smu != ''
	
	
	

	

	where a.status='3'  and a.service !=6 and a.pickup='Y' and a.delivery='N'  
	and a.id_provinsi_tujuan in ($u[area_prov])
	
	AND  (
	(x_inter(a.id_kota_asal)='Y' 

	and x_inter(a.id_kota_tujuan) !='Y')) 

	or (a.transit='Y' AND a.received_transit != 'Y' and a.status!=6 and 

	x_inter(a.id_kota_asal)='Y' 

	 
	and x_inter(a.id_kota_tujuan) !='Y' and a.id_provinsi_tujuan in ($u[area_prov]))   group by a.id_pengiriman) temp
EOT;
		}
		elseif($u['level']=='inbound'){
		$table = 
<<<EOT
	(
	select 
		a.airwaybill_kode, 
		CONCAT
		(DATE_FORMAT(c.tanggal,"%Y-%m-%d")
		,' ', 
		 
		if(b.sla > 0,b.sla, 99)

			
			,' ',a.id_pengiriman	
			,' ',ifnull(b.target_sla,0)	
			,' ',a.pickup_date
			) as tanggal,
		
		c.no_smu, 
		x_namakustomer(a.id_kustomer) as nama_kustomer, 
		case when if(a.id_kota_asal>0,x_inter(a.id_kota_asal),x_intercity(a.lokasi_asal))='Y' then 'JAKARTA' else if(a.id_kota_asal>0,a.kota_asal,x_namakota(a.lokasi_asal)) end as kota_asal, 
		case when if(a.id_kota_tujuan>0,x_inter(a.id_kota_tujuan),x_intercity(a.lokasi_tujuan))='Y' then 'JAKARTA' else if(a.id_kota_asal>0,a.kota_tujuan,x_namakota(a.lokasi_tujuan)) end as kota_tujuan, 
		
		x_service(a.service) as service, 
		a.id_pengiriman ,
		concat(a.transit,',', CAST(x_namacabang(c.manifest_from) AS CHAR CHARACTER SET utf8 ) , ',', CAST(x_namacabang(c.manifest_to) AS CHAR CHARACTER SET utf8 ) ) as transit,
		
		CONCAT(a.id_pengiriman, ' ', 
		
		if(b.sla > 0,b.sla, if(a.service=5,3,2))

			 , ' ' ,DATE_FORMAT(c.tanggal,"%Y-%m-%d"),' ','$u[level]',' ',ifnull(b.target_sla,0),' ',if(c.no_smu is null or c.no_smu = '',9999,c.no_smu), ' ', c.id_manifest,' ', c.manifest_to	 ) as assignment

	from pengiriman a 
	left join manifest_detail b on b.id_manifest_detail=(select id_manifest_detail from manifest_detail y where y.id_pengiriman = a.id_pengiriman order by y.id_manifest_detail desc limit 1)
	inner join manifest c on c.id_manifest= b.id_manifest and c.no_smu != ''
	
	
	

	

	where a.status='3'  and a.service !=6 and a.pickup='Y' and a.delivery='N' and a.status != 7
	and if(a.id_kota_asal>0,x_inter(a.id_kota_asal),x_intercity(a.lokasi_asal))!='Y' 
	
	and a.id_provinsi_asal in ($u[area_prov])

	or (a.transit='Y' and a.status!=7 AND a.received_transit != 'Y' and a.status!=6 and a.status != 4 and a.status!=5  and if(a.id_kota_asal>0,x_inter(a.id_kota_asal),x_intercity(a.lokasi_asal))!='Y'   and a.id_provinsi_asal in ($u[area_prov]) )   group by a.id_pengiriman) temp
EOT;
		}
		elseif($u['level']=='domex'){
			if ($u['id_kustomer'] > 0 ){
				$table = 
<<<EOT
	(
	select 
		a.airwaybill_kode, 
		CONCAT(DATE_FORMAT(a.created_date,"%Y-%m-%d"),' ', 
		
			if(b.sla > 0,b.sla, 99)

			
			,' ',a.id_pengiriman	
			,' ',ifnull(b.target_sla,0)	
			,' ',a.pickup_date
			) as tanggal,
		
		c.no_smu, 
		x_namakustomer(a.id_kustomer) as nama_kustomer, 
		case when if(a.id_kota_asal>0,x_inter(a.id_kota_asal),x_intercity(a.lokasi_asal))='Y' then 'JAKARTA' else if(a.id_kota_asal>0,a.kota_asal,x_namakota(a.lokasi_asal)) end as kota_asal, 
		case when if(a.id_kota_tujuan>0,x_inter(a.id_kota_tujuan),x_intercity(a.lokasi_tujuan))='Y' then 'JAKARTA' else if(a.id_kota_asal>0,a.kota_tujuan,x_namakota(a.lokasi_tujuan)) end as kota_tujuan, 
		
		x_service(a.service) as service, 
		a.id_pengiriman ,
		a.transit,
		ifnull( DATE_FORMAT( DATE_ADD(ifnull(a.pickup_date_request,a.created_date), INTERVAL case when a.service=1 then CASE 
			WHEN airwaybill_kode LIKE '001-%' THEN 1
			WHEN airwaybill_kode LIKE '002-%' THEN 0 
			END when a.service=2 then 1 else f.sla_max end DAY), "%Y-%m-%d" ) ,'0' ) as sla_max,
		
		CONCAT(a.id_pengiriman, ' ', 
		
		case when a.service <= 3 then 1 
		 	
		when a.service = 12 then 1 
		else 
			ifnull(ifnull(f.sla_max,ifnull(g.sla_max,h.sla_max)),99  )
		end

			 , ' ' ,DATE_FORMAT(pickup_date,"%Y-%m-%d"),' ','$u[level]',' ',ifnull(b.target_sla,0),' ',if(c.no_smu is null or c.no_smu = '',9999,c.no_smu), ' ', c.id_manifest,' ', c.manifest_to	 ) as assignment

	from pengiriman a 
	inner join manifest_detail b on b.id_pengiriman=(select id_manifest from manifest_detail y where y.id_pengiriman = a.id_pengiriman order by y.id_manifest_detail desc limit 1)
	left join manifest c on c.id_manifest= (select id_manifest from manifest_detail z where z.id_pengiriman = a.id_pengiriman order by z.id_manifest_detail desc limit 1) 
	
	

	left join sla_cross f on f.kota_asal =  a.id_kota_asal   AND f.kota_tujuan=  a.id_kota_tujuan  AND f.service = a.service	
	left join sla g on g.kota_asal =  if(x_inter(a.id_kota_asal)='Y',9999,0)   AND g.kota_tujuan=  a.id_kota_tujuan  AND g.service = a.service
	left join sla_in h on h.kota_asal =  a.id_kota_asal   AND h.kota_tujuan= if(x_inter(a.id_kota_tujuan)='Y',9999,0)  AND h.service = a.service

	where a.status='3' and a.service !=6 and a.pickup='Y' and a.delivery='N' and a.id_kustomer = $u[id_kustomer]  and a.status!=7

	AND  ((if(a.id_kota_asal>0,x_inter(a.id_kota_asal),x_intercity(a.lokasi_asal))!='Y' and if(a.id_kota_tujuan>0,x_inter(a.id_kota_tujuan),x_intercity(a.lokasi_tujuan))='Y' )  
	or (if(a.id_kota_asal>0,x_inter(a.id_kota_asal),x_intercity(a.lokasi_asal))!='Y' and if(a.id_kota_tujuan>0,x_inter(a.id_kota_tujuan),x_intercity(a.lokasi_tujuan))!='Y') ) 
	or (a.transit='Y' AND a.received_transit != 'Y' and a.status!=6 and ((if(a.id_kota_asal>0,x_inter(a.id_kota_asal),x_intercity(a.lokasi_asal))!='Y' and if(a.id_kota_tujuan>0,x_inter(a.id_kota_tujuan),x_intercity(a.lokasi_tujuan))='Y' )  
	or (if(a.id_kota_asal>0,x_inter(a.id_kota_asal),x_intercity(a.lokasi_asal))!='Y' and if(a.id_kota_tujuan>0,x_inter(a.id_kota_tujuan),x_intercity(a.lokasi_tujuan))!='Y') ) )   group by a.id_pengiriman
	) temp
EOT;
			}
			else{
				$table = 
<<<EOT
	(
	select 
		a.airwaybill_kode, 
		CONCAT
		(DATE_FORMAT(c.tanggal,"%Y-%m-%d")
		,' ', 
		 
		if(b.sla > 0,b.sla, 99)

			
			,' ',a.id_pengiriman	
			,' ',ifnull(b.target_sla,0)	
			,' ',a.pickup_date
			) as tanggal,
		
		c.no_smu, 
		x_namakustomer(a.id_kustomer) as nama_kustomer, 
		case when if(a.id_kota_asal>0,x_inter(a.id_kota_asal),x_intercity(a.lokasi_asal))='Y' then 'JAKARTA' else if(a.id_kota_asal>0,a.kota_asal,x_namakota(a.lokasi_asal)) end as kota_asal, 
		case when if(a.id_kota_tujuan>0,x_inter(a.id_kota_tujuan),x_intercity(a.lokasi_tujuan))='Y' then 'JAKARTA' else if(a.id_kota_asal>0,a.kota_tujuan,x_namakota(a.lokasi_tujuan)) end as kota_tujuan, 
		
		x_service(a.service) as service, 
		a.id_pengiriman ,
		concat(a.transit,',', CAST(x_namacabang(c.manifest_from) AS CHAR CHARACTER SET utf8 ) , ',', CAST(x_namacabang(c.manifest_to) AS CHAR CHARACTER SET utf8 ) ) as transit,
		
		CONCAT(a.id_pengiriman, ' ', 
		
		if(b.sla > 0,b.sla, if(a.service=5,3,2))

			 , ' ' ,DATE_FORMAT(c.tanggal,"%Y-%m-%d"),' ','$u[level]',' ',ifnull(b.target_sla,0),' ',if(c.no_smu is null or c.no_smu = '',9999,c.no_smu), ' ', c.id_manifest,' ', c.manifest_to	 ) as assignment

	from pengiriman a 
	left join manifest_detail b on b.id_manifest_detail=(select id_manifest_detail from manifest_detail y where y.id_pengiriman = a.id_pengiriman order by y.id_manifest_detail desc limit 1)
	inner join manifest c on c.id_manifest= b.id_manifest and c.no_smu != ''
	
	
	

	

	where a.status='3'  and a.service !=6 and a.pickup='Y' and a.delivery='N' 
	and if(a.id_kota_asal>0,x_inter(a.id_kota_asal),x_intercity(a.lokasi_asal))!='Y' 
	
	and x_inter(a.id_kota_tujuan) ='Y' 

	or (a.transit='Y' and a.status!=7 AND a.received_transit != 'Y' and a.status!=6 and a.status != 4 and a.status!=5  and if(a.id_kota_asal>0,x_inter(a.id_kota_asal),x_intercity(a.lokasi_asal))!='Y' AND c.manifest_to=39 )   group by a.id_pengiriman) temp
EOT;
			}
		
		}elseif($u['level']=='agent'){
		$agent = mysqli_query($con, 'select id_kota,id_kota_baru from cabang where id_cabang="'.$u['id_cabang'].'"');
		$a = mysqli_fetch_assoc($agent);
		$table = 
<<<EOT
	(
	select 
		a.airwaybill_kode, 
		CONCAT
		(DATE_FORMAT(c.tanggal,"%Y-%m-%d")
		,' ', 
		 
		if(b.sla > 0,b.sla, 99)

			
			,' ',a.id_pengiriman	
			,' ',ifnull(b.target_sla,0)	
			,' ',a.pickup_date
			) as tanggal,
		
		c.no_smu, 
		x_namakustomer(a.id_kustomer) as nama_kustomer, 
		case when if(a.id_kota_asal>0,x_inter(a.id_kota_asal),x_intercity(a.lokasi_asal))='Y' then 'JAKARTA' else if(a.id_kota_asal>0,a.kota_asal,x_namakota(a.lokasi_asal)) end as kota_asal, 
		case when if(a.id_kota_tujuan>0,x_inter(a.id_kota_tujuan),x_intercity(a.lokasi_tujuan))='Y' then 'JAKARTA' else if(a.id_kota_asal>0,a.kota_tujuan,x_namakota(a.lokasi_tujuan)) end as kota_tujuan, 
		x_service(a.service) as service, 
		a.id_pengiriman ,
		concat(a.transit,',', CAST(x_namacabang(c.manifest_from) AS CHAR CHARACTER SET utf8 ) , ',', CAST(x_namacabang(c.manifest_to) AS CHAR CHARACTER SET utf8 ) ) as transit,
		CONCAT(a.id_pengiriman, ' ', 
		
		if(b.sla > 0,b.sla, if(a.service=5,3,2))

			 , ' ' ,DATE_FORMAT(c.tanggal,"%Y-%m-%d"),' ','$u[level]',' ',ifnull(b.target_sla,0),' ',if(c.no_smu is null or c.no_smu = '',9999,c.no_smu), ' ', c.id_manifest,' ', c.manifest_to	 ) as assignment

	from pengiriman a 
	
	left join manifest_detail b on b.id_manifest_detail=(select id_manifest_detail from manifest_detail y where y.id_pengiriman = a.id_pengiriman order by y.id_manifest_detail desc limit 1) 

	inner join manifest c on c.id_manifest= b.id_manifest and c.no_smu != ''
	
	

	

	left join regencies g on g.id=a.id_kota_tujuan

	left join provinces h on h.id=g.province_id		


	where a.status='3'  and a.service !=6 and a.pickup='Y' and a.delivery='N' 
	and 

	(a.id_kota_tujuan=$a[id_kota_baru] or ifnull(g.hub,h.ibu_kota) =x_hubnew(a.id_kota_tujuan))

	and c.manifest_to=$u[id_cabang] 
	or (a.status=2 AND a.transit='Y' AND (a.received_transit = 'N' or a.received_transit is null and a.status!=6 and a.status != 4 and a.status!=5 ) AND c.manifest_to='$u[id_cabang]') 
	
	group by a.id_pengiriman
	) temp
EOT;
		}
		
		$primaryKey = 'id_pengiriman';

		$columns = array(
			array('db'		=> 'airwaybill_kode',
					'dt'	=> 0,
					'formatter'	=> function($d,$row){
						return '<td><center>'.$d.'</center></td>';
					}
			),
			array('db'	=> 'tanggal', 
					'dt'		=> 1,
					'formatter' => function( $d, $row ) {
						$pieces = explode(' ', $d);

						
						$jumlah_hari = $pieces[1];
						$tanggal =  $pieces[0];
						$id_pengiriman = $pieces[2];
						$req_date_change = $pieces[3];
						$pickup_date = $pieces[4];
						for($i=$jumlah_hari;$i>=1;$i--){
							$tanggal = date('Y-m-d',strtotime($tanggal.'+ 1 day'));
							$hari = date('l' ,strtotime($tanggal));
							if($hari== 'Sunday'){
								$i=$i+1;
								continue;
							}
						}
						if($req_date_change != 0){
							$sla = $req_date_change;
						}else{
							$sla = $tanggal;	
						}
						
									        
						$date_now =  date("Y-m-d");
						$date_request = $pieces[0];

						if($jumlah_hari != 99 || $req_date_change != 0){
							
							if ($date_now > $sla){

								$created_date = $pickup_date. ' -'.$sla . ' <button class="btn btn-xs btn-danger"><i class="fa fa-exclamation-circle"></i> </button>';
							}else{
								$created_date =  $pickup_date . ' -'.$sla;
							}
						}else{
							$created_date =  $pickup_date.  ' <button data-toggle="modal" data-target="#modal_sla_insert" data-id="'.$id_pengiriman.'" class="btn btn-xs btn-warning custom_slaoutbound_insert"><i class="fa fa-plus"></i> </button>';
						}

						$db_host = "127.0.0.1";
						$db_user = "cesco_user";
						$db_pass = "c1l4ndak33032";
						$db_name = "cesco_logistics";

						

						$con = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

						$key = 'asdf$1011';
						$encrypt = $_SESSION['id_user'];
						$id = base64_decrypt($encrypt,$key);
						$useraktif = mysqli_query($con, 'select level from user where id_user="'.$id.'"');
						$u = mysqli_fetch_assoc($useraktif);

						if($u['level'] == 'admin'){
							$pluscheck = '<input type="checkbox" form="plustanggal" name="selectawb[]" value="'.$id_pengiriman.'">';
						}else{
							$pluscheck = '';
						}
						$btn = ' <button class="btn btn-xs btn-primary detailawb" title="detail" onclick="detailwaktu(this.id)" id="'.$id_pengiriman.'"><i class="fa fa-clock-o"></i> </button>';

						return  $created_date.$pluscheck.$btn;
					}
			),
			array( 'db' => 'no_smu', 'dt'=> 2 ),
			array('db'			=> 'nama_kustomer', 'dt'=> 3 ),
			array('db'			=> 'kota_asal',
					'dt'		=> 4,
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
					'dt'		=> 5,
					'formatter'	=> function($d,$row){
						if($d=='Jakarta Selatan' || $d=='Jakarta Timur' || $d=='Jakarta Barat' || $d=='Jakarta Utara' || $d=='Jakarta Pusat'){
							$kota_tujuan = 'Jakarta';
						}else{
							$kota_tujuan = $d;
						}
						return '<td>'.strtoupper($kota_tujuan).'</td>';
					}
			),
			array( 'db' => 'service', 'dt'=> 6 ),
			array('db'			=> 'transit',
					'dt'		=> 7,
					'formatter'	=> function($d,$row){
					

						

						$pieces = explode(',', $d);

$transit = isset($pieces[0]) && $pieces[0] !== '' ? $pieces[0] : 'N';
$dari    = isset($pieces[1]) && $pieces[1] !== '' ? $pieces[1] : 'eror';
$tujuan  = isset($pieces[2]) && $pieces[2] !== '' ? $pieces[2] : 'eror';

						

						return '<td>'.$transit.', '.$dari.' -> '.$tujuan.'</td>';
					}
			),
			array(
				  'db'        => 'assignment',
				  'dt'        => 8,
				  'formatter' => function( $d, $row ) {
					
					//$date_now = date("Y-m-d");
					$pieces = explode(' ', $d);

					

					$id_pengiriman = $pieces[0];

					$req_date_change = $pieces[4];
					$no_smu = $pieces[5];
					

					$jumlah_hari = $pieces[1];
						$tanggal =  $pieces[2];
						for($i=$jumlah_hari;$i>=1;$i--){
							$tanggal = date('Y-m-d',strtotime($tanggal.'+ 1 day'));
							$hari = date('l' ,strtotime($tanggal));
							if($hari== 'Sunday'){
								$i=$i+1;
								continue;
							}
						}
						if($req_date_change != 0){
							$sla = $req_date_change;
						}else{
							$sla = $tanggal;	
						}
									        
						$date_now =  date("Y-m-d");
						$date_request = $pieces[2];

					

					$key = "asdf$1011";
					$level = $pieces[3];
					$manifest_to = $pieces[7];

					if($level == '' || $manifest_to == 39 || $level == 'network' || $level == 'inbound'){
						$assignment = '-';
					}else{
						if($jumlah_hari != '0' || $req_date_change != 0){


							if($jumlah_hari != 99){
								if($date_now > $sla){
									$assignment = '<a class="btn btn-xs btn-success open_inbound-received" id=1 data-id="'.base64_encrypt($id_pengiriman,$key).' 1"><i class="fa fa-hand-paper-o"></i></button>';						
								}else{
									$assignment = '<a class="btn btn-xs btn-success open_inbound-received" id=0 data-id="'.base64_encrypt($id_pengiriman,$key).' 0"><i class="fa fa-hand-paper-o"></i></a>';						
								}
							} 
							else{
								$assignment = '<a class="btn btn-xs btn-success open_inbound-received" id=0 data-id="'.base64_encrypt($id_pengiriman,$key).' 0"><i class="fa fa-hand-paper-o"></i> </a>';						
							}	
						}
						else{
							$id_manifest = $pieces[6];
							$key = "asdf$1011";
							$assignment = '<a  href="#" class="btn btn-xs btn-warning" title="SLA BELUM ADA, HUBUNGI MBA SRI, TANYAKAN SLA, HASILNYA KASIH TAU VICKY"><i class="fa fa-exclamation-circle"></i> </a>
							 
							';
						}
					}

					$key = "asdf$1011";

					 return $assignment.($level == 'admin' ? '
							<a  href="module/mod_manifest-inbound/manifest-inbound_action.php?module=manifest-inbound&act=direct&id='.$id_pengiriman.'" class="btn btn-xs btn-warning" title="direct"><i class="fa fa-location-arrow"></i> </a>' : '' )
					 			.($level == 'admin' ? '
							<a  href="module/mod_manifest-inbound/manifest-inbound_action.php?module=manifest-inbound&act=transit&id='.base64_encrypt($id_pengiriman,$key).'" class="btn btn-xs btn-warning" title="transit">T </a>' : '' )
					 			.($level == 'admin' ? '
							<a  href="module/mod_manifest-inbound/manifest-inbound_action.php?module=manifest-inbound&act=backtooutbound&id='.base64_encrypt($id_pengiriman,$key).'" class="btn btn-xs btn-warning" title="back to outbound"> << </a>' : '' )
					 			;
					
				  }
			)

		);

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
		
	}elseif($module=='manifest-inbound' AND $act=='update'){
		$berat_aktual = mysqli_real_escape_string($con, $_POST['berat_aktual']);
		$collie = mysqli_real_escape_string($con, $_POST['collie']);
		$pcs = mysqli_real_escape_string($con, $_POST['pcs']);
		$packing_kayu = mysqli_real_escape_string($con, $_POST['packing_kayu']);
		$panjang = mysqli_real_escape_string($con, $_POST['panjang']);
		$lebar = mysqli_real_escape_string($con, $_POST['lebar']);
		$tinggi = mysqli_real_escape_string($con, $_POST['tinggi']);
		$lokasi_tujuan = mysqli_real_escape_string($con, $_POST['lokasi_tujuan']);
		$id_manifest = mysqli_real_escape_string($con, $_POST['id_manifest']);
		$manifest_by = mysqli_real_escape_string($con, $_POST['manifest_by']);
		$transit = mysqli_real_escape_string($con, $_POST['transit']);
		$reason = mysqli_real_escape_string($con, $_POST['reason']);
		$tanggal_now = date('Y-m-d');
		if ($reason != ''){
			$reason = $reason;
			$diskon_inbound = 20;
		}
		else{
			$reason = '';
			$diskon_inbound = 0;
		}
		
		$ukuran_barang = $panjang.'x'.$lebar.'x'.$tinggi;
		/*echo "UPDATE pengiriman set status='4',
													manifest='Y',
													berat_aktual='$berat_aktual',
													ukuran_barang='$ukuran_barang',
													collie='$collie',
													pcs='$pcs',
													packing_kayu='$packing_kayu',
													manifest_received='$tgl_sekarang',
													manifest_by='$u[id_user]'
							WHERE id_pengiriman = '$_POST[id]'";*/
		if($transit ==  'Y'){
			//echo 'transit';
		
			//return false;
				mysqli_query($con, "UPDATE pengiriman set
													
													received_transit ='Y',
													manifest_outbound_date_target =  null,
																									
													received_transit_by = '$manifest_by'
													
							WHERE id_pengiriman = '$_POST[id]'");

				mysqli_query($con, "UPDATE manifest_detail set 
													
														inbound_received =now(),
														inbound_by =$manifest_by,
														diskon_inbound = '$diskon_inbound',
														alasan_terlambat_inbound = '$reason'

																									
													
							WHERE id_pengiriman = '$_POST[id]' order by id_manifest_detail desc limit 1");

				mysqli_query($con, "UPDATE manifest set 
													
														manifest_inbound_by =$manifest_by
																																						
							WHERE id_manifest = '$id_manifest'");
		}
		else{
			//echo 'no transit';
			//return false;
				mysqli_query($con, "UPDATE pengiriman set status='4',
													id_kota_tujuan='$lokasi_tujuan',
													manifest='Y',
													
													
													
													manifest_received='$tgl_sekarang',
													manifest_by='$manifest_by',
													alasan_terlambat_inbound = '$reason'
							WHERE id_pengiriman = '$_POST[id]'");

							mysqli_query($con, "UPDATE manifest_detail set 
													
														inbound_received =now(),
														
														diskon_inbound = '$diskon_inbound',
														inbound_by =$manifest_by,
														alasan_terlambat_inbound = '$reason'
																									
													
							WHERE id_pengiriman = '$_POST[id]' order by id_manifest_detail desc limit 1");

							mysqli_query($con, "UPDATE manifest set 
													
														manifest_inbound_by =$manifest_by
																																						
							WHERE id_manifest = '$id_manifest'");
		}
		
		header('location:../../main.php?module='.$module);
	
	}elseif($module=='manifest-inbound' AND $act=='received'){
		$pieces = explode(' ', $_POST['id']);

		$id_pengiriman = $pieces[0];
		$sla = $pieces[1];

		$key = "asdf$1011";

		$encrypt = "$pieces[0]";
		$id = base64_decrypt($encrypt,$key);
		
		// detail
		$query = mysqli_query($con, "SELECT coalesce(a.berat_aktual,a.berat_barang) as berat,a.transit as transitp,a.received_transit, a.collie, a.pcs, b.id_manifest, a.packing_kayu, b.manifest_from, b.manifest_to, a.lokasi_tujuan ,SUBSTRING_INDEX(ukuran_barang,'x',1) as length, 
									SUBSTRING_INDEX(SUBSTRING_INDEX(ukuran_barang,'x',2),'x',-1) as width, 
									SUBSTRING_INDEX(ukuran_barang,'x',-1) as height,x_namacabang(b.manifest_from) as cabang_asal, x_namacabang(b.manifest_to) as cabang_tujuan,a.id_kota_tujuan, a.id_kota_asal  

									FROM  pengiriman a 

									left join manifest_detail c on c.id_manifest_detail = (select y.id_manifest_detail from manifest_detail y where y.id_pengiriman = a.id_pengiriman order by y.id_manifest_detail desc limit 1) 
                                    
                                    left JOIN manifest b on b.id_manifest=c.id_manifest  

                                    WHERE a.id_pengiriman=$id");
		
		$r = mysqli_fetch_assoc($query);

		$to = mysqli_query($con, "SELECT x_inter(id_kota_tujuan) as intercity_tujuan, lokasi_tujuan FROM pengiriman WHERE id_pengiriman='".$id."'");

		$r_to = mysqli_fetch_assoc($to);

		$intercity_tujuan = $r_to['intercity_tujuan'];

		if($r['id_kota_asal'] > 0){
			$cabang_kota = 'id_kota_baru';
		}else{
			$cabang_kota = 'id_kota';
		}
		
		echo'
		<form action="'.$aksi.'?module=manifest-inbound&act=update" name="modal_popup" method="POST" id="form-inbound-received">
			<input type="hidden" name="id" class="form-control" value="'.$id.'" />
			<input type="hidden" name="id_manifest" class="form-control" value="'.$r['id_manifest'].'" />
			';

			if($r['transitp'] == 'Y' && $r['received_transit'] != 'Y'){
				echo '<input type="hidden" name="transit" class="form-control" value="Y" />';
			} 
			echo '<h3><i class="fa fa-warning text-yellow"></i>Update Edit data collie,KG dll, klik <a href="https://cesco-logistics.com/e-cesco/main.php?module=airwaybill&act=outbound&id='.$pieces[0].'"  target="_blank">disini</a> </h3>';
			echo '
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label>Weight: *</label>
						<div class="input-group">
							<input type="text" class="form-control style="width: 100%;" value="'.$r['berat'].'" name="berat_aktual" required readonly> <span class="input-group-addon">Kg</span>
						</div>						
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label>Collie: *</label>
						<input type="text" class="form-control style="width: 100%;" value="'.$r['collie'].'" name="collie" required readonly> 
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label>Pcs: *</label>
						<input type="text" class="form-control style="width: 100%;" value="'.$r['pcs'].'" name="pcs" required readonly>						
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label>Packing Kayu: *</label>
						<select name="packing_kayu" class="form-control style="width: 100%;" required>
							<option value=""></option>
							<option '.($r['packing_kayu'] == 1 ? 'selected' : '').' value="1">Packing Oleh Pemilik</option>
							<option '.($r['packing_kayu'] == 2 ? 'selected' : '').' value="2">Packing Kayu Oleh Cesco Log.</option>
							<option '.($r['packing_kayu'] == 3 ? 'selected' : '').' value="3">Non Packing Kayu Oleh Cesco Log.</option>
						</select>
					</div>
				</div>
			</div>
			';
			/*
			<div class="row">
				<div class="col-md-4">
					<div class="form-group">
						<label>PANJANG(CM): *</label>
						<input type="number" class="form-control style="width: 100%;" value="'.$r[width].'" name="panjang" required>						
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						<label>LEBAR:(CM) *</label>
						<input type="number" class="form-control style="width: 100%;" value="'.$r[length].'" name="lebar" required>						
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						<label>TINGGI:(CM) *</label>
						<input type="number" class="form-control style="width: 100%;" value="'.$r[height].'" name="tinggi" required>						
					</div>
				</div>
			</div>*/
			echo '


			<div class="row">
				<div class="form-group" id="kotatujuan">
					<label class="col-md-3 control-label">Kota Tujuan <span class="required">* </span></label>
					<div class="col-md-12">
						<select name="lokasi_tujuan" id="lokasi_tujuan" data-placeholder="Choose a City..." class="form-control select2">
							<option value="">Select...</option>';
							$kotatujuan = mysqli_query($con, "select id,name from regencies order by name");
									while($t=mysqli_fetch_assoc($kotatujuan)){
											echo'
											<option value="'.$t['id'].'" '.($t['id'] == $r['id_kota_tujuan'] ? 'selected' : '' ).'>'.$t['name'].'</option>';
									}
							echo'
						</select>
						<input type="hidden" name="intercitytujuan" id="intercitytujuan">
						<div class="val-message"></div>
					</div>
				</div>
			</div>
			<div class="form-group" style="padding-bottom: 5px;">
                <label>Manifest From:</label>
                <input type="hidden" name="manifest_from" class="form-control" value="'.$r['manifest_from'].'" width="100%" readonly>
                <input type="text" name="manifest_from_show" class="form-control" value="'.$r['cabang_asal'].'" width="100%" readonly>
				';

				
				echo '
			</div>
			';
			if($r['transitp'] == 'Y' && $r['received_transit'] != 'Y'){
					echo '
					<div class="form-group" style="padding-bottom: 5px;">
		                <label>Manifest Transit:</label>
						<select name="manifest_transit" class="form-control select2" width="100%" readonly>';
							echo '<option value=""><b>'.$r['cabang_tujuan'].'</b></option>';
							echo '
						</select>
					</div>	';
			} 

			echo '
			<div class="form-group" style="padding-bottom: 5px;">
                <label>Manifest To:</label>
				<select name="manifest_to" class="form-control select2" width="100%" readonly>';
					$agent = mysqli_query($con, 'select id_kota,nama_cabang,alamat1 from cabang where id_cabang="'.$u['id_cabang'].'"');
					$a = mysqli_fetch_assoc($agent);
					
					if($u['level'] == 'agent'){
						echo '<option value="' . $u['id_cabang'] . '" selected><b>'.strtoupper($a['nama_cabang']).'</b> ('.strtoupper($a['alamat1']).')</option>';
					}else{
						$result = mysqli_query($con,"select 
							case when if(a.id_kota_tujuan>0,x_inter(a.id_kota_tujuan),x_intercity(a.lokasi_tujuan))='Y' then '39' else c.id_cabang end as id_cabang, case when if(a.id_kota_tujuan>0,x_inter(a.id_kota_tujuan),x_intercity(a.lokasi_tujuan))='Y' then 'JAKARTA' else c.nama_cabang end as nama_cabang, case when if(a.id_kota_tujuan>0,x_inter(a.id_kota_tujuan),x_intercity(a.lokasi_tujuan))='Y' then 'CESCO LOGISTICS' else c.alamat1 end as alamat1 

							from pengiriman a 
							left join kota b on b.id_kota=a.lokasi_tujuan 
							left join cabang c on if(a.id_kota_tujuan>0,c.id_kota_baru,c.id_kota) = if(a.id_kota_tujuan>0,x_hubnew(c.id_kota_baru),b.hub) or if(a.id_kota_tujuan>0,a.id_kota_tujuan,c.id_kota)= if(a.id_kota_tujuan>0,a.id_kota_tujuan,a.lokasi_tujuan )
							left join regencies d on d.id=a.id_kota_tujuan							

							where a.id_pengiriman='".$id."' and (c.status != 'N' or c.status is null) group by c.id_cabang order by c.nama_cabang");
						
						while ($row = mysqli_fetch_assoc($result)) {
							if($r['manifest_to']==$row['id_cabang']){
								echo '<option value="' . $row['id_cabang'] . '" selected><b>'.strtoupper($row['nama_cabang']).'</b> ('.strtoupper($row['alamat1']).')</option>';
							}else{
								echo '<option value="' . $row['id_cabang'] . '"><b>'.strtoupper($row['nama_cabang']).'</b> ('.strtoupper($row['alamat1']).')</option>';
							}
						}
					}
					echo '
				</select>
			</div>
			<div class="form-group" style="padding-bottom: 15px;">
				<label class="col-md-3 control-label">Diterima oleh:</label>
				<input type="text" name="diterima" class="form-control" value="'.$u['namalengkap'].'" width="100%" readonly>
			</div>
			<div class="form-group" style="padding-bottom: 5px;">
                <label>Manifest Inbound By: (Lapangan)</label>
				<select name="manifest_by" class="form-control select2" width="100%">
					';
					
						$result = mysqli_query($con,"select id_user, namalengkap from user where (level='agent' or level='kurir_agent' or level='kurir' or level='driver') AND blokir != 'Y' and id_cabang=".$r['manifest_to']." order by namalengkap");
								while ($row = mysqli_fetch_assoc($result)){
									echo '<option value="'.$row['id_user'].'">'.strtoupper($row['namalengkap']).'</option>';
								}
					
					echo '<option value="56">Andry HL(Hanoman Logistics)</option>';
					echo 

					'
						
					
				</select>
			</div>
			';
			if($sla==1){
				
					echo '<div class="form-group" style="padding-bottom: 5px;">
	                	<label>ALASAN TERLAMBAT:</label>
	                	<input type="text" class="form-control style="width: 100%;" name="reason" minlength="10" required>						
	            	</div>';	
				
				

	         }
	         echo '
     		<div class="modal-footer">
				<button class="btn btn-success" type="submit" >Confirm</button>
				<button type="reset" class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Cancel</button>
			</div>
		</form>';
	}
	elseif ($module=='manifest-inbound' AND $act=='plus'){
		$tanggal		= jin_date_sql($_POST['tanggalplus']);
		$awb		= $_POST['selectawb'];
		$id_awb		= implode(',',$awb);
		$alasan 	= $_POST['alasan_plus'];
		//echo "update pengiriman set manifest_received='$tanggal' where id_pengiriman in ($id_awb)";

		//mysqli_query($con, "update manifest_detail set manifest_received='$tanggal' where id_pengiriman in ($id_awb)");
		foreach ($_POST['selectawb'] as $k) {

			$sql = "select id_manifest_detail from manifest_detail  where id_pengiriman = '$k' order by id_manifest_detail desc limit 1";
			$r = mysqli_fetch_assoc(mysqli_query($con,$sql));


			mysqli_query($con, "update manifest_detail set target_sla='$tanggal', alasan_plus_inbound='$alasan' where id_manifest_detail='$r[id_manifest_detail]'");

		//echo "update manifest_detail set target_sla='$tanggal' where id_manifest_detail='$r[id_manifest_detail]' <br>";
		}

		header('location:../../main.php?module='.$module);
	}

	elseif ($module=='manifest-inbound' AND $act=='direct'){
		
		echo $id_awb		= $_GET['id'];
		//echo "update pengiriman set manifest_received='$tanggal' where id_pengiriman in ($id_awb)";
		$sql = "select id_manifest from manifest_detail z where z.id_pengiriman = ".$id_awb." order by z.id_manifest_detail desc limit 1";
		$r = mysqli_fetch_assoc(mysqli_query($con,$sql));

		//echo $r['id_manifest'];

		$m = "select manifest_to from manifest where id_manifest=".$r['id_manifest']." ";
		$row = mysqli_fetch_assoc(mysqli_query($con,$m));

		//$row['manifest_to'];

		mysqli_query($con, "update pengiriman set status=3,transit='N',received_transit=null, delivery_by_agent=".$row['manifest_to']." where id_pengiriman= $id_awb");
		header('location:../../main.php?module='.$module);
	}


	elseif ($module=='manifest-inbound' AND $act=='backtooutbound'){
		
		 $id		= $_GET['id'];

		 $key = "asdf$1011";
		$encrypt = $id;
		$id_pengiriman = base64_decrypt($encrypt,$key);
		
		/*echo "update pengiriman set status='2', manifest='N', transit='N', received_transit=null,delivery='N', delivery_name=null, courier_date=null, courier_by=null where id_pengiriman='$id_pengiriman'";

		echo "delete manifest_detail from manifest_detail a left join manifest b on a.id_maniest = b.id_manifest   where a.id_pengiriman=$id_pengiriman and b.tipe_manifest != 'pickup' ";*/


		
		mysqli_query($con, "update pengiriman set status='2', manifest='N', transit='N', received_transit=null,delivery='N', delivery_name=null, courier_date=null, courier_by=null,delivery_by_agent=null where id_pengiriman='$id_pengiriman' ");

		mysqli_query($con, "delete a from manifest_detail a left join manifest b on a.id_manifest = b.id_manifest   where a.id_pengiriman=$id_pengiriman and (b.tipe_manifest != 'pickup' or b.tipe_manifest is null ) ");

		
		header('location:../../main.php?module='.$module);
	}


	elseif ($module=='manifest-inbound' AND $act=='transit'){
		
		$encrypt = $_GET['id'];
		$key = 'asdf$1011';
		$id_awb = base64_decrypt($encrypt,$key);
		 
		 //$id_awb		= $_GET['id'];
		//echo "update pengiriman set manifest_received='$tanggal' where id_pengiriman in ($id_awb)";
		//$sql = "select id_manifest from manifest_detail z where z.id_pengiriman = ".$id_awb." order by z.id_manifest_detail desc limit 1";
		//$r = mysqli_fetch_assoc(mysqli_query($con,$sql));

		//echo $r['id_manifest'];

		//$m = "select manifest_to from manifest where id_manifest=".$r['id_manifest']." ";
		//$row = mysqli_fetch_assoc(mysqli_query($con,$m));

		//$row['manifest_to'];

		mysqli_query($con, "update pengiriman set status=2,transit='Y',received_transit='N', delivery_by_agent=null where id_pengiriman= $id_awb");
		header('location:../../main.php?module='.$module);
	}
	elseif ($module=='manifest-inbound' AND $act=='form-insert-sla')
	{	
		
		//echo 'test';
		$id = $_POST['id'];
		
		$query = mysqli_query($con, "select service, x_service(service) as jenis_layanan from pengiriman where id_pengiriman='$id'");
			$r = mysqli_fetch_assoc($query);

			$q = mysqli_query($con, "select id_manifest_detail,a.id_manifest,a.id_pengiriman,x_idkotacabang(b.manifest_from) as id_kota_from,x_idkotacabang(b.manifest_to) as id_kota_to, x_namakotanew( (select id_kota_from) ) as namakota_from, x_namakotanew( (select id_kota_to) ) as namakota_to , c.id_kota_tujuan, c.id_kota_asal

				from manifest_detail a 
				inner join manifest b on a.id_manifest = b.id_manifest 
				inner join pengiriman c on a.id_pengiriman = c.id_pengiriman 
				where a.id_pengiriman =$id order by a.id_manifest_detail
				 desc limit 1
				");

			
			$row = mysqli_fetch_assoc($q);
		
		echo'
		<form action="'.$aksi.'?module=manifest-inbound&act=insert-sla" method="POST">
			
			<div class="form-group" style="padding-bottom: 25px;">
				<label class="col-md-3 control-label">Service Inbound</label>
				<div class="col-md-8">
					<input type="text" value="'.$r['jenis_layanan'].'" class="form-control" readonly>
				</div>
			</div>			
			
			<div class="form-group" style="padding-bottom: 25px;">
				<label class="col-md-3 control-label">Cabang Asal</label>
				<div class="col-md-8">
					<input type="text" value="'.$row['namakota_from'].'" class="form-control" readonly>
				</div>
			</div>			
			<div class="form-group" style="padding-bottom: 25px;">
				<label class="col-md-3 control-label">Cabang Tujuan</label>
				<div class="col-md-8">
					<input type="text" value="'.$row['namakota_to'].'" class="form-control" readonly>
				</div>
			</div>			
						
			
			<div class="form-group" style="padding-bottom: 25px;">
				<label class="col-md-3 control-label">Lead Time / SLA:</label>
				<div class="col-md-8">
					<input type="number" name="sla" class="form-control" value="1" min="1" required/>
				</div>
			</div>
			<input type="hidden" name="id_pengiriman" class="form-control" value="'.$id.'" />
			<input type="hidden" name="id_manifest_detail" class="form-control" value="'.$row['id_manifest_detail'].'" />
			
			<input type="text" name="id_lokasi_asal" class="form-control" value="'.$row['id_kota_asal'].'" />
			<input type="text" name="id_lokasi_tujuan" class="form-control" value="'.$row['id_kota_tujuan'].'" />			
			<input type="hidden" name="id_pelayanan" class="form-control" value="'.$r['service'].'" />
			
			<div class="modal-footer">
				<button class="btn btn-success" type="submit">insert</button>
				<button type="reset" class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Cancel</button>
			</div>
		</form>';
		
	}elseif ($module=='manifest-inbound' AND $act=='insert-sla'){
		$id = $_POST['id_pengiriman'];
		$sla = $_POST['sla'];
		$idkota_asal = $_POST['id_lokasi_asal'];
		$idkota_tujuan = $_POST['id_lokasi_tujuan'];

		$result = mysqli_query($con, "SELECT  x_inter($idkota_asal) as intercity_asal, x_inter($idkota_tujuan) as intercity_tujuan, service FROM pengiriman WHERE id_pengiriman = $id ");
		$row = mysqli_fetch_assoc($result);

		echo "SELECT  x_inter($idkota_asal) as intercity_asal, x_inter($idkota_tujuan) as intercity_tujuan, service FROM pengiriman WHERE id_pengiriman = $id ";

		$id_manifest_detail = $_POST['id_manifest_detail'];

		 $inter_asal = $row['intercity_asal'];
		 $inter_tujuan = $row['intercity_tujuan'];

		if($inter_asal == 'Y' and $inter_tujuan == ''){
			//outbound
			mysqli_query($con, "insert into  sla (id,kota_asal,kota_tujuan,service,sla_max) values ('',9999,$idkota_tujuan,$row[service],$sla)");
		}
		if($inter_asal == '' and $inter_tujuan == 'Y'){
			mysqli_query($con, "insert into  sla_in (id,kota_asal,kota_tujuan,service,sla_max) values ('',$idkota_asal,9999,$row[service],$sla)");
		}
		if($inter_asal == '' and $inter_tujuan == ''){
			mysqli_query($con, "insert into  sla_cross (id,kota_asal,kota_tujuan,service,sla_max) values ('',$idkota_asal,$idkota_tujuan,$row[service],$sla)");
		}

		$q = mysqli_query($con, "SELECT  id_manifest from manifest_detail where id_manifest_detail=$id_manifest_detail ");
		$r = mysqli_fetch_assoc($q);



		mysqli_query($con, "update manifest_detail set sla=$sla where id_manifest=$r[id_manifest]");

		//echo "update manifest_detail set sla=$sla where id_manifest=$r[id_manifest]";

		//echo "<br>insert into  sla (id,kota_asal,kota_tujuan,service,sla_max) values ('',$row[id_kota_asal],$row[id_kota_tujuan],$row[service],$sla)";
		
		
		header('Location: ' . $_SERVER['HTTP_REFERER']);
	}

	elseif ($module=='manifest-inbound' AND $act=='scanawb'){


		
		$awb = $_POST['kode_awb'];
		//$id_kurir = $_POST['kurir'];
		$tanggal = $_POST['tgl'];
		$manifest_by = $_POST['by'];

		

		$result = mysqli_query($con, "SELECT id_pengiriman,kota_asal,kota_tujuan,status,transit,received_transit ,x_namakustomer(id_kustomer) as nama_kustomer,x_service(service) as nama_service,pickup_date,manifest_date from pengiriman where airwaybill_kode='$awb'");
		
		$ketemu	= mysqli_num_rows($result);
		if($ketemu > 0){
			$reason = "";

			$row = mysqli_fetch_assoc($result);
			if($row['status'] == 3 || ($row['status'] == 2 && $row['transit'] == 'Y' && $row['received_transit'] == 'N')){

					


				if($row['status'] == 3){
					mysqli_query($con, "UPDATE pengiriman set status='4',
													
													manifest='Y',
													
													manifest_date=$tgl_sekarang,
													
													manifest_received='$tgl_sekarang',
													manifest_by='$manifest_by',
													alasan_terlambat_inbound = '$reason'
							WHERE id_pengiriman = '$row[id_pengiriman]'");

							

							
				}else{

					mysqli_query($con, "UPDATE pengiriman set
													
													received_transit ='Y',
													manifest_outbound_date_target =  null,
																									
													received_transit_by = '$manifest_by'
													
							WHERE id_pengiriman = '$row[id_pengiriman]'");
				}
				mysqli_query($con, "UPDATE manifest_detail set  inbound_received =now() WHERE id_pengiriman = '$row[id_pengiriman]' order by id_manifest_detail desc limit 1");

							
							//select buat cari manifest
							 $sql = "SELECT id_manifest from manifest_detail WHERE id_pengiriman = '$row[id_pengiriman]' order by id_manifest_detail desc limit 1";

							$r = mysqli_fetch_assoc(mysqli_query($con,$sql));

							//update manifest
							mysqli_query($con, "UPDATE manifest set 	manifest_inbound_by =$manifest_by WHERE id_manifest = '$r[id_manifest]'");
				

				
			
				echo json_encode(array("success"=>1,"message"=> "AIRWAYBILL $awb BERHASIL DI MANIFEST", "pengiriman" => $row));

			}else{
				$succes = array("success"=>0,"message"=> "AIRWAYBILL $awb Belum berangkat dari kota asal atau sudah sampai kota tujuan");
				echo json_encode($succes);
			}

			//echo $ketemu;
		}else{
			$succes = array("success"=>0,"message"=> "AIRWAYBILL $awb TIDAK ADA");
			echo json_encode($succes);
		}

		
		//echo 'oke';
	}
}
?>
