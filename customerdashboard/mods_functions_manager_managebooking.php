<?php
if(!$_SERVER['HTTP_X_REQUESTED_WITH']== 'XMLHttpRequest')
{
   header("Location:login.php");
}
else
{
	session_start();
	error_reporting(E_ALL);
	include('../include/database/config.php');
	$date= new DateTime('now', new DateTimeZone('Asia/Kolkata'));
	if($_POST['getmods']=="fetchreport")
	{
		$gen_report=$database_bus->query("select bd.*,td.id as id_ticket,td.ticket_no,td.seats,td.partialCancellation,td.totalCommission,td.serviceCharge,td.seatFareDisplay from booking_details bd left join ticket_details td on(bd.id = td.b_id) where bd.id_agent='".$_SESSION['authtnid']."' and td.iscancel=0 and bd.journeyDate >= '".date('Y-m-d')."' order by bd.journeyDate desc");$i=1;
	if($gen_report)
		{
			$gen_report=$gen_report->fetchAll();
		}else
		{
			$gen_report=array();
		}
		foreach($gen_report as $paylist)
		{
			$id=$paylist["id"];
			$passengerNameArr = explode(',',$paylist['name']);
			$seatFare = array_sum(explode(',',$paylist['seatFareDisplay']));
			$totalCommission = array_sum(explode(',',$paylist['totalCommission']));
			$s.="<tr>
				<td>".$i."</td>
				<td>".$paylist['orderno']."</td>
				<td>".date('d/m/Y', strtotime($paylist['booking_datetime']))."</td>
				<td>".date('d/m/Y', strtotime($paylist['journeyDate']))."</td>
				<td>".$paylist['ticket_no']."</td>
				<td>".$passengerNameArr[0]."</td>
				<td>".$paylist['leavingfrom']."</td>
				<td>".$paylist['goingto']."</td>
				<td>".(!empty($seatFare) && $seatFare != '0.00' ? $seatFare+0 : '')."</td>
				<td>".(!empty($totalCommission) ? $totalCommission : '')."</td>
				<td>".(!empty($paylist['serviceCharge']) && $paylist['serviceCharge'] != '0.00' ? $paylist['serviceCharge']+0 : '')."</td>
				<td nowrap>
					<button class='btn buttonsearch'  onclick=\"javascript:window.open('".$root_dir."busvac/bus/search/ticket_confirmation.php?orderinfo=".$paylist['orderno']."','_blank');\">&nbsp;View</button>&nbsp;
				</td>
				<td>
					<button id='resendticket' class='btn buttonsearch' onclick='javascript:showResendTicket(".$id.");'>&nbsp;Resend</button>
				</td>
				<td>
					<button id='cancelticket' class='btn buttonsear' onclick='javascript:showCancelTicket(".$id.",".$paylist['partialCancellation'].");'>&nbsp;Cancel</button>
					<input type='Hidden' id='".$id."_seats' value='".$paylist['seats']."'>
				</td>
			</tr>";
			$i++;
		}
		echo $s;
	}
	else if($_POST['getmods']=="searchreport")
	{
		$where = '';
		if(isset($_POST['fromdate']) && !empty($_POST['fromdate']) && isset($_POST['todate']) && !empty($_POST['todate']))
			$where = " AND journeyDate >= '".date('Y-m-d', strtotime($_POST['fromdate']))." 00:00:00' AND journeyDate <= '".date('Y-m-d', strtotime($_POST['todate']))." 23:59:59'";
		else if(isset($_POST['fromdate']) && !empty($_POST['fromdate']))
			$where = " AND journeyDate >= '".date('Y-m-d', strtotime($_POST['fromdate']))." 00:00:00'";
		else if(isset($_POST['todate']) && !empty($_POST['todate']))
			$where = " AND journeyDate <= '".date('Y-m-d', strtotime($_POST['todate']))." 23:59:59'";

		$gen_report_search=$database_bus->query("select bd.*,td.id as id_ticket,td.ticket_no,td.seats,td.partialCancellation,td.totalCommission,td.serviceCharge,td.seatFareDisplay from booking_details bd left join ticket_details td on(bd.id = td.b_id) where bd.id_agent='".$_SESSION['authtnid']."' and td.iscancel=0 and bd.journeyDate >= '".date('Y-m-d')."'".$where." order by bd.journeyDate desc");
		if($gen_report_search)
		{
			$gen_report_search=$gen_report_search->fetchAll();
		}else
		{
			$gen_report_search=array();
		}
		
		$s = ''; $i = 1;
		foreach($gen_report_search as $searchresult)
		{
			$id=$searchresult["id"];
			$passengerNameArr = explode(',',$searchresult['name']);
			$seatFare = array_sum(explode(',',$searchresult['seatFareDisplay']));
			$totalCommission = array_sum(explode(',',$searchresult['totalCommission']));
			$s.="<tr>
				<td>".$i."</td>
				<td>".$searchresult['orderno']."</td>
				<td>".date('d/m/Y', strtotime($searchresult['booking_datetime']))."</td>
				<td>".date('d/m/Y', strtotime($searchresult['journeyDate']))."</td>
				<td>".$searchresult['ticket_no']."</td>
				<td>".$passengerNameArr[0]."</td>
				<td>".$searchresult['leavingfrom']."</td>
				<td>".$searchresult['goingto']."</td>
				<td>".(!empty($seatFare) && $seatFare != '0.00' ? $seatFare+0 : '')."</td>
				<td>".(!empty($totalCommission) ? $totalCommission : '')."</td>
				<td>".(!empty($searchresult['serviceCharge']) && $searchresult['serviceCharge'] != '0.00' ? $searchresult['serviceCharge']+0 : '')."</td>
				<td nowrap>
					<button class='btn buttonsearch'  onclick=\"javascript:window.open('".$root_dir."busvac/bus/search/ticket_confirmation.php?orderinfo=".$searchresult['orderno']."','_blank');\">&nbsp;View</button>&nbsp;
				</td>
				<td>
					<button id='resendticket' class='btn buttonsearch' onclick='javascript:showResendTicket(".$id.");'>&nbsp;Resend</button>
				</td>
				<td>
					<button id='cancelticket' class='btn buttonsear' onclick='javascript:showCancelTicket(".$id.",".$paylist['partialCancellation'].");'>&nbsp;Cancel</button>
					<input type='Hidden' id='".$id."_seats' value='".$searchresult['seats']."'>
				</td>
			</tr>";
			$i++;
		}
		echo $s;
		
	}
	else if($_POST['getmods']=="fetchreportflight")
	{
		$cancelRequestArr = array(
								0 => 'NotSet',
								1 => 'Unassigned',
								2 => 'Assigned',
								3 => 'Acknowledged',
								4 => 'Completed',
								5 => 'Rejected',
								6 => 'Closed',
								7 => 'Pending',
								8 => 'Other'
							);
	

		$flight_bookings=$database_flight->query("select bd.*,td.id as id_ticket,td.BTBookingId,td.date_add as td_date_add,td.TicketNumber,td.PublishedFareDisplay,td.TotalCommissionAgent,cd.BookingId as cancelledBookingId,cd.ChangeRequestStatus from booking_details bd join ticket_details td on(bd.BookingId = td.BookingId) left join cancellation_details cd on(bd.BookingId = cd.BookingId) where bd.BookingId!=0 and bd.journey_date >= '".date('Y-m-d')."' and bd.id_agent='".$_SESSION['authtnid']."' order by td.date_add desc");
		if($flight_bookings)
		{
			$flight_bookings=$flight_bookings->fetchAll();
		}else
		{
			$flight_bookings=array();
		}
		$s = ''; $i = 1;
		foreach($flight_bookings as $paylist)
		{
			$segmentOriginCityNameArr = explode(',',$paylist['segmentOriginCityName']);
			$segmentDestinationCityNameArr = explode(',',$paylist['segmentDestinationCityName']);
			$passengerFirstName = explode(',',$paylist['passengerFirstName']);
			$passengerLastName = explode(',',$paylist['passengerLastName']);
			$ticketNumberArr = explode(',',$paylist['TicketNumber']);

			$s.="<tr>
				<td>".$i."</td>
				<td>".$paylist['BTBookingId']."</td>
				<td>".date('d/m/Y', strtotime($paylist['date_add']))."</td>
				<td>".date('d/m/Y', strtotime($paylist['journey_date']))."</td>
				<td>".$paylist['PNR']."</td>
				<td>".$ticketNumberArr[0]."</td>
				<td>".ucwords($passengerFirstName[0])." ".ucwords($passengerLastName[0])."</td>
				<td>".$segmentOriginCityNameArr[0]."</td>
				<td>".$segmentDestinationCityNameArr[count($segmentDestinationCityNameArr)-1]."</td>
				<td>".$paylist['PublishedFareDisplay']."</td>
				<td>".$paylist['TotalCommissionAgent']."</td>
				<td nowrap>
					<button class='btn buttonsearch'  onclick=\"javascript:window.open('".$root_dir."flightvac/flight/booking/ticket_confirmation.php?bookingID=".$paylist['BookingId']."','_blank');\">&nbsp;View</button>&nbsp;
				</td>
				<td>
					<button id='resendticket' class='btn buttonsearch' onclick='javascript:showResendTicket(".$paylist['id_ticket'].");'>&nbsp;Resend</button>
				</td>
				<td>
					".(!isset($paylist['cancelledBookingId']) || empty($paylist['cancelledBookingId']) || true ? "<button class='btn buttonsear'  style=' ' onclick=\"javascript:window.open('".$root_dir."agentdashboard/flightmanagebooking.php?BookingId=".$paylist['BookingId']."','_blank');\">&nbsp;Cancel</button>" : "<span>".$cancelRequestArr[$paylist['ChangeRequestStatus']]."</span>")."
				</td>
			</tr>";
			$i++;
		}
		echo $s;
	}
	else if($_POST['getmods']=="searchreportflight")
	{
		$cancelRequestArr = array(
								0 => 'NotSet',
								1 => 'Unassigned',
								2 => 'Assigned',
								3 => 'Acknowledged',
								4 => 'Completed',
								5 => 'Rejected',
								6 => 'Closed',
								7 => 'Pending',
								8 => 'Other'
							);
		$where = '';
		if(isset($_POST['fromdate']) && !empty($_POST['fromdate']) && isset($_POST['todate']) && !empty($_POST['todate']))
			$where = " AND bd.journey_date >= '".date('Y-m-d', strtotime($_POST['fromdate']))."' AND bd.journey_date <= '".date('Y-m-d', strtotime($_POST['todate']))."'";
		else if(isset($_POST['fromdate']) && !empty($_POST['fromdate']))
			$where = " AND bd.journey_date >= '".date('Y-m-d', strtotime($_POST['fromdate']))."'";
		else if(isset($_POST['todate']) && !empty($_POST['todate']))
			$where = " AND bd.journey_date <= '".date('Y-m-d', strtotime($_POST['todate']))."'";
		$s = ''; $i = 1;

		$flight_bookings=$database_flight->query("select bd.*,td.id as id_ticket,td.BTBookingId,td.date_add as td_date_add,td.TicketNumber,td.PublishedFareDisplay,td.TotalCommissionAgent,cd.BookingId as cancelledBookingId,cd.ChangeRequestStatus from booking_details bd join ticket_details td on(bd.BookingId = td.BookingId) left join cancellation_details cd on(bd.BookingId = cd.BookingId) where bd.BookingId!=0 and bd.journey_date >= '".date('Y-m-d')."'".$where." and bd.id_agent='".$_SESSION['authtnid']."' order by td.date_add desc");

		if($flight_bookings)
		{
			$flight_bookings=$flight_bookings->fetchAll();
		}else
		{
			$flight_bookings=array();
		}
		foreach($flight_bookings as $paylist)
		{
			$segmentOriginCityNameArr = explode(',',$paylist['segmentOriginCityName']);
			$segmentDestinationCityNameArr = explode(',',$paylist['segmentDestinationCityName']);
			$passengerFirstName = explode(',',$paylist['passengerFirstName']);
			$passengerLastName = explode(',',$paylist['passengerLastName']);
			$ticketNumberArr = explode(',',$paylist['TicketNumber']);

			$s.="<tr>
				<td>".$i."</td>
				<td>".$paylist['BTBookingId']."</td>
				<td>".date('d/m/Y', strtotime($paylist['date_add']))."</td>
				<td>".date('d/m/Y', strtotime($paylist['journey_date']))."</td>
				<td>".$paylist['PNR']."</td>
				<td>".$ticketNumberArr[0]."</td>
				<td>".ucwords($passengerFirstName[0])." ".ucwords($passengerLastName[0])."</td>
				<td>".$segmentOriginCityNameArr[0]."</td>
				<td>".$segmentDestinationCityNameArr[count($segmentDestinationCityNameArr)-1]."</td>
				<td>".$paylist['PublishedFareDisplay']."</td>
				<td>".$paylist['TotalCommissionAgent']."</td>
				<td nowrap>
					<button class='btn buttonsearch'  onclick=\"javascript:window.open('".$root_dir."flightvac/flight/booking/ticket_confirmation.php?bookingID=".$paylist['BookingId']."','_blank');\">&nbsp;View</button>&nbsp;
				</td>
				<td>
					<button id='resendticket' class='btn buttonsearch' onclick='javascript:showResendTicket(".$paylist['id_ticket'].");'>&nbsp;Resend</button>
				</td>
				<td>
					".(!isset($paylist['cancelledBookingId']) || empty($paylist['cancelledBookingId']) ? "<button class='btn buttonsear'  style=' ' onclick=\"javascript:window.open('".$root_dir."agentdashboard/flightmanagebooking.php?BookingId=".$paylist['BookingId']."','_blank');\">&nbsp;Cancel</button>" : "<span class='cancel_request'>".$cancelRequestArr[$paylist['ChangeRequestStatus']]."</span>")."
				</td>
			</tr>";
			$i++;
		}
		echo $s;
	}
	
}

?>