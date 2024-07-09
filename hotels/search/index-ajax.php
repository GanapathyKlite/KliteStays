<?php 
session_start();
$currentpage="hotelsearch";
include("../../include/database/config.php");

error_reporting(0);
require_once("pagination.class.php");

$perPage = new PerPage();
$page = 1;


$queryJSONArr = $filterRating = $filterHotelType = $filterPrice = $filterAmentitiesForCheckBox=[]; $filterAmentities = $filterHotelName = '';
$queryJSON = json_decode($_POST['queryJSON'],true);
$child_age_jsonArr = $_SESSION['child_age_jsonArr'];
foreach($queryJSON as $queryJSONVal){
	foreach($queryJSONVal as $queryJSONKey => $queryJSONVal1){
		if($queryJSONKey == 'rating')
			$filterRating[] = $queryJSONVal1;
		else if($queryJSONKey == 'hoteltype')
			$filterHotelType[] = $queryJSONVal1;
		else if($queryJSONKey == 'landmark')
			$filterLandmarkType[] = $queryJSONVal1;
		else if($queryJSONKey == 'amentities')
			{
			$filterAmentitiesForCheckBox[]=$queryJSONVal1;

			if(strpos($queryJSONVal1,'#') > 0){
				{
					
					$queryJSONVal1Explode = explode('#',$queryJSONVal1);
					foreach($queryJSONVal1Explode as $queryJSONVal1ExplodeV)
						$filterAmentities .= ' or pf.'.$queryJSONVal1ExplodeV.'=1';
				}
			}else
				$filterAmentities .= ' or pf.'.$queryJSONVal1.'=1';
			}
		else if($queryJSONKey == 'price')
			{
				$filterPriceForCheckBox[]=$queryJSONVal1;
			if(strpos($queryJSONVal1,'#') > 0){
				$queryJSONVal1Explode = explode('#',$queryJSONVal1);
				$filterPrice[] = $queryJSONVal1Explode;
			}else
				$filterPrice[] = $queryJSONVal1;
			}
		else if($queryJSONKey == 'hotel_name')
			$filterHotelName = " and p.txtPropertyName like '%".$queryJSONVal1."%'";
	}
	$filterAmentities = (!empty($filterAmentities) ? trim($filterAmentities,' or ') : '');
}




$queryVal=json_decode($_POST['queryVal'],true);
$city_name = $queryVal['goingto'];
$city_id = $queryVal['to_hidden'];
$check_in = $queryVal['hotel_month'];
$check_out = $queryVal['hotel_month1'];
$price_sort = $queryVal['price_sort'];
$star_sort = $queryVal['star_sort'];
//$rating_sort = $queryVal['rating_sort'];
$_GET['guest']=$queryVal['guestJson'];
$rowcount=(isset($queryVal['rowcount'])&&$queryVal['rowcount']!='')?$queryVal['rowcount']:1;
$guest=json_decode($queryVal['guestJson'],true);
$ajax_from=(isset($queryVal['ajax-from'])&&$queryVal['ajax-from']!='')?1:0;

//print_r($guest);
//json_decode(, true);

$searchguest='';
$countadult = $countchild = $roomcount = 0;
$countWhere = $guestCntHidden = '';
foreach($guest as $key=>$val)
{
	$countadult=$val['adult']+$countadult;
	$countchild=$val['child']+$countchild;
	//$countWhere .= ' and (r.selMaxNoOfAdult >= '.$val['adult'].' and r.selMaxNoOfChild >= '.$val['child'].' and r.selMaxNoOfGuest >= '.($val['adult']+$val['child']).')';
	//$countWhere .= ' and (r.selMaxNoOfAdult >= '.$val['\'adult\''].' and r.selMaxNoOfChild >= '.$val['\'child\''].' and r.selMaxNoOfGuest >= '.($val['\'adult\'']+$val['\'child\'']).')';
		$guestCntHidden .= '<input type=\'Hidden\' name="guest['.$key.'][\'adult\']" value=\''.$val['adult'].'\'><input type=\'Hidden\' name="guest['.$key.'][\'child\']" value=\''.$val['child'].'\'>';
	$countWhere .= ' and rt.selMaxNoOfGuest >= '.($val['adult']+$val['child']);

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
	
	$searchguest.='<p>Room <span class="roomnumber">'.$key.'</span></p>
				<p><span class="this_adult">'.$val['adult'].'</span> Adults, <span class="this_child">'.$val['child'].'</span>  Child</p>
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
				<div class="edit" onclick="editval(this)" data="'.$key.'"><i class="fa fa-pencil" aria-hidden="true"></i></div>
				<div class="minimize" onclick="minimize(this)" data="'.$key.'"><i class="fa fa-compress" aria-hidden="true"></i></div>
				<div class="clearfix"></div>
				</div>';
				$roomcount++;

}

$guestcount=$countadult+$countchild;
$text=$key.' Rooms, '.$guestcount.' Guest';


//$old_query='select  cl.name as country, p.selLandmark,l.txtLandmark,c.name,p.offer_percentage,p.selPropertyTypeID,p.txtPropertyDescription, p.txtPropertyName,p.selStarRating,p.txtAddress2,p.photo_gallery,p.selImages,r.id_property,r.id_room,r.period_from,r.period_to,rt.periodic_rateExtraBedAdult,rt.rateExtraBedAdult,rt.periodic_rateExtraBedChildMoreThanFive,rt.periodic_rateExtraBedChildLessThanFive,rt.rateExtraBedChildMoreThanFive,rt.rateExtraBedChildLessThanFive,rt.id_room_type from ps_property p join ps_property_facility pf on(p.id_property = pf.id_property) left join ps_room r on(p.id_property = r.id_property and r.status=0) left join ps_landmark l on(l.id_landmark=p.selLandmark and l.status=0 and l.action=\'\') left join ps_room_type rt on(r.id_room = rt.id_room) left join ps_city c on(c.id_city=\''.$city_id.'\') left join ps_country_lang cl on(cl.id_country=c.id_country)  where 1'.$countWhere.(isset($filterRating) && !empty($filterRating) ? ' and p.selStarRating in('.implode(',',$filterRating).')' : '').(isset($filterLandmarkType) && !empty($filterLandmarkType) ? ' and p.selLandmark in('.implode(',',$filterLandmarkType).')' : '').(isset($filterHotelType) && !empty($filterHotelType) ? ' and p.selPropertyTypeID in('.implode(',',$filterHotelType).')' : '').(!empty($filterAmentities) ? ' and ('.$filterAmentities.')' : '').(!empty($filterHotelName) ? $filterHotelName : '').' and p.status=0 and p.is_delete!=1 and p.selCityId=\''.$city_id.'\'';
// echo $query;
// die;
$query='select  cl.name as country, p.selLandmark,l.txtLandmark,c.name,p.offer_percentage,p.selPropertyTypeID,p.txtPropertyDescription, p.txtPropertyName,p.selStarRating,p.txtAddress1,p.txtAddress2,p.photo_gallery,p.selImages,r.id_property,r.id_room,r.period_from,r.period_to,rt.periodic_rateExtraBedAdult,rt.rateExtraBedAdult,rt.periodic_rateExtraBedChildMoreThanFive,rt.periodic_rateExtraBedChildLessThanFive,rt.rateExtraBedChildMoreThanFive,rt.rateExtraBedChildLessThanFive,rt.id_room_type from ps_property p join ps_property_facility pf on(p.id_property = pf.id_property) left join ps_room r on(p.id_property = r.id_property and r.status=0) left join ps_landmark l on(l.id_landmark=p.selLandmark and l.status=0 and l.action=\'\') left join ps_room_type rt on(r.id_room = rt.id_room) left join ps_city c on(c.id_city=\''.$city_id.'\') left join ps_country_lang cl on(cl.id_country=c.id_country)  where 1'.$countWhere.(isset($filterLandmarkType) && !empty($filterLandmarkType) ? ' and p.selLandmark in('.implode(',',$filterLandmarkType).')' : '').(!empty($filterAmentities) ? ' and ('.$filterAmentities.')' : '').(!empty($filterHotelName) ? $filterHotelName : '').' and p.status=0 and rt.status=0 and p.is_delete!=1 and p.selCityId=\''.$city_id.'\'';

$availableRoomsList = $database_hotel->query($query)->fetchAll();
$cityname=$availableRoomsList[0]['name'];
$searchMonth	= date('n',strtotime($check_in));
$searchYear		= date('Y',strtotime($check_in));
$searchDate		= date('d',strtotime($check_in));
$searchDate1	= date('d',strtotime($check_out));

$roomRateArr = $propertyRooms = $hotelListArr = array();

if(isset($availableRoomsList) && !empty($availableRoomsList)){

	foreach($availableRoomsList as $incKey => &$roomVal){
		$begin 	= new DateTime(date('Y-m-d',strtotime($check_in)));
		//$end 	= new DateTime(date('Y-m-d',strtotime($check_out.' +1 day')));
		$end 	= new DateTime(date('Y-m-d',strtotime($check_out)));
		$daterange = new DatePeriod($begin, new DateInterval('P1D'), $end);

		// get inventory
		foreach($daterange as $date){
			$dateValue = $date->format("Y-m-d");
			$year	= date('Y',strtotime($dateValue));
			$month	= date('n',strtotime($dateValue));
			$dateV	= date('d',strtotime($dateValue));
			$dateV1	= date('j',strtotime($dateValue));

			$inventoryVal = $database_hotel->query('select `'.$dateV1.'` as inventory from ps_room_available_inventory where allot_avail=1 and id_room='.$roomVal['id_room'].' and year='.$year.' and month='.$month)->fetchAll();
			if(!isset($inventoryVal[0][0]) || empty($inventoryVal[0][0])){
				unset($availableRoomsList[$incKey]);
				continue 2;
			}else
				$roomVal['inventory'][] = $inventoryVal[0][0];
		}

		$masterRate=$without_masterRate= $monthlyRate = $periodicRate = $roomTypeList = array();
		// get rate
//echo 'select r.id_room,rt.* from ps_room r left join ps_room_type rt on(r.id_room = rt.id_room) where r.id_room=\''.$roomVal['id_room'].'\' and rt.id_room_type='.$roomVal['id_room_type'];
//die;

		$roomTypeArr = $database_hotel->query('select r.id_room,rt.* from ps_room r left join ps_room_type rt on(r.id_room = rt.id_room) where r.id_room=\''.$roomVal['id_room'].'\' and rt.id_room_type=\''.$roomVal['id_room_type'].'\'')->fetchAll(PDO::FETCH_ASSOC);
		if(isset($roomTypeArr) && !empty($roomTypeArr)){
				foreach($roomTypeArr as $roomTypeKey => $roomTypeVal){
				
					if(in_array(2,explode(',',$roomTypeVal['is_breakfast'])))
					{
					$masterRate[0] = $roomTypeVal['withoutbreakfast_rate_sun'];
					$masterRate[1] = $roomTypeVal['withoutbreakfast_rate_mon'];
					$masterRate[2] = $roomTypeVal['withoutbreakfast_rate_tue'];
					$masterRate[3] = $roomTypeVal['withoutbreakfast_rate_wed'];
					$masterRate[4] = $roomTypeVal['withoutbreakfast_rate_thu'];
					$masterRate[5] = $roomTypeVal['withoutbreakfast_rate_fri'];
					$masterRate[6] = $roomTypeVal['withoutbreakfast_rate_sat'];
					}
					else if(in_array(1,explode(',',$roomTypeVal['is_breakfast']))||$roomTypeVal['is_breakfast']==0)
					{
						$masterRate[0] = $roomTypeVal['withbreakfast_rate_sun'];
						$masterRate[1] = $roomTypeVal['withbreakfast_rate_mon'];
						$masterRate[2] = $roomTypeVal['withbreakfast_rate_tue'];
						$masterRate[3] = $roomTypeVal['withbreakfast_rate_wed'];
						$masterRate[4] = $roomTypeVal['withbreakfast_rate_thu'];
						$masterRate[5] = $roomTypeVal['withbreakfast_rate_fri'];
						$masterRate[6] = $roomTypeVal['withbreakfast_rate_sat'];
					}
				}
		}
		$rateValue = 0;
		foreach($daterange as $dateK => $date){
			$rateRoomType = array();
			$dateValue = $date->format("Y-m-d");
			$year	= date('Y',strtotime($dateValue));
			$month	= date('n',strtotime($dateValue));
			$dateV	= date('d',strtotime($dateValue));


			$periodicRateList = $database_hotel->query('select * from ps_room_periodic_rate where id_room=\''.$roomVal['id_room'].'\' and id_room_type=\''.$roomVal['id_room_type'].'\' and \''.$dateValue.'\' >= period_from and \''.$dateValue.'\' <= period_to')->fetchAll();

			
			

			$periodicRate = [];
			if(isset($periodicRateList) && !empty($periodicRateList))
					foreach($periodicRateList as $periodicRateKey => $periodicRateVal){
						if(!empty($periodicRateVal['periodic_is_breakfast'])){
						if(in_array(2,explode(',',$periodicRateVal['periodic_is_breakfast'])))
						{
							$periodicRate[0] = $periodicRateVal['withoutbreakfast_periodic_rate_sun'];
							$periodicRate[1] = $periodicRateVal['withoutbreakfast_periodic_rate_mon'];
							$periodicRate[2] = $periodicRateVal['withoutbreakfast_periodic_rate_tue'];
							$periodicRate[3] = $periodicRateVal['withoutbreakfast_periodic_rate_wed'];
							$periodicRate[4] = $periodicRateVal['withoutbreakfast_periodic_rate_thu'];
							$periodicRate[5] = $periodicRateVal['withoutbreakfast_periodic_rate_fri'];
							$periodicRate[6] = $periodicRateVal['withoutbreakfast_periodic_rate_sat'];
						}else if(in_array(1,explode(',',$roomTypeVal['periodic_is_breakfast']))||$roomTypeVal['periodic_is_breakfast']==0)
						{
							$periodicRate[0] = $periodicRateVal['withbreakfast_periodic_rate_sun'];
							$periodicRate[1] = $periodicRateVal['withbreakfast_periodic_rate_mon'];
							$periodicRate[2] = $periodicRateVal['withbreakfast_periodic_rate_tue'];
							$periodicRate[3] = $periodicRateVal['withbreakfast_periodic_rate_wed'];
							$periodicRate[4] = $periodicRateVal['withbreakfast_periodic_rate_thu'];
							$periodicRate[5] = $periodicRateVal['withbreakfast_periodic_rate_fri'];
							$periodicRate[6] = $periodicRateVal['withbreakfast_periodic_rate_sat'];
						}
					}
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
				if($guestval['adult'] > 2){
					$extraAdult = $guestval['adult'] - 2;
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
		
		if(isset($rateValue) && !empty($rateValue)){
			if(!isset($propertyRooms[$roomVal['id_property']]['rate']) || (isset($propertyRooms[$roomVal['id_property']]['rate']) && $rateValue < $propertyRooms[$roomVal['id_property']]['rate'])){
				$propertyRooms[$roomVal['id_property']]['id_room'] = $roomVal['id_room'].'_'.$roomVal['id_room_type'];
				$propertyRooms[$roomVal['id_property']]['rate'] = $rateValue;
			}
		}
	}
}
$price = array();
/*foreach ($availableRoomsList as $keyRoom => $row)
{
	if(isset($filterPrice) && !empty($filterPrice)){
		$priceFilterFlag = 0;
		foreach($filterPrice as $filterPriceVal){
			if(is_array($filterPriceVal) && count($filterPriceVal) == 2){
				if($row['rate'] >= $filterPriceVal[0] && $row['rate'] <= $filterPriceVal[1]){
					$priceFilterFlag = 1;
					break;
				}
			}elseif($row['rate'] <= $filterPriceVal){
				$priceFilterFlag = 1;
				break;
			}
		}
		if(!$priceFilterFlag){
			unset($availableRoomsList[$keyRoom]);
			continue;
		}
	}
	$price[$keyRoom] = $row['rate'];
}*/
if(isset($availableRoomsList) && !empty($availableRoomsList)){
		foreach ($availableRoomsList as $keyRoom => $row)
		{
			$price[$keyRoom] = $row['rate'];
		}
		array_multisort($price, SORT_ASC, $availableRoomsList);
	}
//array_multisort($price, SORT_ASC, $availableRoomsList);


$hotelConfig = array();
$getHotelConfigValues = $database->query("select name,value from ps_configuration where name = 'ps_hotel_service_tax' or  name = 'ps_hotel_commission' or  name = 'ps_hotel_pay_cgst_2499' or  name = 'ps_hotel_pay_sgst_2499' or  name = 'ps_hotel_pay_cgst_7499' or  name = 'sps_hotel_pay_sgst_7499' or  name = 'ps_hotel_pay_cgst_7500' or  name = 'ps_hotel_pay_sgst_7500'")->fetchAll();
if(isset($getHotelConfigValues) && !empty($getHotelConfigValues)){
	foreach($getHotelConfigValues as $hotelVal){
		$hotelConfig[$hotelVal['name']] = $hotelVal['value'];
	}
}
$ps_hotel_commission = (isset($hotelConfig['ps_hotel_commission']) && !empty($hotelConfig['ps_hotel_commission']) ? $hotelConfig['ps_hotel_commission'] : 0);
$ps_hotel_service_tax = (isset($hotelConfig['ps_hotel_service_tax']) && !empty($hotelConfig['ps_hotel_service_tax']) ? $hotelConfig['ps_hotel_service_tax'] : 0);

if(isset($availableRoomsList) && !empty($availableRoomsList)){
	foreach($availableRoomsList as $incKey1 => &$roomVal1)
		if(isset($propertyRooms[$roomVal1['id_property']]['id_room']) && $roomVal1['id_room'].'_'.$roomVal1['id_room_type'] != $propertyRooms[$roomVal1['id_property']]['id_room'])
			unset($availableRoomsList[$incKey1]);
		else{
			// if inventory, but no rate, then skip
			if(!isset($roomVal1['rate']) || empty($roomVal1['rate']))
				continue;
				$hotelListArr[$roomVal1['id_property']]['price'] = $roomVal1['rate'];
				$hotelListArr[$roomVal1['id_property']]['txtAddress1'] = $roomVal1['txtAddress1'];
				$hotelListArr[$roomVal1['id_property']]['offer_percentage'] = $roomVal1['offer_percentage'];
				$hotelListArr[$roomVal1['id_property']]['offer_percent']='';
				$hotelListArr[$roomVal1['id_property']]['txtPropertyName']	= $roomVal1['txtPropertyName'];
				$hotelListArr[$roomVal1['id_property']]['selPropertyTypeID']	= $roomVal1['selPropertyTypeID'];
				$hotelListArr[$roomVal1['id_property']]['txtPropertyDescription']	= $roomVal1['txtPropertyDescription'];
				$hotelListArr[$roomVal1['id_property']]['selStarRating']	= $roomVal1['selStarRating'];
				$hotelListArr[$roomVal1['id_property']]['cityname']	= $roomVal1['name'];
				$hotelListArr[$roomVal1['id_property']]['selLocation']		= $roomVal1['txtLandmark'].', '.$city_name.', '.$roomVal1['country'];

			
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
			
			//$tds_amt = $hotelListArr[$roomVal1['id_property']]['price'] * ($ps_hotel_service_tax/100);
			//$commission_amt = $hotelListArr[$roomVal1['id_property']]['price'] * ($ps_hotel_commission/100);
			// $hotelListArr[$roomVal1['id_property']]['price'] = round(($hotelListArr[$roomVal1['id_property']]['price'] - $commission_amt) + $tds_amt);

			if($hotelListArr[$roomVal1['id_property']]['price'] % 2 != 0)
				$hotelListArr[$roomVal1['id_property']]['price'] = $hotelListArr[$roomVal1['id_property']]['price']+1;
			$hotelListArr[$roomVal1['id_property']]['offer_price']=$hotelListArr[$roomVal1['id_property']]['price'];
			if($hotelListArr[$roomVal1['id_property']]['offer_percentage']!='')
			{				
				$offer_percentage=$hotelListArr[$roomVal1['id_property']]['offer_percentage'];
				$bf=($hotelListArr[$roomVal1['id_property']]['price']/(100+$offer_percentage))*100;     
				$offerrate=round($hotelListArr[$roomVal1['id_property']]['price']-$bf);  
				$hotelListArr[$roomVal1['id_property']]['offer_price'] =round($hotelListArr[$roomVal1['id_property']]['price']-$offerrate); 

				$hotelListArr[$roomVal1['id_property']]['offer_percent']=round(($offerrate/$hotelListArr[$roomVal1['id_property']]['price'])*100);



			}

			if($hotelListArr[$roomVal1['id_property']]['price'] > 999 && $hotelListArr[$roomVal1['id_property']]['price'] <= 2499){
		      $hotelListArr[$roomVal1['id_property']]['price']=$hotelListArr[$roomVal1['id_property']]['price']+(round(($hotelListArr[$roomVal1['id_property']]['price']*(($hotelConfig['ps_hotel_pay_cgst_2499']+$hotelConfig['ps_hotel_pay_sgst_2499'])/100)*count($guest))));
		      
		    }else if($hotelListArr[$roomVal1['id_property']]['price'] > 2499 && $hotelListArr[$roomVal1['id_property']]['price'] <= 7499){
		      $hotelListArr[$roomVal1['id_property']]['price']=$hotelListArr[$roomVal1['id_property']]['price']+(round(($hotelListArr[$roomVal1['id_property']]['price']*(($hotelConfig['ps_hotel_pay_cgst_7499']+$hotelConfig['ps_hotel_pay_sgst_7499'])/100)*count($guest))));
		    
		    }else if($hotelListArr[$roomVal1['id_property']]['price'] > 7499){
		      $hotelListArr[$roomVal1['id_property']]['price']=$hotelListArr[$roomVal1['id_property']]['price']+(round(($hotelListArr[$roomVal1['id_property']]['price']*(($hotelConfig['ps_hotel_pay_cgst_7500']+$hotelConfig['ps_hotel_pay_sgst_7500'])/100)*count($guest))));
		    }
		}
}

$cityNames = $database->query("SELECT c.id_city,c.name,s.name as stateName from ps_city c left join ps_state s on(s.id_state = c.id_state) where c.status=0 and is_for_hotel=1")->fetchAll();
if(isset($cityNames) && !empty($cityNames)){
	foreach($cityNames as $cityK => $city){
		$cityNamesArr[$cityK]['id']		= $city['id_city'];
		$cityNamesArr[$cityK]['label']	= $city['name'];//.', '.$city['stateName'];
	}
}


$newhotelListArr=$hotelListArr;

function aasort(&$array, $key,$sorttype) {
    $sorter=array();
    $ret=array();
    reset($array);
    foreach ($array as $ii => $va) {
        $sorter[$ii]=$va[$key];
    }
    if($sorttype==0)
    {
    	asort($sorter);
    }else
    {
		arsort($sorter);
    }
    foreach ($sorter as $ii => $va) {
        $ret[$ii]=$array[$ii];
    }
    $array=$ret;
}

if($price_sort!='')
{
aasort($newhotelListArr,"offer_price",$price_sort);	
}
if($star_sort!='')
{
aasort($newhotelListArr,"selStarRating",$star_sort);
}

	$colors = array_column($newhotelListArr, 'selStarRating');
	$count_star = array_count_values($colors);

	$propertytype = array_column($newhotelListArr, 'selPropertyTypeID');
	$count_property_type = array_count_values($propertytype);

	$pricerange = array(
	    array( 0, 999),
	    array( 1000, 3000),
	    array( 3000, 5000),
	    array( 5000, 10000) 
	);
	foreach($hotelListArr as $keyss=>$valess)
	{
		if($valess['offer_price']=='')
		{
			$pricelist[]=$valess['price'];
		}else
		{
			$pricelist[]=$valess['offer_price'];
		}
	}
	$count_price=array('0-999'=>0,'1000-3000'=>0,'3000-5000'=>0,'5000-10000'=>0);
	foreach($pricelist as $keyp=>$valuep)
	{
		if($valuep>=0&&$valuep<=999)
		{
			$count_price['0-999']=$count_price['0-999']+1;
		}
		else if($valuep>=1000&&$valuep<=3000)
		{
			
			$count_price['1000-3000']=$count_price['1000-3000']+1;
		}
		else if($valuep>=1000&&$valuep<=3000)
		{
			$count_price['1000-3000']=$count_price['1000-3000']+1;
		}
		else if($valuep>=3000&&$valuep<=5000)
		{
			$count_price['3000-5000']=$count_price['3000-5000']+1;
		}
		else if($valuep>=5000&&$valuep<=10000)
		{
			$count_price['5000-10000']=$count_price['5000-10000']+1;
		}

	}

if(isset($filterRating) && !empty($filterRating)){	
	foreach($newhotelListArr as $keysstar=>$valstar)
	{	
		$priceFilterFlag = 0;
			if(in_array($valstar['selStarRating'],$filterRating))
			{
				$priceFilterFlag = 1;
				
			}
			if($priceFilterFlag==0){
				unset($newhotelListArr[$keysstar]);
				continue;
			}

	}
}

if(isset($filterHotelType) && !empty($filterHotelType)){	
	foreach($newhotelListArr as $keystar=>$valstar)
	{	$priceFilterFlag = 0;
		
			if(in_array($valstar['selPropertyTypeID'],$filterHotelType))
			{
				$priceFilterFlag = 1;
				
			}
			if($priceFilterFlag==0){
				unset($newhotelListArr[$keystar]);
				continue;
			}
	}

}
if(isset($filterPrice) && !empty($filterPrice)){
	foreach ($newhotelListArr as $keyRooma => $rowss)
	{
		$priceFilterFlag = 0;

			
			foreach($filterPrice as $keydsd=>$valsdd)
			{

				
				$rate=$rowss['price'];
				if($rowss['offer_price']!=$rowss['price'])
				{
					$rate=$rowss['offer_price'];
				}
				if(is_array($valsdd) && count($valsdd) == 2){

					if($rate >= $valsdd[0] && $rate <= $valsdd[1]){
						$priceFilterFlag = 1;
						break;
					}
				}elseif($rate <= $valsdd){
					$priceFilterFlag = 1;
					break;
				}
				
				
			}
			if($priceFilterFlag==0){
					unset($newhotelListArr[$keyRooma]);
					continue;
			}
			
	}
}

?>	


        

<button type="button" class="navbar-toggle toggle_filters" >
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span> 
      </button>



  <?php    if($ajax_from==0){ ?>


        <aside id="secondary" class="left-sidebar widget-area one-fourth" role="complementary">
            <ul>
               <li class="widget widget-sidebar">
	            <article class="refine-search-results byt_search_widget BookYourTravel_Search_Widget">
                    
                        <h4>Refine search results</h4>
                        <div>
                     
                          
					<div class="column">
						<div class="where dt">
							Where?			
						</div>
						<div class="where rooms ">
							<div class="hotelde_inns">    
							  <p class="hotel_found"><span class="count_amount"><?php echo count($hotelListArr);?></span> Hotels found in <span><?php echo $cityname; ?></span></p>
							<h6>Landmarks </h6>

							<a href="javascript:void(0)" class="search_bylocs">Search by landmark</a>

							</div>
							
						</div>
					</div><hr>
					  <li>
                      <div class="dropdown col-xs-12">
                          <h2 class="show_hide_type">Hotel Types</h2>
                          <Span class="show_atag_hotel" href="#hotelTypes" data-toggle="collapse" >Show</Span>
                          <div id="hotelTypes"  class="collapse">
                          <ul class="dropdown-menus new_drop_menu ">

                              <li><a href="#"><input type="checkbox" data-sort-type="hoteltype" data-value="1" <?php echo (!empty($filterHotelType))?(in_array(1,$filterHotelType)?'checked':''):'';?>>
                              
                              <label>Hotel <span class="filter_number hotel_type_1"><?php echo isset($count_property_type[1])?$count_property_type[1]:''; ?></span></label>
                              </li>

                              <li><a href="#"><input type="checkbox" data-sort-type="hoteltype" data-value="2" <?php echo (!empty($filterHotelType))?(in_array(2,$filterHotelType)?'checked':''):'';;?>>
                              
                              <label>Resort <span class="filter_number hotel_type_2"><?php echo isset($count_property_type[2])?$count_property_type[2]:''; ?></span></label></a></li>

                              <li><a href="#"><input type="checkbox" data-sort-type="hoteltype" data-value="3" <?php echo (!empty($filterHotelType))?(in_array(3,$filterHotelType)?'checked':''):'';?>>
                              
                              <label>Apartment <span class="filter_number hotel_type_3"><?php echo isset($count_property_type[3])?$count_property_type[3]:''; ?></span></label></a></li>

                              <li><a href="#"><input type="checkbox" data-sort-type="hoteltype" data-value="4" <?php echo (!empty($filterHotelType))?(in_array(4,$filterHotelType)?'checked':''):'';?>>
                              
                              <label>Villa <span class="filter_number hotel_type_4"><?php echo isset($count_property_type[4])?$count_property_type[4]:''; ?></span></label></a></li>

                              <li><a href="#"><input type="checkbox" data-sort-type="hoteltype" data-value="5" <?php echo  (!empty($filterHotelType))?(in_array(5,$filterHotelType)?'checked':''):'';?>>
                              
                              <label>Homestay <span class="filter_number hotel_type_5"><?php echo isset($count_property_type[5])?$count_property_type[5]:'';; ?></span></label></a></li>

                              <li><a href="#"><input type="checkbox" data-sort-type="hoteltype" data-value="6" <?php echo  (!empty($filterHotelType))?(in_array(6,$filterHotelType)?'checked':''):'';?>>
                              
                              <label>Dormitory <span class="filter_number hotel_type_6"><?php echo isset($count_property_type[6])?$count_property_type[6]:'';; ?></span></label></a></li>

                              <li><a href="#"><input type="checkbox" data-sort-type="hoteltype" data-value="7" <?php echo (!empty($filterHotelType))?(in_array(7,$filterHotelType)?'checked':''):'';?>>
                              
                              <label>Guest House <span class="filter_number hotel_type_7"><?php echo isset($count_property_type[7])?$count_property_type[7]:'';; ?></span></label></a></li>
                          </ul>
                      </div>
                      </div>
                  </li><hr>

	                <li>
                      <div class="dropdown col-xs-12 ">
                           <h2>Price</h2>
                           	<Span class="show_atag_hotel"   href="#hotelPrice" data-toggle="collapse" >Show</Span>
                <div id="hotelPrice" class="collapse">
                          <ul class="dropdown-menus new_drop_menu ">
                
                            <li><a href="#">
                            <input type="checkbox" data-sort-type="price" data-value="999" <?php echo (!empty($filterPriceForCheckBox))?(in_array('999',$filterPriceForCheckBox)?'checked':''):'';?>>
                            
                            <label> Upto <i class="fa fa-rupee"></i> 999 <span class="filter_number price_range_0_999"><?php echo isset($count_price['0-999'])?$count_price['0-999']:'';?></span> </label></a></li>

                              <li><a href="#"><input type="checkbox" data-sort-type="price" data-value="1000#3000" <?php echo (!empty($filterPriceForCheckBox))?(in_array('1000#3000',$filterPriceForCheckBox)?'checked':''):'';?>>

                              <label> <i class="fa fa-rupee"></i> 1000 to <i class="fa fa-rupee"></i> 3000 <span class="filter_number price_range_1000_3000">
  							  <?php echo isset($count_price['1000-3000'])?$count_price['1000-3000']:'';?></span> </label></a></li>



                              <li><a href="#"><input type="checkbox" data-sort-type="price" data-value="3000#5000" <?php echo (!empty($filterPriceForCheckBox))? (in_array('3000#5000',$filterPriceForCheckBox)?'checked':''):'';?>>
                              <label> <i class="fa fa-rupee"></i> 3000 to <i class="fa fa-rupee"></i> 5000 <span class="filter_number price_range_3000_5000"><?php echo isset($count_price['3000-5000'])?$count_price['3000-5000']:'';?></span> </label></a></li>



                              <li><a href="#"><input type="checkbox" data-sort-type="price" data-value="5000#10000" <?php echo (!empty($filterPriceForCheckBox))? (in_array('5000#10000',$filterPriceForCheckBox)?'checked':''):'';?>>

                              <label> <i class="fa fa-rupee"></i> 5000 to <i class="fa fa-rupee"></i> 10000 <span class="filter_number price_range_5000_10000"><?php echo isset($count_price['5000-10000'])?$count_price['5000-10000']:'';?></span> </label></a></li>


                          </ul>
                      </div>
                      </div>
                  </li>
	                


	               
	               <li><hr>
                      <div class="dropdown col-xs-12 ">
             <h2>Star Category</h2>
              <Span class="show_atag_hotel"   href="#hotelStar" data-toggle="collapse" >Show</Span>
              <div id="hotelStar" class="collapse">
                          <ul class="dropdown-menus new_drop_menu ">
                          

                              <li><a href="#"><input  <?php echo (isset($star)&&$star==1)?'checked':'';?> type="checkbox"  id="1_star" data-sort-type="rating" data-value="1"  <?php echo (!empty($filterRating))?(in_array('1',$filterRating)?'checked':''):'';?>>
                            <label for="1_star">
                               <!-- <i class="fa fa-star-o" aria-hidden="true"></i> -->
                              <span><i class="material-icons">&#xE838;</i></span>
                              <span class="filter_number star_rating_1"><?php echo (!empty($count_star))?(in_array('1',$count_star)?$count_star[1]:''):'';?></span>

                            </label></a></li>
                            
                            <li><a href="#"><input <?php echo (isset($star)&&$star==2)?'checked':'';?>  type="checkbox" id="2_star" data-sort-type="rating" data-value="2" <?php echo (!empty($filterRating))?(in_array('2',$filterRating)?'checked':''):'';?>>
                            <label for="2_star">
                            <span><i class="material-icons">&#xE838;</i></span>
                            <span><i class="material-icons">&#xE838;</i></span>
                              <span class="filter_number star_rating_2"><?php echo (!empty($count_star))?(in_array('2',$count_star)?$count_star[2]:''):'';?></span>
                            </label></a></li>

                            <li><a href="#"><input <?php echo (isset($star)&&($star==3))?'checked':'';?>  type="checkbox" id="3_star" data-sort-type="rating" data-value="3" <?php echo (!empty($filterRating))?(in_array('3',$filterRating)?'checked':''):'';?>>
                            <label for="3_star">
                            <span><i class="material-icons">&#xE838;</i></span>
                             <span><i class="material-icons">&#xE838;</i></span>
                              <span><i class="material-icons">&#xE838;</i></span>
                              <span class="filter_number star_rating_3"><?php echo (!empty($count_star))?(in_array('3',$count_star)?$count_star[3]:''):'';?></span>
                            </label></a></li>

                            <!--<li class="divider"></li>-->
                            <li><a href="#"><input <?php echo (isset($star)&&$star==4)?'checked':'';?>  type="checkbox" id="4_star" data-sort-type="rating" data-value="4" <?php echo (!empty($filterRating))?(in_array('4',$filterRating)?'checked':''):'';?>>
                            <label for="4_star">
                             <span><i class="material-icons">&#xE838;</i></span>
                              <span><i class="material-icons">&#xE838;</i></span>
                               <span><i class="material-icons">&#xE838;</i></span>
                                <span><i class="material-icons">&#xE838;</i></span>
                              <span class="filter_number star_rating_4"><?php echo (!empty($count_star))?(in_array('4',$count_star)?$count_star[4]:''):'';?></span>
                            </label></a></li>

                            <li><a href="#"><input <?php echo (isset($star)&&$star==5)?'checked':'';?>  type="checkbox" id="5_star" data-sort-type="rating" data-value="5" <?php echo (!empty($filterRating))?(in_array(5,$filterRating)?'checked':''):'';?>>
                            <label for="5_star">
                             <span><i class="material-icons">&#xE838;</i></span>
                              <span><i class="material-icons">&#xE838;</i></span>
                               <span><i class="material-icons">&#xE838;</i></span>
                                <span><i class="material-icons">&#xE838;</i></span>
                                 <span><i class="material-icons">&#xE838;</i></span>
                              <span class="filter_number star_rating_5"><?php echo (!empty($count_star))?(in_array('5',$count_star)?$count_star[5]:''):'';?></span>
                            </label></a></li>

                          </ul>
                      </div>
                      </div>
                  </li><hr>
	                


 					<li>
                      <div class="dropdown col-xs-12">
                           <h2> Amenities</h2>
                           <Span class="show_atag_hotel"    href="#hotelAmenities" data-toggle="collapse" >Show</Span>
              <div id="hotelAmenities" class="collapse">
                          <ul class="dropdown-menus new_drop_menu " >
                              <li><a href="#"><input type="checkbox" data-sort-type="amentities" data-value="business_center" <?php echo in_array('business_center',$filterAmentitiesForCheckBox)?'checked':'';?>>
                              <label>Business Services</label></a></li>

                              <!--<li><a href="#"><label><input type="checkbox" data-sort-type="amentities" data-value="internet#internet_access_in_rooms">Free Internet</label></a></li>-->
                              <li><a href="#"><input type="checkbox" data-sort-type="amentities" data-value="front_desk_24_hours#front_desk" <?php echo in_array('front_desk_24_hours#front_desk',$filterAmentitiesForCheckBox)?'checked':'';?>>
                              <label>Front desk</label></a></li>

                <li><a href="#"><input type="checkbox" data-sort-type="amentities" data-value="travel_desk" <?php echo in_array('travel_desk',$filterAmentitiesForCheckBox)?'checked':'';?>><label>Travel Desk</label></a></li>

                <li><a href="#"><input type="checkbox" data-sort-type="amentities" data-value="internet#internet_access_in_rooms"  <?php echo in_array('internet#internet_access_in_rooms',$filterAmentitiesForCheckBox)?'checked':'';?>><label>Internet</label></a></li>

                <li><a href="#"><input type="checkbox" data-sort-type="amentities" data-value="bar" <?php echo in_array('bar',$filterAmentitiesForCheckBox)?'checked':'';?>><label>Bar</label></a></li>
                              <li><a href="#"><input type="checkbox" data-sort-type="amentities" data-value="laundry"><label>Laundry Service</label></a></li>
                  

                <li><a href="#"><input type="checkbox" data-sort-type="amentities" data-value="free_parking#bus_parking#indoor_parking#outdoor_parking#parking#valet_parking" <?php echo in_array('free_parking#bus_parking#indoor_parking#outdoor_parking#parking#valet_parking',$filterAmentitiesForCheckBox)?'checked':'';?>><label>Parking Facility</label></a></li>

                              <li><a href="#"><input type="checkbox" data-sort-type="amentities" data-value="restaurant#coffee_shop" <?php echo in_array('restaurant#coffee_shop',$filterAmentitiesForCheckBox)?'checked':'';?>><label>Restaurant/Coffee Shop</label></a></li>

                              <li><a href="#"><input type="checkbox" data-sort-type="amentities" data-value="room_service#room_service_24_hours" <?php echo in_array('room_service#room_service_24_hours',$filterAmentitiesForCheckBox)?'checked':'';?>><label>Room Service</label></a></li>

                  
                <li><a href="#"><input type="checkbox" data-sort-type="amentities" data-value="spa#ayurveda_spa"  <?php echo in_array('spa#ayurveda_spa',$filterAmentitiesForCheckBox)?'checked':'';?>><label>Spa</label></a></li>

                              <li><a href="#"><input type="checkbox" data-sort-type="amentities" data-value="swimming_pool" <?php echo in_array('amentities',$filterAmentitiesForCheckBox)?'checked':'';?>>
                              <label>Swimming Pool</label></a></li>

                              <li><a href="#"><input type="checkbox" data-sort-type="amentities" data-value="disco#casino#night_club#squash#exercise_gym#bowling" <?php echo in_array('disco#casino#night_club#squash#exercise_gym#bowling',$filterAmentitiesForCheckBox)?'checked':'';?>>
                              <label>Indoor Entertainment</label></a></li>

                  
                              <li><a href="#"><input type="checkbox" data-sort-type="amentities" data-value="hiking#horseback_riding#snow_skiing#volleyball#exercise_gym#bowling#basketball_court#boating#fishing#golf_driving_range#golf_putting_green#jogging_track#skiing_snow#tennis" <?php echo in_array('hiking#horseback_riding#snow_skiing#volleyball#exercise_gym#bowling#basketball_court#boating#fishing#golf_driving_range#golf_putting_green#jogging_track#skiing_snow#tennis',$filterAmentitiesForCheckBox)?'checked':'';?>><label>Outdoor Activities</label></a></li>

                          </ul>
                      </div>
                      </div>
                      </article>
                  </li>
	            </ul>
	      


</aside>

<?php }


else{
 $query="SELECT * from (select id_state from ps_city where id_city=$city_id) as s left join ps_city c on (c.id_state=s.id_state) where c.is_for_hotel=1 ORDER BY RAND() limit 10 ";
                           $list=$database_hotel->query($query)->fetchAll(PDO::FETCH_ASSOC);
                         //  print_r($list);?>
	


        <aside id="secondary" class="left-sidebar widget-area one-fourth" role="complementary">
            <ul>
               <li class="widget widget-sidebar">
	            <article class="refine-search-results byt_search_widget BookYourTravel_Search_Widget">
                    
                        <h4>Refine search results</h4>
                        <div>
                     
                          
					<div class="column">
						<div class="where dt">
							Where?			
						</div>
						<div class="where rooms ">
							<div class="hotelde_inns">    
							  <p class="hotel_found"><span class="count_amount"><?php echo count($hotelListArr);?></span> Hotels found in <span><?php echo $cityname; ?></span></p>
							<h6>Landmarks </h6>

							<a href="javascript:void(0)" class="search_bylocs">Search by landmark</a>

							</div>
							
						</div>
					</div><hr>
					  <li>
                      <div class="dropdown col-xs-12">
                          <h2 class="show_hide_type" >Hotels In <?php echo $cityname;?></h2>
                          	<Span class="show_atag_hotel" href="#hotelTypes" data-toggle="collapse" >Show</Span>
                          <div id="hotelTypes"  class="collapse">
                          <ul class="dropdown-menus new_drop_menu ">

                              <li><a href="#"><input type="checkbox" data-sort-type="hoteltype" data-value="1" <?php echo in_array(1,$filterHotelType)?'checked':'';?>>
                              
                              <label>Hotels in <?php echo $cityname; ?>  <span class="filter_number hotel_type_1"><?php echo $count_property_type[1]; ?></span></label>
                              </li>

                              <li><a href="#"><input type="checkbox" data-sort-type="hoteltype" data-value="2" <?php echo in_array(2,$filterHotelType)?'checked':'';?>>
                              
                              <label>Resorts in <?php echo $cityname; ?>  <span class="filter_number hotel_type_2"><?php echo $count_property_type[2]; ?></span></label></a></li>

                              <li><a href="#"><input type="checkbox" data-sort-type="hoteltype" data-value="3" <?php echo in_array(3,$filterHotelType)?'checked':'';?>>
                              
                              <label>Apartments in <?php echo $cityname; ?>  <span class="filter_number hotel_type_3"><?php echo $count_property_type[3]; ?></span></label></a></li>

                              <li><a href="#"><input type="checkbox" data-sort-type="hoteltype" data-value="4" <?php echo in_array(4,$filterHotelType)?'checked':'';?>>
                              
                              <label>Villas in <?php echo $cityname; ?>  <span class="filter_number hotel_type_4"><?php echo $count_property_type[4]; ?></span></label></a></li>

                              <li><a href="#"><input type="checkbox" data-sort-type="hoteltype" data-value="5" <?php echo in_array(5,$filterHotelType)?'checked':'';?>>
                              
                              <label>Homestays in <?php echo $cityname; ?>  <span class="filter_number hotel_type_5"><?php echo $count_property_type[5]; ?></span></label></a></li>

                              <li><a href="#"><input type="checkbox" data-sort-type="hoteltype" data-value="6" <?php echo in_array(6,$filterHotelType)?'checked':'';?>>
                              
                              <label>Dormitorys in <?php echo $cityname; ?> <span class="filter_number hotel_type_6"><?php echo $count_property_type[6]; ?></span></label></a></li>

                              <li><a href="#"><input type="checkbox" data-sort-type="hoteltype" data-value="7" <?php echo in_array(7,$filterHotelType)?'checked':'';?>>
                              
                              <label>Guest Houses in <?php echo $cityname; ?> <span class="filter_number hotel_type_7"><?php echo $count_property_type[7]; ?></span></label></a></li>
                          </ul>
                      </div>
                      </div>
                  </li><hr>

	                <li>
                      <div class="dropdown col-xs-12 ">
                           <h2>Price </h2>
                           <Span class="show_atag_hotel"   href="#hotelPrice" data-toggle="collapse" >Show</Span>
                <div id="hotelPrice" class="collapse">
                          <ul class="dropdown-menus new_drop_menu ">
                
                            <li><a href="#">
                            <input type="checkbox" data-sort-type="price" data-value="999" <?php echo in_array('999',$filterPriceForCheckBox)?'checked':'';?>>
                            
                            <label> Upto <i class="fa fa-rupee"></i> 999 <span class="filter_number price_range_0_999"><?php echo $count_price['0-999']?></span> </label></a></li>

                              <li><a href="#"><input type="checkbox" data-sort-type="price" data-value="1000#3000" <?php echo in_array('1000#3000',$filterPriceForCheckBox)?'checked':'';?>>
                              <label> <i class="fa fa-rupee"></i> 1000 to <i class="fa fa-rupee"></i> 3000 <span class="filter_number price_range_1000_3000"><?php echo $count_price['1000-3000']?></span></label></a></li>

                              <li><a href="#"><input type="checkbox" data-sort-type="price" data-value="3000#5000" <?php echo in_array('3000#5000',$filterPriceForCheckBox)?'checked':'';?>><label> <i class="fa fa-rupee"></i> 3000 to <i class="fa fa-rupee"></i> 5000 <span class="filter_number price_range_3000_5000"><?php echo $count_price['3000-5000']?></span></label></a></li>

                              <li><a href="#"><input type="checkbox" data-sort-type="price" data-value="5000#10000" <?php echo in_array('5000#10000',$filterPriceForCheckBox)?'checked':'';?>><label> <i class="fa fa-rupee"></i> 5000 to <i class="fa fa-rupee"></i> 10000 <span class="filter_number price_range_5000_10000"><?php echo $count_price['5000-10000']?></span></label></a></li>
                          </ul>
                      </div>
                      </div>
                  </li>
	                


	               
	               <li><hr>
                      <div class="dropdown col-xs-12 ">
             <h2>Hotels In <?php echo $cityname; ?> By Star Rating </h2>
             	<Span class="show_atag_hotel"   href="#hotelStar" data-toggle="collapse" >Show</Span>
              <div id="hotelStar" class="collapse">
                          <ul class="dropdown-menus new_drop_menu ">
                          

                              <li><a href="#"><input  <?php echo (isset($star)&&$star==1)?'checked':'';?> type="checkbox"  id="1_star" data-sort-type="rating" data-value="1"  <?php echo in_array('1',$filterRating)?'checked':'';?>>
                            <label for="1_star">
                               <!-- <i class="fa fa-star-o" aria-hidden="true"></i> -->
                              <span><i class="material-icons">&#xE838;</i></span>
                              <span class="filter_number star_rating_1"><?php echo $count_star['1'];?></span>

                            </label></a></li>
                            
                            <li><a href="#"><input <?php echo (isset($star)&&$star==2)?'checked':'';?>  type="checkbox" id="2_star" data-sort-type="rating" data-value="2" <?php echo in_array('2',$filterRating)?'checked':'';?>>
                            <label for="2_star">
                            <span><i class="material-icons">&#xE838;</i></span>
                            <span><i class="material-icons">&#xE838;</i></span>
                              <span class="filter_number star_rating_2"><?php echo $count_star['2'];?></span>
                            </label></a></li>

                            <li><a href="#"><input <?php echo (isset($star)&&($star==3))?'checked':'';?>  type="checkbox" id="3_star" data-sort-type="rating" data-value="3" <?php echo in_array('3',$filterRating)?'checked':'';?>>
                            <label for="3_star">
                            <span><i class="material-icons">&#xE838;</i></span>
                             <span><i class="material-icons">&#xE838;</i></span>
                              <span><i class="material-icons">&#xE838;</i></span>
                              <span class="filter_number star_rating_3"><?php echo $count_star['3'];?></span>
                            </label></a></li>

                            <!--<li class="divider"></li>-->
                            <li><a href="#"><input <?php echo (isset($star)&&$star==4)?'checked':'';?>  type="checkbox" id="4_star" data-sort-type="rating" data-value="4" <?php echo in_array('4',$filterRating)?'checked':'';?>>
                            <label for="4_star">
                             <span><i class="material-icons">&#xE838;</i></span>
                              <span><i class="material-icons">&#xE838;</i></span>
                               <span><i class="material-icons">&#xE838;</i></span>
                                <span><i class="material-icons">&#xE838;</i></span>
                              <span class="filter_number star_rating_4"><?php echo $count_star['4'];?></span>
                            </label></a></li>

                            <li><a href="#"><input <?php echo (isset($star)&&$star==5)?'checked':'';?>  type="checkbox" id="5_star" data-sort-type="rating" data-value="5" <?php echo in_array(5,$filterRating)?'checked':'';?>>
                            <label for="5_star">
                             <span><i class="material-icons">&#xE838;</i></span>
                              <span><i class="material-icons">&#xE838;</i></span>
                               <span><i class="material-icons">&#xE838;</i></span>
                                <span><i class="material-icons">&#xE838;</i></span>
                                 <span><i class="material-icons">&#xE838;</i></span>
                              <span class="filter_number star_rating_5"><?php echo $count_star['5'];?></span>
                            </label></a></li>

                          </ul>
                      </div>
                      </div>
                  </li><hr>
	                


 					
                      </article>
                  
	            </ul>






	             <ul class="dropdown-menus ">
          <li class="widget widget-sidebar">
          <article class="refine-search-results byt_search_widget BookYourTravel_Search_Widget">
                      <div class="dropdown col-xs-12">
                         

                          <h2>Popular Areas In <?php echo $cityname; ?> </h2>
                          	<Span class="show_atag_hotel"    href="#popularareas" data-toggle="collapse" >Show</Span>
              
                         <hr >
                         <div class="manuals_overflow" >
              <div id="popularareas" class="collapse" >

                        <?php $LandmarkList = $database->query("SELECT * from ps_landmark where selCityId='".$city_id."' and status=0 and action='' order by txtLandmark ASC")->fetchAll(PDO::FETCH_ASSOC);
                           $land='';
                if(isset($LandmarkList) && !empty($LandmarkList)){
                  foreach($LandmarkList as $LandmarkK => $Landmarkvalue){
                    $checked=(isset($_GET['landmark'])&&implode(' ',explode('-',$_GET['landmark']))==strtolower($Landmarkvalue['txtLandmark']))?'checked':'';
                   
                    $land.='<li data-id="id-'.$Landmarkvalue['id_landmark'].'"><a href="#"><input '.$checked.' type="checkbox" data-sort-type="landmark" data-value="'.$Landmarkvalue['id_landmark'].'"><label>'.$Landmarkvalue['txtLandmark'].'</label></a></li>';
                    if($checked=='checked')
                    {
                      $locatio_address='<li id="data-'.$Landmarkvalue['id_landmark'].'"><a href="#" data-value="'.$Landmarkvalue['id_landmark'].'"><label>'.$Landmarkvalue['txtLandmark'].'</label><i class="fa fa-close"></i></a>';
                    }
                  }
                }
                echo $land;
        ?>   
             
                      </div>
                    </div>
                      </div>
                    </article>
                  </li>
                </ul>
                      
        <ul>
          <li class="widget widget-sidebar">
          <article class="refine-search-results byt_search_widget BookYourTravel_Search_Widget">     
                  <div class="dropdown col-xs-12">
                         
                         <h2>Nearest Airports In <?php echo $cityname; ?> </h2>
                         <Span class="show_atag_hotel"    href="#near_airport" data-toggle="collapse" >Show</Span>
             
                         <hr >
                        <div id="near_airport" class="collapse">
                          <ul class="dropdown-menus new_drop_menu" style="line-height: 15px;" >
                              <li><a href="#"><label>Pondicherry Airport, Destination</label></a></li>
                              <li><a href="#"><label>Neyveli Airport, Destination</label></a></li>
                              <li><a href="#"><label></label></a></li>
                          </ul>
                      </div>
                   </div>
                 </article>
               </li>
             </ul>
          <ul>
          <li class="widget widget-sidebar">
          <article class="refine-search-results byt_search_widget BookYourTravel_Search_Widget">     
                  <div class="dropdown col-xs-12">
                        
                       
                          <h2>Top Flights To <?php echo $cityname; ?> </h2>
                          	 <Span class="show_atag_hotel"    href="#top_flight" data-toggle="collapse" >Show</Span>
             
                         <hr >
                        <div id="top_flight" class="collapse">
                          <ul class="dropdown-menus new_drop_menu" style="line-height: 15px;" >
                              <li><a href="#"><label>Bombay To Pondicherry Flight</label></a></li>
                              <li><a href="#"><label>New Delhi To Pondicherry Flight</label></a></li>
                              <li><a href="#"><label>Bangalore To Pondicherry Flight</label></a></li>
                          </ul>
                      </div>
                   </div>
          </article>
          </li>
          </ul>
        <ul>
          <li class="widget widget-sidebar">
          <article class="refine-search-results byt_search_widget BookYourTravel_Search_Widget">     
                  <div class="dropdown col-xs-12">
                        
                         

                         <h2>Top Flights From <?php echo $cityname; ?> </h2>
                         	</a> <Span class="show_atag_hotel"    href="#from_flight" data-toggle="collapse" >Show</Span>
             
                         <hr >
                        <div id="from_flight" class="collapse">
                          <ul class="dropdown-menus new_drop_menu" style="line-height: 15px;" >
                              <li><a href="#"><label>Pondicherry To Bombay Flight</label></a></li>
                              <li><a href="#"><label>Pondicherry To New Delhi Flight</label></a></li>
                              <li><a href="#"><label>Pondicherry To Bangalore Flight</label></a></li>
                          </ul>
                      </div>
                   </div>
                 </article>
               </li>
            </ul>

        <ul>
          <li class="widget widget-sidebar">
          <article class="refine-search-results byt_search_widget BookYourTravel_Search_Widget">     
                  <div class="dropdown col-xs-12">
                         
                        


                        <h2>Top Buses To <?php echo $cityname; ?> </h2>
                        	<Span class="show_atag_hotel"    href="#bus_to" data-toggle="collapse" >Show</Span>
             
                         <hr >
                        <div id="bus_to" class="collapse">
                          <ul class="dropdown-menus new_drop_menu" style="line-height: 15px;" >
                             <?php 
                           $de_list='';
                           foreach($list as $keyD=>$valueD)
                           {
                            $de_list.="<li><a href='#'><label>".ucwords($cityname)." To ".ucwords($valueD['name'])." Bus</label></a></li>";
                           } 
                           echo $de_list;?>
                          </ul>
                      </div>
                   </div>
                 </article>
               </li>
            </ul>
        <ul>
          <li class="widget widget-sidebar">
          <article class="refine-search-results byt_search_widget BookYourTravel_Search_Widget">     
                  <div class="dropdown col-xs-12">
                        
                         

                        <h2>Top Buses From <?php echo $cityname; ?> </h2>
                        	<Span class="show_atag_hotel"    href="#bus_from" data-toggle="collapse" >Show</Span>
             
                         <hr >
                        <div id="bus_from" class="collapse">
                          <ul class="dropdown-menus new_drop_menu" style="line-height: 15px;" >
                              <?php 
                           $de_list='';
                           foreach($list as $keyD=>$valueD)
                           {
                            $de_list.="<li><a href='#'><label>".ucwords($valueD['name'])." To ".ucwords($cityname)." Bus</label></a></li>";
                           } 
                           echo $de_list;?>
                          </ul>
                      </div>
                   </div>
                 </article>
               </li>
            </ul>

        <ul>
          <li class="widget widget-sidebar">
          <article class="refine-search-results byt_search_widget BookYourTravel_Search_Widget">
                   <div class="dropdown col-xs-12">
                        
                       
                       <h2><?php echo $city_name; ?>  Map </h2>
                       	<Span class="show_atag_hotel"    href="#map_current" data-toggle="collapse" >Show</Span>
             
                         <hr >
                        <div id="map_current" class="collapse">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m28!1m12!1m3!1d62461.964325081804!2d79.77945244465539!3d11.913941622005183!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!4m13!3e6!4m5!1s0x3a5361ab8e49cfcf%3A0xcc6bd326d2f0b04e!2sPondicherry%2C+Puducherry!3m2!1d11.913859799999999!2d79.8144722!4m5!1s0x3a5361ab8e49cfcf%3A0xcc6bd326d2f0b04e!2sPuducherry!3m2!1d11.913859799999999!2d79.8144722!5e0!3m2!1sen!2sin!4v1517032683954" width="600" height="450" frameborder="0" style="border:0;width:100%;height: 300px;" allowfullscreen></iframe>
                         
                      </div>
                   </div>
                 </article>
                 </li>
              </ul>
	      


</aside>

<?php 
} ?>








 <section class="three-fourth">

  
       <div class="dropdown col-sm-12" style="padding: 15px 0;margin-bottom: 10px; box-shadow: 0 0 30px rgba(0, 0, 0, 0.1)!important;" >
    
 <div  class="col-sm-5	" >
    <div style="border: 1px solid #ccc;  position: relative;">

      <input style="color:#555!important;font-weight: normal;"   type="button" value ="Select by Address, Landmark and Location " class="searchforadd">
      <span class="manual_caret_searhc"><i  class="fa fa-search" aria-hidden="true"></i> 
        <i  class="fa fa-caret-down close_div" aria-hidden="true"></i></span>


     <ul class="dropdown-menu dropdown-menus new_drop_menu dropmenusval  "  >

       <?php $LandmarkList = $database->query("SELECT * from ps_landmark where selCityId='".$city_id."' and status=0 and action='' order by txtLandmark ASC")->fetchAll(PDO::FETCH_ASSOC);
                           $land='';$locatio_address='';
                if(isset($LandmarkList) && !empty($LandmarkList)){
                  foreach($LandmarkList as $LandmarkK => $Landmarkvalue){

                    $checked=(isset($_GET['landmark'])&&implode(' ',explode('-',$_GET['landmark']))==strtolower($Landmarkvalue['txtLandmark']))?'checked':'';
                    $checked=(in_array($Landmarkvalue['id_landmark'],$filterLandmarkType)?'checked':'');
                   
                    $land.='<li data-id="id-'.$Landmarkvalue['id_landmark'].'"><a href="#"><input '.$checked.' type="checkbox" data-sort-type="landmark" data-value="'.$Landmarkvalue['id_landmark'].'"><label>'.$Landmarkvalue['txtLandmark'].'</label></a></li>';
                    if($checked=='checked')
                    {
                      $locatio_address.='<li id="data-'.$Landmarkvalue['id_landmark'].'"><a href="#" data-value="'.$Landmarkvalue['id_landmark'].'"><label>'.$Landmarkvalue['txtLandmark'].'</label><i class="fa fa-close"></i></a></li>';
                    }
                  }
                }
                echo $land;
        ?>   
             
   </ul>  
   </div>  
    </div>
    <div  style="padding: 0 15px ">
    	
	  <div class="remove_landmarks" > <?php echo $locatio_address;?> </div>   			


    </div>

                      </div>




                
                  

	

            <div class="sort-by col-sm-12">
               


<div class="clearfix"></div>

<ul class="sorting_ul">
 	<li style="font-weight: bold;">Sort By</li>
 	
 	<li>Price<span class="caretidv"><i class="fa fa-caret-down price_sort" style="<?php echo ($price_sort==1)?'color:red':''?>" onclick="sortfor('price_sort','fa-caret-down',1);" aria-hidden="true"></i><i style="<?php echo  ($price_sort==0)?'color:red':''?>" class="fa fa-caret-up price_sort" aria-hidden="true" onclick="sortfor('price_sort','fa-caret-up',0);"></i></span></li>
 	<li>Stars <span class="caretidv"><i class="fa fa-caret-down star_sort"  style="<?php echo ($star_sort==1)?'color:red':''?>" onclick="sortfor('star_sort','fa-caret-down',1);" aria-hidden="true"></i><i  style="<?php echo ($star_sort==0)?'color:red':''?>" class="fa fa-caret-up star_sort" aria-hidden="true" onclick="sortfor('star_sort','fa-caret-up',0);"></i></span></li>
 	<!--<li>Rating <span class="caretidv"><i class="fa fa-caret-down rating_sort"  style="<?php echo ($rating_sort==1)?'color:red':''?>" onclick="sortfor('rating_sort','fa-caret-down',1);" aria-hidden="true"></i><i  style="<?php echo ($rating_sort==0)?'color:red':''?>" class="fa fa-caret-up rating_sort" aria-hidden="true" onclick="sortfor('rating_sort','fa-caret-up',0);"></i></span></li>-->
 	
 	</ul>

<input type="hidden" id="price_sort" value="<?php echo $price_sort;?>">
<input type="hidden" id="star_sort" value="<?php echo $star_sort;?>">
<!--<input type="hidden" id="rating_sort" value="<?php echo $rating_sort;?>">-->




               
              

                    <ul style="float:right;" class="view-type">
  
                  <li class="grid-view"><a href="#" title="grid view">grid view</a></li>
                  <li class="list-view active"><a href="#" title="list view">list view</a></li>
               </ul>
            </div>

<div class="deals">
               <!--deal-->
               <div class="">
 <?php $count1=0;
  $hotelListArr_for_pagin=array_chunk($newhotelListArr, $perPage->perpage, true);
      if(isset($newhotelListArr) && !empty($newhotelListArr)){
        $_SESSION['hotelListArr']=$newhotelListArr;

        foreach($hotelListArr_for_pagin[$rowcount-1] as $id_property => $hotels){



       
if($count1==3)
{
	echo '<div class="clearfix"></div>';
$count1=0;
}
$count1++;
          //echo '<pre>';print_r($hotels);echo '</pre>';
    ?>
     


                  <!--accommodation item-->
                  <article class="accommodation_item full-width">
                     <div>
                        <figure class="assuerd_div">
                           <a href="" title="Villa Maria">
                           <!--<img src="<?php echo (isset($hotels['photo_gallery']) && !empty($hotels['photo_gallery']) ? $hotels['photo_gallery'] : $root_dir.'hotels/search/images/img1.jpg'); ?>" alt="Villa Maria" />-->

                           <span class="ribbobn_assured "><i class="material-icons doubletick">done_all</i>
	              Klitestays Assured</span>
                           <a class="fancybox" href="<?php echo (isset($hotels['photo_gallery']) && !empty($hotels['photo_gallery']) ? $hotels['photo_gallery'] : $root_dir.'hotels/search/images/img1.jpg'); ?>">
	        <img class="hotelimage " src="<?php echo (isset($hotels['photo_gallery']) && !empty($hotels['photo_gallery']) ? $hotels['photo_gallery'] : $root_dir.'hotels/search/images/img1.jpg'); ?>"></a>
                           </a>
                           </a>
                        </figure>
                        <div class="details">
                           <h3>
                              <?php echo $hotels['txtPropertyName']; ?>
                              <span class="stars_hotel_star ">
                             <?php
                              for($i=1;$i<=$hotels['selStarRating'];$i++)
                              {
                              	?>
                              	<i class="material-icons">&#xE838;</i>
                              	<?php
                              }
                              ?>
                              </span> 
                             
                           </h3>


                            

                               <span class='address map_marker_icon'>

                              <i class="fa fa-map-marker" aria-hidden="true"></i><span><?php echo $hotels['selLocation']; ?></span>
                              <!-- <?php echo $hotels['txtAddress1']; ?> --></span>

                              <span class='rating'>
                              	
                              	<span class="full_ratingval">

	  <img src="https://klitestays.com/favicon.png">

	  <span class="full_rativnval"><span class="rating_numbers">4.0 / </span>5</span>    
	  </span>	
	  <br>
	  <a style="margin-top:10px;cursor: pointer;display: block;color:#ccc;"> <span style="color:#bb0f0f;">100</span> Reviews  <i class="fa fa-angle-right" aria-hidden="true"></i></a>
                              </span>	
                              		
                           <div class="price">
                              Price from 
                             <?php if($hotels['offer_price']!=$hotels['price'])
                             { ?>
                            <span class="price_span">
                              <em>
                              
                              <span class="price_strike_span" ><i class="fa fa-inr" aria-hidden="true"></i><?php echo $hotels['price']?></span>
                              </em>

                              <em class="price_under">
                              <span class="curr"><i class="fa fa-inr" aria-hidden="true"></i></span>
                              <span class="amount"><?php echo $hotels['offer_price']?></span>
                              </em>
                          </span>
                          <?php   }else{ ?>
						<span class="price_span">
                             
                              <em class="price_under">
                              <span class="curr"><i class="fa fa-inr" aria-hidden="true"></i></span>
                              <span class="amount"><?php echo $hotels['price']?></span>
                              </em>
                          </span>
                           <?php  } ?>


                           </div>
                           <div class='description clearfix'>

                           	
                        <p class="discription_overflow"><?php //substr(("fdiusgodfug"),0,100)
                              $pag_ct=strip_tags($hotels['txtPropertyDescription']);


                               if($pag_ct!=''){ echo $pag_ct; ?></p>


                           <?php   if(strlen($pag_ct)>=150){?>
                            <a  class="more_infos" >More info</a>
                            <?php } }?>
                           
                           <div  class=" icons_all">
	              <a href="#" data-toggle="tooltip" title="Room Service">
               <i class="material-icons"<?php if(!empty($hotels['amenities'])){if(!in_array('room_service',$hotels['amenities']) && !in_array('room_service_24_hours',$hotels['amenities'])) echo 'style="color:#c8c8c8;cursor:not-allowed;"';} ?>>room_service</i>
               </a>
               <a href="#" data-toggle="tooltip" title="Gym / Spa">
               <i class="material-icons"<?php if(!empty($hotels['amenities'])){if(!in_array('exercise_gym',$hotels['amenities']) && !in_array('spa',$hotels['amenities'])) echo 'style="color:#c8c8c8;cursor:not-allowed;"';} ?>>fitness_center</i>
               </a>

               <a href="#" data-toggle="tooltip" title="Swimming Pool">
               <i class="material-icons"<?php if(!empty($hotels['amenities'])){ if(!in_array('swimming_pool',$hotels['amenities'])) echo 'style="color:#c8c8c8;cursor:not-allowed;"';} ?>>pool</i>
               </a>

               <a href="#" data-toggle="tooltip" title="Wi-fi">
               <i class="fa fa-wifi" style="font-size: 22px;position: relative; bottom: 3px;" aria-hidden="true"<?php if(!empty($hotels['amenities'])){if(!in_array('internet_access_in_rooms',$hotels['amenities'])) echo 'style="color:#c8c8c8;cursor:not-allowed;font-size: 22px;position: relative; bottom: 3px;"'; }?>></i>
               </a>
               <a href="#" data-toggle="tooltip" title="Restaurant">
               <i class="material-icons"<?php if(!empty($hotels['amenities'])){if(!in_array('restaurant',$hotels['amenities'])) echo 'style="color:#c8c8c8;cursor:not-allowed;"';} ?>>restaurant</i>
               </a>
               <a href="#" data-toggle="tooltip" title="Internet Access">
               <i class="material-icons"<?php if(!empty($hotels['amenities'])){if(!in_array('internet',$hotels['amenities']))  echo 'style="color:#c8c8c8;cursor:not-allowed;"';} ?>>desktop_windows</i>
               </a>
	            </div>
                           </div>
                           <div class='actions'>
                           	<form class="book-hotel-form" name="hotel_book" action="<?php echo $root_dir?>hotels/search/book.php">
	                  <input type="Hidden" name="id_property" value="<?php echo $id_property; ?>">
	                  <input type="Hidden" name="goingto" value="<?php echo $city_name; ?>">
	                  <input type="Hidden" name="to_hidden" value="<?php echo $city_id; ?>">
	                  <input type="Hidden" name="hotel_month" value="<?php echo $check_in; ?>">
	                  <input type="Hidden" name="hotel_month1" value="<?php echo $check_out; ?>">
					  <?php echo (isset($guestCntHidden) ? $guestCntHidden : ''); ?>
					  <input type="hidden" class="child_age_json" name="child_age_json" value="">
	                 <!--  <input type="submit" class="btn btn-default diagonal" name="hotel_book" value="Book Now"> -->
	                 <button type="submit" class=" btn btnBook1 select_room_btn" name="hotel_book" value="Book Now">Select Room</button>
	               </form>
                        </div>
                     </div>
                 </div>
                  </article>
         
      
<?php }
			}else{ ?>
					 <div  class="col-md-12 inner_first_content1"><div class=" text-center"><label>No Results Found</label></div></div>
				<?php } ?>

    </div>  </div>



<div class="clearfix"></div>
<div class="page-content">
	<div id="pagination">
	<?php
		$perpageresult = $perPage->getAllPageLinks(count($newhotelListArr), $rowcount);	
		echo $perpageresult;

	?>
	</div>
	<input type="hidden" id="rowcount" value="<?php echo $rowcount?>">
</div>
</section> 
             
           

	<script>

		$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip({ tooltipClass:"tooltip_custom"}
       

      );
  
});
</script>


     	  
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
	function enable_forsearch()
	{
	  $(".search_tag").css("display","inline");
	  $("#to1").removeAttr("disabled");
	  $("#hotels_month").removeAttr("disabled");
	  $("#hotels_month1").removeAttr("disabled");
	  $(".modify_tag").css("display","none");
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
	function check_loc(loc_id)
	{

	  $('#data-'+loc_id).remove();
	  $('input[data-value='+loc_id+']').prop('checked',false);
	  $('input[data-value='+loc_id+']').trigger('click');
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
		$('.dropdown-menus li input[type=checkbox]').on('click', function () {
			$(this).parent().find("a").trigger('click');
		});
		$('.dropdown-menus li a').on('click', function (event) {
			var incJs = 1; var queryJSON = [];
			var queryArr = {};
			var guestJson = '<?php echo $_GET['guest']; ?>';

			if(event.originalEvent){
				if($(this).parent().find('input:checkbox:first').prop("checked") == true)
					$(this).parent().find('input:checkbox:first').prop('checked', false)
				else
					$(this).parent().find('input:checkbox').prop('checked', true);
			}

			$('.dropdown-menus input:checkbox:checked').each(function(){
				var keyFil = $(this).attr('data-sort-type');
				var value = $(this).attr('data-value');
				var object = {}; 
				object[keyFil] = value;
				queryJSON.push(object);
				incJs++;
			});
			var html='';
			
			$.each( queryJSON, function( key, value ) {
			  if(value.landmark)
			  {


			   var text=$('[data-id=id-'+value.landmark +'] a label').html();
			    html+='<li onclick="check_loc('+value.landmark+')" id="data-'+value.landmark+'" class="add_values_select"><a href="#" data-value="'+value.landmark+'"><label>'+text+'</label><i class="fa fa-times"></i></a> </li>';
			   
			  }
			});
			 $(".remove_landmarks").html(html);
			queryArr['goingto'] = $('#goingto').val();
			queryArr['to_hidden'] = $('#to_hidden').val();
			queryArr['hotel_month'] = $('#hotel_month').val();
			queryArr['hotel_month1'] = $('#hotel_month1').val();
		queryArr['price_sort'] = $('#price_sort').val();
		queryArr['star_sort'] = $('#star_sort').val();
		//queryArr['rating_star'] = $('#rating_star').val();
		queryArr['ajax-from'] = '<?php echo $ajax_from;?>';
			queryArr['guestJson'] = guestJson;
			$.ajax({
				type: "POST",
				url: "<?php echo $root_dir;?>hotels/search/index-ajax.php",
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
		$('.remove_landmarks li a').on('click', function (event) {
	        var value = $(this).attr('data-value');
	       // console.log(value);
	        $('[data-id=id-'+value+']').html();
	        $('[data-value='+value+']').prop('checked',false).trigger( "click" );
	        //console.log(value);
	   		// $(".dropdown-menus li a").trigger( "click" );       
        
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
	
	function sortfor(class_id, for_class, value) {
		$("#"+class_id).val(value);

		$("."+for_class+"."+class_id).css("color","red");
		var incJs = 1; var queryJSON = [];
		var queryArr = {};
		var guestJson = '<?php echo $_GET['guest']; ?>';
		$('.dropdown-menus input:checkbox:checked').each(function(){
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
		queryArr['price_sort'] = $('#price_sort').val();
		queryArr['star_sort'] = $('#star_sort').val();
		//queryArr['rating_star'] = $('#rating_star').val();
		queryArr['ajax-from'] = '<?php echo $ajax_from;?>';
		queryArr['guestJson'] = guestJson;

		$.ajax({
			type: "POST",
			url: "<?php echo $root_dir;?>hotels/search/index-ajax.php",
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
	//user is "finished typing," do something
	function doneTyping () {
		console.log($('#filterHotelName').val());
		var incJs = 1; var queryJSON = [];
		var queryArr = {};
		var guestJson = '<?php echo $_GET['guest']; ?>';
		$('.dropdown-menus input:checkbox:checked').each(function(){
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

		$.ajax({
			type: "POST",
			url: "<?php echo $root_dir;?>hotels/search/index-ajax.php",
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

<link href="https://fonts.googleapis.com/icon?family=Material+Icons"
	      rel="stylesheet">

	 
	  


<script type="text/javascript">
function enable_forsearch()
{
  $(".hotel_search").css("display","block");
  $(".display_lables").css("display","none");
  
}
	  $(document).ready(function() {
	    $(".fancybox").fancybox({	

	    	openEffect	: 'elastic',
	    	closeEffect	: 'elastic'});
	  });

	    $(document).ready(function() {

	    	$("#show_all_btn").click(function()


	    	{

	    		$("#landmarks  li").css("display","block");
	    		$(this).css("display","none");


	    	});
	    	$("#show_all_btn1").click(function()


	    	{$("#Locations  li").css("display","block");
	    		$(this).css("display","none");


	    	});
	    	$("#show_all_btn2").click(function()


	    	{$("#Attractions  li").css("display","block");
	    		$(this).css("display","none");


	    	});




	    	  });
	  
	</script>



  <script>$(document).ready(function() {


   $('.search_bylocs,.close_div,.searchforadd').click(function() {
   $(".dropmenusval").slideToggle("slow");
  $( ".close_div" ).toggleClass( "fa-caret-up" );
  $('.searchforadd').focus();

  });


  $(".button_modibook").click(function(){
          $(".button_modibook i").toggleClass("fa-minus");
      });




   });</script>  


  <script type="text/javascript">

    $( document ).ready(function() {

  // $(".hotelde_inner").stick_in_parent({
  //     offset_top: 10
  // });



  $('.hotelde_inner')
            .theiaStickySidebar({
              
            });

   });


 window.itemClass = "one-third";
 $('.grid-view').click(function(e) {
        var currentClass = $(".three-fourth article").attr("class");
        if (typeof currentClass != 'undefined' && currentClass.length > 0) {
          currentClass = currentClass.replace('last', '');
          currentClass = currentClass.replace('full-width', window.itemClass);
          $(".three-fourth article").attr("class", currentClass);
          $(".view-type li").removeClass("active");
          $(this).addClass("active");
          
          staysinn_script.resizeFluidItems();
        }
        e.preventDefault();
      });
      
      $('.list-view').click(function(e) {
        var currentClass = $(".three-fourth article").attr("class");
        if (typeof currentClass != 'undefined' && currentClass.length > 0) {
          currentClass = currentClass.replace('last', '');
          currentClass = currentClass.replace(window.itemClass, 'full-width');
          $(".three-fourth article").attr("class", currentClass);
          $(".view-type li").removeClass("active");
          $(this).addClass("active");
        }
        e.preventDefault();
      });
  </script>

  <script type="text/javascript">
$(document).ready(function()
{


  //scripts for refine search results for hotels/search/index.php file

  $(".toggle_filters").click(function()
{
$("#secondary").slideToggle("slow");

});


   $(window).resize(function() {
        
        if($(window).width() >= 768) {
            
          $("#secondary").css("display","block");
           $(".dropdown .collapse").addClass("in");
           $(".dropdown .collapse").css("height","auto");
        } else {
            
            $("#secondary").css("display","none");
             $(".dropdown .collapse").removeClass("in");
 }
    }).resize(); 

 $(".show_hide_type" ).click(function()
  {
  var tex=$(".show_atag_hotel").text() == "Show"?"Hide":"Show";
  $(".show_atag_hotel").text(tex);


  });





  $(".more_infos" ).click(function()
    {
       $(this).css("display","none");
    $(this).prev(".discription_overflow ").toggleClass("discription_overflow_toggle" );



    });

});
$(".count_holt.red").text("<?php echo count($newhotelListArr);?>");
</script>

   