<?php
session_start();
include ('config/connection.php');
unset($_SESSION['id_user']);
unset($_SESSION['password']);
$useraktif = mysqli_query($con, 'select * from user where username="'.$_SESSION['username'].'"');
$u = mysqli_fetch_assoc($useraktif);
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>E-CESCO | Lockscreen</title>
		<link rel="shortcut icon" href="favicon.ico" />
		<!-- Tell the browser to be responsive to screen width -->
		<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
		<!-- Bootstrap 3.3.5 -->
		<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
		<!-- Font Awesome -->
		<link rel="stylesheet" href="plugins/font-awesome/css/font-awesome.min.css">
		<!-- Ionicons -->
		<link rel="stylesheet" href="plugins/ionicons/css/ionicons.min.css">
		<!-- Theme style -->
		<link rel="stylesheet" href="dist/css/AdminLTE.min.css">

		<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
			<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
			<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
	</head>
	 <body class="hold-transition lockscreen">
		<!-- Automatic element centering -->
		<div class="lockscreen-wrapper">
			<div class="lockscreen-logo">
				<a href="index2.html"><b>E-CESCO</b></a>
			</div>
		     <!-- User name -->
			<div class="lockscreen-name"><?php echo''.$u['namalengkap'].'';?></div>

			<!-- START LOCK SCREEN ITEM -->
			<div class="lockscreen-item">
				<!-- lockscreen image -->
				<div class="lockscreen-image">
				<?php
				if(!empty($u['foto'])){
					echo'<img src="dist/img/'.$u['foto'].'" alt="'.$u['namalengkap'].'">';
				}else{
					echo'<img src="dist/img/avatar.png" alt="'.$u['namalengkap'].'">';
				}
				?>
			</div>
			<!-- /.lockscreen-image -->

	        <!-- lockscreen credentials (contains the form) -->
		    <form class="lockscreen-credentials" action="login_action.php" method="post" autocomplete="off">
				<div class="input-group">
					<input type="hidden" name="username" value="<?php echo''.$u['username'].'';?>">
					<input type="password" name="password" class="form-control" placeholder="password">
					<div class="input-group-btn">
						<button class="btn"><i class="fa fa-arrow-right text-muted"></i></button>
					</div>
				</div>
			</form><!-- /.lockscreen credentials -->

		</div><!-- /.lockscreen-item -->
		<div class="help-block text-center">
			Enter your password to retrieve your session
		</div>
		<div class="text-center">
			<a href="index.php">Or sign in as a different user</a>
		</div>
		<div class="lockscreen-footer text-center">
			Copyright &copy; 2016 <b><a href="http://cesco-logistics.com" class="text-black">Cesco Logistics</a></b><br>
			All rights reserved
		</div>
	</div><!-- /.center -->

    <!-- jQuery 2.1.4 -->
    <script src="plugins/jQuery/jQuery-2.1.4.min.js"></script>
    <!-- Bootstrap 3.3.5 -->
    <script src="bootstrap/js/bootstrap.min.js"></script>
	</body>
</html>