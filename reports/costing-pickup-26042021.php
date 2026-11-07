<?php
include ('../config/connection.php');
include ('../config/encrypt.php');
include ('../config/library.php');
include ('phpqrcode/qrlib.php'); 
	
/*$key = "asdf$1011";
$encrypt = "$_GET[id]";
$id = base64_decrypt($encrypt,$key);
			
$pieces = explode('-', $id);
$pickup_by = $pieces['0'];
$pickup_date = implode('-', array_slice($pieces, 1, 3));
			
$query = mysqli_query($con, "select b.namalengkap from pengiriman a inner join user b on b.id_user=a.pickup_by where a.pickup_by='$pickup_by' and DATE_FORMAT(a.pickup_date,'%Y-%m-%d')='$pickup_date'");
$r = mysqli_fetch_assoc($query);

$tanggal = tgl_indo($pickup_date);

$query_kode_manifest = mysqli_query($con, "select kode_manifest from manifest 
				where tanggal='$pickup_date' and manifest_pickup_by = '$pickup_by'");
			$r_kode = mysqli_fetch_assoc($query_kode_manifest);*/

$key = "asdf$1011";
$encrypt = "$_GET[id]";
$id = base64_decrypt($encrypt,$key);
			
$pieces = explode('-', $id);
$courier_by = $pieces['0'];
$courier_date = implode('-', array_slice($pieces, 1, 3));

//echo $courier_by.$courier_date;
		
$query = mysqli_query($con, "select DATE_FORMAT(a.pickup_date,'%Y-%m-%d') as tglpickup,a.costing_pickup_save, c.namalengkap as nama_costing, sum(coalesce(a.costing_pickup_fee,0)) as costing_pickup_fee, sum(coalesce(a.costing_pickup_toll,0)) as costing_pickup_toll, b.namalengkap, sum(coalesce(a.costing_pickup_fee,0)) + sum(coalesce(a.costing_pickup_parking,0)) + sum(coalesce(a.costing_pickup_toll,0)) + sum(coalesce(a.costing_men_power,0)) as total_cost from pengiriman a left join user b on b.id_user=a.pickup_by left join user c on (c.id_user=a.costing_pickup_by) where a.pickup_by='$courier_by' and DATE_FORMAT(a.pickup_date,'%Y-%m-%d')='$courier_date'  ");


$r = mysqli_fetch_assoc($query);

$query_kode_manifest = mysqli_query($con, "select kode_manifest from manifest 
	where tanggal='$courier_date' and manifest_pickup_by = '$courier_by'");
$r_kode = mysqli_fetch_assoc($query_kode_manifest);

$tanggal = tgl_indo($courier_date);

//QRcode::png("".$r['airwaybill_kode']."","image.png","L",2,2);
?>
<!DOCTYPE html>
<html>
	<head>
	<meta charset="UTF-8">
		<title>pickup MANIFEST</title>
	</head>
	<body>
		<table cellspacing=0 width="100%">
			<tr>
				<td style="font-family:Calibri;text-align:center;font-size:16px;color:#000000;font-weight:bold;" colspan="8">pickup MANIFEST</td>
			</tr>
			<tr>
				<td colspan="10"></td>
			</tr>
			<tr>
				<td style="font-family:Calibri;text-align:center;font-size:16px;color:#000000;font-weight:bold;" colspan="8">MANIFEST CODE : <?php echo $r_kode['kode_manifest']; ?></td>
		  	</tr>
			<tr>
			  <td colspan="2" style=min-width:50px><span style="font-family:Calibri;text-align:right;font-size:14px;color:#000000;">TANGGAL :</span></td>
			  <td colspan="8" style=min-width:50px><span style="font-family:Calibri;font-size:14px;color:#000000;"><?php echo''.$tanggal.'';?></span></td>
		    </tr>
			<tr>
				<td colspan="2" style=min-width:50px><span style="font-family:Calibri;text-align:right;font-size:14px;color:#000000;">KURIR :</span></td>
				<td colspan="8" style=min-width:50px><span style="font-family:Calibri;font-size:14px;color:#000000;"> <?php echo''.strtoupper($r['namalengkap']).'';?></span></td>
		  </tr>		  
			<tr>
				<td colspan="8">&nbsp;</td>
			</tr>
			<tr>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border:1px solid;">NO</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">AWB</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">CUSTOMER</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Origin</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Destination</td>
                <td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Delivered Date</td>
                <td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Lead Time</td>
                <td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Reason Late</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">KOLI</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">BERAT</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">D/P</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">pickup Cost</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Parking</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Toll</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Men Power</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Total</td>
                <td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Discount (%)</td>
                <td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Total after Discount</td>
			</tr>
			<?php
			$no=1;
            $sql = "select 
                    pickup_date,
                    id_pengiriman,
                    airwaybill_kode,
                    x_namakustomer(id_kustomer) as kustomer,
                    ifnull(x_namakotabesar(lokasi_asal), a.kota_asal) as lokasi_asal,
                    ifnull(x_namakotabesar(lokasi_tujuan), a.kota_tujuan) as lokasi_tujuan,
                    delivery_date, 
                    if(delivery_date is null,'-', datediff(delivery_date, date(pickup_date))) as selisih_hari,
                    alasan_terlambat_pickup,
                    collie,
                    if(berat_aktual > 0,
                        berat_aktual,
                        berat_barang) as berat_barang,
                    case
                        when komoditi = '1' then 'Paket'
                        else 'Dokumen'
                    end as komoditi,
                    costing_pickup_fee,
                    costing_pickup_parking,
                    costing_pickup_toll,
                    costing_men_power,
                    ifnull(costing_pickup_fee, 0) + ifnull(costing_pickup_parking, 0) + ifnull(costing_pickup_toll, 0) + ifnull(costing_men_power, 0) as cost_sum,
                    diskon_pickup,
                    ifnull( DATE_FORMAT( DATE_ADD(a.created_date, INTERVAL case when a.service=1 then                         
                        CASE 
                        WHEN airwaybill_kode LIKE '001-%' THEN 1
                        WHEN airwaybill_kode LIKE '002-%' THEN 0 
                        END 
                    when a.service=2 then 1 else s.sla_max end DAY), '%Y-%m-%d' ) ,'0' ) as sla_max2,
                    CONCAT(DATE_FORMAT(a.created_date,'%Y-%m-%d'),' ',                                     
                        ifnull( DATE_FORMAT( DATE_ADD(a.created_date, INTERVAL case when a.service=1 then CASE 
                        WHEN airwaybill_kode LIKE '001-%' THEN 1
                        WHEN airwaybill_kode LIKE '002-%' THEN 0 
                        END  when a.service=2 then 1 else s.sla_max end DAY), '%Y-%m-%d' ) ,'0' )
                        ) as sla_date,
                    substring(airwaybill_kode,1,3) as awb_code,
                    lokasi_asal as id_lokasi_asal,
                    lokasi_tujuan as id_lokasi_tujuan,
                    datediff(a.delivery_date, (select sla_max2)) as selisih_hari2
                from
                    pengiriman a
                    left join sla s on s.kota_asal =  a.id_kota_asal   AND s.kota_tujuan=  a.id_kota_tujuan  AND s.service = a.service
                where
                    (pickup_by = '$courier_by')
                        and DATE_FORMAT(pickup_date, '%Y-%m-%d') = '$courier_date'
                order by created_date asc";
//echo $sql;
//  echo "select id_pengiriman, airwaybill_kode, x_namakustomer(id_kustomer) as kustomer, kota_asal, kota_tujuan, collie, berat_barang, case when komoditi='1' then 'Paket' else 'Dokumen' end as komoditi, costing_pickup_fee, costing_pickup_parking, costing_pickup_toll, costing_men_power, pickup_date , ifnull(costing_pickup_fee, 0) + ifnull(costing_pickup_parking, 0) + ifnull(costing_pickup_toll, 0) + ifnull(costing_men_power, 0) as cost_sum from pengiriman where pickup_by='$courier_by' and DATE_FORMAT(pickup_date,'%Y-%m-%d')='$courier_date'  order by created_date asc";

			$result = mysqli_query($con,$sql);

            $listAreaBdg = array(3204,3217,3273);
            $listAreaSby= array(3578);
            $sum_total = 0;
			while($t=mysqli_fetch_assoc($result)){

                $telat = "";
                if (($t['awb_code'] == '001' || $t['awb_code'] == '002') && (in_array( $t['id_lokasi_asal'] ,$listAreaBdg) || in_array( $t['id_lokasi_tujuan'] ,$listAreaBdg) || in_array( $t['id_lokasi_tujuan'] ,$listAreaSby) || in_array( $t['id_lokasi_tujuan'] ,$listAreaSby))) {
                    ///SLA
                    $d = $t['sla_date'];
                    $pieces = explode(' ', $d);

                    $booking_date = $pieces[0];

                    $d1 = strtotime($pieces[1]);
                    $d2 = date('l', $d1);
                    $dt3 = strtolower($d2);

                    if ($dt3 == "saturday") {
                        $sla = date('Y-m-d', strtotime($pieces[1] . '+2 day'));
                    } elseif ($dt3 == "sunday") {
                        $sla = date('Y-m-d', strtotime($pieces[1] . '+1 day'));
                    } else {
                        $sla = date('Y-m-d', strtotime($pieces[1]));
                    }

                    $date_now = date("Y-m-d");

                    if ($sla != '1970-01-01') {

                        if ($date_now > $sla && $t['selisih_hari2'] > 0) {

                            $telat = '(TERLAMBAT)';

                            $created_date = $booking_date . ' - ' . $sla . '(TERLAMBAT)';
                        } else {
                            $created_date = $booking_date . ' - ' . $sla;
                        }
                    } else {
                        $created_date = $booking_date;
                    }
                    ////
                }

                $total = $t['cost_sum'];
                $diskon = $t['diskon_pickup'];
                $star = ($diskon > 0 || $telat != "") ? '*' : '';
                $totalAfterDiskon = $total-(($diskon/100)*$total);
                $sum_total = $sum_total + $totalAfterDiskon;

				echo'
				<tr>
					<td style="font-family:Calibri;text-align:center;font-size:14px;font-color:#000000;border-color:#000;border:1px solid;">'.$no.'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['airwaybill_kode'].$star.'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['kustomer'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['lokasi_asal'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['lokasi_tujuan'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['delivery_date'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['selisih_hari'].' hari '.$telat.'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['alasan_terlambat_pickup'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['collie'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['berat_barang'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['komoditi'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.number_format(round($t['costing_pickup_fee'])).'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.number_format(round($t['costing_pickup_parking'])).'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.number_format(round($t['costing_pickup_toll'])).'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.number_format(round($t['costing_men_power'])).'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.number_format(round($t['cost_sum'])).'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$diskon.'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.number_format(round($totalAfterDiskon)).'</td>
				</tr>				
				';
			$no++;
			}
			?>
			<tr>
				<td colspan="17" align="right">Total</td>
				<td colspan="12" style="border-color:#000;border:1px solid;" align="center"><?php echo 'Rp.'.number_format(round($sum_total)); ?></td>
			</tr>
			<tr>
			  <td style=min-width:50px></td>
			  <td style=min-width:50px></td>
			  <td style=min-width:50px></td>
			  <td style=min-width:50px></td>
			  <td style=min-width:50px></td>
			  <td style="font-family:Calibri;text-align:right;font-size:14px;color:#000000;">&nbsp;</td>
			  <td style=min-width:50px></td>
		  </tr>
		</table>
	<script type="text/javascript">
		window.print();
		//window.onfocus=function(){ window.close();}
		</script>
	</body>
</html>