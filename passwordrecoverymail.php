<?php
//ini_set('error_reporting', E_ALL);
//error_reporting(~E_ALL);

//if "email" variable is filled out, send email
//echo "sss";
  include('include/database/config.php');
  $email=$_POST['email'];
  $get_password = $database->query("select first_name,reference from ps_customers where email='".$email."'")->fetchAll();
	   
	  if(count($get_password)>0)
      {
	   foreach($get_password as $get_passwordg){
		 $reference=$get_passwordg['reference'];
		$customernamec=$get_passwordg['first_name'];
		
		
	   }
	    $stext1="Click on the link below to Reset your password.<br>".$root_dir."customerpasswdchg.php?key=".$reference;
	  
  if ($_POST['email']!="")  {
	$adminemail="noreply@buddiestours.com";
  //Email information
   $to_email = $_POST['email'];
 // $admin_email = "someone@example.com";
   $subject = "PASSWORD RECOVERY INTIMATION";
 //  $comment = "Your password has been retrived as requested. <br>Following is your login credentials<br><b>Username</b> - ".$get_password[0][0]."<br><b>Password</b> - ".$get_password[0][1];
 $comment = "Dear partner, ".$customernamec."<br>".$stext1."";
  //echo "cc";
 require_once('PHPMailer/class.phpmailer.php');
	require_once('PHPMailer/class.smtp.php'); //echo "c0";
  $mail             = new PHPMailer();

$body             = $comment;
$body             = eregi_replace("[\]",'',$body);
//echo "c1";
$mail->IsSMTP(); // telling the class to use SMTP
//echo "c2";
$mail->Host       = "buddiestours.com"; // SMTP server
$mail->SMTPDebug  = 0;                     // enables SMTP debug information (for testing)
                                           // 1 = errors and messages
                                           // 2 = messages only
$mail->SMTPAuth   = true;                  // enable SMTP authentication
$mail->Host       = "buddiestours.com"; // sets the SMTP server
$mail->Port       = 587;                    // set the SMTP port for the GMAIL server
$mail->Username   = "noreply@buddiestours.com"; // SMTP account username
$mail->Password   = "noreply";        // SMTP account password

$mail->SetFrom($adminemail, "Buddiestours");

//$mail->AddReplyTo("name@yourdomain.com","First Last");

$mail->Subject    = $subject;

$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test

$mail->MsgHTML($body);

$address = $to_email;
//$address1 = "admin@buddiestours.in";
$mail->AddAddress($address, $subject);
//$mail->AddAddress($address1, $subject);

//$mail->AddAttachment("images/phpmailer.gif");      // attachment
//$mail->AddAttachment("images/phpmailer_mini.gif"); // attachment

if(!$mail->Send()) {
  echo "Mailer Error: " . $mail->ErrorInfo;
} else {
  echo "1";
}
  }
  
  
  }
   else
                    {
                        echo "notexist";
                    }

  
  
?>