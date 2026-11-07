<?php
include ('config/connection.php');

$id_user = mysqli_real_escape_string($con, $_POST['id']);
$password = mysqli_real_escape_string($con, md5($_POST['password']));

$query = mysqli_query($con, "update user_client set password='$password', forget_password='N' where id_user='$id_user'");
header('location:index.php');
?>