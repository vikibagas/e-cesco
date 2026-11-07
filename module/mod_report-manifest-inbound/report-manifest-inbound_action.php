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
	$id = base64_decrypt($encrypt,$key);
	$useraktif = mysqli_query($con, 'select * from user where id_user="'.$id.'"');
	$u = mysqli_fetch_assoc($useraktif);
	
	// data
	if($module=='inbound' and $act=='data'){
		if($u['level']=='admin' || $u['level']=='finance'){
		$table = 
<<<EOT
	(
	/*select
		a.kode_manifest,
		a.tanggal,
		x_user(a.created_by) as created_by,
		case when x_intercity(b.id_kota)='Y' then 'JAKARTA' else b.nama_cabang end as manifest_from,
		case when x_intercity(c.id_kota)='Y' then 'JAKARTA' else c.nama_cabang end as manifest_to,
		count(d.id_pengiriman) as jumlah,
		sum(coalesce(d.berat_aktual,d.berat_barang)) as berat,
		a.id_manifest
	from manifest a 
	inner join cabang b on b.id_cabang=a.manifest_from 
	inner join cabang c on c.id_cabang=a.manifest_to 	
	inner join manifest_detail e on e.id_manifest=a.id_manifest 
	inner join pengiriman d on d.id_pengiriman=e.id_pengiriman
	where x_intercity(d.lokasi_asal) !='Y' and x_intercity(d.lokasi_tujuan)='Y' and a.transit = 'N' and d.manifest='Y' and d.manifest_by is not null
	group by a.id_manifest
	order by tanggal desc, a.id_manifest desc*/

	select 
	a.kode_manifest,
	a.tanggal, 
		x_user(a.created_by) as created_by, 
		case when x_intercity(d.id_kota)='Y' then 'JAKARTA' else d.nama_cabang end as manifest_from, 
		case when x_intercity(e.id_kota)='Y' then 'JAKARTA' else e.nama_cabang end as manifest_to,
		count(c.id_pengiriman) as jumlah, 
		sum(coalesce(c.berat_aktual,c.berat_barang)) as berat, 
		a.id_manifest 
	from manifest a 
	inner join manifest_detail b on b.id_manifest=a.id_manifest 
	inner join pengiriman c on c.id_pengiriman=b.id_pengiriman 
	inner join cabang d on d.id_cabang=a.manifest_from 
	inner join cabang e on e.id_cabang=a.manifest_to
	
	where  a.manifest_from not in (39,41) and a.manifest_to in (39,41)

	group by a.id_manifest
	order by tanggal desc, a.id_manifest desc
	) temp
EOT;
		}elseif($u['level']=='operator'){
		$table = 
<<<EOT
	(
	select 
	a.kode_manifest,
	a.tanggal, 
		x_user(a.created_by) as created_by, 
		case when x_intercity(d.id_kota)='Y' then 'JAKARTA' else d.nama_cabang end as manifest_from, 
		case when x_intercity(e.id_kota)='Y' then 'JAKARTA' else e.nama_cabang end as manifest_to,
		count(c.id_pengiriman) as jumlah, 
		sum(coalesce(c.berat_aktual,c.berat_barang)) as berat, 
		a.id_manifest 
	from manifest a 
	inner join manifest_detail b on b.id_manifest=a.id_manifest 
	inner join pengiriman c on c.id_pengiriman=b.id_pengiriman 
	inner join cabang d on d.id_cabang=a.manifest_from 
	inner join cabang e on e.id_cabang=a.manifest_to
	where x_intercity(c.lokasi_asal)!='Y' 
	group by a.id_manifest
	) temp
EOT;
		}elseif($u['level']=='intercity'){
		$table = 
<<<EOT
	(
	select 
	a.kode_manifest,
	a.tanggal, 
		x_user(a.created_by) as created_by, 
		case when x_intercity(d.id_kota)='Y' then 'JAKARTA' else d.nama_cabang end as manifest_from, 
		case when x_intercity(e.id_kota)='Y' then 'JAKARTA' else e.nama_cabang end as manifest_to,
		count(c.id_pengiriman) as jumlah, 
		sum(coalesce(c.berat_aktual,c.berat_barang)) as berat, 
		a.id_manifest 
	from manifest a 
	inner join manifest_detail b on b.id_manifest=a.id_manifest 
	inner join pengiriman c on c.id_pengiriman=b.id_pengiriman 
	inner join cabang d on d.id_cabang=a.manifest_from 
	inner join cabang e on e.id_cabang=a.manifest_to
	where x_intercity(c.lokasi_tujuan)='Y' 
	group by a.id_manifest
	) temp
EOT;
		}elseif($u['level']=='domex'){
		$table = 
<<<EOT
	(
	select 
	a.kode_manifest,
	a.tanggal, 
		x_user(a.created_by) as created_by, 
		case when x_intercity(d.id_kota)='Y' then 'JAKARTA' else d.nama_cabang end as manifest_from, 
		case when x_intercity(e.id_kota)='Y' then 'JAKARTA' else e.nama_cabang end as manifest_to,
		count(c.id_pengiriman) as jumlah, 
		sum(coalesce(c.berat_aktual,c.berat_barang)) as berat, 
		a.id_manifest 
	from manifest a 
	inner join manifest_detail b on b.id_manifest=a.id_manifest 
	inner join pengiriman c on c.id_pengiriman=b.id_pengiriman 
	inner join cabang d on d.id_cabang=a.manifest_from 
	inner join cabang e on e.id_cabang=a.manifest_to
	where  x_intercity(c.lokasi_asal)!='Y' and x_intercity(c.lokasi_tujuan)='Y' 
	group by a.id_manifest
	) temp
EOT;
		}elseif($u['level']=='network'){
		$table = 
<<<EOT
	(
	select 
	a.kode_manifest,
	a.tanggal, 
		x_user(a.created_by) as created_by, 
		case when x_intercity(d.id_kota)='Y' then 'JAKARTA' else d.nama_cabang end as manifest_from, 
		case when x_intercity(e.id_kota)='Y' then 'JAKARTA' else e.nama_cabang end as manifest_to,
		count(c.id_pengiriman) as jumlah, 
		sum(coalesce(c.berat_aktual,c.berat_barang)) as berat, 
		a.id_manifest 
	from manifest a 
	inner join manifest_detail b on b.id_manifest=a.id_manifest 
	inner join pengiriman c on c.id_pengiriman=b.id_pengiriman 
	inner join cabang d on d.id_cabang=a.manifest_from 
	inner join cabang e on e.id_cabang=a.manifest_to
	where c.lokasi_asal in($u[area]) 
	group by a.id_manifest
	) temp
EOT;
		}elseif($u['level']=='agent'){
		$table = 
<<<EOT
	(
	select 
	a.kode_manifest,
	a.tanggal, 
		x_user(a.created_by) as created_by, 
		case when x_intercity(d.id_kota)='Y' then 'JAKARTA' else d.nama_cabang end as manifest_from, 
		case when x_intercity(e.id_kota)='Y' then 'JAKARTA' else e.nama_cabang end as manifest_to,
		count(c.id_pengiriman) as jumlah, 
		sum(coalesce(c.berat_aktual,c.berat_barang)) as berat, 
		a.id_manifest 
	from manifest a 
	inner join manifest_detail b on b.id_manifest=a.id_manifest 
	inner join pengiriman c on c.id_pengiriman=b.id_pengiriman 
	inner join cabang d on d.id_cabang=a.manifest_from 
	inner join cabang e on e.id_cabang=a.manifest_to
	where a.manifest_from=$u[id_cabang] 
	group by a.id_manifest
	) temp
EOT;
		}

		$primaryKey = 'id_manifest';

		$columns = array(
			array( 'db' => 'kode_manifest', 'dt'=> 0 ),
			array(
					'db'        => 'tanggal',
					'dt'        => 1,
					'formatter' => function( $d, $row ) {
						return date( 'd-m-Y', strtotime($d));
					}
			),
			array(
					'db'		=> 'created_by',
					'dt'		=> 2,
					'formatter'	=> function($d,$row){
						return '<td>'.strtoupper($d).'</td>';
					}
			),
			array( 'db' => 'manifest_from', 'dt'=> 3 ),
			array( 'db' => 'manifest_to', 'dt'=> 4 ),
			array( 'db' => 'jumlah', 'dt'=> 5 ),
			array( 'db' => 'berat', 'dt'=> 6 ),
			array(
				  'db'        => 'id_manifest',
				  'dt'        => 7,
				  'formatter' => function( $d, $row ) {
					$aksi="module/mod_airwaybill/airwaybill_action.php";
					$key = "asdf$1011";
					return '<td><center><a class="btn btn-xs btn-primary" target="_blank" href="main.php?module=report-manifest-inbound&act=view&id='.base64_encrypt($d,$key).'"><i class="fa fa-search"></i> View</a>

					

					<button class="btn btn-xs btn-warning open_switch_inbound" data-id='.base64_encrypt($d,$key).'"><i class="fa fa-exchange"></i></button></center></td>
					';
				  }
			)

		);

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
	
	// update
	}elseif($module=='outbound' and $act=='update'){
		$key = "asdf$1011";
		$encrypt = "$_POST[id_manifest]";
		$id = base64_encrypt($encrypt,$key);
		$nosmu = mysqli_real_escape_string($con, $_POST['no_smu']);
		
		mysqli_query($con, "update manifest set no_smu='$_POST[no_smu]' where id_manifest='$_POST[id_manifest]'");
		header('location:../../main.php?module=report-manifest-inbound&act=view&id='.$id);
	
	//delete
	}elseif($module=='outbound' and $act=='delete'){
		$key = "asdf$1011";
		$encrypt = "$_GET[id]";
		$id = base64_decrypt($encrypt,$key);
		mysqli_query($con, "update pengiriman set id_manifest=NULL, manifest_from=NULL, manifest_to=NULL, manifest_date=NULL where id_pengiriman='$id'");
		header('location:../../main.php?module=report-manifest-outbound');
	}
	elseif($module=='report-manifest-inbound' and $act=='searchawb'){
		$q=mysqli_real_escape_string($con,$_POST['q']);

		$query=mysqli_query($con,"select a.id_manifest,a.id_pengiriman,b.airwaybill_kode from manifest_detail a 
			inner join pengiriman b on a.id_pengiriman  = b.id_pengiriman 
			inner JOIN manifest c ON a.id_manifest = c.id_manifest		
		where b.airwaybill_kode LIKE '%".$q."%' and c.manifest_from > 0 and c.manifest_to > 0
	
		");
		// select a.id_manifest,a.id_pengiriman,b.airwaybill_kode from manifest_detail a inner join pengiriman b on a.id_pengiriman = b.id_pengiriman inner JOIN manifest c ON a.id_manifest = c.id_manifest where b.airwaybill_kode LIKE '%001-00185478%' and c.inbound='N' AND c.tipe_manifest is null
		$row=mysqli_num_rows($query);

		if ($row > 0){
			while ($data=mysqli_fetch_assoc($query)){
				$key = "asdf$1011";
					
				echo '
				<td>'.$data['airwaybill_kode'].'	</td>

				<td><center><a class="btn btn-xs btn-primary" target="_blank" href="?module=report-manifest-inbound&act=view&id='.base64_encrypt($data['id_manifest'],$key).'"><i class="fa fa-search"></i> View</a></center></td>
				'; 
			}
		}
		else{
			echo'
			<td colspan="2">
				<center><b>AIRWAYBILL NOT FOUND</b></center>
			</td>'; 
		}
	}

}
?>