<?php
include ('../config/connection.php');
include ('../config/encrypt.php');
include ('../config/library.php');
include ('../reports/phpqrcode/qrlib.php'); 

session_start();

$key = "asdf$1011";
//$encrypt = "$_POST[id]";
//$id = base64_decrypt($encrypt,$key);

$encrypt = $_GET['id'];
$id = base64_decrypt($encrypt,$key);
$query = mysqli_query($con, "select a.*, x_namacabang(manifest_from) as agent_from , x_namacabang(manifest_to) as agent_to ,
sum(coalesce(c.berat_aktual,c.berat_barang)) as jumlah_berat, 
x_idkotacabang(manifest_from) as idkota_cabangfrom,
x_namakotanew( (select idkota_cabangfrom) ) as kota_asal,
x_idkotacabang(manifest_to) as idkota_cabangto,
x_namakotanew( (select idkota_cabangto) ) as kota_tujuan,
x_kodekotabaru( (select idkota_cabangfrom) ) as kodekota_asal,
x_kodekotabaru( (select idkota_cabangto) ) as kodekota_tujuan

from manifest a 
	inner join manifest_detail b on b.id_manifest=a.id_manifest 
	inner join pengiriman c on c.id_pengiriman=b.id_pengiriman 

where a.id_manifest='$id'");



$r = mysqli_fetch_assoc($query);


//echo "select kode_manifest from manifest a left join manifest_detail b on a.id_manifest=b.id_manifest where b.id_pengiriman =$id and a.manifest_from=".$pickup_by_agent." limit 1 ";





//echo '<br>'. $cek;


 //QRcode::png("".$r['airwaybill_kode']."","code/".$r['airwaybill_kode'].".png","M",3,3); 
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />
	
	<title>Print Outbound Label</title>
	
	<link rel='stylesheet' type='text/css' href='../dist/css/print_label.css' />
	
	<style>
		img.center {
			display: block;
			margin: 0 auto;
		}

		@media print 
		{
		


			
		}
	</style>


	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

</head>

<body>
	<!--<h1 id="warning-print"> Harap Buat Manifest outbound terlebih dahulu sebelum bisa print</h1>-->
	<div id="page-wrap">

		
		
		
		<table id="items" style="text-align: left; margin-bottom: 10px; " frame="box">
				
				<tr style="margin-bottom : 2px 0 0 0; border: 2px solid black; ">
					<td style="padding:5px;"  colspan="2" >
						<img style="margin-left: auto; margin-right: auto; display: block; width: 50%;" src="../../assets/img/cesco-new-logo21.png" >
						
					</td>
				</tr>
				<tr style="padding: 5px; margin-bottom : 2px 0 0 0; border: 2px solid black;">
					<td style="padding:5px;"  style="width: 50%;">	
					  		<h1>MANIFEST NO</h1>
					</td>
					<td style="padding:5px;" style="width: 50%;">	<h1><?php echo $r['kode_manifest'];  ?>		</h1>		
					</td>
					

				</tr>
				<tr style="padding: 5px; margin-bottom : 2px 0 0 0; border: 2px solid black;">
					<td style="padding:5px;"  >	
					  		<h1>TANGGAL</h1>
					</td>
					<td style="padding:5px;" >	<h1><?php echo $r['tanggal'];  ?>		</h1>		
					</td>
					

				</tr>
				
				
					

				</tr>
				<tr style="padding: 5px; margin-bottom : 2px 0 0 0; border: 2px solid black;">
					<td style="padding:5px;"  >	
					  		<h1>CABANG TUJUAN</h1>
					</td>
					<td style="padding:5px;" >	<p style="font-size: 3.3em; font-weight:  bold;"><?php echo $r['agent_to']; ?>		</p>		
					</td>
					

				</tr>
				<tr style="padding: 5px; margin-bottom : 2px 0 0 0; border: 2px solid black;">
					<td style="padding:5px;"  >	
					  		<h1>VIA</h1>
					</td>
					<td style="padding:5px;" >	<h1><?php echo $r['manifest_via'];  ?>		</h1>		
					</td>
					

				</tr>
				<tr style="padding: 5px; margin-bottom : 2px 0 0 0; border: 2px solid black;">
					<td style="padding:5px;"  >	
					  		<h1>KOTA ASAL</h1>
					</td>
					<td style="padding:5px;" >	<h1><?php echo $r['kota_asal'].'('.$r['kodekota_asal'].')';  ?>		</h1>		
					</td>
					

				</tr>
				<tr style="padding: 5px; margin-bottom : 2px 0 0 0; border: 2px solid black;">
					<td style="padding:5px;"  >	
					  		<h1>KOTA TUJUAN</h1>
					</td>
					<td style="padding:5px;" >	<h1><?php echo $r['kota_tujuan'].'('.$r['kodekota_tujuan'].')';  ?>		</h1>		
					</td>
					

				</tr>	
				<tr style="padding: 5px; margin-bottom : 2px 0 0 0; border: 2px solid black;">
					<td style="padding:5px;"  >	
					  		<h1>ESTIMASI BERAT</h1>
					</td>
					<td style="padding:5px;" >	<h1><?php echo $r['jumlah_berat'];  ?>		KG</h1>		
					</td>
					

				</tr>						
				
		</table>
		
		<br>
		
		
	
	</div>
	
	
</body>

<script type="text/javascript">

</script>

</html>