<?php
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
	
	$aksi="module/mod_booking/booking_action.php";
	
	$key = 'asdf$1011';
	$encrypt = $_SESSION['id_user'];
	$id = base64_decrypt($encrypt,$key);
	$useraktif = mysqli_query($con, 'select * from user where id_user="'.$id.'"');
	$u = mysqli_fetch_assoc($useraktif);

	// load data
	if($module=='booking' AND $act=='read'){
		$requestData= $_REQUEST;
		$column = !empty($requestData['order'][0]['column']) ? $requestData['order'][0]['column'] : '';
		
		$columns = array( 
			0 => 'id_pengiriman',
			1 => 'id_cabang',
			2 => 'tanggal',
			3 => 'jam',
			4 => 'airwaybill_kode',
			5 => 'nama_kustomer',
			6 => 'lokasi_asal',
			7 => 'kode_kota_asal',
			8 => 'nama_kota_asal',
			9 => 'lokasi_tujuan',
			10 => 'kode_kota_tujuan',
			11 => 'nama_kota_tujuan',
			12 => 'tipe_service',
			13 => 'pickup',
			14 => 'service',
			15 => 'import'
		);

		if($u['level']=='admin'){
			$sql = "select id_pengiriman, id_cabang, tanggal, DATE_FORMAT(created_date,'%H:%i') as jam, airwaybill_kode, x_namakustomer(id_kustomer) as nama_kustomer, lokasi_asal, x_kodekota(lokasi_asal) as kode_kota_asal, x_namakotabesar(lokasi_asal) as nama_kota_asal, lokasi_tujuan, x_kodekota(lokasi_tujuan) as kode_kota_tujuan, x_namakotabesar(lokasi_tujuan) as nama_kota_tujuan, x_service(service) as tipe_service, pickup, service, import ";
			$sql.=" from pengiriman where inbound='N' and pickup='N'";
			$query=mysqli_query($con, $sql);
			$totalData = mysqli_num_rows($query);
			$totalFiltered = $totalData;

			if(!empty($requestData['search']['value'])){
				$sql = "select id_pengiriman, id_cabang, tanggal, DATE_FORMAT(created_date,'%H:%i') as jam, airwaybill_kode, x_namakustomer(id_kustomer) as nama_kustomer, lokasi_asal, x_kodekota(lokasi_asal) as kode_kota_asal, x_namakotabesar(lokasi_asal) as nama_kota_asal, lokasi_tujuan, x_kodekota(lokasi_tujuan) as kode_kota_tujuan, x_namakotabesar(lokasi_tujuan) as nama_kota_tujuan, x_service(service) as tipe_service, pickup, service, import ";
				$sql.=" from pengiriman";
				$sql.=" where (inbound='N' and pickup='N')";
				$sql.=" and airwaybill_kode LIKE '".$requestData['search']['value']."%' ";
				$sql.=" or x_namakustomer(id_kustomer) LIKE '".$requestData['search']['value']."%' ";
				$query=mysqli_query($con, $sql);
				$totalFiltered = mysqli_num_rows($query);

				$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."   LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
				$query=mysqli_query($con, $sql);
	
			}else{

				$sql = "select id_pengiriman, id_cabang, tanggal, DATE_FORMAT(created_date,'%H:%i') as jam, airwaybill_kode, x_namakustomer(id_kustomer) as nama_kustomer, lokasi_asal, x_kodekota(lokasi_asal) as kode_kota_asal, x_namakotabesar(lokasi_asal) as nama_kota_asal, lokasi_tujuan, x_kodekota(lokasi_tujuan) as kode_kota_tujuan, x_namakotabesar(lokasi_tujuan) as nama_kota_tujuan, x_service(service) as tipe_service, pickup, service, import ";
				$sql.=" from pengiriman where inbound='N' and pickup='N'";
				$sql.=" order by ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']." LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
				$query=mysqli_query($con, $sql);
	
			}

		}elseif($u['level']=='operator'){
			$sql = "select id_pengiriman, id_cabang, tanggal, DATE_FORMAT(created_date,'%H:%i') as jam, airwaybill_kode, x_namakustomer(id_kustomer) as nama_kustomer, lokasi_asal, x_kodekota(lokasi_asal) as kode_kota_asal, x_namakotabesar(lokasi_asal) as nama_kota_asal, lokasi_tujuan, x_kodekota(lokasi_tujuan) as kode_kota_tujuan, x_namakotabesar(lokasi_tujuan) as nama_kota_tujuan, x_service(service) as tipe_service, pickup, service, import ";
			$sql.=" from pengiriman where inbound='N' and pickup='N' and id_cabang='39'";
			$query=mysqli_query($con, $sql);
			$totalData = mysqli_num_rows($query);
			$totalFiltered = $totalData;

			if(!empty($requestData['search']['value'])){
				$sql = "select id_pengiriman, id_cabang, tanggal, DATE_FORMAT(created_date,'%H:%i') as jam, airwaybill_kode, x_namakustomer(id_kustomer) as nama_kustomer, lokasi_asal, x_kodekota(lokasi_asal) as kode_kota_asal, x_namakotabesar(lokasi_asal) as nama_kota_asal, lokasi_tujuan, x_kodekota(lokasi_tujuan) as kode_kota_tujuan, x_namakotabesar(lokasi_tujuan) as nama_kota_tujuan, x_service(service) as tipe_service, pickup, service, import ";
				$sql.=" from pengiriman";
				$sql.=" where (inbound='N' and pickup='N') and id_cabang='39'";
				$sql.=" and airwaybill_kode LIKE '".$requestData['search']['value']."%' ";
				$sql.=" or x_namakustomer(id_kustomer) LIKE '".$requestData['search']['value']."%' ";
				$query=mysqli_query($con, $sql);
				$totalFiltered = mysqli_num_rows($query);

				$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."   LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
				$query=mysqli_query($con, $sql);
	
			}else{

				$sql = "select id_pengiriman, id_cabang, tanggal, DATE_FORMAT(created_date,'%H:%i') as jam, airwaybill_kode, x_namakustomer(id_kustomer) as nama_kustomer, lokasi_asal, x_kodekota(lokasi_asal) as kode_kota_asal, x_namakotabesar(lokasi_asal) as nama_kota_asal, lokasi_tujuan, x_kodekota(lokasi_tujuan) as kode_kota_tujuan, x_namakotabesar(lokasi_tujuan) as nama_kota_tujuan, x_service(service) as tipe_service, pickup, service, import ";
				$sql.=" from pengiriman where inbound='N' and pickup='N' and id_cabang='39'";
				$sql.=" order by ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']." LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
				$query=mysqli_query($con, $sql);
	
			}

		}elseif($u['level']=='network'){
			$sql = "select id_pengiriman, id_cabang, tanggal, DATE_FORMAT(created_date,'%H:%i') as jam, airwaybill_kode, x_namakustomer(id_kustomer) as nama_kustomer, lokasi_asal, x_kodekota(lokasi_asal) as kode_kota_asal, x_namakotabesar(lokasi_asal) as nama_kota_asal, lokasi_tujuan, x_kodekota(lokasi_tujuan) as kode_kota_tujuan, x_namakotabesar(lokasi_tujuan) as nama_kota_tujuan, x_service(service) as tipe_service, pickup, service, import ";
			$sql.=" from pengiriman where inbound='N' and pickup='N' and (lokasi_asal in (".$u['area'].") or lokasi_tujuan in(".$u['area']."))";
			$query=mysqli_query($con, $sql);
			$totalData = mysqli_num_rows($query);
			$totalFiltered = $totalData;

			if(!empty($requestData['search']['value'])){
				$sql = "select id_pengiriman, id_cabang, tanggal, DATE_FORMAT(created_date,'%H:%i') as jam, airwaybill_kode, x_namakustomer(id_kustomer) as nama_kustomer, lokasi_asal, x_kodekota(lokasi_asal) as kode_kota_asal, x_namakotabesar(lokasi_asal) as nama_kota_asal, lokasi_tujuan, x_kodekota(lokasi_tujuan) as kode_kota_tujuan, x_namakotabesar(lokasi_tujuan) as nama_kota_tujuan, x_service(service) as tipe_service, pickup, service, import ";
				$sql.=" from pengiriman";
				$sql.=" where (inbound='N' and pickup='N')";
				$sql.=" and (lokasi_asal in (".$u['area'].") or lokasi_tujuan in(".$u['area']."))";
				$sql.=" and airwaybill_kode LIKE '".$requestData['search']['value']."%' ";
				$sql.=" or x_namakustomer(id_kustomer) LIKE '".$requestData['search']['value']."%' ";
				$query=mysqli_query($con, $sql);
				$totalFiltered = mysqli_num_rows($query);

				$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."   LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
				$query=mysqli_query($con, $sql);
	
			}else{	

				$sql = "select id_pengiriman, id_cabang, tanggal, DATE_FORMAT(created_date,'%H:%i') as jam, airwaybill_kode, x_namakustomer(id_kustomer) as nama_kustomer, lokasi_asal, x_kodekota(lokasi_asal) as kode_kota_asal, x_namakotabesar(lokasi_asal) as nama_kota_asal, lokasi_tujuan, x_kodekota(lokasi_tujuan) as kode_kota_tujuan, x_namakotabesar(lokasi_tujuan) as nama_kota_tujuan, x_service(service) as tipe_service, pickup, service, import ";
				$sql.=" from pengiriman where inbound='N' and pickup='N'";
				$sql.=" and (lokasi_asal in (".$u['area'].") or lokasi_tujuan in(".$u['area']."))";
				$sql.=" order by ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']." LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
				$query=mysqli_query($con, $sql);
	
			}
		}

		$data = array();
		while($row=mysqli_fetch_array($query)) {
			$key = "asdf$1011";
			$stringawal = $row["id_pengiriman"];
			$id = base64_encrypt($stringawal,$key);
			$nestedData=array();
			
			if($row['nama_kota_asal']=='Jakarta Selatan' || $row['nama_kota_asal']=='Jakarta Timur' || $row['nama_kota_asal']=='Jakarta Barat' ||		$row['nama_kota_asal']=='Jakarta Utara' || $row['nama_kota_asal']=='Jakarta Pusat'){
				$nama_kota_asal = 'Jakarta';
			}else{
				$nama_kota_asal = $row['nama_kota_asal'];
			}
			
			if($row['nama_kota_tujuan']=='Jakarta Selatan' || $row['nama_kota_tujuan']=='Jakarta Timur' || $row['nama_kota_tujuan']=='Jakarta Barat' ||		$row['nama_kota_tujuan']=='Jakarta Utara' || $row['nama_kota_tujuan']=='Jakarta Pusat'){
				$nama_kota_tujuan = 'Jakarta';
			}else{
				$nama_kota_tujuan = $row['nama_kota_tujuan'];
			}
			
			if($row['import']=='Y'){
				$airwaybill_kode = $row['airwaybill_kode'].''.'*';
			}else{
				$airwaybill_kode = $row['airwaybill_kode'];
			}
			
			$nestedData[] = '<td>'.$airwaybill_kode.'</center></td>';
			$nestedData[] = $row['tanggal'].' / '.$row['jam'];
			$nestedData[] = $row["nama_kustomer"];
			$nestedData[] = strtoupper($nama_kota_asal);
			$nestedData[] = strtoupper($nama_kota_tujuan);
			$nestedData[] = strtoupper($row["tipe_service"]);

			$nestedData[] = '<td><center>
			<button class="btn btn-xs btn-warning" onclick="window.location.href = \'?module=booking&act=update&id='.$id.'\';"><i class="fa fa-pencil"></i></button> 

			<button data-href="'.$aksi.'?module=booking&act=delete&id='.$id.'" class="btn btn-xs btn-danger" type="button" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash"></i></button> 

			<button class="btn btn-xs btn-success" onClick="window.open(\'reports/airwaybill.php?id='.$id.'\',\'mywindow\',\'menubar=1,resizable=1,width=800,height=600\');"><i class="fa fa-print"></i></button></center></td>';
			$data[] = $nestedData;
    
		}

		$json_data = array(
			"draw"            => intval( $requestData['draw'] ), 
			"recordsTotal"    => intval( $totalData ), 
			"recordsFiltered" => intval( $totalFiltered ), 
			"data"            => $data
		);

		echo json_encode($json_data);
	
	//kustomer
	}elseif($module=='booking' AND $act=='kustomer'){
		$id_kustomer = $_POST['id_kustomer'];
		$result = mysqli_query($con, "SELECT alamat, kota, id_cabang, id_cabang, x_intercity(kota) as intercityasal, loc_code FROM kustomer WHERE id_kustomer = '$id_kustomer' ");
		$row = mysqli_fetch_assoc($result);
		echo json_encode($row);
	
	// penerima
	}elseif($module=='booking' AND $act=='penerima'){
		$id_kustomer = $_GET['id_kustomer'];
		$sql =  "select nama_penerima, alamat_penerima, hp_penerima, perusahaan_penerima, lokasi_tujuan, x_intercity(lokasi_tujuan) as intercitytujuan from pengiriman where nama_penerima!='' and nama_penerima like '%".$_GET['term']."%' and id_kustomer='".$id_kustomer."' group by nama_penerima limit 10";
		$result = mysqli_query($con,$sql);
		$json = array();
		while($data = mysqli_fetch_assoc($result)){
			$data['label'] = $data['nama_penerima'];
			$data['value'] = $data['nama_penerima'];
			$json[] = $data;
		}
		header("Content-Type: application/json");
		echo json_encode($json);
	
	// cek intercity atau bukan
	}elseif($module=='booking' and $act=='intercity'){
		$lokasi_tujuan = $_POST['lokasi_tujuan'];
		$result = mysqli_query($con, "SELECT intercity FROM kota WHERE id_kota = '$lokasi_tujuan' ");
		$row = mysqli_fetch_assoc($result);
		echo json_encode($row);
	
	// create
	}elseif($module=='booking' AND $act=='create'){
		$tanggal_form = mysqli_real_escape_string($con, $_POST['tanggal']);
		$tanggal = jin_date_sql($tanggal_form);
		$lokasi_asal = mysqli_real_escape_string($con, $_POST['lokasi_asal']);
		$lokasi_tujuan = mysqli_real_escape_string($con, $_POST['lokasi_tujuan']);
		$id_kustomer = mysqli_real_escape_string($con, $_POST['id_kustomer']);
		$nama_pengirim = mysqli_real_escape_string($con, $_POST['nama_pengirim']);
		$alamat_pengirim = mysqli_real_escape_string($con, $_POST['alamat_pengirim']);
		$hp_pengirim = mysqli_real_escape_string($con, $_POST['hp_pengirim']);
		$loc_code = mysqli_real_escape_string($con, $_POST['loc_code']);
		$nama_penerima = mysqli_real_escape_string($con, $_POST['nama_penerima']);
		$alamat_penerima = mysqli_real_escape_string($con, $_POST['alamat_penerima']);
		$hp_penerima = mysqli_real_escape_string($con, $_POST['hp_penerima']);
		$perusahaan_penerima = mysqli_real_escape_string($con, $_POST['perusahaan_penerima']);
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
		$str = mysqli_real_escape_string($con, $_POST['nilai_barang']);
		$nilai_barang = preg_replace('#(\d),(\d)#','$1$2',$str);
		$catatan = mysqli_real_escape_string($con, $_POST['catatan']);
		$biaya_beban = mysqli_real_escape_string($con, $_POST['biaya_beban']);
		$id_cabang = mysqli_real_escape_string($con, $_POST['id_cabang']);
		$nama_pembayaran = mysqli_real_escape_string($con, $_POST['nama_pembayaran']);
		$alamat_pembayaran = mysqli_real_escape_string($con, $_POST['alamat_pembayaran']);
		$cp_pembayaran = mysqli_real_escape_string($con, $_POST['cp_pembayaran']);
		$telp_pembayaran = mysqli_real_escape_string($con, $_POST['telp_pembayaran']);
		
		$intercityasal = mysqli_real_escape_string($con, $_POST['intercityasal']);
		$intercitytujuan = mysqli_real_escape_string($con, $_POST['intercitytujuan']);

		if($intercityasal=='Y' AND $intercitytujuan=='Y'){
			$kode = "002";
		}else{
			$kode = "001";
		}

		$hasil = mysqli_query($con, "SELECT coalesce(max(right(airwaybill_kode,6)),149999) AS last FROM pengiriman WHERE left(airwaybill_kode,3)='$kode'");
		$data = mysqli_fetch_assoc($hasil);
		$lastNoUrut = $data['last'];
		$nextNoUrut	= $lastNoUrut + 1;
		$airwaybillkode	= $kode."-".sprintf('%08s', $nextNoUrut);
		
		$key = "asdf$1011";
		$encrypt = "$_SESSION[id_user]";
		$userid = base64_decrypt($encrypt,$key);

		mysqli_query($con, "insert into pengiriman (airwaybill_kode,
													tanggal,
													lokasi_asal,
													lokasi_tujuan,
													id_kustomer,
													nama_pengirim,
													alamat_pengirim,
													hp_pengirim,
													loc_code,
													nama_penerima,
													alamat_penerima,
													hp_penerima,
													perusahaan_penerima,
													service,
													komoditi,
													jenis_barang,
													berat_barang,
													ukuran_barang,
													collie,
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
													telp_pembayaran)
											values ('$airwaybillkode',
													'$tanggal',
													'$lokasi_asal',
													'$lokasi_tujuan',
													'$id_kustomer',
													'$nama_pengirim',
													'$alamat_pengirim',
													'$hp_pengirim',
													'$loc_code',
													'$nama_penerima',
													'$alamat_penerima',
													'$hp_penerima',
													'$perusahaan_penerima',
													'$service',
													'$komoditi',
													'$jenis_barang',
													'$berat_barang',
													'$ukuran_barang',
													'$jumlah_collie',
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
													'$telp_pembayaran')");
		
		header('location:../../main.php?module='.$module);
	
	// update
	}elseif($module=='booking' AND $act=='update'){
		$tanggal_form = $_POST['tanggal']; // DD-MM-YYYY
		$tanggal = jin_date_sql($tanggal_form); // hasilnya: YYYY-MM-DD
		$lokasi_asal = mysqli_real_escape_string($con, $_POST['lokasi_asal']);
		$lokasi_tujuan = mysqli_real_escape_string($con, $_POST['lokasi_tujuan']);
		$id_kustomer = mysqli_real_escape_string($con, $_POST['id_kustomer']);
		$nama_pengirim = mysqli_real_escape_string($con, $_POST['nama_pengirim']);
		$alamat_pengirim = mysqli_real_escape_string($con, $_POST['alamat_pengirim']);
		$hp_pengirim = mysqli_real_escape_string($con, $_POST['hp_pengirim']);
		$loc_code = mysqli_real_escape_string($con, $_POST['loc_code']);
		$nama_penerima = mysqli_real_escape_string($con, $_POST['nama_penerima']);
		$alamat_penerima = mysqli_real_escape_string($con, $_POST['alamat_penerima']);
		$hp_penerima = mysqli_real_escape_string($con, $_POST['hp_penerima']);
		$perusahaan_penerima = mysqli_real_escape_string($con, $_POST['perusahaan_penerima']);
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
		$str = mysqli_real_escape_string($con, $_POST['nilai_barang']);
		$nilai_barang = preg_replace('#(\d),(\d)#','$1$2',$str);
		$catatan = mysqli_real_escape_string($con, $_POST['catatan']);
		$biaya_beban = mysqli_real_escape_string($con, $_POST['biaya_beban']);
		$id_cabang = mysqli_real_escape_string($con, $_POST['id_cabang']);
		$airwaybill_kode = mysqli_real_escape_string($con, $_POST['airwaybill_kode']);
		$nama_pembayaran = mysqli_real_escape_string($con, $_POST['nama_pembayaran']);
		$alamat_pembayaran = mysqli_real_escape_string($con, $_POST['alamat_pembayaran']);
		$cp_pembayaran = mysqli_real_escape_string($con, $_POST['cp_pembayaran']);
		$telp_pembayaran = mysqli_real_escape_string($con, $_POST['telp_pembayaran']);
		$pickup_by = mysqli_real_escape_string($con, $_POST['pickup_by']);
		
		mysqli_query($con, "update pengiriman set lokasi_asal='$lokasi_asal',
												lokasi_tujuan='$lokasi_tujuan',
												id_kustomer='$id_kustomer',
												nama_pengirim='$nama_pengirim',
												alamat_pengirim='$alamat_pengirim',
												hp_pengirim='$hp_pengirim',
												loc_code='$loc_code',
												nama_penerima='$nama_penerima',
												alamat_penerima='$alamat_penerima',
												hp_penerima='$hp_penerima',
												perusahaan_penerima='$perusahaan_penerima',
												service='$service',
												komoditi='$komoditi',
												jenis_barang='$jenis_barang',
												berat_barang='$berat_barang',
												ukuran_barang='$ukuran_barang',
												collie='$jumlah_collie',
												packing_kayu='$packing_kayu',
												asuransi='$asuransi',
												nilai_barang='$nilai_barang',
												catatan='$catatan',
												biaya_beban='$biaya_beban',
												updated_last='$tgl_jam',
												updated_by='$_SESSION[id_user]',
												tanggal='$tanggal',
												nama_pembayaran='$nama_pembayaran',
												alamat_pembayaran='$alamat_pembayaran',
												cp_pembayaran='$cp_pembayaran',
												telp_pembayaran='$telp_pembayaran'
						WHERE id_pengiriman = '$_POST[id]' and pickup='N'");
		
		//echo '<script>window.open(\'reports/airwaybill.php?id='.$id.'\',\'mywindow\',\'menubar=1,resizable=1,width=800,height=600\');</script>';
		//header('location:../../main.php?module='.$module);
	
	}elseif ($module=='booking' AND $act=='upload'){
		include "../../config/excel_reader2.php";
		$tanggal_form = $_POST['tanggal']; // DD-MM-YYYY
		$tanggal = jin_date_sql($tanggal_form); // hasilnya: YYYY-MM-DD
		$id_kustomer = mysqli_real_escape_string($con, $_POST['id_kustomer']);
		$lokasi_asal = mysqli_real_escape_string($con, $_POST['lokasi_asal']);
		$nama_pengirim = mysqli_real_escape_string($con, $_POST['nama_pengirim']);
		$alamat_pengirim = mysqli_real_escape_string($con, $_POST['alamat_pengirim']);
		$hp_pengirim = mysqli_real_escape_string($con, $_POST['hp_pengirim']);
		$loc_code = mysqli_real_escape_string($con, $_POST['loc_code']);
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
		$str = mysqli_real_escape_string($con, $_POST['nilai_barang']);
		$nilai_barang = preg_replace('#(\d),(\d)#','$1$2',$str);
		$catatan = mysqli_real_escape_string($con, $_POST['catatan']);
		$biaya_beban = mysqli_real_escape_string($con, $_POST['biaya_beban']);
		$id_cabang = mysqli_real_escape_string($con, $_POST['id_cabang']);
		$nama_pembayaran = mysqli_real_escape_string($con, $_POST['nama_pembayaran']);
		$alamat_pembayaran = mysqli_real_escape_string($con, $_POST['alamat_pembayaran']);
		$cp_pembayaran = mysqli_real_escape_string($con, $_POST['cp_pembayaran']);
		$telp_pembayaran = mysqli_real_escape_string($con, $_POST['telp_pembayaran']);
		
		$key = "asdf$1011";
		$encrypt = "$_SESSION[id_user]";
		$userid = base64_decrypt($encrypt,$key);
		
		// membaca file excel yang diupload
		$data = new Spreadsheet_Excel_Reader($_FILES['bookingfile']['tmp_name']);
		
		// membaca jumlah baris dari data excel
		$baris = $data->rowcount($sheet_index=0);
		
		// nilai awal counter untuk jumlah data yang sukses dan yang gagal diimport
		$sukses = 0;
		$gagal = 0;
		// import data excel mulai baris ke-2 (karena baris pertama adalah nama kolom)
		for ($i=2; $i<=$baris; $i++){
			$nama_penerima		= $data->val($i, 1);
			$alamat_penerima	= $data->val($i, 2);
			$lokasi_tujuan		= $data->val($i, 3);
			$hp_penerima		= $data->val($i, 4);
			//$catatan			= $data->val($i, 5); 
			
			//kota tujuan
			$kotatujuan	= mysqli_query($con, "SELECT id_kota FROM kota WHERE nama_kota = '$lokasi_tujuan'");
			$ketemukotatujuan = mysqli_num_rows($kotatujuan);
			$rtujuan = mysqli_fetch_assoc($kotatujuan);
			
			if($ketemukotatujuan > 0){
				$intercityasal = mysqli_real_escape_string($con, $_POST['intercityasal']);
				$intercitytujuan1 = mysqli_query($con, "select intercity from kota where id_kota='$rtujuan[id_kota]'");
				$rintercity = mysqli_fetch_assoc($intercitytujuan1);
				$intercitytujuan = $rintercity['intercity'];
				if($intercityasal=='Y' AND $intercitytujuan=='Y'){
					$kode = "002";
				}else{
					$kode = "001";
				}
		
				$hasilx = mysqli_query($con, "SELECT coalesce(max(right(airwaybill_kode,6)),149999) AS last FROM pengiriman WHERE left(airwaybill_kode,3)='$kode'");
				while($datax = mysqli_fetch_assoc($hasilx)){
					$lastNoUrut = $datax['last'];
					$nextNoUrut	= $lastNoUrut + 1;
					$airwaybillkode	= $kode."-".sprintf('%08s', $nextNoUrut);
				
					$query = "insert into pengiriman (airwaybill_kode,
														tanggal,
														lokasi_asal,
														lokasi_tujuan,
														id_kustomer,
														nama_pengirim,
														alamat_pengirim,
														hp_pengirim,
														loc_code,
														nama_penerima,
														alamat_penerima,
														hp_penerima,
														service,
														komoditi,
														jenis_barang,
														berat_barang,
														ukuran_barang,
														collie,
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
														import)
												values ('$airwaybillkode',
														'$tanggal',
														'$lokasi_asal',
														'$rtujuan[id_kota]',
														'$id_kustomer',
														'$nama_pengirim',
														'$alamat_pengirim',
														'$hp_pengirim',
														'$loc_code',
														'$nama_penerima',
														'$alamat_penerima',
														'$hp_penerima',
														'$service',
														'$komoditi',
														'$jenis_barang',
														'$berat_barang',
														'$ukuran_barang',
														'$jumlah_collie',
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
														'Y')";
													
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

		//header('location:../../main.php?module='.$module);
	}

	elseif($module=='booking' and $act=='delete'){
		$key = "asdf$1011";
		$encrypt = "$_GET[id]";
		$id = base64_decrypt($encrypt,$key);
		mysqli_query($con, "update pengiriman set status='7', cancel_reason='tidak jadi' where id_pengiriman='$id'");
		header('location:../../main.php?module='.$module);
	}
}
?>