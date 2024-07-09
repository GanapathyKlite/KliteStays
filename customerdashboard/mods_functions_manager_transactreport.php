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
		$modeArray = array('CC' => 'Credit Card', 'DC' => 'Debit Card', 'NB' => 'NetBanking', 'CASH' => 'Cash Card', 'EMI' => 'EMI', 'IVR' => 'IVR', 'COD' => 'Cash On Delivery');
		$gen_sliderslist= $database->query("select pt.*,a.reference from ps_payu_transactions pt left join ps_agents a on(pt.id_agent = a.id_agent) WHERE (pt.mihpayid != '' or is_manual=1) and pt.id_agent='".$_SESSION['authtnid']."' ORDER BY pt.date_add DESC");$s='';$i=1;

		foreach($gen_sliderslist as $sliderslist)
		{
			$s.="<tr>
					<td>".$i."</td>
					<td>".(isset($sliderslist['bt_txnid']) ? $sliderslist['bt_txnid'] : '-')."</td>
					<td>".(isset($sliderslist['status']) ? ucfirst($sliderslist['status']) : '-')."</td>
					<td>".(isset($modeArray[$sliderslist['mode']]) ? $modeArray[$sliderslist['mode']] : ((int)$sliderslist['is_manual'] == 1 ? 'Manual Credit' : ''))."</td>
					<td>".(!empty($sliderslist['amount']) && $sliderslist['amount'] != '0.00' ? number_format($sliderslist['amount'],2,'.',',') : '-')."</td>
					<td>".(!empty($sliderslist['previous_balance']) && $sliderslist['previous_balance'] != '0.00' ? number_format($sliderslist['previous_balance'],2,'.',',') : '-')."</td>
					<td>".(!empty($sliderslist['current_balance']) && $sliderslist['current_balance'] != '0.00' ? number_format($sliderslist['current_balance'],2,'.',',') : '-')."</td>
					<td nowrap>".date('d/m/Y H:i:s', strtotime($sliderslist['date_add']))."</td>
				</tr>";
			$i++;
		}
		echo $s;
	}
	else if($_POST['getmods']=="searchreport")
	{
		$modeArray = array('CC' => 'Credit Card', 'DC' => 'Debit Card', 'NB' => 'NetBanking', 'CASH' => 'Cash Card', 'EMI' => 'EMI', 'IVR' => 'IVR', 'COD' => 'Cash On Delivery');
		$where = '';
		$_POST['fromdate'] = (isset($_POST['fromdate']) && !empty($_POST['fromdate']) ? date('Y-m-d', strtotime($_POST['fromdate'])) : '');
		$_POST['todate'] = (isset($_POST['todate']) && !empty($_POST['todate']) ? date('Y-m-d', strtotime($_POST['todate'])) : '');
		if(isset($_POST['fromdate']) && !empty($_POST['fromdate']) && isset($_POST['todate']) && !empty($_POST['todate']))
			$where = " AND pt.date_add >= '".$_POST['fromdate']." 00:00:00' AND pt.date_add <= '".$_POST['todate']." 23:59:59'";
		else if(isset($_POST['fromdate']) && !empty($_POST['fromdate']))
			$where = " AND pt.date_add >= '".$_POST['fromdate']." 00:00:00'";
		else if(isset($_POST['todate']) && !empty($_POST['todate']))
			$where = " AND pt.date_add <= '".$_POST['todate']."' 23:59:59'";
		$gen_sliderslist= $database->query("select pt.*,a.reference from ps_payu_transactions pt left join ps_agents a on(pt.id_agent = a.id_agent) WHERE (pt.mihpayid != '' or is_manual=1) and pt.id_agent='".$_SESSION['authtnid']."'".$where." ORDER BY pt.date_add DESC");$s='';$i=1;

		foreach($gen_sliderslist as $sliderslist)
		{
			$s.="<tr>
					<td>".$i."</td>
					<td>".(isset($sliderslist['bt_txnid']) ? $sliderslist['bt_txnid'] : '-')."</td>
					<td>".(isset($sliderslist['status']) ? ucfirst($sliderslist['status']) : '-')."</td>
					<td>".(isset($modeArray[$sliderslist['mode']]) ? $modeArray[$sliderslist['mode']] : ((int)$sliderslist['is_manual'] == 1 ? 'Manual Credit' : ''))."</td>
					<td>".(!empty($sliderslist['amount']) && $sliderslist['amount'] != '0.00' ? number_format($sliderslist['amount'],2,'.',',') : '-')."</td>
					<td>".(!empty($sliderslist['previous_balance']) && $sliderslist['previous_balance'] != '0.00' ? number_format($sliderslist['previous_balance'],2,'.',',') : '-')."</td>
					<td>".(!empty($sliderslist['current_balance']) && $sliderslist['current_balance'] != '0.00' ? number_format($sliderslist['current_balance'],2,'.',',') : '-')."</td>
					<td nowrap>".date('d/m/Y H:i:s', strtotime($sliderslist['date_add']))."</td>
				</tr>";
			$i++;
		}
		echo $s;
		
	}
	else if($_POST['getmods']=="fetchreportbooking")
	{
		$modeArray = array('CC' => 'Credit Card', 'DC' => 'Debit Card', 'NB' => 'NetBanking', 'CASH' => 'Cash Card', 'EMI' => 'EMI', 'IVR' => 'IVR', 'COD' => 'Cash On Delivery');
		$where = '';
		$_POST['fromdate'] = (isset($_POST['fromdate']) && !empty($_POST['fromdate']) ? date('Y-m-d', strtotime($_POST['fromdate'])) : '');
		$_POST['todate'] = (isset($_POST['todate']) && !empty($_POST['todate']) ? date('Y-m-d', strtotime($_POST['todate'])) : '');
		if(isset($_POST['fromdate']) && !empty($_POST['fromdate']) && isset($_POST['todate']) && !empty($_POST['todate']))
			$where = " AND pt.date_add >= '".$_POST['fromdate']." 00:00:00' AND pt.date_add <= '".$_POST['todate']." 23:59:59'";
		else if(isset($_POST['fromdate']) && !empty($_POST['fromdate']))
			$where = " AND pt.date_add >= '".$_POST['fromdate']." 00:00:00'";
		else if(isset($_POST['todate']) && !empty($_POST['todate']))
			$where = " AND pt.date_add <= '".$_POST['todate']."' 23:59:59'";
		$gen_sliderslist= $database->query("select pt.*,a.reference from ps_payu_transactions pt left join ps_agents a on(pt.id_agent = a.id_agent) WHERE pt.mihpayid = '' and is_manual=0 and productinfo like '%Account Balance%' and pt.id_agent='".$_SESSION['authtnid']."'".$where." ORDER BY pt.date_add DESC");$s='';$i=1;

		foreach($gen_sliderslist as $sliderslist)
		{
			$s.="<tr>
					<td>".$i."</td>
					<td>".(isset($sliderslist['order_info']) ? $sliderslist['order_info'] : '-')."</td>
					<td>".(isset($sliderslist['order_info']) ? (strpos($sliderslist['order_info'],'FL') > 0 ? 'Flight' : (strpos($sliderslist['order_info'],'GU') > 0 ? 'Guide' : (strpos($sliderslist['order_info'],'BS') > 0 ? 'Bus' : ''))) : '-')."</td>
					<td>".(!empty($sliderslist['amount']) && $sliderslist['amount'] != '0.00' ? number_format($sliderslist['amount'],2,'.',',') : '-')."</td>
					<td>".(!empty($sliderslist['previous_balance']) && $sliderslist['previous_balance'] != '0.00' ? number_format($sliderslist['previous_balance'],2,'.',',') : '-')."</td>
					<td>".(!empty($sliderslist['current_balance']) && $sliderslist['current_balance'] != '0.00' ? number_format($sliderslist['current_balance'],2,'.',',') : '-')."</td>
					<td nowrap>".date('d/m/Y H:i:s', strtotime($sliderslist['date_add']))."</td>
				</tr>";
			$i++;
		}
		echo $s;
		
	}
	
}

?>