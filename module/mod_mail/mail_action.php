<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if(!isset($_SESSION)){
    session_start();
}

if (empty($_SESSION['username']) AND empty($_SESSION['password'])){
	header('location:../application/index.php');
}
else{
	include "../../config/connection.php";
	include "../../config/library.php";
	include "../../config/encrypt.php";
	
	$module	= !empty($_GET['module']) ? $_GET['module'] : '';
	$act	= !empty($_GET['act']) ? $_GET['act'] : '';
	
	$aksi="module/mod_mail/mail_action.php";
	
	$key = 'asdf$1011';
	$encrypt = $_SESSION['id_user'];
	$id = base64_decrypt($encrypt,$key);
	$useraktif = mysqli_query($con, 'select * from user where id_user="'.$id.'"');
	$u = mysqli_fetch_assoc($useraktif);

	
	if($module=='mail' AND $act=='kustomer'){
		$id_kustomer = $_POST['id_kustomer'];
		$result = mysqli_query($con, "SELECT id_cabang, nama_kustomer,pic_utama, substring(alamat,1,52) as alamat1,substring(alamat,53,52) as alamat2 ,provinsi, kota,kecamatan,kelurahan, loc_code, telp FROM kustomer WHERE id_kustomer = '$id_kustomer' ");
		$row = mysqli_fetch_assoc($result);
		echo json_encode($row);
	
	// pengirim
	}elseif($module=='mail' AND $act=='pengirim'){

		$id_kustomer = $_GET['id_kustomer'];
		$sql =  "select nama_pengirim, substring(alamat_pengirim,1,52) as alamatlama1,substring(alamat_pengirim,53,52) as alamatlama2, alamat_pengirim,alamat_pengirim2, hp_pengirim, perusahaan_pengirim, id_provinsi_asal,id_kota_asal,id_kecamatan_asal,kelurahan_asal from pengiriman where nama_pengirim!='' and nama_pengirim like '%".$_GET['term']."%' and id_kustomer='".$id_kustomer."' and id_pengiriman in (SELECT MAX(id_pengiriman) from pengiriman GROUP by nama_pengirim )  order by id_pengiriman desc limit 10";
		$result = mysqli_query($con,$sql);
		$json = array();
		while($data = mysqli_fetch_assoc($result)){

			if($data['alamat_pengirim2']== '' || $data['alamat_pengirim2'] === null){
				$pengirimbaru1 = $data['alamatlama1'];
				$pengirimbaru2 = $data['alamatlama2'];
			}
			else{
				$pengirimbaru1 = $data['alamat_pengirim'];
				$pengirimbaru2 = $data['alamat_pengirim2'];
			}

			$data['label'] = $data['nama_pengirim'];
			$data['value'] = $data['nama_pengirim'];
			$data['alamat_pengirimbaru1'] = $pengirimbaru1 ;
			$data['alamat_pengirimbaru2'] = $pengirimbaru2 ;
			$json[] = $data;
		}
		header("Content-Type: application/json");
		echo json_encode($json);

	// penerima
	}elseif($module=='mail' AND $act=='penerima'){
		$id_kustomer = $_GET['id_kustomer'];
		$sql =  "select nama_penerima, substring(alamat_penerima,1,52) as alamatlama1,substring(alamat_penerima,53,52) as alamatlama2, alamat_penerima,alamat_penerima2, hp_penerima, perusahaan_penerima, id_provinsi_tujuan,id_kota_tujuan,id_kecamatan_tujuan,kelurahan_tujuan from pengiriman where nama_penerima!='' and nama_penerima like '%".$_GET['term']."%' and id_kustomer='".$id_kustomer."' and id_pengiriman in (SELECT MAX(id_pengiriman) from pengiriman GROUP by nama_penerima )  order by id_pengiriman desc limit 10";
		$result = mysqli_query($con,$sql);
		$json = array();
		while($data = mysqli_fetch_assoc($result)){
			if($data['alamat_penerima2']== '' || $data['alamat_penerima2'] === null){
				$penerimabaru1 = $data['alamatlama1'];
				$penerimabaru2 = $data['alamatlama2'];
			}
			else{
				$penerimabaru1 = $data['alamat_penerima'];
				$penerimabaru2 = $data['alamat_penerima2'];
			}

			$data['label'] = $data['nama_penerima'];
			$data['value'] = $data['nama_penerima'];
			$data['alamat_penerimabaru1'] = $penerimabaru1 ;
			$data['alamat_penerimabaru2'] = $penerimabaru2 ;
			$json[] = $data;
		}
		header("Content-Type: application/json");
		echo json_encode($json);
	
	// cek intercity atau bukan
	}elseif($module=='mail' and $act=='intercity'){
		$lokasi_asal = $_POST['lokasi_asal'];
		$result = mysqli_query($con, "SELECT intercity FROM kota WHERE id_kota = '$lokasi_asal' ");
		$row = mysqli_fetch_assoc($result);
		echo json_encode($row);
	
	// create
	}elseif($module=='mail' AND $act=='create'){
		$penerima = mysqli_real_escape_string($con, $_POST['penerima']);
		//$tanggal = jin_date_sql($tanggal_form);
		$subject = mysqli_real_escape_string($con, $_POST['subject']);
		$pesan = $_POST['pesan'];



		echo $penerima.$subject.'<br><br>'.$pesan;
		
		
	
		
		$insert_pengiriman = mysqli_query($con, "insert into mail (
														pengirim,
														penerima_client,
														subject,
														pesan,
														dibaca
														
														)
												values (
														'$u[id_user]',
														'$penerima',
														'$subject',
														'$pesan',
														'N'																												
														)");

		if($insert_pengiriman){
			echo 'berhasil';
		}else{
			echo 'gagal';
		}

		//$last_id_pengiriman = mysqli_insert_id($con);		

		$query = mysqli_query($con, "select * from user_client where id_user='$penerima'");
			$r = mysqli_fetch_assoc($query);

	

include "../../config/PHPMailer-master/src/PHPMailer.php";
include "../../config/PHPMailer-master/src/Exception.php";
include "../../config/PHPMailer-master/src/OAuth.php";
include "../../config/PHPMailer-master/src/POP3.php";
include "../../config/PHPMailer-master/src/SMTP.php";

	$mail = new PHPMailer;

	//Enable SMTP debugging.
	//$mail->SMTPDebug = 3;
	//Set PHPMailer to use SMTP.

	$mail->isSMTP();
	//Set SMTP host name
	$mail->Host = "mail.cesco-logistics.com"; //host mail server
	//Set this to true if SMTP host requires authentication to send email
	$mail->SMTPAuth = true;
	//Provide username and password
	$mail->Username = "itsupport@cesco-logistics.com";   //nama-email smtp
	$mail->Password = "cilandak2019";           //password email smtp
	//If SMTP requires TLS encryption then set it
	$mail->SMTPAutoTLS = false;
	$mail->SMTPSecure = false;
	//Set TCP port to connect to
	$mail->Port = 25;

	$mail->From = "itsupport@cesco-logistics.com"; //email pengirim
	$mail->FromName = "no-reply"; //nama pengirim

	$mail->addAddress($r['email'], $r['fullname']); //email penerima
	$mail->AddCC('customercare@cesco-logistics.com', 'customercare');

	$mail->isHTML(true);

	$mail->Subject = $subject; //subject
	$mail->Body    = $pesan; //isi email
	$mail->AltBody = "PHP mailer"; //body email

	if(!$mail->send())
	{
	echo "Mailer Error: " . $mail->ErrorInfo;
	}
	else
	{
	echo "Message has been sent successfully";
	}		
		
		
	header('location:../../main.php?module='.$module.'&act=sent');

		echo 'masuk';
	
	//upload
	}
	elseif($module=='mail' and $act=='delete'){
		//$key = "asdf$1011";
		$id = "$_GET[id]";
		//$id = base64_decrypt($encrypt,$key);
		mysqli_query($con, "delete from mail where id='$id'");
		header('location:../../main.php?module='.$module);
	}

	elseif ($module=='mail' AND $act=='delete-all'){
		$tanggal		= jin_date_sql($_POST['deletemessage']);
		$mail		= $_POST['selectmail'];
		$id_mail		= implode(',',$mail);
		//echo "delete from mail where id in ($id_mail)";

		mysqli_query($con, "delete from mail where id in ($id_mail)");
		header('location:../../main.php?module='.$module);
	}
	
}
?>