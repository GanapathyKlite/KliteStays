<?php 

session_start();
//include '../config.php';
//include '../include/database/config.php';
include("../../include/header.php");
$currentpage="hotelbooking";
error_reporting(0);
print_r($_REQUEST);

$city_name = isset($details[0]['name'])?$details[0]['name']:''.', '.isset($details[0]['state'])?$details[0]['state']:'';
$city_id = isset($details[0]['id_city'])?$details[0]['id_city']:'';

$check_in = date('d-m-Y',strtotime('+24 hours'));
$check_out = date('d-m-Y',strtotime('+48 hours'));


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
		$countWhere .= ' and r.selMaxNoOfGuest >= '.($val['adult']+$val['child']);
		$guestCntHidden .= '<input type=Hidden name="guest['.$key.'][adult]" value='.$val['adult'].'><input type=Hidden name="guest['.$key.'][child]" value='.$val['child'].'>';
		}else{
			$countadult += $val['\'adult\''];
		$countchild += $val['\'child\''];

		//$countWhere .= ' and (r.selMaxNoOfAdult >= '.$val['\'adult\''].' and r.selMaxNoOfChild >= '.$val['\'child\''].' and r.selMaxNoOfGuest >= '.($val['\'adult\'']+$val['\'child\'']).')';
		$countWhere .= ' and r.selMaxNoOfGuest >= '.($val['\'adult\'']+$val['\'child\'']);
		$guestCntHidden .= '<input type=\'Hidden\' name="guest['.$key.'][\'adult\']" value=\''.$val['\'adult\''].'\'><input type=\'Hidden\' name="guest['.$key.'][\'child\']" value=\''.$val['\'child\''].'\'>';
		}
		
		
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
		<p style="margin-top:5px;margin-bottom:5px;"><span class="this_adult">'.$val['\'adult\''].'</span> Adults, <span class="this_child">'.$val['\'child\''].'</span>  Child</p>
		</div>
		<div class="content_pax content_pax_'.$key.'" style="display:none;">
		<p class="head_title_room">Adult (+12 yrs)</p>
		<ul class="pagination pagination-sm pagination_lists adultlist adultlist_'.$key.'">
		<li class="'.(($val['\'adult\'']==1)?"actives":"").'"onclick="changeadult(1,this)">1</li>
		<li class="'.(($val['\'adult\'']==2)?"actives":"").'" onclick="changeadult(2,this)">2</li>
		<li class="'.(($val['\'adult\'']==3)?"actives":"").'" onclick="changeadult(3,this)">3</li>
		<li class="'.(($val['\'adult\'']==4)?"actives":"").'" onclick="changeadult(4,this)">4</li>
		<li class="'.(($val['\'adult\'']==5)?"actives":"").'" onclick="changeadult(5,this)">5</li>
		<li class="'.(($val['\'adult\'']==6)?"actives":"").'" onclick="changeadult(6,this)">6</li>
		</ul>
		<p class="head_title_room">Childern (1-12 yrs)</p>
		<ul class="pagination pagination-sm pagination_lists childlist childlist_'.$key.'">
		<li class="'.(($val['\'child\'']==0)?"actives":"").'"  onclick="changechild(0,this)">0</li>
		<li class="'.(($val['\'child\'']==1)?"actives":"").'" onclick="changechild(1,this)">1</li>
		<li class="'.(($val['\'child\'']==2)?"actives":"").'" onclick="changechild(2,this)">2</li>
		<li class="'.(($val['\'child\'']==3)?"actives":"").'" onclick="changechild(3,this)">3</li>
		<li class="'.(($val['\'child\'']==4)?"actives":"").'" onclick="changechild(4,this)">4</li>

		</ul>
		</div>
		<a class="edit" onclick="editval(this)" data="'.$key.'"><i  style="font-size:10px;" >Edit</i></a>
		
		<div class="clearfix"></div>
		</div>';
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

$availableRoomsList = $database_hotel->query('select p.txtNoOfGuestRooms,p.txtPropertyName,p.selStarRating,p.txtAddress1,p.txtAddress2,p.txtZip,p.selImages,p.txtLatitude,p.txtLongitude,p.rateLunchAdult,p.rateLunchMoreThanFive,p.rateLunchLessThanFive,p.rateDinnerAdult,p.rateDinnerMoreThanFive,p.rateDinnerLessThanFive,p.txtCGST,p.txtSGST,p.txtTAC,p.cancellation_policy_3_days,p.cancellation_policy_3_days_more,p.terms_and_conditions,r.*,r.txtPropertyDescription as roomDescription,rt.id_room_type,rt.rateExtraBedAdult,rt.rateExtraBedChildMoreThanFive,rt.rateExtraBedChildLessThanFive,rt.periodic_rateExtraBedAdult,rt.periodic_rateExtraBedChildMoreThanFive,rt.periodic_rateExtraBedChildLessThanFive,rt.txtRoomName as txtRoomNameNew,cl.name as countryName, s.name as stateName, c.name as cityName from ps_property p left join ps_room r on(p.id_property = r.id_property and r.status=0) left join ps_room_type rt on(r.id_room = rt.id_room) left join ps_country_lang cl on(p.selCountryId = cl.id_country) left join ps_state s on(p.selStateId = s.id_state) left join ps_city c on(p.selCityId = c.id_city) where 1'.$countWhere.' and p.id_property = '.$id_property.' and p.status=0')->fetchAll();

$searchMonth	= date('n',strtotime($check_in));
$searchYear		= date('Y',strtotime($check_in));
$searchDate		= date('d',strtotime($check_in));
$searchDate1	= date('d',strtotime($check_out));

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
		$roomVal['baseTariff'] = $price1[$roomVal['id_room'].'_'.$roomVal['id_room_type']] = round($rateValue);

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
	$availableRoomsList = $database_hotel->query('select p.txtNoOfGuestRooms,p.txtPropertyName,p.selStarRating,p.txtAddress2,p.selImages,p.rateLunchAdult from ps_property p where p.id_property = '.$id_property.' and p.status=0 and p.selCityId='.$city_id)->fetchAll();

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

?>
<link rel="stylesheet" href="<?php echo $root_dir; ?>hotel/js/jquery.jsgallery/jsgallery.css" type="text/css" media="screen" charset="utf-8">
<script type="text/javascript" src="<?php echo $root_dir; ?>hotel/js/jquery.jsgallery/jquery.jsgallery.min.js"></script>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&key=AIzaSyBGf3r4bN0xY-Oe5W83AOSgYmVWrBf1KwM"></script>
		



	
		
		


<div   >

<div class="container book-detail-cont" >
	<div class="breadcurmb_vals ">
			
				
			<ol class="breadcrumb" >
				<li class="breadcrumb-item"><a class="values_cityname" href="<?php echo $root_dir;?>">Home</a></li>
				<li class="breadcrumb-item"><a class="values_cityname" href="#" src=""><?php echo $city_name; ?></a></li>
				<li class="breadcrumb-item active"><a class="values_cityname1"><?php echo $propertyName[0]['txtPropertyName']; ?></a></li>
			</ol>
					
	
	</div>

	<div class="col-sm-12 inner_bookdetail">
	
    
    <h2><?php echo $propertyName[0]['txtPropertyName']; ?>
    </h2>
    <p style="color:#ccc"><?php echo (!empty($propertyName[0]['txtPropertyDescription']) ? $propertyName[0]['txtPropertyDescription'] : 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer ut suscipit turpis. Pellentesque volutpat odio accumsan enim consequat egestas'); ?></p>
    <div class="col-md-8">


        <div class="booking_hotels">
            <p style="padding-bottom: 10px;">Hotel Photos    </p>
            <div id="jssor_1" style="position:relative;margin:0 auto;top:0px;left:0px;width:809px;height:150px;overflow:hidden;visibility:hidden;">
                <!-- Loading Screen -->
                <div data-u="loading" style="position:absolute;top:0px;left:0px;background:url('images/loading.gif') no-repeat 50% 50%;background-color:rgba(0, 0, 0, 0.7);"></div>
                <div media="slider" data-u="slides" class="secode_gall" style="cursor:default;position:relative;top:0px;left:0px;width:809px;height:150px;overflow:hidden;">

					<?php foreach($photo_gallery as $photo){ ?>
						<div>
							<img  class="ingg" data-u="image" src="<?php echo $photo; ?>" />
						</div>
					<?php } ?>

                    <!--<a data-u="any" href="https://wordpress.org/plugins/jssor-slider/" style="display:none">wordpress banner rotator</a>-->
                </div>
                <!-- Bullet Navigator -->
                <div data-u="navigator" class="jssorb03" style="bottom:10px;right:10px;">
                    <!-- bullet navigator item prototype -->
                    <div data-u="prototype" style="width:21px;height:21px;">
                        <div data-u="numbertemplate"></div>
                    </div>
                </div>
                <!-- Arrow Navigator -->
                <span data-u="arrowleft" class="jssora03l" style="top:0px;left:8px;width:55px;height:55px;" data-autocenter="2"></span>
                <span data-u="arrowright" class="jssora03r" style="top:0px;right:8px;width:55px;height:55px;" data-autocenter="2"></span>
            </div>

            <hr>

            <p style="padding-bottom: 10px;">Travellers Photos</p>
            <div id="jssor_2" style="position:relative;margin:0 auto;top:0px;left:0px;width:809px;height:150px;overflow:hidden;visibility:hidden;">
                <!-- Loading Screen -->
                <div data-u="loading" style="position:absolute;top:0px;left:0px;background:url('images/loading.gif') no-repeat 50% 50%;background-color:rgba(0, 0, 0, 0.7);"></div>
                <div media="slider" data-u="slides" id="secode_gall" style="cursor:default;position:relative;top:0px;left:0px;width:809px;height:150px;overflow:hidden;">

                    <div>
                        <img  class="ingg" data-u="image" src="images/hotel2.jpg" />
                    </div>
                    <div>
                        <img  class="ingg" data-u="image" src="images/hotel3.jpg" />
                    </div>
                    <div>
                        <img  class="ingg" data-u="image" src="images/hotel4.jpg" />
                    </div>
                    <div>
                        <img  class="ingg" data-u="image" src="images/hotel5.jpg" />
                    </div>
                    <div>
                        <img  class="ingg" data-u="image" src="images/hotel2.jpg" />
                    </div>
                    <div>
                        <img  class="ingg" data-u="image" src="images/hotel4.jpg" />
                    </div>
                    <div>
                        <img  class="ingg" data-u="image" src="images/hotel5.jpg" />
                    </div>
                    <div>
                        <img class="ingg"  data-u="image" src="images/hotel3.jpg" />
                    </div>
                    <div>
                        <img  class="ingg" data-u="image" src="images/hotel2.jpg" />
                    </div>
                    <div>
                        <img  class="ingg" data-u="image" src="images/hotel4.jpg" />
                    </div>
                    <div>
                        <img  class="ingg" data-u="image" src="images/hotel5.jpg" />
                    </div>
                    <div>
                        <img  class="ingg" data-u="image" src="images/hotel2.jpg" />
                    </div>
                    <div>
                        <img  class="ingg" data-u="image" src="images/hotel5.jpg" />
                    </div>
                    <div>
                        <img  class="ingg" data-u="image" src="images/hotel2.jpg" />
                    </div>
                    <div>
                        <img  class="ingg" data-u="image" src="images/hotel5.jpg" />
                    </div>
                    <div>
                        <img  class="ingg" data-u="image" src="images/hotel3.jpg" />
                    </div>
                    <div>
                        <img  class="ingg" data-u="image" src="images/hotel2.jpg" />
                    </div>


                    <a data-u="any" href="https://wordpress.org/plugins/jssor-slider/" style="display:none">wordpress banner rotator</a>
                </div>
                <!-- Bullet Navigator -->
                <div data-u="navigator" class="jssorb03" style="bottom:10px;right:10px;">
                    <!-- bullet navigator item prototype -->
                    <div data-u="prototype" style="width:21px;height:21px;">
                        <div data-u="numbertemplate"></div>
                    </div>
                </div>
                <!-- Arrow Navigator -->
                <span data-u="arrowleft" class="jssora03l" style="top:0px;left:8px;width:55px;height:55px;" data-autocenter="2"></span>
                <span data-u="arrowright" class="jssora03r" style="top:0px;right:8px;width:55px;height:55px;" data-autocenter="2"></span>
            </div>
        </div>
        <div class="clearfix"></div>

       <!--  <div class="col-sm-12  col-md-7 col-lg-7 col-xs-12">
            <p style="    font-size: 20px;"><span style="font-size: 35px">85%</span> Agency recommend this hotel</p>
        </div>

        <div class="col-sm-12  col-md-5 col-lg-5 col-xs-12" style="padding-top: 15px; text-align: right;"><a style="  color:#000;  padding: 2px 9px;   border-radius: 7px;    background-color: #fff;    border: 1px solid #db0b0b;    margin-right: 9px;"><span>5</span>/5</a><a style="color:#000;">Based on  <span class="" id=""> 7</span> Agencies </a></div> -->
    </div>
    <div class="col-md-4">





        <div class="col-sm-12  col-md-12 col-lg-12 col-xs-12 checkbooking" style="position: relative;">
            <div class="posrelv"><span class="ico11 white fl offerBlk txtCenter"><span class="width100 fl">Upto 50 % OFF</span></span>
            </div>
            <!--<form class="room_search" action="<?php //echo $_SERVER['PHP_SELF']; ?>" method="get" id="room_search" style="margin-top: 53px;">-->
            <div class="clearfix"></div>
                <div class="col-md-6" style="padding: 0px; ">

                    <i class="fa fa-calendar fa-2 icon_for_rel" aria-hidden="true"></i>
                    <input style="border-radius: 0px !important;" type="text" class="form-control datepi" id="hotels_month_book" placeholder="checkin" value="<?php echo (isset($check_in) ? $check_in : ''); ?>">

                </div>
                <div class="col-md-6 " style="padding: 0px;">
                    <i class="fa fa-calendar fa-2 icon_for_rel" aria-hidden="true"></i> <input style=" border-radius:0px !important;" type="text" class="form-control datepi" id="hotels_month1_book" placeholder="checkout" value="<?php echo (isset($check_out) ? $check_out : ''); ?>">
                </div>
                <div class="clearfix"></div>


				<div class="col-md-12" style="margin-top:20px;padding: 0px;">
					<div class="section_room" style="position: relative">
					<!--<input type="hidden" id="guest" name="guest" value="">--><label>Add rooms
					</label>
					<input type="hidden" id="guestval" value="<?php echo $guestcount?>">
					<input type="hidden" id="roomval" value="<?php echo $key?>">
					
					<button type="button"  id="button-pax-container"><span class="total_guest_room"><?php echo $guestText;?></span></button>

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
					<form class="book-hotel-form" name="hotel_book" action="book.php">
					  <input type="Hidden" name="id_property" value="<?php echo $id_property; ?>">
					  <input type="Hidden" name="goingto" value="<?php echo $city_name; ?>">
					  <input type="Hidden" name="to_hidden" value="<?php echo $city_id; ?>">
					  <input type="Hidden" name="hotel_month" id="hotel_month" value="<?php echo $check_in; ?>">
					  <input type="Hidden" name="hotel_month1" id="hotel_month1" value="<?php echo $check_out; ?>">
					  <input type="Hidden" name="guestJSON" id="guestJSON" value="">
					  <input type="hidden" id="child_age_json" name="child_age_json" value="">
					  <?php echo (isset($guestCntHidden) ? $guestCntHidden : ''); ?>
						<input type="Hidden" name="guest_text" value="<?php echo (isset($guestText) ? $guestText : ''); ?>">
				   </form>
				</div>
                <div class="col-md-12" style=" margin-top:20px; padding: 0px;<?php if($noRoomsFlag){ ?>display:none;<?php } ?>">
                    <i class="fa fa-bed icon_for_rel" aria-hidden="true"></i>
                    <select style=" border-radius:0px !important;    padding-left: 40px;" class="hotelname form-control" id="hotelname" name="adult">
						<?php if(isset($roomNames) && !empty($roomNames)){
								foreach($roomNames as $roomNamesK => $roomNamesV){
									$idRoomTypeArr = explode('_',$roomNamesK);
									echo '<option value="'.$roomNamesK.'" '.(empty($roomNamesK) ? 'selected' : '').' data-rate="'.(isset($price[$roomNamesK]) ? $price[$roomNamesK] : '').'" data-id-room="'.$idRoomTypeArr[0].'" data-id-room-type="'.$idRoomTypeArr[1].'">'.$roomNamesV.'</option>'; 
								}
							}
						?>
					</select>


                </div>
				<div class="col-md-12" style=" margin-top:20px; padding: 0px;<?php if($noRoomsFlag){ ?>display:none;<?php } ?>">
					<div class="col-md-6">
						<div class="checkbox">
						  <!--<a href="#" data-toggle="tooltip" title="Per Adult: Rs.<?php echo $rateLunchAdult; ?>, Per Child: Rs.<?php echo $rateLunchMoreThanFive; ?> (5-12 yrs), Rs.<?php echo $rateLunchLessThanFive; ?> (0-5 yrs)" data-placement="top"><label><input type="checkbox" value="" class="extraRateLunch" data-type="1">Lunch Incl.</label></a>-->
						  <label><input type="checkbox" value="" class="extraRateLunch" data-type="1">Lunch Incl.</label>
						  <a  href="javascript:void(0)"  class="lunch_inclus" ><span class="glyphicon glyphicon-info-sign"></span></a>
						 


						</div>
					</div>
					<div class="col-md-6">
						<div class="checkbox">
							<!--<a href="#" data-toggle="tooltip" title="Per Adult: Rs.<?php echo $rateDinnerAdult; ?>, Per Child: Rs.<?php echo $rateDinnerMoreThanFive; ?> (5-12 yrs), Rs.<?php echo $rateDinnerLessThanFive; ?>(0-5 yrs)" data-placement="top"><label><input type="checkbox" value="" class="extraRateDinner" data-type="2">Dinner Incl.</label></a>-->
							<label><input type="checkbox" value="" class="extraRateDinner" data-type="2">Dinner Incl.</label>
							<a href="javascript:void(0)"  class="dinner_inclus"  ><span class="glyphicon glyphicon-info-sign"></span></a>
							 
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
					<?php $priceFirst = current($price); $totalPriceInitial = (isset($priceFirst) && isset($guest) ? $priceFirst*count($guest) : 0); 



				 ?>

					
                
                    <div class="col-md-6" style="padding: 0">
                        <p style="font-size: 12px; padding-left: 10px;"><?php echo count($guest); ?> room(s) for <?php echo $numberOfNights; ?> night(s)<br>Incl. Taxes</p>
                    </div>

                        <div class="col-md-6 totalhotel" style="padding-left: 23px;padding: 0">Sub Total<br><i style="margin-right: 6px;" class="fa fa-rupee " ></i><span id="totalRateSpan"><?php echo $totalPriceInitial; ?></span>
					<p>
						<a href="javascript:void(0)" class="custonlink fare_breaks"  style="font-size:12px;">Fare Breakup</a>
					
					</p>
					</div>
			

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
							 <button type="submit" class=" btn btn-primary  btnBook11  "  value="Book Now">Book Now
							 </button>
							
							 
						</form>
                    </div>
                    <div class="clearfix"></div>
                </div>

            <!--</form>-->
            <div class="clearfix"></div>
        </div>
    </div>
    <div class="clearfix"></div>
</div>
<div class="col-sm-12" style="margin:10px 0px;box-shadow:1px 1px 9px #eee;     padding-top: 10px;padding-bottom: 10px;">
    <div class="col-sm-12  col-md-12 col-lg-12 col-xs-12" style="padding:10px 0px">
        <div class="book_iconall col-sm-12  col-md-8 col-lg-8 col-xs-12">



           <a href="#" data-toggle="tooltip" title="Room Service">
		   <i class="material-icons"<?php if(!in_array('room_service',$availableRoomsList[0]['amenities']) && !in_array('room_service_24_hours',$availableRoomsList[0]['amenities'])) echo 'style="color:#e9e9e9;"'; ?>>room_service</i>
		   </a>
		   <a href="#" data-toggle="tooltip" title="Gym / Spa">
		   <i class="material-icons"<?php if(!in_array('exercise_gym',$availableRoomsList[0]['amenities']) && !in_array('spa',$availableRoomsList[0]['amenities'])) echo 'style="color:#e9e9e9;"'; ?>>fitness_center</i>
		   </a>
		   <a href="#" data-toggle="tooltip" title="Swimming Pool">
		   <i class="material-icons"<?php if(!in_array('swimming_pool',$availableRoomsList[0]['amenities'])) echo 'style="color:#e9e9e9;"'; ?>>pool</i>
		   </a>
		   <a href="#" data-toggle="tooltip" title="Wi-fi">
		   	<i class="material-icons" <?php if(!in_array('internet_access_in_rooms',$availableRoomsList[0]['amenities'])) echo 'style="color:#e9e9e9;"'; ?>>signal_wifi_4_bar</i>
		   </a>
		   <a href="#" data-toggle="tooltip" title="Restaurant">
		   <i class="material-icons"<?php if(!in_array('restaurant',$availableRoomsList[0]['amenities'])) echo 'style="color:#e9e9e9;"'; ?>>restaurant</i>
		   </a>
		   <a href="#" data-toggle="tooltip" title="Internet Access">
		   <i class="material-icons"<?php if(!in_array('internet',$availableRoomsList[0]['amenities'])) echo 'style="color:#e9e9e9;"'; ?>>desktop_windows</i>
		   </a>

		   <?php if(isset($availableRoomsList[0]['amenities']) && !empty($availableRoomsList[0]['amenities'])){ ?>
            <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" id="all_amenities_a" aria-expanded="true" aria-controls="collapseOne" style="font-size: 12px;">
				<i class="more-less glyphicon glyphicon-plus"></i>
				View all Amenities and services
			</a>
			<?php } ?>

            <div id="collapseOne" style="border: 1px solid #ccc;box-shadow: 1px 1px 24px #eee;" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
                <div class="panel-body">
                    <div class="col-sm-12  col-md-12 col-lg-12 col-xs-12">
						<?php if(isset($availableRoomsList[0]['amenities']) && !empty($availableRoomsList[0]['amenities'])){
								$amenitiesInc = 1;
								foreach($availableRoomsList[0]['amenities'] as $amenities){ ?>
						<div class="col-sm-4  col-md-4 col-lg-4 col-xs-4"><i class="fa fa-check" aria-hidden="true"></i> <?php echo ucwords(str_replace('_',' ',$amenities)); $amenitiesInc++; ?></div>
						<?php }} ?>
					</div>
                </div>
            </div>

        </div>
		<div class="book_iconall col-sm-12  col-md-4 col-lg-4 col-xs-12">

		<div class="col-md-4 col-sm-4 pad0 txtCenter"><span class="chval">12:00</span><span class="chdet">Check In</span></div>
			<div class="col-md-4 col-sm-4 pad0 txtCenter"><span class="chval">12:00</span><span class="chdet">Check Out</span></div>
				<div class="col-md-4 col-sm-4 pad0 txtCenter"><span class="chval"><?php echo (isset($availableRoomsList[0]['txtNoOfGuestRooms']) && !empty($availableRoomsList[0]['txtNoOfGuestRooms']) ? $availableRoomsList[0]['txtNoOfGuestRooms'] : 0); ?></span><span class="chdet">Rooms</span></div>
		</div>



    </div>

  <div class="clearfix"></div>
<div class="col-sm-12  col-md-12 col-lg-12 col-xs-12 tab-hotelbooking" style="padding:10px 0px">
			<ul class="nav nav-tabs">
			<li class="active"><a data-toggle="tab" href="#roomrates">Room & Rates</a></li>
			<li><a data-toggle="tab" href="#location" id="map-location">Location</a></li>
			<li><a data-toggle="tab" href="#policies">Hotel Policies</a></li>
			</ul>

			<div class="tab-content" >
			<div id="roomrates" class="tab-pane fade in active">
			<?php if(isset($availableRoomsList) && !empty($availableRoomsList) && !$noRoomsFlag){
					foreach($availableRoomsList as $room){?>
			<div class="tab-pane fade in active" >
			<div class="rooms_repeat">



				

			<!--<h5>Room & Rates</h5>-->

				<div class="col-sm-3 col-md-3 col-xs-12 col-lg-3 fancybox_values" >

					
	<img class="manuals_fancybox" height="150px" src="<?php echo (isset($room['imageRoom']) && !empty($room['imageRoom']) ? $room['imageRoom'] : 'images/hotel5.jpg'); ?>">


			<div class="overlay_divs_fancy">
			
		<a class="view_more_fancy fancybox-thumb" rel="fancybox-thumb" href="http://farm8.staticflickr.com/7289/16207238089_0124105172_b.jpg" >View More <i class="fa fa-search-plus" aria-hidden="true"></i></a>


			<a class="fancybox-thumb" rel="fancybox-thumb" href="http://farm6.staticflickr.com/5444/17679973232_568353a624_b.jpg" >

</a>
<a class="fancybox-thumb" rel="fancybox-thumb" href="http://farm8.staticflickr.com/7367/16426879675_e32ac817a8_b.jpg" >
	
</a>
<a class="fancybox-thumb" rel="fancybox-thumb" href="http://farm6.staticflickr.com/5612/15344856989_449794889d_b.jpg" >
	
</a>
<a class="fancybox-thumb" rel="fancybox-thumb" href="http://farm8.staticflickr.com/7289/16207238089_0124105172_b.jpg" >
	</a>
	
 




			</div>	
				</div>
				<div class="col-sm-3 col-md-3 col-xs-12 col-lg-3" >
					<div >
						<p style="font-size: 19px;"><?php echo (isset($room['txtRoomName']) && !empty($room['txtRoomName']) ? ucwords($room['txtRoomName']).(isset($roomTypes[$room['id_room_type']]) && !empty($roomTypes[$room['id_room_type']]) ? ' ('.$roomTypes[$room['id_room_type']].')' : '') : 'Taj Club, Room, Business Lounge Access, City View'); ?></p>
						
					</div>
					
						<!--<button type="button" class="btn btn-info" data-toggle="collapse" data-target="#demo<?php echo $room['id_room'].'_'.$room['id_room_type']; ?>">Room Details <i class="fa fa-angle-down" aria-hidden="true"></i></button>-->
					
					
				</div>
				<div class="col-sm-3 col-md-4 col-xs-12 col-lg-4">
				
				<?php if($room['is_breakfast'] == 1 ){ ?><p >Inclusion</p>
				<ul style="padding:0;padding-bottom:  10px;"><li><i class="glyphicon glyphicon-ok"></i><span>Free Breakfast</span></li></ul><?php } ?>
				
				
				<p style="font-size:16px;line-height: 31px;" class="text-success">Refundable</p>
				<!--<p style="font-size:16px;     line-height: 59px;" class="text-warning">Non Refundable</p>-->

				<a href="javascript:void(0)"  class="cancel_ploiciess" style="font-size:12px;"><i class="fa fa-plus" style="    padding-right: 6px;"></i>Cancellation Policies</a>
				</div>

          
				
				<div class="col-sm-3 col-md-2 col-xs-12 col-lg-2">
				<div class="totalhotelsmades"><i class="fa fa-rupee" style="left:0px"></i>&nbsp;<span><?php echo (isset($room['baseTariff']) && !empty($room['baseTariff']) ? $room['baseTariff'] : ''); ?></span>
				<p>per room, per night</p>
				<p>(Excl. Taxes)</p>
				</div>
				<form class="button_forms" name="select_room">
				 <input style="color:#fff;" class="btn btn-primary   book_room" type="button" value="Select Room" style="margin-top: 10px;" data-rate="<?php echo (isset($room['rate']) && !empty($room['rate']) ? $room['rate'] : ''); ?>" data-base-rate="<?php echo (isset($room['baserate']) && !empty($room['baserate']) ? $room['baserate'] : ''); ?>" data-id-room="<?php echo (isset($room['id_room']) && !empty($room['id_room']) ? $room['id_room'] : ''); ?>" data-id-room-type="<?php echo (isset($room['id_room_type']) && !empty($room['id_room_type']) ? $room['id_room_type'] : ''); ?>" data-is-breakfast="<?php echo $room['is_breakfast']; ?>"> 
<!-- 
				 <button type="submit" class="btn  btnBook1 diagonal" name="hotel_book" value="Book Room" style="margin-top: 10px;" data-rate="<?php echo (isset($room['rate']) && !empty($room['rate']) ? $room['rate'] : ''); ?>" data-base-rate="<?php echo (isset($room['baserate']) && !empty($room['baserate']) ? $room['baserate'] : ''); ?>" data-id-room="<?php echo (isset($room['id_room']) && !empty($room['id_room']) ? $room['id_room'] : ''); ?>" data-id-room-type="<?php echo (isset($room['id_room_type']) && !empty($room['id_room_type']) ? $room['id_room_type'] : ''); ?>" data-is-breakfast="<?php echo $room['is_breakfast']; ?>"> Book Now</button> -->
				</form>
				</div>
				<div class="clearfix"></div>

				<div class="col-xs-12 col-sm-3 col-sm-offset-3   ">
				<p class="room_details_para"><a class="room_detaistag"   data-toggle="collapse" href="#demo<?php echo $room['id_room'].'_'.$room['id_room_type']; ?>" style="text-decoration:none;font-size:13px;">Room Details <i class="fa fa-angle-down" aria-hidden="true"></i></a>

					</p></div>

				<div class="clearfix"></div>
				<div class="clearfix"></div>

					<div  style=" margin-top:10px;" id="demo<?php echo $room['id_room'].'_'.$room['id_room_type']; ?>" class="room_details collapse row" media="slider" id="secode_gall">
					<div style="box-shadow: 0 10px 20px rgba(0,0,0,0.19), 0 6px 6px rgba(0,0,0,0.23);background-color: #fff;">
					<?php if(isset($room['imagesRoom']) && !empty($room['imagesRoom'])){ ?>
					<div class="col-sm-12">
						<div class="col-md-4 col-lg-4">
							<p class="margin_topbot_10">More Room Photos</p>
						</div>
						<div class="clearfix"></div>
					<?php foreach($room['imagesRoom'] as $imagesRoomekey => $imagesRoomVal){
								$imgIncVal = $imagesRoomekey % 4;
								if(!empty($imagesRoomekey) && empty($imgIncVal)) echo '<div class="clearfix">&nbsp;</div>';
									?>
								<div class="col-sm-12 col-sm-3 values_images_in" <?php if($imagesRoomekey>7) echo 'style="display:none;"'; ?>>
									<img  class="ingg center-block" data-u="image" src="<?php echo $imagesRoomVal; ?>" width="100%" height="150px" />
								</div>
					<?php } ?>
					</div>
					<div class="clearfix"></div>
					<?php }if(isset($room['roomDescription']) && !empty($room['roomDescription'])){ ?>
					<div class="col-sm-12">
						<div class="col-md-4 col-lg-4 ">
							<p class="margin_topbot_10">Room Details</p>
						</div>
					<div class="clearfix"></div>
					<div class="col-sm-12 indent_for_p" style="color:#7c7c7c;">
						<p  ><?php echo $room['roomDescription']; ?></p>
					</div>
					</div>
					<div class="clearfix"></div>
					<?php }if(isset($room['room_amenities']) && !empty($room['room_amenities'])){ ?>
					<div class="col-sm-12">
						<div class="col-sm-4">
							<p class="margin_topbot_10">Room Amenities</p>
						</div>
					<div class="clearfix"></div>
					<div class="col-md-12 col-lg-12" style="margin-bottom:10px; color:#7c7c7c;">
						<?php foreach($room['room_amenities'] as $roomAmenitiesK => $roomAmenitiesV){ ?>
						<div class="col-md-3 col-lg-3"><i class="fa fa-check" aria-hidden="true"></i> <?php echo ucwords(str_replace('_',' ',$roomAmenitiesV)); ?></div>
						<?php } ?>
					</div></div>
					<div class="clearfix"></div>
					<?php } ?>
				</div>
			</div>
			</div>
			</div>
			<?php }}else{ ?>
				<div class="col-md-12 text-center">We have no availability here for the dates youre looking</div><div class="clearfix"></div>
			<?php } ?>
			</div>
			<div id="location" class="tab-pane fade">
			<h5>Location</h5>
			<!--<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d248756.15464821996!2d80.20832454524812!3d13.047450122465243!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!4m3!3e6!4m0!4m0!5e0!3m2!1sen!2sin!4v1497520688495" width="100%" height="450" frameborder="0" style="border:0" allowfullscreen></iframe>-->
			<div id="map-delivery-canvas" style="height: 350px"></div>
			</div>
			<div id="policies" class="tab-pane fade">
			<p>Hotel Policies</p>
<p style="color:#7c7c7c;text-indent: 30px;font-size:14px;">
			Most hotels do not allow unmarried / unrelated couples to check-in. This is at full discretion of the hotel management. No refund would be applicable in case the hotel denies check-in under such circumstances.Please note that it takes minimum of 4 to 8 working hours to confirm a reservation at the hotel for same day check-ins.For distant check-ins, you are not required to call the hotel to reconfirm the reservations. Your booking details will reach the hotel in time and the booking will be re-confirmed from our end.
				</p>
			</div>
			</div>
 </div> <div class="clearfix"></div>
 </div>
<!-- 
<div class="container" style="padding:20px 30px; box-shadow:1px 1px 9px #eee;     margin-top: 10px;margin-bottom: 10px;">
	
<div class="col-xs-12 col-sm-3 col-lg-3 col-md-3">
	<div class="similar_hotels">
	<img src="images/hotel5.jpg" width="100%">
	<a href="#">hotel name</a>
	<div class="price"><span><i class="fa fa-rupee"></i>2800</span><br>per night</div>
<div class="botmbg"  ><i class="stars" style="    float: left;">
                        <span class="glyphicon glyphicon-star"></span>
                        <span class="glyphicon glyphicon-star"></span>
                        <span class="glyphicon glyphicon-star"></span>
                        <span class="glyphicon glyphicon-star"></span>
                       
                        </i>
                   
 
	<button type="submit" class="btn  btnBook1 diagonal" value="Book " style="right: 0px;"> Book</button>  <div class="clearfix"></div>
	</div>  
	</div>

	</div>

<div class="col-xs-12 col-sm-3 col-lg-3 col-md-3">
	<div class="similar_hotels">
	<img src="images/hotel5.jpg" width="100%">
	<a href="#">hotel name</a>
	<div class="price"><span><i class="fa fa-rupee"></i>2800</span><br>per night</div>
	<div class="botmbg" ><i class="stars" style="    float: left;">
                        <span class="glyphicon glyphicon-star"></span>
                        <span class="glyphicon glyphicon-star"></span>
                        <span class="glyphicon glyphicon-star"></span>
                        <span class="glyphicon glyphicon-star"></span>
                        <span class="glyphicon glyphicon-star"></span>
                        </i>
                   
    
	<button type="submit" class="btn  btnBook1 diagonal" value="Book " style="right: 0px;"> Book</button> <div class="clearfix"></div>
	</div></div>

	</div>


<div class="col-xs-12 col-sm-3 col-lg-3 col-md-3">
	<div class="similar_hotels">
	<img src="images/hotel5.jpg" width="100%">
	<a href="#">hotel name</a>
	<div class="price"><span><i class="fa fa-rupee"></i>2800</span><br>per night</div>
	<div class="botmbg" ><i class="stars" style="    float: left;">
                        <span class="glyphicon glyphicon-star"></span>
                        <span class="glyphicon glyphicon-star"></span>
                        <span class="glyphicon glyphicon-star"></span>
                        <span class="glyphicon glyphicon-star"></span>
                        <span class="glyphicon glyphicon-star"></span>
                        </i>
                   
   
	<button type="submit" class="btn  btnBook1 diagonal" value="Book " style="right: 0px;"> Book</button>  <div class="clearfix"></div> </div>
	</div>

	</div>


<div class="col-xs-12 col-sm-3 col-lg-3 col-md-3">
	<div class="similar_hotels">
	<img src="images/hotel5.jpg" width="100%">
	<a href="#">hotel name</a>
	<div class="price"><span><i class="fa fa-rupee"></i>2800</span><br>per night</div>
	<div class="botmbg"><i class="stars" style="    float: left;">
                        <span class="glyphicon glyphicon-star"></span>
                        <span class="glyphicon glyphicon-star"></span>
                        <span class="glyphicon glyphicon-star"></span>
                        <span class="glyphicon glyphicon-star"></span>
                        <span class="glyphicon glyphicon-star"></span>
                        </i>
                   
    
	<button type="submit" class="btn  btnBook1 diagonal" value="Book " style="right: 0px;"> Book</button>  <div class="clearfix"></div></div>
	</div>
</div>


<div class="clearfix"></div>
<input type="Hidden" id="cityName" value="<?php echo $cityNameMap; ?>">
<input type="Hidden" id="stateName" value="<?php echo $stateNameMap; ?>">
<input type="Hidden" id="countryName" value="<?php echo $countryNameMap; ?>">
</div> -->



<div class="col-sm-12" style="box-shadow:1px 1px 9px #eee;     padding-top: 10px;padding-bottom: 10px;">

<div class="owl-carousel owl-theme hotel_carousels">
	<?php 
	if(isset($_GET['goingto']))
	{
$hotelListArr=$_SESSION['hotelListArr'];

	foreach ($hotelListArr as $key => $value) {		
		?>
   <div class="item">
      <div class="similar_hotels">
         <img src="<?php echo $value['photo_gallery']?>" width="100%">
         <p class="hot_names" ><?php echo $value['txtPropertyName'];?><br><span></span></p>
         <div class="price"><span><i class="fa fa-rupee"></i><?php echo $value['price']?></span><br>per night</div>
      </div>
      <div class="similar_botp">
      	<!-- <span class="numbrs" ><b><?php echo $value['selStarRating']?></b> / 5</span> -->
         <i class="stars" > 
         	<?php for($i=1;$i<=$value['selStarRating'];$i++)
         	{
         		echo '<span class="fa fa-star"></span>';

         	}

         	for($i=1;$i<=abs($value['selStarRating']-5);$i++)
         	{

         		echo '<span class="fa fa-star-o"></span>';
         	}

         	?>
         
         </i>
        <form action="<?php echo $root_dir;?>hotels/search/book.php">
        	<input type="hidden" name="id_property" value="<?php echo $key;?>">
        	<input type="hidden" name="goingto" value="<?php echo $city_name;?>">
        	<input type="hidden" name="to_hidden" value="<?php echo $city_id;?>">
        	<input type="hidden" name="hotel_month" value="<?php echo $check_in;?>">
        	<input type="hidden" name="hotel_month1" value="<?php echo $check_out;?>">
        	<input type="hidden" name="guest" value="">
        	<input type="hidden" name="child" value="">
        	<input type="hidden" name="child_age_json" value=""> 
        	<input type="Hidden" name="guest_text" value="<?php echo (isset($guestText) ? $guestText : ''); ?>">
        	<button type="submit" class="btn btn-primary " value="Book" >Book</button> </form>
      </div>
   </div>

   <?php } } ?>
</div>







	</div>









<div class="clearfix"></div>
<input type="Hidden" id="cityName" value="<?php echo $cityNameMap; ?>">
<input type="Hidden" id="stateName" value="<?php echo $stateNameMap; ?>">
<input type="Hidden" id="countryName" value="<?php echo $countryNameMap; ?>">
</div>
</div>
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
			var amt = aCnt*aPrice;
			var amt1 = cCnt*acPrice;
			amt1 = parseInt(amt1) + parseInt(cCnt1*acPrice1);
			if($(this).prop('checked')){
				$('#lunch_incl').val(1);
				if(meal_rate > 0)
					$('#meal_rate').val(parseInt(meal_rate) + parseInt(amt)+parseInt(amt1));
				else
					$('#meal_rate').val(parseInt(amt)+parseInt(amt1));
				
				$('#lunchRateDiv').html(parseInt(amt)+parseInt(amt1));
				$('#lunchRateParentDiv').show();
				$('#totalRateDiv').html(parseFloat($('#totalRateDiv').html())+parseInt(amt)+parseInt(amt1));

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
			$('html, body').animate({scrollTop:$('#totalhotel').position().top}, 'slow');
		});

        var jssor_1_options = {
            $AutoPlay: 0,
            $AutoPlaySteps: 4,
            $SlideDuration: 160,
            $SlideWidth: 200,
            $SlideSpacing: 3,
            $Cols: 4,
            $ArrowNavigatorOptions: {
                $Class: $JssorArrowNavigator$,
                $Steps: 4
            },
            $BulletNavigatorOptions: {
                $Class: $JssorBulletNavigator$,
                $SpacingX: 1,
                $SpacingY: 1
            }
        };

        var jssor_1_slider = new $JssorSlider$("jssor_1", jssor_1_options);

        /*responsive code begin*/
        /*remove responsive code if you don't want the slider scales while window resizing*/
        function ScaleSlider() {
            var refSize = jssor_1_slider.$Elmt.parentNode.clientWidth;
            if (refSize) {
                refSize = Math.min(refSize, 809);
                jssor_1_slider.$ScaleWidth(refSize);
            } else {
                window.setTimeout(ScaleSlider, 30);
            }
        }
        ScaleSlider();
        $(window).bind("load", ScaleSlider);
        $(window).bind("resize", ScaleSlider);
        $(window).bind("orientationchange", ScaleSlider);

        var jssor_2_slider = new $JssorSlider$("jssor_2", jssor_1_options);

        /*responsive code begin*/
        /*remove responsive code if you don't want the slider scales while window resizing*/
        function ScaleSlider2() {
            var refSize = jssor_2_slider.$Elmt.parentNode.clientWidth;
            if (refSize) {
                refSize = Math.min(refSize, 809);
                jssor_2_slider.$ScaleWidth(refSize);
            } else {
                window.setTimeout(ScaleSlider, 30);
            }
        }
        ScaleSlider2();
        $(window).bind("load", ScaleSlider2);
        $(window).bind("resize", ScaleSlider2);
        $(window).bind("orientationchange", ScaleSlider2);
        /*responsive code end*/
    });
	//$("#datepicker").datepicker({dateFormat: 'dd-mm-yy',minDate: 0});
	//$("#datepickers").datepicker({dateFormat: 'dd-mm-yy',minDate: 0});

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
   $('.total_guest_room').text(room+' Room(s), '+guest+' Guest(s)');

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
   $('.total_guest_room').text(room+' Room(s), '+guest+' Guest(s)');
   
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
	var spanText = $('.total_guest_room').html();
	var guestText = $('#guest_text').val();
	$(".collapse.in").removeClass("in");

	if(spanText == guestText) return;
	var room=0;
	var noofadult=0;
	var noofchild=0;
	var obj = {};
	var items = [];
	$('.pax_container').each(function(){
		if($(this).hasClass("pax_container"))
		{
			room=$(this).attr("data");
			noofadult=$(this).parent().find(".adultlist_"+room+' .actives').text();  
			noofchild=$(this).parent().find(".childlist_"+room+' .actives').text(); 
			obj[room]={'adult':noofadult,'child':noofchild};
		}
	});
	$("#guestJSON").val(JSON.stringify(obj));
	$('.book-hotel-form').submit();
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
$('.total_guest_room').text(s+' Room(s), '+guest+' Guest(s)');
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
         append+='<a class="edit " onclick="editval(this)" data="'+intId+'"><i  style="font-size:10px;" >Edit</i></a>';
         append+='<div class="clearfix"></div>';
         append+=' </div>';

         $(".outer_pax").append(append);

      var guest=0;     
      $('.actives').each(function(){
      guest=guest+parseInt($(this).text());
      });
     $("#roomval").val(intId);
     $('.total_guest_room').text(intId+' Room(s), '+guest+' Guest(s)');

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
		$('#map-location').click(function(){
			if(typeof delivery_map == 'undefined')
				setTimeout(initialise, 1000);
		});
	});
	var delivery_map;
	function initialise(flag){
		var cityName = $('#cityName').val();
		var stateName = $('#stateName').val();
		var countryName = $('#countryName').val();

		/*if(flag)
			var addressStr = '<?php echo $availableRoomsList[0]['cityName']; ?>,<?php echo $availableRoomsList[0]['stateName']; ?>,<?php echo $availableRoomsList[0]['countryName']; ?>';
		else
			var addressStr = '<?php echo $availableRoomsList[0]['address1']; ?>,<?php echo $availableRoomsList[0]['txtZip']; ?>,<?php echo $availableRoomsList[0]['cityName']; ?>,<?php echo $availableRoomsList[0]['stateName']; ?>,<?php echo $availableRoomsList[0]['countryName']; ?>';
		console.log(addressStr);*/
		var delivery_marker,centerPos;
		var geocoder = new google.maps.Geocoder();
		var txtLatitude = "<?php echo $availableRoomsList[0]['txtLatitude']; ?>";
		var txtLongitude = "<?php echo $availableRoomsList[0]['txtLongitude']; ?>";
		if(txtLatitude && txtLongitude)
			var latlngProperty = new google.maps.LatLng(txtLatitude, txtLongitude);
			geocoder.geocode({
				address: ''+cityName+','+stateName+','+countryName
				}, function(results, status) {
					console.log(status);
				//if(status == 'ZERO_RESULTS')
					//initialise(1);
				if (status === google.maps.GeocoderStatus.OK)
				{
					var zoomVal;
					if(typeof latlngProperty != 'undefined'){
						centerPos = latlngProperty;
						zoomVal = 19;
						console.log(zoomVal);
					}else{
						centerPos = results[0].geometry.location;
						zoomVal = 10;
					}
					delivery_map = new google.maps.Map(document.getElementById('map-delivery-canvas'), {
						zoom: zoomVal,
						mapTypeId: google.maps.MapTypeId.ROADMAP,
						center: centerPos
					});
					delivery_marker = new google.maps.Marker({
						map: delivery_map,
						position: centerPos
					});

					google.maps.event.addListener(delivery_map, "click", function (e) {
						if (delivery_marker)
							delivery_marker.setMap(null);
						delivery_marker = new google.maps.Marker({
							map: delivery_map,
							position: e.latLng
						});
					});
				}
			});
	}

	
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
    content:'<div style="padding:0px 30px;"><p class="confirm_paras"><span class="left_span">Base Fare</span><span class="rupees_ico"><i class="fa fa-inr" aria-hidden="true"></i></span><span  id="baseRateDiv"></span></p>     <p class="confirm_paras"  id="lunchRateParentDiv" style="display:none;"><span class="left_span">Lunch Incl.</span><span class="rupees_ico"><i class="fa fa-inr" aria-hidden="true"></i></span><span  id="lunchRateDiv"></span></p>       <p class="confirm_paras" id="dinnerRateParentDiv" style="display:none;"><span class="left_span">Dinner Incl.</span><span class="rupees_ico"><i class="fa fa-inr" aria-hidden="true"></i></span><span  id="dinnerRateDiv"></span></p>     <p class="confirm_paras"><span class="left_span">Tax CGST</span><span class="rupees_ico"><i class="fa fa-inr" aria-hidden="true"></i></span><span  id="cgstRateDiv"><?php echo $totalPriceInitial*($availableRoomsList[0]['txtCGST']/100); ?></span></p>        <p class="confirm_paras"><span class="left_span">Tax CGST</span><span class="rupees_ico"><i class="fa fa-inr" aria-hidden="true"></i></span><span  id="sgstRateDiv"><?php echo $totalPriceInitial*($availableRoomsList[0]['txtSGST']/100); ?></span></p>      <p class="confirm_paras"><span class="left_span">Total</span><span class="rupees_ico"><i class="fa fa-inr" aria-hidden="true"></i></span><span   id="totalRateDiv"><?php echo $totalPriceInitial + ($totalPriceInitial*($availableRoomsList[0]['txtSGST']/100)) + ($totalPriceInitial*($availableRoomsList[0]['txtSGST']/100)); ?></span></p></div>',
    
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
.room_detaisa{background-color:#fff;transition:0.4s ease-in-out;}
	.room_details_atag{background-color: #fff;    }
	.checkbooking label{color:#555;}
	.indent_for_p p{text-indent: 30px;}



	.rooms_in_hotel {
    outline: none!important;
    font-size: 10px;
    width: 70px;
    color: white!important;
    background-color: #ff0000;
    border: 0px solid #fff;
}

.pagination li.actives{color:#fff;}


.detail_pax p {
    font-size: 11px!important;
    font-weight: bold;
}


.icon_for_rel {
    position: absolute;
    top: 9px;
    left: 13px;
}

.custonlink {
	cursor:pointer;
    background-color: transparent;
    border: 0px none;
}

.color_ablue {
    color: #0000ee;
}

.checkbooking {
    position: relative;
    border: 1px solid #ccc;
    padding: 20px;
    margin-top: 10px;
}
.checkbooking .datepi {
    padding-left: 34px!important;
}

.total_guest_room {
    font-size: 11px;
    color: #808080;
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

.totalhotel i, .totalhotel span {
    font-size: 22px;
}

.checkbooking label {
    color: #555;    font-size: 14px;
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
 $("#demo").slideDown("slow");

 });


</script>
<style>{background-color:red;}</style>

<?php include('../../include/footer.php'); ?>