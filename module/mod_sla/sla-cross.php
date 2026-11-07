<section class="content-header">
	<h1>SLA Cross<small></small></h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-home"></i> Home</a></li>
		<li><a href="#">Master Data</a></li>
		<li><a href="#">SLA</a></li>
		<li class="active">Cross City</li>
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
	if($u['level']=='admin' || $u['level']=='finance' || $u['id_user']== 8){
		$aksi	= "module/mod_sla/sla_action.php";
		
		$act = !empty($_GET['act']) ? $_GET['act'] : '';
		
		switch($act){
			
			default:
			echo'
			<section class="content">
				<div class="row">
					<div class="col-xs-12">
						<div class="box box-default">
							<div class="box-header">
								<div class="row">
									<div class="col-md-12">
										<div class="btn-group">
											<button type="button" class="btn btn-default btn-sm" onclick="window.location.href = \'?module=sla-cross&act=create\';"><i class="fa fa-plus"></i> Create New</button>
										</div>
									</div>
								</div>
							</div>
							<div class="box-body">
								<table id="sla" class="table table-bordered table-striped table-hover">
									<thead>
										<tr>
											<th>Kota Asal</th>
											<th>Kota Tujuan</th>
											<th>Service</th>											
											<th>SLA</th>
											<th width="80px">Action</th>
										</tr>
									</thead>
									<tbody>';
									$query=	"SELECT 												
												x_namakotanew(kota_asal) as nama_kota_asal, x_namakotanew(kota_tujuan) as nama_kota_tujuan,kota_asal, x_service(service) as id_pelayanan, id,
												sla_max 
											FROM sla_cross where kota_asal != 0 and kota_tujuan != 0
											ORDER BY x_namakotanew(kota_tujuan) ASC";
									$result = $mysqli->query($query);
									while ( $row=$result->fetch_assoc() ) {
										$key = 'asdf$1011';
										$stringawal = $row['id'];
										$id = base64_encrypt($stringawal,$key);
										echo'
										</tr>											
											
											<td>'.$row['nama_kota_asal'].'</td>
											<td>'.$row['nama_kota_tujuan'].'</td>
											<td align="right">'.$row['id_pelayanan'].'</td>
											<td align="right">'.$row["sla_max"].' hari</td>
											<td><center>
												<div class="btn-group">
													<button type="button" class="btn btn-default btn-sm" onclick="window.location.href = \'?module=sla-cross&act=update&id='.$id.'\';"><i class="fa fa-pencil"></i></button>
													<button type="button" class="btn btn-default btn-sm" data-href="'.$aksi.'?module=sla&act=deletecross&id='.$id.'" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash"></i></button>
												</div>
											</center></td>
										</tr>';
									}
									echo'
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</section>';
			
			break;
			
			case "update":
			
			$key = "asdf$1011";
			$encrypt = $mysqli->real_escape_string($_GET['id']);
			$id = base64_decrypt($encrypt,$key);
			$query = "SELECT * FROM sla_cross WHERE id='$id'";
			$result = $mysqli->query($query);
			$row = $result->fetch_assoc();
			
			echo'
			<section class="content">
				<div class="box box-default">
					<div class="box-header with-border">
						<h3 class="box-title">Create</h3>
						<div class="box-tools pull-right">
							<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
							<button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
						</div>
					</div><!-- /.box-header -->
					<form class="form-horizontal" method="post" action="'.$aksi.'?module=sla&act=updatecross" autocomplete="off">
					<input type="text" name="id_sla" value="'.$id.'">
					<div class="box-body">
						<div class="form-group">
							<label class="col-md-3 control-label">Asal</label>
							<div class="col-md-6">
								<select name="asal" class="form-control select2">
									<option value="">Select...</option>';
									$kota = $mysqli->query("select id, name from regencies order by name");
									while($k=$kota->fetch_assoc()){
										if($row['kota_asal']==$k['id']){
											echo'
											<option value="'.$k['id'].'" selected>'.$k['name'].'</option>';
										}else{
											echo'
											<option value="'.$k['id'].'">'.$k['name'].'</option>';
										}
									}
									echo'
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label">Tujuan</label>
							<div class="col-md-6">
								<select name="tujuan" class="form-control select2">
									<option value="">Select...</option>';
									$kota = $mysqli->query("select id, name from regencies order by name");
									while($k=$kota->fetch_assoc()){
										if($row['kota_tujuan']==$k['id']){
											echo'
											<option value="'.$k['id'].'" selected>'.$k['name'].'</option>';
										}else{
											echo'
											<option value="'.$k['id'].'">'.$k['name'].'</option>';
										}
									}
									echo'
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label">Service </label>
							<div class="col-md-6">
								<select name="id_pelayanan" class="form-control select2">
									<option value="">Select...</option>';
									$pelayanan = $mysqli->query("select id_pelayanan,nama_pelayanan from pelayanan");
									while($p=$pelayanan->fetch_assoc()){
										if($row['service']==$p['id_pelayanan']){
											echo'
											<option value="'.$p['id_pelayanan'].'" selected>'.$p['nama_pelayanan'].'</option>';
										}else{
											echo'
											<option value="'.$p['id_pelayanan'].'">'.$p['nama_pelayanan'].'</option>';
										}
									}
									echo'
								</select>
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-md-3 control-label">SLA</label>
							<div class="col-md-6">
								<input type="text" name="sla" placeholder="SLA" class="form-control" value="'.$row['sla_max'].'">
							</div>
						</div>
						
					</div>
					<div class="box-footer">
						<button type="button" class="btn btn-default" onclick="window.location.href = \'?module=sla-cross\';">Cancel</button>
						<button type="submit" class="btn btn-primary pull-right">Save</button>
					</div>
					</form>
				</div>
			</section>';
			
			break;
			
			case "create":
			
			echo'
			<section class="content">
				<div class="box box-default">
					<div class="box-header with-border">
						<h3 class="box-title">Create</h3>
						<div class="box-tools pull-right">
							<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
							<button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
						</div>
					</div><!-- /.box-header -->
					<form class="form-horizontal" method="post" action="'.$aksi.'?module=sla&act=insert-sla-cross" autocomplete="off">
					<div class="box-body">
						<div class="form-group">
							<label class="col-md-3 control-label">Kota Asal</label>
							<div class="col-md-6">
								<select name="id_kota_asal" class="form-control select2">
									<option value="">Select...</option>';
									$kota = $mysqli->query("select id, name, x_province(province_id) as provinsi from regencies where intercity!='Y' order by name");
									while($k=$kota->fetch_assoc()){
										echo'
										<option value="'.$k['id'].'">'.$k['name'].', '.$k['provinsi'].'</option>';
									}
									echo'
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label">Kota tujuan</label>
							<div class="col-md-6">
								<select name="id_kota_tujuan" class="form-control select2">
									<option value="">Select...</option>';
									$kota = $mysqli->query("select id, name, x_province(province_id) as provinsi from regencies where intercity!='Y' order by name");
									while($k=$kota->fetch_assoc()){
										echo'
										<option value="'.$k['id'].'">'.$k['name'].', '.$k['provinsi'].'</option>';
									}
									echo'
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label">Service </label>
							<div class="col-md-6">
								<select name="id_pelayanan" class="form-control select2">
									<option value="">Select...</option>';
									$pelayanan = $mysqli->query("select id_pelayanan,nama_pelayanan from pelayanan");
									while($p=$pelayanan->fetch_assoc()){
										echo'
										<option value="'.$p['id_pelayanan'].'">'.$p['nama_pelayanan'].'</option>';
									}
									echo'
								</select>
							</div>
						</div>
						<div class="form-group">
						
						<div class="form-group">
							<label class="col-md-3 control-label">SLA</label>
							<div class="col-md-6">
								<input type="number" name="sla" placeholder="SLA" min="1" value="1" class="form-control">
							</div>
						</div>
						
					</div>
					<div class="box-footer">
						<button type="button" class="btn btn-default" onclick="window.location.href = \'?module=agent-outbound\';">Cancel</button>
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