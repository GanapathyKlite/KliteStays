
<?php
	$currentpage="busconfirmation";
	include("../../../include/header.php");
	$bookingRef = $_GET['bookingRef'];
	if(isset($bookingRef) && !empty($bookingRef))
		$bookingDetails = $database->query("select * from ps_hotel_booking where booking_reference = '".$bookingRef."'")->fetchAll();
	
	$propertyDetails = $database_hotel->query('select p.*,r.id_room,r.txtRoomName,r.selNoOfBeds,r.selRoomBedSize,rt.id_room_type,c.name as cityName,s.name as stateName from ps_property p left join ps_room r on(p.id_property = r.id_property) left join ps_room_type rt on(r.id_room = rt.id_room) left join ps_state s on(s.id_state = p.selStateId) left join ps_city c on(c.id_city = p.selCityId) where p.id_property = '.$bookingDetails[0]['id_property'].' and r.id_room='.$bookingDetails[0]['room_id'].' and rt.id_room_type='.$bookingDetails[0]['room_type_id'])->fetchAll();
	
?>


<link href="print.css" rel="stylesheet" type="text/css">

<div class="container-fluid nopadding"  >
<div class="container"    >

<div style="width:75%;margin:10px auto 0px auto;">
	
<p >
<a style="color:#555;" href="<?php echo $root_dir; ?>hotels.php" >Hotel</a><i style="margin:0px 10px;" class="fa fa-angle-double-right" aria-hidden="true"></i>
Voucher
<i style="float: right;font-size: 24px!important;"   class="fa fa-print"></i>


 </div>	
	
</div>
</div>
<div class="container   padding_topbot_20 " id="print_full"  >
<div class="row hotel_vouchers" >

		
		
		<div class="col-sm-8">

		<h3> Service Confirmation Voucher</h3>
		</div>
		<div class="col-sm-4" style="text-align: right;">
		<h5><?php echo $_SESSION['agentname'];?></h5>
		</div>
			<div class="clearfix"></div>
		<div class="col-sm-12">
		<p>BTT Booking ID - <?php echo $bookingDetails[0]['booking_reference']; ?></p>
		<p>Booking Date - <?php echo date('D, d M Y', strtotime($bookingDetails[0]['date_add'])); ?></p>
		<hr style="margin:10px 0;">
		
		<h5>Dear <?php echo ucwords($bookingDetails[0]['customer_firstname']).' '.ucwords($bookingDetails[0]['customer_lastname']); ?></h5>
		
		
		<ul>
			<li>Thank you for using Buddies to book your hotel accommodation.</li>
			<li>For your reference, your BTT Booking ID is <?php echo $bookingDetails[0]['booking_reference'];?>.</li>
			<li>Kindly note, your booking is CONFIRMED and you are not required to contact the hotel or Buddies  to reconfirm the same.</li>
			<li>You will need to carry a printout of this e-mail and present it at the hotel at the time of check-in.</li>
			<li>We hope you have a pleasant stay and look forward to assisting you again!</li>
		</ul>
		<h4 class="hotel_detalis_underlin">Hotel details</h4>
		<h5><?php echo ucwords($propertyDetails[0]['txtPropertyName']); ?></h5>
		<ul>
			<li><?php echo (isset($propertyDetails[0]['txtAddress1']) && !empty($propertyDetails[0]['txtAddress1']) ? $propertyDetails[0]['txtAddress1'] : '').(isset($propertyDetails[0]['txtAddress2']) && !empty($propertyDetails[0]['txtAddress2']) ? ', '.$propertyDetails[0]['txtAddress2'] : ''); ?></li>
			<li><?php echo (isset($propertyDetails[0]['cityName']) && !empty($propertyDetails[0]['cityName']) ? $propertyDetails[0]['cityName'] : ''); ?></li>
			<li><?php echo (isset($propertyDetails[0]['stateName']) && !empty($propertyDetails[0]['stateName']) ? $propertyDetails[0]['stateName'] : ''); ?></li>
			<li>Contact No: <?php echo (isset($propertyDetails[0]['txtPhone']) && !empty($propertyDetails[0]['txtPhone']) ? 'Contact No:&nbsp;'.$propertyDetails[0]['txtPhone'] : ''); ?></li>
			<li>Mobile : <?php echo (isset($propertyDetails[0]['txtMobile']) && !empty($propertyDetails[0]['txtMobile']) ? 'Mobile:&nbsp;'.$propertyDetails[0]['txtMobile'] : ''); ?></li>
		</ul>
		<p><b>Primary Guest:</b> <?php echo strtoupper($bookingDetails[0]['customer_firstname']).' '.strtoupper($bookingDetails[0]['customer_lastname']); ?></p> 
		<p><Span style="color:#000;">No. of Rooms:</Span> <?php echo $propertyDetails[0]['room_count']; ?></p>
		</div>
		<?php
			$guest_json_arr = json_decode($bookingDetails[0]['guest_json'],true);
			foreach($guest_json_arr as $guestKey => $guestVal){
		?>
		
		
		<div class="col-sm-12" >
		<h5>Room <?php echo $guestKey; ?>:</h5>
		<div class="col-sm-12 back_table_full" style="border:1px solid #ddd!important;padding:0 0; " >
				<div class="col-xs-4 back_for_tabl">
				<p>Check in: <?php echo date('D, M d, Y', strtotime($bookingDetails[0]['check_in'])); ?></p>
				</div>
				<div class="col-xs-4 back_for_tabl">
				<p>Check out: <?php echo date('D, M d, Y', strtotime($bookingDetails[0]['check_out'])); ?></p>
				</div>
				<div class="col-xs-4 back_for_tabl">
				<p>No. of Night(s): <?php echo $bookingDetails[0]['no_of_nights']; ?></p>
				</div>
				</div>

				<div class="col-sm-12 back_table_full" style="border:1px solid #ddd!important;padding:0 0; " >

				<div class="col-xs-4 back_for_table">
				<p>Room Type,Category: <?php echo (isset($roomTypes[$bookingDetails[0]['room_type_id']]) ? $roomTypes[$bookingDetails[0]['room_type_id']] : '').(isset($propertyDetails[0]['txtRoomName']) ? ', '.$propertyDetails[0]['txtRoomName'] : ''); ?></p>
				</div>
				<div class="col-xs-4 back_for_table">
				<p>Guest: <?php echo $guestVal['\'adult\''].' Adult'.($guestVal['\'child\''] > 0 ? ', '.$guestVal['\'child\''].' Child' : ''); ?></p>
				</div>
				<div class="col-xs-4 back_for_table">
				<p>	Meal Plan: <?php echo ((int)$bookingDetails[0]['breakfast_incl'] == 1 ? 'Breakfast' : '').((int)$bookingDetails[0]['lunch_incl'] == 1 ? ((int)$bookingDetails[0]['breakfast_incl'] == 1 ? ', ' : '').'Lunch' : '').((int)$bookingDetails[0]['dinner_incl'] == 1 ? ((int)$bookingDetails[0]['breakfast_incl'] == 1 || (int)$bookingDetails[0]['lunch_incl'] == 1 ? ', ' : '').'Dinner' : ''); ?></p>
				</div>
				</div>
				</div>
			<?php } ?>

<div class="clearfix"></div>
				<div class="col-sm-12">		

			

				<h4 class="hotel_detalis_underlin" >General Hotel Policy</h4>




				<ul>
					<li>Early check-in or late check-out is subject to availability and may be chargeable by the hotel. To request for an early check-in or late check-out, kindly contact the hotel directly.</li><li>
As per Government of India rules, it is mandatory for all guests over the age of 18 years to present a valid photo identification (ID) on check-in. Without a valid ID, guests will not be allowed to check-in. The valid ID proofs accepted are Driving License, Passport, Voter ID Card and Ration Card. Kindly note, a PAN Card will not be accepted as a valid ID proof.</li><li>
The primary guest must be at least 18 years of age to check-in to this hotel. Ages of accompanying children, if any, must be between 1-12 years.</li><li>
The room tariff includes all taxes. The amount shown does not include charges for optional services and facilities (such as room service, mini bar, snacks or telephone calls). These will be charged as per actual usage and billed separately on check-out.
Entry to the hotel is at the sole discretion of the hotel authority. If the address on the photo identification card matches the city where the hotel is located, the hotel may refuse to provide accommodation. Buddies will not be responsible for any check-in denied by the hotel due to the aforesaid reasons.</li><li>
Kindly note, if a hotel booking includes an extra bed, most hotels provide a folding cot or a mattress as an extra bed.</li><li>
The hotel reserves the right of admission. Accommodation can be denied to guests posing as a couple if suitable proof of identification is not presented at check-in. Buddies will not be responsible for any check-in denied by the hotel due to the aforesaid reason.</li>
				</ul>

				<h4 class="hotel_detalis_underlin">Cancellation Policy
 
</h4>
<ul>
	<li>More than 3 days before check-in date: FREE CANCELLATION/MODIFICATION.
3 days before check-in date: no refund.</li><li>
In case of no show: no refund.Booking cannot be cancelled/modified on or after the check in date and time mentioned in the Hotel Confirmation Voucher.</li>
</ul>

		</div>
	

		
		</div>
		
	</div>

   
<script>
    $(document).ready(function(e) {
        $(".fa-print").click(function(e) {
            $("#print_full").printThis({
                debug: false, // * show the iframe for debugging
                importCSS: true, // * import page CSS
                printContainer: true, // * grab outer container as well as the contents of the selector
                loadCSS: "../../css/style.css", // * path to additional css file
                pageTitle: "", // * add title to print page
                removeInline: false, // * remove all inline styles from print elements
                printDelay: 333, // * variable print delay
                header: null // * prefix to html
            });
        });
    });
</script>
<?php include('../../../../include/footer.php');?>