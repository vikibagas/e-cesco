<?php
$module = !empty($_GET['module']) ? $_GET['module'] : '';

if ($module=='home'){
	include ('home.php');
}
elseif($module=='booking'){
	include "module/mod_booking/booking.php";
}
elseif($module=='bookinginv2'){
	include "module/mod_bookinginv2/bookinginv2.php";
}
elseif($module=='airwaybill'){
	include "module/mod_airwaybill/airwaybill.php";
}
elseif($module=='report-airwaybill-by-date'){
	include "module/mod_report-airwaybill-by-date/report-airwaybill-by-date.php";
}
elseif($module=='profile'){
	include "module/mod_profile/profile.php";
}
?>