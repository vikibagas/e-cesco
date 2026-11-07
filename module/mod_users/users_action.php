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
	
	$aksi="module/mod_users/users_action.php";
	
	// List User Cesco
	if($module=='users' and $act=='readuser'){
		$table = 
<<<EOT
	(
		SELECT
			a.namalengkap, 
			a.username, 
			a.level,
			a.blokir,
			a.email,
			b.nama_cabang,
			a.id_user,
			c.jabatan
		FROM user a
		LEFT JOIN cabang b ON b.id_cabang=a.id_cabang
		left join jabatan c on a.id_jabatan = c.id
	) temp
EOT;
		$primaryKey = 'id_user';
		$columns = array(
			array('db'=>'namalengkap','dt'=>0),
			
			array('db'=>'username','dt'=>1),
			array('db'=>'level','dt'=>2),
			array('db'=>'jabatan','dt'=>3),
			array('db'=>'blokir','dt'=>4,
				'formatter'=>function($d,$row){
					if($d=='Y'){
						$status = '<button class="btn btn-block btn-danger btn-xs">Blocked</button>';
					}else{
						$status = '<button class="btn btn-block btn-success btn-xs">Unblocked</button>';
					}
					return '<td><center>'.$status.'</center></td>';
				}
			),
			array('db'=>'email', 
					'dt'=>5,
					'formatter'=>function($d,$row){
						return'<td><a href="mailto:'.$d.'">'.$d.'</a></td>';
					}
			),
			array('db'=>'nama_cabang','dt'=>6),
			array(
				  'db'=>'id_user',
				  'dt'=>7,
				  'formatter'=>function( $d, $row ) {
					$aksi="module/mod_users/users_action.php";
					$key = "asdf$1011";
					return '<td><center><button class="btn btn-xs btn-warning" onclick="window.location.href = \'?module=users&act=update1&id='.base64_encrypt($d,$key).'\';"><i class="fa fa-pencil"></i></button> <button data-href="'.$aksi.'?module=users&act=blockeduser&id='.base64_encrypt($d,$key).'" class="btn btn-xs btn-danger" type="button" data-toggle="modal" data-target="#confirm-blocked"><i class="fa fa-close"></i></button></center></td>';
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
	}elseif($module=='users' AND $act=='update1'){
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
		
	}elseif($module=='users' and $act=='blockeduser'){
		$key = "asdf$1011";
		$encrypt = "$_GET[id]";
		$id = base64_decrypt($encrypt,$key);
		mysqli_query($con, "update user set blokir='Y' where id_user='$id'");
		header('location:../../main.php?module='.$module);
	
	// list user client
	}elseif($module=='users' and $act=='readuserclient'){
		$table = 
<<<EOT
	(
		SELECT
			a.fullname, 
			a.username, 
			b.nama_kustomer,
			a.blocked,
			a.email,
			a.created_date,
			a.id_user
		FROM user_client a
		LEFT JOIN kustomer b ON b.id_kustomer=a.id_kustomer
	) temp
EOT;
		$primaryKey = 'id_user';
		$columns = array(
			array('db'=>'fullname',
					'dt'=>0,
					'formatter'=>function($d,$row){
						return'<td>'.strtoupper($d).'</td>';
					}
			),
			array('db'=>'username','dt'=>1),
			array('db'=>'nama_kustomer','dt'=>2),
			array('db'=>'blocked','dt'=>3,
				'formatter'=>function($d,$row){
					if($d=='Y'){
						$status = '<button class="btn btn-block btn-danger btn-xs">Blocked</button>';
					}else{
						$status = '<button class="btn btn-block btn-success btn-xs">Unblocked</button>';
					}
					return '<td><center>'.$status.'</center></td>';
				}
			),
			array('db'=>'email', 
					'dt'=>4,
					'formatter'=>function($d,$row){
						return'<td><a href="mailto:'.$d.'">'.$d.'</a></td>';
					}
			),
			array('db'=>'created_date','dt'=>5),
			array(
				  'db'=>'id_user',
				  'dt'=>6,
				  'formatter'=>function( $d, $row ) {
					$aksi="module/mod_users/users_action.php";
					$key = "asdf$1011";
					return '<td><center><button class="btn btn-xs btn-warning" onclick="window.location.href = \'?module=users&act=update2&id='.base64_encrypt($d,$key).'\';"><i class="fa fa-pencil"></i></button> <button data-href="'.$aksi.'?module=users&act=blockeduserclient&id='.base64_encrypt($d,$key).'" class="btn btn-xs btn-danger" type="button" data-toggle="modal" data-target="#confirm-blocked"><i class="fa fa-close"></i></button></center></td>';
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
	
	}elseif($module=='users' AND $act=='update2'){
		$id_user = mysqli_real_escape_string($con, $_POST['id']);
		$blocked = mysqli_real_escape_string($con, $_POST['blocked']);
		$id_kustomer = mysqli_real_escape_string($con, $_POST['id_kustomer']);
		
		mysqli_query($con, "update user_client set blocked='$blocked',
											id_kustomer='$id_kustomer'
							where id_user='$id_user'");
		
		header('location:../../main.php?module='.$module);
		
	}elseif($module=='users' and $act=='blockeduserclient'){
		$key = "asdf$1011";
		$encrypt = "$_GET[id]";
		$id = base64_decrypt($encrypt,$key);
		mysqli_query($con, "update user_client set blocked='Y' where id_user='$id'");
		header('location:../../main.php?module='.$module);
	}

	elseif($module=='users' AND $act=='create'){
		$id_kustomer = mysqli_real_escape_string($con, $_POST['id_kustomer']);
		
		$fullname = mysqli_real_escape_string($con, $_POST['fullname']);
		$username = mysqli_real_escape_string($con, $_POST['username']);		
		$email = mysqli_real_escape_string($con, $_POST['email']);
		$password = mysqli_real_escape_string($con, $_POST['password']);		
		
		$insert = mysqli_query($con, "INSERT into user_client (id_kustomer,
													username,
													email,
													fullname,
													password
													)
											values ('$id_kustomer',
													'$username',
													'$email',
													'$fullname',
													MD5('$password')
													)");
		
		echo "INSERT into user_client (id_kustomer,
													username,
													email,
													fullname,
													password
													)
											values ('$id_kustomer',
													'$username',
													'$email',
													'$fullname',
													MD5('$password')
													)";

		header('location:../../main.php?module='.$module);
		}
		elseif($module=='users' AND $act=='make_usercesco'){
		
		$username = mysqli_real_escape_string($con, $_POST['username']);
		$namalengkap = mysqli_real_escape_string($con, $_POST['fullname']);
				
		$email = mysqli_real_escape_string($con, $_POST['email']);
		$password = mysqli_real_escape_string($con, $_POST['password']);
		$id_cabang = mysqli_real_escape_string($con, $_POST['id_cabang']);
		$level = mysqli_real_escape_string($con, $_POST['level']);		
		
		$insert = mysqli_query($con, "INSERT into user (namalengkap,
													
													email,
													username,
													
													password,
													id_cabang,
													level
													)
											values ('$namalengkap',
													'$email',
													'$username',
													MD5('$password'),
													$id_cabang,
													'$level'
													)");
		
		echo "INSERT into user (namalengkap,
													
													email,
													username,
													
													password,
													id_cabang,
													level
													)
											values ('$namalengkap',
													'$email',
													'$username',
													MD5('$password'),
													$id_cabang,
													$level
													)";

		header('location:../../main.php?module='.$module);
		
	
		
	}
	elseif($module=='users' AND $act=='new_loc_code'){
		


		$id = $_POST['id'];
		


		echo'
		<form action="'.$aksi.'?module=users&act=update_loc_code" name="modal_popup" method="POST">
				<input type="hidden" name="id" class="form-control" value="'.$id.'" />';


		
		echo'
			<div class="form-group" style="padding-bottom: 5px;">
                <label>New Loc Code to:</label>
                <input type="text" name="loc_code">
                
        

			<div class="modal-footer">
				<button class="btn btn-success" type="submit">Confirm</button>		
			</div>
		</form>';
		
	}

	elseif($module=='users' AND $act=='update_loc_code'){
		

		$id_user = mysqli_real_escape_string($con, $_POST['id']);
		$data = mysqli_query($con, 'select * from user_client where id_user="'.$id_user.'"');
		$d = mysqli_fetch_assoc($data);


		$loc_code = mysqli_real_escape_string($con, strtoupper($_POST['loc_code']));
		$new_loc_code = $d['loc_code_otomatis'].",'$loc_code'";
		mysqli_query($con, "update user_client set loc_code_otomatis=\"$new_loc_code\"
								where id_user='$id_user'");
		
		//	echo "update user_client set loc_code_otomatis=\"$new_loc_code\"
		//						where id_user=$id_user";
		header('Location: ' . $_SERVER['HTTP_REFERER']);

	
		
		
	}

}
?>