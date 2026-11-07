<?php
include ('../../config/connection.php');
include ('../../config/encrypt.php');
include ('../../config/library.php');
include ('phpqrcode/qrlib.php');

/*$key = "asdf$1011";
$encrypt = "$_GET[id]";
$id = base64_decrypt($encrypt,$key);

$pieces = explode('-', $id);
$delivery_by = $pieces['0'];
$delivery_date = implode('-', array_slice($pieces, 1, 3));

$query = mysqli_query($con, "select b.namalengkap from pengiriman a inner join user b on b.id_user=a.delivery_by where a.delivery_by='$delivery_by' and DATE_FORMAT(a.delivery_date,'%Y-%m-%d')='$delivery_date'");
$r = mysqli_fetch_assoc($query);

$tanggal = tgl_indo($delivery_date);

$query_kode_manifest = mysqli_query($con, "select kode_manifest from manifest
				where tanggal='$delivery_date' and manifest_delivery_by = '$delivery_by'");
			$r_kode = mysqli_fetch_assoc($query_kode_manifest);*/

session_start();

if (empty($_SESSION['username']) AND empty($_SESSION['password']))
{
	header('location:../../application/login.php');
}
else
{

$dari = $_GET['dari'];
$sampai = $_GET['sampai'];
$id_kustomer = $_GET['id_kustomer'];
$idinvoice = $_GET['idinvoice'];
$stts = (isset($_GET['stts'])) ? $_GET['stts'] : '';

$judul = ($stts == 'loss') ? 'Loss' : '';

$key 			= "asdf$1011";
$encryptUser 	= $_SESSION['id_user'];
$id_user 		= base64_decrypt($encryptUser,$key);

$query 		= mysqli_query($con,"select a.*, case when a.status='draft' then 'DRAFT' else a.nomor end as nomor, b.kode_kustomer, b.nama_kustomer, b.alamat, b.kota, c.nama_kota, x_namakota(x_hub(b.kota)) as kota, b.telp from invoice a left join kustomer b on b.id_kustomer=a.id_kustomer left join kota c on c.id_kota=b.kota where id_invoice='$idinvoice'");
			$r 			= mysqli_fetch_assoc($query);

header("Content-type: application/vnd-ms-excel");
header('Content-Disposition: attachment; filename="'.$r['nama_kustomer'].'-'.$idinvoice.'_'.$id_user.'-'.$stts.'.xls"');
?>

<!DOCTYPE html>
<html>
	<head>
	<meta charset="UTF-8">
		<title>Invoice Customer Detail</title>
	</head>
	<body>
		<table cellspacing=0 width="100%">
			<tr>
				<td style="font-family:Calibri;text-align:center;font-size:16px;color:#000000;font-weight:bold;" colspan="8">Invoice Customer Detail <?php echo $judul;?></td>
			</tr>
			<tr>
				<td colspan="10"></td>
			</tr>

			<tr>
			  <td colspan="2" style=min-width:50px><span style="font-family:Calibri;text-align:right;font-size:14px;color:#000000;">Customer :</span></td>
			  <td colspan="8" style=min-width:50px><span style="font-family:Calibri;font-size:14px;color:#000000;"><?php echo''.$r['nama_kustomer'];?></span></td>
		    </tr>
			<tr>
			  <td colspan="2" style=min-width:50px><span style="font-family:Calibri;text-align:right;font-size:14px;color:#000000;">Periode :</span></td>
			  <td colspan="8" style=min-width:50px><span style="font-family:Calibri;font-size:14px;color:#000000;"><?php echo''.$r['dari'].' - '.$r['sampai'];?></span></td>
		    </tr>

			<tr>
				<td colspan="8">&nbsp;</td>
			</tr>
			<tr>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border:1px solid;">NO</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border:1px solid;">Tanggal Pickup</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">AWB</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">komoditi</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Service</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Requestor</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Loc Code</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Pengirim</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Nama Perusahaan Pengirim</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Kota Asal</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Alamat lengkap Asal</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Nama Perusahaan Penerima</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Nama Penerima</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Kota Tujuan</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Alamat lengkap Penerima</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Nama Penerima</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Tanggal Diterima</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Volume</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Berat Aktual</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Harga/kg</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">min kg</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Tambahan</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Harga Pengiriman</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Packing</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Asuransi</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Total</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">PU Fee</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">PU Toll</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">PU Parking</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Pu Men</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Deliv Fee</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Deliv Toll</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Deliv parking</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Deliv Men</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Out Fee</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Out Toll</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Out SMU</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Out Handling</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Out Parking</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Out Men</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">In Fee</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">In Toll</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">In Smu</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">In Handling</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">In Parking</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">In Men Power</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Inv Agent PU</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Inv Agent Transit</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Inv Agent Deliv</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Total Inv Agent After Discount</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Total Costing</td>
				<td style="font-family:Calibri;text-align:center;font-size:14px;font-weight:bold;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">Profit & Loss</td>
			</tr>
			<?php
			$no=1;
			$carix = "SELECT
									a.id_pengiriman,
									date(pickup_date) as tanggal,
									airwaybill_kode,
									case when a.komoditi='1' then 'Paket' else 'Dokumen' end as komoditi,
									a.jenis_barang,
									x_service(service) as service,
									nama_pengirim,
									a.loc_code,
									x_namakustomer(a.id_kustomer) as namakustomer,
									nama_penerima,
									case when inbound='Y' then 'INBOUND' when inbound='N' then 'OUTBOUND' else '' end as inbound,
									a.lokasi_asal as id_lokasi_asal,
									x_namakotabesar(lokasi_asal) as lokasi_asal,
									alamat_pengirim,
									lokasi_tujuan,
									if(a.service=6,f.nama_negara,x_namakotabesar(lokasi_tujuan)) as lokasi_tujuan,
									alamat_penerima,
									delivery_name,
									delivery_date,

									CASE
										WHEN airwaybill_kode LIKE '001-%' THEN 0
										WHEN airwaybill_kode LIKE '002-%' THEN 1
									end as intercity,

									SUBSTRING_INDEX(ukuran_barang,'x',1) as length,
									SUBSTRING_INDEX(SUBSTRING_INDEX(ukuran_barang,'x',2),'x',-1) as width,
									SUBSTRING_INDEX(ukuran_barang,'x',-1) as height,

									if( (select intercity) = 1,
										ceiling((select length) *  (select width) * (select height)/4000)
									,
										if (a.service = 5,
											ceiling((select length) *  (select width) * (select height)/4000)
										,
											ceiling((select length) *  (select width) * (select height)/6000)
										)
									) as volume,

									if(berat_aktual is null or berat_aktual < 1,  berat_barang, berat_aktual) as berat_real,

									case when packing_kayu='1' then 'Packing Oleh Pemilik' when packing_kayu='2' then 'Packing Kayu Oleh Cesco Log.' when packing_kayu='3' then 'Non Packing Kayu Oleh Cesco Log.' end as packing_kayu,
									ifnull(b.min_kg,c.min_kg) as min_kg,
									ifnull(b.min_kg,c.min_kg) as min_kgg,
									ifnull(b.next_kg,c.next_kg) as next_kg,
									ifnull(b.harga,c.harga) as harga_perkg,
									ifnull(b.min_kg_2,c.min_kg_2) as min_kgg_2,
									ifnull(b.harga_2,c.harga_2) as harga_perkgg_2,
									harga_pengiriman,
									if ((select berat_real) >= (select volume), (select berat_real), (select volume) ) as berat,

									if ((select min_kgg) >= (select berat), (select min_kgg), (select berat) ) as berat_finish,

									((select harga_perkg) * (select berat_finish))+a.biaya_tambahan as total,

									ifnull(  harga_pengiriman ,  (select total) ) as harga_total,

									((select harga_perkg) * (select berat_finish))+ a.biaya_packing+a.biaya_tambahan+a.biaya_asuransi as total_new,


									ifnull(  harga_pengiriman ,  (select total_new) ) as total_fix,

									ifnull(  harga_kunci ,  (select total_fix) ) as harga_lock,


									if(if(berat_aktual is null or berat_aktual < 1,  berat_barang, berat_aktual) < ifnull(b.min_kg,c.min_kg), (ifnull(b.min_kg,c.min_kg)*ifnull(b.harga,c.harga))+a.biaya_packing+a.biaya_tambahan+a.biaya_asuransi , (if(berat_aktual is null or berat_aktual < 1,  berat_barang, berat_aktual)*ifnull(b.harga,c.harga))+a.biaya_packing+a.biaya_tambahan+a.biaya_asuransi ) as jumlah_harga,

									if(if(berat_aktual is null or berat_aktual < 1,  berat_barang, berat_aktual) < ifnull(d.min_kg,e.min_kg), (ifnull(d.min_kg,e.min_kg)*ifnull(d.price,e.price)) , (if(berat_aktual is null or berat_aktual < 1,  berat_barang, berat_aktual)*ifnull(d.price,e.price))) as total_agent,
                                	ifnull(d.price,e.price) as price_agent,
                                	ifnull(d.min_kg,e.min_kg) as min_kg_agent,
                                	a.perusahaan_pengirim,
                                	a.perusahaan_penerima,
									if(a.packing_kayu=2,if(a.biaya_packing < 1,if(a.berat_aktual is null or a.berat_aktual < 1,  a.berat_barang, a.berat_aktual)*2500,a.biaya_packing),0 ) as packing,
									if(a.asuransi = 'Y', if(a.nilai_barang >= 6000000,150000,0.25/100*a.nilai_barang) ,0) as nilai_asuransi,
									ifnull(a.biaya_asuransi,0) as biaya_asuransi,
									ifnull(a.biaya_tambahan,0) as biaya_tambahan,
									ifnull(a.biaya_packing,0) as biaya_packing,
									ifnull(a.costing_pickup_fee,0) as costing_pickup_fee,
									ifnull(a.costing_pickup_parking,0) as costing_pickup_parking,
									ifnull(a.costing_pickup_toll,0) as costing_pickup_toll,
									ifnull(a.costing_men_power,0) as costing_men_power,
									ifnull(a.costing_delivery_fee,0) as costing_delivery_fee,
									ifnull(a.costing_delivery_parking,0) as costing_delivery_parking,
									ifnull(a.costing_delivery_toll,0) as costing_delivery_toll,
									ifnull(a.costing_delivery_menpower,0) as costing_delivery_menpower,
									ifnull(a.costing_outbound_fee,0) as costing_outbound_fee,
									ifnull(a.costing_outbound_toll,0) as costing_outbound_toll,
									ifnull(a.costing_outbound_smu,0) as costing_outbound_smu,
									ifnull(a.costing_outbound_handling,0) as costing_outbound_handling,
									ifnull(a.costing_outbound_parking,0) as costing_outbound_parking,
									ifnull(a.costing_outbound_menpower,0) as costing_outbound_menpower,
									ifnull(a.costing_inbound_fee,0) as costing_inbound_fee,
									ifnull(a.costing_inbound_toll,0) as costing_inbound_toll,
									ifnull(a.costing_inbound_smu,0) as costing_inbound_smu,
									ifnull(a.costing_inbound_handling,0) as costing_inbound_handling,
									ifnull(a.costing_inbound_parking,0) as costing_inbound_parking,
									ifnull(a.costing_inbound_menpower,0) as costing_inbound_menpower,
									a.pid,
									ifnull(b.id,c.id) as id_rate_customer,
									if(a.id_kota_tujuan > 0,
									a.kota_asal,
									x_namakotabesar(lokasi_asal)) as lokasi_asal,
									if(a.id_kota_tujuan > 0,
									a.kota_tujuan,
									x_namakotabesar(lokasi_tujuan)) as lokasi_tujuan,
									(ifnull(a.kunci_total_agent,0) + ifnull(a.kunci_total_agent_deliv,0) + ifnull(a.kunci_total_agent_pickup,0) + ifnull(md.kunci_total_agent_transit,0) ) as total_agent,
									ifnull(a.kunci_total_agent,0) as kunci_total_agent,
									ifnull(a.kunci_total_agent_deliv,0) as kunci_total_agent_deliv,
									ifnull(a.kunci_total_agent_pickup,0) as kunci_total_agent_pickup,
									ifnull(md.kunci_total_agent_transit,0) as kunci_total_agent_transit,
									case when terlambat_pickup in (1,2) and terlambat_delivery in (1,2) then
											ifnull(a.diskon_pickup,0)+ifnull(a.diskon_deliv,0)
										when terlambat_pickup in (1,2) and terlambat_delivery not in (1,2) then
											ifnull(a.diskon_pickup,0)
										when terlambat_pickup not in (1,2) and terlambat_delivery in (1,2) then
											ifnull(a.diskon_deliv,0)
									else 0
									end as diskon,
									ifnull(a.biaya_packing_agent,0) as biaya_packing_agent,
									ifnull(a.biaya_tambahan_agent,0) as biaya_tambahan_agent,
									ifnull(md.biaya_tambahan_agent_transit,0) as biaya_tambahan_agent_transit,
									x_inter(id_kota_asal) as inter_asal, x_inter(id_kota_tujuan) as inter_tujuan,
									getCabangCesco(a.id_pengiriman) as jumlah_cabang
								FROM pengiriman a
								left join rate_customer b ON b.id_kustomer=a.id_kustomer and b.id_lokasi_asal=a.lokasi_asal and b.id_lokasi_tujuan=a.lokasi_tujuan and b.id_pelayanan=a.service and b.komoditi=a.komoditi and b.loc_code=a.loc_code
								left join rate_customer c ON c.id_kustomer=a.id_kustomer and c.id_lokasi_asal=a.lokasi_asal and c.id_lokasi_tujuan=a.lokasi_tujuan and c.id_pelayanan=a.service and c.komoditi=a.komoditi

								LEFT JOIN rate_agent d ON d.id_cabang=39 AND d.id_kota=a.lokasi_tujuan AND d.id_pelayanan=CASE WHEN a.service > 3 THEN 3 else a.service end AND d.komoditi=a.komoditi

								LEFT JOIN rate_agent_inbound e ON e.id_cabang=a.id_cabang  and e.id_pelayanan=CASE WHEN a.service > 3 THEN 3 else a.service end and e.komoditi=a.komoditi
								left join manifest_detail md on md.id_pengiriman = a.id_pengiriman and md.kunci_total_agent_transit is not null
								LEFT JOIN negara f ON f.id_negara=a.lokasi_tujuan and a.service=6
								join invoice i on i.id_invoice = a.id_invoice
								where a.id_invoice = $idinvoice
								/*a.tanggal BETWEEN '$dari' AND '$sampai' and a.id_kustomer=$id_kustomer*/
								and a.status != 7
								and i.status = 'final'
								group by a.id_pengiriman";


			$cari = "select x.*, substring_index (substring_index(rate_customer,'~',1), ',', -1) as id_rate_customer,
						substring_index (substring_index(rate_customer,'~',3), '~', -1) as min_kg,
                        substring_index (substring_index(rate_customer,'~',3), '~', -1) as min_kgg,
                        substring_index (substring_index(rate_customer,'~',6), '~', -1) as next_kg,
                        substring_index (substring_index(rate_customer,'~',2), '~', -1) as harga_perkg,
                        substring_index (substring_index(rate_customer,'~',5), '~', -1) as min_kgg_2,
                        substring_index (substring_index(rate_customer,'~',4), '~', -1) as harga_perkgg_2,
                        if((select berat_real) >= (select volume),
                            (select berat_real),
                            (select volume)) as berat,
                        if((select min_kgg) >= (select berat),
                            (select min_kgg),
                            (select berat)) as berat_finish,
                         case when kode_kustomer = 'DHL' and (select next_kg) > 0 then
                          ((select harga_perkg) + ((select next_kg) * ((select berat_finish)-1))) + biaya_tambahan
                         else
                          ((select harga_perkg) * (select berat_finish)) + biaya_tambahan
                         end as total_new,
                        ifnull(harga_pengiriman, (select total_new)) as total_fix,
                        ifnull(harga_kunci, (select total_fix)) as harga_lock,
                        if(if(berat_aktual is null or berat_aktual < 1,
                                berat_barang,
                                berat_aktual) < (select min_kg),
                            ((select min_kg) * (select harga_perkg)) + biaya_packing + biaya_asuransi,
                            (if(berat_aktual is null or berat_aktual < 1,
                                berat_barang,
                                berat_aktual) * (select harga_perkg)) + biaya_packing + biaya_asuransi) as jumlah_harga
                        from (	SELECT
                        a.id_pengiriman,
                        date(pickup_date) as tanggal,
                        airwaybill_kode,
                        case
                            when a.komoditi = '1' then 'Paket'
                            else 'Dokumen'
                        end as komoditi,
                        a.jenis_barang,
                        a.catatan,
						pel.nama_pelayanan as service,
                        nama_pengirim,
                        a.loc_code,
						cs.nama_kustomer as namakustomer,
                        nama_penerima,
                        case
                            when inbound = 'Y' then 'INBOUND'
                            when inbound = 'N' then 'OUTBOUND'
                            else ''
                        end as inbound,
                        a.lokasi_asal as id_lokasi_asal,
                        if(a.id_kota_tujuan > 0,
                            a.kota_asal,
                            x_namakotabesar(lokasi_asal)) as lokasi_asal,
                        id_kecamatan_asal,
                        x_namakecamatan(id_kecamatan_asal) as kec_asal,
                        alamat_pengirim,
                        a.id_kecamatan_tujuan,
                        x_namakecamatan(a.id_kecamatan_tujuan) as kec_tujuan,
                        if(a.id_kota_tujuan > 0,
                            a.kota_tujuan,
                            x_namakotabesar(lokasi_tujuan)) as lokasi_tujuan,
                        alamat_penerima,
                        delivery_name,
                        delivery_date,
                        CASE
                            WHEN airwaybill_kode LIKE '001-%' THEN 0
                            WHEN airwaybill_kode LIKE '002-%' THEN 1
                        end as intercity,
                        SUBSTRING_INDEX(ukuran_barang, 'x', 1) as length,
                        SUBSTRING_INDEX(SUBSTRING_INDEX(ukuran_barang, 'x', 2),
                                'x',
                                - 1) as width,
                        SUBSTRING_INDEX(ukuran_barang, 'x', - 1) as height,
                        case when upper(cs.kode_kustomer) in ('CIS','DEL') then
                          if((select intercity) = 1,
                            ((select length) * (select width) * (select height) / 4000),
                            if(a.service = 5,
                                ((select length) * (select width) * (select height) / 4000),
                                ((select length) * (select width) * (select height) / 6000)))
                        else
                            if((select intercity) = 1,
                                ceiling((select length) * (select width) * (select height) / 4000),
                                if(a.service = 5,
                                    ceiling((select length) * (select width) * (select height) / 4000),
                                    ceiling((select length) * (select width) * (select height) / 6000)))
                        end as volume,
                        if(berat_aktual is null or berat_aktual < 1,
                            berat_barang,
                            berat_aktual) as berat_real,
                        case
                            when packing_kayu = '1' then 'Packing Oleh Pemilik'
                            when packing_kayu = '2' then 'Packing Kayu Oleh Cesco Log.'
                            when packing_kayu = '3' then 'Non Packing Kayu Oleh Cesco Log.'
                        end as packing_kayu,
						a.perusahaan_pengirim,
                        a.perusahaan_penerima,
                        a.asuransi,
						a.berat_aktual,
						a.berat_barang,
						if(a.asuransi = 'Y',
                            if(a.nilai_barang >= 6000000,
                                150000,
                                0.25 / 100 * a.nilai_barang),
                            0) as nilai_asuransi,
                        a.biaya_packing,
						if(a.packing_kayu = 2,
                            if(a.biaya_packing < 1,
                                if(a.berat_aktual is null
                                        or a.berat_aktual < 1,
                                    a.berat_barang,
                                    a.berat_aktual) * 2500,
                                a.biaya_packing),
                            0) as packing,
                        a.biaya_asuransi,
                        a.biaya_tambahan,
                        a.keterangan_biaya,
						a.harga_pengiriman,
                        a.harga_kunci,
                        a.costing_pickup_fee,
                        a.costing_pickup_parking,
                        a.costing_pickup_toll,
                        a.costing_men_power,
                        a.costing_delivery_fee,
                        a.costing_delivery_parking,
                        a.costing_delivery_toll,
                        a.costing_delivery_menpower,
                        a.costing_outbound_fee,
                        a.costing_outbound_toll,
                        a.costing_outbound_smu,
                        a.costing_outbound_handling,
                        a.costing_outbound_parking,
                        a.costing_outbound_menpower,
                        a.costing_inbound_fee,
                        a.costing_inbound_toll,
                        a.costing_inbound_smu,
                        a.costing_inbound_handling,
                        a.costing_inbound_parking,
                        a.costing_inbound_menpower,
						ifnull(a.biaya_packing_agent,0) as biaya_packing_agent,
						ifnull(a.biaya_tambahan_agent,0) as biaya_tambahan_agent,
						ifnull(md.biaya_tambahan_agent_transit,0) as biaya_tambahan_agent_transit,
						a.pid,
                        (ifnull(a.kunci_total_agent,0) + ifnull(a.kunci_total_agent_deliv,0) + ifnull(a.kunci_total_agent_pickup,0) + ifnull(md.kunci_total_agent_transit,0) ) as total_agent,
						ifnull(a.kunci_total_agent,0) as kunci_total_agent,
						ifnull(a.kunci_total_agent_deliv,0) as kunci_total_agent_deliv,
						ifnull(a.kunci_total_agent_pickup,0) as kunci_total_agent_pickup,
						ifnull(md.kunci_total_agent_transit,0) as kunci_total_agent_transit,
						cs.kode_kustomer,
						case when terlambat_pickup in (1,2) and terlambat_delivery in (1,2) then
								ifnull(a.diskon_pickup,0)+ifnull(a.diskon_deliv,0)
							when terlambat_pickup in (1,2) and terlambat_delivery not in (1,2) then
								ifnull(a.diskon_pickup,0)
							when terlambat_pickup not in (1,2) and terlambat_delivery in (1,2) then
								ifnull(a.diskon_deliv,0)
						else 0
						end as diskon,
						pickup_by_agent, delivery_by_agent, kunci_berat_agent,
						x_inter(id_kota_asal) as inter_asal, x_inter(id_kota_tujuan) as inter_tujuan,
						getCabangCesco(a.id_pengiriman) as jumlah_cabang,
						getRateCustomer2(a.id_kustomer, a.id_kecamatan_asal, a.lokasi_asal, a.lokasi_tujuan, a.id_kota_asal, a.id_kota_tujuan, a.id_kecamatan_tujuan, a.service, a.komoditi, a.loc_code) as rate_customer,
						(select count(1) from invoice_send
							where invoice_type='AGENT' and agent_date = date_format(a.tanggal,'%Y-%m') and agent_id = a.pickup_by_agent
							and confirm_dtm is not null
						) as confirm_agent_pu,
						(select count(1) from invoice_send
							where invoice_type='AGENT' and agent_date = date_format(a.tanggal,'%Y-%m') and agent_id = a.delivery_by_agent
							and confirm_dtm is not null
						) as confirm_agent_deliv,
						(select count(1) from invoice_send i
							join manifest m on m.manifest_from = i.agent_id
							where invoice_type='AGENT' and agent_date = date_format(a.tanggal,'%Y-%m')
							and m.id_manifest = md.id_manifest
							and confirm_dtm is not null
						) as confirm_agent_transit,
						(select count(1) from invoice_send
							where invoice_type='AGENT' and agent_date = date_format(a.tanggal,'%Y-%m') and agent_id = a.delivery_by_agent
							and agent_id = a.pickup_by_agent
							and confirm_dtm is not null
						) as confirm_agent_intra
                    FROM
                        pengiriman a
						left join manifest_detail md on md.id_pengiriman = a.id_pengiriman
						left join kustomer cs on cs.id_kustomer = a.id_kustomer
						left join pelayanan pel on pel.id_pelayanan = a.service
                            LEFT JOIN
                        negara f ON f.id_negara = a.lokasi_tujuan
                            and a.service = 6
                    where
                        a.id_invoice = $idinvoice and a.status != 7 ";



      $cari .= " group by a.id_pengiriman) x";


			$sum_total=0;
			$sql = mysqli_query($con,$cari);

			$grand_total_costing = 0;
			$total_profitloss = 0;
			while($t=mysqli_fetch_assoc($sql)){


					$totalAgent = 0;
					$totalAgenPickup = ($t['confirm_agent_pu'] == 0) ? 0 : $t['kunci_total_agent_pickup'];
					$totalAgentDeliv = ($t['confirm_agent_deliv'] == 0) ? 0 : $t['kunci_total_agent_deliv'];
					$totalAgentTransit = ($t['confirm_agent_transit'] == 0) ? 0 : $t['kunci_total_agent_transit'];
					$totalAgentIntra = ($t['confirm_agent_intra'] == 0) ? 0 : $t['kunci_total_agent'];

					if ($t['intercity'] == 0){ //001

						if ($t['pickup_by_agent'] == $t['delivery_by_agent']){

							$totalAgent = $totalAgentIntra;
							$totalAgentDeliv = $totalAgentIntra;

						}else{

							if ($t['inter_asal'] != 'Y' && $t['inter_tujuan'] != 'Y'){ //CROSS CITY

								$totalAgent = $totalAgenPickup + $totalAgentDeliv + $totalAgentTransit;



							}else if ($t['inter_asal'] != 'Y' && $t['inter_tujuan'] == 'Y'){	//INBOUND

								$totalAgent = $totalAgenPickup + $totalAgentTransit;




							}else if ($t['inter_asal'] == 'Y' && $t['inter_tujuan'] != 'Y'){ //OUTBOUND

								$totalAgent = $totalAgentDeliv + $totalAgentTransit;


							}
						}

					}else{ //002

						$totalAgent = $totalAgenPickup + $totalAgentDeliv + $totalAgentTransit;

					}

					if ($t['confirm_agent_pu'] == 0 && $t['confirm_agent_deliv'] == 0 && $t['confirm_agent_transit'] == 0 && $t['confirm_agent_intra'] == 0){
						$new_total_diskon = 0;
						$total_agent = 0;
						$diskon = 0;
						$totalAgentAfterDiskon = 0;
					}else{
						$new_total_diskon = $totalAgent - ($t['biaya_packing_agent']+$t['biaya_tambahan_agent']+$t['biaya_tambahan_agent_transit']);
						$total_agent = $new_total_diskon;
						$diskon = $t['diskon'];
						$totalAgentAfterDiskon = ($total_agent-(($diskon/100)*$total_agent))+($t['biaya_packing_agent']+$t['biaya_tambahan_agent']+$t['biaya_tambahan_agent_transit']);
					}

					$harga_total = $t['harga_lock']+$t['biaya_packing']+$t['biaya_asuransi'];
					$sum_total = $sum_total+$harga_total;

					$total_costing = $row['costing_pickup_fee']+$row['costing_pickup_parking']+$row['costing_pickup_toll']+$row['costing_men_power']+$row['costing_delivery_fee']+$row['costing_delivery_parking']+$row['costing_delivery_toll']+$row['costing_delivery_menpower']+$row['costing_outbound_fee']+$row['costing_outbound_toll']+$row['costing_outbound_smu']+$row['costing_outbound_handling']+$row['costing_outbound_parking']+$row['costing_outbound_menpower']+$row['costing_inbound_fee']+$row['costing_inbound_toll']+$row['costing_inbound_smu']+$row['costing_inbound_handling']+$row['costing_inbound_parking']+$row['costing_inbound_menpower']+$totalAgentAfterDiskon;
					
					$profit_loss = $harga_total-$total_costing;

					if ($stts == '' || ($stts == 'loss' && $profit_loss < 0)){

							$grand_total_costing = $grand_total_costing+$total_costing;
							$total_profitloss = $profit_loss+$total_profitloss;

							//$volume = ($t['length']*$t['width']*$t['height'])/6000;
							if($t['lokasi_asal']=='Jakarta Selatan' || $t['lokasi_asal']=='Jakarta Timur' || $t['lokasi_asal']=='Jakarta Barat' ||		$t['lokasi_asal']=='Jakarta Utara' || $t['lokasi_asal']=='Jakarta Pusat'){
								$lokasi_asal = 'Jakarta';
							}else{
								$lokasi_asal = $t['lokasi_asal'];
							}

							if($t['lokasi_tujuan']=='Jakarta Selatan' || $t['lokasi_tujuan']=='Jakarta Timur' || $t['lokasi_tujuan']=='Jakarta Barat' ||		$t['lokasi_tujuan']=='Jakarta Utara' || $t['lokasi_tujuan']=='Jakarta Pusat'){
								$lokasi_tujuan = 'Jakarta';
							}else{
								$lokasi_tujuan = $t['lokasi_tujuan'];
							}

							echo'
							<tr>
								<td style="font-family:Calibri;text-align:center;font-size:14px;font-color:#000000;border-color:#000;border:1px solid;">'.$no.'</td>
								<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['tanggal'].'</td>
								<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['airwaybill_kode'].'</td>
								<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.($t['jenis_barang'] == "" ? $t['komoditi'] : "" ).' '.  strtoupper($t['jenis_barang']).'</td>
								<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['service'].'</td>
								<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['namakustomer'].'</td>
								<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['loc_code']. ($t['pid'] != "" ? " , PID = ".$t[pid] : "" ).'</td>
								<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['nama_pengirim'].'</td>
								<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['perusahaan_pengirim'].'</td>
								<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$lokasi_asal.'</td>
								<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['alamat_pengirim'].'</td>
								<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['perusahaan_penerima'].'</td>
								<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['nama_penerima'].'</td>
								<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$lokasi_tujuan.'</td>
								<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['alamat_penerima'].'</td>
								<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['delivery_name'].'</td>
								<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['delivery_date'].'</td>
								<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['length'].'x'.$t['width'].'x'.$t['height'].'= '.round($t['volume']).'</td>
								<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.$t['berat'].'</td>
								<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.($t['harga_pengiriman'] == '' ? $t['harga_perkg'] : '-' ).'</td>
								<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.($t['harga_pengiriman'] == '' ? $t['min_kgg'] : '-' ).'</td>
								<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.round($t['biaya_tambahan']).'</td>
								<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.round($t['harga_lock']).'</td>
								<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.round($t['biaya_packing']).'</td>
								<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.round($t['biaya_asuransi']).'</td>
								<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.round($harga_total).'</td>

								<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.round($t['costing_pickup_fee']).'</td>
								<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.round($t['costing_pickup_toll']).'</td>
								<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.round($t['costing_pickup_parking']).'</td>
								<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.round($t['costing_men_power']).'</td>

								<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.round($t['costing_delivery_fee']).'</td>
								<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.round($t['costing_delivery_toll']).'</td>
								<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.round($t['costing_delivery_parking']).'</td>
								<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.round($t['costing_delivery_menpower']).'</td>

								<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.round($t['costing_outbound_fee']).'</td>
								<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.round($t['costing_outbound_toll']).'</td>
								<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.round($t['costing_outbound_smu']).'</td>
								<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.round($t['costing_outbound_handling']).'</td>
								<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.round($t['costing_outbound_parking']).'</td>
								<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.round($t['costing_outbound_menpower']).'</td>

								<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.round($t['costing_inbound_fee']).'</td>
								<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.round($t['costing_inbound_toll']).'</td>
								<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.round($t['costing_inbound_smu']).'</td>
								<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.round($t['costing_inbound_handling']).'</td>
								<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.round($t['costing_inbound_parking']).'</td>
								<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.round($t['costing_inbound_menpower']).'</td>

								<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.round($totalAgenPickup).'</td>
								<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.round($totalAgentTransit).'</td>
								<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.round($totalAgentDeliv).'</td>
								<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.round($totalAgentAfterDiskon).'</td>
								<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.round($total_costing).'</td>
								<td style="font-family:Calibri;text-align:center;font-size:14px;color:#000000;border-color:#000;border-top:1px solid;border-right:1px solid;border-bottom:1px solid;">'.round($profit_loss).'</td>

							</tr>
							';
							$no++;

				}

			}
			?>

			<tr>
			  <td colspan="25">&nbsp;</td>
			  <td colspan="1"><b><?php echo round($sum_total);  ?></b></td>
			  <td colspan="21">&nbsp;</td>
			  <td colspan="1"><b><?php echo round($grand_total_costing);  ?></b></td>
			  <td colspan="1"><b><?php echo round($total_profitloss);  ?></b></td>

		  </tr>

			<tr>
				<td colspan="10"></td>
			</tr>

			<tr>
			  <td colspan="2" style=min-width:50px><span style="font-family:Calibri;text-align:right;font-size:14px;color:#000000;">Total Revenue :</span></td>
			  <td colspan="2" style=min-width:50px><span style="font-family:Calibri;font-size:14px;color:#000000;"><?php echo round($sum_total);  ?></span></td>

			</tr>
			<tr>
			  <td colspan="2" style=min-width:50px><span style="font-family:Calibri;text-align:right;font-size:14px;color:#000000;">Total PL :</span></td>
			  <td colspan="2" style=min-width:50px><span style="font-family:Calibri;font-size:14px;color:#000000;"><?php echo round($total_profitloss);  ?></span></td>
			</tr>
		</table>

	</body>
</html>
<?php
}
?>
