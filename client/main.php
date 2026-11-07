<?php
session_start();
include ('../config/connection.php');
require_once('../config/db_connect.php');
include ('../config/library.php');
include ('../config/encrypt.php');

//fungsi redirect
function akses_salah(){
	$pesan = "<center>Maaf Anda tidak berhak mengakses halaman ini</center>";
 	$pesan .= "<meta http-equiv='refresh' content='2;url=main.php?module=home'>";
	return $pesan;
}

//fungsi expired login session
function isLoginSessionExpired() {
	$login_session_duration = 6000; 
	$current_time = time(); 
	if(isset($_SESSION['loggedin_timex']) and isset($_SESSION['id_userx'])){  
		if(((time() - $_SESSION['loggedin_timex']) > $login_session_duration)){ 
			return true; 
		}
	}
	return false;
}

if(empty($_SESSION['usernamex']) AND empty($_SESSION['passwordx'])){
	header('location:../login.php');	
}else{
	if(isset($_SESSION['id_userx'])){
		if(!isLoginSessionExpired()){
			$key = 'asdf$1011';
			$encrypt = $_SESSION['id_userx'];
			$id = base64_decrypt($encrypt,$key);
			$useraktif = mysqli_query($con, 'select *,x_intercity(kota) as intercityasal,kustomer.warehouse as k_warehouse, user_client.warehouse as u_warehouse from user_client, kustomer where kustomer.id_kustomer=user_client.id_kustomer and user_client.id_user="'.$id.'"');
			$u = mysqli_fetch_assoc($useraktif);
			?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<link rel="shortcut icon" href="../favicon.ico" />
		<title>E-CESCO</title>
		<!-- Tell the browser to be responsive to screen width -->
		<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
		<!-- Bootstrap 3.3.5 -->
		<link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
		<!-- Font Awesome 
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">-->
		<link rel="stylesheet" href="../plugins/font-awesome/css/font-awesome.min.css">
		<!-- Ionicons 
		<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">-->
		<link rel="stylesheet" href="../plugins/ionicons/css/ionicons.min.css">
		<!-- daterange picker -->
		<link rel="stylesheet" href="../plugins/daterangepicker/daterangepicker-bs3.css">
		<!-- Bootstrap time Picker -->
	    <link rel="stylesheet" href="../plugins/timepicker/bootstrap-timepicker.min.css">
		<!-- jQueryUI -->
		<link rel="stylesheet" href="../plugins/jQueryUI/jquery-ui.min.css">
		<!-- iCheck for checkboxes and radio inputs -->
		<link rel="stylesheet" href="../plugins/iCheck/all.css">
		<!-- Bootstrap time Picker -->
		<link rel="stylesheet" href="../plugins/timepicker/bootstrap-timepicker.min.css">
		<!-- DataTables
		<link rel="stylesheet" type="text/css" href="plugins/DataTables/datatables.min.css"/>-->
		<link rel="stylesheet" href="../plugins/datatables/dataTables.bootstrap.css">
		<link rel="stylesheet" href="../plugins/datatables/extensions/Responsive/css/dataTables.responsive.css">
		<link rel="stylesheet" href="../plugins/datatables/extensions/Select/css/select.dataTables.min.css"/>
		<link rel="stylesheet" href="../plugins/datatables/extensions/TableTools/css/dataTables.tableTools.min.css"/>
		<link rel="stylesheet" href="../plugins/datatables/extensions/Buttons/css/buttons.dataTables.min.css"/>
		<link rel="stylesheet" href="../plugins/datatables/extensions/FixedColumns/css/dataTables.fixedColumns.min.css"/>
		<link href="../plugins/x-editable/bootstrap3-editable/css/bootstrap-editable.css" rel="stylesheet">
		<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css">
		<link href="../plugins/jvectormap/jqvmap.css" media="screen" rel="stylesheet" type="text/css"/>
		<!-- Select2 -->
		<link rel="stylesheet" href="../plugins/select2/select2.min.css">
		<!-- Theme style -->
		<link rel="stylesheet" href="../dist/css/AdminLTE.min.css">
		<!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
		<link rel="stylesheet" href="../dist/css/skins/skin-blue.min.css">
		
		<?php
			if ($_GET['module'] == 'home' || $_GET['module'] == 'report-warehouse'){
					?>
				<link rel="stylesheet" href="../dist/css/Chart.min.css">
		<?php
			}
		?>		
	
		<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
			<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
			<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
		
		<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
			<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
			<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
		<style>
		.flyover {
			left: 150%;
			overflow: hidden;
			position: fixed;
			width: 25%;
			opacity: 0.7;
			z-index: 1050;
			transition: left 0.6s ease-out 0s;
		}
		.flyover.in {
			left: 75%;
		}
		.flyover-bottom {
			bottom: 10px;
		}
		#download-mobile
                    {
                         right: 5px;
                          bottom: 0px;
                         position:fixed;
                         z-index: 9999;
                         width: 15%;
                         border-radius: 5px; 
                    }
		</style>
	</head>
	<body class="hold-transition skin-blue sidebar-mini">

		<a href="#" data-toggle="modal" data-target="#banner-modal"><img src="../dist/img/download-now.png" id="download-mobile"></a>
		<!-- Site wrapper -->
		<div class="wrapper">

			<header class="main-header">
				<!-- Logo -->
				<a href="#" class="logo">
					<!-- mini logo for sidebar mini 50x50 pixels -->
					<span class="logo-mini"><b>E</b>CL</span>
					<!-- logo for regular state and mobile devices -->
					<span class="logo-lg"><b>E-CESCO</b></span>
				</a>
				<!-- Header Navbar: style can be found in header.less -->
				<nav class="navbar navbar-static-top" role="navigation">
					<!-- Sidebar toggle button-->
					<a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</a>
					<div class="navbar-custom-menu">
						<ul class="nav navbar-nav">
							<li class="dropdown user user-menu">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown">
								<?php
								if(!empty($u['foto'])){
									echo'<img src="../dist/img/'.$u['foto'].'" class="user-image" alt="'.$u['fullname'].'">';
								}else{
									echo'<img src="../dist/img/avatar.png" class="user-image" alt="'.$u['fullname'].'">';
								}
								?>
								<span class="hidden-xs"><?php echo''.$u['fullname'].'';?></span>
								</a>
								<ul class="dropdown-menu">
									<!-- User image -->
									<li class="user-header">
										<?php
										if(!empty($u['foto'])){
											echo'<img src="../dist/img/'.$u['foto'].'" class="img-circle" alt="'.$u['fullname'].'">';
										}else{
											echo'<img src="../dist/img/avatar.png" class="img-circle" alt="'.$u['fullname'].'">';
										}
										?>
										<p>
											<?php echo''.sentence_case($u['fullname']).'';?> - <?php echo''.$u['kode_kustomer'].'';?>
											<small>Member since <?php echo''.tgl_short($u['created_date']).''; ?></small>
										</p>
									</li>
									<!-- Menu Footer-->
									<li class="user-footer">
										<div class="pull-left">
											<a href="?module=profile" class="btn btn-default btn-flat">Profile</a>
										</div>
										<div class="pull-right">
											<a href="logout.php" class="btn btn-default btn-flat">Sign out</a>
										</div>
									</li>
								</ul>
							</li>
						</ul>
					</div>
				</nav>
			</header>

			<!-- =============================================== -->

			<!-- Left side column. contains the sidebar -->
			<aside class="main-sidebar">
				<!-- sidebar: style can be found in sidebar.less -->
				<section class="sidebar">
					<!-- Sidebar user panel -->
					<div class="user-panel">
						<div class="pull-left image">
							<?php
							if(!empty($u['foto'])){
								echo'<img src="../dist/img/'.$u['foto'].'" class="img-circle" alt="'.$u['fullname'].'">';
							}else{
								echo'<img src="../dist/img/avatar.png" class="img-circle" alt="'.$u['fullname'].'">';
							}
							?>
						</div>
						<div class="pull-left info">
							<p><?php echo''.$u['fullname'].'';?></p>
							<a href="#"><i class="fa fa-circle text-success"></i> Online</a>
						</div>
					</div>
					<!-- search form -->
					<script language="JavaScript" type="text/JavaScript"> 
					var x=12;//nr characters 
					function submitT(t,f){ 
						if(t.value.length==x){ 
							f.submit() 
						} 
					}
					</script>
					<form action="#" method="post" class="sidebar-form">
						<div class="input-group">
							<input type="text" name="airwaybill" class="form-control" placeholder="Search Airwaybill..." onkeyup="submitT(this,this.form)">
							<span class="input-group-btn">
								<button type="submit" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i></button>
							</span>
						</div>
					</form>
					<!-- /.search form -->
					<!-- sidebar menu: : style can be found in sidebar.less -->
					<ul class="sidebar-menu">
						<li class="header">MAIN NAVIGATION</li>
						<li><a href="?module=home"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
						<!--<li><a href="?module=mail"><i class="fa fa-envelope"></i> <span>Mailbox</span></a></li>-->
						<?php
						if($_GET['module']=='booking' || $_GET['module']=='bookingin' || $_GET['module']=='bookinginv2'){
							echo'
							<li class="active">
								<a href="?module=bookinginv2"><i class="fa fa-desktop"></i> <span>Booking</span> </a>
							</li>';
						}else{
							echo'
							<li>
								<a href="?module=bookinginv2"><i class="fa fa-desktop"></i> <span>Booking</span> </a>
							</li>';
						}
						?>
						<?php
						if($_GET['module']=='airwaybill'){
							echo'
							<li class="active"><a href="?module=airwaybill"><i class="fa fa-paper-plane"></i> <span>Airwaybill</span></a></li>';
						}else{
							echo'
							<li><a href="?module=airwaybill"><i class="fa fa-paper-plane"></i> <span>Airwaybill</span></a></li>';
						}
						?>
						<?php
						if($_GET['module']=='report-airwaybill-by-date'){
							echo'
							<li class="treeview active">
								<a href="#"><i class="fa fa-print"></i> <span>Reports</span> <i class="fa fa-angle-left pull-right"></i></a>
								
								<ul class="treeview-menu">
								';

								/*	<li class="treeview active">
										<a href="#"><i class="fa fa-circle-o"></i> <span>Invoice</span> <i class="fa fa-angle-left pull-right"></i></a>
										<ul class="treeview-menu">
										     <li class="'.(($_GET['module']=='invoice-customer') ? 'active' : '').'"><a href="?module=invoice-customer"><i class="fa fa-circle-o"></i> Customer</a></li>                                           
                                        </ul>
										
									</li>*/
									echo '
									<li'; if ($_GET['module']=='report-airwaybill-by-date') echo ' class="active"'; echo '><a href="?module=report-airwaybill-by-date"><i class="fa fa-circle-o"></i> Report AWB</a></li>    
									
								</ul>
							</li>';
						}else{
							echo'
							<li class="treeview '; if ($_GET['module']=='invoice-customer') echo ' active'; echo '">
								<a href="#"><i class="fa fa-print"></i> <span>Reports</span> <i class="fa fa-angle-left pull-right"></i></a>
								<ul class="treeview-menu">';

								/*
									<li class="treeview '; if ($_GET['module']=='invoice-customer') echo ' active'; echo '">
										<a href="#"><i class="fa fa-circle-o"></i> <span>Invoice</span> <i class="fa fa-angle-left pull-right"></i></a>
										<ul class="treeview-menu">
										     <li'; if ($_GET['module']=='invoice-customer') echo ' class="active"'; echo '><a href="?module=invoice-customer"><i class="fa fa-circle-o"></i> Customer</a></li>                                           
                                        </ul>
									</li>*/
									
									echo '
									<li'; if ($_GET['module']=='report-airwaybill-by-date') echo ' class="active"'; echo '><a href="?module=report-airwaybill-by-date"><i class="fa fa-circle-o"></i> Report AWB</a></li>                                           
								</ul>
							</li>';
						}
						?>
						<?php
							if($u['k_warehouse'] == 'Y' && $u['u_warehouse'] == 'Y'){
						?>
							<li class="treeview <?php echo ($_GET['module'] == 'rak' || $_GET['module'] == 'barang' || $_GET['module'] == 'report-warehouse' || $_GET['module'] == 'warehouse-barang-nonaktif' || $_GET['module'] == 'pemakaian-rak' || $_GET['module'] == 'warehouse-destroy-barang' ? 'active' : ''); ?>">
								<a href="#"><i class="fa fa-clone"></i> <span>Warehouse</span> <i class="fa fa-angle-left pull-right"></i></a>
								<ul class="treeview-menu">
									
									<li class="<?php echo ($_GET['module'] == 'report-warehouse' ? 'active' : ''); ?>"><a href="?module=report-warehouse"><i class="fa fa-circle-o"></i> Report Warehouse</a></li>
									<li class="<?php echo ($_GET['module'] == 'warehouse-barang-nonaktif' ? 'active' : ''); ?>"><a href="?module=warehouse-barang-nonaktif"><i class="fa fa-circle-o"></i> Barang Non aktif</a></li>
									<?php if ($u['id_kustomer'] == 146){ ?>
										<li class="<?php echo ($_GET['module'] == 'pemakaian-rak' ? 'active' : ''); ?>"><a href="?module=pemakaian-rak"><i class="fa fa-circle-o"></i>Pemakaian Rak</a></li>
									<?php } ?>
								</ul>
							</li>
						<?php
							}
						?>
						<?php
							if($u['show_invoice'] == 'Y'){
						?>
							<li class="<?php echo ($_GET['module'] == 'invoice' ? 'active' : ''); ?>"><a href="?module=invoice"><i class="fa fa-money"></i> <span>Invoice</span></a></li>';
						<?php
							}
						?>
					</ul>

				</section>
			<!-- /.sidebar -->
			</aside>

			<!-- =============================================== -->

			<!-- Content Wrapper. Contains page content -->
			<div class="content-wrapper" style="padding-bottom: 50px;">
				<div id="mappp"></div>
				<!-- Content -->
				<?php include('content.php'); ?>
			</div><!-- /.content-wrapper -->

			<footer class="main-footer">
				<div class="pull-right hidden-xs">
					<b>Version</b> 2.0.0
				</div>
				<strong>Copyright &copy; 2016 <a href="#">Cesco Logistics</a>.</strong> All rights reserved.
			</footer>

		</div><!-- ./wrapper -->
		
		<!-- modal delete confirmation -->
		<div class="modal fade modal-danger" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						Confirmation ...
					</div>
					<div class="modal-body">
						Delete this record ...?
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
						<a class="btn btn-warning btn-ok">Delete</a>
					</div>
				</div>
			</div>
		</div><!-- ./modal delete confirmation -->

		<div id="banner-modal" class="modal fade" width="1000px">
               <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            <h4 class="modal-title">Download Now!</h4>
                        </div>

                        <a href="https://play.google.com/store/apps/details?id=com.cescologistics.cescologistics_app" target="_blank"><img src="../dist/img/promotion-banner.jpg" width="100%"></a>
                        <div class="modal-footer">
                            <div class="col-md-6 col-sm-6 no-padng">
                                <a href="https://play.google.com/store/apps/details?id=com.cescologistics.cescologistics_app" target="_blank"><img src="../dist/img/playstore.png" width="100%"></a>
                            </div>
                            <div class="col-md-6 col-sm-6 no-padng">
                                <a href="https://apps.apple.com/id/app/cesco-logistics/id1562678522" target="_blank"><img src="../dist/img/appstore.png" width="100%"></a>
                            </div>  

                            
                        </div>

                    </div>
                </div>
        </div>
		
		<!-- modal wrong access -->
		<div class="alert alert-warning alert-dismissable flyover flyover-bottom" id="alertmsg1">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
			<h4><i class="icon fa fa-warning"></i> Alert!</h4>
			Please check your notification.
		</div>

		<!-- jQuery 2.1.4 -->
		<script src="../plugins/jQuery/jQuery-2.1.4.min.js"></script>
		<!-- Bootstrap 3.3.5 -->
		<script src="../bootstrap/js/bootstrap.min.js"></script>
		<!-- Select2 -->
		<script src="../plugins/select2/select2.full.min.js"></script>
		<script src="../plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
		<!-- date-range-picker -->
		<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.2/moment.min.js"></script>
		<script src="../plugins/daterangepicker/daterangepicker.js"></script>
		<!-- bootstrap time picker -->
		<script src="../plugins/timepicker/bootstrap-timepicker.min.js"></script>
		<!-- bootstrap time picker -->
		<script src="../plugins/timepicker/bootstrap-timepicker.min.js"></script>
		<!-- DataTables -->
		<!--<script type="text/javascript" src="plugins/DataTables/datatables.min.js"></script>-->
		<script src="../plugins/datatables/jquery.dataTables.min.js"></script>
		<script src="../plugins/datatables/dataTables.bootstrap.min.js"></script>
		<script src="../plugins/datatables/extensions/Responsive/js/dataTables.responsive.min.js"></script>
		<script type="text/javascript" src="../plugins/datatables/extensions/Select/js/dataTables.select.min.js"></script>
		<script src="../plugins/datatables/extensions/TableTools/js/dataTables.tableTools.js"></script>
		<script src="../plugins/datatables/extensions/Buttons/js/dataTables.buttons.min.js"></script>
		<script src="../plugins/datatables/extensions/FixedColumns/js/dataTables.fixedColumns.min.js"></script>
		<script src="../plugins/x-editable/bootstrap3-editable/js/bootstrap-editable.js"></script>
		
		<!-- SlimScroll -->
		<script src="../plugins/slimScroll/jquery.slimscroll.min.js"></script>
		<!-- iCheck 1.0.1 -->
		<script src="../plugins/iCheck/icheck.min.js"></script>
		<!-- jQuery Validation -->
		<script src="../plugins/jqueryvalidation/jquery.validate.min.js"></script>
		<!-- jQueryUI -->
		<script src="../plugins/jQueryUI/jquery-ui.min.js"></script>
		<!-- FastClick -->
		<script src="../plugins/fastclick/fastclick.min.js"></script>
		
		
		<script type="text/javascript" src="../plugins/jvectormap/jquery.vmap.js"></script>
   		<script type="text/javascript" src="../plugins/jvectormap/maps/jquery.vmap.indonesia.js" charset="utf-8"></script>

		<?php
			if ($_GET['module'] == 'home'){
				$data_service = mysqli_query($con,"SELECT x_service(service) as nama_pelayanan, count(x_service(service)) as service FROM pengiriman WHERE  status !=7 and id_kustomer=$u[id_kustomer] and   tanggal >= now()-interval 3 month GROUP by service limit 5");

					$s = mysqli_query($con,"SELECT x_service(service) as nama_pelayanan FROM pengiriman WHERE  status !=7 and id_kustomer=$u[id_kustomer] and   tanggal >= now()-interval 3 month GROUP by service limit 5");

				?>
					<script src="../dist/js/Chart.min.js"></script>
					<script src="../plugins/simplemaps/countrymap.js"></script>
					<script src="../plugins/simplemaps/mapdata.js"></script>
					


					<script type="text/javascript">
			


						//-------------
					    //- DONUT CHART -
					    //-------------
					    // Get context with jQuery - using jQuery's .get() method.
					    var donutChartCanvas = $('#donutChart').get(0).getContext('2d')
					    var donutData        = {
					      labels: [<?php while($z = mysqli_fetch_assoc($s)) { echo ' "'.$z['nama_pelayanan'].'", '; } ?>
					      ],
					      datasets: [
					        {
					          data: [<?php while($p = mysqli_fetch_assoc($data_service)) { echo ' "'.$p['service'].'", '; } ?>],
					          backgroundColor : ['#f56954', '#00a65a', '#f39c12', '#00c0ef', '#3c8dbc', '#d2d6de'],
					        }
					      ]
					    }
					    var donutOptions     = {
					      maintainAspectRatio : false,
					      responsive : true,
					    }
					    //Create pie or douhnut chart
					    // You can switch between pie and douhnut using the method below.
					    var donutChart = new Chart(donutChartCanvas, {
					      type: 'doughnut',
					      data: donutData,
					      options: donutOptions      
					    })


					</script>
				<?php

				
			}


			if($_GET['module'] == 'pemakaian-rak' ){
                    

                 $query = "SELECT
    d.nama_departement,
    COUNT(DISTINCT wr.id) AS jumlah_rak
FROM warehouse_rak wr
INNER JOIN rak_barang rb
    ON rb.id_rak = wr.id
INNER JOIN warehouse_barang wb
    ON wb.id = rb.id_barang
INNER JOIN kustomer_departement d
    ON d.id = wb.departement
WHERE wr.penyewa = 146
  AND wr.id_departement IS NOT NULL
  AND wb.aktif = 'Y'
  AND wb.stok_terkini > 0
GROUP BY d.id, d.nama_departement

UNION ALL

/* 2. Rak tidak terpakai */
SELECT
    'Rak Tidak Terpakai' AS nama_departement,
    COUNT(*) AS jumlah_rak
FROM warehouse_rak wr
WHERE wr.penyewa = 146
  AND wr.id_departement IS NOT NULL
  AND NOT EXISTS (
      SELECT 1
      FROM rak_barang rb
      INNER JOIN warehouse_barang wb
          ON wb.id = rb.id_barang
      WHERE rb.id_rak = wr.id
        AND wb.aktif = 'Y'
        AND wb.stok_terkini > 0
  );";


				$data_departement = mysqli_query($con, $query);

				// 🔹 Olah data
				$departemen_data = [];
				$total_rak = 0;

				while($row = mysqli_fetch_assoc($data_departement)) {
				    $departemen_data[] = $row;
				    $total_rak += $row['jumlah_rak'];
				}


				 $total_rak;





                ?>
                <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
                
                
                    <?php
                $warna_departemen = array(
                    'GENERAL INSULIN'       => '#F56954',
                    'GENERAL GLP-1'         => '#00A65A',
                    'MEDICAL SND'           => '#F39C12',
                    'SALES'                 => '#00BFA5',
                    'MARKETING'             => '#FF5252',
                    'CMR MEDICAL'           => '#4CAF50',
                    'LCQ (LEGAL)'           => '#FFC107',
                    'CE'                    => '#E91E63',
                    'IT'                    => '#00BCD4',
                    'CENTRAL ADMIN (GA)'    => '#3C8DBC',
                    'FINANCE'               => '#9C27B0',
                    'P&O'                   => '#795548',
                    'SUPPLY CHAIN'          => '#FF9800',
                    'MANAGEMENT'            => '#607D8B',
                    'MAPA(PUBLIC AWARNESS)' => '#8E44AD',
                    'RAK TIDAK TERPAKAI'    => '#B0BEC5'
                );
                ?>

       <script>
            const ctx = document.getElementById('chartDepartemen');

            const labels = <?php
            echo json_encode(
                array_column($departemen_data, 'nama_departement')
            );
            ?>;

            const dataValues = <?php
            echo json_encode(
                array_map('intval', array_column($departemen_data, 'jumlah_rak'))
            );
            ?>;

            const colorMap = <?php
            echo json_encode($warna_departemen);
            ?>;

            const normalize = label =>
                String(label || '').trim().toUpperCase();

            const colors = labels.map(label => {
                const key = normalize(label);

                return colorMap[key] || '#7f8c8d';
            });

            new Chart(ctx, {
                type: 'doughnut',

                data: {
                    labels: labels,
                    datasets: [{
                        data: dataValues,
                        backgroundColor: colors,
                        borderColor: '#ffffff',
                        borderWidth: 2
                    }]
                },

                options: {
                    responsive: true,
                    maintainAspectRatio: false,

                    plugins: {
                        legend: {
                            position: 'right',

                            labels: {
                                generateLabels: function(chart) {
                                    const dataset = chart.data.datasets[0];
                                    const total = dataset.data.reduce(
                                        (a, b) => a + b, 0
                                    );

                                    return chart.data.labels.map((label, i) => {
                                        const value = Number(dataset.data[i]) || 0;
                                        const percent = total > 0
                                            ? (value / total * 100).toFixed(1)
                                            : '0.0';

                                        return {
                                            text: label + ' : ' + percent + '%',
                                            fillStyle: dataset.backgroundColor[i],
                                            strokeStyle: dataset.backgroundColor[i],
                                            hidden: chart.getDataVisibility(i) === false,
                                            index: i
                                        };
                                    });
                                }
                            }
                        },

                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const values = context.dataset.data;
                                    const total = values.reduce((a, b) => a + b, 0);
                                    const value = Number(context.parsed) || 0;

                                    const percent = total > 0
                                        ? (value / total * 100).toFixed(1)
                                        : '0.0';

                                    return context.label + ': ' + value +
                                        ' rak (' + percent + '%)';
                                }
                            }
                        },

                        datalabels: {
                            color: '#fff',

                            formatter: function(value, chartContext) {
                                const total = chartContext.chart.data.datasets[0]
                                    .data.reduce((a, b) => a + b, 0);

                                return total > 0
                                    ? (value / total * 100).toFixed(1) + '%'
                                    : '0.0%';
                            },

                            font: {
                                weight: 'bold',
                                size: 13
                            }
                        }
                    }
                },

                plugins: [ChartDataLabels]
            });
            </script>
                <?php
            }
		?>
		<!-- AdminLTE App -->
		<script src="../dist/js/app.min.js"></script>
		<!-- page script -->
		<script src="../dist/js/tables.js"></script>
		<script src="../dist/js/main.js"></script>
		<script src="../dist/js/x-editable.js"></script>

		<script>
		  $(function () {
		    //Add text editor
		    $("#compose-textarea-client").wysihtml5();
		  });
		</script>

		<?php 
			$data_map = mysqli_query($con,"SELECT id_provinsi_tujuan,x_province(id_provinsi_tujuan) as nama_prov_tujuan, count(id_provinsi_tujuan) as tujuan FROM pengiriman WHERE  status !=7 and id_kustomer=$u[id_kustomer] and   tanggal >= now()-interval 3 month GROUP by id_provinsi_tujuan ORDER BY count(id_provinsi_tujuan) DESC LIMIT 5");
		?>
		<script>
			jQuery(document).ready(function() {    
				TableManaged.init();

				 jQuery('#vmap').vectorMap({
			          map: 'indonesia_id',
			          enableZoom: false,
			          showTooltip: true,
			          selectedRegions : [<?php while($m = mysqli_fetch_assoc($data_map)) { echo ' "PATH'.$m['id_provinsi_tujuan'].'", '; } ?>],
			          
					  

					  

			          onRegionClick: function(event, code, region){
			           /* var message = 'You clicked "'
				            + region
				            + '" which has the code: '
				            + code.toUpperCase();

				        alert(message);*/
				        event.preventDefault();
			          }
			        });


				 	var r = 0;
		            $(document).on("click", ".putar", function() {
		                var tipe = $(this).attr("data-tipe");
		                if (tipe == "kanan") {
		                    r = r + 90;
		                    if (r > 360) {
		                        r = 90;
		                    }
		                } else {
		                    r = r - 90;
		                    if (r < 0) {
		                        r = 270;
		                    }
		                }
		                $("#image1").css("transform", "rotate(" + r + "deg)");
		            });
				});

			$(function(){
  $('#world-map-markers').vectorMap({
    map: 'world_mill',
    scaleColors: ['#C8EEFF', '#0071A4'],
    normalizeFunction: 'polynomial',
    hoverOpacity: 0.7,
    hoverColor: false,
    markerStyle: {
      initial: {
        fill: '#F8E23B',
        stroke: '#383f47'
      }
    },
    backgroundColor: '#383f47',
    markers: [
      {latLng: [41.90, 12.45], name: 'Vatican City'},
      {latLng: [43.73, 7.41], name: 'Monaco'},
      {latLng: [-0.52, 166.93], name: 'Nauru'},
      {latLng: [-8.51, 179.21], name: 'Tuvalu'},
      {latLng: [43.93, 12.46], name: 'San Marino'},
      {latLng: [47.14, 9.52], name: 'Liechtenstein'},
      {latLng: [7.11, 171.06], name: 'Marshall Islands'},
      {latLng: [17.3, -62.73], name: 'Saint Kitts and Nevis'},
      {latLng: [3.2, 73.22], name: 'Maldives'},
      {latLng: [35.88, 14.5], name: 'Malta'},
      {latLng: [12.05, -61.75], name: 'Grenada'},
      {latLng: [13.16, -61.23], name: 'Saint Vincent and the Grenadines'},
      {latLng: [13.16, -59.55], name: 'Barbados'},
      {latLng: [17.11, -61.85], name: 'Antigua and Barbuda'},
      {latLng: [-4.61, 55.45], name: 'Seychelles'},
      {latLng: [7.35, 134.46], name: 'Palau'},
      {latLng: [42.5, 1.51], name: 'Andorra'},
      {latLng: [14.01, -60.98], name: 'Saint Lucia'},
      {latLng: [6.91, 158.18], name: 'Federated States of Micronesia'},
      {latLng: [1.3, 103.8], name: 'Singapore'},
      {latLng: [1.46, 173.03], name: 'Kiribati'},
      {latLng: [-21.13, -175.2], name: 'Tonga'},
      {latLng: [15.3, -61.38], name: 'Dominica'},
      {latLng: [-20.2, 57.5], name: 'Mauritius'},
      {latLng: [26.02, 50.55], name: 'Bahrain'},
      {latLng: [0.33, 6.73], name: 'São Tomé and Príncipe'}
    ]
  });
});
		</script>
        <script>
            //
            // Pipelining function for DataTables. To be used to the `ajax` option of DataTables
            //
            $.fn.dataTable.pipeline = function ( opts ) {
                // Configuration options
                var conf = $.extend( {
                    pages: 5,     // number of pages to cache
                    url: '',      // script url
                    data: null,   // function or object with parameters to send to the server
                    // matching how `ajax.data` works in DataTables
                    method: 'GET' // Ajax HTTP method
                }, opts );

                // Private variables for storing the cache
                var cacheLower = -1;
                var cacheUpper = null;
                var cacheLastRequest = null;
                var cacheLastJson = null;

                return function ( request, drawCallback, settings ) {
                    var ajax          = false;
                    var requestStart  = request.start;
                    var drawStart     = request.start;
                    var requestLength = request.length;
                    var requestEnd    = requestStart + requestLength;

                    if ( settings.clearCache ) {
                        // API requested that the cache be cleared
                        ajax = true;
                        settings.clearCache = false;
                    }
                    else if ( cacheLower < 0 || requestStart < cacheLower || requestEnd > cacheUpper ) {
                        // outside cached data - need to make a request
                        ajax = true;
                    }
                    else if ( JSON.stringify( request.order )   !== JSON.stringify( cacheLastRequest.order ) ||
                        JSON.stringify( request.columns ) !== JSON.stringify( cacheLastRequest.columns ) ||
                        JSON.stringify( request.search )  !== JSON.stringify( cacheLastRequest.search )
                    ) {
                        // properties changed (ordering, columns, searching)
                        ajax = true;
                    }

                    // Store the request for checking next time around
                    cacheLastRequest = $.extend( true, {}, request );

                    if ( ajax ) {
                        // Need data from the server
                        if ( requestStart < cacheLower ) {
                            requestStart = requestStart - (requestLength*(conf.pages-1));

                            if ( requestStart < 0 ) {
                                requestStart = 0;
                            }
                        }

                        cacheLower = requestStart;
                        cacheUpper = requestStart + (requestLength * conf.pages);

                        request.start = requestStart;
                        request.length = requestLength*conf.pages;

                        // Provide the same `data` options as DataTables.
                        if ( $.isFunction ( conf.data ) ) {
                            // As a function it is executed with the data object as an arg
                            // for manipulation. If an object is returned, it is used as the
                            // data object to submit
                            var d = conf.data( request );
                            if ( d ) {
                                $.extend( request, d );
                            }
                        }
                        else if ( $.isPlainObject( conf.data ) ) {
                            // As an object, the data given extends the default
                            $.extend( request, conf.data );
                        }

                        settings.jqXHR = $.ajax( {
                            "type":     conf.method,
                            "url":      conf.url,
                            "data":     request,
                            "dataType": "json",
                            "cache":    false,
                            "success":  function ( json ) {
                                cacheLastJson = $.extend(true, {}, json);

                                if ( cacheLower != drawStart ) {
                                    json.data.splice( 0, drawStart-cacheLower );
                                }
                                if ( requestLength >= -1 ) {
                                    json.data.splice( requestLength, json.data.length );
                                }

                                drawCallback( json );
                            }
                        } );
                    }
                    else {
                        json = $.extend( true, {}, cacheLastJson );
                        json.draw = request.draw; // Update the echo for each response
                        json.data.splice( 0, requestStart-cacheLower );
                        json.data.splice( requestLength, json.data.length );

                        drawCallback(json);
                    }
                }
            };

            // Register an API method that will empty the pipelined data, forcing an Ajax
            // fetch on the next draw (i.e. `table.clearPipeline().draw()`)
            $.fn.dataTable.Api.register( 'clearPipeline()', function () {
                return this.iterator( 'table', function ( settings ) {
                    settings.clearCache = true;
                } );
            } );
        </script>
		
	</body>
</html>
			<?php
		}else{
			header("Location:lockscreen.php");
		}
	}
}
?>