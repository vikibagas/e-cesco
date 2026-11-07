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
	
	$aksi="module/mod_destination/destination_action.php";
	
	// List
	if($module=='destination' AND $act=='read'){
		
		$requestData= $_REQUEST;
		$column = !empty($requestData['order'][0]['column']) ? $requestData['order'][0]['column'] : '';
		
		$columns = array( 
			0 => 'id_kota',
			1 => 'kode_kota',
			2 => 'nama_kota',
			3 => 'hub'
		);

		$sql = "select id as id_kota, x_kodekotabaru(id) as kode_kota, name as nama_kota, x_namakotanew(x_hubnew(id)) as hub ";
		$sql.=" from regencies";
		$query=$mysqli->query($sql);
		$totalData = $query->num_rows;
		$totalFiltered = $totalData;

		if(!empty($requestData['search']['value'])){
			$sql = "select id as id_kota,x_kodekotabaru(id) as kode_kota, name as nama_kota, x_namakotanew(x_hubnew(id)) as hub ";
			$sql.=" from regencies";
			$sql.=" where kode_kota LIKE '%".$requestData['search']['value']."%' ";
			$sql.=" or name LIKE '%".$requestData['search']['value']."%' ";
			$query=$mysqli->query($sql);
			$totalData = $query->num_rows;
			$totalFiltered = $totalData;

			$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."   LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
			$query=$mysqli->query($sql);
	
		}else{	

			$sql = "select id as id_kota, x_kodekotabaru(id) as kode_kota, name as nama_kota, x_namakotanew(x_hubnew(id)) as hub";
			$sql.=" from regencies";
			$sql.=" order by ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']." LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
			$query=$mysqli->query($sql);
	
		}

		$data = array();
		while($row=$query->fetch_array()) {
			$key = "asdf$1011";
			$stringawal = $row["id_kota"];
			$id = base64_encrypt($stringawal,$key);
			$nestedData=array();
			$nestedData[] = '<td><center>'.$row["kode_kota"].'</center></td>';
			$nestedData[] = $row["nama_kota"];
			$nestedData[] = $row["hub"];
			
			$nestedData[] = '<td><center><button class="btn btn-xs btn-warning" onclick="window.location.href = \'?module=destination&act=update&id='.$id.'\';"><i class="fa fa-pencil"></i></button> <button data-href="'.$aksi.'?module=destination&act=delete&id='.$id.'" class="btn btn-xs btn-danger" type="button" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash"></i></button></center></td>';
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
	}elseif($module=='destination' AND $act=='create'){
		$kode_kota = mysqli_real_escape_string($con, $_POST['kode_kota']);
		$nama_kota = mysqli_real_escape_string($con, $_POST['nama_kota']);
		$hub = mysqli_real_escape_string($con, $_POST['hub']);
		$intercity = mysqli_real_escape_string($con, $_POST['intercity']);
		
		$hasil = mysqli_query($con, "select max(id_kota) AS last from kota");
		$data = mysqli_fetch_assoc($hasil);
		$lastNoUrut = $data['last'];
		$id_kota = $lastNoUrut + 1;
		
		mysqli_query($con, "insert into kota (kode_kota,
													nama_kota,
													hub,
													intercity,
													created_date,
													created_by,
													id_kota)
											values ('$kode_kota',
													'$nama_kota',
													'$hub',
													'$intercity',
													'$tgl_jam',
													'$_SESSION[id_user]',
													'$id_kota')");
		
		header('location:../../main.php?module='.$module);
	
	// update
	}elseif($module=='destination' AND $act=='update'){
		$id_kota = mysqli_real_escape_string($con, $_POST['id']);
		$kode_kota = mysqli_real_escape_string($con, $_POST['kode_kota']);
		$nama_kota = mysqli_real_escape_string($con, $_POST['nama_kota']);
		$hub = mysqli_real_escape_string($con, $_POST['hub']);
		$intercity = mysqli_real_escape_string($con, $_POST['intercity']);
		
		mysqli_query($con, "update regencies set kode_kota='$kode_kota',
											name='$nama_kota',
											hub='$hub',
											intercity='$intercity'																				
							where id='$_POST[id]'");
		
		header('location:../../main.php?module='.$module);
		
	}elseif($module=='destination' and $act=='delete'){
		$key = "asdf$1011";
		$encrypt = "$_GET[id]";
		$id = base64_decrypt($encrypt,$key);
		mysqli_query($con, "delete from kota where id_kota='$id'");
		header('location:../../main.php?module='.$module);
	}

}
?>