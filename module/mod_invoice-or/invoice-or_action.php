<?php
if(!isset($_SESSION)){
    session_start();
}

if (empty($_SESSION['username']) AND empty($_SESSION['password'])){
	header('location:../application/index.php');
}
else{
	include "../../config/connection.php";
	include "../../config/db_connect.php";
	include "../../config/library.php";
	include "../../config/encrypt.php";
	
	$module	= !empty($_GET['module']) ? $_GET['module'] : '';
	$act	= !empty($_GET['act']) ? $_GET['act'] : '';
	
	$key = 'asdf$1011';
	$encrypt = $_SESSION['id_user'];
	$idUser = base64_decrypt($encrypt,$key);
	$useraktif = mysqli_query($con, 'select * from user where id_user="'.$idUser.'"');
	$u = mysqli_fetch_assoc($useraktif);
	$aksi="module/mod_finance-awb-customer-belum-tertagih/finance-awb-customer-belum-tertagih_action.php";
	
	$level = $u['level'];
	
	
	// data
	if($module=='invoice-or' and $act=='data'){

		
	$table = 
<<<EOT
	(
	SELECT
		a.airwaybill_kode, 
		a.tanggal, 
		x_namakustomer(a.id_kustomer) as kustomer,
		a.id_kustomer,
		a.pickup_date,
		a.courier_date,
		a.id_pengiriman,
		p.id_pembayaran,
		nama_pengirim,kota_asal,nama_penerima,kota_tujuan
		FROM pengiriman a 
		left outer join pembayaran_or p on p.airwaybill = a.airwaybill_kode 
		left outer join pembayaran_tunai r on r.id_pengiriman = a.id_pengiriman 
		where (a.id_invoice is null or a.id_invoice = 0) 
		
		and a.tanggal > DATE_SUB(now(), INTERVAL 48 MONTH)
		and a.biaya_beban = 4
	) temp	
EOT;
	

		$primaryKey = 'id_pengiriman';

		$columns = array(
			array('db' => 'id_pengiriman',
					'dt'		=> 0,
					'formatter'	=> function($d,$row){
						
						return '<input type="checkbox" form="printor" class="printor" name="idprintor[]" value="'.$row['airwaybill_kode'].'">';
					}
			),
			array( 'db' => 'airwaybill_kode', 'dt' => 1 ),			
			array( 'db' => 'kustomer', 'dt'=> 2 ),
			array('db' => 'nama_pengirim',
					'dt'		=> 3
			),
			array('db' => 'kota_asal',
					'dt'		=> 4
			),
			array('db' => 'nama_penerima',
					'dt'		=> 5
			),
			array('db' => 'kota_tujuan',
					'dt'		=> 6),
			array('db' => 'tanggal',
					'dt'		=> 7),
			array('db' => 'id_pembayaran',
					'dt'		=> 8,
					'formatter'	=> function($d,$row){
						if ($d != ''){
							$sudah = '';
						}else{
							$sudah = 'Belum cetak';
						}
						return $sudah;
					}
			),
			
			array(
				  'db'        => 'id_pengiriman',
				  'dt'        => 9,
				  'formatter' => function( $d, $row ) {
					$id = $d;
					
					$bayarStts = ($row['id_pembayaran'] == '') ? 'Bayar Cash' : 'Sudah Bayar Cash';
					$warnaBayar = ($row['id_pembayaran'] == '') ? 'btn-danger' : 'btn-success';
					
					return '<td><center>
							<button type="button" class="btn btn-xs '.$warnaBayar.' frm_or" data-toggle="modal" data-target="#modal_bayar" data-id="'.$id.'" data-id-bayar="'.$id.'" data-awb="'.$row['airwaybill_kode'].'"> <i class="fa fa-money"></i> '.$bayarStts.'</button>
							

							</center></td>';

					
				  }
			)

		);
		
		

		require( '../../config/ssp.class.php' );

		echo json_encode(
			SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns )
		);
	

	}
	elseif($module=='invoice-or' and $act=='tagihkan'){
		$tanggal		= $mysqli->real_escape_string(jin_date_sql($_GET['tanggal']));
		$id_pengiriman = $_GET['id_pengiriman'];
		$id_kustomer = $_GET['id_kustomer'];
		
		
		$query = mysqli_query($con, "select id_invoice from invoice where id_kustomer = $id_kustomer
				and status <> 'final' and date_format(tanggal,'%Y-%m') = date_format(now(),'%Y-%m')
				order by id_invoice desc limit 1");
		$r = mysqli_fetch_assoc($query);
		
		$id_invoice = $r['id_invoice'];
		if ($id_invoice == ''){
			
			$mysqli->query("INSERT into invoice (tanggal,id_kustomer,komoditi,bank,due_date,judul_awal,method,ppn,status,lunas,id_user,created_date) values(now(),'$id_kustomer','1,2','1',now(),'Judul Invoice',1,1,'draft','N',$idUser,now())");
			$id_invoice = $mysqli->insert_id;
				
		}
		
		$mysqli->query("UPDATE pengiriman set id_invoice=$id_invoice WHERE id_pengiriman= $id_pengiriman");
			
		$_SESSION['pesan'] = 'success';
		$id	= base64_encrypt($id_invoice,$key);
		
		header('location:../../main.php?module=invoice-customer&act=update&id='.$id);
		
	}elseif($module=='invoice-or' and $act=='detail-bayar'){
		$id_pengiriman = $_POST['id_pengiriman'];
		
		$query = mysqli_query($con, "select * from pembayaran_tunai where id_pengiriman = $id_pengiriman");
		$r = mysqli_fetch_assoc($query);
		
		echo json_encode($r);
		
	}elseif($module=='invoice-or' and $act=='bayar'){	
		
		//print_r($_POST);
		//die();
		
		$txtNo = $_POST['txtNo'];
		$txtDate = $_POST['txtDate'];
		$txtReceiveFrom = $_POST['txtReceiveFrom'];
		$txtAwb = $_POST['txtAwb'];
		$txtBayar = $_POST['txtBayar'];
		$txtKet = $_POST['txtKet'];
		
		$id_bayar = $_POST['id_bayar'];
		$tanggal = $mysqli->real_escape_string(jin_date_sql($_POST['tanggal']));
		$nilai = $_POST['nilai'];
		$penerima = $_POST['penerima'];
		
		foreach ($txtAwb as $key => $awb){

			$query = mysqli_query($con, "select count(1) as cnt from pembayaran_or where airwaybill = '".$awb."'");
			$r = mysqli_fetch_assoc($query);
			
			$cek = $r['cnt'];
			
			if ($cek > 0){
				$mysqli->query("UPDATE pembayaran_or set cs_number='".$txtNo."', tanggal_cetak = '".$txtDate."',
				diterima_dari = '".$txtReceiveFrom."', jumlah_bayar = $txtBayar[$key], keterangan_bayar = '".$txtKet[$key]."', update_dtm = now(), update_by = $idUser			
				WHERE airwaybill= '".$awb."'");
				
			}else{
				$mysqli->query("INSERT into pembayaran_or (cs_number,tanggal_cetak,diterima_dari,airwaybill,jumlah_bayar,keterangan_bayar,input_dtm,input_by) 
						values('".$txtNo."','".$txtDate."','".$txtReceiveFrom."','".$awb."',$txtBayar[$key],'".$txtKet[$key]."',now(),$idUser)");
				$id_bayar = $mysqli->insert_id;
			}
		}

		echo true;
		
	}
	
}
?>