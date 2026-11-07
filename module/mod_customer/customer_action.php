<?php
if(!isset($_SESSION)){
    session_start();
}

if (empty($_SESSION['username']) AND empty($_SESSION['password'])){
	header('location:../../application/index.php');
}
else{
	include "../../config/connection.php";
	include "../../config/library.php";
	include "../../config/encrypt.php";
	
	$module	= !empty($_GET['module']) ? $_GET['module'] : '';
	$act	= !empty($_GET['act']) ? $_GET['act'] : '';
	
	$aksi="module/mod_customer/customer_action.php";
	
	$key = 'asdf$1011';
	$encrypt = $_SESSION['id_user'];
	$id = base64_decrypt($encrypt,$key);
	$useraktif = mysqli_query($con, 'select * from user where id_user="'.$id.'"');
	$u = mysqli_fetch_assoc($useraktif);
	
	// List
	if($module=='customer' AND $act=='read'){
		
		$requestData= $_REQUEST;
		$column = !empty($requestData['order'][0]['column']) ? $requestData['order'][0]['column'] : '';
		
		$columns = array( 
			0 => 'id_kustomer',
			1 => 'kode_kustomer',
			2 => 'nama_kustomer',
			3 => 'alamat'
		);

		$sql = "select id_kustomer, kode_kustomer, nama_kustomer, alamat ";
		$sql.=" from kustomer";
		$query=mysqli_query($con, $sql);
		$totalData = mysqli_num_rows($query);
		$totalFiltered = $totalData;

		if(!empty($requestData['search']['value'])){
			$sql = "select id_kustomer, kode_kustomer, nama_kustomer, alamat ";
			$sql.=" from kustomer";
			$sql.=" where kode_kustomer LIKE '".$requestData['search']['value']."%' ";
			$sql.=" or nama_kustomer LIKE '".$requestData['search']['value']."%' ";
			$sql.=" or alamat LIKE '".$requestData['search']['value']."%' ";
			$query=mysqli_query($con, $sql);
			$totalFiltered = mysqli_num_rows($query);

			$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."   LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
			$query=mysqli_query($con, $sql);
	
		}else{	

			$sql = "select id_kustomer, kode_kustomer, nama_kustomer, alamat ";
			$sql.=" from kustomer";
			$sql.=" order by ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']." LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
			$query=mysqli_query($con, $sql);
	
		}

		$data = array();
		while($row=mysqli_fetch_array($query)) {
			$key = "asdf$1011";
			$stringawal = $row["id_kustomer"];
			$id = base64_encrypt($stringawal,$key);
			$nestedData=array();
			$nestedData[] = '<td><center>'.$row["kode_kustomer"].'</center></td>';
			$nestedData[] = $row["nama_kustomer"];
			$nestedData[] = $row["alamat"];
			
			$nestedData[] = '<td><center><button class="btn btn-xs btn-warning" onclick="window.location.href = \'?module=customer&act=update&id='.$id.'\';"><i class="fa fa-pencil"></i></button> <button data-href="'.$aksi.'?module=customer&act=delete&id='.$id.'" class="btn btn-xs btn-danger" type="button" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash"></i></button></center></td>';
			$data[] = $nestedData;
    
		}

		$json_data = array(
			"draw"            => intval( $requestData['draw'] ), 
			"recordsTotal"    => intval( $totalData ), 
			"recordsFiltered" => intval( $totalFiltered ), 
			"data"            => $data
			);

		echo json_encode($json_data);
	
	// Cek Kode
	}elseif($module=='customer' and $act=='checkcode'){
		if($_POST['kode_kustomer']){
			$kode = mysqli_real_escape_string($con,$_POST['kode_kustomer']);
			$query = mysqli_query($con, "SELECT kode_kustomer FROM kustomer WHERE kode_kustomer='$kode'");
			$result = mysqli_num_rows($query);
			if( $result > 0 ){
				echo 'false';
			} else {
				echo 'true';
			}
		}
	
	// create
	}elseif($module=='customer' AND $act=='create'){
		$id_cabang = mysqli_real_escape_string($con, $_POST['id_cabang']);
		$kode_kustomer = mysqli_real_escape_string($con, $_POST['kode_kustomer']);
		$nama_kustomer = mysqli_real_escape_string($con, $_POST['nama_kustomer']);
		$alamat = mysqli_real_escape_string($con, $_POST['alamat']);
		$kota = mysqli_real_escape_string($con, $_POST['kota']);
		$kecamatan = mysqli_real_escape_string($con, $_POST['kecamatan']);
		$telp = mysqli_real_escape_string($con, $_POST['telp']);
		
		mysqli_query($con, "insert into kustomer (id_cabang,
													kode_kustomer,
													nama_kustomer,
													alamat,
													kota,
													kecamatan,
													telp,
													created_date,
													created_by,
													status)
											values ('$id_cabang',
													'$kode_kustomer',
													'$nama_kustomer',
													'$alamat',
													'$kota',
													'$kecamatan',
													'$telp',
													'$tgl_jam',
													'$u[id_user]',
													'Y')");
		
		header('location:../../main.php?module='.$module);
		
	}elseif($module=='customer' AND $act=='update'){
		$id_kustomer = mysqli_real_escape_string($con, $_POST['id']);
		$id_cabang = mysqli_real_escape_string($con, $_POST['id_cabang']);
		//$kode_kustomer = mysqli_real_escape_string($con, $_POST['kode_kustomerx']);
		$nama_kustomer = mysqli_real_escape_string($con, $_POST['nama_kustomer']);
		$alamat = mysqli_real_escape_string($con, $_POST['alamat']);
		$kota = mysqli_real_escape_string($con, $_POST['kota']);
		$kecamatan = mysqli_real_escape_string($con, $_POST['kecamatan']);
		$telp = mysqli_real_escape_string($con, $_POST['telp']);
		$penagihan = isset($_POST['penagihan']) ? $_POST['penagihan'] : array();
		
		mysqli_begin_transaction($con);

try {

    // 1️⃣ Update data kustomer
    mysqli_query($con, "
        UPDATE kustomer SET 
            id_cabang='$id_cabang',
            nama_kustomer='$nama_kustomer',
            alamat='$alamat',
            kota='$kota',
            kecamatan='$kecamatan',
            telp='$telp',
            updated_last='$tgl_jam',
            updated_by='$u[id_user]'
        WHERE id_kustomer='$id_kustomer'
    ");

    if (mysqli_error($con)) {
        throw new Exception(mysqli_error($con));
    }

    // 2️⃣ Hapus relasi lama
    mysqli_query($con, "
        DELETE FROM kustomer_tagihan 
        WHERE id_kustomer='$id_kustomer'
    ");

    if (mysqli_error($con)) {
        throw new Exception(mysqli_error($con));
    }

    // 3️⃣ Insert relasi baru
    if (!empty($penagihan)) {

        foreach ($penagihan as $tagih) {

            $tagih = mysqli_real_escape_string($con, $tagih);

            // cegah tagih ke diri sendiri
            if ($tagih != $id_kustomer) {

                mysqli_query($con, "
                    INSERT INTO kustomer_tagihan 
                    (id_kustomer, id_kustomer_tagih)
                    VALUES 
                    ('$id_kustomer', '$tagih')
                ");

                if (mysqli_error($con)) {
                    throw new Exception(mysqli_error($con));
                }
            }
        }
    }

    mysqli_commit($con);

} catch (Exception $e) {

    mysqli_rollback($con);
    die("Terjadi kesalahan: " . $e->getMessage());

}

header('location:../../main.php?module='.$module);
exit;
		
	}elseif($module=='customer' and $act=='delete'){
		$key = "asdf$1011";
		$encrypt = "$_GET[id]";
		$id = base64_decrypt($encrypt,$key);
		mysqli_query($con, "delete from kustomer where id_kustomer='$id'");
		header('location:../../main.php?module='.$module);
	}
}
?>