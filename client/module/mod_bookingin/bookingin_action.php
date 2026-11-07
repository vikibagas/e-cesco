<?php
if(!isset($_SESSION)){
    session_start();
}

if (empty($_SESSION['usernamex']) AND empty($_SESSION['passwordx'])){
	header('location:../../../index.php');
}
else{
	include "../../../config/connection.php";
	include "../../../config/library.php";
	include "../../../config/encrypt.php";
	
	$module	= !empty($_GET['module']) ? $_GET['module'] : '';
	$act	= !empty($_GET['act']) ? $_GET['act'] : '';
	
	$aksi="module/mod_bookingin/bookingin_action.php";
	
	$key = 'asdf$1011';
	$encrypt = $_SESSION['id_userx'];
	$id = base64_decrypt($encrypt,$key);
	$useraktif = mysqli_query($con, 'select * from user_client where id_user="'.$id.'"');
	$u = mysqli_fetch_assoc($useraktif);

	// load data
	if($module=='bookingin' AND $act=='read'){
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
			13 => 'pickup'
		);

		$sql = "select id_pengiriman, id_cabang, tanggal, DATE_FORMAT(created_date,'%H:%i') as jam, airwaybill_kode, x_namakustomer(id_kustomer) as nama_kustomer, lokasi_asal, x_kodekota(lokasi_asal) as kode_kota_asal, x_namakotabesar(lokasi_asal) as nama_kota_asal, lokasi_tujuan, x_kodekota(lokasi_tujuan) as kode_kota_tujuan, x_namakotabesar(lokasi_tujuan) as nama_kota_tujuan, x_service(service) as tipe_service, pickup, perusahaan_penerima ";
		$sql.=" from pengiriman where pickup='N' and user_client='".$u['id_user']."' and status !=7";
		$query=mysqli_query($con, $sql);
		$totalData = mysqli_num_rows($query);
		$totalFiltered = $totalData;

		if(!empty($requestData['search']['value'])){
			$sql = "select id_pengiriman, id_cabang, tanggal, DATE_FORMAT(created_date,'%H:%i') as jam, airwaybill_kode, x_namakustomer(id_kustomer) as nama_kustomer, lokasi_asal, x_kodekota(lokasi_asal) as kode_kota_asal, x_namakotabesar(lokasi_asal) as nama_kota_asal, lokasi_tujuan, x_kodekota(lokasi_tujuan) as kode_kota_tujuan, x_namakotabesar(lokasi_tujuan) as nama_kota_tujuan, x_service(service) as tipe_service, pickup, perusahaan_penerima ";
			$sql.=" from pengiriman";
			$sql.=" where pickup='N' and user_client='".$u['id_user']."' and status !=7";
			$sql.=" and airwaybill_kode LIKE '".$requestData['search']['value']."%' ";
			$sql.=" or x_namakustomer(id_kustomer) LIKE '".$requestData['search']['value']."%' ";
			$query=mysqli_query($con, $sql);
			$totalFiltered = mysqli_num_rows($query);

			$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."   LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
			$query=mysqli_query($con, $sql);
	
		}else{	

			$sql = "select id_pengiriman, id_cabang, tanggal, DATE_FORMAT(created_date,'%H:%i') as jam, airwaybill_kode, x_namakustomer(id_kustomer) as nama_kustomer, lokasi_asal, x_kodekota(lokasi_asal) as kode_kota_asal, x_namakotabesar(lokasi_asal) as nama_kota_asal, lokasi_tujuan, x_kodekota(lokasi_tujuan) as kode_kota_tujuan, x_namakotabesar(lokasi_tujuan) as nama_kota_tujuan, x_service(service) as tipe_service, pickup, perusahaan_penerima ";
			$sql.=" from pengiriman where pickup='N' and user_client='".$u['id_user']."' and status != 7";
			$sql.=" order by ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']." LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
			$query=mysqli_query($con, $sql);
	
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
			
			$nestedData[] = '<td><center>'.$row["airwaybill_kode"].'</center></td>';
			$nestedData[] = $row['tanggal'].' '.$row['jam'];
			$nestedData[] = $row["nama_kustomer"];
			$nestedData[] = strtoupper($nama_kota_asal);
			$nestedData[] = strtoupper($nama_kota_tujuan);
			$nestedData[] = strtoupper($row["perusahaan_penerima"]);
			$nestedData[] = strtoupper($row["tipe_service"]);

			$nestedData[] = '<td><center><button class="btn btn-xs btn-warning" onclick="window.location.href = \'?module=bookingin&act=update&id='.$id.'\';"><i class="fa fa-pencil"></i></button> 
			<button data-href="'.$aksi.'?module=bookingin&act=delete&id='.$id.'" class="btn btn-xs btn-danger" type="button" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-close"></i> </button>

			</button> <button class="btn btn-xs btn-success" onClick="window.open(\'reports/airwaybill_inbound.php?id='.$id.'\',\'mywindow\',\'menubar=1,resizable=1,width=800,height=600\');"><i class="fa fa-print"></i></button>
			'. ($u[id_kustomer] == 106 ?  '
			<button class="btn btn-xs btn-success" onClick="window.open(\'reports/airwaybill_asus.php?id='.$row['id_pengiriman'].'\',\'mywindow\',\'menubar=1,resizable=1,width=800,height=600\');"><i class="fa fa-print"></i></button>' : '' ).'

			</center></td>';
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
	}elseif($module=='bookingin' AND $act=='kustomer'){
		$id_kustomer = $_POST['id_kustomer'];
		$result = mysqli_query($con, "SELECT id_cabang, nama_kustomer,telp,pic_utama, alamat, kota, loc_code, x_intercity(kota) as intercityasal FROM kustomer WHERE id_kustomer = '$id_kustomer' ");
		$row = mysqli_fetch_assoc($result);
		echo json_encode($row);
	
	// pengirim
	}elseif($module=='bookingin' AND $act=='pengirim'){
		$id_kustomer = $_GET['id_kustomer'];
		$sql =  "select nama_pengirim, alamat_pengirim, hp_pengirim, perusahaan_pengirim, lokasi_asal, x_intercity(lokasi_asal) as intercityasal from pengiriman where nama_pengirim!='' and nama_pengirim like '%".$_GET['term']."%' and id_kustomer='".$id_kustomer."' group by nama_pengirim order by id_pengiriman desc limit 10";
		$result = mysqli_query($con,$sql);
		$json = array();
		while($data = mysqli_fetch_assoc($result)){
			$data['label'] = $data['nama_pengirim'];
			$data['value'] = $data['nama_pengirim'];
			$json[] = $data;
		}
		header("Content-Type: application/json");
		echo json_encode($json);

	// penerima
	}elseif($module=='bookingin' AND $act=='penerima'){
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
	
	// pembayaran
	}elseif($module=='bookingin' AND $act=='pembayaran'){
		$id_kustomer = $_GET['id_kustomer'];
		$sql =  "select nama_pembayaran, alamat_pembayaran, cp_pembayaran, telp_pembayaran from pengiriman where nama_pembayaran!='' and nama_pembayaran like '%".$_GET['term']."%' and id_kustomer='".$id_kustomer."' group by nama_pembayaran limit 10";
		$result = mysqli_query($con,$sql);
		$json = array();
		while($data = mysqli_fetch_assoc($result)){
			$data['label'] = $data['nama_pembayaran'];
			$data['value'] = $data['nama_pembayaran'];
			$json[] = $data;
		}
		header("Content-Type: application/json");
		echo json_encode($json);
		
	// cek intercity atau bukan
	}elseif($module=='bookingin' and $act=='intercity'){
		$lokasi_asal = $_POST['lokasi_asal'];
		$result = mysqli_query($con, "SELECT intercity FROM kota WHERE id_kota = '$lokasi_asal' ");
		$row = mysqli_fetch_assoc($result);
		echo json_encode($row);
	
	// create
	}elseif($module=='bookingin' AND $act=='create'){
		$tanggal_form = mysqli_real_escape_string($con, $_POST['tanggal']);
		$tanggal = jin_date_sql($tanggal_form);
		$nama_pengirim = mysqli_real_escape_string($con, $_POST['nama_pengirim']);
		$alamat_pengirim = mysqli_real_escape_string($con, $_POST['alamat_pengirim']);
		$hp_pengirim = mysqli_real_escape_string($con, $_POST['hp_pengirim']);
		$perusahaan_pengirim = mysqli_real_escape_string($con, $_POST['perusahaan_pengirim']);
		$lokasi_asal = mysqli_real_escape_string($con, $_POST['lokasi_asal']);
		
		$id_kustomer = mysqli_real_escape_string($con, $_POST['id_kustomer']);
		$nama_penerima = mysqli_real_escape_string($con, $_POST['nama_penerima']);
		$alamat_penerima = mysqli_real_escape_string($con, $_POST['alamat_penerima']);
		$hp_penerima = mysqli_real_escape_string($con, $_POST['hp_penerima']);
		$perusahaan_penerima = mysqli_real_escape_string($con, $_POST['perusahaan_penerima']);
		

		//loc code otomatis asus
		if($u['id_kustomer'] == 106){
			$hasil = mysqli_query($con, "SELECT right(loc_code,8) AS last FROM pengiriman WHERE left(loc_code,4)='ASUS' and id_kustomer=106 ORDER BY id_pengiriman DESC limit 1");
			//echo "SELECT right(loc_code,8) AS last FROM pengiriman WHERE left(loc_code,4)='ASUS' and id_kustomer=106 ORDER BY id_pengiriman DESC limit 1";
			$data = mysqli_fetch_assoc($hasil);
			$lastNoUrut = $data['last'];
			$nextNoUrut	= $lastNoUrut + 1; 
		
		
			$loc_code	= "ASUS".$nextNoUrut;		
		}
		else {
			$loc_code = mysqli_real_escape_string($con, $_POST['loc_code']);
		}
		if(isset($_POST['pid'])){
			$pid = mysqli_real_escape_string($con, $_POST['pid']);
		}
		$lokasi_tujuan = mysqli_real_escape_string($con, $_POST['lokasi_tujuan']);		
		$id_cabang = mysqli_real_escape_string($con, $_POST['id_cabang']);
		
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
		
		$nama_pembayaran = mysqli_real_escape_string($con, $_POST['nama_pembayaran']);
		$alamat_pembayaran = mysqli_real_escape_string($con, $_POST['alamat_pembayaran']);
		$cp_pembayaran = mysqli_real_escape_string($con, $_POST['cp_pembayaran']);
		$telp_pembayaran = mysqli_real_escape_string($con, $_POST['telp_pembayaran']);
		$surat_jalan = mysqli_real_escape_string($con, $_POST['surat_jalan']);
		//$filex = mysqli_real_escape_string($con, $_POST['datafile']);
		//$acak = rand(1,99);
		//$file = strtolower($acak.$filex);
		//$today = date("Ymd");
		//if (!file_exists('../../upload/'.$today.'/')) {
			//mkdir('../../upload/'.$today.'/', 0777, true);
		//}
		//rename ('../../upload/'.$today.'/'.$filex.'', '../../upload/'.$today.'/'.$file.'');
		
		$intercityasal = mysqli_real_escape_string($con, $_POST['intercityasal']);
		$intercitytujuan = mysqli_real_escape_string($con, $_POST['intercitytujuan']);
		if($intercityasal=='Y' AND $intercitytujuan=='Y'){
			$kode = "002";
		}else{
			$kode = "001";
		}
		
		$hasil = mysqli_query($con, "SELECT SUBSTRING(airwaybill_kode, 7) as last  FROM pengiriman WHERE left(airwaybill_kode,3)='$kode' ORDER by id_pengiriman DESC LIMIT 1");
		$data = mysqli_fetch_assoc($hasil);
		$lastNoUrut = $data['last'];
		$nextNoUrut	= $lastNoUrut + 1;
		$airwaybillkode	= $kode."-".sprintf('%08s', $nextNoUrut);
		
		$key = "asdf$1011";
		$encrypt = "$_SESSION[id_userx]";
		$userid = base64_decrypt($encrypt,$key);
		
		if($_POST['outbound']=='N'){
			$inbound="Y";
		}else{
			$inbound="N";
		}
		
		mysqli_query($con, "insert into pengiriman (airwaybill_kode,
														tanggal,
														nama_pengirim,
														alamat_pengirim,
														hp_pengirim,
														perusahaan_pengirim,
														lokasi_asal,
														id_kustomer,
														nama_penerima,
														alamat_penerima,
														hp_penerima,
														perusahaan_penerima,
														lokasi_tujuan,
														loc_code,
														pid,
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
														user_client,
														id_cabang,
														nama_pembayaran,
														alamat_pembayaran,
														cp_pembayaran,
														telp_pembayaran,
														inbound,
														surat_jalan)
												values ('$airwaybillkode',
														'$tanggal',
														'$nama_pengirim',
														'$alamat_pengirim',
														'$hp_pengirim',
														'$perusahaan_pengirim',
														'$lokasi_asal',
														'$id_kustomer',
														'$nama_penerima',					
														'$alamat_penerima',
														'$hp_penerima',
														'$perusahaan_penerima',
														'$lokasi_tujuan',
														'$loc_code',
														'$pid',
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
														'$inbound',
														'$surat_jalan')");

		$last_id_pengiriman = mysqli_insert_id($con);

		include "../../../config/excel_reader2.php";

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
				$width				 = $data->val($i,8);
				$height				 = $data->val($i,9);
				$length				 = $data->val($i,10);
				$weight				 = $data->val($i,11);
				
				
				$part_desc = addslashes($part_n_desc);

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
															remark,
															width,
															height,
															length,
															weight																								
															)
													values ('$last_id_pengiriman',
															'$product',
															'$aprc',
															'$part_n',
															'$part_desc',
															'$rma_no',
															'$qty',
															'$remark',
															'$width',
															'$height',
															'$length',
															'$weight'
															)";					
														
						$hasil = mysqli_query($con,$query);
					
						
				if ($hasil) $sukses++;
				else $gagal++;
			}
		 'sukses = '.$sukses. '<br>';
			'gagal = '.$gagal. '<br>';

		}

		if($userid == 516 || $userid == 517 || $u['id_kustomer'] == 18){
			$last_id_pengiriman = mysqli_insert_id($con);

			$today = date("Ymd");
			$query = "SELECT max(kode_manifest) AS last FROM manifest WHERE kode_manifest LIKE '$today%'";
			$hasil = mysqli_query($con,$query);
			$data  = mysqli_fetch_assoc($hasil);
			$lastNoTransaksi = $data['last'];
			// baca nomor urut transaksi dari id transaksi terakhir
			$lastNoUrut = substr($lastNoTransaksi, 8, 3); 
			// nomor urut ditambah 1
			$nextNoUrut = $lastNoUrut + 1;
			// membuat format nomor transaksi berikutnya
			$nextNoTransaksi = $today.sprintf('%03s', $nextNoUrut);

			mysqli_query($con, "UPDATE pengiriman set pickup_request='31' WHERE id_pengiriman='".$last_id_pengiriman."'");
			$datetime = new DateTime('tomorrow');
			$tomorrow =  $datetime->format('Y-m-d');
			
			$tgl_jam = date("Y-m-d H:i:s"); 

			$format_awal = "Y-m-d 19:00:00";
			$batas_awal = date($format_awal);
			$format_ahir = "Y-m-d 23:59:59";
			$batas_ahir = date($format_ahir);

			if ($tgl_jam >= $batas_awal && $tgl_jam <= $batas_ahir) {
				$set_tgl = $tomorrow;
			}
			else{
				$set_tgl = $tgl_sekarang;
			}

			$cek = mysqli_query($con, "SELECT * FROM manifest WHERE tanggal='".$set_tgl."' AND manifest_pickup_by ='31' AND tipe_manifest ='pickup'");
			$ketemu	= mysqli_num_rows($cek);
			$r = mysqli_fetch_assoc($cek);
			if($ketemu==0){
					mysqli_query($con, "INSERT INTO manifest (kode_manifest,tanggal,manifest_pickup_by,tipe_manifest,created_date) VALUES ('$nextNoTransaksi','$set_tgl','31','pickup','$tgl_jam')");
					$id_manifest = mysqli_insert_id($con);
					// insert to manifest detail
					mysqli_query($con, "insert into manifest_detail (id_manifest,id_pengiriman) values ('$id_manifest','$last_id_pengiriman')");
					// update pengiriman
					//mysqli_query($con, "update pengiriman set status='3', berat_aktual='$berat_aktual', collie='$collie', pcs='$pcs', packing_kayu='$packing_kayu', transit='N' where id_pengiriman='$_POST[id]'");
			}	
			else{
				mysqli_query($con, "insert into manifest_detail (id_manifest,id_pengiriman) values ('$r[id_manifest]','$last_id_pengiriman')");
			}		
		}



		header('location:../../main.php?module='.$module);
	
	// update
	}elseif($module=='bookingin' AND $act=='update'){
		
		$tanggal_form = mysqli_real_escape_string($con, $_POST['tanggal']);
		$tanggal = jin_date_sql($tanggal_form);
		$nama_pengirim = mysqli_real_escape_string($con, $_POST['nama_pengirim']);
		$alamat_pengirim = mysqli_real_escape_string($con, $_POST['alamat_pengirim']);
		$hp_pengirim = mysqli_real_escape_string($con, $_POST['hp_pengirim']);
		$perusahaan_pengirim = mysqli_real_escape_string($con, $_POST['perusahaan_pengirim']);
		$lokasi_asal = mysqli_real_escape_string($con, $_POST['lokasi_asal']);
		
		$id_kustomer = mysqli_real_escape_string($con, $_POST['id_kustomer']);
		$nama_penerima = mysqli_real_escape_string($con, $_POST['nama_penerima']);
		$alamat_penerima = mysqli_real_escape_string($con, $_POST['alamat_penerima']);
		$hp_penerima = mysqli_real_escape_string($con, $_POST['hp_penerima']);
		$perusahaan_penerima = mysqli_real_escape_string($con, $_POST['perusahaan_penerima']);
		$loc_code = mysqli_real_escape_string($con, $_POST['loc_code']);
		if(isset($_POST['pid'])){
			$pid = mysqli_real_escape_string($con, $_POST['pid']);
		}
		$lokasi_tujuan = mysqli_real_escape_string($con, $_POST['lokasi_tujuan']);		
		$id_cabang = mysqli_real_escape_string($con, $_POST['id_cabang']);
		
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
		$surat_jalan = mysqli_real_escape_string($con, $_POST['surat_jalan']);
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
		$encrypt = "$_SESSION[id_userx]";
		$userid = base64_decrypt($encrypt,$key);

		//update awb jika lokasi tujuan kosong & ganti jabodetabek
		$intercityasal = mysqli_real_escape_string($con, $_POST['intercityasal']);
		$intercitytujuan = mysqli_real_escape_string($con, $_POST['intercitytujuan']);
		$tujuankosong =  mysqli_real_escape_string($con, $_POST['tujuankosong']);
		if($intercityasal=='Y' AND $intercitytujuan=='Y' AND $tujuankosong == 1){
			$kode = "002";
		}
		
		$hasil = mysqli_query($con, "SELECT SUBSTRING(airwaybill_kode, 7) as last  FROM pengiriman WHERE left(airwaybill_kode,3)='$kode' ORDER by id_pengiriman DESC LIMIT 1");
		$data = mysqli_fetch_assoc($hasil);
		$lastNoUrut = $data['last'];
		$nextNoUrut	= $lastNoUrut + 1;
		$airwaybillkode	= $kode."-".sprintf('%08s', $nextNoUrut);

		//ubah awb kalo lokasi tujuan kosong & ganti jabodetabek
		if($intercityasal=='Y' AND $intercitytujuan=='Y' AND $tujuankosong ==1){
			mysqli_query($con, "update pengiriman set airwaybill_kode='$airwaybillkode',
													tanggal='$tanggal',
													nama_pengirim='$nama_pengirim',
													alamat_pengirim='$alamat_pengirim',
													hp_pengirim='$hp_pengirim',
													perusahaan_pengirim='$perusahaan_pengirim',
													lokasi_asal='$lokasi_asal',
													id_kustomer='$id_kustomer',
													nama_penerima='$nama_penerima',
													alamat_penerima='$alamat_penerima',
													hp_penerima='$hp_penerima',
													perusahaan_penerima='$perusahaan_penerima',
													lokasi_tujuan='$lokasi_tujuan',
													loc_code='$loc_code',
													pid='$pid',
													service='$service',
													komoditi='$komoditi',
													jenis_barang='$jenis_barang',
													berat_barang='$berat_barang',
													ukuran_barang='$ukuran_barang',
													collie='$jumlah_collie',
													packing_kayu='$packing_kayu',
													asuransi='$asuransi',
													surat_jalan='$surat_jalan',
													nilai_barang='$nilai_barang',
													catatan='$catatan',
													biaya_beban='$biaya_beban',
													updated_last='$tgl_jam',
													client_update='$userid',
													nama_pembayaran='$nama_pembayaran',
													alamat_pembayaran='$alamat_pembayaran',
													cp_pembayaran='$cp_pembayaran',
													telp_pembayaran='$telp_pembayaran'
							WHERE id_pengiriman = '$_POST[id]' AND pickup='N'");
		}
		else{
			mysqli_query($con, "update pengiriman set tanggal='$tanggal',
													nama_pengirim='$nama_pengirim',
													alamat_pengirim='$alamat_pengirim',
													hp_pengirim='$hp_pengirim',
													perusahaan_pengirim='$perusahaan_pengirim',
													lokasi_asal='$lokasi_asal',
													id_kustomer='$id_kustomer',
													nama_penerima='$nama_penerima',
													alamat_penerima='$alamat_penerima',
													hp_penerima='$hp_penerima',
													perusahaan_penerima='$perusahaan_penerima',
													lokasi_tujuan='$lokasi_tujuan',
													loc_code='$loc_code',
													pid='$pid',
													service='$service',
													komoditi='$komoditi',
													jenis_barang='$jenis_barang',
													berat_barang='$berat_barang',
													ukuran_barang='$ukuran_barang',
													collie='$jumlah_collie',
													packing_kayu='$packing_kayu',
													asuransi='$asuransi',
													surat_jalan='$surat_jalan',
													nilai_barang='$nilai_barang',
													catatan='$catatan',
													biaya_beban='$biaya_beban',
													updated_last='$tgl_jam',
													client_update='$userid',
													nama_pembayaran='$nama_pembayaran',
													alamat_pembayaran='$alamat_pembayaran',
													cp_pembayaran='$cp_pembayaran',
													telp_pembayaran='$telp_pembayaran'
							WHERE id_pengiriman = '$_POST[id]' AND pickup='N'");
		}




		//isi catatan
		

		include "../../../config/excel_reader2.php";

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
				$width				 = $data->val($i,8);
				$height				 = $data->val($i,9);
				$length				 = $data->val($i,10);
				$weight				 = $data->val($i,11);
				
				$part_desc = addslashes($part_n_desc);
				
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
															remark,
															width,
															height,
															length,
															weight
															)
													values ('$_POST[id]',
															'$product',
															'$aprc',
															'$part_n',
															'$part_desc',
															'$rma_no',
															'$qty',
															'$remark',
															'$width',
															'$height',
															'$length',
															'$weight'
															)";					
														
						$hasil = mysqli_query($con,$query);
					
						
				if ($hasil) $sukses++;
				else $gagal++;
			}
		 'sukses = '.$sukses. '<br>';
			'gagal = '.$gagal. '<br>';

		}
		
		header('location:../../main.php?module='.$module);
		
	}
	elseif ($module=='bookingin' AND $act=='upload'){
		include "../../../config/excel_reader2.php";
		$tanggal_form = mysqli_real_escape_string($con, $_POST['tanggal']);
		$tanggal = jin_date_sql($tanggal_form);
		$nama_pengirim = mysqli_real_escape_string($con, $_POST['nama_pengirim']);
		$alamat_pengirim = mysqli_real_escape_string($con, $_POST['alamat_pengirim']);
		$hp_pengirim = mysqli_real_escape_string($con, $_POST['hp_pengirim']);
		$perusahaan_pengirim = mysqli_real_escape_string($con, $_POST['perusahaan_pengirim']);
		$lokasi_asal = mysqli_real_escape_string($con, $_POST['lokasi_asal']);
		
		$id_kustomer = mysqli_real_escape_string($con, $_POST['id_kustomer']);
		//$loc_code = mysqli_real_escape_string($con, $_POST['loc_code']);
		$id_cabang = mysqli_real_escape_string($con, $_POST['id_cabang']);
		
		$service = mysqli_real_escape_string($con, $_POST['service']);
		$komoditi = mysqli_real_escape_string($con, $_POST['komoditi']);
		$jenis_barang = mysqli_real_escape_string($con, $_POST['jenis_barang']);
		//$berat_barang = mysqli_real_escape_string($con, $_POST['berat_barang']);
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
		//$catatan = mysqli_real_escape_string($con, $_POST['catatan']);
		$biaya_beban = mysqli_real_escape_string($con, $_POST['biaya_beban']);
		
		$nama_pembayaran = mysqli_real_escape_string($con, $_POST['nama_pembayaran']);
		$alamat_pembayaran = mysqli_real_escape_string($con, $_POST['alamat_pembayaran']);
		$cp_pembayaran = mysqli_real_escape_string($con, $_POST['cp_pembayaran']);
		$telp_pembayaran = mysqli_real_escape_string($con, $_POST['telp_pembayaran']);
		

		if($_POST['outbound']=='N'){
			$inbound="Y";
		}else{
			$inbound="N";
		}
		
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
			$nama_penerima		 = $data->val($i, 1);
			$alamat_penerima	 = $data->val($i, 2);
			$lokasi_tujuan		 = $data->val($i, 3);
			$hp_penerima		 = $data->val($i, 4);
			$perusahaan_penerima = $data->val($i, 5); 
			$pickup_by			 = $data->val($i,6);
			$pickup_date		 = $data->val($i,7);
			$berat_barang		 = $data->val($i,8);
			$koli			 	 = $data->val($i,9);
			$volume			 	 = $data->val($i,10).'x'.$data->val($i,11).'x'.$data->val($i,12);
			$catatan		 	 = $data->val($i,13);
			$loc_code		 	 = $data->val($i,14);
			
			//kota tujuan
			$kotatujuan	= mysqli_query($con, "SELECT id_kota FROM kota WHERE nama_kota Like '%$lokasi_tujuan%'");
			$ketemukotatujuan = mysqli_num_rows($kotatujuan);
			$rtujuan = mysqli_fetch_assoc($kotatujuan);

			if($u['id_kustomer'] == 106){
				$ambildata = mysqli_query($con, "SELECT right(loc_code,8) AS last FROM pengiriman WHERE left(loc_code,4)='ASUS' and id_kustomer=106 ORDER BY id_pengiriman DESC limit 1");
				//echo "SELECT right(loc_code,8) AS last FROM pengiriman WHERE left(loc_code,4)='ASUS' and id_kustomer=106 ORDER BY id_pengiriman DESC limit 1";
				$dataasus = mysqli_fetch_assoc($ambildata);
				$lastNoUrutt = $dataasus['last'];
				$nextNoUrutt	= $lastNoUrutt + 1; 
			
			
				$loccode	= "ASUS".$nextNoUrutt;		
				
			}
			else {
				$loccode	= $loc_code;
				//$loc_code = mysqli_real_escape_string($con, $_POST['loc_code']);
			}
			
			if($ketemukotatujuan > 0){
				if($pickup_by != ""){
					$status = 2;
					$pickup = 'Y';
				}else{
					$status =1;
					$pickup = 'N';
				}

				$intercityasal = mysqli_real_escape_string($con, $_POST['intercityasal']);
				$intercitytujuan1 = mysqli_query($con, "select intercity from kota where id_kota='$rtujuan[id_kota]'");
				$rintercity = mysqli_fetch_assoc($intercitytujuan1);
				$intercitytujuan = $rintercity['intercity'];
				if($intercityasal=='Y' AND $intercitytujuan=='Y'){
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
														lokasi_asal,
														lokasi_tujuan,
														id_kustomer,
														nama_pengirim,
														alamat_pengirim,
														hp_pengirim,
														perusahaan_pengirim,
														loc_code,
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
														pickup_date,
														status,
														pickup,
														berat_barang,
														collie,
														ukuran_barang,
														user_client)
												values ('$airwaybillkode',
														'$tanggal',
														'$lokasi_asal',
														'$rtujuan[id_kota]',
														'$id_kustomer',
														'$nama_pengirim',
														'$alamat_pengirim',
														'$hp_pengirim',
														'$perusahaan_pengirim',
														'$loccode',
														'$nama_penerima',
														'$alamat_penerima',
														'$hp_penerima',
														'$perusahaan_penerima',
														'$service',
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
														'$pickup_date',
														'$status',
														'$pickup',
														'$berat_barang',
														'$koli',
														'$volume',
														'$id')";
					
													
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
	}
	elseif($module=='bookingin' and $act=='delete_catatan'){ 
		
		$id = "$_GET[id_catatan]";
		
		//mysqli_query($con, "delete from pengiriman where id_pengiriman='$id'");
		mysqli_query($con, "delete from asus_detail_catatan  where id_pengiriman='$id'");
		header('location:../../main.php?module='.$module);
	}

	elseif($module=='bookingin' and $act=='delete'){
		$key = "asdf$1011";
		$encrypt = "$_GET[id]"; 
		$id = base64_decrypt($encrypt,$key);
		//mysqli_query($con, "delete from pengiriman where id_pengiriman='$id'");
		mysqli_query($con, "update pengiriman set status='7' where id_pengiriman='$id'");
		header('location:../../main.php?module='.$module);
	}

	elseif($module=='bookingin' and $act=='upload-asus-all'){
		//$last_id_pengiriman = mysqli_insert_id($con);


		include "../../../config/excel_reader2.php";

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
				$width				 = $data->val($i,8);
				$height				 = $data->val($i,9);
				$length				 = $data->val($i,10);
				$weight				 = $data->val($i,11);
				$awb				 = $data->val($i,12);					
				
				$part_desc = addslashes($part_n_desc);

				$getid_pengiriman	= mysqli_query($con, "SELECT id_pengiriman FROM pengiriman WHERE airwaybill_kode Like '%$awb%'");
				//$ketemu_id = mysqli_num_rows($getid_pengiriman);
				$r_id = mysqli_fetch_assoc($getid_pengiriman);	
				
				//echo $awb.'='.$r_id['id_pengiriman']. '<br>';
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
															remark,
															width,
															height,
															length,
															weight																								
															)
													values ('$r_id[id_pengiriman]',
															'$product',
															'$aprc',
															'$part_n',
															'$part_desc',
															'$rma_no',
															'$qty',
															'$remark',
															'$width',
															'$height',
															'$length',
															'$weight'
															)";					
														
					$hasil = mysqli_query($con,$query);
					
						
				if ($hasil) $sukses++;
				else $gagal++;
			}
		 'sukses = '.$sukses. '<br>';
			'gagal = '.$gagal. '<br>';

		}
		header('location:../../main.php?module='.$module);

	}


}
?>