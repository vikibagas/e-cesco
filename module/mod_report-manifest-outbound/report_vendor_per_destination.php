<?php
include ('../../config/connection.php');
include ('../../config/encrypt.php');
include ('../../config/library.php');
	
$key = "asdf$1011";
$encrypt = "$_GET[id]";

$id = base64_decrypt($encrypt,$key);
			
$query = mysqli_query($con, "select b.alamat2 as alamat_tujuan,b.telp as telp_tujuan,b.id_cabang as id_cabang_tujuan,a.tanggal, c.nama_cabang as asal_cabang, case when x_intercity(b.id_kota)='Y' then 'JAKARTA' else b.nama_cabang end as tujuan, case when a.manifest_via='1' then 'UDARA' when a.manifest_via='2' then 'DARAT' when a.manifest_via='3' then 'LAUT' when a.manifest_via='4' then 'TOTO' when a.manifest_via='5' then 'HERONA' when a.manifest_via='6' then 'BES' when a.manifest_via='7' then 'Indah Cargo' when a.manifest_via='8' then 'JNE' when a.manifest_via='9' then 'TIKI' else a.manifest_via end as manifest_via, a.no_smu, x_user(packed_by) as packed,
	berat_aktual_volume,collie_barang 

	from manifest a inner join cabang b on b.id_cabang=a.manifest_to inner join cabang c on c.id_cabang=a.manifest_from where a.id_manifest='$id'");
$r = mysqli_fetch_assoc($query);



$u_to = mysqli_query($con,"select namalengkap from user where id_cabang= '".$r['id_cabang_tujuan']."' and level = 'agent'  ");
$r_to = mysqli_fetch_assoc($u_to);

$tanggal = tgl_indo($r['tanggal']);


$q = mysqli_query($con,"select koli_ke,berat_aktual,volume from manifest_collie_vendor where id_manifest =".$id."");
		  $cek = mysqli_num_rows($q);

?>
<!DOCTYPE html>
<html>
	<head>
	<meta charset="UTF-8">
		<title>Manifest Outbound</title>
		<link rel="stylesheet" href="../../bootstrap/css/bootstrap.min.css">
                <!-- Font Awesome
                <link rel="stylesheet" href="../../https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">-->
                
                <!-- jQueryUI -->
                <link rel="stylesheet" href="../../plugins/jQueryUI/jquery-ui.min.css">
                <!-- iCheck for checkboxes and radio inputs -->
              
                <!-- DataTables
                <link rel="stylesheet" type="text/css" href="plugins/DataTables/datatables.min.css"/>-->
                <link rel="stylesheet" href="../../plugins/datatables/dataTables.bootstrap.css">
                
                <link href="../../plugins/x-editable/bootstrap3-editable/css/bootstrap-editable.css" rel="stylesheet">
                
                <!-- Select2 -->
              
	</head>
	<style type="text/css">
		input {
		  outline: 0;
		  border-width: 0 0 1px;
		  border-color: #000;
		}
		input:focus {
		  border-color: green;
		  outline: 1px dotted #000;
		}

	</style>
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
			  <td colspan="6">&nbsp;</td>
              <td colspan="3">
              	<?php if($cek < 1){  ?>
               <button type="button" class="btn   btn-primary pull-right tombol_print" onclick="print_hasil()" id="<?php echo $id; ?>">save dan print</button>
           <?php } ?>
           </td>
			  		    </tr>
		    <tr>
				<td colspan="2" style="font-family:Calibri;font-size:14px;font-weight:bold;color:#000000;">MANIFEST DARI</td>
				<td colspan="6" style="font-family:Calibri;font-size:14px;font-weight:bold;color:#000000;">: <?php echo''.strtoupper($r['asal_cabang']).'';?></td>

              <td colspan="3" style="font-family:Calibri;font-size:14px;font-weight:bold;color:#000000;"> Berat(KG) </td>
              <td colspan="3" style="font-family:Calibri;font-size:14px;font-weight:bold;color:#000000;"> VOLUME(PxLXT) CM </td>
			
		  </tr>
				
		  <tr>
			  <td colspan="2" style="font-family:Calibri;font-size:14px;font-weight:bold;color:#000000;">VIA</td>
              <td colspan="6" style="font-family:Calibri;font-size:14px;font-weight:bold;color:#000000;">: <?php echo''.$r['manifest_via'].'';?></td>
              
		  </tr>
		  <?php 
		  
		  echo '<input type="hidden"  id="jumlah_koli"  value="'.($cek > 0 ? $cek : $_GET['koli']).'"> ';
		  if($cek > 0){
		  	while($data= mysqli_fetch_assoc($q)){
		  ?>
			  	<tr>
				  <td colspan="4" style="font-family:Calibri;font-size:14px;font-weight:bold;color:#000000;">&nbsp;</td>
	              <td colspan="4" style="font-family:Calibri;font-size:14px;font-weight:bold;color:#000000;">&nbsp;</td>
	              <td colspan="1" style="font-family:Calibri;font-size:14px;font-weight:bold;color:#000000;">Koli <?php echo $data['koli_ke']; ?>  </td>
	              <td colspan="2" style="font-family:Calibri;font-size:14px;font-weight:bold;color:#000000;">
	              	
	              	<input type="number" min="0" class="berat" id="berat1" placeholder="Berat (KG)" name="berat[]" value="<?php echo $data['berat_aktual']; ?>"></td>
	             
	              <td colspan="2" style="font-family:Calibri;font-size:14px;font-weight:bold;color:#000000;"><input type="text" class="koli" id="koli1" placeholder="VOLUME(PXLXT)" name="koli[]" value="<?php echo $data['volume']; ?>">
	             
											
	              </td>
			  	</tr>
		  <?php
		  	}
		  }else{
		  	
		  
		 // echo $data;



		  ?>
		  <tr>
			  <td colspan="4" style="font-family:Calibri;font-size:14px;font-weight:bold;color:#000000;">&nbsp;</td>
              <td colspan="4" style="font-family:Calibri;font-size:14px;font-weight:bold;color:#000000;">&nbsp;</td>
              <td colspan="1" style="font-family:Calibri;font-size:14px;font-weight:bold;color:#000000;">Koli 1 </td>
              <td colspan="2" style="font-family:Calibri;font-size:14px;font-weight:bold;color:#000000;">
              	<input type="hidden"  id="jumlah_koli"  value="<?php echo $_GET['koli']; ?>"> 
              	<input type="number" min="0" class="berat" id="berat1" placeholder="Berat (KG)" name="berat[]"></td>
             
              <td colspan="2" style="font-family:Calibri;font-size:14px;font-weight:bold;color:#000000;"><input type="text" class="koli" id="koli1" placeholder="VOLUME(PXLXT)" name="koli[]">
             
										
              </td>
		  </tr>
		  <?php 
		  $koli = "$_GET[koli]";
		  for ($i=2;$i<=$koli;$i++) {
		  
		  ?>
		  <tr style="display: ;" id="barisdetail<?php echo $i; ?>">
			  <td colspan="4" style="font-family:Calibri;font-size:14px;font-weight:bold;color:#000000;">&nbsp;</td>
              <td colspan="4" style="font-family:Calibri;font-size:14px;font-weight:bold;color:#000000;">&nbsp;</td>
              <td colspan="1" style="font-family:Calibri;font-size:14px;font-weight:bold;color:#000000;">Koli <?php echo $i; ?></td>
              <td colspan="2" style="font-family:Calibri;font-size:14px;font-weight:bold;color:#000000;"><input type="number" min="0" class="berat" id="berat<?php echo $i;?>" placeholder="Berat (KG)" name="berat[]"></td>
              <td colspan="2" style="font-family:Calibri;font-size:14px;font-weight:bold;color:#000000;"><input type="text" class="koli" id="koli<?php echo $i;?>" placeholder="VOLUME(PXLXT)" name="koli[]"></td>
		  </tr>

		  <?php 
		  }
		}
		  ?>
		
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
				<td width="3%" style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">SERVICE</td>
				<td width="3%" style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">REMARKS</td>

				
			</tr>
			<?php
			$no=1;
			$result = mysqli_query($con,"select airwaybill_kode, x_namakustomer(id_kustomer) as kustomer, collie,alamat_penerima,alamat_penerima2, coalesce(berat_aktual,berat_barang) as berat_aktual, case when komoditi='1' then 'P' else 'D' end as komoditi, if(id_kota_tujuan>0,kota_tujuan,x_namakota(lokasi_tujuan)) as tujuan, x_service(service) as service, alamat_penerima,perusahaan_penerima, case when packing_kayu='1' then 'Packing Oleh Pemilik' when packing_kayu='2' then 'Packing Kayu Oleh Cesco Log.' when packing_kayu='3' then 'Non Packing Kayu Oleh Cesco Log.' end as packing_kayu , jenis_barang, x_namacabang(delivery_by_agent) as delivery_by_agent,hp_penerima,alamat_penerima,alamat_penerima2, kecamatan_tujuan,kota_tujuan, x_user(delivery_by) as delivery_by,c.tanggal as tanggal_manifest, b.kota_asal,b.kota_tujuan,d.pic,d.telp,d.alamat2,a.remarks,a.id_manifest_detail

				from manifest_detail a 
				inner join pengiriman b on b.id_pengiriman=a.id_pengiriman 
				inner join manifest c on a.id_manifest = c.id_manifest
				
				inner join cabang d on c.manifest_to = d.id_cabang


				where a.id_manifest='$id' order by b.created_date asc");
			
			while($t=mysqli_fetch_assoc($result)){
				echo'
				<tr>
					<td style="font-family:Calibri;text-align:center;font-size:14px;font-color:#000000;border-color:#000;border:1px solid;">'.$no.'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['tanggal_manifest'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['airwaybill_kode'].'</td>
					';

					echo '
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">CESCO JAKARTA</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['kota_tujuan'].'</td>					
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['pic'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['telp'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['alamat2'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['berat_aktual'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.strtoupper($t['collie']).'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['komoditi'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['jenis_barang'].'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.strtoupper($t['service']).'</td>
					<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;"><a href="#" class="remarks" data-type="textarea" data-pk="'.$t['id_manifest_detail'].'">'.$t['remarks'].'</a></td>
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
	<script src="../../plugins/jQuery/jQuery-2.1.4.min.js"></script>
           
            <script src="../../bootstrap/js/bootstrap.min.js"></script>
            
            <!-- DataTables -->
            <!--<script type="text/javascript" src="plugins/DataTables/datatables.min.js"></script>-->
            
            <script src="../../plugins/datatables/dataTables.bootstrap.min.js"></script>
           
            <script src="../../plugins/x-editable/bootstrap3-editable/js/bootstrap-editable.js"></script>
           

            <!-- SlimScroll -->
           
            <!-- page script -->
           
            <script src="../../dist/js/main.js"></script>
            <script src="../../dist/js/x-editable.js"></script>
           
            <script src="../../notif.js"></script>
	<script type="text/javascript">
		//window.print();
		//window.onfocus=function(){ window.close();}
		$(document).ready(function(e) {   
			$(".tombol_print").click(function(){
		//function print_hasil(clicked_id) {
			//var 
			//var id_manifest = clicked_id;
			var id_manifest = $(this).attr("id")
					//alert(id_manifest);
				var jumlah_koli = $('#jumlah_koli').val();
				$(".tombol_print").hide();
				$(".btn_catatan").hide();
				

				var data_berat = $('input[name="berat[]"]').map(function(){
				   return this.value;
				}).get();

				var data_koli = $('input[name="koli[]"]').map(function(){
				   return this.value;
				}).get();

				//alert (data_berat);
				//alert (data_koli);
				
				
				$.ajax({
					type: 'POST',
					dataType: 'json',
					data: {
						
						idmanifest:id_manifest,
						databerat:data_berat,
						datakoli:data_koli,
						jumlahkoli:jumlah_koli
					},
					url: 'report-manifest-outbound_action.php?module=outbound&act=proses-save-report-vendor',
					success: function (result) {
						//alert('asda');
						//$('#intercityasal').val(result['intercity']);
						if(result.success > 0){
							alert(result.message);
						}else{

							alert(result.message);
						}

							

					}
				});
				window.print();
				

			
			});
		 });

		
		</script>
	</body>
</html>