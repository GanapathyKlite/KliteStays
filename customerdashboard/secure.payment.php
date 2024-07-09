<?php
	if(isset($_REQUEST['mihpayid']) && !empty($_REQUEST['mihpayid'])){
		session_start();
		include('../include/database/config.php');
		
		$transExists = $database->query("select id from ps_payu_transactions where mihpayid='".$_REQUEST['mihpayid']."'")->fetchAll();
		if(!isset($transExists[0][0]) && empty($transExists[0][0])){
			if($_REQUEST['status'] == 'success'){
				$bt_reference = 'PTI'.mt_rand(10000000, 99999999);
				$values = array(
					'mihpayid' => (isset($_REQUEST['mihpayid']) ? $_REQUEST['mihpayid'] : ''),
					'id_customer' => (isset($_SESSION['authtnid']) ? $_SESSION['authtnid'] : ''),
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

				$_SESSION['lastRechargeAmt'] = $_REQUEST['amount'];
				$previousBalanceArr = $database->query("select available_balance from ps_customers where id_customer=".$_SESSION['authtnid'])->fetchAll();	
				$previous_balance = (isset($previousBalanceArr[0]['available_balance']) ? $previousBalanceArr[0]['available_balance'] : '');
				$database->exec('update ps_customers set available_balance = available_balance + '.$_REQUEST['amount'].' where id_customer='.$_SESSION['authtnid'].' limit 1');
				$currentBalanceArr = $database->query("select available_balance from ps_customers where id_customer=".$_SESSION['authtnid'])->fetchAll();	
				$current_balance = (isset($currentBalanceArr[0]['available_balance']) ? $currentBalanceArr[0]['available_balance'] : '');
				$database->insert('ps_payu_transactions', array_merge($values,array('previous_balance' => $previous_balance, 'current_balance' => $current_balance)));
			}
		}
		header("Location: index.php");
		exit;
	}
	include('include/header.php');
	//if(isset($_SESSION['authtnid']) && !empty($_SESSION['authtnid']))
		//$customer_details=$database->query("select * from customers where id=".$_SESSION['authtnid'])->fetchAll();
	//echo '<pre>'; print_r($customer_details); echo '</pre>'; die();
	if(isset($PayUMessage) && !empty($PayUMessage)){
		?>
			<div id="page-wrapper">
				<div class="graphs">
		<?php
		if(strtolower(trim($PayUMessage)) == 'success'){ ?>
					<div class="payment-success">
						<p>Thank you for your process. Your account has been recharged with the amount Rs. <?php echo (isset($_REQUEST['amount']) && !empty($_REQUEST['amount']) ? $_REQUEST['amount'] : ''); ?> successfully.
						</p>
					</div>
		<?php 
			$database->exec('update ps_customers set available_balance = available_balance + '.$_REQUEST['amount'].' where id_customer='.$_SESSION['authtnid']);
			header("Location: payment_success.php");
		}else{ ?>
					<div class="payment-failure">
						<p><?php echo $PayUMessage; ?></p>
					</div>
		<?php } ?>
				</div>
			</div>
	<?php }else{ ?>
<style>
	.error{
		display : none;
	}
</style>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.10/jquery.mask.js"></script>
<script>
	$( document ).ready(function() {
		var flagSet = 0;
		if(flagSet == 0){
			$('#default_credit').css('background','rgba(0,0,0,0)');
			$('#default_credit').css('color','#DB0B0B');
			$('#default_credit').css('box-shadow','inset 0 0 0');
			flagSet++;
		}
		$('.credit_tab_inner li').click(function () {
			$('#default_credit').attr('style','');
			//$('#payment-details')[0].reset();
			$('.error').hide();
			if($(this).text() == 'Credit Card'){
				$('.details_block ').hide();
				$('.credit_block ').show();
			}else if($(this).text() == 'Debit Card'){
				$('.details_block ').hide();
				$('.debit_block ').show();
			}else if($(this).text() == 'Net Banking'){
				$('.details_block ').hide();
				$('.net_banking_block ').show();
			}
		});
	});
	function calculateHash(type){
		$('.error').hide();
		var flag = validatePaymentForm(type);
		if(!flag) return false;
		if(type == 'CC')
			var pgVal = $('#ccard-type').val();//$('input[name=ccard-type]:checked').val();
		else if(type == 'DC')
			var pgVal = $('#debit_card_select').val();
		else
			var pgVal = $('#netbanking_select').val();
		$('#pg').val(type);
		$('#bankcode').val(pgVal);
		
		if(type == 'CC' || type == 'DC'){
			if(type == 'CC'){
				var ccname = $('#cc_card_name').val();
				var ccnum = $('#cc_card_number').val();
				var ccvv = $('#cc_cvv_number').val();
				var ccexpmon = $('#cc_expiry_date_month').val();
				var ccexpyr = $('#cc_expiry_date_year').val();
			}else{
				var ccname = $('#dc_card_name').val();
				var ccnum = $('#dc_card_number').val();
				var ccvv = $('#dc_cvv_number').val();
				var ccexpmon = $('#dc_expiry_date_month').val();
				var ccexpyr = $('#dc_expiry_date_year').val();
			}
			if($("input[name='ccname']").length <= 0)
				$('.payment_form').append('<input type="hidden" name="ccname" value="'+ccname+'" />');
			else
				$("input[name='ccname']").val(ccname);
			if($("input[name='ccnum']").length <= 0)
				$('.payment_form').append('<input type="hidden" name="ccnum" value="'+ccnum+'" />');
			else
				$("input[name='ccnum']").val(ccnum);
			if(type != 'DC' || (type == 'DC' && $('.switch_cvv_exp').css('display') == 'block')){
				if($("input[name='ccvv']").length <= 0)
					$('.payment_form').append('<input type="hidden" name="ccvv" value="'+ccvv+'" />');
				else
					$("input[name='ccvv']").val(ccvv);
				if($("input[name='ccexpmon']").length <= 0)
					$('.payment_form').append('<input type="hidden" name="ccexpmon" value="'+ccexpmon+'" />');
				else
					$("input[name='ccexpmon']").val(ccexpmon);
				if($("input[name='ccexpyr']").length <= 0)
					$('.payment_form').append('<input type="hidden" name="ccexpyr" value="'+ccexpyr+'" />');
				else
					$("input[name='ccexpyr']").val(ccexpyr);
			}else{
				if($("input[name='ccvv']").length > 0)
					$("input[name='ccvv']").remove();
				if($("input[name='ccexpmon']").length > 0)
					$("input[name='ccexpmon']").remove();
				if($("input[name='ccexpyr']").length > 0)
					$("input[name='ccexpyr']").remove();
			}
		}else{
			if($("input[name='ccname']").length > 0)
				$("input[name='ccname']").remove();
			if($("input[name='ccnum']").length > 0)
				$("input[name='ccnum']").remove();
			if($("input[name='ccvv']").length > 0)
				$("input[name='ccvv']").remove();
			if($("input[name='ccexpmon']").length > 0)
				$("input[name='ccexpmon']").remove();
			if($("input[name='ccexpyr']").length > 0)
				$("input[name='ccexpyr']").remove();
		}
		
		var key = $('#key').val();
		var txnid = $('#txnid').val();
		var amount = $('#amount').val();
		var productinfo = $('#productinfo').val();
		var firstname = $('#firstname').val();
		var email = $('#email').val();
		var salt = $('#salt').val();

		//var additional_charges = $('#additional_charges').val();
		//var hashString = key+'|'+txnid+'|'+amount+'|'+productinfo+'|'+firstname+'|'+email+'|||||||||||'+salt;//+'|'+additional_charges;
		var hashString = 'gtKFFx|CAR00000047|1100|Car Booking|Lavanya|lavanya@buddiestechnologies.com|||||||||||eCwWELxi';
		$.ajax({
			url: "<?php echo $root_dir; ?>customerdashboard/recharge.php?getHash=1",
			data: 'hashString='+hashString,
			type: "POST",
			async: false,
			success: function(result){
				if($.trim(result) != '' && result)
					$('#hash').val(result);
			}}
		);
		
		//$(".payment_form").submit();
	}
	function validatePaymentForm(type){
		var flag = 0;
		if(type == 'CC'){
			alert($('#ccard-type').val());
			if(!$('#ccard-type').val()){
				$('.span_ccctype').show();
				flag = 1;
			}
			var ccnum = $('#cc_card_number').val();
			var ccvv = $('#cc_cvv_number').val();
			var ccexpmon = $('#cc_expiry_date_month').val();
			var ccexpyr = $('#cc_expiry_date_year').val();
		}else if(type == 'DC'){
			if(!$('#debit_card_select').val()){
				$('.span_dcctype').show();
				flag = 1;
			}
			var ccnum = $('#dc_card_number').val();
			var ccvv = $('#dc_cvv_number').val();
			var ccexpmon = $('#dc_expiry_date_month').val();
			var ccexpyr = $('#dc_expiry_date_year').val();
		}else if(type == 'NB'){
			var select_bank = $('#netbanking_select').val();
			if(!select_bank){
				$('.span_nbbank').show();
				flag = 1;
			}
		}
		if(ccnum == ''){
			$('.span_ccnum').show();
			flag = 1;
		}
		if(type != 'DC' || (type == 'DC' && $('.switch_cvv_exp').css('display') == 'block')){
			if(ccvv == ''){
				$('.span_ccvv').show();
				flag = 1;
			}
			if(ccexpmon == ''){
				$('.span_ccexpmon').show();
				flag = 1;
			}else if(ccexpyr == ''){
				$('.span_ccexpyr').show();
				flag = 1;
			}
		}
		if(flag == 1) return 0;
		else return 1;
	}
	function select_debitcard(type){
		if(type == 'SMAE'){
			$('.enableDisableCvvExp').hide();
			$('#enableCvvExpDivSBI').show();
			$('.switch_cvv_exp').hide();
		}else if(type == 'MAES'){
			$('.enableDisableCvvExp').hide();
			$('#disableCvvExpDiv').show();
			$('.switch_cvv_exp').show();
		}
	}
	function changeAmountDisplay(type){
		if(type == 'nb'){
			$('.trans_charge').html('(Transaction charge incl. Rs.10)');
			var amt = $('#amount_actual').val();
			$('.display_amount').html(parseInt(amt)+10);
			$('#amount').val(parseInt(amt)+10);
		}
	}
</script>	

<div class="secure_fullpage" >        
<div class="container">


		<form method="post" action="https://test.payu.in/_payment" name="payment_form" class="payment_form"><!--https://secure.payu.in/_payment-->
		<?php
		if(isset($_SESSION['authtnid']) && !empty($_SESSION['authtnid']))
		$customer_details=$database->query("select * from ps_customers where id_customer=".$_SESSION['authtnid'])->fetchAll();

		$MERCHANT_KEY = "gtKFFx";
		$SALT = "eCwWELxi";
		/*$MERCHANT_KEY = "nSuvko";
		$SALT = "ZuoX1Zt1";*/
		$txnid = substr(hash('sha256', mt_rand() . microtime()), 0, 20);
		?>
		<input type="hidden" name="firstname" id="firstname" value="<?php echo $_SESSION['username']; ?>" />
		<input type="hidden" name="surl" value="<?php echo (isset($_POST['surl']) && !empty($_POST['surl']) ? $_POST['surl'] : $root_dir.'customerdashboard/secure.payment.php');?>" />
		<input type="hidden" name="phone" id="phone" value="<?php echo (isset($customer_details[0]['mobile']) ? $customer_details[0]['mobile'] : ''); ?>" />
		<input type="hidden" name="key" id="key" value="<?php echo $MERCHANT_KEY; ?>" />
		<input type="hidden" id="salt" value="<?php echo $SALT; ?>" />
		<input type="hidden" name="hash" id="hash" value="" />
		<input type="hidden" name="curl" value="<?php echo (isset($_POST['curl']) && !empty($_POST['curl']) ? $_POST['curl'] : $root_dir.'customerdashboard/secure.payment.php');?>" />
		<input type="hidden" name="furl" value="<?php echo (isset($_POST['curl']) && !empty($_POST['curl']) ? $_POST['curl'] : $root_dir.'customerdashboard/secure.payment.php');?>" />
		<input type="hidden" name="txnid" id="txnid" value="<?php echo $txnid; ?>" />
		<input type="hidden" name="productinfo" id="productinfo" value="<?php echo (isset($_POST['productinfo']) && !empty($_POST['productinfo']) ? $_POST['productinfo'] : 'Recharge'); ?>" />
		<input type="hidden" name="amount" id="amount" value="<?php echo (isset($_POST['amount']) && !empty($_POST['amount']) ? $_POST['amount'] : ''); ?>" />
		<?php if(isset($_POST['amount']) && !empty($_POST['amount'])){
				$additional_charges = 'CC:'.($_POST['amount']*0.025); // 2.5%
				if($_POST['amount'] <= 2000)
					$additional_charges .= ',DC:'.($_POST['amount']*0.0075); // 0.75%
				else
					$additional_charges .= ',DC:'.($_POST['amount']*0.01); // 1%
				$additional_charges .= ',NB:20';
		} ?>
		<!--<input type="hidden" name="additional_charges" id="additional_charges" value="<?php //echo $additional_charges; ?>" />-->
		<input type="hidden" name="email" id="email" value="<?php echo 'kiruba@buddiestechnologies.com';//$customer_details[0]['emailid']; ?>" />
		<input type="hidden" name="pg" id="pg" value="CC">
		<input type="hidden" name="bankcode" id="bankcode">
		</form>
		<input type="hidden" name="amount_actual" id="amount_actual" value="<?php echo (isset($_POST['amount']) && !empty($_POST['amount']) ? $_POST['amount'] : ''); ?>" />


<DIV CLASS="ROW">
<div class="col-md-3"></div>

<div class="col-md-6 secure_full_page" >
<div class="heading" >

<div class="col-md-12 amount">
	
	
	<p>Amount: Rs. <span class="display_amount"><?php echo (isset($_POST['amount']) && !empty($_POST['amount']) ? $_POST['amount'] : ''); ?></span></p>
	<p style="font-size: 11px;padding-top:0;" class="trans_charge">(Transaction charge incl. Rs. 1%)</p>
	<!--(Transaction charges for Credit Card: 2.5%, Debit Card: <?php //echo ($_POST['amount'] <= 2000 ? '0.75%' : '1%'); ?>, NetBanking: Rs.10)-->
</div>
				
				
			
				
				
				<div class="col-md-12 payment" >
				<div class="col-md-8 amount_inner "  >
				<p class="payment_met">Choose a payment method </p>
				</div>
				<div class="col-md-4 image_inner" ><img src="images/pays.png" class="img-responsive images" width="150px" height="50px" ></div>
				</div>
				</div>



					<div class="col-md-3 card_menu" >
				
				<ul class="nav nav-tabs credit_tab_inner" style="text-align:center;">
				<li class="active"><a href="#a" data-toggle="tab" onclick="javascript:$('.trans_charge').html('(Transaction charge incl. Rs. 2.5%)');">Credit Card</a></li>
				<li><a href="#b" data-toggle="tab" onclick="javascript:$('.trans_charge').html('(Transaction charge incl. Rs. 1%)');">Debit Card</a></li>
				<li><a href="#c" data-toggle="tab" onclick="javascript:changeAmountDisplay('nb');">Net Banking</a></li>
				</ul>
				
				</div>





				<div class="col-md-9 center_content"  >
				<div class="tab-content">
				<div class="tab-pane active" id="a">
						<div class="details_block credit_block" >
							<div class="col-md-12">
								<label class="col-md-4 ">Card Type</label>
							<div class="col-md-8">

								<select class=" form-control required valid" id="ccard-type" name="ccard-type" >
								<option selected="selected" value="">Select Card Type</option>
								<option value="CC">Visa / Masters</option>
								<option value="AMEX">Pay American</option>
								<option value="DINR">Pay Diners</option>
								</select>
								<span class="error span_ccctype error_script">Please select Card Type</span>
							</div>
							</div>
							<div class="col-md-12">


								<label  class="col-md-4">Card Number</label>
								<div class="col-md-8"><input class="form-control"  type="Text" name="cc_card_number" id="cc_card_number"><span class="error span_ccnum error_script">Please enter card number</span>
								</div>
							</div>
						<div class="col-md-12">
						<label class="col-md-4">Name on Card</label>
						<div class="col-md-8">
						<input  class="form-control"  type="Text" name="cc_card_name" id="cc_card_name">
						</div>
						</div>
						<div class="col-md-12">
						<label class="col-md-4">CVV Number</label>
						<div class="col-md-8"><input  class="form-control"  type="Text" name="cc_cvv_number" id="cc_cvv_number" maxlength="3"><span  class="error span_ccvv error_script">Please enter cvv number</span></div>
						</div>
						<div class="col-md-12">

						<label class="col-md-4">Expiry Date</label>

						<div class="col-md-4 months">
						<select   class="form-control "id="cc_expiry_date_month" name="cc_expiry_date_month">
						<option value="">Month</option>
						<option value="01">Jan (1)</option>
						<option value="02">Feb (2)</option>
						<option value="03">Mar (3)</option>
						<option value="04">Apr (4)</option>
						<option value="05">May (5)</option>
						<option value="06">Jun (6)</option>
						<option value="07">Jul (7)</option>
						<option value="08">Aug (8)</option>
						<option value="09">Sep (9)</option>
						<option value="10">Oct (10)</option>
						<option value="11">Nov (11)</option>
						<option value="12">Dec (12)</option>
						</select>
						<span class="error span_ccexpmon error_script ">Please Select Month</span>
						
						</div>



						<div class="col-md-4">
						<select  class="form-control " id="cc_expiry_date_year" name="cc_expiry_date_year">
						<option value="">Year</option>
						<?php 
						$currentYear = date('Y'); $toYear = $currentYear+50;
						for($i=$currentYear;$i<=$toYear;$i++)
						echo '<option value="'.$i.'">'.$i.'</option>';
						?>
						</select>
						<span  class="error span_ccexpyr error_script">Please Select year</span> 



						
						</div>

						

						</div>
					
						 
					
						<div class="col-md-12 notes">



						<p>Note: In the next step you will be redirected to your bank's website to verify yourself.</p>

						</div>

						<div class="col-md-12">

						<div class="col-md-4 col-md-offset-8">

						<input  type="button"  name="pay_button" value="Pay Now" onclick="calculateHash('CC');" class=" btn credit_pay_button" id="pay_button">
						</div>
						</div>
						<div class="col-md-4"></div>


						</div>
				</div>

				<div class="tab-pane" id="b">
						<div class="details_block debit_block" >
								<div class="col-md-12">
								<label class="col-md-4 "> Card Type</label>
								<div class="col-md-8">

								<select  class=" form-control required valid" id="debit_card_select" name="debit_card_select" onchange="select_debitcard(this.value);" style="">
								<option value="" selected="selected" value="">Select Card Type</option>
								<option value="VISA">Visa Cards</option>
								<option value="MAST">MasterCard</option>
								<option value="mdAE">SBI Maestro</option>
								<option value="MAES">Other Maestro</option>										
								</select>
								<span class="error span_dcctype error_script">Please select Card Type</span>
								</div>
								</div>
								<div class="col-md-12">


								<label  class="col-md-4">Card Number</label>
								<div class="col-md-8"><input class="form-control"  type="Text" name="dc_card_number" id="dc_card_number"><span style="color:red;font-size:10px; "class="error span_ccnum error_script">Please enter card number</span></div>
								</div>
								<div class="col-md-12">
								<label class="col-md-4">Name on Card</label>
								<div class="col-md-8">
								<input class="form-control"   type="Text" name="dc_card_name" id="dc_card_name">
								</div>
								</div>
								<div class="col-md-12">
								<label class="col-md-4">CVV Number</label>
								<div class="col-md-8">
								<input  class="form-control"  type="Text" name="dc_cvv_number" id="dc_cvv_number" maxlength="3"><span  class="error span_ccvv error_script">Please enter cvv number</span>
								</div>
								</div>
								<div class="col-md-12">

								<label class="col-md-4">Expiry Date</label>

								<div class="col-md-4 months">
								<select  class="form-control"  id="dc_expiry_date_month" name="dc_expiry_date_month">
								<option value="">Month</option>
								<option value="01">Jan (1)</option>
								<option value="02">Feb (2)</option>
								<option value="03">Mar (3)</option>
								<option value="04">Apr (4)</option>
								<option value="05">May (5)</option>
								<option value="06">Jun (6)</option>
								<option value="07">Jul (7)</option>
								<option value="08">Aug (8)</option>
								<option value="09">Sep (9)</option>
								<option value="10">Oct (10)</option>
								<option value="11">Nov (11)</option>
								<option value="12">Dec (12)</option>
								</select>
								</div>



								<div class="col-md-4">
								<select   class="form-control"  id="dc_expiry_date_year" name="dc_expiry_date_year">
								<option value="">Year</option>
								<?php 
								$currentYear = date('Y'); $toYear = $currentYear+50;
								for($i=$currentYear;$i<=$toYear;$i++)
								echo '<option value="'.$i.'">'.$i.'</option>';
								?>
								</select>
								<span class="error span_ccexpmon error_script">Please enter date</span><span class="error span_ccexpyr error_script">Please enter year</span>
								</div>
								
								</div> 
								
								<div id="enableDisableDiv">
								<div class="enableDisableCvvExp row" id="enableCvvExpDiv" style="display: none;" onclick="$('.enableDisableCvvExp').hide();$('#disableCvvExpDiv').show();$('.switch_cvv_exp').show();">
								<a href="javascript:void(0);"><b>Undo</b> if you do have a CVV number and expiry date on card</a>
								</div>
								<div class="enableDisableCvvExp row" id="disableCvvExpDiv" style="display: none;" onclick="$('.enableDisableCvvExp').hide();$('#enableCvvExpDiv').show();$('.switch_cvv_exp').hide();">
								<a href="javascript:void(0);"><b>Click here </b> <span class="dot">.</span> I don't have a CVV number and expiry date on card</a>
								</div>
								<div class="enableDisableCvvExp row" id="enableCvvExpDivSBI" style="display: none;" onclick="$('.enableDisableCvvExp').hide();$('#disableCvvExpDivSBI').show();$('.switch_cvv_exp').show();">
								<a href="javascript:void(0);"><b>Click here</b> if you do have a CVV number and expiry date on card</a>
								</div>
								<div class="enableDisableCvvExp row" id="disableCvvExpDivSBI" style="display: none;" onclick="$('.enableDisableCvvExp').hide();$('#enableCvvExpDivSBI').show();$('.switch_cvv_exp').hide();">
								<a href="javascript:void(0);"><b>Undo </b> <span class="dot">.</span> I don't have a CVV number and expiry date on card</a>
								</div>
								</div>
								<div class="col-md-12 notes">



								<p>Note: In the next step you will be redirected to your bank's website to verify yourself.</p>

								</div>

								<div class="col-md-12">

								<div class="col-md-4 col-md-offset-8">

								<input type="button" value="Pay Now" onclick="calculateHash('DC');" class=" btn debit_pay_button"></div>
								</div>
								<div class="col-md-4"></div>


						</div>
				</div>
				
				<div class="tab-pane" id="c">
						<div class="details_block net_banking_block" >
								<div class="col-md-12">
										<label class="col-md-12 ">or select any other bank:</label>
								</div>
								<div class="col-md-12">

										<select class="form-control frm"id="netbanking_select" name="netbanking_select">
										<option  value="" selected="selected" gateway_status="1">Select
										Bank
										</option>
										<option value="ADBB">Andhra Bank</option>
										<option value="AXIB">AXIS Bank NetBanking</option>
										<option value="BOIB">Bank of India</option>
										<option value="BOMB">Bank of Maharashtra</option>
										<option value="CABB">Canara Bank</option>
										<option value="CSBN">Catholic Syrian Bank</option>
										<option value="CBIB">Central Bank Of India</option>
										<option value="CITIRDR">Citi Netbanking</option>
										<option value="CITNB">Citibank Netbanking</option>
										<option value="CUBB">City Union Bank</option>
										<option value="CRPB">Corporation Bank</option>
										<option value="CSMSNB">Cosmos Bank</option>
										<option value="DCBCORP">DCB Bank - Corporate Netbanking</option>
										<option value="DCBB">DCB Bank Limited</option>
										<option value="DENN">Dena Bank</option>
										<option value="DSHB">Deutsche Bank</option>
										<option value="DLSB">Dhanlaxmi Bank</option>
										<option value="FEDB">Federal Bank</option>
										<option value="HDFB">HDFC Bank</option>
										<option value="ICIB">ICICI Netbanking</option>
										<option value="IDBB">IDBI Bank</option>
										<option value="INDB">Indian Bank</option>
										<option value="INOB">Indian Overseas Bank</option>
										<option value="INIB">IndusInd Bank</option>
										<option value="JAKB">Jammu and Kashmir Bank</option>
										<option value="JSBNB">Janata Sahakari Bank Pune</option>
										<option value="KRKB">Karnataka Bank</option>
										<option value="KRVBC">Karur Vysya - Corporate Netbanking</option>
										<option value="KRVB">Karur Vysya - Retail Netbanking</option>
										<option value="162B">Kotak Mahindra Bank</option>
										<option value="LVCB">Lakshmi Vilas Bank - Corporate Netbanking</option>
										<option value="LVRB">Lakshmi Vilas Bank - Retail Netbanking</option>
										<option value="OBCB">Oriental Bank of Commerce</option>
										<option value="PMNB">Punjab And Maharashtra Co-operative Bank Limited</option>
										<option value="PSBNB">Punjab And Sind Bank</option>
										<option value="CPNB">Punjab National Bank - Corporate Banking</option>
										<option value="PNBB">Punjab National Bank - Retail Banking</option>
										<option value="SRSWT">Saraswat Bank</option>
										<option value="SVCNB">Shamrao Vithal Co-operative Bank Ltd.</option>
										<option value="SOIB">South Indian Bank</option>
										<option value="SBBJB">State Bank of Bikaner and Jaipur</option>
										<option value="SBHB">State Bank of Hyderabad</option>
										<option value="SBIB">State Bank of India</option>
										<option value="SBMB">State Bank of Mysore</option>
										<option value="SBPB">State Bank of Patiala</option>
										<option value="SBTB">State Bank of Travancore</option>
										<option value="SYNDB">Syndicate Bank</option>
										<option value="TMBB">Tamilnad Mercantile Bank</option>
										<option value="UCOB">UCO Bank</option>
										<option value="UBIBC">Union Bank - Corporate Netbanking</option>
										<option value="UBIB">Union Bank - Retail Netbanking</option>
										<option value="UNIB">United Bank Of India</option>
										<option value="VJYB">Vijaya Bank</option>
										<option value="YESB">Yes Bank</option>
										<option value="TBON">Nainital Bank</option>
										</select>
										<span class="error span_nbbank error_script">Please choose bank</span>
								</div>
						</div>

						<div class="col-md-12 notes">



						<p>Note: In the next step you will be redirected to your bank's website to verify yourself.</p>

						</div>

						<div class="col-md-12">

						<div class="col-md-4 col-md-offset-8">

						<input type="button" id="npay_button" class="btn debit_pay_button" value="Pay Now" onclick="calculateHash('NB');"></div>
						</div>
				
				</div>
			</div>
			</div>




</div>


</div>
</div>
</div>

	<div class="clearfix"> </div>
	<script>
		$("#cc_card_number").mask("0000 0000 0000 0000");
		$("#dc_card_number").mask("0000 0000 0000 0000");
		$('#cc_cvv_number').mask("000");
		$('#dc_cvv_number').mask("000");
	</script>

<?php }include('include/footer.php')?>
		
		





