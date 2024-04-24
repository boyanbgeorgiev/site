<?php
session_start();

include("connection.php");
include("functions.php");

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $user_name = $_POST['user_name'];
    $password = $_POST['password'];

    if (!empty($user_name) && !empty($password) && !is_numeric($user_name)) {
        // Check if the username already exists
        $existing_user_query = "SELECT * FROM users WHERE user_name = ?";
        $stmt = mysqli_prepare($con, $existing_user_query);
        mysqli_stmt_bind_param($stmt, "s", $user_name);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if (mysqli_num_rows($result) > 0) {
            echo '<p class="error-message">Username already exists</p>';
        } else {
            // Generate a random salt
            $salt = randomSalt(16);
            
            // Combine the password and salt
            $salted_password = $password . $salt;
            
            // Hash the salted password
            $hashed_password = password_hash($salted_password, PASSWORD_DEFAULT);
            
            // Prepare and bind the SQL statement
            $insert_query = "INSERT INTO users (user_id, user_name, password, salt) VALUES (?, ?, ?, ?)";
            $insert_stmt = mysqli_prepare($con, $insert_query);
            
            // Check if the statement preparation was successful
            if ($insert_stmt) {
                // Generate a random user ID
                $user_id = random_num(20);
                
                // Bind parameters to the prepared statement
                mysqli_stmt_bind_param($insert_stmt, "ssss", $user_id, $user_name, $hashed_password, $salt);
                
                // Execute the prepared statement
                if (mysqli_stmt_execute($insert_stmt)) {
                    // Set username cookie
                    setcookie('user_name', $user_name, time() + (86400 * 30), '/'); // Cookie expires in 30 days
    
                    // Redirect to the login page
                    header("Location: login.php");
                    exit;
                } else {
                    echo '<p class="error-message">Error executing the statement: ' . mysqli_stmt_error($insert_stmt) . '</p>';
                }
            } else {
                echo '<p class="error-message">Error preparing the statement: ' . mysqli_error($con) . '</p>';
            }
            
            // Close the statement
            mysqli_stmt_close($insert_stmt);
        }
    } else {
        echo '<p class="error-message">Please enter correct info</p>';
    }
}

// Function to generate a random salt
function randomSalt($length) {
    return bin2hex(random_bytes($length));
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup</title>
    <link rel="stylesheet" href="../styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Jacquard+24&family=Jersey+25&family=Staatliches&display=swap" rel="stylesheet">
</head>
<body>
    <div id="navigation"></div>
<script src="../navbar.js"></script> 
    
    <div class="title">SIGN UP</div>
    <div id="box">
        <form method="post">
            
            <p class="desc">USERNAME</p>
            <input type="text" name="user_name"><br><br>
            <p class="desc">PASSWORD</p>
            <input type="password" name="password"><br><br>
            <input type="submit" value="Submit"><br><br>
        </form> 
    </div>
<div class="links">
    <a href="login.php">LOG IN</a><br><br>
    <a href="logout.php">LOG OUT</a>
</div>
</body>
</html>
