<?php
session_start();
include ('config/connection.php');
include ('config/encrypt.php');

$email = mysqli_real_escape_string($con, $_POST['email']);

$query = mysqli_query($con, "SELECT * FROM user_client WHERE email = '$email'");
$result = mysqli_num_rows($query);
$r = mysqli_fetch_assoc($query);
if( $result > 0 ){
	mysqli_query($con, "update user_client set forget_password='Y' where id_user='$r[id_user]'");
	//header('location:login.php');
	
	$key = "asdf$1011";
	$stringawal = "$r[id_user]";
	$id = base64_encrypt($stringawal,$key);
	
	//mail customer
	$from = 'itsupport@cesco-logistics.com';
	$to = "$email";
	
	require 'PHPMailer/PHPMailerAutoload.php';

	$mail = new PHPMailer;

	//$mail->SMTPDebug = 3;								// Enable verbose debug output

	$mail->isSMTP();									// Set mailer to use SMTP
	$mail->Host = 'mail.cesco-logistics.com ';			// Specify main and backup SMTP servers
	$mail->SMTPAuth = true;								// Enable SMTP authentication
	$mail->Username = 'itsupport@cesco-logistics.com';	// SMTP username
	$mail->Password = 'Email-Cesco2022!';						// SMTP password
	$mail->SMTPSecure = 'tls';							// Enable TLS encryption, `ssl` also accepted
	$mail->Port = 26;									// TCP port to connect to

	$mail->setFrom('itsupport@cesco-logistics.com', 'E-CESCO');
	$mail->addAddress(''.$to.'', 'Anwar');     // Add a recipient

	$mail->isHTML(true);                                  // Set email format to HTML

	$mail->Subject = 'Forgot password';
	$mail->Body    = '<html>
						<head>
						</head>
						<body>
							<table cellpadding="10">
								<tr><td>Klik <a href="http://cesco-logistics.com/e-cesco/forget-password.php?id='.$id.'" target="_blank">disini</a> untuk reset password E-CESCO Anda. atau copy link di bawah ini</td></tr>
								<tr><td>http://cesco-logistics.com/e-cesco/forget-password.php?id='.$id.'</td></tr>
							</table>
						</body>
					</html>';
	
	if(!$mail->send()){
		header("Location: forgot.php");
		$_SESSION['msg']="Email not sent";
	}else{
		header("Location: forgot.php");
		$_SESSION['msg']="Email has been sent, please check your inbox";
	}
	
}else{
	header('location:login.php');
};
?>