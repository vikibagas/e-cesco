<?php
set_time_limit(0);

include ('../../config/connection.php');
include ('../../config/encrypt.php');
include ('../../config/library.php');
include ('../phpqrcode/qrlib.php');




$id_inv = $_GET['idinvoice'];
$stat = (isset($_GET['stat'])) ? $_GET['stat'] : '';



$query 		= mysqli_query($con,"select a.*, case when a.status='draft' then 'DRAFT' else a.nomor end as nomor, b.kode_kustomer, b.nama_kustomer, b.alamat, b.kota, c.nama_kota, x_namakota(x_hub(b.kota)) as kota, b.telp from invoice a left join kustomer b on b.id_kustomer=a.id_kustomer left join kota c on c.id_kota=b.kota where id_invoice='$id_inv'");
			$r 			= mysqli_fetch_assoc($query);

$qtotal = mysqli_query($con, "SELECT sum(if(b.next_kg is not null or c.next_kg is not null, if(if(berat_aktual is null or berat_aktual < 1, berat_barang, berat_aktual) <= ifnull(b.min_kg,c.min_kg), (ifnull(b.min_kg,c.min_kg)*ifnull(b.harga,c.harga))+a.biaya_packing+a.biaya_tambahan+a.biaya_asuransi , ifnull(b.harga,c.harga)*ifnull(b.min_kg,c.min_kg)+((if(berat_aktual is null or berat_aktual < 1, berat_barang, berat_aktual)-ifnull(b.min_kg,c.min_kg))*ifnull(b.next_kg,c.next_kg)) ), if(if(berat_aktual is null or berat_aktual < 1, berat_barang, berat_aktual) < ifnull(b.min_kg,c.min_kg), (ifnull(b.min_kg,c.min_kg)*ifnull(b.harga,c.harga))+a.biaya_packing+a.biaya_tambahan+a.biaya_asuransi , (if(berat_aktual is null or berat_aktual < 1, berat_barang, berat_aktual)*ifnull(b.harga,c.harga))+a.biaya_packing+a.biaya_tambahan+a.biaya_asuransi ) )) as subtotal
										FROM pengiriman a
								left join rate_customer b ON b.id_kustomer=a.id_kustomer and b.id_lokasi_asal=a.lokasi_asal and b.id_lokasi_tujuan=a.lokasi_tujuan and b.id_pelayanan=a.service and b.komoditi=a.komoditi and b.loc_code=a.loc_code
								left join rate_customer c ON c.id_kustomer=a.id_kustomer and c.id_lokasi_asal=a.lokasi_asal and c.id_lokasi_tujuan=a.lokasi_tujuan and c.id_pelayanan=a.service and c.komoditi=a.komoditi
								where a.id_invoice = $id_inv and a.status != 7 ");
						$st = mysqli_fetch_assoc($qtotal);
header("Content-type: application/vnd-ms-excel");
header('Content-Disposition: attachment; filename="'.$r['nama_kustomer'].$r['dari'].'-'.$r['sampai'].'_'.$id_inv.'.xls"');
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
			  <td colspan="8" style=min-width:50px><span style="font-family:Calibri;font-size:14px;color:#000000;"><?php echo''.($r['dari'] == '0000-00-00' ? '' : $r['dari']).' - '.($r['sampai'] == '0000-00-00' ? '' : $r['sampai']);?></span></td>
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
                                <td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid; width: 125px;">Product (DO Number)</td>
                            <?php
						}elseif ($r['id_kustomer'] == 13){
							?>
								<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid; width: 125px;">Tipe EDC</td>
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
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">KG </td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Volume </td>
				
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Chargeable weight</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Harga/kg</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Harga next kg</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">min kg</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Tambahan</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Keterangan</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Harga Pengiriman</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Packing</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Asuransi</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Total</td>

				<?php
				if ($stat == 'costing'){
				?>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Pickup Fee</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Pickup Toll</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Pickup Parking</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Pickup Menpower</td>
				
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Delivery Fee</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Delivery Toll</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Delivery Parking</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Delivery Menpower</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Outbound Fee</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Outbound SMU</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Outbound Toll</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Outbound Handling</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Outbound Parking</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Outbound Men Power</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Inbound Fee</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Inbound SMU</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Inbound Toll</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Inbound Handling</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Inbound Parking</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Inbound Menpower</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Invoice Agen PU</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Invoice Agen Transit</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Invoice Agen Deliv</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Total Invoice Agen</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Total Costing</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Total profit loss</td>
				<?php } ?>

			</tr>
			<?php
			$no=1;


			$cari = "select x.*, substring_index (substring_index(rate_customer,'~',1), ',', -1) as id_rate_customer,
						substring_index (substring_index(rate_customer,'~',3), '~', -1) as min_kg,
                        substring_index (substring_index(rate_customer,'~',3), '~', -1) as min_kgg,
                        substring_index (substring_index(rate_customer,'~',6), '~', -1) as next_kg,
                        substring_index (substring_index(rate_customer,'~',2), '~', -1) as harga_perkg,
                        substring_index (substring_index(rate_customer,'~',5), '~', -1) as min_kgg_2,
                        substring_index (substring_index(rate_customer,'~',4), '~', -1) as harga_perkgg_2,
                        if((select berat_real) >= (select volume),
                            (select berat_real),
                            (select volume)) as berat,
                        if((select min_kgg) >= (select berat),
                            (select min_kgg),
                            (select berat)) as berat_finish,
                        case when kode_kustomer = 'DHL' and (select next_kg) > 0 then
                          ((select harga_perkg) + ((select next_kg) * ((select berat_finish)-1))) + biaya_tambahan
                         else
                          ((select harga_perkg) * (select berat_finish)) + biaya_tambahan
                         end as total_new,
                        ifnull(harga_pengiriman, (select total_new)) as total_fix,
                        ifnull(harga_kunci, (select total_fix)) as harga_lock,
                        if(if(berat_aktual is null or berat_aktual < 1,
                                berat_barang,
                                berat_aktual) < (select min_kg),
                            ((select min_kg) * (select harga_perkg)) + biaya_packing + biaya_asuransi,
                            (if(berat_aktual is null or berat_aktual < 1,
                                berat_barang,
                                berat_aktual) * (select harga_perkg)) + biaya_packing + biaya_asuransi) as jumlah_harga
                        from (	SELECT
                        a.id_pengiriman,
                        date(pickup_date) as tanggal,
                        airwaybill_kode,
                        case
                            when a.komoditi = '1' then 'Paket'
                            else 'Dokumen'
                        end as komoditi,
                        a.jenis_barang,
                        a.catatan,
						pel.nama_pelayanan as service,
                        nama_pengirim,
                        a.loc_code,
						cs.nama_kustomer as namakustomer,
                        nama_penerima,
                        case
                            when inbound = 'Y' then 'INBOUND'
                            when inbound = 'N' then 'OUTBOUND'
                            else ''
                        end as inbound,
                        a.lokasi_asal as id_lokasi_asal,
                        if(a.id_kota_tujuan > 0,
                            a.kota_asal,
                            x_namakotabesar(lokasi_asal)) as lokasi_asal,
                        id_kecamatan_asal,
                        x_namakecamatan(id_kecamatan_asal) as kec_asal,
                        alamat_pengirim,
                        a.id_kecamatan_tujuan,
                        x_namakecamatan(a.id_kecamatan_tujuan) as kec_tujuan,
                        if(a.id_kota_tujuan > 0,
                            a.kota_tujuan,
                            x_namakotabesar(lokasi_tujuan)) as lokasi_tujuan,
                        alamat_penerima,
                        delivery_name,
                        delivery_date,
                        CASE
                            WHEN airwaybill_kode LIKE '001-%' THEN 0
                            WHEN airwaybill_kode LIKE '004-%' THEN 0
                            WHEN airwaybill_kode LIKE '002-%' THEN 1
                            WHEN airwaybill_kode LIKE '005-%' THEN 1
                        end as intercity,
                        SUBSTRING_INDEX(ukuran_barang, 'x', 1) as length,
                        SUBSTRING_INDEX(SUBSTRING_INDEX(ukuran_barang, 'x', 2),
                                'x',
                                - 1) as width,
                        SUBSTRING_INDEX(ukuran_barang, 'x', - 1) as height,
                        case when upper(cs.kode_kustomer) in ('CIS','DEL') then
                          if((select intercity) = 1,
                            ((select length) * (select width) * (select height) / 4000),
                            if(a.service = 5,
                                ((select length) * (select width) * (select height) / 4000),
                                ((select length) * (select width) * (select height) / 6000)))
                        else
                            if((select intercity) = 1,
                                ceiling((select length) * (select width) * (select height) / 4000),
                                if(a.service = 5,
                                    ceiling((select length) * (select width) * (select height) / 4000),
                                    ceiling((select length) * (select width) * (select height) / 6000)))
                        end as volume,
                        if(berat_aktual is null or berat_aktual < 1,
                            berat_barang,
                            berat_aktual) as berat_real,
                        case
                            when packing_kayu = '1' then 'Packing Oleh Pemilik'
                            when packing_kayu = '2' then 'Packing Kayu Oleh Cesco Log.'
                            when packing_kayu = '3' then 'Non Packing Kayu Oleh Cesco Log.'
                        end as packing_kayu,
						a.perusahaan_pengirim,
                        a.perusahaan_penerima,
                        a.asuransi,
						a.berat_aktual,
						a.berat_barang,
						if(a.asuransi = 'Y',
                            if(a.nilai_barang >= 6000000,
                                150000,
                                0.25 / 100 * a.nilai_barang),
                            0) as nilai_asuransi,
                        a.biaya_packing,
						if(a.packing_kayu = 2,
                            if(a.biaya_packing < 1,
                                if(a.berat_aktual is null
                                        or a.berat_aktual < 1,
                                    a.berat_barang,
                                    a.berat_aktual) * 2500,
                                a.biaya_packing),
                            0) as packing,
                        a.biaya_asuransi,
                        a.biaya_tambahan,
						a.harga_pengiriman,
                        a.harga_kunci,
                        a.costing_pickup_fee,
                        a.costing_pickup_parking,
                        a.costing_pickup_toll,
                        a.costing_men_power,
                        a.costing_delivery_fee,
                        a.costing_delivery_parking,
                        a.costing_delivery_toll,
                        a.costing_delivery_menpower,
                        a.costing_outbound_fee,
                        a.costing_outbound_toll,
                        a.costing_outbound_smu,
                        a.costing_outbound_handling,
                        a.costing_outbound_parking,
                        a.costing_outbound_menpower,
                        a.costing_inbound_fee,
                        a.costing_inbound_toll,
                        a.costing_inbound_smu,
                        a.costing_inbound_handling,
                        a.costing_inbound_parking,
                        a.costing_inbound_menpower,
                        a.catatan1,
                        a.pid,
                        a.keterangan_biaya,
						a.pickup_by_agent, a.delivery_by_agent,
						ifnull(a.biaya_packing_agent,0) as biaya_packing_agent,
						ifnull(a.biaya_tambahan_agent,0) as biaya_tambahan_agent,
						ifnull(md.biaya_tambahan_agent_transit,0) as biaya_tambahan_agent_transit,
                        (ifnull(a.kunci_total_agent,0) + ifnull(a.kunci_total_agent_deliv,0) + ifnull(a.kunci_total_agent_pickup,0) + ifnull(md.kunci_total_agent_transit,0) ) as total_agent,
						ifnull(a.kunci_total_agent,0) as kunci_total_agent,
						ifnull(a.kunci_total_agent_deliv,0) as kunci_total_agent_deliv,
						ifnull(a.kunci_total_agent_pickup,0) as kunci_total_agent_pickup,
						ifnull(md.kunci_total_agent_transit,0) as kunci_total_agent_transit,
						cs.kode_kustomer, kunci_berat_agent,
						x_inter(id_kota_asal) as inter_asal, x_inter(id_kota_tujuan) as inter_tujuan,
						case when terlambat_pickup in (1,2) and terlambat_delivery in (1,2) then
								ifnull(a.diskon_pickup,0)+ifnull(a.diskon_deliv,0)
							when terlambat_pickup in (1,2) and terlambat_delivery not in (1,2) then
								ifnull(a.diskon_pickup,0)
							when terlambat_pickup not in (1,2) and terlambat_delivery in (1,2) then
								ifnull(a.diskon_deliv,0)
						else 0
						end as diskon,
						getCabangCesco(a.id_pengiriman) as jumlah_cabang,
						getAgentCesco(a.id_pengiriman) as jumlah_agent,
						getRateCustomer2(a.id_kustomer, a.id_kecamatan_asal, a.lokasi_asal, a.lokasi_tujuan, a.id_kota_asal, a.id_kota_tujuan, a.id_kecamatan_tujuan, a.service, a.komoditi, a.loc_code) as rate_customer,
						(select count(1) from invoice_send
							where invoice_type='AGENT' and agent_date = date_format(ifnull(a.tanggal_agent,a.tanggal),'%Y-%m') and agent_id = a.pickup_by_agent
							and confirm_dtm is not null
						) as confirm_agent_pu,
						(select count(1) from invoice_send
							where invoice_type='AGENT' and agent_date = date_format(ifnull(a.tanggal_agent,a.tanggal),'%Y-%m') and agent_id = a.delivery_by_agent
							and confirm_dtm is not null
						) as confirm_agent_deliv,
						(select count(1) from invoice_send i
							join manifest m on m.manifest_from = i.agent_id
							where invoice_type='AGENT' and agent_date = date_format(a.tanggal,'%Y-%m')
							and m.id_manifest = md.id_manifest
							and confirm_dtm is not null
						) as confirm_agent_transit,
						(select count(1) from invoice_send
							where invoice_type='AGENT' and agent_date = date_format(a.tanggal,'%Y-%m') and agent_id = a.delivery_by_agent
							and agent_id = a.pickup_by_agent
							and confirm_dtm is not null
						) as confirm_agent_intra,
						case
				when a.service = '6' then x_namanegara(a.lokasi_tujuan)
				else ''
				end as negara,
				a.service as service_id
                    FROM
                        pengiriman a
						left join manifest_detail md on md.id_pengiriman = a.id_pengiriman and md.kunci_total_agent_transit is not null
						left join kustomer cs on cs.id_kustomer = a.id_kustomer
						left join pelayanan pel on pel.id_pelayanan = a.service
                            LEFT JOIN
                        negara f ON f.id_negara = a.lokasi_tujuan
                            and a.service = 6
                    where
                        a.id_invoice = $id_inv and a.status != 7 
                    group by a.id_pengiriman
                  ) x";

                  //echo $cari;

			$sum_total=0;
			$sql = mysqli_query($con,$cari);
			while($t=mysqli_fetch_assoc($sql)){

				$cek002 = 0;
				$cekOut = 0;
				$cekIn = 0;
				$cekCross = 0;

				$cekCostOut = "";
				$cekCostIn = "";
				$cekCostDeliv = "";
				$cekCostPick = "";
				$cekCostAgent = "";

				$row = $t;

				$btnAlert = " **";

				$totalAgent = 0;
				$totalAgenPickup = ($row['confirm_agent_pu'] == 0) ? 0 : $row['kunci_total_agent_pickup'];
				$totalAgentDeliv = ($row['confirm_agent_deliv'] == 0) ? 0 : $row['kunci_total_agent_deliv'];
				$totalAgentTransit = ($row['confirm_agent_transit'] == 0) ? 0 : $row['kunci_total_agent_transit'];
				$totalAgentIntra = ($row['confirm_agent_intra'] == 0) ? 0 : $row['kunci_total_agent'];

				$total_costing = $row['costing_pickup_fee']+$row['costing_pickup_parking']+$row['costing_pickup_toll']+$row['costing_men_power']+$row['costing_delivery_fee']+$row['costing_delivery_parking']+$row['costing_delivery_toll']+$row['costing_delivery_menpower']+

				$row['costing_outbound_fee']+$row['costing_outbound_toll']+$row['costing_outbound_handling']+$row['costing_outbound_handling']

				+$row['costing_outbound_parking']+$row['costing_outbound_menpower']+$row['costing_outbound_smu']

				+$row['costing_inbound_fee']+$row['costing_inbound_smu']+$row['costing_inbound_toll']+$row['costing_inbound_handling']+$row['costing_inbound_parking']+$row['costing_inbound_menpower']+$row['total_agent'];

				if ($row['intercity'] == 0){ //001

					if ($row['pickup_by_agent'] == $row['delivery_by_agent']){

						$totalAgent = $totalAgentIntra;
						$totalAgentDeliv = $totalAgentIntra;

					}else{

						if ($row['inter_asal'] != 'Y' && $row['inter_tujuan'] != 'Y'){ //CROSS CITY

							$totalAgent = $totalAgenPickup + $totalAgentDeliv + $totalAgentTransit;

							if ($row['jumlah_cabang'] > 0 && $row['jumlah_agent'] == 0){

								/*if ($totalAgentAfterDiskon == 0){
									$cekCross++;
									$cekCostAgent = $btnAlert;
								}*/

								if ($row['costing_delivery_fee'] == 0){
									$cekCross++;
									$cekCostDeliv = $btnAlert;
								}

							}else{
								if ($totalAgent == 0){
									$cekCross++;
									$cekCostAgent = $btnAlert;
								}
							}


						}else if ($row['inter_asal'] != 'Y' && $row['inter_tujuan'] == 'Y'){	//INBOUND

							$totalAgent = $totalAgenPickup + $totalAgentTransit;

							/*
							if ($row['costing_delivery_fee'] == 0) {
								$cekIn++;
								$cekCostDeliv = $btnAlert;
							}*/
							if ($row['costing_inbound_fee'] == 0) {
								$cekIn++;
								$cekCostIn = $btnAlert;
							}
							/*
							if ($totalAgentAfterDiskon == 0) {
								$cekIn++;
								$cekCostAgent = $btnAlert;
							}*/


							if ($row['jumlah_cabang'] > 0 && $row['jumlah_agent'] == 0){
								if ($row['costing_delivery_fee'] == 0) {
									$cekIn++;
									$cekCostDeliv = $btnAlert;
								}

							}else{
								if ($totalAgent == 0){
									$cekIn++;
									$cekCostAgent = $btnAlert;
								}
							}


						}else if ($row['inter_asal'] == 'Y' && $row['inter_tujuan'] != 'Y'){ //OUTBOUND

							$totalAgent = $totalAgentDeliv + $totalAgentTransit;

							if ($row['costing_pickup_fee'] == 0){
								$cekOut++;
								$cekCostPick = $btnAlert;
							}
							if ($row['costing_outbound_fee'] == 0){
								$cekOut++;
								$cekCostOut = $btnAlert;
							}

							if ($row['jumlah_cabang'] > 0 && $row['jumlah_agent'] == 0){
								if ($row['costing_delivery_fee'] == 0) {
									$cekOut++;
									$cekCostDeliv = $btnAlert;
								}

							}else{
								if ($totalAgent == 0){
									$cekOut++;
									$cekCostAgent = $btnAlert;
								}
							}
						}
					}

				}else{ //002

					$totalAgent = $totalAgenPickup + $totalAgentDeliv + $totalAgentTransit;

					if ($row['costing_pickup_fee'] == 0){
						$cek002++;
						$cekCostPick = $btnAlert;
					}
					if ($row['costing_delivery_fee'] == 0){
						$cek002++;
						$cekCostDeliv = $btnAlert;
					}
				}

				if ($row['confirm_agent_pu'] == 0 && $row['confirm_agent_deliv'] == 0 && $row['confirm_agent_transit'] == 0 && $row['confirm_agent_intra'] == 0){
					$new_total_diskon = 0;
					$total_agent = 0;
					$diskon = 0;
					$totalAgentAfterDiskon = 0;

				}else{

					$new_total_diskon = $totalAgent - ($row['biaya_packing_agent']+$row['biaya_tambahan_agent']+$row['biaya_tambahan_agent_transit']);
					$total_agent = $new_total_diskon;
					$diskon = $row['diskon'];
					$totalAgentAfterDiskon = ($total_agent-(($diskon/100)*$total_agent))+($row['biaya_packing_agent']+$row['biaya_tambahan_agent']+$row['biaya_tambahan_agent_transit']);
					//$sum_total = $sum_total + $totalAgentAfterDiskon;
				}
				$total_harga_awb = $t['harga_lock']+$row['biaya_packing']+$row['biaya_asuransi'];
				$profit_loss = $total_harga_awb-$total_costing;
				
				if ($cek002 > 0 || $cekOut > 0 || $cekIn > 0 || $cekCross > 0 || $stat == '' || $profit_loss < 0){
					//$volume = ($t['length']*$t['width']*$t['height'])/6000;
								if($t['lokasi_asal']=='Jakarta Selatan' || $t['lokasi_asal']=='Jakarta Timur' || $t['lokasi_asal']=='Jakarta Barat' ||		$t['lokasi_asal']=='Jakarta Utara' || $t['lokasi_asal']=='Jakarta Pusat'){
									$lokasi_asal = 'Jakarta';
								}else{
									$lokasi_asal = $t['lokasi_asal'];
								}

								if($t['lokasi_tujuan']=='Jakarta Selatan' || $t['lokasi_tujuan']=='Jakarta Timur' || $t['lokasi_tujuan']=='Jakarta Barat' ||		$t['lokasi_tujuan']=='Jakarta Utara' || $t['lokasi_tujuan']=='Jakarta Pusat'){
									$lokasi_tujuan = 'Jakarta';
								}else{
									if ($t['service_id'] == 6) $lokasi_tujuan = $t['negara'];
									else $lokasi_tujuan = $t['lokasi_tujuan'];
								}

					$beratDitagih = (round($t['volume']) > $t['berat']) ? round($t['volume']) : $t['berat'];

					//$volume = ($t['kode_kustomer'] == 'CIS' || $t['kode_kustomer'] == 'DEL') ? round($beratDitagih,2) : ceil($beratDitagih);
					$volume = ($t['kode_kustomer'] == 'CIS' || $t['kode_kustomer'] == 'DEL') ? round($t['volume'],2) : ceil($t['volume']);
					$harga_lock = ($t['kode_kustomer'] == 'CIS' || $t['kode_kustomer'] == 'DEL') ? round($t['harga_lock'],2) : round($t['harga_lock']);
					$sum_total = $sum_total+$total_harga_awb;

					

					echo'
					<tr>
						<td style="font-family:Calibri;text-align:center;font-size:14px;font-color:#000000;border-color:#000;border:1px solid;">'.$no.'</td>
						<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['tanggal'].'</td>
						<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['airwaybill_kode'].'</td>
						';



						//$number = 0.5;
						//$number = $number-1;*/
							//$cek = mysqli_num_rows($sqll);
							if ($r['id_kustomer'] == 106){
                                $produk = '';

								$sqll = mysqli_query($con, "select id_pengiriman,product,aprc,part_n,part_n_desc,rma_no,qty,remark from asus_detail_catatan  where id_pengiriman='".$t['id_pengiriman']."'");

									echo '<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">';
									$cekAprc = 0;
									while ($row = mysqli_fetch_assoc($sqll)) {
										$cekAprc++;
										echo ' '.$row['aprc'].' ';
                                        $produk .= $row['product'].' ';
									}

									$catatan = strtolower($t['catatan1']);
									if ($cekAprc == 0 && (strpos($catatan, 'aprc-') !== false || strpos($catatan, 'sbt-') !== false || strpos($catatan, 'oplk-') !== false || strpos($catatan, 'abcd-') !== false || strpos($catatan, 'asuj-') !== false || strpos($catatan, 'sfw-') !== false || strpos($catatan, 'do') !== false)){
										echo $t['catatan1'];
									}

									echo '</td>';
                                    echo '<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">';
									echo $produk;
                                    echo '</td>';

							}elseif ($r['id_kustomer'] == 13){

								echo '<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">';

								$catatan = strtolower($t['catatan1']);
								if (strpos($catatan, 'dg') !== false || strpos($catatan, 'edc') !== false){
									echo $t['catatan1'];
								}

								echo '</td>';

							}


						echo '
							<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.($t['jenis_barang'] == "" ? $t['komoditi'] : $t['komoditi']).', '.  strtoupper($t['jenis_barang']).', '.  strtoupper($t['catatan']).'</td>
							<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['service'].'</td>
							<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['namakustomer'].'</td>
							<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['loc_code']. ($t['pid'] != "" ? " , PID = ".$t['pid'] : "" ).'</td>
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
							

							';

								 $j=0;
								 $tb = 0;
								 
								 $q = mysqli_query($con, "select * from detail_collie where id_pengiriman='".$t['id_pengiriman']."' order by koli_ke asc ");
								 $ketemu = mysqli_num_rows($q);

								 if($ketemu > 0){
								 	echo '<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">';
								 	while($roww = mysqli_fetch_assoc($q)){
			 //for($i=1;$i<41;$i++){

									 	if($t['intercity'] == 1){
						              			$beratvol = $roww['p']*$roww['l']*$roww['t']/4000;
						              	}
						             	else{
						           			if($t['service_id'] == 5){
						           				$beratvol = $roww['p']*$roww['l']*$roww['t']/4000;
						           			}
						              		else{
						              			$beratvol = $roww['p']*$roww['l']*$roww['t']/6000;
						              		}
						              	}

									 	echo '
									 		
											 	
											 	'.$roww['berat'].'  <br>
											 	
											 
									 	';
									 	$j=$j+1;
									 	//$tb=$tb+$roww['berat_real'];
									 }
									 echo '</td>';
									 $qu = mysqli_query($con, "select * from detail_collie where id_pengiriman='".$t['id_pengiriman']."' order by koli_ke asc ");

									 echo '<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">';
										 while($roww = mysqli_fetch_assoc($qu)){
				 //for($i=1;$i<41;$i++){

										 	if($t['intercity'] == 1){
							              			$beratvol = $roww['p']*$roww['l']*$roww['t']/4000;
							              			
							              	}
							             	else{
							           			if($t['service_id'] == 5){
							           				$beratvol = $roww['p']*$roww['l']*$roww['t']/4000;

							           			}
							              		else{
							              			$beratvol = $roww['p']*$roww['l']*$roww['t']/6000;

							              		}
							              	}

										 	echo '								 										
												 	 '.$roww['p'].' x '.$roww['l'].' x '.$roww['t'].' ( '.number_format($beratvol,2).' )'.'<br>
												 	
												 
										 	';
										 	$j=$j+1;
										 	//$tb=$tb+$roww['berat_real'];
										 }
									echo '</td>';
									
								}else{
									echo '<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">';
										
										echo $t['berat_real'];
									echo '</td>';

									echo '<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">';

										echo ($t['length'] == '' ? 0 : $t['length']).'x'.($t['width'] == '' ? 0 : $t['width']).'x'.($t['height'] == '' ? 0 : $t['height']).'('. number_format($t['volume'],2).'  )';
									echo '</td>';
									}

								echo 
								'

							
							
							<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.ceil($t['berat_finish']).'</td>
							<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.($t['harga_pengiriman'] == '' ? $t['harga_perkg'] : '-' ).'</td>
							<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['next_kg'].'</td>
							<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.($t['harga_pengiriman'] == '' ? $t['min_kgg'] : '-' ).'</td>
							<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['biaya_tambahan'].'</td>
							<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['keterangan_biaya'].'</td>
							<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$harga_lock.'</td>
							<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['biaya_packing'].'</td>
							<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['biaya_asuransi'].'</td>
							<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.round($t['total_fix']).'</td>';

						if ($stat == 'costing'){

							echo '<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.round($t['costing_pickup_fee']).$cekCostPick.'</td>
							<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.round($t['costing_pickup_toll']).'</td>
							<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.round($t['costing_pickup_parking']).'</td>
							<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.round($t['costing_men_power']).'</td>
								<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.round($t['costing_delivery_fee']).$cekCostDeliv.'</td>
								<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.round($t['costing_delivery_toll']).'</td>
								<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.round($t['costing_delivery_parking']).'</td>
								<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.round($t['costing_delivery_menpower']).'</td>
								<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.round($t['costing_outbound_fee']).$cekCostOut.'</td>
								<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.round($t['costing_outbound_smu']).'</td>
								<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.round($t['costing_outbound_toll']).'</td>
								<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.round($t['costing_outbound_handling']).'</td>
								<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.round($t['costing_outbound_parking']).'</td>
								<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.round($t['costing_outbound_menpower']).'</td>


								<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.round($t['costing_inbound_fee']).$cekCostIn.'</td>
								<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.round($t['costing_inbound_smu']).'</td>
								<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.round($t['costing_inbound_toll']).'</td>
								<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.round($t['costing_inbound_handling']).'</td>
								<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.round($t['costing_inbound_parking']).'</td>
								<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.round($t['costing_inbound_menpower']).'</td>

								<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.round($totalAgenPickup).'</td>
									<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.round($totalAgentTransit).'</td>
									<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.round($totalAgentDeliv).'</td>
									<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.round($totalAgentAfterDiskon).$cekCostAgent.'</td>
								<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.round($total_costing).'</td>
								<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.number_format(round($profit_loss)).($profit_loss < 0 ? '**' : '').'</td>
							</tr>
							';

						}

						echo '
					';
					$no++;
				}
			}
			?>

			<tr>
			  <td colspan="30">&nbsp;</td>
			  <td colspan="1"><b> <?php echo $sum_total;  ?> </b></td>

		  </tr>
		</table>

	</body>
</html>
