<?php
session_start(); 
include ('../../include/dbconnect.php');
$host='localhost/currentbuddies/admin1';
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
$html ='<html>
<head>
<meta charset="utf-8">
<title></title>
<link rel="stylesheet" type="text/css" href="http://'.$host.'/css/style.css" />
<link rel="stylesheet" type="text/css" href="http://'.$host.'/css/bootstrap.css" />
<link rel="stylesheet" type="text/css" href="http://'.$host.'/css/bootstrap-responsive.css" />
<style>
th{
text-align:center;	
}
.ui-button-icon-only .ui-button-text,
.ui-button-icons-only .ui-button-text {
	padding: .7em;
	text-indent: -9999999px;
	position:relative;
	top:-5px;	
}
.ui-state-default
{
	padding:.5em;	
}
.ui-state-hover ,
.ui-state-focus {
	background: #fff;
}
 .ui-autocomplete {
    max-height: 100px;
    overflow-y: auto;
    /* prevent horizontal scrollbar */
    overflow-x: hidden;
  }
  /* IE 6 doesnt support max-height
   * we use height instead, but this forces the menu to always be this tall
   */
  * html .ui-autocomplete {
    height: 100px;
  }
  tfoot ,tr, td
  {
	  
  }
@media print
{
	@page A4
	{
		
	}
	input[type=text],textarea
	{
		border:none !important;	
		margin-left:10px;
		padding-top:0;
	}
	.table-bordered
	{
	border:none !important;	
	}
	.inv_txt
	{
		font-family:Engravers MT;
		font-size:30px;
		font-weight:bold;
		text-align:center;
		top:30px;
		right:70px;
		position:relative;	
	}
}
	.inv_txt
		{
			font-family:Engravers MT;
			font-size:40px;
			font-weight:bold;
			text-align:center;
			top:30px;
			right:70px;
			position:relative;	
		}
.top_table input[type=text],.top_table textarea
{
	margin-left:10px;	
	text-transform:capitalize;
}
input{
width:150px;	

}
input[type=radio],input[type=checkbox]{
width:20px;	
padding:0;
}
.bt-control
{
	margin:5px;
	padding:0;
	
}
html, body, div, span, applet, object, iframe, h1, h2, h3, h4, h5, h6, p, blockquote, pre, a, abbr, acronym, address, big, cite, code, del, dfn, em, font, img, ins, kbd, q, s, samp, small, strike, strong, sub, sup, tt, var, b, u, i, center, dl, dt, dd, ol, ul, li, fieldset, form, label, legend, caption {
    margin: 0px;
    padding: 0px;
    border: 0px none;
    outline: 0px none;
    font-size: 16px;
    background: none repeat scroll 0% 0% transparent;
	
}
.padding-right
{
	padding-right:20px;		
}
</style>
<body onLoad="print();">
<div class="container" style="width:800px;">   
<div style="position:relative;margin-bottom:15px;">
<button onClick="window.print();" class="btn btn-default hidden" style="float:right;background:#fff;padding:5px 10px;"><span class="glyphicon glyphicon-print"></span> Print</button>
<button onClick="window.location.href=edit_invoice.php?id='.$get_id.'" class="btn btn-default hidden" style="margin-right:10px;float:right;background:#fff;padding:5px 10px;"><span class="glyphicon glyphicon-edit"></span> Edit</button>
</div>
<div style="position:relative;margin-bottom:10px;height:90px;">
<div style="float:left">
<img src="http://'.$host.'/invoice/format_files/logo.png" style="border-width:0px;width:300px;left:-20px;position:relative;">
</div>
<div style="float:right">
<h2 class="inv_txt">INVOICE</h2>
</div>
</div>
<table>
          <tr>
               <td style="width:60px; height: 20px;">
                   Sold To :</td>
               <td rowspan="6" valign="top">
                    <div class="bt-control">
                    	<textarea rows="7" cols="30" readonly style="resize:none;border:none;padding:0;">'.$sold_to .'</textarea>
                        
					</div>

				</td>
                <td style="width:160px; height: 20px;">
               </td>
               <td style="width:170px; height: 20px;" align="left">
                   Invoice Number <span style="float:right;">:</span></td>
               <td style="width:220px; height: 20px;">
                   &nbsp;
                  
                    <div class="bt-control">
                    BTT/INV/'.$inv_no .'
                    </div></td>
           </tr>
           <tr>
                  <td style="width:80px; height: 20px;">
               </td>
               <td style="width:160px; height: 20px;">
               </td>
               <td style="width:170px; height: 20px;" align="left">
                   Invoice Date <span style="float:right;">:</span></td>
               <td style="width:220px; height: 20px;">
                   &nbsp;
                   <div class="bt-control">
                    '.$inv_date .'
                    </div>
                   </td>
           </tr>
           <tr>
               <td style="width: 100px; height: 20px">
               </td>
               <td style="width:300px; height: 20px;">
               </td>
               <td align="left" style="width: 160px; height: 20px">
                   BTT Booking No <span style="float:right;">:</span></td>
               <td style="width: 220px; height: 20px">
                  <div class="bt-control">
                    BTT/BN/'.$book_no .'
                    </div>
                  </td>
           </tr>
           <tr>
               <td style="width: 100px; height: 20px;">
               </td>
               <td style="width:160px; height: 20px;">
               </td>
               <td align="left" style="width: 150px; height: 20px;">
                   BTT Consultant <span style="float:right;">:</span></td>
               <td style="width: 200px; height: 20px;">
                   <div class="bt-control" style="width:175px;">
                   '.$consultant_name .'
                    </div>
                    </td>
                  
           </tr>
              <tr>
               <td style="width: 100px; height: 20px;">
                   </td>
               <td style="width: 300px; height: 20px;">
                   </td>
               <td align="left" style="width: 150px; height: 20px;">
                   Arrival Date<span style="float:right;">:</span></td>
               <td style="width: 200px; height: 20px;">
                  <div class="bt-control">
                    '.$arrival_date .'
                    </div>
                  </td>
           </tr>
          
           </tbody>
           </table>
           <table style="width:100%;margin-bottom:10px;margin-top:10px;">
           <tr>
               <td style="width:100px;">
               <strong>Guest Name :</strong> 
                   </td>
               <td colspan="2" style="font-weight:bold;text-transform:capitalize;text-align:left;">
                   '.strtolower($pax_name) .'
                   </td>
              
               <td align="right" style="width: 150px; height: 20px">
               </td>
               <td style="width: 200px; height: 20px">
               </td>
           </tr>
           
           </table>
           
           <table style="width:95%;margin-bottom:30px;border:none;" class="table table-bordered table_desc">
           <tbody class="table_desc_body"> 
           <tr>
     			<td style="width:10%;text-align:center;font-weight:bold;">S.No</td>
                <td colspan="2" style="width:70%;text-align:center;font-weight:bold;">DESCRIPTION</td>
               
                <td style="width:20%;text-align:center;font-weight:bold;">AMOUNT</td>
		   </tr>';
         
        
             $split_desc=split("!",$description);
             foreach($split_desc as $split_desc1)
             {
                 $desc_explode=explode("^",$split_desc1);
             	 if($split_desc1!='')
				 {
         
            $html .= ' <tr class="row1">
                <td style="vertical-align:middle;text-align:center;">'.$desc_explode[0] .'</td>
                <td colspan="2">'.$desc_explode[1] .'</td>
               
                <td style="text-align:right;vertical-align:middle;"><span style="margin-right: 35px;">'.$desc_explode[2] .'</span></td>
              </tr>';
			
              
            	}
             }
    	   $html .= ' </tbody>';
   		 $split_desc=split("!",$description);
		 $amount=0;
		 foreach($split_desc as $split_desc1)
		 {
		 $desc_explode=explode("^",$split_desc1);
		 if($split_desc1!='')
		 {
			$amount=$amount+$desc_explode[2];
		 }
		 
		 }
		 $sub_total=$amount;
		 $service_tax=($amount*$tax)/100;
		 $service_tax=sprintf('%0.2f', $service_tax);
		 $grand_total=$sub_total+$service_tax-$advance;
		 $grand_total=round($grand_total);
		$html .= '<tfoot>
	        <tr>
			<td>&nbsp;</td>
            <td style="font-weight:bold;text-align:right;" colspan="2">Sub Total:</td>
            <td style="text-align:right;">
            <span style="margin-right: 35px;">'.$sub_total .'</span>
            </td>
			</tr>
        	<tr>
			<td>&nbsp;</td>
            <td style="font-weight:bold;text-align:right;" colspan="2">Service Tax</td>
            <td style="text-align:right;">
            <span style="margin-right: 35px;">'.$service_tax .'</span>
            </td>
			</tr>
       		<tr>
			<td>&nbsp;</td>
            <td style="font-weight:bold;text-align:right;" colspan="2">Advance Paid</td>
            <td style="text-align:right;">
            <span style="margin-right: 35px;">'.$advance .'</span>
            </td>
		</tr>
        <tr>
			
            <td></td>
            
          	<td colspan="2" style="font-weight:bold;text-align:right;">Grand Total</td>
            <td style="font-weight:bold;text-align:right;">
            <span style="margin-right: 35px;">'.$currency .' '.$grand_total.'
            </span>
             </td>
             <tr>
             <td colspan="4" style="font-weight:bold;border:none !important;">'.$currency .' <span class="rupee_word" style="text-transform:capitalize;">'.no_to_words($total).'</span>  Only </td>
             </tr>
		</tr>
	</tfoot>
   </table>
   <div class="pull-right padding-right">
   <img src="http://'.$host.'/images/bar_seal1.png" style="width:120px;" alt="" />
   <p>Authorised Signature</p>
   </div>
   <div class="clearfix"></div>
   <hr/>
   		<div class="row">
      			  <div class="col-xs-8">
                   <p style="display:inline-block;font-weight:bold;height:18px;width:380px;">Direct All Inquiries To:</p>
                   <p>info@buddiestours.com <br/>accounts@buddiestours.in</p><br><br><br>
				   
                   <!--<strong>Make all cheques payable to:</strong><br/>
					BUDDIES TOURS AND TRAVELS PRIVATE LIMITED-->
                    </div>
                    
                   <div class="col-xs-4">
                       <p><strong>Corporate Address:</strong> </p>
                       <textarea name="offline_payment"  cols="20" style="width:275px;overflow:hidden;resize:none;min-height:170px;position:relative;left: 0px;border:none;" readonly>'.$offline_payment .'
                       </textarea>
  	               </div>
           </div>
        
</div>
<p style="position:relative;bottom:0;text-align:center;width:100%;">Thanks for your Business!</p>';

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

            // output the HTML content
            $pdf->writeHTML($html, true, false, true, false, '');







// output the HTML content
//$pdf->writeHTML($html, true, 0, true, 0);

// reset pointer to the last page
$pdf->lastPage();

// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output('vehicleallotment.pdf');

//============================================================+
// END OF FILE
//============================================================+
