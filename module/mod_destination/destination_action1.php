<?php
if(!isset($_SESSION)){
    session_start();
}

if (empty($_SESSION['username']) AND empty($_SESSION['password'])){
	header('location:../../index.php');
}
else{
	include "../../config/connection.php";
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
			3 => 'induk',
			4 => 'hub'
		);

		$sql = "select id_kota, kode_kota, nama_kota, x_namakota(induk) as induk, x_namakota(hub) as hub ";
		$sql.=" from kota";
		$query=mysqli_query($con, $sql);
		$totalData = mysqli_num_rows($query);
		$totalFiltered = $totalData;

		if(!empty($requestData['search']['value'])){
			$sql = "select id_kota, kode_kota, nama_kota, x_namakota(induk) as induk, x_namakota(hub) as hub ";
			$sql.=" from kota";
			$sql.=" where kode_kota LIKE '".$requestData['search']['value']."%' ";
			$sql.=" or nama_kota LIKE '".$requestData['search']['value']."%' ";
			$query=mysqli_query($con, $sql);
			$totalFiltered = mysqli_num_rows($query);

			$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."   LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
			$query=mysqli_query($con, $sql);
	
		}else{	

			$sql = "select id_kota, kode_kota, nama_kota, x_namakota(induk) as induk, x_namakota(hub) as hub ";
			$sql.=" from kota";
			$sql.=" order by ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']." LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
			$query=mysqli_query($con, $sql);
	
		}

		$data = array();
		while($row=mysqli_fetch_array($query)) {
			$key = "asdf$1011";
			$stringawal = $row["id_kota"];
			$id = base64_encrypt($stringawal,$key);
			$nestedData=array();
			$nestedData[] = '<td><center>'.$row["kode_kota"].'</center></td>';
			$nestedData[] = $row["nama_kota"];
			$nestedData[] = $row["induk"];
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
	
	// create
	}elseif($module=='destination' AND $act=='create'){
		$kode_kota = mysqli_real_escape_string($con, $_POST['kode_kota']);
		$nama_kota = mysqli_real_escape_string($con, $_POST['nama_kota']);
		$induk = mysqli_real_escape_string($con, $_POST['induk']);
		$hub = mysqli_real_escape_string($con, $_POST['hub']);
		$intercity = mysqli_real_escape_string($con, $_POST['intercity']);
		
		$hasil = mysqli_query($con, "select max(id_kota) AS last from kota");
		$data = mysqli_fetch_assoc($hasil);
		$lastNoUrut = $data['last'];
		$id_kota = $lastNoUrut + 1;
		
		mysqli_query($con, "insert into kota (kode_kota,
													nama_kota,
													induk,
													hub,
													intercity,
													created_date,
													created_by,
													id_kota)
											values ('$kode_kota',
													'$nama_kota',
													'$induk',
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
		$induk = mysqli_real_escape_string($con, $_POST['induk']);
		$hub = mysqli_real_escape_string($con, $_POST['hub']);
		$intercity = mysqli_real_escape_string($con, $_POST['intercity']);
		
		mysqli_query($con, "update kota set kode_kota='$kode_kota',
											nama_kota='$nama_kota',
											induk='$induk',
											hub='$hub',
											intercity='$intercity',
											updated_last='$tgl_jam',
											updated_by='$_SESSION[id_user]'
							where id_kota='$_POST[id]'");
		
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