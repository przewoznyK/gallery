<?php
session_start();
require_once('connect.php');
$conn = @new mysqli($host, $db_user, $db_password, $db_name);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="gallery_style.css">
    <title>Document</title>
</head>

<body>
    <header>
        <div id="top_menu_container">
            <a href='index.php'><img id="home_icon" src="tools/home_image.png" alt='Back to main page'></a>
            <form method="post">
                <label id='search_container'>
                    <input type="text" name="search">
                    <input type="submit" name="submit" value="Search user">
                </label>

            </form>
            <div><?php
                    if (isset($_SESSION['user'])) {
                        $user = $_SESSION['user'];
                        $user_profile = 'users_images/' . $user . '/';
                        echo '<a href=profile.php?user_search=' . $user. '>';
                        
                        $user_profile = 'users_images/' . $user . '/';
                        $images = glob($user_profile . "/avatar/*.{jpg,jpeg,png,gif}", GLOB_BRACE);
                        if ($images) {
                            echo '<img src="' . $images[0] . '" alt="My Image">';
                        } else {
                            echo 'No avatar found.';
                        }
                        echo $user.'</a>';
                        echo '<div id="logout_button"><a href="logout.php"> Logout</a></div>';
                    }
                    else
                    {
                        echo '<div id="login_button"><a href="login.php"> Login </a></div>';
                    }
                    ?>
            </div>
        </div>
        <?php

        if (isset($_POST["submit"])) {
            $str = $_POST["search"];

            $search_query = "%" . $conn->real_escape_string($_POST['search']) . "%";
            $sql = "SELECT * FROM gallery_users WHERE user LIKE '$search_query'";
            $result = $conn->query($sql);

            echo   '<div id="container_search" >';


            while ($r = $result->fetch_assoc()) {
                $login = $r['user'];
                $base_folder = 'users_images/' . $login . '/';
    
                echo  '<div class="profile_square">';
                echo '<a href=profile.php?user_search=' . $login . '>';

                $images = glob($base_folder . "avatar/*.{jpg,jpeg,png,gif}", GLOB_BRACE);

                if ($images) {
                    echo '<img src="' . $images[0] . '" alt="My Image">';
                } else {
                    echo 'No avatar found.';
                }
                echo $login;
                echo '</div></a>';
            }
            echo '</div>';
        }
        ?>
    </header>




</body>

</html>