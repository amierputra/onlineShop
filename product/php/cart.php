<?php

include_once("dbconnect.php");
session_start();

if (isset($_SESSION['sessionid'])) {
    $useremail = $_SESSION['email'];
    $username = $_SESSION['name'];
} else {
    echo "<script>alert('Please login or register')</script>";
    echo "<script> window.location.replace('login.php')</script>";
}

$sqlcart = "SELECT * FROM tbl_carts WHERE email = '$useremail'";
$stmt = $conn->prepare($sqlcart);
$stmt->execute();
$number_of_rows = $stmt->rowCount();
if ($number_of_rows > 0) {
    if (isset($_GET['submit'])) {
        if ($_GET['submit'] == "add") {
            $prodid = $_GET['prodid'];
            $qty = $_GET['qty'];
            $cartqty = $qty + 1;
            $updatecart = "UPDATE `tbl_carts` SET `cart_qty`= '$cartqty' WHERE email = '$useremail' AND prod_id = '$prodid'";
            $conn->exec($updatecart);
            echo "<script>alert('Cart updated')</script>";
        }
        if ($_GET['submit'] == "remove") {
            $prodid = $_GET['prodid'];
            $qty = $_GET['qty'];
            if ($qty == 1) {
                $updatecart = "DELETE FROM `tbl_carts` WHERE email = '$useremail' AND prod_id = '$prodid'";
                $conn->exec($updatecart);
                echo "<script>alert('Product removed')</script>";
            } else {
                $cartqty = $qty - 1;
                $updatecart = "UPDATE `tbl_carts` SET `cart_qty`= '$cartqty' WHERE email = '$useremail' AND prod_id = '$prodid'";
                $conn->exec($updatecart);
                echo "<script>alert('Removed')</script>";
            }
        }
    }
} else {
    echo "<script>alert('No item in your cart')</script>";
    echo "<script> window.location.replace('index.php')</script>";
}

$stmtqty = $conn->prepare("SELECT * FROM tbl_carts INNER JOIN tbl_product ON tbl_carts.prod_id = tbl_product.prod_id WHERE tbl_carts.email = '$useremail'");
$stmtqty->execute();
$resultqty = $stmtqty->setFetchMode(PDO::FETCH_ASSOC);
$rowsqty = $stmtqty->fetchAll();
foreach ($rowsqty as $carts) {
    $carttotal = $carts['cart_qty'] + $carttotal;
}

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
<html lang="ms">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="preconnect" href="https://fonts.googleapis.com/css?family=Montserrat|Poppins|Roboto">
    <link rel="stylesheet" type="text/css" href="../css/style.css">
    <script src="../jscript/script.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <title>TEMYRACLE | Home</title>
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

    <header class="w3-container w3-center w3-padding-24 w3-white">
        <h1 class="font"><b>TEMYRACLE</b></h1>
        <h4 class="w3-large">Invest your skin a little <span class="w3-tag w3-red">Love!</span></h4>
    </header>

    <div class="w3-main w3-content w3-padding" style="max-width:1200px;margin-top:100px">
        <div class="w3-container w3-center">
            <p>Cart for <?php echo $username ?> </p>
        </div>
        <hr>
        <div class="w3-grid-template">
            <?php

            $total_payable = 0.00;
            foreach ($rowsqty as $prod) {
                $prodid = $prod['prod_id'];
                $prodname = $prod['prod_name'];
                $prodnum = $prod['prod_num'];
                $proddesc = $prod['prod_desc'];
                $prodprice = $prod['prod_price'];
                $prod_qty = $prod['cart_qty'];
                $prod_total = $prod_qty * $prodprice;
                $total_payable = $prod_total + $total_payable;

                echo "<div class='w3-center w3-padding-small' id='prodcard_$prodid'>
                        <div class = 'w3-card w3-round-large'>
                            <div class='w3-padding-small'>
                                <a href='details.php?prodid=$prodid'><img class='w3-container w3-image' 
                                src=../images/products/$prodnum.jpg onerror=this.onerror=null;this.src='../images/products/error.jpg'></a>
                            </div>
                            <b>$prodname</b><br>RM $prodprice/unit<br>
                            <a href='cart.php?useremail=$useremail&prodid=$prodid&qty=$prod_qty&submit=remove' class='w3-btn w3-red w3-round'>-</a>
                            <label id='qtyid_$prodid'>$prod_qty</label>
                            <a href='cart.php?useremail=$useremail&prodid=$prodid&qty=$prod_qty&submit=add' class='w3-btn w3-green w3-round'>+</a><br>
                            <br>
                            <b><label id='prodprid_$prodid'> Price: RM $prod_total</label></b><br>
                        </div>
                    </div>";
            }
            ?>
        </div>
        <?php
        echo "<div class='w3-container w3-padding w3-block w3-center'><p><b><label id='totalpaymentid'> Total Amount Payable: RM $total_payable</label>
        </b></p><a href='payment.php?email=$useremail&amount=$total_payable' class='w3-button w3-round w3-red'> Pay Now </a> </div><br>";
        ?>
    </div>
</body>

<footer class="w3-center w3-padding w3-red">
    <p>
        &copy; Designed by
        <a href="https://amierputra.work" style="color: yellow; text-decoration:none;">Amier Putra</a>.<br><b>DISCLAIMER: This is for learning purpose only.</b><br>All Rights Reserved.<b>
    </p>
</footer>

<script>
    function addCart(prodid, prodprice) {
        jQuery.ajax({
            type: "GET",
            url: "cartajax.php",
            data: {
                prodid: prodid,
                submit: 'add',
                prodprice: prodprice
            },
            cache: false,
            dataType: "json",
            success: function(response) {
                var res = JSON.parse(JSON.stringify(response));
                console.log(res.data.carttotal);
                if (res.status = "success") {
                    var prodid = res.data.prodid;
                    document.getElementById("carttotalida").innerHTML = "Cart (" + res.data.carttotal + ")";
                    document.getElementById("carttotalidb").innerHTML = "Cart (" + res.data.carttotal + ")";
                    document.getElementById("qtyid_" + prodid).innerHTML = res.data.qty;
                    document.getElementById("prodprid_" + prodid).innerHTML = "Price: RM " + res.data.prodprice;
                    document.getElementById("totalpaymentid").innerHTML = "Total Amount Payable: RM " + res.data.totalpayable;
                } else {
                    alert("Failed");
                }

            }
        });
    }

    function removeCart(prodid, prodprice) {
        jQuery.ajax({
            type: "GET",
            url: "cartajax.php",
            data: {
                prodid: prodid,
                submit: 'remove',
                prodprice: prodprice
            },
            cache: false,
            dataType: "json",
            success: function(response) {
                var res = JSON.parse(JSON.stringify(response));
                if (res.status = "success") {
                    console.log(res.data.carttotal);
                    if (res.data.carttotal == null || res.data.carttotal == 0) {
                        alert("Cart empty");
                        window.location.replace("index.php");
                    } else {
                        var prodid = res.data.prodid;
                        document.getElementById("carttotalida").innerHTML = "Cart (" + res.data.carttotal + ")";
                        document.getElementById("carttotalidb").innerHTML = "Cart (" + res.data.carttotal + ")";
                        document.getElementById("qtyid_" + prodid).innerHTML = res.data.qty;
                        document.getElementById("prodprid_" + prodid).innerHTML = "Price: RM " + res.data.prodprice;
                        document.getElementById("totalpaymentid").innerHTML = "Total Amount Payable: RM " + res.data.totalpayable;
                        console.log(res.data.qty);
                        if (res.data.qty == null) {
                            var element = document.getElementById("prodcard_" + prodid);
                            element.parentNode.removeChild(element);
                        }
                    }

                } else {
                    alert("Failed");
                }

            }
        });
    }
</script>

</html>