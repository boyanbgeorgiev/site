<?php

var_dump($_POST);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>

    <div id="box">

       <form method="post">
       <div>Log in</div>

            <input type="text" name="user_name"><br><br>
            <input type="password" name="password"><br><br>

            <input type="submit" value="Login"><br><br>

            <a href="signup.php">Click to signup</a><br><br>
       </form> 

    </div>
    
</body>
</html>