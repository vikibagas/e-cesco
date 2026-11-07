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

$qu = mysqli_query($con, "select b.namalengkap, b.level from pengiriman a inner join user b on b.id_user=a.courier_by where a.courier_by='$courier_by' and courier_date='$courier_date'");


$currentDate = date('Y-m-d'); 

if($courier_date > $currentDate){
	$tanggal_courier = $currentDate;
}else{
	$tanggal_courier = $courier_date;
}


$r = mysqli_fetch_assoc($qu);

$query_kode_manifest = mysqli_query($con, "select kode_manifest from manifest 
	where tanggal='$tanggal_courier' and manifest_delivery_by = '$courier_by'");
$r_kode = mysqli_fetch_assoc($query_kode_manifest);


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
			<style>
		    
		    @media print {
		    .pageBreak {
		        page-break-after: always;
		    }
		}
		    </style>
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
				
				<?php
				$no=1;
				$result = mysqli_query($con,"select id_pengiriman,airwaybill_kode,alasan_terlambat, x_namakustomer(id_kustomer) as kustomer, x_namakotabesar(lokasi_asal) as lokasi_asal, x_namakotabesar(lokasi_tujuan) as lokasi_tujuan, collie, ifnull(berat_aktual,berat_barang) as berat_aktual,collie, case when komoditi='1' then 'Paket' else 'Dokumen' end as komoditi, delivery_name, 
					kota_asal ,kota_tujuan,delivery_status,delivery_date,delivery_time,x_user(".$courier_by.") as nama_kurir
				 from pengiriman where courier_by='$courier_by' and DATE_FORMAT(courier_date,'%Y-%m-%d')='$courier_date' order by x_namakustomer(id_kustomer) asc");
				$kustomer_akhir = '0';
						echo '
							<tr >
									<td style="min-width:35px;max-width:35px;font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border:1px solid;">NO</td>
									<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">AWB</td>
				                    <td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">CUSTOMER</td>
				                    <td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">KOTA ASAL</td>
				                    <td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">KOTA TUJUAN</td>
									
									
									
									
									<td style="min-width:110px;max-width:110px;font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">NAMA PENERIMA</td>
									<td style="min-width:110px;max-width:110px;font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">JABATAN</td>
									<td style="min-width:60px;max-width:60px;font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">PARAF</td>
									
									<td style="min-width:90px;max-width:90px;font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">TANGGAL</td>
									<td style="min-width:90px;max-width:90px;font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">JAM</td>
									<td style="min-width:110px;max-width:150px;font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">ALASAN RETURN</td>
								</tr>

						';
				while($t=mysqli_fetch_assoc($result)){
					$jumlah_rowspan = $t['collie']+2;
					
					$kustomer_pakem = $t['kustomer'];

					if($kustomer_akhir != $kustomer_pakem && $kustomer_akhir != '0'){
					$no = 1;
								echo '


									<tr class="pageBreak" style="background-color:#grey;">


										<td style="font-family:Calibri;text-align:center;font-size:36px;color:#000000;border-color:#000;border-top:0px solid;border-right:0px solid;border-bottom:0px solid; " >&nbsp;</td>

										<td style="font-family:Calibri;text-align:center;font-size:36px;color:#000000;border-color:#000;border-top:1px solid;border-right:0px solid;border-bottom:0px solid; " colspan="4">&nbsp;</td>

										<td style="font-family:Calibri;text-align:center;font-size:36px;color:#000000;border-color:#000;border-top:0px solid;border-right:0px solid;border-bottom:0px solid; " colspan="6">&nbsp;</td>
									</tr>

									<tr >


										<td style="font-family:Calibri;text-align:center;font-size:16px;color:#000000;border-color:#000;border-top:0px solid;border-right:0px solid;border-bottom:0px solid; " colspan="11">&nbsp;</td>
									</tr>
									<tr >
									<tr >


										<td style="font-family:Calibri;text-align:center;font-size:16px;color:#000000;border-color:#000;border-top:0px solid;border-right:0px solid;border-bottom:0px solid; " colspan="11"><b>DELIVERY MANIFEST</b></td>
									</tr>
									<tr >


										<td style="font-family:Calibri;text-align:center;font-size:16px;color:#000000;border-color:#000;border-top:0px solid;border-right:0px solid;border-bottom:0px solid; " colspan="11"><b>MANIFEST CODE : '.$r_kode['kode_manifest'].' </b></td>
									</tr>
									<tr >


										<td style="font-family:Calibri;text-align:left;font-size:14px;color:#000000;border-color:#000;border-top:0px solid;border-right:0px solid;border-bottom:0px solid; " colspan="11">TANGGAL : '.$tanggal.' </td>
									</tr>
									<tr >


										<td style="font-family:Calibri;text-align:left;font-size:14px;color:#000000;border-color:#000;border-top:0px solid;border-right:0px solid;border-bottom:0px solid; " colspan="11">KURIR : '.strtoupper($t['nama_kurir']).' </td>
									</tr>
									<tr >


										<td style="font-family:Calibri;text-align:left;font-size:14px;color:#000000;border-color:#000;border-top:0px solid;border-right:0px solid;border-bottom:0px solid; " colspan="11">&nbsp;</td>
									</tr>


									<tr >
									<td style="min-width:35px;max-width:35px;font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border:1px solid;">NO</td>
									<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">AWB</td>
				                    <td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">CUSTOMER</td>
				                    <td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">KOTA ASAL</td>
				                    <td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">KOTA TUJUAN</td>
									
									
									
									
									<td style="min-width:110px;max-width:110px;font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">NAMA PENERIMA</td>
									<td style="min-width:110px;max-width:110px;font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">JABATAN</td>
									<td style="min-width:60px;max-width:60px;font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">PARAF</td>
									
									<td style="min-width:90px;max-width:90px;font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">TANGGAL</td>
									<td style="min-width:90px;max-width:90px;font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">JAM</td>
									<td style="min-width:110px;max-width:150px;font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">ALASAN RETURN</td>
								</tr>
								';
							}
					echo'
					<tr>
						<td rowspan='.$jumlah_rowspan.' style="font-family:Calibri;text-align:center;font-size:14px;font-color:#000000;border-color:#000;border-left:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$no.'</td>
						<td style="font-family:Calibri;text-align:center;font-size:16px;color:#000000;border-color:#000;border-right:1px solid;border-top:1px solid;border-bottom:1px solid;"><b>'.$t['airwaybill_kode'].'</b></td>
						<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['kustomer'].'</td>
						<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['kota_asal'].'</td>
						<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['kota_tujuan'].'</td>

						';

							
						echo '
						
						
						
						<td rowspan='.$jumlah_rowspan.'  style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.($t['delivery_name'] != '' ?  $t['delivery_name'] : '&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;').'</td>
						<td rowspan='.$jumlah_rowspan.'  style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.($t['delivery_status'] != '' ?  $t['delivery_status'] : '&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;').'</td>
						
						<td rowspan='.$jumlah_rowspan.' style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </td>
						<td rowspan='.$jumlah_rowspan.' style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.($t['delivery_date'] == '0000-00-00' ? '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' : tgl_indo($t['delivery_date'])).'</td>
						<td rowspan='.$jumlah_rowspan.' style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'. $t['delivery_time'].'</td>
						<td rowspan='.$jumlah_rowspan.' style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
						
						
					</tr>


					<tr style="background:#eee;">
						<td  style="font-family:Calibri;text-align:center;font-size:12px;color:#000000;border-color:#000;"><b>KOLI KE</b></td>
						<td  style="font-family:Calibri;text-align:center;font-size:12px;color:#000000;border-color:#000;"><b>BERAT (KG)</b></td>
						<td  style="font-family:Calibri;text-align:center;font-size:12px;color:#000000;border-color:#000;"><b>PXLXT(CM)</b></td>
						<td  style="font-family:Calibri;text-align:center;font-size:12px;color:#000000;border-color:#000;border-right:1px solid;"><b>BERAT DIHITUNG(KG)</b></td>
					</tr>

					';
					
					$r = mysqli_query($con," select koli_ke,berat, p,l,t,berat_real 
							 from detail_collie where id_pengiriman=".$t['id_pengiriman']."  ");

					$border_bottom_terakhir = "border-left:1px solid;";

							while($collie=mysqli_fetch_assoc($r)){

								echo '<tr>';
								
									echo '<td style="font-family:Calibri;text-align:center;font-size:12px;color:#000000;border-color:#000;" >'.$collie['koli_ke'].'</td>';
									
									echo '<td style="font-family:Calibri;text-align:center;font-size:12px;color:#000000;border-color:#000;"> '.$collie['berat'].'</td>';
									
									echo '<td style="font-family:Calibri;text-align:center;font-size:12px;color:#000000;border-color:#000;">'. $collie['p'].'x'.$collie['l'].'x'.$collie['t'].'</td>';
									
									echo '<td style="font-family:Calibri;text-align:center;font-size:12px;color:#000000;border-color:#000;border-right:1px solid;">'.$collie['berat_real'].'</td>';
								
								echo '</tr>';
							}

					
						

					$kustomer_akhir = $kustomer_pakem;

					 $kustomer_akhir;
					$no++;
				}
				echo '

									<tr >


										<td style="font-family:Calibri;text-align:center;font-size:36px;color:#000000;border-color:#000;border-top:0px solid;border-right:0px solid;border-bottom:0px solid; " >&nbsp;</td>

										<td style="font-family:Calibri;text-align:center;font-size:36px;color:#000000;border-color:#000;border-top:1px solid;border-right:0px solid;border-bottom:0px solid; " colspan="4">&nbsp;</td>

										<td style="font-family:Calibri;text-align:center;font-size:36px;color:#000000;border-color:#000;border-top:0px solid;border-right:0px solid;border-bottom:0px solid; " colspan="6">&nbsp;</td>
									</tr>
				';
				?>
				
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
				</tr>-->
                
			</table>
			<script type="text/javascript">
			window.print();			
			</script>
		</body>
</html>