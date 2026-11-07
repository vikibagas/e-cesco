<?php
include ('../../config/connection.php');
include ('../../config/encrypt.php');
include ('../../config/library.php');

$periode = $_GET['periode'];

header("Content-type: application/vnd-ms-excel");
header('Content-Disposition: attachment; filename="Invoice-Customer-'.$periode.'.xls"');


?>
<!DOCTYPE html>
<html>
	<head>
	<meta charset="UTF-8">
		<title>Invoice Customer</title>
	</head>
	<body>
		<table cellspacing=0 width="100%">
			<tr>
				<td style="font-family:Calibri;text-align:center;font-size:16px;color:#000000;font-weight:bold;" colspan="17">Invoice Customer</td>
			</tr>
			<tr>
				<td colspan="10"></td>
			</tr>	  
			<tr>
				<td colspan="8">&nbsp;</td>
			</tr>
			<tr>
				<th style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border:1px solid;">NO </th>
				<th style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border:1px solid;">No Invoice</th>
				<th style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border:1px solid;">Tanggal</th>
				<th style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border:1px solid;">Metode </th>
				<th style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border:1px solid;">Customer </th>
				<th style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border:1px solid;">Tanggal AWB</th>
				<th style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border:1px solid;">Tanggal Bayar </th>
				<th style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border:1px solid;">Jumlah Bayar </th>
				<th style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border:1px solid;">Keterangan Bayar </th>
			</tr>
			<?php
			$no=1;
			//$tipe = mysqli_real_escape_string($con, $_GET['tipe']);
						
						
						
							$cari = "SELECT
                                    coalesce(a.nomor,a.status) as nomor,
                                    a.tanggal,
                                    b.nama_kustomer,
                                    case when a.method=1 then concat(date_format(a.dari,\"%d-%m-%Y\"),' s/d ',date_format(a.sampai,\"%d-%m-%Y\")) else '-' end as tanggalairwaybill,
                                    case when a.method=1 then LEFT(a.loc_code,16) else '-' end as loc_code,
                                    a.id_invoice as id_invoice,
                                    case when a.method=1 then 'range tanggal' else 'pilih pengiiman tertentu' end as metode,
                                    s.id as send, s.confirm_by as confirm, a.id_kustomer, a.tanggal_bayar, a.jumlah_bayar, a.ket_bayar 
                                FROM invoice a
                                left JOIN kustomer b ON b.id_kustomer=a.id_kustomer
                                left outer JOIN invoice_send s ON s.invoice_id=a.id_invoice and s.invoice_type = 'CUSTOMER'
                                where a.status = 'final' and date_format(a.tanggal,'%Y-%m') = '$periode'  
                                group by a.id_invoice
							";
						

			$result = mysqli_query($con, $cari);
			while($t=mysqli_fetch_assoc($result)){
						
				echo'
				<tr style="text-transform: uppercase;">
					<td style="font-family:Calibri;text-align:center;font-size:14px;font-color:#000000;border-color:#000;border:1px 
						solid;">'.$no.'</td>					
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['nomor'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['tanggal'].'</td>
					
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['metode'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['nama_kustomer'].'</td>					
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['tanggalairwaybill'].'</td>					
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['tanggal_bayar'].'</td>					
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['jumlah_bayar'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['ket_bayar'].'</td>					
						
				</tr>';

			$no++;
			}
			?>

		</table>

	</body>
</html>