<?php
session_start();
error_reporting(0);
$payment_page=true;
include '../../../include/database/config.php';
include('../../../include/header.php');

if(isset($_POST['id_property']) && !empty($_POST['id_property']))
        $_SESSION['postVals'] = $_POST;
    else if(isset($_SESSION['postVals']) && !empty($_SESSION['postVals']))
        $_POST = $_SESSION['postVals'];
    $currentpage="hotelbooking";
    $id_property = (int)$_POST['id_property'];
    $city_name = $_POST['city_name'];
    $city_id = $_POST['city_id'];
    $check_in = $_POST['check_in'];
    $check_out = $_POST['check_out'];
    $room_rate = $_POST['room_rate'];  
    $room_id = (int)$_POST['room_id'];
    $room_type_id = (int)$_POST['room_type_id'];
    $room_count = $_POST['room_count'];
    $guest_text = $_POST['guest_text'];
    $guest = $_POST['guest'];
    $numberOfNights = $_POST['no_of_nights'];
    $mealRate = (int)$_POST['meal_rate'];
    $breakfast_incl = (int)$_POST['breakfast_incl'];
    $lunch_incl = (int)$_POST['lunch_incl'];
    $dinner_incl = (int)$_POST['dinner_incl'];
    $room_base_rate = $_POST['room_base_rate'];
  

    //if((isset($_SESSION['roomRate'][$room_id][$room_type_id]) && !empty($_SESSION['roomRate'][$room_id][$room_type_id]) && $_SESSION['roomRate'][$room_id][$room_type_id] != $room_rate) || $_SESSION['check_in'] != $check_in || $_SESSION['check_out'] != $check_out || $_SESSION['city_name'] != $city_name || $_SESSION['city_id'] != $city_id || $_SESSION['room_id'] != $room_id || $_SESSION['room_type_id'] != $room_type_id || $_SESSION['guest_text'] != $guest_text || $_SESSION['no_of_nights'] != $numberOfNights || $_SESSION['room_count'] != count($guest))
    //if(isset($_SESSION['roomRate'][$room_id][$room_type_id]) && !empty($_SESSION['roomRate'][$room_id][$room_type_id]) && $_SESSION['roomRate'][$room_id][$room_type_id] != $room_rate)
        //die('Invalid Request');

    $guestCntHidden = '';
 
    if(isset($guest) && !empty($guest)){
        foreach($guest as $key => $val){
            $guestCntHidden .= '<input type=\'Hidden\' name="guest['.$key.'][adult]" value=\''.$val['\'adult\''].'\'><input type=\'Hidden\' name="guest['.$key.'][child]" value=\''.$val['\'child\''].'\'>';
        }
    }   
   
    $propertyDetails = $database_hotel->query('select l.txtlandmark,p.offer_percentage, p.*,r.id_room,rt.txtRoomName,r.selNoOfBeds,r.selRoomBedSize,rt.id_room_type,rt.is_breakfast,c.name as cityName,s.name as stateName from ps_property p left join ps_room r on(p.id_property = r.id_property) left join ps_room_type rt on(r.id_room = rt.id_room) left join ps_state s on(s.id_state = p.selStateId) left join ps_city c on(c.id_city = p.selCityId) left join ps_landmark l on(l.id_landmark=p.selLandmark and l.status=0 and l.action=\'\')where p.id_property =\''.$id_property.'\' and r.id_room=\''.$room_id.'\' and rt.id_room_type=\''.$room_type_id.'\'')->fetchAll();

    if(isset($propertyDetails[0]['selImages']) && !empty($propertyDetails[0]['selImages'])){
        $property_gallery_arr = json_decode($propertyDetails[0]['selImages']);
        
            $imageProperty = _BO_HOTEL_IMG_DIR_.'property/'.$propertyDetails[0]['id_property'].'/'.current(current($property_gallery_arr));
      }
    //print_r($propertyDetails);
   // $room_rate=$room_rate+$mealRate;
    $room_rate_with_offer=$room_base_rate;
    $offer_percentage=$propertyDetails[0]['offer_percentage'];
    $bf=($room_rate_with_offer/(100+$offer_percentage))*100;
    $bf= $room_rate_with_offer-$bf;
    $room_rate_without_offer=round($room_rate_with_offer+$bf);  
    


    $actualTotal_witoutoffer = ($room_rate_without_offer*$room_count);   
    $actualTotal_witoffer=($room_base_rate*$room_count);  


    $subtotal=$actualTotal_witoffer;
    /*$cgst = round($subtotal*($propertyDetails[0]['txtCGST']/100));
    $sgst = round($subtotal*($propertyDetails[0]['txtSGST']/100));
    $tac  = round($subtotal*($propertyDetails[0]['txtTAC']/100));
    $overallTotal = round($subtotal+$cgst+$sgst+$mealRate);*/

    $hotelConfig = array();
    $getHotelConfigValues = $database->query("select name,value from ps_configuration where name = 'ps_hotel_service_tax' or  name = 'ps_hotel_commission' or  name = 'ps_hotel_pay_cgst_2499' or  name = 'ps_hotel_pay_sgst_2499' or  name = 'ps_hotel_pay_cgst_7499' or  name = 'sps_hotel_pay_sgst_7499' or  name = 'ps_hotel_pay_cgst_7500' or  name = 'ps_hotel_pay_sgst_7500'")->fetchAll();
    if(isset($getHotelConfigValues) && !empty($getHotelConfigValues)){
        foreach($getHotelConfigValues as $hotelVal){
            $hotelConfig[$hotelVal['name']] = $hotelVal['value'];
        }
    }
    
    if($room_base_rate > 999 && $room_base_rate <= 2499){
        $cgst = round(($room_base_rate*($hotelConfig['ps_hotel_pay_cgst_2499']/100))*$room_count);
        $sgst = round(($room_base_rate*($hotelConfig['ps_hotel_pay_sgst_2499']/100))*$room_count);
    }else if($room_base_rate > 2499 && $room_base_rate <= 7499){
        $cgst = round(($room_base_rate*($hotelConfig['ps_hotel_pay_cgst_7499']/100))*$room_count);
        $sgst = round(($room_base_rate*($hotelConfig['sps_hotel_pay_sgst_7499']/100))*$room_count);
    }else if($room_base_rate > 7499){
        $cgst = round(($room_base_rate*($hotelConfig['ps_hotel_pay_cgst_7500']/100))*$room_count);
        $sgst = round(($room_base_rate*($hotelConfig['ps_hotel_pay_sgst_7500']/100))*$room_count);
    }else
        $cgst = $sgst = 0;
    
    $staysinn_discount=$save=$actualTotal_witoutoffer-$actualTotal_witoffer;
    $overallTotal = round($subtotal+$cgst+$sgst+$mealRate);
    /*$hotelConfig = array();
    $getHotelConfigValues = $database->query("select name,value from ps_configuration where name = 'ps_hotel_service_tax' or  name = 'ps_hotel_commission'")->fetchAll();
    if(isset($getHotelConfigValues) && !empty($getHotelConfigValues)){
        foreach($getHotelConfigValues as $hotelVal){
            $hotelConfig[$hotelVal['name']] = $hotelVal['value'];
        }
    }
    $ps_hotel_commission = (isset($hotelConfig['ps_hotel_commission']) && !empty($hotelConfig['ps_hotel_commission']) ? $hotelConfig['ps_hotel_commission'] : 0);
    $ps_hotel_service_tax = (isset($hotelConfig['ps_hotel_service_tax']) && !empty($hotelConfig['ps_hotel_service_tax']) ? $hotelConfig['ps_hotel_service_tax'] : 0);
    
    $commissionAmount = $tac*($ps_hotel_commission/100);
    $commissionAmount = $commissionAmount - ($commissionAmount*($ps_hotel_service_tax/100));*/
    $commissionAmount = 0;
    $txnid          = substr(hash('sha256', mt_rand() . microtime()), 0, 20);
?>

<style>

 .popover {
    max-width: 800px;
    text-align: left !important;}

     .error{
        display : none;
        color:#C40000;
        font-size: 11px;
    }

    
    .credit_tab_inner > li a {
    color: #555;
    background: none;
    box-shadow: 0 0 1px #ccc;
    border: none;
    border-radius: 0;
    border: none!important;
}

.custo_det_car select {
    color: rgba(85, 85, 85, 0.76)!important;
   
    padding-left: 10px;
}

.details_block select {
    margin-top: 10px;
}

.custo_det_car label {
    color: #1c1c1c;
    font-size: 13px;
}

.disp_payment label {
    margin-top: 10px;
}

.tab-content select{padding: 8px 10px;height: 34px;
    width: 100%;
    border: 1px solid #ccc;
    color: #454545;
    /*-webkit-border-radius: 18px;
    -moz-border-radius: 18px;*/
    border-radius: 3px!important;}

    .selector span{display: none;}
.secure_full_page_full {margin:20px 0;}
    .secure_full_page_full select{border-radius: 3px;width: 100%!important;border:1px solid #ccc;}

    .Step  select{ margin-bottom: 10px; font-weight: normal; padding:0px 10px;    color: #454545;    margin-bottom: 10px;
    font-weight: normal;
    padding: 0px 10px;
    color: #454545;
    height: 37px;
    border-radius: 3px;
    border: 1px solid #ccc;}

    .credit_tab_inner a:before{background:#C40000;color:#fff;}
        .credit_tab_inner a{color:#fff;}
        .credit_tab_inner > li{width: 100%;}
         .credit_tab_inner  {border: none!important;}

        .credit_tab_inner > li.active > a, .credit_tab_inner > li.active > a:focus, .credit_tab_inner > li.active > a:hover {
    color: #fff;
    background: #C40000;
}


.Step{margin-top: 20px;}    
.headtop{-webkit-box-shadow: 0 0 30px rgba(0, 0, 0, 0.1)!important;
    }

   .no_1{background: #7b7778;
    border-radius: 20px;
    -webkit-border-radius: 20px;
    -moz-border-radius: 20px;
    padding: 7px 13px;
    font-size: 18px;
    color: #333;
    line-height: 1;
    color: #fff;}
.Step .texts{margin-left: 10px;    font-weight: 600;
    font-size: 18px;}
.marginB10{margin-top: 10px;}
.imgwid{
margin:0 auto;
    max-height: 158px;
    max-width: 250px;
    
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

.tickicon{//padding-bottom: 15px;}
ul.tickicon{margin:0px;}
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

.pad5 {margin-left: 15px;}
.btntype{    width: 90px; margin-left: 12px;}
 .grey{margin:0px;margin-top:10px;padding: 0}
.marg_top{margin-top:15px;}
}
  .text_areaa {  

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
.greyLt{font-size: 12px;color:#a5a5a5;}
.paybtn{float: right;padding-bottom: 20px;}
.tab-content{display: block!important;}


   /* .inner_contaboknows select{width:100%!important;}

    #uniform-debit_card_select.selector select{width: 300px!important;}
    #uniform-dc_expiry_date_month.selector select,#uniform-dc_expiry_date_year.selector select{width: 150px!important;}

    #uniform-netbanking_select.selector select{width: 300px!important;}*/
    .tab-content{width: 100%;}
.selector{width: 100%!important;}

.secure_full_page_full button{float: right;}



.sign_ins {
     background: #c40000;
    border-radius: 3px 0 0 3px;
    color: #fff;
    display: inline-block;
  
    line-height: 26px;
    padding: 0 20px 0 23px;
    position: relative;
    margin: 0 10px 10px 10px;
    
    -webkit-transition: color 0.2s;
}

.sign_ins::before {
  background: #fff;
  border-radius: 10px;
  box-shadow: inset 0 1px rgba(0, 0, 0, 0.25);
  content: '';
  height: 6px;
  left: 10px;
  position: absolute;
  width: 6px;
  top: 10px;
}

.sign_ins::after {
    background: #fff;
    border-bottom: 13px solid transparent;
    border-left: 10px solid #c40000;
    border-top: 13px solid transparent;
    content: '';
    position: absolute;
    right: 0;
    top: 0;
}



@media screen and (max-width: 699px) {

   .sign_ins {
   
    margin: 0 10px 10px 43px;
    
}

    .imgwid{    margin: 10px 0!important;}
}








</style>


 <div class="container">
    <div class="col-xs-12 col-sm-12">
     <ol class="breadcrumb" style="margin:0; padding: 0;background: #fff;margin:20px 0 10px 0;">
        <li class="breadcrumb-item"><a class="values_cityname" href="<?php echo $root_dir;?>">Home</a></li>
        <li class="breadcrumb-item"><a class="values_cityname" href="<?php echo $_SESSION['2ndpage']?>"><?php echo $city_name; ?></a></li>
        <li class="breadcrumb-item active"><a href="<?php echo $_SERVER['HTTP_REFERER'] ?>" class="values_cityname1"><?php echo $propertyDetails[0]['txtPropertyName']; ?></a></li>
     </ol> 
    </div>  
</div>


    <div class="container ">

<div class="container container_booknows headtop" style=" ">
      
<div class="col-xs-12 col-sm-12 inner_contaboknows Step"  >

    <div class="" id="review_hotel_1">
      





        <div>
        <span class="no_1">1</span>
            <span class="texts">Review your booking</span>
            <div class="clearfix"></div>
            </div>

<div class="marginB10">
            <div class="col-md-4 col-sm-5 col-xs-12 ">
           
          <!--   <img class="imgwid" src="<?php echo (isset($imageProperty) && !empty($imageProperty) ? $imageProperty : ''); ?>" width="100%"> <img class="imgwid" src="https://media-cdn.tripadvisor.com/media/photo-o/0e/d5/8e/98/hotel-carlos-i.jpg" width="100%">-->
          
<img class="imgwid" src="<?php echo (isset($imageProperty) && !empty($imageProperty) ? $imageProperty : ''); ?>" width="100%">


        </div>

            <div class="col-md-8 col-sm-7 col-xs-12">
            <h3 class="h3tt">
                              <?php echo $propertyDetails[0]['txtPropertyName'] ?><span class="stars">
                              <i class="material-icons"></i>
                              <i class="material-icons"></i>
                              <i class="material-icons"></i>
                              <i class="material-icons"></i>
                              </span> 
                             
                           </h3>
                           <span class="address map_marker_icon">
                              <i class="fa fa-map-marker" aria-hidden="true"></i><span class="txtlandmark"> <?php echo $propertyDetails[0]['txtlandmark'] ?>, <?php echo $city_name ?></span>
                             </span>
                             <p class="deluxe_txt"><?php echo $propertyDetails[0]['txtRoomName'].(isset($roomTypes[$propertyDetails[0]['id_room_type']]) && !empty($roomTypes[$propertyDetails[0]['id_room_type']]) ? ' ('.$roomTypes[$propertyDetails[0]['id_room_type']].')' : '').(!empty($propertyDetails[0]['selNoOfBeds']) ? ', '.$propertyDetails[0]['selNoOfBeds'].(!empty($roomBedSize[$propertyDetails[0]['selRoomBedSize']]) ? ' '.$roomBedSize[$propertyDetails[0]['selRoomBedSize']] : '') : ''); ?></p>




                             <?php if($id_property != '29'){ ?>
         <p class="stay_txt">Your stay includes:</p>
            <ul class="tickicon">
                <?php if(isset($breakfast_incl) && $breakfast_incl == 1){ ?><li style="padding: 0;"><span class=""><i class="fa fa-check colorz" aria-hidden="true"></i></span><span><?php echo 'Complimentary Breakfast'; ?></span></li><?php } ?>

            </ul>
             <ul class="tickicon">
                <?php if(isset($lunch_incl) && $lunch_incl == 1){ ?><li style="padding: 0;"><span class=""><i class="fa fa-check colorz" aria-hidden="true"></i></span><span><?php echo 'Lunch Included'; ?></span></li><?php } ?>
                    
            </ul>
             <ul class="tickicon">
                <?php if(isset($dinner_incl) && $dinner_incl == 1){ ?><li style="padding: 0;"><span class=""><i class="fa fa-check colorz" aria-hidden="true"></i></span><span><?php echo 'Dinner Included'; ?></span></li><?php } ?>
                    
            </ul>
            <?php } ?>
                            
                             

                          </div>
        <div class="col-md-3 col-xs-3" style="padding:0px">
</div>
        <div class="col-md-9 col-xs-9">
            <!--<p style="font-size: 20px;">
                <?php //echo (isset($propertyDetails[0]['txtPropertyName']) ? $propertyDetails[0]['txtPropertyName'] : ''); ?>
                <span>
                    <i class="stars" style="padding-left: 12px;">
                    <?php /*for($ratingInc = 0; $ratingInc < $propertyDetails[0]['selStarRating']; $ratingInc++){ 
                                echo '<span class="glyphicon glyphicon-star"></span>';
                    }*/ ?>
                    </i>
                </span>
            </p>-->
            <!--<p><i class="glyphicon glyphicon-map-marker" style="    color: #db0b0b;"></i><?php //echo $propertyDetails[0]['txtAddress1'].(!empty($propertyDetails[0]['txtAddress2']) ? ', '.$propertyDetails[0]['txtAddress2'] : ''); ?></p>-->

            
        </div>

        <div class="clearfix"></div>
      
       
     
       

               
    </div>


    <div class="clearfix"></div>





     <div class="col-xs-12 col-sm-12" style="line-height: 22px;"> 
        <div class="row">
                            <div class="bkChkDetailsIn">
                            
                            <div class="col-sm-3">
                            <span class="top_detail db">Check-In</span>
                            <span class=" db"><?php echo date('D, d M Y',strtotime($check_in)); ?></span>
                            <span id="checkin-time" class="db" style="display: none;">12:00 PM</span>
                            </div>
                            <div class="col-sm-3">
                            <span class="top_detail db">Check-Out</span>
                            <span class=" db"><?php echo date('D, d M Y',strtotime($check_out)); ?></span>
                            <span id="checkout-time" class="db" style="display: none;">12:00 PM</span>
                            </div>
                            
                            <div class="col-sm-3">
                            <span class="top_detail lh1-5 db"><?php echo $guest_text; ?></span>
                            <span class=" db"><?php echo $numberOfNights;?> night <?php echo $numberOfNights-1;?> day
                            </span>
                            </div>
                            <div class="col-sm-3">

                            <span class="db">Non Refundable</span>
                            <a href="#">Booking &amp; Cancellation Policy</a>
                            </div>
                            </div>
                            
                        </div>

                        </div>

                        <div id="roomCountMessage" class="bkOfferTagWrap">
                        <div class="bkOfferTag fmtTooltip" style="cursor:auto;color:#dc5858"><span class="arrowLeft"></span>HURRY! - Book Your Hotels. ACT FAST!!</div>
                        </div>

                        
                        <!-- price details --> 

                        <div class="col-xs-12 col-sm-12 bor_bot">

                                    <div class="col-sm-6"></div>

                                    <div class="col-sm-6 price_wrap">
                                     <div class="priceSpilt">
                                     <ul>
                                     <li>
                                        <div class="priceSplitBase">
                                          <span class="ico13">Room Charges</span>
                                        <span class="rupee_right">
                                          <span class="ico13"><span><i class="fa fa-inr" aria-hidden="true"></i></span><?php echo $actualTotal_witoutoffer;?></span>
                                        </span>
                                      </div>
                                    </li>
                                
                                    <li>
                                        <div class="priceSplitBase">
                                          <span class="ico13">Klitestays Discount</span>
                                        <span class="rupee_right">
                                          <span class="ico13"><span><i class="fa fa-inr" aria-hidden="true"></i></span> <?php echo $staysinn_discount;?></span>
                                        </span>
                                      </div>
                                    </li>
                                
                                    <li>
                                        <div class="priceSplitBase">
                                          <span class="ico14" style="">SubTotal</span>
                                        <span class="rupee_right">
                                          <span class="ico14"><span><i class="fa fa-inr" aria-hidden="true"></i></span><?php echo $subtotal;?></span>
                                        </span>
                                      </div>
                                    </li>
                                
                                    <li>
                                        <div class="priceSplitBase">
                                          <span class="ico13">GST on Room Charges</span>
                                        <span class="rupee_right">
                                          <span class="ico13"><span><i class="fa fa-inr" aria-hidden="true"></i></span><?php echo $cgst+$sgst;?></span>
                                        </span>
                                      </div>
                                    </li>
                                
                                    <li>
                                        <div class="priceSplitBase">
                                          <span class="ico13">Grand Total</span>
                                        <span class="rupee_right">
                                          <span class="ico13" ><span><i class="fa fa-inr" aria-hidden="true"></i></span><?php echo $overallTotal; ?></span>
                                        </span>
                                      </div>
                                    </li>
                                
                                    <li>
                                        <div class="priceSplitBase">
                                          <span class="ico14">Pay Now</span>
                                        <span class="rupee_right">
                                          <span class="ico14"><span><i class="fa fa-inr" aria-hidden="true"></i></span><?php echo $overallTotal; ?></span>
                                        </span>
                                      </div>
                                    </li>
                                
                                    <li style="border-bottom: none;">
                                        <div class="priceBreakSave">
                                          <span class="ico15"> Savings</span>
                                        <span class="rupee_right">
                                          <span class="ico15"><span><i class="fa fa-inr" aria-hidden="true"></i></span><?php echo $save;?></span>
                                        </span>
                                      </div>
                                    </li>
                                
                          </ul>
                          </div>
                           </div>


                     
   


<div class="col-sm-12">
    <div class="clearfix"></div>
         <p>
    <input style="    width: 14px;
    position: relative;
    height: 16px;
    top: 4px;" type="checkbox" id="termscondi"><a  data-trigger="hover" data-toggle="popoverc"  data-placement="right" data-html="true" style="margin-left:10px;color: #005bff;" data-content='<div><?php echo (isset($propertyDetails[0]['terms_and_conditions']) ? $propertyDetails[0]['terms_and_conditions'] : ''); ?></div>' class="custonlink termsandcondis" >I Agree All Terms and Conditions</a></p>
</div>
                    </div>

  <div id="hide_div" class="col-xs-12 col-sm-12" style="padding: 0;display: none;">
    <form class="hotel_form">

      
            <hr>        

        <div class="Step col-md-12 col-sm-12 col-xs-12">


                <span class="no_1">2</span>
                    <span class="texts"> Guest Details </span>
                   <!--  <span class="ico14 pad5 ">- Or -</span>
                    <span  class="sign_ins"> Sign in</span>
                    <span class="">to speed up your booking process and use your GoCash</span> -->
            


                    <div class="col-md-12 col-sm-12 col-xs-12 marginT10">
                    <div class="col-md-2 col-sm-3 col-xs-12 padT10 marginB5 stepLabel"><label class="ico14 grey">Guest Name </label></div>
                    <div class="col-md-10 col-sm-9 col-xs-12 pad0" style="padding: 0px;">
                    <div class="col-md-6 col-sm-7 col-xs-12" style="padding: 0px;"> 
                    <div class="col-md-3 col-sm-4 col-xs-12 pad0 marginB10">
                    <select  class="form-control inputMedium" id="choose" name="choose" style="margin-bottom: 0px!important">
                    <option value="1" selected="selected">Mr</option>
                    <option value="2">Mrs</option>
                    <option value="3">Ms</option>
                    </select>
                    </div>
                    <div class="col-md-9 col-sm-8 col-xs-12 pad0 marginB10">

                    <input  class="firstname_hotel" type="text" name="customer_firstname" id="customer_firstname"  placeholder="First Name" required>
                     <span class="error span_customer_firstname error_script" >Please enter Firstname</span>
                    </div>
                    </div>
                    <div class="col-md-4 col-sm-5 col-xs-12 marginB10">
                    <input type="text" name="customer_lastname" id="customer_lastname" placeholder="Last Name" required>
                     <span class="error span_customer_lastname error_script" >Please enter Lastname</span>
                    </div>
                    </div>
                    </div>

                    <div class="col-md-12 col-sm-12 col-xs-12 marg_top">
                    <div class="col-md-2 col-sm-3 col-xs-12 padT10  marginB5 stepLabel">
                    <label class="ico14 grey">Email Address</label>
                    </div>

                    <div class="col-md-10 col-sm-9 col-xs-12 pad0">
                    <div class="col-md-6 col-sm-7 col-xs-12  posRel" style="padding: 0px">
                      <input type="email" name="customer_email" id="customer_email"  placeholder="example@gmail.com" required style="    margin-top: 14px;">
                                        <span class="error span_customer_email error_script" >Please enter Email</span>
                    <i class="icon-email iconEmailPos ico24 greyLt"></i>
                    </div>
                    <div class="col-md-6 col-sm-5 col-xs-12 mobdn" style="margin-top: 12px;">
                    <span class="greyLt">Your voucher will be sent to this email address</span>
                    </div>
                    </div>
                    </div>

                    <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="col-md-2 col-sm-3 col-xs-12 padT10  marginB5 stepLabel">
                    <label class="ico14 grey">Mobile Number</label>
                    </div>
                    <div class="col-md-2 col-sm-6 col-xs-12 padT10  marginB5 stepLabel">

                    <select class="ico14 grey" style="margin-left: 0px;">Mobile Number
                      <option value="1">India(+91)</option>
                      <option value="2">India(+91)</option>
                      <option value="3">India(+91)</option>
                      <option value="3">India(+91)</option>

                    </select>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-12">
                    <input style="padding-left: 36px;margin-top: 15px;margin-left: 0px;" type="text" name="customer_contact" id="customer_contact" placeholder="" required>
                                        <span class="error span_customer_contact error_script" >Please enter Contact</span>
                    </div>
                    </div>


                    <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="col-md-2 col-sm-3 col-xs-12 padT10  marginB5 stepLabel">
                    <label class="ico14 grey"> Expected Time of Check In</label>
                    </div>
                    <div class="col-md-2 col-sm-6 col-xs-12 padT10  marginB5 stepLabel">

                    <select class="ico14 grey" style="margin-left: 0px;">I'dont know
                      <option value="1">12PM- 3AM</option>
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
                    <label class="ico14 grey">Special Request</label>
                    </div>
                    <div class="col-md-6 col-sm-12 col-xs-12 padT10  marginB5 stepLabel" style="margin-bottom: 15px;">

                    <textarea class="form-control text_areaa " name="customer_specialrequest" placeholder="Eg : I want meal"  ></textarea>

                    <span class="greyLt">Special requests cannot be guaranteed – but the property will try to meet your needs.
                    </span>
                    </div>

                    </div>


                    <input type="Hidden" name="check_in" id="check_in" value="<?php echo (isset($check_in) ? $check_in : ''); ?>">
                    <input type="Hidden" name="check_out" id="check_out" value="<?php echo (isset($check_out) ? $check_out : ''); ?>">
                    <input type="Hidden" name="city_name" id="city_name" value="<?php echo (isset($city_name) ? $city_name : ''); ?>">
                    <input type="Hidden" name="city_id" id="city_id" value="<?php echo (isset($city_id) ? $city_id : ''); ?>">
                    <input type="Hidden" name="room_rate" id="room_rate" value="<?php echo (isset($room_rate) ? $room_rate : 0); ?>">
                    <input type="Hidden" name="room_id" id="room_id" value="<?php echo (isset($room_id) ? $room_id: 0); ?>">
                    <input type="Hidden" name="room_type_id" id="room_type_id" value="<?php echo (isset($room_type_id) ? $room_type_id : 0); ?>">
                    <input type="Hidden" name="guest_text" id="guest_text" value="<?php echo (isset($guest_text) ? $guest_text : ''); ?>">
                    <?php echo (isset($guestCntHidden) ? $guestCntHidden : ''); ?>
                    <input type="Hidden" name="no_of_nights" value="<?php echo (isset($numberOfNights) ? $numberOfNights : ''); ?>">
                    <input type="Hidden" name="room_count" id="room_count" value="<?php echo (isset($room_count) ? $room_count : 0); ?>">
                    <input type="Hidden" name="id_property" id="id_property" value="<?php echo (isset($id_property) ? $id_property : ''); ?>">
                    <input type="Hidden" name="meal_rate" id="meal_rate" value="<?php echo $mealRate; ?>">
                    <input type="Hidden" name="breakfast_incl" id="breakfast_incl" value="<?php echo (isset($breakfast_incl) ? $breakfast_incl : 0); ?>">
                    <input type="Hidden" name="lunch_incl" id="lunch_incl" value="<?php echo (isset($lunch_incl) ? $lunch_incl : 0); ?>">
                    <input type="Hidden" name="dinner_incl" id="dinner_incl" value="<?php echo (isset($dinner_incl) ? $dinner_incl : 0); ?>">
                    <input type="Hidden" name="commission_amount" value="<?php echo $commissionAmount; ?>">
                    <input type="Hidden" name="cgst_amount" value="<?php echo $cgst; ?>">
                    <input type="Hidden" name="sgst_amount" value="<?php echo $sgst; ?>">
                    <input type="Hidden" name="customer_id" value="<?php echo isset($_SESSION['authtnid'])?$_SESSION['authtnid']:''; ?>">
                    <input type="Hidden" name="grand_total_price" id="grand_total_price" value="<?php echo $overallTotal; ?>">
                    <!--<input type="submit" class="btn btn-red" id="makePayment" value="Book">-->
                    
                    <input type="hidden" name="txnid" id="txnid1" value="<?php echo $txnid; ?>" />
             </div>
              <div class="col-md-4 col-md-offset-8"> <img src="../images/load_search.gif" alt="" style="width:25px;display:none; position: absolute;top: 3px;left: 81%;" class="submit_loading" />
                                 <button type="button" id="npay_button" class="btn-primary btn credit_pay_button payment_pay  RS"  onclick="calculateHash('NB');">
                                 Proceed
                                </button>
                              </div>
      
        </form>
    <div >



</div>
</div>
</div>
</div>
</div>
</div>



<!--<script type="text/javascript">
$(document).ready(function(){
    $('#termscondi').change(function(){
        if(this.checked)
            $('.full_underdiv').fadeIn('slow');
        else
            $('.full_underdiv').fadeOut('slow');

    });
});
</script>-->





<script>
$(document).ready(function(e) {
  
    $(".hotel_form").submit(function(e) {
          
        var formdata=$(this).serialize();
       
          var authtnid='<?php echo (isset($_SESSION['authtnid']))?$_SESSION['authtnid']:'';?>';  
          if(authtnid==0||authtnid==''){
            var postdata="getmods=savecustomer&first_name="+$("#customer_firstname").val()+"&last_name="+$("#customer_lastname").val()+"&address=''&mobile_no="+$("#customer_contact").val()+"&email_id="+$("#customer_email").val()+"&username="+$("#customer_email").val()+"&password="+$("#customer_email").val();
                 $.ajax({
                   url: "<?php echo $root_dir; ?>mods_functions_manager_createcustomer.php",
                   data: postdata,
                   type: "POST",
                   async: false,
                   success: function(result){
                        var result=jQuery.parseJSON(result);
                     if(result.id_customer||authtnid)
                     {
                      
                    //Code for Payment Gateway
    let amount = $("#grand_total_price").val();
    let razorpayOrderId = '';
    let razorpay_payment_id = '';
    let razorpay_signature = '';
    let payment_message = '';

    $.ajax({
        url: "<?php echo $root_dir ?>razorpay_payment/generateRazorPayOrder.php",
        data: 'amount=' + amount,
        type: "POST",
        beforeSend: function() {
            $.LoadingOverlay("show", {
                image: "",
                fontawesome: "fa fa-spinner fa-spin"
            });
        },
        success: function(result) {
            $.LoadingOverlay("hide", {
                image: "",
                fontawesome: "fa fa-spinner fa-spin",
            });

            console.log('Success:', result);
            var resultData = JSON.parse(result);
            var razorpayOrderId = resultData.razorpayOrderId;
            var receiptnos = resultData.receiptnos;
            var keyId = "<?php echo ($keyId); ?>";
            var options = {
                key: keyId, // Your Razorpay API Key
                amount: amount * 100, // Amount in paisa
                currency: 'INR',
                name: 'Klite Stays',
                description: 'Make a Payment',
                order_id: razorpayOrderId,
                 prefill: {
                name: $("#customer_firstname").val(),
                email: $("#customer_email").val(),
                contact: $("#customer_contact").val()
            },
            notes: {
                address: "KliteStays Pondicherry"
            },
            theme: {
                "color": "#3399cc"
            },
                handler: function(response) {
                    razorpay_payment_id = response.razorpay_payment_id;
                    razorpay_signature = response.razorpay_signature;
                    $.ajax({
                        url: "<?php echo $root_dir ?>razorpay_payment/verifyRazorPaySignature.php",
                        data: 'order_id=' + response.razorpay_order_id + '&payment_id=' + response.razorpay_payment_id + '&razorpay_signature=' + response.razorpay_signature,
                        type: "POST",
                        success: function(responseData) {
                            console.log('Received Response Data:', responseData);
                            if (responseData.trim() == "Success") {
                                     authtnid=result.id_customer;
                     $("#authtnid").val(authtnid);
                     $("#customer_id").val(authtnid);
                     var formdata=$(this).serialize();
                           $.ajax({
                           type: "POST",
                           url: "<?php echo $root_dir?>hotels/search/booking/get_booking_ref.php",
                           data: formdata,
                           dataType: "JSON",
                           async: false, 
                           beforeSend: function() {
                               $.LoadingOverlay("show", {
                                   image: "",
                                   fontawesome: "fa fa-spinner fa-spin",
                               });
                           },
                           success: function(data) {
                               
                               $.LoadingOverlay("hide", {
                                   image: "",
                                   fontawesome: "fa fa-spinner fa-spin",
                               });
                               data = eval(data);
                               if (data.status == 'success') {
                                   alertify.success("Payment was Successfull");
                               }else{
                                  $.confirm({
                                       title: '',
                                       content: data.message,
                                       theme: 'supervan',
                                       backgroundDismiss: true,
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

                            } else {
                                alertify.error("Database Error");
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Error:', error);
                            alertify.error(error);
                        }
                    });
                },
                error: function(xhr, status, error) {
                    alertify.error("Payment Signature Processing Failed");
                    console.error('Error:', error);
                }
            };
            var rzp = new Razorpay(options);
            rzp.on('payment.failed', function(response) {
                alertify.error("Payment Failed. Error Code:" + response.error.code + "," + "Error Description:" + response.error.description);
            });
            rzp.open();
        },
        error: function(xhr, status, error) {
            console.error('Error:', error);
            //alert("Error Try Again Later");
            alertify.error("Error Pls try again later..");

        }
    });
            
                    return false;

                     }
                     return false;
                   }

               });
          }
        //   else
        //   {
        //     $.ajax({
        //         type: "POST",
        //         url: "get_booking_ref.php",
        //         data: formdata,
        //         dataType: "JSON",
        //         async: false, 
        //         beforeSend: function() {
        //             $.LoadingOverlay("show", {
        //                 image: "",
        //                 fontawesome: "fa fa-spinner fa-spin",
        //             });
        //         },
        //         success: function(data) {
        //             $.LoadingOverlay("hide", {
        //                 image: "",
        //                 fontawesome: "fa fa-spinner fa-spin",
        //             });
        //             data = eval(data);
        //             if (data.status == 'success') {
        //                 console.log("afddsfdsf");
        //                 $(".payment_form").submit();
        //             }else{
        //                $.confirm({
        //                     title: '',
        //                     content: data.message,
        //                     theme: 'supervan',
        //                     backgroundDismiss: true,
        //                     buttons: {
        //                         ok: function(){},
        //                     }
        //                 });
        //             }
        //         },
        //         error: function(xhr, desc, err) {
        //             console.log(xhr);
        //             console.log("Details: " + desc + "\nError:" + err);
        //         }
        //     });
        // }
        
        return false;
    });

});
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.10/jquery.mask.js"></script>
<script>
    function calculateHash(type){
        $('.error').hide();
        var flag = validatePaymentForm(type);
        if(!flag) return false;
        if(!$('#termscondi').prop('checked')){
            $.confirm({
               
                title: 'Terms and Conditions',
                content: 'Please click on I Agree All Terms and Conditions.',
                onClose: function () {
                    //$('#termsandcondiscroll').offset().top
                    $("html, body").animate({ scrollTop:  50}, 5000);
                },
                buttons: {
                    ok: function(){
                    }
                }
            });
            return false;
        }
        $(".hotel_form").submit();
     
        return false;
    }
    function validatePaymentForm(type){
        var flag = 0;

        var customer_firstname = $('#customer_firstname').val();
        var customer_lastname = $('#customer_lastname').val();
        var customer_email = $('#customer_email').val();
        var customer_contact = $('#customer_contact').val();
        if(!customer_firstname){
            $('.span_customer_firstname').show();
            flag = 1;
        }
        if(!customer_lastname){
            $('.span_customer_lastname').show();
            flag = 1;
        }
        if(!customer_email){
            $('.span_customer_email').show();
            flag = 1;
        }
        if(!customer_contact){
            $('.span_customer_contact').show();
            flag = 1;
        }
 
        if(flag == 1) return 0;
        else return 1;
    }
  
</script>

<script type="text/javascript">
    


    /* $(".termsandcondis").click(
        function(){
            var text='';
            $.confirm({
                    backgroundDismiss: true,

             columnClass: 'col-md-12',
              icon: 'fa fa-info-circle',
    title: 'Terms and Conditions',
    content:text,
    type: 'blue',
    typeAnimated: true,
   buttons: {
    
        close: {
            btnClass: 'btn-blue',
            action: function(){}
        }
    }
});

        }
    );
*/


$(document).ready(function(){
      $('[data-toggle="popoverc"]').popover({
          placement : 'right',
          align:'left',
           trigger: 'click',
            html: true
      });

      $('.termsandcondis').on('shown.bs.popover', function () {
      $('#message').closest('.popover').addClass('clicked'); //adds class on same element as `.popover`
    });

    $('.termsandcondis').on('hidden.bs.popover', function () {
      $('#message').closest('.popover').removeClass('clicked');
    });

   $('#termscondi').click(function()

   {
         $("#hide_div ").toggle();

   });  
  


 $('.popover_div').popover({
    container: 'body',
    html: true,
    placement: 'auto',
    trigger: 'hover',
    content: function() {
      // get the url for the full size img
      var url = $(this).data('full');
      return '<img src="' + url + '">'
    }
  });




});
</script>
<style>
    @media (max-width:768px){
.col-sm-12,.col-xs-12{padding:  0 2px}
.bkOfferTagWrap{
       display: none;
}

    } 
    
</style>

<?php include('../../../include/footer.php'); ?>


