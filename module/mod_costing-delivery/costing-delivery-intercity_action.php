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
	if($module=='costing-delivery-intercity' and $act=='data'){

		if($u['level']=='kurir' || $u['level']=='driver'){
			$table =
<<<EOT
	(
	select
		DATE_FORMAT(a.courier_date),'%Y-%m-%d') as delivery,
		a.kode_manifest,
		b.namalengkap,
		count(id_pengiriman) as jml,
		sum(a.berat_barang) as berat,
		sum(a.collie) as collie,
		concat("Rp. ", format(round(SUM(coalesce(a.costing_delivery_fee,0))+ sum(coalesce(a.biaya_packing,0)),0),0)) AS cost_delivery,
		concat("Rp. ", format(round(sum(coalesce(a.costing_delivery_parking,0)),0),0)) AS parking_fee,
		concat(a.courier_by,'-', DATE_FORMAT(a.courier_date,'%Y-%m-%d')) as courier_by,
		a.id_pengiriman
		from pengiriman a
	inner join user b on b.id_user=a.courier_by where a.tanggal > DATE_SUB(now(), INTERVAL 1 MONTH) and a.courier_by is not null and a.courier_by='$u[id_user]'
	group by DATE_FORMAT(a.courier_date),'%y-%m-%d'), courier_by
	) temp
EOT;
		}elseif($u['level']=='intercity'){
			$table =
<<<EOT
	(
	select
		DATE_FORMAT(a.courier_date),'%Y-%m-%d') as delivery,
		b.namalengkap,
		count(id_pengiriman) as jml,
		sum(a.berat_barang) as berat,
		sum(a.collie) as collie,
		concat("Rp. ", format(SUM(coalesce(a.costing_delivery_fee,0))+ sum(coalesce(a.biaya_packing,0)),0)) AS cost_delivery,
		concat("Rp. ", format(sum(coalesce(a.costing_delivery_parking,0)),0)) AS parking_fee,
		concat(a.courier_by,'-', DATE_FORMAT(a.courier_date,'%Y-%m-%d')) as courier_by,
		a.id_pengiriman
		from pengiriman a
	inner join user b on b.id_user=a.courier_by where a.tanggal > DATE_SUB(now(), INTERVAL 1 MONTH) and a.courier_by is not null and x_intercity(a.lokasi_tujuan)='Y'
	group by DATE_FORMAT( a.courier_date,'%y-%m-%d'), courier_by
	) temp
EOT;
		}elseif($u['level']=='agent'){
			$table =
<<<EOT
	(
	select
		DATE_FORMAT(a.courier_date,'%Y-%m-%d') as delivery,
		b.namalengkap,
		count(id_pengiriman) as jml,
		sum(if(a.berat_aktual > 0, a.berat_aktual, a.berat_barang)) as berat,
		sum(a.collie) as collie,
		concat("Rp. ", format(round(SUM(coalesce(a.costing_delivery_fee,0))+sum(coalesce(a.biaya_packing,0)),0),0)) AS cost_delivery,
		concat("Rp. ", format(round(sum(coalesce(a.costing_delivery_toll,0)),0),0)) AS toll_fee,
		concat(a.courier_by,'-', DATE_FORMAT(a.courier_date,'%Y-%m-%d')) as courier_by,
		a.id_pengiriman
		from pengiriman a
	inner join user b on b.id_user=a.courier_by where a.tanggal > DATE_SUB(now(), INTERVAL 2 MONTH) and a.courier_by is not null and delivery_by_agent=$u[id_cabang]
	group by DATE_FORMAT(a.courier_date,'%y-%m-%d'), courier_by
	) temp
EOT;
		}

		else{
			$table =
<<<EOT
	(
	select
		DATE_FORMAT(a.courier_date,'%Y-%m-%d') as delivery,
		b.namalengkap,
		count(id_pengiriman) as jml,
		sum(if(a.berat_aktual > 0, a.berat_aktual, a.berat_barang)) as berat,
		sum(a.collie) as collie,
		concat("Rp. ", format(round(SUM(coalesce(a.costing_delivery_fee,0))+ sum(coalesce(a.biaya_packing,0)),0),0)) AS cost_delivery,
		concat("Rp. ", format(round(sum(coalesce(a.costing_delivery_toll,0)),0),0)) AS toll_fee,
		concat(a.courier_by,'-', DATE_FORMAT(a.courier_date,'%Y-%m-%d')) as courier_by,
		a.id_pengiriman
		from pengiriman a
	inner join user b on b.id_user=a.courier_by where a.tanggal > DATE_SUB(now(), INTERVAL 2 MONTH) and a.courier_by is not null and b.id_cabang in (11,16,39,41)
	group by DATE_FORMAT(a.courier_date,'%y-%m-%d'), courier_by
	) temp
EOT;
		}

		$primaryKey = 'id_pengiriman';

		$columns = array(
			array( 'db' => 'delivery',  'dt' => 0 ),
			array(
					'db'        => 'delivery',
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

			//array( 'db' => 'toll_fee', 'dt' => 5 ),
			array( 'db' => 'cost_delivery', 'dt' => 5
                    /*,
                'formatter' => function( $d, $row ) {

                    $primaryKey = 'id_pengiriman';
                    $columnD = array(
                        array( 'db' => 'total_diskon',  'dt' => 0 )
                    );

                    $pieces = explode('-', $row['courier_by']);
                    $courier_by = $pieces[0];
                    $courier_date = implode('-', array_slice($pieces, 1, 3));

                    $cekDiskon = "(
                            select coalesce(sum(diskon),0) as total_diskon from (select
                                coalesce(a.costing_delivery_fee, 0) + coalesce(a.biaya_packing, 0) as total_cost,
                                (select total_cost*(diskon_deliv/100)) as diskon
                            from
                                pengiriman a
                                    left join
                                user b ON (b.id_user = a.courier_by)
                                    left join
                                user c ON (c.id_user = a.costing_delivery_by)
                            where
                                (a.courier_by = '$courier_by')
                                    and DATE_FORMAT(ifnull(a.manifest_delivery_date,a.courier_date), '%Y-%m-%d') = '$courier_date') x
                          ) tmp";

                    $resDiskon = SSP::simple( NULL, SQL_DETAILS, $cekDiskon, $primaryKey, $columnD );
                    $diskon = $resDiskon['data'][0][0];

                    return "Rp. ".number_format($row['cost_delivery']-$diskon);
                }*/
            ),
			array( 'db' => 'courier_by', 'dt' => 6 ),
			array(
				  'db'        => 'courier_by',
				  'dt'        => 6,
				  'formatter' => function( $d, $row ) {
					$key = "asdf$1011";
					return '<td><center><a class="btn btn-xs btn-primary" target="_blank" href="main.php?module=costing-delivery-intercity&act=view&id='.base64_encrypt($d,$key).'"><i class="fa fa-search"></i> View</a></center></td>';
				  }
			)


		);

		require( '../../config/ssp.class.php' );

        $column0 = array(
            array( 'db' => 'cnt',  'dt' => 0 )
        );
        $cekBiaya0 = "(select count(1) as cnt from (select * from ".$table.") x where cost_delivery = 'Rp. 0') tmp";

        $total0 = SSP::simple( NULL, $sql_details, $cekBiaya0, $primaryKey, $column0 );

        $data =	SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns );

        $data['total0'] = $total0['data'][0][0];

        echo json_encode($data);


	}elseif($module=='costing-delivery-intercity' and $act=='calculate'){

		if (mysqli_connect_errno())
		{
		  echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}
		/*else{
			echo 'berhasil';
		}*/

		$tgldelivery 		= $mysqli->real_escape_string($_POST['tgldelivery']);
		$costing_courier_by = $mysqli->real_escape_string($_POST['costing_delivery_by']);
		$delivery_cost 		= $mysqli->real_escape_string($_POST['delivery_cost_real']);
		$parking_fee 		= $mysqli->real_escape_string($_POST['parking_fee']);
		$toll_fee 			= $mysqli->real_escape_string($_POST['toll_fee_real']);
		$id_courier_by 		= $mysqli->real_escape_string($_POST['courier_by']);

		// hitung jumlah airwaybill

		echo $delivery_cost.'<br><br>';
		$sqlold = "select count(*) as qty,
					sum(berat_barang) as sum_berat,
					sum(volume) as sum_volume
				from (
				SELECT
				berat_aktual,
					if(a.berat_aktual > 0,
						a.berat_aktual,
						a.berat_barang) as berat_barang,
				CASE
					WHEN airwaybill_kode LIKE '001-%' THEN 0
					WHEN airwaybill_kode LIKE '002-%' THEN 1
				end as intercity,
				SUBSTRING_INDEX(ukuran_barang, 'x', 1) as length,
				SUBSTRING_INDEX(SUBSTRING_INDEX(ukuran_barang, 'x', 2),
						'x',
						- 1) as width,
				SUBSTRING_INDEX(ukuran_barang, 'x', - 1) as height,
				case when upper(cs.kode_kustomer) in ('CIS','DEL') then
				  if((select intercity) = 1,
					((select length) * (select width) * (select height) / 4000),
					if(a.service = 5,
						((select length) * (select width) * (select height) / 4000),
						((select length) * (select width) * (select height) / 6000)))
				else
					if((select intercity) = 1,
						ceiling((select length) * (select width) * (select height) / 4000),
						if(a.service = 5,
							ceiling((select length) * (select width) * (select height) / 4000),
							ceiling((select length) * (select width) * (select height) / 6000)))
				end as volume
				FROM
				pengiriman a
				left join kustomer cs on cs.id_kustomer = a.id_kustomer
				where
				date(a.courier_date) = '".$tgldelivery."'
				and courier_by = '".$costing_courier_by."'
				and (a.status=6 or a.status=8 or a.status=5)
				) x";

		//"select count(*) as qty, sum(if(berat_aktual > 0, berat_aktual, berat_barang)) as sum_berat  from pengiriman where date(courier_date)='$tgldelivery' and courier_by='$costing_courier_by' and status=6"

		//$count_airwaybill = $mysqli->query($sql);
		//echo "<br> select count(*) as qty from pengiriman where date(courier_date)='$tgldelivery' and courier_by='$costing_courier_by' <br>";
		//$ca = $count_airwaybill->fetch_assoc();
		//header('location:../../main.php?module='.$module.'&error_count='.$ca['qty'].'&delivery_cost='.$delivery_cost.'&toll_fee='.$toll_fee);
		//cek erro kalo kosong
		//echo 'delivery cost ='.$delivery_cost . 'toll =' . $toll_fee .'qty = '. $ca['qty']. 'tgl = '.$tgldelivery. 'kurir = '.$costing_courier_by ;
		//return false;

		$count_airwaybill = mysqli_query($con,"select count(*) as qty, sum(if(berat_aktual > 0, berat_aktual, berat_barang)) as sum_berat  from pengiriman where date(courier_date)='$tgldelivery' and courier_by='$costing_courier_by' and (status=6 or status=8 or status=5)");
		//echo "<br> select count(*) as qty from pengiriman where date(courier_date)='$tgldelivery' and courier_by='$costing_courier_by' <br>";
		$ca = mysqli_fetch_assoc($count_airwaybill);



		$count_airwaybill2 = mysqli_query($con, "select

										SUBSTRING_INDEX(ukuran_barang,'x',1) as length,
										SUBSTRING_INDEX(SUBSTRING_INDEX(ukuran_barang,'x',2),'x',-1) as width,
										SUBSTRING_INDEX(ukuran_barang,'x',-1) as height,
									CASE
										WHEN airwaybill_kode LIKE '001-%' THEN 0
										WHEN airwaybill_kode LIKE '002-%' THEN 1
									end as intercity,
								if( (select intercity) = 1,
										ceiling((select length) *  (select width) * (select height)/4000)
									,
										if (service = 5,
											ceiling((select length) *  (select width) * (select height)/4000)
										,
											ceiling((select length) *  (select width) * (select height)/6000)
										)
									) as volume,
									if(berat_aktual is null or berat_aktual < 1,  berat_barang, berat_aktual) as berat_real,

		if( (select berat_real) > (select volume), (select berat_real), (select volume)) as sum_berat


		from pengiriman where date(courier_date)='$tgldelivery' and courier_by='$costing_courier_by' and status in (6,8,5)");

		while($ca2 = mysqli_fetch_assoc($count_airwaybill2)){
			$sum_berat=$sum_berat+ $ca2["sum_berat"];
		}

		if ($delivery_cost == 0 || $ca['qty'] == 0)
		{

			//echo "select count(*) as qty from pengiriman where date(delivery_date)='$tgldelivery' and courier_by='$id_courier_by'";
			//return false;
			header('location:../../main.php?module='.$module.'&error_count='.$ca['qty'].'&delivery_cost='.$delivery_cost.'&toll_fee='.$toll_fee);
		}

		$sql = "SELECT a.id_pengiriman,
				airwaybill_kode,
				berat_aktual,
					berat_aktual,
					if(a.berat_aktual > 0,
						a.berat_aktual,
						a.berat_barang) as berat_kg,
				CASE
					WHEN airwaybill_kode LIKE '001-%' THEN 0
					WHEN airwaybill_kode LIKE '002-%' THEN 1
				end as intercity,
				courier_date,
				SUBSTRING_INDEX(ukuran_barang, 'x', 1) as length,
				SUBSTRING_INDEX(SUBSTRING_INDEX(ukuran_barang, 'x', 2),
						'x',
						- 1) as width,
				SUBSTRING_INDEX(ukuran_barang, 'x', - 1) as height,
				case when upper(cs.kode_kustomer) in ('CIS','DEL') then
				  if((select intercity) = 1,
					ceiling((select length) * (select width) * (select height) / 4000),
					if(a.service = 5,
						ceiling((select length) * (select width) * (select height) / 4000),
						ceiling((select length) * (select width) * (select height) / 6000)))
				else
					if((select intercity) = 1,
						ceiling((select length) * (select width) * (select height) / 4000),
						if(a.service = 5,
							ceiling((select length) * (select width) * (select height) / 4000),
							ceiling((select length) * (select width) * (select height) / 6000)))
				end as volume,
				if((select berat_kg) > ceiling((select volume)) , (select berat_kg) , (select volume)) as berat_real
				FROM
				pengiriman a
				left join kustomer cs on cs.id_kustomer = a.id_kustomer
				where
				date(a.courier_date) = '".$tgldelivery."'
				and courier_by = '".$costing_courier_by."'
				and (a.status=6 or a.status=8 or a.status=5)";


		//"SELECT id_pengiriman, if(berat_aktual > 0, berat_aktual, berat_barang) as berat_barang from pengiriman where date(courier_date)='$tgldelivery' and courier_by='$costing_courier_by' and status=6"

		$q = $mysqli->query($sql);
		while($r = $q->fetch_assoc()){

			//if ($r['berat_barang'] > $r['volume']){
				$avg_delivery_calc = $r['berat_real']/$sum_berat*$delivery_cost;
				$avg_toll_calc = $r['berat_real']/$sum_berat*$toll_fee;
				//echo $r['airwaybill_kode'].', '.$r['berat_real'].' ' .$sum_berat.'<br>';
			/*}else{
				$avg_delivery_calc = $r['volume']/$ca['sum_volume']*$delivery_cost;
				$avg_toll_calc = $r['volume']/$ca['sum_volume']*$toll_fee;
			}*/

			$result = mysqli_query($con, "UPDATE pengiriman set costing_delivery='Y', costing_delivery_by='$id', costing_delivery_fee='$avg_delivery_calc', costing_delivery_toll='$avg_toll_calc' where id_pengiriman='$r[id_pengiriman]' and (status=6 or status=8 or status=5)");

			//echo $r['berat_real'].' , '.$sum_berat.',  '.$r['airwaybill_kode']. " ,   UPDATE pengiriman set costing_delivery='Y', costing_delivery_by='$id', costing_delivery_fee='$avg_delivery_calc', costing_delivery_toll='$avg_toll_calc' where id_pengiriman='$r[id_pengiriman]' and (status=6 or status=8) ".$r[courier_date]." <br><br>";

			//echo "UPDATE pengiriman set costing_delivery='Y', costing_delivery_by='$id', costing_delivery_fee='$avg_delivery_calc', costing_delivery_toll='$avg_toll_calc' where id_pengiriman='$r[id_pengiriman]' and (status=6 or status=8 or status=5) <br>$r[airwaybill_kode]<br>";
		}

		/*$result = mysqli_query($con, "UPDATE pengiriman set costing_delivery='Y', costing_delivery_by='$id', costing_delivery_fee='$avg_delivery', costing_delivery_toll='$avg_toll' where date(courier_date)='$tgldelivery' and courier_by='$costing_courier_by'");*/

		if($result){
			header('Location: ' . $_SERVER['HTTP_REFERER']);

		}else{
			echo "failed";
		}


	// delete

	// delete
	}
	elseif($module=='costing-delivery-intercity' and $act=='costingparking'){
		$id_pengiriman = $mysqli->real_escape_string($_POST['id']);
		$parking_fee = $mysqli->real_escape_string($_POST['parking_fee']);
		//echo $id_pengiriman . 'and' .$parking_fee;
		//return false;


		$result = mysqli_query($con, "UPDATE pengiriman set costing_delivery_parking='$parking_fee' where id_pengiriman='$id_pengiriman' and (status=6 or status=8 or status=5)");
		//echo "UPDATE pengiriman set costing_delivery_parking='$parking_fee', where id='$id_pengiriman'";
		header('Location: ' . $_SERVER['HTTP_REFERER']);
	}

	elseif($module=='costing-delivery-intercity' and $act=='costing_men_power'){
		$id_pengiriman = $mysqli->real_escape_string($_POST['id']);
		$costing_men_power = $mysqli->real_escape_string($_POST['men_power']);
		//echo $id_pengiriman . 'and' .$costing_men_power;
		//return false;

		$result = mysqli_query($con, "UPDATE pengiriman set costing_delivery_menpower='$costing_men_power' where id_pengiriman='$id_pengiriman' and (status=6 or status=8 or status=5)");
		//echo "UPDATE pengiriman set costing_delivery_parking='$parking_fee', where id='$id_pengiriman'";
		header('Location: ' . $_SERVER['HTTP_REFERER']);
	}
	elseif($module=='costing-delivery-intercity' and $act=='save'){
		$tgl = $_GET['date'];
		$id_delivery_by = $_GET['deliveryby'];
		//echo  $tgl .' - '. $id_delivery_by;
		//echo "update pengiriman set costing_delivery_save='Y' where date(courier_date)='$tgl' and courier_by='$id_delivery_by'";
		//return false;
		mysqli_query($con, "update pengiriman set costing_delivery_save='Y' where date(courier_date)='$tgl' and courier_by='$id_delivery_by' and (status=6 or status=8 or status=5)");
		header('Location: ' . $_SERVER['HTTP_REFERER']);
	}
	elseif($module=='costing-delivery-intercity' and $act=='costingparking_edit'){
		$id_pengiriman = $mysqli->real_escape_string($_POST['id']);
		$parking_fee = $mysqli->real_escape_string($_POST['parking_fee']);
		//return false;

		$result = mysqli_query($con, "UPDATE pengiriman set costing_delivery_parking='$parking_fee' where id_pengiriman='$id_pengiriman' and (status=6 or status=8 or status=5)");
		//echo "UPDATE pengiriman set costing_delivery_parking='$parking_fee', where id='$id_pengiriman'";
		header('Location: ' . $_SERVER['HTTP_REFERER']);
	}

	elseif($module=='costing-delivery-intercity' and $act=='costing_men_power_edit'){
		$id_pengiriman = $mysqli->real_escape_string($_POST['id']);
		$costing_men_power = $mysqli->real_escape_string($_POST['men_power']);
		//return false;

		$result = mysqli_query($con, "UPDATE pengiriman set costing_delivery_menpower='$costing_men_power' where id_pengiriman='$id_pengiriman' and (status=6 or status=8 or status=5)");
		//echo "UPDATE pengiriman set costing_delivery_parking='$parking_fee', where id='$id_pengiriman'";
		header('Location: ' . $_SERVER['HTTP_REFERER']);
	}

	elseif($module=='costing-delivery-intercity' and $act=='searchawb'){
		$q=mysqli_real_escape_string($con,$_POST['q']);

		/*
		$query=mysqli_query($con,"select a.id_manifest,CONCAT(b.courier_by,'-', DATE_FORMAT(b.courier_date,'%Y-%m-%d')) AS delivery_by, a.id_pengiriman,b.airwaybill_kode from manifest_detail a
			inner join pengiriman b on a.id_pengiriman  = b.id_pengiriman
			inner JOIN manifest c ON a.id_manifest = c.id_manifest
		where b.airwaybill_kode LIKE '%".$q."%' and b.delivery='Y' and  c.tipe_manifest ='delivery' and b.status=6
		AND b.courier_by is not null limit 1
		");*/

        $query=mysqli_query($con,"select  CONCAT(courier_by,'-', DATE_FORMAT(courier_date,'%Y-%m-%d')) AS delivery_by, id_pengiriman,airwaybill_kode from pengiriman 
			
		where airwaybill_kode LIKE '%".$q."%' and delivery='Y' and (status=6 or status=8 or status=5)
		AND courier_by is not null limit 1
		");

		// select a.id_manifest,a.id_pengiriman,b.airwaybill_kode from manifest_detail a inner join pengiriman b on a.id_pengiriman = b.id_pengiriman inner JOIN manifest c ON a.id_manifest = c.id_manifest where b.airwaybill_kode LIKE '%001-00185478%' and c.inbound='N' AND c.tipe_manifest is null
		$row=mysqli_num_rows($query);

		if ($row > 0){
			while ($data=mysqli_fetch_assoc($query)){
				$key = "asdf$1011";

				echo '
				<td>'.$data['airwaybill_kode'].'	</td>

				<td><center><a class="btn btn-xs btn-primary" target="_blank" href="?module=costing-delivery-intercity&act=view&id='.base64_encrypt($data['delivery_by'],$key).'"><i class="fa fa-search"></i> View </a></center></td>
				';
			}
		}
		else{
			echo'
			<td colspan="2">
				<center><b>AIRWAYBILL NOT FOUND</b></center>
			</td>';
		}
	}elseif($module=='costing-delivery-intercity' and $act=='diskon'){
        $arrData = explode('~', $_POST['pk']);

        mysqli_query($con, "update pengiriman set diskon_deliv='$_POST[value]' where id_pengiriman='".$arrData[0]."'");
        echo json_encode(array("id_pengiriman" => $arrData[0], "total" => $arrData[1], "diskon" => $_POST['value']));
    }elseif($module=='costing-delivery-intercity' and $act=='packing'){
        $id = $_POST['pk'];

        mysqli_query($con, "update pengiriman set biaya_packing='$_POST[value]' where id_pengiriman='".$id."'");
        echo json_encode(array("id_pengiriman" => $id, "biaya_packing" => $_POST['value']));
    }
    elseif($module=='costing-delivery-intercity' and $act=='biaya-delivery-manual'){
        $id = $_POST['pk'];

        mysqli_query($con, "update pengiriman set costing_delivery_fee='$_POST[value]' where id_pengiriman='".$id."'");
        echo json_encode(array("id_pengiriman" => $id, "costing_delivery_fee" => $_POST['value']));
    }
    elseif ($module=='costing-delivery-intercity' AND $act=='plus'){
		$tanggal		= jin_date_sql($_POST['tanggalplus']);
		$awb		= $_POST['selectawb'];
		$id_awb		= implode(',',$awb);
		//echo "update pengiriman set courier_date='$tanggal' where id_pengiriman in ($id_awb)";

		mysqli_query($con, "update pengiriman set courier_date='$tanggal' where id_pengiriman in ($id_awb)");
		header('Location: ' . $_SERVER['HTTP_REFERER']);
	}
	elseif($module=='costing-delivery-intercity' and $act=='searchmerah'){
		//mysqli_query($con, "update rate_agent_cross set min_kg='$_POST[value]' where rate_id='$_POST[pk]'");
		$q=mysqli_real_escape_string($con,$_POST['q']);


		$query=mysqli_query($con," select airwaybill_kode, 

			
			
			
			

			
			 DATE_FORMAT(courier_date,'%Y-%m-%d') as deliv_tanggal,x_user(courier_by) as deliv, CONCAT(courier_by,'-', DATE_FORMAT(courier_date,'%Y-%m-%d')) AS deliv_format


			from pengiriman
		where   status !=7  and (status=6 or status=8) and  x_cabangcesco(delivery_by_agent) = 'Y'

		and (costing_delivery_fee  is null or costing_delivery_fee = 0) 

		and tanggal >= now() - INTERVAL 12 MONTH  order by tanggal asc
		");
		

		$row=mysqli_num_rows($query);

		$key = "asdf$1011";

		if ($row > 0){

			while ($data=mysqli_fetch_assoc($query)){
				$view = $data['deliv_format'];
				

				echo '
				<tr>
				';
				
					echo '
					
					<td><center>'.$data['deliv_tanggal'].'</center></td>
					<td><center>'.$data['deliv'].'</center></td>
					<td><center>'.$data['airwaybill_kode'].'</center></td>';
				
				
					echo '<td><center>
					<a class="btn btn-xs btn-primary" target="_blank" href="?module=costing-delivery-intercity&act=view&id='.base64_encrypt($view,$key).'"><i class="fa fa-search"></i> View </a>
					</center></td>';
				

				echo '
				</tr>
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
}

?>
