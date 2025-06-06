<?php
include('../inc/config.php');
if (empty($_SESSION['user_id'])) {
  header("Location: ../user_login.php");
}

	 // for Block admin
if(isset($_GET['did']))
{
$did=intval($_GET['did']);

mysqli_query($conn,"update users set status='0' where id='$did'");

header("location: user_record.php");
}

// for unBlock admin
if(isset($_GET['eid']))
{
$eid=intval($_GET['eid']);

mysqli_query($conn,"update users set status='1' where id='$eid'");

header("location: user_record.php");
}

?>
