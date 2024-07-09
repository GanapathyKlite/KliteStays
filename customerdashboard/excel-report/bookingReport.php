<?php 

	include('../../include/database/config.php');



	date_default_timezone_set('Asia/Kolkata');

	require_once 'PHPExcel/Classes/PHPExcel.php';

	
error_reporting(E_ALL);
	$filename = 'b0okingReport';

	$objPHPExcel = new PHPExcel();

	/*********************Add column headings START**********************/

	$objPHPExcel->setActiveSheetIndex(0) 

				->setCellValue('A1', 'Sl. No.')

				->setCellValue('B1', 'Booking ID')

				->setCellValue('C1', 'Booking Type')

				->setCellValue('D1', 'Total Amount')

				->setCellValue('E1', 'Previous Balance')

				->setCellValue('F1', 'Current Balance')

				->setCellValue('G1', 'Date');



	$i=1;

	$k=1;

		

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

	foreach($agent_details as $sliderslist)

	{

		$i++;
		
		$objPHPExcel->setActiveSheetIndex(0) 

		->setCellValue('A'.$i,$k)

		->setCellValue('B'.$i, (isset($sliderslist['order_info']) ? $sliderslist['order_info'] : '-'))

		->setCellValue('C'.$i, (isset($sliderslist['order_info']) ? (strpos($sliderslist['order_info'],'FL') > 0 ? 'Flight' : (strpos($sliderslist['order_info'],'GU') > 0 ? 'Guide' : (strpos($sliderslist['order_info'],'BS') > 0 ? 'Bus' : (strpos($sliderslist['order_info'],'CR') > 0 ? 'CAR': (strpos($sliderslist['order_info'],'CART') > 0 ? 'Tailor Car':''))))) : '-'))



		->setCellValue('D'.$i, (!empty($sliderslist['amount']) && $sliderslist['amount'] != '0.00' ? number_format($sliderslist['amount'],2,'.',',') : '-'))

		->setCellValue('E'.$i, (!empty($sliderslist['previous_balance']) && $sliderslist['previous_balance'] != '0.00' ? number_format($sliderslist['previous_balance'],2,'.',',') : '-'))

		->setCellValue('F'.$i, (!empty($sliderslist['current_balance']) && $sliderslist['current_balance'] != '0.00' ? number_format($sliderslist['current_balance'],2,'.',',') : '-'))

		->setCellValue('G'.$i, date('d/m/Y H:i:s', strtotime($sliderslist['date_add'])));
		$k++;

	}

				

	$objPHPExcel->getActiveSheet()->getStyle('G1:G'.$objPHPExcel->getActiveSheet()->getHighestRow())->getAlignment()->setWrapText(true); 

	$objPHPExcel->getActiveSheet()->getStyle('F1:F'.$objPHPExcel->getActiveSheet()->getHighestRow())->getAlignment()->setWrapText(true); 

	$objPHPExcel->getActiveSheet()->getStyle('J1:J'.$objPHPExcel->getActiveSheet()->getHighestRow())->getAlignment()->setWrapText(true); 

	$objPHPExcel->getActiveSheet()->getStyle('B1:B'.$objPHPExcel->getActiveSheet()->getHighestRow())->getAlignment()->setWrapText(true); 

	

	$objPHPExcel->setActiveSheetIndex(0);



	/*********************Add data entries END**********************/

	

	/*********************Autoresize column width depending upon contents START**********************/

	foreach(range('A','G') as $columnID) {

		$objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);

	}

	/*********************Autoresize column width depending upon contents END***********************/

	

	$objPHPExcel->getActiveSheet()->getStyle('A1:G1')->getFont()->setBold(true); //Make heading font bold

	

	/*********************Add color to heading START**********************/

	$objPHPExcel->getActiveSheet()

				->getStyle('A1:G1')

				->getFill()

				->setFillType(PHPExcel_Style_Fill::FILL_SOLID)

				->getStartColor()

				->setARGB('99ff99');

	/*********************Add color to heading END***********************/

	

	$objPHPExcel->getActiveSheet()->setTitle('Booking Report'); //give title to sheet

	$objPHPExcel->setActiveSheetIndex(0);

	header('Content-Type: application/vnd.ms-excel');

	header("Content-Disposition: attachment;Filename=$filename.xls");

	header('Cache-Control: max-age=0');

	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');

	$objWriter->save('php://output');

	exit;

?>

				

				

				

				

			

		

				

				

			

			

									 

								