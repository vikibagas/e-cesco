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
$query = mysqli_query($con, "select *,x_namarak(id_rak) as rak,x_namakustomer(pemilik_customer) as kustomer from warehouse_barang where id = '".$id."' ");

$cek = mysqli_num_rows($query);
//
$r = mysqli_fetch_assoc($query);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />
	
	<title>Print <?php echo $r['nama_barang']; ?></title>
	
	<link rel='stylesheet' type='text/css' href='../dist/css/print_label.css' />
	
	<style>
		img.center {
			display: block;
			margin: 0 auto;
		}

	</style>


	

</head>

<body>

	<div id="page-wrap-landscape">

		
		<table id="items" style="text-align: left; " frame="box">
				
				<tr style="padding: 5px; margin-bottom : 2px 0 0 0; border: 2px solid black;">
					<td  colspan="4">
						<img style="margin-left: 5px;" src="../dist/img/cesco-new-logo21.png" height="65px" width="auto" >
					</td>
					<td colspan="2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
					<td  colspan="4">	
							<div style="font-weight:bold;text-align:left;font-size:22px" class="center"> CESCO LOGISTICS </div> 	
							<div style="text-align:left;font-size:15px" class="center"> WWW.CESCO.LOGISTICS.COM </div> 	
					</td>
					
				</tr>
				<tr style="padding: 5px; margin-bottom : 2px 0 0 0; border: 2px solid black;">
					<td  colspan="4">	
					  		<img src="fragilenew.jpeg" style="margin-left: 5px;" height="225px" width="auto" >
					</td>
					<td colspan="2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
					<td  colspan="4">	<?php QRcode::png("".$r['kode_barang']."","code/".$r['kode_barang'].".png","M",3,3); 
		  				echo '<img id="center-imd" style="width:200px; height:auto;" src="code/'.$r['kode_barang'].'.png" >'  ?>   						
					</td>
					

				</tr>
				<tr style="padding: 5px; margin-bottom : 2px 0 0 0; border: 2px solid black;"	>
					<td  colspan="1">&nbsp;</td>
					<td  colspan="3"><div style="font-weight:bold;text-align:left;font-size:18px">Customer Name   </div></td>
					<td  colspan="6">
						<div style="font-weight:bold;text-align:left;font-size:18px">  <?php echo $r['kustomer']; ?> </div>
						
					</td>
				</tr>
				<tr style="padding: 5px; margin-bottom : 2px 0 0 0; border: 2px solid black;">
					<td  colspan="1">&nbsp;</td>
					<td  colspan="3"><div style="font-weight:bold;text-align:left;font-size:18px">Product Name  </div></td>
					<td  colspan="6">
						<div style="font-weight:bold;text-align:left;font-size:18px">  <?php echo $r['nama_barang']; ?> </div>
						
					</td>
				</tr>
				<tr style="padding: 5px; margin-bottom : 2px 0 0 0; border: 2px solid black;">
				<td  colspan="1">&nbsp;</td>
					<td  colspan="3"><div style="font-weight:bold;text-align:left;font-size:18px">Product Code / Dept  </div></td>
					<td  colspan="6">
						<div style="font-weight:bold;text-align:left;font-size:18px">  <?php echo $r['kode_barang']; ?> </div>
						
					</td>
				</tr>
				<tr style="padding: 5px; margin-bottom : 2px 0 0 0; border: 2px solid black;"> 
					<td  colspan="1">&nbsp;</td>
					<td  colspan="3"><div style="font-weight:bold;text-align:left;font-size:18px">Date Of Entry  </div></td>
					<td  colspan="6">
						<div style="font-weight:bold;text-align:left;font-size:18px">  <?php echo $r['created_date']; ?> </div>
						
					</td>
				</tr>
				
		</table>
		<br>
		
		
	
	</div>
	
	
</body>

</html>