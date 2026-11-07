<section class="content-header">
	<h1>Profit & Loss</h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-home"></i> Home</a></li>
		<li><a href="#">Finance</a></li>
		<li><a href="#">Invoice</a></li>
		<li class="active">Profit & Loss</li>
	</ol>
</section>
<?php
if(!isset($_SESSION))
{
	session_start();
}

if (empty($_SESSION['username']) AND empty($_SESSION['password']))
{
	header('location:../../application/login.php');
}
else
{
	$key 			= "asdf$1011";
	$encryptUser 	= $_SESSION['id_user'];
	$id_user 		= base64_decrypt($encryptUser,$key);

	//cek hak akses user
	if ($u['id_user'] == 3 || $u['id_user'] == 4 || $u['id_user'] == 83)
	{
		$aksi	= "module/mod_invoice-customer/invoice-customer_action.php";
		$act	= !empty($_GET['act']) ? $_GET['act'] : '';

		switch($act)
		{

			default:
			echo'
			<section class="content">
				<div class="row">
					<div class="col-md-12">
						<div class="box box-primary">
							<div class="box-header">
								<div class="row">
									
									<div class="col-md-12 col-offset-md-6">
									<div class="btn-group pull-right">
											<button class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown">Tools <i class="fa fa-angle-down"></i></button>
											<ul class="dropdown-menu pull-right">

												<li><a href="main.php?module=profit-loss&act=cari-costing-merah" class=""><i class="fa fa-search"></i>Cari Costing Merah</a></li>
												<li><a href="main.php?module=profit-loss&act=report-per-customer" class=""><i class="fa fa-search"></i> Report Summary Per Customer Per Tahun</a></li>

												
											</ul>
										</div>
									</div>
								</div>
							</div>
							<div class="box-body">
								<table id="profitLoss" class="table table-bordered table-striped table-hover" width="100%" cellspacing="0" style="white-space:nowrap;">
									<thead>
										<tr>
											<th>Nomor</th>
											<th>Tanggal</th>
											<th>metode</th>
											<th>Customer</th>
											<th>Tanggal Airwaybill</th>
											<th width="50">Download</th>
											<th width="65"><center>Detail</center></th>
										</tr>
									</thead>
									<tbody>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</section>';

			break;

			case "detail":

			$key		= "asdf$1011";
			$encrypt	= "$_GET[id]";
			$id 		= base64_decrypt($encrypt,$key);
			$idInv = $id;

			$query 		= $mysqli->query("select a.*,d.nama_bank, d.id as id_bank, case when a.status='draft' then 'DRAFT' else a.nomor end as nomor,ppn,
				b.kode_kustomer, b.nama_kustomer, b.alamat, b.kota,b.tagihan_ke, c.nama_kota, x_namakota(x_hub(b.kota)) as kota, b.telp
				from invoice a left join kustomer b on b.id_kustomer=a.id_kustomer left join kota c on c.id_kota=b.kota left join rekening_cesco d on a.bank=d.id where id_invoice='$id'");
			$r= $query->fetch_assoc();
			$tanggal = $r['tanggal'];
			$nomor = $r['nomor'];
			$ppn = $r['ppn'];
			$id_kustomer = $r['id_kustomer'];
			$tgl_due_date = date('Y-m-d', strtotime('+45 days', strtotime($tanggal)));
			$id_tagihan = $r['tagihan_ke'];

			echo'
			<section class="invoice">
      			<div class="row">
        			<div class="col-xs-12">
          				<h2 class="page-header">
            				<i class="fa fa-truck"></i> PT. Delta Fortuna Forwarders (Cesco Logistics)
							<small class="pull-right">Date: '.$r['tanggal'].'</small>
						</h2>
					</div>
				</div>
				<div class="row invoice-info">
					<div class="col-sm-8 invoice-col">
						';

							echo'
							<address>
								<strong>'.$r['nama_kustomer'].'</strong><br>
	            				'.$r['alamat'].'<br>
	            				'.$r['nama_kota'].'<br>
							'.$r['kota'].'<br>
	            			Phone: '.$r['telp'].'
	          				</address>';
          				if($id_tagihan != null){
          					$tagihan = $mysqli->query("select nama_kustomer, alamat,  x_namakota(x_hub(kota)) as kota, telp from kustomer where id_kustomer='$id_tagihan'");
          					$t= $tagihan->fetch_assoc();
          					echo'
          					Paid To
							<address>
								<strong>'.$t['nama_kustomer'].'</strong><br>
	            				'.$t['alamat'].'<br>
	            				'.$t['kota'].'<br>
							'.$t['kota'].'<br>
	            			Phone: '.$t['telp'].'
	          				</address>';
          				}
          				echo'
        			</div>
        			<div class="col-sm-4 invoice-col">
          				<b>Invoice #'.$r['nomor'].'</b><br>
          				<br>
          				<b>Payment Due: </b><span id="edit-duedate">'.date('d-m-Y',strtotime($r['due_date'])).'</span> <br>
          				<b>Tagihan ke bank: </b>'.$r['nama_bank']. '

        			</div>
				</div>    ';

				echo '
				<form action="https://cesco-logistics.com/e-cesco/reportsnew/airwaybill_per_inv_customer.php" target="_blank" id="printawb" method="POST"> </form>

      			<input type="hidden" name="id_kustomer" form="tagihkan_baru" value="'.$id_kustomer.'">
	  			<div class="row">
        			<div class="box-body">
						<table id="report" class="table table-striped table-bordered table-hover " width="100%" cellspacing="0" style="white-space:nowrap;">
						<thead>
							<tr>
									<th rowspan="2"><i class="fa fa-print"></i></th>
									<th colspan="4"><center>Data</center></th>
									<th colspan="6"><center>Pengirim</center></th>
									<th colspan="6"><center>Penerima</center></th>
									<th colspan="2">Berat</th>
									<th rowspan="2">Harga/kg</th>
									<th rowspan="2">Harga next kg</th>
									<th rowspan="2">min kg</th>
									<th rowspan="2">Add Charge</th>
									<th rowspan="2">Remarks</th>
									<th rowspan="2">Biaya Pengiriman</th>
									<th rowspan="2">Packing</th>
									<th rowspan="2">Asuransi</th>
									<th rowspan="2">Total</th>
									<th colspan="4" id="thCPI">Costing pickup intercity</th>
									<th colspan="4" id="thCDI">Costing Delivery Intercity</th>
									<th colspan="6" id="thCO">Costing Outbound</th>
									<th colspan="6" id="thCI">Costing Inbound</th>
									<th rowspan="2">Inv Agent PU</th>
									<th rowspan="2">Inv Agent Transit</th>
									<th rowspan="2">Inv Agent Deliv</th>
									<th rowspan="2" id="thTA">Total Inv Agent After Disc</th>
									<th rowspan="2">Berat Agent</th>
									<th rowspan="2">Total Costing</th>
									<th rowspan="2">Total Profit/loss</th>
								</tr>
								<tr>

									<th>Pick Up</th>
									<th>Airwaybill</th>
									<th>Komoditi</th>
									<th>Service Type</th>
									<th>Requestor</th>
									<th>Loc Code</th>
									<th>Pengirim</th>
									<th>Nama Perusahaan</th>
									<th>Kota Asal</th>
									<th width="800px;">Alamat Lengkap Asal</th>
									<th>Nama Perusahaan</th>
									<th>consignee</th>
									<th>kota tujuan</th>
									<th>alamat lengkap penerima</th>
									<th>nama penerima</th>
									<th>Tanggal</th>
									<th>Volume</th>
									<th>Actual</th>
									<th>Pickup</th>
									<th>Toll</th>
									<th>Parkir</th>
									<th>Menpower</th>
									<th>Delivery</th>
									<th>Toll</th>
									<th>Parkir</th>
									<th>Menpower</th>
									<th>Outbound</th>
									<th>toll</th>
									<th>SMU/TOTO</th>
									<th>Handling</th>
									<th>Parking</th>
									<th>menpower</th>
									<th>Inbound</th>
									<th>toll</th>
									<th>SMU/TOTO</th>
									<th>Handling</th>
									<th>Parking</th>
									<th>menpower</th>
								</tr>
						</thead>
						<tbody>';


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
                                berat_aktual) * (select harga_perkg)) + biaya_packing + biaya_asuransi) as jumlah_harga,
								confirm_agent_pu, confirm_agent_deliv
                        from (	SELECT
                        a.id_pengiriman,
                        date(pickup_date) as tanggal,
                        airwaybill_kode,
                        case
                            when a.komoditi = '1' then 'Paket'
                            else 'Dokumen'
                        end as komoditi,
                        a.komoditi as jenis_komoditi,
                        a.jenis_barang,
                        a.catatan,
						pel.nama_pelayanan as service,
                        nama_pengirim,
                        a.loc_code,
                        a.collie,
						cs.nama_kustomer as namakustomer,
                        nama_penerima,
                        case
                            when inbound = 'Y' then 'INBOUND'
                            when inbound = 'N' then 'OUTBOUND'
                            else ''
                        end as inbound,
                        id_kota_tujuan,
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

														case
												when a.service = '6' then x_namanegara(a.lokasi_tujuan)
												else ''
												end as negara,
												a.service as service_id,
						a.lokasi_tujuan as id_lokasi_tujuan,
                        alamat_penerima,
                        delivery_name,
                        delivery_date,
                        CASE
                            WHEN airwaybill_kode LIKE '001-%' THEN 0
                            WHEN airwaybill_kode LIKE '004-%' THEN 0
                            WHEN airwaybill_kode LIKE '002-%' THEN 1
                             WHEN airwaybill_kode LIKE '005-%' THEN 1
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
                        a.pembayaran_khusus,
                        a.id_kustomer,
                        a.heavy_cargo,
                        adc.aprc_list,
						adc.product_list,
						ifnull(a.biaya_packing_agent,0) as biaya_packing_agent,
						ifnull(a.biaya_tambahan_agent,0) as biaya_tambahan_agent,
						
						(select sum(md.kunci_total_agent_transit)
						from manifest_detail md
						join manifest m on m.id_manifest = md.id_manifest
						where md.id_pengiriman = a.id_pengiriman
						and md.kunci_total_agent_transit is not null
						
						) as kunci_total_agent_transit_sum,
						
						(select sum(md.biaya_tambahan_agent_transit)
						from manifest_detail md
						join manifest m on m.id_manifest = md.id_manifest
						where md.id_pengiriman = a.id_pengiriman
						and md.biaya_tambahan_agent_transit is not null
						
						) as biaya_tambahan_agent_transit_sum,

						ifnull( (select biaya_tambahan_agent_transit_sum) ,0) as biaya_tambahan_agent_transit,
                        (ifnull(a.kunci_total_agent,0) + ifnull(a.kunci_total_agent_deliv,0) + ifnull(a.kunci_total_agent_pickup,0) + ifnull( (select kunci_total_agent_transit_sum) ,0) ) as total_agent,
						ifnull(a.kunci_total_agent,0) as kunci_total_agent,
						ifnull(a.kunci_total_agent_deliv,0) as kunci_total_agent_deliv,
						ifnull(a.kunci_total_agent_pickup,0) as kunci_total_agent_pickup,
						ifnull( (select kunci_total_agent_transit_sum),0) as kunci_total_agent_transit,
						cs.kode_kustomer,
						case when terlambat_pickup in (1,2) and terlambat_delivery in (1,2) and pickup_by_agent != 39 and delivery_by_agent != 39 then
								ifnull(a.diskon_pickup,0)+ifnull(a.diskon_deliv,0)
							when terlambat_pickup in (1,2) and terlambat_delivery not in (1,2) and pickup_by_agent != 39 then
								ifnull(a.diskon_pickup,0)
							when terlambat_pickup not in (1,2) and terlambat_delivery in (1,2) and delivery_by_agent != 39 then
								ifnull(a.diskon_deliv,0)
						else 0
						end as diskon,
						case when diskon_deliv > 0 and delivery_by_agent IN (39, 11, 16) then								
							diskon_deliv
							else 0
						end as diskon_deliv_intercity,
						pickup_by_agent, delivery_by_agent, kunci_berat_agent,
						x_inter(id_kota_asal) as inter_asal, x_inter(id_kota_tujuan) as inter_tujuan,
						getCabangCesco(a.id_pengiriman) as jumlah_cabang,
						getAgentCesco(a.id_pengiriman) as jumlah_agent,
						getTagihInvAgent(a.id_pengiriman) as tagih_inv_agent,
						getRateCustomer2(a.id_kustomer, a.id_kecamatan_asal, a.lokasi_asal, a.lokasi_tujuan, a.id_kota_asal, a.id_kota_tujuan, a.id_kecamatan_tujuan, a.service, a.komoditi, a.loc_code) as rate_customer,
						(select count(1) from invoice_send
							where invoice_type='AGENT' and agent_date = date_format(ifnull(ifnull(tanggal_pickup_agent,tanggal_agent),a.tanggal),'%Y-%m') and agent_id = a.pickup_by_agent
							and confirm_dtm is not null
						) as confirm_agent_pu,
						
						

						(select count(1) from invoice_send
							where invoice_type='AGENT' and agent_date = date_format(ifnull(ifnull(tanggal_deliv_agent,tanggal_agent),a.tanggal),'%Y-%m') and agent_id = a.delivery_by_agent
							and confirm_dtm is not null
						) as confirm_agent_deliv,
						(select count(1) from invoice_send i
							join manifest m on m.manifest_from = i.agent_id
							where invoice_type='AGENT' and agent_date = date_format(a.tanggal,'%Y-%m')
							and m.id_manifest IN (select id_manifest from manifest_detail where id_pengiriman = a.id_pengiriman)
							and confirm_dtm is not null
						) as confirm_agent_transit,
						(select count(1) from invoice_send
							where invoice_type='AGENT' and agent_date = date_format(a.tanggal,'%Y-%m') and agent_id = a.delivery_by_agent
							and agent_id = a.pickup_by_agent
							and confirm_dtm is not null
						) as confirm_agent_intra
                    FROM
                        pengiriman a
						
						left join kustomer cs on cs.id_kustomer = a.id_kustomer
						left join pelayanan pel on pel.id_pelayanan = a.service
                        LEFT JOIN negara f ON f.id_negara = a.lokasi_tujuan and a.service = 6 and a.biaya_beban != 4
                        LEFT JOIN (
						    SELECT 
						        id_pengiriman,
						        GROUP_CONCAT(aprc SEPARATOR '<br>') AS aprc_list,
						        GROUP_CONCAT(product SEPARATOR '<br>') AS product_list
						    FROM asus_detail_catatan
						    GROUP BY id_pengiriman
						) adc ON adc.id_pengiriman = a.id_pengiriman
                    where
                        a.id_invoice = $id and a.status != 7
                    group by a.id_pengiriman
                  ) x";

				  		$sql = $mysqli->query($cari);
						$row_count= $sql->num_rows;

						$sum_total=0;
						$sum_packing =0;
						$sum_asuransi=0;
                        $key = "asdf$1011";
                        $kunciForm = "";
						$kunci = '';

						$cek002 = 0;
						$cekOut = 0;
						$cekIn = 0;
						$cekCross = 0;
						$cekHarga = 0;
						$totalPL = 0;
						$totalRevenue = 0;

						$countCostOut = 0;
						$countCostIn = 0;
						$countCostDeliv = 0;
						$countCostPick = 0;
						$countCostAgent = 0;
						$count_profit_loss = 0;

						while($row = $sql->fetch_assoc()){
							$kunci = $row['harga_kunci'];

                            $kunciForm .= "<input type=\"hidden\" name=\"harga_pengiriman[".$row['id_pengiriman']."]\" form=\"kunciform\" value=\"".$row['harga_lock']."\">\n";

							$sum_packing = $sum_packing+$row['biaya_packing'];
							$sum_asuransi = $sum_asuransi+$row['biaya_asuransi'];

							$stringawal = $row["id_pengiriman"];
							$id = base64_encrypt($stringawal,$key);
							//$volume = ($row['length']*$row['width']*$row['height'])/6000;

                            $volume = ($row['kode_kustomer'] == 'CIS' || $row['kode_kustomer'] == 'DEL') ? round($row['volume'],2) : ceil($row['volume']);
                            $harga_lock = ($row['kode_kustomer'] == 'CIS' || $row['kode_kustomer'] == 'DEL') ? round($row['harga_lock'],2) : round($row['harga_lock']);

							if ($row['harga_lock'] == 0 || $row['harga_lock'] == ''){
								$cekHarga++;
							}

                            $sum_total = $sum_total+$harga_lock;
                            $harga_total = $harga_lock+$row['biaya_packing']+$row['biaya_asuransi'];

                            if($row['lokasi_asal']=='Jakarta Selatan' || $row['lokasi_asal']=='Jakarta Timur' || $row['lokasi_asal']=='Jakarta Barat' ||		$row['lokasi_asal']=='Jakarta Utara' || $row['lokasi_asal']=='Jakarta Pusat'){
								$lokasi_asal = 'Jakarta';
							}else{
								$lokasi_asal = $row['lokasi_asal'];
							}

							if($row['lokasi_tujuan']=='Jakarta Selatan' || $row['lokasi_tujuan']=='Jakarta Timur' || $row['lokasi_tujuan']=='Jakarta Barat' ||		$row['lokasi_tujuan']=='Jakarta Utara' || $row['lokasi_tujuan']=='Jakarta Pusat'){
								$lokasi_tujuan = 'Jakarta';
							}else{
								$lokasi_tujuan = $row['lokasi_tujuan'];
							}


                            $noRate = ($row['harga_perkg'] > 0) ? '' : '<button type="button" class="btn btn-xs btn-success" title="Belum Ada Rate"><i class="fa fa-exclamation-triangle"></i> </button>';

                          
                            //$sum_total = $sum_total + $totalAgentAfterDiskon;


							$cekCostOut = "";
							$cekCostIn = "";
							$cekCostDeliv = "";
							$cekCostPick = "";
							$cekCostAgent = "";
							$alert = 'onclick="alert(\'Mandatory for AWB: '.$row['airwaybill_kode'].'\')"';
							$btnAlert = '<button type="button" '.$alert.' class="btn btn-xs btn-danger" title="Mandatory for AWB: '.$row['airwaybill_kode'].'"><i class="fa fa-exclamation-circle"></i> </button>';

							$totalAgent = 0;
							$totalAgenPickup = ($row['confirm_agent_pu'] == 0) ? 0 : $row['kunci_total_agent_pickup'];
							$totalAgentDeliv = ($row['confirm_agent_deliv'] == 0) ? 0 : $row['kunci_total_agent_deliv'];
							$totalAgentTransit = ($row['confirm_agent_transit'] == 0) ? 0 : $row['kunci_total_agent_transit'];
							$totalAgentIntra = ($row['confirm_agent_intra'] == 0) ? 0 : $row['kunci_total_agent'];

							if ($row['intercity'] == 0){ //001

								if ($row['pickup_by_agent'] == $row['delivery_by_agent']){

									$totalAgent = $totalAgentIntra;
									$totalAgentDeliv = $totalAgentIntra;

								}else{

									if ($row['inter_asal'] != 'Y' && $row['inter_tujuan'] != 'Y'){ //CROSS CITY

										$totalAgent = $totalAgenPickup + $totalAgentDeliv + $totalAgentTransit;

										if ($row['jumlah_cabang'] > 0 && $row['jumlah_agent'] == 0){

											/*
											if ($totalAgentAfterDiskon == 0){
												$cekCross++;
												$cekCostAgent = $btnAlert;
											}*/

											if ($row['costing_delivery_fee'] == 0){
												$cekCross++;
												$cekCostDeliv = $btnAlert;

												$countCostDeliv++;

											}

										}else{
											if ($totalAgent == 0 && $row['service_id'] != 4 && $row['tagih_inv_agent'] == 1){
												$cekCross++;
												$cekCostAgent = $btnAlert;

												$countCostAgent++;
											}
										}

										/*
										if ($row['costing_outbound_fee'] == 0){
											$cekCross++;
											$cekCostOut = '<button type="button" '.$alert.' class="btn btn-xs btn-danger" title="Mandatory. AWB: '.$row['airwaybill_kode'].'"><i class="fa fa-exclamation-circle"></i> </button>';
										}
										if ($row['costing_inbound_fee'] == 0){
											$cekCross++;
											$cekCostIn = '<button type="button" '.$alert.' class="btn btn-xs btn-danger" title="Mandatory. AWB: '.$row['airwaybill_kode'].'"><i class="fa fa-exclamation-circle"></i> </button>';
										}
										if ($totalAgentAfterDiskon == 0) {
											$cekCross++;
											$cekCostAgent = '<button type="button" '.$alert.' class="btn btn-xs btn-danger" title="Mandatory. AWB: '.$row['airwaybill_kode'].'"><i class="fa fa-exclamation-circle"></i> </button>';
										}
										*/

									}else if ($row['inter_asal'] != 'Y' && $row['inter_tujuan'] == 'Y'){	//INBOUND

										$totalAgent = $totalAgenPickup + $totalAgentTransit;

										/*
										if ($row['costing_delivery_fee'] == 0) {
											$cekIn++;
											$cekCostDeliv = $btnAlert;
										}*/
										if ($row['costing_inbound_fee'] == 0) {
											$cekIn++;
											$cekCostIn = $btnAlert;
											$countCostIn++;
										}
										/*
										if ($totalAgentAfterDiskon == 0) {
											$cekIn++;
											$cekCostAgent = $btnAlert;
										}*/


										if ($row['jumlah_cabang'] > 0 && $row['jumlah_agent'] == 0){
											if ($row['costing_delivery_fee'] == 0) {
												$cekIn++;
												$cekCostDeliv = $btnAlert;
											}

										}else{
											if ($totalAgent == 0 && $row['service_id'] != 4 && $row['tagih_inv_agent'] == 1){
												$cekIn++;
												$cekCostAgent = $btnAlert;

												$countCostAgent++;
											}
										}


									}else if ($row['inter_asal'] == 'Y' && $row['inter_tujuan'] != 'Y'){ //OUTBOUND

										$totalAgent = $totalAgentDeliv + $totalAgentTransit;

										if ($row['costing_pickup_fee'] == 0){
											$cekOut++;
											$cekCostPick = $btnAlert;
											$countCostPick++;
										}
										if ($row['costing_outbound_fee'] == 0){
											$cekOut++;
											$cekCostOut = $btnAlert;
											$countCostOut++;
										}
										//permasalahan
										if ($row['jumlah_cabang'] > 0){
											/*if ($row['costing_delivery_fee'] == 0) {
												$cekOut++;
												$cekCostDeliv = $btnAlert;
											}*/

										}else{
											if ($totalAgent == 0 && $row['service_id'] != 4  && $row['tagih_inv_agent'] == 1){
												$cekOut++;
												$cekCostAgent = $btnAlert;

												$countCostAgent++;
											}
										}
									}
								}

							}else{ //002

								$totalAgent = $totalAgenPickup + $totalAgentDeliv + $totalAgentTransit;

								if ($row['costing_pickup_fee'] == 0){
									$cek002++;
									$cekCostPick = $btnAlert;
									$countCostPick++;
								}
								if ($row['costing_delivery_fee'] == 0){
									$cek002++;
									$cekCostDeliv = $btnAlert;
								}
							}

							if ($row['confirm_agent_pu'] == 0 && $row['confirm_agent_deliv'] == 0 && $row['confirm_agent_transit'] == 0 && $row['confirm_agent_intra'] == 0){
								$new_total_diskon = 0;
								$total_agent = 0;
								$diskon = 0;
								$totalAgentAfterDiskon = 0;
							}else{
								$new_total_diskon = $totalAgent - ($row['biaya_packing_agent']+$row['biaya_tambahan_agent']+$row['biaya_tambahan_agent_transit']);
								$total_agent = $new_total_diskon;
								$diskon = $row['diskon'];
								$totalAgentAfterDiskon = ($total_agent-(($diskon/100)*$total_agent))+($row['biaya_packing_agent']+$row['biaya_tambahan_agent']+$row['biaya_tambahan_agent_transit']);
							}
							$delivery_fee_after_discount = $row['costing_delivery_fee'];

							
							if (isset($row['diskon_deliv_intercity']) && $row['diskon_deliv_intercity'] > 0) {
							
							    $discount_amount = $row['costing_delivery_fee'] * ($row['diskon_deliv_intercity'] / 100);
							    $delivery_fee_after_discount = $row['costing_delivery_fee'] - $discount_amount;
							}

							$total_costing = $row['costing_pickup_fee']+
											 $row['costing_pickup_parking']+
											 $row['costing_pickup_toll']+
											 $row['costing_men_power']+
											 $delivery_fee_after_discount+
											 $row['costing_delivery_parking']+
											 $row['costing_delivery_toll']+
											 $row['costing_delivery_menpower']+
											 $row['costing_outbound_fee']+
											 $row['costing_outbound_toll']+
											 $row['costing_outbound_smu']+
											 $row['costing_outbound_handling']+
											 $row['costing_outbound_parking']+
											 $row['costing_outbound_handling']+
											 $row['costing_outbound_menpower']+
											 $row['costing_inbound_fee']+
											 $row['costing_inbound_toll']+
											 $row['costing_inbound_smu']+
											 $row['costing_inbound_handling']+
											 $row['costing_inbound_parking']+
											 $row['costing_inbound_menpower']+
											 $row['total_agent'];
							$profit_loss = $harga_total-$total_costing;


							if($profit_loss < 0){
								$count_profit_loss = $count_profit_loss+1;
							}


							
							//$disableTagihkan = (($cekCostOut == "" && $cekCostIn == "" && $cekCostDeliv == "" && $cekCostPick == "" && $cekCostAgent == "") || $u['level'] == 'admin') ? '' : ' disabled ';
							$disableTagihkan = '';

							$lokasiAsalShow = ($row['id_kecamatan_asal']>0 ? strtoupper($row['kec_asal']).', ' : '').strtoupper($lokasi_asal);
							$lokasiTujuanShow = ($row['id_kecamatan_tujuan']>0 ? strtoupper($row['kec_tujuan']).', ' : '').strtoupper($lokasi_tujuan);

							$totalPL = $totalPL+$profit_loss;
							$totalRevenue = $totalRevenue+$harga_total;

							echo'
							<tr>
								<td><input type="checkbox" form="printawb" class="printawb" name="idprintawb[]" value="'.$row['id_pengiriman'].'"></td>
								<td width="20px">'.$row['tanggal'].'</td>
								<td width="20px">'.$row['airwaybill_kode'].'</td>
								<td width="20px">'.($row['jenis_barang'] == "" ? $row['komoditi'] : $row['komoditi'] ).', '.  strtoupper($row['jenis_barang']).', '.strtoupper($row['catatan']).'</td>
								<td width="20px">'.strtoupper($row['service']).'</td>
								<td width="20px">'.strtoupper($row['namakustomer']).'</td>
								<td width="20px">'.strtoupper($row['loc_code']).'</td>
								<td width="20px">'.strtoupper($row['nama_pengirim']).'</td>
								<td width="20px">'.strtoupper($row['perusahaan_pengirim']).'</td>
								<td width="20px">'.($row['id_kecamatan_asal']>0 ? strtoupper($row['kec_asal']).', ' : '').strtoupper($lokasi_asal).'</td>
								<td width="50px">'.strtoupper($row['alamat_pengirim']).'</td>
								<td width="20px">'.strtoupper($row['perusahaan_penerima']).'</td>
								<td width="20px">'.strtoupper($row['nama_penerima']).'</td>
								<td width="20px">'.($row['id_kecamatan_tujuan']>0 ? strtoupper($row['kec_tujuan']).', ' : '').strtoupper($lokasi_tujuan).'</td>
								<td width="20px">'.strtoupper($row['alamat_penerima']).'</td>
								<td width="20px">'.strtoupper($row['delivery_name']).'</td>
								<td width="20px">'.strtoupper($row['delivery_date']).'</td>
								<td width="20px">'.$row['length'].'x'.$row['width'].'x'.$row['height'].'= '.$volume.'</td>
								<td width="20px">'.$row['berat_real'].'</td>
								';
								if($row['harga_pengiriman'] != ''){
									echo '<td>-</td>';
								}else{
									echo'<td><span class="harga_perkg'.$row['id_rate_customer'].'">'.number_format($row['harga_perkg']).' '.$noRate.'</span>';

									echo '</td>';

								}

								$nextKg = ($row['next_kg']>0) ? number_format($row['next_kg']) : '';
                                echo'<td><span class="harga_nextkg'.$row['id_rate_customer'].'">'.$nextKg.'</span></td>';

								if($row['harga_pengiriman'] != ''){
									echo '<td>-</td>';
								}else{
									echo '<td>'.$row['min_kg'].'</td>';
								}
								echo '
								<td align="right" align="right">'.number_format($row['biaya_tambahan']).'</td>
								<td>'.$row['keterangan_biaya'].'</td>
								<td>'
										.number_format($harga_lock).

										'
										';

								echo '
								</td>

								<td align="right" align="right">'.strtoupper($row['packing_kayu']).' </td>
								<td align="right" align="right">'.$row['asuransi'].' </td>

								<td>'
										.number_format(round($harga_total)).

										'
										';

								echo '
								</td>
								<td>'.number_format(round((float)$row['costing_pickup_fee'])).' '.$cekCostPick. '</td>
								<td>'.number_format(round((float)$row['costing_pickup_toll'])). '</td>
								<td>'.number_format(round((float)$row['costing_pickup_parking'])). '</td>	
								<td>'.number_format(round((float)$row['costing_men_power'])). '</td>
								<td>'.number_format(round((float)$delivery_fee_after_discount)).' '.$cekCostDeliv. '</td>
								<td>'.number_format(round((float)$row['costing_delivery_toll'])). '</td>
								<td>'.number_format(round((float)$row['costing_delivery_parking'])). '</td>
								<td>'.number_format(round((float)$row['costing_delivery_menpower'])). '</td>
								<td>'.number_format(round((float)$row['costing_outbound_fee'])).' '.$cekCostOut. '</td>
								<td>'.number_format(round((float)$row['costing_outbound_toll'])). '</td>
								<td>'.number_format(round((float)$row['costing_outbound_smu'])). '</td>
								<td>'.number_format(round((float)$row['costing_outbound_handling'])). '</td>
								<td>'.number_format(round((float)$row['costing_outbound_parking'])). '</td>
								<td>'.number_format(round((float)$row['costing_outbound_menpower'])). '</td>
								<td>'.number_format(round((float)$row['costing_inbound_fee'])).' '.$cekCostIn. '</td>
								<td>'.number_format(round((float)$row['costing_inbound_toll'])). '</td>
								<td>'.number_format(round((float)$row['costing_inbound_smu'])). '</td>
								<td>'.number_format(round((float)$row['costing_inbound_handling'])). '</td>
								<td>'.number_format(round((float)$row['costing_inbound_parking'])). '</td>
								<td>'.number_format(round((float)$row['costing_inbound_menpower'])). '</td>
								<td>'.number_format(round($totalAgenPickup)).'</td>
								<td>'.number_format(round($totalAgentTransit)).'</td>
								<td>'.number_format(round($totalAgentDeliv)).'</td>
								<td>'.number_format(round($totalAgentAfterDiskon)).' ('.$cekCostAgent. $diskon.'%)</td>
								<td>'.number_format(round($row['kunci_berat_agent'])).'</td>
								<td>'.number_format(round($total_costing)).'</td>
								<td>'.number_format(round($profit_loss)).'</td>
							</tr>';
						}
						echo'
						</tbody>
					</table>

					</div>
				</div>
				<script>
				var countCostOut = '.$countCostOut.';
				var countCostIn = '.$countCostIn.';
				var countCostDeliv = '.$countCostDeliv.';
				var countCostPick = '.$countCostPick.';
				var countCostAgent = '.$countCostAgent.';
				</script>
				<div class="row">
					<div class="col-xs-6">
						<a href="#" id="checkallProfitLoss">Check All</a>

						<input class="btn btn-success" type="submit" form="printawb" value="Print Selected">
						';

						echo ' <a href="reports/PHPExcel/excel_profit-loss.php?id_kustomer='.$id_kustomer.'&idinvoice='.$idInv.'" target="_blank" class="btn btn-primary"><i class="fa fa-print"></i> Print Excel</a>

							
						';

						if ($cek002 > 0  || $cekOut > 0 || $cekIn > 0 || $cekCross > 0 || $count_profit_loss > 0){
							echo ' <a href="reports/PHPExcel/invoice_lampiran.php?stat=costing&idinvoice='.$idInv.'" target="_blank" class="btn btn-success"><i class="fa fa-file-excel-o"></i> Cek kosting</a>';
						}

						

						echo '
					</div>

					<div class="col-xs-6">

					</div>
					<div class="col-xs-6">
						<!--<p class="lead">Amount Due</p>-->
						<div class="table-responsive">
							<table class="table tablebawah">
								<tbody>

								<tr>
									<th>Total Revenue</th>
									<td style="text-align:right;">Rp '.($r['subtotal'] > 0  ? number_format($r['subtotal']) : number_format($totalRevenue)).'</td>

								</tr>
								<tr>
									<th>Total PL</th>
									<td style="text-align:right;">Rp '.number_format($totalPL).'</td>
								</tr>
							</tbody></table>
						</div>
					</div>


				</div>

    		</section>
    		<div class="clearfix"></div>


			';

			break;

			case "update2":

			$key = "asdf$1011";
			$encrypt = "$_GET[id]";
			$id = base64_decrypt($encrypt,$key);

			$query = mysqli_query($con, "select * from user_client where id_user='$id'");
			$r = mysqli_fetch_assoc($query);

			echo'
			<section class="content">
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">Detail User Client</h3>
						<div class="box-tools pull-right">
							<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
							<button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
						</div>
					</div><!-- /.box-header -->
					<form class="form-horizontal" id="form-user" method="post" action="'.$aksi.'?module=users&act=update2" autocomplete="off">
					<input type="hidden" name="id" value="'.$r['id_user'].'">
					<div class="box-body">
						<div class="form-group">
							<label class="col-md-3 control-label">Username <span class="required">* </span></label>
							<div class="col-md-6">
								<input type="text" name="username" placeholder="Username..." class="form-control" value="'.$r['username'].'" disabled>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label">Name <span class="required">* </span></label>
							<div class="col-md-6">
								<input type="text" name="namalengkap" placeholder="Name..." class="form-control" value="'.$r['fullname'].'" disabled>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label">Email <span class="required">* </span></label>
							<div class="col-md-6">
								<input type="text" name="email" placeholder="Email..." class="form-control" value="'.$r['email'].'" disabled>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label">Company <span class="required">* </span></label>
							<div class="col-md-6">
								<select name="id_kustomer" data-placeholder="Choose a customer..." class="form-control select2">
									<option value="">Select...</option>';
									$cabang = mysqli_query($con, "select id_kustomer,nama_kustomer from kustomer order by nama_kustomer");
									while($c=mysqli_fetch_assoc($cabang)){
										if($r['id_kustomer']==$c['id_kustomer']){
											echo'
											<option value="'.$c['id_kustomer'].'" selected>'.$c['nama_kustomer'].'</option>';
										}else{
											echo'
											<option value="'.$c['id_kustomer'].'">'.$c['nama_kustomer'].'</option>';
										}
									}
									echo'
								</select>
								<div class="val-message"></div>
							</div>
						</div>';
						if($r['blocked']=='Y'){
							echo'
							<div class="form-group">
								<label class="col-md-3 control-label"></label>
								<div class="col-md-6">
									<label><input type="checkbox" class="minimal" name="blocked" value="N"> Unblocked</label>
								</div>
							</div>';
						}
						echo'
					</div><!-- /.box-body -->
					<div class="box-footer">
						<button type="button" class="btn btn-default" onclick="window.location.href = \'?module=tarif-regular\';">Cancel</button>
						<button type="submit" class="btn btn-primary pull-right">Save</button>
					</div><!-- /.box-footer -->
					</form>
				</div><!-- /.box -->
			</section>';

			break;

			case "report-per-customer":
			?>

				<form class="form-horizontal" method="GET" target="_blank" action="reports/PHPExcel/excel_report_profitloss_percustomer.php" autocomplete="off">
					<div class="box box-primary">
						<div class="box-header with-border">
							<h3 class="box-title">Profit Loss per Customer</h3>
							<div class="box-tools pull-right">
								<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
								<button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
							</div>
						</div><!-- /.box-header -->
						<div class="box-body">
							<div class="row">
								<div class="col-md-4">
									<div class="form-group">
										<label class="col-sm-5 control-label full-right">Tahun</label>
										<div class="col-sm-7">
											<div class="input-group">
												<div class="input-group-addon">
													<i class="fa fa-calendar"></i>
												</div>
												<select name="bulan" class="form-control select2">
													<?php
													    $month = !empty( $_GET['month'] ) ? $_GET['month'] : 0;
									                    for ($i = 0; $i <= 12; ++$i) {
									                        $time = strtotime(sprintf('+%d months', $i));
									                        $label = date('F ', $time);
									                        $value = date('m', $time);

									                        $selected = ( $value==$month ) ? ' selected=true' : '';

									                        printf('<option value="%s"%s>%s</option>', $value, $selected, $label );
									                    }
													?>
													
												</select> 
											</div>
										</div>
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<label class="col-sm-5 control-label full-right">Tahun</label>
										<div class="col-sm-7">
											<div class="input-group">
												<div class="input-group-addon">
													<i class="fa fa-calendar"></i>
												</div>
												<select name="tahun" class="form-control select2">
													
														<?php
														
															for($i = date("Y"); $i >=date("Y")-5; $i--){
															    echo '<option value="' . $i . '">' . $i . '</option>' ;
															}
															

														?>
													
												</select> 
											</div>
										</div>
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<label class="col-sm-3 control-label full-right">Customer</label>
										<div class="col-sm-8">
											<select name="id_kustomer" class="form-control select2">
											<?php
											if($u['id_kustomer']> 0){
												$query=mysqli_query($con,'select id_kustomer, nama_kustomer from kustomer  order by nama_kustomer');
												
											}else{
												$query=mysqli_query($con,'select id_kustomer, nama_kustomer from kustomer order by nama_kustomer');	
											}
											while($r=mysqli_fetch_assoc($query)){
												echo'
												<option value="'.$r['id_kustomer'].'">'.$r['nama_kustomer'].'</option>';
											}

											?>
											</select>
										</div>
									</div>
								</div>
							</div><!-- /.row -->
						</div><!-- /.box-body -->
				        <div class="box-footer">
							<button type="submit" class="btn btn-primary pull-right"> <i class="fa fa-search"></i> Excel</button>
						</div>
					</div><!-- /.box -->
					</form>

			<?php

			break;

		}
	}else{
		echo akses_salah();
	}
}
?>
