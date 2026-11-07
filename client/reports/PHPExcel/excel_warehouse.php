<?php
session_start();

include ('../../config/connection.php');
include ('../../config/encrypt.php');
include ('../../config/library.php');
//include ('phpqrcode/qrlib.php');

	//$dari = $_GET['dari'];
	//$sampai = $_GET['sampai'];
	
    $id_kustomer = $_GET['id_kustomer'];
    $gudang = $_GET['gudang'];


                  if(isset($_GET['id_departement'])){
                            if($_GET['id_departement'] != 0){
                                $where_departement_ = "departement = ".$_GET['id_departement']." and";
                            }else{
                                $where_departement_ = "";
                            }
                            
                        
                        }else{
                            $where_departement_ = "";
                        }
    
	
	//$key 			= "asdf$1011";
	//$encryptUser 	= $_SESSION['id_user'];
	//$id_user 		= base64_decrypt($encryptUser,$key);

	$query 		= mysqli_query($con,"select nama_kustomer from kustomer where id_kustomer=$id_kustomer
                                ");
			$r 			= mysqli_fetch_assoc($query);


require '../../config/phpspreadsheet/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Border;
//use PhpOffice\PhpSpreadsheet\PHPExcel_Style_Fill;



$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet(); 

//use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;













            $cari = "select id,kode_barang,nama_barang,serial_number, pemilik_customer,x_namakustomer(pemilik_customer) as nama_kustomer,stok_awal_masuk, stok_terkini,satuan,x_namarak(id_rak) as rak,created_date,x_namadepartement(departement) as nama_departement
                                
                            FROM warehouse_barang
                                
                            where $where_departement_ pemilik_customer='$id_kustomer'  and x_idgudang(id_rak)='$gudang' and aktif='Y'
                                
                            order by nama_barang

                                ";
     
			$sql = mysqli_query($con,$cari);
			
            $i=1;
			$j=$i+1;
			$k=$i+2;
			
			while($t=mysqli_fetch_assoc($sql)){

               $img = '../../module/mod_warehouse-barang/foto_barang/'.$t['kode_barang'].'.jpg';

               if (file_exists($img)) {
               		$foto = $img;
               }else{
               		$foto =  $img = '../../dist/img/logocesco2024.png';
               }


							$sheet->mergeCells('A'.$i.':L'.$i)->setCellValue('A'.$i, $t['nama_barang'].' ( '.$t['kode_barang'].' )')->getStyle('A'.$i)->getFont()->setBold( true );  						
							$sheet->getStyle('A'.$i.':L'.$i)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_MEDIUM);

							$drawing = new  PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
							$drawing->setName('PhpSpreadsheet logo');
							$drawing->setDescription('PhpSpreadsheet logo');
							$drawing->setPath($foto);
							$drawing->setHeight(90);
							$drawing->setCoordinates('N'.$i);
							$drawing->setOffsetX(10);
							$drawing->setWorksheet($spreadsheet->getActiveSheet());

							
							$sheet->mergeCells('A'.$j.':B'.$j)->setCellValue('A'.$j, 'Stok '.$t['stok_terkini'].' '.$t['satuan'])->getStyle('A'.$j)->getFont()->setBold( true );
							$sheet->getStyle('A'.$j.':B'.$j)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_MEDIUM);

                           


                            $sheet->mergeCells('C'.$j.':L'.$j)->setCellValue('C'.$j, 'Departement '.$t['nama_departement'])->getStyle('C'.$j)->getFont()->setBold( true );  
                            $sheet->getStyle('C'.$j.':L'.$j)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_MEDIUM);

                           
                           // $sheet->setCellValue('C'.$j, $img); 
							
                            
                            $q = "select a.id,out_in,id_produk,jumlah,stok_sebelum,stok_menjadi,x_user(a.created_by) as nama_user,a.created_date,dokumen,awb,a.id_pengiriman,a.catatan, x_noawb(a.id_pengiriman) as no_awb,serial_number, nama_pengirim,kota_tujuan, nama_penerima, delivery_name
                                
                            FROM warehouse_history a left join pengiriman b on a.id_pengiriman = b.id_pengiriman
                                
                            where id_produk='".$t['id']."' 
                                
                            order by a.created_date desc, a.id desc

                                ";




                            $sheet->setCellValue('A'.$k, 'Tanggal')->getStyle('A'.$k)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_MEDIUM);
						    



                            $sheet->setCellValue('B'.$k, 'Out/In')->getStyle('B'.$k)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_MEDIUM);
                            $sheet->setCellValue('C'.$k, 'Jumlah')->getStyle('C'.$k)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_MEDIUM);
                            $sheet->setCellValue('D'.$k, 'Stok Sebelum')->getStyle('D'.$k)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_MEDIUM);
                            $sheet->setCellValue('E'.$k, 'Stok Menjadi')->getStyle('E'.$k)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_MEDIUM);
                            $sheet->setCellValue('F'.$k, 'Catatan')->getStyle('F'.$k)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_MEDIUM);
                            $sheet->setCellValue('G'.$k, 'SN')->getStyle('G'.$k)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_MEDIUM);
                            $sheet->setCellValue('H'.$k, 'AWB')->getStyle('H'.$k)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_MEDIUM);
                            $sheet->setCellValue('I'.$k, 'PENGIRIM')->getStyle('I'.$k)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_MEDIUM);
                            $sheet->setCellValue('J'.$k, 'PENERIMA')->getStyle('J'.$k)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_MEDIUM);
                            $sheet->setCellValue('K'.$k, 'TUJUAN')->getStyle('K'.$k)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_MEDIUM);
                            $sheet->setCellValue('L'.$k, 'YANG MENERIMA')->getStyle('L'.$k)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_MEDIUM);


						    
                            
                         // $spreadsheet->getActiveSheet()->getStyle('N1')->getFill()->applyFromArray(array('type' => PHPExcel_Style_Fill::FILL_SOLID,'startcolor' => array('rgb' =>'FF1E1E')));
                            
                         

                            
                        $l=$k+1;

                        
                        $sql_history = mysqli_query($con, $q);

                        $cek_sql = mysqli_num_rows($sql_history);

						if($cek_sql > 0){
							while($row = mysqli_fetch_assoc($sql_history)){
	                            $sheet->setCellValue('A'.$l, $row['created_date'])->getStyle('A'.$l)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_MEDIUM);
	                           

	                            $sheet->setCellValue('B'.$l, $row['out_in'])->getStyle('B'.$l)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_MEDIUM);;
	                            //setborder('B'.$l);
	                            $sheet->setCellValue('C'.$l, $row['jumlah'])->getStyle('C'.$l)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_MEDIUM);
	                            $sheet->setCellValue('D'.$l, $row['stok_sebelum'])->getStyle('D'.$l)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_MEDIUM);
	                            $sheet->setCellValue('E'.$l, $row['stok_menjadi'])->getStyle('E'.$l)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_MEDIUM);
	                            $sheet->setCellValue('F'.$l, $row['catatan'])->getStyle('F'.$l)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_MEDIUM);
	                            $sheet->setCellValue('G'.$l, $row['serial_number'])->getStyle('G'.$l)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_MEDIUM);
	                            $sheet->setCellValue('H'.$l, ($row['no_awb'] != '' ? $row['no_awb'] : $row['awb']))->getStyle('H'.$l)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_MEDIUM);
	                            $sheet->setCellValue('I'.$l, $row['nama_pengirim'])->getStyle('I'.$l)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_MEDIUM);
	                            $sheet->setCellValue('J'.$l, $row['nama_penerima'])->getStyle('J'.$l)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_MEDIUM);
	                            $sheet->setCellValue('K'.$l, $row['kota_tujuan'])->getStyle('K'.$l)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_MEDIUM);
	                            $sheet->setCellValue('L'.$l, $row['delivery_name'])->getStyle('L'.$l)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_MEDIUM);
	                           
	                            $l++;
                        	}
						}else{
							 $sheet->mergeCells('A'.$l.':L'.$l)->setCellValue('A'.$l, 'Belum ada history keluar masuk barang')->getStyle('A'.$l.':L'.$l)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_MEDIUM);
							 $l++;
						}
                        

							$i=$l+2;
							$j=$i+1;
                            $k=$i+2;
                            //$j=$k+1;

					//akhir if ($stts == '' || ($stts == 'loss' && $profit_loss < 0)){

			}//akhir while

			

//AKHIR QUERY



// Write an .xlsx file  

/*$styleArray = [
			'borders' => [
				'allBorders' => [
					'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
				],
			],
		];
$i = $i - 1;
$sheet->getStyle('A1:I'.$i)->applyFromArray($styleArray);*/

$filename = "".$r['nama_kustomer']."-".$r['nama_kustomer']."-".'ok'.".xlsx";

$writer = new Xlsx($spreadsheet);
//$writer->save('okereports.xlsx');
//echo "<script>window.location = 'okereports.xlsx'</script>";
$writer->save($filename);
echo "<script>window.location = '".$filename."'</script>";


/*
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

unlink($filename);*/

?>