<?php
if(!isset($_SESSION)){
    session_start();
}

if (empty($_SESSION['username']) AND empty($_SESSION['password'])){
	header('location:../../index.php');
}
else{
	include "../../config/connection.php";
	include "../../config/db_connect.php";
	include "../../config/library.php";
	include "../../config/encrypt.php";
	
	$module	= !empty($_GET['module']) ? $_GET['module'] : '';
	$act	= !empty($_GET['act']) ? $_GET['act'] : '';
	
	$aksi="module/mod_country/country_action.php";
	
	// List
	if($module=='country' AND $act=='read'){
		
		$requestData= $_REQUEST;
		$column = !empty($requestData['order'][0]['column']) ? $requestData['order'][0]['column'] : '';
		
		$columns = array( 
			0 => 'id_negara',
			1 => 'nama_negara',
			2 => 'kode_negara',
			3 => 'zona'
		);

		$sql = "select id_negara, nama_negara, kode_negara, zona ";
		$sql.=" from negara";
		$query=$mysqli->query($sql);
		$totalData = $query->num_rows;
		$totalFiltered = $totalData;

		if(!empty($requestData['search']['value'])){
			$sql = "select id_negara, nama_negara, kode_negara, zona ";
			$sql.=" from negara";
			$sql.=" where kode_negara LIKE '".$requestData['search']['value']."%' ";
			$sql.=" or nama_negara LIKE '".$requestData['search']['value']."%' ";
			$query=$mysqli->query($sql);
			$totalData = $query->num_rows;
			$totalFiltered = $totalData;

			$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."   LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
			$query=$mysqli->query($sql);
	
		}else{	

			$sql = "select id_negara, nama_negara, kode_negara, zona ";
			$sql.=" from negara";
			$sql.=" order by ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']." LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
			$query=$mysqli->query($sql);
	
		}

		$data = array();
		while($row=$query->fetch_array()) {
			$key = "asdf$1011";
			$stringawal = $row["id_negara"];
			$id = base64_encrypt($stringawal,$key);
			$nestedData=array();
			$nestedData[] = $row["nama_negara"];
			$nestedData[] = '<td><center>'.$row["kode_negara"].'</center></td>';
			$nestedData[] = '<td><center>'.$row["zona"].'</center></td>';
			
			$nestedData[] = '<td><center><button class="btn btn-xs btn-warning" onclick="window.location.href = \'?module=country&act=update&id='.$id.'\';"><i class="fa fa-pencil"></i></button> <button data-href="'.$aksi.'?module=country&act=delete&id='.$id.'" class="btn btn-xs btn-danger" type="button" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash"></i></button></center></td>';
			$data[] = $nestedData;
    
		}

		$json_data = array(
			"draw"            => intval( $requestData['draw'] ), 
			"recordsTotal"    => intval( $totalData ), 
			"recordsFiltered" => intval( $totalFiltered ), 
			"data"            => $data
			);

		echo json_encode($json_data);
		
		$query->free();
	
	// create
	}
	elseif($module=='country' AND $act=='create')
	{
		$kode_negara	= $mysqli->real_escape_string($_POST['kode_negara']);
		$nama_negara	= $mysqli->real_escape_string($_POST['nama_negara']);
		$zona			= $mysqli->real_escape_string($_POST['zona']);
		
		$result			= $mysqli->query("INSERT INTO negara ( kode_negara, nama_negara, zona ) VALUES ('$kode_negara', '$nama_negara', '$zona')");
		
		if($result)
		{
			$_SESSION['pesan'] = 'success';
		}
		else
		{
			$_SESSION['pesan'] = 'failed';
		}
		
		header('location:../../main.php?module='.$module);
	
	// update
	}
	elseif($module=='country' AND $act=='update')
	{
		$id_negara 		= $mysqli->real_escape_string($_POST['id_negara']);
		$kode_negara	= $mysqli->real_escape_string($_POST['kode_negara']);
		$nama_negara	= $mysqli->real_escape_string($_POST['nama_negara']);
		$zona			= $mysqli->real_escape_string($_POST['zona']);
		
		$result			= $mysqli->query("UPDATE negara SET kode_negara='$kode_negara',
															nama_negara='$nama_negara',
															zona='$zona' 
											WHERE id_negara='$id_negara'");
		
		if($result)
		{
			$_SESSION['pesan'] = 'success';
		}
		else
		{
			$_SESSION['pesan'] = 'failed';
		}
		
		header('location:../../main.php?module='.$module);
		
	}
	elseif ($module=='country' and $act=='delete')
	{
		$key		= "asdf$1011";
		$encrypt	= "$_GET[id]";
		$id 		= base64_decrypt($encrypt,$key);
		$result		= $mysqli->query("DELETE FROM negara WHERE id_negara='$id'");
		
		if($result)
		{
			$_SESSION['pesan'] = 'delete';
		}
		else
		{
			$_SESSION['pesan'] = 'failed';
		}
		
		header('location:../../main.php?module='.$module);
	}
}
?>