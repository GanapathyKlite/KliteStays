<?php 
	include('../../include/database/config.php');

	date_default_timezone_set('Asia/Kolkata');
	require_once 'PHPExcel/Classes/PHPExcel.php';
$pend=isset($_GET['pending']) && !empty($_GET['pending']) ? ",".$_GET['pending'] : '';
$cancel=isset($_GET['cancel']) && ($_GET['cancel']!='') ? $_GET['cancel'] : '';
	$filename = 'carReport';
	$objPHPExcel = new PHPExcel();
	/*********************Add column headings START**********************/

		if($cancel==3)
		{
			$objPHPExcel->setActiveSheetIndex(0) 
				->setCellValue('A1', 'SI.No')
				->setCellValue('B1', 'Booking Date')
				->setCellValue('C1', 'Booking ID')
				->setCellValue('D1', 'Customer')
				->setCellValue('E1', 'Contact')
				->setCellValue('F1', 'Package Code')
				->setCellValue('G1', 'Arrival City')
				->setCellValue('H1', 'Amount Paid')
				->setCellValue('I1', 'Amount Refund')
				->setCellValue('I1', 'Refund %');


		}else{
			$objPHPExcel->setActiveSheetIndex(0) 
				->setCellValue('A1', 'SI.No')
				->setCellValue('B1', 'Booking Date')
				->setCellValue('C1', 'Booking ID')
				->setCellValue('D1', 'Customer')
				->setCellValue('E1', 'Contact')
				->setCellValue('F1', 'Package Code')
				->setCellValue('G1', 'Arrival City')
				->setCellValue('H1', 'Departure City');
		}
	

	$i=1;
	$k=1;
		
	$id_agent = (isset($_GET['id']) ? $_GET['id'] : '');
	
	$where = '';
	if(isset($_GET['from']) && !empty($_GET['from']) && isset($_GET['to']) && !empty($_GET['to']))
		$where = " AND booking_datetime >= '".date('Y-m-d', strtotime($_GET['from']))." 00:00:00' AND booking_datetime <= '".date('Y-m-d', strtotime($_GET['to']))." 23:59:59'";
	else if(isset($_GET['from']) && !empty($_GET['from']))
		$where = " AND booking_datetime >= '".date('Y-m-d', strtotime($_GET['from']))." 00:00:00'";
	else if(isset($_GET['to']) && !empty($_GET['to']))
		$where = " AND booking_datetime <= '".date('Y-m-d', strtotime($_GET['to']))." 23:59:59'";




		$booking_details=$database->query("select * from ps_car_booking where agent_id='".$id_agent."' and is_cancel in(".$cancel.$pend.")".$where." order by booking_date DESC");
		if($booking_details)
		{
			$booking_details=$booking_details->fetchAll();
		}else
		{
			$booking_details=array();
		}

	foreach($booking_details as $row)
	{
		$i++;

		
		if($cancel==3)
		{
			$objPHPExcel->setActiveSheetIndex(0) 
			->setCellValue('A'.$i,$k)
			->setCellValue('B'.$i, date('Y-m-d', strtotime($row['booking_date'])))
			->setCellValue('C'.$i, $row['booking_ref'])
			->setCellValue('D'.$i, $row['name'])
			->setCellValue('E'.$i, $row['mobileno'])
			->setCellValue('F'.$i, $row['package_code'])
			->setCellValue('G'.$i, $row['source_name'])
			->setCellValue('H'.$i, $row['selected_car_rate'])
			->setCellValue('I'.$i, $row['refund_amount'])
			->setCellValue('J'.$i, $row['refund_percentage']);
			
		}
		else
		{
			$objPHPExcel->setActiveSheetIndex(0) 
			->setCellValue('A'.$i,$k)
			->setCellValue('B'.$i, date('Y-m-d', strtotime($row['booking_date'])))
			->setCellValue('C'.$i, $row['booking_ref'])
			->setCellValue('D'.$i, $row['name'])
			->setCellValue('E'.$i, $row['mobileno'])
			->setCellValue('F'.$i, $row['package_code'])
			->setCellValue('G'.$i, $row['source_name'])
			->setCellValue('H'.$i, $row['destination_name']);
		}
		$k++;
	}
	if($cancel==3)
		{
			
	$objPHPExcel->getActiveSheet()->getStyle('G1:G'.$objPHPExcel->getActiveSheet()->getHighestRow())->getAlignment()->setWrapText(true); 
	$objPHPExcel->getActiveSheet()->getStyle('F1:F'.$objPHPExcel->getActiveSheet()->getHighestRow())->getAlignment()->setWrapText(true); 
	$objPHPExcel->getActiveSheet()->getStyle('B1:B'.$objPHPExcel->getActiveSheet()->getHighestRow())->getAlignment()->setWrapText(true); 
	
	$objPHPExcel->setActiveSheetIndex(0);

	/*********************Add data entries END**********************/
	
	/*********************Autoresize column width depending upon contents START**********************/
	foreach(range('A','J') as $columnID) {
		$objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
	}
	/*********************Autoresize column width depending upon contents END***********************/

	$objPHPExcel->getActiveSheet()->getStyle('A1:J1')->getFont()->setBold(true); //Make heading font bold
	
	/*********************Add color to heading START**********************/
	$objPHPExcel->getActiveSheet()
				->getStyle('A1:J1')
				->getFill()
				->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
				->getStartColor()
				->setARGB('99ff99');
	/*********************Add color to heading END***********************/
}
else{
	$objPHPExcel->getActiveSheet()->getStyle('G1:G'.$objPHPExcel->getActiveSheet()->getHighestRow())->getAlignment()->setWrapText(true); 
	$objPHPExcel->getActiveSheet()->getStyle('F1:F'.$objPHPExcel->getActiveSheet()->getHighestRow())->getAlignment()->setWrapText(true); 
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
}
$type='';
	if(isset($_GET['type'])&&($_GET['type']!=''))
	{
		$type=$_GET['type'];
	}else
	{
		$type="CAR BOOKING REPORT";
	}
	
	$booking_report=$type.(isset($_GET['from']) && !empty($_GET['from']) ? '  FROM '.$_GET['from'] : '').(isset($_GET['to']) && !empty($_GET['to']) ? '  To '.$_GET['to'] : '');
			
	$objPHPExcel->getActiveSheet()->setTitle($booking_report); 
	$objPHPExcel->setActiveSheetIndex(0);
	header('Content-Type: application/vnd.ms-excel');
	header("Content-Disposition: attachment;Filename=$filename.xls");
	header('Cache-Control: max-age=0');
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	$objWriter->save('php://output');
	exit;
?>
				
				
				
				
			
		
				
				
			
			
									 
								