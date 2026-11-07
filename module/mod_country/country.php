<section class="content-header">
	<h1>Negara<small></small></h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-home"></i> Home</a></li>
		<li><a href="#">Master Data</a></li>
		<li class="active">Negara</li>
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
	if($u['level']=='admin' || $u['level']=='domex'){
		$aksi	= "module/mod_country/country_action.php";
		
		$act = !empty($_GET['act']) ? $_GET['act'] : '';
		
		switch($act){
			
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
				elseif(isset($_SESSION['pesan']) && $_SESSION['pesan'] == 'delete')
				{
					echo'
					<div class="callout callout-success pesan">
						<h4><i class="icon fa fa-check"></i> Success!</h4>
						<p>Record has been deleted</p>
					</div>';
				}
				$_SESSION['pesan'] = '';
				echo'
				<div class="row">
					<div class="col-xs-12">
						<div class="box box-primary">
							<div class="box-header">
								<div class="row">
									<div class="col-md-6">
										<div class="btn-group">
											<button class="btn btn-sm btn-primary" onclick="window.location.href = \'?module=country&act=create\';">Add New <i class="fa fa-plus"></i></button>
										</div>
									</div>
									<div class="col-md-6">
									</div>
								</div>
							</div>
							<div class="box-body">
								<table id="negara" class="table table-bordered table-striped table-hover">
									<thead>
										<tr>
											<th>Nama Negara</th>
											<th width="50px">Kode</th>
											<th width="50px">Zona</th>
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
					</div>
					<form class="form-horizontal" id="form-negara" method="post" action="'.$aksi.'?module=country&act=create" autocomplete="off">
					<div class="box-body">
						<div class="form-group">
							<label class="col-md-3 control-label">Kode</label>
							<div class="col-md-6">
								<input type="text" name="kode_negara" placeholder="Kode..." class="form-control">
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label">Nama <span class="required">* </span></label>
							<div class="col-md-6">
								<input type="text" name="nama_negara" placeholder="Nama..." class="form-control">
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label">Zona <span class="required">* </span></label>
							<div class="col-md-6">
								<select name="zona" class="form-control select2">
									<option value="">Select...</option>';
									for ($i=1; $i<=9; $i++){
										echo '<option value="'.$i.'">'.$i.'</option>';
									 }
									echo'
								</select>
							</div>
						</div>
					</div>
					<div class="box-footer">
						<button type="button" class="btn btn-default" onclick="window.location.href = \'?module=country\';">Cancel</button>
						<button type="submit" class="btn btn-primary pull-right">Save</button>
					</div>
					</form>
				</div>
			</section>';
			
			break;
			
			case "update":
			
			$key		= "asdf$1011";
			$encrypt	= "$_GET[id]";
			$id			= base64_decrypt($encrypt,$key);

			$query		= $mysqli->query("SELECT * FROM negara WHERE id_negara='$id'");
			$r			= $query->fetch_assoc();
									
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
					<form class="form-horizontal" id="form-negara" method="post" action="'.$aksi.'?module=country&act=update" autocomplete="off">
					<input type="hidden" name="id_negara" value="'.$r['id_negara'].'">
					<div class="box-body">
						<div class="form-group">
							<label class="col-md-3 control-label">Kode</label>
							<div class="col-md-6">
								<input type="text" name="kode_negara" placeholder="Kode..." class="form-control" value="'.$r['kode_negara'].'">
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label">Nama <span class="required">* </span></label>
							<div class="col-md-6">
								<input type="text" name="nama_negara" placeholder="Nama..." class="form-control" value="'.$r['nama_negara'].'">
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label">Zona <span class="required">* </span></label>
							<div class="col-md-6">
								<select name="zona" class="form-control select2">
									<option value="">Select...</option>';
									for ($i=1; $i<=9; $i++)
									{
										if ($i==$r['zona'])
											echo '<option value="'.$i.'" selected>'.$i.'</option>';
										else
											echo '<option value="'.$i.'">'.$i.'</option>';
									 }
									echo'
								</select>
							</div>
						</div>
					</div>
					<div class="box-footer">
						<button type="button" class="btn btn-default" onclick="window.location.href = \'?module=country\';">Cancel</button>
						<button type="submit" class="btn btn-primary pull-right">Save</button>
					</div>
					</form>
				</div>
			</section>';
			
			break;
			
		}
	}else{
		echo akses_salah();
	}
}
?>