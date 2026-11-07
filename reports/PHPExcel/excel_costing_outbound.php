<?php
include ('../../config/connection.php');
include ('../../config/encrypt.php');
include ('../../config/library.php');

$key = "asdf$1011";
$encrypt = "$_GET[id]";
$id = base64_decrypt($encrypt,$key);


$query = mysqli_query($con, "select a.id_manifest, a.tanggal, DATE_FORMAT(a.tanggal,'%Y-%m-%d') as tgloutbound, a.kode_manifest, x_user(a.created_by) as manifest_by, case when x_intercity(b.id_kota)='Y' then 'JAKARTA' else b.nama_cabang end as manifest_from, case when x_intercity(b.id_kota)='Y' then 'CESCO LOGISTICS' else b.alamat1 end as manifestfrom, case when x_intercity(c.id_kota)='Y' then 'JAKARTA' else c.nama_cabang end as manifest_to, case when x_intercity(c.id_kota)='Y' then 'CESCO LOGISTICS' else c.alamat1 end as manifestto,
case when a.manifest_via = 1 then 'AMTL' else a.manifest_via end as manifest_via,
a.no_smu from manifest a inner join cabang b on b.id_cabang=a.manifest_from inner join cabang c on c.id_cabang=a.manifest_to  where a.id_manifest='$id'");
$r = mysqli_fetch_assoc($query);

$result = mysqli_query($con,"select sum(coalesce(a.costing_outbound_fee,0)) + sum(coalesce(a.costing_outbound_parking,0)) + sum(coalesce(a.costing_outbound_toll,0)) + sum(coalesce(a.costing_outbound_menpower,0)) + sum(coalesce(a.costing_outbound_smu,0)) + sum(coalesce(a.costing_outbound_handling,0)) as total_cost, a.costing_outbound_save, sum(coalesce(a.costing_outbound_fee,0)) as costing_outbound_fee, sum(coalesce(a.costing_outbound_parking,0)) as costing_outbound_parking, sum(coalesce(a.costing_outbound_toll,0)) as costing_outbound_toll, sum(coalesce(a.costing_outbound_menpower,0)) as costing_outbound_menpower, sum(coalesce(a.costing_outbound_smu,0)) as costing_outbound_smu, sum(coalesce(a.costing_outbound_handling,0)) as costing_outbound_handling from pengiriman a inner join manifest_detail b on a.id_pengiriman = b.id_pengiriman where b.id_manifest='$r[id_manifest]' and x_intercity(a.lokasi_asal) ='Y' and x_intercity(a.lokasi_tujuan)!='Y' and a.id_cabang in (11,16,39) ");
$r_cost = mysqli_fetch_assoc($result);

$tanggal = tgl_indo($r['tanggal']);

header("Content-type: application/vnd-ms-excel");
header('Content-Disposition: attachment; filename="costing_outbound_'.$r['kode_manifest'].'.xls"');

?>
<!DOCTYPE html>
<html>
	<head>
	<meta charset="UTF-8">
		<title>Manifest Outbound</title>
	</head>
	<body>
		<table cellspacing=0 width="100%">
			<tr>
				<td style="font-family:Calibri;text-align:center;font-size:16px;color:#000000;font-weight:bold;" colspan="8">MANIFEST OUTBOUND</td>
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
				<td width="5%" style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border:1px solid;">NO</td>
				<td width="11%" style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">AIRWAYBILL</td>
				<td width="28%" style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">CUSTOMER</td>
				<td width="28%" style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Asal</td>
				<td width="28%" style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Tujuan</td>
				<td width="9%" style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">KOLI</td>
				<td width="9%" style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">BERAT</td>
				<td width="9%" style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">VOLUME</td>
				<td width="5%" style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">D/P</td>
				<td width="16%" style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Outbound cost</td>
				<td width="17%" style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Toll</td>
				<td width="17%" style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">SMU/Land</td>
				<td width="17%" style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">handling</td>
				<td width="17%" style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Parkir</td>
				<td width="17%" style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Menpower</td>
				<td width="17%" style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Total</td>
			</tr>
			<?php
			$no=1;

			//$sql = "select b.id_pengiriman as id_pengiriman,  b.costing_outbound_fee as costing_outbound_fee, b.costing_outbound_parking as costing_outbound_parking, b.costing_outbound_toll as costing_outbound_toll, b.costing_outbound_menpower as costing_outbound_menpower, b.costing_outbound_smu as costing_outbound_smu, b.costing_outbound_handling as costing_outbound_handling, airwaybill_kode, x_namakustomer(id_kustomer) as kustomer, x_namakotabesar(lokasi_asal) as lokasi_asal, x_namakotabesar(lokasi_tujuan) as lokasi_tujuan, collie, coalesce(berat_aktual,berat_barang) as berat_barang, case when komoditi='1' then 'Paket' else 'Dokumen' end as komoditi, x_service(service) as service, ifnull(b.costing_outbound_fee, 0) + ifnull(b.costing_outbound_parking, 0) + ifnull(b.costing_outbound_toll, 0) + ifnull(b.costing_outbound_menpower, 0) + ifnull(b.costing_outbound_smu, 0) + ifnull(costing_outbound_handling, 0) as total_cost from manifest_detail a left join pengiriman b on b.id_pengiriman=a.id_pengiriman where a.id_manifest='$r[id_manifest]'  order by created_date asc";

			$sql = "select
						a.id_manifest_detail,
						b.id_pengiriman as id_pengiriman,
						b.costing_outbound_fee as costing_outbound_fee,
						b.costing_outbound_parking as costing_outbound_parking,
						b.costing_outbound_toll as costing_outbound_toll,
						b.costing_outbound_menpower as costing_outbound_menpower,
						b.costing_outbound_smu as costing_outbound_smu,
						b.costing_outbound_handling as costing_outbound_handling,
						airwaybill_kode,
						x_namakustomer(b.id_kustomer) as kustomer,
						b.kota_asal as lokasi_asal,
						b.kota_tujuan as lokasi_tujuan,
						/*x_namakotabesar(lokasi_asal) as lokasi_asal,
						x_namakotabesar(lokasi_tujuan) as lokasi_tujuan,*/
					cs.kode_kustomer,
					CASE
							WHEN airwaybill_kode LIKE '001-%' THEN 0
							WHEN airwaybill_kode LIKE '002-%' THEN 1
						end as intercity,
						SUBSTRING_INDEX(ukuran_barang, 'x', 1) as length,
						SUBSTRING_INDEX(SUBSTRING_INDEX(ukuran_barang, 'x', 2),
								'x',
								- 1) as width,
						SUBSTRING_INDEX(ukuran_barang, 'x', - 1) as height,
						case when upper(cs.kode_kustomer) in ('CIS','DEL') then
						  if((select intercity) = 1,
							((select length) * (select width) * (select height) / 4000),
							if(b.service = 5,
								((select length) * (select width) * (select height) / 4000),
								((select length) * (select width) * (select height) / 6000)))
						else
							if((select intercity) = 1,
								ceiling((select length) * (select width) * (select height) / 4000),
								if(b.service = 5,
									ceiling((select length) * (select width) * (select height) / 4000),
									ceiling((select length) * (select width) * (select height) / 6000)))
						end as volume,
						collie,
						if(berat_aktual > 0,
							berat_aktual,
							berat_barang) as berat_barang,
						case
							when komoditi = '1' then 'Paket'
							else 'Dokumen'
						end as komoditi,
						x_service(service) as service,
						ifnull(b.costing_outbound_fee, 0) + ifnull(b.costing_outbound_parking, 0) + ifnull(b.costing_outbound_toll, 0) + ifnull(b.costing_outbound_menpower, 0) + ifnull(b.costing_outbound_smu, 0) + ifnull(b.costing_outbound_handling, 0) as total_cost
					from
						manifest_detail a
							left join
						pengiriman b ON b.id_pengiriman = a.id_pengiriman
					left join kustomer cs on cs.id_kustomer = b.id_kustomer
					where
						a.id_manifest = '".$r['id_manifest']."'
					group by a.id_pengiriman
					order by b.created_date asc";

			$result = mysqli_query($con,$sql);
			$totalCost = 0;
			while($t=mysqli_fetch_assoc($result)){
				$totalCost = $totalCost + $t['total_cost'];
				echo'
				<tr>
					<td style="font-family:Calibri;text-align:center;font-size:14px;font-color:#000000;border-color:#000;border:1px solid;">'.$no.'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['airwaybill_kode'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['kustomer'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['lokasi_asal'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['lokasi_tujuan'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['collie'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['berat_barang'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['length'].'x'.$t['width'].'x'.$t['height'].'= '.round($t['volume']).'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['komoditi'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.round($t['costing_outbound_fee']).'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.round($t['costing_outbound_toll']).'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.round($t['costing_outbound_smu']).'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.round($t['costing_outbound_handling']).'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.round($t['costing_outbound_parking']).'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.round($t['costing_outbound_menpower']).'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.round($t['total_cost']).'</td>

				</tr>';
			$no++;
			}
			?>
			<tr>
				<td colspan="15" align="right">total</td>
				<td style="border-color:#000;border:1px solid;" align="center"><?php echo 'Rp.'.round($totalCost); ?></td>
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

	</body>
</html>
