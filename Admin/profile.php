<?php 
include('../inc/config.php');
if (empty($_SESSION['user_id'])) {
  header("Location: ../user_login.php");
}

// Get current user info
$user_id = $_SESSION['user_id'];
$stmt_user = $dbh->prepare("SELECT * FROM users WHERE id = :user_id");
$stmt_user->bindParam(':user_id', $user_id);
$stmt_user->execute();
$row_user = $stmt_user->fetch(PDO::FETCH_ASSOC);

// Profile update (name, email, etc.)
if (isset($_POST['btnedit'])) {
    $name  = $_POST['txtname'];
    $email = $_POST['txtemail'];
    $role  = $_POST['cmdrole'];
    $phone = $_POST['txtphone'];
    $address = $_POST['txtaddress'];


    // Check if email already exists for another user
    $checkEmail = $dbh->prepare("SELECT * FROM users WHERE email = :email AND id != :user_id");
    $checkEmail->bindParam(':email', $email);
    $checkEmail->bindParam(':user_id', $user_id);
    $checkEmail->execute();

    if ($checkEmail->rowCount() > 0) {
        $_SESSION['error']='The email address is already in use by another account. Please use a different one.';
    } else {
        $stmt = $dbh->prepare("UPDATE users SET name = :name, email = :email, role = :role, phone = :phone,address = :address WHERE id = :user_id");
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':role', $role);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':user_id', $user_id);

        if ($stmt->execute()) {
            $_SESSION['success']='Profile updated successfully.';
            header("refresh:2; url=profile.php");
        } else {
            $_SESSION['error']='Error updating profile.';
        }
    }
}

// Profile image update
if (isset($_POST['btneditphoto'])) {
    if (isset($_FILES['userphoto']) && $_FILES['userphoto']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $file_name = $_FILES['userphoto']['name'];
        $file_tmp = $_FILES['userphoto']['tmp_name'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        if (in_array($file_ext, $allowed)) {
            $new_name = 'uploadImage/Profile/user_' . $user_id . '_' . time() . '.' . $file_ext;
            if (move_uploaded_file($file_tmp, '../' . $new_name)) {
                // Update DB
                $stmt = $dbh->prepare("UPDATE users SET photo = :photo WHERE id = :user_id");
                $stmt->bindParam(':photo', $new_name);
                $stmt->bindParam(':user_id', $user_id);
                if ($stmt->execute()) {
                    $_SESSION['success'] = 'Profile image updated successfully.';
                    // Optionally delete old image file here
                    $row_user['photo'] = $new_name;
                    header("refresh:2; url=profile.php");
                } else {
                    $_SESSION['error'] = 'Error updating profile image.';
                }
            } else {
                $_SESSION['error'] = 'Failed to upload image.';
            }
        } else {
            $_SESSION['error'] = 'Invalid image format. Allowed: jpg, jpeg, png, gif.';
        }
    } else {
        $_SESSION['error'] = 'Please select an image to upload.';
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php echo $app_name; ?> | My Profile</title>
  <?php include('partials/head.php'); ?>
  <style>
    .edit-photo-icon {
      position: absolute;
      top: 60px;
      left: 60px;
      background: #fff;
      border-radius: 50%;
      padding: 4px;
      cursor: pointer;
      box-shadow: 0 2px 6px rgba(0,0,0,0.2);
    }
    .profile-photo-wrapper {
      position: relative;
      display: inline-block;
    }
    .edit-photo-icon i {
      font-size: 18px;
      color: #007bff;
    }
  </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <!-- Navbar -->
  <?php include('partials/navbar.php'); ?>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <?php include('partials/sidebar.php'); ?>
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>My Profile</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">My Profile</li>
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
            <!-- Profile Card -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">User Profile</h3>
              </div>
              <!-- /.card-header -->
              <!-- Profile details -->
              <div class="card-body">
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group profile-photo-wrapper">
                      <img src="../<?php echo htmlspecialchars($row_user['photo']); ?>" width="80" height="80" style="object-fit:cover;border-radius:50%;" alt="Profile Photo"/>
                      <span class="edit-photo-icon" data-bs-toggle="modal" data-bs-target="#editPhotoModal" title="Edit Photo">
                        <i class="fas fa-edit"></i>
                      </span>
                    </div>
                    <div class="form-group">
                      <label for="txtname">Full Name</label>
                      <input type="text" class="form-control" id="txtname" value="<?php echo htmlspecialchars($row_user['name']); ?>" disabled>
                    </div>
                    <div class="form-group">
                      <label for="txtrole">Role</label>
                      <input type="text" class="form-control" id="txtrole" value="<?php echo htmlspecialchars($row_user['role']); ?>" disabled>
                    </div>
                    <div class="form-group">
                      <label for="txtemail">Email</label>
                      <input type="email" class="form-control" id="txtemail" value="<?php echo htmlspecialchars($row_user['email']); ?>" disabled>
                    </div>
                    <div class="form-group">
                      <label for="txtphone">Phone</label>
                      <input type="tel" class="form-control" id="txtphone" value="<?php echo htmlspecialchars($row_user['phone']); ?>" disabled>
                    </div>
                    <div class="form-group">
                      <label for="txtaddress">Address</label>
                      <input type="text" class="form-control" id="txtaddress" value="<?php echo htmlspecialchars($row_user['address']); ?>" disabled>
                    </div>
                  </div>
                </div>
              </div>
              <!-- /.card-body -->

              <!-- Profile Edit Button -->
              <div class="card-footer">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editProfileModal">Edit Profile</button>
              </div>
            </div>
          </div>
          <!-- /.card -->
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->

  </div>
  <!-- /.content-wrapper -->

  <!-- Modal for Editing Profile -->
  <div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <form method="POST" action="">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="editProfileModalLabel">Edit Profile</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <label for="txtname">Full Name</label>
              <input type="text" name="txtname" class="form-control" id="txtname" value="<?php echo htmlspecialchars($row_user['name']); ?>" required>
            </div>
            <div class="form-group">
              <label for="txtemail">Email</label>
              <input type="email" name="txtemail" class="form-control" id="txtemail" value="<?php echo htmlspecialchars($row_user['email']); ?>" required>
            </div>
            <div class="form-group">
              <label for="cmdrole">Role</label>
              <input type="text" name="cmdrole" class="form-control" id="cmdrole" value="<?php echo htmlspecialchars($row_user['role']); ?>" required>
            </div>
            <div class="form-group">
              <label for="txtphone">Phone</label>
              <input type="tel" name="txtphone" class="form-control" id="txtphone" value="<?php echo htmlspecialchars($row_user['phone']); ?>" required>
            </div>
            <div class="form-group">
              <label for="txtphone">Address</label>
              <input type="text" name="txtaddress" class="form-control" id="txtphone" value="<?php echo htmlspecialchars($row_user['address']); ?>" required>
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" name="btnedit" class="btn btn-primary">Save Changes</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          </div>
        </div>
      </form>
    </div>
  </div>

  <!-- Modal for Editing Photo -->
  <div class="modal fade" id="editPhotoModal" tabindex="-1" aria-labelledby="editPhotoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <form method="POST" action="" enctype="multipart/form-data">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="editPhotoModalLabel">Edit Profile Image</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <label for="userphoto">Choose New Image</label>
              <input type="file" name="userphoto" class="form-control" id="userphoto" accept="image/*" required>
              <small class="form-text text-muted">Allowed formats: jpg, jpeg, png, gif.</small>
            </div>
            <div class="form-group mt-2">
              <img id="previewImg" src="#" alt="Preview" style="display:none;max-width:100px;max-height:100px;border-radius:50%;"/>
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" name="btneditphoto" class="btn btn-primary">Save Image</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          </div>
        </div>
      </form>
    </div>
  </div>

  <!-- Footer -->
  <footer class="main-footer">
    <strong><?php include('partials/footer.php'); ?></strong>
  </footer>

</div>
<!-- ./wrapper -->

<!-- Scripts -->
<?php include('partials/bottom-script.php'); ?>
<script>
  // Preview selected image in modal
  document.getElementById('userphoto')?.addEventListener('change', function(e) {
    const [file] = this.files;
    if (file) {
      const preview = document.getElementById('previewImg');
      preview.src = URL.createObjectURL(file);
      preview.style.display = 'block';
    }
  });
</script>
<!-- FontAwesome for edit icon -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
</body>
</html>
