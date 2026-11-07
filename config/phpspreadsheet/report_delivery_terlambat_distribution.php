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
$encrypt = "$_GET[id_team]";
$id = base64_decrypt($encrypt,$key);


 $id_team = $id;
 $tanggal = $_GET['tanggal'];

$qu = mysqli_query($con, "select * from user where id_user = ".$id_team."");





$currentDate = date('Y-m-d'); 
$r = mysqli_fetch_assoc($qu);


$sheet->setCellValue('A1', 'No');
$sheet->setCellValue('B1', 'Airwaybill');
$sheet->setCellValue('C1', 'Serivce');
$sheet->setCellValue('D1', 'Kota tujuan');
$sheet->setCellValue('E1', 'Tanggal pickup/Sampai dikota tujuan');
$sheet->setCellValue('F1', 'Tanggal delivery');
$sheet->setCellValue('G1', 'Alasan terlambat');
$sheet->setCellValue('H1', 'Delivery by');

$sheet->getStyle('A1:H1')->getFont()->setBold(true);

			
$i = 2;
$no = 1;

$result = mysqli_query($con,"select tanggal, airwaybill_kode, delivery_date,x_kurir(delivery_by) as delivery_oleh,alasan_terlambat_delivery,x_namacabang(delivery_by_agent) as namacabang,x_service(service) as nama_service,kota_tujuan, DATE_FORMAT(pickup_date, '%Y-%m-%d') as tanggal_pickup, manifest_received,x_inter(id_kota_asal) as inter_asal,x_inter(id_kota_tujuan) as inter_tujuan 

				 from pengiriman where delivery_by='$id_team' and delivery_date between '".$tanggal."-01' and '".$tanggal."-31'
				 
				 and status in (6,8) and terlambat_delivery > 0

				 ");
				
				//$cek = mysqli_num_rows($result);

				


				
				$no = 1;



while($d = mysqli_fetch_array($result))
{
	


    $sheet->setCellValue('A'.$i, $no++);
    $sheet->setCellValue('B'.$i, $d['airwaybill_kode']);
    $sheet->setCellValue('C'.$i, $d['nama_service']);
    $sheet->setCellValue('D'.$i, $d['kota_tujuan']);
    $sheet->setCellValue('E'.$i, ($d['inter_asal'] == 'Y' &&  $d['inter_tujuan'] == 'Y' ? $d['tanggal_pickup'] : $d['manifest_received']));    
    $sheet->setCellValue('F'.$i, $d['delivery_date']);   
    $sheet->setCellValue('G'.$i, $d['alasan_terlambat_delivery']);
    $sheet->setCellValue('H'.$i, $d['delivery_oleh'].' ( '.$d['namacabang'].' )');

    $i++;
}

foreach (range('A', 'H') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

$namafile = 'delivery_terlambat '.$tanggal.'-'.$r['namalengkap'].'-';
 
$writer = new Xlsx($spreadsheet);
$writer->save($namafile.'reports.xlsx');
echo "<script>window.location = '".$namafile."reports\.xlsx'</script>";
 
?>