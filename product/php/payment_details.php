<?php
include_once("dbconnect.php");
session_start();
if (isset($_SESSION['sessionid'])) {
    $useremail = $_SESSION['user_email'];
    $user_name = $_SESSION['user_name'];
} else {
    echo "<script>alert('Please login or register')</script>";
    echo "<script> window.location.replace('login.php')</script>";
}

$receiptid = $_GET['receipt'];
$sqlquery = "SELECT * FROM tbl_orders INNER JOIN tbl_product ON tbl_orders.order_prodid = tbl_product.prod_id WHERE tbl_orders.order_receiptid = '$receiptid'";
$stmt = $conn->prepare($sqlquery);
$stmt->execute();
$result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
$rows = $stmt->fetchAll();

function subString($str)
{
    if (strlen($str) > 15) {
        return $substr = substr($str, 0, 15) . '...';
    } else {
        return $str;
    }
}

?>


<!DOCTYPE html>
<html>

<head>
    <title>TEMYRACLE | Payment Details</title>
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
            foreach ($rows as $details) {
                $prodid = $details['prod_id'];
                $prodname = $details['prod_name'];
                $prodnum = $details['prod_num'];
                $proddesc = $details['prod_desc'];
                $prodprice = $details['prod_price'];
                $order_qty = $details['order_qty'];
                $order_paid = $details['order_paid'];
                $order_status = $details['order_status'];
                $totalpaid = ($order_paid * $order_qty) + $totalpaid;
                $order_date = date_format(date_create($details['order_date']), 'd/m/y h:i A');
                echo "<div class='w3-center w3-padding-small'>
                        <div class = 'w3-card w3-round-large'>
                                <div class='w3-padding-small'>
                                    <a href='details.php?prodid=$prodid'>
                                    <img class='w3-container w3-image' src=../images/products/$prodnum.jpg onerror=this.onerror=null;this.src='../images/products/error.jpg'></a>
                                </div>
                                <b>$prodname</b><br>RM $order_paid<br> $order_qty unit<br>
                        </div>
                    </div>";
            }

            ?>
        </div>
    </div>

    <div class="w3-container w3-center">
        <?php
        $totalpaid = number_format($totalpaid, 2, '.', '');
        echo "<div>
                    <br><hr>
                    <div><h4>Your Order</h4><p>Order ID: $receiptid<br>Name: $user_name <br>Total Paid: RM $totalpaid<br>Status: $order_status<br>Date Order: $order_date<p>
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