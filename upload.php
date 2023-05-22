
<?php

require_once('connect.php');
require_once('top_menu.php');
$conn = @new mysqli($host, $db_user, $db_password, $db_name);


$user = $_SESSION['user'];



$result = mysqli_query($conn, "SELECT * FROM gallery_users WHERE user = '$user'");

while ($user = mysqli_fetch_array($result)) {

  $login = $user['user'];
  $gender = $user['gender'];
}
$base_folder = 'users_images/' . $login . '/';
$user_folder = $base_folder . 'gallery';
$add_image = false;
if (isset($_POST['profileDescription'])) {

  $new_description = $_POST['profileDescription'];
  $new_description = mysqli_real_escape_string($conn, $new_description);
  $conn->query("UPDATE gallery_users SET user_description= '$new_description' WHERE user='$login';");

}


if ((isset($_FILES['addImage']['name'])) && !$_FILES['addImage']['name'] == "") {

  $imageName = $_FILES["addImage"]["name"];
  $imageSize = $_FILES["addImage"]["size"];
  $tmpName = $_FILES["addImage"]["tmp_name"];

  $validImageExtension = ['jpg', 'jpeg', 'png'];
  $imageExtension = explode('.', $imageName);
  $imageExtension = strtolower(end($imageExtension));

  if (!in_array($imageExtension, $validImageExtension)) {
    echo
    "<script> alert('Invalid Image Extension');
            document.location.href = 'updateimageprofile.php';
            </script>
            ";
  } elseif ($imageSize > 12000000) {
    echo
    "<script> alert('Image Size Is Too Large');
            document.location.href = 'updateimageprofile.php';
            </script>
            ";
  } else {
    $newImageName = $login . '-date-' . date('d-F-y_H-i-s') . '';
    $newImageName .= '.' . $imageExtension;



    if (!file_exists($user_folder)) {

      mkdir($user_folder, 0777, true);
    }

    move_uploaded_file($tmpName, $base_folder . 'gallery/' . $newImageName);

    echo
    "
        <script>
           
            alert('dziala');
        </script>
        ";
    unset($_FILES['addImage']['name']);
  }
}

if ((isset($_FILES['changeAvatar']['name'])) && !$_FILES['changeAvatar']['name'] == "") {

  $imageName = $_FILES["changeAvatar"]["name"];
  $imageSize = $_FILES["changeAvatar"]["size"];
  $tmpName = $_FILES["changeAvatar"]["tmp_name"];

  $validImageExtension = ['jpg', 'jpeg', 'png'];
  $imageExtension = explode('.', $imageName);
  $imageExtension = strtolower(end($imageExtension));

  if (!in_array($imageExtension, $validImageExtension)) {
    echo
    "<script> alert('Invalid Image Extension');
          document.location.href = 'updateimageprofile.php';
          </script>
          ";
  } elseif ($imageSize > 12000000) {
    echo
    "<script> alert('Image Size Is Too Large');
          document.location.href = 'updateimageprofile.php';
          </script>
          ";
  } else {
    $newImageName = $login . '-date-' . date('d-F-y_H-i-s') . '_avatar';
    $newImageName .= '.' . $imageExtension;


    $files = glob($base_folder.'avatar/*'); 
    foreach($files as $file){ 
    if(is_file($file)){ 
        unlink($file); 
    }
}
    move_uploaded_file($tmpName, $base_folder.'avatar/' . $newImageName);
  }
}

if ((isset($_FILES['changeBanner']['name'])) && !$_FILES['changeBanner']['name'] == "") {

  $imageName = $_FILES["changeBanner"]["name"];
  $imageSize = $_FILES["changeBanner"]["size"];
  $tmpName = $_FILES["changeBanner"]["tmp_name"];

  $validImageExtension = ['jpg', 'jpeg', 'png'];
  $imageExtension = explode('.', $imageName);
  $imageExtension = strtolower(end($imageExtension));

  if (!in_array($imageExtension, $validImageExtension)) {
    echo
    "<script> alert('Invalid Image Extension');
          document.location.href = 'updateimageprofile.php';
          </script>
          ";
  } elseif ($imageSize > 12000000) {
    echo
    "<script> alert('Image Size Is Too Large');
          document.location.href = 'updateimageprofile.php';
          </script>
          ";
  } else {
    $newImageName = $login . '_banner';
    $newImageName .= '.' . $imageExtension;



    $files = glob($base_folder.'banner/*'); // znalezienie plików w folderze
    foreach($files as $file){ // iteracja przez znalezione pliki
    if(is_file($file)){ // sprawdzenie czy to plik (a nie katalog)
        unlink($file); // usunięcie pliku
    }
}
    move_uploaded_file($tmpName, $base_folder.'banner/' . $newImageName);
  }
}

header('location: profile.php');
?>

