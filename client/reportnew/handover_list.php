<?php
if(!isset($_SESSION)){
    session_start();
}

if (isset($_SESSION['username']) || isset($_SESSION['usernamex'])){
	
	


include ('../../config/connection.php');
include ('../../config/encrypt.php');
include ('../../config/library.php');
include ('../../reports/phpqrcode/qrlib.php'); 

$key = 'asdf$1011';
	$encrypt = $_SESSION['id_userx'];
	$id_user = base64_decrypt($encrypt,$key);

//	echo $encrypt.'<br>'.$id;


//$tanggal = $_POST['tanggal'];
		$tgl = mysqli_real_escape_string($con, $_POST['tanggal']);

		$tanggal = date('Y-m-d',strtotime($tgl));

		$jam_dari = mysqli_real_escape_string($con, $_POST['jam_dari']);
		$jam_sampai = mysqli_real_escape_string($con, $_POST['jam_sampai']);
		$kustomer = $_POST['kustomer'];
		//$bookingan = $_POST['bookingan'];



$awal = $tanggal.' '.$jam_dari;
$akhir = $tanggal.' '.$jam_sampai;


$sql = mysqli_query($con, "select 

x_namakustomer($kustomer) as nama_kustomer

from kustomer 

where id_kustomer='$kustomer' limit 1  ");
$r = mysqli_fetch_assoc($sql);

		

	
$key = "asdf$1011";
$encrypt = "sAGC7feni5XI@653wU120trzK6gQAhjcUHeb7q/VzrA=";
$id = base64_decrypt($encrypt,$key);

$query = mysqli_query($con, "select 

airwaybill_kode, berat_aktual,kota_tujuan,jenis_barang,nama_pengirim,collie,x_namakustomer($kustomer) as nama_kustomer

from pengiriman 

where id_kustomer='$kustomer' and created_date between '$awal' and '$akhir' and status !=7  ");




//$r = mysqli_fetch_assoc($query);

	
//QRcode::png("".$r['airwaybill_kode']."","image.png","L",3,3);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />
	
	<title>Airwaybill Cesco Logistics</title>
	
	<link rel='stylesheet' type='text/css' href='../../dist/css/airwaybill_2020.css' />
	
	
	<style type="text/css">
		table {border: none;}
		@media print 
		{
		    @page {
		       /* DIN A4 standard, Europe */
		      margin-left:0.5cm;
		      margin-right: 0.5cm;
		      margin-top: 0.05cm ;
		      margin-bottom: 0.05cm;
		    }
		    
		    body {
		       
		    }

		    
		}
		
	</style>

</head>

<body>

	<div id="page-wrap" style="height:293mm; ">

		<h2 style="text-align:center;">HANDOVER MANIFEST</h2><br><br>
		
		<?php 
			$tanggal_tampil = $tanggal.' ('.$jam_dari.' - '.$jam_sampai.')';
		?>
		
	
		
		<table style="width: 100%;">
			<tr>
				<td style="border: none;"><div style="text-align:left;"><b>Tanggal</b> : <?php echo $tanggal_tampil; ?></div></td>
				<td style="border: none;"><div style="text-align:right;"><b>Customer</b> : PT DIGITAL APLIKASI SOLUSINDO</div></td>
			</tr>
		</table>
		
		
		<table id="items">
		 	<tr>
			 	<th>NO</th>
			 	<th>REF NO</th>
			 	<th>AIRWAYBILL</th>
			   	<th>QTY</th>
			   	<th>WEIGHT</th>
			   	<th>DEST</th>
			   	<th>GOOD DESC</th>
			   	<th>CHECK</th>
			</tr>
		   	<?php
		   		$cek = mysqli_num_rows($query);

				if($cek < 1){
					echo '<img src="tidak_ada_data.png"><h2>Tidak ada data, ganti tanggal lain</h2>';
				}else{
					$no = 1;
					$j = 0;
					while ($r = mysqli_fetch_assoc($query)) {
						
						
						echo '
							<tr>
							 	<td>'.$no.'</td>
							 	<td>'.$r['nama_pengirim'].'</td>
							 	<td>'.$r['airwaybill_kode'].'</td>
							   	<td>'.$r['collie'].'</td>
							   	<td>'.$r['berat_aktual'].'</td>
							   	<td>'.$r['kota_tujuan'].'</td>
							   	<td>'.$r['jenis_barang'].'</td>
							   	<td></td>
							</tr>
						';
						$j= $j+$r['collie'];
						$no++;
					}
					echo '
							<tr>
							   	<td colspan="3">TOTAL</td>
							   	<td >'.$j.'</td>

							</tr>
						';

				}
		   	?>

		   	
		</table>
		<br>
		<table style="width: 100%;">
			<tr>
				<td style="border:none;width: 15%;">Customer</td>
				<td style="border:none; width: 70%;" colspan="7">&nbsp;</td>
				<td style="border: none; width: 15%; " >Courier Pickup</td>
			</tr>
			<tr>
				<td style="border:none;"></td>
				<td style="border:none;" colspan="7">&nbsp;</td>
				<td style="border:none;"> </td>

			</tr>
			<tr>
				<td style="border:none;"></td>
				<td style="border:none;" colspan="7">&nbsp;</td>
				<td style="border:none;"></td>

			</tr>
			<tr>
				<td style="border:none;"></td>
				<td style="border:none;" colspan="7">&nbsp;</td>
				<td style="border:none;"></td>

			</tr>
			<tr>
				<td style="border:none;">________________</td>
				<td style="border:none;" colspan="7">&nbsp;</td>
				<td style="border:none;">________________</td>

			</tr>
		</table>

		<p style="text-align:center;">Mohon di print dan di serahkan ke petugas pickup kami guna keperluan checking dan handover</p>
	
	</div>
	
</body>

</html>
<?php 

}// ini session
else{
		header('location:../../ecesco/login.php');

}

?>
