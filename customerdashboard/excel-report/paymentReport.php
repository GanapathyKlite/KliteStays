<?php 

	include('../../include/database/config.php');



	date_default_timezone_set('Asia/Kolkata');

	require_once 'PHPExcel/Classes/PHPExcel.php';

	

	$filename = 'paymentReport';

	$objPHPExcel = new PHPExcel();

	/*********************Add column headings START**********************/

	$objPHPExcel->setActiveSheetIndex(0) 

				->setCellValue('A1', 'Sl. No.')

				->setCellValue('B1', 'BT Transaction ID')

				->setCellValue('C1', 'Payment Status')

				->setCellValue('D1', 'Recharge Type')

				->setCellValue('E1', 'Recharge Amount')

				->setCellValue('F1', 'Previous Balance')

				->setCellValue('G1', 'Current Balance')
				
				->setCellValue('H1', 'Date');



	$i=1;

	$k=1;

		

	$id_agent = (isset($_GET['id']) ? $_GET['id'] : '');

	

	$where = '';

	$_GET['fromdate'] = (isset($_GET['from']) && !empty($_GET['from']) ? date('Y-m-d', strtotime($_GET['from'])) : '');
	$_GET['todate'] = (isset($_GET['to']) && !empty($_GET['to']) ? date('Y-m-d', strtotime($_GET['to'])) : '');
	if(isset($_GET['fromdate']) && !empty($_GET['fromdate']) && isset($_GET['todate']) && !empty($_GET['todate']))
		$where = " AND pt.date_add >= '".$_GET['fromdate']." 00:00:00' AND pt.date_add <= '".$_GET['todate']." 23:59:59'";
	else if(isset($_GET['fromdate']) && !empty($_GET['fromdate']))
		$where = " AND pt.date_add >= '".$_GET['fromdate']." 00:00:00'";
	else if(isset($_GET['todate']) && !empty($_GET['todate']))
		$where = " AND pt.date_add <= '".$_GET['todate']."' 23:59:59'";



	$modeArray = array('CC' => 'Credit Card', 'DC' => 'Debit Card', 'NB' => 'NetBanking', 'CASH' => 'Cash Card', 'EMI' => 'EMI', 'IVR' => 'IVR', 'COD' => 'Cash On Delivery');
	$agent_details=$database->query("select pt.*,a.reference from ps_payu_transactions pt left join ps_agents a on(pt.id_agent = a.id_agent) WHERE (pt.mihpayid != '' or is_manual=1) and pt.id_agent='".$id_agent."'".$where." ORDER BY pt.date_add DESC")->fetchAll();



	foreach($agent_details as $sliderslist)

	{

		$i++;

		$objPHPExcel->setActiveSheetIndex(0) 

		->setCellValue('A'.$i,$k)

		->setCellValue('B'.$i,(isset($sliderslist['bt_txnid']) ? $sliderslist['bt_txnid'] : '-'))

		->setCellValue('C'.$i, (isset($sliderslist['status']) ? ucfirst($sliderslist['status']) : '-'))

		->setCellValue('D'.$i,(isset($modeArray[$sliderslist['mode']]) ? $modeArray[$sliderslist['mode']] : ((int)$sliderslist['is_manual'] == 1 ? 'Manual Credit' : '')))

		->setCellValue('E'.$i, (!empty($sliderslist['amount']) && $sliderslist['amount'] != '0.00' ? number_format($sliderslist['amount'],2,'.',',') : '-'))

		->setCellValue('F'.$i, (!empty($sliderslist['previous_balance']) && $sliderslist['previous_balance'] != '0.00' ? number_format($sliderslist['previous_balance'],2,'.',',') : '-'))

		->setCellValue('G'.$i,(!empty($sliderslist['current_balance']) && $sliderslist['current_balance'] != '0.00' ? number_format($sliderslist['current_balance'],2,'.',',') : '-'))

		->setCellValue('H'.$i, date('d/m/Y H:i:s', strtotime($sliderslist['date_add'])));



		$k++;

	}

				

	$objPHPExcel->getActiveSheet()->getStyle('G1:G'.$objPHPExcel->getActiveSheet()->getHighestRow())->getAlignment()->setWrapText(true); 

	$objPHPExcel->getActiveSheet()->getStyle('F1:F'.$objPHPExcel->getActiveSheet()->getHighestRow())->getAlignment()->setWrapText(true); 

	$objPHPExcel->getActiveSheet()->getStyle('J1:J'.$objPHPExcel->getActiveSheet()->getHighestRow())->getAlignment()->setWrapText(true); 

	$objPHPExcel->getActiveSheet()->getStyle('B1:B'.$objPHPExcel->getActiveSheet()->getHighestRow())->getAlignment()->setWrapText(true); 

	

	$objPHPExcel->setActiveSheetIndex(0);



	/*********************Add data entries END**********************/

	

	/*********************Autoresize column width depending upon contents START**********************/

	foreach(range('A','H') as $columnID) {

		$objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);

	}

	/*********************Autoresize column width depending upon contents END***********************/

	

	$objPHPExcel->getActiveSheet()->getStyle('A1:H1')->getFont()->setBold(true); //Make heading font bold

	

	/*********************Add color to heading START**********************/

	$objPHPExcel->getActiveSheet()

				->getStyle('A1:H1')

				->getFill()

				->setFillType(PHPExcel_Style_Fill::FILL_SOLID)

				->getStartColor()

				->setARGB('99ff99');

	/*********************Add color to heading END***********************/

	

	$objPHPExcel->getActiveSheet()->setTitle('PAYMENT REPORT'); //give title to sheet

	$objPHPExcel->setActiveSheetIndex(0);

	header('Content-Type: application/vnd.ms-excel');

	header("Content-Disposition: attachment;Filename=$filename.xls");

	header('Cache-Control: max-age=0');

	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');

	$objWriter->save('php://output');

	exit;

?>

				

				

				

				

			

		

				

				

			

			

									 

								