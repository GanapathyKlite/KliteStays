<?php
if(!$_SERVER['HTTP_X_REQUESTED_WITH']== 'XMLHttpRequest')
{
   header("Location:index.php");
}
else
{
	session_start();
	error_reporting(E_ALL);
	include('../include/database/config.php');
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
	
	if($_POST['getmods']=="saveagent")
	{
		
		
		
		
		
		
		/*
		$gen_b2bagval=$database->query("select agnextval from agnextval where id=1")->fetchAll();
		$agval1= $gen_b2bagval[0][0]+1;
		if($agval1>9999)
		{
			$updateb2bagvalsql1    = $database->query("select b2bagval from b2bagnextval where id=1")->fetchAll();
			$b2bvalupdate= $updateb2bagvalsql1[0][0]+1;
			$updateb2bsql2    = $database->query("update b2bagnextval set b2bagval='".$b2bvalupdate."' where id=1")->fetchAll();
			$updateagsql2    = $database->query("update agnextval set agnextval=1 where id=1")->fetchAll();
		}
		else
		{
			$updateagsql2    = $database->query("update agnextval set agnextval='".$agval1."' where id=1")->fetchAll();
		}
		$gen_b2bagval=$database->query("select b2bagval from b2bagnextval where id=1")->fetchAll();
		$b2bagval1=$gen_b2bagval[0][0];
		if(strlen($agval1)==1){ $agval1="000".$agval1;}
		if(strlen($agval1)==2){ $agval1="00".$agval1;}
		if(strlen($agval1)==3){ $agval1="0".$agval1;}
		
		if(strlen($b2bagval1)==1){ $b2bagval1="000".$b2bagval1;}
		if(strlen($b2bagval1)==2){ $b2bagval1="00".$b2bagval1;}
		if(strlen($b2bagval1)==3){ $b2bagval1="0".$b2bagval1;}
		
		$autogennumber="B2B".$b2bagval1."AG".$agval1;*/
		
		//echo $dateadd = date('Y-m-d H:i:s');
		
 $dateadd = date("Y-m-d H:i:s");
		
		//$insertuser_result = $database->query("insert into ps_agents  (username, password, agentname, pan_no, address, city, pincode, id_state, id_country, telephone, mobile, fax, email, website, date_add, date_upd) values('".$_POST['username']."','".$_POST['password']."','".$_POST['agentname']."','".$_POST['pan_number']."','".$_POST['address']."','".$_POST['city']."','".$_POST['pincode']."','".$_POST['websitelink']."','".$_POST['address']."','".$_POST['city']."','".$_POST['pincode']."','".$_POST['state']."','".$_POST['country']."','".$_POST['telephone_no']."',".$_POST['mobile_no']."','".$_POST['fax_no']."','".$_POST['email_id']."','".$_POST['website']."','".$_POST['dateadd']."','".$_POST['dateadd']."')")->fetchAll();
 //$insertuser_result = $database->query("insert into ps_agents (username, password, agentname, pan_no, address, city, pincode, id_state, id_country, telephone, mobile, fax, email, website, date_add, date_upd) values('".$_POST['username']."','".$_POST['password']."','".$_POST['agent_name']."','".$_POST['pan_number']."','".$_POST['address']."','".$_POST['city']."','".$_POST['pin_code']."','".$_POST['state']."','".$_POST['country']."','".$_POST['telephone_no']."','".$_POST['mobile_no']."','".$_POST['fax_no']."','".$_POST['email_id']."','".$_POST['website']."','".$dateadd."','".$dateadd."')")->fetchAll();
 //$lastId= $insertuser_result->pdo->lastInsertId();
//echo $insertuser_result[0][0];
//echo $last_insert_id[0][0] = $insertuser_result->pdo->lastInsertId();
//echo 1;
 //$lastId=$insertuser_result[0][0];
//print_r($lastId);

	$values = array(
					'username' => $_POST['username'], 
					'password' => $_POST['password'], 
					'agentname' => $_POST['agent_name'], 
					'pan_no' => $_POST['pan_number'], 
					'address' => $_POST['address'], 
					'city' => $_POST['city'], 
					'pincode' => $_POST['pin_code'], 
					'id_state' => $_POST['state'], 
					'id_country' => $_POST['country'], 
					'telephone' => $_POST['telephone_no'], 
					'mobile' => $_POST['mobile_no'], 
					'fax' => $_POST['fax_no'], 
					'email' => $_POST['email_id'], 
					'website' => $_POST['website'], 
					'date_add' => $dateadd, 
					'date_upd' => $dateadd,
					'description' => $_POST['desc'], 
					'logo_ext' => $_POST['sortpicture'], 
					'services_offered' => $_POST['services_offered'],
					'contact_person' => $_POST['contact_person'],
					);
					
					
		$checkagentem1=$database->query("select agentname, mobile, email, username from ps_agents where agentname='".$_POST['agent_name']."' or mobile='".$_POST['mobile_no']."' or email='".$_POST['email_id']."' or username='".$_POST['username']."' LIMIT 1 ")->fetchAll();
		
		
		foreach($checkagentem1 as $checkagentem){
			
			
		$agentnamec=$checkagentem['agentname'];
		 $mobilec=$checkagentem['mobile'];
		$emailc=$checkagentem['email'];
	$usernamec=$checkagentem['username'];
	
		
		if( $_POST['agent_name'] == $agentnamec)
		{
			
			echo "agent";
			exit();
			
		}
		elseif( $_POST['mobile_no'] == $mobilec)
		{
			echo "mobile";
			exit();
			
		}
		
		elseif( $_POST['email_id'] == $emailc)
		{
			echo "email";
			exit();
		}
		
		elseif( $_POST['username'] == $usernamec)
		{
			echo "usern";
			exit();
		}
		
		
		}
		
		
					
					$lastID = $database->insert('ps_agents', $values);
					$lastID;
 $reference = 'BT'.strtoupper(substr($_POST['agent_name'],0,2)).$lastID;
$updateuser_resulta = $database->query("update ps_agents set reference='".$reference."',username='".$reference."',password='".bin2hex(openssl_random_pseudo_bytes(4))."' where id_agent='".$lastID."'")->fetchAll();
$file_type= $_POST['sortpicture'];
//$agimgreference = (substr($_POST['agent_name'],0,2)).$lastID;
$aggent=$_POST['agent_name'];
$agimgreference =  $aggent._.$lastID;



if(isset($file_type) && !empty($file_type) && file_exists('/home/buddiest/public_html/admin/img/uploads/agent.'.$file_type)){
			//rename('uploads/agent.'.$file_type, 'uploads/'.$agimgreference.'.'.$file_type);
			rename('/home/buddiest/public_html/admin/img/uploads/agent.'.$file_type, '/home/buddiest/public_html/admin/img/uploads/'.$agimgreference.'.'.$file_type);
			
			//Db::getInstance()->update('agents', array('logo_ext' => $file_type), 'id_agent = '.$lastID, 1);
		}
		
	
if ($_POST['email_id']!="")  {
  $adminemail="noreply@buddiestours.com";

   $to_email = $_POST['email_id'];
   
   
   $subject = "Your registration application number is ".$reference;

     $message=  file_get_contents('basic.html'); 

 require_once('PHPMailer/class.phpmailer.php');
	require_once('PHPMailer/class.smtp.php'); 
  $mail             = new PHPMailer();
$message = str_replace(array('{agentname}'),array($_POST['agent_name']),$message);
$body             = $message;
$body             = eregi_replace("[\]",'',$body);

$mail->IsSMTP();

$mail->isHTML(true);
$mail->Host       = "buddiestours.com"; // SMTP server
$mail->SMTPDebug  = 0;                     // enables SMTP debug information (for testing)
                                           // 1 = errors and messages
                                           // 2 = messages only
$mail->SMTPAuth   = true;                  // enable SMTP authentication
$mail->Host       = "buddiestours.com"; // sets the SMTP server
$mail->Port       = 587;                    // set the SMTP port for the GMAIL server
$mail->Username   = "noreply@buddiestours.com"; // SMTP account username
$mail->Password   = "noreply";        // SMTP account password

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
//$mail->AddAddress($address1, $subject);

//$mail->AddAttachment("images/phpmailer.gif");      // attachment
//$mail->AddAttachment("images/phpmailer_mini.gif"); // attachment

if(!$mail->Send()) {
  echo "Mailer Error: " . $mail->ErrorInfo;
} else {
$mobile=rtrim($_POST['mobile_no']);	
$msg="thank you for registration";
$send_sms =  sendsms($mobile,$msg);
$send_sms;
if($send_sms){
	echo 1;
}

else{
		 echo 0;
}
}
  
 }


  

	}
	
	
	
else if(isset($_GET['getmods']) && $_GET['getmods']=="uploadimg")
	{
		$files = scandir('uploads/');
		if(count($files) > 0)
			foreach($files as $file){
				$fileName = explode('.',$file);
				if($fileName[0] == 'agent')
					unlink('uploads/'.$file);
			}

		$size = $_FILES['file']['size']/1024;
	
			
		$type = explode('/',$_FILES['file']['type']);
		
		//$id_agent = (int)Tools::getValue('id_agent');
		if($size > 8192)
			echo json_encode(array('error' => 'File size exceeds 8MB.'));
		elseif(!in_array($type[1],array('png','jpeg','jpg','gif')))
			echo json_encode(array('error' => 'File type should be jpeg or png or gif.'));
		else{
			$fileName = 'agent';
			/*if($id_agent > 0){
				$fileName = $id_agent;
				Db::getInstance()->update('agents', array('logo_ext' => $type[1]), 'id_agent = '.$id_agent, 1);
			}*/
			/*move_uploaded_file(
				$_FILES['file']['tmp_name'],
				'uploads/'.$fileName.'.'.$type[1]
			);*/
			if(move_uploaded_file(
				$_FILES['file']['tmp_name'],
				'/home/buddiest/public_html/admin/img/uploads/'.$fileName.'.'.$type[1]
			))
				echo json_encode(array('success' => $type[1]));
			else
				echo json_encode(array('failure' => $type[1]));
		}
		exit;
		
		
	}
	
	
	else if($_POST['getmods']=="agentdetailsupdate")
	{
	ini_set('display_errors','on');
	//echo "update agents set username='".$_POST['username']."', password='".$_POST['password']."', agentname='".$_POST['agent_name']."', pan_no='".$_POST['pan_number']."', address='".$_POST['address']."', city='".$_POST['city']."', pincode='".$_POST['pin_code']."', id_state='".$_POST['state']."', id_country='".$_POST['country']."', telephone='".$_POST['telephone_no']."', mobile='".$_POST['mobile_no']."', fax='".$_POST['fax_no']."', email='".$_POST['email_id']."', website='".$_POST['website']."', date_upd='".$dateadd."', description='".$_POST['desc']."', services_offered='".$_POST['services_offered']."' where reference='".$_POST['agentrefid']."'";
	//die();
$file_type= $_POST['sortpicture'];
$aggent=$_POST['agent_name'];
$id_agent=$_POST['id_agent'];


	$agimgreference =  $aggent.'_'.$id_agent;



if(isset($file_type) && !empty($file_type) && file_exists('/home/buddiest/public_html/admin/img/uploads/agent.'.$file_type)){
	
	
	
			//rename('uploads/agent.'.$file_type, 'uploads/'.$agimgreference.'.'.$file_type);
			rename('/home/buddiest/public_html/admin/img/uploads/agent.'.$file_type, '/home/buddiest/public_html/admin/img/uploads/'.$agimgreference.'.'.$file_type);
			//Db::getInstance()->update('agents', array('logo_ext' => $file_type), 'id_agent = '.$lastID, 1);
		}

		
		$updateagent_result = $database->exec("update ps_agents set username='".$_POST['username']."', password='".$_POST['password']."', agentname='".$_POST['agent_name']."', pan_no='".$_POST['pan_number']."', address='".$_POST['address']."', city='".$_POST['city']."', pincode='".$_POST['pin_code']."', id_state='".$_POST['state']."', id_country='".$_POST['country']."', telephone='".$_POST['telephone_no']."', mobile='".$_POST['mobile_no']."', fax='".$_POST['fax_no']."', email='".$_POST['email_id']."', website='".$_POST['website']."', date_upd='".$dateadd."', description='".$_POST['desc']."', services_offered='".$_POST['services_offered']."', logo_ext='".$_POST['sortpicture']."', contact_person='".$_POST['contact_person']."' where reference='".$_POST['agentrefid']."'");
		
		
	if($updateagent_result)
		echo "1";

		
			/*$values = array(
					'username' => $_POST['username'], 
					'password' => $_POST['password'], 
					'agentname' => $_POST['agent_name'], 
					'pan_no' => $_POST['pan_number'], 
					'address' => $_POST['address'], 
					'city' => $_POST['city'], 
					'pincode' => $_POST['pin_code'], 
					'id_state' => $_POST['state'], 
					'id_country' => $_POST['country'], 
					'telephone' => $_POST['telephone_no'], 
					'mobile' => $_POST['mobile_no'], 
					'fax' => $_POST['fax_no'], 
					'email' => $_POST['email_id'], 
					'website' => $_POST['website'], 
					'date_upd' => $dateadd,
					'description' => $_POST['desc'], 
					//'logo_ext' => $_POST['sortpicture'], 
					'reference' => $_POST['agentrefid'],
					'services_offered' => $_POST['services_offered']
					);
					
					 $whereref= 'where reference='.$_POST['agentrefid'];
					
				
						$update = $database->update('ps_agents', $values, $whereref);
						echo 2;*/
					
					
				
					
					
					
	}
	
	
	
	else if($_POST['getmods']=="gettabledatas")
	{
		$get_agents=$database->query("select * from agents where status=1")->fetchAll();
		$s='';$i=1;
		foreach($get_agents as $agents)
		{//(".$i.",".count($get_mobilelist).")
			$s.="<tr><td>".$i."</td><td>".$agents['refid']."</td><td>".$agents['agentname']."</td><td>".$agents['contactperson']."</td><td>".$agents['mobileno']."</td><td>".$agents['emailid']."</td><td>".$agents['address']."</td><td>".$agents['username']."</td><td>".$agents['password']."</td>";
			if($_SESSION['editaccess']==1){$s.="<td class='centertext'><i class='fa fa-edit' style='cursor:pointer;' onclick='confirm_edit_agent(".$agents['id'].");'></i></td>";}else{$s.="<td class='centertext'><i class='fa fa-edit' style='color:gray; cursor:default;'></i></td>";}
			if($_SESSION['deleteaccess']==1){$s.="<td class='centertext'><i class='fa fa-trash' style='cursor:pointer;' onclick='confirm_delete_agent(".$agents['id'].");'></i></td></tr>";}else{$s.="<td class='centertext'><i class='fa fa-trash' style='color:gray; cursor:default;'></i></td></tr>";}
			$i++;
		}
		echo $s;
	}
	else if($_POST['getmods']=="getagentname")
	{
		$get_agents_name=$database->query("select agentname from agents where id='".$_POST['userid']."'")->fetchAll();
		echo $get_agents_name[0][0];
	}
	else if($_POST['getmods']=="geteditagentdetailsdata")
	{
		$get_agents_details=$database->query("select * from agents where id='".$_POST['userid']."'")->fetchAll();
		foreach($get_agents_details as $agentdetails)
		{
			
		$s=$agentdetails['id']."||".$agentdetails['refid']."||".$agentdetails['agentname']."||".$agentdetails['registerno']."||".$agentdetails['contactperson']."||".$agentdetails['landlineno']."||".$agentdetails['mobileno']."||".$agentdetails['fax']."||".$agentdetails['emailid']."||".$agentdetails['websitelink']."||".$agentdetails['address']."||".$agentdetails['city']."||".$agentdetails['pincode']."||".$agentdetails['state']."||".$agentdetails['country']."||".$agentdetails['pancardno']."||".$agentdetails['bankname']."||".$agentdetails['accountno']."||".$agentdetails['accholdername']."||".$agentdetails['ifsccode']."||".$agentdetails['branchname']."||".$agentdetails['username']."||".$agentdetails['alternateno']."||".$agentdetails['companylogo'];
		}
		echo $s;
	}
	else if($_POST['getmods']=="updateagent")
	{
		$updateuser_result = $database->query("update ps_agents set agentname='".$_POST['agentname']."', registerno='".$_POST['registerno']."', contactperson='".$_POST['contactperson']."', landlineno='".$_POST['landlineno']."', mobileno='".$_POST['mobileno']."', fax='".$_POST['fax']."', emailid='".$_POST['emailid']."', websitelink='".$_POST['websitelink']."', address='".$_POST['address']."', city='".$_POST['city']."', pincode='".$_POST['pincode']."', state='".$_POST['state']."', country='".$_POST['country']."', pancardno='".$_POST['pancardno']."', bankname='".$_POST['bankname']."', accountno='".$_POST['accountno']."', accholdername='".$_POST['accholdername']."', ifsccode='".$_POST['ifsccode']."', branchname='".$_POST['branchname']."',username='".$_POST['username']."',alternateno='".$_POST['alternateno']."' where id='".$_POST['eid']."'")->fetchAll();
		$updateagent_verify=$database->query("select count(*) from ps_agents where agentname='".$_POST['agentname']."' and registerno='".$_POST['registerno']."' and contactperson='".$_POST['contactperson']."' and landlineno='".$_POST['landlineno']."' and mobileno='".$_POST['mobileno']."' and fax='".$_POST['fax']."' and emailid='".$_POST['emailid']."' and websitelink='".$_POST['websitelink']."' and address='".$_POST['address']."' and city='".$_POST['city']."' and pincode='".$_POST['pincode']."' and state='".$_POST['state']."' and country='".$_POST['country']."' and pancardno='".$_POST['pancardno']."' and bankname='".$_POST['bankname']."' and accountno='".$_POST['accountno']."' and accholdername='".$_POST['accholdername']."' and ifsccode='".$_POST['ifsccode']."' and branchname='".$_POST['branchname']."'")->fetchAll();
		echo $updateagent_verify[0][0];
	}
	else if($_POST['getmods']=="deleteagentdetailsdata")
	{
		$delete_agents_details=$database->query("delete from agents where id='".$_POST['userid']."'")->fetchAll();
		$verify_agents_delete=$database->query("select count(*) from agents where id='".$_POST['userid']."'")->fetchAll();
		echo $verify_agents_delete[0][0];
	}

	
	
	
	
	
	/*	ini_set("display_errors",1);
if(isset($_POST))
{
	echo "sss";
    $Destination = 'uploads/';
    if(!isset($_FILES['ImageFile']) || !is_uploaded_file($_FILES['ImageFile']['tmp_name']))
    {
       // die('Something went wrong with Upload!');
	   echo "Something went wrong with Upload!";
    }

    $RandomNum   = rand(0, 9999999999);
    
    $ImageName      = str_replace(' ','-',strtolower($_FILES['ImageFile']['name']));
    $ImageType      = $_FILES['ImageFile']['type']; //"image/png", image/jpeg etc.

    $ImageExt = substr($ImageName, strrpos($ImageName, '.'));
    $ImageExt = str_replace('.','',$ImageExt);

    $ImageName      = preg_replace("/\.[^.\s]{3,4}$/", "", $ImageName);

    //Create new image name (with random number added).
    $NewImageName = $ImageName.'-'.$RandomNum.'.'.$ImageExt;
    $types = array('image/jpeg','image/gif','image/png');
	if(in_array($_FILES['ImageFile']['type'], $types)) {
    move_uploaded_file($_FILES['ImageFile']['tmp_name'], "$Destination/$NewImageName");
	
	echo 1;
 /*  echo '<table style="width:30px;" border="0" cellpadding="4" cellspacing="0">';
    echo '<tr>';
    echo '<td align="center"><img id="uploadedimage" src="uploads/'.$NewImageName.'" style="width:80px; height:80px;"></td>';
    echo '<td align="center"><button id="deleteimage" name="deleteimage" onclick="removeimage();" class="btn btn-danger">Delete</button></td></tr>';
    echo '</table>';
	}
	else
	{
		echo 2;
	}
}
*/
	
	
	
}

?>