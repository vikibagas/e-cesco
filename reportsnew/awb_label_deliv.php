<?php
include ('../config/connection.php');
include ('../config/encrypt.php');
include ('../config/library.php');
include ('../reports/phpqrcode/qrlib.php'); 
	
session_start();

$key = "asdf$1011";
//$encrypt = "$_POST[id]";
//$id = base64_decrypt($encrypt,$key);

$encrypt = $_GET['id'];
$id = base64_decrypt($encrypt,$key);
$query = mysqli_query($con, "select 
x_namakotabesar(a.lokasi_asal) as lokasi_asal,

a.id_kota_asal,
a.provinsi_asal,
CASE 
	WHEN airwaybill_kode LIKE '001-%' THEN 0
	WHEN airwaybill_kode LIKE '002-%' THEN 1
	WHEN airwaybill_kode LIKE '004-%' THEN 0
	WHEN airwaybill_kode LIKE '005-%' THEN 1
end as intercity,
a.kota_asal,
jumlah_detail_barang,
x_namakotanew(a.id_kota_asal) as xnamakotaasal,
a.kecamatan_asal,
a.kelurahan_asal,
a.id_kota_tujuan,
a.provinsi_tujuan,
a.kota_tujuan,
x_namakotanew(a.id_kota_tujuan) as xnamakotatujuan,
a.kecamatan_tujuan,
a.kelurahan_tujuan,
case when a.service='6' then x_namanegara(a.lokasi_tujuan) else x_namakotabesar(a.lokasi_tujuan) end as lokasi_tujuan,
a.nama_pengirim,
a.perusahaan_pengirim,
a.nama_penerima,
a.perusahaan_penerima,
a.airwaybill_kode,
case when a.service = 1 then 'TOP URGENT' when a.service=2 then 'URGENT' when a.service=3 then 'REGULER' when a.service=4 then 'TRCK CHARTER' when a.service=5 then 'TRCK CONSOLE' else x_service(a.service) end as pelayanan,
x_service(a.service) as service,
a.service as id_pelayanan,
a.hp_pengirim,
a.hp_penerima,
a.packing_kayu,
a.alamat_pengirim,
a.alamat_pengirim2,
x_namakotadetail(a.lokasi_asal) as alamat_pengirim1,

a.alamat_penerima,
a.alamat_penerima2,
x_namakotadetail(a.lokasi_tujuan) as alamat_penerima111,
a.asuransi,
a.surat_jalan,
a.biaya_beban,
SUBSTRING_INDEX(ukuran_barang,'x',1) as length, 
									SUBSTRING_INDEX(SUBSTRING_INDEX(ukuran_barang,'x',2),'x',-1) as width, 
									SUBSTRING_INDEX(ukuran_barang,'x',-1) as height, 
a.loc_code,
packing_diterima,
kiriman_diperiksa,
a.berat_barang,
coalesce(a.berat_aktual,a.berat_barang) as berat_aktual,
a.tanggal,
DATE_FORMAT(a.created_date, '%d-%m-%Y %H:%i') as tanggal_booking,
a.id_kustomer,
a.pid,
a.ukuran_barang,
x_user(a.pickup_by) as pickup_by,
DATE_FORMAT(a.pickup_date,'%d-%m-%Y') as pickup_date,
DATE_FORMAT(a.pickup_date,'%H:%i') as pickup_time,
a.catatan,
x_user(a.courier_by) as courier_by,
CASE WHEN a.status=6 THEN DATE_FORMAT(a.delivery_date, '%d-%m-%Y') ELSE '' END AS delivery_date,
CASE WHEN a.status=6 THEN DATE_FORMAT(a.delivery_time, '%H:%i') ELSE '' END AS delivery_time,
a.delivery_name,
a.delivery_status,
a.komoditi,
a.jenis_barang,
a.collie,
a.inbound,
f.name as kota_penerima,
f.kode_kota,
if(x_intercity(a.lokasi_asal) = 'Y','CGK',x.kode_cabang)  as kode_cabang_asal,
if(a.service!=6,if(x_intercity(a.lokasi_tujuan) = 'Y' && a.service,'CGK',y.kode_cabang), '')  as kode_cabang_tujuan,
ff.name as kota_pengirim,
if(a.service=6,x_namanegara(a.lokasi_tujuan),ff.kode_kota) as kode_kota_tujuan,
x_kodekotabaru(a.id_kota_tujuan) as kodebarukotatujuan,
x_kodekotabaru(id_kota_asal) as kodekotaasal,
x_kodekotabaru(id_kota_tujuan) as kodekottujuan,
d.namalengkap,

e.name as kec_penerima,
ee.name as kec_pengirim,
case when d.level='kurir' then 'MOTOR' when d.level='driver' then 'MOBIL' end as pickup,
a.delivery_sign,
case when a.user_client IS NOT NULL THEN (select fullname FROM user_client WHERE id_user=a.user_client) ELSE (SELECT namalengkap FROM user WHERE id_user=a.created_by) END AS booking,
s.sla_max
from pengiriman a 

left join user d on d.id_user=a.pickup_by
left join districts e on e.id=a.id_kecamatan_asal
left join districts ee on ee.id=a.id_kecamatan_tujuan
left join regencies f on f.id=a.id_kota_asal
left join regencies ff on ff.id= x_hubnew(a.id_kota_tujuan)
left join cabang x on x.id_kota=x_hub(a.lokasi_asal)
left join cabang y on y.id_kota= x_hub(a.lokasi_tujuan)
left join sla s  on a.id_kota_tujuan = s.kota_tujuan and a.service = s.service
where a.id_pengiriman='$id'");

$manifest =  mysqli_query($con, "select kode_manifest from manifest a left join manifest_detail b on a.id_manifest=b.id_manifest where b.id_pengiriman =$id and a.manifest_from=39 limit 1 ");
//echo "select kode_manifest from manifest a left join manifest_detail b on a.id_manifest=b.id_manifest where b.id_pengiriman =$id and a.manifest_from=39 limit 1 ";
$m = mysqli_fetch_assoc($manifest);

$cek = mysqli_num_rows($query);
//
$r = mysqli_fetch_assoc($query);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />
	
	<title>Print <?php echo $r['airwaybill_kode']; ?></title>
	
	<link rel='stylesheet' type='text/css' href='../dist/css/print_label.css' />
	
	<style>
		img.center {
			display: block;
			margin: 0 auto;
		}

	</style>


	

</head>

<body>

	<div id="page-wrap">

		
		<?php 
		$q = mysqli_query($con, "select koli_ke,catatan,berat,p,l,t from detail_collie where id_pengiriman=$id order by koli_ke asc");
		while ($row = mysqli_fetch_assoc($q)) {
			$dim = $row['p'].'x'.$row['l'].'x'.$row['t'];
		?>
		
		<table id="items" style="text-align: left; margin-bottom: 10px; " frame="box">
				
				<tr style="margin-bottom : 2px 0 0 0; border: 2px solid black; ">
					<td style="padding:5px;"  colspan="2" >
						<img style="margin-left: auto; margin-right: auto; display: block; width: 50%;" src="../../assets/img/cesco-new-logo21.png" >
						
					</td>
				</tr>
				<tr style="padding: 5px; margin-bottom : 2px 0 0 0; border: 2px solid black;">
					<td style="padding:5px;"  style="width: 50%;">	
					  		<h1>AWB NO</h1>
					</td>
					<td style="padding:5px;" style="width: 50%;">	<p style="font-size: 4em; font-weight:  bold;"><?php echo $r['airwaybill_kode'];  ?>		</p>		
					</td>
					

				</tr>
				<tr style="padding: 5px; margin-bottom : 2px 0 0 0; border: 2px solid black;">
					<td style="padding:5px;"  >	
					  		<h1>LAYANAN</h1>
					</td>
					<td style="padding:5px;" >	<h1><?php echo $r['pelayanan'];  ?>		</h1>		
					</td>
					

				</tr>
				<tr style="padding: 5px; margin-bottom : 2px 0 0 0; border: 2px solid black;">
					<td style="padding:5px;"  >	
					  		<h1>ISI BARANG</h1>
					</td>
					<td style="padding:5px;" >	<h1><?php echo ($r['komoditi']  == 1 ? substr($r['jenis_barang'],0,12) : 'DOKUMEN'); ?>		</h1>		
					</td>
					

				</tr>
				<!--<tr style="padding: 5px; margin-bottom : 2px 0 0 0; border: 2px solid black;">
					<td style="padding:5px;"  >	
					  		<h1>KOTA ASAL</h1>
					</td>
					<td style="padding:5px;" >	<p style="font-size: 3em; font-weight:  bold;"><?php 
		  		
		  	//	echo ($r['kodekotaasal']  != '' ? $r['kodekotaasal'] : $r['kode_cabang_asal']); ?>		</p>		
					</td>-->
					

				</tr>
				<tr style="padding: 5px; margin-bottom : 2px 0 0 0; border: 2px solid black;">
					<td style="padding:5px;"  >	
					  		<h1>KOTA</h1>
					</td>
					<td style="padding:5px;" >	<p style="font-size: 3.3em; font-weight:  bold;"><?php echo ($r['kodekotaasal']  != '' ? $r['kodekotaasal'] : $r['kode_cabang_asal']) . '-> ' .  ($r['kode_kota_tujuan']  != '' ? $r['kode_kota_tujuan'].' - ' : ''); ?>  <?php echo ($r['kodebarukotatujuan']  != '' && $r['id_pelayanan'] !=6 ? $r['kodebarukotatujuan'] : $r['kode_cabang_tujuan']); ?>		</p>		
					</td>
					

				</tr>
				<tr style="padding: 5px; margin-bottom : 2px 0 0 0; border: 2px solid black;">
					<td style="padding:5px;"  >	
					  		<h1>KODE MANIFEST</h1>
					</td>
					<td style="padding:5px;" >	<h1><?php echo $m['kode_manifest'] != '' ? $m['kode_manifest'] : '-';  ?>		</h1>		
					</td>
					

				</tr>
				<tr >

					<td style="padding-left:5px; margin-bottom: -2px;"  ><h2>BERAT(KG) & DIM(CM)</h2> 	<h1> <?php echo $row['berat']; ?> KG |  <?php echo $dim; ?> </h1> </td>
					<td rowspan="3" style="padding:5px;" >	<?php echo '<img style="width:65%;" src="code/'.$r['airwaybill_kode'].'.png" >'; ?> 	</h1>	
					</td>
				</tr>
				
				
					
					
					
					<tr>
						<td style="padding-left:5px;  margin-bottom: -2px;"  ><h2>TANGGAL PICKUP</h2> <h1><?php echo $r['pickup_date']; ?> </h1>	</td>
					</tr>
					<?php
						$tgl_pickup = $r['pickup_date'];

						


						if($r['intercity'] == 1){
							if($r['service'] == 1){
								$target_sla = $tgl_pickup;
							}elseif($r['service'] == 2){
								$target_sla = date('d-m-Y',strtotime($tgl_pickup.'+1 day'));
							}else{
								$target_sla = date('d-m-Y',strtotime($tgl_pickup.'+2 day'));
							}
						}else{
							if($r['service'] == 1){
								$target_sla = date('d-m-Y',strtotime($tgl_pickup.'+1 day'));
							}elseif($r['service'] == 2){
								$target_sla = date('d-m-Y',strtotime($tgl_pickup.'+2 day'));
							}
							else{
								$target_sla = date('d-m-Y',strtotime($tgl_pickup.'+'.$r['sla_max'].' day'));
							}

							$d1 = strtotime($target_sla);
										$d2 = date('l',$d1);
										$dt3 = strtolower($d2);


							

						}// akhir if tidak intercity

						if($dt3 == "saturday")
										{
								            $sla = date('d-m-Y',strtotime($target_sla.'+2 day'));
								        }
								        elseif($dt3 == "sunday")
										{
								            $sla = date('d-m-Y',strtotime($target_sla.'+1 day'));
								        }

								        else{
								        	$sla = date('d-m-Y',strtotime($target_sla));
								        }

						if($tgl_pickup != ''){
							if($sla == '01-01-1970'){
								$sla_print = '';
							}else{
								$sla_print = $sla;
							}
							
						}else{
							$sla_print = 'PAKET BELUM DIPICKUP';
						}
					?>

					<tr>
						<td style="padding-left:5px;  margin-bottom: -2px;"  ><h2>DUE DATE DELIVERY</h2> <h1><?php echo $sla_print; ?> </h1>	</td>
					</tr>
				
					  		
					  	

					
					
					

				
				<tr style="padding: 5px; margin-bottom : 2px 0 0 0; border: 2px solid black;">
					<td style="padding:5px; text-align: center;"  colspan="2" >	
					  		<p style="font-size: 6em; font-weight:  bold;">KOLI <?php echo $row['koli_ke']; ?> / <?php echo $r['collie']; ?></p>
					</td>
					
					

				</tr>
				<tr style="padding: 5px; margin-bottom : 2px 0 0 0; border: 2px solid black;">
					<td style="padding:5px; text-align: center;" colspan="2" >	
					  		<p style="font-size: 4.5em; font-weight:  bold;"> JANGAN DIBANTING</p>
					</td>
					
					

				</tr>
				
				
		</table>
		<?php 
			}
		?>
		<br>
		
		
	
	</div>
	
	
</body>

</html>