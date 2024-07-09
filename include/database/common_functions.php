<?php
global $roomTypes;
$roomTypes = array(
				1 => 'Single',
				2 => 'Double',
				3 => 'Triple',
				4 => 'Quadruple'
			);
global $roomBedSize;
$roomBedSize = array(
				1 => 'Single Size Bed',
				2 => 'Double Size Bed',
				3 => 'Twin Size Bed'
			);
			
global $roomFacilitiesArr;			
$roomFacilitiesArr = array('ac','cable_tv','direct_phone','channel_music','attached_bathroom','shower','bath_tub','shower_bath_tub','minibar','work_desk','balcony','radio','clock','hair_dryer','fire_place','safe_deposit_box','smoke_alarms','sprinklers','double_bed','king_bed','high_speed_wi_fi_internet_access','lcd_tv','sound_proof_windows','24_hour_room_service','electronic_lock','electronic_laptop_compatible_safe','marble_flooring','study_table','free_local_phone_calls','iron_with_ironing_board','full_length_mirror','complimentary_toiletries','internet','tea_coffee_maker','complimentary_tea_coffee','complimentary_packed_water_bottles','carpet_flooring','complimentary_fruit_basket','high_speed_wi_fi_internet','double_twin_beds','direct_dialing_phone','smoke_detector_alarms','on_the_bay','on_the_beach','on_the_garden','on_the_lake','on_the_ocean','on_the_park','on_the_river','poolside_room','garden_room','city_view','mountain_view','sea_facing_room');
	function getNextBookingID($mode = ''){
		global $database;
		$bookingValuesArr = $database->query("select * from ps_booking_reference where id=1")->fetchAll();
		$nextBookingSeries = str_pad((int)$bookingValuesArr[0]['nextBookingSeries'], 2, '0', STR_PAD_LEFT);
		$nextBookingID = str_pad((int)$bookingValuesArr[0]['nextBookingID']+1, 7, '0', STR_PAD_LEFT);
		if($nextBookingID > 9999999){
			$database->exec('update ps_booking_reference set nextBookingSeries = nextBookingSeries + 1, nextBookingID = 1 where id=1');
			$nextBookingSeries = str_pad((int)($bookingValuesArr[0]['nextBookingSeries']+1), 2, '0', STR_PAD_LEFT);
			$nextBookingID = str_pad(1, 7, '0', STR_PAD_LEFT);
		}else
			$database->exec('update ps_booking_reference set nextBookingID = nextBookingID + 1 where id=1');
		return 'BT'.$nextBookingSeries.$mode.$nextBookingID;
	}
	function getStaysinnNextBookingID($mode = ''){
		global $database;
		$bookingValuesArr = $database->query("select * from ps_booking_reference where id=1")->fetchAll();
		$staysinnnextBookingSeries = str_pad((int)$bookingValuesArr[0]['staysinnnextBookingSeries'], 2, '0', STR_PAD_LEFT);
		$staysinnNextBookingId = str_pad((int)$bookingValuesArr[0]['staysinnNextBookingId']+1, 7, '0', STR_PAD_LEFT);
		if($staysinnNextBookingId > 9999999){
			$database->exec('update ps_booking_reference set staysinnnextBookingSeries = staysinnnextBookingSeries + 1, staysinnNextBookingId = 1 where id=1');
			$staysinnnextBookingSeries = str_pad((int)($bookingValuesArr[0]['staysinnnextBookingSeries']+1), 2, '0', STR_PAD_LEFT);
			$staysinnNextBookingId = str_pad(1, 7, '0', STR_PAD_LEFT);
		}else
			$database->exec('update ps_booking_reference set staysinnNextBookingId = staysinnNextBookingId + 1 where id=1');
		return 'SINN'.$staysinnnextBookingSeries.$mode.$staysinnNextBookingId;
	}
	function smsAPICall($message,$mobile_no){
		
		$apiKey = urlencode('Mzg0NzMzNmQ2ZTRjMzU0ZTM0NmY1NDZlNjk3MzQzNDQ=');
		$mobile = rtrim($mobile_no,",");
		$sms_to_exp=explode(",",trim($mobile));
		$gsmArr = array();
		foreach($sms_to_exp as $mNo){
			$gsmArr[] = (strlen($mNo) == 10 ? '91'.$mNo : $mNo);
		}
		
		$numbers=implode(',', $gsmArr);
		$sender = urlencode('KSTAYS');
		$message = rawurlencode($message);
		// Prepare data for POST request
		$data = array('apikey' => $apiKey, 'numbers' => $numbers, "sender" => $sender, "message" => $message);
	 
		// Send the POST request with cURL
		$ch = curl_init('https://api.textlocal.in/send/');
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec($ch);
		curl_close($ch);
		$response=json_decode($response,true);
		  //print_r($data);
          //print_r($response);
		if($response['status']!='success')
		{
			echo $response['errors'][0]['message'];
			//smsAPICallAlter($message,$mobile_no);
		}else if($response['status']=='success'&&$response['balance']<5000)
		{
			//send mail to admin
			//mailto:sendconfirmationmail('senthil@buddiesholidays.com', 'SMS Credits', 'SMS Credits is below 5000 So kindly Upgrade');
		}
		if($response['status']=='success')
		{
			return $response['status'];
		}
	
	}
	function smsAPICallAlter($message,$mobile_no){
		$msg = urlencode(str_ireplace(array('mobile'),array('Contact'),$message));
		$mobile = rtrim($mobile_no,",");
		$sms_to_exp=explode(",",trim($mobile));
		$gsmArr = array();
		foreach($sms_to_exp as $mNo){
			$gsmArr[] = (strlen($mNo) == 10 ? '91'.$mNo : $mNo);
		}
		$url = 'http://103.16.101.52:8080/sendsms/bulksms?username=budd-holidays&password=budyholy&type=0&dlr=1&destination='.implode(',',$gsmArr).'&source=BUDYHD&message='.$msg;

		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Content-Type: text/csv;charset= UTF-8',
			'Content-Length: ' . strlen($message)
		));
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		$result = curl_exec($ch);
		return $result;
	}
	function sendConfirmationMail($to_email, $subject, $email_message, $fileNamePDF = false, $filePathPDF = false){
		if(isset($to_email) && !empty($to_email)){
			require_once(dirname(dirname(dirname(__FILE__))).'/PHPMailer/class.phpmailer.php');
			require_once(dirname(dirname(dirname(__FILE__))).'/PHPMailer/class.smtp.php');
			$mail             = new PHPMailer();
			$body             = preg_replace('/\[.*\]/', '',$email_message);
			$mail->IsSMTP(); // telling the class to use SMTP
			$mail->Host       = "mail.klitestays.com"; // SMTP server
			$mail->SMTPDebug  = 0;                     // enables SMTP debug information (for testing)
													   // 1 = errors and messages
													   // 2 = messages only
			$mail->SMTPAuth   = true;                  // enable SMTP authentication
			$mail->Host       = "mail.klitestays.com"; // sets the SMTP server
			$mail->Port       = 587;                    // set the SMTP port for the GMAIL server
			$mail->Username   = "noreply@klitestays.com"; // SMTP account username
			$mail->Password   = "Noreply@123";        // SMTP account password

			$mail->SetFrom('noreply@klitestays.com', "StaysInn");

			$mail->Subject    = $subject;

			$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test

			$mail->MsgHTML($body);

			if($fileNamePDF && $filePathPDF){
				if(is_array($fileNamePDF)){
					foreach($fileNamePDF as $filePDF)
						$mail->AddAttachment($filePathPDF.$filePDF, $name = $filePDF,  $encoding = 'base64', $type = 'application/pdf');
				}else
				
					$mail->AddAttachment($filePathPDF.$fileNamePDF, $name = $fileNamePDF,  $encoding = 'base64', $type = 'application/pdf');
			}

			$address = $to_email;
			$mail->AddAddress($address, $subject);
			return $mail->Send();
		}
	}
	//sendConfirmationMail("testing123@mailinator.com","test","testing from otocabs");
?>