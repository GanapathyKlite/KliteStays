<?php session_start(); 
//ini_set('display_errors', 'on');
include ('../../include/dbconnect.php');
$cuser=$_GET['cusername'];
$queryc = "SELECT * FROM admin_user WHERE name='".$_GET['cusername']."'";
$select= mysqli_query($con,$queryc);
while($row=mysqli_fetch_array($select))
{
	$usermob=$row['mobile'];
	
}	

$query = "SELECT * FROM trans_list WHERE t_id='".$_GET['clientid']."'";	
$select= mysqli_query($con,$query);
while($row=mysqli_fetch_array($select))
{
	$clientname=$row['client_name'];
	//$startfrom=$row['start_from'];
	$startfrom = date('d-m-Y', strtotime($row['start_from']));
	//$endto=$row['end_to'];
	$endto = date('d-m-Y', strtotime($row['end_to']));
	$vehicle=$row['vehicle_type'];
	$clientcontactno=$row['contact_no'];
	$arrival=$row['client_arrival'].'-'.$row['arrival_via'].'-'.$row['a_train_flight_no'];
	$departure=$row['client_departure'].'-'.$row['departure_via'].'-'.$row['d_train_flight'];
	
}
$query1 = "SELECT * FROM t_vehicle_types WHERE vt_id='".$vehicle."'";	
$select1= mysqli_query($con,$query1);
while($row1=mysqli_fetch_array($select1))
{
	$vehiclename=$row1['vehicle_type_name'];
}
$get_details="SELECT details FROM `trans_list` WHERE t_id='".$_GET['clientid']."'";
$select2= mysqli_query($con,$get_details);
	while($row2=mysqli_fetch_array($select2))
	{
		$dets=$row2['details'];	
	}

	

$html = '<h1 style="text-align:center; color:red;">Itinerary Details</h1><h2>Emergency Contact: '.$cuser.' : '.$usermob.',8220044442 – Buddies Tours</h2><table cellpadding="0" cellspacing="0" width="100%" border="1" style="text-align:center; margin-left:120px; font-size:14px; "><tr style="background-color:#ffffff; #000"><td style=" vertical-align:middle; padding:20px 30px 0 0; font-size:18px;">Guest Name</td><td style="text-align:center;padding-top:10px;">'.$clientname.'</td></tr><tr style="background-color:#ffffff; #000"><td style=" vertical-align:middle; padding-top:20px; font-size:18px;">Date of Arrival  & Departure</td><td style="text-align:center;padding-top:10px;">'.$startfrom.' To '.$endto.'</td></tr><tr style="background-color:#ffffff; #000"><td style=" vertical-align:middle; padding-top:20px; font-size:18px;">Contact Number</td><td style="text-align:center;padding-top:10px;">'.$clientcontactno.'</td></tr><tr style="background-color:#ffffff; #000"><td style=" vertical-align:middle; padding-top:20px; font-size:18px;">Vehicle</td><td style="text-align:center;padding-top:10px;">'.$vehiclename.'</td></tr><tr style="background-color:#ffffff; #000"><td style=" vertical-align:middle; padding-top:20px; font-size:18px;">Arrival</td><td style="text-align:center;padding-top:10px;">'.$arrival.'</td></tr><tr style="background-color:#ffffff; #000"><td style=" vertical-align:middle; padding-top:20px; font-size:18px;">Departure</td><td style="text-align:center;padding-top:10px; font-weight:normal;">'.$departure.'</td></tr><tr>'.$dets.'</tr></table>';

				
				//$html .= '<tr style="border:1px solid red;"><td style=" vertical-align:middle; padding-top:20px;">'.$i.'</td><td style="text-align:center; width:80px; font-size:8px; padding-top:10px;">'.$row['2'].'<br>('.$customer_mobile.')'.'</td><td style="text-align:center; font-size:8px; width:70px;">'.$from1.'</td><td style="text-align:center;font-size:8px; width:70px;">'.$to1.'</td><td style="text-align:center; font-size:8px;width:30px;">'.$row['5'].'</td><td style="text-align:center; font-size:8px;width:100px;">'.$arrivaldata.'</td><td style="text-align:center; font-size:8px;width:100px;">'.$departture_data.'</td><td style="text-align:center;font-size:8px;">'.$dest1_data.'</td><td style="text-align:center;font-size:8px;">'.$trans_data.'</td><td style="text-align:center;font-size:8px;">'.$driver_data.'</td><td style="text-align:center; font-size:8px; width:50px;">'.$row['10'].'</td></tr><tr><td colspan="11" style="border-top:1px solid #0171B6; height:1px;"></td></tr>';
				
				
				
				//$html .='</table>';
		
				
				
			
			
									 
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
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');
// set default header data

$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE);
//$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
//$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
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
$assets_cnt=explode(",",$assets);
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
										}
// set font
$pdf->SetFont('helvetica', '', 9);
$pdf->SetMargins(10, 20, 10);

//$pdf->SetAutoPageBreak(TRUE, 0);
// add a page
$pdf->AddPage();


// create some HTML content


$html=$html;




// output the HTML content
$pdf->writeHTML($html, true, 0, true, 0);

// reset pointer to the last page
$pdf->lastPage();

// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output('vehicleallotment.pdf', 'D');

//============================================================+
// END OF FILE
//============================================================+
