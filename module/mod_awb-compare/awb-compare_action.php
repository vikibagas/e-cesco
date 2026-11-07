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
	$key 		= "asdf$1011";
	$user 		= $_SESSION['id_user'];
	$id_user 	= base64_decrypt($user,$key);
	
	$aksi="module/mod_invoice-customer/invoice-customer_action.php";
	
	// List paket
	if ($module=='awb-compare' and $act=='compare')
	{
		
		$periode = $_POST['tanggal'];
		$fileDoc = $_FILES['xlsFile']['name'];
		$newFileName = __DIR__ . '/../../upload/compare/'.$id_user . '-' . $fileDoc;
		
		$result = array();
		
		if (move_uploaded_file($_FILES['xlsFile']['tmp_name'], $newFileName)){						
						
			include 'SimpleXLSX.php';
						
			$xlsx = SimpleXLSX::parse($newFileName);
			
			if ($xlsx) {

				$x = 0;
				$posErr = '';
				$idKustomer = '';
				$cekCust = 0;
				$arrAWB = array();
				
				foreach ($xlsx->rows() as $row){

					if ($x == 0){ //Cek Format
						$errFormat = 0;
						if (count($row) != 8){

							$errFormat++;

							$posErr = 'Column Count: '.count($row);

						}else{

							if ($row[0] != "AWB" ||
								$row[1] != "KG" ||
								$row[2] != "P" ||
								$row[3] != "L" ||
								$row[4] != "T" ||
								$row[5] != "SERVICE" ||
								$row[6] != "ASAL" ||
								$row[7] != "TUJUAN"
								) {

								$errFormat++;
								$posErr = 'Column Header';
							}
						}

						if ($errFormat > 0) {
							
							$resCek = array(
								'AWB' => 'Data Error '.$errFormat.': '.$posErr,
								'KG' => '',
								'P' => '',
								'L' => '',
								'T' => '',
								'Service' => '',
								'Asal' => '',
								'Tujuan' => '',
								'Cek' => 'FALSE'
							);
							
							array_push($result, $resCek);

						}
					}
					else{
						
						$awb = $row[0];
						$kg = $row[1];
						$p = $row[2];
						$l = $row[3];
						$t = $row[4];
						$service = $row[5];
						$asal = $row[6];
						$tujuan = $row[7];
						$arrAWB[] = $awb;
						
						$sql = "SELECT 
									id_pengiriman,
									id_kustomer,
									airwaybill_kode,
									ukuran_barang,
									if(berat_aktual is null or berat_aktual < 1, berat_barang, berat_aktual) as berat_real,
									pel.nama_pelayanan as service,
									if(a.id_kota_tujuan > 0, a.kota_asal, x_namakotabesar(lokasi_asal)) as lokasi_asal,
									if(a.id_kota_tujuan > 0, a.kota_tujuan, x_namakotabesar(lokasi_tujuan)) as lokasi_tujuan            
								FROM
									pengiriman a
								left join pelayanan pel ON pel.id_pelayanan = a.service
								where
									a.airwaybill_kode = '".$awb."'";
						
						$airwaybill		= $mysqli->query($sql);												
						$count			= $airwaybill->num_rows;
						
						if ($count>0){
							
							$res = $airwaybill->fetch_assoc();
							$nok = 0;
							$nokStts = '';
							
							if ($cekCust == 0){											
								$idKustomer = $res['id_kustomer'];								
								$cekCust++;
							}
							
							
							
							// length x widht x height
							// p x l x t
							$arrDim = explode("X", strtoupper($res['ukuran_barang']));
							
							
							if ($arrDim[0] != $p) { 
								$nok++; 
								$nokStts .= '#"'.$arrDim[0] .'"!="'. $p.'#'; 
								$p = "<font style='color:#FF0000';>".$p."</font>";
							}
							if ($arrDim[1] != $l) { 
								$nok++; 
								$nokStts .= '#"'.$arrDim[1] .'"!="'. $l.'#'; 
								$l = "<font style='color:#FF0000';>".$l."</font>";
							}
							if ($arrDim[2] != $t) { 
								$nok++; 
								$nokStts .= '#'.$arrDim[2] ."!=". $t; 
								$t = "<font style='color:#FF0000';>".$t."</font>";
							}
							if (strtoupper(trim($res['berat_real'])) != strtoupper(trim($kg))){ 
								$nok++; 
								$nokStts .= '#"'.strtoupper($res['berat_real']) .'"!="'. strtoupper($kg).'"';
								$kg = "<font style='color:#FF0000';>".$kg."</font>";
							}
							if (strtoupper(trim($res['service'])) != strtoupper(trim($service))){ 
								$nok++; 
								$nokStts .= '#"'.strtoupper($res['service']) .'"!="'. strtoupper(trim($service)).'"'; 
								$service = "<font style='color:#FF0000';>".$service."</font>";
							}
							
							
							$posAsal = strpos(strtoupper(trim($res['lokasi_asal'])), strtoupper(trim($asal)));
							if ($posAsal === false) {
								$nok++;
								$nokStts .= '#"'.strtoupper(trim($res['lokasi_asal'])) .'"!="'. strtoupper(trim($asal)).'"';
								$asal = "<font style='color:#FF0000';>".$asal."</font>";
							}
							
							$posTujuan = strpos(strtoupper(trim($res['lokasi_tujuan'])), strtoupper(trim($tujuan)));
							if ($posTujuan === false) {
								$nok++;
								$nokStts .= '#"'.strtoupper(trim($res['lokasi_tujuan'])) .'"!="'. strtoupper(trim($tujuan)).'"';
								$tujuan = "<font style='color:#FF0000';>".$tujuan."</font>";
							}
							
							
							$cek = ($nok == 0) ? 'TRUE' : 'FALSE ('.$nokStts.')';
							
						}else $cek = 'NOT IN DATABASE';
						
						$resCek = array(
							'AWB' => $awb,
							'KG' => $kg,
							'P' => $p,
							'L' => $l,
							'T' => $t,
							'Service' => $service,
							'Asal' => $asal,
							'Tujuan' => $tujuan,
							'Cek' => $cek
						);
						
						array_push($result, $resCek);
						
						
					}

					$x++;
				}
				
				if ($idKustomer != ''){
										
					$sqlCust = "SELECT 
							id_pengiriman,
							id_kustomer,
							airwaybill_kode,
							ukuran_barang,
							if(berat_aktual is null or berat_aktual < 1, berat_barang, berat_aktual) as berat_real,
							pel.nama_pelayanan as service,
							if(a.id_kota_tujuan > 0, a.kota_asal, x_namakotabesar(lokasi_asal)) as lokasi_asal,
							if(a.id_kota_tujuan > 0, a.kota_tujuan, x_namakotabesar(lokasi_tujuan)) as lokasi_tujuan            
						FROM
							pengiriman a
						left join pelayanan pel ON pel.id_pelayanan = a.service
						where
							a.id_kustomer = ".$idKustomer." 
							and date_format(a.tanggal, '%Y-%m') = '".$periode."'
							and airwaybill_kode not in ('".implode("','",$arrAWB)."')";
								
				
					$query	= $mysqli->query($sqlCust);	
					$resPeriode = array();
					while($r = $query->fetch_assoc()){
						$arrDim = explode("X", strtoupper($r['ukuran_barang']));
						$resCek = array(
							'AWB' => $r['airwaybill_kode'],
							'KG' => $r['berat_real'],
							'P' => $arrDim[0],
							'L' => $arrDim[1],
							'T' => $arrDim[2],
							'Service' => $r['service'],
							'Asal' => $r['lokasi_asal'],
							'Tujuan' => $r['lokasi_tujuan'],
							'Cek' => 'NOT IN EXCEL'
						);
						
						array_push($result, $resCek);
						
					}						
				}				

			} else {
				$result = SimpleXLSX::parse_error();
			}
		}

		echo json_encode($result);
	
	} 
	
}
?>