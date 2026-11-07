<section class="content-header">
	<h1>Kota<small></small></h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-home"></i> Home</a></li>
		<li><a href="#">Master Data</a></li>
		<li class="active">Kota</li>
	</ol>
</section>
<?php
if(!isset($_SESSION)){
    session_start();
}

if (empty($_SESSION['username']) AND empty($_SESSION['password'])){
	header('location:../../application/login.php');
}
else{
	//cek hak akses user
	if($u['level']=='admin'){
		$aksi	= "module/mod_destination/destination_action.php";
		
		$act = !empty($_GET['act']) ? $_GET['act'] : '';
		
		switch($act){
			
			default:
			echo'
			<section class="content">
				<div class="row">
					<div class="col-xs-12">
						<div class="box box-primary">
							<div class="box-header">
								<div class="row">
									<div class="col-md-6">
										<div class="btn-group">
											<button class="btn btn-sm btn-primary" onclick="window.location.href = \'?module=destination&act=create\';">Add New <i class="fa fa-plus"></i></button>
										</div>
									</div>
									<div class="col-md-6">
										<div class="btn-group pull-right">
											<button class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown">Tools <i class="fa fa-angle-down"></i></button>
											<ul class="dropdown-menu pull-right">
												<li><a href="javascript:;">Print </a></li>
												<li><a href="javascript:;">Save as PDF </a></li>
												<li><a href="javascript:;">Export to Excel </a></li>
											</ul>
										</div>
									</div>
								</div>
							</div>
							<div class="box-body">
								<table id="kota" class="table table-bordered table-striped table-hover">
									<thead>
										<tr>
											<th>Kode</th>
											<th>Kota</th>
											<th>Induk</th>
											<th>Pusat</th>
											<th width="50px">Action</th>
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
					</div><!-- /.box-header -->
					<form class="form-horizontal" id="form-kota" method="post" action="'.$aksi.'?module=destination&act=create" autocomplete="off">
					<div class="box-body">
						<div class="form-group">
							<label class="col-md-3 control-label">Kode</label>
							<div class="col-md-6">
								<input type="text" name="kode_kota" placeholder="Kode..." class="form-control">
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label">Nama <span class="required">* </span></label>
							<div class="col-md-6">
								<input type="text" name="nama_kota" placeholder="Nama..." class="form-control">
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label">Induk </label>
							<div class="col-md-6">
								<select name="induk" class="form-control select2">
									<option value="">Select...</option>';
									$induk = mysqli_query($con, "select id_kota,nama_kota,kode_kota from kota");
									while($i=mysqli_fetch_assoc($induk)){
										if(!empty($i['kode_kota'])){
											echo'
											<option value="'.$i['id_kota'].'">'.$i['nama_kota'].' ('.$i['kode_kota'].')</option>';
										}else{
											echo'
											<option value="'.$i['id_kota'].'">'.$i['nama_kota'].'</option>';
										}
									}
									echo'
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label">Pusat </label>
							<div class="col-md-6">
								<select name="hub" class="form-control select2">
									<option value="">Select...</option>';
									$pusat = mysqli_query($con, "select id_kota,nama_kota,kode_kota from kota");
									while($p=mysqli_fetch_assoc($pusat)){
										if(!empty($p['kode_kota'])){
											echo'
											<option value="'.$p['id_kota'].'">'.$p['nama_kota'].' ('.$p['kode_kota'].')</option>';
										}else{
											echo'
											<option value="'.$p['id_kota'].'">'.$p['nama_kota'].'</option>';
										}
									}
									echo'
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label"></label>
							<div class="col-md-6">
								<label><input type="checkbox" class="minimal" name="intercity" value="Y"> Intercity</label>
							</div>
						</div>

					</div><!-- /.box-body -->
					<div class="box-footer">
						<button type="button" class="btn btn-default" onclick="window.location.href = \'?module=customer\';">Cancel</button>
						<button type="submit" class="btn btn-primary pull-right">Save</button>
					</div><!-- /.box-footer -->
					</form>
				</div><!-- /.box -->
			</section>';
			
			break;
			
			case "update":
			
			$key = "asdf$1011";
			$encrypt = "$_GET[id]";
			$id = base64_decrypt($encrypt,$key);

			$query = mysqli_query($con, "select * from kota where id_kota='$id'");
			$r = mysqli_fetch_assoc($query);
									
			echo'
			<section class="content">
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">Update</h3>
						<div class="box-tools pull-right">
							<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
							<button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
						</div>
					</div><!-- /.box-header -->
					<form class="form-horizontal" id="form-kota" method="post" action="'.$aksi.'?module=destination&act=update" autocomplete="off">
					<input type="hidden" name="id" value="'.$r['id_kota'].'">
					<div class="box-body">
						<div class="form-group">
							<label class="col-md-3 control-label">Kode</label>
							<div class="col-md-6">
								<input type="text" name="kode_kota" placeholder="Kode..." class="form-control" value="'.$r['kode_kota'].'">
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label">Nama <span class="required">* </span></label>
							<div class="col-md-6">
								<input type="text" name="nama_kota" placeholder="Nama..." class="form-control" value="'.$r['nama_kota'].'">
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label">Induk </label>
							<div class="col-md-6">
								<select name="induk" class="form-control select2">
									<option value="">Select...</option>';
									$induk = mysqli_query($con, "select id_kota,nama_kota,kode_kota from kota");
									while($i=mysqli_fetch_assoc($induk)){
										if($r['induk']==$i['id_kota']){
											if(!empty($i['kode_kota'])){
												echo'
												<option value="'.$i['id_kota'].'" selected>'.$i['nama_kota'].' ('.$i['kode_kota'].')</option>';
											}else{
												echo'
												<option value="'.$i['id_kota'].'" selected>'.$i['nama_kota'].'</option>';
											}
										}else{
											if(!empty($i['kode_kota'])){
												echo'
												<option value="'.$i['id_kota'].'">'.$i['nama_kota'].' ('.$i['kode_kota'].')</option>';
											}else{
												echo'
												<option value="'.$i['id_kota'].'">'.$i['nama_kota'].'</option>';
											}
										}
									}
									echo'
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label">Pusat </label>
							<div class="col-md-6">
								<select name="hub" class="form-control select2">
									<option value="">Select...</option>';
									$pusat = mysqli_query($con, "select id_kota,nama_kota,kode_kota from kota");
									while($p=mysqli_fetch_assoc($pusat)){
										if($r['hub']==$p['id_kota']){
											if(!empty($p['kode_kota'])){
												echo'
												<option value="'.$p['id_kota'].'" selected>'.$p['nama_kota'].' ('.$p['kode_kota'].')</option>';
											}else{
												echo'
												<option value="'.$p['id_kota'].'" selected>'.$p['nama_kota'].'</option>';
											}
										}else{
											if(!empty($p['kode_kota'])){
												echo'
												<option value="'.$p['id_kota'].'">'.$p['nama_kota'].' ('.$p['kode_kota'].')</option>';
											}else{
												echo'
												<option value="'.$p['id_kota'].'">'.$p['nama_kota'].'</option>';
											}
										}
									}
									echo'
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label"></label>
							<div class="col-md-6">';
							if($r['intercity']=='Y'){
								echo'
								<label><input type="checkbox" class="minimal" name="intercity" value="Y" checked> Intercity</label>';
							}else{
								echo'
								<label><input type="checkbox" class="minimal" name="intercity" value="Y"> Intercity</label>';
							}
							echo'	
							</div>
						</div>

					</div><!-- /.box-body -->
					<div class="box-footer">
						<button type="button" class="btn btn-default" onclick="window.location.href = \'?module=destination\';">Cancel</button>
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