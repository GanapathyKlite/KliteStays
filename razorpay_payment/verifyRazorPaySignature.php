<?php
include("../include/database/config.php");
require('razorpay-php/Razorpay.php');

function hmac_sha256($data, $key)
{
    return hash_hmac('sha256', $data, $key);
}

$order_id = $_POST['order_id'];
$razorpay_payment_id = $_POST['payment_id'];
$razorpay_signature = $_POST['razorpay_signature'];
$secret = $keySecret;

$generated_signature = hmac_sha256($order_id . "|" . $razorpay_payment_id, $secret);

//writeToLogFile("Generated Signature:".$generated_signature);
//writeToLogFile("Razor Pay Signature:".$razorpay_signature);
if (trim($generated_signature) == trim($razorpay_signature)) {
    echo "Success";
} else {
    echo "Failure";
}
