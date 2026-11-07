<?php
if(!isset($_SESSION)){
    session_start();
}

if (empty($_SESSION['username']) AND empty($_SESSION['password'])){
	header('location:../../index.php');
}
else{
	include "../../config/db_connect.php";
	include "../../config/encrypt.php";
	
	$module	= !empty($_GET['module']) ? $_GET['module'] : '';
	$act	= !empty($_GET['act']) ? $_GET['act'] : '';
	
	$aksi="module/mod_sla/sla_action.php";
	
	if($module=='sla' and $act=='create'){
		$sla_to = $_GET['sla'];

		$id_kota_asal = $mysqli->real_escape_string($_POST['id_kota_asal']);
		$id_kota_tujuan = $mysqli->real_escape_string($_POST['id_kota_tujuan']);
		$id_pelayanan = $mysqli->real_escape_string($_POST['id_pelayanan']);			
		$sla_days = $mysqli->real_escape_string($_POST['sla']);
		
		if($sla_to == 'cross'){
			$query =	"INSERT INTO sla_cross (id,kota_asal,kota_tujuan, service, sla_min ,sla_max)
									VALUES ('', '$id_kota_asal','$id_kota_tujuan', '$id_pelayanan', '$sla_days','$sla_days')";	

			$result = $mysqli->query($query);
			header('location:../../main.php?module=sla-cross');
		}
								
	}

	if($module=='sla' and $act=='insertcross'){
		$sla_to = $_GET['sla'];

		$id_kota_asal = $mysqli->real_escape_string($_POST['id_kota_asal']);
		$id_kota_tujuan = $mysqli->real_escape_string($_POST['id_kota_tujuan']);
		$id_pelayanan = $mysqli->real_escape_string($_POST['id_pelayanan']);			
		$sla_days = $mysqli->real_escape_string($_POST['sla']);
		
		if($sla_to == 'cross'){
			$query =	"INSERT INTO sla_cross (id,kota_asal,kota_tujuan, service, sla_min ,sla_max)
									VALUES ('', '$id_kota_asal','$id_kota_tujuan', '$id_pelayanan', '$sla_days','$sla_days')";	

			$result = $mysqli->query($query);
			header('location:../../main.php?module=sla-cross');
		}
								
	}

	elseif($module=='sla' and $act=='update'){
		$rateagent_id = $mysqli->real_escape_string($_POST['rateagent_id']);
		$id_kota = $mysqli->real_escape_string($_POST['id_kota']);
		$id_pelayanan = $mysqli->real_escape_string($_POST['id_pelayanan']);
		$komoditi = $mysqli->real_escape_string($_POST['komoditi']);
		$min_kg = $mysqli->real_escape_string($_POST['min_kg']);
		$price = $mysqli->real_escape_string($_POST['price']);
		
		$query =	"UPDATE rate_agent SET id_kota='$id_kota', 
										id_pelayanan='$id_pelayanan', 
										komoditi='$komoditi', 
										min_kg='$min_kg', 
										price='$price' 
					WHERE rateagent_id='$rateagent_id'";
		$result = $mysqli->query($query);
		
		header('location:../../main.php?module='.$module);
	
	}

	elseif($module=='sla' and $act=='updatecross'){
		$id = $mysqli->real_escape_string($_POST['id_sla']);
		$sla = $mysqli->real_escape_string($_POST['sla']);
		
		$query =	"UPDATE sla_cross SET 
										sla_max = '$sla', 
										
										
					WHERE id='$id'";
		$result = $mysqli->query($query);
		
		header('location:../../main.php?module=sla-cross');
	
	}

	elseif($module=='sla' and $act=='delete'){
		$key = "asdf$1011";
		$encrypt = $mysqli->real_escape_string($_GET['id']);
		$id = base64_decrypt($encrypt,$key);
		
		$query = "DELETE FROM rate_agent WHERE rateagent_id='$id'";
		$result = $mysqli->query($query);
		
		header('location:../../main.php?module='.$module);
		
	}
	elseif($module=='sla' and $act=='deletecross'){
		$key = "asdf$1011";
		$encrypt = $mysqli->real_escape_string($_GET['id']);
		$id = base64_decrypt($encrypt,$key);
		
		$query = "DELETE FROM sla_cross WHERE id='$id'";
		$result = $mysqli->query($query);
		
		header('location:../../main.php?module=sla-cross');
		
	}
	elseif ($act=='min-kg') {
		$query = "update rate_agent set min_kg='$_POST[value]' where rateagent_id='$_POST[pk]'";
		$result = $mysqli->query($query);
		$result->free();
		
	} elseif($act=='price') {
		$query = "update rate_agent set price='$_POST[value]' where rateagent_id='$_POST[pk]'";
		$result = $mysqli->query($query);
		$result->free();
		
	}

	elseif($module=='sla' and $act=='insert-sla-outbound') {
		
		
		$service = $mysqli->real_escape_string($_POST['service']);
		$sla = $_POST['sla'];
		//echo $service.'<br>';
		foreach ($sla as $s => $value) {
			if ($value != ''){
				//echo "insert into sla (id,kota_asal,kota_tujuan,service,sla_max) values ('','9999','$s','$service','$value') <br>";
				$result = $mysqli->query("insert into sla (id,kota_asal,kota_tujuan,service,sla_max) values ('','9999','$s','$service','$value') ");
				/*$result = $mysqli->query("insert into sla (id,kota_asal,kota_tujuan,service,sla_max) values ('','9999','$s','3','$value') ");
				$result = $mysqli->query("insert into sla (id,kota_asal,kota_tujuan,service,sla_max) values ('','9999','$s','5','$value') ");
				$result = $mysqli->query("insert into sla (id,kota_asal,kota_tujuan,service,sla_max) values ('','9999','$s','12','$value') ");*/
				//echo $value.'&'.$s.'<br><br>';
			}
		}

		header('location:../../main.php?module=sla-outbound');
		//$query = "update rate_agent set price='$_POST[value]' where rateagent_id='$_POST[pk]'";
		//$result = $mysqli->query($query);
		//$result->free();
		
	}

	elseif($module=='sla' and $act=='insert-sla-inbound') {
		
		
		$service = $mysqli->real_escape_string($_POST['service']);
		$sla = $_POST['sla'];
		//echo $service.'<br>';
		foreach ($sla as $s => $value) {
			if ($value != ''){
				//echo "insert into sla (id,kota_asal,kota_tujuan,service,sla_max) values ('','9999','$s','$service','$value') <br>";
				$result = $mysqli->query("insert into sla_in (id,kota_asal,kota_tujuan,service,sla_max) values ('','$s','9999','$service','$value') ");

				//echo $value.'&'.$s.'<br><br>';
			}
		}

		header('location:../../main.php?module=sla-inbound');
		//$query = "update rate_agent set price='$_POST[value]' where rateagent_id='$_POST[pk]'";
		//$result = $mysqli->query($query);
		//$result->free();
		
	}

	elseif($module=='sla' and $act=='insert-sla-cross') {
		
		
		//$sla_to = $_GET['sla'];

		$id_kota_asal = $mysqli->real_escape_string($_POST['id_kota_asal']);
		$id_kota_tujuan = $mysqli->real_escape_string($_POST['id_kota_tujuan']);
		$id_pelayanan = $mysqli->real_escape_string($_POST['id_pelayanan']);			
		$sla_days = $mysqli->real_escape_string($_POST['sla']);
		
		
			$query =	"INSERT INTO sla_cross (id,kota_asal,kota_tujuan, service, sla_min ,sla_max)
									VALUES ('', '$id_kota_asal','$id_kota_tujuan', '$id_pelayanan', '$sla_days','$sla_days')";	

			$result = $mysqli->query($query);
			header('location:../../main.php?module=sla-cross');
		//}
		
	}
	elseif($module=='sla' and $act=='insert-sla-deliv') {
		
		
		$service = $mysqli->real_escape_string($_POST['service']);
		$sla = $_POST['sla'];
		//echo $service.'<br>';
		foreach ($sla as $s => $value) {
			if ($value != ''){
				//echo "insert into sla (id,kota_asal,kota_tujuan,service,sla_max) values ('','9999','$s','$service','$value') <br>";
				$result = $mysqli->query("insert into sla_deliv (id,kota_tujuan,service,sla) values ('','$s','$service','$value') ");
				//$result = $mysqli->query("insert into sla_deliv (id,kota_tujuan,service,sla) values ('','$s','5','$value') ");

				//echo $value.'&'.$s.'<br><br>';
			}
		}

		header('location:../../main.php?module=sla-deliv');
		//$query = "update rate_agent set price='$_POST[value]' where rateagent_id='$_POST[pk]'";
		//$result = $mysqli->query($query);
		//$result->free();
		
	}

	elseif($module=='sla' and $act=='insert-sla-delivdhl') {
		
		
		$service = $mysqli->real_escape_string($_POST['service']);
		$sla = $_POST['sla'];
		//echo $service.'<br>';
		foreach ($sla as $s => $value) {
			if ($value != ''){
				//echo "insert into sla (id,kota_asal,kota_tujuan,service,sla_max) values ('','9999','$s','$service','$value') <br>";
				$result = $mysqli->query("insert into sla_deliv_dhl (id,kota_tujuan,service,sla) values ('','$s','$service','$value') ");

				//$result = $mysqli->query("insert into sla_deliv_dhl (id,kota_tujuan,service,sla) values ('','$s','5','$value') ");

				//echo $value.'&'.$s.'<br><br>';
			}
		}

		header('location:../../main.php?module=sla-delivdhl');
		//$query = "update rate_agent set price='$_POST[value]' where rateagent_id='$_POST[pk]'";
		//$result = $mysqli->query($query);
		//$result->free();
		
	}
}
?>