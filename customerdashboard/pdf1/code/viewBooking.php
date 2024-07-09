<?php
	include('../../../include/database/config.php');
$manage=isset($_GET['manage']) && !empty($_GET['manage']) ? $_GET['manage'] : '';
$cancel=isset($_GET['cancel']) && ($_GET['cancel']!='') ? $_GET['cancel'] : '';
	if($manage==3)
	{
	$html = '<table cellpadding="0" cellspacing="0" width="100%" style="text-align:center;">
				<tr style="background-color:#DD1B22; color:#fff;">
					<td style="width:50px; vertical-align:middle; padding-top:20px;">SL.No</td>
					<td style="text-align:center; width:70px;">Booking ID</td>
					<td style="text-align:center; width:70px; padding-top:20px;">Booking Date</td>
					<td style="text-align:center; width:70px;">Journey Date</td>
					
					<td style="text-align:center; width:60px;">Ticket No.</td>
					<td style="text-align:center; width:80px;">Name</td>
					<td style="text-align:center; width:80px;">Source</td>
					<td style="text-align:center; width:80px;">Destination</td>
					<td style="text-align:center; width:70px;">Total Fare</td>
					<td style="text-align:center; width:90px;">Refund Amount</td>
				</tr>
				<tr><td colspan="11" style="border-top:1px solid #0171B6; height:1px;"></td></tr>';
		}
		else
		{
			$html = '<table cellpadding="0" cellspacing="0" width="100%" style="text-align:center;">
			<tr style="background-color:#DD1B22; color:#fff;">
				<td style="width:50px; vertical-align:middle; padding-top:20px;">SL.No</td>
				<td style="text-align:center; width:70px;">Booking ID</td>
				<td style="text-align:center; width:70px; padding-top:20px;">Booking Date</td>
				<td style="text-align:center; width:70px;">Journey Date</td>
				
				<td style="text-align:center; width:70px;">Ticket No.</td>
				<td style="text-align:center; width:80px;">Name</td>
				<td style="text-align:center; width:80px;">Source</td>
				<td style="text-align:center; width:80px;">Destination</td>
				<td style="text-align:center; width:60px;">Total Fare</td>
				<td style="text-align:center; width:50px;">Comm</td>
				<td style="text-align:center; width:50px;">Srv Chrg</td>
			</tr>
			<tr><td colspan="11" style="border-top:1px solid #0171B6; height:1px;"></td></tr>';
		}
				$i=1;
				
				$id_agent = (isset($_GET['id']) ? $_GET['id'] : '');
	
				$where = '';
				if(isset($_GET['from']) && !empty($_GET['from']) && isset($_GET['to']) && !empty($_GET['to']))
					$where = " AND booking_datetime >= '".date('Y-m-d', strtotime($_GET['from']))." 00:00:00' AND booking_datetime <= '".date('Y-m-d', strtotime($_GET['to']))." 23:59:59'";
				else if(isset($_GET['from']) && !empty($_GET['from']))
					$where = " AND booking_datetime >= '".date('Y-m-d', strtotime($_GET['from']))." 00:00:00'";
				else if(isset($_GET['to']) && !empty($_GET['to']))
					$where = " AND booking_datetime <= '".date('Y-m-d', strtotime($_GET['to']))." 23:59:59'";

				//$booking_details=$database_bus->query("select bd.*,td.ticket_no from booking_details bd left join ticket_details td on(bd.id = td.b_id) where bd.id_agent='".$id_agent."' and td.iscancel=".(isset($_GET['cancel']) && !empty($_GET['cancel']) ? '1' : '0').(isset($_GET['confirm']) && !empty($_GET['confirm']) ? " and bd.journeyDate < '".date('Y-m-d')."'" : "").$where)->fetchAll();

			$cancel=isset($_GET['cancel']) && ($_GET['cancel']!='') ? $_GET['cancel'] : '';			

			$booking_details=$database_bus->query("select bd.*,td.id as id_ticket,td.ticket_no,td.seats,td.partialCancellation,td.totalCommission,td.serviceCharge,td.seatFareDisplay from booking_details bd left join ticket_details td on(bd.id = td.b_id) where bd.id_agent='".$id_agent."' and td.iscancel in($cancel) and bd.journeyDate >= '".date('Y-m-d')."'".$where." order by bd.journeyDate desc");
			if($booking_details)
			{
				$booking_details=$booking_details->fetchAll();
			}else
			{
				$booking_details=array();
			}
				foreach($booking_details as $row)
				{
					$passengerNameArr = explode(',',$row['name']);
					$seatFare = array_sum(explode(',',$row['seatFareDisplay']));
					$totalCommission = array_sum(explode(',',$row['totalCommission']));
					if($manage==3)
					{
						$html .= '
						<tr style="border:1px solid red;">
							<td style="width:50px; vertical-align:middle; padding-top:20px;">'.$i.'</td>
							<td style="text-align:center; width:70px; font-size:8px; padding-top:10px;">'.$row['orderno'].'</td>
							<td style="text-align:center; font-size:8px; width:70px;">'.date('H:i:s', strtotime($row['booking_datetime'])).'</td>
							<td style="text-align:center;font-size:8px; width:70px;">'.date('d/m/Y', strtotime($row['journeyDate'])).'</td>
							<td style="text-align:center; font-size:8px;width:60px;">'.$row['ticket_no'].'</td>
							<td style="text-align:center; font-size:8px;width:80px;">'.$passengerNameArr[0].'</td>
							<td style="text-align:center;font-size:8px;width:80px;">'.$row['leavingfrom'].'</td>
							<td style="text-align:center;font-size:8px;width:80px;">'. $row['goingto'].'</td>
							<td style="text-align:center;font-size:8px;width:70px;">'.array_sum($seatFare).'</td>
							<td style="text-align:center;font-size:8px;width:90px;"></td>
							
						</tr>
						<tr><td colspan="11" style="border-top:1px solid #0171B6; height:1px;"></td></tr>';
					}else
					{
						$html .= '
						<tr style="border:1px solid red;">
							<td style="width:50px; vertical-align:middle; padding-top:20px;">'.$i.'</td>
							<td style="text-align:center; width:70px; font-size:8px; padding-top:10px;">'.$row['orderno'].'</td>
							<td style="text-align:center; font-size:8px; width:70px;">'.date('H:i:s', strtotime($row['booking_datetime'])).'</td>
							<td style="text-align:center;font-size:8px; width:70px;">'.date('d/m/Y', strtotime($row['journeyDate'])).'</td>
							<td style="text-align:center; font-size:8px;width:70px;">'.$row['ticket_no'].'</td>
							<td style="text-align:center; font-size:8px;width:80px;">'.$passengerNameArr[0].'</td>
							<td style="text-align:center;font-size:8px;width:80px;">'.$row['leavingfrom'].'</td>
							<td style="text-align:center;font-size:8px;width:80px;">'. $row['goingto'].'</td>
							<td style="text-align:center;font-size:8px;width:60px;">'.(!empty($seatFare) && $seatFare != '0.00' ? $seatFare+0 : '').'</td>
							<td style="text-align:center;font-size:8px;width:50px;">'.(!empty($totalCommission) ? $totalCommission : '').'</td>
							<td style="text-align:center;font-size:8px;width:50px;">'.(!empty($row['serviceCharge']) && $row['serviceCharge'] != '0.00' ? $row['serviceCharge']+0 : '').'</td>
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
$pdf->SetKeywords('TCPDF, PDF, example, test, bus');
// set default header data
if(isset($_GET['type'])&&($_GET['type']!=''))
{
	$type=$_GET['type'];
}else
{
	$type="BUS BOOKING REPORT";
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
$pdf->Output('busReport.pdf', 'D');

//============================================================+
// END OF FILE
//============================================================+
