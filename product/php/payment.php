<?php
error_reporting(0);
session_start();
if (isset($_SESSION['sessionid']))
{
    $useremail = $_SESSION['email'];
    $user_name = $_SESSION['name'];

}else{
    echo "<script>alert('Please login or register')</script>";
    echo "<script> window.location.replace('login.php')</script>";
}

$email = $_GET['email']; //email
$amount = $_GET['amount']; 

$api_key = 'ad14f62f-58c0-4237-abd2-2fb8d360ef1f';
$collection_id = '_w5qpmpc';
$host = 'https://billplz-staging.herokuapp.com/api/v3/bills';

$data = array(
          'collection_id' => $collection_id,
          'email' => $useremail,
          'name' => $user_name,
          'amount' => ($amount + 1) * 100, // RM20
		  'description' => 'Payment for order by '.$email,
          'callback_url' => "http://amierputra.work/product/php/return_url",
          'redirect_url' => "http://amierputra.work/product/php/paymentupdate.php?email=$email&amount=$amount" 
);

$process = curl_init($host );
curl_setopt($process, CURLOPT_HEADER, 0);
curl_setopt($process, CURLOPT_USERPWD, $api_key . ":");
curl_setopt($process, CURLOPT_TIMEOUT, 30);
curl_setopt($process, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($process, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($process, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($process, CURLOPT_POSTFIELDS, http_build_query($data) ); 

$return = curl_exec($process);
curl_close($process);

$bill = json_decode($return, true);
header("Location: {$bill['url']}");
