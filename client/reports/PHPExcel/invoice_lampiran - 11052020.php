<?php
include ('../../config/connection.php');
include ('../../config/encrypt.php');
include ('../../config/library.php');
include ('phpqrcode/qrlib.php'); 
	
/*$key = "asdf$1011";
$encrypt = "$_GET[id]";
$id = base64_decrypt($encrypt,$key);
			
$pieces = explode('-', $id);
$delivery_by = $pieces['0'];
$delivery_date = implode('-', array_slice($pieces, 1, 3));
			
$query = mysqli_query($con, "select b.namalengkap from pengiriman a inner join user b on b.id_user=a.delivery_by where a.delivery_by='$delivery_by' and DATE_FORMAT(a.delivery_date,'%Y-%m-%d')='$delivery_date'");
$r = mysqli_fetch_assoc($query);

$tanggal = tgl_indo($delivery_date);

$query_kode_manifest = mysqli_query($con, "select kode_manifest from manifest 
				where tanggal='$delivery_date' and manifest_delivery_by = '$delivery_by'");
			$r_kode = mysqli_fetch_assoc($query_kode_manifest);*/



$id_inv = $_GET['idinvoice'];
			

$query 		= mysqli_query($con,"select a.*, case when a.status='draft' then 'DRAFT' else a.nomor end as nomor, b.kode_kustomer, b.nama_kustomer, b.alamat, b.kota, c.nama_kota, x_namakota(x_hub(b.kota)) as kota, b.telp from invoice a left join kustomer b on b.id_kustomer=a.id_kustomer left join kota c on c.id_kota=b.kota where id_invoice='$id_inv'");
			$r 			= mysqli_fetch_assoc($query);

$qtotal = mysqli_query($con, "SELECT sum(if(b.next_kg is not null or c.next_kg is not null, if(if(berat_aktual is null or berat_aktual < 1, berat_barang, berat_aktual) <= ifnull(b.min_kg,c.min_kg), (ifnull(b.min_kg,c.min_kg)*ifnull(b.harga,c.harga))+a.biaya_packing+a.biaya_tambahan+a.biaya_asuransi , ifnull(b.harga,c.harga)*ifnull(b.min_kg,c.min_kg)+((if(berat_aktual is null or berat_aktual < 1, berat_barang, berat_aktual)-ifnull(b.min_kg,c.min_kg))*ifnull(b.next_kg,c.next_kg)) ), if(if(berat_aktual is null or berat_aktual < 1, berat_barang, berat_aktual) < ifnull(b.min_kg,c.min_kg), (ifnull(b.min_kg,c.min_kg)*ifnull(b.harga,c.harga))+a.biaya_packing+a.biaya_tambahan+a.biaya_asuransi , (if(berat_aktual is null or berat_aktual < 1, berat_barang, berat_aktual)*ifnull(b.harga,c.harga))+a.biaya_packing+a.biaya_tambahan+a.biaya_asuransi ) )) as subtotal
										FROM pengiriman a 
								left join rate_customer b ON b.id_kustomer=a.id_kustomer and b.id_lokasi_asal=a.lokasi_asal and b.id_lokasi_tujuan=a.lokasi_tujuan and b.id_pelayanan=a.service and b.komoditi=a.komoditi and b.loc_code=a.loc_code	
								left join rate_customer c ON c.id_kustomer=a.id_kustomer and c.id_lokasi_asal=a.lokasi_asal and c.id_lokasi_tujuan=a.lokasi_tujuan and c.id_pelayanan=a.service and c.komoditi=a.komoditi	
								where a.id_invoice = $id_inv and a.status != 7 ");
						$st = mysqli_fetch_assoc($qtotal);
header("Content-type: application/vnd-ms-excel");
header('Content-Disposition: attachment; filename="'.$r['nama_kustomer'].$r['dari'].'-'.$r['samapi'].'_'.$id_user.'.xls"');
?>

<!DOCTYPE html>
<html>
	<head>
	<meta charset="UTF-8">
		<title>Invoice Customer Detail</title>
	</head>
	<body>
		<table cellspacing=0 width="100%">
			<tr>
				<td style="font-family:Calibri;text-align:center;font-size:16px;color:#000000;font-weight:bold;" colspan="8">Invoice Customer Detail</td>
			</tr>
			<tr>
				<td colspan="10"></td>
			</tr>
			
			<tr>
			  <td colspan="2" style=min-width:50px><span style="font-family:Calibri;text-align:right;font-size:14px;color:#000000;">Customer :</span></td>
			  <td colspan="8" style=min-width:50px><span style="font-family:Calibri;font-size:14px;color:#000000;"><?php echo''.$r['nama_kustomer'];?></span></td>
		    </tr>
			<tr>
			  <td colspan="2" style=min-width:50px><span style="font-family:Calibri;text-align:right;font-size:14px;color:#000000;">Periode :</span></td>
			  <td colspan="8" style=min-width:50px><span style="font-family:Calibri;font-size:14px;color:#000000;"><?php echo''.$r['dari'].' - '.$r['sampai'];?></span></td>
		    </tr>
			
			<tr>
				<td colspan="8">&nbsp;</td>
			</tr>
			<tr>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border:1px solid;">NO</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border:1px solid;">Tanggal Pickup</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">AWB</td>
				<?php 
					//$sqll = mysqli_query($con, "select id_pengiriman,product,aprc,part_n,part_n_desc,rma_no,qty,remark from asus_detail_catatan  where id_pengiriman='".$t['id_pengiriman']."'");
					//$number = 0.5;
					//$number = $number-1;*/
						//$cek = mysqli_num_rows($sqll);
						if ($r['id_kustomer'] == 106){
							?>
								<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid; width: 125px;">APRC</td>
							<?php
						}
				?>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">komoditi</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Service</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Requestor</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Loc Code</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Pengirim</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Nama Perusahaan Pengirim</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Kota Asal</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Alamat lengkap Asal</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Nama Perusahaan Penerima</td>				
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Nama Penerima</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Kota Tujuan</td>				
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Alamat lengkap Penerima</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Nama Penerima</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Tanggal Diterima</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Volume</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Berat Aktual</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Harga/kg</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">min kg</td>				
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Tambahan</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Harga Pengiriman</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Packing</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Asuransi</td>				
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Total</td>
			</tr>
			<?php
			$no=1;
			$cari = "SELECT 
									id_pengiriman, 
									date(pickup_date) as tanggal, 
									airwaybill_kode, 
									case when a.komoditi='1' then 'Paket' else 'Dokumen' end as komoditi,
									a.jenis_barang,
									x_service(service) as service, 
									nama_pengirim, 
									a.loc_code, 
									x_namakustomer(a.id_kustomer) as namakustomer, 
									nama_penerima, 
									case when inbound='Y' then 'INBOUND' when inbound='N' then 'OUTBOUND' else '' end as inbound, 
									id_kecamatan_asal,
									x_namakecamatan(id_kecamatan_asal) as kec_asal,
									a.lokasi_asal as id_lokasi_asal, 
									if(a.id_kota_tujuan>0,a.kota_asal,x_namakotabesar(lokasi_asal)) as lokasi_asal, 
									alamat_pengirim, 
									id_kecamatan_tujuan,
									x_namakecamatan(id_kecamatan_tujuan) as kec_tujuan,
									lokasi_tujuan, 
									if(a.id_kota_tujuan>0,a.kota_tujuan,x_namakotabesar(lokasi_tujuan)) as lokasi_tujuan,
									alamat_penerima, 
									delivery_name, 
									delivery_date, 

									CASE 
										WHEN airwaybill_kode LIKE '001-%' THEN 0
										WHEN airwaybill_kode LIKE '002-%' THEN 1
									end as intercity,

									SUBSTRING_INDEX(ukuran_barang,'x',1) as length, 
									SUBSTRING_INDEX(SUBSTRING_INDEX(ukuran_barang,'x',2),'x',-1) as width, 
									SUBSTRING_INDEX(ukuran_barang,'x',-1) as height, 

									if( (select intercity) = 1,
										ceiling((select length) *  (select width) * (select height)/4000)
									,
										if (a.service = 5,
											ceiling((select length) *  (select width) * (select height)/4000)
										,
											ceiling((select length) *  (select width) * (select height)/6000)
										)
									) as volume,

									if(berat_aktual is null or berat_aktual < 1,  berat_barang, berat_aktual) as berat_real, 

									case when packing_kayu='1' then 'Packing Oleh Pemilik' when packing_kayu='2' then 'Packing Kayu Oleh Cesco Log.' when packing_kayu='3' then 'Non Packing Kayu Oleh Cesco Log.' end as packing_kayu,
									if(id_kota_tujuan>0,ifnull(g.min_kg,h.min_kg),ifnull(b.min_kg,c.min_kg)) as min_kg, 
									if(id_kota_tujuan>0,ifnull(g.min_kg,h.min_kg),ifnull(b.min_kg,c.min_kg)) as min_kgg, 
									ifnull(b.next_kg,c.next_kg) as next_kg,
									if(id_kota_tujuan>0, ifnull(g.harga ,h.harga) ,ifnull(b.harga ,c.harga)) as harga_perkg,
									ifnull(b.min_kg_2,c.min_kg_2) as min_kgg_2,
									ifnull(b.harga_2,c.harga_2) as harga_perkgg_2,
									harga_pengiriman,
									

									if ((select berat_real) >= (select volume), (select berat_real), (select volume) ) as berat,

									if ((select min_kgg) >= (select berat), (select min_kgg), (select berat) ) as berat_finish,

									((select harga_perkg) * (select berat_finish))+a.biaya_tambahan as total,
									
									ifnull(  harga_pengiriman ,  (select total) ) as harga_total, 

									((select harga_perkg) * (select berat_finish))+ a.biaya_packing+a.biaya_tambahan+a.biaya_asuransi as total_new,

									ifnull(  harga_pengiriman ,  (select total_new) ) as total_fix, 

									
									
									if(if(berat_aktual is null or berat_aktual < 1,  berat_barang, berat_aktual) < ifnull(b.min_kg,c.min_kg), (ifnull(b.min_kg,c.min_kg)*ifnull(b.harga,c.harga))+a.biaya_packing+a.biaya_tambahan+a.biaya_asuransi , (if(berat_aktual is null or berat_aktual < 1,  berat_barang, berat_aktual)*ifnull(b.harga,c.harga))+a.biaya_packing+a.biaya_tambahan+a.biaya_asuransi ) as jumlah_harga,
										
									if(if(berat_aktual is null or berat_aktual < 1,  berat_barang, berat_aktual) < ifnull(d.min_kg,e.min_kg), (ifnull(d.min_kg,e.min_kg)*ifnull(d.price,e.price)) , (if(berat_aktual is null or berat_aktual < 1,  berat_barang, berat_aktual)*ifnull(d.price,e.price))) as total_agent, 
                                	ifnull(d.price,e.price) as price_agent, 
                                	ifnull(d.min_kg,e.min_kg) as min_kg_agent,
                                	a.perusahaan_pengirim,
                                	a.perusahaan_penerima,
									if(a.packing_kayu=2,if(a.biaya_packing < 1,if(a.berat_aktual is null or a.berat_aktual < 1,  a.berat_barang, a.berat_aktual)*2500,a.biaya_packing),0 ) as packing,
									if(a.asuransi = 'Y', if(a.nilai_barang >= 6000000,150000,0.25/100*a.nilai_barang) ,0) as nilai_asuransi,
									a.biaya_asuransi,
									a.biaya_tambahan,
									a.biaya_packing,
									a.costing_pickup_fee, 
									a.costing_pickup_parking, 
									a.costing_pickup_toll ,
									a.costing_men_power ,
									a.costing_delivery_fee, 
									a.costing_delivery_parking, 
									a.costing_delivery_toll ,
									a.costing_delivery_menpower,
									a.costing_outbound_fee, 
									a.costing_outbound_toll ,
									a.costing_outbound_smu ,
									a.costing_outbound_handling ,
									a.costing_outbound_parking, 									
									a.costing_outbound_menpower,
									a.costing_inbound_fee, 
									a.costing_inbound_toll ,
									a.costing_inbound_smu ,
									a.costing_inbound_handling ,
									a.costing_inbound_parking, 									
									a.costing_inbound_menpower,
									a.pid,
									ifnull(b.id,c.id) as id_rate_customer
								FROM pengiriman a 								
								left join rate_customer b ON b.id_kustomer=a.id_kustomer and b.id_lokasi_asal=a.lokasi_asal and b.id_lokasi_tujuan=a.lokasi_tujuan and b.id_pelayanan=a.service and b.komoditi=a.komoditi and b.loc_code=a.loc_code	
								left join rate_customer c ON c.id_kustomer=a.id_kustomer and c.id_lokasi_asal=a.lokasi_asal and c.id_lokasi_tujuan=a.lokasi_tujuan and c.id_pelayanan=a.service and c.komoditi=a.komoditi
								
								LEFT JOIN rate_agent d ON d.id_cabang=39 AND d.id_kota=a.lokasi_tujuan AND d.id_pelayanan=CASE WHEN a.service > 3 THEN 3 else a.service end AND d.komoditi=a.komoditi 
							
								LEFT JOIN rate_agent_inbound e ON e.id_cabang=a.id_cabang  and e.id_pelayanan=CASE WHEN a.service > 3 THEN 3 else a.service end and e.komoditi=a.komoditi
								
								LEFT JOIN negara f ON f.id_negara=a.lokasi_tujuan and a.service=6

								left join rate_customer g ON g.id_kustomer=a.id_kustomer and g.id_lokasi_asal=a.id_kota_asal and g.id_lokasi_tujuan=a.id_kota_tujuan and g.id_pelayanan=a.service and g.komoditi=a.komoditi and g.loc_code=a.loc_code

								left join rate_customer h ON h.id_kustomer=a.id_kustomer and h.id_lokasi_asal=a.id_kota_asal and h.id_lokasi_tujuan=a.id_kota_tujuan and h.id_pelayanan=a.service and h.komoditi=a.komoditi

								where a.id_invoice = $id_inv and a.status != 7
								group by a.id_pengiriman";
			$sum_total=0;						
			$sql = mysqli_query($con,$cari);
			while($t=mysqli_fetch_assoc($sql)){
				$sum_total = $sum_total+$t['total_fix'];
				//$volume = ($t['length']*$t['width']*$t['height'])/6000;
							if($t['lokasi_asal']=='Jakarta Selatan' || $t['lokasi_asal']=='Jakarta Timur' || $t['lokasi_asal']=='Jakarta Barat' ||		$t['lokasi_asal']=='Jakarta Utara' || $t['lokasi_asal']=='Jakarta Pusat'){
								$lokasi_asal = 'Jakarta';
							}else{
								$lokasi_asal = $t['lokasi_asal'];
							}
			
							if($t['lokasi_tujuan']=='Jakarta Selatan' || $t['lokasi_tujuan']=='Jakarta Timur' || $t['lokasi_tujuan']=='Jakarta Barat' ||		$t['lokasi_tujuan']=='Jakarta Utara' || $t['lokasi_tujuan']=='Jakarta Pusat'){
								$lokasi_tujuan = 'Jakarta';
							}else{
								$lokasi_tujuan = $t['lokasi_tujuan'];
							}
				echo'
				<tr>
					<td style="font-family:Calibri;text-align:center;font-size:14px;font-color:#000000;border-color:#000;border:1px solid;">'.$no.'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['tanggal'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['airwaybill_kode'].'</td>
					';

				
					
					//$number = 0.5;
					//$number = $number-1;*/
						$cek = mysqli_num_rows($sqll);
						if ($r['id_kustomer'] == 106){

							$sqll = mysqli_query($con, "select id_pengiriman,product,aprc,part_n,part_n_desc,rma_no,qty,remark from asus_detail_catatan  where id_pengiriman='".$t['id_pengiriman']."'");
						
								echo '<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'; 

								while ($row = mysqli_fetch_assoc($sqll)) {
									echo $row['aprc'].' ';
								}

								echo '</td>';
						
						}
				

					echo '
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.($t['jenis_barang'] == "" ? $t[komoditi] : $t[komoditi]).' '.  strtoupper($t['jenis_barang']).'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['service'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['namakustomer'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['loc_code']. ($t['pid'] != "" ? " , PID = ".$t[pid] : "" ).'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['nama_pengirim'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['perusahaan_pengirim'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.($t['id_kecamatan_asal']>0 ? strtoupper($t['kec_asal']).', ' : '').strtoupper($lokasi_asal).'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['alamat_pengirim'].'</td>					
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['perusahaan_penerima'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['nama_penerima'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.($t['id_kecamatan_tujuan']>0 ? strtoupper($t['kec_tujuan']).', ' : '').strtoupper($lokasi_tujuan).'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['alamat_penerima'].'</td>					
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['delivery_name'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['delivery_date'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['length'].'x'.$t['width'].'x'.$t['height'].'= '.round($t['volume']).'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['berat'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.($t['harga_pengiriman'] == '' ? number_format($t['harga_perkg']) : '-' ).'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.($t['harga_pengiriman'] == '' ? number_format($t['min_kgg']) : '-' ).'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.number_format($t['biaya_tambahan']).'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.number_format($t['harga_total']).'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.number_format($t['biaya_packing']).'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.number_format($t['biaya_asuransi']).'</td>					
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.number_format($t['total_fix']).'</td>
				</tr>				
				';
					/*$sqll = mysqli_query($con, "select id_pengiriman,product,aprc,part_n,part_n_desc,rma_no,qty,remark from asus_detail_catatan  where id_pengiriman='".$t['id_pengiriman']."'");
					$number = 0.5;
					//$number = $number-1;*/
						$cek = mysqli_num_rows($sqll);
						/*if ($cek > 0){
								echo '
								<tr>  
								<td> &nbsp; </td>
								</tr>								
								<tr style="font-size:14px;">
							        <th ></th>
							       <th >NO</th>
							        <th>PRODUCT</th>
							        <th>APRC</th>
							        <th>PART N</th>
							        <th>PART DESC</th>
							        <th>RMA NO</th>
							        <th>QTY</th>
							      </tr>';
							
							      while ($row = mysqli_fetch_assoc($sqll)) {
							      	
								    echo '  
								    <tr style="font-size:14px;>
								      <td></td>
								     	<td>'.$number.'</td> 
								        <td>'.$row['product'].'</td> 
								        <td>'.$row['aprc'].'</td>
								        <td>'.$row['part_n'].'</td>
								        <td>'.$row['part_n_desc'].'</td>
								        <td>'.$row['rma_no'].'</td>
								        <td>'.$row['qty'].'</td>
								    </tr> 
								      ';
								     $number=$number+1-0.5;

							      }
							      echo '<tr>  
								<td> &nbsp;</td>
								</tr>';
								
						}*/
					echo '				
				';
			$no++;
			}
			?>
			
			<tr>
			  <td colspan="24">&nbsp;</td>
			  <td colspan="1"><b> Rp <?php echo number_format($sum_total);  ?> </b></td>
			 
		  </tr>
		</table>
	
	</body>
</html>