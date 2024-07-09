<?php
include("../include/database/config.php");
require('razorpay-php/Razorpay.php');


use Razorpay\Api\Api;

$api = new Api($keyId, $keySecret);

$ReceiptSeriesID = 0;
global $database;
$ReceiptValuesArr = $database->query("select * from ps_receiptid_payment_reference where id=1")->fetchAll();
$ReceiptSeriesID = (int)$ReceiptValuesArr[0]['nextReceiptSeries'];
$database->exec('update ps_receiptid_payment_reference set nextReceiptSeries = nextReceiptSeries+ 1 where id=1');


//writeToLogFile ("Before ReceiptSeriesID:" . $ReceiptSeriesID);
$ReceiptSeriesID = $ReceiptSeriesID + 1;
//writeToLogFile ("After ReceiptSeriesID:" . $ReceiptSeriesID);

$receiptnos = "receipt_" . $ReceiptSeriesID;
//$_SESSION['receiptnos'] = $receiptnos;
//writeToLogFile("created ReceiptID:".$receiptnos);
//
// We create an razorpay order using orders api
// Docs: https://docs.razorpay.com/docs/orders
//
$orderData = [
    'receipt'         => $receiptnos,
    'amount'          => intval($_POST['amount']) * 100, // 2000 rupees in paise
    'currency'        => 'INR'
];

$razorpayOrder = $api->order->create($orderData);
$razorpayOrderId = $razorpayOrder['id'];
//writeToLogFile("Generated OrderID:".$razorpayOrderId);
echo (json_encode(array('razorpayOrderId'=>$razorpayOrderId,'receiptnos'=>$receiptnos)));
?>