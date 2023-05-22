<?php

session_start();
require_once "connect.php";

//database connection
$conn = @new mysqli($host, $db_user, $db_password, $db_name);

//check database connection
if ($conn->connect_errno != 0) {
    echo "Error " . $conn->connect_errno;
} else {
    $login = $_POST['login'];
    $pass = $_POST['pass'];


    $login = htmlentities($login, ENT_QUOTES, "UTF-8");
    


    if ($result = @$conn->query(sprintf(
        "SELECT * FROM gallery_users WHERE user = '%s'",
        mysqli_real_escape_string($conn, $login)
    ))) {
        $how_many_users = $result->num_rows;
        if ($how_many_users > 0) {
            $row = $result->fetch_assoc();
            if  (password_verify($pass, $row['pass'])) {
                $_SESSION['logged'] = true;
                $_SESSION['id'] = $row['id'];
                $_SESSION['user'] = $row['user'];
                $_SESSION['email'] = $row['email'];
                $_SESSION['gender'] = $row['gender'];
                unset($_SESSION['error']);
          
                $result->free_result();
                header('Location: index.php');
            } else {
                $_SESSION['error'] =
                    '<span class="texterror">Invalid login or password! </span>';
                    header("refresh:0;login.php");
                
            }
        } else {
            $_SESSION['error'] =
            '<span class="texterror">Invalid login or password! </span>';
            header("refresh:0;login.php");
        }
    }
    $conn->close();
}
