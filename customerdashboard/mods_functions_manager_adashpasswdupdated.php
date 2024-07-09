<?php
if(!$_SERVER['HTTP_X_REQUESTED_WITH']== 'XMLHttpRequest')
{
   header("Location:logout.php");
}
else
{
	session_start();
	error_reporting(0);
	include('../include/database/config.php');
	$date= new DateTime('now', new DateTimeZone('Asia/Kolkata'));
	if($_POST['getmods']=="customerpaswdup")
	{
	

	
		$updateuser_result = $database->query("update ps_customers set password='".password_hash($_POST['customerpaswd'],PASSWORD_DEFAULT)."' where id_customer='".$_POST['customerid']."'");
		
		if($updateuser_result){
				echo "1";
		}
	
		//$updatecustomer_verify=$database->query("select count(*) from customers where customername='".$_POST['customername']."' and registerno='".$_POST['registerno']."' and contactperson='".$_POST['contactperson']."' and landlineno='".$_POST['landlineno']."' and mobileno='".$_POST['mobileno']."' and fax='".$_POST['fax']."' and emailid='".$_POST['emailid']."' and websitelink='".$_POST['websitelink']."' and address='".$_POST['address']."' and city='".$_POST['city']."' and pincode='".$_POST['pincode']."' and state='".$_POST['state']."' and country='".$_POST['country']."' and pancardno='".$_POST['pancardno']."' and bankname='".$_POST['bankname']."' and accountno='".$_POST['accountno']."' and accholdername='".$_POST['accholdername']."' and ifsccode='".$_POST['ifsccode']."' and branchname='".$_POST['branchname']."'")->fetchAll();
		//echo $updatecustomer_verify[0][0];
	}
	else if($_POST['getmods']=="customerdetailsupdate")
	{
		//echo "update customers set emailid='".$_POST['customeremail']."', landlineno='".$_POST['customerlandno']."',mobileno='".$_POST['customermob']."', aternateno='".$_POST['customeraltmobno']."' where refid='".$_POST['customerref']."'";
		$updatecustomer_result = $database->query("update customers set email='".$_POST['customeremail']."', landlineno='".$_POST['customerlandno']."',phone='".$_POST['customermob']."', alternateno='".$_POST['customeraltmobno']."' where refid='".$_POST['customerref']."'")->fetchAll();
		echo "1";
	}
}
?>