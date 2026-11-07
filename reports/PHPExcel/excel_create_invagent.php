<?php
include ('../../config/connection.php');
include ('../../config/encrypt.php');
include ('../../config/library.php');

 

$key 			= "asdf$1011";
$decrypt		= $_GET['id_invoice'];				 		
$id_invoice 	= base64_decrypt($decrypt,$key);

$query = mysqli_query($con, "select nomor,invoice_date,invoice_date_end,id_agent,status_lock,date_format(invoice_date, '%Y-%m') as inv_month from invoice_agent_status where id=$id_invoice ");

//echo "select nomor,invoice_date,invoice_date_end,id_agent,status_lock,date_format(invoice_date, '%Y-%m') as inv_month from invoice_agent_status where id=$id_invoice ";
$n = mysqli_fetch_assoc($query);

$awal = $n['invoice_date'];

$month = $n['inv_month'];

if($n['invoice_date_end'] === null || $n['invoice_date_end'] == ''){
	$akhir = $month.'-31';
}else{
	$akhir = $n['invoice_date_end'];
}


$agent = $n['id_agent'];


$invoice = isset($n["nomor"]) ? $n["nomor"] : '';
$status_lock = $n["status_lock"];

$cabang = mysqli_query($con, "select nama_cabang from cabang where id_cabang = $agent");
$c = mysqli_fetch_assoc($cabang);

$nama_cabang = $agent == 115 ? $agent : $c['nama_cabang'];

header("Content-type: application/vnd-ms-excel");
//header("Content-Disposition: attachment; filename=".$awal."_".$akhir."_".$c[nama_cabang].".xls");
//header("Content-Disposition: attachment; filename=".$awal."_".$akhir.".xls");
header("Content-Disposition: attachment; filename=".$awal."_".$akhir."_".$nama_cabang.".xls");
?>
<!DOCTYPE html>
<html>
	<head>
	<meta charset="UTF-8">
		<title>INVOICE AGENT. </title>
	</head>
	<body>

		<table cellspacing=0 width="100%">
            <tr>
                <td colspan="5" style="font-size:10px;">
                    <img src='https://cesco-logistics.com/e-cesco/dist/img/logocesco2024.png' width="272" height="75">
                </td>

                <td rowspan="4" style="font-family:Aharoni;text-align:center;font-size:26px;background-color:#000000;color:#FFFFFF;min-width:20px" colspan=4>
                    <nobreak>I N V O I C E &nbsp;&nbsp; A G E N T</nobreak><br><?php echo $invoice; ?>
                </td>

            </tr>
            <tr colspan="3">
                <td style="font-size:12px;font-weight:bold;"></td>

            </tr>
            <tr>
                <td style="font-size:12px;font-weight:bold;"></td>

            </tr>
            <tr>
                <td colspan="3" style="font-size:12px;font-weight:bold;">
                    <nobreak>CESCO LOGISTICS (PT. CESCO LOGISTIK INDONESIA) <?php echo $c['nama_cabang'].$invoice; ?></nobreak></td>

            </tr>
            <tr>
                <td colspan="3" style="font-size:10px;min-width:20px">
                    <nobreak>Cilandak Commercial Estate Kav. 402</nobreak></td>

            </tr>
            <tr>
                <td colspan="3" style="font-size:10px;min-width:20px">
                    Jl. Raya Cilandak KKO Jakarta Selatan. Kode Pos 12560  </td>

            </tr>
            <tr >


            </tr>
			<tr>
				<td colspan="10"></td>
			</tr>
			<tr>
			  <td style="min-width:50px;"colspan="3"><span style="font-family:Calibri;text-align:right;font-size:16px;color:#000000;font-weight:bold;">TANGGAL :</span></td>
			  <td  style="min-width:50px;"colspan="3"><span style="font-family:Calibri;font-size:16px;color:#000000;font-weight:bold;"><?php echo''.$awal.' - '.$akhir; ?></span></td>
		    </tr>
			<tr>
				<td style="min-width:50px;"colspan="3"><span style="font-family:Calibri;text-align:right;font-size:16px;color:#000000;font-weight:bold;">Agent :</span></td>
				<td style="min-width:50px;"colspan="3"style=min-width:50px><span style="font-family:Calibri;font-size:16px;color:#000000;font-weight:bold;"> <?php echo''.strtoupper($c['nama_cabang']).'';?></span></td>
		  </tr>
			<tr>
				<td colspan="8">&nbsp;</td>
			</tr>
			<tr>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border:1px solid;">NO</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border:1px solid;">Pickup Date</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">AWB</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">No Invoice</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">komoditi</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Service</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Custmer's Name</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Consignee</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Origin</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Destination</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Delivered by</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Tanggal diterima</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Tanggal Pickup</td>       
                <td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Tanggal Outbound</td>
                <td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Manifest Outbound</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Manifest Inbound</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Lead Time</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Reason Late</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Weight</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Price</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Min Weight</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Packing</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Add Charge</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Detail Add Charge</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Total Pickup</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Total Transit</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Total Delivery</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Diskon (%)</td>
                <td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Diskon Out (%)</td>
                <td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Diskon In (%)</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Total setelah Diskon</td>
			</tr>
			<?php
			$no=1;

            if($status_lock == 'Y'){
                $q_lock = "and (a.kunci_total_agent_pickup > 0 or a.kunci_total_agent_deliv > 0 or n.kunci_total_agent_transit > 0)";
            }else{
                $q_lock = "";
            }

			$cari = "
                            SELECT
                                a.id_pengiriman,
                                date(a.tanggal) as tanggal,
                                airwaybill_kode,
                                case when a.komoditi='1' then 'Paket' else 'Dokumen' end as komoditi,
                                x_service(a.service) as service,
                                case when a.service=5 then 3 else a.service end as srv,
                                nama_pengirim,
                                a.loc_code,
                                x_namakustomer(a.id_kustomer) as namakustomer,
                                nama_penerima,
                                a.id_kota_asal as id_asal,
                                a.id_kota_tujuan as id_tujuan,
                                a.kota_asal as lokasi_asal,
                                a.kota_tujuan as lokasi_tujuan,


                                case when pickup_by_agent =$agent then ifnull(biaya_packing_agent,0)
                                    else ifnull(biaya_packing_deliv_agent,0) end
                                as new_biaya_packing_agent_total,



                                case when pickup_by_agent =$agent and (delivery_by_agent !=$agent or delivery_by_agent is null) then ifnull(biaya_tambahan_agent,0)


                                    when delivery_by_agent=$agent and pickup_by_agent !=$agent then ifnull(a.biaya_tambahan_deliv_agent,0)

                                    when pickup_by_agent=$agent and delivery_by_agent =$agent   then ifnull(biaya_tambahan_agent,0)

                                    else ifnull(n.biaya_tambahan_agent_transit,0)

                                end as new_biaya_tambahan_agent_total,

                                kecamatan_asal,
                                kecamatan_tujuan,

                                case

                                    when pickup_by_agent != $agent   and (delivery_by_agent !=$agent or delivery_by_agent is null)    then n.kunci_total_agent_transit
                                    when pickup_by_agent =$agent     and (delivery_by_agent !=$agent or delivery_by_agent is null)    then kunci_total_agent_pickup
                                    when pickup_by_agent !=$agent    and delivery_by_agent =$agent    then kunci_total_agent_deliv
                                    when pickup_by_agent =$agent     and delivery_by_agent =$agent    then kunci_total_agent

                                end as total_kunci_harga_agent,

								case
                                	when pickup_by_agent != $agent	and (delivery_by_agent !=$agent or delivery_by_agent is null) 	then 'transit'
                                	when pickup_by_agent =$agent 	and (delivery_by_agent !=$agent or delivery_by_agent is null) 	then 'pickup'
                               		when pickup_by_agent !=$agent 	and delivery_by_agent =$agent 	then 'delivery'
                           			when pickup_by_agent =$agent 	and delivery_by_agent =$agent 	then 'pickup-delivery'
                                end as status_agent,

								ifnull(kunci_total_agent,0) as kunci_total_agent,
								ifnull(n.kunci_total_agent_transit,0) as kunci_total_agent_transit,
								ifnull(kunci_total_agent_pickup,0) as kunci_total_agent_pickup,
								ifnull(kunci_total_agent_deliv,0) as kunci_total_agent_deliv,

								if( (select total_kunci_harga_agent) > 0,1,0) as ifkunci,


                                if(pickup_by_agent=$agent,1,0) as in_out,

                                case

                                    when pickup_by_agent=$agent then 'biaya_tambahan_in'

                                    when pickup_by_agent != $agent and delivery_by_agent != $agent then 'biaya_tambahan_transit'

                                else 'biaya_tambahan'

                                end as biaya_tambahan_in_out,

                                case when pickup_by_agent != 39 and delivery_by_agent != 39
                                    then
                                        case when pickup_by_agent =$agent then 1
                                        else  0 end
                                    else
                                        0
                                end as keterangan_in_out,

                                alamat_penerima,
                                delivery_name,
                                delivery_date,
                                date(pickup_date) as pickup_date,
                                pickup_by_agent,
                                alasan_terlambat,
                                if(delivery_date is null,'-', datediff(delivery_date, date(pickup_date))) as selisih_hari,
                                SUBSTRING_INDEX(ukuran_barang,'x',1) as length, SUBSTRING_INDEX(SUBSTRING_INDEX(ukuran_barang,'x',2),'x',-1) as width, SUBSTRING_INDEX(ukuran_barang,'x',-1) as height,
                                 CASE
                                        WHEN airwaybill_kode LIKE '001-%' THEN 0
                                        WHEN airwaybill_kode LIKE '002-%' THEN 1
                                    end as intercity,
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
                                if(berat_aktual is null or berat_aktual < 1,  berat_barang, berat_aktual) as berat_aktual,
                                berat_aktual as brt_akt,
                                berat_barang as brt_brg,
                                kunci_berat_agent,
                                ifnull(ifnull(f.price_next_kg,g.price_next_kg),ifnull(d.price_next_kg,ifnull(e.price_next_kg,0))) as next_kg,
                                ifnull(ifnull(f.price,g.price),ifnull(d.price,ifnull(e.price,0))) as price,

                                ifnull(ifnull(f.price,g.price),ifnull(d.price,ifnull(e.price,0))) as priceee,

                                ifnull(  kunci_hargaperkg_agent ,  (select priceee) ) as pricee,
                                ifnull(ifnull(f.min_kg,g.min_kg),ifnull(d.min_kg,e.min_kg)) as min_kg,
                                ifnull(ifnull(f.min_kg,g.min_kg),ifnull(d.min_kg,e.min_kg)) as min_kggg,
                                ifnull(  kunci_minkg_agent ,  (select min_kggg) ) as min_kgg,
                                 ifnull (kunci_berat_agent, (select berat_real) ) as berat_tampil,

                                    if ((select min_kgg) >= (select berat_real), (select min_kgg), (select berat_real) ) as berat_finishh,

                                    ifnull(if(kunci_berat_agent< (select min_kgg) ,(select min_kgg),kunci_berat_agent),
                                            (select berat_finishh)) as berat_finish,

                                    ifnull(  if(kunci_berat_agent<(select min_kgg),(select min_kgg),kunci_berat_agent) ,  (select berat_real) ) as kunci_berat,

                                a.biaya_packing_agent,
                                a.biaya_tambahan_agent,

                                case when pickup_by_agent != 39 and delivery_by_agent != 39
                                    then
                                        case when pickup_by_agent =$agent then keterangan_biaya_in
                                        else keterangan_biaya_agent end
                                    else
                                        keterangan_biaya_agent
                                end as keterangan_biaya,


                                if(a.paid_reject = '1',if(if(berat_aktual is null or berat_aktual < 1,  berat_barang, berat_aktual) < (select min_kgg), ((select min_kgg)*(select pricee))+(select new_biaya_packing_agent_total)+(select new_biaya_tambahan_agent_total) , (if(berat_aktual is null or berat_aktual < 1,  berat_barang, berat_aktual)*(select pricee))+(select new_biaya_packing_agent_total)+(select new_biaya_tambahan_agent_total) ),0) as total,

                                (
                                    case when (select next_kg) > 0  then
                                        (select berat_finish) * (select pricee) + ( ((select berat_finish) - (select min_kgg)) * (select next_kg)  )
                                    else
                                        (select berat_finish) * (select pricee)
                                    end
                                )

                                +(select new_biaya_packing_agent_total)+(select new_biaya_tambahan_agent_total) as new_totall ,
                                if(  (select total_kunci_harga_agent) > 0 ,(select total_kunci_harga_agent) ,  (select new_totall) ) as new_total,
                                d.rateagent_id,
                                ifnull(d.rateagent_id,ifnull(e.rateagentinbound_id,f.rate_id)) as idrate,
                                d.rateagent_id as idrate_out,
                                e.rateagentinbound_id as idrate_in,
                                f.rate_id as idrate_cross,
                                g.rateagent_id as idrate_transit,
                                a.paid_reject,
                                a.reject_reason,
                                case
                                when pickup_by_agent = $agent and delivery_by_agent <> $agent THEN
                                    case when terlambat_pickup in (1,2) then
                                        a.diskon_pickup
                                    else 0
                                    end
                                when delivery_by_agent = $agent and pickup_by_agent <> $agent THEN
                                    case when terlambat_delivery in (1,2) then
                                        a.diskon_deliv
                                    else 0
                                    end
                                else
                                    case when terlambat_pickup in (1,2) and terlambat_delivery in (1,2) then
                                            a.diskon_pickup+a.diskon_deliv
                                        when terlambat_pickup in (1,2) and terlambat_delivery not in (1,2) then
                                            a.diskon_pickup
                                        when terlambat_pickup not in (1,2) and terlambat_delivery in (1,2) then
                                            a.diskon_deliv
                                    else 0
                                    end
                                end as diskon, kunci_hargaperkg_agent,
                                getManifestCode(a.id_pengiriman, a.pickup_by_agent, 'OUT') as manifest_outbound,
                                getManifestCode(a.id_pengiriman, a.delivery_by_agent, 'IN') as manifest_inbound,
																i.nomor as invoice,
                                                                o.cs_number,
                                                                a.biaya_beban,
                                m.tanggal as tanggal_outbound,
                                n.inbound_received as tanggal_inbound,
                                n.diskon_outbound as diskon_outbound,
                                n.diskon_inbound as diskon_inbound

                            FROM pengiriman a

                            LEFT JOIN rate_agent d ON d.id_kota=id_kecamatan_tujuan AND d.id_pelayanan=a.service and a.delivery_by_agent = $agent and d.id_cabang=$agent

                            LEFT JOIN rate_agent_inbound e ON e.id_kota=id_kecamatan_asal  and e.id_pelayanan=a.service and a.pickup_by_agent = $agent and e.id_cabang = $agent

                            LEFT JOIN rate_agent_cross f ON f.id_kota_asal = a.id_kecamatan_asal and f.id_kota_tujuan = a.id_kecamatan_tujuan  AND a.pickup_by_agent=$agent and a.delivery_by_agent = $agent and f.id_agent = $agent

                            LEFT JOIN pembayaran_or o on a.airwaybill_kode=o.airwaybill
                            
                            inner JOIN manifest_detail n ON a.id_pengiriman = n.id_pengiriman 
                            and n.id_manifest =

                            
                            
                                    (select man.id_manifest from manifest man
                                        inner join (select id_pengiriman,id_manifest from manifest_detail) md 
                                            on md.id_manifest = man.id_manifest and md.id_pengiriman = (select a.id_pengiriman)

                                        where 
                                        
                                         case when
                                            a.delivery_by_agent = $agent
                                            then man.manifest_to
                                        else
                                            man.manifest_from
                                        end

                                         =$agent  limit 1)
                                

                            inner JOIN manifest m ON m.id_manifest = n.id_manifest

                            LEFT JOIN rate_agent g ON g.id_kota=id_kecamatan_tujuan AND g.id_pelayanan=a.service and m.manifest_from = $agent and g.id_cabang=$agent and pickup_by_agent != $agent and delivery_by_agent != $agent

														LEFT JOIN invoice i ON i.id_invoice = a.id_invoice

                            WHERE (
														case when a.tanggal_agent is null and tanggal_deliv_agent is not null and delivery_by_agent = $agent then
															a.tanggal_deliv_agent BETWEEN '$awal' AND '$akhir'

															when a.tanggal_agent is null and tanggal_pickup_agent is not null and pickup_by_agent = $agent then
															a.tanggal_pickup_agent BETWEEN '$awal' AND '$akhir'

														 when a.tanggal_agent is not null then
															a.tanggal_agent BETWEEN '$awal' AND '$akhir'
														else a.tanggal BETWEEN '$awal' AND '$akhir'
														end

														and a.service != 6 and a.status != 7 ) and ( pickup_by_agent = $agent or delivery_by_agent = $agent /*and tanggal_agent is null*/)
                                                        or (m.manifest_from =$agent and
                                                       
                            case when n.tanggal_tagihan_agent is not null then
                                n.tanggal_tagihan_agent BETWEEN '$awal' AND '$akhir'
                            else n.created_date BETWEEN '$awal' AND '$akhir'
														end
														and a.service != 6 and a.status != 7 and  pickup_by_agent != $agent and delivery_by_agent != $agent)
                                                        $q_lock
														group by a.id_pengiriman
                            ";

			//echo $cari;
			$sum_total = 0;
			$result = mysqli_query($con, $cari);
			while($t=mysqli_fetch_assoc($result)){
				$new_total = ($t['paid_reject'] == 1 ? $t['new_total'] : 0);
				//$new_total = $t['new_total'];
                //$sum_total = $sum_total + $new_total;

				$totalAgent = 0;
				$totalAgenPickup = $t['kunci_total_agent_pickup'];
				$totalAgentDeliv = $t['kunci_total_agent_deliv'];
				$totalAgentTransit = $t['kunci_total_agent_transit'];
				$totalAgentPickupDeliv = $t['kunci_total_agent'];

				$sttsAgent = $t['status_agent'];
				if ($sttsAgent == 'pickup-delivery'){
					$totalAgenPickup = 0;
					$totalAgentTransit = 0;
					$totalAgentDeliv = $new_total;
					$totalAgent = $totalAgentDeliv;
				}else{
					if ($sttsAgent == 'pickup'){
						$totalAgenPickup = $new_total;
						$totalAgentDeliv = 0;
						$totalAgentTransit = 0;
						$totalAgent = $totalAgenPickup;
					}
					else if ($sttsAgent == 'transit') {
						$totalAgentTransit = $new_total;
						$totalAgenPickup = 0;
						$totalAgentDeliv = 0;
						$totalAgent = $totalAgentTransit;
					}
					else if ($sttsAgent == 'delivery'){
						$totalAgentDeliv = $new_total;
						$totalAgenPickup = 0;
						$totalAgentTransit = 0;
						$totalAgent = $totalAgentDeliv;
					}
				}

                $new_total_diskon = $totalAgent - ($t['new_biaya_packing_agent_total']+$t['new_biaya_tambahan_agent_total']);
				$total = $new_total_diskon;
				$diskon = $t['diskon'];
                $diskon_outbound = $t['diskon_outbound'];
				if ($t['paid_reject'] != 0){			
					$totalAfterDiskon = ($total-(($diskon/100)*$total) - ($diskon_outbound/100*$total) )+($t['new_biaya_packing_agent_total']+$t['new_biaya_tambahan_agent_total']);
				}else{
					$totalAfterDiskon = 0;
				}

				if ($t['paid_reject'] != 0){
					$sum_total = $sum_total + $totalAfterDiskon;
				}



				echo'
				<tr>
					<td style="font-family:Calibri;text-align:center;font-size:14px;font-color:#000000;border-color:#000;border:1px solid;">'.$no.'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['tanggal'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['airwaybill_kode'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.($t['invoice'] == '' ? $t['cs_number'] : $t['invoice']).'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['komoditi'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['service'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['namakustomer'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['nama_penerima'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['kecamatan_asal'].', '.$t['lokasi_asal'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['kecamatan_tujuan'].', '.$t['lokasi_tujuan'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['delivery_name'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['delivery_date'].'</td>
                    <td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['pickup_date'].'</td>
                    <td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['tanggal_outbound'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['manifest_outbound'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['manifest_inbound'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['selisih_hari'].' hari</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['alasan_terlambat'].' </td>

					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['berat_tampil'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['pricee'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['min_kg'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['biaya_packing_agent'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['new_biaya_tambahan_agent_total'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['keterangan_biaya'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$totalAgenPickup.'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$totalAgentTransit.'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$totalAgentDeliv.'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['diskon'].'</td>
                    <td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['diskon_outbound'].'</td>
                    <td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['diskon_inbound'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$totalAfterDiskon.'</td>
				</tr>
				';
			$no++;
			}

			$sqlBA = "select ifnull(biaya_admin,0) as biaya_admin from invoice_agent_status where id = ".$id_invoice." ";
			$qrBA = mysqli_query($con, $sqlBA);
			$resBA = mysqli_fetch_assoc($qrBA);

			$biayaAdmin = ($resBA['biaya_admin']) ? $resBA['biaya_admin'] : 0;
			?>
			<tr>
				<td colspan="30" style="font-family:Calibri;text-align:right;font-size:16px;color:#000000;font-weight:bold;" align="right">Subtotal</td>
				<td  style="font-family:Calibri;text-align:right;font-size:16px;color:#000000;font-weight:bold;" align="right"><?php echo 'Rp.'.number_format($sum_total); ?></td>
			</tr>
			<tr>
				<td colspan="30" style="font-family:Calibri;text-align:right;font-size:16px;color:#000000;font-weight:bold;" align="right">Biaya Admin</td>
				<td  style="font-family:Calibri;text-align:right;font-size:16px;color:#000000;font-weight:bold;" align="right"><?php echo 'Rp.'.number_format($biayaAdmin); ?></td>
			</tr>
			<tr>
				<td colspan="30" style="font-family:Calibri;text-align:right;font-size:16px;color:#000000;font-weight:bold;" align="right">Total</td>
				<td  style="font-family:Calibri;text-align:right;font-size:16px;color:#000000;font-weight:bold;" align="right"><?php echo 'Rp.'.number_format($sum_total+$biayaAdmin); ?></td>
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
