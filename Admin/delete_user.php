<?php
include('../inc/config.php');
if (empty($_SESSION['user_id'])) {
  header("Location: ../user_login.php");
}

$id= $_GET['id'];
$sql = "DELETE FROM users WHERE id=?";
$stmt= $dbh->prepare($sql);
$stmt->execute([$id]);

header("Location: user_record.php");
 ?>
