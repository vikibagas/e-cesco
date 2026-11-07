<?php
include ('../../config/connection.php');
include ('../../config/encrypt.php');
include ('../../config/library.php');
//$dari = $_GET['dari'];
//$sampai = $_GET['sampai'];
//$id_kustomer = $_GET['id_kustomer'];
header("Content-type: application/vnd-ms-excel");
header('Content-Disposition: attachment; filename="Report last 3 month.xls"');
//
?>
<!DOCTYPE html>
<html>
	<head>
	<meta charset="UTF-8">
		<title>Airwaybill Courier</title>
	</head>
	<body>
		<table cellspacing=0 width="100%">
			<tr>
				<td style="font-family:Calibri;text-align:center;font-size:16px;color:#000000;font-weight:bold;" colspan="17">Airwaybill Courier</td>
			</tr>
			<tr>
				<td colspan="10"></td>
			</tr>	  
			<tr>
				<td colspan="8">&nbsp;</td>
			</tr>
			<tr>
				<th style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border:1px solid;" rowspan="2">NO </th>
				<th style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border:1px solid;" rowspan="2">Tanggal Booking</th>
				<th style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border:1px solid;" rowspan="2">Tanggal Pick Up </th>
				<th style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border:1px solid;" rowspan="2">Airwaybill </th>
				<th style="font-family:Calibri		;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border:1px solid;" rowspan="2">Komoditi </th>
				<th style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border:1px solid;" rowspan="2">Service</th>
				<th style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border:1px solid;" colspan="3">Pengirim </th>
				<th style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border:1px solid;" colspan="4">Penerima</th>
				<th style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border:1px solid;" colspan="3">Status </th>
				<th style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border:1px solid;" colspan="2">Berat </th>
			</tr>
			<tr>
				<th style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border:1px solid;">Nama</th>
				<th style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border:1px solid;">Alamat</th>
				<th style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border:1px solid;">Kota</th>
				<th style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border:1px solid;">Nama</th>
				<th style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border:1px solid;">Perusahaan</th>
				<th style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border:1px solid;">Alamat</th>
				<th style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border:1px solid;">Kota</th>
				<th style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border:1px solid;">Status</th>
				<th style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border:1px solid;">Diterima Oleh</th>
				<th style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border:1px solid;">Tanggal</th>				
				<th style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border:1px solid;">Volume</th>
				<th style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border:1px solid;">Aktual</th>
			</tr>
			<?php
			$no=1;
			$tipe = mysqli_real_escape_string($con, $_GET['tipe']);
						
						
							echo $cari = "SELECT 
							 airwaybill_kode, 
							 tanggal, 
							 DATE_FORMAT(created_date,'%H:%i') as jam,
							 pickup_date,
							 case when komoditi='1' then 'Paket' else 'Dokumen' end as komoditi,
							 jenis_barang,
							 perusahaan_pengirim,
							 x_namakustomer(id_kustomer) as namakustomer, 
							 alamat_pengirim,
							 x_namakotabesar(lokasi_asal) as lokasi_asal, 
							 perusahaan_penerima,
							 alamat_penerima,
							 x_namakotabesar(lokasi_tujuan) as lokasi_tujuan,
							 x_service(service) as service,
							 nama_penerima,
							 delivery_date,
							 delivery_name,							 
							 ukuran_barang,
							 collie,
							 SUBSTRING_INDEX(ukuran_barang,'x',1) as length, 
									SUBSTRING_INDEX(SUBSTRING_INDEX(ukuran_barang,'x',2),'x',-1) as width, 
									SUBSTRING_INDEX(ukuran_barang,'x',-1) as height, 
									coalesce(berat_aktual,berat_barang) as berat_aktual, 
						status FROM pengiriman where tanggal >= NOW() - INTERVAL 3 MONTH and pickup_by='87' or courier_by = '87'";
											

			$result = mysqli_query($con, $cari);
			while($t=mysqli_fetch_assoc($result)){		
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
				echo'
				<tr>
					<td style="font-family:Calibri;text-align:center;font-size:14px;font-color:#000000;border-color:#000;border:1px 
						solid;">'.$no.'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['tanggal'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['pickup_date'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['airwaybill_kode'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['komoditi'].', '.$t['jenis_barang'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['service'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['perusahaan_pengirim'].'</td>					
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['alamat_pengirim'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['lokasi_asal'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['nama_penerima'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['perusahaan_penerima'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['alamat_penerima'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['lokasi_tujuan'].'</td>					
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$status.'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['delivery_name'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['delivery_date'].'</td>					
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['volume'].'</td>	
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['berat_aktual'].'</td>				
				</tr>					
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