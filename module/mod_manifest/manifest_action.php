<?php
if(!isset($_SESSION)){
    session_start();
}

if (empty($_SESSION['username']) AND empty($_SESSION['password'])){
	header('location:../application/login.php');
}else{
	include "../../config/connection.php";
	include "../../config/library.php";
	include "../../config/encrypt.php";
	include "../../config/db_connect.php";
	
	$key = 'asdf$1011';
	$encrypt = $_SESSION['id_user'];
	$id = base64_decrypt($encrypt,$key);
	$useraktif = mysqli_query($con, 'select * from user where id_user="'.$id.'"');
	$u = mysqli_fetch_assoc($useraktif);

	$module	= !empty($_GET['module']) ? $_GET['module'] : '';
	$act	= !empty($_GET['act']) ? $_GET['act'] : '';
	
	$aksi="module/mod_manifest/manifest_action.php";
	
	if($module=='manifest' and $act=='data'){
		

		if($u['level']=='admin' || $u['tambahan_admin'] == '1' || $u['level'] == 'finance'){
		$table = 
<<<EOT
	(
	select 
		airwaybill_kode,
		
		
		CONCAT(DATE_FORMAT(a.created_date,"%Y-%m-%d"),' ', 
		
		case 
			when a.service = 1 then ifnull(case when x_dhl(a.id_kustomer) = 'Y' then i.sla else o.sla end,0) 
			when a.service = 2 then ifnull(case when x_dhl(a.id_kustomer) = 'Y' then i.sla else o.sla end,1) 
			else
				ifnull(case when x_dhl(a.id_kustomer) = 'Y' then i.sla else o.sla end,if (x_hubnew(a.id_kota_tujuan) = a.id_kota_tujuan,1,2)) 
			end

			 ,' ',ifnull(a.manifest_received,a.pickup_date),' ',a.id_pengiriman
			) as created_date,

		x_namakustomer(id_kustomer) as nama_kustomer,
		a.kota_asal,
		a.kota_tujuan,
		x_service(a.service) as service,
		
		CONCAT(id_pengiriman, ' ', 
		ifnull(case when x_dhl(a.id_kustomer) = 'Y' then i.sla else o.sla end,if (x_hubnew(a.id_kota_tujuan) = a.id_kota_tujuan,1,2)) 

			, ' ' ,ifnull(a.manifest_received,DATE_FORMAT(a.pickup_date,"%Y-%m-%d")),' ', '99' ) as assignment

		, id_pengiriman
	from pengiriman a  
	
	left join sla_deliv o on o.kota_tujuan =  a.id_kota_tujuan AND o.service = a.service	

	left join sla_deliv_dhl i on i.kota_tujuan =  a.id_kota_tujuan AND i.service = a.service


	where status != 6 and  pickup='Y' and delivery='N' and (courier_by is null or courier_by =0) and status!='7' 

	and 
		case when x_inter(a.id_kota_asal)='Y' and x_inter(a.id_kota_tujuan) ='Y' then status='2' 

		when a.service = 6 then status = 2

		else status='4' 

		 and a.service != 6 



		end group by a.id_pengiriman
	) temp
EOT;
		}elseif( $u['level']=='intercity' || strpos($u['menu_tambahan'], 'manifest') !== false){
		$table = 
<<<EOT
	(
	select 
		airwaybill_kode,
		
		CONCAT(DATE_FORMAT(a.created_date,"%Y-%m-%d"),' ', 
		case 
			when a.service = 1 then ifnull(case when x_dhl(a.id_kustomer) = 'Y' then i.sla else o.sla end,0) 
			when a.service = 2 then ifnull(case when x_dhl(a.id_kustomer) = 'Y' then i.sla else o.sla end,1) 
			else
				ifnull(case when x_dhl(a.id_kustomer) = 'Y' then i.sla else o.sla end,if (x_hubnew(a.id_kota_tujuan) = a.id_kota_tujuan,1,2)) 
			end

			, ' ' ,ifnull(a.manifest_received,a.pickup_date)
			) as created_date,
		
		x_namakustomer(id_kustomer) as nama_kustomer,
		case when lokasi_asal=0 then a.kota_asal else x_namakotabesar(a.lokasi_asal) end as kota_asal,
		a.kota_tujuan,
		x_service(a.service) as service,
		CONCAT(id_pengiriman, ' ', 

		ifnull(case when x_dhl(a.id_kustomer) = 'Y' then i.sla else o.sla end,if (x_hubnew(a.id_kota_tujuan) = a.id_kota_tujuan,1,2)) 

			, ' ' ,ifnull(a.manifest_received,DATE_FORMAT(a.pickup_date,"%Y-%m-%d")),' ', ifnull(a.delivery_by_agent,'39') ) as assignment,
		id_pengiriman
	from pengiriman a  
	left join sla_deliv o on o.kota_tujuan =  a.id_kota_tujuan AND o.service = a.service	

	left join sla_deliv_dhl i on i.kota_tujuan =  a.id_kota_tujuan AND i.service = a.service



	where status != 6 and  pickup='Y' and delivery='N' and (courier_by is null or courier_by =0) and status!='7' and 
	case 
		when  x_inter(a.id_kota_asal) ='Y'  and x_inter(a.id_kota_tujuan) ='Y' then status='2' 

		when a.service = 6 then status = 2
	end
	
	

	 group by a.id_pengiriman
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
		case 
			when a.service = 1 then ifnull(case when x_dhl(a.id_kustomer) = 'Y' then i.sla else o.sla end,0) 
			when a.service = 2 then ifnull(case when x_dhl(a.id_kustomer) = 'Y' then i.sla else o.sla end,1) 
			else
				ifnull(case when x_dhl(a.id_kustomer) = 'Y' then i.sla else o.sla end,if (x_hubnew(a.id_kota_tujuan) = a.id_kota_tujuan,1,2)) 
			end

			, ' ' ,ifnull(a.manifest_received,a.pickup_date)
			) as created_date,
		x_namakustomer(id_kustomer) as nama_kustomer,
		case when lokasi_asal=0 then a.kota_asal else x_namakotabesar(a.lokasi_asal) end as kota_asal,
		a.kota_tujuan,
		x_service(a.service) as service,
		CONCAT(id_pengiriman, ' ', 
		ifnull(case when x_dhl(a.id_kustomer) = 'Y' then i.sla else o.sla end,if (x_hubnew(a.id_kota_tujuan) = a.id_kota_tujuan,1,2)) 

			, ' ' ,ifnull(a.manifest_received,DATE_FORMAT(a.pickup_date,"%Y-%m-%d")),' ', '99' ) as assignment,
		id_pengiriman
	from pengiriman a  
	left join sla_deliv o on o.kota_tujuan =  a.id_kota_tujuan AND o.service = a.service	

	left join sla_deliv_dhl i on i.kota_tujuan =  a.id_kota_tujuan AND i.service = a.service

			left join kota c on c.id_kota=a.lokasi_tujuan 
			left join regencies d on d.id=a.id_kota_tujuan

	where status != 6 and  status='4' and pickup='Y' and a.service != 6  and (courier_by is null or courier_by =0) and if(id_kota_asal>0,d.province_id in ($u[area_prov]),c.kode_pulau in ($u[area_pulau]))  and status!='7' and a.service != 6 
			and if(a.id_kota_asal>0,x_inter(a.id_kota_asal),x_intercity(a.lokasi_asal))='Y' 
			and if(a.id_kota_tujuan>0,x_inter(a.id_kota_tujuan),x_intercity(a.lokasi_tujuan))!='Y' 

			
			group by a.id_pengiriman
	) temp
EOT;
		}

		elseif($u['level']=='admin_network'){
		$table = 
<<<EOT
	(
	select 
		airwaybill_kode,
		
		CONCAT(DATE_FORMAT(a.created_date,"%Y-%m-%d"),' ', 
		case 
			when a.service = 1 then ifnull(case when x_dhl(a.id_kustomer) = 'Y' then i.sla else o.sla end,0) 
			when a.service = 2 then ifnull(case when x_dhl(a.id_kustomer) = 'Y' then i.sla else o.sla end,1) 
			else
				ifnull(case when x_dhl(a.id_kustomer) = 'Y' then i.sla else o.sla end,if (x_hubnew(a.id_kota_tujuan) = a.id_kota_tujuan,1,2)) 
			end

			, ' ' ,ifnull(a.manifest_received,a.pickup_date)
			) as created_date,
		x_namakustomer(id_kustomer) as nama_kustomer,
		case when lokasi_asal=0 then a.kota_asal else x_namakotabesar(a.lokasi_asal) end as kota_asal,
		a.kota_tujuan,
		x_service(a.service) as service,
		CONCAT(id_pengiriman, ' ', 
		ifnull(case when x_dhl(a.id_kustomer) = 'Y' then i.sla else o.sla end,if (x_hubnew(a.id_kota_tujuan) = a.id_kota_tujuan,1,2)) 

			, ' ' ,ifnull(a.manifest_received,DATE_FORMAT(a.pickup_date,"%Y-%m-%d")),' ', '99' ) as assignment,
		id_pengiriman
	from pengiriman a  
	left join sla_deliv o on o.kota_tujuan =  a.id_kota_tujuan AND o.service = a.service	

	left join sla_deliv_dhl i on i.kota_tujuan =  a.id_kota_tujuan AND i.service = a.service

			

	where status != 6 and  status='4' and pickup='Y' and a.service != 6  and (courier_by is null or courier_by =0) 

	and a.airwaybill_kode not like '002-%'

	and status!='7' and a.service != 6 
			

			or (status='2' and pickup='Y' and a.service=4 and x_inter(a.id_kota_asal) = 'Y' and x_inter(a.id_kota_tujuan) = 'Y')
			group by a.id_pengiriman
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
		
		case 
			when a.service = 1 then ifnull(case when x_dhl(a.id_kustomer) = 'Y' then i.sla else o.sla end,0) 
			when a.service = 2 then ifnull(case when x_dhl(a.id_kustomer) = 'Y' then i.sla else o.sla end,1) 
			else
				ifnull(case when x_dhl(a.id_kustomer) = 'Y' then i.sla else o.sla end,if (x_hubnew(a.id_kota_tujuan) = a.id_kota_tujuan,1,2)) 
		end

			, ' ' ,ifnull(a.manifest_received,a.pickup_date)
			) as created_date,
		x_namakustomer(id_kustomer) as nama_kustomer,
		case when lokasi_asal=0 then a.kota_asal else x_namakotabesar(a.lokasi_asal) end as kota_asal,
		a.kota_tujuan,
		x_service(a.service) as service,
		CONCAT(id_pengiriman, ' ', 

		ifnull(case when x_dhl(a.id_kustomer) = 'Y' then i.sla else o.sla end,if (x_hubnew(a.id_kota_tujuan) = a.id_kota_tujuan,1,2)) 

			, ' ' ,ifnull(a.manifest_received,DATE_FORMAT(a.pickup_date,"%Y-%m-%d")),' ', a.delivery_by_agent ) as assignment,
		id_pengiriman
	from pengiriman a  
	left join sla_deliv o on o.kota_tujuan =  a.id_kota_tujuan AND o.service = a.service	

	left join sla_deliv_dhl i on i.kota_tujuan =  a.id_kota_tujuan AND i.service = a.service

			left join kota c on c.id_kota=a.lokasi_asal 
			

			left join regencies d on d.id=a.id_kota_asal

	where status != 6 and  status='4' and pickup='Y' and a.service != 6 and (courier_by is null or courier_by =0) 
	and if(id_kota_asal>0,d.province_id in ($u[area_prov]),c.kode_pulau in ($u[area_pulau]))
	and status!='7' and a.service != 4  
	and if(a.id_kota_asal>0,x_inter(a.id_kota_asal),x_intercity(a.lokasi_asal))!='Y' 
	group by a.id_pengiriman
	) temp 
EOT;
		}

		elseif($u['level']=='operator' or $u['level']=='domex'){
		if ($u['id_kustomer'] > 0 ){
			$table = 
<<<EOT
	(
	select 
		airwaybill_kode,
		
		CONCAT(DATE_FORMAT(a.created_date,"%Y-%m-%d"),' ', 
		case 
			when a.service = 1 then ifnull(case when x_dhl(a.id_kustomer) = 'Y' then i.sla else o.sla end,0) 
			when a.service = 2 then ifnull(case when x_dhl(a.id_kustomer) = 'Y' then i.sla else o.sla end,1) 
			else
				ifnull(case when x_dhl(a.id_kustomer) = 'Y' then i.sla else o.sla end,if (x_hubnew(a.id_kota_tujuan) = a.id_kota_tujuan,1,2)) 
			end

			, ' ' ,ifnull(a.manifest_received,a.pickup_date)
			) as created_date,
		
		x_namakustomer(id_kustomer) as nama_kustomer,
		case when lokasi_asal=0 then a.kota_asal else x_namakotabesar(a.lokasi_asal) end as kota_asal,
		a.kota_tujuan,
		x_service(a.service) as service,
		CONCAT(id_pengiriman, ' ', 

		ifnull(case when x_dhl(a.id_kustomer) = 'Y' then i.sla else o.sla end,if (x_hubnew(a.id_kota_tujuan) = a.id_kota_tujuan,1,2)) 

			, ' ' ,ifnull(a.manifest_received,DATE_FORMAT(a.pickup_date,"%Y-%m-%d")),' ', ifnull(a.delivery_by_agent,'39') ) as assignment,
		id_pengiriman
	from pengiriman a  
	left join sla_deliv o on o.kota_tujuan =  a.id_kota_tujuan AND o.service = a.service	

	left join sla_deliv_dhl i on i.kota_tujuan =  a.id_kota_tujuan AND i.service = a.service



	where status != 6 and  pickup='Y' and delivery='N' and (courier_by is null or courier_by =0) and status!='7' and 
	case 
		when  x_inter(a.id_kota_asal) !='Y'  and x_inter(a.id_kota_tujuan) ='Y' then status='2' 

		when a.service = 6 then status = 2
	end
	
	

	 group by a.id_pengiriman
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
		case 
			when a.service = 1 then ifnull(case when x_dhl(a.id_kustomer) = 'Y' then i.sla else o.sla end,0) 
			when a.service = 2 then ifnull(case when x_dhl(a.id_kustomer) = 'Y' then i.sla else o.sla end,1) 
			else
				ifnull(case when x_dhl(a.id_kustomer) = 'Y' then i.sla else o.sla end,if (x_hubnew(a.id_kota_tujuan) = a.id_kota_tujuan,1,2)) 
			end

			, ' ' ,ifnull(a.manifest_received,a.pickup_date)
			) as created_date,
		
		x_namakustomer(id_kustomer) as nama_kustomer,
		case when lokasi_asal=0 then a.kota_asal else x_namakotabesar(a.lokasi_asal) end as kota_asal,
		a.kota_tujuan,
		x_service(a.service) as service,
		CONCAT(id_pengiriman, ' ', 

		ifnull(case when x_dhl(a.id_kustomer) = 'Y' then i.sla else o.sla end,if (x_hubnew(a.id_kota_tujuan) = a.id_kota_tujuan,1,2)) 

			, ' ' ,ifnull(a.manifest_received,DATE_FORMAT(a.pickup_date,"%Y-%m-%d")),' ', ifnull(a.delivery_by_agent,'39') ) as assignment,
		id_pengiriman
	from pengiriman a  
	left join sla_deliv o on o.kota_tujuan =  a.id_kota_tujuan AND o.service = a.service	

	left join sla_deliv_dhl i on i.kota_tujuan =  a.id_kota_tujuan AND i.service = a.service



	where status != 6 and  pickup='Y' and delivery='N' and (courier_by is null or courier_by =0) and status!='7' and 
	case 
		when  x_inter(a.id_kota_asal) !='Y'  and x_inter(a.id_kota_tujuan) ='Y' then status='2' 

		when a.service = 6 then status = 2
	end
	
	

	 group by a.id_pengiriman
	) temp
EOT;
		}
		

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

						$booking_date = $pieces[0];
						$sla_hari = $pieces[1];
						$date_request = $pieces[2];
						$id_pengiriman = $pieces[3];
						
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
						$sla = $tanggal;
						

						$date_now =  date("Y-m-d");

						if($jumlah_hari != 99){
							
							if ($date_now > $sla){

								$created_date = $date_request. ' - '.$sla. '<button class="btn btn-xs btn-danger" title="TERLAMBAT"><i class="fa fa-exclamation-circle"></i> </button>';
							}else{
								$created_date =  $date_request . ' - '.$sla;
							}
						}else{
							$created_date =  $date_request. ' <button data-toggle="modal" data-target="#modal_sla_insert" data-id="'.$id_pengiriman.'" class="btn btn-xs btn-warning custom_sla_insert"><i class="fa fa-plus"></i> </button>';
						}

						return  $created_date;
					}
			),
			array('db'		=> 'nama_kustomer',
					'dt'	=> 2,
					'formatter'	=> function($d,$row){
						return substr($d,0,30);
					}
			),
			array('db'			=> 'kota_asal',
					'dt'		=> 3,
					'formatter' => function($d,$row){
						if($d=='KOTA JAKARTA SELATAN' || $d=='KOTA JAKARTA TIMUR' || $d=='KOTA JAKARTA BARAT' || $d=='KOTA JAKARTA UTARA' || $d=='KOTA JAKARTA PUSAT'){
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
						
							$kota_tujuan = $d;
						
						return '<td>'.strtoupper($kota_tujuan).'</td>';
					}
			),
			array('db'		=> 'service',
					'dt'	=> 5,
					'formatter'	=> function($d,$row){
						

						return $d;
					}
			),
			array(
				  'db'        => 'assignment',
				  'dt'        => 6,
				  'formatter' => function( $d, $row ) {
					$date_now = date("Y-m-d");
					$pieces = explode(' ', $d);

					$sla_hari = $pieces[1];
					$date_request = $pieces[2];

					$delivery_by_agent = $pieces[3];

					$id_pengiriman = $pieces[0];
					
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
						$sla = $tanggal;

					

					$key = "asdf$1011";

					
						if($jumlah_hari != 99){
							if($date_now > $sla){
								$assignment = '<td><center><button class="btn btn-xs btn-primary open_manifest-delivery" id=1 data-id="'.base64_encrypt($id_pengiriman,$key).' 1 '.$sla.'">Assignment</button>

								
								</center></td>';						
							}else{
								$assignment = '<td><center>

								<button class="btn btn-xs btn-primary open_manifest-delivery" id=0 data-id="'.base64_encrypt($id_pengiriman,$key).' 0 '.$sla.'">Assignment</button>
								
								</center></td>';						
							}
						} 
						else{
							$assignment = '<td><center><button class="btn btn-xs btn-primary open_manifest-delivery" id=0 data-id="'.base64_encrypt($id_pengiriman,$key).' 0">Assignment</button>

							

							</center></td>';			

						}
					

					$transit = '<button class="btn btn-xs btn-warning" onclick="window.location.href = \'module/mod_manifest/manifest_action.php?module=manifest&act=backtotransit&id_pengiriman='.$id_pengiriman.'\';"><i class="fa fa-angle-left"></i>transit</button>';	


						$backtoinbound='<button class="btn btn-xs btn-warning" onclick="window.location.href = \'module/mod_manifest/manifest_action.php?module=manifest&act=backtoinbound&id_pengiriman='.$id_pengiriman.'\';"><i class="fa fa-angle-left"></i></button>';
	
					 
					 return $assignment.$transit.$backtoinbound;
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
	
	// manifest delivery assignment
	}elseif($module=='manifest' and $act=='assignment'){
		$pieces = explode(' ', $_POST['id']);

		$id_pengiriman = $pieces[0];
		$sla = $pieces[1];
		$sla_tanggal = $pieces[2];

		$key = "asdf$1011";
		$encrypt = "$pieces[0]";
		$id = base64_decrypt($encrypt,$key);
		
		$query = mysqli_query($con, "SELECT x_inter(id_kota_tujuan) as intercity, id_kota_tujuan as lokasi_tujuan,delivery_by_agent, id_kota_tujuan, id_kustomer,alasan_terlambat,service 

			FROM pengiriman WHERE id_pengiriman='".$id."'");
		$r = mysqli_fetch_assoc($query); 
		
		if($u['level']=='admin' or $u['level']=='operator' or $u['level']=='intercity' or $u['level']=='agent' or $u['level']=='inbound' or $u['level']=='network' or $u['level']=='domex' or $u['level']=='admin_network'){
		echo'
		<form action="'.$aksi.'?module=manifest&act=update" name="modal_popup" method="POST">
				<input type="hidden" name="id" class="form-control" value="'.$id.'" />';
		if($r['intercity']=='Y' || $r['service'] == 6){
			echo'
			<div class="form-group" style="padding-bottom: 5px;">
                <label>Assignment to: </label>
     			<select name="courier_by" class="form-control">';
				$result = mysqli_query($con,"select id_user, namalengkap from user where (level='kurir' or level='driver') AND blokir != 'Y' order by namalengkap");
				while ($row = mysqli_fetch_assoc($result)){
					echo '<option value="'.$row['id_user'].'">'.strtoupper($row['namalengkap']).'</option>';
				}
				echo'
				</select>
			</div>';
		}else{
			echo'
				<label>Assignment to:'.$id.'</label>
     			<select name="courier_by" class="form-control select2" width="100%">';
     				if($r['intercity'] != 'Y' AND $r['id_kota_tujuan'] > 0){
     					$result = mysqli_query($con,"select a.id_user, concat(b.nama_cabang,' ','(',b.alamat1,')',' / ',a.namalengkap) as namalengkap from user a inner join cabang b on b.id_cabang=a.id_cabang inner join regencies c on c.id=b.id_kota_baru 
     						inner join provinces d on d.id=c.province_id 

     						where (a.level='agent' or a.level='kurir_agent') AND a.blokir != 'Y' and a.id_cabang='".$r['delivery_by_agent']."' and b.status='Y' order by b.id_cabang ASC ");
     				}
					while ($row = mysqli_fetch_assoc($result)) {
						echo '<option value="' . $row['id_user'] . '">'.strtoupper($row['namalengkap']).'</option>';
					}
						//echo '<option value="56">Andry Hanoman Logistics</option>';
					echo '
				</select>';
		}
		echo'

		<div class="form-group" style="padding-bottom: 5px;">
               <label>Manifest day:</label>
     		<select name="manifest_day" class="form-control">';
				
				$tanggal = $sla_tanggal;

				while (true) {

				    $cek = $mysqli->query("SELECT 1 FROM libur_nasional 
				                           WHERE tanggal = '$tanggal' 
				                           LIMIT 1");

				    if ($cek->num_rows > 0) {
				        // Kalau tanggal tersebut hari libur → tambah 1 hari
				        $tanggal = date('Y-m-d', strtotime($tanggal . ' +1 day'));
				    } else {
				        // Kalau bukan hari libur → berhenti
				        break;
				    }
				}

				$sla_tanggal_fix = $tanggal;
				
				
				echo '<option value="'.date('Y-m-d',strtotime($sla_tanggal_fix)).'">Max delivery = '.date('Y-m-d',strtotime($sla_tanggal_fix)).'</option>';
				
				
				$plus = mysqli_query($con,"select plus_hari from libur_plus where tanggal_mulai <= CURDATE() and tanggal_akhir >= CURDATE() ");

								        while ($p = mysqli_fetch_assoc($plus)) {
											echo '<option value="'.date('Y-m-d',strtotime($sla_tanggal.'+'.$p['plus_hari'].' day'))	.'">Max Deliv +'.$p['plus_hari'].' = '.date('Y-m-d',strtotime($sla_tanggal.'+'.$p['plus_hari'].' day')).'</option>';
										}
				
			
			echo'
			</select>
		</div>
		';
		/*<div class="form-group" style="padding-bottom: 5px;">
                <label>Manifest for next day '.date('Y-m-d',strtotime(' +1 day')).'</label>
                <input type="checkbox" name="nextday" value="1"> *abaikan jika tidak  

                
        </div>*/
        if($sla==1){
				if ($r['alasan_terlambat'] != ''){
					echo '<label>ALASAN TERLAMBAT = '.$r['alasan_terlambat'].'</label>';
				}else{
					echo '<div class="form-group" style="padding-bottom: 5px;">
	                	<label>ALASAN TERLAMBAT:</label>
	                	<input type="text" class="form-control style="width: 100%;" name="reason" required>						
	            	</div>';	
				}
				

	         }
        echo '


        <input type="hidden" name="m_date" class="form-control" value="'.date('Y-m-d',strtotime(' +1 day')).'" />

			<div class="modal-footer">
				<button class="btn btn-success" type="submit">Confirm</button>
				<button type="reset" class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Cancel</button>
			</div>
		</form>';
		}
		
	// update delivery
	}elseif ($module=='manifest' AND $act=='update'){

		//library buat kode manifest otomatis
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

		//cek SET TANGGAL

		$datetime = new DateTime('tomorrow');
		$tomorrow =  $datetime->format('Y-m-d');
		
		$tgl_jam = date("Y-m-d H:i:s"); 
		if ($reason != ''){
			$reason = $reason;
		}
		else{
			$reason = '';
		}

		$format_awal = "Y-m-d 19:00:00";
		$batas_awal = date($format_awal);
		$format_ahir = "Y-m-d 23:59:59";
		$batas_ahir = date($format_ahir);

		$nextday = mysqli_real_escape_string($con, $_POST['nextday']);
		$set_tgl = mysqli_real_escape_string($con, $_POST['manifest_day']);

		/*if ($tgl_jam >= $batas_awal && $tgl_jam <= $batas_ahir) {
			$set_tgl = $tomorrow;
		}
		else{
			if($nextday == 1){
				$set_tgl = $tomorrow;
			}else{
				$set_tgl = $tgl_sekarang;
			}

		}*/

		
		
		if($u['level']=='admin' || $u['level']=='operator'){
			mysqli_query($con,"UPDATE pengiriman set courier_by='" . $_POST["courier_by"] . "', courier_date='$set_tgl', status='5', alasan_terlambat='$reason', manifest_delivery_date=now(), manifest_delivery_by='$u[id_user]' WHERE id_pengiriman='" . $_POST["id"] . "'");
			$m_delivery_by = $_POST["courier_by"];
		}else{
			mysqli_query($con,"UPDATE pengiriman set courier_by='" . $_POST["courier_by"] . "', courier_date='$set_tgl', status='5', alasan_terlambat='$reason', manifest_delivery_date=now(), manifest_delivery_by='$u[id_user]' WHERE id_pengiriman='" . $_POST["id"] . "'");
			$m_delivery_by = $_POST["courier_by"];
		}



		$cek = mysqli_query($con, "SELECT * FROM manifest WHERE tanggal='".$set_tgl."' AND manifest_delivery_by ='".$m_delivery_by."' AND tipe_manifest ='delivery'");
		$ketemu	= mysqli_num_rows($cek);
		$r = mysqli_fetch_assoc($cek);
		
		if($ketemu==0){
				mysqli_query($con, "INSERT INTO manifest (kode_manifest,tanggal,manifest_delivery_by,tipe_manifest,created_date,created_by) VALUES ('$nextNoTransaksi','$set_tgl','$m_delivery_by','delivery','$tgl_jam','$u[id_user]')");
				$id_manifest = mysqli_insert_id($con);
				// insert to manifest detail
				mysqli_query($con, "insert into manifest_detail (id_manifest,id_pengiriman) values ('$id_manifest','". $_POST["id"] ."')");
				// update pengiriman
				//mysqli_query($con, "update pengiriman set status='3', berat_aktual='$berat_aktual', collie='$collie', pcs='$pcs', packing_kayu='$packing_kayu', transit='N' where id_pengiriman='$_POST[id]'");
		}	
		else{
			mysqli_query($con, "insert into manifest_detail (id_manifest,id_pengiriman) values ('$r[id_manifest]','". $_POST["id"] ."')");
		}
		
		header('location:../../main.php?module='.$module);
	}
	elseif ($module=='manifest' AND $act=='test') {
		$datetime = new DateTime('tomorrow');
		$tomorrow =  $datetime->format('Y-m-d');
		
		$tgl_jam = date("Y-m-d H:i:s"); 

		$format_awal = "Y-m-d 16:00:00";
		$batas_awal = date($format_awal);
		$format_ahir = "Y-m-d 23:59:59";
		$batas_ahir = date($format_ahir);

		if ($tgl_jam >= $batas_awal && $tgl_jam <= $batas_ahir) {
			$set_tgl = $tomorrow;
		}
		else{
			$set_tgl = $tgl_sekarang;
		}
		
		
			echo "UPDATE pengiriman set courier_by='" . $_POST["courier_by"] . "', courier_date='$set_tgl', status='5' WHERE id_pengiriman='" . $_POST["id"] . "'";
		



		echo "SELECT * FROM manifest WHERE tanggal='".$set_tgl."' AND manifest_delivery_by ='".$m_delivery_by."' AND tipe_manifest ='delivery'";
		
		
				echo "INSERT INTO manifest (kode_manifest,tanggal,manifest_delivery_by,tipe_manifest,created_date,created_by) VALUES ('$nextNoTransaksi','$set_tgl','$m_delivery_by','delivery','$tgl_jam','$u[id_user]')";
		
	}

	elseif ($module=='manifest' AND $act=='scanawb'){


		
		$awb = $_POST['kode_awb'];
		$id_kurir = $_POST['kurir'];

		
		$result = mysqli_query($con, "SELECT id_pengiriman,kota_asal,service,kota_tujuan,status,transit,x_inter(id_kota_asal) as inter_asal,x_inter(id_kota_tujuan) as inter_tujuan ,x_namakustomer(id_kustomer) as nama_kustomer,x_service(service) as nama_service,pickup_date,manifest_received,updated_by_operational, komoditi from pengiriman where airwaybill_kode='$awb'");
		
		$ketemu	= mysqli_num_rows($result);
		if($ketemu > 0){

			$row = mysqli_fetch_assoc($result);
			if($row['updated_by_operational'] > 0 ||  $row['komoditi'] == 2){
				if($row['status'] == 4 || ($row['status'] == 2 && $row['inter_asal'] == 'Y' && $row['inter_tujuan'] == 'Y' ) || ($row['status'] == 2 && $row['service'] == '6' ) ){

						if($row['status'] == 4){
							$tgl_awal = $row['manifest_received'];
						}else{
							$tgl_awal = $row['pickup_date'];
						}

						if($row['service'] == 1){
							$tgl = date('Y-m-d',strtotime($tgl_awal));		
						}
						elseif($row['service'] == 2){
							$tgl = date('Y-m-d',strtotime($tgl_awal.'+ 1 day'));		
						}
						else{
							$tgl = date('Y-m-d',strtotime($tgl_awal.'+ 2 day'));
						}

						$now = date('Y-m-d');

						/*if ( $row['service'] < 3 && $now > $tgl && $u['level'] != 'admin' ) {
							
							$succes = array("success"=>0,"message"=> "AIRWAYBILL $awb Tidak bisa di proses,manifest urgent harus dihari yang sama dari max sla delivery");
							echo json_encode($succes);

						}elseif($row['service'] > 2 && $now >= $tgl && $u['level'] != 'admin'){
							$succes = array("success"=>0,"message"=> "AIRWAYBILL $awb Tidak bisa di proses,manifest reg dan trucking harus dihari sebelumnya dari batas terakhir max sla delivery".$tgl." & ".$now);
							echo json_encode($succes);
						}else{*/

							mysqli_query($con, "update pengiriman set status='5', courier_by='$id_kurir',courier_date='".$tgl."', manifest_delivery_date=now(), manifest_delivery_by='$u[id_user]' where airwaybill_kode='$awb'");
					

					
				
							echo json_encode(array("success"=>1,"message"=> "AIRWAYBILL $awb BERHASIL DI MANIFEST", "pengiriman" => $row));

							mysqli_query($con, "insert into update_status_kiriman (id,id_pengiriman,status_kiriman,kode_pengiriman,kurir_by, status_by,created_by,created_date) values ('','$row[id_pengiriman]','Paket sudah ditugaskan ke kurir, segera dibawa menuju lokasi anda',5,$id_kurir,'$u[id_user]','$u[id_user]',now() ) ");
						//}

					

				}else{
					$succes = array("success"=>0,"message"=> "AIRWAYBILL $awb Belum dipickup atau belum sampai kota tujuan");
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
	elseif ($module=='manifest' AND $act=='backtotransit') {
		$id_pengiriman =  $_GET['id_pengiriman'];
		
		
		echo "update pengiriman set status='2', manifest='N', transit='Y', received_transit='Y',delivery_by_agent=null,delivery='N', delivery_name=null, courier_date=null, courier_by=null where id_pengiriman='$id_pengiriman'";

		

		mysqli_query($con, "update pengiriman set status='2', manifest='N', transit='Y', received_transit='Y',delivery_by_agent=null,delivery='N', delivery_name=null, courier_date=null, courier_by=null where id_pengiriman='$id_pengiriman'");

		
		header('location:../../main.php?module='.$module);
	}
	elseif ($module=='manifest' AND $act=='backtoinbound') {
		$id_pengiriman =  $_GET['id_pengiriman'];
		
		
		echo "update pengiriman set status='3', manifest='N' where id_pengiriman='$id_pengiriman'";

		

		mysqli_query($con, "update pengiriman set status='3', manifest='N' where id_pengiriman='$id_pengiriman'");

		
		header('location:../../main.php?module='.$module);
	}
	
}
?>