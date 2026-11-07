<?php
session_start();

include ('../../config/connection.php');
include ('../../config/encrypt.php');
include ('../../config/library.php');

	
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
    
	


	$query 		= mysqli_query($con,"select nama_kustomer from kustomer where id_kustomer=$id_kustomer
                                ");
			$k 			= mysqli_fetch_assoc($query);


require '../../config/phpspreadsheet/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Border;
//use PhpOffice\PhpSpreadsheet\PHPExcel_Style_Fill;



$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet(); 

//use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;













            $cari = "select a.id,kode_barang,nama_barang,serial_number, pemilik_customer,x_namakustomer(pemilik_customer) as nama_kustomer,stok_awal_masuk, stok_terkini,satuan,a.created_date,x_namadepartement(departement) as nama_departement,expired_date,expired_date_action,dengan_serial,
            GROUP_CONCAT(r.nama_rak ORDER BY r.nama_rak SEPARATOR ', ') AS lokasi_rak
                                
                            FROM warehouse_barang a

                            left JOIN rak_barang br ON a.id = br.id_barang
							left JOIN warehouse_rak r ON br.id_rak = r.id
                                
                            where $where_departement_ pemilik_customer='$id_kustomer' and aktif='Y'
                            GROUP BY a.id
                                
                            order by nama_barang

                                ";
     
			$sql = mysqli_query($con,$cari);
			
            $i=2;
			$j=1;
		
			$sheet->setCellValue('A1', 'No')->getStyle('A1')->getFont()->setBold( true );  
			$sheet->getStyle('A1')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

            $sheet->setCellValue('B1', 'Nama Produk')->getStyle('B1')->getFont()->setBold( true );  
			$sheet->getStyle('B1')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

            $sheet->setCellValue('C1', 'Kode Produk')->getStyle('C1')->getFont()->setBold( true );  
			$sheet->getStyle('C1')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);


                            $sheet->setCellValue('D1', 'Serial Number')->getStyle('D1')->getFont()->setBold( true );  

							  $sheet->getStyle('D1')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);


                           $sheet->setCellValue('E1', 'Stok Terkini')->getStyle('E1')->getFont()->setBold( true );  

							  $sheet->getStyle('E1')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);


                           $sheet->setCellValue('F1', 'Departement')->getStyle('F1')->getFont()->setBold( true );  

							  $sheet->getStyle('F1')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

                            $sheet->setCellValue('G1', 'User')->getStyle('G1')->getFont()->setBold( true );  

							  $sheet->getStyle('G1')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

                            $sheet->setCellValue('H1', 'Expired Date - Keep')->getStyle('H1')->getFont()->setBold( true );  

							  $sheet->getStyle('H1')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);


                           $sheet->setCellValue('I1', 'Posisi Rak')->getStyle('I1')->getFont()->setBold( true );  

							  $sheet->getStyle('I1')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

                            $sheet->setCellValue('J1', 'Stok Awal')->getStyle('J1')->getFont()->setBold( true );  

							  $sheet->getStyle('J1')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

                            $sheet->setCellValue('K1', 'Tanggal Entry')->getStyle('K1')->getFont()->setBold( true );  

							  $sheet->getStyle('K1')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
			
			
			while($t=mysqli_fetch_assoc($sql)){

               	$sheet->setCellValue('A'.$i, $j)->getStyle('B'.$i)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);  	

				$sheet->setCellValue('B'.$i, $t['nama_barang'])->getStyle('B'.$i)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);  						
				$sheet->setCellValue('C'.$i, $t['kode_barang'])->getStyle('C'.$i)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN); 		

				if ($t['dengan_serial'] == 'Y') {
				    $q_sn = "SELECT serial_number FROM warehouse_barang_serial WHERE id_produk= ".$t['id']." AND aktif='Y'";
				    $q_sn = mysqli_query($con, $q_sn);
				    $cek_sn = mysqli_num_rows($q_sn);

				    if ($cek_sn > 0) {
				        $sn_list = [];
				        while ($r = mysqli_fetch_assoc($q_sn)) {
				            $sn_list[] = $r['serial_number'];
				        }
				        // Gabungkan semua serial dengan newline
				        $sn = implode("\n", $sn_list);
				    } else {
				        $sn = '-';
				    }
				} else {
				    $sn = '-';
				}


				$sheet->setCellValue('D'.$i, $sn)->getStyle('D'.$i)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN); 		$sheet->getStyle('D'.$i)->getAlignment()->setWrapText(true);			

                $sheet->setCellValue('E'.$i, $t['stok_terkini'].' '.$t['satuan'])->getStyle('E'.$i)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);	
                $sheet->setCellValue('F'.$i, $t['nama_departement'])->getStyle('F'.$i)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);	
                 $sheet->setCellValue('G'.$i, '-')->getStyle('G'.$i)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);	
                $sheet->setCellValue('H'.$i, $t['expired_date'].' - '.$t['expired_date_action'])->getStyle('H'.$i)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);	
               
                $sheet->setCellValue('I'.$i, $t['lokasi_rak'])->getStyle('I'.$i)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);	
                $sheet->setCellValue('j'.$i, $t['stok_awal_masuk']. ' '.$t['satuan'])->getStyle('j'.$i)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                $sheet->setCellValue('k'.$i, $t['created_date'])->getStyle('k'.$i)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);	

                $sheet->getRowDimension($i)->setRowHeight(-1); 
							
                $i++;
				$j++;
                            

			}
$sheet->getColumnDimension('A')->setAutoSize(true);
$sheet->getColumnDimension('B')->setAutoSize(true);
$sheet->getColumnDimension('C')->setAutoSize(true);
$sheet->getColumnDimension('D')->setAutoSize(true);
$sheet->getColumnDimension('E')->setAutoSize(true);
$sheet->getColumnDimension('F')->setAutoSize(true);
$sheet->getColumnDimension('G')->setAutoSize(true);
$sheet->getColumnDimension('H')->setAutoSize(true);
$sheet->getColumnDimension('I')->setAutoSize(true);
$sheet->getColumnDimension('J')->setAutoSize(true);
$sheet->getColumnDimension('K')->setAutoSize(true);

$sheet->getStyle('A1:K1000')->getAlignment()
      ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
      ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

			

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

$filename = "".$k['nama_kustomer']."-".$k['nama_kustomer'].".xlsx";

$writer = new Xlsx($spreadsheet);
//$writer->save('okereports.xlsx');
//echo "<script>window.location = 'okereports.xlsx'</script>";
$writer->save($filename);
echo "<script>window.location = '".$filename."'</script>";



?>