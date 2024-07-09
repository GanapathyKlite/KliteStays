<?php 
	include('../../include/database/config.php');

	date_default_timezone_set('Asia/Kolkata');
	require_once 'PHPExcel/Classes/PHPExcel.php';
	
	$filename = 'guideReport';
	$objPHPExcel = new PHPExcel();

	$pend=isset($_GET['pending']) && !empty($_GET['pending']) ? ",".$_GET['pending'] : '';
	$cancel=isset($_GET['cancel']) && ($_GET['cancel']!='') ? $_GET['cancel'] : '';
	$manage=isset($_GET['manage']) && ($_GET['manage']==1) ? ' and guide_date >= \''.date('Y-m-d').' 00:00:00\' ' :' and guide_date < \''.date('Y-m-d').' 00:00:00\' ';
	/*********************Add column headings START**********************/
	if($cancel==3)
	{
	$objPHPExcel->setActiveSheetIndex(0) 
				->setCellValue('A1', 'SI.No')
				->setCellValue('B1', 'Booking ID')
				->setCellValue('C1', 'Booking Date')
				->setCellValue('D1', 'Journey Date')
				->setCellValue('E1', 'Customer')
				->setCellValue('F1', 'Contact')
				->setCellValue('G1', 'Destination')
				->setCellValue('H1', 'Language')
				->setCellValue('I1', 'Total Amount')
				->setCellValue('J1', 'Refund Amount');
	}
	else
	{
		$objPHPExcel->setActiveSheetIndex(0) 
				->setCellValue('A1', 'SI.No')
				->setCellValue('B1', 'Booking ID')
				->setCellValue('C1', 'Booking Date')
				->setCellValue('D1', 'Journey Date')
				->setCellValue('E1', 'Customer')
				->setCellValue('F1', 'Contact')
				->setCellValue('G1', 'Destination')
				->setCellValue('H1', 'Language')
				->setCellValue('I1', 'Total Amount')
				->setCellValue('J1', 'Commission');
	}

	$i=1;
	$k=1;
		
	$id_agent = (isset($_GET['id']) ? $_GET['id'] : '');
	
	$where = '';
	if(isset($_GET['from']) && !empty($_GET['from']) && isset($_GET['to']) && !empty($_GET['to']))
		$where = " AND date_add >= '".date('Y-m-d', strtotime($_GET['from']))." 00:00:00' AND date_add <= '".date('Y-m-d', strtotime($_GET['to']))." 23:59:59'";
	else if(isset($_GET['from']) && !empty($_GET['from']))
		$where = " AND date_add >= '".date('Y-m-d', strtotime($_GET['from']))." 00:00:00'";
	else if(isset($_GET['to']) && !empty($_GET['to']))
		$where = " AND date_add <= '".date('Y-m-d', strtotime($_GET['to']))." 23:59:59'";



	$booking_details=$database->query("select * from ps_guide_booking where id_agent='".$id_agent."' and is_cancel in(".$cancel.$pend.") ".$manage.$where." order by guide_date DESC")->fetchAll();

	foreach($booking_details as $row)
	{
		$i++;

		if($cancel==3)
		{
			$objPHPExcel->setActiveSheetIndex(0) 
		->setCellValue('A'.$i,$k)
		->setCellValue('B'.$i, $row['booking_reference'])
		->setCellValue('C'.$i, date('d/m/Y', strtotime($row['date_add'])))
		->setCellValue('D'.$i, date('d/m/Y', strtotime($row['guide_date'])))
		->setCellValue('E'.$i, ucwords($row['guide_user_name']))
		->setCellValue('F'.$i, $row['guide_contact'])
		->setCellValue('G'.$i, $row['guide_destination'])
		->setCellValue('H'.$i, $row['guide_language'])
		->setCellValue('I'.$i, $row['guide_grand_total_display'])
		->setCellValue('J'.$i, $row['refund_amount_approved']);

		}else
		{
			$objPHPExcel->setActiveSheetIndex(0) 
		->setCellValue('A'.$i,$k)
		->setCellValue('B'.$i, $row['booking_reference'])
		->setCellValue('C'.$i, date('d/m/Y', strtotime($row['date_add'])))
		->setCellValue('D'.$i, date('d/m/Y', strtotime($row['guide_date'])))
		->setCellValue('E'.$i, ucwords($row['guide_user_name']))
		->setCellValue('F'.$i, $row['guide_contact'])
		->setCellValue('G'.$i, $row['guide_destination'])
		->setCellValue('H'.$i, $row['guide_language'])
		->setCellValue('I'.$i, $row['guide_grand_total_display'])
		->setCellValue('J'.$i, $row['guide_commission']);
		}

		$k++;
	}
				
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

if(isset($_GET['type'])&&($_GET['type']!=''))
{
	$type=$_GET['type'];
}else
{
	$type="GUIDE BOOKING REPORT";
}
	$booking_report=$type.(isset($_GET['from']) && !empty($_GET['from']) ? '  FROM '.$_GET['from'] : '').(isset($_GET['to']) && !empty($_GET['to']) ? '  To '.$_GET['to'] : '');

	$objPHPExcel->getActiveSheet()->setTitle($booking_report); //give title to sheet
	$objPHPExcel->setActiveSheetIndex(0);
	header('Content-Type: application/vnd.ms-excel');
	header("Content-Disposition: attachment;Filename=$filename.xls");
	header('Cache-Control: max-age=0');
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	$objWriter->save('php://output');
	exit;
?>
				
				
				
				
			
		
				
				
			
			
									 
								