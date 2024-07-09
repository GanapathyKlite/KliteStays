<?php 

include '../../include/dbconnect.php';

$host='localhost/buddies/admin1';
//$host='admin.buddiestours.com';

	
	$inv_no=$_GET['inv'];
	$get_invoice=mysqli_query($con,"select * from inv_add_invoice where inv_no=$inv_no");
	while($row=mysqli_fetch_array($get_invoice))
	{
	$sold_id=$row['sold_id'];
	$get_id=$row['id'];
	 $get_sold_to=mysqli_query($con,"select * from inv_agent_consultant where id=$sold_id ");
	 while($get_sold=mysqli_fetch_array($get_sold_to))
	 {
		$sold_to='M/s '.$get_sold['company_name'].',<br>';
		 $sold_address=$get_sold['address'];
		 $sold_to=$sold_to.$sold_address;
	 }

	 if(empty($sold_id))
	 {
		$sold_to=$row['sold_to'];	
	 }
	 $pax_name=$row['pax_name'];	
$agent=$row['agent_name'];
	 $inv_no = $row['inv_no'];
	 //$inv_date = $row['inv_date'];
         $inv_date_exp=explode("-",$row['inv_date']);
	$inv_date = $inv_date_exp[2].'-'.$inv_date_exp[1].'-'.$inv_date_exp[0];
	 $book_no = $row['book_no'];
	 $consultant_name =$row['consultant_name'];
	 $agent_name = '';
	 $agent_ref_no = '';
	 $arrival_date = $row['arrival_date'];
	 $tax = $row['tax'];
	 $advance = $row['advance'];
	 $total = $row['total'];
	 $baseprice=round(($total*100)/105.6,2);
	//$baseprice=floatval(round($baseprice,2));
	$calctax=round(($baseprice*5.6)/100,2);
	 $create_date = $row['create_date'];
 	 $descriptiondata =explode("^",$row['description']);
	 $description=$descriptiondata[1];
	 $currency=$row['currency'];
	 $offline_payment=$row['offline_payment'];
	 $online_payment=$row['online_payment'];
	 $totalamount=$baseprice+$calctax;
	 
	}



	
/*$html='
	
	
	<table style="width:100%;  font-size:15px; " border="0" cellspacing="0" cellpadding="0">
	<h2 style="font-weight:bold; font-size:40px; text-align:center; ">INVOICE</h2>




<tr><td style="width:20px;"></td><td style="text-align:right;"></td>
<td></td><td style="text-align:right;"><b>Invoice Date</b>&nbsp;&nbsp;&nbsp;:</td>

<td style="text-align:left;">'.$inv_date.'</td>
</tr>


</table>
	
	<td style="width:15%;"><b>Sold To</b>&nbsp;&nbsp;:&nbsp;</td>
		<td style="width:30%; text-align:left;">'.$sold_to.'</td>
	
<tr>
		<td style="width:15%;"><b>Guest Name</b>&nbsp;&nbsp;:&nbsp;</td>
		<td style="width:30%; text-align:left;">'.$agent.' '.$pax_name.'</td>
	</tr>
	
	';
*/
$html ='<h3 style="text-align:center; font-size:30px; font-weight:bold;">INVOICE</h3>

<table style="width:100%;  font-size:12px;" border="0" cellspacing="0" cellpadding="5">
	<tr>
		<td style="width:55%;">
			<table style="width:100%;  font-size:12px;" border="0" cellspacing="0" cellpadding="10">
				<tr>
					<td  style=" width:35%; text-align:right;" ><b>Sold To</b>&nbsp;&nbsp;&nbsp;:</td>
					<td style="width:65%; text-align:left; font-size:12px;" >'.$sold_to.'</td>
				</tr>
				<tr>
					<td style=" width:35%; text-align:right;" ><b>Guest Name</b>&nbsp;&nbsp;&nbsp;:</td>
					<td style=" width:65%; text-align:left; font-size:12px;" >'.$agent.' '.$pax_name.'</td>
				</tr>
			</table>
		</td>
		<td style="width:45%;">
			<table style="width:100%;  font-size:12px;" border="0" cellspacing="0" cellpadding="10">
				<tr>
					<td  style=" width:50%; text-align:right;" ><b>Invoice Number</b>&nbsp;&nbsp;&nbsp;:</td>
					<td style="width:50%; text-align:left; font-size:12px;" >'.$inv_no.'</td>
				</tr>
				<tr>
					<td style=" width:50%; text-align:right;" ><b>Invoice Date</b>&nbsp;&nbsp;&nbsp;:</td>
					<td style=" width:50%; text-align:left; font-size:12px;" >'.$inv_date.'</td>
				</tr>
				<tr>
					<td style=" width:50%; text-align:right;" ><b>BTT Booking No</b>&nbsp;&nbsp;&nbsp;:</td>
					<td style=" width:50%;  text-align:left; font-size:12px;" >'.$book_no.'</td>
				</tr>
				<tr>
					<td style=" width:50%;  text-align:right;" ><b>Arrival Date</b>&nbsp;&nbsp;&nbsp;:</td>
					<td style=" width:50%; text-align:left; font-size:12px;" >'.$arrival_date.'</td>
				</tr>
			</table>
		</td>
	</tr>
</table>

<table border="1" style="width:1024px; font-size:12px;" cellpadding="5">
<tr><td style="width:90px;"><b>S.NO</b></td><td style="width:470px;"><b>DESCRIPTION</b></td><td style="width:150px;"><b>AMOUNT</b></td></tr>
<tr><td style="width:90px; font-size:12px;">1</td><td style="width:470px; font-size:12px;">'.$description.'</td><td style="width:150px; font-size:12px;">'.$baseprice.'</td></tr>
<tr><td style="width:90px;"></td><td style="width:470px; text-align:right;">Sub total</td><td style="width:150px; font-size:12px;">'.$baseprice.'</td></tr>
<tr><td style="width:90px;"></td><td style="width:470px; text-align:right;">Service Tax</td><td style="width:150px; font-size:12px;">'.$calctax.'</td></tr>
<tr><td style="width:90px;"></td><td style="width:470px; text-align:right;">Advance</td><td style="width:150px; font-size:12px;">'.$advance.'</td></tr>
<tr><td style="width:90px;"></td><td style="width:470px; text-align:right;">Grand Total</td><td style="width:150px; font-size:12px;">'.$totalamount.'</td></tr>
</table>

<table border="0" style="width:1024px; font-size:15px;" cellpadding="10"><tr><td>'.no_to_words($totalamount).'only</td></tr></table>

<table border="0" style="width:1024px; font-size:15px;" cellpadding="10">
<tr><td style="width:20px;"></td><td></td><td style="width:200px;"></td><td><img src="images/bar_seal.png" style="width:120px;" alt="" />
   
   <p>Authorised Signature</p></td></tr></table>
   <hr>
   <table border="0" style="width:1024px; font-size:15px;" cellpadding="10">
<tr><td style="width:469px;">Direct All Inquiries To:<br>info@buddiestours.com<br>accounts@buddiestours.in</td><td style="width:512px;">Corporate Address:<br>Buddies Tours and Travels Pvt. Ltd.,<br>3, JVS Nagar,<br>Mudaliarpet,<br>Pondicherry - 605004<br>Phone : 0413 - 2202352/54</td></tr>
<tr><td>Note: This is an auto generated invoice.</td><td>Thanks for Your Business!</td></tr>


</table>';

function no_to_words($no)
{   
 $words = array('0'=> '' ,'1'=> 'one' ,'2'=> 'two' ,'3' => 'three','4' => 'four','5' => 'five','6' => 'six','7' => 'seven','8' => 'eight','9' => 'nine','10' => 'ten','11' => 'eleven','12' => 'twelve','13' => 'thirteen','14' => 'fourteen','15' => 'fifteen','16' => 'sixteen','17' => 'seventeen','18' => 'eighteen','19' => 'nineteen','20' => 'twenty','30' => 'thirty','40' => 'fourty','50' => 'fifty','60' => 'sixty','70' => 'seventy','80' => 'eighty','90' => 'ninty','100' => 'hundred &','1000' => 'thousand','100000' => 'lakh','10000000' => 'crore');
    if($no == 0)
        return ' ';
    else {
	$novalue='';
	$highno=$no;
	$remainno=0;
	$value=100;
	$value1=1000;       
            while($no>=100)    {
                if(($value <= $no) &&($no  < $value1))    {
                $novalue=$words["$value"];
                $highno = (int)($no/$value);
                $remainno = $no % $value;
                break;
                }
                $value= $value1;
                $value1 = $value * 100;
            }       
          if(array_key_exists("$highno",$words))
              return $words["$highno"]." ".$novalue." ".no_to_words($remainno);
          else {
             $unit=$highno%10;
             $ten =(int)($highno/10)*10;            
             return $words["$ten"]." ".$words["$unit"]." ".$novalue." ".no_to_words($remainno);
           }
    }
}

	
$html .='</body></html>';
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
$pdf->Output('invoice_'.$_GET['inv'].'.pdf');

?>

