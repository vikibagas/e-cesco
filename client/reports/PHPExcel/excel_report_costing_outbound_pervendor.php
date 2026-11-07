<?php
include ('../../config/connection.php');
include ('../../config/encrypt.php');
include ('../../config/library.php');


$key = "asdf$1011";
//$encrypt = "$_GET['id']";
//$id = base64_decrypt($encrypt,$key);

//$pieces = explode('-', $id);
$id_vendor = $_GET['id_vendor'];
//$outbound_date = implode('-', array_slice($pieces, 1, 3));
$dari = $_GET['dari'];
$sampai = $_GET['sampai'];

//$query_kode_manifest = mysqli_query($con, "select kode_manifest from manifest
//	where tanggal='$outbound_date' and manifest_outbound_by = '$outbound_by'");
//$r_kode = mysqli_fetch_assoc($query_kode_manifest);

//$tanggal = tgl_indo($outbound_date);

header("Content-type: application/vnd-ms-excel");
header('Content-Disposition: attachment; filename="costing_outbound-'.$r['namalengkap'].$dari.$sampai.'.xls"');



			$sql_vendor = mysqli_query($con, "select name from manifest_vendor where id='".$id_vendor."'");
			$rv = mysqli_fetch_assoc($sql_vendor);
?>
<!DOCTYPE html>
<html>
	<head>
	<meta charset="UTF-8">
		<title>Outbound Manifest</title>
	</head>
	<body>
		<table cellspacing=0 width="100%">
			<tr>
				<td style="font-family:Calibri;text-align:center;font-size:16px;color:#000000;font-weight:bold;" colspan="8">REPORT COSTING OUTBOUND</td>
			</tr>
			<tr>
				<td colspan="10"></td>
			</tr>
			<tr>
			  <td style=min-width:50px><span style="font-family:Calibri;text-align:right;font-size:14px;color:#000000;">PERIODE :</span></td>
			  <td  style=min-width:50px><span style="font-family:Calibri;font-size:14px;color:#000000;"><?php echo''.$dari.' - '. $sampai;?></span></td>
		    </tr>
			<tr>
				<td style=min-width:50px><span style="font-family:Calibri;text-align:right;font-size:14px;color:#000000;">VENDOR :</span></td>
				<td colspan="8" style=min-width:50px><span style="font-family:Calibri;font-size:14px;color:#000000;"> <?php echo''.strtoupper($rv['name']).'';?></span></td>
		  </tr>
			<tr>
				<td colspan="8">&nbsp;</td>
			</tr>
			<tr>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border:1px solid;">NO</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Outbound date</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Airwaybill</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Customer</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">NO SMU</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Destination</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Collie</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Weight</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Dimensi</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">SMU Cost</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">NO INVOICE</td>
			</tr>
			<?php
			$no=1;



			$result = mysqli_query($con,"select b.tanggal as outbound_date,c.airwaybill_kode,x_namakustomer(id_kustomer) as kustomer,x_namakotabesar(lokasi_asal) as lokasi_asal, x_namakotabesar(lokasi_tujuan) as lokasi_tujuan,collie,berat_barang, costing_outbound_smu , c.ukuran_barang, b.no_smu,c.kota_tujuan,x_noinvoice(c.id_invoice) as noinv, d.cs_number

				from

				manifest_detail a

				left join manifest b on a.id_manifest=b.id_manifest

				left join pengiriman c on a.id_pengiriman = c.id_pengiriman

				left join pembayaran_or d on c.airwaybill_kode = d.airwaybill

			 where /*x_inter(c.id_kota_asal) = 'Y' and x_inter(c.id_kota_tujuan) <> 'Y' AND*/ b.manifest_via='".$rv['name']."' and b.manifest_to <> 39 and b.tanggal BETWEEN '".$dari."' AND '".$sampai."' group by c.id_pengiriman");

			 $total_sum=0;
			while($row=mysqli_fetch_assoc($result)){
				$sum_total = round($row['costing_outbound_smu']);
				$total_sum = $total_sum+$sum_total;

				if($row['lokasi_asal']=='Jakarta Selatan' || $row['lokasi_asal']=='Jakarta Timur' || $row['lokasi_asal']=='Jakarta Barat' || $row['lokasi_asal']=='Jakarta Utara' || $row['lokasi_asal']=='Jakarta Pusat'){
					$lokasi_asal = 'Jakarta';
				}else{
					$lokasi_asal = $row['lokasi_asal'];
				}
				if($row['lokasi_tujuan']=='Jakarta Selatan' || $row['lokasi_tujuan']=='Jakarta Timur' || $row['lokasi_tujuan']=='Jakarta Barat' || $row['lokasi_tujuan']=='Jakarta Utara' || $row['lokasi_tujuan']=='Jakarta Pusat'){
					$lokasi_tujuan = 'Jakarta';
				}else{
					$lokasi_tujuan = $row['lokasi_tujuan'];
				}

				echo'
				<tr>
					<td style="font-family:Calibri;text-align:center;font-size:14px;font-color:#000000;border-color:#000;border:1px solid;">'.$no.'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$row['outbound_date'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$row['airwaybill_kode'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$row['kustomer'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$row['no_smu'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$row['kota_tujuan'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$row['collie'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$row['berat_barang'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$row['ukuran_barang'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.round($row['costing_outbound_smu']).'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.($row['noinv'] != '' ? $row['noinv'] : $row['cs_number']).'</td>
				</tr>
				';
			$no++;
			}
			?>
			<tr>
				<td colspan="9" align="right">total</td>
				<td  style="border-color:#000;border:1px solid;" align="center"><?php echo 'Rp.'.number_format(round($total_sum)); ?></td>
			</tr>
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
