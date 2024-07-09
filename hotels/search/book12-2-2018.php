<?php 

session_start();
//include '../config.php';
//include '../include/database/config.php';
$currentpage="hotelbooking";
include("../../include/header.php");

error_reporting(0);
$city_name = isset($details[0]['name'])?$details[0]['name']:''.', '.isset($details[0]['state'])?$details[0]['state']:'';
$city_id = isset($details[0]['id_city'])?$details[0]['id_city']:'';

$check_in = date('d-m-Y',strtotime('+24 hours'));
$check_out = date('d-m-Y',strtotime('+48 hours'));

$numberOfNights=(strtotime($check_out)-strtotime($check_in))/ (60 * 60 * 24);
global $roomTypes;
$id_property = (int)$_GET['id_property'];

$property = $database->query("SELECT p.*,c.name as Cityname,s.name as Statename from ps_property p left join ps_city c on(c.id_city=p.selCityId) left join ps_state s on(s.id_state=p.selStateId)  where p.status=0 and p.is_delete=0 and p.id_property=$id_property")->fetchAll(PDO::FETCH_ASSOC);
if(!isset($_GET['goingto']))
{
	$city_name = $property[0]['Cityname'].', '.$property[0]['Statename'];
	$city_id = $property[0]['selCityId'];
	$check_in = date('d-m-Y',strtotime('+24 hours'));
	$check_out =date('d-m-Y',strtotime('+48 hours'));	
	$_GET['guest']=array(1=> array('adult' => 2,'child' => 0));
}else
{
	$city_name = $_GET['goingto'];
	$city_id = $_GET['to_hidden'];
	$check_in = $_GET['hotel_month'];
	$check_out = $_GET['hotel_month1'];
}

if(isset($_GET['guestJSON']) && !empty($_GET['guestJSON'])){
	$guest = json_decode($_GET['guestJSON'],true);
	foreach($guest as $guestKK => $guestVV){
		foreach($guestVV as $guestKK1 => $guestVV1){
			$guest[$guestKK]["'".$guestKK1."'"] = $guestVV1;
			unset($guest[$guestKK][$guestKK1]);
		}
	}
}else
	$guest = $_GET['guest'];

if(isset($_GET['child_age_json']) && !empty($_GET['child_age_json'])){
	$child_age_json = json_decode($_GET['child_age_json'], true);
	$child_age_jsonArr = [];
	if(isset($child_age_json) && !empty($child_age_json))
		foreach($child_age_json as $child_age_jsonK => $child_age_jsonV)
			foreach($child_age_jsonV as $child_age_jsonVK => $child_age_jsonVV)
				$child_age_jsonArr[$child_age_jsonVK][] = $child_age_jsonVV;
}else
	$child_age_jsonArr = $_SESSION['child_age_jsonArr'];

$countWhere = $guestCntHidden = $guestText = $searchguest = ''; $guestcount = $countadult = $countchild = 0;
if(isset($guest) && !empty($guest)){

	foreach($guest as $key => $val){
		if(!isset($_GET['goingto']))
		{
			$countadult += $val['adult'];
		$countchild += $val['child'];
		//$countWhere .= ' and (r.selMaxNoOfAdult >= '.$val['adult'].' and r.selMaxNoOfChild >= '.$val['child'].' and r.selMaxNoOfGuest >= '.($val['adult']+$val['child']).')';
		$countWhere .= ' and rt.selMaxNoOfGuest >= '.($val['adult']+$val['child']);
		$guestCntHidden .= '<input type=Hidden name="guest['.$key.'][adult]" value='.$val['adult'].'><input type=Hidden name="guest['.$key.'][child]" value='.$val['child'].'>';
		}else{


			$countadult += $val['\'adult\''];

		$countchild += $val['\'child\''];

		//$countWhere .= ' and (r.selMaxNoOfAdult >= '.$val['\'adult\''].' and r.selMaxNoOfChild >= '.$val['\'child\''].' and r.selMaxNoOfGuest >= '.($val['\'adult\'']+$val['\'child\'']).')';
		$countWhere .= ' and rt.selMaxNoOfGuest >= '.($val['\'adult\'']+$val['\'child\'']);
		$guestCntHidden .= '<input type=\'Hidden\' name="guest['.$key.'][\'adult\']" value=\''.$val['\'adult\''].'\'><input type=\'Hidden\' name="guest['.$key.'][\'child\']" value=\''.$val['\'child\''].'\'>';
		}
		
		
		if($key!=1)
		{
			$searchguest.='<div class="pax_container test pax_container_'.$key.'" data="'.$key.'">';
		}else
		{
			$searchguest.='<div class="pax_container pax_container_'.$key.'" data="'.$key.'"><span id="mesg">only 3 guest allowed</span>';
		}
		if($key!=1)
		{
$searchguest.='<div class="removepax" onclick="removeroom(this)"><i class="fa fa-times" aria-hidden="true"></i></div>';
		}

$searchguest.='<div class="row" style="margin-top:0;"><div class="col-sm-3"><p class="detail_pax_p">Room <span class="roomnumber">'.$key.'</span></p></div>
					
					
					<div class="content_pax content_pax_'.$key.'" ><div class="col-xs-8">
				
					<div class="row" style="margin-top:0;"><div class="col-xs-6">
					<p class="head_title_room" style="margin-left: -5px;">Adult</p>
					<select class="select1  actives adultlist adultlist_'.$key.'" onchange="changeadult(this.value,this)">
					<option '.(($val['\'adult\'']==1)?"selected":"").' value="1">1</option>
					<option '.(($val['\'adult\'']==2)?"selected":"").' value="2">2</option>
					<option '.(($val['\'adult\'']==3)?"selected":"").' value="3">3</option>
					</select>
					<input type="hidden" id="oldAdult_'.$key.'" value="2">
					</div>
					<div class="col-sm-6 col-xs-6">
					<p class="head_title_room" style="margin-left: -34px;">Childern (5-12 yrs)</p>
					<select class="select1 actives childlist childlist_'.$key.'" onchange="changechild(this.value,this)">
					<option '.(($val['\'child\'']==0)?"selected":"").' value="0" >0</option>
					<option '.(($val['\'child\'']==1)?"selected":"").' value="1">1</option>
					<option '.(($val['\'child\'']==2)?"selected":"").' value="2">2</option>
					</select><input type="hidden" id="oldChild_'.$key.'" value="0">
					</div>
					</div>
					</div>
					</div>
					</div></div>';
	}
	$guestcount = $countadult + $countchild;

	$guestText	= $key.' Room(s), '.$guestcount.' Guest(s)';
}


$hotelConfig = array();
$getHotelConfigValues = $database->query("select name,value from ps_configuration where name = 'ps_hotel_service_tax' or  name = 'ps_hotel_commission' or  name = 'ps_hotel_pay_cgst_2499' or  name = 'ps_hotel_pay_sgst_2499' or  name = 'ps_hotel_pay_cgst_7499' or  name = 'sps_hotel_pay_sgst_7499' or  name = 'ps_hotel_pay_cgst_7500' or  name = 'ps_hotel_pay_sgst_7500'")->fetchAll();
if(isset($getHotelConfigValues) && !empty($getHotelConfigValues)){
	foreach($getHotelConfigValues as $hotelVal){
		$hotelConfig[$hotelVal['name']] = $hotelVal['value'];
	}
}
$ps_hotel_commission = (isset($hotelConfig['ps_hotel_commission']) && !empty($hotelConfig['ps_hotel_commission']) ? $hotelConfig['ps_hotel_commission'] : 0);
$ps_hotel_service_tax = (isset($hotelConfig['ps_hotel_service_tax']) && !empty($hotelConfig['ps_hotel_service_tax']) ? $hotelConfig['ps_hotel_service_tax'] : 0);

$propertyName = $database_hotel->query('select * from ps_property where id_property = '.$id_property)->fetchAll();

//echo 'select p.maplink,p.offer_percentage,p.txtNoOfGuestRooms,p.txtPropertyName,p.selStarRating,p.txtAddress1,p.txtAddress2,p.txtZip,p.selImages,p.txtLatitude,p.txtLongitude,p.rateLunchAdult,p.rateLunchMoreThanFive,p.rateLunchLessThanFive,p.rateDinnerAdult,p.rateDinnerMoreThanFive,p.rateDinnerLessThanFive,p.txtCGST,p.txtSGST,p.txtTAC,p.cancellation_policy_3_days,p.cancellation_policy_3_days_more,p.terms_and_conditions,r.*,r.txtPropertyDescription as roomDescription,rt.id_room_type,rt.rateExtraBedAdult,rt.rateExtraBedChildMoreThanFive,rt.rateExtraBedChildLessThanFive,rt.periodic_rateExtraBedAdult,rt.periodic_rateExtraBedChildMoreThanFive,rt.periodic_rateExtraBedChildLessThanFive,rt.txtRoomName as txtRoomNameNew,cl.name as countryName, s.name as stateName, c.name as cityName from ps_property p left join ps_room r on(p.id_property = r.id_property and r.status=0) left join ps_room_type rt on(r.id_room = rt.id_room) left join ps_country_lang cl on(p.selCountryId = cl.id_country) left join ps_state s on(p.selStateId = s.id_state) left join ps_city c on(p.selCityId = c.id_city) where 1'.$countWhere.' and p.id_property = '.$id_property.' and p.status=0';
//die;
$availableRoomsList = $database_hotel->query('select p.txtPhone,p.txtMobile,p.txtPropertyDescription,l.txtlandmark,p.selPropertyTypeID,p.offer_percentage,p.txtNoOfGuestRooms,p.txtPropertyName,p.selStarRating,p.txtAddress1,p.txtAddress2,p.txtZip,p.selImages,p.txtLatitude,p.txtLongitude,p.rateLunchAdult,p.rateLunchMoreThanFive,p.rateLunchLessThanFive,p.rateDinnerAdult,p.rateDinnerMoreThanFive,p.rateDinnerLessThanFive,p.txtCGST,p.txtSGST,p.txtTAC,p.cancellation_policy_3_days,p.cancellation_policy_3_days_more,p.terms_and_conditions,r.*,r.txtPropertyDescription as roomDescription,rt.id_room_type,rt.rateExtraBedAdult,rt.rateExtraBedChildMoreThanFive,rt.rateExtraBedChildLessThanFive,rt.periodic_rateExtraBedAdult,rt.periodic_rateExtraBedChildMoreThanFive,rt.periodic_rateExtraBedChildLessThanFive,rt.txtRoomName as txtRoomNameNew,cl.name as countryName, s.name as stateName, c.name as cityName from ps_property p left join ps_room r on(p.id_property = r.id_property and r.status=0) left join ps_room_type rt on(r.id_room = rt.id_room) left join ps_country_lang cl on(p.selCountryId = cl.id_country) left join ps_state s on(p.selStateId = s.id_state) left join ps_city c on(p.selCityId = c.id_city) left join ps_landmark l on(l.id_Landmark=p.selLandmark and l.status=0 and l.action=\'\') where 1'.$countWhere.' and p.id_property = '.$id_property.' and p.status=0')->fetchAll();

$mobile_number=$availableRoomsList[0]['txtMobile'];
$phone_no=$availableRoomsList[0]['txtPhone'];
$property_desc=$availableRoomsList[0]['txtPropertyDescription'];
$offer_percentage=$availableRoomsList[0]['offer_percentage'];
$address=$availableRoomsList[0]['txtAddress1'];
$propertyName=$availableRoomsList[0]['txtPropertyName'];
$landmarkss=$availableRoomsList[0]['txtlandmark'];
$starcat=$availableRoomsList[0]['selStarRating'];
$text1=$starcat .' Star '.$hotel_type;
$text1=ucwords($text1);
$searchMonth	= date('n',strtotime($check_in));
$searchYear		= date('Y',strtotime($check_in));
$searchDate		= date('d',strtotime($check_in));
$searchDate1	= date('d',strtotime($check_out));


$attractions_hjf = $database_hotel->query('select txtAttractions from ps_attractions where selCityId='.$city_id.' and status=0 and action=\'\' order by RAND() limit 4')->fetchAll(PDO::FETCH_ASSOC);

$attractions_hjf=array_column($attractions_hjf, 'txtAttractions');
$roomRateArr = $propertyRooms = $hotelListArr = $price = $roomNames = array();

if(isset($availableRoomsList) && !empty($availableRoomsList)){
	foreach($availableRoomsList as $incKey => &$roomVal){
		$begin 	= new DateTime(date('Y-m-d',strtotime($check_in)));
		//$end 	= new DateTime(date('Y-m-d',strtotime($check_out.' +1 day')));
		$end 	= new DateTime(date('Y-m-d',strtotime($check_out)));
		$daterange = new DatePeriod($begin, new DateInterval('P1D'), $end);

		$extraRateAdult = $extraRateChild = 0;
		// get inventory
		foreach($daterange as $date){
			$dateValue = $date->format("Y-m-d");
			$year	= date('Y',strtotime($dateValue));
			$month	= date('n',strtotime($dateValue));
			$dateV	= date('d',strtotime($dateValue));
			$dateV1	= date('j',strtotime($dateValue));

			$inventoryVal = array();
			if(isset($roomVal['id_room']) && !empty($roomVal['id_room']))
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
			$year	= date('Y',strtotime($dateValue));
			$month	= date('n',strtotime($dateValue));
			$dateV	= date('d',strtotime($dateValue));
				
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

		$extraRate = 0;
		if(isset($guest) && !empty($guest)){
			foreach($guest as $guestkey => $guestval){
				if($guestkey>1) $extraRate += $rateValue;
				if($guestval['\'adult\''] > $roomVal['id_room_type']){ // > 2
					$extraAdult = $guestval['\'adult\''] - $roomVal['id_room_type']; // - 2;
					foreach($daterange as $dateK => $date){
						$dateValue = $date->format("Y-m-d");

						if($dateValue >= $roomVal['period_from'] && $dateValue <= $roomVal['period_to'] && isset($roomVal['periodic_rateExtraBedAdult']) && !empty($roomVal['periodic_rateExtraBedAdult']))
							$extraRate += $extraAdult * $roomVal['periodic_rateExtraBedAdult'];
						else
							$extraRate += $extraAdult * $roomVal['rateExtraBedAdult'];
					}
				}
				if($guestval['\'child\''] > 0){
					$extraAdult = $guestval['\'child\''];
					foreach($daterange as $dateK => $date){
						$dateValue = $date->format("Y-m-d");
						if($dateValue >= $roomVal['period_from'] && $dateValue <= $roomVal['period_to']){
							for($extraAdultInc=0; $extraAdultInc<$extraAdult; $extraAdultInc++){
								if(isset($child_age_jsonArr[$extraAdultInc+1][$extraAdultInc]) && $child_age_jsonArr[$extraAdultInc+1][$extraAdultInc] > 5){
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
		$rateValue=round($rateValue);
		//echo $baseTariff;
		 if($offer_percentage!=''&&$offer_percentage!=0)
           {
				$bf=($rateValue/(100+$offer_percentage))*100;     
				$offerrate=round($rateValue-$bf);  
				$rateValue =round($rateValue-$offerrate); 
				
           }
		$roomVal['baseTariff'] = $price1[$roomVal['id_room'].'_'.$roomVal['id_room_type']] = $rateValue;
		if($roomVal['baseTariff'] > 999 && $roomVal['baseTariff'] <= 2499){
			$roomVal['cgstAmount'] = $gstArrSelect[$roomVal['id_room'].'_'.$roomVal['id_room_type']]['cgstAmount'] = round(($roomVal['baseTariff']*($hotelConfig['ps_hotel_pay_cgst_2499']/100))*count($guest));
			$roomVal['sgstAmount'] = $gstArrSelect[$roomVal['id_room'].'_'.$roomVal['id_room_type']]['sgstAmount'] = round(($roomVal['baseTariff']*($hotelConfig['ps_hotel_pay_sgst_2499']/100))*count($guest));
		}else if($roomVal['baseTariff'] > 2499 && $roomVal['baseTariff'] <= 7499){
			$roomVal['cgstAmount'] = $gstArrSelect[$roomVal['id_room'].'_'.$roomVal['id_room_type']]['cgstAmount'] = round(($roomVal['baseTariff']*($hotelConfig['ps_hotel_pay_cgst_7499']/100))*count($guest));
			$roomVal['sgstAmount'] = $gstArrSelect[$roomVal['id_room'].'_'.$roomVal['id_room_type']]['sgstAmount'] = round(($roomVal['baseTariff']*($hotelConfig['sps_hotel_pay_sgst_7499']/100))*count($guest));
		}else if($roomVal['baseTariff'] > 7499){
			$roomVal['cgstAmount'] = $gstArrSelect[$roomVal['id_room'].'_'.$roomVal['id_room_type']]['cgstAmount'] = round(($roomVal['baseTariff']*($hotelConfig['ps_hotel_pay_cgst_7500']/100))*count($guest));
			$roomVal['sgstAmount'] = $gstArrSelect[$roomVal['id_room'].'_'.$roomVal['id_room_type']]['sgstAmount'] = round(($roomVal['baseTariff']*($hotelConfig['ps_hotel_pay_sgst_7500']/100))*count($guest));
		}else{
			$roomVal['cgstAmount'] = $gstArrSelect[$roomVal['id_room'].'_'.$roomVal['id_room_type']]['cgstAmount'] = 0;
			$roomVal['sgstAmount'] = $gstArrSelect[$roomVal['id_room'].'_'.$roomVal['id_room_type']]['sgstAmount'] = 0;
		}
		if(isset($rateValue) && !empty($rateValue))
			$rateValue = (count($guest) > 1 ? round(($rateValue + $extraRate) / count($guest)) : round($rateValue + $extraRate));
		$roomVal['rate'] = $roomVal['baserate'] = (isset($rateValue) ? $rateValue : 0);
		
		if(isset($rateValue) && !empty($rateValue)){
			if(!isset($propertyRooms[$roomVal['id_property']]['rate']) || (isset($propertyRooms[$roomVal['id_property']]['rate']) && $rateValue < $propertyRooms[$roomVal['id_property']]['rate'])){
				$propertyRooms[$roomVal['id_property']]['id_room'][] = $roomVal['id_room'];
				$propertyRooms[$roomVal['id_property']]['rate'] = $rateValue;
			}
		}
		
		//$tds_amt = $roomVal['rate'] * ($ps_hotel_service_tax/100);
		//$commission_amt = $roomVal['rate'] * ($ps_hotel_commission/100);
		//$roomVal['rate'] = $price[$roomVal['id_room'].'_'.$roomVal['id_room_type']] = $_SESSION['roomRate'][$roomVal['id_room']][$roomVal['id_room_type']] = round(($roomVal['rate'] - $commission_amt) + $tds_amt);
		
		$roomVal['rate'] = $price[$roomVal['id_room'].'_'.$roomVal['id_room_type']] = round($roomVal['rate']);
	}
}

$photo_gallery = array();
if(isset($price1) && !empty($price1))
	array_multisort($price1, SORT_ASC, $availableRoomsList);

$cityNameMap = $stateNameMap = $countryNameMap = '';
$rateLunchAdult = $rateLunchMoreThanFive = $rateLunchLessThanFive = $rateDinnerAdult = $rateDinnerMoreThanFive = $rateDinnerLessThanFive = $countchildless = $countchildmore = 0;
$keyIteInc = 0;
foreach ($availableRoomsList as $keyIte => $row)
{
	if(empty($keyIteInc)){
		$rateLunchAdult = $row['rateLunchAdult'];
		$rateDinnerAdult = $row['rateDinnerAdult'];
		$rateLunchMoreThanFive = $row['rateLunchMoreThanFive'];
		$rateDinnerMoreThanFive = $row['rateDinnerMoreThanFive'];
		$rateLunchLessThanFive = $row['rateLunchLessThanFive'];
		$rateDinnerLessThanFive = $row['rateDinnerLessThanFive'];
		
		$cityNameMap = $row['cityName'];
		$stateNameMap = $row['stateName'];
		$countryNameMap = $row['countryName'];

		foreach($guest as $guestkey => $guestval){
			if($guestval['\'child\''] > 0){
				$extraAdult = $guestval['\'child\''];
				for($extraAdultInc=0; $extraAdultInc<$extraAdult; $extraAdultInc++){
					if($child_age_jsonArr[$extraAdultInc+1][$extraAdultInc] > 5)
						$countchildmore++;
					else
						$countchildless++;
				}
			}
		}
		
		$propertyAmenitiesArr = $database_hotel->query('select * from ps_property_facility where id_property='.$row['id_property'])->fetchAll();
		$availableAmenities = array_keys($propertyAmenitiesArr[0], 1);
		foreach($availableAmenities as $availableAmenitiesK => $availableAmenitiesV){
			if($availableAmenitiesV == 'id_property_facility' || $availableAmenitiesV == 'id_property' || is_numeric($availableAmenitiesV))
				unset($availableAmenities[$availableAmenitiesK]);
		}
		if(isset($availableAmenities) && !empty($availableAmenities))
			$availableRoomsList[$keyIte]['amenities'] = $availableAmenities;
	}
	if(empty($row['rate'])){
		unset($availableRoomsList[$keyIte]);
		continue;
	}
	$breakfastResult = $database_hotel->query('select is_breakfast from ps_room_type where id_room='.$row['id_room'].' and id_room_type='.$row['id_room_type'])->fetchAll();
	$roomNames[$row['id_room'].'_'.$row['id_room_type']] = $row['txtRoomName'].(isset($roomTypes[$row['id_room_type']]) && !empty($roomTypes[$row['id_room_type']]) ? ' - '.$roomTypes[$row['id_room_type']] : '').(isset($breakfastResult[0][0]) && $breakfastResult[0][0] == 1 ? ' with Breakfast' : '');
	
	if((empty($keyIteInc) || empty($photo_gallery)) && isset($row['selImages']) && !empty($row['selImages'])){
		 $jsonImg = json_decode($row['selImages']);
		 if(isset($jsonImg) && !empty($jsonImg))
			 foreach($jsonImg as $imageF)
				 $photo_gallery[] = _BO_HOTEL_IMG_DIR_.'property/'.$row['id_property'].'/'.current($imageF);
	}
	if(isset($row['photo_gallery']) && !empty($row['photo_gallery']) && $row['photo_gallery'] != ',,,'){
		$room_gallery_arr = json_decode($row['photo_gallery'],true);
		if(isset($room_gallery_arr) && !empty($room_gallery_arr)){
			foreach($room_gallery_arr as $room_gallery_arr_val){
				$availableRoomsList[$keyIte]['imagesRoom'][] = _BO_HOTEL_IMG_DIR_.'rooms/'.$row['id_room'].'/'.current($room_gallery_arr_val);
				if(!isset($availableRoomsList[$keyIte]['imageRoom']) || empty($availableRoomsList[$keyIte]['imageRoom']))
					$availableRoomsList[$keyIte]['imageRoom'] = _BO_HOTEL_IMG_DIR_.'rooms/'.$row['id_room'].'/'.current($room_gallery_arr_val);
			}
		}
	}

	$roomAmenitiesArr = $database_hotel->query('select * from ps_room where id_room='.$row['id_room'])->fetchAll();
	$availableRoomAmenities = array_keys($roomAmenitiesArr[0], 1);
	foreach($availableRoomAmenities as $availableRoomAmenitiesK => $availableRoomAmenitiesV){
		if(in_array($availableRoomAmenitiesV,$roomFacilitiesArr))
			$availableRoomsList[$keyIte]['room_amenities'][] = $availableRoomAmenitiesV;
	}
	$keyIteInc++;
}

$noRoomsFlag = 0;
$keyIteInc = 0;


if(empty($availableRoomsList)){

	$noRoomsFlag = 1;
	$availableRoomsList = $database_hotel->query('select p.offer_percentage,p.txtNoOfGuestRooms,p.txtPropertyName,p.selStarRating,p.txtAddress2,p.selImages,p.rateLunchAdult from ps_property p where p.id_property = '.$id_property.' and p.status=0 and p.selCityId='.$city_id)->fetchAll();

	foreach($availableRoomsList as $keyIte => $row){
		
		if((empty($keyIteInc) || empty($photo_gallery)) && isset($row['selImages']) && !empty($row['selImages'])){
			 $jsonImg = json_decode($row['selImages'],true);
			 if(isset($jsonImg) && !empty($jsonImg))
				 foreach($jsonImg as $imageF)
					 $photo_gallery[] = _BO_HOTEL_IMG_DIR_.'property/'.$id_property.'/'.current($imageF);
		}
 
		if(empty($keyIteInc)){
			$propertyAmenitiesArr = $database_hotel->query('select * from ps_property_facility where id_property='.$id_property)->fetchAll();
			$availableAmenities = array_keys($propertyAmenitiesArr[0], 1);
			foreach($availableAmenities as $availableAmenitiesK => $availableAmenitiesV){
				if($availableAmenitiesV == 'id_property_facility' || $availableAmenitiesV == 'id_property' || is_numeric($availableAmenitiesV))
					unset($availableAmenities[$availableAmenitiesK]);
			}
			if(isset($availableAmenities) && !empty($availableAmenities))
				$availableRoomsList[$keyIte]['amenities'] = $availableAmenities;
		}
		$keyIteInc++;
	}
}
$availableRoomsList = array_values($availableRoomsList);
//echo '<pre>';print_r($availableRoomsList);
//echo '</pre>';

?>
<link rel="stylesheet" href="<?php echo $root_dir; ?>hotel/js/jquery.jsgallery/jsgallery.css" type="text/css" media="screen" charset="utf-8">
<script type="text/javascript" src="<?php echo $root_dir; ?>hotel/js/jquery.jsgallery/jquery.jsgallery.min.js"></script>

		



	
		
		


<div class="main" role="main" id="primary">


   <div class="wrap">
   	<h1>Hotel <?php echo $propertyName ?></h1>
      <!--main content--><!--breadcrumbs-->
      <nav role="navigation" class="breadcrumbs">
         <ul>
            <li><a href="index.php" title="Home">Home / </a></li>
            <li><a href="<?php echo $root_dir;?><?php echo $hotel_type;?>-in-<?php echo $destination; ?>" title="Hotels"><?php echo $city_name; ?></a></li>
            <li class="breadcrumbs_list"> / <?php echo $propertyName; ?></li>
         </ul>
      </nav>
      <!--//breadcrumbs-->	
      <div class="">
        
         
         <!--full-width content-->
         <section class="full-width accommodation-inquiry-section inquiry-section" style="display:none">
            <div class="static-content">
               <form method="post" action="" class="inquiry accommodation-inquiry-form">
                  <h2>Use the form below to contact us directly.</h2>
                  <div class="error" style="display:none;">
                     <div>
                        <p></p>
                     </div>
                  </div>
                  <p>Please complete all required fields.</p>
                  <div class="row">
                     <div class="f-item full-width">
                        <label for="your_name">Your name</label>
                        <input type="text" name="your_name" id="your_name" />
                     </div>
                     <div class="f-item full-width">
                        <label for="your_email">Your email</label>
                        <input type="email" id="your_email" name="your_email" />
                     </div>
                     <div class="f-item full-width">
                        <label for="your_phone">Your phone</label>
                        <input type="text" name="your_phone" id="your_phone" />
                     </div>
                     <div class="f-item full-width">
                        <label>What would you like to inquire about?</label>
                        <textarea name='your_message' id='your_message' rows="10" cols="10" ></textarea>
                     </div>
                     <div class="f-item full-width captcha">
                        <label>How much is 18 + 3?</label>
                        <input type="text" required="required" id="c_val_s_inq" name="c_val_s_inq" />
                        <input type="hidden" name="c_val_1_inq" id="c_val_1_inq" value="m1IEBHN/B93I91yP8F97XDcE0IKUbJv+cLJC1FJpGlQ=" />
                        <input type="hidden" name="c_val_2_inq" id="c_val_2_inq" value="PBhLDDEonOlEhscyjDRhTldeFM9xk6FVJyebKZ1Z/ro=" />
                     </div>
                     <div class="f-item full-width">
                        <a href='#' class='gradient-button cancel-accommodation-inquiry'  id='cancel-inquiry'  title='Cancel'>Cancel</a>				
                        	<input type='submit' class='gradient-button' id='submit-accommodation-inquiry' name='submit-accommodation-inquiry' value='Submit inquiry' />				
                     </div>
                  </div>
               </form>
            </div>
         </section>

         <!--//full-width content-->
         <!--accommodation three-fourth content-->
         <section class="three-fourth">
            
            
            <div class="loading" id="wait_loading" style="display:none">
               <div class="ball"></div>
               <div class="ball1"></div>
            </div>
            
           
            <!--gallery-->
          <!--   <ul id="gallery" class="cS-hidden">
            	<?php foreach($photo_gallery as $photo){ ?>
					<li data-thumb="<?php echo $photo; ?>">
		               <img src="<?php echo $photo; ?>" alt="" />
		           </li>
							
					<?php } ?>
              
            </ul> -->

            <ul id="imageGallery">

            	<?php foreach($photo_gallery as $photo){ ?>

            	<li data-thumb="<?php echo $photo; ?>" data-src="<?php echo $photo; ?>">
    <img src="<?php echo $photo; ?>" />
  </li>
					
		              
		          
							
					<?php } ?>
  
 
</ul>

           
            	
           
            <!--//gallery-->			<!--inner navigation-->
            <nav class="inner-nav">
               <ul>
                  <li class='availability'><a href="#availability" title="Availability">Availability </a></li>
                  <li class='descriptions'><a href="#description" title="Description">Hotel Info</a></li>
                  <li class='facilities'><a href="#facilities" title="Facilities">Facilities</a></li>
                  <li class='location'><a href="#location" title="Location">Location</a></li>
                <!--  <li class='things-to-do'><a href="#things-to-do" title="Things to do">Things to do</a></li>
                  <li class='reviews'><a href="#reviews" title="Reviews">Reviews</a></li>-->
               </ul>
            </nav>
            <!--//inner navigation-->
            <script>
               window.moreInfoText = "+ more info";
               window.lessInfoText = "+ less info";
            </script>
            <section id="availability" class="tab-content initial">
               <article>
                  <h2>Availability </h2>
                  <div class='text-wrap availability_text'>
                  	<?php 
                  	$room_category=array_filter(array_column($availableRoomsList, 'txtRoomNameNew'));

                  	if(!empty($room_category))
                  	{

						$room_category=implode(', ',$room_category);
                  	}else
                  	{
                  		$room_category='';
                  	}

                  	$baseTariff=array_filter(array_column($availableRoomsList, 'baseTariff'));

                  	if(!empty($baseTariff))
                  	{

						$baseTariff=implode(', ',$baseTariff);
                  	}else
                  	{
                  		$baseTariff='';
                  	}
                  	$ament='';
							
							$ament.=($room['ac']==1)?', AC':'';
							$ament.=($room['cable_tv']==1)?', Cable Tv':'';

							$ament.=($room['direct_phone']==1)?', Direct Phone':'';

							$ament.=($room['channel_music']==1)?', Channel Music':'';

							$ament.=($room['attached_bathroom']==1)?', Attached Bathroom':'';

							$ament.=($room['shower']==1)?', Shower':'';

							$ament.=($room['bath_tub']==1)?', Bath Tub':'';

							$ament.=($room['shower_bath_tub']==1)?', Shower Bath Tub':'';

							$ament.=($room['minibar']==1)?', Minibar':'';

							$ament.=($room['work_desk']==1)?', Work Desk':'';

							$ament.=($room['balcony']==1)?', Balcony':'';

							$ament.=($room['radio']==1)?', Radio':'';

							$ament.=($room['clock']==1)?', Clock':'';

							$ament.=($room['hair_dryer']==1)?', Hair Dryer':'';

							$ament.=($room['fire_place']==1)?', Fire Place':'';

							$ament.=($room['safe_deposit_box']==1)?', Safe Deposit Box':'';

							$ament.=($room['smoke_alarms']==1)?', Smoke Alarms':'';

							$ament.=($room['sprinklers']==1)?', Sprinklers':'';

							$ament.=($room['double_bed']==1)?', Double Bed':'';

							$ament.=($room['king_bed']==1)?', King Bed':'';

							$ament.=($room['high_speed_wi_fi_internet_access']==1)?', High Speed Wi_fi Internet Access':'';

							$ament.=($room['lcd_tv']==1)?', LCD Tv':'';

							$ament.=($room['sound_proof_windows']==1)?', Sound Proof Windows':'';

							$ament.=($room['24_hour_room_service']==1)?', 24 Hour Room Service':'';

							$ament.=($room['electronic_lock']==1)?', Electronic Lock':'';

							$ament.=($room['electronic_laptop_compatible_safe']==1)?', Electronic Laptop Compatible Safe':'';

							$ament.=($room['marble_flooring']==1)?', Marble Flooring':'';

							$ament.=($room['study_table']==1)?', Study Table':'';

							$ament.=($room['free_local_phone_calls']==1)?', Free Local Phone Calls':'';

							$ament.=($room['iron_with_ironing_board']==1)?', Iron With Ironing Board':'';

							$ament.=($room['full_length_mirror']==1)?', Full Length Mirror':'';

							$ament.=($room['complimentary_toiletries']==1)?', Complimentary Toiletries':'';

							$ament.=($room['internet']==1)?', Internet':'';

							$ament.=($room['tea_coffee_maker']==1)?', Tea Coffee Maker':'';

							$ament.=($room['complimentary_tea_coffee']==1)?', Complimentary Tea Coffee':'';

							$ament.=($room['complimentary_packed_water_bottles']==1)?', Complimentary Packed Water Bottles':'';

							$ament.=($room['carpet_flooring']==1)?', Carpet Flooring':'';

							$ament.=($room['complimentary_fruit_basket']==1)?', Complimentary Fruit Basket':'';

							$ament.=($room['double_twin_beds']==1)?', Double Twin Beds':'';

							$ament.=($room['direct_dialing_phone']==1)?', Direct Dialing Phone':'';

							$ament.=($room['smoke_detector_alarms']==1)?', Smoke Detector Alarms':'';

							$ament.=($room['on_the_bay']==1)?', On The Bay':'';
							
							$ament.=($room['on_the_garden']==1)?', On The Garden':'';

							$ament.=($room['on_the_lake']==1)?', On The Lake':'';

							$ament.=($room['on_the_ocean']==1)?', On The Ocean':'';

							$ament.=($room['on_the_park']==1)?', On The Park':'';

							$ament.=($room['on_the_river']==1)?', On The River':'';

							$ament.=($room['poolside_room']==1)?', Poolside Room':'';

							$ament.=($room['garden_room']==1)?', Garden Room':'';

							$ament.=($room['city_view']==1)?', City View':'';

							$ament.=($room['mountain_view']==1)?', Mountain View':'';

							$ament.=($room['sea_facing_room']==1)?', Sea Facing Room':'';


							echo $ament;
                  	?> 
                    <!--  Hotel <?php echo $propertyName ?> it’s a best Hotels in <?php echo $destination; ?> , Hotel <?php echo $propertyName; ?> is Located near <?php echo $landmarkss?> , Hotel  <?php echo $propertyName ?>  <?php echo $address ?> have <?php echo count($availableRoomsList);?> rooms <?php echo ($room_category!='')?'and '.$room_category:''; ?> -->

                     <div class="seo_content_index">
                      <div class="seocontent_div">

					<h3><?php echo $property_desc ?></h3>
                       <?php echo $propertyName ?> is on the Best <?php echo ucwords($hotel_type);?> in <?php echo $destination; ?>, its located <?php echo $landmarkss?>, we have <?php echo ($room_category!='')?$room_category:''; ?> and Price start from  <?php echo $baseTariff;?> Sightseeing Places near <?php echo $text1;?> , contact number: <?php echo $mobile_number;?> & Address : <?php echo $availableRoomsList[0]['txtAddress1']; ?> <?php echo $text1;?> and we have <?php echo count($availableRoomsList);?>
						Our hotel have <?php echo $ament;?> rooms , we have online Booking system as well.

				</div></div>
                  </div>
                  <ul class="room-types">
                     <!--room_type-->

                    <?php 

                    if(isset($availableRoomsList) && !empty($availableRoomsList) && !$noRoomsFlag){
//echo '<pre>';print_r($availableRoomsList);echo '</pre>';
					foreach($availableRoomsList as $room){ ?>
								
								    <li id="room_type_206">
                        <figure class="left">
                           <!-- <img src="<?php echo $room['photo_gallery']?>" alt="Standard Double room" /><a href="<?php echo $room['photo_gallery']?>" class="image-overlay" rel="prettyPhoto[gallery206]"></a> -->
                           <img src="<?php echo $root_dir;?>images/noimage.jpg">
                        </figure>
                        <div class="meta room_type">
                           <h3><p style="font-size: 19px;"><?php echo (isset($room['txtRoomName']) && !empty($room['txtRoomName']) ? ucwords($room['txtRoomName']).(isset($roomTypes[$room['id_room_type']]) && !empty($roomTypes[$room['id_room_type']]) ? ' ('.$roomTypes[$room['id_room_type']].')' : '') : 'Taj Club, Room, Business Lounge Access, City View'); ?></p></h3>
                           <div class='text-wrap room_meta'>
                              <p>Breakfast not icluded. Non refundable.</p>
                           </div>
                           <a href='javascript:void(0)' class='more-info'  title='+ more info'>+ more info</a>						
                        </div>
                        <div class="room-information">
                           <div>
                              <span class="first">Max:</span>
                              <span class="second">
                              <i class="material-icons">&#xE7FD;</i>
                              <i class="material-icons">&#xE7FD;</i>
                              <i class="material-icons material-icons-small">&#xE7FD;</i>
                              </span>
                           </div>
                           <div>
                              <span class="first">Price from:</span>
                              <span class="second price">
                              <em>
                              <span class="curr"><i class="fa fa-rupee"></i> </span> 
                              <span class="amount">   <?php echo (isset($room['baseTariff']) && !empty($room['baseTariff']) ? $room['baseTariff'] : ''); ?></span>
                              </em>
                             
                              </span>
                           </div>
                           <div style="text-align: center;">
                      
				 <input style="color:#fff;margin-top: 35px!important;" class="btn btn-primary   book_room" type="button" value="Select Room" style="margin-top: 10px;" data-rate="<?php echo (isset($room['rate']) && !empty($room['rate']) ? $room['rate'] : ''); ?>" data-base-rate="<?php echo (isset($room['baserate']) && !empty($room['baserate']) ? $room['baserate'] : ''); ?>" data-id-room="<?php echo (isset($room['id_room']) && !empty($room['id_room']) ? $room['id_room'] : ''); ?>" data-id-room-type="<?php echo (isset($room['id_room_type']) && !empty($room['id_room_type']) ? $room['id_room_type'] : ''); ?>" data-is-breakfast="<?php echo $room['is_breakfast']; ?>"> </div>
																	
                        </div>
                        <div class="more-information">
                           <div class='text-wrap room_facitilies'>
                              <p><span class=''>Room facilities:</span>Air Condition, Desk, Hairdryer, Heating, Minibar, Pay-per-view Channels, Private bathroom, Safety Deposit Box, Seating area, Telephone, TV, WiFi</p>
                           </div>
                           <p>Stylish and individually designed room featuring a satellite TV, mini bar and a 24-hour room service menu.</p>
                           <div class='text-wrap bed_size'>
                              <p><span class=''>Bed size:</span>King Size</p>
                           </div>
                           <div class='text-wrap room_size'>
                              <p><span class=''>Room size:</span>25 m2</p>
                           </div>
                        </div>
                        <div class="booking_form_controls" style="display:none"></div>
                     </li>
								<?php
							}  } ?>
                 
                   
                  </ul>
               </article>
            </section>
            <!--description-->
            <section id="description" class="tab-content ">
               <article>
                  
                
                 
                  <div class='text-wrap pets'>
                     <h2>Pets</h2>
                     <p>Pets are allowed. Charges may be applicable.</p>
                  </div>
                  
                  <div class='text-wrap check_in_time'>
                     <h2>Check-in time</h2>
                     14:00
                  </div>
                  <div class='text-wrap check_out_time'>
                     <h2>Check-out time</h2>
                     11:00
                  </div>
               </article>
            </section>
            <!--//description-->
            <!--facilities-->
            <section id="facilities" class="tab-content ">
               <article>
                  <div class="text-wrap facilities">
                     <h2>Facilities</h2>
                     <ul class="three-col">
                        <div class="col-sm-12">
						
							<!--[ac] => 1 [38] => 1 [cable_tv] => 1 [39] => 1 [direct_phone] => 1 [40] => 1 [channel_music] => 0 [41] => 0 [attached_bathroom] => 1 [42] => 1 [shower] => 0 [43] => 0 [bath_tub] => 0 [44] => 0 [shower_bath_tub] => 0 [45] => 0 [minibar] => 0 [46] => 0 [work_desk] => 0 [47] => 0 [balcony] => 0 [48] => 0 [radio] => 0 [49] => 0 [clock] => 0 [50] => 0 [hair_dryer] => 0 [51] => 0 [fire_place] => 0 [52] => 0 [safe_deposit_box] => 0 [53] => 0 [smoke_alarms] => 0 [54] => 0 [sprinklers] => 0 [55] => 0 [double_bed] => 0 [56] => 0 [king_bed] => 0 [57] => 0 [high_speed_wi_fi_internet_access] => 0 [58] => 0 [lcd_tv] => 0 [59] => 0 [sound_proof_windows] => 0 [60] => 0 [24_hour_room_service] => 0 [61] => 0 [electronic_lock] => 0 [62] => 0 [electronic_laptop_compatible_safe] => 0 [63] => 0 [marble_flooring] => 0 [64] => 0 [study_table] => 0 [65] => 0 [free_local_phone_calls] => 0 [66] => 0 [iron_with_ironing_board] => 0 [67] => 0 [full_length_mirror] => 0 [68] => 0 [complimentary_toiletries] => 0 [69] => 0 [internet] => 0 [70] => 0 [tea_coffee_maker] => 0 [71] => 0 [complimentary_tea_coffee] => 0 [72] => 0 [complimentary_packed_water_bottles] => 0 [73] => 0 [carpet_flooring] => 0 [74] => 0 [complimentary_fruit_basket] => 0 [75] => 0 [high_speed_wi_fi_internet] => 0 [76] => 0 [double_twin_beds] => 0 [77] => 0 [direct_dialing_phone] => 0 [78] => 0 [smoke_detector_alarms] => 0 [79] => 0 [on_the_bay] => 0 [80] => 0 [on_the_beach] => 0 [81] => 0 [on_the_garden] => 0 [82] => 0 [on_the_lake] => 0 [83] => 0 [on_the_ocean] => 0 [84] => 0 [on_the_park] => 0 [85] => 0 [on_the_river] => 0 [86] => 0 [poolside_room] => 0 [87] => 0 [garden_room] => 0 [88] => 0 [city_view] => 0 [89] => 0 [mountain_view] => 0 [90] => 0 [sea_facing_room] => 0 [91] => 0 -->
							<p class="margin_topbot_10">	
								<?php $ament='';
							
							$ament.=($room['ac']==1)?'AC</label>':'';
							$ament.=($room['cable_tv']==1)?'<label class="ament_label"><i class="fa fa-check" style="font-size: 14px;"></i>Cable Tv</label>':'';

							$ament.=($room['direct_phone']==1)?'<label class="ament_label"><i class="fa fa-check" style="font-size: 14px;"></i>Direct Phone</label>':'';

							$ament.=($room['channel_music']==1)?'<label class="ament_label"><i class="fa fa-check" style="font-size: 14px;"></i>Channel Music</label>':'';

							$ament.=($room['attached_bathroom']==1)?'<label class="ament_label"><i class="fa fa-check" style="font-size: 14px;"></i>Attached Bathroom</label>':'';

							$ament.=($room['shower']==1)?'<label class="ament_label"><i class="fa fa-check" style="font-size: 14px;"></i>Shower</label>':'';

							$ament.=($room['bath_tub']==1)?'<label class="ament_label"><i class="fa fa-check" style="font-size: 14px;"></i>Bath Tub</label>':'';

							$ament.=($room['shower_bath_tub']==1)?'<label class="ament_label"><i class="fa fa-check" style="font-size: 14px;"></i>Shower Bath Tub</label>':'';

							$ament.=($room['minibar']==1)?'<label class="ament_label"><i class="fa fa-check" style="font-size: 14px;"></i>Minibar</label>':'';

							$ament.=($room['work_desk']==1)?'<label class="ament_label"><i class="fa fa-check" style="font-size: 14px;"></i>Work Desk</label>':'';

							$ament.=($room['balcony']==1)?'<label class="ament_label"><i class="fa fa-check" style="font-size: 14px;"></i>Balcony</label>':'';

							$ament.=($room['radio']==1)?'<label class="ament_label"><i class="fa fa-check" style="font-size: 14px;"></i>Radio</label>':'';

							$ament.=($room['clock']==1)?'<label class="ament_label"><i class="fa fa-check" style="font-size: 14px;"></i>Clock</label>':'';

							$ament.=($room['hair_dryer']==1)?'<label class="ament_label"><i class="fa fa-check" style="font-size: 14px;"></i>Hair Dryer</label>':'';

							$ament.=($room['fire_place']==1)?'<label class="ament_label"><i class="fa fa-check" style="font-size: 14px;"></i>Fire Place</label>':'';

							$ament.=($room['safe_deposit_box']==1)?'<label class="ament_label"><i class="fa fa-check" style="font-size: 14px;"></i>Safe Deposit Box</label>':'';

							$ament.=($room['smoke_alarms']==1)?'<label class="ament_label"><i class="fa fa-check" style="font-size: 14px;"></i>Smoke Alarms</label>':'';

							$ament.=($room['sprinklers']==1)?'<label class="ament_label"><i class="fa fa-check" style="font-size: 14px;"></i>Sprinklers</label>':'';

							$ament.=($room['double_bed']==1)?'<label class="ament_label"><i class="fa fa-check" style="font-size: 14px;"></i>Double Bed</label>':'';

							$ament.=($room['king_bed']==1)?'<label class="ament_label"><i class="fa fa-check" style="font-size: 14px;"></i>King Bed</label>':'';

							$ament.=($room['high_speed_wi_fi_internet_access']==1)?'<label class="ament_label"><i class="fa fa-check" style="font-size: 14px;"></i>High Speed Wi_fi Internet Access</label>':'';

							$ament.=($room['lcd_tv']==1)?'<label class="ament_label"><i class="fa fa-check" style="font-size: 14px;"></i>LCD Tv</label>':'';

							$ament.=($room['sound_proof_windows']==1)?'<label class="ament_label"><i class="fa fa-check" style="font-size: 14px;"></i>Sound Proof Windows</label>':'';

							$ament.=($room['24_hour_room_service']==1)?'<label class="ament_label"><i class="fa fa-check" style="font-size: 14px;"></i>24 Hour Room Service</label>':'';

							$ament.=($room['electronic_lock']==1)?'<label class="ament_label"><i class="fa fa-check" style="font-size: 14px;"></i>Electronic Lock</label>':'';

							$ament.=($room['electronic_laptop_compatible_safe']==1)?'<label class="ament_label"><i class="fa fa-check" style="font-size: 14px;"></i>Electronic Laptop Compatible Safe</label>':'';

							$ament.=($room['marble_flooring']==1)?'<label class="ament_label"><i class="fa fa-check" style="font-size: 14px;"></i>Marble Flooring</label>':'';

							$ament.=($room['study_table']==1)?'<label class="ament_label"><i class="fa fa-check" style="font-size: 14px;"></i>Study Table</label>':'';

							$ament.=($room['free_local_phone_calls']==1)?'<label class="ament_label"><i class="fa fa-check" style="font-size: 14px;"></i>Free Local Phone Calls</label>':'';

							$ament.=($room['iron_with_ironing_board']==1)?'<label class="ament_label"><i class="fa fa-check" style="font-size: 14px;"></i>Iron With Ironing Board</label>':'';

							$ament.=($room['full_length_mirror']==1)?'<label class="ament_label"><i class="fa fa-check" style="font-size: 14px;"></i>Full Length Mirror</label>':'';

							$ament.=($room['complimentary_toiletries']==1)?'<label class="ament_label"><i class="fa fa-check" style="font-size: 14px;"></i>Complimentary Toiletries</label>':'';

							$ament.=($room['internet']==1)?'<label class="ament_label"><i class="fa fa-check" style="font-size: 14px;"></i>Internet</label>':'';

							$ament.=($room['tea_coffee_maker']==1)?'<label class="ament_label"><i class="fa fa-check" style="font-size: 14px;"></i>Tea Coffee Maker</label>':'';

							$ament.=($room['complimentary_tea_coffee']==1)?'<label class="ament_label"><i class="fa fa-check" style="font-size: 14px;"></i>Complimentary Tea Coffee</label>':'';

							$ament.=($room['complimentary_packed_water_bottles']==1)?'<label class="ament_label"><i class="fa fa-check" style="font-size: 14px;"></i>Complimentary Packed Water Bottles</label>':'';

							$ament.=($room['carpet_flooring']==1)?'<label class="ament_label"><i class="fa fa-check" style="font-size: 14px;"></i>Carpet Flooring</label>':'';

							$ament.=($room['complimentary_fruit_basket']==1)?'<label class="ament_label"><i class="fa fa-check" style="font-size: 14px;"></i>Complimentary Fruit Basket</label>':'';

							$ament.=($room['double_twin_beds']==1)?'<label class="ament_label"><i class="fa fa-check" style="font-size: 14px;"></i>Double Twin Beds</label>':'';

							$ament.=($room['direct_dialing_phone']==1)?'<label class="ament_label"><i class="fa fa-check" style="font-size: 14px;"></i>Direct Dialing Phone</label>':'';

							$ament.=($room['smoke_detector_alarms']==1)?'<label class="ament_label"><i class="fa fa-check" style="font-size: 14px;"></i>Smoke Detector Alarms</label>':'';

							$ament.=($room['on_the_bay']==1)?'<label class="ament_label"><i class="fa fa-check" style="font-size: 14px;"></i>On The Bay</label>':'';
							
							$ament.=($room['on_the_garden']==1)?'<label class="ament_label"><i class="fa fa-check" style="font-size: 14px;"></i>On The Garden</label>':'';

							$ament.=($room['on_the_lake']==1)?'<label class="ament_label"><i class="fa fa-check" style="font-size: 14px;"></i>On The Lake</label>':'';

							$ament.=($room['on_the_ocean']==1)?'<label class="ament_label"><i class="fa fa-check" style="font-size: 14px;"></i>On The Ocean</label>':'';

							$ament.=($room['on_the_park']==1)?'<label class="ament_label"><i class="fa fa-check" style="font-size: 14px;"></i>On The Park</label>':'';

							$ament.=($room['on_the_river']==1)?'<label class="ament_label"><i class="fa fa-check" style="font-size: 14px;"></i>On The River</label>':'';

							$ament.=($room['poolside_room']==1)?'<label class="ament_label"><i class="fa fa-check" style="font-size: 14px;"></i>Poolside Room</label>':'';

							$ament.=($room['garden_room']==1)?'<label class="ament_label"><i class="fa fa-check" style="font-size: 14px;"></i>Garden Room</label>':'';

							$ament.=($room['city_view']==1)?'<label class="ament_label"><i class="fa fa-check" style="font-size: 14px;"></i>City View</label>':'';

							$ament.=($room['mountain_view']==1)?'<label class="ament_label"><i class="fa fa-check" style="font-size: 14px;"></i>Mountain View</label>':'';

							$ament.=($room['sea_facing_room']==1)?'<label class="ament_label"><i class="fa fa-check" style="font-size: 14px;"></i>Sea Facing Room</label>':'';


							echo $ament;?></p>
						
					<div class="clearfix"></div>
					<div class="col-md-12 col-lg-12" style="margin-bottom:10px; color:#7c7c7c;">
						<?php foreach($room['room_amenities'] as $roomAmenitiesK => $roomAmenitiesV){ ?>
						<div class="col-md-3 col-lg-3"><i class="fa fa-check" aria-hidden="true"></i> <?php echo ucwords(str_replace('_',' ',$roomAmenitiesV)); ?></div>
						<?php } ?>
					</div></div>
                     </ul>
                  </div>
                  
               </article>
            </section>
            <!--//facilities-->
            <!--location-->
            <section id="location" class="tab-content ">
               <article>
                  <!--map-->
                  <div class="gmap" id="map_canvas">
<input type="hidden" id="address" value="<?php echo $availableRoomsList[0]['txtPropertyName'].', '.$availableRoomsList[0]['txtAddress1']; ?>"></span>
<iframe id="map" width="600" height="450"></iframe>
                  
                  	
                  </div>
                  <!--//<iframe src="https://www.google.com/maps/embed?pb=<?php echo $availableRoomsList[0]['maplink']; ?>" width="600" height="450" frameborder="0" style="border:0" allowfullscreen></iframe>map-->
               </article>
            </section>
            <!--//location-->
            <!--reviews-->
           
            <!--//reviews-->	
            <!--things to do-->
           
            <!--//things-to-do-->
         </section>
         <!--//accommodation content-->	
         <aside id="" class="right-sidebar widget-area one-fourth" role="complementary">
            <ul>
               <li>
                  <article class="accommodation-details hotel-details">
                   <!--  <h1><?php echo $propertyName[0]['txtPropertyName']; ?>
                
                     </h1>-->
                   <h3 class="room_type_name"></h3>

                   <?php 
              $priceFirst = current($price);

                    $totalPriceInitial = (isset($priceFirst) && isset($guest) ? $priceFirst*count($guest) : 0);
                   
				 ?>
                     <div class="price">
                        Price <em class="price_under">
                        <span class="curr"><i class="fa fa-inr" aria-hidden="true"></i></span>
                        <span id="totalRateSpan" >           	
						<?php echo $totalPriceInitial; ?></span>
					

                  
                        </em><span class="inc_tax">Incl. Taxes</span>
                         <p style="padding: 0;text-align: right;margin-top: 4px;cursor: pointer;">
                     
                        <a type="button" class="" data-toggle="modal" data-target="#myModalFareDetails" style="font-size:10px;color:#dd3236;">Fare Breakup</a>
                    </p>
                     </div>
                     <div class='description'>
<p><?php echo count($guest); ?> room(s) for <?php echo ($numberOfNights!='')?$numberOfNights.' Night':''; ?></p>
                     </div> 
                     <!-- <div class="tags">
                        <ul>
                           <li> Lunch Inclusion<a href="#" data-toggle="modal" data-target="#myModalLunch" style="color: #23527c;"><span class="glyphicon glyphicon-info-sign"></span></a></li>
                           <li>Dinner Inclusion<a href="#" data-toggle="modal" data-target="#myModalDinner" style="color: #23527c;"><span class="glyphicon glyphicon-info-sign"></span></a></li>
                          
                        </ul>
                     </div> -->

                     <div class=" checkbooking" style="padding:0" >
                     	
            
           
                <div class="col-sm-6">
                   <label>Check In</label>
                    <i class="fa fa-calendar  icon_for_rel" aria-hidden="true" style="top: 43px;"></i>
                    <input  type="text" class="form-control datepi" id="hotels_month_book" placeholder="checkin" value="<?php echo (isset($check_in) ? $check_in : ''); ?>">

                </div>
                <div class="col-sm-6" >
                	<label>Check Out</label>
                     <i class="fa fa-calendar  icon_for_rel" aria-hidden="true" style="top: 43px;"></i>
                      <input  type="testimonialsxt" class="form-control datepi" id="hotels_month1_book" placeholder="checkout" value="<?php echo (isset($check_out) ? $check_out : ''); ?>">
                </div>

                <div class="clearfix"></div>
 

				<div class="col-md-12" style="margin-top:20px;padding: 0px;">
					<div class="section_room" style="position: relative">
					<!--<input type="hidden" id="guest" name="guest" value="">--><label class="add_rooms_plus" style="color: #dd3236;float: right;font-size: 12px;"><span  style="font-size: 14px;">+</span> Rooms</label>
					
					<input type="hidden" id="guestval" value="<?php echo $guestcount?>">
					<input type="hidden" id="roomval" value="<?php echo $key?>">
					
					<button type="button"  id="button-pax-container" style="border-radius: 3px;"><span class="total_guest_room"><?php echo $guestText;?></span></button>

					


                    <div class="collapse room_res2" id="demo" >
					<div class="outer_div_search"><div class="inner_demo outer_pax">
					<?php echo $searchguest;?>
					</div>
					<div class="con_for_but"></div>
					<button class="rooms_in_hotel btn btn-primary" data="<?php echo $key+1; ?>" type="button" onclick="addroom(this)" >+ Add Room</button>
					<span class="done" onclick="ondone()">Done</span>

					</div>
				    </div>
				 <form class="book-hotel-form" method="get" name="hotel_book" action="book.php">
					  <input type="hidden" name="id_property" value="<?php echo $id_property; ?>">
					  <input type="hidden" name="goingto" value="<?php echo $city_name; ?>">
					  <input type="hidden" name="to_hidden" value="<?php echo $city_id; ?>">
					  <input type="hidden" name="hotel_month" id="hotel_month" value="<?php echo $check_in; ?>">
					  <input type="hidden" name="hotel_month1" id="hotel_month1" value="<?php echo $check_out; ?>">
					  <input type="hidden" name="guestJSON" id="guestJSON" value="">
					  <?php echo (isset($guestCntHidden) ? $guestCntHidden : ''); ?>
						<input type="hidden" name="guest_text" value="<?php echo (isset($guestText) ? $guestText : ''); ?>">
				   <select  style=" visibility: hidden;z-index:-1;position:absolute:bottom:-9999999px" class="hotelname form-control" id="hotelname" name="adult">
						<?php if(isset($roomNames) && !empty($roomNames)){
								foreach($roomNames as $roomNamesK => $roomNamesV){
									$idRoomTypeArr = explode('_',$roomNamesK);
									echo '<option value="'.$roomNamesK.'" '.(empty($roomNamesK) ? 'selected' : '').' data-rate="'.(isset($price[$roomNamesK]) ? $price[$roomNamesK] : '').'" data-id-room="'.$idRoomTypeArr[0].'" data-id-room-type="'.$idRoomTypeArr[1].'">'.$roomNamesV.'</option>'; 
								}
							}
						?>
					</select>
				</form>
				 <div class="clearfix"></div>

				</div>
               </div>
              

				<div class="col-md-12" style=" margin-top:20px; padding: 0px;<?php if($noRoomsFlag){ ?>display:none;<?php } ?>">
					<div >
						<!--<div class="checkbox">
						  <a href="#" data-toggle="tooltip" title="Per Adult: Rs.<?php echo $rateLunchAdult; ?>, Per Child: Rs.<?php echo $rateLunchMoreThanFive; ?> (5-12 yrs), Rs.<?php echo $rateLunchLessThanFive; ?> (0-5 yrs)" data-placement="top"><label><input type="checkbox" value="" class="extraRateLunch" data-type="1">Lunch Incl.</label></a>
						  <label><input type="checkbox" value="" class="extraRateLunch" data-type="1">Lunch Incl.</label>
						  <a  href="javascript:void(0)"  class="lunch_inclus" ><span class="glyphicon glyphicon-info-sign"></span></a>
						 


						</div>
					</div>-->
					<div class="checkbox_width">
						<div class="checkbox ">
                    <!--<a href="#" data-toggle="tooltip" title="Per Adult: Rs.<?php echo $rateLunchAdult; ?>, Per Child: Rs.<?php echo $rateLunchMoreThanFive; ?> (5-12 yrs), Rs.<?php echo $rateLunchLessThanFive; ?> (0-5 yrs)" data-placement="top"><label><input type="checkbox" value="" class="extraRateLunch" data-type="1">Lunch Incl.</label></a>-->
                     <!-- <li><a href="#"><input type="checkbox" data-sort-type="hoteltype" data-value="1"> -->
                     <div class="col-xs-6" style="padding: 0">
                    <label ><input type="checkbox" value="" class="extraRateLunch" data-type="1" style="margin-left: -21px;">Lunch Incl.</label>
                    <a href="#" data-toggle="modal" data-target="#myModalLunch" ><span class="glyphicon glyphicon-info-sign"    style=" position: relative;top: 1px;"></span></a></div>

                    <div class="col-xs-6 " style="padding: 0">
                    <label ><input type="checkbox" value="" class="extraRateDinner" data-type="2" style=" ">Dinner Incl.</label>
                    <a href="#" data-toggle="modal" data-target="#myModalDinner" ><span class="glyphicon glyphicon-info-sign"></span></a></div>


                    <div class="modal fade" role="dialog" id="myModalLunch">
                     <div class="modal-dialog"  >
                       
                        <div class="modal-content">
                        	<div class="modal-header " >
						        <button type="button" class="close" data-dismiss="modal">&times;</button>
						        <h4 class="modal-title">Lunch Inclusion</h4>
						    </div>
                           
                           <div class="modal-body">
                              <div class="fare_break_mod " style="text-align: left;">
                              	
                                
                                    <p>
                                    	<span class="pull-left">Per Adult</span>
                                  		<i class="fa fa-inr" aria-hidden="true"></i>
                                  		<Span class="pull-right">
                                   
                                    <?php echo $rateLunchAdult; ?></Span></p>
                               
                                    <p><span class="pull-left">Per Child (5-12 yrs)</span>
                                  		<i class="fa fa-inr" aria-hidden="true"></i>
                                  		<Span class="pull-right">
                                    	
                                    <?php echo $rateLunchMoreThanFive; ?></p>
                                
                                
                                   <p> 
                                   	<span class="pull-left">Per Child (0-5 yrs)</span>
                                  		<i class="fa fa-inr" aria-hidden="true"></i>
                                  		<Span class="pull-right">
                                    
                                   <?php echo $rateLunchLessThanFive; ?></p>
                                
                                 
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
                  </div></div>
              </div>
					<div >
						<!--<div class="checkbox">
							<a href="#" data-toggle="tooltip" title="Per Adult: Rs.<?php echo $rateDinnerAdult; ?>, Per Child: Rs.<?php echo $rateDinnerMoreThanFive; ?> (5-12 yrs), Rs.<?php echo $rateDinnerLessThanFive; ?>(0-5 yrs)" data-placement="top"><label><input type="checkbox" value="" class="extraRateDinner" data-type="2">Dinner Incl.</label></a>
							<label><input type="checkbox" value="" class="extraRateDinner" data-type="2">Dinner Incl.</label>
							<a href="javascript:void(0)"  class="dinner_inclus"  ><span class="glyphicon glyphicon-info-sign"></span></a>

							 
					</div>--><div class="checkbox">
                     <!--<a href="#" data-toggle="tooltip" title="Per Adult: Rs.<?php echo $rateDinnerAdult; ?>, Per Child: Rs.<?php echo $rateDinnerMoreThanFive; ?> (5-12 yrs), Rs.<?php echo $rateDinnerLessThanFive; ?>(0-5 yrs)" data-placement="top"><label><input type="checkbox" value="" class="extraRateDinner" data-type="2">Dinner Incl.</label></a>-->
                     
                     <a href="#" data-toggle="modal" data-target="#myModalDinner" ></a>
                       <div class="modal fade" role="dialog" id="myModalDinner">
                        <div class="modal-dialog"  >
                           
                           <div class="modal-content">
                           	<div class="modal-header " >
						        <button type="button" class="close" data-dismiss="modal">&times;</button>
						        <h4 class="modal-title">Dinner Inclusion</h4>
						    </div>
                             
                              <div class="modal-body">
                                    <div class="fare_break_mod" style="text-align: left;">
										<p>
											<span class="pull-left">Per Adult</span>
                                  		<i class="fa fa-inr" aria-hidden="true"></i>
											<Span class="pull-right">
												
												<?php echo $rateDinnerAdult; ?>
											</Span>
										</p>
										<p><span class="pull-left">Per Child (5-12 yrs)</span>
                                  		<i class="fa fa-inr" aria-hidden="true"></i>
											<Span class="pull-right">
												<?php echo $rateDinnerMoreThanFive; ?>
											</Span>
										</p>
										<p>
											<span class="pull-left">Per Child (0-5 yrs)</span>
                                  		<i class="fa fa-inr" aria-hidden="true"></i>
											<span class="pull-right">
												
												<?php echo $rateDinnerLessThanFive; ?>

											</span>
										</p>
                                
                                   
                                 </div>
                              </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>

				</div>
                <div class="clearfix"></div>
				<?php
					$date_checkin = new DateTime(date('Y-m-d', strtotime($check_in)));
					$date_checkout = new DateTime(date('Y-m-d', strtotime($check_out)));

					// this calculates the diff between two dates, which is the number of nights
					$numberOfNights= $date_checkout->diff($date_checkin)->format("%a"); 
					
				?>
                <div class="col-md-12" style="text-align:center;margin-top: 20px;<?php if($noRoomsFlag){ ?>display:none;<?php } ?>" id="totalhotel">
                        <div class=" totalhotel" style="padding-left: 23px;padding: 0">
					
						
                  <div class="modal fade" role="dialog" id="myModalFareDetails">
                     <div class="modal-dialog"  >
                        
                        <div class="modal-content">
                        	<div class="modal-header " >
						        <button type="button" class="close" data-dismiss="modal">&times;</button>
						        <h4 class="modal-title">Fare Breakup</h4>
						    </div>
                          
                           <div class="modal-body">
                                  <div class="fare_break_mod" style="text-align: left;">


                                  	<p ><span class="pull-left">Base Fare</span>
                                  		<i class="fa fa-inr" aria-hidden="true"></i>
											<span class="pull-right">
												
												<span id="baseRateDiv" ></span>
											</span>
										</p>
										<p id="lunchRateParentDiv" style="display:none;">
											
											<span class="pull-left">Lunch Incl.</span>
                                  		<i class="fa fa-inr" aria-hidden="true"></i>
											<span class="pull-right">
												
												   <span class="col-xs-5" id="lunchRateDiv" >
												   	
												   </span>
											</span>
										</p>
										<p id="dinnerRateParentDiv" style="display:none;">
											<span class="pull-left">Dinner Incl.</span>
                                  		<i class="fa fa-inr" aria-hidden="true"></i>
											<span class="pull-right">
												
												<span id="dinnerRateDiv"></span>
											</span>
										</p>
										<p>
												<span class="pull-left">Tax CGST</span>
                                  		<i class="fa fa-inr" aria-hidden="true"></i>
											<span class="pull-right">
												
												<span id="cgstRateDiv" ><?php echo $totalPriceInitial*($availableRoomsList[0]['txtCGST']/100); ?></span>
											</span>
										</p>
										<p><span class="pull-left">Tax SGST</span>
                                  		<i class="fa fa-inr" aria-hidden="true"></i>
											<span class="pull-right">
											
												<span id="sgstRateDiv"><?php echo $totalPriceInitial*($availableRoomsList[0]['txtSGST']/100); ?></span>
											</span>
										</p>
										<p><span class="pull-left">Total</span>
                                  		<i class="fa fa-inr" aria-hidden="true"></i>
											<span class="pull-right">
												
												<span id="totalRateDiv"><?php echo $totalPriceInitial + ($totalPriceInitial*($availableRoomsList[0]['txtSGST']/100)) + ($totalPriceInitial*($availableRoomsList[0]['txtSGST']/100)); ?></span>
											</span>
										</p>
										
                             
                             
                           
                           </div>
                        </div>
                     </div>
                  </div>
					
					
					</div>
			

                    <div class="col-md-12 book_button_hotel">
                     		<div class="col-md-12 book_button_hotel">

                     		  <form name="book_room" action="<?php echo $root_dir;?>hotels/search/booking/booknow.php" id="book_room" method="POST">
					
							<?php
								$_SESSION['check_in'] = (isset($check_in) ? $check_in : '');
								$_SESSION['check_out'] = (isset($check_out) ? $check_out : '');
								$_SESSION['city_name'] = (isset($city_name) ? $city_name : '');
								$_SESSION['city_id'] = (isset($city_id) ? $city_id : '');
			
								$_SESSION['room_id'] = (isset($availableRoomsList[0]['id_room']) ? $availableRoomsList[0]['id_room'] : 0);
								$_SESSION['room_type_id'] = (isset($availableRoomsList[0]['id_room_type']) ? $availableRoomsList[0]['id_room_type'] : 0);
								$_SESSION['guest_text'] = (isset($guestText) ? $guestText : '');
								$_SESSION['no_of_nights'] = (isset($numberOfNights) ? $numberOfNights : '');
								$_SESSION['room_count'] = (isset($guest) && !empty($guest) ? count($guest) : 0);
							?>
							<input type="Hidden" name="check_in" id="check_in" value="<?php echo (isset($check_in) ? $check_in : ''); ?>">
							<input type="Hidden" name="check_out" id="check_out" value="<?php echo (isset($check_out) ? $check_out : ''); ?>">
							<input type="Hidden" name="city_name" id="city_name" value="<?php echo (isset($city_name) ? $city_name : ''); ?>">
							<input type="Hidden" name="city_id" id="city_id" value="<?php echo (isset($city_id) ? $city_id : ''); ?>">
							<input type="Hidden" name="room_rate" id="room_rate" value="<?php echo (isset($priceFirst) ? $priceFirst : 0); ?>">
							<input type="Hidden" name="room_base_rate" id="room_base_rate" value="<?php echo (isset($availableRoomsList[0]['baserate']) ? $availableRoomsList[0]['baserate'] : 0); ?>">
							<input type="Hidden" name="room_id" id="room_id" value="<?php echo (isset($availableRoomsList[0]['id_room']) ? $availableRoomsList[0]['id_room'] : 0); ?>">
							<input type="Hidden" name="room_type_id" id="room_type_id" value="<?php echo (isset($availableRoomsList[0]['id_room_type']) ? $availableRoomsList[0]['id_room_type'] : 0); ?>">
							<?php echo (isset($guestCntHidden) ? $guestCntHidden : ''); ?>
							<input type="Hidden" name="guest_text" id="guest_text" value="<?php echo (isset($guestText) ? $guestText : ''); ?>">
							<input type="Hidden" name="no_of_nights" value="<?php echo (isset($numberOfNights) ? $numberOfNights : ''); ?>">
							<input type="Hidden" name="room_count" id="room_count" value="<?php echo (isset($guest) && !empty($guest) ? count($guest) : 0); ?>">
							<input type="Hidden" name="meal_rate" id="meal_rate" value="">
							<input type="Hidden" name="breakfast_incl" id="breakfast_incl" value="<?php echo (isset($availableRoomsList[0]['is_breakfast']) ? $availableRoomsList[0]['is_breakfast'] : 0); ?>">
							<input type="Hidden" name="lunch_incl" id="lunch_incl" value="">
							<input type="Hidden" name="dinner_incl" id="dinner_incl" value="">
							<input type="Hidden" name="id_property" id="id_property" value="<?php echo (isset($id_property) ? $id_property : ''); ?>">
							 <button type="submit" class=" btn btn-primary  btnBook11  "  value="Book Now">
							 	Book Now
							 </button>
							
							 
						</form>
                       </div>
					
                    </div>
                    <div class="clearfix"></div>
                </div>

            <!--</form>-->
            <div class="clearfix"></div>
        </div>
    </div>
                  </article>
               </li>
               <li>
                  <!--testimonials-->
                  <!-- <article class="testimonials">
                     <blockquote>asd</blockquote>
                     <span class="name">Libero</span>
                     </article> -->
                  <!--//testimonials-->
               </li>
             
               <li class="widget widget-sidebar details">
                  <!--deals-->
                  <ul class="small-list">
                     <h4>Explore our latest hotels</h4>
                     <li>
                        <a href="">
                           <h3>Youth Hostel 
                           <span class="stars topx">
                              <i class="material-icons">&#xE838;</i>
                              <i class="material-icons">&#xE838;</i>
                              </span>

                              <span class="address map_marker_icon">
							  <i class="fa fa-map-marker" aria-hidden="true"></i><span class="txtlandmark"> Landmark, City, Country</span>
                             </span>
                           </h3>
                           <p>
                              <span class="curr"><i class="fa fa-inr" aria-hidden="true"></i></span><span class="amount">95</span> per night			
                           </p>
                           <span class='rating'>4 / 10</span>		
                        </a>
                     </li>
                     <li>
                        <a href="">
                           <h3>Tango hotel 			
                           	<span class="stars">
                              <i class="material-icons">&#xE838;</i>
                              <i class="material-icons">&#xE838;</i>
                              <i class="material-icons">&#xE838;</i>
                              </span>

                              <span class="address map_marker_icon">
							  <i class="fa fa-map-marker" aria-hidden="true"></i><span class="txtlandmark"> Landmark, City, Country</span>
                             </span>
                           </h3>
                           <p>
                              <span class="curr"><i class="fa fa-inr" aria-hidden="true"></i></span><span class="amount">115</span> per night			
                           </p>
                           <span class='rating'>13 / 10</span>		
                        </a>
                     </li>
                     <li>
                        <a href="">
                           <h3>Sirtaki Hotel 		
                           		<span class="stars">
                              <i class="material-icons">&#xE838;</i>
                              <i class="material-icons">&#xE838;</i>
                              <i class="material-icons">&#xE838;</i>
                              </span>

                              <span class="address map_marker_icon">
							  <i class="fa fa-map-marker" aria-hidden="true"></i><span class="txtlandmark"> Landmark, City, Country</span>
                             </span>
                           </h3>
                           <p>
                              <span class="curr"><i class="fa fa-inr" aria-hidden="true"></i></span><span class="amount">176</span> per night			
                           </p>
                        </a>
                     </li>
                     <li>
                        <a href="">
                           <h3>Queen Hotel 			
                           	<span class="stars">
                              <i class="material-icons">&#xE838;</i>
                              <i class="material-icons">&#xE838;</i>
                              <i class="material-icons">&#xE838;</i>
                              <i class="material-icons">&#xE838;</i>
                              <i class="material-icons">&#xE838;</i>
                              </span>

                              <span class="address map_marker_icon">
							  <i class="fa fa-map-marker" aria-hidden="true"></i><span class="txtlandmark"> Landmark, City, Country</span>
                             </span>
                           </h3>
                           <p>
                              <span class="curr"><i class="fa fa-inr" aria-hidden="true"></i></span><span class="amount">215</span> per night			
                           </p>
                        </a>
                     </li>
                  </ul>
                  <!--//deals-->
               </li>
            </ul>
         </aside>
         <!-- #secondary -->	
      </div>
      <div class="booking_form_controls_holder" style="display:none">
         <div class="text-wrap booking_terms">
            <div>
               <p>Select your check-in and check-out dates using the calendar below to book this accommodation.</p>
               <p>
                  <span class="min">Minimum days stay <strong>3</strong></span>
               </p>
            </div>
         </div>
         <div class="row calendar-colors">
            <div class="f-item full-width">
               <div class="today"><span></span>Today</div>
               <div class="selected"><span></span>Selected</div>
               <div class="available"><span></span>Available</div>
               <div class="unavailable"><span></span>Unavailable</div>
            </div>
         </div>
         <div class="error step1_error text-wrap" style="display:none;">
            <div>
               <p></p>
            </div>
         </div>
         <div class="row calendar">
            <div class="f-item full-width">
               <div class="datepicker_holder"></div>
            </div>
         </div>
         <div class="row loading" id="datepicker_loading" style="display:none">
            <div class="ball"></div>
            <div class="ball1"></div>
         </div>
         <div class="text-wrap price_row" style="display:none">
            <h3>Who is checking in?</h3>
            <p>Please select number of adults and children checking into the accommodation using the controls you see below.</p>
            <div class="row">
               <div class="f-item one-half booking_form_adults_div">
                  <label for="booking_form_adults">Adults</label>
                  <select class="dynamic_control" id="booking_form_adults" name="booking_form_adults"></select>
               </div>
               <div class="f-item one-half booking_form_children_div">
                  <label for="booking_form_children">Children</label>
                  <select class="dynamic_control" id="booking_form_children" name="booking_form_children"></select>
               </div>
            </div>
         </div>
         <div class="text-wrap price_row extra_items_row" style="display:none">
            <h3>Extra items</h3>
            <p>Please select the extra items you wish to be included with your accommodation using the controls you see below.</p>
            <table class="extraitems responsive">
               <thead>
                  <tr>
                     <th>Item</th>
                     <th>Price</th>
                     <th>Per person?</th>
                     <th>Per day?</th>
                     <th>Quantity</th>
                  </tr>
               </thead>
               <tbody>
                  <script>
                     window.requiredExtraItems = [];
                  </script>
                  <tr>
                     <td>
                        <span id="extra_item_title_4985">Cleaning</span>
                        <i>
                           <p>This is some text to describe the cleaning service offered by accommodation.</p>
                        </i>
                     </td>
                     <td>
                        <em>
                        <span class="curr">$</span>
                        <span class="amount">30</span>
                        <input type="hidden" value="30" name="extra_item_price_4985" id="extra_item_price_4985" />
                        <input type="hidden" value="0" name="extra_item_price_per_person_4985" id="extra_item_price_per_person_4985" />
                        <input type="hidden" value="1" name="extra_item_price_per_day_4985" id="extra_item_price_per_day_4985" />
                        </em>							
                     </td>
                     <td>No</td>
                     <td>Yes</td>
                     <td>
                        <select class="extra_item_quantity dynamic_control" name="extra_item_quantity_4985" id="extra_item_quantity_4985">
                           <option value="0">0</option>
                           <option value="1">1</option>
                        </select>
                     </td>
                  </tr>
                  <script>
                     window.requiredExtraItems.push(5795);
                  </script>							
                  <tr>
                     <td>
                        <span id="extra_item_title_5795">Tourist Tax</span>
                        <i>
                           <p>This is a mandatory surcharge.</p>
                        </i>
                     </td>
                     <td>
                        <em>
                        <span class="curr">$</span>
                        <span class="amount">1</span>
                        <input type="hidden" value="1" name="extra_item_price_5795" id="extra_item_price_5795" />
                        <input type="hidden" value="1" name="extra_item_price_per_person_5795" id="extra_item_price_per_person_5795" />
                        <input type="hidden" value="1" name="extra_item_price_per_day_5795" id="extra_item_price_per_day_5795" />
                        </em>							
                     </td>
                     <td>Yes</td>
                     <td>Yes</td>
                     <td>
                        <select class="extra_item_quantity dynamic_control" name="extra_item_quantity_5795" id="extra_item_quantity_5795">
                           <option value="1">1</option>
                        </select>
                     </td>
                  </tr>
                  <tr>
                     <td>
                        <span id="extra_item_title_4986">Wifi</span>
                        <i>
                           <p>This is some information to describe wifi service.</p>
                        </i>
                     </td>
                     <td>
                        <em>
                        <span class="curr">$</span>
                        <span class="amount">10</span>
                        <input type="hidden" value="10" name="extra_item_price_4986" id="extra_item_price_4986" />
                        <input type="hidden" value="1" name="extra_item_price_per_person_4986" id="extra_item_price_per_person_4986" />
                        <input type="hidden" value="1" name="extra_item_price_per_day_4986" id="extra_item_price_per_day_4986" />
                        </em>							
                     </td>
                     <td>Yes</td>
                     <td>Yes</td>
                     <td>
                        <select class="extra_item_quantity dynamic_control" name="extra_item_quantity_4986" id="extra_item_quantity_4986">
                           <option value="0">0</option>
                           <option value="1">1</option>
                           <option value="2">2</option>
                           <option value="3">3</option>
                           <option value="4">4</option>
                           <option value="5">5</option>
                        </select>
                     </td>
                  </tr>
               </tbody>
               <tfoot></tfoot>
            </table>
         </div>
         <div class="text-wrap dates_row" style="display:none">
            <h3>Summary</h3>
            <p>The summary of your accommodation booking is shown below.</p>
            <table class="summary responsive">
               <tbody>
                  <tr>
                     <th>Check in</th>
                     <td>
                        <span class="date_from_text"></span>
                        <input type="hidden" name="selected_date_from" id="selected_date_from" value="" />
                     </td>
                  </tr>
                  <tr>
                     <th>Check out</th>
                     <td>
                        <span class="date_to_text">Select your check out date using the calendar above.</span>
                        <input type="hidden" name="selected_date_to" id="selected_date_to" value="" />
                     </td>
                  </tr>
                  <tr class="room_type_row" style="display:none">
                     <th>
                        Room type						
                     </th>
                     <td>
                        <span class="room_type_span"></span>
                        <input type="hidden" name="room_type_id" id="room_type_id" />							
                     </td>
                  </tr>
                  <tr class=" people_count_div" style="display:none">
                     <th>
                        People						
                     </th>
                     <td>
                        <span class="people_text">1</span>
                     </td>
                  </tr>
                  <tr class=" adult_count_div">
                     <th>
                        Adults						
                     </th>
                     <td>
                        <span class="adults_text">1</span>
                     </td>
                  </tr>
                  <tr class=" children_count_div">
                     <th>
                        Children						
                     </th>
                     <td>
                        <span class="children_text">0</span>
                     </td>
                  </tr>
                  <tr>
                     <th>
                        Reservation total						
                     </th>
                     <td>
                        <span class="reservation_total"></span>
                     </td>
                  </tr>
                  <tr class="extra_items_breakdown_row">
                     <th>
                        Extra items total						
                     </th>
                     <td>
                        <span class="extra_items_total"></span>
                     </td>
                  </tr>
               </tbody>
               <tfoot>
                  <tr>
                     <th>
                        Total price						
                     </th>
                     <td class="total_price"></td>
                  </tr>
               </tfoot>
            </table>
            <a href="#" class="toggle_breakdown show_breakdown">Show price breakdown</a>
            <div class="row price_breakdown_row hidden" style="display:none">
               <div class="f-item full-width">
                  <label>Accommodation price breakdown</label>
                  <table class="accommodation_price_breakdown tablesorter responsive">
                     <thead></thead>
                     <tbody></tbody>
                     <tfoot></tfoot>
                  </table>
               </div>
            </div>
            <div class="row price_breakdown_row extra_items_breakdown_row" style="display:none">
               <div class="f-item full-width">
                  <label>Extra items price breakdown</label>
                  <table class="extra_items_price_breakdown tablesorter responsive">
                     <thead></thead>
                     <tbody></tbody>
                     <tfoot></tfoot>
                  </table>
               </div>
            </div>
         </div>
         <div class='booking-commands'>
            <a href='#' class='gradient-button book-accommodation-reset'  id='book-accommodation-rest'  title='Reset'>Reset</a><a href='#' class='gradient-button book-accommodation-next'  id='book-accommodation-next'  title='Proceed'>Proceed</a>	
         </div>
      </div>
      <!--//main content-->
   </div>
   <!--//wrap-->
</div>
 <script>

            $(document).ready(function() {
              var owl = $('.hotel_carousels');
              owl.owlCarousel({

              	 responsive:{
                0:{
                    items:1
                },
                600:{
                    items:2
                },
                1000:{
                    items:4
                }
            },
               
                loop: false,
                margin: 10,
               
               
               
                
              });
             
            })
          </script>



<script type="text/javascript">
    jQuery(document).ready(function($) {
		$('.extraRateLunch').click(function(){
			var roomRate = $('#room_rate').val();
			var roomCnt = $('#room_count').val();
			var total = parseInt(roomRate)*parseInt(roomCnt);
			var aCnt  = <?php echo $countadult; ?>;
			var cCnt  = <?php echo $countchildmore; ?>;
			var cCnt1  = <?php echo $countchildless; ?>;
			var aPrice  = <?php echo $rateLunchAdult; ?>;
			var acPrice  = <?php echo $rateLunchMoreThanFive; ?>;
			var acPrice1  = <?php echo $rateLunchLessThanFive; ?>;

			var meal_rate = $('#meal_rate').val();
			var amt = parseInt(aCnt)*parseInt(aPrice);
			var amt1 = parseInt(cCnt)*parseInt(acPrice);
			amt1 = parseInt(amt1) + parseInt(cCnt1*acPrice1);
		//	console.log(roomRate+'roomRate'+roomCnt+'roomCnt'+total+'total'+aCnt+'aCnt'+cCnt+'cCnt'+cCnt1+'cCnt1'+aPrice+'aPrice'+acPrice+'acPrice'+acPrice1+'acPrice1'+meal_rate+'meal_rate'+amt+'amt'+amt1+'amt1');
			if($(this).prop('checked')){
				$('#lunch_incl').val(1);
				if(meal_rate > 0)
					$('#meal_rate').val(parseInt(meal_rate) + parseInt(amt)+parseInt(amt1));
				else
					$('#meal_rate').val(parseInt(amt)+parseInt(amt1));
				
				$('#lunchRateDiv').html(parseInt(amt)+parseInt(amt1));
				$('#lunchRateParentDiv').show();
				$('#totalRateDiv').html(Math.round(parseFloat($('#totalRateDiv').html())+parseInt(amt)+parseInt(amt1)));

				$('#totalRateSpan').html(Math.round(parseInt($('#totalRateDiv').html())));

				var meal_rate_final = $('#meal_rate').val();
				//$('#totalRateSpan').html(parseInt(total)+parseInt(meal_rate_final));
			}else{
				$('#lunch_incl').val(0);
				var meal_amt = parseInt(amt)+parseInt(amt1);
				if(meal_rate > 0)
					$('#meal_rate').val(parseInt(meal_rate) - parseInt(meal_amt));

				$('#lunchRateParentDiv').hide();
				$('#totalRateDiv').html(parseFloat($('#totalRateDiv').html()) - parseInt(meal_amt));
				$('#totalRateSpan').html(Math.round(parseInt($('#totalRateDiv').html())));
				var meal_rate_final = $('#meal_rate').val();
				//$('#totalRateSpan').html(parseInt(total)+parseInt(meal_rate_final));
			}
			/* if($(this).prop('checked')){
            $('#lunch_incl').val(1);
            if(meal_rate > 0)
               $('#meal_rate').val(parseInt(meal_rate) + parseInt(amt)+parseInt(amt1));
            else
               $('#meal_rate').val(parseInt(amt)+parseInt(amt1));
            
            $('#lunchRateDiv').html(parseInt(amt)+parseInt(amt1));
            $('#lunchRateParentDiv').show();
            $('#totalRateDiv').html(Math.round(parseFloat($('#totalRateDiv').html())+parseInt(amt)+parseInt(amt1)));
            $('#totalRateSpan').html($('#totalRateDiv').html());

            var meal_rate_final = $('#meal_rate').val();
            //$('#totalRateSpan').html(parseInt(total)+parseInt(meal_rate_final));
         }else{
            $('#lunch_incl').val(0);
            var meal_amt = parseInt(amt)+parseInt(amt1);
            if(meal_rate > 0)
               $('#meal_rate').val(parseInt(meal_rate) - parseInt(meal_amt));

            $('#lunchRateParentDiv').hide();
            $('#totalRateDiv').html(Math.round(parseFloat($('#totalRateDiv').html()) - parseInt(meal_amt)));
            $('#totalRateSpan').html($('#totalRateDiv').html());
            var meal_rate_final = $('#meal_rate').val();
            //$('#totalRateSpan').html(parseInt(total)+parseInt(meal_rate_final));
         }*/
		});
		$('.extraRateDinner').click(function(){
			var roomRate = $('#room_rate').val();
			var roomCnt = $('#room_count').val();
			var total = parseInt(roomRate)*parseInt(roomCnt);
			var aCnt  = <?php echo $countadult; ?>;
			var cCnt  = <?php echo $countchildmore; ?>;
			var cCnt1  = <?php echo $countchildless; ?>;
			var cPrice  = <?php echo $rateDinnerAdult; ?>;
			var ccPrice  = <?php echo $rateDinnerMoreThanFive; ?>;
			var ccPrice1  = <?php echo $rateDinnerLessThanFive; ?>;
			
			var meal_rate = $('#meal_rate').val();
			var amt = aCnt*cPrice;
			var amt1 = cCnt*ccPrice;
			amt1 = parseInt(amt1) + parseInt(cCnt1*ccPrice1);
			if($(this).prop('checked')){
				$('#dinner_incl').val(1);
				if(meal_rate > 0)
					$('#meal_rate').val(parseInt(meal_rate) + parseInt(amt)+parseInt(amt1));
				else
					$('#meal_rate').val(parseInt(amt)+parseInt(amt1));

				$('#dinnerRateDiv').html(parseInt(amt)+parseInt(amt1));
				$('#dinnerRateParentDiv').show();
				$('#totalRateDiv').html(parseFloat($('#totalRateDiv').html())+parseInt(amt)+parseInt(amt1));
				$('#totalRateSpan').html(Math.round(parseInt($('#totalRateDiv').html())));
				
				var meal_rate_final = $('#meal_rate').val();
				//$('#totalRateSpan').html(parseInt(total)+parseInt(meal_rate_final));
			}else{
				$('#dinner_incl').val(0);
				var meal_amt = parseInt(amt)+parseInt(amt1);
				if(meal_rate > 0)
					$('#meal_rate').val(parseInt(meal_rate) - parseInt(meal_amt));

				$('#dinnerRateParentDiv').hide();
				$('#totalRateDiv').html(parseFloat($('#totalRateDiv').html()) - parseInt(meal_amt));
				$('#totalRateSpan').html(Math.round(parseInt($('#totalRateDiv').html())));

				var meal_rate_final = $('#meal_rate').val();
				//$('#totalRateSpan').html(parseInt(total) + parseInt(meal_rate_final));
			}
		});
		$('.extraRateLunch').prop('checked',false);
		$('.extraRateDinner').prop('checked',false);

		$('[data-toggle="tooltip"]').tooltip(); 
		$('.room_details_para a').click(function(){
			
			 $(this).toggleClass('room_detaisa');
			$(this).find('i').toggleClass('fa-angle-down fa-angle-up');
		});
		$('#all_amenities_a').click(function(){
			$(this).find('i').toggleClass('glyphicon-plus glyphicon-minus');
		});
		
		$('.hotelname').change(function(){
			var roomCnt = $('#room_count').val();
			var id_room = $(this).find(':selected').attr('data-id-room');
			var id_room_type = $(this).find(':selected').attr('data-id-room-type');
			var room_type_name = $(this).find(':selected').text();
			$(".room_type_name").text(room_type_name);
			var roomRate = $(this).find(':selected').attr('data-rate');
			var roomBaseRate = $(this).find(':selected').attr('data-base-rate');
			//$('#totalRateSpan').html(parseFloat(roomRate)*parseInt(roomCnt));
			var baseRate = parseFloat(roomRate)*parseInt(roomCnt);
			var cgstP = '<?php echo $availableRoomsList[0]['txtCGST']; ?>';
			var sgstP = '<?php echo $availableRoomsList[0]['txtSGST']; ?>';
			$('#baseRateDiv').html(baseRate);
			$('#cgstRateDiv').html(parseFloat(baseRate*(parseInt(cgstP)/100)));
			$('#sgstRateDiv').html(parseFloat(baseRate*(parseInt(sgstP)/100)));
			$('#totalRateDiv').html(baseRate+parseFloat(baseRate*(parseInt(cgstP)/100))+parseFloat(baseRate*(parseInt(sgstP)/100)));
			$('#totalRateSpan').html(Math.round(parseInt(baseRate+parseFloat(baseRate*(parseInt(cgstP)/100))+parseFloat(baseRate*(parseInt(sgstP)/100)))));
			
			$('#room_rate').val(parseInt(roomRate));
			if(!$('#room_base_rate').val())
				$('#room_base_rate').val(parseInt(roomBaseRate));
			$('#room_id').val(parseInt(id_room));
			$('#room_type_id').val(parseInt(id_room_type));
		});
	//	#hotelname,
	$('#hotelname').trigger('change');
	
		$('.book_room').click(function(){
			var rate = $(this).attr('data-rate');
			var roomBaseRate = $(this).attr('data-base-rate');
			var id_room = $(this).attr('data-id-room');
			var id_room_type = $(this).attr('data-id-room-type');
			var roomCnt = <?php echo (isset($guest) && !empty($guest) ? count($guest) : 0); ?>;
			var breakfast = $(this).attr('data-is-breakfast');
			if(breakfast)
				$('#breakfast_incl').val(breakfast);
			else
				$('#breakfast_incl').val(0);
			//$('#totalRateSpan').html(parseInt(rate)*parseInt(roomCnt));

			var baseRate = parseInt(rate)*parseInt(roomCnt);
			var cgstP = '<?php echo $availableRoomsList[0]['txtCGST']; ?>';
			var sgstP = '<?php echo $availableRoomsList[0]['txtSGST']; ?>';
			$('#baseRateDiv').html(baseRate);
			$('#cgstRateDiv').html(parseFloat(baseRate*(parseInt(cgstP)/100)));
			$('#sgstRateDiv').html(parseFloat(baseRate*(parseInt(sgstP)/100)));
			$('#totalRateDiv').html(baseRate+parseFloat(baseRate*(parseInt(cgstP)/100))+parseFloat(baseRate*(parseInt(sgstP)/100)));
			$('#totalRateSpan').html(Math.round(baseRate+parseFloat(baseRate*(parseInt(cgstP)/100))+parseFloat(baseRate*(parseInt(sgstP)/100))));
			
			$('#room_rate').val(parseInt(rate));
			$('#room_base_rate').val(parseInt(roomBaseRate));
			$('#room_id').val(parseInt(id_room));
			$('#room_type_id').val(parseInt(id_room_type));
			$('#hotelname').val(parseInt(id_room)+'_'+parseInt(id_room_type));
			$('#meal_rate').val('');
			$('#lunch_incl').val(0);
			$('#dinner_incl').val(0);
			$('.extraRateLunch').prop('checked',false);
			$('.extraRateDinner').prop('checked',false);
			var room_type_name = $("#hotelname").find(':selected').text();
			$(".room_type_name").text(room_type_name);
			/*$('.extraRateLunch').each(function(){
				if($(this).prop('checked')){
					$(this).prop('checked',false);
					$(this).trigger('click');
				}
			});
			$('.extraRateDinner').each(function(){
				if($(this).prop('checked')){
					$(this).prop('checked',false);
					$(this).trigger('click');
				}
			});*/
			$('html, body').animate({scrollTop:$('.hotel-details').offset().top}, 'slow');

		});

      
    

        /*responsive code end*/
    });
	//$("#datepicker").datepicker({dateFormat: 'dd-mm-yy',minDate: 0});
	//$("#datepickers").datepicker({dateFormat: 'dd-mm-yy',minDate: 0});

function changeadult(value,identifier)
   {
      
      var guest=0;
      var currentpax=$(identifier).closest(".pax_container").attr("data");
      var oldval=$("#oldAdult_"+currentpax).val();
      var checkchild=$(".childlist_"+currentpax).find(":selected").val();
      var tot=parseInt(checkchild)+parseInt(value);

    $("#oldAdult_"+currentpax).val(oldval);
    console.log(currentpax);
     console.log(oldval);
      if(tot>3)
      {
        $(".adultlist_"+currentpax).val(oldval).attr("selected");
        $("#oldAdult_"+currentpax).val(oldval);
        $("#mesg").css("display","block");
         setTimeout(function() {
          $('#mesg').fadeOut('fast');
        }, 1000);

        return false;
      }
     
      $(".adultlist_"+currentpax+" li").removeAttr("selected");
      $(identifier).addClass("actives");

     /* $('.actives').each(function(){
        console.log(guest);
        console.log(parseInt($(this).find(":selected").val()));
      guest=guest+parseInt($(this).find(":selected").val());
      });*/
      $( ".select1 option:selected" ).each(function() {
      guest=parseInt(guest)+parseInt($(this).val());
      });
      var room=$("#roomval").val();

      $('.detail_pax_'+currentpax+' .this_adult').text(value);
      $('.total_guest_room').text(room+' Room, '+guest+' Guests');
   
   }
   function changechild(value,identifier)
   {

		var guest=0;
		var currentpax=$(identifier).closest(".pax_container").attr("data");
		var oldval=$("#oldChild_"+currentpax).val();
		var checkadult=$(".adultlist_"+currentpax).find(":selected").val();
		var tot=parseInt(checkadult)+parseInt(value);
		$("#oldChild_"+currentpax).val(value);
		if(tot>3)
		{
		$("#mesg").css("display","block");
		$(".childlist_"+currentpax).val(oldval).attr("selected");
		$("#oldChild_"+currentpax).val(oldval);
		setTimeout(function() {
		$('#mesg').fadeOut('fast');
		}, 1000);
		return false;
		}
		$(".childlist_"+currentpax+" select").removeAttr("selected");
		$(identifier).addClass("actives");

		$( ".select1 option:selected" ).each(function() { 
		
		guest=parseInt(guest)+parseInt($(this).val());
		});

		var room=$("#roomval").val();

		$('.detail_pax_'+currentpax+' .this_child').text(value);
		$('.total_guest_room').text(room+' Room, '+guest+' Guests');
   
   }
function ondone()
{

	var spanText = $('.total_guest_room').html();
	var guestText = $('#guest_text').val();


	if(spanText == guestText){
		$("#demo").css("display","none");
		return;
	} 
	var room=0;
	var noofadult=0;
	var noofchild=0;
	var obj = {};
	var items = [];
	$('.pax_container').each(function(){
		if($(this).hasClass("pax_container"))
		{
			room=$(this).attr("data");
			noofadult=$(this).parent().find(".adultlist_"+room+' option:selected').val();  
			noofchild=$(this).parent().find(".childlist_"+room+' option:selected').val();  
			obj[room]={'adult':noofadult,'child':noofchild};
		}
	});
	$("#guestJSON").val(JSON.stringify(obj));

	$('.book-hotel-form').submit();
	return false;

 $("#demo").css("display","none");
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
  $( ".select1 option:selected" ).each(function() {
      guest=parseInt(guest)+parseInt($(this).val());
      });
   $("#roomval").val(s);
   $('.total_guest_room').text(s+' Room, '+guest+' Guests');
   }
   function addroom(value)
   {
   
        var intId=parseInt($(value).attr('data'));
        var inc=intId+1;
        $(".rooms_in_hotel").attr('data',inc);
       var append='<div  class="col-sm-12 pax_container test pax_container_'+intId+'" data="'+intId+'"><div class="removepax" onclick="removeroom(this)"><i class="fa fa-times" aria-hidden="true"></i></div>';
           append+='<div class="row" style="margin-top: 4px;">';
           append+='<div class="col-sm-3 detail_pax detail_pax_'+intId+'">';
           append+='<p class="detail_pax_p">Room <span class="roomnumber">'+intId+'</span></p>';
           append+='';
           append+='</div>';
           append+='<div class="col-sm-8 content_pax content_pax_1'+intId+'" style="margin-top: -20px;">';
           append+='<div class="row">';
           append+='<div class="col-xs-6">';
           append+='<p class="head_title_room" style="margin-left: -5px;">Adult</p>';
           append+='<select class="select1 actives adultlist adultlist_'+intId+'" onchange="changeadult(this.value,this)">';
           append+='<option value="1">1</option>';
           append+='<option selected value="2">2</option>';
          append+='<option value="3">3</option>';
           append+='</select>';
           append+='<input type="hidden" id="oldAdult_'+intId+'" value="2">';

           append+='</div>';
           append+='<div class="col-sm-6 col-xs-6">';
           append+='<p class="head_title_room" style="margin-left: -34px;">Childern (5-12 yrs)</p>';
           append+='<select class="select1 actives childlist childlist_'+intId+'" onchange="changechild(this.value,this)">';
           append+='<option selected value="0" >0</option>'
           append+='<option  value="1">1</option>';
           append+=' <option  value="2">2</option>';

           append+=' </select><input type="hidden" id="oldChild_'+intId+'" value="0">';
           append+='</div>';
           append+='</div>';
           append+='</div>';
           append+='<div class="clearfix"></div>';
           append+=' </div>';
           append+=' </div>';
   
           $(".outer_pax").append(append);
   
        var guest=0;     
        $('.actives').each(function(){
        guest=parseInt(guest)+parseInt($(this).val());
        });
       $("#roomval").val(intId);
       $('.total_guest_room').text(intId+' Room, '+guest+' Guests');
   
   }

</script>
<script>
	$(function() {

		$(".secode_gall").jsgallery({
			//imgSelector : "img", //default is img, ommit this property to use default
			imgSelector : ".ingg",
			customHTMLFooter : "",
			bgClickClose : true
		});
	});
	
	$(document).ready(function(){
		$('.childlist .actives').each(function(){
			console.log($(this).attr('onclick'));
			setTimeout(function(){ $(this).trigger('click'); }, 1000);
		});
		$(".done").click(function()
		{
		 var childAgeJSON = [];
		 $('.child_age').each(function(){
			var object = {}; 
			object[$(this).attr('data-room')] = $(this).val();
			childAgeJSON.push(object);
		 });
		 $('#child_age_json').val(JSON.stringify(childAgeJSON));
		});
		$(".done").trigger('click');
		
		$( "#hotels_month1_book" ).datepicker({
			changeMonth: true,
			changeYear: true,
			dateFormat: "dd-mm-yy",
			onClose: function( selectedDate ) {
				$( "#hotels_month_book" ).datepicker( "option", "maxDate", selectedDate );	
				if($( "#hotels_month_book" ).val() != $('#hotel_month').val() || $('#hotel_month1').val() != selectedDate){
					$('#hotel_month1').val(selectedDate);
					$('.book-hotel-form').submit();
				}
			}
		});
		$( "#hotels_month_book" ).datepicker({
			changeMonth: true,
			changeYear: true,
			dateFormat: "dd-mm-yy",
			minDate: 0,
			onClose: function( selectedDate ) {
				$( "#hotels_month_book" ).val(selectedDate);
				$('#hotel_month').val(selectedDate);
				$( "#hotels_month1_book" ).datepicker( "option", "minDate", selectedDate );
			}
		});
		
	});


	
</script>
<script>

$(document).ready(function(){
    $(".room_detailsa").toggle(
        function(){$(".room_detailsa").css({"background": "#fff"});},
    );
	$('[data-toggle="popover"]').popover();
});
//  modal_contents scripts


$(document).ready(function(){
    $(".cancel_ploiciess").click(
        function(){
        	$.confirm({
        		    backgroundDismiss: true,

        	 columnClass: 'col-md-12',
        	 
    title: 'Cancellation Policies',
    content: '<p class="cancel_pol_class" >Condimentum lorem ornare tortor porta quis quis tempor morbi tempus. Sed dictumst primis ante class ac lectus curabitur eu eu consectetur vivamus. Aenean torquent sit lacinia semper sociis viverra praesent! Id aliquet inceptos molestie sollicitudin sem mi. Convallis varius nam sed scelerisque dis ligula. At sodales etiam potenti nunc vestibulum iaculis malesuada lacinia vel lacus habitasse. Ultrices lacus fames ante nisl eget mus non, imperdiet leo sodales in massa! Est luctus at potenti placerat morbi. Nam duis aliquet sed et elementum curabitur velit nostra cum suspendisse. Adipiscing eleifend.</p>',
    
    typeAnimated: true,
     buttons: {
        close: {
            btnClass: 'btn-red',
            action: function(){}
        }
    }
});

        }
    );

  $(".lunch_inclus").click(
        function(){
        	$.confirm({
        		    backgroundDismiss: true,

        	 columnClass: 'small',
        	  
    title: 'Lunch Inclusion',
    content:'<div style="padding:0px 30px;"><p class="confirm_paras"><span class="left_span">Per Adult</span><span class="rupees_ico"><i class="fa fa-inr" aria-hidden="true"></i></span><?php echo $rateLunchAdult; ?></p>    <p class="confirm_paras"><span class="left_span">Per Child (5-12 yrs)</span><span class="rupees_ico"><i class="fa fa-inr" aria-hidden="true"></i></span><?php echo $rateLunchMoreThanFive; ?></p>    <p class="confirm_paras"><span class="left_span">Per Child (0-5 yrs)</span><span class="rupees_ico"><i class="fa fa-inr" aria-hidden="true"></i></span><?php echo $rateLunchLessThanFive; ?></p></div>',
    
    typeAnimated: true,
     buttons: {
        close: {
            btnClass: 'btn-red',
            action: function(){}
        }
    }
});

        }
    );



  $(".dinner_inclus").click(
        function(){
        	$.confirm({
        		    backgroundDismiss: true,

        		 columnClass: 'small',
        	  
    title: 'Dinner Inclusion',
    content:'<div style="padding:0px 30px;"><p class="confirm_paras"><span class="left_span">Per Adult</span><span class="rupees_ico"><i class="fa fa-inr" aria-hidden="true"></i></span><?php echo $rateDinnerAdult; ?>0</p>    <p class="confirm_paras"><span class="left_span">Per Child (5-12 yrs)</span><span class="rupees_ico"><i class="fa fa-inr" aria-hidden="true"></i></span><?php echo $rateDinnerMoreThanFive; ?></p>    <p class="confirm_paras"><span class="left_span">Per Child (0-5 yrs)</span><span class="rupees_ico"><i class="fa fa-inr" aria-hidden="true"></i></span><?php echo $rateDinnerLessThanFive; ?></p></div>',
    
    typeAnimated: true,
    buttons: {
        close: {
            btnClass: 'btn-red',
            action: function(){}
        }
    }
});

        }
    );


    $(".fare_breaks").click(
        function(){
        	$.confirm({
        		    backgroundDismiss: true,

        	 columnClass: 'small',
        	

    title: 'Fare Breakup',
    content:'<div style="padding:0px 30px;"><p class="confirm_paras"><span class="left_span">Base Fare</span><span class="rupees_ico"><i class="fa fa-inr" aria-hidden="true"></i></span><span  id="baseRateDiv"><?php echo $totalPriceInitial;?></span></p>     <p class="confirm_paras"  id="lunchRateParentDiv" style="display:none;"><span class="left_span">Lunch Incl.</span><span class="rupees_ico"><i class="fa fa-inr" aria-hidden="true"></i></span><span  id="lunchRateDiv"></span></p>       <p class="confirm_paras" id="dinnerRateParentDiv" style="display:none;"><span class="left_span">Dinner Incl.</span><span class="rupees_ico"><i class="fa fa-inr" aria-hidden="true"></i></span><span  id="dinnerRateDiv"></span></p>     <p class="confirm_paras"><span class="left_span">Tax CGST</span><span class="rupees_ico"><i class="fa fa-inr" aria-hidden="true"></i></span><span  id="cgstRateDiv"><?php echo $totalPriceInitial*($availableRoomsList[0]['txtCGST']/100); ?></span></p>        <p class="confirm_paras"><span class="left_span">Tax CGST</span><span class="rupees_ico"><i class="fa fa-inr" aria-hidden="true"></i></span><span  id="sgstRateDiv"><?php echo $totalPriceInitial*($availableRoomsList[0]['txtSGST']/100); ?></span></p>      <p class="confirm_paras"><span class="left_span">Total</span><span class="rupees_ico"><i class="fa fa-inr" aria-hidden="true"></i></span><span   id="totalRateDiv"><?php echo $totalPriceInitial + ($totalPriceInitial*($availableRoomsList[0]['txtSGST']/100)) + ($totalPriceInitial*($availableRoomsList[0]['txtSGST']/100)); ?></span></p></div>',
    
    typeAnimated: true,
    buttons: {
        close: {
            btnClass: 'btn-red',
            action: function(){}
        }
    }
});

        }
    );







});





</script>


<style type="text/css">
.checkbooking{padding: 0;}

.checkbooking .col-sm-6{
	padding:0 2px ;position: relative;
}

#button-pax-container{width: 100%;background:transparent!important;border:1px solid #ccc; }
.room_detaisa{background-color:#fff;transition:0.4s ease-in-out;}
	.room_details_atag{background-color: #fff;    }
	.checkbooking label{color:#555;}
	.indent_for_p p{text-indent: 30px;}



	.rooms_in_hotel {
    outline: none!important;
    font-size: 10px;
    width: 70px;
  
    background-color: #ff0000;
    border: 0px solid #fff;
}
.article li{border-bottom: 2px solid #000;}
.pagination li.actives{color:#000;}


.detail_pax p {
    font-size: 11px!important;
    font-weight: bold;
}


.icon_for_rel {
    
    font-size: 12px;
    position: absolute;
    top: 11px;
    left: 8px;
}

.custonlink {
	cursor:pointer;
    background-color: transparent;
    border: 0px none;
}

.color_ablue {
    color: #0000ee;
}


.checkbooking .datepi {
       padding-left: 30px!important;
    border-radius: 3PX;
    padding: 0;
    font-size: 12px;
}

.total_guest_room {
    font-size: 11px;
    color: #555;
        font-weight: normal;
}
.chval {
    font-size: 20px;
    display: block;
    color: #555;
}

.chdet {
    font-size: 14px;
    display: block;
    color: #555;
}

.book_iconall a {
    	
    margin-right: 15px;
    font-size: 28px;
}		


.offerBlk {
    top: -5px;
    background: #e31e24;
    color: #fff;
    float: left;
    padding-top: 5px;
    padding-bottom: 5px;
    padding-left: 10px;
    padding-right: 15px;
    font-weight: bold;
    position: relative;
    font-style: normal;
    left: 0;
    border-bottom-left-radius: 0px;
    -o-border-bottom-left-radius: 0px;
    -webkit-border-bottom-left-radius: 0px;
    -moz-border-bottom-left-radius: 0px;
}

.offerBlk :after {
    position: absolute;
    content: '';
    width: 0;
    height: 0;
    border-top: 17px solid transparent;
    border-right: 11px solid #fff;
    border-bottom: 13px solid transparent;
    top: 0;
    right: 0;
}

.txtCenter {
    text-align: center;
}

.posrelv {
    width: 300px;
    margin-left: -22px;
    margin-top: -10px;
}


.checkbooking label {
    color: #555;   
     font-size: 14px;
        margin-left: 4px;
}


.book_button_hotel input {
    width: 100px;
}

.tab-hotelbooking .nav-tabs>li.active>a {
    background-color: #eee;
}


.tab-hotelbooking .nav-tabs>li>a {
    color: #000;
}

.tab-hotelbooking .tab-content {
    background-color: #eee;padding: 15px;
}

.tab-hotelbooking .nav-tabs>li>a {
    color: #000;
}

.rooms_repeat {
    padding: 15px;
    border: 1px solid #fff;
}

.tab-content .tab-pane li {
    float: none !important;
}

.rooms_repeat .glyphicon {
    margin-right: 10px;
    color: #ccc;
    font-size: 11px;
}

.rooms_repeat ul, .review_hotel ul {
    padding: 10px 0px;
    color: #555;
}

.totalhotelsmades {
    text-align: center;
}

.button_forms {
    text-align: center;
}

.stars{margin-right: 4px;margin-left: 4px;}

.stars .glyphicon-star {
    color: #FFD700;
}
.hot_names span{font-size: 11px;text-transform: capitalize;}
.similar_hotels .hot_names {
	text-transform: uppercase;
	margin:0;
	padding:10px;
    background: rgba(0, 0, 0, 0.58);
    background-size: cover;
    position: absolute;
    bottom: 0px;
    width: 100%;
  	 color: #fff;
    left: 0;
}

.similar_botp {background:#adaaaa;padding:10px 15px;}

.similar_botp .btn{    padding: 2px 18px;  padding: 2px 18px;
    position: absolute;
    /* float: right; */
    right: 4px;
    bottom: 8px;}

.similar_hotels .price {
	    border-radius: 16px 1px 16px 1px;
	font-size: 10px;
  position: absolute;
    top: 5px;
    right: 10px;
    background-color:rgba(195, 15, 15, 0.62);
        padding: 6px 10px;
    color: #fff;
    text-align: center;
}

.similar_hotels .price span {font-size:16px;}
.similar_hotels .price span i{
	margin-right:5px;
}

.similar_hotels {
    position: relative;
    border: 1px solid #ccc;
}
.confirm_paras{text-align: left; font-size: 12px;
}
.left_span{width:150px;float:left;color:#555;
    }
.jconfirm-title{font-size: 16px!important;color:#2f2f2f;}
.rupees_ico{padding:0 10px ;}



.checkbox {text-align: center;}
.checkbox .glyphicon {color:#C40000!important;outline:none!importants;}
/*.checkbox{width: 50%;float:left;}*/
.checkbox .checker span{  width: 20px;
    height: 20px;}
.checkbox .checker{margin-right: 0}

.txtlandmark{font-size: 11px;
    text-transform: capitalize;}
.details .stars{top:0px!important;}
div.selector{;
    padding-left: 15px;
    background: #fff;
   }
div.radio, div.radio span, div.radio input{
	width: 20px;
	height: 19px;
	position: relative;
	top: 2px;
}
div.radio span.checked:before {
    content: "";
    width: 10px;
    height: 10px;
    background: #B8ACA4;
    -webkit-border-radius: 6px;
    -moz-border-radius: 6px;
    border-radius: 6px;
    position: absolute;
    top: 4px;
    left: 4px;
}




</style>




        
         
        </div>

<script type="text/javascript">$(document).ready(function() {
	$(".fancybox-thumb").fancybox({
		prevEffect	: 'none',
		nextEffect	: 'none',
		'loop': false,
		helpers	: {
			title	: {
				type: 'outside'
			},
			thumbs	: {
				width	: 50,
				height	: 50
			}
		}
	});




});
 $('#button-pax-container').click(function() {
 $("#demo").toggle("slow");

 });

  $('.add_rooms_plus').click(function() {
 $("#demo").show("slow");

 });


</script>


 <script>
            window.postType = 'accommodation';
            window.pauseBetweenSlides = 3000;
         </script>

        




<Script>

	
	 $(document).ready(function() {
    $('#imageGallery').lightSlider({
        gallery:true,
        item:1,
        loop:true,
        thumbItem:9,
        slideMargin:0,
        enableDrag: true,
        currentPagerPosition:'left',
        onSliderLoad: function(el) {
           /* el.lightGallery({
                selector: '#imageGallery .lslide'
            });*/
        }   
    });  
  });
</Script>
<script>
$(document).ready(function(){
    $(".rooms_in_hotel ").click(function(){
      
        $(".room_res1").addClass("room_res2");
    });








});
</script>
<script  type="text/javascript">
jQuery(
  function($)
  {
       var q=encodeURIComponent($('#address').val());
       $('#map')
        .attr('src',
             'https://www.google.com/maps/embed/v1/place?key=AIzaSyCvQxw5RS5Ed_6iOHhTB6pl9PARhrxaIcs&q='+q);

  }
);
</script>


<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightslider/1.1.6/css/lightslider.min.css" />

<script src="https://cdnjs.cloudflare.com/ajax/libs/lightslider/1.1.6/js/lightslider.min.js"></script>
<?php include('../../include/footer.php'); ?>