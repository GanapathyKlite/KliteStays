<?php
if(!$_SERVER['HTTP_X_REQUESTED_WITH']== 'XMLHttpRequest')
{
   header("Location:login.php");
}
else
{
	session_start();

	error_reporting(0);
	include('../include/database/config.php');
	$date= new DateTime('now', new DateTimeZone('Asia/Kolkata'));

	if($_POST['getmods']=="fetchreportcar")
	{
	
	
		$manage = FALSE;
		if(isset($_POST['manage']) && !empty($_POST['manage']))
			$manage = true;
		$carList=$database->query("select * from ps_car_booking where customer_id='".$_SESSION['authtnid']."' and is_cancel!=2 ".($manage ? ' and booking_datetime <= \''.date('Y-m-d').' 23:59:59\' and is_cancel in(0,1)' : 'and is_cancel NOT IN(3,2,1) and booking_datetime <= \''.date('Y-m-d').' 23:59:59\'')." order by id desc")->fetchAll(); 
		$carList1=$database->query("select * from ps_car_tailor_booking where customer_id='".$_SESSION['authtnid']."' and is_cancel!=2 ".($manage ? ' and booking_datetime <= \''.date('Y-m-d').' 23:59:59\' and is_cancel in(0,1)' : 'and is_cancel NOT IN(3,2,1) and booking_datetime <= \''.date('Y-m-d').' 23:59:59\'')." order by id_car_tailor_booking desc")->fetchAll();
		$carList2=$database->query("select * from ps_carbooking_oneway where customer_id='".$_SESSION['authtnid']."' and is_cancel!=2 ".($manage ? ' and booking_datetime <= \''.date('Y-m-d').' 23:59:59\' and is_cancel in(0,1)' : 'and is_cancel NOT IN(3,2,1) and booking_datetime <= \''.date('Y-m-d').' 23:59:59\'')." order by id_car_oneway desc")->fetchAll();

		$carList=array_merge($carList,$carList1,$carList2);
		$price = array();
		foreach ($carList as $key => $row)
		{
		    $price[$key] = $row['booking_datetime'];
		}
		array_multisort($price, SORT_DESC, $carList);
		$i=1;
	
		foreach($carList as $car)
		{
			$id=($car["id"]?$car["id"]:($car["id_car_tailor_booking"]?$car["id_car_tailor_booking"]:($car["id_car_oneway"]?$car["id_car_oneway"]:'')));
			if(isset($car["id"]))
			{
				$type='c';
			}
			elseif(isset($car["id_car_tailor_booking"]))
			{
				$type='ct';
			}
			elseif(isset($car["id_car_oneway"]))
			{
				$type='ctone';
			}
			$s.="<tr>
				<td class='centertext'>".$i."</td>
				<td class='centertext'>".date('Y-m-d', strtotime($car['booking_date']))."</td>
				<td class='centertext'>".$car['booking_ref']."</td>
				<td class='centertext'>".$car['name']."</td>
				<td class='centertext'>".$car['phone']."</td>
				<td class='centertext'>".$car['source_name']."</td>
				<td class='centertext'>".$car['destination_name']."</td>";	
				if($manage == true)
				{
					$s.=($car['is_cancel'] == '0' ? "<td class='centertext'><button class='btn buttonsearch' onclick=\"javascript:cancelBooking('".$type."',".$id.");\">Cancel Booking</button></td>" : ($car['is_cancel'] == '1' ? "<td class=\'centertext\'><span class='label color_field' style='background-color:RoyalBlue;color:white'>Awaiting Approval</span></td>" : ($car['is_cancel'] == '2' ? '<span class="label color_field" style="background-color:DarkOrange;color:white">Awaiting Refund</span>' : '')));
				}			
				else
				{
					if($type=='c')
					{
						$s.=($car['is_cancel'] == '0' ? "<td class='centertext'><button class='btn buttonsearch'  onclick=\"javascript:window.open('".$root_dir."car/confirmation.php?bookingRef=".$car['booking_ref']."','_blank');\">&nbsp;View</button></td>" : '');
					}elseif($type=='ct')
					{
						$s.=($car['is_cancel'] == '0' ? "<td class='centertext'><button class='btn buttonsearch'  onclick=\"javascript:window.open('".$root_dir."car/confirmation_tailor.php?bookingRef=".$car['booking_ref']."','_blank');\">&nbsp;View</button></td>" : '');
					}else
					{
						$s.=($car['is_cancel'] == '0' ? "<td class='centertext'><button class='btn buttonsearch'  onclick=\"javascript:window.open('".$root_dir."car/confirmation_oneway.php?bookingRef=".$car['booking_ref']."','_blank');\">&nbsp;View</button></td>" : '');
					}
					
				}
				
			$s.="</tr>";
			$i++;
		}
		echo $s;
	}
	else if($_POST['getmods']=="searchreportcar")
	{
		$manage = false;
		if(isset($_POST['manage']) && !empty($_POST['manage']))
			$manage = true;
		$where = '';
		if(isset($_POST['fromdate']) && !empty($_POST['fromdate']) && isset($_POST['todate']) && !empty($_POST['todate']))
			$where = " AND booking_datetime >= '".date('Y-m-d', strtotime($_POST['fromdate']))." 00:00:00' AND booking_datetime <= '".date('Y-m-d', strtotime($_POST['todate']))." 23:59:59'";
		else if(isset($_POST['fromdate']) && !empty($_POST['fromdate']))
			$where = " AND booking_datetime >= '".date('Y-m-d', strtotime($_POST['fromdate']))." 00:00:00'";
		else if(isset($_POST['todate']) && !empty($_POST['todate']))
			$where = " AND booking_datetime <= '".date('Y-m-d', strtotime($_POST['todate']))." 23:59:59'";

		$carList=$database->query("select * from ps_car_booking where customer_id='".$_SESSION['authtnid']."' and is_cancel!=2".$where.($manage ? ' and is_cancel IN(0,1) and booking_datetime <= \''.date('Y-m-d').' 23:59:59\'' : ' and is_cancel NOT IN(1,2,3) and booking_datetime <= \''.date('Y-m-d').' 23:59:59\'')." order by id desc")->fetchAll();

		$carList1=$database->query("select * from ps_car_tailor_booking where customer_id='".$_SESSION['authtnid']."' and is_cancel!=2".$where.($manage ? ' and is_cancel IN(0,1)  and booking_datetime <= \''.date('Y-m-d').' 23:59:59\'' : ' and is_cancel NOT IN(3,2,1)  and booking_datetime <= \''.date('Y-m-d').' 23:59:59\'')." order by id_car_tailor_booking desc")->fetchAll();

		$carList=array_merge($carList,$carList1);
		$price = array();
		foreach ($carList as $key => $row)
		{
		    $price[$key] = $row['booking_datetime'];
		}
		array_multisort($price, SORT_DESC, $carList);
		 $i=1;
		foreach($carList as $car)
		{
			$id=($car["id"]?$car["id"]:isset($car["id_car_tailor_booking"])?$car["id_car_tailor_booking"]:'');
			if(isset($car["id"]))
			{
				$type='c';
			}
			elseif(isset($car["id_car_tailor_booking"]))
			{
				$type='ct';
			}			$s.="<tr>
				<td class='centertext'>".$i."</td>
				<td class='centertext'>".date('Y-m-d', strtotime($car['booking_date']))."</td>
				<td class='centertext'>".$car['booking_ref']."</td>
				<td class='centertext'>".$car['name']."</td>
				<td class='centertext'>".$car['phone']."</td>
				<td class='centertext'>".$car['source_name']."</td>
				<td class='centertext'>".$car['destination_name']."</td>				
				".($manage && $car['is_cancel'] == '0' ? "<td class='centertext'><button class='btn buttonsearch' onclick=\"javascript:cancelBooking('".$type."',".$id.");\">Cancel Booking</button></td>" : ($car['is_cancel'] == '1' ? '<td class=\'centertext\'><span class="label color_field" style="background-color:RoyalBlue;color:white">Awaiting Approval</span></td>' : ($car['is_cancel'] == '2' ? '<span class="label color_field" style="background-color:DarkOrange;color:white">Awaiting Refund</span>' : '')))."
				".(!($manage) && ($car['is_cancel'] == '0') ? "<td class='centertext'><button class='btn buttonsearch'  onclick=\"javascript:window.open('".$root_dir."car/confirmation.php?bookingRef=".$car['booking_ref']."','_blank');\">&nbsp;View</button></td>" : '')."
			</tr>";
			$i++;
		}
		echo $s;
	}
	else if($_POST['getmods']=="fetchreportcarcancel")
	{
		if(isset($_POST['fromdate']) && !empty($_POST['fromdate']) && isset($_POST['todate']) && !empty($_POST['todate']))
			$where = " AND booking_datetime >= '".date('Y-m-d', strtotime($_POST['fromdate']))." 00:00:00' AND booking_datetime <= '".date('Y-m-d', strtotime($_POST['todate']))." 23:59:59'";
		else if(isset($_POST['fromdate']) && !empty($_POST['fromdate']))
			$where = " AND booking_datetime >= '".date('Y-m-d', strtotime($_POST['fromdate']))." 00:00:00'";
		else if(isset($_POST['todate']) && !empty($_POST['todate']))
			$where = " AND booking_datetime <= '".date('Y-m-d', strtotime($_POST['todate']))." 23:59:59'";

		$carList=$database->query("select * from ps_car_booking where customer_id='".$_SESSION['authtnid']."' and is_cancel=3".$where." order by id desc")->fetchAll();

		$carList1=$database->query("select * from ps_car_tailor_booking where customer_id='".$_SESSION['authtnid']."' and is_cancel=3".$where." order by id_car_tailor_booking desc")->fetchAll();
		 $s='';  $i=1;
		$carList=array_merge($carList,$carList1);

		foreach($carList as $car)
		{
			$id=($car["id"]?$car["id"]:isset($car["id_car_tailor_booking"])?$car["id_car_tailor_booking"]:'');
			$s.="<tr>
				<td class='centertext'>".$i."</td>
				<td class='centertext'>".date('Y-m-d', strtotime($car['booking_date']))."</td>
				<td class='centertext'>".$car['booking_ref']."</td>
				<td class='centertext'>".$car['name']."</td>
				<td class='centertext'>".$car['phone']."</td>
				<td class='centertext'>".$car['source_name']."</td>
				<td class='centertext'>".$car['total']."</td>
				<td class='centertext'>".$car['refund_amount']."</td>
				<td class='centertext'>".$car['refund_percentage']."</td>

				<td><span class='label color_field' style='background-color:#108510;color:white'>Refund Successful</span></td>					
			</tr>";
			$i++;
		}
		echo $s;
	}
	

}

?>