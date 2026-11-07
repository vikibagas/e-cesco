<?php


include ('../../config/connection.php');
include ('../../config/encrypt.php');
include ('../../config/library.php');
include ('phpqrcode/qrlib.php'); 
	
$key = "asdf$1011";
$encrypt = "$_GET[id]";
$id = base64_decrypt($encrypt,$key);
$query = mysqli_query($con, "select 
x_namakotabesar(a.lokasi_asal) as kota_asal,
b.kode_kota as kode_kota_asal,
case when a.service='6' then x_namanegara(a.lokasi_tujuan) else x_namakotabesar(a.lokasi_tujuan) end as kota_tujuan,
case when a.service='6' then '' else c.kode_kota end as kode_kota_tujuan,
CASE 
	WHEN airwaybill_kode LIKE '001-%' THEN 0
	WHEN airwaybill_kode LIKE '002-%' THEN 1
end as intercity,
a.service as id_pelayanan,
a.nama_pengirim,
a.perusahaan_pengirim as  perusahaan_pengirim,
a.nama_penerima,
a.perusahaan_penerima,
a.airwaybill_kode,
x_service(a.service) as service,
a.hp_pengirim,
a.hp_penerima,
a.packing_kayu,
a.alamat_pengirim,
x_namakotadetail(a.lokasi_asal) as alamat_pengirim1,
a.alamat_penerima,
x_namakotadetail(a.lokasi_tujuan) as alamat_penerima1,
a.asuransi,
a.kota_asal as kota_asal_new,
a.kota_tujuan as kota_tujuan_new,
a.kecamatan_asal,
a.kecamatan_tujuan,
a.surat_jalan,
x_kodekotabaru(id_kota_asal) as kodekotaasal,
x_kodekotabaru(id_kota_tujuan) as kodekottujuan,
ff.kode_kota as kodecabangtujuan,
a.biaya_beban,
a.loc_code,
a.berat_barang,
coalesce(a.berat_aktual,a.berat_barang) as berat_aktual,
a.tanggal,
a.ukuran_barang,
x_user(a.pickup_by) as pickup_by,
a.pickup_date,
a.catatan,
x_user(a.delivery_by) as delivery_by,
CASE WHEN a.delivery_time IS NOT NULL THEN DATE_FORMAT(concat(a.delivery_date,' ',a.delivery_time), '%d-%m-%Y %H:%i') ELSE DATE_FORMAT(a.delivery_date, '%d-%m-%Y') END AS delivery_date,
a.delivery_name,
a.delivery_status,
a.komoditi,
a.jenis_barang,
a.collie,
a.inbound,
case when d.level='kurir' then 'MOTOR' when d.level='driver' then 'MOBIL' end as pickup,
a.delivery_sign,
case when a.user_client IS NOT NULL THEN (select fullname FROM user_client WHERE id_user=a.user_client) ELSE (SELECT namalengkap FROM user WHERE id_user=a.created_by) END AS booking
from pengiriman a 
LEFT join kota b on b.id_kota=a.lokasi_asal
LEFT join kota c on c.id_kota=a.lokasi_tujuan
left join user d on d.id_user=a.pickup_by
left join regencies ff on ff.id= x_hubnew(a.id_kota_tujuan)
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
	
	<title>Editable Invoice</title>
	
	<link rel='stylesheet' type='text/css' href='../../dist/css/airwaybill_new.css' />
	<link rel='stylesheet' type='text/css' href='css/print.css' media="print" />
	
	

</head>

<body>

	<div id="page-wrap">

		
		
		
		
		<table id="items">
		  <tr>	
		  		<td colspan="3" rowspan="2">	<img src="../../../../assets/img/cesco-new-logo21.png" style="width: 50%;"></td>
		  				<td colspan="3" style="font-size:25px;"> <?php echo $r['airwaybill_kode']; ?> </td>
		  						<td colspan="2">	<?php
		  						QRcode::png("".$r['airwaybill_kode']."","../../reportsnew/code/".$r['airwaybill_kode'].".png","M",3,3); 

		  						echo '<img src="../../reportsnew/code/'.$r['airwaybill_kode'].'.png" >' ?> </td>
		  </tr>


		  <tr>	
		  		<td colspan="3" style="font-size: 16px;">	WWW.CESCO-LOGISTICS.COM	</td>
		  		<td style="font-size: 28px;">	<?php echo $r['kodekotaasal']; ?></td>
		  		<td style="font-size: 16px; font-weight: bold;"><?php echo $r['kodecabangtujuan'].' 
		  		'.$r['kodekottujuan']; ?></td>
		  		
		  </tr>		  
		  <tr >	
		  	<th>PENGIRIM</th>
		  	<th>KOTA KAB ASAL	</th>
		 	<td >
		 		<?php
					echo strtoupper($r['kota_asal_new']);
				?>
		 	</td>
		 	<th>PENERIMA	</th>
		 	<th>KOTA KAB TUJUAN	</th>
		 	<td ><?php echo strtoupper($r['kota_tujuan_new']); ?>	</td>
		 	<th>JENIS LAYANAN	</th>
		 	<td><?php echo strtoupper($r['service']); ?></td>
		 </tr>
		 <tr>
		 	<td><?php echo $r['nama_pengirim']; ?>	</td>
		 	<th >PHONE NO	</th>
		 	<td><?php echo $r['hp_pengirim']; ?></td>
		 	<td><?php echo $r['nama_penerima']; ?></td>
		 	<th>PHONE NO	</th>
		 	<td><?php echo $r['hp_penerima']; ?></td>
		 	<th>PACKING	</th>
		 	<td><?php echo $packing; ?></td>
		 </tr>	
		 <tr>	
		 		<th>PERUSAHAAN PENGIRIM</th>
		 		<td colspan="2" rowspan="2"><b>DARI</b> : <?php echo $r['alamat_pengirim'].', kec.'.$r['kecamatan_asal']; ?></td>
		 		<th>PERUSAHAAN Penerima</th>
		 		<td colspan="2" rowspan="2"><b>TUJUAN</b> : <?php echo $r['alamat_penerima'].', kec.'.$r['kecamatan_tujuan']; ?>	</td>
		 		<th>ASURANSI </th>
		 		<td> TIDAK	</td>
		 </tr>
		 <tr>			 	
		 		<td><?php echo $r['perusahaan_pengirim']; ?>	</td>
		 		<td><?php echo $r['perusahaan_penerima']; ?>	</td>
		 		<th>TANGGAL BOOKING	</th>
		 		<td><?php echo $r['tanggal']; ?>	</td>
		 </tr>
		 <tr>	
		 	<th rowspan="3">TOTAL COLLIE	</th>
		 	<td rowspan="3"><?php echo $r['collie']; ?>	</td>
		 	<th rowspan="3">TOTAL UKURAN</th>
		 	<td rowspan="3">

		 		<?php 
		 		$q = mysqli_query($con, "select * from detail_collie where id_pengiriman='".$id."' order by koli_ke asc ");
				 $ketemu = mysqli_num_rows($q);

				 if($ketemu > 0){
				 	while($row = mysqli_fetch_assoc($q)){
				 		$j=0;
				 		$tb=0;
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
					 		
							 	
							 	
							 	
							 	'.$row['p'].' x '.$row['l'].' x '.$row['t'].' ( '.number_format($beratvol,2).' )'.'
							 	
							 <br>
					 	';
					 	$j=$j+1;
					 	$tb=$tb+$row['berat_real'];
					}
				 }
				 else{
				 	echo $r['ukuran_barang'];
				 }

		 		?>
		 		

		 	</td>
		 	<th rowspan="3">TOTAL BERAT	</th>
		 	<td rowspan="3"><?php echo $r['berat_aktual']; ?> KG</td>
		 	<th>DIBOOKING OLEH	</th>
		 	<td><?php echo $r['booking']; ?></td>
		 </tr>
		 <tr>	
		 	<th>KODE PENGIRIMAN	</th>
		 	<td><?php echo $r['loc_code']; ?></td>	
		 </tr>
		 <tr>	
		 	<th>DO KEMBALI	</th>
		 	<td>YA</td>	
		 </tr>
		 
	</table>
	<table id="items">
		  <tr>	
		  		<th colspan="8">	DATA BARANG</th>
		  </tr>
		  <tr style="font-size:12px;">
		      <th>No</th>
		      <th>Product(DO NUMBER)</th>
		      <th>APRC</th>
		      <th>PART NO</th>
		      <th>PART DEST</th>
		      <th>RMA NO / PI NO</th>
		      <th>QTY</th>
		      <th>RE MARKS</th>
		  </tr>
		  <?php 
		  $sql = mysqli_query($con, "select id_pengiriman,product,aprc,part_n,part_n_desc,rma_no,qty,remark from asus_detail_catatan  where id_pengiriman='".$id."'");

				$cek = mysqli_num_rows($sql);
				if ($cek > 0){
					$no=1;
							      while ($row = mysqli_fetch_assoc($sql)) {
							      	
								    echo '  
								    <tr class="item-row" style="font-size:12px;">
								      <td>'.$no.'</td>
								        <td>'.$row['product'].'</td> 
								        <td>'.$row['aprc'].'</td>
								        <td>'.$row['part_n'].'</td>
								        <td>'.$row['part_n_desc'].'</td>
								        <td>'.$row['rma_no'].'</td>
								        <td>'.$row['qty'].'</td>
								        <td>'.$row['remark'].'</td>
								    </tr> 
								      ';
								      $no++;
							      }
				}
		  $sum_qty = mysqli_query($con, "select SUM(qty) as sum_qty from asus_detail_catatan where id_pengiriman='".$id."'");
		  $rsum = mysqli_fetch_assoc($sum_qty);
		  ?>
		  <tr class="item-row" style="font-size:12px;font-weight: bold;">	
		  		<td colspan="6" style="text-align: right;">TOTAL	</td>
		  		<td> <?php echo $rsum['sum_qty']; ?>	</td>
		  		<td> </td>
		  </tr>
		</table>
		<table  id="items">
		  <tr style="font-size:12px;"> 		  		
		  		<th colspan="2">AIRWAYBILL KODE</th>
		  		<td colspan="2"><?php echo $r['airwaybill_kode'] ?></td>		  		
		  		<th colspan="2">PERUSAHAAN PENERIMA	</th>
		  		<td colspan="2"><?php echo strtoupper($r['perusahaan_penerima']); ?>	</td>
		  </tr>
		  <tr id="items"> 		  		
		  		<th colspan="4">CATATAN KIRIMAN</th>
		  		<th class="meta-head">DIAMBIL OLEH</th>
		  		<td><?php echo strtoupper($r['pickup_by']); ?></td>
		  		<th class="meta-head">DIANTAR OLEH	</th>
		  		<td><?php echo strtoupper($r['delivery_by']); ?>	</td>
		  </tr>
		  <tr > 
		  		<td colspan="4" class="blank"><?php echo $r['catatan']; ?></td>
		  		<th class="meta-head">NAMA PENGIRIM</th>
		  		<th class="meta-head">TANGGAL KIRIM</th>
		  		<th class="meta-head">NAMA PENERIMA</th>
		  		<th class="meta-head">TANGGAL TERIMA</th>
		  </tr id="items">
		  <tr > 		  		
		  		<td colspan="4" class="blank"></td>
		  		<td><?php echo $r['nama_pengirim']; ?></td>
		  		<td><?php 
		  			if($r['pickup'] == "Y"){	
		  				echo strtoupper($r['pickup_date']);
		  			}

		  		?></td>
		  		<td><?php echo strtoupper($r['delivery_name']); ?></td>
		  		<td><?php echo strtoupper($r['delivery_date']); ?></td>
		  </tr>
		  <tr> 		  		
		  		<td colspan="4" class="blank"></td>
		  		<th >STATUS PENGIRIM</th>
		  		<td>-</td>
		  		<th >STATUS PENERIMA</th>
		  		<td><?php echo $r['delivery_status']; ?></td>
		  </tr>
		  <tr> 		  				  		
		  		<td colspan="4" class="blank"></td>
		  		<th colspan="2">TANDA TANGAN & NAMA PENGIRIM</th>
		  		<th colspan="2">TANDA TANGAN & NAMA PENERIMA</th>		  				  		
		  </tr>		  		  		
		  <tr> 		  				  		
		  		
		  		<td colspan="4" style="font-size: 7px;">TANDA TERIMA INI ADALAH BUKTI SAH PENGIRIMAN & SEMUA PIHAK SETUJU AKAN KETENTUAN YANG ADA.			</td>
		  		<td colspan="2" style="border-bottom: 0;"></td>
		  		<td colspan="2" class="blank"></td>
		  		
		  </tr>	
		  <tr> 		  				  		
		  		
		  		<td colspan="4" style="font-size: 7px;">SETELAH PENERIMA PARAF DI TANDA TERIMA INI SEGALA MACAM KELUHAN TIDAK DAPAT DI PENUHI.	</td>
		  		<td colspan="2" style="border-right: 1px solid black ;" class="blank"></td>
		  		<td colspan="2" class="blank"></td>
		  		
		  </tr>		  		  		
		</table>
		
		
	
	</div>
	
</body>

</html>

<?php 



?>