<?php 
	include('../../include/database/config.php');

	date_default_timezone_set('Asia/Kolkata');
	require_once 'PHPExcel/Classes/PHPExcel.php';
	
	$filename = 'busReport';
	$objPHPExcel = new PHPExcel();
	/*********************Add column headings START**********************/
$manage=isset($_GET['manage']) && !empty($_GET['manage']) ? $_GET['manage'] : '';
$cancel=isset($_GET['cancel']) && ($_GET['cancel']!='') ? $_GET['cancel'] : '';
	if($manage==3)
	{
	
		$objPHPExcel->setActiveSheetIndex(0) 
				->setCellValue('A1', 'SL.No')
				->setCellValue('B1', 'Booking ID')
				->setCellValue('C1', 'Booking Date')
				->setCellValue('D1', 'Journey Date')				
				->setCellValue('E1', 'Ticket No.')
				->setCellValue('F1', 'Passenger Name')
				->setCellValue('G1', 'Source')
				->setCellValue('H1', 'Destination')
				->setCellValue('I1', 'Total Fare')
				->setCellValue('J1', 'Refunded Amount');
	}
	else{
		$objPHPExcel->setActiveSheetIndex(0) 
				->setCellValue('A1', 'SL.No')
				->setCellValue('B1', 'Booking ID')
				->setCellValue('C1', 'Booking Date')
				->setCellValue('D1', 'Journey Time')				
				->setCellValue('E1', 'Ticket No.')
				->setCellValue('F1', 'Passenger Name')
				->setCellValue('G1', 'Source')
				->setCellValue('H1', 'Destination')
				->setCellValue('I1', 'Total Fare')
				->setCellValue('J1', 'Commission')
				->setCellValue('K1', 'Service Charge');
	}	


	$i=1;
	$k=1;
		
	$id_agent = (isset($_GET['id']) ? $_GET['id'] : '');
	
	$where = '';
	if(isset($_GET['fromdate']) && !empty($_GET['fromdate']) && isset($_GET['todate']) && !empty($_GET['todate']))
			$where = " AND journeyDate >= '".date('Y-m-d', strtotime($_GET['fromdate']))." 00:00:00' AND journeyDate <= '".date('Y-m-d', strtotime($_GET['todate']))." 23:59:59'";
		else if(isset($_GET['fromdate']) && !empty($_GET['fromdate']))
			$where = " AND journeyDate >= '".date('Y-m-d', strtotime($_GET['fromdate']))." 00:00:00'";
		else if(isset($_GET['todate']) && !empty($_GET['todate']))
			$where = " AND journeyDate <= '".date('Y-m-d', strtotime($_GET['todate']))." 23:59:59'";
		if($manage==1)
		{
			$query="select bd.*,td.id as id_ticket,td.ticket_no,td.seats,td.totalCommission,td.serviceCharge,td.seatFareDisplay from booking_details bd left join ticket_details td on(bd.id = td.b_id) where bd.id_agent='".$id_agent."' and td.iscancel=0 and bd.journeyDate < '".date('Y-m-d')."'".$where." order by bd.journeyDate desc";
		}elseif($manage==2)
		{
			$query="select bd.*,td.id as id_ticket,td.ticket_no,td.seats,td.partialCancellation,td.totalCommission,td.serviceCharge,td.seatFareDisplay from booking_details bd left join ticket_details td on(bd.id = td.b_id) where bd.id_agent='".$id_agent."' and td.iscancel=0 and bd.journeyDate >= '".date('Y-m-d')."'".$where." order by bd.journeyDate desc";
		}else
		{
			$query="select bd.*,td.id as id_ticket,td.ticket_no,td.seats,td.iscancel,td.seatFareDisplay from booking_details bd left join ticket_details td on(bd.id = td.b_id) where bd.id_agent='".$id_agent."' and td.iscancel!=0 order by bd.journeyDate desc";
		}

		$booking_details=$database_bus->query($query);

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
			$passengerNameArr = explode(',',$row['name']);
			$seatFare = array_sum(explode(',',$row['seatFareDisplay']));
			$totalCommission = array_sum(explode(',',$row['totalCommission']));
	if($manage==3)
	{

		$objPHPExcel->setActiveSheetIndex(0) 
		->setCellValue('A'.$i,$k)
		->setCellValue('B'.$i, $row['orderno'])
		->setCellValue('C'.$i, date('d/m/Y', strtotime($row['booking_datetime'])))
		->setCellValue('D'.$i, date('d/m/Y', strtotime($row['journeyDate'])))
		->setCellValue('E'.$i, $row['ticket_no'])
		->setCellValue('F'.$i, $passengerNameArr[0])
		->setCellValue('G'.$i, $row['leavingfrom'])
		->setCellValue('H'.$i, $row['goingto'])
		->setCellValue('I'.$i, array_sum($seatFare))
		->setCellValue('J'.$i, '');
		
		
	}else
	{
		$objPHPExcel->setActiveSheetIndex(0) 
			->setCellValue('A'.$i,$k)
			->setCellValue('B'.$i, $row['orderno'])
			->setCellValue('C'.$i, date('d/m/Y', strtotime($row['booking_datetime'])))
			->setCellValue('D'.$i, date('d/m/Y', strtotime($row['journeyDate'])))
			->setCellValue('E'.$i, $row['ticket_no'])
			->setCellValue('F'.$i, $passengerNameArr[0])
			->setCellValue('G'.$i, $row['leavingfrom'])
			->setCellValue('H'.$i, $row['goingto'])
			->setCellValue('I'.$i, (!empty($seatFare) && $seatFare != '0.00' ? $seatFare+0 : ''))
			->setCellValue('J'.$i, (!empty($totalCommission) ? $totalCommission : ''))
			->setCellValue('K'.$i, (!empty($row['serviceCharge']) && $row['serviceCharge'] != '0.00' ? $row['serviceCharge']+0 : ''));
	}
		$k++;
	}
	if($manage==3)
	{
		$objPHPExcel->getActiveSheet()->getStyle('G1:G'.$objPHPExcel->getActiveSheet()->getHighestRow())->getAlignment()->setWrapText(true); 
	$objPHPExcel->getActiveSheet()->getStyle('F1:F'.$objPHPExcel->getActiveSheet()->getHighestRow())->getAlignment()->setWrapText(true); 
	$objPHPExcel->getActiveSheet()->getStyle('H1:H'.$objPHPExcel->getActiveSheet()->getHighestRow())->getAlignment()->setWrapText(true); 

	
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
	}else{
		$objPHPExcel->getActiveSheet()->getStyle('G1:G'.$objPHPExcel->getActiveSheet()->getHighestRow())->getAlignment()->setWrapText(true); 
	$objPHPExcel->getActiveSheet()->getStyle('F1:F'.$objPHPExcel->getActiveSheet()->getHighestRow())->getAlignment()->setWrapText(true); 
	$objPHPExcel->getActiveSheet()->getStyle('H1:H'.$objPHPExcel->getActiveSheet()->getHighestRow())->getAlignment()->setWrapText(true); 
	$objPHPExcel->getActiveSheet()->getStyle('B1:B'.$objPHPExcel->getActiveSheet()->getHighestRow())->getAlignment()->setWrapText(true); 
	
	$objPHPExcel->setActiveSheetIndex(0);

	/*********************Add data entries END**********************/
	
	/*********************Autoresize column width depending upon contents START**********************/
	foreach(range('A','K') as $columnID) {
		$objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
	}
	/*********************Autoresize column width depending upon contents END***********************/
	
	$objPHPExcel->getActiveSheet()->getStyle('A1:K1')->getFont()->setBold(true); //Make heading font bold
	
	/*********************Add color to heading START**********************/
	$objPHPExcel->getActiveSheet()
				->getStyle('A1:K1')
				->getFill()
				->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
				->getStartColor()
				->setARGB('99ff99');
	}			
	
	/*********************Add color to heading END***********************/
	if(isset($_GET['type'])&&($_GET['type']!=''))
	{
		$type=$_GET['type'];
	}else
	{
		$type="BUS BOOKING REPORT";
	}
	$objPHPExcel->getActiveSheet()->setTitle($type.(isset($_GET['from']) && !empty($_GET['from']) ? '  FROM '.$_GET['from'] : '').(isset($_GET['to']) && !empty($_GET['to']) ? '  To '.$_GET['to'] : '')); //give title to sheet
	$objPHPExcel->setActiveSheetIndex(0);
	header('Content-Type: application/vnd.ms-excel');
	header("Content-Disposition: attachment;Filename=$filename.xls");
	header('Cache-Control: max-age=0');
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	$objWriter->save('php://output');
	exit;
?>
				
				
				
				
			
		
				
				
			
			
									 
								