<?php
session_start();
include("connection.php");
include("functions.php");
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com"> <!-- preconnection with the Google Fonts domain, which can reduce latency -->
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin> <!-- preconnection with the Google Fonts domain, which can reduce latency -->
    <link href="https://fonts.googleapis.com/css2?family=Jacquard+24&family=Jersey+25&family=Staatliches&display=swap" rel="stylesheet"> <!-- Google font -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- Hello, $username -->
    <script src="cart.js"></script>
</head>
<body>
<div id="navigation"><script src="navbar.js"></script></div>
    <p class="message">You are logged in as <?php echo $_SESSION['user_name']; ?></p>
    <div class="links">
    <a href="logout.php">LOG OUT</a>
</div>
</body>
</html>
