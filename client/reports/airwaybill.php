<?php
include ('../../config/connection.php');
include ('../../config/encrypt.php');
include ('../../config/library.php');
include ('phpqrcode/qrlib.php'); 
	
$key = "asdf$1011";
$encrypt = "$_GET[id]";
$id = $encrypt;

$query = mysqli_query($con, "select 
x_namakotabesar(a.lokasi_asal) as kota_asal,
b.kode_kota as kode_kota_asal,
x_namakotabesar(a.lokasi_tujuan) as kota_tujuan,
c.kode_kota as kode_kota_tujuan,
a.nama_pengirim,
a.perusahaan_pengirim,
a.nama_penerima,
a.perusahaan_penerima,
a.id_kustomer,
coalesce(a.perusahaan_penerima,x_namakustomer(a.id_kustomer)) as perusahaan_penerima,
a.airwaybill_kode,
x_service(a.service) as service,
a.hp_pengirim,
a.hp_penerima,
a.packing_kayu,
a.alamat_pengirim,
x_namakotadetail(a.lokasi_asal) as alamat_pengirim1,
a.alamat_penerima,
x_namakotadetail(a.lokasi_tujuan) as alamat_penerima1,
a.asuransi,
a.surat_jalan,
a.biaya_beban,
a.loc_code,
a.pid,
a.berat_barang,
coalesce(a.berat_aktual,a.berat_barang) as berat_aktual,
a.tanggal,
a.ukuran_barang,
x_user(a.pickup_by) as pickup_by,
a.pickup_date,
a.catatan,
x_user(a.delivery_by) as delivery_by,
CASE WHEN a.delivery_time IS NOT NULL THEN DATE_FORMAT(concat(a.delivery_date,' ',a.delivery_time), '%d-%m-%Y %H:%i') ELSE DATE_FORMAT(a.delivery_date, '%d-%m-%Y') END AS delivery_date,
a.delivery_name,
a.delivery_status,
a.delivery_sign,
a.komoditi,
a.jenis_barang,
a.collie,
case when d.level='kurir' then 'MOTOR' when d.level='driver' then 'MOBIL' end as pickup,
a.20ft,
a.40ft,
a.40ft_hc,
a.lcl,
case when a.user_client IS NOT NULL THEN (select fullname FROM user_client WHERE id_user=a.user_client) ELSE (SELECT namalengkap FROM user WHERE id_user=a.created_by) END AS booking
from pengiriman a 
inner join kota b on b.id_kota=a.lokasi_asal
inner join kota c on c.id_kota=a.lokasi_tujuan
left join user d on d.id_user=a.pickup_by
where a.id_pengiriman='$id'");
$r = mysqli_fetch_assoc($query);

if($r['komoditi']=='1'){
	$komoditi="Paket";
}else{
	$komoditi="Dokumen";
}

if($r['packing_kayu']=='1'){
	$packing="Pemilik";
}elseif($r['packing_kayu']=='2'){
	$packing="Cesco Log.";
}else{
	$packing="Non Packing";
}

	
QRcode::png("".$r['airwaybill_kode']."","image.png","L",3,3);
?>
<!DOCTYPE html>
<html>

<head>
	<meta charset="UTF-8">
	<title>Airwaybill</title>
    <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet"> 
	<link type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/themes/south-street/jquery-ui.css" rel="stylesheet">
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
	<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
	<link type="text/css" href="jquery.signature/jquery.signature.css" rel="stylesheet">
	<script type="text/javascript" src="jquery.signature/jquery.signature.js"></script>
	<script type="text/javascript">
		function loadSignature() {
			$('#redrawSignature').signature({
				disabled: true
			});
			$('#redrawSignature').signature('draw', $('#signatureJSON').val());
		}

	</script>
	<style>
		body {
			background: rgb(204, 204, 204);
			font-family: 'Calibri', sans-serif;
			font-size:10.5px;
			font-weight:bold;
		}
		
		page {
			background: white;
			display: block;
			margin: 0 auto;
			margin-bottom: 0.5cm;
			box-shadow: 0 0 0.5cm rgba(0, 0, 0, 0.5);
		}
		
		page[size="A4"] {
			width: 21cm;
			height: 29.7cm;
		}
		
		page[size="A4"][layout="portrait"] {
			width: 29.7cm;
			height: 21cm;
		}
		
		page[size="A3"] {
			width: 29.7cm;
			height: 42cm;
		}
		
		page[size="A3"][layout="portrait"] {
			width: 42cm;
			height: 29.7cm;
		}
		
		page[size="A5"] {
			width: 14.8cm;
			height: 21cm;
		}
		
		page[size="A5"][layout="portrait"] {
			width: 21cm;
			height: 14.8cm;
		}
		
		.subpage {
			padding: 0.2cm;
			height: 12.8cm;
			outline: 1cm;
		}
		
		@media print {
			body,
			page {
				margin: 0;
				box-shadow: 0;
			}
		}
		.kbw-signature {
			width: 100%;
			height: 160px;
			margin-top: -20px;
		}
		
	</style>
</head>

<body onLoad="loadSignature()">
	<page size="A5" layout="portrait">
		<div class="subpage">
		  <table class="" cellspacing=0 border=0 width="100%">
            <tbody>
              <tr style="height:18.666666666667px;">
                <td style="text-align:center;font-size:8px;color:#000000;font-weight:bold;border-left:2px solid;border-top:2px solid;border-left-color:#000000;border-top-color:#000000;border-bottom-color:#000000;min-width:auto" colspan=3><div style="width: 270px"><img src='../../dist/img/logo.jpg'></div></td>
                <td colspan=3 style="text-align:right;font-size:12px;font-weight:bold;border-top:2px solid;border-top-color:#000000;min-width:auto" valign="top"><div style="width: 270px"> WWW. CESCO-LOGISTICS.COM<br/>
                  CALL CENTRE : 021-78840825<br/>
					CUSTOMERCARE@CESCO-LOGISTICS.COM </div></td>
                <td style="text-align:center;font-size:8px;color:#000000;font-weight:bold;border-left:2px solid #000000;border-top:2px solid;border-right:2px solid #000000;min-width:auto" colspan=2 rowspan=2><?php echo "<img src='image.png'>"; ?></td>
              </tr>
              <tr style="height:20px;">
                <td colspan="3" style="border-left:2px solid #000000;border-top:2px solid #000000;border-top-color:#000000;min-width:auto"> KOTA ASAL * <?php
				if($r['kota_asal']=='Jakarta Selatan' || $r['kota_asal']=='Jakarta Timur' || $r['kota_asal']=='Jakarta Barat' || $r['kota_asal']=='Jakarta Utara' || $r['kota_asal']=='Jakarta Pusat'){
					echo'JAKARTA';
				}elseif(!empty($r['kode_kota_asal'])){
					echo''.strtoupper($r['kota_asal']).' (<b>'.strtoupper($r['kode_kota_asal']).'</b>)';
				}else{
					echo''.strtoupper($r['kota_asal']).'';
				}
				?></td>
                <td colspan="3" style="border-left:2px solid #000000;border-top:2px solid #000000;min-width:auto"> KOTA TUJUAN * <?php
				if($r['kota_tujuan']=='Jakarta Selatan' || $r['kota_tujuan']=='Jakarta Timur' || $r['kota_tujuan']=='Jakarta Barat' || $r['kota_tujuan']=='Jakarta Utara' || $r['kota_tujuan']=='Jakarta Pusat'){
					echo'JAKARTA';
				}elseif(!empty($r['kode_kota_tujuan'])){
					echo''.strtoupper($r['kota_tujuan']).' (<b>'.strtoupper($r['kode_kota_tujuan']).'</b>)';
				}else{
					echo''.strtoupper($r['kota_tujuan']).'';
				}
				?></td>
              </tr>
              <tr style="height:30.666666666667px;">
                <td colspan="3" rowspan="2" style="font-weight:bold;border-left:2px solid;border-top:2px solid;border-left-color:#000000;border-top-color:#000000;border-bottom:2px solid #000000;min-width:auto" valign="top">PENGIRIM *<br/> <?php
			if(!empty($r['nama_pengirim'])){
				echo'
				'.strtoupper(wordwrap($r['perusahaan_pengirim'],45,"<br>\n")).'</br>
				'.strtoupper(wordwrap($r['nama_pengirim'],45,"<br>\n")).'';
			}else{
				echo''.strtoupper($r['perusahaan_pengirim']).'';
			}
			?></td>
                <td colspan="3" rowspan="2" style="border-left:2px solid;border-top:2px solid;border-left-color:#000000;border-top-color:#000000;border-bottom:2px solid #000000;min-width:auto" valign="top"><nobr>PENERIMA *<br/>
			<?php
			if(!empty($r['perusahaan_penerima'])){
				echo'
				'.strtoupper(wordwrap($r['perusahaan_penerima'],45,"<br>\n")).'</br>
				'.strtoupper(wordwrap($r['nama_penerima'],45,"<br>\n")).'';
			}else{
				echo''.strtoupper($r['nama_penerima']).'';
			}
			?></td>
                <td style="text-align:center;font-size:20px;border:2px solid;border-left-color:#000000;border-right-color:#000000;border-top-color:#000000;min-width:auto" colspan=2><?php echo''.$r['airwaybill_kode'].'';?></td>
              </tr>
              <tr style="height:20px;">
                <td style="text-align:right;bold;border-left:2px solid #000000;border-right:2px solid #000000;border-bottom:2px solid #000000;min-width:auto"><div style="width: 85px">LAYANAN *</div></td>
                <td style="border-right:2px solid;border-bottom:2px solid;border-right-color:#000000;border-top-color:#000000;border-bottom-color:#000000;min-width:auto"><div style="width: 85px"><?php echo''.strtoupper(substr($r['service'], 0,14)).''; ?></div></td>
              </tr>
              <tr style="height:20px;">
                <td colspan="3" style="border-left:2px solid #000000;border-bottom:2px solid;min-width:auto">PHONE/HP NO * <?php echo''.$r['hp_pengirim'].'';?></td>
                <td colspan="3" style="border-left:2px solid #000000;border-bottom:2px solid #000000;;min-width:auto">PHONE/HP NO * <?php echo''.$r['hp_penerima'].'';?></td>
                <td style="text-align:right;border-left:2px solid #000000;border-right:2px solid #000000;border-bottom:2px solid #000000;min-width:auto">PACKING *</td>
                <td style="border-right:2px solid;border-bottom:2px solid;border-right-color:#000000;border-bottom-color:#000000;min-width:auto"><?php echo''.strtoupper($packing).'';?></td>
              </tr>
              <tr style="height:20px;">
                <td colspan="3" 
                rowspan='<?php if($r['id_kustomer'] == 13 || $r['id_kustomer'] == 29 || $r['id_kustomer'] == 31){ echo 4;} else{ echo 3;} ?>' 

                	style="border-left:2px solid #000000;min-width:auto" valign="top">ALAMAT * <br/><?php echo''.strtoupper($r['alamat_pengirim']).'';?></td>
                <td colspan="3" 
                rowspan='<?php if($r['id_kustomer'] == 13 || $r['id_kustomer'] == 29 || $r['id_kustomer'] == 31){ echo 4;} else{ echo 3;} ?>' 


                style="border-left:2px solid #000000;min-width:auto" valign="top">ALAMAT * <br><?php echo''.strtoupper($r['alamat_penerima']).'';?></td>
                <td style="text-align:right;border-left:2px solid #000000;border-right:2px solid #000000;border-bottom:2px solid #000000;min-width:auto"><nobr>ASURANSI *</td>
                <td style="border-right:2px solid;border-bottom:2px solid;border-right-color:#000000;border-bottom-color:#000000;min-width:auto"><?php if($r['asuransi']=='Y'){echo'YA';}else{echo'TIDAK';}?></td>
              </tr>
              <tr style="height:20px;">
                <td style="text-align:right;border-left:2px solid #000000;border-right:2px solid #000000;border-bottom:2px solid #000000;min-width:auto">DO KEMBALI *</td>
                <td style="border-right:2px solid;border-bottom:2px solid;border-right-color:#000000;border-bottom-color:#000000;min-width:auto"><?php if($r['surat_jalan']=='Y'){echo'YA';}else{echo'TIDAK';}?></td>
              </tr>
              <tr style="height:20px;">
                <td style="text-align:right;border-left:2px solid #000000;border-right:2px solid #000000;min-width:auto">DI TAGIHKAN KE *</td>
                <td style="border-right:2px solid #000000;min-width:auto"><?php if($r['biaya_beban']=='1'){echo'PENGIRIM';}elseif($r['biaya_beban']=='2'){echo'PENERIMA';}elseif($r['biaya_beban']=='3'){echo'PIHAK KETIGA';}else{echo'TUNAI';}?></td>
              </tr>
              <?php 
              if($r['id_kustomer'] == 13 || $r['id_kustomer'] == 29 || $r['id_kustomer'] == 31){
              ?>
              
              	<tr style="height:20px;">
	                <td style="text-align:right;border-left:2px solid #000000;border-top:2px solid #000000; border-right:2px solid #000000;min-width:auto">NO PID</td>
	                <td style="border-right:2px solid #000000;border-top:2px solid #000000;min-width:auto"><?php echo $r['pid']; ?></td>
	              </tr>

              <?php	

              }

              ?>
              <tr style="height:20px;">
                <td style="text-align:right;border-left:2px solid #000000;border-top:2px solid #000000;min-width:auto"><nobr>STATUS ANTARAN</nobr> </td>
                <td style="border-left:2px solid #000000;border-top:2px solid #000000;min-width:auto" colspan=2></td>
                <td style="text-align:right;border-left:2px solid #000000;border-top:2px solid #000000;min-width:auto">BIAYA PENGIRIMAN</td>
                <td style="border-left:2px solid #000000;border-top:2px solid #000000;min-width:auto" colspan=2></td>
                <td style="text-align:right;border-left:2px solid #000000;border-right:2px solid #000000;border-top:2px solid #000000;min-width:auto"><nobr>NO DO *</td>
                <td style="border-right:2px solid #000000;border-top:2px solid #000000;min-width:auto"><?php echo''.$r['loc_code'].'';?></td>
                
              </tr>
              <tr style="height:20px;">
                <td style="text-align:right;border-left:2px solid #000000;border-top:2px solid #000000;min-width:auto">ISI KIRIMAN *</td>
                <td style="border-left:2px solid #000000;border-top:2px solid #000000;min-width:auto" colspan=2><?php echo''.strtoupper($r['jenis_barang']).'';?></td>
                <td style="text-align:right;border-left:2px solid #000000;border-top:2px solid #000000;min-width:auto"><nobr>BIAYA ASURANSI</td>
                <td style="border-left:2px solid #000000;border-top:2px solid #000000;min-width:auto" colspan=2></td>
                <td style="text-align:right;border-left:2px solid #000000;border-right:2px solid #000000;border-top:2px solid #000000;min-width:auto">DI BOOKING OLEH</td>
                <td style="border-right:2px solid #000000;border-top:2px solid #000000;min-width:auto"><?php echo''.strtoupper($r['booking']).'';?></td>
              </tr>
              <tr style="height:20px;">
                <td style="text-align:center;border-left:2px solid #000000;border-top:2px solid #000000;min-width:auto">UKURAN BARANG *</td>
                <td style="text-align:center;border-left:2px solid #000000;border-top:2px solid #000000;border-top:2px solid #000000;min-width:auto">JUMLAH BARANG *</td>
                <td style="text-align:center;border-left:2px solid #000000;border-top:2px solid #000000;border-top:2px solid #000000;min-width:auto"><nobr>BERAT BARANG *</td>
                <td style="text-align:right;border-left:2px solid #000000;border-top:2px solid #000000;min-width:auto"><nobr>BIAYA PACKING</td>
                <td style="border-left:2px solid #000000;border-top:2px solid #000000;min-width:auto" colspan=2></td>
                <td style="text-align:right;border-left:2px solid #000000;border-top:2px solid #000000;border-right:2px solid #000000;min-width:auto">TANGGAL BOOKING</td>
                <td style="border-right:2px solid #000000;border-top:2px solid #000000;min-width:auto"><?php echo''.tgl_indo($r['tanggal']).'';?></td>
              </tr>
              <tr style="height:20px;">
                <td style="text-align:center;border-left:2px solid #000000;border-top:2px solid #000000;min-width:auto" rowspan=2><?php echo''.$r['ukuran_barang'].'';?></td>
                <td style="text-align:center;border-left:2px solid #000000;border-top:2px solid #000000;min-width:auto" rowspan=2><?php echo''.$r['collie'].'';?></td>
                <td style="text-align:center;border-left:2px solid #000000;border-top:2px solid #000000;min-width:auto" rowspan=2><?php if(!empty($r['berat_aktual'])){echo''.$r['berat_aktual'].' Kg';}?></td>
                <td style="text-align:right;border-left:2px solid #000000;border-top:2px solid #000000;min-width:auto">PPN</td>
                <td style="border-left:2px solid #000000;border-top:2px solid #000000;min-width:auto" colspan=2></td>
                <td style="text-align:right;border-left:2px solid #000000;border-right:2px solid #000000;border-top:2px solid #000000;min-width:auto">DI AMBIL OLEH</td>
                <td style="border-right:2px solid #000000;border-top:2px solid #000000;min-width:auto"><?php echo''.strtoupper($r['pickup_by']).'';?></td>
              </tr>
              <tr style="height:20px;">
                <td style="text-align:right;border-left:2px solid #000000;border-top:2px solid #000000;min-width:auto">TOTAL BIAYA</td>
                <td style="border-left:2px solid #000000;border-top:2px solid #000000;min-width:auto" colspan=2></td>
                <td style="text-align:right;border-left:2px solid #000000;border-right:2px solid #000000;border-top:2px solid #000000;min-width:auto">DIANTAR OLEH</td>
                <td style="border-right:2px solid #000000;border-top:2px solid #000000;min-width:auto"><?php echo''.strtoupper($r['delivery_by']).'';?></td>
              </tr>
              <tr style="height:20px;">
                <td colspan=4 rowspan="4" style="text-align:center;border-left:2px solid;border-top:2px solid;border-left-color:#000000;border-top-color:#000000;min-width:auto" valign="top"> CATATAN PENGIRIMAN / DETAIL BARANG (JIKA ADA)<br>
                    <h1><?php echo''.strtoupper(wordwrap($r['catatan'],45,"<br>\n")).'';?></h1></td>
                <td width="85" style="text-align:center;border-left:2px solid #000000;border-right:2px solid #000000;border-top:2px solid #000000;min-width:auto">NAMA PENGIRIM</td>
                <td width="85" style="text-align:center;border-top:2px solid #000000;min-width:auto">TANGGAL KIRIM</td>
                <td style="text-align:center;border-left:2px solid #000000;border-right:2px solid #000000;border-top:2px solid #000000;min-width:auto">NAMA PENERIMA</td>
                <td style="text-align:center;border-right:2px solid #000000;border-top:2px solid #000000;min-width:auto">TANGGAL TERIMA</td>
              </tr>
              <tr style="height:20px;">
                <td style="text-align:center;border-right:2px solid #000000;border-top:2px solid;border-left:2px solid #000000;border-top-color:#000000;min-width:auto"></td>
                <td style="text-align:center;border-top:2px solid #000000;min-width:auto"><?php echo''.$r['delivery_date'].'';?></td>
                <td style="text-align:center;border-left:2px solid #000000;border-right:2px solid #000000;border-top:2px solid #000000;min-width:auto"><?php echo''.strtoupper($r['delivery_name']).'';?></td>
                <td style="text-align:center;border-right:2px solid #000000;border-top:2px solid #000000;min-width:auto"><?php echo''.$r['delivery_date'].'';?></td>
              </tr>
              <tr style="height:20px;">
                <td style="text-align:center;border-left:2px solid #000000;border-top:2px solid #000000;border-right: 2px solid #000000;min-width:auto" colspan=4><nobr>TANDA TANGAN PENERIMA</nobr> </td>
              </tr>
              <tr style="height:50px;">
                <td style="text-align:center;border-top:2px solid #000000;border-bottom:2px solid #000000;border-left:2px solid #000000;border-right:2px solid #000000;min-width:auto" colspan=4 rowspan=3><input type="hidden" id="signatureJSON" value='<?php echo''.$r['delivery_sign'].'';?>'>
                    <div id="redrawSignature"></div></td>
              </tr>
              <tr style="height:20px;">
                <td style="text-align:center;font-size:8px;border-left:2px solid #000000;border-bottom:2px solid #000000;border-top:1px solid #000000;border-bottom-color:#000000;min-width:auto" colspan=4><nobr>TANDA TERIMA INI ADALAH BUKTI SAH PENGIRIMAN &amp; SEMUA PIHAK SETUJU AKAN KETENTUAN YANG ADA<br/>
                  SETELAH PENERIMA PARAF DI TANDA TERIMA INI SEGALA MACAM KOMPLAIN TIDAK DAPAT DI PENUHI</nobr> </td>
              </tr>
            </tbody>
          </table>
		</div>
</page>
<script type="text/javascript">
	window.print();
	//window.onfocus=function(){ window.close();}
	</script>
</body>

</html>
