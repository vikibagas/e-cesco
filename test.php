<?php
$ch = curl_init(); 

$url = "https://apim.workato.com/vdi/wms-v1/sap";

 // set url 


$header = ['API-TOKEN' => '88d5307e42f42bd756180ff70f16fa058375e98ddeb71a3dc9bf286c5a67efed'];

$data = 
[
  'NoAWB' 						=> '001-00123456',
  'NoDR' 						=> '12345678',
  'StatusDelivery'   			=> '1',
  'DeliveryDescription' 		=> 'paket sudah dipickup oleh bambang',
  'StatusDeliveryUpdateDate' 	=> '2023-01-26 13:00:00',
  'ReceiverName' 				=> 'vicky',
  'NumberOfColly'  				=> '2',
  'Volumetric' 					=> '5x4x1',
  'TotalKilo'  					=> '1'

];


curl_setopt($ch, CURLOPT_URL, $url); 

curl_setopt($ch, CURLOPT_POST,true);
curl_setopt($ch, CURLOPT_POSTFIELDS,$data);

//return the transfer as a string 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER,$header);

$resp = curl_exec($ch);

if($e = curl_error($ch)){
	echo $e;
} else{
	$decoded = json_decode($resp);
	foreach ($decoded as $key => $val) {
		echo $key.' : '. $val. '<br>'; 
	}
}
curl_close($ch);


?>