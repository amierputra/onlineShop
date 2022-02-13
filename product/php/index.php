<?php
include_once("dbconnect.php");
session_start();

$useremail = "Guest";
$username = "Guest";

if (isset($_SESSION['sessionid'])) {
    $useremail = $_SESSION['email'];
    $username = $_SESSION['name'];
}

if (isset($_GET['submit'])) {
    include_once("dbconnect.php");
    if ($_GET['submit'] == "cart") {
        if ($useremail != "Guest") {
            $prodid = $_GET['prodid'];
            $cartqty = "1";

            $stmt = $conn->prepare("SELECT * FROM tbl_carts WHERE email = '$useremail' AND prod_id = '$prodid'");
            $stmt->execute();
            $number_of_rows = $stmt->rowCount();
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $rows = $stmt->fetchAll();
            if ($number_of_rows > 0) {

                $cartqty = $cartqty + 1;
                $updatecart = "UPDATE `tbl_carts` SET `cart_qty`= '$cartqty' WHERE email = '$useremail' AND prod_id = '$prodid'";
                $conn->exec($updatecart);
                echo "<script>alert('Cart updated')</script>";
                echo "<script> window.location.replace('index.php')</script>";
            } else {
                $addcart = "INSERT INTO `tbl_carts`(`email`, `prod_id`, `cart_qty`) VALUES ('$useremail', '$prodid', '$cartqty')";
                try {
                    $conn->exec($addcart);
                    echo "<script>alert('Success')</script>";
                } catch (PDOException $e) {
                    echo "<script>alert('Failed')</script>";
                }
            }
        } else {
            echo "<script>alert('Please login or register')</script>";
            echo "<script> window.location.replace('login.php')</script>";
        }
    }
}

$sqlquery = "SELECT * FROM `tbl_product`";
$stmt = $conn->prepare($sqlquery);
$stmt->execute();
$result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
$rows = $stmt->fetchAll();

$results_per_page = 10;
if (isset($_GET['pageno'])) {
    $pageno = (int)$_GET['pageno'];
    $page_first_result = ($pageno - 1) * $results_per_page;
} else {
    $pageno = 1;
    $page_first_result = 0;
}

$stmt = $conn->prepare($sqlquery);
$stmt->execute();
$result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
$rows = $stmt->fetchAll();
$number_of_result = $stmt->rowCount();
$number_of_page = ceil($number_of_result / $results_per_page);
$sqlquery = $sqlquery . " LIMIT $page_first_result , $results_per_page";
$stmt = $conn->prepare($sqlquery);
$stmt->execute();
$result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
$rows = $stmt->fetchAll();
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

    <header class="w3-container w3-center w3-padding-32 w3-white">
        <h1 class="font"><b>TEMYRACLE</b></h1>
        <h4 class="w3-large">Invest your skin a little <span class="w3-tag w3-red">Love!</span></h4>
    </header>

    <div class="w3-main w3-content w3-padding" style="max-width:1200px;margin-top:100px">
        <div class="w3-container w3-center">
            <p>Welcome <?php echo $username ?> </p>
        </div>
    </div>

    <div class="w3-container w3-content w3-padding-12" style="max-width:1200px;">
        <h4 class="w3-padding"><b>PRODUCT</b></h4>
        <div class="w3-grid-template">
            <?php
            $cart = "cart";
            foreach ($rows as $prod) {
                $prodid = $prod['prod_id'];
                $prodname = $prod['prod_name'];
                $prodnum = $prod['prod_num'];
                $proddesc = $prod['prod_desc'];
                $prodprice = $prod['prod_price'];

                echo "<div class='w3-center w3-padding-small'>
                    <div class='w3-card w3-round-large'>
                        <div class='w3-padding-small'>
                        <a href='details.php?prodid=$prodid'>
                        <img class='w3-container w3-image' src=../images/products/$prodnum.jpg onerror=this.onerror=null;this.src='../images/products/error.jpg'></a></div>
                        <b>$prodname</b><br>RM $prodprice<br>
                        <a href='index.php?prodid=$prodid&submit=$cart' class='w3-btn w3-red w3-round'>Add to Cart</a><br><br>
                    </div>
                </div>";
            }
            ?>
        </div>
    </div>
    <br>
    <?php
    $num = 1;
    if ($pageno == 1) {
        $num = 1;
    } else if ($pageno == 2) {
        $num = ($num) + $results_per_page;
    } else {
        $num = $pageno * $results_per_page - 9;
    }
    echo "<div class='w3-container w3-row'>";
    echo "<center>";
    for ($page = 1; $page <= $number_of_page; $page++) {
        echo '<a href = "index.php?pageno=' . $page . '" style=
            "text-decoration: none">&nbsp&nbsp' . $page . ' </a>';
    }
    echo " ( " . $pageno . " )";
    echo "</center>";
    echo "</div>";
    ?>
    <br>
</body>

<footer class="w3-center w3-padding w3-red">
    <p>
        &copy; Designed by
        <a href="https://amierputra.work" style="color: yellow; text-decoration:none;">Amier Putra</a>.<br><b>DISCLAIMER: This is for learning purpose only.</b><br>All Rights Reserved.<b>
    </p>
</footer>

</html>