<!-- Content Header (Page header) -->
<section class="content-header">
	<h1>Dashboard <small>it all starts here</small></h1>
	<ol class="breadcrumb">
		<li class="active"><i class="fa fa-dashboard"></i> Traffic</li>
	</ol>
</section>

<!-- Main content -->
<section class="content">
	
	<!-- Small boxes (Stat box) -->
	
	<div class="row">
		
		<div class="col-lg-3 col-xs-6">
			<!-- small box -->
			<div class="small-box bg-aqua">
				<div class="inner">
					<h3>
					<?php
					$agent = mysqli_query($con, 'select id_kota_baru from cabang where id_cabang='.$u['id_cabang'].'');
					$a = mysqli_fetch_assoc($agent);
					
					
						$query = mysqli_query($con, "SELECT count(*) AS total FROM user_client_history WHERE   tanggal >= now()-interval 1 day");
					
					$r = mysqli_fetch_assoc($query);
					echo ''.$r['total'].'';
					?>
					</h3>
					<p>Login Client Yesterday until now <?php echo $u[level]; ?>  </p>
                </div>
                <div class="icon">
					<i class="ion ion-android-desktop"></i>
                </div>
                
			</div>
		</div><!-- ./col -->

		

		<div class="col-lg-3 col-xs-6">
			<!-- small box -->
			<div class="small-box bg-green">
				<div class="inner">
					<h3>
					<?php
						$query = mysqli_query($con, "SELECT count(*) AS total FROM user_history WHERE   tanggal >= now()-interval 1 day");
					$r = mysqli_fetch_assoc($query);
					echo ''.$r['total'].'';
					?>
					</h3>
                  <p>Total Login Admin/Finance Yesterday until now</p>
                </div>
                <div class="icon">
					<i class="ion ion-android-desktop"></i>
                </div>
                
            </div>
		</div><!-- ./col -->
		<div class="col-lg-3 col-xs-6">
			<!-- small box -->
			<div class="small-box bg-yellow">
				<div class="inner">
					<h3>
					<?php
					if($u['id_cabang']=='39'){
						$query = mysqli_query($con, "SELECT count(*) AS total FROM pengiriman WHERE delivery='Y' and tanggal >= now()-interval 3 month and status !=7");
					}else{
						$query = mysqli_query($con, "SELECT count(*) AS total FROM pengiriman WHERE delivery='Y' and tanggal >= now()-interval 3 month and status !=7 and delivery_by_agent=$u[id_cabang] ");
					}
					$r = mysqli_fetch_assoc($query);
					echo 0;
					?>
					</h3>
					<p>Mobile Apps</p>
				</div>
				<div class="icon">
					<i class="ion ion-android-phone-portrait"></i>
				</div>
				
			</div>
		</div><!-- ./col -->
		<div class="col-lg-3 col-xs-6">
			<!-- small box -->
			<div class="small-box bg-red">
				<div class="inner">
					<h3>
					<?php
						$query = mysqli_query($con, "SELECT count(*) AS total FROM visitor WHERE   date_created >= now()-interval 1 day");
					$r = mysqli_fetch_assoc($query);
					echo ''.$r['total'].'';
					?>
					</h3>
					<p>Visitor Yesterday until now</p>
				</div>
				<div class="icon">
					<i class="ion ion-ios-person-outline"></i>
				</div>
				
			</div>
		</div><!-- ./col -->
	</div><!-- /.row -->

	<!-- Small boxes (Stat box) -->
	
	
	<?php
	if($u['level']=='admin' || $u['level']=='network' || $u['level']=='inbound' || $u['level']=='inbound' || $u['level']=='agent' || $u['level']=='kurir') {
	?> 
	<!-- Main row -->
	<div class="row">
		<!-- Left col -->
		<section class="col-lg-7 connectedSortable">
			<div class="box box-info">
				<div class="box-header with-border">
					<h3 class="box-title">Latest Login</h3>
					<div class="box-tools pull-right">
						<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
						<button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
					</div>
				</div><!-- /.box-header -->
				<div class="box-body">
					<div class="table-responsive" id="latest-booking">
						<table class="table no-margin">
							<tr>
								<th>USER</th>
								<th>WAKTU</th>
								
							</tr>
							
								<?php 
									$query = mysqli_query($con,"select x_user(id_user) as user,concat(tanggal,' ',jam) as waktu from user_history order by id desc limit 10");
									while($q=mysqli_fetch_assoc($query)){
										echo '<tr>';
											echo '<td>'.$q['user'].'</td>';
											echo '<td>'.$q['waktu'].'</td>';
											
										echo '</tr>';

									}
								?>
							

							
						</table>
					</div><!-- /.table-responsive -->
				</div><!-- /.box-body -->
			</div><!-- /.box -->
		</section><!-- /.Left col -->
		
		<!-- right col (We are only adding the ID to make the widgets sortable)-->
		<section class="col-lg-5 connectedSortable">
			<!-- latest pickup -->
			<div class="box box-success">
				<div class="box-header with-border">
					<h3 class="box-title">Latest Login Client</h3>
					<div class="box-tools pull-right">
						<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
						<button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
					</div>
				</div><!-- /.box-header -->
				<div class="box-body">
					<div class="table-responsive" id="latest-pickup">
						<table class="table no-margin">
							<tr>
								<th>USER CLIENT</th>
								<th>WAKTU</th>
								
							</tr>
							
								<?php 
									$query = $query = mysqli_query($con,"select x_userclient(id_user) as user,tanggal as waktu from user_client_history order by id desc limit 10");
									while($q=mysqli_fetch_assoc($query)){
										echo '<tr>';
											echo '<td>'.$q['user'].'</td>';
											echo '<td>'.$q['waktu'].'</td>';
											
										echo '</tr>';

									}
								?>
							

							
						</table>
					</div><!-- /.table-responsive -->
				</div><!-- /.box-body -->
			</div><!-- /.box -->
		</section><!-- right col -->

	</div><!-- /.row (main row) -->

	<div class="row">
		<!-- Left col -->
		<section class="col-lg-12 connectedSortable">
			<div class="box box-info">
				<div class="box-header with-border">
					<h3 class="box-title">Latest Visitor Website</h3>
					<div class="box-tools pull-right">
						<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
						<button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
					</div>
				</div><!-- /.box-header -->
				<div class="box-body">
					<div class="table-responsive" id="latest-booking">
						<table class="table no-margin">
							<tr>
								<th>IP</th>
								<th>OS</th>
								<th>Browser</th>
								<th>Tanggal</th>
								
							</tr>
							
								<?php 
									$query = mysqli_query($con,"select ip,os,browser,date_created from visitor order by id desc limit 10");
									while($q=mysqli_fetch_assoc($query)){
										echo '<tr>';
											echo '<td>'.$q['ip'].'</td>';
											echo '<td>'.$q['os'].'</td>';
											echo '<td>'.$q['browser'].'</td>';
											echo '<td>'.$q['date_created'].'</td>';
											
										echo '</tr>';

									}
								?>
							

							
						</table>
					</div><!-- /.table-responsive -->
				</div><!-- /.box-body -->
			</div><!-- /.box -->
		</section><!-- /.Left col -->

	</div>

	
	<?php
	}
	?>
	
</section><!-- /.content -->