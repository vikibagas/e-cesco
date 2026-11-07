<?php
include ('../../config/connection.php');
include ('../../config/encrypt.php');
include ('../../config/library.php');
include ('phpqrcode/qrlib.php'); 
	
$key = "asdf$1011";
$encrypt = "$_GET[id]";
$id = base64_decrypt($encrypt,$key);

$query = mysqli_query($con, "select 
x_namakotabesar(a.lokasi_asal) as kota_asal,
b.kode_kota as kode_kota_asal,
x_namakotabesar(a.lokasi_tujuan) as kota_tujuan,
c.kode_kota as kode_kota_tujuan,
a.nama_pengirim,
coalesce(x_namakustomer(a.id_kustomer),a.perusahaan_pengirim) as perusahaan_pengirim,
a.nama_penerima,
a.perusahaan_penerima,
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
a.berat_barang,
coalesce(a.berat_aktual,a.berat_barang) as berat_aktual,
a.tanggal,
a.ukuran_barang,
x_user(a.pickup_by) as pickup_by,
a.pickup_date,
a.catatan,
x_user(a.delivery_by) as delivery_by,
a.delivery_date,
a.delivery_name,
a.delivery_status,
a.komoditi,
a.jenis_barang,
a.collie,
a.inbound
from pengiriman a 
inner join kota b on b.id_kota=a.lokasi_asal
inner join kota c on c.id_kota=a.lokasi_tujuan
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
<html>
 <head>
  <style>
<!-- @page
	{margin:0,98in 0,75in 0,98in 0,75in;
	mso-header-margin:0,51in;
	mso-footer-margin:0,51in;}
tr
	{mso-height-source:auto;
	mso-ruby-visibility:none;}
col
	{mso-width-source:auto;
	mso-ruby-visibility:none;}
br
	{mso-data-placement:same-cell;}
.font0
	{color:#000000;
	font-size:11.0pt;
	font-weight:400;
	font-style:normal;
	text-decoration:none;
	font-family:"Calibri";
	mso-generic-font-family:swiss;
	mso-font-charset:0;}
.font1
	{color:#000000;
	font-size:8.0pt;
	font-weight:700;
	font-style:normal;
	text-decoration:none;
	font-family:"Calibri";
	mso-generic-font-family:swiss;
	mso-font-charset:0;}
.font2
	{color:windowtext;
	font-size:10.0pt;
	font-weight:700;
	font-style:normal;
	text-decoration:none;
	font-family:"Calibri";
	mso-generic-font-family:swiss;
	mso-font-charset:0;}
.font3
	{color:#000000;
	font-size:18.0pt;
	font-weight:700;
	font-style:normal;
	text-decoration:none;
	font-family:"Calibri";
	mso-generic-font-family:swiss;
	mso-font-charset:0;}
.font4
	{color:#000000;
	font-size:8.0pt;
	font-weight:400;
	font-style:normal;
	text-decoration:none;
	font-family:"Calibri";
	mso-generic-font-family:swiss;
	mso-font-charset:0;}
.font5
	{color:#FF0000;
	font-size:11.0pt;
	font-weight:400;
	font-style:normal;
	text-decoration:none;
	font-family:"Calibri";
	mso-generic-font-family:swiss;
	mso-font-charset:0;}
.font6
	{color:#FFFFFF;
	font-size:11.0pt;
	font-weight:700;
	font-style:normal;
	text-decoration:none;
	font-family:"Calibri";
	mso-generic-font-family:swiss;
	mso-font-charset:0;}
.font7
	{color:#44546A;
	font-size:13.0pt;
	font-weight:700;
	font-style:normal;
	text-decoration:none;
	font-family:"Calibri";
	mso-generic-font-family:swiss;
	mso-font-charset:0;}
.font8
	{color:#9C0006;
	font-size:11.0pt;
	font-weight:400;
	font-style:normal;
	text-decoration:none;
	font-family:"Calibri";
	mso-generic-font-family:swiss;
	mso-font-charset:0;}
.font9
	{color:#44546A;
	font-size:11.0pt;
	font-weight:700;
	font-style:normal;
	text-decoration:none;
	font-family:"Calibri";
	mso-generic-font-family:swiss;
	mso-font-charset:0;}
.font10
	{color:#7F7F7F;
	font-size:11.0pt;
	font-weight:400;
	font-style:italic;
	text-decoration:none;
	font-family:"Calibri";
	mso-generic-font-family:swiss;
	mso-font-charset:0;}
.font11
	{color:#800080;
	font-size:11.0pt;
	font-weight:400;
	font-style:normal;
	text-decoration:underline;
	text-underline-style:single;
	font-family:"Calibri";
	mso-generic-font-family:swiss;
	mso-font-charset:0;}
.font12
	{color:#FFFFFF;
	font-size:11.0pt;
	font-weight:400;
	font-style:normal;
	text-decoration:none;
	font-family:"Calibri";
	mso-generic-font-family:swiss;
	mso-font-charset:0;}
.font13
	{color:#000000;
	font-size:11.0pt;
	font-weight:700;
	font-style:normal;
	text-decoration:none;
	font-family:"Calibri";
	mso-generic-font-family:swiss;
	mso-font-charset:0;}
.font14
	{color:#44546A;
	font-size:18.0pt;
	font-weight:700;
	font-style:normal;
	text-decoration:none;
	font-family:"Calibri";
	mso-generic-font-family:swiss;
	mso-font-charset:0;}
.font15
	{color:#0000FF;
	font-size:11.0pt;
	font-weight:400;
	font-style:normal;
	text-decoration:underline;
	text-underline-style:single;
	font-family:"Calibri";
	mso-generic-font-family:swiss;
	mso-font-charset:0;}
.font16
	{color:#3F3F3F;
	font-size:11.0pt;
	font-weight:700;
	font-style:normal;
	text-decoration:none;
	font-family:"Calibri";
	mso-generic-font-family:swiss;
	mso-font-charset:0;}
.font17
	{color:#44546A;
	font-size:15.0pt;
	font-weight:700;
	font-style:normal;
	text-decoration:none;
	font-family:"Calibri";
	mso-generic-font-family:swiss;
	mso-font-charset:0;}
.font18
	{color:#9C6500;
	font-size:11.0pt;
	font-weight:400;
	font-style:normal;
	text-decoration:none;
	font-family:"Calibri";
	mso-generic-font-family:swiss;
	mso-font-charset:0;}
.font19
	{color:#3F3F76;
	font-size:11.0pt;
	font-weight:400;
	font-style:normal;
	text-decoration:none;
	font-family:"Calibri";
	mso-generic-font-family:swiss;
	mso-font-charset:0;}
.font20
	{color:#FA7D00;
	font-size:11.0pt;
	font-weight:400;
	font-style:normal;
	text-decoration:none;
	font-family:"Calibri";
	mso-generic-font-family:swiss;
	mso-font-charset:0;}
.font21
	{color:#006100;
	font-size:11.0pt;
	font-weight:400;
	font-style:normal;
	text-decoration:none;
	font-family:"Calibri";
	mso-generic-font-family:swiss;
	mso-font-charset:0;}
.font22
	{color:#FA7D00;
	font-size:11.0pt;
	font-weight:700;
	font-style:normal;
	text-decoration:none;
	font-family:"Calibri";
	mso-generic-font-family:swiss;
	mso-font-charset:0;}
.font23
	{color:#000000;
	font-size:8.0pt;
	font-weight:700;
	font-style:normal;
	text-decoration:none;
	font-family:"Calibri";
	mso-generic-font-family:swiss;
	mso-font-charset:0;}
.style0
	{mso-number-format:"General";
	text-align:general;
	vertical-align:middle;
	white-space:nowrap;
	mso-rotate:0;
	color:#000000;
	font-size:11.0pt;
	font-weight:400;
	font-style:normal;
	text-decoration:none;
	font-family:Calibri;
	mso-generic-font-family:swiss;
	mso-font-charset:0;
	border:none;
	mso-protection:locked visible;
	mso-style-name:"Normal";
	mso-style-id:0;}
.style16
	{mso-pattern:auto none;
	background:#BDD7EE;
	color:#000000;
	font-size:11.0pt;
	font-weight:400;
	font-style:normal;
	text-decoration:none;
	font-family:Calibri;
	mso-generic-font-family:swiss;
	mso-font-charset:0;
	mso-style-name:"40% - Accent1";}
.style17
	{mso-number-format:"_-* \#\,\#\#0\.00_-\;\\-* \#\,\#\#0\.00_-\;_-* \0022-\0022??_-\;_-\@_-";
	mso-style-name:"Comma";
	mso-style-id:3;}
.style18
	{mso-number-format:"_-* \#\,\#\#0_-\;\\-* \#\,\#\#0_-\;_-* \0022-\0022_-\;_-\@_-";
	mso-style-name:"Comma [0]";
	mso-style-id:6;}
.style19
	{mso-number-format:"_-\0022£\0022* \#\,\#\#0_-\;\\-\0022£\0022* \#\,\#\#0_-\;_-\0022£\0022* \0022-\0022_-\;_-\@_-";
	mso-style-name:"Currency [0]";
	mso-style-id:7;}
.style20
	{mso-number-format:"_-\0022£\0022* \#\,\#\#0\.00_-\;\\-\0022£\0022* \#\,\#\#0\.00_-\;_-\0022£\0022* \0022-\0022??_-\;_-\@_-";
	mso-style-name:"Currency";
	mso-style-id:4;}
.style21
	{mso-number-format:"0%";
	mso-style-name:"Percent";
	mso-style-id:5;}
.style22
	{mso-pattern:auto none;
	background:#A5A5A5;
	color:#FFFFFF;
	font-size:11.0pt;
	font-weight:700;
	font-style:normal;
	text-decoration:none;
	font-family:Calibri;
	mso-generic-font-family:swiss;
	mso-font-charset:0;
	border:2.0pt double #3F3F3F;
	mso-style-name:"Check Cell";}
.style23
	{color:#44546A;
	font-size:13.0pt;
	font-weight:700;
	font-style:normal;
	text-decoration:none;
	font-family:Calibri;
	mso-generic-font-family:swiss;
	mso-font-charset:0;
	border-left:none;
	border-top:none;
	border-right:none;
	border-bottom:1.0pt solid #5B9BD5;
	mso-style-name:"Heading 2";}
.style24
	{mso-pattern:auto none;
	background:#FFFFCC;
	border:.5pt solid #B2B2B2;
	mso-style-name:"Note";}
.style25
	{color:#0000FF;
	font-size:11.0pt;
	font-weight:400;
	font-style:normal;
	text-decoration:underline;
	text-underline-style:single;
	font-family:Calibri;
	mso-generic-font-family:swiss;
	mso-font-charset:0;
	mso-style-name:"Hyperlink";
	mso-style-id:8;}
.style26
	{mso-pattern:auto none;
	background:#FFD966;
	color:#FFFFFF;
	font-size:11.0pt;
	font-weight:400;
	font-style:normal;
	text-decoration:none;
	font-family:Calibri;
	mso-generic-font-family:swiss;
	mso-font-charset:0;
	mso-style-name:"60% - Accent4";}
.style27
	{color:#800080;
	font-size:11.0pt;
	font-weight:400;
	font-style:normal;
	text-decoration:underline;
	text-underline-style:single;
	font-family:Calibri;
	mso-generic-font-family:swiss;
	mso-font-charset:0;
	mso-style-name:"Followed Hyperlink";
	mso-style-id:9;}
.style28
	{mso-pattern:auto none;
	background:#DBDBDB;
	color:#000000;
	font-size:11.0pt;
	font-weight:400;
	font-style:normal;
	text-decoration:none;
	font-family:Calibri;
	mso-generic-font-family:swiss;
	mso-font-charset:0;
	mso-style-name:"40% - Accent3";}
.style29
	{color:#FF0000;
	font-size:11.0pt;
	font-weight:400;
	font-style:normal;
	text-decoration:none;
	font-family:Calibri;
	mso-generic-font-family:swiss;
	mso-font-charset:0;
	mso-style-name:"Warning Text";}
.style30
	{mso-pattern:auto none;
	background:#F8CBAD;
	color:#000000;
	font-size:11.0pt;
	font-weight:400;
	font-style:normal;
	text-decoration:none;
	font-family:Calibri;
	mso-generic-font-family:swiss;
	mso-font-charset:0;
	mso-style-name:"40% - Accent2";}
.style31
	{color:#44546A;
	font-size:18.0pt;
	font-weight:700;
	font-style:normal;
	text-decoration:none;
	font-family:Calibri;
	mso-generic-font-family:swiss;
	mso-font-charset:0;
	mso-style-name:"Title";}
.style32
	{color:#7F7F7F;
	font-size:11.0pt;
	font-weight:400;
	font-style:italic;
	text-decoration:none;
	font-family:Calibri;
	mso-generic-font-family:swiss;
	mso-font-charset:0;
	mso-style-name:"CExplanatory Text";}
.style33
	{color:#44546A;
	font-size:15.0pt;
	font-weight:700;
	font-style:normal;
	text-decoration:none;
	font-family:Calibri;
	mso-generic-font-family:swiss;
	mso-font-charset:0;
	border-left:none;
	border-top:none;
	border-right:none;
	border-bottom:1.0pt solid #5B9BD5;
	mso-style-name:"Heading 1";}
.style34
	{color:#44546A;
	font-size:11.0pt;
	font-weight:700;
	font-style:normal;
	text-decoration:none;
	font-family:Calibri;
	mso-generic-font-family:swiss;
	mso-font-charset:0;
	border-left:none;
	border-top:none;
	border-right:none;
	border-bottom:1.0pt solid #ACCCEA;
	mso-style-name:"Heading 3";}
.style35
	{color:#44546A;
	font-size:11.0pt;
	font-weight:700;
	font-style:normal;
	text-decoration:none;
	font-family:Calibri;
	mso-generic-font-family:swiss;
	mso-font-charset:0;
	mso-style-name:"Heading 4";}
.style36
	{mso-pattern:auto none;
	background:#FFCC99;
	color:#3F3F76;
	font-size:11.0pt;
	font-weight:400;
	font-style:normal;
	text-decoration:none;
	font-family:Calibri;
	mso-generic-font-family:swiss;
	mso-font-charset:0;
	border:.5pt solid #7F7F7F;
	mso-style-name:"Input";}
.style37
	{mso-pattern:auto none;
	background:#C9C9C9;
	color:#FFFFFF;
	font-size:11.0pt;
	font-weight:400;
	font-style:normal;
	text-decoration:none;
	font-family:Calibri;
	mso-generic-font-family:swiss;
	mso-font-charset:0;
	mso-style-name:"60% - Accent3";}
.style38
	{mso-pattern:auto none;
	background:#C6EFCE;
	color:#006100;
	font-size:11.0pt;
	font-weight:400;
	font-style:normal;
	text-decoration:none;
	font-family:Calibri;
	mso-generic-font-family:swiss;
	mso-font-charset:0;
	mso-style-name:"Good";}
.style39
	{mso-pattern:auto none;
	background:#F2F2F2;
	color:#3F3F3F;
	font-size:11.0pt;
	font-weight:700;
	font-style:normal;
	text-decoration:none;
	font-family:Calibri;
	mso-generic-font-family:swiss;
	mso-font-charset:0;
	border:.5pt solid #3F3F3F;
	mso-style-name:"Output";}
.style40
	{mso-pattern:auto none;
	background:#DDEBF7;
	color:#000000;
	font-size:11.0pt;
	font-weight:400;
	font-style:normal;
	text-decoration:none;
	font-family:Calibri;
	mso-generic-font-family:swiss;
	mso-font-charset:0;
	mso-style-name:"20% - Accent1";}
.style41
	{mso-pattern:auto none;
	background:#F2F2F2;
	color:#FA7D00;
	font-size:11.0pt;
	font-weight:700;
	font-style:normal;
	text-decoration:none;
	font-family:Calibri;
	mso-generic-font-family:swiss;
	mso-font-charset:0;
	border:.5pt solid #7F7F7F;
	mso-style-name:"Calculation";}
.style42
	{color:#FA7D00;
	font-size:11.0pt;
	font-weight:400;
	font-style:normal;
	text-decoration:none;
	font-family:Calibri;
	mso-generic-font-family:swiss;
	mso-font-charset:0;
	border-left:none;
	border-top:none;
	border-right:none;
	border-bottom:2.0pt double #FF8001;
	mso-style-name:"Linked Cell";}
.style43
	{color:#000000;
	font-size:11.0pt;
	font-weight:700;
	font-style:normal;
	text-decoration:none;
	font-family:Calibri;
	mso-generic-font-family:swiss;
	mso-font-charset:0;
	border-left:none;
	border-top:.5pt solid #5B9BD5;
	border-right:none;
	border-bottom:2.0pt double #5B9BD5;
	mso-style-name:"Total";}
.style44
	{mso-pattern:auto none;
	background:#FFC7CE;
	color:#9C0006;
	font-size:11.0pt;
	font-weight:400;
	font-style:normal;
	text-decoration:none;
	font-family:Calibri;
	mso-generic-font-family:swiss;
	mso-font-charset:0;
	mso-style-name:"Bad";}
.style45
	{mso-pattern:auto none;
	background:#FFEB9C;
	color:#9C6500;
	font-size:11.0pt;
	font-weight:400;
	font-style:normal;
	text-decoration:none;
	font-family:Calibri;
	mso-generic-font-family:swiss;
	mso-font-charset:0;
	mso-style-name:"Neutral";}
.style46
	{mso-pattern:auto none;
	background:#5B9BD5;
	color:#FFFFFF;
	font-size:11.0pt;
	font-weight:400;
	font-style:normal;
	text-decoration:none;
	font-family:Calibri;
	mso-generic-font-family:swiss;
	mso-font-charset:0;
	mso-style-name:"Accent1";}
.style47
	{mso-pattern:auto none;
	background:#D9E1F2;
	color:#000000;
	font-size:11.0pt;
	font-weight:400;
	font-style:normal;
	text-decoration:none;
	font-family:Calibri;
	mso-generic-font-family:swiss;
	mso-font-charset:0;
	mso-style-name:"20% - Accent5";}
.style48
	{mso-pattern:auto none;
	background:#9BC2E6;
	color:#FFFFFF;
	font-size:11.0pt;
	font-weight:400;
	font-style:normal;
	text-decoration:none;
	font-family:Calibri;
	mso-generic-font-family:swiss;
	mso-font-charset:0;
	mso-style-name:"60% - Accent1";}
.style49
	{mso-pattern:auto none;
	background:#ED7D31;
	color:#FFFFFF;
	font-size:11.0pt;
	font-weight:400;
	font-style:normal;
	text-decoration:none;
	font-family:Calibri;
	mso-generic-font-family:swiss;
	mso-font-charset:0;
	mso-style-name:"Accent2";}
.style50
	{mso-pattern:auto none;
	background:#FCE4D6;
	color:#000000;
	font-size:11.0pt;
	font-weight:400;
	font-style:normal;
	text-decoration:none;
	font-family:Calibri;
	mso-generic-font-family:swiss;
	mso-font-charset:0;
	mso-style-name:"20% - Accent2";}
.style51
	{mso-pattern:auto none;
	background:#E2EFDA;
	color:#000000;
	font-size:11.0pt;
	font-weight:400;
	font-style:normal;
	text-decoration:none;
	font-family:Calibri;
	mso-generic-font-family:swiss;
	mso-font-charset:0;
	mso-style-name:"20% - Accent6";}
.style52
	{mso-pattern:auto none;
	background:#F4B084;
	color:#FFFFFF;
	font-size:11.0pt;
	font-weight:400;
	font-style:normal;
	text-decoration:none;
	font-family:Calibri;
	mso-generic-font-family:swiss;
	mso-font-charset:0;
	mso-style-name:"60% - Accent2";}
.style53
	{mso-pattern:auto none;
	background:#A5A5A5;
	color:#FFFFFF;
	font-size:11.0pt;
	font-weight:400;
	font-style:normal;
	text-decoration:none;
	font-family:Calibri;
	mso-generic-font-family:swiss;
	mso-font-charset:0;
	mso-style-name:"Accent3";}
.style54
	{mso-pattern:auto none;
	background:#EDEDED;
	color:#000000;
	font-size:11.0pt;
	font-weight:400;
	font-style:normal;
	text-decoration:none;
	font-family:Calibri;
	mso-generic-font-family:swiss;
	mso-font-charset:0;
	mso-style-name:"20% - Accent3";}
.style55
	{mso-pattern:auto none;
	background:#FFC000;
	color:#FFFFFF;
	font-size:11.0pt;
	font-weight:400;
	font-style:normal;
	text-decoration:none;
	font-family:Calibri;
	mso-generic-font-family:swiss;
	mso-font-charset:0;
	mso-style-name:"Accent4";}
.style56
	{mso-pattern:auto none;
	background:#FFF2CC;
	color:#000000;
	font-size:11.0pt;
	font-weight:400;
	font-style:normal;
	text-decoration:none;
	font-family:Calibri;
	mso-generic-font-family:swiss;
	mso-font-charset:0;
	mso-style-name:"20% - Accent4";}
.style57
	{mso-pattern:auto none;
	background:#FFE699;
	color:#000000;
	font-size:11.0pt;
	font-weight:400;
	font-style:normal;
	text-decoration:none;
	font-family:Calibri;
	mso-generic-font-family:swiss;
	mso-font-charset:0;
	mso-style-name:"40% - Accent4";}
.style58
	{mso-pattern:auto none;
	background:#4472C4;
	color:#FFFFFF;
	font-size:11.0pt;
	font-weight:400;
	font-style:normal;
	text-decoration:none;
	font-family:Calibri;
	mso-generic-font-family:swiss;
	mso-font-charset:0;
	mso-style-name:"Accent5";}
.style59
	{mso-pattern:auto none;
	background:#B4C6E7;
	color:#000000;
	font-size:11.0pt;
	font-weight:400;
	font-style:normal;
	text-decoration:none;
	font-family:Calibri;
	mso-generic-font-family:swiss;
	mso-font-charset:0;
	mso-style-name:"40% - Accent5";}
.style60
	{mso-pattern:auto none;
	background:#8EA9DB;
	color:#FFFFFF;
	font-size:11.0pt;
	font-weight:400;
	font-style:normal;
	text-decoration:none;
	font-family:Calibri;
	mso-generic-font-family:swiss;
	mso-font-charset:0;
	mso-style-name:"60% - Accent5";}
.style61
	{mso-pattern:auto none;
	background:#70AD47;
	color:#FFFFFF;
	font-size:11.0pt;
	font-weight:400;
	font-style:normal;
	text-decoration:none;
	font-family:Calibri;
	mso-generic-font-family:swiss;
	mso-font-charset:0;
	mso-style-name:"Accent6";}
.style62
	{mso-pattern:auto none;
	background:#C6E0B4;
	color:#000000;
	font-size:11.0pt;
	font-weight:400;
	font-style:normal;
	text-decoration:none;
	font-family:Calibri;
	mso-generic-font-family:swiss;
	mso-font-charset:0;
	mso-style-name:"40% - Accent6";}
.style63
	{mso-pattern:auto none;
	background:#A9D08E;
	color:#FFFFFF;
	font-size:11.0pt;
	font-weight:400;
	font-style:normal;
	text-decoration:none;
	font-family:Calibri;
	mso-generic-font-family:swiss;
	mso-font-charset:0;
	mso-style-name:"60% - Accent6";}
td
	{mso-style-parent:style0;
	padding-top:1px;
	padding-right:1px;
	padding-left:1px;
	mso-ignore:padding;
	mso-number-format:"General";
	text-align:general;
	vertical-align:middle;
	white-space:nowrap;
	mso-rotate:0;
	color:#000000;
	font-size:11.0pt;
	font-weight:400;
	font-style:normal;
	text-decoration:none;
	font-family:Calibri;
	mso-generic-font-family:swiss;
	mso-font-charset:0;
	border:none;
	mso-protection:locked visible;}
.xl65
	{mso-style-parent:style0;
	text-align:center;
	font-size:8.0pt;
	font-weight:700;
	mso-font-charset:0;
	border-left:1.0pt solid windowtext;
	border-top:1.0pt solid windowtext;
	border-bottom:1.0pt solid windowtext;}
.xl66
	{mso-style-parent:style0;
	text-align:right;
	color:windowtext;
	font-size:10.0pt;
	font-weight:700;
	mso-font-charset:0;
	border-top:1.0pt solid windowtext;}
.xl67
	{mso-style-parent:style0;
	text-align:center;
	font-size:8.0pt;
	font-weight:700;
	mso-font-charset:0;
	border:1.0pt solid windowtext;}
.xl68
	{mso-style-parent:style0;
	text-align:right;
	color:windowtext;
	font-size:10.0pt;
	font-weight:700;
	mso-font-charset:0;}
.xl69
	{mso-style-parent:style25;
	text-align:right;
	color:windowtext;
	font-size:10.0pt;
	font-weight:700;
	mso-font-charset:0;
	border-bottom:1.0pt solid windowtext;}
.xl70
	{mso-style-parent:style0;
	text-align:left;
	font-size:8.0pt;
	font-weight:700;
	mso-font-charset:0;
	border-left:1.0pt solid windowtext;
	border-bottom:1.0pt solid windowtext;}
.xl71
	{mso-style-parent:style0;
	text-align:left;
	font-size:8.0pt;
	font-weight:700;
	mso-font-charset:0;
	border-left:1.0pt solid windowtext;}
.xl72
	{mso-style-parent:style0;
	text-align:left;
	vertical-align:top;
	font-size:8.0pt;
	font-weight:700;
	mso-font-charset:0;
	border-left:1.0pt solid windowtext;}
.xl73
	{mso-style-parent:style0;
	text-align:left;
	vertical-align:top;
	font-size:8.0pt;
	font-weight:700;
	mso-font-charset:0;
	border-left:1.0pt solid windowtext;
	border-top:1.0pt solid windowtext;
	border-right:1.0pt solid windowtext;}
.xl74
	{mso-style-parent:style0;
	text-align:center;
	vertical-align:bottom;
	font-size:18.0pt;
	font-weight:700;
	mso-font-charset:0;
	border-right:1.0pt solid windowtext;
	border-bottom:1.0pt solid windowtext;}
.xl75
	{mso-style-parent:style0;
	text-align:right;
	font-size:8.0pt;
	font-weight:700;
	mso-font-charset:0;
	border-bottom:1.0pt solid windowtext;}
.xl76
	{mso-style-parent:style0;
	text-align:center;
	font-size:8.0pt;
	font-weight:700;
	mso-font-charset:0;
	border-right:1.0pt solid windowtext;
	border-bottom:1.0pt solid windowtext;}
.xl77
	{mso-style-parent:style0;
	text-align:left;
	font-size:8.0pt;
	font-weight:700;
	mso-font-charset:0;
	border-left:1.0pt solid windowtext;
	border-right:1.0pt solid windowtext;}
.xl78
	{mso-style-parent:style0;
	text-align:right;
	font-size:8.0pt;
	font-weight:700;
	mso-font-charset:0;
	border-top:1.0pt solid windowtext;
	border-bottom:1.0pt solid windowtext;}
.xl79
	{mso-style-parent:style0;
	text-align:center;
	font-size:8.0pt;
	font-weight:700;
	mso-font-charset:0;
	border-top:1.0pt solid windowtext;
	border-right:1.0pt solid windowtext;
	border-bottom:1.0pt solid windowtext;}
.xl80
	{mso-style-parent:style0;
	text-align:left;
	vertical-align:top;
	font-size:8.0pt;
	font-weight:700;
	mso-font-charset:0;
	border-left:1.0pt solid windowtext;
	border-right:1.0pt solid windowtext;
	border-bottom:1.0pt solid windowtext;}
.xl81
	{mso-style-parent:style0;
	text-align:center;
	vertical-align:top;
	font-size:8.0pt;
	font-weight:700;
	mso-font-charset:0;
	border-left:1.0pt solid windowtext;
	border-top:1.0pt solid windowtext;
	border-bottom:1.0pt solid windowtext;}
.xl82
	{mso-style-parent:style0;
	text-align:left;
	vertical-align:top;
	font-size:8.0pt;
	font-weight:700;
	mso-font-charset:0;
	border:1.0pt solid windowtext;}
.xl83
	{mso-style-parent:style0;
	text-align:right;
	font-size:8.0pt;
	font-weight:700;
	mso-font-charset:0;
	border-top:1.0pt solid windowtext;}
.xl84
	{mso-style-parent:style0;
	text-align:center;
	font-size:8.0pt;
	font-weight:700;
	mso-font-charset:0;
	border-top:1.0pt solid windowtext;
	border-right:1.0pt solid windowtext;}
.xl85
	{mso-style-parent:style0;
	text-align:center;
	font-size:8.0pt;
	mso-font-charset:0;
	border-left:1.0pt solid windowtext;}
.xl86
	{mso-style-parent:style0;
	text-align:center;
	font-size:8.0pt;
	mso-font-charset:0;
	border-left:1.0pt solid windowtext;
	border-bottom:1.0pt solid windowtext;}
.xl87
	{mso-style-parent:style0;
	text-align:center;
	font-size:8.0pt;
	mso-font-charset:0;
	border-left:1.0pt solid windowtext;}
.xl88
	{mso-style-parent:style0;
	text-align:right;
	font-size:8.0pt;
	font-weight:700;
	mso-font-charset:0;
	border-left:1.0pt solid windowtext;
	border-top:1.0pt solid windowtext;
	border-bottom:1.0pt solid windowtext;}
.xl89
	{mso-style-parent:style0;
	text-align:center;
	font-size:8.0pt;
	font-weight:700;
	mso-font-charset:0;
	border-top:1.0pt solid windowtext;
	border-right:1.0pt solid windowtext;
	border-bottom:1.0pt solid windowtext;}
.xl90
	{mso-style-parent:style0;
	text-align:center;
	font-size:8.0pt;
	font-weight:700;
	mso-font-charset:0;
	border-left:1.0pt solid windowtext;
	border-top:1.0pt solid windowtext;}
.xl91
	{mso-style-parent:style0;
	text-align:center;
	font-size:8.0pt;
	font-weight:700;
	mso-font-charset:0;
	border-top:1.0pt solid windowtext;
	border-right:1.0pt solid windowtext;}
.xl92
	{mso-style-parent:style0;
	text-align:center;
	font-size:8.0pt;
	font-weight:700;
	mso-font-charset:0;
	border-left:1.0pt solid windowtext;
	border-top:1.0pt solid windowtext;
	border-right:1.0pt solid windowtext;}
.xl93
	{mso-style-parent:style0;
	text-align:center;
	font-size:8.0pt;
	font-weight:700;
	mso-font-charset:0;
	border-left:1.0pt solid windowtext;
	border-top:1.0pt solid windowtext;
	border-bottom:1.0pt solid windowtext;}
.xl94
	{mso-style-parent:style0;
	text-align:center;
	font-size:8.0pt;
	font-weight:700;
	mso-font-charset:0;
	border-top:1.0pt solid windowtext;
	border-right:1.0pt solid windowtext;
	border-bottom:1.0pt solid windowtext;}
.xl95
	{mso-style-parent:style0;
	text-align:right;
	font-size:8.0pt;
	font-weight:700;
	mso-font-charset:0;}
.xl96
	{mso-style-parent:style0;
	text-align:center;
	font-size:8.0pt;
	font-weight:700;
	mso-font-charset:0;
	border-right:1.0pt solid windowtext;}
.xl97
	{mso-style-parent:style0;
	text-align:center;
	font-size:8.0pt;
	font-weight:700;
	mso-font-charset:0;
	border-left:1.0pt solid windowtext;
	border-bottom:1.0pt solid windowtext;}
.xl98
	{mso-style-parent:style0;
	text-align:center;
	font-size:8.0pt;
	font-weight:700;
	mso-font-charset:0;
	border-right:1.0pt solid windowtext;
	border-bottom:1.0pt solid windowtext;}
.xl99
	{mso-style-parent:style0;
	text-align:center;
	font-size:8.0pt;
	font-weight:700;
	mso-font-charset:0;
	border-left:1.0pt solid windowtext;}
.xl100
	{mso-style-parent:style0;
	text-align:center;
	font-size:8.0pt;
	font-weight:700;
	mso-font-charset:0;}
.xl101
	{mso-style-parent:style0;
	text-align:center;
	font-size:8.0pt;
	font-weight:700;
	mso-font-charset:0;
	border-top:1.0pt solid windowtext;}
.xl102
	{mso-style-parent:style0;
	text-align:center;
	font-size:8.0pt;
	font-weight:700;
	mso-font-charset:0;
	border-top:1.0pt solid windowtext;
	border-right:1.0pt solid windowtext;}
.xl103
	{mso-style-parent:style0;
	text-align:center;
	vertical-align:top;
	font-size:8.0pt;
	font-weight:700;
	mso-font-charset:0;
	border-left:1.0pt solid windowtext;}
.xl104
	{mso-style-parent:style0;
	text-align:center;
	vertical-align:top;
	font-size:8.0pt;
	font-weight:700;
	mso-font-charset:0;}
.xl105
	{mso-style-parent:style0;
	text-align:center;
	vertical-align:top;
	font-size:8.0pt;
	font-weight:700;
	mso-font-charset:0;
	border-right:1.0pt solid windowtext;}
.xl106
	{mso-style-parent:style0;
	text-align:center;
	vertical-align:bottom;
	font-size:8.0pt;
	font-weight:700;
	mso-font-charset:0;
	border-top:1.0pt solid windowtext;
	border-right:1.0pt solid windowtext;}
.xl107
	{mso-style-parent:style0;
	text-align:center;
	vertical-align:bottom;
	font-size:8.0pt;
	font-weight:700;
	mso-font-charset:0;
	border-right:1.0pt solid windowtext;}
.xl108
	{mso-style-parent:style0;
	text-align:center;
	font-size:8.0pt;
	mso-font-charset:0;
	border-right:1.0pt solid windowtext;
	border-bottom:1.0pt solid windowtext;}
.xl109
	{mso-style-parent:style0;
	text-align:center;
	font-size:8.0pt;
	font-weight:700;
	mso-font-charset:0;
	border-left:1.0pt solid windowtext;
	border-right:1.0pt solid windowtext;}
.xl110
	{mso-style-parent:style0;
	text-align:center;
	vertical-align:top;
	font-size:8.0pt;
	font-weight:700;
	mso-font-charset:0;
	border-top:1.0pt solid windowtext;}
.xl111
	{mso-style-parent:style0;
	text-align:center;
	vertical-align:top;
	font-size:8.0pt;
	font-weight:700;
	mso-font-charset:0;
	border-top:1.0pt solid windowtext;
	border-right:1.0pt solid windowtext;}
.xl112
	{mso-style-parent:style0;
	text-align:center;
	vertical-align:top;
	font-size:8.0pt;
	font-weight:700;
	mso-font-charset:0;}
.xl113
	{mso-style-parent:style0;
	text-align:center;
	vertical-align:top;
	font-size:8.0pt;
	font-weight:700;
	mso-font-charset:0;
	border-right:1.0pt solid windowtext;}
.xl114
	{mso-style-parent:style0;
	text-align:center;
	vertical-align:top;
	font-size:8.0pt;
	font-weight:700;
	mso-font-charset:0;
	border-left:1.0pt solid windowtext;
	border-bottom:1.0pt solid windowtext;}
.xl115
	{mso-style-parent:style0;
	text-align:center;
	vertical-align:top;
	font-size:8.0pt;
	font-weight:700;
	mso-font-charset:0;
	border-bottom:1.0pt solid windowtext;}
.xl116
	{mso-style-parent:style0;
	text-align:center;
	vertical-align:top;
	font-size:8.0pt;
	font-weight:700;
	mso-font-charset:0;
	border-right:1.0pt solid windowtext;
	border-bottom:1.0pt solid windowtext;}
.xl117
	{mso-style-parent:style0;
	text-align:center;
	vertical-align:top;
	font-size:8.0pt;
	font-weight:700;
	mso-font-charset:0;
	border-bottom:1.0pt solid windowtext;}
.xl118
	{mso-style-parent:style0;
	text-align:center;
	vertical-align:top;
	font-size:8.0pt;
	font-weight:700;
	mso-font-charset:0;
	border-right:1.0pt solid windowtext;
	border-bottom:1.0pt solid windowtext;}
body {
  background: rgb(204,204,204); 
}
page[size="A4"] {
  background: white;
  width: 210mm;
  height: 148.5mm;
  display: block;
  margin: 0 auto;
  margin-bottom: 0.5cm;
  padding-top: 0.5cm;
  box-shadow: 0 0 0.5cm rgba(0,0,0,0.5);
}
@media print {
  body, page[size="A4"] {
    margin: 0;
    box-shadow: 0;
  }
}
 -->  </style>
  <!--[if gte mso 9]>
   <xml>
    <x:ExcelWorkbook>
     <x:ExcelWorksheets>
      <x:ExcelWorksheet>
       <x:Name>Sheet1</x:Name>
       <x:WorksheetOptions>
        <x:DefaultRowHeight>300</x:DefaultRowHeight>
        <x:StandardWidth>2345</x:StandardWidth>
        <x:Selected/>
        <x:Panes>
         <x:Pane>
          <x:Number>3</x:Number>
          <x:ActiveCol>3</x:ActiveCol>
          <x:ActiveRow>6</x:ActiveRow>
          <x:RangeSelection>D7:F7</x:RangeSelection>
         </x:Pane>
        </x:Panes>
        <x:DoNotDisplayGridlines/>
        <x:ProtectContents>False</x:ProtectContents>
        <x:ProtectObjects>False</x:ProtectObjects>
        <x:ProtectScenarios>False</x:ProtectScenarios>
        <x:PageBreakZoom>100</x:PageBreakZoom>
        <x:Print>
         <x:ValidPrinterInfo/>
         <x:PaperSizeIndex>9</x:PaperSizeIndex>
         <x:HorizontalResolution>600</x:HorizontalResolution>
        </x:Print>
       </x:WorksheetOptions>
      </x:ExcelWorksheet>
     </x:ExcelWorksheets>
     <x:ProtectStructure>False</x:ProtectStructure>
     <x:ProtectWindows>False</x:ProtectWindows>
     <x:WindowHeight>8520</x:WindowHeight>
     <x:WindowWidth>20385</x:WindowWidth>
    </x:ExcelWorkbook>
    <x:SupBook>
     <x:Path>F:\cesco\</x:Path>
    </x:SupBook>
   </xml>
  <![endif]-->
 </head>
 <body>
 <page size="A4">
  <table width="682,47" border="0" cellpadding="0" cellspacing="0" style='width:511.85pt;border-collapse:collapse;table-layout:fixed;margin: 0 auto;'>
   <col width="81,67" span="6" style='mso-width-source:userset;mso-width-alt:2986;'/>
   <col width="96,80" style='mso-width-source:userset;mso-width-alt:3539;'/>
   <col width="95,67" style='mso-width-source:userset;mso-width-alt:3498;'/>
   <tr height="21" style='height:15.75pt;'>
    <td class="xl65" height="63" width="245" colspan="3" rowspan="3" style='height:47.25pt;width:183.75pt;border-right:none;border-bottom:1.0pt solid windowtext;' x:str><font class="font23"><span style='mso-spacerun:yes;'><img src='../../dist/img/logo.jpg'></span></font></td>
    <td class="xl66" width="245" colspan="3" style='width:183.75pt;border-right:none;border-bottom:none;' x:str>WWW. CESCO-LOGISTICS.COM</td>
    <td class="xl67" width="192,47" colspan="2" rowspan="4" style='width:144.35pt;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;' x:str><?php echo "<img src='image.png'>"; ?></td>
   </tr>
   <tr height="21" style='height:15.75pt;'>
    <td class="xl68" colspan="3" style='border-right:none;border-bottom:none;' x:str>CALL CENTRE : 021-78840825</td>
   </tr>
   <tr height="21" style='height:15.75pt;'>
    <td class="xl69" colspan="3" style='border-right:none;border-bottom:1.0pt solid windowtext;' x:str><a href="mailto:CUSTOMERCARE@CESCO-LOGISTICS.COM" target="_parent" title="mailto:CUSTOMERCARE@CESCO-LOGISTICS.COM">CUSTOMERCARE@CESCO-LOGISTICS.COM</a></td>
   </tr>
   <tr height="21" style='height:15.75pt;'>
    <td class="xl70" height="21" colspan="3" style='height:15.75pt;border-right:none;border-bottom:1.0pt solid windowtext;' x:str>KOTA ASAL* :<font class="font23"><span style='mso-spacerun:yes;'> <?php
				if($r['kota_asal']=='Jakarta Selatan' || $r['kota_asal']=='Jakarta Timur' || $r['kota_asal']=='Jakarta Barat' || $r['kota_asal']=='Jakarta Utara' || $r['kota_asal']=='Jakarta Pusat'){
					echo'JAKARTA';
				}elseif(!empty($r['kode_kota_asal'])){
					echo''.strtoupper($r['kota_asal']).' (<b>'.strtoupper($r['kode_kota_asal']).'</b>)';
				}else{
					echo''.strtoupper($r['kota_asal']).'';
				}
				?></span></font></td>
    <td class="xl71" colspan="3" style='border-right:none;border-bottom:none;' x:str>KOTA TUJUAN* :<font class="font23"><span style='mso-spacerun:yes;'> <?php
				if($r['kota_tujuan']=='Jakarta Selatan' || $r['kota_tujuan']=='Jakarta Timur' || $r['kota_tujuan']=='Jakarta Barat' || $r['kota_tujuan']=='Jakarta Utara' || $r['kota_tujuan']=='Jakarta Pusat'){
					echo'JAKARTA';
				}elseif(!empty($r['kode_kota_tujuan'])){
					echo''.strtoupper($r['kota_tujuan']).' (<b>'.strtoupper($r['kode_kota_tujuan']).'</b>)';
				}else{
					echo''.strtoupper($r['kota_tujuan']).'';
				}
				?></span></font></td>
   </tr>
   <tr height="32" style='height:24.00pt;'>
    <td class="xl72" height="52" colspan="3" rowspan="2" style='height:39.00pt;border-right:none;border-bottom:none;' x:str>PENGIRIM* :<font class="font23"><span style='mso-spacerun:yes;'><br/>
			<?php
			if(!empty($r['nama_pengirim'])){
				echo'
				'.strtoupper(wordwrap($r['perusahaan_pengirim'],45,"<br>\n")).'</br>
				'.strtoupper(wordwrap($r['nama_pengirim'],45,"<br>\n")).'';
			}else{
				echo''.strtoupper($r['perusahaan_pengirim']).'';
			}
			?>
			</span></font></td>
    <td class="xl73" colspan="3" rowspan="2" style='border-right:1.0pt solid windowtext;border-bottom:none;' x:str>PENERIMA* :<font class="font23"><span style='mso-spacerun:yes;'><br/>
			<?php
			if(!empty($r['perusahaan_penerima'])){
				echo'
				'.strtoupper(wordwrap($r['perusahaan_penerima'],45,"<br>\n")).'</br>
				'.strtoupper(wordwrap($r['nama_penerima'],45,"<br>\n")).'';
			}else{
				echo''.strtoupper($r['nama_penerima']).'';
			}
			?>
			</span></font></td>
    <td class="xl74" colspan="2" style='border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;' x:str><?php echo''.$r['airwaybill_kode'].'';?></td>
   </tr>
   <tr height="20" style='height:15.00pt;'>
    <td class="xl75" x:str>LAYANAN* :</td>
    <td class="xl76"><?php echo''.strtoupper($r['service']).''; ?></td>
   </tr>
   <tr height="20" style='height:15.00pt;'>
    <td class="xl71" height="20" colspan="3" style='height:15.00pt;border-right:none;border-bottom:none;' x:str>HP NO* :<font class="font23"><span style='mso-spacerun:yes;'> <?php echo''.$r['hp_pengirim'].'';?></span></font></td>
    <td class="xl77" colspan="3" style='border-right:1.0pt solid windowtext;border-bottom:none;' x:str>HP NO* :<font class="font23"><span style='mso-spacerun:yes;'> <?php echo''.$r['hp_penerima'].'';?></span></font></td>
    <td class="xl78" x:str>PACKING* :</td>
    <td class="xl79"><?php echo''.strtoupper($packing).'';?></td>
   </tr>
   <tr height="20" style='height:15.00pt;'>
    <td class="xl72" height="62" colspan="3" rowspan="3" style='height:46.50pt;border-right:none;border-bottom:none;' x:str>ALAMAT *:<br/><font class="font23"><span style='mso-spacerun:yes;'><?php echo''.strtoupper(wordwrap($r['alamat_pengirim'],45,"<br>\n")).'';?></span></font></td>
    <td class="xl80" colspan="3" rowspan="3" style='border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;' x:str>ALAMAT* :<span style='mso-spacerun:yes;'><br/><?php echo''.strtoupper(wordwrap($r['alamat_penerima'],45,"<br>\n")).'';?></span></td>
    <td class="xl78" x:str>ASURANSI* :</td>
    <td class="xl79" x:str><?php if($r['asuransi']=='Y'){echo'YA';}else{echo'TIDAK';}?></td>
   </tr>
   <tr height="21" style='height:15.75pt;'>
    <td class="xl78" x:str>DO KEMBALI* :</td>
    <td class="xl79" x:str><?php if($r['surat_jalan']=='Y'){echo'YA';}else{echo'TIDAK';}?></td>
   </tr>
   <tr height="21" style='height:15.75pt;'>
    <td class="xl78" x:str>DI TAGIHKAN KE* :</td>
    <td class="xl79"><?php if($r['biaya_beban']=='1'){echo'PENGIRIM';}elseif($r['biaya_beban']=='2'){echo'PENERIMA';}elseif($r['biaya_beban']=='3'){echo'PIHAK KETIGA';}else{echo'TUNAI';}?></td>
   </tr>
   <tr height="20" style='height:15.00pt;'>
    <td class="xl81" height="20" style='height:15.00pt;' x:str>BERAT ( EST.)</td>
    <td class="xl81" x:str>COLLIE / PCS</td>
    <td class="xl82" x:str><span style='mso-spacerun:yes;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>PAKET / DOC</td>
    <td class="xl67" colspan="3" style='border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;' x:str>CATATAN PENGANTARAN (CESCO)<span style='mso-spacerun:yes;'>&nbsp;</span></td>
    <td class="xl83" x:str>KODE PENGIRIM* :</td>
    <td class="xl84" x:str><?php echo''.$r['loc_code'].'';?></td>
   </tr>
   <tr height="20" style='height:15.00pt;'>
    <td class="xl93" height="20" style='height:15.00pt;'><?php if(!empty($r['berat_barang'])){echo''.$r['berat_barang'].' Kg';}?></td>
    <td class="xl93"><?php echo''.$r['collie'].'';?></td>
    <td class="xl93"><?php echo''.strtoupper($r['jenis_barang']).'';?></td>
    <td class="xl87" colspan="3" style='border-right:none;border-bottom:none;'></td>
    <td class="xl88" x:str>TGL BOOKING :</td>
    <td class="xl89"><?php echo''.tgl_indo($r['tanggal']).'';?></td>
   </tr>
   <tr height="20" style='height:15.00pt;'>
    <td class="xl90" height="40" rowspan="2" style='height:30.00pt;border-right:none;border-bottom:1.0pt solid windowtext;' x:str>UKURAN BARANG</td>
    <td class="xl90" colspan="2" rowspan="2" style='border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;'><?php echo''.$r['ukuran_barang'].'';?></td>
    <td class="xl92" x:str>BERAT AKTUAL<span style='mso-spacerun:yes;'>&nbsp;</span></td>
    <td class="xl93" colspan="2" style='border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;'><?php if(!empty($r['berat_aktual'])){echo''.$r['berat_aktual'].' Kg';}?></td>
    <td class="xl95" x:str>DI AMBIL OLEH :</td>
    <td class="xl96"><?php echo''.strtoupper($r['pickup_by']).'';?></td>
   </tr>
   <tr height="20" style='height:15.00pt;'>
    <td class="xl90" x:str>TOTAL BIAYA<span style='mso-spacerun:yes;'>&nbsp;</span></td>
    <td class="xl93" colspan="2" style='border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;'></td>
    <td class="xl88" x:str>TANGGAL AMBIL<font class="font23"><span style='mso-spacerun:yes;'>&nbsp; </span>:</font></td>
    <td class="xl79"><?php echo''.tgl_indo($r['pickup_date']).'';?></td>
   </tr>
   <tr height="20" style='height:15.00pt;'>
    <td class="xl99" height="20" colspan="6" style='height:15.00pt;border-right:1.0pt solid windowtext;border-bottom:none;' x:str>CATATAN PENGIRIMAN &amp; RINCIAN BARANG</td>
    <td class="xl95" x:str>DIANTAR OLEH :</td>
    <td class="xl76"><?php echo''.strtoupper($r['delivery_by']).'';?></td>
   </tr>
   <tr height="20" style='height:15.00pt;'>
    <td class="xl103" height="141" colspan="6" rowspan="7" style='height:105.75pt;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;'><?php echo''.strtoupper($r['catatan']).'';?></td>
    <td class="xl106" x:str>NAMA PENERIMA</td>
    <td class="xl107" x:str>TANGGAL TERIMA</td>
   </tr>
   <tr height="20" style='height:15.00pt;'>
    <td class="xl108" rowspan="3" style='border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;'><?php echo'<b>'.strtoupper($r['delivery_name']).'</b>';?></td>
    <td class="xl109" rowspan="3" style='border-right:1.0pt solid windowtext;border-bottom:none;'><?php echo''.tgl_indo($r['delivery_date']).'';?></td>
   </tr>
   <tr height="20" style='height:15.00pt;'/>
   <tr height="20" style='height:15.00pt;'/>
   <tr height="20" style='height:15.00pt;'>
    <td class="xl110" colspan="2" rowspan="3" style='border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;' x:str>TANDA TANGAN PENERIMA</td>
   </tr>
   <tr height="20" style='height:15.00pt;'/>
   <tr height="21" style='height:15.75pt;'/>
   <![if supportMisalignedColumns]>
    <tr width="0" style='display:none;'>
     <td width="82" style='width:61;'></td>
     <td width="97" style='width:73;'></td>
     <td width="96" style='width:72;'></td>
    </tr>
   <![endif]>
  </table>
  </page>
  <script type="text/javascript">
	window.print();
	window.onfocus=function(){ window.close();}
	</script>
 </body>
</html>