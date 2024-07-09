<?php 
//echo '<pre>hotel/search/booking/success.php-->'; print_r($_REQUEST); echo '</pre>'; die();
$currentpage='hotel';
	session_start();
	include '../../../include/database/config.php';
	error_reporting(0);
	if(isset($_REQUEST['mihpayid']) && !empty($_REQUEST['mihpayid'])){
		
		$transExists = $database->query("select id from ps_payu_transactions where mihpayid='".$_REQUEST['mihpayid']."'")->fetchAll();
		//if(!isset($transExists[0][0]) || empty($transExists[0][0])){
			if($_REQUEST['status'] == 'success'){
				$bt_reference = 'PTI'.mt_rand(10000000, 99999999);
				$values = array(
					'mihpayid' => (isset($_REQUEST['mihpayid']) ? $_REQUEST['mihpayid'] : ''),
					'mode' => (isset($_REQUEST['mode']) ? $_REQUEST['mode'] : ''),
					'status' => (isset($_REQUEST['status']) ? $_REQUEST['status'] : ''),
					'merchant_key' => (isset($_REQUEST['key']) ? $_REQUEST['key'] : ''),
					'bt_txnid' => $bt_reference,
					'txnid' => (isset($_REQUEST['txnid']) ? $_REQUEST['txnid'] : ''),
					'amount' => (isset($_REQUEST['amount']) ? $_REQUEST['amount'] : ''),
					'additionalCharges' => (isset($_REQUEST['additionalCharges']) ? $_REQUEST['additionalCharges'] : ''),
					'net_amount_debit' => (isset($_REQUEST['net_amount_debit']) ? $_REQUEST['net_amount_debit'] : ''),
					'addedon' => (isset($_REQUEST['addedon']) ? $_REQUEST['addedon'] : ''),
					'discount' => (isset($_REQUEST['discount']) ? $_REQUEST['discount'] : ''),
					'offer' => (isset($_REQUEST['offer']) ? $_REQUEST['offer'] : ''),
					'productinfo' => (isset($_REQUEST['productinfo']) ? $_REQUEST['productinfo'] : ''),
					'firstname' => (isset($_REQUEST['firstname']) ? $_REQUEST['firstname'] : ''),
					'email' => (isset($_REQUEST['email']) ? $_REQUEST['email'] : ''),
					'phone' => (isset($_REQUEST['phone']) ? $_REQUEST['phone'] : ''),
					'hash' => (isset($_REQUEST['hash']) ? $_REQUEST['hash'] : ''),
					'error' => (isset($_REQUEST['error']) ? $_REQUEST['error'] : ''),
					'bankcode' => (isset($_REQUEST['bankcode']) ? $_REQUEST['bankcode'] : ''),
					'PG_TYPE' => (isset($_REQUEST['PG_TYPE']) ? $_REQUEST['PG_TYPE'] : ''),
					'bank_ref_num' => (isset($_REQUEST['bank_ref_num']) ? $_REQUEST['bank_ref_num'] : ''),
					'unmappedstatus' => (isset($_REQUEST['unmappedstatus']) ? $_REQUEST['unmappedstatus'] : ''),
					'cardCategory' => (isset($_REQUEST['cardCategory']) ? $_REQUEST['cardCategory'] : ''),
					'error_Message' => (isset($_REQUEST['error_Message']) ? $_REQUEST['error_Message'] : ''),
					'name_on_card' => (isset($_REQUEST['name_on_card']) ? $_REQUEST['name_on_card'] : ''),
					'cardnum' => (isset($_REQUEST['cardnum']) ? $_REQUEST['cardnum'] : ''),
					'issuing_bank' => (isset($_REQUEST['issuing_bank']) ? $_REQUEST['issuing_bank'] : ''),
					'card_type' => (isset($_REQUEST['card_type']) ? $_REQUEST['card_type'] : ''),
					'date_add' => date('Y-m-d H:i:s'));

				if(!isset($transExists[0][0]) || empty($transExists[0][0]))
					$database->insert('ps_payu_transactions', $values);

				$getBookingDetails = $database->query("select hb.*,r.txtRoomName,p.txtEmail from ps_hotel_booking hb left join ps_property p on(p.id_property=hb.id_property) left join ps_room r on(hb.room_id=r.id_room) where hb.txnid='".$_REQUEST['txnid']."'")->fetchAll();
				if(isset($getBookingDetails[0]) && !empty($getBookingDetails[0])){

					$check_in = $getBookingDetails[0]['check_in'];
					$check_out = $getBookingDetails[0]['check_out'];
					$room_id = $getBookingDetails[0]['room_id'];
					$room_count = $getBookingDetails[0]['room_count'];
					$property_email = $getBookingDetails[0]['txtEmail'];
					$id_property = $getBookingDetails[0]['id_property'];

					if(!isset($transExists[0][0]) || empty($transExists[0][0])){
						// update inventory
						$begin 	= new DateTime(date('Y-m-d',strtotime($check_in)));
						$end 	= new DateTime(date('Y-m-d',strtotime($check_out)));
						$daterange = new DatePeriod($begin, new DateInterval('P1D'), $end);
						foreach($daterange as $date){
							$dateValue = $date->format("Y-m-d");
							$year	= date('Y',strtotime($dateValue));
							$month	= date('n',strtotime($dateValue));
							$dateV	= date('j',strtotime($dateValue));
							$database->exec('update ps_room_available_inventory set `'.$dateV.'` = `'.$dateV.'` - '.$room_count.' where allot_avail=1 and id_room='.$room_id.' and year='.$year.' and month='.$month);
						}
						$database->exec('update ps_hotel_booking set is_payment_success = 1 where booking_reference like \''.$getBookingDetails[0]['booking_reference'].'\' limit 1');
						sendConfirmationMail($getBookingDetails[0]['customer_email'], 'Hotel Booking Confirmation', $getBookingDetails[0]['voucher_html'], $getBookingDetails[0]['booking_reference'].'.pdf', dirname(__FILE__).'/vouchers/');
						//$property_email
						sendConfirmationMail($property_email, 'Hotel Booking Confirmation', $getBookingDetails[0]['hotel_voucher_html'], $getBookingDetails[0]['booking_reference'].'_hotel.pdf', dirname(__FILE__).'/vouchers/');
						if(isset($getBookingDetails[0]['sms_html']) && !empty($getBookingDetails[0]['sms_html']))
							smsAPICall($getBookingDetails[0]['sms_html'],$getBookingDetails[0]['customer_contact']);
						smsAPICall('Dear Hotelier, New Booking Arrived: Booking ID - '.$getBookingDetails[0]['hotelier_reference'].'. Please check your mail for complete details.',$getBookingDetails[0]['txtMobile']);
					}
				}
			}
		//}
	}
	include '../../../include/header.php';
?>
<script src="<?php echo $root_dir; ?>hotel/js/jQuery.print.js" type="text/javascript"></script>
<div class="container" style="padding:20px 30px;box-shadow: 1px 1px 4px #959494;margin:107px auto">
    <div class="col-md-12 col-xs-12">
    	<?php global $roomTypes; ?>
		<?php if(isset($_REQUEST['status']) && $_REQUEST['status'] == 'success'){ ?>
        <h3>Booking Successful!</h3>
        <hr>
		<div class="col-md-2 col-xs-2"></div>
		<div class="col-md-10 col-xs-10">
			<div class="col-md-5 col-xs-5">Booking Reference</div>
			<div class="col-md-1 col-xs-1">:</div>
			<div class="col-md-6 col-xs-6"><?php echo $getBookingDetails[0]['booking_reference']; ?></div>
		</div>
		<div class="col-md-2 col-xs-2"></div>
		<div class="clearfix"></div>
		<div class="col-md-2 col-xs-2"></div>
		<div class="col-md-10 col-xs-10">
			<div class="col-md-5 col-xs-5">Primary Guest</div>
			<div class="col-md-1 col-xs-1">:</div>
			<div class="col-md-6 col-xs-6"><?php echo ucfirst($getBookingDetails[0]['customer_firstname']); ?></div>
		</div>
		<div class="col-md-2 col-xs-2"></div>
		<div class="clearfix"></div>
		<div class="col-md-2 col-xs-2"></div>
		<div class="col-md-10 col-xs-10">
			<div class="col-md-5 col-xs-5">Room Type,Category</div>
			<div class="col-md-1 col-xs-1">:</div>
			<div class="col-md-6 col-xs-6"><?php echo (isset($roomTypes[$getBookingDetails[0]['room_type_id']]) ? $roomTypes[$getBookingDetails[0]['room_type_id']] : '').(isset($getBookingDetails[0]['txtRoomName']) ? ', '.$getBookingDetails[0]['txtRoomName'] : ''); ?></div>
		</div>
		<div class="col-md-2 col-xs-2"></div>
		<div class="clearfix"></div>
		<div class="col-md-2 col-xs-2"></div>
		<div class="col-md-10 col-xs-10">
			<div class="col-md-5 col-xs-5">Room(s)</div>
			<div class="col-md-1 col-xs-1">:</div>
			<div class="col-md-6 col-xs-6"><?php echo $getBookingDetails[0]['guest_text']; ?></div>
		</div>
		<div class="col-md-2 col-xs-2"></div>
		<div class="clearfix"></div>
		<div class="col-md-2 col-xs-2"></div>
		<div class="col-md-10 col-xs-10">
			<div class="col-md-5 col-xs-5">Check-In</div>
			<div class="col-md-1 col-xs-1">:</div>
			<div class="col-md-6 col-xs-6"><?php echo date('d/m/Y',strtotime($getBookingDetails[0]['check_in'])); ?></div>
		</div>
		<div class="col-md-2 col-xs-2"></div>
		<div class="clearfix"></div>
		<div class="col-md-2 col-xs-2"></div>
		<div class="col-md-10 col-xs-10">
			<div class="col-md-5 col-xs-5">Check-Out</div>
			<div class="col-md-1 col-xs-1">:</div>
			<div class="col-md-6 col-xs-6"><?php echo date('d/m/Y',strtotime($getBookingDetails[0]['check_out'])); ?></div>
		</div>
		<div class="col-md-2 col-xs-2"></div>
		<div class="clearfix"></div>
		<?php }else if(!isset($_REQUEST['status']) || $_REQUEST['status'] != 'success'){ ?>
		<h3>Booking Unuccessful!</h3>
        <hr>
		<div class="col-md-2 col-xs-2"></div>
		<div class="col-md-10 col-xs-10">
			<p><?php echo $_REQUEST['error_Message']; ?></p>
		</div>
		<div class="col-md-2 col-xs-2"></div>
		<div class="clearfix"></div>
		<?php }else{ ?>
			<script type="text/javascript">
				window.location = "<?php echo $root_dir.'hotel/'; ?>";
			</script>
		<?php } ?>
    </div>
    <div class="clearfix"></div>



		<div class="col-md-12 col-xs-12" style="margin-top: 5%;">
		<div class="col-md-4 col-xs-4"></div>
		<div class="col-md-4 col-xs-4">
			<div class="col-md-6 col-xs-6 center"><a href="<?php echo (isset($hotel_url) ? $hotel_url : '#'); ?>" style="color:red;font-size:11px;">Go back to Home!</a></div>
			<?php if(isset($_REQUEST['status']) && $_REQUEST['status'] == 'success'){ ?>
			<div class="col-md-6 col-xs-6 center"><a href="javascript:void(0);" style="color:red;font-size:11px;" class="print-voucher">Print Voucher</a></div>
			<?php } ?>
		</div>
		<div class="col-md-4 col-xs-4"></div>
	</div>  

	<div  class="col-sm-12 vals"  style="display: none;"  >
		<div   id="print_full" ><?php echo $getBookingDetails[0]['voucher_html']; ?></div>
	</div>

	
</div>

<script>
    $(document).ready(function(e) {
        $(".print-voucher").click(function(e) {
            $("#print_full").printThis({
                debug: false, // * show the iframe for debugging
                importCSS: true, // * import page CSS
                printContainer: true, // *grab outer container as well as the contents of the selector
                loadCSS: "../../css/style.css", //* path to additional css file
                pageTitle: "", //*add title to print page
                 removeInline: false, //*remove all inline styles from print elements
                printDelay: 333, //*variable print delay
                header: null //*prefix to html


            });


        });
    });
</script>

<style>





</style>
<?php include('../../../include/footer.php'); ?>