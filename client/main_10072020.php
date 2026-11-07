<?php
session_start();
include ('../config/connection.php');
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
			$useraktif = mysqli_query($con, 'select *,x_intercity(kota) as intercityasal from user_client, kustomer where kustomer.id_kustomer=user_client.id_kustomer and user_client.id_user="'.$id.'"');
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
		<!-- Select2 -->
		<link rel="stylesheet" href="../plugins/select2/select2.min.css">
		<!-- Theme style -->
		<link rel="stylesheet" href="../dist/css/AdminLTE.min.css">
		<!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
		<link rel="stylesheet" href="../dist/css/skins/skin-blue.min.css">
	
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
		</style>
	</head>
	<body class="hold-transition skin-blue sidebar-mini">
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
					
					<!-- /.search form -->
					<!-- sidebar menu: : style can be found in sidebar.less -->
					<ul class="sidebar-menu">
						<li class="header">MAIN NAVIGATION</li>
						<li><a href="?module=home"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
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
									<li class="treeview active">
										<a href="#"><i class="fa fa-circle-o"></i> <span>Invoice</span> <i class="fa fa-angle-left pull-right"></i></a>
										
									</li>
									<li class="treeview active">
										<a href="#"><i class="fa fa-circle-o"></i> <span>Payment</span> <i class="fa fa-angle-left pull-right"></i></a>
								
									</li>
								</ul>
							</li>';
						}else{
							echo'
							<li class="treeview">
								<a href="#"><i class="fa fa-print"></i> <span>Reports</span> <i class="fa fa-angle-left pull-right"></i></a>
								<ul class="treeview-menu">
									<li class="treeview">
										<a href="#"><i class="fa fa-circle-o"></i> <span>Invoice</span> <i class="fa fa-angle-left pull-right"></i></a>
									</li>
									<li class="treeview">
										<a href="#"><i class="fa fa-circle-o"></i> <span>Payment</span> <i class="fa fa-angle-left pull-right"></i></a>
									</li>
								</ul>
							</li>';
						}
						?>
					</ul>
				</section>
			<!-- /.sidebar -->
			</aside>

			<!-- =============================================== -->

			<!-- Content Wrapper. Contains page content -->
			<div class="content-wrapper">
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
		<!-- AdminLTE App -->
		<script src="../dist/js/app.min.js"></script>
		<!-- page script -->
		<script src="../dist/js/tables.js"></script>
		<script src="../dist/js/main.js"></script>
		<script>
			jQuery(document).ready(function() {    
				TableManaged.init();
			});
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