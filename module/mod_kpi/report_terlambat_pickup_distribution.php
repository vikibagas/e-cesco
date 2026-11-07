<?php
include ('../../config/connection.php');
include ('../../config/encrypt.php');
include ('../../config/library.php');




$key = "asdf$1011";
$encrypt = "$_GET[id_team]";
$id = base64_decrypt($encrypt,$key);


 $id_team = $id;
 $tanggal = $_GET['tanggal'];

$qu = mysqli_query($con, "select * from user where id_user = ".$id_team."");


$currentDate = date('Y-m-d'); 




$r = mysqli_fetch_assoc($qu);

$tanggal;


?>
<!DOCTYPE html>
	<html>
		<head>
			<meta charset="UTF-8">
			<title>Pickup terlambat</title>
			<style>
		    
		    @media print {
		    .pageBreak {
		        page-break-after: always;
		    }
		}
		    </style>
		    <link rel='stylesheet' type='text/css' href='../../dist/css/airwaybill_2020.css' />
		</head>
		<body>
			<br>
			<center><h2>REPORT TERLAMBAT PICKUP <?php echo strtoupper($r['namalengkap']); ?> PERIODE <?php echo $tanggal; ?></h2></center>
			<br>
			<table cellspacing=0 width="100%">

				<tr>
					<th>No</th>
					<th>Airwaybill</th>
					<th>Service</th>
					<th>Kota Asal</th>
					<th>Booking</th>					
					<th>Tanggal pickup</th>					
					<th>Alasan Terlambat</th>
					<th>Dipickup Oleh</th>
				</tr>
				
				<?php
				$no=1;	
				$result = mysqli_query($con,"select tanggal, airwaybill_kode, pickup_date,pickup_date_request,x_kurir(pickup_by) as pickup_oleh, pickup_by_agent,alasan_terlambat_pickup,x_namacabang(pickup_by_agent) as namacabang,x_service(service) as nama_service,kota_asal,manifest_pickup_date,DATE_FORMAT(created_date, '%Y-%m-%d') as created_date

				 from pengiriman where pickup_by='$id_team' and pickup_date between '".$tanggal."-01' and '".$tanggal."-31'
				 
				 and status in (6,8) and terlambat_pickup > 0

				 ");
			
				
				$no = 1;
				while($t=mysqli_fetch_assoc($result)){
					echo '<tr>
						
						<td>'.$no.'</td>
						<td>'.$t['airwaybill_kode'].'</td>
						<td>'.$t['nama_service'].'</td>
						<td>'.$t['kota_asal'].'</td>						
						<td>'.$t['created_date'].'</td>						
						<td>'.$t['pickup_date'].'</td>						
						<td>'.$t['alasan_terlambat_pickup'].'</td>
						<td>'.$t['pickup_oleh'].' ( '.$t['namacabang'].' )</td>
					</tr>
					';
				
				$no++;	
				}
				echo '
                
			</table>
			
		</body>
</html>';