<?php
include('database/config.php');
$to = "lavanya123@mailinator.com";


$subject = "Enquiry from ".SITENAME;

$message = "
<html>
<head>
<title>Enquiry</title>
</head>
<body>
<table>

<tr>
<td>Name:</td>
<td>".$_POST['name']."</td>
</tr>

<tr>
<td>Email:</td>
<td>".$_POST['email']."</td>
</tr>

<tr>
<td>Mobile Number:</td>
<td>".$_POST['mobile']."</td>
</tr>


<tr>
<td>Message:</td>
<td>".$_POST['message']."</td>
</tr>

</table>
</body>
</html>
";

$messageforemp='';
$messageforemp.="
<html>
<head>
<title>From ".SITENAME."</title>
</head>
<body>
<p>You will Contact by our Team As soon as Possible.</p>
</body>
</html>
";

// Always set content-type when sending HTML email
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

// More headers
$headers .= 'From: <noreply@klitestays.com>' . "\r\n";

sendConfirmationMail($_POST['email'],"Thanks for Send Enquiry",$messageforemp);
sendConfirmationMail($to,$subject,$message);



?>
