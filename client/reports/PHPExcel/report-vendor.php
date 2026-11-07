<?php
include ('../../config/connection.php');
include ('../../config/encrypt.php');
include ('../../config/library.php');
	

$vendor = $_GET['vendor'];




$tanggal = mysqli_real_escape_string($con, $_GET['tanggal']);
						$dr = strleft($tanggal, 10);
						$sm = strright($tanggal, 10);
						$dari = jin_date_sql($dr);
						$sampai = jin_date_sql($sm);


header("Content-type: application/vnd-ms-excel");
header('Content-Disposition: attachment; filename="'.$vendor.'-'.$dari.'-'.$sampai.'.Report.xls"');




//$tanggal = tgl_indo($r['tanggal']);

?>
<!DOCTYPE html>
<html>
	<head>
	<meta charset="UTF-8">
		<title>Manifest Outbound</title>
	
	<style type="text/css">
		input {
		  outline: 0;
		  border-width: 0 0 2px;
		  border-color: #000;
		}
		input:focus {
		  border-color: green;
		  outline: 1px dotted #000;
		}

	</style>

	</head>
	<body style="text-transform: uppercase;">
		<table cellspacing=0 width="100%">
			<tr>
				<td style="font-family:Calibri;text-align:center;font-size:16px;color:#000000;font-weight:bold;" colspan="8">MANIFEST OUTBOUND</td>
			</tr>
			<tr>
				<td colspan="11"></td>
			</tr>
			<tr>
			  <td colspan="2" style="font-family:Calibri;font-size:14px;font-weight:bold;color:#000000;">TANGGAL</td>
			  <td colspan="2" style="font-family:Calibri;font-size:14px;font-weight:bold;color:#000000;">: <?php echo''.$dari.'-'.$sampai;?></td>
			  
		    </tr>
		    
		  <tr>
			  <td colspan="2" style="font-family:Calibri;font-size:14px;font-weight:bold;color:#000000;">Vendor</td>
              <td colspan="6" style="font-family:Calibri;font-size:14px;font-weight:bold;color:#000000;">: <?php echo''.$vendor.'';?></td>
              <td  style="font-family:Calibri;font-size:14px;font-weight:bold;color:#000000;">&nbsp;</td>
               
               
		  </tr>
		 
		 
			<tr>
				<td colspan="8">&nbsp;</td>
			</tr>
			<tr>
				<td width="1%" style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border:1px solid;">NO</td>
				<td width="7%" style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Date</td>
				<td width="15%" style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">AWB CESCO</td>
				
				<td width="12%" style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">COMMODITY</td>

				<td width="12%" style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">ISI BARANG</td>
				<td width="12%" style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">DESTINATION</td>
				<td width="12%" style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">3LC</td>
				<td width="3%" style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">RECEPTIENT</td>
				<td width="3%" style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">RECEIPT HANDPHONE</td>
				<td width="3%" style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">RECEIPT ADDRESS</td>
				<td width="3%" style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">CATATAN PENGANTARAN</td>

				<td width="3%" style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">DESTINASI KOTA</td>
				<td width="3%" style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">DESTINASI KECAMATAN</td>
				<td width="3%" style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">BERAT AKTUAL</td>
				<td width="3%" style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">DIMENSI</td>
				<td width="3%" style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">SERVICE</td>

				
			</tr>
			<?php
			$no=1;
			/*echo "select b.tanggal,c.airwaybill_kode,c.kota_asal,c.kota_tujuan,c.berat_aktual,c.collie,c.komoditi

							from manifest_detail a 
							inner join manifest b on a.id_manifest=b.id_manifest
							inner join pengiriman c on a.id_pengiriman=c.id_pengiriman where b.manifest_via='vendor' and b.tanggal BETWEEN '$dari' AND '$sampai'
							";*/
			$result = mysqli_query($con, "
							select b.id_manifest,b.tanggal, d.alamat2,d.telp, b.manifest_to, x_namacabang(b.manifest_to) as nama_cabang,d.kode_cabang,d.pic,x_namakotanew(d.id_kota_baru) as kota_cabang,
							b.berat_smu,b.collie_smu,b.manifest_remarks,d.kecamatan_text
							from manifest b
							inner join cabang d on b.manifest_to = d.id_cabang

							where b.manifest_via='$vendor' and b.tanggal BETWEEN '$dari' AND '$sampai' and b.manifest_from = 39 and b.manifest_to !=39 and b.manifest_to !=109
						");
			$last_key = null;

			while($t=mysqli_fetch_assoc($result)){

				$q = mysqli_query($con, "select namalengkap from user where id_cabang = $t[manifest_to] and level='agent' limit 1
							");
					$r=mysqli_fetch_assoc($q);
				echo'
				<tr>
					<td style="font-family:Calibri;text-align:center;font-size:14px;font-color:#000000;border-color:#000;border:1px solid;">'.$no.'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['tanggal'].'</td>
					
					';

					$query_detail=mysqli_query($con,"select  b.airwaybill_kode
												from manifest_detail a
												inner join pengiriman b on a.id_pengiriman = b.id_pengiriman
												where a.id_manifest='".$t['id_manifest']."' ");
													
												echo '<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px 	solid;border-bottom:1px solid;">';
													while($d_detail=mysqli_fetch_assoc($query_detail)){
														
														echo $d_detail['airwaybill_kode'].'<br>';
														
												
														
														
													}
												echo '</td>';

					echo '


					';

					$query_detail=mysqli_query($con,"select   case when b.komoditi='1' then 'P' else 'D' end as komoditi
												from manifest_detail a
												inner join pengiriman b on a.id_pengiriman = b.id_pengiriman
												where a.id_manifest='".$t['id_manifest']."' ");
													
												echo '<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px 	solid;border-bottom:1px solid;">';
													while($d_detail=mysqli_fetch_assoc($query_detail)){
														
														echo $d_detail['komoditi'].'<br>';
														
												
														
														
													}
											echo '</td>';

				
					

					$query_detail=mysqli_query($con,"select   jenis_barang
												from manifest_detail a
												inner join pengiriman b on a.id_pengiriman = b.id_pengiriman
												where a.id_manifest='".$t['id_manifest']."' ");
													
												echo '<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px 	solid;border-bottom:1px solid;">';
													while($d_detail=mysqli_fetch_assoc($query_detail)){
														
														echo $d_detail['jenis_barang'].'<br>';
														
												
														
														
													}
											echo '</td>';
					echo '
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['nama_cabang'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['kode_cabang'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['pic'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['telp'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['alamat2'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['manifest_remarks'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['kota_cabang'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['kecamatan_text'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['berat_smu'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['collie_smu'].'</td>

					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Reguler</td>


					
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
		<script src="js-report-vendor.js"></script>
		<script src="http://code.jquery.com/jquery-2.0.0.min.js"></script>
		<script type="text/javascript">


			function tambah() {
				
				alert('asda');
				var collie   = $('#collie').val();
				alert(collie);
				('#barisdetail2').show();

				/*if (collie == 10){
					alert ('maaf sudah maksimal');
				}
				else{

					
					$('#barisdetail').val(total);


					
					for (i = 2; i < 11; i++) {
					  $('#barisdetail'+i).hide();
					//  alert(i);

					}
					//var koli = 0;
					//var jumlah_detailbarang =  parseInt($('#jumlah_detailbarang').val())+1;

					koli = collie+1;

					//alert(collie+'koli ='+koli);

					for (i = 2; i <= koli; i++) {
					  $('#barisdetail'+i).show();
					//  alert(i);

					}

				//$('.d_koli_ke').attr('max',collie);


				}*/
				
			}



	$('#tambahkoli_min').click(function () {
		var jumlah_detailbarang   = $('#jumlah_detailbarang').val();

		var total = 0;

		if (jumlah_detailbarang == 1){
			alert ('maaf sudah minimal');
		}
		else{
			total = parseInt(jumlah_detailbarang)-1;
			$('#jumlah_detailbarang').val(total);

			
			for (i = 2; i < 21; i++) {
			  $('#barisdetail_barang'+i).hide();
			//  alert(i);

			}
			//var koli = 0;
			var jumlah_detailbarang =  parseInt($('#jumlah_detailbarang').val())+1;

			//koli = collie+1;

			//alert(collie+'koli ='+koli);

			for (i = 2; i < jumlah_detailbarang; i++) {
			  $('#barisdetail_barang'+i).show();
			//  alert(i);

			}
		}
		
	});
			
		</script>
	</body>
</html>