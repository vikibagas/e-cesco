<?php
if(!isset($_SESSION)){
    session_start();
}

if (empty($_SESSION['username']) AND empty($_SESSION['password'])){
	header('location:../application/index.php');
}
else{
	include "../../config/connection.php";
	
	$act	= !empty($_GET['act']) ? $_GET['act'] : '';
	
	if($act=='berat_aktual'){
		mysqli_query($con, "update pengiriman set berat_aktual='$_POST[value]' where id_pengiriman='$_POST[pk]'");
	}elseif($act=='biaya_packing'){
		mysqli_query($con, "update pengiriman set biaya_packing='$_POST[value]' where id_pengiriman='$_POST[pk]'");
	}elseif($act=='biaya_tambahan'){
		mysqli_query($con, "update pengiriman set biaya_tambahan='$_POST[value]' where id_pengiriman='$_POST[pk]'");
	}elseif($act=='keterangan_biaya'){
		mysqli_query($con, "update pengiriman set keterangan_biaya='$_POST[value]' where id_pengiriman='$_POST[pk]'");
	}elseif($act=='rate'){
		mysqli_query($con, "update rate_agent set price='$_POST[value]' where rateagent_id='$_POST[pk]'");
	}elseif($act=='rate-new'){
		$sql = mysqli_query($con,"select case when x_intercity(a.lokasi_asal)='Y' then '39' else c.id_cabang end as id_cabang, a.lokasi_tujuan as id_kota, a.service as id_pelayanan, a.komoditi from pengiriman a inner join kota b on b.id_kota=a.lokasi_tujuan inner join cabang c on c.id_kota=b.id_kota where id_pengiriman='$_POST[pk]'");
		$r = mysqli_fetch_assoc($sql);
		mysqli_query($con, "insert into rate_agent (id_cabang,id_kota,id_pelayanan,komoditi,price) value ('$r[id_cabang]','$r[id_kota]','$r[id_pelayanan]','$r[komoditi]','$_POST[value]')");
	}elseif($act=='min-kg'){
		mysqli_query($con, "update rate_agent set min_kg='$_POST[value]' where rateagent_id='$_POST[pk]'");
	}elseif($act=='price'){
		mysqli_query($con, "update pengiriman set price='$_POST[value]' where id_pengiriman='$_POST[pk]'");
	}
	elseif($act=='save'){
		$dari = $_POST['tanggal_dr'];
		$sampai = $_POST['tanggal_smp'];
		$id_agent = $_POST['agent_id'];
		/*mysqli_query($con, " UPDATE pengiriman a							
							INNER JOIN kota b ON b.id_kota=a.lokasi_tujuan 
							OR b.id_kota=a.lokasi_asal 
							INNER JOIN cabang c ON c.id_kota=b.id_kota OR b.hub=c.id_kota 
						LEFT JOIN rate_agent d ON d.id_cabang=CASE WHEN x_intercity(a.lokasi_asal)='Y' THEN '39' ELSE c.id_cabang END AND d.id_kota=a.lokasi_tujuan AND d.id_pelayanan=a.service AND d.komoditi=a.komoditi 
							LEFT JOIN rate_agent_inbound e ON e.id_kota=a.lokasi_asal and e.id_pelayanan=a.service and e.komoditi=a.komoditi 
						set a.inv_agent_save = 1
							WHERE c.id_cabang='$id_agent' AND a.tanggal BETWEEN '$dari' AND '$sampai' AND ((x_intercity(a.lokasi_asal)='Y' AND x_intercity(a.lokasi_tujuan)!='Y') OR 
(x_intercity(a.lokasi_asal)!='Y' and x_intercity(a.lokasi_tujuan)='Y')) 
							ORDER BY airwaybill_kode");*/
		header('Content-Type: application/json');
		echo json_encode(array('succes' => true));
	}

}
?>