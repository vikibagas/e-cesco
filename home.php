<!-- Content Header (Page header) -->
<section class="content-header">
	<h1>Dashboard <small>it all starts here</small></h1>
	<ol class="breadcrumb">
		<li class="active"><i class="fa fa-dashboard"></i> Dashboard</li>
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
					//$agent = mysqli_query($con, 'select id_kota_baru from cabang where id_cabang='.$u['id_cabang'].'');
					//$a = mysqli_fetch_assoc($agent);
					
					if($u['id_cabang']=='39'){
						$query = mysqli_query($con, "SELECT count(*) AS total FROM pengiriman WHERE   tanggal >= now()-interval 3 month and status !=7");
						$test = 1;
					}else{
						$query = mysqli_query($con, "SELECT count(*) AS total FROM pengiriman WHERE   tanggal >= now()-interval 3 month and status !=7 and id_cabang=$u[id_cabang]");
						$test = 2;
					}
					//echo "SELECT count(*) AS total FROM pengiriman WHERE   tanggal >= now()-interval 3 month and status !=7";
					$r = mysqli_fetch_assoc($query);
					echo $r['total'].$test;
					?>
					</h3>
					<p>Total Airwaybill Booking Last 3 Month </p>
                </div>
                <div class="icon">
					<i class="ion ion-bag"></i>
                </div>
                
			</div>
		</div><!-- ./col -->

		

		<div class="col-lg-3 col-xs-6">
			<!-- small box -->
			<div class="small-box bg-green">
				<div class="inner">
					<h3>
					<?php
					if($u['id_cabang']=='39'){
						$query = mysqli_query($con, "SELECT count(*) AS total FROM pengiriman WHERE pickup='Y' and status !=7 and tanggal >= now()-interval 3 month ");
					}else{
						$query = mysqli_query($con, "SELECT count(*) AS total FROM pengiriman WHERE pickup='Y' and status !=7 and tanggal >= now()-interval 3 month and pickup_by_agent=$u[id_cabang] ");
					}
					$r = mysqli_fetch_assoc($query);
					echo ''.$r['total'].'';
					?>
					</h3>
                  <p>Total Airwaybill Pickup Last 3 Month</p>
                </div>
                <div class="icon">
					<i class="ion ion-android-bicycle"></i>
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
					echo ''.$r['total'].'';
					?>
					</h3>
					<p>Total Airwaybill Delivery Last 3 Month</p>
				</div>
				<div class="icon">
					<i class="ion ion-plane"></i>
				</div>
				
			</div>
		</div><!-- ./col -->
		<div class="col-lg-3 col-xs-6">
			<!-- small box -->
			<div class="small-box bg-red">
				<div class="inner">
					<h3>
					<?php
					if($u['id_cabang']=='39'){
						$query = mysqli_query($con, "SELECT sum(ifnull(berat_aktual,berat_barang)) AS total FROM pengiriman where  tanggal >= now()-interval 3 month  and status !=7");
					}else{
						$query = mysqli_query($con, "SELECT sum(ifnull(berat_aktual,berat_barang)) AS total FROM pengiriman where  tanggal >= now()-interval 3 month  and status !=7 and (pickup_by_agent=$u[id_cabang] or delivery_by_agent=$u[id_cabang] )");
					}
					$r = mysqli_fetch_assoc($query);
					echo ''.$r['total'].'';
					?>
					</h3>
					<p>Total Kg Last 3 Month</p>
				</div>
				<div class="icon">
					<i class="ion ion-pie-graph"></i>
				</div>
				
			</div>
		</div><!-- ./col -->
	</div><!-- /.row -->

	<!-- Small boxes (Stat box) -->
	<div class="row">
		<div class="col-lg-3 col-xs-6">
			<!-- small box -->
			<div class="small-box bg-aqua">
				<div class="inner">
					<h3>
					<?php
					$awal  = date('Y-m').'-01';
					$akhir = date('Y-m').'-31';
					//$agent = mysqli_query($con, 'select id_kota_baru from cabang where id_cabang='.$u['id_cabang'].'');
					//$a = mysqli_fetch_assoc($agent);
					
					if($u['id_cabang']=='39'){
						$query = mysqli_query($con, "SELECT count(*) AS total FROM pengiriman WHERE tanggal between '$awal' and '$akhir' and status !=7 and status !=0 and status is not null ");
					}else{
						$query = mysqli_query($con, "SELECT count(*) AS total FROM pengiriman WHERE tanggal between '$awal' and '$akhir' and status !=7 and status !=0 and status is not null and id_cabang=$u[id_cabang]");
					}

					$r = mysqli_fetch_assoc($query);
					echo ''.$r['total'].'';
					?>
					</h3>
					<p>Total Airwaybill Booking This Month</p>
                </div>
                <div class="icon">
					<i class="ion ion-bag"></i>
                </div>
                <a href="main.php?module=bookinginv2" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a> 
                
			</div>
		</div><!-- ./col -->
		<?php
			//echo "SELECT count(*) AS total FROM pengiriman WHERE pickup='N' and tanggal between '$awal' and '$akhir'";
		?>
		
		<div class="col-lg-3 col-xs-6">
			<!-- small box -->
			<div class="small-box bg-green">
				<div class="inner">
					<h3>
					<?php
					if($u['id_cabang']=='39'){
						$query = mysqli_query($con, "SELECT count(*) AS total FROM pengiriman WHERE pickup='Y' and tanggal between '$awal' and '$akhir' and status !=7");
					}else{
						$query = mysqli_query($con, "SELECT count(*) AS total FROM pengiriman WHERE pickup='Y' and tanggal between '$awal' and '$akhir' and status !=7 and pickup_by_agent = $u[id_cabang]");
					}
					$r = mysqli_fetch_assoc($query);
					echo ''.$r['total'].'';
					?>
					</h3>
                  <p>Total Airwaybill Pickup This Month</p>
                </div>
                <div class="icon">
					<i class="ion ion-android-bicycle"></i>
                </div>
                <a href="main.php?module=pickup" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                
            </div>
		</div><!-- ./col -->
		<div class="col-lg-3 col-xs-6">
			<!-- small box -->
			<div class="small-box bg-yellow">
				<div class="inner">
					<h3>
					<?php
					if($u['id_cabang']=='39'){
						$query = mysqli_query($con, "SELECT count(*) AS total FROM pengiriman WHERE delivery='Y' and tanggal between '$awal' and '$akhir' and status !=7");
					}else{
						$query = mysqli_query($con, "SELECT count(*) AS total FROM pengiriman WHERE delivery='Y' and tanggal between '$awal' and '$akhir' and status !=7 and delivery_by_agent=$u[id_cabang]");
					}
					$r = mysqli_fetch_assoc($query);
					echo ''.$r['total'].'';
					?>
					</h3>
					<p>Total Airwaybill Delivery This Month</p>
				</div>
				<div class="icon">
					<i class="ion ion-plane"></i>
				</div>
				<a href="main.php?module=delivery" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
			</div>
		</div><!-- ./col -->
		<div class="col-lg-3 col-xs-6">
			<!-- small box -->
			<div class="small-box bg-red">
				<div class="inner">
					<h3>
					<?php
					if($u['id_cabang']=='39'){
						$query = mysqli_query($con, "SELECT sum(ifnull(berat_aktual,berat_barang)) AS total FROM pengiriman where  tanggal between '$awal' and '$akhir' and status !=7");
					}else{
						$query = mysqli_query($con, "SELECT sum(ifnull(berat_aktual,berat_barang)) AS total FROM pengiriman where  tanggal between '$awal' and '$akhir' and status !=7 and (pickup_by_agent=$u[id_cabang] or delivery_by_agent=$u[id_cabang])");
					}
					$r = mysqli_fetch_assoc($query);
					echo ''.$r['total'].'';
					?>
					</h3>
					<p>Total Kg This Month</p>
				</div>
				<div class="icon">
					<i class="ion ion-pie-graph"></i>
				</div>
				<a href="main.php?module=airwaybill" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
			</div>
		</div><!-- ./col -->
	</div><!-- /.row -->
	
	<?php
	if($u['level']=='admin' || $u['level']=='network' || $u['level']=='inbound' || $u['level']=='inbound' || $u['level']=='agent' || $u['level']=='kurir') {
	?> 
	<!-- Main row -->
	<div class="row">
		<!-- Left col -->
		<section class="col-lg-7 connectedSortable">
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
								<th>Customer</th>
								<th>AWB</th>
								<th>SERVICE</th>
							</tr>
							
								<?php 
									$query = mysqli_query($con,"select x_namakustomer(id_kustomer) as kustomer, airwaybill_kode, x_service(service) as pelayanan from pengiriman where status=1 order by id_pengiriman desc limit 5");
									while($q=mysqli_fetch_assoc($query)){
										echo '<tr>';
											echo '<td>'.$q['kustomer'].'</td>';
											echo '<td>'.$q['airwaybill_kode'].'</td>';
											echo '<td>'.$q['pelayanan'].'</td>';
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
					<h3 class="box-title">Latest Pickup</h3>
					<div class="box-tools pull-right">
						<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
						<button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
					</div>
				</div><!-- /.box-header -->
				<div class="box-body">
					<div class="table-responsive" id="latest-pickup">
						<table class="table no-margin">
							<tr>
								<th>Customer</th>
								<th>AWB</th>
								<th>SERVICE</th>
							</tr>
							
								<?php 
									$query = mysqli_query($con,"select x_namakustomer(id_kustomer) as kustomer, airwaybill_kode, x_service(service) as pelayanan from pengiriman where status=2 order by id_pengiriman desc limit 5");
									while($q=mysqli_fetch_assoc($query)){
										echo '<tr>';
											echo '<td>'.$q['kustomer'].'</td>';
											echo '<td>'.$q['airwaybill_kode'].'</td>';
											echo '<td>'.$q['pelayanan'].'</td>';
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
		<div class="col-lg-4 col-sm-6">
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
        <div class="col-md-8">
          <!-- MAP & BOX PANE -->
          <div class="box box-success">
            <div class="box-header with-border">
              <h3 class="box-title">THE MOST SHIPMENTS TO 5 PROVINCES, LAST 3 MONTH</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body no-padding">
              <div class="row">
                <div class="col-md-9 col-sm-8">
                  <div class="pad">
                    <!-- Map will be created here -->
                    <div id="vmap" style="height: 305px; width: 600px;"></div>
                  </div>
                </div>
                <div class="col-md-3 col-sm-4">
                  <div class="pad box-pane-right bg-yellow" style="min-height: 325px">
                    <div class="description-block margin-bottom">
                    	<h5 class="description-header">MOST SHIPMENTS</h5><br>
                      <?php 
                      	$data_map = mysqli_query($con,"SELECT id_provinsi_tujuan,x_province(id_provinsi_tujuan) as nama_prov_tujuan, count(id_provinsi_tujuan) as tujuan FROM pengiriman WHERE  status !=7 and   tanggal >= now()-interval 3 month GROUP by id_provinsi_tujuan ORDER BY count(id_provinsi_tujuan) DESC LIMIT 5");
                      	while($m = mysqli_fetch_assoc($data_map)) { echo '	
                      
		                    
		                    <span class="description-text">'.$m['nama_prov_tujuan'].'</span> = '.$m['tujuan'].' AWB <br>';
		                    
                  		}
					 ?>
                    </div>
                    <!-- /.description-block -->
                    
                    <!-- /.description-block -->
                  </div>
                </div>
                <!-- /.col -->
                
                <!-- /.col -->
              </div>
              <!-- /.row -->
            </div>
            <!-- /.box-body -->
          </div>
          
        </div>
        
	</div>

	
	<?php
	}
	?>
	<div class="row">
		<div class="col-lg-9">
		<!--	<script src="https://apps.elfsight.com/p/platform.js" defer></script>
<div class="elfsight-app-b4942616-408d-471b-85df-6ef144a22a05"></div-->

			<!--<div class="powr-instagram-feed" id="27861d3c_1596784105"></div><script src="https://www.powr.io/powr.js?platform=html"></script>-->
	
		</div>
		
	</div>

</section><!-- /.content -->