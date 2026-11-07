<?php
include ('config/connection.php');
include ('config/encrypt.php');

$id	= !empty($_GET['id']) ? $_GET['id'] : '';

if(empty($id)){
	header('location:login.php');
}else{
	$key = "asdf$1011";
	$encrypt = "$_GET[id]";
	$id = base64_decrypt($encrypt,$key);

	$query = mysqli_query($con, "select * from user_client where id_user='$id' and forget_password='Y'");
	$ketemu	= mysqli_num_rows($query);
	$r = mysqli_fetch_assoc($query);
	
	if($ketemu > 0){
?>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	<link rel="shortcut icon" href="favicon.ico" />
    <title>E-CESCO | Forgot password</title>
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
    <!-- iCheck -->
    <link rel="stylesheet" href="plugins/iCheck/square/blue.css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body class="hold-transition login-page">
    <div class="login-box">
      <div class="login-logo">
        <a href="#"><b>E-CESCO</b></a>
      </div><!-- /.login-logo -->
      <div class="login-box-body">
        <p class="login-box-msg">Enter your new password</p>
		<form action="resetpassword_action.php" method="post" autocomplete="off" id="form-forgotpassword">
          <div class="form-group has-feedback">
			<input type="hidden" name="id" value="<?php echo''.$r['id_user'].'';?>">
            <input type="password" name="password" id="password" class="form-control" placeholder="Password" required="required">
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
          </div>
		  <div class="row">
            <div class="col-xs-8">
              &nbsp;
            </div><!-- /.col -->
            <div class="col-xs-4">
              <button type="submit" class="btn btn-primary btn-block btn-flat">Submit</button>
            </div><!-- /.col -->
          </div>
        </form>		
      </div><!-- /.login-box-body -->
    </div><!-- /.login-box -->

    <!-- jQuery 2.1.4 -->
    <script src="plugins/jQuery/jQuery-2.1.4.min.js"></script>
    <!-- Bootstrap 3.3.5 -->
    <script src="bootstrap/js/bootstrap.min.js"></script>
  </body>
</html>
<?php
	}else{
		header('location:login.php');
	}	
}
?>