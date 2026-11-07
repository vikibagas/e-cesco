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
$sheet->setCellValue('D1', 'Kota tujuan');
$sheet->setCellValue('E1', 'Tanggal sampai kota tujuan');
$sheet->setCellValue('F1', 'Tanggal delivery');
$sheet->setCellValue('G1', 'SLA AKTUAL');
$sheet->setCellValue('H1',  'SLA KONTRAK');
$sheet->setCellValue('I1', 'Alasan terlambat');
$sheet->setCellValue('J1', 'Delivery by');

$sheet->getStyle('A1:J1')->getFont()->setBold(true);

			
$i = 2;
$no = 1;

$result = mysqli_query($con,"select 
    tanggal,
    airwaybill_kode,
    x_kurir(delivery_by) AS delivery_oleh,
    delivery_by_agent,
    alasan_terlambat_delivery,
    x_namacabang(delivery_by_agent) AS namacabang,
    x_service(p.service) AS nama_service,
    kota_tujuan,
    manifest_pickup_date,
    DATE_FORMAT(created_date, '%Y-%m-%d') AS created_date,
    DATE_FORMAT(pickup_date, '%Y-%m-%d') AS tanggal_pickup,
    manifest_received,
    x_inter(p.id_kota_asal) AS inter_asal,
    x_inter(p.id_kota_tujuan) AS inter_tujuan,
    delivery_date,

    (
      DATEDIFF(delivery_date, manifest_received)
      -
      (
        (DATEDIFF(delivery_date, manifest_received)
         + WEEKDAY(manifest_received) + 1
        ) DIV 7
      )
    ) AS sla_aktual,

    CASE
        WHEN p.service IN (1,2) THEN '1 - 1 hari'

        WHEN p.service IN (3,5) AND sla.id_agent IS NOT NULL THEN
            CONCAT(sla.sla_awal, ' - ', sla.sla_akhir, ' hari')

        WHEN p.service IN (3,5) AND p.id_kota_tujuan = ".$r['id_kota_baru']." THEN
            '1 - 1 hari'

        WHEN p.service IN (3,5) AND r.is_kota_madya = 1 THEN
            '2 - 2 hari'

        WHEN p.service IN (3,5) AND (r.is_kota_madya IS NULL OR r.is_kota_madya != 1) THEN
            '2 - 3 hari'

        ELSE '-'
    END AS sla_kontrak

FROM pengiriman p

LEFT JOIN sla_deliv_kontrak_agent sla
    ON sla.id_agent = ".$id_cabang."
    AND sla.service = p.service
    AND sla.id_kota_tujuan = p.id_kota_tujuan

LEFT JOIN regencies r
    ON r.id = p.id_kota_tujuan

WHERE delivery_by_agent = '$id_cabang'
  AND delivery_date BETWEEN '".$tanggal."-01' AND '".$tanggal."-31'
  AND status IN (6,8)
  AND terlambat_delivery > 0

				 ");
				
				//$cek = mysqli_num_rows($result);

				


				
				$no = 1;



while($d = mysqli_fetch_array($result))
{
	


    $sheet->setCellValue('A'.$i, $no++);
    $sheet->setCellValue('B'.$i, $d['airwaybill_kode']);
    $sheet->setCellValue('C'.$i, $d['nama_service']);
    $sheet->setCellValue('D'.$i, $d['kota_tujuan']);
    $sheet->setCellValue('E'.$i, $d['manifest_received']);    
    $sheet->setCellValue('F'.$i, $d['delivery_date']);   
    $sheet->setCellValue('G'.$i, $d['sla_aktual']. ' hari');
    $sheet->setCellValue('H'.$i, $d['sla_kontrak']);
    $sheet->setCellValue('I'.$i, $d['alasan_terlambat_delivery']);
    $sheet->setCellValue('J'.$i, $d['delivery_oleh']);

    $i++;
}

foreach (range('A', 'H') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

$namafile = 'delivery_terlambat'.$tanggal.'-'.$r['nama_cabang'].'-';
 
$writer = new Xlsx($spreadsheet);
$writer->save($namafile.'reports.xlsx');
echo "<script>window.location = '".$namafile."reports\.xlsx'</script>";
 
?>a