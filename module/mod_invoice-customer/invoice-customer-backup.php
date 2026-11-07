<section class="content-header">
	<h1>Invoice <small>Customer</small></h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-home"></i> Home</a></li>
		<li><a href="#">Finance</a></li>
		<li><a href="#">Invoice</a></li>
		<li class="active">Customer</li>
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
	if($u['level']=='admin' || $u['id_user']== 5 || $u['level'] == 'finance')
	{
		$aksi	= "module/mod_invoice-customer/invoice-customer_action.php";
		$act	= !empty($_GET['act']) ? $_GET['act'] : '';

		switch($act)
		{

			default:
			echo'
			<section class="content">';
				if (isset($_SESSION['pesan']) && $_SESSION['pesan'] == 'success')
				{
					echo'
					<div class="callout callout-success pesan">
						<h4><i class="icon fa fa-check"></i> Success!</h4>
						<p>Draft invoice has been saved</p>
					</div>';
				}
				elseif(isset($_SESSION['pesan']) && $_SESSION['pesan'] == 'failed')
				{
					echo'
					<div class="callout callout-warning pesan">
						<h4><i class="icon fa fa-warning"></i> Failed!</h4>
						<p>Draft invoice not successfully saved</p>
					</div>';
				}
				elseif(isset($_SESSION['pesan']) && $_SESSION['pesan'] == 'null')
				{
					echo'
					<div class="callout callout-danger pesan">
						<h4><i class="icon fa fa-warning"></i> Failed!</h4>
						<p>Airwaybill not found</p>
					</div>';
				}
				elseif(isset($_SESSION['pesan']) && $_SESSION['pesan'] == 'delete')
				{
					echo'
					<div class="callout callout-success pesan">
						<h4><i class="icon fa fa-check"></i> Success!</h4>
						<p>Delete Success</p>
					</div>';
				}
				$_SESSION['pesan'] = '';
				echo'
				<div class="row">
					<div class="col-md-12">
						<div class="box box-primary">
							<div class="box-header">
								<div class="row">
									
									<div class="col-md-12 col-offset-md-6">
									<button class="btn btn-sm btn-default" onclick="window.location.href = \'?module=invoice-customer&act=create\';"><i class="fa fa-plus text-green"></i> Create</button>
									<div class="btn-group pull-right">
											
											<button class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown">Tools <i class="fa fa-angle-down"></i></button>
											<ul class="dropdown-menu pull-right">

												<li><a href="main.php?module=invoice-customer&act=search" class=""><i class="fa fa-search"></i>Search By Airwaybill</a></li>

												
											</ul>
										</div>
									</div>
								</div>
							</div>
							<div class="box-body">
								<table id="InvoiceCustomer" class="table table-bordered table-striped table-hover" width="100%" cellspacing="0" style="white-space:nowrap;">
									<thead>
										<tr>
											<th>Nomor</th>
											<th>Tanggal</th>
											<th>metode</th>
											<th>Customer</th>
											<th>type Invoice</th>
											<th>Tanggal Airwaybill</th>
											<th width="50">Tanggal Bayar</th>
											<th width="65"><center>#</center></th>
										</tr>
									</thead>
									<tbody>
									</tbody>
								</table>
								<br>
								<div class="row">
                                    <div class="form-group">
                                        <label class="col-md-1 control-label">Periode* </span></label>
                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-calendar"></i>
                                                </div>
                                                <input type="text" required class="form-control date-picker" placeholder="yyyy-mm" id="tanggal-excel" name="tanggal" data-date-format="mm-yyyy">
                                            </div>	
                                        </div>
                                        <div class="col-md-1">
                                            <div class="btn-group">
                                                <button type="button" id="excelInv" class="btn btn-primary pull-right"><i class="fa fa-file-excel-o"></i> Excel</button>
                
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                    </div>
                                </div>
                            </div>
							</div>
							
						</div>
					</div>
				</div>
			</section>
			
			
			<div id="modal_bayar" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
							<h4 class="modal-title" id="myModalLabel">Invoice Paid</h4>
						</div>
						<div class="modal-content">
							<div class="modal-body">
								';
								//echo "SELECT airwaybill_kode, id_pengiriman FROM pengiriman where id_kustomer= \'$r[id_kustomer]\' and tanggal BETWEEN (CURRENT_DATE() - INTERVAL 3 MONTH) AND CURRENT_DATE() and (id_invoice is null or id_invoice = 0) ORDER BY id_pengiriman";


								echo '
								<form action="'.$aksi.'?module=invoice-customer&act=bayar" id="frmBayarInv" method="POST">						
									<div class="form-group" style="padding-bottom: 25px;">
										<label class="col-md-3 control-label">Nomor Invoice <span class="required">* </span></label>
										<div class="col-md-8">
											<div class="input-group">
												<input type="text" class="form-control" id="no_invoice" readonly>
											</div>
										</div>
									</div>
									<div class="form-group" style="padding-bottom: 25px;">
										<label class="col-md-3 control-label">Tanggal Bayar <span class="required">* </span></label>
										<div class="col-md-8">
											<div class="input-group">
												<div class="input-group-addon">
													<i class="fa fa-calendar"></i>
												</div>
												<input type="text" class="form-control" id="tanggal" name="tanggal" required>
											</div>
										</div>
									</div>
									<div class="form-group" style="padding-bottom: 25px;">
										<label class="col-md-3 control-label">Nilai Rupiah <span class="required">* </span></label>
										<div class="col-md-8">
											<input type="number" id="nilai" name="nilai" value="0" class="form-control" required>
										</div>
									</div>
									<div class="form-group" style="padding-bottom: 25px;">
										<label class="col-md-3 control-label">Keterangan <span class="required">* </span></label>
										<div class="col-md-8">
											<input type="text" name="keterangan" id="keterangan" value="" class="form-control" required>
										</div>
									</div>
								
									<input type="hidden" name="id_invoice" id="id_invoice" class="form-control" />
									
									<div class="modal-footer">
										<button class="btn btn-success" type="submit" id="btnSubmitBayar">Submit</button>
										<button type="reset" class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Cancel</button>
									</div>
								</form>
							</div>

						</div>
					</div>
				</div>
			</div>';

			break;

			case "search":
			echo'
			<section class="content">
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">Search</h3>

					</div><!-- /.box-header -->
					<div class="box-body">
						<div class="form-group">
							<label class="col-sm-3 control-label full-right">Search By Airwaybill</label>
							<div class="col-sm-6">
								<div class="input-group">
									<input type="text" class="form-control" id="searchawb" name="searchawb" value="" >
									<button type="button" name="invoice-customer"  data-id="invoice-customer" id="btnsearchcosting" class="btn form-control btn-primary">search</button>
							</div>
						</div>
						<div id="load" style="display:none;"><img src="dist/img/Loading_icon.gif"/ style="display:block; margin: 0 auto; "></div>

						<table id="airwaybillsrc" class="table table-bordered table-striped table-hover" width="100%" cellspacing="0" style="white-space:nowrap; margin-top:50px;">
									<thead>
										<tr style="text-transform: uppercase;">
											<th>AWB</th>
											<th width="90px">View Invoice</th>
										</tr>
									<thead>
									<tbody>
										<tr id="hasil" style="text-transform: uppercase;">
											<td></td>
											<td></td>
										</tr>
									</tbody>
						</table>

					</div>
				</div>

			</section>
			';
			break;

			case "create":

			echo'
			<section class="content">
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">Create</h3>
						<div class="box-tools pull-right">
							<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
							<button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
						</div>
					</div>
					<form class="form-horizontal" method="post" action="'.$aksi.'?module=invoice-customer&act=create" autocomplete="off">
					<div class="box-body">
						<div class="form-group">
							<label class="col-md-3 control-label">Tanggal <span class="required">* </span></label>
							<div class="col-md-6">
								<div class="input-group">
									<div class="input-group-addon">
										<i class="fa fa-calendar"></i>
									</div>
									<input type="text" class="form-control" id="tanggal" name="tanggal" value="'.$setdate.'" readonly>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label">Customer <span class="required">* </span></label>
							<div class="col-md-6">
								<select name="id_kustomer_inv" id="id_kustomer_inv" data-placeholder="Choose..." class="form-control select2">
									<option value="">Choose...</option> ';
								$customer = $mysqli->query("SELECT id_kustomer, nama_kustomer FROM kustomer ORDER BY nama_kustomer");
								while($c=$customer->fetch_assoc()){
									echo'<option value="'.$c['id_kustomer'].'">'.$c['nama_kustomer'].'</option>';
								}
								echo'
								</select>
								<div class="val-message"></div>
							</div>
						</div>
						<div class="form-group" id="loc_code" style="display:none;">
							<label class="col-md-3 control-label">Loc Code <span class="required">* </span></label>
							<div class="col-md-6">
								<select name="loc_code[]" id="loc_code_inv" multiple="multiple" data-placeholder="Choose..." class="form-control select2" style="width: 100%;">
								';

								/*$customer = $mysqli->query("SELECT id_kustomer, nama_kustomer FROM kustomer ORDER BY nama_kustomer");
								while($c=$customer->fetch_assoc()){
									echo'<option value="'.$c['id_kustomer'].'">'.$c['nama_kustomer'].'</option>';
								}*/
								echo'
								</select>

								<div class="val-message"></div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label">Service <span class="required">* </span></label>
							<div class="col-md-6">
								<select name="id_pelayanan[]" multiple="multiple" data-placeholder="Choose..." class="form-control select2" style="width: 100%;">';
								$service = $mysqli->query("SELECT id_pelayanan, nama_pelayanan FROM pelayanan WHERE active='Y' ORDER BY id_pelayanan");
								while($s=$service->fetch_assoc()){
									echo'<option value="'.$s['id_pelayanan'].'">'.$s['nama_pelayanan'].'</option>';
								}
								echo'
								</select>
								<div class="val-message"></div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label">Komoditi <span class="required">* </span></label>
							<div class="col-md-6">
								<select name="id_komoditi[]" multiple="multiple" data-placeholder="Choose..." class="form-control select2" style="width: 100%;">
									<option value="1"> Paket</option>
									<option value="2"> Dokumen</option>
								</select>
								<div class="val-message"></div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label">Bank <span class="required">* </span></label>
							<div class="col-md-6">
								<select name="bank" id="bank" data-placeholder="Choose..." class="form-control select2">';
								$rekening = $mysqli->query("SELECT id, nama_bank, no_rekening, atas_nama FROM rekening_cesco");
								while($rek=$rekening->fetch_assoc()){
									echo'<option value="'.$rek['id'].'">'.$rek['nama_bank'].' - '.$rek['no_rekening'].' - '.$rek['atas_nama'].'</option>';
								}
								echo'
								</select>
								<div class="val-message"></div>
							</div>
						</div>
						<div class="form-group" id="show_tagih_ke" style="display:none;">
							<label class="col-md-3 control-label"> Tagihan ke </label>
							<div class="col-md-6">
								<select name="tagih_ke" id="tagih_ke"  data-placeholder="Choose..." class="form-control select2" style="width: 100%;">';
								
								echo'
								</select>
								<div class="val-message"></div>
							</div>
						</div>



						<div class="form-group">
							<label class="control-label col-md-3">metode pengambilan data<span class="required">* </span></label>
							<div class="col-md-6">
								<div class="radio radio-info">
									<label><input type="radio" class="choose_method" value="1" name="choose_method" checked>range tanggal</label>
									<label><input type="radio" class="choose_method" value="2" name="choose_method"> pilih pengiriman </label>
								</div>
							</div>
						</div>
						<div class="form-group" id="ifrange">
							<label class="col-md-3 control-label full-right">Tanggal Airwaybill</label>
							<div class="col-md-6">
								<div class="input-group">
									<div class="input-group-addon">
										<i class="fa fa-calendar"></i>
									</div>
									<input type="text" class="form-control" id="daterange" name="tgl_airwaybill" readonly>
								</div>
							</div>
						</div>
						<div class="form-group" id="ifchoose" style="display:none;">
							<label class="col-md-3 control-label"> Pilih pengiriman </label>
							<div class="col-md-6">
								<select name="data_pengiriman[]" id="data_pengiriman_inv" multiple="multiple" data-placeholder="Choose..." class="form-control select2" style="width: 100%;">';
								/*$service = $mysqli->query("SELECT airwaybill_kode, id_pengiriman,tanggal FROM pengiriman where id_kustomer=13 AND YEAR(tanggal) = YEAR(CURRENT_DATE - INTERVAL 3 MONTH) AND MONTH(tanggal) = MONTH(CURRENT_DATE - INTERVAL 3 MONTH) and (id_invoice = null or id_invoice = 0) ORDER BY id_pengiriman");
								while($s=$service->fetch_assoc()){
									echo'<option value="'.$s['id_pengiriman'].'">'.$s['airwaybill_kode'].'</option>';
								}*/
								echo'
								</select>
								<div class="val-message"></div>
							</div>
						</div>
					</div>
					<div class="box-footer">
						<button type="button" class="btn btn-default" onclick="window.location.href = \'?module=invoice-customer\';">Cancel</button>
						<button type="submit" class="btn btn-primary pull-right"><i class="fa fa-send"></i> Process</button>
					</div>
					</form>
				</div>
			</section>';

			break;

			case "update":

			$key		= "asdf$1011";
			$encrypt	= "$_GET[id]";
			$id 		= base64_decrypt($encrypt,$key);

			$query 		= $mysqli->query("select a.*,d.nama_bank, d.id as id_bank, case when a.status='draft' then 'DRAFT' else a.nomor end as nomor,ppn,
				b.kode_kustomer, b.nama_kustomer, b.alamat, b.kota,b.tagihan_ke, c.nama_kota, x_namakota(x_hub(b.kota)) as kota, b.telp,b.custom_invoice,
				tampil_nama_kustomer as tampil, tampil_ppn,approval_jika_minus,a.tagih_ke

				from invoice a left join kustomer b on b.id_kustomer=a.id_kustomer left join kota c on c.id_kota=b.kota left join rekening_cesco d on a.bank=d.id where id_invoice='$id'");
			$r= $query->fetch_assoc();
			$tanggal = $r['tanggal'];
			$nomor = $r['nomor'];
			$ppn = $r['ppn'];
			$id_kustomer = $r['id_kustomer'];
			$tgl_due_date = date('Y-m-d', strtotime('+45 days', strtotime($tanggal)));
			$id_tagihan = $r['tagih_ke'];

			echo'
			<section class="invoice">
      			<div class="row">
        			<div class="col-xs-12">
          				<h2 class="page-header">
            				<i class="fa fa-truck"></i> PT CESCO LOGISTIK INDONESIA
							<small class="pull-right">Date: '.$r['tanggal'].'<a href="#" id="klikedit-dateinv" data-id="'.date('d-m-Y',strtotime($r['tanggal'])).'"><i class="fa fa-pencil"></i></a></small>
						</h2>
					</div>
				</div>
				<div class="row invoice-info">
					<div class="col-sm-8 invoice-col">
						';

						if($r['custom_invoice'] == 'Y'){
							$custom_alamat = ($r['custom_judul_alamat'] != '' ? $r['custom_judul_alamat'] : 'custom');
							$button_edit_custom_alamat = '<a href="#" id="klikedit-judulalamat" data-id="'.$r['custom_judul_alamat'].'"><i class="fa fa-pencil"></i></a>';
							$nama_kota = '';
							$kota = '';
							$phone = '';
						}else{
							$custom_alamat = $r['alamat'];
							$button_edit_custom_alamat = '';
							$nama_kota = $r['nama_kota'];
							$kota = $r['kota'];
							$phone = $r['telp'];
						}

							echo'
							<address>
								<strong>'.$r['nama_kustomer'].' 
									<a href="module/mod_invoice-customer/invoice-customer_action.php?module=invoice-customer&act=edit-tampil&tampil='.$r['tampil'].'&id_invoice='.$id.'">
									 	 <i class="fa fa-pencil"></i>
									 </a>
									 '.($r['tampil'] == 'Y' ? '<i class="fa fa-eye" title="Tampil"></i>' : '<i class="fa fa-ban" title="Tidak Tampil"></i>').'
									 
								</strong>
								<br>
	            				'.strtoupper($custom_alamat).' '.$button_edit_custom_alamat.'<br>
	            				'.strtoupper($nama_kota).'<br>
							'.strtoupper($kota).'<br>
	            			'.$phone.'
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
          				<b>Invoice #'.$r['nomor'].'</b><a href="#" id="klikedit-noinv" data-id="'.$r['nomor'].'"><i class="fa fa-pencil"></i></a><br>
          				<br>
          				<b>Custom Keterangan Invoice : '.($r['keterangan_no_invoice'] != '' ? $r['keterangan_no_invoice'] : '-') .' </b><a href="#" id="klikedit-ketinv" onclick="editketinv()" data-id="'.$r['keterangan_no_invoice'].'"><i class="fa fa-pencil"></i></a><br>
          				<br>
          				<b>Payment Due: </b><span id="edit-duedate">'.date('d-m-Y',strtotime($r['due_date'])).'</span> <a href="#" id="klikedit-duedate" data-id="'.date('d-m-Y',strtotime($r['due_date'])).'"><i class="fa fa-pencil"></i></a><br>
          				<b>Tagihan ke bank: </b>'.$r['nama_bank']. ' <a href="module/mod_invoice-customer/invoice-customer_action.php?module=invoice-customer&act=edit-bank&bank='.$r['id_bank'].'&id_invoice='.$id.'"><i class="fa fa-pencil"></i></a><br>

          				<b>Type Invoice: </b>'.$r['type_invoice']. ' <a href="module/mod_invoice-customer/invoice-customer_action.php?module=invoice-customer&act=edit-type-invoice&type_invoice='.$r['type_invoice'].'&id_invoice='.$id.'"><i class="fa fa-pencil"></i></a>

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
      			<form action="https://cesco-logistics.com/e-cesco/reportsnew/airwaybill_per_inv_customer.php" target="_blank" id="printawb" method="POST"> </form>
				<form action="reports/PHPExcel/invoice_lampiran.php" id="excelCosting" target="_blank" method="GET">
				<input type="hidden" name="idinvoice" value="'.$id.'">
				<input type="hidden" name="stat" value="costing">
				</form>
      			<input type="hidden" name="id_kustomer" form="tagihkan_baru" value="'.$id_kustomer.'">
	  			<div class="row">
        			<div class="box-body">
						<table id="report" class="table table-striped table-bordered table-hover " width="100%" cellspacing="0" style="white-space:nowrap;">
						<thead>
							<tr>
									<th rowspan="2">#</th>
									<th rowspan="2"><i class="fa fa-print"></i></th>
									<th colspan="4"><center>Data</center></th>
									';
									if($id_kustomer == 106){
										echo'
									<th  rowspan="2">APRC</th>
									<th  rowspan="2">Product (DO Number)</th>
									';
									}
									echo'
									<th colspan="6"><center>Pengirim</center></th>
									<th colspan="6"><center>Penerima</center></th>
									<th colspan="3">Berat</th>
									<th rowspan="2">Heavy Cargo</th>
									<th rowspan="2">Harga/kg</th>
									<th rowspan="2">Harga next kg</th>
									<th rowspan="2">min kg</th>
									<th rowspan="2" width="150px"><center>Action#</center></th>
									<th rowspan="2">Add Charge</th>
									<th rowspan="2">Remarks</th>
									<th rowspan="2">Biaya Khusus</th>
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
									<th>KG</th>
									<th>Volume</th>
									<th>Chargerable weight</th>
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
						ifnull(md.biaya_tambahan_agent_transit,0) as biaya_tambahan_agent_transit,
                        (ifnull(a.kunci_total_agent,0) + ifnull(a.kunci_total_agent_deliv,0) + ifnull(a.kunci_total_agent_pickup,0) + ifnull(md.kunci_total_agent_transit,0) ) as total_agent,
						ifnull(a.kunci_total_agent,0) as kunci_total_agent,
						ifnull(a.kunci_total_agent_deliv,0) as kunci_total_agent_deliv,
						ifnull(a.kunci_total_agent_pickup,0) as kunci_total_agent_pickup,
						ifnull(md.kunci_total_agent_transit,0) as kunci_total_agent_transit,
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
						left join manifest_detail md on md.id_pengiriman = a.id_pengiriman and md.kunci_total_agent_transit is not null
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

				  //echo $cari;

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

						$countCostOut = 0;
						$countCostIn = 0;
						$countCostDeliv = 0;
						$countCostPick = 0;
						$countCostAgent = 0;
						$lockPrintInvoice = false;
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

                            $sum_total = $sum_total+$harga_lock+$row['biaya_packing']+$row['biaya_asuransi'];
                            $harga_total = $harga_lock+$row['biaya_packing']+$row['biaya_asuransi'];

                            if($row['lokasi_asal']=='Jakarta Selatan' || $row['lokasi_asal']=='Jakarta Timur' || $row['lokasi_asal']=='Jakarta Barat' ||		$row['lokasi_asal']=='Jakarta Utara' || $row['lokasi_asal']=='Jakarta Pusat'){
								$lokasi_asal = 'Jakarta';
							}else{
								$lokasi_asal = $row['lokasi_asal'];
							}

							if($row['lokasi_tujuan']=='Jakarta Selatan' || $row['lokasi_tujuan']=='Jakarta Timur' || $row['lokasi_tujuan']=='Jakarta Barat' ||		$row['lokasi_tujuan']=='Jakarta Utara' || $row['lokasi_tujuan']=='Jakarta Pusat'){
								$lokasi_tujuan = 'Jakarta';
							}else{
								if ($row['service_id'] == 6) $lokasi_tujuan = $row['negara'];
								else $lokasi_tujuan = $row['lokasi_tujuan'];
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
							


                            $noRate = ($row['harga_perkg'] > 0) ? '' : '<button type="button" class="btn btn-xs btn-success" title="Belum Ada Rate"><i class="fa fa-exclamation-triangle"></i> </button>';

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

							/*
                            $lockPrintInvoice = ($row['confirm_agent_pu'] == 0) ? true : false;
                            $lockPrintInvoice = ($row['confirm_agent_deliv'] == 0) ? true : $lockPrintInvoice;
                            $lockPrintInvoice = ($row['confirm_agent_transit'] == 0) ? true : $lockPrintInvoice;
                            $lockPrintInvoice = ($row['confirm_agent_intra'] == 0) ? true : $lockPrintInvoice;
							*/

							if ($row['intercity'] == 0){ //001

								if ($row['pickup_by_agent'] == $row['delivery_by_agent']){

									$totalAgent = $totalAgentIntra;
									$totalAgentDeliv = $totalAgentIntra;

									/*if ($row['jumlah_cabang'] == 0 && $row['jumlah_agent'] > 0){
										if ($row['inter_asal'] != 'Y' && $row['inter_tujuan'] != 'Y'){
											if ($total_agent == 0){
												$cekCross++;
												$cekCostAgent = $btnAlert;

												$countCostAgent++;
											}
										}
									}*/

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

												$countCostDeliv++;

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

										if ($row['jumlah_cabang'] > 0 && $row['jumlah_agent'] == 0){
											if ($row['costing_delivery_fee'] == 0) {
												$cekOut++;
												$cekCostDeliv = $btnAlert;

												$countCostDeliv++;
											}

										}else{
											//permasalahan
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

									$countCostDeliv++;
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
								//$sum_total = $sum_total + $totalAgentAfterDiskon;
							}


							//$disableTagihkan = (($cekCostOut == "" && $cekCostIn == "" && $cekCostDeliv == "" && $cekCostPick == "" && $cekCostAgent == "") || $u['level'] == 'admin') ? '' : ' disabled ';
							$disableTagihkan = '';

							$lokasiAsalShow = ($row['id_kecamatan_asal']>0 ? strtoupper($row['kec_asal']).', ' : '').strtoupper($lokasi_asal);
							$lokasiTujuanShow = ($row['id_kecamatan_tujuan']>0 ? strtoupper($row['kec_tujuan']).', ' : '').strtoupper($lokasi_tujuan);

							echo'
							<tr>
								<td width="20px"> <input type="checkbox" form="keluarkanawb" class="checkdeleteawb" name="deleteawb[]" value="'.$row['id_pengiriman'].'">
								<input type="checkbox" '.$disableTagihkan.' form="tagihkan_baru" class="tagihkan_baru" data-id="'.$row['harga_lock'].'" name="tagihkan_baru[]" value="'.$row['id_pengiriman'].'"> </td>
								<td><input type="checkbox" form="printawb"  class="printawb" name="idprintawb[]" value="'.$row['id_pengiriman'].'"></td>
								<td width="20px">'.$row['tanggal'].'</td>
								<td width="20px">'.$row['airwaybill_kode'].($row['collie'] == 1 
													 && $row['intercity'] != 1 
													 && $row['berat_real'] > 50 
													 && isset($row['service_id']) 
													 && in_array($row['service_id'], [1, 2, 3]) 
 														? '<span style="color:red;"><b>***</b></span>' : '').'</td>
								<td width="20px">'.($row['jenis_barang'] == "" ? $row['komoditi'] : $row['komoditi'] ).', '.  strtoupper($row['jenis_barang']).', '.strtoupper($row['catatan']);
								if($row['jenis_komoditi'] == 1 and $row['id_kustomer'] == 13){
									echo '<a href="#" onclick="search_jenisbarang(this);" id="searchjenisbarang" class="searchjenisbarang" data-id="'.$row['id_kustomer'].'" data-type="'.$row['jenis_barang'].'" data-toggle="modal" data-target="#modal-jenisbarang"> <i class="fa fa-search"></i> </a> ';
								}
								echo '

								</td>
								<td width="20px">'.strtoupper($row['service']).'</td>
								';
								/*	if($id_kustomer == 106){
										 $produk = '';
										$q_asus =   "select aprc,product from asus_detail_catatan where id_pengiriman=$row[id_pengiriman] ";
										$query_asus = $mysqli->query($q_asus);
										echo '<td>';
											while($r_asus = $query_asus->fetch_assoc()){
												echo $r_asus['aprc'].'<br>';
												$produk .= $r_asus['product'].' ';
											}
											echo '</td>';

										echo '<td>';
											echo $produk;
											echo '</td>';
											}	*/
								if($id_kustomer == 106){
									echo '<td>'.$row['aprc_list'].'</td>';
										echo '<td>'.$row['product_list'].'</td>';
								}								
								echo '
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


								

								';

								 $j=0;
								 $tb = 0;

								 $query_detail_collie = "select * from detail_collie where id_pengiriman=$row[id_pengiriman] order by koli_ke asc ";
								 
								 $query_d = $mysqli->query($query_detail_collie);
								 $ketemu = $query_d->num_rows;

								 if($ketemu > 0){
								 	echo '<td width="20px">';
									 	while($roww = $query_d->fetch_assoc()){
										 	echo '
										 		
												 	
												 	'.$roww['berat'].'
												 	 <br>
												 	
												 
										 	';
										 	$j=$j+1;
										 	//$tb=$tb+$roww['berat_real'];
										}
									echo '</td>';

									echo '<td width="20px">';
									$qu = $mysqli->query($query_detail_collie);
									 	while($roww = $qu->fetch_assoc()){
				 //for($i=1;$i<41;$i++){

										 	if($row['intercity'] == 1){
							              			$beratvol = $roww['p']*$roww['l']*$roww['t']/4000;
							              	}
							             	else{
							           			if($row['service_id'] == 5){
							           				$beratvol = $roww['p']*$roww['l']*$roww['t']/4000;
							           			}
							              		else{
							              			$beratvol = $roww['p']*$roww['l']*$roww['t']/6000;
							              		}
							              	}

										 	echo $roww['p'].'x'.$roww['l'].'x'.$roww['t'].'( '.$beratvol.' )'.'<br>';
										 	$j=$j+1;
										 	//$tb=$tb+$roww['berat_real'];
										}
									echo '</td>';



									
								}else{
									echo '<td width="20px">';
										echo $row['berat_real'];
									echo '</td>';

									echo '<td width="20px">';
									echo ($row['length'] == '' ? 0 : $row['length']).'x'.($row['width'] == '' ? 0 : $row['width']).'x'.($row['height'] == '' ? 0 : $row['height']).'('. number_format($row['volume'],2).' ) ';
									echo '</td>';
								}

								echo 

								'


								<td width="20px">'.ceil($row['berat_finish']).'</td>
								<td width="20px">'.($row['heavy_cargo'] == 1 ? 'Y' : 'N').'</td>
								';
								if($row['harga_pengiriman'] != ''){
									echo '<td>-</td>';
								}else{
									echo'<td><span class="harga_perkg'.$row['id_rate_customer'].'">'.number_format($row['harga_perkg']).' '.$noRate.'</span>';

									if ($id_user == 4 || $id_user == 5 || $id_user == 8 || $id_user == 134){

											$dataTujuan = ($row['id_kecamatan_tujuan'] > 0) ? $row['id_kecamatan_tujuan'] : $row['id_kota_tujuan'];
											$dataTujuan = ($dataTujuan > 0) ? $dataTujuan : $row['id_lokasi_tujuan'];

											echo ' <button type="button" class="btn btn-default btn-xs custom_harga_kg" data-toggle="modal" data-target="#modal_harga_kg" data-id="'.$row['id_pengiriman'].'" data-id-rate="'.$row['id_rate_customer'].'" data-asal="'.$lokasiAsalShow.'" data-tujuan="'.$lokasiTujuanShow.'" data-kecamatan-asal="'.$row['id_kecamatan_asal'].'" data-kecamatan-tujuan="'.$dataTujuan.'" data-kustomer="'.$row['namakustomer'].'"> <i class="fa fa-pencil text-orange"></i></button>'. ($row['harga_kunci'] != '' ? '*' : ''). '';

									}

									echo '</td>';

								}

								$nextKg = ($row['next_kg']>0) ? number_format($row['next_kg']) : '';
                                echo'<td><span class="harga_nextkg'.$row['id_rate_customer'].'">'.$nextKg.'</span></td>';

								if($row['harga_pengiriman'] != ''){
									echo '<td>-</td>';
								}else{
									echo '<td>'.$row['min_kg'].'</td>';
								}
								echo '<td>
									<center>

										';
										if($nomor == 'DRAFT'){
											if($row['id_rate_customer'] != ''){
												/*<div class="btn-group">
												<button type="button" class="btn btn-default btn-xs airwaybill_detail" data-toggle="modal" data-target="#airwaybill_detail" data-id="'.$row['id_pengiriman'].'"><i class="fa fa-pencil text-orange"></i></button>*/

												echo'<button type="button" class="btn btn-default btn-xs custom_price_edit" data-toggle="modal" data-target="#modal_price_edit" data-id="'.$row['id_rate_customer'].'">'.$row['id_rate_customer'].'<i class="fa fa-pencil text-orange"></i></button>';
											}
											else{
												echo'<button type="button" class="btn btn-default btn-xs custom_price_insert" data-toggle="modal" data-target="#modal_price_insert" data-id="'.$row['id_pengiriman'].'"><i class="fa fa-plus-square text-orange"></i></button>';
											}
										}
										echo'
										</div>
									</center>
								</td>
								<td align="right" align="right"><a href="#" class="cust_tambahan" data-type="text" data-pk="'.$row['id_pengiriman'].'">'.number_format($row['biaya_tambahan']).'</a></td>
								<td><a href="#" class="ket_biaya" data-type="text" data-pk="'.$row['id_pengiriman'].'">'.$row['keterangan_biaya'].'</a></td>
								<td>'.($row['pembayaran_khusus'] == 1 ?  '<b>Y</b>' : 'N').'</td>
								<td>'
										.number_format($harga_lock).

										'
										';
										//if($nomor == 'DRAFT'){
											echo '
											<button type="button" class="btn btn-default btn-xs custom_total_fix" data-toggle="modal" data-target="#modal_total_fix" data-id="'.$row['id_pengiriman'].'"> <i class="fa fa-pencil text-orange"></i></button>'. ($row['harga_kunci'] != '' ? '*' : ''). '
											';
										//}

								echo '
								</td>

								<td align="right" align="right">'.strtoupper($row['packing_kayu']).' <a href="#" class="cust_packing" data-type="text" data-pk="'.$row['id_pengiriman'].'">'.number_format($row['biaya_packing']).'</a></td>
								<td align="right" align="right">'.$row['asuransi'].' <a href="#" class="cust_asuransi" data-type="text" data-pk="'.$row['id_pengiriman'].'">'.number_format($row['biaya_asuransi']).'</a></td>

								<td>'
										.number_format(round($harga_total)).

										'
										';
										//if($nomor == 'DRAFT'){
											echo '
											<button type="button" class="btn btn-default btn-xs custom_total_fix" data-toggle="modal" data-target="#modal_total_fix" data-id="'.$row['id_pengiriman'].'"> <i class="fa fa-pencil text-orange"></i></button>'. ($row['harga_kunci'] != '' ? '*' : ''). '
											';
										//}

								echo '
								</td>
								<td>'.number_format(round($row['costing_pickup_fee'])).' '.$cekCostPick. '</td>
								<td>'.number_format(round($row['costing_pickup_toll'])). '</td>
								<td>'.number_format(round($row['costing_pickup_parking'])). '</td>	
								<td>'.number_format(round($row['costing_men_power'])). '</td>
								<td>'.number_format(round($delivery_fee_after_discount)).' '.$cekCostDeliv. '</td>
								<td>'.number_format(round($row['costing_delivery_toll'])). '</td>
								<td>'.number_format(round($row['costing_delivery_parking'])). '</td>
								<td>'.number_format(round($row['costing_delivery_menpower'])). '</td>
								<td>'.number_format(round($row['costing_outbound_fee'])).' '.$cekCostOut. '</td>
								<td>'.number_format(round($row['costing_outbound_toll'])). '</td>
								<td>'.number_format(round($row['costing_outbound_smu'])). '</td>
								<td>'.number_format(round($row['costing_outbound_handling'])). '</td>
								<td>'.number_format(round($row['costing_outbound_parking'])). '</td>
								<td>'.number_format(round($row['costing_outbound_menpower'])). '</td>
								<td>'.number_format(round($row['costing_inbound_fee'])).' '.$cekCostIn. '</td>
								<td>'.number_format(round($row['costing_inbound_toll'])). '</td>
								<td>'.number_format(round($row['costing_inbound_smu'])). '</td>
								<td>'.number_format(round($row['costing_inbound_handling'])). '</td>
								<td>'.number_format(round($row['costing_inbound_parking'])). '</td>
								<td>'.number_format(round($row['costing_inbound_menpower'])). '</td>
								<td>'.number_format(round($totalAgenPickup)).'</td>
								<td>'.number_format(round($totalAgentTransit)).'</td>
								<td>'.number_format(round($totalAgentDeliv)).'</td>
								<td>'.number_format(round($totalAgentAfterDiskon)).' ('.$diskon.'%) '.$cekCostAgent.$row['tagih_inv_agent'].'</td>
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
					<a href="#" class="btn btn-xs btn-success" id="checkall"><i class="fa fa-check"> </i></a>
					<a href="#" class="btn btn-xs btn-success" id="checkalltengah" onclick="check_all_tengah();"><i class="fa fa-check"> </i></a>
					<a href="#"  class="btn btn-xs btn-success" id="checkallprint" onclick="check_print_all();"><i class="fa fa-check"> </i> <i class="fa fa-print"> </i></a>




        			<input class="btn btn-danger" type="submit" form="keluarkanawb" value="keluarkan awb">
        			<input class="btn btn-success" type="submit" form="printawb" value="Print Selected">
					';

					if ($kunci != '' && $id_user != 3 && $id_user != 8){
						echo "<script>
						function cekKunci(){
							setTimeout(function() {
								$('.editable').unbind();
								$('.custom_total_fix').remove();

							}, 10);
						}
						</script>";
					}else{
							echo "<script>
							function cekKunci(){
								console.log(0);

							}
							</script>";
						}

					$key		= "asdf$1011";
					$encrypt	= "$_GET[id]";
					$id_invoice	= base64_decrypt($encrypt,$key);

					echo '<input class="btn btn-primary" type="submit" form="tagihkan_baru" value="TAGIHKAN">';

					if ($cek002 == 0 && $cekOut == 0 && $cekIn == 0 && $cekCross == 0 && $count_profit_loss == 0){
						//echo '<input class="btn btn-primary" type="submit" form="tagihkan_baru" value="TAGIHKAN">';
						//echo ' <button class="btn btn-success" type="submit" form="excelCosting"><i class="fa fa-file-excel-o"></i> Costing Excel</button>';
        			}else{
						echo ' <button class="btn btn-success" type="submit" form="excelCosting"><i class="fa fa-file-excel-o"></i> Costing Excel</button>';
					}

					echo '
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

						echo '
							<table class="table tablebawah">
								<tr>
                					<th style="width:65%">Preview Total (Checklist)</th>
                					<td><div id="preview">0</div></td>
              					</tr>
								<tr>
                					<th style="width:65%">'.($r['judul_awal']).'<a href="#" id="edit-judulinv" data-val="'.($r['judul_awal']).'" data-id="$id_invoice"><i class="fa fa-pencil"></i></a></th>
              					</tr>
								';

								$encrypt = "$_GET[id]";
								$id_inv = base64_decrypt($encrypt,$key);

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

                                $tambahan_manual = ($r['biaya_tambahan'] > 0 || $r['biaya_tambahan'] < 0   ? $r['biaya_tambahan'] : 0);


              					$pajak1 = $ppn/100*($tambahan_kena_pajak+$sum_total+$tambahan_manual);

								$test_pajak = floor($ppn/100* ($sum_total+$tambahan_kena_pajak+$tambahan_manual));

								$qp10 = "select sum(biaya_tambahan) as pajak from invoice_detail where id_invoice='".$id_inv."' and pajak='10'";

                                $pajak10 = $mysqli->query($qp10);
                                $tambahan_kena_pajak10 = 0;
                                while($p10 = $pajak10->fetch_assoc()){
                                    $tambahan_kena_pajak10 = $p10['pajak'];
                                }

              					$pajak_packing = floor(11/100*($sum_packing+$tambahan_kena_pajak10));
              					//$total_semua = $sum_total+$tambahan_manual+$biayaTambahan+$test_pajak+$sum_asuransi;
              					$total_semua = $sum_total+$tambahan_manual+$biayaTambahan+$test_pajak;


									echo '
									<tr>
	                					<th style="width:65%">'.($r['judul_subtotal'] == null ? 'subtotal' : $r['judul_subtotal']).'<a href="#" id="edit-subtotal" data-val="'.($r['judul_subtotal'] == null ? 'subtotal' : $r['judul_subtotal']).'" data-id="$id_invoice"><i class="fa fa-pencil"></i></a></th>
	                					<td style="text-align:right;"> Rp '. ($r['subtotal'] > 0 ? number_format($r['subtotal']) : number_format($sum_total)) .'</td>
	              					</tr>

	              					<tr>
	              						';
	                						if($tambahan_manual > 0 || $tambahan_manual < 0){
	                							echo '<th style="width:65%">

	                								'.$r['keterangan_biaya_tambahan']. '  
	                								<a href="#" onclick="edit_biaya_manual()" id="open_edit_biaya_manual" data-val="'.$r['keterangan_biaya_tambahan'].'" data-id="'.$id_invoice.'" data-number="'.$tambahan_manual.'"><i class="fa fa-pencil"></i></a>

	                							</th>';
	                						}else{
	                							if($r['nomor'] == 'DRAFT'){
	                								echo 	
		                							'<th style="width:65%">
		                								<button type="button" class="btn btn-default btn-xs" id="open_tambah_biaya_manual" onclick="biaya_manual()"  data-id="'.$id_inv.'"> biaya tambahan<i class="fa fa-plus text-orange"></i></button>
		                							</th>';
	                							}else{
	                								echo '<th style="width:65%">&nbsp;</th>';
	                							}
	                							
	                						}
	                						
	                					
	                					echo '
	                					';

	                					if($r['nomor'] == 'DRAFT'){	   
	                						if($tambahan_manual > 0 || $tambahan_manual < 0){
	                							echo '<td style="text-align:right;"> Rp '.number_format($tambahan_manual).'

	                							</td>';
	                						}else{
	                							echo '<td style="text-align:right;">&nbsp;</td>';
	                						}     						
	                						
	                					}
	                					else{
	                						if($tambahan_manual > 0 || $tambahan_manual < 0){
	                							echo '<td style="text-align:right;"> Rp '. number_format($tambahan_manual) .'</td>';
	                						}else{
	                							echo '<td style="text-align:right;">&nbsp;</td>';
	                						}
	                						
	                					}
	                					echo '

	              					</tr>

									<tr>
										<th>PPN ('.$ppn.'%) <a href="#" id="edit-ppn" data-val="'.($r['ppn']).'" data-id="$id_invoice"><i class="fa fa-pencil"></i></a>
											
											<a href="module/mod_invoice-customer/invoice-customer_action.php?module=invoice-customer&act=edit-tampil-ppn&tampil_ppn='.$r['tampil_ppn'].'&id_invoice='.base64_encrypt($id_inv,$key).'">
									 	 '.($r['tampil_ppn'] == 'Y' ? '<i class="fa fa-eye" title="Tampil"></i>' : '<i class="fa fa-ban" title="Tidak Tampil"></i>').'
									 </a>
									 

										</th>
										<td style="text-align:right;">Rp '.($r['ppn_subtotal'] > 0 && $ppn > 0 ? number_format($r['ppn_subtotal']) : number_format($test_pajak)). '</td>

									</tr>
	              				';



              				$qt = "select * from invoice_detail where id_invoice='".$id_inv."'";

                            $tambahan = $mysqli->query($qt);

                            while($t = $tambahan->fetch_assoc()){



                                //if ($r['total_all'] > 0) $r['total_all'] = $r['total_all'] + $t['biaya_tambahan'];
                                //else $total_semua = $total_semua + $t['biaya_tambahan'];

              				echo'
              					<tr>

									<th>'.$t['detail_biaya_tambahan'].

									'<a href="#" id="hapus-biayatambahan" data-val="'.($t['biaya_tambahan']).'" data-id="'.($t['id']).'"><i class="fa fa-close"></i></a>

									

									</th>
                					<td style="text-align:right;">'.number_format($t['biaya_tambahan']).'</td>
              					</tr>';
              				}
              				//if($nomor == 'DRAFT'){
								echo'
								<tr>
									<td colspan="2">
										<button type="button" class="btn btn-default btn-xs " id="open_tambah_biaya"  data-id="'.$id_inv.'"> tambah biaya<i class="fa fa-plus text-orange"></i></button>
									</td>
								</tr>

								';
							//}
								echo number_format($count_profit_loss);


								if($r['subtotal'] > 0 && $r['inv_packing_kayu'] > 0){
									echo'

	              					<tr>
										<th>Packing Kayu</th>
	                					<td style="text-align:right;">Rp '.($r['subtotal'] > 0 ? number_format($r['inv_packing_kayu']) : number_format($sum_packing)). '</td>

	              					</tr>
	              					<tr>
										<th>PPN 10%</th>
	                					<td style="text-align:right;">Rp '.($r['subtotal'] > 0 ? number_format($r['ppn_packing']) : number_format($pajak_packing)). '</td>

	              					</tr>';
	              				}
	              				if($r['inv_asuransi'] > 0){
	              					echo '
	              					<tr>
										<th>Asuransi</th>
	                					<td style="text-align:right;">Rp '.($r['subtotal'] > 0 ? number_format($r['inv_asuransi']) : number_format($sum_asuransi)).'</td>

	              					</tr>
	              					 ';	
	              				} 
	              				echo '
	              					<tr>
	                					<th>Total</th>
	                					<td style="text-align:right;">Rp '.($r['total_all'] > 0  ? number_format($r['total_all']) : number_format($total_semua)).'</td>
	              					</tr>
            				</table>
          				</div>
        			</div>
				</div>
      			<div class="row no-print">
        			<div class="col-xs-12">';
        				$key		= "asdf$1011";

						if (($cek002 == 0 && $cekOut == 0 && $cekIn == 0 && $cekCross == 0 && $nomor != 'draft') || $u['level'] == 'admin'){
							echo '<a href="../e-cesco/reports/invoice.php?idinvoice='.base64_encrypt($id_inv,$key).'" target="_blank" class="btn btn-default"><i class="fa fa-print"></i> Print Invoice</a>';
						}else{
							echo '<a href="#" disabled class="btn btn-default"><i class="fa fa-print"></i> Print Invoice'.$nomor.'</a>';
						}


          				$key		= "asdf$1011";
						$encrypt	= "$_GET[id]";
						$id 		= base64_decrypt($encrypt,$key);

          				$query_bottom 		= $mysqli->query("select case when a.status='draft' then 'DRAFT' else a.nomor end as nomor from invoice a left join kustomer b on b.id_kustomer=a.id_kustomer left join kota c on c.id_kota=b.kota left join rekening_cesco d on a.bank=d.id where id_invoice='$id'");

						$b= $query_bottom->fetch_assoc();
          				if($b['nomor'] == 'DRAFT'){

							echo '<a href="#" disabled class="btn btn-default"><i class="fa fa-print"></i> Print lampiran</a>';
          				}
          				else{

          					echo '<a href="../e-cesco/reports/invoice_lampiran.php?idinvoice='.$id_inv.'" target="_blank" class="btn btn-default"><i class="fa fa-print"></i> Print lampiran</a>';
          				}



          				 $cek = "SELECT
          							a.airwaybill_kode,
									if(id_kota_tujuan>0,ifnull(g.min_kg,h.min_kg),ifnull(b.min_kg,c.min_kg)) as min_kg,
									if(id_kota_tujuan>0,ifnull(g.next_kg,h.next_kg),ifnull(b.next_kg,c.next_kg)) as min_kg,
									if(id_kota_tujuan>0,ifnull(g.harga,h.harga),ifnull(b.harga,c.harga)) as min_kg
								FROM pengiriman a
								left join rate_customer b ON b.id_kustomer=a.id_kustomer and b.id_lokasi_asal=a.lokasi_asal and b.id_lokasi_tujuan=a.lokasi_tujuan and b.id_pelayanan=a.service and b.komoditi=a.komoditi and b.loc_code=a.loc_code
								left join rate_customer c ON c.id_kustomer=a.id_kustomer and c.id_lokasi_asal=a.lokasi_asal and c.id_lokasi_tujuan=a.lokasi_tujuan and c.id_pelayanan=a.service and c.komoditi=a.komoditi
								left join rate_customer g ON g.id_kustomer=a.id_kustomer and g.id_lokasi_asal=a.id_kota_asal and g.id_lokasi_tujuan=a.id_kota_tujuan and g.id_pelayanan=a.service and g.komoditi=a.komoditi and g.loc_code=a.loc_code

								left join rate_customer h ON h.id_kustomer=a.id_kustomer and h.id_lokasi_asal=a.id_kota_asal and h.id_lokasi_tujuan=a.id_kota_tujuan and h.id_pelayanan=a.service and h.komoditi=a.komoditi


								where a.id_invoice = $id_inv and (b.harga is null and c.harga is null and a.harga_pengiriman is null and g.harga is null and h.harga is null) and a.status !=7
								group by a.id_pengiriman" ;

						//$rowcek=mysqli_query($con, $cek);
						 //$row_cek =mysqli_num_rows($rowcek);
						 $row_cek = $cekHarga;



						 //cek intercity

						  $cek_intercity = "SELECT
          							airwaybill_kode


								FROM pengiriman a

								where a.id_invoice = $id_inv and costing_delivery_fee and costing_pickup_fee is null and x_intercity(lokasi_asal) = 'y' and x_intercity(lokasi_tujuan) = 'y'
								and status != 7
								group by a.id_pengiriman" ;

						$rowcekintercity=mysqli_query($con, $cek_intercity);

						$cek_inter = "";
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

								$cek_out = "";
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
								$cek_in = '';
								while($test_in=mysqli_fetch_assoc($rowcekinbound)){
									$cek_in = $test_in['airwaybill_kode'].' in ';
								}
								$rc_in =mysqli_num_rows($rowcekinbound);

							//cek  inbound

						  $cek_inv = "SELECT
          							airwaybill_kode
								FROM pengiriman a
								where a.id_invoice = $id_inv
								and kunci_total_agent is null
								and status != 7
								group by a.id_pengiriman" ;

								$rowcekinv=mysqli_query($con, $cek_inv);
								$cek_inv ='';
								while($test_inv=mysqli_fetch_assoc($rowcekinv)){
									$cek_inv = $test_inv['airwaybill_kode'].' inv ';
								}
								$rc_inv =mysqli_num_rows($rowcekinv);

							$cek_kayu_asu = "SELECT
          							airwaybill_kode
								FROM pengiriman a
								where a.id_invoice = $id_inv and id_kustomer != 103
								and ( (packing_kayu = 2 and biaya_packing = 0) or (asuransi='Y' and biaya_asuransi = 0) )
								and status != 7
								group by a.id_pengiriman" ;

								$rowcekpackasu=mysqli_query($con, $cek_kayu_asu);
								$cek_pack = "";
								while($test_packasu=mysqli_fetch_assoc($rowcekpackasu)){
									$cek_pack = $test_packasu['airwaybill_kode'].' pack asu ';
								}
								$rc_pack =mysqli_num_rows($rowcekpackasu);


								echo "
								<script type='text/javascript'>
									function testfunction() {
										alert('$cek_inter');
										alert('$cek_out');
										alert('$cek_in');
										alert('$cek_inv');
										alert('$cek_pack');
									}

								</script>";




						//<a href="../e-cesco/module/mod_invoice-customer/invoice-customer_action.php?module=invoice-customer&act=nomor&idinvoice='.$id_inv.'"  class="btn btn-primary pull-right " '.($r['nomor'] == 'DRAFT' ? ($row_cek == 0 ? 'enabled' : 'disabled') : 'disabled' ).'>'.$r['nomor'].$row_count.$row_cek.' Buat No Invoice </a>

                        echo $kunciForm;

						/* $sql = $mysqli->query($cari);
						$row_count= $sql->num_rows;

						while($row = $sql->fetch_assoc()){
							echo '<input type="hidden" name="harga_pengiriman['.$row['id_pengiriman'].']" form="kunciform" value="'.$row['harga_lock'].'">
										';
						}*/

						$sub_total_yang_dipakai = ($r['subtotal'] > 0 ? $r['subtotal'] : $sum_total);
						$cek_profit_loss = ($count_profit_loss > 0 ? ' (Ada profit yang minus) ' : '');
						//$profit_loss_disabled = ($sum_profit_loss <= 0 ? 'disabled' : '');


						if($count_profit_loss > 0 && $r['nomor'] == 'DRAFT'){
							if($r['approval_jika_minus'] != 'Y'){
								if($u['level'] == 'admin'){
									echo'

				          				<button type="button" class="btn btn-success" onclick="window.location.href = \'module/mod_invoice-customer/invoice-customer_action.php?module=invoice-customer&act=approval_jika_minus&idinvoice='.$encrypt.'\';"> approve tagihan minus  </button>

				          				';	
								}else{
									echo'

				          				<button type="button" class="btn btn-success" disabled>  tagihan minus, minta approval ke bu galuh/pak toni '.$cek_profit_loss.'  </button>

				          				';	
								}
							}	else{
								echo'

		          				<button type="button" class="btn btn-success" id="save" onclick="window.location.href = \'module/mod_invoice-customer/invoice-customer_action.php?module=invoice-customer&act=nomor&subtotal='.$sum_total.'&ppn='.$test_pajak.'&total_semua='.$total_semua.'&asuransi=0&tambahan='.$tambahan_manual.'&idinvoice='.$id_inv.'\';" '.($r['nomor'] == 'DRAFT' ? ($cekHarga == 0 && $rc_pack == 0  ? 'enabled' : 'disabled') : 'disabled' ).'>  Buat No Invoice ' . $cekHarga.', '.$rc_intercity.', '. $rc_out.', '.$rc_in.', '.$rc_inv.','. $rc_pack.' </button>

		          				';
							}

								
							
						}else{
							echo'

	          				<button type="button" class="btn btn-success" id="save" onclick="window.location.href = \'module/mod_invoice-customer/invoice-customer_action.php?module=invoice-customer&act=nomor&subtotal='.$sum_total.'&ppn='.$test_pajak.'&total_semua='.$total_semua.'&asuransi=0&tambahan='.$tambahan_manual.'&idinvoice='.$id_inv.'\';" '.($r['nomor'] == 'DRAFT' ? ($cekHarga == 0 && $rc_pack == 0  ? 'enabled' : 'disabled') : 'disabled' ).'>  Buat No Invoice ' . $cekHarga.', '.$rc_intercity.', '. $rc_out.', '.$rc_in.', '.$rc_inv.','. $rc_pack.' </button>

	          				';
						}

	          				


          				if ($r['nomor'] != 'DRAFT'){
          					echo '<button type="submit" form="kunciform" class="btn btn-success" id="kunci" Value="Kunci Harga"> Kunci Harga </button>';
          				}
          				if ($r['nomor'] == 'DRAFT'){
          				echo 	'<button class="btn btn-success"  onclick="testfunction()">Cek Costing</button>';
          				}

						if ($kunci == ''){
							echo ' <a href="#" disabled class="btn btn-primary pull-right"><i class="fa fa-print"></i> Invoice PDF Lampiran</a>
							<a href="#" disabled class="btn btn-primary pull-right"><i class="fa fa-print"></i> Invoice PDF</a>
							';
						}else{

							echo ' <a href="reports/reportpdf/invoicecustomerlampiran.php?idinvoice='.$id_inv.'" target="_blank" class="btn btn-primary pull-right"><i class="fa fa-print"></i> Invoice PDF Lampiran</a>
							<a href="reports/reportpdf/invoicecustomer.php?idinvoice='.$id_inv.'" target="_blank" class="btn btn-primary pull-right"><i class="fa fa-print"></i> Invoice PDF</a>
							';
						}
						//echo ' <a href="../e-cesco/reportsnew/airwaybill_per_inv_customer.php?id='.$id_inv.'"  target="_blank" class="btn btn-primary pull-right"><i class="fa fa-print"></i> Print All AWB</a>';


					

							if($b['nomor'] == 'DRAFT'){
								
								
									echo ' <a href="reports/PHPExcel/invoice_lampiran.php?idinvoice='.$id_inv.'" target="_blank" class="btn btn-primary pull-right"><i class="fa fa-print"></i>Print Excel</a>';
								

							}
							else{
								echo ' <a href="reports/PHPExcel/invoice_lampiran.php?idinvoice='.$id_inv.'" target="_blank" class="btn btn-primary pull-right"><i class="fa fa-print"></i>Print Excel</a>';
							}

						
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
			<div id="modal_jenisbarang" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
							<h4 class="modal-title" id="myModalLabel">Jenis barang</h4>
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
                    						<input type="checkbox" name="pajak" id="pajak" value="Ya" checked> pajak 1.1% ?
                  						</label>
                					</div>
									<div class="checkbox">
                  						<label>
                    						<input type="checkbox" name="pajak11" id="pajak11" value="11"> pajak 11% ?
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

			<div id="modal_tambah_biaya_manual" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
							<h4 class="modal-title" id="myModalLabel">Biaya Tambahan</h4>
						</div>
						<div class="modal-content">
							<div class="modal-body">

								<form action="'.$aksi.'?module=invoice-customer&act=tambah-biaya-manual" method="POST">
								<input type="hidden" name="id_invoice" id="idinvoice" class="form-control" value="" />
									<div class="form-group" style="padding-bottom: 25px;">
										<label class="col-md-3 control-label">Detail biaya:</label>
										<div class="col-md-8">
											<input type="text" name="detail_biaya" class="form-control" value="" placeholder="contoh biaya pickup" required/>
										</div>
									</div>
									<div class="form-group" style="padding-bottom: 25px;">
										<label class="col-md-3 control-label">biaya</label>
										<div class="col-md-8">
											<input type="number" name="biaya"  class="form-control" value="0" required />
										</div>
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

			<div id="modal_edit_biaya_manual" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
							<h4 class="modal-title" id="myModalLabel">Edit Biaya Tambahan</h4>
						</div>
						<div class="modal-content">
							<div class="modal-body">

								<form action="'.$aksi.'?module=invoice-customer&act=edit-biaya-manual" method="POST">
								<input type="hidden" name="id_invoice" id="idinvoice" class="form-control" value="" />
									<div class="form-group" style="padding-bottom: 25px;">
										<label class="col-md-3 control-label">Detail biaya:</label>
										<div class="col-md-8">
											<input type="text" id="keterangan_biaya_manual" name="detail_biaya" class="form-control" value="" placeholder="contoh biaya pickup" required/>
										</div>
									</div>
									<div class="form-group" style="padding-bottom: 25px;">
										<label class="col-md-3 control-label">biaya</label>
										<div class="col-md-8">
											<input type="number" id="jumlah_biaya_manual" name="biaya"  class="form-control" value="0" required />
										</div>
									</div>
									
									<div class="modal-footer">
										<button class="btn btn-success" type="submit">Edit</button>
										<a class="btn btn-danger" href="'.$aksi.'?module=invoice-customer&act=hapus-biaya-manual&id_invoice='.$id_invoice.'" >Hapus Biaya</a>
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

			<div id="modal_editalamat" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
							<h4 class="modal-title" id="myModalLabel">Edit Judul Alamat</h4>
						</div>
						<div class="modal-content">
							<div class="modal-body">
								<form action="'.$aksi.'?module=invoice-customer&act=edit-alamat" method="POST">
									<input type="hidden" name="id_invoice" class="form-control" value="'.$id_invoice.'" />
									<div class="form-group">
										<label class="col-md-3 control-label">Keterangan invoice <span class="required">* </span></label>
										<div class="col-md-9">
											
												<input type="text" class="form-control" id="judulalamat" name="judul_alamat" value="'.$r['custom_judul_alamat'].'">
											
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
											<a class="btn btn-sm btn-primary" id="cek_inv" onclick="cek_inv();">cek invoice</a><br>
											<input type="hidden"  class="form-control" id="hasil_cek_inv" name="hasil_cek_inv" required >
											<span >klik cek invoice dulu baru klik submit</span>

										</div>
									</div>

									<div class="modal-footer">
										<button class="btn btn-success" type="submit" disabled id="submit_setelah_cek_inv">edit</button>
										<button type="reset" class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Cancel</button>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div id="modal_ketinv" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
							<h4 class="modal-title" id="myModalLabel">Edit Keterangan invoice</h4>
						</div>
						<div class="modal-content">
							<div class="modal-body">
								<form action="'.$aksi.'?module=invoice-customer&act=edit-ketinv" method="POST">
									<input type="hidden" name="id_invoice" class="form-control" value="'.$id_invoice.'" />
									<div class="form-group">
										<label class="col-md-3 control-label">Keterangan invoice <span class="required">* </span></label>
										<div class="col-md-9">
											
												<input type="text" class="form-control" id="ketinv" name="ketinv" value="'.$r['keterangan_no_invoice'].'">
											
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
							<h4 class="modal-title" id="myModalLabel">Edit judul subtotal</h4>
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
							<h4 class="modal-title" id="myModalLabel">Edit judul subtotal</h4>
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
							<h4 class="modal-title" id="myModalLabel">Edit PPN</h4>
						</div>
						<div class="modal-content">
							<div class="modal-body">
								<form action="'.$aksi.'?module=invoice-customer&act=edit-ppn" method="POST">
									<input type="hidden" name="id_invoice" class="form-control" value="'.$id_invoice.'" />
									<div class="form-group">
										<label class="col-md-3 control-label">PPN  <span class="required">* </span></label>

										<div class="col-md-9">
											<div class="radio">
												  <label><input type="radio" value="1" name="ppn" '.($ppn ==1 ? 'checked' : '').' readonly>1 %</label>
											</div>
											<div class="radio">
												  <label><input type="radio" value="10" name="ppn" '.($ppn ==10 ? 'checked' : '').' readonly>10 %</label >
											</div>
											<div class="radio">
												  <label><input type="radio" value="1.1" name="ppn" '.($ppn ==1.1 ? 'checked' : '').'>1.1 %</label>
											</div>
											<div class="radio">
												  <label><input type="radio" value="11" name="ppn" '.($ppn ==11 ? 'checked' : '').'>11 %</label>
											</div>
											<div class="radio">
												  <label><input type="radio" value="1.2" name="ppn" '.($ppn ==1.2 ? 'checked' : '').'>1.2 %</label>
											</div>
											<div class="radio">
												  <label><input type="radio" value="12" name="ppn" '.($ppn ==12 ? 'checked' : '').'>12 %</label>
											</div>
										</div>
									</div>

									<div class="form-group">
										<label class="col-md-3 control-label">Keterangan </label>

										<div class="col-md-9">
												<textarea class="form-control" id="keteranganppn" name="keteranganppn" value="">'.$r['keterangan_ppn'].'</textarea>
										</div>
									</div>
									<br>
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

			<div id="modal_confirm_hapusbiayatambahan" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
							<h4 class="modal-title" id="myModalLabel">Yakin hapus biaya tambahan</h4>
						</div>
						<div class="modal-content">
							<div class="modal-body">
							<form action="'.$aksi.'?module=invoice-customer&act=delete-biayatambahan" method="POST">
							<input type="text" val="" name="id" id="idbiayatambahan">
							</div>
						</div>
						<div class="modal-footer">
			                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
			                

			                	<button type="confirm" class="btn btn-primary">Delete</button>
			                </form>
			              </div>
					</div>
				</div>
			</div>

			<div id="modal_harga_kg" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
							<h4 class="modal-title" id="myModalLabel">Edit Rate Customer</h4>
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


														$service = $mysqli->query("SELECT airwaybill_kode, id_pengiriman FROM pengiriman where id_kustomer= '$r[id_kustomer]' and tanggal BETWEEN (CURRENT_DATE() - INTERVAL 6 MONTH) AND CURRENT_DATE() and (id_invoice is null or id_invoice = 0) ORDER BY id_pengiriman");
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
                                                    <th>KG</th>
                                                    <th>Volume</th>

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

		}
	}else{
		echo akses_salah();
	}
}
?>

<script>
	function editketinv() {
		//e.preventDefault();
		//no = $(this).attr('data-id');
		$("#modal_ketinv").modal('show');
		//$("#modal_noinv #noinv").val(no);
		/*$("#detail_waktu").modal('show');
		var detailawb = clicked_id;


		$.post('module/mod_pickup/pickup_action.php?module=pickup&act=status', {
			id: detailawb
			},
			function (html) {
				$("#detail_waktu .modal-body").html(html);
			}
		);*/

		

	}


	function biaya_manual() {
		//e.preventDefault();
		no = $('#open_tambah_biaya_manual').attr('data-id');
		$("#modal_tambah_biaya_manual").modal('show');
		$("#modal_tambah_biaya_manual #idinvoice").val(no);
		/*$("#detail_waktu").modal('show');
		var detailawb = clicked_id;


		$.post('module/mod_pickup/pickup_action.php?module=pickup&act=status', {
			id: detailawb
			},
			function (html) {
				$("#detail_waktu .modal-body").html(html);
			}
		);*/

		

	}

	function edit_biaya_manual() {
		//e.preventDefault();
		no = $('#open_edit_biaya_manual').attr('data-id');
		biaya = $('#open_edit_biaya_manual').attr('data-number');
		keterangan = $('#open_edit_biaya_manual').attr('data-val');

		$("#modal_edit_biaya_manual").modal('show');

		$("#modal_edit_biaya_manual #idinvoice").val(no);
		$("#modal_edit_biaya_manual #jumlah_biaya_manual").val(biaya);
		$("#modal_edit_biaya_manual #keterangan_biaya_manual").val(keterangan);
	

		

	}

	function cek_inv() {
		//alert('tes');

	    	var noinv = $("#noinv").val();
			$.ajax({
				type: 'POST',
				dataType: 'json',
				data: {
					inv : noinv
				},
				url: 'module/mod_invoice-customer/invoice-customer_action.php?module=invoice-customer&act=cek_inv',
				success: function (result) {
					//alert('tes');
					//alert('asda');
					//$('#intercityasal').val(result['intercity']);
					if(result.success > 0){
						$("#hasil_cek_inv").val('Y');	
						//alert('AWB OKE,Silahkan klik submit');		
						$("#submit_setelah_cek_inv").prop('disabled', false);		
					}else{
						alert(result.message);
					}


				}
			});
	}
	function check_print_all() {
		//alert('tes');

	    	//$("#btnsearch").prop("disabled", true);

		if ($('#report .printawb').is(':checked') ==  false)
	    {
	    //	alert('test if');
	        $('#report .printawb').prop('checked', true);
	        $("#report .printawb").attr("checked", true);
	       
	    }
	    else
	    {
	    //	alert('test else');
	        $('#report .printawb').prop('checked', false);
	    }
	}

	function check_all_tengah() {
		//alert('tes');

	    	//$("#btnsearch").prop("disabled", true);

		if ($('#report .tagihkan_baru').is(':checked') ==  false)
	    {
	    //	alert('test if');
	        $('#report .tagihkan_baru').prop('checked', true);
	        $("#report .tagihkan_baru").attr("checked", true);
	       
	    }
	    else
	    {
	    //	alert('test else');
	        $('#report .tagihkan_baru').prop('checked', false);
	    }
	}

	function search_jenisbarang(el) {
		//alert('tes klik function berhasil');
		$("#modal_jenisbarang").modal('show');
		var jenis_barang = $(el).attr('data-type');
		//alert(jenis_barang);
		$.post('module/mod_invoice-customer/invoice-customer_action.php?module=invoice-customer&act=search_jenisbarang', {
				jenis: jenis_barang
			},
			function (html) {
				$(".modal-body").html(html);
			}
		);
		


	}


	
</script>
