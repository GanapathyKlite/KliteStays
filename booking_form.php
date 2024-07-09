<?php 

session_start();
//include '../config.php';
//include '../include/database/config.php';
$currentpage="hotelbooking";
include("include/header.php");
?>
<style type="text/css">

.Step{margin-top: 20px;}	
.headtop{    -webkit-box-shadow: 0 0 30px rgba(0, 0, 0, 0.1)!important;
    margin-top: 20px;}

   .no_1{background: #7b7778;
    border-radius: 20px;
    -webkit-border-radius: 20px;
    -moz-border-radius: 20px;
    padding: 7px 13px;
    font-size: 18px;
    color: #333;
    line-height: 1;
    color: #fff;}
.Step .texts{margin-left: 30px;    font-weight: 600;
    font-size: 18px;}
.marginB10{margin-top: 25px;}
.imgwid{max-height: 158px;
    max-width: 250px;
    margin-left: 65px;
}
.stars{display: inline-block;
    float: none;
    position: static;    margin-left: 7px;}
    .h3tt{padding-bottom: 0px;}

    .bkChkDetailsIn {
    background: #fff;
    padding-left: 20px;
    padding-right: 20px;
    padding-top: 10px;
    padding-bottom: 20px;
    margin-top: 10px;
    float: left;
    width: 100%;
    -webkit-box-shadow: 0 0 30px rgba(0, 0, 0, 0.1)!important;
}
.db {
    display: block;
}
.tickicon{padding-bottom: 15px;}
.stay_txt{padding-bottom: 7px;}
.acc_tick{margin-left: 15px;}
.colorz{color: green;}
.deluxe_txt{font-size: 16px; padding-top: 4px;}
.bkOfferTagWrap {
    position: absolute;
    right: 0;
    top: 5px;
}
.top_detail{color: #7b7778}
.bkOfferTagWrap .bkOfferTag {
    background:#7b7778;
    padding: 5px;
    color: #fff!important;
    font-size: 13px;
    line-height: 1;
    position: relative;
}
.bkOfferTagWrap .bkOfferTag .arrowLeft {
    width: 0;
    height: 0;
    border-top: 12px solid transparent;
    border-bottom: 12px solid transparent;
    border-right: 11px solid #7b7778;
    position: absolute;
    left: -10px;
    top: -1px;
}
.priceSpilt {
    width: 100%;
    float: left;
}
.priceSpilt ul {
    width: 100%;
    float: left;
    padding: 10px;
    padding-top: 10px;
    /*padding-bottom: 10px;*/
    -webkit-box-shadow: 0 0 1px 2px rgba(0,0,0,0.14);
    -moz-box-shadow: 0 0 1px 2px rgba(0,0,0,0.14);
    box-shadow: 0 0 1px 2px rgba(0,0,0,0.14);
}
.price_wrap{padding-top: 20px;}

.priceSpilt ul li {
    width: 100%;
    float: left;
    border-bottom: 1px solid #e6e6e6;
    padding: 9px 15px;
    color: #333;
}
.rupee_right{float: right;}
.ico13{color:#000000; font-weight: 400; font-size: 12px;}
.ico14{color:#000000; font-weight: 800; font-size: 14px;}
.ico15{color:#C40000; font-weight: 800; font-size: 14px; border-bottom: 0px!important}
.bor_bot{border-bottom: 1px solid #ccc;}
.pad5 {margin-left: 15px;}
.btntype{    width: 90px; margin-left: 12px;}
 .grey{margin:15px;}
.marg_top{margin-top:15px;}
.stepLabel div.selector{margin:0px!important; width: 160px!important; border-radius: 20px!important}

.marginB10 div.selector{margin:0px!important; border-radius: 20px!important}

.card_left div.selector{width: 315px!important;}
  textarea{  
  overflow:hidden;
  padding:10px;
  width:250px;
  border-radius: 35px!important;
  font-size:14px;
  box-shadow: none!important;
  display:block;
  border:6px solid #556677;
}
.form-control:focus{border:1px solid #ccc;}

.margP10{padding-bottom: 20px;}
.greyLt{font-size: 12px;}
.paybtn{float: right;padding-bottom: 20px;}
.tab-content{display: block!important;}

@media screen and (max-width: 699px) and (min-width: 320px) {

	.imgwid{margin: 0px!important;}
}

</style>


<body>
	<div class="container headtop">
		<div class="Step col-md-12 col-sm-12 col-xs-12">

<div>
		<span class="no_1">1</span>
			<span class="texts">Review your booking</span>
			<div class="clearfix"></div>
			</div>

<div class="marginB10">
			<div class="col-md-4 col-sm-5 col-xs-12 ">
			<img src="images/img1.jpg" class="imgwid"></div>

			<div class="col-md-8 col-sm-7 col-xs-12">
			<h3 class="h3tt">
                              Hotel Mahaveer <span class="stars">
                              <i class="material-icons"></i>
                              <i class="material-icons"></i>
                              <i class="material-icons"></i>
                              <i class="material-icons"></i>
                              </span> 
                             
                           </h3>
                           <span class="address map_marker_icon">
							  <i class="fa fa-map-marker" aria-hidden="true"></i><span class="txtlandmark"> Landmark, City, Country</span>
                             </span>
                             <p class="deluxe_txt">Deluxe Suite with Breakfast</p>
                             <p class="stay_txt">Your stay includes:</p>
                             <div class="tickicon">
                             <span class=""><i class="fa fa-check colorz" aria-hidden="true"></i></span> <span>Free Breakfast</span>
                             <span class="acc_tick"><i class="fa fa-check colorz" aria-hidden="true"></i></span> <span>Accommodation</span></div>

                          </div>

                          <div class="col-md-12 col-sm-12 col-xs-12">

							<div class="bkChkDetailsIn">
							<div class="col-md-5 col-sm-5 col-xs-12">
							<div class="col-md-6 col-sm-6 col-xs-6">
							<span class="top_detail db">Check-In</span>
							<span class=" db">Fri, 26 Jan 2018</span>
							<span id="checkin-time" class="db" style="display: none;">12:00 PM</span>
							</div>
							<div class="col-md-6 col-sm-6 col-xs-6">
							<span class="top_detail db">Check-Out</span>
							<span class=" db">Tue, 30 Jan 2018</span>
							<span id="checkout-time" class="db" style="display: none;">12:00 PM</span>
							</div>
							</div>
							<div class="col-md-3 col-sm-3 col-xs-12">
							<span class="top_detail lh1-5 db">2 Adults</span>
							<span class=" db">in 1 Room
							for 4 Nights
							</span>
							</div>
							<div class="col-md-4 col-sm-4 col-xs-12">

							<span class="db">Non Refundable</span>
							<a href="#">Booking &amp; Cancellation Policy</a>
							</div>
							</div>
							<div>
							</div>

						</div>

						<div id="roomCountMessage" class="bkOfferTagWrap">
						<div class="bkOfferTag fmtTooltip" style="cursor:auto;color:#dc5858"><span class="arrowLeft"></span>HURRY! - Book Your Hotels. ACT FAST!!</div>
						</div>

						
						<!-- price details --> 

						<div class="col-md-12 col-sm-12 col-xs-12 bor_bot">

						  			<div class="col-md-6 col-sm-6 col-xs-12"></div>

						  			<div class="col-md-6 col-sm-6 col-xs-12 price_wrap">
							<div class="priceSpilt">
						  <ul>
						  
						  		
							    	<li>
							            <div class="priceSplitBase">
							              <span class="ico13">Room Charges</span>
							            <span class="rupee_right">
							              <span class="ico13"><span><i class="fa fa-inr" aria-hidden="true"></i></span> 68000</span>
							            </span>
							          </div>
							        </li>
							    
						  	
						  		
							    	<li>
							            <div class="priceSplitBase">
							              <span class="ico13">Klitestays Discount</span>
							            <span class="rupee_right">
							              <span class="ico13"><span><i class="fa fa-inr" aria-hidden="true"></i></span> 17680</span>
							            </span>
							          </div>
							        </li>
							    
						  	
						  		
							    	<li>
							            <div class="priceSplitBase">
							              <span class="ico14" style="">SubTotal</span>
							            <span class="rupee_right">
							              <span class="ico14"><span><i class="fa fa-inr" aria-hidden="true"></i></span> 50320</span>
							            </span>
							          </div>
							        </li>
							    
						  	
						  		
							    	<li>
							            <div class="priceSplitBase">
							              <span class="ico13">GST on Room Charges</span>
							            <span class="rupee_right">
							              <span class="ico13"><span><i class="fa fa-inr" aria-hidden="true"></i></span> 19040</span>
							            </span>
							          </div>
							        </li>
							    
						  	
						  		
							    	<li>
							            <div class="priceSplitBase">
							              <span class="ico13">Grand Total</span>
							            <span class="rupee_right">
							              <span class="ico13" ><span><i class="fa fa-inr" aria-hidden="true"></i></span> 3400</span>
							            </span>
							          </div>
							        </li>
							    
						  	
						  		
							    	<li>
							            <div class="priceSplitBase">
							              <span class="ico14">Pay Now</span>
							            <span class="rupee_right">
							              <span class="ico14"><span><i class="fa fa-inr" aria-hidden="true"></i></span> 72760</span>
							            </span>
							          </div>
							        </li>
							    
						  	
						  		
							        <li style="border-bottom: none;">
							            <div class="priceBreakSave">
							              <span class="ico15"> Savings</span>
							            <span class="rupee_right">
							              <span class="ico15"><span><i class="fa fa-inr" aria-hidden="true"></i></span> 17680</span>
							            </span>
							          </div>
							        </li>
							    
						  </ul>
						  </div>
              <div class="col-sm-6">



<p>
    <input style="    width: 14px;
    position: relative;
    height: 16px;
    top: 4px;" type="checkbox" id="termscondi"><a style="color: #005bff;" class="custonlink termsandcondis">I Agree All Terms and Conditions</a></p>
</div>
						  </div>
					</div>
						
				<!-- /price details -->

<div class="Step col-md-12 col-sm-12 col-xs-12">


		<span class="no_1">2</span>
			<span class="texts"> Guest Details </span>
			<span class="ico14 pad5 ">- Or -</span>
			<span><input class="wysija-submit myinput_button wysija-submit-field btntype" type="submit" value="Sign In" ></span>
			<span class="">to speed up your booking process and use your GoCash</span>
			


			<div class="col-md-12 col-sm-12 col-xs-12 marginT10">
<div class="col-md-2 col-sm-3 col-xs-12 padT10 marginB5 stepLabel"><label class="ico14 grey">Guest Name </label></div>
<div class="col-md-10 col-sm-9 col-xs-12 pad0" style="padding: 0px;">
<div class="col-md-6 col-sm-7 col-xs-12" style="padding: 0px;">
<div class="col-md-3 col-sm-4 col-xs-3 pad0 marginB10">
<select class="form-control inputMedium" id="choose" name="choose" style="margin-bottom: 0px!important">
<option value="1" selected="selected">Mr</option>
<option value="2">Mrs</option>
<option value="3">Ms</option>
</select>
</div>
<div class="col-md-9 col-sm-8 col-xs-9 pad0 marginB10">
<input type="text" name="firstname1" id="firstname1" class="form-control inputMedium" placeholder="First Name" value="">
</div>
</div>
<div class="col-md-4 col-sm-5 col-xs-12 marginB10">
<input type="text" name="lastname1" id="lastname1" class="form-control inputMedium" placeholder="Last Name" value="">
</div>
</div>
</div>

<div class="col-md-12 col-sm-12 col-xs-12 marg_top">
<div class="col-md-2 col-sm-3 col-xs-12 padT10  marginB5 stepLabel">
<label class="ico14 grey">Email Address</label>
</div>

<div class="col-md-10 col-sm-9 col-xs-12 pad0">
<div class="col-md-6 col-sm-7 col-xs-12  posRel" style="padding: 0px">
<input type="text" id="email" name="email" value="" class="form-control inputMedium iconImg" placeholder="Enter Email">
<i class="icon-email iconEmailPos ico24 greyLt"></i>
</div>
<div class="col-md-6 col-sm-5 col-xs-12 mobdn">
<span class="greyLt">Your voucher will be sent to <br>this email address</span>
</div>
</div>
</div>

<div class="col-md-12 col-sm-12 col-xs-12">
<div class="col-md-2 col-sm-3 col-xs-12 padT10  marginB5 stepLabel">
<label class="ico14 grey">Mobile Number</label>
</div>
<div class="col-md-2 col-sm-6 col-xs-12 padT10  marginB5 stepLabel">

<select class="ico14 grey">Mobile Number
  <option value="1">India(+91)</option>
  <option value="2">India(+91)</option>
  <option value="3">India(+91)</option>
  <option value="3">India(+91)</option>

</select>
</div>
<div class="col-md-3 col-sm-6 col-xs-12">
<input type="text" id="number">
</div>
</div>


<div class="col-md-12 col-sm-12 col-xs-12">
<div class="col-md-2 col-sm-3 col-xs-12 padT10  marginB5 stepLabel">
<label class="ico14 grey"> Expected Time of Check In</label>
</div>
<div class="col-md-2 col-sm-6 col-xs-12 padT10  marginB5 stepLabel">

<select class="ico14 grey">
  <option value="1">I'dont know</option>
  <option value="2">12PM- 3AM</option>
  <option value="3">12PM- 3AM</option>
  <option value="3">12PM- 3AM</option>
  <option value="1">12PM- 3AM</option>  
  <option value="2">12PM- 3AM</option>
  <option value="3">12PM- 3AM</option>
  <option value="3">12PM- 3AM</option>

</select>
</div>

</div>

<div class="col-md-12 col-sm-12 col-xs-12 margP10">
<div class="col-md-2 col-sm-3 col-xs-12 padT10  marginB5 stepLabel">
<label class="ico14 grey">special Request</label>
</div>
<div class="col-md-6 col-sm-12 col-xs-12 padT10  marginB5 stepLabel">

<textarea class="form-control text_areaa" name="" placeholder="Eg: I want meal"></textarea>
<span class="greyLt">Special requests cannot be guaranteed – but the property will try to meet your needs.
</span>
</div>

</div>

<div class="col-md-6 col-sm-6 col-xs-6 paybtn">
<input type="button" class="" id="makePayment" value="Pay Rs. 58800">
</div>

			</div>



 <div class="col-md-1 col-xs-1 numbers_spans">
    <span  class="no_1">3</span>

</div>

<div class="col-sm-11 col-xs-11 ">

 <div class=" custo_det_car disp_payment" >

            <h3 enable="0" id="pay_head">Payment</h3><!-- onclick="tootlge(this,'paytab')"-->
            <hr>
            <div class="togle tog_hide" id="paytab">
               <div class="row" style="margin:0 0;">
                  <div class="col-md-6 col-sm-6 ">
                  </div>
                  <div class="col-md-12" style="position:relative">
                  </div>
               </div>
               <div class="secure_full_page_full" >

                <form method="post" action="" name="payment_form" class="payment_form">
                    <?php
                       // $MERCHANT_KEY   = "nSuvko";
                       // $SALT           = "ZuoX1Zt1";
                       $MERCHANT_KEY = "gtKFFx";
                       $SALT = "eCwWELxi";

                    ?>
                    <input type="hidden" id="firstname" name="firstname" value="Testing" />
                    <input type="hidden" id="email" name="email" value="lavanya@buddiestechnologies.com" />
                    <input type="hidden" id="phone" name="phone" value="9003615961" />
                    <input type="hidden" name="surl" value="<?php echo $root_dir; ?>hotels/search/booking/success.php" />
                    <input type="hidden" name="key" id="key" value="<?php echo $MERCHANT_KEY; ?>" />
                    <input type="hidden" id="salt" value="<?php echo $SALT; ?>" />
                    <input type="hidden" name="hash" id="hash" value="" />
                    <input type="hidden" name="curl" value="<?php echo $root_dir; ?>hotels/search/booking/success.php" />
                    <input type="hidden" name="furl" value="<?php echo $root_dir; ?>hotels/search/booking/success.php" />
                    <input type="hidden" name="txnid" id="txnid" value="<?php echo $txnid; ?>" />
                    <input type="hidden" name="productinfo" id="productinfo" value="Hotel Booking - <?php echo $propertyDetails[0]['reference']; ?>" />
                    <input type="hidden" name="amount" id="amount" value="<?php echo $overallTotal; ?>" />
                     <?php
                        /*if(isset($_POST['amount']) && !empty($_POST['amount'])){
                            $additional_charges = 'CC:'.($_POST['amount']*0.025); // 2.5%
                            if($_POST['amount'] <= 2000)
                                $additional_charges .= ',DC:'.($_POST['amount']*0.0075); // 0.75%
                            else
                                $additional_charges .= ',DC:'.($_POST['amount']*0.01); // 1%
                            $additional_charges .= ',NB:20';
                        }*/
                    ?>
                    <!-- <input type="hidden" name="additional_charges" id="additional_charges" value="<?php //echo $additional_charges; ?>" />-->
                     <input type="hidden" name="pg" id="pg" value="CC">
                     <input type="hidden" name="bankcode" id="bankcode">
                  </form>
                  <div class="heading" >
                     <div class="col-sm-12 payment amount_inner" >
                        
                           <p class="payment_met">Choose a payment method </p>
                       
                       
                     </div>
                  </div>
                  <div class="col-sm-3 card_menu" >
                     <ul class="nav nav-tabs credit_tab_inner" style="text-align:center;">
                        <li class="active"><a href="#a" data-toggle="tab" onclick="$('.trans_charge').html('(Transaction charge incl. Rs. 2.5%)');changeAmountDisplay('');" class="mytb hvr-sweep-to-bottom">Credit Card</a></li>
                        <li><a href="#b" data-toggle="tab" onclick="$('.trans_charge').html('(Transaction charge incl. Rs. 1%)');changeAmountDisplay('');" class="mytb hvr-sweep-to-bottom">Debit Card</a></li>
                        <li><a class="mytb hvr-sweep-to-bottom" href="#c" data-toggle="tab" onclick="javascript:changeAmountDisplay('nb');">Net Banking</a></li><!---->
                     </ul>
                  </div>
                  <div class="col-sm-9 center_content"  >
                     <div class="tab-content">
                        <div class="tab-pane active" id="a">
                           <div class="details_block credit_block" >
                              <div class="col-sm-12">
                                 <label class="col-sm-4 ">Card Type</label>
                                 <div class="col-sm-8 card_left">
                                    <select class="required valid" id="ccard-type" name="ccard-type" >
                                       <option selected="selected" value="">Select Card Type</option>
                                       <option value="CC">Visa / Masters</option>
                                       <option value="AMEX">Pay American</option>
                                       <option value="DINR">Pay Diners</option>
                                    </select>
                                    <span class="error span_ccctype error_script" >Please select Card Type</span>
                                 </div>
                              </div>
                              <div class="col-sm-12">
                                 <label  class="col-sm-4">Card Number</label>
                                 <div class="col-sm-8">
                                    <div class="input-group input_groups_edits">
                                       <input class="form-control"  type="Text" name="cc_card_number" id="cc_card_number">
                                       <span class="input-group-addon"><i class="fa fa-credit-card" aria-hidden="true"></i></span>
                                    </div>
                                    <span class="error span_ccnum error_script">Please enter card number</span>
                                 </div>
                              </div>
                              <div class="col-sm-12">
                                 <label class="col-sm-4">Name on Card</label>
                                 <div class="col-sm-8">
                                    <div class="input-group input_groups_edits">
                                       <input  class="form-control"  type="Text" name="cc_card_name" id="cc_card_name">
                                       <span class="input-group-addon"><i class="fa fa-user" aria-hidden="true"></i></span>
                                    </div>
                                 </div>
                              </div>
                              <div class="col-sm-12">
                                 <label class="col-sm-4">CVV Number</label>
                                 <div class="col-sm-8">
                                    <div class="input-group input_groups_edits">
                                       <input  class="form-control"  type="Text" name="cc_cvv_number" id="cc_cvv_number" maxlength="3">
                                       <span   rel="popover"   data-img="../images/cvvimage.png" class=" btn input-group-addon"><i style="color:#555;" class="fa fa-credit-card" aria-hidden="true"></i></span>
                                    </div>
                                    <span  class="error span_ccvv error_script">Please enter cvv number</span>
                                 </div>
                              </div>
                              <div class="col-sm-12">
                                 <label class="col-sm-4">Expiry Date</label>
                                 <div class="col-sm-4 months">
                                    <select   id="cc_expiry_date_month" name="cc_expiry_date_month">
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
                                 <div class="col-sm-4">
                                    <select  id="cc_expiry_date_year" name="cc_expiry_date_year">
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
                              <div class="col-sm-12 notess">
                                 <p>Note: In the next step you will be redirected to your bank's website to verify yourself.</p>
                              </div>
                              <div class="col-sm-12">
                                 <div class="col-sm-4 col-sm-offset-8"> <img src="../images/load_search.gif" alt="" style="width:25px;display:none; position: absolute;top: 3px;left: 81%;" class="submit_loading" />
                                    <button  type="button"  name="pay_button"  onclick="calculateHash('CC');" class=" btn btn-primary credit_pay_button payment_pay RS" id="pay_button"> Pay Now</button>
                                    
                                 </div>
                              </div>
                              <div class="col-sm-4"></div>
                           </div>
                        </div>
                        <div class="tab-pane" id="b">
                           <div class="details_block debit_block" >
                              <div class="col-sm-12">
                                 <label class="col-sm-4 "> Card Type</label>
                                 <div class="col-sm-8">
                                    <select  class="  required valid" id="debit_card_select" name="debit_card_select" onchange="select_debitcard(this.value);" style="">
                                       <option value="" selected="selected" value="">Select Card Type</option>
                                       <option value="VISA">Visa Cards</option>
                                       <option value="MAST">MasterCard</option>
                                       <option value="mdAE">SBI Maestro</option>
                                       <option value="MAES">Other Maestro</option>
                                    </select>
                                    <!-- <span class="error span_dcctype error_script">Please select Card Type</span> -->
                                 </div>
                              </div>
                              <div class="col-sm-12">
                                 <label  class="col-sm-4">Card Number</label>
                                 <div class="col-sm-8">
                                    <div class="input-group input_groups_edits">
                                       <input class="form-control"  type="Text" name="dc_card_number" id="dc_card_number">
                                       <span class="input-group-addon"><i class="fa fa-credit-card" aria-hidden="true"></i></span>
                                    </div>
                                   <!--  <span style="color:red;font-size:10px; "class="error span_ccnum error_script">Please enter card number</span> -->
                                 </div>
                              </div>
                              <div class="col-sm-12">
                                 <label class="col-sm-4">Name on Card</label>
                                 <div class="col-sm-8">
                                    <div class="input-group input_groups_edits">
                                       <input class="form-control"   type="Text" name="dc_card_name" id="dc_card_name">
                                       <span class="input-group-addon"><i class="fa fa-user" aria-hidden="true"></i></span>
                                    </div>
                                 </div>
                              </div>
                              <div class="col-sm-12">
                                 <label class="col-sm-4">CVV Number</label>
                                 <div class="col-sm-8">
                                    <div class="input-group input_groups_edits">
                                       <input  class="form-control"  type="Text" name="dc_cvv_number" id="dc_cvv_number" maxlength="3">
                                       <span   rel="popover"   data-img="../images/cvvimage.png" class=" btn input-group-addon"><i style="color:#555;" class="fa fa-question" aria-hidden="true"></i></span>
                                    </div>
                                    <span  class="error span_ccvv error_script">Please enter cvv number</span>
                                 </div>
                              </div>
                              <div class="col-sm-12">
                                 <label class="col-sm-4">Expiry Date</label>
                                 <div class="col-sm-4 months">
                                    <select    id="dc_expiry_date_month" name="dc_expiry_date_month">
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
                                    <br>
                                     <span class="error span_ccexpmon error_script">Please enter Month</span>
                                 </div>
                                 <div class="col-sm-4">
                                    <select     id="dc_expiry_date_year" name="dc_expiry_date_year">
                                       <option value="">Year</option>
                                       <?php 
                                          $currentYear = date('Y'); $toYear = $currentYear+50;
                                          for($i=$currentYear;$i<=$toYear;$i++)
                                          echo '<option value="'.$i.'">'.$i.'</option>';
                                          ?>
                                    </select>
                                   <span class="error span_ccexpyr error_script">Please enter Year</span>
                                 </div>
                              </div>
                              <div id="enableDisableDiv">
                                 <div class="enableDisableCvvExp row" id="enableCvvExpDiv" style="display: 
                                 ;" onclick="$('.enableDisableCvvExp').hide();$('#disableCvvExpDiv').show();$('.switch_cvv_exp').show();">
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
                              <div class="col-sm-12 notess">
                                 <p>Note: In the next step you will be redirected to your bank's website to verify yourself.</p>
                              </div>
                              <div class="col-sm-12">
                                 <div class="col-sm-4 col-md-offset-8"> <img src="../images/load_search.gif" alt="" style="width:25px;display:none; position: absolute;top: 3px;left: 81%;" class="submit_loading" />
                                    <button type="button" onclick="calculateHash('DC');" class="btn-primary btn credit_pay_button payment_pay  RS ">Pay Now            </button>
                                 </div>
                              </div>
                              <div class="col-sm-4"></div>
                           </div>
                        </div>
                        <div class="tab-pane" id="c">
                           <div class="details_block net_banking_block" >
                              <!--<label class="col-sm-12 ">or select any other bank:</label>-->
                              <div class="col-sm-12">
                                 <select class="frm"id="netbanking_select" name="netbanking_select">
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
                                    <!--<option value="HDFB">HDFC Bank</option>-->
                                    <option value="ICIB">ICICI Netbanking</option>
                                    <option value="IDBB">IDBI Bank</option>
                                    <option value="INDB">Indian Bank</option>
                                    <option value="INOB">Indian Overseas Bank</option>
                                    <option value="INIB">IndusInd Bank</option>
                                    <!--<option value="JAKB">Jammu and Kashmir Bank</option>-->
                                    <option value="JSBNB">Janata Sahakari Bank Pune</option>
                                    <option value="KRKB">Karnataka Bank</option>
                                    <option value="KRVBC">Karur Vysya - Corporate Netbanking</option>
                                    <option value="KRVB">Karur Vysya - Retail Netbanking</option>
                                    <!--<option value="162B">Kotak Mahindra Bank</option>-->
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
                           <div class="col-sm-12 notess">
                              <p>Note: In the next step you will be redirected to your bank's website to verify yourself.</p>
                           </div>
                           <div class="col-sm-12">
                              <div class="col-md-4 col-md-offset-8"> <img src="../images/load_search.gif" alt="" style="width:25px;display:none; position: absolute;top: 3px;left: 81%;" class="submit_loading" />
                                 <button type="button" id="npay_button" class="btn-primary btn credit_pay_button payment_pay  RS"  onclick="calculateHash('NB');">
                                 Pay Now
                                </button>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>

</div>


<script type="text/javascript">
	var textarea = document.querySelector('textarea');

textarea.addEventListener('keydown', autosize);
             
function autosize(){
  var el = this;
  setTimeout(function(){
    el.style.cssText = 'height:auto; padding:0';
    // for box-sizing other than "content-box" use:
    // el.style.cssText = '-moz-box-sizing:content-box';
    el.style.cssText = 'height:' + el.scrollHeight + 'px';
  },0);
}
</script>


	</div>
	</div>
	</div>
</body>

