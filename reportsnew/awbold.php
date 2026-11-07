<?php
include ('../config/connection.php');
include ('../config/encrypt.php');
include ('../config/library.php');
include ('../reports/phpqrcode/qrlib.php'); 
	
$key = "asdf$1011";
$encrypt = "$_GET[id]";
$id = base64_decrypt($encrypt,$key);

$query = mysqli_query($con, "select 
x_namakotabesar(a.lokasi_asal) as lokasi_asal,
b.kode_kota as kode_kota_asal,
a.id_kota_asal,
a.provinsi_asal,
CASE 
	WHEN airwaybill_kode LIKE '001-%' THEN 0
	WHEN airwaybill_kode LIKE '002-%' THEN 1
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
a.catatan1,
a.catatan2,
a.catatan3,
a.catatan4,
a.catatan5,
a.catatan6,
a.catatan7,
a.catatan8,
a.catatan9,
a.catatan10,
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
a.berat_barang,
coalesce(a.berat_aktual,a.berat_barang) as berat_aktual,
a.tanggal,
DATE_FORMAT(a.created_date, '%d-%m-%Y %H:%i') as tanggal_booking,
a.id_kustomer,
a.pid,
a.ukuran_barang,
x_user(a.pickup_by) as pickup_by,
DATE_FORMAT(a.pickup_date,'%d-%m-%Y %H:%i') as pickup_date,
a.catatan,
x_user(a.courier_by) as courier_by,
CASE WHEN a.status=6 THEN concat(DATE_FORMAT(a.delivery_date, '%d-%m-%Y'),' ',DATE_FORMAT(a.delivery_time, '%H:%i') ) ELSE '' END AS delivery_date,
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
case when a.user_client IS NOT NULL THEN (select fullname FROM user_client WHERE id_user=a.user_client) ELSE (SELECT namalengkap FROM user WHERE id_user=a.created_by) END AS booking
from pengiriman a 
LEFT join kota b on b.id_kota=a.lokasi_asal
LEFT join kota c on c.id_kota=a.lokasi_tujuan
left join user d on d.id_user=a.pickup_by
left join districts e on e.id=a.id_kecamatan_asal
left join districts ee on ee.id=a.id_kecamatan_tujuan
left join regencies f on f.id=a.id_kota_asal
left join regencies ff on ff.id= x_hubnew(a.id_kota_tujuan)
left join cabang x on x.id_kota=x_hub(a.lokasi_asal)
left join cabang y on y.id_kota= x_hub(a.lokasi_tujuan)
where a.id_pengiriman='$id'");



$r = mysqli_fetch_assoc($query);

if($r['komoditi']=='1'){
	$komoditi="Paket";
}else{
	$komoditi="Dokumen";
}

if($r['packing_kayu']=='1'){
	$packing="Pemilik";
}elseif($r['packing_kayu']=='2'){
	$packing="Cesco Log.";
}else{
	$packing="Non Packing";
}

	
//QRcode::png("".$r['airwaybill_kode']."","image.png","L",3,3);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />
	
	<title>Airwaybill Cesco Logistics</title>
	
	<link rel='stylesheet' type='text/css' href='../dist/css/airwaybill_2020.css' />
	
	
	<style type="text/css">
		@media print 
		{
		    @page {
		       /* DIN A4 standard, Europe */
		      margin-left:0.5cm;
		      margin-right: 0.5cm;
		      margin-top: 0.05cm ;
		      margin-bottom: 0.05cm;
		    }
		    html, body {
		        width: 215mm;
		        /* height: 297mm; */
		        height: 330;
		       
		        overflow:visible;
		    }
		    body {
		       
		    }
		}
	</style>

</head>

<body>

	<div id="-wrap" style="max-width: 980px ;">

		
		
		
		
		<table id="items">
		  <tr>	
		  		<td colspan="3" rowspan="2" width="325px">	<img src="../../assets/img/cesco-new-logo21.png" width="79%"></td>
		  				<td colspan="3" style="font-size: 28px;font-weight: bold; min-width:320px ; max-width: 313px;"> CESCO-LOGISTICS.COM </td>
		  						<td colspan="2" style="min-width:185px ; max-width: 185px;">	<?php QRcode::png("".$r['airwaybill_kode']."","code/".$r['airwaybill_kode'].".png","M",3,3); 

		  						echo '<img src="code/'.$r['airwaybill_kode'].'.png" >' ?> 
		  						
		  					</td>
		  </tr>


		  <tr>	
		  		<td colspan="3" style="font-size: 30px;font-weight: bold;">	<?php echo ($r['airwaybill_kode']  != '' ? $r['airwaybill_kode'] : '001-00123456'); ?> 	</td>
		  		<td style="font-size: 28px; font-weight: bold;width: 80px; " rowspan="2" colspan="2">	<?php 
		  		
		  		echo ($r['kodekotaasal']  != '' ? $r['kodekotaasal'] : $r['kode_cabang_asal']); ?>

		  			<hr style="border-color: #EEEEEE -moz-use-text-color #FFFFFF; margin-left:20px; margin-right :20px;">

		  			<?php echo ($r['kode_kota_tujuan']  != '' ? $r['kode_kota_tujuan'].' -' : ''); ?>  <?php echo ($r['kodebarukotatujuan']  != '' && $r['id_pelayanan'] !=6 ? $r['kodebarukotatujuan'] : $r['kode_cabang_tujuan']); ?>
		  		</td>
		  		
		  		
		  </tr>		  
		  <tr >	
		  	
		  	<th style="width: 60px;">KOTA ASAL	</th>
		 	<td colspan="2" style="font-size: 13px; font-weight: bold;">
		 		<?php  echo substr(($r['id_kota_asal']  > 0 ? $r['kecamatan_asal'] : $r['lokasi_asal']), 0, 30) ;?>, 

		 		<?php  echo substr(($r['id_kota_asal']  > 0 ? ($r['kota_asal'] !='' ? $r['kota_asal'] : $r['xnamakotaasal'] ) : $r['lokasi_asal']), 0, 30) ;?>
		 	</td>
		 	
		 	<th style="width: 65px;">KOTA TUJUAN	</th>
		 	<td colspan="2" style="font-size: 13px; font-weight: bold;"><?php  echo substr(($r['id_kota_tujuan']  > 0 ? $r['kecamatan_tujuan'] : $r['lokasi_tujuan']), 0, 30) ;?>, 

		 		<?php  echo substr(($r['id_kota_tujuan']  > 0 ? ($r['kota_tujuan'] !='' ? $r['kota_tujuan'] : $r['xnamakotatujuan'] ) : $r['lokasi_tujuan']), 0, 30) ;?></td>
		 	
		 </tr>
		 <tr>
		 	<th  style="border-bottom-style:none; font-size: 10px;">PENGIRIM</th>
		 	<th >TELEPON</th>
		 	<td style="font-size: 12px;letter-spacing: 0.5px; width: 180px;"><?php echo ($r['hp_pengirim']  != '' ? $r['hp_pengirim'] : ''); ?></td>
		 	<th style="border-bottom-style:none;font-size: 10px;">PENERIMA</th>
		 	<th>TELEPON	</th>
		 	<td style="font-size: 12px;letter-spacing: 0.5px; width: 100px;"><?php echo ($r['hp_penerima']  != '' ? $r['hp_penerima'] : ''); ?></td>
		 	<th>JENIS LAYANAN	</th>
		 	<td style="font-size: 10px;"><?php echo ($r['pelayanan']  != '' ? substr($r['pelayanan'],0,15) : ''); ?></td>
		 </tr>	
		 <tr>	
		 		<td colspan="3" style="border-bottom-style:none;border-top-style: none;font-size: 10px;"><?php echo ($r['nama_pengirim']  != '' ? $r['nama_pengirim'] : '');  echo ($r['id_kustomer'] ==13 ? '/ PID : '.$r['pid'] : '' ); ?></td>
		 		
		 		<td colspan="3"  style="border-bottom-style:none;border-top-style: none; font-size: 10px;"><?php echo ($r['nama_penerima']  != '' ? $r['nama_penerima'] : ''); ?></td>
		 		
		 		<th>PACKING </th>
		 		<td style="font-size: 10px;"> <?php echo $packing; ?>	</td>
		 </tr>
		 <tr>			 	
		 		<td colspan="3" style="border-top-style:none; font-size: 12px; font-weight: bold;"><?php echo ($r['perusahaan_pengirim']  != '' ? $r['perusahaan_pengirim'] : 'PT. ABCD EFGH IJKL MNOP'); ?>	</td>
		 		<td colspan="3"  style="border-top-style:none; font-size: 12px; font-weight: bold;"><?php echo ($r['perusahaan_penerima']  != '' ? $r['perusahaan_penerima'] : 'PT. OPQRS TUVW XYZ'); ?>	</td>
		 		<th>ASURANSI</th>
		 		<td><?php echo ($r['asuransi'] == 'N' ? 'TIDAK' : 'YA'); ?></td>
		 </tr>
		 <tr>	
		 	<th colspan="3" style="font-size: 10px;">ALAMAT PENGIRIM</th>
		 	<th colspan="3" style="font-size: 10px;">ALAMAT PENERIMA</th>
		 	<th rowspan="1">DO KEMBALI</th>
		 	<td rowspan="1" style="font-size: 10px;"><?php echo ($r['surat_jalan'] == 'N' ? 'TIDAK' : 'YA');  ?></td>		 	
		 </tr>
		 <tr>	
		 	<td rowspan="1" colspan="3" align="left" style="border-bottom-style:none; font-size: 12px;"><?php echo ($r['alamat_pengirim']  != '' ? substr($r['alamat_pengirim'],0,52) : '-'); ?> </td>
		 	<td rowspan="1" colspan="3" align="left" style="border-bottom-style:none; font-size: 12px;"><?php echo ($r['alamat_penerima']  != '' ? substr($r['alamat_penerima'],0,52) : '-'); ?> 
		 	</td>
		 	<th >DITAGIH KE</th>
		 	<td style="font-size: 10px;">PENGIRIM</td>
		 </tr>
		 <tr>	
		 	<td rowspan="1" align="left" colspan="3" style="border-top-style:none;font-size: 12px;">
		 	<?php echo ($r['alamat_pengirim2']  != '' ? substr($r['alamat_pengirim2'],0,52) : ''); ?>
		 	
		 </td>
		 	<td rowspan="1" align="left" colspan="3" style="border-top-style:none;font-size: 12px;"><?php echo ($r['alamat_penerima2']  != '' ? substr($r['alamat_penerima2'],0,52) : ''); ?> 
		 	 
		 	</td>
		 	<th>TGL BOOKING	</th>
		 	<td><?php echo $r['tanggal_booking']; ?></td>	
		 </tr>
		 <tr>
		 	<td colspan="3" rowspan="5" style="vertical-align: text-bottom; border-bottom-style:none; font-size: 9px;">TANDA TANGAN PENGIRIM</td>
		 	<td colspan="3" rowspan="5" style="vertical-align: text-bottom; border-bottom-style:none; font-size: 9px;">TANDA TANGAN PENERIMA</td>
		 	<th>Booking oleh</th>
		 	<td><?php echo ($r['booking']  != '' ? $r['booking'] : ''); ?></td>
		 </tr>
		 <tr>
		 	<th>Kode Pengiriman</th>
		 	<td><?php echo ($r['loc_code']  != '' ? substr($r['loc_code'],0,20) : ''); ?></td>
		 </tr>
		 <tr>
		 	<th>Diambil oleh </th>
		 	<td><?php echo ($r['pickup_by']  != '' ? substr($r['pickup_by'],0,15) : ''); ?></td>
		 </tr>
		 <tr>
		 	<th>Diantar oleh </th>
		 	<td><?php echo ($r['courier_by']  != '' ? substr($r['courier_by'],0,17) : ''); ?></td>
		 </tr>
		 <tr>
		 	<th>Alasan kembali  </th>
		 	<td>-</td>
		 </tr>
		 <tr>
		 	<td colspan="3" style="vertical-align: text-bottom; border-top-style:none; font-size: 9px;"><?php echo ($r['nama_pengirim']  != '' ? substr($r['nama_pengirim'],0,17) : '_______________________'); ?> | <?php echo ($r['pickup_date']  != '' ? substr($r['pickup_date'],0,17) : 'tanggal : _______________________'); ?></td>
		 	<td  colspan="3" style="vertical-align: text-bottom; border-top-style:none; font-size: 9px;"><?php echo ($r['delivery_name']  != '' ? substr($r['delivery_name'],0,17) : '_______________________'); ?>  | <?php echo ($r['delivery_date']  != '' ? substr($r['delivery_date'],0,17) : 'tanggal : _______________________'); ?></td>
		 	<th>Isi kiriman </th>
		 	<td><?php echo ($r['komoditi']  == 1 ? substr($r['jenis_barang'],0,12) : 'DOKUMEN'); ?></td>
		 </tr>
		 <tr>
		 	<td colspan="8" style="font-size: 8px;"> Tanda Terima ini adalah bukti sah pengiriman & semua pihak setuju akan ketentuan Cesco Logistics.<br>
		 		Batas waktu komplain adalah 1x24 jam dari TTD Penerima pada AWB ini
		 	</td>
		 	
		 </tr>
		 
		 <?php
		 $j=0;
		 $tb = 0;
		 
		 $q = mysqli_query($con, "select * from detail_collie where id_pengiriman='".$id."' order by koli_ke asc ");
		 $ketemu = mysqli_num_rows($q);

		 if($ketemu > 0){


		 	echo 
		 	'
			 <tr >
			 	<th style="border-top-style:none;border-bottom-style:none;border-left-style:none;border-right-style:none;">KOLI KE</th>
			 	<th style="border-top-style:none;border-bottom-style:none;border-left-style:none;border-right-style:none;" colspan="4">catatan pengiriman(bila ada)</th>
			 	<th style="border-top-style:none;border-bottom-style:none;border-left-style:none;border-right-style:none;">berat kg</th>
			 	<th style="border-top-style:none;border-bottom-style:none;border-left-style:none;border-right-style:none;">p x l x t (CM)</th>
			 	<th style="border-top-style:none;border-bottom-style:none;border-left-style:none;border-right-style:none;">berat dihitung</th>
			 </tr>
		 	';
			 while($row = mysqli_fetch_assoc($q)){
			 //for($i=1;$i<41;$i++){

			 	if($r['intercity'] == 1){
              			$beratvol = $row['p']*$row['l']*$row['t']/4000;
              	}
             	else{
           			if($r['id_pelayanan'] == 5){
           				$beratvol = $row['p']*$row['l']*$row['t']/4000;
           			}
              		else{
              			$beratvol = $row['p']*$row['l']*$row['t']/6000;
              		}
              	}

			 	echo '
			 		 <tr >
					 	<td style="border-top-style:none;border-bottom-style:none;border-left-style:none;border-right-style:none;">'.$row['koli_ke'].'</td>
					 	<td colspan="4" style="border-top-style:none;border-bottom-style:none;border-left-style:none;border-right-style:none;"> '.$row['catatan'].' </td>
					 	<td style="border-top-style:none;border-bottom-style:none;border-left-style:none;border-right-style:none;">'.$row['berat'].'</td>
					 	<td style="border-top-style:none;border-bottom-style:none;border-left-style:none;border-right-style:none;">'.$row['p'].' x '.$row['l'].' x '.$row['t'].' ( '.number_format($beratvol,2).' )'.'</td>
					 	<td style="border-top-style:none;border-bottom-style:none;border-left-style:none;border-right-style:none;">'.$row['berat_real'].'</td>
					 </tr>
			 	';
			 	$j=$j+1;
			 	$tb=$tb+$row['berat_real'];
			 }
		  ?>
		 <tr>
		 	<td colspan="7" style="border-top-style:none;border-right-style:none;text-align: right;"><b>total berat</b></td>
		 	<td style="border-left-style: none;border-top-style: none; "><b><?php echo number_format($r['berat_aktual'],0); ?>
		 	 kg</b></td>
		 </tr>

		 <?php 
		}


		 else{
		 	echo 
		 	'
			 <tr >
			 	<th style="border-top-style:none;border-bottom-style:none;border-left-style:none;border-right-style:none;">JUMLAH KOLI</th>
			 	<th style="border-top-style:none;border-bottom-style:none;border-left-style:none;border-right-style:none;" colspan="4">catatan pengiriman(bila ada)</th>
			 	<th style="border-top-style:none;border-bottom-style:none;border-left-style:none;border-right-style:none;">berat kg</th>
			 	<th style="border-top-style:none;border-bottom-style:none;border-left-style:none;border-right-style:none;">p x l x t (CM)</th>
			 	<th style="border-top-style:none;border-bottom-style:none;border-left-style:none;border-right-style:none;">berat dihitung</th>
			 </tr>
		 	';
		 	
		  		if($r['intercity'] == 1){
              			$beratv = $r['width']*$r['length']*$r['height']/4000;
              	}
             	else{
           			if($r['id_pelayanan'] == 5){
           				$beratv = $r['width']*$r['length']*$r['height']/4000;
           			}
              		else{
              			$beratv = $r['width']*$r['length']*$r['height']/6000;
              		}
              	}
		  		
		 	echo '
			 		 <tr >
					 	<td style="border-top-style:none;border-bottom-style:none;border-left-style:none;border-right-style:none;">'.$r['collie'].'</td>
					 	<td colspan="4" style="border-top-style:none;border-bottom-style:none;border-left-style:none;border-right-style:none;"> '.$r['catatan1'].' </td>
					 	<td style="border-top-style:none;border-bottom-style:none;border-left-style:none;border-right-style:none;">'.$r['berat_aktual'].'</td>
					 	<td style="border-top-style:none;border-bottom-style:none;border-left-style:none;border-right-style:none;">'.$r['width'].' x '.$r['length'].' x '.$r['height'].' ('.number_format($beratv,2).' KG)'.'</td>
					 	<td style="border-top-style:none;border-bottom-style:none;border-left-style:none;border-right-style:none;">'.($r['berat_aktual'] > $beratv ? $r['berat_aktual'] : number_format($beratv,2)).'</td>
					 </tr>
			 	';
			 	if($r['collie'] > 1 && $ketemu > 1){
			 		for($i=2; $i<=$r['collie']; $i++){
				 	
				 		echo '
				 		 <tr >
						 	<td style="border-top-style:none;border-bottom-style:none;border-left-style:none;border-right-style:none;"></td>
						 	<td colspan="4" style="border-top-style:none;border-bottom-style:none;border-left-style:none;border-right-style:none;"> '.$r['catatan'.$i].' </td>
						 	<td style="border-top-style:none;border-bottom-style:none;border-left-style:none;border-right-style:none;"></td>
						 	<td style="border-top-style:none;border-bottom-style:none;border-left-style:none;border-right-style:none;"></td>
						 	<td style="border-top-style:none;border-bottom-style:none;border-left-style:none;border-right-style:none;"></td>
						 </tr>';
				 	
					}
				}
		 
		 ?>
		 <tr>
		 	<td colspan="7" style="border-top-style:none;border-right-style:none;text-align: right;"><b>total berat</b></td>
		 	<td style="border-left-style: none;border-top-style: none; "><b><?php echo ($r['berat_aktual'] > $beratv ? $r['berat_aktual'] : number_format(round($beratv),0)); ?>
		 	 kg</b></td>
		 </tr>

		 <?php 
		}

		if($r['jumlah_detail_barang'] > 0){
		 ?>
	
		  <tr> 	
		  		<th colspan="1" style="border-top-style:none;border-bottom-style:none;border-left-style:none;border-right-style:none;">No</th>	  		
		  		<th colspan="6" style="border-top-style:none;border-bottom-style:none;border-left-style:none;border-right-style:none;">Detail Barang</th>
		  		<th colspan="1" style="border-top-style:none;border-bottom-style:none;border-left-style:none;border-right-style:none;">QTY</th>
		  		
		  </tr>

		  <?php
		 $q_detail_barang = mysqli_query($con, "select * from detail_catatan_barang where id_pengiriman='".$id."' order by detail_catatan_ke asc ");
		 $k = mysqli_num_rows($q_detail_barang);

		 if($k > 0){

		 	while($row = mysqli_fetch_assoc($q_detail_barang)){

		  	echo '
			 		 <tr >
					 	<td style="border-top-style:none;border-bottom-style:none;border-left-style:none;border-right-style:none;">'.$row['detail_catatan_ke'].'</td>
					 	<td colspan="6" style="border-top-style:none;border-bottom-style:none;border-left-style:none;border-right-style:none;"> '.$row['detail_catatan'].'</td>
					 	<td style="border-top-style:none;border-bottom-style:none;border-left-style:none;border-right-style:none;">'.$row['qty'].'</td>
					 	
					 </tr>
					 
			 	';

			 }
			}
		}

		  ?>
		  
		   		  		
		</table>
		<br>
		
	
	</div>
	
</body>

</html>

