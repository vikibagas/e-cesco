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
	include "../../config/db_connect.php";
	
	$module	= !empty($_GET['module']) ? $_GET['module'] : '';
	$act	= !empty($_GET['act']) ? $_GET['act'] : '';
	
	$aksi="module/mod_booking-warehouse/booking-warehouse_action.php";
	
	$key = 'asdf$1011';
	$encrypt = $_SESSION['id_user'];
	$id = base64_decrypt($encrypt,$key);
	$useraktif = mysqli_query($con, 'select * from user where id_user="'.$id.'"');
	$u = mysqli_fetch_assoc($useraktif);

	// load data
	if($module=='booking-warehouse' and $act=='data'){
		
			$table = 
<<<EOT
	(
	SELECT
		a.airwaybill_kode, 
		CONCAT(DATE_FORMAT(a.created_date,"%Y-%m-%d"),' ', 
		
			ifnull( DATE_FORMAT( DATE_ADD(a.created_date, INTERVAL case when a.service=1 then CASE 
			WHEN airwaybill_kode LIKE '004-%' THEN 1
			WHEN airwaybill_kode LIKE '005-%' THEN 0 
			END  when a.service=2 then 1 else 

			CASE 
			WHEN airwaybill_kode LIKE '001-%' THEN ifnull(cr.sla_max,ifnull(o.sla_max,i.sla_max))
			WHEN airwaybill_kode LIKE '002-%' THEN 2 
			END

			 end DAY), "%Y-%m-%d" ) ,'0' ),' ',a.id_pengiriman
			) as created_date,

		x_namakustomer(a.id_kustomer) as nama_kustomer,
		case when id_kota_asal>0 then a.kota_asal else x_namakotabesar(a.lokasi_asal) end as lokasi_asal,
		
		case when a.service != 6 then x_namakotanew(a.id_kota_tujuan) else x_namanegara(a.lokasi_tujuan) end as lokasi_tujuan,
		
		x_service(a.service) as tipe_service,
		a.id_pengiriman
		FROM pengiriman a 
		left join sla_cross cr on cr.kota_asal =  a.id_kota_asal   AND cr.kota_tujuan=  a.id_kota_tujuan  AND cr.service = a.service	
	left join sla o on o.kota_asal =  if(x_inter(a.id_kota_asal)='Y',9999,0)   AND o.kota_tujuan=  a.id_kota_tujuan  AND o.service = a.service
	left join sla_in i on i.kota_asal =  a.id_kota_asal   AND i.kota_tujuan= if(x_inter(a.id_kota_tujuan)='Y',9999,0)  AND i.service = a.service

		WHERE id_kustomer != 0 and a.pickup='N' and  a.status = 1  and a.awb_warehouse='Y' group by a.id_pengiriman

	) temp
EOT;
		

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
						
						$pieces = explode(' ', $d);

						$booking_date = $pieces[0];
						
						//$sla =  $pieces[1];

						$d1 = strtotime($pieces[1]);
						$d2 = date('l',$d1);
						$dt3 = strtolower($d2);

						$id_pengiriman= $pieces[2];


						//$created_date='ok';

						if($dt3 == "saturday" )
						{
				            $sla = date('Y-m-d',strtotime($pieces[1].'+2 day'));
				        } 
				   		elseif($dt3 == "sunday")
						{
				            $sla = date('Y-m-d',strtotime($pieces[1].'+1 day'));
				        }else{
				        	$sla = date('Y-m-d',strtotime($pieces[1]));
				        }

						$date_now =  date("Y-m-d");

						if($sla != '1970-01-01'){
							
							if ($date_now > $sla){

								$created_date = $booking_date. ' - '.$sla . '<button class="btn btn-xs btn-danger" title="TERLAMBAT"><i class="fa fa-exclamation-circle"></i> </button>';
							}else{
								$created_date =  $booking_date . ' - '.$sla;
							}
						}else{
							$created_date =  $booking_date.  ' <button data-toggle="modal" data-target="#modal_sla_insert" data-id="'.$id_pengiriman.'" class="btn btn-xs btn-warning custom_sla_insert"><i class="fa fa-plus"></i> </button>';
						}

						return  $created_date;
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
					$aksi="module/mod_booking-warehouse/booking-warehouse_action.php";
					$key = "asdf$1011";
					return '<td><center>
					<button class="btn btn-xs btn-warning" onclick="window.location.href = \'?module=booking-warehouse&act=update&id='.base64_encrypt($d,$key).'\';"><i class="fa fa-pencil"></i></button> 
					
					 <button class="btn btn-xs btn-success" onClick="window.open(\'reportsnew/airwaybillnew.php?id='.base64_encrypt($d,$key).'\',\'mywindow\',\'menubar=1,resizable=1,width=800,height=600\');"><i class="fa fa-print"></i></button>

					 <button class="btn btn-xs btn-success" onClick="window.open(\'client/reports/airwaybill_asus.php?id='.$d.'\',\'mywindow\',\'menubar=1,resizable=1,width=800,height=600\');"><i class="fa fa-print"></i> ASUS</button>

					
					</center></td>';
				  }
			)

		);
		//button delete <button data-href="'.$aksi.'?module=booking-warehouse&act=delete&id='.base64_encrypt($d,$key).'" class="btn btn-xs btn-danger" type="button" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash"></i></button>

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
		
	//kustomer
	}elseif($module=='booking-warehouse' AND $act=='kustomer'){
		$id_kustomer = $_POST['id_kustomer'];
		$result = mysqli_query($con, "SELECT id_cabang, nama_kustomer,pic_utama, substring(alamat,1,52) as alamat1,substring(alamat,53,52) as alamat2 ,provinsi, kota,kecamatan,kelurahan, loc_code,pid, telp FROM kustomer WHERE id_kustomer = '$id_kustomer' ");
		$row = mysqli_fetch_assoc($result);
		echo json_encode($row);
	
	// pengirim
	
	}elseif($module=='booking-warehouse' AND $act=='alamat-gudang'){
		$id_gudang = $_POST['id'];
		$result = mysqli_query($con, "SELECT *, substring(alamat2,1,52) as alamat_awal,substring(alamat2,53,52) as alamat_lanjutan FROM cabang WHERE id_cabang = '$id_gudang' ");
		$row = mysqli_fetch_assoc($result);
		echo json_encode($row);
	
	// pengirim
	}

	elseif($module=='booking-warehouse' AND $act=='pengirim'){

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
	}elseif($module=='booking-warehouse' AND $act=='penerima'){
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
	}elseif($module=='booking-warehouse' and $act=='intercity'){
		$kota = $_POST['kota'];
		$result = mysqli_query($con, "SELECT intercity FROM regencies WHERE id = '$kota' ");
		$row = mysqli_fetch_assoc($result);
		echo json_encode($row);
	
	// create
	}elseif($module=='booking-warehouse' AND $act=='create'){
		$tanggal_form = mysqli_real_escape_string($con, $_POST['tanggal']);
		$tanggal = jin_date_sql($tanggal_form);
		$nama_pengirim = mysqli_real_escape_string($con, $_POST['nama_pengirim']);
		//$alamat_pengirim = mysqli_real_escape_string($con, $_POST['alamat_pengirim']);
		$hp_pengirim = mysqli_real_escape_string($con, $_POST['hp_pengirim']);
		$perusahaan_pengirim = mysqli_real_escape_string($con, $_POST['perusahaan_pengirim']);
		//$lokasi_asal = mysqli_real_escape_string($con, $_POST['lokasi_asal']);
		
		$id_kustomer = mysqli_real_escape_string($con, $_POST['id_kustomer']);
		$nama_penerima = mysqli_real_escape_string($con, $_POST['nama_penerima']);
		$alamat_penerima = mysqli_real_escape_string($con, $_POST['alamat_penerima1']);
		$alamat_penerima2 = mysqli_real_escape_string($con, $_POST['alamat_penerima2']);
		$hp_penerima = mysqli_real_escape_string($con, $_POST['hp_penerima']);
		$perusahaan_penerima = mysqli_real_escape_string($con, $_POST['perusahaan_penerima']);
		$loc_code = mysqli_real_escape_string($con, $_POST['loc_code']);
		//$lokasi_tujuan = mysqli_real_escape_string($con, $_POST['lokasi_tujuan']);		
		$id_cabang = mysqli_real_escape_string($con, $_POST['id_cabang']);
		$negara_tujuan = mysqli_real_escape_string($con, $_POST['negara_tujuan']);

		$service = mysqli_real_escape_string($con, $_POST['service']);
		$komoditi = mysqli_real_escape_string($con, $_POST['komoditi']);
		$jenis_barang = mysqli_real_escape_string($con, $_POST['jenis_barang']);
		$berat_barang = ceil(mysqli_real_escape_string($con, $_POST['berat_barang']));
		$pid = mysqli_real_escape_string($con, $_POST['pid']);
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
		$jumlah_collie = mysqli_real_escape_string($con, $_POST['jumlah_collie']);
		$packing_kayu = mysqli_real_escape_string($con, $_POST['packing_kayu']);
		$asuransi = mysqli_real_escape_string($con, $_POST['asuransi']);
		$surat_jalan = mysqli_real_escape_string($con, $_POST['surat_jalan']);
		$str = mysqli_real_escape_string($con, $_POST['nilai_barang']);

		$nilai_barang = preg_replace('/\D/','',$_POST['nilai_barang']);

		//$catatan = mysqli_real_escape_string($con, $_POST['catatan']);
		$biaya_beban = mysqli_real_escape_string($con, $_POST['biaya_beban']);
		
		$nama_pembayaran = mysqli_real_escape_string($con, $_POST['nama_pembayaran']);
		$alamat_pembayaran = mysqli_real_escape_string($con, $_POST['alamat_pembayaran']);
		$cp_pembayaran = mysqli_real_escape_string($con, $_POST['cp_pembayaran']);
		$telp_pembayaran = mysqli_real_escape_string($con, $_POST['telp_pembayaran']);
		
		$intercityasal = mysqli_real_escape_string($con, $_POST['intercityasal']);
		$intercitytujuan = mysqli_real_escape_string($con, $_POST['intercitytujuan']);

		$kotaasal = mysqli_real_escape_string($con, $_POST['kotaasal']);
		$kota = mysqli_real_escape_string($con, $_POST['kota']);

		$qasal = mysqli_query($con, "SELECT intercity FROM regencies WHERE id=$kotaasal");
		$dataasal = mysqli_fetch_assoc($qasal);

		$qtujuan = mysqli_query($con, "SELECT intercity FROM regencies WHERE id=$kota");
		$datatujuan = mysqli_fetch_assoc($qtujuan);


		
		if($dataasal['intercity']=='Y' AND $datatujuan['intercity']=='Y'){
			$kode = "005";
		}else{
			$kode = "004";
		}
		
		echo $dataasal['intercity'].$datatujuan['intercity'];
		//$kode = "001";
		$hasil = mysqli_query($con, "SELECT SUBSTRING(airwaybill_kode, 7) as last  FROM pengiriman WHERE left(airwaybill_kode,3)='$kode' ORDER by id_pengiriman DESC LIMIT 1");
		$data = mysqli_fetch_assoc($hasil);
		$lastNoUrut = $data['last'];
		$nextNoUrut	= $lastNoUrut + 1;
		echo $airwaybillkode	= $kode."-".sprintf('%08s', $nextNoUrut);
		//return false;
		
		$key = "asdf$1011";
		$encrypt = "$_SESSION[id_user]";
		$userid = base64_decrypt($encrypt,$key);
		
		

		$duapuluhft = mysqli_real_escape_string($con, $_POST['20ft']);
		$empatpuluhft = mysqli_real_escape_string($con, $_POST['40ft']);
		$empatpuluhfthc = mysqli_real_escape_string($con, $_POST['40ft_hc']);
		$lcl = mysqli_real_escape_string($con, $_POST['lcl']);
		
		$alamat_pengirim1 = mysqli_real_escape_string($con, $_POST['alamat_pengirim1']);
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
		
		

		
		$detail_barang = mysqli_real_escape_string($con, $_POST['detail_barang']);
		if($detail_barang == 'Y'){
			$jumlah_detailbarang = mysqli_real_escape_string($con, $_POST['jumlah_detailbarang']);
		}
		else{
			$jumlah_detailbarang = 0;
		}



		
		$insert_pengiriman = mysqli_query($con, "insert into pengiriman (
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
														pid,
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
														created_by,
														jumlah_detail_barang,
														awb_warehouse
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
														'$tujuan',
														
														'$provinsi',
														'$provinsi_text',
														'$kota',
														'$kota_text',
														'$kecamatan',
														'$kecamatan_text',
														'$kelurahan',
														'$loc_code',
														'$pid',
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
														'$userid',
														'$jumlah_detailbarang',
														'Y'
														
														)");

		$last_id_pengiriman = mysqli_insert_id($con);

		if($insert_pengiriman){
			//echo 'berhasil';

			$query_update_status_kiriman = "insert into update_status_kiriman (
															id_pengiriman,
															status_kiriman,
															created_date,
															status_by,
															created_by
																																							
															)
													values (
															'$last_id_pengiriman',
															'Pengiriman dibuat',
															now(),
															'$u[id_user]',
															'$u[id_user]',
															)";					
														
						$hasil = mysqli_query($con,$query_update_status_kiriman);

		}else{
			//echo 'gagal';
		}

		
		//echo 'jumlah collie '.$jumlah_collie.'<br><br>';
		
		//update warehouse history
		for($i=1; $i<=$jumlah_collie;$i++){
			$c_weight = $_POST['cweight'][$i];
			$c_width = $_POST['cwidth'][$i];
			$c_height = $_POST['cheight'][$i];
			$c_length = $_POST['clength'][$i];
			$c_total = $_POST['ctotal'][$i];
			
			$cttn = $_POST['catatanbaru'.$i];

			$barang_catatan = implode(",",$cttn);

			$update_warehouse_id_pengiriman = "update  warehouse_history SET		
																id_pengiriman ='$last_id_pengiriman'
																where
																id in ($barang_catatan)
								
																";		

			//echo $update_warehouse_id_pengiriman.'<br><br>';		
															
							$qc = mysqli_query($con,$update_warehouse_id_pengiriman);


			//mulai cari nama_barang
			$result = mysqli_query($con, "SELECT 
				concat(b.nama_barang,' ', ifnull(a.serial_number,'') ,'( ',a.jumlah,' ',b.satuan,' )') as nama_barang
				

				from warehouse_history a
				inner join warehouse_barang b on b.id=a.id_produk

				where a.id in($barang_catatan) ");

			//selesai query
			$rows = '';
			while($r=  mysqli_fetch_assoc($result) ){
				$rows[] = $r['nama_barang'];
			}

			$nama_barang_catatan = implode(",",$rows);

			//echo $nama_barang_catatan.'<br><br>';

			//echo $barang_catatan.'<br><br>';


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
															'$nama_barang_catatan',
															'$c_weight',
															'$c_width',
															'$c_length',
															'$c_height',
															'$c_total'
															
															)";		

															//echo $qcollie;			
														
						$qc = mysqli_query($con,$qcollie);
			}
			/*$qcolliet = "insert into detail_collie (id,
															id_pengiriman,
															catatan,
															berat,
															p,
															l,
															t,
															berat_real
																																							
															)
													values ('',
															'$last_id_pengiriman',
															'$nama_barang_catatan',
															'$c_weight',
															'$c_width',
															'$c_length',
															'$c_height',
															'$c_total'
															
															)";	*/
			//echo $qcolliet.'<br><br>';



		}


		
		
		echo "<script>window.location.href='https://cesco-logistics.com/e-cesco/main.php?module=booking-warehouse';</script>";
		header('location:../../main.php?module='.$module);
	
	
	}elseif($module=='booking-warehouse' AND $act=='update'){
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
		$provinsi_text = mysqli_real_escape_string($con, $_POST['provinsitujuan_text']);
		
		$kota = mysqli_real_escape_string($con, $_POST['kota']);
		$kota_text = mysqli_real_escape_string($con, $_POST['kota_text']);
		$kecamatan = mysqli_real_escape_string($con, $_POST['kecamatan']);
		$kecamatan_text = mysqli_real_escape_string($con, $_POST['kecamatan_text']);
		$kelurahan = mysqli_real_escape_string($con, $_POST['kelurahan']);
		
		

		
		$hp_pengirim = mysqli_real_escape_string($con, $_POST['hp_pengirim']);
		$perusahaan_pengirim = mysqli_real_escape_string($con, $_POST['perusahaan_pengirim']);
		
		
		$id_kustomer = mysqli_real_escape_string($con, $_POST['id_kustomer']);
		$nama_penerima = mysqli_real_escape_string($con, $_POST['nama_penerima']);
		
		$alamat_penerima1 = mysqli_real_escape_string($con, $_POST['alamat_penerima1']);
		$alamat_penerima2 = mysqli_real_escape_string($con, $_POST['alamat_penerima2']);

		$hp_penerima = mysqli_real_escape_string($con, $_POST['hp_penerima']);
		$perusahaan_penerima = mysqli_real_escape_string($con, $_POST['perusahaan_penerima']);
		$loc_code = mysqli_real_escape_string($con, $_POST['loc_code']);
		
		
		$id_cabang = mysqli_real_escape_string($con, $_POST['id_cabang']);
		

		$service = mysqli_real_escape_string($con, $_POST['service']);
		$komoditi = mysqli_real_escape_string($con, $_POST['komoditi']);
		$jenis_barang = mysqli_real_escape_string($con, $_POST['jenis_barang']);
		$berat_barang = ceil(mysqli_real_escape_string($con, $_POST['berat_barang']));
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
		$jumlah_collie = mysqli_real_escape_string($con, $_POST['jumlah_collie']);
		$packing_kayu = mysqli_real_escape_string($con, $_POST['packing_kayu']);
		$asuransi = mysqli_real_escape_string($con, $_POST['asuransi']);
		$surat_jalan = mysqli_real_escape_string($con, $_POST['surat_jalan']);
		$str = mysqli_real_escape_string($con, $_POST['nilai_barang']);
		$nilai_barang = preg_replace('#(\d),(\d)#','$1$2',$str);
		//$catatan = mysqli_real_escape_string($con, $_POST['catatan']);
		$biaya_beban = mysqli_real_escape_string($con, $_POST['biaya_beban']);
		
		$nama_pembayaran = mysqli_real_escape_string($con, $_POST['nama_pembayaran']);
		$alamat_pembayaran = mysqli_real_escape_string($con, $_POST['alamat_pembayaran']);
		$cp_pembayaran = mysqli_real_escape_string($con, $_POST['cp_pembayaran']);
		$telp_pembayaran = mysqli_real_escape_string($con, $_POST['telp_pembayaran']);
		
		//$filex = mysqli_real_escape_string($con, $_POST['datafile']);
		//$acak = rand(1,99);
		//$file = strtolower($acak.$filex);
		$today = date("Ymd");
		
		/*if (!file_exists('../../upload/'.$today.'/')) {
			mkdir('../../upload/'.$today.'/', 0777, true);
		}*/
		
		$key = "asdf$1011";
		$encrypt = "$_SESSION[id_user]";
		$userid = base64_decrypt($encrypt,$key);
		
		

		$duapuluhft = mysqli_real_escape_string($con, $_POST['20ft']);
		$empatpuluhft = mysqli_real_escape_string($con, $_POST['40ft']);
		$empatpuluhfthc = mysqli_real_escape_string($con, $_POST['40ft_hc']);
		$lcl = mysqli_real_escape_string($con, $_POST['lcl']);

		//awal detail collie
		$q = mysqli_query($con, "select * from detail_collie where id_pengiriman='".$_POST['id']."' order by koli_ke asc ");
		$ketemu = mysqli_num_rows($q);
		if($ketemu > 0){

			$dcollie = "delete FROM detail_collie 
													
													where 	id_pengiriman= '".$_POST['id']."'
														and koli_ke > '".$jumlah_collie."'
							
															";		

															//echo $qcollie;			
														
						$dc = mysqli_query($con,$dcollie);

			$update_warehouse_id_pengiriman_null = "update  warehouse_history SET		
																id_pengiriman = NULL
																where
																id_pengiriman='".$_POST['id']."'
								
																";		
			$qc_null = mysqli_query($con,$update_warehouse_id_pengiriman_null);


			for($i=1; $i<=$jumlah_collie;$i++){
				$c_weight = $_POST['cweight'][$i];
				$c_width = $_POST['cwidth'][$i];
				$c_height = $_POST['cheight'][$i];
				$c_length = $_POST['clength'][$i];
				$c_total = $_POST['ctotal'][$i];
				$cttn = $_POST['catatanbaru'.$i];
				
				//mulai update warhouse history
				$barang_catatan = implode(",",$cttn);

			$update_warehouse_id_pengiriman = "update  warehouse_history SET		
																id_pengiriman ='".$_POST['id']."'
																where
																id in ($barang_catatan)
								
																";



			echo $update_warehouse_id_pengiriman.'<br><br>';		
															
							$qc = mysqli_query($con,$update_warehouse_id_pengiriman);


			//mulai cari nama_barang
			$result = mysqli_query($con, "SELECT 
				concat(b.nama_barang,' ', ifnull(a.serial_number,'') ,'( ',a.jumlah,' ',b.satuan,' )') as nama_barang
				

				from warehouse_history a
				inner join warehouse_barang b on b.id=a.id_produk

				where a.id in($barang_catatan) ");

			//selesai query
			$rows = '';
			while($r=  mysqli_fetch_assoc($result) ){
				$rows[] = $r['nama_barang'];
			}

			$nama_barang_catatan = implode(",",$rows);

			//selesai update warehose history
				
				echo $nama_barang_catatan;

				$qcek = mysqli_query($con, "select * from detail_collie where id_pengiriman='".$_POST['id']."' and koli_ke = '".$i."' ");

				$ada = mysqli_num_rows($qcek);

				if($ada > 0){

				

					$qcollie = "update  detail_collie SET		
																catatan ='$nama_barang_catatan',
																berat ='$c_weight',
																p ='$c_width',
																l = '$c_length',
																t = '$c_height',
																berat_real = '$c_total'
														
														where 	id_pengiriman= '$_POST[id]'
															and koli_ke = '".$i."'
								
																";		

																//echo $qcollie;			
															
							$qc = mysqli_query($con,$qcollie);
				}else{
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
																'$_POST[id]',
																$i,
																'$nama_barang_catatan',
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

		}
		//akhir detail collie
		
		mysqli_query($con, "update pengiriman set 
													nama_pengirim='$nama_pengirim',
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
													

													biaya_beban='$biaya_beban',
													updated_last='$tgl_jam',
													updated_by='$userid',
													nama_pembayaran='$nama_pembayaran',
													alamat_pembayaran='$alamat_pembayaran',
													cp_pembayaran='$cp_pembayaran',
													telp_pembayaran='$telp_pembayaran'
													
							WHERE id_pengiriman = '$_POST[id]'");


		
		

		
		
		header('location:../../main.php?module='.$module);
		
	}
	
	elseif($module=='booking-warehouse' and $act=='delete'){
		$key = "asdf$1011";
		$encrypt = "$_GET[id]";
		$id = base64_decrypt($encrypt,$key);
		mysqli_query($con, "delete from pengiriman where id_pengiriman='$id'");
		header('location:../../main.php?module='.$module);
	}
	elseif($module='booking-warehouse' and $act == 'data-wilayah'){
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

	elseif ($module=='booking-warehouse' AND $act=='form-insert-sla')
	{	
		
		$id = $_POST['id'];
		
		$query = mysqli_query($con, "select *,x_service(service) as jenis_layanan,SUBSTRING_INDEX(ukuran_barang,'x',1) as length, SUBSTRING_INDEX(SUBSTRING_INDEX(ukuran_barang,'x',2),'x',-1) as width, SUBSTRING_INDEX(ukuran_barang,'x',-1) as height, DATE_FORMAT(created_date,'%Y%m%d') as folder from pengiriman where id_pengiriman='$id'");
			$r = mysqli_fetch_assoc($query);
		
		echo'
		<form action="'.$aksi.'?module=booking-warehouse&act=insert-sla" method="POST">
			
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
					<input type="number" name="sla" class="form-control" value="1" min="1" required/>
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
	elseif ($module=='booking-warehouse' AND $act=='insert-sla')
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
	elseif($module=='booking-warehouse' AND $act=='pembulatan'){



		 function bulatkan($angka)  
		 {  
		 $exp = explode('.',$angka);  
		 if(count($exp) == 2)  
		     {  
		     $p = "0.".$exp[1];  
		     if($p<0.05) {    
		     	$t=0;
		     }  
		     

		     else {$t=1;}  
		     $r=$exp[0] + $t;  
		     return $r;  
		     }  
		     else return $angka;  
		 }  
		   
		 $angka = $_POST['nomer'];  
		 
		 echo bulatkan($angka); 
	
	}
	elseif($module=='booking-warehouse' AND $act=='testing'){
		$clean = preg_replace('/\D/','',$_POST['nilai_barang']);
		echo  $clean;
	}

	elseif($module=='booking-warehouse' AND $act=='data-barang-keluar'){
		
		//$tanggal = date('Y-m-d');
		$tanggal = date('Y-m-d',strtotime(' -1 day'));

		$tanggal_akhir = date('Y-m-d');

		//$tanggal = '2023-12-07';

		//$id_barang = $_POST['id_barang'];

		$id_kustomer = $_POST['id'];

		//$id_kustomer=104;

		$result = mysqli_query($con, "SELECT a.id, a.serial_number, 
			concat(b.nama_barang,' ', ifnull(a.serial_number,'') ,'( ',a.jumlah,' ',b.satuan,' )') as nama_barang,
			b.pemilik_customer 

			from warehouse_history a
			inner join warehouse_barang b on b.id=a.id_produk

			where a.created_date between '$tanggal 00:00:01' and '$tanggal_akhir 23:59:59' and out_in='out' and pemilik_customer='$id_kustomer' and id_pengiriman is null ");

		//$r = mysqli_fetch_assoc($result);

		$ketemu	= mysqli_num_rows($result);
		if ($ketemu > 0){
			while($r=  mysqli_fetch_assoc($result) ){
				$rows[] = $r;
			}

			echo json_encode($rows);
		}else{
			$json = array();
			
				
				//$json[] = ;
			
			header("Content-Type: application/json");
			echo json_encode($json);
		}

		
		/*echo "SELECT a.id,a.jumlah, a.serial_number, b.nama_barang,b.pemilik_customer ,b.satuan

			from warehouse_history a
			inner join warehouse_barang b on b.id=a.id_produk

			where a.created_date between '$tanggal 00:00:01' and '$tanggal 23:59:59' and out_in='out' and pemilik_customer='$id_kustomer' ";*/
	}

	//upload
	elseif ($module=='booking-warehouse' AND $act=='upload'){
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
			$alamat_penerima	 = mysqli_real_escape_string($con, $data->val($i, 4));
			$provinsi_text		 = $data->val($i, 5);
			$provinsi			 = $data->val($i, 6);
			
			$kota_text 			 = $data->val($i, 7);
			$kota				 = $data->val($i, 8);
			
			$kecamatan_text		 = $data->val($i, 9);
			$kelurahan_text		 = $data->val($i, 10); 

			$hp_penerima		 = $data->val($i, 11);
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
					$kode = "005";				
				}else{
					$kode = "004";
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
														inbound,
														import,
														pickup_by,
														
														status,
														pickup,
														berat_barang,
														collie,
														ukuran_barang,
														awb_warehouse
														)
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
														'$inbound',
														'Y',
														'$pickup_by',
														
														'$status',
														'$pickup',
														'$berat_barang',
														'1',
														'$volume',
														'Y')";
					
													
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

		echo "<script>window.location.href='https://cesco-logistics.com/e-cesco/main.php?module=booking-warehouse';</script>";
		header('location:../../main.php?module='.$module);
		
	
	}
	//selesai upload


}
?>