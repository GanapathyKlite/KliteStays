<?php

if(!$_SERVER['HTTP_X_REQUESTED_WITH']== 'XMLHttpRequest')
{
   header("Location:index.php");
}
else
{

	session_start();
	error_reporting(E_ALL);
	

	include('include/database/config.php');
	//$date= new DateTime('now', new DateTimeZone('Asia/Kolkata'));
	date_default_timezone_set('Asia/Kolkata');
	 $dateadd = date("Y-m-d H:i:s");
	
 function sendSMS($mobile,$msg) 
{ 
	if($mobile!='')
	{
		$username = 'rajagagendran'; 
		$password = 'tams#$%^'; 
		$sender	  = 'BUDDYT'; 
		//$msg = $_POST['msg']; 
		//$msg = $_POST['msg']; 
		$priority = 'ndnd';
		$stype = 'normal';     
		$content =  'user='.rawurlencode($username). 
					'&pass='.rawurlencode($password). 
					'&sender='.rawurlencode($sender). 
					'&phone='.rawurlencode($mobile). 
					'&text='.rawurlencode($msg).
					'&priority='.rawurlencode($priority).
					'&stype='.rawurlencode($stype);
		
		$ch = curl_init('http://bhashsms.com/api/sendmsg.php'); 
        curl_setopt($ch, CURLOPT_POST, true); 
        curl_setopt($ch, CURLOPT_POSTFIELDS, $content); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
        $output = curl_exec ($ch); 
        curl_close ($ch); 
        return $output;
	}
	else
	{
		return json_encode(array(array("responseCode"=>"error")));	
	}   
} 
	
	if($_POST['getmods']=="savecustomer")
	{
      	include('include/securimage/securimage.php');

            $securimage = new Securimage();
            if(isset($_POST['captcha_code'])){
            	if ($securimage->check($_POST['captcha_code']) == false) {
               echo json_encode(array('msg'=>'captcha','id_customer'=>''));
                  die;
            }
            }
            
       
		$dateadd = date("Y-m-d H:i:s");
		

		$values = array(
					'username' =>(isset($_POST['username'])?$_POST['username']:''), 
					'password' =>(isset($_POST['password'])?$_POST['password']:''), 
					'first_name' => (isset($_POST['first_name'])?$_POST['first_name']:''), 
					'last_name' => (isset($_POST['last_name'])?$_POST['last_name']:''), 
					'address' => (isset($_POST['address'])?$_POST['address']:''), 
					'pan_no' => (isset($_POST['pan_no'])?$_POST['pan_no']:''),
					'gst_no' => (isset($_POST['gst_no'])?$_POST['gst_no']:''),
					'city' => (isset($_POST['city'])?$_POST['city']:''), 
					'pincode' => (isset($_POST['pin_code'])?$_POST['pin_code']:''), 
					'id_state' => (isset($_POST['state'])?$_POST['state']:''),
					'mobile' => (isset($_POST['mobile_no'])?$_POST['mobile_no']:''), 
					'email' => (isset($_POST['email_id'])?$_POST['email_id']:''), 
					'date_add' => $dateadd, 
					'date_upd' => $dateadd,
					);

	$checkcustomerem1=$database->query("select id_customer,first_name, mobile, email, username from ps_customers where (mobile='".(isset($_POST['mobile_no'])?$_POST['mobile_no']:'')."' or email='".(isset($_POST['email_id'])?$_POST['email_id']:'')."') and is_delete=1  LIMIT 1 ")->fetchAll(PDO::FETCH_ASSOC);
		
	//	print_r($checkcustomerem1);
		foreach($checkcustomerem1 as $checkcustomerem){
			
			
			$customernamec=$checkcustomerem['first_name'];
			 $mobilec=$checkcustomerem['mobile'];
			$emailc=$checkcustomerem['email'];
		//$usernamec=$checkcustomerem['username'];
		
			
			/*if( $_POST['first_name'] == $customernamec)
			{
				
				echo "customer";
				exit();
				
			}
			else*/
			if($mobilec)
			{
				echo json_encode(array('msg'=>'mobile','id_customer'=>$checkcustomerem['id_customer']));

				die();
				
			}
			
			elseif($emailc)
			{
				echo json_encode(array('msg'=>'email','id_customer'=>$checkcustomerem['id_customer']));
				die();
			}
			
		
			
			}


		//echo "select * FROM ps_state where id_state='".$_POST['state']."' and active=1 ORDER BY name";
				$states = $database->query("select * FROM ps_state where id_state='".(isset($_POST['state'])?$_POST['state']:'')."' and active=1 ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);
			
						$lastID = $database->insert('ps_customers', $values);
						$isd =str_pad($lastID, 7, '0', STR_PAD_LEFT);
						
					 $reference = 'OTO'.$isd;

					  $first_name =(isset($_POST['first_name'])?$_POST['first_name']:'');

					$updateuser_resulta = $database->query("update ps_customers set reference='".$reference."', password='". password_hash($_POST['password'],PASSWORD_DEFAULT)."' where id_customer='".$lastID."'")->fetchAll();

					//$agimgreference = (substr($_POST['customer_name'],0,2)).$lastID;



						
					if ($_POST['email_id']!="")  {
					  $adminemail="noreply@klitestays.com";

					   $to_email = $_POST['email_id'];
					   
					   
					   $subject = "Your registration application number is ".$reference;

					     $message=  file_get_contents('basic.html'); 

					 require_once('PHPMailer/class.phpmailer.php');
						require_once('PHPMailer/class.smtp.php'); 
					  $mail             = new PHPMailer();
					$message = str_replace(array('{customername}'),array($_POST['first_name']),$message);
					$message = str_replace(array('[username]'),array($_POST['email_id']),$message);
					$message = str_replace(array('[password]'),array($_POST['password']),$message);
					$body             = $message;

					$body             = preg_replace('/\[.*\]/','',$body);

					$mail->IsSMTP();

					$mail->isHTML(true);
					$mail->Host       = "noreply@klitestays.com"; // SMTP server
					$mail->SMTPDebug  = 0;                     // enables SMTP debug information (for testing)
					                                           // 1 = errors and messages
					                                           // 2 = messages only
					$mail->SMTPAuth   = true;                  // enable SMTP authentication
					$mail->Host       = "klitestays.com"; // sets the SMTP server
					$mail->Port       = 587;                    // set the SMTP port for the GMAIL server
					$mail->Username   = "noreply@klitestays.com"; // SMTP account username
					$mail->Password   = "Noreply@123";        // SMTP account password

					$mail->SetFrom($adminemail, "Noreply-Buddiestours");

					//$mail->AddReplyTo("name@yourdomain.com","First Last");

					$mail->Subject    = $subject;

					$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!";

					$mail->MsgHTML($body);
					$mail->WordWrap = 50;
					$mail->CharSet="utf-8";
					$address = $to_email;
					//$address1 = "admin@buddiestours.in";
					$mail->AddAddress($address, $subject);


					if(!$mail->Send()) {
						
					  echo "Mailer Error: " . $mail->ErrorInfo;
					} else {
					$mobile=rtrim($_POST['mobile_no']);	
					$msg="Thank you for your registration.";
					//$send_sms =  sendsms($mobile,$msg);
					$send_sms =  smsAPICall($msg,$mobile);

					
					}
					$auth_result = $database->query("select * FROM ps_customers where id_customer='".$lastID."' and is_active=0")->fetchAll(PDO::FETCH_ASSOC);
					$_SESSION['authtnid']=$auth_result[0]['id_customer'];
					$_SESSION['first_name']=$auth_result[0]["first_name"];
					$_SESSION['username']=$auth_result[0]["username"];
					$_SESSION['reference']=$auth_result[0]["reference"];

					echo json_encode(array('msg'=>'success','id_customer'=>$lastID));

					  
				}


  

	}
	

	
	else if($_POST['getmods']=="customerdetailsupdate")
	{
	
	
		$id_customer=$_POST['id_customer'];



		$updatecustomer_result = $database->exec("update ps_customers set  first_name='".$_POST['first_name']."',last_name='".$_POST['last_name']."',address='".$_POST['address']."', city='".$_POST['city']."', pincode='".$_POST['pin_code']."', pan_no='".$_POST['pan_no']."', id_state='".$_POST['state']."',id_country='".$_POST['id_country']."', mobile='".$_POST['mobile_no']."', email='".$_POST['email_id']."',  date_upd='".$dateadd."' where reference='".$_POST['customerrefid']."'");
		
	
		if($updatecustomer_result)
		{
			echo "1";
 }

				
					
					
					
	}
	else if($_POST['getmods']=="updatepassword")
	{
	



		$updatecustomer_result = $database->exec("update ps_customers set  password='".password_hash($_POST['password'], PASSWORD_DEFAULT)."' where reference='".$_POST['customerrefid']."'");

	if($updatecustomer_result)
		echo "1";


				
					
					
					
	}
	
	
	
	else if($_POST['getmods']=="gettabledatas")
	{
		$get_customers=$database->query("select * from customers where status=1")->fetchAll();
		$s='';$i=1;
		foreach($get_customers as $customers)
		{//(".$i.",".count($get_mobilelist).")
			$s.="<tr><td>".$i."</td><td>".$customers['refid']."</td><td>".$customers['customername']."</td><td>".$customers['contactperson']."</td><td>".$customers['mobileno']."</td><td>".$customers['emailid']."</td><td>".$customers['address']."</td><td>".$customers['username']."</td><td>".$customers['password']."</td>";
			if($_SESSION['editaccess']==1){$s.="<td class='centertext'><i class='fa fa-edit' style='cursor:pointer;' onclick='confirm_edit_customer(".$customers['id'].");'></i></td>";}else{$s.="<td class='centertext'><i class='fa fa-edit' style='color:gray; cursor:default;'></i></td>";}
			if($_SESSION['deleteaccess']==1){$s.="<td class='centertext'><i class='fa fa-trash' style='cursor:pointer;' onclick='confirm_delete_customer(".$customers['id'].");'></i></td></tr>";}else{$s.="<td class='centertext'><i class='fa fa-trash' style='color:gray; cursor:default;'></i></td></tr>";}
			$i++;
		}
		echo $s;
	}
	else if($_POST['getmods']=="getcustomername")
	{
		$get_customers_name=$database->query("select customername from customers where id='".$_POST['userid']."'")->fetchAll();
		echo $get_customers_name[0][0];
	}
	else if($_POST['getmods']=="geteditcustomerdetailsdata")
	{
		$get_customers_details=$database->query("select * from customers where id='".$_POST['userid']."'")->fetchAll();
		foreach($get_customers_details as $customerdetails)
		{
			
		$s=$customerdetails['id']."||".$customerdetails['refid']."||".$customerdetails['customername']."||".$customerdetails['registerno']."||".$customerdetails['contactperson']."||".$customerdetails['landlineno']."||".$customerdetails['mobileno']."||".$customerdetails['fax']."||".$customerdetails['emailid']."||".$customerdetails['websitelink']."||".$customerdetails['address']."||".$customerdetails['city']."||".$customerdetails['pincode']."||".$customerdetails['state']."||".$customerdetails['country']."||".$customerdetails['pancardno']."||".$customerdetails['bankname']."||".$customerdetails['accountno']."||".$customerdetails['accholdername']."||".$customerdetails['ifsccode']."||".$customerdetails['branchname']."||".$customerdetails['username']."||".$customerdetails['alternateno']."||".$customerdetails['companylogo'];
		}
		echo $s;
	}
	else if($_POST['getmods']=="updatecustomer")
	{
		$updateuser_result = $database->query("update ps_customers set customername='".$_POST['customername']."', registerno='".$_POST['registerno']."', contactperson='".$_POST['contactperson']."', landlineno='".$_POST['landlineno']."', mobileno='".$_POST['mobileno']."', fax='".$_POST['fax']."', emailid='".$_POST['emailid']."', websitelink='".$_POST['websitelink']."', address='".$_POST['address']."', city='".$_POST['city']."', pincode='".$_POST['pincode']."', state='".$_POST['state']."', country='".$_POST['country']."', pancardno='".$_POST['pancardno']."', bankname='".$_POST['bankname']."', accountno='".$_POST['accountno']."', accholdername='".$_POST['accholdername']."', ifsccode='".$_POST['ifsccode']."', branchname='".$_POST['branchname']."',username='".$_POST['username']."',alternateno='".$_POST['alternateno']."' where id='".$_POST['eid']."'")->fetchAll();
		$updatecustomer_verify=$database->query("select count(*) from ps_customers where customername='".$_POST['customername']."' and registerno='".$_POST['registerno']."' and contactperson='".$_POST['contactperson']."' and landlineno='".$_POST['landlineno']."' and mobileno='".$_POST['mobileno']."' and fax='".$_POST['fax']."' and emailid='".$_POST['emailid']."' and websitelink='".$_POST['websitelink']."' and address='".$_POST['address']."' and city='".$_POST['city']."' and pincode='".$_POST['pincode']."' and state='".$_POST['state']."' and country='".$_POST['country']."' and pancardno='".$_POST['pancardno']."' and bankname='".$_POST['bankname']."' and accountno='".$_POST['accountno']."' and accholdername='".$_POST['accholdername']."' and ifsccode='".$_POST['ifsccode']."' and branchname='".$_POST['branchname']."'")->fetchAll();
		echo $updatecustomer_verify[0][0];
	}
	else if($_POST['getmods']=="deletecustomerdetailsdata")
	{
		$delete_customers_details=$database->query("delete from customers where id='".$_POST['userid']."'")->fetchAll();
		$verify_customers_delete=$database->query("select count(*) from customers where id='".$_POST['userid']."'")->fetchAll();
		echo $verify_customers_delete[0][0];
	}

	
	
	
	
	

	
	
	
}

?>