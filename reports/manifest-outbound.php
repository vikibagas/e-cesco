<?php
include ('../config/connection.php');
include ('../config/encrypt.php');
include ('../config/library.php');
	
$key = "asdf$1011";
$encrypt = "$_GET[id]";
$id = base64_decrypt($encrypt,$key);
			
$query = mysqli_query($con, "select b.alamat2 as alamat_tujuan,b.telp as telp_tujuan,b.id_cabang as id_cabang_tujuan,a.tanggal, c.nama_cabang as asal_cabang, case when x_intercity(b.id_kota)='Y' then 'JAKARTA' else b.nama_cabang end as tujuan, case when a.manifest_via='1' then 'UDARA' when a.manifest_via='2' then 'DARAT' when a.manifest_via='3' then 'LAUT' when a.manifest_via='4' then 'TOTO' when a.manifest_via='5' then 'HERONA' when a.manifest_via='6' then 'BES' when a.manifest_via='7' then 'Indah Cargo' when a.manifest_via='8' then 'JNE' when a.manifest_via='9' then 'TIKI' else a.manifest_via end as manifest_via, a.no_smu, x_user(packed_by) as packed,x_user(a.created_by) as created,
	berat_aktual_volume,collie_barang 

	from manifest a inner join cabang b on b.id_cabang=a.manifest_to inner join cabang c on c.id_cabang=a.manifest_from where a.id_manifest='$id'");
$r = mysqli_fetch_assoc($query);



$u_to = mysqli_query($con,"select namalengkap from user where id_cabang= '".$r['id_cabang_tujuan']."' and level = 'agent'  limit 1");
$r_to = mysqli_fetch_assoc($u_to);

$tanggal = tgl_indo($r['tanggal']);

?>
<!DOCTYPE html>
<html>
	<head>
	<meta charset="UTF-8">
		<title>Manifest Outbound</title>
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
			  <td colspan="3" style="font-family:Calibri;font-size:14px;font-weight:bold;color:#000000;">: <?php echo''.$tanggal.'';?></td>
			  		    </tr>
		    <tr>
				<td colspan="2" style="font-family:Calibri;font-size:14px;font-weight:bold;color:#000000;">MANIFEST DARI</td>
				<td colspan="3" style="font-family:Calibri;font-size:14px;font-weight:bold;color:#000000;">: <?php echo''.strtoupper($r['asal_cabang']).'';?></td>
			
		  </tr>
			<tr>
				<td colspan="2" style="font-family:Calibri;font-size:14px;font-weight:bold;color:#000000;">MANIFEST TUJUAN</td>
				<td colspan="6" style="font-family:Calibri;font-size:14px;font-weight:bold;color:#000000;">: <?php echo''.strtoupper($r['tujuan']).'-'.$r_to['namalengkap'];?></td>
		  </tr>
		  </tr>
			<tr>
				<td colspan="2" style="font-family:Calibri;font-size:14px;font-weight:bold;color:#000000;">PENERIMA</td>
				<td colspan="6" style="font-family:Calibri;font-size:14px;font-weight:bold;color:#000000;">: <?php echo $r_to['namalengkap'].' - '.$r['telp_tujuan'];?></td>
		  </tr>
		  </tr>
			<tr>
				<td colspan="2" style="font-family:Calibri;font-size:14px;font-weight:bold;color:#000000;">ALAMAT</td>
				<td colspan="6" style="font-family:Calibri;font-size:14px;font-weight:bold;color:#000000;">: <?php echo $r['alamat_tujuan'];?></td>
		  </tr>
		  <tr>
			  <td colspan="2" style="font-family:Calibri;font-size:14px;font-weight:bold;color:#000000;">VIA</td>
              <td colspan="6" style="font-family:Calibri;font-size:14px;font-weight:bold;color:#000000;">: <?php echo''.$r['manifest_via'].'';?></td>
		  </tr>
			<tr>
			  <td colspan="2" style="font-family:Calibri;font-size:14px;font-weight:bold;color:#000000;">NO. SMU/RESI</td>
              <td colspan="6" style="font-family:Calibri;font-size:14px;font-weight:bold;color:#000000;">: <?php echo''.$r['no_smu'].'';?></td>
		  </tr>
		  <tr>
			  <td colspan="2" style="font-family:Calibri;font-size:14px;font-weight:bold;color:#000000;">CREATED BY</td>
              <td colspan="6" style="font-family:Calibri;font-size:14px;font-weight:bold;color:#000000;">: <?php echo''.$r['created'].'';?></td>
		  </tr>
			<tr>
				<td colspan="8">&nbsp;</td>
			</tr>
			<tr>
				<td width="1%" style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border:1px solid;">NO</td>
				<td width="7%" style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">AIRWAYBILL</td>
				<td width="15%" style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Kustomer</td>
				<td width="15%" style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">PERUSAHAAN PENERIMA
				</td>		
				<td width="15%" style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">ALAMAT PENERIMA
				</td>				

				<td width="12%" style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">PACKING KAYU</td>
				<td width="3%" style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">KOLI</td>
				<td width="3%" style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">BERAT/VOLUME</td>
				<td width="3%" style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">D/P</td>
				<td width="10%" style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">KOMODITI</td>
				<td width="20%" style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">SERVICE</td>
			</tr>
			<?php
			$no=1;
			$result = mysqli_query($con,"select airwaybill_kode, x_namakustomer(id_kustomer) as kustomer, collie,alamat_penerima,alamat_penerima2, coalesce(berat_aktual,berat_barang) as berat_aktual, case when komoditi='1' then 'P' else 'D' end as komoditi, if(id_kota_tujuan>0,kota_tujuan,x_namakota(lokasi_tujuan)) as tujuan, x_service(service) as service, alamat_penerima,perusahaan_penerima, case when packing_kayu='1' then 'Packing Oleh Pemilik' when packing_kayu='2' then 'Packing Kayu Oleh Cesco Log.' when packing_kayu='3' then 'Non Packing Kayu Oleh Cesco Log.' end as packing_kayu , jenis_barang, x_namacabang(delivery_by_agent) as delivery_by_agent,hp_penerima,alamat_penerima,alamat_penerima2, kecamatan_tujuan,kota_tujuan, x_user(delivery_by) as delivery_by

				from manifest_detail a inner join pengiriman b on b.id_pengiriman=a.id_pengiriman 
				inner join manifest c on a.id_manifest = c.id_manifest

				where a.id_manifest='$id' order by b.created_date asc");
			while($t=mysqli_fetch_assoc($result)){
				echo'
				<tr>
					<td style="font-family:Calibri;text-align:center;font-size:14px;font-color:#000000;border-color:#000;border:1px solid;">'.$no.'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['airwaybill_kode'].'</td>
					';

					echo '
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['kustomer'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['perusahaan_penerima'].'</td>					
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['alamat_penerima'].' '.$t['alamat_penerima2'].' '.$t['kecamatan_tujuan'].', ' .$t['kota_tujuan'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['packing_kayu'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['collie'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['berat_aktual'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['komoditi'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['jenis_barang'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.strtoupper($t['service']).'</td>
				</tr>';
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