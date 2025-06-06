<?php
include('database/connect2.php');

$name = $email = $password = $phone = $address = $role = $latitude = $longitude = $photo = "";
$error = $success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
  $name      = trim($_POST['name']);
  $email     = trim($_POST['email']);
  $password  = $_POST['password'];
  $phone     = trim($_POST['phone']);
  $role      = $_POST['role'];
  $latitude  = $_POST['latitude'];
  $longitude = $_POST['longitude'];
  $address   = "";

  // Get address from latitude and longitude using Nominatim API
  if (!empty($latitude) && !empty($longitude)) {
    $url = "https://nominatim.openstreetmap.org/reverse?format=json&lat=$latitude&lon=$longitude";
    $opts = [
      "http" => [
        "header" => "User-Agent: emergency_response_app"
      ]
    ];
    $context = stream_context_create($opts);
    $json = file_get_contents($url, false, $context);
    if ($json) {
      $data = json_decode($json, true);
      if (isset($data['display_name'])) {
        $address = $data['display_name'];
      }
    }
  }

  // Handle image upload
  if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
    $targetDir = "uploadImage/Profile/";
    if (!is_dir($targetDir)) {
      mkdir($targetDir, 0777, true);
    }
    $fileName = uniqid() . "_" . basename($_FILES["photo"]["name"]);
    $targetFile = $targetDir . $fileName;
    move_uploaded_file($_FILES["photo"]["tmp_name"], $targetFile);
    $photo = $targetFile;
  }

  // Check if user already exists
  $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $stmt->store_result();
  if ($stmt->num_rows > 0) {
    $error = "User already exists with this email.";
  } else {
    // Insert user
    $stmt = $conn->prepare("INSERT INTO users (name, email, password, phone, address, role, latitude, longitude, photo) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssdds", $name, $email, $password, $phone, $address, $role, $latitude, $longitude, $photo);
    if ($stmt->execute()) {
      $success = "Registration successful. <a href='login.php'>Login here</a>.";
    } else {
      $error = "Registration failed. Please try again.";
    }
  }
  $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="icon" type="image/x-icon" href="uploadImage/Logo/logo.png">
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
  <style>
    #map { height: 300px; width: 100%; margin-bottom: 1rem; }
  </style>
</head>
<body class="bg-light">
<div class="container mt-5">
  <div class="row justify-content-center">
  <div class="col-md-5">
    <div class="card">
    <div class="card-body">
      <h4 class="text-center">Create an Account</h4>
      <?php if ($error): ?>
      <div class="alert alert-danger"><?php echo $error; ?></div>
      <?php endif; ?>
      <?php if ($success): ?>
      <div class="alert alert-success"><?php echo $success; ?></div>
      <?php endif; ?>
      <form action="" method="post" enctype="multipart/form-data" id="registerForm">
      <div class="mb-3">
        <label>Full Name</label>
        <input type="text" name="name" class="form-control" required value="<?php echo htmlspecialchars($name); ?>">
      </div>
      <div class="mb-3">
        <label>Email</label>
        <input type="email" name="email" class="form-control" required value="<?php echo htmlspecialchars($email); ?>">
      </div>
      <div class="mb-3">
        <label>Phone</label>
        <input type="text" name="phone" class="form-control" required value="<?php echo htmlspecialchars($phone); ?>">
      </div>
      <div class="mb-3">
        <label>Password</label>
        <input type="password" name="password" class="form-control" required>
      </div>
      <div class="mb-3">
        <label>Role</label>
        <select name="role" class="form-control" required>
        <option value="elderly" <?php if($role=="elderly") echo "selected"; ?>>Elderly</option>
        <option value="caregiver" <?php if($role=="caregiver") echo "selected"; ?>>Caregiver</option>
        </select>
      </div>
      <div class="mb-3">
        <label>Photo</label>
        <input type="file" name="photo" class="form-control" accept="image/*" required>
      </div>
      <div class="mb-3">
        <label>Select Your Location</label>
        <div id="map"></div>
        <small class="text-muted">Drag the marker or click on the map to set your location.</small>
      </div>
      <input type="hidden" name="latitude" id="latitude" value="<?php echo htmlspecialchars($latitude); ?>">
      <input type="hidden" name="longitude" id="longitude" value="<?php echo htmlspecialchars($longitude); ?>">
      <button name="register" class="btn btn-success w-100">Register</button>
      <p class="mt-2 text-center">Already have an account? <a href="login.php">Login</a></p>
      </form>
    </div>
    </div>
  </div>
  </div>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
// Default location (center of the world)
var defaultLat = 0, defaultLng = 0;
var latInput = document.getElementById('latitude');
var lngInput = document.getElementById('longitude');

// Try to use previous values if available
if (latInput.value && lngInput.value) {
  defaultLat = parseFloat(latInput.value);
  defaultLng = parseFloat(lngInput.value);
} else if (navigator.geolocation) {
  navigator.geolocation.getCurrentPosition(function(position) {
    defaultLat = position.coords.latitude;
    defaultLng = position.coords.longitude;
    map.setView([defaultLat, defaultLng], 13);
    marker.setLatLng([defaultLat, defaultLng]);
    latInput.value = defaultLat;
    lngInput.value = defaultLng;
  });
}

var map = L.map('map').setView([defaultLat, defaultLng], 2);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
  maxZoom: 19,
  attribution: '© OpenStreetMap'
}).addTo(map);

var marker = L.marker([defaultLat, defaultLng], {draggable:true}).addTo(map);

marker.on('dragend', function(e) {
  var latlng = marker.getLatLng();
  latInput.value = latlng.lat;
  lngInput.value = latlng.lng;
});

map.on('click', function(e) {
  marker.setLatLng(e.latlng);
  latInput.value = e.latlng.lat;
  lngInput.value = e.latlng.lng;
});

// Set initial values
latInput.value = defaultLat;
lngInput.value = defaultLng;
</script>

<?php  include 'inc/footer.php'; ?>
</body>
</html>
