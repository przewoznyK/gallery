<?php

    $host = "localhost";
    $db_user = "root";
    $db_password = "";
    $db_name = "gallery";

    function debug_to_console($data) {
        $output = $data;
        if (is_array($output))
            $output = implode(',', $output);
    
        echo "<script>console.log('Debug Objects: " . $output . "' );</script>";
    }
?>