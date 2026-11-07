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
	header('Content-Disposition: attachment; filename="terlambat-delivery_agen'.$r['nama_cabang'].' - '. $tanggal.'.xls"');
}


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
		<body>
			<br>
			<center><h2>REPORT TERLAMBAT DELIVERY AGENT <?php echo strtoupper($r['nama_cabang']); ?> PERIODE <?php echo $tanggal; ?></h2></center>
			<br>
			<table cellspacing=0 width="100%">

				<tr>
					<th>NO</th>
					<th>AIRWAYBILL</th>
					<th>SERVICE</th>
					<th>KOTA TUJUAN</th>
					<th>TANGGAL SAMPAI KOTA TUJUAN</th>					
					<th>TANGGAL DELIV</th>	
					<th>SLA AKTUAL</th>
					<th>SLA KONTRAK</th>				
					<th>ALASAN TERLAMBAT</th>
					<th>DELIVERY OLEH</th>
				</tr>
				
				<?php
				$no=1;	
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

				 $cek = mysqli_num_rows($result);

				if($cek < 1){
					echo '<tr><td colspan="8"><h2>Tidak ada data delivery terlambat</h2></td></tr>';
				}									
				
				$no = 1;
				while($t=mysqli_fetch_assoc($result)){
					echo '<tr>
						
						<td>'.$no.'</td>
						<td>'.$t['airwaybill_kode'].'</td>
						<td>'.$t['nama_service'].'</td>
						<td>'.$t['kota_tujuan'].'</td>						
						<td>'.($t['inter_asal'] == 'Y' &&  $t['inter_tujuan'] == 'Y' ? $t['tanggal_pickup'] : $t['manifest_received']).'</td>
						<td>'.$t['delivery_date'].'</td>
						<td>'.$t['sla_aktual'].' hari</td>			
						<td>'.$t['sla_kontrak'].'</td>									
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