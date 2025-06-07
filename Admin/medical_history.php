<?php 
include('../inc/config.php');
if (empty($_SESSION['user_id'])) {
  header("Location: ../user_login.php");
  exit;
}

// Get current user info
$user_id = $_SESSION['user_id'];
$stmt_user = $dbh->prepare("SELECT * FROM users WHERE id = :user_id");
$stmt_user->bindParam(':user_id', $user_id);
$stmt_user->execute();
$row_user = $stmt_user->fetch(PDO::FETCH_ASSOC);

// Get or create medical profile
$stmt_med = $dbh->prepare("SELECT * FROM medical_profiles WHERE user_id = :user_id");
$stmt_med->bindParam(':user_id', $user_id);
$stmt_med->execute();
$row_med = $stmt_med->fetch(PDO::FETCH_ASSOC);

if (!$row_med) {
    // Insert empty profile if not exists
    $stmt_insert = $dbh->prepare("INSERT INTO medical_profiles (user_id, age, conditions, medications, allergies) VALUES (:user_id, '', '', '', '')");
    $stmt_insert->bindParam(':user_id', $user_id);
    $stmt_insert->execute();
    $stmt_med = $dbh->prepare("SELECT * FROM medical_profiles WHERE user_id = :user_id");
    $stmt_med->bindParam(':user_id', $user_id);
    $stmt_med->execute();
    $row_med = $stmt_med->fetch(PDO::FETCH_ASSOC);
}

// Medical profile update
if (isset($_POST['btnedit'])) {
    $age        = $_POST['txtage'];
    $conditions = $_POST['txtconditions'];
    $medications= $_POST['txtmedications'];
    $allergies  = $_POST['txtallergies'];

    $stmt = $dbh->prepare("UPDATE medical_profiles SET age = :age, conditions = :conditions, medications = :medications, allergies = :allergies WHERE user_id = :user_id");
    $stmt->bindParam(':age', $age);
    $stmt->bindParam(':conditions', $conditions);
    $stmt->bindParam(':medications', $medications);
    $stmt->bindParam(':allergies', $allergies);
    $stmt->bindParam(':user_id', $user_id);

    if ($stmt->execute()) {
        $_SESSION['success']='Medical history updated successfully.';
        header("refresh:2; url=medical_history.php");
        // Refresh $row_med
        $stmt_med = $dbh->prepare("SELECT * FROM medical_profiles WHERE user_id = :user_id");
        $stmt_med->bindParam(':user_id', $user_id);
        $stmt_med->execute();
        $row_med = $stmt_med->fetch(PDO::FETCH_ASSOC);
    } else {
        $_SESSION['error']='Error updating medical history.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php echo $app_name; ?> | Medical History</title>
  <?php include('partials/head.php'); ?>
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
            <h1>Medical History</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Medical History</li>
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
            <!-- Medical History Card -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">My Medical Profile</h3>
              </div>
              <!-- /.card-header -->
              <!-- Medical details -->
              <div class="card-body">
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label for="txtage">Age</label>
                      <input type="number" class="form-control" id="txtage" value="<?php echo htmlspecialchars($row_med['age']); ?>" disabled>
                    </div>
                    <div class="form-group">
                      <label for="txtconditions">Medical Conditions</label>
                      <textarea class="form-control" id="txtconditions" rows="2" disabled><?php echo htmlspecialchars($row_med['conditions']); ?></textarea>
                    </div>
                    <div class="form-group">
                      <label for="txtmedications">Medications</label>
                      <textarea class="form-control" id="txtmedications" rows="2" disabled><?php echo htmlspecialchars($row_med['medications']); ?></textarea>
                    </div>
                    <div class="form-group">
                      <label for="txtallergies">Allergies</label>
                      <textarea class="form-control" id="txtallergies" rows="2" disabled><?php echo htmlspecialchars($row_med['allergies']); ?></textarea>
                    </div>
                  </div>
                </div>
              </div>
              <!-- /.card-body -->

              <!-- Edit Button -->
              <div class="card-footer">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editMedicalModal">Edit Medical History</button>
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

  <!-- Modal for Editing Medical History -->
  <div class="modal fade" id="editMedicalModal" tabindex="-1" aria-labelledby="editMedicalModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <form method="POST" action="">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="editMedicalModalLabel">Edit Medical History</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <label for="txtage">Age</label>
              <input type="number" name="txtage" class="form-control" id="txtage" value="<?php echo htmlspecialchars($row_med['age']); ?>" required>
            </div>
            <div class="form-group">
              <label for="txtconditions">Medical Conditions</label>
              <textarea name="txtconditions" class="form-control" id="txtconditions" rows="2" required><?php echo htmlspecialchars($row_med['conditions']); ?></textarea>
            </div>
            <div class="form-group">
              <label for="txtmedications">Medications</label>
              <textarea name="txtmedications" class="form-control" id="txtmedications" rows="2" required><?php echo htmlspecialchars($row_med['medications']); ?></textarea>
            </div>
            <div class="form-group">
              <label for="txtallergies">Allergies</label>
              <textarea name="txtallergies" class="form-control" id="txtallergies" rows="2" required><?php echo htmlspecialchars($row_med['allergies']); ?></textarea>
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

  <!-- Footer -->
  <footer class="main-footer">
    <strong><?php include('partials/footer.php'); ?></strong>
  </footer>

</div>
<!-- ./wrapper -->

<!-- Scripts -->
<?php include('partials/bottom-script.php'); ?>
</body>
</html>
