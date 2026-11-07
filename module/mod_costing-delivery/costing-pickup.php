<?php
include ('../config/connection.php');
include ('../config/encrypt.php');
include ('../config/library.php');
include ('phpqrcode/qrlib.php'); 
	
/*$key = "asdf$1011";
$encrypt = "$_GET[id]";
$id = base64_decrypt($encrypt,$key);
			
$pieces = explode('-', $id);
$pickup_by = $pieces['0'];
$pickup_date = implode('-', array_slice($pieces, 1, 3));
			
$query = mysqli_query($con, "select b.namalengkap from pengiriman a inner join user b on b.id_user=a.pickup_by where a.pickup_by='$pickup_by' and DATE_FORMAT(a.pickup_date,'%Y-%m-%d')='$pickup_date'");
$r = mysqli_fetch_assoc($query);

$tanggal = tgl_indo($pickup_date);

$query_kode_manifest = mysqli_query($con, "select kode_manifest from manifest 
				where tanggal='$pickup_date' and manifest_pickup_by = '$pickup_by'");
			$r_kode = mysqli_fetch_assoc($query_kode_manifest);*/

$key = "asdf$1011";
$encrypt = "$_GET[id]";
$id = base64_decrypt($encrypt,$key);
			
$pieces = explode('-', $id);
$pickup_by = $pieces['0'];
$pickup_date = implode('-', array_slice($pieces, 1, 3));
		
$query = mysqli_query($con,"select DATE_FORMAT(a.pickup_date,'%Y-%m-%d') as tglpickup, a.costing_pickup_by , a.costing_pickup_save,b.namalengkap as namalengkap, c.namalengkap as nama_costing ,sum(coalesce(a.costing_pickup_fee,0)) as costing_pickup_fee, sum(coalesce(a.costing_pickup_parking,0)) as costing_pickup_parking, sum(coalesce(a.costing_pickup_toll,0)) as costing_pickup_toll , sum(coalesce(a.costing_pickup_fee,0)) + sum(coalesce(a.costing_pickup_parking,0)) + sum(coalesce(a.costing_pickup_toll,0)) + sum(coalesce(a.costing_men_power,0)) as total_cost from pengiriman a left join user b on (b.id_user=a.pickup_by) left join user c on (c.id_user=a.costing_pickup_by) where a.pickup_by='$pickup_by'  and DATE_FORMAT(a.pickup_date,'%Y-%m-%d')='$pickup_date' and x_intercity(a.lokasi_asal)='Y' and a.id_cabang in (11,16,39) ");
$r = mysqli_fetch_assoc($query);
			
$query_kode_manifest = mysqli_query($con, "select kode_manifest from manifest 
	where tanggal='$pickup_date' and manifest_pickup_by = '$pickup_by'");
$r_kode = mysqli_fetch_assoc($query_kode_manifest);

$tanggal = tgl_indo($pickup_date);

//QRcode::png("".$r['airwaybill_kode']."","image.png","L",2,2);
?>
<!DOCTYPE html>
<html>
	<head>
	<meta charset="UTF-8">
		<title>Pickup Manifest</title>
	</head>
	<body>
		<table cellspacing=0 width="100%">
			<tr>
				<td style="font-family:Calibri;text-align:center;font-size:16px;color:#000000;font-weight:bold;" colspan="8">PICKUP MANIFEST</td>
			</tr>
			<tr>
				<td colspan="10"></td>
			</tr>
			<tr>
				<td style="font-family:Calibri;text-align:center;font-size:16px;color:#000000;font-weight:bold;" colspan="8">MANIFEST CODE : <?php echo $r_kode['kode_manifest']; ?></td>
		  	</tr>
			<tr>
			  <td style=min-width:50px><span style="font-family:Calibri;text-align:right;font-size:14px;color:#000000;">TANGGAL :</span></td>
			  <td colspan="8" style=min-width:50px><span style="font-family:Calibri;font-size:14px;color:#000000;"><?php echo''.$tanggal.'';?></span></td>
		    </tr>
			<tr>
				<td style=min-width:50px><span style="font-family:Calibri;text-align:right;font-size:14px;color:#000000;">KURIR :</span></td>
				<td colspan="8" style=min-width:50px><span style="font-family:Calibri;font-size:14px;color:#000000;"> <?php echo''.strtoupper($r['namalengkap']).'';?></span></td>
		  </tr>		  
			<tr>
				<td colspan="8">&nbsp;</td>
			</tr>
			<tr>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border:1px solid;">NO</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">AWB</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">CUSTOMER</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Origin</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Destination</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">KOLI</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">BERAT</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">D/P</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Pickup Cost</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Parking</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Toll</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Men Power</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Total</td>
			</tr>
			<?php
			$no=1;
			$result = mysqli_query($con,"select id_pengiriman, airwaybill_kode, x_namakustomer(id_kustomer) as kustomer, x_namakotabesar(lokasi_asal) as lokasi_asal, x_namakotabesar(lokasi_tujuan) as lokasi_tujuan, collie, if(berat_aktual > 0, berat_aktual, berat_barang) as berat_barang, case when komoditi='1' then 'Paket' else 'Dokumen' end as komoditi, costing_pickup_fee, costing_pickup_parking, costing_pickup_toll ,costing_men_power , ifnull(costing_pickup_fee, 0) + ifnull(costing_pickup_parking, 0) + ifnull(costing_pickup_toll, 0) + ifnull(costing_men_power, 0) as cost_sum from pengiriman where pickup_by='$pickup_by' and DATE_FORMAT(pickup_date,'%Y-%m-%d')='$pickup_date' and x_intercity(lokasi_asal)='Y'  and id_cabang in (11,16,39) order by created_date asc");
			while($t=mysqli_fetch_assoc($result)){
				echo'
				<tr>
					<td style="font-family:Calibri;text-align:center;font-size:14px;font-color:#000000;border-color:#000;border:1px solid;">'.$no.'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['airwaybill_kode'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['kustomer'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['lokasi_asal'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['lokasi_tujuan'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['collie'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['berat_barang'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['komoditi'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.number_format(round($t['costing_pickup_fee']),0).'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.number_format(round($t['costing_pickup_parking']),0).'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.number_format(round($t['costing_pickup_toll']),0).'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.number_format(round($t['costing_men_power']),0).'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Rp. '.number_format(round($t['cost_sum']),0).'</td>
				</tr>				
				';
			$no++;
			}
			?>
			<tr>
				<td colspan="12" align="right">total</td>
				<td colspan="12" style="border-color:#000;border:1px solid;" align="center"><?php echo 'Rp.'.number_format(round($r['total_cost'])); ?></td>
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