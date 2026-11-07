<section class="content-header">
	<h1>Customer<small></small></h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-home"></i> Home</a></li>
		<li><a href="#">Master Data</a></li>
		<li class="active">Customer</li>
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
	if($u['level']=='admin' || $u['level']=='domex' || $u['tambahan_admin']==2){
		$aksi	= "module/mod_customer/customer_action.php";
		
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
											<button class="btn btn-sm btn-primary" onclick="window.location.href = \'?module=customer&act=create\';">Add New <i class="fa fa-plus"></i></button>
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
								<table id="customer" class="table table-bordered table-striped table-hover">
									<thead>
										<tr>
											<th>Kode</th>
											<th>Nama Customer</th>
											<th>Alamat</th>
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
					<form class="form-horizontal" id="form-customer" method="post" action="'.$aksi.'?module=customer&act=create" autocomplete="off">
					<div class="box-body">
						<div class="form-group">
							<label class="col-md-3 control-label">Branch/Agent <span class="required">* </span></label>
							<div class="col-md-6">
								<select name="id_cabang" data-placeholder="Choose a Branch/Agent..." class="form-control select2">
									<option value="">Select...</option>';
									$cabang = mysqli_query($con, "select id_cabang,concat(nama_cabang,' ','(',alamat1,')') as nama_cabang from cabang where status='Y' order by nama_cabang");
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
							<label class="col-md-3 control-label">Kode <span class="required">* </span></label>
							<div class="col-md-6">
								<input type="text" name="kode_kustomer" id="inputID" placeholder="Kode..." class="form-control">
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label">Nama <span class="required">* </span></label>
							<div class="col-md-6">
								<input type="text" name="nama_kustomer" placeholder="Nama..." class="form-control">
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label">Alamat <span class="required">* </span></label>
							<div class="col-md-6">
								<textarea name="alamat" placeholder="Alamat..." class="form-control" rows="2"></textarea>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label">Kota <span class="required">* </span></label>
							<div class="col-md-6">
								<select name="kota" data-placeholder="Choose a City..." class="form-control select2">
									<option value="">Select...</option>';
									$kotatujuan = mysqli_query($con, "select a.id_kota, case when b.nama_kota is null then a.nama_kota when x_namakota(b.induk) is null then concat(a.nama_kota,', ',b.nama_kota) else concat(a.nama_kota,', ',b.nama_kota,', ',x_namakota(b.induk)) end as nama_kota from kota a left join kota b on b.id_kota=a.induk where a.kota_tujuan='Y'");
									while($t=mysqli_fetch_assoc($kotatujuan)){
										echo'
										<option value="'.$t['id_kota'].'">'.$t['nama_kota'].'</option>';
									}
									echo'
								</select>
								<div class="val-message"></div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label">Telp. </label>
							<div class="col-md-6">
								<input type="text" name="telp" placeholder="Telp..." class="form-control">
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

			$query = mysqli_query($con, "select * from kustomer where id_kustomer='$id'");
			$r = mysqli_fetch_assoc($query);
									
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
					<form class="form-horizontal" id="form-customer" method="post" action="'.$aksi.'?module=customer&act=update" autocomplete="off">
					<input type="hidden" name="id" value="'.$r['id_kustomer'].'">
					<div class="box-body">
						<div class="form-group">
							<label class="col-md-3 control-label">Branch/Agent <span class="required">* </span></label>
							<div class="col-md-6">
								<select name="id_cabang" data-placeholder="Choose a Branch/Agent..." class="form-control select2">
									<option value="">Select...</option>';
									$cabang = mysqli_query($con, "select id_cabang,concat(nama_cabang,' ','(',alamat1,')') as nama_cabang from cabang where status='Y' order by nama_cabang");
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
							<label class="col-md-3 control-label">Kode <span class="required">* </span></label>
							<div class="col-md-6">
								<input type="text" name="kode_kustomer" id="inputID" placeholder="Kode..." class="form-control" value="'.$r['kode_kustomer'].'" disabled>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label">Nama <span class="required">* </span></label>
							<div class="col-md-6">
								<input type="text" name="nama_kustomer" placeholder="Nama..." class="form-control" value="'.$r['nama_kustomer'].'">
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label">Alamat <span class="required">* </span></label>
							<div class="col-md-6">
								<textarea name="alamat" placeholder="Alamat..." class="form-control" rows="2">'.$r['alamat'].'</textarea>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label">Kota <span class="required">* </span></label>
							<div class="col-md-6">
								<select name="kota" data-placeholder="Choose a City..." class="form-control select2">
									<option value="">Select...</option>';
									$kotatujuan = mysqli_query($con, "select a.id_kota, case when b.nama_kota is null then a.nama_kota when x_namakota(b.induk) is null then concat(a.nama_kota,', ',b.nama_kota) else concat(a.nama_kota,', ',b.nama_kota,', ',x_namakota(b.induk)) end as nama_kota from kota a left join kota b on b.id_kota=a.induk where a.kota_tujuan='Y'");
									while($t=mysqli_fetch_assoc($kotatujuan)){
										if($r['kota']==$t['id_kota']){
											echo'
											<option value="'.$t['id_kota'].'" selected>'.$t['nama_kota'].'</option>';
										}else{
											echo'
											<option value="'.$t['id_kota'].'">'.$t['nama_kota'].'</option>';
										}
									}
									echo'
								</select>
								<div class="val-message"></div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label">Telp. </label>
							<div class="col-md-6">
								<input type="text" name="telp" placeholder="Telp..." class="form-control" value="'.$r['telp'].'">
							</div>
						</div>
						<div class="form-group">
						    <label class="col-md-3 control-label">Penagihan inv ke</label>
						    <div class="col-md-6">
						        <select name="penagihan[]" 
						                multiple
						                data-placeholder="Pilih customer (boleh lebih dari satu)"
						                class="form-control select2">
									<option value="">Select...</option>';
									//echo "select id_kustomer, x_namakustomer(a.id_kustomer) as namakustomer from kustomer";
									$kustomer = mysqli_query($con, "
						                SELECT id_kustomer, x_namakustomer(id_kustomer) as namakustomer 
						                FROM kustomer 
						                WHERE id_kustomer != '$id'
						            ");
						            $tagihan = mysqli_query($con, "
						                SELECT id_kustomer_tagih 
						                FROM kustomer_tagihan 
						                WHERE id_kustomer = '$id'
						            ");
						             $selected_tagihan = [];
						            while($t = mysqli_fetch_assoc($tagihan)){
						                $selected_tagihan[] = $t['id_kustomer_tagih'];
						            }
									while($k = mysqli_fetch_assoc($kustomer)){

						                $selected = in_array($k['id_kustomer'], $selected_tagihan) ? 'selected' : '';

						                echo '<option '.$selected.' value="'.$k['id_kustomer'].'">
						                        '.$k['namakustomer'].'
						                      </option>';
						            }
									echo'
								</select>
								<div class="val-message"></div>
							</div>
						</div>

						';/*
						<div class="form-group">
							<label class="col-md-3 control-label"></label>
							<div class="col-md-6">';
							if($r['status']=='Y'){
								echo'
								<label><input type="checkbox" class="minimal" name="status" value="Y" checked> Active</label>';
							}else{
								echo'
								<label><input type="checkbox" class="minimal" name="status" value="Y"> Active</label>';
							}
								
							</div>
						</div>*/
						echo'
					</div><!-- /.box-body -->
					<div class="box-footer">
						<button type="button" class="btn btn-default" onclick="window.location.href = \'?module=customer\';">Cancel</button>
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