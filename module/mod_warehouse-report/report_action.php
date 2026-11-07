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
	if($module=='report-warehouse' and $act=='data'){
		$id_kustomer	= $_GET['id_kustomer'];
		$gudang			= $_GET['gudang'];
		$table = 
<<<EOT
	(
	select a.id,kode_barang,nama_barang,serial_number, pemilik_customer,x_namakustomer(pemilik_customer) as nama_kustomer,stok_awal_masuk, stok_terkini,satuan,a.created_date,expired_date,expired_date_action,
							x_namadepartement(departement) as nama_departement,dengan_serial,
							GROUP_CONCAT(r.nama_rak ORDER BY r.nama_rak SEPARATOR ', ') AS lokasi_rak
										
									FROM warehouse_barang a 

									left JOIN rak_barang br ON a.id = br.id_barang
									left JOIN warehouse_rak r ON br.id_rak = r.id
										
									where pemilik_customer='$id_kustomer' and aktif='Y'
										
									GROUP BY a.id
									order by nama_barang
	) temp
EOT;

//echo $table;

$primaryKey = 'id';
		$no=1;
		$columns = array(
			
			array(
				  'db'        => 'id',
				  'dt'        => 0,
				  'formatter' => function( $d, $row ) {
					
					static $rowNumber = 0;
		            $start = isset($_GET['start']) ? intval($_GET['start']) : 0;
		            return $start + (++$rowNumber);
				  }
			),
			array(
				  'db'        => 'kode_barang',
				  'dt'        => 1,
				  'formatter' => function( $d, $row ) {
					
					return '<img src="module/mod_warehouse-barang/foto_barang/'.$d.'.jpg" style="width:50px;height:50px;" loading="lazy">';
				  }
			),
			array( 'db' => 'nama_barang', 'dt'=> 2 ),
			array( 'db' => 'kode_barang', 'dt'=> 3 ),
			array(
				  'db'        => 'serial_number',
				  'dt'        => 4,
				  'formatter' => function( $d, $row ) {
					
					return '<a href="#">lihat serial number <a/>';
				  }
			),
			array(
				  'db'        => 'stok_terkini',
				  'dt'        => 5,
				  'formatter' => function( $d, $row ) {
					
					return $d.' '.$row['satuan'].' <a class="btn btn-sm btn-primary" href="main.php?module=warehouse-history&id_kustomer='.$row['pemilik_customer'].'&id_barang='.$row['id'].'" target="_blank"> history</a>';
				  }
			),
			array( 'db' => 'nama_departement', 'dt'=> 6 ),
			array(
				  'db'        => 'nama_departement',
				  'dt'        => 7,
				  'formatter' => function( $d, $row ) {
					
					return '-';
				  }
			),
			array(
				  'db'        => 'expired_date',
				  'dt'        => 8,
				  'formatter' => function( $d, $row ) {
					
					return $d.'-'.$row['expired_date_action'];
				  }
			),
			array( 'db' => 'lokasi_rak', 'dt'=> 9 ),
			
			array( 'db' => 'stok_awal_masuk', 'dt'=> 10 ),
			array( 'db' => 'created_date', 'dt'=> 11 ),
			array( 'db' => 'pemilik_customer', 'dt'=> null ),
			array( 'db' => 'expired_date_action', 'dt'=> null ),
			array( 'db' => 'satuan', 'dt'=> null ),


		);
		
		require( '../../config/ssp.class.php' );

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
    'formatter' => function($d, $row) {

        if (!$d || empty($row['departements'])) {
            return '<span style="color:#999;">KOSONG</span>';
        }

        // Samakan nama departemen dengan data database
        $colors = array(
            'GENERAL INSULIN'       => '#F56954',
            'GENERAL GLP-1'         => '#00A65A',
            'MEDICAL SND'           => '#F39C12',
            'SALES'                 => '#00BFA5',
            'MARKETING'             => '#FF5252',
            'CMR MEDICAL'           => '#4CAF50',
            'LCQ (LEGAL)'           => '#FFC107',
            'CE'                    => '#E91E63',
            'IT'                    => '#00BCD4',
            'CENTRAL ADMIN (GA)'    => '#3C8DBC',
            'FINANCE'               => '#9C27B0',
            'P&O'                   => '#795548',
            'SUPPLY CHAIN'          => '#FF9800',
            'MANAGEMENT'            => '#607D8B',
            'MAPA(PUBLIC AWARNESS)' => '#8E44AD',
            'RAK TIDAK TERPAKAI'    => '#B0BEC5'
        );

        $departemens = explode(',', $row['departements']);
        $departemens_valid = array();

        foreach ($departemens as $dep) {
            $dep = strtoupper(trim($dep));

            if ($dep !== '') {
                $departemens_valid[] = $dep;
            }
        }

        $jumlah = count($departemens_valid);

        if ($jumlah == 0) {
            return '<span style="color:#999;">KOSONG</span>';
        }

        $persen = 100 / $jumlah;
        $bars = '';

        foreach ($departemens_valid as $dep) {

            $color = isset($colors[$dep])
                ? $colors[$dep]
                : '#7f8c8d';

            $bars .= '
                <div style="
                    flex:1;
                    min-width:0;
                    background:' . $color . ';
                    color:#fff;
                    text-align:center;
                    padding:3px 0;
                    border:1px solid #fff;
                    box-sizing:border-box;
                ">
                    ' . round($persen, 1) . '%
                </div>
            ';
        }

        return '
            <div style="
                width:100%;
                background:#eee;
                border-radius:4px;
                overflow:hidden;
                display:flex;
            ">
                ' . $bars . '
            </div>
        ';
    }
),


			
			

			
		


		);
		
		require( '../../config/ssp.class.php' );

		echo json_encode(
			SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns )
		);
	}


	if($module=='barang' and $act=='delete'){
		echo $key = "asdf$1011";
		echo $encrypt = "$_GET[id]";
		$id = base64_decrypt($encrypt,$key);
		echo $id;
		//mysqli_query($con, "delete from warehouse_barang where id='$id'");
		//header('location:../../main.php?module='.$module);
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
	elseif($module=='history' and $act=='update'){
		$key = "asdf$1011";
		if($key == "asdf$1011"){
			$jumlah_menjadi = $_POST['jumlah_menjadi']; //contoh 14
			$jumlah_sebelum = $_POST['jumlah_sebelum']; // 7
			$out_in = $_POST['out_in']; //contoh 14


			if($out_in == "out"){
				$selisih_jumlah = $jumlah_sebelum-$jumlah_menjadi; //selisih 7

				//echo "jumlah sebelum = ".$jumlah_sebelum." dikurang ".$jumlah_menjadi."selisih jumlah = ". $selisih_jumlah . "<br><br>";
			}else{
				$selisih_jumlah = $jumlah_sebelum-$jumlah_menjadi; //selisih 7

				//echo "jumlah sebelum = ".$jumlah_sebelum." ditambah ".$jumlah_menjadi."selisih jumlah = ". $selisih_jumlah . "<br><br>";
			}
			
			
			
			$id_history = $_POST['id_history'];
			$id_produk = $_POST['id_produk'];

			$sql_id_history = mysqli_query($con, "SELECT id,jumlah,stok_sebelum,stok_menjadi FROM warehouse_history WHERE id = $id_history");
			$r = mysqli_fetch_assoc($sql_id_history);
			if($out_in == "out"){
				$stok_menjadi_update_awal = $r['stok_menjadi']+$selisih_jumlah;
			}else{
				$stok_menjadi_update_awal = $r['stok_menjadi']-$selisih_jumlah;
			}
			
			
			//echo 'stok menjadi ='.$stok_menjadi_update_awal.'<br><br>';
			//return false;

			 mysqli_query($con, "update warehouse_history set jumlah=$jumlah_menjadi, stok_menjadi=$stok_menjadi_update_awal where id =$id_history ");

			//echo '<br><br>';

			$select = mysqli_query($con, "SELECT id,jumlah,stok_sebelum,stok_menjadi,out_in FROM warehouse_history WHERE id > $id_history and id_produk = $id_produk ");
			$cek_history = mysqli_num_rows($select);

			if($cek_history > 0 ){	
				$stok_terkini = 0;
				while($row = mysqli_fetch_assoc($select)){

					if($out_in == 'out'){
						$stok_menjadi_update = $row['stok_menjadi']+$selisih_jumlah;
						$stok_sebelum_update = $row['stok_sebelum']+$selisih_jumlah;
					}else{
						$stok_menjadi_update = $row['stok_menjadi']-$selisih_jumlah;
						$stok_sebelum_update = $row['stok_sebelum']-$selisih_jumlah;
					}
					
					//echo 'stok sebelum'.$stok_sebelum_update.' dan stok_menjadi ='.$stok_menjadi_update.'<br>';

					  mysqli_query($con, "update warehouse_history set stok_sebelum= $stok_sebelum_update ,stok_menjadi= $stok_menjadi_update where id = ".$row['id']."  ");

					//echo '<br><br>';

					 $stok_terkini = $stok_menjadi_update;
				}
			}
			else{
				$stok_terkini = $stok_menjadi_update_awal;
			}


			 mysqli_query($con, "update warehouse_barang set stok_terkini= $stok_terkini where id = ".$id_produk."  ");

			//echo '<br><br>id history '.$id_history.'<br>';
			//echo 'selisih = '.$selisih_jumlah;

			header('Location: ' . $_SERVER['HTTP_REFERER']);
		}
		
	}


	elseif($module=='history' and $act=='update_stok_awal'){
		
			$stokawal_menjadi = $_POST['stok_awal_menjadi']; //contoh 14
			$stokawal_sebelum = $_POST['stok_awal_sebelum']; // 7
			$selisih_stokawal = $stokawal_sebelum-$stokawal_menjadi;
			
			$id_produk = $_POST['id_produk'];

			$awal = 1;

			//echo '<br><br>';
			//echo "stok awal sebelum = ".$stokawal_sebelum."<br>";
			//echo "stok awal menjadi = ".$stokawal_menjadi."<br>";
			//echo "selisih stok = ".$selisih_stokawal."<br><br>";

			$select = mysqli_query($con, "SELECT id,jumlah,stok_sebelum,stok_menjadi,out_in FROM warehouse_history WHERE  id_produk = $id_produk ");
			$cek_history = mysqli_num_rows($select);

			if($cek_history > 0 ){	
				$stok_terkini = 0;
				while($row = mysqli_fetch_assoc($select)){

					if($awal == 1){
						if($row['out_in'] == 'out'){
							$stok_menjadi_update = $stokawal_menjadi-$row['jumlah'];
							$stok_sebelum_update = $stokawal_menjadi;
						}else{
							$stok_menjadi_update = $stokawal_menjadi+$row['jumlah'];
							$stok_sebelum_update = $stokawal_menjadi;
						}
					}else{
						if($row['out_in'] == 'out'){
							$stok_menjadi_update = $row['stok_sebelum']-$selisih_stokawal-$row['jumlah'];;
							$stok_sebelum_update = $row['stok_sebelum']-$selisih_stokawal;
						}else{
							$stok_menjadi_update = $row['stok_sebelum']-$selisih_stokawal+$row['jumlah'];;
							$stok_sebelum_update = $row['stok_sebelum']-$selisih_stokawal;
						}
					}

					
					
					//echo 'stok sebelum'.$stok_sebelum_update.' dan stok_menjadi ='.$stok_menjadi_update.'<br>';

					  mysqli_query($con, "update warehouse_history set stok_sebelum= $stok_sebelum_update ,stok_menjadi= $stok_menjadi_update where id = ".$row['id']."  ");

					 // echo "update warehouse_history set stok_sebelum= $stok_sebelum_update ,stok_menjadi= $stok_menjadi_update where id = ".$row['id']."<br>";

					//echo '<br><br>';

					  echo "awal =" .$awal."<br>";

					$awal = 0;

					 $stok_terkini = $stok_menjadi_update;
				}
			}
			else{
				$stok_terkini = $stokawal_menjadi;
			}


			 mysqli_query($con, "update warehouse_barang set stok_terkini= $stok_terkini, stok_awal_masuk=$stokawal_menjadi where id = ".$id_produk."  ");

			

			header('Location: ' . $_SERVER['HTTP_REFERER']);
		
		
	}

	elseif($module=='history' and $act=='delete'){
		$key = "asdf$1011";
		$encrypt = $_GET['id'];
		$id_history = base64_decrypt($encrypt,$key);
		
		if($key == "asdf$1011"){
			
			

			$sql_id_history = mysqli_query($con, "SELECT id,jumlah,stok_sebelum,stok_menjadi,id_produk,out_in FROM warehouse_history WHERE id = $id_history");
			$r = mysqli_fetch_assoc($sql_id_history);

			$jumlah_yang_dihapus = $r['jumlah'];
			
			
			$id_produk = $r['id_produk'];

			$out_in = $r['out_in'];


		

			$select = mysqli_query($con, "SELECT id,jumlah,stok_sebelum,stok_menjadi FROM warehouse_history WHERE id > $id_history and id_produk = $id_produk ");
			$cek_history = mysqli_num_rows($select);

			if($cek_history > 0 ){

				$stok_terkini = 0;
				while($row = mysqli_fetch_assoc($select)){
					if($out_in == 'out'){
						$stok_menjadi_update = $row['stok_menjadi']+$jumlah_yang_dihapus;
						$stok_sebelum_update = $row['stok_sebelum']+$jumlah_yang_dihapus;
					}else{
						$stok_menjadi_update = $row['stok_menjadi']-$jumlah_yang_dihapus;
						$stok_sebelum_update = $row['stok_sebelum']-$jumlah_yang_dihapus;
					}
					
					

					//proses update 
					  mysqli_query($con, "update warehouse_history set stok_sebelum= $stok_sebelum_update ,stok_menjadi= $stok_menjadi_update where id = ".$row['id']."  ");

					
					 $stok_terkini = $stok_menjadi_update;

				}
				mysqli_query($con, "update warehouse_barang set stok_terkini= ".$stok_terkini." where id = ".$id_produk."  ");
			}else{
				if($out_in == 'out'){
					mysqli_query($con, "update warehouse_barang set stok_terkini= stok_terkini + ".$jumlah_yang_dihapus." where id = ".$id_produk."  ");
				}else{
					mysqli_query($con, "update warehouse_barang set stok_terkini= stok_terkini - ".$jumlah_yang_dihapus." where id = ".$id_produk."  ");
				}
				

			}

			
			
			
			mysqli_query($con, "delete from warehouse_history where id =$id_history ");

			
			header('Location: ' . $_SERVER['HTTP_REFERER']);
		}
		
	}

	elseif ($module=='history' AND $act=='cek_awb'){
		$no_awb = $_POST['awb'];

		$result = mysqli_query($con, "SELECT id_pengiriman,awb_warehouse from pengiriman where airwaybill_kode='".$no_awb."' and status != 7 ");

		//echo "SELECT id_pengiriman from pengiriman where airwaybill_number='".$no_awb."' and status != 7 ";

		$r = mysqli_fetch_assoc($result);

		$ketemu	= mysqli_num_rows($result);

		if($ketemu > 0){
			if($r['awb_warehouse'] == 'Y'){
				echo json_encode(array("success"=>1,"message"=> "awb ada", "awb" => $r));
			}else{
				echo json_encode(array("success"=>0,"message"=> "AWB ini bukan awb warehouse, silahkan ganti awb lain"));
			}
			
		}else{
			echo json_encode(array("success"=>0,"message"=> "AWB TIDAK ADA"));
		}
		

		

		
	}
	elseif($module=='history' and $act=='update_awb'){
		$id_awb = $_POST['id_awb'];
		echo $id_history = $_POST['id_history'];
		$tanpa_awb = isset($_POST['tanpa_awb']) ? $_POST['tanpa_awb'] : 'N';
		if($tanpa_awb == 'Y'){
			//echo 'Y';
			$dokumen_tanpa_awb = isset($_POST['dokumen_tanpa_awb']) ? $_POST['dokumen_tanpa_awb'] : 'N';
			mysqli_query($con, "update warehouse_history set awb='$dokumen_tanpa_awb' where id =$id_history ");

		}else{
			//echo 'N';
			mysqli_query($con, "update warehouse_history set id_pengiriman=$id_awb where id =$id_history ");
		}
		

		header('Location: ' . $_SERVER['HTTP_REFERER']);
	}
	if($module=='report-warehouse' and $act=='belum-tercatat'){
		$id_kustomer	= $_GET['id_kustomer'];				
		$table = 
<<<EOT
	(
		SELECT * , x_namabarang(id_produk) as nama_barang,
		x_user(created_by) as yang_membuat
			FROM warehouse_history 
			WHERE id_pengiriman IS NULL 
  			AND awb IS NULL AND out_in = "out" and created_date > "2025-01-01" order by created_date desc

	) temp
EOT;

//echo $table;

$primaryKey = 'id';
		$no=1;
		$columns = array(
			
			array( 'db' => 'created_date', 'dt'=> 0 ),						
			array( 'db' => 'nama_barang', 'dt'=> 1 ),			
			array( 'db' => 'jumlah', 'dt'=> 2 ),			
			array( 'db' => 'stok_sebelum', 'dt'=> 3 ),			
			array( 'db' => 'stok_menjadi', 'dt'=> 4 ),						
			array( 'db' => 'yang_membuat', 'dt'=> 5 ),						
			array( 'db' => 'catatan', 'dt'=> 6 ),
			array( 'db' => 'serial_number', 'dt'=> 7 ),			
			array(
				  'db'        => 'id',
				  'dt'        => 8,
				  'formatter' => function( $d, $row ) {
					
					return ' <a class="btn btn-sm btn-primary" href="main.php?module=warehouse-history&id_barang='.$row['id_produk'].'" target="_blank"> history</a>';
				  }
			),			
			array( 'db' => 'id_produk', 'dt'=> null ),

		);
		
		require( '../../config/ssp.class.php' );

		echo json_encode(
			SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns )
		);
	}


	
}
?>