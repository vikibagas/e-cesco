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
	$key 		= "asdf$1011";
	$user 		= $_SESSION['id_user'];
	$id_user 	= base64_decrypt($user,$key);
	
	$aksi="module/mod_invoice-customer/invoice-customer_action.php";

	if ($module=='invoice-agent-compare' and $act=='compare')
	{

        $agent_by = $_POST['agent'];
		$month = $_POST['tanggal'];
		$fileDoc = $_FILES['xlsFile']['name'];
		$newFileName = __DIR__ . '/../../upload/compare/'.$id_user . '-' . $fileDoc;

		
		$result = array();
		
		if (move_uploaded_file($_FILES['xlsFile']['tmp_name'], $newFileName)){						
						
			include 'SimpleXLSX.php';
						
			$xlsx = SimpleXLSX::parse($newFileName);
			
			if ($xlsx) {

				$x = 0;
				$posErr = '';
				$idAgent = '';
                $cekAgent = 0;
				$arrAWB = array();
				
				foreach ($xlsx->rows() as $row){

					if ($x == 0){ //Cek Format
						$errFormat = 0;
						if (count($row) != 7){

							$errFormat++;

							$posErr = 'Column Count: '.count($row);

						}else{

							if ($row[0] != "AWB" ||
								$row[1] != "SERVICE" ||
								$row[2] != "KOTA ASAL" ||
								$row[3] != "KOTA TUJUAN" ||
								$row[4] != "BERAT" ||
								$row[5] != "HARGA" ||
								$row[6] != "TOTAL"
								) {

								$errFormat++;
								$posErr = 'Column Header';
							}
						}

						if ($errFormat > 0) {
							
							$resCek = array(
								'AWB' => 'Data Error '.$errFormat.': '.$posErr,
								'SERVICE' => '',
								'KOTA_ASAL' => '',
								'KOTA_TUJUAN' => '',
								'BERAT' => '',
								'HARGA' => '',
								'TOTAL' => '',
								'Cek' => 'FALSE'
							);
							
							array_push($result, $resCek);

						}
					}
					else{
						
						$awb = $row[0];
                        $service = $row[1];
						$asal = $row[2];
						$tujuan = $row[3];
						$berat = $row[4];
						$harga = $row[5];
						$totalHarga = $row[6];
                        //$agent_by = $agent;
						$arrAWB[] = $awb;
						
				        $sql = "
							SELECT
								a.id_pengiriman,
								a.id_kustomer,
                                airwaybill_kode,
                                x_service(a.service) as service,
                                if(a.id_kota_tujuan > 0, a.kota_asal, x_namakotabesar(lokasi_asal)) as lokasi_asal,
								if(a.id_kota_tujuan > 0, a.kota_tujuan, x_namakotabesar(lokasi_tujuan)) as lokasi_tujuan, 
                                case when pickup_by_agent =$agent_by then ifnull(biaya_packing_agent,0)
                                	else ifnull(biaya_packing_deliv_agent,0) end
                                as new_biaya_packing_agent_total,
                                case when pickup_by_agent =$agent_by and (delivery_by_agent !=$agent_by or delivery_by_agent is null) then ifnull(biaya_tambahan_agent,0)
                                	when delivery_by_agent=$agent_by and pickup_by_agent !=$agent_by then ifnull(a.biaya_tambahan_deliv_agent,0)
                                	when pickup_by_agent=$agent_by and delivery_by_agent =$agent_by   then ifnull(biaya_tambahan_agent,0)
                                	else ifnull(n.biaya_tambahan_agent_transit,0)
                                end as new_biaya_tambahan_agent_total,
                                ifnull(kunci_total_agent,0) as kunci_total_agent,
                                case
                                	when pickup_by_agent != $agent_by	and (delivery_by_agent !=$agent_by or delivery_by_agent is null) 	then ifnull(n.kunci_total_agent_transit,0)
                                	when pickup_by_agent =$agent_by 	and (delivery_by_agent !=$agent_by or delivery_by_agent is null) 	then ifnull(kunci_total_agent_pickup,0)
                               		when pickup_by_agent !=$agent_by 	and delivery_by_agent =$agent_by 	then ifnull(kunci_total_agent_deliv,0)
                           			when pickup_by_agent =$agent_by 	and delivery_by_agent =$agent_by 	then ifnull(kunci_total_agent,0)
                                end as total_kunci_harga_agent,
								case
                                	when pickup_by_agent != $agent_by	and (delivery_by_agent !=$agent_by or delivery_by_agent is null) 	then 'transit'
                                	when pickup_by_agent =$agent_by 	and (delivery_by_agent !=$agent_by or delivery_by_agent is null) 	then 'pickup'
                               		when pickup_by_agent !=$agent_by 	and delivery_by_agent =$agent_by 	then 'delivery'
                           			when pickup_by_agent =$agent_by 	and delivery_by_agent =$agent_by 	then 'pickup-delivery'
                                end as status_agent,
								ifnull(n.kunci_total_agent_transit,0) as kunci_total_agent_transit,
								ifnull(kunci_total_agent_pickup,0) as kunci_total_agent_pickup,
								ifnull(kunci_total_agent_deliv,0) as kunci_total_agent_deliv,
                                
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
                                a.paid_reject,
                            	case
                            	when pickup_by_agent = $agent_by and delivery_by_agent <> $agent_by THEN
									case when terlambat_pickup in (1,2) then
										ifnull(a.diskon_pickup,0)
									else 0
									end
                            	when delivery_by_agent = $agent_by and pickup_by_agent <> $agent_by THEN
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
                            	end as diskon, kunci_hargaperkg_agent,
								case when a.tanggal_agent is not null then
                                    date_format(a.tanggal_agent, '%Y-%m') 
                                else date_format(a.tanggal, '%Y-%m')  
                                end as tanggal
							FROM pengiriman a

							LEFT JOIN rate_agent d ON d.id_kota=id_kecamatan_tujuan AND d.id_pelayanan=a.service and a.delivery_by_agent = $agent_by and d.id_cabang=$agent_by

							LEFT JOIN rate_agent_inbound e ON e.id_kota=id_kecamatan_asal  and e.id_pelayanan=a.service and a.pickup_by_agent = $agent_by and e.id_cabang = $agent_by

							LEFT JOIN rate_agent_cross f ON f.id_kota_asal = a.id_kecamatan_asal and f.id_kota_tujuan = a.id_kecamatan_tujuan  AND a.pickup_by_agent=$agent_by and a.delivery_by_agent = $agent_by and f.id_agent = $agent_by

							LEFT JOIN manifest_detail n ON a.id_pengiriman = n.id_pengiriman

							LEFT JOIN manifest m ON n.id_manifest = m.id_manifest

							LEFT JOIN rate_agent g ON g.id_kota=id_kecamatan_tujuan AND g.id_pelayanan=a.service and m.manifest_from = $agent_by and g.id_cabang=$agent_by

							WHERE ((
								a.service != 6 and a.status != 7  and pickup_by_agent = $agent_by or delivery_by_agent = $agent_by )
							or (m.manifest_from =$agent_by 
							and a.service != 6 and a.status != 7 and  pickup_by_agent != $agent_by and delivery_by_agent != $agent_by))
							and a.airwaybill_kode = '".$awb."'
							group by a.id_pengiriman
							";

						$airwaybill		= $mysqli->query($sql);												
						$count			= $airwaybill->num_rows;
                        $sum_total = 0;
						
						if ($count>0){

                            $row = $airwaybill->fetch_assoc();
							$nok = 0;
							$nokStts = '';
							
							if ($cekAgent == 0){
								$idAgent = $agent_by;
                                $cekAgent++;
							}

                            if($row['lokasi_asal']=='Jakarta Selatan' || $row['lokasi_asal']=='Jakarta Timur' || $row['lokasi_asal']=='Jakarta Barat' ||		$row['lokasi_asal']=='Jakarta Utara' || $row['lokasi_asal']=='Jakarta Pusat'){
                                $lokasi_asal = 'Jakarta';
                            }else{
                                $lokasi_asal = $row['lokasi_asal'];
                            }

                            if($row['lokasi_tujuan']=='Jakarta Selatan' || $row['lokasi_tujuan']=='Jakarta Timur' || $row['lokasi_tujuan']=='Jakarta Barat' ||		$row['lokasi_tujuan']=='Jakarta Utara' || $row['lokasi_tujuan']=='Jakarta Pusat'){
                                $lokasi_tujuan = 'Jakarta';
                            }else{
                                $lokasi_tujuan = $row['lokasi_tujuan'];
                            }

                            $totalAgent = 0;
                            $totalAgenPickup = $row['kunci_total_agent_pickup'];
                            $totalAgentDeliv = $row['kunci_total_agent_deliv'];
                            $totalAgentTransit = $row['kunci_total_agent_transit'];
                            $totalAgentPickupDeliv = $row['kunci_total_agent'];

                            $sttsAgent = $row['status_agent'];
                            if ($sttsAgent == 'pickup-delivery'){
                                $totalAgenPickup = 0;
                                $totalAgentTransit = 0;
                                $totalAgentDeliv = $row['new_total'];
                                $totalAgent = $totalAgentDeliv;
                            }else{
                                if ($sttsAgent == 'pickup'){
                                    $totalAgenPickup = $row['new_total'];
                                    $totalAgentDeliv = 0;
                                    $totalAgentTransit = 0;
                                    $totalAgent = $totalAgenPickup;
                                }
                                else if ($sttsAgent == 'transit') {
                                    $totalAgentTransit = $row['new_total'];
                                    $totalAgenPickup = 0;
                                    $totalAgentDeliv = 0;
                                    $totalAgent = $totalAgentTransit;
                                }
                                else if ($sttsAgent == 'delivery'){
                                    $totalAgentDeliv = $row['new_total'];
                                    $totalAgenPickup = 0;
                                    $totalAgentTransit = 0;
                                    $totalAgent = $totalAgentDeliv;
                                }
                            }

                            $new_total_diskon = $totalAgent - ($row['new_biaya_packing_agent_total']+$row['new_biaya_tambahan_agent_total']);
                            $total = $new_total_diskon;
                            $diskon = $row['diskon'];
                            $totalAfterDiskon = ($total-(($diskon/100)*$total))+($row['new_biaya_packing_agent_total']+$row['new_biaya_tambahan_agent_total']);

                            if ($row['paid_reject'] != 0){
                                $sum_total = $sum_total + $totalAfterDiskon;
                            }

                            $posAsal = strpos(strtoupper(trim($lokasi_asal)), strtoupper(trim($asal)));
                            if ($posAsal === false) {
                                $nok++;
                                $nokStts .= '#"'.strtoupper($lokasi_asal) .'"!="'. strtoupper(trim($asal)).'"';
                                $asal = "<font style='color:#FF0000';>".$asal."</font>";
                            }

                            $posTujuan = strpos(strtoupper(trim($lokasi_tujuan)), strtoupper(trim($tujuan)));
                            if ($posTujuan === false) {
                                $nok++;
                                $nokStts .= '#"'.strtoupper($lokasi_tujuan) .'"!="'. strtoupper(trim($tujuan)).'"';
                                $tujuan = "<font style='color:#FF0000';>".$tujuan."</font>";
                            }
                            if (strtoupper(trim($row['service'])) != strtoupper(trim($service))){
                                $nok++;
                                $nokStts .= '#"'.strtoupper($row['service']) .'"!="'. strtoupper(trim($service)).'"';
                                $service = "<font style='color:#FF0000';>".$service."</font>";
                            }
                            if (strtoupper(trim($row['berat_tampil'])) != strtoupper(trim($berat))){
                                $nok++;
                                $nokStts .= '#"'.strtoupper($row['berat_tampil']) .'"!="'. strtoupper($berat).'"';
                                $berat = "<font style='color:#FF0000';>".$berat."</font>";
                            }
                            if (strtoupper(trim($row['pricee'])) != strtoupper(trim($harga))){
                                $nok++;
                                $nokStts .= '#"'.strtoupper($row['pricee']) .'"!="'. strtoupper($harga).'"';
                                $harga = "<font style='color:#FF0000';>".$harga."</font>";
                            }
							if (strtoupper(trim($totalAfterDiskon)) != strtoupper(trim($totalHarga))){
								$nok++; 
								$nokStts .= '#"'.strtoupper($totalAfterDiskon) .'"!="'. strtoupper($totalHarga).'"';
                                $totalHarga = "<font style='color:#FF0000';>".$totalHarga."</font>";
							}

							$cek = ($nok == 0) ? 'TRUE' : 'FALSE ('.$nokStts.')';

							$btnCek = ($nok > 0) ? '<a class="btn btn-xs btn-default" title="Detail" href="?module=invoice-agent&act=update&id='.base64_encrypt($agent_by.'~'.$row['tanggal'].'~~'.'~'.$awb,$key).'" target="_blank"><i class="fa fa-file text-yellow"></i></a> ' : '';

							
						}else {
						    $cek = 'NOT IN DATABASE';
                            $btnCek = '';
                        }
						
						$resCek = array(
						    'AWB' => $awb,
                            'SERVICE' => $service,
                            'KOTA_ASAL' => $asal,
                            'KOTA_TUJUAN' => $tujuan,
                            'BERAT' => $berat,
                            'HARGA' => $harga,
                            'TOTAL' => $totalHarga,
                            'HARGA_ECESCO' => $totalAfterDiskon,
                            'Cek' => $btnCek.$cek

						);
						
						array_push($result, $resCek);
						
						
					}

					$x++;
				}


				//cek awb lain

                $sql = "
							SELECT
								a.id_pengiriman,
								a.id_kustomer,
                                airwaybill_kode,
                                x_service(a.service) as service,
                                if(a.id_kota_tujuan > 0, a.kota_asal, x_namakotabesar(lokasi_asal)) as lokasi_asal,
								if(a.id_kota_tujuan > 0, a.kota_tujuan, x_namakotabesar(lokasi_tujuan)) as lokasi_tujuan, 
                                case when pickup_by_agent =$agent_by then ifnull(biaya_packing_agent,0)
                                	else ifnull(biaya_packing_deliv_agent,0) end
                                as new_biaya_packing_agent_total,
                                case when pickup_by_agent =$agent_by and (delivery_by_agent !=$agent_by or delivery_by_agent is null) then ifnull(biaya_tambahan_agent,0)
                                	when delivery_by_agent=$agent_by and pickup_by_agent !=$agent_by then ifnull(a.biaya_tambahan_deliv_agent,0)
                                	when pickup_by_agent=$agent_by and delivery_by_agent =$agent_by   then ifnull(biaya_tambahan_agent,0)
                                	else ifnull(n.biaya_tambahan_agent_transit,0)
                                end as new_biaya_tambahan_agent_total,
                                ifnull(kunci_total_agent,0) as kunci_total_agent,
                                case
                                	when pickup_by_agent != $agent_by	and (delivery_by_agent !=$agent_by or delivery_by_agent is null) 	then ifnull(n.kunci_total_agent_transit,0)
                                	when pickup_by_agent =$agent_by 	and (delivery_by_agent !=$agent_by or delivery_by_agent is null) 	then ifnull(kunci_total_agent_pickup,0)
                               		when pickup_by_agent !=$agent_by 	and delivery_by_agent =$agent_by 	then ifnull(kunci_total_agent_deliv,0)
                           			when pickup_by_agent =$agent_by 	and delivery_by_agent =$agent_by 	then ifnull(kunci_total_agent,0)
                                end as total_kunci_harga_agent,
								case
                                	when pickup_by_agent != $agent_by	and (delivery_by_agent !=$agent_by or delivery_by_agent is null) 	then 'transit'
                                	when pickup_by_agent =$agent_by 	and (delivery_by_agent !=$agent_by or delivery_by_agent is null) 	then 'pickup'
                               		when pickup_by_agent !=$agent_by 	and delivery_by_agent =$agent_by 	then 'delivery'
                           			when pickup_by_agent =$agent_by 	and delivery_by_agent =$agent_by 	then 'pickup-delivery'
                                end as status_agent,
								ifnull(n.kunci_total_agent_transit,0) as kunci_total_agent_transit,
								ifnull(kunci_total_agent_pickup,0) as kunci_total_agent_pickup,
								ifnull(kunci_total_agent_deliv,0) as kunci_total_agent_deliv,
                                
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
                                a.paid_reject,
                            	case
                            	when pickup_by_agent = $agent_by and delivery_by_agent <> $agent_by THEN
									case when terlambat_pickup in (1,2) then
										ifnull(a.diskon_pickup,0)
									else 0
									end
                            	when delivery_by_agent = $agent_by and pickup_by_agent <> $agent_by THEN
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
                            	end as diskon, kunci_hargaperkg_agent,
								case when a.tanggal_agent is not null then
                                    date_format(a.tanggal_agent, '%Y-%m') 
                                else date_format(a.tanggal, '%Y-%m')  
                                end as tanggal
							FROM pengiriman a

							LEFT JOIN rate_agent d ON d.id_kota=id_kecamatan_tujuan AND d.id_pelayanan=a.service and a.delivery_by_agent = $agent_by and d.id_cabang=$agent_by

							LEFT JOIN rate_agent_inbound e ON e.id_kota=id_kecamatan_asal  and e.id_pelayanan=a.service and a.pickup_by_agent = $agent_by and e.id_cabang = $agent_by

							LEFT JOIN rate_agent_cross f ON f.id_kota_asal = a.id_kecamatan_asal and f.id_kota_tujuan = a.id_kecamatan_tujuan  AND a.pickup_by_agent=$agent_by and a.delivery_by_agent = $agent_by and f.id_agent = $agent_by

							LEFT JOIN manifest_detail n ON a.id_pengiriman = n.id_pengiriman

							LEFT JOIN manifest m ON n.id_manifest = m.id_manifest

							LEFT JOIN rate_agent g ON g.id_kota=id_kecamatan_tujuan AND g.id_pelayanan=a.service and m.manifest_from = $agent_by and g.id_cabang=$agent_by

							WHERE a.service != 6
                            AND a.status != 7
                            and
                            (		( pickup_by_agent = $agent_by
                                    OR delivery_by_agent = $agent_by)
                                OR (m.manifest_from = $agent_by
                                    AND pickup_by_agent != $agent_by
                                    AND delivery_by_agent != $agent_by))
                                    and CASE
                                    WHEN a.tanggal_agent IS NOT NULL THEN 
                                     date_format(a.tanggal_agent, '%Y-%m') = '".$month."'
                                    ELSE date_format(a.tanggal, '%Y-%m') = '".$month."'
                                    END
                                    AND a.airwaybill_kode NOT IN ('".implode("','",$arrAWB)."')
							group by a.id_pengiriman
							";


                $query	= $mysqli->query($sql);
                $resPeriode = array();
                while($row = $query->fetch_assoc()){
                    if($row['lokasi_asal']=='Jakarta Selatan' || $row['lokasi_asal']=='Jakarta Timur' || $row['lokasi_asal']=='Jakarta Barat' ||		$row['lokasi_asal']=='Jakarta Utara' || $row['lokasi_asal']=='Jakarta Pusat'){
                        $lokasi_asal = 'Jakarta';
                    }else{
                        $lokasi_asal = $row['lokasi_asal'];
                    }

                    if($row['lokasi_tujuan']=='Jakarta Selatan' || $row['lokasi_tujuan']=='Jakarta Timur' || $row['lokasi_tujuan']=='Jakarta Barat' ||		$row['lokasi_tujuan']=='Jakarta Utara' || $row['lokasi_tujuan']=='Jakarta Pusat'){
                        $lokasi_tujuan = 'Jakarta';
                    }else{
                        $lokasi_tujuan = $row['lokasi_tujuan'];
                    }

                    $totalAgent = 0;
                    $totalAgenPickup = $row['kunci_total_agent_pickup'];
                    $totalAgentDeliv = $row['kunci_total_agent_deliv'];
                    $totalAgentTransit = $row['kunci_total_agent_transit'];
                    $totalAgentPickupDeliv = $row['kunci_total_agent'];

                    $sttsAgent = $row['status_agent'];
                    if ($sttsAgent == 'pickup-delivery'){
                        $totalAgenPickup = 0;
                        $totalAgentTransit = 0;
                        $totalAgentDeliv = $row['new_total'];
                        $totalAgent = $totalAgentDeliv;
                    }else{
                        if ($sttsAgent == 'pickup'){
                            $totalAgenPickup = $row['new_total'];
                            $totalAgentDeliv = 0;
                            $totalAgentTransit = 0;
                            $totalAgent = $totalAgenPickup;
                        }
                        else if ($sttsAgent == 'transit') {
                            $totalAgentTransit = $row['new_total'];
                            $totalAgenPickup = 0;
                            $totalAgentDeliv = 0;
                            $totalAgent = $totalAgentTransit;
                        }
                        else if ($sttsAgent == 'delivery'){
                            $totalAgentDeliv = $row['new_total'];
                            $totalAgenPickup = 0;
                            $totalAgentTransit = 0;
                            $totalAgent = $totalAgentDeliv;
                        }
                    }

                    $new_total_diskon = $totalAgent - ($row['new_biaya_packing_agent_total']+$row['new_biaya_tambahan_agent_total']);
                    $total = $new_total_diskon;
                    $diskon = $row['diskon'];
                    $totalAfterDiskon = ($total-(($diskon/100)*$total))+($row['new_biaya_packing_agent_total']+$row['new_biaya_tambahan_agent_total']);

                    if ($row['paid_reject'] != 0){
                        $sum_total = $sum_total + $totalAfterDiskon;
                    }



                    $btnCek = '<a class="btn btn-xs btn-default" title="Detail" href="?module=invoice-agent&act=update&id='.base64_encrypt($agent_by.'~'.$row['tanggal'].'~~'.'~'.$row['airwaybill_kode'],$key).'" target="_blank"><i class="fa fa-file text-yellow"></i></a> ';

                    $resCek = array(
                        'AWB' => $row['airwaybill_kode'],
                        'SERVICE' => $row['service'],
                        'KOTA_ASAL' => $lokasi_asal,
                        'KOTA_TUJUAN' => $lokasi_tujuan,
                        'BERAT' => $row['berat_tampil'],
                        'HARGA' => $row['pricee'],
                        'TOTAL' => $totalAfterDiskon,
                        'HARGA_ECESCO' => $totalAfterDiskon,
                        'Cek' => $btnCek.'NOT IN EXCEL'

                    );

                    array_push($result, $resCek);

                }


			} else {
				$result = SimpleXLSX::parse_error();
			}
		}

		echo json_encode($result);
        //echo $sql;
	
	} 
	
}
?>