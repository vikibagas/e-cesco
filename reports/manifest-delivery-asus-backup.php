<?php
include ('../config/connection.php');
include ('../config/encrypt.php');
include ('../config/library.php');
include ('phpqrcode/qrlib.php'); 

function like_match($pattern, $subject)
{
    $pattern = str_replace('%', '.*', preg_quote($pattern, '/'));
    return (bool) preg_match("/^{$pattern}$/i", $subject);
}

$key = "asdf$1011";
$encrypt = "$_GET[id]";
$id = base64_decrypt($encrypt,$key);

$pieces = explode('-', $id);
 $courier_by = $pieces['0'];
$courier_date = implode('-', array_slice($pieces, 1, 3));

$qu = mysqli_query($con, "select b.namalengkap, b.level from pengiriman a inner join user b on b.id_user=a.courier_by where a.courier_by='$courier_by' and courier_date='$courier_date'");


$currentDate = date('Y-m-d'); 

if($courier_date > $currentDate){
    $tanggal_courier = $currentDate;
}else{
    $tanggal_courier = $courier_date;
}


$r = mysqli_fetch_assoc($qu);

$query_kode_manifest = mysqli_query($con, "select kode_manifest from manifest 
    where tanggal='$tanggal_courier' and manifest_delivery_by = '$courier_by'");
$r_kode = mysqli_fetch_assoc($query_kode_manifest);


if($courier_date > $currentDate){
    $tanggal = tgl_indo($currentDate);
}else{
    $tanggal = tgl_indo($courier_date);
}


$header_asus =  '


                                    <tr style="background-color:#grey;">


                                        <td style="font-family:Calibri;text-align:center;font-size:36px;color:#000000;border-color:#000;border-top:0px solid;border-right:0px solid;border-bottom:0px solid; " >&nbsp;</td>

                                        <td style="font-family:Calibri;text-align:center;font-size:36px;color:#000000;border-color:#000;border-top:0px solid;border-right:0px solid;border-bottom:0px solid; " colspan="4">&nbsp;</td>

                                        <td style="font-family:Calibri;text-align:center;font-size:36px;color:#000000;border-color:#000;border-top:0px solid;border-right:0px solid;border-bottom:0px solid; " colspan="6">&nbsp;</td>
                                    </tr>

                                    <tr >


                                        <td style="font-family:Calibri;text-align:center;font-size:16px;color:#000000;border-color:#000;border-top:0px solid;border-right:0px solid;border-bottom:0px solid; " colspan="11">&nbsp;</td>
                                    </tr>
                                    <tr >
                                    <tr >


                                        <td style="font-family:Calibri;text-align:center;font-size:16px;color:#000000;border-color:#000;border-top:0px solid;border-right:0px solid;border-bottom:0px solid; " colspan="11"><b>DELIVERY MANIFEST</b></td>
                                    </tr>
                                    <tr >


                                        <td style="font-family:Calibri;text-align:center;font-size:16px;color:#000000;border-color:#000;border-top:0px solid;border-right:0px solid;border-bottom:0px solid; " colspan="11"><b>MANIFEST CODE : '.$r_kode['kode_manifest'].' </b></td>
                                    </tr>
                                    <tr >


                                        <td style="font-family:Calibri;text-align:left;font-size:14px;color:#000000;border-color:#000;border-top:0px solid;border-right:0px solid;border-bottom:0px solid; " colspan="11">TANGGAL : '.$tanggal.' </td>
                                    </tr>
                                    <tr >


                                        <td style="font-family:Calibri;text-align:left;font-size:14px;color:#000000;border-color:#000;border-top:0px solid;border-right:0px solid;border-bottom:0px solid; " colspan="11">KURIR : '.($r['namalengkap']).' </td>
                                    </tr>
                                    <tr >


                                        <td style="font-family:Calibri;text-align:left;font-size:14px;color:#000000;border-color:#000;border-top:0px solid;border-right:0px solid;border-bottom:0px solid; " colspan="11">&nbsp;</td>
                                    </tr>

                                    <tr >
                                    <tr >
                                    <td style="min-width:35px;max-width:35px;font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border:1px solid;">NO</td>

                                     <td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">ORDER NUMBER</td>


                                    <td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">AWB</td>
                                   

                                    
                                    <td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">ORIGIN ID</td>
                                      <td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">SHIP TO</td>

                                   
                                    

                                  

                                    

                                     <td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Weight</td>

                                     <td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Collie</td>

                                     
                                      <td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">ELM ORDER</td>

                                       <td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">TYPE</td>
                                      
                                         
                                         <td style="min-width:50px;max-width:65px;font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">CHECKLIST</td>
                                    
                                    
                                    
                                    <td style="min-width:110px;max-width:110px;font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">REMARKS</td>
                                    
                                </tr>
                                ';

$header_asus_break =  '


                                    <tr class="pageBreak" style="background-color:#grey;">


                                        <td style="font-family:Calibri;text-align:center;font-size:36px;color:#000000;border-color:#000;border-top:0px solid;border-right:0px solid;border-bottom:0px solid; " >&nbsp;</td>

                                        <td style="font-family:Calibri;text-align:center;font-size:36px;color:#000000;border-color:#000;border-top:0px solid;border-right:0px solid;border-bottom:0px solid; " colspan="4">&nbsp;</td>

                                        <td style="font-family:Calibri;text-align:center;font-size:36px;color:#000000;border-color:#000;border-top:0px solid;border-right:0px solid;border-bottom:0px solid; " colspan="6">&nbsp;</td>
                                    </tr>

                                    <tr >


                                        <td style="font-family:Calibri;text-align:center;font-size:16px;color:#000000;border-color:#000;border-top:0px solid;border-right:0px solid;border-bottom:0px solid; " colspan="11">&nbsp;</td>
                                    </tr>
                                    <tr >
                                    <tr >


                                        <td style="font-family:Calibri;text-align:center;font-size:16px;color:#000000;border-color:#000;border-top:0px solid;border-right:0px solid;border-bottom:0px solid; " colspan="11"><b>DELIVERY MANIFEST</b></td>
                                    </tr>
                                    <tr >


                                        <td style="font-family:Calibri;text-align:center;font-size:16px;color:#000000;border-color:#000;border-top:0px solid;border-right:0px solid;border-bottom:0px solid; " colspan="11"><b>MANIFEST CODE : '.$r_kode['kode_manifest'].' </b></td>
                                    </tr>
                                    <tr >


                                        <td style="font-family:Calibri;text-align:left;font-size:14px;color:#000000;border-color:#000;border-top:0px solid;border-right:0px solid;border-bottom:0px solid; " colspan="11">TANGGAL : '.$tanggal.' </td>
                                    </tr>
                                    <tr >


                                        <td style="font-family:Calibri;text-align:left;font-size:14px;color:#000000;border-color:#000;border-top:0px solid;border-right:0px solid;border-bottom:0px solid; " colspan="11">KURIR : '.($r['namalengkap']).' </td>
                                    </tr>
                                    <tr >


                                        <td style="font-family:Calibri;text-align:left;font-size:14px;color:#000000;border-color:#000;border-top:0px solid;border-right:0px solid;border-bottom:0px solid; " colspan="11">&nbsp;</td>
                                    </tr>

                                    <tr >
                                    <tr >
                                    <td style="min-width:35px;max-width:35px;font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border:1px solid;">NO</td>

                                     <td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">ORDER NUMBER</td>


                                    <td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">AWB</td>
                                    

                                    
                                    <td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">ORIGIN ID</td>
                                      <td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">SHIP TO</td>

                                   
                                    

                                  

                                    

                                     <td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Weight</td>

                                     <td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Collie</td>

                                     <td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">ELM ORDER</td>

                                     
                                       <td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">TYPE</td>
                                      
                                         
                                         <td style="min-width:50px;max-width:65px;font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">CHECKLIST</td>
                                    
                                    
                                    
                                    <td style="min-width:110px;max-width:110px;font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">REMARKS</td>
                                    
                                </tr>
                                ';


$paraf_asus =  '
                        <tr>
                            <td colspan="11">&nbsp;</td>
                            
                        </tr>
                        <tr>
                            <td colspan="4" style="text-align:center;">Courier </td>
                            <td colspan="2">&nbsp;</td>
                            <td colspan="4" style="text-align:center;">Penerima </td>
                        </tr>
                        <tr>
                            <td colspan="4" style="text-align:center;">'.ucwords($r['namalengkap']).' </td>
                            <td colspan="2">&nbsp;</td>
                            <td colspan="4" style="text-align:center;">Nama</td>
                            
                        </tr>
                        <tr>
                            <td colspan="11">&nbsp;</td>
                            
                        </tr>
                        <tr>
                            <td colspan="11">&nbsp;</td>
                            
                        </tr>
                        <tr>
                            <td colspan="11">&nbsp;</td>
                            
                        </tr>
                        <tr>
                            <td colspan="4"  style="text-align:center;">Hari/Tanggal/Jam</td>
                            <td colspan="2">&nbsp;</td>
                            <td colspan="4"  style="text-align:center;">Hari/Tanggal/Jam</td>
                        </tr>
                    ';

?>
<!DOCTYPE html>
    <html>
        <head>
            <meta charset="UTF-8">
            <title>Delivery Manifest</title>
            <style>
            
            @media print {
            .pageBreak {
                page-break-after: always;
            }
        }
            </style>
        </head>
        <body>
            <table cellspacing=0 width="100%">
                
                <?php
                $no=1;
                
                $result = mysqli_query($con,"select a.id_pengiriman,airwaybill_kode,alasan_terlambat, x_namakustomer(id_kustomer) as kustomer, x_namakotabesar(lokasi_asal) as lokasi_asal, x_namakotabesar(lokasi_tujuan) as lokasi_tujuan, collie, ifnull(berat_aktual,berat_barang) as berat_aktual,collie, case when komoditi='1' then 'Paket' else 'Dokumen' end as komoditi, delivery_name, 
                    kota_asal ,kota_tujuan,delivery_status,delivery_date,delivery_time,x_user(".$courier_by.") as nama_kurir,
                    id_kustomer,
                    perusahaan_pengirim,perusahaan_penerima,
                    jenis_barang,
                    remark,
                    aprc,

                    case  when remark LIKE '%BAD%' then 1 
                        when remark LIKE '%SCRAP%' then 1
                        else 2 end as status_remark, 

                    
                    
                    
                    DATE_FORMAT(pickup_date,'%Y-%m-%d') as tanggal_pickup,
                    berat_aktual,collie

                 from asus_detail_catatan a inner join pengiriman b on a.id_pengiriman=b.id_pengiriman

                 where courier_by='$courier_by' and DATE_FORMAT(courier_date,'%Y-%m-%d')='$courier_date' 
                 and id_kustomer=106

                        group by airwaybill_kode,(select status_remark)

                 order by 
                 case 
                    when remark LIKE '%BAD%' then 1
                   when remark LIKE '%SCRAP%' then 1 
                 else 2 end,
                 product



                 asc");



                //cari asus
                
                $num_rows_asus = mysqli_num_rows($result);

                $kustomer_akhir = '0';
                $jenis_barang_pakem = '0';
                $pakem = '0';
                $no_good = 0;   
                
                
                while($t=mysqli_fetch_assoc($result)){

                    $jumlah_rowspan = $t['collie']+2;
                    
                    $kustomer_pakem = $t['kustomer'];
                    $id_kustomer = $t['id_kustomer'];

                    //jika sudah halaman ke dua dan seterusnya
                    if($kustomer_akhir != $kustomer_pakem && $kustomer_akhir != '0'){
                    //$no = 10;

                            if($id_kustomer == 106){
                                echo $header_asus_break;
                            }


                                
                    }elseif($kustomer_akhir == '0'){
                        //jika halaman  diawal

                        if($id_kustomer == 106){
                            echo $header_asus;
                        }
                        

                        
                    }
                    

                    //ini data nya


                    // jika data asus
                    if($id_kustomer == 106){
                        $jenis_barang_pakem = $t['status_remark'];
                        
                        //$bad = 'BAD';
                         if($jenis_barang_pakem == 1){
                            

                            $pakem = 'bad';


                            


                        }else{
                            if($pakem == 'bad'){
                                $no_good = 1;
                                    echo $paraf_asus;
                                    echo $header_asus_break;

                            }

                            $pakem = '';
                            
                        }

                    

                        echo'
                    <tr>
                        <td  style="font-family:Calibri;text-align:center;font-size:14px;font-color:#000000;border-color:#000;border-left:1px solid;border-right:1px solid;border-bottom:1px solid;">'.($pakem == 'bad' ? $no : $no_good).'</td>

                        ';


                        $sqll_bad = mysqli_query($con, "select id_pengiriman,product,aprc,part_n,part_n_desc,rma_no,qty,remark from asus_detail_catatan  where id_pengiriman='".$t['id_pengiriman']."' and (remark like '%BAD%' or remark like '%SCRAP%') ");
                        $aprc_bad = '';
                        $remarks_bad = '';
                            echo '
                            <td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">
                            ';
                            
                            $cekAprc_bad = 0;
                            while ($row = mysqli_fetch_assoc($sqll_bad)) {
                                 echo $row['product'];
                                
                                $aprc_bad .=  ($cekAprc_bad > 0 ? '<hr style="border-top: 1px solid; margin: 0px -2px  0px;">' : '').$row['aprc'];
                                $remarks_bad .=  ($cekAprc_bad > 0 ? '<hr style="border-top: 1px solid; margin: 0px -2px  0px;">' : '').$row['remark'];
                                $cekAprc_bad++;
                              
                            }


                            $sqll_good = mysqli_query($con, "select id_pengiriman,product,aprc,part_n,part_n_desc,rma_no,qty,remark from asus_detail_catatan  where id_pengiriman='".$t['id_pengiriman']."' and remark not like '%BAD%' and remark not like '%SCRAP%' ");
                        $aprc_good = '';
                        $remarks_good = '';
                        
                            
                            $cekAprc_good = 0;
                            while ($row = mysqli_fetch_assoc($sqll_good)) {
                                 echo $row['product'];
                                
                                $aprc_good .=  ($cekAprc_good > 0 ? '<hr style="border-top: 1px solid; margin: 0px -2px  0px;">' : '').$row['aprc'];
                                $remarks_good .=  ($cekAprc_good > 0 ? '<hr style="border-top: 1px solid; margin: 0px -2px  0px;">' : '').$row['remark'];
                                $cekAprc_good++;
                              
                            }

                            echo '


                        <td style="font-family:Calibri;text-align:center;font-size:16px;color:#000000;border-color:#000;border-right:1px solid;border-top:1px solid;border-bottom:1px solid;"><b>'.$t['airwaybill_kode'].'</b></td>
                        
                        

                        <td  style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.($t['perusahaan_pengirim'] != '' ?  $t['perusahaan_pengirim'] : '&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;').'</td>
                        
                        <td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.($t['perusahaan_penerima'] != '' ?  $t['perusahaan_penerima'] : '&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;').' </td>
                        
                    
                        ';
                                $r_koli_bad= mysqli_query($con," select koli_ke,berat, p,l,t,berat_real,catatan 

                                     

                             from detail_collie where id_pengiriman=".$t['id_pengiriman']."  and (catatan  LIKE '%BAD%' or catatan like '%SCRAP%'  ) ");
                        $c_bad = '';
                        $berat_bad = '';
                        
                            $koli_bad = 0;
                            while($collie_bad=mysqli_fetch_assoc($r_koli_bad)){
                                $c_bad .= ($koli_bad > 0 ? '<hr style="border-top: 1px solid;margin: 0px -2px  0px;">' : '') .'('.$collie_bad['p'].'x'.$collie_bad['l'].'x'.$collie_bad['t'].') = '. number_format($collie_bad['berat_real']).'kg'.'<br>';
                                $berat_bad .= number_format($collie_bad['berat_real']).' kg';
                                $koli_bad++;
                            }

                            
                                $mulai_koli_good = $koli_bad+1;
                            


                            $r_koli_good= mysqli_query($con," select koli_ke,berat, p,l,t,berat_real,catatan 
                             from detail_collie where id_pengiriman=".$t['id_pengiriman']." and catatan not LIKE '%BAD%' and catatan not like '%SCRAP%'    ");
                        $c_good = '';
                        $berat_good = '';
                        
                            $koli_good = 0;
                            while($collie_good=mysqli_fetch_assoc($r_koli_good)){
                                $c_good .= ($koli_good > 0 ? '<hr style="border-top: 1px solid;margin: 0px -2px  0px;">' : '').'('.$collie_good['p'].'x'.$collie_good['l'].'x'.$collie_good['t'].') = '. number_format($collie_good['berat_real']).'kg '.'<br>';
                                $berat_good .= number_format($collie_good['berat_real']).' kg';

                                $koli_good++;
                            }

                        if($pakem == 'bad'){
                            if($koli_bad > 1){
                                $berat_hasil = $c_bad;
                            }else{
                                $berat_hasil = $c_bad;
                            }
                        }else{
                            if($koli_good > 1){
                                $berat_hasil = $c_good;
                            }else{
                                $berat_hasil = $c_good;
                            }
                        }


                        echo '

                        <td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$berat_hasil.'</td>                 <td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'. ($pakem == 'bad' ? $koli_bad : $koli_good) .'</td>                     
                        

                        <td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.($pakem == 'bad' ? $aprc_bad : $aprc_good).'</td>
                        
                        <td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.($pakem == 'bad' ? $remarks_bad : $remarks_good).'</td>

                        
                        
                        <td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">&nbsp;</td>
                        <td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">&nbsp;</td>
                        
                    </tr>';


                    

                    //jika nomer == jumlah data asus keluar paraf
                    if($no == $num_rows_asus){              
                        echo $paraf_asus;
                    }// akhir paraf                 


                }//akhir jika data asus
                    
                    //jika data bukan asus
                    else{
                        echo'
                    <tr>
                        <td rowspan='.$jumlah_rowspan.' style="font-family:Calibri;text-align:center;font-size:14px;font-color:#000000;border-color:#000;border-left:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$no.'</td>
                        

                        <td style="font-family:Calibri;text-align:center;font-size:16px;color:#000000;border-color:#000;border-right:1px solid;border-top:1px solid;border-bottom:1px solid;"><b>'.$t['airwaybill_kode'].'</b></td>
                        <td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['kustomer'].'</td>
                        <td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['kota_asal'].'</td>
                        <td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['kota_tujuan'].'</td>

                        ';

                            
                        echo '
                        
                        
                        
                        <td rowspan='.$jumlah_rowspan.'  style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.($t['delivery_name'] != '' ?  $t['delivery_name'] : '&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;').'</td>
                        <td rowspan='.$jumlah_rowspan.'  style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.($t['delivery_status'] != '' ?  $t['delivery_status'] : '&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;').'</td>
                        
                        <td rowspan='.$jumlah_rowspan.' style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </td>
                        <td rowspan='.$jumlah_rowspan.' style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.($t['delivery_date'] == '0000-00-00' ? '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' : tgl_indo($t['delivery_date'])).'</td>
                        <td rowspan='.$jumlah_rowspan.' style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'. $t['delivery_time'].'</td>
                        <td rowspan='.$jumlah_rowspan.' style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                        
                        
                    </tr>


                    <tr style="background:#eee;">
                        <td  style="font-family:Calibri;text-align:center;font-size:12px;color:#000000;border-color:#000;"><b>KOLI KE</b></td>
                        <td  style="font-family:Calibri;text-align:center;font-size:12px;color:#000000;border-color:#000;"><b>BERAT (KG)</b></td>
                        <td  style="font-family:Calibri;text-align:center;font-size:12px;color:#000000;border-color:#000;"><b>PXLXT(CM)</b></td>
                        <td  style="font-family:Calibri;text-align:center;font-size:12px;color:#000000;border-color:#000;border-right:1px solid;"><b>BERAT DIHITUNG(KG)</b></td>
                    </tr>
                    


                    ';
                    
                    $r = mysqli_query($con," select koli_ke,berat, p,l,t,berat_real 
                             from detail_collie where id_pengiriman=".$t['id_pengiriman']."  ");

                    $border_bottom_terakhir = "border-left:1px solid;";

                            while($collie=mysqli_fetch_assoc($r)){

                                echo '<tr>';
                                
                                    echo '<td style="font-family:Calibri;text-align:center;font-size:12px;color:#000000;border-color:#000;" >'.$collie['koli_ke'].'</td>';
                                    
                                    echo '<td style="font-family:Calibri;text-align:center;font-size:12px;color:#000000;border-color:#000;"> '.$collie['berat'].'</td>';
                                    
                                    echo '<td style="font-family:Calibri;text-align:center;font-size:12px;color:#000000;border-color:#000;">'. $collie['p'].'x'.$collie['l'].'x'.$collie['t'].'</td>';
                                    
                                    echo '<td style="font-family:Calibri;text-align:center;font-size:12px;color:#000000;border-color:#000;border-right:1px solid;">'.$collie['berat_real'].'</td>';
                                
                                echo '</tr>';
                            }
                    }//akhir data jika bukan asus




                    
                    
                        

                    $kustomer_akhir = $kustomer_pakem;

                     $kustomer_akhir;
                    $no++;
                    $no_good++;


                }
                
                ?>
                
                <?php
                $biaya = mysqli_query($con, "select case when count(id_pengiriman)<=10 then count(id_pengiriman)*2000 when count(id_pengiriman)>10 and count(id_pengiriman)<=15 then 20000+1300 end as jml from pengiriman a inner join user b on b.id_user=a.courier_by where a.delivery_by='$courier_by' and DATE_FORMAT(a.delivery_date,'%Y-%m-%d')='$courier_date'");

                $costing = mysqli_query($con,"select sum(costing_delivery_fee) as costing_delivery_fee, sum(costing_delivery_parking) as costing_delivery_parking, sum(costing_delivery_toll) as costing_delivery_toll, sum(costing_delivery_menpower) as costing_delivery_menpower, 
                    
                     (select costing_delivery_fee)+(select costing_delivery_parking)+(select costing_delivery_menpower)+(select costing_delivery_toll)  as total
                    
                    from pengiriman where courier_by='$courier_by' and DATE_FORMAT(ifnull(manifest_delivery_date,courier_date),'%Y-%m-%d')='$courier_date' ");
$c = mysqli_fetch_assoc($costing);

                ?>
                
    <!--            <tr>
                    <td colspan="2" style="font-family:Calibri;font-size:14px;color:#000000;text-align:center;">TTD KURIR / DRIVER</td>
                    <td>&nbsp;</td>
                    <td colspan="2" style="font-family:Calibri;font-size:14px;color:#000000;text-align:center;">TTD MGR / SPV</td>
                    <td>&nbsp;</td>
                    <td style="font-family:Calibri;font-size:14px;color:#000000;border:1px solid;">BENSIN</td>
                    <td style="font-family:Calibri;text-align:right;font-size:14px;color:#000000;border:1px solid;"><?php echo number_format(round($c['costing_delivery_fee'])); ?></td>
                </tr>
                <tr>
                    <td colspan="6">&nbsp;</td>
                    <td style="font-family:Calibri;font-size:14px;color:#000000;border:1px solid;">PARKIR</td>
                    <td style="font-family:Calibri;text-align:right;font-size:14px;color:#000000;border:1px solid;"><?php echo number_format(round($c['costing_delivery_parking'])); ?></td>
                </tr>
                <tr>
                    <td colspan="6">&nbsp;</td>
                    <td style="font-family:Calibri;font-size:14px;color:#000000;border:1px solid;">TOL</td>
                    <td style="font-family:Calibri;text-align:right;font-size:14px;color:#000000;border:1px solid;"><?php echo number_format(round($c['costing_delivery_toll'])); ?></td>
                </tr>
              
                
                <tr>
                    <td>(</td>
                    <td style="text-align:right;">)</td>
                    <td>&nbsp;</td>
                    <td>(</td>
                    <td style="text-align:right;">)</td>
                    <td>&nbsp;</td>
                    <td style="font-family:Calibri;font-size:14px;color:#000000;border:1px solid;">KULI</td>
                    <td style="font-family:Calibri;text-align:right;font-size:14px;color:#000000;border:1px solid;"><?php echo number_format(round($c['costing_delivery_menpower'])); ?></td>
                </tr>               
                <tr>
                    <td colspan="6">&nbsp;</td>
                    <td style="font-family:Calibri;font-size:14px;color:#000000;font-weight:bold;border:1px solid;">TOTAL</td>
                    <td style="font-family:Calibri;text-align:right;font-size:14px;color:#000000;border:1px solid;">Rp <?php echo number_format(round($c['costing_delivery_menpower']+$c['costing_delivery_toll']+$c['costing_delivery_parking']+$c['costing_delivery_fee'])); ?></td>
                </tr>-->
                
            </table>
            <script type="text/javascript">
           // window.print();         
            </script>
        </body>
</html>