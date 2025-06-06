<?php 
include('../inc/config.php');
if (empty($_SESSION['user_id'])) {
  header("Location: ../user_login.php");

}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php echo $app_name; ?> | Response Record</title>
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
            <h1></h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="index">Home</a></li>
              <li class="breadcrumb-item active">Response Record</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <!-- /.card -->
       <div class="card">
              <div class="card-header">
                <div>
                  <h5>This Table contains data about Alert Response</h5>
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
                    $sql = "SELECT u.*, ea.*,ea.created_at , ea.status FROM users u INNER JOIN emergency_alerts ea ON ea.elderly_id = u.id
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
                    ?>
                    <tr>
                      <td><?php echo $cnt ?></td>
                      <td><?php echo htmlspecialchars($row['name']) ?></td>
                      <td><?php echo htmlspecialchars($caregiverName); ?></td>
                       <td><?php echo htmlspecialchars($row['alert_message']) ?></td>
                      <td><?php echo htmlspecialchars($row['location']) ?></td>
                      <td><?php echo htmlspecialchars($row['status']) ?></td>
                      <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                      <td>
                      <a href="delete_alert.php?id=<?php echo $row['id'];?>" onClick="return deldata();">
                        <i class="fa fa-trash" aria-hidden="true" title="Delete Record"></i>
                      </a>
                      </td>
                    </tr>
                    <?php $cnt++; } ?>
                    </tbody>

                  </tr>

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

</body>
</html>
