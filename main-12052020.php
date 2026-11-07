<?php
session_start();
// Set header untuk tidak menyimpan cache
header("Cache-Control: no-cache, must-revalidate"); // HTTP 1.1
header("Pragma: no-cache"); // HTTP 1.0
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Tanggal waktu lampau
 
// Set header untuk menyimpan cache selama 30 hari (60 detik * 60 menit * 24 jam * 30 hari)
//header("Cache-Control: max-age=2592000");
//header("Cache-Control: max-age=120");

include ('config/connection.php');
include ('config/db_connect.php');
include ('config/library.php');
include ('config/encrypt.php');



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
	if(isset($_SESSION['loggedin_time']) and isset($_SESSION['id_user'])){  
		if(((time() - $_SESSION['loggedin_time']) > $login_session_duration)){ 
			return true; 
		}
	}
	return false;
}

if(empty($_SESSION['username']) AND empty($_SESSION['password'])){
	header('location:login.php');	
}else{
	if(isset($_SESSION['id_user'])){
		if(!isLoginSessionExpired()){
			$key = 'asdf$1011';
			$encrypt = $_SESSION['id_user'];
			$id = base64_decrypt($encrypt,$key);
			$useraktif = mysqli_query($con, 'select a.*, c.cabang_cesco from user a inner join cabang c on c.id_cabang = a.id_cabang  where id_user="'.$id.'"');
			$u = mysqli_fetch_assoc($useraktif);
			?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta http-equiv="Cache-control" content="public">
		<link rel="shortcut icon" href="favicon.ico" />

		<title>E-CESCO</title>
		<!-- Tell the browser to be responsive to screen width -->
		<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
		<!-- Bootstrap 3.3.5 -->
		<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
		<!-- Font Awesome 
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">-->
		<link rel="stylesheet" href="plugins/font-awesome/css/font-awesome.min.css">
		<!-- Ionicons 
		<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">-->
		<link rel="stylesheet" href="plugins/ionicons/css/ionicons.min.css">
		<!-- daterange picker -->
		<link rel="stylesheet" href="plugins/daterangepicker/daterangepicker-bs3.css">
		<!-- Bootstrap time Picker -->
	    <link rel="stylesheet" href="plugins/timepicker/bootstrap-timepicker.min.css">
		<!-- jQueryUI -->
		<link rel="stylesheet" href="plugins/jQueryUI/jquery-ui.min.css">
		<!-- iCheck for checkboxes and radio inputs -->
		<link rel="stylesheet" href="plugins/iCheck/all.css">
		<!-- Bootstrap time Picker -->
		<link rel="stylesheet" href="plugins/timepicker/bootstrap-timepicker.min.css">
		<!-- DataTables
		<link rel="stylesheet" type="text/css" href="plugins/DataTables/datatables.min.css"/>-->
		<link rel="stylesheet" href="plugins/datatables/dataTables.bootstrap.css">
		<link rel="stylesheet" href="plugins/datatables/extensions/Responsive/css/dataTables.responsive.css">
		<link rel="stylesheet" href="plugins/datatables/extensions/Select/css/select.dataTables.min.css"/>
		<link rel="stylesheet" href="plugins/datatables/extensions/TableTools/css/dataTables.tableTools.min.css"/>
		<link rel="stylesheet" href="plugins/datatables/extensions/Buttons/css/buttons.dataTables.min.css"/>
		<link rel="stylesheet" href="plugins/datatables/extensions/FixedColumns/css/dataTables.fixedColumns.min.css"/>
		<link href="plugins/x-editable/bootstrap3-editable/css/bootstrap-editable.css" rel="stylesheet">
		<!-- Select2 -->
		<link rel="stylesheet" href="plugins/select2/select2.min.css">
		<!-- Theme style -->
		<link rel="stylesheet" href="dist/css/AdminLTE.min.css">
		<!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
		<link rel="stylesheet" href="dist/css/skins/skin-blue.min.css">
	
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
							<!-- Messages: style can be found in dropdown.less-->
							<li class="dropdown messages-menu">
								<!--<a href="#" class="dropdown-toggle" data-toggle="dropdown">
									<i class="fa fa-envelope-o"></i>
									
								</a>
								<ul class="dropdown-menu">
									<!--<li class="header">You have 4 messages</li>-->
									<!--<li><!-- inner menu: contains the actual data -->
										<!--<ul class="menu">
											<li><!-- start message -->
												<!--<a href="#">
													<div class="pull-left">
														<img src="dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
													</div>
													<h4>
														Support Team
														<small><i class="fa fa-clock-o"></i> 5 mins</small>
													</h4>
													<p>Why not buy a new awesome theme?</p>
												</a>
											</li><!-- end message -->
										<!--</ul>
									</li>
									<li class="footer"><a href="#">See All Messages</a></li>
								</ul>-->
							</li>
							<!-- Notifications: style can be found in dropdown.less -->
							<li class="dropdown notifications-menu">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown" id="notification">
									<i class="fa fa-bell-o"></i>
									<span class="label label-warning" id="topurgentpui"></span>
								</a>
								<ul class="dropdown-menu">
									<li class="header">You have <span id="topurgentpui1"></span> notifications</li>
									<li>
										<!-- inner menu: contains the actual data -->
										<ul class="menu" id="latest-notification">
											<!--<li>
												<a href="#">
													<i class="fa fa-users text-aqua"></i> 5 new members joined today
												</a>
											</li>-->
										</ul>
									</li>
									<li class="footer"><a href="#">View all</a></li>
								</ul>
							</li>
							<!-- Tasks: style can be found in dropdown.less
							<li class="dropdown tasks-menu">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown">
									<i class="fa fa-flag-o"></i>
									<span class="label label-danger">9</span>
								</a>
								<ul class="dropdown-menu">
									<li class="header">You have 9 tasks</li>
									<li>
										
										<ul class="menu">
											<li>
												<a href="#">
													<h3>
														Design some buttons
														<small class="pull-right">20%</small>
													</h3>
													<div class="progress xs">
														<div class="progress-bar progress-bar-aqua" style="width: 20%" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
															<span class="sr-only">20% Complete</span>
														</div>
													</div>
												</a>
											</li>
										</ul>
									</li>
									<li class="footer">
										<a href="#">View all tasks</a>
									</li>
								</ul>
							</li> -->
							<!-- User Account: style can be found in dropdown.less -->
							<li class="dropdown user user-menu">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown">
								<?php
								if(!empty($u['foto'])){
									echo'<img src="dist/img/'.$u['foto'].'" class="user-image" alt="'.$u['namalengkap'].'">';
								}else{
									echo'<img src="dist/img/avatar.png" class="user-image" alt="'.$u['namalengkap'].'">';
								}
								?>
								<span class="hidden-xs"><?php echo''.$u['namalengkap'].'';?></span>
								</a>
								<ul class="dropdown-menu">
									<!-- User image -->
									<li class="user-header">
										<?php
										if(!empty($u['foto'])){
											echo'<img src="dist/img/'.$u['foto'].'" class="img-circle" alt="'.$u['namalengkap'].'">';
										}else{
											echo'<img src="dist/img/avatar.png" class="img-circle" alt="'.$u['namalengkap'].'">';
										}
										?>
										<p>
											<?php echo''.sentence_case($u['namalengkap']).'';?> - <?php echo''.sentence_case($u['level']).'';?>
											<small>Member since <?php echo''.tgl_short($u['tanggaldibuat']).$u['menu_tambahan'].''; ?>
												
												<?php
													if (strpos($u['menu_tambahan'], 'booking') !== false){
														echo 1;
													}
													else{
														echo 2;
													}
												?>

											</small>
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
								echo'<img src="dist/img/'.$u['foto'].'" class="img-circle" alt="'.$u['namalengkap'].'">';
							}else{
								echo'<img src="dist/img/avatar.png" class="img-circle" alt="'.$u['namalengkap'].'">';
							}
							?>
						</div>
						<div class="pull-left info">
							<p><?php echo''.$u['namalengkap'].'';?></p>
							<a href="#"><i class="fa fa-circle text-success"></i> Online</a>
						</div>
					</div>
					<!-- search form -->
					<form action="#" method="get" class="sidebar-form">
						<div class="input-group">
							<input type="text" name="q" class="form-control" placeholder="Search...">
							<span class="input-group-btn">
								<button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i></button>
							</span>
						</div>
					</form>
					<!-- /.search form -->
					<!-- sidebar menu: : style can be found in sidebar.less -->
					<?php include('menu.php'); ?>
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
				<strong>Copyright &copy; 2018 <a href="#">Cesco Logistics</a>.</strong> All rights reserved.
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
		<script src="plugins/jQuery/jQuery-2.1.4.min.js"></script>
		<!-- Bootstrap 3.3.5 -->
		<script src="bootstrap/js/bootstrap.min.js"></script>
		<!-- Select2 -->
		<script src="plugins/select2/select2.full.min.js"></script>
		<!-- date-range-picker -->
		<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.2/moment.min.js"></script>
		<script src="plugins/daterangepicker/daterangepicker.js"></script>
		<!-- bootstrap time picker -->
		<script src="plugins/timepicker/bootstrap-timepicker.min.js"></script>
		<!-- bootstrap time picker -->
		<script src="plugins/timepicker/bootstrap-timepicker.min.js"></script>
		<!-- DataTables -->
		<!--<script type="text/javascript" src="plugins/DataTables/datatables.min.js"></script>-->
		<script src="plugins/datatables/jquery.dataTables.min.js"></script>
		<script src="plugins/datatables/dataTables.bootstrap.min.js"></script>
		<script src="plugins/datatables/extensions/Responsive/js/dataTables.responsive.min.js"></script>
		<script type="text/javascript" src="plugins/datatables/extensions/Select/js/dataTables.select.min.js"></script>
		<script src="plugins/datatables/extensions/TableTools/js/dataTables.tableTools.js">	</script>
		<script src="plugins/datatables/extensions/Buttons/js/dataTables.buttons.min.js"></script>
		<script src="plugins/datatables/extensions/FixedColumns/js/dataTables.fixedColumns.min.js"></script>
		<script src="plugins/x-editable/bootstrap3-editable/js/bootstrap-editable.js"></script>
		<script src="plugins/datatables/extensions/Buttons/js/buttons.print.min.js"></script>
		
		<!-- SlimScroll -->
		<script src="plugins/slimScroll/jquery.slimscroll.min.js"></script>
		<!-- iCheck 1.0.1 -->
		<script src="plugins/iCheck/icheck.min.js"></script>
		<!-- jQuery Validation -->
		<script src="plugins/jqueryvalidation/jquery.validate.min.js"></script>
		<!-- jQueryUI -->
		<script src="plugins/jQueryUI/jquery-ui.min.js"></script>
		<!-- FastClick -->
		<script src="plugins/fastclick/fastclick.min.js"></script>
		<!-- AdminLTE App -->
		<script src="dist/js/app.min.js"></script>
		<!-- page script -->
		<script src="dist/js/tables.js"></script>
		<script src="dist/js/main.js"></script>
		<script src="dist/js/x-editable.js"></script>
		<script src="notif.js"></script>
		<script>
		jQuery(document).ready(function() {    
			TableManaged.init();
		});
		</script>
		<script>
		function notif(){
			$('#alertmsg1').addClass('in');
			timer = setTimeout("notif()",60000);
			setTimeout(function() {
				$('#alertmsg1').removeClass('in');
			}, 6000);
		}
		$(document).ready(function(){
			//notif();
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
		<script>
			$(document).ready(function() {
				setTimeout(function() {
					$(".pesan").fadeIn('slow');
				}, 500);
			});
			setTimeout(function() {
				$(".pesan").fadeOut('slow');
			}, 3000);

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