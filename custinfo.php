<?php
session_start();
//$root_dir="http://".$_SERVER['HTTP_HOST']."/";
include '../../config.php';
include '../../../include/database/config.php';
$currentpage="buscustomer";

$goingtoid=$_GET['sourceStationId'];

$leavingtoid=$_GET['destinationStationId'];

$goingto = $_GET['goingto'];

$leavingto=$_GET['leavingto'];

$boarding_point_list=$_GET['boarding_point_list'];

$droppingPointID=$_GET['droppingPointID'];

$boardingPointID=$_GET['boardingPointID'];

$boardingPointName=$_GET['boardingPointName'];

$operatorId=$_GET['operatorId'];

$serviceId=$_GET['serviceId'];

$sourceStationId=$_GET['sourceStationId'];

$destinationStationId=$_GET['destinationStationId'];

$journeyDate=$_GET['journeyDate'];

$Bus_Type_Name=$_GET['Bus_Type_Name'];

$Traveler_Agent_Name=$_GET['Traveler_Agent_Name'];

$layoutId=$_GET['layoutId'];

$boardingPointID=$_GET['boardingPointID'];

$seatNumbersList=$_GET['seatNumbersList'];

$seatFare=$_GET['seatFare'];

$totalfare=$_GET['totalfare'];

$seatTypeIdsList=$_GET['seatTypeIdsList'];

$seatTypesList=$_GET['seatTypesList'];

$isAcSeat=$_GET['isAcSeat'];

$seatNumbersList_count=count(explode(",",$seatNumbersList));

$time=strtotime($journeyDate);
$weekday=date("D",$time).', '.date("d",$time).' '.date("M",$time).' '.date("Y",$time);
$journeyDate1=date("d",$time).'-'.date("m",$time).'-'.date("Y",$time);
 
/*try{
    $service = new SoapClient($url, array('trace' => 1,'stream_context' => stream_context_create(array('http' => array( 'user_agent' => 'PHPSoapClient') ) )));
    //GetAvailableServices //GetServiceSeatingLayout //BlockTickets //ConfirmationSeatBooking //CancelTicket    
    $response = $service->__soapCall("GetAvailableServices",    
    array(
    array('username' => $username,
    'password' => $password,
	//'operatorId' => $operatorId,
	'sourceStationId' => $source_stn_id,
	'destinationStationId' =>$dest_stn_id,
  	'journeyDate' => $journey_date //'2014-03-14'
    )));    
	
  //  stripcslashes(json_encode($response));  
 $json_data = preg_replace('/^([\'"])(.*)\\1$/', '\\2', stripcslashes(json_encode($response)));
 	$arr = json_decode($response,true);
		
	echo '<pre>';
	print_r($arr);
}
catch(Exception $e){
    echo $e->getMessage();
}*/

try{
	/*$service = new SoapClient($url, array('trace' => 1,'stream_context' => stream_context_create(array('http' => array( 'user_agent' => 'PHPSoapClient') ) )));
    //GetAvailableServices //GetServiceSeatingLayout //BlockTickets //ConfirmationSeatBooking //CancelTicket    
    $response = $service->__soapCall("GetAvailableServices",    
    array(
    array('username' => $username,
    'password' => $password,
	'sourceStationId' => $goingtoid,
	'destinationStationId' =>$leavingtoid,
  	'journeyDate' => $journeyDate //'2014-03-14'
    )));    
	
   $json_data = preg_replace('/^([\'"])(.*)\\1$/', '\\2', stripcslashes(json_encode($response)));
 	$arr = json_decode($response,true);*/


}
catch(Exception $e){
    echo $e->getMessage();
}




?>
<!--<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Bus Results</title>
 
<link href="../../css/style.css" type="text/css" rel="stylesheet" />
  
<link rel="shortcut icon" href="../../favicon.ico" />

 <link rel="stylesheet" type="text/css" href="../css/bootstrap.css" />
  <link rel="stylesheet" type="text/css" href="../css/bootstrap-dropdown-checkbox.css" />
    <link rel="stylesheet" type="text/css" href="../css/icons.css" />
 <script src="../js/jquery-2.1.0.min.js"></script>
<script src="../js/bootstrap-dropdown-checkbox.js" type="text/javascript"></script>
<script src="../js/bootstrap-modal.js" type="text/javascript"></script>
<script src="../js/bootstrap-3.1.1.min.js" type="text/javascript"></script>
<script type="text/javascript" src="../../js/jquery.validate.min.js"></script>-->
<?php include("../../../include/header.php");?>
<script>

$(document).ready(function(e) {
$(".city_name").html($('.city_name option').sort(function(x, y) {
            return $(x).text() < $(y).text() ? -1 : 1;
        }));
      //  $(".city_name").get(0).selectedIndex = 0;
       // e.preventDefault();
	});
function calcTotal(){
	var service_charge = $('#service_charge').val();
	if(!service_charge || service_charge == 'undefined' || service_charge == '') service_charge = 0;
	var total = $("#hidden_grand_total").val();
	var newTotal = parseFloat(service_charge) + parseFloat(total);
	$('.grand_total_span').html(newTotal.toFixed(2));
}
</script>
<style>

</style>






<div class="payment_success" style="width: auto; padding-top: 2%; text-align: center; font-size: 9pt; color: #69bc49;<?php if(isset($_SESSION['lastRechargeAmt']) && !empty($_SESSION['lastRechargeAmt'])){ echo ' display:block;'; unset($_SESSION['lastRechargeAmt']);}else{echo ' display:none;';} ?>">Thank you for your process. Your account has been recharged with the amount Rs. <?php echo (isset($_SESSION['lastRechargeAmt']) && !empty($_SESSION['lastRechargeAmt']) ? $_SESSION['lastRechargeAmt'] : ''); ?> successfully.</div>

<div class="container-fluid nopadding" style="background-color: #a0a0a0">
	<div class="container">
	<ol class="breadcrumb" style="">
	  <!--<li><a href="/" style="top:0;color:#d92525;font-weight:600;">Home</a></li>
	  <!--<li style="color:#555555;padding-left:10px;padding-right:10px;"> / </li>
	  <li><a href="<?php //echo $root_dir ?>bus/" style="top:0;color:#d92525;font-weight:600;">Bus</a></li>-->
	  <!--<li style="color:#555555;padding-left:10px;padding-right:10px;"> / </li>-->
	 <?php
	 //echo ' <li ><a href="'.$root_dir.'/bus/search/?leavingto='.$leavingto.'&from_hidden='.$sourceStationId.'&goingto='.$goingto.'&to_hidden='.$destinationStationId.'&departure_date='.$journeyDate1.'&date_hidden='.$journeyDate.'&bus_submit=Search#" style="top:0;color:#555555;">'.$leavingto.' to '.$goingto.'</a></li>';
	 
	 ?>
	 <li><a href="<?php echo $root_dir ?>bus.php">Bus</a></li>
	 <li><?php echo $leavingto.' to '.$goingto ?></li>
	  <!--<li style="color:#555555;padding-left:10px;padding-right:10px;"> / </li>-->
	  <li>Customer Details</li>
	</ol>
</div>
</div>
<div class="container-fluid " >

<div class="bus_tickets container" style="padding-bottom: 20px;position: relative;">
<div style="float:left"><button style="width:auto;padding:1px 6px;  margin: 10px 0 10px 16px;" class="btn btn-red" ><a href="javascript:history.go(-1)" style="font-size:12px;">Go Back</a></button></div>
<div class="clearfix"></div>
<div class="col-sm-12  col-md-8 col-lg-8 col-xs-12" >
<div class="detail_container" style="border: 1px solid #e7e7e7; box-shadow:1px 2px 9px #ccc; padding-bottom: 10px;">
<h4 class="details_h4" style="margin-top:0px;">Passenger Details</h4>
<form class="cust_details" method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<?php 
for($i=1;$i<=$seatNumbersList_count;$i++)
{
echo '
<div class="col-md-12 col-sm-12 col-xs-12 single_name">
<div class="col-md-4 col-sm-4 col-xs-12">
<label class="flight_font_size3 capital1"  class="lbl'.$i.'">'.$i.'. Name</label>
<input type="text" name="name'.$i.'" class="flight_font_size3" style="" required="required"  />
</div>
<div class="col-md-4 col-sm-4 col-xs-12">
<label class="flight_font_size3 capital1" class="lbl'.$i.'">Gender</label><br>
<div class="col-md-6 col-sm-6 col-xs-6 ">
<label style="font-size:12px; " class="flight_font_size3" ><input type="radio" name="gender'.$i.'" required="required" value="M"  />Male</label>
</div>
<div class="col-md-6 col-sm-6 col-xs-6">
<label style="padding:0;font-size:12px;" class="flight_font_size3" ><input type="radio" name="gender'.$i.'" required="required" value="F" />Female</label>
</div>



</div>
<div class="col-md-4 col-sm-4 col-xs-12">
<label class="flight_font_size3 capital1"  class="lbl'.$i.'">Age</label>
<input class="flight_font_size3" type="text" name="age'.$i.'" style="" maxlength="2" pattern="[0-9]{1,2}" title="must be digits" required="required"  />
</div>

</div>

';
}
?>
<div class="clearfix"></div>
<h4 class="details_h4">Contact Details</h4>
<div class="customer_info">
<div class="col-md-12 col-sm-12 col-xs-12 single_name">
<div class="col-sm-6  col-md-6 col-lg-6 col-xs-12">
<label class="flight_font_size3 capital1">Mobile No</label>
<span class="form-control mobilens" class="flight_font_size3">+91</span><input type="text" name="mobile" style="padding-left:45px;" pattern="[0-9]{10}" title="Must be 10 digit mobile number" required="required" maxlength="10"  />
</div>

<div class="col-sm-6  col-md-6 col-lg-6 col-xs-12">
<label class="flight_font_size3 capital1" >Email</label><br>

<input class="flight_font_size3" type="email" name="email" style="" required="required"  />
</div>
</div>
<div class="col-md-12 ">

<div class="col-sm-6  col-md-6 col-lg-6 col-xs-12 single_name">
<label class="flight_font_size3 capital1">City Name</label><br>
 <input class="flight_font_size3" type="text" name="city_name" style="" required="required" class="city_name"> 
  </div>



<div class="col-sm-6  col-md-6 col-lg-6 col-xs-12">
<label class="flight_font_size3 capital1">Select Id proof</label><br>

<select class="flight_font_size3" id="proofs" name="proofs">   
<option value="" selected>-- Select your Id proof  --</option>
 <option value="PanCard" >PanCard</option> 
 <option value="Driving">Driving license</option>   
 <option value="Passport">Passport</option> 
 <option value="Adharc">Adhar card</option>
</select>   
</div>
</div>

<div class="col-sm-12  col-md-6 col-lg-6 col-xs-12 ">

</div>
<div class="col-sm-12  col-md-6 col-lg-6 col-xs-12" style="padding-top:10px;">
<input type="text" id="proofdetail" name="proofdetail" style="width:250px; display:none;"/>
</div>
</div>
<div >



</div>

<input type="hidden" value="<?php echo $operatorId ?>" name="operatorId" />
<input type="hidden" value="<?php echo $serviceId ?>" name="serviceId" />
<input type="hidden" value="<?php echo $sourceStationId ?>" name="sourceStationId" />
<input type="hidden" value="<?php echo $destinationStationId ?>" name="destinationStationId" />
<input type="hidden" value="<?php echo $goingto ?>" name="goingto" />
<input type="hidden" value="<?php echo $leavingto ?>" name="leavingto" />
<input type="hidden" value="<?php echo $journeyDate ?>" name="journeyDate" />
<input type="hidden" value="<?php echo $layoutId ?>" name="layoutId" />
<input type="hidden" value="<?php echo $boardingPointID ?>" name="boardingPointID" />
<input type="hidden" value="<?php echo $droppingPointID ?>" name="droppingPointID" />
<input type="hidden" value="<?php echo $seatNumbersList ?>" name="seatNumbersList" id="seatNumbersList" />
<input type="hidden" value="<?php echo $seatFare ?>" name="seatFare" id="" />
<input type="hidden" value="<?php echo $seatTypeIdsList ?>" name="seatTypeIdsList" id="" />
<input type="hidden" value="<?php echo $seatTypesList ?>" name="seatTypesList" id="" />
<input type="hidden" value="true" name="isAcSeat"  />
<div class="clearfix"></div>

<h4 class="details_h4">Payment Details</h4>
<div class="table_font table-responsive">
<?php

$serviceTaxPercent = $database->query("select value from ps_configuration where name ='ps_bus_service_tax'")->fetchAll();
$serviceTax = (isset($serviceTaxPercent[0]['value']) && !empty($serviceTaxPercent[0]['value']) ? $serviceTaxPercent[0]['value'] : '0');

$seatFare_explode=explode(",",$seatFare);
$totalfare_cal=$seatFare_explode[0]*$seatNumbersList_count;
$totalfare_cal=sprintf("%0.2f",$totalfare_cal);
$tax=$serviceTax;
$tax=sprintf("%0.2f",$tax);
$grand_total=$totalfare_cal+$tax;
$grand_total=sprintf("%0.2f",$grand_total);
 ?>
<table class="table table-striped table-hover" style="text-align:center;">
<thead>
<tr >
<th style="width:5%;text-align:center;font-size:14px;font-weight:700;color: #555;" class="flight_font_size2 capital1">#</th>
<th style="width:15%;text-align:center;font-size:14px;font-weight:700;color: #555;"  class="flight_font_size2 capital1">Date</th>
<th style="width:30%;text-align:center;font-size:14px;font-weight:700;color: #555;"  class="flight_font_size2 capital1">Journey Details</th>
<th style="width:10%;text-align:center;font-size:14px;font-weight:700;color: #555;"  class="flight_font_size2 capital1">Fare</th>
<th style="width:20%;text-align:center;font-size:14px;font-weight:700;color: #555;"  class="flight_font_size2 capital1">No. of Persons</th>
<th style="width:20%;text-align:center;font-size:14px;font-weight:700;color: #555;"  class="flight_font_size2 capital1">Totalfare</th>
</tr>
</thead>
<tbody>
<tr>
<td style="width:5%;text-align:center;font-size:12px;" class="flight_font_size3">1.</td>
<td style="width:15%;text-align:center;font-size:12px;" class="flight_font_size3"><?php echo $journeyDate ?></td>
<td  style="width:30%;text-align:center;font-size:12px;"  class="flight_font_size3"><?php echo $leavingto.' - '.$goingto ?></td>
<td style="width:10%;text-align:center;font-size:12px;"  nowrap class="flight_font_size3"><span class="rupee"> ` </span><?php echo $grand_total;//$seatFare_explode[0] ?></td>
<td  style="width:20%;text-align:center;font-size:12px;" class="flight_font_size3"><?php echo $seatNumbersList_count ?></td>
<td style="width:20%;text-align:center;font-size:12px;" class="flight_font_size3"><span class="rupee"> ` </span><?php echo $grand_total;//$totalfare_cal ?></td>
</tr>
<!--<tr>
<td colspan="5" style="text-align:right;font-weight:600;">Service Tax:</td>
<td style="text-align:right;"><span class="rupee"> ` </span><?php //echo $tax ?></td>
</tr>-->

<tr>
<td colspan="5" style="text-align:right;font-size:14px;font-weight:700;color: #555; " class="flight_font_size2  capital1">Service charge:</td>
<td style="text-align:right;font-size:13px;"><span class="rupee capital1"></span><input type="text" onkeypress='return event.charCode >= 48 && event.charCode <= 57 || event.charCode == 46;' id="service_charge" onblur="javascript:calcTotal();" style="text-align:right;padding-right:5px;    float: left;"></td>
</tr>
<tr>
<td colspan="5" style="font-size:14px;text-align:right;font-weight:700;color: #555;" class=" capital1">Grand Total :</td>
<td style="text-align:center;"><span class="rupee" style="font-size:12px;"> ` </span><span class="grand_total_span flight_font_size2"><?php echo $grand_total ?></span>
<input type="Hidden" id="hidden_grand_total" value="<?php echo $grand_total; ?>">
</td>
</tr>
</tbody>
</table>
<div class="clearfix"></div>
</div>
<div class="clearfix"></div>


<div style="float:right;     margin-right: 40px; position: relative;">

<input type="submit" class="btn-red" style =" margin-right: 13px;" name="book_ticket" value="Book Now" style="" class="btn" />
<img src="../../load_search.gif" alt="" style="width:25px;display:none; position: absolute; top:3px; left:-31px;" class="submit_loading" />

</div>
</form>

<div class="clearfix"></div>

</div>

</div>

<div class="col-sm-12  col-md-4 col-lg-4 col-xs-12 colm_fixed" >
<div  style="" class="journey_detail">
<h4 style="text-align:center;padding:10px 0;color:white;margin:10px;border-bottom:1px solid #ccc;font-size:14px;font-weight:600; border: 1px solid #DB0B0B; background-image: -webkit-linear-gradient( bottom, #DB0B0B 19%, #910005 56%);;color:white;">Journey Details</h4>
<div style="text-align:left;padding:10px;color:#555;margin:5px;font-size:13px;font-weight:600;height:200px;">

<table class="table table-striped table-hover journey">
<tr>
<td>Journey</td><td>:</td><td class="small_font"><?php echo $leavingto.' to '.$goingto ?></td></tr>
<tr>
<td>Travels</td><td>:</td><td class="small_font"><?php echo $Traveler_Agent_Name ?></td></tr>
<tr>
<td>Type</td><td>:</td><td class="small_font"><?php echo $Bus_Type_Name ?></td></tr>
<tr><td>Boarding</td><td>:</td><td class="small_font"><?php echo $boardingPointName ?></td></tr>
<tr><td>Date</td><td>:</td><td class="small_font"><?php echo $weekday ?></td></tr>
</table>
</div>

</div>
</div>




</div>
</div>
<form method="post" action="<?php echo $root_dir; ?>agentdashboard/secure.payment.php" name="bus_payment" class="bus_payment">
<!--<input type="hidden" name="Title" value="Transaction">
<input type="hidden" name="virtualPaymentClientURL" value="https://migs.mastercard.com.au/vpcpay">
<input type="hidden" name="vpc_Version" value="1">
<input type="hidden" name="vpc_Command" value="pay">
<input type="hidden" name="vpc_AccessCode" value="2E228DC0">
<input type="hidden" name="vpc_MerchTxnRef" value="">
<input type="hidden" name="vpc_Merchant" value="BUDDIESTOUR">-->
<!--<input type="hidden" name="vpc_OrderInfo" value="">-->
<!--<input type="hidden" name="vpc_Amount" value="<?php //echo $grand_total*100 ?>">
<input type="hidden" name="vpc_Amount" value="1">
<input type="hidden" name="vpc_Locale" value="en">
<input type="hidden" name="vpc_ReturnURL" value="http://buddiestours.com/busvac/bus/search/payment_receiving.php">
<input type="hidden" id="grand_total_price" value="<?php //echo $grand_total; ?>">-->

<?php
	if(isset($_SESSION['authtnid']) && !empty($_SESSION['authtnid']))
		$agent_details=$database->query("select * from ps_agents where id_agent=".$_SESSION['authtnid'])->fetchAll();
	
	$MERCHANT_KEY = "gtKFFx";
	$SALT = "eCwWELxi";
	$txnid = substr(hash('sha256', mt_rand() . microtime()), 0, 20);
?>
<input type="hidden" name="firstname" id="firstname" value="<?php echo $_SESSION['username']; ?>" />
<input type="hidden" name="surl" value="<?php echo $root_dir.'busvac/bus/search/payment_receiving.php'; ?>" />
<input type="hidden" name="phone" id="phone" value="<?php echo (isset($agent_details[0]['mobile']) ? $agent_details[0]['mobile'] : ''); ?>" />
<input type="hidden" name="key" id="key" value="<?php echo $MERCHANT_KEY; ?>" />
<input type="hidden" id="salt" value="<?php echo $SALT; ?>" />
<input type="hidden" name="hash" id="hash" value="" />
<input type="hidden" name="curl" value="<?php echo $root_dir.'busvac/bus/search/payment_receiving.php'; ?>" />
<input type="hidden" name="furl" value="<?php echo $root_dir.'busvac/bus/search/payment_receiving.php'; ?>" />
<input type="hidden" name="txnid" id="txnid" value="<?php echo $txnid; ?>" />
<input type="hidden" name="productinfo" id="productinfo" value="" />
<input type="hidden" name="amount" id="amount" value="<?php echo $grand_total; ?>" />
<input type="hidden" name="email" id="email" value="<?php echo 'kirubakaran.it@buddiesholidays.com';//$agent_details[0]['emailid']; ?>" />
</form>
<input type="hidden" id="grand_total_price" value="<?php echo $grand_total; ?>">
<input type="hidden" id="buddies_add_rate" value="<?php echo $serviceTax; ?>">
<?php include '../../footer.php'; /*<?php//echo ($totalfare1*100) $grand_total*100 ?><?php echo $grand_total*100 ?>*/?>
<script type="text/javascript">   
$(document).ready(function() {   
$('#proofs').change(function(){   
if($('#proofs').val() === 'PanCard')   
   {   
   $('#proofdetail').show();
   $('#proofdetail').attr('placeholder','pls enter your PanCard details');
   
   }   
 else if($('#proofs').val() === 'Driving')   
 {
  $('#proofdetail').show();
 $('#proofdetail').attr('placeholder','pls enter your Driving liecense details');  
 }
 
 else if($('#proofs').val() === 'Passport')   
 {
  $('#proofdetail').show();
 $('#proofdetail').attr('placeholder','pls enter your Passport  details');  
 }
 
 
 else if($('#proofs').val() === 'Adharc')   
 {
  $('#proofdetail').show();
 $('#proofdetail').attr('placeholder','pls enter your Adhar card details');  
 }
else 
   {   
   $('#proofdetail').hide();      
   }   
});   
});   
</script>   
<script>
$(document).ready(function(e) {

	$(".bus_payment").submit(function(e) {
		$.LoadingOverlay("show", {
			image       : "",
			fontawesome : "fa fa-spinner fa-spin",
		});
	});
    $(".cust_details").submit(function(e) {
		var formdata=$(this).serialize();
		var balance_amt = $('#balance_amount').val();
		var grand_total = $('#grand_total_price').val();
		var buddies_add_rate = $('#buddies_add_rate').val();
		
		$.ajax({
			type:"GET",
			url:"get_booking_ref.php",
			data:formdata+'&service_charge='+$('#service_charge').val()+'&buddies_add_rate='+buddies_add_rate,
			dataType:"JSON",
			beforeSend: function(){
				$.LoadingOverlay("show", {
					image       : "",
					fontawesome : "fa fa-spinner fa-spin",
				});
			},
			success: function(data){
				$.LoadingOverlay("hide", {
					image       : "",
					fontawesome : "fa fa-spinner fa-spin",
				});
				data=eval(data);
				alert(data);
				return false;
				if(data.status=='success')
				{
					var ref_no=data.refNo;
					var busrefno=data.busRefNo;
					var orderno=data.orderNo;
					if(Number(balance_amt) >= Number(grand_total)){
						$('#productinfo').val(orderno);
						$(".bus_payment").attr('action', '<?php echo $root_dir; ?>busvac/bus/search/payment_receiving.php');
						$(".bus_payment").submit();
						return false;
					}else{
						var productInfo = 'Bus Payment##'+ref_no+'##'+orderno;
						$('#productinfo').val(productInfo);
						calculateHash();
					}
					console.log(data);
				}
				else
				{
					$.confirm({
						title: 'Booking Failed!',
						content: data.message,
						theme: 'supervan',
						backgroundDismiss: true,
						onDestroy: function () {
							window.location='<?php echo $root_dir.'bus.php' ?>';
						},
						buttons: {
							ok: function(){},
						}
					});
				}
			},
			error: function(xhr, desc, err) {
				console.log(xhr);
				console.log("Details: " + desc + "\nError:" + err);
			}
		});
    });
});
function calculateHash(){
	var key = $('#key').val();
	var txnid = $('#txnid').val();
	var amount = $('#amount').val();
	var productinfo = $('#productinfo').val();
	var firstname = $('#firstname').val();
	var email = $('#email').val();
	var salt = $('#salt').val();
	//var additional_charges = $('#additional_charges').val();
	var hashString = key+'|'+txnid+'|'+amount+'|'+productinfo+'|'+firstname+'|'+email+'|||||||||||'+salt;
	$.ajax({
		url: "<?php echo $root_dir; ?>agentdashboard/recharge.php?getHash=1",
		data: 'hashString='+hashString,
		type: "POST",
		async: false,
		success: function(result){
			if($.trim(result) != '' && result)
				$('#hash').val(result);
		}}
	);
	$(".bus_payment").submit();
}
</script>
<script>

if($(window).width() < 800)
{
$(".journey_detail").css("position","relative");
} else {
	$(".journey_detail").css("position","fixed");

}
/*$(window).scroll(function(){
  if($(window).scrollTop() > 20){
     //alert("scrolling");// $("#div").fadeIn("slow");
	 $(".journey-detail").css("top","64px");

  }
});
$(window).scroll(function(){
  if($(window).scrollTop() < 20){
     //$("#div").fadeOut("fast");
	$(".journey-detail").css("top","164px");
  }
});*/
</script>
<div class="foo" style="clear: both;">
<?php include('../../../include/footer.php');?>


</div>
<!--</body>
</html>-->