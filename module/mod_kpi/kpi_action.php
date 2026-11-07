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
	include "../../config/db_connect.php";
	
	$module	= !empty($_GET['module']) ? $_GET['module'] : '';
	$act	= !empty($_GET['act']) ? $_GET['act'] : '';
	
	$aksi="module/mod_kpi/kpi_action.php";
	
	// List User Cesco
	if($module=='kpi' and $act=='readkaryawan'){
		$table = 
<<<EOT
	(
		SELECT
			a.namalengkap, 			
			a.email,
			b.nama_cabang,
			a.id_user,
			c.jabatan
		FROM user a
		LEFT JOIN cabang b ON b.id_cabang=a.id_cabang
		left join jabatan c on a.id_jabatan = c.id
		where a.id_cabang = 39 and blokir = 'N'
		and jabatan != ''
	) temp
EOT;
		$primaryKey = 'id_user';
		$columns = array(
			array('db'=>'namalengkap','dt'=>0),
			
			
			array('db'=>'jabatan','dt'=>1),
			array('db'=>'email','dt'=>2),
			
			
			array(
				  'db'=>'id_user',
				  'dt'=>3,
				  'formatter'=>function( $d, $row ) {
					$aksi="module/mod_users/users_action.php";
					$key = "asdf$1011";
					return '<td><center><button class="btn btn-xs btn-warning" onclick="window.location.href = \'?module=kpi-karyawan&act=detail&id='.base64_encrypt($d,$key).'\';">detail</button> 

					</center></td>';
				  }
			)

		);
		/*$sql_details = array(
			'user' => 'cesco_user',
			'pass' => 'srnGRkhl3c',
			'db'   => 'cesco_logistics',
			'host' => '127.0.0.1'
		);*/
		require( '../../config/ssp.class.php' );
		echo json_encode(
			SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns )
		);
	
	// update user cesco
	}

	if($module=='kpi' and $act=='readdistribution'){
		$table = 
<<<EOT
	(
		SELECT
			a.namalengkap, 			
			a.username,
			b.nama_cabang,
			a.id_user,
			c.jabatan
		FROM user a
		LEFT JOIN cabang b ON b.id_cabang=a.id_cabang
		left join jabatan c on a.id_jabatan = c.id
		where a.id_cabang in (39,11,16) and blokir = 'N'
		and a.id_jabatan in (8,11)
	) temp
EOT;
		$primaryKey = 'id_user';
		$columns = array(
			array(
				  'db'        => 'namalengkap',
				  'dt'        => 0,
				  'formatter' => function( $d, $row ) {
					return ucfirst(strtolower($d));
				}
			),
			
			
			array(
				  'db'        => 'jabatan',
				  'dt'        => 1,
				  'formatter' => function( $d, $row ) {
					return ucfirst(strtolower($d));
				}
			),
			array(
				  'db'        => 'username',
				  'dt'        => 2,
				  'formatter' => function( $d, $row ) {
					return strtolower($d);
				}
			),
			
			
			array(
				  'db'=>'id_user',
				  'dt'=>3,
				  'formatter'=>function( $d, $row ) {
					$aksi="module/mod_users/users_action.php";
					$key = "asdf$1011";
					return '<td><center><button class="btn btn-xs btn-warning" onclick="window.location.href = \'?module=kpi-distribution-team&act=detail&id='.base64_encrypt($d,$key).'\';">Detail</button> 

					</center></td>';
				  }
			)

		);
		/*$sql_details = array(
			'user' => 'cesco_user',
			'pass' => 'srnGRkhl3c',
			'db'   => 'cesco_logistics',
			'host' => '127.0.0.1'
		);*/
		require( '../../config/ssp.class.php' );
		echo json_encode(
			SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns )
		);
	
	// update user cesco
	}

	if($module=='kpi' and $act=='readcabang'){
		
		$table = 
<<<EOT
	(
		SELECT
			*
		FROM cabang
		
		where status = 'Y' and cabang_cesco != 'Y'
		
	) temp
EOT;

		$primaryKey = 'id_cabang';

		$columns = array(
			array( 'db' => 'kode_cabang', 'dt' => 0 ),
			array( 'db' => 'nama_cabang',  'dt' => 1 ),
			array( 'db' => 'alamat2', 'dt'=> 2 ),
			
			
			array(
				  'db'        => 'id_cabang',
				  'dt'        => 3,
				  'formatter' => function( $d, $row ) {
					$aksi="module/mod_branch/branch_action.php";
					$key = "asdf$1011";
					return '<button class="btn btn-xs btn-warning" onclick="window.location.href = \'?module=kpi-cabang&act=detail&id='.base64_encrypt($d,$key).'\';">detail</button> 

					';
				}
			)

		);

		
		require( '../../config/ssp.class.php' );

		echo json_encode(
			SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns )
		);

	// Cek Kode
	}

	if($module=='kpi' and $act=='readcustomer'){
		
		$table = 
<<<EOT
	(
		SELECT
			*
		FROM kustomer
		
		where status != 'N'
		
	) temp
EOT;

		$primaryKey = 'id_kustomer';

		$columns = array(
			
			array(
				  'db'        => 'nama_kustomer',
				  'dt'        => 0,
				  'formatter' => function( $d, $row ) {
					return strtoupper($d);
				}
			),
			array(
				  'db'        => 'alamat',
				  'dt'        => 1,
				  'formatter' => function( $d, $row ) {
					return ucfirst(strtolower($d));
				}
			),
			
			
			array(
				  'db'        => 'id_kustomer',
				  'dt'        => 2,
				  'formatter' => function( $d, $row ) {
					
					$key = "asdf$1011";
					return '<td><center><button class="btn btn-xs btn-warning" onclick="window.location.href = \'?module=kpi-customer&act=detail&id='.base64_encrypt($d,$key).'\';">Detail</button> 

					</center></td>';
				}
			)

		);

		
		require( '../../config/ssp.class.php' );

		echo json_encode(
			SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns )
		);

	// Cek Kode
	}

	if($module=='kpi' and $act=='readvendor'){
		
		$table = 
<<<EOT
	(
		SELECT
			*
		FROM manifest_vendor
		
		where active != 'N'
		
	) temp
EOT;

		$primaryKey = 'id';

		$columns = array(
			
			array(
				  'db'        => 'name',
				  'dt'        => 0,
				  'formatter' => function( $d, $row ) {
					return strtoupper($d);
				}
			),
			
			array(
				  'db'        => 'id',
				  'dt'        => 1,
				  'formatter' => function( $d, $row ) {
					
					$key = "asdf$1011";
					return '<td><center><button class="btn btn-xs btn-warning" onclick="window.location.href = \'?module=kpi-vendor&act=detail&id='.base64_encrypt($d,$key).'\';">Detail</button> 

					</center></td>';
				}
			)

		);

		
		require( '../../config/ssp.class.php' );

		echo json_encode(
			SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns )
		);

	// Cek Kode
	}

	elseif($module=='users' AND $act=='update1'){
		$id_user = mysqli_real_escape_string($con, $_POST['id']);
		$namalengkap = mysqli_real_escape_string($con, $_POST['namalengkap']);
		$email = mysqli_real_escape_string($con, $_POST['email']);
		$id_cabang = mysqli_real_escape_string($con, $_POST['id_cabang']);
		$level = mysqli_real_escape_string($con, $_POST['level']);
		//$blokir = mysqli_real_escape_string($con, $_POST['blokir']);
		$id_kustomer = mysqli_real_escape_string($con, $_POST['id_kustomer']);
		$jabatan = mysqli_real_escape_string($con, $_POST['jabatan']);
		if(!empty($_POST['area'])){
			$pickup_area = $_POST['area'];
			$area=implode(',',$pickup_area);
		}
		$delivery_customer = mysqli_real_escape_string($con, $_POST['delivery_customer']);
		if(!empty($_POST['delivery_area'])){
			$delivery_area = $_POST['delivery_area'];
			$delivery=implode(',',$delivery_area);
		}
		
		if(!empty($blokir)){
			mysqli_query($con, "update user set namalengkap='$namalengkap',
												email='$email',
												id_cabang='$id_cabang',
												level='$level',
												id_jabatan='$jabatan',
												blokir='$blokir',
												id_kustomer='$id_kustomer',
												area='$area',
												delivery_customer='$delivery_customer',
												delivery_area='$delivery'
								where id_user='$id_user'");
		}else{
			mysqli_query($con, "update user set namalengkap='$namalengkap',
												email='$email',
												id_cabang='$id_cabang',
												level='$level',
												id_jabatan='$jabatan',
												id_kustomer='$id_kustomer',
												area='$area',
												delivery_customer='$delivery_customer',
												delivery_area='$delivery'
								where id_user='$id_user'");
		}
		
		
		header('location:../../main.php?module='.$module);
		
	}

	elseif($module=='kpi' AND $act=='new')
	{
		$id_jabatan			= $mysqli->real_escape_string($_POST['id_jabatan']);
		$kpi			= $mysqli->real_escape_string($_POST['kpi']);
		$bobot			= $mysqli->real_escape_string($_POST['bobot']);
		$target			= $mysqli->real_escape_string($_POST['target']);
		$detail_target	= $mysqli->real_escape_string($_POST['detail_target']);
		$type			= $mysqli->real_escape_string($_POST['type']);
		
		$result			= $mysqli->query("INSERT INTO kpi ( id_jabatan, kpi, bobot,target,detail_target,type_penilaian ) VALUES ('$id_jabatan', '$kpi', '$bobot', '$target', '$detail_target', '$type')");
		
		if($result)
		{
			$_SESSION['pesan'] = 'success';
		}
		else
		{
			$_SESSION['pesan'] = 'failed';
		}
			$key = "asdf$1011";
			$stringawal = $id_jabatan;
			$id = base64_encrypt($stringawal,$key);
		
		header('location:../../main.php?module='.$module.'&act=detail&id='.$id);
	
	
	}
	// update
	elseif($module=='kpi' AND $act=='update')
	{
		$back		= $_GET['back'];

		$key		= "asdf$1011";
		$encrypt	= $mysqli->real_escape_string($_POST['id_kpi']);
		$id_kpi 		= base64_decrypt($encrypt,$key);
		
		$id_jabatan		= $mysqli->real_escape_string($_POST['id_jabatan']);
		$kpi			= $mysqli->real_escape_string($_POST['kpi']);
		$bobot			= $mysqli->real_escape_string($_POST['bobot']);
		$target			= $mysqli->real_escape_string($_POST['target']);
		$detail_target	= $mysqli->real_escape_string($_POST['detail_target']);
		$type			= $mysqli->real_escape_string($_POST['type']);


		$result			= $mysqli->query("UPDATE kpi SET kpi='$kpi',
															bobot='$bobot',
															target='$target' ,
															detail_target='$detail_target' ,
															type_penilaian='$type' 
											WHERE id='$id_kpi'");
		
		if($result)
		{
			$_SESSION['pesan'] = 'success';
		}
		else
		{
			$_SESSION['pesan'] = 'failed';
		}
		
		header('location:../../main.php?module='.$module.'&act=detail&id='.$back);
		
	}

	elseif ($module=='kpi' and $act=='delete')
	{
		$back		= $_GET['back'];
		$key		= "asdf$1011";
		$encrypt	= "$_GET[id]";
		$id 		= base64_decrypt($encrypt,$key);
		$result		= $mysqli->query("DELETE FROM kpi WHERE id='$id'");
		
		if($result)
		{
			$_SESSION['pesan'] = 'delete';
		}
		else
		{
			$_SESSION['pesan'] = 'failed';
		}
		
		header('location:../../main.php?module='.$module.'&act=detail&id='.$back);
	}

	elseif($module=='kpi' AND $act=='input-score')
	{
		$id_user			= $mysqli->real_escape_string($_POST['id_user']);
		$periode			= $mysqli->real_escape_string($_POST['periode']);
		$id_kpi				= $mysqli->real_escape_string($_POST['id_kpi']);
		$score				= $mysqli->real_escape_string($_POST['score']);
		$detail_score		= $mysqli->real_escape_string($_POST['detail_score']);
		//$type				= $mysqli->real_escape_string($_POST['type']);
		
		$result			= $mysqli->query("INSERT INTO kpi_score ( id_user, periode, id_kpi,score,detail_score ) VALUES ('$id_user', '$periode', '$id_kpi', '$score', '$detail_score')");
		
		
		 header('Location: ' . $_SERVER['HTTP_REFERER']);
	
	
	}

	elseif($module=='kpi' AND $act=='update-score')
	{
		
		$id_score			= $mysqli->real_escape_string($_POST['id_score']);
		$score				= $mysqli->real_escape_string($_POST['score']);
		$detail_score		= $mysqli->real_escape_string($_POST['detail_score']);


		$result			= $mysqli->query("UPDATE kpi_score SET 
															score='$score',
															detail_score='$detail_score' 															
											WHERE id=$id_score");
		
		
		
		 header('Location: ' . $_SERVER['HTTP_REFERER']);
		
	}

}
?>