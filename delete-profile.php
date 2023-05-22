
<?php
session_start();

if(!isset($_SESSION['user']))
{
    header('location: index.php');
}
else
{
    $user_to_delete = $_SESSION['user'];
require_once('connect.php');
$conn = @new mysqli($host, $db_user, $db_password, $db_name);

$dir = 'users_images/'.$user_to_delete;
function deleteAll($dir)
{
    foreach(glob($dir . '/*') as $file) {
    if(is_dir($file))
    deleteAll($file);
    else
    unlink($file);
    }
    rmdir($dir);
    }
}
if(@$conn->query("DELETE FROM gallery_users WHERE user = '$user_to_delete'"))
{
    if(deleteAll($dir))
    {
            echo 'Account successfully deleted';
    }
}
else
{
    echo 'Error, please try again';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="form.css">
    <title>Document</title>
</head>

<body>
    <div id="container">
        <script>
            time = 5;

            function sleep(ms) {
                return new Promise(resolve => setTimeout(resolve, ms));
            }
            async function count() {
                for (let i = time; i >= 0; i--) {

                    document.getElementById("container").innerHTML = 'You account has been successfully deleted, return to the main page in ' + i;
                    await sleep(1000);
                    <?php session_destroy(); ?>
                }
                document.location.href = 'index.php';
            }
            count();
        </script>
    </div>
    <div id="back_now"> <a href="index.php"> Back now </a> </div>

</body>

</html>

