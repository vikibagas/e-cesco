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
	
	$aksi="module/mod_agent-outbound/agent-outbound_action.php";
	
	if($module=='agent-outbound' and $act=='create'){
		$id_kota = $mysqli->real_escape_string($_POST['id_kota']);
		$id_pelayanan = $mysqli->real_escape_string($_POST['id_pelayanan']);
		$komoditi = $mysqli->real_escape_string($_POST['komoditi']);
		$min_kg = $mysqli->real_escape_string($_POST['min_kg']);
		$price = $mysqli->real_escape_string($_POST['price']);
		
		$query =	"INSERT INTO rate_agent (id_kota, id_pelayanan, komoditi, min_kg, price, id_cabang)
									VALUES ('$id_kota', '$id_pelayanan', '$komoditi', '$min_kg', '$price', '39')";
		$result = $mysqli->query($query);
		
		header('location:../../main.php?module='.$module);
		
	}elseif($module=='agent-outbound' and $act=='update'){
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
	
	}elseif($module=='agent-outbound' and $act=='delete'){
		$key = "asdf$1011";
		$encrypt = $mysqli->real_escape_string($_GET['id']);
		$id = base64_decrypt($encrypt,$key);
		
		$query = "DELETE FROM rate_agent WHERE rateagent_id='$id'";
		$result = $mysqli->query($query);
		
		header('location:../../main.php?module='.$module);
		
	}elseif ($act=='min-kg') {
		$query = "update rate_agent set min_kg='$_POST[value]' where rateagent_id='$_POST[pk]'";
		$result = $mysqli->query($query);
		$result->free();
		
	} elseif($act=='price') {
		$query = "update rate_agent set price='$_POST[value]' where rateagent_id='$_POST[pk]'";
		$result = $mysqli->query($query);
		$result->free();
		
	}
}
?>