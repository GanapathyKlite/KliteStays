<?php
if(!$_SERVER['HTTP_X_REQUESTED_WITH']== 'XMLHttpRequest')
{
   header("Location:login.php");
}
else
{
	session_start();
	error_reporting(0);
	include('../include/database/config.php');
	$date= new DateTime('now', new DateTimeZone('Asia/Kolkata'));
	if($_POST['getmods']=="fetchreport")
	{
		$s = '';
		$bus_bookings=$database_bus->query("select bd.*,td.id as id_ticket,td.ticket_no,td.seats,td.iscancel,td.seatFareDisplay from booking_details bd left join ticket_details td on(bd.id = td.b_id) where bd.id_agent='".$_SESSION['authtnid']."' and td.iscancel!=0 order by bd.journeyDate desc");
		if($bus_bookings)
		{
			$bus_bookings=$bus_bookings->fetchAll();
		}else
		{
			$bus_bookings=array();
		}
		$i=1;
		foreach($bus_bookings as $paylist)
		{
			$id=$paylist["id"];
			$passengerNameArr = explode(',',$paylist['name']);
			$seatFareArr = explode(',',$paylist['seatFareDisplay']);
			$s.="<tr>
				<td>".$i."</td>
				<td>".$paylist['orderno']."</td>
				<td>".date('d/m/Y', strtotime($paylist['booking_datetime']))."</td>
				<td>".date('d/m/Y', strtotime($paylist['journeyDate']))."</td>
				<td>".$paylist['ticket_no']."</td>
				<td>".$passengerNameArr[0]."</td>
				<td>".$paylist['leavingfrom']."</td>
				<td>".$paylist['goingto']."</td>
				<td>".array_sum($seatFareArr)."</td>
				<td></td>
				<td class='centertext'>".($paylist['iscancel'] == '1' ? '<span class="label color_field" style="background-color:RoyalBlue;color:white">Awaiting Approval</span>' : ($paylist['iscancel'] == '2' ? '<span class="label color_field" style="background-color:DarkOrange;color:white">Awaiting Refund</span>' : '<span class="label color_field" style="background-color:#108510;color:white">Refund Successful</span>'))."</td>
			</tr>";
			$i++;
		}
		echo $s;
	}
	else if($_POST['getmods']=="searchreport"){
		$where = '';
		if(isset($_POST['fromdate']) && !empty($_POST['fromdate']) && isset($_POST['todate']) && !empty($_POST['todate']))
			$where = " AND journeyDate >= '".date('Y-m-d', strtotime($_POST['fromdate']))." 00:00:00' AND journeyDate <= '".date('Y-m-d', strtotime($_POST['todate']))." 23:59:59'";
		else if(isset($_POST['fromdate']) && !empty($_POST['fromdate']))
			$where = " AND journeyDate >= '".date('Y-m-d', strtotime($_POST['fromdate']))." 00:00:00'";
		else if(isset($_POST['todate']) && !empty($_POST['todate']))
			$where = " AND journeyDate <= '".date('Y-m-d', strtotime($_POST['todate']))." 23:59:59'";

		$gen_report_search=$database_bus->query("select bd.*,td.id as id_ticket,td.ticket_no,td.seats,td.iscancel,td.seatFareDisplay from booking_details bd left join ticket_details td on(bd.id = td.b_id) where bd.id_agent='".$_SESSION['authtnid']."' and td.iscancel!=0".$where." order by bd.journeyDate desc");

		if($gen_report_search)
		{
			$gen_report_search=$gen_report_search->fetchAll();
		}else
		{
			$gen_report_search=array();
		}
		$i=1;
		
		foreach($gen_report_search as $searchresult)
		{
			$id=$searchresult["id"];
			$passengerNameArr = explode(',',$searchresult['name']);
			$seatFareArr = explode(',',$searchresult['seatFareDisplay']);
			$s.="<tr>
				<td>".$i."</td>
				<td>".$searchresult['orderno']."</td>
				<td>".date('d/m/Y', strtotime($searchresult['booking_datetime']))."</td>
				<td>".date('d/m/Y', strtotime($searchresult['journeyDate']))."</td>
				<td>".$searchresult['ticket_no']."</td>
				<td>".$passengerNameArr[0]."</td>
				<td>".$searchresult['leavingfrom']."</td>
				<td>".$searchresult['goingto']."</td>
				<td>".array_sum($seatFareArr)."</td>
				<td></td>
				<td class='centertext'>".($paylist['iscancel'] == '1' ? '<span class="label color_field" style="background-color:RoyalBlue;color:white">Awaiting Approval</span>' : ($paylist['iscancel'] == '2' ? '<span class="label color_field" style="background-color:DarkOrange;color:white">Awaiting Refund</span>' : '<span class="label color_field" style="background-color:#108510;color:white">Refund Successful</span>'))."</td>
			</tr>";
			$i++;
		}
		echo $s;
		
	}
	else if($_POST['getmods']=="fetchreportflight")
	{
		include('../flightvac/config.php');
		$changeRequestArrValues = array(0 => 'NotSet',1 => 'Unassigned',2 => 'Assigned',3 => 'Acknowledged',4 => 'Completed',5 => 'Rejected',6 => 'Closed',7 => 'Pending',8 => 'Other');

		$s = ''; $i = 1;
		$flight_cancelled_bookings=$database_flight->query("select * from cancellation_details")->fetchAll();
		foreach($flight_cancelled_bookings as $cancel){
			$params = array(
				'ChangeRequestId' => $cancel['ChangeRequestId'],
				'EndUserIp' => $EndUserIp,
				'TokenId' => $TokenId
			);
			$changeRequestStatusResults = flightAPICall($flightGetChangeRequestStatusUrl,json_encode($params));
			if(isset($changeRequestStatusResults->Response->ResponseStatus) && $changeRequestStatusResults->Response->ResponseStatus == 1){
				$database_flight->exec('update cancellation_details set RefundedAmount = "'.$changeRequestStatusResults->Response->RefundedAmount.'", CancellationCharge = "'.$changeRequestStatusResults->Response->CancellationCharge.'", ServiceTaxOnRAF = "'.$changeRequestStatusResults->Response->ServiceTaxOnRAF.'", ChangeRequestStatus = "'.$changeRequestStatusResults->Response->ChangeRequestStatus.'" where ChangeRequestId='.$changeRequestStatusResults->Response->ChangeRequestId);
			}
		}
		$flight_bookings=$database_flight->query("select bd.*,cd.*,td.BTBookingId,bd.date_add as bookingDate,td.PublishedFareDisplay from cancellation_details cd join booking_details bd on(cd.BookingId = bd.BookingId) left join ticket_details td on(bd.BookingId = td.BookingId) where bd.BookingId!=0 and bd.id_agent='".$_SESSION['authtnid']."'")->fetchAll();

		foreach($flight_bookings as $paylist)
		{
			$segmentOriginCityNameArr = explode(',',$paylist['segmentOriginCityName']);
			$segmentDestinationCityNameArr = explode(',',$paylist['segmentDestinationCityName']);
			$journeyFrom = $segmentOriginCityNameArr[0];
			$journeyTo = $segmentDestinationCityNameArr[count($segmentDestinationCityNameArr)-1];
			
			$paylist['refund_amount_approved'] = $paylist['refund_amount_approved']+0;
			$s.="<tr>
				<td class='centertext'>".$i."</td>
				<td class='centertext'>".$paylist['BTBookingId']."</td>
				<td class='centertext'>".date('d/m/Y', strtotime($paylist['bookingDate']))."</td>
				<td class='centertext'>".date('d/m/Y', strtotime($paylist['journey_date']))."</td>
				<td class='centertext'>".$journeyFrom."</td>
				<td class='centertext'>".$journeyTo."</td>
				<td class='centertext'>".$paylist['TicketId']."</td>
				<td class='centertext'>".$paylist['PublishedFareDisplay']."</td>
				<td class='centertext'>".(!empty($paylist['refund_amount_approved']) ? $paylist['refund_amount_approved'] : '')."</td>
				<td class='centertext'>".($paylist['is_cancel'] == '0' ? "<button class='btn buttonsearch' onclick='javascript:cancelBooking(".$id.");'>Cancel Booking</button>" : ($paylist['is_cancel'] == '1' ? '<span class="label color_field" style="background-color:RoyalBlue;color:white">Awaiting Approval</span>' : ($paylist['is_cancel'] == '2' ? '<span class="label color_field" style="background-color:DarkOrange;color:white">Awaiting Refund</span>' : '<span class="label color_field" style="background-color:#108510;color:white">Refund Successful</span>')))."</td>
			</tr>";
			$i++;
		}
		echo $s;
	}
	else if($_POST['getmods']=="searchreportflight")
	{
		$changeRequestArrValues = array(0 => 'NotSet',1 => 'Unassigned',2 => 'Assigned',3 => 'Acknowledged',4 => 'Completed',5 => 'Rejected',6 => 'Closed',7 => 'Pending',8 => 'Other');
		$where = '';
		if(isset($_POST['fromdate']) && !empty($_POST['fromdate']) && isset($_POST['todate']) && !empty($_POST['todate']))
			$where = " AND bd.journey_date >= '".date('Y-m-d', strtotime($_POST['fromdate']))."' AND bd.journey_date <= '".date('Y-m-d', strtotime($_POST['todate']))."'";
		else if(isset($_POST['fromdate']) && !empty($_POST['fromdate']))
			$where = " AND bd.journey_date >= '".date('Y-m-d', strtotime($_POST['fromdate']))."'";
		else if(isset($_POST['todate']) && !empty($_POST['todate']))
			$where = " AND bd.journey_date <= '".date('Y-m-d', strtotime($_POST['todate']))."'";
		$s = ''; $i = 1;
		
		$flight_bookings=$database_flight->query("select bd.*,cd.*,td.BTBookingId,bd.date_add as bookingDate,td.PublishedFareDisplay from cancellation_details cd join booking_details bd on(cd.BookingId = bd.BookingId) left join ticket_details td on(bd.BookingId = td.BookingId) where bd.BookingId!=0 and bd.id_agent='".$_SESSION['authtnid']."'".$where)->fetchAll();

		foreach($flight_bookings as $paylist)
		{
			$segmentOriginCityNameArr = explode(',',$paylist['segmentOriginCityName']);
			$segmentDestinationCityNameArr = explode(',',$paylist['segmentDestinationCityName']);
			$journeyFrom = $segmentOriginCityNameArr[0];
			$journeyTo = $segmentDestinationCityNameArr[count($segmentDestinationCityNameArr)-1];
			
			$paylist['refund_amount_approved'] = $paylist['refund_amount_approved']+0;
			$s.="<tr>
				<td class='centertext'>".$i."</td>
				<td class='centertext'>".$paylist['BTBookingId']."</td>
				<td class='centertext'>".date('d/m/Y', strtotime($paylist['bookingDate']))."</td>
				<td class='centertext'>".date('d/m/Y', strtotime($paylist['journey_date']))."</td>
				<td class='centertext'>".$journeyFrom."</td>
				<td class='centertext'>".$journeyTo."</td>
				<td class='centertext'>".$paylist['TicketId']."</td>
				<td class='centertext'>".$paylist['PublishedFareDisplay']."</td>
				<td class='centertext'>".(!empty($paylist['refund_amount_approved']) ? $paylist['refund_amount_approved'] : '')."</td>
				<td class='centertext'>".($paylist['is_cancel'] == '0' ? "<button class='btn buttonsearch' onclick='javascript:cancelBooking(".$id.");'>Cancel Booking</button>" : ($paylist['is_cancel'] == '1' ? '<span class="label color_field" style="background-color:RoyalBlue;color:white">Awaiting Approval</span>' : ($paylist['is_cancel'] == '2' ? '<span class="label color_field" style="background-color:DarkOrange;color:white">Awaiting Refund</span>' : '<span class="label color_field" style="background-color:#108510;color:white">Refund Successful</span>')))."</td>
			</tr>";
			$i++;
		}
		echo $s;
	}
	
}

?>