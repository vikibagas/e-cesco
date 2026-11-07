<?php
if(!isset($_SESSION)){
    session_start();
}

if (isset($_SESSION['username']) || isset($_SESSION['usernamex'])){
	


include ('../../config/connection.php');
include ('../../config/encrypt.php');
include ('../../config/library.php');
include ('../reports/phpqrcode/qrlib.php');


?>
<html>
	<head>
		<meta charset="UTF-8">
			<title>Invoice</title>
			<link type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/themes/south-street/jquery-ui.css" rel="stylesheet">
			<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
			<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
			<link type="text/css" href="jquery.signature/jquery.signature.css" rel="stylesheet">
			<script type="text/javascript" src="jquery.signature/jquery.signature.js"></script>
			<script type="text/javascript">
				function loadSignature() {
					$('#redrawSignature').signature({
						disabled: true
					});
					$('#redrawSignature').signature('draw', $('#signatureJSON').val());
				}

			</script>
            <style>
		body {
			background: rgb(204, 204, 204);
			font-family: 'Calibri', sans-serif;
			font-size:10.5px;
			font-weight:bold;
		}

		page {
			background: white;
			display: block;
			margin: 0 auto;
			margin-bottom: 0.5cm;
			box-shadow: 0 0 0.5cm rgba(0, 0, 0, 0.5);
		}

		page[size="A4"] {
			width: 21cm;
			height: 29.7cm;
		}

		page[size="A4"][layout="portrait"] {
			width: 29.7cm;
			height: 21cm;
		}

		page[size="A3"] {
			width: 29.7cm;
			height: 42cm;
		}

		page[size="A3"][layout="portrait"] {
			width: 42cm;
			height: 29.7cm;
		}

		page[size="A5"] {
			width: 14.8cm;
			height: 21cm;
		}

		page[size="A5"][layout="portrait"] {
			width: 21cm;
			height: 14.8cm;
		}

		.subpage {
			padding: 1cm;
			height: 20cm;
			outline: 1cm;
		}

		@media print {
			body,
			page {
				margin: 0;
				box-shadow: 0;
			}
		}
		.kbw-signature {
			width: 100%;
			height: 160px;
			margin-top: -20px;
		}

	</style>
		</head>
<body>
			<page size="A4"">
		<div class="subpage">
		<?php

			$key		= "asdf$1011";
						$encrypt	= "$_GET[idinvoice]";
						$id_inv 		= base64_decrypt($encrypt,$key);



			 //$id_inv = $_GET['idinvoice'];
			 "select a.*, case when a.status='draft' then 'DRAFT' else a.nomor end as nomor, b.kode_kustomer, b.nama_kustomer, b.alamat, b.kota, c.nama_kota, x_namakota(x_hub(b.kota)) as kota, b.telp from invoice a left join kustomer b on b.id_kustomer=a.id_kustomer left join kota c on c.id_kota=b.kota where id_invoice='$id_inv'";
			$query 		= mysqli_query($con,"select a.*,d.*, case when a.status='draft' then 'DRAFT' else a.nomor end as nomor,ppn, b.kode_kustomer, b.nama_kustomer, b.alamat, b.kota,b.tagihan_ke, c.nama_kota, x_namakota(x_hub(b.kota)) as kota, b.telp,b.custom_invoice,a.tagih_ke,x_user(a.id_user) as nama_pembuat

				from invoice a left join kustomer b on b.id_kustomer=a.id_kustomer left join kota c on c.id_kota=b.kota inner join rekening_cesco d on a.bank=d.id where id_invoice='$id_inv'");
			$r 			= mysqli_fetch_assoc($query);
			$tanggal = $r['tanggal'];
			$ppn = $r['ppn'];
			$tgl_due_date = date('Y-m-d', strtotime('+45 days', strtotime($tanggal)));


		?>
		<table class="" cellspacing=0 width="100%" style="text-transform: uppercase; ">
			<tbody>
				<tr>
					<td colspan="2" rowspan="5" style="font-size:10px;min-width:50px">
						<img src='../../dist/img/logocesco2024.png' style="width:160px; margin-bottom: 5px;">
						<nobr>&nbsp;</nobr>
					<nobr>&nbsp;</nobr>
						<nobr>&nbsp;</nobr>
					<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:10px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:10px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:10px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:10px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:10px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:10px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:10px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:10px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
				</tr>
				<tr>
					<td style="font-size:10px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:10px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:10px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:10px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:10px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:10px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:10px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:10px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
				</tr>
				<tr>
					<td style="font-size:10px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:10px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:10px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:10px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:10px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:10px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:10px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:10px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
				</tr>
				<tr>
					<td style="font-size:10px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:10px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:10px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:10px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:10px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:10px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:10px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:10px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
				</tr>
				<tr >
					<td style="font-size:10px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:10px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:10px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:10px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:10px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:10px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:10px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:10px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
				</tr>
				<tr >
					<td style="font-size:12px;font-weight:bold;min-width:50px" colspan=4>
						<nobr>PT CESCO LOGISTIK INDONESIA</nobr>				    </td>
					<td style="font-size:10px;min-width:50px">
						<nobr> </nobr>				    </td>
					<td style="font-size:10px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:10px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:10px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:10px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:10px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
				</tr>
				<tr >
					<td style="font-size:10px;min-width:50px" colspan=4>
						<nobr>Cilandak Commercial Estate unit. 406 AW & AE</nobr><br>Jl. Cilandak KKO RT 001 RW 005 <br> Cilandak Timur Pasar Minggu <br>Jakarta Selatan. Kode Pos 12560  </td>
					<td style="font-size:10px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:10px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:10px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:10px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:10px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:10px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
				</tr>
				<tr >
					<td style="font-size:10px;min-width:50px" colspan=4>
						<nobr></nobr>				    </td>
					<td style="font-size:10px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:10px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>

					<td style="font-family:Calibri;text-align:center;font-size:20px;background-color:#000000;color:#FFFFFF;min-width:50px margin-top:;10px" colspan=4 rowspan=1>
						<nobr>I N V O I C E</nobr><hr><?php echo $r['nomor']; ?></td>
				</tr>
				<tr >
					<td style="font-size:10px;min-width:50px" colspan=4>
						<nobr></nobr>				    </td>
					<td style="font-size:10px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:10px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>

					<td style="text-align:center;font-size:12px;background-color:#fff;color:#000;min-width:50px; padding-top:5px;letter-spacing: 1px;" colspan=4 rowspan=1>
						<?php echo $r['keterangan_no_invoice']; ?></td>
				</tr>


				<tr >
					<td style="font-size:14px;font-weight:bold;min-width:50px" colspan="2">
						<nobr>To.</nobr>
						<?php
						if($r['custom_invoice'] == 'Y'){
							$custom_alamat = ($r['custom_judul_alamat'] != '' ? $r['custom_judul_alamat'] : 'custom');
							
							
						}else{
							$custom_alamat = $r['alamat'];
							
							
						}

						 "tagihan ke".$r['tagih_ke'];
						if ($r['tagih_ke'] != ''){
							$tagih_ke 		= mysqli_query($con,"select kode_kustomer, nama_kustomer, alamat, kota, x_namakota(x_hub(kota)) as nama_kota, telp from kustomer where id_kustomer='$r[tagih_ke]'");
							 "select kode_kustomer, nama_kustomer, alamat, kota, x_namakota(x_hub(kota)) as nama_kota, telp from kustomer where id_kustomer='$r[tagih_ke]'";
			$t 			= mysqli_fetch_assoc($tagih_ke);
							echo '
							<address>
								<strong>'.$t['nama_kustomer'].'</strong><br>
	            				'.$t['alamat'].'<br>
	            				
	          				</address> ';
						}
						else{
							echo '
							<address>
								<strong>'.($r['tampil_nama_kustomer'] == 'Y' ? $r['nama_kustomer'].'<br>' : '').'</strong>
	            				'.strtoupper($custom_alamat).'<br>
	            				
	          				</address> ';
	          			}
          				?>
					</td>
					<td style="font-size:10px;font-weight:bold;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:10px;font-weight:bold;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="text-align:right;font-size:12px;min-width:100px" colspan=5>
						<nobr> <?php QRcode::png("".$r['nomor']."","inv".$id_inv.".png","M",4,4); echo "<img src='inv".$id_inv.".png'>" ?> </nobr>				    </td>

				</tr>


				<tr >
					<td style="font-size:10px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:10px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:10px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:10px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:10px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:10px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:10px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:10px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:10px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:10px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
				</tr>
				<tr >
					<td style="text-align:center;font-size:12px;font-weight:bold;border-left:2px solid #000000;border-top:2px solid #000000;border-bottom:2px solid #000000;border-right:2px solid #000000;width:5px;">
						<nobr>No.</nobr>				    </td>
					<td style="text-align:center;font-size:12px;font-weight:bold;border-top:2px solid;border-bottom:2px solid;border-left-color:#000000;border-top-color:#000000;border-bottom-color:#000000;min-width:50px" colspan=5>
						<nobr>Description</nobr>				    </td>
					<td style="text-align:center;font-size:12px;font-weight:bold;border-left:2px solid;border-right:2px solid;border-top:2px solid;border-bottom:2px solid;border-left-color:#000000;border-right-color:#000000;border-top-color:#000000;border-bottom-color:#000000;min-width:50px" colspan=4>
						<nobr>Amount</nobr>				    </td>
				</tr>


				<tr >
					<td style="text-align:center;font-size:12px;border-left:2px solid;border-right:2px solid;border-left-color:#000000;border-right-color:#000000;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:10px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:10px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:10px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:10px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:10px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="text-align:center;font-size:10px;border-left:2px solid;border-left-color:#000000;border-right:2px solid;border-right-color:#000000;min-width:50px" colspan=4>
						<nobr>&nbsp;</nobr>				    </td>
				</tr>
			<?php

           

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
                            ((select min_kg) * (select harga_perkg)) + biaya_packing + biaya_tambahan + biaya_asuransi,
                            (if(berat_aktual is null or berat_aktual < 1,
                                berat_barang,
                                berat_aktual) * (select harga_perkg)) + biaya_packing + biaya_tambahan + biaya_asuransi) as jumlah_harga
                        from (	SELECT
                        id_pengiriman,
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
                        0 as total_agent, cs.kode_kustomer,
						getRateCustomer2(a.id_kustomer, a.id_kecamatan_asal, a.lokasi_asal, a.lokasi_tujuan, a.id_kota_asal, a.id_kota_tujuan, a.id_kecamatan_tujuan, a.service, a.komoditi, a.loc_code) as rate_customer
                    FROM
                        pengiriman a
						left join kustomer cs on cs.id_kustomer = a.id_kustomer
						left join pelayanan pel on pel.id_pelayanan = a.service
                            LEFT JOIN
                        negara f ON f.id_negara = a.lokasi_tujuan
                            and a.service = 6
                    where
                        a.id_invoice = $id_inv and a.status != 7
                    group by a.id_pengiriman
                  ) x";

						$sql = mysqli_query($con, $cari);


						$sum_total=0;
						$sum_packing =0;
						$sum_asuransi=0;
						while($row = mysqli_fetch_assoc($sql)){
							//$sum_total = $sum_total+$row['total_fix'];
							$harga_lock = ($row['kode_kustomer'] == 'CIS' || $row['kode_kustomer'] == 'DEL') ? round($row['harga_lock'],2) : round($row['harga_lock']);
                            $sum_total = $sum_total + $harga_lock+$row['biaya_packing']+$row['biaya_asuransi'];
							$sum_packing = $sum_packing + $row['biaya_packing'];
							$sum_asuransi = $sum_asuransi + $row['biaya_asuransi'];
						}

			
						$detail_biaya = strtoupper('Subtotal Periode '.$r['dari'].' - '.$r['sampai']);

			echo'
				<tr >
					<td style="text-align:center;font-size:12px;border-left:2px solid;border-right:2px solid;border-left-color:#000000;border-right-color:#000000;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:12px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:12px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:12px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:12px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:12px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="text-align:center;font-size:12px;border-left:2px solid;border-left-color:#000000;border-right:2px solid;border-right-color:#000000;min-width:50px" colspan=4>
						<nobr>&nbsp;</nobr>				    </td>
				</tr>
				<tr >
					<td style="text-align:center;font-size:12px;border-left:2px solid;border-right:2px solid;border-left-color:#000000;border-right-color:#000000;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>

					<td style="text-align:left;font-size:12px;min-width:50px; padding-left:12px;" colspan=5>
						<nobr></nobr>'.($r['judul_awal']).'
					</td>
					<td style="text-align:center;font-size:12px;border-left:2px solid;border-left-color:#000000;border-right:2px solid;border-right-color:#000000;min-width:50px" colspan=4>
						<nobr></nobr>&nbsp;</td>
				</tr>
				<tr >
					<td style="text-align:center;font-size:12px;border-left:2px solid;border-right:2px solid;border-left-color:#000000;border-right-color:#000000;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:12px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:12px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:12px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:12px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:12px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="text-align:center;font-size:12px;border-left:2px solid;border-left-color:#000000;border-right:2px solid;border-right-color:#000000;min-width:50px" colspan=4>
						<nobr>&nbsp;</nobr>				    </td>
				</tr>
				<tr >
					<td style="text-align:center;font-size:12px;border-left:2px solid;border-right:2px solid;border-left-color:#000000;border-right-color:#000000;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:12px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:12px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:12px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:12px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:12px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="text-align:center;font-size:12px;border-left:2px solid;border-left-color:#000000;border-right:2px solid;border-right-color:#000000;min-width:50px" colspan=4>
						<nobr>&nbsp;</nobr>				    </td>
				</tr>
				<tr >
					<td style="text-align:center;font-size:12px;border-left:2px solid;border-right:2px solid;border-left-color:#000000;border-right-color:#000000;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:12px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:12px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:12px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:12px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:12px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="text-align:center;font-size:12px;border-left:2px solid;border-left-color:#000000;border-right:2px solid;border-right-color:#000000;min-width:50px" colspan=4>
						<nobr>&nbsp;</nobr>				    </td>
				</tr>
				';
					$no = 1;
					if ($sum_total != 0) {
					echo '
				<tr >
					<td style="text-align:center;font-size:12px;border-left:2px solid;border-right:2px solid;border-left-color:#000000;border-right-color:#000000;min-width:50px">
						<nobr>'.$no.'</nobr>				    </td>

					<td style="text-align:left;font-size:12px;min-width:50px; padding-left:12px;" colspan=5>
						<nobr></nobr>'.($r['judul_subtotal'] == null ? 'subtotal' : $r['judul_subtotal']).'
					</td>
					<td style="text-align:left;text-transform:capitalize; font-size:12px;padding-left:12px;border-left:2px solid;border-left-color:#000000;" >
						<nobr></nobr>Rp</td>

					<td style="text-align:right;font-size:12px; padding-right:12px; border-right:2px solid;border-right-color:#000000;min-width:50px" colspan=3>
						<nobr></nobr>'.($r['subtotal'] > 0 ? number_format($r['subtotal']) : number_format($sum_total)).'</td>

				</tr>
				';
					}

					echo '
				<tr >
					<td style="text-align:center;font-size:12px;border-left:2px solid;border-right:2px solid;border-left-color:#000000;border-right-color:#000000;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:12px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:12px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:12px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:12px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:12px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="text-align:center;font-size:12px;border-left:2px solid;border-left-color:#000000;border-right:2px solid;border-right-color:#000000;min-width:50px" colspan=4>
						<nobr>&nbsp;</nobr>				    </td>
				</tr>';


				 $tambahan_manual = ($r['biaya_tambahan'] > 0 || $r['biaya_tambahan'] < 0 ? $r['biaya_tambahan'] : 0);
				


				if($r['biaya_tambahan'] > 0 || $r['biaya_tambahan'] < 0){
					$no++;
					echo 
					'<tr >
						<td style="text-align:center;font-size:12px;border-left:2px solid;border-right:2px solid;border-left-color:#000000;border-right-color:#000000;min-width:50px">
							<nobr>'.$no.'</nobr>				    </td>

						<td style="text-align:left;font-size:12px;min-width:50px;padding-left:12px;" colspan=5>
							<nobr></nobr> '.$r['keterangan_biaya_tambahan'].'   				    </td>
						<td style="text-align:left;font-size:12px;padding-left:12px;border-left:2px solid;border-left-color:#000000;" >
							<nobr></nobr>Rp</td>
						<td style="text-align:right;font-size:12px; padding-right:12px; border-right:2px solid;border-right-color:#000000;min-width:50px" colspan=3>
							<nobr></nobr>'.number_format($r['biaya_tambahan']).'</td>
					</tr>

					<tr >
						<td style="text-align:center;font-size:12px;border-left:2px solid;border-right:2px solid;border-left-color:#000000;border-right-color:#000000;min-width:50px">
							<nobr>&nbsp;</nobr>				    </td>
						<td style="font-size:12px;min-width:50px">
							<nobr>&nbsp;</nobr>				    </td>
						<td style="font-size:12px;min-width:50px">
							<nobr>&nbsp;</nobr>				    </td>
						<td style="font-size:12px;min-width:50px">
							<nobr>&nbsp;</nobr>				    </td>
						<td style="font-size:12px;min-width:50px">
							<nobr>&nbsp;</nobr>				    </td>
						<td style="font-size:12px;min-width:50px">
							<nobr>&nbsp;</nobr>				    </td>
						<td style="text-align:center;font-size:12px;border-left:2px solid;border-left-color:#000000;border-right:2px solid;border-right-color:#000000;min-width:50px" colspan=4>
							<nobr>&nbsp;</nobr>				    </td>
					</tr>


					';
				}

				














				$no++;

				$qp = "select sum(biaya_tambahan) as pajak from invoice_detail where id_invoice='".$id_inv."' and pajak='Ya'";
				$pajak = mysqli_query($con, $qp);
				$p = mysqli_fetch_assoc($pajak);
				$tambahan_kena_pajak = $p['pajak'];

				$pajak1 = floor($ppn/100* ($sum_total+$tambahan_kena_pajak+$tambahan_manual));

					if ($sum_total != 0) {
					echo '
				<tr >
					<td style="text-align:center;font-size:12px;border-left:2px solid;border-right:2px solid;border-left-color:#000000;border-right-color:#000000;min-width:50px">
						<nobr>'.$no.'</nobr>				    </td>

					<td style="text-align:left;font-size:12px;min-width:50px;padding-left:12px;" colspan=5>
						<nobr></nobr> PPN '.($r['tampil_ppn'] == 'Y' ? $ppn.' %' : '').'   				    </td>
					<td style="text-align:left;font-size:12px;padding-left:12px;border-left:2px solid;border-left-color:#000000;" >
						<nobr></nobr>Rp</td>
					<td style="text-align:right;font-size:12px; padding-right:12px; border-right:2px solid;border-right-color:#000000;min-width:50px" colspan=3>
						<nobr></nobr>'.($r['ppn_subtotal'] > 0 ? number_format($r['ppn_subtotal']) : number_format($pajak1)).'</td>
				</tr>
				';
				$no++;
				}

					echo '
				<tr >
					<td style="text-align:center;font-size:12px;border-left:2px solid;border-right:2px solid;border-left-color:#000000;border-right-color:#000000;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:12px;min-width:50px;padding-left:12px;">
						'.($r['keterangan_ppn'] == '' && $r['keterangan_ppn'] === null  ? '&nbsp;' : $r['keterangan_ppn'] ).'					    
					</td>
					<td style="font-size:12px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:12px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:12px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:12px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="text-align:center;font-size:12px;border-left:2px solid;border-left-color:#000000;border-right:2px solid;border-right-color:#000000;min-width:50px" colspan=4>
						<nobr>&nbsp;</nobr>				    </td>
				</tr>';

				if($r['keterangan_ppn'] != '' && $r['keterangan_ppn'] !== null){
					echo 

					'<tr >
					<td style="text-align:center;font-size:12px;border-left:2px solid;border-right:2px solid;border-left-color:#000000;border-right-color:#000000;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:12px;min-width:50px;padding-left:12px;">
								<nobr>&nbsp;</nobr>				    
					</td>
					<td style="font-size:12px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:12px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:12px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:12px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="text-align:center;font-size:12px;border-left:2px solid;border-left-color:#000000;border-right:2px solid;border-right-color:#000000;min-width:50px" colspan=4>
						<nobr>&nbsp;</nobr>				    </td>
				</tr>';
				}





				$qt = "select * from invoice_detail where id_invoice='".$id_inv."'";
              				$tambahan = mysqli_query($con, $qt);
									while($t=mysqli_fetch_assoc($tambahan)){
				echo '
				<tr >
					<td style="text-align:center;font-size:12px;border-left:2px solid;border-right:2px solid;border-left-color:#000000;border-right-color:#000000;min-width:50px">
						<nobr>'.$no.'</nobr>				    </td>

					<td style="text-align:left;font-size:12px;min-width:50px;padding-left:12px;" colspan=5>
						<nobr></nobr>'.strtoupper($t['detail_biaya_tambahan']).'   				    </td>
					<td style="text-align:left;text-transform:capitalize;font-size:12px;padding-left:12px;border-left:2px solid;border-left-color:#000000;" >
						<nobr></nobr>Rp</td>
					<td style="text-align:right;font-size:12px; padding-right:12px; border-right:2px solid;border-right-color:#000000;min-width:50px" colspan=3>
						<nobr></nobr>'.number_format($t['biaya_tambahan']).'</td>
				</tr>
				<tr >
					<td style="text-align:center;font-size:12px;border-left:2px solid;border-right:2px solid;border-left-color:#000000;border-right-color:#000000;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:12px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:12px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:12px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:12px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:12px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="text-align:center;font-size:12px;border-left:2px solid;border-left-color:#000000;border-right:2px solid;border-right-color:#000000;min-width:50px" colspan=4>
						<nobr>&nbsp;</nobr>				    </td>
				</tr>
				';
				$no++;
				}

					$qp10 = "select sum(biaya_tambahan) as pajak from invoice_detail where id_invoice='".$id_inv."' and pajak='10'";
					$pajak10 = mysqli_query($con, $qp10);
					$p10 = mysqli_fetch_assoc($pajak10);
					$tambahan_kena_pajak10 = $p10['pajak'];

					$pajak_packing = floor(10/100*($sum_packing+$tambahan_kena_pajak10));
					$no_packing = $no;
					$no_pajak_packing = $no+1;
					if($r['inv_packing_kayu'] > 0){
						$no_asuransi = $no+2;
					}
					else{
						$no_asuransi = $no;
					}

				if($r['inv_packing_kayu'] > 0){
					echo '
				<tr >
					<td style="text-align:center;font-size:12px;border-left:2px solid;border-right:2px solid;border-left-color:#000000;border-right-color:#000000;min-width:50px">
						<nobr> '.$no_packing.'</nobr>				    </td>

					<td style="text-align:left;font-size:12px;min-width:50px;padding-left:12px;" colspan=5>
						<nobr></nobr> PACKING KAYU   				    </td>
					<td style="text-align:left;font-size:12px;padding-left:12px;border-left:2px solid;border-left-color:#000000;" >
						<nobr></nobr>Rp</td>
					<td style="text-align:right;font-size:12px; padding-right:12px; border-right:2px solid;border-right-color:#000000;min-width:50px" colspan=3>
						<nobr></nobr>'.($r['subtotal'] > 0 ? number_format($r['inv_packing_kayu']) : number_format($sum_packing)).'</td>
				</tr>
				<tr >
					<td style="text-align:center;font-size:12px;border-left:2px solid;border-right:2px solid;border-left-color:#000000;border-right-color:#000000;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:12px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:12px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:12px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:12px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:12px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="text-align:center;font-size:12px;border-left:2px solid;border-left-color:#000000;border-right:2px solid;border-right-color:#000000;min-width:50px" colspan=4>
						<nobr>&nbsp;</nobr>				    </td>
				</tr>

				<tr >
					<td style="text-align:center;font-size:12px;border-left:2px solid;border-right:2px solid;border-left-color:#000000;border-right-color:#000000;min-width:50px">
						<nobr>'.$no_pajak_packing.'</nobr>				    </td>

					<td style="text-align:left;font-size:12px;min-width:50px;padding-left:12px;" colspan=5>
						<nobr></nobr> VAT 10%   				    </td>
					<td style="text-align:left;font-size:12px;padding-left:12px;border-left:2px solid;border-left-color:#000000;" >
						<nobr></nobr>Rp</td>
					<td style="text-align:right;font-size:12px; padding-right:12px; border-right:2px solid;border-right-color:#000000;min-width:50px" colspan=3>
						<nobr></nobr>'.($r['subtotal'] > 0 ? number_format($r['ppn_packing']) : number_format($pajak_packing)).'</td>
				</tr>
				<tr >
					<td style="text-align:center;font-size:12px;border-left:2px solid;border-right:2px solid;border-left-color:#000000;border-right-color:#000000;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:12px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:12px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:12px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:12px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:12px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="text-align:center;font-size:12px;border-left:2px solid;border-left-color:#000000;border-right:2px solid;border-right-color:#000000;min-width:50px" colspan=4>
						<nobr>&nbsp;</nobr>				    </td>
				</tr>
				';  
				} 
				echo '

				';
				if($r['inv_asuransi'] > 0 ){
					echo '
				<tr >
					<td style="text-align:center;font-size:12px;border-left:2px solid;border-right:2px solid;border-left-color:#000000;border-right-color:#000000;min-width:50px">
						<nobr>'.$no_asuransi.'</nobr>				    </td>

					<td style="text-align:left;font-size:12px;min-width:50px;padding-left:12px;" colspan=5>
						<nobr></nobr> ASURANSI  				    </td>
					<td style="text-align:left;font-size:12px;padding-left:12px;border-left:2px solid;border-left-color:#000000;" >
						<nobr></nobr>Rp</td>
					<td style="text-align:right;font-size:12px; padding-right:12px; border-right:2px solid;border-right-color:#000000;min-width:50px" colspan=3>
						<nobr></nobr>'.($r['inv_asuransi'] > 0 ? number_format($r['inv_asuransi']) : number_format($sum_asuransi)).'</td>
				</tr>
				<tr >
					<td style="text-align:center;font-size:12px;border-left:2px solid;border-right:2px solid;border-left-color:#000000;border-right-color:#000000;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:12px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:12px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:12px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:12px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:12px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="text-align:center;font-size:12px;border-left:2px solid;border-left-color:#000000;border-right:2px solid;border-right-color:#000000;min-width:50px" colspan=4>
						<nobr>&nbsp;</nobr>				    </td>
				</tr>
				'; }
			?>
				<tr >
					<td style="text-align:center;font-size:12px;border-left:2px solid;border-right:2px solid;border-left-color:#000000;border-right-color:#000000;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:12px;min-width:50px">
						<nobr></nobr>				    </td>
					<td style="font-size:12px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:12px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:12px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:12px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="text-align:center;font-size:12px;border-left:2px solid;border-left-color:#000000;border-right:2px solid;border-right-color:#000000;min-width:50px" colspan=4>
						<nobr></nobr>				    </td>
				</tr>
				<tr >
					<td style="text-align:center;font-size:12px;border-left:2px solid;border-right:2px solid;border-left-color:#000000;border-right-color:#000000;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:12px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:12px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:12px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:12px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:12px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="text-align:center;font-size:12px;border-left:2px solid;border-left-color:#000000;border-right:2px solid;border-right-color:#000000;min-width:50px" colspan=4>
						<nobr>&nbsp;</nobr>				    </td>
				</tr>
				<tr >
					<td style="text-align:center;font-size:12px;border-left:2px solid;border-right:2px solid;border-left-color:#000000;border-right-color:#000000;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:12px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:12px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:12px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:12px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:12px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="text-align:center;font-size:12px;border-left:2px solid;border-left-color:#000000;border-right:2px solid;border-right-color:#000000;min-width:50px" colspan=4>
						<nobr>&nbsp;</nobr>				    </td>
				</tr>
				<tr >
					<td style="text-align:center;font-size:12px;border-left:2px solid;border-right:2px solid;border-left-color:#000000;border-right-color:#000000;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:12px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:12px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:12px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:12px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:12px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="text-align:center;font-size:12px;border-left:2px solid;border-left-color:#000000;border-right:2px solid;border-right-color:#000000;min-width:50px" colspan=4>
						<nobr>&nbsp;</nobr>				    </td>
				</tr>
				<tr >
					<td style="text-align:center;font-size:12px;border-left:2px solid;border-right:2px solid;border-left-color:#000000;border-right-color:#000000;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:12px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:12px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:12px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:12px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:12px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="text-align:center;font-size:12px;border-left:2px solid;border-left-color:#000000;border-right:2px solid;border-right-color:#000000;min-width:50px" colspan=4>
						<nobr>&nbsp;</nobr>				    </td>
				</tr>
				<tr >
					<td style="text-align:center;font-size:12px;border-left:2px solid;border-right:2px solid;border-left-color:#000000;border-right-color:#000000;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:12px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:12px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:12px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:12px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:12px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="text-align:center;font-size:12px;border-left:2px solid;border-left-color:#000000;border-right:2px solid;border-right-color:#000000;min-width:50px" colspan=4>
						<nobr>&nbsp;</nobr>				    </td>
				</tr>

				<tr >
					<td style="text-align:center;font-size:12px;border-left:2px solid;border-right:2px solid;border-left-color:#000000;border-right-color:#000000;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:12px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:12px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:12px;font-weight:bold;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:12px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:12px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="text-align:center;font-size:12px;border-left:2px solid;border-left-color:#000000;border-right:2px solid;border-right-color:#000000;min-width:50px" colspan=4>
						<nobr>&nbsp;</nobr>				    </td>
				</tr>
				<?php if ($no < 3) 
				{
				?>
				<tr >
					<td style="text-align:center;font-size:12px;border-left:2px solid;border-right:2px solid;border-left-color:#000000;border-right-color:#000000;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:12px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:12px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:12px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:12px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:12px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="text-align:center;font-size:12px;border-left:2px solid;border-left-color:#000000;border-right:2px solid;border-right-color:#000000;min-width:50px" colspan=4>
						<nobr>&nbsp;</nobr>				    </td>
				</tr>

				<tr >
					<td style="text-align:center;font-size:12px;border-left:2px solid;border-right:2px solid;border-left-color:#000000;border-right-color:#000000;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:12px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:12px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:12px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:12px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:12px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="text-align:center;font-size:12px;border-left:2px solid;border-left-color:#000000;border-right:2px solid;border-right-color:#000000;min-width:50px" colspan=4>
						<nobr>&nbsp;</nobr>				    </td>
				</tr>
				
				<tr >
					<td style="text-align:center;font-size:12px;border-left:2px solid;border-right:2px solid;border-left-color:#000000;border-right-color:#000000;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:12px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:12px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:12px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:12px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:12px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="text-align:center;font-size:12px;border-left:2px solid;border-left-color:#000000;border-right:2px solid;border-right-color:#000000;min-width:50px" colspan=4>
						<nobr>&nbsp;</nobr>				    </td>
				</tr>
				<tr >
					<td style="text-align:center;font-size:12px;border-left:2px solid;border-right:2px solid;border-left-color:#000000;border-right-color:#000000;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:12px;font-weight:bold;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:12px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:12px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:12px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:12px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="text-align:center;font-size:12px;border-left:2px solid;border-left-color:#000000;border-right:2px solid;border-right-color:#000000;min-width:50px" colspan=4>
						<nobr>&nbsp;</nobr>				    </td>
				</tr>
				<?php
				}
				?>
				<tr >
					<td style="text-align:center;font-size:12px;border-left:2px solid;border-right:2px solid;border-left-color:#000000;border-right-color:#000000;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:12px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:12px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:12px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:12px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:12px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="text-align:center;font-size:12px;border-left:2px solid;border-left-color:#000000;border-right:2px solid;border-right-color:#000000;min-width:50px" colspan=4>
						<nobr>&nbsp;</nobr>				    </td>
				</tr>
				<tr >
					<td style="text-align:center;font-size:12px;border-left:2px solid;border-right:2px solid;border-left-color:#000000;border-right-color:#000000;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:12px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:12px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:12px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:12px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:12px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="text-align:center;font-size:12px;border-left:2px solid;border-left-color:#000000;border-right:2px solid;border-right-color:#000000;min-width:50px" colspan=4>
						<nobr>&nbsp;</nobr>				    </td>
				</tr>

				<tr >
					<td style="font-size:12px;border-left:2px solid;border-right:2px solid;border-left-color:#000000;border-right-color:#000000;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:10px;min-width:50px; padding: 10px; font-style: italic;" colspan="5">
						<span style="color: red;">NOTE : </span> <span style="color: green;">WHT Slip PPh 23</span><span style="color: green;text-transform:lowercase;"> must be sent to our email ggaluh@cesco-logistics.com and <br> fin_acc@cesco-logistics.com not less than end of month After Payment.</span>   </td>
					
					
					<td style="text-align:right;font-size:12px;border-left:2px solid;border-left-color:#000000;min-width:50px" colspan="3">
						<nobr>&nbsp;</nobr>				    </td>
					
					<td style="text-align:right;font-size:12px;border-right:2px solid;border-right-color:#000000;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
				</tr>
				<tr >
					<td style="text-align:center;font-size:12px;font-weight:bold;border-left:2px solid;border-left-color:#000000;border-right:2px solid;border-right-color:#000000;min-width:50px;">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="text-align:center;font-size:14px;font-weight:bold;border-top:2px solid;border-top-color:#000000;min-width:50px;border-bottom:2px solid;border-bottom-color:#000000;" colspan=5 rowspan=2>
						<nobr>Total</nobr>
									    </td>
					<?php
						$qsum = "select sum(biaya_tambahan) as sum_biaya_tambahan from invoice_detail where id_invoice='".$id_inv."'";
              					$sum_bt = mysqli_query($con, $qsum);
              					$sbt = mysqli_fetch_assoc($sum_bt);
              				$total_semua = $sum_total+$tambahan_manual+$sbt['sum_biaya_tambahan']+$pajak1;
					?>

					<td style="text-align:left;text-transform: capitalize; padding-left:12px;font-size:14px;font-weight:bold;border-left:2px solid;border-top:2px solid;border-left-color:#000000;border-top-color:#000000;border-bottom:2px solid;border-right-color:#000000;border-bottom-color:#000000;" colspan=1 rowspan=2>
						<nobr> Rp </nobr>				    </td>
					<td style="text-align:right; padding-right:12px;font-size:14px;font-weight:bold;border-top:2px solid;border-top-color:#000000;border-right:2px solid;border-bottom:2px solid;border-right-color:#000000;border-bottom-color:#000000;min-width:50px;" colspan=3 rowspan=2>
						<nobr> <?php echo ($r['total_all'] > 0 ? number_format($r['total_all']) : number_format($total_semua)); ?></nobr>				    </td>
				</tr>
				<tr >
					<td style="font-size:14px;border-left:2px solid;border-right:2px solid;border-bottom:2px solid;border-left-color:#000000;border-right-color:#000000;border-bottom-color:#000000;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
				</tr>
				<tr >
					<td style="font-size:12px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:10px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:12px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:12px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:12px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:12px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="text-align:right;font-size:12px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="text-align:right;font-size:12px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="text-align:right;font-size:12px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="text-align:right;font-size:12px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
				</tr>
				<tr >
					<td style="font-size:12px;font-weight:bold;min-width:50px" colspan=4>
						<nobr>PAYMENT IS CONSIDERED VALID IF THE TRANSFER </nobr>				    </td>
					<td style="font-size:12px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:12px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="text-align:center;font-size:12px;min-width:50px" colspan=2>
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:12px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:12px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
				</tr>
				<tr >
					<td style="font-size:12px;font-weight:bold;min-width:50px" colspan=4>
						<nobr>HAS BEEN MADE TO THE ACCOUNT BELOW</nobr>				    </td>
					<td style="min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="text-align:center;font-size:12px;font-weight:bold;min-width:50px" colspan=2>
						<nobr>Jakarta,</nobr>				    </td>
					<td style="text-align:center;font-size:12px;font-weight:bold;min-width:50px" colspan=2>
						<nobr><?php echo date('d F Y', strtotime($r['tanggal'])); ?></nobr>				    </td>
				</tr>
				<tr >
					<td style="font-size:12px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:12px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:12px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:12px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:12px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:12px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:12px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:12px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:12px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:12px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
				</tr>
				<tr >
					<td style="font-size:12px;font-weight:bold;min-width:50px" colspan=4>
						<nobr>PLEASE TRANSFER PAYMENT TO :</nobr>				    </td>
					<td style="font-size:10px;min-width:50px" colspan="6" rowspan="5">
						<nobr>&nbsp;</nobr>				  </td>

				</tr>
				<tr >
					<td style="font-size:12px;font-weight:bold;min-width:50px" colspan=4>
						<nobr>PT CESCO LOGISTIK INDONESIA</nobr><br> AC. <?php echo $r['no_rekening'] ?>	    </td>

				</tr>
				<tr >
					<td style="font-size:12px;font-weight:bold;min-width:50px" colspan=4>
						<nobr></nobr>				    </td>

				</tr>
				<tr >
					<td style="font-size:12px;font-weight:bold;min-width:50px" colspan=4>
						<nobr><?php echo $r['nama_bank'] ?></nobr>				    </td>

				</tr>
				<tr >
					<td style="font-size:12px;font-weight:bold;min-width:50px" colspan=4>
						<nobr><?php echo $r['cabang'] ?></nobr>				    </td>

				</tr>

				<tr >
					<td style="font-size:10px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:10px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:10px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:10px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:10px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="text-align:center;font-size:14px;font-weight:bold;text-decoration:underline;min-width:50px" colspan=5>
						<nobr><?php echo strtoupper($r['nama_pembuat']);?></nobr>				    </td>
				</tr>
				<tr >
					<td style="font-size:10px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:10px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:10px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:10px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="font-size:10px;min-width:50px">
						<nobr>&nbsp;</nobr>				    </td>
					<td style="text-align:center;font-size:10px;font-weight:bold;min-width:50px" colspan=5>
						<nobr>Billing &amp; Collection Staff</nobr>				    </td>
				</tr>
		    </tbody>
	    </table>
        </div>
</page>
</body>
					</html>


<?php 

}
else{
		header('location:../login.php');

}

?>

