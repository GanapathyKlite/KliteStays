<?php 
	include('../../include/database/config.php');

	date_default_timezone_set('Asia/Kolkata');
	require_once 'PHPExcel/Classes/PHPExcel.php';
	
	$filename = 'paymentReport';
	$objPHPExcel = new PHPExcel();
	/*********************Add column headings START**********************/
	$objPHPExcel->setActiveSheetIndex(0) 
				->setCellValue('A1', 'S.No')
				->setCellValue('B1', 'Date')
				->setCellValue('C1', 'Time')
				->setCellValue('D1', 'Transaction No.')
				->setCellValue('E1', 'Recharge Amount')
				->setCellValue('F1', 'Status')
				->setCellValue('G1', 'Credit amount');

	$i=1;
	$k=1;
		
	$id_agent = (isset($_GET['id']) ? $_GET['id'] : '');
	
	$where = '';
	if(isset($_GET['from']) && !empty($_GET['from']))
		$where .= " and date_add >= '".date('Y-m-d', strtotime($_GET['from']))." 00:00:00'";
	if(isset($_GET['to']) && !empty($_GET['to']))
		$where .= " and date_add <= '".date('Y-m-d', strtotime($_GET['to']))." 23:59:59'";

	$agent_details=$database->query("select * from ps_payu_transactions where id_agent='".$id_agent."'".$where." order by date_add desc")->fetchAll();

	foreach($agent_details as $row)
	{
		$i++;
		$objPHPExcel->setActiveSheetIndex(0) 
		->setCellValue('A'.$i,$k)
		->setCellValue('B'.$i, date('Y-m-d', strtotime($row['date_add'])))
		->setCellValue('C'.$i, date('H:i:s', strtotime($row['date_add'])))
		->setCellValue('D'.$i, $row['txnid'])
		->setCellValue('E'.$i, $row['amount'])
		->setCellValue('F'.$i, $row['status'])
		->setCellValue('G'.$i, $row['amount']);

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
	
	$objPHPExcel->getActiveSheet()->setTitle('TRANSACTION REPORT'.(isset($_GET['from']) && !empty($_GET['from']) ? '  FROM '.$_GET['from'] : '').(isset($_GET['to']) && !empty($_GET['to']) ? '  To '.$_GET['to'] : '')); //give title to sheet
	$objPHPExcel->setActiveSheetIndex(0);
	header('Content-Type: application/vnd.ms-excel');
	header("Content-Disposition: attachment;Filename=$filename.xls");
	header('Cache-Control: max-age=0');
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	$objWriter->save('php://output');
	exit;
?>
				
				
				
				
			
		
				
				
			
			
									 
								