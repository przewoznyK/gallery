<?php
if(isset($_GET['image']))
{
    unlink($_GET['image']);
    header('location: profile.php');
}
else header('location: profile.php');
?>