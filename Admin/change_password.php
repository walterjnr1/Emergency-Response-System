<?php 
include('../inc/email.php'); 
include('../inc/config.php'); 

if (empty($_SESSION['user_id'])) {
  header("Location: ../user_login.php");
}

// Fetch user's email 
//$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT email, password FROM users WHERE id = ?");
$stmt->bind_param("s", $user_id);
$stmt->execute();
$stmt->bind_result($email, $password);
$stmt->fetch();
$stmt->close();

if (isset($_POST["btnchange"])) {

  $oldPassword = $_POST['txtoldpassword'];
  $newPassword = $_POST['txtnewpassword'];
  $confirmPassword = $_POST['txtconfirmpassword'];

  if ($oldPassword !== $password) {
    $_SESSION['error'] = "Old password is incorrect.";
  } elseif ($newPassword !== $confirmPassword) {
    $_SESSION['error'] = "New password and confirm password do not match.";
  } elseif (strlen($newPassword) < 8) {
    $_SESSION['error'] = "New password must be at least 8 characters long.";
  } else {
    $stmt = $conn->prepare("UPDATE users SET password = ? WHERE  id = ?");
    $stmt->bind_param("ss", $newPassword, $user_id);
    
    if ($stmt->execute()) {
      // Send new password via email
      $message = "
      <html>
      <head>
      <title>Password Update Notification | $app_name</title>
      </head>
      <body>
      <p><strong>Hello,</strong></p>
      <p>Your password has been changed successfully. Your new temporary password is:</p>
      <p><strong>$newPassword</strong></p>
      <p>Please change this password after your next login for security.</p>
      <p>If you did not request this change, please contact our support team immediately.</p>
      <p>Regards,</p>
      <p>$app_name Team</p>
      </body>
      </html>
      ";

      $subject = "Password Update Notification | $app_name";
      if (sendEmail($email, $subject, $message)) {

        //refresh for 2 seconds and redirect to another page
        header("Refresh: 2; url=../user_login.php");

        $_SESSION['success'] = 'Password changed successfully. New password sent to your email.';
      } else {
        $_SESSION['error'] = 'Password changed but could not send email notification.';
      }
    } else {
      $_SESSION['error'] = 'Problem changing password. Please try again.';
    }
    $stmt->close();
  }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php echo $app_name; ?> | Change Password</title>
  <?php include('partials/head.php') ;?>

</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <!-- Navbar -->
  <?php include('partials/navbar.php') ;?>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
  <?php include('partials/sidebar.php') ;?>
  </aside>


  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Change Password</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Change Password</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
      
        <div class="row">
          <!-- left column -->
          <div class="col-md-12">
            <!-- jquery validation -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">&nbsp;</h3>
                <h3 class="card-title">Change Password</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form  action="" method="POST" >
                <div class="card-body">
                  <div class="form-group">
                    <label for="exampleInputEmail1">Old Password</label>
                    <input type="password" name="txtoldpassword" class="form-control" id="exampleInputEmail1" value="<?php echo $_POST['txtoldpassword'] ?? ''; ?>">
                  </div>
                  <div class="form-group">
                    <label for="exampleInputPassword1">New Password</label>
                    <input type="password" name="txtnewpassword" class="form-control" id="exampleInputPassword1" value="<?php echo $_POST['txtnewpassword'] ?? ''; ?>">
                  </div>
                  <div class="form-group">
                    <label for="exampleInputEmail1">Confirm Password</label>
                    <input type="password" name="txtconfirmpassword" class="form-control" id="exampleInputEmail1" value="<?php echo $_POST['txtconfirmpassword'] ?? ''; ?>">
                  </div>
                  
                  
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                  <button type="submit" name="btnchange" class="btn btn-primary">Save Changes</button>
                </div>
              </form>
            </div>
            <!-- /.card -->
            </div>
                  </div>

        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <strong>  <?php include('partials/footer.php') ;?></strong>
    <div class="float-right d-none d-sm-inline-block">
    </div>
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<?php include('partials/bottom-script.php') ;?>

</body>
</html>
