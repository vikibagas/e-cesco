<?php
require 'config/phpspreadsheet/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet(); 


$sheet->setCellValue('A1', 'ID'); 
$sheet->setCellValue('B1', 'Name'); 
$sheet->setCellValue('C1', 'Name2'); 
$sheet->setCellValue('D1', 'Name3');
$sheet->setCellValue('E1', 'Type'); 

// Write an .xlsx file  
$date = date('d-m-y-'.substr((string)microtime(), 1, 8));
$date = str_replace(".", "", $date);
$filename = "export_".$date.".xlsx";

try {
    $writer = new Xlsx($spreadsheet);
    $writer->save($filename);
    $content = file_get_contents($filename);
} catch(Exception $e) {
    exit($e->getMessage());
}

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="' . urlencode($filename) .'"' );

echo $content;  // this actually send the file content to the browser

unlink($filename);

?>