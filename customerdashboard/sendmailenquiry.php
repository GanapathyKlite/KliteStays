<?php
session_start();
			$name="krishna";
			$email="krishnarajan26591@gmail.com";
			$gender="male";
			$phone="9500297295";
			$web=$_"IT";
			$dob="26-05-1991";
			$msg="pondy";
			if($name!="" && $email!="" && $phone!="" && $msg!="")
			{
			
			$subject="Enquiry Info";
			$to= "krishnarajan26591@gmail.com";
			$headers = 'MIME-Version: 1.0'."\r\n"; 
			$headers .= 'Content-type: text/html; charset=iso-8859-1'."\r\n"; 
			$headers .= "From:".$name."<".$email.">"."\r\n";
			$message = '<HTML><BODY><TABLE width=650 align=center cellpadding=0 cellspacing=0 border=0>
						<TR><TD align=center height=30 align=right>Name : </TD><TD height=30>&nbsp;&nbsp;'.$name.'</TD></TR>			
						<TR><TD align=center height=30 align=right>Email : </TD><TD height=30>&nbsp;&nbsp;'.$email.'</TD></TR>
						<TR><TD align=center height=30 align=right>Gender : </TD><TD height=30>&nbsp;&nbsp;'.$gender.'</TD></TR>
						<TR><TD align=center height=30 align=right>Phone : </TD><TD height=30>&nbsp;&nbsp;'.$phone.'</TD></TR>
						<TR><TD align=center height=30 align=right>Department : </TD><TD height=30>&nbsp;&nbsp;'.$web.'</TD></TR>
						<TR><TD align=center height=30 align=right>Date of Birth : </TD><TD height=30>&nbsp;&nbsp;'.$dob.'</TD></TR>						
						<TR><TD align=center height=30 align=right>Message : </TD><TD height=30>&nbsp;&nbsp;'.$msg.'</TD></TR>	
						</table>
						</CENTER></BODY>
						</HTML>';
						
	

				

		
			mail($to,$subject,$message,$headers);
		
				echo "Mail successfully sent. You will be redirected shortly...";
			
			
			
			
		
			}
			
		
			
			else
			{
				echo "OOPS! We are sorry for the inconvenience. Please try again. You will be redirected shortly...";
				
				echo "<META HTTP-EQUIV='Refresh' CONTENT='3; URL=http://arenggc.com/contact.html'>";
			}
//echo $message;			
?>