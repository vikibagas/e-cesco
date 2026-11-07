<?php 
require_once('../../config/encrypt.php');
$id_cabang = 80;

$key = "asdf$1011";


$periode = [];
for ($year = 2023; $year <= 2024; $year++) {
    for ($month = 1; $month <= 12; $month++) {
        $periode[] = sprintf("%04d-%02d", $year, $month);
    }
}

// 1. Pengulangan dengan for
for ($i = 0; $i < count($periode); $i++) {
    echo "$periode[$i]" ;
    $kode = $id_cabang.'~'.$periode[$i];

    echo '<a class="btn btn-xs btn-primary" target="_blank" href="https://www.cesco-logistics.com/e-cesco/main.php?module=invoice-agent&act=update&id='.base64_encrypt($kode,$key).'"><i class="fa fa-search"></i> View </a>'.'<br>';
}



?>