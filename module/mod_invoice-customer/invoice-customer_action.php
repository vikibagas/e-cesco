<?php
if (!isset($_SESSION))
{
	session_start();
}

if (empty($_SESSION['username']) AND empty($_SESSION['password']))
{
	header('location:../../application/index.php');
}
else
{
	include "../../config/connection.php";
	require_once('../../config/db_connect.php');
	require_once('../../config/library.php');
	require_once('../../config/encrypt.php');

	$module	= !empty($_GET['module']) ? $_GET['module'] : '';
	$act	= !empty($_GET['act']) ? $_GET['act'] : '';

	$aksi="module/mod_invoice-customer/invoice-customer_action.php";

	// List paket
	if ($module=='invoice-customer' and $act=='read')
	{
		$table =
<<<EOT
	(
		SELECT
			coalesce(a.nomor,a.status) as nomor,
			a.tanggal,
			a.type_invoice,
			b.nama_kustomer,
			case when a.method=1 then concat(date_format(a.dari,"%d-%m-%Y"),' s/d ',date_format(a.sampai,"%d-%m-%Y")) else '-' end as tanggalairwaybill,
			case when a.method=1 then LEFT(a.loc_code,16) else '-' end as loc_code,
			a.id_invoice as id_invoice,
			case when a.method=1 then 'range tanggal' else 'pilih pengiiman tertentu' end as metode,
			s.id as send, s.confirm_by as confirm, a.id_kustomer, a.tanggal_bayar 
		FROM invoice a
		left JOIN kustomer b ON b.id_kustomer=a.id_kustomer
		left outer JOIN invoice_send s ON s.invoice_id=a.id_invoice and s.invoice_type = 'CUSTOMER'
		group by a.id_invoice
	) temp
EOT;
		$primaryKey = 'id_invoice';
		$columns = array(
			array( 'db' => 'nomor', 'dt' => 0, 'formatter' => function($d,$row) {
				return	'<td><center>'.strtoupper($d).'</center></td>';
			}),
			array( 'db' => 'tanggal', 'dt' => 1 ),
			array( 'db' => 'metode', 'dt' => 2 ),
			array( 'db' => 'nama_kustomer', 'dt' => 3 ),
			array( 'db' => 'type_invoice', 'dt' => 4 ),
			array( 'db' => 'tanggalairwaybill', 'dt' => 5 ),
			array( 'db' => 'tanggal_bayar', 'dt' => 6, 'formatter' => function($d,$row) {
				if ($row['tanggal_bayar'] != '') return $row['tanggal_bayar'].' <button type="button" class="btn btn-xs btn-default frm_inv_bayar" data-toggle="modal" data-target="#modal_bayar" data-id="'.$row['id_invoice'].'" data-invoice="'.$row['nomor'].'"> <i class="fa fa-pencil text-orange"></i> </button>';
				else if ($row['nomor'] != 'draft') {
					return '<button type="button" class="btn btn-xs btn-success frm_inv_bayar" data-toggle="modal" data-target="#modal_bayar" data-id="'.$row['id_invoice'].'" data-invoice="'.$row['nomor'].'"> <i class="fa fa-money"></i> Bayar </button>';
				}
			}),
			array( 'db' => 'send', 'dt' => 18 ),
            array( 'db' => 'confirm', 'dt' => 19 ),
			array( 'db' => 'id_invoice', 'dt' => 7, 'formatter' => function($d,$row) {
				$aksi	= "module/mod_invoice-customer/invoice-customer_action.php";
				$key	= "asdf$1011";
				$nomor = 'nomor';

				if ($row['send'] == ''){
				    $btnSend = '<button class="btn btn-xs btn-default" title="Kirim Invoice ke Customer" onclick="window.location.href = \'module/mod_invoice-customer/invoice-customer_action.php?module=invoice-customer&act=send&stts=1&id='.base64_encrypt($d,$key).'\';"><i class="fa fa-send text-red"></i></button>';
                }else{
					if ($row['confirm'] == ''){
						$btnSend = '<button class="btn btn-xs btn-default" title="Batalkan Kirim Invoice ke Customer" onclick="window.location.href = \'module/mod_invoice-customer/invoice-customer_action.php?module=invoice-customer&act=send&stts=0&id='.base64_encrypt($d,$key).'\';"><i class="fa fa-send text-green"></i></button>';
					}else{

						$btnSend = '<button class="btn btn-xs btn-default" title="Invoice Berhasil Dikonfirmasi" onclick="#"><i class="fa fa-send text-blue"></i></button>';
					}
                }

				return '<td><center>
				<div class="btn-group">
					<button class="btn btn-xs btn-default" title="Update Invoice" onclick="window.location.href = \'?module=invoice-customer&act=update&id='.base64_encrypt($d,$key).'\';"><i class="fa fa-pencil text-yellow"></i></button> &nbsp;'.($row['nomor'] == 'draft' ? '<button class="btn btn-xs btn-default" title="Hapus Invoice" onclick="window.location.href = \'module/mod_invoice-customer/invoice-customer_action.php?module=invoice-customer&act=delete&id='.base64_encrypt($d,$key).'\';"><i class="fa fa-trash text-yellow"></i></button>' : '').'

					'.($row['nomor'] != 'draft' ? '<button class="btn btn-xs btn-default" title="Jadikan DRAFT" onclick="window.location.href = \'module/mod_invoice-customer/invoice-customer_action.php?module=invoice-customer&act=backtodraft&id='.base64_encrypt($d,$key).'\';"><i class="fa fa-arrow-left text-yellow"></i></button>'.$btnSend : '').'

				</div></center></td>';
			}),
		);

		require('../../config/ssp.class.php');
		echo json_encode(
			SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns )
		);

	// create
	} elseif ($module=='invoice-customer' and $act=='not-billed')
    {

        $id	= "$_GET[id]";
        $table =
            <<<EOT
            	(
		                SELECT      '' as cb,
									id_pengiriman,
									date(pickup_date) as tanggal,
									airwaybill_kode,
									case when a.komoditi='1' then 'Paket' else 'Dokumen' end as komoditi,
									a.jenis_barang,
									a.catatan,
									x_service(service) as service,
									nama_pengirim,
									a.loc_code,
									x_namakustomer(a.id_kustomer) as namakustomer,
									nama_penerima,
									case when inbound='Y' then 'INBOUND' when inbound='N' then 'OUTBOUND' else '' end as inbound,
									a.lokasi_asal as id_lokasi_asal,
									if(a.id_kota_tujuan>0,a.kota_asal,x_namakotabesar(lokasi_asal)) as lokasi_asal,
									id_kecamatan_asal,
									x_namakecamatan(id_kecamatan_asal) as kec_asal,
									alamat_pengirim,
									id_kecamatan_tujuan,
									x_namakecamatan(id_kecamatan_tujuan) as kec_tujuan,
									if(a.id_kota_tujuan>0,a.kota_tujuan,x_namakotabesar(lokasi_tujuan)) as lokasi_tujuan,
									alamat_penerima,
									delivery_name,
									delivery_date,

									CASE
										WHEN airwaybill_kode LIKE '001-%' THEN 0
										WHEN airwaybill_kode LIKE '002-%' THEN 1
									end as intercity,

									SUBSTRING_INDEX(ukuran_barang,'x',1) as length,
									SUBSTRING_INDEX(SUBSTRING_INDEX(ukuran_barang,'x',2),'x',-1) as width,
									SUBSTRING_INDEX(ukuran_barang,'x',-1) as height,

									if( (select intercity) = 1,
										ceiling((select length) *  (select width) * (select height)/4000)
									,
										if (a.service = 5,
											ceiling((select length) *  (select width) * (select height)/4000)
										,
											ceiling((select length) *  (select width) * (select height)/6000)
										)
									) as volume,

concat(
									SUBSTRING_INDEX(ukuran_barang,'x',1),'x',
									SUBSTRING_INDEX(SUBSTRING_INDEX(ukuran_barang,'x',2),'x',-1),'x',
									SUBSTRING_INDEX(ukuran_barang,'x',-1),'= ',
									if( (select intercity) = 1,
										ceiling((select length) *  (select width) * (select height)/4000)
									,
										if (a.service = 5,
											ceiling((select length) *  (select width) * (select height)/4000)
										,
											ceiling((select length) *  (select width) * (select height)/6000)
										)
									)) as volume2,

									if(berat_aktual is null or berat_aktual < 1,  berat_barang, berat_aktual) as berat_real,

									case when packing_kayu='1' then 'Packing Oleh Pemilik' when packing_kayu='2' then 'Packing Kayu Oleh Cesco Log.' when packing_kayu='3' then 'Non Packing Kayu Oleh Cesco Log.' end as packing_kayu,
									harga_pengiriman,
									harga_kunci,

									if ((select berat_real) >= (select volume), (select berat_real), (select volume) ) as berat,

                                	a.perusahaan_pengirim,
                                	a.perusahaan_penerima,
									if(a.packing_kayu=2,if(a.biaya_packing < 1,if(a.berat_aktual is null or a.berat_aktual < 1,  a.berat_barang, a.berat_aktual)*2500,a.biaya_packing),0 ) as packing,
									if(a.asuransi = 'Y', if(a.nilai_barang >= 6000000,150000,0.25/100*a.nilai_barang) ,0) as nilai_asuransi,
									a.asuransi,
									a.biaya_packing,
									a.biaya_asuransi,
									a.biaya_tambahan,
									a.costing_pickup_fee,
									a.costing_pickup_parking,
									a.costing_pickup_toll ,
									a.costing_men_power ,
									a.costing_delivery_fee,
									a.costing_delivery_parking,
									a.costing_delivery_toll ,
									a.costing_delivery_menpower,
									a.costing_outbound_fee,
									a.costing_outbound_toll ,
									a.costing_outbound_smu ,
									a.costing_outbound_handling ,
									a.costing_outbound_parking,
									a.costing_outbound_menpower,
									a.costing_inbound_fee,
									a.costing_inbound_toll ,
									a.costing_inbound_smu ,
									a.costing_inbound_handling ,
									a.costing_inbound_parking,
									a.costing_inbound_menpower
								FROM pengiriman a, invoice b
								where
								b.id_kustomer = a.id_kustomer
								and b.id_invoice = '$id' and a.status = 6 and a.id_invoice is null
								and a.pickup_date between DATE_ADD(DATE_FORMAT(b.dari,'%Y-%m-01'),INTERVAL -2 MONTH) and DATE_FORMAT(b.sampai,'%Y-%m-31')
								group by a.id_pengiriman
	) temp
EOT;

        $primaryKey = 'id_pengiriman';
        $columns = array(
            array( 'db' => 'cb', 'dt' => 0 ),
            array( 'db' => 'id_pengiriman', 'dt' => 1, 'formatter' => function($d,$row) {
                return '<input type="checkbox" form="frmAddAwbBulk" name="id_pengiriman_bulk[]" value="'.$row['id_pengiriman'].'">';
            }),
            array( 'db' => 'tanggal', 'dt' => 2 ),
            array( 'db' => 'airwaybill_kode', 'dt' => 3 ),
            array( 'db' => 'komoditi', 'dt' => 4 ),
            array( 'db' => 'service', 'dt' => 5 ),
            array( 'db' => 'namakustomer', 'dt' => 6 ),
            array( 'db' => 'nama_pengirim', 'dt' => 7 ),
            array( 'db' => 'perusahaan_pengirim', 'dt' => 8 ),
            array( 'db' => 'lokasi_asal', 'dt' => 9 ),
            array( 'db' => 'alamat_pengirim', 'dt' => 10 ),
            array( 'db' => 'perusahaan_penerima', 'dt' => 11),
            array( 'db' => 'nama_penerima', 'dt' => 12 ),
            array( 'db' => 'lokasi_tujuan', 'dt' => 13 ),
            array( 'db' => 'alamat_penerima', 'dt' => 14 ),
            array( 'db' => 'delivery_name', 'dt' => 15 ),
            array( 'db' => 'delivery_date', 'dt' => 16 ),
            array( 'db' => 'volume', 'dt' => 17 ),
            array( 'db' => 'berat_real', 'dt' => 18 )
        );

        require('../../config/ssp.class.php');
        echo json_encode(
            SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns )
        );

        // create
    }
    elseif ($module=='invoice-customer' and $act=='create')
	{
		$tanggal		= $mysqli->real_escape_string(jin_date_sql($_POST['tanggal']));
		$id_kustomer	= $mysqli->real_escape_string($_POST['id_kustomer_inv']);
		$pelayanan		= $_POST['id_pelayanan'];
		$id_pelayanan	= implode(',',$pelayanan);
		$tgl_airwaybill	= $mysqli->real_escape_string($_POST['tgl_airwaybill']);
		$dr 			= strleft($tgl_airwaybill, 10);
		$sm 			= strright($tgl_airwaybill, 10);
		$dari 			= jin_date_sql($dr);
		$sampai 		= jin_date_sql($sm);
		$id_komoditi 		= $_POST['id_komoditi'];
		$komoditi 	= implode(',',$id_komoditi);
		//$loc_code		= $mysqli->real_escape_string($_POST['loc_code']);
		$loc 			= $_POST['loc_code'];
		$loc1			= implode(',',$loc);
		$loc_code		= implode("','",$loc);
		//echo $loc_code;
		//return false;
		$bank			= $mysqli->real_escape_string($_POST['bank']);
		$due_date		= date('Y-m-d', strtotime('+45 days', strtotime($tanggal)));
		$key 			= "asdf$1011";
		$encrypt 		= $_SESSION['id_user'];
		$id_user 		= base64_decrypt($encrypt,$key);
		$method 		= $_POST['choose_method'];
		$data_pengiriman = $_POST['data_pengiriman'];
		$awbs = implode(',',$data_pengiriman);
		$tagih_ke = "NULL";

		if (isset($_POST['tagih_ke']) && $_POST['tagih_ke'] != '') {
		    $tagih_ke = intval($_POST['tagih_ke']);
		}
		//echo $method;
		//echo 		 "INSERT into invoice values('',null,'$tanggal','$id_kustomer','$id_pelayanan','$dari','$sampai','$loc1','$komoditi','$bank','$due_date',null,'Judul Invoice',2,1,'draft','N','$id_user',null,now(),null)";
				//$id_invoice = $mysqli->insert_id;

		//return false;
		//echo 'method top'.$method;

		if ($method == 1){
			echo 'method'.$method;
			if ($loc_code != '' and $loc_code != 'all'){
				echo "	loc_code". $loc_code."
											SELECT
												a.id_pengiriman
											FROM pengiriman a
											WHERE a.tanggal BETWEEN '$dari' AND '$sampai' AND a.id_kustomer='$id_kustomer' AND a.service in ($id_pelayanan) AND loc_code in ('$loc_code') AND (id_invoice = 0 or id_invoice is null)
											GROUP BY a.id_pengiriman";
					$airwaybill		= $mysqli->query("
											SELECT
												a.id_pengiriman
											FROM pengiriman a
											WHERE a.tanggal BETWEEN '$dari' AND '$sampai' AND a.id_kustomer='$id_kustomer' AND a.service in ($id_pelayanan) AND loc_code in ('$loc_code') AND (id_invoice = 0 or id_invoice is null)
											GROUP BY a.id_pengiriman");
			}
			else{
				echo "	non loc_code					SELECT
												a.id_pengiriman
											FROM pengiriman a
											WHERE a.tanggal BETWEEN '$dari' AND '$sampai' AND a.id_kustomer='$id_kustomer' AND a.service in ($id_pelayanan) AND (id_invoice = 0 or id_invoice is null)
											GROUP BY a.id_pengiriman";

				$airwaybill		= $mysqli->query("
											SELECT
												a.id_pengiriman
											FROM pengiriman a
											WHERE a.tanggal BETWEEN '$dari' AND '$sampai' AND a.id_kustomer='$id_kustomer' AND a.service in ($id_pelayanan) AND (id_invoice = 0 or id_invoice is null)
											GROUP BY a.id_pengiriman");
			}
			$count			= $airwaybill->num_rows;
			echo $count;
			//return false;
			if ($count>0){
				echo 'ada data';
				$result			= $mysqli->query("INSERT into invoice (id_invoice,nomor,tanggal,id_kustomer,id_pelayanan,dari,sampai,loc_code,komoditi,bank,due_date,judul_subtotal,judul_awal,method,ppn,status,lunas,id_user,nomor_dibuat,created_date,last_update,tagih_ke) values('',null,'$tanggal','$id_kustomer','$id_pelayanan','$dari','$sampai','$loc1','$komoditi','$bank','$due_date',null,'Judul Invoice',1,1,'draft','N','$id_user',null,now(),null,$tagih_ke)");
				
				echo "INSERT into invoice (id_invoice,nomor,tanggal,id_kustomer,id_pelayanan,dari,sampai,loc_code,komoditi,bank,due_date,judul_subtotal,judul_awal,method,ppn,status,lunas,id_user,nomor_dibuat,created_date,last_update) values('',null,'$tanggal','$id_kustomer','$id_pelayanan','$dari','$sampai','$loc1','$komoditi','$bank','$due_date',null,'Judul Invoice',1,1,'draft','N','$id_user',null,now(),null)";


				$id_invoice = $mysqli->insert_id;
				$id			= base64_encrypt($id_invoice,$key);
				echo "<br><br> id pengiriman = ".$id;

				if ($loc_code != '' and $loc_code != 'all'){
					$mysqli->query("UPDATE pengiriman set id_invoice=$id_invoice WHERE tanggal BETWEEN '$dari' AND '$sampai' AND id_kustomer='$id_kustomer' AND service in ($id_pelayanan) AND loc_code in ('$loc_code') AND (id_invoice = 0 or id_invoice is null) AND biaya_beban != 4");
					echo "UPDATE pengiriman set id_invoice=$id_invoice WHERE tanggal BETWEEN '$dari' AND '$sampai' AND id_kustomer='$id_kustomer' AND service in ($id_pelayanan) AND loc_code in ('$loc_code') AND (id_invoice = 0 or id_invoice is null) AND biaya_beban != 4";
					//return false;
				}
				else{
					$mysqli->query("UPDATE pengiriman set id_invoice=$id_invoice WHERE tanggal BETWEEN '$dari' AND '$sampai' AND id_kustomer='$id_kustomer' AND service in ($id_pelayanan) AND (id_invoice = 0 or id_invoice is null) AND biaya_beban != 4");
				//	echo "UPDATE pengiriman set id_invoice=$id_invoice WHERE tanggal BETWEEN '$dari' AND '$sampai' AND id_kustomer='$id_kustomer' AND service in ($id_pelayanan) AND (id_invoice = 0 or id_invoice is null)";
				}



				if ($result)
				{
					$_SESSION['pesan'] = 'success';
					header('location:../../main.php?module=invoice-customer&act=update&id='.$id);
				}
				else
				{
					$_SESSION['pesan'] = 'failed';
					header('location:../../main.php?module=invoice-customer&error=failed');
				}
			}
			else
			{
				echo 'ga ada data';
				$_SESSION['pesan'] = 'null';
				header('location:../../main.php?module=invoice-customer&error=null');
				//return true;
			}
		}
		else{
				//echo 'method else'.$method;
			//	echo "INSERT into invoice values('',null,'$tanggal','$id_kustomer','$id_pelayanan','$dari','$sampai','$loc_code','$komoditi','$bank','$due_date',null,null,'draft','N','$id_user',null,now(),null)";
			//	echo "UPDATE pengiriman set id_invoice=$id_invoice WHERE id_pengiriman in ($awbs)";

			//bener



				$result			= $mysqli->query("INSERT into invoice (id_invoice,nomor,tanggal,id_kustomer,id_pelayanan,dari,sampai,loc_code,komoditi,bank,due_date,judul_subtotal,judul_awal,method,ppn,status,lunas,id_user,nomor_dibuat,created_date,last_update,tagih_ke) values('',null,'$tanggal','$id_kustomer','$id_pelayanan','$dari','$sampai','$loc1','$komoditi','$bank','$due_date',null,'Judul Invoice',2,1,'draft','N','$id_user',null,now(),null,$tagih_ke)");
				$id_invoice = $mysqli->insert_id;
				$id			= base64_encrypt($id_invoice,$key);
				$mysqli->query("UPDATE pengiriman set id_invoice=$id_invoice WHERE id_pengiriman in ($awbs)");

				if ($result)
				{
					$_SESSION['pesan'] = 'success';
					header('location:../../main.php?module='.$module.'&act=update&id='.$id);
				}

		}
		//echo 'bawah';
		/*$airwaybill		= $mysqli->query("
										SELECT
											a.id_pengiriman
										FROM pengiriman a
										WHERE a.tanggal BETWEEN '$dari' AND '$sampai' AND a.id_kustomer='$id_kustomer' AND a.service in ($id_pelayanan) AND a.id_pengiriman NOT IN (select id_pengiriman FROM invoice_detail)
										GROUP BY a.id_pengiriman");
		$count			= $airwaybill->num_rows;
		if ($count>0)
		{
			$result			= $mysqli->query("insert into invoice (tanggal,id_kustomer,id_pelayanan,dari,sampai,id_user) values('$tanggal','$id_kustomer','$id_pelayanan','$dari','$sampai','$id_user')");
			$id_invoice = $mysqli->insert_id;
			$id			= base64_encrypt($id_invoice,$key);
			/*$id_invoice = $mysqli->insert_id;
			$id			= base64_encrypt($id_invoice,$key);

			while($a=$airwaybill->fetch_assoc()){
				$mysqli->query("insert into invoice_detail (id_invoice, id_pengiriman, harga_jual) values ('$id_invoice', '$a[id_pengiriman]', '0')");
			}*/


		/*}
		else
		{
			$_SESSION['pesan'] = 'null';
		}*/



	}

	// delete invoice
	elseif ($module=='invoice-customer' AND $act=='delete')
	{
		$encrypt	= $_GET['id'];
		$key		= "asdf$1011";
		$id_invoice	= base64_decrypt($encrypt,$key);
		//echo 'delete'.$id_invoice;
		//$mysqli->query("delete from invoice where id_invoice='$id_invoice'");
		$mysqli->query("delete from invoice where id_invoice='$id_invoice'");
		$mysqli->query("delete from invoice_detail where id_invoice='$id_invoice'");
		$mysqli->query("update pengiriman set id_invoice=null, harga_kunci=null where id_invoice =$id_invoice");
		$_SESSION['pesan'] = 'delete';
		header('location:../../main.php?module='.$module.'&delete=success'.$id_invoice);
	}
	elseif ($module=='invoice-customer' AND $act=='backtodraft')
	{
		$encrypt	= $_GET['id'];
		$key		= "asdf$1011";
		$id_invoice	= base64_decrypt($encrypt,$key);
		//echo 'delete'.$id_invoice;
		//$mysqli->query("delete from invoice where id_invoice='$id_invoice'");
		$mysqli->query("update invoice set nomor = null, status='draft', nomor_dibuat = null, subtotal = null, ppn_subtotal = null, inv_packing_kayu = null, ppn_packing = null, inv_asuransi = null, total_all = null,jumlah_bayar = null, tanggal_bayar = null, ket_bayar = null

		  where id_invoice='$id_invoice'");
		
		$mysqli->query("update pengiriman set harga_kunci=null where id_invoice =$id_invoice");

		header('Location: ' . $_SERVER['HTTP_REFERER']);
	}

	// detail airwaybill
	elseif ($module=='invoice-customer' AND $act=='airwaybill')
	{
		$id = $_POST['id'];

		$query = $mysqli->query("SELECT a.id_pengiriman, a.airwaybill_kode, a.tanggal, a.pickup_date ,x_namakotabesar(a.lokasi_asal) as lokasi_asal, x_namakotabesar(a.lokasi_tujuan) as lokasi_tujuan, x_service(a.service) as service, case when a.komoditi='1' then 'Paket' else 'Dokumen' end as comodity, a.berat_barang, a.berat_aktual, b.harga_jual, b.packing, b.asuransi, b.charge, b.id_invoice, a.nama_pengirim, a.nama_penerima, a.alamat_pengirim, a.alamat_penerima, a.loc_code, a.delivery_name, a.delivery_date FROM pengiriman a inner join invoice_detail b on b.id_pengiriman=a.id_pengiriman WHERE a.id_pengiriman='".$id."'");
		$r = $query->fetch_assoc();
		echo'
		<form action="'.$aksi.'?module=invoice-customer&act=updateharga" method="POST">
			<div class="form-group" style="padding-bottom: 25px;">
				<label class="col-md-3 control-label">Pickup</label>
				<div class="col-md-8">
					<input type="text" value="'.tgl_indo($r['pickup_date']).'" class="form-control" readonly>
				</div>
			</div>
			<div class="form-group" style="padding-bottom: 25px;">
				<label class="col-md-3 control-label">Airwaybill</label>
				<div class="col-md-8">
					<input type="text" value="'.$r['airwaybill_kode'].'" class="form-control" readonly>
				</div>
			</div>
			<div class="form-group" style="padding-bottom: 25px;">
				<label class="col-md-3 control-label">Komoditi</label>
				<div class="col-md-8">
					<input type="text" value="'.$r['comodity'].'" class="form-control" readonly>
				</div>
			</div>
			<div class="form-group" style="padding-bottom: 25px;">
				<label class="col-md-3 control-label">Service</label>
				<div class="col-md-8">
					<input type="text" value="'.$r['service'].'" class="form-control" readonly>
				</div>
			</div>
			<div class="form-group" style="padding-bottom: 25px;">
				<label class="col-md-3 control-label">Requestor</label>
				<div class="col-md-8">
					<input type="text" value="'.$r['nama_pengirim'].'" class="form-control" readonly>
				</div>
			</div>
			<div class="form-group" style="padding-bottom: 25px;">
				<label class="col-md-3 control-label">Loc Code</label>
				<div class="col-md-8">
					<input type="text" value="'.$r['loc_code'].'" class="form-control" readonly>
				</div>
			</div>
			<div class="form-group" style="padding-bottom: 25px;">
				<label class="col-md-3 control-label">Consignee</label>
				<div class="col-md-8">
					<input type="text" value="'.$r['nama_penerima'].'" class="form-control" readonly>
				</div>
			</div>
			<div class="form-group" style="padding-bottom: 25px;">
				<label class="col-md-3 control-label">Kota Asal</label>
				<div class="col-md-8">
					<input type="text" value="'.$r['lokasi_asal'].'" class="form-control" readonly>
				</div>
			</div>
			<div class="form-group" style="padding-bottom: 25px;">
				<label class="col-md-3 control-label">Alamat</label>
				<div class="col-md-8">
					<textarea rows="2" cols="40" disabled>'.$r['alamat_pengirim'].'</textarea>
				</div>
			</div>
			<div class="form-group" style="padding-bottom: 25px;">
				<label class="col-md-3 control-label">Kota Tujuan</label>
				<div class="col-md-8">
					<input type="text" value="'.$r['lokasi_tujuan'].'" class="form-control" readonly>
				</div>
			</div>
			<div class="form-group" style="padding-bottom: 25px;">
				<label class="col-md-3 control-label">Alamat</label>
				<div class="col-md-8">
					<textarea rows="2" cols="40" disabled>'.$r['alamat_penerima'].'</textarea>
				</div>
			</div>
			<div class="form-group" style="padding-bottom: 25px;">
				<label class="col-md-3 control-label">Diterima</label>
				<div class="col-md-8">
					<input type="text" value="'.$r['delivery_name'].'" class="form-control" readonly>
				</div>
			</div>
			<div class="form-group" style="padding-bottom: 25px;">
				<label class="col-md-3 control-label">Tanggal</label>
				<div class="col-md-8">
					<input type="text" value="'.tgl_indo($r['delivery_date']).'" class="form-control" readonly>
				</div>
			</div>
			<div class="form-group" style="padding-bottom: 25px;">
				<label class="col-md-3 control-label">Berat</label>
				<div class="col-md-8">
					<input type="text" value="'.$r['berat_barang'].'" class="form-control" readonly>
				</div>
			</div>
			<div class="form-group" style="padding-bottom: 25px;">
				<label class="col-md-3 control-label">Harga:</label>
				<div class="col-md-8">
					<input type="text" name="harga_jual" class="form-control" value="'.$r['harga_jual'].'" />
				</div>
			</div>
			<div class="form-group" style="padding-bottom: 25px;">
				<label class="col-md-3 control-label">Packing:</label>
				<div class="col-md-8">
					<input type="text" name="packing" class="form-control" value="'.$r['packing'].'" />
				</div>
			</div>
			<div class="form-group" style="padding-bottom: 25px;">
				<label class="col-md-3 control-label">Asuransi:</label>
				<div class="col-md-8">
					<input type="text" name="asuransi" class="form-control" value="'.$r['asuransi'].'" />
				</div>
			</div>
			<div class="form-group" style="padding-bottom: 25px;">
				<label class="col-md-3 control-label">Add Charge:</label>
				<div class="col-md-8">
					<input type="text" name="charge" class="form-control" value="'.$r['charge'].'" />
				</div>
			</div>
			<input type="hidden" name="id_invoice" class="form-control" value="'.$r['id_invoice'].'" />
			<input type="hidden" name="id_pengiriman" class="form-control" value="'.$r['id_pengiriman'].'" />
			<div class="modal-footer">
				<button class="btn btn-success" type="submit">Confirm</button>
				<button type="reset" class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Cancel</button>
			</div>
		</form>';

	}


	elseif ($module=='invoice-customer' AND $act=='form-insert-price')
	{
		$id = $_POST['id'];

		$query = $mysqli->query("SELECT a.id_pengiriman, a.airwaybill_kode, a.tanggal, a.pickup_date, a.id_kustomer ,if(a.id_kota_asal > 0,a.kota_asal,x_namakotabesar(a.lokasi_asal)) as lokasi_asal, if(a.id_kota_asal>0,a.id_kota_asal,a.lokasi_asal) as id_lokasi_asal,
			if(a.id_kota_tujuan > 0,a.kota_tujuan,x_namakotabesar(a.lokasi_tujuan)) as lokasi_tujuan, if(a.id_kota_tujuan>0,a.id_kota_tujuan,a.lokasi_tujuan) as id_lokasi_tujuan,
			x_service(a.service) as service, a.service as id_pelayanan, case when a.komoditi='1' then 'Paket' else 'Dokumen' end as comodity,a.komoditi as komoditi, a.berat_barang, a.berat_aktual, a.nama_pengirim, a.nama_penerima, a.alamat_pengirim, a.alamat_penerima, a.loc_code, a.delivery_name, a.delivery_date FROM pengiriman a LEFT JOIN negara f ON f.id_negara=a.lokasi_tujuan and a.service=6 WHERE a.id_pengiriman='".$id."'");
		$r = $query->fetch_assoc();
		//<form action="'.$aksi.'?module=invoice-customer&act=updateharga" method="POST">
		echo'
		<form action="'.$aksi.'?module=invoice-customer&act=insert-price" method="POST">

			<div class="form-group" style="padding-bottom: 25px;">
				<label class="col-md-3 control-label">Komoditi</label>
				<div class="col-md-8">
					<input type="text" value="'.$r['comodity'].'" class="form-control" readonly>
				</div>
			</div>
			<div class="form-group" style="padding-bottom: 25px;">
				<label class="col-md-3 control-label">Service</label>
				<div class="col-md-8">
					<input type="text" value="'.$r['service'].'" class="form-control" readonly>
				</div>
			</div>
			<div class="form-group" style="padding-bottom: 25px;">
				<label class="col-md-3 control-label">Loc Code</label>
				<div class="col-md-8">
					<input type="text" value="'.$r['loc_code'].'" name="loc_code" class="form-control" readonly>
				</div>
			</div>
			<div class="form-group" style="padding-bottom: 25px;">
				<label class="col-md-3 control-label">Kota Asal</label>
				<div class="col-md-8">
					<input type="text" value="'.$r['lokasi_asal'].'" class="form-control" readonly>
				</div>
			</div>
			<div class="form-group" style="padding-bottom: 25px;">
				<label class="col-md-3 control-label">Kota Tujuan</label>
				<div class="col-md-8">
					<input type="text" value="'.$r['lokasi_tujuan'].'" class="form-control" readonly>
				</div>
			</div>

			<div class="form-group" style="padding-bottom: 25px;">
				<label class="col-md-3 control-label">Min kg</label>
				<div class="col-md-8">
					<input type="number" value="1" name="min_kg" class="form-control" min="1">
				</div>
			</div>
			<div class="form-group" style="padding-bottom: 25px;">
				<label class="col-md-3 control-label">Harga:</label>
				<div class="col-md-8">
					<input type="number" name="price_perkg" class="form-control" value="1000" min="1000" />
				</div>
			</div>
			<div class="form-group" style="padding-bottom: 25px;">
				<label class="col-md-3 control-label">Lead Time:</label>
				<div class="col-md-8">
					<input type="number" name="sla" class="form-control" value="3" min="1" required/>
				</div>
			</div>
			<input type="hidden" name="id_pengiriman" class="form-control" value="'.$r['id_pengiriman'].'" />
			<input type="hidden" name="id_kustomer" class="form-control" value="'.$r['id_kustomer'].'" />
			<input type="hidden" name="id_lokasi_asal" class="form-control" value="'.$r['id_lokasi_asal'].'" />
			<input type="hidden" name="id_lokasi_tujuan" class="form-control" value="'.$r['id_lokasi_tujuan'].'" />
			<input type="hidden" name="id_pelayanan" class="form-control" value="'.$r['id_pelayanan'].'" />
			<input type="hidden" name="komoditi" class="form-control" value="'.$r['komoditi'].'" />
			<div class="modal-footer">
				<button class="btn btn-success" type="submit">insert</button>
				<button type="reset" class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Cancel</button>
			</div>
		</form>';

	}
	elseif ($module=='invoice-customer' AND $act=='insert-price'){
		$id_kustomer		= $mysqli->real_escape_string($_POST['id_kustomer']);
		$id_lokasi_asal	= $mysqli->real_escape_string($_POST['id_lokasi_asal']);
		$id_lokasi_tujuan		= $mysqli->real_escape_string($_POST['id_lokasi_tujuan']);
		$id_pelayanan		= $mysqli->real_escape_string($_POST['id_pelayanan']);
		$komoditi			= $mysqli->real_escape_string($_POST['komoditi']);
		$loc_code			= $_POST['loc_code'];
		if($loc_code == ''){
			$loc_code = 'null';
		}else{
			$loc_code = "'$loc_code'";
		}
		$min_kg			= $mysqli->real_escape_string($_POST['min_kg']);
		$harga			= $mysqli->real_escape_string($_POST['price_perkg']);
		//$min_kg_2			= $mysqli->real_escape_string($_POST['min_kg_2']);
		$min_kg_2 = isset($_POST["min_kg_2"]) ? $_POST["min_kg_2"] : 0;
		//$harga_2			= $mysqli->real_escape_string($_POST['price_perkg_2']);
		$harga_2 = isset($_POST["harga_2"]) ? $_POST["harga_2"] : 0;
		//$nextkg = $_POST['check-nextkg'];
		$nextkg = isset($_POST["check-nextkg"]) ? $_POST["check-nextkg"] : 0;
		//$pricenextkg = $_POST['price_nextkg'];
		$pricenextkg = isset($_POST["price_nextkg"]) ? $_POST["price_nextkg"] : 0;
		$sla = $_POST['sla'];
		if($nextkg == ''){
			$next = 'null';
		}else{
			$next = "'$pricenextkg'";
		}
		$result			= $mysqli->query("INSERT into rate_customer values(null,$id_kustomer,null,$id_lokasi_asal,$id_lokasi_tujuan,$id_pelayanan,$komoditi,$loc_code,'$min_kg','$harga','$min_kg_2','$harga_2',$next,$sla)");

		//echo "INSERT into rate_customer values('',$id_kustomer,null,$id_lokasi_asal,$id_lokasi_tujuan,$id_pelayanan,$komoditi,$loc_code,'$min_kg','$harga','$min_kg_2','$harga_2',$next,null)";

		//return false;
		if ($result)
		{
			$_SESSION['pesan'] = 'success';
		}
		else
		{
			$_SESSION['pesan'] = 'failed';
			echo 'failde';
		}

		header('Location: ' . $_SERVER['HTTP_REFERER']);
	}
	elseif ($module=='invoice-customer' AND $act=='form-edit-price')
	{
		$id = $_POST['id'];
		 $q = "SELECT id_kustomer, if(komoditi = 1,'paket','dokumen') as comodity,
		 ifnull(x_namakotanew(id_lokasi_asal),x_namakotabesar(id_lokasi_asal)) as lokasi_asal,

		 if(a.id_pelayanan=6,f.nama_negara,ifnull(x_namakotanew(id_lokasi_tujuan), x_namakotabesar(id_lokasi_tujuan)) ) as lokasi_tujuan,

		 x_service(id_pelayanan) as service, loc_code, min_kg, harga,min_kg_2, harga_2, next_kg,lead_time from rate_customer a LEFT JOIN negara f ON f.id_negara=a.id_lokasi_tujuan and a.id_pelayanan=6 where id=$id";
		$query = $mysqli->query($q);
		$r = $query->fetch_assoc();
		//<form action="'.$aksi.'?module=invoice-customer&act=updateharga" method="POST">
		echo'
		<form action="'.$aksi.'?module=invoice-customer&act=update-price" method="POST">

			<div class="form-group" style="padding-bottom: 25px;">
				<label class="col-md-3 control-label">Komoditi</label>
				<div class="col-md-8">
					<input type="text" value="'.$r['comodity'].'" class="form-control" readonly>
				</div>
			</div>
			<div class="form-group" style="padding-bottom: 25px;">
				<label class="col-md-3 control-label">Service</label>
				<div class="col-md-8">
					<input type="text" value="'.$r['service'].'" class="form-control" readonly>
				</div>
			</div>
			<div class="form-group" style="padding-bottom: 25px;">
				<label class="col-md-3 control-label">Loc Code</label>
				<div class="col-md-8">
					<input type="text" value="'.$r['loc_code'].'" name="loc_code" class="form-control" readonly>
				</div>
			</div>
			<div class="form-group" style="padding-bottom: 25px;">
				<label class="col-md-3 control-label">Kota Asal</label>
				<div class="col-md-8">
					<input type="text" value="'.$r['lokasi_asal'].'" class="form-control" readonly>
				</div>
			</div>
			<div class="form-group" style="padding-bottom: 25px;">
				<label class="col-md-3 control-label">Kota Tujuan</label>
				<div class="col-md-8">
					<input type="text" value="'.$r['lokasi_tujuan'].'" class="form-control" readonly>
				</div>
			</div>

			<div class="form-group" style="padding-bottom: 25px;">
				<label class="col-md-3 control-label">Min kg</label>
				<div class="col-md-8">
					<input type="number" value="'.$r['min_kg'].'" name="min_kg" class="form-control" min="1">
				</div>
			</div>
			<div class="form-group" style="padding-bottom: 25px;">
				<label class="col-md-3 control-label">Harga:</label>
				<div class="col-md-8">
					<input type="number" name="price_perkg" value="'.$r['harga'].'" class="form-control"  min="1000" />
				</div>
			</div>
			<div class="form-group" style="padding-bottom: 25px;">
				<label class="col-md-3 control-label">Lead Time:</label>
				<div class="col-md-8">
					<input type="number" name="sla" value="'.$r['lead_time'].'" class="form-control"  min="1"  required/>
				</div>
			</div>
			<input type="hidden" name="id_rate" class="form-control" value="'.$id.'" />
			<div class="modal-footer">
				<button class="btn btn-success" type="submit">update</button>
				<button type="reset" class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Cancel</button>
			</div>
		</form>';

	}
	elseif ($module=='invoice-customer' AND $act=='update-price'){
		$id_rate		= $mysqli->real_escape_string($_POST['id_rate']);
		$min_kg			= $mysqli->real_escape_string($_POST['min_kg']);
		$harga			= $mysqli->real_escape_string($_POST['price_perkg']);
		$min_kg_2			= $mysqli->real_escape_string($_POST['min_kg_2']);
		$harga_2			= $mysqli->real_escape_string($_POST['price_perkg_2']);
		$nextkg		= $mysqli->real_escape_string($_POST['price_nextkg']);
		$sla		= $mysqli->real_escape_string($_POST['sla']);

		// "update rate_customer set min_kg='".$min_kg."', harga='".$harga."' where id='".$id_rate."'";

		if ($nextkg == ''){
			$result			= $mysqli->query("update rate_customer set min_kg='".$min_kg."', harga='".$harga."',min_kg_2='".$min_kg_2."', harga_2='".$harga_2."', lead_time='".$sla."' where id='".$id_rate."'");
		}
		else{
			$result			= $mysqli->query("update rate_customer set min_kg='".$min_kg."', harga='".$harga."',min_kg_2='".$min_kg_2."', harga_2='".$harga_2."', next_kg = '".$nextkg."', lead_time='".$sla."' where id='".$id_rate."'");
		}
		//echo "INSERT into rate_customer values('',$id_kustomer,null,$id_lokasi_asal,$id_lokasi_tujuan,$id_pelayanan,$komoditi,$loc_code,$min_kg,$harga,null)";
		if ($result)
		{
			$_SESSION['pesan'] = 'success';
		}
		else
		{
			$_SESSION['pesan'] = 'failed';
			echo 'failde';
		}

		header('Location: ' . $_SERVER['HTTP_REFERER']);
	}
	elseif ($module=='invoice-customer' AND $act=='tambah-biaya'){
		//echo 'asdas';
		//header('Location: ' . $_SERVER['HTTP_REFERER']);
		$id_invoice		= $mysqli->real_escape_string($_POST['id_invoice']);
		//echo $id_invoice;
		$detail_biaya	= $mysqli->real_escape_string($_POST['detail_biaya']);
		$pajak = $mysqli->real_escape_string($_POST['pajak']);
		$pajak10 = $mysqli->real_escape_string($_POST['pajak10']);

		if ($pajak == 'Ya')
		{
			$pajak = 'Ya';
		}
		else{
			$pajak = 'Tidak';
		}

		if ($pajak10 == '10')
		{
			$pajak = '10';
		}

		$biaya		= $mysqli->real_escape_string($_POST['biaya']);

		$result			= $mysqli->query("INSERT into invoice_detail values('',$id_invoice,'$detail_biaya','$pajak',$biaya)");
		if ($result){
			header('Location: ' . $_SERVER['HTTP_REFERER']);
		}
		else{
			echo 'error'."INSERT into invoice_detail values('',$id_invoice,'$detail_biaya','$pajak',$biaya)";
		}
	}

	elseif ($module=='invoice-customer' AND $act=='tambah-biaya-manual'){
		//echo 'asdas';
		//header('Location: ' . $_SERVER['HTTP_REFERER']);
		$id_invoice		= $mysqli->real_escape_string($_POST['id_invoice']);
		//echo $id_invoice;
		$detail_biaya	= $mysqli->real_escape_string($_POST['detail_biaya']);
	

		

		$biaya		= $mysqli->real_escape_string($_POST['biaya']);

	//	echo $id_invoice.' '.$detail_biaya.' '.$biaya;

		$mysqli->query("update invoice set biaya_tambahan = ".$biaya.", keterangan_biaya_tambahan='".$detail_biaya."' 

		  where id_invoice='$id_invoice'");

		  //echo "update invoice set biaya_tambahan = ".$biaya.", keterangan_biaya_tambahan='".$detail_biaya."' where id_invoice='$id_invoice'";

		
			header('Location: ' . $_SERVER['HTTP_REFERER']);
		
	}

	elseif ($module=='invoice-customer' AND $act=='edit-biaya-manual'){
		//echo 'asdas';
		//header('Location: ' . $_SERVER['HTTP_REFERER']);
		$id_invoice		= $mysqli->real_escape_string($_POST['id_invoice']);
		//echo $id_invoice;
		$detail_biaya	= $mysqli->real_escape_string($_POST['detail_biaya']);
	

		

		$biaya		= $mysqli->real_escape_string($_POST['biaya']);

	//	echo $id_invoice.' '.$detail_biaya.' '.$biaya;

		$mysqli->query("update invoice set biaya_tambahan = ".$biaya.", keterangan_biaya_tambahan='".$detail_biaya."' 

		  where id_invoice='$id_invoice'");

		  //echo "update invoice set biaya_tambahan = ".$biaya.", keterangan_biaya_tambahan='".$detail_biaya."' where id_invoice='$id_invoice'";

		
			header('Location: ' . $_SERVER['HTTP_REFERER']);
		
	}

	elseif ($module=='invoice-customer' AND $act=='hapus-biaya-manual'){
		//echo 'asdas';
		//header('Location: ' . $_SERVER['HTTP_REFERER']);
		$id_invoice		= $mysqli->real_escape_string($_GET['id_invoice']);
		//echo $id_invoice;
		//$detail_biaya	= $mysqli->real_escape_string($_POST['detail_biaya']);
	

		

		//$biaya		= $mysqli->real_escape_string($_POST['biaya']);

	//	echo $id_invoice.' '.$detail_biaya.' '.$biaya;

		$mysqli->query("update invoice set biaya_tambahan = null, keterangan_biaya_tambahan= null 

		  where id_invoice='$id_invoice'");

		  

		
			header('Location: ' . $_SERVER['HTTP_REFERER']);
		
	}

	// update harga airwaybill
	elseif ($module=='invoice-customer' AND $act=='updateharga')
	{
		$id_invoice		= $mysqli->real_escape_string($_POST['id_invoice']);
		$id_pengiriman	= $mysqli->real_escape_string($_POST['id_pengiriman']);
		$harga_jual		= $mysqli->real_escape_string($_POST['harga_jual']);
		$packing		= $mysqli->real_escape_string($_POST['packing']);
		$asuransi		= $mysqli->real_escape_string($_POST['asuransi']);
		$charge			= $mysqli->real_escape_string($_POST['charge']);
		$key			= "asdf$1011";
		$id				= base64_encrypt($id_invoice,$key);

		$result			= $mysqli->query("update invoice_detail set harga_jual='$harga_jual', packing='$packing', asuransi='$asuransi', charge='$charge' where id_invoice='$id_invoice' and id_pengiriman='$id_pengiriman'");

		if ($result)
		{
			$_SESSION['pesan'] = 'success';
		}
		else
		{
			$_SESSION['pesan'] = 'failed';
		}

		header('location:../../main.php?module='.$module.'&act=update&id='.$id);

	}

	elseif ($module=='invoice-customer' AND $act=='form-total-fix')
	{
		$id = $_POST['id'];
		 $q = "SELECT id_kustomer,airwaybill_kode, if(komoditi = 1,'paket','dokumen') as comodity, x_namakotabesar(lokasi_asal) as lokasi_asal,  if(a.service=6,f.nama_negara,x_namakotabesar(lokasi_tujuan)) as lokasi_tujuan, x_service(service) as service, loc_code, harga_pengiriman, berat_aktual from pengiriman a LEFT JOIN negara f ON f.id_negara=a.lokasi_tujuan and a.service=6 where id_pengiriman=$id";
		$query = $mysqli->query($q);
		$r = $query->fetch_assoc();
		//<form action="'.$aksi.'?module=invoice-customer&act=updateharga" method="POST">
		echo'
		<form action="'.$aksi.'?module=invoice-customer&act=update-total-fix" method="POST">
			<div class="form-group" style="padding-bottom: 25px;">
				<label class="col-md-3 control-label">No Airwaybill</label>
				<div class="col-md-8">
					<input type="text" value="'.$r['airwaybill_kode'].'" class="form-control" readonly>
				</div>
			</div>
			<div class="form-group" style="padding-bottom: 25px;">
				<label class="col-md-3 control-label">Komoditi</label>
				<div class="col-md-8">
					<input type="text" value="'.$r['comodity'].'" class="form-control" readonly>
				</div>
			</div>
			<div class="form-group" style="padding-bottom: 25px;">
				<label class="col-md-3 control-label">Service</label>
				<div class="col-md-8">
					<input type="text" value="'.$r['service'].'" class="form-control" readonly>
				</div>
			</div>
			<div class="form-group" style="padding-bottom: 25px;">
				<label class="col-md-3 control-label">Loc Code</label>
				<div class="col-md-8">
					<input type="text" value="'.$r['loc_code'].'" name="loc_code" class="form-control" readonly>
				</div>
			</div>
			<div class="form-group" style="padding-bottom: 25px;">
				<label class="col-md-3 control-label">Kota Asal</label>
				<div class="col-md-8">
					<input type="text" value="'.$r['lokasi_asal'].'" class="form-control" readonly>
				</div>
			</div>
			<div class="form-group" style="padding-bottom: 25px;">
				<label class="col-md-3 control-label">Kota Tujuan</label>
				<div class="col-md-8">
					<input type="text" value="'.$r['lokasi_tujuan'].'" class="form-control" readonly>
				</div>
			</div>

			<div class="form-group" style="padding-bottom: 25px;">
				<label class="col-md-3 control-label">Berat</label>
				<div class="col-md-8">
					<input type="number" value="'.$r['berat_aktual'].'" name="min_kg" class="form-control" min="1" readonly>
				</div>
			</div>
			<div class="form-group" style="padding-bottom: 25px;">
				<label class="col-md-3 control-label">Harga:</label>
				<div class="col-md-8">
					<input type="number" name="harga" value="'.($r['harga_pengiriman'] === null ? 0 : $r['harga_pengiriman']).'" class="form-control"   />
				</div>
			</div>
			<input type="hidden" name="id_pengiriman" class="form-control" value="'.$id.'" />
			<div class="modal-footer">
				<button class="btn btn-success" type="submit">update</button>
				<button type="reset" class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Cancel</button>
			</div>
		</form>';

	}
	elseif ($module=='invoice-customer' AND $act=='update-total-fix')
	{
		$id_pengiriman	= $mysqli->real_escape_string($_POST['id_pengiriman']);
		$harga				= $mysqli->real_escape_string($_POST['harga']);
		//echo "update pengiriman set harga_pengiriman='$harga' where id_pengiriman='$id_pengiriman'";
		//return false;

		if ($harga == ''){
			$result			= $mysqli->query("update pengiriman set harga_pengiriman=null where id_pengiriman='$id_pengiriman'");
		}else{
			$result			= $mysqli->query("update pengiriman set harga_pengiriman='$harga' where id_pengiriman='$id_pengiriman'");
		}

		if ($result)
		{
			$_SESSION['pesan'] = 'success';
		}
		else
		{
			$_SESSION['pesan'] = 'failed';
		}

		header('Location: ' . $_SERVER['HTTP_REFERER']);

	}
	// delete airwaybill
	elseif ($module=='invoice-customer' AND $act=='deleteairwaybill')
	{
		$id = $_GET['id'];
		$invoice	= $mysqli->query("select id_invoice from invoice_detail where id_pengiriman='$id'");
		$i			= $invoice->fetch_assoc();
		$key		= "asdf$1011";
		$id_invoice	= base64_encrypt($i['id_invoice'],$key);

		$mysqli->query("delete from invoice_detail where id_pengiriman='$id'");
		header('location:../../main.php?module='.$module.'&act=update&id='.$id_invoice);
	}
	elseif($module=='invoice-customer' AND $act=='tambahan_manual'){
		$mysqli->query("update invoice set biaya_tambahan='$_POST[value]' where id_invoice='$_POST[pk]'");
		//echo "update invoice set biaya_tambahan='$_POST[value]' where id_invoice='$_POST[pk]'";
	}

	elseif($module=='invoice-customer' AND $act=='packing'){
		$mysqli->query("update pengiriman set biaya_packing='$_POST[value]' where id_pengiriman='$_POST[pk]'");
		echo "update pengiriman set biaya_packing='$_POST[value]' where id_pengiriman='$_POST[pk]'";
	}

	elseif($module=='invoice-customer' AND $act=='asuransi'){
		$mysqli->query("update pengiriman set biaya_asuransi='$_POST[value]' where id_pengiriman='$_POST[pk]'");
	}

	elseif($module=='invoice-customer' AND $act=='tambahan'){
		$mysqli->query("update pengiriman set biaya_tambahan='$_POST[value]' where id_pengiriman='$_POST[pk]'");
	}
	elseif($module=='invoice-customer' AND $act=='ket_biaya'){
		$mysqli->query("update pengiriman set keterangan_biaya='$_POST[value]' where id_pengiriman='$_POST[pk]'");
	}
	elseif ($module=='invoice-customer' AND $act=='nomor') {
		
		$total_semua = $_GET['total_semua'];
		$ppn = $_GET['ppn'];
		$asuransi = $_GET['asuransi'];
		$subtotal = $_GET['subtotal'];
		$tambahan = $_GET['tambahan'];

		$cek = mysqli_fetch_assoc(mysqli_query($con,"SELECT nomor FROM invoice ORDER BY nomor_dibuat DESC LIMIT 1"));
		//echo "SELECT nomor FROM invoice ORDER BY nomor_dibuat DESC LIMIT 1";
		//echo $cek['nomor'].' test<br>';
		/*$ex = explode('/', $cek['nomor']);
		$total_semua = $_GET['total_semua'];
		$ppn = $_GET['ppn'];
		$asuransi = $_GET['asuransi'];
		$subtotal = $_GET['subtotal'];
		$tambahan = $_GET['tambahan'];

		if (date('d')=='01'){ $a = '01'; }
		else{ $a = $ex[0]+1; }

		$b = 'CLS';
		$c = array('','I','II','III','IV','V','VI','VII','VIII','IX','X','XI','XII');
		$d = date('Y');
		$no_surat = $a.'/'.$b.'/'.$c[date('n')].'/'.$d;*/

		$ex = trim($cek['nomor']); // contoh: "20268CLSX2025"
		//echo $ex.'<br>';
		// ambil angka depan sebelum CLS
		if (preg_match('/^(\d+)/', $ex, $match)) {
		    $last_number = (int)$match[1];
		} else {
		    $last_number = 0;
		}

		//echo $last_number.'<br>';
		
		$a = $last_number + 1;
		
		//echo $a.'<br>';
		$b = 'CLS';
		$c = array('', 'I','II','III','IV','V','VI','VII','VIII','IX','X','XI','XII');
		$d = date('Y');

		// format baru tanpa slash
		$no_surat = $a . $b . $c[date('n')] . $d;

		//echo $no_surat;
		//echo $no_surat;
		//echo "update invoice set status='final', nomor='".$no_surat."' where id_invoice='".$_GET['idinvoice']."'";
		$update = mysqli_query($con,"update invoice set status='final', nomor='".$no_surat."', nomor_dibuat=now(),subtotal='".$subtotal."',ppn_subtotal='".$ppn."', inv_asuransi='".$asuransi."' , biaya_tambahan='".$tambahan."' , total_all='".$total_semua."' where id_invoice='".$_GET['idinvoice']."'");

		//$harga = $_POST['harga_pengiriman'];

        $cari = "select
                x . *,
                substring_index (substring_index(rate_customer,'~',1), ',', -1) as id_rate_customer,
						substring_index (substring_index(rate_customer,'~',3), '~', -1) as min_kg,
                        substring_index (substring_index(rate_customer,'~',3), '~', -1) as min_kgg,
                        substring_index (substring_index(rate_customer,'~',6), '~', -1) as next_kg,
                        substring_index (substring_index(rate_customer,'~',2), '~', -1) as harga_perkg,
                        substring_index (substring_index(rate_customer,'~',5), '~', -1) as min_kgg_2,
                        substring_index (substring_index(rate_customer,'~',4), '~', -1) as harga_perkgg_2,
                if((select berat_real) >= (select volume),
                    (select berat_real),
                    (select volume)) as berat,
                if((select min_kgg) >= (select berat),
                    (select min_kgg),
                    (select berat)) as berat_finish,
                case
                    when
                        kode_kustomer = 'DHL'
                            and (select next_kg) > 0
                    then
                        ((select harga_perkg) + ((select next_kg) * ((select berat_finish) - 1))) + biaya_tambahan
                    else ((select harga_perkg) * (select berat_finish)) + biaya_tambahan
                end as total_new,
                ifnull(harga_pengiriman, (select total_new)) as total_fix,
                ifnull(harga_kunci, (select total_fix)) as harga_lock
            from
                (SELECT
                    id_pengiriman,
                        CASE
                            WHEN airwaybill_kode LIKE '001-%' THEN 0
                            WHEN airwaybill_kode LIKE '002-%' THEN 1
                        end as intercity,
                        SUBSTRING_INDEX(ukuran_barang, 'x', 1) as length,
                        SUBSTRING_INDEX(SUBSTRING_INDEX(ukuran_barang, 'x', 2), 'x', - 1) as width,
                        SUBSTRING_INDEX(ukuran_barang, 'x', - 1) as height,
                        case
                            when upper(cs.kode_kustomer) in ('CIS' , 'DEL') then if((select intercity) = 1, ((select length) * (select width) * (select height) / 4000), if(a.service = 5, ((select length) * (select width) * (select height) / 4000), ((select length) * (select width) * (select height) / 6000)))
                            else if((select intercity) = 1, ceiling((select length) * (select width) * (select height) / 4000), if(a.service = 5, ceiling((select length) * (select width) * (select height) / 4000), ceiling((select length) * (select width) * (select height) / 6000)))
                        end as volume,
                        if(berat_aktual is null or berat_aktual < 1, berat_barang, berat_aktual) as berat_real,
                        a.biaya_tambahan,
                        a.harga_pengiriman,
                        a.harga_kunci,
                        cs.kode_kustomer,
                        getRateCustomer2(a.id_kustomer, a.id_kecamatan_asal, a.lokasi_asal, a.lokasi_tujuan, a.id_kota_asal, a.id_kota_tujuan, a.id_kecamatan_tujuan, a.service, a.komoditi, a.loc_code) as rate_customer
                FROM
                    pengiriman a
                left join kustomer cs ON cs.id_kustomer = a.id_kustomer
                left join pelayanan pel ON pel.id_pelayanan = a.service
                LEFT JOIN negara f ON f.id_negara = a.lokasi_tujuan
                    and a.service = 6
                where
                    a.id_invoice = '".$_GET['idinvoice']."' and a.status != 7
                group by a.id_pengiriman) x";

        $sql = $mysqli->query($cari);
        while($row = $sql->fetch_assoc()){
            if ($row["harga_lock"] != "") {
                $mysqli->query("UPDATE pengiriman set harga_kunci=" . $row["harga_lock"] . " WHERE id_pengiriman=" . $row["id_pengiriman"]);
            }
        }
		//foreach ($harga as $h => $value) {
			//$mysqli->query("UPDATE pengiriman set harga_kunci=$value WHERE id_pengiriman=$h");
		//}


		//$mysqli->query("UPDATE pengiriman set id_invoice=$id_invoice WHERE tanggal BETWEEN '$dari' AND '$sampai' AND id_kustomer='$id_kustomer' AND service in ($id_pelayanan) AND (id_invoice = 0 or id_invoice is null)");

		header('Location: ' . $_SERVER['HTTP_REFERER']);
	}
	elseif ($module=='invoice-customer' AND $act=='kunci-harga') {

		$id = $_POST['textid_pengiriman'];
		$harga = $_POST['harga_pengiriman'];
		foreach ($harga as $h => $value) {
			$mysqli->query("UPDATE pengiriman set harga_kunci=$value WHERE id_pengiriman=$h");
		}

		header('Location: ' . $_SERVER['HTTP_REFERER']);

	}
	elseif($module=='invoice-customer' AND $act=='loccode'){
		$id_kustomer = $_POST['id_kustomer'];
		//cek
		$result = mysqli_query($con, "SELECT id_cabang, nama_kustomer, alamat, kota, loc_code, x_intercity(kota) as intercityasal FROM kustomer WHERE id_kustomer = '$id_kustomer' ");
		$row->cek = mysqli_fetch_assoc($result);
		//ambil loc code
		$q = mysqli_query($con, "SELECT loc_code FROM pengiriman WHERE id_kustomer = '$id_kustomer' and tanggal BETWEEN (CURRENT_DATE() - INTERVAL 2 MONTH) AND CURRENT_DATE() group by loc_code");

		$row->loccode = array();
		while($r=$q->fetch_assoc()){
			$row->loccode[] = $r['loc_code'];
		}
		//$row->test = array('a' => 1);
		echo json_encode($row);

	// pengirim
	}

	elseif($module=='invoice-customer' AND $act=='choose_shipment'){
		$id_kustomer = $_POST['id_kustomer'];

		$result = mysqli_query($con, "SELECT all_awb FROM kustomer WHERE id_kustomer = '$id_kustomer' ");
		$row_cek = mysqli_fetch_assoc($result);
		
		if($row_cek['all_awb'] == 'Y'){
			$q = mysqli_query($con, "SELECT airwaybill_kode, id_pengiriman FROM pengiriman where  tanggal BETWEEN (CURRENT_DATE() - INTERVAL 6 MONTH) AND CURRENT_DATE() and (id_invoice is null or id_invoice = 0) AND biaya_beban = 4 ORDER BY id_pengiriman");
		}else{
			$q = mysqli_query($con, "SELECT airwaybill_kode, id_pengiriman FROM pengiriman where id_kustomer= '$id_kustomer' and tanggal BETWEEN (CURRENT_DATE() - INTERVAL 6 MONTH) AND CURRENT_DATE() and (id_invoice is null or id_invoice = 0) AND biaya_beban != 4 ORDER BY id_pengiriman");
		}



		

		$row = array();
		while($r=$q->fetch_assoc()){
			$rows[] = $r;
		}
		//$row->test = array('a' => 1);
		echo json_encode($rows);

	// pengirim
	}

	elseif ($module=='invoice-customer' AND $act=='tagih_ke') {

    $id_kustomer = mysqli_real_escape_string($con, $_POST['id_kustomer']);

    $result = mysqli_query($con, "
        SELECT 
            a.id_kustomer_tagih, 
            b.nama_kustomer 
        FROM kustomer_tagihan a
        JOIN kustomer b 
            ON a.id_kustomer_tagih = b.id_kustomer
        WHERE a.id_kustomer = '$id_kustomer'
        ORDER BY b.nama_kustomer ASC
    ");

    $rows = array();

    while ($r = mysqli_fetch_assoc($result)) {
        $rows[] = $r;
    }

    echo json_encode($rows);
    exit;
}
	elseif($module=='invoice-customer' AND $act=='edit-duedate'){
		$id = $_POST['id_invoice'];
		$date = $_POST['tanggal'];
		$date		= date('Y-m-d', strtotime($date));
		$mysqli->query("update invoice set due_date='$date' where id_invoice=$id");
		//	echo "update invoice set due_date='$date' where id_invoice=$id";
		$key			= "asdf$1011";
		$id_inv				= base64_encrypt($id,$key);
		header('location:../../main.php?module='.$module.'&act=update&id='.$id_inv);

	}
	elseif($module=='invoice-customer' AND $act=='edit-dateinv'){
		$id = $_POST['id_invoice'];
		$date = $_POST['tanggal'];
		$date		= date('Y-m-d', strtotime($date));
		$mysqli->query("update invoice set tanggal='$date' where id_invoice=$id");
		//	echo "update invoice set due_date='$date' where id_invoice=$id";
		$key			= "asdf$1011";
		$id_inv				= base64_encrypt($id,$key);
		header('location:../../main.php?module='.$module.'&act=update&id='.$id_inv);

	}
	elseif($module=='invoice-customer' AND $act=='edit-subtotaljudul'){
		$id = $_POST['id_invoice'];
		$judul = $_POST['subtotaljudul'];

		$mysqli->query("update invoice set judul_subtotal='$judul' where id_invoice=$id");

		$key			= "asdf$1011";
		$id_inv				= base64_encrypt($id,$key);
		header('location:../../main.php?module='.$module.'&act=update&id='.$id_inv);

	}
	elseif($module=='invoice-customer' AND $act=='edit-judulinv'){
		$id = $_POST['id_invoice'];
		$judul = $_POST['judulinv'];

		$mysqli->query("update invoice set judul_awal='$judul' where id_invoice=$id");

		$key			= "asdf$1011";
		$id_inv				= base64_encrypt($id,$key);
		header('location:../../main.php?module='.$module.'&act=update&id='.$id_inv);

	}

	elseif($module=='invoice-customer' AND $act=='edit-noinv'){
		$id = $_POST['id_invoice'];
		$no = $_POST['noinv'];

		$mysqli->query("update invoice set nomor='$no' where id_invoice=$id");

		$key			= "asdf$1011";
		$id_inv				= base64_encrypt($id,$key);
		header('location:../../main.php?module='.$module.'&act=update&id='.$id_inv);

	}
	elseif($module=='invoice-customer' AND $act=='edit-ketinv'){
		$id = $_POST['id_invoice'];
		$ket = $_POST['ketinv'];

		$mysqli->query("update invoice set keterangan_no_invoice='$ket' where id_invoice=$id");

		$key			= "asdf$1011";
		$id_inv				= base64_encrypt($id,$key);
		header('location:../../main.php?module='.$module.'&act=update&id='.$id_inv);

	}
	elseif($module=='invoice-customer' AND $act=='edit-alamat'){
		$id = $_POST['id_invoice'];
		$alamat = $_POST['judul_alamat'];

		$mysqli->query("update invoice set custom_judul_alamat='$alamat' where id_invoice=$id");

		$key			= "asdf$1011";
		$id_inv				= base64_encrypt($id,$key);
		header('location:../../main.php?module='.$module.'&act=update&id='.$id_inv);

	}

	elseif($module=='invoice-customer' AND $act=='keluarkanawb'){
		$awb		= $_POST['deleteawb'];
		$id_awb		= implode(',',$awb);

		$mysqli->query("update pengiriman set id_invoice=null where id_pengiriman in ($id_awb)");

		header('Location: ' . $_SERVER['HTTP_REFERER']);
	}

	elseif($module=='invoice-customer' AND $act=='tagihkan-baru'){
		$awb			= $_POST['tagihkan_baru'];
		$id_kustomer	= $_POST['id_kustomer'];
		$id_awb	= implode(',',$awb);

		if ($awb != ''){
			$mysqli->query("INSERT into invoice (id_invoice,nomor,tanggal,id_kustomer,id_pelayanan,dari,sampai,loc_code,komoditi,bank,due_date,judul_subtotal,judul_awal,method,ppn,status,lunas,id_user,nomor_dibuat,created_date,last_update) values('',null,now(),'$id_kustomer','','','','','1,2','1','now()',null,'Judul Invoice',1,1,'draft','N',5,null,now(),null)");
			$id_invoice = $mysqli->insert_id;
			$key 			= "asdf$1011";
			$id			= base64_encrypt($id_invoice,$key);

			$mysqli->query("update pengiriman set id_invoice='$id_invoice' where id_pengiriman in ($id_awb)");


			header('location:../../main.php?module=invoice-customer&act=update&id='.$id);
		} else{
			header('Location: ' . $_SERVER['HTTP_REFERER']);
		}

	}

	elseif($module=='invoice-customer' AND $act=='addawb'){

		$add_pengiriman = $_POST['add_pengiriman'];
		$awbs = implode(',',$add_pengiriman);
		$id_invoice = $_POST['id_invoice'];

		//echo "update pengiriman set id_invoice='$id_invoice' where id_pengiriman in ($awbs)";
		$mysqli->query("update pengiriman set id_invoice='$id_invoice' where id_pengiriman in ($awbs)");

		header('Location: ' . $_SERVER['HTTP_REFERER']);

	}
	elseif($module=='invoice-customer' AND $act=='addawbbulk'){

		$add_pengiriman = $_POST['id_pengiriman_bulk'];
		$awbs = implode(',',$add_pengiriman);
		$id_invoice = $_POST['id_invoice_bulk'];

		//echo "update pengiriman set id_invoice='$id_invoice' where id_pengiriman in ($awbs)";
		$result = $mysqli->query("update pengiriman set id_invoice='$id_invoice' where id_pengiriman in ($awbs)");

		echo $result;
		//header('Location: ' . $_SERVER['HTTP_REFERER']);

	}

	elseif($module=='invoice-customer' AND $act=='edit-ppn'){
		$id = $_POST['id_invoice'];
		$ppn = $_POST['ppn'];
		$keteranganppn = $_POST['keteranganppn'];



		$mysqli->query("update invoice set ppn='$ppn', keterangan_ppn = '$keteranganppn' where id_invoice=$id");

		//$key			= "asdf$1011";
		//$id_inv				= base64_encrypt($id,$key);
		header('Location: ' . $_SERVER['HTTP_REFERER']);

	}
	elseif($module=='invoice-customer' AND $act=='edit-bank'){
		$id = $_GET['id_invoice'];
		//$ppn = $_POST['ppn'];
		$bank = $_GET['bank'];
		if($bank == 1){
			$id_bank = 2;
		}else{
			$id_bank = 1;
		}
		//echo "update invoice set bank='$id_bank' where id_invoice=$id";
		$mysqli->query("update invoice set bank='$id_bank' where id_invoice=$id");

		//$key			= "asdf$1011";
		//$id_inv				= base64_encrypt($id,$key);
		header('Location: ' . $_SERVER['HTTP_REFERER']);

	}

	elseif($module=='invoice-customer' AND $act=='edit-type-invoice'){
		$id = $_GET['id_invoice'];
		//$ppn = $_POST['ppn'];
		$type_invoice = $_GET['type_invoice'];
		if($type_invoice == "monthly"){
			$type = "OR";
		}else{
			$type = "monthly";
		}
		//echo "update invoice set bank='$id_bank' where id_invoice=$id";
		if($id != ""){
			$mysqli->query("update invoice set type_invoice='$type' where id_invoice=$id");
		}
		

		//$key			= "asdf$1011";
		//$id_inv				= base64_encrypt($id,$key);
		header('Location: ' . $_SERVER['HTTP_REFERER']);

	}
	elseif($module=='invoice-customer' AND $act=='edit-tampil'){
		$id = $_GET['id_invoice'];
		//$ppn = $_POST['ppn'];
		$tampil = $_GET['tampil'];
		if($tampil == 'Y'){
			$update_tampil = 'N';
		}else{
			$update_tampil = 'Y';
		}
		//echo "update invoice set bank='$id_bank' where id_invoice=$id";
		$mysqli->query("update invoice set tampil_nama_kustomer='$update_tampil' where id_invoice=$id");

		//$key			= "asdf$1011";
		//$id_inv				= base64_encrypt($id,$key);
		header('Location: ' . $_SERVER['HTTP_REFERER']);

	}
	elseif($module=='invoice-customer' AND $act=='edit-tampil-ppn'){
		

		$id = $_GET['id_invoice'];
		
		$key			= "asdf$1011";
		$id_inv				= base64_decrypt($id,$key);

		//$ppn = $_POST['ppn'];
		$tampil = $_GET['tampil_ppn'];
		if($tampil == 'Y'){
			$update_tampil = 'N';
		}else{
			$update_tampil = 'Y';
		}
		
		//echo "update invoice set tampil_ppn='$update_tampil' where id_invoice=$id_inv";
		$mysqli->query("update invoice set tampil_ppn='$update_tampil' where id_invoice=$id_inv");

		//$key			= "asdf$1011";
		//$id_inv				= base64_encrypt($id,$key);
		header('Location: ' . $_SERVER['HTTP_REFERER']);

	}
	elseif($module=='invoice-customer' AND $act=='send'){
		$id = $_GET['id'];
		$stts = $_GET['stts'];

		$key 		= "asdf$1011";
        $user 		= $_SESSION['id_user'];
        $id_user 	= base64_decrypt($user,$key);
        $id_invoice = base64_decrypt($id,$key);

        if ($stts == 1){
            $sql = "INSERT into invoice_send (invoice_type, invoice_id, send_by, send_dtm) values ('CUSTOMER',$id_invoice,$id_user,now())";
        }else{
            $sql = "update invoice_send set invoice_type = 'CUSTOMER_ABORT', abort_by = $id_user, abort_dtm = now() where invoice_id = $id_invoice and invoice_type = 'CUSTOMER'";
        }

        $mysqli->query($sql);
        header('Location: ' . $_SERVER['HTTP_REFERER']);

	}elseif ($module=='invoice-customer' AND $act=='form-harga-kg')
	{
		$id = $_POST['id'];
		$rate_id = $_POST['rate_id'];
		$asal = $_POST['asal'];
		$tujuan = $_POST['tujuan'];
		 $q = "SELECT
					a.id_kustomer,
					airwaybill_kode,
					if(komoditi = 1, 'paket', 'dokumen') as comodity,
					x_service(service) as service_name,
					a.loc_code,
					harga_pengiriman,
					berat_aktual,
					a.komoditi,
					a.service
				from
					pengiriman a
				where
					id_pengiriman = $id";

		$query = $mysqli->query($q);
		$r = $query->fetch_assoc();

		if ($rate_id){
			$qr = "SELECT *
					from
						rate_customer
					where
						id = $rate_id";

			$queryR = $mysqli->query($qr);
			$rr = $queryR->fetch_assoc();
		}else{
			$rr = array(
				'min_kg' => 0,
				'harga' => 0,
				'next_kg' => 0,
				'lead_time' => 0
			);
		}

		//<form action="'.$aksi.'?module=invoice-customer&act=updateharga" method="POST">
		echo'
		<form action="'.$aksi.'?module=invoice-customer&act=update-harga-kg" method="POST">
			<div class="form-group" style="padding-bottom: 25px;">
				<label class="col-md-3 control-label">Customer</label>
				<div class="col-md-8">
					<input type="text" value="'.$_POST['kustomer'].'" class="form-control" readonly>
				</div>
			</div>
			<div class="form-group" style="padding-bottom: 25px;">
				<label class="col-md-3 control-label">Komoditi</label>
				<div class="col-md-8">
					<input type="text" value="'.$r['comodity'].'" class="form-control" readonly>
				</div>
			</div>
			<div class="form-group" style="padding-bottom: 25px;">
				<label class="col-md-3 control-label">Service</label>
				<div class="col-md-8">
					<input type="text" value="'.$r['service_name'].'" class="form-control" readonly>
				</div>
			</div>
			<div class="form-group" style="padding-bottom: 25px;">
				<label class="col-md-3 control-label">Loc Code</label>
				<div class="col-md-8">
					<input type="text" value="'.$r['loc_code'].'" name="loc_code" class="form-control" readonly>
				</div>
			</div>
			<div class="form-group" style="padding-bottom: 25px;">
				<label class="col-md-3 control-label">Lokasi Asal</label>
				<div class="col-md-8">
					<input type="text" value="'.$asal.'" class="form-control" readonly>
				</div>
			</div>
			<div class="form-group" style="padding-bottom: 25px;">
				<label class="col-md-3 control-label">Lokasi Tujuan</label>
				<div class="col-md-8">
					<input type="text" value="'.$tujuan.'" class="form-control" readonly>
				</div>
			</div>

			<div class="form-group" style="padding-bottom: 25px;">
				<label class="col-md-3 control-label">Min KG</label>
				<div class="col-md-8">
					<input type="number" value="'.$rr['min_kg'].'" name="min_kg" class="form-control" min="1">
				</div>
			</div>
			<div class="form-group" style="padding-bottom: 25px;">
				<label class="col-md-3 control-label">Harga/KG:</label>
				<div class="col-md-8">
					<input type="number" name="harga" value="'.($rr['harga'] === null ? 0 : $rr['harga']).'" class="form-control"   />
				</div>
			</div>
			<div class="form-group" style="padding-bottom: 25px;">
				<label class="col-md-3 control-label">Harga Next KG:</label>
				<div class="col-md-8">
					<input type="number" name="harga_next" value="'.($rr['next_kg'] === null ? 0 : $rr['next_kg']).'" class="form-control"   />
				</div>
			</div>
			<div class="form-group" style="padding-bottom: 25px;">
				<label class="col-md-3 control-label">Lead Time:</label>
				<div class="col-md-8">
					<input type="number" name="lead_time" value="'.$rr['lead_time'].'" class="form-control"   />
				</div>
			</div>
			<input type="hidden" name="id_pengiriman" class="form-control" value="'.$id.'" />
			<input type="hidden" name="id_rate" class="form-control" value="'.$rate_id.'" />
			<input type="hidden" name="lokasi_asal" class="form-control" value="'.$_POST['kec_asal'].'" />
			<input type="hidden" name="lokasi_tujuan" class="form-control" value="'.$_POST['kec_tujuan'].'" />
			<input type="hidden" name="id_kustomer" class="form-control" value="'.$r['id_kustomer'].'" />
			<input type="hidden" name="service" class="form-control" value="'.$r['service'].'" />
			<input type="hidden" name="komoditi" class="form-control" value="'.$r['komoditi'].'" />
			<div class="modal-footer">
				<button class="btn btn-success" type="submit">Update</button>
				<button type="reset" class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Cancel</button>
			</div>
		</form>';

	}elseif ($module=='invoice-customer' AND $act=='update-harga-kg')
	{
		$id_pengiriman	= $mysqli->real_escape_string($_POST['id_pengiriman']);
		$id_kustomer	= $mysqli->real_escape_string($_POST['id_kustomer']);
		$id_rate	= $mysqli->real_escape_string($_POST['id_rate']);
		$harga				= $mysqli->real_escape_string($_POST['harga']);
		$harga_next				= $mysqli->real_escape_string($_POST['harga_next']);
		$min_kg				= $mysqli->real_escape_string($_POST['min_kg']);
		$lead_time				= $mysqli->real_escape_string($_POST['lead_time']);
		$lokasi_asal				= $mysqli->real_escape_string($_POST['lokasi_asal']);
		$lokasi_tujuan				= $mysqli->real_escape_string($_POST['lokasi_tujuan']);
		$service				= $mysqli->real_escape_string($_POST['service']);
		$komoditi				= $mysqli->real_escape_string($_POST['komoditi']);
		$loc_code				= $mysqli->real_escape_string($_POST['loc_code']);

		if ($id_rate){

			$sql = "update rate_customer set harga = '$harga', min_kg = '$min_kg' ";

			if ($harga_next == 0 || $harga_next == '') $sql .= ", next_kg = null ";
			else $sql .= ", next_kg = '$harga_next' ";

			if ($lead_time == 0 || $lead_time == '') $sql .= ", lead_time = null ";
			else $sql .= ", lead_time = '$lead_time' ";

			$sql .= " where id='$id_rate' ";

		}else{

			$harga_next = ($harga_next == 0 || $harga_next == '') ? 'null' : $harga_next;
			$lead_time = ($lead_time == 0 || $lead_time == '') ? 'null' : $lead_time;
			$loc_code = ($loc_code == 0 || $loc_code == '') ? 'null' : "'".$loc_code."'";

			$sql = "insert into rate_customer (id_kustomer, id_lokasi_asal, id_lokasi_tujuan, id_pelayanan, komoditi, min_kg, harga, next_kg, lead_time, loc_code)
					values ($id_kustomer, $lokasi_asal, $lokasi_tujuan, $service, $komoditi, $min_kg, $harga, $harga_next, $lead_time, $loc_code)";

		}

		$result			= $mysqli->query($sql);

		if ($result)
		{
			$_SESSION['pesan'] = 'success';
		}
		else
		{
			$_SESSION['pesan'] = 'failed';
		}

		header('Location: ' . $_SERVER['HTTP_REFERER']);

	}

	elseif ($module=='invoice-customer' AND $act=='test')
	{
		$id_awal = $_GET['awal'];
		$id_akhir = $_GET['akhir'];

		for($i=$id_awal; $i<=$id_akhir;$i++){



			$id_inv = $i;

			$kena_ppn = $mysqli->query("select ppn,nomor from invoice where id_invoice='".$id_inv."'");

	        $kena = $kena_ppn->fetch_assoc();
	        $ketemu= $kena_ppn->num_rows;

	        if($ketemu > 0){
				$cari = "select x.*, substring_index (substring_index(rate_customer,'~',1), ',', -1) as id_rate_customer,
								substring_index (substring_index(rate_customer,'~',3), '~', -1) as min_kg,
		                        substring_index (substring_index(rate_customer,'~',3), '~', -1) as min_kgg,
		                        substring_index (substring_index(rate_customer,'~',6), '~', -1) as next_kg,
		                        substring_index (substring_index(rate_customer,'~',2), '~', -1) as harga_perkg,
		                        substring_index (substring_index(rate_customer,'~',5), '~', -1) as min_kgg_2,
		                        substring_index (substring_index(rate_customer,'~',4), '~', -1) as harga_perkgg_2,
		                        if((select berat_real) >= (select volume),
		                            (select berat_real),
		                            (select volume)) as berat,
		                        if((select min_kgg) >= (select berat),
		                            (select min_kgg),
		                            (select berat)) as berat_finish,
		                         case when kode_kustomer = 'DHL' and (select next_kg) > 0 then
		                          ((select harga_perkg) + ((select next_kg) * ((select berat_finish)-1))) + biaya_tambahan
		                         else
		                          ((select harga_perkg) * (select berat_finish)) + biaya_tambahan
		                         end as total_new,
		                        ifnull(harga_pengiriman, (select total_new)) as total_fix,
		                        ifnull(harga_kunci, (select total_fix)) as harga_lock,
		                        if(if(berat_aktual is null or berat_aktual < 1,
		                                berat_barang,
		                                berat_aktual) < (select min_kg),
		                            ((select min_kg) * (select harga_perkg)) + biaya_packing + biaya_asuransi,
		                            (if(berat_aktual is null or berat_aktual < 1,
		                                berat_barang,
		                                berat_aktual) * (select harga_perkg)) + biaya_packing + biaya_asuransi) as jumlah_harga
										
		                        from (	SELECT
		                        a.id_pengiriman,
		                        date(pickup_date) as tanggal,
		                        airwaybill_kode,
		                       
		                     
		                       
								
		                        
		                       
								
		                        
		                       
		                        a.lokasi_asal as id_lokasi_asal,
		                        
		                        id_kecamatan_asal,
		                        
		                       
		                        a.id_kecamatan_tujuan,
														
		                       
								a.service as service_id,
								a.lokasi_tujuan as id_lokasi_tujuan,
		                        
		                       
		                        CASE
		                            WHEN airwaybill_kode LIKE '001-%' THEN 0
		                            WHEN airwaybill_kode LIKE '002-%' THEN 1
		                        end as intercity,
		                        SUBSTRING_INDEX(ukuran_barang, 'x', 1) as length,
		                        SUBSTRING_INDEX(SUBSTRING_INDEX(ukuran_barang, 'x', 2),
		                                'x',
		                                - 1) as width,
		                        SUBSTRING_INDEX(ukuran_barang, 'x', - 1) as height,
		                        case when upper(cs.kode_kustomer) in ('CIS','DEL') then
		                          if((select intercity) = 1,
		                            ((select length) * (select width) * (select height) / 4000),
		                            if(a.service = 5,
		                                ((select length) * (select width) * (select height) / 4000),
		                                ((select length) * (select width) * (select height) / 6000)))
		                        else
		                            if((select intercity) = 1,
		                                ceiling((select length) * (select width) * (select height) / 4000),
		                                if(a.service = 5,
		                                    ceiling((select length) * (select width) * (select height) / 4000),
		                                    ceiling((select length) * (select width) * (select height) / 6000)))
		                        end as volume,
		                        if(berat_aktual is null or berat_aktual < 1,
		                            berat_barang,
		                            berat_aktual) as berat_real,
		                        case
		                            when packing_kayu = '1' then 'Packing Oleh Pemilik'
		                            when packing_kayu = '2' then 'Packing Kayu Oleh Cesco Log.'
		                            when packing_kayu = '3' then 'Non Packing Kayu Oleh Cesco Log.'
		                        end as packing_kayu,
							
		                        a.asuransi,
								a.berat_aktual,
								a.berat_barang,
								if(a.asuransi = 'Y',
		                            if(a.nilai_barang >= 6000000,
		                                150000,
		                                0.25 / 100 * a.nilai_barang),
		                            0) as nilai_asuransi,
		                        a.biaya_packing,
								if(a.packing_kayu = 2,
		                            if(a.biaya_packing < 1,
		                                if(a.berat_aktual is null
		                                        or a.berat_aktual < 1,
		                                    a.berat_barang,
		                                    a.berat_aktual) * 2500,
		                                a.biaya_packing),
		                            0) as packing,
		                        a.biaya_asuransi,
		                        a.biaya_tambahan,
		                     
								a.harga_pengiriman,
		                        a.harga_kunci,
		                        cs.kode_kustomer,


		                        
								
								getRateCustomer2(a.id_kustomer, a.id_kecamatan_asal, a.lokasi_asal, a.lokasi_tujuan, a.id_kota_asal, a.id_kota_tujuan, a.id_kecamatan_tujuan, a.service, a.komoditi, a.loc_code) as rate_customer

								

		                    FROM
		                        pengiriman a
								
								left join kustomer cs on cs.id_kustomer = a.id_kustomer
								
		                        
		                    where
		                        a.id_invoice = $id_inv and a.status != 7
		                    group by a.id_pengiriman
		                  ) x";

				

				$sql = $mysqli->query($cari);
				$sum_total=0;
				
				$sum_packing =0;
				$sum_asuransi=0;

				while($row = $sql->fetch_assoc()){
					$harga_lock = ($row['kode_kustomer'] == 'CIS' || $row['kode_kustomer'] == 'DEL') ? round($row['harga_lock'],2) : round($row['harga_lock']);
					$sum_total = $sum_total+$harga_lock;
					$harga_total = $harga_lock+$row['biaya_packing']+$row['biaya_asuransi'];
					$sum_packing = $sum_packing+$row['biaya_packing'];
					$sum_asuransi = $sum_asuransi+$row['biaya_asuransi'];
				}
				
				$qsum = "select sum(biaya_tambahan) as sum_biaya_tambahan from invoice_detail where id_invoice='".$id_inv."'";
		//              					$sum_bt = mysqli_query($con, $qsum);
		//              					$sbt = mysqli_fetch_assoc($sum_bt);

		                                $sum_bt = $mysqli->query($qsum);
		                                $biayaTambahan = 0;
		                                while($sbt = $sum_bt->fetch_assoc()){
		                                    $biayaTambahan = $sbt['sum_biaya_tambahan'];
		                                }


										$qp = "select sum(biaya_tambahan) as pajak from invoice_detail where id_invoice='".$id_inv."' and pajak='Ya'";
		//              					$pajak = mysqli_query($con, $qp);
		//              					$p = mysqli_fetch_assoc($pajak);

		                                $pajak = $mysqli->query($qp);
		                                $tambahan_kena_pajak = 0;
		                                while($p = $pajak->fetch_assoc()){
		                                    $tambahan_kena_pajak = $p['pajak'];
		                                }

		                                

		                                $ppn = $kena['ppn'];


		              					$pajak1 = $ppn/100*($tambahan_kena_pajak+$sum_total);

										$test_pajak = floor($ppn/100* ($sum_total+$tambahan_kena_pajak));

										$qp10 = "select sum(biaya_tambahan) as pajak from invoice_detail where id_invoice='".$id_inv."' and pajak='10'";

		                                $pajak10 = $mysqli->query($qp10);
		                                $tambahan_kena_pajak10 = 0;
		                                while($p10 = $pajak10->fetch_assoc()){
		                                    $tambahan_kena_pajak10 = $p10['pajak'];
		                                }

		              					$pajak_packing = floor(10/100*($sum_packing+$tambahan_kena_pajak10));
		              					$total_semua = $sum_total+$biayaTambahan+$test_pajak+$sum_packing+$pajak_packing+$sum_asuransi;

		        //declare koma number format
		        


		        //echo

		        echo $kena['nomor'].'<br><br>';

		        
				echo number_format($sum_total).'<br><br>';

				
				echo number_format($test_pajak).'<br><br>';

				$qt = "select * from invoice_detail where id_invoice='".$id_inv."'";

		                            $tambahan = $mysqli->query($qt);

		                            while($t = $tambahan->fetch_assoc()){


		              				echo number_format($t['biaya_tambahan']). '<br>';
		              				}
		        
		        echo number_format($sum_packing).'<br><br>'.
		        
		        
		        number_format($pajak_packing).'<br><br>'.

		        
		        number_format($sum_asuransi).'<br><br>'.

		        
		        number_format($total_semua).'<br><br>';

		              					echo '----------------------------------'.'<br><br>';


		        

		         $mysqli->query("update invoice set subtotal = '$sum_total', ppn_subtotal='$test_pajak', inv_packing_kayu = '$sum_packing',ppn_packing='$pajak_packing',inv_asuransi='$sum_asuransi',total_all='$total_semua'  where id_invoice='".$i."'");
		    }
	    }
	}elseif($module=='invoice-customer' and $act=='bayar'){

		$id_invoice = $_POST['id_invoice'];
		$tanggal = $mysqli->real_escape_string(jin_date_sql($_POST['tanggal']));
		$nilai = $_POST['nilai'];
		$keterangan = $_POST['keterangan'];

		$mysqli->query("UPDATE invoice set jumlah_bayar=$nilai, tanggal_bayar = '$tanggal',
					ket_bayar = '$keterangan' 		
		WHERE id_invoice = $id_invoice");

		header('location:../../main.php?module=invoice-customer');

	}elseif($module=='invoice-customer' and $act=='detail-bayar'){

		$id_invoice = $_POST['id_invoice'];

		//echo "select tanggal_bayar, jumlah_bayar, ket_bayar from invoice WHERE id_invoice = $id_invoice";

        $query = mysqli_query($con, "select tanggal_bayar, jumlah_bayar, ket_bayar from invoice WHERE id_invoice = $id_invoice");
        $r = mysqli_fetch_assoc($query);

        echo json_encode($r);

	}elseif($module=='invoice-customer' and $act=='delete-biayatambahan'){
		//echo $_POST['id'];
		
		$mysqli->query("delete from invoice_detail  where id=$_POST[id]");

		header('Location: ' . $_SERVER['HTTP_REFERER']);	
	}

	elseif ($module=='invoice-customer' AND $act=='cek_inv'){
		$no_inv = $_POST['inv'];

		$result = mysqli_query($con, "SELECT id_invoice,nomor from invoice where nomor='".$no_inv."' ");

		//echo "SELECT id_pengiriman from pengiriman where airwaybill_number='".$no_awb."' and status != 7 ";

		$r = mysqli_fetch_assoc($result);

		$ketemu	= mysqli_num_rows($result);

		if($ketemu > 0){
			
				echo json_encode(array("success"=>0,"message"=> "Nomor Invoice sudah ada, silahkan pakai nomor lain"));
			
			
		}else{
			echo json_encode(array("success"=>1,"message"=> "Nomor invoice belum ada, silahakan lanjut klik edit"));
		}
		

		

		
	}

	elseif ($module=='invoice-customer' AND $act=='search_jenisbarang'){
		$jenis_barang = $_POST['jenis'];

		$result = mysqli_query($con, "SELECT jenis_barang, berat_aktual, collie FROM pengiriman WHERE jenis_barang ='".$jenis_barang."' and berat_aktual > 0 and collie > 0 GROUP BY jenis_barang, berat_aktual, collie order by created_date DESC");

		 


		

		

		$ketemu	= mysqli_num_rows($result);
		echo '<table id="searchjenisbarang" class="table table-bordered table-striped table-hover" width="100%" cellspacing="0" style="white-space:nowrap;">';
		echo '
					<tr>
						<th>JENIS BARANG</td>
						<th>KOLI</td>
						<th>BERAT AKTUAL</td>
					</tr>
						';		
		if ($ketemu > 0){
			while ($data=mysqli_fetch_assoc($result)){											
					echo '
					<tr>
						<td><center>'.$data['jenis_barang'].'</center></td>
						<td><center>'.$data['collie'].'</center></td>
						<td><center>'.$data['berat_aktual'].'</center></td>
					</tr>
						';				
			}
		}
		else{
			echo'
			<td colspan="2">
				<center><b>DATA NOT FOUND</b></center>
			</td>';
		}
		

		echo '</table>';

		
	}

	elseif($module=='invoice-customer' and $act=='searchawb'){
		//mysqli_query($con, "update rate_agent_cross set min_kg='$_POST[value]' where rate_id='$_POST[pk]'");
		$q=mysqli_real_escape_string($con,$_POST['q']);


		$query=mysqli_query($con," select id_invoice,airwaybill_kode


			


			from pengiriman
		where airwaybill_kode = '".$q."'  and status !=7
		");
		

		$row=mysqli_num_rows($query);

		if ($row > 0){
			while ($data=mysqli_fetch_assoc($query)){
				$key = "asdf$1011";
				$d_invoice = $data['id_invoice'];
				

				echo '

				';
				
					echo '
					<td><center>'.$data['airwaybill_kode'].'</center></td>
					<td><center><a class="btn btn-xs btn-primary" target="_blank" href="?module=invoice-customer&act=update&id='.base64_encrypt($d_invoice,$key).'"><i class="fa fa-search"></i> View </a></center></td>';
				
			}
		}
		else{
			echo'
			<td colspan="2">
				<center><b>AIRWAYBILL NOT FOUND</b></center>
			</td>';
		}
	}
	elseif ($module=='invoice-customer' AND $act=='approval_jika_minus')
	{
		$encrypt	= $_GET['idinvoice'];
		$key		= "asdf$1011";
		$id_invoice	= base64_decrypt($encrypt,$key);
		

		//echo "update invoice set approval_jika_minus = 'Y' where id_invoice='$id_invoice'";


		$mysqli->query("update invoice set approval_jika_minus = 'Y' where id_invoice='$id_invoice'");
		
		

		header('Location: ' . $_SERVER['HTTP_REFERER']);
	}

}
?>
