<section class="content-header">
	<h1>SLA Inbound<small></small></h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-home"></i> Home</a></li>
		<li><a href="#">Master Data</a></li>
		<li><a href="#">SLA</a></li>
		<li class="active">Inbound</li>
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
											<button type="button" class="btn btn-default btn-sm" onclick="window.location.href = \'?module=sla-inbound&act=create\';"><i class="fa fa-plus"></i> Create New</button>
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
												x_namakotanew(kota_asal) as nama_kota_asal,kota_asal, x_service(service) as id_pelayanan, 
												sla_max,id
											FROM sla_in
											ORDER BY x_namakotanew(kota_tujuan) ASC";
									$result = $mysqli->query($query);
									while ( $row=$result->fetch_assoc() ) {
										$key = 'asdf$1011';
										$stringawal = $row['id'];
										$id = base64_encrypt($stringawal,$key);
										echo'
										</tr>											
											
											<td>'.$row['nama_kota_asal'].'</td>
											<td> JABODETABEK </td>
											<td align="right">'.$row['id_pelayanan'].'</td>
											<td align="right">'.$row["sla_max"].' hari</td>
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
					<form class="form-horizontal" method="POST" action="">
					<div class="box-body">
						<div class="form-group">
							<label class="col-md-3 control-label">Province</label>
							<div class="col-md-6">
								<select name="id_provinces" class="form-control select2">
									<option value="">Select...</option>';
									$provinces = $mysqli->query("select id, name from provinces where id != 31 order by name");
										while($p=$provinces->fetch_assoc()){
											echo '<option value="'.$p['id'].'">'.$p['name'].'</option>';									
										}
									
									echo'
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label">Service </label>
							<div class="col-md-6">
								<select name="id_pelayanan" class="form-control select2">
									<option value="">Select...</option>
									
										<option value="3"> Reguler </option>
										<option value="5"> Trucking </option>
										<option value="12"> Reguler DG </option>
									
									
								</select>
							</div>
						</div>
						
						<div class="box-footer">
							<button type="button" class="btn btn-default" onclick="window.location.href = \'?module=agent-inbound\';">Cancel</button>
							<button type="submit" name="search" class="btn btn-primary pull-right">Search</button>
						</div>
					</form>

					<form method="POST" action="'.$aksi.'?module=sla&act=insert-sla-inbound">
					<table id="sla-outbound-insert" class="table table-bordered table-striped table-hover">

						<tr>
							<td>Kota Tujuan</td>	
							<td>Service</td>	
							<td>SLA</td>	
						</tr>

						';
						if ($_SERVER["REQUEST_METHOD"] == "POST") {
							 $id_province = $_POST['id_provinces'];
							 $id_service = $_POST['id_pelayanan'];

							 /*echo 'select a.* from regencies a 
							 	left join provinces c on a.province_id = c.id
							 	
							 	where c.id = '.$id_province.' and a.id not in (select kota_tujuan from sla j left join regencies k on j.kota_tujuan=k.id where k.province_id='.$id_province.')
							 	';*/

							 $sql = mysqli_query($con, 'select a.* from regencies a 
							 	left join provinces c on a.province_id = c.id
							 	
							 	where c.id = '.$id_province.' and intercity="" and a.id not in (select kota_asal from sla_in j left join regencies k on j.kota_asal=k.id where k.province_id='.$id_province.' and j.service='.$id_service.')
							 	');
							 while($row = mysqli_fetch_assoc($sql)){

							echo 
							'<tr>
								<td>'.$row['name'].'</td>
								<td>'.$id_service.'</td>
								<td> <input type="text" name="sla['.$row[id].']" >  </td>
							</tr>
							';
							}
						}
						
						echo '
						
					</table>
					<input type="text" name="service" value="'.$id_service.'" >
					<div class="box-footer">
							<button type="button" class="btn btn-default" onclick="window.location.href = \'?module=agent-outbound\';">Cancel</button>
							<button type="submit" name="search" class="btn btn-primary pull-right">Input</button>
						</div>

					</form>

				</div>


			</section>

			';
			
			break;
			
		}
	}else{
		echo akses_salah();
	}
}
?>