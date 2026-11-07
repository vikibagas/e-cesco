<?php
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

	$key = "asdf$1011";
	$encrypt = $_SESSION['id_userx'];
	$id_user = base64_decrypt($encrypt,$key);
	
	$useraktif = mysqli_query($con, 'select * from user_client where id_user="'.$id_user.'"');
	$u = mysqli_fetch_assoc($useraktif);
	
	$module	= !empty($_GET['module']) ? $_GET['module'] : '';
	$act	= !empty($_GET['act']) ? $_GET['act'] : '';
	
	$aksi="module/mod_warehouse-report/report_action.php";
	
	// data
	
	if($module=='barang' and $act=='update-expired-action'){
		//echo $key = "asdf$1011";
		mysqli_query($con,"update warehouse_barang set expired_date_action='$_POST[value]' where id='$_POST[pk]'");
		//echo "update warehouse_barang set expired_date_action='$_POST[value]' where id='$_POST[pk]'";
		
	}
	if($module=='barang' and $act=='update-expired-date'){
		//echo $key = "asdf$1011";
		mysqli_query($con,"update warehouse_barang set expired_date='$_POST[value]' where id='$_POST[pk]'");
		//echo "update warehouse_barang set expired_date_action='$_POST[value]' where id='$_POST[pk]'";
		
	}

	elseif($module=='report' and $act=='show-sn'){
		$key = "asdf$1011";
		$id = "$_POST[id]";
		//$id = base64_decrypt($encrypt,$key);
		//echo $id;

		$q = mysqli_query($con, "SELECT nama_barang FROM warehouse_barang WHERE id='$id'");
		//echo "SELECT * FROM warehouse_barang_serial WHERE id_produk='$id' and aktif='Y'";
		$r = mysqli_fetch_assoc($q);


		$select = mysqli_query($con, "SELECT * FROM warehouse_barang_serial WHERE id_produk='$id' and aktif='Y'");
		//echo "SELECT * FROM warehouse_barang_serial WHERE id_produk='$id' and aktif='Y'";
		$cek = mysqli_num_rows($select);


		echo '
		<table class="table">
			<tr>
				
				<th colspan="2">'.$r['nama_barang'].'</th>
			</tr>

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
					<td>'.$row['serial_number'].'</td>
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
	if($module=='warehouse-report' and $act=='nonaktifkan'){
		$id_barang = $_GET['id_barang'];
		mysqli_query($con,"update warehouse_barang set aktif='N' where id=".$id_barang."");
		header('Location: ' . $_SERVER['HTTP_REFERER']);
		//echo "update warehouse_barang set aktif='N' where id=".$id_barang."";
		
	}
	if($module=='warehouse-report' and $act=='aktifkan'){
		$id_barang = $_GET['id_barang'];
		mysqli_query($con,"update warehouse_barang set aktif='Y' where id=".$id_barang."");
		header('Location: ' . $_SERVER['HTTP_REFERER']);
		//echo "update warehouse_barang set aktif='N' where id=".$id_barang."";
		
	}
	elseif($module=='barang' and $act=='update_out'){
		$id_barang = $_POST['id_barang'];
		//$in = $_POST['in'];

		$keterangan = $_POST['keterangan'];
		$jumlah = $_POST['jumlah'];
		$dokumen = $_POST['dokumen'];

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

			mysqli_query($con, "insert into warehouse_history (out_in,id_produk,jumlah,stok_sebelum,stok_menjadi,created_by_cust,dokumen,catatan,kode_history, serial_number)
												values (
														'out',
														'$id_barang','$jumlah','$stok_terkini','$stok_menjadi','$id_user','$dokumen','$keterangan','$kode_history','$r_sn' )");
			$last_id_pengiriman = mysqli_insert_id($con);

			echo "update warehouse_barang_serial set aktif='N',keterangan_barang_keluar ='$last_id_pengiriman' WHERE id in ($sn) ";

			mysqli_query($con, "update warehouse_barang_serial set aktif='N',keterangan_barang_keluar ='$last_id_pengiriman' WHERE id in ($sn)");

		}else{
			mysqli_query($con, "insert into warehouse_history (out_in,id_produk,jumlah,stok_sebelum,stok_menjadi,created_by_cust,dokumen,catatan,kode_history)
												values (
														'out',
														'$id_barang','$jumlah','$stok_terkini','$stok_menjadi','$id_user','$dokumen','$keterangan','$kode_history')");
		}

	


		
		
		//echo "update warehouse_barang set stok_terkini='$stok_menjadi' WHERE id='$id_barang'";
		 mysqli_query($con, "update warehouse_barang set stok_terkini='$stok_menjadi' WHERE id='$id_barang'");


		header('Location: ' . $_SERVER['HTTP_REFERER']);
	}

	if($module=='barang' and $act=='nonaktif'){

		
		
			$table = 
<<<EOT
	(
	SELECT
		id,
		kode_barang,
		nama_barang,
		foto_barang,
		x_namadepartement(departement) as nama_departement
		FROM warehouse_barang  where pemilik_customer=$u[id_kustomer] and aktif='N'
	) temp
EOT;

		$primaryKey = 'id';

	
		$columns = array(
			array(
					'db'        => 'foto_barang',
					'dt'        => 0,
					'formatter' => function( $d, $row ) {
						return '<a href="#" onclick="open_photo(this);" class="zoomphoto" data-id="'.$row['kode_barang'].'"><img src="../../e-cesco/module/mod_warehouse-barang/foto_barang/'.$d.'" style="width:50px;height:50px;" loading="lazy"></a>';
					}
			),
			array( 'db' => 'id', 'dt' => null ),
			array( 'db' => 'nama_barang', 'dt' => 1 ),
			array( 'db' => 'kode_barang', 'dt' => 2 ),
			array( 'db' => 'nama_departement', 'dt' => 3 ),
			array(
					'db'        => 'id',
					'dt'        => 4,
					'formatter' => function( $d, $row ) {
						return '<a href="module/mod_warehouse-report/report_action.php?module=warehouse-report&act=aktifkan&id_barang='.$row['id'].'" class="btn btn-sm btn-success" onclick="return confirm(\'Yakin ingin mengaktifkan barang ini?\')">
							                🟢 aktifkan
							            </a>';
					}
			)
			
			

		);
		


		
		$sql_details = array(
			'user' => 'cesco_user',
			'pass' => 'c1l4ndak33032',
			'db'   => 'cesco_logistics',
			'host' => '127.0.0.1'
		);
		require( '../../../config/ssp.class.php' );

		echo json_encode(
			SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns )
		);
	

	}

	if($module=='pemakaian-rak' and $act=='data'){
		$id_kustomer	= $_GET['id_kustomer'];
		$gudang			= $_GET['gudang'];
		$table = 
<<<EOT
	(
	SELECT 
	    r.nama_rak,
	    GROUP_CONCAT(DISTINCT d.nama_departement ORDER BY d.nama_departement ASC SEPARATOR ', ') AS departements,
	    COUNT(DISTINCT w.departement) AS jumlah_departemen
	FROM warehouse_rak r
	LEFT JOIN rak_barang rb 
       ON r.id = rb.id_rak
	LEFT JOIN warehouse_barang w 
       ON rb.id_barang = w.id 
      AND w.aktif = 'Y' 
      AND w.stok_terkini > 0
	LEFT JOIN kustomer_departement d
       ON w.departement = d.id

	       where r.penyewa= $id_kustomer 
	GROUP BY r.nama_rak
	) temp
EOT;

//echo $table;

$primaryKey = 'id';
		$no=1;
		$columns = array(
			
			array(
				  'db'        => 'nama_rak',
				  'dt'        => 0,
				  'formatter' => function( $d, $row ) {
					
					
		            return strtoupper($d);
				  }
			),

			array(
				  'db'        => 'departements',
				  'dt'        => 1,
				  'formatter' => function( $d, $row ) {
					
					
		            if (!$d || trim($d) === '') {
			            return '<span style="color:#999;">KOSONG</span>'; 
			        }
			        return strtoupper($d);
				  }
			),
			
			array(
				  'db'        => 'jumlah_departemen',
				  'dt'        => 2,
				  'formatter' => function( $d, $row ) {

				  	if (!$d || empty($row['departements'])) {
			            return '<span style="color:#999;">KOSONG</span>';
			        }

				  	$colors = [
					    'GENERAL INSULIN' => '#f56954',
					    'GENERAL GLP-1' => '#00a65a',
					    'MEDICAL SND' => '#f39c12',
					    'IT' => '#00c0ef',
					    'CENTRAL ADMIN' => '#3c8dbc',
					    'P&O' => '#d2d6de',
					    'MAPA(PUBLIC AWARNESS)' => '#8e44ad'
					];

				  	if (!$d || empty($row['departements'])) {
		                return ''; // kalau kosong, return blank
		            }
					$bars = '';
						$persen = 100 / $d;
						$departemens = explode(',', $row['departements']);

						/*foreach ($departemens as $dep) {
						    $bars .= '
						        <div style="width:' . $persen . '%; background:#4caf50; color:black; text-align:center; padding:3px 0; float:left; border:1px solid #eee;">
						            ' . round($persen, 1) . '%
						        </div>
						    ';
						}*/

						foreach ($departemens as $dep) {
						    $dep = strtoupper(trim($dep)); // samakan formatnya
						    $color = isset($colors[$dep]) ? $colors[$dep] : '#4caf50'; // fallback kalau tidak ada
						    $bars .= '
						        <div style="width:' . $persen . '%; background:' . $color . '; color:white; text-align:center; padding:3px 0; float:left; border:1px solid #eee;">
						            ' . round($persen, 1) . '%
						        </div>
						    ';
						}

						// Bungkus dalam container
						return '
						<div style="width:100%; background:#eee; border-radius:4px; overflow:hidden; display:flex;">
						    ' . $bars . '
						</div>';
					
		         
				  }
			),
			
			

			
		


		);
		
		$sql_details = array(
			'user' => 'cesco_user',
			'pass' => 'c1l4ndak33032',
			'db'   => 'cesco_logistics',
			'host' => '127.0.0.1'
		);
		require( '../../../config/ssp.class.php' );

		echo json_encode(
			SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns )
		);
	}
}
?>