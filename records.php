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

$conn = new mysqli($servername, $username, $password, $dbname);

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
  
  $conn->close();
  ?>

</body>
</html>