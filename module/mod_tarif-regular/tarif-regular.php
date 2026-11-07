<section class="content-header">
	<h1>Tarif Kiriman <small>Regular</small></h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-home"></i> Home</a></li>
		<li><a href="#">Finance</a></li>
		<li><a href="#">Tarif Kiriman</a></li>
		<li class="active">Regular</li>
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
	//cek hak akses user
	if($u['level']=='admin' || $u['level']=='finance')
	{
		$aksi	= "module/mod_tarif-regular/tarif-regular_action.php";	
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
						<p>Record has been saved</p>
					</div>';
				}
				elseif(isset($_SESSION['pesan']) && $_SESSION['pesan'] == 'failed')
				{
					echo'
					<div class="callout callout-warning pesan">
						<h4><i class="icon fa fa-warning"></i> Failed!</h4>
						<p>Record not successfully saved</p>
					</div>';
				}
				$_SESSION['pesan'] = '';
				echo'
				<div class="row">
					<div class="col-md-12">
						<div class="nav-tabs-custom">
							<ul class="nav nav-tabs">
								<li class="active"><a href="#tab_1" data-toggle="tab">Outbound</a></li>
								<li><a href="#tab_2" data-toggle="tab">Inbound</a></li>
							</ul>
							<div class="tab-content">
								<div class="tab-pane active" id="tab_1">
									<table id="TarifKirimanRegularOutbound" class="table table-bordered table-striped table-hover" width="100%" cellspacing="0" style="white-space:nowrap;">
										<thead>
											<tr>
												<th>Kota Tujuan</th>
												<th>Komoditi</th>
												<th>Harga</th>
												<th>Min. Kg</th>
												<th>Lead Time</th>
												<th width="50"><center>#</center></th>
											</tr>
										</thead>
										<tbody>
										</tbody>
									</table>
								</div>
								<div class="tab-pane" id="tab_2">
									<table id="TarifKirimanRegularInbound" class="table table-bordered table-striped table-hover" width="100%" cellspacing="0" style="white-space:nowrap;">
										<thead>
											<tr>
												<th>Kota Asal</th>
												<th>Komoditi</th>
												<th>Harga</th>
												<th>Min. Kg</th>
												<th>Lead Time</th>
												<th width="50"><center>#</center></th>
											</tr>
										</thead>
										<tbody>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
			</section>';
			
			break;
			
			case "update":
			
			$key		= "asdf$1011";
			$encrypt	= "$_GET[id]";
			$id 		= base64_decrypt($encrypt,$key);

			$query 		= $mysqli->query("select * from rate where rate_id='$id'");
			$r 			= $query->fetch_assoc();
									
			echo'
			<section class="content">
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">Update</h3>
						<div class="box-tools pull-right">
							<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
							<button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
						</div>
					</div>
					<form class="form-horizontal" method="post" action="'.$aksi.'?module=tarif-regular&act=update" autocomplete="off">
					<input type="hidden" name="rate_id" value="'.$r['rate_id'].'">
					<div class="box-body">
						<div class="form-group">
							<label class="col-md-3 control-label">Kota Asal <span class="required">* </span></label>
							<div class="col-md-6">
								<select name="id_cabang" data-placeholder="Choose a Branch/Agent..." class="form-control select2">
									<option value="">Select...</option>';
									$cabang = $mysqli->query("select id_cabang,nama_cabang from cabang order by nama_cabang");
									while($c=$cabang->fetch_assoc()){
										if($r['id_cabang']==$c['id_cabang']){
											echo'
											<option value="'.$c['id_cabang'].'" selected>'.$c['nama_cabang'].'</option>';
										}else{
											echo'
											<option value="'.$c['id_cabang'].'">'.$c['nama_cabang'].'</option>';
										}
									}
									echo'
								</select>
								<div class="val-message"></div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label">Kota Tujuan <span class="required">* </span></label>
							<div class="col-md-6">
								<select name="id_kota" data-placeholder="Choose..." class="form-control select2">
									<option value="">Select...</option>';
									$kota = $mysqli->query("select id_kota,nama_kota from kota order by nama_kota");
									while($k=$kota->fetch_assoc()){
										if($r['id_kota']==$k['id_kota']){
											echo'
											<option value="'.$k['id_kota'].'" selected>'.strtoupper($k['nama_kota']).'</option>';
										}else{
											echo'
											<option value="'.$k['id_kota'].'">'.strtoupper($k['nama_kota']).'</option>';
										}
									}
									echo'
								</select>
								<div class="val-message"></div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label">Komoditi <span class="required">* </span></label>
							<div class="col-md-6">
								<select name="komoditi" data-placeholder="Choose..." class="form-control select2">
									<option value="1" ';if($r['komoditi']=='1'){echo'selected';}echo'>Paket</option>
									<option value="2" ';if($r['komoditi']=='2'){echo'selected';}echo'>Dokumen</option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label">Harga</label>
							<div class="col-md-6">
								<input type="text" name="harga_awal" class="form-control" value="'.$r['harga_awal'].'">
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label">Min. Kg</label>
							<div class="col-md-6">
								<input type="text" name="berat_minimal" class="form-control" value="'.$r['berat_minimal'].'">
							</div>
						</div>
					</div>
					<div class="box-footer">
						<button type="button" class="btn btn-default" onclick="window.location.href = \'?module=tarif-regular\';">Cancel</button>
						<button type="submit" class="btn btn-primary pull-right"><i class="fa fa-save"></i> Save</button>
					</div>
					</form>
				</div>
			</section>';
			
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