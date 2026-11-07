
<?php
	$module = !empty($_GET['module']) ? $_GET['module'] : '';
	if ($module=='tracking-courier'){
		echo '<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" >';
	}
?>