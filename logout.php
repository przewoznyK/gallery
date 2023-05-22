<?php
session_start();
session_unset();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout</title>
    <link rel="stylesheet" href="form.css">
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

                    document.getElementById("container").innerHTML = 'You have been logged out, return to the main page in ' + i;
                    await sleep(1000);
                }
                document.location.href = 'index.php';
            }
            count();
        </script>
    </div>
    <div id="back_now"> <a href="index.php"> Back now </a> </div>

</body>


</html>