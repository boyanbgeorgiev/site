<?php
session_start();

include("connection.php");
include("functions.php");

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $user_name = $_POST['user_name'];
    $password = $_POST['password'];

    if (!empty($user_name) && !empty($password) && !is_numeric($user_name)) {
        //read from database
        $query = "select * from users where user_name = '$user_name' limit 1";  

        $result = mysqli_query($con, $query);

        if($result)
        {
            if($result && mysqli_num_rows($result) > 0)
            {

                $user_data = mysqli_fetch_assoc($result); 

                if($user_data['password'] === $password)
                {

                    $_SESSION['user_id'] = $user_data['user_id'];
                    header("Location: ../index.html");
                    die;
                }

            }
        }
        echo "Wrong username or pass";
    } else {
        echo "Please enter correct info";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../login.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Jacquard+24&family=Jersey+25&family=Staatliches&display=swap" rel="stylesheet">
</head>
<body>
    <div class="topnav">
        <a href="#home">HOME</a>
        <a href="#news">HOODIES</a>
        <a href="#contact">TEES</a>
        <a href="#contact">E GIFT CARDS</a>
        <a href="#about" class="split"><img src="../market.png" alt="BAG" class="logo"></a>
        <a href="#about" class="split"><img src="../user.png" alt="USER" class="logo"></a>
    </div>
    
    <div class="title">Log in</div>
<div id="box">
        <form method="post">
            
            <p class="desc">USERNAME</p>
            <input type="text" name="user_name"><br><br>
            <p class="desc">PASSWORD</p>
            <input type="password" name="password"><br><br>
            <input type="submit" value="Login"><br><br>
        </form> 
    </div>
<div class="links">
    <a href="/login/signup.php">CREATE ACCOUNT</a>
</div>
</body>
</html>