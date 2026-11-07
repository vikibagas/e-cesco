<?php
include ('../../config/connection.php');
include ('../../config/encrypt.php');
include ('../../config/library.php');



			
//pieces = explode('-', $id);
//$pickup_by = $pieces['0'];
//$pickup_date = implode('-', array_slice($pieces, 1, 3));
$tahun = $_GET['tahun'];
$bulan = $_GET['bulan'];
$id_kustomer = $_GET['id_kustomer']; 		
//$query = mysqli_query($con,"select DATE_FORMAT(a.pickup_date,'%Y-%m-%d') as tglpickup, a.costing_pickup_by , a.costing_pickup_save,b.namalengkap as namalengkap, c.namalengkap as nama_costing ,sum(coalesce(a.costing_pickup_fee,0)) as costing_pickup_fee, sum(coalesce(a.costing_pickup_parking,0)) as costing_pickup_parking, sum(coalesce(a.costing_pickup_toll,0)) as costing_pickup_toll , sum(coalesce(a.costing_pickup_fee,0)) + sum(coalesce(a.costing_pickup_parking,0)) + sum(coalesce(a.costing_pickup_toll,0)) + sum(coalesce(a.costing_men_power,0)) as total_cost from pengiriman a left join user b on (b.id_user=a.pickup_by) left join user c on (c.id_user=a.costing_pickup_by) where a.pickup_by='$pickup_by'  and DATE_FORMAT(a.pickup_date,'%Y-%m-%d')='$pickup_date' and x_intercity(a.lokasi_asal)='Y' and a.id_cabang in (11,16,39) ");

			
$q = "

							select nama_kustomer from kustomer where id_kustomer=".$id_kustomer." ";
 
$r_kust = mysqli_fetch_assoc(mysqli_query($con, $q));

//$tanggal = tgl_indo($pickup_date);

header("Content-type: application/vnd-ms-excel");
header('Content-Disposition: attachment; filename="'.$r_kust['nama_kustomer'].'-'.$tahun.'-'.$bulan.'.Report-summary-delivery-per-customer.xls"');

?>
<!DOCTYPE html>
<html>
	<head>
	<meta charset="UTF-8">
		<title>Report Costing Delivery Per Customer</title>
	</head>
	<body>
		<table cellspacing=0 width="100%">
			<tr>
				<td style="font-family:Calibri;text-align:center;font-size:16px;color:#000000;font-weight:bold;" colspan="17">Report summary delivery</td>
			</tr>
			<tr>
				<td colspan="3"></td>
			</tr>	  
			<tr>
				<td colspan="3">&nbsp;</td>
			</tr>

			<tr>
				<td colspan="2" style="text-align:left;">Customer :</td>
				<td colspan="1" style="text-align:left;"><?php echo $r_kust['nama_kustomer']; ?></td>
			</tr>
			<tr>
				<td colspan="2" style="text-align:left;">Tahun :</td>
				<td colspan="1" style="text-align:left;"><?php echo $_GET['tahun']; ?></td>
				<td colspan="2" style="text-align:left;">Bulan :</td>
				<td colspan="1" style="text-align:left;"><?php echo $_GET['bulan']; ?></td>
			</tr>

			<tr>
				<td colspan="3"></td>
			</tr>	  
			<tr>
				<td colspan="3">&nbsp;</td>
			</tr>
			<tr>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border:1px solid;">NO</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">AWB</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">CUSTOMER</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Origin</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">delivery Date</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Delivered Date</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Lead Time</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Reason Late</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">KOLI</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">BERAT/VOLUME</td>
				
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">D/P</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">delivery Cost</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Packing</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Parking</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Toll</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Men Power</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Total</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Discount (%)</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Total after Discount</td>
			</tr>
			<?php
			$no=1;

			//"select id_pengiriman, airwaybill_kode, x_namakustomer(id_kustomer) as kustomer, x_namakotabesar(lokasi_asal) as lokasi_asal, x_namakotabesar(lokasi_tujuan) as lokasi_tujuan, collie, if(berat_aktual > 0, berat_aktual, berat_barang) as berat_barang, case when komoditi='1' then 'Paket' else 'Dokumen' end as komoditi, costing_pickup_fee, costing_pickup_parking, costing_pickup_toll ,costing_men_power , ifnull(costing_pickup_fee, 0) + ifnull(costing_pickup_parking, 0) + ifnull(costing_pickup_toll, 0) + ifnull(costing_men_power, 0) as cost_sum from pengiriman where pickup_by='$pickup_by' and DATE_FORMAT(pickup_date,'%Y-%m-%d')='$pickup_date' and x_intercity(lokasi_asal)='Y'  and id_cabang in (11,16,39) order by created_date asc"

             $sql = "select 
                    DATE_FORMAT(a.delivery_date,'%Y-%m-%d') as tgldeliv,
                    id_pengiriman,
                    airwaybill_kode,
                    x_namakustomer(a.id_kustomer) as kustomer,
                    ifnull(x_namakotabesar(lokasi_asal), a.kota_asal) as lokasi_asal,
                    ifnull(x_namakotabesar(lokasi_tujuan), a.kota_tujuan) as lokasi_tujuan,
                    delivery_date, 
                    if(delivery_date is null,'-', datediff(delivery_date, date(pickup_date))) as selisih_hari,
                    alasan_terlambat_delivery,
                    collie,
                    if(berat_aktual > 0,
                        berat_aktual,
                        berat_barang) as berat_barang,
                    case
                        when komoditi = '1' then 'Paket'
                        else 'Dokumen'
                    end as komoditi,
                    costing_delivery_fee,
                    costing_delivery_parking,
                    costing_delivery_toll,
                    costing_men_power,
                    ifnull(costing_delivery_fee, 0) + ifnull(costing_delivery_parking, 0) + ifnull(costing_delivery_toll, 0) + ifnull(biaya_packing, 0) + ifnull(costing_men_power, 0) as cost_sum,
                    diskon_deliv,
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
                    lokasi_tujuan as id_lokasi_tujuan,biaya_packing,
                    datediff(a.delivery_date, (select sla_max2)) as selisih_hari2,
					CASE
							WHEN airwaybill_kode LIKE '001-%' THEN 0
							WHEN airwaybill_kode LIKE '002-%' THEN 1
						end as intercity,
						SUBSTRING_INDEX(ukuran_barang, 'x', 1) as length,
						SUBSTRING_INDEX(SUBSTRING_INDEX(ukuran_barang, 'x', 2),
								'x',
								- 1) as width,
						SUBSTRING_INDEX(ukuran_barang, 'x', - 1) as height,
						case
							when
								upper(cs.kode_kustomer) in ('CIS' , 'DEL')
							then
								if((select intercity) = 1,
									((select length) * (select width) * (select height) / 4000),
									if(a.service = 5,
										((select length) * (select width) * (select height) / 4000),
										((select length) * (select width) * (select height) / 6000)))
							else if((select intercity) = 1,
								ceiling((select length) * (select width) * (select height) / 4000),
								if(a.service = 5,
									ceiling((select length) * (select width) * (select height) / 4000),
									ceiling((select length) * (select width) * (select height) / 6000)))
						end as volume
                from
                    pengiriman a
                    left join sla s on s.kota_asal =  a.id_kota_asal   AND s.kota_tujuan=  a.id_kota_tujuan  AND s.service = a.service
					left join kustomer cs ON cs.id_kustomer = a.id_kustomer
               where a.id_kustomer='".$id_kustomer."' and a.delivery_date between '".$tahun."-".$bulan."-01' and '".$tahun."-".$bulan."-31'  and delivery_by != ''
               and x_inter(a.id_kota_tujuan) = 'Y'
                order by a.delivery_date asc";

            //"select id_pengiriman, airwaybill_kode, x_namakustomer(id_kustomer) as kustomer, x_namakotabesar(lokasi_asal) as lokasi_asal, x_namakotabesar(lokasi_tujuan) as lokasi_tujuan, collie, if(berat_aktual > 0, berat_aktual, berat_barang) as berat_barang, case when komoditi='1' then 'Paket' else 'Dokumen' end as komoditi, costing_pickup_fee, costing_pickup_parking, costing_pickup_toll ,costing_men_power , ifnull(costing_pickup_fee, 0) + ifnull(costing_pickup_parking, 0) + ifnull(costing_pickup_toll, 0) + ifnull(costing_men_power, 0) as cost_sum from pengiriman where pickup_by='$pickup_by' and DATE_FORMAT(pickup_date,'%Y-%m-%d')='$pickup_date' and x_intercity(lokasi_asal)='Y'  and id_cabang in (11,16,39) order by created_date asc"

            $listAreaBdg = array(3204,3217,3273);
            $listAreaSby= array(3578);

			$result = mysqli_query($con,$sql);
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
                $diskon = $t['diskon_deliv'];
                $totalAfterDiskon = $total-(($diskon/100)*$total);
                $sum_total = $sum_total + $totalAfterDiskon;


                echo'
				<tr>
					<td style="font-family:Calibri;text-align:center;font-size:14px;font-color:#000000;border-color:#000;border:1px solid;">'.$no.'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['airwaybill_kode'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['kustomer'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['lokasi_tujuan'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['tgldeliv'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['delivery_date'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['selisih_hari'].' hari '.$telat.'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['alasan_terlambat_delivery'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['collie'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['berat_barang'].'</td>
					
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['komoditi'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.round($t['costing_delivery_fee']).'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.round($t['biaya_packing']).'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.round($t['costing_delivery_parking']).'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.round($t['costing_delivery_toll']).'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.round($t['costing_men_power']).'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.round($t['cost_sum']).'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$diskon.'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.round($totalAfterDiskon).'</td>
				</tr>				
				';
			$no++;
			}
			?>
			<tr>
				<td colspan="18" align="right">Total</td>
				<td  style="border-color:#000;border:1px solid;" align="center"><?php echo 'Rp.'.number_format(round($sum_total)); ?></td>
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

	</body>
</html>