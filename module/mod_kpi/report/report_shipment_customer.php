<?php
include ('../../../config/connection.php');
include ('../../../config/encrypt.php');
include ('../../../config/library.php');




$key = "asdf$1011";
$encrypt = "$_GET[id_kustomer]";
$id = base64_decrypt($encrypt,$key);


 $id_kustomer = $id;
 $tanggal = $_GET['tanggal'];

$qu = mysqli_query($con, "select * from kustomer where id_kustomer = ".$id_kustomer."");


$currentDate = date('Y-m-d'); 




$r = mysqli_fetch_assoc($qu);

$tanggal;

function hitungHariKerja($start, $end) {
    $startDate = new DateTime($start);
    $endDate = new DateTime($end);
    $startDate->modify('+1 day'); // kalau kamu mau pickup_date tidak dihitung

    $workdays = 0;
    $interval = new DateInterval('P1D');
    $daterange = new DatePeriod($startDate, $interval, $endDate);

    foreach ($daterange as $date) {
        $day = $date->format('N'); // 1 = Senin, ..., 7 = Minggu
        if ($day < 6) {
            $workdays++;
        }
    }

    return $workdays;
}


?>
<!DOCTYPE html>
	<html>
		<head>
			<meta charset="UTF-8">
			<title>Report shipment <?php echo $r['nama_kustomer']; ?> PERIODE <?php echo $tanggal; ?></title>
			<link rel="stylesheet" type="text/css" href="../../../dist/css/airwaybill_2020.css" />	
			 <link rel="stylesheet" href="../../../plugins/font-awesome/css/font-awesome.min.css">
			<style>

		    
		    @media print {
		    .pageBreak {
		        page-break-after: always;
		    }
		}
		    </style>
		    <link rel='stylesheet' type='text/css' href='../../dist/css/airwaybill_2020.css' />
		</head>
		<center><h2>REPORT SHIPMENT <?php echo $r['nama_kustomer']; ?> PERIODE <?php echo $tanggal; ?></h2></center>
		<body>
			<br>
			<table cellspacing=0 width="100%" border="1">
			<br>

				<tr>
					<th>No</th>
					<th>Airwaybill</th>
					<th>Service</th>
					<th>Kota asal</th>
					<th>Kota Tujuan</th>
					
					<th>Tanggal Booking</th>
					<th>Tanggal pickup</th>
					<th>Outbound - Inbound</th>
					<th>Tanggal delivered</th>
					<th>SLA (hari kerja)</th>
					<th>Lead time (hari)</th>
					<th>Delivery Oleh</th>
				</tr>
				
				<?php
				$no=1;	
				$result = mysqli_query($con,"select a.id_pengiriman,tanggal, b.lead_time,airwaybill_kode, delivery_date,x_kurir(delivery_by) as delivery_oleh,alasan_terlambat_delivery,x_namacabang(delivery_by_agent) as namacabang,x_service(service) as nama_service,kota_tujuan, DATE_FORMAT(pickup_date, '%Y-%m-%d') as tanggal_pickup, manifest_received,x_inter(id_kota_asal) as inter_asal,x_inter(id_kota_tujuan) as inter_tujuan , kota_asal,created_date,DATEDIFF(delivery_date, pickup_date) AS hari_ditempuh,diskon_pickup,diskon_deliv

				 from pengiriman a 
				 left join rate_customer b on a.id_kustomer = b.id_kustomer and a.id_kota_asal = b.id_lokasi_asal and a.id_kota_tujuan=b.id_lokasi_tujuan and a.service = b.id_pelayanan



				 where a.id_kustomer='$id_kustomer' and tanggal between '".$tanggal."-01' and '".$tanggal."-31'
				 
				 and status in (6,8)  group by id_pengiriman

				 ");

				$liburNasional = [];
				$qLibur = mysqli_query($con, "SELECT tanggal FROM libur_nasional");

				while ($row = mysqli_fetch_assoc($qLibur)) {
				    $liburNasional[] = $row['tanggal'];
				}
				
				$no = 1;
				while($t=mysqli_fetch_assoc($result)){

					$workdays = 0;

				    if (!empty($t['tanggal_pickup']) && !empty($t['delivery_date'])) {
				        $pickup   = new DateTime($t['tanggal_pickup']);
				        $delivery = new DateTime($t['delivery_date']);
				        $pickup->modify('+1 day'); // kalau tidak mau hitung pickup-nya

				        $interval  = new DateInterval('P1D');
				        $delivery->modify('+1 day'); 
				        $daterange = new DatePeriod($pickup, $interval, $delivery);

				        foreach ($daterange as $date) {
				            $tgl     = $date->format('Y-m-d');
				            $hariKe  = $date->format('N'); // 1 = Senin, ..., 7 = Minggu

				            if ($hariKe < 6 && !in_array($tgl, $liburNasional)) {
				                $workdays++;
				            }
				        }
				    }

				   
				    if ($workdays > $t['lead_time']){
						echo '<tr>
							
							<td>'.$no++.'</td>
							<td>'.$t['airwaybill_kode'].'</td>
							<td>'.$t['nama_service'].'</td>
							<td>'.$t['kota_asal'].'</td>
							<td>'.$t['kota_tujuan'].'</td>	
							
							<td>'.$t['created_date'].'</td>	
							<td>'.$t['tanggal_pickup'].'</td>	
							<td>
							';

							$qu_out = mysqli_query($con,"select x_namacabang(manifest_from) as cabang_asal,

								x_namacabang(manifest_to) as cabang_tujuan,
								DATE_FORMAT(a.created_date, '%Y-%m-%d') as tanggal_outbound, b.tanggal,DATE_FORMAT(a.inbound_received, '%Y-%m-%d') as tanggal_inbound,DATEDIFF(inbound_received, a.created_date) AS outbound_ditempuh,sla,diskon_outbound,diskon_inbound

								from manifest_detail a 
								inner join manifest b on a.id_manifest = b.id_manifest 

								where a.id_pengiriman = ".$t['id_pengiriman']." and b.manifest_from > 0
								
								 ");


							
							while($row=mysqli_fetch_assoc($qu_out)){
								echo $row['cabang_asal'].' '.$row['tanggal_outbound'].'  ✈️ ' . $row['cabang_tujuan'] .' '.$row['tanggal_inbound'].' ('. $row['outbound_ditempuh'].' hari ) '.($row['diskon_outbound'] > 0 ? ' TERLAMBAT' : '').($row['diskon_inbound'] > 0 ? ' TERLAMBAT' : '').'<br>'; 
							}

							echo '


							</td>	
							<td>'.$t['delivery_date'].'</td>							
							<td>'.( $t['lead_time'] > 0 ? $t['lead_time'] : 'belum ada SLA').'</td>						
							<td>'.$workdays.($workdays > $t['lead_time'] ? ' SLA TERLAMBAT' : '').'</td>
							
							<td>'.$t['delivery_oleh'].' ( '.$t['namacabang'].' )'.($t['diskon_deliv'] > 0 ? ' kurir terlambat deliv' : '').'</td>
						</tr>
						';
					
					}
				}
				echo '
                
			</table>
			
		</body>
</html>';