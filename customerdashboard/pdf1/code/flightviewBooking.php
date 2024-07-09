<?php
	include('../../../include/database/config.php');
	if($_GET['manage']==1||$_GET['manage']==2)
	{
	$html = '<table cellpadding="0" cellspacing="0" width="100%" style="text-align:center;">
				<tr style="background-color:#DD1B22; color:#fff;">
					<td style="width:50px; vertical-align:middle; padding-top:20px;">SL.No</td>
					<td style="text-align:center; width:60px; padding-top:20px;">Booking ID</td>
					<td style="text-align:center; width:70px;">Booking Date</td>
					<td style="text-align:center; width:60px;">Journey Date</td>
					<td style="text-align:center; width:60px;">PNR</td>
					<td style="text-align:center; width:80px;">Ticket No.</td>
					<td style="text-align:center; width:80px;">Passenger Name</td>
					<td style="text-align:center; width:80px;">Source</td>
					<td style="text-align:center; width:80px;">Destination</td>
					<td style="text-align:center;width:60px;">Total Fare</td>
					<td style="text-align:center;width:40px;">Comm</td>
				</tr>
				<tr><td colspan="11" style="border-top:1px solid #0171B6; height:1px;"></td></tr>';
	}
	else
	{
			$html = '<table cellpadding="0" cellspacing="0" width="100%" style="text-align:center;">
				<tr style="background-color:#DD1B22; color:#fff;">
					<td style="width:50px; vertical-align:middle; padding-top:20px;">SL.No</td>
					<td style="text-align:center; width:60px; padding-top:20px;">Booking ID</td>
					<td style="text-align:center; width:70px;">Booking Date</td>
					<td style="text-align:center; width:60px;">Journey Date</td>
					<td style="text-align:center; width:120px;">Source</td>
					<td style="text-align:center; width:120px;">Destination</td>
					<td style="text-align:center; width:80px;">Ticket Id</td>
					<td style="text-align:center; width:80px;">Total Amount</td>
					<td style="text-align:center; width:80px;">Refund Amount</td>					
				</tr>
				<tr><td colspan="11" style="border-top:1px solid #0171B6; height:1px;"></td></tr>';
	}
	
				$i=1;
				
				$id_agent = (isset($_GET['id']) ? $_GET['id'] : '');
	
				$where = '';
				if(isset($_GET['from']) && !empty($_GET['from']) && isset($_GET['to']) && !empty($_GET['to']))
		$where = " AND bd.journey_date >= '".date('Y-m-d', strtotime($_GET['from']))."' AND bd.journey_date <= '".date('Y-m-d', strtotime($_GET['to']))."'";
		else if(isset($_GET['from']) && !empty($_GET['from']))
			$where = " AND bd.journey_date >= '".date('Y-m-d', strtotime($_GET['from']))."'";
		else if(isset($_GET['to']) && !empty($_GET['to']))
			$where = " AND bd.journey_date <= '".date('Y-m-d', strtotime($_GET['to']))."'";

				

			if($_GET['manage']==1)
			{
				$query="select bd.*,td.BTBookingId,td.date_add as td_date_add,td.TicketNumber,td.PublishedFareDisplay,td.TotalCommissionAgent from booking_details bd join ticket_details td on(bd.BookingId = td.BookingId) where bd.BookingId!=0 and bd.id_agent='".$id_agent."' and journey_date < '".date('Y-m-d')."'".$where." order by td.date_add desc";
			}elseif($_GET['manage']==2)
			{
				$query="select bd.*,td.id as id_ticket,td.BTBookingId,td.date_add as td_date_add,td.TicketNumber,td.PublishedFareDisplay,td.TotalCommissionAgent,cd.BookingId as cancelledBookingId,cd.ChangeRequestStatus from booking_details bd join ticket_details td on(bd.BookingId = td.BookingId) left join cancellation_details cd on(bd.BookingId = cd.BookingId) where bd.BookingId!=0 and bd.journey_date >= '".date('Y-m-d')."'".$where." and bd.id_agent='".$id_agent."' order by td.date_add desc";
			}elseif($_GET['manage']==3)
			{
				$query="select bd.*,cd.*,td.BTBookingId,bd.date_add as bookingDate,td.PublishedFareDisplay from cancellation_details cd join booking_details bd on(cd.BookingId = bd.BookingId) left join ticket_details td on(bd.BookingId = td.BookingId) where bd.BookingId!=0 and bd.id_agent='".$id_agent."'".$where;
			}			

			$booking_details=$database_flight->query($query);

			if($booking_details)
			{
				$booking_details=$booking_details->fetchAll();
				
			}else
			{
				$booking_details=array();
			}
				foreach($booking_details as $row)
				{
					$segmentOriginCityNameArr = explode(',',$row['segmentOriginCityName']);
					$segmentDestinationCityNameArr = explode(',',$row['segmentDestinationCityName']);
					$passengerFirstName = explode(',',$row['passengerFirstName']);
					$passengerLastName = explode(',',$row['passengerLastName']);
					$ticketNumberArr = explode(',',$row['TicketNumber']);
					$journeyFrom = $segmentOriginCityNameArr[0];
					$journeyTo = $segmentDestinationCityNameArr[count($segmentDestinationCityNameArr)-1];
					if($_GET['manage']==1||$_GET['manage']==2)
					{
						$html .= '
						<tr style="border:1px solid red;">
							<td style="width:50px; vertical-align:middle; padding-top:20px;">'.$i.'</td>
							<td style="text-align:center; width:60px; font-size:8px; padding-top:10px;">'.$row['BTBookingId'].'</td>
							<td style="text-align:center; font-size:8px; width:70px;">'.date('d/m/Y', strtotime($row['date_add'])).'</td>
							<td style="text-align:center;font-size:8px; width:60px;">'.($row['journey_date'] != '0000-00-00' ? date('d/m/Y', strtotime($row['journey_date'])) : '').'</td>
							<td style="width:60px;text-align:center; font-size:8px;">'.$row['PNR'].'</td>
							<td style="text-align:center; font-size:8px;width:80px;">'.$ticketNumberArr[0].'</td>
							<td style="text-align:center; font-size:8px;width:80px;">'.ucwords($passengerFirstName[0])." ".ucwords($passengerLastName[0]).'</td>
							<td style="text-align:center;font-size:8px;width:80px;">'.$segmentOriginCityNameArr[0].'</td>
							<td style="text-align:center;font-size:8px;width:80px;">'.$segmentDestinationCityNameArr[count($segmentDestinationCityNameArr)-1].'</td>
							<td style="text-align:center;font-size:8px;width:60px;">'.$row['PublishedFareDisplay'].'</td>
							<td style="text-align:center;font-size:8px;width:40px;">'. $row['TotalCommissionAgent'].'</td>
						</tr>
						<tr><td colspan="11" style="border-top:1px solid #0171B6; height:1px;"></td></tr>';
					}
					else
					{

						$html .= '
						<tr style="border:1px solid red;">
							<td style="width:50px; vertical-align:middle; padding-top:20px;">'.$i.'</td>
							<td style="text-align:center; width:60px; font-size:8px; padding-top:10px;">'.$row['BTBookingId'].'</td>
							<td style="text-align:center; font-size:8px; width:70px;">'.date('d/m/Y', strtotime($row['bookingDate'])).'</td>
							<td style="text-align:center;font-size:8px; width:60px;">'.($row['journey_date'] != '0000-00-00' ? date('d/m/Y', strtotime($row['journey_date'])) : '').'</td>
							<td style="width:120px;text-align:center; font-size:8px;">'.$journeyFrom.'</td>
							<td style="width:120px;text-align:center; font-size:8px;">'.$journeyTo.'</td>
							<td style="text-align:center; font-size:8px;width:80px;">'.$row['TicketId'].'</td>
							<td style="text-align:center; font-size:8px;width:80px;">'.$row['PublishedFareDisplay'].'</td>
							<td style="text-align:center;font-size:8px;width:80px;">'.(!empty($row['refund_amount_approved']) ? $row['refund_amount_approved'] : '').'</td>						
						</tr>
						<tr><td colspan="11" style="border-top:1px solid #0171B6; height:1px;"></td></tr>';
					}
					$i++;
				}
				$html .='</table>';

?>
<?php
//============================================================+
// File name   : example_021.php
// Begin       : 2008-03-04
// Last Update : 2013-05-14
//
// Description : Example 021 for TCPDF class
//               WriteHTML text flow
//
// Author: Nicola Asuni
//
// (c) Copyright:
//               Nicola Asuni
//               Tecnick.com LTD
//               www.tecnick.com
//               info@tecnick.com
//============================================================+

/**
 * Creates an example PDF TEST document using TCPDF
 * @package com.tecnick.tcpdf
 * @abstract TCPDF - Example: WriteHTML text flow.
 * @author Nicola Asuni
 * @since 2008-03-04
 */

// Include the main TCPDF library (search for installation path).
require_once('tcpdf_include.php');

// create new PDF document
$pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nicola Asuni');
$pdf->SetTitle('TCPDF Example 021');
$pdf->SetSubject('TCPDF Tutorial');
$pdf->SetKeywords('TCPDF, PDF, example, test, flight');
// set default header data
if(isset($_GET['type'])&&($_GET['type']!=''))
{
	$type=$_GET['type'];
}else
{
	$type="FLIGHT BOOKING REPORT";
}
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, '', $type.(isset($_GET['from']) && !empty($_GET['from']) ? '  FROM '.$_GET['from'] : '').(isset($_GET['to']) && !empty($_GET['to']) ? '  To '.$_GET['to'] : ''));

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
	require_once(dirname(__FILE__).'/lang/eng.php');
	$pdf->setLanguageArray($l);
}

// ---------------------------------------------------------
/*$assets_cnt=explode(",",$assets);
										//print_r($assets_cnt);
										$aaa1='';
										foreach($assets_cnt as $asset)
										{ 
											$aaa1.= "*";
										}
$assets_cnt1=explode(",",$passets);
										//print_r($assets_cnt);
										$aaa2='';
										foreach($assets_cnt1 as $asset1)
										{ 
											$aaa2.="*";// "<i id='star1' class='fa fa-star' style='font-weight:bold; display:inline; color:#2F96B4;'></i>";
										}*/
// set font
$pdf->SetFont('helvetica', '', 9);

// add a page
$pdf->AddPage();


// create some HTML content
$html = $html;





// output the HTML content
$pdf->writeHTML($html, true, 0, true, 0);

// reset pointer to the last page
$pdf->lastPage();

// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output('flightReport.pdf', 'D');

//============================================================+
// END OF FILE
//============================================================+
