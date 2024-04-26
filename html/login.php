<?php
session_start();

include("connection.php");
include("functions.php");

// Check if user is already logged in using session
if(isset($_SESSION['user_name'])) {
    // Redirect to the welcome page
    header("Location: welcome.php");
    exit; // Terminate script execution
}

// Check if the remember me cookie exists and contains valid data
if(isset($_COOKIE['rememberme'])) {
    list($user_id, $token) = explode(':', $_COOKIE['rememberme']);

    // Fetch the user from the database based on the user ID
    $query = "SELECT * FROM users WHERE user_id = '$user_id' LIMIT 1";
    $result = mysqli_query($con, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $user_data = mysqli_fetch_assoc($result);

        // Verify the token stored in the database with the token from the cookie
        if (hash_equals($user_data['remember_token'], hash('sha256', $token))) {
            // Set user session variables
            $_SESSION['user_id'] = $user_data['user_id'];
            $_SESSION['user_name'] = $user_data['user_name'];

            // Redirect to the welcome page
            header("Location: welcome.php");
            exit; // Terminate script execution
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $user_name = $_POST['user_name'];
    $password = $_POST['password'];
    $remember_me = isset($_POST['remember_me']); // Check if remember me checkbox is checked

    if (!empty($user_name) && !empty($password) && !is_numeric($user_name)) {
        // Read from database
        $query = "SELECT * FROM users WHERE user_name = '$user_name' LIMIT 1";  

        $result = mysqli_query($con, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            $user_data = mysqli_fetch_assoc($result); 

            // Combine the password and salt
            $salted_password = $password . $user_data['salt'];

            // Verify hashed password
            if (password_verify($salted_password, $user_data['password'])) {
                // Set user session variables
                $_SESSION['user_id'] = $user_data['user_id'];
                $_SESSION['user_name'] = $user_data['user_name'];

                // Set remember me cookie if checked
                if ($remember_me) {
                    $token = bin2hex(random_bytes(16)); // Generate a random token
                    $expiry_time = time() + (30 * 24 * 3600); // 30 days expiration time
                    $token_hash = hash('sha256', $token);
                    setcookie('rememberme', $user_data['user_id'] . ':' . $token, $expiry_time, '/');
                    
                    // Update the remember token in the database
                    $update_query = "UPDATE users SET remember_token = '$token_hash' WHERE user_id = '{$user_data['user_id']}'";
                    mysqli_query($con, $update_query);
                }

                // Redirect to the welcome page
                header("Location: welcome.php");
                exit; // Terminate script execution
            } else {
                echo '<p class="error-message">Wrong password</p>';
            }
        } else {
            echo '<p class="error-message">User not found</p>';
        }
    } else {
        echo '<p class="error-message">Please enter correct info</p>';
    }
}
?>





<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="icon" href="/img/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="/styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Jacquard+24&family=Jersey+25&family=Staatliches&display=swap" rel="stylesheet">
</head>
<body>
<div id="navigation"></div>
<script src="/navbar.js"></script> 
    
    <div class="title">Log in</div>
    <div id="box">
        <?php if(!empty($error)) { ?>
            <p class="error"><?php echo $error; ?></p>
        <?php } ?>
        <form method="post">
            <p class="desc">USERNAME</p>
            <input type="text" name="user_name"><br><br>
            <p class="desc">PASSWORD</p>
            <input type="password" name="password"><br><br>
            <input type="submit" value="Login"><br><br>
        </form> 
    </div>
<div class="links">
    <a href="signup.php">CREATE ACCOUNT</a><br><br>
    <a href="logout.php">LOG OUT</a>
</div>
</body>
</html>
