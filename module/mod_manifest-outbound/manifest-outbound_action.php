<?php
if(!isset($_SESSION)){
    session_start();
}

if (empty($_SESSION['username']) AND empty($_SESSION['password'])){
	header('location:../login.php');
}else{
	include "../../config/connection.php";
	include "../../config/db_connect.php";
	include "../../config/library.php";
	include "../../config/encrypt.php";
	
	$module	= !empty($_GET['module']) ? $_GET['module'] : '';
	$act	= !empty($_GET['act']) ? $_GET['act'] : '';
	
	$aksi="module/mod_manifest-outbound/manifest-outbound_action.php";

	$key = 'asdf$1011';
	$encrypt = $_SESSION['id_user'];
	$id = base64_decrypt($encrypt,$key);
	$useraktif = mysqli_query($con, 'select * from user where id_user="'.$id.'"');
	$u = mysqli_fetch_assoc($useraktif);
	
	// load data
	if($module=='manifest-outbound' and $act=='data'){
		if($u['level']=='admin' || $u['level'] == 'finance'){
		$table = 
<<<EOT
	(
	select 
		a.airwaybill_kode,		

		CONCAT(DATE_FORMAT(a.pickup_date,"%Y-%m-%d")
			,' ',ifnull(DATE_FORMAT(d.inbound_received,"%Y-%m-%d"),DATE_FORMAT(a.pickup_date,"%Y-%m-%d") ),' ',a.id_pengiriman,' ',a.transit,' ',ifnull(d.target_sla,0), ' ',  ifnull(a.manifest_outbound_date_target,0),' ','$u[level]') as created_date,

		x_namakustomer(a.id_kustomer) as nama_kustomer,
		case when id_kota_asal>0 then a.kota_asal else x_namakotabesar(a.lokasi_asal) end as kota_asal,
		case when id_kota_asal>0 then a.kota_tujuan else x_namakotabesar(a.lokasi_tujuan) end as kota_tujuan,
		x_service(a.service) as service,
		case when a.service='6' then x_namanegara(a.lokasi_tujuan) else x_namakotanew(x_hubnew(a.id_kota_tujuan)) end as hub,
		CONCAT(a.transit,' ',a.id_pengiriman) as transit_concat,
		a.id_pengiriman,
		CONCAT(a.id_pengiriman
			, ' ' ,DATE_FORMAT(pickup_date,"%Y-%m-%d")
			, ' ' ,ifnull(DATE_FORMAT(d.inbound_received,"%Y-%m-%d"),DATE_FORMAT(a.pickup_date,"%Y-%m-%d") ) 
			
			,' ',a.transit,' ','$u[level]',' ',ifnull(d.target_sla,0)
			, ' ',  ifnull(a.manifest_outbound_date_target,0)
			, ' ',  if(c.manifest_from > 0 and c.no_smu = '',c.id_manifest,0 )
			, ' ', if(c.manifest_from > 0, c.manifest_to, a.pickup_by_agent)

		) as assignment
	from pengiriman a  

	left join manifest_detail d on d.id_manifest_detail = (select z.id_manifest_detail from manifest_detail z where z.id_pengiriman = a.id_pengiriman order by z.id_manifest_detail desc limit 1)

	left join manifest c on c.id_manifest = d.id_manifest 

	where a.status='2' and a.pickup='Y' and a.delivery='N' and 
	   (
	   
	   (x_inter(a.id_kota_asal)='Y' and x_inter(a.id_kota_tujuan) !='Y' )

	   or (x_inter(a.id_kota_asal) !='Y' and x_inter(a.id_kota_tujuan)='Y') 

	   or (x_inter(a.id_kota_asal) !='Y' and x_inter(a.id_kota_tujuan) !='Y')

	   ) 
	   and  a.status!='7' and a.service !=6
	   and (a.transit!='Y'  and a.delivery='N'   or (a.transit='Y' AND a.received_transit = 'Y')   ) 
	  
	  or ( (a.status = 3 or (a.status=2 and a.transit='Y' AND a.received_transit = 'N')  ) and (c.no_smu= '' or no_smu is null) )

	   group by a.id_pengiriman


	) temp
EOT;
		}

		if($u['level']=='operator'){
		$table = 
<<<EOT
	(
	select 
		a.airwaybill_kode,		

		CONCAT(DATE_FORMAT(a.pickup_date,"%Y-%m-%d")
			,' ',ifnull(DATE_FORMAT(d.inbound_received,"%Y-%m-%d"),DATE_FORMAT(a.pickup_date,"%Y-%m-%d") ),' ',a.id_pengiriman,' ',a.transit,' ',ifnull(d.target_sla,0), ' ',  ifnull(a.manifest_outbound_date_target,0),' ','$u[level]') as created_date,

		x_namakustomer(a.id_kustomer) as nama_kustomer,
		case when id_kota_asal>0 then a.kota_asal else x_namakotabesar(a.lokasi_asal) end as kota_asal,
		case when id_kota_asal>0 then a.kota_tujuan else x_namakotabesar(a.lokasi_tujuan) end as kota_tujuan,
		x_service(a.service) as service,
		case when a.service='6' then x_namanegara(a.lokasi_tujuan) else x_namakotanew(x_hubnew(a.id_kota_tujuan)) end as hub,
		
		CONCAT(a.transit,' ',a.id_pengiriman) as transit_concat,
		a.id_pengiriman,
		CONCAT(a.id_pengiriman
			, ' ' ,DATE_FORMAT(pickup_date,"%Y-%m-%d")
			, ' ' ,ifnull(DATE_FORMAT(d.inbound_received,"%Y-%m-%d"),DATE_FORMAT(a.pickup_date,"%Y-%m-%d") ) 
			
			,' ',a.transit,' ','$u[level]',' ',ifnull(d.target_sla,0)
			, ' ',  ifnull(a.manifest_outbound_date_target,0)
			, ' ',  if(c.manifest_from > 0 and c.no_smu = '',c.id_manifest,0 )
			, ' ', if(c.manifest_from > 0, c.manifest_to, a.pickup_by_agent)

		) as assignment
	from pengiriman a  

	left join manifest_detail d on d.id_manifest_detail = (select z.id_manifest_detail from manifest_detail z where z.id_pengiriman = a.id_pengiriman order by z.id_manifest_detail desc limit 1)

	left join manifest c on c.id_manifest = d.id_manifest 

	where a.status='2' and a.pickup='Y' and a.delivery='N' and 
	   (
	   
	   (x_inter(a.id_kota_asal)='Y' and x_inter(a.id_kota_tujuan) !='Y' )

	  

	   ) 
	   and  a.status!='7' and a.service !=6
	   and (a.transit!='Y'  or (a.transit='Y' AND a.received_transit = 'Y')   ) 
	  
	  or ( (a.status = 3 and (x_inter(a.id_kota_asal)='Y' and x_inter(a.id_kota_tujuan) !='Y' )  or (a.status=2 and a.transit='Y' AND a.received_transit = 'N')  ) and (c.no_smu= '' or no_smu is null) )

	   group by a.id_pengiriman
	) temp
EOT;
		}
	
		elseif($u['level']=='domex'){
		
		if($u['tambahan_admin'] == 1){
					$table = 
<<<EOT
	(
	select 
		a.airwaybill_kode,
		CONCAT(DATE_FORMAT(a.pickup_date,"%Y-%m-%d"),' ', 
		
		case when a.service <= 3 then 1 
		 	
		when a.service = 12 then 1 
		else 
			ifnull(ifnull(b.sla_max,ifnull(c.sla_max,d.sla_max)),99  )
		end
			,' ',DATE_FORMAT(a.pickup_date,"%Y-%m-%d"),' ',a.id_pengiriman,' ',a.transit,' ',ifnull(a.manifest_received,0),' ','$u[level]' ) as created_date,

		x_namakustomer(a.id_kustomer) as nama_kustomer,
		case when lokasi_asal=0 then a.kota_asal else x_namakotabesar(a.lokasi_asal) end as kota_asal,
		case when lokasi_tujuan=0 then a.kota_tujuan else x_namakotabesar(a.lokasi_tujuan) end as kota_tujuan,
		x_service(a.service) as service,
		case when a.service='6' then x_namanegara(a.lokasi_tujuan) else x_namakotanew(x_hubnew(a.id_kota_tujuan)) end as hub,
		
		CONCAT(a.transit,' ',a.id_pengiriman) as transit_concat,
		a.id_pengiriman,
		CONCAT(id_pengiriman, ' ', 
		case when a.service <= 3 then 1 
		 	
		when a.service = 12 then 1 
		else 
			ifnull(ifnull(b.sla_max,ifnull(c.sla_max,d.sla_max)),99  )
		end
			, ' ' ,DATE_FORMAT(pickup_date,"%Y-%m-%d"), ' ',a.transit,' ','$u[level]',' ',ifnull(a.manifest_received,0)
			, ' ', if(c.manifest_from > 0, c.manifest_to, a.pickup_by_agent)
			) as assignment
	from pengiriman a 
	
	left join sla_cross b on b.kota_asal =  a.id_kota_asal   AND b.kota_tujuan=  a.id_kota_tujuan  AND b.service = a.service	

	left join sla c on c.kota_asal =  if(x_inter(a.id_kota_asal)='Y',9999,0)   AND c.kota_tujuan= a.id_kota_tujuan  AND c.service = a.service

	left join sla_in d on d.kota_asal =  a.id_kota_asal   AND d.kota_tujuan= if(x_inter(a.id_kota_tujuan)='Y',9999,0)  AND d.service = a.service

	where a.status='2' and a.pickup='Y' and a.delivery='N' and ((if(a.id_kota_asal>0,x_inter(a.id_kota_asal),x_intercity(a.lokasi_asal))='Y' and if(a.id_kota_tujuan>0,x_inter(a.id_kota_tujuan),x_intercity(a.lokasi_tujuan))!='Y') or (if(a.id_kota_asal>0,x_inter(a.id_kota_asal),x_intercity(a.lokasi_asal))!='Y' and if(a.id_kota_tujuan>0,x_inter(a.id_kota_tujuan),if(a.id_kota_tujuan>0,x_inter(a.id_kota_tujuan),x_intercity(a.lokasi_tujuan)))='Y') or (if(a.id_kota_asal>0,x_inter(a.id_kota_asal),x_intercity(a.lokasi_asal))!='Y' and if(a.id_kota_tujuan>0,x_inter(a.id_kota_tujuan),if(a.id_kota_tujuan>0,x_inter(a.id_kota_tujuan),x_intercity(a.lokasi_tujuan)))!='Y')) and (id_manifest='0' or id_manifest is null) and a.status!='7'  and a.service !=6 and (a.transit!='Y'  or (a.transit='Y' AND a.received_transit = 'Y')  ) group by a.id_pengiriman
	) temp
EOT;
		}
		else{

		if ($u['id_kustomer'] > 0 ){
			$table = 
<<<EOT
	(
	select 
		a.airwaybill_kode,
		CONCAT(DATE_FORMAT(a.pickup_date,"%Y-%m-%d"),' ', 
		
		case when a.service <= 3 then 1 
		 	
		when a.service = 12 then 1 
		else 
			ifnull(ifnull(b.sla_max,ifnull(c.sla_max,d.sla_max)),99  )
		end
			,' ',DATE_FORMAT(a.pickup_date,"%Y-%m-%d"),' ',a.id_pengiriman,' ',a.transit,' ',ifnull(a.manifest_received,0),' ','$u[level]' ) as created_date,

		x_namakustomer(a.id_kustomer) as nama_kustomer,
		case when lokasi_asal=0 then a.kota_asal else x_namakotabesar(a.lokasi_asal) end as kota_asal,
		case when lokasi_tujuan=0 then a.kota_tujuan else x_namakotabesar(a.lokasi_tujuan) end as kota_tujuan,
		x_service(a.service) as service,
		case when a.service='6' then x_namanegara(a.lokasi_tujuan) else x_namakotanew(x_hubnew(a.id_kota_tujuan)) end as hub,
		
		CONCAT(a.transit,' ',a.id_pengiriman) as transit_concat,
		a.id_pengiriman,
		CONCAT(id_pengiriman, ' ', 
		case when a.service <= 3 then 1 
		 	
		when a.service = 12 then 1 
		else 
			ifnull(ifnull(b.sla_max,ifnull(c.sla_max,d.sla_max)),99  )
		end
			, ' ' ,DATE_FORMAT(pickup_date,"%Y-%m-%d"), ' ',a.transit,' ','$u[level]',' ',ifnull(a.manifest_received,0)
			, ' ', if(c.manifest_from > 0, c.manifest_to, a.pickup_by_agent)
			) as assignment
	from pengiriman a
	left join sla_cross b on b.kota_asal =  a.id_kota_asal   AND b.kota_tujuan=  a.id_kota_tujuan  AND b.service = a.service	

	left join sla c on c.kota_asal =  if(x_inter(a.id_kota_asal)='Y',9999,0)   AND c.kota_tujuan= a.id_kota_tujuan  AND c.service = a.service

	left join sla_in d on d.kota_asal =  a.id_kota_asal   AND d.kota_tujuan= if(x_inter(a.id_kota_tujuan)='Y',9999,0)  AND d.service = a.service

	where a.status='2' and a.pickup='Y' and a.delivery='N' and a.id_kustomer=$u[id_kustomer] and (if(a.id_kota_asal>0,x_inter(a.id_kota_asal),x_intercity(a.lokasi_asal))!='Y' ) and (id_manifest='0' or id_manifest is null) and a.status!='7' and a.service !=6 and (a.transit!='Y'  or (a.transit='Y' AND a.received_transit = 'Y' and (if(a.id_kota_asal>0,x_inter(a.id_kota_asal),x_intercity(a.lokasi_asal))!='Y' ))  ) group by a.id_pengiriman
	) temp
EOT;
		}
		else{
			$table = 
<<<EOT
	(
	select 
		a.airwaybill_kode,		

		CONCAT(DATE_FORMAT(a.pickup_date,"%Y-%m-%d")
			,' ',ifnull(DATE_FORMAT(d.inbound_received,"%Y-%m-%d"),DATE_FORMAT(a.pickup_date,"%Y-%m-%d") ),' ',a.id_pengiriman,' ',a.transit,' ',ifnull(d.target_sla,0), ' ',  ifnull(a.manifest_outbound_date_target,0),' ','$u[level]') as created_date,

		x_namakustomer(a.id_kustomer) as nama_kustomer,
		case when id_kota_asal>0 then a.kota_asal else x_namakotabesar(a.lokasi_asal) end as kota_asal,
		case when id_kota_asal>0 then a.kota_tujuan else x_namakotabesar(a.lokasi_tujuan) end as kota_tujuan,
		x_service(a.service) as service,
		case when a.service='6' then x_namanegara(a.lokasi_tujuan) else x_namakotanew(x_hubnew(a.id_kota_tujuan)) end as hub,
		CONCAT(a.transit,' ',a.id_pengiriman) as transit_concat,
		a.id_pengiriman,
		CONCAT(a.id_pengiriman
			, ' ' ,DATE_FORMAT(pickup_date,"%Y-%m-%d")
			, ' ' ,ifnull(DATE_FORMAT(d.inbound_received,"%Y-%m-%d"),DATE_FORMAT(a.pickup_date,"%Y-%m-%d") ) 
			
			,' ',a.transit,' ','$u[level]',' ',ifnull(d.target_sla,0)
			, ' ',  ifnull(a.manifest_outbound_date_target,0)
			, ' ',  if(c.manifest_from > 0 and c.no_smu = '',c.id_manifest,0 )
			, ' ', if(c.manifest_from > 0, c.manifest_to, a.pickup_by_agent)

		) as assignment
	from pengiriman a  

	left join manifest_detail d on d.id_manifest_detail = (select z.id_manifest_detail from manifest_detail z where z.id_pengiriman = a.id_pengiriman order by z.id_manifest_detail desc limit 1)

	left join manifest c on c.id_manifest = d.id_manifest 

	where a.status='2' and a.pickup='Y' and a.delivery='N' and pickup_by_agent = 39 and
	   (
	   
	   (x_inter(a.id_kota_asal)='Y' and x_inter(a.id_kota_tujuan) !='Y' )

	   or (x_inter(a.id_kota_asal) !='Y' and x_inter(a.id_kota_tujuan)='Y') 

	   or (x_inter(a.id_kota_asal) !='Y' and x_inter(a.id_kota_tujuan) !='Y')

	   ) 
	   and  a.status!='7' and a.service !=6
	   and (a.transit!='Y'  and a.delivery='N'   or (a.transit='Y' AND a.received_transit = 'Y' and c.manifest_to =39)   ) 
	  
	  or ( (a.status = 3 and manifest_from='39' or (a.status=2 and a.transit='Y' AND a.received_transit = 'N')  ) and (c.no_smu= '' or no_smu is null) )

	   group by a.id_pengiriman
	) temp
EOT;
		}

		


}
		}

		elseif($u['level']=='admin_network'){

		$table = 
<<<EOT
	(
	select 
		a.airwaybill_kode,
		CONCAT(DATE_FORMAT(a.pickup_date,"%Y-%m-%d")
			,' ',ifnull(DATE_FORMAT(e.inbound_received,"%Y-%m-%d"),DATE_FORMAT(a.pickup_date,"%Y-%m-%d") ),' ',a.id_pengiriman,' ',a.transit,' ',ifnull(e.target_sla,0), ' ',  ifnull(a.manifest_outbound_date_target,0),' ','$u[level]' ) as created_date,

		x_namakustomer(a.id_kustomer) as nama_kustomer,
		case when lokasi_asal=0 then a.kota_asal else x_namakotabesar(a.lokasi_asal) end as kota_asal,
		case when lokasi_tujuan=0 then a.kota_tujuan else x_namakotabesar(a.lokasi_tujuan) end as kota_tujuan,
		x_service(a.service) as service,
		case when a.service='6' then x_namanegara(a.lokasi_tujuan) else x_namakotanew(x_hubnew(a.id_kota_tujuan)) end as hub,
		
		CONCAT(a.transit,' ',a.id_pengiriman) as transit_concat,
		a.id_pengiriman,
		
		CONCAT(a.id_pengiriman
			, ' ' ,DATE_FORMAT(pickup_date,"%Y-%m-%d")
			, ' ' ,ifnull(DATE_FORMAT(e.inbound_received,"%Y-%m-%d"),DATE_FORMAT(a.pickup_date,"%Y-%m-%d") ) 
			
			,' ',a.transit,' ','$u[level]',' ',ifnull(e.target_sla,0), ' ',  ifnull(a.manifest_outbound_date_target,0)
			, ' ',  if(c.manifest_from > 0 and c.no_smu = '',c.id_manifest,0 )
			, ' ', if(c.manifest_from > 0, c.manifest_to, a.pickup_by_agent)

		) as assignment

	from pengiriman a 

	left join manifest_detail e on e.id_manifest_detail = (select z.id_manifest_detail from manifest_detail z where z.id_pengiriman = a.id_pengiriman order by z.id_manifest_detail desc limit 1)

	

	left join manifest c on c.id_manifest = e.id_manifest 
					  
	
	where a.status='2' and a.pickup='Y' and a.delivery='N'  and  a.airwaybill_kode not like '002-%'

	
	    and  a.status!='7' and a.service !=6 

	    and (a.transit!='Y'  
	    or (a.transit='Y' AND a.received_transit = 'Y' and a.status not in(6,7)  ) and  a.airwaybill_kode not like '002-%'   )

	    or ( (a.status = 3 or (a.status=2 and a.transit='Y' AND a.received_transit = 'N')  ) and (c.no_smu= '' or no_smu is null)  and  a.airwaybill_kode not like '002-%')

	    group by a.id_pengiriman
	) temp
EOT;
		}

		elseif($u['level']=='network'){

		$table = 
<<<EOT
	(
	select 
		a.airwaybill_kode,
		CONCAT(DATE_FORMAT(a.pickup_date,"%Y-%m-%d")
			,' ',ifnull(DATE_FORMAT(e.inbound_received,"%Y-%m-%d"),DATE_FORMAT(a.pickup_date,"%Y-%m-%d") ),' ',a.id_pengiriman,' ',a.transit,' ',ifnull(e.target_sla,0), ' ',  ifnull(a.manifest_outbound_date_target,0),' ','$u[level]' ) as created_date,

		x_namakustomer(a.id_kustomer) as nama_kustomer,
		case when lokasi_asal=0 then a.kota_asal else x_namakotabesar(a.lokasi_asal) end as kota_asal,
		case when lokasi_tujuan=0 then a.kota_tujuan else x_namakotabesar(a.lokasi_tujuan) end as kota_tujuan,
		x_service(a.service) as service,
		case when a.service='6' then x_namanegara(a.lokasi_tujuan) else x_namakotanew(x_hubnew(a.id_kota_tujuan)) end as hub,
		
		CONCAT(a.transit,' ',a.id_pengiriman) as transit_concat,
		a.id_pengiriman,
		
		CONCAT(a.id_pengiriman
			, ' ' ,DATE_FORMAT(pickup_date,"%Y-%m-%d")
			, ' ' ,ifnull(DATE_FORMAT(e.inbound_received,"%Y-%m-%d"),DATE_FORMAT(a.pickup_date,"%Y-%m-%d") ) 
			
			,' ',a.transit,' ','$u[level]',' ',ifnull(e.target_sla,0), ' ',  ifnull(a.manifest_outbound_date_target,0)
			, ' ',  if(c.manifest_from > 0 and c.no_smu = '',c.id_manifest,0 )
			, ' ', if(c.manifest_from > 0, c.manifest_to, a.pickup_by_agent)

		) as assignment

	from pengiriman a 

	left join manifest_detail e on e.id_manifest_detail = (select z.id_manifest_detail from manifest_detail z where z.id_pengiriman = a.id_pengiriman order by z.id_manifest_detail desc limit 1)

	

	left join manifest c on c.id_manifest = e.id_manifest 
					  
	
	where a.status='2' and a.pickup='Y' and a.delivery='N'  and a.id_provinsi_tujuan in ($u[area_prov])

	and x_inter(a.id_kota_asal) = 'Y' 

	and x_inter(a.id_kota_tujuan) !='Y' 

	    and a.status!='7' and a.service !=6 

	    and (a.transit!='Y'  
	    or (a.transit='Y' AND a.received_transit = 'Y' and a.status not in(6,7)  and x_inter(a.id_kota_asal)='Y' and x_inter(a.id_kota_tujuan) !='Y') and a.id_provinsi_tujuan in ($u[area_prov])   )

	    or ( (a.status = 3 or (a.status=2 and a.transit='Y' AND a.received_transit = 'N')  ) and (c.no_smu= '' or no_smu is null) and x_inter(a.id_kota_asal) = 'Y'  and a.id_provinsi_tujuan in ($u[area_prov]))

	    group by a.id_pengiriman
	) temp
EOT;
		}
		elseif($u['level']=='inbound'){
		$table = 
<<<EOT
	(
	select 
		a.airwaybill_kode,
		CONCAT(DATE_FORMAT(a.pickup_date,"%Y-%m-%d")
			,' ',ifnull(DATE_FORMAT(e.inbound_received,"%Y-%m-%d"),DATE_FORMAT(a.pickup_date,"%Y-%m-%d") ),' ',a.id_pengiriman,' ',a.transit,' ',ifnull(e.target_sla,0), ' ',  ifnull(a.manifest_outbound_date_target,0),' ','$u[level]' ) as created_date,

		x_namakustomer(a.id_kustomer) as nama_kustomer,
		case when id_kota_asal>0 then a.kota_asal else x_namakotabesar(a.lokasi_asal) end as kota_asal,
		case when id_kota_asal>0 then a.kota_tujuan else x_namakotabesar(a.lokasi_tujuan) end as kota_tujuan,
		x_service(a.service) as service,
		case when a.service='6' then x_namanegara(a.lokasi_tujuan) else x_namakotanew(x_hubnew(a.id_kota_tujuan)) end as hub,
		
		CONCAT(a.transit,' ',a.id_pengiriman) as transit_concat,
		a.id_pengiriman,
		
		CONCAT(a.id_pengiriman
			, ' ' ,DATE_FORMAT(pickup_date,"%Y-%m-%d")
			, ' ' ,ifnull(DATE_FORMAT(e.inbound_received,"%Y-%m-%d"),DATE_FORMAT(a.pickup_date,"%Y-%m-%d") ) 
			
			,' ',a.transit,' ','$u[level]',' ',ifnull(e.target_sla,0), ' ',  ifnull(a.manifest_outbound_date_target,0)
			, ' ',  if(c.manifest_from > 0 and c.no_smu = '',c.id_manifest,0 )
			, ' ', if(c.manifest_from > 0, c.manifest_to, a.pickup_by_agent)
		) as assignment
	from pengiriman a 

	left join manifest_detail e on e.id_manifest_detail = (select z.id_manifest_detail from manifest_detail z where z.id_pengiriman = a.id_pengiriman order by z.id_manifest_detail desc limit 1)

	left join manifest c on c.id_manifest = e.id_manifest 
					  
	
	where a.status='2' and a.pickup='Y' and a.delivery='N' 
	and a.id_provinsi_asal in ($u[area_prov])

	and if(a.id_kota_asal>0,x_inter(a.id_kota_asal),x_intercity(a.lokasi_asal))!='Y' 

	     and a.status!='7' and a.service !=6 and (a.transit!='Y'  

	    or (a.transit='Y' AND a.received_transit = 'Y' and if(a.id_kota_asal>0,x_inter(a.id_kota_asal),x_intercity(a.lokasi_asal))!='Y' and a.id_provinsi_asal in ($u[area_prov])  )  )

	    or ( (a.status = 3  and a.delivery='N'  or (a.status=2 and a.transit='Y' AND a.received_transit = 'N')  ) and (c.no_smu= '' or no_smu is null) and x_inter(a.id_kota_asal) != 'Y'  and a.id_provinsi_asal in ($u[area_prov]) )
	    group by a.id_pengiriman
	) temp
EOT;
		}
		elseif($u['level']=='agent'){
		$agent = mysqli_query($con, 'select id_kota,id_kota_baru from cabang where id_cabang="'.$u['id_cabang'].'"');
		$a = mysqli_fetch_assoc($agent);
		$table = 
<<<EOT
	(
	select 
		a.airwaybill_kode,
		CONCAT(DATE_FORMAT(a.pickup_date,"%Y-%m-%d")
			,' ',ifnull(DATE_FORMAT(e.inbound_received,"%Y-%m-%d"),DATE_FORMAT(a.pickup_date,"%Y-%m-%d") ),' ',a.id_pengiriman,' ',a.transit,' ',ifnull(e.target_sla,0), ' ',  ifnull(a.manifest_outbound_date_target,0),' ','$u[level]' ) as created_date,

		x_namakustomer(a.id_kustomer) as nama_kustomer,
		case when lokasi_asal=0 then a.kota_asal else x_namakotabesar(a.lokasi_asal) end as kota_asal,
		case when lokasi_tujuan=0 then a.kota_tujuan else x_namakotabesar(a.lokasi_tujuan) end as kota_tujuan,
		x_service(a.service) as service,
		case when a.service='6' then x_namanegara(a.lokasi_tujuan) else x_namakotanew(x_hubnew(a.id_kota_tujuan)) end as hub,
	
		CONCAT(a.transit,' ',a.id_pengiriman) as transit_concat,
		a.id_pengiriman,
		
		CONCAT(a.id_pengiriman
			, ' ' ,DATE_FORMAT(pickup_date,"%Y-%m-%d")
			, ' ' ,ifnull(DATE_FORMAT(e.inbound_received,"%Y-%m-%d"),DATE_FORMAT(a.pickup_date,"%Y-%m-%d") ) 
			
			,' ',a.transit,' ','$u[level]',' ',ifnull(e.target_sla,0), ' ',  ifnull(a.manifest_outbound_date_target,0)
			, ' ',  if(d.manifest_from > 0 and d.no_smu = '',d.id_manifest,0 )
			, ' ', if(d.manifest_from > 0, d.manifest_to, a.pickup_by_agent)

		) as assignment

	from pengiriman a
	
	left join manifest_detail e on e.id_manifest_detail = (select x.id_manifest_detail from manifest_detail x where x.id_pengiriman = a.id_pengiriman order by x.id_manifest_detail desc limit 1)
	
	

	 left join regencies f on f.id=a.id_kota_asal

	left join provinces g on g.id=f.province_id	

		left join manifest_detail c on c.id_pengiriman = a.id_pengiriman
		
		left join manifest d on d.id_manifest = (select id_manifest from manifest_detail z where z.id_pengiriman = a.id_pengiriman order by z.id_manifest_detail desc limit 1)
		

	where a.status='2' and a.pickup='Y' and a.delivery='N' 
	and 
	(a.id_kota_asal=$a[id_kota_baru] or ifnull(f.hub,g.ibu_kota) =x_hubnew(a.id_kota_asal))

	and a.status!='7' and a.status !=6 and a.service !=6 and a.pickup_by_agent=$u[id_cabang] and a.transit != 'Y'  or (a.transit='Y' AND a.received_transit = 'Y' and d.manifest_to='$u[id_cabang]' and a.status!='7' and a.status !=6) 

	or ( (a.status = 3 

			and (a.id_kota_asal=$a[id_kota_baru] or ifnull(f.hub,g.ibu_kota) =x_hubnew(a.id_kota_asal))

			and a.pickup_by_agent=$u[id_cabang]

			or (a.status=2 and a.transit='Y' AND a.received_transit = 'N' and d.manifest_to='$u[id_cabang]' )  ) and (d.no_smu= '' or no_smu is null) 
		)
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
			array('db'	=> 'created_date',
					'dt'		=> 1,
					'formatter' => function( $d, $row ) {
						$pieces = explode(' ', $d);

						$pickup_date = $pieces[0];
						$inbound_transit_date= $pieces[1];
						$id_pengiriman= $pieces[2];
						$transit =  $pieces[3];
						$req_date_change = $pieces[5];
						$level = $pieces[6];
						//$sla_hari = $pieces[1];
						/*if($transit == 'Y'){
							$jumlah_hari = $pieces[1]+1;	
						}else{*/
							$jumlah_hari = 1;
						//}
						
						
						if ($transit = 'Y'){
							$tanggal = $inbound_transit_date;
							$date_request = $pickup_date;
						}else{
							$tanggal =  $pickup_date;
							$date_request = $inbound_transit_date;
						}

						/*for($i=$jumlah_hari;$i>=1;$i--){
							$tanggal = date('Y-m-d',strtotime($tanggal.'+ 1 day'));
							$hari = date('l' ,strtotime($tanggal));
							if($hari== 'Saturday' || $hari== 'Sunday'){
								$i=$i+1;
								continue;
							}
						}*/
						$tanggal = date('Y-m-d',strtotime($tanggal.'+ 1 day'));
						
						if($req_date_change != 0){
							$sla = $req_date_change;
						}else{
							$sla = $tanggal;	
						}

										

						$date_now =  date("Y-m-d");

						if($jumlah_hari != 99){
							
							if ($date_now > $sla){

								$created_date = $date_request.' - ' .$sla.' <button class="btn btn-danger btn-xs" title="TERLAMBAT!"><i class="fa fa-exclamation-circle"></i> </button>';
							}else{
								$created_date = $date_request.' - '. $sla;
							}
						}else{
							$created_date = $date_request.  ' <button data-toggle="modal" data-target="#modal_sla_insert" data-id="'.$id_pengiriman.'" class="btn btn-xs btn-warning custom_sla_insert"><i class="fa fa-plus"></i> </button>';
						}

						
						
						if($level == 'admin'){
							$pluscheck = '<input type="checkbox" form="plustanggal" name="selectawb[]" value="'.$id_pengiriman.'">';
						}else{
							$pluscheck = '';
						}
						$btn = ' <button class="btn btn-xs btn-primary detailawb" title="detail" onclick="detailwaktu(this.id)" id="'.$id_pengiriman.'"><i class="fa fa-clock-o"></i> </button>';
						//return  $created_date.$pluscheck;
						return  $created_date. $pluscheck.$btn;
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
			array('db'			=> 'hub',
					'dt'		=> 6,
					'formatter'	=> function($d,$row){
						return '<td>'.strtoupper($d).'</td>';
					}
			),
			array('db'			=> 'transit_concat',
					'dt'		=> 7,
					'formatter'	=> function($d,$row){
						$pieces = explode(' ', $d);
						$transit = $pieces[0];
						$key = "asdf$1011";
						$id_pengiriman = $pieces[1];
						
						if($transit=='Y'){
							$t = '<i class="fa fa-home"></i> DIKOTA TRANSIT <button class="btn btn-xs btn-primary" onclick="window.location.href = \'module/mod_manifest-outbound/manifest-outbound_action.php?module=manifest-outbound&act=godirect&id_pengiriman='.base64_encrypt($id_pengiriman,$key).'\';" ><i class="fa fa-angle-right"></i></button>' ;
						}else{
							$t = '<i class="fa fa-plane"></i> Kota tujuan';
						}

						return $t;
					}	
			),
			
			array(
				  'db'        => 'assignment',
				  'dt'        => 8,
				  'formatter' => function( $d, $row ) {
					
					$date_now = date("Y-m-d");
					$pieces = explode(' ', $d);

					$id_pengiriman = $pieces[0];
					/*$d1 = strtotime($pieces[1]);
						$d2 = date('l',$d1);
						$dt3 = strtolower($d2);*/


					$pickup_date = $pieces[1];
					$inbound_date = $pieces[2];
					$transit = $pieces[3];
					$level = $pieces[4];
					$req_date_change = $pieces[6];
					$id_manifest = $pieces[7];

					
				

						/*if($transit == 'Y'){
							$jumlah_hari = $pieces[1]+1;	
						}else{
							$jumlah_hari = $pieces[1];
						}*/
						$jumlah_hari = 1;
					
						if ($transit = 'Y'){
							$tanggal = $pickup_date;
							$date_request = $pickup_date;
						}else{
							$tanggal =  $inbound_transit_date;
							$date_request = $inbound_transit_date;
						}


							for($i=$jumlah_hari;$i>=1;$i--){
								$tanggal = date('Y-m-d',strtotime($tanggal.'+ 1 day'));
								$hari = date('l' ,strtotime($tanggal));
								if($hari== 'Sunday'){
									$i=$i+1;
									continue;
								}
							}
							/*$req_date_change = $pieces[5];*/
		
							if($req_date_change != 0){
								$sla = $req_date_change;
							}else{
								$sla = $tanggal;	
							}
							//$sla = $tanggal;	

						$agent_from = $pieces[8];

						$key = "asdf$1011";
						$level = $pieces[4];

						if($level == 'admin'){
							$button_back = '<button class="btn btn-xs btn-primary" onclick="window.location.href = \'module/mod_manifest-outbound/manifest-outbound_action.php?module=manifest-outbound&act=backtopickup&id_pengiriman='.$id_pengiriman.'\';" id=1 data-id="'.base64_encrypt($id_pengiriman,$key).'"><i class="fa fa-angle-left"></i></button>';
						}else{
							$button_back= '';
						}

						if( $level == '' || $level == 'network' || $level == 'inbound'){
							$assignment = '-';
						}
						else{
							if ($id_manifest != 0 ){
								$assignment = '<td><center><button class="btn btn-xs btn-warning open_outbound-smu" id=0 data-id="'.base64_encrypt($id_pengiriman,$key).' '. ($date_now > $sla ? 1:0).' '. $id_manifest.'">Input SMU</button> '.$button_back.'</center></td>';	
							}else{
								
								if($agent_from != '39'){
									if($sla != '1970-01-01'){
										if($date_now > $sla){
											$assignment = '<td><center><button class="btn btn-xs btn-primary open_outbound-assignment" id=1 data-id="'.base64_encrypt($id_pengiriman,$key).' 1">Assignment</button> '.$button_back.'</center></td>';						
										}else{
											$assignment = '<td><center><button class="btn btn-xs btn-primary open_outbound-assignment" id=0 data-id="'.base64_encrypt($id_pengiriman,$key).' 0">Assignment</button> '.$button_back.'</center></td>';						
										}
									} 
									else{
										$assignment = '<td><center><button class="btn btn-xs btn-primary open_outbound-assignment" id=0 data-id="'.base64_encrypt($id_pengiriman,$key).' 0">Assignment</button> '.$button_back.'</center></td>';						
									}	
								}else{
									$assignment = '-';
								}
							}
						}
					
					
					 return $assignment.$button_back;
				  }
			)

		);

		
		require( '../../config/ssp.class.php' );

		echo json_encode(
			SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns )
		);
	
	// update pickup
	}elseif ($module=='manifest-outbound' AND $act=='update'){

		$que = "SELECT ifnull(berat_aktual,berat_barang) as berat, collie, packing_kayu,service,
		id_kota_asal,id_kota_tujuan,
		CASE 
										WHEN airwaybill_kode LIKE '001-%' THEN 0
										WHEN airwaybill_kode LIKE '002-%' THEN 1

									end as intercity ,

									alasan_plus_outbound

		FROM pengiriman WHERE id_pengiriman= '".$_POST['id']."' ";
		$qu = mysqli_query($con,$que);
		$row  = mysqli_fetch_assoc($qu);

		/*if($row['alasan_plus_outbound'] != ''){
			$alasan_plus_outbound_update =  $row['alasan_plus_outbound'];
		}else{
			$alasan_plus_outbound_update = "NULL";
		}*/
		$alasan_plus_outbound_update = !empty($row['alasan_plus_outbound']) 
    		? "'".$row['alasan_plus_outbound']."'" 
    		: "NULL";

		if($_POST['berat_aktual'] != ''){
			$berat_aktual = mysqli_real_escape_string($con, $_POST['berat_aktual']);
		}else{
			$berat_aktual = $row['berat'];
		}
		//colie
		if($_POST['collie'] != ''){
			$collie = mysqli_real_escape_string($con, $_POST['collie']);
		}else{
			$collie = $row['collie'];
		}
		//packing kayu
		if($_POST['packing_kayu'] != ''){
			$packing_kayu = mysqli_real_escape_string($con, $_POST['packing_kayu']);
		}else{
			$packing_kayu = $row['packing_kayu'];
		}	



		
		//$pcs = mysqli_real_escape_string($con, $_POST['pcs']);
		$panjang = mysqli_real_escape_string($con, $_POST['panjang']);
		$lebar = mysqli_real_escape_string($con, $_POST['lebar']);
		$tinggi = mysqli_real_escape_string($con, $_POST['tinggi']);
		$ukuran_barang = $panjang.'x'.$lebar.'x'.$tinggi;

		if($row['intercity'] == 1){
              			$beratvol = $panjang*$lebar*$tinggi/4000;
              	}
             	else{
           			if($row['service'] == 5){
           				$beratvol = $panjang*$lebar*$tinggi/4000;
           			}
              		else{
              			$beratvol = $panjang*$lebar*$tinggi/6000;
              		}
              	}

         function bulatkan($angka)  
		 {  
		 $exp = explode('.',$angka);  
		 if(count($exp) == 2)  
		     {  
		     $p = "0.".$exp[1];  
		     if($p<0.05) {    
		     	$t=0;
		     }  
		     

		     else {$t=1;}  
		     $r=$exp[0] + $t;  
		     return $r;  
		     }  
		     else return $angka;  
		 }  
		   
		 //$angka = $_POST['nomer'];  
		 
		 //echo bulatkan($angka); 

         if($berat_aktual > $beratvol){
         	$berat_dihitung = $berat_aktual;
         }else{
         	$berat_dihitung = bulatkan($beratvol);
         }

         if($panjang > 0){
         	mysqli_query($con, "update detail_collie set berat='$berat_aktual',p='$panjang',  l='$lebar', t='$tinggi', berat_real = '$berat_dihitung
         		' where id_pengiriman='$_POST[id]' and koli_ke=1");
         }
		

		


		$manifest_from = mysqli_real_escape_string($con, $_POST['manifest_from']);
		$transit_to = mysqli_real_escape_string($con, $_POST['transit_to']);
		$manifest_to = mysqli_real_escape_string($con, $_POST['manifest_to']);

		if($transit_to > 0){
			$manifest_tujuan = $transit_to;
		}else{
			$manifest_tujuan = $manifest_to;
		}

		$service = $row['service'];

		//cek SLA
		if($service == '5'){
			if($manifest_from == 39){
				$sla_cek =  mysqli_query($con, "SELECT sla_max FROM sla WHERE  kota_asal='9999' AND kota_tujuan= x_idkotacabang('".$manifest_tujuan."') and service='5' ");

			}elseif($manifest_tujuan == 39){
				$sla_cek =  mysqli_query($con, "SELECT sla_max FROM sla_in WHERE  kota_tujuan='9999' AND kota_asal= x_idkotacabang('".$manifest_from."') and service='5' ");
			}else{
				$sla_cek =  mysqli_query($con, "SELECT sla_max FROM sla_cross WHERE  kota_asal=x_idkotacabang('".$manifest_from."') AND kota_tujuan= x_idkotacabang('".$manifest_tujuan."') and service='5' ");
			}


			$sla_row  = mysqli_fetch_assoc($sla_cek);


			$sla_maxx = $sla_row['sla_max'];
			if($sla_maxx > 3 and $sla_maxx < 10){
				$sla_akhir = $sla_maxx-1;
			}elseif($sla_maxx >= 10 ){
				$sla_akhir = $sla_maxx-3;
			}else{
				$sla_akhir = $sla_maxx;
			}
		}
		elseif($service == '3'){
			$sla_akhir = 2;
		}
		else{
			$sla_akhir = 1;
		}


		$manifest_date = mysqli_real_escape_string($con, $_POST['manifest_date']);		
		$manifest_via = mysqli_real_escape_string($con, $_POST['manifest_via']);
		$dg = mysqli_real_escape_string($con, $_POST['dg']);
		$manifest_by =  mysqli_real_escape_string($con, $_POST['manifest_by']);
		$packed_by =  mysqli_real_escape_string($con, $_POST['packed_by']);
		$resi = mysqli_real_escape_string($con, $_POST['resi']);
		
		$reason = mysqli_real_escape_string($con, $_POST['reason']);
		if ($reason != ''){
			$reason = $reason;
			$diskon_outbound = 15;
		}
		else{
			$reason = '';
			$diskon_outbound = 0;
		}



		// cari id transaksi terakhir yang berawalan tanggal hari ini
		//$today = date("Ymd");
		$today = $manifest_date;
		$tgl_sekarang = $manifest_date;
		$query = "SELECT max(kode_manifest) AS last FROM manifest WHERE kode_manifest LIKE '$today%'";
		$hasil = mysqli_query($con,$query);
		$data  = mysqli_fetch_assoc($hasil);
		$lastNoTransaksi = $data['last'];

		// baca nomor urut transaksi dari id transaksi terakhir
		$lastNoUrut = substr($lastNoTransaksi, 8, 3); 
		// nomor urut ditambah 1
		$nextNoUrut = $lastNoUrut + 1;
		// membuat format nomor transaksi berikutnya
		$nextNoTransaksi = $today.sprintf('%03s', $nextNoUrut);
		
		if($transit_to>0){
			// cari manifest transit
			$cektransit = mysqli_query($con,"select * from manifest where tanggal='".$tgl_sekarang."' and manifest_from='".$manifest_from."' and manifest_to='".$transit_to."' and manifest_via='".$manifest_via."'  ");
			$adatransit = mysqli_num_rows($cektransit);
			$t = mysqli_fetch_assoc($cektransit);
			if($adatransit>0){
				// insert to manifest detail
				mysqli_query($con, "insert into manifest_detail (id_manifest,id_pengiriman,diskon_outbound,alasan_terlambat_outbound,sla,alasan_plus_outbound) values ('$t[id_manifest]','$_POST[id]',$diskon_outbound,'$reason','$sla_akhir',$alasan_plus_outbound_update)");
				// update pengiriman
				mysqli_query($con, "update pengiriman set status='2',manifest_outbound_date_target= NULL,alasan_plus_outbound=NULL, berat_aktual='$berat_dihitung',ukuran_barang='$ukuran_barang', collie='$collie', pcs='$pcs', packing_kayu='$packing_kayu', transit='Y',received_transit='N' where id_pengiriman='$_POST[id]'");
			}else{
				mysqli_query($con, "INSERT INTO manifest (kode_manifest,tanggal,manifest_from,manifest_to,manifest_via,manifest_outbound_by,packed_by,transit,created_date,created_by,created_with,no_smu) VALUES ('$nextNoTransaksi','$tgl_sekarang','$manifest_from','$transit_to','$manifest_via','$manifest_by','$packed_by','Y','$tgl_jam','$u[id_user]','web','$resi')");
				$id_manifest = mysqli_insert_id($con);
				// insert to manifest detail
				mysqli_query($con, "insert into manifest_detail (id_manifest,id_pengiriman,sla,diskon_outbound,alasan_terlambat_outbound,alasan_plus_outbound) values ('$id_manifest','$_POST[id]','$sla_akhir','$diskon_outbound','$reason',$alasan_plus_outbound_update)");
				// update pengiriman
				mysqli_query($con, "update pengiriman set status='2',manifest_outbound_date_target= NULL,alasan_plus_outbound=NULL, berat_aktual='$berat_dihitung', ukuran_barang='$ukuran_barang', collie='$collie', pcs='$pcs', packing_kayu='$packing_kayu', transit='Y',received_transit='N' where id_pengiriman='$_POST[id]'");
			}	
		}else{
			// cari manifest direct
			$cek = mysqli_query($con, "SELECT * FROM manifest WHERE tanggal='".$tgl_sekarang."' AND manifest_from='".$manifest_from."' AND manifest_to='".$manifest_to."' and manifest_via='".$manifest_via."' ");
			$ketemu	= mysqli_num_rows($cek);
			$r = mysqli_fetch_assoc($cek);
			/*echo $ketemu;
			echo "INSERT INTO manifest (kode_manifest,tanggal,manifest_from,manifest_to,manifest_via,manifest_outbound_by,created_date,created_by) VALUES ('$nextNoTransaksi','$tgl_sekarang','$manifest_from','$manifest_to','$manifest_via','$manifest_by','$tgl_jam','$u[id_user]')";
			return false;*/

			if($ketemu>0){
				// insert to manifest detail
				mysqli_query($con, "insert into manifest_detail (id_manifest,id_pengiriman,sla,diskon_outbound,alasan_terlambat_outbound,alasan_plus_outbound) values ('$r[id_manifest]','$_POST[id]','$sla_akhir','$diskon_outbound','$reason',$alasan_plus_outbound_update)");
				// update pengiriman
				mysqli_query($con, "update pengiriman set status='3',manifest_outbound_date_target= NULL,alasan_plus_outbound=NULL, berat_aktual='$berat_dihitung',ukuran_barang='$ukuran_barang', collie='$collie', pcs='$pcs', packing_kayu='$packing_kayu', transit='N',delivery_by_agent='".$manifest_to."',alasan_terlambat='$reason' where id_pengiriman='$_POST[id]'");
			}else{
				// insert to manifest
				mysqli_query($con, "INSERT INTO manifest (kode_manifest,tanggal,manifest_from,manifest_to,manifest_via,manifest_outbound_by,packed_by,created_date,created_by, created_with,no_smu ) VALUES ('$nextNoTransaksi','$tgl_sekarang','$manifest_from','$manifest_to','$manifest_via','$manifest_by','$packed_by','$tgl_jam','$u[id_user]','web','$resi')");
				$id_manifest = mysqli_insert_id($con);
				// insert to manifest detail
				mysqli_query($con, "insert into manifest_detail (id_manifest,id_pengiriman,sla,diskon_outbound,alasan_terlambat_outbound,alasan_plus_outbound) values ('$id_manifest','$_POST[id]','$sla_akhir','$diskon_outbound','$reason',$alasan_plus_outbound_update)");
				// update pengiriman
				mysqli_query($con, "update pengiriman set status='3',manifest_outbound_date_target= NULL,alasan_plus_outbound=NULL, berat_aktual='$berat_dihitung',ukuran_barang='$ukuran_barang', collie='$collie', pcs='$pcs', packing_kayu='$packing_kayu', transit='N',delivery_by_agent='".$manifest_to."' where id_pengiriman='$_POST[id]'");
			}
		}
		
		header('location:../../main.php?module='.$module);
	
	}

	elseif($module=='manifest-outbound' AND $act=='smu'){
		$pieces = explode(' ', $_POST['id']);

		$id_pengiriman = $pieces[0];
		$sla = $pieces[1];
		$id_manifest = $pieces[2];

		$key = "asdf$1011";
		$encrypt = "$pieces[0]";
		$id = base64_decrypt($encrypt,$key);

		echo'
		<form action="'.$aksi.'?module=manifest-outbound&act=updatesmu" name="modal_popup" method="POST">
			<input type="hidden" name="id" class="form-control" value="'.$id.'" />
			<input type="hidden" name="id_manifest" class="form-control" value="'.$id_manifest.'" />

		<div class="row">
					<div class="col-md-12">
						<div class="form-group">
							<label>No SMU/RESI (Berlaku untuk semua airwaybill di manifest yang sama)</label>
							<input type="text" placeholder="NO SMU/RESI"  class="form-control style="width: 100%;" name="resi" value="" id="resi"   minlength="5" minlength="5" required>	
						</div>
					</div>
				</div>';

		


		if($sla==1){
				
					echo '<div class="form-group" style="padding-bottom: 5px;">
	                	<label>ALASAN TERLAMBAT:</label>
	                	<input type="text" class="form-control style="width: 100%;" name="reason" minlength="10" required>						
	            	</div>';	
				
				

	     }

	     echo '

			<div class="modal-footer">
				
							<button class="btn btn-success" type="submit">Confirm</button>
				
			</div>
		</form>';
	}

	elseif($module=='manifest-outbound' AND $act=='updatesmu'){

		$id_pengiriman = $_POST['id_pengiriman'];
		$id_manifest = $_POST['id_manifest'];
		$resi = $_POST['resi'];

		/*$reason = mysqli_real_escape_string($con, $_POST['reason']);
		if ($reason != ''){
			$reason = $reason;
			$diskon_outbound = 15;
			mysqli_query($con, "update manifest_detail set diskon_outbound='$diskon_outbound',alasan_terlambat_outbound='$reason', update_date_smu=now() where id_manifest = '$id_manifest' and id_pengiriman = '$id_pengiriman' ");
		}*/
		

		mysqli_query($con, "update manifest set no_smu='$resi', update_date_smu=now() where id_manifest = '$id_manifest' ");

		mysqli_query($con, "update pengiriman set manifest_outbound_date_target= NULL where id_pengiriman = $id_pengiriman ");
		//manifest_outbound_date_target


		header('location:../../main.php?module='.$module);
	}
	
	elseif($module=='manifest-outbound' AND $act=='assignment'){

		$pieces = explode(' ', $_POST['id']);

		$id_pengiriman = $pieces[0];
		$sla = $pieces[1];

		

		$key = "asdf$1011";
		$encrypt = "$pieces[0]";
		$id = base64_decrypt($encrypt,$key);
		
		$query = mysqli_query($con, "select  CASE 
										
										WHEN airwaybill_kode LIKE '002-%' THEN 1
										WHEN airwaybill_kode LIKE '005-%' THEN 1
										else 0

									end as intercity, if(coalesce(berat_aktual,berat_barang) > 0,coalesce(berat_aktual,berat_barang),1 ) as berat,collie,service, alasan_terlambat,collie, transit, if(a.id_kota_tujuan > 0 ,x_hubnew(a.id_kota_tujuan), x_hub(lokasi_tujuan)) as hub,
										packing_kayu

									 from pengiriman a  where id_pengiriman='".$id."'");
		$r = mysqli_fetch_assoc($query);
		$transit=$r['transit'];
		$hub=$r['hub'];
		$service=$r['service'];
		$intercity = $r['intercity'];


		$from = mysqli_query($con, "SELECT x_inter(id_kota_asal) as intercity_asal, lokasi_asal, id_kota_asal FROM pengiriman WHERE id_pengiriman='".$id."'");

		$r_from = mysqli_fetch_assoc($from);

		$asal = ($r_from['id_kota_asal'] > 0 ? 'id_kota_baru':'id_kota');
		$asal_hub = ($r_from['id_kota_asal'] > 0 ? 'x_hubnew':'x_hub');

		$intercity_asal = $r_from['intercity_asal'];

		
			
		$test = $pieces[1];
			echo '


			<input type="hidden" name="test" class="form-control" value="'.$test.'" />
			<input type="hidden" name="id" class="form-control" value="'.$id.'" />
			';
			$q = mysqli_query($con, "select * from detail_collie where id_pengiriman='".$id."'  ");
			$q_berat = mysqli_query($con, "select * from detail_collie where id_pengiriman='".$id."' and koli_ke=1 ");
			$b = mysqli_fetch_assoc($q_berat);
			$ketemu = mysqli_num_rows($q);
			if($ketemu > 1){
				echo '<h2><i class="fa fa-warning text-yellow"></i>Koli Lebih dari 1</h2> <h3>Edit data collie,KG dll, klik <a href="https://cesco-logistics.com/e-cesco/main.php?module=airwaybill&act=outbound&id='.$pieces[0].'"  target="_blank">disini</a> </h3>';
			}
			else{
			echo '
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label>Collie: *</label>
							<input type="text" class="form-control style="width: 100%;" name="collie" value="'.$r['collie'].'" required readonly="readonly">
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label>Berat Timbangan:</label>
							<div class="input-group">
								<input type="text" class="form-control style="width: 100%;" name="berat_aktual" value="'.$b['berat'].'" required> <span class="input-group-addon">Kg</span>
							</div>						
						</div>
					</div>
					
				</div>
				
				<div class="row">
					<div class="col-md-4">
						<div class="form-group">
							<label>PANJANG(CM): *</label>
							<input type="number" class="form-control style="width: 100%;" name="panjang" value="'.$b['p'].'" required>						
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label>LEBAR:(CM) *</label>
							<input type="number" class="form-control style="width: 100%;" name="lebar" value="'.$b['l'].'" required>						
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label>TINGGI:(CM) *</label>
							<input type="number" class="form-control style="width: 100%;" name="tinggi" value="'.$b['t'].'" required>						
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="form-group">
							<label>Packing Kayu: *</label>
							<select name="packing_kayu" class="form-control style="width: 100%;" required>
								<option value=""></option>
								<option value="1" '.($r['packing_kayu'] == 1 ? 'selected' : '').'>Packing Oleh Pemilik</option>
								<option value="2" '.($r['packing_kayu'] == 2 ? 'selected' : '').'>Packing Kayu Oleh Cesco Log.</option>
								<option value="3" '.($r['packing_kayu'] == 3 ? 'selected' : '').'>Non Packing Kayu Oleh Cesco Log.</option>
							</select>
						</div>
					</div>
				</div>';
			}
			echo '
			<div class="form-group" style="padding-bottom: 5px;">
                <label>Manifest From:</label>
				<select name="manifest_from" class="form-control select2" width="100%" id="manifest_from" required>
					<option value="">- SELECT -</option>
				';
				$agent = mysqli_query($con, 'select nama_cabang, alamat1 from cabang where id_cabang="'.$u['id_cabang'].'"');
						$a = mysqli_fetch_assoc($agent);
					if($u['level'] == 'agent'){
						

						echo '<option value="' . $u['id_cabang'] . '"><b>'.strtoupper($a['nama_cabang']).'</b> ('.strtoupper($a['alamat1']).')</option>';
					}
					else {
						if($transit=='Y'){
							$result = mysqli_query($con,"select c.id_cabang,c.nama_cabang,c.alamat1 from manifest 
								a inner join manifest_detail b on b.id_manifest=a.id_manifest inner join cabang c on c.id_cabang=a.manifest_to where b.id_pengiriman='".$id."' and (c.status != 'N' or c.status is null) order by b.id_manifest_detail desc limit 1");
						}else{
							$result = mysqli_query($con,"select 
								case when x_inter(a.id_kota_asal) = 'Y' then '39' else c.id_cabang end as id_cabang, 

								case when x_inter(a.id_kota_asal) = 'Y' then 'JAKARTA' else c.nama_cabang end as nama_cabang, 
								case when x_inter(a.id_kota_asal) = 'Y' then 'CESCO LOGISTICS' else c.alamat1 end as alamat1 

								from pengiriman a  
								
								left join cabang c on  (c.id_kota_baru = x_hubnew(id_kota_asal) or c.id_kota_baru = id_kota_asal)

								where a.id_pengiriman='".$id."' and (c.status != 'N' or c.status is null) order by c.nama_cabang");
						}
						while ($row = mysqli_fetch_assoc($result)) {
							echo '<option value="' . $row['id_cabang'] . '"><b>'.strtoupper($row['nama_cabang']).'</b> ('.strtoupper($row['alamat1']).')</option>';
						}
					}
					echo '
				</select>
			</div>
				<div class="form-group" style="padding-bottom: 5px;">
					<label>Transit to:</label>
					<select name="transit_to" class="form-control select2" width="100%" id="manifest_transit" required>						
						<option value="0" selected>- DIRECT (LANGSUNG, TANPA TRANSIT) -</option>';
						
						$result = mysqli_query($con,"select id_cabang, nama_cabang, alamat1 from cabang order by nama_cabang");
						while ($row = mysqli_fetch_assoc($result)) {
							echo '<option value="' . $row['id_cabang'] . '"><b>'.strtoupper($row['nama_cabang']).'</b> ('.strtoupper($row['alamat1']).')</option>';
						}
						echo '
					</select>
				</div>	
			<div class="form-group" style="padding-bottom: 5px;">
                <label>Manifest To:</label>
				<select name="manifest_to" class="form-control select2" width="100%" id="manifest_to" required>
				<option value="">- SELECT -</option>
				';

					if ($r['service'] == 6){
						$result = mysqli_query($con,"select  id_cabang, nama_cabang,alamat1 from cabang where kode_cabang='INT' order by nama_cabang");

						while ($row = mysqli_fetch_assoc($result)) {
							echo '<option value="' . $row['id_cabang'] . '"><b>'.strtoupper($row['nama_cabang']).'</b> ('.strtoupper($row['alamat1']).')</option>';
						}
					}
					elseif($r['service'] == 5){
						$result = mysqli_query($con,"select case when if(a.id_kota_tujuan>0,x_inter(a.id_kota_tujuan),x_intercity(a.lokasi_tujuan))='Y' then '39' else c.id_cabang end as id_cabang, case when if(a.id_kota_tujuan>0,x_inter(a.id_kota_tujuan),x_intercity(a.lokasi_tujuan))='Y' then 'JAKARTA' else c.nama_cabang end as nama_cabang, case when if(a.id_kota_tujuan>0,x_inter(a.id_kota_tujuan),x_intercity(a.lokasi_tujuan))='Y' then 'CESCO LOGISTICS' else c.alamat1 end as alamat1 

							from pengiriman a 
							left join cabang c on  (c.id_kota_baru = x_hubnew(id_kota_tujuan) or c.id_kota_baru = id_kota_tujuan )

							where a.id_pengiriman='".$id."' and (c.status != 'N' or c.status is null)  order by c.nama_cabang");

						while ($row = mysqli_fetch_assoc($result)) {
							echo '<option value="' . $row['id_cabang'] . '"><b>'.strtoupper($row['nama_cabang']).'</b> ('.strtoupper($row['alamat1']).')</option>';
						}
						if( $service == 5 || $service == 4){
							 $result = mysqli_query($con,"select  id_cabang, nama_cabang,alamat1 from cabang where (trucking_all_city='Y'  )order by nama_cabang");

								while ($row = mysqli_fetch_assoc($result)) {
									echo '<option value="' . $row['id_cabang'] . '"><b>'.strtoupper($row['nama_cabang']).'</b> ('.strtoupper($row['alamat1']).')</option>';
								}
						}
					}
					else{
						$result = mysqli_query($con,"select case when if(a.id_kota_tujuan>0,x_inter(a.id_kota_tujuan),x_intercity(a.lokasi_tujuan))='Y' then '39' else c.id_cabang end as id_cabang, case when if(a.id_kota_tujuan>0,x_inter(a.id_kota_tujuan),x_intercity(a.lokasi_tujuan))='Y' then 'JAKARTA' else c.nama_cabang end as nama_cabang, case when if(a.id_kota_tujuan>0,x_inter(a.id_kota_tujuan),x_intercity(a.lokasi_tujuan))='Y' then 'CESCO LOGISTICS' else c.alamat1 end as alamat1 from pengiriman a left join cabang c on if(a.id_kota_tujuan>0 ,c.id_kota_baru,c.id_kota) =if(a.id_kota_tujuan>0, x_hubnew(a.id_kota_tujuan), x_hub(a.lokasi_tujuan)) or if(a.id_kota_tujuan>0 ,c.id_kota_baru,c.id_kota)=if( a.id_kota_tujuan>0, a.id_kota_tujuan,a.lokasi_tujuan) where a.id_pengiriman='".$id."' and (c.status != 'N' or c.status is null) order by c.nama_cabang");

						while ($row = mysqli_fetch_assoc($result)) {
							echo '<option value="' . $row['id_cabang'] . '"><b>'.strtoupper($row['nama_cabang']).'</b> ('.strtoupper($row['alamat1']).')</option>';
						}
						if($service == 2 || $service == 3 || $service == 5 || $service == 4){
							$result = $result = mysqli_query($con,"select  id_cabang, nama_cabang,alamat1 from cabang where (trucking_all_city='Y' or all_city='Y') order by nama_cabang");

								while ($row = mysqli_fetch_assoc($result)) {
									echo '<option value="' . $row['id_cabang'] . '"><b>'.strtoupper($row['nama_cabang']).'</b> ('.strtoupper($row['alamat1']).')</option>';
								}
						}
					}

					if($transit=='Y'){
							$result = mysqli_query($con,"select c.id_cabang,c.nama_cabang,c.alamat1 from manifest 
								a inner join manifest_detail b on b.id_manifest=a.id_manifest inner join cabang c on c.id_cabang=a.manifest_to where b.id_pengiriman='".$id."' and (c.status != 'N' or c.status is null) order by b.id_manifest_detail desc limit 1");
						}else{
							$result = mysqli_query($con,"select 
								case when x_inter(a.id_kota_asal) = 'Y' then '39' else c.id_cabang end as id_cabang, 

								case when x_inter(a.id_kota_asal) = 'Y' then 'JAKARTA' else c.nama_cabang end as nama_cabang, 
								case when x_inter(a.id_kota_asal) = 'Y' then 'CESCO LOGISTICS' else c.alamat1 end as alamat1 

								from pengiriman a  
								
								left join cabang c on  (c.id_kota_baru = x_hubnew(id_kota_asal) or c.id_kota_baru = id_kota_asal)

								where a.id_pengiriman='".$id."' and (c.status != 'N' or c.status is null) order by c.nama_cabang");
						}
						while ($row = mysqli_fetch_assoc($result)) {
							echo '<option value="' . $row['id_cabang'] . '"><b>'.strtoupper($row['nama_cabang']).'</b> ('.strtoupper($row['alamat1']).')</option>';
						}


					echo '
				</select>
			</div>
			<div class="form-group" style="padding-bottom: 5px;">
                <label>Manifest Date:</label>
				<select name="manifest_date" class="form-control select2" width="100%" id="manifest_date" required >
					<option value="">- SELECT -</option>					
					<option value="'.date('Ymd').'">'.date('Y-m-d').' (Hari Ini)</option>
					<option value="'.date('Ymd',strtotime(' +1 day')).'">'.date('Y-m-d',strtotime(' +1 day')).' (Besok)</option>
					<option value="'.date('Ymd',strtotime(' -1 day')).'">'.date('Y-m-d',strtotime(' -1 day')).' (Kemarin)</option>
					';
					
					for ($x = 2; $x <= 15; $x++) {
					  echo '<option value="'.date('Ymd',strtotime(' -'.$x.' day')).'">'.date('Y-m-d',strtotime(' -'.$x.' day')).'</option>';
					}

					echo '															

				</select>
			</div>

			

			<div class="form-group" style="padding-bottom: 5px;">
                <label>Manifest Via:</label>
				<select name="manifest_via" class="form-control select2" width="100%" id="manifest_via" required> 
					<option value="">- SELECT -</option>
				';

				$result = $result = mysqli_query($con,"select  name, id from manifest_vendor where active = 'Y' order by name asc ");

								while ($row = mysqli_fetch_assoc($result)) {
									echo '<option value="' . $row['name'] . '"><b>'.strtoupper($row['name']).'</b> </option>';
								}

				/*echo
					<option value="">- SELECT -</option>
					<option value="RIONA SUB">RIONA SUB</option>
					<option value="RIONA UPG">RIONA UPG</option>
					<option value="DEN LOGSTIK">DEN LOGSTIK</option>
					<option value="DUA PERISAI">DUA PERISAI</option>
					<option value="PANCA MANDIRI">PANCA MANDIRI</option>
					<option value="POS LAUT">POS LAUT</option>
					<option value="JAYA BAYA">JAYA BAYA</option>
					<option value="DANEX">DANEX</option>
					<option value="PANDU SIWI">PANDU SIWI</option>
					<option value="HANOMAN LOGISTIK">HANOMAN LOGISTIK</option>
					<option value="CAC WEKO">CAC / WEKO</option>
					<option value="GA SPEED">GA SPEED</option>
					<option value="GA CN">GA CN</option>
					<option value="DMK">DMK</option>
					<option value="HERONA">HERONA</option>
					<option value="KLOG">KLOG</option>
					<option value="KI8">KI8</option>
					<option value="TOTO">TOTO</option>
					<option value="RAIL EXPRESS">RAIL EXPRESS</option>					
					<option value="XTRANS">XTRANS</option>
					<option value="BES">BES</option>
					<option value="MJE">MJE</option>
					<option value="JNE">JNE</option>
					<option value="TIKI">TIKI</option>
					<option value="ARHDHL">ARH/DHL</option>
					<option value="IJX">IJX/WEKO</option>
					<option value="ANUGRAH">ANUGRAH</option>
					<option value="INDAH CARGO">INDAH CARGO</option>
					<option value="PLUIT KENCANA">PLUIT KENCANA</option>
					<option value="PUTRA BANGKA">PUTRA BANGKA EXPRESS</option>
					<option value="APM">APM</option>
					<option value="BALI PRIMA">BALI PRIMA</option>
					<option value="SMI">SMI</option>
					<option value="R2M express">R2M express</option>
					<option value="ATLAS TRUCKING">ATLAS TRUCKING</option>	
					<option value="MEDIA LOG">MEDIA LOG</option>	*/				
					echo '
				</select>
			</div>
			<div class="form-group" style="padding-bottom: 5px;">
                <label>Manifest Date:</label>
				<select name="dg" class="form-control select2" width="100%" id="dg" required >
					<option value="N">NON DG</option>
					<option value="Y">DG</option>																		
				</select>
			</div>
			
					
			<div class="form-group" style="padding-bottom: 5px;">
				<label>No SMU/RESI</label>
				<input type="text" placeholder="KOSONGKAN JIKA BELUM ADA" class="form-control style="width: 100%;" name="resi" value="" id="resi"  readonly  minlength="5">	
			</div>
			 
			<div class="form-group" style="padding-bottom: 5px;">
                <label>Manifest Outbound By: (Lapangan)</label>
				<select name="manifest_by" class="form-control select2" width="100%" required>
					<option value="">- SELECT -</option>
					';
					if($u['level'] == 'agent'){
						$result = mysqli_query($con,"select id_user, namalengkap from user  where id_cabang=".$u['id_cabang']." and blokir = 'N' "  );

						while ($row = mysqli_fetch_assoc($result)){
									echo '<option value="'.$row['id_user'].'">'.strtoupper($row['namalengkap']).'</option>';
								}
					}
					else{
						if($transit=='Y'){
							$result = mysqli_query($con,"select a.id_user, concat(b.nama_cabang,' ','(',b.alamat1,')',' / ',a.namalengkap) as namalengkap from user a inner join cabang b on a.id_cabang=b.id_cabang where a.id_cabang in (16,69,39) and blokir = 'N' "  );

							while ($row = mysqli_fetch_assoc($result)){
									echo '<option value="'.$row['id_user'].'">'.strtoupper($row['namalengkap']).'</option>';
								}
						}
						else{
							if ($intercity_asal == 'Y'){
								$result = mysqli_query($con,"select id_user, namalengkap from user where (level='kurir' or level='driver') AND blokir != 'Y' order by namalengkap");
								while ($row = mysqli_fetch_assoc($result)){
									echo '<option value="'.$row['id_user'].'">'.strtoupper($row['namalengkap']).'</option>';
								}
							}
							else{
								$result = mysqli_query($con,"select a.id_user, concat(b.nama_cabang,' ','(',b.alamat1,')',' / ',a.namalengkap) as namalengkap 
									from user a 
									inner join cabang b on b.id_cabang=a.id_cabang 
									where (a.level='agent' or a.level='kurir_agent' or a.level='kurir' or a.level='inbound')  
									AND a.blokir != 'Y' 
									and b.".$asal." = ".$asal_hub."('".($r_from['id_kota_asal']>0 ? $r_from['id_kota_asal'] : $r_from['lokasi_asal'])."')
									 and b.status='Y' order by a.id_user desc ");
								while ($row = mysqli_fetch_assoc($result)) {
									echo '<option value="' . $row['id_user'] . '">'.strtoupper($row['namalengkap']).'</option>';
								}
							}
						}
					}
					echo '
					
					
				</select>
			</div>
			';
			if($intercity_asal == 'Y' && $transit != 'Y'){
				echo '<div class="form-group" style="padding-bottom: 5px;">
	                <label>Manifest Packed By </label>
					<select name="packed_by" class="form-control select2" width="100%" required>
						<option value="">- SELECT -</option>
						<option value="Rizky">Rizky</option>
								<option value="Agus">Agus</option>
								<option value="Aris">Aris</option>
								<option value="Sopiyan">Sopiyan</option>
								<option value="Bagus">Bagus</option>
								<option value="Kurnia">Kurnia</option>															
								<option value="Mustofa">Mustofa</option>															
								<option value="Edy">Edy</option>															
					</select>
				</div>';
			}
			
			if($sla==1){
				
					echo '<div class="form-group" style="padding-bottom: 5px;">
	                	<label>ALASAN TERLAMBAT:</label>
	                	<input type="text" class="form-control style="width: 100%;" name="reason" minlength="10" required>						
	            	</div>';	
				
				

	         }
	        echo '

			<div class="modal-footer">
				';
				if ($hub != 0){
					if($intercity == 0){
						echo '
							';
					}
					else{
						echo 'ada kesalahan no awalan airwaybill kode';
					}
				
				}
				else{
					echo 'manifest to kosong harap hubungi admin';
				}
				echo '
				
			</div>
		';
	}
	elseif ($module=='manifest-outbound' AND $act=='plus'){
		$tanggal		= jin_date_sql($_POST['tanggalplus']);
		$awb		= $_POST['selectawb'];
		$id_awb		= implode(',',$awb);
		$alasan 	= $_POST['alasan_plus'];
		//echo "update pengiriman set manifest_received='$tanggal' where id_pengiriman in ($id_awb)";

		mysqli_query($con, "update pengiriman set manifest_outbound_date_target='$tanggal', alasan_plus_outbound='$alasan' where id_pengiriman in ($id_awb)");
		header('location:../../main.php?module='.$module);
	}
	elseif($module=='manifest-outbound' and $act=='backtopickup'){
		$id_pengiriman =  $_GET['id_pengiriman'];
		
		
		

		mysqli_query($con, "update pengiriman set status='1',pickup='N', transit='N', pickup_by_agent=null,pickup_date=null, pickup_by=null where id_pengiriman='$id_pengiriman'");

		
		header('location:../../main.php?module='.$module);
	}
	elseif($module=='manifest-outbound' and $act=='godirect'){
		$key = 'asdf$1011';
		$encrypt = $_GET['id_pengiriman'];
		$id_pengiriman = base64_decrypt($encrypt,$key);

		$result = mysqli_query($con,"select m.manifest_to as manifest_to from manifest_detail z inner join manifest m on z.id_manifest=m.id_manifest where z.id_pengiriman = $id_pengiriman order by z.id_manifest_detail desc limit 1");

		
		$r = mysqli_fetch_assoc($result);

		$manifest_to = $r['manifest_to'];

		//update
		mysqli_query($con, "update pengiriman set status='3',manifest='N', transit='N', delivery_by_agent=$manifest_to ,received_transit=null where id_pengiriman='$id_pengiriman'");

		
		header('location:../../main.php?module='.$module);
	}
	elseif($module=='manifest-outbound' and $act=='cekresi'){
		$manifest_from = $_POST['manifest_from'];
		$manifest_to = $_POST['manifest_to'];
		$manifest_transit = $_POST['manifest_transit'];
		if($manifest_transit != 0){
			$to = $manifest_transit;
		}else{
			$to = $manifest_to;
		}
		$manifest_via = $_POST['manifest_via'];
		$manifest_date = $_POST['manifest_date'];

		
		$result = mysqli_query($con, "SELECT if(no_smu is null or no_smu ='',0,no_smu) as no_smu FROM manifest WHERE manifest_from = '$manifest_from' and manifest_to = '$to' and manifest_via = '$manifest_via' and tanggal = '$manifest_date'   ");
		$row = mysqli_fetch_assoc($result);
		echo json_encode($row);

	}

	elseif ($module=='manifest-outbound' AND $act=='updatetest'){

		$que = "SELECT ifnull(berat_aktual,berat_barang) as berat, collie, packing_kayu,service,
		id_kota_asal,id_kota_tujuan,
		CASE 
										WHEN airwaybill_kode LIKE '001-%' THEN 0
										WHEN airwaybill_kode LIKE '002-%' THEN 1

									end as intercity  

		FROM pengiriman WHERE id_pengiriman= '".$_POST[id]."' ";
		$qu = mysqli_query($con,$que);
		$row  = mysqli_fetch_assoc($qu);

		if($_POST['berat_aktual'] != ''){
			$berat_aktual = mysqli_real_escape_string($con, $_POST['berat_aktual']);
		}else{
			$berat_aktual = $row['berat'];
		}
		//colie
		if($_POST['collie'] != ''){
			$collie = mysqli_real_escape_string($con, $_POST['collie']);
		}else{
			$collie = $row['collie'];
		}
		//packing kayu
		if($_POST['packing_kayu'] != ''){
			$packing_kayu = mysqli_real_escape_string($con, $_POST['packing_kayu']);
		}else{
			$packing_kayu = $row['packing_kayu'];
		}	

		
		$pcs = mysqli_real_escape_string($con, $_POST['pcs']);
		$panjang = mysqli_real_escape_string($con, $_POST['panjang']);
		$lebar = mysqli_real_escape_string($con, $_POST['lebar']);
		$tinggi = mysqli_real_escape_string($con, $_POST['tinggi']);
		$ukuran_barang = $panjang.'x'.$lebar.'x'.$tinggi;

		if($row['intercity'] == 1){
              			$beratvol = $panjang*$lebar*$tinggi/4000;
              	}
             	else{
           			if($row['service'] == 5){
           				$beratvol = $panjang*$lebar*$tinggi/4000;
           			}
              		else{
              			$beratvol = $panjang*$lebar*$tinggi/6000;
              		}
              	}

         function bulatkan($angka)  
		 {  
		 $exp = explode('.',$angka);  
		 if(count($exp) == 2)  
		     {  
		     $p = "0.".$exp[1];  
		     if($p<0.05) {    
		     	$t=0;
		     }  
		     

		     else {$t=1;}  
		     $r=$exp[0] + $t;  
		     return $r;  
		     }  
		     else return $angka;  
		 }  
		   
		 //$angka = $_POST['nomer'];  
		 
		 //echo bulatkan($angka); 

         if($berat_aktual > $beratvol){
         	$berat_dihitung = $berat_aktual;
         }else{
         	$berat_dihitung = bulatkan($beratvol);
         }

         if($panjang > 0){
         	mysqli_query($con, "update detail_collie set berat='$berat_aktual',p='$panjang',  l='$lebar', t='$tinggi', berat_real = '$berat_dihitung
         		' where id_pengiriman='$_POST[id]' and koli_ke=1");
         }
		

		


		$manifest_from = mysqli_real_escape_string($con, $_POST['manifest_from']);
		$transit_to = mysqli_real_escape_string($con, $_POST['transit_to']);
		$manifest_to = mysqli_real_escape_string($con, $_POST['manifest_to']);

		if($transit_to > 0){
			$manifest_tujuan = $transit_to;
		}else{
			$manifest_tujuan = $manifest_to;
		}

		$service = $row['service'];

		//cek SLA
		if($service == '5'){
			if($manifest_from == 39){
				$sla_cek =  mysqli_query($con, "SELECT sla_max FROM sla WHERE  kota_asal='9999' AND kota_tujuan= x_idkotacabang('".$manifest_tujuan."') and service='5' ");

			}elseif($manifest_tujuan == 39){
				$sla_cek =  mysqli_query($con, "SELECT sla_max FROM sla_in WHERE  kota_tujuan='9999' AND kota_asal= x_idkotacabang('".$manifest_from."') and service='5' ");
			}else{
				$sla_cek =  mysqli_query($con, "SELECT sla_max FROM sla_cross WHERE  kota_asal=x_idkotacabang('".$manifest_from."') AND kota_tujuan= x_idkotacabang('".$manifest_tujuan."') and service='5' ");
			}


			$sla_row  = mysqli_fetch_assoc($sla_cek);


			$sla_maxx = $sla_row['sla_max'];
			if($sla_maxx > 3 and $sla_maxx < 10){
				$sla_akhir = $sla_maxx-1;
			}elseif($sla_maxx >= 10 ){
				$sla_akhir = $sla_maxx-3;
			}else{
				$sla_akhir = $sla_maxx;
			}
		}
		else{
			$sla_akhir = 1;
		}
		echo 'manifest from='. $manifest_from .',manifest tujuan'.$manifest_tujuan . ', sla='.  $sla_akhir.'<br>';
		echo "SELECT sla_max FROM sla WHERE  kota_asal='9999' AND kota_tujuan= x_idkotacabang('".$manifest_tujuan."') and service='5' ";
		return false;
	}


	elseif ($module=='manifest-outbound' AND $act=='cek-manifest'){

		$tgl = date('Y-m-d',strtotime($_POST['tgl']));
		$from = $_POST['from'];
		$to = $_POST['to'];
		$via = $_POST['via'];
		$manifest_by = $_POST['manifest_by'];
		$packed_by = $_POST['packed_by'];
		$dg = $_POST['dg'];


			$cek = mysqli_query($con, "SELECT * FROM manifest WHERE tanggal='".$tgl."' AND manifest_from='".$from."' AND manifest_to='".$to."' and manifest_via='".$via."'  ");
			$ketemu	= mysqli_num_rows($cek);
			$r = mysqli_fetch_assoc($cek);
			

			if($ketemu>0){
				

				$query = mysqli_query($con, "SELECT a.id_manifest,a.id_pengiriman,a.sla, b.airwaybill_kode,b.kota_asal,b.kota_tujuan,
											kota_asal,kota_tujuan,x_namakustomer(b.id_kustomer) as nama_kustomer,x_service(b.service) as nama_service
																				FROM manifest_detail a
																				inner join pengiriman b on a.id_pengiriman = b.id_pengiriman
																				WHERE a.id_manifest='".$r['id_manifest']."'");
				$jumlah_data	= mysqli_num_rows($query);

				while ($row = mysqli_fetch_assoc($query)) {
					$rows[] = $row;
				}

				echo json_encode(array("success"=>1,"message"=> "LANJUTKAN DENGAN SCAN AIRWAYBILL","jumlah_data"=>$jumlah_data,"kode_manifest"=> $r['kode_manifest'],"id_manifest"=> $r['id_manifest'], "no_smu" => $r['no_smu'] ));
				
			}else{


				//kode
				$today = date('Ymd', strtotime($_POST['tgl']));
				$tgl_sekarang = $tgl;
				$query = "SELECT max(kode_manifest) AS last FROM manifest WHERE kode_manifest LIKE '$today%'";
				$hasil = mysqli_query($con, $query);
				$data  = mysqli_fetch_assoc($hasil);
				$lastNoTransaksi = $data['last'];

				// baca nomor urut transaksi dari id transaksi terakhir
				$lastNoUrut = ($lastNoTransaksi === null) ? 0 : (int) substr($lastNoTransaksi, 8, 3);
				// nomor urut ditambah 1
				$nextNoUrut = $lastNoUrut + 1;
				// membuat format nomor transaksi berikutnya
				$nextNoTransaksi = $today . sprintf('%03s', $nextNoUrut);


				//ahir kode

				

				// insert to manifest
				mysqli_query($con, "INSERT INTO manifest (kode_manifest,tanggal,manifest_from,manifest_to,manifest_via,manifest_outbound_by,packed_by,created_date,created_by, created_with,no_smu,status_dg ) VALUES ('$nextNoTransaksi','$tgl','$from','$to','$via','$manifest_by','$packed_by','$tgl_jam','$u[id_user]','web','','$dg')");
				
				$id_manifest = mysqli_insert_id($con);

				echo json_encode(array("success"=>1,"message"=> "LANJUTKAN DENGAN SCAN AIRWAYBILL","jumlah_data"=>0,"kode_manifest"=> $nextNoTransaksi,"id_manifest"=> $id_manifest,"no_smu" => '' ));

				
			}


		

		
		//echo 'oke';
	}
	
	elseif ($module=='manifest-outbound' AND $act=='scanawb'){
		$manifest_from = 39;
		$manifest_tujuan = $_POST['to'];

		$id_manifest = $_POST['id_manifest'];
		$awb = $_POST['kode_awb'];
		$from = $_POST['from'];
		$to = $_POST['to'];
		$via = $_POST['via'];
		$direct = $_POST['direct'];
		$result = mysqli_query($con, "SELECT id_pengiriman,kota_asal,id_kota_tujuan,kota_tujuan,status,service,received_transit,transit,x_namakustomer(id_kustomer) as nama_kustomer,x_service(service) as nama_service ,sentral_cargo_reguler,sentral_cargo_trucking
		from pengiriman a 
		inner join regencies r on a.id_kota_tujuan = r.id
		
		where airwaybill_kode='$awb'");

		$r = mysqli_fetch_assoc($result);

		$service = $r['service'];

		//cek SLA
		if($service == '5'){

			if ($via == "Sentral Cargo" && $r['sentral_cargo_trucking'] != 1){
				$succes = array("success"=>0,"message"=> "AIRWAYBILL $awb Dengan kota tujuan".$row['kota_tujuan']."tidak bisa dikirim via sentral kargo, harap pakai via yang lain");
				  exit(0);
			}

			if($manifest_from == 39){
				$sla_cek =  mysqli_query($con, "SELECT sla_max FROM sla WHERE  kota_asal='9999' AND kota_tujuan= x_idkotacabang('".$manifest_tujuan."') and service='5' ");

			}elseif($manifest_tujuan == 39){
				$sla_cek =  mysqli_query($con, "SELECT sla_max FROM sla_in WHERE  kota_tujuan='9999' AND kota_asal= x_idkotacabang('".$manifest_from."') and service='5' ");
			}else{
				$sla_cek =  mysqli_query($con, "SELECT sla_max FROM sla_cross WHERE  kota_asal=x_idkotacabang('".$manifest_from."') AND kota_tujuan= x_idkotacabang('".$manifest_tujuan."') and service='5' ");
			}


			$sla_row  = mysqli_fetch_assoc($sla_cek);


			$sla_maxx = $sla_row['sla_max'];
			if($sla_maxx > 3 and $sla_maxx < 10){
				$sla_akhir = $sla_maxx-1;
			}elseif($sla_maxx >= 10 ){
				$sla_akhir = $sla_maxx-3;
			}else{
				$sla_akhir = $sla_maxx;
			}
		}
		elseif($service == '3'){
			if ($via == "Sentral Cargo" && $r['sentral_cargo_reguler'] != 1){
				$succes = array("success"=>0,"message"=> "AIRWAYBILL $awb Dengan kota tujuan".$r['kota_tujuan']."tidak bisa dikirim via sentral kargo, harap pakai via yang lain");
				echo json_encode($succes);  
				exit(0);
			}
			$sla_akhir = 2;
		}
		else{
			$sla_akhir = 1;
		}


		$result = mysqli_query($con, "SELECT id_pengiriman,kota_asal,kota_tujuan, id_kota_tujuan, x_hubnew(id_kota_tujuan) as hub_kota_tujuan,status, service, received_transit, transit,x_namakustomer(id_kustomer) as nama_kustomer,x_service(service) as nama_service, updated_by_operational,komoditi from pengiriman where airwaybill_kode='$awb'");
		
		$ketemu	= mysqli_num_rows($result);
		if($ketemu > 0){
			

			$reason = "";

			$row = mysqli_fetch_assoc($result);
			if($row['updated_by_operational'] > 0 ||  $row['komoditi'] == 2){


				if( ($row['status'] == 2 && $row['transit'] == 'N') || ($row['status'] == 2 && $row['received_transit'] == 'Y')){


					$cek_kota_tujuan =  mysqli_query($con, "SELECT id_kota_baru,trucking_all_city from cabang where id_cabang=$to");

					$r_cabang = mysqli_fetch_assoc($cek_kota_tujuan);

						if($direct == 'direct'){

							if($r_cabang['trucking_all_city'] == 'Y'){
								 mysqli_query($con, "update pengiriman set status='3',manifest_outbound_date_target= NULL, transit='N',delivery_by_agent='".$to."',alasan_terlambat='$reason' where airwaybill_kode='$awb'");

								 mysqli_query($con, "insert into manifest_detail (id_manifest,id_pengiriman,sla,created_by) values ('$id_manifest','$row[id_pengiriman]','$sla_akhir','$u[id_user]')");

						
					
								echo json_encode(array("success"=>1,"message"=> "AIRWAYBILL $awb BERHASIL DI MANIFEST", "pengiriman" => $row));
							}else{
								if($row['id_kota_tujuan'] == $r_cabang['id_kota_baru'] || $row['hub_kota_tujuan'] == $r_cabang['id_kota_baru']){
									echo json_encode(array("success"=>1,"message"=> "AIRWAYBILL $awb BERHASIL DI MANIFEST", "pengiriman" => $row));

									mysqli_query($con, "update pengiriman set status='3',manifest_outbound_date_target= NULL ,transit='N',delivery_by_agent='".$to."',alasan_terlambat='$reason' where airwaybill_kode='$awb'");

								 mysqli_query($con, "insert into manifest_detail (id_manifest,id_pengiriman,sla,created_by) values ('$id_manifest','$row[id_pengiriman]','$sla_akhir','$u[id_user]')");

								}else{
									$succes = array("success"=>0,"message"=> "$awb Gagal Karena kota tujuan tidak sesuai dengan agen tujuan");
									echo json_encode($succes);
								}
							}

							
						

						}else{
							mysqli_query($con, "update pengiriman set status='2',manifest_outbound_date_target= NULL, transit='Y',received_transit='Y',alasan_terlambat='$reason' where airwaybill_kode='$awb'");

							mysqli_query($con, "insert into manifest_detail (id_manifest,id_pengiriman,sla,created_by) values ('$id_manifest','$row[id_pengiriman]','$sla_akhir','$u[id_user]')");

						
					
							echo json_encode(array("success"=>1,"message"=> "AIRWAYBILL $awb BERHASIL DI MANIFEST", "pengiriman" => $row));
						}
						


						


					/*echo json_encode(array("success"=>1,

						"message"=> 
						"insert into manifest_detail (id_manifest,id_pengiriman,sla,created_by) values ('$id_manifest','$row[id_pengiriman]','2','$u[id_user]')", 

						"pengiriman" => $row));*/
				}else{
					$succes = array("success"=>0,"message"=> "AIRWAYBILL $awb Belum dipickup atau sudah dalam kota tujuan".$row['status']);
					echo json_encode($succes);
				}
			}else{
				$succes = array("success"=>0,"message"=> "AIRWAYBILL INI BELUM ADA UPDATE KG / DIMENSI / PERUBAHAN, SILAHKAN UPDATE DAHULU");
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