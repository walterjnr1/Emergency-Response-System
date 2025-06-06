<?php
include('database/connect2.php');
session_start();

if (isset($_POST['login'])) {
  $email = trim($_POST['email']);
  $password = trim($_POST['password']);

  $status = '1';
  $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? AND password = ? AND status = ?");
  $stmt->bind_param("sss", $email, $password, $status);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result && $result->num_rows === 1) {
    $row = $result->fetch_assoc();
    // Login success
    $_SESSION['user_id'] = $row['id'];
    $_SESSION['user_role'] = $row['role'];

    $_SESSION['user_name'] = $row['name'];
    $_SESSION['user_address'] = $row['address'];
    $_SESSION['caregiver_email'] = $row['email'];

    header("Location: Admin/index.php");
    //exit();
  } else {
    $error = "Invalid email or password.";
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
   <link rel="icon" type="image/x-icon" href="uploadImage/Logo/logo.png">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
  <div class="row justify-content-center">
  <div class="col-md-5">
    <div class="card">
    <div class="card-body">
      <h4 class="text-center">Login</h4>
      <?php if (isset($error)): ?>
      <div class="alert alert-danger"><?php echo $error; ?></div>
      <?php endif; ?>
      <form action="" method="post">
      <div class="mb-3">
        <label>Email</label>
        <input type="email" name="email" class="form-control" required>
      </div>
      <div class="mb-3">
        <label>Password</label>
        <input type="password" name="password" class="form-control" required>
      </div>
      <button name="login" class="btn btn-primary w-100">Login</button>
      <p class="mt-2 text-center">No account? <a href="register.php">Register</a></p>
      </form>
    </div>
    </div>
  </div>
  </div>
</div>

<p>
  <!-- Footer -->
</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>
  <?php  include 'inc/footer.php'; ?>
</p>
<p>&nbsp;</p>
<p>&nbsp; </p>
</body>
</html>