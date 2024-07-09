<?php 
	error_reporting(0);
	include('include/header.php');
	
	if(isset($_GET['serviceCharge']))
	{
		$val=$_GET['serviceCharge'];
	
	}else
	{
		$res=$database->query("select * from ps_car_servicecharge where id_agent=".$_SESSION['authtnid'])->fetchAll(PDO::FETCH_ASSOC);
		$val=$res[0]['serviceCharge'];
		
	}

	
?>
<div class="viewbook">
	<div class="container contain">
	<form class="form-inline top_form" action="" name="servicecharge" method="GET">
			<div class="form-group">
				<label class="from">Service Charge</label>
				<input type="text" class="form-control" id="serviceCharge" name="serviceCharge" placeholder="Enter Price"  value="<?php echo (isset($val) ? $val : '') ?>" >
				
			</div>
			<div class="form-group">
			<button type="submit" class="btn cubtn" id="addprice" >Update</button>
			</div>
	</form>
	</div>
</div>

<?php


	$gettimedate=date("Y-m-d h:i:s");
	$res=$database->query("select * from ps_car_servicecharge where id_agent=".$_SESSION['authtnid'])->fetchAll(PDO::FETCH_ASSOC);
	
	if(!empty($res))
	{

		if($res[0]['id_agent']==$_SESSION['authtnid'])
		{
			
			$res=$database->query("update ps_car_servicecharge set serviceCharge='".$_GET['serviceCharge']."', date_upd='".$gettimedate."' where id_agent=".$_SESSION['authtnid']);
		}
		else
		{
			$res=$database->query("insert into ps_car_servicecharge (serviceCharge,id_agent,date_upd) values('".$_GET['serviceCharge']."','".$_SESSION['authtnid']."','".$gettimedate."')");
		}
		

	
	}
	else
	{

		$res=$database->query("insert into ps_car_servicecharge (serviceCharge,id_agent,date_upd) values('".$_GET['serviceCharge']."','".$_SESSION['authtnid']."','".$gettimedate."')");
		
		
	}
		

		


?>

