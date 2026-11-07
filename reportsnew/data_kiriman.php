<?php
include ('../config/connection.php');
include ('../config/encrypt.php');
include ('../config/library.php');




if(isset($_GET['komoditi'])){

	$query_dokumen = mysqli_query($con, "SELECT DATE_FORMAT(created_date, '%m-%Y') AS bulan, count(id_pengiriman) as jumlah_awb, sum(ifnull(if(berat_aktual=0,berat_barang,berat_aktual),berat_barang)) as total_berat, sum(collie) as total_collie from pengiriman WHERE created_date BETWEEN '".$_GET['tahun']."-01-01 00:00:00' AND '".$_GET['tahun']."-12-31 23:59:59' AND status != 7 and komoditi=2 group by MONTH(created_date)");

	$query_paket = mysqli_query($con, "SELECT DATE_FORMAT(created_date, '%m-%Y') AS bulan, count(id_pengiriman) as jumlah_awb, sum(ifnull(if(berat_aktual=0,berat_barang,berat_aktual),berat_barang)) as total_berat, sum(collie) as total_collie from pengiriman WHERE created_date BETWEEN '".$_GET['tahun']."-01-01 00:00:00' AND '".$_GET['tahun']."-12-31 23:59:59' AND status != 7 and komoditi=1 group by MONTH(created_date)");


	if($_GET['komoditi'] == '1'){
		$nama_dokumen = 'PAKET';
	}
	elseif($_GET['komoditi'] == '2'){
		$nama_dokumen = 'DOKUMEN';
	}
}


$komoditi = isset($nama_dokumen) ? $_GET['komoditi'] : null;	

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />
	
	<title>Airwaybill Cesco Logistics</title>
	
	<link rel='stylesheet' type='text/css' href='../dist/css/airwaybill_2020.css' />
	<link rel='stylesheet' type='text/css' href='css/print.css' media="print" />
	
	

</head>

<body>

	<div id="page-wrap">

		
		<form method="get" action="data_kiriman.php">
			<select name="tahun">
				<option value="2019" <?php if(isset($_GET['tahun']) && $_GET['tahun'] == '2019') echo"selected"; ?> >2019</option>
				<option value="2020" <?php if(isset($_GET['tahun']) && $_GET['tahun'] == '2020') echo"selected"; ?>  >2020</option>
				<option value="2021" <?php if(isset($_GET['tahun']) && $_GET['tahun'] == '2021') echo"selected"; ?> >2021</option>
				<option value="2022" <?php if(isset($_GET['tahun']) && $_GET['tahun'] == '2022') echo"selected"; ?> >2022</option>
				<option value="2023" <?php if(isset($_GET['tahun']) && $_GET['tahun'] == '2023') echo"selected"; ?> >2023</option>
				<option value="2024" <?php if(isset($_GET['tahun']) && $_GET['tahun'] == '2024') echo"selected"; ?> >2024</option>
			</select>
			<select name="komoditi">
				<option value="1" <?php if(isset($_GET['komoditi']) && $_GET['komoditi'] == '1') echo"selected"; ?> >paket</option>
				<option value="2" <?php if(isset($_GET['komoditi']) && $_GET['komoditi'] == '2') echo"selected"; ?> >dokumen</option>
			</select>
			<button type="submit" value="Submit">Submit</button>
		</form>
		
		
		
		<table id="items">
		 <tr>
		 	<th colspan="4"><?php if(isset($_GET['komoditi'])) echo $nama_dokumen; ?></th>
		 </tr>
		 <tr>
		 	<th>BULAN</th>
		 	<th>JUMLAH AWB</th>
		 	<th>JUMLAH KOLI/PUCUK</th>
		 	<th>JUMLAH KILOGRAM</th>
		 </tr>  		

		 <?php 
			if(isset($_GET['komoditi'])){
				if($_GET['komoditi'] == 1){
					$query = $query_paket;
				}else{
					$query = $query_dokumen;
				}
			 	while ($row = mysqli_fetch_assoc($query)) {
			 		echo '
			 			<tr>
						 	<td>'.$row['bulan'].'</td>
						 	<td>'.$row['jumlah_awb'].'</td>
						 	<td>'.$row['total_collie'].'</td>
						 	<td>'.$row['total_berat'].'</td>
						 </tr>
			 		';
			 	}
			}
			
		 ?>  		
		</table>
		
		
	
	</div>
	
</body>

</html>