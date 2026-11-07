<!DOCTYPE html>
<html>
<head>
	<title>Export Data Ke Excel Dengan PHP - www.malasngoding.com</title>
</head>
<body>
	<style type="text/css">
	body{
		font-family: sans-serif;
	}
	table{
		margin: 20px auto;
		border-collapse: collapse;
	}
	table th,
	table th{
		border: 1px solid #3c3c3c;
		padding: 3px 8px;
 
	}
	a{
		background: blue;
		color: #fff;
		padding: 8px 10px;
		text-decoration: none;
		border-radius: 2px;
	}
	</style>
 
	<?php
	header("Content-type: application/vnd-ms-excel");
	header("Content-Disposition: attachment; filename=Data Pegawai.xls");
	?>
 
	<center>
		<h1>Export Data Ke Excel Dengan PHP <br/> www.malasngoding.com</h1>
	</center>
 
	<table border="1">
		<tr>
			<th>No</th>
			<th>Nama Pegawai</th>
			<th>Alamat</th>
			<th>No.Telp</th>
		</tr>
		<tr>
			<th>1</th>
			<th>Sulaiman</th>
			<th>Jakarta</th>
			<th>0829121223</th>
		</tr>
		<tr>
			<th>2</th>
			<th>Diki Alfarabi Hadi</th>
			<th>Jakarta</th>
			<th>08291212211</th>
		</tr>
		<tr>
			<th>3</th>
			<th>Zakaria</th>
			<th>Medan</th>
			<th>0829121223</th>
		</tr>
		<tr>
			<th>4</th>
			<th>Alvinur</th>
			<th>Jakarta</th>
			<th>02133324344</th>
		</tr>
		<tr>
			<th>5</th>
			<th>Muhammad Rizani</th>
			<th>Jakarta</th>
			<th>08231111223</th>
		</tr>
		<tr>
			<th>6</th>
			<th>Rizaldi Waloni</th>
			<th>Jakarta</th>
			<th>027373733</th>
		</tr>
		<tr>
			<th>7</th>
			<th>Ferdian</th>
			<th>Jakarta</th>
			<th>0829121223</th>
		</tr>
		<tr>
			<th>8</th>
			<th>Fatimah</th>
			<th>Jakarta</th>
			<th>23432423423</th>
		</tr>
		<tr>
			<th>9</th>
			<th>Aminah</th>
			<th>Jakarta</th>
			<th>0829234233</th>
		</tr>
		<tr>
			<th>10</th>
			<th>Jafarudin</th>
			<th>Jakarta</th>
			<th>0829239323</th>
		</tr>
	</table>
</body>
</html>