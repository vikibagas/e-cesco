<?php
include ('../config/connection.php');
include ('../config/encrypt.php');
include ('../config/library.php');
include ('../reports/phpqrcode/qrlib.php'); 
	
session_start();

$key = "asdf$1011";
//$encrypt = "$_POST[id]";
//$id = base64_decrypt($encrypt,$key);

$encrypt = $_SESSION['id_user'];
$id = base64_decrypt($encrypt,$key);
$useraktif = mysqli_query($con, 'select a.*, c.cabang_cesco from user a inner join cabang c on c.id_cabang = a.id_cabang  where id_user="'.$id.'"');
$u = mysqli_fetch_assoc($useraktif);

$awb = implode("','", $_POST['idprintor']);
$awb = "'".$awb."'";

$query = mysqli_query($con, "select * from pembayaran_or where airwaybill in (".$awb.")");

$cek = mysqli_num_rows($query);
//echo $cek;

$arrHarga = array();
$arrKet = array();
$noCs = '';
$tglPrint = '';
$receivedFrom = '';
while($row = mysqli_fetch_assoc($query)){
	$arrHarga[$row['airwaybill']] = $row['jumlah_bayar'];
	$arrKet[$row['airwaybill']] = $row['keterangan_bayar'];

	if ($noCs == '' && $row['cs_number'] != ''){
		$noCs = $row['cs_number'];
	}
	if ($tglPrint == '' && $row['tanggal_cetak'] != ''){
		$tglPrint = $row['tanggal_cetak'];
	}
	if ($receivedFrom == '' && $row['diterima_dari'] != ''){
		$receivedFrom = $row['diterima_dari'];
	}
	
}



?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />
	
	<title>Print OR Cesco Logistics</title>
	
	<link rel='stylesheet' type='text/css' href='../dist/css/print_to.css' />
	<link rel='stylesheet' type='text/css' href='css/print.css' media="print" />
	
	<style>
		img.center {
			display: block;
			margin: 0 auto;
		}

		.keterangan {
			left: 55%;
			position: absolute;
		}
	</style>


	<script src="../plugins/jQuery/jQuery-2.1.4.min.js"></script>
	<script src="../dist/js/invoice-or.js"></script>

</head>

<body>

	<div id="page-wrap">

		<form id='frmOr' method="POST">
		<table id="items" style="text-align: left;" frame="box">
				
				<tr>
					<td rowspan="2" colspan="5">
					<img src="../dist/img/cesco-new-logo21.png" height="80px" width="auto" class="center">
					</td>
					<td colspan="10">
					
					</td>
					<td colspan="2" style="font-weight: bold;text-align:right;font-size: 15px;">
					No. CS-  
					<span id="spNo"><input id='txtNo' name='txtNo' type='text' value='<?php echo $noCs;?>' style="width:50%;"></span>
					</td>
				</tr>
				<tr>
					<td colspan="10">
					
					</td>
					<td colspan="2" style="font-weight: bold;;text-align:right;font-size: 15px;">
					Date: <span id="spDate"><input id='txtDate' name='txtDate' type='text' style="width:50%;" value="<?php echo ($tglPrint == '') ? date('d F Y') : $tglPrint; ?>"></span>
					</td>
				</tr>
				<tr>
					<td colspan="5" style="background-color: black;color: white;font-size: 20px;text-align: center;"> OFFICIAL RECEIPT	</td>
					<td colspan="12"></td>
				</tr>
				<tr>
					<td style="width: 10px;">&nbsp;</td>
					<td style="width: 150px;">&nbsp;</td>
					<td style="width: 5px;">&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td colspan="2" style="width:50px;font-size: 15px; text-align:left;" > Received From</td>
					<td>:</td>
					<td colspan="14" id="tdReceiveFrom" style="width:50px;font-size: 15px; text-align:left;"><input id='txtReceiveFrom' name='txtReceiveFrom' type='text' value='<?php echo $receivedFrom;?>' style="width:99%;"></td>
				</tr>
				<tr>
					<td colspan="2" style="font-size: 15px; text-align:left;" > The Sum of Rupiah</td>
					<td>:</td>
					<td colspan="14" id="tdSum" style="width:50px;font-size: 15px; text-align:left;text-transform: uppercase;"><input id='txtSum' name='txtSum' type='text' style="width:99%;"></td>
				</tr>
				<tr>
					<td colspan="2" style="font-size: 15px; text-align:left;vertical-align:top;" > Remarks</td>
					<td style="vertical-align: top;">:</td>
					<td colspan="14" style="width:50px;font-size: 15px; text-align:left;">
						<?php
							//$arrKet =array();
							//$arrHarga = array();
						//echo $_POST['idprintor'];
						//if($cek > 0){
							foreach($_POST['idprintor'] as $key => $val){
								echo $val;
								echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input name='txtAwb[]' value='".$val."' type='hidden'><span id='sp".$val."'><input id='txt".$val."' name='txtBayar[]' value='".($cek > 0 ? $arrHarga[$val] : '')."' type='text' onkeypress='return isNumber(event)' onchange='sumRupiah()' placeholder='Harga (Tanpa titik/koma)'>&nbsp;&nbsp;<input id='txtKet".$val."' name='txtKet[]' value='".($cek > 0 ? $arrKet[$val] : '')."' type='text' style='width: 46%;' placeholder='Keterangan'></span><hr>";
							}
						//}
						?>
						
					</td>
				</tr>
				<tr>
					<td style="font-size: 15px; text-align:left;" > &nbsp;</td>
					<td colspan="5"></td>
					<td colspan="11"></td>
				</tr>
				<tr>
					<td style="font-size: 15px; text-align:left;" ></td>
					<td colspan="12"></td>
					<td colspan="4" style="font-weight:bold;text-align:center;font-size:15px">PT. CESCO LOGISTIK INDONESIA</td>
				</tr>
				<tr>
					<td style="font-size: 15px; text-align:left;" > Rp.</td>
					<td colspan="2" style="border-bottom: 1px double black;width:50px;font-size: 15px; text-align:right;border-bottom-style:double;border-width: medium" id='tdRp'><input id='txtRp' id='txtRpTotal' type='text' style="width:100%;"></td>
					<td colspan="10"></td>
					<td colspan="4" rowspan="4" style="font-weight:bold;text-align:center;font-size:15px"><?php if (strtoupper($_SESSION['username']) == 'SUB0001'){ ?><img src="../dist/img/ttd/SUB0001.png" style="" height="80px" width="auto" class="center"> <?php } ?></td>
				</tr>
				<tr>
					<td style="font-size: 15px; text-align:left;" > &nbsp;</td>
					<td colspan="2" style="font-size: 15px; text-align:left;border-top: 1px double black;border-top-style:double;border-width: thick"></td>
					<td colspan="10"></td>
				</tr>
				<tr>
					<td colspan="4" style="font-size: 15px; text-align:left;" >PLEASE TRANSFER PAYMENT TO :</td>
					<td colspan="9"></td>
					</td>
				</tr>
				<tr>
					<td colspan="4" style="font-size: 15px; text-align:left;font-weight:bold;" >PT CESCO LOGISTIK INDONESIA</td>
					<td colspan="9"></td>
					
				</tr>
				<tr>
					<td colspan="4" style="font-size: 15px; text-align:left;font-weight:bold;" >A/C.  1270004614127</td>
					<td colspan="9"></td>
					<td colspan="4" rowspan="2" style="font-weight:bold;text-align:center;font-size:15px"><?php echo strtoupper($u['namalengkap']);?>
					<hr style="width:170px;text-align:center;margin: auto;"><?php echo (strtoupper($u['level']) == 'AGENT') ? 'ADMIN' : strtoupper($u['level']);?></td>
				</tr>
				<tr>
					<td colspan="4" style="font-size: 15px; text-align:left;font-weight:bold;" >Bank Mandiri</td>
					<td colspan="9"></td>
				</tr>
				<tr>
					<td colspan="4" style="font-size: 15px; text-align:left;font-weight:bold;" >Cabang warung buncit</td>
					<td colspan="9"></td>
					<td colspan="4"></td>
				</tr>
		</table>
		<br>
		<button id="printor" type="button" class="btn btn-primary" onclick="printOr()">&nbsp;Print OR&nbsp;</button>
		</form>		
	
	</div>
	<script>

		function isNumber(evt) {
			evt = (evt) ? evt : window.event;
			var charCode = (evt.which) ? evt.which : evt.keyCode;
			if (charCode > 31 && (charCode < 48 || charCode > 57)) {
				return false;
			}
			return true;
		}

		function terbilang(bilangan) {

			bilangan    = String(bilangan);
			var angka   = new Array('0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0');
			var kata    = new Array('','Satu','Dua','Tiga','Empat','Lima','Enam','Tujuh','Delapan','Sembilan');
			var tingkat = new Array('','Ribu','Juta','Milyar','Triliun');

			var panjang_bilangan = bilangan.length;

			/* pengujian panjang bilangan */
			if (panjang_bilangan > 15) {
				kaLimat = "Diluar Batas";
				return kaLimat;
			}

			/* mengambil angka-angka yang ada dalam bilangan, dimasukkan ke dalam array */
			for (i = 1; i <= panjang_bilangan; i++) {
				angka[i] = bilangan.substr(-(i),1);
			}

			i = 1;
			j = 0;
			kaLimat = "";


			/* mulai proses iterasi terhadap array angka */
			while (i <= panjang_bilangan) {

				subkaLimat = "";
				kata1 = "";
				kata2 = "";
				kata3 = "";

				/* untuk Ratusan */
				if (angka[i+2] != "0") {
					if (angka[i+2] == "1") {
					kata1 = "Seratus";
					} else {
					kata1 = kata[angka[i+2]] + " Ratus";
					}
				}

				/* untuk Puluhan atau Belasan */
				if (angka[i+1] != "0") {
					if (angka[i+1] == "1") {
					if (angka[i] == "0") {
						kata2 = "Sepuluh";
					} else if (angka[i] == "1") {
						kata2 = "Sebelas";
					} else {
						kata2 = kata[angka[i]] + " Belas";
					}
					} else {
					kata2 = kata[angka[i+1]] + " Puluh";
					}
				}

				/* untuk Satuan */
				if (angka[i] != "0") {
					if (angka[i+1] != "1") {
					kata3 = kata[angka[i]];
					}
				}

				/* pengujian angka apakah tidak nol semua, lalu ditambahkan tingkat */
				if ((angka[i] != "0") || (angka[i+1] != "0") || (angka[i+2] != "0")) {
					subkaLimat = kata1+" "+kata2+" "+kata3+" "+tingkat[j]+" ";
				}

				/* gabungkan variabe sub kaLimat (untuk Satu blok 3 angka) ke variabel kaLimat */
				kaLimat = subkaLimat + kaLimat;
				i = i + 3;
				j = j + 1;

			}

			/* mengganti Satu Ribu jadi Seribu jika diperlukan */
			if ((angka[5] == "0") && (angka[6] == "0")) {
				kaLimat = kaLimat.replace("Satu Ribu","Seribu");
			}

			return kaLimat + "Rupiah";
		}

		function numberWithCommas(x) {
			return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
		}

		function sumRupiah(){
			var total = 0;
			
			<?php
			//if($cek > 0){	
				foreach($_POST['idprintor'] as $key => $val){
					?>
					if (document.getElementById('txt<?php echo $val?>').value != ''){
						total = total + parseInt(document.getElementById('txt<?php echo $val?>').value);
					}
					<?php

				}
			//}
			?>

			if (total > 0){
				document.getElementById('txtRp').value = total;
				document.getElementById('txtSum').value = terbilang(total);
			}
			
		}

		function printOr(){


			/////
			
			//e.preventDefault();
			var form = $('#frmOr');

			$.ajax({
				type: "POST",
				url: '../module/mod_invoice-or/invoice-or_action.php?module=invoice-or&act=bayar',
				data: form.serialize(), // serializes the form's elements.
				success: function(data)
				{

					document.getElementById('spNo').innerHTML = document.getElementById('txtNo').value;
					document.getElementById('spDate').innerHTML = document.getElementById('txtDate').value;
					document.getElementById('tdReceiveFrom').innerHTML = document.getElementById('txtReceiveFrom').value;
					
					<?php
					//if($cek > 0	) {
						foreach($_POST['idprintor'] as $key => $val){
							?>
							document.getElementById('sp<?php echo $val?>').innerHTML = numberWithCommas(document.getElementById('txt<?php echo $val?>').value)+"&nbsp;&nbsp;<span class='keterangan'>"+document.getElementById('txtKet<?php echo $val?>').value+"</span>";
							<?php
						
						}
				//	}
					?>

					if (document.getElementById('txtSum').value == ''){
						document.getElementById('txtSum').value = terbilang(document.getElementById('txtRp').value);
					}
					document.getElementById('tdSum').innerHTML = document.getElementById('txtSum').value;
					

					document.getElementById('tdRp').innerHTML = numberWithCommas(document.getElementById('txtRp').value);

					

					document.getElementById('printor').style.visibility = 'hidden';

					window.print();
					
				}
			});

			///////


			
		}

		sumRupiah();
	</script>
	
</body>

</html>