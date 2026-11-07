<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
if(!isset($_SESSION)){
    session_start();
}

if (empty($_SESSION['usernamex']) AND empty($_SESSION['passwordx'])){
	header('location:../application/index.php');
}
else{
	include "../../../config/connection.php";
	include "../../../config/library.php";
	include "../../../config/encrypt.php";
	
	$module	= !empty($_GET['module']) ? $_GET['module'] : '';
	$act	= !empty($_GET['act']) ? $_GET['act'] : '';
	
	$aksi="module/mod_mail/mail_action.php";
	
	$key = 'asdf$1011';
	$encrypt = $_SESSION['id_userx'];
	$id = base64_decrypt($encrypt,$key);
	$useraktif = mysqli_query($con, 'select *,x_namakustomer(id_kustomer) as namakustomer from user_client where id_user="'.$id.'"');
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

		echo $penerima.$subject.'<br>'.$u['fullname'].'<br>'.$pesan;

		echo 'select *,x_namakustomer(id_kustomer) as namakustomer from user_client where id_user="'.$id.'"';
		
		
	
		
		$insert_pengiriman = mysqli_query($con, "insert into mail (
														pengirim_client,
														penerima,
														subject,
														pesan,
														dibaca
														
														)
												values (
														'$u[id_user]',
														'9999',
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

	include "../../../config/PHPMailer-master/src/PHPMailer.php";
	include "../../../config/PHPMailer-master/src/Exception.php";
	include "../../../config/PHPMailer-master/src/OAuth.php";
	include "../../../config/PHPMailer-master/src/POP3.php";
	include "../../../config/PHPMailer-master/src/SMTP.php";

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
	$mail->FromName = "noreply"; //nama pengirim

	$mail->addAddress('customercare@cesco-logistics.com', 'customercare'); //email penerima
//	$mail->AddCC('vikibagas@gmail.com', 'CC 2');

	$mail->isHTML(true);

	$mail->Subject = 'from '.$u['email'].', '.$subject; //subject
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

	//	echo 'masuk';
	
	//upload
	}elseif ($module=='mail' AND $act=='upload'){
		include "../../config/excel_reader2.php";
		$tanggal_form = mysqli_real_escape_string($con, $_POST['tanggal']);
		$tanggal = jin_date_sql($tanggal_form);
		$nama_pengirim = mysqli_real_escape_string($con, $_POST['nama_pengirim']);
		$alamat_pengirim1 = mysqli_real_escape_string($con, $_POST['alamat_pengirim1']);
		$alamat_pengirim2 = mysqli_real_escape_string($con, $_POST['alamat_pengirim2']);
		$hp_pengirim = mysqli_real_escape_string($con, $_POST['hp_pengirim']);
		$perusahaan_pengirim = mysqli_real_escape_string($con, $_POST['perusahaan_pengirim']);
		$lokasi_asal = mysqli_real_escape_string($con, $_POST['lokasi_asal']);
		
		$id_kustomer = mysqli_real_escape_string($con, $_POST['id_kustomer']);
		$loc_code = mysqli_real_escape_string($con, $_POST['loc_code']);
		$id_cabang = mysqli_real_escape_string($con, $_POST['id_cabang']);
		
		//$service = mysqli_real_escape_string($con, $_POST['service']);
		//$komoditi = mysqli_real_escape_string($con, $_POST['komoditi']);
		//$jenis_barang = mysqli_real_escape_string($con, $_POST['jenis_barang']);
		//$berat_barang = mysqli_real_escape_string($con, $_POST['berat_barang']);
		//$ukuran_barang = mysqli_real_escape_string($con, $_POST['ukuran_barang']);
		/*$ukuran_barang = '';
		if(isset($_POST['length'])){
			$ukuran_barang .= mysqli_real_escape_string($con, $_POST['length']);
		}
		if(isset($_POST['width'])){
			$ukuran_barang .= 'x' . mysqli_real_escape_string($con, $_POST['width']);
		}
		if(isset($_POST['height'])){
			$ukuran_barang .= 'x' . mysqli_real_escape_string($con, $_POST['height']);
		}*/
		//$jumlah_collie = mysqli_real_escape_string($con, $_POST['jumlah_collie']);
		$packing_kayu = mysqli_real_escape_string($con, $_POST['packing_kayu']);
		$asuransi = mysqli_real_escape_string($con, $_POST['asuransi']);
		$str = mysqli_real_escape_string($con, $_POST['nilai_barang']);
		$nilai_barang = preg_replace('#(\d),(\d)#','$1$2',$str);
		//$catatan = mysqli_real_escape_string($con, $_POST['catatan']);
		$biaya_beban = mysqli_real_escape_string($con, $_POST['biaya_beban']);
		$pid = mysqli_real_escape_string($con, $_POST['pid']);
		
		$nama_pembayaran = mysqli_real_escape_string($con, $_POST['nama_pembayaran']);
		$alamat_pembayaran = mysqli_real_escape_string($con, $_POST['alamat_pembayaran']);
		$cp_pembayaran = mysqli_real_escape_string($con, $_POST['cp_pembayaran']);
		$telp_pembayaran = mysqli_real_escape_string($con, $_POST['telp_pembayaran']);


		$provinsi_asal = mysqli_real_escape_string($con, $_POST['provinsiasal']);
		$provinsiasal_text = mysqli_real_escape_string($con, $_POST['provinsiasal_text']);
		$kotaasal =  mysqli_real_escape_string($con, $_POST['kotaasal']);
		$kotaasal_text = mysqli_real_escape_string($con, $_POST['kotaasal_text']);
		$kecamatanasal =  mysqli_real_escape_string($con, $_POST['kecamatanasal']);
		$kecamatanasal_text = mysqli_real_escape_string($con, $_POST['kecamatanasal_text']);
		$kelurahanasal = mysqli_real_escape_string($con, $_POST['kelurahanasal']);


		
		/*if($_POST['outbound']=='N'){
			$inbound="Y";
		}else{
			$inbound="N";
		}*/
		
		$key = "asdf$1011";
		$encrypt = "$_SESSION[id_user]";
		$userid = base64_decrypt($encrypt,$key);
		
		// membaca file excel yang diupload
		$data = new Spreadsheet_Excel_Reader($_FILES['bookingfile']['tmp_name']);
		
		// membaca jumlah baris dari data excel
		$baris = $data->rowcount($sheet_index=0);

		echo $baris;

		//return false;
		
		// nilai awal counter untuk jumlah data yang sukses dan yang gagal diimport
		$sukses = 0;
		$gagal = 0;
		// import data excel mulai baris ke-2 (karena baris pertama adalah nama kolom)
		for ($i=2; $i<=$baris; $i++){
			$p_d		 		 = $data->val($i, 1);
			$service			 = $data->val($i, 2);

			$nama_penerima		 = $data->val($i, 3);
			$alamat_penerima	 = $data->val($i, 4);
			$provinsi_text		 = $data->val($i, 5);
			$provinsi			 = $data->val($i, 6);
			
			$kota_text 			 = $data->val($i, 7);
			$kota				 = $data->val($i, 8);
			
			$kecamatan_text		 = $data->val($i, 9);
			$kelurahan_text		 = $data->val($i, 10); 

			$hp_penerima		 = $data->val($i, 11);
			$perusahaan_penerima = $data->val($i, 12); 			
			$berat_barang		 = $data->val($i,13);
			$koli			 	 = $data->val($i,14);
			$volume			 	 = $data->val($i,15).'x'.$data->val($i,16).'x'.$data->val($i,17);
			$jenis_barang		 	 = $data->val($i,18);
			$catatan		 	 = $data->val($i,19);
			$pickup_by		 	 = $data->val($i,20);
			//$pickup_date		 = $data->val($i,21);
			//$loc_code		 	 = $data->val($i,14);
			
			//kota tujuan
			//$kotatujuan	= mysqli_query($con, "SELECT id_kota FROM kota WHERE nama_kota = '$lokasi_tujuan'");
			//$ketemukotatujuan = mysqli_num_rows($kotatujuan);
			//$rtujuan = mysqli_fetch_assoc($kotatujuan);
			if($service == "TOP"){
				$layanan = 1;
			}
			elseif($service == "URGENT"){
				$layanan = 2;
			}
			elseif($service == "REGULER"){
				$layanan = 3;
			}
			else{
				$layanan = 5;
			}


			if($p_d == "P"){
				$komoditi = 1;
			}else{
				$komoditi = 2;
			}

			if($nama_penerima != ""){
				if($pickup_by != ""){
					$status = 2;
					$pickup = 'Y';
				}else{
					$status =1;
					$pickup = 'N';
				}

				$qasal = mysqli_query($con, "SELECT intercity FROM regencies WHERE id=$kotaasal");
				$dataasal = mysqli_fetch_assoc($qasal);

				$qtujuan = mysqli_query($con, "SELECT intercity FROM regencies WHERE id=$kota");
				$datatujuan = mysqli_fetch_assoc($qtujuan);


				if($dataasal['intercity']=='Y' AND $datatujuan['intercity']=='Y'){
					$kode = "002";				
				}else{
					$kode = "001";
				}

				$hasilx = mysqli_query($con, "SELECT SUBSTRING(airwaybill_kode, 7) as last  FROM pengiriman WHERE left(airwaybill_kode,3)='$kode' ORDER by id_pengiriman DESC LIMIT 1");
				while($datax = mysqli_fetch_assoc($hasilx)){
					$lastNoUrut = $datax['last'];
					$nextNoUrut	= $lastNoUrut + 1;
					$airwaybillkode	= $kode."-".sprintf('%08s', $nextNoUrut);
				
					 $query = "insert into pengiriman (airwaybill_kode,
														tanggal,
														
														id_provinsi_asal,
														provinsi_asal,
														id_kota_asal,
														kota_asal,
														id_kecamatan_asal,
														kecamatan_asal,
														kelurahan_asal,


														id_kustomer,
														nama_pengirim,
														alamat_pengirim,
														alamat_pengirim2,
														hp_pengirim,
														perusahaan_pengirim,
														loc_code,
														pid,
														id_provinsi_tujuan,
														provinsi_tujuan,
														id_kota_tujuan,
														kota_tujuan,
														kecamatan_tujuan,
														kelurahan_tujuan,

														nama_penerima,
														alamat_penerima,
														hp_penerima,
														perusahaan_penerima,
														service,
														komoditi,
														jenis_barang,
														
														
														
														packing_kayu,
														asuransi,
														nilai_barang,
														catatan,
														biaya_beban,
														created_date,
														created_by,
														id_cabang,
														nama_pembayaran,
														alamat_pembayaran,
														cp_pembayaran,
														telp_pembayaran,
														inbound,
														import,
														pickup_by,
														
														status,
														pickup,
														berat_barang,
														collie,
														ukuran_barang)
												values ('$airwaybillkode',
														'$tanggal',
														
														'$provinsi_asal',
														'$provinsiasal_text',
														'$kotaasal',
														'$kotaasal_text',
														'$kecamatanasal',
														'$kecamatanasal_text',
														'$kelurahanasal',

														'$id_kustomer',
														'$nama_pengirim',
														'$alamat_pengirim1',
														'$alamat_pengirim2',
														'$hp_pengirim',
														'$perusahaan_pengirim',
														'$loc_code',
														'$pid',

														'$provinsi',
														'$provinsi_text',
														'$kota',
														'$kota_text',
														'$kecamatan_text',
														'$kelurahan_text',

														'$nama_penerima',
														'$alamat_penerima',
														'$hp_penerima',
														'$perusahaan_penerima',
														'$layanan',
														'$komoditi',
														'$jenis_barang',
														
														
														
														'$packing_kayu',
														'$asuransi',
														'$nilai_barang',
														'$catatan',
														'$biaya_beban',
														'$tgl_jam',
														'$userid',
														'$id_cabang',
														'$nama_pembayaran',
														'$alamat_pembayaran',
														'$cp_pembayaran',
														'$telp_pembayaran',
														'$inbound',
														'Y',
														'$pickup_by',
														
														'$status',
														'$pickup',
														'$berat_barang',
														'$koli',
														'$volume')";
					
													
					$hasil = mysqli_query($con,$query);
				}
			}
			if ($hasil) $sukses++;
			else $gagal++;
		}


		// tampilan status sukses dan gagal
		echo "<h3>Proses import data selesai.</h3>";
		echo "<p>Jumlah data yang sukses diimport : ".$sukses."<br>";
		echo "Jumlah data yang gagal diimport : ".$gagal."</p>";

		header('location:../../main.php?module='.$module);
		
	// update
	}elseif($module=='mail' AND $act=='update'){
		$tanggal_form = mysqli_real_escape_string($con, $_POST['tanggal']);
		$tanggal = jin_date_sql($tanggal_form);
		$nama_pengirim = mysqli_real_escape_string($con, $_POST['nama_pengirim']);
		
		$alamat_pengirim1 = mysqli_real_escape_string($con, $_POST['alamat_pengirim1']);
		$alamat_pengirim2 = mysqli_real_escape_string($con, $_POST['alamat_pengirim2']);

		$provinsiasal = mysqli_real_escape_string($con, $_POST['provinsiasal']);
		$provinsiasal_text = mysqli_real_escape_string($con, $_POST['provinsiasal_text']);
		
		$kotaasal = mysqli_real_escape_string($con, $_POST['kotaasal']);
		$kotaasal_text = mysqli_real_escape_string($con, $_POST['kotaasal_text']);
		$kecamatanasal = mysqli_real_escape_string($con, $_POST['kecamatanasal']);
		$kecamatanasal_text = mysqli_real_escape_string($con, $_POST['kecamatanasal_text']);
		$kelurahanasal = mysqli_real_escape_string($con, $_POST['kelurahanasal']);

		$provinsi = mysqli_real_escape_string($con, $_POST['provinsi']);
		$provinsi_text = mysqli_real_escape_string($con, $_POST['provinsi_text']);
		
		$kota = mysqli_real_escape_string($con, $_POST['kota']);
		$kota_text = mysqli_real_escape_string($con, $_POST['kota_text']);
		$kecamatan = mysqli_real_escape_string($con, $_POST['kecamatan']);
		$kecamatan_text = mysqli_real_escape_string($con, $_POST['kecamatan_text']);
		$kelurahan = mysqli_real_escape_string($con, $_POST['kelurahan']);
		
		

		$catatan1 = mysqli_real_escape_string($con, $_POST['catatan1']);
		$catatan2 = mysqli_real_escape_string($con, $_POST['catatan2']);
		$catatan3 = mysqli_real_escape_string($con, $_POST['catatan3']);
		$catatan4 = mysqli_real_escape_string($con, $_POST['catatan4']);
		$catatan5 = mysqli_real_escape_string($con, $_POST['catatan5']);
		$catatan6 = mysqli_real_escape_string($con, $_POST['catatan6']);
		$catatan7 = mysqli_real_escape_string($con, $_POST['catatan7']);
		$catatan8 = mysqli_real_escape_string($con, $_POST['catatan8']);
		$catatan9 = mysqli_real_escape_string($con, $_POST['catatan9']);
		$catatan10 = mysqli_real_escape_string($con, $_POST['catatan10']);

		$hp_pengirim = mysqli_real_escape_string($con, $_POST['hp_pengirim']);
		$perusahaan_pengirim = mysqli_real_escape_string($con, $_POST['perusahaan_pengirim']);
		$lokasi_asal = mysqli_real_escape_string($con, $_POST['lokasi_asal']);
		
		$id_kustomer = mysqli_real_escape_string($con, $_POST['id_kustomer']);
		$nama_penerima = mysqli_real_escape_string($con, $_POST['nama_penerima']);
		
		$alamat_penerima1 = mysqli_real_escape_string($con, $_POST['alamat_penerima1']);
		$alamat_penerima2 = mysqli_real_escape_string($con, $_POST['alamat_penerima2']);

		$hp_penerima = mysqli_real_escape_string($con, $_POST['hp_penerima']);
		$perusahaan_penerima = mysqli_real_escape_string($con, $_POST['perusahaan_penerima']);
		$loc_code = mysqli_real_escape_string($con, $_POST['loc_code']);
		$pid = mysqli_real_escape_string($con, $_POST['pid']);
		$lokasi_tujuan = mysqli_real_escape_string($con, $_POST['lokasi_tujuan']);		
		$id_cabang = mysqli_real_escape_string($con, $_POST['id_cabang']);
		$negara_tujuan = mysqli_real_escape_string($con, $_POST['negara_tujuan']);

		$service = mysqli_real_escape_string($con, $_POST['service']);
		$komoditi = mysqli_real_escape_string($con, $_POST['komoditi']);
		$jenis_barang = mysqli_real_escape_string($con, $_POST['jenis_barang']);
		$berat_barang = mysqli_real_escape_string($con, $_POST['berat_barang']);
		//$ukuran_barang = mysqli_real_escape_string($con, $_POST['ukuran_barang']);
		$ukuran_barang = '';
		if(isset($_POST['length'])){
			$ukuran_barang .= mysqli_real_escape_string($con, $_POST['length']);
		}
		if(isset($_POST['width'])){
			$ukuran_barang .= 'x' . mysqli_real_escape_string($con, $_POST['width']);
		}
		if(isset($_POST['height'])){
			$ukuran_barang .= 'x' . mysqli_real_escape_string($con, $_POST['height']);
		}
		$jumlah_collie = mysqli_real_escape_string($con, $_POST['jumlah_collie']);
		$packing_kayu = mysqli_real_escape_string($con, $_POST['packing_kayu']);
		$asuransi = mysqli_real_escape_string($con, $_POST['asuransi']);
		$surat_jalan = mysqli_real_escape_string($con, $_POST['surat_jalan']);
		$str = mysqli_real_escape_string($con, $_POST['nilai_barang']);
		$nilai_barang = preg_replace('#(\d),(\d)#','$1$2',$str);
		$catatan = mysqli_real_escape_string($con, $_POST['catatan']);
		$biaya_beban = mysqli_real_escape_string($con, $_POST['biaya_beban']);
		
		$nama_pembayaran = mysqli_real_escape_string($con, $_POST['nama_pembayaran']);
		$alamat_pembayaran = mysqli_real_escape_string($con, $_POST['alamat_pembayaran']);
		$cp_pembayaran = mysqli_real_escape_string($con, $_POST['cp_pembayaran']);
		$telp_pembayaran = mysqli_real_escape_string($con, $_POST['telp_pembayaran']);
		
		$filex = mysqli_real_escape_string($con, $_POST['datafile']);
		$acak = rand(1,99);
		$file = strtolower($acak.$filex);
		$today = date("Ymd");
		
		if (!file_exists('../../upload/'.$today.'/')) {
			mkdir('../../upload/'.$today.'/', 0777, true);
		}
		
		$key = "asdf$1011";
		$encrypt = "$_SESSION[id_user]";
		$userid = base64_decrypt($encrypt,$key);
		
		if($service=='6'){
			$tujuan = $negara_tujuan;
		}else{
			$tujuan = $lokasi_tujuan;
		}

		$duapuluhft = mysqli_real_escape_string($con, $_POST['20ft']);
		$empatpuluhft = mysqli_real_escape_string($con, $_POST['40ft']);
		$empatpuluhfthc = mysqli_real_escape_string($con, $_POST['40ft_hc']);
		$lcl = mysqli_real_escape_string($con, $_POST['lcl']);
		
		mysqli_query($con, "update pengiriman set 
													nama_pengirim='$nama_pengirim',
													pid='$pid',
													alamat_pengirim='$alamat_pengirim1',
													alamat_pengirim2='$alamat_pengirim2',



													hp_pengirim='$hp_pengirim',
													perusahaan_pengirim='$perusahaan_pengirim',
													
													id_provinsi_asal = '$provinsiasal',
													provinsi_asal = '$provinsiasal_text',
													id_kota_asal = '$kotaasal',
													kota_asal = '$kotaasal_text',
													id_kecamatan_asal = '$kecamatanasal',
													kecamatan_asal = '$kecamatanasal_text',
													kelurahan_asal = '$kelurahanasal',

													id_kustomer='$id_kustomer',
													nama_penerima='$nama_penerima',
													alamat_penerima='$alamat_penerima1',
													alamat_penerima2='$alamat_penerima2',
													hp_penerima='$hp_penerima',
													perusahaan_penerima='$perusahaan_penerima',

													id_provinsi_tujuan = '$provinsi',
													provinsi_tujuan = '$provinsi_text',
													id_kota_tujuan = '$kota',
													kota_tujuan = '$kota_text',
													id_kecamatan_tujuan = '$kecamatan',
													kecamatan_tujuan = '$kecamatan_text',
													kelurahan_tujuan = '$kelurahan',
													
													loc_code='$loc_code',
													service='$service',
													komoditi='$komoditi',
													jenis_barang='$jenis_barang',
													berat_aktual='$berat_barang',
													ukuran_barang='$ukuran_barang',
													collie='$jumlah_collie',
													packing_kayu='$packing_kayu',
													asuransi='$asuransi',
													surat_jalan='$surat_jalan',
													nilai_barang='$nilai_barang',
													catatan1 = '$catatan1',
													catatan2 = '$catatan2',
													catatan3 = '$catatan3',
													catatan4 = '$catatan4',
													catatan5 = '$catatan5',
													catatan6 = '$catatan6',
													catatan7 = '$catatan7',
													catatan8 = '$catatan8',
													catatan9 = '$catatan9',
													catatan10 = '$catatan10',

													biaya_beban='$biaya_beban',
													updated_last='$tgl_jam',
													updated_by='$userid',
													nama_pembayaran='$nama_pembayaran',
													alamat_pembayaran='$alamat_pembayaran',
													cp_pembayaran='$cp_pembayaran',
													telp_pembayaran='$telp_pembayaran',
													20ft='$duapuluhft',
													40ft='$empatpuluhft',
													40ft_hc='$empatpuluhfthc',
													lcl='$lcl'
							WHERE id_pengiriman = '$_POST[id]'");

		include "../../config/excel_reader2.php";

		$data = new Spreadsheet_Excel_Reader($_FILES['uploadcatatan']['tmp_name']);



		if(is_uploaded_file($_FILES['uploadcatatan']['tmp_name'])){	
			
			// membaca jumlah baris dari data excel
			$baris = $data->rowcount($sheet_index=0);
			
			// nilai awal counter untuk jumlah data yang sukses dan yang gagal diimport
			$sukses = 0;
			$gagal = 0;
			//echo 'test';
			
			// import data excel mulai baris ke-2 (karena baris pertama adalah nama kolom)
			for ($i=2; $i<=$baris; $i++){
				$product			 = $data->val($i, 1);
				$aprc				 = $data->val($i, 2);
				$part_n				 = $data->val($i, 3);
				$part_n_desc		 = $data->val($i, 4);
				$rma_no				 = $data->val($i, 5); 
				$qty				 = $data->val($i,6);
				$remark				 = $data->val($i,7);
				
				
				
					if(is_numeric($qty)){
						$qty = $qty;
					}else{
						$qty = 1;
					}

					
			
					
					
						$query = "insert into asus_detail_catatan (id_pengiriman,
															product,
															aprc,
															part_n,
															part_n_desc,
															rma_no,
															qty,
															remark																								
															)
													values ('$_POST[id]',
															'$product',
															'$aprc',
															'$part_n',
															'$part_n_desc',
															'$rma_no',
															'$qty',
															'$remark'
															)";					
														
						$hasil = mysqli_query($con,$query);
					
						
				if ($hasil) $sukses++;
				else $gagal++;
			}
		 'sukses = '.$sukses. '<br>';
			'gagal = '.$gagal. '<br>';

		}
		//echo $surat_jalan;
		header('location:../../main.php?module='.$module);
		
	}
	elseif($module=='mail' and $act=='delete_catatan'){
		
		$id = "$_GET[id_catatan]";
		
		//mysqli_query($con, "delete from pengiriman where id_pengiriman='$id'");
		mysqli_query($con, "delete from asus_detail_catatan  where id_pengiriman='$id'");
		header('location:../../main.php?module='.$module);
	}
	elseif($module=='mail' and $act=='delete'){
		$key = "asdf$1011";
		$encrypt = "$_GET[id]";
		$id = base64_decrypt($encrypt,$key);
		mysqli_query($con, "delete from pengiriman where id_pengiriman='$id'");
		header('location:../../main.php?module='.$module);
	}
	elseif($module='mail' and $act == 'data-wilayah'){
		switch ($_GET['jenis']) {
		  //ambil data kota / kabupaten
		  case 'kota':
		  $id_provinces = $_POST['id_provinces'];
		  if($id_provinces == ''){
		       exit;
		  }else{
		       $getcity = mysqli_query($con,"SELECT  * FROM regencies WHERE province_id ='$id_provinces' ORDER BY name ASC") or die ('Query Gagal');
		       while($data = mysqli_fetch_array($getcity)){
		            echo '<option value="'.$data['id'].'">'.$data['name'].'</option>';
		       }
		       exit;    
		  }
		  break;

		  //ambil data kecamatan
		  case 'kecamatan':
		  $id_regencies = $_POST['id_regencies'];
		  if($id_regencies == ''){
		       exit;
		  }else{
		       $getcity = mysqli_query($con,"SELECT  * FROM districts WHERE regency_id ='$id_regencies' ORDER BY name ASC") or die ('Query Gagal');
		       while($data = mysqli_fetch_array($getcity)){
		            echo '<option value="'.$data['id'].'">'.$data['name'].'</option>';
		       }
		       exit;    
		  }
		  break;
		  
		  //ambil data kelurahan
		  case 'kelurahan':
		  $id_district = $_POST['id_district'];
		  if($id_district == ''){
		       exit;
		  }else{
		       $getcity = mysqli_query($con,"SELECT  * FROM villages WHERE district_id ='$id_district' ORDER BY name ASC") or die ('Query Gagal');
		       while($data = mysqli_fetch_array($getcity)){
		            echo '<option value="'.$data['name'].'">'.$data['name'].'</option>';
		       }
		       exit;    
		  }
		  break;
		 }
	}

	elseif ($module=='mail' AND $act=='form-insert-sla')
	{	
		
		$id = $_POST['id'];
		
		$query = mysqli_query($con, "select *,x_service(service) as jenis_layanan,SUBSTRING_INDEX(ukuran_barang,'x',1) as length, SUBSTRING_INDEX(SUBSTRING_INDEX(ukuran_barang,'x',2),'x',-1) as width, SUBSTRING_INDEX(ukuran_barang,'x',-1) as height, DATE_FORMAT(created_date,'%Y%m%d') as folder from pengiriman where id_pengiriman='$id'");
			$r = mysqli_fetch_assoc($query);
		
		echo'
		<form action="'.$aksi.'?module=mail&act=insert-sla" method="POST">
			
			<div class="form-group" style="padding-bottom: 25px;">
				<label class="col-md-3 control-label">Service</label>
				<div class="col-md-8">
					<input type="text" value="'.$r['jenis_layanan'].'" class="form-control" readonly>
				</div>
			</div>			
			
			<div class="form-group" style="padding-bottom: 25px;">
				<label class="col-md-3 control-label">Kota Asal</label>
				<div class="col-md-8">
					<input type="text" value="'.$r['kota_asal'].'" class="form-control" readonly>
				</div>
			</div>			
			<div class="form-group" style="padding-bottom: 25px;">
				<label class="col-md-3 control-label">Kota Tujuan</label>
				<div class="col-md-8">
					<input type="text" value="'.$r['kota_tujuan'].'" class="form-control" readonly>
				</div>
			</div>			
						
			
			<div class="form-group" style="padding-bottom: 25px;">
				<label class="col-md-3 control-label">Lead Time / SLA:</label>
				<div class="col-md-8">
					<input type="number" name="sla" class="form-control" value="2" min="2" required/>
				</div>
			</div>
			<input type="hidden" name="id_pengiriman" class="form-control" value="'.$r['id_pengiriman'].'" />
			
			<input type="hidden" name="id_lokasi_asal" class="form-control" value="'.$r['id_kota_asal'].'" />
			<input type="hidden" name="id_lokasi_tujuan" class="form-control" value="'.$r['id_kota_tujuan'].'" />			
			<input type="hidden" name="id_pelayanan" class="form-control" value="'.$r['service'].'" />
			
			<div class="modal-footer">
				<button class="btn btn-success" type="submit">insert</button>
				<button type="reset" class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Cancel</button>
			</div>
		</form>';
		
	}
	elseif ($module=='mail' AND $act=='insert-sla')
	{	
		
		$id = $_POST['id_pengiriman'];
		$sla = $_POST['sla'];

		$result = mysqli_query($con, "SELECT id_kota_asal, id_kota_tujuan, x_inter(id_kota_asal) as intercity_asal, x_inter(id_kota_tujuan) as intercity_tujuan, service FROM pengiriman WHERE id_pengiriman = $id ");
		$row = mysqli_fetch_assoc($result);

		echo "SELECT id_kota_asal, id_kota_tujuan, x_inter(id_kota_asal) as intercity_asal, x_inter(id_kota_tujuan) as intercity_tujuan, service FROM pengiriman WHERE id_pengiriman = $id ";

		$inter_asal = $row['intercity_asal'];
		$inter_tujuan = $row['intercity_tujuan'];

		if($inter_asal == 'Y' and $inter_tujuan == ''){
			//outbound
			mysqli_query($con, "insert into  sla (id,kota_asal,kota_tujuan,service,sla_max) values ('',9999,$row[id_kota_tujuan],$row[service],$sla)");
		}
		if($inter_asal == '' and $inter_tujuan == 'Y'){
			mysqli_query($con, "insert into  sla_in (id,kota_asal,kota_tujuan,service,sla_max) values ('',$row[id_kota_asal],9999,$row[service],$sla)");
		}
		if($inter_asal == '' and $inter_tujuan == ''){
			mysqli_query($con, "insert into  sla_cross (id,kota_asal,kota_tujuan,service,sla_max) values ('',$row[id_kota_asal],$row[id_kota_tujuan],$row[service],$sla)");
		}

		//echo "<br>insert into  sla (id,kota_asal,kota_tujuan,service,sla_max) values ('',$row[id_kota_asal],$row[id_kota_tujuan],$row[service],$sla)";
		
		
		header('Location: ' . $_SERVER['HTTP_REFERER']);
	}
}
?>