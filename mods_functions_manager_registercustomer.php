<?php

if (!$_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
    header("Location:index.php");
} else {

    session_start();
    error_reporting(E_ALL);


    include('include/database/config.php');

    // $_POST['email_id'];
    // $_POST['mobile_no'];
    // $_POST['password'];
    // $_POST['confirmPassword'];

    if ($_POST['getmods'] == "savecustomer") {
        
        $checkcustomerem1 = $database->query("select id_customer,mobile, email from ps_customers where (mobile='" . (isset($_POST['mobile']) ? $_POST['mobile'] : '') . "' or email='" . (isset($_POST['email']) ? $_POST['email'] : '') . "') and is_delete=0  LIMIT 1 ")->fetchAll(PDO::FETCH_ASSOC);

        //	print_r($checkcustomerem1);
        foreach ($checkcustomerem1 as $checkcustomerem) {

            $mobilec = $checkcustomerem['mobile'];
            $emailc = $checkcustomerem['email'];

            if ($mobilec) {
                echo json_encode(array('msg' => 'mobile', 'id_customer' => $checkcustomerem['id_customer']));
                die();
            } elseif ($emailc) {
                echo json_encode(array('msg' => 'email', 'id_customer' => $checkcustomerem['id_customer']));
                die();
            }
        }

        $forcustomer = array("username"=>$_POST['email'],"email" => $_POST['email'], "mobile" => $_POST['mobile'], "password" => password_hash($_POST['password'], PASSWORD_DEFAULT));
        $values = array_merge($forcustomer, array('is_delete' => 0, 'date_add' => date('Y-m-d H:i:s'), 'date_upd' => date('Y-m-d H:i:s')));

        $customerid = $database->insert('ps_customers', $values);

        if ($customerid > 0) {
            echo json_encode(array('msg'=>'success','id_customer'=>$customerid));
//            echo "alert('" . "Registration Successfull" . "')";
        } else {
            echo json_encode(array('msg'=>'failed','id_customer'=>$customerid));
            //echo "alert('" . "Registration Failed" . "')";
        }
    } 

}
?>