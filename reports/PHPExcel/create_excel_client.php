<?php
include ('../../config/connection.php');
include ('../../config/encrypt.php');
include ('../../config/library.php');
$dari = $_GET['dari'];
$sampai = $_GET['sampai'];
$id_kustomer = $_GET['id_kustomer'];
$id_user = $_GET['id_user'];
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
				<th style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border:1px solid;" rowspan="2">Airwaybill </th>
				<th style="font-family:Calibri		;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border:1px solid;" rowspan="2">Komoditi </th>
				<th style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border:1px solid;" rowspan="2">Service</th>
				<th style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border:1px solid;" colspan="4">Pengirim </th>
				<th style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border:1px solid;" colspan="4">Penerima</th>
				<th style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border:1px solid;" colspan="3">Status </th>
				<th style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border:1px solid;" colspan="2">Berat </th>
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
						
					/*	if ($u['other_customer'] == 'Y' || $u['report_awb_by_booking'] == 1) {
							$cari = "SELECT 
									 id_pengiriman, 
											date(pickup_date) as tanggal, 
											airwaybill_kode, 
											case when komoditi='1' then 'Paket' else 'Dokumen' end as komoditi, 
											x_service(service) as service, 
											nama_pengirim, 
											loc_code, 
											pid,
											x_namakustomer(id_kustomer) as namakustomer, 
											nama_penerima, 
											case when inbound='Y' then 'INBOUND' when inbound='N' then 'OUTBOUND' else '' end as inbound, 
											perusahaan_pengirim,
											lokasi_asal, 
											if(id_kota_asal>0,kota_asal,x_namakotabesar(lokasi_asal)) as lokasi_asal, 
											alamat_pengirim, 
											perusahaan_penerima,
											lokasi_tujuan, 
											if(id_kota_tujuan>0,kota_tujuan,x_namakotabesar(lokasi_tujuan)) as lokasi_tujuan, 
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
								status FROM pengiriman where status !=7 and  tanggal between '$dari' and '$sampai' and  user_client in  (".$u['id_user'].") ";
						}
						else{
							if($u['report_awb_loc'] != 1){
								if($tipe=="1"){
									$cari = "SELECT 
									 id_pengiriman, 
											date(pickup_date) as tanggal, 
											airwaybill_kode, 
											case when komoditi='1' then 'Paket' else 'Dokumen' end as komoditi, 
											x_service(service) as service, 
											nama_pengirim, 
											loc_code, 
											pid,
											x_namakustomer(id_kustomer) as namakustomer, 
											nama_penerima, 
											case when inbound='Y' then 'INBOUND' when inbound='N' then 'OUTBOUND' else '' end as inbound, 
											perusahaan_pengirim,
											lokasi_asal, 
											if(id_kota_asal>0,kota_asal,x_namakotabesar(lokasi_asal)) as lokasi_asal, 
											alamat_pengirim, 
											perusahaan_penerima,
											lokasi_tujuan, 
											if(id_kota_tujuan>0,kota_tujuan,x_namakotabesar(lokasi_tujuan)) as lokasi_tujuan, 
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
								status FROM pengiriman where status !=7 and x_intercity(lokasi_asal) = 'Y' and x_intercity(lokasi_tujuan) !='Y'and tanggal between '$dari' and '$sampai' and id_kustomer='".$u['id_kustomer']."'";
								}elseif($tipe=="2"){
									$cari = "SELECT 
									id_pengiriman, 
											date(pickup_date) as tanggal, 
											airwaybill_kode, 
											case when komoditi='1' then 'Paket' else 'Dokumen' end as komoditi, 
											x_service(service) as service, 
											nama_pengirim, 
											loc_code, 
											pid,
											x_namakustomer(id_kustomer) as namakustomer, 
											nama_penerima, 
											case when inbound='Y' then 'INBOUND' when inbound='N' then 'OUTBOUND' else '' end as inbound, 
											perusahaan_pengirim,
											lokasi_asal, 
											if(id_kota_asal>0,kota_asal,x_namakotabesar(lokasi_asal)) as lokasi_asal, 
											alamat_pengirim, 
											perusahaan_penerima,
											lokasi_tujuan, 
											if(id_kota_tujuan>0,kota_tujuan,x_namakotabesar(lokasi_tujuan)) as lokasi_tujuan, 
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
								status FROM pengiriman where status !=7 and x_intercity(lokasi_asal) != 'Y' and x_intercity(lokasi_tujuan) ='Y' and tanggal between '$dari' and '$sampai' and id_kustomer='".$u['id_kustomer']."'";
								}else{
									$cari = "SELECT 
									id_pengiriman,
									 airwaybill_kode,
									 tanggal, 
									 DATE_FORMAT(created_date,'%H:%i') as jam, 
									 pickup_date,
									 case when komoditi='1' then 'Paket' else 'Dokumen' end as komoditi,
									 jenis_barang,
									 perusahaan_pengirim,
									 x_namakustomer(id_kustomer) as namakustomer, 
									 alamat_pengirim,
									 nama_pengirim,id_pengiriman, 
											date(pickup_date) as tanggal, 
											airwaybill_kode, 
											case when komoditi='1' then 'Paket' else 'Dokumen' end as komoditi, 
											x_service(service) as service, 
											nama_pengirim, 
											loc_code, 
											pid,
											x_namakustomer(id_kustomer) as namakustomer, 
											nama_penerima, 
											case when inbound='Y' then 'INBOUND' when inbound='N' then 'OUTBOUND' else '' end as inbound, 
											perusahaan_pengirim,
											lokasi_asal, 
											if(id_kota_asal>0,kota_asal,x_namakotabesar(lokasi_asal)) as lokasi_asal, 
											alamat_pengirim, 
											perusahaan_penerima,
											lokasi_tujuan, 
											if(id_kota_tujuan>0,kota_tujuan,x_namakotabesar(lokasi_tujuan)) as lokasi_tujuan, 
											jenis_barang,
											alamat_penerima, 
											delivery_name, 
											delivery_date, 
											SUBSTRING_INDEX(ukuran_barang,'x',1) as length, 
											SUBSTRING_INDEX(SUBSTRING_INDEX(ukuran_barang,'x',2),'x',-1) as width, 
											SUBSTRING_INDEX(ukuran_barang,'x',-1) as height, 
											if(berat_aktual is null or berat_aktual < 1,  berat_barang, berat_aktual) as berat_aktual, 
											case when packing_kayu='1' then 'Packing Oleh Pemilik' when packing_kayu='2' then 'Packing Kayu Oleh Cesco Log.' when packing_kayu='3' then 'Non Packing Kayu Oleh Cesco Log.' end as packing_kayu, 
											asuransi,  
									 status FROM pengiriman where status !=7 and tanggal between '$dari' and '$sampai' and id_kustomer='".$u['id_kustomer']."'";
								}
							}
							//berdasarkan loc code
							else{
								if($tipe=="1"){
									$cari = "SELECT 
									 id_pengiriman, 
											date(pickup_date) as tanggal, 
											airwaybill_kode, 
											case when komoditi='1' then 'Paket' else 'Dokumen' end as komoditi, 
											x_service(service) as service, 
											nama_pengirim, 
											loc_code, 
											pid,
											x_namakustomer(id_kustomer) as namakustomer, 
											nama_penerima, 
											case when inbound='Y' then 'INBOUND' when inbound='N' then 'OUTBOUND' else '' end as inbound, 
											perusahaan_pengirim,
											lokasi_asal, 
											if(id_kota_asal>0,kota_asal,x_namakotabesar(lokasi_asal)) as lokasi_asal, 
											alamat_pengirim, 
											perusahaan_penerima,
											lokasi_tujuan, 
											if(id_kota_tujuan>0,kota_tujuan,x_namakotabesar(lokasi_tujuan)) as lokasi_tujuan, 
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
								status FROM pengiriman where status !=7 and inbound='N' and tanggal between '$dari' and '$sampai' and id_kustomer='".$u['id_kustomer']."' and loc_code in($u[loc_code_otomatis])";
								}elseif($tipe=="2"){
									$cari = "SELECT 
									id_pengiriman, 
											date(pickup_date) as tanggal, 
											airwaybill_kode, 
											case when komoditi='1' then 'Paket' else 'Dokumen' end as komoditi, 
											x_service(service) as service, 
											nama_pengirim, 
											loc_code, 
											pid,
											x_namakustomer(id_kustomer) as namakustomer, 
											nama_penerima, 
											case when inbound='Y' then 'INBOUND' when inbound='N' then 'OUTBOUND' else '' end as inbound, 
											perusahaan_pengirim,
											lokasi_asal, 
											if(id_kota_asal>0,kota_asal,x_namakotabesar(lokasi_asal)) as lokasi_asal, 
											alamat_pengirim, 
											perusahaan_penerima,
											lokasi_tujuan, 
											if(id_kota_tujuan>0,kota_tujuan,x_namakotabesar(lokasi_tujuan)) as lokasi_tujuan, 
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
								status FROM pengiriman where status !=7 and inbound='Y' and tanggal between '$dari' and '$sampai' and id_kustomer='".$u['id_kustomer']."' and loc_code in($u[loc_code_otomatis])";
								}else{
									 $cari = "SELECT 
									id_pengiriman,
									 airwaybill_kode,
									 tanggal, 
									 DATE_FORMAT(created_date,'%H:%i') as jam, 
									 pickup_date,
									 case when komoditi='1' then 'Paket' else 'Dokumen' end as komoditi,
									 jenis_barang,
									 perusahaan_pengirim,
									 x_namakustomer(id_kustomer) as namakustomer, 
									 alamat_pengirim,
									 nama_pengirim,id_pengiriman, 
											date(pickup_date) as tanggal, 
											airwaybill_kode, 
											case when komoditi='1' then 'Paket' else 'Dokumen' end as komoditi, 
											x_service(service) as service, 
											nama_pengirim, 
											loc_code, 
											pid,
											x_namakustomer(id_kustomer) as namakustomer, 
											nama_penerima, 
											case when inbound='Y' then 'INBOUND' when inbound='N' then 'OUTBOUND' else '' end as inbound, 
											perusahaan_pengirim,
											lokasi_asal, 
											if(id_kota_asal>0,kota_asal,x_namakotabesar(lokasi_asal)) as lokasi_asal, 
											alamat_pengirim, 
											perusahaan_penerima,
											lokasi_tujuan, 
											if(id_kota_tujuan>0,kota_tujuan,x_namakotabesar(lokasi_tujuan)) as lokasi_tujuan, 
											jenis_barang,
											alamat_penerima, 
											delivery_name, 
											delivery_date, 
											SUBSTRING_INDEX(ukuran_barang,'x',1) as length, 
											SUBSTRING_INDEX(SUBSTRING_INDEX(ukuran_barang,'x',2),'x',-1) as width, 
											SUBSTRING_INDEX(ukuran_barang,'x',-1) as height, 
											if(berat_aktual is null or berat_aktual < 1,  berat_barang, berat_aktual) as berat_aktual, 
											case when packing_kayu='1' then 'Packing Oleh Pemilik' when packing_kayu='2' then 'Packing Kayu Oleh Cesco Log.' when packing_kayu='3' then 'Non Packing Kayu Oleh Cesco Log.' end as packing_kayu, 
											asuransi,  
									 status FROM pengiriman where status !=7 and tanggal between '$dari' and '$sampai' and id_kustomer='".$u['id_kustomer']."' and loc_code in($u[loc_code_otomatis])";
								}

							}
						}*/

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
									kota_asal,
									alamat_pengirim, 
									perusahaan_penerima,
									lokasi_tujuan, 
									kota_tujuan,
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
									 
									kota_asal,
									alamat_pengirim, 
									perusahaan_penerima,
									lokasi_tujuan, 
									kota_tujuan,
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
									kota_asal,
									alamat_pengirim, 
									perusahaan_penerima,
									kota_tujuan,
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
				echo'
				<tr>
					<td style="font-family:Calibri;text-align:center;font-size:14px;font-color:#000000;border-color:#000;border:1px 
						solid;">'.$no.'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['tanggal_booking'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['tanggal_pickup'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['airwaybill_kode'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['komoditi'].', '.$t['jenis_barang'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['service'].'</td>
					
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['nama_pengirim'].'</td>				
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['perusahaan_pengirim'].'</td>					
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['alamat_pengirim'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['kota_asal'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['nama_penerima'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['perusahaan_penerima'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['alamat_penerima'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['kota_tujuan'].'</td>					
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$status.'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['delivery_name'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['delivery_date'].'</td>					
					
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['berat_aktual'].'</td>				
				</tr>	

				';
					$sql = mysqli_query($con, "select id_pengiriman,product,aprc,part_n,part_n_desc,rma_no,qty,remark from asus_detail_catatan  where id_pengiriman='".$t['id_pengiriman']."'");

					
					$number = 0.5;
					//$number = $number-1;
						$cek = mysqli_num_rows($sql);
						
								echo '
							
								<tr style="font-size:14px; font-weight:bold;">
							        
							       <td >NO</td>
							        <td>PRODUCT</td>
							        <td>APRC</td>
							        <td>PART N</td>
							        <td>PART DESC</td>
							        <td>RMA NO</td>
							        <td>QTY</td>
							      </tr>';
							
							      while ($row = mysqli_fetch_assoc($sql)) {
							      	
								    echo '  
								    <tr style="font-size:14px;>
								      
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