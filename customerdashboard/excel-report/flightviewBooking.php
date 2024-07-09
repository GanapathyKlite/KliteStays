<?php 
	include('../../include/database/config.php');

	date_default_timezone_set('Asia/Kolkata');
	require_once 'PHPExcel/Classes/PHPExcel.php';
	
	$filename = 'flightReport';
	$objPHPExcel = new PHPExcel();
	/*********************Add column headings START**********************/
	if($_GET['manage']==1||$_GET['manage']==2)
	{
	$objPHPExcel->setActiveSheetIndex(0) 
				->setCellValue('A1', 'SL.No')
				->setCellValue('B1', 'Booking ID')
				->setCellValue('C1', 'Booking Date')
				->setCellValue('D1', 'Journey Date')
				->setCellValue('E1', 'PNR')
				->setCellValue('F1', 'Ticket No.')
				->setCellValue('G1', 'Passenger Name')
				->setCellValue('H1', 'Source')
				->setCellValue('I1', 'Destination')
				->setCellValue('J1', 'Total Fare')
				->setCellValue('K1', 'Comm');
			}else
			{
				$objPHPExcel->setActiveSheetIndex(0) 
				->setCellValue('A1', 'SL.No')
				->setCellValue('B1', 'Booking ID')
				->setCellValue('C1', 'Booking Date')
				->setCellValue('D1', 'Journey Date')
				->setCellValue('E1', 'Source')
				->setCellValue('F1', 'Destination')
				->setCellValue('G1', 'Ticket Id')
				->setCellValue('H1', 'Total Amount')
				->setCellValue('I1', 'Refund Amount');
			}
	

	$i=1;
	$k=1;
		
	$id_agent = (isset($_GET['id']) ? $_GET['id'] : '');
	
	$where = '';
	if(isset($_GET['from']) && !empty($_GET['from']) && isset($_GET['to']) && !empty($_GET['to']))
		$where = " AND bd.journey_date >= '".date('Y-m-d', strtotime($_GET['from']))."' AND bd.journey_date <= '".date('Y-m-d', strtotime($_GET['to']))."'";
		else if(isset($_GET['from']) && !empty($_GET['from']))
			$where = " AND bd.journey_date >= '".date('Y-m-d', strtotime($_GET['from']))."'";
		else if(isset($_GET['to']) && !empty($_GET['to']))
			$where = " AND bd.journey_date <= '".date('Y-m-d', strtotime($_GET['to']))."'";
	if($_GET['manage']==1)
			{
				$query="select bd.*,td.BTBookingId,td.date_add as td_date_add,td.TicketNumber,td.PublishedFareDisplay,td.TotalCommissionAgent from booking_details bd join ticket_details td on(bd.BookingId = td.BookingId) where bd.BookingId!=0 and bd.id_agent='".$id_agent."' and journey_date < '".date('Y-m-d')."' order by td.date_add desc".$where;
			}elseif($_GET['manage']==2)
			{
				$query="select bd.*,td.id as id_ticket,td.BTBookingId,td.date_add as td_date_add,td.TicketNumber,td.PublishedFareDisplay,td.TotalCommissionAgent,cd.BookingId as cancelledBookingId,cd.ChangeRequestStatus from booking_details bd join ticket_details td on(bd.BookingId = td.BookingId) left join cancellation_details cd on(bd.BookingId = cd.BookingId) where bd.BookingId!=0 and bd.journey_date >= '".date('Y-m-d')."'".$where." and bd.id_agent='".$id_agent."' order by td.date_add desc";

			}elseif($_GET['manage']==3)
			{
				$query="select bd.*,cd.*,td.BTBookingId,bd.date_add as bookingDate,td.PublishedFareDisplay from cancellation_details cd join booking_details bd on(cd.BookingId = bd.BookingId) left join ticket_details td on(bd.BookingId = td.BookingId) where bd.BookingId!=0 and bd.id_agent='".$id_agent."'".$where;
			}			

	$booking_details=$database_flight->query($query);

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
		$segmentOriginCityNameArr = explode(',',$row['segmentOriginCityName']);
		$segmentDestinationCityNameArr = explode(',',$row['segmentDestinationCityName']);
		$passengerFirstName = explode(',',$row['passengerFirstName']);
		$passengerLastName = explode(',',$row['passengerLastName']);
		$ticketNumberArr = explode(',',$row['TicketNumber']);
		$journeyFrom = $segmentOriginCityNameArr[0];
		$journeyTo = $segmentDestinationCityNameArr[count($segmentDestinationCityNameArr)-1];

		if($_GET['manage']==1||$_GET['manage']==2)
		{
		

		$objPHPExcel->setActiveSheetIndex(0) 
		->setCellValue('A'.$i, $k)
		->setCellValue('B'.$i, $row['BTBookingId'])
		->setCellValue('C'.$i, date('d/m/Y', strtotime($row['date_add'])))
		->setCellValue('D'.$i, ($row['journey_date'] != '0000-00-00' ? date('d/m/Y', strtotime($row['journey_date'])) : ''))
		->setCellValue('E'.$i, $row['PNR'])
		->setCellValue('F'.$i, $ticketNumberArr[0])
		->setCellValue('G'.$i, ucwords($passengerFirstName[0])." ".ucwords($passengerLastName[0]))
		->setCellValue('H'.$i, $segmentOriginCityNameArr[0])
		->setCellValue('I'.$i, $segmentDestinationCityNameArr[count($segmentDestinationCityNameArr)-1])
		->setCellValue('J'.$i, $row['PublishedFareDisplay'])
		->setCellValue('K'.$i, $row['TotalCommissionAgent']);
		}
		else
		{
			$objPHPExcel->setActiveSheetIndex(0) 
		->setCellValue('A'.$i, $k)
		->setCellValue('B'.$i, $row['BTBookingId'])
		->setCellValue('C'.$i, date('d/m/Y', strtotime($row['bookingDate'])))
		->setCellValue('D'.$i, ($row['journey_date'] != '0000-00-00' ? date('d/m/Y', strtotime($row['journey_date'])) : ''))
		->setCellValue('E'.$i, $journeyFrom)
		->setCellValue('F'.$i, $journeyTo)
		->setCellValue('G'.$i, $row['TicketId'])
		->setCellValue('H'.$i, $row['PublishedFareDisplay'])
		->setCellValue('I',$i, (!empty($row['refund_amount_approved']) ? $row['refund_amount_approved'] : ''));
		}
		$k++;
	}

	if($_GET['manage']==1||$_GET['manage']==2)
		{			
	$objPHPExcel->getActiveSheet()->getStyle('G1:G'.$objPHPExcel->getActiveSheet()->getHighestRow())->getAlignment()->setWrapText(true); 
	$objPHPExcel->getActiveSheet()->getStyle('F1:F'.$objPHPExcel->getActiveSheet()->getHighestRow())->getAlignment()->setWrapText(true); 
	$objPHPExcel->getActiveSheet()->getStyle('J1:J'.$objPHPExcel->getActiveSheet()->getHighestRow())->getAlignment()->setWrapText(true); 
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
	/*********************Add color to heading END***********************/
	
	}
	else
	{
		$objPHPExcel->getActiveSheet()->getStyle('E1:E'.$objPHPExcel->getActiveSheet()->getHighestRow())->getAlignment()->setWrapText(true); 
	$objPHPExcel->getActiveSheet()->getStyle('F1:F'.$objPHPExcel->getActiveSheet()->getHighestRow())->getAlignment()->setWrapText(true); 


	$objPHPExcel->setActiveSheetIndex(0);

	/*********************Add data entries END**********************/
	
	/*********************Autoresize column width depending upon contents START**********************/
	foreach(range('A','I') as $columnID) {
		$objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
	}
	/*********************Autoresize column width depending upon contents END***********************/
	
	$objPHPExcel->getActiveSheet()->getStyle('A1:I1')->getFont()->setBold(true); //Make heading font bold
	
	/*********************Add color to heading START**********************/
	$objPHPExcel->getActiveSheet()
				->getStyle('A1:I1')
				->getFill()
				->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
				->getStartColor()
				->setARGB('99ff99');

	}
$type='';
	if(isset($_GET['type'])&&($_GET['type']!=''))
	{
		$type=$_GET['type'];
	}else
	{
		$type="Flight BOOKING REPORT";
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
				
				
				
				
			
		
				
				
			
			
									 
								