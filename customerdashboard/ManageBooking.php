<?php 
include('../include/database/config.php');
if(isset($_POST['b_id']) && !empty($_POST['b_id'])){
	include('../busvac/config.php');
	$b_id = $_POST['b_id'];
	$seats = explode(',',$_POST['value']);
	$partial = $_POST['partial'];
	
	$ticket_details=$database_bus->query("select td.*,bd.operatorId from ticket_details td left join booking_details bd on(td.b_id = bd.id) where td.b_id=".$b_id)->fetchAll();
	
	if($partial){
		$bookedSeats = explode(',',$ticket_details[0]['seats']);
		foreach($seats as $seat){
			if(isset($seat) && !empty($seat) && !in_array($seat, $bookedSeats)){
				echo json_encode(array('error' => 'Invalid Seats'));
				exit;
			}
		}
		$partialCancellation = 1;//(count($seats) != count($bookedSeats) ? '1' : '0');
	}else
		$partialCancellation = 0;

	$phoneNum = $ticket_details[0]['phoneNum'];
	$ticketNo = $ticket_details[0]['ticket_no'];
	$cancelSeats = $_POST['value'];
	$operatorId = $ticket_details[0]['operatorId'];
	try
	{
		$service = new SoapClient($url, array('trace' => 1,'stream_context' => stream_context_create(array('http' => array( 'user_agent' => 'PHPSoapClient') ) )));

		$cancel_ticket = $service->__soapCall("CancelTicket",
			array(
				array('username' => $username,
					'password' => $password,
					'operatorId' => $operatorId,
					'phoneNum' => $phoneNum,
					'ticketNo' => $ticketNo,
					'cancelSeats' => $cancelSeats,
					'partialCancellation' => $partialCancellation
				)
			)
		);    

		$cancel_ticket = preg_replace('/^([\'"])(.*)\\1$/', '\\2', stripcslashes(json_encode($cancel_ticket)));
		$arr_cancel_ticket = json_decode($cancel_ticket,true);
		
		if(isset($arr_cancel_ticket['status']) && $arr_cancel_ticket['status'] == 'success'){
			$values = array(
					'b_id' => $b_id,
					'ticket_no' => $ticketNo,
					'seat' => $cancelSeats,
					'mobileno' => $phoneNum,
					'refundamount' => (isset($arr_cancel_ticket['return_amount']) ? $arr_cancel_ticket['return_amount'] : ''),
					'status' => (isset($arr_cancel_ticket['status']) ? $arr_cancel_ticket['status'] : ''),
					'refundstatus' => 0,
					'detail' => (isset($arr_cancel_ticket['message']) ? $arr_cancel_ticket['message'] : ''),
					'NewPNR' => (isset($arr_cancel_ticket['NewPNR']) ? $arr_cancel_ticket['NewPNR'] : ''),
					'cancelled_seats' => (isset($arr_cancel_ticket['cancelled_seats']) ? $arr_cancel_ticket['cancelled_seats'] : ''),
					'totalcancel_seatsfare' => (isset($arr_cancel_ticket['totalcancel_seatsfare']) ? $arr_cancel_ticket['totalcancel_seatsfare'] : ''),
					'date_add' => date('Y-m-d H:i:s')
				);
			$database_bus->insert('cancellation_details', $values);
			if(empty($partialCancellation))
				$database_bus->exec('update ticket_details set iscancel=1 where b_id='.$b_id);
			echo json_encode(array('status' => 'success'));
			exit;
		}
	}
	catch(Exception $e)
	{
		$e->getMessage();
	}
	echo json_encode(array('status' => 'fail'));
	exit;
}else if(isset($_POST['type']) && !empty($_POST['type'])){
	$id = (int)$_POST['id'];
	$value = $_POST['value'];
	$type = $_POST['type'];
	
	$smsMailDetailsArr = $database_bus->query("select bd.orderno,td.ticket_html,td.sms_html from booking_details bd left join ticket_details td on(bd.id = td.b_id) where bd.id=".$id)->fetchAll();
	if($type == 'sms')
		$res = smsAPICall($smsMailDetailsArr[0]['sms_html'],$value);
	else
		$res = sendConfirmationMail($value, 'Ticket Booking Confirmation', $smsMailDetailsArr[0]['ticket_html'], $smsMailDetailsArr[0]['orderno'].'.pdf', '/home/buddiest/public_html/agent/busvac/bus/search/tickets/');
	if($res) echo '1';
	die();
}

include('include/header.php');
?>	
<link href="plugins/datatables/css/jquery.datatables.min.css" rel="stylesheet" type="text/css"/>	
<link href="plugins/datatables/css/jquery.datatables_themeroller.css" rel="stylesheet" type="text/css"/>	
<link rel="stylesheet" href="css/datatables.css" id="toggleCSS" />

<div class="managebooking">
	<div class="container contain" >
		<form class="form-inline top_form" onsubmit="javascript:return false;">
			<div class="form-group">
				<label class="from">From</label>
				<input type="text" class="form-control" id="fromdate" name="fromdate" placeholder="dd-mm-yyyy" autocomplete="off" required >
			</div>
			<div class="form-group">
				<label>To</label>
				<input type="text" class="form-control" id="todate" name="todate" placeholder="dd-mm-yyyy" autocomplete="off" required >
			</div>
			<button id="searchreport" class="btn search_button" ><i class="fa fa-search"></i>&nbsp;Search</button>
			<div class="form-group pdf_icon" >
				<a href="#"  onclick="javascript:downloadReport('pdf');"><img src="images/pdf.png" title="Download Invoice" alt="" >Export</a>
			</div>
			<div class="form-group pdf_icon">
				<a href="#"  onclick="javascript:downloadReport('excel');"><img src="images/excel.png" title="Download Invoice" alt="">Export</a>
			</div>
		</form>

		<div class="col-md-12 table-responsive manage" style="border:0px solid red; text-align:center;">
			<table id="datatable" class="table profile table-bordered table-responsive table-striped datatable tablestyle">
				<thead>
				  <tr>
					<th>Sl.No.</th>
					<th>Booking ID</th>
					<th>Booking Date</th>
					<th>Journey Date</th>
					<th>Ticket No.</th>
					<th>Passenger Name</th>
					<th>Source</th>
					<th>Destination</th>
					<th>Total Fare</th>
					<th>Commission</th>
					<th>Service Charge</th>
					<th>View</th>
					<th>Resend</th>
					<th>Cancel</th>
				  </tr>
				</thead>
				<tbody id="tablecontent">
				</tbody>
			</table>
		</div>
	</div>	
</div>	
<div class="clearfix"> </div>


<div id="showCancelTicket" style="display:none;">
	<label style="background-color: white !important;color: #DB0B0B;font-size: 16px;">Ticket Cancellation</label>
	<div style="margin:10px;">
		<label style="font-size: 13px;font-weight:normal;">Booked Seat Nos.: </label><span style="font-size: 14px;font-weight:normal;margin-left:2%;" class="booked_seats"></span><br><br>
		<label style="font-size: 13px;font-weight:normal;">Please enter the seat numbers that you wish to cancel: </label><br><input type="Text" name="to_cancel_seats"><br><span style="font-size: 11px;font-weight:normal;">(Use comma separator for multiple values)</span>
		<input type="Hidden" name="booking_id">
	</div>
	<input type="button" value="Submit" class="btn" onclick="javascript:validateCancellation();" style="background-image: linear-gradient(to bottom, #DB0B0B 19%, #910005 56%); color:#fff;margin-top: 3.5%;margin-left: 40%;">
</div>
<div id="showResendTicketContent" style="display:none;">
	<p>
		<label>Mobile Number:</label>
		<input type="text" id="resend_mobile" onkeypress="return event.charCode >= 48 && event.charCode <= 57;" maxlength="10">
		<button id="send_sms" class="btn search_button" onclick="javascript:sendSMSMail('sms');">Send SMS</button>
	</p>
	<p>
		<label>E-mail ID:</label>
		<input type="text" id="resend_email" maxlength="100">
		<button id="send_email" class="btn search_button" onclick="javascript:sendSMSMail();">Send E-mail</button>
	</p>
	<input type="hidden" id="hidden_id" value="">
</div>
<?php include('include/footer.php');?>
   

<script src="js/jquery.dataTables.min.js"></script>
<script src="js/datatables.js"></script>
<script type="text/javascript" src="js/jquery.datepick.js"></script>
<link rel="stylesheet" type="text/css" href="css/jquery.datepick.css">
<link rel="stylesheet" href="css/jquery-ui.css">
<script src="js/jquery-ui.js"></script>
 <script src="js/script_managebooking.js"></script>
 <script src="<?=$root_dir;?>js/script.js"></script>
<!--Start of Zopim Live Chat Script -->
<script type="text/javascript">
window.$zopim||(function(d,s){var z=$zopim=function(c){z._.push(c)},$=z.s=
d.createElement(s),e=d.getElementsByTagName(s)[0];z.set=function(o){z.set.
_.push(o)};z._=[];z.set._=[];$.async=!0;$.setAttribute('charset','utf-8');
$.src='//v2.zopim.com/?1QkEwkuxE0ZXXPAx1k46Ymsdrgigmd51';z.t=+new Date;$.
type='text/javascript';e.parentNode.insertBefore($,e)})(document,'script');

function downloadReport(type){
	if(type == 'excel')
		var url = 'excel-report/viewBooking.php?';
	else if(type == 'pdf')
		var url = 'pdf1/code/viewBooking.php?';
		
	window.open(url+"id=<?php echo (isset($_SESSION['authtnid']) ? $_SESSION['authtnid'] : ''); ?>&manage=2&from="+$('#fromdate').val()+"&to="+$('#todate').val(), "_blank");
	return false;
}
function showResendTicket(id){
	$('#hidden_id').val(id);
	var content = $('#showResendTicketContent').html();
	$.fancybox({
			'content': content,
			'autoSize': true,
			'padding' : 20
		});
}
function sendSMSMail(type){
	var value,data,msg,errorMsg = '';
	if(type == 'sms'){
		value = 'value='+$('.fancybox-wrap').find('input[id="resend_mobile"]').val();
		data = 'type=sms';
		if(!$('.fancybox-wrap').find('input[id="resend_mobile"]').val())
			errorMsg = 'Please enter Mobile number!';
	}else{
		value = 'value='+$('.fancybox-wrap').find('input[id="resend_email"]').val();
		data = 'type=email';
		if(!$('.fancybox-wrap').find('input[id="resend_email"]').val())
			errorMsg = 'Please enter Email id!';
	}
	if(errorMsg){
		$.confirm({
			title: '',
			content: errorMsg,
			theme: 'supervan',
			backgroundDismiss: true,
			buttons: {
				ok: function(){}
			},
		});
		return false;
	}
	var id = $('.fancybox-wrap').find('input[id="hidden_id"]').val();
	$.ajax({
		url: 'ManageBooking.php',
		data: data+'&'+value+'&id='+id,
		type: 'post',
		success: function(result){
			if($.trim(result) == '1'){
				if(type == 'sms') msg = 'SMS sent successfully!';
				else msg = 'Mail sent successfully!';
			}else
				msg = 'Error occurred!';
			$.confirm({
				title: '',
				content: msg,
				theme: 'supervan',
				backgroundDismiss: true,
				buttons: {
					ok: function(){
						$.fancybox.close();
					}
				}
			});
		}
	});
}
</script>
 
	   
	