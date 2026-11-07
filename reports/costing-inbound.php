<?php
include ('../config/connection.php');
include ('../config/encrypt.php');
include ('../config/library.php');

$key = "asdf$1011";
$encrypt = "$_GET[id]";
$id = base64_decrypt($encrypt,$key);

$query = mysqli_query($con, "select a.id_manifest, a.created_by, a.tanggal, DATE_FORMAT(a.tanggal,'%Y-%m-%d') as tglinbound,  a.kode_manifest, x_user(a.created_by) as manifest_by, case when x_intercity(b.id_kota)='Y' then 'JAKARTA' else b.nama_cabang end as manifest_from, case when x_intercity(b.id_kota)='Y' then 'CESCO LOGISTICS' else b.alamat1 end as manifestfrom, case when x_intercity(c.id_kota)='Y' then 'JAKARTA' else c.nama_cabang end as manifest_to, case when x_intercity(c.id_kota)='Y' then 'CESCO LOGISTICS' else c.alamat1 end as manifestto, a.no_smu,
case when a.manifest_via = 1 then 'AMTL' else a.manifest_via end as manifest_via 
from manifest a inner join cabang b on b.id_cabang=a.manifest_from inner join cabang c on c.id_cabang=a.manifest_to  where a.id_manifest='$id'");
//echo "select a.id_manifest, a.created_by, a.tanggal, DATE_FORMAT(a.tanggal,'%Y-%m-%d') as tglinbound,  a.kode_manifest, x_user(a.created_by) as manifest_by, case when x_intercity(b.id_kota)='Y' then 'JAKARTA' else b.nama_cabang end as manifest_from, case when x_intercity(b.id_kota)='Y' then 'CESCO LOGISTICS' else b.alamat1 end as manifestfrom, case when x_intercity(c.id_kota)='Y' then 'JAKARTA' else c.nama_cabang end as manifest_to, case when x_intercity(c.id_kota)='Y' then 'CESCO LOGISTICS' else c.alamat1 end as manifestto, a.no_smu from manifest a inner join cabang b on b.id_cabang=a.manifest_from inner join cabang c on c.id_cabang=a.manifest_to  where a.id_manifest='$id'";
$r = mysqli_fetch_assoc($query);

$result = mysqli_query($con,"select sum(coalesce(costing_inbound_fee,0)) + sum(coalesce(costing_inbound_parking,0)) + sum(coalesce(costing_inbound_toll,0)) + sum(coalesce(costing_inbound_menpower,0)) + sum(coalesce(costing_inbound_smu,0)) + sum(coalesce(costing_inbound_handling,0)) as total_cost, costing_inbound_save,sum(coalesce(costing_inbound_fee,0)) as costing_inbound_fee, sum(coalesce(costing_inbound_toll,0)) as costing_inbound_toll, sum(coalesce(costing_inbound_smu,0)) as costing_inbound_smu, sum(coalesce(costing_inbound_handling,0)) as costing_inbound_handling, sum(coalesce(costing_inbound_parking,0)) as costing_inbound_parking, sum(coalesce(costing_inbound_menpower,0)) as costing_inbound_menpower from pengiriman a left JOIN manifest_detail b on a.id_pengiriman = b.id_pengiriman where b.id_manifest='$r[id_manifest]'");

//echo "select sum(coalesce(costing_inbound_fee,0)) + sum(coalesce(costing_inbound_parking,0)) + sum(coalesce(costing_inbound_toll,0)) + sum(coalesce(costing_inbound_menpower,0)) + sum(coalesce(costing_inbound_smu,0)) + sum(coalesce(costing_inbound_handling,0)) as total_cost, c.namalengkap as nama_costing, costing_inbound_save,sum(coalesce(costing_inbound_fee,0)) as costing_inbound_fee, sum(coalesce(costing_inbound_toll,0)) as costing_inbound_toll, sum(coalesce(costing_inbound_smu,0)) as costing_inbound_smu, sum(coalesce(costing_inbound_handling,0)) as costing_inbound_handling, sum(coalesce(costing_inbound_parking,0)) as costing_inbound_parking, sum(coalesce(costing_inbound_menpower,0)) as costing_inbound_menpower from pengiriman left join user c on (c.id_user=costing_inbound_by) where id_manifest='$r[id_manifest]' and x_intercity(lokasi_asal)!='Y' and x_intercity(lokasi_tujuan)='Y'";
$r_cost = mysqli_fetch_assoc($result);

$tanggal = tgl_indo($r['tanggal']);

?>
<!DOCTYPE html>
<html>
	<head>
	<meta charset="UTF-8">
		<title>Manifest inbound</title>
	</head>
	<body>
		<table cellspacing=0 width="100%">
			<tr>
				<td style="font-family:Calibri;text-align:center;font-size:16px;color:#000000;font-weight:bold;" colspan="8">MANIFEST inbound</td>
			</tr>
			<tr>
				<td style="font-family:Calibri;text-align:center;font-size:16px;color:#000000;font-weight:bold;" colspan="8">MANIFEST CODE : <?php echo $r['kode_manifest']; ?></td>
		  	</tr>
			<tr>
				<td colspan="11"></td>
			</tr>
			<tr>
			  <td colspan="2" style="font-family:Calibri;font-size:14px;font-weight:bold;color:#000000;">Date</td>
			  <td colspan="6" style="font-family:Calibri;font-size:14px;font-weight:bold;color:#000000;">: <?php echo''.$tanggal.'';?></td>
		    </tr>
			<tr>
				<td colspan="2" style="font-family:Calibri;font-size:14px;font-weight:bold;color:#000000;">Manifest From</td>
				<td colspan="6" style="font-family:Calibri;font-size:14px;font-weight:bold;color:#000000;">: <?php echo''.strtoupper($r['manifest_from']).'';?></td>
		    </tr>
			<tr>
				<td colspan="2" style="font-family:Calibri;font-size:14px;font-weight:bold;color:#000000;">Manifest to</td>
				<td colspan="6" style="font-family:Calibri;font-size:14px;font-weight:bold;color:#000000;">: <?php echo''.strtoupper($r['manifest_to']).'';?></td>
		    </tr>
		    <tr>
			  <td colspan="2" style="font-family:Calibri;font-size:14px;font-weight:bold;color:#000000;">VIA</td>
              <td colspan="6" style="font-family:Calibri;font-size:14px;font-weight:bold;color:#000000;">: <?php echo strtoupper($r['manifest_via']) ?></td>
		    </tr>
			<tr>
			  <td colspan="2" style="font-family:Calibri;font-size:14px;font-weight:bold;color:#000000;">NO. SMU/RESI</td>
              <td colspan="6" style="font-family:Calibri;font-size:14px;font-weight:bold;color:#000000;">: <?php echo''.$r['no_smu'].'';?></td>
		  </tr>
			<tr>
				<td colspan="8">&nbsp;</td>
			</tr>
			<tr>
				<td width="3%" style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border:1px solid;">NO</td>
				<td width="11%" style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">AIRWAYBILL</td>
				<td width="17%" style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">CUSTOMER</td>
				<td width="11%" style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">ASAL</td>
				<td width="11%" style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">TUJUAN</td>
				<td width="3%" style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">KOLI</td>
				<td width="4%" style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">BERAT</td>
				<td width="3%" style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">D/P</td>
				<td width="5%" style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">FEE</td>
				<td width="5%" style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">TOLL</td>
				<td width="5%" style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">SMU</td>
				<td width="5%" style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">HANDLING</td>
				<td width="5%" style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">PARKING</td>
				<td width="5%" style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">MENPOWER</td>
				<td width="5%" style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">TOTAL</td>
			</tr>
			<?php
			$no=1;
			$result = mysqli_query($con,"select a.id_pengiriman as id_pengiriman, costing_inbound_fee as costing_inbound_fee, costing_inbound_parking as costing_inbound_parking, costing_inbound_toll as costing_inbound_toll, costing_inbound_menpower as costing_inbound_menpower,costing_inbound_smu as costing_inbound_smu,costing_inbound_handling as costing_inbound_handling, airwaybill_kode, x_namakustomer(id_kustomer) as kustomer, kota_asal, kota_tujuan, collie, coalesce(berat_aktual,berat_barang) as berat_barang, case when komoditi='1' then 'Paket' else 'Dokumen' end as komoditi, x_service(service) as service, manifest_received, x_user(manifest_by) as received_by, ifnull(costing_inbound_fee, 0) + ifnull(costing_inbound_parking, 0) + ifnull(costing_inbound_toll, 0) + ifnull(costing_inbound_menpower, 0) + ifnull(costing_inbound_smu, 0) + ifnull(costing_inbound_handling, 0) as total_cost from pengiriman a left join manifest_detail b on a.id_pengiriman=b.id_pengiriman where b.id_manifest='$r[id_manifest]' order by created_date asc");

			//echo "select a.id_pengiriman as id_pengiriman, costing_inbound_fee as costing_inbound_fee, costing_inbound_parking as costing_inbound_parking, costing_inbound_toll as costing_inbound_toll, costing_inbound_menpower as costing_inbound_menpower,costing_inbound_smu as costing_inbound_smu,costing_inbound_handling as costing_inbound_handling, airwaybill_kode, x_namakustomer(id_kustomer) as kustomer, x_namakotabesar(lokasi_asal) as lokasi_asal, x_namakotabesar(lokasi_tujuan) as lokasi_tujuan, collie, coalesce(berat_aktual,berat_barang) as berat_barang, case when komoditi='1' then 'Paket' else 'Dokumen' end as komoditi, x_service(service) as service, manifest_received, x_user(manifest_by) as received_by, ifnull(costing_inbound_fee, 0) + ifnull(costing_inbound_parking, 0) + ifnull(costing_inbound_toll, 0) + ifnull(costing_inbound_menpower, 0) + ifnull(costing_inbound_smu, 0) + ifnull(costing_inbound_handling, 0) as total_cost from pengiriman a left join manifest_detail b on a.id_pengiriman=b.id_pengiriman where b.id_manifest='$r[id_manifest]' order by created_date asc";


			while($t=mysqli_fetch_assoc($result)){
				echo'
				<tr>
					<td style="font-family:Calibri;text-align:center;font-size:14px;font-color:#000000;border-color:#000;border:1px solid;">'.$no.'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['airwaybill_kode'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.substr($t['kustomer'],0,17).'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['kota_asal'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['kota_tujuan'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['collie'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['berat_barang'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.substr($t['komoditi'],0,1).'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.number_format(round($t['costing_inbound_fee'])).'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.number_format(round($t['costing_inbound_toll'])).'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.number_format(round($t['costing_inbound_smu'])).'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.number_format(round($t['costing_inbound_handling'])).'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.number_format(round($t['costing_inbound_parking'])).'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.number_format(round($t['costing_inbound_menpower'])).'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.number_format(round($t['total_cost'])).'</td>

				</tr>';
			$no++;
			}
			?>
			<tr>
				<td colspan="8" align="right">TOTAL</td>
				<td style="border-color:#000;border:1px solid;" align="center"><?php echo number_format(round($r_cost['costing_inbound_fee'])); ?></td>
				<td style="border-color:#000;border:1px solid;" align="center"><?php echo number_format(round($r_cost['costing_inbound_toll'])); ?></td>
				<td style="border-color:#000;border:1px solid;" align="center"><?php echo number_format(round($r_cost['costing_inbound_smu'])); ?></td>
				<td style="border-color:#000;border:1px solid;" align="center"><?php echo number_format(round($r_cost['costing_inbound_handling'])); ?></td>
				<td style="border-color:#000;border:1px solid;" align="center"><?php echo number_format(round($r_cost['costing_inbound_parking'])); ?></td>
				<td style="border-color:#000;border:1px solid;" align="center"><?php echo number_format(round($r_cost['costing_inbound_menpower'])); ?></td>
				<td style="border-color:#000;border:1px solid;" align="center"><?php echo number_format(round($r_cost['total_cost'])); ?></td>
			</tr>
			<tr>
			  <td><div align="center"></div></td>
			  <td><div align="center"></div></td>
			  <td><div align="center"></div></td>
			  <td><div align="center"></div></td>
			  <td><div align="center"></div></td>
			  <td><div align="center"></div></td>
			  <td><div align="center"></div></td>
			  <td><div align="center"></div></td>
		  </tr>
		</table>
	<script type="text/javascript">
		window.print();

		</script>
	</body>
</html>
