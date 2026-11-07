<?php
include ('../../config/connection.php');
include ('../../config/encrypt.php');
include ('../../config/library.php');


header("Content-type: application/vnd-ms-excel");
header('Content-Disposition: attachment; filename="rate-agent-inbound.xls"');

?>
<!DOCTYPE html>
<html>
	<head>
	<meta charset="UTF-8">
		<title>Rate Agent Inbound</title>
	</head>
	<body>
		<table cellspacing=0 width="100%">
			
			<tr>
				
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Nama cabang</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Kota Asal</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Service</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Min Kg</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Price</td>
				
			</tr>
			<?php
			$no=1;

			

            $sql = "SELECT x_namakecamatan(id_kota) as nama_kecamatan,x_namacabang(id_cabang) as nama_cabang, rateagentinbound_id, x_service(id_pelayanan) as nama_pelayanan,  min_kg, price FROM rate_agent_inbound  where id_kota is not null and id_kota != 0

ORDER BY x_namacabang(id_cabang) asc,id_pelayanan asc,id_kota ASC ";

    

			$result = mysqli_query($con,$sql);
			$sum_total = 0;
			while($t=mysqli_fetch_assoc($result)){

                


                echo'
				<tr>
				
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['nama_cabang'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['nama_kecamatan'].'</td>
					
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['nama_pelayanan'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['min_kg'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['price'].'</td>
					
					
				</tr>				
				';
			$no++;
			}
			?>
			
			<tr>
			  <td style=min-width:50px></td>
			  <td style=min-width:50px></td>
			  <td style=min-width:50px></td>
			  <td style=min-width:50px></td>
			  <td style=min-width:50px></td>
			  <td style="font-family:Calibri;text-align:right;font-size:14px;color:#000000;">&nbsp;</td>
			  <td style=min-width:50px></td>
		  </tr>
		</table>

	</body>
</html>