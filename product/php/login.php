<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '/home/amierputra/public_html/PHPMailer/src/Exception.php';
require '/home/amierputra/public_html/PHPMailer/src/PHPMailer.php';
require '/home/amierputra/public_html/PHPMailer/src/SMTP.php';

if (isset($_POST["submit"])) {
    include 'dbconnect.php';
    $email = $_POST["email"]; 
    $pass = sha1($_POST["password"]);
    $otp = '1';
    $stmt = $conn->prepare("SELECT * FROM tbl_user WHERE email = '$email' AND password = '$pass' AND user_otp='$otp'");
    $stmt->execute();
    $number_of_rows = $stmt->rowCount();
    $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $rows = $stmt->fetchAll();
    if ($number_of_rows  > 0) {
        foreach ($rows as $user) {
            $user_name = $user['name'];
        }
        session_start();
        $_SESSION["sessionid"] = session_id();
        $_SESSION["email"] = $email;
        $_SESSION["name"] = $user_name;

        echo "<script>alert('Login Success');</script>";
        echo "<script> window.location.replace('index.php')</script>";
    } else {
        echo "<script>alert('Login Failed');</script>";
        echo "<script> window.location.replace('login.php')</script>";
    }
}

if (isset($_POST["reset"])) {
    $email = $_POST["email"];
    sendMail($email);
    echo "<script>alert('Check your email');</script>";
    echo "<script> window.location.replace('login.php')</script>";
}


function sendMail($email)
{
    $mail = new PHPMailer(true);
    $mail->SMTPDebug = 0;
    $mail->isSMTP();
    $mail->Host       = '';         //mail server
    $mail->SMTPAuth   = true;
    $mail->Username   = '';         //email username
    $mail->Password   = '';         //email password
    $mail->SMTPSecure = 'tls';
    $mail->Port       = 587;
    $from = "";                     //email
    $to = $email;
    $subject = 'TEMYRACLE - Reset password request';
    $message = "<h2>You have requested to reset your password </h2> <p>Please click on the following link to reset your password. If your did not request for the reset. You can ignore this email<p>
    <p><button><a href ='https://amierputra.work/product/php/resetpass.php?email=$email'>Reset Password Here</a></button>";

    $mail->setFrom($from, "Temyracle");
    $mail->addAddress($to);

    //Content
    $mail->isHTML(true);
    $mail->Subject = $subject;
    $mail->Body    = $message;
    $mail->send();
}

?>

<!DOCTYPE html>
<html lang="ms">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="preconnect" href="https://fonts.googleapis.com/css?family=Montserrat|Poppins|Roboto">
    <link rel="stylesheet" type="text/css" href="../css/style.css">
    <script src="../jscript/script.js"></script>
    <title>TEMYRACLE | Login</title>
</head>

<style>
    @media screen and (min-width: 601px) {
        .form-container {
            max-width: 1000px;
            margin: auto;
        }
    }

    @media screen and (min-width: 601px) {
        .btn {
            max-width: 500px;
            margin: auto;
        }
    }

    @media screen and (min-width: 601px) {
        .field {
            max-width: 700px;
            margin: auto;
        }
    }

    .pointer {
        cursor: pointer;
    }
</style>

<body>
    <div class="w3-padding-24">
        <div class="w3-bar w3-bottombar w3-cell-middle w3-top w3-white w3-border-red w3-padding-12">
            <a href="index.php" class="w3-bar-item w3-large w3-wide pointer" style="font-family: Poppins; text-decoration:none;"><b>HOME</b></a>
            <a href="adminlog.php" class="w3-bar-item w3-large w3-text-red pointer" style="font-family: Poppins; text-decoration:none;">Admin</a>
            <a href="login.php" class="w3-bar-item w3-large w3-right " style="font-family: Poppins; text-decoration:none;">Login</a>
            <a href="mypayment.php" class="w3-bar-item w3-large w3-right " style="font-family: Poppins; text-decoration:none;">My Payment</a>
            <a href="cart.php" class="w3-bar-item w3-large w3-right " style="font-family: Poppins; text-decoration:none;">Cart</a>
        </div>
    </div>

    <header class="w3-container w3-center w3-padding-24 w3-white">
        <h1 class="font"><b>TEMYRACLE</b></h1>
        <h4 class="w3-large">Invest your skin a little <span class="w3-tag w3-red">Love!</span></h4>
    </header>

    <div class="w3-padding-64 w3-container" style="background-color: white;">
        <div class="w3-card form-container w3-round-large">
            <form class="w3-container w3-white w3-padding-24 w3-round-large formco" name="insertForm" action="login.php" method="post" onsubmit="return confirmDialogLog()" enctype="multipart/form-data">
                <div class="w3-margin-left w3-margin-right">
                    <h3 class="w3-center w3-text-black" style="font-family: Poppins;"><b>
                            LOGIN</b>
                    </h3>
                    <p class="field">
                        <label>Email</label>
                        <input class="w3-input w3-sand w3-border w3-round" name="email" id="email" type="email" required><br>
                    </p>
                    <p class="field">
                        <label>Password</label>
                        <input class="w3-input w3-sand w3-border w3-round" name="password" id="password" type="password" required>
                    </p>
                    <br>
                    <div class="btn w3-center">
                        <button class="pointer w3-input w3-red w3-text-sand w3-border w3-block w3-round w3-hover-yellow" name="submit">LOG IN</button>
                    </div>
                    <p class="w3-center">
                        Don't have an account? <a href="register.php" style="color: red; text-decoration:none;"><b>CLICK HERE</b>.</a><br>
                        Forgot your password? <a href="" style="color: red; text-decoration:none;" onclick="document.getElementById('id01').style.display='block';return false;"><b>CLICK HERE</b>.</a>
                    </p>
                </div>
            </form>
        </div>
    </div>

    <footer class="w3-center w3-padding w3-red">
        <p>
            &copy; Designed by
            <a href="https://amierputra.work" style="color: yellow; text-decoration:none;">Amier Putra</a>.<br><b>DISCLAIMER: This is for learning purpose only.</b><br>All Rights Reserved.<b>
        </p>
    </footer>

    <div id="id01" class="w3-modal">
        <div class="w3-modal-content" style="width:50%">
            <header class="w3-container w3-red">
                <span onclick="document.getElementById('id01').style.display='none'" class="w3-button w3-display-topright">&times;</span>
                <h4>Email to reset password</h4>
            </header>
            <div class="w3-container w3-padding">
                <form action="login.php" method="post">
                    <label><b>Email</b></label>
                    <input class="w3-input w3-border w3-round" name="email" type="email" id="idemail" required>
                    <p>
                        <button class="w3-btn w3-round w3-red" name="reset">Submit</button>
                    </p>
                </form>
            </div>
        </div>
    </div>
</body>

<script>
    function confirmDialogLog() {
        var r = confirm("Login?");
        if (r == true) {
            return true;
        } else {
            return false;
        }
    }
</script>

</html>
