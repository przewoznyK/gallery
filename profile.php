<?php

require_once('connect.php');
require('search.php');
$my_profile = false;

$conn = @new mysqli($host, $db_user, $db_password, $db_name);
if (!isset($_GET['user_search']) && !isset($_SESSION['user']))
{
    header('location:index.php');
}
if (isset($_GET['user_search'])) {
    $user = mysqli_real_escape_string($conn, $_GET['user_search']);
    $result = mysqli_query($conn, "SELECT user FROM gallery_users WHERE user = '$user'");

    if ($result->num_rows == 0) {
        header('location:index.php');
    }
}

if (isset($_GET['user_search']) && isset($_SESSION['user']) && $_GET['user_search'] == $_SESSION['user']) {
    $my_profile = true;
} else if (!isset($_GET['user_search']) && isset($_SESSION['user'])) {
    $my_profile = true;
}

$result = mysqli_query($conn, "SELECT * FROM gallery_users WHERE user = '$user'");

while ($user = mysqli_fetch_array($result)) {
    $login = $user['user'];
    $gender = $user['gender'];
    $user_description = $user['user_description'];
}
$base_folder = 'users_images/' . $login . '/';
$user_folder = $base_folder . 'gallery';

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="index.css">
    <meta http-equiv="Pragma" content="no-cache">
</head>

<body>
<div id="mainSection">
        
        </div>

    <div id="banner"> <?php
                        $images = glob($base_folder . "/banner/*.{jpg,jpeg,png,gif}", GLOB_BRACE);

                        if ($images) {
                            echo '<img src="' . $images[0] . '" alt="My Image">';
                        } else {
                            echo 'No banner found.';
                        }
                        ?>

        <div id="avatar">
            <?php
            $images = glob($base_folder . "/avatar/*.{jpg,jpeg,png,gif}", GLOB_BRACE);
            if ($images) {
                echo '<img src="' . $images[0] . '" alt="My Image">';
            } else {
                echo 'No avatar found.';
            }
            ?></div>
    </div>

    <div id="profile_info_container">
        <?php
        if ($my_profile) echo "<div id='editButton'><a href='profile-edit.php'><img src='tools/edit.jpg'></a></div>";
        ?>

        <p><strong>Information</strong> </p>

        <p><?php echo $login; ?></p>
        <p><?php echo $gender; ?></p>

        <p>
        <div id="user_description"> <?php echo $user_description ?></div>
        </p>

    </div>
    <main>
        <?php
        $extensions_array = array('jpg', 'png', 'jpeg');
        $dir_path    = $base_folder . 'gallery/';
        if (is_dir($dir_path)) {
            $files = scandir($dir_path);
            for ($i = 0; $i < count($files); $i++) {
                if ($files[$i] != '.' && $files[$i] != '..') {
                    // get file extension
                    $file = pathinfo($files[$i]);
                    $extension = $file['extension'];
                    // check file extension
                    if (in_array($extension, $extensions_array)) {
                        // show image
                        echo "<div id='image_container'>";
                        echo "<img class='image' action='profile.php' src='$dir_path$files[$i]'class='resize' onclick='toFullScreen(this, $i)' id='id_$i'>";
                        if($my_profile)
                        {
                        echo "<a href=delete_image_from_gallery.php?image=$dir_path$files[$i]><img class='trash' src='tools/trash.jpg'></a>";
                        }
                        echo "</div>";
                    }
                }
            }
        }
        
        ?>
        <form class="form" id="form" action="upload.php" enctype="multipart/form-data" method="post">
            <?php
            if ($my_profile) {
                echo
                "
            <div id='button_container'>
            <input type='file' name='addImage' id='addImage' accept='.jpg, .jpeg, .png'>
            <input id='save_button' type=submit value='Add Image' name='upload'>
            </div>
            ";
            }
            ?>
        </form>
    </main>
    <div style="clear:both" ;></div>
</body>

</html>

<script>
        const images = document.getElementsByClassName('image');
    var full_screen_active = false;
    var image_count;
    window.addEventListener('keydown', (event) => {
        if (event.key == "d" || event.keyCode == 39) {
            nextRightImage();
        } else if (event.key == "a" || event.keyCode == 37) {
            nextLeftImage();
            
        } else if (event.key == "Escape") {
            closeFullScreen();
        }
    });
    function nextRightImage()
    {
        if( image_count < images.length -1 && full_screen_active)
            {
            image_count++;
            image_dispaly = (images[image_count].getAttribute('src'));
            document.getElementById("mainSection").innerHTML = '<div id="fullScreen"><img src=' +  image_dispaly + '  alt = "" /></div>';
            createArrow();
            }
    }
    function nextLeftImage()
    {
        if(image_count > 0 && full_screen_active)
            {
            image_count--;
            image_dispaly = (images[image_count].getAttribute('src'));
            document.getElementById("mainSection").innerHTML = '<div id="fullScreen"><img src=' +  image_dispaly + '  alt = "" /></div>';
            createArrow();
            }
    }
    function toFullScreen(this_image, id) {
        full_screen_active = true;
        image_count = id - 2;
        document.body.insertAdjacentHTML('afterbegin', '<div onclick="closeFullScreen()" id="full_screen_background"></div>');
        document.getElementById("mainSection").insertAdjacentHTML('afterbegin',
        '<div id="fullScreen"><img src=' + this_image.src + '  alt = "" /></div>');
        createArrow();


    }
    function createArrow()
    {
        if(image_count > 0)
        {
            document.getElementById("fullScreen").insertAdjacentHTML('afterbegin', '<div id="left_arrow" class="arrow" onclick="arrow(0)"><img src="tools/left_arrow.png"></div>');
        }
        if(image_count < images.length -1)
        {
            document.getElementById("fullScreen").insertAdjacentHTML('afterbegin', '<div id="right_arrow" class="arrow" onclick="arrow(1)"><img src="tools/left_arrow.png"></div>');
        }
 console.log(image_count);

            
    }
    function arrow(direction)
    {
        if(direction == 0) {nextLeftImage();
        }
        else if (direction == 1) {nextRightImage();}
    }
    function closeFullScreen()
    {
        document.getElementById("full_screen_background").remove();
        document.getElementById("fullScreen").remove();

    }
    </script>