<?php
session_start();

if (isset($_POST['email'])) {
    //success_regful validation
    $success_reg = true;
    //correctness of login
    $login = $_POST['login'];

    //checking login length
    if ((strlen($login) < 3) || (strlen($login) > 20)) {
        $success_reg = false;
        $_SESSION['e_login'] = "Login must have 3-20 characters";
    }

    if (ctype_alnum($login) == false) {
        $success_reg = false;
        $_SESSION['e_login'] = "No special characters";
    }
    //Chceking email correctness
    $email = $_POST['email'];
    $emailB = filter_var($email, FILTER_SANITIZE_EMAIL);
    if ((filter_var($emailB, FILTER_VALIDATE_EMAIL) == false || ($emailB != $email))) {
        $success_reg = false;
        $_SESSION['e_email'] = "Please enter a valid email address";
    }

    //Checking password correctness
    $pass = $_POST['pass'];
    $repeat_pass = $_POST['repeat_pass'];

    if (strlen($pass) < 8 || (strlen($pass) > 20)) {
        $success_reg = false;
        $_SESSION['e_pass'] = "Password must have 8-20 characters";
    }

    if ($pass != $repeat_pass) {
        $success_reg = false;
        $_SESSION['e_pass'] = "The given passwords are not identical";
    }

    $pass_hash = password_hash($pass, PASSWORD_DEFAULT);
    //Acceptance of the regulations
    if (!isset($_POST['regulations'])) {
        $success_reg = false;
        $_SESSION['e_regulations'] = "Accept regulations";
    }

    //Bot or not
    $captcha = $_POST['g-recaptcha-response'];
    $ch = curl_init();

    curl_setopt_array($ch, [
        CURLOPT_URL => 'https://www.google.com/recaptcha/api/siteverify',
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => [
            'secret' => '6Le8cL8kAAAAAHijCHxeQOU_-2LaLEZnCLz_avP5',
            'response' => $captcha,
            'remoteip' => $_SERVER['REMOTE_ADDR']
        ],
        CURLOPT_RETURNTRANSFER => true
    ]);

    $output = curl_exec($ch);
    curl_close($ch);
    $json = json_decode($output);


    if ($json->success == false) {
        $success_reg = false;
        $_SESSION['e_bot'] = "Confirm you are human";
    }

    //Remember data


    require_once "connect.php";
    mysqli_report(MYSQLI_REPORT_STRICT);
    try {
        $conn = new mysqli($host, $db_user, $db_password, $db_name);
        if ($conn->connect_errno != 0) {
            throw new Exception(mysqli_connect_errno());
        } else {
            //mail exist?
            $result = $conn->query("SELECT id FROM gallery_users WHERE email='$email'");
            if (!$result) throw new Exception($conn->error);
            $how_many_such_emails = $result->num_rows;
            if ($how_many_such_emails > 0) {
                $success_reg = false;
                $_SESSION['e_email'] = "There is already an account assigned to this email address!";
            }

            //is nick taken?
            $result = $conn->query("SELECT id FROM gallery_users WHERE user='$login'");
            if (!$result) throw new Exception($conn->error);
            $how_many_such_login = $result->num_rows;
            if ($how_many_such_login > 0) {
                $success_reg = false;
                $_SESSION['e_login'] = "There is already a player with this nickname! Choose another.";
            }
            if (!isset($_POST['gender'])) {
                $success_reg = false;
                $_SESSION['e_gender'] = "Choose gender.";
            } else $gender = $_POST['gender'];
            if ($success_reg == true) {
                //Add user to database
                $base_folder = "users_images/";
                $user_folder = $base_folder . $login;

                $avatar_path = $login . 'avatar/_avatar.jpg';
                $banner_path = $login . 'banner/_banner.jpg';
                if ($conn->query("INSERT INTO gallery_users VALUES (NULL, '$login', '$pass_hash', '$email' ,'$gender',' ')")) {
                    $_SESSION['success_regfulRegistration'] = true;
                    if (!file_exists($user_folder)) {

                        mkdir($user_folder, 0777, true);
                        mkdir($user_folder . '/avatar');
                        mkdir($user_folder . '/banner');
                        $source_path = 'tools/no-avatar-image.jpg';

                        $destination_path_avatar =  $user_folder . '/avatar/no_avatar.jpg';
                        copy($source_path, $destination_path_avatar);

                        $source_path = 'tools/no-banner-image.jpg';

                        $destination_path_banner = $user_folder . '/banner/no_banner.jpg';
                        copy($source_path, $destination_path_banner);
                    }

                    header('Location: welcome.php');
                } else {
                    echo 'Nie udalo sie';
                }
            }

            $conn->close();
        }
    } catch (Exception $e) {
        echo '<div class="error"> Błąd serwera</div>';
        echo $e;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <link rel="stylesheet" href="form.css">
</head>

<body>

    <main>
        <span>Account Registration</span>
        <form method="post">
            <input type="text" name="login" value="<?php
                                                    if (isset($_SESSION['fr_login'])) {
                                                        echo $_SESSION['fr_login'];
                                                        unset($_SESSION['fr_login']);
                                                    }
                                                    ?>" placeholder="Login">
            <?php
            if ((isset($_SESSION['e_login']))) {
                echo '<div class="error">' . $_SESSION['e_login'] . '</div>';
                unset($_SESSION['e_login']);
            }
            ?>
            <input type="email" name="email" placeholder="E-mail" value="
        <?php
        if (isset($_SESSION['fr_email'])) {
            echo $_SESSION['fr_email'];
            unset($_SESSION['fr_email']);
        }
        ?>">
            <?php
            if ((isset($_SESSION['e_email']))) {
                echo '<div class="error">' . $_SESSION['e_email'] . '</div>';
                unset($_SESSION['e_email']);
            }
            ?>
            <input type="password" name="pass" placeholder="Password">
            <?php
            if ((isset($_SESSION['e_pass']))) {
                echo '<div class="error">' . $_SESSION['e_pass'] . '</div>';
                unset($_SESSION['e_pass']);
            }
            ?>
            <input type="password" name="repeat_pass" placeholder="Repeat password">
            <p>Gender:</p>
            <label>Male<input type="radio" id="male" name="gender" value="male">
                Female<input type="radio" id="female" name="gender" value="female">
                <?php
                if ((isset($_SESSION['e_gender']))) {
                    echo '<div class="error">' . $_SESSION['e_gender'] . '</div>';
                    unset($_SESSION['e_gender']);
                }
                ?>
            </label>
            <br> <br>
            <label id="checkbox">
                <input sty type="checkbox" name="regulations" <?php
                                                                if (isset($_SESSION['fr_regulations'])) {
                                                                    echo "checked";
                                                                    unset($_SESSION['fr_regulations']);
                                                                }
                                                                ?> /> Accept regulations
            </label>
            <?php
            if ((isset($_SESSION['e_regulations']))) {
                echo '<div class="error">' . $_SESSION['e_regulations'] . '</div>';
                unset($_SESSION['e_regulations']);
            }
            ?>
            <div class="text-xs-center">
                <div class="g-recaptcha" id="captchar" data-sitekey="6Le8cL8kAAAAAH1E_dpFGWCRiEQpUwwT75mAUsam"></div>
                <?php
                if ((isset($_SESSION['e_bot']))) {
                    echo '<div class="error">' . $_SESSION['e_bot'] . '</div>';
                    unset($_SESSION['e_bot']);
                }
                ?>
            </div>
            <br />
            <input type="submit" value="Sign up" name="signUp">
            <a href="login.php"><input id="registerButton" type="button" value="Login"></a>
        </form>
        <?php
        if (isset($_SESSION['blad'])) {
            echo $_SESSION['blad'];
        }
        ?>
    </main>
</body>

</html>