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
		  </tr>
		  <input type="hidden" name="collie" value="1" id="collie">
		  <tr>
			  <td colspan="2" style="font-family:Calibri;font-size:14px;font-weight:bold;color:#000000;">&nbsp;</td>
              <td colspan="2" style="font-family:Calibri;font-size:14px;font-weight:bold;color:#000000;">&nbsp;</td>
              <td colspan="1" style="font-family:Calibri;font-size:14px;font-weight:bold;color:#000000;">Koli 1</td>
              <td colspan="2" style="font-family:Calibri;font-size:14px;font-weight:bold;color:#000000;"><input type="text" id="berat1" placeholder="Berat (KG)" name="berat[1]">kg</td>
             
              <td colspan="2" style="font-family:Calibri;font-size:14px;font-weight:bold;color:#000000;"><input type="text" id="koli1" placeholder="VOLUME(CM)" name="koli[1]">CM
             <!-- <button type="button" class="btn   btn-primary pull-right" onclick="tambah()" id="tambahkoli_plus" style="margin-right:0px;margin-left:10px;">+</button>
										<button type="button" class="btn  pull-right" id="tambahkoli_min">-</button>-->
              </td>
		  </tr>
		  <?php 
		  for ($i=2;$i<=10;$i++) {
		  
		  ?>
		  <tr style="display: ;" id="barisdetail<?php echo $i; ?>">
			  <td colspan="2" style="font-family:Calibri;font-size:14px;font-weight:bold;color:#000000;">&nbsp;</td>
              <td colspan="2" style="font-family:Calibri;font-size:14px;font-weight:bold;color:#000000;">&nbsp;</td>
              <td colspan="1" style="font-family:Calibri;font-size:14px;font-weight:bold;color:#000000;">Koli <?php echo $i; ?></td>
              <td colspan="2" style="font-family:Calibri;font-size:14px;font-weight:bold;color:#000000;"><input type="text" id="berat<?php echo $i;?>" placeholder="Berat (KG)" name="berat[<?php echo $i;?>]">kg</td>
              <td colspan="2" style="font-family:Calibri;font-size:14px;font-weight:bold;color:#000000;"><input type="text" id="koli<?php echo $i;?>" placeholder="VOLUME(CM)" name="koli[<?php echo $i;?>]">CM</td>
		  </tr>

		  <?php } ?>
			<tr>
				<td colspan="8">&nbsp;</td>
			</tr>
			<tr>
				<td width="1%" style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border:1px solid;">NO</td>
				<td width="7%" style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">TANGAGL</td>
				<td width="15%" style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">AWB</td>
				<td width="15%" style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">KOTA ASAL
				</td>				
				<td width="12%" style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">KOTA TUJUAN</td>

				<td width="12%" style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">PENERIMA</td>
				<td width="12%" style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">HP PENERIMA</td>
				<td width="12%" style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">ALAMAT</td>
				<td width="3%" style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">BERAT</td>
				<td width="3%" style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">KOLI</td>
				<td width="3%" style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">D/P</td>
				<td width="3%" style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">KOMODITI</td>

				
			</tr>
			<?php
			$no=1;
			/*echo "select b.tanggal,c.airwaybill_kode,c.kota_asal,c.kota_tujuan,c.berat_aktual,c.collie,c.komoditi

							from manifest_detail a 
							inner join manifest b on a.id_manifest=b.id_manifest
							inner join pengiriman c on a.id_pengiriman=c.id_pengiriman where b.manifest_via='vendor' and b.tanggal BETWEEN '$dari' AND '$sampai'
							";*/
			$result = mysqli_query($con, "select b.tanggal,c.airwaybill_kode,c.kota_asal,c.kota_tujuan,c.berat_aktual,c.collie,case when c.komoditi='1' then 'P' else 'D' end as komoditi,jenis_barang,b.manifest_to, d.alamat2

							from manifest_detail a 
							inner join manifest b on a.id_manifest=b.id_manifest
							inner join pengiriman c on a.id_pengiriman=c.id_pengiriman 
							inner join cabang d on b.manifest_to = d.id_cabang

							where b.manifest_via='$vendor' and b.tanggal BETWEEN '$dari' AND '$sampai' and b.manifest_from = 39 and b.manifest_to !=39 order by b.manifest_to,c.kota_tujuan asc
							");
			while($t=mysqli_fetch_assoc($result)){

				$q = mysqli_query($con, "select namalengkap,telp from user where id_cabang = $t[manifest_to] and level='agent' limit 1
							");
					$r=mysqli_fetch_assoc($q);
				echo'
				<tr>
					<td style="font-family:Calibri;text-align:center;font-size:14px;font-color:#000000;border-color:#000;border:1px solid;">'.$no.'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['tanggal'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['airwaybill_kode'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['kota_asal'].'</td>					
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['kota_tujuan'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$r['namalengkap'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$r['telp'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['alamat2'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['berat_aktual'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['collie'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['komoditi'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['jenis_barang'].'</td>

					
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