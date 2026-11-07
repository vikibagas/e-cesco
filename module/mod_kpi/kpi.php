<section class="content-header">
	<h1>KPI<small></small></h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-home"></i> KPI</a></li>
		<li class="active"><a href="#">Indicator</a></li>
		
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
	if($u['level']=='admin' ){
		
		$aksi	= "module/mod_kpi/kpi_action.php";
		
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
								<li class="active"><a href="#tab_1" data-toggle="tab">KPI</a></li>
								
							</ul>
							<div class="tab-content">
								<div class="tab-pane active" id="tab_1">
									<div class="box-header">
										<div class="row">
											<div class="col-md-12">
												<div class="btn-group">
													
												</div>
											</div>										
										</div>
									</div>
									<table id="kpi" class="table table-bordered table-striped table-hover">
										<thead>
											<tr>
												<th>Jabatan</th>
												
												<th>Jumlah Indikator</th>
												
												
												<th width="50px">Detail</th>
											</tr>
                      </thead>
                     <tbody>
                        ';
                          $query = mysqli_query($con, "select a.id,jabatan,type_penilaian, nama_level,COALESCE(SUM(bobot), 0) as jumlah_bobot  from jabatan a

                          	left join kpi b on a.id = b.id_jabatan

                          	GROUP BY 
    a.id, jabatan, nama_level;

                          	");
                        

                          while($row= mysqli_fetch_assoc($query)){
                              echo  '<tr>
                                      <td>'.$row['jabatan'].'</td>
                          
                                      <td>'.$row['jumlah_bobot']. ($row['jumlah_bobot'] < 100 ? ' <button class="btn btn-xs btn-danger"><i class="fa fa-exclamation-circle"></i> belum 100%</button>' : '').' </td>
                            		 
                                      <td width="50px">
                                       <button class="btn btn-xs btn-warning" onclick="window.location.href = \'?module=kpi&act=detail&id='.base64_encrypt($row['id'],$key).'\';">detail</button> 

          
                                      </td>
                                    </tr>
                                    ';
                          } 

                        echo '
                        
                     
										
										
										</tbody>
									</table>
								</div>
								
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
			
			case "detail":
			
			$key = "asdf$1011";
			$encrypt = "$_GET[id]";
			$id = base64_decrypt($encrypt,$key);

      $query = mysqli_query($con, "select sum(bobot) as jumlah_bobot, b.jabatan 
      	from kpi a 
      	inner join jabatan b on a.id_jabatan = b.id
       where id_jabatan=$id");

      
      $r = mysqli_fetch_assoc($query);
			
									
			echo'
			<section class="content">
			';
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

				echo '

	      <div class="row">
		      <div class="col-lg-3 col-xs-6">
		            <!-- small box -->
		            <div class="small-box bg-aqua">
		              <div class="inner">
		                <h3>
		                    ';

		                    echo $r['jumlah_bobot'].' %';



		                  

		                    echo 
		                    '
		                
		                </h3>
		                <p>Jumlah indeks Score KPI '.$r['jabatan'].'</p>
		                      </div>
		                      <div class="icon">
		                <i class="ion ion-pie-graph"></i>
		                      </div>
		                      
		            </div>
		     

		      </div>
		      <div class="col-lg-9 col-xs-6">
						';

						if( $r['jumlah_bobot'] < 100) {
							 echo '<div class="alert alert-danger" role="alert">
								  Jumlah Bobot belum mencapai 100%, harap isi hingga 100%
								</div>'	;
						}
						
					
						echo '
					</div>

		    	
		  	</div>
		   

		   
				<div class="row">
					<div class="col-xs-12">
						<div class="box box-primary">
							<div class="box-header">
								<div class="row">
									<div class="col-md-6">
										<div class="btn-group">
											<button class="btn btn-sm btn-primary" onclick="window.location.href = \'?module=kpi&act=new&id='.$_GET['id'].'\';">Add New <i class="fa fa-plus"></i></button>
										</div>
									</div>
									<div class="col-md-6">
									</div>
								</div>
							</div>
							<div class="box-body">
								<table id="kpi_indicator" class="table table-bordered table-striped table-hover">
									<thead>
										<tr>
											<th>KPI</th>
											<th width="50px">Bobot</th>
											<th>Target</th>
											<th width="50px">tipe</th>
											<th width="90px">Action</th>
										</tr>
									</thead>
									<tbody>
										';
										  $query = mysqli_query($con, "select id,kpi, bobot,target,detail_target,type_penilaian from kpi where id_jabatan=$id");

                        while($row= mysqli_fetch_assoc($query)){
                        	$key = "asdf$1011";
													$stringawal = $row['id'];
													$id_kpi = base64_encrypt($stringawal,$key);
													echo '
													<tr>
														<td>'.$row['kpi'].'</td>
														<td>'.$row['bobot'].' %</td>
														<td>'.$row['target'].' '.$row['detail_target'].'</td>
														<td>'.$row['type_penilaian'].'</td>
														<td><center><button class="btn btn-xs btn-warning" onclick="window.location.href = \'?module=kpi&act=update&id='.$id_kpi.'&back='.$_GET['id'].'\';"><i class="fa fa-pencil"></i></button> <button data-href="'.$aksi.'?module=kpi&act=delete&id='.$id_kpi.'&back='.$_GET['id'].'" class="btn btn-xs btn-danger" type="button" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash"></i></button></center></td>
													</tr>
													';
												}
										echo '
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
      	

    </section>

			';
			
			break;
			
			
			

			
			case "new":

			$key = "asdf$1011";
			$encrypt = "$_GET[id]";
			$id = base64_decrypt($encrypt,$key);
			
			echo'
			<section class="content">
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">New KPI</h3>
						<div class="box-tools pull-right">
							<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
							<button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
						</div>
					</div><!-- /.box-header -->
					<form class="form-horizontal" id="form-customer" method="post" action="'.$aksi.'?module=kpi&act=new" autocomplete="off">
					<div class="box-body">
						<div class="form-group">
							<label class="col-md-3 control-label">Jabatan<span class="required">* </span></label>
							<div class="col-md-6">
								';



								      $jabatan = mysqli_query($con, "select jabatan,COALESCE(SUM(bobot), 0) as jumlah_bobot  from jabatan a

                          	left join kpi b on a.id = b.id_jabatan
								       where a.id=$id
								      ");



								      
								
								$j=mysqli_fetch_assoc($jabatan);
								//echo 	$j['jumlah_bobot'];
								$max_input_bobot = 100-$j['jumlah_bobot'];

										echo'
										<input type="hidden" name="id_jabatan" id="" placeholder="Kode..." class="form-control" value="'.$id.'">
										<input type="text" readonly name="jabatan" id="" placeholder="Kode..." class="form-control" value="'.$j['jabatan'].'">';
									
									echo'
					
								<div class="val-message"></div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label">KPI<span class="required">* </span></label>
							<div class="col-md-6">
								<input type="text"  name="kpi" id="inputID" placeholder="KPI..." class="form-control">
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label">Bobot % <span class="required">* </span></label>
							<div class="col-md-6">
								<input type="number" min="1" max="'.$max_input_bobot.'" name="bobot" id="inputID" placeholder="Bobot..." class="form-control">
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label">Target <span class="required">* </span></label>
							<div class="col-md-6">
								<input type="number" min="1" name="target" placeholder="Target..." class="form-control">
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label">Detail target <span class="required">* </span></label>
							<div class="col-md-6">
								<input type="text"  name="detail_target" placeholder="Detail Target..." class="form-control">
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label">Tipe input data <span class="required">* </span></label>
							<div class="col-md-6">
								<select name="type" data-placeholder="Choose a City..." class="form-control">
									<option value="manual">Manual input</option>
									<option value="otomatis">otomatis</option>
									
								</select>
								<div class="val-message"></div>
							</div>
						</div>
						
					</div><!-- /.box-body -->
					<div class="box-footer">
						<button type="button" class="btn btn-default" onclick="window.location.href = \'?module=kpi\';">Cancel</button>
						<button type="submit" class="btn btn-primary pull-right">Save</button>
					</div><!-- /.box-footer -->
					</form>
				</div><!-- /.box -->
			</section>';
			
			break;


			case "update":
			
			$key		= "asdf$1011";
			$encrypt	= "$_GET[id]";
			$id			= base64_decrypt($encrypt,$key);

			$query		= $mysqli->query("SELECT * FROM kpi WHERE id='$id'");
			$r			= $query->fetch_assoc();

			  $jabatan = mysqli_query($con, "select jabatan,COALESCE(SUM(bobot), 0) as jumlah_bobot  from jabatan a

                          	left join kpi b on a.id = b.id_jabatan
								       where a.id=".$r['id_jabatan']."
								      ");



								      
								
								$j=mysqli_fetch_assoc($jabatan);
								//echo 	$j['jumlah_bobot'];
								$max_input_bobot = 100-$j['jumlah_bobot']+$r['bobot'];
									
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
					<form class="form-horizontal" id="form-kpi" method="post" action="'.$aksi.'?module=kpi&act=update&back='.$_GET['back'].'" autocomplete="off">
					<input type="hidden" name="id_kpi" value="'.$_GET['id'].'">
					<input type="hidden" name="id_jabatan" value="'.$r['id_jabatan'].'">

					<div class="box-body">
						<div class="form-group">
							<label class="col-md-3 control-label">JABATAN</label>
							<div class="col-md-6">
								<input type="text" readonly name="jabatan" placeholder="kpi..." class="form-control" value="'.$j['jabatan'].'">
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label">KPI</label>
							<div class="col-md-6">
								<input type="text" name="kpi" placeholder="kpi..." class="form-control" value="'.$r['kpi'].'">
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-md-3 control-label">bobot %<span class="required">* </span></label>
							<div class="col-md-6">
								<input type="number" min="5" max="'.$max_input_bobot.'" name="bobot" placeholder="bobot..." class="form-control" value="'.$r['bobot'].'">
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label">target <span class="required">* </span></label>
							<div class="col-md-6">
								<input type="number" min="1" name="target" placeholder="target..." class="form-control" value="'.$r['target'].'">
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label">detail target <span class="required">* </span></label>
							<div class="col-md-6">
								<input type="text" name="detail_target" placeholder="target..." class="form-control" value="'.$r['detail_target'].'">
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label">Tipe Input data <span class="required">* </span></label>
							<div class="col-md-6">
								<select name="type" class="form-control ">
									<option value="manual" '.($r['type_penilaian'] == 'manual' ? 'selected' : '').'>Manual</option>
									<option value="otomatis" '.($r['type_penilaian'] == 'otomatis' ? 'selected' : '').'>otomatis</option>
									
									
								</select>
							</div>
						</div>
					</div>
					<div class="box-footer">
						<button type="button" class="btn btn-default" onclick="window.location.href = \'?module=kpi\';">Cancel</button>
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