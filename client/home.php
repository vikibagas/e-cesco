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
					if ($u['customer_admin'] == 1){
						$query = mysqli_query($con, "SELECT count(*) AS total FROM pengiriman WHERE  status !=7 and id_kustomer='".$u['id_kustomer']."' and   tanggal >= now()-interval 3 month");
					}
					else{
						$query = mysqli_query($con, "SELECT count(*) AS total FROM pengiriman WHERE   id_kustomer='".$u['id_kustomer']."' and   tanggal >= now()-interval 3 month ");
					}
					$r = mysqli_fetch_assoc($query);
					echo ''.$r['total'].'';
					?>
					</h3>
					<p>Total Airwaybill Booking Last 3 Month</p>
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
					$query = mysqli_query($con, "SELECT count(*) AS total FROM pengiriman WHERE pickup='Y' and id_kustomer='".$u['id_kustomer']."' and status != 7 and   tanggal >= now()-interval 3 month ");
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
					$query = mysqli_query($con, "SELECT count(*) AS total FROM pengiriman WHERE delivery='Y' and id_kustomer='".$u['id_kustomer']."' and status != 7 and   tanggal >= now()-interval 3 month");
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
					$query = mysqli_query($con, "SELECT sum(ifnull(berat_aktual,berat_barang)) AS total FROM pengiriman where id_kustomer='".$u['id_kustomer']."' and status != 7 and   tanggal >= now()-interval 3 month");
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
					$agent = mysqli_query($con, 'select id_kota_baru from cabang where id_cabang='.$u['id_cabang'].'');
					$a = mysqli_fetch_assoc($agent);
					
					if ($u['customer_admin'] == 1){
						$query = mysqli_query($con, "SELECT count(*) AS total FROM pengiriman WHERE  status !=7 and id_kustomer='".$u['id_kustomer']."' and   tanggal between '$awal' and '$akhir'");
					}
					else{
						$query = mysqli_query($con, "SELECT count(*) AS total FROM pengiriman WHERE   id_kustomer='".$u['id_kustomer']."' and   tanggal between '$awal' and '$akhir' ");
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
					$query = mysqli_query($con, "SELECT count(*) AS total FROM pengiriman WHERE pickup='Y' and id_kustomer='".$u['id_kustomer']."' and status != 7 and   tanggal between '$awal' and '$akhir'");
					$r = mysqli_fetch_assoc($query);
					echo ''.$r['total'].'';
					?>
					</h3>
                  <p>Total Airwaybill Pickup This Month</p>
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
					$query = mysqli_query($con, "SELECT count(*) AS total FROM pengiriman WHERE delivery='Y' and id_kustomer='".$u['id_kustomer']."' and status != 7 and   tanggal between '$awal' and '$akhir'");
					$r = mysqli_fetch_assoc($query);
					echo ''.$r['total'].'';
					?>
					</h3>
					<p>Total Airwaybill Delivery This Month</p>
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
					$query = mysqli_query($con, "SELECT sum(ifnull(berat_aktual,berat_barang)) AS total FROM pengiriman where id_kustomer='".$u['id_kustomer']."' and status != 7  and   tanggal between '$awal' and '$akhir'");
					$r = mysqli_fetch_assoc($query);
					echo ''.$r['total'].'';
					?>
					</h3>
					<p>Total Kg This Month</p>
				</div>
				<div class="icon">
					<i class="ion ion-pie-graph"></i>
				</div>
				
			</div>
		</div><!-- ./col -->
	</div><!-- /.row -->
	
	<?php
	
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
									$query = mysqli_query($con,"select x_namakustomer(id_kustomer) as kustomer, airwaybill_kode, x_service(service) as pelayanan from pengiriman where status=1 and id_kustomer='".$u['id_kustomer']."' order by id_pengiriman desc limit 5");
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
									$query = mysqli_query($con,"select x_namakustomer(id_kustomer) as kustomer, airwaybill_kode, x_service(service) as pelayanan from pengiriman where status=2 and id_kustomer='".$u['id_kustomer']."' order by id_pengiriman desc limit 5");
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
		<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
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
        <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12">
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
            <div class="box-body " >
              <div class="row">
               
                  <div class="pad" >
                    <!-- Map will be created here -->
                    <div id="vmap" style="width: 100%; min-height: 225px;max-height: 225px; "></div>
                  </div>
               
                
                <!-- /.col -->
                
                <!-- /.col -->
              </div>
              <!-- /.row -->
            </div>
            <!-- /.box-body -->
          </div>
          
        </div>

        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
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
            <div class="box-body "  >
              <div class="row" >
               
                 <div class="pad box-pane-right bg-yellow" style="min-height: 225px;max-height: 225px; margin-left: 10px ; margin-right: 10px ;">
                    <div class="description-block margin-bottom">
                    	<h5 class="description-header">MOST SHIPMENTS</h5><br>
                      <?php 
                      	$data_map = mysqli_query($con,"SELECT id_provinsi_tujuan,x_province(id_provinsi_tujuan) as nama_prov_tujuan, count(id_provinsi_tujuan) as tujuan FROM pengiriman WHERE  status !=7 and id_kustomer=$u[id_kustomer] and   tanggal >= now()-interval 3 month GROUP by id_provinsi_tujuan ORDER BY count(id_provinsi_tujuan) DESC LIMIT 5");
                      	while($m = mysqli_fetch_assoc($data_map)) { echo '	
                      
		                    
		                    <span class="description-text">'.$m['nama_prov_tujuan'].'</span> = '.$m['tujuan'].' AWB <br>';
		                    
                  		}
					 ?>
                    </div>
                    <!-- /.description-block -->
                    
                    <!-- /.description-block -->
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
	<div id="mappp"></div>
	
	<!--<script src="https://apps.elfsight.com/p/platform.js" defer></script>
<div class="elfsight-app-b4942616-408d-471b-85df-6ef144a22a05"></div>-->
	<?php
	
	?>

</section><!-- /.content -->