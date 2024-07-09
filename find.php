<?php 

$currentpage="hotelsearch";

include("include/header.php");

if (!isset($details)) {
$details='';

}

?>

<?php
//include '../config.php';
//include '../include/database/config.php';
error_reporting(0);

$city_name = isset($details[0]['name'])?$details[0]['name']:''.', '.isset($details[0]['state'])?$details[0]['state']:'';
$city_id = isset($details[0]['id_city'])?$details[0]['id_city']:'';

$check_in = date('d-m-Y',strtotime('+24 hours'));
$check_out = date('d-m-Y',strtotime('+48 hours'));

$guest=json_decode('{"1":{"adult":"2","child":"0"}}', true);
$child_age_json = json_decode($_GET['child_age_json'], true);
$child_age_jsonArr = [];
if(isset($child_age_json) && !empty($child_age_json))
  foreach($child_age_json as $child_age_jsonK => $child_age_jsonV)
    foreach($child_age_jsonV as $child_age_jsonVK => $child_age_jsonVV)
      $child_age_jsonArr[$child_age_jsonVK][] = $child_age_jsonVV;
$_SESSION['child_age_jsonArr'] = $child_age_jsonArr;

$searchguest='';
$countadult = $countchild = $roomcount = 0;
$countWhere = $guestCntHidden = '';
foreach($guest as $key=>$val)
{
  $countadult=$val['adult']+$countadult;
  $countchild=$val['child']+$countchild;

  //$countWhere .= ' and (r.selMaxNoOfAdult >= '.$val['adult'].' and r.selMaxNoOfChild >= '.$val['child'].' and r.selMaxNoOfGuest >= '.($val['adult']+$val['child']).')';
  $countWhere .= ' and r.selMaxNoOfGuest >= '.($val['adult']+$val['child']);
  $guestCntHidden .= '<input type=\'Hidden\' name="guest['.$key.'][\'adult\']" value=\''.$val['adult'].'\'><input type=\'Hidden\' name="guest['.$key.'][\'child\']" value=\''.$val['child'].'\'>';
    if($key!=1)
    {
      $searchguest.='<div class="pax_container test pax_container_'.$key.'" data="'.$key.'">';
    }else
    {
      $searchguest.='<div class="pax_container pax_container_'.$key.'" data="'.$key.'">';
    }
    $searchguest.='<div class="detail_pax detail_pax_'.$key.'">';
        if($key!=1)
        {
$searchguest.='<div class="removepax" onclick="removeroom(this)"><i class="fa fa-times" aria-hidden="true"></i></div>';
        }
  
  $searchguest.='<p class="detail_pax_p">Room <span class="roomnumber">'.$key.'</span></p>
        <p style="margin-top:5px;margin-bottom:5px;"><span class="this_adult">'.$val['adult'].'</span> Adults, <span class="this_child">'.$val['child'].'</span>  Child</p>
        </div>
        <div class="content_pax content_pax_'.$key.'" style="display:none;">
        <p class="head_title_room">Adult (+12 yrs)</p>
        <ul class="pagination pagination-sm pagination_lists adultlist adultlist_'.$key.'">
        <li class="'.(($val['adult']==1)?"actives":"").'"onclick="changeadult(1,this)">1</li>
        <li class="'.(($val['adult']==2)?"actives":"").'" onclick="changeadult(2,this)">2</li>
        <li class="'.(($val['adult']==3)?"actives":"").'" onclick="changeadult(3,this)">3</li>
        <li class="'.(($val['adult']==4)?"actives":"").'" onclick="changeadult(4,this)">4</li>
        <li class="'.(($val['adult']==5)?"actives":"").'" onclick="changeadult(5,this)">5</li>
        <li class="'.(($val['adult']==6)?"actives":"").'" onclick="changeadult(6,this)">6</li>
        </ul>
        <p class="head_title_room">Childern (1-12 yrs)</p>
        <ul class="pagination pagination-sm pagination_lists childlist childlist_'.$key.'">
        <li class="'.(($val['child']==0)?"actives":"").'"  onclick="changechild(0,this)">0</li>
        <li class="'.(($val['child']==1)?"actives":"").'" onclick="changechild(1,this)">1</li>
        <li class="'.(($val['child']==2)?"actives":"").'" onclick="changechild(2,this)">2</li>
        <li class="'.(($val['child']==3)?"actives":"").'" onclick="changechild(3,this)">3</li>
        <li class="'.(($val['child']==4)?"actives":"").'" onclick="changechild(4,this)">4</li>

        </ul>
        </div>
        <div class="edit" onclick="editval(this)" data="'.$key.'"><i  style="font-size:10px;" >Edit</i></div>
        <div class="clearfix"></div>
        </div>';
        $roomcount++;

}

$guestcount=$countadult+$countchild;
$text=$key.' Rooms, '.$guestcount.' Guest';
$land_query='';
if($landmark)
{

$land_query=" and l.txtLandmark like '".$landmark."'";
}

$availableRoomsList = $database_hotel->query('select p.selLandmark, l.txtLandmark, p.offer_percentage,p.txtPropertyDescription, p.txtPropertyName,p.selStarRating,p.txtAddress2,p.photo_gallery,p.selImages,r.id_property,r.id_room,r.period_from,r.period_to,rt.periodic_rateExtraBedAdult,rt.rateExtraBedAdult,rt.periodic_rateExtraBedChildMoreThanFive,rt.periodic_rateExtraBedChildLessThanFive,rt.rateExtraBedChildMoreThanFive,rt.rateExtraBedChildLessThanFive,rt.id_room_type from ps_property p left join ps_room r on(p.id_property = r.id_property and r.status=0) left join ps_landmark l on(l.id_landmark=p.selLandmark and l.status=0 and l.action=\'\') left join ps_room_type rt on(r.id_room = rt.id_room)  where 1 '.$countWhere.' and p.status=0 and p.is_delete!=1 '.$land_query)->fetchAll(PDO::FETCH_ASSOC);



$searchMonth  = date('n',strtotime($check_in));
$searchYear   = date('Y',strtotime($check_in));
$searchDate   = date('d',strtotime($check_in));
$searchDate1  = date('d',strtotime($check_out));

$roomRateArr = $propertyRooms = $hotelListArr = array();

if(isset($availableRoomsList) && !empty($availableRoomsList)){
  foreach($availableRoomsList as $incKey => &$roomVal){
    $begin  = new DateTime(date('Y-m-d',strtotime($check_in)));
    //$end  = new DateTime(date('Y-m-d',strtotime($check_out.' +1 day')));
    $end  = new DateTime(date('Y-m-d',strtotime($check_out)));
    $daterange = new DatePeriod($begin, new DateInterval('P1D'), $end);

    // get inventory
    foreach($daterange as $date){
      $dateValue = $date->format("Y-m-d");
      $year = date('Y',strtotime($dateValue));
      $month  = date('n',strtotime($dateValue));
      $dateV  = date('d',strtotime($dateValue));
      $dateV1 = date('j',strtotime($dateValue));

      $inventoryVal = $database_hotel->query('select `'.$dateV1.'` as inventory from ps_room_available_inventory where allot_avail=1 and id_room='.$roomVal['id_room'].' and year='.$year.' and month='.$month)->fetchAll();
      
      if(!isset($inventoryVal[0][0]) || empty($inventoryVal[0][0])){
        unset($availableRoomsList[$incKey]);
        continue 2;
      }elseif(count($guest) > $inventoryVal[0][0]){
        unset($availableRoomsList[$incKey]);
        continue 2;
      }else
        $roomVal['inventory'][] = $inventoryVal[0][0];
    }

    $masterRate = $monthlyRate = $periodicRate = $roomTypeList = array();
    // get rate
    
    $roomTypeArr = $database_hotel->query('select r.id_room,rt.* from ps_room r left join ps_room_type rt on(r.id_room = rt.id_room) where r.id_room='.$roomVal['id_room'].' and rt.id_room_type='.$roomVal['id_room_type'])->fetchAll();
    if(isset($roomTypeArr) && !empty($roomTypeArr)){
      foreach($roomTypeArr as $roomTypeKey => $roomTypeVal){
        if((int)$roomTypeVal['is_breakfast'] == 1)
          $roomVal['is_breakfast'] = 1;
        $masterRate[0] = $roomTypeVal['rate_sun'];
        $masterRate[1] = $roomTypeVal['rate_mon'];
        $masterRate[2] = $roomTypeVal['rate_tue'];
        $masterRate[3] = $roomTypeVal['rate_wed'];
        $masterRate[4] = $roomTypeVal['rate_thu'];
        $masterRate[5] = $roomTypeVal['rate_fri'];
        $masterRate[6] = $roomTypeVal['rate_sat'];
      }
    }
    $rateValue = 0;
    foreach($daterange as $dateK => $date){
      $rateRoomType = array();
      $dateValue = $date->format("Y-m-d");
      $year = date('Y',strtotime($dateValue));
      $month  = date('n',strtotime($dateValue));
      $dateV  = date('d',strtotime($dateValue));
        
      $periodicRateList = $database_hotel->query('select * from ps_room_periodic_rate where id_room='.$roomVal['id_room'].' and id_room_type='.$roomVal['id_room_type'].' and \''.$dateValue.'\' >= period_from and \''.$dateValue.'\' <= period_to')->fetchAll();

      $periodicRate = [];
      if(isset($periodicRateList) && !empty($periodicRateList))
        foreach($periodicRateList as $periodicRateKey => $periodicRateVal){
          $periodicRate[0] = $periodicRateVal['periodic_rate_sun'];
          $periodicRate[1] = $periodicRateVal['periodic_rate_mon'];
          $periodicRate[2] = $periodicRateVal['periodic_rate_tue'];
          $periodicRate[3] = $periodicRateVal['periodic_rate_wed'];
          $periodicRate[4] = $periodicRateVal['periodic_rate_thu'];
          $periodicRate[5] = $periodicRateVal['periodic_rate_fri'];
          $periodicRate[6] = $periodicRateVal['periodic_rate_sat'];
        }

      if(isset($periodicRate[date('w',strtotime($dateValue))]) && !empty($periodicRate[date('w',strtotime($dateValue))]))
        $rateValue += $periodicRate[date('w',strtotime($dateValue))];
      else if(isset($masterRate[date('w',strtotime($dateValue))]) && !empty($masterRate[date('w',strtotime($dateValue))]))
        $rateValue += $masterRate[date('w',strtotime($dateValue))];
    }
//echo $roomVal['id_room'].'_'.$roomVal['id_room_type'].'__'.$rateValue;
    $extraRate = 0;
    if(isset($guest) && !empty($guest)){
      foreach($guest as $guestkey => $guestval){

        if($guestkey>1) $extraRate += $rateValue;
        if($guestval['adult'] > $roomVal['id_room_type']){
          $extraAdult = $guestval['adult'] - $roomVal['id_room_type'];
          foreach($daterange as $dateK => $date){
            $dateValue = $date->format("Y-m-d");

            if($dateValue >= $roomVal['period_from'] && $dateValue <= $roomVal['period_to'] && isset($roomVal['periodic_rateExtraBedAdult']) && !empty($roomVal['periodic_rateExtraBedAdult']))
              $extraRate += $extraAdult * $roomVal['periodic_rateExtraBedAdult'];
            else
              $extraRate += $extraAdult * $roomVal['rateExtraBedAdult'];
          }
        }
        if($guestval['child'] > 0){
          $extraAdult = $guestval['child'];
          foreach($daterange as $dateK => $date){
            $dateValue = $date->format("Y-m-d");
            if($dateValue >= $roomVal['period_from'] && $dateValue <= $roomVal['period_to']){
              for($extraAdultInc=0; $extraAdultInc<$extraAdult; $extraAdultInc++){
                if($child_age_jsonArr[$extraAdultInc+1][$extraAdultInc] > 5){
                  $childRate = $roomVal['periodic_rateExtraBedChildMoreThanFive'];
                  $childRate1 = $roomVal['rateExtraBedChildMoreThanFive'];
                }else{
                  $childRate = $roomVal['periodic_rateExtraBedChildLessThanFive'];
                  $childRate1 = $roomVal['rateExtraBedChildLessThanFive'];
                }
                if(isset($childRate) && !empty($childRate))
                  $extraRate += $childRate;
                else
                  $extraRate += $childRate1;
              }
            }else{
              for($extraAdultInc=0; $extraAdultInc<$extraAdult; $extraAdultInc++){
                if(isset($child_age_jsonArr[$extraAdultInc+1][$extraAdultInc]) && $child_age_jsonArr[$extraAdultInc+1][$extraAdultInc] > 5)
                  $childRate = $roomVal['rateExtraBedChildMoreThanFive'];
                else
                  $childRate = $roomVal['rateExtraBedChildLessThanFive'];
              }
              $extraRate += $childRate;
            }
          }
        }
      }
    }

    if(!isset($rateValue) || empty($rateValue)){
      unset($availableRoomsList[$incKey]);
      continue;
    }
    $rateValue = $rateValue + $extraRate;
    $roomVal['rate'] = $rateValue;
//echo '__'.$extraRate.'__'.$roomVal['rate'].'<br>';    
    if(isset($rateValue) && !empty($rateValue)){
      if(!isset($propertyRooms[$roomVal['id_property']]['rate']) || (isset($propertyRooms[$roomVal['id_property']]['rate']) && $rateValue < $propertyRooms[$roomVal['id_property']]['rate'])){
        $propertyRooms[$roomVal['id_property']]['id_room'] = $roomVal['id_room'].'_'.$roomVal['id_room_type'];
        $propertyRooms[$roomVal['id_property']]['rate'] = $rateValue;


      }
    }
  }
}

$price = array();
if(isset($availableRoomsList) && !empty($availableRoomsList)){
  foreach ($availableRoomsList as $keyRoom => $row)
  {
    $price[$keyRoom] = $row['rate'];
  }
  array_multisort($price, SORT_ASC, $availableRoomsList);
}

$hotelConfig = array();
$getHotelConfigValues = $database->query("select name,value from ps_configuration where name = 'ps_hotel_service_tax' or  name = 'ps_hotel_commission'")->fetchAll();
if(isset($getHotelConfigValues) && !empty($getHotelConfigValues)){
  foreach($getHotelConfigValues as $hotelVal){
    $hotelConfig[$hotelVal['name']] = $hotelVal['value'];
  }
}
$ps_hotel_commission = (isset($hotelConfig['ps_hotel_commission']) && !empty($hotelConfig['ps_hotel_commission']) ? $hotelConfig['ps_hotel_commission'] : 0);
$ps_hotel_service_tax = (isset($hotelConfig['ps_hotel_service_tax']) && !empty($hotelConfig['ps_hotel_service_tax']) ? $hotelConfig['ps_hotel_service_tax'] : 0);

if(isset($availableRoomsList) && !empty($availableRoomsList)){
  foreach($availableRoomsList as $incKey1 => &$roomVal1){
    if(isset($propertyRooms[$roomVal1['id_property']]['id_room']) && $roomVal1['id_room'].'_'.$roomVal1['id_room_type'] != $propertyRooms[$roomVal1['id_property']]['id_room'])
      unset($availableRoomsList[$incKey1]);
    else{
      // if inventory, but no rate, then skip
      if(!isset($roomVal1['rate']) || empty($roomVal1['rate']))
        continue;
      $hotelListArr[$roomVal1['id_property']]['price'] = $roomVal1['rate'];
      $hotelListArr[$roomVal1['id_property']]['offer_percentage'] = $roomVal1['offer_percentage'];
      $hotelListArr[$roomVal1['id_property']]['offer_percent']='';
      $hotelListArr[$roomVal1['id_property']]['txtPropertyName']  = $roomVal1['txtPropertyName'];
      $hotelListArr[$roomVal1['id_property']]['txtPropertyDescription'] = $roomVal1['txtPropertyDescription'];
      $hotelListArr[$roomVal1['id_property']]['selStarRating']  = $roomVal1['selStarRating'];
      $hotelListArr[$roomVal1['id_property']]['selLandmark']    = $roomVal1['txtLandmark'].', '.$city_name;
      
      if(isset($roomVal1['selImages']) && !empty($roomVal1['selImages'])){
         $jsonImg = json_decode($roomVal1['selImages']);
         $firstImage = current(current($jsonImg));
         if(isset($firstImage) && !empty($firstImage))
           $hotelListArr[$roomVal1['id_property']]['photo_gallery'] = _BO_HOTEL_IMG_DIR_.'property/'.$roomVal1['id_property'].'/'.$firstImage;
      }
      
      $propertyAmenitiesArr = $database_hotel->query('select * from ps_property_facility where id_property='.$roomVal1['id_property'])->fetchAll();
      $availableAmenities = array_keys($propertyAmenitiesArr[0], 1);
      foreach($availableAmenities as $availableAmenitiesK => $availableAmenitiesV)
        if($availableAmenitiesV == 'id_property_facility' || $availableAmenitiesV == 'id_property' || is_numeric($availableAmenitiesV))
          unset($availableAmenities[$availableAmenitiesK]);
      if(isset($availableAmenities) && !empty($availableAmenities))
        $hotelListArr[$roomVal1['id_property']]['amenities'] = $availableAmenities;
      
      /*$tds_amt = $hotelListArr[$roomVal1['id_property']]['price'] * ($ps_hotel_service_tax/100);
      $commission_amt = $hotelListArr[$roomVal1['id_property']]['price'] * ($ps_hotel_commission/100);
      $hotelListArr[$roomVal1['id_property']]['price'] = round(($hotelListArr[$roomVal1['id_property']]['price'] - $commission_amt) + $tds_amt);*/


      
      $hotelListArr[$roomVal1['id_property']]['offer_price']='';
      if($hotelListArr[$roomVal1['id_property']]['price'] % 2 != 0)
        $hotelListArr[$roomVal1['id_property']]['price'] = $hotelListArr[$roomVal1['id_property']]['price']+1;
      if($hotelListArr[$roomVal1['id_property']]['offer_percentage']!='')
      {       
        $offer_percentage=$hotelListArr[$roomVal1['id_property']]['offer_percentage'];
        $bf=($hotelListArr[$roomVal1['id_property']]['price']/(100+$offer_percentage))*100;     
        $offerrate=round($hotelListArr[$roomVal1['id_property']]['price']-$bf);  
        $hotelListArr[$roomVal1['id_property']]['offer_price'] =round($hotelListArr[$roomVal1['id_property']]['price']-$offerrate); 

        $hotelListArr[$roomVal1['id_property']]['offer_percent']=round(($offerrate/$hotelListArr[$roomVal1['id_property']]['price'])*100);



      }
    }
  }
}

$cityNames = $database->query("SELECT c.id_city,c.name,s.name as stateName from ps_city c left join ps_state s on(s.id_state = c.id_state) where c.status=0 and is_for_hotel=1")->fetchAll();
if(isset($cityNames) && !empty($cityNames)){
  foreach($cityNames as $cityK => $city){
    $cityNamesArr[$cityK]['id']   = $city['id_city'];
    $cityNamesArr[$cityK]['label']  = $city['name'];//.', '.$city['stateName'];
  }
}
?>


<section id="bus_tick">
  <div class="container">
  <div class="row">

    <div class="bus_tickets" >
        <div class="col-sm-12  col-md-12 col-lg-12 col-xs-12 checkins">
    <form class="hotel_search" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get" id="hotel_search">
            <div class="col-xs-12 col-sm-2">
                <input type="text" class="form-control" required placeholder="City, Area or Hotel Name" id="to1" autocomplete="off" name="goingto" value="<?php echo (isset($city_name) && !empty($city_name) ? $city_name : ''); ?>" />
        <input type="hidden" id="to_hidden1" name="to_hidden" value="<?php echo (isset($city_id) && !empty($city_id) ? $city_id : ''); ?>">
            </div>
            <div class="col-xs-12 col-sm-2 ">
                <input type="text" class="form-control" placeholder="Select date" id="hotels_month" name="checkin" value="<?php echo (isset($check_in) && !empty($check_in) ? $check_in : ''); ?>">
            </div>
            <div class="col-xs-12 col-sm-2 ">
                <input type="text" class="form-control" placeholder="Select date" id="hotels_month1" name="checkout" value="<?php echo (isset($check_out) && !empty($check_out) ? $check_out : ''); ?>">
            </div>

            <div class="col-xs-12 col-sm-3 " style="margin-bottom: 10px;">
        <div class="section_room" style="position: relative">
        <input type="hidden" id="guest" name="guest" value="">
        <input type="hidden" id="guestval" value="<?php echo $guestcount?>">
        <input type="hidden" id="roomval" value="<?php echo $key?>">

        <button  class="btn-custom-collapse" type="button" style=" " data-toggle="collapse"  data-target="#demo"><span class="total_guest_room"><?php echo $text;?></span></button>

        <div class="collapse"  style="width:100%;margin-top:10px;z-index:9999;box-shadow:0 0 16px 10px rgba(0,0,0,.19);background-color:white;padding:10px;
        color:black!important;position:absolute;" id="demo" >
        <div class="outer_div_search"><div class="inner_demo outer_pax">
        <?php echo $searchguest;?>
        </div>
        <div class="con_for_but"></div>
        <button class="rooms_in_hotel" data="<?php echo $key+1; ?>" type="button" onclick="addroom(this)" >Add Room</button>
        <span class="done" onclick="ondone()">Done</span>

        </div></div>
        </div>  
        
            </div>
            <div class="clearfix visible-xs-block"></div>
                        <div class="col-sm-3">
               
          <input type="hidden" class="child_age_json" name="child_age_json" value="">
                    <input type="submit" value="Modify Search" class="btn-cus-submit btn btn-primary" />
                
            </div>

           </form>

        </div>
    </div>

    </div>
    </div>  
    </section>
   

   

     





</div>
</div>






       <div class="topnav1">
      <div class="container" >
          <div class="col-xs-12 col_inneratag"  >
            
              
              <span style="float:left;padding: 9px 0px;" >
                <a class="values_cityname"  href="<?php echo $root_dir?>" src="">Home |</a>

                <a class="values_cityname1" href="#" src=""><?php echo $city_name; ?> </a> </span>


              <span style="position: relative;"><span   class="fa fa-search manuals_searchhotel" aria-hidden="true"></span>
                    <input type="text" class="but" id="filterHotelName" placeholder="Enter Hotel name"></span>
                      </div>
            



            <div class="col-xs-12" style="z-index:1; padding:0px ;display: none;" >
        <div class=" col-xs-12 col-sm-8  filter ">
            <ul>
                
              <li >
                    <div class="dropdown col-xs-12">
                        <button class="btn btn-default dropdown-toggle but" type="button" data-toggle="dropdown">Landmark
              <span class="caret caret1"></span></button>
                        <ul class="dropdown-menu new_drop_menu" style="width:auto; height:150px; overflow: auto;">
                           <?php $LandmarkList = $database->query("SELECT * from ps_landmark where selCityId='".$city_id."' and status=0 and action=''")->fetchAll(PDO::FETCH_ASSOC);
                           $land='';
                if(isset($LandmarkList) && !empty($LandmarkList)){
                  foreach($LandmarkList as $LandmarkK => $Landmarkvalue){
                    $land.=' <li><input type="checkbox" data-sort-type="landmark" data-value="'.$Landmarkvalue['id_landmark'].'"><a href="#"><label>'.$Landmarkvalue['txtLandmark'].'</label></a></li>';
                  }
                }
                echo $land;
              ?>

                           
                        </ul>
                    </div>
                </li>
                <li >
                    <div class="dropdown col-xs-12">
                        <button class="btn btn-default dropdown-toggle but" type="button" data-toggle="dropdown">Amentities
    <span class="caret"></span></button>
                        <ul class="dropdown-menu new_drop_menu" style="width:auto; height:150px; overflow: auto;">
                            <li><input type="checkbox" data-sort-type="amentities" data-value="business_center"><a href="#"><label>Business Services</label></a></li>
                            <!--<li><a href="#"><label><input type="checkbox" data-sort-type="amentities" data-value="internet#internet_access_in_rooms">Free Internet</label></a></li>-->
                            <li><input type="checkbox" data-sort-type="amentities" data-value="front_desk_24_hours#front_desk"><a href="#"><label>Front desk</label></a></li>
              <li><input type="checkbox" data-sort-type="amentities" data-value="travel_desk"><a href="#"><label>Travel Desk</label></a></li>
              <li><input type="checkbox" data-sort-type="amentities" data-value="internet#internet_access_in_rooms"><a href="#"><label>Internet</label></a></li>
              <li><input type="checkbox" data-sort-type="amentities" data-value="bar"><a href="#"><label>Bar</label></a></li>
                            <li><input type="checkbox" data-sort-type="amentities" data-value="laundry"><a href="#"><label>Laundry Service</label></a></li>
              <li><input type="checkbox" data-sort-type="amentities" data-value="free_parking#bus_parking#indoor_parking#outdoor_parking#parking#valet_parking"><a href="#"><label>Parking Facility</label></a></li>
                            <li><input type="checkbox" data-sort-type="amentities" data-value="restaurant#coffee_shop"><a href="#"><label>Restaurant/Coffee Shop</label></a></li>
                            <li><input type="checkbox" data-sort-type="amentities" data-value="room_service#room_service_24_hours"><a href="#"><label>Room Service</label></a></li>
              <li><input type="checkbox" data-sort-type="amentities" data-value="spa#ayurveda_spa"><a href="#"><label>Spa</label></a></li>
                            <li><input type="checkbox" data-sort-type="amentities" data-value="swimming_pool"><a href="#"><label>Swimming Pool</label></a></li>
                            <li><input type="checkbox" data-sort-type="amentities" data-value="disco#casino#night_club#squash#exercise_gym#bowling"><a href="#"><label>Indoor Entertainment</label></a></li>
                            <li><input type="checkbox" data-sort-type="amentities" data-value="hiking#horseback_riding#snow_skiing#volleyball#exercise_gym#bowling#basketball_court#boating#fishing#golf_driving_range#golf_putting_green#jogging_track#skiing_snow#tennis"><a href="#"><label>Outdoor Activities</label></a></li>
                        </ul>
                    </div>
                </li>
                <li >
                    <div class="dropdown col-xs-12">
          <button class="btn btn-default dropdown-toggle but" type="button" data-toggle="dropdown">Star Rating
               <span class="caret caret1"></span></button>
                        <ul class="dropdown-menu new_drop_menu">
                            <li><input type="checkbox" id="1_star" data-sort-type="rating" data-value="1"><a href="#"><label for="1_star">1 Star</label></a></li>
                            <li><input type="checkbox" id="2_star" data-sort-type="rating" data-value="2"><a href="#"><label for="2_star">2 Star</label></a></li>
                            <li><input type="checkbox" id="3_star" data-sort-type="rating" data-value="3"><a href="#"><label for="3_star">3 Star</label></a></li>
                            <!--<li class="divider"></li>-->
                            <li><input type="checkbox" id="4_star" data-sort-type="rating" data-value="4"><a href="#"><label for="4_star">4 Star</label></a></li>
                            <li><input type="checkbox" id="5_star" data-sort-type="rating" data-value="5"><a href="#"><label for="5_star">5 Star</label></a></li>
                        </ul>
                    </div>
                </li>
                
                <li >
                    <div class="dropdown col-xs-12">
                        <button class="btn btn-default dropdown-toggle but" type="button" data-toggle="dropdown">Hotel Type
    <span class="caret caret1"></span></button>
                        <ul class="dropdown-menu new_drop_menu" style="width:auto; height:150px; overflow: auto;">
                            <li><input type="checkbox" data-sort-type="hoteltype" data-value="1"><a href="#"><label>Hotel</label></a></li>
                            <li><input type="checkbox" data-sort-type="hoteltype" data-value="2"><a href="#"><label>Resort</label></a></li>
                            <li><input type="checkbox" data-sort-type="hoteltype" data-value="3"><a href="#"><label>Apartment</label></a></li>
                            <li><input type="checkbox" data-sort-type="hoteltype" data-value="4"><a href="#"><label>Villa</label></a></li>
              <li><input type="checkbox" data-sort-type="hoteltype" data-value="5"><a href="#"><label>Homestay</label></a></li>
                            <li><input type="checkbox" data-sort-type="hoteltype" data-value="6"><a href="#"><label>Dormitory</label></a></li>
                        </ul>
                    </div>
                </li>
                
                <li>
                    <div class="dropdown col-xs-12 ">
                        <button class="btn btn-default dropdown-toggle but" type="button" data-toggle="dropdown">Price range
                           <span class="caret caret1"></span></button>
                        <ul class="dropdown-menu new_drop_menu">
              
              <li><input type="checkbox" data-sort-type="price" data-value="999"><a href="#"><label> Upto <i class="fa fa-rupee"></i> 999</label></a></li>
                            <li><input type="checkbox" data-sort-type="price" data-value="1000#3000"><a href="#"><label> <i class="fa fa-rupee"></i> 1000 to <i class="fa fa-rupee"></i> 3000</label></a></li>
                            <li><input type="checkbox" data-sort-type="price" data-value="3000#5000"><a href="#"><label> <i class="fa fa-rupee"></i> 3000 to <i class="fa fa-rupee"></i> 5000</label></a></li>
                            <li><input type="checkbox" data-sort-type="price" data-value="5000#10000"><a href="#"><label> <i class="fa fa-rupee"></i> 5000 to <i class="fa fa-rupee"></i> 10000</label></a></li>
                        </ul>
                    </div>
                </li>
                <!--<li>
                    <div class="dropdown col-xs-12">
                        <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown">Chain
    <span class="caret"></span></button>
                        <ul class="dropdown-menu">
                            <li><a href="#"><label><input type="checkbox">single</label></a></li>
                            <li><a href="#"><label><input type="checkbox">double</label></a></li>
                            <li><a href="#"><label><input type="checkbox">triple</label></a></li>
                            <li><a href="#"><label><input type="checkbox">triple</label></a></li>
                        </ul>
                    </div>
                </li>-->
            </ul>
        </div>


        <div class="col-xs-12 col-sm-4 find_location">
            <!-- <div class="col-md-3 col-sm-3 col-xs-12">
                <div class="form-group form-inline">
                    <span class="glyphicon glyphicon-map-marker"></span>
                    <input type="text" class="form-control" id="pwd" placeholder="Find By Location">
                </div>
            </div> -->
            <div class="col-md-12 col-sm-12 col-xs-12" >
                <div class="form-group form-inline ">
                    <span class="fa fa-search" aria-hidden="true"></span>
                    <!--<label for="pwd"><i class="fa fa-search" aria-hidden="true"></i> Find By Hotel Name:</label>-->
                    <input type="text" class="but" id="filterHotelName" placeholder="Enter Hotel name">
                </div>
            </div>


        </div>
     </div> 
 </div>
    </div>

<div class="container full_conta " id="full_conta_sty">
<div class="col-sm-12" style="">
    <div class="col-sm-12 terms_privacy_inner" style="margin-bottom:20px;padding-bottom:20px;    box-shadow: 1px 1px 8px #ccc;border: 1px solid #ccc;">
      <div class="">
         <?php $content="<h1>".(isset($search)&&$search!=''?ucwords($search):'Hotels')." in [destination], ".$details[0]['state'].(isset($landmark)&&$landmark!=''?', '.$landmark:'')."</h1><p>[keyword] Hotels in [destination], find the best hotel services in budget and standard [keyword] hotels in [destination], online [keyword] hotel booking in [destination] helps you find budget friendly resorts, service apartments, dormitories, guest houses etc. Book a hotel @ Staysinn.com</p><div class='complete' style='display:none;'><p>Staysinn.com is the contemporary establishment of Buddies Group. Staysinn.com is a user friendly online hotel booking portal that enables the customers to book any kind of hotels all over India. Staysinn.com provides an on-set of various kinds of stay options of hotels in [destination] such as resorts, service apartments, hotels, dormitories, budget and luxury hotels to our customers and helps our customers to pick the best of hotels in [destination] at any point of the day. Our attentive team of skilled and responsive representatives helps the customers to make bookings even in the eleventh hour with absolute ease (24 X 7). We have been of absolute preference from clients all over the country. We have endeavored to provide impeccable services to our clients from the hospitality industry as well as the general public in providing hotels in [destination]. We have respectful affinity with places ranging from luxurious resorts to budget hotels.</p><p>Avail the best kind of hotels in the [destination] and en route from your travelling place to any given [destination] that pleases your penchant. You can easily book hotel in [destination] using staysinn.com 24 X 7 without any hassle using our user friendly booking interface. </p><p>With our voluminous range of hotels, you can now choose the best of the hotels in any [destination] just with few simple clicks and enjoy the trip with friends and families.</p><p>Economical rates with an extensive range of hotels in [destination] are provided through staysinn.com and a calm and serene stay at any assigned haven. Having been in the globe-trotting industry for quite a few years Buddies tours has acquired the best of the good will of the hoteliers all over the country and we solely choose our customers to be our top-most priority.</p><h2>Budget & Standard Hotels in [destination]</h2><p>Using staysinn.com one can avail multiple options of various types of hotels. Staysinn.com acts as a channel to provide accommodations to book from resorts, luxury hotels, and guest houses to service apartments, budget hotels and dormitories. Booking a hotel through our portal helps you find the best hotels in [destination] at economical rates providing you the best service at the same time.</p><p>The staysinn.com website helps in booking the hotel in very effortlessly. Online hotel booking for budget hotels, cheap hotels and the best hotel deals are provided through staysinn.com. </p><p>We have special offers during the holiday seasons throughout India. We provide you the excellent hotel deals with exciting hotel deals. With the help of staysinn.com you can find the best hotel rooms to the cheap hotel rooms according to your budget. Finding cheap hotel accommodation, room booking with easy access to all the tourist attractions at any [destination] is possible through our portal.</p><h2>Online Hotel Booking</h2><p>At staysinn.com, you will have multiple options to choose from distinguished hotel deals at any given point from on-set point to the [destination].</p><p>Staysinn.com provides the best renowned luxurious hotels at a [destination] and the cheap hotel rooms providing hospitality services to micro, meso and macro levels of the society.</p><h2>Best Hotel Services</h2><p>Staysinn.com is known in the market for strong service ethics and best value for money package. Book a hotel in staysinn.com, we assure to provide best services, responsive team to attend to all your queries at all times, budget friendly rates and option to choose from a multitude of hotels. </p><p>Room booking at a [destination] through our staysinn.com ensures an enjoyable time.</p>
";
$content=str_replace("[keyword]",ucwords($search),$content);
echo str_replace("[destination]",ucwords($destination),$content);

?>
 
</div>
<span class="testvalues">Read More...</span> 
    </div>
  </div>
</div>


    <div class="col-md-12 col-xs-12 col-lg-12 col-sm-12">






  <input type="Hidden" id="goingto" value="<?php echo $city_name; ?>">
  <input type="Hidden" id="to_hidden" value="<?php echo $city_id; ?>">
  <input type="Hidden" id="hotel_month" value="<?php echo $check_in; ?>">
  <input type="Hidden" id="hotel_month1" value="<?php echo $check_out; ?>">
<div class="col-md-12 col-xs-12 col-lg-12 col-sm-12 hoteldetails" id="hoteldetails" style="padding:0;" >
  <div class="col-sm-2 hotelde_inner " >

     
<div class=" hotelde_inns">     
<h6>All Locations In <?php echo $city_name; ?></h6>
<ul id="Locations">
  
<?php $LandmarkList = $database->query("SELECT * from ps_landmark where selCityId='".$city_id."' and status=0 and action=''")->fetchAll(PDO::FETCH_ASSOC);
$land='';
  if(isset($LandmarkList) && !empty($LandmarkList)){
    foreach($LandmarkList as $LandmarkK => $Landmarkvalue){
      $land.=' <li><a href="#"><label>'.$Landmarkvalue['txtLandmark'].'</label></a></li>';
    }
  }
  echo $land;
?>

                           
 
  
</ul>
<button type="button"  class="show_all_btn" id="show_all_btn1">Show All Locations</button>
</div>
<div class=" hotelde_inns">     
<h6>Attractions In <?php echo $city_name; ?></h6>
<ul id="Attractions">
  <?php 
  $AttractionsList = $database->query("SELECT * from ps_attractions where selCityId='".$city_id."' and status=0 and action=''")->fetchAll(PDO::FETCH_ASSOC);
$att='';
  if(isset($AttractionsList) && !empty($AttractionsList)){
    foreach($AttractionsList as $AttractionsK => $Attractions){
      $att.=' <li><a href="#"><label>'.$Attractions['txtAttractions'].'</label></a></li>';
    }
  }
  echo $att;
?>

        
  
</ul>
<button type="button"  class="show_all_btn" id="show_all_btn2">Show All Attractions</button>
</div>





     
     </div>
      <div class="col-sm-10  col_padhot" >


    <?php 
      if(isset($hotelListArr) && !empty($hotelListArr)){
        $_SESSION['hotelListArr']=$hotelListArr;
        foreach($hotelListArr as $id_property => $hotels){
          //echo '<pre>';print_r($hotels);echo '</pre>';
    ?>
     


        


          
           <div class="full_page1"> 
   <div class=" col-xs-12  col-sm-4 imag" style="padding: 0px;">
      <div class="custom-hotelimage">
        <a class="fancybox" href="<?php echo (isset($hotels['photo_gallery']) && !empty($hotels['photo_gallery']) ? $hotels['photo_gallery'] : $root_dir.'hotels/search/images/img1.jpg'); ?>">
        <img class="hotelimage " src="<?php echo (isset($hotels['photo_gallery']) && !empty($hotels['photo_gallery']) ? $hotels['photo_gallery'] : $root_dir.'hotels/search/images/img1.jpg'); ?>"></a>
       <!--   <a class="custom-hotelimagetitle "><?php //echo $hotels['txtPropertyName']; ?></a> -->
      </div>
   </div>
 
   <div class="image_content">
    <div class="col-sm-8 hotel_detail" style="position:relative;">
          <!-- <?php if($hotels['offer_percent']!='')
        { ?>
        <div class="offer_pers"><?php echo $hotels['offer_percent']; ?>%</div>
      <?php }?> -->
        <div class="row">
         <div class="col-sm-8" style="margin-bottom: 10px;">
         <p style="font-size:18px;color:#2c67b3;margin-bottom: 5px;text-transform: capitalize;">

          <?php echo $hotels['txtPropertyName']; ?> 


           <span  style="float:right;" class="stars">
               <?php for($starI=0; $starI<$hotels['selStarRating']; $starI++){ ?>
               <span class="glyphicon glyphicon-star"></span>
               <?php } ?>
               <!--<span class="glyphicon glyphicon-star"></span><span class="glyphicon glyphicon-star"></span><span class="glyphicon glyphicon-star"></span><span class="glyphicon glyphicon-star"></span>-->
            </span>
         </p>
        
          <p  style="margin-bottom: 5px;" class=" areaname allpara"><i class="fa fa-map-marker" aria-hidden="true"></i><?php echo $hotels['selLandmark']; ?> </p>


            
             </div>
         
           <?php if($hotels['offer_price']!=''){ ?> 


           <div class="col-sm-12 text-right"  style="">
            <div class="col-sm-12 text-right"  style="">
            <p  class=" bookingprice"><!-- <span style="font-size: 14px;">Offer price</span> --><span style='display:inline-block;'>&nbsp;
            <i class="fa fa-rupee"><span><?php echo $hotels['offer_price'];?></span></i></span></p><!--<span style="font-size:8px;color: #a0a0a0;">(Excl. taxes)</span>-->
        </div>

          </div>
        <?php } ?>
        
     </div>
     
         <div class="actual"> 
           <?php if($hotels['offer_price']!=''){ ?>
         
            <p class="bookingprice1" ><!-- <span style="font-size:14px">Actual price</span><span >&nbsp;</span> -->
           <i class="fa fa-rupee"><strike><span class=""><?php echo $hotels['price']?></span></strike></i></p>
           <?php }else{ ?>

            <p class="bookingprice">
           <i class="fa fa-rupee"><span ><?php echo $hotels['price']?></span></i></p>

          <?php } ?>
         </div>

         <div class="clearfix"></div>
          <div class="col-sm-offset-8 col-sm-4 " >
            
          </div>
                <div class="clearfix"></div>
         <div class="col-sm-8 " style="padding:0 0;">
            <div style="margin-top:5px;" class=" icons_all">
               <a href="#" data-toggle="tooltip" title="Room Service">
               <i class="material-icons"<?php if(!in_array('room_service',$hotels['amenities']) && !in_array('room_service_24_hours',$hotels['amenities'])) echo 'style="color:#e9e9e9;"'; ?>>room_service</i>
               </a>
               <a href="#" data-toggle="tooltip" title="Gym / Spa">
               <i class="material-icons"<?php if(!in_array('exercise_gym',$hotels['amenities']) && !in_array('spa',$hotels['amenities'])) echo 'style="color:#e9e9e9;"'; ?>>fitness_center</i>
               </a>
               <a href="#" data-toggle="tooltip" title="Swimming Pool">
               <i class="material-icons"<?php if(!in_array('swimming_pool',$hotels['amenities'])) echo 'style="color:#e9e9e9;"'; ?>>pool</i>
               </a>
               <a href="#" data-toggle="tooltip" title="Wi-fi">
               <i class="fa fa-wifi" aria-hidden="true"<?php if(!in_array('internet_access_in_rooms',$hotels['amenities'])) echo 'style="color:#e9e9e9;"'; ?>></i>
               </a>
               <a href="#" data-toggle="tooltip" title="Restaurant">
               <i class="material-icons"<?php if(!in_array('restaurant',$hotels['amenities'])) echo 'style="color:#e9e9e9;"'; ?>>restaurant</i>
               </a>
               <a href="#" data-toggle="tooltip" title="Internet Access">
               <i class="material-icons"<?php if(!in_array('internet',$hotels['amenities'])) echo 'style="color:#e9e9e9;"'; ?>>desktop_windows</i>
               </a>
            </div>
            </div>




         <div >
         
            <div class=" col-sm-4 icons_all" >
               <form class="book-hotel-form" name="hotel_book" action="book.php">
                  <input type="Hidden" name="id_property" value="<?php echo $id_property; ?>">
                  <input type="Hidden" name="goingto" value="<?php echo $city_name; ?>">
                  <input type="Hidden" name="to_hidden" value="<?php echo $city_id; ?>">
                  <input type="Hidden" name="hotel_month" value="<?php echo $check_in; ?>">
                  <input type="Hidden" name="hotel_month1" value="<?php echo $check_out; ?>">
          <?php echo (isset($guestCntHidden) ? $guestCntHidden : ''); ?>
          <input type="hidden" class="child_age_json" name="child_age_json" value="">
                 <!--  <input type="submit" class="btn btn-default diagonal" name="hotel_book" value="Book Now"> -->
                 <button type="submit" class="btn  btnBook1 diagonal" name="hotel_book" value="Book Now"> Book Now</button>
               </form>
            </div>


        
         </div>


<div class="clearfix"></div>

             <div class="col-sm-12" style="padding:0 15px 0 0 ;position: relative; " >
              <div style="background-color:#f5f5f5;line-height:2;padding:5px;font-size:10px;margin-top:10px;overflow:hidden;height:65px">

                

            <?php echo $hotels['txtPropertyDescription']; ?>
            </div>
            <!-- <p style="right:15px;font-size:10px;position: absolute;">Read More</p> -->

            </div>
          
        
      </div>

       <div class="clearfix"></div>
         
   </div>
   </div>

      <?php }}else{ ?>
        <div class="col-xs-12 text-center"><label>No Results Found</label></div>
      <?php } ?>
</div>
   </div>
              
      
    </div>

</div>
<script type="text/javascript">

var leavingfrom = <?php echo json_encode($cityNamesArr); ?>;

$(document).ready(function() {
  $( "#to1" ).autocomplete(
  {
        source: function( request, response ) 
        {
           var matcher = new RegExp( "^" + $.ui.autocomplete.escapeRegex( request.term ), "i" );
           response( $.grep( leavingfrom, function( item )
           {
            return matcher.test( item.label );
           }) );
        },
        select:function(event,ui)
        {
         $("#to_hidden1").val(ui.item.id);
         $("#hotels_month").focus();
        },
        minLength:2,
        scroll:true, 
  });
});

$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip({ tooltipClass:"tooltip_custom"}
       

      );
  //$("#to1").bind('focus', function(){ $(this).autocomplete("search"); } );
});
</script>

    <?php include('../include/footer.php'); ?>
    <script>


function changeadult(value,identifier)
{
   var guest=0;
   var currentpax=$(identifier).closest(".pax_container").attr("data");
   $(".adultlist_"+currentpax+" li").removeClass("actives");
   $(identifier).addClass("actives");

   $('.actives').each(function(){
   guest=guest+parseInt($(this).text());
   });
  var room=$("#roomval").val();

   $('.detail_pax_'+currentpax+' .this_adult').text(value);
   $('.total_guest_room').text(room+' Room, '+guest+' Guests');

}
function changechild(value,identifier)
{
   var guest=0;
  
  
   var currentpax=$(identifier).closest(".pax_container").attr("data");
   $(".childlist_"+currentpax+" li").removeClass("actives");
   $(identifier).addClass("actives");

   $('.actives').each(function(){
   guest=guest+parseInt($(this).text());
   });
  var room=$("#roomval").val();
    $('.detail_pax_'+currentpax+' .this_child').text(value);
   $('.total_guest_room').text(room+' Room, '+guest+' Guests');
   
  $('.child_age_div_'+currentpax).remove();
  var ageSelect = '<div class="child_age_div_'+currentpax+'">';
  for(c=1;c<=value;c++){
   ageSelect = ageSelect + '<div class="clearfix"><p class="head_title_room">Child '+c+' Age</p><select style="width:25%;" class="child_age" data-room="'+currentpax+'">';
   var ageJSON = <?php echo json_encode($child_age_jsonArr); ?>;
   for(op=1;op<=12;op++){
     if(currentpax in ageJSON){
       if(c-1 in ageJSON[currentpax]){
         if(ageJSON[currentpax][c-1] == op)
          ageSelect = ageSelect + '<option value="'+op+'" selected>'+op+'</option>';
        else
          ageSelect = ageSelect + '<option value="'+op+'">'+op+'</option>';
       }else
         ageSelect = ageSelect + '<option value="'+op+'">'+op+'</option>';
     }else
      ageSelect = ageSelect + '<option value="'+op+'">'+op+'</option>';
   }
   ageSelect = ageSelect + '</select></div>';
  }
  ageSelect = ageSelect + '</div>';
  $(".content_pax_"+currentpax).append(ageSelect);

}
function ondone()
{

   $(".in").removeClass("in");
}
function editval(val)
{
   var currentpax=$(val).closest(".pax_container").attr("data");
   $(".content_pax_"+currentpax).toggle("slow");
     var text=$(".edit[data='"+currentpax+"'] i").text();
     if(text=="Edit")
     {
      $(".edit[data='"+currentpax+"'] i").text("Minimize");
     }else{
      $(".edit[data='"+currentpax+"'] i").text("Edit");
     }
}
function minimize(val)
{
    var currentpax=$(val).closest(".pax_container").attr("data");
   $(".content_pax_"+currentpax).hide("slow");
}
function removeroom(data)
{ var s=0;
   var currentpax=$(data).closest(".pax_container").attr("data");
   var guest=0;
   $(".pax_container_"+currentpax).remove();
   var i=1;
   $('.pax_container').each(function(){
      if($(this).hasClass("test"))
      {
        var item=$(this).attr("data");  
         $(this).attr("data",i);
          $(".pax_container_"+item+' .roomnumber').text(i);
           
$(this).parent().find('.detail_pax_'+item).removeClass('detail_pax_'+item).addClass('detail_pax_'+i);
$(this).parent().find('.content_pax_'+item).removeClass('content_pax_'+item).addClass('content_pax_'+i);
$(this).parent().find('.adultlist_'+item).removeClass('adultlist_'+item).addClass('adultlist_'+i);
$(this).parent().find('.childlist_'+item).removeClass('childlist_'+item).addClass('childlist_'+i);
$(this).parent().find('.pax_container_'+item).removeClass('pax_container_'+item).addClass('pax_container_'+i);$(this).parent().find('.edit').attr("data",i);


      }
s++;
      i++;

   });
$(".rooms_in_hotel").attr('data',i);
$('.actives').each(function(){
      guest=guest+parseInt($(this).text());
      });
$("#roomval").val(s);
$('.total_guest_room').text(s+' Room, '+guest+' Guests');
}
function addroom(value)
{

      var intId=parseInt($(value).attr('data'));
      var inc=intId+1;
      $(".rooms_in_hotel").attr('data',inc);
     var append='<div  class="pax_container test pax_container_'+intId+'" data="'+intId+'"><div class="removepax" onclick="removeroom(this)"><i class="fa fa-times" aria-hidden="true"></i></div>';
         append+='<div class="detail_pax detail_pax_'+intId+'">';
         append+='<p class="detail_pax_p">Room <span class="roomnumber">'+intId+'</span></p>';
         append+='<p style="margin-top:5px;margin-bottom:5px;" ><span class="this_adult">2</span> Adults, <span class="this_child">0</span>  Child</p>';
         append+='</div>';
         append+='<div class="content_pax content_pax_'+intId+'" style="display:none">';
         append+='<p class="head_title_room">Adult (+12 yrs>)</p>';
         append+='<ul class="pagination pagination-sm pagination_lists adultlist adultlist_'+intId+'">';
         append+='<li onclick="changeadult(1,this)" >1</li>';
         append+='<li class="actives" onclick="changeadult(2,this)" >2</li>';
         append+='<li onclick="changeadult(3,this)" >3</li>';
         append+='<li onclick="changeadult(4,this)" >4</li>';
         append+='<li onclick="changeadult(5,this)" >5</li>';
         append+=' <li onclick="changeadult(6,this)" >6</li>';
         append+='</ul>';
         append+='<p class="head_title_room">Childern (1-12 yrs>)</p>';
         append+='<ul class="pagination pagination-sm pagination_lists childlist childlist_'+intId+'">';
         append+=' <li class="actives" onclick="changechild(0,this)">0</li><li onclick="changechild(1,this)">1</li>';
         append+=' <li onclick="changechild(2,this)" >2</li>';
         append+=' <li onclick="changechild(3,this)">3</li>';
         append+=' <li onclick="changechild(4,this)">4</li>';

         append+=' </ul>';
         append+='</div>';
         append+='<div class="edit " onclick="editval(this)" data="'+intId+'"><i  style="font-size:10px;" >Edit</i></div>';
         append+='<div class="clearfix"></div>';
         append+=' </div>';

         $(".outer_pax").append(append);

      var guest=0;     
      $('.actives').each(function(){
      guest=guest+parseInt($(this).text());
      });
     $("#roomval").val(intId);
     $('.total_guest_room').text(intId+' Room, '+guest+' Guests');

}
$(document).ready(function()
{
   var room=0;
   var noofadult=0;
   var noofchild=0;
   var obj = {};
   var items = [];
   $( "#hotel_search" ).submit(function( e ) {
    $.LoadingOverlay("show", {
      image       : "",
      fontawesome : "fa fa-spinner fa-spin",
    });
     $('.pax_container').each(function(){
     if($(this).hasClass("pax_container"))
     {
      room=$(this).attr("data");
      noofadult=$(this).parent().find(".adultlist_"+room+' .actives').text();  
      noofchild=$(this).parent().find(".childlist_"+room+' .actives').text(); 
     // obj[room]=[noofadult,noofchild];

     obj[room]={'adult':noofadult,'child':noofchild};

    // items.push({'adult':noofadult,'child':noofchild});

     }
     });
     $("#guest").val(JSON.stringify(obj));
     return true;
  });

  $('.dropdown-toggle').dropdown();//.divCustomCheck
  $('.dropdown-menu li input[type=checkbox]').on('click', function () {
    $(this).parent().find("a").trigger('click');
  });
  $('.dropdown-menu li a').on('click', function (event) {
    var incJs = 1; var queryJSON = [];
    var queryArr = {};
    var guestJson = '<?php echo json_encode($guest); ?>';
    
    if(event.originalEvent){
      if($(this).parent().find('input:checkbox:first').prop("checked") == true)
        $(this).parent().find('input:checkbox:first').prop('checked', false)
      else
        $(this).parent().find('input:checkbox').prop('checked', true);
    }

    $('.dropdown-menu input:checkbox:checked').each(function(){
      var keyFil = $(this).attr('data-sort-type');
      var value = $(this).attr('data-value');
      var object = {}; 
      object[keyFil] = value;
      queryJSON.push(object);
      incJs++;
    });

    queryArr['goingto'] = $('#goingto').val();
    queryArr['to_hidden'] = $('#to_hidden').val();
    queryArr['hotel_month'] = $('#hotel_month').val();
    queryArr['hotel_month1'] = $('#hotel_month1').val();
    queryArr['guestJson'] = guestJson;
    var root_dir=$("#root_dir").val();
    $.ajax({
      type: "POST",
      url: root_dir+"hotels/search/index-ajax.php",
      data: {queryJSON: JSON.stringify(queryJSON),queryVal: JSON.stringify(queryArr)},
      async: false,
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
        $('#hoteldetails').empty().append(data);    
      }
    });
    return false;
  });

  $('.childlist .actives').each(function(){
    $(this).trigger('click');
  });
  $(".done").click(function()
  {
   var childAgeJSON = [];
   $('.child_age').each(function(){
    var object = {}; 
    object[$(this).attr('data-room')] = $(this).val();
    childAgeJSON.push(object);
   });
   $('.child_age_json').val(JSON.stringify(childAgeJSON));
  });
  $(".done").trigger('click');
});
//setup before functions
var typingTimer;
var doneTypingInterval = 1000;
var $input = $('#filterHotelName');

//on keyup, start the countdown
$input.on('keyup', function () {
  clearTimeout(typingTimer);
  typingTimer = setTimeout(doneTyping, doneTypingInterval);
});

//on keydown, clear the countdown 
$input.on('keydown', function () {
  clearTimeout(typingTimer);
});

//user is "finished typing," do something
function doneTyping () {
  console.log($('#filterHotelName').val());
  var incJs = 1; var queryJSON = [];
  var queryArr = {};
  var guestJson = '<?php echo $_GET['guest']; ?>';
  $('.dropdown-menu input:checkbox:checked').each(function(){
    var keyFil = $(this).attr('data-sort-type');
    var value = $(this).attr('data-value');
    var object = {}; 
    object[keyFil] = value;
    queryJSON.push(object);
    incJs++;
  });
  if($('#filterHotelName').val()){
    var object = {};
    object['hotel_name'] = $('#filterHotelName').val();
    queryJSON.push(object);
  }
  queryArr['goingto'] = $('#goingto').val();
  queryArr['to_hidden'] = $('#to_hidden').val();
  queryArr['hotel_month'] = $('#hotel_month').val();
  queryArr['hotel_month1'] = $('#hotel_month1').val();
  queryArr['guestJson'] = guestJson;
var root_dir=$("#root_dir").val();
  $.ajax({
    type: "POST",
    url: root_dir+"hotels/search/index-ajax.php",
    data: {queryJSON: JSON.stringify(queryJSON),queryVal: JSON.stringify(queryArr)},
    async: false,
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
      $('#hoteldetails').empty().append(data);    
    }
  });
  return false;
}
</script>
<?php include("../../include/footer.php"); ?>
        

<link href="https://fonts.googleapis.com/icon?family=Material+Icons"
      rel="stylesheet">

 
  


<script type="text/javascript">
  $(document).ready(function() {
      $(".testvalues").click(function(){

            $(this).text($(this).text() == "...Show Less" ? "Read More..." : "...Show Less");
            $(".complete").toggle('slow');    
         });
            $('[data-toggle="popover"]').popover({
             container: 'body'
         });
    $(".fancybox").fancybox({ 

      openEffect  : 'elastic',
      closeEffect : 'elastic'});
  });
</script>
<?php include('include/footer.php');?>