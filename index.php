<?php
// landing_page.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Geriatric Emergency Response App</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="uploadImage/Logo/logo.png">

    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .hero {
            background: url('https://cdn.pixabay.com/photo/2017/01/10/19/05/old-1977794_1280.jpg') no-repeat center center;
            background-size: cover;
            height: 80vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-shadow: 2px 2px 5px #000;
        }
        .footer {
            background: #343a40;
            color: white;
            text-align: center;
            padding: 20px 0;
        }
        .logo {
            font-size: 1.5rem;
            font-weight: bold;
            color: #dc3545;
        }
        /* Floating Alert Button */
        .floating-alert-btn {
            position: fixed;
            bottom: 30px;
            right: 30px;
            z-index: 1050;
            background: #dc3545;
            color: #fff;
            border: none;
            border-radius: 50%;
            width: 70px;
            height: 70px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
            font-size: 2rem;
            transition: background 0.2s;
        }
        .floating-alert-btn:hover {
            background: #b52a37;
            color: #fff;
        }
        .floating-alert-btn:focus {
            outline: none;
            box-shadow: 0 0 0 0.2rem rgba(220,53,69,.25);
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <a class="navbar-brand logo" href="#">GeriCare</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
                <li class="nav-item"><a class="nav-link" href="register.php">Register</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- Hero Section -->
<section class="hero">
    <div class="text-center">
        <h1 class="display-4">Emergency Help at the Press of a Button</h1>
        <p class="lead">Quick, reliable emergency support for our elderly loved ones</p>
        <a href="register.php" class="btn btn-danger btn-lg">Get Started</a>
    </div>
</section>

<!-- About Section -->
<section class="container py-5">
    <div class="row align-items-center">
        <div class="col-md-6">
            <img src="images/istockphoto-1194308013-612x612.jpg" class="img-fluid rounded" alt="Elderly Care">
        </div>
        <div class="col-md-6">
            <h2>Why Choose GeriCare?</h2>
            <p>
                GeriCare is a web-based emergency response platform tailored for elderly users. With a simple interface, they can quickly send alerts to caregivers, family members, or medical personnel in times of need. It's designed with accessibility and speed in mind.
            </p>
            <ul>
                <li>Instant alert notifications</li>
                <li>View medical history and conditions</li>
                <li>Caregiver dashboard for quick response</li>
            </ul>
        </div>
    </div>
</section>

<!-- Floating Alert Button -->
<button class="floating-alert-btn" title="Send Emergency Alert" onclick="alert('Emergency Alert Sent!')">
    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-exclamation-triangle" viewBox="0 0 16 16">
        <path d="M7.938 2.016a.13.13 0 0 1 .125 0l6.857 11.856c.06.104.009.228-.104.228H1.184a.13.13 0 0 1-.104-.228L7.938 2.016zm.562-1.032a1.13 1.13 0 0 0-1 0L.643 12.84C.215 13.6.773 14.5 1.684 14.5h12.632c.911 0 1.469-.9 1.041-1.66L8.5.984zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
    </svg>
</button>

<!-- Footer -->
<footer class="footer">
    <div class="container">
        <p>&copy; 2025 GeriCare. All rights reserved.</p>
        <p>Designed for quick and compassionate elderly care.</p>
    </div>
</footer>

</body>
</html>
