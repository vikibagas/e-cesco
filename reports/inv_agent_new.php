<?php
include ('../config/connection.php');
include ('../config/encrypt.php');
include ('../config/library.php');
include ('phpqrcode/qrlib.php'); 
	


$month = $_GET['date'];
$sampai = $_GET['dateuntil'];

$agent = $_GET['id_agent'];

$cabang = mysqli_query($con, "select nama_cabang from cabang where id_cabang = $agent");
$c = mysqli_fetch_assoc($cabang);
		
/*$query = mysqli_query($con,"select b.namalengkap as namalengkap,  sum(coalesce(a.costing_delivery_fee,0)) + sum(coalesce(a.costing_delivery_parking,0)) + sum(coalesce(a.costing_delivery_toll,0)) + sum(coalesce(a.costing_delivery_menpower,0)) as total_cost from pengiriman a left join user b on (b.id_user=a.courier_by)  where courier_by='$id_user' and DATE_FORMAT(courier_date,'%Y-%m-%d') BETWEEN '$dari' AND '$sampai' and x_intercity(lokasi_tujuan)='Y'  order by created_date asc");*/
if($agent == 49 || $agent == 58 || $agent == 49 || $agent == 21 || $agent == 22 || $agent == 42){
		 $total = "
                             SELECT                                 
                                SUM(if(a.paid_reject = '1',if(if(berat_aktual is null or berat_aktual < 1,  berat_barang, berat_aktual) < ifnull(d.min_kg,ifnull(e.min_kg,f.min_kg)), (ifnull(d.min_kg,ifnull(e.min_kg,f.min_kg))*ifnull(d.price,ifnull(e.price,f.price)))+a.biaya_packing+a.biaya_tambahan , (if(berat_aktual is null or berat_aktual < 1,  berat_barang, berat_aktual)*ifnull(d.price,ifnull(e.price,f.price)))+a.biaya_packing+a.biaya_tambahan ),0)) AS total 

                            FROM pengiriman a 
                            LEFT JOIN kota b ON b.id_kota=a.lokasi_tujuan 
                            OR b.id_kota=a.lokasi_asal 
                            LEFT JOIN cabang c ON c.id_kota=b.id_kota OR b.hub=c.id_kota 
                        
                        LEFT JOIN rate_agent d ON d.id_cabang=CASE WHEN x_intercity(a.lokasi_asal)='Y' THEN '39' ELSE c.id_cabang END AND d.id_kota=a.lokasi_tujuan AND d.id_pelayanan=CASE WHEN a.service > 3 THEN 3 else a.service end 

                            LEFT JOIN rate_agent_inbound e ON e.id_kota=a.lokasi_asal  and e.id_pelayanan=CASE WHEN a.service > 3 THEN 3 else a.service end and e.id_cabang=CASE WHEN x_intercity(a.lokasi_tujuan)='Y' THEN 0 else 99 end

                            LEFT JOIN rate_agent_cross f ON f.id_kota_asal=CASE WHEN x_intercity(a.lokasi_asal)!='Y' THEN a.lokasi_asal ELSE 0 END AND f.id_kota_tujuan=CASE WHEN x_intercity(a.lokasi_tujuan)!='Y' THEN a.lokasi_tujuan ELSE 0 END AND f.id_pelayanan=CASE WHEN a.service > 3 THEN 3 else a.service end 

                            WHERE (c.id_cabang=$agent AND a.tanggal BETWEEN '$month-01' AND '$month-31' and service != 6 and a.status != 7 ) and ( pickup_by_agent = $agent or delivery_by_agent = $agent or pickup_by_agent is null or delivery_by_agent is null or pickup_by_agent != $agent or delivery_by_agent != $agent)";
	}
	else{
		  $total = "
                             SELECT                                 
                                SUM(if(a.paid_reject = '1',if(if(berat_aktual is null or berat_aktual < 1,  berat_barang, berat_aktual) < ifnull(d.min_kg,ifnull(e.min_kg,f.min_kg)), (ifnull(d.min_kg,ifnull(e.min_kg,f.min_kg))*ifnull(d.price,ifnull(e.price,f.price)))+a.biaya_packing+a.biaya_tambahan , (if(berat_aktual is null or berat_aktual < 1,  berat_barang, berat_aktual)*ifnull(d.price,ifnull(e.price,f.price)))+a.biaya_packing+a.biaya_tambahan ),0)) AS total 

                            FROM pengiriman a 
                            LEFT JOIN kota b ON b.id_kota=a.lokasi_tujuan 
                            OR b.id_kota=a.lokasi_asal 
                            LEFT JOIN cabang c ON c.id_kota=b.id_kota OR b.hub=c.id_kota 
                        
                        LEFT JOIN rate_agent d ON d.id_cabang=CASE WHEN x_intercity(a.lokasi_asal)='Y' THEN '39' ELSE c.id_cabang END AND d.id_kota=a.lokasi_tujuan AND d.id_pelayanan=CASE WHEN a.service > 3 THEN 3 else a.service end 

                            LEFT JOIN rate_agent_inbound e ON e.id_kota=a.lokasi_asal  and e.id_pelayanan=CASE WHEN a.service > 3 THEN 3 else a.service end and e.id_cabang=CASE WHEN x_intercity(a.lokasi_tujuan)='Y' THEN 0 else 99 end

                            LEFT JOIN rate_agent_cross f ON f.id_kota_asal=CASE WHEN x_intercity(a.lokasi_asal)!='Y' THEN a.lokasi_asal ELSE 0 END AND f.id_kota_tujuan=CASE WHEN x_intercity(a.lokasi_tujuan)!='Y' THEN a.lokasi_tujuan ELSE 0 END AND f.id_pelayanan=CASE WHEN a.service > 3 THEN 3 else a.service end 

                            WHERE (c.id_cabang=$agent AND a.tanggal BETWEEN '$month-01' AND '$month-31' and service != 6 and a.status != 7 ) and ( pickup_by_agent = $agent or delivery_by_agent = $agent or pickup_by_agent is null or delivery_by_agent is null or pickup_by_agent != $agent or delivery_by_agent != $agent)";
	}
$query = mysqli_query($con,$total);							
$r = mysqli_fetch_assoc($query);




//header("Content-type: application/vnd-ms-excel");
//header('Content-Disposition: attachment; filename="'.$month.'_'.$c['nama_cabang'].'.xls"');

?>
<!DOCTYPE html>
<html>
	<head>
	<meta charset="UTF-8">
		<title>INVOICE AGENT</title>
	</head>
	<body>
		<table cellspacing=0 width="100%">
			<tr>
				<td style="font-family:Calibri;text-align:center;font-size:16px;color:#000000;font-weight:bold;" colspan="8">INVOICE AGENT</td>
			</tr>
			<tr>
				<td colspan="10"></td>
			</tr>
			<tr>
			  <td style=min-width:50px><span style="font-family:Calibri;text-align:right;font-size:16px;color:#000000;font-weight:bold;">TANGGAL :</span></td>
			  <td  style=min-width:50px><span style="font-family:Calibri;font-size:16px;color:#000000;font-weight:bold;"><?php echo''.$month; ?></span></td>
		    </tr>
			<tr>
				<td style=min-width:50px><span style="font-family:Calibri;text-align:right;font-size:16px;color:#000000;font-weight:bold;">Agent :</span></td>
				<td colspan="8" style=min-width:50px><span style="font-family:Calibri;font-size:16px;color:#000000;font-weight:bold;"> <?php echo''.strtoupper($c['nama_cabang']).'';?></span></td>
		  </tr>		  
			<tr>
				<td colspan="8">&nbsp;</td>
			</tr>
			<tr>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border:1px solid;">NO</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border:1px solid;">Pickup Date</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">AWB</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">komoditi</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Service</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Custmer's Name</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Consignee</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Origin</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Destination</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Delivered by</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Tanggal diterima</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Lead Time</td>								
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Weight</td>				
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Price</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Min Weight</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Packing</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Add Charge</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Detail Add Charge</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Total</td>				
			</tr>
			<?php
			$no=1;
			if($agent == 49 || $agent == 58 || $agent == 49 || $agent == 21 || $agent == 22 || $agent == 42){
                         $cari = "
							SELECT 
								id_pengiriman, 
                                date(tanggal) as tanggal, 
                                airwaybill_kode, 
                                case when a.komoditi='1' then 'Paket' else 'Dokumen' end as komoditi, 
                                x_service(service) as service, 
                                case when a.service=5 then 3 else a.service end as srv,
                                nama_pengirim, 
                                loc_code, 
                                x_namakustomer(id_kustomer) as namakustomer, 
                                nama_penerima, 
                                lokasi_asal, 
                                x_namakotabesar(lokasi_asal) as lokasi_asal, 
                                lokasi_tujuan, 
                                x_namakotabesar(lokasi_tujuan) as lokasi_tujuan, 
                                alamat_penerima, 
                                delivery_name, 
                                delivery_date, 
                                if(delivery_date is null,'-', datediff(delivery_date, date(pickup_date))) as selisih_hari,
                                SUBSTRING_INDEX(ukuran_barang,'x',1) as length, SUBSTRING_INDEX(SUBSTRING_INDEX(ukuran_barang,'x',2),'x',-1) as width, SUBSTRING_INDEX(ukuran_barang,'x',-1) as height, 
                                if(berat_aktual is null or berat_aktual < 1,  berat_barang, berat_aktual) as berat_aktual,
                                berat_aktual as brt_akt,
                            	berat_barang as brt_brg,
                                ifnull(d.price,e.price) as price, 
                                ifnull(d.min_kg,e.min_kg) as min_kg, 
                                a.biaya_packing, 
                                a.biaya_tambahan, 
                                a.keterangan_biaya, 
                                if(a.paid_reject = '1',if(if(berat_aktual is null or berat_aktual < 1,  berat_barang, berat_aktual) < ifnull(d.min_kg,e.min_kg), (ifnull(d.min_kg,e.min_kg)*ifnull(d.price,e.price))+a.biaya_packing+a.biaya_tambahan , (if(berat_aktual is null or berat_aktual < 1,  berat_barang, berat_aktual)*ifnull(d.price,e.price))+a.biaya_packing+a.biaya_tambahan ),0) as total, 
                                d.rateagent_id,
                            	a.paid_reject,
                            	a.reject_reason
							FROM pengiriman a 
							INNER JOIN kota b ON b.id_kota=a.lokasi_tujuan 
							OR b.id_kota=a.lokasi_asal 
							INNER JOIN cabang c ON c.id_kota=b.id_kota OR b.hub=c.id_kota 
						LEFT JOIN rate_agent d ON d.id_cabang=CASE WHEN x_intercity(a.lokasi_asal)='Y' THEN '39' ELSE c.id_cabang END AND d.id_kota=a.lokasi_tujuan AND d.id_pelayanan=CASE WHEN a.service > 3 THEN 3 else a.service end AND d.komoditi=a.komoditi 
							LEFT JOIN rate_agent_inbound e ON e.id_kota=a.lokasi_asal  and e.id_pelayanan=CASE WHEN a.service > 3 THEN 3 else a.service end and e.komoditi=a.komoditi 
							WHERE (c.id_cabang='$agent' AND a.tanggal BETWEEN '$dari' AND '$sampai' and service != 6 and a.status != 7 ) and ( pickup_by_agent = '$agent' or delivery_by_agent = '$agent')
							";
						
						}
						else{
							 $cari = "
							SELECT 
								id_pengiriman, 
                                date(tanggal) as tanggal, 
                                airwaybill_kode, 
                                case when a.komoditi='1' then 'Paket' else 'Dokumen' end as komoditi, 
                                x_service(service) as service, 
                                case when a.service=5 then 3 else a.service end as srv,
                                nama_pengirim, 
                                loc_code, 
                                x_namakustomer(id_kustomer) as namakustomer, 
                                nama_penerima, 
                                a.lokasi_asal as id_asal, 
                                a.lokasi_tujuan as id_tujuan, 
                                x_namakotabesar(lokasi_asal) as lokasi_asal, 
                                lokasi_tujuan,
                                b.id_kota,
                                b.intercity as kota_in, 
                                x_namakotabesar(lokasi_tujuan) as lokasi_tujuan, 
                                alamat_penerima, 
                                delivery_name, 
                                delivery_date, 
                                if(delivery_date is null,'-', datediff(delivery_date, date(pickup_date))) as selisih_hari,
                                SUBSTRING_INDEX(ukuran_barang,'x',1) as length, SUBSTRING_INDEX(SUBSTRING_INDEX(ukuran_barang,'x',2),'x',-1) as width, SUBSTRING_INDEX(ukuran_barang,'x',-1) as height, 
                                 CASE 
										WHEN airwaybill_kode LIKE '001-%' THEN 0
										WHEN airwaybill_kode LIKE '002-%' THEN 1
									end as intercity,
								if( (select intercity) = 1,
										ceiling((select length) *  (select width) * (select height)/4000)
									,
										if (a.service = 5,
											ceiling((select length) *  (select width) * (select height)/4000)
										,
											ceiling((select length) *  (select width) * (select height)/6000)
										)
									) as volume,
								if(berat_aktual is null or berat_aktual < 1,  berat_barang, berat_aktual) as berat_real, 
                                if(berat_aktual is null or berat_aktual < 1,  berat_barang, berat_aktual) as berat_aktual,
                                berat_aktual as brt_akt,
                            	berat_barang as brt_brg,
                                ifnull(d.price,ifnull(e.price,f.price)) as price, 
                                ifnull(d.price,ifnull(e.price,f.price)) as pricee, 
                                ifnull(d.min_kg,ifnull(e.min_kg,f.min_kg)) as min_kg,
                                ifnull(d.min_kg,ifnull(e.min_kg,f.min_kg)) as min_kgg, 

                                 if ((select berat_real) >= (select volume), (select berat_real), (select volume) ) as berat,

									if ((select min_kgg) >= (select berat_real), (select min_kgg), (select berat) ) as berat_finish,

                                a.biaya_packing, 
                                a.biaya_tambahan, 
                                a.keterangan_biaya, 
                                if(a.paid_reject = '1',if(if(berat_aktual is null or berat_aktual < 1,  berat_barang, berat_aktual) < (select min_kgg), ((select min_kgg)*(select pricee))+a.biaya_packing+a.biaya_tambahan , (if(berat_aktual is null or berat_aktual < 1,  berat_barang, berat_aktual)*(select pricee))+a.biaya_packing+a.biaya_tambahan ),0) as total, 
                                ((select berat_finish) * (select pricee))+a.biaya_packing+a.biaya_tambahan as new_total ,
                                d.rateagent_id,
                                ifnull(d.rateagent_id,ifnull(e.rateagentinbound_id,f.rate_id)) as idrate,
                                d.rateagent_id as idrate_out,
                            	e.rateagentinbound_id as idrate_in,	
                            	f.rate_id as idrate_cross,
                            	CASE WHEN x_intercity(a.lokasi_asal)='Y' THEN '39' ELSE c.id_cabang END as cabang_asal,
                            	a.paid_reject,
                            	a.reject_reason
							FROM pengiriman a 
							LEFT JOIN kota b ON b.id_kota=a.lokasi_tujuan 
							OR b.id_kota=a.lokasi_asal 
							LEFT JOIN cabang c ON c.id_kota=b.id_kota OR b.hub=c.id_kota 
						
						LEFT JOIN rate_agent d ON d.id_cabang=CASE WHEN x_intercity(a.lokasi_asal)='Y' THEN '39' ELSE c.id_cabang END AND d.id_kota=a.lokasi_tujuan AND d.id_pelayanan=CASE WHEN a.service > 3 THEN 3 else a.service end 

							LEFT JOIN rate_agent_inbound e ON e.id_kota=a.lokasi_asal  and e.id_pelayanan=CASE WHEN a.service > 3 THEN 3 else a.service end and e.id_cabang=CASE WHEN x_intercity(a.lokasi_tujuan)='Y' THEN 0 else 99 end

							LEFT JOIN rate_agent_cross f ON f.id_kota_asal=CASE WHEN x_intercity(a.lokasi_asal)!='Y' THEN a.lokasi_asal ELSE 0 END AND f.id_kota_tujuan=CASE WHEN x_intercity(a.lokasi_tujuan)!='Y' THEN a.lokasi_tujuan ELSE 0 END AND f.id_pelayanan=CASE WHEN a.service > 3 THEN 3 else a.service end 

							WHERE (c.id_cabang=$agent AND a.tanggal BETWEEN '$month-01' AND '$month-31' and service != 6 and a.status != 7 ) and ( pickup_by_agent = $agent or delivery_by_agent = $agent or pickup_by_agent is null or delivery_by_agent is null or pickup_by_agent != $agent or delivery_by_agent != $agent) order by tanggal ASC, airwaybill_kode ASC
							";

						}

			//echo $cari;
			$sum_total = 0;						
			$result = mysqli_query($con, $cari);
			while($t=mysqli_fetch_assoc($result)){
				$new_total = ($t['paid_reject'] == 1 ? $t['new_total'] : 0);
							$sum_total = $sum_total + $new_total;
				echo'
				<tr>
					<td style="font-family:Calibri;text-align:center;font-size:14px;font-color:#000000;border-color:#000;border:1px solid;">'.$no.'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['tanggal'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['airwaybill_kode'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['komoditi'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['service'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['namakustomer'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['nama_penerima'].'</td>					
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['lokasi_asal'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['lokasi_tujuan'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['delivery_name'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['delivery_date'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['selisih_hari'].' hari</td>					
					
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['berat_aktual'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['price'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['min_kg'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['biaya_packing'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['biaya_tambahan'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['keterangan_biaya'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.($t['paid_reject'] == 1 ? $t['new_total'] : 0).'</td>
				</tr>					
				';
			$no++;
			}
			?>
			<tr>
				<td colspan="18" style="font-family:Calibri;text-align:right;font-size:16px;color:#000000;font-weight:bold;" align="right">Total</td>
				<td  style="font-family:Calibri;text-align:right;font-size:16px;color:#000000;font-weight:bold;" align="right"><?php echo 'Rp.'.number_format($sum_total); ?></td>
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