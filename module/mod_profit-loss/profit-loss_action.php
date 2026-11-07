<?php
if (!isset($_SESSION))
{
	session_start();
}

if (empty($_SESSION['username']) AND empty($_SESSION['password']))
{
	header('location:../../application/index.php');
}
else
{
	include "../../config/connection.php";
	require_once('../../config/db_connect.php');
	require_once('../../config/library.php');
	require_once('../../config/encrypt.php');

	$module	= !empty($_GET['module']) ? $_GET['module'] : '';
	$act	= !empty($_GET['act']) ? $_GET['act'] : '';

	$aksi="module/mod_profit-loss/profit-loss_action.php";

	// List paket
	if ($module=='profit-loss' and $act=='read')
	{
		$table =
<<<EOT
	(
		SELECT
			coalesce(i.nomor,i.status) as nomor,
			i.tanggal,
			b.nama_kustomer,
			case when i.method=1 then concat(date_format(i.dari,"%d-%m-%Y"),' s/d ',date_format(i.sampai,"%d-%m-%Y")) else '-' end as tanggalairwaybill,
			/*case when a.method=1 then LEFT(a.loc_code,16) else '-' end as loc_code,*/
			i.id_kustomer,
			i.id_invoice as id_invoice,
			case when i.method=1 then 'range tanggal' else 'pilih pengiiman tertentu' end as metode,
			s.id as send, s.confirm_by as confirm

		FROM invoice i
		left JOIN kustomer b ON b.id_kustomer=i.id_kustomer
		left outer JOIN invoice_send s ON s.invoice_id=i.id_invoice and s.invoice_type = 'CUSTOMER'
		where  i.tanggal >= now() - INTERVAL 50 MONTH
		group by i.id_invoice
	) temp
EOT;
		$primaryKey = 'id_invoice';
		$columns = array(
			array( 'db' => 'nomor', 'dt' => 0, 'formatter' => function($d,$row) {
				return	'<td><center>'.strtoupper($d).'</center></td>';
			}),
			array( 'db' => 'tanggal', 'dt' => 1 ),
			array( 'db' => 'metode', 'dt' => 2 ),
			array( 'db' => 'nama_kustomer', 'dt' => 3 ),
			array( 'db' => 'tanggalairwaybill', 'dt' => 4 ),
			//array( 'db' => 'loc_code', 'dt' => 5 ),
			array( 'db' => 'id_kustomer', 'dt' => 5, 'formatter' => function($d,$row) {


				$primaryKey = 'id_pengiriman';
				$columnD = array(
						array( 'db' => 'id_pengiriman',  'dt' => 'id_pengiriman' ),
						array( 'db' => 'airwaybill_kode',  'dt' => 'airwaybill_kode' ),
						array( 'db' => 'komoditi',  'dt' => 'komoditi' ),
						array( 'db' => 'jenis_barang',  'dt' => 'jenis_barang' ),
						array( 'db' => 'service',  'dt' => 'service' ),

						array( 'db' => 'total_fix',  'dt' => 'total_fix' ),
						array( 'db' => 'harga_lock',  'dt' => 'harga_lock' ),
						array( 'db' => 'harga_kunci',  'dt' => 'harga_kunci' ),
						array( 'db' => 'biaya_packing',  'dt' => 'biaya_packing' ),
						array( 'db' => 'biaya_asuransi',  'dt' => 'biaya_asuransi' ),
						array( 'db' => 'costing_pickup_fee',  'dt' => 'costing_pickup_fee' ),
						array( 'db' => 'costing_pickup_parking',  'dt' => 'costing_pickup_parking' ),
						array( 'db' => 'costing_pickup_toll',  'dt' => 'costing_pickup_toll' ),
						array( 'db' => 'costing_men_power',  'dt' => 'costing_men_power' ),
						array( 'db' => 'costing_delivery_fee',  'dt' => 'costing_delivery_fee' ),

						array( 'db' => 'costing_delivery_parking',  'dt' => 'costing_delivery_parking' ),
						array( 'db' => 'costing_delivery_toll',  'dt' => 'costing_delivery_toll' ),
						array( 'db' => 'costing_delivery_menpower',  'dt' => 'costing_delivery_menpower' ),
						array( 'db' => 'costing_outbound_fee',  'dt' => 'costing_outbound_fee' ),
						array( 'db' => 'costing_outbound_toll',  'dt' => 'costing_outbound_toll' ),
						array( 'db' => 'costing_outbound_smu',  'dt' => 'costing_outbound_smu' ),
						array( 'db' => 'costing_outbound_handling',  'dt' => 'costing_outbound_handling' ),
						array( 'db' => 'costing_outbound_parking',  'dt' => 'costing_outbound_parking' ),
						array( 'db' => 'costing_outbound_menpower',  'dt' => 'costing_outbound_menpower' ),
						array( 'db' => 'costing_inbound_fee',  'dt' => 'costing_inbound_fee' ),

						array( 'db' => 'costing_inbound_toll',  'dt' => 'costing_inbound_toll' ),
						array( 'db' => 'costing_inbound_smu',  'dt' => 'costing_inbound_smu' ),
						array( 'db' => 'costing_inbound_handling',  'dt' => 'costing_inbound_handling' ),
						array( 'db' => 'costing_inbound_parking',  'dt' => 'costing_inbound_parking' ),
						array( 'db' => 'costing_inbound_menpower',  'dt' => 'costing_inbound_menpower' ),

						array( 'db' => 'biaya_packing_agent',  'dt' => 'biaya_packing_agent' ),
						array( 'db' => 'biaya_tambahan_agent',  'dt' => 'biaya_tambahan_agent' ),

						array( 'db' => 'biaya_tambahan_agent_transit',  'dt' => 'biaya_tambahan_agent_transit' ),
						array( 'db' => 'confirm_agent_pu',  'dt' => 'confirm_agent_pu' ),
						array( 'db' => 'confirm_agent_deliv',  'dt' => 'confirm_agent_deliv' ),
						array( 'db' => 'confirm_agent_transit',  'dt' => 'confirm_agent_transit' ),
						array( 'db' => 'confirm_agent_intra',  'dt' => 'confirm_agent_intra' ),

						array( 'db' => 'kode_kustomer',  'dt' => 'kode_kustomer' ),
						array( 'db' => 'kunci_total_agent_deliv',  'dt' => 'kunci_total_agent_deliv' ),
						array( 'db' => 'intercity',  'dt' => 'intercity' ),
						array( 'db' => 'pickup_by_agent',  'dt' => 'pickup_by_agent' ),
						array( 'db' => 'delivery_by_agent',  'dt' => 'delivery_by_agent' ),
						array( 'db' => 'diskon',  'dt' => 'diskon' ),
						array( 'db' => 'inter_asal',  'dt' => 'inter_asal' ),
						array( 'db' => 'inter_tujuan',  'dt' => 'inter_tujuan' ),
						array( 'db' => 'jumlah_cabang',  'dt' => 'jumlah_cabang' ),
						array( 'db' => 'kunci_total_agent_pickup',  'dt' => 'kunci_total_agent_pickup' ),

						array( 'db' => 'kunci_total_agent_pickup',  'dt' => 'kunci_total_agent_pickup' ),
						array( 'db' => 'kunci_total_agent_transit',  'dt' => 'kunci_total_agent_transit' ),
						array( 'db' => 'kunci_total_agent',  'dt' => 'kunci_total_agent' ),





				);



				$cekLoss= "(
					select x.*, substring_index (substring_index(rate_customer,'~',1), ',', -1) as id_rate_customer,
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
                                berat_aktual) * (select harga_perkg)) + biaya_packing + biaya_asuransi) as jumlah_harga,
								confirm_agent_deliv_x as confirm_agent_deliv
                        from (	SELECT
                        a.id_pengiriman, a.airwaybill_kode,
                        case
                            when a.komoditi = '1' then 'Paket'
                            else 'Dokumen'
                        end as komoditi,
                        a.jenis_barang,
						pel.nama_pelayanan as service,
                        case
                            when inbound = 'Y' then 'INBOUND'
                            when inbound = 'N' then 'OUTBOUND'
                            else ''
                        end as inbound,

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
						ifnull(a.biaya_packing_agent,0) as biaya_packing_agent,
						ifnull(a.biaya_tambahan_agent,0) as biaya_tambahan_agent,
						ifnull(md.biaya_tambahan_agent_transit,0) as biaya_tambahan_agent_transit,
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
						) as confirm_agent_deliv_x,
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
                        LEFT JOIN negara f ON f.id_negara = a.lokasi_tujuan and a.service = 6
                    where
                        a.id_invoice = ".$row['id_invoice']." and a.status != 7 
                    group by a.id_pengiriman
                  ) x
							) tmp";



				$resDiskon = SSP::simple( NULL, $GLOBALS["sql_details"], $cekLoss, $primaryKey, $columnD );


				$totalAgentAfterDiskon = 0;
				$btnAlert = "";
				$totalPL = 0;
				foreach ($resDiskon['data'] as $res) {
					$totalPL = 0;
					//echo "<pre>";
					//print_r($res);

					$totalAgent = 0;
					$totalAgenPickup = ($res['confirm_agent_pu'] == 0) ? 0 : $res['kunci_total_agent_pickup'];
					$totalAgentDeliv = ($res['confirm_agent_deliv'] == 0) ? 0 : $res['kunci_total_agent_deliv'];
					$totalAgentTransit = ($res['confirm_agent_transit'] == 0) ? 0 : $res['kunci_total_agent_transit'];
					$totalAgentIntra = ($res['confirm_agent_intra'] == 0) ? 0 : $res['kunci_total_agent'];

					if ($res['intercity'] == 0){ //001

						if ($res['pickup_by_agent'] == $res['delivery_by_agent']){

							$totalAgent = $totalAgentIntra;
							$totalAgentDeliv = $totalAgentIntra;

						}else{

							if ($res['inter_asal'] != 'Y' && $res['inter_tujuan'] != 'Y'){ //CROSS CITY

								$totalAgent = $totalAgenPickup + $totalAgentDeliv + $totalAgentTransit;



							}else if ($res['inter_asal'] != 'Y' && $res['inter_tujuan'] == 'Y'){	//INBOUND

								$totalAgent = $totalAgenPickup + $totalAgentTransit;



							}else if ($res['inter_asal'] == 'Y' && $res['inter_tujuan'] != 'Y'){ //OUTBOUND

								$totalAgent = $totalAgentDeliv + $totalAgentTransit;


							}
						}

					}else{ //002

						$totalAgent = $totalAgenPickup + $totalAgentDeliv + $totalAgentTransit;


					}

					if ($res['confirm_agent_pu'] == 0 && $res['confirm_agent_deliv'] == 0 && $res['confirm_agent_transit'] == 0 && $res['confirm_agent_intra'] == 0){
						$new_total_diskon = 0;
						$total_agent = 0;
						$diskon = 0;
						$totalAgentAfterDiskon = 0;
					}else{
						$new_total_diskon = $totalAgent - ($res['biaya_packing_agent']+$res['biaya_tambahan_agent']+$res['biaya_tambahan_agent_transit']);
						$total_agent = $new_total_diskon;
						$diskon = $res['diskon'];
						$totalAgentAfterDiskon = ($total_agent-(($diskon/100)*$total_agent))+($res['biaya_packing_agent']+$res['biaya_tambahan_agent']+$res['biaya_tambahan_agent_transit']);
					}

					$harga_lock = ($res['kode_kustomer'] == 'CIS' || $res['kode_kustomer'] == 'DEL') ? round($res['harga_lock'],2) : round($res['harga_lock']);
					$harga_total = $harga_lock+$res['biaya_packing']+$res['biaya_asuransi'];

					$total_costing = $res['costing_pickup_fee']+$res['costing_pickup_parking']+$res['costing_pickup_toll']+$res['costing_men_power']+$res['costing_delivery_fee']+$res['costing_delivery_parking']+$res['costing_delivery_toll']+$res['costing_delivery_menpower']+$res['costing_outbound_fee']+$res['costing_outbound_toll']+$res['costing_outbound_smu']+$res['costing_outbound_handling']+$res['costing_outbound_parking']+$res['costing_outbound_menpower']+$res['costing_inbound_fee']+$res['costing_inbound_toll']+$res['costing_inbound_smu']+$res['costing_inbound_handling']+$res['costing_inbound_parking']+$res['costing_inbound_menpower']+$totalAgentAfterDiskon;

					$profit_loss = $harga_total-$total_costing;
					//$totalPL = $totalPL+$profit_loss;

					if ($profit_loss < 0) {
						//echo "LOSSX:".$harga_total."-".$total_costing;
						$totalPL++;
						break;
					}
				}

				$aksi	= "reports/PHPExcel/excel_profit-lossnew.php?id_kustomer=".$row['id_kustomer']."&idinvoice=".$row['id_invoice']."&stts=loss";
				$aksi_old	= "reports/PHPExcel/excel_profit-loss.php?id_kustomer=".$row['id_kustomer']."&idinvoice=".$row['id_invoice']."&stts=loss";

				/*return ($totalPL == 0) ? '' : '<td><center>
				<div class="btn-group">
					<button class="btn btn-primary pull-right" onclick="window.location.href = \''.$aksi.'\';"><i class="fa fa-file-excel-o"></i> Excel</button>
					<div class="btn-group">
					<button class="btn btn-primary pull-right" onclick="window.location.href = \''.$aksi_old.'\';"><i class="fa fa-file-excel-o"></i> Excel Old</button>
				</div></center></td>';*/

				return '<td><center>
				<div class="btn-group">
					<button class="btn btn-primary pull-right" onclick="window.location.href = \''.$aksi.'\';"><i class="fa fa-file-excel-o"></i> Excel</button>
					<div class="btn-group">
					<button class="btn btn-primary pull-right" onclick="window.location.href = \''.$aksi_old.'\';"><i class="fa fa-file-excel-o"></i> Excel Old</button>
				</div></center></td>';

			}),
			array( 'db' => 'send', 'dt' => 7 ),
			array( 'db' => 'confirm', 'dt' => 8 ),
			array( 'db' => 'id_invoice', 'dt' => 6, 'formatter' => function($d,$row) {
				$aksi	= "module/mod_invoice-customer/invoice-customer_action.php";
				$key	= "asdf$1011";
				$nomor = 'nomor';

				return '<td><center>
				<div class="btn-group">
					<button class="btn btn-xs btn-default" onclick="window.location.href = \'?module=profit-loss&act=detail&id='.base64_encrypt($d,$key).'\';"><i class="fa fa-file text-yellow"></i></button>
				</div></center></td>';
			}),
		);

		require('../../config/ssp.class.php');
		echo json_encode(
			SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns )
		);


	}

}
?>
