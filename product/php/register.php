<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '/home/amierputra/public_html/PHPMailer/src/Exception.php';
require '/home/amierputra/public_html/PHPMailer/src/PHPMailer.php';
require '/home/amierputra/public_html/PHPMailer/src/SMTP.php';

if (isset($_POST["submit"])) {
    include_once("dbconnect.php");

    $name = $_POST["name"];
    $email = $_POST["email"];
    $pass = sha1($_POST["password"]);
    $otp = rand(10000, 99999);
    $sqlregister = "INSERT INTO `tbl_user`(`name`, `email`, `password`, `user_otp`) VALUES('$name', '$email', '$pass', '$otp')";
    try {
        $conn->exec($sqlregister);
        sendMail($email, $otp);
        echo "<script>alert('Registration successful.Please check your email.')</script>";
        echo "<script>window.location.replace('login.php')</script>";
    } catch (PDOException $e) {
        echo "<script>alert('Registration failed')</script>";
        echo "<script>window.location.replace('register.php')</script>";
    }
}

function sendMail($email, $otp)
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
    $subject = 'TEMYRACLE - Please verify your account';
    $message = "<h2>Welcome to Temyracle Online App</h2> <p>Thank you for registering your account with us. To complete your registration please click the following.<p>
    <p><button><a href ='https://amierputra.work/product/php/verifyaccount.php?email=$email&otp=$otp'>Verify Here</a></button>";

    $mail->setFrom($from, "Temyracle");
    $mail->addAddress($to);                                             //Add a recipient

    //Content
    $mail->isHTML(true);                                                //Set email format to HTML
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
    <title>TEMYRACLE | Register</title>
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
</style>

<body>
    <div class="w3-padding-24">
        <div class="w3-bar w3-bottombar w3-cell-middle w3-top w3-white w3-border-red w3-padding-12">
            <a href="index.php" class="w3-bar-item w3-large w3-wide pointer" style="font-family: Poppins; text-decoration:none;"><b>HOME</b></a>
            <a href="" class="w3-bar-item w3-large w3-right " style="font-family: Poppins; text-decoration:none;">About</a>
            <a href="" class="w3-bar-item w3-large w3-right " style="font-family: Poppins; text-decoration:none;">My Cart</a>
        </div>
    </div>

    <header class="w3-container w3-center w3-padding-24 w3-white">
        <h1 class="font"><b>TEMYRACLE</b></h1>
        <h4 class="w3-large">Invest your skin a little <span class="w3-tag w3-red">Love!</span></h4>
    </header>

    <div class="w3-padding-64 w3-container" style="background-color: white;">
        <div class="w3-card form-container w3-round-large">
            <form class="w3-container w3-white w3-padding-24 w3-round-large formco" name="insertForm" action="register.php" method="post" onsubmit="return confirmDialogReg()" enctype="multipart/form-data">
                <div class="w3-margin-left w3-margin-right">
                    <h3 class="w3-center w3-text-black" style="font-family: Poppins;"><b>
                            REGISTER</b>
                    </h3>
                    <p class="field">
                        <label>Name</label>
                        <input class="w3-input w3-sand w3-border w3-round" name="name" id="name" type="name" required><br>
                    </p>
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
                        <button class="pointer w3-input w3-red w3-text-sand w3-border w3-block w3-round w3-hover-yellow" name="submit">REGISTER</button>
                    </div>
                    <p class="w3-center">
                        Already have an account? <a href="login.php" style="color: red; text-decoration:none;"><b>CLICK HERE</b>.</a>
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
</body>

<script>
    function confirmDialogReg() {
        var r = confirm("Register?");
        if (r == true) {
            return true;
        } else {
            return false;
        }
    }
</script>

</html>