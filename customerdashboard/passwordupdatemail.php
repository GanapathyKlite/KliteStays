<?php
//ini_set('error_reporting', E_ALL);
//error_reporting(~E_ALL);

//if "email" variable is filled out, send email
//echo "sss";
  if ($_POST['email']!="")  {
   $adminemail="admin@buddiestours.in";
  //Email information
   $to_email = $_POST['email'];
 // $admin_email = "someone@example.com";
   $subject = "PASSWORD CHANGED INTIMATION";
   $comment = "Your password has been changed as requested. <br>Following is your login credentials<br><b>Username</b> - ".$_POST['username']."<br><b>Password</b> - ".$_POST['password'];
  //echo "cc";
 require_once('PHPMailer/class.phpmailer.php');
	require_once('PHPMailer/class.smtp.php'); //echo "c0";
  $mail             = new PHPMailer();

$body             = $comment;
$body             = eregi_replace("[\]",'',$body);
//echo "c1";
$mail->IsSMTP(); // telling the class to use SMTP
//echo "c2";
$mail->Host       = "buddiestours.in"; // SMTP server
$mail->SMTPDebug  = 2;                     // enables SMTP debug information (for testing)
                                           // 1 = errors and messages
                                           // 2 = messages only
$mail->SMTPAuth   = true;                  // enable SMTP authentication
$mail->Host       = "buddiestours.in"; // sets the SMTP server
$mail->Port       = 587;                    // set the SMTP port for the GMAIL server
$mail->Username   = "admin@buddiestours.in"; // SMTP account username
$mail->Password   = "admin2016";        // SMTP account password

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

  
  
?>