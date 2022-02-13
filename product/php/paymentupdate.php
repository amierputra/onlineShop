<?php
error_reporting(0);
include_once("dbconnect.php");
$email = $_GET['email'];
$amount = $_GET['amount'];

$data = array(
    'id' =>  $_GET['billplz']['id'],
    'paid_at' => $_GET['billplz']['paid_at'] ,
    'paid' => $_GET['billplz']['paid'],
    'x_signature' => $_GET['billplz']['x_signature']
);

$paidstatus = $_GET['billplz']['paid'];
if ($paidstatus=="true"){
    $paidstatus = "Success";
}else{
    $paidstatus = "Failed";
}
$receiptid = $_GET['billplz']['id'];
$signing = '';
foreach ($data as $key => $value) {
    $signing.= 'billplz'.$key . $value;
    if ($key === 'paid') {
        break;
    } else {
        $signing .= '|';
    }
}
 
 
$signed= hash_hmac('sha256', $signing, '');    //enter x-signature key
if ($signed === $data['x_signature']) {
    if ($paidstatus == "Success"){ //payment success
        $sqlinsertpayment = "INSERT INTO `tbl_payments`(`payment_receipt`, `payment_email`, `payment_paid`) VALUES ('$receiptid','$email','$amount')";
        $sqlcart = "SELECT * FROM `tbl_carts` INNER JOIN tbl_product ON tbl_carts.prod_id = tbl_product.prod_id WHERE  tbl_carts.email = '$email'";
        $stmtcart= $conn->prepare($sqlcart);
        $stmtcart->execute();
        $number_of_rows = $stmtcart->rowCount();
        $rows = $stmtcart->fetchAll();
        if ($number_of_rows > 0)
        {
            foreach ($rows as $carts)
                {
                    $prodid = $carts['prod_id'];
                    $cartqty = (int)$carts['cart_qty'];
                    $prodprice = (double)$carts['prod_price'];
                    $totalprice = $prodprice * $cartqty;
                    $status = "Processing";
                    $sqlinsertorders = "INSERT INTO `tbl_orders`(`order_receiptid`, `order_prodid`, `order_custid`, `order_paid`, `order_qty`, `order_status`) VALUES ('$receiptid','$prodid','$email','$totalprice','$cartqty','$status')";
                    //$conn->exec($sqlinsertorders);
                    $stmt = $conn->prepare($sqlinsertorders);
                    $stmt->execute();
                }
        }
        $sqldeletecart = "DELETE FROM `tbl_carts` WHERE email = '$email'";
        try {
        $conn->exec($sqlinsertpayment);
        $stmt = $conn->prepare($sqldeletecart);
        $stmt->execute();
            echo "<script>alert('Payment successful')</script>";
            echo "<script>window.location.replace('https://amierputra.work/product/php/index.php')</script>";
        } catch (PDOException $e) {
            echo "<script>alert('Payment failed')</script>";
            echo "<script>window.location.replace('https://amierputra.work/product/php/index.php')</script>";
        }
    }
    else 
    {
        echo 'Payment Failed!';
    }
}
