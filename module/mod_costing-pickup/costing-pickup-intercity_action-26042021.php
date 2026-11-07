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
	if($module=='costing-pickup-intercity' and $act=='data'){
		if($u['level']=='admin' || $u['tambahan_admin'] == '1' || $u['level']=='finance'){
		$table = 
/*<<<EOT
	(
	SELECT
		date(a.pickup_date) AS pickup,
		b.namalengkap,
		count(a.id_pengiriman) AS jml,
		SUM(coalesce(a.berat_aktual,0)) AS berat,
		SUM(coalesce(a.collie,0)) AS collie,
		SUM(coalesce(a.costing_pickup_fee,0)) AS cost_pickup,
		sum(coalesce(a.costing_pickup_parking,0)) AS parking_fee,
		CONCAT(a.pickup_by,'-', DATE_FORMAT(a.pickup_date,'%Y-%m-%d')) AS pickup_by,
		a.id_pengiriman
	FROM pengiriman a
	INNER JOIN user b on b.id_user=a.costing_pickup_by
	WHERE a.costing_pickup='Y'
	GROUP BY DATE(pickup_date), costing_pickup_by
	) temp*/

	//and x_intercity(a.lokasi_tujuan)='Y'
<<<EOT
	(	
	SELECT
		DATE_FORMAT(a.pickup_date,'%Y-%m-%d') as pickup,
		b.namalengkap,
		count(a.id_pengiriman) AS jml,
		sum(if(a.berat_aktual > 0, a.berat_aktual, a.berat_barang)) as berat,
		SUM(coalesce(a.collie,0)) AS collie,		
		/*concat("Rp. ", format(round(sum(coalesce(a.costing_pickup_fee,0)) + sum(coalesce(a.costing_pickup_parking,0)) + sum(coalesce(a.costing_pickup_toll,0)) + sum(coalesce(a.biaya_packing,0)) + sum(coalesce(a.costing_men_power,0)),0),0)) as total_cost,*/
		round(sum(coalesce(a.costing_pickup_fee,0)) + sum(coalesce(a.costing_pickup_parking,0)) + sum(coalesce(a.costing_pickup_toll,0)) + sum(coalesce(a.biaya_packing,0)) + sum(coalesce(a.costing_men_power,0)),0) as total_cost,
		CONCAT(a.pickup_by,'-', DATE_FORMAT(a.pickup_date,'%Y-%m-%d')) AS pickup_by,
		a.id_pengiriman 
	FROM pengiriman a
	inner join user b on b.id_user=a.pickup_by where a.pickup='Y' and a.pickup_by is not null	 
	and b.id_cabang in (11,16,39,41)
	group by DATE_FORMAT(a.pickup_date,'%y-%m-%d'), a.pickup_by
	) temp
EOT;
} elseif ($u['level'] == 'agent'){
	$table = 
<<<EOT
	(
	SELECT 
		DATE_FORMAT(a.pickup_date,'%Y-%m-%d') as pickup,
		b.namalengkap,
		count(a.id_pengiriman) AS jml,
		sum(if(a.berat_aktual > 0, a.berat_aktual, a.berat_barang)) as berat,
		SUM(coalesce(a.collie,0)) AS collie,		
		/*concat("Rp. ", format(round(sum(coalesce(a.costing_pickup_fee,0)) + sum(coalesce(a.costing_pickup_parking,0)) + sum(coalesce(a.costing_pickup_toll,0)) + sum(coalesce(a.biaya_packing,0)) + sum(coalesce(a.costing_men_power,0)),0),0)) as total_cost,*/
		round(sum(coalesce(a.costing_pickup_fee,0)) + sum(coalesce(a.costing_pickup_parking,0)) + sum(coalesce(a.costing_pickup_toll,0)) + sum(coalesce(a.biaya_packing,0)) + sum(coalesce(a.costing_men_power,0)),0) as total_cost,
		CONCAT(a.pickup_by,'-', DATE_FORMAT(a.pickup_date,'%Y-%m-%d')) AS pickup_by,
		a.id_pengiriman
	FROM pengiriman a
	inner join user b on b.id_user=a.pickup_by where a.pickup='Y' and a.pickup_by is not null	 
	and pickup_by_agent = $u[id_cabang]
	group by DATE_FORMAT(a.pickup_date,'%y-%m-%d'), a.pickup_by
	) temp
EOT;
}

//echo $table;
		$primaryKey = 'id_pengiriman';

        $columns = array(
			array( 'db' => 'pickup',  'dt' => 0 ),
			array(
					'db'        => 'pickup',
					'dt'        => 0,
					'formatter' => function( $d, $row ) {
						return date( 'd-m-Y', strtotime($d));
					}
			),
			array( 'db' => 'namalengkap', 'dt'=> 1 ),
			array(
					'db'		=> 'namalengkap',
					'dt'		=> 1,
					'formatter'	=> function($d,$row){
						return '<td>'.strtoupper($d).'</td>';
					}
			),
			array( 'db' => 'jml', 'dt'=> 2 ),
			array( 'db' => 'berat', 'dt'=> 3 ),
			array( 'db' => 'collie', 'dt' => 4 ),			
			array( 'db' => 'total_cost', 'dt' => 5,
                    'formatter' => function( $d, $row ) {

                        $primaryKey = 'id_pengiriman';
                        $columnD = array(
                            array( 'db' => 'total_diskon',  'dt' => 0 )
                        );

                        $pieces = explode('-', $row['pickup_by']);
                        $pickup_by = $pieces[0];
                        $pickup_date = implode('-', array_slice($pieces, 1, 3));

                        $cekDiskon = "(
                            select coalesce(sum(diskon),0) as total_diskon from (select 
                                coalesce(a.costing_pickup_fee, 0) + coalesce(a.costing_pickup_parking, 0) + coalesce(a.costing_pickup_toll, 0) + coalesce(a.costing_men_power, 0) as total_cost,
                                (select total_cost*(diskon_pickup/100)) as diskon
                            from
                                pengiriman a
                                    left join
                                user b ON (b.id_user = a.pickup_by)
                                    left join
                                user c ON (c.id_user = a.costing_pickup_by)
                            where
                                (a.pickup_by = '$pickup_by')
                                    and DATE_FORMAT(a.pickup_date, '%Y-%m-%d') = '$pickup_date') x
                          ) tmp";

                        $resDiskon = SSP::simple( NULL, $GLOBALS["sql_details"], $cekDiskon, $primaryKey, $columnD );
                        $diskon = $resDiskon['data'][0][0];

                        return "Rp. ".number_format($row['total_cost']-$diskon);
                    }
                ),
			array(
				  'db'        => 'pickup_by',
				  'dt'        => 6,
                    'formatter' => function( $d, $row ) {
                        $aksi="module/mod_costing-pickup/costing-pickup-intercity_action.php";
                        $key = "asdf$1011";
                        return '<td><center><a class="btn btn-xs btn-primary" target="_blank" href="main.php?module=costing-pickup-intercity&act=view&id='.base64_encrypt($d,$key).'"><i class="fa fa-search"></i> View</a></center></td>';
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

	// create
	}/*elseif($module=='costing-pickup-intercity' and $act=='create'){
	    ob_start();
		$tgl = $mysqli->real_escape_string($_POST['tanggal']);
		$tanggal = jin_date_sql($tgl);
		$user_id = $mysqli->real_escape_string($_POST['id_user']);
		$result = $mysqli->query("update pengiriman set costing_pickup='Y', costing_pickup_by='$user_id' where date(pickup_date)='$tanggal' and pickup_by='$user_id' and costing_pickup!='Y'");
		if($result){
			echo'success';
		}else{
			echo'failed';
		}
		header('location:../../main.php?module='.$module);*/
	
	// calculate}
	elseif($module=='costing-pickup-intercity' and $act=='calculate'){
		if (mysqli_connect_errno())
		{
		  echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}

		$tglpickup = $mysqli->real_escape_string($_POST['tglpickup']);
		$costing_pickup_by = $mysqli->real_escape_string($_POST['costing_pickup_by']);
		$pickup_cost = $mysqli->real_escape_string($_POST['pickup_cost_real']);
		$parking_fee = $mysqli->real_escape_string($_POST['parking_fee']);
		$toll_fee = $mysqli->real_escape_string($_POST['toll_fee_real']);
		$id_pickup_by = $mysqli->real_escape_string($_POST['pickup_by']);
		
		//echo $id.' '. $id_pickup_by;
		//return false;

		// hitung jumlah airwaybill
		
		$count_airwaybill = $mysqli->query("select count(*) as qty, sum(if(berat_aktual > 0, berat_aktual, berat_barang)) as sum_berat  from pengiriman where (pickup_by='$id_pickup_by') and date(pickup_date)='$tglpickup'  ");
		$ca = $count_airwaybill->fetch_assoc();
		
		/*echo $ca['qty'];
		return false;*/

		//cek erro kalo kosong
		
		if ($pickup_cost == 0 || $toll_fee == 0 || $ca['qty'] == 0)
		{

			//echo "select count(*) as qty from pengiriman where date(pickup_date)='$tglpickup' and pickup_by='$id_pickup_by'";
			//return false;
			header('location:../../main.php?module='.$module.'&error_count='.$ca['qty'].'&pickup_cost='.$pickup_cost.'&toll_fee='.$toll_fee);
		}


		$q = $mysqli->query("SELECT id_pengiriman, if(berat_aktual > 0, berat_aktual, berat_barang) as berat_barang  from pengiriman where (pickup_by='$id_pickup_by') and date(pickup_date)='$tglpickup'  ");
		while($r = $q->fetch_assoc()){
			
			$avg_pickup_calc = $r['berat_barang']/$ca['sum_berat']*$pickup_cost;
			$avg_toll_calc = $r['berat_barang']/$ca['sum_berat']*$toll_fee;
			//echo $r['berat_barang']. '<br>'.$ca['sum_berat'].'<br>';
			
		$result = mysqli_query($con, "UPDATE pengiriman set costing_pickup='Y', costing_pickup_by='$id', costing_pickup_fee='$avg_pickup_calc', costing_pickup_toll='$avg_toll_calc' where id_pengiriman='$r[id_pengiriman]'");
		}
		
		if($result){
			header('Location: ' . $_SERVER['HTTP_REFERER']);
			
		}else{
			echo "failed";
		}
		
		
	// delete
	}elseif($module=='costing-pickup-intercity' and $act=='cancel'){
		$key = "asdf$1011";
		$encrypt = "$_GET[id]";
		$id = base64_decrypt($encrypt,$key);
		mysqli_query($con, "update pengiriman set status='5' where id_pengiriman='$id'");
		header('location:../../main.php?module='.$module);
	}

	elseif($module=='costing-pickup-intercity' and $act=='costingparking'){
		$id_pengiriman = $mysqli->real_escape_string($_POST['id']);
		$parking_fee = $mysqli->real_escape_string($_POST['parking_fee']);
		//return false;

		$result = mysqli_query($con, "UPDATE pengiriman set costing_pickup_parking='$parking_fee' where id_pengiriman='$id_pengiriman'");
		//echo "UPDATE pengiriman set costing_pickup_parking='$parking_fee', where id='$id_pengiriman'";
		header('Location: ' . $_SERVER['HTTP_REFERER']);
	}

	elseif($module=='costing-pickup-intercity' and $act=='costing_men_power'){
		$id_pengiriman = $mysqli->real_escape_string($_POST['id']);
		$costing_men_power = $mysqli->real_escape_string($_POST['men_power']);
		//return false;

		$result = mysqli_query($con, "UPDATE pengiriman set costing_men_power='$costing_men_power' where id_pengiriman='$id_pengiriman'");
		//echo "UPDATE pengiriman set costing_pickup_parking='$parking_fee', where id='$id_pengiriman'";
		header('Location: ' . $_SERVER['HTTP_REFERER']);
	}

	elseif($module=='costing-pickup-intercity' and $act=='save'){
		$tgl = $_GET['date'];
		$id_pickup_by = $_GET['pickupby'];
		//echo  $tgl .' - '. $id_pickup_by;
		//echo "update pengiriman set costing_pickup_save='Y' where date(pickup_date)='$tgl' and pickup_by='$id_pickup_by'";
		//return false;
		mysqli_query($con, "update pengiriman set costing_pickup_save='Y' where date(pickup_date)='$tgl' and pickup_by='$id_pickup_by'");
		header('Location: ' . $_SERVER['HTTP_REFERER']);
	}

	elseif($module=='costing-pickup-intercity' and $act=='costingparking_edit'){
		$id_pengiriman = $mysqli->real_escape_string($_POST['id']);
		$parking_fee = $mysqli->real_escape_string($_POST['parking_fee']);
		//return false;

		$result = mysqli_query($con, "UPDATE pengiriman set costing_pickup_parking='$parking_fee' where id_pengiriman='$id_pengiriman'");
		//echo "UPDATE pengiriman set costing_pickup_parking='$parking_fee', where id='$id_pengiriman'";
		header('Location: ' . $_SERVER['HTTP_REFERER']);
	}

	elseif($module=='costing-pickup-intercity' and $act=='costing_men_power_edit'){
		$id_pengiriman = $mysqli->real_escape_string($_POST['id']);
		$costing_men_power = $mysqli->real_escape_string($_POST['men_power']);
		//return false;

		$result = mysqli_query($con, "UPDATE pengiriman set costing_men_power='$costing_men_power' where id_pengiriman='$id_pengiriman'");
		//echo "UPDATE pengiriman set costing_pickup_parking='$parking_fee', where id='$id_pengiriman'";
		header('Location: ' . $_SERVER['HTTP_REFERER']);
	}

	elseif($module=='costing-pickup-intercity' and $act=='searchawb'){
		$q=mysqli_real_escape_string($con,$_POST['q']);

		$query=mysqli_query($con,"select a.id_manifest,CONCAT(c.manifest_pickup_by,'-', DATE_FORMAT(c.tanggal,'%Y-%m-%d')) AS pickup_by,CONCAT(b.pickup_by,'-', DATE_FORMAT(b.pickup_date,'%Y-%m-%d')) AS pickup, a.id_pengiriman,b.airwaybill_kode from manifest_detail a 
			left join pengiriman b on a.id_pengiriman  = b.id_pengiriman 
			left JOIN manifest c ON a.id_manifest = c.id_manifest		
		where b.airwaybill_kode LIKE '%".$q."%' and b.pickup='Y' ");

		
		// select a.id_manifest,a.id_pengiriman,b.airwaybill_kode from manifest_detail a inner join pengiriman b on a.id_pengiriman = b.id_pengiriman inner JOIN manifest c ON a.id_manifest = c.id_manifest where b.airwaybill_kode LIKE '%001-00185478%' and c.inbound='N' AND c.tipe_manifest is null
		$row=mysqli_num_rows($query);

		if ($row > 0){
			while ($data=mysqli_fetch_assoc($query)){
				$key = "asdf$1011";
					
				echo '
				<td>'.$data['airwaybill_kode'].'	</td>

				<td><center><a class="btn btn-xs btn-primary" target="_blank" href="?module=costing-pickup-intercity&act=view&id='.base64_encrypt($data['pickup'],$key).'"><i class="fa fa-search"></i> View </a></center></td>
				'; 
			}
		}
		else{
			echo'
			<td colspan="2">
				<center><b>AIRWAYBILL NOT FOUND</b></center>
			</td>'; 
		}
	}elseif($module=='costing-pickup-intercity' and $act=='diskon'){
        $arrData = explode('~', $_POST['pk']);

        mysqli_query($con, "update pengiriman set diskon_pickup='$_POST[value]' where id_pengiriman='".$arrData[0]."'");
        echo json_encode(array("id_pengiriman" => $arrData[0], "total" => $arrData[1], "diskon" => $_POST['value']));
    }elseif($module=='costing-pickup-intercity' and $act=='packing'){
        $id = $_POST['pk'];

        mysqli_query($con, "update pengiriman set biaya_packing='$_POST[value]' where id_pengiriman='".$id."'");
        echo json_encode(array("id_pengiriman" => $id, "biaya_packing" => $_POST['value']));
    }
}
?>