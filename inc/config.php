<?php 
session_start();
error_reporting(1);
include('../database/connect.php'); 
include('../database/connect2.php'); 

//set time
date_default_timezone_set('Africa/Accra');
$current_date = date('Y-m-d H:i:s');

// Define the current month and year
$current_month = date('m');
$current_year = date('Y');

$app_name = 'Emergency response System';
$app_logo = 'uploadImage/Logo/logo.png';
$app_email = 'support@Emergency-response.com';


//fetch user data
$user_id = $_SESSION["user_id"];
$stmt = $dbh->query("SELECT * FROM users where id='$user_id'");
$row_user = $stmt->fetch();
$role = $row_user['role'];


//no of admin
$stmt = $dbh->query("SELECT COUNT(*) as total_admin FROM users where role='admin'");
$total_admins = $stmt->fetch();

//no of elder
$stmt = $dbh->query("SELECT COUNT(*) as total_elderly FROM users where role='elderly'");
$total_elderly = $stmt->fetch();

//no of caregiver
$stmt = $dbh->query("SELECT COUNT(*) as total_caregiver FROM users where role='caregiver'");
$total_caregivers = $stmt->fetch();


//no of alert_pending
$stmt = $dbh->query("SELECT COUNT(*) as total_alert_pending FROM emergency_alerts where elderly_id='$user_id' AND status='Pending'");
$total_alert = $stmt->fetch();

//no of alert
$stmt = $dbh->query("SELECT COUNT(*) as total_alert FROM emergency_alerts where elderly_id='$user_id'");
$total_alerts = $stmt->fetch();
?>