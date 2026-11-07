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
		<div class="col-lg-6 col-sm-6">
			<div class="box box-success">
				<div class="box-header with-border">
					<h3 class="box-title">SERVICE USAGE LAST 3 MONTH</h3>
					<div class="box-tools pull-right">
						<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
						<button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
					</div>
				</div><!-- /.box-header -->
				<div class="box-body">
					<!-- DONUT CHART -->
		            
		              <div class="card-body">
		                <canvas id="donutChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
		              </div>
		              <!-- /.card-body -->
		           
				</div>
			</div>
		</div>
		<div class="col-lg-6 col-xs-6">
			<!-- small box -->
			<div class="small-box bg-yellow">
				<div class="inner">
					<h3>
					<?php
					
						
					$query = mysqli_query($con,"select COUNT(id) as total  from user_client_history where via='mobile' ");
					
					$r = mysqli_fetch_assoc($query);
					echo $r['total'];
					?>
					</h3>
					<p>Mobile Apps</p>
				</div>
				<div class="icon">
					<i class="ion ion-android-phone-portrait"> </i> Login 
				</div>
				
			</div>
		</div><!-- ./col -->
	</div>


	
	
	<?php
	if($u['level']=='admin' || $u['level']=='network' || $u['level']=='inbound' || $u['level']=='inbound' || $u['level']=='agent' || $u['level']=='kurir') {
	?> 
	<!-- Main row -->
	<div class="row">
		<!-- Left col -->
		<section class="col-lg-7 connectedSortable">
			<div class="box box-info">
				<div class="box-header with-border">
					<h3 class="box-title">Latest Activity</h3>
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
								<th>Activity</th>
								
							</tr>
							
								<?php 
									$query = mysqli_query($con,"select x_userclient(user) as user,user as id_user,aktivitas,tanggal from user_mobile_activity order by id desc limit 10");
									while($q=mysqli_fetch_assoc($query)){
										echo '<tr>';
											echo '<td>'.($q['id_user'] != 0 ? $q['user'] : 'visitor').'</td>';
											echo '<td>'.$q['aktivitas'].'</td>';
											echo '<td>'.$q['tanggal'].'</td>';
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
									$query = $query = mysqli_query($con,"select x_userclient(id_user) as user,tanggal as waktu from user_client_history where via='mobile' order by id desc limit 10");
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
					<h3 class="box-title">Latest Booking</h3>
					<div class="box-tools pull-right">
						<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
						<button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
					</div>
				</div><!-- /.box-header -->
				<div class="box-body">
					<div class="table-responsive" id="latest-booking">
						<table class="table no-margin">
							<tr>
								<th>Airwaybill</th>
								<th>By</th>
								<th>Tanggal</th>
								
							</tr>
							
								<?php 
									$query = mysqli_query($con,"select airwaybill_kode,created_date,if(created_by is null,x_userclient(user_client),x_user(created_by)) as user, created_date from pengiriman where create_via='mobile' and status != 7 order by id_pengiriman desc limit 10");
									while($q=mysqli_fetch_assoc($query)){
										echo '<tr>';
											echo '<td>'.$q['airwaybill_kode'].'</td>';
											echo '<td>'.$q['user'].'</td>';
											
											echo '<td>'.$q['created_date'].'</td>';
											
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

