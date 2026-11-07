<section class="content-header">
	<h1>Pricelist Agent Outbound<small></small></h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-home"></i> Home</a></li>
		<li><a href="#">Master Data</a></li>
		<li><a href="#">Pricelist Agent</a></li>
		<li class="active">Outbound</li>
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
	if($u['level']=='admin' || $u['id_user']== 9 || $u['id_user']== 8){
		$aksi	= "module/mod_agent-outbound/agent-outbound_action.php";
		
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
											<button type="button" class="btn btn-default btn-sm" onclick="window.location.href = \'?module=agent-outbound&act=create\';"><i class="fa fa-plus"></i> Create New</button>
										</div>
									</div>
								</div>
							</div>
							<div class="box-body">
								<table id="agent_outbound" class="table table-bordered table-striped table-hover">
									<thead>
										<tr>
											<th>City Name</th>
											<th>Service</th>
											<th>Comodity</th>
											<th>Min. Kg</th>
											<th>Price</th>
											<th width="80px">Action</th>
										</tr>
									</thead>
									<tbody>';
									$query=	"SELECT 
												x_namakota(id_kota) as id_kota, 
												rateagent_id, x_service(id_pelayanan) as id_pelayanan, 
												case when komoditi='1' then 'Paket' else 'Dokumen' end as komoditi, 
												min_kg, 
												price
											FROM rate_agent
											ORDER BY x_namakota(id_kota) ASC";
									$result = $mysqli->query($query);
									while ( $row=$result->fetch_assoc() ) {
										$key = 'asdf$1011';
										$stringawal = $row['rateagent_id'];
										$id = base64_encrypt($stringawal,$key);
										echo'
										</tr>
											<td>'.$row['id_kota'].'</td>
											<td>'.$row['id_pelayanan'].'</td>
											<td>'.$row['komoditi'].'</td>
											<td align="right">'.$row['min_kg'].'</td>
											<td align="right">'.$row["price"].'</td>
											<td><center>
												<div class="btn-group">
													<button type="button" class="btn btn-default btn-sm" onclick="window.location.href = \'?module=agent-outbound&act=update&id='.$id.'\';"><i class="fa fa-pencil"></i></button>
													<button type="button" class="btn btn-default btn-sm" data-href="'.$aksi.'?module=agent-outbound&act=delete&id='.$id.'" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash"></i></button>
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
			$query = "SELECT * FROM rate_agent WHERE rateagent_id='$id'";
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
					<form class="form-horizontal" method="post" action="'.$aksi.'?module=agent-outbound&act=update" autocomplete="off">
					<input type="hidden" name="rateagent_id" value="'.$row['rateagent_id'].'">
					<div class="box-body">
						<div class="form-group">
							<label class="col-md-3 control-label">City Name </label>
							<div class="col-md-6">
								<select name="id_kota" class="form-control select2">
									<option value="">Select...</option>';
									$kota = $mysqli->query("select id_kota, nama_kota from kota where intercity!='Y' order by nama_kota");
									while($k=$kota->fetch_assoc()){
										if($row['id_kota']==$k['id_kota']){
											echo'
											<option value="'.$k['id_kota'].'" selected>'.$k['nama_kota'].'</option>';
										}else{
											echo'
											<option value="'.$k['id_kota'].'">'.$k['nama_kota'].'</option>';
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
										if($row['id_pelayanan']==$p['id_pelayanan']){
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
							<label class="col-md-3 control-label">Comodity </label>
							<div class="col-md-6">
								<select name="komoditi" class="form-control select2">
									<option value="">Select...</option>';
									if($row['komoditi']=='1'){
										echo'
										<option value="1" selected>Package</option>
										<option value="2">Document</option>';
									}elseif($row['komoditi']=='2'){
										echo'
										<option value="1">Package</option>
										<option value="2" selected>Document</option>';
									}
									echo'
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label">Min. Kg</label>
							<div class="col-md-6">
								<input type="text" name="min_kg" placeholder="Min. Kg" class="form-control" value="'.$row['min_kg'].'">
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label">Price</label>
							<div class="col-md-6">
								<input type="text" name="price" placeholder="Price..." class="form-control" value="'.$row['price'].'">
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
					<form class="form-horizontal" method="post" action="'.$aksi.'?module=agent-outbound&act=create" autocomplete="off">
					<div class="box-body">
						<div class="form-group">
							<label class="col-md-3 control-label">City Name </label>
							<div class="col-md-6">
								<select name="id_kota" class="form-control select2">
									<option value="">Select...</option>';
									$kota = $mysqli->query("select id_kota, nama_kota from kota where intercity!='Y' order by nama_kota");
									while($k=$kota->fetch_assoc()){
										echo'
										<option value="'.$k['id_kota'].'">'.$k['nama_kota'].'</option>';
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
							<label class="col-md-3 control-label">Comodity </label>
							<div class="col-md-6">
								<select name="komoditi" class="form-control select2">
									<option value="">Select...</option>
									<option value="1">Package</option>
									<option value="2">Document</option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label">Min. Kg</label>
							<div class="col-md-6">
								<input type="text" name="min_kg" placeholder="Min. Kg" class="form-control">
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label">Price</label>
							<div class="col-md-6">
								<input type="text" name="price" placeholder="Price..." class="form-control">
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