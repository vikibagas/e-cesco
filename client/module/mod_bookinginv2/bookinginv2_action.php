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
	
	$aksi="module/mod_bookinginv2/bookinginv2_action.php";
	
	$key = 'asdf$1011';
	$encrypt = $_SESSION['id_userx'];
	$id = base64_decrypt($encrypt,$key);
	$useraktif = mysqli_query($con, 'select * from user_client where id_user="'.$id.'"');
	$u = mysqli_fetch_assoc($useraktif);

	// load data
	if($module=='bookinginv2' AND $act=='read'){
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

		$sql = "select id_pengiriman, id_cabang, tanggal, DATE_FORMAT(created_date,'%H:%i') as jam, airwaybill_kode, x_namakustomer(id_kustomer) as nama_kustomer, lokasi_asal, x_kodekota(lokasi_asal) as kode_kota_asal,if(id_kota_asal>0,kota_asal,x_namakotabesar(lokasi_asal)) as nama_kota_asal, lokasi_tujuan, x_kodekota(lokasi_tujuan) as kode_kota_tujuan, if(id_kota_tujuan>0,kota_tujuan,x_namakotabesar(lokasi_tujuan)) as nama_kota_tujuan, x_service(service) as tipe_service, pickup, perusahaan_penerima ";
		$sql.=" from pengiriman where pickup='N' and user_client='".$u['id_user']."' and status !=7 and status != 6";
		$query=mysqli_query($con, $sql);
		$totalData = mysqli_num_rows($query);
		$totalFiltered = $totalData;

		if(!empty($requestData['search']['value'])){
			$sql = "select id_pengiriman, id_cabang, tanggal, DATE_FORMAT(created_date,'%H:%i') as jam, airwaybill_kode, x_namakustomer(id_kustomer) as nama_kustomer, lokasi_asal, x_kodekota(lokasi_asal) as kode_kota_asal, if(id_kota_asal>0,kota_asal,x_namakotabesar(lokasi_asal)) as nama_kota_asal, lokasi_tujuan, x_kodekota(lokasi_tujuan) as kode_kota_tujuan, if(id_kota_tujuan>0,kota_tujuan,x_namakotabesar(lokasi_tujuan)) as nama_kota_tujuan, x_service(service) as tipe_service, pickup, perusahaan_penerima ";
			$sql.=" from pengiriman";
			$sql.=" where pickup='N' and user_client='".$u['id_user']."' and status !=7 and status != 6";
			$sql.=" and airwaybill_kode LIKE '".$requestData['search']['value']."%' ";
			$sql.=" or x_namakustomer(id_kustomer) LIKE '".$requestData['search']['value']."%' ";
			$query=mysqli_query($con, $sql);
			$totalFiltered = mysqli_num_rows($query);

			$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."   LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
			$query=mysqli_query($con, $sql);
	
		}else{	

			$sql = "select id_pengiriman, id_cabang, tanggal, DATE_FORMAT(created_date,'%H:%i') as jam, airwaybill_kode, x_namakustomer(id_kustomer) as nama_kustomer, lokasi_asal, x_kodekota(lokasi_asal) as kode_kota_asal, if(id_kota_asal>0,kota_asal,x_namakotabesar(lokasi_asal)) as nama_kota_asal, lokasi_tujuan, x_kodekota(lokasi_tujuan) as kode_kota_tujuan, if(id_kota_tujuan>0,kota_tujuan,x_namakotabesar(lokasi_tujuan)) as nama_kota_tujuan, x_service(service) as tipe_service, pickup, perusahaan_penerima ";
			$sql.=" from pengiriman where pickup='N' and user_client='".$u['id_user']."' and status != 7 and status != 6";
			$sql.=" order by ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']." LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
			$query=mysqli_query($con, $sql);
	
		}



		$data = array();
		while($row=mysqli_fetch_array($query)) {
			/*$key = "asdf$1011";
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

			$nestedData[] = '<td><center><button class="btn btn-xs btn-warning" onclick="window.location.href = \'?module=bookinginv2&act=update&id='.$id.'\';"><i class="fa fa-pencil"></i></button> 
			<button data-href="'.$aksi.'?module=bookinginv2&act=delete&id='.$id.'" class="btn btn-xs btn-danger" type="button" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-close"></i> </button>

			
			'. ($u[id_kustomer] == 106 ?  '
			<button class="btn btn-xs btn-success" onClick="window.open(\'reports/airwaybill_asus.php?id='.$row['id_pengiriman'].'\',\'mywindow\',\'menubar=1,resizable=1,width=800,height=600\');"><i class="fa fa-print"></i></button>' : '' ).'

			<button class="btn btn-xs btn-success" onClick="window.open(\'../reportsnew/airwaybillnew.php?id='.base64_encrypt($stringawal,$key).'\',\'mywindow\',\'menubar=1,resizable=1,width=800,height=600\');"><i class="fa fa-print"></i></button>

			</center></td>';*/
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

			$nestedData[] = '<td><center>
			<a href="main.php?module=bookinginv2&act=update&id='.$id.'"><button class="btn btn-xs btn-warning"><i class="fa fa-pencil"></i></button> </a>

			<button data-href="'.$aksi.'?module=bookinginv2&act=delete&id='.$id.'" class="btn btn-xs btn-danger" type="button" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-close"></i> </button>

			'. ($u['id_kustomer'] == 106 ?  '
			
			<a href="reports/airwaybill_asus.php?id='.$row['id_pengiriman'].'" target="_blank"><button class="btn btn-xs btn-success"><i class="fa fa-print"></i></button></a>' : '' ).'


			<a href="../reportsnew/airwaybillnew.php?id='.base64_encrypt($stringawal,$key).'" target="_blank"><button class="btn btn-xs btn-success"><i class="fa fa-print"></i></button></a>

			

			

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
	}elseif($module=='bookinginv2' AND $act=='kustomer'){
		$id_kustomer = $_POST['id_kustomer'];
		$result = mysqli_query($con, "SELECT id_cabang, nama_kustomer,pic_utama, substring(alamat,1,52) as alamat1,substring(alamat,53,52) as alamat2 ,provinsi, kota,kecamatan,kelurahan, loc_code, telp FROM kustomer WHERE id_kustomer = '$id_kustomer' ");
		$row = mysqli_fetch_assoc($result);
		echo json_encode($row);
	
	// pengirim
	}elseif($module=='bookinginv2' AND $act=='pengirim'){
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
	}elseif($module=='bookinginv2' AND $act=='penerima'){
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
	
	// pembayaran
	}elseif($module=='bookinginv2' AND $act=='pembayaran'){
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
	}elseif($module=='bookinginv2' and $act=='intercity'){
		$kota = $_POST['kota'];
		$result = mysqli_query($con, "SELECT intercity FROM regencies WHERE id = '$kota' ");
		$row = mysqli_fetch_assoc($result);
		echo json_encode($row);
	
	// create
	}elseif($module=='bookinginv2' AND $act=='create'){
		$tanggal_form = mysqli_real_escape_string($con, $_POST['tanggal']);
		$tanggal = jin_date_sql($tanggal_form);
		$nama_pengirim = mysqli_real_escape_string($con, $_POST['nama_pengirim']);
		$alamat_pengirim = mysqli_real_escape_string($con, $_POST['alamat_pengirim']);
		$hp_pengirim = mysqli_real_escape_string($con, $_POST['hp_pengirim']);
		$perusahaan_pengirim = mysqli_real_escape_string($con, $_POST['perusahaan_pengirim']);
		//$lokasi_asal = mysqli_real_escape_string($con, $_POST['lokasi_asal']);
		
		$id_kustomer = mysqli_real_escape_string($con, $_POST['id_kustomer']);
		$nama_penerima = mysqli_real_escape_string($con, $_POST['nama_penerima']);
		$alamat_penerima = mysqli_real_escape_string($con, $_POST['alamat_penerima']);
		$alamat_penerima2 = mysqli_real_escape_string($con, $_POST['alamat_penerima2']);
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
		//$lokasi_tujuan = mysqli_real_escape_string($con, $_POST['lokasi_tujuan']);		
		$id_cabang = mysqli_real_escape_string($con, $_POST['id_cabang']);
		
		$service = mysqli_real_escape_string($con, $_POST['service']);
		$komoditi = mysqli_real_escape_string($con, $_POST['komoditi']);
		$jenis_barang = mysqli_real_escape_string($con, $_POST['jenis_barang']);
		$berat_barang = ceil(mysqli_real_escape_string($con, $_POST['berat_barang']));
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
		$surat_jalan = mysqli_real_escape_string($con, $_POST['surat_jalan']);


		$alamat_pengirim1 = mysqli_real_escape_string($con, $_POST['alamat_pengirim']);
		$alamat_pengirim2 = mysqli_real_escape_string($con, $_POST['alamat_pengirim2']);
		$provinsiasal = mysqli_real_escape_string($con, $_POST['provinsiasal']);
		$provinsiasal_text = mysqli_real_escape_string($con, $_POST['provinsiasal_text']);
		
		$kotaasal_text = mysqli_real_escape_string($con, $_POST['kotaasal_text']);
		$kecamatanasal = mysqli_real_escape_string($con, $_POST['kecamatanasal']);
		$kecamatanasal_text = mysqli_real_escape_string($con, $_POST['kecamatanasal_text']);
		$kelurahanasal = mysqli_real_escape_string($con, $_POST['kelurahanasal']);

		$provinsi = mysqli_real_escape_string($con, $_POST['provinsi']);
		$provinsi_text = mysqli_real_escape_string($con, $_POST['provinsi_text']);
		
		$kota_text = mysqli_real_escape_string($con, $_POST['kota_text']);
		$kecamatan = mysqli_real_escape_string($con, $_POST['kecamatan']);
		$kecamatan_text = mysqli_real_escape_string($con, $_POST['kecamatan_text']);
		$kelurahan = mysqli_real_escape_string($con, $_POST['kelurahan']);
		

		/*$catatan1 = mysqli_real_escape_string($con, $_POST['catatan1']);
		$catatan2 = mysqli_real_escape_string($con, $_POST['catatan2']);
		$catatan3 = mysqli_real_escape_string($con, $_POST['catatan3']);
		$catatan4 = mysqli_real_escape_string($con, $_POST['catatan4']);
		$catatan5 = mysqli_real_escape_string($con, $_POST['catatan5']);
		$catatan6 = mysqli_real_escape_string($con, $_POST['catatan6']);
		$catatan7 = mysqli_real_escape_string($con, $_POST['catatan7']);
		$catatan8 = mysqli_real_escape_string($con, $_POST['catatan8']);
		$catatan9 = mysqli_real_escape_string($con, $_POST['catatan9']);
		$catatan10 = mysqli_real_escape_string($con, $_POST['catatan10']);*/


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

		$kotaasal = mysqli_real_escape_string($con, $_POST['kotaasal']);
		$kota = mysqli_real_escape_string($con, $_POST['kota']);

		$qasal = mysqli_query($con, "SELECT intercity FROM regencies WHERE id=$kotaasal");
		$dataasal = mysqli_fetch_assoc($qasal);

		$qtujuan = mysqli_query($con, "SELECT intercity FROM regencies WHERE id=$kota");
		$datatujuan = mysqli_fetch_assoc($qtujuan);

		if($dataasal['intercity']=='Y' AND $datatujuan['intercity']=='Y'){
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
		
		/*if($_POST['outbound']=='N'){
			$inbound="Y";
		}else{
			$inbound="N";
		}*/
		
		


		

		
		
		mysqli_query($con, "insert into pengiriman (
														airwaybill_kode,
														tanggal,
														nama_pengirim,
														alamat_pengirim,
														alamat_pengirim2,
														hp_pengirim,
														perusahaan_pengirim,
														lokasi_asal,
														id_provinsi_asal,
														provinsi_asal,
														id_kota_asal,
														kota_asal,
														id_kecamatan_asal,
														kecamatan_asal,
														
														kelurahan_asal,

														id_kustomer,
														nama_penerima,
														alamat_penerima,
														alamat_penerima2,
														hp_penerima,
														perusahaan_penerima,
														lokasi_tujuan,

														id_provinsi_tujuan,
														provinsi_tujuan,
														id_kota_tujuan,
														kota_tujuan,
														id_kecamatan_tujuan,
														kecamatan_tujuan,
														kelurahan_tujuan,
														
														
														loc_code,
														service,
														komoditi,
														status,
														jenis_barang,
														berat_barang,
														ukuran_barang,
														collie,
														packing_kayu,
														asuransi,
														surat_jalan,
														nilai_barang,
														
														biaya_beban,
														created_date,
														user_client
														)
												values (
														'$airwaybillkode',
														'$tanggal',
														'$nama_pengirim',
														'$alamat_pengirim1',
														'$alamat_pengirim2',
														'$hp_pengirim',
														'$perusahaan_pengirim',
														'0',
														'$provinsiasal',
														'$provinsiasal_text',
														'$kotaasal',
														'$kotaasal_text',
														'$kecamatanasal',
														'$kecamatanasal_text',
														'$kelurahanasal',
														'$id_kustomer',
														'$nama_penerima',					
														'$alamat_penerima',
														'$alamat_penerima2',
														'$hp_penerima',
														'$perusahaan_penerima',
														'0',
														
														'$provinsi',
														'$provinsi_text',
														'$kota',
														'$kota_text',
														'$kecamatan',
														'$kecamatan_text',
														'$kelurahan',
														'$loc_code',
														'$service',
														'$komoditi',
														'1',
														'$jenis_barang',
														'$berat_barang',
														'$ukuran_barang',
														'$jumlah_collie',
														'$packing_kayu',
														'$asuransi',
														'$surat_jalan',
														'$nilai_barang',
														
														'$biaya_beban',
														'$tgl_jam',
														'$userid'
														
														)");

		

		$last_id_pengiriman = mysqli_insert_id($con);
		
		
		
		
			
			for($i=1; $i<=$jumlah_collie;$i++){
				$c_weight = $_POST['cweight'][$i];
				$c_width = $_POST['cwidth'][$i];
				$c_height = $_POST['cheight'][$i];
				$c_length = $_POST['clength'][$i];
				$c_total = $_POST['ctotal'][$i];
				$cttn = $_POST['catatanbaru'][$i];

				if($c_total > 0){

							$qcollie = "insert into detail_collie (id,
																id_pengiriman,
																koli_ke,
																catatan,
																berat,
																p,
																l,
																t,
																berat_real
																																								
																)
														values ('',
																'$last_id_pengiriman',
																$i,
																'$cttn',
																'$c_weight',
																'$c_width',
																'$c_length',
																'$c_height',
																'$c_total'
																
																)";		

																//echo $qcollie;																	
							$qc = mysqli_query($con,$qcollie);
				}
			}
		
		if (isset($_FILES['uploadcatatan']['name'])){

			include "../../../config/excel_reader2.php";

			$data = new Spreadsheet_Excel_Reader($_FILES['uploadcatatan']['tmp_name']);
		



		
		
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

	//echo 'Apabila tidak berpindah klik <a href="https://cesco-logistics.com/e-cesco/client/main.php?module=bookinginv2">DISINI</a>';
		// echo "<script>window.location.href='https://cesco-logistics.com/e-cesco/client/main.php?module=bookinginv2';</script>";
		//exit;

		header('location:../../main.php?module='.$module);
	
	// update
	}elseif($module=='bookinginv2' AND $act=='update'){
		
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
	elseif ($module=='bookinginv2' AND $act=='upload'){
		include "../../../config/excel_reader2.php";
		$tanggal_form = mysqli_real_escape_string($con, $_POST['tanggal']);
		$tanggal = jin_date_sql($tanggal_form);
		$nama_pengirim = mysqli_real_escape_string($con, $_POST['nama_pengirim']);
		$alamat_pengirim1 = mysqli_real_escape_string($con, $_POST['alamat_pengirim1']);
		$alamat_pengirim2 = mysqli_real_escape_string($con, $_POST['alamat_pengirim2']);
		$hp_pengirim = mysqli_real_escape_string($con, $_POST['hp_pengirim']);
		$perusahaan_pengirim = mysqli_real_escape_string($con, $_POST['perusahaan_pengirim']);
		//$lokasi_asal = mysqli_real_escape_string($con, $_POST['lokasi_asal']);
		
		$id_kustomer = mysqli_real_escape_string($con, $_POST['id_kustomer']);
		//$loc_code = mysqli_real_escape_string($con, $_POST['loc_code']);
		$loc_code = !empty($_POST['loc_code']) ?  mysqli_real_escape_string($con, $_POST['loc_code']) : '';
		$id_cabang = mysqli_real_escape_string($con, $_POST['id_cabang']);
		
		//$service = mysqli_real_escape_string($con, $_POST['service']);
		//$komoditi = mysqli_real_escape_string($con, $_POST['komoditi']);
		//$jenis_barang = mysqli_real_escape_string($con, $_POST['jenis_barang']);
		//$berat_barang = mysqli_real_escape_string($con, $_POST['berat_barang']);
		//$ukuran_barang = mysqli_real_escape_string($con, $_POST['ukuran_barang']);
		$ukuran_barang = '1x1x1';
		/*if(isset($_POST['length'])){
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
		//$pid = mysqli_real_escape_string($con, $_POST['pid']);
		$pid = !empty($_POST['pid']) ?  mysqli_real_escape_string($con, $_POST['pid']) : '';
		
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

		
		
		$key = "asdf$1011";
		$encrypt = "$_SESSION[id_userx]";
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
			$p_d		 		 = $data->val($i, 1);
			$service			 = $data->val($i, 2);

			$nama_penerima		 = mysqli_real_escape_string($con, $data->val($i, 3));
			$alamat_penerima	 = mysqli_real_escape_string($con, $data->val($i, 4));
			$provinsi_text		 = $data->val($i, 5);
			$provinsi			 = $data->val($i, 6);
			
			$kota_text 			 = $data->val($i, 7);
			$kota				 = $data->val($i, 8);
			
			$kecamatan_text		 = $data->val($i, 9);
			$kelurahan_text		 = $data->val($i, 10); 

			$hp_penerima		 = mysqli_real_escape_string($con, $data->val($i, 11));
			$perusahaan_penerima = $data->val($i, 12); 			
			$berat_barang		 = $data->val($i,13);
			//$koli			 	 = $data->val($i,14);
			$p  				 = $data->val($i,14);
			$l  				 = $data->val($i,15);
			$t  				 = $data->val($i,16);
			$volume			 	 = $data->val($i,14).'x'.$data->val($i,15).'x'.$data->val($i,16);
			$jenis_barang		 	 = $data->val($i,17);
			$catatan		 	 = $data->val($i,18);
			$pickup_by		 	 = $data->val($i,19);
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
			$alamatpenerima1 = substr($alamat_penerima,0,52);
			$alamatpenerima2 = substr($alamat_penerima,53,52);



			if($p_d == "P"){
				$komoditi = 1;
			}else{
				$komoditi = 2;
			}

			if($nama_penerima != ""){
				/*if($pickup_by != ""){
					$status = 2;
					$pickup = 'Y';
				}else{*/
					$status =1;
					$pickup = 'N';
				//}

				$qasal = mysqli_query($con, "SELECT intercity FROM regencies WHERE id=$kotaasal");
				$dataasal = mysqli_fetch_assoc($qasal);

				$qtujuan = mysqli_query($con, "SELECT intercity FROM regencies WHERE id=$kota");
				$datatujuan = mysqli_fetch_assoc($qtujuan);


				if($dataasal['intercity']=='Y' AND $datatujuan['intercity']=='Y'){
					$kode = "002";				
				}else{
					$kode = "001";
				}

				$kecamatan_tujuan = mysqli_query($con, "SELECT id FROM districts WHERE name='$kecamatan_text' and regency_id = '$kota' limit 1");
				$ketemu = mysqli_num_rows($kecamatan_tujuan);
				if($ketemu > 0){
					$kec_tujuan = mysqli_fetch_assoc($kecamatan_tujuan);
				}else{
					$kecamatan_tujuan_awal = mysqli_query($con, "SELECT id FROM districts WHERE regency_id = '$kota' limit 1");
					$kec_tujuan = mysqli_fetch_assoc($kecamatan_tujuan_awal);
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
														id_kecamatan_tujuan,
														kecamatan_tujuan,
														kelurahan_tujuan,

														nama_penerima,
														alamat_penerima,
														alamat_penerima2,
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
														
														import,
														pickup_by,
														
														status,
														pickup,
														berat_barang,
														collie,
														ukuran_barang,
														user_client)

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
														'$kec_tujuan[id]',
														'$kecamatan_text',
														'$kelurahan_text',

														'$nama_penerima',
														'$alamatpenerima1',
														'$alamatpenerima2',
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
														
														'Y',
														'$pickup_by',
														
														'$status',
														'$pickup',
														'$berat_barang',
														'1',
														'$volume',
														'$id'
														)";
					
													
					$hasil = mysqli_query($con,$query);

					$last_id_pengiriman = mysqli_insert_id($con);

					$qcollie = "insert into detail_collie (id,
															id_pengiriman,
															koli_ke,
															catatan,
															berat,
															p,
															l,
															t,
															berat_real
																																							
															)
													values ('',
															'$last_id_pengiriman',
															'1',
															'$catatan',
															'$berat_barang',
															'$p',
															'$l',
															'$t',
															'$berat_barang'
															
															)";		

															//echo $qcollie;			
														
						$qc = mysqli_query($con,$qcollie);

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
	elseif($module=='bookinginv2' and $act=='delete_catatan'){ 
		
		$id = "$_GET[id_catatan]";
		
		//mysqli_query($con, "delete from pengiriman where id_pengiriman='$id'");
		mysqli_query($con, "delete from asus_detail_catatan  where id_pengiriman='$id'");
		header('location:../../main.php?module='.$module);
	}

	elseif($module=='bookinginv2' and $act=='delete'){
		$key = "asdf$1011";
		$encrypt = "$_GET[id]"; 
		$id = base64_decrypt($encrypt,$key);
		//mysqli_query($con, "delete from pengiriman where id_pengiriman='$id'");
		mysqli_query($con, "update pengiriman set status='7' where id_pengiriman='$id'");
		header('location:../../main.php?module='.$module);
	}

	elseif($module=='bookinginv2' and $act=='upload-asus-all'){
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

	elseif($module='bookinginv2' and $act == 'data-wilayah'){
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


}
?>