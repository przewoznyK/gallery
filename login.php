<?php
session_start();
if(isset($_SESSION['user']))
{
    header('location: index.php');
}
?>

<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="login_style.css">
    <title>Log in</title>
</head>

<body>
    <main>
        <span>Login</span>
        <form action="verification.php" method="post">
        <input type="text" name="login" placeholder="Login">
        <input type="password" name="pass" placeholder="Password">
        <input type="submit" value="Login">
        
        </form>
        <?php
            if(isset($_SESSION['error']))
            {
           echo $_SESSION['error'];
            }
        ?>
        <a href="registration.php"><input id="registerButton" type="button" value="Register"></a>
    </main>


</body>

</html>
