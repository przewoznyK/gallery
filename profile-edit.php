<?php
session_start();
header('Cache-Control: no-cache');
header('Pragma: no-cache');
if (!isset($_SESSION['user'])) {
    header('location:index.php');
}
$id = $_SESSION['id'];

require_once "connect.php";
mysqli_report(MYSQLI_REPORT_STRICT);
try {
    $conn = new mysqli($host, $db_user, $db_password, $db_name);
    if ($conn->connect_errno != 0) {
        throw new Exception(mysqli_connect_errno());
    } else {
        $result =  $conn->query("SELECT user, gender, user_description FROM gallery_users WHERE id='$id'");
        while ($row = $result->fetch_assoc()) {
            $login = $row["user"];
            $gender = $row["gender"];
            $user_description = $row['user_description'];        }
    }
} catch (Exception $e) {
    echo '<div class="error"> Błąd serwera</div>';
    echo $e;
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
    <link rel="stylesheet" href="gallery_style.css">
</head>

<body>
    <nav></nav>
    <div id="banner"> <?php 
    
    $images = glob($base_folder."/banner/*.{jpg,jpeg,png,gif}", GLOB_BRACE);
    
    if ($images) {
      echo '<img src="' . $images[0] . '" alt="My Image" id="preview-selected-banner">';
    } else {
      echo 'No banner found.';
    }
    
    ?>
        <div id="avatar"> 
        <?php 
    
    $images = glob($base_folder."/avatar/*.{jpg,jpeg,png,gif}", GLOB_BRACE);
    
    if ($images) {
      echo '<img src="' . $images[0] . '" alt="My Image" id="preview-selected-avatar">';
    } else {
      echo 'No avatar found.';
    }
    
    ?>
    
        </div>

        <div class="image-preview-container">
            <div class="preview">
                <img id="preview-selected-image" />
            </div>
            <form action='upload.php' enctype="multipart/form-data" method="post">
                <label id='change_container'>
                <p>Change banner  <input type="file" name="changeBanner" id="changeBanner" accept="image/*" onchange="previewBanner(event);" /></p>
                <p>Change avatar  <input type="file" name="changeAvatar" id="changeAvatar" accept="image/*" onchange="previewAvatar(event);" /></p>
                
            <p>Change description</p>
            <textarea id="profileDescription" name="profileDescription" rows="4" cols="50"> <?php echo $user_description ?> </textarea>
                <br>
                <br>
                <br>
                <br> </label>
                 <a href="profile.php" id="back_button"> Back </a>
           <input type=submit value="save" name="upload" class='save_button'>
                <div id="delete_button" onclick="confirmation()" >Delete profile</div>
    
               
            </form>
        
    
        </div>
        <script>
            function confirmation()
            {
                var answer = confirm("Do you really want delete this account?")
                if (answer)
                {
                    window.location = 'delete-profile.php';
                }
            }
            const previewAvatar = (event) => {
                /**
                 * Get the selected files.
                 */
                const imageFiles = event.target.files;
                /**
                 * Count the number of files selected.
                 */
                const imageFilesLength = imageFiles.length;
                /**
                 * If at least one image is selected, then proceed to display the preview.
                 */
                if (imageFilesLength > 0) {
                    /**
                     * Get the image path.
                     */
                    const imageSrc = URL.createObjectURL(imageFiles[0]);
                    /**
                     * Select the image preview element.
                     */
                    const imagePreviewElement = document.querySelector("#preview-selected-avatar");
                    /**
                     * Assign the path to the image preview element.
                     */
                    imagePreviewElement.src = imageSrc;
                    /**
                     * Show the element by changing the display value to "block".
                     */
                    imagePreviewElement.style.display = "block";
                }
            };

            const previewBanner = (event) => {
                
                const imageFiles = event.target.files;
               
                const imageFilesLength = imageFiles.length;
              
                if (imageFilesLength > 0) {
                  
                    const imageSrc = URL.createObjectURL(imageFiles[0]);
                 
                    const imagePreviewElement = document.querySelector("#preview-selected-banner");
                 
                    imagePreviewElement.src = imageSrc;
            
                    imagePreviewElement.style.display = "block";
                }
            };






        </script>

        
</body>

</html>

