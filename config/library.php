<?php
date_default_timezone_set('Asia/Jakarta'); // PHP 6 mengharuskan penyebutan timezone.
$seminggu = array("Minggu","Senin","Selasa","Rabu","Kamis","Jumat","Sabtu");
$hari = date("w");
$hari_ini = $seminggu[$hari];

$tgl_sekarang = date("Ymd");
$tgl_skrg     = date("d");
$bln_sekarang = date("m");
$thn_sekarang = date("Y");
$jam_sekarang = date("H:i:s");
$tgl_jam = date("Y-m-d H:i:s");
$setdate = date("d-m-Y");

$nama_bln=array(1=> "Januari", "Februari", "Maret", "April", "Mei", 
                    "Juni", "Juli", "Agustus", "September", 
                    "Oktober", "November", "Desember");
					
function tgl_indo($tgl){
	$tanggal = substr($tgl,8,2);
	$bulan = getBulan(substr($tgl,5,2));
	$tahun = substr($tgl,0,4);
	return $tanggal.'-'.$bulan.'-'.$tahun;		 
}

function tgl_short($tgl){
	$tanggal = substr($tgl,8,2);
	$bulan = getBulanShort(substr($tgl,5,2));
	$tahun = substr($tgl,0,4);
	return $bulan.' '.$tanggal.', '.$tahun;		 
}

function jam($tgl){
	$jam = substr($tgl,10,9);
	return $jam; 
}

function getBulan($bln){
	switch ($bln){
		case 1: 
		return "01";
		break;
		case 2:
		return "02";
		break;
		case 3:
		return "03";
		break;
		case 4:
		return "04";
		break;
		case 5:
		return "05";
		break;
		case 6:
		return "06";
		break;
		case 7:
		return "07";
		break;
		case 8:
		return "08";
		break;
		case 9:
		return "09";
		break;
		case 10:
		return "10";
		break;
		case 11:
		return "11";
		break;
		case 12:
		return "12";
		break;
	}
}

function getBulanShort($bln){
	switch ($bln){
		case 1: 
		return "Jan.";
		break;
		case 2:
		return "Feb.";
		break;
		case 3:
		return "Mar.";
		break;
		case 4:
		return "Apr.";
		break;
		case 5:
		return "May.";
		break;
		case 6:
		return "Jun.";
		break;
		case 7:
		return "Jul.";
		break;
		case 8:
		return "Agu.";
		break;
		case 9:
		return "Sep.";
		break;
		case 10:
		return "Okt.";
		break;
		case 11:
		return "Nov.";
		break;
		case 12:
		return "Des.";
		break;
	}
}

// YYYY-MM-DD					
function jin_date_sql($date){
	$exp = explode('-',$date);
	if(count($exp) == 3) {
		$date = $exp[2].'-'.$exp[1].'-'.$exp[0];
	}
	return $date;
}

// DD-MM-YYYY
function jin_date_str($date){
	$exp = explode('-',$date);
	if(count($exp) == 3) {
		$date = $exp[2].'/'.$exp[1].'/'.$exp[0];
	}
	return $date;
}

function service($service){
	switch ($service){
		case 1: 
		return "Top Urgent Courier";
		break;
		case 2:
		return "Urgent Courier";
		break;
		case 3:
		return "Regular Courier";
		break;
		case 4:
		return "Trucking Charter";
		break;
		case 5:
		return "Trucking Consol";
		break;
		case 6:
		return "International Express Regular";
		break;
		case 7:
		return "Sea Freight - FCL";
		break;
		case 8:
		return "Sea Freight - LCL";
		break;
	}
}

function sentence_case($string) {
    $sentences = preg_split('/([.?!]+)/', $string, -1, PREG_SPLIT_NO_EMPTY|PREG_SPLIT_DELIM_CAPTURE);
    $new_string = '';
    foreach ($sentences as $key => $sentence) {
        $new_string .= ($key & 1) == 0?
            ucfirst(strtolower(trim($sentence))) :
            $sentence.' ';
    }
    return trim($new_string);
}

// FUNGSI AMBIL KATA DARI KIRI
function strleft($leftstring, $length)
{
	return(substr($leftstring, 0, $length));
}

// FUNGSI AMBIL KATA DARI KANAN
function strright($rightstring, $length) 
{
	return(substr($rightstring, -$length));
}

?>
