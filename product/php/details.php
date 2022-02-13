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

$prodid = $_GET['prodid'];
$sqlquery = "SELECT * FROM tbl_product WHERE prod_id = $prodid";
$stmt = $conn->prepare($sqlquery);
$stmt->execute();
$result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
$rows = $stmt->fetchAll();

foreach ($rows as $prod) {
    $prodid = $prod['prod_id'];
    $prodname = $prod['prod_name'];
    $prodnum = $prod['prod_num'];
    $proddesc = $prod['prod_desc'];
    $proding = $prod['prod_ing'];
    $prodprice = $prod['prod_price'];
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
    <title>Details</title>
</head>

<body>
    <!-- NAVIGATION -->
    <div class="w3-padding-24">
        <div class="w3-bar w3-bottombar w3-cell-middle w3-top w3-white w3-border-red w3-padding-12">
            <a href="index.php" class="w3-bar-item w3-large w3-wide pointer" style="font-family: Poppins; text-decoration:none;"><b>HOME</b></a>
            <a href="login.php" class="w3-bar-item w3-large w3-right " style="font-family: Poppins; text-decoration:none;">Login</a>
            <a href="mypayment.php" class="w3-bar-item w3-large w3-right " style="font-family: Poppins; text-decoration:none;">My Payment</a>
            <a href="cart.php" class="w3-bar-item w3-large w3-right " style="font-family: Poppins; text-decoration:none;">Cart</a>
        </div>
    </div>

    <!-- Content -->
    <div class="w3-main w3-content w3-padding" style="max-width:1200px;margin-top:100px">

        <div class="w3-row w3-card w3-round-large">
            <div class="w3-half w3-center">
                <img class="w3-image w3-margin w3-center" style="height:100%;width:100%;max-width:330px" src="../images/products/<?php echo $prodnum ?>.jpg">
            </div>
            <div class="w3-half w3-container">
                <?php
                $cart = "cart";
                echo "<h3><b>$prodname</h3></b>
                    <p style='font-style: italic;'>$proddesc</p>
                    <p>Ingredients: $proding</p>
                    <p style='font-size:160%;'>RM $prodprice </p>
                    <p> <a href='index.php?prodid=$prodid&submit=$cart' class='w3-btn w3-red w3-round'>Add to Cart</a><p><br>
                    ";
                ?>
            </div>
        </div>
        <br>
    </div>
</body>

<footer class="w3-center w3-padding w3-red">
    <p>
        &copy; Designed by
        <a href="https://amierputra.work" style="color: yellow; text-decoration:none;">Amier Putra</a>.<br><b>DISCLAIMER: This is for learning purpose only.</b><br>All Rights Reserved.<b>
    </p>
</footer>

</html>