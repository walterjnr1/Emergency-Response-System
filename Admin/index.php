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
  <title><?php echo $app_name; ?> | Dashboard</title>
  <?php include('partials/head.php') ;?>

  <style type="text/css">
<!--
.style1 {color: #FF0000}
-->
  </style>
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
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Welcome  <?php echo $row_user['name']; ?>  </h1>
            <p class="m-0 text-dark">&nbsp;</p>

          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Dashboard v1</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-dark">
              <div class="inner">
                <h3><?php echo $row_user['role']; ?></h3>

                <p>Role</p>
              </div>
              <div class="icon">
                <i class="ion ion-person"></i>

              </div>
            </div>
          </div>

          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
              <div class="inner">
                <h3><?php echo $total_admins['total_admin']; ?></h3>

                <p>No. of Admin(s)</p>
              </div>
              <div class="icon">
              <i class="fas fa-user"></i>

              </div>
            </div>
          </div>

          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
              <div class="inner">
                <h3><?php echo $total_elderly['total_elderly']; ?></h3>

                <p>No. of Elderly(s)</p>
              </div>
              <div class="icon">
<i class="fas fa-user-plus"></i>
              </div>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
              <div class="inner">
                <h3><?php echo $total_caregivers['total_caregiver']; ?></h3>

                <p>No. of Caregiver(s)</p>
              </div>
              <div class="icon">
              <i class="fas fa-user-nurse"></i> 
              </div>
            </div>
          </div>
          <!-- ./col -->
                  

            <div class="col-lg-3 col-6">
            <!-- small box -->
            <a href="alerts.php" style="text-decoration: none;">
              <div class="small-box bg-white">
              <div class="inner">
                <h3><?php echo $total_alerts['total_alert']; ?></h3>
                <p>No. of Alert</p>
              </div>
              <div class="icon">
                <i class="fas fa-bell" style="color: #FF0000;"></i>
              </div>
              </div>
            </a>
            </div>
            <!-- ./col -->

        </div>
        <!-- /.row -->
        <!-- Main row -->
        <div class="row">
          <!-- Left col -->
          
          <div class="row">
  
</div>
          <!-- right col -->
        </div>
        <!-- /.row (main row) -->
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
