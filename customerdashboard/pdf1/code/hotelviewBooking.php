<?php
	include('../../../include/database/config.php');

	$html = '<table cellpadding="0" cellspacing="0" width="100%" style="text-align:center;">
				<tr style="background-color:#DD1B22; color:#fff;">
					<td style="width:50px; vertical-align:middle; padding-top:20px;">SI.No</td>
					<td style="text-align:center; width:70px; padding-top:20px;">Booking ID</td>
					<td style="text-align:center; width:70px;">Booking Date</td>
					<td style="text-align:center; width:70px;">Check In</td>
					<td style="text-align:center; width:100px;">Check Out</td>
					<td style="text-align:center; width:60px;">Customer</td>
					<td style="text-align:center; width:100px;">Hotel</td>
					<td style="text-align:center; width:60px;">Destination</td>
					<td style="text-align:center; width:70px;">No.of Rooms</td>
					<td style="text-align:center; width:80px;">Total Amount</td>
					<td style="text-align:center; width:80px;">Commission</td>
					
				</tr>
				<tr><td colspan="11" style="border-top:1px solid #0171B6; height:1px;"></td></tr>';
				$i=1;
				
				$id_agent = (isset($_GET['id']) ? $_GET['id'] : '');
	
				$where = '';
					if(isset($_GET['fromdate']) && !empty($_GET['fromdate']) && isset($_GET['todate']) && !empty($_GET['todate']))
					$where = " AND hb.check_in >= '".date('Y-m-d', strtotime($_GET['fromdate']))." 00:00:00' AND hb.check_in <= '".date('Y-m-d', strtotime($_GET['todate']))." 23:59:59'";
				else if(isset($_GET['fromdate']) && !empty($_GET['fromdate']))
					$where = " AND hb.check_in >= '".date('Y-m-d', strtotime($_GET['fromdate']))." 00:00:00'";
				else if(isset($_GET['todate']) && !empty($_GET['todate']))
					$where = " AND hb.check_in <= '".date('Y-m-d', strtotime($_GET['todate']))." 23:59:59'";

				$manage=false;
				if(isset($_GET['manage']) && ($_GET['manage']==1))
				{$manage=true;
					$query="select hb.*,p.txtPropertyName,concat(c.name,',',s.name) as destination from ps_hotel_booking hb left join ps_property p on(hb.id_property = p.id_property) left join ps_city c on(hb.city_id = c.id_city) left join ps_state s on(c.id_state = s.id_state) where hb.id_agent='".$id_agent."'".$where.($manage ? ' and hb.check_in >= \''.date('Y-m-d').' 00:00:00\' and hb.is_cancel!=3' : ' and hb.check_in < \''.date('Y-m-d').' 00:00:00\' and hb.is_cancel=0')." order by hb.id desc";
				}elseif(isset($_GET['manage']) && ($_GET['manage']==2))
				{
					$query="select hb.*,p.txtPropertyName,concat(c.name,',',s.name) as destination from ps_hotel_booking hb left join ps_property p on(hb.id_property = p.id_property) left join ps_city c on(hb.city_id = c.id_city) left join ps_state s on(c.id_state = s.id_state) where hb.id_agent='".$id_agent."'".$where." and hb.is_cancel=3 order by hb.id desc";
				}elseif(isset($_GET['manage']) && ($_GET['manage']==0))
				{

					$manage=false;
					$query="select hb.*,p.txtPropertyName,concat(c.name,',',s.name) as destination from ps_hotel_booking hb left join ps_property p on(hb.id_property = p.id_property) left join ps_city c on(hb.city_id = c.id_city) left join ps_state s on(c.id_state = s.id_state) where hb.id_agent='".$id_agent."'".$where.($manage ? ' and hb.check_in >= \''.date('Y-m-d').' 00:00:00\' and hb.is_cancel!=3' : ' and hb.check_in < \''.date('Y-m-d').' 00:00:00\' and hb.is_cancel=0')." order by hb.id desc";
				}

					$booking_details=$database->query($query)->fetchAll();

				foreach($booking_details as $row)
				{
					$html .= '
						<tr style="border:1px solid red;">
							<td style="font-size:8px; width:50px; vertical-align:middle; padding-top:20px;">'.$i.'</td>
							<td style="font-size:8px; width:70px; vertical-align:middle; padding-top:20px;">'.$row['booking_reference'].'</td>
							<td style="text-align:center; width:70px; font-size:8px; padding-top:10px;">'.date('d/m/Y', strtotime($row['date_add'])).'</td>
							<td style="text-align:center; font-size:8px; width:70px; padding-top:10px;">'.date('d/m/Y', strtotime($row['check_in'])).'</td>
							<td style="text-align:center;font-size:8px; width:100px;">'.date('d/m/Y', strtotime($row['check_out'])).'</td>
							<td style="text-align:center; font-size:8px;width:60px;">'.ucfirst($row['customer_firstname']).' '.ucfirst($row['customer_lastname']).'</td>
							<td style="text-align:center; font-size:8px;width:100px;">'.ucwords($row['txtPropertyName']).'</td>							
							<td style="text-align:center;font-size:8px;width:60px;">'.$row['destination'].'</td>
							<td style="text-align:center;font-size:8px;width:70px;">'.$row['room_count'].'</td>
							<td style="text-align:center;font-size:8px;width:80px;">'.$row['grand_total_price'].'</td>
							<td style="text-align:center;font-size:8px;width:80px;"></td>
						</tr>
						<tr><td colspan="11" style="border-top:1px solid #0171B6; height:1px;"></td></tr>';
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
$pdf->SetKeywords('TCPDF, PDF, example, test, Guide');
// set default header data
if(isset($_GET['type'])&&($_GET['type']!=''))
{
	$type=$_GET['type'];
}else
{
	$type="HOTEL BOOKING REPORT";
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
$pdf->Output('bookingReport.pdf', 'D');

//============================================================+
// END OF FILE
//============================================================+
