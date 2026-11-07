<!-- Content Header (Page header) -->
<section class="content-header">
	<h1>User Profile</h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-home"></i> Home</a></li>
		<li class="active">User profile</li>
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
	$aksi	= "module/mod_profile/profile_action.php";
?>


<!-- Main content -->
<section class="content">

	<div class="row">
		<div class="col-md-3">
		
			<!-- Profile Image -->
			<div class="box box-primary">
				<div class="box-body box-profile">
				<?php

				$cabang = mysqli_query($con, "select * from cabang where id_cabang=$u[id_cabang]");

				$c = mysqli_fetch_assoc($cabang);


				if(!empty($u['foto'])){
					echo'<img class="profile-user-img img-responsive img-circle" src="dist/img/'.$u['foto'].'" alt="'.$u['namalengkap'].'">';
				}else{
					echo'<img class="profile-user-img img-responsive img-circle" src="dist/img/avatar.png" alt="'.$u['namalengkap'].'">';
				}
				?>
				<h3 class="profile-username text-center"><?php echo''.$u['namalengkap'].'';?></h3>
				<p class="text-muted text-center"></p>
				<ul class="list-group list-group-unbordered">
					<li class="list-group-item">
						<b>Cabang</b> <a class="pull-right"><?php echo''.strtoupper($c['nama_cabang'].', '.$c['alamat1']);?></a>
					</li>
					<li class="list-group-item">
						<b>Level</b> <a class="pull-right"><?php echo''.strtoupper($u['level']).'';?></a>
					</li>
					<li class="list-group-item">
						<b>Aktif Sejak</b> <a class="pull-right"><?php echo $u['tanggaldibuat']; ?></a>
					</li>
				</ul>
				</div>
				<!-- /.box-body -->
			</div>
			<!-- /.box -->

			<!-- About Me Box -->
			
			<!-- /.box -->
        </div>
        <!-- /.col -->
        <div class="col-md-9">
			<div class="nav-tabs-custom">
				<ul class="nav nav-tabs">
					<li class="active"><a href="#settings" data-toggle="tab">Settings</a></li>
				</ul>
				<div class="tab-content">
				
				<!-- /.tab-pane -->
				
				<div class="active tab-pane" id="settings">
					<form method="post" action="<?php echo''.$aksi.'?module=profile&act=update';?>" class="form-horizontal" autocomplete="off" enctype="multipart/form-data">
						<div class="form-group">
							<label for="inputName" class="col-sm-2 control-label">Username</label>
							<div class="col-sm-10">
								<input type="hidden" class="form-control" value="<?php echo''.$u['id_user'].'';?>" name="id">
								<input type="email" class="form-control" id="inputName" value="<?php echo''.$u['username'].'';?>" disabled>
							</div>
						</div>
						<div class="form-group">
							<label for="inputName" class="col-sm-2 control-label">Fullname</label>
							<div class="col-sm-10">
								<input type="text" class="form-control" id="inputName" value="<?php echo''.$u['namalengkap'].'';?>" name="namalengkap">
							</div>
						</div>
						<div class="form-group">
							<label for="inputEmail" class="col-sm-2 control-label">Email</label>
							<div class="col-sm-10">
								<input type="email" class="form-control" id="inputEmail" value="<?php echo''.$u['email'].'';?>" name="email">
							</div>
						</div>
						<div class="form-group">
							<label for="inputName" class="col-sm-2 control-label">Password</label>
							<div class="col-sm-10">
								<input type="password" class="form-control" id="inputName" name="password" required>
							</div>
						</div>
						<div class="form-group">
							<label for="inputName" class="col-sm-2 control-label">Photo</label>
							<div class="col-sm-10">
								<input type="file" id="exampleInputFile" name="fupload">
								<p class="help-block">Image type .jpg or .png</p>
							</div>
						</div>
						<div class="form-group">
							<div class="col-sm-offset-2 col-sm-10">
								<button type="submit" class="btn btn-danger">Submit</button>
							</div>
						</div>
					</form>
				</div>
				<!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
          </div>
          <!-- /.nav-tabs-custom -->
        </div>
        <!-- /.col -->
	</div>
	<!-- /.row -->
	
</section>
<!-- /.content -->
<?php
}
?>