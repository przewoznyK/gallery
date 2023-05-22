<?php

require_once('search.php');
$my_profile = false;

$conn = @new mysqli($host, $db_user, $db_password, $db_name);

if(isset($_SESSION['user']))
{
    $user = $_SESSION['user'];
    $my_profile = true;
}

$images = 0;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Index</title>
    <link rel="stylesheet" href="index.css">
</head>
<script>

</script>

<body>

    <section>
        <main>
            <div id="mainSection">
        
            </div>
            <?php
            $extensions_array = array('jpg', 'png', 'jpeg');
            $dir_path    = 'img/';
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
                            echo "<img class='image' src='$dir_path$files[$i]'class='resize' onclick='toFullScreen(this, $i)' id='id_$i'>";
                            $images++;
                        }
                    }
                }
            }
            ?>
        </main>
       
    </section>
    <div style="clear:both" ;></div>
</body>

</html>
<script>
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
        image_count = id;
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
    const images = document.getElementsByClassName('image');

</script>