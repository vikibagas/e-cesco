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

// UPDATE: Hanya memasukkan kode singkatan dari kategori BAD dan SCRAP saja sesuai instruksi
$bad_scrap_only = "'BPT', 'BBT', 'BUT', 'SBD', 'SBB', 'SOK', 'SOB'";

?>

<!DOCTYPE html>
<html>
<head>
    <meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />
    <title>Airwaybill Cesco Logistics</title>
    <link rel='stylesheet' type='text/css' href='../dist/css/manifest_deliv.css' />
    <style type="text/css">
        @media print 
        {
            .pageBreak {
                page-break-after: always;
            }
        }
    </style>
</head>
<body>
    <table id="items">
         <tr>
            <td colspan="11" class="blank"><h3>DELIVERY MANIFEST </h3></td>
        </tr>
        <tr>
            <td colspan="11" class="blank"><h3>MANIFEST CODE : <?php echo $r_kode['kode_manifest'];  ?></h3></td>
        </tr>
         <tr>
            <td colspan="11" class="blank" style=" padding-left:10px;text-align: left;">
                Kurir :  <?php echo $r['namalengkap'];  ?><br>
                Tanggal : <?php echo $tanggal;  ?>
            </td>
        </tr>
        <tr>
            <th>NO</th>
            <th>ORDER NUMBER</th>
            <th>AWB</th>
            <th>ORIGIN ID</th>
            <th>SHIP TO</th>        
            <th>ELM ORDER</th>
            <th>WEIGHT</th>
            <th>COLLIE</th>
            <th>TYPE</th>
            <th>CHECKLIST</th>
            <th>REMARKS</th>
        </tr>

           <?php 
                // FILTER: Hanya mengambil data yang remark-nya ada di daftar BAD & SCRAP
                $sqll_bad = mysqli_query($con, "SELECT 
                                                    perusahaan_pengirim,
                                                    perusahaan_penerima, 
                                                    a.id_pengiriman,
                                                    airwaybill_kode,
                                                    product,
                                                    aprc,
                                                    part_n,
                                                    part_n_desc,
                                                    rma_no,
                                                    qty,
                                                    remark,
                                                    berat_aktual,
                                                    collie
                                                FROM asus_detail_catatan a 
                                                INNER JOIN pengiriman b ON a.id_pengiriman = b.id_pengiriman
                                                WHERE remark IN ($bad_scrap_only) 
                                                  AND b.courier_by = '$courier_by'                                                   
                                                  AND (
                                                      (DATE(b.courier_date) = '$courier_date' AND '$courier_date' != CURDATE())
                                                      OR 
                                                      (DATE(b.courier_date) >= CURDATE() AND '$courier_date' = CURDATE())
                                                  )
                                                  AND id_kustomer = 106
                                                GROUP BY airwaybill_kode
                                                ORDER BY airwaybill_kode, product ");
                  

                $no = 1;
                $do = '';

                  while ($row = mysqli_fetch_assoc($sqll_bad)) {
                    $do = $row['product'];
                    echo '<tr>';
                        echo '<td> '.$no.' </td>';
                        echo '<td> ';
                        
                         $sqll_product = mysqli_query($con, "select product from asus_detail_catatan where id_pengiriman=".$row['id_pengiriman']." and product is not null ");
                        $r_product = mysqli_fetch_assoc($sqll_product);
                        echo $r_product['product'];
                        

                        echo '</td>';
                        echo '<td> '.$row['airwaybill_kode'].' </td>';
                        echo '<td> '.$row['perusahaan_pengirim'].' </td>';
                        echo '<td> '.$row['perusahaan_penerima'].' </td>';                                                                                                        
                        echo '<td> ';

                        // FILTER SUB-QUERY: Sesuai daftar BAD & SCRAP
                        $sql_aprc = mysqli_query($con, "select aprc,remark from asus_detail_catatan where id_pengiriman=".$row['id_pengiriman']."  and remark IN ($bad_scrap_only) ");
                        $aprc_bad = '';
                        $remarks_bad = '';
                        $cekAprc_bad = 0;
                        while ($rows = mysqli_fetch_assoc($sql_aprc)) {
                               
                                
                                $aprc_bad .=  ($cekAprc_bad > 0 ? '<hr style="border-top: 1px solid; margin: 0px -2px  0px;">' : '').$rows['aprc'];
                                $remarks_bad .=  ($cekAprc_bad > 0 ? '<hr style="border-top: 1px solid; margin: 0px -2px  0px;">' : '').$rows['remark'];
                                $cekAprc_bad++;
                              
                            }
                            echo $aprc_bad;

                        echo '</td>';
                        echo '<td> '; 

                            // FILTER SUB-QUERY COLLIE: Sesuai daftar BAD & SCRAP
                             $r_koli_bad= mysqli_query($con," select koli_ke,berat, p,l,t,berat_real,catatan 
                             from detail_collie where id_pengiriman=".$row['id_pengiriman']."  and catatan IN ($bad_scrap_only) ");
                        $c_bad = '';
                        $berat_bad = '';
                        
                            $koli_bad = 0;
                            while($collie_bad=mysqli_fetch_assoc($r_koli_bad)){
                                $c_bad .= ($koli_bad > 0 ? '<hr style="border-top: 1px solid;margin: 0px -2px  0px;">' : '') .'('.$collie_bad['p'].'x'.$collie_bad['l'].'x'.$collie_bad['t'].') = '. number_format($collie_bad['berat_real']).'kg'.'<br>';
                               

                                
                                $koli_bad++;
                            }
                            echo $c_bad;

                        echo ' </td>';
                        
                        echo '<td> ';

                            echo $koli_bad;

                        echo ' </td>';
                        echo '<td> '.$remarks_bad.' </td>';
                        echo '<td> &nbsp; </td>';
                        echo '<td> &nbsp; </td>';
                    echo '</tr>';


                    $no++;

                  }

                   echo '
                        <tr  class="blank">
                            <td  class="blank"  colspan="11">&nbsp;</td>
                            
                        </tr>
                        <tr>
                            <td class="blank" colspan="4" style="text-align:center;">Courier </td>
                            <td colspan="2"  class="blank">&nbsp;</td>
                            <td colspan="4" style="text-align:center;"  class="blank">Penerima </td>
                        </tr>
                        <tr>
                            <td colspan="4" style="text-align:center;"  class="blank">'.ucwords($r['namalengkap']).' </td>
                            <td colspan="2"  class="blank">&nbsp;</td>
                            <td colspan="4" style="text-align:center;"  class="blank">Nama</td>
                            
                        </tr>
                        <tr>
                            <td colspan="11"  class="blank">&nbsp;</td>
                            
                        </tr>
                        <tr>
                            <td colspan="11"  class="blank">&nbsp;</td>
                            
                        </tr>
                        <tr>
                            <td colspan="11"  class="blank">&nbsp;</td>
                            
                        </tr>
                        <tr class="pageBreak">
                            <td colspan="4"  class="blank" style="text-align:center;">Hari/Tanggal/Jam</td>
                            <td colspan="2"  class="blank">&nbsp;</td>
                            <td colspan="4"   class="blank" style="text-align:center;">Hari/Tanggal/Jam</td>
                        </tr >
                    ';

            ?>
        
    </table>
    <table id="items" style="margin-top: 10px;" >
         <tr >
            <td colspan="11" class="blank"><h3>DELIVERY MANIFEST</h3></td>
        </tr>
        <tr>
            <td colspan="11" class="blank"><h3>MANIFEST CODE : <?php echo $r_kode['kode_manifest'];  ?></h3></td>
        </tr>
        <tr>
            <td colspan="11" class="blank" style=" padding-left:10px;text-align: left;">
                Kurir :  <?php echo $r['namalengkap'];  ?><br>
                Tanggal : <?php echo $tanggal;  ?>
            </td>
        </tr>
        <tr>
            <th>NO</th>
            <th>ORDER NUMBER</th>
            <th>AWB</th>
            <th>ORIGIN ID</th>
            <th>SHIP TO</th>           
            <th>ELM ORDER</th>
             <th>WEIGHT</th>
            <th>COLLIE</th>
            <th>TYPE</th>
            <th>CHECKLIST</th>
            <th>REMARKS</th>
        </tr>
        

        <?php 
                // FILTER: Menggunakan NOT IN ($bad_scrap_only) sehingga sisa singkatan lainnya otomatis masuk tabel GOOD ini
                $sqll_good = mysqli_query($con, "select perusahaan_pengirim,perusahaan_penerima, a.id_pengiriman,airwaybill_kode,product,aprc,part_n,part_n_desc,rma_no,qty,remark ,berat_aktual,collie
                    from asus_detail_catatan a inner join pengiriman b on a.id_pengiriman = b.id_pengiriman
                    where remark NOT IN ($bad_scrap_only) and courier_by='$courier_by' 
                     AND (
                                                      (DATE(b.courier_date) = '$courier_date' AND '$courier_date' != CURDATE())
                                                      OR 
                                                      (DATE(b.courier_date) >= CURDATE() AND '$courier_date' = CURDATE())
                                                  )
                     and id_kustomer=106
                     group by airwaybill_kode
                      ORDER by airwaybill_kode,product
                    ");
                    

                $no_good = 1;

                  while ($row = mysqli_fetch_assoc($sqll_good)) {
                    echo '<tr>';
                        echo '<td> '.$no_good.' </td>';
                        echo '<td> ';

                         $sqll_product = mysqli_query($con, "select product from asus_detail_catatan where id_pengiriman=".$row['id_pengiriman']." and product is not null ");
                        $r_product = mysqli_fetch_assoc($sqll_product);
                        echo $r_product['product'];
                        

                        echo ' </td>';
                        echo '<td> '.$row['airwaybill_kode'].' </td>';
                        echo '<td> '.$row['perusahaan_pengirim'].' </td>';
                        echo '<td> '.$row['perusahaan_penerima'].' </td>';                                                                                                        
                        echo '<td> ';

                        // FILTER SUB-QUERY: Sesuai kelompok GOOD (Bukan BAD/SCRAP)
                        $sql_aprc = mysqli_query($con, "select aprc,remark from asus_detail_catatan where id_pengiriman=".$row['id_pengiriman']."  and remark NOT IN ($bad_scrap_only) ");
                        $aprc_good = '';
                        $remarks_good = '';
                        $cekAprc_good = 0;
                        while ($rows = mysqli_fetch_assoc($sql_aprc)) {
                               
                                
                                $aprc_good .=  ($cekAprc_good > 0 ? '<hr style="border-top: 1px solid; margin: 0px -2px  0px;">' : '').$rows['aprc'];
                                $remarks_good .=  ($cekAprc_good > 0 ? '<hr style="border-top: 1px solid; margin: 0px -2px  0px;">' : '').$rows['remark'];
                                $cekAprc_good++;
                              
                            }
                            echo $aprc_good;

                        echo '</td>';
                        echo '<td> '; 

                            // FILTER SUB-QUERY COLLIE: Sesuai kelompok GOOD (Bukan BAD/SCRAP)
                             $r_koli_good= mysqli_query($con," select koli_ke,berat, p,l,t,berat_real,catatan 
                             from detail_collie where id_pengiriman=".$row['id_pengiriman']." and catatan NOT IN ($bad_scrap_only) ");
                        $c_good = '';
                        $berat_good = '';
                        
                            $koli_good = 0;
                            while($collie_good=mysqli_fetch_assoc($r_koli_good)){
                                $c_good .= ($koli_good > 0 ? '<hr style="border-top: 1px solid;margin: 0px -2px  0px;">' : '') .'('.$collie_good['p'].'x'.$collie_good['l'].'x'.$collie_good['t'].') = '. number_format($collie_good['berat_real']).'kg'.'<br>';
                               

                                
                                $koli_good++;
                            }
                            echo $c_good;

                        echo ' </td>';
                        
                        echo '<td> ';

                            echo $koli_good;

                        echo ' </td>';
                        echo '<td> '.$remarks_good.' </td>';
                        echo '<td> &nbsp; </td>';
                        echo '<td> &nbsp; </td>';
                    echo '</tr>';

                    


                    $no_good++;



                  }

                   echo '
                        <tr  class="blank">
                            <td  class="blank"  colspan="11">&nbsp;</td>
                            
                        </tr>
                        <tr>
                            <td class="blank" colspan="4" style="text-align:center;">Courier </td>
                            <td colspan="2"  class="blank">&nbsp;</td>
                            <td colspan="4" style="text-align:center;"  class="blank">Penerima </td>
                        </tr>
                        <tr>
                            <td colspan="4" style="text-align:center;"  class="blank">'.ucwords($r['namalengkap']).' </td>
                            <td colspan="2"  class="blank">&nbsp;</td>
                            <td colspan="4" style="text-align:center;"  class="blank">Nama</td>
                            
                        </tr>
                        <tr>
                            <td colspan="11"  class="blank">&nbsp;</td>
                            
                        </tr>
                        <tr>
                            <td colspan="11"  class="blank">&nbsp;</td>
                            
                        </tr>
                        <tr>
                            <td colspan="11"  class="blank">&nbsp;</td>
                            
                        </tr>
                        <tr>
                            <td colspan="4"  class="blank" style="text-align:center;">Hari/Tanggal/Jam</td>
                            <td colspan="2"  class="blank">&nbsp;</td>
                            <td colspan="4"   class="blank" style="text-align:center;">Hari/Tanggal/Jam</td>
                        </tr>
                    ';

            ?>

    </table>
</body>
</html>