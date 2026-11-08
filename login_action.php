<?php
include ('config/connection.php');
include ('config/encrypt.php');

date_default_timezone_set('Asia/Jakarta');
$tanggal	= mktime(date("m"),date("d"),date("Y"));
$tglsekarang = date("Y-m-d", $tanggal);
$jamsekarang = date("H:i:s");

// fungsi untuk menghindari injeksi dari user yang jahil
function anti_injection($data){
	$filter  = stripslashes(strip_tags(htmlspecialchars($data,ENT_QUOTES)));
	return $filter;
}

$username = anti_injection($_POST['username']);
$password = anti_injection(md5($_POST['password']));

// menghindari sql injection
$injeksi_username = mysqli_real_escape_string($con, $username);
$injeksi_password = mysqli_real_escape_string($con, $password);

// pastikan username dan password adalah berupa huruf atau angka.
//if (!ctype_alnum($injeksi_username) OR !ctype_alnum($injeksi_password)){
//	echo "<h2>Please don't try to injection</h2>";
//}
//else{
	$login	= mysqli_query($con, "SELECT * FROM user WHERE username = '$username' AND password = '$password' AND blokir='N'");
	$ketemu	= mysqli_num_rows($login);
	$r		= mysqli_fetch_assoc($login);

	

	// Apabila username dan password ditemukan
	if ($ketemu > 0){
		$cabang	= mysqli_query($con, "SELECT cabang_cesco FROM cabang WHERE id_cabang = '$r[id_cabang]'");
	
		$r_cabang		= mysqli_fetch_assoc($cabang);	
		$cabang_cesco = $r_cabang['cabang_cesco'];
		//session_save_path();
		session_start();
		
		$key						= 'asdf$1011';
		$stringawal					= $r['id_user'];
		$id = base64_encrypt($stringawal,$key);
		$_SESSION['id_user']		= $id;
		$_SESSION['username']		= $r['username'];
		$_SESSION['namalengkap']	= $r['namalengkap'];
		$_SESSION['password']		= $r['password'];
		$_SESSION['level']		    = $r['level'];
		$_SESSION['loggedin_time']	= time();
		$_SESSION['id_cabang']	    = $r['id_cabang'];
		$_SESSION['cabang_cesco']	= $cabang_cesco;

		mysqli_query($con, "
		INSERT INTO user_history 
			(id_user, tanggal, jam) 
			VALUES (
				'{$r['id_user']}',
				'$tglsekarang',
				'$jamsekarang'
			)
		
		");
		mysqli_query($con, "update user set jumlahlogin=$r[jumlahlogin]+1 where id_user='$r[id_user]'");
		
		header('Location: main.php?module=home');
		echo 'masuk';
	}
	else{
		//header('location:index.php');
		$logincustomer	= mysqli_query($con, "SELECT * FROM user_client WHERE username = '$username' AND password = '$password' AND blocked='N'");
		$ketemucustomer	= mysqli_num_rows($logincustomer);
		$rcustomer		= mysqli_fetch_assoc($logincustomer);

		if($ketemucustomer > 0){
			session_start();

			$key						= 'asdf$1011';
			$stringawal					= $rcustomer['id_user'];
			$idx = base64_encrypt($stringawal,$key);
			$_SESSION['id_userx']		= $idx;
            $idcx = base64_encrypt($rcustomer['id_kustomer'],$key);
			$_SESSION['id_kustomerx']	= $idcx;
			$_SESSION['usernamex']		= $rcustomer['username'];
			$_SESSION['namalengkapx']	= $rcustomer['fullname'];
			$_SESSION['passwordx']		= $rcustomer['password'];
			$_SESSION['level']		    = 'client';
			$_SESSION['loggedin_timex']	= time();

			mysqli_query($con, "insert into user_client_history (id_user,tanggal) values('$rcustomer[id_user]',now())");
			
			header('Location: client/main.php?module=home');

		}else{
		header('Location: login');
		}

	}
//}
?>
