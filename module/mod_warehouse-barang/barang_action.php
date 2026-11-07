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

	$key = "asdf$1011";
	$encrypt = $_SESSION['id_user'];
	$id_user = base64_decrypt($encrypt,$key);
	
	
	
	$module	= !empty($_GET['module']) ? $_GET['module'] : '';
	$act	= !empty($_GET['act']) ? $_GET['act'] : '';
	
	$aksi="module/mod_warehouse-barang/barang_action.php";
	
	// data
	if($module=='barang' and $act=='data'){

		$table = 
<<<EOT
	(
	SELECT
		a.id,nama_barang, kode_barang,dengan_serial, x_namakustomer(pemilik_customer) as kustomer ,x_kustomercallname(pemilik_customer) as call_name , stok_awal_masuk, concat(stok_terkini,' ',satuan) as stok,a.created_date, x_namarak(a.id_rak) as rak,x_idgudang(a.id_rak) as idgudang, x_namacabang( (select idgudang) ) as namacabang, concat(expired_date,' ',expired_date_action) as expired,a.departement,x_namadepartement(a.departement) as nama_departement,stok_terkini,
			GROUP_CONCAT(r.nama_rak ORDER BY r.nama_rak SEPARATOR ', ') AS lokasi_rak


		from warehouse_barang a inner join kustomer b on a.pemilik_customer = b.id_kustomer 

		left JOIN rak_barang br ON a.id = br.id_barang

		left JOIN warehouse_rak r ON br.id_rak = r.id

		where a.aktif="Y" and b.warehouse = 'Y'
		GROUP BY a.id
	) temp
EOT;
		
		//$table = 'warehouse_barang';

		$primaryKey = 'id';

		$columns = array(
			array( 'db' => 'nama_barang', 'dt' => 0 ),
			array( 'db' => 'kode_barang',  'dt' => 1 ),
			array( 'db' => 'call_name',  'dt' => 2 ),
			array( 'db' => 'stok_terkini',  'dt' => null ),
			array(
				  'db'        => 'stok',
				  'dt'        => 3,
				  'formatter' => function( $d, $row ) {
					$aksi="module/mod_warehouse-barang/barang_action.php";
					$key = "asdf$1011";
					return '<td><center>

							
							'.$d.'
							
							</center></td>

					';
					
				  }
			),
			
			array( 'db' => 'created_date',  'dt' => 4 ),
			array( 'db' => 'rak',  'dt' => 5),
			array( 'db' => 'lokasi_rak',  'dt' => 6),
			array( 'db' => 'namacabang',  'dt' => 7),
			array( 'db' => 'expired',  'dt' => 8),
			array( 'db' => 'nama_departement',  'dt' => 9),
			array( 'db' => 'dengan_serial',  'dt' => 10),
			array(
				  'db'        => 'id',
				  'dt'        => 11,
				  'formatter' => function( $d, $row ) {
					$aksi="module/mod_warehouse-barang/barang_action.php";
					$key = "asdf$1011";
					return '<td><center>

							
							<a href="reportsnew/label_barang_landscape.php?id='.base64_encrypt($d,$key).'" class="btn btn-xs btn-warning" target=”_blank”   ><i class="fa fa-print"></i></a>
							<a href="module/mod_warehouse-barang/foto_barang/foto_barang.php?id='.base64_encrypt($d,$key).'" class="btn btn-xs btn-warning" target=”_blank”   ><i class="fa fa-photo"></i></a> 
							

							<button class="btn btn-xs btn-warning" onclick="window.location.href = \'?module=barang&act=edit&id='.base64_encrypt($d,$key).'\';"><i class="fa fa-pencil"></i></button> 

							'.($row['stok_terkini'] == 0 ? 
							            '<a href="module/mod_warehouse-barang/barang_action.php?module=barang&act=nonaktifkan&id_barang='.base64_encrypt($d,$key).'" class="btn btn-xs btn-danger" onclick="return confirm(\'Yakin ingin nonaktifkan data ini?\')">
							                <i class="fa fa-remove"></i> 
							            </a>' 
							        : '').'
							
							</center></td>

					';
					
				  }
			),

			
		);

		
		require( '../../config/ssp.class.php' );

		echo json_encode(
			SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns )
		);

	// Cek Kode
	}

	if($module=='barang-nonaktif' and $act=='data'){

		$table = 
<<<EOT
	(
	SELECT
		id,nama_barang, kode_barang,dengan_serial, x_namakustomer(pemilik_customer) as kustomer ,x_kustomercallname(pemilik_customer) as call_name , stok_awal_masuk, concat(stok_terkini,' ',satuan) as stok,a.created_date, x_namarak(id_rak) as rak,x_idgudang(id_rak) as idgudang, x_namacabang( (select idgudang) ) as namacabang, concat(expired_date,' ',expired_date_action) as expired,a.departement,x_namadepartement(a.departement) as nama_departement,stok_terkini

		from warehouse_barang a inner join kustomer b on a.pemilik_customer = b.id_kustomer where a.aktif="N" and b.warehouse = 'Y'
	) temp
EOT;
		
		//$table = 'warehouse_barang';

		$primaryKey = 'id';

		$columns = array(
			array( 'db' => 'nama_barang', 'dt' => 0 ),
			array( 'db' => 'kode_barang',  'dt' => 1 ),
			array( 'db' => 'call_name',  'dt' => 2 ),
			array( 'db' => 'stok_terkini',  'dt' => null ),
			array(
				  'db'        => 'stok',
				  'dt'        => 3,
				  'formatter' => function( $d, $row ) {
					$aksi="module/mod_warehouse-barang/barang_action.php";
					$key = "asdf$1011";
					return '<td><center>

							
							'.$d.'
							
							</center></td>

					';
					
				  }
			),
			
			array( 'db' => 'created_date',  'dt' => 4 ),
			array( 'db' => 'rak',  'dt' => 5),
			array( 'db' => 'namacabang',  'dt' => 6),
			array( 'db' => 'expired',  'dt' => 7),
			array( 'db' => 'nama_departement',  'dt' => 8),
			array( 'db' => 'dengan_serial',  'dt' => 9),
			array(
				  'db'        => 'id',
				  'dt'        => 10,
				  'formatter' => function( $d, $row ) {
					$aksi="module/mod_warehouse-barang/barang_action.php";
					$key = "asdf$1011";
					return '<td><center>

							
							

							 
							            <a href="module/mod_warehouse-barang/barang_action.php?module=barang&act=aktifkan&id_barang='.base64_encrypt($d,$key).'" class="btn btn-xs btn-primary" onclick="return confirm(\'Yakin ingin aktifkan data ini?\')">
							                <i class="fa fa-check"></i>aktifkan 
							            </a>
							       
							
							</center></td>

					';
					
				  }
			),

			
		);

		
		require( '../../config/ssp.class.php' );

		echo json_encode(
			SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns )
		);

	// Cek Kode
	}

	elseif($module=='barang' and $act=='checkcode'){
		if($_POST['kode_barang']){
			$kode = mysqli_real_escape_string($con,$_POST['kode_barang']);
			$query = mysqli_query($con, "SELECT kode_barang FROM warehouse_barang WHERE kode_barang='$kode'");
			$result = mysqli_num_rows($query);
			if( $result > 0 ){
				echo 'false';
			} else {
				echo 'true';
			}
		}
	
	// create
	}
	elseif($module=='barang' and $act=='checkimage'){
		if($_POST['foto']){
			$ekstensi_diperbolehkan	= array('png','jpg','jpeg');
			$foto = $_POST['foto']['name'];
			$x = explode('.', $foto);
			$ekstensi = _POST(end($x));
			$ukuran	= $_FILES['foto']['size'];
			$file_tmp = $_POST['foto']['tmp_name'];	
			$nama_file = $kode.'.jpg';
			if(in_array($ekstensi, $ekstensi_diperbolehkan) === false){
			
				echo 'false';
			} else {
				if($ukuran < 9044070){	
					echo 'true';
				}else{
					echo 'false';
				}
				
			}
		}
	
	// create
	}

	elseif($module=='barang' AND $act=='create'){
		
		 function xss($string)
        {
            return str_replace(
                            array('!','"','#','$','%','&',"'",'(',')','*','+',',','-','.','/',':',';','<','=','>','?','@','[','\\',']','^','_','{','|','}','~'),
                            array('\!','\"','\#','\$','\%','\&',"\'",'\(','\)','\*','\+','\,','\-','\.','\/','\:','\;','\<','\=','\>','\?','\@','\[','\\','\]','\^','\_','\{','\|','\}','\~'),
                            $string);
        }

		$nama = mysqli_real_escape_string($con, $_POST['nama']);
		//$nama = xss($nama);
		$kode = mysqli_real_escape_string($con, $_POST['kode_barang']);
		//$kode = xss($kode);
		$stok = mysqli_real_escape_string($con, $_POST['stok']);
		$id_kustomer_pemilik = mysqli_real_escape_string($con, $_POST['pemilik']);
		$id_rak = mysqli_real_escape_string($con, $_POST['rak']);
		//$foto = mysqli_real_escape_string($con, $_FILES['foto']);
		$satuan = mysqli_real_escape_string($con, $_POST['satuan']);
		//$serial_number = mysqli_real_escape_string($con, $_POST['serial_number']);
		$dengan_serial = mysqli_real_escape_string($con, $_POST['dengan_serial']);
		//$expired_date_form = mysqli_real_escape_string($con, $_POST['expired_date']);
		//$expired_date = jin_date_sql($expired_date_form);
		
		if (empty($_POST['expired_date'])){ 
		    $expired_date="NULL";
		}else{ 
		    //$action_expired = mysqli_real_escape_string($con, $_POST['action_expired']);
		    

		    $expired_date_form = mysqli_real_escape_string($con, $_POST['expired_date']);
			$expired_date_1 = jin_date_sql($expired_date_form);
			$expired_date="'$expired_date_1'";
		}
		


		if (empty($_POST['action_expired'])){ 
		    $action_expired="NULL";
		}else{ 
		    $action_expired = mysqli_real_escape_string($con, $_POST['action_expired']);
		    $action_expired="'$action_expired'";
		}

		if (empty($_POST['departement'])){ 
		    $departement="NULL";
		}else{ 
		    $departement = mysqli_real_escape_string($con, $_POST['departement']);
		    $departement="'$departement'";
		}
		
		

		$cek_sql = mysqli_query($con, "SELECT * FROM warehouse_barang WHERE kode_barang='$kode'");
		$cek = mysqli_num_rows($cek_sql);
		if($cek > 0){
			echo 'ERROR, Kode barang sudah ada';
		}
		else{
			if($_FILES['foto']){

				


				$ekstensi_diperbolehkan	= array('png','jpg','jpeg');
				$foto = $_FILES['foto']['name'];
				$x = explode('.', $foto);
				$ekstensi = strtolower(end($x));
				$ukuran	= $_FILES['foto']['size'];
				$file_tmp = $_FILES['foto']['tmp_name'];	
				$nama_file = $kode.'.jpg';
	 
				if(in_array($ekstensi, $ekstensi_diperbolehkan) === true){
					if($ukuran < 9044070){			
						if(move_uploaded_file($file_tmp, 'foto_barang/'.$kode.'.jpg')){					
							echo "BERHASIL UPLOAD";
							


							mysqli_query($con, "insert into warehouse_barang (kode_barang,nama_barang,pemilik_customer,stok_awal_masuk,stok_terkini,satuan,id_rak,foto_barang,created_by,dengan_serial,	expired_date,expired_date_action,departement)
												values (
														'$kode',
														'$nama','$id_kustomer_pemilik','$stok','$stok','$satuan','$id_rak','$nama_file','".$id_user."','$dengan_serial',$expired_date,$action_expired,$departement)");

							/*echo "insert into warehouse_barang (kode_barang,nama_barang,pemilik_customer,stok_awal_masuk,stok_terkini,satuan,id_rak,foto_barang,created_by,serial_number)
												values (
														'$kode',
														'$nama','$id_kustomer_pemilik','$stok','$stok','$satuan','$id_rak','$nama_file','".$id_user."','$serial_number')";*/
	
							header('location:../../main.php?module='.$module);
						}
						else{
							echo "gagal";
							mysqli_query($con, "insert into warehouse_barang (kode_barang,nama_barang,pemilik_customer,stok_awal_masuk,stok_terkini,satuan,id_rak,foto_barang,created_by,serial_number,	expired_date,expired_date_action,departement)
												values (
														'$kode',
														'$nama','$id_kustomer_pemilik','$stok','$stok','$satuan','$id_rak','$nama_file','".$id_user."','$serial_number',$expired_date,$action_expired,$departement)");

							/*echo "insert into warehouse_barang (kode_barang,nama_barang,pemilik_customer,stok_awal_masuk,stok_terkini,satuan,id_rak,foto_barang,created_by)
												values (
														'$kode',
														'$nama','$id_kustomer_pemilik','$stok','$stok','$satuan','$id_rak','$nama_file','".$id_user."','$serial_number')";*/
		
						header('location:../../main.php?module='.$module);

						}
					}else{
						echo 'UKURAN FILE TERLALU BESAR, max 1mb';
					}
				}else{
					echo 'EKSTENSI FILE YANG DI UPLOAD TIDAK DI PERBOLEHKAN';
				}

			}	
			
		}
	
	// update
	}elseif($module=='barang' AND $act=='update'){


		$id = $_POST['id'];
		
		//$active = mysqli_real_escape_string($con, $_POST['active']);

		$nama_barang = mysqli_real_escape_string($con, $_POST['nama']);
		if (!empty($_POST['serial_number'])){ 
			$serial_number = mysqli_real_escape_string($con, $_POST['serial_number']);
		}
		

		//$stok = mysqli_real_escape_string($con, $_POST['stok']);
		$satuan = mysqli_real_escape_string($con, $_POST['satuan']);
		$rak = mysqli_real_escape_string($con, $_POST['rak']);

		if (empty($_POST['expired_date'])){ 
		    $expired_date="NULL";
		}else{ 
		    //$action_expired = mysqli_real_escape_string($con, $_POST['action_expired']);
		    

		    $expired_date_form = mysqli_real_escape_string($con, $_POST['expired_date']);
			$expired_date_1 = jin_date_sql($expired_date_form);
			$expired_date="'$expired_date_1'";
		}
		

		if (empty($_POST['action_expired'])){ 
		    $action_expired="NULL";
		}else{ 
		    $action_expired = mysqli_real_escape_string($con, $_POST['action_expired']);
		    $action_expired="'$action_expired'";
		}

		if (empty($_POST['departement'])){ 
		    $departement="NULL";
		}else{ 
		    $departement = mysqli_real_escape_string($con, $_POST['departement']);
		    $departement="'$departement'";
		}
		mysqli_query($con, "DELETE FROM rak_barang WHERE id_barang = $_POST[id]");
		


		if (empty($_POST['stok'])){ 
		    mysqli_query($con, "update warehouse_barang set 

											nama_barang='$nama_barang',
										
											satuan='$satuan',
											id_rak='$rak',
											expired_date=$expired_date,
											expired_date_action=$action_expired,
											departement=$departement
											
							where id='$_POST[id]'");


		

		}else{ 
			$stok = mysqli_real_escape_string($con, $_POST['stok']);
		    mysqli_query($con, "update warehouse_barang set 

											nama_barang='$nama_barang',
											
											stok_awal_masuk='$stok',
											stok_terkini='$stok',
											satuan='$satuan',
											id_rak='$rak',
											expired_date=$expired_date,
											expired_date_action=$action_expired,
											departement=$departement
											
							where id='$_POST[id]'");
		}

		if (!empty($_POST['pilih_rak'])){ 
				
				$pilih_rak = $_POST['pilih_rak'];

				foreach($pilih_rak as $id_rak){
				    mysqli_query($con, "INSERT INTO rak_barang (id_barang, id_rak) VALUES ($_POST[id], $id_rak)");

				    echo "INSERT INTO rak_barang (id_barang, id_rak) VALUES ($_POST[id], $id_rak)";
				}

				//$rak_pilih = implode(',',$pilih_rak);
				//echo $rak_pilih;
		}

		if($_FILES['gambar']){			


		$select = mysqli_query($con, "SELECT kode_barang FROM warehouse_barang WHERE id='$id'");

		$r = mysqli_fetch_assoc($select);


			$ekstensi_diperbolehkan	= array('png','jpg','jpeg');
			$nama = $_FILES['gambar']['name'];
			$x = explode('.', $nama);
			$ekstensi = strtolower(end($x));
			$ukuran	= $_FILES['gambar']['size'];
			$file_tmp = $_FILES['gambar']['tmp_name'];	
 
			if(in_array($ekstensi, $ekstensi_diperbolehkan) === true){
				if($ukuran < 9044070){			
					if(move_uploaded_file($file_tmp, 'foto_barang/'.$r['kode_barang'].'.jpg')){					
						echo "BERHASIL UPLOAD";
					}
					else{
						echo "gagal";
					}
				}else{
					echo 'UKURAN FILE TERLALU BESAR, max 1mb';
				}
			}else{
				echo 'EKSTENSI FILE YANG DI UPLOAD TIDAK DI PERBOLEHKAN';
			}
		}
		

		
		
		header('Location: ' . $_SERVER['HTTP_REFERER']);
		
	}elseif($module=='barang' and $act=='delete'){
		$key = "asdf$1011";
		$encrypt = "$_GET[id]";
		$id = base64_decrypt($encrypt,$key);
		mysqli_query($con, "delete from warehouse_barang where id='$id'");
		echo "delete from warehouse_barang where id='$id'";
		header('location:../../main.php?module='.$module);
	}

	elseif($module=='barang' AND $act=='update-sn'){
		$mysqli->query("update warehouse_barang_serial set serial_number='$_POST[value]' where id='$_POST[pk]'");
	}

	elseif($module=='barang' and $act=='update_out_in'){
		$id_barang = $_POST['id_barang'];
		$out_in = $_POST['out_in'];

		$jumlah = $_POST['jumlah'];
		$dokumen = $_POST['dokumen'];
		$awb = $_POST['awb'];

		$select = mysqli_query($con, "SELECT stok_terkini,kode_barang FROM warehouse_barang WHERE id='$id_barang'");

		$r = mysqli_fetch_assoc($select);

		$stok_terkini = $r['stok_terkini'];

		if($out_in == 'out'){
			$stok_menjadi = $stok_terkini-$jumlah;
		}else{
			$stok_menjadi = $stok_terkini+$jumlah;
		}
		$id_user = $_POST['id_user'];

		$datetime = date("Ymdhis");
		$kode_history = $r['kode_barang'].$datetime;


		mysqli_query($con, "insert into warehouse_history (out_in,id_produk,jumlah,stok_sebelum,stok_menjadi,created_by,dokumen,awb,kode_history)
												values (
														'$out_in',
														'$id_barang','$jumlah','$stok_terkini','$stok_menjadi','$id_user','$dokumen','$awb','$kode_history')");

		/*mysqli_query($con, "delete from cabang where id_cabang='$id'");*/
		 mysqli_query($con, "update warehouse_barang set stok_terkini='$stok_menjadi' WHERE id='$id_barang'");
		header('location:../../main.php?module='.$module);
	}

	elseif($module=='barang' and $act=='update_out'){
		$id_barang = $_POST['id_barang'];
		//$in = $_POST['in'];

		$keterangan = $_POST['keterangan'];
		$jumlah = $_POST['jumlah'];
		$dokumen = $_POST['dokumen'];
		$requestor = $_POST['requestor'];

		$dengan_serial = $_POST['dengan_serial'];
		//$awb = $_POST['awb'];

		$select = mysqli_query($con, "SELECT stok_terkini,kode_barang FROM warehouse_barang WHERE id='$id_barang'");

		$r = mysqli_fetch_assoc($select);

		$stok_terkini = $r['stok_terkini'];

		
			$stok_menjadi = $stok_terkini-$jumlah;
		
		//$id_user = $id_user;

		$datetime = date("Ymdhis");
		$kode_history = $r['kode_barang'].$datetime;

		if($dengan_serial == 'Y'){
			$pilih_serial_number = $_POST['pilih_serial_number'];
			$sn = implode(',',$pilih_serial_number);

			$result = mysqli_query($con, "SELECT 
				serial_number
				

				from warehouse_barang_serial 
				

				where id in($sn) ");

			//selesai query
			$rows = '';
			while($r=  mysqli_fetch_assoc($result) ){
				$rows[] = $r['serial_number'];
			}

			$r_sn = implode(",",$rows);

			mysqli_query($con, "insert into warehouse_history (out_in,id_produk,jumlah,stok_sebelum,stok_menjadi,created_by,dokumen,catatan,kode_history, serial_number,requestor)
												values (
														'out',
														'$id_barang','$jumlah','$stok_terkini','$stok_menjadi','$id_user','$dokumen','$keterangan','$kode_history','$r_sn','$requestor' )");
			$last_id_pengiriman = mysqli_insert_id($con);

			//echo "update warehouse_barang_serial set aktif='N',keterangan_barang_keluar ='$last_id_pengiriman' WHERE id in ($sn) ";

			mysqli_query($con, "update warehouse_barang_serial set aktif='N',keterangan_barang_keluar ='$last_id_pengiriman' WHERE id in ($sn)");

		}else{
			mysqli_query($con, "insert into warehouse_history (out_in,id_produk,jumlah,stok_sebelum,stok_menjadi,created_by,dokumen,catatan,kode_history,requestor)
												values (
														'out',
														'$id_barang','$jumlah','$stok_terkini','$stok_menjadi','$id_user','$dokumen','$keterangan','$kode_history','$requestor')"); 
		}

	


		
		
		//echo "update warehouse_barang set stok_terkini='$stok_menjadi' WHERE id='$id_barang'";
		 mysqli_query($con, "update warehouse_barang set stok_terkini='$stok_menjadi' WHERE id='$id_barang'");


		header('Location: ' . $_SERVER['HTTP_REFERER']);
	}


	elseif($module=='barang' and $act=='update_out_berulang'){
		$id_barang = $_POST['id_barang'];
		//$in = $_POST['in'];

		$keterangan = $_POST['keterangan'];
		$jumlah = $_POST['jumlah'];
//		$dokumen = $_POST['dokumen'];
		$requestor = $_POST['requestor'];

		$pengulangan = $_POST['pengulangan'];
		//$awb = $_POST['awb'];

		$select = mysqli_query($con, "SELECT stok_terkini,kode_barang FROM warehouse_barang WHERE id='$id_barang'");

		$r = mysqli_fetch_assoc($select);

		$stok_terkini = $r['stok_terkini'];

		
		echo $stok_terkini.'<br>';
		
		//$id_user = $id_user;

		

		$stok_terbaru = $stok_terkini;
		
		for($i=1; $i <= $pengulangan; $i++){


		$stok_menjadi = $stok_terbaru-$jumlah;

		//echo $stok_menjadi.'</br>';

		$datetime = date("Ymdhis",time() + $i);

		$kode_history = $r['kode_barang'].$datetime;
			

			mysqli_query($con, "insert into warehouse_history (out_in,id_produk,jumlah,stok_sebelum,stok_menjadi,created_by,dokumen,catatan,kode_history,requestor)
												values (
														'out',
														'$id_barang','$jumlah','$stok_terbaru','$stok_menjadi','$id_user','-','$keterangan','$kode_history','$requestor')");
		$stok_terbaru = $stok_menjadi;

		}
		


		
		
		//echo "update warehouse_barang set stok_terkini='$stok_menjadi' WHERE id='$id_barang'";
		 mysqli_query($con, "update warehouse_barang set stok_terkini='$stok_menjadi' WHERE id='$id_barang'");


		header('Location: ' . $_SERVER['HTTP_REFERER']);
	}

	elseif($module=='barang' and $act=='update_in'){
		$id_barang = $_POST['id_barang'];
		//$in = $_POST['in'];

		$keterangan = $_POST['keterangan'];
		$jumlah = $_POST['jumlah'];
		$dokumen = $_POST['dokumen'];
		$dengan_serial = $_POST['dengan_serial'];
		$requestor = $_POST['requestor'];

		//$awb = $_POST['awb'];

		$select = mysqli_query($con, "SELECT stok_terkini,kode_barang FROM warehouse_barang WHERE id='$id_barang'");

		$r = mysqli_fetch_assoc($select);

		$stok_terkini = $r['stok_terkini'];

		
			$stok_menjadi = $stok_terkini+$jumlah;
		
		//$id_user = $id_user;

		$datetime = date("Ymdhis");
		$kode_history = $r['kode_barang'].$datetime;


		if($dengan_serial == 'Y'){
			
			$rows = '';

			for($i=1; $i<=$jumlah;$i++){

				$rows[] = $_POST['serial_number_in'][$i];
				$serial_number_in = $_POST['serial_number_in'][$i];

				mysqli_query($con, "insert into warehouse_barang_serial (id_produk,serial_number) values ($id_barang,'$serial_number_in')");

				echo "insert into warehouse_barang_serial (id_produk,serial_number) values ($id_barang,'$serial_number_in')". '<br><br>';
			}
			$r_sn = implode(",",$rows);

			//echo $r_sn;
			mysqli_query($con, "insert into warehouse_history (out_in,id_produk,jumlah,stok_sebelum,stok_menjadi,created_by,dokumen,catatan,kode_history, serial_number,requestor)
												values (
														'out',
														'$id_barang','$jumlah','$stok_terkini','$stok_menjadi','$id_user','$dokumen','$keterangan','$kode_history','$r_sn','$requestor' )");

		}else{
			mysqli_query($con, "insert into warehouse_history (out_in,id_produk,jumlah,stok_sebelum,stok_menjadi,created_by,dokumen,catatan,kode_history,requestor)
												values (
														'in',
														'$id_barang','$jumlah','$stok_terkini','$stok_menjadi','$id_user','$dokumen','$keterangan','$kode_history','$requestor')");
		}


		

		/*echo "insert into warehouse_history (out_in,id_produk,jumlah,stok_sebelum,stok_menjadi,created_by,dokumen,catatan,kode_history)
												values (
														'in',
														'$id_barang','$jumlah','$stok_terkini','$stok_menjadi','$id_user','$dokumen','$keterangan','$kode_history')";*/

		//echo "update warehouse_barang set stok_terkini='$stok_menjadi' WHERE id='$id_barang'";
		
		 mysqli_query($con, "update warehouse_barang set stok_terkini='$stok_menjadi' WHERE id='$id_barang'");
		header('Location: ' . $_SERVER['HTTP_REFERER']);
	}
	elseif($module=='barang' and $act=='edit-sn'){
		$key = "asdf$1011";
		$id = "$_POST[id]";
		//$id = base64_decrypt($encrypt,$key);
		//echo $id;


		$select = mysqli_query($con, "SELECT * FROM warehouse_barang_serial WHERE id_produk='$id' and aktif='Y'");
		//echo "SELECT * FROM warehouse_barang_serial WHERE id_produk='$id' and aktif='Y'";
		$cek = mysqli_num_rows($select);


		echo '
		<table class="table">
			<tr>
				<th>NO</th>
				<th>SERIAL NUMBER</th>
			</tr>
		';
		if($cek > 0){
			$i=1;
			while ($row = mysqli_fetch_assoc($select)) {
				echo 
				'
				<tr>
					<td>'.$i.'</td>
					<td>'.$row['serial_number']

					.' <a href="#" class="btn btn-xs btn-danger delete-sn"><i class="fa fa-trash"></i></a>'.'</td>
				</tr>
				';

				$i++;
			}
		}else{
			echo 
				'
				<tr>
					<td>data tidak ada</td>
					
				</tr>
				';
		}
		$i=0;
		

	}

	elseif ($module=='barang' AND $act=='scan_kodebarang'){
		$kode_barang = $_POST['kode'];

		$result = mysqli_query($con, "SELECT *,x_namakustomer(pemilik_customer) as nama_customer from warehouse_barang where kode_barang='$kode_barang' limit 1");

		$r = mysqli_fetch_assoc($result);

		$ketemu	= mysqli_num_rows($result);


		





		if($ketemu > 0){
			$list_user = mysqli_query($con, "SELECT username from user_client where id_kustomer=".$r['pemilik_customer']." and blocked != 'Y' ");

			while($r_user =  mysqli_fetch_assoc($list_user) ){
				$rows[] = $r_user;
			}
			
			echo json_encode(array("success"=>1,"message"=> "KODE BARANG ADA $kode_barang", "barang" => $r, "user" => $rows));

		}else{
			
			$succes = array("success"=>0,"message"=> " $kode_barang TIDAK ADA");
			echo json_encode($succes);
		}
	}


	elseif ($module=='barang' AND $act=='scan_kodebarang_berulang'){
		$kode_barang = $_POST['kode'];

		$result = mysqli_query($con, "SELECT *,x_namakustomer(pemilik_customer) as nama_customer from warehouse_barang where kode_barang='$kode_barang' limit 1");

		$r = mysqli_fetch_assoc($result);

		$ketemu	= mysqli_num_rows($result);

		if($ketemu > 0){

			$list_user = mysqli_query($con, "SELECT username from user_client where id_kustomer=".$r['pemilik_customer']." and blocked != 'Y' ");

			while($r_user =  mysqli_fetch_assoc($list_user) ){
				$rows[] = $r_user;
			}
			
			if($r['dengan_serial'] == 'Y'){
				$succes = array("success"=>0,"message"=> " $kode_barang Memiliki serial number, out berulang hanya untuk yang tidak memiliki serial number");
				echo json_encode($succes);
			}else{
				echo json_encode(array("success"=>1,"message"=> "KODE BARANG ADA $kode_barang", "barang" => $r, "user" => $rows));
			}

			

		}else{
			
			$succes = array("success"=>0,"message"=> " $kode_barang TIDAK ADA");
			echo json_encode($succes);
		}
	}


	elseif ($module=='barang' AND $act=='serial_number_result'){
		$id_barang = $_POST['id_barang'];

		$result = mysqli_query($con, "SELECT * from warehouse_barang_serial where id_produk=$id_barang and aktif='Y' ");

		//$r = mysqli_fetch_assoc($result);

		//$ketemu	= mysqli_num_rows($result);

		while($r=  mysqli_fetch_assoc($result) ){
			$rows[] = $r;
		}

		echo json_encode($rows);
	}

	elseif($module=='barang' AND $act=='nonaktifkan'){
		$key = "asdf$1011";
		$encrypt = $_GET['id_barang'];
		$id_barang = base64_decrypt($encrypt,$key);


		//echo "update warehouse_barang set aktif='N' where id=$id_barang";

		$mysqli->query("update warehouse_barang set aktif='N' where id=$id_barang");
		header('Location: ' . $_SERVER['HTTP_REFERER']);
	}
	elseif($module=='barang' AND $act=='aktifkan'){
		$key = "asdf$1011";
		$encrypt = $_GET['id_barang'];
		$id_barang = base64_decrypt($encrypt,$key);


		//echo "update warehouse_barang set aktif='N' where id=$id_barang";

		$mysqli->query("update warehouse_barang set aktif='Y' where id=$id_barang");
		header('Location: ' . $_SERVER['HTTP_REFERER']);
	}


	/*elseif($module=='barang' and $act=='input-sn-manual'){
		$data = [
		'2209SCG0E459',
		'2209SCG0E4A9',
		'2209SCG0E419',
		'2209SCG0DLL9',
		'2209SCG0DLM9',
		'2209SCG0DLN9',
		'2209SCG0DLP9',
		'2209SCG0DLQ9',
		'2209SCG0DLT9',
		'2209SCG0DLS9',
		'2209SCG0DLU9',
		'2209SCG0DLX9',
		'2209SCG0DLW9',
		'2209SCG0DLY9',
		'2209SCG0DLV9',
		'2209SCG0DLA9',
		'2209SCG0DL89',
		'2209SCG0DL79',
		'2209SCG0DLB9',
		'2209SCG0DUQ9',
		'2209SCG0DUS9',
		'2209SCG0DUP9',
		'2209SCG0DUR9',
		'2209SCG0DXP9',
		'2209SCG0DXM9',
		'2209SCG0DXQ9',
		'2209SCG0DXN9',
		'2209SCG0DXU9',
		'2209SCG0DXS9',
		'2209SCG0DXT9',
		'2209SCG0DXV9',
		'2209SCG0DXW9',
		'2209SCG0DXR9',
		'2209SCG0DXY9',
		'2209SCG0DXX9',
		'2216SCG0DU39',
		'2216SCG0DU59',
		'2216SCG0DU49',
		'2216SCG0DU29',
		'2216SCG0DPR9',
		'2216SCG0DPS9',
		'2216SCG0DPQ9',
		'2216SCG0DPP9',
		'2209SCG0DKQ9',
		'2209SCG0DKS9',
		'2209SCG0DKT9',
		'2209SCG0DKU9',
		'2216SCG0DR99',
		'2216SCG0DRZ9',
		'2216SCG0DR89',
		'2216SCG0DRA9',
		'2216SCG0DR69',
		'2216SCG0DR59',
		'2216SCG0DR09',
		'2216SCG0DR79',
		'2209SCG0DK79',
		'2209SCG0DKF9',
		'2209SCG0DKG9',
		'2209SCG0DKE9',
		'2209SCG0E049',
		'2209SCG0E0B9',
		'2209SCG0E099',
		'2209SCG0E0A9',
		'2209SCG0DZ69',
		'2209SCG0DZ79',
		'2209SCG0DZ89',
		'2209SCG0DZ59',
		'2209SCG0DKN9',
		'2209SCG0DKP9',
		'2209SCG0DKM9',
		'2209SCG0DKL9',
		'2209SCG0DL09',
		'2209SCG0DKW9',
		'2209SCG0DKX9',
		'2209SCG0DKV9'

		];

		foreach ($data as $key ) {
			$sql =  'insert into warehouse_barang_serial (id_produk,serial_number) values (758,"'.$key.'")';
			echo $sql.'<br>';

			$insert = mysqli_query($con,$sql);
		}

	}*/
}
?>