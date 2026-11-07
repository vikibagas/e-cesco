<?php
include ('../../config/connection.php');
include ('../../config/encrypt.php');
include ('../../config/library.php');





$key = "asdf$1011";
$encrypt = "$_GET[id_cabang]";
$id = base64_decrypt($encrypt,$key);


 $id_cabang = $id;
 $tanggal = $_GET['tanggal'];

$qu = mysqli_query($con, "select * from cabang where id_cabang = ".$id_cabang."");


$currentDate = date('Y-m-d'); 
$r = mysqli_fetch_assoc($qu);

if($_GET['type'] == 'excel'){
	header("Content-type: application/vnd-ms-excel");
	header('Content-Disposition: attachment; filename="terlambat-pickup_agen'.$r['nama_cabang'].' - '. $tanggal.'.xls"');
}






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
			<center><h2>REPORT TERLAMBAT PICKUP AGENT <?php echo strtoupper($r['nama_cabang']); ?> PERIODE <?php echo $tanggal; ?></h2></center>
			<br>
			<table cellspacing=0 width="100%">

				<tr>
					<th>No</th>
					<th>Airwaybill</th>
					<th>Service</th>
					<th>Kota Asal</th>
					<th>Booking</th>					
					<th>Tanggal pickup</th>
					<th>SLA PICKUP</th>	
					<th>SLA KONTRAK</th>					
					<th>Alasan Terlambat</th>
					<th>Dipickup Oleh</th>
				</tr>
				
				<?php
				$no=1;	
				$result = mysqli_query($con,"select tanggal, airwaybill_kode, pickup_date,pickup_date_request,x_kurir(pickup_by) as pickup_oleh, pickup_by_agent,alasan_terlambat_pickup,x_namacabang(pickup_by_agent) as namacabang,x_service(service) as nama_service,kota_asal,manifest_pickup_date,DATE_FORMAT(created_date, '%Y-%m-%d') as created_date,

					(
				        DATEDIFF(pickup_date, pickup_date_request)
				        -
				        (
				            (DATEDIFF(pickup_date, pickup_date_request)
				             + WEEKDAY(pickup_date_request) + 1
				            ) DIV 7
				        )
				    ) AS sla_aktual_pickup,
				      CASE
				        WHEN service IN (1,2) THEN '0 - 1 hari'

				        WHEN service IN (3,5)
				             AND id_kota_asal = ".$r['id_kota_baru']." THEN
				            '0 - 1 hari'

				        WHEN service IN (3,5)
				             AND rg.is_kota_madya = 1 THEN
				            '1 - 2 hari'

				        WHEN service IN (3,5)
				             AND (rg.is_kota_madya IS NULL OR rg.is_kota_madya != 1) THEN
				            '2 - 2 hari'

				        ELSE '-'
				    END AS sla_kontrak_pickup

				 from pengiriman p 
				  LEFT JOIN regencies rg
   					 ON rg.id = p.id_kota_asal

				 where pickup_by_agent='$id_cabang' and pickup_date between '".$tanggal."-01' and '".$tanggal."-31'

				
				 
				 and status in (6,8) and terlambat_pickup > 0

				 ");
				
				$cek = mysqli_num_rows($result);

				if($cek < 1){
					echo '<tr><td colspan="8"><h2>Tidak ada data pickup terlambat</h2></td></tr>';
				}


				
				$no = 1;
				while($t=mysqli_fetch_assoc($result)){
					echo '<tr>
						
						<td>'.$no.'</td>
						<td>'.$t['airwaybill_kode'].'</td>
						<td>'.$t['nama_service'].'</td>
						<td>'.$t['kota_asal'].'</td>						
						<td>'.$t['created_date'].'</td>						
						<td>'.$t['pickup_date'].'</td>	
						<td>'.$t['sla_aktual_pickup'].' hari</td>
						<td>'.$t['sla_kontrak_pickup'].'</td>					
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