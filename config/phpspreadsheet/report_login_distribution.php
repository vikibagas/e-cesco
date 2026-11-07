<?php  
require 'vendor/autoload.php';  
include('../../config/connection.php');  
include('../../config/encrypt.php');  

use PhpOffice\PhpSpreadsheet\Spreadsheet;  
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;  

$key = "asdf$1011";  
$encrypt = $_GET['id_team'];  
$id_team = base64_decrypt($encrypt, $key);  
$tanggal = $_GET['tanggal']; // format: YYYY-MM  

// ambil data user (kurir)
$qu = mysqli_query($con, "SELECT * FROM user WHERE id_user = " . $id_team . "");  
$r = mysqli_fetch_assoc($qu);  

$spreadsheet = new Spreadsheet();  
$sheet = $spreadsheet->getActiveSheet();  

$sheet->setCellValue('A1', 'No');  
$sheet->setCellValue('B1', 'Nama Kurir');  
$sheet->setCellValue('C1', 'Tanggal');  
$sheet->setCellValue('D1', 'Jam');  
$sheet->setCellValue('E1', 'Latitude');  
$sheet->setCellValue('F1', 'Longitude');  
$sheet->setCellValue('G1', 'Alamat Detail');  

$sheet->getStyle('A1:G1')->getFont()->setBold(true);  

$i = 2;  
$no = 1;  

// Ambil semua lokasi di bulan yang diminta
$result = mysqli_query($con, "
    SELECT 
        DATE(timestamp) AS tanggal, 
        TIME(timestamp) AS jam, 
        lat, 
        lng, 
        detail_alamat 
    FROM location_history 
    WHERE user_id = " . $id_team . " 
      AND DATE(timestamp) BETWEEN '" . $tanggal . "-01' AND '" . $tanggal . "-31' 
    ORDER BY DATE(timestamp) ASC, TIME(timestamp) ASC
");

while ($d = mysqli_fetch_array($result)) {  
    $sheet->setCellValue('A' . $i, $no++);  
    $sheet->setCellValue('B' . $i, $r['namalengkap']);  
    $sheet->setCellValue('C' . $i, $d['tanggal']);  
    $sheet->setCellValue('D' . $i, $d['jam']);  
    $sheet->setCellValue('E' . $i, $d['lat']);  
    $sheet->setCellValue('F' . $i, $d['lng']);  
    $sheet->setCellValue('G' . $i, $d['detail_alamat']);  
    $i++;  
}  

// auto size kolom
foreach (range('A', 'G') as $col) {  
    $sheet->getColumnDimension($col)->setAutoSize(true);  
}  

$namafile = 'location_history_' . $tanggal . '_' . $r['namalengkap'] . '-';  
$writer = new Xlsx($spreadsheet);  
$writer->save($namafile . 'report.xlsx');  

echo "<script>window.location = '" . $namafile . "report.xlsx'</script>";  
?>
