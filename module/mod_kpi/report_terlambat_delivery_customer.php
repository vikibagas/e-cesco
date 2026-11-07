<?php
include ('../../config/connection.php');
include ('../../config/encrypt.php');
include ('../../config/library.php');




$key = "asdf$1011";
$encrypt = "$_GET[id_kustomer]";
$id = base64_decrypt($encrypt,$key);


 $id_kustomer = $id;
 $tanggal = $_GET['tanggal'];

$qu = mysqli_query($con, "select * from kustomer where id_kustomer = ".$id_kustomer."");


$currentDate = date('Y-m-d'); 




$r = mysqli_fetch_assoc($qu);

$tanggal;


?>
<!DOCTYPE html>
	<html>
		<head>
			<meta charset="UTF-8">
			<title>Delivery terlambat</title>
			<style>
		    
		    @media print {
		    .pageBreak {
		        page-break-after: always;
		    }
		}
		    </style>
		    <link rel='stylesheet' type='text/css' href='../../dist/css/airwaybill_2020.css' />
		</head>
		<center><h2>REPORT TERLAMBAT DELIVERY <?php echo $r['nama_kustomer']; ?> PERIODE <?php echo $tanggal; ?></h2></center>
		<body>
			<br>
			<table cellspacing=0 width="100%">
			<br>

				<tr>
					<th>No</th>
					<th>Airwaybill</th>
					<th>Service</th>
					<th>Kota Tujuan</th>
					<th>Tanggal pickup/Sampai di kota tujuan</th>
					<th>Tanggal delivered</th>
					<th>Alasan Terlambat</th>
					<th>Delivery Oleh</th>
				</tr>
				
				<?php
				$no=1;	
				$result = mysqli_query($con,"select tanggal, airwaybill_kode, delivery_date,x_kurir(delivery_by) as delivery_oleh,alasan_terlambat_delivery,x_namacabang(delivery_by_agent) as namacabang,x_service(service) as nama_service,kota_tujuan, DATE_FORMAT(pickup_date, '%Y-%m-%d') as tanggal_pickup, manifest_received,x_inter(id_kota_asal) as inter_asal,x_inter(id_kota_tujuan) as inter_tujuan 

				 from pengiriman where id_kustomer='$id_kustomer' and tanggal between '".$tanggal."-01' and '".$tanggal."-31'
				 
				 and status in (6,8) and terlambat_delivery > 0

				 ");

			
			


				

				

			
				
				$no = 1;
				while($t=mysqli_fetch_assoc($result)){
					echo '<tr>
						
						<td>'.$no.'</td>
						<td>'.$t['airwaybill_kode'].'</td>
						<td>'.$t['nama_service'].'</td>
						<td>'.$t['kota_tujuan'].'</td>						
						<td>'.($t['inter_asal'] == 'Y' &&  $t['inter_tujuan'] == 'Y' ? $t['tanggal_pickup'] : $t['manifest_received']).'</td>
						<td>'.$t['delivery_date'].'</td>
						<td>'.$t['alasan_terlambat_delivery'].'</td>
						<td>'.$t['delivery_oleh'].' ( '.$t['namacabang'].' )</td>
					</tr>
					';
				
				$no++;	
				}
				echo '
                
			</table>
			
		</body>
</html>';