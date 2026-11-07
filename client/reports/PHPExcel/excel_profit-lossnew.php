<?php
session_start();

include ('../../config/connection.php');
include ('../../config/encrypt.php');
include ('../../config/library.php');
//include ('phpqrcode/qrlib.php');

	//$dari = $_GET['dari'];
	//$sampai = $_GET['sampai'];
	$id_kustomer = $_GET['id_kustomer'];
	$idinvoice = $_GET['idinvoice'];
	$stts = (isset($_GET['stts'])) ? $_GET['stts'] : '';

	$judul = ($stts == 'loss') ? 'Loss' : '';

	$key 			= "asdf$1011";
	$encryptUser 	= $_SESSION['id_user'];
	$id_user 		= base64_decrypt($encryptUser,$key);

	$query 		= mysqli_query($con,"select a.*, case when a.status='draft' then 'DRAFT' else a.nomor end as nomor, b.kode_kustomer, b.nama_kustomer, b.alamat, b.kota, c.nama_kota, x_namakota(x_hub(b.kota)) as kota, b.telp from invoice a left join kustomer b on b.id_kustomer=a.id_kustomer left join kota c on c.id_kota=b.kota where id_invoice='$idinvoice'");
			$r 			= mysqli_fetch_assoc($query);


require '../../config/phpspreadsheet/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet(); 

$sheet->setCellValue('A1', $r['nama_kustomer']);   
$sheet->setCellValue('A2',$r['dari'].' - '.$r['sampai']);
$sheet->setCellValue('A6', 'NO'); 
$sheet->setCellValue('B6', 'TANGGAL PICKUP'); 
$sheet->setCellValue('C6', 'AWB'); 
$sheet->setCellValue('D6', 'KOMODITI');
$sheet->setCellValue('E6', 'SERVICE'); 
$sheet->setCellValue('F6', 'REQUESTOR');
$sheet->setCellValue('G6', 'LOC CODE');
$sheet->setCellValue('H6', 'PENGIRIM');
$sheet->setCellValue('I6', 'PERUSAHAAN PENGIRIM');
$sheet->setCellValue('J6', 'KOTA ASAL');
$sheet->setCellValue('K6', 'ALAMAT ASAL');
$sheet->setCellValue('L6', 'PERUSAHAAN PENERIMA');
$sheet->setCellValue('M6', 'NAMA PENERIMA');  
$sheet->setCellValue('N6', 'KOTA TUJUAN'); 
$sheet->setCellValue('O6', 'ALAMAT LENGKAP PENERIMA');
$sheet->setCellValue('P6', 'DITERIMA OLEH');
$sheet->setCellValue('Q6', 'TANGGAL DITERIMA'); 
$sheet->setCellValue('R6', 'VOLUME PER BERAT');
$sheet->setCellValue('S6', 'HARGA PER KG');
$sheet->setCellValue('T6', 'MIN KG');
$sheet->setCellValue('U6', 'TAMBAHAN');
$sheet->setCellValue('V6', 'HARGA PENGIRIMAN');
$sheet->setCellValue('W6', 'PACKING');
$sheet->setCellValue('X6', 'TOTAL');
$sheet->setCellValue('Y6', 'PU FEE');
$sheet->setCellValue('Z6', 'PU TOLL');
$sheet->setCellValue('AA6', 'PU PARKING');
$sheet->setCellValue('AB6', 'PU MEN'); 
$sheet->setCellValue('AC6', 'FELIV FEE');
$sheet->setCellValue('AD6', 'DELIV TOLL');
$sheet->setCellValue('AE6', 'DELIV PARKING');
$sheet->setCellValue('AF6', 'DELIV MEN');
$sheet->setCellValue('AG6', 'OUT FEE');
$sheet->setCellValue('AH6', 'OUT TOLL');
$sheet->setCellValue('AI6', 'OUT SMU');
$sheet->setCellValue('AJ6', 'OUT HANDLING');
$sheet->setCellValue('AK6', 'OUT PARKING');
$sheet->setCellValue('AL6', 'OUT MEN');
$sheet->setCellValue('AM6', 'IN FEE');
$sheet->setCellValue('AN6', 'IN TOLL');
$sheet->setCellValue('AO6', 'IN SMU');
$sheet->setCellValue('AP6', 'IN HANDLING');
$sheet->setCellValue('AQ6', 'IN PARKING');
$sheet->setCellValue('AR6', 'IN MEN');
$sheet->setCellValue('AS6', 'INV AGEN PU');
$sheet->setCellValue('AT6', 'INV AGEN TRANSIT');
$sheet->setCellValue('AU6', 'INV AGENT DELIV');
$sheet->setCellValue('AV6', 'TOTAL AGENT AFTER DISCOUNT');
$sheet->setCellValue('AW6', 'TOTAL COSTING');
$sheet->setCellValue('AX6', 'PROFIT LOSS');                                        



//MULAI QUERY

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
                        a.keterangan_biaya,
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
						ifnull(a.biaya_packing_agent,0) as biaya_packing_agent,
						ifnull(a.biaya_tambahan_agent,0) as biaya_tambahan_agent,
						ifnull(md.biaya_tambahan_agent_transit,0) as biaya_tambahan_agent_transit,
						a.pid,
                        (ifnull(a.kunci_total_agent,0) + ifnull(a.kunci_total_agent_deliv,0) + ifnull(a.kunci_total_agent_pickup,0) + ifnull(md.kunci_total_agent_transit,0) ) as total_agent,
						ifnull(a.kunci_total_agent,0) as kunci_total_agent,
						ifnull(a.kunci_total_agent_deliv,0) as kunci_total_agent_deliv,
						ifnull(a.kunci_total_agent_pickup,0) as kunci_total_agent_pickup,
						ifnull(md.kunci_total_agent_transit,0) as kunci_total_agent_transit,
						cs.kode_kustomer,
						case when terlambat_pickup in (1,2) and terlambat_delivery in (1,2) then
								ifnull(a.diskon_pickup,0)+ifnull(a.diskon_deliv,0)
							when terlambat_pickup in (1,2) and terlambat_delivery not in (1,2) then
								ifnull(a.diskon_pickup,0)
							when terlambat_pickup not in (1,2) and terlambat_delivery in (1,2) then
								ifnull(a.diskon_deliv,0)
						else 0
						end as diskon,
						pickup_by_agent, delivery_by_agent, kunci_berat_agent,
						x_inter(id_kota_asal) as inter_asal, x_inter(id_kota_tujuan) as inter_tujuan,
						getCabangCesco(a.id_pengiriman) as jumlah_cabang,
						getRateCustomer2(a.id_kustomer, a.id_kecamatan_asal, a.lokasi_asal, a.lokasi_tujuan, a.id_kota_asal, a.id_kota_tujuan, a.id_kecamatan_tujuan, a.service, a.komoditi, a.loc_code) as rate_customer,
						(select count(1) from invoice_send
							where invoice_type='AGENT' and agent_date = date_format(a.tanggal,'%Y-%m') and agent_id = a.pickup_by_agent
							and confirm_dtm is not null
						) as confirm_agent_pu,
						(select count(1) from invoice_send
							where invoice_type='AGENT' and agent_date = date_format(a.tanggal,'%Y-%m') and agent_id = a.delivery_by_agent
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
						) as confirm_agent_intra
                    FROM
                        pengiriman a
						left join manifest_detail md on md.id_pengiriman = a.id_pengiriman 
						left join kustomer cs on cs.id_kustomer = a.id_kustomer
						left join pelayanan pel on pel.id_pelayanan = a.service
                            LEFT JOIN
                        negara f ON f.id_negara = a.lokasi_tujuan
                            and a.service = 6
                    where
                        a.id_invoice = $idinvoice and a.status != 7 ";



      $cari .= " group by a.id_pengiriman) x";


      $sum_total=0;
			$sql = mysqli_query($con,$cari);
			$no=1;
			$i=7;
			$grand_total_costing = 0;
			$total_profitloss = 0;
			while($t=mysqli_fetch_assoc($sql)){


					$totalAgent = 0;
					$totalAgenPickup = ($t['confirm_agent_pu'] == 0) ? 0 : $t['kunci_total_agent_pickup'];
					$totalAgentDeliv = ($t['confirm_agent_deliv'] == 0) ? 0 : $t['kunci_total_agent_deliv'];
					$totalAgentTransit = ($t['confirm_agent_transit'] == 0) ? 0 : $t['kunci_total_agent_transit'];
					$totalAgentIntra = ($t['confirm_agent_intra'] == 0) ? 0 : $t['kunci_total_agent'];

					if ($t['intercity'] == 0){ //001

						if ($t['pickup_by_agent'] == $t['delivery_by_agent']){

							$totalAgent = $totalAgentIntra;
							$totalAgentDeliv = $totalAgentIntra;

						}else{

							if ($t['inter_asal'] != 'Y' && $t['inter_tujuan'] != 'Y'){ //CROSS CITY

								$totalAgent = $totalAgenPickup + $totalAgentDeliv + $totalAgentTransit;



							}else if ($t['inter_asal'] != 'Y' && $t['inter_tujuan'] == 'Y'){	//INBOUND

								$totalAgent = $totalAgenPickup + $totalAgentTransit;




							}else if ($t['inter_asal'] == 'Y' && $t['inter_tujuan'] != 'Y'){ //OUTBOUND

								$totalAgent = $totalAgentDeliv + $totalAgentTransit;


							}
						}

					}else{ //002

						$totalAgent = $totalAgenPickup + $totalAgentDeliv + $totalAgentTransit;

					}

					if ($t['confirm_agent_pu'] == 0 && $t['confirm_agent_deliv'] == 0 && $t['confirm_agent_transit'] == 0 && $t['confirm_agent_intra'] == 0){
						$new_total_diskon = 0;
						$total_agent = 0;
						$diskon = 0;
						$totalAgentAfterDiskon = 0;
					}else{
						$new_total_diskon = $totalAgent - ($t['biaya_packing_agent']+$t['biaya_tambahan_agent']+$t['biaya_tambahan_agent_transit']);
						$total_agent = $new_total_diskon;
						$diskon = $t['diskon'];
						$totalAgentAfterDiskon = ($total_agent-(($diskon/100)*$total_agent))+($t['biaya_packing_agent']+$t['biaya_tambahan_agent']+$t['biaya_tambahan_agent_transit']);
					}

                $harga_lock = ($t['kode_kustomer'] == 'CIS' || $t['kode_kustomer'] == 'DEL') ? round($t['harga_lock'],2) : round($t['harga_lock']);
                $harga_total = $harga_lock+$t['biaya_packing']+$t['biaya_asuransi'];



					$total_costing = $t['costing_pickup_fee']+$t['costing_pickup_parking']+$t['costing_pickup_toll']+$t['costing_men_power']+$t['costing_delivery_fee']+$t['costing_delivery_parking']+$t['costing_delivery_toll']+$t['costing_delivery_menpower']+$t['costing_outbound_fee']+$t['costing_outbound_toll']+$t['costing_outbound_smu']+$t['costing_outbound_handling']+$t['costing_outbound_parking']+$t['costing_outbound_menpower']+$t['costing_inbound_fee']+$t['costing_inbound_toll']+$t['costing_inbound_smu']+$t['costing_inbound_handling']+$t['costing_inbound_parking']+$t['costing_inbound_menpower']+$totalAgentAfterDiskon;
					
					$profit_loss = $harga_total-$total_costing;



					if ($stts == '' || ($stts == 'loss' && $profit_loss < 0)){

                        $sum_total = $sum_total+$harga_total;

							$grand_total_costing = $grand_total_costing+$total_costing;
							$total_profitloss = $profit_loss+$total_profitloss;

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

							$sheet->setCellValue('A'.$i, $no++); 
							$sheet->setCellValue('B'.$i, $t['tanggal']); 
							$sheet->setCellValue('C'.$i, $t['airwaybill_kode']); 
							$sheet->setCellValue('D'.$i, ($t['jenis_barang'] == "" ? $t['komoditi'] : "" ).' '.  strtoupper($t['jenis_barang']));
							$sheet->setCellValue('E'.$i, $t['service']); 
							$sheet->setCellValue('F'.$i, $t['namakustomer']);
							$sheet->setCellValue('G'.$i, $t['loc_code']. ($t['pid'] != "" ? " , PID = ".$t[pid] : "" ));
							$sheet->setCellValue('H'.$i, $t['nama_pengirim']);
							$sheet->setCellValue('I'.$i, $t['perusahaan_pengirim']);
							$sheet->setCellValue('J'.$i, $lokasi_asal);
							$sheet->setCellValue('K'.$i,  $t['alamat_pengirim']);
							$sheet->setCellValue('L'.$i, $t['perusahaan_penerima']);
							$sheet->setCellValue('M'.$i, $t['nama_penerima']);  
							$sheet->setCellValue('N'.$i, $lokasi_tujuan); 
							$sheet->setCellValue('O'.$i, $t['alamat_penerima']);
							$sheet->setCellValue('P'.$i, $t['delivery_name']);
							$sheet->setCellValue('Q'.$i, $t['delivery_date']); 
							$sheet->setCellValue('R'.$i, $t['berat']);
							$sheet->setCellValue('S'.$i, ($t['harga_pengiriman'] == '' ? $t['harga_perkg'] : '-' ));
							$sheet->setCellValue('T'.$i, ($t['harga_pengiriman'] == '' ? $t['min_kgg'] : '-' ));
							$sheet->setCellValue('U'.$i, round($t['biaya_tambahan']));
							$sheet->setCellValue('V'.$i, round($t['harga_lock']));
							$sheet->setCellValue('W'.$i, round($t['biaya_packing']));
							$sheet->setCellValue('X'.$i, round($harga_total));
							$sheet->setCellValue('Y'.$i, round($t['costing_pickup_fee']));
							$sheet->setCellValue('Z'.$i, round($t['costing_pickup_toll']));
							$sheet->setCellValue('AA'.$i, round($t['costing_pickup_parking']));
							$sheet->setCellValue('AB'.$i, round($t['costing_men_power'])); 
							$sheet->setCellValue('AC'.$i, round($t['costing_delivery_fee']));
							$sheet->setCellValue('AD'.$i, round($t['costing_delivery_toll']));
							$sheet->setCellValue('AE'.$i, round($t['costing_delivery_parking']));
							$sheet->setCellValue('AF'.$i, round($t['costing_delivery_menpower']));
							$sheet->setCellValue('AG'.$i, round($t['costing_outbound_fee']));
							$sheet->setCellValue('AH'.$i, round($t['costing_outbound_toll']));
							$sheet->setCellValue('AI'.$i, round($t['costing_outbound_smu']));
							$sheet->setCellValue('AJ'.$i, round($t['costing_outbound_handling']));
							$sheet->setCellValue('AK'.$i, round($t['costing_outbound_parking']));
							$sheet->setCellValue('AL'.$i, round($t['costing_outbound_menpower']));
							$sheet->setCellValue('AM'.$i, round($t['costing_inbound_fee']));
							$sheet->setCellValue('AN'.$i, round($t['costing_inbound_toll']));
							$sheet->setCellValue('AO'.$i, round($t['costing_inbound_smu']));
							$sheet->setCellValue('AP'.$i, round($t['costing_inbound_handling']));
							$sheet->setCellValue('AQ'.$i, round($t['costing_inbound_parking']));
							$sheet->setCellValue('AR'.$i, round($t['costing_inbound_menpower']));
							$sheet->setCellValue('AS'.$i, round($totalAgenPickup));
							$sheet->setCellValue('AT'.$i, round($totalAgentTransit));
							$sheet->setCellValue('AU'.$i, round($totalAgentDeliv));
							$sheet->setCellValue('AV'.$i, round($totalAgentAfterDiskon));
							$sheet->setCellValue('AW'.$i, round($total_costing));
							$sheet->setCellValue('AX'.$i, round($profit_loss)); 
							$i++;

					}//akhir if ($stts == '' || ($stts == 'loss' && $profit_loss < 0)){

			}//akhir while

			$sheet->setCellValue('X'.$i++, round($grand_total_costing));
			$sheet->setCellValue('AX'.$i++, round($total_profitloss));

//AKHIR QUERY



// Write an .xlsx file  

$styleArray = [
			'borders' => [
				'allBorders' => [
					'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
				],
			],
		];
$i = $i - 1;
$sheet->getStyle('A6:AX'.$i)->applyFromArray($styleArray);

$filename = "".$r['nama_kustomer']."-".$idinvoice."_".$id_user."-".$stts.".xlsx";

$writer = new Xlsx($spreadsheet);
//$writer->save('okereports.xlsx');
//echo "<script>window.location = 'okereports.xlsx'</script>";
$writer->save($filename);
echo "<script>window.location = '".$filename."'</script>";


/*
try {
    $writer = new Xlsx($spreadsheet);
    $writer->save($filename);
    $content = file_get_contents($filename);
} catch(Exception $e) {
    exit($e->getMessage());
}

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="' . urlencode($filename) .'"' );

echo $content;  // this actually send the file content to the browser

unlink($filename);*/

?>