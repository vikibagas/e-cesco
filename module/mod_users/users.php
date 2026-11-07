<section class="content-header">
	<h1>Users<small></small></h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-home"></i> Home</a></li>
		<li><a href="#">Master Data</a></li>
		<li class="active">Users</li>
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
		
		$aksi	= "module/mod_users/users_action.php";
		
		$act = !empty($_GET['act']) ? $_GET['act'] : '';
		
		switch($act){
			
			default:
			echo'
			<section class="content">
				<div class="row">
					<div class="col-md-12">
						<!-- Custom Tabs -->
						<div class="nav-tabs-custom">
							<ul class="nav nav-tabs">
								<li class="active"><a href="#tab_1" data-toggle="tab">User Cesco</a></li>
								<li><a href="#tab_2" data-toggle="tab">User Customer</a></li>
							</ul>
							<div class="tab-content">
								<div class="tab-pane active" id="tab_1">
									<div class="box-header">
										<div class="row">
											<div class="col-md-12">
												<div class="btn-group">
													<button class="btn btn-sm btn-primary" onclick="window.location.href = \'?module=users&act=create_usercesco\';">Add New <i class="fa fa-plus"></i></button>
												</div>
											</div>										
										</div>
									</div>
									<table id="user" class="table table-bordered table-striped table-hover">
										<thead>
											<tr>
												<th>Name</th>
												<th>Username</th>
												<th>Level</th>
												<th>Jabatan</th>
												<th>Blocked</th>
												<th>Email</th>
												<th>Agent/Branch</th>
												<th width="50px">Action</th>
											</tr>
										</thead>
										<tbody>
										</tbody>
									</table>
								</div>
								<div class="tab-pane" id="tab_2"> 
									<div class="box-header">
										<div class="row">
											<div class="col-md-12">
												<div class="btn-group">
													<button class="btn btn-sm btn-primary" onclick="window.location.href = \'?module=users&act=create_customer\';">Add New <i class="fa fa-plus"></i></button>
												</div>
											</div>										
										</div>
									</div>
									<table id="user_client" class="table table-bordered table-striped table-hover" width="100%" cellspacing="0" style="white-space:nowrap;">
										<thead>
											<tr>
												<th>Name</th>
												<th>Username</th>
												<th>Company</th>
												<th>Blocked</th>
												<th>Email</th>
												<th>Member Since</th>
												<th width="50px">Action</th>
											</tr>
										</thead>
										<tbody>
										</tbody>
									</table>
								</div><!-- /.tab-pane -->
							</div><!-- /.tab-content -->
						</div><!-- nav-tabs-custom -->
					</div>
				</div>
			</section>
			
			<div class="modal fade modal-danger" id="confirm-blocked" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							Confirmation ...
						</div>
						<div class="modal-body">
							Blocked this user ...?
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
							<a class="btn btn-warning btn-ok">Ok</a>
						</div>
					</div>
				</div>
			</div>';
			
			break;
			
			case "update1":
			
			$key = "asdf$1011";
			$encrypt = "$_GET[id]";
			$id = base64_decrypt($encrypt,$key);

			$query = mysqli_query($con, "select * from user where id_user='$id'");
			$r = mysqli_fetch_assoc($query);
									
			echo'
			<section class="content">
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">Detail User</h3>
						<div class="box-tools pull-right">
							<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
							<button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
						</div>
					</div><!-- /.box-header -->
					<form class="form-horizontal" id="form-user" method="post" action="'.$aksi.'?module=users&act=update1" autocomplete="off">
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
								<input type="text" name="namalengkap" placeholder="Name..." class="form-control" value="'.$r['namalengkap'].'">
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label">Email <span class="required">* </span></label>
							<div class="col-md-6">
								<input type="text" name="email" placeholder="Email..." class="form-control" value="'.$r['email'].'">
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label">Branch/Agent <span class="required">* </span></label>
							<div class="col-md-6">
								<select name="id_cabang" data-placeholder="Choose a Branch/Agent..." class="form-control select2">
									<option value="">Select...</option>';
									$cabang = mysqli_query($con, "select id_cabang,nama_cabang from cabang order by nama_cabang");
									while($c=mysqli_fetch_assoc($cabang)){
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
							<label class="col-md-3 control-label">Jabatan <span class="required">* </span></label>
							<div class="col-md-6">
								<select name="jabatan" data-placeholder="Pilih Jabatan..." class="form-control select2">
									<option value="">Select...</option>';
									$jabatan = mysqli_query($con, "select id,jabatan, nama_level  from jabatan order by jabatan");
									while($j=mysqli_fetch_assoc($jabatan)){
										if($r['id_jabatan']==$j['id']){
											echo'
											<option value="'.$j['id'].'" selected>'.$j['jabatan'].' ( '.$j['nama_level'].' )</option>';
										}else{
											echo'
											<option value="'.$j['id'].'">'.$j['jabatan'].' ( '.$j['nama_level'].' )</option>';
										}
									}
									echo'
								</select>
								<div class="val-message"></div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label">Level <span class="required">* </span></label>
							<div class="col-md-6">
								<select name="level" data-placeholder="Choose a Level..." class="form-control select2">
									<option value="">Select...</option>';
									if($r['level']=='admin'){
										echo'<option value="admin" selected>Admin</option>';
									}else{
										echo'<option value="admin">Admin</option>';
									}
									if($r['level']=='operator'){
										echo'<option value="operator" selected>Operator</option>';
									}else{
										echo'<option value="operator">Operator</option>';
									}
									if($r['level']=='finance'){
										echo'<option value="finance" selected>Finance</option>';
									}else{
										echo'<option value="finance">Finance</option>';
									}
									if($r['level']=='domex'){
										echo'<option value="domex" selected>Domex</option>';
									}else{
										echo'<option value="domex">Domex</option>';
									}
									if($r['level']=='network'){
										echo'<option value="network" selected>Network</option>';
									}else{
										echo'<option value="network">Network</option>';
									}
									if($r['level']=='kurir'){
										echo'<option value="kurir" selected>Kurir</option>';
									}else{
										echo'<option value="kurir">Kurir</option>';
									}
									if($r['level']=='driver'){
										echo'<option value="driver" selected>Driver</option>';
									}else{
										echo'<option value="driver">Driver</option>';
									}
									if($r['level']=='agent'){
										echo'<option value="agent" selected>Agent</option>';
									}else{
										echo'<option value="agent">Agent</option>';
									}
									if($r['level']=='kurir_agent'){
										echo'<option value="kurir_agent" selected>Kurir Agent</option>';
									}else{
										echo'<option value="kurir_agent">Kurir Agent</option>';
									}
									if($r['level']=='intercity'){
										echo'<option value="intercity" selected>Intercity</option>';
									}else{
										echo'<option value="intercity">Intercity</option>';
									}
									if($r['level']=='admin_network'){
										echo'<option value="admin_network" selected>Admin Network</option>';
									}else{
										echo'<option value="admin_network">Admin Network</option>';
									}
									if($r['level']=='inbound'){
										echo'<option value="inbound" selected>inbound</option>';
									}else{
										echo'<option value="inbound">inbound</option>';
									}
									
									echo'
								</select>
								<div class="val-message"></div>
							</div>
						</div>';
						if($r['blokir']=='Y'){
							echo'
							<div class="form-group">
								<label class="col-md-3 control-label"></label>
								<div class="col-md-6">
									<label><input type="checkbox" class="minimal" name="blokir" value="N"> Unblocked</label>
								</div>
							</div>';							
						}
						if($r['level']=='kurir' || $r['level']=='driver' || $r['level']=='network'){
							echo'
							<hr>';
							if($r['level']=='kurir' || $r['level']=='driver'){
								echo'
								<div class="form-group">
									<label class="col-md-3 control-label">Pickup Customer </label>
									<div class="col-md-6">
										<select name="id_kustomer" data-placeholder="Choose a Customer..." class="form-control select2">
											<option value="">Select...</option>';
											$customerpickup = mysqli_query($con, "select id_kustomer, nama_kustomer from kustomer");
											while($cp=mysqli_fetch_assoc($customerpickup)){
												if($r['id_kustomer']==$cp['id_kustomer']){
													echo'
													<option value="'.$cp['id_kustomer'].'" selected>'.$cp['nama_kustomer'].'</option>';
												}else{
													echo'
													<option value="'.$cp['id_kustomer'].'">'.$cp['nama_kustomer'].'</option>';
												}
											}
											echo'
										</select>
										<div class="val-message"></div>
									</div>
								</div>';
							}
							echo'
							<div class="form-group">
								<label class="col-md-3 control-label">Pickup Area </label>
								<div class="col-md-6">
									<select class="form-control select2" name="area[]" multiple="multiple" data-placeholder="Select pickup area" style="width: 100%;">';
										if($r['level']=='kurir' || $r['level']=='driver'){
											$kotaasal = mysqli_query($con, "select a.id_kota, case when b.nama_kota is null then a.nama_kota when x_namakota(b.induk) is null then concat(a.nama_kota,', ',b.nama_kota) else concat(a.nama_kota,', ',b.nama_kota,', ',x_namakota(b.induk)) end as nama_kota from kota a left join kota b on b.id_kota=a.induk where a.kota_asal='Y' and a.intercity='Y' order by a.id_kota");
										}else{
											$kotaasal = mysqli_query($con, "select a.id_kota, case when b.nama_kota is null then a.nama_kota when x_namakota(b.induk) is null then concat(a.nama_kota,', ',b.nama_kota) else concat(a.nama_kota,', ',b.nama_kota,', ',x_namakota(b.induk)) end as nama_kota from kota a left join kota b on b.id_kota=a.induk where a.kota_asal='Y' and a.intercity!='Y' order by a.id_kota");
										}
										$_arrNilai = explode(',', $r['area']);
										while($t=mysqli_fetch_assoc($kotaasal)){
											$_ck = (array_search($t['id_kota'], $_arrNilai) === false)? '' : 'selected';
											echo '<option value="'.$t['id_kota'].'" '.$_ck.'>'.$t['nama_kota'].' </option>';
										}
										echo'
									</select>
									<div class="val-message"></div>
								</div>
							</div>
							<hr>';
							if($r['level']=='kurir' || $r['level']=='driver'){
								echo'
								<div class="form-group">
									<label class="col-md-3 control-label">Delivery Customer </label>
									<div class="col-md-6">
										<select name="delivery_customer" data-placeholder="Choose a Customer..." class="form-control select2">
											<option value="">Select...</option>';
											$customerdelivery = mysqli_query($con, "select id_kustomer, nama_kustomer from kustomer");
											while($cd=mysqli_fetch_assoc($customerdelivery)){
												if($r['delivery_customer']==$cd['id_kustomer']){
													echo'
													<option value="'.$cd['id_kustomer'].'" selected>'.$cd['nama_kustomer'].'</option>';
												}else{
													echo'
													<option value="'.$cd['id_kustomer'].'">'.$cd['nama_kustomer'].'</option>';
												}
											}
											echo'
										</select>
										<div class="val-message"></div>
									</div>
								</div>';
							}
							echo'
							<div class="form-group">
								<label class="col-md-3 control-label">Delivery Area </label>
								<div class="col-md-6">
									<select class="form-control select2" name="delivery_area[]" multiple="multiple" data-placeholder="Select pickup area" style="width: 100%;">';
										if($r['level']=='kurir' || $r['level']=='driver'){
											$kotatujuan = mysqli_query($con, "select a.id_kota, case when b.nama_kota is null then a.nama_kota when x_namakota(b.induk) is null then concat(a.nama_kota,', ',b.nama_kota) else concat(a.nama_kota,', ',b.nama_kota,', ',x_namakota(b.induk)) end as nama_kota from kota a left join kota b on b.id_kota=a.induk where a.kota_tujuan='Y' and a.intercity='Y' order by a.id_kota");
										}else{
											$kotatujuan = mysqli_query($con, "select a.id_kota, case when b.nama_kota is null then a.nama_kota when x_namakota(b.induk) is null then concat(a.nama_kota,', ',b.nama_kota) else concat(a.nama_kota,', ',b.nama_kota,', ',x_namakota(b.induk)) end as nama_kota from kota a left join kota b on b.id_kota=a.induk where a.kota_tujuan='Y' and a.intercity!='Y' order by a.id_kota");
										}
										$_arrNilai = explode(',', $r['delivery_area']);
										while($t=mysqli_fetch_assoc($kotatujuan)){
											$_ck = (array_search($t['id_kota'], $_arrNilai) === false)? '' : 'selected';
											echo '<option value="'.$t['id_kota'].'" '.$_ck.'>'.$t['nama_kota'].' </option>';
										}
										echo'
									</select>
									<div class="val-message"></div>
								</div>
							</div>';
						}
						echo'
					</div><!-- /.box-body -->
					<div class="box-footer">
						<button type="button" class="btn btn-default" onclick="window.location.href = \'?module=users\';">Cancel</button>
						<button type="submit" class="btn btn-primary pull-right">Save</button>
					</div><!-- /.box-footer -->
					</form>
				</div><!-- /.box -->
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
					<form class="form-horizontal" id="form-user" method="post" action="'.$aksi.'?module=users&act=update1" autocomplete="off">
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
						if($r['loc_code_otomatis']!=''){

							echo'
							<div class="form-group">
								<label class="col-md-3 control-label">Loc Code / Network Number  <span class="required">* </span></label>
								<div class="col-md-5">
									

								';
								echo '<select name="loc_code"  data-placeholder="Daftar Loc Code ..." class="form-control select2">
											<option value="">Select...</option>';
										$loc_code_otomatis = explode(',', $r['loc_code_otomatis']);
										//$project_name_client = array($u['perusahaan_pengirim']);
										foreach ($loc_code_otomatis as $l) {
											echo '
												<option value="'.str_replace("'","",$l).'">'.str_replace("'","",$l).'</option>
											';
										}
									echo '</select>';

							echo '
								</div>
								<div class="col-md-1">
									<button type="button" id="open_user_add_loc_code"  data-id="'.$r['id_user'].'" class="btn btn-primary pull-right">Add New</button>
								</div>
							</div>';

						}
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
						<button type="button" class="btn btn-default" onclick="window.location.href = \'?module=users\';">Cancel</button>
						<button type="submit" class="btn btn-primary pull-right">Save</button>
					</div><!-- /.box-footer -->
					</form>
				</div><!-- /.box -->
			</section>

			<div id="user_add_loc_code_otomatis" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
							<h4 class="modal-title" id="myModalLabel">Add New Loc Code </h4>

						</div>
						<div class="modal-content">
							<div class="modal-body"></div>
						</div>
					</div>
				</div>
			</div>';
			
			break;

			case "create_customer":
			
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
					<form class="form-horizontal" id="form-customer" method="post" action="'.$aksi.'?module=users&act=create" autocomplete="off">
					<div class="box-body">
						<div class="form-group">
							<label class="col-md-3 control-label">Kustomer <span class="required">* </span></label>
							<div class="col-md-6">
								<select name="id_kustomer" data-placeholder="Choose a Customer..." class="form-control select2">
									<option value="">Select...</option>';
									$cabang = mysqli_query($con, "select id_kustomer,nama_kustomer from kustomer order by nama_kustomer");
									while($c=mysqli_fetch_assoc($cabang)){
										echo'
										<option value="'.$c['id_kustomer'].'">'.$c['nama_kustomer'].'</option>';
									}
									echo'
								</select>
								<div class="val-message"></div>
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-md-3 control-label">Name <span class="required">* </span></label>
							<div class="col-md-6">
								<input type="text" name="fullname" placeholder="fullname..." class="form-control" required>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label">Username <span class="required">* </span></label>
							<div class="col-md-6">
								<input type="text" name="username" placeholder="usernamme..." class="form-control" required>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label">Email <span class="required">* </span></label>
							<div class="col-md-6">
								<input type="email" name="email" placeholder="email..." class="form-control" required>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label">password <span class="required">* </span></label>
							<div class="col-md-6">
								<input type="password" name="password" class="form-control" required>
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
			
			case "create_usercesco":
			
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
					<form class="form-horizontal" id="form-customer" method="post" action="'.$aksi.'?module=users&act=make_usercesco" autocomplete="off">
					
					<div class="box-body">
						<div class="form-group">
							<label class="col-md-3 control-label">Username <span class="required">* </span></label>
							<div class="col-md-6">
								<input type="text" name="username" placeholder="username..." class="form-control" required>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label">Name <span class="required">* </span></label>
							<div class="col-md-6">
								<input type="text" name="fullname" placeholder="fullname..." class="form-control" required>
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-3 control-label">Email <span class="required">* </span></label>
							<div class="col-md-6">
								<input type="email" name="email" placeholder="email..." class="form-control" required>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label">password <span class="required">* </span></label>
							<div class="col-md-6">
								<input type="password" name="password" class="form-control" required>
							</div>
						</div>	
						<div class="form-group">
							<label class="col-md-3 control-label">Branch <span class="required">* </span></label>
							<div class="col-md-6">
								<select name="id_cabang" data-placeholder="Choose a Branch..." class="form-control select2">
									<option value="">Select...</option>';
									$cabang = mysqli_query($con, "select id_cabang,nama_cabang from cabang order by nama_cabang");
									while($c=mysqli_fetch_assoc($cabang)){
										echo'
										<option value="'.$c['id_cabang'].'">'.$c['nama_cabang'].'</option>';
									}
									echo'
								</select>
								<div class="val-message"></div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label">Level <span class="required">* </span></label>
							<div class="col-md-6">
								<select name="level" data-placeholder="Choose a Level..." class="form-control select2">
									<option value="">Select...</option>
									<option value="admin">Admin</option>
									<option value="operator">Operator</option>
									<option value="finance">Finance</option>
									<option value="domex">Domex</option>
									<option value="network">Network</option>
									<option value="kurir">Kurir</option>
									<option value="driver">Driver</option>
									<option value="agent">Agent</option>
									<option value="kurir_agent">Kurir Agent</option>
									<option value="sales">sales promotion</option>
								</select>
								<div class="val-message"></div>
							</div>
						</div>				
					</div><!-- /.box-body -->
					<div class="box-footer">
						<button type="button" class="btn btn-default" onclick="window.location.href = \'?module=customer\';">Cancel</button>
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