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
	$key = 'asdf$1011';
	$encrypt = $_SESSION['id_user'];
	$id = base64_decrypt($encrypt,$key);
	$userId = $id;
	$useraktif = mysqli_query($con, 'select * from user where id_user="'.$id.'"');
	$u = mysqli_fetch_assoc($useraktif);
	$aksi="module/mod_finance_invoice-agent/invoice-agent_action.php";

	$level = $u['level'];

	// List paket
	if ($module=='invoice-agent' and $act=='read')
	{
		if($u['level'] == 'admin' || $u['level'] == 'finance' ){


		$table =
<<<EOT
	(
		SELECT a.id_pengiriman,date_format(tanggal,'%Y-%m') as tanggal, b.nama_cabang,b.id_cabang as cabang_id
		,concat(a.delivery_by_agent,'-', DATE_FORMAT(a.tanggal,'%Y-%m')) as delivery,
		concat(a.delivery_by_agent, '~', date_format(tanggal, '%Y-%m'), '~',ifnull(DATE_FORMAT(d.due_date, '%Y-%m-%d'),''),'~',ifnull(d.id,'')) as due_date,
        concat(a.delivery_by_agent, '~', date_format(tanggal, '%Y-%m'), '~',ifnull(DATE_FORMAT(d.paid_date, '%Y-%m-%d'),''),'~',ifnull(d.id,'')) as paid_date,
         s.id as send, status_inv_agent, s.confirm_by as confirm
		FROM pengiriman a

		left JOIN cabang b ON b.id_cabang=a.delivery_by_agent LEFT JOIN cabang c on c.id_cabang = a.pickup_by_agent

		LEFT JOIN invoice_agent_status d ON d.id_agent = a.delivery_by_agent and date_format(d.invoice_date, '%Y-%m') = date_format(a.tanggal, '%Y-%m')

		LEFT OUTER JOIN invoice_send s ON s.agent_id=a.delivery_by_agent and s.agent_date = DATE_FORMAT(a.tanggal,'%Y-%m') and s.invoice_type = 'AGENT'

		where delivery_by_agent is not null GROUP BY date_format(tanggal,'%Y-%m'),delivery_by_agent ORDER BY date_format(tanggal,'%Y-%m')
	) temp
EOT;
	} else{
		$table =
<<<EOT
	(
		SELECT a.id_pengiriman,date_format(a.tanggal,'%Y-%m') as tanggal, b.nama_cabang,b.id_cabang as cabang_id
		,concat(a.delivery_by_agent,'-', DATE_FORMAT(a.tanggal,'%Y-%m')) as delivery,
		concat(a.delivery_by_agent, '~', date_format(tanggal, '%Y-%m'), '~',ifnull(DATE_FORMAT(d.due_date, '%Y-%m-%d'),''),'~',ifnull(d.id,'')) as due_date,
        concat(a.delivery_by_agent, '~', date_format(tanggal, '%Y-%m'), '~',ifnull(DATE_FORMAT(d.paid_date, '%Y-%m-%d'),''),'~',ifnull(d.id,'')) as paid_date,
        s.id as send, status_inv_agent, s.confirm_by as confirm
		FROM pengiriman a left JOIN cabang b ON b.id_cabang=a.delivery_by_agent LEFT JOIN cabang c on c.id_cabang = a.pickup_by_agent
		LEFT JOIN invoice_agent_status d ON d.id_agent = a.delivery_by_agent and date_format(d.invoice_date, '%Y-%m') = date_format(a.tanggal, '%Y-%m')
		JOIN invoice_send s ON s.agent_id=a.delivery_by_agent and s.agent_date = DATE_FORMAT(a.tanggal,'%Y-%m') and s.invoice_type = 'AGENT'
		WHERE delivery_by_agent is NOT null and delivery_by_agent !=39
		and delivery_by_agent='$u[id_cabang]'
		and (status_inv_agent is not null or status_inv_agent != 0)
		GROUP BY date_format(tanggal,'%Y-%m'),delivery_by_agent ORDER BY date_format(tanggal,'%Y-%m')
	) temp
EOT;
	}

		$primaryKey = 'id_pengiriman';
		$columns = array(
			array( 'db' => 'tanggal', 'dt' => 0 ),
			array( 'db' => 'nama_cabang', 'dt' => 1 ),

			array( 'db' => 'due_date', 'dt' => 2, 'formatter' => function($d,$row) {
				$data = explode('~', $d);
                $idCabang = $_SESSION['id_cabang'];

                $dueDate = ($data[2] == '') ? 'Empty' : $data[2];
                if ($idCabang == 39) {
                    $dueDateClass = ($data[2] == '') ? 'editable-click editable-empty' : 'editable editable-click';
                    return '<td><a href="#" id="dueDate' . $data[0] . $data[1] . '" class="open_due_date ' . $dueDateClass . '" data-pk="' . $d . '">' . $dueDate . '</a></td>';
                }else{
                    return '<td>' . $dueDate . '</td>';
                }
			}),

            array( 'db' => 'paid_date', 'dt' => 3, 'formatter' => function($d,$row) {
				$data = explode('~', $d);
                $paidDate = ($data[2] == '') ? 'Empty' : $data[2];
                $paidDateClass = ($data[2] == '') ? 'editable-click editable-empty' : 'editable editable-click';

                $idCabang = $_SESSION['id_cabang'];
                if ($idCabang == 39) {
                    return '<td><a href="#" id="paidDate' . $data[0] . $data[1] . '" class="open_paid_date ' . $paidDateClass . '" data-pk="' . $d . '">' . $paidDate . '</a></td>';
                }else{
                    return '<td>' . $paidDate . '</td>';
                }
			}),

            array( 'db' => 'send', 'dt' => 5 ),
            array( 'db' => 'status_inv_agent', 'dt' => 6 ),
            array( 'db' => 'confirm', 'dt' => 7 ),

            array( 'db' => 'delivery', 'dt' => 4, 'formatter' => function($d,$row) {
                $aksi	= "module/mod_invoice-agent/invoice-agent_action.php";
                $key	= "asdf$1011";
                $idCabang = $_SESSION['id_cabang'];

                if ($idCabang == 39) {
                    $btnSend = '';
                    if ($row['status_inv_agent'] != '') {

						if ($GLOBALS["level"] == 'agent'){

							if ($row['confirm'] == ''){
								$btnSend = '<button class="btn btn-xs btn-default" title="Konfirmasi Invoice" onclick="window.location.href = \'module/mod_finance_invoice-agent/invoice-agent_action.php?module=invoice-agent&act=confirm&stts=1&id='.base64_encrypt($d,$key).'\';"><i class="fa fa-send text-red"></i></button>';
							}else{

								$btnSend = '<button class="btn btn-xs btn-default" title="Invoice Berhasil Dikonfirmasi" onclick="#"><i class="fa fa-send text-blue"></i></button>';
							}

						}else{

							if ($row['send'] == '') {
								$btnSend = '<button class="btn btn-xs btn-default" title="Kirim Invoice ke Agent" onclick="window.location.href = \'module/mod_finance_invoice-agent/invoice-agent_action.php?module=invoice-agent&act=send&stts=1&id=' . base64_encrypt($d, $key) . '\';"><i class="fa fa-send text-red"></i></button>';
							} else {

								if ($row['confirm'] == ''){
									$btnSend = '<button class="btn btn-xs btn-default" title="Batalkan Kirim Invoice ke Agent" onclick="window.location.href = \'module/mod_finance_invoice-agent/invoice-agent_action.php?module=invoice-agent&act=send&stts=0&id=' . base64_encrypt($d, $key) . '\';"><i class="fa fa-send text-green"></i></button>';
								}else{

									$btnSend = '<button class="btn btn-xs btn-default" title="Invoice Berhasil Dikonfirmasi" onclick="#"><i class="fa fa-send text-blue"></i></button>';
								}


							}
						}
                    }

                    return '<td><center>
                        <div class="btn-group">
                            <button class="btn btn-xs btn-default" title="Update" onclick="window.location.href = \'?module=invoice-agent&act=update&id='.base64_encrypt($d,$key).'\';"><i class="fa fa-pencil text-yellow"></i></button>'.$btnSend.'
                        </div></center></td>';

                }else{

					if ($row['confirm'] == ''){
						$comfirm = '<button class="btn btn-xs btn-default" title="Konfirmasi Invoice" onclick="window.location.href = \'module/mod_finance_invoice-agent/invoice-agent_action.php?module=invoice-agent&act=confirm&stts=1&id='.base64_encrypt($d, $key).'\';"><i class="fa fa-send text-red"></i></button>';
					}else{

						$comfirm = '<button class="btn btn-xs btn-default" title="Invoice Berhasil Dikonfirmasi" onclick="#"><i class="fa fa-send text-blue"></i></button>';
					}

                    return '<td><center>
                        <div class="btn-group">
                            <button class="btn btn-xs btn-default" title="Detail" onclick="window.location.href = \'?module=invoice-agent&act=detail&id='.base64_encrypt($d,$key).'\';"><i class="fa fa-file-text-o text-yellow"></i></button>
							'.$comfirm.'
                        </div></center></td>';
                }


            }),
		);
//		$sql_details = array(
//			'user' => 'cesco_user',
//			'pass' => 'srnGRkhl3c',
//			'db'   => 'cesco_logistics',
//			'host' => '127.0.0.1'
//		);

		require('../../config/ssp.class.php');
		echo json_encode(
			SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns )
		);

	// create
	}
	elseif ($module=='invoice-agent' and $act=='create')
	{
		$tanggal		= $mysqli->real_escape_string(jin_date_sql($_POST['tanggal']));
		$id_agent	= $mysqli->real_escape_string($_POST['id_agent']);

		$tgl_airwaybill	= $mysqli->real_escape_string($_POST['tgl_airwaybill']);
		$dr 			= strleft($tgl_airwaybill, 10);
		$sm 			= strright($tgl_airwaybill, 10);
		$dari 			= jin_date_sql($dr);
		$sampai 		= jin_date_sql($sm);

		$id_user 		= base64_decrypt($encrypt,$key);



				$airwaybill		= $mysqli->query("
											SELECT
												* FROM invoice_agent where id_agent=$id_agent and dari='$dari' and sampai='$sampai'
												");
			echo "SELECT
												* FROM invoice_agent where id_agent=$id_agent and dari='$dari' and sampai='$sampai'";
			$count			= $airwaybill->num_rows;
			echo $count;
			if ($count==0){
				echo 'kosong';
				//return false;

				$result			= $mysqli->query("INSERT into invoice_agent values('','$tanggal',$id_agent,'$dari','$sampai',now(),NULL)");
				//echo "INSERT into invoice values('',null,'$tanggal','$id_kustomer','$id_pelayanan','$dari','$sampai','$loc_code','$komoditi','$bank','$due_date',null,null,'draft','N','$id_user',null,now(),null)";

				$id_invoice = $mysqli->insert_id;
				$id			= base64_encrypt($id_invoice,$key);




				if ($result)
				{
					$_SESSION['pesan'] = 'success';
				}
				else
				{
					$_SESSION['pesan'] = 'failed';
					header('location:../../main.php?module='.$module.'&error=failed');
				}
			}
			else
			{
				echo 'data sudah ada';
				//return false;
				$_SESSION['pesan'] = 'duplicate';
				header('location:../../main.php?module='.$module.'&error=dupliacte');
				return true;
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

		header('location:../../main.php?module='.$module.'&act=update&id='.$id);

	}
	elseif($module=='invoice-agent' and $act=='biaya_packing'){

		mysqli_query($con, "update pengiriman set biaya_packing_deliv_agent='$_POST[value]' where id_pengiriman='$_POST[pk]'");
	}
	elseif($module=='invoice-agent' and $act=='biaya_packing_in'){

		mysqli_query($con, "update pengiriman set biaya_packing_agent='$_POST[value]' where id_pengiriman='$_POST[pk]'");
	}

	elseif($module=='invoice-agent' and $act=='biaya_tambahan'){
		mysqli_query($con, "update pengiriman set biaya_tambahan_deliv_agent='$_POST[value]' where id_pengiriman='$_POST[pk]'");
	}

	elseif($module=='invoice-agent' and $act=='biaya_tambahan_in'){
		mysqli_query($con, "update pengiriman set biaya_tambahan_agent='$_POST[value]' where id_pengiriman='$_POST[pk]'");
	}
	elseif($module=='invoice-agent' and $act=='biaya_tambahan_transit'){

		$pieces = explode('-', $_POST['pk']);
		$id_pengiriman = $pieces['0'];
		$agent_by = $pieces['1'];

		mysqli_query($con, "update manifest_detail a
							left join manifest b on a.id_manifest = b.id_manifest

		 set a.biaya_tambahan_agent_transit='$_POST[value]' where id_pengiriman='$id_pengiriman' and b.manifest_from =$agent_by ");

		/*echo "update manifest_detail a
							left join manifest b on a.id_manifest = b.id_manifest

		 set a.biaya_tambahan_agent_transit='$_POST[value]' where id_pengiriman='$id_pengiriman' and b.manifest_from =$agent_by ";*/

		// echo $_POST['PK'].'<br>'.$id_pengiriman.''
	}


	elseif($module=='invoice-agent' and $act=='keterangan_biaya'){
		mysqli_query($con, "update pengiriman set keterangan_biaya_agent='$_POST[value]' where id_pengiriman='$_POST[pk]'");

	}
	elseif($module=='invoice-agent' and $act=='keterangan_biaya_in'){
		mysqli_query($con, "update pengiriman set keterangan_biaya_in='$_POST[value]' where id_pengiriman='$_POST[pk]'");

	}

	elseif($module=='invoice-agent' and $act=='diskon'){
	    $arrData = explode('~', $_POST['pk']);

		mysqli_query($con, "update pengiriman set diskon='$_POST[value]' where id_pengiriman='".$arrData[0]."'");
		echo json_encode(array("id_pengiriman" => $arrData[0], "total" => $arrData[1], "diskon" => $_POST['value']));
	}

	elseif($module=='invoice-agent' and $act=='berat-agent'){
		mysqli_query($con, "update pengiriman set kunci_berat_agent='$_POST[value]' where id_pengiriman='$_POST[pk]'");
	}

	elseif($module=='invoice-agent' and $act=='rateout-update'){
		mysqli_query($con, "update rate_agent set price='$_POST[value]' where rateagent_id='$_POST[pk]'");
	}
	elseif($module=='invoice-agent' and $act=='ratein-update'){
		mysqli_query($con, "update rate_agent_inbound set price='$_POST[value]' where rateagentinbound_id='$_POST[pk]'");
	}
	elseif($module=='invoice-agent' and $act=='ratecross-update'){
		mysqli_query($con, "update rate_agent_cross set price='$_POST[value]' where rate_id='$_POST[pk]'");
	}
	//min kg
	elseif($module=='invoice-agent' and $act=='kg-update'){
		mysqli_query($con, "update rate_agent set min_kg='$_POST[value]' where rateagent_id='$_POST[pk]'");
	}
	elseif($module=='invoice-agent' and $act=='kgin-update'){
		mysqli_query($con, "update rate_agent_inbound set min_kg='$_POST[value]' where rateagentinbound_id='$_POST[pk]'");
	}
	elseif($module=='invoice-agent' and $act=='kgcross-update'){
		mysqli_query($con, "update rate_agent_cross set min_kg='$_POST[value]' where rate_id='$_POST[pk]'");
	}
	elseif($module=='invoice-agent' and $act=='kgtransit-update'){
		mysqli_query($con, "update rate_agent set min_kg='$_POST[value]' where rateagent_id='$_POST[pk]'");
	}

	elseif($module=='invoice-agent' and $act=='searchawb'){
		//mysqli_query($con, "update rate_agent_cross set min_kg='$_POST[value]' where rate_id='$_POST[pk]'");
		$q=mysqli_real_escape_string($con,$_POST['q']);


		$query=mysqli_query($con," select pickup_by_agent, delivery_by_agent, DATE_FORMAT(tanggal,'%Y-%m') AS tanggal_inv from pengiriman
		where airwaybill_kode LIKE '%".$q."%'  and status !=7
		");


		$row=mysqli_num_rows($query);

		if ($row > 0){
			while ($data=mysqli_fetch_assoc($query)){
				$key = "asdf$1011";
				$d_pu = $data['pickup_by_agent'].'-'.$data['tanggal_inv'];
				$d_deliv = $data['delivery_by_agent'].'-'.$data['tanggal_inv'];

				echo '

				';
				if($data['pickup_by_agent'] != 39){
					echo '
					<td><center><a class="btn btn-xs btn-primary" target="_blank" href="?module=invoice-agent&act=update&id='.base64_encrypt($d_pu,$key).'"><i class="fa fa-search"></i> View </a></center></td>';
				}
				else{
					echo '<td>-</td>';
				}

				if($data['delivery_by_agent'] != 39){
					echo '
					<td><center><a class="btn btn-xs btn-primary" target="_blank" href="?module=invoice-agent&act=update&id='.base64_encrypt($d_deliv,$key).'"><i class="fa fa-search"></i> View </a></center></td>
					';
				}else{
					echo '<td>-</td>';
				}
			}
		}
		else{
			echo'
			<td colspan="2">
				<center><b>AIRWAYBILL NOT FOUND</b></center>
			</td>';
		}
	}

	// delete invoice
	elseif ($module=='invoice-agent' AND $act=='delete')
	{
		$encrypt	= $_GET['id'];
		$key		= "asdf$1011";
		$id_invoice	= base64_decrypt($encrypt,$key);
		//echo 'delete'.$id_invoice;
		//$mysqli->query("delete from invoice where id_invoice='$id_invoice'");
		$mysqli->query("delete from invoice where id_invoice='$id_invoice'");
		$mysqli->query("delete from invoice_detail where id_invoice='$id_invoice'");
		$mysqli->query("update pengiriman set id_invoice=null where id_invoice =$id_invoice");
		$_SESSION['pesan'] = 'delete';
		header('location:../../main.php?module='.$module.'&delete=success'.$id_invoice);
	}

	// detail airwaybill
	elseif ($module=='invoice-agent' AND $act=='airwaybill')
	{
		$id = $_POST['id'];

		$query = $mysqli->query("SELECT a.id_pengiriman, a.airwaybill_kode, a.tanggal, a.pickup_date ,x_namakotabesar(a.lokasi_asal) as lokasi_asal, x_namakotabesar(a.lokasi_tujuan) as lokasi_tujuan, x_service(a.service) as service, case when a.komoditi='1' then 'Paket' else 'Dokumen' end as comodity, a.berat_barang, a.berat_aktual, b.harga_jual, b.packing, b.asuransi, b.charge, b.id_invoice, a.nama_pengirim, a.nama_penerima, a.alamat_pengirim, a.alamat_penerima, a.loc_code, a.delivery_name, a.delivery_date FROM pengiriman a inner join invoice_detail b on b.id_pengiriman=a.id_pengiriman WHERE a.id_pengiriman='".$id."'");
		$r = $query->fetch_assoc();
		echo'
		<form action="'.$aksi.'?module=invoice-agent&act=updateharga" method="POST">
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


	elseif ($module=='invoice-agent' AND $act=='form-insert-price')
	{
		$id = $_POST['id'];
		$agent = $_POST['pickup'];

		$query = $mysqli->query("SELECT a.id_pengiriman, a.airwaybill_kode, a.tanggal, a.pickup_date, a.id_kustomer ,kota_asal as lokasi_asal, a.id_kota_asal as id_lokasi_asal, kota_tujuan as lokasi_tujuan,a.id_kota_tujuan as id_lokasi_tujuan, x_service(a.service) as service, a.service as id_pelayanan, case when a.komoditi='1' then 'Paket' else 'Dokumen' end as comodity,a.komoditi as komoditi, a.berat_barang, a.berat_aktual, a.nama_pengirim, a.nama_penerima, a.alamat_pengirim, a.alamat_penerima, a.loc_code, a.delivery_name, a.delivery_date ,id_kecamatan_asal,kecamatan_asal,id_kecamatan_tujuan,kecamatan_tujuan,
			x_inter(a.id_kota_asal) as intercityasal, 	x_inter(a.id_kota_tujuan) as intercitytujuan,pickup_by_agent,delivery_by_agent

			FROM pengiriman a
			LEFT JOIN negara f ON f.id_negara=a.lokasi_tujuan and a.service=6
			WHERE a.id_pengiriman='".$id."'");
		$r = $query->fetch_assoc();
		//<form action="'.$aksi.'?module=invoice-agent&act=updateharga" method="POST">
		echo'
		<form action="'.$aksi.'?module=invoice-agent&act=insert-price" method="POST">


			<div class="form-group" style="padding-bottom: 25px;">
				<label class="col-md-3 control-label">Service</label>
				<div class="col-md-8">
					<input type="text" value="'.$r['service'].'" class="form-control" readonly>
				</div>
			</div>
			<div class="form-group" style="padding-bottom: 25px;">
				<label class="col-md-3 control-label">Kota Asal</label>
				<div class="col-md-8">
					<input type="text" value="'.$r['kecamatan_asal'].', '.$r['lokasi_asal'].'" class="form-control" readonly>
				</div>
			</div>

					<input type="hidden" value="'.$r['intercityasal'].'" name="intercityasal" class="form-control" readonly>

			<div class="form-group" style="padding-bottom: 25px;">
				<label class="col-md-3 control-label">Kota Tujuan</label>
				<div class="col-md-8">
					<input type="text" value="'.$r['kecamatan_tujuan'].', '.$r['lokasi_tujuan'].'" class="form-control" readonly>
				</div>
			</div>

					<input type="hidden" value="'.$r['intercitytujuan'].'" name="intercitytujuan" class="form-control" readonly>

			<div class="form-group" style="padding-bottom: 25px;">
				<label class="col-md-3 control-label">Min KG</label>
				<div class="col-md-8">
					<input type="number" value="1" name="min_kg" class="form-control" min="1">
				</div>
			</div>
			<div class="form-group" style="padding-bottom: 25px;">
				<label class="col-md-3 control-label">Harga(Rp))</label>
				<div class="col-md-8">
					<input type="number" name="price_perkg" class="form-control" value="1000" min="1000" />
				</div>
			</div>
			<div class="form-group" style="padding-bottom: 25px;">
				<label class="col-md-3 control-label">next KG(Rp)</label>
				<div class="col-md-8">
					<input type="number" name="price_next_kg" class="form-control" placeholder="kosongkan jika harga sama" />
				</div>
			</div>
			<div class="form-group" style="padding-bottom: 25px;">
				<label class="col-md-3 control-label">SLA(Hari)</label>
				<div class="col-md-8">
					<input type="number" value="1" name="sla" class="form-control" min="1">
				</div>
			</div>


			<input type="hidden" name="pickup_by_agent" class="form-control" value="'.$r['pickup_by_agent'].'" />
			<input type="hidden" name="delivery_by_agent" class="form-control" value="'.$r['delivery_by_agent'].'" />
			<input type="hidden" name="agent" class="form-control" value="'.$agent.'" />
			<input type="hidden" name="id_pengiriman" class="form-control" value="'.$r['id_pengiriman'].'" />
			<input type="hidden" name="id_kustomer" class="form-control" value="'.$r['id_kustomer'].'" />
			<input type="hidden" name="id_lokasi_asal" class="form-control" value="'.$r['id_kecamatan_asal'].'" />
			<input type="hidden" name="id_lokasi_tujuan" class="form-control" value="'.$r['id_kecamatan_tujuan'].'" />



			<input type="hidden" name="id_pelayanan" class="form-control" value="'.$r['id_pelayanan'].'" />
			<div class="modal-footer">
				<button class="btn btn-success" type="submit">insert</button>
				<button type="reset" class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Cancel</button>
			</div>
		</form>';

	}
	elseif ($module=='invoice-agent' AND $act=='insert-price'){
		//$id_kustomer		= $mysqli->real_escape_string($_POST['id_kustomer']);
		$id_lokasi_asal	= $mysqli->real_escape_string($_POST['id_lokasi_asal']);
		$id_lokasi_tujuan		= $mysqli->real_escape_string($_POST['id_lokasi_tujuan']);
		$id_pelayanan		= $mysqli->real_escape_string($_POST['id_pelayanan']);
		$intercityasal		= $mysqli->real_escape_string($_POST['intercityasal']);
		echo $intercitytujuan		= $mysqli->real_escape_string($_POST['intercitytujuan']);
		echo $agent 		= $mysqli->real_escape_string($_POST['agent']);
		$min_kg			= $mysqli->real_escape_string($_POST['min_kg']);
		$harga			= $mysqli->real_escape_string($_POST['price_perkg']);
		$price_next_kg			= $mysqli->real_escape_string($_POST['price_next_kg']);
		$sla			= $mysqli->real_escape_string($_POST['sla']);
		$pickup_by_agent			= $mysqli->real_escape_string($_POST['pickup_by_agent']);
		$delivery_by_agent			= $mysqli->real_escape_string($_POST['delivery_by_agent']);

		if ($pickup_by_agent == $agent || $delivery_by_agent==$agent){
			 echo '<br> asal'.$intercityasal.'<br> tujuan'.$intercitytujuan.'<br>agent'.$agent.'<br>pu'.$pickup_by_agent.'<br>deliv'.$delivery_by_agent;
			if ($intercityasal == 'Y' && $intercitytujuan != 'Y'){
				$result			= $mysqli->query("INSERT into rate_agent (rateagent_id,id_cabang,id_kota,id_pelayanan,komoditi,min_kg,price_next_kg,price,sla) values('','$agent','$id_lokasi_tujuan','$id_pelayanan','2','$min_kg','$price_next_kg','$harga','$sla')");

				echo "11INSERT into rate_agent (rateagent_id,id_cabang,id_kota,id_pelayanan,komoditi,min_kg,price_next_kg,price,sla) values('','$agent','$id_lokasi_tujuan','$id_pelayanan','2','$min_kg','$price_next_kg','$harga','$sla'))";
			}
			if ($intercityasal != 'Y' && $intercitytujuan == 'Y'){
				$result			= $mysqli->query("INSERT into rate_agent_inbound (rateagentinbound_id,id_cabang,id_kota,id_pelayanan, komoditi, min_kg, price, price_next_kg, sla) values('','$agent',$id_lokasi_asal,$id_pelayanan,'2',$min_kg,$harga, '$price_next_kg', $sla)");

				echo "INSERT into rate_agent_inbound (rateagentinbound_id,id_cabang,id_kota,id_pelayanan, komoditi, min_kg, price, price_next_kg, sla) values('','$agent',$id_lokasi_asal,$id_pelayanan,'2',$min_kg,$harga, '$price_next_kg', $sla)";



			}
			if ($intercityasal != 'Y' && $intercitytujuan != 'Y'){

					if($pickup_by_agent == $agent && $delivery_by_agent == $agent){
						$result			= $mysqli->query("INSERT into rate_agent_cross (rate_id, id_agent, id_kota_asal, id_kota_tujuan, id_pelayanan, min_kg, price, price_next_kg, sla) values('',$agent, $asal,$tujuan,$id_pelayanan,$min_kg,$harga,'$price_next_kg', $sla)");

						echo "INSERT into rate_agent_cross (rate_id, id_agent, id_kota_asal, id_kota_tujuan, id_pelayanan, min_kg, price, price_next_kg, sla) values('',$agent, $asal,$tujuan,$id_pelayanan,$min_kg,$harga,'$price_next_kg', $sla)";
					}

					elseif($pickup_by_agent == $agent && $delivery_by_agent != $agent){
						//inbound
						$result			= $mysqli->query("INSERT into rate_agent_inbound (rateagentinbound_id,id_cabang,id_kota,id_pelayanan, komoditi, min_kg, price, price_next_kg, sla) values('','$agent',$id_lokasi_asal,$id_pelayanan,'2',$min_kg,$harga, '$price_next_kg', $sla)");

						echo "INSERT into rate_agent_inbound (rateagentinbound_id,id_cabang,id_kota,id_pelayanan, komoditi, min_kg, price, price_next_kg, sla) values('','$agent',$id_lokasi_asal,$id_pelayanan,'2',$min_kg,$harga, '$price_next_kg', $sla)";
					}
					elseif($pickup_by_agent != $agent && $delivery_by_agent == $agent){
						//outbound
						$result			= $mysqli->query("INSERT into rate_agent (rateagent_id,id_cabang,id_kota,id_pelayanan,komoditi,min_kg,price_next_kg,price,sla) values('','$agent','$id_lokasi_tujuan','$id_pelayanan','2','$min_kg','$price_next_kg','$harga','$sla')");

						echo "88INSERT into rate_agent (rateagent_id,id_cabang,id_kota,id_pelayanan,komoditi,min_kg,price_next_kg,price,sla) values('','$agent','$id_lokasi_tujuan','$id_pelayanan','2','$min_kg','$price_next_kg','$harga','$sla')";
					}

				echo "INSERT into rate_agent_cross values('',$asal,$tujuan,$id_pelayanan,$min_kg,$harga,$sla,$agent,$pickup_by_agent,$delivery_by_agent)";
				//return false;




			}
		} else{
			 echo '<br> asal'.$intercityasal.'<br> tujuan'.$intercitytujuan.'<br>agent'.$agent.'<br>pu'.$pickup_by_agent.'<br>deliv'.$delivery_by_agent;
			$result			= $mysqli->query("INSERT into rate_agent (rateagent_id,id_cabang,id_kota,id_pelayanan,komoditi,min_kg,price_next_kg,price,sla) values('','$agent','$id_lokasi_tujuan','$id_pelayanan','2','$min_kg','$price_next_kg','$harga','$sla')");

			echo "99INSERT into rate_agent (rateagent_id,id_cabang,id_kota,id_pelayanan,komoditi,min_kg,price_next_kg,price,sla) values('','$agent','$id_lokasi_tujuan','$id_pelayanan','2','$min_kg','$price_next_kg','$harga','$sla')";
		}


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
	elseif ($module=='invoice-agent' AND $act=='form-edit-price')
	{
		$id = $_POST['id'];
		 $q = "SELECT id_kustomer, if(komoditi = 1,'paket','dokumen') as comodity, x_namakotabesar(id_lokasi_asal) as lokasi_asal,  if(a.id_pelayanan=6,f.nama_negara,x_namakotabesar(id_lokasi_tujuan)) as lokasi_tujuan, x_service(id_pelayanan) as service, loc_code, min_kg, harga, next_kg from rate_agent a LEFT JOIN negara f ON f.id_negara=a.id_lokasi_tujuan and a.id_pelayanan=6 where id=$id";
		$query = $mysqli->query($q);
		$r = $query->fetch_assoc();
		//<form action="'.$aksi.'?module=invoice-agent&act=updateharga" method="POST">
		echo'
		<form action="'.$aksi.'?module=invoice-agent&act=update-price" method="POST">

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
			';

			echo '
			<input type="hidden" name="id_rate" class="form-control" value="'.$id.'" />
			<div class="modal-footer">
				<button class="btn btn-success" type="submit">update</button>
				<button type="reset" class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Cancel</button>
			</div>
		</form>';

	}
	elseif ($module=='invoice-agent' AND $act=='update-price'){
		$id_rate		= $mysqli->real_escape_string($_POST['id_rate']);
		$min_kg			= $mysqli->real_escape_string($_POST['min_kg']);
		$harga			= $mysqli->real_escape_string($_POST['price_perkg']);
		$nextkg		= $mysqli->real_escape_string($_POST['price_nextkg']);

		// "update rate_agent set min_kg='".$min_kg."', harga='".$harga."' where id='".$id_rate."'";

		if ($nextkg == ''){
			$result			= $mysqli->query("update rate_agent set min_kg='".$min_kg."', harga='".$harga."' where id='".$id_rate."'");
		}
		else{
			$result			= $mysqli->query("update rate_agent set min_kg='".$min_kg."', harga='".$harga."', next_kg = '".$nextkg."' where id='".$id_rate."'");
		}
		//echo "INSERT into rate_agent values('',$id_kustomer,null,$id_lokasi_asal,$id_lokasi_tujuan,$id_pelayanan,$komoditi,$loc_code,$min_kg,$harga,null)";
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
	elseif ($module=='invoice-agent' AND $act=='tambah-biaya'){
		//echo 'asdas';
		//header('Location: ' . $_SERVER['HTTP_REFERER']);
		$id_invoice		= $mysqli->real_escape_string($_POST['id_invoice']);
		echo $id_invoice;
		$detail_biaya	= $mysqli->real_escape_string($_POST['detail_biaya']);
		$pajak = $mysqli->real_escape_string($_POST['pajak']);
		echo $pajak;
		if ($pajak == 'on')
		{
			$pajak = 'Ya';
		}
		else{
			$pajak = 'Tidak';
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
	// update harga airwaybill
	elseif ($module=='invoice-agent' AND $act=='updateharga')
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

	// delete airwaybill
	elseif ($module=='invoice-agent' AND $act=='deleteairwaybill')
	{
		$id = $_GET['id'];
		$invoice	= $mysqli->query("select id_invoice from invoice_detail where id_pengiriman='$id'");
		$i			= $invoice->fetch_assoc();
		$key		= "asdf$1011";
		$id_invoice	= base64_encrypt($i['id_invoice'],$key);

		$mysqli->query("delete from invoice_detail where id_pengiriman='$id'");
		header('location:../../main.php?module='.$module.'&act=update&id='.$id_invoice);
	}
	elseif($module=='invoice-agent' AND $act=='packing'){
		$mysqli->query("update pengiriman set biaya_packing_agent='$_POST[value]' where id_pengiriman='$_POST[pk]'");
		echo "update pengiriman set biaya_packing_agent='$_POST[value]' where id_pengiriman='$_POST[pk]'";
	}
	elseif($module=='invoice-agent' AND $act=='asuransi'){
		$mysqli->query("update pengiriman set biaya_asuransi='$_POST[value]' where id_pengiriman='$_POST[pk]'");
	}

	elseif($module=='invoice-agent' AND $act=='tambahan'){
		$mysqli->query("update pengiriman set biaya_tambahan_agent='$_POST[value]' where id_pengiriman='$_POST[pk]'");
	}
	elseif ($module=='invoice-agent' AND $act=='nomor') {
		$cek = mysqli_fetch_assoc(mysqli_query($con,"SELECT nomor FROM invoice ORDER BY nomor_dibuat DESC LIMIT 1"));
		//echo "SELECT nomor FROM invoice ORDER BY nomor_dibuat DESC LIMIT 1";
		//echo $cek['nomor'].' test<br>';
		$ex = explode('/', $cek['nomor']);

		if (date('d')=='01'){ $a = '01'; }
		else{ $a = $ex[0]+1; }

		$b = 'CLS';
		$c = array('','I','II','III','IV','V','VI','VII','VIII','IX','X','XI','XII');
		$d = date('Y');
		$no_surat = $a.'/'.$b.'/'.$c[date('n')].'/'.$d;
		echo $no_surat;
		//echo "update invoice set status='final', nomor='".$no_surat."' where id_invoice='".$_GET['idinvoice']."'";
		$update = mysqli_query($con,"update invoice set status='final', nomor='".$no_surat."', nomor_dibuat=now() where id_invoice='".$_GET['idinvoice']."'");
		header('Location: ' . $_SERVER['HTTP_REFERER']);
	}
	elseif($module=='invoice-agent' AND $act=='loccode'){
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

	elseif($module=='invoice-agent' AND $act=='choose_shipment'){
		$id_kustomer = $_POST['id_kustomer'];
		//cek

		$q = mysqli_query($con, "SELECT airwaybill_kode, id_pengiriman FROM pengiriman where id_kustomer= '$id_kustomer' and tanggal BETWEEN (CURRENT_DATE() - INTERVAL 2 MONTH) AND CURRENT_DATE() and (id_invoice is null or id_invoice = 0) ORDER BY id_pengiriman");

		$row = array();
		while($r=$q->fetch_assoc()){
			$rows[] = $r;
		}
		//$row->test = array('a' => 1);
		echo json_encode($rows);

	// pengirim
	}
	elseif($module=='invoice-agent' AND $act=='edit-duedate'){
		$id = $_POST['id_invoice'];
		$date = $_POST['tanggal'];
		$date		= date('Y-m-d', strtotime($date));
		$mysqli->query("update invoice set due_date='$date' where id_invoice=$id");
		//	echo "update invoice set due_date='$date' where id_invoice=$id";
		$key			= "asdf$1011";
		$id_inv				= base64_encrypt($id,$key);
		header('location:../../main.php?module='.$module.'&act=update&id='.$id_inv);

	}
	elseif($module=='invoice-agent' AND $act=='edit-subtotaljudul'){
		$id = $_POST['id_invoice'];
		$judul = $_POST['subtotaljudul'];

		$mysqli->query("update invoice set judul_subtotal='$judul' where id_invoice=$id");

		$key			= "asdf$1011";
		$id_inv				= base64_encrypt($id,$key);
		header('location:../../main.php?module='.$module.'&act=update&id='.$id_inv);

	}

	elseif($module=='invoice-agent' and $act=='reject'){
		$id_pengiriman = $mysqli->real_escape_string($_POST['id']);
		$reject_reason = $mysqli->real_escape_string($_POST['reject_reason']);
		//return false;

		$result = mysqli_query($con, "UPDATE pengiriman set paid_reject='0', reject_reason='$reject_reason' where id_pengiriman='$id_pengiriman'");
		//echo "UPDATE pengiriman set costing_pickup_parking='$parking_fee', where id='$id_pengiriman'";
		header('Location: ' . $_SERVER['HTTP_REFERER']);
	}

	elseif($module=='invoice-agent' and $act=='move'){
		$id_pengiriman = $mysqli->real_escape_string($_POST['id']);
		$tanggal		= $mysqli->real_escape_string(jin_date_sql($_POST['tanggal']));
		$agent		= $mysqli->real_escape_string(jin_date_sql($_POST['move_agent']));


		$query = $mysqli->query("SELECT pickup_by_agent,delivery_by_agent

			FROM pengiriman

			WHERE id_pengiriman='".$id_pengiriman."'");

		$r = $query->fetch_assoc();


		if($r['pickup_by_agent'] != ''){
			$mysqli->query("UPDATE pengiriman set tanggal_agent='$tanggal' where id_pengiriman='$id_pengiriman'");

			//$result = mysqli_query($con, "UPDATE pengiriman set tanggal_agent='$tanggal' where id_pengiriman='$id_pengiriman'");
		}else{
			$mysqli->query("UPDATE pengiriman set tanggal_agent='$tanggal' where id_pengiriman='$id_pengiriman'");

			//$result = mysqli_query($con, "UPDATE pengiriman set tanggal_agent='$tanggal' where id_pengiriman='$id_pengiriman'");
		}

		//echo "UPDATE pengiriman set tanggal_agent='$tanggal', pickup_by_agent=$agent where id_pengiriman='$id_pengiriman'";
		header('Location: ' . $_SERVER['HTTP_REFERER']);
	}
	elseif($module=='invoice-agent' and $act=='kunci-harga'){
		$berat = $_POST['berat'];
		foreach ($berat as $b => $value) {
			//echo "UPDATE pengiriman set kunci_berat_agent=$value WHERE id_pengiriman=$b <br>";
				$mysqli->query("UPDATE pengiriman set kunci_berat_agent=$value WHERE id_pengiriman=$b");
		}

		/*$harga = $_POST['hargaperkg'];
		foreach ($harga as $h => $value) {
			//echo "UPDATE pengiriman set kunci_hargaperkg_agent=$value WHERE id_pengiriman=$h <br>";
				$mysqli->query("UPDATE pengiriman set kunci_hargaperkg_agent=$value WHERE id_pengiriman=$h");
		}

		$kg = $_POST['minkg'];
		foreach ($kg as $k => $value) {
			//echo "UPDATE pengiriman set kunci_minkg_agent=$value WHERE id_pengiriman=$k <br>";
				$mysqli->query("UPDATE pengiriman set kunci_minkg_agent=$value WHERE id_pengiriman=$k");
		}*/

		$agent_by = $_POST['agent'];
		$total = $_POST['total'];
		foreach ($total as $t => $value) {
			//echo "UPDATE pengiriman set kunci_total_agent=$value WHERE id_pengiriman=$t <br>";
				$airwaybill		= $mysqli->query("
											SELECT
												case
													when pickup_by_agent =$agent_by and delivery_by_agent=$agent_by then 2
													when pickup_by_agent =$agent_by and (delivery_by_agent !=$agent_by or delivery_by_agent is null)  then 0
													when delivery_by_agent=$agent_by and pickup_by_agent != $agent_by then 1
													else 3
												end as agent_by,pickup_by_agent,delivery_by_agent
													FROM pengiriman where id_pengiriman = $t
												");
				$cek = $airwaybill->fetch_assoc();
				if ($value != 0){

					if($cek['agent_by'] == 2){
					// cross city sama agent
						$mysqli->query("UPDATE pengiriman set kunci_total_agent=$value WHERE id_pengiriman=$t");
					}
					elseif($cek['agent_by'] == 0){
						$mysqli->query("UPDATE pengiriman set kunci_total_agent_pickup=$value WHERE id_pengiriman=$t");

				//		echo 	"UPDATE pengiriman set kunci_total_agent_pickup=$value WHERE id_pengiriman=$t";
						// inbound
					}
					elseif($cek['agent_by'] == 1){
						$mysqli->query("UPDATE pengiriman set kunci_total_agent_deliv=$value WHERE id_pengiriman=$t");
				//		echo "UPDATE pengiriman set kunci_total_agent_deliv=$value WHERE id_pengiriman=$t";
						//outbound
					}
					else{

							$mysqli->query("UPDATE manifest_detail a
							left join manifest b on a.id_manifest = b.id_manifest

		 					set a.kunci_total_agent_transit='$value' where id_pengiriman='$t' and b.manifest_from =$agent_by");

					}





				}
		//echo $cek['agent_by'].'<br>';
		}

		$diskon = $_POST['diskon'];
		foreach ($diskon as $d => $value) {
			//echo "UPDATE pengiriman set kunci_total_agent=$value WHERE id_pengiriman=$t <br>";
				$mysqli->query("UPDATE pengiriman set diskon=$value WHERE id_pengiriman=$d");
		}

		//Count Lock Price
		$agent = $_POST['agent'];
		$periode = $_POST['periode'];
		$query = $mysqli->query("SELECT id_agent
			FROM invoice_agent_status
			WHERE id_agent=".$agent." and date_format(invoice_date, '%Y-%m') = '".$periode."' ");

		$r = $query->fetch_assoc();

		if($r['id_agent'] != ''){
			$sql = "update invoice_agent_status set lock_count = lock_count+1, updated_date = now(), updated_by = $userId
				where id_agent = $agent and invoice_date = '".$periode."-01'";
			$mysqli->query($sql);
		}else{
			$sql = "insert into invoice_agent_status (id_agent, invoice_date, lock_count, created_date, created_by) values ($agent, '".$periode."-01', 1, now(), $userId)";
			$mysqli->query($sql);
		}


		header('Location: ' . $_SERVER['HTTP_REFERER']);
	}
	elseif($module=='invoice-agent' and $act=='reset-kunci'){


		$id_pengiriman = $_POST['id_pengiriman'];
		$agent_by = $_POST['agent'];
		foreach ($id_pengiriman as $d => $value) {

			$query = $mysqli->query("SELECT pickup_by_agent,delivery_by_agent
				FROM pengiriman
				WHERE id_pengiriman =$d ");
			$r = $query->fetch_assoc();


			//echo "UPDATE pengiriman set kunci_total_agent = null, kunci_berat_agent=null WHERE id_pengiriman=$d <br>";

				//$mysqli->query("UPDATE pengiriman set kunci_total_agent = null,kunci_total_agent_deliv=null,kunci_total_agent_pickup=null WHERE id_pengiriman=$d");

				//update pickup
				if($r['pickup_by_agent'] == $agent_by && $r['delivery_by_agent'] == $agent_by){
					$mysqli->query("UPDATE pengiriman set kunci_total_agent = null WHERE id_pengiriman=$d");

					echo "UPDATE pengiriman set kunci_total_agent = null WHERE id_pengiriman=$d";
				}
				elseif($r['pickup_by_agent'] == $agent_by && $r['delivery_by_agent'] != $agent_by){
					$mysqli->query("UPDATE pengiriman set kunci_total_agent_pickup = null WHERE id_pengiriman=$d");

					echo "UPDATE pengiriman set kunci_total_agent_pickup = null WHERE id_pengiriman=$d";
				}
				elseif($r['pickup_by_agent'] != $agent_by && $r['delivery_by_agent'] == $agent_by){
					$mysqli->query("UPDATE pengiriman set kunci_total_agent_deliv = null WHERE id_pengiriman=$d");

					echo "UPDATE pengiriman set kunci_total_agent_deliv = null WHERE id_pengiriman=$d";
				}
				elseif($r['pickup_by_agent'] != $agent_by && $r['delivery_by_agent'] != $agent_by){
							$mysqli->query("UPDATE manifest_detail a
									left join manifest b on a.id_manifest = b.id_manifest

				 set a.kunci_total_agent_transit='$value' where id_pengiriman='$d' and b.manifest_from =$agent_by");

							echo "UPDATE manifest_detail a
									left join manifest b on a.id_manifest = b.id_manifest

				 set a.kunci_total_agent_transit='$value' where id_pengiriman='$d' and b.manifest_from =$agent_by";
				}




				//reset transit
			echo "<br>";

		}

		//header('Location: ' . $_SERVER['HTTP_REFERER']);
	}

	elseif($module=='invoice-agent' and $act=='draft'){

		$agent_by = $_GET['agent'];
		$awal = $_GET['awal'];
		$akhir	  = $_GET['akhir'];
		$status	  = $_GET['status'];

		if ($status == ""){
			$set_status = 1;
		}elseif($status == 1){
			$set_status = 2;
		}else{
			header('Location: ' . $_SERVER['HTTP_REFERER']);
		}




		$qupdate = "UPDATE  pengiriman a
						LEFT JOIN
					kota b ON b.id_kota = a.lokasi_tujuan
						OR b.id_kota = a.lokasi_asal
						LEFT JOIN
					cabang c ON c.id_cabang = a.pickup_by_agent
						OR c.id_cabang = a.delivery_by_agent
						LEFT JOIN
					rate_agent d ON d.id_cabang = CASE
						WHEN x_inter(a.id_kota_asal) = 'Y' THEN '39'
						ELSE c.id_cabang
					END
						AND d.id_kota = a.id_kota_tujuan
						AND d.id_pelayanan = CASE
						WHEN a.service > 3 THEN 3
						else a.service
					end
						LEFT JOIN
					rate_agent_inbound e ON e.id_kota = a.id_kota_asal
						and e.id_pelayanan = CASE
						WHEN a.service > 3 THEN 3
						else a.service
					end
						and e.id_cabang = CASE
						WHEN x_inter(a.id_kota_tujuan) = 'Y' THEN 0
						else 99
					end
						LEFT JOIN
					rate_agent_cross f ON case
						when a.pickup_by_agent = 24 THEN f.id_kota_asal
						else f.id_kota_tujuan
					END = case
						when a.pickup_by_agent = 24 THEN a.id_kota_asal
						else a.id_kota_tujuan
					END
						AND f.id_pelayanan = a.service
					set a.status_inv_agent = '$set_status'
					WHERE (c.id_cabang=$agent_by AND
						case when a.tanggal_agent is not null then
							a.tanggal_agent BETWEEN '$awal' AND '$akhir'
						else a.tanggal BETWEEN '$awal' AND '$akhir'
						end
					and service != 6 and a.status != 7 )
					and ( pickup_by_agent = $agent_by
					or delivery_by_agent = $agent_by
					) and (status_inv_agent is null or status_inv_agent = '')
							" ;

						//	echo $qupdate;

		$result = mysqli_query($con, $qupdate);


		header('Location: ' . $_SERVER['HTTP_REFERER']);
	}
	elseif($module=='invoice-agent' and $act=='due_date_invoice'){

        $id_agen = $mysqli->real_escape_string($_POST['id_agen']);
        $invoice_date		= $mysqli->real_escape_string(jin_date_sql($_POST['invoice_date'])).'-01';
        $due_date		= $mysqli->real_escape_string($_POST['tanggal']);
        $id_stts		= $mysqli->real_escape_string($_POST['id_stts']);

        if ($id_stts == ''){
            $sql = "INSERT INTO invoice_agent_status
                    (id_agent,
                    invoice_date,
                    due_date,
                    created_date,
                    created_by
                    )
                    VALUES
                    (
                    '$id_agen',
                    '$invoice_date',
                    '$due_date',
                    now(),
                    '$userId')";

            $result			= $mysqli->query($sql);
            $id_stts = $mysqli->insert_id;
        }else{
            $sql = "update invoice_agent_status
                    set
                    due_date = '$due_date',
                    updated_date = now(),
                    updated_by = '$userId'
                    where id = '$id_stts'";

            $result			= $mysqli->query($sql);
        }

        echo $id_stts;

	}
	elseif($module=='invoice-agent' and $act=='paid_date_invoice'){

        $id_agen = $mysqli->real_escape_string($_POST['id_agen']);
        $invoice_date		= $mysqli->real_escape_string(jin_date_sql($_POST['invoice_date'])).'-01';
        $paid_date		= $mysqli->real_escape_string($_POST['tanggal']);
        $id_stts		= $mysqli->real_escape_string($_POST['id_stts']);

        if ($id_stts == ''){
            $sql = "INSERT INTO invoice_agent_status
                    (id_agent,
                    invoice_date,
                    paid_date,
                    created_date,
                    created_by
                    )
                    VALUES
                    (
                    '$id_agen',
                    '$invoice_date',
                    '$paid_date',
                    now(),
                    '$userId')";

            $result			= $mysqli->query($sql);
            $id_stts = $mysqli->insert_id;
        }else{
            $sql = "update invoice_agent_status
                    set
                    paid_date = '$paid_date',
                    updated_date = now(),
                    updated_by = '$userId'
                    where id = '$id_stts'";

            $result			= $mysqli->query($sql);

        }

        echo $id_stts;

	}elseif($module=='invoice-agent' AND $act=='send'){
        $stts = $_GET['stts'];
        $encrypt	= $_GET['id'];

        $key 		= "asdf$1011";

        $id 		= base64_decrypt($encrypt,$key);
        $pieces = explode('~', $id);
        $agent_id = $pieces['0'];
        //$month = implode('-', array_slice($pieces, 1, 3));
				$month = $pieces['1'];

        $user 		= $_SESSION['id_user'];
        $id_user 	= base64_decrypt($user,$key);

        if ($stts == 1){

			$query = $mysqli->query("SELECT id
				FROM invoice_send
				WHERE agent_id = $agent_id and agent_date='$month' and invoice_type = 'AGENT' ");
			$r = $query->fetch_assoc();

			if($r['id'] != ''){
				$sql = "update invoice_send set send_by = $id_user, send_dtm = now() where agent_id = $agent_id and agent_date='$month' and invoice_type = 'AGENT'";
			}else{
				$sql = "INSERT into invoice_send (invoice_type, agent_id, agent_date, send_by, send_dtm) values ('AGENT',$agent_id,'$month',$id_user,now())";
			}
        }else{
            $sql = "update invoice_send set invoice_type = 'AGENT_ABORT', abort_by = $id_user, abort_dtm = now() where agent_id = $agent_id and agent_date='$month' and invoice_type = 'AGENT'";
        }

		$mysqli->query($sql);
        header('Location: ' . $_SERVER['HTTP_REFERER']);

    }elseif($module=='invoice-agent' AND $act=='confirm'){
		$encrypt = $_GET['id'];
		$stts = $_GET['stts'];

		$key 		= "asdf$1011";
        $user 		= $_SESSION['id_user'];
        $id_user 	= base64_decrypt($user,$key);

		$id 		= base64_decrypt($encrypt,$key);
        $pieces = explode('~', $id);
        $agent_id = $pieces['0'];
        //$month = implode('-', array_slice($pieces, 1, 3));
				$month = $pieces['1'];

        $sql = "update invoice_send set confirm_by = $id_user, confirm_dtm = now()
				where agent_id = $agent_id and agent_date = '".$month."' and invoice_type = 'AGENT'";
        $mysqli->query($sql);

		///Get Due Date Agent
		$query = $mysqli->query("SELECT due_date
			FROM cabang
			WHERE id_cabang=".$agent_id);
		$d = $query->fetch_assoc();
		$dueDate = ($d['due_date'] != '') ? $d['due_date'] : 0;

		//Update Invoice Agent Status
		$query = $mysqli->query("SELECT id_agent
			FROM invoice_agent_status
			WHERE id_agent=".$agent_id." and date_format(invoice_date, '%Y-%m') = '".$month."' ");
		$r = $query->fetch_assoc();

		if($r['id_agent'] != ''){
			$sql = "update invoice_agent_status set due_date = CURDATE()+ INTERVAL ".$dueDate." DAY, updated_date = now(), updated_by = $id_user
				where id_agent = $agent_id and invoice_date = '".$month."-01'";
		}else{
			$sql = "insert into invoice_agent_status (id_agent, invoice_date, due_date, created_date, created_by) values ($agent_id, '".$month."-01', CURDATE()+ INTERVAL ".$dueDate." DAY, now(), $id_user)";
		}
		$mysqli->query($sql);

		header('Location: ' . $_SERVER['HTTP_REFERER']);

	}
	elseif($module=='invoice-agent' AND $act=='tambah-biaya-admin'){

		$idAgent = $mysqli->real_escape_string($_POST['idAgent']);
		$month		= $mysqli->real_escape_string(jin_date_sql($_POST['month']));
		$biaya		= $mysqli->real_escape_string(jin_date_sql($_POST['biaya']));

		$query = $mysqli->query("SELECT id_agent
			FROM invoice_agent_status
			WHERE id_agent='".$idAgent."' and date_format(invoice_date, '%Y-%m') = '".$month."' ");

		$r = $query->fetch_assoc();

		if($r['id_agent'] != ''){
			$result = mysqli_query($con, "UPDATE invoice_agent_status set biaya_admin='$biaya', updated_date = now(), updated_by=$userId where id_agent='".$idAgent."' and date_format(invoice_date, '%Y-%m') = '".$month."'");
		}else{
			$result = mysqli_query($con, "insert into invoice_agent_status (id_agent, invoice_date, biaya_admin, created_date, created_by)  values ($idAgent, '".$month."-01', '$biaya', now(), $userId)");
		}


		header('Location: ' . $_SERVER['HTTP_REFERER']);

	}
	elseif($module=='invoice-agent' AND $act=='nomor-invoice'){

		$idAgent = $mysqli->real_escape_string($_POST['idAgent']);
		$month		= $mysqli->real_escape_string(jin_date_sql($_POST['month']));
		$nomor		= $mysqli->real_escape_string(jin_date_sql($_POST['nomor']));

		$query = $mysqli->query("SELECT id_agent
			FROM invoice_agent_status
			WHERE id_agent='".$idAgent."' and date_format(invoice_date, '%Y-%m') = '".$month."' ");

		$r = $query->fetch_assoc();

		if($r['id_agent'] != ''){
			$result = mysqli_query($con, "UPDATE invoice_agent_status set nomor='$nomor', updated_date = now(), updated_by=$userId where id_agent='".$idAgent."' and date_format(invoice_date, '%Y-%m') = '".$month."'");
		}else{
			$result = mysqli_query($con, "insert into invoice_agent_status (id_agent, invoice_date, nomor, created_date, created_by)  values ($idAgent, '".$month."-01', '$nomor', now(), $userId)");
		}

		header('Location: ' . $_SERVER['HTTP_REFERER']);

	}

}
?>
