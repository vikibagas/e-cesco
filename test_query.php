<?php
$id = 269866;
$pickup_request = 89;

$q = "SELECT case when id_kota_asal > 0 then x_inter(id_kota_asal) else  x_intercity(lokasi_asal) end as intercity,alasan_terlambat_pickup, case when id_kota_asal>0 then id_kota_asal else lokasi_asal end as lokasi_asal, id_kota_asal, id_kustomer,service,created_date,id_provinsi_asal, x_hubnew(id_kota_asal) as hub,
			loc_code,airwaybill_kode, kecamatan_asal, kota_asal,x_user(".$pickup_request.") as nama_kurir

		 FROM pengiriman WHERE id_pengiriman='".$id."'";

		 echo $q;
?>