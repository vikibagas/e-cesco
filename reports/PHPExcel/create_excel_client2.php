<?php
include ('../../config/connection.php');
include ('../../config/encrypt.php');
include ('../../config/library.php');
$dari = $_GET['dari'];
$sampai = $_GET['sampai'];
$id_kustomer = $_GET['id_kustomer'];
$id_user = $_GET['id_user'];
//$useraktif = mysqli_query($con, 'select * from user_client where id_user="'.$id_user.'"');
//						$u = mysqli_fetch_assoc($useraktif);
						
$useraktif = mysqli_query($con, 'select * from user_client where id_user="'.$id_user.'"');
	$u = mysqli_fetch_assoc($useraktif);
	
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
				<th style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border:1px solid;" rowspan="2">Tanggal Booking</th>
				<th style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border:1px solid;" rowspan="2">Tanggal Pick Up </th>
				<th style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border:1px solid;" rowspan="2">Jam Pick Up </th>
				<th style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border:1px solid;" rowspan="2">Airwaybill </th>
				<th style="font-family:Calibri		;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border:1px solid;" rowspan="2">Komoditi </th>
				<th style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border:1px solid;" rowspan="2">Service</th><th style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border:1px solid;" rowspan="2">LOC CODE/PID</th>

				<th style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border:1px solid;" colspan="4">Pengirim </th>
				<th style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border:1px solid;" colspan="4">Penerima</th>
				<th style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border:1px solid;" colspan="3">Status </th>
				<th style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border:1px solid;" >Berat </th>
				<!--<th style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border:1px solid;" rowspan="2">Invoice </th>-->
			</tr>
			<tr>
				<th style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border:1px solid;">Nama Pengirim</th>
				<th style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border:1px solid;">Perusahaan</th>
				<th style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border:1px solid;">Alamat</th>
				<th style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border:1px solid;">Kota</th>
				<th style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border:1px solid;">Nama</th>
				<th style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border:1px solid;">Perusahaan</th>
				<th style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border:1px solid;">Alamat</th>
				<th style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border:1px solid;">Kota</th>
				<th style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border:1px solid;">Status</th>
				<th style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border:1px solid;">Diterima Oleh</th>
				<th style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border:1px solid;">Tanggal</th>				
				
				<th style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border:1px solid;">Aktual</th>
			</tr>
			<?php
			$no=1;
			$tipe = mysqli_real_escape_string($con, $_GET['tipe']);

            if($tipe=="1"){
                $cari = "SELECT 
							 id_pengiriman, 
									
									date(created_date) as tanggal_booking, 
									date(pickup_date) as tanggal_pickup, 
									DATE_FORMAT(pickup_date, '%H:%i') as jam_pickup,
									airwaybill_kode, 
									case when komoditi='1' then 'Paket' else 'Dokumen' end as komoditi, 
									x_service(service) as service, 
									nama_pengirim, 
									loc_code, 
									x_namakustomer(id_kustomer) as namakustomer, 
									nama_penerima, 
									case when inbound='Y' then 'INBOUND' when inbound='N' then 'OUTBOUND' else '' end as inbound, 
									perusahaan_pengirim,
									lokasi_asal, 
									x_namakotabesar(lokasi_asal) as lokasi_asal, 
									alamat_pengirim, 
									perusahaan_penerima,
									lokasi_tujuan, 
									x_namakotabesar(lokasi_tujuan) as lokasi_tujuan, 
									jenis_barang,
									alamat_penerima, 
									delivery_name, 
									delivery_date, 
									SUBSTRING_INDEX(ukuran_barang,'x',1) as length, 
									SUBSTRING_INDEX(SUBSTRING_INDEX(ukuran_barang,'x',2),'x',-1) as width, 
									SUBSTRING_INDEX(ukuran_barang,'x',-1) as height, 
									if(berat_aktual is null or berat_aktual < 1,  berat_barang, berat_aktual) as berat_aktual, 
									case when packing_kayu='1' then 'Packing Oleh Pemilik' when packing_kayu='2' then 'Packing Kayu Oleh Cesco Log.' when packing_kayu='3' then 'Non Packing Kayu Oleh Cesco Log.' end as packing_kayu, 
									asuransi ,
						status FROM pengiriman where  x_inter(id_kota_asal) = 'Y' and x_inter(id_kota_tujuan) !='Y' and tanggal between '$dari' and '$sampai' and status != 7 and id_kustomer='".$u['id_kustomer']."'";
            }elseif($tipe=="2"){
                $cari = "SELECT 
							id_pengiriman, 
									date(created_date) as tanggal_booking, 
									date(pickup_date) as tanggal_pickup, 
									DATE_FORMAT(pickup_date, '%H:%i') as jam_pickup,
									airwaybill_kode, 
									case when komoditi='1' then 'Paket' else 'Dokumen' end as komoditi, 
									x_service(service) as service, 
									nama_pengirim, 
									loc_code, 
									x_namakustomer(id_kustomer) as namakustomer, 
									nama_penerima, 
									case when inbound='Y' then 'INBOUND' when inbound='N' then 'OUTBOUND' else '' end as inbound, 
									perusahaan_pengirim,
									lokasi_asal, 
									x_namakotabesar(lokasi_asal) as lokasi_asal, 
									alamat_pengirim, 
									perusahaan_penerima,
									lokasi_tujuan, 
									x_namakotabesar(lokasi_tujuan) as lokasi_tujuan, 
									jenis_barang,
									alamat_penerima, 
									delivery_name, 
									delivery_date, 
									SUBSTRING_INDEX(ukuran_barang,'x',1) as length, 
									SUBSTRING_INDEX(SUBSTRING_INDEX(ukuran_barang,'x',2),'x',-1) as width, 
									SUBSTRING_INDEX(ukuran_barang,'x',-1) as height, 
									if(berat_aktual is null or berat_aktual < 1,  berat_barang, berat_aktual) as berat_aktual, 
									case when packing_kayu='1' then 'Packing Oleh Pemilik' when packing_kayu='2' then 'Packing Kayu Oleh Cesco Log.' when packing_kayu='3' then 'Non Packing Kayu Oleh Cesco Log.' end as packing_kayu, 
									asuransi ,
						status FROM pengiriman where  x_inter(id_kota_asal) != 'Y' and x_inter(id_kota_tujuan) ='Y' and tanggal between '$dari' and '$sampai' and status != 7 and id_kustomer='".$u['id_kustomer']."'";
            }else{
                /*$cari = "SELECT 
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
									delivery_name,
									delivery_date,
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
									i.status as status_invoice, a.pid
								FROM
									pengiriman a
										INNER JOIN
									kota b ON b.id_kota = a.lokasi_tujuan
										OR b.id_kota = a.lokasi_asal
										INNER JOIN
									cabang c ON c.id_kota = b.id_kota
										OR b.hub = c.id_kota
										left outer JOIN
									invoice i ON i.id_invoice = a.id_invoice 
									left join kustomer cs on cs.id_kustomer = a.id_kustomer 
								where a.tanggal between '$dari' and '$sampai' and c.id_cabang='".$u['id_cabang']."'";*/

								$cari = "SELECT 
							id_pengiriman, 
									date(created_date) as tanggal_booking, 
									date(pickup_date) as tanggal_pickup, 
									DATE_FORMAT(pickup_date, '%H:%i') as jam_pickup,
									airwaybill_kode, 
									case when komoditi='1' then 'Paket' else 'Dokumen' end as komoditi, 
									x_service(service) as service, 
									nama_pengirim, 
									loc_code, 
									pickup_date,
									x_namakustomer(id_kustomer) as namakustomer, 
									nama_penerima, 
									case when inbound='Y' then 'INBOUND' when inbound='N' then 'OUTBOUND' else '' end as inbound, 
									perusahaan_pengirim,
									lokasi_asal, 
									kota_asal as lokasi_asal, 
									alamat_pengirim, 
									perusahaan_penerima,
									lokasi_tujuan, 
									kota_tujuan as lokasi_tujuan, 
									jenis_barang,
									alamat_penerima, 
									delivery_name, 
									delivery_date, 
									SUBSTRING_INDEX(ukuran_barang,'x',1) as length, 
									SUBSTRING_INDEX(SUBSTRING_INDEX(ukuran_barang,'x',2),'x',-1) as width, 
									SUBSTRING_INDEX(ukuran_barang,'x',-1) as height, 
									if(berat_aktual is null or berat_aktual < 1,  berat_barang, berat_aktual) as berat_aktual, 
									case when packing_kayu='1' then 'Packing Oleh Pemilik' when packing_kayu='2' then 'Packing Kayu Oleh Cesco Log.' when packing_kayu='3' then 'Non Packing Kayu Oleh Cesco Log.' end as packing_kayu, 
									asuransi ,
						status FROM pengiriman where tanggal between '$dari' and '$sampai' and status != 7 and id_kustomer='".$u['id_kustomer']."'";
            }
            //echo $cari;

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
							
					//$volume = ($t['kode_kustomer'] == 'CIS' || $t['kode_kustomer'] == 'DEL') ? round($t['volume'],2) : ceil($t['volume']);
					
					//$sttsInv = ($t['status_invoice'] == 'final') ? 'Y' : 'N';
				echo'
				<tr>
					<td style="font-family:Calibri;text-align:center;font-size:14px;font-color:#000000;border-color:#000;border:1px 
						solid;">'.$no.'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['tanggal_booking'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['tanggal_pickup'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['jam_pickup'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['airwaybill_kode'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['komoditi'].', '.$t['jenis_barang'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['service'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.strtoupper($t['loc_code']).'</td>

					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['nama_pengirim'].'</td>				
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