<?php
include_once("dbconnect.php");
session_start();

if (isset($_SESSION['sessionid'])) {
    $useremail = $_SESSION['email'];
} else {
    $response = array('status' => 'failed', 'data' => null);
    sendJsonResponse($response);
    return;
}
if (isset($_GET['submit'])) {
    $prodid = $_GET['prod_id'];
    $prodprice = $_GET['prod_price'];
    $sqlqty = "SELECT * FROM tbl_carts WHERE email = '$useremail' AND prod_id = '$prodid'";
    $stmtsqlqty = $conn->prepare($sqlqty);
    $stmtsqlqty->execute();
    $resultsqlqty = $stmtsqlqty->setFetchMode(PDO::FETCH_ASSOC);
    $rowsqlqty = $stmtsqlqty->fetchAll();
    $prodcurqty = 0;
    foreach ($rowsqlqty as $prod) {
        $prodcurqty = $prod['cart_qty'] + $prodcurqty;
    }
    if ($_GET['submit'] == "add") {
        $cartqty = $prodcurqty + 1;
        $updatecart = "UPDATE `tbl_carts` SET `cart_qty`= '$cartqty' WHERE email = '$useremail' AND prod_id = '$prodid'";
        $conn->exec($updatecart);
    }
    if ($_GET['submit'] == "remove") {
        if ($prodcurqty == 1) {
            $updatecart = "DELETE FROM `tbl_carts` WHERE email = '$useremail' AND prod_id = '$prodid'";
            $conn->exec($updatecart);
        } else {
            $cartqty = $prodcurqty - 1;
            $updatecart = "UPDATE `tbl_carts` SET `cart_qty`= '$cartqty' WHERE email = '$useremail' AND prod_id = '$prodid'";
            $conn->exec($updatecart);
        }
    }
}


$stmtqty = $conn->prepare("SELECT * FROM tbl_carts INNER JOIN tbl_product ON tbl_carts.prod_id = tbl_product.prod_id WHERE tbl_carts.email = '$useremail'");
$stmtqty->execute();
//$resultqty = $stmtqty->setFetchMode(PDO::FETCH_ASSOC);
$rowsqty = $stmtqty->fetchAll();
$totalpayable = 0;
foreach ($rowsqty as $carts) {
    $carttotal = $carts['cart_qty'] + $carttotal;
    $prodpr = $carts['prod_price'] * $carts['cart_qty'];
    $totalpayable = $totalpayable + $prodpr;
}

$mycart = array();
$mycart['carttotal'] = $carttotal;
$mycart['prod_id'] = $prodid;
$mycart['qty'] = $cartqty;
$mycart['prod_price'] = bcdiv($cartqty * $prodprice, 1, 2);
$mycart['totalpayable'] = bcdiv($totalpayable, 1, 2);


$response = array('status' => 'success', 'data' => $mycart);
sendJsonResponse($response);


function sendJsonResponse($sentArray)
{
    header('Content-Type: application/json');
    echo json_encode($sentArray);
}
