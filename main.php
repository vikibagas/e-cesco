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
                <link href="plugins/jvectormap/jqvmap.css" media="screen" rel="stylesheet" type="text/css"/>
                <!-- Select2 -->
                <link rel="stylesheet" href="plugins/select2/select2.min.css">
                <!-- Theme style -->
                <link rel="stylesheet" href="dist/css/AdminLTE.min.css">

                <link rel="stylesheet" href="dist/css/tracking.css">
                <!-- AdminLTE Skins. Choose a skin from the css/skins
                 folder instead of downloading all of them to reduce the load. -->
                <link rel="stylesheet" href="dist/css/skins/skin-blue.min.css">
                <?php
                    include ('config/css_include.php');
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

                    .tanggal_max_min {
                      z-index: 1600 !important; /* has to be larger than 1050 */
                    }
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
                    .warning-finance{
                        white-space: normal !important;
                    }

                    .hide-notif{
                        visibility: hidden;
                    }

                    #processing-modal{
                        z-index: 10000;
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

            <a href="#" data-toggle="modal" data-target="#banner-modal"><img src="dist/img/download-now.png" id="download-mobile"></a>
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

                                <?php
                                $key    = 'asdf$1011';
                                $level	= $_SESSION['level'];
                                $id		= $_SESSION['id_user'];
                                $idUser = base64_decrypt($id,$key);
                                $userFin = array(5,8,9,77);

                                $module = !empty($_GET['module']) ? $_GET['module'] : '';
                                ?>
                                <!-- Notifications: style can be found in dropdown.less -->
                                <li class="dropdown notifications-menu">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" id="notification">
                                        <i class="fa fa-bell-o"></i>
                                        <span class="label label-warning alert-notif" id="topurgentpui"><?php echo (in_array($idUser,$userFin) || $level == 'finance' || $module == 'invoice-agent') ? '*' : '';?></span>
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li class="header">You have <span id="topurgentpui1"></span> notifications</li>
                                        <li>
                                            <!-- inner menu: contains the actual data -->
                                            <ul class="menu" id="latest-notification">
                                                <?php if (in_array($idUser,$userFin) || $level == 'finance' ){?>
                                                    <li class="alert-notif">
                                                        <a href="#" class="warning-finance">
                                                            <i class="fa fa-warning text-red"></i> SEMUA AWB 100% BIAYANYA HARUS TERISI SEBELUM TGL 02 DI AWAL BULAN & SETIAP HARI SENIN UNTUK MINGGU SEBELUMNYA
                                                        </a>
                                                    </li>
                                                <?php } ?>

                                                <?php if ($module == 'invoice-agent'){?>
                                                    <li>
                                                        <a href="#" class="warning-finance">
                                                            <i class="fa fa-warning text-red"></i> LOCK INVOICE SEBELUM TGL 5 AWAL BULAN DAN YG SUDAH DI LCOCK TIDAK BISA DIRUBAH KECUALI OLEH ADMIN
                                                        </a>
                                                    </li>
                                                <?php } ?>
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
                                                echo '<img src="dist/img/'.$u['foto'].'" class="img-circle" alt="'.($u['namalengkap'] ?? '').'">';
                                            }else{
                                                echo '<img src="dist/img/avatar.png" class="img-circle" alt="'.($u['namalengkap'] ?? '').'">';
                                            }
                                            ?>
                                            <p>
                                                <?php echo sentence_case($u['namalengkap'] ?? ''); ?> - <?php echo sentence_case($u['level'] ?? ''); ?>
                                                <small>Member since <?php echo tgl_short($u['tanggaldibuat'] ?? '').($u['menu_tambahan'] ?? ''); ?>

                                                    <?php
                                                    // Diubah menggunakan type cast (string) agar tidak warning jika nilainya NULL
                                                    $menu_tambahan = (string)($u['menu_tambahan'] ?? '');
                                                    
                                                    if (strpos($menu_tambahan, 'booking') !== false){
                                                        echo 1;
                                                    } else {
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

                        <!-- /.search form -->
                        <!-- sidebar menu: : style can be found in sidebar.less -->
                        <?php include('menu.php'); ?>
                    </section>
                    <!-- /.sidebar -->
                </aside>

                <!-- =============================================== -->

                <!-- Content Wrapper. Contains page content -->
                <div class="content-wrapper" style="padding-bottom: 50px;">
                    <!-- Content -->
                    <?php include('content.php'); ?>
                </div><!-- /.content-wrapper -->



                <footer class="main-footer" >
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

            <div id="banner-modal" class="modal fade" width="1000px">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            <h4 class="modal-title">Download Now!</h4>
                        </div>

                        <a href="https://play.google.com/store/apps/details?id=com.cescologistics.cescologistics_app" target="_blank"><img src="dist/img/promotion-banner.jpg" width="100%"></a>
                        <div class="modal-footer">
                            <div class="col-md-6 col-sm-6 no-padng">
                                <a href="https://play.google.com/store/apps/details?id=com.cescologistics.cescologistics_app" target="_blank"><img src="dist/img/playstore.png" width="100%"></a>
                            </div>
                            <div class="col-md-6 col-sm-6 no-padng">
                                <a href="https://apps.apple.com/id/app/cesco-logistics/id1562678522" target="_blank"><img src="dist/img/appstore.png" width="100%"></a>
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
            <script src="plugins/jQuery/jQuery-2.1.4.min.js"></script>
            <script src="plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
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
            <script src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
            <script src="plugins/datatables/extensions/FixedColumns/js/dataTables.fixedColumns.min.js"></script>
            <script src="plugins/x-editable/bootstrap3-editable/js/bootstrap-editable.js"></script>
            <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js" charset="utf-8"></script>
            <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js" charset="utf-8"></script>
            <script src="plugins/datatables/extensions/Buttons/js/buttons.print.min.js"></script>
            <script type="text/javascript" src="plugins/jvectormap/jquery.vmap.js"></script>
            <script type="text/javascript" src="plugins/jvectormap/maps/jquery.vmap.indonesia.js" charset="utf-8"></script>



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
            <script src="dist/js/compare.js"></script>
            <script src="dist/js/profit-loss.js"></script>
            <script src="dist/js/belum-tertagih.js"></script>
            <script src="dist/js/inv-customer.js"></script>
            <script src="dist/js/inv-agent.js"></script>
            <script src="dist/js/finance.js"></script>
            <script src="dist/js/invoice-or.js"></script>

            <script src="notif.js"></script>

            <?php
            $data_map = mysqli_query($con,"SELECT id_provinsi_tujuan,x_province(id_provinsi_tujuan) as nama_prov_tujuan, count(id_provinsi_tujuan) as tujuan FROM pengiriman WHERE  status !=7  and   tanggal >= now()-interval 3 month GROUP by id_provinsi_tujuan ORDER BY count(id_provinsi_tujuan) DESC LIMIT 5");
            ?>

            <script>
                $(document).ready(function() {
                    if(sessionStorage.getItem('#banner-modal')!=='true')
                    {
                        $('#banner-modal').modal('show');
                        sessionStorage.setItem('#banner-modal',true);
                    }
                });

            </script>

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

                });
            </script>


            <?php
            if ($_GET['module'] == 'home' ){
                $data_service = mysqli_query($con,"SELECT x_service(service) as nama_pelayanan, count(x_service(service)) as service FROM pengiriman WHERE  status !=7  and   tanggal >= now()-interval 3 month GROUP by service  ORDER by count(x_service(service))  DESC limit 5");

                $s = mysqli_query($con,"SELECT x_service(service) as nama_pelayanan FROM pengiriman WHERE  status !=7  and   tanggal >= now()-interval 3 month GROUP by service   ORDER by count(x_service(service))  DESC limit 5");

                ?>
                <script src="dist/js/Chart.min.js"></script>

                


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
            ?>

            <?php
            if ($_GET['module'] == 'traffic-mobile' ){
                $data_aktivitas = mysqli_query($con,"SELECT aktivitas , count(aktivitas) as jumlah_aktivitas FROM user_mobile_activity    GROUP by aktivitas  ORDER by count(aktivitas)  DESC limit 5");

                $s = mysqli_query($con,"SELECT count(aktivitas) as jumlah_aktivitas FROM user_mobile_activity    GROUP by aktivitas  ORDER by count(aktivitas)  DESC limit 5");

                ?>
                <script src="dist/js/Chart.min.js"></script>

                


                <script type="text/javascript">



                    //-------------
                    //- DONUT CHART -
                    //-------------
                    // Get context with jQuery - using jQuery's .get() method.
                    var donutChartCanvas = $('#donutChart').get(0).getContext('2d')
                    var donutData        = {
                        labels: [<?php while($z = mysqli_fetch_assoc($data_aktivitas)) { echo ' "'.$z['aktivitas'].'", '; } ?>
                        ],
                        datasets: [
                            {
                                data: [<?php while($p = mysqli_fetch_assoc($s)) { echo ' "'.$p['jumlah_aktivitas'].'", '; } ?>
                        ],
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
            ?>

           <?php

           if($_GET['module'] == 'pemakaian-rak' ){
                    

         /*           $data_departement = mysqli_query($con,"SELECT 
                x_namadepartement(departement) AS nama_departement, 
                COUNT(DISTINCT rb.id_rak) AS jumlah_rak
            FROM warehouse_barang wb
            LEFT JOIN rak_barang rb ON wb.id = rb.id_barang
            WHERE wb.stok_terkini > 0 
              AND wb.departement IS NOT NULL 
              AND wb.aktif = 'Y'
            GROUP BY wb.departement

                        "); 

                    $data_departement2 = mysqli_query($con,"SELECT 
                x_namadepartement(departement) AS nama_departement, 
                COUNT(DISTINCT rb.id_rak) AS jumlah_rak
            FROM warehouse_barang wb
            LEFT JOIN rak_barang rb ON wb.id = rb.id_barang
            WHERE wb.stok_terkini > 0 
              AND wb.departement IS NOT NULL 
              AND wb.aktif = 'Y'
            GROUP BY wb.departement

                        "); */

                    // 🔹 Ambil data departemen dan jumlah rak
/*$query = "
    SELECT 
        d.id AS id_departement,
        d.nama_departement,
        COALESCE(COUNT(DISTINCT rb.id_rak), 0) AS jumlah_rak
    FROM kustomer_departement d
    LEFT JOIN warehouse_barang wb 
        ON wb.departement = d.id AND wb.aktif = 'Y'
    LEFT JOIN rak_barang rb 
        ON wb.id = rb.id_barang
    GROUP BY d.id, d.nama_departement
    ORDER BY d.nama_departement
";*/

 /*$query = "
    SELECT 
    d.id AS id_departement,
    d.nama_departement,
    COUNT(DISTINCT rb.id_rak) AS jumlah_rak
FROM kustomer_departement d
LEFT JOIN warehouse_barang wb 
    ON wb.departement = d.id 
    AND wb.aktif = 'Y'
LEFT JOIN rak_barang rb 
    ON wb.id = rb.id_barang
LEFT JOIN warehouse_rak wr 
    ON rb.id_rak = wr.id
WHERE wr.penyewa = 146
  AND wr.id_departement IS NOT NULL
GROUP BY d.id, d.nama_departement
HAVING jumlah_rak > 0
ORDER BY d.nama_departement";*/

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

                
                <script type="text/javascript">
                /*    Chart.register(ChartDataLabels);
                 
                        var donutChartCanvas2 = $('#donutChartt').get(0).getContext('2d')
                        var donutData2 = {
                            labels: [<?php //while($z = mysqli_fetch_assoc($data_departement)) { echo ' "'.$z['nama_departement'].'", '; } ?>],
                            datasets: [{
                                data: [<?php //while($o = mysqli_fetch_assoc($data_departement2)) { echo $o['jumlah_rak'].','; } ?>],
                                backgroundColor: [ '#f56954', '#00a65a', '#f39c12', '#00c0ef', '#3c8dbc', '#d2d6de',
                                '#8e44ad', '#1abc9c', '#34495e', '#e67e22', '#2ecc71', '#c0392b',
                                '#9b59b6', '#e74c3c', '#7f8c8d'],
                            }]
                        }
                        var donutOptions2 = {
                            maintainAspectRatio: false,
                            responsive: true,
                        }
                        var donutChart2 = new Chart(donutChartCanvas2, {
                            type: 'doughnut',
                            data: donutData2,
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                legend: {
                                    position: 'top'
                                },
                                tooltips: {
                                    callbacks: {
                                        label: function(tooltipItem, data) {
                                            var dataset = data.datasets[tooltipItem.datasetIndex];
                                            var label = data.labels[tooltipItem.index] || '';
                                            var value = dataset.data[tooltipItem.index] || 0;
                                            return label + ': ' + value + ' rak';
                                        }
                                    }
                                },
                                plugins: {
                                    datalabels: {
                                        color: '#fff',
                                        font: {
                                            weight: 'bold',
                                            size: 10
                                        },
                                        formatter: function(value) {
                                            return value + ' rak';
                                        }
                                    }
                                }
                            },
                            plugins: [ChartDataLabels]
                        })*/
                </script>
                <?php
            }
            ?>


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
            </script>

            <script>
                $(function () {
                    //Add text editor
                    $("#compose-textarea").wysihtml5();
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
