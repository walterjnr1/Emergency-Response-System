# Emergency Response System 🚨

A **web-based Emergency Response System** built using **PHP** and **MySQL**. This application is designed to help users quickly report emergencies and receive timely responses. Notifications and alerts are sent via **Email** to the appropriate responders or administrators.

## 🔧 Technologies Used

- **Backend:** PHP (Core PHP)
- **Database:** MySQL
- **Notifications:** Email (using PHP `mail()` function or SMTP)
- **Frontend:** HTML, CSS, JavaScript (optional for interactivity)

## 💡 Features

- 🔹 Emergency alert submission form
- 🔹 Real-time response tracking (status updates)
- 🔹 Email notifications sent to administrators or responders
- 🔹 Admin dashboard to view, manage, and respond to incidents
- 🔹 User authentication (optional for security)
- 🔹 Emergency type and priority classification

## 📦 Installation

1. **Clone the repository:**

   ```bash
   git clone https://github.com/walterjnr1/Emergency-Response-System.git
Import the SQL database:

Open phpMyAdmin or any MySQL client

Create a database (emergency_response)

Import the provided emergency_response.sql file

Update with your MySQL credentials

php
Copy
Edit
$host = "localhost";
$user = "root";
$password = "";
$dbname = "emergency_response";
Configure Email settings:

If using mail() function, ensure your server supports it

For SMTP (recommended), update mail configuration in config/mail.php

🚀 Usage
Users can submit emergency reports through a public-facing form

Admins log in to view and manage submitted reports

Upon submission, an email is automatically sent to designated responders or admins

🔐 Security Tips
Change default credentials after setup

Sanitize user inputs to avoid SQL injection

Use HTTPS in production

Configure proper SMTP authentication for reliable email delivery

Built for organizations, schools, or communities seeking to improve emergency communication and response time using simple and effective technology.
