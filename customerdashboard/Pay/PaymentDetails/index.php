<?php 
	include('../../../include/database/config.php');
	include('../../include/header.php');
?>	
        <div id="page-wrapper">
        <div class="graphs">
      <div class="xs">
  	       <h4>Make a payment</h4>
  	         <div class="tab-content ">
						<div class="tab-pane active" id="horizontal-form">
							<!--action="secure.payment.php"-->
						<form action="secure.payment.php" method="post" id="defaultForm1" class="form-horizontal" >
								<div class="paymm">
								<!--<div class="form-group">
							
							
									
								
									<div  class="col-md-6">
									<label class="pt">Pay With</label>
									<select name="selector1"  class="snet">
									<option>Debit Card/Credit Card</option>
									<option>Net Banking</option>
										
										
					
									</select>
								</div>
								
								
								</div>
								<p>Your payment will usually be credited within (30 Minutes)</p>-->
								
								<p style="color:black;">Specified Your payment accounts </p>
							
								<?php

									$get_last_transaction = $database->query("select amount from ps_payu_transactions where id_agent='".$_SESSION['authtnid']."' and status = 'success' order by addedon desc limit 0,1")->fetchAll();
									$lastamount = $get_last_transaction[0][0];
									//if(count($get_last_transaction)!=0)
									if($lastamount > 0)
									{
										/*$amountcalc=$get_last_transaction[0]['amount']*100-($get_last_transaction[0]['amount']*1.8);
								$amount=$amountcalc/100;
								$c=intval($c)+intval($amount);*/
								?>
								
								<p><input type="radio" name="radio1" id="lastamount"><ins>Rs.<?php echo $lastamount; ?>(Last Payment)</ins></p>
								<input type="hidden" id="lastamountval" value="<?php echo $lastamount; ?>">
								<p><input type="radio" id="amount_opt" name="radio1"><ins>Other amount</ins><input type="text"  required name="amount" id="amount_pay"  placeholder="Rs.0.00" onkeypress='validate(event);' disabled></p>
									<?php }else{?>
										
									<p><input type="radio" id="amount_opt"><ins>Amount</ins><input type="text"  required name="amount" id="amount_pay"  placeholder="Rs.0.00" onkeypress='validate(event);' disabled></p>	
										
									<?php } ?>
									
									
									<button type="submit" class="btn cubtn" id="makepayment" disabled>Make a Payment</button>
									<button class="btn-default btn" type="button" onclick="reload_form();">Cancel</button>
							</div>
				
									
									
					<!--<input type="hidden" name="Title" value="PHP VPC 3-Party">
<input type="hidden" name="virtualPaymentClientURL" value="https://migs.mastercard.com.au/vpcpay">
<input type="hidden" name="vpc_Version" value="1">
<input type="hidden" name="vpc_Command" value="pay">
<input type="hidden" name="vpc_AccessCode" value="2E228DC0">
<input type="hidden" name="vpc_MerchTxnRef" id="merchantref" value="<?php echo $_SESSION['refid']; ?>" >
<input type="hidden" name="vpc_Merchant" value="BUDDIESTOUR">
<input type="hidden" name="vpc_OrderInfo" value="Package Advance Payment">
<input type="hidden" name="vpc_Amount" id="vpc_Amount" >
<input type="hidden" name="vpc_Locale" value="en">
<input type="hidden" name="vpc_ReturnURL" value="http://buddiestours.com/payment/migs/vpc_php_serverhost_dr.php">-->
<?php
	if(isset($_SESSION['authtnid']) && !empty($_SESSION['authtnid']))
		$agent_details=$database->query("select * from ps_agents where id_agent=".$_SESSION['authtnid'])->fetchAll();
	
	$MERCHANT_KEY = "gtKFFx";
	$SALT = "eCwWELxi";
	$txnid = substr(hash('sha256', mt_rand() . microtime()), 0, 20);
?>
<input type="hidden" name="firstname" id="firstname" value="<?php echo $_SESSION['username']; ?>" />
<input type="hidden" name="surl" value="http://<?php echo $_SERVER['HTTP_HOST']; ?>/agent/agentdashboard/secure.payment.php" />
<input type="hidden" name="phone" id="phone" value="<?php echo (isset($agent_details[0]['mobile']) ? $agent_details[0]['mobile'] : ''); ?>" />
<input type="hidden" name="key" id="key" value="<?php echo $MERCHANT_KEY; ?>" />
<input type="hidden" id="salt" value="<?php echo $SALT; ?>" />
<input type="hidden" name="hash" id="hash" value="" />
<input type="hidden" name="curl" value="http://<?php echo $_SERVER['HTTP_HOST']; ?>/agent/agentdashboard/secure.payment.php" />
<input type="hidden" name="furl" value="http://<?php echo $_SERVER['HTTP_HOST']; ?>/agent/agentdashboard/secure.payment.php" />
<input type="hidden" name="txnid" id="txnid" value="<?php echo $txnid; ?>" />
<input type="hidden" name="productinfo" id="productinfo" value="Recharge" />
<input type="hidden" name="amount" id="amount" value="" />
<input type="hidden" name="additional_charges" id="additional_charges" value="" />
<input type="hidden" name="email" id="email" value="<?php echo 'kirubakaran.it@buddiestours.com';//$agent_details[0]['emailid']; ?>" />
			
			
			</form>
		</div>					
     
	 <div class="clearfix"> </div>
</div>
</div>
   <?php include('../../include/footer.php')?>
    <script>
$('#amount_opt').click(function() {
   if($('#amount_opt').is(':checked')) { $("#amount_pay").attr("disabled",false); $("#makepayment").attr("disabled",false);}
});
$("#lastamount").click(function(){
	//alert(Math.round(2.4));
	 if($('#lastamount').is(':checked')) {
		 var amount2=$("#lastamountval").val();
		 amount1 = parseFloat(amount2).toFixed(2);
		 //$("#amount").val(amount2.toFixed());
		 $("#amount").val(parseFloat(amount1));
		 var cc = parseFloat(amount1)*0.0187;
		if(parseFloat(amount1) <= 2000)
			var dc = parseFloat(amount1)*0.0075;
		else if(parseFloat(amount1) > 2000)
			var dc = parseFloat(amount1)*0.01;
		additional_charges = 'CC:12,AMEX:19,SBIB:98,DINR:2,DC:25,NB:55';//'CC:'+parseInt(cc)+','+'DC:'+parseInt(dc)+','+'NB:20';
		$('#additional_charges').val(additional_charges);
		 calculateHash();
		 $("#makepayment").attr("disabled",false);
	}
});

	function validate(evt) {
  var theEvent = evt || window.event;
  var key = theEvent.keyCode || theEvent.which;
  key = String.fromCharCode( key );
  var regex = /[0-9\b]|\./;
  if( !regex.test(key) ) {
    theEvent.returnValue = false;
    if(theEvent.preventDefault) theEvent.preventDefault();
  }
}
$(document).ready(function(e) {
    
	$("#amount_pay").keyup(function(e) {
        var amount=$(this).val();
		var amount1=$(this).val();
		amount1 = parseFloat(amount1).toFixed(2);
		amount=amount*100+(amount*1.8);

		//alert(amount);
		//$("#amount").val(amount.toFixed());
		var cc = parseFloat(amount1)*0.0187;
		if(parseFloat(amount1) <= 2000)
			var dc = parseFloat(amount1)*0.0075;
		else if(parseFloat(amount1) > 2000)
			var dc = parseFloat(amount1)*0.01;
		additional_charges = 'CC:12,AMEX:19,SBIB:98,DINR:2,DC:25,NB:55';//'CC:'+parseInt(cc)+','+'DC:'+parseInt(dc)+','+'NB:20';
		$('#additional_charges').val(additional_charges);
		$("#amount").val(parseFloat(amount1));
		var a=$("#servicetax1").text(amount1);
		var b=$("#servicetax").text(amount1*1.8/100);
		$("#servicetax2").text(amount/100);
		calculateHash();
    });
	$("#pan_pay").keyup(function(e) {
        var bid=$(this).val();
		$("#merchantref").val(bid);
	});
});

function reload_form()
{
	
	window.location.reload();
}
function calculateHash(){
	var key = $('#key').val();
	var txnid = $('#txnid').val();
	var amount = $('#amount').val();
	var productinfo = $('#productinfo').val();
	var firstname = $('#firstname').val();
	var email = $('#email').val();
	var salt = $('#salt').val();
	var additional_charges = $('#additional_charges').val();
	var hashString = key+'|'+txnid+'|'+amount+'|'+productinfo+'|'+firstname+'|'+email+'|||||||||||'+salt+'|'+additional_charges;
	$.ajax({
		url: "recharge.php?getHash=1",
		data: 'hashString='+hashString,
		type: "POST",
		success: function(result){
			if($.trim(result) != '' && result)
				$('#hash').val(result);
		}}
	);
}
</script>
<script>
//$(".payment_form").validate();
</script>	
<!--Start of Zopim Live Chat Script -->
<script type="text/javascript">
window.$zopim||(function(d,s){var z=$zopim=function(c){z._.push(c)},$=z.s=
d.createElement(s),e=d.getElementsByTagName(s)[0];z.set=function(o){z.set.
_.push(o)};z._=[];z.set._=[];$.async=!0;$.setAttribute('charset','utf-8');
$.src='//v2.zopim.com/?1QkEwkuxE0ZXXPAx1k46Ymsdrgigmd51';z.t=+new Date;$.
type='text/javascript';e.parentNode.insertBefore($,e)})(document,'script');
</script>
   <!--<script>
	  
	   $('#defaultForm').bootstrapValidator({
            message: 'This value is not valid',
            //live: 'enabled',
            feedbackIcons: {
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
            },
            submitHandler: function(validator, form) {
               
				
				 update_agentupdtepasswwd();
				

            },
            fields: {
                username_e: {
					message: 'Field value is not valid',
                    validators: {
                        notEmpty: {
                            message: 'The field is required and can\'t be empty'
                        }
                    }
                },
                password_e: {
                  	message: 'Field value is not valid',
                    validators: {
                        notEmpty: {
                            message: 'The field is required and can\'t be empty'
                        },
						  stringLength: {
                            min: 6,
                            max: 12,
                            message: 'The field must contain 6 to 12 digits'
                        },
						
                    }
                },
                confirmpassword_e: {
                  	message: 'Field value is not valid',
                    validators: {
                        notEmpty: {
                            message: 'The field is required and can\'t be empty'
                        },
						identical: {
							field: 'password_e',
							message: 'The password does not match'
						}
                    }
                },
				emailid_e: {
					validators: {
						notEmpty: {
							message: 'The email address is required and can\'t be empty'
						},
						emailAddress: {
							message: 'The input is not a valid email address'
						}
					}
				},
                userrole_e: {
                  	message: 'Field value is not valid',
                    validators: {
                        notEmpty: {
                            message: 'The field is required and can\'t be empty'
                        }
                    }
                },
                mobileno_e: {
                    message: 'Field value is not valid',
                    validators: {
                        notEmpty: {
                            message: 'The field is required and can\'t be empty'
                        },
                        stringLength: {
                            min: 10,
                            max: 10,
                            message: 'The field must contain 10 digits'
                        },
                        regexp: {
                            regexp: /^[0-9 -]+$/,
                            message: 'The field can only consist of numbers'
                        }
                    }
                }
            }
        });
		
	   </script>
	   
	