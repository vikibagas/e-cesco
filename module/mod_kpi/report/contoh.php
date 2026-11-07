<?php
require 'vendor/autoload.php';
include('../../config/connection.php');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
 
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
 

/*$dari = $_GET['dari'];
$sampai = $_GET['sampai'];
$id_kustomer = $_GET['id_kustomer'];*/
$dari = $_GET['dari'];
$sampai = $_GET['sampai'];
$id_kustomer = $_GET['id_kustomer'];

$sheet->setCellValue('A1', 'No');
$sheet->setCellValue('B1', 'Airwaybill');
$sheet->setCellValue('C1', 'Pickup Date');
$sheet->setCellValue('D1', 'Booking By');
$sheet->setCellValue('E1', 'Komoditi');
$sheet->setCellValue('F1', 'Service');
$sheet->setCellValue('G1', 'Requestor');
$sheet->setCellValue('H1', 'LOC CODE/PID');
$sheet->setCellValue('I1', 'Nama Pengirim');
$sheet->setCellValue('J1', 'Perusahaan Pengirim');
$sheet->setCellValue('K1', 'Kota Asal');
$sheet->setCellValue('L1', 'Alamat  Asal');
$sheet->setCellValue('M1', 'Perusahaan Penerima');
$sheet->setCellValue('N1', 'Nama Penerima');
$sheet->setCellValue('O1', 'Kota Tujuan');
$sheet->setCellValue('P1', 'Alamat Penerima');
$sheet->setCellValue('Q1', 'Diterima Oleh');
$sheet->setCellValue('R1', 'Tanggal Diterima');
$sheet->setCellValue('S1', 'Berat');
$sheet->setCellValue('T1', 'Catatan Kollie');
$sheet->setCellValue('U1', 'Packing');
$sheet->setCellValue('V1', 'Asuransi');
$sheet->setCellValue('W1', 'Invoice');


			$no=1;
			$cari = "select 
					id_pengiriman,
					date(pickup_date) as tanggal,
					airwaybill_kode,
					case
					when a.komoditi = '1' then 'Paket'
						else 'Dokumen'
						end as komoditi,
					x_service(service) as service,
					a.service as id_service,
					IF(created_by != '',x_user(created_by),x_userclient(user_client)) as nama_booking,
					nama_pengirim,
					a.loc_code,
					pid,
								x_namakustomer(a.id_kustomer) as namakustomer,
								nama_penerima,
								case
									when inbound = 'Y' then 'INBOUND'
									when inbound = 'N' then 'OUTBOUND'
									else ''
								end as inbound,
								perusahaan_pengirim,
								lokasi_asal,
								if(id_kota_asal > 0,
									kota_asal,
									x_namakotabesar(lokasi_asal)) as lokasi_asal,
								alamat_pengirim,
								perusahaan_penerima,
								lokasi_tujuan,
								if(id_kota_tujuan > 0,
									kota_tujuan,
									x_namakotabesar(lokasi_tujuan)) as lokasi_tujuan,
								jenis_barang,
								alamat_penerima,
								delivery_name,
								a.status,
								delivery_date,
								a.id_invoice,
								CASE
									WHEN airwaybill_kode LIKE '001-%' THEN 0
									WHEN airwaybill_kode LIKE '002-%' THEN 1
								end as intercity,
								SUBSTRING_INDEX(ukuran_barang, 'x', 1) as length,
								SUBSTRING_INDEX(SUBSTRING_INDEX(ukuran_barang, 'x', 2),
										'x',
										- 1) as width,
								SUBSTRING_INDEX(ukuran_barang, 'x', - 1) as height,
								if((select intercity) = 1,
									(select length) * (select width) * (select height) / 4000,
									if(service = 5,
										(select length) * (select width) * (select height) / 4000,
										(select length) * (select width) * (select height) / 6000)) as volume,
								if(berat_aktual is null or berat_aktual < 1,
									berat_barang,
									berat_aktual) as berat_aktual,

								case
									when packing_kayu = '1' then 'Packing Oleh Pemilik'
									when packing_kayu = '2' then 'Packing Kayu Oleh Cesco Log.'
									when packing_kayu = '3' then 'Non Packing Kayu Oleh Cesco Log.'
								end as packing_kayu,
								asuransi,
								i.status as status_invoice, 
								id_pelayanan,
								pickup_date
							FROM
								pengiriman a
									left outer JOIN
								invoice i ON i.id_invoice = a.id_invoice
							where a.id_kustomer='".$id_kustomer."' and a.tanggal between '$dari' and '$sampai' ";
						

			$result = mysqli_query($con, $cari);
 
//$data = mysqli_query($koneksi,"select * from tbl_karyawan");
$i = 2;
$no = 1;


while($d = mysqli_fetch_array($result))
{
	if ($d['id_pelayanan'] == 5){
					$volume = ($d['length']*$d['width']*$d['height'])/4000;	
				}else{
					$volume = ($d['length']*$d['width']*$d['height'])/6000;
				}
				
				if($d['status']=='1'){
								$status = 'Booking';
							}elseif($d['status']=='2'){
								$status = 'Pickup';
							}elseif($d['status']=='3'){
								$status = 'On Outbound';
							}elseif($d['status']=='4'){
								$status = 'On Inbound';
							}elseif($d['status']=='5'){
								$status = 'On Delivery';
							}elseif($d['status']=='6'){
								$status = 'Delivered';
							}elseif($d['status']=='7'){
								$status = 'Cancel';
							}elseif($d['status']=='8'){
								$status = 'Return';
							}	

					$sttsInv = ($d['status_invoice'] == 'final') ? 'Y' : 'N';



    $sheet->setCellValue('A'.$i, $no++);
    $sheet->setCellValue('B'.$i, $d['pickup_date']);
    $sheet->setCellValue('C'.$i, $d['airwaybill_kode']);
    $sheet->setCellValue('D'.$i, $d['nama_booking']);
    $sheet->setCellValue('E'.$i, $d['komoditi']);    
    $sheet->setCellValue('F'.$i, $d['service']);   
    $sheet->setCellValue('G'.$i, $d['namakustomer']);
    $sheet->setCellValue('H'.$i, strtoupper($d['loc_code']).($d['pid'] != '' ? ', PID = '. $d['pid'] : ''));
    $sheet->setCellValue('I'.$i, $d['nama_pengirim']);
    $sheet->setCellValue('J'.$i, $d['perusahaan_pengirim']);
    $sheet->setCellValue('K'.$i, $d['lokasi_asal']);
    $sheet->setCellValue('L'.$i, $d['alamat_pengirim']);
    $sheet->setCellValue('M'.$i, $d['perusahaan_penerima']);
    $sheet->setCellValue('N'.$i, $d['nama_penerima']);
    $sheet->setCellValue('O'.$i, $d['lokasi_tujuan']);
    $sheet->setCellValue('P'.$i, $d['alamat_penerima']);
    $sheet->setCellValue('Q'.$i, $d['status']);
    $sheet->setCellValue('R'.$i, $d['delivery_date']);
    


    $sheet->setCellValue('S'.$i, $d['berat_aktual']);

    $sheet->setCellValue('T'.$i, '');
    $sheet->setCellValue('U'.$i, $d['packing_kayu']);
    $sheet->setCellValue('V'.$i, $d['asuransi']);
    $sheet->setCellValue('W'.$i, $sttsInv);
    

    $i++;
}

$namafile = $dari.'-'.$sampai.'-'.$id_kustomer.'-';
 
$writer = new Xlsx($spreadsheet);
$writer->save($namafile.'reports.xlsx');
echo "<script>window.location = '".$namafile."reports\.xlsx'</script>";
 
?>