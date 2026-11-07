<?php
include ('../../config/connection.php');
include ('../../config/encrypt.php');
include ('../../config/library.php');

$key = "asdf$1011";
$encrypt = "$_GET[id_vendor]";
$id_vendor = base64_decrypt($encrypt,$key);

$tanggal = $_GET['tanggal'];

$qu = mysqli_query($con, "SELECT * FROM manifest_vendor WHERE id = '".$id_vendor."'");
$r = mysqli_fetch_assoc($qu);

if(isset($_GET['type']) && $_GET['type'] == 'excel'){
	header("Content-type: application/vnd-ms-excel");
	header('Content-Disposition: attachment; filename="terlambat-vendor_'.$r['name'].' - '.$tanggal.'.xls"');
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>Vendor Terlambat</title>
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
<center>
	<h2>
		REPORT TERLAMBAT VENDOR <?php echo strtoupper($r['name']); ?>  
		PERIODE <?php echo $tanggal; ?>
	</h2>
</center>
<br>

<table cellspacing="0" width="100%">
	<tr>
		<th>NO</th>
		<th>AIRWAYBILL</th>
		<th>SERVICE</th>
		<th>CABANG ASAL</th>
		<th>CABANG TUJUAN</th>
		<th>TANGGAL BERANGKAT</th>
		<th>TANGGAL DITERIMA</th>
		<th>SLA AKTUAL</th>
		<th>SLA KONTRAK 2</th>
		<th>ALASAN TERLAMBAT</th>
		
	</tr>

<?php
$no = 1;

$result = mysqli_query($con,"
	SELECT 
    b.airwaybill_kode,
    x_service(b.service) AS nama_service,
    x_namacabang(c.manifest_from) AS cabang_asal,
    x_namacabang(c.manifest_to) AS cabang_tujuan,
    c.tanggal AS tanggal_berangkat,
    DATE_FORMAT(a.inbound_received, '%Y-%m-%d') AS tanggal_diterima,

    /* ======================
       SLA AKTUAL
       ====================== */
   CASE
  WHEN b.service IN (1,2,3,5) THEN
    (
      DATEDIFF(a.inbound_received, c.tanggal)
      - (DATEDIFF(a.inbound_received, c.tanggal) DIV 7) * 2
      - CASE
          WHEN WEEKDAY(a.inbound_received) = 6 THEN 1
          WHEN WEEKDAY(a.inbound_received) = 5 THEN 1
          ELSE 0
        END
      - CASE
          WHEN WEEKDAY(c.tanggal) = 6 THEN 1
          WHEN WEEKDAY(c.tanggal) = 5 THEN 1
          ELSE 0
        END
    )
  ELSE
    DATEDIFF(a.inbound_received, c.tanggal)
END AS sla_hari,

    a.diskon_inbound,
    x_idkotacabang(c.manifest_to) AS kota_inbound,

    /* ======================
       SLA KONTRAK FINAL
       ====================== */
    CASE
        /* SERVICE 1 & 2 */
        WHEN b.service IN (1,2) THEN '1 - 1 hari'

        /* SERVICE 3 */
        WHEN b.service = 3 THEN '1 - 2 hari'

        /* SERVICE 5 */
        WHEN b.service = 5 THEN
			    CASE
			        /* OUTBOUND */
			        WHEN c.manifest_from = 39 THEN
			            CONCAT(
			                CASE
			                    WHEN COALESCE(s_out.sla_min, 0) = 0
			                        THEN s_out.sla_max - 1
			                    ELSE s_out.sla_min
			                END,
			                ' - ',
			                s_out.sla_max,
			                ' hari'
			            )

			        /* INBOUND */
			        WHEN c.manifest_to = 39 THEN
			            CONCAT(
			                CASE
			                    WHEN COALESCE(s_in.sla_min, 0) = 0
			                        THEN s_in.sla_max - 1
			                    ELSE s_in.sla_min
			                END,
			                ' - ',
			                s_in.sla_max,
			                ' hari'
			            )

			        /* CROSS */
			        ELSE
			            CONCAT(
			                CASE
			                    WHEN COALESCE(s_cross.sla_min, 0) = 0
			                        THEN s_cross.sla_max - 1
			                    ELSE s_cross.sla_min
			                END,
			                ' - ',
			                s_cross.sla_max,
			                ' hari'
			            )
			    END

        ELSE '-'
    END AS sla_kontrak,

    CASE
		    WHEN b.service IN (1,2) THEN '1 - 1 hari'

		    WHEN b.service = 3 THEN '1 - 2 hari'

		    WHEN b.service = 5 THEN
		        CONCAT(
		            IF(a.sla - 1 < 1, 1, a.sla - 1),
		            ' - ',
		            a.sla,
		            ' hari'
		        )

		    ELSE '-'
		END AS sla_kontrak_dua,

    a.alasan_terlambat_inbound

FROM manifest_detail a
INNER JOIN pengiriman b 
    ON a.id_pengiriman = b.id_pengiriman
INNER JOIN manifest c 
    ON a.id_manifest = c.id_manifest

/* ======================
   JOIN SLA OUTBOUND
   ====================== */
LEFT JOIN sla s_out
    ON s_out.kota_asal = 9999
    AND s_out.kota_tujuan = x_idkotacabang(c.manifest_to)
    AND s_out.service = b.service

/* ======================
   JOIN SLA INBOUND
   ====================== */
LEFT JOIN sla_in s_in
    ON s_in.kota_tujuan = 9999
    AND s_in.kota_asal = x_idkotacabang(c.manifest_from)
    AND s_in.service = b.service

/* ======================
   JOIN SLA CROSS
   ====================== */
LEFT JOIN sla_cross s_cross
    ON s_cross.kota_asal = x_idkotacabang(c.manifest_from)
    AND s_cross.kota_tujuan = x_idkotacabang(c.manifest_to)
    AND s_cross.service = b.service

WHERE c.manifest_via = '".$r['name']."'
  AND c.tanggal BETWEEN '".$tanggal."-01' AND '".$tanggal."-31'
  AND b.status IN (6,8)
  AND a.diskon_inbound > 0
");

$cek = mysqli_num_rows($result);

if($cek < 1){
	echo '<tr>
			<td colspan="10"><h2>Tidak ada data vendor terlambat</h2></td>
		  </tr>';
}

while($t = mysqli_fetch_assoc($result)){
	echo '
	<tr>
		<td>'.$no.'</td>
		<td>'.$t['airwaybill_kode'].'</td>
		<td>'.$t['nama_service'].'</td>
		<td>'.$t['cabang_asal'].'</td>
		<td>'.$t['cabang_tujuan'].'</td>
		<td>'.$t['tanggal_berangkat'].'</td>
		<td>'.$t['tanggal_diterima'].'</td>
		<td>'.$t['sla_hari'].' hari</td>
		<td> '.$t['sla_kontrak_dua'].'</td>
		<td>'.$t['alasan_terlambat_inbound'].'</td>
		
	</tr>
	';
	$no++;
}
?>

</table>

</body>
</html>
