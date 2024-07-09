<?php
	include('../../../include/database/config.php');

	$html = '<table cellpadding="0" cellspacing="0" width="100%" style="text-align:center;">
				<tr style="background-color:#DD1B22; color:#fff;">
					<td style="width:50px; vertical-align:middle; padding-top:20px;">Sl. No.</td>
					<td style="text-align:center; width:100px; padding-top:20px;">Booking ID</td>
					<td style="text-align:center; width:100px;">Booking Type</td>
					<td style="text-align:center; width:100px;">Total Amount</td>
					<td style="text-align:center; width:120px;">Previous Balance</td>
					<td style="text-align:center; width:120px;">Current Balance</td>
					<td style="text-align:center; width:120px;">Date</td>
				</tr>
				<tr><td colspan="11" style="border-top:1px solid #0171B6; height:1px;"></td></tr>';
				$i=1;
				
				

				$id_agent = (isset($_GET['id']) ? $_GET['id'] : '');

	

		$where = '';
		$_GET['fromdate'] = (isset($_GET['fromdate']) && !empty($_GET['fromdate']) ? date('Y-m-d', strtotime($_GET['fromdate'])) : '');
		$_GET['todate'] = (isset($_GET['todate']) && !empty($_GET['todate']) ? date('Y-m-d', strtotime($_GET['todate'])) : '');
		if(isset($_GET['fromdate']) && !empty($_GET['fromdate']) && isset($_GET['todate']) && !empty($_GET['todate']))
			$where = " AND pt.date_add >= '".$_GET['fromdate']." 00:00:00' AND pt.date_add <= '".$_GET['todate']." 23:59:59'";
		else if(isset($_GET['fromdate']) && !empty($_GET['fromdate']))
			$where = " AND pt.date_add >= '".$_GET['fromdate']." 00:00:00'";
		else if(isset($_GET['todate']) && !empty($_GET['todate']))
			$where = " AND pt.date_add <= '".$_GET['todate']."' 23:59:59'";
	
	$modeArray = array('CC' => 'Credit Card', 'DC' => 'Debit Card', 'NB' => 'NetBanking', 'CASH' => 'Cash Card', 'EMI' => 'EMI', 'IVR' => 'IVR', 'COD' => 'Cash On Delivery');




	$agent_details=$database->query("select pt.*,a.reference from ps_payu_transactions pt left join ps_agents a on(pt.id_agent = a.id_agent) WHERE pt.mihpayid = '' and is_manual=0 and productinfo like '%Account Balance%' and pt.id_agent='".$id_agent."'".$where." ORDER BY pt.date_add DESC");


				foreach($agent_details as $row)
				{
					$html .= '
						<tr style="border:1px solid red;">
							<td style="width:50px; vertical-align:middle; padding-top:20px;">'.$i.'</td>
							<td style="text-align:center; width:100px; font-size:8px; padding-top:10px;">'.(isset($row['order_info']) ? $row['order_info'] : '-').'</td>
							<td style="text-align:center; width:100px; font-size:8px; padding-top:10px;">'.(isset($row['order_info']) ? (strpos($row['order_info'],'FL') > 0 ? 'Flight' : (strpos($row['order_info'],'GU') > 0 ? 'Guide' : (strpos($row['order_info'],'BS') > 0 ? 'Bus' : (strpos($row['order_info'],'CR') > 0 ? 'CAR': (strpos($row['order_info'],'CART') > 0 ? 'Tailor Car':''))))) : '-').'</td>
							<td style="text-align:center; font-size:8px; width:100px;">'.(!empty($row['amount']) && $row['amount'] != '0.00' ? number_format($row['amount'],2,'.',',') : '-').'</td>
							<td style="text-align:center;font-size:8px; width:120px;">'.(!empty($row['previous_balance']) && $row['previous_balance'] != '0.00' ? number_format($row['previous_balance'],2,'.',',') : '-').'</td>
							<td style="text-align:center; font-size:8px;width:120px;">'.(!empty($row['current_balance']) && $row['current_balance'] != '0.00' ? number_format($row['current_balance'],2,'.',',') : '-').'</td>
							<td style="text-align:center; font-size:8px;width:120px;">'.date('d/m/Y H:i:s', strtotime($row['date_add'])).'</td>
							
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
$pdf->SetKeywords('TCPDF, PDF, example, test, booking');
// set default header data

$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, '', 'TRANSACTION REPORT'.(isset($_GET['fromdate']) && !empty($_GET['fromdate']) ? '  FROM '.$_GET['fromdate'] : '').(isset($_GET['todate']) && !empty($_GET['todate']) ? '  To '.$_GET['todate'] : ''));

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
//$pdf->Output(getcwd().'\paymentReport.pdf', 'F');


//============================================================+
// END OF FILE
//============================================================+
