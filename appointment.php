<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/animations.css">  
    <link rel="stylesheet" href="css/main.css">  
    <link rel="stylesheet" href="css/login.css">
        
    <title>Login</title>

    
    
</head>
<body>

<?php
// Start session
session_start();

// Redirect to login page if doctor is not logged in
if (!isset($_SESSION['doctor_id'])) {
  header('Location: login.php');
  exit();
}

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Retrieve form data
$username = $_POST['username'];
$password = $_POST['password'];

// Hash the password
$hashed_password = md5($password);

// Check if the doctor exists in the database
$sql = "SELECT * FROM doctors WHERE username='$username' AND password='$hashed_password'";
$result = $conn->query($sql);

if ($result->num_rows == 1) {
  // Doctor exists, set session variable and redirect to dashboard
  $row = $result->fetch_assoc();
  $_SESSION['doctor_id'] = $row['id'];
  header('Location: dashboard.php');
} else {
  // Invalid login, display error message
  echo "Invalid username or password.";
}

// Retrieve appointment information for the logged-in doctor
$doctor_id = $_SESSION['doctor_id']; // Assumes you're using PHP sessions to keep track of the logged-in doctor
$sql = "SELECT * FROM appointments WHERE doctor_id = $doctor_id";
$result = $conn->query($sql);

// Display the appointment information in an HTML table
if ($result->num_rows > 0) {
  echo "<table>";
  echo "<tr><th>Appointment ID</th><th>Patient Name</th><th>Date</th><th>Time</th></tr>";
  while($row = $result->fetch_assoc()) {
    echo "<tr><td>".$row["id"]."</td><td>".$row["patient_name"]."</td><td>".$row["date"]."</td><td>".$row["time"]."</td></tr>";
  }
  echo "</table>";
} else {
  echo "No appointments found.";
}

$conn->close();
?>

</body>
</html>
