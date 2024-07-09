<?php 

$currentpage="hotelsearch";

include("../../include/header.php");
require_once("pagination.class.php");

$perPage = new PerPage();


	         //include '../config.php';
	//include '../include/database/config.php';
$_SESSION['2ndpage']="https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	error_reporting(0);
	$city_name = $_GET['goingto'];
	$city_id = $_GET['to_hidden'];
	$check_in = $_GET['checkin'];
	$check_out = $_GET['checkout'];
	$guest=json_decode($_GET['guest'], true);
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
		$countWhere .= ' and rt.selMaxNoOfGuest >= '.($val['adult']+$val['child']);
		$guestCntHidden .= '<input type=\'Hidden\' name="guest['.$key.'][\'adult\']" value=\''.$val['adult'].'\'><input type=\'Hidden\' name="guest['.$key.'][\'child\']" value=\''.$val['child'].'\'>';
			if($key!=1)
			{
				$searchguest.='<div class="pax_container test pax_container_'.$key.'" data="'.$key.'">';
			}else
			{
				$searchguest.='<div class="pax_container pax_container_'.$key.'" data="'.$key.'">';
			}
			
					if($key!=1)
					{
	$searchguest.='<div class="removepax" onclick="removeroom(this)"><i class="fa fa-times" aria-hidden="true"></i></div>';
					}
		$searchguest.='<div class="row"  style="margin-top:0;"><div class="col-xs-3 detail_pax detail_pax_'.$key.'">';
		$searchguest.='<p class="detail_pax_p">Room <span class="roomnumber">'.$key.'</span></p>
					
					</div>
					<div class=" content_pax content_pax_'.$key.'" >
					<div   ><div class="col-xs-4">
					<p class="head_title_room" style="margin-left: -5px;">Adult</p>
					<select class="select1 adultlist adultlist_'.$key.'" onchange="changeadult(this.value,this)">
					<option '.(($val['adult']==1)?"selected":"").' value="1">1</option>
					<option '.(($val['adult']==2)?"selected":"").' value="2">2</option>
					<option '.(($val['adult']==3)?"selected":"").' value="3">3</option>
					</select>
					<input type="hidden" id="oldAdult_'.$key.'" value="2">
					</div>
					<div class="col-xs-4">
					<p class="head_title_room" style="margin-left: -34px;">Childern (5-12 yrs)</p>
					<select class="select1 childlist childlist_'.$key.'" onchange="changechild(this.value,this)">
					<option '.(($val['child']==0)?"selected":"").' value="0" >0</option>
					<option '.(($val['child']==1)?"selected":"").' value="1">1</option>
					<option '.(($val['child']==2)?"selected":"").' value="2">2</option>
					</select><input type="hidden" id="oldChild_'.$key.'" value="0">
					</div>
					</div>
					</div>
					
					</div></div>';
					$roomcount++;

	}

	$guestcount=$countadult+$countchild;
	$text=$key.' Rooms, '.$guestcount.' Guest';
	$land_query='';
	if($locations)
	{

	$land_query=" and l.txtLandmark like '".$locations."'";
	}


$query='select r.selMaxNoOfGuest, r.selRoomCategory,cl.name as country, p.selLandmark,l.txtLandmark,p.offer_percentage,p.selPropertyTypeID, p.txtPropertyDescription, p.txtPropertyName,p.selStarRating,p.txtAddress1,p.txtAddress2,p.photo_gallery,p.selImages,r.id_property,r.id_room,r.period_from,r.period_to,rt.periodic_rateExtraBedAdult,rt.rateExtraBedAdult,rt.periodic_rateExtraBedChildMoreThanFive,rt.periodic_rateExtraBedChildLessThanFive,rt.rateExtraBedChildMoreThanFive,rt.rateExtraBedChildLessThanFive,rt.id_room_type from ps_property p left join ps_room r on(p.id_property = r.id_property and r.status=0) left join ps_landmark l on(l.id_landmark=p.selLandmark and l.status=0 and l.action=\'\') left join ps_room_type rt on(r.id_room = rt.id_room) left join ps_city c on(c.id_city=\''.$city_id.'\') left join ps_country_lang cl on(cl.id_country=c.id_country)  where p.selCityId=\''.$city_id.'\' '.$countWhere.' and p.status=0 and rt.status=0 and p.is_delete!=1'.$land_query;
	$availableRoomsList = $database_hotel->query($query)->fetchAll(PDO::FETCH_ASSOC);
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
				}elseif(count($guest) > $inventoryVal[0][0]){
					unset($availableRoomsList[$incKey]);
					continue 2;
				}else
					$roomVal['inventory'][] = $inventoryVal[0][0];
			}

			$masterRate=$without_masterRate= $monthlyRate = $periodicRate = $roomTypeList = array();
			// get rate
			//echo 'select r.id_room,rt.* from ps_room r left join ps_room_type rt on(r.id_room = rt.id_room) where r.id_room='.$roomVal['id_room'].' and rt.id_room_type='.$roomVal['id_room_type'];

			$roomTypeArr = $database_hotel->query('select r.id_room,rt.* from ps_room r left join ps_room_type rt on(r.id_room = rt.id_room) where r.id_room='.$roomVal['id_room'].' and rt.id_room_type=\''.$roomVal['id_room_type'].'\'')->fetchAll();
			if(isset($roomTypeArr) && !empty($roomTypeArr)){

				foreach($roomTypeArr as $roomTypeKey => $roomTypeVal){
					
					//if((int)$roomTypeVal['is_breakfast'] == 1)
						//$roomVal['is_breakfast'] = 1;
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
				
				$periodicRateList = $database_hotel->query('select * from ps_room_periodic_rate where id_room='.$roomVal['id_room'].' and id_room_type=\''.$roomVal['id_room_type'].'\' and \''.$dateValue.'\' >= period_from and \''.$dateValue.'\' <= period_to')->fetchAll();

				$periodicRate = [];
				if(isset($periodicRateList) && !empty($periodicRateList))
					foreach($periodicRateList as $periodicRateKey => $periodicRateVal){
						
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

				if(isset($periodicRate[date('w',strtotime($dateValue))]) && !empty($periodicRate[date('w',strtotime($dateValue))]))
					{
						$rateValue += $periodicRate[date('w',strtotime($dateValue))];
					}

				else if(isset($masterRate[date('w',strtotime($dateValue))]) && !empty($masterRate[date('w',strtotime($dateValue))]))
					{
						$rateValue += $masterRate[date('w',strtotime($dateValue))];
					}
			}

	//echo $roomVal['id_room'].'_'.$roomVal['id_room_type'].'__'.$rateValue;
			$extraRate = 0;
			if(isset($guest) && !empty($guest)){
				foreach($guest as $guestkey => $guestval){

					if($guestkey>1) $extraRate += $rateValue;
					if($roomVal['selRoomCategory']!=13)
					{


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
				else
				{
					if($guestval['adult'] > $roomVal['selMaxNoOfGuest']){
						$extraAdult = $guestval['adult'] - $roomVal['selMaxNoOfGuest'];
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
	$getHotelConfigValues = $database->query("select name,value from ps_configuration where name = 'ps_hotel_service_tax' or  name = 'ps_hotel_commission' or  name = 'ps_hotel_pay_cgst_2499' or  name = 'ps_hotel_pay_sgst_2499' or  name = 'ps_hotel_pay_cgst_7499' or  name = 'sps_hotel_pay_sgst_7499' or  name = 'ps_hotel_pay_cgst_7500' or  name = 'ps_hotel_pay_sgst_7500'")->fetchAll();
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
				$hotelListArr[$roomVal1['id_property']]['txtAddress1'] = $roomVal1['txtAddress1'];
				$hotelListArr[$roomVal1['id_property']]['offer_percentage'] = $roomVal1['offer_percentage'];
				$hotelListArr[$roomVal1['id_property']]['offer_percent']='';
				$hotelListArr[$roomVal1['id_property']]['txtPropertyName']	= $roomVal1['txtPropertyName'];
				$hotelListArr[$roomVal1['id_property']]['selPropertyTypeID']	= $roomVal1['selPropertyTypeID'];
				$hotelListArr[$roomVal1['id_property']]['txtPropertyDescription']	= $roomVal1['txtPropertyDescription'];
				$hotelListArr[$roomVal1['id_property']]['selStarRating']	= $roomVal1['selStarRating'];
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
				
				/*$tds_amt = $hotelListArr[$roomVal1['id_property']]['price'] * ($ps_hotel_service_tax/100);
				$commission_amt = $hotelListArr[$roomVal1['id_property']]['price'] * ($ps_hotel_commission/100);
				$hotelListArr[$roomVal1['id_property']]['price'] = round(($hotelListArr[$roomVal1['id_property']]['price'] - $commission_amt) + $tds_amt);*/


				
				$hotelListArr[$roomVal1['id_property']]['offer_price']='';

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
	}

	$cityNames = $database->query("SELECT c.id_city,c.name,s.name as stateName from ps_city c left join ps_state s on(s.id_state = c.id_state) where c.status=0 and is_for_hotel=1")->fetchAll();
	if(isset($cityNames) && !empty($cityNames)){
		foreach($cityNames as $cityK => $city){
			$cityNamesArr[$cityK]['id']		= $city['id_city'];
			$cityNamesArr[$cityK]['label']	= $city['name'].', '.$city['stateName'];
		}
	}

	$colors = array_column($hotelListArr, 'selStarRating');
	$count_star = array_count_values($colors);

	$propertytype = array_column($hotelListArr, 'selPropertyTypeID');
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

	?>
	<input type="Hidden" id="goingto" value="<?php echo $city_name; ?>">
		<input type="Hidden" id="to_hidden" value="<?php echo $city_id; ?>">
		<input type="Hidden" id="hotel_month" value="<?php echo $check_in; ?>">
		<input type="Hidden" id="hotel_month1" value="<?php echo $check_out; ?>">
	<div class="main" role="main" id="primary">
   <div class="">

   	<section id="bus_tick" class="section_bg">
		<div class="container">
		<div class="row" style="margin-top:0;">

	    <div class="bus_tickets" >
	        <div class="col-sm-12  col-md-12 col-lg-12 col-xs-12 checkins">
			<form class="hotel_search" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get" id="hotel_search"  style="display: none">
	            <div class="col-xs-12 col-sm-3">
	                <input type="text" class="form-control" required placeholder="City, Area or Hotel Name" id="to1" autocomplete="off" name="goingto" value="<?php echo (isset($city_name) && !empty($city_name) ? $city_name : ''); ?>"  />
					<input type="hidden" id="to_hidden1" name="to_hidden" value="<?php echo (isset($city_id) && !empty($city_id) ? $city_id : ''); ?>">
	            </div>
	            <div class="col-xs-12 col-sm-2 ">
	            	 <i class="fa fa-calendar" aria-hidden="true"></i>
	                <input type="text" class="form-control" placeholder="Select date" id="hotels_month" name="checkin" value="<?php echo (isset($check_in) && !empty($check_in) ? $check_in : ''); ?>" >
	            </div>
	            <div class="col-xs-12 col-sm-2 ">
	            	 <i class="fa fa-calendar" aria-hidden="true"></i>
	                <input type="text" class="form-control" placeholder="Select date" id="hotels_month1" name="checkout" value="<?php echo (isset($check_out) && !empty($check_out) ? $check_out : ''); ?>" >
	            </div>

	            <div class="col-xs-12 col-sm-3 " style="margin-bottom: 10px;">
					<div class="section_room" style="position: relative">
					<input type="hidden" id="guest" name="guest" value="">
					<input type="hidden" id="guestval" value="<?php echo $guestcount?>">
					<input type="hidden" id="roomval" value="<?php echo $key?>">

					<!-- <button  class="btn-custom-collapse" type="button" style=" " data-toggle="collapse"  data-target="#demo"><span class="total_guest_room"><?php echo $text;?></span></button> -->

					<div id="button_add_roo" type="button" style="" data-toggle="collapse" data-target="#demo"><span class="total_guest_room"><?php echo $text;?></span><span style="float: right;"><i class="fa fa-angle-down arrows_addroom" aria-hidden="true" style="position: absolute;top: 10px;right: 15px;"></i></span></div>



                     <div class="collapse room_res2" id="demo" >
					<div class="outer_div_search">
					<div class="inner_demo outer_pax">
					<?php echo $searchguest;?>
					</div>
					<div class="con_for_but"></div>
					<button  class="rooms_in_hotel btn btn-primary" data="<?php echo $key+1; ?>" type="button" onclick="addroom(this)" >+ Add Room</button>
					<span class="done" onclick="ondone()">Done</span>

					</div>
				    </div> 
					<div class="collapse room_res2 "  style="" id="demo" ><!-- 
						<div class="outer_div_search">
                                 <div class="inner_demo outer_pax">
                                 	<?php echo $searchguest;?>
					              </div>
                                    <div  class="col-sm-12 pax_container" data="1">
                                      <div class="row" style="margin-top: 4px;">
                                       <div class="col-sm-3 detail_pax detail_pax_1">
                                          <p class="detail_pax_p" style="font-family: 'Roboto',sans-serif;">Room <span class="roomnumber">1
                                             </span>
                                          </p>
                                          
                                          </div>

                                          <div class="col-sm-8 content_pax content_pax_1" 
                                          style="margin-top: -20px;">
                                          <span id="mesg">only 3 guest allowed</span>
                                            <div class="row">
                                              <div class="col-sm-5 col-xs-6">
                                          <p class="head_title_room" style="font-family: 'Roboto',sans-serif;">Adult</p>
                                         
                                          <select class="adultlist adultlist_1 actives" onchange="changeadult(this.value,this)">
                                            <option value="1">1</option>
                                            <option selected="seletcted" value="2" >2</option>
                                            <option value="3" >3</option>
                                       
                                          </select>
                                          <input type="hidden" id="oldAdult_1" value="2">
                                        </div>
                                         
                                         <div class="col-sm-6 col-xs-6">
                                          <p class="head_title_room" style="margin-left: -34px;">Childern (5-12 yrs)</p>
                                          
                                          <select class="childlist childlist_1 actives" onchange="changechild(this.value,this)">
                                            <option selected="seletcted" value="0">0</option>
                                            <option value="1" >1</option>
                                            <option value="2">2</option>
                                          
                                          </select>
                                          <input type="hidden" id="oldChild_1" value="0">
                                         </div>
                                        </div>
                                      </div>
                                       
                                       
                                      

                                       <div class="clearfix"></div>
                                     </div>
                                    </div>
                                 </div>
                                 <div class="con_for_but"></div>
                                 <button class="rooms_in_hotel " data="2" type="button" onclick="addroom(this)" style="font-family: 'Roboto',sans-serif;background: transparent!important;color: #dd3236!important;border:none!important;margin-top: -10px;padding: 5px 10px!important;font-size: 11px!important;font-weight: 500!important;" >+ Add Room</button>
                                 <span class="done" onclick="ondone()" style="color:#ad1f1f; font-family: 'Roboto',sans-serif;margin-right: 2px;">Done</span>
                               --></div>
					</div>	
					
	            </div>
	            
	                        <div class="col-sm-2">
	               
						<input type="hidden" class="child_age_json" name="child_age_json" value="">
	                   
	                    <input type="submit" value="Search" class="btn-cus-submit btn modify_tag btn modify_searh" />

	                
	            </div>

	           </form>
  			<div class="display_lables">
              <div class="col-md-3 label_cont"><?php echo (isset($city_name) && !empty($city_name) ? $city_name : ''); ?></div>
              <div class="col-md-2 label_cont"><?php echo (isset($check_in) && !empty($check_in) ? date('d D, M Y',strtotime($check_in)) : ''); ?></div>
              <div class="col-md-2 label_cont"><?php echo (isset($check_out) && !empty($check_out) ? date('d D, M Y',strtotime($check_out)) : ''); ?></div>
              <div class="col-md-3 label_cont"><?php echo $text;?></div>

               <div class="col-md-2 label_cont"><button type="button" class="btn-cus-submit modify_tag btn modify_before_Act" style="position: relative;" onclick="enable_forsearch()" ><span  style="text-decoration: none!important;position: absolute;left: 0">+</span>  Modify Search </button></div>
               
            </div>
	        </div>
	    </div>

	    </div>
	    </div>	
	    </section>
      <!--main content--><!--breadcrumbs-->
      
      <!--//breadcrumbs-->

<div class="container">
	<div class="div_top_inde"></div>




	<nav role="navigation" class="breadcrumbs" >
         <ul style="border:none;">
         <li>
           <span style="padding: 9px 0px;" >
                <a class="values_cityname"  href="<?php echo $root_dir?>" src="">Hotels | </a>
                <?php 
                if($details[0]['country'])
                { ?>
                    / <a class="values_cityname" href="<?php echo $url_country; ?>">Hotels in <?php echo $details[0]['country'];?></a>
           <?php 
               }
                ?>
                 <a class="values_cityname" href="<?php echo $url_destination;?>">Hotels in <?php echo $city_name; ?> </a>
               <?php 
                if(trim($_GET['star'])!='hotels')
                {

                if($_GET['star']!='')
                {

                ?>/ <a class="values_cityname1" href="<?php echo $url_category;?>" src=""><?php echo $text;?></a> 	
                <?php } } ?>


<?php 
$date = strtotime($check_in);
$date1 = strtotime($check_out);
?>


              
            </span></li>
            </ul>
         <div class="clearfix"></div>
      </nav>

	


<!--content starts here-->
 <div class="row">
 	<div id="hoteldetails">

 	<button type="button" class="navbar-toggle toggle_filters" >
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span> 
      </button>
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
							  <p class="hotel_found"><span class="count_amount text_color"><?php echo count($hotelListArr);?></span> Hotels found in <span><?php echo $city_name; ?></span></p>
							<h6 style="font-weight: 700!important;">Landmarks </h6>

							<a href="javascript:void(0)" class="search_bylocs">Search by landmark</a>

							</div>
							
						</div>
					</div><hr>
					  <li>
                      <div class="dropdown col-xs-12">
                          <h2 class="show_hide_type text_color">Hotel Types</h6>
                          	<Span class="show_atag_hotel" href="#hotelTypes" data-toggle="collapse" >Show</Span>
                          <div id="hotelTypes"  class="collapse in">
                          <ul class="dropdown-menus new_drop_menu ">

                              <li><a href="#"><input type="checkbox" data-sort-type="hoteltype" data-value="1">
                              
                              <label>Hotel <span class="filter_number hotel_type_1"><?php echo $count_property_type[1]; ?></span></label>
                              </li>

                              <li><a href="#"><input type="checkbox" data-sort-type="hoteltype" data-value="2">
                              
                              <label>Resort <span class="filter_number hotel_type_2"><?php echo $count_property_type[2]; ?></span></label></a></li>

                              <li><a href="#"><input type="checkbox" data-sort-type="hoteltype" data-value="3">
                              
                              <label>Apartment <span class="filter_number hotel_type_3"><?php echo $count_property_type[3]; ?></span></label></a></li>

                              <li><a href="#"><input type="checkbox" data-sort-type="hoteltype" data-value="4">
                              
                              <label>Villa <span class="filter_number hotel_type_4"><?php echo $count_property_type[4]; ?></span></label></a></li>

                              <li><a href="#"><input type="checkbox" data-sort-type="hoteltype" data-value="5">
                              
                              <label>Homestay <span class="filter_number hotel_type_5"><?php echo $count_property_type[5]; ?></span></label></a></li>

                              <li><a href="#"><input type="checkbox" data-sort-type="hoteltype" data-value="6">
                              
                              <label>Dormitory <span class="filter_number hotel_type_6"><?php echo $count_property_type[6]; ?></span></label></a></li>

                              <li><a href="#"><input type="checkbox" data-sort-type="hoteltype" data-value="7">
                              
                              <label>Guest House <span class="filter_number hotel_type_7"><?php echo $count_property_type[7]; ?></span></label></a></li>
                          </ul>
                      </div>
                      </div>
                  </li><hr>

	                <li>
                      <div class="dropdown col-xs-12 ">
                          <h2 class="text_color">Price </h2>
                          <Span class="show_atag_hotel"   href="#hotelPrice" data-toggle="collapse" >Show</Span>
                <div id="hotelPrice" class="collapse">
                          <ul class="dropdown-menus new_drop_menu ">
                
                            <li><a href="#">
                            <input type="checkbox" data-sort-type="price" data-value="999">
                            
                            <label> Upto <i class="fa fa-rupee"></i> 999 <span class="filter_number price_range_0_999"><?php echo $count_price['0-999']?></span> </label></a></li>

                              <li><a href="#"><input type="checkbox" data-sort-type="price" data-value="1000#3000"><label> <i class="fa fa-rupee"></i> 1000 to <i class="fa fa-rupee"></i> 3000 <span class="filter_number price_range_1000_3000"><?php echo $count_price['1000-3000']?></span></label></a></li>

                              <li><a href="#"><input type="checkbox" data-sort-type="price" data-value="3000#5000"><label> <i class="fa fa-rupee"></i> 3000 to <i class="fa fa-rupee"></i> 5000 <span class="filter_number price_range_3000_5000"><?php echo $count_price['3000-5000']?></span></label></a></li>

                              <li><a href="#"><input type="checkbox" data-sort-type="price" data-value="5000#10000"><label> <i class="fa fa-rupee"></i> 5000 to <i class="fa fa-rupee"></i> 10000 <span class="filter_number price_range_5000_10000"><?php echo $count_price['5000-10000']?></span></label></a></li>
                          </ul>
                      </div>
                      </div>
                  </li>
	                


	               
	               <li><hr>
                      <div class="dropdown col-xs-12 ">
            <h2 class="text_color">Star Category</a> </h2>
            	<Span class="show_atag_hotel"   href="#hotelStar" data-toggle="collapse" >Show</Span>
              <div id="hotelStar" class="collapse">
                          <ul class="dropdown-menus new_drop_menu ">
                          

                              <li><a href="#"><input  <?php echo (isset($star)&&$star==1)?'checked':'';?> type="checkbox"  id="1_star" data-sort-type="rating" data-value="1">
                            <label for="1_star">
                               <!-- <i class="fa fa-star-o" aria-hidden="true"></i> -->
                              <span><i class="material-icons">&#xE838;</i></span>
                              <span class="filter_number star_rating_1"><?php echo $count_star['1'];?></span>

                            </label></a></li>
                            
                            <li><a href="#"><input <?php echo (isset($star)&&$star==2)?'checked':'';?>  type="checkbox" id="2_star" data-sort-type="rating" data-value="2">
                            <label for="2_star">
                            <span><i class="material-icons">&#xE838;</i></span>
                            <span><i class="material-icons">&#xE838;</i></span>
                              <span class="filter_number star_rating_2"><?php echo $count_star['2'];?></span>
                            </label></a></li>

                            <li><a href="#"><input <?php echo (isset($star)&&($star==3))?'checked':'';?>  type="checkbox" id="3_star" data-sort-type="rating" data-value="3">
                            <label for="3_star">
                            <span><i class="material-icons">&#xE838;</i></span>
                             <span><i class="material-icons">&#xE838;</i></span>
                              <span><i class="material-icons">&#xE838;</i></span>
                              <span class="filter_number star_rating_3"><?php echo $count_star['3'];?></span>
                            </label></a></li>

                            <!--<li class="divider"></li>-->
                            <li><a href="#"><input <?php echo (isset($star)&&$star==4)?'checked':'';?>  type="checkbox" id="4_star" data-sort-type="rating" data-value="4">
                            <label for="4_star">
                             <span><i class="material-icons">&#xE838;</i></span>
                              <span><i class="material-icons">&#xE838;</i></span>
                               <span><i class="material-icons">&#xE838;</i></span>
                                <span><i class="material-icons">&#xE838;</i></span>
                              <span class="filter_number star_rating_4"><?php echo $count_star['4'];?></span>
                            </label></a></li>

                            <li><a href="#"><input <?php echo (isset($star)&&$star==5)?'checked':'';?>  type="checkbox" id="5_star" data-sort-type="rating" data-value="5">
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
	                
	            


 					<li>
                      <div class="dropdown col-xs-12">
                         <h2 class="text_color">Amenities </h2>
                         	<Span class="show_atag_hotel"    href="#hotelAmenities" data-toggle="collapse" >Show</Span>
              <div id="hotelAmenities" class="collapse">
                          <ul class="dropdown-menus new_drop_menu " >
                              <li><a href="#"><input type="checkbox" data-sort-type="amentities" data-value="business_center">
                              <label>Business Services</label></a></li>

                              <!--<li><a href="#"><label><input type="checkbox" data-sort-type="amentities" data-value="internet#internet_access_in_rooms">Free Internet</label></a></li>-->
                              <li><a href="#"><input type="checkbox" data-sort-type="amentities" data-value="front_desk_24_hours#front_desk">
                              <label>Front desk</label></a></li>

                <li><a href="#"><input type="checkbox" data-sort-type="amentities" data-value="travel_desk"><label>Travel Desk</label></a></li>

                <li><a href="#"><input type="checkbox" data-sort-type="amentities" data-value="internet#internet_access_in_rooms"><label>Internet</label></a></li>

                <li><a href="#"><input type="checkbox" data-sort-type="amentities" data-value="bar"><label>Bar</label></a></li>
                              <li><a href="#"><input type="checkbox" data-sort-type="amentities" data-value="laundry"><label>Laundry Service</label></a></li>
                  

                <li><a href="#"><input type="checkbox" data-sort-type="amentities" data-value="free_parking#bus_parking#indoor_parking#outdoor_parking#parking#valet_parking"><label>Parking Facility</label></a></li>

                              <li><a href="#"><input type="checkbox" data-sort-type="amentities" data-value="restaurant#coffee_shop"><label>Restaurant/Coffee Shop</label></a></li>

                              <li><a class="text_color" href="#"><input type="checkbox" data-sort-type="amentities" data-value="room_service#room_service_24_hours"><label>Room Service</label></a></li>

                  
                <li><a href="#"><input type="checkbox" data-sort-type="amentities" data-value="spa#ayurveda_spa"><label>Spa</label></a></li>

                              <li><a href="#"><input type="checkbox" data-sort-type="amentities" data-value="swimming_pool">
                              <label>Swimming Pool</label></a></li>

                              <li><a href="#"><input type="checkbox" data-sort-type="amentities" data-value="disco#casino#night_club#squash#exercise_gym#bowling">
                              <label>Indoor Entertainment</label></a></li>

                  
                              <li><a href="#"><input type="checkbox" data-sort-type="amentities" data-value="hiking#horseback_riding#snow_skiing#volleyball#exercise_gym#bowling#basketball_court#boating#fishing#golf_driving_range#golf_putting_green#jogging_track#skiing_snow#tennis"><label>Outdoor Activities</label></a></li>

                          </ul>
                      </div>
                      </div>
                      </article>
                  </li>
	            </ul>
	      


</aside>
 <section class="three-fourth">

  
       <div class="dropdown col-sm-12" style="padding: 15px 0;margin-bottom: 10px; box-shadow: 0 0 30px rgba(0, 0, 0, 0.1)!important;" >
    
 <div  class="col-sm-5	" >
    <div style="border: 1px solid #ccc;  position: relative;">

      <input style="color:#555!important;font-weight: normal;"   type="button" value ="Select by Address, Landmark and Location " class="searchforadd">
      <span class="manual_caret_searhc"><i  class="fa fa-search" aria-hidden="true"></i> 
        <i  class="fa fa-caret-down close_div" aria-hidden="true"></i></span>


     <ul class="dropdown-menu dropdown-menus new_drop_menu dropmenusval  "  >

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
 	<li>Price<span class="caretidv"><i class="fa fa-caret-down price_sort" onclick="sortfor('price_sort','fa-caret-down',1);" aria-hidden="true"></i><i class="fa fa-caret-up price_sort" aria-hidden="true" onclick="sortfor('price_sort','fa-caret-up',0);"></i></span></li>
 	<li>Stars <span class="caretidv"><i class="fa fa-caret-down star_sort" onclick="sortfor('star_sort','fa-caret-down',1);" aria-hidden="true"></i><i class="fa fa-caret-up star_sort" aria-hidden="true" onclick="sortfor('star_sort','fa-caret-up',0);"></i></span></li>
 	<!--<li>Rating <span class="caretidv"><i class="fa fa-caret-down rating_sort" onclick="sortfor('rating_sort','fa-caret-down',1);" aria-hidden="true"></i><i class="fa fa-caret-up rating_sort" aria-hidden="true" onclick="sortfor('rating_sort','fa-caret-up',0);"></i></span></li>-->
 	
 	</ul>


<input type="hidden" id="price_sort" value="">
<input type="hidden" id="star_sort" value="">
<!--<input type="hidden" id="rating_sort" value="">-->


               
              

                    <ul style="float:right;" class="view-type">
  
                  <li class="grid-view active"><a href="#" title="grid view">grid view</a></li>
                  <li class="list-view "><a href="#" title="list view">list view</a></li>
               </ul>
            </div>

<div class="deals">
               <!--deal-->
               <div class="">
 <?php $count1=0;

  $hotelListArr_for_pagin=array_chunk($hotelListArr, $perPage->perpage, true);

      if(isset($hotelListArr) && !empty($hotelListArr)){
        $_SESSION['hotelListArr']=$hotelListArr;


        foreach($hotelListArr_for_pagin[0] as $id_property => $hotels){



       
if($count1==3)
{
	echo '<div class="clearfix"></div>';
$count1=0;
}
$count1++;
          //echo '<pre>';print_r($hotels);echo '</pre>';
    ?>
     


                  <!--accommodation item-->
                  <article class="accommodation_item one-third">
                     <div>
                        <figure class="assuerd_div">
                           <a href="" title="Villa Maria">
                           <!-- <img src="<?php echo (isset($hotels['photo_gallery']) && !empty($hotels['photo_gallery']) ? $hotels['photo_gallery'] : $root_dir.'images/noimage.jpg'); ?>" alt="Villa Maria" /> -->

                           <span class="ribbobn_assured green"><i class="material-icons doubletick">done_all</i>
	              Staysinn Assured</span>
                          <a class="fancybox" href="<?php echo (isset($hotels['photo_gallery']) && !empty($hotels['photo_gallery']) ? $hotels['photo_gallery'] : $root_dir.'images/noimage.jpg'); ?>">
	        <img class="hotelimage " src="<?php echo (isset($hotels['photo_gallery']) && !empty($hotels['photo_gallery']) ? $hotels['photo_gallery'] : $root_dir.'hotels/search/images/noimage.jpg'); ?>"></a>
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

                              <i class="fa fa-map-marker" aria-hidden="true"></i><span><?php echo $hotels['selLocation']?></span>
                              <!-- <?php echo $hotels['txtAddress1']; ?> --></span>

                              <span class='rating'>
                              	
                              	<span class="full_ratingval">

	  <img src="https://staysinn.com/favicon.png">

	  <span class="full_rativnval green_text"><span class="rating_numbers">4.0 / </span>5</span>    
	  </span>	
	  <br>
	  <a style="margin-top:10px;cursor: pointer;display: block;color:#ccc;"> <span class="text_color">100</span> Reviews  <i class="fa fa-angle-right" aria-hidden="true"></i></a>
                              </span>	
                              		
                           <div class="price">
                              Price from 
                              <?php if($hotels['offer_price']!=$hotels['price'])
                              {
                              	?>
                              	<span class="price_span">
	                              <em>
	                              
	                              <span class="price_strike_span" ><i class="fa fa-inr" aria-hidden="true"></i><?php echo $hotels['price']?></span>
	                              </em>

	                              <em class="price_under">
	                              <span class="curr text_color"><i class="fa fa-inr" aria-hidden="true"></i></span>
	                              <span class="amount text_color"><?php echo $hotels['offer_price']?></span>
	                              </em>
                          		</span>

                           <?php   }else{ ?>
							<span class="price_span">
	                           
	                              <em class="price_under">
	                              <span class="curr"><i class="fa fa-inr" aria-hidden="true"></i></span>
	                              <span class="amount"><?php echo $hotels['price']?></span>
	                              </em>
                          		</span>
                             
                         <?php   } ?>
                              

                           </div>
                           <div class='description '>

                           	
                           



                           		 <p class="discription_overflow"><?php //substr(("fdiusgodfug"),0,100)
                              $pag_ct=strip_tags($hotels['txtPropertyDescription']);


                               if($pag_ct!=''){ echo $pag_ct; ?></p>


                           <?php   if(strlen($pag_ct)>=150){?>
                            <a  class="more_infos text_color" >More info</a>
                            <?php } }?>



                           </div>


                             <div  class=" icons_all">
	              <a href="#" data-toggle="tooltip" title="Room Service">
               <i class="material-icons"<?php if(!in_array('room_service',$hotels['amenities']) && !in_array('room_service_24_hours',$hotels['amenities'])) echo 'style="color:#c8c8c8;cursor:not-allowed;"'; ?>>room_service</i>
               </a>
               <a href="#" data-toggle="tooltip" title="Gym / Spa">
               <i class="material-icons"<?php if(!in_array('exercise_gym',$hotels['amenities']) && !in_array('spa',$hotels['amenities'])) echo 'style="color:#c8c8c8;cursor:not-allowed;"'; ?>>fitness_center</i>
               </a>
               <a href="#" data-toggle="tooltip" title="Swimming Pool">
               <i class="material-icons"<?php if(!in_array('swimming_pool',$hotels['amenities'])) echo 'style="color:#c8c8c8;cursor:not-allowed;"'; ?>>pool</i>
               </a>
               <a href="#" data-toggle="tooltip" title="Wi-fi">
               <i class="fa fa-wifi text_color" style="font-size: 22px;position: relative; bottom: 3px;" aria-hidden="true"<?php if(!in_array('internet_access_in_rooms',$hotels['amenities'])) echo 'style="color:#c8c8c8;cursor:not-allowed;font-size: 22px;position: relative; bottom: 3px;"'; ?>></i>
               </a>
               <a href="#" data-toggle="tooltip" title="Restaurant">
               <i class="material-icons"<?php if(!in_array('restaurant',$hotels['amenities'])) echo 'style="color:#c8c8c8;cursor:not-allowed;"'; ?>>restaurant</i>
               </a>
               <a href="#" data-toggle="tooltip" title="Internet Access">
               <i class="material-icons"<?php if(!in_array('internet',$hotels['amenities'])) echo 'style="color:#c8c8c8;cursor:not-allowed;"'; ?>>desktop_windows</i>
               </a>
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
	                 <button type="submit" class=" btn btnBook1 select_room_btn button_color" name="hotel_book" value="Book Now">Select Room</button>
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
	
		
		$perpageresult = $perPage->getAllPageLinks(count($hotelListArr),1);	
		echo $perpageresult;
		

		?>
	</div>
	<input type="hidden" id="rowcount" value="1">
</div>
</section> 

         
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
	
	function getresult(pagenumber)
	{
		$("#rowcount").val(pagenumber);
		$('.dropdown-menus li a').trigger('click');
	}
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
      $( "select option:selected" ).each(function() {
      guest=guest+parseInt($(this).val());
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
     $(".childlist_"+currentpax+" li").removeAttr("selected");
     $(identifier).addClass("actives");
   
    $( "select option:selected" ).each(function() {
      guest=guest+parseInt($(this).val());
      });
    var room=$("#roomval").val();

      $('.detail_pax_'+currentpax+' .this_child').text(value);
     $('.total_guest_room').text(room+' Room, '+guest+' Guests');
   
   /*$('.child_age_div_'+currentpax).remove();
   var ageSelect = '<div class="child_age_div_'+currentpax+'">';
   for(c=1;c<=value;c++){
     ageSelect = ageSelect + '<div class="clearfix"><p class="head_title_room">Child '+c+' Age</p><select style="width:50px;border:1px solid #ccc;" class="child_age" data-room="'+currentpax+'"><option>1</option><option>2</option><option>3</option><option>4</option><option>5</option><option>6</option><option>7</option><option>8</option><option>9</option><option>10</option><option>11</option><option>12</option></select></div>';
   }
   ageSelect = ageSelect + '</div>';
   $(".content_pax_"+currentpax).append(ageSelect);*/
   
   }
   function ondone()
   {
   
     $("#demo").removeClass("in");
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
  $( "select option:selected" ).each(function() {
      guest=guest+parseInt($(this).val());
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
           append+='<div class="col-sm-6 col-xs-6">';
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
   
        var guest=parseInt($("#guestval").val());  
        $('.actives').each(function(){
        guest=guest+parseInt($(this).val());
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
			   $(".adultlist_"+room+ " option:selected" ).each(function() {
			      noofadult=parseInt($(this).val());
			      });
			    $(".childlist_"+room+ " option:selected" ).each(function() {
			      noofchild=parseInt($(this).val());
			      });
			 // noofadult=$(this).parent().find(".adultlist_"+room+' .actives').text();  
			  //noofchild=$(this).parent().find(".childlist_"+room+' .actives').text(); 
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
			queryArr['rowcount']=$("#rowcount").val()
			queryArr['guestJson'] = guestJson;
			$.ajax({
				type: "POST",
				url: "index-ajax.php",
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
		//queryArr['rating_sort'] = $('#rating_sort').val();
		queryArr['guestJson'] = guestJson;

		$.ajax({
			type: "POST",
			url: "index-ajax.php",
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
			url: "index-ajax.php",
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
</div></div></div>


	
					

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

	<Script>
    $( document ).ready(function() {

 




    });

    
  </Script>



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

 $('.hotelde_inner')
            .theiaStickySidebar({
              
            });

   });


 window.itemClass = "one-third";

  </script>


  <?php include('../../include/footer.php'); ?>