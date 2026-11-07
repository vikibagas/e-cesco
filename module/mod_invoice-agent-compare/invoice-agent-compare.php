<section class="content-header">
	<h1>Invoice Agent <small>Compare</small></h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-home"></i> Home</a></li>
		<li><a href="#">Finance</a></li>
		<li><a href="#">Invoice</a></li>
		<li class="active">Invoice Agent Compare</li>
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
		$aksi	= "module/mod_invoice-agent-compare/invoice-agent-compare_action.php";
		$act	= !empty($_GET['act']) ? $_GET['act'] : '';
		
		switch($act)
		{
			
			default:
			echo'
			<style>
			.content {
				min-height: 50px !important;
			}
			</style>
			<section class="content">
				<div class="box box-primary">
					<div class="box-header with-border">
						<div class="box-tools pull-right">
							<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
							<button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
						</div>
					</div>
					<form id="frmInvAgentCompare" class="form-horizontal" method="post" action="'.$aksi.'?module=invoice-agent-compare&act=compare" autocomplete="off" enctype="multipart/form-data">
					<div class="box-body">
					    <div class="form-group">
                            <label class="col-md-3 control-label">Agent</label>

                            <div class="col-md-3">
                            <div class="input-group">
                                    <select " class="form-control" name="agent" >
                                        ';
                                            $select_agent = mysqli_query($con,"select id_cabang,nama_cabang from cabang order by nama_cabang");
                                            while($c=mysqli_fetch_assoc($select_agent)){
                                                echo '<option value="'.$c['id_cabang'].'"> '.$c['nama_cabang'].'</option>';
                                            }
                                        echo '
                                    </select>
                             </div>
                            </div>
                        </div>
						<div class="form-group">
							<label class="col-md-3 control-label">Periode* </span></label>
							<div class="col-md-3">
								<div class="input-group">
									<div class="input-group-addon">
										<i class="fa fa-calendar"></i>
									</div>
									<input type="text" required class="form-control date-picker" placeholder="yyyy-mm" id="tanggal-compare" value="'.(isset($_POST['tanggal']) ? $_POST['tanggal'] : '').'" name="tanggal" data-date-format="mm-yyyy">
								</div>	
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label">Excel File (*.xlsx) <span class="required">* </span></label>
							<div class="col-md-3">
								<div class="input-group">
									<input type="file" required placeholder="Dokumen" class="form-control" id="xlsFile" name="xlsFile">
									<a href="upload/compare/format-compare-invoice-agent.xlsx" target="_blank"> Contoh Format Excel</a>
								</div>
							</div>
						</div>													
					</div>
					
					<div class="box-footer">
					    <div class="row">
							<label class="col-md-3 control-label"></label>
							<div class="col-md-3">
								<button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Compare</button>
							</div>
						</div>	
						
						
					</div>
					</form>
				</div>
			</section>';
			
			echo '<section class="content">
			<div class="box box-primary">
				<div class="box-body">
								<table id="tblCompare" class="table table-bordered table-striped table-hover" width="100%" cellspacing="0" style="white-space:nowrap;">
									<thead>
										<tr>
											<th>AWB</th>
											<th>Service</th>
											<th>Kota Asal</th>											
											<th>Kota Tujuan</th>
											<th>Berat</th>
											<th>Harga</th>
											<th>Total</th>
											<th>Harga Ecesco</th>
											<th>Status Compare</th>
										</tr>
									</thead>
									<tbody>
									</tbody>
								</table>
							</div>
							</div>
							</section>
			';
			
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
			
			break;		
			
			
		}
	}else{
		echo akses_salah();
	}
}
?>