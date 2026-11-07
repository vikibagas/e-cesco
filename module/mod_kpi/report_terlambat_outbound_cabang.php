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
			<title>Mengirim ke vendor terlambat</title>
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
			<center><h2>REPORT TERLAMBAT MENGIRIM KE VENDOR, AGENT <?php echo strtoupper($r['nama_cabang']); ?> PERIODE <?php echo $tanggal; ?></h2></center>
			<br>
			<table cellspacing=0 width="100%">

				<tr>
					<th>No</th>
					<th>Airwaybill</th>
					<th>Service</th>					
					<th>Tanggal pickup/Received transit</th>					
					<th>Tanggal mengirim ke vendor</th>					
					<th>Tujuan</th>					
					<th>Vendor</th>					
					<th>Alasan Terlambat</th>					
				</tr>
				
				<?php
				$no=1;	
				$result = mysqli_query($con,"SELECT  
				    a.id_manifest_detail, 
				    a.diskon_outbound, 
				    a.alasan_terlambat_outbound, 
				    c.airwaybill_kode, 
				    x_service(c.service) AS nama_service,
				    b.manifest_from,
				    c.pickup_by_agent,

				    
				    DATE_FORMAT(
				        IF (
				            b.manifest_from = c.pickup_by_agent,
				          
				            c.pickup_date,

				           
				            (
				                SELECT  md2.inbound_received
				                FROM    manifest_detail md2
				                JOIN    manifest       m2 ON m2.id_manifest = md2.id_manifest
				                WHERE   md2.id_pengiriman = a.id_pengiriman
				                  AND   m2.manifest_to    = '$id_cabang'
				                ORDER BY md2.inbound_received DESC  
				                LIMIT 1
				            )
				        ),
				        '%Y-%m-%d'
				    ) AS tanggal_awal,

				    a.inbound_received,
				    b.manifest_via,
				    x_namacabang(b.manifest_to)               AS cabang_tujuan,
				    DATE_FORMAT(a.created_date, '%Y-%m-%d')   AS tanggal_outbound

				FROM        manifest_detail a
				INNER JOIN  manifest        b ON a.id_manifest   = b.id_manifest
				INNER JOIN  pengiriman      c ON a.id_pengiriman = c.id_pengiriman
				WHERE       b.manifest_from = '$id_cabang'
				  AND       b.tanggal BETWEEN CONCAT('$tanggal','-01')
				                          AND CONCAT('$tanggal','-31')
				  AND       c.status IN (6,8)
				  AND       a.diskon_outbound > 0

				 ");

			
				
				$cek = mysqli_num_rows($result);

				if($cek < 1){
					echo '<tr><td colspan="8"><h2>Tidak ada data terlambat</h2></td></tr>';
				}


				
				$no = 1;
				while($t=mysqli_fetch_assoc($result)){
					echo '<tr>
						
						<td>'.$no.'</td>
						<td>'.$t['airwaybill_kode'].'</td>
						<td>'.$t['nama_service'].'</td>
						<td>'.$t['tanggal_awal'].'</td>						
						<td>'.$t['tanggal_outbound'].'</td>						
						<td>'.$t['cabang_tujuan'].'</td>						
						<td>'.$t['manifest_via'].'</td>
						<td>'.$t['alasan_terlambat_outbound'].'</td>
					</tr>
					';
				
				$no++;	
				}
				echo '
                
			</table>
			
		</body>
</html>';