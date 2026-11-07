<?php
include ('../../config/connection.php');
include ('../../config/encrypt.php');
include ('../../config/library.php');
$dari = $_GET['dari'];
$sampai = $_GET['sampai'];

header("Content-type: application/vnd-ms-excel");
header('Content-Disposition: attachment; filename="'.$dari.'-'.$sampai.'.Report.xls"');

?>
<!DOCTYPE html>
<html>
	<head>
	<meta charset="UTF-8">
		<title>Airwaybill Customer</title>
	</head>
	<body>
		<table cellspacing=0 width="100%">
			<tr>
				<td style="font-family:Calibri;text-align:center;font-size:16px;color:#000000;font-weight:bold;" colspan="17">Airwaybill Customer</td>
			</tr>
			<tr>
				<td colspan="10"></td>
			</tr>	  
			<tr>
				<td colspan="8">&nbsp;</td>
			</tr>
			<tr>
				<th style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border:1px solid;" rowspan="2">NO </th>
				<th style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border:1px solid;" colspan="4">Data</th>
				<th style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border:1px solid;" colspan="6">Pengirim</th>
				<th style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border:1px solid;" colspan="6">Penerima </th>
				<th style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border:1px solid;" colspan="3">Detail Barang </th>
				
				<th style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border:1px solid;" rowspan="2">Packing</th>
				<th style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border:1px solid;" rowspan="2">Asuransi </th>				
				<th style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border:1px solid;" rowspan="2">Invoice </th>
				<th style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border:1px solid;" rowspan="2">DO Kembali </th>				
			</tr>
			<tr>
				<th style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border:1px solid;">Pickup</th>
				<th style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border:1px solid;">Airwaybill</th>
				<th style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border:1px solid;">Komoditi</th>
				<th style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border:1px solid;">Service type</th>
				<th style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border:1px solid;">Requestor</th>
				<th style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border:1px solid;">Loc Code/PID</th>
				<th style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border:1px solid;">Pengirim</th>
				<th style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border:1px solid;">Nama Perusahaan</th>
				<th style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border:1px solid;">Kota Asal</th>
				<th style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border:1px solid;">Alamat lengkap asal</th>				
				<th style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border:1px solid;">Nama Perusahaan</th>
				<th style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border:1px solid;">Consignee</th>
				<th style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border:1px solid;">Kota Tujuan</th>
				<th style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border:1px solid;">Alamat lengkap penerima</th>
				<th style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border:1px solid;">Nama Penerima</th>
				<th style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border:1px solid;">Tanggal</th>
				<th style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border:1px solid;">BERAT</th>
				<th style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border:1px solid;">Volume</th>
				<th style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border:1px solid;" >Koli </th>
			</tr>
			<?php
			$no=1;
			//$tipe = mysqli_real_escape_string($con, $_GET['tipe']);
						
						
						
							$cari = "SELECT 
									a.id_pengiriman,
									airwaybill_kode,
									a.tanggal,
									DATE_FORMAT(a.created_date, '%H:%i') as jam,
									pickup_date,
									case
										when a.komoditi = '1' then 'Paket'
										else 'Dokumen'
									end as komoditi,
									jenis_barang,
									perusahaan_pengirim,
									x_namakustomer(a.id_kustomer) as namakustomer,
									alamat_pengirim,
									nama_pengirim,
									id_pengiriman,
									date(pickup_date) as tanggal,
									airwaybill_kode,
									x_service(service) as service,
									nama_pengirim,
									collie,
									a.loc_code,
									x_namakustomer(a.id_kustomer) as namakustomer,
									nama_penerima,
									case
										when inbound = 'Y' then 'INBOUND'
										when inbound = 'N' then 'OUTBOUND'
										else ''
									end as inbound,
									perusahaan_pengirim,
									a.lokasi_asal as id_lokasi_asal,
									if(a.id_kota_tujuan > 0,
										a.kota_asal,
										x_namakotabesar(lokasi_asal)) as lokasi_asal,
									alamat_pengirim,
									perusahaan_penerima,
									lokasi_tujuan,
									x_namakotabesar(lokasi_tujuan) as lokasi_tujuan,
									jenis_barang,
									alamat_penerima,
									surat_jalan,
									delivery_name,
									if(a.status=6,delivery_date,'') as delivery_date,
									SUBSTRING_INDEX(ukuran_barang, 'x', 1) as length,
									SUBSTRING_INDEX(SUBSTRING_INDEX(ukuran_barang, 'x', 2),
											'x',
											- 1) as width,
									SUBSTRING_INDEX(ukuran_barang, 'x', - 1) as height,
									if(berat_aktual is null or berat_aktual < 1,
										berat_barang,
										berat_aktual) as berat_aktual,
									case
										when packing_kayu = '1' then 'Packing Oleh Pemilik'
										when packing_kayu = '2' then 'Packing Kayu Oleh Cesco Log.'
										when packing_kayu = '3' then 'Non Packing Kayu Oleh Cesco Log.'
									end as packing_kayu,
									asuransi,
									a.status,
									CASE
									WHEN airwaybill_kode LIKE '001-%' THEN 0
									WHEN airwaybill_kode LIKE '002-%' THEN 1
								end as intercity,
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
									end as volume,
									kode_kustomer,
									i.status as status_invoice 
								FROM
									pengiriman a
										
										left  JOIN
									invoice i ON i.id_invoice = a.id_invoice 
									left join kustomer cs on cs.id_kustomer = a.id_kustomer 
								where a.tanggal between '$dari' and '$sampai'  
								";
						

			$result = mysqli_query($con, $cari);
			while($t=mysqli_fetch_assoc($result)){		
				if ($t['id_pelayanan'] == 5){
					$volume = ($t['length']*$t['width']*$t['height'])/4000;	
				}else{
					$volume = ($t['length']*$t['width']*$t['height'])/6000;
				}
				
				if($t['status']=='1'){
								$status = 'Booking';
							}elseif($t['status']=='2'){
								$status = 'Pickup';
							}elseif($t['status']=='3'){
								$status = 'On Outbound';
							}elseif($t['status']=='4'){
								$status = 'On Inbound';
							}elseif($t['status']=='5'){
								$status = 'On Delivery';
							}elseif($t['status']=='6'){
								$status = 'Delivered';
							}elseif($t['status']=='7'){
								$status = 'Cancel';
							}elseif($t['status']=='8'){
								$status = 'Return';
							}	

					$sttsInv = ($t['status_invoice'] == 'final') ? 'Y' : 'N';
						
				echo'
				<tr style="text-transform: uppercase;">
					<td style="font-family:Calibri;text-align:center;font-size:14px;font-color:#000000;border-color:#000;border:1px 
						solid;">'.$no.'</td>					
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['pickup_date'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['airwaybill_kode'].'</td>
					
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['komoditi'].', '.$t['jenis_barang'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px 	solid;border-bottom:1px solid;">'.$t['service'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['namakustomer'].'</td>					
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.strtoupper($t['loc_code']).($t['pid'] != '' ? ', PID = '. $t['pid'] : '').'</td>					
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['nama_pengirim'].'</td>					
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['perusahaan_pengirim'].'</td>					
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['lokasi_asal'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['alamat_pengirim'].'</td>					
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['perusahaan_penerima'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['nama_penerima'].'</td>					
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['lokasi_tujuan'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['alamat_penerima'].'</td>										
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.($t['status'] == 7 ? '<div color="red">Cancel</div>' : strtoupper($t['delivery_name']))  .'</td>					
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['delivery_date'].'</td>	
							

							';

								 $j=0;
								 $tb = 0;
								 
								 $q = mysqli_query($con, "select * from detail_collie where id_pengiriman='".$t['id_pengiriman']."' order by koli_ke asc ");
								 $ketemu = mysqli_num_rows($q);

								 if($ketemu > 0){
								 	echo '<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">';
								 	while($roww = mysqli_fetch_assoc($q)){
			 //for($i=1;$i<41;$i++){

									 	if($t['intercity'] == 1){
						              			$beratvol = $roww['p']*$roww['l']*$roww['t']/4000;
						              	}
						             	else{
						           			if($t['id_service'] == 5){
						           				$beratvol = $roww['p']*$roww['l']*$roww['t']/4000;
						           			}
						              		else{
						              			$beratvol = $roww['p']*$roww['l']*$roww['t']/6000;
						              		}
						              	}

									 	echo '
									 		
											 	
											 	'.$roww['berat'].'  <br>
											 	
											 
									 	';
									 	$j=$j+1;
									 	//$tb=$tb+$roww['berat_real'];
									 }
									 echo '</td>';
									 $qu = mysqli_query($con, "select * from detail_collie where id_pengiriman='".$t['id_pengiriman']."' order by koli_ke asc ");

									 echo '<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">';
										 while($roww = mysqli_fetch_assoc($qu)){
				 //for($i=1;$i<41;$i++){

										 	if($t['intercity'] == 1){
							              			$beratvol = $roww['p']*$roww['l']*$roww['t']/4000;
							              	}
							             	else{
							           			if($t['id_service'] == 5){
							           				$beratvol = $roww['p']*$roww['l']*$roww['t']/4000;
							           			}
							              		else{
							              			$beratvol = $roww['p']*$roww['l']*$roww['t']/6000;
							              		}
							              	}

										 	echo '								 										
												 	 '.$roww['p'].' x '.$roww['l'].' x '.$roww['t'].' ( '.number_format($beratvol,2).' )'.'<br>
												 	
												 
										 	';
										 	$j=$j+1;
										 	//$tb=$tb+$roww['berat_real'];
										 }
									echo '</td>';
									
								}else{
									echo '<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">';
										
										echo $t['berat_aktual'];
									echo '</td>';

									echo '<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">';

										echo ($t['length'] == '' ? 0 : $t['length']).'x'.($t['width'] == '' ? 0 : $t['width']).'x'.($t['height'] == '' ? 0 : $t['height']).'('. number_format($t['volume'],2).'  )';
									echo '</td>';
									}

								echo 
								'

							
							
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['collie'].'</td>						
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['packing_kayu'].'</td>				
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['asuransi'].'</td>				
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$sttsInv.'</td>	
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['surat_jalan'].'</td>				
				</tr>	
				';
					$sql = mysqli_query($con, "select id_pengiriman,product,aprc,part_n,part_n_desc,rma_no,qty,remark from asus_detail_catatan  where id_pengiriman='".$t['id_pengiriman']."'");
					$number = 0.5;
					//$number = $number-1;
						$cek = mysqli_num_rows($sql);
						if ($cek > 0){
								echo '
								<tr>  
								<td> &nbsp; </td>
								</tr>								
								<tr style="font-size:14px;">
							        <th ></th>
							       <th >NO</th>
							        <th>PRODUCT</th>
							        <th>APRC</th>
							        <th>PART N</th>
							        <th>PART DESC</th>
							        <th>RMA NO</th>
							        <th>QTY</th>
							      </tr>';
							
							      while ($row = mysqli_fetch_assoc($sql)) {
							      	
								    echo '  
								    <tr style="font-size:14px;>
								      <td></td>
								     	<td>'.$number.'</td> 
								        <td>'.$row['product'].'</td> 
								        <td>'.$row['aprc'].'</td>
								        <td>'.$row['part_n'].'</td>
								        <td>'.$row['part_n_desc'].'</td>
								        <td>'.$row['rma_no'].'</td>
								        <td>'.$row['qty'].'</td>
								    </tr> 
								      ';
								     $number=$number+1-0.5;

							      }
							      echo '<tr>  
								<td> &nbsp;</td>
								</tr>';
								
						}
					echo '				
				';
			$no++;
			}
			?>
			
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