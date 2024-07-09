<?php
//echo '<pre>hotel/search/booking/get_booking_ref.php-->'; print_r($_REQUEST); echo '</pre>'; die();
	session_start();
	include '../../../include/database/config.php';
	error_reporting(0);


	//$bookingExists = $database->query("select id from ps_hotel_booking where txnid='".$_POST['txnid']."' and customer_title='".$_POST['customer_title']."' and customer_firstname='".$_POST['customer_firstname']."' and customer_lastname='".$_POST['customer_lastname']."' and customer_email='".$_POST['customer_email']."' and customer_contact='".$_POST['customer_contact']."' and customer_specialrequest='".$_POST['customer_specialrequest']."' and check_in='".date('Y-m-d', strtotime($_POST['check_in']))."' and check_out='".date('Y-m-d', strtotime($_POST['check_out']))."' and city_name='".$_POST['city_name']."' and city_id='".$_POST['city_id']."' and room_rate='".$_POST['room_rate']."' and room_id='".$_POST['room_id']."' and room_type_id='".$_POST['room_type_id']."' and guest_text='".$_POST['guest_text']."' and room_count='".$_POST['room_count']."' and id_property='".$_POST['id_property']."' and meal_rate='".$_POST['meal_rate']."' and grand_total_price='".$_POST['grand_total_price']."'")->fetchAll();

	// if(isset($bookingExists[0]['id']) && !empty($bookingExists[0]['id']))
	// 	echo json_encode(array('status' => 'failure', 'message' => 'Booking already exists!'));
	// else{
		$booking_reference_hotel = getStaysinnNextBookingID();
		$booking_reference = getNextBookingID("SI");
		//$booking_reference_hotel = strtoupper(getNextBookingID('',true,$_POST['id_property']));

		$values = array(
			'customer_id' => $_POST['customer_id'],
			'booking_reference' => $booking_reference,
			'hotelier_reference' => $booking_reference_hotel,
			'txnid' => isset($_POST['txnid'])? $_POST['txnid']:"test",
			'customer_title' => $_POST['customer_title'],
			'customer_firstname' => $_POST['customer_firstname'],
			'customer_lastname' => $_POST['customer_lastname'],
			'customer_email' => $_POST['customer_email'],
			'customer_contact' => $_POST['customer_contact'],
			'customer_specialrequest' => $_POST['customer_specialrequest'],
			'check_in' => date('Y-m-d',strtotime($_POST['check_in'])),
			'check_out' => date('Y-m-d', strtotime($_POST['check_out'])),
			'city_name' => $_POST['city_name'],
			'city_id' => $_POST['city_id'],
			'room_rate' => $_POST['room_rate'],
			'room_id' => $_POST['room_id'],
			'room_type_id' => $_POST['room_type_id'],
			'guest_text' => $_POST['guest_text'],
			'guest_json' => json_encode($_POST['guest']),
			'no_of_nights' => (int)$_POST['no_of_nights'],
			'breakfast_incl' => (int)$_POST['breakfast_incl'],
			'lunch_incl' => (int)$_POST['lunch_incl'],
			'dinner_incl' => (int)$_POST['dinner_incl'],
			'room_count' => $_POST['room_count'],
			'id_property' => $_POST['id_property'],
			'meal_rate' => $_POST['meal_rate'],
			'commission_amount' => $_POST['commission_amount'],
			'cgst_amount' => $_POST['cgst_amount'],
			'sgst_amount' => $_POST['sgst_amount'],
			'grand_total_price' => $_POST['grand_total_price'],
			'date_add' => date('Y-m-d H:i:s')
		);
		$lastInsertId = $database->insert('ps_hotel_booking', $values);
		if(isset($lastInsertId) && !empty($lastInsertId)){
			/*$valuesT = array(
				'id_agent' => (isset($_SESSION['authid']) ? $_SESSION['authid'] : ''),
				'status' => 'success',
				'bt_txnid' => 'PTI'.mt_rand(10000000, 99999999),
				'amount' => ($_POST['grand_total_price']-$_POST['commission_amount']),
				'productinfo' => 'Hotel Booking - Account Balance',
				'order_info' => $booking_reference,
				'date_add' => date('Y-m-d H:i:s'));
			$previousBalanceArr = $database->query("select available_balance from ps_agents where id_agent=".$_SESSION['authid'])->fetchAll();	
			$previous_balance = (isset($previousBalanceArr[0]['available_balance']) ? $previousBalanceArr[0]['available_balance'] : '');
			
			$result = $database->exec('update ps_agents set available_balance = available_balance - '.($_POST['grand_total_price']-$_POST['commission_amount']).' where id_agent='.$_SESSION['authid']);*/
			if($result){
				/*$currentBalanceArr = $database->query("select available_balance from ps_agents where id_agent=".$_SESSION['authid'])->fetchAll();	
				$current_balance = (isset($currentBalanceArr[0]['available_balance']) ? $currentBalanceArr[0]['available_balance'] : '');
				$database->insert('ps_payu_transactions', array_merge($valuesT,array('previous_balance' => $previous_balance, 'current_balance' => $current_balance)));*/
			}

			//$email_message_header = '<html><head></head><body><p>Dear '.$_POST['guide_user_name'].',<br><br>Thank you for choosing Buddies Tours!&nbsp;Please find attached pdf of your booking.<br><br>';
			
			$propertyDetails = $database_hotel->query('select p.*,r.id_room,r.txtRoomName,r.selNoOfBeds,r.selRoomBedSize,rt.id_room_type,c.name as cityName,s.name as stateName from ps_property p left join ps_room r on(p.id_property = r.id_property) left join ps_room_type rt on(r.id_room = rt.id_room) left join ps_state s on(s.id_state = p.selStateId) left join ps_city c on(c.id_city = p.selCityId) where p.id_property = '.$_POST['id_property'].' and r.id_room='.$_POST['room_id'].' and rt.id_room_type='.$_POST['room_type_id'])->fetchAll();
			
			$smsMessage = 'Dear Customer, Please find following details:- Guest Name: '.ucwords($_POST['customer_firstname']).' '.ucwords($_POST['customer_lastname']).', Hotel: '.ucwords($propertyDetails[0]['txtPropertyName']).', Destination: '.(isset($propertyDetails[0]['txtAddress1']) && !empty($propertyDetails[0]['txtAddress1']) ? $propertyDetails[0]['txtAddress1'] : '').(isset($propertyDetails[0]['txtAddress2']) && !empty($propertyDetails[0]['txtAddress2']) ? ','.$propertyDetails[0]['txtAddress2'] : '').(isset($propertyDetails[0]['cityName']) && !empty($propertyDetails[0]['cityName']) ? ','.$propertyDetails[0]['cityName'] : '').(isset($propertyDetails[0]['stateName']) && !empty($propertyDetails[0]['stateName']) ? $propertyDetails[0]['stateName'] : '').', Check In: '.date('D, d M Y',strtotime($_POST['check_in']));
			$emailMessage1 = '';
			$emailMessage = '
<table   align="center" cellspacing="5"  width="650px"  style="border: 0px solid #ddd;font-family:serif;">
	<tr>
		<td>
			<table>
				<tr>
					<td width="80%"  align="left" style="font-size:20px;font-weight: bold;">Service Confirmation Voucher </td>
					<td width="20%" align="left" style="font-weight:600;font-size: 16px;color: #000000;text-align: right;">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="2"> &nbsp;  </td>
				</tr>
				<tr>
					<td colspan=2  align="left" style="line-height:1.5;color: #555555;font-size:13px;">Booking ID - '.$booking_reference.'</td>
				</tr>
				<tr>
					<td colspan=2 width="100%" align="left" style=";line-height:1.5;border-bottom:1px solid #ddd;color: #555555;font-size:13px;">Booking Date - '.date('D, d M Y').'</td>
				</tr>
				<tr>
					<td colspan=2   align="left" style="font-size:12px;color: #555555;line-height:2;color:#000;">Dear '.ucfirst($_POST['customer_firstname']).' '.ucwords($_POST['customer_lastname']).',  </td>
				</tr>
			</table>
			<table>
				<tr>
					<td   align="left" style="line-height:2;font-size:12px;color: #555555">Thank you for choosing <strong>'.ucwords($propertyDetails[0]['txtPropertyName']).'</strong>. </td>
				</tr>
				<!--<tr>
					<td    align="left" style="line-height:2;font-size:12px;color: #555555" >For your reference, your Booking ID is '.$booking_reference_hotel.'.  </td>
				</tr>-->
				<tr>
					<td  align="left" style="line-height:2;font-size:12px;color: #555555">
Kindly note, your booking is CONFIRMED and you are not required to contact the hotel or Buddies  to reconfirm the same. 
 </td>
				</tr>
				<tr>
					<td  align="left" style="line-height:2;font-size:12px;color: #555555">
You will need to carry a printout of this e-mail and present it at the hotel at the time of check-in. </td>
				</tr>
				<tr>
					<td  align="left" style="line-height:2;font-size:12px;color: #555555 ">
We hope you have a pleasant stay and look forward to assisting you again! </td>
				</tr>
			</table>
			<table>
				<tr>
					<td width="100px" align="left" style="line-height:1.5;font-size:16px;border-bottom:1px solid #ddd;">
Hotel Details:-
 </td>
				</tr>
			</table>
			<table>
				<tr>
					<td align="left" style="font-weight:400;font-size: 14px;color: #000000;line-height:2;">'.$propertyDetails[0]['txtPropertyName'].'</td>
				</tr>
				<tr>
					<td  align="left" style="line-height:1.5;font-size:12px;color: #555555 ">'.(isset($propertyDetails[0]['txtAddress1']) && !empty($propertyDetails[0]['txtAddress1']) ? $propertyDetails[0]['txtAddress1'] : '').(isset($propertyDetails[0]['txtAddress2']) && !empty($propertyDetails[0]['txtAddress2']) ? ', '.$propertyDetails[0]['txtAddress2'] : '').'</td>
				</tr>
				<tr>
					<td  align="left" style="line-height:1.5;font-size:12px;color: #555555 ">'.(isset($propertyDetails[0]['cityName']) && !empty($propertyDetails[0]['cityName']) ? $propertyDetails[0]['cityName'] : '').'</td>
				</tr>
				<tr>
					<td  align="left" style="line-height:1.5;font-size:12px;color: #555555 ">'.(isset($propertyDetails[0]['stateName']) && !empty($propertyDetails[0]['stateName']) ? $propertyDetails[0]['stateName'] : '').'</td>
				</tr>
				<tr>
					<td  align="left" style="line-height:1.5;font-size:12px;color: #555555 ">'.(isset($propertyDetails[0]['txtPhone']) && !empty($propertyDetails[0]['txtPhone']) ? 'Contact No:&nbsp;'.$propertyDetails[0]['txtPhone'] : '').'</td>
				</tr>
				<tr>
					<td  align="left" style="line-height:1.5;font-size:12px;color: #555555 ">'.(isset($propertyDetails[0]['txtMobile']) && !empty($propertyDetails[0]['txtMobile']) ? 'Mobile:&nbsp;'.$propertyDetails[0]['txtMobile'] : '').'</td>
				</tr>
				<tr>
					<td  align="left" style="line-height:2;font-size:13px; ">
						<b>Primary Guest:</b>
						<span style="color:#555555;font-size:12px; ">'.ucwords($_POST['customer_firstname']).' '.ucwords($_POST['customer_lastname']).'</span>
					</td>
				</tr>
				<tr>
					<td  align="left" style="line-height:2;font-size:13px; ;color: #000000; ">
						<b>No. of Rooms:</b>
						<span style="font-size:12px;color: #555555;">'.$_POST['room_count'].'</span>
					</td>
				</tr>
			</table>';

			$emailMessageHotel = '
<table  align="center" cellspacing="5"  width="650px"  style="border: 1px solid #ddd;font-family:serif;">
	<tr>
		<td>
			<table>
				<tr>
					<td width="80%"  align="left" style="font-size:20px;font-weight: bold;">Service Confirmation Voucher </td>
					<td width="20%" align="left" style="font-weight:600;font-size: 16px;color: #000000;text-align: right;">&nbsp;</td>
				</tr>
				<tr>
					<td> &nbsp;  </td>
				</tr>
				<tr>
					<td colspan=2  align="left" style="line-height:1.5;color: #555555;font-size:13px;">Booking ID - '.$booking_reference.'</td>
				</tr>
				<tr>
					<td colspan=2 width="100%" align="left" style=";line-height:1.5;border-bottom:1px solid #ddd;color: #555555;font-size:13px;">Booking Date - '.date('D, d M Y').'</td>
				</tr>
				<tr>
					<td  colspan=2  align="left" style="font-size:12px;color: #555555;line-height:2;color:#000;">Dear Partner,</td>
				</tr>
			</table>
			<table>
				<tr>
					<td   align="left" style="line-height:2;font-size:12px;color: #555555">Buddies has received a booking for your hotel as per the details below. The primary guest <strong>'.ucwords($_POST['customer_firstname']).' '.ucwords($_POST['customer_lastname']).'</strong>, will be carrying a copy of this e-voucher.</td>
				</tr>
				<!--<tr>
					<td align="left" style="line-height:2;font-size:12px;color: #555555" >For your reference, your Booking ID is '.$booking_reference_hotel.'.</td>
				</tr>-->
				<tr>
					<td  align="left" style="line-height:2;font-size:12px;color: #555555">The amount payable by buddies for this booking is Rs. '.$_POST['grand_total_price'].' including all taxes. Please email us at hotelsupport@buddiestechnologies.com if there is any discrepancy in this payment amount.</td>
				</tr>
				<tr>
					<td align="left" style="line-height:2;font-size:12px;color: #555555">Kindly consider this as the confirmation e-voucher and provide the guest with the following inclusions and services.</td>
				</tr>
			</table>
			<table>
				<tr>
					<td width="100px" align="left" style="line-height:1.5;font-size:16px;border-bottom:1px solid #ddd;">
Hotel Details:-
 </td>
				</tr>
			</table>
			<table>
				<tr>
					<td align="left" style="font-weight:400;font-size: 14px;color: #000000;line-height:2;">'.$propertyDetails[0]['txtPropertyName'].'</td>
				</tr>
				<tr>
					<td  align="left" style="line-height:1.5;font-size:12px;color: #555555 ">'.(isset($propertyDetails[0]['txtAddress1']) && !empty($propertyDetails[0]['txtAddress1']) ? $propertyDetails[0]['txtAddress1'] : '').(isset($propertyDetails[0]['txtAddress2']) && !empty($propertyDetails[0]['txtAddress2']) ? ', '.$propertyDetails[0]['txtAddress2'] : '').'</td>
				</tr>
				<tr>
					<td  align="left" style="line-height:1.5;font-size:12px;color: #555555 ">'.(isset($propertyDetails[0]['cityName']) && !empty($propertyDetails[0]['cityName']) ? $propertyDetails[0]['cityName'] : '').'</td>
				</tr>
				<tr>
					<td  align="left" style="line-height:1.5;font-size:12px;color: #555555 ">'.(isset($propertyDetails[0]['stateName']) && !empty($propertyDetails[0]['stateName']) ? $propertyDetails[0]['stateName'] : '').'</td>
				</tr>
				<tr>
					<td  align="left" style="line-height:1.5;font-size:12px;color: #555555 ">'.(isset($propertyDetails[0]['txtPhone']) && !empty($propertyDetails[0]['txtPhone']) ? 'Contact No:&nbsp;'.$propertyDetails[0]['txtPhone'] : '').'</td>
				</tr>
				<tr>
					<td  align="left" style="line-height:1.5;font-size:12px;color: #555555 ">'.(isset($propertyDetails[0]['txtMobile']) && !empty($propertyDetails[0]['txtMobile']) ? 'Mobile:&nbsp;'.$propertyDetails[0]['txtMobile'] : '').'</td>
				</tr>
				<tr>
					<td  align="left" style="line-height:2;font-size:13px; ">
						<b>Primary Guest:</b>
						<span style="color:#555555;font-size:12px; ">'.ucwords($_POST['customer_firstname']).' '.ucwords($_POST['customer_lastname']).'</span>
					</td>
				</tr>
				<tr>
					<td  align="left" style="line-height:2;font-size:13px; ;color: #000000; ">
						<b>No. of Rooms:</b>
						<span style="font-size:12px;color: #555555;">'.$_POST['room_count'].'</span>
					</td>
				</tr>
			</table>';

			global $roomTypes;
			foreach($_POST['guest'] as $guestKey => $guestVal){
				$emailMessage1 .= '<table>
					<tr>
						<td colspan="3" align="left" style="font-weight:600;font-size: 16px;color: #000000;line-height:2;">Room '.$guestKey.'</td>
					</tr>
					<tr>
						<td width="40%" align="center" style="line-height:2;font-size:13px;color: #000;border: 1px solid #ddd; background-color:#ddd; ">
							<b>Check in:  </b>
							<span style="font-size:12px;color: #000">'.date('D, M d, Y', strtotime($_POST['check_in'])).'</span>
						</td>
						<td width="40%" align="center" style="line-height:2;font-size:13px; ;color: #000;border: 1px solid #ddd;    background-color: #ddd; ">
							<b>Check out:   </b>
							<span style="font-size:12px;color: #000">'.date('D, M d, Y', strtotime($_POST['check_out'])).'</span>
						</td>
						<td width="20%" align="center" style="line-height:2;font-size:13px;color:#000;border: 1px solid #ddd; background-color: #ddd; ">
							<b>No. of Night(s):</b>
							<span style="font-size:12px;color:#000;">'.(int)$_POST['no_of_nights'].'</span>
						</td>
					</tr>
					<tr>
						<td width="40%" align="center" style="line-height:2;font-size:13px;border: 1px solid #ddd ;color: #000000;  ">
							<b>Room Type,Category:</b>
							<span style="font-size:12px;color: #555555">'.(isset($roomTypes[$_POST['room_type_id']]) ? $roomTypes[$_POST['room_type_id']] : '').(isset($propertyDetails[0]['txtRoomName']) ? ', '.$propertyDetails[0]['txtRoomName'] : '').'</span>
						</td>
						<td width="40%" align="center" style="line-height:2;font-size:13px;border: 1px solid #ddd ;color: #000000; ">
							<b>Guest:</b>
							<span style="font-size:12px;color: #555555">'.$guestVal['adult'].' Adult'.($guestVal['child'] > 0 ? ', '.$guestVal['child'].' Child' : '').'</span>
						</td>
						<td width="20%" align="center" style="line-height:2;font-size:13px;border: 1px solid #ddd;color: #000000; ">
							<b>Meal Plan: </b>
							<span style="font-size:12px;color: #555555">'.((int)$_POST['breakfast_incl'] == 1 ? 'Breakfast' : '').((int)$_POST['lunch_incl'] == 1 ? ((int)$_POST['breakfast_incl'] == 1 ? ', ' : '').'Lunch' : '').((int)$_POST['dinner_incl'] == 1 ? ((int)$_POST['breakfast_incl'] == 1 || (int)$_POST['lunch_incl'] == 1 ? ', ' : '').'Dinner' : '').'</span>
						</td>
					</tr>
				</table>';
			}
			$emailMessage1 .= '
			<table>
				<tr>
					<td> &nbsp;  </td>
				</tr>
				<tr>
					<td width="160px" align="left" style="font-weight:600;font-size: 16px;color: #000000;line-height:1.5;border-bottom:1px solid #ddd;">General Hotel Policy:- </td>
				</tr>
				<tr>
					<td> &nbsp;  </td>
				</tr>
			</table>
			<table cellpadding="5">
				<tr>
					<td style="font-size:12px;text-align:justify;color: #555555">
						<ul>
							<li style="color: ">
Early check-in or late check-out is subject to availability and may be chargeable by the hotel. To request for an early check-in or late check-out, kindly contact the hotel directly.
</li>
							<li>
As per Government of India rules, it is mandatory for all guests over the age of 18 years to present a valid photo identification (ID) on check-in. Without a valid ID, guests will not be allowed to check-in. The valid ID proofs accepted are Driving License, Passport, Voter ID Card and Ration Card. Kindly note, a PAN Card will not be accepted as a valid ID proof.

</li>
							<li>
The primary guest must be at least 18 years of age to check-in to this hotel. Ages of accompanying children, if any, must be between 1-12 years.
</li>
							<li>
The room tariff includes all taxes. The amount shown does not include charges for optional services and facilities (such as room service, mini bar, snacks or telephone calls). These will be charged as per actual usage and billed separately on check-out.
</li>
							<li>
Entry to the hotel is at the sole discretion of the hotel authority. If the address on the photo identification card matches the city where the hotel is located, the hotel may refuse to provide accommodation. Buddies will not be responsible for any check-in denied by the hotel due to the aforesaid reasons.
</li>
							<li>
Kindly note, if a hotel booking includes an extra bed, most hotels provide a folding cot or a mattress as an extra bed.
</li>
							<li>
The hotel reserves the right of admission. Accommodation can be denied to guests posing as a couple if suitable proof of identification is not presented at check-in. Buddies will not be responsible for any check-in denied by the hotel due to the aforesaid reason.
</li>
						</ul>
					</td>
				</tr>
			</table>
			<table>
				<tr>
					<td width="150px;" align="left" style="font-weight:600;font-size: 16px;color:#000000;line-height:1.5; border-bottom:1px solid #ddd;">Cancellation Policy:-  </td>
				</tr>
				<tr>
					<td> &nbsp;  </td>
				</tr>
			</table>
			<table cellpadding="10">
				<tr>
					<td style="font-size:12px;text-align:justify;color: #555555">
						<ul>
							<li>
More than 3 days before check-in date: '.(isset($propertyDetails[0]['cancellation_policy_3_days_more']) && !empty($propertyDetails[0]['cancellation_policy_3_days_more']) ? $propertyDetails[0]['cancellation_policy_3_days_more'].'% Refund.' : 'FREE CANCELLATION/MODIFICATION.').'
</li>
							<li>
3 days before check-in date: '.(isset($propertyDetails[0]['cancellation_policy_3_days']) && !empty($propertyDetails[0]['cancellation_policy_3_days']) ? $propertyDetails[0]['cancellation_policy_3_days'].'% Refund.' : 'FREE CANCELLATION/MODIFICATION.').'.
</li>
							<li>
In case of no show: No refund. Booking cannot be cancelled/modified on or after the check in date and time mentioned in the Hotel Confirmation Voucher.
</li>
						</ul>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>';


			//$email_message_footer = '<br>Have a nice Trip!<br><br>Thanks and Regards,<br>Buddies Tours Team.</p></body></html>';

			require_once(dirname(dirname(dirname(__FILE__))).'/pdf/tcpdf_include.php');
			$pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
			$pdf->SetCreator(PDF_CREATOR);
			$pdf->SetAuthor($booking_reference);
			$pdf->SetTitle($booking_reference);
			$pdf->SetSubject($booking_reference);
			$pdf->SetKeywords('TCPDF, PDF', $booking_reference);
			$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
			$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
			$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
			$pdf->SetMargins(20,10,0);
			//$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
			$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
			$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
			$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
			$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
			if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
				require_once(dirname(__FILE__).'/lang/eng.php');
				$pdf->setLanguageArray($l);
			}
			$pdf->SetFont('helvetica', '', 9);
			$pdf->AddPage();
			$pdf->writeHTML($emailMessage.$emailMessage1, true, 0, true, 0);
			$pdf->lastPage();
			$pdf->Output(getcwd().'/vouchers/'.$booking_reference.'.pdf', 'F');

			$pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
			$pdf->SetCreator(PDF_CREATOR);
			$pdf->SetAuthor($booking_reference);
			$pdf->SetTitle($booking_reference);
			$pdf->SetSubject($booking_reference);
			$pdf->SetKeywords('TCPDF, PDF', $booking_reference);
			$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
			$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
			$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
			$pdf->SetMargins(20,10,0);
			//$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
			$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
			$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
			$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
			$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
			if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
				require_once(dirname(__FILE__).'/lang/eng.php');
				$pdf->setLanguageArray($l);
			}
			$pdf->SetFont('helvetica', '', 9);
			$pdf->AddPage();
			$pdf->writeHTML($emailMessageHotel.$emailMessage1, true, 0, true, 0);
			$pdf->lastPage();
			$pdf->Output(getcwd().'/vouchers/'.$booking_reference.'_hotel.pdf', 'F');

			$mail = sendConfirmationMail($_POST['customer_email'], 'Hotel Booking Confirmation', $emailMessage.$emailMessage1, $booking_reference.'.pdf', dirname(__FILE__).'/vouchers/');
			smsAPICall($smsMessage,$_POST['customer_contact']);
			
			//$mail = sendConfirmationMail('lavanya@buddiestechnologies.com', 'Hotel Booking Confirmation', $emailMessageHotel, $booking_reference.'_hotel.pdf', dirname(__FILE__).'/vouchers/');
			if($mail){
				// update inventory
				$begin 	= new DateTime(date('Y-m-d',strtotime($_POST['check_in'])));
				$end 	= new DateTime(date('Y-m-d',strtotime($_POST['check_out'])));
				$daterange = new DatePeriod($begin, new DateInterval('P1D'), $end);
				foreach($daterange as $date){
					$dateValue = $date->format("Y-m-d");
					$year	= date('Y',strtotime($dateValue));
					$month	= date('n',strtotime($dateValue));
					$dateV	= date('j',strtotime($dateValue));
					
					$database->exec('update ps_room_available_inventory set `'.$dateV.'` = `'.$dateV.'` - '.$_POST['room_count'].' where allot_avail=1 and id_room='.$_POST['room_id'].' and year='.$year.' and month='.$month);
				}
				$database->exec('update ps_hotel_booking set hotel_voucher_html = \''.addslashes($emailMessageHotel.$emailMessage1).'\',voucher_html = \''.addslashes($emailMessage.$emailMessage1).'\',sms_html = \''.addslashes($smsMessage).'\' where id='.$lastInsertId);
			}
			
			echo json_encode(array('status' => 'success', 'lastInsertId' => $booking_reference));
		}
	//}
	exit;
?>

