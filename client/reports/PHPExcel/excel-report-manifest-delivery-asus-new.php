<?php
include ('../../config/connection.php');
include ('../../config/encrypt.php');
include ('../../config/library.php');
//include ('phpqrcode/qrlib.php'); 

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

header("Content-type: application/vnd-ms-excel");
header('Content-Disposition: attachment; filename="'.($r_kode['kode_manifest'] != '' ? 'report-manifest-delivery-asus_'.$r_kode['kode_manifest'] : 'manifest-deliv-asus-'.$tanggal.'_'.$courier_by).'.xls"');

?>



<!DOCTYPE html>
<html>
<head>
    <meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />
    
    <title>Airwaybill Cesco Logistics</title>
    
    
    
    
    <style type="text/css">
        /*
	 CSS-Tricks Example
	 by Chris Coyier
	 http://css-tricks.com
*/

* { margin: 0; padding: 0; }
body { font: 14px/1.4 Calibri, sans-serif; }
#page-wrap { width: 800px; margin: 0 auto; }

textarea { border: 0; font: 14px Calibri, sans-serif; overflow: hidden; resize: none; }
table { border-collapse: collapse; text-align: center; text-transform: uppercase;}
table td, table th { border: 1px solid black; padding: 2px; }

tr,th,td{vertical-align : middle; text-align: center;}

tr:nth-of-type(odd) {
  background-color: #eee;
}

tr th[scope="row"] {
  background-color: #d6ecd4;
}




#items th { background: #eee; }

#items td.balance { background: #eee; }
#items td.blank { border: 0; background: #fff;}
#items td.blank-bottom { border:-border-bottom 0; }

    </style>

</head>
<body>
    <!-- BAD PART -->
    <table id="items">
         <tr>
            <td colspan="11" class="blank"><h3>DELIVERY MANIFEST </h3></td>

        </tr>
        <tr>
            <td colspan="11" class="blank"><h3>MANIFEST CODE : <?php echo $r_kode['kode_manifest'];  ?></h3></td>
            
        </tr>
       <tr>
            <td colspan="11" class="blank" style=" padding-left:10px;text-align: left;">
                Kurir :  <?php echo $r['namalengkap'];  ?><br style="mso-data-placement:same-cell;" />
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
                $sqll_bad = mysqli_query($con, "select perusahaan_pengirim,perusahaan_penerima, a.id_pengiriman,airwaybill_kode,product,aprc,part_n,part_n_desc,rma_no,qty,remark ,berat_aktual,collie

                    from asus_detail_catatan a inner join pengiriman b on a.id_pengiriman = b.id_pengiriman


                    where   (remark  like '%BAD%' or remark like '%SCRAP%') and 

                      courier_by='$courier_by' 

                        AND (
                                                      (DATE(b.courier_date) = '$courier_date' AND '$courier_date' != CURDATE())
                                                      OR 
                                                      (DATE(b.courier_date) >= CURDATE() AND '$courier_date' = CURDATE())
                                                  )
                 and id_kustomer=106

                     group by airwaybill_kode
                     ORDER by airwaybill_kode,product ");
                 

                $no = 1;
              

                  while ($row = mysqli_fetch_assoc($sqll_bad)) {
                  	if($no%2 == 0){
                  		$bg = 'eee';
                  	}else{
                  		$bg = 'fff';
                  	}
                
                    echo '<tr style="background-color:#'.$bg.';">';
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

                        $sql_aprc = mysqli_query($con, "select aprc,remark from asus_detail_catatan where id_pengiriman=".$row['id_pengiriman']."  and (remark like '%BAD%' or remark like '%SCRAP%') ");
                        $aprc_bad = '';
                        $remarks_bad = '';
                        $cekAprc_bad = 0;
                        while ($rows = mysqli_fetch_assoc($sql_aprc)) {
                               
                                
                                $aprc_bad .=  ($cekAprc_bad > 0 ? '<br style="mso-data-placement:same-cell;" />' : '').$rows['aprc'];
                                $remarks_bad .=  ($cekAprc_bad > 0 ? '<br style="mso-data-placement:same-cell;" />' : '').$rows['remark'];
                                $cekAprc_bad++;
                              
                            }
                            echo $aprc_bad;

                        echo '</td>';
                        echo '<td> '; 

                             $r_koli_bad= mysqli_query($con," select koli_ke,berat, p,l,t,berat_real,catatan 

                                     

                             from detail_collie where id_pengiriman=".$row['id_pengiriman']."  and (catatan  LIKE '%BAD%' or catatan like '%SCRAP%'  ) ");
                        $c_bad = '';
                        $berat_bad = '';
                        
                            $koli_bad = 0;
                            while($collie_bad=mysqli_fetch_assoc($r_koli_bad)){
                                $c_bad .= ($koli_bad > 0 ? '<br style="mso-data-placement:same-cell;" />' : '') .'('.$collie_bad['p'].'x'.$collie_bad['l'].'x'.$collie_bad['t'].') = '. number_format($collie_bad['berat_real']).'kg';
                               

                               
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
                        <tr>
                            <td colspan="4"  class="blank" style="text-align:center;">Hari/Tanggal/Jam</td>
                            <td colspan="2"  class="blank">&nbsp;</td>
                            <td colspan="4"   class="blank" style="text-align:center;">Hari/Tanggal/Jam</td>
                        </tr>
                    ';


            ?>
        
    </table>
    <!-- AKHIR BAD PART -->
    <br><br><br><br><br><br><br><br>

    <!-- Good Part -->
    <table id="items" style=" page-break-after: always;">
         <tr>
            <td colspan="11" class="blank"><h3>DELIVERY MANIFEST</h3></td>

        </tr>
        <tr>
            <td colspan="11" class="blank"><h3>MANIFEST CODE : <?php echo $r_kode['kode_manifest'];  ?></h3></td>
            
        </tr>
        <tr>
            <td colspan="11" class="blank" style=" padding-left:10px;text-align: left;">
                Kurir :  <?php echo $r['namalengkap'];  ?><br style="mso-data-placement:same-cell;" />
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
                $sqll_good = mysqli_query($con, "select perusahaan_pengirim,perusahaan_penerima, a.id_pengiriman,airwaybill_kode,product,aprc,part_n,part_n_desc,rma_no,qty,remark ,berat_aktual,collie

                    from asus_detail_catatan a inner join pengiriman b on a.id_pengiriman = b.id_pengiriman

                    where   remark not like '%BAD%' and remark not like '%SCRAP%' 

                     and courier_by='$courier_by' 

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

                    if($no_good%2 == 0){
                  		$bg = 'eee';
                  	}else{
                  		$bg = 'fff';
                  	}
                
                    echo '<tr style="background-color:#'.$bg.';">';
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

                        $sql_aprc = mysqli_query($con, "select aprc,remark from asus_detail_catatan where id_pengiriman=".$row['id_pengiriman']."  and remark not like '%BAD%' and remark not like '%SCRAP%' ");
                        $aprc_good = '';
                        $remarks_good = '';
                        $cekAprc_good = 0;
                        while ($rows = mysqli_fetch_assoc($sql_aprc)) {
                               
                                
                                $aprc_good .=  ($cekAprc_good > 0 ? '<br style="mso-data-placement:same-cell;" />' : '').$rows['aprc'];
                                $remarks_good .=  ($cekAprc_good > 0 ? '<br style="mso-data-placement:same-cell;" />' : '').$rows['remark'];
                                $cekAprc_good++;
                              
                            }
                            echo $aprc_good;

                        echo '</td>';
                        echo '<td> '; 

                             $r_koli_good= mysqli_query($con," select koli_ke,berat, p,l,t,berat_real,catatan 

                                     

                             from detail_collie where id_pengiriman=".$row['id_pengiriman']." and catatan not LIKE '%BAD%' and catatan not like '%SCRAP%' ");
                        $c_good = '';
                        $berat_good = '';
                        
                            $koli_good = 0;
                            while($collie_good=mysqli_fetch_assoc($r_koli_good)){
                                $c_good .= ($koli_good > 0 ? '<br style="mso-data-placement:same-cell;" />' : '') .'('.$collie_good['p'].'x'.$collie_good['l'].'x'.$collie_good['t'].') = '. number_format($collie_good['berat_real']).'kg';
                               

                               
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