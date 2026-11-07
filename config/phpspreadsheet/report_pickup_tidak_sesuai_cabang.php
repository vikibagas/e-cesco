<?php
require 'vendor/autoload.php';
include('../../config/connection.php');
include ('../../config/encrypt.php');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
 
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
 

/*$dari = $_GET['dari'];
$sampai = $_GET['sampai'];
$id_kustomer = $_GET['id_kustomer'];*/

$key = "asdf$1011";
$encrypt = "$_GET[id_cabang]";
$id = base64_decrypt($encrypt,$key);


 $id_cabang = $id;
 $tanggal = $_GET['tanggal'];

$qu = mysqli_query($con, "select * from cabang where id_cabang = ".$id_cabang."");


$currentDate = date('Y-m-d'); 
$r = mysqli_fetch_assoc($qu);


$sheet->setCellValue('A1', 'No');
$sheet->setCellValue('B1', 'Airwaybill');
$sheet->setCellValue('C1', 'Serivce');
$sheet->setCellValue('D1', 'Kota asal');
$sheet->setCellValue('E1', 'Booking');
$sheet->setCellValue('F1', 'Tanggal pickup');
$sheet->setCellValue('G1', 'Alasan terlambat');
$sheet->setCellValue('H1', 'Pickup by');

$sheet->getStyle('A1:H1')->getFont()->setBold(true);

			
$i = 2;
$no = 1;

$result = mysqli_query($con,"select tanggal, airwaybill_kode, pickup_date,pickup_date_request,x_kurir(pickup_by) as pickup_oleh, pickup_by_agent,alasan_terlambat_pickup,x_namacabang(pickup_by_agent) as namacabang,x_service(service) as nama_service,kota_asal,manifest_pickup_date,DATE_FORMAT(created_date, '%Y-%m-%d') as created_date

				 from pengiriman where pickup_by_agent='$id_cabang' and pickup_date between '".$tanggal."-01' and '".$tanggal."-31'
				 
				 and status in (6,8) and terlambat_pickup > 0

				 ");
				
				//$cek = mysqli_num_rows($result);

				


				
				$no = 1;



while($d = mysqli_fetch_array($result))
{
	


    $sheet->setCellValue('A'.$i, $no++);
    $sheet->setCellValue('B'.$i, $d['airwaybill_kode']);
    $sheet->setCellValue('C'.$i, $d['nama_service']);
    $sheet->setCellValue('D'.$i, $d['kota_asal']);
    $sheet->setCellValue('E'.$i, $d['created_date']);    
    $sheet->setCellValue('F'.$i, $d['pickup_date']);   
    $sheet->setCellValue('G'.$i, $d['alasan_terlambat_pickup']);
    $sheet->setCellValue('H'.$i, $d['pickup_oleh']);

    $i++;
}

foreach (range('A', 'H') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

$namafile = 'pickup_terlambat'.$tanggal.'-'.$r['nama_cabang'].'-';
 
$writer = new Xlsx($spreadsheet);
$writer->save($namafile.'reports.xlsx');
echo "<script>window.location = '".$namafile."reports\.xlsx'</script>";
 
?>a