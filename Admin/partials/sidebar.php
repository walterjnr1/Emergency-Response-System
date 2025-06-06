<style>
  .alert-badge {
    position: absolute;
    top: 0;
    left: 18px;
    background: red;
    color: white;
    font-size: 12px;
    padding: 2px 6px;
    border-radius: 50%;
  }
  .sidebar-alert-btn {
    position: fixed;
    right: 40px;
    bottom: 4%;
    width: auto;
    z-index: 999;
    display: flex;
    justify-content: flex-end;
  }
  .alert-btn {
    background: #dc3545;
    color: #fff;
    border: none;
    border-radius: 50%;
    width: 100px;
    height: 100px;
    font-size: 28px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    cursor: pointer;
    transition: background 0.2s;
  }
  .alert-btn:hover {
    background: #b52a37;
  }
</style>
     
<!-- Brand Logo -->
<a href="index" class="brand-link">
  <img src="../<?php echo $app_logo; ?>" alt="app Logo" class="brand-image img-circle elevation-3" style="opacity: .8; width: 100px; height: 200px;">
  <span class="brand-text font-weight-light"><?php echo $app_name; ?></span>
</a>

<!-- Sidebar -->
<div class="sidebar">
  <!-- Sidebar user panel (optional) -->
  <div class="user-panel mt-3 pb-3 mb-3 d-flex">
    
  </div>

  <!-- SidebarSearch Form -->
  <div class="form-inline">
    <div class="input-group" data-widget="sidebar-search">
      <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
      <div class="input-group-append">
        <button class="btn btn-sidebar">
          <i class="fas fa-search fa-fw"></i>
        </button>
      </div>
    </div>
  </div>

  <!-- Sidebar Menu -->
  <nav class="mt-2">
    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
     
      <li class="nav-item">
        <a href="index" class="nav-link active">
          <i class="nav-icon fas fa-tachometer-alt"></i>
          <p>Dashboard</p>
        </a>
      </li>
      <li class="nav-item">
        <a href="#" class="nav-link">
          <i class="nav-icon fas fa-user"></i>
          <p>
            Account Management
            <i class="fas fa-angle-left right"></i>
          </p>
        </a>
        <ul class="nav nav-treeview">
          <li class="nav-item">
            <a href="add_user" class="nav-link">
              <i class="far fa-circle nav-icon"></i>
              <p>New User</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="user_record" class="nav-link">
              <i class="far fa-circle nav-icon"></i>
              <p>User Record</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="change_password" class="nav-link">
              <i class="far fa-circle nav-icon"></i>
              <p>Change Password</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="profile" class="nav-link">
              <i class="far fa-circle nav-icon"></i>
              <p>Profile</p>
            </a>
          </li>
        </ul>
      </li>
      <li class="nav-item">
        <a href="#" class="nav-link">
          <i class="nav-icon fas fa-bell" style="color: orange;"></i>
            <span class="alert-badge">
            <?php echo $total_alert['total_alert_pending']; ?>
            </span>
          <p>Alert Management<i class="fas fa-angle-left right"></i></p>
        </a>
        <ul class="nav nav-treeview">
          <li class="nav-item">
           
          </li>
          <li class="nav-item">
            <a href="alert_record.php" class="nav-link">
              <i class="far fa-circle nav-icon"></i><p>Alert Record</p></a>
          </li>
                  </ul>
      </li>
      <li class="nav-item">
        <a href="logout.php" class="nav-link">
          <i class="nav-icon fas fa-sign-out-alt"></i>
          <p>Logout</p>
        </a>
      </li>
    </ul>
  </nav>
  <!-- /.sidebar-menu -->

  <?php
$user_id = $_SESSION["user_id"];

  // Handle form submission before any HTML output
  if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_alert'])) {
   //include('../inc/config.php');


    $elderly_name = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : '';
    $elderly_address = isset($_SESSION['user_address']) ? $_SESSION['user_address'] : '';
    $elderly_id = $user_id;
    $location = trim($_POST['location']);
    $status = 'Pending';

    // Compose alert message
    $alert_message = "Emergency alert triggered by $elderly_name at address: $location.";

    // Insert into emergency_alerts
    if (isset($conn) && $conn) {
      $stmt = $conn->prepare("INSERT INTO emergency_alerts (elderly_id, alert_message, location, status) VALUES (?, ?, ?, ?)");
      $stmt->bind_param("isss", $elderly_id, $alert_message, $location, $status);
      if ($stmt->execute()) {
        

        //use caregiver_id in users table to get caregiver data. caregiver_id and user_id are the same users table
if ($role == 'elderly') {
    $user_id = $row_user['caregiver_id'];
} else {
    $user_id = $row_user['id'];
}
$stmt = $dbh->query("SELECT * FROM users WHERE caregiver_id='$user_id' AND role='caregiver'");
$caregiver_data = $stmt->fetch();
//fetch caregiver email
$caregiver_email = $caregiver_data['email'] ?? '';
$caregiver_name = $caregiver_data['name'] ?? '';



        // Include the file where sendEmail() is defined
        if (!function_exists('sendEmail')) {
          include_once('../inc/email.php'); // Adjust the path as needed
        }

        // Send emergency alert email to caregiver
        if (!empty($caregiver_email)) {
          $subject = "Emergency Alert Notification | $app_name";
          $message = "
          <html>
          <head>
              <title>Emergency Alert</title>
          </head>
          <body>
              <p>Hello <strong>" . htmlspecialchars($caregiver_name) . "</strong>,</p>
              <p>An emergency alert has been triggered by <strong>" . htmlspecialchars($elderly_name) . "</strong> at address:</p>
              <p><strong>" . htmlspecialchars($location) . "</strong></p>
              <p>Please respond as soon as possible.</p>
              <p><strong>$app_name</strong> Team</p>
          </body>
          </html>";
          sendEmail($caregiver_email, $subject, $message);
        }
        echo "<script>alert('Emergency alert sent and caregiver notified.');</script>";
      }
      $stmt = null;
    }
  }
  ?>

  <?php
  // Check if user is elderly
  if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'elderly') {
    $elderly_name = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : '';
    $elderly_address = isset($_SESSION['user_address']) ? $_SESSION['user_address'] : '';
    ?>
    <!-- Alert Button at the Bottom -->
    <div class="sidebar-alert-btn">
      <button class="alert-btn" id="openAlertModal" type="button">
      <i class="fas fa-exclamation-triangle"></i> Alert
      </button>
    </div>

    <!-- Modal -->
    <div class="modal" id="alertModal" tabindex="-1" style="display:none; position:fixed; z-index:10000; left:0; top:0; width:100vw; height:100vh; background:rgba(0,0,0,0.5); align-items:center; justify-content:center;">
      <div style="background:#fff; padding:24px; border-radius:8px; max-width:400px; margin:auto; position:relative;">
      <h5>Send Emergency Alert</h5>
      <form method="post" id="alertForm">
        <div class="form-group">
        <label>Name</label>
        <input type="text" name="elderly_name" class="form-control" value="<?php echo htmlspecialchars($elderly_name); ?>" readonly>
        </div>
        <div class="form-group">
        <label>Address</label>
        <input type="text" name="location" class="form-control" value="<?php echo htmlspecialchars($elderly_address); ?>" size="67" required>
        </div>
      
        <button type="submit" name="send_alert" class="btn btn-danger">Send Alert</button>
        <button type="button" class="btn btn-secondary" id="closeAlertModal" style="margin-left:10px;">Cancel</button>
      </form>
      </div>
    </div>
    <script>
      document.getElementById('openAlertModal').onclick = function() {
      document.getElementById('alertModal').style.display = 'flex';
      };
      document.getElementById('closeAlertModal').onclick = function() {
      document.getElementById('alertModal').style.display = 'none';
      };
      // Optional: close modal on outside click
      document.getElementById('alertModal').onclick = function(e) {
      if (e.target === this) this.style.display = 'none';
      };
    </script>
    <?php
  }
  ?>
</div>
<!-- /.sidebar -->

