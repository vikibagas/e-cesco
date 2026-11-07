<?php
include ('../../config/connection.php');
include ('../../config/encrypt.php');
include ('../../config/library.php');
$tahun = $_GET['tahun'];
$bulan = $_GET['bulan'];
$id_kustomer = $_GET['id_kustomer'];
$q = "

							select nama_kustomer from kustomer where id_kustomer=".$id_kustomer." ";
 
$r = mysqli_fetch_assoc(mysqli_query($con, $q));


header("Content-type: application/vnd-ms-excel");
header('Content-Disposition: attachment; filename="'.$r['nama_kustomer'].'-'.$tahun.'-'.$bulan.'.Report-summary-profit-loss.xls"');




?>
<!DOCTYPE html>
<html>
	<head>
	<meta charset="UTF-8">
		<title>Report Profit Loss Per Customer</title>
	</head>
	<body>
		<table cellspacing=0 width="100%">
			<tr>
				<td style="font-family:Calibri;text-align:center;font-size:16px;color:#000000;font-weight:bold;" colspan="17">Report summary profit loss</td>
			</tr>
			<tr>
				<td colspan="3"></td>
			</tr>	  
			<tr>
				<td colspan="3">&nbsp;</td>
			</tr>

			<tr>
				<td colspan="2">Customer :</td>
				<td colspan="1"><?php echo $r['nama_kustomer']; ?></td>
			</tr>
			<tr>
				<td colspan="2">Tahun :</td>
				<td colspan="1"><?php echo $_GET['tahun']; ?></td>
				<td colspan="2">Bulan :</td>
				<td colspan="1"><?php echo $_GET['bulan']; ?></td>
			</tr>

			<tr>
				<td colspan="3"></td>
			</tr>	  
			<tr>
				<td colspan="3">&nbsp;</td>
			</tr>
			<tr>
				<th style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border:1px solid;" rowspan="">Tanggal </th>
				<th style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border:1px solid;" rowspan="">Nomor</th>
				<th style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border:1px solid;" rowspan="">Total Invoice</th>
				<th style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border:1px solid;" rowspan="">Asuransi+POSTAGE+dll</th>
				<th style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border:1px solid;" rowspan="">PPN</th>
				<th style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border:1px solid;" rowspan="">NET INVOICE BEFORE TAX</th>

				<th style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border:1px solid;" rowspan="">Profit Loss</th>
				<th style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border:1px solid;" rowspan="">PPH 23(2%)</th>
				<th style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border:1px solid;" rowspan="">NETT PROFIT After PPH 23(2%)</th>
				
				

			</tr>
			<?php
			$no=1;
			//$tipe = mysqli_real_escape_string($con, $_GET['tipe']);
						
						
						
							$cari = "

							select a.id_invoice,a.tanggal, subtotal, MONTH(a.tanggal) as bulan,nomor,

							ifnull(inv_asuransi,0) + ifnull(inv_packing_kayu,0) + ifnull(ppn_packing,0)       as biaya_lain_lain ,

							ppn_subtotal,
							
							total_all,


							
							(

	                        SUM(IFNULL(b.costing_pickup_fee,0))+
	                        SUM(IFNULL(b.costing_pickup_parking,0))+
	                        SUM(IFNULL(b.costing_pickup_toll,0))+
	                        SUM(IFNULL(b.costing_men_power,0))+
	                        SUM(IFNULL(b.costing_delivery_fee,0))+
	                        SUM(IFNULL(b.costing_delivery_parking,0))+
	                        SUM(IFNULL(b.costing_delivery_toll,0))+
	                        SUM(IFNULL(b.costing_delivery_menpower,0))+
	                        SUM(IFNULL(b.costing_outbound_fee,0))+
	                        SUM(IFNULL(b.costing_outbound_toll,0))+
	                        SUM(IFNULL(b.costing_outbound_smu,0))+
	                        SUM(IFNULL(b.costing_outbound_handling,0))+
	                        SUM(IFNULL(b.costing_outbound_parking,0))+
	                        SUM(IFNULL(b.costing_outbound_menpower,0))+
	                        SUM(IFNULL(b.costing_inbound_fee,0))+
	                        SUM(IFNULL(b.costing_inbound_toll,0))+
	                        SUM(IFNULL(b.costing_inbound_smu,0))+
	                        SUM(IFNULL(b.costing_inbound_handling,0))+
	                        SUM(IFNULL(b.costing_inbound_parking,0))+
	                        SUM(IFNULL(b.costing_inbound_menpower ,0))+
	                        SUM(IFNULL(b.kunci_total_agent_pickup,0))+
	                        SUM(IFNULL(b.kunci_total_agent_deliv,0))+
	                        SUM(IFNULL(md.kunci_total_agent_transit,0))
	                        


	                        ) as costing

	                       

							FROM
								
								 

								pengiriman b 

								left join manifest_detail md on md.id_pengiriman = b.id_pengiriman and md.kunci_total_agent_transit is not null

								inner join invoice a on a.id_invoice = b.id_invoice
									
							where a.id_kustomer='".$id_kustomer."' and a.tanggal between '".$tahun."-".$bulan."-01' and '".$tahun."-".$bulan."-31' and a.subtotal != ''  group by a.id_invoice order by a.tanggal  ";
						

			$result = mysqli_query($con, $cari);
			while($t=mysqli_fetch_assoc($result)){		
				
						
				echo'
				<tr >
					<td style="font-family:Calibri;text-align:center;font-size:14px;font-color:#000000;border-color:#000;border:1px 
						solid;">'.$t['tanggal'].'</td>		
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['nomor'].'</td>			
					
					
					
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Rp. '.number_format($t['total_all']).'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Rp. '.number_format($t['biaya_lain_lain']).'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Rp. '.number_format($t['ppn_subtotal']).'</td>
					<td style="font-family:Calibri;text-align:left;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Rp. '.number_format($t['subtotal']).'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Rp. '.number_format($t['subtotal']-$t['costing']).'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Rp. '.number_format(2/100*$t['subtotal']).'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Rp. '.number_format($t['subtotal']-$t['costing']-(2/100*$t['subtotal'])).'</td>
					
					
					
				</tr>	
				';
			$no++;
			}
			?>
			
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