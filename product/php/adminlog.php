<?php


if (isset($_POST["submit"])) {
    include 'dbconnect.php';
    $email = $_POST["email"];
    $pass = sha1($_POST["password"]);
    $stmt = $conn->prepare("SELECT * FROM tbl_admin WHERE email = '$email' AND password = '$pass'");
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
        echo "<script> window.location.replace('newproduct.php')</script>";
    } else {
        echo "<script>alert('Login Failed');</script>";
        echo "<script> window.location.replace('adminlog.php')</script>";
    }
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
    <title>TEMYRACLE | ADMIN Login</title>
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
            <a href="adminlog.php" class="w3-bar-item w3-large w3-text-red pointer" style="font-family: Poppins; text-decoration:none; ">Admin</a>

        </div>
    </div>

    <header class="w3-container w3-center w3-padding-24 w3-white">
        <h1 class="font"><b>TEMYRACLE</b></h1>
        <h4 class="w3-large">Invest your skin a little <span class="w3-tag w3-red">Love!</span></h4>
    </header>

    <div class="w3-padding-64 w3-container" style="background-color: white;">
        <div class="w3-card form-container w3-round-large">
            <form class="w3-container w3-white w3-padding-24 w3-round-large formco" name="insertForm" action="adminlog.php" method="post" onsubmit="return confirmDialogLog()" enctype="multipart/form-data">
                <div class="w3-margin-left w3-margin-right">
                    <h3 class="w3-center w3-text-black" style="font-family: Poppins;"><b>
                            ADMIN LOGIN</b>
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