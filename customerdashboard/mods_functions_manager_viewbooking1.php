<?php
if(!$_SERVER['HTTP_X_REQUESTED_WITH']== 'XMLHttpRequest')
{
   header("Location:login.php");
}
else
{
	session_start();
	error_reporting(E_ALL);
	include('../include/database/config.php');
	$date= new DateTime('now', new DateTimeZone('Asia/Kolkata'));
	if($_POST['getmods']=="fetchreport")
	{
		//echo "select * from loginandaccessreport";
		//$gen_report=$database->query("select * from transactions")->fetchAll();$i=1;
		$gen_report=$database_bus->query("select bd.*,td.ticket_no,td.seats from booking_details bd left join ticket_details td on(bd.id = td.b_id) where bd.id_agent='".$_SESSION['authid']."' and td.iscancel=0")->fetchAll();$i=1;
		//print_r($gen_modulelist);
		foreach($gen_report as $paylist)
		{
			$id=$paylist["id"];
			
			$s.="<tr>
				<td class='centertext'>".$i."</td>
				<td class='centertext'>".date('Y-m-d', strtotime($paylist['booking_datetime']))."</td>
				<td class='centertext'>".date('H:i:s', strtotime($paylist['booking_datetime']))."</td>
				<td class='centertext'>".$paylist['orderno']."</td>
				<td class='centertext'>".$paylist['ticket_no']."</td>
				<td class='centertext'>".$paylist['leavingfrom']."</td>
				<td class='centertext'>".$paylist['goingto']."</td>
				<td class='centertext'>".$paylist['seatFare']."</td>
				<td class='centertext'>".$paylist['service_charge']."</td>
				<td class='centertext'><img src='".$root_dir."images/cancel_icon.png' width='20' style='cursor:pointer;cursor:hand;' onclick='javascript:showCancelTicket(".$id.");'>
				<input type='Hidden' id='".$id."_seats' value='".$paylist['seats']."'>
				</td>
			</tr>";
			$i++;
		}
		echo $s;
	}
	else if($_POST['getmods']=="searchreport")
	{
		/*$fromdt=$_POST['fromdate'];
		$split_fromdt=explode("-",$fromdt);
		$fromdate=$split_fromdt[2]."-".$split_fromdt[1]."-".$split_fromdt[0];
		
		$todt=$_POST['todate'];
		$split_todt=explode("-",$todt);
		$todate=$split_todt[2]."-".$split_todt[1]."-".$split_todt[0];*/
		$where = '';
		if(isset($_POST['fromdate']) && !empty($_POST['fromdate']) && isset($_POST['todate']) && !empty($_POST['todate']))
			$where = " AND booking_datetime >= '".date('Y-m-d', strtotime($_POST['fromdate']))." 00:00:00' AND booking_datetime <= '".date('Y-m-d', strtotime($_POST['todate']))." 23:59:59'";
		else if(isset($_POST['fromdate']) && !empty($_POST['fromdate']))
			$where = " AND booking_datetime >= '".date('Y-m-d', strtotime($_POST['fromdate']))." 00:00:00'";
		else if(isset($_POST['todate']) && !empty($_POST['todate']))
			$where = " AND booking_datetime <= '".date('Y-m-d', strtotime($_POST['todate']))." 23:59:59'";
		if($_POST['agentid']!="")
		{
			//$gen_report_search=$database->query("select * from transactions where transdate between '".$fromdate."' and '".$todate."' and merchTxnRef='".$_POST['agentid']."'")->fetchAll();
		}
		else
		{
			//$gen_report_search=$database->query("select * from transactions where transdate between '".$fromdate."' and '".$todate."' and merchTxnRef='".$_SESSION['refid']."'")->fetchAll();
			//echo "select * from ps_payu_transactions where 1".$where." and id_agent='".$_SESSION['authid']."' order by date_add desc"; die();
			//echo "select * from ps_payu_transactions where 1".$where." and id_agent='".$_SESSION['authid']."' order by date_add desc"; die();
			$gen_report_search=$database_bus->query("select bd.*,td.ticket_no from booking_details bd left join ticket_details td on(bd.id = td.b_id) where bd.id_agent='".$_SESSION['authid']."' and td.iscancel=0".$where)->fetchAll();
			
			
		}
		//echo "select * from transactions where transdate between '".$fromdate."' and '".$todate."' and merchTxnRef='".$_SESSION['refid']."'";	
			$i=1;
		
		foreach($gen_report_search as $searchresult)
		{
			/*$c=0;
			$get_agentname=$database->query("select agentname from agents where refid='".$searchresult['merchTxnRef']."'")->fetchAll();
			$amountcalc=$searchresult['amount']*100-($searchresult['amount']*1.8);
			$amount=$amountcalc/100;
			$c=intval($c)+intval($amount);
			$servietax=($c*1.8);*/
			
			
			
		/*$frmdate=$searchresult['transdate'];
			$split_fromdt=explode("-",$frmdate);
		$fromdate=$split_fromdt[2]."-".$split_fromdt[1]."-".$split_fromdt[0];*/
			
			/*$s.="<tr><td>".$i."</td><td>".$searchresult['merchTxnRef']."</td><td>".$get_agentname[0][0]."</td><td class='centertext'>".$searchresult['transactionNo']."</td><td class='centertext'>".$searchresult['receiptNo']."</td><td class='centertext'>".$c.".00"."</td><td>".$servietax."</td><td class='centertext'>".$c.".00"."</td><td class='centertext'>".$searchresult['transdate']."</td><td>".$searchresult['transtime']."</td></tr>";*/
			/*$s.="<tr><td>".$i."</td><td class='centertext'>".$fromdate."</td><td>".$searchresult['transtime']."</td><td class='centertext'>".$searchresult['transactionNo']."</td><td class='centertext'>".$searchresult['receiptNo']."</td><td class='centertext'>".$c.".00"."</td><td>".$servietax."</td><td class='centertext'>".$c.".00"."</td></tr>";*/
			$s.="<tr>
				<td class='centertext'>".$i."</td>
				<td class='centertext'>".date('Y-m-d', strtotime($searchresult['booking_datetime']))."</td>
				<td class='centertext'>".date('H:i:s', strtotime($searchresult['booking_datetime']))."</td>
				<td class='centertext'>".$searchresult['orderno']."</td>
				<td class='centertext'>".$searchresult['ticket_no']."</td>
				<td class='centertext'>".$searchresult['leavingfrom']."</td>
				<td class='centertext'>".$searchresult['goingto']."</td>
				<td class='centertext'>".$searchresult['seatFare']."</td>
				<td class='centertext'>".$searchresult['service_charge']."</td>
				<td class='centertext'><img src='".$root_dir."images/cancel_icon.png' width='20' style='cursor:pointer;cursor:hand;' onclick='javascript:showCancelTicket();'></td>
			</tr>";
			$i++;
		}
		echo $s;
		
	}
	
}

?>