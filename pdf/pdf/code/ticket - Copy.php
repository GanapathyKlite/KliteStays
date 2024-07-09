



<?php
	include('../../../include/database/config.php');
	error_reporting(0);
ob_start();

	$html = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;

<table cellpadding="4" cellspacing="0" width="600px"  style="font-size:12px;line-height:1.5;border:1px solid #ccc;" >
	

    <tr >
<td  colspan="2"><img src='.$root_dir.'logo.png"></td>
<td colspan="1"><h4 style="font-size:12px;"><b>e-Ticket</b></h4>
</td>
<td colspan="2" style="text-align:right;font-size:12px;"><h4>'.$_SESSION['agentname'].'</h4></td>

</tr>


<tr><td colspan="3"style="width:100%;border-width:1px ;border-style:solid;border-color:white white black white; "></td></tr>

	
	<tr>
		<td colspan="2" align="left" style="border-right:none;"><b>'.$source_name.' to '.$dest_name.'</td>
        <td colspan="1" align="left" style="border-right:none;"><b>'.$Journey_Date.'</td>
		
		<td colspan="2" align="right"style="border-left:none;"><b>Buddies booking-Id</b>:'.$orderInfo.'<br><b>Ticket Number</b>:'.$Ticket_no.'</td>
		
		
	</tr>
	
	
		<tr>
		<td colspan="3" align="left">'.$operatorname.'</td>
		
		<td colspan="2" align="right" ><b>Booked on</b></td>
		</tr>
			<tr>
		<td colspan="3" align="left" style="border-bottom:1px solid black;">'.$bustype.'</td>
		
		<td colspan="2" align="right" style="border-bottom:1px solid black;">'.date("d/m/Y", strtotime($CreatedTime)).'@'.date("g:i a", strtotime($CreatedTime)).'</td>
		</tr>
	

    <tr rowspan="4">
		<td colspan="1"style="border-bottom:1px solid black;color:#DB0B0B;font-weight:bolder;"align="left"><br>Boarding Point<br>
		<span class="glyphicon glyphicon-map-marker"></span><b style="color:black;" >'.$source_name.'</b></td>
		<td colspan="1" style="border-bottom:1px solid black;color:#DB0B0B;font-weight:bolder;"align="right"><br>Dropping Point<br><span  class="glyphicon glyphicon-map-marker"></span>
		<b style="color:black;" >'.$dest_name.'</b></td>
        <td colspan="3" align="center"  style="border-bottom:1px solid black;font-weight:bolder;">'.$landmark_string.'<br></td>

		
		
	</tr>


	<tr>
	<td style="color:#DB0B0B;font-weight:bolder;"align="left">Arrival Time</td>
	<td colspan="3" style="color:#DB0B0B;font-weight:bolder;"align="center">Reporting Time</td>
	<td style="color:#DB0B0B;font-weight:bolder;" align="right">Boarding Time</td>
	</tr>
	<tr style="border-bottom:1px solid black;" >
	<td align="left" style="border-bottom:1px solid black;">'.date("g:i a", strtotime($Arr_Time)).'</td>
	<td colspan="3" align="center" style="border-bottom:1px solid black;">'.date("g:i a", strtotime($Start_Time)).'</td>
	<td align="right" style="border-bottom:1px solid black;">'.date("g:i a", strtotime($Start_Time)).'</td>
	</tr>
	
	<tr align="center"><th style="color:#DB0B0B;">#</th>
	<th style="color:#DB0B0B;">Traveller</th>
	<th style="color:#DB0B0B;"> Age</th>
	<th style="color:#DB0B0B;">Seat</th>
	<th style="color:#DB0B0B;">Fare</th>
	</tr>
	
	<tr align="center">
	<td>'.$i.'</td>
	<td>'.$fetch_passinfoarr['Passenger_Name'].'</td>
	<td>'.$fetch_passinfoarr['Age'].'</td>
	<td>'.$fetch_passinfoarr['Seat_Num'].'</td>
	<td>'.$seatFare_explode[0].'.00</td>
	</tr>
	<tr>
	<td colspan="5"align="left" style="border-top:1px solid black;color:#DB0B0B;font-weight:bolder;">Terms and Conditions</td>
	</tr>
	<tr><td colspan="5" align="left" style="border-bottom:1px solid black;" >Buddies tours* is only a bus ticket agent.It does not operate of its own in order to provide a comprehensive choice of bus operators departure time and price to customers.<br>

						The arrival and departure times mentioned on the ticket are only tentative timings.<br>

						Passengers are requested to arrive at the boarding point at least 15 min. before the scheduled time of departure.<br>

						Buddies tours is not responsible for any accidents or loss of passenger belongings.<br>

						Buddies tours is not responsible for any delay or inconvenience during the journey due to breakdown of thevehicle or other reasons beyond the control of Buddies tours.<br>

						If a bus/service is canceled, for tickets booked through Buddies tours, the refund money is transfer#DB0B0B back to the passenger\'s C#DB0B0Bit/Debit card or Bank Account used for booking.<br>

						Cancellation charges are applicable on Original fare but not on the Discounted Fare.</td></tr>
	<tr>
	<td colspan="3" style="color:#DB0B0B;font-weight:bolder;"align="left">Buddies Tours responsibillities do not include</td>
	<td colspan="2" align="left"><b style="color:#DB0B0B;">Cancellation policy</b></td></tr>
	 
		<tr >	    
				<td colspan="3" align="left" >
				1.The bus operator\'s bus not departing/reaching on time.<br>

				2.The bus operator\'s employees being rude.<br>

				3.The bus operator\'s bus seats etc not being up to the customer\'s expectation.<br>

				4.The bus operator cancelling the trip due to unavoidable reasons.<br>

				5.The baggage of the customer getting lost/stolen/damaged.<br>

				6.he bus operator changing a customer\'s seat at the last minute to accommodate a lady/child.<br>
				</td>
				<td align="left" style="width:200px;">
				<b style="color:#4f5b66" >Cancellation timing</b><br>

					<ol align="left"style="padding:0 0;list-style-type:none;">
					'.$hrs_before_time.'
				
					</ol>
				
					</td>
					<td align="center" style="width:100px;">
				<b style="color:#4f5b66" >charges(%)</b>
			<ol align="center"style="padding:0 0;list-style-type:none;">
			  '.$return_amt.'
		
			</ol>
		

					

				</td>
	</tr>
	<tr>
	<td colspan="3"></td>
	<td colspan="2" align="left">Partial cancellation is allowed for this ticket<br>

Additional Cancellation Charge of Rs.15 per seat is applicable
	</td>
	</tr>

	<tr>
	<td></td>
	<td  style=" background-color:#999999;color:black;border-radius:25px;border:1px solid black;" colspan="3" align="center">Customer Support and Enquiries<br>Buddies Customer Care (24*7)+91 413-4500111, 2202352</td>
	<td></td>
	</tr>
	

</table>';
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

$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, '', 'TRANSACTION REPORT'.(isset($_GET['from']) && !empty($_GET['from']) ? '  FROM '.$_GET['from'] : '').(isset($_GET['to']) && !empty($_GET['to']) ? '  To '.$_GET['to'] : ''));

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
$pdf->Output('paymentReport.pdf', 'D');

//============================================================+
// END OF FILE
//============================================================+
