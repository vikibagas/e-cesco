<?php
include ('../../config/connection.php');
include ('../../config/encrypt.php');
include ('../../config/library.php');
//$dari = $_GET['dari'];
//$sampai = $_GET['sampai'];
$id_regency = $_GET['id_regency'];
$regencies = mysqli_query($con, "SELECT * from regencies where id=".$id_regency." ");
$s=mysqli_fetch_assoc($regencies);

header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=sla from ".$s['name'].".xls");

?>
<!DOCTYPE html>
<html>
	<head>
	<meta charset="UTF-8">
		<title>Airwaybill Customer</title>
	</head>
	<body>
		<table cellspacing=0 width="100%">
			
			
				<th style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border:1px solid;">no</th>
				<th style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border:1px solid;"> id asal</th>
				<th style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border:1px solid;"> asal</th>
				<th style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border:1px solid;"> id tujuan</th>
				<th style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border:1px solid;">tujuan</th>
				<th style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border:1px solid;">tarif reg</th>
				<th style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border:1px solid;">tarif urgent</th>
				<th style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border:1px solid;">sla</th>
				
			
			<?php
			$no=1;
			//$tipe = mysqli_real_escape_string($con, $_GET['tipe']);
						
			$regencies = mysqli_query($con, "SELECT * from regencies where id=".$id_regency." ");	
			while($r=mysqli_fetch_assoc($regencies)){			
							$cari = "SELECT *,x_namakotanew(regency_id) as nama_kota from districts where regency_id !=  ".$r[id]." 
							";
						

				$result = mysqli_query($con, $cari);
				while($t=mysqli_fetch_assoc($result)){		
					
					echo'
					<tr style="text-transform: uppercase;">
						<td style="font-family:Calibri;text-align:center;font-size:14px;font-color:#000000;border-color:#000;border:1px 
							solid;">'.$no.'</td>					
						<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$r['id'].'</td>
						<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$r['name'].'</td>
						<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['id'].'</td>
						<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['name'].',' .$t['nama_kota'].'</td>
						<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">0</td>
						<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">0</td>
						<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">0</td>
							
					</tr>	
					';
					$no++;
				}
			}
			?>
			
			
		</table>

	</body>
</html>