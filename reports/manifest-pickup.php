<?php
include ('../config/connection.php');
include ('../config/encrypt.php');
include ('../config/library.php');
include ('phpqrcode/qrlib.php'); 
	
$key = "asdf$1011";
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
			$r_kode = mysqli_fetch_assoc($query_kode_manifest);

//QRcode::png("".$r['airwaybill_kode']."","image.png","L",2,2);
?>
<!DOCTYPE html>
<html>
	<head>
	<meta charset="UTF-8">
		<title>Pickup Manifest</title>
	</head>
	<body>
		<table cellspacing=0 width="100%">
			<tr>
				<td style="font-family:Calibri;text-align:center;font-size:16px;color:#000000;font-weight:bold;" colspan="8">PICKUP MANIFEST</td>
			</tr>
			<tr>
				<td colspan="10"></td>
			</tr>
			<tr>
				<td style="font-family:Calibri;text-align:center;font-size:16px;color:#000000;font-weight:bold;" colspan="8">MANIFEST CODE : <?php echo $r_kode['kode_manifest']; ?></td>
		  	</tr>
			<tr>
			  <td style=min-width:50px><span style="font-family:Calibri;text-align:right;font-size:14px;color:#000000;">TANGGAL :</span></td>
			  <td colspan="8" style=min-width:50px><span style="font-family:Calibri;font-size:14px;color:#000000;"><?php echo''.$tanggal.'';?></span></td>
		    </tr>
			<tr>
				<td style=min-width:50px><span style="font-family:Calibri;text-align:right;font-size:14px;color:#000000;">KURIR :</span></td>
				<td colspan="8" style=min-width:50px><span style="font-family:Calibri;font-size:14px;color:#000000;"> <?php echo''.strtoupper($r['namalengkap']).'';?></span></td>
		  </tr>		  
			<tr>
				<td colspan="8">&nbsp;</td>
			</tr>
			<tr>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border:1px solid;">NO</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">AWB</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">CUSTOMER</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">KOLI</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">BERAT</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">D/P</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">DITERIMA OLEH</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">TGL</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Alasan(Jika Terlambat)</td>
			</tr>
			<?php
			$no=1;
			$result = mysqli_query($con,"select airwaybill_kode,alasan_terlambat, x_namakustomer(id_kustomer) as kustomer, x_namakotabesar(lokasi_asal) as lokasi_asal, x_namakotabesar(lokasi_tujuan) as lokasi_tujuan, collie, coalesce(berat_aktual,berat_barang) as berat_aktual, case when komoditi='1' then 'Paket' else 'Dokumen' end as komoditi, pickup_date from pengiriman where pickup_by='$pickup_by' and DATE_FORMAT(pickup_date,'%Y-%m-%d')='$pickup_date' order by created_date asc");
			while($t=mysqli_fetch_assoc($result)){
				echo'
				<tr>
					<td style="font-family:Calibri;text-align:center;font-size:14px;font-color:#000000;border-color:#000;border:1px solid;">'.$no.'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['airwaybill_kode'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['kustomer'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['collie'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['berat_aktual'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['komoditi'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;"></td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.tgl_indo($t['pickup_date']).'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['alasan_terlambat'].'</td>
				</tr>';
			$no++;
			}
			?>
			<tr>
          			<td colspan="8">&nbsp;</td>
				</tr>
                <?php
				$biaya = mysqli_query($con, "select case when count(id_pengiriman)<=10 then count(id_pengiriman)*2000 when count(id_pengiriman)>10 and count(id_pengiriman)<=15 then 20000+1300 end as jml from pengiriman a inner join user b on b.id_user=a.pickup_by where a.pickup_by='$pickup_by' and DATE_FORMAT(a.pickup_date,'%Y-%m-%d')='$pickup_date'");

				$costing = mysqli_query($con,"select sum(costing_pickup_fee) as costing_pickup_fee, sum(costing_pickup_parking) as costing_pickup_parking, sum(costing_pickup_toll) as costing_pickup_toll, sum(costing_men_power) as costing_men_power, 
					
					 (select costing_pickup_fee)+(select costing_pickup_parking)+(select costing_men_power)+(select costing_pickup_toll)  as total
					
					from pengiriman where pickup_by='$pickup_by' and DATE_FORMAT(pickup_date,'%Y-%m-%d')='$pickup_date' ");

				
$c = mysqli_fetch_assoc($costing);

				?>
                
            	<tr>
          			<td colspan="2" style="font-family:Calibri;font-size:14px;color:#000000;text-align:center;">TTD KURIR / DRIVER</td>
                    <td>&nbsp;</td>
                    <td colspan="2" style="font-family:Calibri;font-size:14px;color:#000000;text-align:center;">TTD MGR / SPV</td>
                    <td>&nbsp;</td>
            		<td style="font-family:Calibri;font-size:14px;color:#000000;border:1px solid;">BENSIN</td>
            		<td style="font-family:Calibri;text-align:right;font-size:14px;color:#000000;border:1px solid;"><?php echo number_format(round($c['costing_pickup_fee'])); ?></td>
				</tr>
                <tr>
          			<td colspan="6">&nbsp;</td>
            		<td style="font-family:Calibri;font-size:14px;color:#000000;border:1px solid;">PARKIR</td>
            		<td style="font-family:Calibri;text-align:right;font-size:14px;color:#000000;border:1px solid;"><?php echo number_format(round($c['costing_pickup_parking'])); ?></td>
				</tr>
                <tr>
          			<td colspan="6">&nbsp;</td>
            		<td style="font-family:Calibri;font-size:14px;color:#000000;border:1px solid;">TOL</td>
            		<td style="font-family:Calibri;text-align:right;font-size:14px;color:#000000;border:1px solid;"><?php echo number_format(round($c['costing_pickup_toll'])); ?></td>
				</tr>
              
                
                <tr>
          			<td>(</td>
                    <td style="text-align:right;">)</td>
                    <td>&nbsp;</td>
                    <td>(</td>
                    <td style="text-align:right;">)</td>
                    <td>&nbsp;</td>
            		<td style="font-family:Calibri;font-size:14px;color:#000000;border:1px solid;">KULI</td>
            		<td style="font-family:Calibri;text-align:right;font-size:14px;color:#000000;border:1px solid;"><?php echo number_format(round($c['costing_men_power'])); ?></td>
				</tr>               
                <tr>
          			<td colspan="6">&nbsp;</td>
            		<td style="font-family:Calibri;font-size:14px;color:#000000;font-weight:bold;border:1px solid;">TOTAL</td>
            		<td style="font-family:Calibri;text-align:right;font-size:14px;color:#000000;border:1px solid;">Rp <?php echo number_format(round($c['costing_men_power']+$c['costing_pickup_toll']+$c['costing_pickup_parking']+$c['costing_pickup_fee'])); ?></td>
				</tr>
                
			</table>
			<script type="text/javascript">
			window.print();			
			</script>
	</body>
</html>