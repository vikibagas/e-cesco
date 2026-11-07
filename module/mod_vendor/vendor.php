<section class="content-header">
	<h1>Vendor<small></small></h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-home"></i> Home</a></li>
		<li><a href="#">Master Data</a></li>
		<li class="active">Vendor</li>
	</ol>
</section>
<?php
if(!isset($_SESSION)){
    session_start();
}

if (empty($_SESSION['username']) AND empty($_SESSION['password'])){
	header('location:../application/login.php');
}
else{
	//cek hak akses user
	if($u['level']=='admin' || $u['level']=='domex' || $u['level']=='operational' || $u['level']=='admin_network' || $u['level']=='finance' || $u['level']=='inbound'){

		$aksi	= "module/mod_vendor/vendor_action.php";
		
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
											<button class="btn btn-sm btn-primary" onclick="window.location.href = \'?module=vendor&act=create\';">Add New <i class="fa fa-plus"></i></button>
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
								<table class="table table-striped table-bordered table-hover" id="vendor">
									<thead>
										<tr>
											<th>vendor</th>
											<th>Aktif Y/N</th>
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
					<form name="demo" method="post" action="'.$aksi.'?module=vendor&act=create" class="form-horizontal form-bordered" id="form-vendor" autocomplete="off">
					<div class="box-body">
						
						<div class="form-group">
							<label class="col-md-3 control-label">Nama <span class="required">* </span></label>
							<div class="col-md-6">
								<input type="text" name="nama" placeholder="Nama..." class="form-control">
							</div>
						</div>
						
					</div><!-- /.box-body -->
					<div class="box-footer">
						<button type="button" class="btn btn-default" onclick="window.location.href = \'?module=vendor\';">Cancel</button>
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

			$query = mysqli_query($con, "select * from manifest_vendor where id='$id'");
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
					<form name="demo" method="post" action="'.$aksi.'?module=vendor&act=update" class="form-horizontal form-bordered" id="form-vendor" autocomplete="off">
					<input type="hidden" name="id" value="'.$r['id'].'">
					<div class="box-body">
						
						<div class="form-group">
							<label class="col-md-3 control-label">Nama <span class="required">* </span></label>
							<div class="col-md-6">
								<input type="text" name="nama_cabang" placeholder="Nama..." class="form-control" value="'.$r['name'].'" readonly>
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-md-3 control-label">Aktif </label>
							<div class="col-md-6">
								<label class="radio-inline">
							      <input type="radio" name="active" '.($r['active'] == "Y" ? "checked" : "").' value="Y"> Aktif
							    </label>
							    <label class="radio-inline">
							      <input type="radio" name="active" value="N" '.($r['active'] == "N" ? "checked" : "").' > Non Aktif
							    </label>							    
							</div>
						</div>
					</div><!-- /.box-body -->
					<div class="box-footer">
						<button type="button" class="btn btn-default" onclick="window.location.href = \'?module=vendor\';">Cancel</button>
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