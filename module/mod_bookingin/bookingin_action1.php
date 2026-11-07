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
	
	$aksi="module/mod_bookingin/bookingin_action.php";
	
	$key = 'asdf$1011';
	$encrypt = $_SESSION['id_user'];
	$id = base64_decrypt($encrypt,$key);
	$useraktif = mysqli_query($con, 'select * from user where id_user="'.$id.'"');
	$u = mysqli_fetch_assoc($useraktif);

	// load data
	if($module=='bookingin' and $act=='data'){
		if($u['level']=='admin'){
			$table = 
<<<EOT
	(
	SELECT
		a.airwaybill_kode, 
		a.created_date, 
		x_namakustomer(a.id_kustomer) as nama_kustomer,
		x_namakotabesar(a.lokasi_asal) as lokasi_asal,
		x_namakotabesar(a.lokasi_tujuan) as lokasi_tujuan,
		x_service(a.service) as tipe_service,
		a.id_pengiriman
		FROM pengiriman a WHERE a.pickup='N' and a.status='1' and a.status!='7'
	) temp
EOT;
		}elseif($u['level']=='operator'){
			$table = 
<<<EOT
	(
	SELECT
		a.airwaybill_kode, 
		a.created_date, 
		x_namakustomer(a.id_kustomer) as nama_kustomer,
		x_namakotabesar(a.lokasi_asal) as lokasi_asal,
		x_namakotabesar(a.lokasi_tujuan) as lokasi_tujuan,
		x_service(a.service) as tipe_service,
		a.id_pengiriman
		FROM pengiriman a WHERE a.pickup='N' AND a.id_cabang='39' and status='1' and a.status!='7' and x_intercity(a.lokasi_asal)='Y'
	) temp
EOT;
		}elseif($u['level']=='intercity'){
			$table = 
<<<EOT
	(
	SELECT
		a.airwaybill_kode, 
		a.created_date, 
		x_namakustomer(a.id_kustomer) as nama_kustomer,
		x_namakotabesar(a.lokasi_asal) as lokasi_asal,
		x_namakotabesar(a.lokasi_tujuan) as lokasi_tujuan,
		x_service(a.service) as tipe_service,
		a.id_pengiriman
		FROM pengiriman a WHERE a.pickup='N' AND a.id_cabang='39' and status='1' and a.status!='7' and x_intercity(a.lokasi_asal)='Y'
	) temp
EOT;
		}elseif($u['level']=='domex'){
			$table = 
<<<EOT
	(
	SELECT
		a.airwaybill_kode, 
		a.created_date, 
		x_namakustomer(a.id_kustomer) as nama_kustomer,
		x_namakotabesar(a.lokasi_asal) as lokasi_asal,
		x_namakotabesar(a.lokasi_tujuan) as lokasi_tujuan,
		x_service(a.service) as tipe_service,
		a.id_pengiriman
		FROM pengiriman a WHERE a.pickup='N' AND a.id_cabang='39' and status='1' and a.status!='7' and x_intercity(a.lokasi_asal)!='Y'
	) temp
EOT;
		}elseif($u['level']=='network'){
			$table = 
<<<EOT
	(
	SELECT
		a.airwaybill_kode, 
		a.created_date, 
		x_namakustomer(a.id_kustomer) as nama_kustomer,
		x_namakotabesar(a.lokasi_asal) as lokasi_asal,
		x_namakotabesar(a.lokasi_tujuan) as lokasi_tujuan,
		x_service(a.service) as tipe_service,
		a.id_pengiriman
		FROM pengiriman a WHERE a.pickup='N' AND (a.lokasi_asal in ($u[area])) and status='1' and a.status!='7'
	) temp
EOT;
		}

		$primaryKey = 'id_pengiriman';

		$columns = array(
			array(
					'db'		=> 'airwaybill_kode',
					'dt'		=> 0,
					'formatter'	=> function($d,$row){
						return '<td><center>'.$d.'</center></td>';
					}
			),
			array(
					'db'        => 'created_date',
					'dt'        => 1,
					'formatter' => function( $d, $row ) {
						return date( 'd-m-Y H:i', strtotime($d));
					}
			),
			array( 'db' => 'nama_kustomer', 'dt'=> 2 ),
			array(
				  'db'        => 'lokasi_asal',
				  'dt'        => 3,
				  'formatter' => function( $d, $row ) {
					if($d=='Jakarta Selatan' || $d=='Jakarta Timur' || $d=='Jakarta Barat' ||	$d=='Jakarta Utara' || $d=='Jakarta Pusat'){
						$lokasi_asal = 'Jakarta';
					}else{
						$lokasi_asal = $d;
					}
					return '<td>'.strtoupper($lokasi_asal).'</td>';
				  }
			),
			array(
				  'db'        => 'lokasi_tujuan',
				  'dt'        => 4,
				  'formatter' => function( $d, $row ) {
					if($d=='Jakarta Selatan' || $d=='Jakarta Timur' || $d=='Jakarta Barat' ||	$d=='Jakarta Utara' || $d=='Jakarta Pusat'){
						$lokasi_tujuan = 'Jakarta';
					}else{
						$lokasi_tujuan = $d;
					}
					return '<td>'.strtoupper($lokasi_tujuan).'</td>';
				  }
			),
			array('db'        => 'tipe_service', 'dt'=> 5 ),
			array(
				  'db'        => 'id_pengiriman',
				  'dt'        => 6,
				  'formatter' => function( $d, $row ) {
					$aksi="module/mod_bookingin/bookingin_action.php";
					$key = "asdf$1011";
					return '<td><center>
					<button class="btn btn-xs btn-warning" onclick="window.location.href = \'?module=bookingin&act=update&id='.base64_encrypt($d,$key).'\';"><i class="fa fa-pencil"></i></button> <button data-href="'.$aksi.'?module=bookingin&act=delete&id='.base64_encrypt($d,$key).'" class="btn btn-xs btn-danger" type="button" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash"></i></button> <button class="btn btn-xs btn-success" onClick="window.open(\'reports/airwaybill_inbound.php?id='.base64_encrypt($d,$key).'\',\'mywindow\',\'menubar=1,resizable=1,width=800,height=600\');"><i class="fa fa-print"></i></button>
					</center></td>';
				  }
			)

		);

		$sql_details = array(
			'user' => 'cescolog_root',
			'pass' => 'asdf$1011',
			'db'   => 'cescolog_courier',
			'host' => '127.0.0.1'
		);
		require( '../../config/ssp.class.php' );

		echo json_encode(
			SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns )
		);
		
	//kustomer
	}elseif($module=='bookingin' AND $act=='kustomer'){
		$id_kustomer = $_POST['id_kustomer'];
		$result = mysqli_query($con, "SELECT id_cabang, nama_kustomer, alamat, kota, loc_code, x_intercity(kota) as intercityasal FROM kustomer WHERE id_kustomer = '$id_kustomer' ");
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
		$loc_code = mysqli_real_escape_string($con, $_POST['loc_code']);
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
		
		$hasil = mysqli_query($con, "SELECT coalesce(max(right(airwaybill_kode,6)),149999) AS last FROM pengiriman WHERE left(airwaybill_kode,3)='$kode'");
		$data = mysqli_fetch_assoc($hasil);
		$lastNoUrut = $data['last'];
		$nextNoUrut	= $lastNoUrut + 1;
		$airwaybillkode	= $kode."-".sprintf('%08s', $nextNoUrut);
		
		$key = "asdf$1011";
		$encrypt = "$_SESSION[id_user]";
		$userid = base64_decrypt($encrypt,$key);
		
		if($_POST['outbound']=='N'){
			$inbound="N";
		}else{
			$inbound="Y";
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
														inbound)
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
														'$inbound')");
		
		header('location:../../main.php?module='.$module);
	
	//upload
	}elseif ($module=='bookingin' AND $act=='upload'){
		include "../../config/excel_reader2.php";
		$tanggal_form = mysqli_real_escape_string($con, $_POST['tanggal']);
		$tanggal = jin_date_sql($tanggal_form);
		$nama_pengirim = mysqli_real_escape_string($con, $_POST['nama_pengirim']);
		$alamat_pengirim = mysqli_real_escape_string($con, $_POST['alamat_pengirim']);
		$hp_pengirim = mysqli_real_escape_string($con, $_POST['hp_pengirim']);
		$perusahaan_pengirim = mysqli_real_escape_string($con, $_POST['perusahaan_pengirim']);
		$lokasi_asal = mysqli_real_escape_string($con, $_POST['lokasi_asal']);
		
		$id_kustomer = mysqli_real_escape_string($con, $_POST['id_kustomer']);
		$loc_code = mysqli_real_escape_string($con, $_POST['loc_code']);
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
			$nama_penerima		= $data->val($i, 1);
			$alamat_penerima	= $data->val($i, 2);
			$lokasi_tujuan		= $data->val($i, 3);
			$hp_penerima		= $data->val($i, 4);
			$perusahaan_penerima	= $data->val($i, 5); 
			$pickup_by			= $data->val($i,6);
			$pickup_date		= $data->val($i,7);
			
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
														perusahaan_pengirim,
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
														telp_pembayaran,
														inbound,
														import,
														pickup_by,
														pickup_date,
														status,
														pickup)
												values ('$airwaybillkode',
														'$tanggal',
														'$lokasi_asal',
														'$rtujuan[id_kota]',
														'$id_kustomer',
														'$nama_pengirim',
														'$alamat_pengirim',
														'$hp_pengirim',
														'$perusahaan_pengirim',
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
														'$telp_pembayaran',
														'$inbound',
														'Y',
														'$pickup_by',
														'$pickup_date',
														'2',
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
		
	// update
	}elseif($module=='bookingin' AND $act=='update'){
		$tanggal_form = mysqli_real_escape_string($con, $_POST['tanggal']);
		$tanggal = jin_date_sql($tanggal_form);
		$nama_pengirim = mysqli_real_escape_string($con, $_POST['nama_pengirim1']);
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
													updated_by='$userid',
													nama_pembayaran='$nama_pembayaran',
													alamat_pembayaran='$alamat_pembayaran',
													cp_pembayaran='$cp_pembayaran',
													telp_pembayaran='$telp_pembayaran'
							WHERE id_pengiriman = '$_POST[id]' AND pickup='N'");
		
		header('location:../../main.php?module='.$module);
		
	}elseif($module=='bookingin' and $act=='delete'){
		$key = "asdf$1011";
		$encrypt = "$_GET[id]";
		$id = base64_decrypt($encrypt,$key);
		mysqli_query($con, "delete from pengiriman where id_pengiriman='$id'");
		header('location:../../main.php?module='.$module);
	}

}
?>