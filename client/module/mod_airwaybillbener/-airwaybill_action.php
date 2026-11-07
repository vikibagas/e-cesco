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
	
	$aksi="module/mod_airwaybill/airwaybill_action.php";
	
	// data
	if($module=='airwaybill' and $act=='data'){
		
		$table = 
<<<EOT
	(
	SELECT
		a.airwaybill_kode, 
		a.created_date as tanggal, 
		x_namakustomer(a.id_kustomer) as id_kustomer,
		x_namakotabesar(a.lokasi_asal) as lokasi_asal,
		case when a.service='6' then x_namanegara(a.lokasi_tujuan) else x_namakotabesar(a.lokasi_tujuan) end as lokasi_tujuan,
		a.komoditi,
		a.status,
		a.inbound,
		a.pickup_date,
		x_user(a.pickup_by) as pickup_by,
		a.delivery_date,
		x_user(a.delivery_by) as delivery_by,
		case when status='8' then delivery_note else delivery_name end as delivery_name,
		concat(a.inbound,'-',a.id_pengiriman) as id,
		id_pengiriman
		FROM pengiriman a
	) temp
EOT;

		$primaryKey = 'id_pengiriman';

		$columns = array(
			array( 'db' => 'airwaybill_kode', 'dt' => 0 ),
			array(
					'db'        => 'tanggal',
					'dt'        => 1,
					'formatter' => function( $d, $row ) {
						return date( 'd-m-Y H:i', strtotime($d));
					}
			),
			array( 'db' => 'id_kustomer', 'dt'=> 2 ),
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
			array(
				  'db'        => 'komoditi',
				  'dt'        => 5,
				  'formatter' => function( $d, $row ) {
					if($d=='1'){
						$komoditi = '<button class="btn btn-block btn-xs"><i class="fa fa-cube"></i> Package</button>';
					}else{
						$komoditi = '<button class="btn btn-block btn-xs"><i class="fa fa-book"></i> Document</button>';
					}
					return '<td>'.$komoditi.'</td>';
				  }
			),
			array(
				  'db'        => 'status',
				  'dt'        => 6,
				  'formatter' => function( $d, $row ) {
					if($d=='1'){
						$status = '<button class="btn btn-block btn-warning btn-xs">Booking</button>';
					}elseif($d=='2'){
						$status = '<button class="btn btn-block btn-info btn-xs">Pickup</button>';
					}elseif($d=='3'){
						$status = '<button class="btn btn-block btn-primary btn-xs">On Outbound</button>';
					}elseif($d=='4'){
						$status = '<button class="btn btn-block btn-primary btn-xs">On Inbound</button>';
					}elseif($d=='5'){
						$status = '<button class="btn btn-block btn-primary btn-xs">On Delivery</button>';
					}elseif($d=='6'){
						$status = '<button class="btn btn-block btn-success btn-xs">Delivered</button>';
					}elseif($d=='7'){
						$status = '<button class="btn btn-block btn-danger btn-xs">Cancel</button>';
					}elseif($d=='8'){
						$status = '<button class="btn btn-block btn-danger btn-xs">Return</button>';
					}
					return '<td><center>'.$status.'</center></td>';
				  }
			),
			array(
				'db'		=> 'inbound',
				'dt'		=> 7,
				'formatter'	=> function($d,$row){
					if($d=='Y'){
						$tipe='Inbound';
					}else{
						$tipe='Outbound';
					}
					return '<td><center>'.$tipe.'</center></td>';
				}
			),
			array('db' => 'pickup_date',
					'dt'		=> 8,
					'formatter'	=> function($d,$row){
						if($d==''){
							$pickup_date = '';
						}else{
							$pickup_date = date( 'd-m-Y', strtotime($d));
						}
						return '<td><center>'.$pickup_date.'</center></td>';
					}
			),
			array('db' => 'pickup_by',
					'dt'		=> 9,
					'formatter'	=> function($d,$row){
						return '<td>'.strtoupper($d).'</td>';
					}
			),
			array('db' => 'delivery_date',
					'dt'		=> 10,
					'formatter'	=> function($d,$row){
						if($d==''){
							$delivery_date = '';
						}else{
							$delivery_date = date( 'd-m-Y', strtotime($d));
						}
						return '<td><center>'.$delivery_date.'</center></td>';
					}
			),
			array('db' => 'delivery_by',
					'dt'		=> 11,
					'formatter'	=> function($d,$row){
						return '<td>'.strtoupper($d).'</td>';
					}
			),
			array('db' => 'delivery_name',
					'dt'		=> 12,
					'formatter'	=> function($d,$row){
						return '<td>'.strtoupper($d).'</td>';
					}
			),
			array(
				  'db'        => 'id',
				  'dt'        => 13,
				  'formatter' => function( $d, $row ) {
					$aksi="module/mod_airwaybill/airwaybill_action.php";
					$pieces = explode('-', $d);
					$inbound = $pieces['0'];
					$id = implode('-', array_slice($pieces, 1));
					$key = "asdf$1011";
					if($inbound=='Y'){
						$awb='airwaybill_inbound';
						$booking='inbound';
					}else{
						$awb='airwaybill';
						$booking='outbound';
					}
					return '<td><center>
					<button class="btn btn-xs btn-warning" onclick="window.location.href = \'?module=airwaybill&act='.$booking.'&id='.base64_encrypt($id,$key).'\';"><i class="fa fa-pencil"></i></button> 
					<button class="btn btn-xs btn-danger open_cancel" data-id="'.base64_encrypt($id,$key).'"><i class="fa fa-remove"></i></button> 
					<button class="btn btn-xs btn-success" onClick="window.open(\'reports/'.$awb.'.php?id='.base64_encrypt($id,$key).'\',\'mywindow\',\'menubar=1,resizable=1,width=800,height=600\');"><i class="fa fa-print"></i></button></center></td>';
				  }
			)

		);

		$sql_details = array(
			'user' => 'cesco_user',
			'pass' => 'srnGRkhl3c',
			'db'   => 'cesco_logistics',
			'host' => '127.0.0.1'
		);
		require( '../../config/ssp.class.php' );

		echo json_encode(
			SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns )
		);
	
	// read data
	}elseif($module=='airwaybill' and $act=='read'){
		$query = "SELECT 
					a.airwaybill_kode, 
					a.created_date as tanggal, 
					x_namakustomer(a.id_kustomer) as id_kustomer, 
					x_namakotabesar(a.lokasi_asal) as lokasi_asal, 
					case when a.service='6' then x_namanegara(a.lokasi_tujuan) else x_namakotabesar(a.lokasi_tujuan) end as lokasi_tujuan, 
					a.komoditi, 
					a.status, 
					a.inbound, 
					a.pickup_date, 
					x_user(a.pickup_by) as pickup_by, 
					a.delivery_date, 
					x_user(a.delivery_by) as delivery_by, 
					case when status='8' then delivery_note else delivery_name end as delivery_name, 
					concat(a.inbound,'-',a.id_pengiriman) as id,
					id_pengiriman 
				FROM pengiriman a";
		$result = $mysqli->query($query);
		while($row = $result->fetch_assoc()){
			echo'
			<tr>
				<td>'.$row['airwaybill_kode'].'</td>
				<td>'.$row['tanggal'].'</td>
				<td>'.$row['id_kustomer'].'</td>
				<td>'.$row['lokasi_asal'].'</td>
				<td>'.$row['lokasi_tujuan'].'</td>
				<td>'.$row['komoditi'].'</td>
				<td>'.$row['status'].'</td>
				<td>'.$row['inbound'].'</td>
				<td>'.$row['pickup_date'].'</td>
				<td>'.$row['pickup_by'].'</td>
				<td>'.$row['delivery_date'].'</td>
				<td>'.$row['delivery_by'].'</td>
				<td>'.$row['delivery_name'].'</td>
				<td>'.$row['id'].'</td>
			</tr>';
		}
	// update airwaybill
	}elseif($module=='airwaybill' and $act=='update'){
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
		$berat_aktual = mysqli_real_escape_string($con, $_POST['berat_aktual']);
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
													berat_aktual='$berat_aktual',
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
							WHERE id_pengiriman = '$_POST[id]'");
		
		header('location:../../main.php?module='.$module);
		
	// cancel form
	}elseif($module=='airwaybill' and $act=='cancel'){
		$key = "asdf$1011";
		$encrypt = "$_POST[id]";
		$id = base64_decrypt($encrypt,$key);
		echo'
		<form action="'.$aksi.'?module=airwaybill&act=cancelupdate" name="modal_popup" method="POST">
			<div class="form-group" style="padding-bottom: 5px;">
                <label>Alasan Dibatalkan</label>
				<input type="hidden" name="id" class="form-control" value="'.$id.'" />
     			<textarea name="cancel_reason" class="form-control" rows="3"/></textarea>
			</div>
			<div class="modal-footer">
				<button class="btn btn-success" type="submit">Confirm</button>
				<button type="reset" class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Cancel</button>
			</div>
		</form>';

	// delete
	}elseif($module=='airwaybill' and $act=='cancelupdate'){
		$id = mysqli_real_escape_string($con,$_POST['id']);
		$cancel_reason = mysqli_real_escape_string($con,$_POST['cancel_reason']);
		mysqli_query($con, "update pengiriman set status='7', cancel_reason='$cancel_reason' where id_pengiriman='$id'");
		header('location:../../main.php?module='.$module);
	
	// import penerima
	}elseif($module=='airwaybill' and $act=='importpenerima'){
		echo'
		<form action="'.$aksi.'?module=airwaybill&act=importpenerima_action" name="modal_popup" method="POST" enctype="multipart/form-data">
			<div class="form-group" style="padding-bottom: 5px;">
                <input type="file" name="importfile">
				<p class="help-block">Hanya bisa file ber-type .xls</p>
			</div>
			<div class="modal-footer">
				<button class="btn btn-success" type="submit">Confirm</button>
				<button type="reset" class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Cancel</button>
			</div>
		</form>';
		
	// import penerima action
	}elseif($module=='airwaybill' and $act=='importpenerima_action'){
		include "../../config/excel_reader2.php";
		// membaca file excel yang diupload
		$data = new Spreadsheet_Excel_Reader($_FILES['importfile']['tmp_name']);
		
		// membaca jumlah baris dari data excel
		$baris = $data->rowcount($sheet_index=0);
		
		// nilai awal counter untuk jumlah data yang sukses dan yang gagal diimport
		$sukses = 0;
		$gagal = 0;
		// import data excel mulai baris ke-2 (karena baris pertama adalah nama kolom)
		for ($i=2; $i<=$baris; $i++){
			$airwaybill	= $data->val($i, 1);
			$penerima	= $data->val($i, 2);
			$tanggal	= $data->val($i, 3);
			
			//airwaybill
			$query1	= mysqli_query($con, "SELECT airwaybill_kode FROM pengiriman WHERE airwaybill_kode = '$airwaybill'");
			$ketemu = mysqli_num_rows($query1);
			$rairwaybill = mysqli_fetch_assoc($query1);
			
			if($ketemu > 0){
				$query = "update pengiriman set delivery_name='$penerima', delivery_date='$tanggal', status='6', delivery='Y' where airwaybill_kode='$airwaybill'";
				$hasil = mysqli_query($con,$query);
				
			}
			if ($hasil) $sukses++;
			else $gagal++;
		}
		
		header('location:../../main.php?module='.$module);
	}

}
?>