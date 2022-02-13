<?php
include_once("dbconnect.php");
session_start();
if (isset($_SESSION['sessionid'])) {
    $useremail = $_SESSION['email'];
    $user_name = $_SESSION['name'];
} else {
    echo "<script>alert('Please login or register')</script>";
    echo "<script> window.location.replace('login.php')</script>";
}
$sqlpayment = "SELECT * FROM tbl_payments WHERE payment_email = '$useremail' ORDER BY payment_date DESC";
$stmt = $conn->prepare($sqlpayment);
$stmt->execute();
$number_of_rows = $stmt->rowCount();
$result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
$rows = $stmt->fetchAll();


?>
<!DOCTYPE html>
<html>

<head>
    <title>TEMYRACLE | My Payment</title>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="preconnect" href="https://fonts.googleapis.com/css?family=Montserrat|Poppins|Roboto">
    <link rel="stylesheet" type="text/css" href="../css/style.css">
    <script src="../jscript/script.js"></script>
</head>

<style>
    @media screen and (max-width: 600px) {
        .w3-grid-template {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
        }

        .w3-image {
            width: 100%;
            height: 150px;
            object-fit: fill;
        }
    }

    @media screen and (min-width: 601px) {
        .w3-grid-template {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
        }

        .w3-image {
            width: 100%;
            height: 200px;
            object-fit: fill;
        }
    }

    @media screen and (min-width: 1000px) {
        .w3-grid-template {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
        }

        .w3-image {
            width: 100%;
            height: 180px;
            object-fit: fill;
        }
    }
</style>

<body>
    <div class="w3-padding-24">
        <div class="w3-bar w3-bottombar w3-cell-middle w3-top w3-white w3-border-red w3-padding-12">
            <a href="index.php" class="w3-bar-item w3-large w3-wide pointer" style="font-family: Poppins; text-decoration:none;"><b>HOME</b></a>
            <a href="login.php" class="w3-bar-item w3-large w3-right " style="font-family: Poppins; text-decoration:none;">Login</a>
            <a href="mypayment.php" class="w3-bar-item w3-large w3-right " style="font-family: Poppins; text-decoration:none;">My Payment</a>
            <a href="cart.php" class="w3-bar-item w3-large w3-right " style="font-family: Poppins; text-decoration:none;">Cart</a>
        </div>
    </div>

    <header class="w3-container w3-center w3-padding-32 w3-white">
        <h1 class="font"><b>TEMYRACLE</b></h1>
        <h4 class="w3-large">Invest your skin a little <span class="w3-tag w3-red">Love!</span></h4>
    </header>

    <div class="w3-main w3-content w3-padding" style="max-width:1200px;margin-top:100px">
        <div class="w3-grid-template">
            <?php
            $totalpaid = 0.0;
            $count = 0;
            foreach ($rows as $payments) {
                $paymentid = $payments['payment_id'];
                $paymentreceipt = $payments['payment_receipt'];
                $paymentpaid = number_format($payments['payment_paid'], 2, '.', '');
                $totalpaid = $paymentpaid + $totalpaid;
                $count++;
                $paymentdate = date_format(date_create($payments['payment_date']), "d/m/Y h:i A");
                echo "<div class='w3-left w3-padding-small'>
                        <div class = 'w3-card w3-round-large w3-padding'>
                            <div class='w3-container w3-center w3-padding-small'><b>Receipt ID: $paymentreceipt</b>
                            </div>
                            <br>
                            Product ordered: $count<br>Paid: RM $paymentpaid<br>Date: $paymentdate<br>
                            <a style='text-decoration: none;' href='payment_details.php?receipt=$paymentreceipt' class='w3-button w3-red w3-round w3-block'>Details</a>
                            
                        </div>
                    </div>";
            }
            ?>
        </div>
    </div>
    <div class="w3-container w3-center">
        <?php
        $totalpaid = number_format($totalpaid, 2, '.', '');
        $totalpaid = number_format($totalpaid, 2, '.', '');
        echo "<div>
                <br><hr>
                <div>
                    <h4>Your Orders</h4>
                    <p>Name: $user_name <br>Total Paid: RM $totalpaid<p>
                </div>
                </div>";
        ?>
    </div>
    <br>

</body>

<footer class="w3-center w3-padding w3-red">
    <p>
        &copy; Designed by
        <a href="https://amierputra.work" style="color: yellow; text-decoration:none;">Amier Putra</a>.<br><b>DISCLAIMER: This is for learning purpose only.</b><br>All Rights Reserved.<b>
    </p>
</footer>

</html>