<?php 
	include('../../include/database/config.php');

	date_default_timezone_set('Asia/Kolkata');
	require_once 'PHPExcel/Classes/PHPExcel.php';
	
	$filename = 'bookingReport';
	$objPHPExcel = new PHPExcel();
	/*********************Add column headings START**********************/
	$objPHPExcel->setActiveSheetIndex(0) 
				->setCellValue('A1', 'SI.No')
				->setCellValue('B1', 'Booking ID')
				->setCellValue('C1', 'Booking Date')
				->setCellValue('D1', 'Check In')
				->setCellValue('E1', 'Check Out')
				->setCellValue('F1', 'Customer')
				->setCellValue('G1', 'Hotel')
				->setCellValue('H1', 'Destination')
				->setCellValue('I1', 'No.of Rooms')
				->setCellValue('J1', 'Total Amount')
				->setCellValue('K1', 'Commission');

	$i=1;
	$k=1;
		
	$id_agent = (isset($_GET['id']) ? $_GET['id'] : '');
	
	$where = '';
	if(isset($_GET['fromdate']) && !empty($_GET['fromdate']) && isset($_GET['todate']) && !empty($_GET['todate']))
			$where = " AND hb.check_in >= '".date('Y-m-d', strtotime($_GET['fromdate']))." 00:00:00' AND hb.check_in <= '".date('Y-m-d', strtotime($_GET['todate']))." 23:59:59'";
		else if(isset($_GET['fromdate']) && !empty($_GET['fromdate']))
			$where = " AND hb.check_in >= '".date('Y-m-d', strtotime($_GET['fromdate']))." 00:00:00'";
		else if(isset($_GET['todate']) && !empty($_GET['todate']))
			$where = " AND hb.check_in <= '".date('Y-m-d', strtotime($_GET['todate']))." 23:59:59'";

/*$pend=isset($_GET['pending']) && !empty($_GET['pending']) ? ",".$_GET['pending'] : '';
$cancel=isset($_GET['cancel']) && ($_GET['cancel']!='') ? $_GET['cancel'] : '';
$manage=isset($_GET['manage']) && ($_GET['manage']==1) ? ' and hb.check_in >= \''.date('Y-m-d').' 00:00:00\' and hb.is_cancel in('.$cancel.')' :' and hb.check_in < \''.date('Y-m-d').' 00:00:00\'  and hb.is_cancel in('.$cancel.')';*/
$manage=false;
if(isset($_GET['manage']) && ($_GET['manage']==1))
{$manage=true;
	$query="select hb.*,p.txtPropertyName,concat(c.name,',',s.name) as destination from ps_hotel_booking hb left join ps_property p on(hb.id_property = p.id_property) left join ps_city c on(hb.city_id = c.id_city) left join ps_state s on(c.id_state = s.id_state) where hb.id_agent='".$id_agent."'".$where.($manage ? ' and hb.check_in >= \''.date('Y-m-d').' 00:00:00\' and hb.is_cancel!=3' : ' and hb.check_in < \''.date('Y-m-d').' 00:00:00\' and hb.is_cancel=0')." order by hb.id desc";
}elseif(isset($_GET['manage']) && ($_GET['manage']==2))
{
	$query="select hb.*,p.txtPropertyName,concat(c.name,',',s.name) as destination from ps_hotel_booking hb left join ps_property p on(hb.id_property = p.id_property) left join ps_city c on(hb.city_id = c.id_city) left join ps_state s on(c.id_state = s.id_state) where hb.id_agent='".$id_agent."'".$where." and hb.is_cancel=3 order by hb.id desc";
}elseif(isset($_GET['manage']) && ($_GET['manage']==0))
{

	$manage=false;
	$query="select hb.*,p.txtPropertyName,concat(c.name,',',s.name) as destination from ps_hotel_booking hb left join ps_property p on(hb.id_property = p.id_property) left join ps_city c on(hb.city_id = c.id_city) left join ps_state s on(c.id_state = s.id_state) where hb.id_agent='".$id_agent."'".$where.($manage ? ' and hb.check_in >= \''.date('Y-m-d').' 00:00:00\' and hb.is_cancel!=3' : ' and hb.check_in < \''.date('Y-m-d').' 00:00:00\' and hb.is_cancel=0')." order by hb.id desc";
}

	$booking_details=$database->query($query)->fetchAll();

	foreach($booking_details as $row)
	{
		$i++;

		$objPHPExcel->setActiveSheetIndex(0) 
		->setCellValue('A'.$i,$k)
		->setCellValue('B'.$i, $row['booking_reference'])
		->setCellValue('C'.$i, date('d/m/Y', strtotime($row['date_add'])))
		->setCellValue('D'.$i, date('d/m/Y', strtotime($row['check_in'])))
		->setCellValue('E'.$i, date('d/m/Y', strtotime($row['check_out'])))
		->setCellValue('F'.$i, ucfirst($row['customer_firstname']).' '.ucfirst($row['customer_lastname']))
		->setCellValue('G'.$i, ucwords($row['txtPropertyName']))
		->setCellValue('H'.$i, $row['destination'])
		->setCellValue('I'.$i, $row['room_count'])
		->setCellValue('J'.$i, $row['grand_total_price'])
		->setCellValue('K'.$i,'');

		$k++;
	}
				
	$objPHPExcel->getActiveSheet()->getStyle('G1:G'.$objPHPExcel->getActiveSheet()->getHighestRow())->getAlignment()->setWrapText(true); 
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
	/*********************Add color to heading END***********************/

if(isset($_GET['type'])&&($_GET['type']!=''))
{
	$type=$_GET['type'];
}else
{
	$type="HOTEL BOOKING REPORT";
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
				
				
				
				
			
		
				
				
			
			
									 
								