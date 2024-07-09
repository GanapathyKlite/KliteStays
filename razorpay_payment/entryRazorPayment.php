<?php
include("../include/database/config.php");
// print_r($_POST);die;
if($_POST['booking_type']=='holidays'){
$values = array(
    'id_customer' => $_SESSION['refid'],
    'razorpay_order_id'=>$_POST['razorpay_order_id'],
    'razorpay_payment_id'=>$_POST['razorpay_payment_id'],
    'razorpay_signature'=>$_POST['razorpay_signature'],
    'receipt_nos'=>$_POST['receiptnos'],
    'amount'=> (float)(($_POST['amount'])),    
    'payment_status' =>$_POST['payment_status'],
    'payment_type'=>'RazorPay',
    'is_manual' => 0,
    'booking_ref_no' => $_POST['booking_ref'],
    'productinfo' => 'Holiday Booking using RazorPay',
    'previous_balance' => $_SESSION['available_balance'],
    'current_balance' => $_SESSION['available_balance']

);
$res = $database->insert('payment_transactions', $values);

if($res!=0)
{
    echo "Success";
}
else
{
    echo "Failure";
}

}
elseif($_POST['booking_type']=='recharge'){
    $values = array(
    'id_customer' => $_SESSION['refid'],
    'razorpay_order_id'=>$_POST['razorpay_order_id'],
    'razorpay_payment_id'=>$_POST['razorpay_payment_id'],
    'razorpay_signature'=>$_POST['razorpay_signature'],
    'receipt_nos'=>$_POST['receiptnos'],
    'amount'=> (float)(($_POST['amount'])),    
    'payment_status' =>$_POST['payment_status'],
    'payment_type'=>'RazorPay',
    'is_manual' => 0,
    'productinfo' => 'customer Recharge using RazorPay',
    'previous_balance' => $_POST['previousBalance'],
    'current_balance' => $_POST['currentBalance']

);

$res = $database->insert('payment_transactions', $values);

if($res!=0)
{
    echo "Success";
}
else
{
    echo "Failure";
}
}
elseif($_POST['booking_type']=='flight_payment_gateway'){
    $values = array(
    'id_customer' => $_POST['id_customer'],
    'razorpay_order_id'=>$_POST['razorpay_order_id'],
    'razorpay_payment_id'=>$_POST['razorpay_payment_id'],
    'razorpay_signature'=>$_POST['razorpay_signature'],
    'receipt_nos'=>$_POST['receiptnos'],
    'amount'=>intval($_POST['amount']),
    'payment_status' =>$_POST['payment_status'],
    'payment_type'=>'RazorPay',
    'is_manual' => 0,
    //'booking_ref_no' => $_POST['booking_ref'],
    'productinfo' => 'Flight Booking using RazorPay',
    'previous_balance' => $_SESSION['available_balance'],
    'current_balance' => $_SESSION['available_balance']
);


writeToLogFile("Values for Payment Entry".PHP_EOL.json_encode($values));
$res = $database->insert('payment_transactions', $values);
writeToLogFile("Result Value of Insert Entry in payment_transaction:".$res);

if($res!=0)
{
   // echo "Success";
   echo '{"status":"Success","lastID":'.$res.'}';
}
else
{
    // echo "Failure";
    echo '{"status":"Failure","lastID":'.$res.'}';
}

}
elseif($_POST['booking_type']=='flight_wallet'){
    $values = array(
    'id_customer' => $_SESSION['refid'],
    'amount' => (float)(($_POST['amount'])),
    'payment_status' => 'Success',
    'payment_type' => 'Wallet',
    'is_manual' => 0,
    'productinfo' => 'Flight Booking using Wallet',
    'previous_balance' => $_POST['availableBalance'],
    'current_balance' => $_POST['availableBalance'] - $_POST['amount']
);




writeToLogFile("Values for Payment Entry" . PHP_EOL . json_encode($values));
$res = $database->insert('payment_transactions', $values);
writeToLogFile("Result Value of Insert Entry in payment_transaction:" . $res);

if ($res != 0) {
    // echo "Success";
    echo '{"status":"Success","lastID":' . $res . '}';
} else {
    // echo "Failure";
    echo '{"status":"Failure","lastID":' . $res . '}';
}
}



?>