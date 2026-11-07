<section class="content-header">
	<h1>Reports<small>Invoice Customer</small></h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-home"></i> Home</a></li>
		<li><a href="#">Reports</a></li>
		<li><a href="#">Invoice</a></li>
		<li class="active">Customer</li>
	</ol>
</section>
<?php
if(!isset($_SESSION)){
    session_start();
}

if (empty($_SESSION['usernamex']) AND empty($_SESSION['passwordx'])){
    header('location:../main.php?module=home');
}
else
{

		$aksi	= "module/mod_invoice-customer/invoice-customer_action.php";
		$act	= !empty($_GET['act']) ? $_GET['act'] : '';
		
		switch($act)
		{
			
			default:
			echo'
			<section class="content">';
				echo'
				<div class="row">
					<div class="col-md-12">
						<div class="box box-primary">
							<div class="box-body">
								<table id="InvoiceCustomer" class="table table-bordered table-striped table-hover" width="100%" cellspacing="0" style="white-space:nowrap;">
									<thead>
										<tr>
											<th>Nomor</th>
											<th>Tanggal</th>
											<th>metode</th>
											<th>Customer</th>
											<th>Tanggal Airwaybill</th>											
											<th width="50">Loc Code</th>
											<th width="50"><center>Detail</center></th>
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
	            				'.$t['nama_kota'].'<br>
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
				
				if($nomor == 'DRAFT'){
					echo '<a href="#" class="btn btn-primary" id="inv_open_add_awb" data-id="'.$id.'"><i class="fa fa-plus"></i> TAMBAH AWB</a>';
                    echo '&nbsp;<a href="#awb_not_billed" data-target="#awb_not_billed" data-toggle="modal" class="btn btn-primary" id="inv_open_awb_not_billed" data-id="'.$id.'"><i class="fa fa-search"></i> AWB Belum Tertagih</a>';
				}
				echo '
				<form action="'.$aksi.'?module=invoice-customer&act=kunci-harga" id="kunciform" method="POST"></form>  			
      			<form action="'.$aksi.'?module=invoice-customer&act=keluarkanawb" id="keluarkanawb" method="POST"> </form>
      			<form action="'.$aksi.'?module=invoice-customer&act=tagihkan-baru" id="tagihkan_baru" method="POST"> </form>
      			<input type="hidden" name="id_kustomer" form="tagihkan_baru" value="'.$id_kustomer.'">
	  			<div class="row">
        			<div class="box-body">
						<table id="report" class="table table-striped table-bordered table-hover " width="100%" cellspacing="0" style="white-space:nowrap;">
						<thead>
							<tr>
									<th rowspan="2">#</th>
									<th colspan="4"><center>Data</center></th>		
									<th colspan="6"><center>Pengirim</center></th>
									<th colspan="6"><center>Penerima</center></th>												
									<th colspan="2">Berat</th>
									<th rowspan="2">Harga/kg</th>
									<th rowspan="2">min kg</th>									
									<th rowspan="2">Add Charge</th>
									<th rowspan="2">Remarks</th>
									<th rowspan="2">Biaya Pengiriman</th>
									<th rowspan="2">Packing</th>
									<th rowspan="2">Asuransi</th>
									
									<th rowspan="2">Total</th>
									
									<th colspan="4">Costing pickup intercity</th>
									<th colspan="4">Costing Delivery Intercity</th>
									<th colspan="6">Costing Outbound</th>
									<th colspan="6">Costing Inbound</th>
									<th rowspan="2">Invoice Agent</th>
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
						/*if ($r['loc_code'] != ''  and $r['loc_code'] != 'all'){
							$qloc = 'and a.loc_code ="'.$r['loc_code'].'"';
						}						
						else{
							$qloc = '';
						}*/



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
                        ((select harga_perkg) * (select berat_finish)) + biaya_tambahan as total_new,
                        ifnull(harga_pengiriman, (select total_new)) as total_fix,
                        ifnull(harga_kunci, (select total_fix)) as harga_lock,	
                        if(if(berat_aktual is null or berat_aktual < 1,
                                berat_barang,
                                berat_aktual) < substring_index (substring_index(rate_customer,'~',3), '~', -1),
                            (substring_index (substring_index(rate_customer,'~',3), '~', -1) * substring_index (substring_index(rate_customer,'~',2), '~', -1)) + biaya_packing + biaya_tambahan + biaya_asuransi,
                            (if(berat_aktual is null or berat_aktual < 1,
                                berat_barang,
                                berat_aktual) * substring_index (substring_index(rate_customer,'~',2), '~', -1)) + biaya_packing + biaya_tambahan + biaya_asuransi) as jumlah_harga                       					
                        from (	SELECT 
                        id_pengiriman,
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
                        if((select intercity) = 1,
                            ceiling((select length) * (select width) * (select height) / 4000),
                            if(a.service = 5,
                                ceiling((select length) * (select width) * (select height) / 4000),
                                ceiling((select length) * (select width) * (select height) / 6000))) as volume,
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
                                       0 as total_agent,
						getRateCustomer2(a.id_kustomer, a.id_kecamatan_asal, a.lokasi_asal, a.lokasi_tujuan, a.id_kota_asal, a.id_kota_tujuan, a.id_kecamatan_tujuan, a.service, a.komoditi, a.loc_code) as rate_customer						
                    FROM
                        pengiriman a
						left join kustomer cs on cs.id_kustomer = a.id_kustomer
						left join pelayanan pel on pel.id_pelayanan = a.service
                            LEFT JOIN
                        negara f ON f.id_negara = a.lokasi_tujuan
                            and a.service = 6  
                    where
                        a.id_invoice = $id and a.status != 7 
                    group by a.id_pengiriman order by pickup_date
                  ) x";


						$sql = $mysqli->query($cari);
						$row_count= $sql->num_rows;

						$sum_total=0;
						$sum_packing =0;
						$sum_asuransi=0;
                        $key = "asdf$1011";
                        $no = 0;
						while($row = $sql->fetch_assoc()){
                            $no++;
							$sum_total = $sum_total+$row['harga_lock'];
							$sum_packing = $sum_packing+$row['biaya_packing'];
							$sum_asuransi = $sum_asuransi+$row['biaya_asuransi'];
							$harga_total = $row['harga_lock']+$row['biaya_packing']+$row['biaya_asuransi'];
							$stringawal = $row["id_pengiriman"];
							$id = base64_encrypt($stringawal,$key);
							$volume = ($row['length']*$row['width']*$row['height'])/6000;
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
							$total_costing = $row['costing_pickup_fee']+$row['costing_pickup_parking']+$row['costing_pickup_toll']+$row['costing_men_power']+$row['costing_delivery_fee']+$row['costing_delivery_parking']+$row['costing_delivery_toll']+$row['costing_delivery_menpower']+$row['costing_outbound_fee']+$row['costing_outbound_toll']+$row['costing_outbound_smu']+$row['costing_outbound_handling']+$row['costing_outbound_parking']+$row['costing_outbound_handling']+$row['costing_outbound_menpower']+$row['costing_inbound_fee']+$row['costing_inbound_toll']+$row['costing_inbound_smu']+$row['costing_inbound_handling']+$row['costing_inbound_parking']+$row['costing_inbound_menpower']+$row['total_agent'];
							$profit_loss = $row['harga_lock']-$total_costing;
							echo'
							<tr>
								<td width="20px"> '.$no.' </td>
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
								<td width="20px">'.$row['length'].'x'.$row['width'].'x'.$row['height'].'= '.$row['volume'].'</td>
								<td width="20px">'.$row['berat_real'].'</td>
								'; 
								if($row['harga_pengiriman'] != ''){
									echo '<td>-</td>';
								}else{
									echo'<td><span class="harga_perkg'.$row['id_rate_customer'].'">'.$row['harga_perkg'].'</span></td>';
								}

								
								if($row['harga_pengiriman'] != ''){
									echo '<td>-</td>';
								}else{
									echo '<td>'.$row['min_kg'].'</td>';
								}
								echo '
								<td align="right" align="right">'.number_format($row['biaya_tambahan']).'</td>
								<td>'.number_format($row['keterangan_biaya']).'</td>
								<td>'
										.number_format(round($row['harga_lock'])).

										' 
										';
										//if($nomor == 'DRAFT'){
											echo '
											'. ($row['harga_kunci'] != '' ? '*' : ''). ' 
											';
										//}

								echo '
								</td>
								
								<td align="right" align="right">'.strtoupper($row['packing_kayu']).' '.number_format($row['biaya_packing']).'</td>
								<td align="right" align="right">'.$row['asuransi'].' '.number_format($row['biaya_asuransi']).'</td>
								
								<td>'
										.number_format(round($harga_total)).

										' 
										';
										//if($nomor == 'DRAFT'){
											echo '
											'. ($row['harga_kunci'] != '' ? '*' : ''). ' 
											';
										//}

								echo '
								</td>
								<td>'.number_format(round($row['costing_pickup_fee'])). '</td>	
								<td>'.number_format(round($row['costing_pickup_parking'])). '</td>	
								<td>'.number_format(round($row['costing_pickup_toll'])). '</td>	
								<td>'.number_format(round($row['costing_men_power'])). '</td>
								<td>'.number_format(round($row['costing_delivery_fee'])). '</td>	
								<td>'.number_format(round($row['costing_delivery_parking'])). '</td>	
								<td>'.number_format(round($row['costing_delivery_toll'])). '</td>	
								<td>'.number_format(round($row['costing_delivery_menpower'])). '</td>
								<td>'.number_format(round($row['costing_outbound_fee'])). '</td>	
								<td>'.number_format(round($row['costing_outbound_toll'])). '</td>	
								<td>'.number_format(round($row['costing_outbound_smu'])). '</td>	
								<td>'.number_format(round($row['costing_outbound_handling'])). '</td>
								<td>'.number_format(round($row['costing_outbound_parking'])). '</td>	
								<td>'.number_format(round($row['costing_outbound_menpower'])). '</td>
								<td>'.number_format(round($row['costing_inbound_fee'])). '</td>	
								<td>'.number_format(round($row['costing_inbound_toll'])). '</td>	
								<td>'.number_format(round($row['costing_inbound_smu'])). '</td>	
								<td>'.number_format(round($row['costing_inbound_handling'])). '</td>
								<td>'.number_format(round($row['costing_inbound_parking'])). '</td>	
								<td>'.number_format(round($row['costing_inbound_menpower'])). '</td>
								<td>'.number_format(round($row['total_agent'])).'</td>
								<td>'.number_format(round($total_costing)).'</td>
								<td>'.number_format(round($profit_loss)).'</td>
							</tr>';
						}
						echo'
						</tbody>
					</table>
					</div>
				</div>
			
				<div class="row">
					<div class="col-xs-6">
        			
						<!--
          				<p class="lead">Payment Methods:</p>
						<p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
						</p>
						-->
					</div>
					<div class="col-xs-6">
						<!--<p class="lead">Amount Due</p>-->
						<div class="table-responsive">';
						/*$query_total = "SELECT									
									
									sum(if(b.next_kg is not null or c.next_kg is not null,

										if(if(berat_aktual is null or berat_aktual < 1,  berat_barang, berat_aktual) <= ifnull(b.min_kg,c.min_kg), (ifnull(b.min_kg,c.min_kg)*ifnull(b.harga,c.harga))+a.biaya_packing+a.biaya_tambahan+a.biaya_asuransi , 
											ifnull(b.harga,c.harga)*ifnull(b.min_kg,c.min_kg)+((if(berat_aktual is null or berat_aktual < 1,  berat_barang, berat_aktual)-ifnull(b.min_kg,c.min_kg))*ifnull(b.next_kg,c.next_kg))

											),

									if(if(berat_aktual is null or berat_aktual < 1,  berat_barang, berat_aktual) < ifnull(b.min_kg,c.min_kg), (ifnull(b.min_kg,c.min_kg)*ifnull(b.harga,c.harga))+a.biaya_packing+a.biaya_tambahan+a.biaya_asuransi , (if(berat_aktual is null or berat_aktual < 1,  berat_barang, berat_aktual)*ifnull(b.harga,c.harga))+a.biaya_packing+a.biaya_tambahan+a.biaya_asuransi )

										)) as subtotal,
										FROM pengiriman a 
								left join rate_customer b ON b.id_kustomer=a.id_kustomer and b.id_lokasi_asal=a.lokasi_asal and b.id_lokasi_tujuan=a.lokasi_tujuan and b.id_pelayanan=a.service and b.komoditi=a.komoditi and b.loc_code=a.loc_code	
								left join rate_customer c ON c.id_kustomer=a.id_kustomer and c.id_lokasi_asal=a.lokasi_asal and c.id_lokasi_tujuan=a.lokasi_tujuan and c.id_pelayanan=a.service and c.komoditi=a.komoditi	
								where a.tanggal between '$r[dari]' and '$r[sampai]' and a.id_kustomer='$r[id_kustomer]'";*/
					$key		= "asdf$1011";
					$encrypt	= "$_GET[id]";
					$id_invoice	= base64_decrypt($encrypt,$key);

						  /*  "SELECT 
						    	

						    sum(if(b.next_kg is not null or c.next_kg is not null, if(if(berat_aktual is null or berat_aktual < 1, berat_barang, berat_aktual) <= ifnull(b.min_kg,c.min_kg), (ifnull(b.min_kg,c.min_kg)*ifnull(b.harga,c.harga))+a.biaya_packing+a.biaya_tambahan+a.biaya_asuransi , ifnull(b.harga,c.harga)*ifnull(b.min_kg,c.min_kg)+((if(berat_aktual is null or berat_aktual < 1, berat_barang, berat_aktual)-ifnull(b.min_kg,c.min_kg))*ifnull(b.next_kg,c.next_kg)) ), if(if(berat_aktual is null or berat_aktual < 1, berat_barang, berat_aktual) < ifnull(b.min_kg,c.min_kg), (ifnull(b.min_kg,c.min_kg)*ifnull(b.harga,c.harga))+a.biaya_packing+a.biaya_tambahan+a.biaya_asuransi , (if(berat_aktual is null or berat_aktual < 1, berat_barang, berat_aktual)*ifnull(b.harga,c.harga))+a.biaya_packing+a.biaya_tambahan+a.biaya_asuransi ) )) as subtotal
										FROM pengiriman a 
								left join rate_customer b ON b.id_kustomer=a.id_kustomer and b.id_lokasi_asal=a.lokasi_asal and b.id_lokasi_tujuan=a.lokasi_tujuan and b.id_pelayanan=a.service and b.komoditi=a.komoditi and b.loc_code=a.loc_code	
								left join rate_customer c ON c.id_kustomer=a.id_kustomer and c.id_lokasi_asal=a.lokasi_asal and c.id_lokasi_tujuan=a.lokasi_tujuan and c.id_pelayanan=a.service and c.komoditi=a.komoditi	
								where a.id_invoice = $id_invoice and a.status != 7 group by a.id_pengiriman with rollup

								CASE 
										WHEN airwaybill_kode LIKE '001-%' THEN 0
										WHEN airwaybill_kode LIKE '002-%' THEN 1
									end as intercity,
							if( (select intercity) = 1,
										round((select length) *  (select width) * (select height)/4000)
									,
										if (a.service = 5,
											round((select length) *  (select width) * (select height)/4000)
										,
											round((select length) *  (select width) * (select height)/6000)
										)
									) as volume,

							if(berat_aktual is null or berat_aktual < 1,  berat_barang, berat_aktual) as berat_real, 

							SUBSTRING_INDEX(ukuran_barang,'x',1) as length, 
									SUBSTRING_INDEX(SUBSTRING_INDEX(ukuran_barang,'x',2),'x',-1) as width, 
									SUBSTRING_INDEX(ukuran_barang,'x',-1) as height, 
								";*/

						/*$new_qtotal = $mysqli->query("

							select CASE 
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

									ifnull(b.harga,c.harga) as harga_perkg,									

									if ((select berat_real) >= (select volume), (select berat_real), (select volume) ) as berat,

									if ((select min_kgg) >= (select berat), (select min_kgg), (select berat) ) as berat_finish,

									sum(((select harga_perkg) * (select berat_finish))+ a.biaya_packing+a.biaya_tambahan+a.biaya_asuransi) as subtotal

									FROM pengiriman a 								
									left join rate_customer b ON b.id_kustomer=a.id_kustomer and b.id_lokasi_asal=a.lokasi_asal and b.id_lokasi_tujuan=a.lokasi_tujuan and b.id_pelayanan=a.service and b.komoditi=a.komoditi and b.loc_code=a.loc_code	
									left join rate_customer c ON c.id_kustomer=a.id_kustomer and c.id_lokasi_asal=a.lokasi_asal and c.id_lokasi_tujuan=a.lokasi_tujuan and c.id_pelayanan=a.service and c.komoditi=a.komoditi
									
									LEFT JOIN rate_agent d ON d.id_cabang=39 AND d.id_kota=a.lokasi_tujuan AND d.id_pelayanan=CASE WHEN a.service > 3 THEN 3 else a.service end AND d.komoditi=a.komoditi 
								
									LEFT JOIN rate_agent_inbound e ON e.id_cabang=a.id_cabang  and e.id_pelayanan=CASE WHEN a.service > 3 THEN 3 else a.service end and e.komoditi=a.komoditi
									
									LEFT JOIN negara f ON f.id_negara=a.lokasi_tujuan and a.service=6

									where a.id_invoice = $id and a.status != 7
									group by a.id_pengiriman");*/

						/*$qtotal = $mysqli->query("SELECT 

							sum(
								if ( airwaybill_kode LIKE '002-%', 
									if(b.next_kg is not null or c.next_kg is not null, if( 
									if( if(berat_aktual is null or berat_aktual < 1, berat_barang, berat_aktual)> round( SUBSTRING_INDEX(ukuran_barang,'x',1)*SUBSTRING_INDEX(SUBSTRING_INDEX(ukuran_barang,'x',2),'x',-1)*SUBSTRING_INDEX(ukuran_barang,'x',-1)/4000 ) , if(berat_aktual is null or berat_aktual < 1, berat_barang, berat_aktual),round( SUBSTRING_INDEX(ukuran_barang,'x',1)*SUBSTRING_INDEX(SUBSTRING_INDEX(ukuran_barang,'x',2),'x',-1)*SUBSTRING_INDEX(ukuran_barang,'x',-1)/4000 )
										
									)

									 <= ifnull(b.min_kg,c.min_kg), (ifnull(b.min_kg,c.min_kg)*ifnull(b.harga,c.harga))+a.biaya_packing+a.biaya_tambahan+a.biaya_asuransi , ifnull(b.harga,c.harga)*ifnull(b.min_kg,c.min_kg)+((if( if(berat_aktual is null or berat_aktual < 1, berat_barang, berat_aktual)> round( SUBSTRING_INDEX(ukuran_barang,'x',1)*SUBSTRING_INDEX(SUBSTRING_INDEX(ukuran_barang,'x',2),'x',-1)*SUBSTRING_INDEX(ukuran_barang,'x',-1)/4000 ) , if(berat_aktual is null or berat_aktual < 1, berat_barang, berat_aktual),round( SUBSTRING_INDEX(ukuran_barang,'x',1)*SUBSTRING_INDEX(SUBSTRING_INDEX(ukuran_barang,'x',2),'x',-1)*SUBSTRING_INDEX(ukuran_barang,'x',-1)/4000 )
										
									)-ifnull(b.min_kg,c.min_kg))*ifnull(b.next_kg,c.next_kg)) ), 


									if(if(berat_aktual is null or berat_aktual < 1, berat_barang, berat_aktual) < ifnull(b.min_kg,c.min_kg), (ifnull(b.min_kg,c.min_kg)*ifnull(b.harga,c.harga))+a.biaya_packing+a.biaya_tambahan+a.biaya_asuransi , (if( if(berat_aktual is null or berat_aktual < 1, berat_barang, berat_aktual)> round( SUBSTRING_INDEX(ukuran_barang,'x',1)*SUBSTRING_INDEX(SUBSTRING_INDEX(ukuran_barang,'x',2),'x',-1)*SUBSTRING_INDEX(ukuran_barang,'x',-1)/4000 ) , if(berat_aktual is null or berat_aktual < 1, berat_barang, berat_aktual),round( SUBSTRING_INDEX(ukuran_barang,'x',1)*SUBSTRING_INDEX(SUBSTRING_INDEX(ukuran_barang,'x',2),'x',-1)*SUBSTRING_INDEX(ukuran_barang,'x',-1)/4000 )
										
									)*ifnull(b.harga,c.harga))+a.biaya_packing+a.biaya_tambahan+a.biaya_asuransi ) )
								,

									if (a.service = 5,
										if(b.next_kg is not null or c.next_kg is not null, if(if( if(berat_aktual is null or berat_aktual < 1, berat_barang, berat_aktual)> round( SUBSTRING_INDEX(ukuran_barang,'x',1)*SUBSTRING_INDEX(SUBSTRING_INDEX(ukuran_barang,'x',2),'x',-1)*SUBSTRING_INDEX(ukuran_barang,'x',-1)/4000 ) , if(berat_aktual is null or berat_aktual < 1, berat_barang, berat_aktual),round( SUBSTRING_INDEX(ukuran_barang,'x',1)*SUBSTRING_INDEX(SUBSTRING_INDEX(ukuran_barang,'x',2),'x',-1)*SUBSTRING_INDEX(ukuran_barang,'x',-1)/4000 )
										
									) <= ifnull(b.min_kg,c.min_kg), (ifnull(b.min_kg,c.min_kg)*ifnull(b.harga,c.harga))+a.biaya_packing+a.biaya_tambahan+a.biaya_asuransi , ifnull(b.harga,c.harga)*ifnull(b.min_kg,c.min_kg)+((if( if(berat_aktual is null or berat_aktual < 1, berat_barang, berat_aktual)> round( SUBSTRING_INDEX(ukuran_barang,'x',1)*SUBSTRING_INDEX(SUBSTRING_INDEX(ukuran_barang,'x',2),'x',-1)*SUBSTRING_INDEX(ukuran_barang,'x',-1)/4000 ) , if(berat_aktual is null or berat_aktual < 1, berat_barang, berat_aktual),round( SUBSTRING_INDEX(ukuran_barang,'x',1)*SUBSTRING_INDEX(SUBSTRING_INDEX(ukuran_barang,'x',2),'x',-1)*SUBSTRING_INDEX(ukuran_barang,'x',-1)/4000 )
										
									)-ifnull(b.min_kg,c.min_kg))*ifnull(b.next_kg,c.next_kg)) ), if(if( if(berat_aktual is null or berat_aktual < 1, berat_barang, berat_aktual)> round( SUBSTRING_INDEX(ukuran_barang,'x',1)*SUBSTRING_INDEX(SUBSTRING_INDEX(ukuran_barang,'x',2),'x',-1)*SUBSTRING_INDEX(ukuran_barang,'x',-1)/4000 ) , if(berat_aktual is null or berat_aktual < 1, berat_barang, berat_aktual),round( SUBSTRING_INDEX(ukuran_barang,'x',1)*SUBSTRING_INDEX(SUBSTRING_INDEX(ukuran_barang,'x',2),'x',-1)*SUBSTRING_INDEX(ukuran_barang,'x',-1)/4000 )
										
									) < ifnull(b.min_kg,c.min_kg), (ifnull(b.min_kg,c.min_kg)*ifnull(b.harga,c.harga))+a.biaya_packing+a.biaya_tambahan+a.biaya_asuransi , (if( if(berat_aktual is null or berat_aktual < 1, berat_barang, berat_aktual)> round( SUBSTRING_INDEX(ukuran_barang,'x',1)*SUBSTRING_INDEX(SUBSTRING_INDEX(ukuran_barang,'x',2),'x',-1)*SUBSTRING_INDEX(ukuran_barang,'x',-1)/4000 ) , if(berat_aktual is null or berat_aktual < 1, berat_barang, berat_aktual),round( SUBSTRING_INDEX(ukuran_barang,'x',1)*SUBSTRING_INDEX(SUBSTRING_INDEX(ukuran_barang,'x',2),'x',-1)*SUBSTRING_INDEX(ukuran_barang,'x',-1)/4000 )
										
									)*ifnull(b.harga,c.harga))+a.biaya_packing+a.biaya_tambahan+a.biaya_asuransi ) )
									,

										if(b.next_kg is not null or c.next_kg is not null, if(if( if(berat_aktual is null or berat_aktual < 1, berat_barang, berat_aktual)> round( SUBSTRING_INDEX(ukuran_barang,'x',1)*SUBSTRING_INDEX(SUBSTRING_INDEX(ukuran_barang,'x',2),'x',-1)*SUBSTRING_INDEX(ukuran_barang,'x',-1)/6000 ) , if(berat_aktual is null or berat_aktual < 1, berat_barang, berat_aktual),round( SUBSTRING_INDEX(ukuran_barang,'x',1)*SUBSTRING_INDEX(SUBSTRING_INDEX(ukuran_barang,'x',2),'x',-1)*SUBSTRING_INDEX(ukuran_barang,'x',-1)/6000 )
										
									) <= ifnull(b.min_kg,c.min_kg), (ifnull(b.min_kg,c.min_kg)*ifnull(b.harga,c.harga))+a.biaya_packing+a.biaya_tambahan+a.biaya_asuransi , ifnull(b.harga,c.harga)*ifnull(b.min_kg,c.min_kg)+((if( if(berat_aktual is null or berat_aktual < 1, berat_barang, berat_aktual)> round( SUBSTRING_INDEX(ukuran_barang,'x',1)*SUBSTRING_INDEX(SUBSTRING_INDEX(ukuran_barang,'x',2),'x',-1)*SUBSTRING_INDEX(ukuran_barang,'x',-1)/6000 ) , if(berat_aktual is null or berat_aktual < 1, berat_barang, berat_aktual),round( SUBSTRING_INDEX(ukuran_barang,'x',1)*SUBSTRING_INDEX(SUBSTRING_INDEX(ukuran_barang,'x',2),'x',-1)*SUBSTRING_INDEX(ukuran_barang,'x',-1)/6000 )
										
									)-ifnull(b.min_kg,c.min_kg))*ifnull(b.next_kg,c.next_kg)) ), if(if( if(berat_aktual is null or berat_aktual < 1, berat_barang, berat_aktual)> round( SUBSTRING_INDEX(ukuran_barang,'x',1)*SUBSTRING_INDEX(SUBSTRING_INDEX(ukuran_barang,'x',2),'x',-1)*SUBSTRING_INDEX(ukuran_barang,'x',-1)/6000 ) , if(berat_aktual is null or berat_aktual < 1, berat_barang, berat_aktual),round( SUBSTRING_INDEX(ukuran_barang,'x',1)*SUBSTRING_INDEX(SUBSTRING_INDEX(ukuran_barang,'x',2),'x',-1)*SUBSTRING_INDEX(ukuran_barang,'x',-1)/6000 )
										
									) < ifnull(b.min_kg,c.min_kg), (ifnull(b.min_kg,c.min_kg)*ifnull(b.harga,c.harga))+a.biaya_packing+a.biaya_tambahan+a.biaya_asuransi , (if( if(berat_aktual is null or berat_aktual < 1, berat_barang, berat_aktual)> round( SUBSTRING_INDEX(ukuran_barang,'x',1)*SUBSTRING_INDEX(SUBSTRING_INDEX(ukuran_barang,'x',2),'x',-1)*SUBSTRING_INDEX(ukuran_barang,'x',-1)/6000 ) , if(berat_aktual is null or berat_aktual < 1, berat_barang, berat_aktual),round( SUBSTRING_INDEX(ukuran_barang,'x',1)*SUBSTRING_INDEX(SUBSTRING_INDEX(ukuran_barang,'x',2),'x',-1)*SUBSTRING_INDEX(ukuran_barang,'x',-1)/6000 )
										
									)*ifnull(b.harga,c.harga))+a.biaya_packing+a.biaya_tambahan+a.biaya_asuransi ) )
									)
								)

							) as subtotal

										FROM pengiriman a 
								left join rate_customer b ON b.id_kustomer=a.id_kustomer and b.id_lokasi_asal=a.lokasi_asal and b.id_lokasi_tujuan=a.lokasi_tujuan and b.id_pelayanan=a.service and b.komoditi=a.komoditi and b.loc_code=a.loc_code	
								left join rate_customer c ON c.id_kustomer=a.id_kustomer and c.id_lokasi_asal=a.lokasi_asal and c.id_lokasi_tujuan=a.lokasi_tujuan and c.id_pelayanan=a.service and c.komoditi=a.komoditi	
								where a.id_invoice = $id_invoice and a.status != 7 ");
						$st = $qtotal->fetch_assoc();*/
						
						echo'
							<table class="table tablebawah">
								
								<tr>
                					<th style="width:65%">'.($r['judul_awal']).'</th>                					
              					</tr>
								';
								
									echo '
									<tr>
	                					<th style="width:65%">'.($r['judul_subtotal'] == null ? 'subtotal' : $r['judul_subtotal']).'</th>
	                					<td style="text-align:right;"> Rp '. number_format($sum_total) .'</td>
	              					</tr>
	              				';


              				$encrypt = "$_GET[id]";
							$id_inv = base64_decrypt($encrypt,$key);
              				$qt = "select * from invoice_detail where id_invoice='".$id_inv."'";

                            $tambahan = $mysqli->query($qt);

                            while($t = $tambahan->fetch_assoc()){


              				echo'
              					<tr>

									<th>'.$t['detail_biaya_tambahan'].'</th>
                					<td style="text-align:right;">'.number_format($t['biaya_tambahan']).'</td>
              					</tr>';
              				}		

								 $qsum = "select sum(biaya_tambahan) as sum_biaya_tambahan from invoice_detail where id_invoice='".$id_inv."'";
//              					$sum_bt = mysqli_query($con, $qsum);
//              					$sbt = mysqli_fetch_assoc($sum_bt);

                                $sum_bt = $mysqli->query($qsum);
                                $biayaTambahan = 0;
                                while($sbt = $sum_bt->fetch_assoc()){
                                    $biayaTambahan = $sbt['sum_biaya_tambahan'];
                                }


								$qp = "select sum(biaya_tambahan) as pajak from invoice_detail where id_invoice='".$id_inv."' and pajak='Ya'";
//              					$pajak = mysqli_query($con, $qp);
//              					$p = mysqli_fetch_assoc($pajak);

                                $pajak = $mysqli->query($qp);
                                $tambahan_kena_pajak = 0;
                                while($p = $pajak->fetch_assoc()){
                                    $tambahan_kena_pajak = $p['pajak'];
                                }
             

              					$pajak1 = $ppn/100*($tambahan_kena_pajak+$sum_total);

								$test_pajak = floor($ppn/100* ($sum_total+$tambahan_kena_pajak));              					

              					$pajak_packing = floor(10/100*$sum_packing);
              					$total_semua = $sum_total+$biayaTambahan+$test_pajak+$sum_packing+$pajak_packing+$sum_asuransi;
								echo'
								<tr>
									<th>PPN ('.$ppn.'%) </th>
                					<td style="text-align:right;">Rp '.number_format($test_pajak). '</td>

              					</tr>
              					<tr>
									<th>Packing Kayu</th>
                					<td style="text-align:right;">Rp '.number_format($sum_packing). '</td>
                					
              					</tr>
              					<tr>
									<th>PPN 10%</th>
                					<td style="text-align:right;">Rp '.number_format($pajak_packing). '</td>
                					
              					</tr>
              					<tr>
									<th>Asuransi</th>
                					<td style="text-align:right;">Rp '.number_format($sum_asuransi).'</td>
                					
              					</tr>
              					<tr>
                					<th>Total</th>
                					<td style="text-align:right;">Rp '.number_format($total_semua).'</td>
              					</tr>
            				</table>
          				</div>
        			</div>
				</div>
      			<div class="row no-print">
        			<div class="col-xs-12">';

          				$key		= "asdf$1011";
						$encrypt	= "$_GET[id]";
						$id 		= base64_decrypt($encrypt,$key);

          				$query_bottom 		= $mysqli->query("select case when a.status='draft' then 'DRAFT' else a.nomor end as nomor from invoice a left join kustomer b on b.id_kustomer=a.id_kustomer left join kota c on c.id_kota=b.kota left join rekening_cesco d on a.bank=d.id where id_invoice='$id'");
						
						$b= $query_bottom->fetch_assoc();


          				 $cek = "SELECT 	
          							a.airwaybill_kode,				
									if(id_kota_tujuan>0,ifnull(g.min_kg,h.min_kg),ifnull(b.min_kg,c.min_kg)) as min_kg, 
									if(id_kota_tujuan>0,ifnull(g.next_kg,h.next_kg),ifnull(b.next_kg,c.next_kg)) as min_kg, 
									if(id_kota_tujuan>0,ifnull(g.harga,h.harga),ifnull(b.harga,c.harga)) as min_kg, 
									
								FROM pengiriman a 								
								left join rate_customer b ON b.id_kustomer=a.id_kustomer and b.id_lokasi_asal=a.lokasi_asal and b.id_lokasi_tujuan=a.lokasi_tujuan and b.id_pelayanan=a.service and b.komoditi=a.komoditi and b.loc_code=a.loc_code	
								left join rate_customer c ON c.id_kustomer=a.id_kustomer and c.id_lokasi_asal=a.lokasi_asal and c.id_lokasi_tujuan=a.lokasi_tujuan and c.id_pelayanan=a.service and c.komoditi=a.komoditi
								left join rate_customer g ON g.id_kustomer=a.id_kustomer and g.id_lokasi_asal=a.id_kota_asal and g.id_lokasi_tujuan=a.id_kota_tujuan and g.id_pelayanan=a.service and g.komoditi=a.komoditi and g.loc_code=a.loc_code

								left join rate_customer h ON h.id_kustomer=a.id_kustomer and h.id_lokasi_asal=a.id_kota_asal and h.id_lokasi_tujuan=a.id_kota_tujuan and h.id_pelayanan=a.service and h.komoditi=a.komoditi
																

								where a.id_invoice = $id_inv and (b.harga is null and c.harga is null and a.harga_pengiriman is null and.g.harga is null and h.harga is null) and a.status !=7
								group by a.id_pengiriman" ;

					//	$rowcek=mysqli_query($con, $cek);
						// $row_cek =mysqli_num_rows($rowcek);


						 //cek intercity

						/*  $cek_intercity = "SELECT 	
          							airwaybill_kode
          							
									
								FROM pengiriman a 								

								where a.id_invoice = $id_inv and costing_delivery_fee and costing_pickup_fee is null and x_intercity(lokasi_asal) = 'y' and x_intercity(lokasi_tujuan) = 'y'
								and status != 7
								group by a.id_pengiriman" ;

						$rowcekintercity=mysqli_query($con, $cek_intercity);

						while($test_inter=mysqli_fetch_assoc($rowcekintercity)){
									$cek_inter = $test_inter['airwaybill_kode'].'inter';
								}

						 $rc_intercity =mysqli_num_rows($rowcekintercity);

						//cek  outbound

						  $cek_outbound = "SELECT 	
          							airwaybill_kode
          							
									
								FROM pengiriman a 								

								where a.id_invoice = $id_inv 
								and costing_pickup_fee is null 
								and costing_outbound_fee is null 
								and costing_outbound_toll is null 
								and costing_outbound_parking is null 
								and costing_outbound_smu is null 
								and x_intercity(lokasi_asal) = 'y' 
								and x_intercity(lokasi_tujuan) != 'y'
								and status != 7
								group by a.id_pengiriman" ;	

								$rowcekoutbound=mysqli_query($con, $cek_outbound);
								
								while($test_out=mysqli_fetch_assoc($rowcekoutbound)){
									$cek_out = $test_out['airwaybill_kode'].'out';
								}
								
						 		$rc_out =mysqli_num_rows($rowcekoutbound);

						//cek  inbound

						  $cek_inbound = "SELECT 	
          							airwaybill_kode
          							
									
								FROM pengiriman a 								

								where a.id_invoice = $id_inv 
								and costing_delivery_fee is null 
								and costing_inbound_fee is null 
								and costing_inbound_toll is null 
								and costing_inbound_parking is null 
								and costing_inbound_smu is null 
								and x_intercity(lokasi_asal) != 'y' 
								and x_intercity(lokasi_tujuan) = 'y'
								and status != 7
								group by a.id_pengiriman" ;	

								$rowcekinbound=mysqli_query($con, $cek_inbound);
								while($test_in=mysqli_fetch_assoc($rowcekinbound)){
									$cek_in = $test_in['airwaybill_kode'].'in ';
								}
								$rc_in =mysqli_num_rows($rowcekinbound);*/

							//cek  inbound

						 /* $cek_inv = "SELECT 	
          							airwaybill_kode
          							
									
								FROM pengiriman a 								

								where a.id_invoice = $id_inv 
								and kunci_total_agent is null 								
								and status != 7
								group by a.id_pengiriman" ;	

								$rowcekinv=mysqli_query($con, $cek_inv);
								while($test_inv=mysqli_fetch_assoc($rowcekinv)){
									$cek_inv = $test_inv['airwaybill_kode'].'inv ';
								}
								$rc_inv =mysqli_num_rows($rowcekinv);

							$cek_kayu_asu = "SELECT 	
          							airwaybill_kode
          							
									
								FROM pengiriman a 								

								where a.id_invoice = $id_inv 
								and ( (packing_kayu = 2 and biaya_packing = 0) or (asuransi='Y' and biaya_asuransi = 0) ) 								
								and status != 7
								group by a.id_pengiriman" ;	

								$rowcekpackasu=mysqli_query($con, $cek_kayu_asu);
								while($test_packasu=mysqli_fetch_assoc($rowcekpackasu)){
									$cek_pack = $test_packasu['airwaybill_kode'].'pack asu ';
								}
								$rc_pack =mysqli_num_rows($rowcekpackasu);*/


								
						 		



						//<a href="../e-cesco/module/mod_invoice-customer/invoice-customer_action.php?module=invoice-customer&act=nomor&idinvoice='.$id_inv.'"  class="btn btn-primary pull-right " '.($r['nomor'] == 'DRAFT' ? ($row_cek == 0 ? 'enabled' : 'disabled') : 'disabled' ).'>'.$r['nomor'].$row_count.$row_cek.' Buat No Invoice </a>

						 $sql = $mysqli->query($cari);
						$row_count= $sql->num_rows;
						
						while($row = $sql->fetch_assoc()){
							echo '<input type="hidden" name="harga_pengiriman['.$row["id_pengiriman"].']" form="kunciform" value="'.$row['harga_lock'].'">
										';
						}
          				



          				echo ' <a href="../../e-cesco/reports/invoice.php?idinvoice='.$id_inv.'" target="_blank" class="btn btn-primary pull-right"><i class="fa fa-print"></i> Invoice PDF</a>
          				';

                        echo ' <a href="../../e-cesco/reports/PHPExcel/invoice_lampiran.php?idinvoice='.$id_inv.'" target="_blank" class="btn btn-primary pull-right"><i class="fa fa-print"></i>Print Excel</a>';

          				 echo '         				
          				
        			</div>
      			</div>
    		</section>
    		<div class="clearfix"></div>
			
			<div id="airwaybill_detail" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
							<h4 class="modal-title" id="myModalLabel">Airwaybill Detail</h4>
						</div>
						<div class="modal-content">
							<div class="modal-body"></div>
						</div>
					</div>
				</div>
			</div>
			<div id="modal_price_insert" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
							<h4 class="modal-title" id="myModalLabel">Insert Price</h4>
						</div>
						<div class="modal-content">
							<div class="alert alert-warning alert-dismissible">
			                	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
			               		<h4><i class="icon fa fa-warning"></i> Perhatian!</h4>
			                	penambahan harga berlaku untuk semua pengiriman dan customer yang sama.
			              	</div>
							<div class="modal-body"></div>
						</div>
					</div>
				</div>
			</div>
			<div id="modal_price_edit" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
							<h4 class="modal-title" id="myModalLabel">Edit Price</h4>
						</div>
						<div class="modal-content">
							<div class="alert alert-warning alert-dismissible">
			                	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
			               		<h4><i class="icon fa fa-warning"></i> Perhatian!</h4>
			                	penambahan harga berlaku untuk semua pengiriman dan customer yang sama.
			              	</div>
							<div class="modal-body"></div>
						</div>
					</div>
				</div>
			</div>			
			<div id="modal_tambah_biaya" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
							<h4 class="modal-title" id="myModalLabel">Tambah biaya invoice</h4>
						</div>
						<div class="modal-content">
							<div class="modal-body">
								
								<form action="'.$aksi.'?module=invoice-customer&act=tambah-biaya" method="POST">
								<input type="hidden" name="id_invoice" id="idinvoice" class="form-control" value="" />
									<div class="form-group" style="padding-bottom: 25px;">
										<label class="col-md-3 control-label">Detail biaya:</label>
										<div class="col-md-8">
											<input type="text" name="detail_biaya" class="form-control" value="" placeholder="contoh biaya pickup" />
										</div>
									</div>
									<div class="form-group" style="padding-bottom: 25px;">
										<label class="col-md-3 control-label">biaya</label>
										<div class="col-md-8">
											<input type="number" name="biaya" class="form-control" value="0" />
										</div>
									</div>
									<div class="checkbox">
                  						<label>
                    						<input type="checkbox" name="pajak" value"Ya" checked> pajak 1% ?
                  						</label>
                					</div>
									<div class="modal-footer">
										<button class="btn btn-success" type="submit">Tambah</button>
										<button type="reset" class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Cancel</button>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div id="modal_duedate" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
							<h4 class="modal-title" id="myModalLabel">Edit due date</h4>
						</div>
						<div class="modal-content">
							<div class="modal-body">
								<form action="'.$aksi.'?module=invoice-customer&act=edit-duedate" method="POST">
									<input type="hidden" name="id_invoice" class="form-control" value="'.$id_invoice.'" />
									<div class="form-group">
										<label class="col-md-3 control-label">Tanggal <span class="required">* </span></label>
										<div class="col-md-9">
											<div class="input-group">
												<div class="input-group-addon">
													<i class="fa fa-calendar"></i>
												</div>
												<input type="text" class="form-control" id="tanggal" name="tanggal" >
											</div>
										</div>
									</div>

									<div class="modal-footer">
										<button class="btn btn-success" type="submit">edit</button>
										<button type="reset" class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Cancel</button>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div id="modal_dateinv" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
							<h4 class="modal-title" id="myModalLabel">Edit due date</h4>
						</div>
						<div class="modal-content">
							<div class="modal-body">
								<form action="'.$aksi.'?module=invoice-customer&act=edit-dateinv" method="POST">
									<input type="hidden" name="id_invoice" class="form-control" value="'.$id_invoice.'" />
									<div class="form-group">
										<label class="col-md-3 control-label">Tanggal <span class="required">* </span></label>
										<div class="col-md-9">
											<div class="input-group">
												<div class="input-group-addon">
													<i class="fa fa-calendar"></i>
												</div>
												<input type="text" class="form-control" id="tanggal" name="tanggal" >
											</div>
										</div>
									</div>

									<div class="modal-footer">
										<button class="btn btn-success" type="submit">edit</button>
										<button type="reset" class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Cancel</button>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div id="modal_noinv" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
							<h4 class="modal-title" id="myModalLabel">Edit no invoice</h4>
						</div>
						<div class="modal-content">
							<div class="modal-body">
								<form action="'.$aksi.'?module=invoice-customer&act=edit-noinv" method="POST">
									<input type="hidden" name="id_invoice" class="form-control" value="'.$id_invoice.'" />
									<div class="form-group">
										<label class="col-md-3 control-label">No invoice <span class="required">* </span></label>
										<div class="col-md-9">
											<div class="input-group">
												<div class="input-group-addon">
													<i class="fa fa-calendar"></i>
												</div>
												<input type="text" class="form-control" id="noinv" name="noinv" >
											</div>
										</div>
									</div>

									<div class="modal-footer">
										<button class="btn btn-success" type="submit">edit</button>
										<button type="reset" class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Cancel</button>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div id="modal_subtotaljudul" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
							<h4 class="modal-title" id="myModalLabel">Edit due date</h4>
						</div>
						<div class="modal-content">
							<div class="modal-body">
								<form action="'.$aksi.'?module=invoice-customer&act=edit-subtotaljudul" method="POST">
									<input type="hidden" name="id_invoice" class="form-control" value="'.$id_invoice.'" />
									<div class="form-group">
										<label class="col-md-3 control-label">judul subtotal <span class="required">* </span></label>

										<div class="col-md-9">											
												<input type="text" class="form-control" id="editsubtotaljudul" name="subtotaljudul" >											
										</div>
									</div>

									<div class="modal-footer">
										<button class="btn btn-success" type="submit">edit</button>
										<button type="reset" class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Cancel</button>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div id="modal_editjudulinv" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
							<h4 class="modal-title" id="myModalLabel">Edit due date</h4>
						</div>
						<div class="modal-content">
							<div class="modal-body">
								<form action="'.$aksi.'?module=invoice-customer&act=edit-judulinv" method="POST">
									<input type="hidden" name="id_invoice" class="form-control" value="'.$id_invoice.'" />
									<div class="form-group">
										<label class="col-md-3 control-label">judul subtotal <span class="required">* </span></label>

										<div class="col-md-9">											
												<textarea class="form-control" id="editjudulinv" name="judulinv" >  </textarea>											
										</div>
									</div>

									<div class="modal-footer">
										<button class="btn btn-success" type="submit">edit</button>
										<button type="reset" class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Cancel</button>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div id="modal_editppn" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
							<h4 class="modal-title" id="myModalLabel">Edit due date</h4>
						</div>
						<div class="modal-content">
							<div class="modal-body">
								<form action="'.$aksi.'?module=invoice-customer&act=edit-ppn" method="POST">
									<input type="hidden" name="id_invoice" class="form-control" value="'.$id_invoice.'" />
									<div class="form-group">
										<label class="col-md-3 control-label">PPN  <span class="required">* </span></label>

										<div class="col-md-9">											
											<div class="radio">
												  <label><input type="radio" value="1" name="ppn" '.($ppn ==1 ? 'checked' : '').'>1 %</label>
												</div>
												<div class="radio">
												  <label><input type="radio" value="10" name="ppn" '.($ppn ==10 ? 'checked' : '').'>10 %</label>
											</div>											
										</div>
									</div>

									<div class="modal-footer">
										<button class="btn btn-success" type="submit">edit</button>
										<button type="reset" class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Cancel</button>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div id="modal_total_fix" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
							<h4 class="modal-title" id="myModalLabel">Edit Harga Manual</h4>
						</div>
						<div class="modal-content">
							<div class="modal-body">								
							</div>
						</div>
					</div>
				</div>
			</div>
			<div id="inv_add_awb" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
							<h4 class="modal-title" id="myModalLabel">Tambah Airwaybill</h4>
						</div>
						<div class="modal-content">
							<div class="modal-body">
								';
								//echo "SELECT airwaybill_kode, id_pengiriman FROM pengiriman where id_kustomer= '$r[id_kustomer]' and tanggal BETWEEN (CURRENT_DATE() - INTERVAL 3 MONTH) AND CURRENT_DATE() and (id_invoice is null or id_invoice = 0) ORDER BY id_pengiriman";
														

								echo '
								<form action="'.$aksi.'?module=invoice-customer&act=addawb" method="POST">						
									<div class="form-group">
													<label class="col-md-3 control-label"> Pilih pengiriman </label>
													<div class="col-md-6">
														<select name="add_pengiriman[]" id="data_pengiriman_inv" multiple="multiple" data-placeholder="Choose..." class="form-control select2" style="width: 100%;">';
														

														$service = $mysqli->query("SELECT airwaybill_kode, id_pengiriman FROM pengiriman where id_kustomer= '$r[id_kustomer]' and tanggal BETWEEN (CURRENT_DATE() - INTERVAL 3 MONTH) AND CURRENT_DATE() and (id_invoice is null or id_invoice = 0) ORDER BY id_pengiriman");
														while($s=$service->fetch_assoc()){
															echo'<option value="'.$s['id_pengiriman'].'">'.$s['airwaybill_kode'].'</option>';
														}
														echo'
														</select>
														<div class="val-message"></div>
													</div>
												</div>
								
									<input type="hidden" name="id_invoice" class="form-control" value="'.$id_invoice.'" />
									
									<div class="modal-footer">
										<button class="btn btn-success" type="submit">Confirm</button>
										<button type="reset" class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Cancel</button>
									</div>
								</form>
							</div>

						</div>
					</div>
				</div>
			</div>
			<div id="awb_not_billed" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog" style="width: 80%;height: 900px !important;max-height: 900px !important;overflow-y: initial !important;">
					<div class="modal-content" style="overflow-y: auto;">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
							<h4 class="modal-title" id="myModalLabel">Airwaybill Belum Tertagih</h4>
						</div>
						<div class="modal-content">
							<div class="modal-body">
								<form action="module/mod_invoice-customer/invoice-customer_action.php?module=invoice-customer&act=addawbbulk" id="frmAddAwbBulk" method="POST" onsubmit="return false">
                                    <table id="awbNotBilled" class="table table-bordered table-striped table-hover" width="100%" cellspacing="0" style="white-space:nowrap;">
                                        <thead>
                                            <tr>
                                                    <th rowspan="2">#</th>
                                                    <th rowspan="2"><input type="checkbox" name="pengiriman_bulk_all" id="pengiriman_bulk_all"></th>
                                                    <th colspan="4"><center>Data</center></th>		
                                                    <th colspan="5"><center>Pengirim</center></th>
                                                    <th colspan="6"><center>Penerima</center></th>												
                                                    <th colspan="2">Berat</th>              
                                                </tr>
                                                <tr>
                                                    
                                                    <th>Pick Up</th>
                                                    <th>Airwaybill</th>
                                                    <th>Komoditi</th>
                                                    <th>Service Type</th>
                                                    <th>Requestor</th>
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
                                                    
                                                </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                    <input type="hidden" name="id_invoice_bulk" value="'.$id.'">
                                    <button class="btn btn-success" type="button" id="addAwbBulk">Tambahkan AWB</button>
                                    <button type="reset" class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Cancel</button>
								</form>
								
							</div>

						</div>
					</div>
				</div>
			</div>			
            <div class="modal modal-static fade" id="processing-modal" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                <div class="modal-dialog process-modal" style="width:200px;">
                    <div class="modal-content">
                        <div class="modal-body">
                            <div class="text-center">
                                <i class="fa fa-refresh fa-5x fa-spin"></i>
                                <h4>Processing...</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
			';
			
			break;

		}

}
?>