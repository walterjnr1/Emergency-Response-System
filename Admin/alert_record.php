<?php 
include('../inc/config.php');
if (empty($_SESSION['user_id'])) {
  header("Location: ../user_login.php");
  //exit;
}

// Handle status update via POST (no AJAX)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $alert_id = intval($_POST['alert_id']);
    $new_status = $_POST['status'];
    $stmt = $conn->prepare("UPDATE emergency_alerts SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $new_status, $alert_id);
    $stmt->execute();
    // Redirect to avoid form resubmission
    header("Location: alert_record.php?updated=True");
    //exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php echo $app_name; ?> | Alert Record</title>
  <?php include('partials/head.php') ;?>
  <script type="text/javascript">
  function deldata(){
    return confirm("ARE YOU SURE YOU WISH TO DELETE THIS RECORD ?");
  }
  </script>
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
            <h1></h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="index">Home</a></li>
              <li class="breadcrumb-item active">Alert Record</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <?php if (isset($_GET['updated'])): ?>
          <div class="alert alert-success">Alert status updated successfully.</div>
        <?php endif; ?>
        <div class="row">
          <div class="col-12">
            <!-- /.card -->
       <div class="card">
              <div class="card-header">
                <div>
                  <h5>This Table contains data about Alert</h5>
                  </div>
                <h3 class="card-title">&nbsp;</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                      <th>s/n</th>
                      <th>Elderly Name</th>
                      <th>Caregiver</th>
                      <th>Message</th>
                      <th>Location</th>
                      <th>Status</th>
                      <th>Alert Time</th>
                      <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $sql = "SELECT u.*, ea.*, ea.created_at, ea.status, ea.id as alert_id FROM users u INNER JOIN emergency_alerts ea ON ea.elderly_id = u.id
                        WHERE u.role != 'Admin' ORDER BY ea.created_at DESC";
                    $result = $conn->query($sql);
                    $cnt = 1;
                    while ($row = $result->fetch_assoc()) {
                      // Fetch caregiver name if caregiver_id is set
                      $caregiverName = '';
                      if (!empty($row['caregiver_id'])) {
                        $cgid = intval($row['caregiver_id']);
                        $cgSql = "SELECT name FROM users WHERE id = $cgid AND role = 'caregiver' LIMIT 1";
                        $cgResult = $conn->query($cgSql);
                        if ($cgRow = $cgResult->fetch_assoc()) {
                          $caregiverName = $cgRow['name'];
                        } else {
                          $caregiverName = 'N/A';
                        }
                      } else {
                        $caregiverName = 'N/A';
                      }
                      // Icon color based on status
                      $iconColor = 'warning';
                      if ($row['status'] === 'Responding') $iconColor = 'info';
                      if ($row['status'] === 'Resolved') $iconColor = 'success';
                    ?>
                    <tr>
                      <td><?php echo $cnt ?></td>
                      <td><?php echo htmlspecialchars($row['name']) ?></td>
                      <td><?php echo htmlspecialchars($caregiverName); ?></td>
                      <td><?php echo htmlspecialchars($row['alert_message']) ?></td>
                      <td><?php echo htmlspecialchars($row['location']) ?></td>
                      <td>
                        <form method="post" style="display:inline-block;">
                          <input type="hidden" name="alert_id" value="<?php echo $row['alert_id']; ?>">
                          <select name="status" class="form-control form-control-sm d-inline" style="width:auto;display:inline-block;" required>
                            <option value="Pending" <?php if($row['status']=='Pending') echo 'selected'; ?>>Pending</option>
                            <option value="Responding" <?php if($row['status']=='Responding') echo 'selected'; ?>>Responding</option>
                            <option value="Resolved" <?php if($row['status']=='Resolved') echo 'selected'; ?>>Resolved</option>
                          </select>
                          <button type="submit" name="update_status" class="btn btn-sm btn-primary" title="Update Status">
                            <i class="fa fa-edit text-<?php echo $iconColor; ?>"></i>
                          </button>
                        </form>
                      </td>
                      <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                      <td>
                        <a href="delete_alert.php?id=<?php echo $row['id'];?>" onClick="return deldata();">
                          <i class="fa fa-trash" aria-hidden="true" title="Delete Record"></i>
                        </a>
                      </td>
                    </tr>
                    <?php $cnt++; } ?>
                    </tbody>
                  <tfoot>
                  </tfoot>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
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

<!-- jQuery -->
<?php include('partials/bottom-script.php') ;?>
<!-- Make sure Bootstrap JS and jQuery are loaded for modal functionality -->

</body>
</html>
