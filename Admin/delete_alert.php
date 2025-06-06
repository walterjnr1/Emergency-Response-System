<?php
include('../inc/config.php');
if (empty($_SESSION['user_id'])) {
  header("Location: ../user_login.php");
}

$id= $_GET['id'];
$sql = "DELETE FROM emergency_alerts WHERE id=?";
$stmt= $dbh->prepare($sql);
$stmt->execute([$id]);

header("Location: alert_record.php");
 ?>
