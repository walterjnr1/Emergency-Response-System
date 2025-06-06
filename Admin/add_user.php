<?php 
include('../inc/email.php'); 
include('../inc/config.php');

if (empty($_SESSION['user_id'])) {
  header("Location: ../user_login.php");

}

if (isset($_POST['btnadd'])) {
    // Sanitize inputs
    $email = mysqli_real_escape_string($conn, $_POST['txtemail']);
    $password = $_POST['txtpassword'];
    $role = mysqli_real_escape_string($conn, $_POST['cmdrole']);
    $caregiver_id = mysqli_real_escape_string($conn, $_POST['cmdcaregiver']);
    $name = mysqli_real_escape_string($conn, $_POST['txtname']);
        $phone = mysqli_real_escape_string($conn, $_POST['txtphone']);


    // Check required fields
    if (empty($email) || empty($password) || empty($role) || empty($name) || empty($phone)) {
        $_SESSION['error'] = "All fields are required!";
    } elseif (strlen($password) < 9) {
        $_SESSION['error'] = "Password must be more than 8 characters!";
    } else {
        $query = "SELECT * FROM users WHERE email = '$email'";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) > 0) {
            $_SESSION['error'] = "Email already exists!";
        } else {
     
            $query = "INSERT INTO users (name, email,phone, password, role,caregiver_id)VALUES ('$name', '$email','$phone', '$password', '$role','$caregiver_id')";
            $result = mysqli_query($conn, $query);
            if ($result) {
           

                // Send email notification
                $subject = "User Registration notification";
                $message = "
<html>
<head>
<title>User Registration notification | $app_name</title>
</head>
<body>

<p>Hello $name  ,</p>

<p>Thank you for signing up with <strong>$app_name</strong>. We're excited to have you on board.</p>
<p>You can now log in using your email address: <strong>$email</strong> , <br>Password: <strong>$password</strong></p>

<p>If you have any questions or need support, feel free to contact us anytime.</p>

<p>Best regards,<br>The $app_name Team</p>
<hr>
<small>This is an automated message. Please do not reply directly to this email.</small>
</body>
</html>
";

                if (sendEmail($email, $subject, $message)) {
                    $_SESSION['success'] = "$role Account created successfully!";
                } else {
                    $_SESSION['error'] = "Email could not be sent, but user was created.";
                }
            } else {
                $_SESSION['error'] = "Database error: Could not Save user.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php echo $app_name; ?> | Add USer</title>
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
            <h1>New User</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">New User</li>
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
                <h3 class="card-title">New User</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form action="" method="POST">
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="exampleInputEmail1">Full Name</label>
                    <input type="text" name="txtname" class="form-control" id="exampleInputEmail1" value="<?php echo $_POST['txtname'] ?? ''; ?>">
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail1">Password</label>
                    <input type="password" name="txtpassword" class="form-control" id="exampleInputEmail1" value="<?php echo $_POST['txtpassword'] ?? ''; ?>">
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail1">Phone</label>
                    <input type="tel" name="txtphone" class="form-control" id="exampleInputEmail1" value="<?php echo $_POST['txtphone'] ?? ''; ?>">
                </div>
           
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="exampleInputEmail1">Role</label>
                    <select id="cmdrole" name="cmdrole" class="form-control">
                        <option value="" <?php echo $_POST['cmdrole'] == '' ? 'selected' : ''; ?>>-- Select Role --</option>
                        <option value="elderly">elderly</option>
                         <option value="caregiver">caregiver</option>
                    </select>
                </div>
                <div class="form-group">
                  <label for="exampleInputEmail1">Caregivers</label>
                  <select id="cmdcaregiver" name="cmdcaregiver" class="form-control">
                    <option value="" <?php echo (isset($_POST['cmdcaregiver']) && $_POST['cmdcaregiver'] == '') ? 'selected' : ''; ?>>-- Select caregiver --</option>
                    <?php
                    $caregivers = mysqli_query($conn, "SELECT id, name FROM users WHERE role='caregiver'");
                    while ($row = mysqli_fetch_assoc($caregivers)) {
                      $selected = (isset($_POST['cmdcaregiver']) && $_POST['cmdcaregiver'] == $row['id']) ? 'selected' : '';
                      echo "<option value=\"{$row['id']}\" $selected>{$row['name']}</option>";
                    }
                    ?>
                  </select>
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail1">Email</label>
                    <input type="email" name="txtemail" class="form-control" id="exampleInputEmail1" value="<?php echo $_POST['txtemail'] ?? ''; ?>">
                </div>
            </div>
        </div>
    </div>
    <!-- /.card-body -->
    <div class="card-footer">
        <button type="submit" name="btnadd" class="btn btn-primary">Save</button>
    </div>
</form>
            </div>
            <!-- /.card -->
            </div>
          <!--/.col (left) -->
          <!-- right column -->
          <div class="col-md-6">

          </div>
          <!--/.col (right) -->
        </div>

         
            <!-- /.card -->
            </div>
          <!--/.col (left) -->
          <!-- right column -->
          <div class="col-md-6">

          </div>
          <!--/.col (right) -->
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
