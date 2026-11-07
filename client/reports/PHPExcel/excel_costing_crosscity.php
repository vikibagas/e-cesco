<?php
include ('../../config/connection.php');
include ('../../config/encrypt.php');
include ('../../config/library.php');

$key = "asdf$1011";
$encrypt = "$_GET[id]";
$id = base64_decrypt($encrypt,$key);


$query = mysqli_query($con, "select a.id_manifest, a.tanggal, DATE_FORMAT(a.tanggal,'%Y-%m-%d') as tgloutbound, a.kode_manifest, x_user(a.created_by) as manifest_by, case when x_intercity(b.id_kota)='Y' then 'JAKARTA' else b.nama_cabang end as manifest_from, case when x_intercity(b.id_kota)='Y' then 'CESCO LOGISTICS' else b.alamat1 end as manifestfrom, case when x_intercity(c.id_kota)='Y' then 'JAKARTA' else c.nama_cabang end as manifest_to, case when x_intercity(c.id_kota)='Y' then 'CESCO LOGISTICS' else c.alamat1 end as manifestto,
case when a.manifest_via = 1 then 'AMTL' else a.manifest_via end as manifest_via,
a.no_smu from manifest a inner join cabang b on b.id_cabang=a.manifest_from inner join cabang c on c.id_cabang=a.manifest_to  where a.id_manifest='$id'");
$r = mysqli_fetch_assoc($query);

$result = mysqli_query($con,"select sum(coalesce(a.costing_outbound_fee,0)) + sum(coalesce(a.costing_outbound_parking,0)) + sum(coalesce(a.costing_outbound_toll,0)) + sum(coalesce(a.costing_outbound_menpower,0)) + sum(coalesce(a.costing_outbound_smu,0)) + sum(coalesce(a.costing_outbound_handling,0)) as total_cost, a.costing_outbound_save, sum(coalesce(a.costing_outbound_fee,0)) as costing_outbound_fee, sum(coalesce(a.costing_outbound_parking,0)) as costing_outbound_parking, sum(coalesce(a.costing_outbound_toll,0)) as costing_outbound_toll, sum(coalesce(a.costing_outbound_menpower,0)) as costing_outbound_menpower, sum(coalesce(a.costing_outbound_smu,0)) as costing_outbound_smu, sum(coalesce(a.costing_outbound_handling,0)) as costing_outbound_handling from pengiriman a inner join manifest_detail b on a.id_pengiriman = b.id_pengiriman where b.id_manifest='$r[id_manifest]' and x_intercity(a.lokasi_asal) ='Y' and x_intercity(a.lokasi_tujuan)!='Y' and a.id_cabang in (11,16,39) ");
$r_cost = mysqli_fetch_assoc($result);

$tanggal = tgl_indo($r['tanggal']);

header("Content-type: application/vnd-ms-excel");
header('Content-Disposition: attachment; filename="costing_crosscity_'.$r['kode_manifest'].'.xls"');

?>
<!DOCTYPE html>
<html>
	<head>
	<meta charset="UTF-8">
		<title>Manifest Cross City</title>
	</head>
	<body>
		<table cellspacing=0 width="100%">
			<tr>
				<td style="font-family:Calibri;text-align:center;font-size:16px;color:#000000;font-weight:bold;" colspan="8">MANIFEST CROSS CITY</td>
			</tr>
			<tr>
				<td style="font-family:Calibri;text-align:center;font-size:16px;color:#000000;font-weight:bold;" colspan="8">MANIFEST CODE : <?php echo $r['kode_manifest']; ?></td>
		  	</tr>
			<tr>
				<td colspan="11"></td>
			</tr>
			<tr>
			  <td colspan="2" style="font-family:Calibri;font-size:14px;font-weight:bold;color:#000000;">Date</td>
			  <td colspan="6" style="font-family:Calibri;font-size:14px;font-weight:bold;color:#000000;">: <?php echo''.$tanggal.'';?></td>
		    </tr>
			<tr>
				<td colspan="2" style="font-family:Calibri;font-size:14px;font-weight:bold;color:#000000;">Manifest From</td>
				<td colspan="6" style="font-family:Calibri;font-size:14px;font-weight:bold;color:#000000;">: <?php echo''.strtoupper($r['manifest_from']).'';?></td>
		    </tr>
			<tr>
				<td colspan="2" style="font-family:Calibri;font-size:14px;font-weight:bold;color:#000000;">Manifest to</td>
				<td colspan="6" style="font-family:Calibri;font-size:14px;font-weight:bold;color:#000000;">: <?php echo''.strtoupper($r['manifest_to']).'';?></td>
		    </tr>
		    <tr>
			  <td colspan="2" style="font-family:Calibri;font-size:14px;font-weight:bold;color:#000000;">VIA</td>
              <td colspan="6" style="font-family:Calibri;font-size:14px;font-weight:bold;color:#000000;">: <?php echo strtoupper($r['manifest_via']) ?></td>
		    </tr>
			<tr>
			  <td colspan="2" style="font-family:Calibri;font-size:14px;font-weight:bold;color:#000000;">NO. SMU/RESI</td>
              <td colspan="6" style="font-family:Calibri;font-size:14px;font-weight:bold;color:#000000;">: <?php echo''.$r['no_smu'].'';?></td>
		  </tr>
			<tr>
				<td colspan="8">&nbsp;</td>
			</tr>
			<tr>
				<td width="5%" style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border:1px solid;">NO</td>
				<td width="11%" style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">AIRWAYBILL</td>
				<td width="28%" style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">CUSTOMER</td>
				<td width="28%" style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Asal</td>
				<td width="28%" style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Tujuan</td>
				<td width="17%" style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Total Cost Origin</td>
				<td width="17%" style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Total Cost Destination</td>
				<td width="17%" style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Total Cost SMU</td>
                <td width="17%" style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Total Cost</td>
			</tr>
			<?php
			$no=1;

			//$sql = "select b.id_pengiriman as id_pengiriman,  b.costing_outbound_fee as costing_outbound_fee, b.costing_outbound_parking as costing_outbound_parking, b.costing_outbound_toll as costing_outbound_toll, b.costing_outbound_menpower as costing_outbound_menpower, b.costing_outbound_smu as costing_outbound_smu, b.costing_outbound_handling as costing_outbound_handling, airwaybill_kode, x_namakustomer(id_kustomer) as kustomer, x_namakotabesar(lokasi_asal) as lokasi_asal, x_namakotabesar(lokasi_tujuan) as lokasi_tujuan, collie, coalesce(berat_aktual,berat_barang) as berat_barang, case when komoditi='1' then 'Paket' else 'Dokumen' end as komoditi, x_service(service) as service, ifnull(b.costing_outbound_fee, 0) + ifnull(b.costing_outbound_parking, 0) + ifnull(b.costing_outbound_toll, 0) + ifnull(b.costing_outbound_menpower, 0) + ifnull(b.costing_outbound_smu, 0) + ifnull(costing_outbound_handling, 0) as total_cost from manifest_detail a left join pengiriman b on b.id_pengiriman=a.id_pengiriman where a.id_manifest='$r[id_manifest]'  order by created_date asc";

			$sql = "select
						a.id_manifest_detail,
						b.id_pengiriman as id_pengiriman,
						b.costing_outbound_fee as costing_outbound_fee,
						b.costing_outbound_parking as costing_outbound_parking,
						b.costing_outbound_toll as costing_outbound_toll,
						b.costing_outbound_menpower as costing_outbound_menpower,
						b.costing_outbound_smu as costing_outbound_smu,
						b.costing_outbound_handling as costing_outbound_handling,
						airwaybill_kode,
						x_namakustomer(b.id_kustomer) as kustomer,
						b.kota_asal as lokasi_asal,
						b.kota_tujuan as lokasi_tujuan,
						/*x_namakotabesar(lokasi_asal) as lokasi_asal,
						x_namakotabesar(lokasi_tujuan) as lokasi_tujuan,*/
					cs.kode_kustomer,
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
							if(b.service = 5,
								((select length) * (select width) * (select height) / 4000),
								((select length) * (select width) * (select height) / 6000)))
						else
							if((select intercity) = 1,
								ceiling((select length) * (select width) * (select height) / 4000),
								if(b.service = 5,
									ceiling((select length) * (select width) * (select height) / 4000),
									ceiling((select length) * (select width) * (select height) / 6000)))
						end as volume,
						collie,
						if(berat_aktual > 0,
							berat_aktual,
							berat_barang) as berat_barang,
						case
							when komoditi = '1' then 'Paket'
							else 'Dokumen'
						end as komoditi,
						x_service(service) as service,
						b.pickup_by_agent,
						b.delivery_by_agent
					from
						manifest_detail a
							left join
						pengiriman b ON b.id_pengiriman = a.id_pengiriman
					left join kustomer cs on cs.id_kustomer = b.id_kustomer
					where
						a.id_manifest = '".$r['id_manifest']."'
					group by a.id_pengiriman
					order by b.created_date asc";

			$result = mysqli_query($con,$sql);
			$grandTotal = 0;

			while($t=mysqli_fetch_assoc($result)){
				$totalCost = 0;

                $agentOrigin = $t['pickup_by_agent'];
                $agentDestination = $t['delivery_by_agent'];

                $cariOrigin = "
                                    SELECT
                                        a.id_pengiriman,
                                        case when pickup_by_agent =$agentOrigin then ifnull(biaya_packing_agent,0)
                                            else ifnull(biaya_packing_deliv_agent,0) end
                                        as new_biaya_packing_agent_total,
        
        
        
                                        case when pickup_by_agent =$agentOrigin and (delivery_by_agent !=$agentOrigin or delivery_by_agent is null) then ifnull(biaya_tambahan_agent,0)
        
        
                                            when delivery_by_agent=$agentOrigin and pickup_by_agent !=$agentOrigin then ifnull(a.biaya_tambahan_deliv_agent,0)
        
                                            when pickup_by_agent=$agentOrigin and delivery_by_agent =$agentOrigin   then ifnull(biaya_tambahan_agent,0)
        
                                            else ifnull(n.biaya_tambahan_agent_transit,0)
        
                                        end as new_biaya_tambahan_agent_total,
        
                                        ifnull(kunci_total_agent,0) as kunci_total_agent,
                                        case
                                            when pickup_by_agent != $agentOrigin	and (delivery_by_agent !=$agentOrigin or delivery_by_agent is null) 	then ifnull(n.kunci_total_agent_transit,0)
                                            when pickup_by_agent =$agentOrigin 	and (delivery_by_agent !=$agentOrigin or delivery_by_agent is null) 	then ifnull(kunci_total_agent_pickup,0)
                                            when pickup_by_agent !=$agentOrigin 	and delivery_by_agent =$agentOrigin 	then ifnull(kunci_total_agent_deliv,0)
                                            when pickup_by_agent =$agentOrigin 	and delivery_by_agent =$agentOrigin 	then ifnull(kunci_total_agent,0)
                                        end as total_kunci_harga_agent,
                                        case
                                            when pickup_by_agent != $agentOrigin	and (delivery_by_agent !=$agentOrigin or delivery_by_agent is null) 	then 'transit'
                                            when pickup_by_agent = $agentOrigin 	and (delivery_by_agent !=$agentOrigin or delivery_by_agent is null) 	then 'pickup'
                                            when pickup_by_agent != $agentOrigin 	and delivery_by_agent =$agentOrigin 	then 'delivery'
                                            when pickup_by_agent = $agentOrigin 	and delivery_by_agent =$agentOrigin 	then 'pickup-delivery'
                                        end as status_agent,
                                        ifnull(n.kunci_total_agent_transit,0) as kunci_total_agent_transit,
                                        ifnull(kunci_total_agent_pickup,0) as kunci_total_agent_pickup,
                                        ifnull(kunci_total_agent_deliv,0) as kunci_total_agent_deliv,
                                        if( (select total_kunci_harga_agent) > 0,1,0) as ifkunci,
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
                                        case
                                        when pickup_by_agent = $agentOrigin and delivery_by_agent <> $agentOrigin THEN
                                            case when terlambat_pickup in (1,2) then
                                                ifnull(a.diskon_pickup,0)
                                            else 0
                                            end
                                        when delivery_by_agent = $agentOrigin and pickup_by_agent <> $agentOrigin THEN
                                            case when terlambat_delivery in (1,2) then
                                                ifnull(a.diskon_deliv,0)
                                            else 0
                                            end
                                        else
                                            case when terlambat_pickup in (1,2) and terlambat_delivery in (1,2) then
                                                    ifnull(a.diskon_pickup,0)+ifnull(a.diskon_deliv,0)
                                                when terlambat_pickup in (1,2) and terlambat_delivery not in (1,2) then
                                                    ifnull(a.diskon_pickup,0)
                                                when terlambat_pickup not in (1,2) and terlambat_delivery in (1,2) then
                                                    ifnull(a.diskon_deliv,0)
                                            else 0
                                            end
                                        end as diskon, kunci_hargaperkg_agent
                                    FROM pengiriman a
        
                                    LEFT JOIN rate_agent d ON d.id_kota=id_kecamatan_tujuan AND d.id_pelayanan=a.service and a.delivery_by_agent = $agentOrigin and d.id_cabang=$agentOrigin
        
                                    LEFT JOIN rate_agent_inbound e ON e.id_kota=id_kecamatan_asal  and e.id_pelayanan=a.service and a.pickup_by_agent = $agentOrigin and e.id_cabang = $agentOrigin
        
                                    LEFT JOIN rate_agent_cross f ON f.id_kota_asal = a.id_kecamatan_asal and f.id_kota_tujuan = a.id_kecamatan_tujuan  AND a.pickup_by_agent=$agentOrigin and a.delivery_by_agent = $agentOrigin and f.id_agent = $agentOrigin
        
                                    LEFT JOIN manifest_detail n ON a.id_pengiriman = n.id_pengiriman
        
                                    LEFT JOIN manifest m ON n.id_manifest = m.id_manifest
        
                                    LEFT JOIN rate_agent g ON g.id_kota=id_kecamatan_tujuan AND g.id_pelayanan=a.service and m.manifest_from = $agentOrigin and g.id_cabang=$agentOrigin
        
                                    WHERE a.id_pengiriman = '$t[id_pengiriman]' 
                                    group by a.id_pengiriman
                                    ";


                $sum_total = 0;
                $emptyPrice = 0;
                $totalAfterDiskonOrigin = 0;
                $new_total_diskon = 0;
                $total = 0;
                $diskon = 0;

                $resOrigin = mysqli_query($con,$cariOrigin);

                while($rowOrigin=mysqli_fetch_assoc($resOrigin)){
                    $new_total = ($rowOrigin['paid_reject'] == 1 ? $rowOrigin['new_total'] : 0);
                    //$sum_total = $sum_total + $new_total;

                    $key = "asdf$1011";
                    $stringawal = $rowOrigin["id_pengiriman"];
                    $id = base64_encrypt($stringawal, $key);

                    $totalAgent = 0;
                    $totalAgenPickup = $rowOrigin['kunci_total_agent_pickup'];
                    $totalAgentDeliv = $rowOrigin['kunci_total_agent_deliv'];
                    $totalAgentTransit = $rowOrigin['kunci_total_agent_transit'];
                    $totalAgentPickupDeliv = $rowOrigin['kunci_total_agent'];


                    $sttsAgent = $rowOrigin['status_agent'];
                    if ($sttsAgent == 'pickup-delivery') {
                        $totalAgenPickup = 0;
                        $totalAgentTransit = 0;
                        $totalAgentDeliv = $rowOrigin['new_total'];
                        $totalAgent = $totalAgentDeliv;
                    } else {
                        if ($sttsAgent == 'pickup') {
                            $totalAgenPickup = $rowOrigin['new_total'];
                            $totalAgentDeliv = 0;
                            $totalAgentTransit = 0;
                            $totalAgent = $totalAgenPickup;
                        } else if ($sttsAgent == 'transit') {
                            $totalAgentTransit = $rowOrigin['new_total'];
                            $totalAgenPickup = 0;
                            $totalAgentDeliv = 0;
                            $totalAgent = $totalAgentTransit;
                        } else if ($sttsAgent == 'delivery') {
                            $totalAgentDeliv = $rowOrigin['new_total'];
                            $totalAgenPickup = 0;
                            $totalAgentTransit = 0;
                            $totalAgent = $totalAgentDeliv;
                        }
                    }


                    ////

                    $new_total_diskon = $totalAgent - ($rowOrigin['new_biaya_packing_agent_total'] + $rowOrigin['new_biaya_tambahan_agent_total']);
                    $total = $new_total_diskon;
                    $diskon = $rowOrigin['diskon'];
                    $totalAfterDiskonOrigin = ($total - (($diskon / 100) * $total)) + ($rowOrigin['new_biaya_packing_agent_total'] + $rowOrigin['new_biaya_tambahan_agent_total']);

                }

                /// ///////////

                $cariDest = "
                                    SELECT
                                        a.id_pengiriman,
                                        case when pickup_by_agent =$agentDestination then ifnull(biaya_packing_agent,0)
                                            else ifnull(biaya_packing_deliv_agent,0) end
                                        as new_biaya_packing_agent_total,
        
        
        
                                        case when pickup_by_agent =$agentDestination and (delivery_by_agent !=$agentDestination or delivery_by_agent is null) then ifnull(biaya_tambahan_agent,0)
        
        
                                            when delivery_by_agent=$agentDestination and pickup_by_agent !=$agentDestination then ifnull(a.biaya_tambahan_deliv_agent,0)
        
                                            when pickup_by_agent=$agentDestination and delivery_by_agent =$agentDestination   then ifnull(biaya_tambahan_agent,0)
        
                                            else ifnull(n.biaya_tambahan_agent_transit,0)
        
                                        end as new_biaya_tambahan_agent_total,
        
                                        ifnull(kunci_total_agent,0) as kunci_total_agent,
                                        case
                                            when pickup_by_agent != $agentDestination	and (delivery_by_agent !=$agentDestination or delivery_by_agent is null) 	then ifnull(n.kunci_total_agent_transit,0)
                                            when pickup_by_agent =$agentDestination 	and (delivery_by_agent !=$agentDestination or delivery_by_agent is null) 	then ifnull(kunci_total_agent_pickup,0)
                                            when pickup_by_agent !=$agentDestination 	and delivery_by_agent =$agentDestination 	then ifnull(kunci_total_agent_deliv,0)
                                            when pickup_by_agent =$agentDestination 	and delivery_by_agent =$agentDestination 	then ifnull(kunci_total_agent,0)
                                        end as total_kunci_harga_agent,
                                        case
                                            when pickup_by_agent != $agentDestination	and (delivery_by_agent !=$agentDestination or delivery_by_agent is null) 	then 'transit'
                                            when pickup_by_agent = $agentDestination 	and (delivery_by_agent !=$agentDestination or delivery_by_agent is null) 	then 'pickup'
                                            when pickup_by_agent != $agentDestination 	and delivery_by_agent =$agentDestination 	then 'delivery'
                                            when pickup_by_agent = $agentDestination 	and delivery_by_agent =$agentDestination 	then 'pickup-delivery'
                                        end as status_agent,
                                        ifnull(n.kunci_total_agent_transit,0) as kunci_total_agent_transit,
                                        ifnull(kunci_total_agent_pickup,0) as kunci_total_agent_pickup,
                                        ifnull(kunci_total_agent_deliv,0) as kunci_total_agent_deliv,
                                        if( (select total_kunci_harga_agent) > 0,1,0) as ifkunci,
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
                                        case
                                        when pickup_by_agent = $agentDestination and delivery_by_agent <> $agentDestination THEN
                                            case when terlambat_pickup in (1,2) then
                                                ifnull(a.diskon_pickup,0)
                                            else 0
                                            end
                                        when delivery_by_agent = $agentDestination and pickup_by_agent <> $agentDestination THEN
                                            case when terlambat_delivery in (1,2) then
                                                ifnull(a.diskon_deliv,0)
                                            else 0
                                            end
                                        else
                                            case when terlambat_pickup in (1,2) and terlambat_delivery in (1,2) then
                                                    ifnull(a.diskon_pickup,0)+ifnull(a.diskon_deliv,0)
                                                when terlambat_pickup in (1,2) and terlambat_delivery not in (1,2) then
                                                    ifnull(a.diskon_pickup,0)
                                                when terlambat_pickup not in (1,2) and terlambat_delivery in (1,2) then
                                                    ifnull(a.diskon_deliv,0)
                                            else 0
                                            end
                                        end as diskon, kunci_hargaperkg_agent
                                    FROM pengiriman a
        
                                    LEFT JOIN rate_agent d ON d.id_kota=id_kecamatan_tujuan AND d.id_pelayanan=a.service and a.delivery_by_agent = $agentDestination and d.id_cabang=$agentDestination
        
                                    LEFT JOIN rate_agent_inbound e ON e.id_kota=id_kecamatan_asal  and e.id_pelayanan=a.service and a.pickup_by_agent = $agentDestination and e.id_cabang = $agentDestination
        
                                    LEFT JOIN rate_agent_cross f ON f.id_kota_asal = a.id_kecamatan_asal and f.id_kota_tujuan = a.id_kecamatan_tujuan  AND a.pickup_by_agent=$agentDestination and a.delivery_by_agent = $agentDestination and f.id_agent = $agentDestination
        
                                    LEFT JOIN manifest_detail n ON a.id_pengiriman = n.id_pengiriman
        
                                    LEFT JOIN manifest m ON n.id_manifest = m.id_manifest
        
                                    LEFT JOIN rate_agent g ON g.id_kota=id_kecamatan_tujuan AND g.id_pelayanan=a.service and m.manifest_from = $agentDestination and g.id_cabang=$agentDestination
        
                                    WHERE a.id_pengiriman = '$t[id_pengiriman]' 
                                    group by a.id_pengiriman
                                    ";


                $sum_total = 0;
                $emptyPrice = 0;
                $totalAfterDiskonDest = 0;

                $new_total_diskon = 0;
                $total = 0;
                $diskon = 0;

                $resDest = mysqli_query($con,$cariDest);

                while($rowDest=mysqli_fetch_assoc($resDest)){

                    $new_total = ($rowDest['paid_reject'] == 1 ? $rowDest['new_total'] : 0);
                    //$sum_total = $sum_total + $new_total;

                    $key = "asdf$1011";
                    $stringawal = $rowDest["id_pengiriman"];

                    $totalAgent = 0;
                    $totalAgenPickup = $rowDest['kunci_total_agent_pickup'];
                    $totalAgentDeliv = $rowDest['kunci_total_agent_deliv'];
                    $totalAgentTransit = $rowDest['kunci_total_agent_transit'];
                    $totalAgentPickupDeliv = $rowDest['kunci_total_agent'];


                    $sttsAgent = $rowDest['status_agent'];
                    if ($sttsAgent == 'pickup-delivery') {
                        $totalAgenPickup = 0;
                        $totalAgentTransit = 0;
                        $totalAgentDeliv = $rowDest['new_total'];
                        $totalAgent = $totalAgentDeliv;
                    } else {
                        if ($sttsAgent == 'pickup') {
                            $totalAgenPickup = $rowDest['new_total'];
                            $totalAgentDeliv = 0;
                            $totalAgentTransit = 0;
                            $totalAgent = $totalAgenPickup;
                        } else if ($sttsAgent == 'transit') {
                            $totalAgentTransit = $rowDest['new_total'];
                            $totalAgenPickup = 0;
                            $totalAgentDeliv = 0;
                            $totalAgent = $totalAgentTransit;
                        } else if ($sttsAgent == 'delivery') {
                            $totalAgentDeliv = $rowDest['new_total'];
                            $totalAgenPickup = 0;
                            $totalAgentTransit = 0;
                            $totalAgent = $totalAgentDeliv;
                        }
                    }


                    ////

                    $new_total_diskon = $totalAgent - ($rowDest['new_biaya_packing_agent_total'] + $rowDest['new_biaya_tambahan_agent_total']);
                    $total = $new_total_diskon;
                    $diskon = $rowDest['diskon'];
                    $totalAfterDiskonDest = ($total - (($diskon / 100) * $total)) + ($rowDest['new_biaya_packing_agent_total'] + $rowDest['new_biaya_tambahan_agent_total']);

                }


                $totalCost = $totalAfterDiskonOrigin + $totalAfterDiskonDest + $t['costing_outbound_smu'];
                $grandTotal = $grandTotal + $totalCost;

				echo'
				<tr>
					<td style="font-family:Calibri;text-align:center;font-size:14px;font-color:#000000;border-color:#000;border:1px solid;">'.$no.'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['airwaybill_kode'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['kustomer'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['lokasi_asal'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['lokasi_tujuan'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.round($totalAfterDiskonOrigin).'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.round($totalAfterDiskonDest).'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.round($t['costing_outbound_smu']).'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.round($totalCost).'</td>

				</tr>';
			$no++;
			}
			?>
			<tr>
				<td colspan="8" align="right">Total</td>
				<td style="border-color:#000;border:1px solid;" align="center"><?php echo 'Rp.'.round($grandTotal); ?></td>
			</tr>
			<tr>
			  <td><div align="center"></div></td>
			  <td><div align="center"></div></td>
			  <td><div align="center"></div></td>
			  <td><div align="center"></div></td>
			  <td><div align="center"></div></td>
			  <td><div align="center"></div></td>
			  <td><div align="center"></div></td>
			  <td><div align="center"></div></td>
		  </tr>
		</table>

	</body>
</html>
