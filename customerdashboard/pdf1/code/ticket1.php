



<?php
	include('../../../include/database/config.php');
	error_reporting(0);
ob_start();

	$html = '

<table  align="center" cellspacing="5"  width="650px"  style="border: 1px solid #ddd;font-family:serif;">
  <tr><td> 
	<table width="100%">
 <tr> 
 <td width="80%" align="left" style="font-size:20px;font-weight: bold;">Service Confirmation Voucher </td> 
   <td width="20%" align="" style=" ;"> <img src="images/tcpdf_logo.png"> </td> 
</tr> 

<tr>
<td colspan=2  align="left" style="line-height:1.5;color: #555555">Buddies Booking ID - IN1706B5S4151195  </td>
</tr>
<!--<tr>
<td colspan=2   align="left" style="line-height:1.5;color: #555555" >Service Voucher No - NH8108053714032  </td>
</tr>-->

<tr>
<td colspan=2 width="100%" align="left" style=";line-height:1.5;border-bottom:1px solid #ddd;color: #555555;">Booking Date - Fri, 09 Jun 2017  </td>
</tr>

<tr> 
  
  <td colspan=2 align="left" style="font-size:10px;color: #555555;line-height:2;">Dear Partner,  </td>  
  <!--<td width="30%" style="font-size: 12px;color: #000000;line-height:2;text-align: right;"> <a style="text-decoration:none"> Print Book Confirmation </a> </td>-->
</tr> 


</table>



<table>
 
<tr>
<td   align="left" style="line-height:3;font-size:10px;color: #555555">Buddies has received a booking for your hotel as per the details below. The primary guest <b>Rajesh Rai</b>, will be carrying a copy of this e-voucher.   </td>
</tr>
<tr>
<td    align="left" style="line-height:3;font-size:10px;color: #555555" >For your reference, Buddies Booking ID is IN1704B3S3696823. </td>
</tr>
<tr> <td>&nbsp;</td></tr>
<tr >
<td  align="left" style="line-height:1;font-size:10px;color: #555555;">
The amount payable by buddies for this booking is Rs. <b>22950</b> including all taxes. Please email us at <a style="text-decoration:none">Hotel-Partner@buddiestours.com </a> if there is any discrepancy in this payment amount.  
 </td>
</tr>

<tr><td>&nbsp;</td></tr>
<tr>
<td  align="left" style="line-height:3;font-size:10px;color: #555555">
Kindly consider this as the confirmation e-voucher and provide the guest with the following inclusions and services. </td>
</tr>


<!--<tr>
<td  align="left" style="line-height:3;font-size:10px;color: #555555 ">
Team Buddiestours.com </td>
</tr>-->


<tr>
<td  align="left" style="line-height:2;font-size:14px;border-bottom:1px solid #ddd;">
Hotel Details
 </td>
</tr>

<tr>
<td align="left" style="font-weight:400;font-size: 14px;color: #000000;line-height:2;" >Hotel Name, Designation   </td>
</tr>

<tr>
<td  align="left" style="line-height:1.5;font-size:10px;color: #555555 ">
Grand Duff Road, Valley View, Lovedale

 </td>
</tr>

<tr>
<td  align="left" style="line-height:1.5;font-size:10px;color: #555555 ">
Lovedale, Ooty Ooty
 </td>
</tr>

<tr>
<td  align="left" style="line-height:1.5;font-size:10px;color: #555555 ">
Contact No: 91-5942-236901
 </td>
</tr>

<tr>
<td  align="left" style="line-height:1.5;font-size:10px;color: #555555 ">
Mobile : 91-9258317244
 </td>
</tr>

<!--<tr>
<td  align="left" style="line-height:2;font-size:12px;font-weight: bold;color: #000000; ">
Hotel/Service Provider Confirmation Number:
 </td>
</tr>-->

<tr>
<td  align="left" style="line-height:2;font-size:12px; "><b>Primary Guest:</b> <span style="color:#555555;font-size:10px; ">Rajesh Rai </span>
 </td>
</tr>

<tr>
<td  align="left" style="line-height:2;font-size:12px; ;color: #000000; ">
<b>No. of Rooms:</b> <span style="font-size:10px;color: #555555;"> 1 </span> </td>
</tr>

<tr> 
  <td align="left" style="font-weight:600;font-size: 16px;color: #000000;line-height:2;">Room 1</td>  
</tr> 


</table>

<!--<table>

<tr> 
 
  <td colspan="3" align="left" style="font-weight:600;font-size: 16px;color: #000000;line-height:2;">Room 1</td>  
</tr> 
  <tr> 
 <td width="40%" align="center" style="line-height:2;font-size:12px;color: #ffffff;border: 1px solid #ddd; background-color:grey; ">
<b>Check in:  </b> <span style="font-size:10px;color: #ffffff">Mon, Jun 12, 2017 12:00 PM </span> </td>
  <td width="40%" align="center" style="line-height:2;font-size:12px; ;color: #ffffff;border: 1px solid #ddd;    background-color: grey; ">
<b>Check out:   </b> <span style="font-size:10px;color: #ffffff">Thu, Jun 15, 2017 12:00 PM</span> </td>
  <td width="20%" align="center" style="line-height:2;font-size:12px; ;color: #ffffff;border: 1px solid #ddd;    background-color: grey; ">
<b>No. of Night(s):</b> <span style="font-size:10px;color: #ffffff">3 </span> </td>
</tr>

<tr>

 <td width="40%" align="center" style="line-height:2;font-size:12px;border: 1px solid #ddd ;color: #000000;  ">
<b>Room Type,Category:</b> <span style="font-size:10px;color: #555555">Double,premium suit</span> </td>

<td width="40%" align="center" style="line-height:2;font-size:12px;border: 1px solid #ddd ;color: #000000; ">
<b>Guest:</b> <span style="font-size:10px;color: #555555">2 Adult  </span> </td>

<td width="20%" align="center" style="line-height:2;font-size:12px;border: 1px solid #ddd;color: #000000; ">
<b>Meal Plan: </b> <span style="font-size:10px;color: #555555">Breakfast  </span> </td>

</tr>




</table>-->

<table width="100%">
 <tr> 
 <td width="35%" align="center" style="line-height:2;font-size:12px;color: #ffffff;border: 1px solid #ddd; background-color:grey; ">
<b>Check in:  </b> <span style="font-size:10px;color: #ffffff">Mon, Jun 12, 2017 12:00 PM </span> </td>
  <td width="45%" align="center" style="line-height:2;font-size:12px; ;color: #ffffff;border: 1px solid #ddd;    background-color: grey; ">
<b>Check out:   </b> <span style="font-size:10px;color: #ffffff">Thu, Jun 15, 2017 12:00 PM</span> </td>
  <td width="20%" align="center" style="line-height:2;font-size:12px; ;color: #ffffff;border: 1px solid #ddd;    background-color: grey; ">
<b>No. of Night(s):</b> <span style="font-size:10px;color: #ffffff">3 </span> </td>
</tr>
</table>


<table width="100%">
 <tr> 
 <td width="35%" align="center" style="line-height:2;font-size:12px;border: 1px solid #ddd ; ">
<b>Room Type,Category:</b> <span style="font-size:10px;color: #555555">Double,premium suit</span> </td>
  <td width="15%" align="center" style="line-height:2;font-size:12px;border: 1px solid #ddd ; ">
<b>Guest:</b> <span style="font-size:10px;color: #555555">3 Adult  </span> </td>
   <td width="30%" align="center" style="line-height:2;font-size:12px;border: 1px solid #ddd; ">
<b>No of Extra Bed(Adult):</b> <span style="font-size:10px;color: #555555">1  </span> </td>
<td width="20%" align="center" style="line-height:2;font-size:12px;border:1px solid #ddd ; ">
<b>Meal Plan: </b> <span style="font-size:10px;color: #555555">Breakfast  </span> </td>
</tr>
</table>


<table>
<tr> 
 <td align="left" style="font-weight:600;font-size: 16px;color: #000000;line-height:2;text-decoration:underline;">Special Notes:- </td>  
</tr>

<tr><td> &nbsp; </td> </tr>
</table>




<table>
<tr> 
 <td align="left" style="font-weight:600;font-size: 16px;color: #000000;line-height:2;text-decoration:underline;">General Hotel Policy:- </td>  
</tr>
</table>

<table cellpadding="5">
<tr>

<td style="font-size:10px;text-align:justify;color: #555555"><ul>
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
Entry to the hotel is at the sole discretion of the hotel authority. If the address on the photo identification card matches the city where the hotel is located, the hotel may refuse to provide accommodation. MakeMyTrip will not be responsible for any check-in denied by the hotel due to the aforesaid reasons.
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
 
  <td align="left" style="font-weight:600;font-size: 16px;color: #000000;line-height:2;text-decoration:underline;">Cancellation Policy:-  </td>  
</tr>
</table>


<table cellpadding="10">
<tr>
<td style="font-size:10px;text-align:justify;color: #555555">
<ul><li>
More than 3 days before check-in date: FREE CANCELLATION/MODIFICATION. 
</li>
<li>
3 days before check-in date: no refund.
</li>
<li>
In case of no show: no refund.Booking cannot be cancelled/modified on or after the check in date and time mentioned in the Hotel Confirmation Voucher.
</li>

</ul>
</td>
</tr>

</table>



</td>
</tr>
</table>




';
			/*	<tr style="background-color:#DD1B22;">
					<td><h2 style="">Buddies Ticket</h2></td>
					
				</tr>
				<tr>
				<td><h3>e-Ticket</h3> </td>
				</tr>
		
		
				
	
	
		
				
						<tr style="border:1px solid red;">
							<td style="">Hyderabad To Vijayawada- Fri, 14th Apr 2017</td>
								<td style="">Hyderabad To Vijayawada- Fri, 14th Apr 2017</td>
							<td style="text-align:center; width:70px; font-size:8px; padding-top:10px;">Buddies Booking ID</b>:<strong>BUS00053</td>
							<td style="text-align:center; font-size:8px; width:70px;">Ticket Number</b>: &nbsp;&nbsp;&nbsp; <strong>ABRS3747094</td>
						
						</tr>
						<tr><td colspan="11" style="border-top:1px solid #0171B6; height:1px;"></td></tr>
							<tr style="border:1px solid red;">
							<td style="width:50px; vertical-align:middle; padding-top:20px;"></td>
							<td style="text-align:center; width:70px; font-size:8px; padding-top:10px;"></td>
							<td style="text-align:center; font-size:8px; width:70px;"></td>
							<td style="text-align:center;font-size:8px; width:100px;"></td>
							<td style="text-align:center; font-size:8px;width:100px;"></td>
							<td style="text-align:center; font-size:8px;width:100px;"></td>
							<td style="text-align:center;font-size:8px;"></td>
						</tr>
						<tr><td colspan="11" style="border-top:1px solid #0171B6; height:1px;"></td></tr>
							<tr style="border:1px solid red;">
							<td style="width:50px; vertical-align:middle; padding-top:20px;"></td>
							<td style="text-align:center; width:70px; font-size:8px; padding-top:10px;"></td>
							<td style="text-align:center; font-size:8px; width:70px;"></td>
							<td style="text-align:center;font-size:8px; width:100px;"></td>
							<td style="text-align:center; font-size:8px;width:100px;"></td>
							<td style="text-align:center; font-size:8px;width:100px;"></td>
							<td style="text-align:center;font-size:8px;"></td>
						</tr>
						<tr><td colspan="11" style="border-top:1px solid #0171B6; height:1px;"></td></tr>
						</TABLE>
				
				

<div align="center">
<div style="width:70%;border:1px solid black;TEXT-ALIGN:LEFT;MARGIN:AUTO;">
<div>
</div>
<h2 style="">Buddies Ticket</h2>
						
						
<h3 align="center">e-Ticket</h3> 
<hr>
	<div style="width:50%;background-color:black;float:left;">
		<p><b>Hyderabad To Vijayawada- Fri, 14th Apr 2017</b></p></div>
		<div style="float:left;width:50%;"ALIGN="RIGHT">
		<p><b>Buddies Booking ID</b>:<strong>BUS00053</strong></p>
		<p><b>Ticket Number</b>: &nbsp;&nbsp;&nbsp; <strong>ABRS3747094</strong></p>														
	</div>										
													 
																
		<div  style="float:left;">
			<p ><B>Aarna Travels</b></p>
			<p >Volvo AC B9R Multi-Axle Semi Sleeper</b></p>
		</div>
												
													
		<div style="" ALIGN="RIGHT">
		<p><b>Booked on</b></p>
	<p> 14-04-2017@3:09 pm</p>
</div>	<br>					
										

	<div style="float:left;">
	
<h4 style="">Boarding Point</h4>
<span align="left"><span class="glyphicon glyphicon-map-marker"></span><h5 class="hydara"style="display:inline;": >Hyderabad</h5></span>
	</div>							
							
							
									
	<div style="float:left;">
	 <p> 
										  Adithya guest house,<br/>
											 Near pondicherry bus stand,<br/>
											 Puducherry,Contact-7867887767.
										</p>
									
</div>						
								
									
	<div style=""ALIGN="RIGHT">
	<span><h4 >Dropping Point</h4></span>
											<span ><span  class="glyphicon glyphicon-map-marker"></span><h5 class="hydara"style="display:inline;":>Vijayawada</h5></span>
</div >								  
										
					
							
		<div style="float:left;">
		
		<h4>Reporting Time</h4>
										
											<p class="time" ><b >8:00 pm</b></p>								
										
</div>								
							
		<div style="float:left;">
			<h4 >Boarding Time</h4>
										
											<p class="time"><b>8:00 pm</b></p>	
</div>
<div style="" ALIGN="RIGHT">
	
										<h4>Arrival Time</h4>
										<b>5:00 am</b>
</div>								
									
										
				
	<div style="background-color:black;">								 
								
							
						  
						  
						         
									   <h4 class="head">Terms and Conditions</h4>
									  
									   
											 <p >Buddies tours* is only a bus ticket agent.It does not operate of its own in order to provide a comprehensive choice of bus operators departure time and price to customers.</p>
							
											   <p>The arrival and departure times mentioned on the ticket are only tentative timings.</p>
											   <p>Passengers are requested to arrive at the boarding point at least 15 min. before the scheduled time of departure.</p>
											   <p>Buddies tours is not responsible for any accidents or loss of passenger belongings.</p>
											   <p>Buddies tours is not responsible for any delay or inconvenience during the journey due to breakdown of thevehicle or other reasons beyond the control of Buddies tours.</p>
											   <p>If a bus/service is canceled, for tickets booked through Buddies tours, the refund money is transferred back to the passenger\'s Credit/Debit card or Bank Account used for booking.</p>
											   <p>Cancellation charges are applicable on Original fare but not on the Discounted Fare.</p>
									   
	</div>		
<div style="float:left;width:50%;">	
									   
								
											
													<p><h4 class="head">Buddies Tours responsibillities do not include</h4></p><br>
									            
									                <p>1.The bus operator\'s bus not departing/reaching on time. </p>
									                <p>2.The bus operator\'s employees being rude.</p>
									                 <p>3.The bus operator\'s bus seats etc not being up to the customer\'s expectation.</p>
									                 <p>4.The bus operator cancelling the trip due to unavoidable reasons.</p>
									                 <p>5.The baggage of the customer getting lost/stolen/damaged.</p>
									                 <p>6.he bus operator changing a customer\'s seat at the last minute to accommodate a lady/child. </p>
											
</div>											 
						          
	<div style="float:left;">							
								<h4 class="head"align="left">Cancellation Policy</h4>
									
										 
											 <h5>Cancellation Timing</h5>
											 
													 <p >Start Time</p>
													 <p >B/W 0-1 hours of bus start time</p>
													 <p >B/W 1-2 hours of bus start time</p>
													 <p >B/W 2-4 hours of bus start time</p>
											 <p >Above 4 hours of bus start time</p>
	</div>												 
											 
											 
		<div>							
											
										<br>
										<br>
											 <h5>Charges</h5>
											
											  
													<p class="cancel_head" >(%)</p>
													<p class="cancel_rate" >0%</p>
													  <p class="cancel_rate" >10%</p>
													  <p class="cancel_rate" >50%</p>
													  <p class="cancel_rate" >90%</p>												
														
												
</div>								
							
<div>
<p>Partial cancellation is allowed for this ticket</p><p>Additional Cancellation Charge of Rs.15 per seat is applicable</p>
</div>								
								
						
<DIV ALIGN="CENTER">
							 
									   <h4 class="customer" >Customer Support and Enquiries</h4>
									   
										<p><span class="glyphicon glyphicon-earphone"></span>Buddies Customer Care (24*7)<b>+91  413-4500111, 2202352</b> </p>
									 
									   
									</div>	
	
			






</div>
</div>
									
</body>
	
			




 
</html>';*/
				
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

//$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, '', 'TRANSACTION REPORT'.(isset($_GET['from']) && !empty($_GET['from']) ? '  FROM '.$_GET['from'] : '').(isset($_GET['to']) && !empty($_GET['to']) ? '  To '.$_GET['to'] : ''));

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(13, 15, PDF_MARGIN_RIGHT);
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
$pdf->Output('paymentReport.pdf', 'D');

//============================================================+
// END OF FILE
//============================================================+
