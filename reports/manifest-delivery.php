<?php
include ('../config/connection.php');
include ('../config/encrypt.php');
include ('../config/library.php');
include ('phpqrcode/qrlib.php'); 

$key = "asdf$1011";
$encrypt = "$_GET[id]";
$id = base64_decrypt($encrypt,$key);

$pieces = explode('-', $id);
 $courier_by = $pieces['0'];
$courier_date = implode('-', array_slice($pieces, 1, 3));

$query = mysqli_query($con, "select b.namalengkap, b.level from pengiriman a inner join user b on b.id_user=a.courier_by where a.courier_by='$courier_by' and courier_date='$courier_date'");

 

$r = mysqli_fetch_assoc($query);

$query_kode_manifest = mysqli_query($con, "select kode_manifest from manifest 
	where tanggal='$courier_date' and manifest_delivery_by = '$courier_by'");
$r_kode = mysqli_fetch_assoc($query_kode_manifest);

$currentDate = date('Y-m-d');
if($courier_date > $currentDate){
	$tanggal = tgl_indo($currentDate);
}else{
	$tanggal = tgl_indo($courier_date);
}


?>
<!DOCTYPE html>
	<html>
		<head>
			<meta charset="UTF-8">
			<title>Delivery Manifest</title>
		</head>
		<body>
			<table cellspacing=0 width="100%">
				<tr>
					<td style="font-family:Calibri;text-align:center;font-size:16px;color:#000000;font-weight:bold;" colspan="8">DELIVERY MANIFEST</td>
                </tr>
				<tr>
					<td colspan="10"></td>
				</tr>
				<tr>
					<td style="font-family:Calibri;text-align:center;font-size:16px;color:#000000;font-weight:bold;" colspan="8">MANIFEST CODE : <?php echo $r_kode['kode_manifest']; ?></td>
		  		</tr>
				<tr>
					<td style=min-width:50px colspan="7"></td>
		  		</tr>
				<tr>
			  		<td style=min-width:50px><span style="font-family:Calibri;text-align:right;font-size:14px;color:#000000;">TANGGAL :</span></td>
			  		<td colspan="7" style=min-width:50px><span style="font-family:Calibri;font-size:14px;color:#000000;"><?php echo''.$tanggal.'';?></span></td>
		  		</tr>
				<tr>
					<td style=min-width:50px><span style="font-family:Calibri;text-align:right;font-size:14px;color:#000000;">KURIR :</span></td>
					<td colspan="7" style=min-width:50px><span style="font-family:Calibri;font-size:14px;color:#000000;"> <?php echo''.strtoupper($r['namalengkap']).'';?></span></td>
		  		</tr>
				<tr>
					<td colspan="8">&nbsp;</td>
				</tr>
				<tr>
					<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border:1px solid;">NO</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">AWB</td>
                    <td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">CUSTOMER</td>
                    <td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">KOTA ASAL</td>
                    <td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">KOTA TUJUAN</td>
					
					
					<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">BERAT & KOLI</td>
					
					<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">DITERIMA OLEH</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">JABATAN</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">PARAF</td>
					
					<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">TGL TERIMA</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">JAM TERIMA</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">ALASAN(JIKA) RETURN</td>
				</tr>
				<?php
				$no=1;
				$result = mysqli_query($con,"select id_pengiriman,airwaybill_kode,alasan_terlambat, x_namakustomer(id_kustomer) as kustomer, x_namakotabesar(lokasi_asal) as lokasi_asal, x_namakotabesar(lokasi_tujuan) as lokasi_tujuan, collie, ifnull(berat_aktual,berat_barang) as berat_aktual, case when komoditi='1' then 'Paket' else 'Dokumen' end as komoditi, delivery_name, delivery_date,delivery_time
					kota_asal ,kota_tujuan,delivery_status,delivery_date
				 from pengiriman where courier_by='$courier_by' and DATE_FORMAT(courier_date,'%Y-%m-%d')='$courier_date' order by created_date asc");
				while($t=mysqli_fetch_assoc($result)){
					echo'
					<tr>
						<td style="font-family:Calibri;text-align:center;font-size:14px;font-color:#000000;border-color:#000;border:1px solid;">'.$no.'</td>
						<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['airwaybill_kode'].'</td>
						<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['kustomer'].'</td>
						<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['kota_asal'].'</td>
						<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['kota_tujuan'].'</td>

						<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">';

							$r = mysqli_query($con," select koli_ke, p,l,t,berat_real 
							 from detail_collie where id_pengiriman=".$t['id_pengiriman']."  ");
							while($collie=mysqli_fetch_assoc($r)){
								echo 'koli '.$collie['koli_ke'].' = '. $collie['p'].'x'.$collie['l'].'x'.$collie['t'].'('.number_format($collie['berat_real']).' KG)'.'<br>';		
							}
						echo '
						</td>
						
						
						<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['delivery_name'].'</td>
						<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['delivery_status'].'</td>
						<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;"> </td>
						<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.tgl_indo($t['delivery_date']).'</td>
						<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['delivery_time'].'</td>
						<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['alasan_terlambat'].'</td>
					</tr>';
					$no++;
				}
				?>
				<tr>
          			<td colspan="8">&nbsp;</td>
				</tr>
                <?php
				$biaya = mysqli_query($con, "select case when count(id_pengiriman)<=10 then count(id_pengiriman)*2000 when count(id_pengiriman)>10 and count(id_pengiriman)<=15 then 20000+1300 end as jml from pengiriman a inner join user b on b.id_user=a.courier_by where a.delivery_by='$courier_by' and DATE_FORMAT(a.delivery_date,'%Y-%m-%d')='$courier_date'");

				$costing = mysqli_query($con,"select sum(costing_delivery_fee) as costing_delivery_fee, sum(costing_delivery_parking) as costing_delivery_parking, sum(costing_delivery_toll) as costing_delivery_toll, sum(costing_delivery_menpower) as costing_delivery_menpower, 
					
					 (select costing_delivery_fee)+(select costing_delivery_parking)+(select costing_delivery_menpower)+(select costing_delivery_toll)  as total
					
					from pengiriman where courier_by='$courier_by' and DATE_FORMAT(ifnull(manifest_delivery_date,courier_date),'%Y-%m-%d')='$courier_date' ");
$c = mysqli_fetch_assoc($costing);

				?>
                
    <!--        	<tr>
          			<td colspan="2" style="font-family:Calibri;font-size:14px;color:#000000;text-align:center;">TTD KURIR / DRIVER</td>
                    <td>&nbsp;</td>
                    <td colspan="2" style="font-family:Calibri;font-size:14px;color:#000000;text-align:center;">TTD MGR / SPV</td>
                    <td>&nbsp;</td>
            		<td style="font-family:Calibri;font-size:14px;color:#000000;border:1px solid;">BENSIN</td>
            		<td style="font-family:Calibri;text-align:right;font-size:14px;color:#000000;border:1px solid;"><?php echo number_format(round($c['costing_delivery_fee'])); ?></td>
				</tr>
                <tr>
          			<td colspan="6">&nbsp;</td>
            		<td style="font-family:Calibri;font-size:14px;color:#000000;border:1px solid;">PARKIR</td>
            		<td style="font-family:Calibri;text-align:right;font-size:14px;color:#000000;border:1px solid;"><?php echo number_format(round($c['costing_delivery_parking'])); ?></td>
				</tr>
                <tr>
          			<td colspan="6">&nbsp;</td>
            		<td style="font-family:Calibri;font-size:14px;color:#000000;border:1px solid;">TOL</td>
            		<td style="font-family:Calibri;text-align:right;font-size:14px;color:#000000;border:1px solid;"><?php echo number_format(round($c['costing_delivery_toll'])); ?></td>
				</tr>
              
                
                <tr>
          			<td>(</td>
                    <td style="text-align:right;">)</td>
                    <td>&nbsp;</td>
                    <td>(</td>
                    <td style="text-align:right;">)</td>
                    <td>&nbsp;</td>
            		<td style="font-family:Calibri;font-size:14px;color:#000000;border:1px solid;">KULI</td>
            		<td style="font-family:Calibri;text-align:right;font-size:14px;color:#000000;border:1px solid;"><?php echo number_format(round($c['costing_delivery_menpower'])); ?></td>
				</tr>               
                <tr>
          			<td colspan="6">&nbsp;</td>
            		<td style="font-family:Calibri;font-size:14px;color:#000000;font-weight:bold;border:1px solid;">TOTAL</td>
            		<td style="font-family:Calibri;text-align:right;font-size:14px;color:#000000;border:1px solid;">Rp <?php echo number_format(round($c['costing_delivery_menpower']+$c['costing_delivery_toll']+$c['costing_delivery_parking']+$c['costing_delivery_fee'])); ?></td>
				</tr>
                
			</table>-->
			<script type="text/javascript">
			window.print();			
			</script>
		</body>
</html>