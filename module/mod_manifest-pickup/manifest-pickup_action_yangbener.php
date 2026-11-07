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
	include "../../config/db_connect.php";
	include "../../../mobileapps/customer/courier/test_vdi_new.php";
	
	$module	= !empty($_GET['module']) ? $_GET['module'] : '';
	$act	= !empty($_GET['act']) ? $_GET['act'] : '';
	
	$aksi = "module/mod_manifest-pickup/manifest-pickup_action.php";

	$key = 'asdf$1011';
	$encrypt = $_SESSION['id_user'];
	$id = base64_decrypt($encrypt,$key);
	$useraktif = mysqli_query($con, 'select * from user where id_user='.$id.'');
	$u = mysqli_fetch_assoc($useraktif);
	
	// load data
	if($module=='manifest-pickup' and $act=='data'){
		if($u['level']=='admin' || $u['tambahan_admin'] == '1' OR $u['level']=='finance'){
		$table = 
<<<EOT
	(
	select 
		airwaybill_kode,
		CONCAT(DATE_FORMAT(a.created_date,"%Y-%m-%d"),' ', 
		
			ifnull( DATE_FORMAT( DATE_ADD(a.created_date, INTERVAL case when a.service=1 then 0 when a.service=2 then 1 else b.sla_max end DAY), "%Y-%m-%d" ) ,'0' ), ' ', a.id_kota_asal, ' ',a.service
			) as created_date,
		x_namakustomer(id_kustomer) as nama_kustomer,
		case when a.id_kota_asal>0 then a.kota_asal else x_namakotabesar(a.lokasi_asal) end as kota_asal,
		case when a.service != 6 then x_namakotanew(a.id_kota_tujuan) else x_namanegara(a.lokasi_tujuan) end as kota_tujuan,
		x_service(a.service) as service,
		id_pengiriman,
		CONCAT(id_pengiriman, ' ' ,DATE_FORMAT(a.created_date,"%Y-%m-%d"), ' ', a.id_kota_asal, ' ',a.service,' ', '$u[level]',' ',a.id_kota_tujuan,' ',a.service,' ',a.id_kustomer ) as assignment,
		ifnull( DATE_FORMAT( DATE_ADD(a.created_date, INTERVAL case when a.service=1 then 0 when a.service=2 then 1 else b.sla_max end DAY), "%Y-%m-%d" ) ,'0' ) as sla_max
	from pengiriman a 

	left join sla b on b.kota_asal = case when x_inter(a.id_kota_asal)='Y' then 9999 else a.id_kota_asal end  AND b.kota_tujuan= case when x_inter(a.id_kota_tujuan)='Y' then 9999 else a.id_kota_tujuan end AND b.service = a.service

	where id_kustomer != 0 and status=1 and pickup='N' and (pickup_request is null or pickup_request='') and status!=7
	group by id_pengiriman
	) temp
EOT;
		}elseif( $u['level']=='intercity' || strpos($u['menu_tambahan'], 'manifest') !== false){
		$table = 
<<<EOT
	(
	select 
		airwaybill_kode,
		CONCAT(DATE_FORMAT(a.created_date,"%Y-%m-%d"),' ', 
		
			ifnull( DATE_FORMAT( DATE_ADD(a.created_date, INTERVAL case when a.service=1 then 0 when a.service=2 then 1 else b.sla_max end DAY), "%Y-%m-%d" ) ,'0' ), ' ', a.id_kota_asal, ' ',a.service
			) as created_date,
		x_namakustomer(id_kustomer) as nama_kustomer,
		case when a.id_kota_asal>0 then a.kota_asal else x_namakotabesar(a.lokasi_asal) end as kota_asal,
		case when a.service != 6 then x_namakotanew(a.id_kota_tujuan) else x_namanegara(a.lokasi_tujuan) end as kota_tujuan,
		x_service(a.service) as service,
		id_pengiriman,
		CONCAT(id_pengiriman, ' ' ,DATE_FORMAT(a.created_date,"%Y-%m-%d"), ' ', a.id_kota_asal, ' ',a.service,' ', '$u[level]',' ',a.id_kota_tujuan,' ',a.service,' ',a.id_kustomer ) as assignment,
		ifnull( DATE_FORMAT( DATE_ADD(a.created_date, INTERVAL case when a.service=1 then 0 when a.service=2 then 1 else b.sla_max end DAY), "%Y-%m-%d" ) ,'0' ) as sla_max
	from pengiriman a 
	left join sla b on b.kota_asal = case when x_inter(a.id_kota_asal)='Y' then 9999 else a.id_kota_asal end  AND b.kota_tujuan= case when x_inter(a.id_kota_tujuan)='Y' then 9999 else a.id_kota_tujuan end AND b.service = a.service

	where id_kustomer != 0 and status=1 and pickup='N' and (pickup_request is null or pickup_request='') and if(id_kota_asal>0,x_inter(id_kota_asal)='Y',x_intercity(lokasi_asal)='Y')  and status!=7 or(a.service = 6 and delivery='N' and status!='7' and pickup_request is null  and pickup='N' and if(id_kota_asal>0,x_inter(id_kota_asal)='Y',x_intercity(lokasi_asal)='Y'))
	group by id_pengiriman
	) temp
EOT;
		}elseif($u['level']=='operator' || $u['level']=='domex'){
			if ($u['id_kustomer'] > 0 ){
				$table = 
<<<EOT
	(
	select 
		airwaybill_kode,
		CONCAT(DATE_FORMAT(a.created_date,"%Y-%m-%d"),' ', 
		
			ifnull( DATE_FORMAT( DATE_ADD(a.created_date, INTERVAL case when a.service=1 then 0 when a.service=2 then 1 else b.sla_max end DAY), "%Y-%m-%d" ) ,'0' ), ' ', a.id_kota_asal, ' ',a.service
			) as created_date,
		x_namakustomer(id_kustomer) as nama_kustomer,
		case when a.id_kota_asal>0 then a.kota_asal else x_namakotabesar(a.lokasi_asal) end as kota_asal,
		case when a.service != 6 then x_namakotanew(a.id_kota_tujuan) else x_namanegara(a.lokasi_tujuan) end as kota_tujuan,
		x_service(a.service) as service,
		id_pengiriman,
		CONCAT(id_pengiriman, ' ' ,DATE_FORMAT(a.created_date,"%Y-%m-%d"), ' ', a.id_kota_asal, ' ',a.service,' ', '$u[level]',' ',a.id_kota_tujuan,' ',a.service,' ',a.id_kustomer ) as assignment,
		ifnull( DATE_FORMAT( DATE_ADD(a.created_date, INTERVAL case when a.service=1 then 0 when a.service=2 then 1 else b.sla_max end DAY), "%Y-%m-%d" ) ,'0' ) as sla_max
	from pengiriman a 
	left join sla b on b.kota_asal = case when x_inter(a.id_kota_asal)='Y' then 9999 else a.id_kota_asal end  AND b.kota_tujuan= case when x_inter(a.id_kota_tujuan)='Y' then 9999 else a.id_kota_tujuan end AND b.service = a.service

	where id_kustomer != 0 and status=1 and pickup='N' and (pickup_request is null or pickup_request='') and if(id_kota_asal>0,x_inter(id_kota_asal)='Y',x_intercity(lokasi_asal)='Y') and status!=7 and id_kustomer=$u[id_kustomer]
	group by id_pengiriman
	) temp
EOT;
			}	
			else{
				$table = 
<<<EOT
	(
	select 
		airwaybill_kode,
		CONCAT(DATE_FORMAT(a.created_date,"%Y-%m-%d"),' ', 
		
			ifnull( DATE_FORMAT( DATE_ADD(a.created_date, INTERVAL case when a.service=1 then 0 when a.service=2 then 1 else b.sla_max end DAY), "%Y-%m-%d" ) ,'0' ), ' ', a.id_kota_asal, ' ',a.service
			) as created_date,
		x_namakustomer(id_kustomer) as nama_kustomer,
		case when a.id_kota_asal>0 then a.kota_asal else x_namakotabesar(a.lokasi_asal) end as kota_asal,
		case when a.service != 6 then x_namakotanew(a.id_kota_tujuan) else x_namanegara(a.lokasi_tujuan) end as kota_tujuan,
		x_service(a.service) as service,
		id_pengiriman,
		CONCAT(id_pengiriman, ' ' ,DATE_FORMAT(a.created_date,"%Y-%m-%d"), ' ', a.id_kota_asal, ' ',a.service,' ', '$u[level]',' ',a.id_kota_tujuan,' ',a.service,' ',a.id_kustomer ) as assignment,
		ifnull( DATE_FORMAT( DATE_ADD(a.created_date, INTERVAL case when a.service=1 then 0 when a.service=2 then 1 else b.sla_max end DAY), "%Y-%m-%d" ) ,'0' ) as sla_max
	from pengiriman a 
	left join sla b on b.kota_asal = case when x_inter(a.id_kota_asal)='Y' then 9999 else a.id_kota_asal end  AND b.kota_tujuan= case when x_inter(a.id_kota_tujuan)='Y' then 9999 else a.id_kota_tujuan end AND b.service = a.service

	where id_kustomer != 0 and status=1 and pickup='N' and (pickup_request is null or pickup_request='') and if(id_kota_asal>0,x_inter(id_kota_asal)='Y',x_intercity(lokasi_asal)='Y') and status!=7
	group by id_pengiriman
	) temp
EOT;
			}
			

		}
		
		elseif($u['level']=='admin_network'){
		$table = 
<<<EOT
	(
	select 
		airwaybill_kode,
		CONCAT(DATE_FORMAT(a.created_date,"%Y-%m-%d"),' ', 
		
			ifnull( DATE_FORMAT( DATE_ADD(a.created_date, INTERVAL case when a.service=1 then 0 when a.service=2 then 1 else b.sla_max end DAY), "%Y-%m-%d" ) ,'0' ), ' ', a.id_kota_asal, ' ',a.service
			) as created_date,
		x_namakustomer(id_kustomer) as nama_kustomer,
		case when a.id_kota_asal>0 then a.kota_asal else x_namakotabesar(a.lokasi_asal) end as kota_asal,
		case when a.service != 6 then x_namakotanew(a.id_kota_tujuan) else x_namanegara(a.lokasi_tujuan) end as kota_tujuan,
		x_service(a.service) as service,
		id_pengiriman,
		CONCAT(id_pengiriman, ' ' ,DATE_FORMAT(a.created_date,"%Y-%m-%d"), ' ', a.id_kota_asal, ' ',a.service,' ', '$u[level]',' ',a.id_kota_tujuan,' ',a.service,' ',a.id_kustomer ) as assignment,
		ifnull( DATE_FORMAT( DATE_ADD(a.created_date, INTERVAL case when a.service=1 then 0 when a.service=2 then 1 else b.sla_max end DAY), "%Y-%m-%d" ) ,'0' ) as sla_max
	from pengiriman a 
	left join sla b on b.kota_asal = case when x_inter(a.id_kota_asal)='Y' then 9999 else a.id_kota_asal end  AND b.kota_tujuan= case when x_inter(a.id_kota_tujuan)='Y' then 9999 else a.id_kota_tujuan end AND b.service = a.service

				
	 where id_kustomer != 0 and status=1 and pickup='N' and (pickup_request is null or pickup_request='') 



	 and  a.airwaybill_kode not like '002-%' 
	 and  a.airwaybill_kode not like '005-%'
	 and status!=7
	 group by id_pengiriman
	) temp
EOT;
		}


		elseif($u['level']=='network'){
		$table = 
<<<EOT
	(
	select 
		airwaybill_kode,
		CONCAT(DATE_FORMAT(a.created_date,"%Y-%m-%d"),' ', 
		
			ifnull( DATE_FORMAT( DATE_ADD(a.created_date, INTERVAL case when a.service=1 then 0 when a.service=2 then 1 else b.sla_max end DAY), "%Y-%m-%d" ) ,'0' ), ' ', a.id_kota_asal, ' ',a.service
			) as created_date,
		x_namakustomer(id_kustomer) as nama_kustomer,
		case when a.id_kota_asal>0 then a.kota_asal else x_namakotabesar(a.lokasi_asal) end as kota_asal,
		case when a.service != 6 then x_namakotanew(a.id_kota_tujuan) else x_namanegara(a.lokasi_tujuan) end as kota_tujuan,
		x_service(a.service) as service,
		id_pengiriman,
		CONCAT(id_pengiriman, ' ' ,DATE_FORMAT(a.created_date,"%Y-%m-%d"), ' ', a.id_kota_asal, ' ',a.service,' ', '$u[level]',' ',a.id_kota_tujuan,' ',a.service,' ',a.id_kustomer ) as assignment,
		ifnull( DATE_FORMAT( DATE_ADD(a.created_date, INTERVAL case when a.service=1 then 0 when a.service=2 then 1 else b.sla_max end DAY), "%Y-%m-%d" ) ,'0' ) as sla_max
	from pengiriman a 
	left join sla b on b.kota_asal = case when x_inter(a.id_kota_asal)='Y' then 9999 else a.id_kota_asal end  AND b.kota_tujuan= case when x_inter(a.id_kota_tujuan)='Y' then 9999 else a.id_kota_tujuan end AND b.service = a.service

				left join kota c on c.id_kota=a.lokasi_asal 
				left join regencies d on d.id=a.id_kota_asal
				
	 where id_kustomer != 0 and status=1 and pickup='N' and (pickup_request is null or pickup_request='') 

	 and a.service in ($u[handle_service]) 

	 and d.province_id in ($u[area_prov])

	 and status!=7
	 group by id_pengiriman
	) temp
EOT;
		}
		elseif($u['level']=='inbound'){
		$table = 
<<<EOT
	(
	select 
		airwaybill_kode,
		CONCAT(DATE_FORMAT(a.created_date,"%Y-%m-%d"),' ', 
		
			ifnull( DATE_FORMAT( DATE_ADD(a.created_date, INTERVAL case when a.service=1 then 0 when a.service=2 then 1 else b.sla_max end DAY), "%Y-%m-%d" ) ,'0' ), ' ', a.id_kota_asal, ' ',a.service
			) as created_date,
		x_namakustomer(id_kustomer) as nama_kustomer,
		case when a.id_kota_asal>0 then a.kota_asal else x_namakotabesar(a.lokasi_asal) end as kota_asal,
		case when a.service != 6 then x_namakotanew(a.id_kota_tujuan) else x_namanegara(a.lokasi_tujuan) end as kota_tujuan,
		x_service(a.service) as service,
		id_pengiriman,
		CONCAT(id_pengiriman, ' ' ,DATE_FORMAT(a.created_date,"%Y-%m-%d"), ' ', a.id_kota_asal, ' ',a.service,' ', '$u[level]',' ',a.id_kota_tujuan,' ',a.service,' ',a.id_kustomer ) as assignment,
		ifnull( DATE_FORMAT( DATE_ADD(a.created_date, INTERVAL case when a.service=1 then 0 when a.service=2 then 1 else b.sla_max end DAY), "%Y-%m-%d" ) ,'0' ) as sla_max
	from pengiriman a 
	left join sla b on b.kota_asal = case when x_inter(a.id_kota_asal)='Y' then 9999 else a.id_kota_asal end  AND b.kota_tujuan= case when x_inter(a.id_kota_tujuan)='Y' then 9999 else a.id_kota_tujuan end AND b.service = a.service

				left join kota c on c.id_kota=a.lokasi_asal 

				left join regencies d on d.id=a.id_kota_asal

	 where id_kustomer != 0 and status=1 and pickup='N' and (pickup_request is null or pickup_request='') 
	 and d.province_id in ($u[area_prov])  

	 and status!=7 and x_inter(a.id_kota_asal)!='Y'
	 group by id_pengiriman
	) temp
EOT;
		}
		elseif($u['level']=='agent'){
		$agent = mysqli_query($con, 'select id_kota,id_kota_baru from cabang where id_cabang='.$u['id_cabang'].'');
		$a = mysqli_fetch_assoc($agent);
		$table = 
<<<EOT
	(
	select
	airwaybill_kode,
		CONCAT(DATE_FORMAT(a.created_date,"%Y-%m-%d"),' ', 
		
			ifnull( DATE_FORMAT( DATE_ADD(a.created_date, INTERVAL case when a.service=1 then 0 when a.service=2 then 1 else b.sla_max end DAY), "%Y-%m-%d" ) ,'0' ), ' ', a.id_kota_asal, ' ',a.service
			) as created_date,
		x_namakustomer(id_kustomer) as nama_kustomer,
		case when a.id_kota_asal>0 then a.kota_asal else x_namakotabesar(a.lokasi_asal) end as kota_asal,
		case when a.service != 6 then x_namakotanew(a.id_kota_tujuan) else x_namanegara(a.lokasi_tujuan) end as kota_tujuan,
		x_service(a.service) as service,
		id_pengiriman,
		CONCAT(id_pengiriman, ' ' ,DATE_FORMAT(a.created_date,"%Y-%m-%d"), ' ', a.id_kota_asal, ' ',a.service,' ', '$u[level]',' ',a.id_kota_tujuan,' ',a.service,' ',a.id_kustomer ) as assignment,
		ifnull( DATE_FORMAT( DATE_ADD(a.created_date, INTERVAL case when a.service=1 then 0 when a.service=2 then 1 else b.sla_max end DAY), "%Y-%m-%d" ) ,'0' ) as sla_max
	from pengiriman a 
	left join sla b on b.kota_asal = case when x_inter(a.id_kota_asal)='Y' then 9999 else a.id_kota_asal end  AND b.kota_tujuan= case when x_inter(a.id_kota_tujuan)='Y' then 9999 else a.id_kota_tujuan end AND b.service = a.service

	left join kota c on c.id_kota=a.lokasi_asal where id_kustomer != 0 and a.status=1 and pickup='N' and (pickup_request is null or pickup_request='') and if(id_kota_asal>0,x_inter(a.id_kota_asal)!='Y',x_intercity(a.lokasi_asal)!='Y') and if( a.id_kota_asal>0, a.id_kota_asal=$a[id_kota_baru] ,(a.lokasi_asal=$a[id_kota] or c.hub=$a[id_kota])) and status!=7
	group by id_pengiriman
	) temp
EOT;
		}
		
		$primaryKey = 'id_pengiriman';

		$columns = array(
			array('db'		=> 'id_pengiriman',
					'dt'	=> 0,
					'formatter'	=> function($d,$row){
						return '<td><center><input type="checkbox" name="selectawb[]" value="'.$d.'"> </center></td>';
					}
			),
			array('db'		=> 'airwaybill_kode',
					'dt'	=> 1,
					'formatter'	=> function($d,$row){
						return '<td><center>'.$d.' </center></td>';
					}
			),
			array('db'	=> 'created_date',
					'dt'		=> 2,
					'formatter' => function( $d, $row ) {
						$pieces = explode(' ', $d);

						$booking_date = date('Y-m-d',strtotime($pieces[0]));

						$kota_asal = $pieces[2];

						$db_host = "127.0.0.1";
						$db_user = "cesco_user";
						$db_pass = "c1l4ndak33032";
						$db_name = "cesco_logistics";

						$db_akuntansi = "cesco_finance";

						$con = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

						$cekkota 		= mysqli_query($con, "select x_hubnew(id) as hub, intercity, province_id from regencies where id=$kota_asal");

						$r= mysqli_fetch_assoc($cekkota);

						if ($pieces[3] >= 3){
							if($r['intercity'] == 'Y'){
								if($r['province_id'] == 31){
									$sla_booking = date('Y-m-d',strtotime($pieces[0]));								
								}else{
									$sla_booking = date('Y-m-d',strtotime($pieces[0].'+1 day'));								
								}

							}else{
								if($r['hub'] == $kota_asal){
									$sla_booking = date('Y-m-d',strtotime($pieces[0].'+1 day'));	
								}else{
									$sla_booking = date('Y-m-d',strtotime($pieces[0].'+2 day'));
								}
							}
						}else{
							$sla_booking = $booking_date;
						}
						
						//$sla_booking = date('Y-m-d',strtotime($pieces[0].'+1 day'));
						//$sla =  $pieces[1];

						$d1 = strtotime($sla_booking);
						$d2 = date('l',$d1);
						$dt3 = strtolower($d2);

						
				   		if($dt3 == "sunday")
						{
				            $sla_weekend = date('Y-m-d',strtotime($sla_booking.'+1 day'));
				        }else{
				        	$sla_weekend = date('Y-m-d',strtotime($sla_booking));
				        }

				        $sla = date('Y-m-d',strtotime($sla_weekend));
						$date_now =  date("Y-m-d");

						if($sla != '1970-01-01'){
							
							if ($date_now > $sla){

								$created_date = $booking_date.' - '. $sla.' <button class="btn btn-xs btn-danger" title="Terlambat!"><i class="fa fa-exclamation-circle"></i> </button>';
							}else{
								$created_date =  $booking_date.' - '. $sla;
							}
						}else{
							$created_date =  $booking_date;
						}

						//return  $created_date;
						return $created_date;
					}
			),
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
			array(
				  'db'        => 'assignment',
				  'dt'        => 7,
				  'formatter' => function( $d, $row ) {
					$date_now = date("Y-m-d");
					$pieces = explode(' ', $d);

					$id_pengiriman = $pieces[0];

					//mulai
					$booking_date = $pieces[1];
					$kota_asal = $pieces[2];

					$kota_tujuan = $pieces[5];
					$service = $pieces[6];
					$id_kustomer = $pieces[7];

						$db_host = "127.0.0.1";
						$db_user = "cesco_user";
						$db_pass = "c1l4ndak33032";
						$db_name = "cesco_logistics";

						$db_akuntansi = "cesco_finance";

						$con = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

						$cekkota 		= mysqli_query($con, "select x_hubnew(id) as hub, intercity, province_id from regencies where id=$kota_asal");

						$r= mysqli_fetch_assoc($cekkota);

						if ($pieces[3] >= 3){
							if($r['intercity'] == 'Y'){
								if($r['province_id'] == 31){
									$sla_booking = date('Y-m-d',strtotime($pieces[1]));								
								}else{
									$sla_booking = date('Y-m-d',strtotime($pieces[1].'+1 day'));								
								}

							}else{
								if($r['hub'] == $kota_asal){
									$sla_booking = date('Y-m-d',strtotime($pieces[1].'+1 day'));	
								}else{
									$sla_booking = date('Y-m-d',strtotime($pieces[1].'+2 day'));
								}
							}
						}else{
							$sla_booking = $booking_date;
						}


						// id 0 booking 1 lokasi asal2 service3
					//ahir




					$d1 = strtotime($sla_booking);
						$d2 = date('l',$d1);
						$dt3 = strtolower($d2);

						
				   		if($dt3 == "sunday")
						{
				            $sla_weekend = date('Y-m-d',strtotime($sla_booking.'+1 day'));
				        }else{
				        	$sla_weekend = date('Y-m-d',strtotime($sla_booking));
				        }

				        $sla = date('Y-m-d',strtotime($sla_weekend));

					//$booking_date = $pieces[2];

					$key = "asdf$1011";
					$level = $pieces[4];

					$cek_rate		= mysqli_query($con, "select id  from rate_customer where id_kustomer = $id_kustomer AND id_pelayanan=$service AND id_lokasi_asal=$kota_asal AND id_lokasi_tujuan = $kota_tujuan ");

						$rc= mysqli_num_rows($cek_rate);

					if($rc > 0 || $id_kustomer == 79 || $id_kustomer == 80 || $id_kustomer == 81){
						if ($level == ''){
							$assignment = '-';
						}
						else{
							if($sla != '1970-01-01'){
								if($date_now > $sla){
									$assignment = '<td><center><button class="btn btn-xs btn-primary open_assignment" id="1" data-id="'.base64_encrypt($id_pengiriman,$key).' 1 '.$sla.'">Assignment</button></center></td>';
								}else{
									$assignment = '<td><center><button class="btn btn-xs btn-primary open_assignment" id="0" data-id="'.base64_encrypt($id_pengiriman,$key).' 0 '.$sla.'">Assignment</button></center></td>';							
								}
							} 
							else{
								$assignment = '<td><center><button class="btn btn-xs btn-primary open_assignment" id="0" data-id="'.base64_encrypt($id_pengiriman,$key).' 0">Assignment</button></center></td>';						
							}
						}	
					}
					else{
						$assignment = '<button class="btn btn-xs btn-success custom_price_insert_pickup"  data-target="#modal_price_insert_pickup" data-id="'.$id_pengiriman.'" title="Belum ada Tarif Customer, Hubungi Finance!"><i class="fa fa-exclamation-circle"></i> </button>';
					}

					
					
					


					return $assignment;
				  
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
		
	// update pickup
	}elseif ($module=='manifest-pickup' AND $act=='update'){
		// buat no transaksi
		$today = date("Ymd");
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

		$reason = mysqli_real_escape_string($con, $_POST['reason']);

		if ($reason != ''){
			$reason = $reason;
		}
		else{
			$reason = '';
		}

		$id = mysqli_real_escape_string($con, $_POST['id']);
		$pickup_request = mysqli_real_escape_string($con, $_POST['pickup_request']);
		$manifest_day = mysqli_real_escape_string($con, $_POST['manifest_day']);

		$query = mysqli_query($con, "SELECT case when id_kota_asal > 0 then x_inter(id_kota_asal) else  x_intercity(lokasi_asal) end as intercity,alasan_terlambat_pickup, case when id_kota_asal>0 then id_kota_asal else lokasi_asal end as lokasi_asal, id_kota_asal, id_kustomer,service,created_date,id_provinsi_asal, x_hubnew(id_kota_asal) as hub,
			loc_code,airwaybill_kode, kecamatan_asal, kota_asal,x_user(".$pickup_request.") as nama_kurir,
			ifnull(berat_aktual,berat_barang) as berat_aktual

		 FROM pengiriman WHERE id_pengiriman='".$id."'");
		$r = mysqli_fetch_assoc($query);

		$awb = $r['airwaybill_kode'];
		$service = $r['service'];
		if($service == 1){
			$servicevdi = 2;
		}elseif($service == 2){
			$servicevdi = 1;
		}elseif($service == 3){
			$servicevdi = 0;
		}elseif($service == 5){
			$servicevdi = 3;
		}
		$id_kustomer = $r['id_kustomer'];

			if($id_kustomer == '13'){
				$now = date("Y-m-d\TH:i:s");
				/*$postDataDeliv = [
				    'noDR'=> $r['loc_code'],
				    'noAWB'=> $awb,
				    'serviceType'=> $servicevdi,
				    'statusDelivery'=> 0,
				    'statusDeliveryUpdateDate'=> $now,
				    'notes'=> '',
				    'receiverName'=> '',
				    'totalColly'=> $r['collie'],
				    'totalCost'=> 1,
				    'volumeInMtr'=> $r['berat_aktual'],
				    'cityCode'=> $r['kota_asal'],
				    'districtCode'=> $r['kecamatan_asal'],
				    'weightInKg'=> $r['berat_aktual'],
				   'courierId'=> $r['nama_kurir'],
				    'leadtime'=> 0,
				     'Content-Type: application/json'
				  ];*/

				  $postDataDeliv = [
		            '_ListDRMover' => [
		                'aWBNo' => $awb,
		                'cityCode' => $r['kota_asal'],
		                'CourierId' => 'V-00069',
		                'DistrictCode' => $r['kecamatan_asal'],
		                'drNo' => $r['loc_code'],
		                'Leadtime' => 1,
		                'notes' => 'kurir '.$r['nama_kurir'].' sedang menuju tempat penjemputan.',
		                'receiverName' => '',
		                'serviceType' => $servicevdi,
		                'statusDelivery' => 0,
		                'statusDeliveryUpdateDate' => $now,
		                'totColly' => $r['collie'],
		                'totCost' => 0,
		                'volInMeter' => $r['berat_aktual'],
		                'weightInKg' => $r['berat_aktual'],
		                'Content-Type: application/json'
		            ]
		        ];

				update_vdi($postDataDeliv);
			}

		if ($r['service'] >= 3){
			if($r['intercity'] == 'Y'){
				if($r['id_provinsi_asal'] == '31'){
					$sla_booking = $r['created_date'];
				}else{
					$sla_booking = date('Y-m-d',strtotime($r['created_date'].'+1 day'));	
				}
				

			}else{
				if($r['hub'] == $r['id_kota_asal']){
					$sla_booking = date('Y-m-d',strtotime($r['created_date'].'+1 day'));	
				}else{
					$sla_booking = date('Y-m-d',strtotime($r['created_date'].'+2 day'));										
				}									
			}

		}else{
			$sla_booking = $r['created_date'];
		
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

		$d= strtotime($manifest_day);

		if ($sla>=$d){
			$date_request = $sla;
		}else{
			$date_request = $manifest_day;
		}				     
		
		mysqli_query($con, "UPDATE pengiriman set pickup_request='".$pickup_request."', alasan_terlambat_pickup='$reason', pickup_date_request='$date_request', manifest_pickup_date=now(), manifest_pickup_by='$u[id_user]' WHERE id_pengiriman='".$id."'"); 
		//manifest detail

		$datetime = new DateTime('tomorrow');
		$tomorrow =  $datetime->format('Y-m-d');
		
		$tgl_jam = date("Y-m-d H:i:s"); 

		$format_awal = "Y-m-d 19:00:00";
		$batas_awal = date($format_awal);
		$format_ahir = "Y-m-d 23:59:59";
		$batas_ahir = date($format_ahir);

		if ($tgl_jam >= $batas_awal && $tgl_jam <= $batas_ahir) {
			$set_tgl = $tomorrow;
		}
		else{
			$set_tgl = $tgl_sekarang;
		}



		
		$cek = mysqli_query($con, "SELECT * FROM manifest WHERE tanggal='".$set_tgl."' AND manifest_pickup_by ='".$pickup_request."' AND tipe_manifest ='pickup'");
		$ketemu	= mysqli_num_rows($cek);
		$r = mysqli_fetch_assoc($cek);
		if($ketemu==0){
				mysqli_query($con, "INSERT INTO manifest (kode_manifest,tanggal,manifest_pickup_by,tipe_manifest,created_date,created_by) VALUES ('$nextNoTransaksi','$set_tgl','$pickup_request','pickup','$tgl_jam','$u[id_user]')");
				$id_manifest = mysqli_insert_id($con);
				// insert to manifest detail
				mysqli_query($con, "insert into manifest_detail (id_manifest,id_pengiriman) values ('$id_manifest','$id')");
				// update pengiriman
				//mysqli_query($con, "update pengiriman set status='3', berat_aktual='$berat_aktual', collie='$collie', pcs='$pcs', packing_kayu='$packing_kayu', transit='N' where id_pengiriman='$_POST[id]'");
		}	
		else{
			mysqli_query($con, "insert into manifest_detail (id_manifest,id_pengiriman) values ('$r[id_manifest]','$id')");
		}		
		//mysqli_query($con, "insert into manifest_detail (id_manifest,id_pengiriman) values ('$t[id_manifest]','$_POST[id]')");
		header('location:../../main.php?module='.$module);

	// assigment
	}elseif($module=='manifest-pickup' AND $act=='assignment'){

		$pieces = explode(' ', $_POST['id']);

		$id_pengiriman = $pieces[0];
		$sla = $pieces[1];
		$sla_tanggal = $pieces[2];

		$key = "asdf$1011";
		$encrypt = "$pieces[0]";
		$id = base64_decrypt($encrypt,$key);
		
		$query = mysqli_query($con, "SELECT airwaybill_kode,

			x_inter(id_kota_asal) as intercity,

			x_inter(id_kota_asal) as inter_asal , alasan_terlambat_pickup, 

			id_kota_asal as lokasi_asal, 

			id_kota_asal, id_kustomer,DATE_FORMAT(created_date,'%Y-%m-%d') as tanggal_dibuat , created_date FROM pengiriman WHERE id_pengiriman='".$id."'");
		$r = mysqli_fetch_assoc($query);


		
		echo'
		
			<div class="form-group" style="padding-bottom: 5px;">
			<input type="hidden" name="id" class="form-control" value="'.$id.'" />';
			if($r['intercity']=='Y'){
				echo'
				 <label>AWB '.$r['airwaybill_kode'].'</label>
                <label>Kurir/Driver</label>
     			<select name="pickup_request" class="form-control select2" width="100%">';
					$result = mysqli_query($con,"select id_user, namalengkap, id_kustomer, area from user where (level='kurir' or level='driver') and blokir='N' order by namalengkap asc");
					while ($row = mysqli_fetch_assoc($result)) {
						echo '<option value="' . $row['id_user'] . '">'.strtoupper($row['namalengkap']).'</option>';
					}
					echo '
				</select>';
			}else{
				echo'
				<label>Network/Agent</label>
				<select name="pickup_request" class="form-control select2" width="100%">';
					if($r['id_kota_asal'] > 0){
						$result = mysqli_query($con,"select a.id_user, concat(b.nama_cabang,' ','(',b.alamat1,')',' / ',a.namalengkap) as namalengkap 

							from user a 
							left join cabang b on b.id_cabang=a.id_cabang 
							left join regencies c on c.id=b.id_kota_baru 


							where a.blokir != 'Y' AND (a.level='agent' or a.level='kurir_agent') 

							and (c.id=x_hubnew('".$r['id_kota_asal']."') or c.id = '".$r['id_kota_asal']."' or c.id=x_hubbackup('".$r['id_kota_asal']."')) order by a.id_user desc");
					}
					else{
						if($u['level']=='admin' or $u['level']=='operator' or $u['level']=='network' or $u['level']=='inbound'){
							$result = mysqli_query($con,"

								select a.id_user, 
								concat(b.nama_cabang,' ','(',b.alamat1,')',' / ',a.namalengkap) as namalengkap 

								from user a inner join cabang b on b.id_cabang=a.id_cabang 

								 where  a.blokir != 'Y' AND (a.level='agent' or a.level='kurir_agent') and (c.id_kota=x_hub('".$r['lokasi_asal']."') or c.id_kota = '".$r['lokasi_asal']."') order by a.id_user desc");


						}elseif($u['level']=='domex'){
							$result = mysqli_query($con,"select a.id_user, concat(b.nama_cabang,' ','(',b.alamat1,')',' / ',a.namalengkap) as namalengkap from user a inner join cabang b on b.id_cabang=a.id_cabang inner join kota c on c.id_kota=b.id_kota where  a.blokir != 'Y' AND (a.level='agent' or a.level='kurir_agent') and (c.id_kota=x_hub('".$r['lokasi_asal']."') or c.id_kota = '".$r['lokasi_asal']."') order by a.id_user desc");
						}else{
							$result = mysqli_query($con,"select a.id_user, concat(b.nama_cabang,' ','(',b.alamat1,')',' / ',a.namalengkap) as namalengkap from user a inner join cabang b on b.id_cabang=a.id_cabang inner join kota c on c.id_kota=b.id_kota where a.blokir != 'Y' AND (a.level='agent' or a.level='kurir_agent') and (c.id_kota=x_hub('".$r['lokasi_asal']."') or c.id_kota = '".$r['lokasi_asal']."') order by a.id_user desc");
						}
					}
					while ($row = mysqli_fetch_assoc($result)) {
						echo '<option value="' . $row['id_user'] . '">'.strtoupper($row['namalengkap']).'</option>';
					}

					
							$result = $result = mysqli_query($con,"select a.id_user, concat(b.nama_cabang,' ','(',b.alamat1,')',' / ',a.namalengkap) as namalengkap 

							from user a 
							left join cabang b on b.id_cabang=a.id_cabang 
							where b.trucking_all_city = 'Y' AND (a.level='agent' or a.level='kurir_agent' )
							order by a.id_user desc

							");

								while ($row = mysqli_fetch_assoc($result)) {
									echo '<option value="' . $row['id_user'] . '"><b>'.strtoupper($row['namalengkap']).'</b> </option>';
								}
						

					echo '
				</select>

				

				';
			}
			echo '<div class="form-group" style="padding-bottom: 5px;">
    		      		<label>Manifest day:'.$sla_tanggal.$r['tanggal_dibuat'].'</label>
     					<select name="manifest_day" class="form-control" required>';
			
							echo '<option value="'.date('Y-m-d',strtotime($sla_tanggal)).'">Max Pickup = '.date('Y-m-d',strtotime($sla_tanggal)).'</option>';
							
							$done = strtotime(date('Y-m-d',strtotime($sla_tanggal.'+1 day')));
										$dtwo = date('l',$done);
										$dthree = strtolower($dtwo);
							if($sla_tanggal == $r['tanggal_dibuat']){					
								if($dthree == "sunday" )
										{
								            echo '<option value="'.date('Y-m-d',strtotime($sla_tanggal.'+1 day'))	.'">Max Pickup +1 = '.date('Y-m-d',strtotime($sla_tanggal.'+1 day')).'</option>';
								        }				
							}	        		
										$booking_time = date('Y-m-d H:i', strtotime($r['created_date']));
										$batas = date('Y-m-d H:i', strtotime($r['tanggal_dibuat']." 15:00"));


										if ($r['inter_asal'] == 'Y' && $booking_time >= $batas){
											echo '<option value="'.date('Y-m-d',strtotime($sla_tanggal.'+1 day'))	.'">Max Pickup +1 = '.date('Y-m-d',strtotime($sla_tanggal.'+1 day')).'</option>';
										}

								        $plus = mysqli_query($con,"select plus_hari from libur_plus where tanggal_mulai <= CURDATE() and tanggal_akhir >= CURDATE() ");

								        while ($p = mysqli_fetch_assoc($plus)) {
											echo '<option value="'.date('Y-m-d',strtotime($sla_tanggal.'+'.$p['plus_hari'].' day'))	.'">Max Pickup +'.$p['plus_hari'].' = '.date('Y-m-d',strtotime($sla_tanggal.'+'.$p['plus_hari'].' day')).'</option>';
										}
							
							
			
					echo'
						</select>
				</div>';
			if($sla==1){
				if ($r['alasan_terlambat_pickup'] != ''){
					echo '<label>ALASAN TERLAMBAT = '.$r['alasan_terlambat_pickup'].'</label>';
				}else{
					echo '<div class="form-group" style="padding-bottom: 5px;">
	                	<label>ALASAN TERLAMBAT:</label>
	                	<input type="text" class="form-control style="width: 100%;" name="reason" required>						
	            	</div>';	
				}
				

	         }
			echo'
			</div>
			
		';
	}
	elseif($module=='manifest-pickup' AND $act=='assignment-all'){
		$awb		= $_POST['selectawb'];
		$id_awb		= implode(',',$awb);
		$kurir 		= $_POST['kurir_all']; 
		echo $id_awb. '<br>'.$kurir.'<br>';
		if ($id_awb == ''){
			$_SESSION['pesan'] = 'null';
			header('Location: ' . $_SERVER['HTTP_REFERER']);
		}
		else{
			$today = date("Ymd");
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

			//$id = mysqli_real_escape_string($con, $_POST['id'])
			$pickup_request = mysqli_real_escape_string($con, $_POST['kurir_all']);

			$tdy = date("Y-m-d");

			foreach($_POST['selectawb'] as $value){
				$query = mysqli_query($con, "SELECT case when id_kota_asal > 0 then x_inter(id_kota_asal) else  x_intercity(lokasi_asal) end as intercity,alasan_terlambat_pickup, case when id_kota_asal>0 then id_kota_asal else lokasi_asal end as lokasi_asal, id_kota_asal, id_kustomer,service,created_date,id_provinsi_asal,x_hubnew(id_kota_asal) as hub FROM pengiriman WHERE id_pengiriman='".$value."'");
				$r = mysqli_fetch_assoc($query);

				if ($r['service'] >= 3){
					if($r['intercity'] == 'Y'){
						if($r['id_provinsi_asal'] == '31'){
							$sla_booking = $r['created_date'];
						}else{
							$sla_booking = date('Y-m-d',strtotime($r['created_date'].'+1 day'));	
						}
						

					}else{
						if($r['hub'] == $r['id_kota_asal']){
							$sla_booking = $r['created_date'];				
						}else{
							$sla_booking = date('Y-m-d',strtotime($r['created_date'].'+2 day'));										
						}									
					}

				}else{
					$sla_booking = $r['created_date'];
				
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
				
		        if ($sla<$tdy){
					$date_request = $tdy;
				}else{
					$date_request = $sla;
				}	

				//echo $tdy.'<br>'.$sla.'<br>';
            	mysqli_query($con, "UPDATE pengiriman set pickup_request='".$pickup_request."', pickup_date_request='$date_request', manifest_pickup_date=now(), manifest_pickup_by='$u[id_user]'  WHERE id_pengiriman ='$value' ");	
            	
            	//echo "UPDATE pengiriman set pickup_request='".$pickup_request."', pickup_date_request='$date_request'  WHERE id_pengiriman ='$value' <br>";


        	}

        	//return false;

			
			//manifest detail

			$datetime = new DateTime('tomorrow');
			$tomorrow =  $datetime->format('Y-m-d');
			
			$tgl_jam = date("Y-m-d H:i:s"); 

			$format_awal = "Y-m-d 19:00:00";
			$batas_awal = date($format_awal);
			$format_ahir = "Y-m-d 23:59:59";
			$batas_ahir = date($format_ahir);

			if ($tgl_jam >= $batas_awal && $tgl_jam <= $batas_ahir) {
				$set_tgl = $tomorrow;
			}
			else{
				$set_tgl = $tgl_sekarang;
			}

			$cek = mysqli_query($con, "SELECT * FROM manifest WHERE tanggal='".$set_tgl."' AND manifest_pickup_by ='".$pickup_request."' AND tipe_manifest ='pickup'");
			$ketemu	= mysqli_num_rows($cek);
			$r = mysqli_fetch_assoc($cek);
			//echo $ketemu.'<br>';
			if($ketemu==0){
				$_SESSION['pesan'] = 'success';
				mysqli_query($con, "INSERT INTO manifest (kode_manifest,tanggal,manifest_pickup_by,tipe_manifest,created_date,created_by) VALUES ('$nextNoTransaksi','$set_tgl','$pickup_request','pickup','$tgl_jam','$u[id_user]')");
				$id_manifest = mysqli_insert_id($con);
					// insert to manifest detail
					foreach($_POST['selectawb'] as $value) {
						mysqli_query($con, "insert into manifest_detail (id_manifest,id_pengiriman) values ('$id_manifest','$id')");
						//echo "insert into manifest_detail (id_manifest,id_pengiriman) values ('$id_manifest','$value') <br>";
					}
					
					
					// update pengiriman
					//mysqli_query($con, "update pengiriman set status='3', berat_aktual='$berat_aktual', collie='$collie', pcs='$pcs', packing_kayu='$packing_kayu', transit='N' where id_pengiriman='$_POST[id]'");
			}	
			else{
				$_SESSION['pesan'] = 'success';
				foreach($_POST['selectawb'] as $value) {
						mysqli_query($con, "insert into manifest_detail (id_manifest,id_pengiriman) values ('$r[id_manifest]','$id')");
						//echo "insert into manifest_detail (id_manifest,id_pengiriman) values ('$r[id_manifest]','$value') <br>";
					}
			}

			
			
			header('Location: ' . $_SERVER['HTTP_REFERER']);
		}
	}
	elseif($module=='manifest-pickup' AND $act=='test'){
	echo	$tgl_sekarang = date("Ymd").'<br>'; 
	echo	$tgl_skrg     = date("d").'<br>'; 
	echo	$bln_sekarang = date("m").'<br>'; 
	echo	$thn_sekarang = date("Y").'<br>'; 
	echo	$jam_sekarang = date("H:i:s").'<br>'; 
	echo	$tgl_jam = date("Y-m-d H:i:s").'<br>'; 
	echo	$setdate = date("Y-m-d").'<br>';
	
	$datetime = new DateTime('tomorrow');
	$tomorrow =  $datetime->format('Y-m-d');
	echo $tomorrow;

	$format_awal = "Y-m-d 19:00:00";
	$batas_awal = date($format_awal);
	$format_ahir = "Y-m-d 23:59:59";
	$batas_ahir = date($format_ahir);

	if ($tgl_jam >= $batas_awal && $tgl_jam <= $batas_ahir) {
		echo 1;
	
	}
	else{
		echo 2;
	}

	}

}
?>