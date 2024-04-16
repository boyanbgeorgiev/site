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
    <title>Signup</title>
</head>
<body>

    <div id="box">

       <form method="post">
            <div>Signup</div>

            <input type="text" name="user_name"><br><br>
            <input type="password" name="password"><br><br>

            <input type="submit" value="Signuo"><br><br>

            <a href="login.php">Click to login</a><br><br>
       </form> 

    </div>
    
</body>
</html>