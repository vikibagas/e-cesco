<?php
if(!isset($_SESSION)){
    session_start();
}

if (empty($_SESSION['username']) AND empty($_SESSION['password'])){
	header('location:../application/index.php');
}
else{
	include "../../config/connection.php";
	include "../../config/db_connect.php";
	include "../../config/library.php";
	include "../../config/encrypt.php";
	
	$module	= !empty($_GET['module']) ? $_GET['module'] : '';
	$act	= !empty($_GET['act']) ? $_GET['act'] : '';
	
	$key = 'asdf$1011';
	$encrypt = $_SESSION['id_user'];
	$id = base64_decrypt($encrypt,$key);
	$useraktif = mysqli_query($con, 'select * from user where id_user="'.$id.'"');
	$u = mysqli_fetch_assoc($useraktif);
	
	// data
	if($module=='costing-outbound' and $act=='data'){
		if($u['level']=='admin' || $u['level']=='finance'){
		$table = 
<<<EOT
	(
	select 
	a.tanggal, 
		x_user(a.manifest_outbound_by) as created_by, 
		case when x_intercity(d.id_kota)='Y' then 'JAKARTA' else d.nama_cabang end as manifest_from, 
		case when x_intercity(e.id_kota)='Y' then 'JAKARTA' else e.nama_cabang end as manifest_to, 
		case when a.manifest_via='1' then 'UDARA' when a.manifest_via='2' then 'DARAT' when a.manifest_via='3' then 'LAUT' when a.manifest_via='4' then 'TOTO' when a.manifest_via='5' then 'HERONA' when a.manifest_via='6' then 'BES' end as manifest_via, 
		count(c.id_pengiriman) as jumlah, 
		sum(if(c.berat_aktual > 0, c.berat_aktual, c.berat_barang)) as berat, 
		concat("Rp. ", format(round(sum(coalesce(c.costing_outbound_fee,0)) + sum(coalesce(c.costing_outbound_parking,0)) + sum(coalesce(c.costing_outbound_toll,0)) + sum(coalesce(c.costing_outbound_menpower,0)) + sum(coalesce(c.costing_outbound_smu,0)) + sum(coalesce(c.costing_outbound_handling,0)),0),0)) as total_cost,
		a.transit, 
		a.id_manifest 
	from manifest a 
	left join manifest_detail b on b.id_manifest=a.id_manifest 
	left join pengiriman c on c.id_pengiriman=b.id_pengiriman 
	left join cabang d on d.id_cabang=a.manifest_from 
	left join cabang e on e.id_cabang=a.manifest_to

	where   (a.manifest_from  in (39,41) and a.manifest_to not in (39,41) 
	)
 	
	group by a.id_manifest
	order by tanggal desc, a.id_manifest desc limit 2000
	) temp
EOT;
		}elseif($u['level']=='operator'){
		$table = 
<<<EOT
	(
	select 
	a.tanggal, 
		x_user(a.created_by) as created_by, 
		case when x_intercity(d.id_kota)='Y' then 'JAKARTA' else d.nama_cabang end as manifest_from, 
		case when x_intercity(e.id_kota)='Y' then 'JAKARTA' else e.nama_cabang end as manifest_to, 
		case when a.manifest_via='1' then 'UDARA' when a.manifest_via='2' then 'DARAT' when a.manifest_via='3' then 'LAUT' when a.manifest_via='4' then 'TOTO' when a.manifest_via='5' then 'HERONA' when a.manifest_via='6' then 'BES' end as manifest_via, 
		count(c.id_pengiriman) as jumlah, 
		sum(coalesce(c.berat_aktual,c.berat_barang)) as berat, 
		format(round(sum(coalesce(c.costing_outbound_fee,0)) + sum(coalesce(c.costing_outbound_parking,0)) + sum(coalesce(c.costing_outbound_toll,0)) + sum(coalesce(c.costing_outbound_menpower,0)) + sum(coalesce(c.costing_outbound_smu,0)) + sum(coalesce(c.costing_outbound_handling,0)),0),0) as total_cost,
		a.transit, 
		a.id_manifest 
	from manifest a 
	inner join manifest_detail b on b.id_manifest=a.id_manifest 
	inner join pengiriman c on c.id_pengiriman=b.id_pengiriman 
	inner join cabang d on d.id_cabang=a.manifest_from 
	inner join cabang e on e.id_cabang=a.manifest_to
	where x_intercity(c.lokasi_asal)='Y'
	group by a.id_manifest
	order by tanggal desc, a.id_manifest desc
	) temp
EOT;
		}elseif($u['level']=='domex'){
		$table = 
<<<EOT
	(
	select 
	a.tanggal, 
		x_user(a.created_by) as created_by, 
		case when x_intercity(d.id_kota)='Y' then 'JAKARTA' else d.nama_cabang end as manifest_from, 
		case when x_intercity(e.id_kota)='Y' then 'JAKARTA' else e.nama_cabang end as manifest_to, 
		case when a.manifest_via='1' then 'UDARA' when a.manifest_via='2' then 'DARAT' when a.manifest_via='3' then 'LAUT' when a.manifest_via='4' then 'TOTO' when a.manifest_via='5' then 'HERONA' when a.manifest_via='6' then 'BES' end as manifest_via, 
		count(c.id_pengiriman) as jumlah, 
		sum(coalesce(c.berat_aktual,c.berat_barang)) as berat,
		format(round(sum(coalesce(c.costing_outbound_fee,0)) + sum(coalesce(c.costing_outbound_parking,0)) + sum(coalesce(c.costing_outbound_toll,0)) + sum(coalesce(c.costing_outbound_menpower,0)) + sum(coalesce(c.costing_outbound_smu,0)) + sum(coalesce(c.costing_outbound_handling,0)),0),0) as total_cost,
		a.transit, 
		a.id_manifest 
	from manifest a 
	inner join manifest_detail b on b.id_manifest=a.id_manifest 
	inner join pengiriman c on c.id_pengiriman=b.id_pengiriman 
	inner join cabang d on d.id_cabang=a.manifest_from 
	inner join cabang e on e.id_cabang=a.manifest_to
	where x_intercity(c.lokasi_tujuan)!='Y'
	group by a.id_manifest
	order by tanggal desc, a.id_manifest desc
	) temp
EOT;
		}elseif($u['level']=='network'){
		$table = 
<<<EOT
	(
	select
		a.tanggal,
		x_user(a.created_by) as created_by,
		case when x_intercity(b.id_kota)='Y' then 'JAKARTA' else b.nama_cabang end as manifest_from,
		case when x_intercity(c.id_kota)='Y' then 'JAKARTA' else c.nama_cabang end as manifest_to,
		case when a.manifest_via='1' then 'UDARA' when a.manifest_via='2' then 'DARAT' when a.manifest_via='3' then 'LAUT' when a.manifest_via='4' then 'TOTO' when a.manifest_via='5' then 'HERONA' when a.manifest_via='6' then 'BES' end as manifest_via,
		count(d.id_pengiriman) as jumlah,
		sum(coalesce(d.berat_aktual,d.berat_barang)) as berat,
		format(round(sum(coalesce(c.costing_outbound_fee,0)) + sum(coalesce(c.costing_outbound_parking,0)) + sum(coalesce(c.costing_outbound_toll,0)) + sum(coalesce(c.costing_outbound_menpower,0)) + sum(coalesce(c.costing_outbound_smu,0)) + sum(coalesce(c.costing_outbound_handling,0)),0),0) as total_cost,
		a.id_manifest
	from manifest a 
	inner join cabang b on b.id_cabang=a.manifest_from 
	inner join cabang c on c.id_cabang=a.manifest_to 
	inner join pengiriman d on d.id_manifest=a.id_manifest 
	where d.lokasi_asal in($u[area]) 
	group by a.id_manifest order by a.id_manifest desc
	) temp
EOT;
		}elseif($u['level']=='agent'){
		$table = 
<<<EOT
	(
	select 
	a.tanggal, 
		x_user(a.created_by) as created_by, 
		case when x_intercity(d.id_kota)='Y' then 'JAKARTA' else d.nama_cabang end as manifest_from, 
		case when x_intercity(e.id_kota)='Y' then 'JAKARTA' else e.nama_cabang end as manifest_to, 
		case when a.manifest_via='1' then 'UDARA' when a.manifest_via='2' then 'DARAT' when a.manifest_via='3' then 'LAUT' when a.manifest_via='4' then 'TOTO' when a.manifest_via='5' then 'HERONA' when a.manifest_via='6' then 'BES' end as manifest_via, 
		count(c.id_pengiriman) as jumlah, 
		sum(if(c.berat_aktual > 0, c.berat_aktual, c.berat_barang)) as berat, 
		concat("Rp. ", format(round(sum(coalesce(c.costing_outbound_fee,0)) + sum(coalesce(c.costing_outbound_parking,0)) + sum(coalesce(c.costing_outbound_toll,0)) + sum(coalesce(c.costing_outbound_menpower,0)) + sum(coalesce(c.costing_outbound_smu,0)) + sum(coalesce(c.costing_outbound_handling,0)),0),0)) as total_cost,
		a.transit, 
		a.id_manifest 
	from manifest a 
	inner join manifest_detail b on b.id_manifest=a.id_manifest 
	inner join pengiriman c on c.id_pengiriman=b.id_pengiriman 
	inner join cabang d on d.id_cabang=a.manifest_from 
	inner join cabang e on e.id_cabang=a.manifest_to

	where   (
	a.manifest_from  = $u[id_cabang] 
	and a.manifest_to !=  $u[id_cabang]
	)
 	
	group by a.id_manifest
	order by tanggal desc, a.id_manifest desc
	) temp
EOT;
		}

		$primaryKey = 'id_manifest';

		$columns = array(
			array(
					'db'        => 'tanggal',
					'dt'        => 0,
					'formatter' => function( $d, $row ) {
						return date( 'd-m-Y', strtotime($d));
					}
			),
			array(
					'db'		=> 'created_by',
					'dt'		=> 1,
					'formatter'	=> function($d,$row){
						return '<td>'.strtoupper($d).'</td>';
					}
			),
			array( 'db' => 'manifest_from', 'dt'=> 2 ),
			array( 'db' => 'manifest_to', 'dt'=> 3 ),
			array( 'db' => 'manifest_via', 'dt'=> 4 ),
			array( 'db' => 'jumlah', 'dt'=> 5 ),
			array( 'db' => 'berat', 'dt'=> 6 ),			
			array( 'db' => 'total_cost', 'dt' => 7 ),
			array('db'			=> 'transit',
					'dt'		=> 8,
					'formatter'	=> function($d,$row){
						if($d=='Y'){
							$transit = '<i class="fa fa-exchange"></i> Transit';
						}else{
							$transit = '';
						}
						return '<td>'.$transit.'</td>';
					}
			),
			array(
				  'db'        => 'id_manifest',
				  'dt'        => 9,
				  'formatter' => function( $d, $row ) {
					$aksi="module/mod_costing-outbound/costing-outbound_action.php";
					$key = "asdf$1011";
					return '<td><center><a class="btn btn-xs btn-primary" target="_blank" href="main.php?module=costing-outbound&act=view&id='.base64_encrypt($d,$key).'"><i class="fa fa-search"></i> View</a></center></td>';
				  }
			)

		);


		require( '../../config/ssp.class.php' );

        $column0 = array(
            array( 'db' => 'cnt',  'dt' => 0 )
        );
        $cekBiaya0 = "(select count(1) as cnt from (select * from ".$table.") x where total_cost = 'Rp. 0') tmp";

        $total0 = SSP::simple( NULL, $sql_details, $cekBiaya0, $primaryKey, $column0 );

        $data =	SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns );

        $data['total0'] = $total0['data'][0][0];

        echo json_encode($data);
	
	// update
	}

	elseif($module == 'costing-outbound' and $act == 'calculate'){
		
		if (mysqli_connect_errno())
		{
		  echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}
		/*else{
			echo 'berhasil';
		}*/

		$tgloutbound 		= $mysqli->real_escape_string($_POST['tgloutbound']);
		$manifest_by 		= $mysqli->real_escape_string($_POST['manifest_by']);
		$id_manifest 		= $mysqli->real_escape_string($_POST['id_manifest']);
		$outbound_cost 		= $mysqli->real_escape_string($_POST['outbound_cost']);
		$parking_fee 		= $mysqli->real_escape_string($_POST['parking_fee']);
		$toll_fee 			= $mysqli->real_escape_string($_POST['toll_fee']);
		$smu_cost 			= $mysqli->real_escape_string($_POST['smu_cost']);
		$handling_cost 			= $mysqli->real_escape_string($_POST['handling_cost']);
		$menpower_cost 			= $mysqli->real_escape_string($_POST['menpower_cost']);
		//$id_courier_by 		= $mysqli->real_escape_string($_POST['courier_by']);
		
		// hitung jumlah airwaybill
		
		$count_airwaybill = $mysqli->query("select count(*) as qty, sum(if(a.berat_aktual > 0, a.berat_aktual, a.berat_barang)) as sum_berat,sum(costing_outbound_fee) as sum_outbound FROM pengiriman a inner join manifest_detail b on a.id_pengiriman = b.id_pengiriman where b.id_manifest='$id_manifest'");
		//echo "<br> select count(*) as qty from pengiriman where date(courier_date)='$tgloutbound' and courier_by='$costing_courier_by' <br>";
		$ca = $count_airwaybill->fetch_assoc();
		//header('location:../../main.php?module='.$module.'&error_count='.$ca['qty'].'&outbound_cost='.$outbound_cost.'&toll_fee='.$toll_fee);
		//cek erro kalo kosong
		//echo 'outbound  ='.$ca['sum_outbound'] . 'toll =' . $toll_fee .'qty = '. $ca['qty'].', berat = '. $ca['sum_berat']. 'manifest =' .$id_manifest;
		//echo "SELECT a.id_pengiriman, if(a.berat_aktual > 0, a.berat_aktual, a.berat_barang) as berat_barang  FROM pengiriman a inner join manifest_detail b on a.id_pengiriman = b.id_pengiriman where b.id_manifest='$id_manifest'";
		//return false;
		//header('location:../../main.php?module='.$module.'&error_count='.$ca['qty'].'&outbound_cost='.$outbound_cost.'&toll_fee='.$toll_fee);
		
		if ($outbound_cost == 0 || $toll_fee == 0 || $ca['qty'] == 0)
		{

			//echo "select count(*) as qty from pengiriman where date(pickup_date)='$tglpickup' and courier_by='$id_courier_by'";
			//return false;
			header('location:../../main.php?module='.$module.'&error_count='.$ca['qty'].'&outbound_cost='.$outbound_cost.'&toll_fee='.$toll_fee);
		}

		
		
		//$ca = $count_airwaybill->fetch_assoc();
		//echo "UPDATE pengiriman a inner join manifest_detail b on a.id_pengiriman = b.id_pengiriman set a.costing_outbound='Y', a.costing_outbound_by='$id', a.costing_outbound_fee='$avg_outbound', a.costing_outbound_toll='$avg_toll',a.costing_outbound_parking='$avg_parking',a.costing_outbound_smu='$avg_smu',a.costing_outbound_handling='$avg_handling',a.costing_outbound_menpower='$avg_menpower' where b.id_manifest='$id_manifest'";
		//return false;	
		$q = $mysqli->query("SELECT a.id_pengiriman, if(a.berat_aktual > 0, a.berat_aktual, a.berat_barang) as berat_barang  FROM pengiriman a inner join manifest_detail b on a.id_pengiriman = b.id_pengiriman where b.id_manifest='$id_manifest'");

		while($r = $q->fetch_assoc()){
			
			$avg_outbound_calc = $r['berat_barang']/$ca['sum_berat']*$outbound_cost;
			$avg_toll_calc = $r['berat_barang']/$ca['sum_berat']*$toll_fee;
			$avg_parking_calc = $r['berat_barang']/$ca['sum_berat']*$parking_fee;			
			$avg_smu_calc = $r['berat_barang']/$ca['sum_berat']*$smu_cost;
			$avg_handling_calc = $r['berat_barang']/$ca['sum_berat']*$handling_cost;
			$avg_menpower_calc = $r['berat_barang']/$ca['sum_berat']*$menpower_cost;

			//echo '<br>'.$r['id_pengiriman'].'<br>'.$r['berat_barang']. '<br>'.$ca['sum_berat'].'<br>'.$avg_outbound_calc.'<br>'.$avg_toll_calc.'<br>'.$outbound_cost.'<br>';
			//echo "UPDATE pengiriman a inner join manifest_detail b on a.id_pengiriman = b.id_pengiriman set a.costing_outbound='Y', a.costing_outbound_by='$id', a.costing_outbound_fee='$avg_outbound_calc', a.costing_outbound_toll='$avg_toll_calc',a.costing_outbound_parking='$avg_parking_calc',a.costing_outbound_smu='$avg_smu_calc',a.costing_outbound_handling='$avg_handling_calc',a.costing_outbound_menpower='$avg_menpower_calc' where a.id_pengiriman=$r[id_pengiriman] <br>";
			$result = mysqli_query($con, "UPDATE pengiriman a inner join manifest_detail b on a.id_pengiriman = b.id_pengiriman set a.costing_outbound='Y', a.costing_outbound_by='$id', a.costing_outbound_fee='$avg_outbound_calc', a.costing_outbound_toll='$avg_toll_calc',a.costing_outbound_parking='$avg_parking_calc',a.costing_outbound_smu='$avg_smu_calc',a.costing_outbound_handling='$avg_handling_calc',a.costing_outbound_menpower='$avg_menpower_calc' where a.id_pengiriman=$r[id_pengiriman]");
		}
		//return false;
		
		
		if($result){
			header('Location: ' . $_SERVER['HTTP_REFERER']);
			//echo $result;
			//echo "success UPDATE pengiriman set costing_pickup='Y', costing_courier_by='$id_courier_by', costing_pickup_fee='$avg_pickup', costing_pickup_toll='$avg_toll' where date(pickup_date)='$tglpickup' and courier_by='$id_courier_by'";
		}else{
			echo "failed";
		}
					
	}
	elseif($module=='costing-outbound' and $act=='save'){
		
		$id_manifest 		= $_GET['idmanifest'];
		//echo  $tgl .' - '. $id_outbound_by;
		//echo "UPDATE  pengiriman a inner join manifest_detail b on a.id_pengiriman = b.id_pengiriman set a.costing_outbound_save='Y' where b.id_manifest='$id_manifest' ";
		//return false;
		mysqli_query($con, "UPDATE  pengiriman a inner join manifest_detail b on a.id_pengiriman = b.id_pengiriman set a.costing_outbound_save='Y' where b.id_manifest='$id_manifest' ");
		header('Location: ' . $_SERVER['HTTP_REFERER']);
	}
	elseif($module=='costing-outbound' and $act=='costingparking'){
		$id_pengiriman = $mysqli->real_escape_string($_POST['id']);
		$parking_fee = $mysqli->real_escape_string($_POST['parking_fee']);
		//echo $id_pengiriman . 'and' .$parking_fee;
		//return false;
		

		$result = mysqli_query($con, "UPDATE pengiriman set costing_outbound_parking='$parking_fee' where id_pengiriman='$id_pengiriman'");
		//echo "UPDATE pengiriman set costing_pickup_parking='$parking_fee', where id='$id_pengiriman'";
		header('Location: ' . $_SERVER['HTTP_REFERER']);
	}

	elseif($module=='costing-outbound' and $act=='costing_men_power'){
		$id_pengiriman = $mysqli->real_escape_string($_POST['id']);
		$costing_men_power = $mysqli->real_escape_string($_POST['men_power']);
		//echo $id_pengiriman . 'and' .$costing_men_power;
		//return false;

		$result = mysqli_query($con, "UPDATE pengiriman set costing_outbound_menpower='$costing_men_power' where id_pengiriman='$id_pengiriman'");
		//echo "UPDATE pengiriman set costing_pickup_parking='$parking_fee', where id='$id_pengiriman'";
		header('Location: ' . $_SERVER['HTTP_REFERER']);
	}

	elseif($module=='costing-outbound' and $act=='searchawb'){
		$q=mysqli_real_escape_string($con,$_POST['q']);

		$query=mysqli_query($con,"select a.id_manifest,a.id_pengiriman,b.airwaybill_kode from manifest_detail a 
			inner join pengiriman b on a.id_pengiriman  = b.id_pengiriman 
			inner JOIN manifest c ON a.id_manifest = c.id_manifest		
		where b.airwaybill_kode LIKE '%".$q."%' and c.inbound='N' AND c.tipe_manifest is null");
		// select a.id_manifest,a.id_pengiriman,b.airwaybill_kode from manifest_detail a inner join pengiriman b on a.id_pengiriman = b.id_pengiriman inner JOIN manifest c ON a.id_manifest = c.id_manifest where b.airwaybill_kode LIKE '%001-00185478%' and c.inbound='N' AND c.tipe_manifest is null
		$row=mysqli_num_rows($query);

		if ($row > 0){
			while ($data=mysqli_fetch_assoc($query)){
				$key = "asdf$1011";
					
				echo '
				<td>'.$data['airwaybill_kode'].'	</td>

				<td><center><a class="btn btn-xs btn-primary" target="_blank" href="?module=costing-outbound&act=view&id='.base64_encrypt($data[id_manifest],$key).'"><i class="fa fa-search"></i> View</a></center></td>
				'; 
			}
		}
		else{
			echo'
			<td colspan="2">
				<center><b>AIRWAYBILL NOT FOUND</b></center>
			</td>'; 
		}
	}
	elseif($module=='costing-outbound' and $act=='delete'){
		$key = "asdf$1011";
		$encrypt = "$_GET[id]";
		$id = base64_decrypt($encrypt,$key);
		$id_manifest_detail = $_GET['id_manifest_detail'];
		//echo "delete from manifest_detail where id_manifest_detail='$id_manifest_detail'";	
		mysqli_query($con, "delete from manifest_detail where id_manifest_detail='$id_manifest_detail'");
		header('Location: ' . $_SERVER['HTTP_REFERER']);
	}
}
?>