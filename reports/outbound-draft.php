<?php
include ('../config/connection.php');
include ('../config/encrypt.php');
include ('../config/library.php');
	
$key = "asdf$1011";
$encrypt = "$_GET[id]";
$id = base64_decrypt($encrypt,$key);
			
$result = mysqli_query($con, "select a.airwaybill_kode,collie, case when komoditi=1 then 'P' else 'D' end as jenis_komoditi,x_service(a.service) as pelayanan, x_namakustomer(id_kustomer) as nama_kustomer, a.tanggal, a.status, x_inter(a.id_kota_asal) as inter_asal,x_inter(a.id_kota_tujuan) as inter_tujuan,kota_tujuan,x_hubnew(a.id_kota_tujuan) as id_hub
	from pengiriman a 
	
	Left join manifest_detail c on a.id_pengiriman = c.id_pengiriman
	left join manifest d on d.id_manifest = c.id_manifest

	where (a.tanggal= CURDATE() or pickup_date=CURDATE() or (d.tanggal=CURDATE() and d.manifest_from=39 ) ) and x_inter(a.id_kota_asal) = 'Y' and x_inter(a.id_kota_tujuan) != 'Y' order by x_hubnew(a.id_kota_tujuan) ASC");
//$r = mysqli_fetch_assoc($query);

//$tanggal = tgl_indo($r['tanggal']);

?>
<!DOCTYPE html>
<html>
	<head>
	<meta charset="UTF-8">
		<title>Manifest Outbound Draft</title>
	</head>
	<body>
		<table cellspacing=0 width="100%">
			<tr>
				<td style="font-family:Calibri;text-align:center;font-size:16px;color:#000000;font-weight:bold;" colspan="8">MANIFEST OUTBOUND</td>
			</tr>
			<tr>
				<td colspan="11"></td>
			</tr>
			<tr>
			  <td colspan="2" style="font-family:Calibri;font-size:14px;font-weight:bold;color:#000000;">TANGGAL</td>
			  <td colspan="6" style="font-family:Calibri;font-size:14px;font-weight:bold;color:#000000;">: <?php echo''.date("d - m - Y").'';?></td>
		    </tr>
		    
		  			
			<tr>
				<td colspan="8">&nbsp;</td>
			</tr>
			<tr>
				<td width="1%" style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border:1px solid;">NO</td>
				<td width="7%" style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">AIRWAYBILL</td>
				<td width="15%" style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">CUSTOMER</td>
				<td width="15%" style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">collie
				</td>				
				
				<td width="3%" style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">D/P</td>
				<td width="3%" style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Kota Tujuan</td>
				<td width="3%" style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Service</td>
				
				
			</tr>
			<?php
			$no=1;
			/*$result = mysqli_query($con,"select airwaybill_kode, x_namakustomer(id_kustomer) as kustomer, collie,alamat_penerima,alamat_penerima2, coalesce(berat_aktual,berat_barang) as berat_aktual, case when komoditi='1' then 'P' else 'D' end as komoditi, if(id_kota_tujuan>0,kota_tujuan,x_namakota(lokasi_tujuan)) as tujuan, x_service(service) as service, alamat_penerima,perusahaan_penerima, case when packing_kayu='1' then 'Packing Oleh Pemilik' when packing_kayu='2' then 'Packing Kayu Oleh Cesco Log.' when packing_kayu='3' then 'Non Packing Kayu Oleh Cesco Log.' end as packing_kayu from manifest_detail a inner join pengiriman b on b.id_pengiriman=a.id_pengiriman where a.id_manifest='$id' order by created_date asc");*/
			while($t=mysqli_fetch_assoc($result)){
				$n = $t['id_hub'];
				if ($r != $n){
					echo '<tr> <td colspan="7">&nbsp;</td> </tr>';
					
				}
				echo'
				<tr>
					<td style="font-family:Calibri;text-align:center;font-size:14px;font-color:#000000;border-color:#000;border:1px solid;">'.$no.'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['airwaybill_kode'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['nama_kustomer'].'</td>					
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['collie'].'</td>					
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['jenis_komoditi'].'</td>

					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.strtoupper($t['kota_tujuan']).'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.strtoupper($t['pelayanan']).'</td>
					
					
				</tr>';
				$r = $t['id_hub'];
				
			$no++;
			}
			?>
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
	<script type="text/javascript">
		window.print();
		//window.onfocus=function(){ window.close();}
		</script>
	</body>
</html>