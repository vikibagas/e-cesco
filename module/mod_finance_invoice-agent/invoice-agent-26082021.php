<section class="content-header">
	<h1>Invoice <small>Agent</small></h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-home"></i> Home</a></li>
		<li><a href="#">Finance</a></li>
		<li><a href="#">Invoice</a></li>
		<li class="active">Agent</li>
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
	if($u['level']=='admin' || $u['id_user']== 5 || $u['id_user']== 9 || $u['level']=='agent' || $u['id_user']== 8  || $u['level']=='finance')
	{
		$aksi	= "module/mod_finance_invoice-agent/invoice-agent_action.php";
		$act	= !empty($_GET['act']) ? $_GET['act'] : '';

		switch($act)
		{

			default:
			set_time_limit(0);
			echo'
			<section class="content">';
				/*if (isset($_SESSION['pesan']) && $_SESSION['pesan'] == 'success')
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
				elseif(isset($_SESSION['pesan']) && $_SESSION['pesan'] == 'duplicate')
				{
					echo'
					<div class="callout callout-danger pesan">
						<h4><i class="icon fa fa-warning"></i> Failed!</h4>
						<p>Data sudah ada</p>
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
				$_SESSION['pesan'] = '';*/
				echo'
				<div class="row">
					<div class="col-md-12">
						<div class="box box-primary">
							<div class="box-header">
								<div class="row">
									<div class="col-md-6">
										<div class="col-md-6">
										<div class="btn-group ">
											<button class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown">Tools <i class="fa fa-angle-down"></i></button>
											<ul class="dropdown-menu pull-right">

												<li><a href="main.php?module=invoice-agent&act=search" class=""><i class="fa fa-search"></i>Search By Airwaybill</a></li>
											</ul>
										</div>
									</div>
									</div>
									<div class="col-md-6">
									</div>
								</div>
							</div>
							<div class="box-body">
							<form method="post" action="#" class="form-horizontal form-bordered" autocomplete="off">
								<div class="form-group">
									<label class="col-md-2 control-label">Periode Invoice</label>
									<div class="col-md-3">
										<div class="input-group">
											<div class="input-group-addon">
												<i class="fa fa-calendar"></i>
											</div>
											<input type="text" class="form-control date-picker" id="tanggal-invoice" value="'.(isset($_POST['tanggal']) ? $_POST['tanggal'] : '').'" name="tanggal" data-date-format="mm-yyyy">
										</div>
									</div>
									<div class="col-md-1">
									<button type="submit" class="btn btn-primary pull-right">Search</button>
									</div>


								</div>
							</form>
								<table id="InvoiceAgent" class="table table-bordered table-striped table-hover" width="100%" cellspacing="0" style="white-space:nowrap;">
									<thead>
										<tr>
											<th>Tanggal Invoice</th>
											<th>Nama Agent</th>
											<th>No Invoice</th>
											<th>Due Date</th>
											<th>Paid Date</th>
											<th width="50"><center>#</center></th>
										</tr>
									</thead>
									<tbody>
											';

											$idCabang = $u['id_cabang'];

											if (isset($_POST['tanggal'])){

												if ($_POST['tanggal'] != ""){

													$arrTgl = explode(' - ', $_POST['tanggal']);

													$start = $month = strtotime($arrTgl[1].'-01');
													$end = strtotime($arrTgl[0].'-01');
												}else{
													$end = strtotime(date('Y-m-d', strtotime('-3 month')));
													$start = $month = strtotime(date("Y-m-d"));
												}

											}else{
												$end = strtotime(date('Y-m-d', strtotime('-3 month')));
												$start = $month = strtotime(date("Y-m-d"));
											}

											$key	= "asdf$1011";

											$no = 0;
											while($month >= $end)
											{
												$sqlCabang = "select *
														from cabang where status='Y' and cabang_cesco='N'";


												if ($u['level']=='agent'){
													$sqlCabang .= " and id_cabang = ".$idCabang;
												}

												$cari_agent=mysqli_query($con,$sqlCabang);

												while($ca=mysqli_fetch_assoc($cari_agent)){


													$sql = "SELECT
															(SELECT distinct
																concat(ifnull(id,''),'~',ifnull(due_date,''),'~',ifnull(paid_date,''),'~',ifnull(nomor,'')) as agent_status
															FROM
																invoice_agent_status
															WHERE
																id_agent = ".$ca['id_cabang']."
																	and date_format(invoice_date, '%Y-%m') = '".date('Y-m', $month)."') as agent_status,
															(
															SELECT distinct
																concat(s.id,'~',ifnull(s.confirm_by,'')) as send_to_agent
															FROM
																invoice_send s
															WHERE
																s.agent_id = ".$ca['id_cabang']."
																	and s.agent_date = '".date('Y-m', $month)."'
																	and s.invoice_type = 'AGENT' order by id desc limit 1) as send_to_agent,
															(select
																distinct status_inv_agent
															from
																pengiriman
															where
																(delivery_by_agent = ".$ca['id_cabang']." or pickup_by_agent = ".$ca['id_cabang'].")
																	and date_format(tanggal, '%Y-%m') = '".date('Y-m', $month)."'
																	and (status_inv_agent is not null and status_inv_agent != 0)
															GROUP BY date_format(tanggal, '%Y-%m')
															) as status_inv_agent";


													$q = mysqli_query($con,$sql);

													$idStatus = '';
													$dueDate = '';
													$paidDate = '';
													$btnSend = '';
													$confirm = '';
													$sentToAgent = '';
													$invoice = '';
													$invoiceTerkirim = 0;
													while($r=$q->fetch_assoc()){

                                                        $no++;
														//Cek Due Date & Paid Date
														if ($r['agent_status'] != ''){
															$arrAgentStts = explode('~', $r['agent_status']);

															$idStatus = $arrAgentStts[0];
															$dueDate = ($arrAgentStts[1] == '') ? $dueDate : $arrAgentStts[1];
															$paidDate = ($arrAgentStts[2] == '') ? $paidDate : $arrAgentStts[2];
															$invoice = ($arrAgentStts[3] == '') ? '' : $arrAgentStts[3];
														}

                                                        $invoiceTerkirim = 0;
														$d = $ca['id_cabang'].'~'.date('Y-m', $month).'~'.$invoice.'~'.$invoiceTerkirim;


														//Cek Send to Agent
														$sentToAgent = $r['send_to_agent'];
														if ($sentToAgent){

															$arrSendToAgent = explode('~', $r['send_to_agent']);
															if ($idCabang == 39) {

																if ($r['status_inv_agent'] != '') {
																	if ($r['send_to_agent'] != ''){
																		if ($u['level'] == 'agent'){
																			if ($arrSendToAgent[1] == ''){
																				$btnSend = '<button class="btn btn-xs btn-default" title="Konfirmasi Invoice" id="btnConfirm'.$no.'" onclick="confirmInvoice(\''.$no.'\',\''.base64_encrypt($d,$key).'\')"><i id="iConfirm'.$no.'" class="fa fa-send text-red"></i></button>';
																			}else{

																				$btnSend = '<button class="btn btn-xs btn-default" title="Invoice Berhasil Dikonfirmasi" onclick="#"><i class="fa fa-send text-blue"></i></button>';
																			}
																		}else{
																			if ($arrSendToAgent[0] == '') {
																				$btnSend = '<button class="btn btn-xs btn-default" title="Kirim Invoice ke Agent" id="btnSend'.$no.'" onclick="sendInvoice(\''.$no.'\',\'' . base64_encrypt($d, $key) . '\')"><i id="iSend'.$no.'" class="fa fa-send text-red"></i></button>';
																			} else {

                                                                                $invoiceTerkirim = 1;

																				if ($arrSendToAgent[1] == ''){
																					//$btnSend = '<button class="btn btn-xs btn-default" title="Batalkan Kirim Invoice ke Agent" onclick="window.location.href = \'module/mod_finance_invoice-agent/invoice-agent_action.php?module=invoice-agent&act=send&stts=0&id=' . base64_encrypt($d, $key) . '\';"><i class="fa fa-send text-green"></i></button>';
																					$btnSend = '<button class="btn btn-xs btn-default" title="Invoice Berhasil Dikirim ke Agent" onclick="#"><i class="fa fa-send text-green"></i></button>';
																				}else{

																					$btnSend = '<button class="btn btn-xs btn-default" title="Invoice Berhasil Dikonfirmasi" onclick="#"><i class="fa fa-send text-blue"></i></button>';
																				}


																			}
																		}

																	}else{

																		if ($u['level'] == 'agent'){
																			if ($arrSendToAgent[1] == ''){
																				$btnSend = '<button class="btn btn-xs btn-default" title="Konfirmasi Invoice" id="btnConfirm'.$no.'" onclick="confirmInvoice(\''.$no.'\',\''.base64_encrypt($d,$key).'\')"><i id="iConfirm'.$no.'" class="fa fa-send text-red"></i></button>';
																			}else{

																				$btnSend = '<button class="btn btn-xs btn-default" title="Invoice Berhasil Dikonfirmasi" onclick="#"><i class="fa fa-send text-blue"></i></button>';
																			}
																		}else{
																			$btnSend = '<button class="btn btn-xs btn-default" title="Kirim Invoice ke Agent" id="btnSend'.$no.'" onclick="sendInvoice(\''.$no.'\',\'' . base64_encrypt($d, $key) . '\')"><i id="iSend'.$no.'" class="fa fa-send text-red"></i></button>';
																		}

																	}
																}

															}else{

																if ($arrSendToAgent[1] == ''){
																	$confirm = '<button class="btn btn-xs btn-default" title="Konfirmasi Invoice" id="btnConfirm'.$no.'" onclick="confirmInvoice(\''.$no.'\',\''.base64_encrypt($d,$key).'\')"><i id="iConfirm'.$no.'" class="fa fa-send text-red"></i></button>';
																}else{
                                                                    $invoiceTerkirim = 1;
																	$confirm = '<button class="btn btn-xs btn-default" title="Invoice Berhasil Dikonfirmasi" onclick="#"><i class="fa fa-send text-blue"></i></button>';
																}

															}
														}else{
															if ($r['status_inv_agent'] != '') $btnSend = '<button class="btn btn-xs btn-default" title="Kirim Invoice ke Agent" id="btnSend'.$no.'" onclick="sendInvoice(\''.$no.'\',\'' . base64_encrypt($d, $key) . '\')"><i id="iSend'.$no.'" class="fa fa-send text-red"></i></button>';
														}
													}



                                                    $d = $ca['id_cabang'].'~'.date('Y-m', $month).'~'.$invoice.'~'.$invoiceTerkirim;

														if ($idCabang == 39) {
															$dueDateClass = ($dueDate == '') ? 'editable-click editable-empty' : 'editable editable-click';
															$dueDateTable = ($dueDate == '') ? 'Empty' : $dueDate;
															$dueDateTable = '<a href="#" id="dueDate' . $ca['id_cabang'] . date('Y-m', $month) . '" class="open_due_date ' . $dueDateClass . '" data-pk="' . $ca['id_cabang'] .'~'.date('Y-m', $month).'~'.$dueDate.'~'.$idStatus.'">' . $dueDateTable . '</a>';

															$paidDateClass = ($paidDate == '') ? 'editable-click editable-empty' : 'editable editable-click';
															$paidDateTable = ($paidDate == '') ? 'Empty' : $paidDate;
															$paidDateTable = '<a href="#" id="paidDate' . $ca['id_cabang'] . date('Y-m', $month) . '" class="open_paid_date ' . $paidDateClass . '" data-pk="' . $ca['id_cabang'] .'~'.date('Y-m', $month).'~'.$paidDate.'~'.$idStatus.'">' . $paidDateTable . '</a>';

															$btnEdit = '<a class="btn btn-xs btn-default" title="Detail" href="?module=invoice-agent&act=update&id='.base64_encrypt($d,$key).'" target="_blank"><i class="fa fa-pencil text-yellow" ></i></a>';
														}else{
															$dueDateTable = ($dueDate == '') ? 'Empty' : $dueDate;
															$paidDateTable = ($paidDate == '') ? 'Empty' : $paidDate;

															$btnEdit = '<button class="btn btn-xs btn-default" title="Detail" onclick="window.location.href = \'?module=invoice-agent&act=update&id='.base64_encrypt($d,$key).'\';"><i class="fa fa-file-text-o text-yellow"></i></button>';

														}

														if ($u['level']!='agent' || ($u['level']=='agent' && $sentToAgent)){

															echo '<tr>
																	<td>'.date('m-Y', $month), PHP_EOL.'</td>
																	<td>'.$ca['nama_cabang'].'</td>
																	<td>'.$invoice.'</td>
																	<td>'.$dueDateTable.'</td>
																	<td>'.$paidDateTable.'</td>
																	<td width="50"><center>'.$btnEdit.$btnSend.$confirm.'</center></td>
																</tr>';
														}

												}
												$month = strtotime("-1 month", $month);

											}

											/*while($month < $end)
											{



												    echo '
												    <tr>
														<td>'.date('F Y', $month), PHP_EOL.'</td>
														<td>Nama Agent</td>
														<td></td>
														<td>Paid Date</td>
														<td width="50"><center>#</center></td>
													</tr>
													';
													$month = strtotime("+1 month", $month);


											}*/

											echo '
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</section>';

            echo '
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
                </div>';

            echo '
                <div id="modal-due-date" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog" style="width: 400px">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                                <h4 class="modal-title" id="myModalLabel">Due Date </h4>
                            </div>
                            <div class="modal-content">
                                <div class="modal-body">
                                    <form action="'.$aksi.'?module=invoice-agent&act=due_date_invoice" name="form_duedate" id="form_duedate" method="POST">
                                        <input type="hidden" name="id_agen" value="" id="id_agen">
                                        <input type="hidden" name="invoice_date" value="" id="invoice_date">
                                        <input type="hidden" name="id_stts" value="" id="id_stts">
                                        <div class="form-group">
                                            <label class="col-md-4 control-label">Due Date <span class="required">* </span></label>
                                            <div class="input-group col-md-8">
                                                        <div class="input-group-addon">
                                                            <i class="fa fa-calendar"></i>
                                                        </div>
                                                <input type="text" class="form-control tanggal" id="tanggal" name="tanggal" >
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                                <button class="btn btn-success" type="button" id="submitDueDate">Confirm</button>
                                                <button type="reset" class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Cancel</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            ';

            echo '
                <div id="modal-paid-date" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog" style="width: 400px">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                                <h4 class="modal-title" id="myModalLabel">Paid Date </h4>
                            </div>
                            <div class="modal-content">
                                <div class="modal-body">
                                    <form action="'.$aksi.'?module=invoice-agent&act=paid_date_invoice" name="form_paiddate" id="form_paiddate" method="POST">
                                        <input type="hidden" name="id_agen" value="" id="id_agen">
                                        <input type="hidden" name="invoice_date" value="" id="invoice_date">
                                        <input type="hidden" name="id_stts" value="" id="id_stts">
                                        <div class="form-group">
                                            <label class="col-md-4 control-label">Paid Date <span class="required">* </span></label>
                                            <div class="input-group col-md-8">
                                                        <div class="input-group-addon">
                                                            <i class="fa fa-calendar"></i>
                                                        </div>
                                                        <input type="text" class="form-control tanggal" id="tanggal" name="tanggal" >
                                                    </div>
                                </div>
                                <div class="modal-footer">
                                        <button class="btn btn-success" type="button" id="submitPaidDate">Confirm</button>
                                        <button type="reset" class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Cancel</button>
                                        </div>
                                    </form>
                            </div>
                        </div>
                    </div>
                    </div>
                </div>
            ';

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
									<button type="button" name="invoice-agent"  data-id="finance_invoice-agent" id="btnsearchcosting" class="btn form-control btn-primary">search</button>
							</div>
						</div>
						<div id="load" style="display:none;"><img src="dist/img/Loading_icon.gif"/ style="display:block; margin: 0 auto; "></div>

						<table id="airwaybillsrc" class="table table-bordered table-striped table-hover" width="100%" cellspacing="0" style="white-space:nowrap; margin-top:50px;">
									<thead>
										<tr style="text-transform: uppercase;">
											<th>View Pickup</th>
											<th width="90px">View Delivery</th>
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
					<form class="form-horizontal" method="post" action="'.$aksi.'?module=invoice-agent&act=create" autocomplete="off">
					<div class="box-body">
						<div class="form-group">
							<label class="col-md-3 control-label">Tanggal <span class="required">* </span></label>
							<div class="col-md-6">
								<div class="input-group">
									<div class="input-group-addon">
										<i class="fa fa-calendar"></i>
									</div>
									<input type="text" class="form-control" id="tanggal" name="tanggal" value="'.$setdate.'" readonly required>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label">Date Range <span class="required">* </span></label>
							<div class="col-md-6">
								<div class="input-group">
								<div class="input-group-addon">
									<i class="fa fa-calendar"></i>
								</div>
								<input type="text" class="form-control" id="daterange" name="tgl_airwaybill" value="" required>
								<input type="hidden" value="" id="tgl_dr">
								<input type="hidden" value="" id="tgl_sampai">
								<input type="hidden" value="" id="id_agent">
							</div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label">Agent <span class="required">* </span></label>
							<div class="col-md-6">
								<select name="id_agent" class="form-control select2" required>
									';
									$query=mysqli_query($con,"select id_cabang, concat(nama_cabang,' ','(',alamat1,')') as nama_cabang from cabang where cabang_cesco = 'N' order by concat(nama_cabang,' ','(',alamat1,')')");
									while($r=mysqli_fetch_assoc($query)){
										if($r['id_cabang']==$id_cabang){
											echo'
											<option value="'.$r['id_cabang'].'" selected>'.$r['nama_cabang'].'</option>';
										}else{
											echo'
											<option value="'.$r['id_cabang'].'">'.$r['nama_cabang'].'</option>';
										}
									}
								echo'
								</select>
							</div>
						</div>

					</div>
					<div class="box-footer">
						<button type="button" class="btn btn-default" onclick="window.location.href = \'?module=invoice-agent\';">Cancel</button>
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

			$pieces = explode('~', $id);
			$agent_by = $pieces['0'];

			$month = $pieces['1']; //implode('-', array_slice($pieces, 1, 2));
            $invoiceTerkirim = $pieces['3'];


			$query 		= $mysqli->query("select c.*,c.id_cabang as id_agent from cabang c where id_cabang=$agent_by

				");
			$r= $query->fetch_assoc();
			$tanggal = $month;

			$query = $mysqli->query("SELECT nomor
				FROM invoice_agent_status
				WHERE id_agent='".$agent_by."' and date_format(invoice_date, '%Y-%m') = '".$month."' ");

			$n = $query->fetch_assoc();
			$invoice = isset($n["nomor"]) ? $n["nomor"] : '';

			//$id_inv = $r['id'];

			echo'
			<section class="invoice">

				<div class="row invoice-info">
					<div class="col-sm-8 invoice-col">
						';

							echo'
							<address>
								<strong>'.$r['nama_cabang'].','.$r['alamat1'].'</strong><br>
	            				'.$r['alamat2'].'<br>
	            			Phone: '.$r['telp'].'
	          				</address>';
          				echo'
        			</div>
        			<div class="col-sm-4 invoice-col">
          				<b>Tanggal: </b><span id="edit-duedate">'.$month.'</span><br>
									<b>Nomor Invoice: </b><span id="edit-invoice">'.$invoice.' <a id="open_nomor" class="open_invoice btn btn-xs btn-default" data-id="'.$agent_by.'" data-month="'.$month.'" data-nomor="'.$invoice.'" title="Edit Invoice" href="#" target="_blank"><i class="fa fa-pencil text-yellow" ></i></a></span>
        			</div>
				</div>



	  			<div class="row">
        			<div class="box-body">
        			<form action="'.$aksi.'?module=invoice-agent&act=kunci-harga" id="kunciform" method="POST"></form>
        			<form action="'.$aksi.'?module=invoice-agent&act=reset-kunci" id="resetkunci" method="POST"></form>
						<table id="report" class="table table-striped table-bordered table-hover " width="100%" cellspacing="0" style="white-space:nowrap;">
						<thead>
							<tr>
									<th rowspan="2">Booking</th>
									<th rowspan="2">Airwaybill</th>
									<th rowspan="2">Komoditi</th>
									<th rowspan="2">Service Type</th>
									<!--<th rowspan="2">Requestor</th>-->
									<th rowspan="2">Customers Name</th>
									<th rowspan="2">Lockcode</th>
									<th rowspan="2">Consignee</th>
									<th colspan="3"><center>Area</center></th>
									<th colspan="3"><center>Penerima</center></th>
									<th rowspan="2">Manifest Outbound</th>
									<th rowspan="2">Manifest Inbound</th>
									<th rowspan="2">Alasan(Jika) Terlambat</th>
									<th rowspan="2">Berat</th>
									<th rowspan="2">Harga</th>
									<th rowspan="2">Harga Next KG</th>
									<th rowspan="2">Min. Kg</th>
									<th rowspan="2">Packing</th>
									<th rowspan="2">Add Charge</th>
									<th rowspan="2">Detail Add Charge</th>
									<th rowspan="2">Total Pickup</th>
									<th rowspan="2">Total Transit</th>
									<th rowspan="2">Total Delivery</th>
									<th rowspan="2">Diskon (%)</th>
									<th rowspan="2">Total setelah Diskon</th>
									<th rowspan="2">Paid/Reject</th>';

								echo '</tr>
								<tr>
									<th>Kota Asal</th>
									<th>Kota Tujuan</th>
									<th width="800px;">Alamat Lengkap Tujuan</th>
									<th>Nama</th>
									<th>Tanggal</th>
									<th>Lead time</th>
								</tr>
						</thead>
						<tbody>';


						   $cari = "
							SELECT
								a.id_pengiriman,
                                date(a.tanggal) as tanggal,
                                airwaybill_kode,
                                case when a.komoditi='1' then 'Paket' else 'Dokumen' end as komoditi,
                                x_service(a.service) as service,
                                case when a.service=5 then 3 else a.service end as srv,
                                nama_pengirim,
                                loc_code,
                                x_namakustomer(id_kustomer) as namakustomer,
                                nama_penerima,
                                a.id_kota_asal as id_asal,
                                a.id_kota_tujuan as id_tujuan,
                                a.kota_asal as lokasi_asal,
                                a.kota_tujuan as lokasi_tujuan,


                                case when pickup_by_agent =$agent_by then ifnull(biaya_packing_agent,0)
                                	else ifnull(biaya_packing_deliv_agent,0) end
                                as new_biaya_packing_agent_total,



                                case when pickup_by_agent =$agent_by and (delivery_by_agent !=$agent_by or delivery_by_agent is null) then ifnull(biaya_tambahan_agent,0)


                                	when delivery_by_agent=$agent_by and pickup_by_agent !=$agent_by then ifnull(a.biaya_tambahan_deliv_agent,0)

                                	when pickup_by_agent=$agent_by and delivery_by_agent =$agent_by   then ifnull(biaya_tambahan_agent,0)

                                	else ifnull(n.biaya_tambahan_agent_transit,0)

                                end as new_biaya_tambahan_agent_total,

                                kecamatan_asal,
                                kecamatan_tujuan,

                                ifnull(kunci_total_agent,0) as kunci_total_agent,
                                case
                                	when pickup_by_agent != $agent_by	and (delivery_by_agent !=$agent_by or delivery_by_agent is null) 	then ifnull(n.kunci_total_agent_transit,0)
                                	when pickup_by_agent =$agent_by 	and (delivery_by_agent !=$agent_by or delivery_by_agent is null) 	then ifnull(kunci_total_agent_pickup,0)
                               		when pickup_by_agent !=$agent_by 	and delivery_by_agent =$agent_by 	then ifnull(kunci_total_agent_deliv,0)
                           			when pickup_by_agent =$agent_by 	and delivery_by_agent =$agent_by 	then ifnull(kunci_total_agent,0)
                                end as total_kunci_harga_agent,
								case
                                	when pickup_by_agent != $agent_by	and (delivery_by_agent !=$agent_by or delivery_by_agent is null) 	then 'transit'
                                	when pickup_by_agent =$agent_by 	and (delivery_by_agent !=$agent_by or delivery_by_agent is null) 	then 'pickup'
                               		when pickup_by_agent !=$agent_by 	and delivery_by_agent =$agent_by 	then 'delivery'
                           			when pickup_by_agent =$agent_by 	and delivery_by_agent =$agent_by 	then 'pickup-delivery'
                                end as status_agent,
								ifnull(n.kunci_total_agent_transit,0) as kunci_total_agent_transit,
								ifnull(kunci_total_agent_pickup,0) as kunci_total_agent_pickup,
								ifnull(kunci_total_agent_deliv,0) as kunci_total_agent_deliv,
								if( (select total_kunci_harga_agent) > 0,1,0) as ifkunci,

                                if(pickup_by_agent=$agent_by,1,0) as in_out,

                                case

	                                when pickup_by_agent=$agent_by then 'biaya_tambahan_in'

	                                when pickup_by_agent != $agent_by and delivery_by_agent != $agent_by then 'biaya_tambahan_transit'

                                else 'biaya_tambahan'

                                end as biaya_tambahan_in_out,

                                case when pickup_by_agent != 39 and delivery_by_agent != 39
                                	then
                                		case when pickup_by_agent =$agent_by then 1
                                		else  0 end
                                	else
                                		0
                                end as keterangan_in_out,

                                alamat_penerima,
                                delivery_name,
                                delivery_date,
                                pickup_by_agent,
                                alasan_terlambat,
                                if(delivery_date is null,'-', datediff(delivery_date, date(pickup_date))) as selisih_hari,
                                SUBSTRING_INDEX(ukuran_barang,'x',1) as length, SUBSTRING_INDEX(SUBSTRING_INDEX(ukuran_barang,'x',2),'x',-1) as width, SUBSTRING_INDEX(ukuran_barang,'x',-1) as height,
                                 CASE
										WHEN airwaybill_kode LIKE '001-%' THEN 0
										WHEN airwaybill_kode LIKE '002-%' THEN 1
									end as intercity,
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
                                if(berat_aktual is null or berat_aktual < 1,  berat_barang, berat_aktual) as berat_aktual,
                                berat_aktual as brt_akt,
                            	berat_barang as brt_brg,
                            	kunci_berat_agent,
                            	ifnull(ifnull(f.price_next_kg,g.price_next_kg),ifnull(d.price_next_kg,ifnull(e.price_next_kg,0))) as next_kg,
                                ifnull(ifnull(f.price,g.price),ifnull(d.price,ifnull(e.price,0))) as price,

                                ifnull(ifnull(f.price,g.price),ifnull(d.price,ifnull(e.price,0))) as priceee,

                                ifnull(  kunci_hargaperkg_agent ,  (select priceee) ) as pricee,
                                ifnull(ifnull(f.min_kg,g.min_kg),ifnull(d.min_kg,e.min_kg)) as min_kg,
                                ifnull(ifnull(f.min_kg,g.min_kg),ifnull(d.min_kg,e.min_kg)) as min_kggg,
                                ifnull(  kunci_minkg_agent ,  (select min_kggg) ) as min_kgg,
                                 ifnull (kunci_berat_agent, (select berat_real) ) as berat_tampil,

									if ((select min_kgg) >= (select berat_real), (select min_kgg), (select berat_real) ) as berat_finishh,

									ifnull(if(kunci_berat_agent< (select min_kgg) ,(select min_kgg),kunci_berat_agent),
											(select berat_finishh)) as berat_finish,

									ifnull(  if(kunci_berat_agent<(select min_kgg),(select min_kgg),kunci_berat_agent) ,  (select berat_real) ) as kunci_berat,

                                a.biaya_packing_agent,
                                a.biaya_tambahan_agent,

                                case when pickup_by_agent != 39 and delivery_by_agent != 39
                                	then
                                		case when pickup_by_agent =$agent_by then keterangan_biaya_in
                                		else keterangan_biaya_agent end
                                	else
                                		keterangan_biaya_agent
                                end as keterangan_biaya,

                                if(a.paid_reject = '1',if(if(berat_aktual is null or berat_aktual < 1,  berat_barang, berat_aktual) < (select min_kgg), ((select min_kgg)*(select pricee))+(select new_biaya_packing_agent_total)+(select new_biaya_tambahan_agent_total) , (if(berat_aktual is null or berat_aktual < 1,  berat_barang, berat_aktual)*(select pricee))+(select new_biaya_packing_agent_total)+(select new_biaya_tambahan_agent_total) ),0) as total,

                                (
                                	case when (select next_kg) > 0  then
                                		(select berat_finish) * (select pricee) + ( ((select berat_finish) - (select min_kgg)) * (select next_kg)  )
                                	else
                                		(select berat_finish) * (select pricee)
                                	end
                                )

                                +(select new_biaya_packing_agent_total)+(select new_biaya_tambahan_agent_total) as new_totall ,
                                if(  (select total_kunci_harga_agent) > 0 ,(select total_kunci_harga_agent) ,  (select new_totall) ) as new_total,
                                d.rateagent_id,
                                ifnull(d.rateagent_id,ifnull(e.rateagentinbound_id,f.rate_id)) as idrate,
                                d.rateagent_id as idrate_out,
                            	e.rateagentinbound_id as idrate_in,
                            	f.rate_id as idrate_cross,
                            	g.rateagent_id as idrate_transit,
                            	a.paid_reject,
                            	a.reject_reason,
                            	case
                            	when pickup_by_agent = $agent_by and delivery_by_agent <> $agent_by THEN
									case when terlambat_pickup in (1,2) then
										ifnull(a.diskon_pickup,0)
									else 0
									end
                            	when delivery_by_agent = $agent_by and pickup_by_agent <> $agent_by THEN
									case when terlambat_delivery in (1,2) then
										ifnull(a.diskon_deliv,0)
									else 0
									end
                            	else
									case when terlambat_pickup in (1,2) and terlambat_delivery in (1,2) then
											ifnull(a.diskon_pickup,0)+ifnull(a.diskon_deliv,0)
										when terlambat_pickup in (1,2) and terlambat_delivery not in (1,2) then
											ifnull(a.diskon_pickup,0)
										when terlambat_pickup not in (1,2) and terlambat_delivery in (1,2) then
											ifnull(a.diskon_deliv,0)
									else 0
									end
                            	end as diskon, kunci_hargaperkg_agent,
								getManifestCode(a.id_pengiriman, a.pickup_by_agent, 'OUT') as manifest_outbound,
								getManifestCode(a.id_pengiriman, a.delivery_by_agent, 'IN') as manifest_inbound
							FROM pengiriman a

							LEFT JOIN rate_agent d ON d.id_kota=id_kecamatan_tujuan AND d.id_pelayanan=a.service and a.delivery_by_agent = $agent_by and d.id_cabang=$agent_by

							LEFT JOIN rate_agent_inbound e ON e.id_kota=id_kecamatan_asal  and e.id_pelayanan=a.service and a.pickup_by_agent = $agent_by and e.id_cabang = $agent_by

							LEFT JOIN rate_agent_cross f ON f.id_kota_asal = a.id_kecamatan_asal and f.id_kota_tujuan = a.id_kecamatan_tujuan  AND a.pickup_by_agent=$agent_by and a.delivery_by_agent = $agent_by and f.id_agent = $agent_by

							LEFT JOIN manifest_detail n ON a.id_pengiriman = n.id_pengiriman

							LEFT JOIN manifest m ON n.id_manifest = m.id_manifest

							LEFT JOIN rate_agent g ON g.id_kota=id_kecamatan_tujuan AND g.id_pelayanan=a.service and m.manifest_from = $agent_by and g.id_cabang=$agent_by

							WHERE (
								case when a.tanggal_agent is not null then
									a.tanggal_agent BETWEEN '$month-01' AND '$month-31'
								else a.tanggal BETWEEN '$month-01' AND '$month-31'
								end
								and a.service != 6 and a.status != 7 ) and ( pickup_by_agent = $agent_by or delivery_by_agent = $agent_by /*and tanggal_agent is null*/)
							or (m.manifest_from =$agent_by and
							case when a.tanggal_agent is not null then
								a.tanggal_agent BETWEEN '$month-01' AND '$month-31'
							else a.tanggal BETWEEN '$month-01' AND '$month-31'
							end
							and a.service != 6 and a.status != 7 and  pickup_by_agent != $agent_by and delivery_by_agent != $agent_by)
							group by a.id_pengiriman
							";



						/*LEFT JOIN rate_agent d ON d.id_cabang=CASE WHEN x_inter(a.id_kota_asal)='Y' THEN '39' ELSE c.id_cabang END AND d.id_kota=a.id_kota_tujuan AND d.id_pelayanan=CASE WHEN a.service > 3 THEN 3 else a.service end

							LEFT JOIN rate_agent_inbound e ON e.id_kota=a.id_kota_asal  and e.id_pelayanan=CASE WHEN a.service > 3 THEN 3 else a.service end and e.id_cabang=CASE WHEN x_inter(a.id_kota_tujuan)='Y' THEN 0 else 99 end

							LEFT JOIN rate_agent_cross f ON case when a.pickup_by_agent=$agent_by THEN f.id_kota_asal else f.id_kota_tujuan END  = case when a.pickup_by_agent=$agent_by THEN a.id_kota_asal else a.id_kota_tujuan END  AND f.id_pelayanan=a.service */
                //echo $cari;

						$sql = $mysqli->query($cari);
						$row_count= $sql->num_rows;
						$sum_total = 0;
						$kunci = '';
						$emptyPrice = 0;
						while($row = $sql->fetch_assoc()){
							$kunci = $row['kunci_hargaperkg_agent'];
							$new_total = ($row['paid_reject'] == 1 ? $row['new_total'] : 0);
							//$sum_total = $sum_total + $new_total;

							$key = "asdf$1011";
							$stringawal = $row["id_pengiriman"];
							$id = base64_encrypt($stringawal,$key);
							//$volume = ($r['length']*$r['width']*$r['height'])/6000;
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

							if($row['pricee']>0){

								if($row['idrate_out']>0){
									$price = '<a href="#" class="rate_agent" data-type="text" data-pk="'.$row['idrate_out'].'">'.$row['pricee'].'</a>';
								}elseif($row['idrate_in']>0){
									$price = '<a href="#" class="rate_agent_in" data-type="text" data-pk="'.$row['idrate_in'].'">'.$row['pricee'].'</a>';
								}elseif($row['idrate_cross']>0){
									$price = '<a href="#" class="rate_agent_cross" data-type="text" data-pk="'.$row['idrate_cross'].'">'.$row['pricee'].'</a>';
								}
								elseif($row['idrate_transit']>0){
									$price = '<a href="#" class="rate_agent" data-type="text" data-pk="'.$row['idrate_transit'].'">'.$row['pricee'].'</a>';
								}else{
									$price = $row['pricee'];
								}

							}else{
								$price = '<button type="button" class="btn btn-default btn-xs custom_price_insert_agent" data-toggle="modal" data-target="#modal_price_insert_agent" data-id="'.$row['id_pengiriman'].'" id="'.$agent_by.'"><i class="fa fa-plus-square text-orange"></i></button>';
							}


							if($row['min_kgg']>0){

								if($row['idrate_out']>0){
									$min_kg = '<a href="#" class="min_kg_agent" data-type="text" data-pk="'.$row['idrate_out'].'">'.$row['min_kgg'].'</a>';
								}elseif($row['idrate_in']>0){
									$min_kg = '<a href="#" class="min_kg_agent_in" data-type="text" data-pk="'.$row['idrate_in'].'">'.$row['min_kgg'].'</a>';
								}elseif($row['idrate_cross']>0){
									$min_kg = '<a href="#" class="min_kg_agent_cross" data-type="text" data-pk="'.$row['idrate_cross'].'">'.$row['min_kgg'].'</a>';
								}elseif($row['idrate_transit']>0){
									$min_kg = '<a href="#" class="min_kg_agent_transit" data-type="text" data-pk="'.$row['idrate_transit'].'">'.$row['min_kgg'].'</a>';
								}else{
									$min_kg = $row['min_kgg'];
								}


							}else{
								$min_kg='-';
							}


							///SLA
                            /*
                            $d = $row['sla_date'];
                            $pieces = explode(' ', $d);

                            $booking_date = $pieces[0];

                            $d1 = strtotime($pieces[1]);
                            $d2 = date('l',$d1);
                            $dt3 = strtolower($d2);

                            $telat = "";

                            if($dt3 == "saturday" )
                            {
                                $sla = date('Y-m-d',strtotime($pieces[1].'+2 day'));
                            }
                            elseif($dt3 == "sunday")
                            {
                                $sla = date('Y-m-d',strtotime($pieces[1].'+1 day'));
                            }else{
                                $sla = date('Y-m-d',strtotime($pieces[1]));
                            }

                            $date_now =  date("Y-m-d");

                            if($sla != '1970-01-01'){

                                if ($date_now > $sla && $row['selisih_hari2'] > 0){

                                    $telat = '<button type="button" class="btn btn-xs btn-danger" title="TERLAMBAT"><i class="fa fa-exclamation-circle"></i> </button>';

                                    $created_date = $booking_date. ' - '.$sla . '<button class="btn btn-xs btn-danger" title="TERLAMBAT"><i class="fa fa-exclamation-circle"></i> </button>';
                                }else{
                                    $created_date =  $booking_date . ' - '.$sla;
                                }
                            }else{
                                $created_date =  $booking_date;
                            }
                            */
                            ////

							////

							$totalAgent = 0;
							$totalAgenPickup = $row['kunci_total_agent_pickup'];
							$totalAgentDeliv = $row['kunci_total_agent_deliv'];
							$totalAgentTransit = $row['kunci_total_agent_transit'];
							$totalAgentPickupDeliv = $row['kunci_total_agent'];

							/*
							if ($row['kunci_total_agent'] > 0){
								$totalAgenPickup = $row['kunci_total_agent_pickup'];
								$totalAgentDeliv = $row['kunci_total_agent_deliv'];
								$totalAgent = $totalAgenPickup+$totalAgentDeliv;
							}else{
								if ($row['kunci_total_agent_pickup'] > 0){
									$totalAgenPickup = $row['new_total'];
									$totalAgent = $totalAgenPickup;
								}
								else if ($row['kunci_total_agent_transit'] > 0) {
									$totalAgentTransit = $row['new_total'];
									$totalAgent = $totalAgentTransit;
								}
								else if ($row['kunci_total_agent_deliv'] > 0){
									$totalAgentDeliv = $row['new_total'];
									$totalAgent = $totalAgentDeliv;
								}
							}*/

							/////

							$sttsAgent = $row['status_agent'];
							if ($sttsAgent == 'pickup-delivery'){
								$totalAgenPickup = 0;
								$totalAgentTransit = 0;
								$totalAgentDeliv = $row['new_total'];
								$totalAgent = $totalAgentDeliv;
							}else{
								if ($sttsAgent == 'pickup'){
									$totalAgenPickup = $row['new_total'];
									$totalAgentDeliv = 0;
									$totalAgentTransit = 0;
									$totalAgent = $totalAgenPickup;
								}
								else if ($sttsAgent == 'transit') {
									$totalAgentTransit = $row['new_total'];
									$totalAgenPickup = 0;
									$totalAgentDeliv = 0;
									$totalAgent = $totalAgentTransit;
								}
								else if ($sttsAgent == 'delivery'){
									$totalAgentDeliv = $row['new_total'];
									$totalAgenPickup = 0;
									$totalAgentTransit = 0;
									$totalAgent = $totalAgentDeliv;
								}
							}


							////

                            $new_total_diskon = $totalAgent - ($row['new_biaya_packing_agent_total']+$row['new_biaya_tambahan_agent_total']);
							$total = $new_total_diskon;
                            $diskon = $row['diskon'];
                            $totalAfterDiskon = ($total-(($diskon/100)*$total))+($row['new_biaya_packing_agent_total']+$row['new_biaya_tambahan_agent_total']);

														if ($row['paid_reject'] != 0){
															$sum_total = $sum_total + $totalAfterDiskon;
														}

                            $telat = ($diskon > 0) ? '<button type="button" class="btn btn-xs btn-danger" title="TERLAMBAT"><i class="fa fa-exclamation-circle"></i> </button>' : '';

							if ($row['new_total'] == 0 || $row['new_total'] == ''){
								$emptyPrice++;
							}


							echo'
							<tr>
								<td width="20px">'.$row['tanggal'].'</td>
								<td width="20px">'.$row['airwaybill_kode'].'</td>
								<td width="20px">'.strtoupper($row['komoditi']).'</td>
								<td width="50px">'.strtoupper($row['service']).'</td>
								'; //<td width="20px">'.strtoupper($row['nama_pengirim']).'</td>'
								echo '
								<td width="20px">'.strtoupper($row['namakustomer']).'</td>
								<td width="20px">'.strtoupper($row['loc_code']).'</td>
								<td width="20px">'.strtoupper($row['nama_penerima']).'</td>
								<td width="20px">'.strtoupper($row['kecamatan_asal']).' ,'.strtoupper($lokasi_asal).'</td>
								<td width="20px">'.strtoupper($row['kecamatan_tujuan']).' ,'.strtoupper($lokasi_tujuan).'</td>
								<td width="20px">'.strtoupper($row['alamat_penerima']).'</td>
								<td width="20px">'.strtoupper($row['delivery_name']).'</td>
								<td width="20px">'.$row['delivery_date'].'</td>
								<td width="20px"> '.$row['selisih_hari'].' hari '.$telat.'</td>

								';/*
								<td width="20px" align="right"><a href="#" class="berat_aktual" data-type="text" data-pk="'.$row['id_pengiriman'].'">'.$row['berat_aktual'].'</a></td>
								<td align="right"><a href="#" class="price" data-type="text" data-pk="'.$row['id_pengiriman'].'">'.number_format($row['price']).'</a></td>
								<td align="right" ><a href="#" class="min_kg" data-type="text" data-pk="'.$row['rateagent_id'].'">'.number_format($row['min_kg']).'</a></td>*/echo '
								<td width="20px"> '.$row['manifest_outbound'].'</td>
								<td width="20px"> '.$row['manifest_inbound'].'</td>
								<td width="20px"> '.$row['alasan_terlambat'].'</td>
								<td width="20px" align="right">
								';
								if ($u['level']=='agent' || $row['ifkunci'] ==1){
									echo $row['berat_tampil'] ;
								}else{
									echo '<a href="#" class="berat_agent" data-type="text" data-pk="'.$row['id_pengiriman'].'">'.$row['berat_tampil'].'</a>';
								}
								echo '
								</td>
								<td align="right">'.($u['level']=='agent' || $row['ifkunci'] ==1 ? number_format($row['price']) : $price).' dan '.$row['idrate_out'].' </td>
								<td align="right" >'.($u['level']=='agent' ? $row['next_kg'] : $row['next_kg']).'</td>
								<td align="right" >'.($u['level']=='agent' || $row['ifkunci'] ==1 ? $row['min_kgg'] : $min_kg).'</td>


								';

								if ($u['level']=='agent' ){
									echo '
									<td align="right" align="right">'.number_format($row['new_biaya_packing_agent_total']).'</td>
									<td align="right" align="right">'.number_format($row['new_biaya_tambahan_agent_total']).'</td>
									<td>'.$row['keterangan_biaya'].'</td>
									<td align="right">'.($row['paid_reject'] == 1 ? number_format($totalAgenPickup) : '-').($row['ifkunci'] == 1 ? '<i class="fa fa-key"></i>' : '').'
									<td align="right">'.($row['paid_reject'] == 1 ? number_format($totalAgentTransit) : '-').($row['ifkunci'] == 1 ? '<i class="fa fa-key"></i>' : '').'
									<td align="right">'.($row['paid_reject'] == 1 ? number_format($totalAgentDeliv) : '-').($row['ifkunci'] == 1 ? '<i class="fa fa-key"></i>' : '').'
									</td>
									<td align="right" align="right">'.$diskon.'</td>
									<td align="right" align="right">'.number_format($totalAfterDiskon).'</td>
									<td>'.($row['paid_reject'] == 1 ? 'paid' : 'rejected, '.$row["reject_reason"]) .'</td>

									';
								}
								else{

								    $diskonCell = ($telat == "") ? $diskon : '<a href="#" class="diskon" data-type="text" data-pk="'.$row['id_pengiriman'].'~'.$total.'">'.$diskon.'</a>';
                                    //$diskonCell = ($telat == "") ? $diskon : '<a href="#" class="open_diskon" data-type="text" data-pk="'.$row['id_pengiriman'].'">'.$diskon.'</a>';

									echo '
									<td align="right" align="right">'.($diskon == '' ? '<a href="#" class="biaya_packing" data-type="text" data-pk="'.$row['id_pengiriman'].'">'.$row['new_biaya_packing_agent_total'].'</a>' : number_format($row['new_biaya_packing_agent_total'])).'</td>

									<td align="right" align="right"><a href="#" class="'.$row['biaya_tambahan_in_out'].'" data-type="text" data-pk="'.($row['biaya_tambahan_in_out'] == 'biaya_tambahan_transit' ? $row['id_pengiriman'].'-'.$agent_by : $row['id_pengiriman']).'">'.$row['new_biaya_tambahan_agent_total'].'</a></td>

									<td><a href="#" class="'.($row['keterangan_in_out'] == 1 ? 'keterangan_biaya_in' : 'keterangan_biaya').'" data-type="text" data-pk="'.$row['id_pengiriman'].'">'.$row['keterangan_biaya'].'</a></td>


									<td align="right">'.($row['paid_reject'] == 1 ? number_format($totalAgenPickup) : '-').($row['ifkunci'] == 1 ? '<i class="fa fa-key"></i>' : '').'</td>
									<td align="right">'.($row['paid_reject'] == 1 ? number_format($totalAgentTransit) : '-').($row['ifkunci'] == 1 ? '<i class="fa fa-key"></i>' : '').'</td>
									<td align="right">'.($row['paid_reject'] == 1 ? number_format($totalAgentDeliv) : '-').($row['ifkunci'] == 1 ? '<i class="fa fa-key"></i>' : '').'</td>
									<td align="right" align="right">'.$diskonCell.'</td>
									<td align="right" align="right" id="totAfterDisc'.$row['id_pengiriman'].'">'.number_format($totalAfterDiskon).'</td>
									<td>'.($row['paid_reject'] == 1 ? 'paid | <a href="#" id="'.$row['id_pengiriman'].'" class="open_reject">reject</a>' : 'rejected, '.$row["reject_reason"]) .'| <a href="#" id="'.$row['id_pengiriman'].'" class="open_move">move</a> </td>';

								}
								echo '
							</tr>';
						}
						echo'
						</tbody>
					</table
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

                        if ($invoiceTerkirim == 1){
                            echo "<script>

                                    function cekKirimAgentx(){
                                        setTimeout(function() {
                                            $('.editable').unbind();
                                            $('.custom_price_insert_agent').remove();
                                            $('.custom_total_fix').remove();
                                            $('.open_reject').unbind();
                                            $('.open_move').unbind();
                                            $('#open_nomor').remove();
                                            $('#spTombolKunci').remove();

                                        }, 10);

                                    }
                                    </script>";
                        }

						if ($kunci != '' && $id_user != 3 && $id_user != 8){
							echo "<script>
							function cekKunci(){
								setTimeout(function() {
									$('.editable').unbind();
									$('.custom_price_insert_agent').remove();
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

					if($r['id_agent'] == 49 || $r['id_agent'] == 58 || $r['id_agent'] == 49 || $r['id_agent'] == 21 || $r['id_agent'] == 22 || $r['id_agent'] == 42){
						 "SELECT
                                SUM(if(a.paid_reject = '1',if(if(berat_aktual is null or berat_aktual < 1,  berat_barang, berat_aktual) < ifnull(d.min_kg,e.min_kg), (ifnull(d.min_kg,e.min_kg)*ifnull(d.price,e.price))+a.biaya_packing_agent+a.biaya_tambahan_agent , (if(berat_aktual is null or berat_aktual < 1,  berat_barang, berat_aktual)*ifnull(d.price,e.price))+a.biaya_packing_agent+a.biaya_tambahan_agent ),0)) AS total

                            FROM pengiriman a
							INNER JOIN kota b ON b.id_kota=a.lokasi_tujuan
							OR b.id_kota=a.lokasi_asal
							INNER JOIN cabang c ON c.id_kota=b.id_kota OR b.hub=c.id_kota
						LEFT JOIN rate_agent d ON d.id_cabang=CASE WHEN x_intercity(a.lokasi_asal)='Y' THEN '39' ELSE c.id_cabang END AND d.id_kota=a.lokasi_tujuan AND d.id_pelayanan=CASE WHEN a.service > 3 THEN 3 else a.service end
							LEFT JOIN rate_agent_inbound e ON e.id_kota=a.lokasi_asal  and e.id_pelayanan=CASE WHEN a.service > 3 THEN 3 else a.service end
							WHERE (c.id_cabang=$agent_by AND a.tanggal BETWEEN '$r[dari]' AND '$r[sampai]' and service != 6 and a.status != 7 and paid_reject = '1') and ( pickup_by_agent = $agent_by or delivery_by_agent = $agent_by)


							";
						$qtotal = $mysqli->query("SELECT
                                SUM(if(a.paid_reject = '1',if(if(berat_aktual is null or berat_aktual < 1,  berat_barang, berat_aktual) < ifnull(d.min_kg,ifnull(e.min_kg,f.min_kg)), (ifnull(d.min_kg,ifnull(e.min_kg,f.min_kg))*ifnull(d.price,ifnull(e.price,f.price)))+a.biaya_packing_agent+a.biaya_tambahan_agent , (if(berat_aktual is null or berat_aktual < 1,  berat_barang, berat_aktual)*ifnull(d.price,ifnull(e.price,f.price)))+a.biaya_packing_agent+a.biaya_tambahan_agent ),0)) AS total

                           FROM pengiriman a
                            LEFT JOIN kota b ON b.id_kota=a.lokasi_tujuan
                            OR b.id_kota=a.lokasi_asal
                            LEFT JOIN cabang c ON c.id_kota=b.id_kota OR b.hub=c.id_kota

                        LEFT JOIN rate_agent d ON d.id_cabang=CASE WHEN x_intercity(a.lokasi_asal)='Y' THEN '39' ELSE c.id_cabang END AND d.id_kota=a.lokasi_tujuan AND d.id_pelayanan=CASE WHEN a.service > 3 THEN 3 else a.service end

                            LEFT JOIN rate_agent_inbound e ON e.id_kota=a.lokasi_asal  and e.id_pelayanan=CASE WHEN a.service > 3 THEN 3 else a.service end and e.id_cabang=CASE WHEN x_intercity(a.lokasi_tujuan)='Y' THEN 0 else 99 end

                            LEFT JOIN rate_agent_cross f ON f.id_kota_asal=CASE WHEN x_intercity(a.lokasi_asal)!='Y' THEN a.lokasi_asal ELSE 0 END AND f.id_kota_tujuan=CASE WHEN x_intercity(a.lokasi_tujuan)!='Y' THEN a.lokasi_tujuan ELSE 0 END AND f.id_pelayanan=CASE WHEN a.service > 3 THEN 3 else a.service end
							WHERE (c.id_cabang=$agent_by AND a.tanggal BETWEEN '$r[dari]' AND '$r[sampai]' and service != 6 and a.status != 7 and paid_reject = '1') and ( pickup_by_agent = $agent_by or delivery_by_agent = $agent_by)


							");
					}
					else{
						  "SELECT
                                SUM(if(a.paid_reject = '1',if(if(berat_aktual is null or berat_aktual < 1,  berat_barang, berat_aktual) < ifnull(d.min_kg,ifnull(e.min_kg,f.min_kg)), (ifnull(d.min_kg,ifnull(e.min_kg,f.min_kg))*ifnull(d.price,ifnull(e.price,f.price)))+a.biaya_packing_agent+a.biaya_tambahan_agent , (if(berat_aktual is null or berat_aktual < 1,  berat_barang, berat_aktual)*ifnull(d.price,ifnull(e.price,f.price)))+a.biaya_packing_agent+a.biaya_tambahan_agent ),0)) AS total

                            FROM pengiriman a
                            LEFT JOIN kota b ON b.id_kota=a.lokasi_tujuan
                            OR b.id_kota=a.lokasi_asal
                            LEFT JOIN cabang c ON c.id_kota=b.id_kota OR b.hub=c.id_kota

                        LEFT JOIN rate_agent d ON d.id_cabang=CASE WHEN x_intercity(a.lokasi_asal)='Y' THEN '39' ELSE c.id_cabang END AND d.id_kota=a.lokasi_tujuan AND d.id_pelayanan=CASE WHEN a.service > 3 THEN 3 else a.service end

                            LEFT JOIN rate_agent_inbound e ON e.id_kota=a.lokasi_asal  and e.id_pelayanan=CASE WHEN a.service > 3 THEN 3 else a.service end and e.id_cabang=CASE WHEN x_intercity(a.lokasi_tujuan)='Y' THEN 0 else 99 end

                            LEFT JOIN rate_agent_cross f ON f.id_kota_asal=CASE WHEN x_intercity(a.lokasi_asal)!='Y' THEN a.lokasi_asal ELSE 0 END AND f.id_kota_tujuan=CASE WHEN x_intercity(a.lokasi_tujuan)!='Y' THEN a.lokasi_tujuan ELSE 0 END AND f.id_pelayanan=CASE WHEN a.service > 3 THEN 3 else a.service end

                            WHERE (c.id_cabang=$agent_by AND a.tanggal BETWEEN '$month-01' AND '$month-31' and service != 6 and a.status != 7 ) and ( pickup_by_agent = $agent_by or delivery_by_agent = $agent_by or pickup_by_agent is null or delivery_by_agent is null or pickup_by_agent != $agent_by or delivery_by_agent != $agent_by)
							";

						$qtotal = $mysqli->query("SELECT
                                SUM(if(a.paid_reject = '1',if(if(berat_aktual is null or berat_aktual < 1,  berat_barang, berat_aktual) < ifnull(d.min_kg,ifnull(e.min_kg,f.min_kg)), (ifnull(d.min_kg,ifnull(e.min_kg,f.min_kg))*ifnull(d.price,ifnull(e.price,f.price)))+a.biaya_packing_agent+a.biaya_tambahan_agent , (if(berat_aktual is null or berat_aktual < 1,  berat_barang, berat_aktual)*ifnull(d.price,ifnull(e.price,f.price)))+a.biaya_packing_agent+a.biaya_tambahan_agent ),0)) AS total

                            FROM pengiriman a
                            LEFT JOIN kota b ON b.id_kota=a.lokasi_tujuan
                            OR b.id_kota=a.lokasi_asal
                            LEFT JOIN cabang c ON c.id_kota=b.id_kota OR b.hub=c.id_kota

                        LEFT JOIN rate_agent d ON d.id_cabang=CASE WHEN x_intercity(a.lokasi_asal)='Y' THEN '39' ELSE c.id_cabang END AND d.id_kota=a.lokasi_tujuan AND d.id_pelayanan=CASE WHEN a.service > 3 THEN 3 else a.service end

                            LEFT JOIN rate_agent_inbound e ON e.id_kota=a.lokasi_asal  and e.id_pelayanan=CASE WHEN a.service > 3 THEN 3 else a.service end and e.id_cabang=CASE WHEN x_intercity(a.lokasi_tujuan)='Y' THEN 0 else 99 end

                            LEFT JOIN rate_agent_cross f ON f.id_kota_asal=CASE WHEN x_intercity(a.lokasi_asal)!='Y' THEN a.lokasi_asal ELSE 0 END AND f.id_kota_tujuan=CASE WHEN x_intercity(a.lokasi_tujuan)!='Y' THEN a.lokasi_tujuan ELSE 0 END AND f.id_pelayanan=CASE WHEN a.service > 3 THEN 3 else a.service end

                            WHERE (c.id_cabang=$agent_by AND a.tanggal BETWEEN '$month-01' AND '$month-31' and service != 6 and a.status != 7 ) and ( pickup_by_agent = $agent_by or delivery_by_agent = $agent_by or pickup_by_agent is null or delivery_by_agent is null or pickup_by_agent != $agent_by or delivery_by_agent != $agent_by)


							");
					}
						$st = $qtotal->fetch_assoc();


						$sqlBA = "select ifnull(biaya_admin,0) as biaya_admin, lock_count
								from invoice_agent_status where id_agent = ".$agent_by." and date_format(invoice_date, '%Y-%m') = '".$month."'";
						$qrBA = $mysqli->query($sqlBA);
						$resBA = $qrBA->fetch_assoc();

						$lockCount = ($resBA['lock_count']) ? $resBA['lock_count'] : 0;
						$lockDisabled = ($lockCount < 3) ? '' : 'disabled';
						$biayaAdmin = ($resBA['biaya_admin']) ? $resBA['biaya_admin'] : 0;
						$btnBiayaAdmin = ($u['level']!='agent') ? '<button type="button" class="btn btn-default btn-xs " id="open_biaya_admin"  data-id="'.$agent_by.'" data-month="'.$month.'" data-biaya="'.$biayaAdmin.'"> Tambah <i class="fa fa-plus text-orange"></i></button>' : '';

						echo'
							<table class="table">
              					<tr>
                					<th>Subtotal</th>
                					<td style="text-align:right;">Rp '.number_format($sum_total).'</td>
              					</tr>
								<tr>
									<th>Biaya Admin '.$btnBiayaAdmin.'</th>
									<td style="text-align:right;">
										Rp '.number_format($biayaAdmin).'
									</td>
								</tr>
								<tr>
                					<th>Total</th>
                					<td style="text-align:right;">Rp '.number_format($sum_total+$biayaAdmin).'</td>
              					</tr>
            				</table>
          				</div>
        			</div>
				</div>

      			<div class="row no-print">
        			<div class="col-xs-12">
        			';
$key		= "asdf$1011";
			$encrypt	= "$_GET[id]";
			$id 		= base64_decrypt($encrypt,$key);
			//echo $id;

			$pieces = explode('~', $id);
			$agent_by = $pieces['0'];
			$month = $pieces['1'];

							$query 		= $mysqli->query("select * from cabang where id_cabang=$agent_by

				");
        			//echo "select * from cabang where id_cabang=$agent_by";
			$r= $query->fetch_assoc();


			$cek_status = $mysqli->query("SELECT
                               if(a.paid_reject = '1',if(if(berat_aktual is null or berat_aktual < 1,  berat_barang, berat_aktual) < ifnull(d.min_kg,ifnull(e.min_kg,f.min_kg)), (ifnull(d.min_kg,ifnull(e.min_kg,f.min_kg))*ifnull(d.price,ifnull(e.price,f.price)))+a.biaya_packing_agent+a.biaya_tambahan_agent , (if(berat_aktual is null or berat_aktual < 1,  berat_barang, berat_aktual)*ifnull(d.price,ifnull(e.price,f.price)))+a.biaya_packing_agent+a.biaya_tambahan_agent ),0) AS total

                            FROM pengiriman a
                            LEFT JOIN kota b ON b.id_kota=a.lokasi_tujuan
                            OR b.id_kota=a.lokasi_asal
                            LEFT JOIN cabang c ON c.id_kota=b.id_kota OR b.hub=c.id_kota

                        LEFT JOIN rate_agent d ON d.id_cabang=CASE WHEN x_intercity(a.lokasi_asal)='Y' THEN '39' ELSE c.id_cabang END AND d.id_kota=a.lokasi_tujuan AND d.id_pelayanan=CASE WHEN a.service > 3 THEN 3 else a.service end

                            LEFT JOIN rate_agent_inbound e ON e.id_kota=a.lokasi_asal  and e.id_pelayanan=CASE WHEN a.service > 3 THEN 3 else a.service end and e.id_cabang=CASE WHEN x_intercity(a.lokasi_tujuan)='Y' THEN 0 else 99 end

                            LEFT JOIN rate_agent_cross f ON f.id_kota_asal=CASE WHEN x_intercity(a.lokasi_asal)!='Y' THEN a.lokasi_asal ELSE 0 END AND f.id_kota_tujuan=CASE WHEN x_intercity(a.lokasi_tujuan)!='Y' THEN a.lokasi_tujuan ELSE 0 END AND f.id_pelayanan=CASE WHEN a.service > 3 THEN 3 else a.service end

                            WHERE (c.id_cabang=$agent_by AND a.tanggal BETWEEN '$month-01' AND '$month-31' and service != 6 and a.status != 7 ) and ( pickup_by_agent = $agent_by or delivery_by_agent = $agent_by or pickup_by_agent is null or delivery_by_agent is null or pickup_by_agent != $agent_by or delivery_by_agent != $agent_by)


							");


			$cek_draft = $mysqli->query("SELECT
                               id_pengiriman,status_inv_agent

                            FROM pengiriman a
                            LEFT JOIN rate_agent d ON d.id_kota=id_kecamatan_tujuan AND d.id_pelayanan=a.service and a.delivery_by_agent = $agent_by and d.id_cabang=$agent_by

							LEFT JOIN rate_agent_inbound e ON e.id_kota=id_kecamatan_asal  and e.id_pelayanan=a.service and a.pickup_by_agent = $agent_by and e.id_cabang = $agent_by

							LEFT JOIN rate_agent_cross f ON f.id_kota_asal = a.id_kecamatan_asal and f.id_kota_tujuan = a.id_kecamatan_tujuan  AND a.pickup_by_agent=$agent_by and a.delivery_by_agent = $agent_by and id_agent = $agent_by



                            WHERE ( a.tanggal BETWEEN '$month-01' AND '$month-31' and service != 6 and a.status != 7 ) and ( pickup_by_agent = $agent_by or delivery_by_agent = $agent_by or pickup_by_agent is null or delivery_by_agent is null or pickup_by_agent != $agent_by or delivery_by_agent != $agent_by) and (status_inv_agent is null or status_inv_agent = '')


							");

						$d = $cek_draft->fetch_assoc();
          				$draft = $cek_draft->num_rows;
        					//tombol print
          				//<a target="_blank" href="reports/inv_agent.php?id_agent='.$agent_by.'&date='.$month.'"><button type="button" class="btn btn-primary pull-right" ><i class="fa fa-file-print-o"></i> Print</button></a>

        			echo '


							<a target="_blank" href="reports/PHPExcel/excel_create_invagent.php?id_agent='.$agent_by.'&date='.$month.'"><button type="button" class="btn btn-primary pull-right" ><i class="fa fa-file-excel-o"></i> Excel</button></a>

							';

							$cek_st = $mysqli->query("SELECT
                               id_pengiriman,status_inv_agent

                            FROM pengiriman a

                           WHERE (    a.tanggal BETWEEN '$month-01' AND '$month-31' and a.service != 6 and a.status != 7 ) and ( pickup_by_agent = $agent_by or delivery_by_agent = $agent_by) group by a.id_pengiriman


							");
							$c = $cek_st->fetch_assoc();


							/*
							$cek_harga = $mysqli->query("SELECT
                               id_pengiriman

                            FROM pengiriman a
                            LEFT JOIN kota b ON b.id_kota=a.lokasi_tujuan
                            OR b.id_kota=a.lokasi_asal
                            LEFT JOIN cabang c ON c.id_kota=b.id_kota OR b.hub=c.id_kota

                        LEFT JOIN rate_agent d ON d.id_cabang=CASE WHEN x_intercity(a.lokasi_asal)='Y' THEN '39' ELSE c.id_cabang END AND d.id_kota=a.lokasi_tujuan AND d.id_pelayanan=CASE WHEN a.service > 3 THEN 3 else a.service end

                            LEFT JOIN rate_agent_inbound e ON e.id_kota=a.lokasi_asal  and e.id_pelayanan=CASE WHEN a.service > 3 THEN 3 else a.service end and e.id_cabang=CASE WHEN x_intercity(a.lokasi_tujuan)='Y' THEN 0 else 99 end

                            LEFT JOIN rate_agent_cross f ON f.id_kota_asal=CASE WHEN x_intercity(a.lokasi_asal)!='Y' THEN a.lokasi_asal ELSE 0 END AND f.id_kota_tujuan=CASE WHEN x_intercity(a.lokasi_tujuan)!='Y' THEN a.lokasi_tujuan ELSE 0 END AND f.id_pelayanan=CASE WHEN a.service > 3 THEN 3 else a.service end

                            WHERE (c.id_cabang=$agent_by AND a.tanggal BETWEEN '$month-01' AND '$month-31' and service != 6 and a.status != 7 ) and (d.price is null and e.price is null and f.price is null) and ( pickup_by_agent = $agent_by or delivery_by_agent = $agent_by or pickup_by_agent is null or delivery_by_agent is null or pickup_by_agent != $agent_by or delivery_by_agent != $agent_by)


							");
							$h =  $cek_harga->num_rows;
							*/

							$sql = $mysqli->query($cari);
							while($row = $sql->fetch_assoc()){
								echo '
								<input type="hidden" name="berat['.$row['id_pengiriman'].']" form="kunciform" value="'.$row['berat_tampil'].'" >
								<input type="hidden" name="hargaperkg['.$row['id_pengiriman'].']" form="kunciform" value="'.$row['pricee'].'" >
								<input type="hidden" name="minkg['.$row['id_pengiriman'].']" form="kunciform" value="'.$row['min_kgg'].'" >
								<input type="hidden" name="total['.$row['id_pengiriman'].']" form="kunciform" value="'.$row['new_total'].'" >
								<input type="hidden" name="diskon['.$row['id_pengiriman'].']" form="kunciform" value="'.$row['diskon'].'" >

								<input type="hidden" name="id_pengiriman['.$row['id_pengiriman'].']" form="resetkunci" value="0" >


											';
							}
							echo '<input type="hidden" name="agent" form="resetkunci" value="'.$agent_by.'" > ';
							echo '<input type="hidden" name="agent" form="kunciform" value="'.$agent_by.'" > ';
							echo '<input type="hidden" name="periode" form="kunciform" value="'.$month.'" > ';
							echo '<span id="spTombolKunci" >';

							if($u['level']!='agent' && $kunci == '' || ($id_user == 3 || $id_user == 8)){

								echo '<button type="submit" '.$lockDisabled.' id="btnKunciHarga" form="kunciform" class="btn btn-success" id="kunci" Value="Kunci Harga"> Kunci Harga ('.$lockCount.') </button>';

								echo '
									<a href="module/mod_finance_invoice-agent/invoice-agent_action.php?module=invoice-agent&act=draft&agent='.$agent_by.'&status='.$c['status_inv_agent'].'&awal='.$month.'-01&akhir='.$month.'-31"><button type="button" class="btn btn-primary" '.($emptyPrice > 0 ? 'disabled' : '').'><i class="fa fa-key"></i>';

									if($c['status_inv_agent'] == ""){
											echo "klik to draft ";
										}
										elseif($c['status_inv_agent'] == 1){
											echo "klik to final ";
										}
										elseif($c['status_inv_agent'] == 2){
											echo "final ";
										}

									echo $emptyPrice.' status ='.$c['status_inv_agent'].'</button></a>';
							}

							if($u['level']!='agent')
							echo ' <button type="submit" '.$lockDisabled.' form="resetkunci" class="btn btn-success" id="kunci" Value="Reset Kunci"> Reset Kunci ('.$lockCount.') </button>';

                            echo '</span>';
						//$row_cek =mysqli_num_rows($rowcek);

						//<a href="../e-cesco/module/mod_invoice-customer/invoice-customer_action.php?module=invoice-customer&act=nomor&idinvoice='.$id_inv.'"  class="btn btn-primary pull-right " '.($r['nomor'] == 'DRAFT' ? ($row_cek == 0 ? 'enabled' : 'disabled') : 'disabled' ).'>'.$r['nomor'].$row_count.$row_cek.' Buat No Invoice </a>

 					 echo '
        			</div>
      			</div>
    		</section>
    		<div class="clearfix"></div>

				<div id="modal_nomor" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
								<h4 class="modal-title" id="myModalLabel">Edit Invoice</h4>
							</div>
							<div class="modal-content">
								<div class="modal-body">
								<form action="'.$aksi.'?module=invoice-agent&act=nomor-invoice" name="form_costing" method="POST">
									<input type="hidden" name="idAgent" value="" id="idAgent">
									<input type="hidden" name="month" value="" id="month">
									<input type="text" class="form-control" id="nomor" placeholder="No Invoice" name="nomor" required>
							</div>
							<div class="modal-footer">
									<button class="btn btn-success" type="submit">Update</button>
									<button type="reset" class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Cancel</button>
									</div>
								</form>
								</div>

							</div>
						</div>
					</div>
				</div>

			<div id="modal_price_insert_agent" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
							<h4 class="modal-title" id="myModalLabel">Buat Harga</h4>
						</div>
						<div class="modal-content">
							<div class="modal-body">

							</div>

						</div>
					</div>
				</div>
			</div>

			<div id="modal-rejectreason" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
							<h4 class="modal-title" id="myModalLabel">Reject</h4>
						</div>
						<div class="modal-content">
							<div class="modal-body">
								<form action="'.$aksi.'?module=invoice-agent&act=reject" name="form_costing" method="POST">
									<input type="hidden" name="id" value="" id="idpengiriman">
									<input type="text" class="form-control" id="reject_reason" placeholder="reject reason" name="reject_reason" required>
							</div>
							<div class="modal-footer">
									<button class="btn btn-success" type="submit">Confirm</button>
									<button type="reset" class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Cancel</button>
									</div>
								</form>
						</div>
					</div>
				</div>
			</div>

			<div id="modal-movecesco" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
							<h4 class="modal-title" id="myModalLabel">Move </h4>
						</div>
						<div class="modal-content">
						    <form action="'.$aksi.'?module=invoice-agent&act=move" name="form_costing" method="POST">
                                <div class="modal-body">

                                        <input type="hidden" name="id" value="" id="idpengiriman">
                                        <div class="form-group">
                                            <label class="col-md-3 control-label">Move Date <span class="required">* </span></label>
                                            <div class="input-group col-md-9">
                                                        <div class="input-group-addon">
                                                            <i class="fa fa-calendar"></i>
                                                        </div>
                                                        <input type="text" class="form-control" id="tanggal" name="tanggal" >
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-3 control-label">Move Agent <span class="required">* </span></label>

                                            <div class="col-md-9">
                                                    <select " class="form-control" name="move_agent" >
                                                        ';
                                                            $select_agent = mysqli_query($con,"select id_cabang,nama_cabang from cabang");
                                                            while($c=mysqli_fetch_assoc($select_agent)){
                                                                echo '<option value="'.$c['id_cabang'].'" '.($c['id_cabang'] == $agent_by ? 'selected' : '').'> '.$c['nama_cabang'].'</option>';
                                                            }
                                                        echo '
                                                    </select>
                                            </div>
                                        </div>
                                </div>
                                <div class="modal-footer">
                                    <input type="hidden" name="id_agent" value="'.$agent_by.'">
                                    <button class="btn btn-success" type="submit">Confirm</button>
                                    <button type="reset" class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Cancel</button>
                                </div>
                            </form>
						</div>
					</div>
				</div>
			</div>

			<div id="modal-opendiskon" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
							<h4 class="modal-title" id="myModalLabel">Diskon </h4>
						</div>
						<div class="modal-content">
						    <form action="'.$aksi.'?module=invoice-agent&act=diskon" name="form_costing" method="POST">
                                <div class="modal-body">

                                        <input type="hidden" name="id" value="" id="idpengiriman">
                                        <div class="form-group">
                                            <label class="col-md-3 control-label">Diskon (%)<span class="required">* </span></label>
                                            <div class="col-md-9">
                                                        <input type="number" class="form-control" id="diskon" name="diskon" size="10" >
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-3 control-label">Alasan <span class="required">* </span></label>

                                            <div class="col-md-9">
                                                    <input type="text" class="form-control" id="alasan" name="alasan" >
                                            </div>
                                        </div>
                                </div>
                                <br>
                                <div class="modal-footer">
                                    <button class="btn btn-success" type="submit">Confirm</button>
                                    <button type="reset" class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Cancel</button>
                                </div>
                            </form>
						</div>
					</div>
				</div>
			</div>

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
			<div id="modal_biaya_admin" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
							<h4 class="modal-title" id="myModalLabel">Tambah biaya Admin</h4>
						</div>
						<div class="modal-content">
							<div class="modal-body">
								<form action="'.$aksi.'?module=invoice-agent&act=tambah-biaya-admin" method="POST">
								<input type="hidden" name="idAgent" id="idAgent" class="form-control" value="" />
								<input type="hidden" name="month" id="month" class="form-control" value="" />
									<div class="form-group" style="padding-bottom: 25px;">
										<label class="col-md-3 control-label">Biaya Admin</label>
										<div class="col-md-8">
											<input type="number" name="biaya" id="biaya" class="form-control" value="0" />
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
			';

			break;

			case "detail":

                $key		= "asdf$1011";
                $encrypt	= "$_GET[id]";
                $id 		= base64_decrypt($encrypt,$key);
                //echo $id;

                $pieces = explode('-', $id);
                $agent_by = $pieces['0'];
                $month = implode('-', array_slice($pieces, 1, 3));

                $query 		= $mysqli->query("select * from cabang where id_cabang=$agent_by

				");
                $r= $query->fetch_assoc();
                $tanggal = $month;

                //$id_inv = $r['id'];

                echo'
			<section class="invoice">

				<div class="row invoice-info">
					<div class="col-sm-8 invoice-col">
						';

                echo'
							<address>
								<strong>'.$r['nama_cabang'].','.$r['alamat1'].'</strong><br>
	            				'.$r['alamat2'].'<br>
	            			Phone: '.$r['telp'].'
	          				</address>';
                echo'
        			</div>
        			<div class="col-sm-4 invoice-col">
          				<b>Tanggal: </b><span id="edit-duedate">'.$month.'</span>
        			</div>
				</div>

	  			<div class="row">
        			<div class="box-body">

						<table id="report" class="table table-striped table-bordered table-hover " width="100%" cellspacing="0" style="white-space:nowrap;">
						<thead>
							<tr>
									<th rowspan="2">Booking</th>
									<th rowspan="2">Airwaybill</th>
									<th rowspan="2">Komoditi</th>
									<th rowspan="2">Service Type</th>
									<!--<th rowspan="2">Requestor</th>-->
									<th rowspan="2">Customers Name</th>
									<th rowspan="2">Lockcode</th>
									<th rowspan="2">Consignee</th>
									<th colspan="3"><center>Area</center></th>
									<th colspan="3"><center>Penerima</center></th>
									<th rowspan="2">Alasan(Jika) Terlambat</th>
									<th rowspan="2">Berat</th>
									<th rowspan="2">Harga</th>
									<th rowspan="2">Min. Kg</th>
									<th rowspan="2">Packing</th>
									<th rowspan="2">Add Charge</th>
									<th rowspan="2">Detail Add Charge</th>
									<th rowspan="2">Total</th>
									<th rowspan="2">Diskon (%)</th>
									<th rowspan="2">Total setelah Diskon</th>
									<th rowspan="2">Paid/Reject</th>
								</tr>
								<tr>
									<th>Kota Asal</th>
									<th>Kota Tujuan</th>
									<th width="800px;">Alamat Lengkap Tujuan</th>
									<th>Nama</th>
									<th>Tanggal</th>
									<th>Lead time</th>
								</tr>
						</thead>
						<tbody>';

                if($r['id_agent'] == 49 || $r['id_agent'] == 58 || $r['id_agent'] == 49 || $r['id_agent'] == 21 || $r['id_agent'] == 22 || $r['id_agent'] == 42){
                    $cari = "
							SELECT
								id_pengiriman,
                                date(tanggal) as tanggal,
                                airwaybill_kode,
                                case when a.komoditi='1' then 'Paket' else 'Dokumen' end as komoditi,
                                x_service(a.service) as service,
                                case when a.service=5 then 3 else a.service end as srv,
                                nama_pengirim,
                                loc_code,
                                x_namakustomer(id_kustomer) as namakustomer,
                                nama_penerima,
                                a.lokasi_asal as id_asal,
                                a.lokasi_tujuan as id_tujuan,
                                x_namakotabesar(lokasi_asal) as lokasi_asal,
                                lokasi_tujuan,
                                b.id_kota,
                                b.intercity as kota_in,
                                x_namakotabesar(lokasi_tujuan) as lokasi_tujuan,
                                alamat_penerima,
                                delivery_name,
                                delivery_date,
                                alasan_terlambat,
                                if(delivery_date is null,'-', datediff(delivery_date, date(pickup_date))) as selisih_hari,
                                SUBSTRING_INDEX(ukuran_barang,'x',1) as length, SUBSTRING_INDEX(SUBSTRING_INDEX(ukuran_barang,'x',2),'x',-1) as width, SUBSTRING_INDEX(ukuran_barang,'x',-1) as height,
                                 CASE
										WHEN airwaybill_kode LIKE '001-%' THEN 0
										WHEN airwaybill_kode LIKE '002-%' THEN 1
									end as intercity,
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
                                if(berat_aktual is null or berat_aktual < 1,  berat_barang, berat_aktual) as berat_aktual,
                                berat_aktual as brt_akt,
                            	berat_barang as brt_brg,
                            	kunci_berat_agent,
                                ifnull(d.price,ifnull(e.price,f.price)) as price,
                                ifnull(d.price,ifnull(e.price,f.price)) as priceee,
                                ifnull(  kunci_hargaperkg_agent ,  (select priceee) ) as pricee,

                                ifnull(d.min_kg,ifnull(e.min_kg,f.min_kg)) as min_kg,
                                ifnull(d.min_kg,ifnull(e.min_kg,f.min_kg)) as min_kggg,
                                ifnull(  kunci_minkg_agent ,  (select min_kggg) ) as min_kgg,

                                 if ((select berat_real) >= (select volume), (select berat_real), (select volume) ) as berat,

									if ((select min_kgg) >= (select berat), (select min_kgg), (select berat) ) as berat_finishh,

									ifnull(  kunci_berat_agent ,  (select berat_finishh) ) as berat_finish,

									ifnull(  kunci_berat_agent ,  (select berat_real) ) as kunci_berat,

                                a.biaya_packing_agent,
                                a.biaya_tambahan_agent,
                                a.keterangan_biaya_agent as keterangan_biaya,
                                if(a.paid_reject = '1',if(if(berat_aktual is null or berat_aktual < 1,  berat_barang, berat_aktual) < (select min_kgg), ((select min_kgg)*(select pricee))+a.biaya_packing_agent+a.biaya_tambahan_agent , (if(berat_aktual is null or berat_aktual < 1,  berat_barang, berat_aktual)*(select pricee))+a.biaya_packing_agent+a.biaya_tambahan_agent ),0) as total,

                                ((select berat_finish) * (select pricee))+a.biaya_packing_agent+a.biaya_tambahan_agent as new_totall ,

                                ifnull(  kunci_total_agent ,  (select new_totall) ) as new_total,

                                d.rateagent_id,
                                ifnull(d.rateagent_id,ifnull(e.rateagentinbound_id,f.rate_id)) as idrate,
                                d.rateagent_id as idrate_out,
                            	e.rateagentinbound_id as idrate_in,
                            	f.rate_id as idrate_cross,

                            	CASE WHEN x_intercity(a.lokasi_asal)='Y' THEN '39' ELSE c.id_cabang END as cabang_asal,
                            	a.paid_reject,
                            	a.reject_reason,
                            	a.diskon,
                            	ifnull( DATE_FORMAT( DATE_ADD(a.created_date, INTERVAL case when a.service=1 then
                                    CASE
                                    WHEN airwaybill_kode LIKE '001-%' THEN 1
                                    WHEN airwaybill_kode LIKE '002-%' THEN 0
                                    END
                                when a.service=2 then 1 else s.sla_max end DAY), '%Y-%m-%d' ) ,'0' ) as sla_max,
                                CONCAT(DATE_FORMAT(a.created_date,'%Y-%m-%d'),' ',

                                    ifnull( DATE_FORMAT( DATE_ADD(a.created_date, INTERVAL case when a.service=1 then CASE
                                    WHEN airwaybill_kode LIKE '001-%' THEN 1
                                    WHEN airwaybill_kode LIKE '002-%' THEN 0
                                    END  when a.service=2 then 1 else s.sla_max end DAY), '%Y-%m-%d' ) ,'0' )
                                    ) as sla_date
							FROM pengiriman a
							INNER JOIN kota b ON b.id_kota=a.lokasi_tujuan
							OR b.id_kota=a.lokasi_asal
							INNER JOIN cabang c ON c.id_kota=b.id_kota OR b.hub=c.id_kota
						LEFT JOIN rate_agent d ON d.id_cabang=CASE WHEN x_intercity(a.lokasi_asal)='Y' THEN '39' ELSE c.id_cabang END AND d.id_kota=a.lokasi_tujuan AND d.id_pelayanan=CASE WHEN a.service > 3 THEN 3 else a.service end

							LEFT JOIN rate_agent_inbound e ON e.id_kota=a.lokasi_asal  and e.id_pelayanan=CASE WHEN a.service > 3 THEN 3 else a.service end and e.id_cabang=CASE WHEN x_intercity(a.lokasi_tujuan)='Y' THEN 0 else 99 end

							LEFT JOIN rate_agent_cross f ON f.id_kota_asal=CASE WHEN x_intercity(a.lokasi_asal)!='Y' THEN a.lokasi_asal ELSE 0 END AND f.id_kota_tujuan=CASE WHEN x_intercity(a.lokasi_tujuan)!='Y' THEN a.lokasi_tujuan ELSE 0 END AND d.id_pelayanan=CASE WHEN a.service > 3 THEN 3 else a.service end
                            left join sla s on s.kota_asal =  a.id_kota_asal   AND s.kota_tujuan=  a.id_kota_tujuan  AND s.service = a.service
							WHERE (c.id_cabang=$agent_by AND a.tanggal BETWEEN '$month-01' AND '$month-31' and a.service != 6 and a.status != 7 ) and ( pickup_by_agent = $agent_by or delivery_by_agent = $agent_by)
							";

                }
                else{
                    $cari = "
							SELECT
								id_pengiriman,
                                date(tanggal) as tanggal,
                                airwaybill_kode,
                                case when a.komoditi='1' then 'Paket' else 'Dokumen' end as komoditi,
                                x_service(a.service) as service,
                                case when a.service=5 then 3 else a.service end as srv,
                                nama_pengirim,
                                loc_code,
                                x_namakustomer(id_kustomer) as namakustomer,
                                nama_penerima,
                                a.lokasi_asal as id_asal,
                                a.lokasi_tujuan as id_tujuan,
                                x_namakotabesar(lokasi_asal) as lokasi_asal,
                                lokasi_tujuan,
                                b.id_kota,
                                b.intercity as kota_in,
                                x_namakotabesar(lokasi_tujuan) as lokasi_tujuan,
                                alamat_penerima,
                                delivery_name,
                                delivery_date,
                                pickup_by_agent,
                                alasan_terlambat,
                                if(delivery_date is null,'-', datediff(delivery_date, date(pickup_date))) as selisih_hari,
                                SUBSTRING_INDEX(ukuran_barang,'x',1) as length, SUBSTRING_INDEX(SUBSTRING_INDEX(ukuran_barang,'x',2),'x',-1) as width, SUBSTRING_INDEX(ukuran_barang,'x',-1) as height,
                                 CASE
										WHEN airwaybill_kode LIKE '001-%' THEN 0
										WHEN airwaybill_kode LIKE '002-%' THEN 1
									end as intercity,
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
                                if(berat_aktual is null or berat_aktual < 1,  berat_barang, berat_aktual) as berat_aktual,
                                berat_aktual as brt_akt,
                            	berat_barang as brt_brg,
                            	c.id_cabang as cabang_id,
                            	kunci_berat_agent,
                                ifnull(d.price,ifnull(e.price,f.price)) as price,
                                ifnull(d.price,ifnull(e.price,f.price)) as priceee,
                                ifnull(  kunci_hargaperkg_agent ,  (select priceee) ) as pricee,

                                ifnull(d.min_kg,ifnull(e.min_kg,f.min_kg)) as min_kg,
                                ifnull(d.min_kg,ifnull(e.min_kg,f.min_kg)) as min_kggg,
                                ifnull(  kunci_minkg_agent ,  (select min_kggg) ) as min_kgg,

                                 if ((select berat_real) >= (select volume), (select berat_real), (select volume) ) as berat,

									if ((select min_kgg) >= (select berat_real), (select min_kgg), (select berat_real) ) as berat_finishh,

									ifnull(  kunci_berat_agent ,  (select berat_finishh) ) as berat_finish,

									ifnull(  kunci_berat_agent ,  (select berat_real) ) as kunci_berat,

                                a.biaya_packing_agent,
                                a.biaya_tambahan_agent,
                                a.keterangan_biaya_agent as keterangan_biaya,
                                if(a.paid_reject = '1',if(if(berat_aktual is null or berat_aktual < 1,  berat_barang, berat_aktual) < (select min_kgg), ((select min_kgg)*(select pricee))+a.biaya_packing_agent+a.biaya_tambahan_agent , (if(berat_aktual is null or berat_aktual < 1,  berat_barang, berat_aktual)*(select pricee))+a.biaya_packing_agent+a.biaya_tambahan_agent ),0) as total,

                                ((select berat_finish) * (select pricee))+a.biaya_packing_agent+a.biaya_tambahan_agent as new_totall ,

                                ifnull(  kunci_total_agent ,  (select new_totall) ) as new_total,
                                d.rateagent_id,
                                ifnull(d.rateagent_id,ifnull(e.rateagentinbound_id,f.rate_id)) as idrate,
                                d.rateagent_id as idrate_out,
                            	e.rateagentinbound_id as idrate_in,
                            	f.rate_id as idrate_cross,
                            	CASE WHEN x_intercity(a.lokasi_asal)='Y' THEN '39' ELSE c.id_cabang END as cabang_asal,
                            	a.paid_reject,
                            	a.reject_reason,
                            	a.diskon,
                            	ifnull( DATE_FORMAT( DATE_ADD(a.created_date, INTERVAL case when a.service=1 then
                                    CASE
                                    WHEN airwaybill_kode LIKE '001-%' THEN 1
                                    WHEN airwaybill_kode LIKE '002-%' THEN 0
                                    END
                                when a.service=2 then 1 else s.sla_max end DAY), '%Y-%m-%d' ) ,'0' ) as sla_max,
                                CONCAT(DATE_FORMAT(a.created_date,'%Y-%m-%d'),' ',

                                    ifnull( DATE_FORMAT( DATE_ADD(a.created_date, INTERVAL case when a.service=1 then CASE
                                    WHEN airwaybill_kode LIKE '001-%' THEN 1
                                    WHEN airwaybill_kode LIKE '002-%' THEN 0
                                    END  when a.service=2 then 1 else s.sla_max end DAY), '%Y-%m-%d' ) ,'0' )
                                    ) as sla_date
							FROM pengiriman a
							LEFT JOIN kota b ON b.id_kota=a.lokasi_tujuan
							OR b.id_kota=a.lokasi_asal
							LEFT JOIN cabang c ON c.id_cabang=a.pickup_by_agent OR c.id_cabang=a.delivery_by_agent

						LEFT JOIN rate_agent d ON  d.id_kota=a.id_kota_tujuan AND d.id_pelayanan=CASE WHEN a.service > 3 THEN 3 else a.service end

							LEFT JOIN rate_agent_inbound e ON e.id_kota=a.id_kota_asal  and e.id_pelayanan=CASE WHEN a.service > 3 THEN 3 else a.service end

							LEFT JOIN rate_agent_cross f ON case when a.pickup_by_agent=$agent_by THEN f.id_kota_asal else f.id_kota_tujuan END  = case when a.pickup_by_agent=$agent_by THEN a.id_kota_asal else a.id_kota_tujuan END  AND f.id_pelayanan=a.service

                            left join sla s on s.kota_asal =  a.id_kota_asal   AND s.kota_tujuan=  a.id_kota_tujuan  AND s.service = a.service
							WHERE ( c.id_cabang =$agent_by and   a.tanggal BETWEEN '$month-01' AND '$month-31' and a.service != 6 and a.status != 7 ) and ( pickup_by_agent = $agent_by or delivery_by_agent = $agent_by) group by a.id_pengiriman
							";

                }
                //echo $cari;

                $sql = $mysqli->query($cari);
                $row_count= $sql->num_rows;
                $sum_total = 0;
                while($row = $sql->fetch_assoc()){
                    $new_total = ($row['paid_reject'] == 1 ? $row['new_total'] : 0);
                    //$sum_total = $sum_total + $new_total;

                    $key = "asdf$1011";
                    $stringawal = $row["id_pengiriman"];
                    $id = base64_encrypt($stringawal,$key);
                    //$volume = ($r['length']*$r['width']*$r['height'])/6000;
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

                    $price = '';
                    if($row['pricee']>0){

                        if($row['idrate_out']>0){
                            $price = $row['pricee'];
                        }elseif($row['idrate_in']>0){
                            $price = $row['pricee'];
                        }elseif($row['idrate_cross']>0){
                            $price = $row['pricee'];
                        }


                    }

                    if($row['min_kgg']>0){

                        if($row['idrate_out']>0){
                            $min_kg = $row['min_kgg'];
                        }elseif($row['idrate_in']>0){
                            $min_kg = $row['min_kgg'];
                        }elseif($row['idrate_cross']>0){
                            $min_kg = $row['min_kgg'];
                        }


                    }else{
                        $min_kg='-';
                    }


                    ///SLA
                    $d = $row['sla_date'];
                    $pieces = explode(' ', $d);

                    $booking_date = $pieces[0];

                    $d1 = strtotime($pieces[1]);
                    $d2 = date('l',$d1);
                    $dt3 = strtolower($d2);

                    $telat = "";

                    if($dt3 == "saturday" )
                    {
                        $sla = date('Y-m-d',strtotime($pieces[1].'+2 day'));
                    }
                    elseif($dt3 == "sunday")
                    {
                        $sla = date('Y-m-d',strtotime($pieces[1].'+1 day'));
                    }else{
                        $sla = date('Y-m-d',strtotime($pieces[1]));
                    }

                    $date_now =  date("Y-m-d");

                    if($sla != '1970-01-01'){

                        if ($date_now > $sla && $row['selisih_hari'] > 0){

                            $telat = '<button type="button" class="btn btn-xs btn-danger" title="TERLAMBAT"><i class="fa fa-exclamation-circle"></i> </button>';

                            $created_date = $booking_date. ' - '.$sla . '<button class="btn btn-xs btn-danger" title="TERLAMBAT"><i class="fa fa-exclamation-circle"></i> </button>';
                        }else{
                            $created_date =  $booking_date . ' - '.$sla;
                        }
                    }else{
                        $created_date =  $booking_date;
                    }
                    ////

                    $total = $row['new_total'];
                    $diskon = $row['diskon'];
                    $totalAfterDiskon = $total-(($diskon/100)*$total);
                    $sum_total = $sum_total + $totalAfterDiskon;

                    echo'
							<tr>
								<td width="20px">'.$row['tanggal'].'</td>
								<td width="20px">'.$row['airwaybill_kode'].'</td>
								<td width="20px">'.strtoupper($row['komoditi']).'</td>
								<td width="50px">'.strtoupper($row['service']).'</td>
								'; //<td width="20px">'.strtoupper($row['nama_pengirim']).'</td>'
                    echo '
								<td width="20px">'.strtoupper($row['namakustomer']).'</td>
								<td width="20px">'.strtoupper($row['loc_code']).'</td>
								<td width="20px">'.strtoupper($row['nama_penerima']).'</td>
								<td width="20px">'.strtoupper($lokasi_asal).'</td>
								<td width="20px">'.strtoupper($lokasi_tujuan).'</td>
								<td width="20px">'.strtoupper($row['alamat_penerima']).'</td>
								<td width="20px">'.strtoupper($row['delivery_name']).'</td>
								<td width="20px">'.$row['delivery_date'].'</td>
								<td width="20px"> '.$row['selisih_hari'].' hari '.$telat.'</td>

								';
                    echo '
								<td width="20px"> '.$row['alasan_terlambat'].'</td>
								<td width="20px" align="right">
								';

                        echo $row['kunci_berat'] ;

                    echo '
								</td>
								<td align="right">'.($u['level']=='agent' ? $row['price'] : $price).' id rate cross='. $row['
								'] .' dan '.$row['priceee'].' </td>
								<td align="right" >'.($u['level']=='agent' ? $row['min_kgg'] : $min_kg).'</td>

								';

                    if ($u['level']=='agent'){
                        echo '
									<td align="right" align="right">'.number_format($row['biaya_packing_agent']).'</td>
									<td align="right" align="right">'.number_format($row['biaya_tambahan_agent']).'</td>
									<td>'.$row['keterangan_biaya'].'</td>
									<td align="right">'.($row['paid_reject'] == 1 ? number_format($row['new_total']) : '-').'</td>
									<td align="right" align="right">'.$diskon.'</td>
									<td align="right" align="right">'.number_format($totalAfterDiskon).'</td>
									<td>'.($row[paid_reject] == 1 ? 'paid' : 'rejected, '.$row["reject_reason"]) .'</td>

									';
                    }
                    else{

                        echo '
									<td align="right" align="right">'.number_format($row['biaya_packing_agent']).'</td>
									<td align="right" align="right">'.number_format($row['biaya_tambahan_agent']).'</td>
									<td>'.$row['keterangan_biaya'].'</td>
									<td align="right">'.($row['paid_reject'] == 1 ? number_format($row['new_total']) : '-').'</td>
									<td align="right" align="right">'.$diskon.'</td>
									<td align="right" align="right" id="totAfterDisc'.$row['id_pengiriman'].'">'.number_format($totalAfterDiskon).'</td>
									<td>'.($row['paid_reject'] == 1 ? 'paid' : 'rejected, '.$row["reject_reason"]) .'</td>
									';
                    }
                    echo '
							</tr>';
                }
                echo'
						</tbody>
					</table
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

                $key		= "asdf$1011";
                $encrypt	= "$_GET[id]";
                $id_invoice	= base64_decrypt($encrypt,$key);

                if($r['id_agent'] == 49 || $r['id_agent'] == 58 || $r['id_agent'] == 49 || $r['id_agent'] == 21 || $r['id_agent'] == 22 || $r['id_agent'] == 42){
                    "SELECT
                                SUM(if(a.paid_reject = '1',if(if(berat_aktual is null or berat_aktual < 1,  berat_barang, berat_aktual) < ifnull(d.min_kg,e.min_kg), (ifnull(d.min_kg,e.min_kg)*ifnull(d.price,e.price))+a.biaya_packing_agent+a.biaya_tambahan_agent , (if(berat_aktual is null or berat_aktual < 1,  berat_barang, berat_aktual)*ifnull(d.price,e.price))+a.biaya_packing_agent+a.biaya_tambahan_agent ),0)) AS total

                            FROM pengiriman a
							INNER JOIN kota b ON b.id_kota=a.lokasi_tujuan
							OR b.id_kota=a.lokasi_asal
							INNER JOIN cabang c ON c.id_kota=b.id_kota OR b.hub=c.id_kota
						LEFT JOIN rate_agent d ON d.id_cabang=CASE WHEN x_intercity(a.lokasi_asal)='Y' THEN '39' ELSE c.id_cabang END AND d.id_kota=a.lokasi_tujuan AND d.id_pelayanan=CASE WHEN a.service > 3 THEN 3 else a.service end
							LEFT JOIN rate_agent_inbound e ON e.id_kota=a.lokasi_asal  and e.id_pelayanan=CASE WHEN a.service > 3 THEN 3 else a.service end
							WHERE (c.id_cabang=$agent_by AND a.tanggal BETWEEN '$r[dari]' AND '$r[sampai]' and service != 6 and a.status != 7 and paid_reject = '1') and ( pickup_by_agent = $agent_by or delivery_by_agent = $agent_by)


							";
                    $qtotal = $mysqli->query("SELECT
                                SUM(if(a.paid_reject = '1',if(if(berat_aktual is null or berat_aktual < 1,  berat_barang, berat_aktual) < ifnull(d.min_kg,ifnull(e.min_kg,f.min_kg)), (ifnull(d.min_kg,ifnull(e.min_kg,f.min_kg))*ifnull(d.price,ifnull(e.price,f.price)))+a.biaya_packing_agent+a.biaya_tambahan_agent , (if(berat_aktual is null or berat_aktual < 1,  berat_barang, berat_aktual)*ifnull(d.price,ifnull(e.price,f.price)))+a.biaya_packing_agent+a.biaya_tambahan_agent ),0)) AS total

                           FROM pengiriman a
                            LEFT JOIN kota b ON b.id_kota=a.lokasi_tujuan
                            OR b.id_kota=a.lokasi_asal
                            LEFT JOIN cabang c ON c.id_kota=b.id_kota OR b.hub=c.id_kota

                        LEFT JOIN rate_agent d ON d.id_cabang=CASE WHEN x_intercity(a.lokasi_asal)='Y' THEN '39' ELSE c.id_cabang END AND d.id_kota=a.lokasi_tujuan AND d.id_pelayanan=CASE WHEN a.service > 3 THEN 3 else a.service end

                            LEFT JOIN rate_agent_inbound e ON e.id_kota=a.lokasi_asal  and e.id_pelayanan=CASE WHEN a.service > 3 THEN 3 else a.service end and e.id_cabang=CASE WHEN x_intercity(a.lokasi_tujuan)='Y' THEN 0 else 99 end

                            LEFT JOIN rate_agent_cross f ON f.id_kota_asal=CASE WHEN x_intercity(a.lokasi_asal)!='Y' THEN a.lokasi_asal ELSE 0 END AND f.id_kota_tujuan=CASE WHEN x_intercity(a.lokasi_tujuan)!='Y' THEN a.lokasi_tujuan ELSE 0 END AND f.id_pelayanan=CASE WHEN a.service > 3 THEN 3 else a.service end
							WHERE (c.id_cabang=$agent_by AND a.tanggal BETWEEN '$r[dari]' AND '$r[sampai]' and service != 6 and a.status != 7 and paid_reject = '1') and ( pickup_by_agent = $agent_by or delivery_by_agent = $agent_by)


							");
                }
                else{
                    "SELECT
                                SUM(if(a.paid_reject = '1',if(if(berat_aktual is null or berat_aktual < 1,  berat_barang, berat_aktual) < ifnull(d.min_kg,ifnull(e.min_kg,f.min_kg)), (ifnull(d.min_kg,ifnull(e.min_kg,f.min_kg))*ifnull(d.price,ifnull(e.price,f.price)))+a.biaya_packing_agent+a.biaya_tambahan_agent , (if(berat_aktual is null or berat_aktual < 1,  berat_barang, berat_aktual)*ifnull(d.price,ifnull(e.price,f.price)))+a.biaya_packing_agent+a.biaya_tambahan_agent ),0)) AS total

                            FROM pengiriman a
                            LEFT JOIN kota b ON b.id_kota=a.lokasi_tujuan
                            OR b.id_kota=a.lokasi_asal
                            LEFT JOIN cabang c ON c.id_kota=b.id_kota OR b.hub=c.id_kota

                        LEFT JOIN rate_agent d ON d.id_cabang=CASE WHEN x_intercity(a.lokasi_asal)='Y' THEN '39' ELSE c.id_cabang END AND d.id_kota=a.lokasi_tujuan AND d.id_pelayanan=CASE WHEN a.service > 3 THEN 3 else a.service end

                            LEFT JOIN rate_agent_inbound e ON e.id_kota=a.lokasi_asal  and e.id_pelayanan=CASE WHEN a.service > 3 THEN 3 else a.service end and e.id_cabang=CASE WHEN x_intercity(a.lokasi_tujuan)='Y' THEN 0 else 99 end

                            LEFT JOIN rate_agent_cross f ON f.id_kota_asal=CASE WHEN x_intercity(a.lokasi_asal)!='Y' THEN a.lokasi_asal ELSE 0 END AND f.id_kota_tujuan=CASE WHEN x_intercity(a.lokasi_tujuan)!='Y' THEN a.lokasi_tujuan ELSE 0 END AND f.id_pelayanan=CASE WHEN a.service > 3 THEN 3 else a.service end

                            WHERE (c.id_cabang=$agent_by AND a.tanggal BETWEEN '$month-01' AND '$month-31' and service != 6 and a.status != 7 ) and ( pickup_by_agent = $agent_by or delivery_by_agent = $agent_by or pickup_by_agent is null or delivery_by_agent is null or pickup_by_agent != $agent_by or delivery_by_agent != $agent_by)
							";

                    $qtotal = $mysqli->query("SELECT
                                SUM(if(a.paid_reject = '1',if(if(berat_aktual is null or berat_aktual < 1,  berat_barang, berat_aktual) < ifnull(d.min_kg,ifnull(e.min_kg,f.min_kg)), (ifnull(d.min_kg,ifnull(e.min_kg,f.min_kg))*ifnull(d.price,ifnull(e.price,f.price)))+a.biaya_packing_agent+a.biaya_tambahan_agent , (if(berat_aktual is null or berat_aktual < 1,  berat_barang, berat_aktual)*ifnull(d.price,ifnull(e.price,f.price)))+a.biaya_packing_agent+a.biaya_tambahan_agent ),0)) AS total

                            FROM pengiriman a
                            LEFT JOIN kota b ON b.id_kota=a.lokasi_tujuan
                            OR b.id_kota=a.lokasi_asal
                            LEFT JOIN cabang c ON c.id_kota=b.id_kota OR b.hub=c.id_kota

                        LEFT JOIN rate_agent d ON d.id_cabang=CASE WHEN x_intercity(a.lokasi_asal)='Y' THEN '39' ELSE c.id_cabang END AND d.id_kota=a.lokasi_tujuan AND d.id_pelayanan=CASE WHEN a.service > 3 THEN 3 else a.service end

                            LEFT JOIN rate_agent_inbound e ON e.id_kota=a.lokasi_asal  and e.id_pelayanan=CASE WHEN a.service > 3 THEN 3 else a.service end and e.id_cabang=CASE WHEN x_intercity(a.lokasi_tujuan)='Y' THEN 0 else 99 end

                            LEFT JOIN rate_agent_cross f ON f.id_kota_asal=CASE WHEN x_intercity(a.lokasi_asal)!='Y' THEN a.lokasi_asal ELSE 0 END AND f.id_kota_tujuan=CASE WHEN x_intercity(a.lokasi_tujuan)!='Y' THEN a.lokasi_tujuan ELSE 0 END AND f.id_pelayanan=CASE WHEN a.service > 3 THEN 3 else a.service end

                            WHERE (c.id_cabang=$agent_by AND a.tanggal BETWEEN '$month-01' AND '$month-31' and service != 6 and a.status != 7 ) and ( pickup_by_agent = $agent_by or delivery_by_agent = $agent_by or pickup_by_agent is null or delivery_by_agent is null or pickup_by_agent != $agent_by or delivery_by_agent != $agent_by)


							");
                }
                $st = $qtotal->fetch_assoc();

                echo'
							<table class="table">
              					<tr>
                					<th>Total</th>
                					<td style="text-align:right;">Rp '.number_format($sum_total).'</td>
              					</tr>
            				</table>
          				</div>
        			</div>
				</div>

      			<div class="row no-print">
        			<div class="col-xs-12">
        			';
                $key		= "asdf$1011";
                $encrypt	= "$_GET[id]";
                $id 		= base64_decrypt($encrypt,$key);
                //echo $id;

                $pieces = explode('-', $id);
                $agent_by = $pieces['0'];
                $month = implode('-', array_slice($pieces, 1, 3));
                $query 		= $mysqli->query("select * from cabang where id_cabang=$agent_by

				");
                //echo "select * from cabang where id_cabang=$agent_by";
                $r= $query->fetch_assoc();


                $cek_status = $mysqli->query("SELECT
                               if(a.paid_reject = '1',if(if(berat_aktual is null or berat_aktual < 1,  berat_barang, berat_aktual) < ifnull(d.min_kg,ifnull(e.min_kg,f.min_kg)), (ifnull(d.min_kg,ifnull(e.min_kg,f.min_kg))*ifnull(d.price,ifnull(e.price,f.price)))+a.biaya_packing_agent+a.biaya_tambahan_agent , (if(berat_aktual is null or berat_aktual < 1,  berat_barang, berat_aktual)*ifnull(d.price,ifnull(e.price,f.price)))+a.biaya_packing_agent+a.biaya_tambahan_agent ),0) AS total

                            FROM pengiriman a
                            LEFT JOIN kota b ON b.id_kota=a.lokasi_tujuan
                            OR b.id_kota=a.lokasi_asal
                            LEFT JOIN cabang c ON c.id_kota=b.id_kota OR b.hub=c.id_kota

                        LEFT JOIN rate_agent d ON d.id_cabang=CASE WHEN x_intercity(a.lokasi_asal)='Y' THEN '39' ELSE c.id_cabang END AND d.id_kota=a.lokasi_tujuan AND d.id_pelayanan=CASE WHEN a.service > 3 THEN 3 else a.service end

                            LEFT JOIN rate_agent_inbound e ON e.id_kota=a.lokasi_asal  and e.id_pelayanan=CASE WHEN a.service > 3 THEN 3 else a.service end and e.id_cabang=CASE WHEN x_intercity(a.lokasi_tujuan)='Y' THEN 0 else 99 end

                            LEFT JOIN rate_agent_cross f ON f.id_kota_asal=CASE WHEN x_intercity(a.lokasi_asal)!='Y' THEN a.lokasi_asal ELSE 0 END AND f.id_kota_tujuan=CASE WHEN x_intercity(a.lokasi_tujuan)!='Y' THEN a.lokasi_tujuan ELSE 0 END AND f.id_pelayanan=CASE WHEN a.service > 3 THEN 3 else a.service end

                            WHERE (c.id_cabang=$agent_by AND a.tanggal BETWEEN '$month-01' AND '$month-31' and service != 6 and a.status != 7 ) and ( pickup_by_agent = $agent_by or delivery_by_agent = $agent_by or pickup_by_agent is null or delivery_by_agent is null or pickup_by_agent != $agent_by or delivery_by_agent != $agent_by)


							");

                "SELECT
                               id_pengiriman,status_inv_agent

                            FROM pengiriman a
                            LEFT JOIN kota b ON b.id_kota=a.lokasi_tujuan
                            OR b.id_kota=a.lokasi_asal
                            LEFT JOIN cabang c ON c.id_kota=b.id_kota OR b.hub=c.id_kota

                        LEFT JOIN rate_agent d ON d.id_cabang=CASE WHEN x_intercity(a.lokasi_asal)='Y' THEN '39' ELSE c.id_cabang END AND d.id_kota=a.lokasi_tujuan AND d.id_pelayanan=CASE WHEN a.service > 3 THEN 3 else a.service end

                            LEFT JOIN rate_agent_inbound e ON e.id_kota=a.lokasi_asal  and e.id_pelayanan=CASE WHEN a.service > 3 THEN 3 else a.service end and e.id_cabang=CASE WHEN x_intercity(a.lokasi_tujuan)='Y' THEN 0 else 99 end

                            LEFT JOIN rate_agent_cross f ON f.id_kota_asal=CASE WHEN x_intercity(a.lokasi_asal)!='Y' THEN a.lokasi_asal ELSE 0 END AND f.id_kota_tujuan=CASE WHEN x_intercity(a.lokasi_tujuan)!='Y' THEN a.lokasi_tujuan ELSE 0 END AND f.id_pelayanan=CASE WHEN a.service > 3 THEN 3 else a.service end

                            WHERE (c.id_cabang=$agent_by AND a.tanggal BETWEEN '$month-01' AND '$month-31' and service != 6 and a.status != 7 ) and ( pickup_by_agent = $agent_by or delivery_by_agent = $agent_by or pickup_by_agent is null or delivery_by_agent is null or pickup_by_agent != $agent_by or delivery_by_agent != $agent_by) and (status_inv_agent is null or status_inv_agent = '')

							";
                $cek_draft = $mysqli->query("SELECT
                               id_pengiriman,status_inv_agent

                            FROM pengiriman a
                            LEFT JOIN kota b ON b.id_kota=a.lokasi_tujuan
                            OR b.id_kota=a.lokasi_asal
                            LEFT JOIN cabang c ON c.id_kota=b.id_kota OR b.hub=c.id_kota

                        LEFT JOIN rate_agent d ON d.id_cabang=CASE WHEN x_intercity(a.lokasi_asal)='Y' THEN '39' ELSE c.id_cabang END AND d.id_kota=a.lokasi_tujuan AND d.id_pelayanan=CASE WHEN a.service > 3 THEN 3 else a.service end

                            LEFT JOIN rate_agent_inbound e ON e.id_kota=a.lokasi_asal  and e.id_pelayanan=CASE WHEN a.service > 3 THEN 3 else a.service end and e.id_cabang=CASE WHEN x_intercity(a.lokasi_tujuan)='Y' THEN 0 else 99 end

                            LEFT JOIN rate_agent_cross f ON f.id_kota_asal=CASE WHEN x_intercity(a.lokasi_asal)!='Y' THEN a.lokasi_asal ELSE 0 END AND f.id_kota_tujuan=CASE WHEN x_intercity(a.lokasi_tujuan)!='Y' THEN a.lokasi_tujuan ELSE 0 END AND f.id_pelayanan=CASE WHEN a.service > 3 THEN 3 else a.service end

                            WHERE (c.id_cabang=$agent_by AND a.tanggal BETWEEN '$month-01' AND '$month-31' and service != 6 and a.status != 7 ) and ( pickup_by_agent = $agent_by or delivery_by_agent = $agent_by or pickup_by_agent is null or delivery_by_agent is null or pickup_by_agent != $agent_by or delivery_by_agent != $agent_by) and (status_inv_agent is null or status_inv_agent = '')


							");

                $d = $cek_draft->fetch_assoc();
                $draft = $cek_draft->num_rows;
                //tombol print
                //<a target="_blank" href="reports/inv_agent.php?id_agent='.$agent_by.'&date='.$month.'"><button type="button" class="btn btn-primary pull-right" ><i class="fa fa-file-print-o"></i> Print</button></a>

                echo '


							<a target="_blank" href="reports/PHPExcel/excel_create_invagent.php?id_agent='.$agent_by.'&date='.$month.'"><button type="button" class="btn btn-primary pull-right" ><i class="fa fa-file-excel-o"></i> Excel</button></a>

							';




                echo '
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
			</section>



			';

			break;

		}
	}else{
		echo akses_salah();
	}
}
?>
