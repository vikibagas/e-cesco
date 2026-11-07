<?php
if(!isset($_SESSION)){
    session_start();
}

if (empty($_SESSION['username'])){
	header('location:../../ecesco/login.php');
}
else{

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
a.kondisi_packing_pickup,
a.kondisi_barang_pickup,
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
		  <tr  >	
		  		<td rowspan="3" colspan="2"  width="150">	<img src="../dist/img/logocesco2024.png" width="39%"></td>
		  				<td  colspan="3" style="font-size: 10px;font-weight: bold; min-width:320px ; max-width: 330px;"> BUKTI PEMERIKSAAN ISI KIRIMAN/PACKAGING PT CESCO LOGISTICS INDONESIA </td>
		  				

		  						
		  </tr>
		  <tr>
		  		<th>PENGIRIM</th>
		  		<th>KURIR</th>
		  		<th>PENERIMA</th>
		  </tr>
		  <tr>
		  		
				<td rowspan="3" style="vertical-align: text-bottom;">TTD NAMA JELAS</td>
 				<td rowspan="3" style="vertical-align: text-bottom;">TTD NAMA JELAS</td>
 				<td rowspan="3" style="vertical-align: text-bottom;">TTD NAMA JELAS</td>
		  </tr>

		  <tr>
		  		<th style="max-width: 70px;">NO AWB</th>
		  		<td><?php echo $r['airwaybill_kode']; ?></td>
		  </tr>
		  <tr>
		  		<th>Tanggal</th>
		  		<td><?php echo $r['pickup_date']; ?></td>
		  </tr>
		  <tr>
		  		<th>KOLI</th>
		  		<th>ISI KIRIMAN</th>
		  		<th>DETAIL MERK/TYPE/SN/JUMLAH</th>
		  		<th>KONDISI PACKING</th>
		  		<th>KONDISI BARANG</th>
		  </tr>

		 


		  <?php

		  $q1 = mysqli_query($con, "select * from detail_collie where id_pengiriman='".$id."' order by koli_ke asc limit 1 ");
		  $r1 = mysqli_fetch_assoc($q1);
		  ?>

		  <tr>
		  		<td> <?php echo $r1['koli_ke']; ?> </td>
		  		<td> <?php echo $r['jenis_barang']; ?> </td>
		  		<td><?php echo $r1['catatan']; ?></td>
		  		<?php echo "<td rowspan='".$r['collie']."'>";?>
		  		
		  		<?php echo $r['kondisi_packing_pickup']; ?><br><br>

		  		
		  		</td>
		  		<?php echo "<td rowspan='".$r['collie']."'>";?>
		  		
		  		<?php echo $r['kondisi_barang_pickup']; ?><br><br>

		  		
		  		</td>
		  		
		  		
		  </tr>



		  <?php
		  $q = mysqli_query($con, "select * from detail_collie where id_pengiriman='".$id."' order by koli_ke asc limit 1,100 ");




		 $ketemu = mysqli_num_rows($q);

		 if($ketemu > 0){
		  	while($row = mysqli_fetch_assoc($q)){
		  ?> 
		  <tr>
		  		<td> <?php echo $row['koli_ke']; ?> </td>
		  		<td> <?php echo $r['jenis_barang']; ?> </td>
		  		<td><?php echo $row['catatan']; ?></td>
		  		
		  		
		  		
		  </tr>

		  <?php
		   }
		}
		  ?>

		 

		 
		  
		   		  		
		</table>
		<br>
		
	
	</div>
	
</body>

</html>

<?php 

}

?>
	