



<?php
	include('../../../include/database/config.php');
	error_reporting(0);
ob_start();

	$html = '

<table style="border:1px solid #808080;line-height:1.5; width:100%;">
	<tr><td>
		<table style=" width:100%;color: #555555;font-size:10px;line-height:1.5; "> 
			<?php 
				$i = 1; $inc=1; $a=0; $pnr="gtest";
					foreach($detailsArr as $pnrKey => $detial){ if($i==1){ $i++;?>

			<tr>
				<td style="color:black;font-size: 18px;font-weight: bold;" >E-Ticket</td>
				<td rowspan="3" style="font-size: 12px ;text-align:right; border-bottom:1px solid #eee;"></td>
			</tr>
			
			<tr>
				<td style="border-bottom:1px solid #eee;" ><span style="color:black;">Booking Date on:</span> </td>
			</tr>
			<?php } ?>
			
		</table>


			
			
			
			

	

		<table style=" width:100%;color: #555555; font-size:10px;">
		
		<tr>
			<TD> &nbsp;</TD>
		</tr>
		<tr >
				<TD colspan="2" style="font-size: 14px;margin-bottom: 5px;margin-top: 20px;color: black !important;">Iterinarly and reservatrion</TD>
			</tr>
			<tr>
				<td><span style="color:black;">Booking ID:</span> 232323232</td>
				
			</tr>
			<tr>
				<td><span style="color:black;">Airline Pnr:</span><?php  echo $pnrKey; ?></td>
				
			</tr>
		</table>
		<?php } ?>
		
		<table  style="border:1px solid #eee; width:96%; color: #555555; font-size:10px; line-height:1.5;" cellspacing=5>
		   <tr><td colspan="4">&nbsp;</td></tr>
			<tr>
				
				<td style="text-align: center;">
				 </td>
				 <td style="text-align: center;border-left:1px solid #eee;border-right:1px solid #eee;">Departure<br>
				   <b>Terminal  </b><br>
				   
				 </td>
				 <td style="text-align: center;border-right:1px solid #eee;">Arrival<br>
				   <b></b><b></b><br>
				
				 </td>
				 <td style="text-align: center;">
				  
				   Cabin :
				 </td>
				
			</tr>
			<tr>
				<td colspan="2"> Flight</td>
				<td colspan="2">Meal Plan: <b>North Indian meals</b></td>
				<td colspan="2">Baggages: <b>25kg</b></td>
			
				
			</tr>
		</table>

		<?php  $pnr=$pnrKey;} ?>
		<table style="border:1px solid #eee; width:96%;border-top:none;color: #555555;font-size:10px;  line-height:1.5;" cellpadding="0" cellspacing="0"  >
			<tr>
			<td colspan="8">&nbsp;</td>
			
			</tr>
			<tr style="text-align:center;">
			   
				<td style="background-color:grey;color:white; width:10%;">S.no</td>
				<td style="background-color:grey;color:white; width:20%;">Passenger Name</td>
				<td style="background-color:grey;color:white; width:10%;">Type</td>
				<td style="background-color:grey;color:white;width:10%;">Gender</td>
				<td style="background-color:grey;color:white; width:10%;" >Airline pnr</td>
				
			</tr>
			
			<tr><td colspan="7">&nbsp;</td></tr>

			<tr style="text-align:center;">
			  
				<td style=""><?php echo $j;?></td>
				<td style=""></td>
				<td style=""></td>
				<td style=""></td>
				<td style="" ></td>
				
			</tr>
			<tr><td colspan="7"></td></tr>
			<tr style="text-align:center;">
			   
				<td style="">1</td>
				<td style="">RAm</td>
				<td style="">Adult</td>
				<td style="">F</td>
				<td style="" >GH1232</td>
				
			</tr>


		</table>
		
			<table style=" width:100%;color: #555555; font-size:10px">
			<tr>
				<td>&nbsp;</td>
			</tr>
		<tr >
				
				<TD style="font-size: 14px;margin-bottom: 5px;margin-top: 20px;color: black !important;">Return Journey</TD>
			</tr>
		</table>

			<table style="border:1px solid #eee; width:100%;color: #555555; font-size:10px; line-height:1.5;" cellspacing=5>
			<tr>
				<td>&nbsp;&nbsp;</td>
				<td style="text-align: center;">Indigo<br>
				   Indigo<br>
				   6E-345
				 </td>
				 <td style="text-align: center;border-left:1px solid #eee;border-right:1px solid #eee;">Departure<br>
				   <b>(PNE)</b>Pune,<b>Terminal 1</b><br>
				   Saturday,13th may,2017 00:45hrs
				 </td>
				 <td style="text-align: center;border-right:1px solid #eee;">Arrival<br>
				   <b>(KCH)</b>Kochi,<b>Terminal 2</b><br>
				   Saturday,13th may,2017 00:45hrs
				 </td>
				 <td style="text-align: center;">1-stop flight<br>
				   Duration:9hr 15min<br>
				   Cabin :economy
				 </td>
				 <td>&nbsp;&nbsp;</td>
			</tr>
			<tr>
			<td>&nbsp;</td>
				<td colspan="2">Meal Plan: <b>North Indian meals</b></td>
				<td colspan="2">Baggages: <b>25kg</b></td>
				<td>&nbsp;</td>
				
			</tr>
		</table>
		<table style="border:1px solid #eee; width:100%;border-top:none;color: #555555;font-size:10px; line-height:1.5; " cellpadding="0" cellspacing="0"  >
			<tr>
			<td colspan="8">&nbsp;</td>
			
			</tr>
			<tr style="text-align:center;">
			    <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
				<td style="background-color:grey;color:white;">S.no</td>
				<td style="background-color:grey;color:white;">Passenger Name</td>
				<td style="background-color:grey;color:white;">Type</td>
				<td style="background-color:grey;color:white;">Gender</td>
				<td style="background-color:grey;color:white;" >Airline pnr</td>
				<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
			</tr>
			<tr><td colspan="7">&nbsp;</td></tr>

			<tr style="text-align:center;">
			    <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
				<td style="">1</td>
				<td style="">RAm</td>
				<td style="">Adult</td>
				<td style="">M</td>
				<td style="" >GH1232</td>
				<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
			</tr>
			<tr><td colspan="7"></td></tr>
			<tr style="text-align:center;">
			    <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
				<td style="">1</td>
				<td style="">RAm</td>
				<td style="">Adult</td>
				<td style="">F</td>
				<td style="" >GH1232</td>
				<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
			</tr>


		</table>
		<table style="border:1px solid #eee; width:100%;border-top:none;color: #555555;font-size:10px; line-height:1.5;">
		<tr><td colspan="5" >&nbsp;</td>
		</tr>
		<tr>
			<td colspan="2" rowspan="5">This is an electronic ticket . Please carry a positive idenfication forcheck in.</td>
			<td>Airfare<span></span> </td>
			<td>10,000.00</td>
			<td style="">&emsp;&emsp;&emsp;&emsp;</td>
		</tr>
		<tr>
			<td>JnTax</td><td> 8,000.00</td>
		</tr>
		<tr>
			<td>Fees & Surcharge</td><td>8,000.00</td>
		</tr>
		<tr>
			<td>Total Fare</td><td>10,000.00</td>
		</tr>
		
		
		
			
		</table>
		<table style="border:1px solid #eee; width:100%;border-top:none;color: #555555;font-size:10px;">
		<tr>
			<td>&nbsp;</td>
		</tr>
			<tr>
				<td style="text-decoration:underline;font-weight:bold;">Terms and Conditions:
				</td>
				</tr>
				<tr>
				<td style="font-size:9px;"><ol>

    <li>Carriage and other services provide by the carrier are subject to conditions of carriage which hereby incorporated by reference.</li>
   <li> The conditions may be obtained from the issuing carrier.If the passenger\'s journey involves an ultimate destination or stop in a country
    other than country of departure the Warsaw convention may be applicable and convention governs and in most case limits the
    liablity of carriers for death or personal injury and in respect of loss of or damage to baggage</li></ol>

	</td>
			</tr>
		</table>
		</td></tr>
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
