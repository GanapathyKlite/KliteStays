<?php
	error_reporting(E_ALL);
	include('include/database/config.php');
$getHotelConfigValues = $database->query("select name,value from ps_configuration where name = 'ps_hotel_service_tax' or  name = 'ps_hotel_commission' or  name = 'ps_hotel_pay_cgst_2499' or  name = 'ps_hotel_pay_sgst_2499' or  name = 'ps_hotel_pay_cgst_7499' or  name = 'sps_hotel_pay_sgst_7499' or  name = 'ps_hotel_pay_cgst_7500' or  name = 'ps_hotel_pay_sgst_7500'")->fetchAll(PDO::FETCH_ASSOC);
			//include('hotel/modules/fileuploader/class.fileuploader.php');

	if(isset($_GET['ajaxAction']) && $_GET['ajaxAction'] == 'getCityByState'){
		$content = '<option value="">Select City</option>';
		$id_state = (int)$_POST['id_state'];
		$citiesListArr = $database->query('select * from ps_city where id_state='.$id_state.' order by name asc')->fetchAll();
		if(isset($citiesListArr) && !empty($citiesListArr))
			foreach($citiesListArr as $city)
				$content .= '<option value="'.$city['id_city'].'" '.(isset($_POST['id_city']) && $_POST['id_city'] == $city['id_city'] ? 'selected' : '').'>'.$city['name'].'</option>';
		echo $content;
		exit;
	}
	if(isset($_GET['ajaxAction']) && $_GET['ajaxAction'] == 'getLandmark'){
		$content = '<option value="">Select Landmark</option>';
		$selCityId = $_POST['selCityId'];	

		$ss=$database->query('select * from ps_landmark where selCityId='.$selCityId.' and status=0 and id_history=0 order by txtLandmark asc');	
		
		if($ss)
		{
			$landmarkArr =$ss->fetchAll(PDO::FETCH_ASSOC);
			
			if(isset($landmarkArr) && !empty($landmarkArr))
				foreach($landmarkArr as $landmark)
					$content .= '<option value="'.$landmark['id_landmark'].'" '.(isset($_POST['id_landmark']) && $_POST['id_landmark'] == $landmark['id_landmark'] ? 'selected' : '').'>'.$landmark['txtLandmark'].'</option>';
			echo $content;
			exit;
		}
		echo $content;
		exit;
		
	}

	$errorMessage = '';$error=0;$submitFlag = 0;
	if(isset($_POST['submitPropertyHidden'])&&$_POST['submitPropertyHidden']==1)
	{
		//	echo '<pre>';print_r($_POST['txtMasterRate']);echo '</pre>';
	//die;
		//echo '<pre>';print_r($_POST);echo '</pre>';
	//	echo '<pre>';print_r($_FILES);echo '</pre>';
		//die;
		//$fileuploaded=$database->update('ps_property',array('selImages' => 'fsafsd',' where id_property=130',1));
		//die;
		//$pah=dirname(__FILE__).'/hotel/img/uploads/';
		//$pah=_BO_HOTEL_IMG_DIR_;
		$target_dir = "hotel/img/uploads/";
		$pah = "hotel/img/uploads/";
		
		unset($_POST['submitPropertyHidden']);
		$emailExists = $database->query('select txtEmail from ps_property where txtEmail=\''.$_POST['txtEmail'].'\' and is_delete=0')->fetchAll();
		if(isset($emailExists[0]) && !empty($emailExists[0])){
			$errorMessage = 'Email already exists!';
		}else{
			unset($_POST['submitProperty'],$_POST['submitPropertyHidden']);
			$cancellation_policy = implode(',',$_POST['cancellation_policy']);

			$isGST=isset($_POST['gstavailable'])?$_POST['gstavailable']:'';
			$forproperty=array('selPropertyTypeID'=>$_POST['selPropertyTypeID'],'selStarRating'=>$_POST['selStarRating'],'txtNoOfGuestRooms'=>$_POST['txtNoOfGuestRooms'],'txtPropertyName'=>$_POST['txtPropertyName'],'selCountryId'=>$_POST['selCountryId'],'selStateId'=>$_POST['selStateId'],'selCityId'=>$_POST['selCityId'],'selLandmark'=>$_POST['selLandmark'],'txtAddress1'=>$_POST['txtAddress1'],'txtAddress2'=>$_POST['txtAddress2'],'txtZip'=>$_POST['txtZip'],'txtPhone'=>$_POST['txtPhone'],'txtMobile'=>$_POST['txtMobile'],'txtFax'=>$_POST['txtFax'],'txtEmail'=>$_POST['txtEmail'],'txtWebSite'=>$_POST['txtWebSite'],'txtPropertyDescription'=>$_POST['txtPropertyDescription'],'txtBankAccNo'=>$_POST['txtBankAccNo'],'txtBankIFSC'=>$_POST['txtBankIFSC'],'txtBeneficiaryName'=>$_POST['txtBeneficiaryName'],'txtTAC'=>$_POST['txtTAC'],'isGST'=>$isGST,'cancellation_policy'=>$cancellation_policy,'terms_and_conditions'=>$_POST['terms_and_conditions'],'first_update'=>1);

		$fileuploader_list_propertygallery = $_POST['fileuploader-list-propertygallery'];
			$values = array_merge($forproperty,array('status' => 1,'date_add' => date('Y-m-d H:i:s'),'date_upd' => date('Y-m-d H:i:s')));
			$propertyid = $database->insert('ps_property', $values);
			if(isset($fileuploader_list_propertygallery) && !empty($fileuploader_list_propertygallery)){
				$fileList = [];

			$upload_dir = $target_dir=$pah.'property/'.$propertyid.'/';

					
						if(!is_dir($pah.'property/'.$propertyid.'/'))
						mkdir($pah.'property/'.$propertyid.'/', 0777, true);
				$count=count($_FILES['propertygallery']['name']);
			//	print_r($count);
					for($i=0;$i<$count;$i++)
					{
					//	echo $_FILES['propertygallery']['name'][$i];

					if(isset($_FILES['propertygallery']['name'][$i])&&$_FILES['propertygallery']['name'][$i]!='')
					{
					   $uploadFile = $upload_dir.basename($_FILES['propertygallery']['name'][$i]);
					   
					 if(move_uploaded_file($_FILES['propertygallery']['tmp_name'][$i], $uploadFile))
					    {

					        $uploadRequest = array(
					            'uploadurl' => $target_dir,
					            'fileName' => basename($uploadFile),
					            'fileData' => base64_encode(file_get_contents($uploadFile)));


					        $curl = curl_init();
					        curl_setopt($curl, CURLOPT_URL, 'https://buddiestechnologies.com/rec.php');
					        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
					        curl_setopt($curl, CURLOPT_POST, 1);
					        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
					        curl_setopt($curl, CURLOPT_POSTFIELDS, $uploadRequest);
					        $response = curl_exec($curl);
					        curl_close($curl);					     
					        unlink($uploadFile);
					       	$fileList[] = [$i => $_FILES['propertygallery']['name'][$i]];
					    }
					}
					} 
				
			
			//$fileuploaded=$database->update('ps_property',array('selImages' => json_encode($fileList)),' where id_property='.$propertyid,1);
			}
			if($propertyid)
			{
			
				$facilities=isset($_POST['facilities'])?$_POST['facilities']:array();
				
				$facilities=array_fill_keys(array_keys($facilities),1);
				$facilities = array_merge($facilities,array('id_property'=>$propertyid,'date_add' => date('Y-m-d H:i:s'),'date_upd' => date('Y-m-d H:i:s')));
				$facilityid = $database->insert('ps_property_facility', $facilities);
				if(!$facilityid)
				{
					$errorMessage="Error Occured facility".$facilityid;
				}

				$id_room_type=$_POST['id_room_type'];
				
				$roomamentities=array();
				
				foreach($_POST['selRoomCategory'] as $orderKey=>$orderValue)
				{
					if(isset($_POST['roomamentities'][$orderKey]))
					{
						
						$roomamentities= array_fill_keys(array_keys($_POST['roomamentities'][$orderKey]), 1);		
						
					}
					$fileuploader_list_roomgallery=$_POST['fileuploader-list-roomgallery'.$orderKey];
					$selRoomCategory=$orderValue;
					$roomValues = array(
							'id_property' => $propertyid,
							'selRoomCategory' => $selRoomCategory,
							'inv_sun'=>$_POST['noofRooms'][$orderKey],
							'inv_mon'=>$_POST['noofRooms'][$orderKey],
							'inv_tue'=>$_POST['noofRooms'][$orderKey],
							'inv_wed'=>$_POST['noofRooms'][$orderKey],
							'inv_thu'=>$_POST['noofRooms'][$orderKey],
							'inv_fri'=>$_POST['noofRooms'][$orderKey],
							'inv_sat'=>$_POST['noofRooms'][$orderKey],							
							'otherCategory' => $_POST['otherCategory'][$orderKey],
							'period_from' => '',
							'period_to' => '',
							'date_add' => date('Y-m-d H:i:s'),
							'date_upd' => date('Y-m-d H:i:s')
						);
					$otherCategory=$_POST['otherCategory'][$orderKey];

					$valuesextra = array('rateExtraBedAdult' => $_POST['txtExtraBedAdult'][$selRoomCategory],'rateExtraBedChildMoreThanFive' => $_POST['txtExtraBedMoreThanFive'][$selRoomCategory]);
					$roomtypevalues = array_merge(array(
									'minNoOfGuest' => $_POST['minNoOfGuest'][$orderKey],
									'noofRooms' => $_POST['noofRooms'][$orderKey],
									'txtRoomName' => $_POST['txtRoomName'][$orderKey],
									'selMaxNoOfGuest' => $_POST['selMaxNoOfGuest'][$orderKey],
									'selRoomBedSize' => $_POST['selRoomBedSize'][$orderKey],
									'txtPropertyDescription' => $_POST['txtRoomDescription'][$orderKey],
								),$roomamentities);

						$roomtypevalues=array_merge($roomtypevalues,$valuesextra);
						if($selRoomCategory==13)
						{


								$res = $database->insert('ps_room',$roomValues);
								$lastID=$lastRoomID = $roomExists = $res;

								$currentMonth = date('n');
								$currentYear = date('Y');
								//echo '<pre>'; print_r($_POST['masterInventory']); echo '</pre>';
								$yearInc = 0;
								for ($x = $currentMonth; $x < $currentMonth + 12; $x++){
									if($x > 12 && empty($yearInc)){ $currentYear++; $yearInc++; }
									$currentDate = $currentYear.'-'.($x > 12 ? $x-12 : $x).'-01';
									for ($y = 1; $y <= date('t',strtotime($currentDate)); $y++){
										$currentDate1 = $currentYear.'-'.($x > 12 ? $x-12 : $x).'-'.$y;
										$currentDate2 = $currentYear.'-'.str_pad(($x > 12 ? $x-12 : $x),2,0,STR_PAD_LEFT).'-'.str_pad($y,2,0,STR_PAD_LEFT);
										$dateValues[$currentYear][($x > 12 ? $x-12 : $x)][$res][$y] = $_POST['noofRooms'][$orderKey];
									}
									
									
								}

								if(isset($dateValues) && !empty($dateValues)){
										foreach($dateValues as $yearKey => $yearVal){
											foreach($yearVal as $monthKey => $monthVal){
												foreach($monthVal as $roomKey => $dateVals){
													//echo $yearKey.'_'.$monthKey.'_'.$roomKey.print_r($roomVal).'<br>';
													$insertValues = $dateVals;
													$insertValues['id_room'] = $roomKey;
													$insertValues['month'] = $monthKey;
													$insertValues['year'] = $yearKey;
													$finalValues = $insertValues + array('allot_avail' => 0,'date_add' => date('Y-m-d H:i:s'),'date_upd' => date('Y-m-d H:i:s'));
													$finalValues1 = $insertValues + array('allot_avail' => 1,'date_add' => date('Y-m-d H:i:s'),'date_upd' => date('Y-m-d H:i:s'));
													//Db::getInstance()->insert('room_available_inventory',$finalValues);
													//Db::getInstance()->insert('room_available_inventory',$finalValues1);
													$res=$database->insert('room_available_inventory',$finalValues);
													$res=$database->insert('room_available_inventory',$finalValues1);
													
												}
											}
										}
								}


								$room_type=implode('_',explode(' ',$otherCategory));
								$res=$database->insert('ps_room_type',array_merge($roomtypevalues, array('id_room' => $lastID, 'id_room_type' => $room_type, 'date_add' => date('Y-m-d H:i:s'), 'date_upd' => date('Y-m-d H:i:s'))));
								
								if(isset($_POST['txtMasterRate']) && !empty($_POST['txtMasterRate'])){
						$valuesmaster = array(
						'withbreakfast_rate_sun' => $_POST['txtMasterRate'][$selRoomCategory][$room_type]['with'][0],
						'withbreakfast_rate_mon' => $_POST['txtMasterRate'][$selRoomCategory][$room_type]['with'][0],
						'withbreakfast_rate_tue' => $_POST['txtMasterRate'][$selRoomCategory][$room_type]['with'][0],
						'withbreakfast_rate_wed' => $_POST['txtMasterRate'][$selRoomCategory][$room_type]['with'][0],
						'withbreakfast_rate_thu' => $_POST['txtMasterRate'][$selRoomCategory][$room_type]['with'][0],
						'withbreakfast_rate_fri' => $_POST['txtMasterRate'][$selRoomCategory][$room_type]['with'][0],
						'withbreakfast_rate_sat' => $_POST['txtMasterRate'][$selRoomCategory][$room_type]['with'][0],
						'withoutbreakfast_rate_sun' => $_POST['txtMasterRate'][$selRoomCategory][$room_type]['without'][0],
						'withoutbreakfast_rate_mon' => $_POST['txtMasterRate'][$selRoomCategory][$room_type]['without'][0],
						'withoutbreakfast_rate_tue' => $_POST['txtMasterRate'][$selRoomCategory][$room_type]['without'][0],
						'withoutbreakfast_rate_wed' => $_POST['txtMasterRate'][$selRoomCategory][$room_type]['without'][0],
						'withoutbreakfast_rate_thu' => $_POST['txtMasterRate'][$selRoomCategory][$room_type]['without'][0],
						'withoutbreakfast_rate_fri' => $_POST['txtMasterRate'][$selRoomCategory][$room_type]['without'][0],
						'withoutbreakfast_rate_sat' => $_POST['txtMasterRate'][$selRoomCategory][$room_type]['without'][0],
						'is_breakfast' =>implode(',',$_POST['is_breakfast'][$orderKey]),
						'date_upd' => date('Y-m-d H:i:s')
						);
										$wheremaster = ' where id=\''.$res.'\'';
									$rese23=$database->update('ps_room_type', $valuesmaster, $wheremaster, 1);
									//echo $rese23;
									
								}
								/*if(isset($_POST['txtExtraBedAdult']) && !empty($_POST['txtExtraBedAdult'])){
										foreach($_POST['txtExtraBedAdult'][$selRoomCategory] as $extrakey => $extravalue){
												$valuesextra = array(
															'rateExtraBedAdult' => $_POST['txtExtraBedAdult'][$selRoomCategory],
															'rateExtraBedChildMoreThanFive' => $_POST['txtExtraBedMoreThanFive'][$selRoomCategory]
															
														);
												$whereextra = ' where id = '.$res;
												$database->update('ps_room_type', $valuesextra, $whereextra, 1);
											}
									
								}*/
								if(!$res)
								{
									
									$error++;
								}
								if(isset($fileuploader_list_roomgallery) && !empty($fileuploader_list_roomgallery)){
								$upload_dir=$target_dir = $pah.'rooms/'.$lastID.'/'.$room_type.'/';

									if(!is_dir($pah.'rooms/'.$lastID.'/'.$room_type.'/'))
										mkdir($pah.'rooms/'.$lastID.'/'.$room_type.'/', 0777, true);
									
									$fileList = [];
									$count=count($_FILES['roomgallery'.$orderKey]['name']);
									for($i=0;$i<$count;$i++)
									{
									if(isset($_FILES['roomgallery'.$orderKey]['name'][$i])&&$_FILES['roomgallery'.$orderKey]['name'][$i]!='')
									{
									   $uploadFile = $upload_dir.basename($_FILES['roomgallery'.$orderKey]['name'][$i]);
									 if(move_uploaded_file($_FILES['roomgallery'.$orderKey]['tmp_name'][$i], $uploadFile))
									    {

									        $uploadRequest = array(
									            'uploadurl' => $target_dir,
									            'fileName' => basename($uploadFile),
									            'fileData' => base64_encode(file_get_contents($uploadFile)));


									        $curl = curl_init();
									        curl_setopt($curl, CURLOPT_URL, 'https://buddiestechnologies.com/rec.php');
									        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
									        curl_setopt($curl, CURLOPT_POST, 1);
									        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
									        curl_setopt($curl, CURLOPT_POSTFIELDS, $uploadRequest);
									        $response = curl_exec($curl);
									        curl_close($curl);
									        unlink($uploadFile);
									       	$fileList[] = [$i => $_FILES['roomgallery'.$orderKey]['name'][$i]];
									    }
									}
									} 
								$res=$database->update('ps_room_type',array('photo_gallery' => json_encode($fileList)),' where id='.$res,1);
								
							}
						}else
						{


						foreach($id_room_type[$orderKey] as $key=>$val)
						{

									$res = $database->insert('ps_room',$roomValues);
									$lastRoomID = $roomExists = $res;

									$currentMonth = date('n');
									$currentYear = date('Y');
									//echo '<pre>'; print_r($_POST['masterInventory']); echo '</pre>';
									$yearInc = 0;
									for ($x = $currentMonth; $x < $currentMonth + 12; $x++){
										if($x > 12 && empty($yearInc)){ $currentYear++; $yearInc++; }
										$currentDate = $currentYear.'-'.($x > 12 ? $x-12 : $x).'-01';
										for ($y = 1; $y <= date('t',strtotime($currentDate)); $y++){
											$currentDate1 = $currentYear.'-'.($x > 12 ? $x-12 : $x).'-'.$y;
											$currentDate2 = $currentYear.'-'.str_pad(($x > 12 ? $x-12 : $x),2,0,STR_PAD_LEFT).'-'.str_pad($y,2,0,STR_PAD_LEFT);
											$dateValues[$currentYear][($x > 12 ? $x-12 : $x)][$res][$y] = $_POST['noofRooms'][$orderKey];
										}
										
										
									}

									if(isset($dateValues) && !empty($dateValues)){
											foreach($dateValues as $yearKey => $yearVal){
												foreach($yearVal as $monthKey => $monthVal){
													foreach($monthVal as $roomKey => $dateVals){
														//echo $yearKey.'_'.$monthKey.'_'.$roomKey.print_r($roomVal).'<br>';
														$insertValues = $dateVals;
														$insertValues['id_room'] = $roomKey;
														$insertValues['month'] = $monthKey;
														$insertValues['year'] = $yearKey;
														$finalValues = $insertValues + array('allot_avail' => 0,'date_add' => date('Y-m-d H:i:s'),'date_upd' => date('Y-m-d H:i:s'));
														$finalValues1 = $insertValues + array('allot_avail' => 1,'date_add' => date('Y-m-d H:i:s'),'date_upd' => date('Y-m-d H:i:s'));
														
													
													$fsfdf=$database->insert('room_available_inventory',$finalValues);
													echo $fsfdf;
													$gsfdgdf=$database->insert('room_available_inventory',$finalValues1);
													echo $gsfdgdf;
													die;
													//Db::getInstance()->insert('room_available_inventory',$finalValues);
													//Db::getInstance()->insert('room_available_inventory',$finalValues1);
														
													}
												}
											}
									}

									$lastID = (empty($roomExists) ?$res : $roomExists);
									$res=$database->insert('ps_room_type',array_merge($roomtypevalues, array('id_room' => $lastID, 'id_room_type' => $val, 'date_add' => date('Y-m-d H:i:s'), 'date_upd' => date('Y-m-d H:i:s'))));
								if(isset($_POST['txtMasterRate']) && !empty($_POST['txtMasterRate'])){

								/*'rate_sun' => $_POST['txtMasterRate'][$selRoomCategory][$val][0],
										'rate_mon' => $_POST['txtMasterRate'][$selRoomCategory][$val][0],
										'rate_tue' => $_POST['txtMasterRate'][$selRoomCategory][$val][0],
										'rate_wed' => $_POST['txtMasterRate'][$selRoomCategory][$val][0],
										'rate_thu' => $_POST['txtMasterRate'][$selRoomCategory][$val][0],
										'rate_fri' => $_POST['txtMasterRate'][$selRoomCategory][$val][0],
										'rate_sat' => $_POST['txtMasterRate'][$selRoomCategory][$val][0],*/
										$valuesmaster = array(
						'withbreakfast_rate_sun' => $_POST['txtMasterRate'][$selRoomCategory][$val]['with'][0],
						'withbreakfast_rate_mon' => $_POST['txtMasterRate'][$selRoomCategory][$val]['with'][0],
						'withbreakfast_rate_tue' => $_POST['txtMasterRate'][$selRoomCategory][$val]['with'][0],
						'withbreakfast_rate_wed' => $_POST['txtMasterRate'][$selRoomCategory][$val]['with'][0],
						'withbreakfast_rate_thu' => $_POST['txtMasterRate'][$selRoomCategory][$val]['with'][0],
						'withbreakfast_rate_fri' => $_POST['txtMasterRate'][$selRoomCategory][$val]['with'][0],
						'withbreakfast_rate_sat' => $_POST['txtMasterRate'][$selRoomCategory][$val]['with'][0],
						'withoutbreakfast_rate_sun' => $_POST['txtMasterRate'][$selRoomCategory][$val]['without'][0],
						'withoutbreakfast_rate_mon' => $_POST['txtMasterRate'][$selRoomCategory][$val]['without'][0],
						'withoutbreakfast_rate_tue' => $_POST['txtMasterRate'][$selRoomCategory][$val]['without'][0],
						'withoutbreakfast_rate_wed' => $_POST['txtMasterRate'][$selRoomCategory][$val]['without'][0],
						'withoutbreakfast_rate_thu' => $_POST['txtMasterRate'][$selRoomCategory][$val]['without'][0],
						'withoutbreakfast_rate_fri' => $_POST['txtMasterRate'][$selRoomCategory][$val]['without'][0],
						'withoutbreakfast_rate_sat' => $_POST['txtMasterRate'][$selRoomCategory][$val]['without'][0],
						'is_breakfast' =>implode(',',$_POST['is_breakfast'][$orderKey]),
										'date_upd' => date('Y-m-d H:i:s')
									);
										
										$wheremaster = ' where id='.$res;
										$database->update('ps_room_type', $valuesmaster, $wheremaster, 1);
									
								}
								/*if(isset($_POST['txtExtraBedAdult']) && !empty($_POST['txtExtraBedAdult'])){
										foreach($_POST['txtExtraBedAdult'][$selRoomCategory] as $extrakey => $extravalue){
												$valuesextra = array(
															'rateExtraBedAdult' => $_POST['txtExtraBedAdult'][$selRoomCategory][$extrakey],
															'rateExtraBedChildMoreThanFive' => $_POST['txtExtraBedMoreThanFive'][$selRoomCategory][$extrakey]
															
														);
												$whereextra = ' where id = '.$res;
												$database->update('ps_room_type', $valuesextra, $whereextra, 1);
											}
									
									}*/
									
									if(isset($fileuploader_list_roomgallery) && !empty($fileuploader_list_roomgallery)){
									$fileList = [];
									
								
										$upload_dir=$target_dir = $pah.'rooms/'.$lastID.'/'.$val.'/';

									if(!is_dir($pah.'rooms/'.$lastID.'/'.$val.'/'))
										mkdir($pah.'rooms/'.$lastID.'/'.$val.'/', 0777, true);
									
								
									$count=count($_FILES['roomgallery'.$orderKey]['name']);
									for($i=0;$i<$count;$i++)
									{
									if(isset($_FILES['roomgallery'.$orderKey]['name'][$i])&&$_FILES['roomgallery'.$orderKey]['name'][$i]!='')
									{
									   $uploadFile = $upload_dir.basename($_FILES['roomgallery'.$orderKey]['name'][$i]);
									   
									 if(move_uploaded_file($_FILES['roomgallery'.$orderKey]['tmp_name'][$i], $uploadFile))
									    {

									        $uploadRequest = array(
									            'uploadurl' => $target_dir,
									            'fileName' => basename($uploadFile),
									            'fileData' => base64_encode(file_get_contents($uploadFile)));


									        $curl = curl_init();
									        curl_setopt($curl, CURLOPT_URL, 'https://buddiestechnologies.com/rec.php');
									        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
									        curl_setopt($curl, CURLOPT_POST, 1);
									        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
									        curl_setopt($curl, CURLOPT_POSTFIELDS, $uploadRequest);
									        $response = curl_exec($curl);
									        curl_close($curl);
									        unlink($uploadFile);
									       	$fileList[] = [$i => $_FILES['roomgallery'.$orderKey]['name'][$i]];
									    }
									}
									} 
								
							
									$res=$database->update('ps_room_type',array('photo_gallery' => json_encode($fileList)),' where id='.$res,1);
									
									
								}

								}
					}
				}
					
					
			$wheremeal = ' where id_property = '.$propertyid;			
				$valuesmeal = array(
					'is_meal_plan' => ((isset($_POST['rateLunchAdult'])&&$_POST['rateLunchAdult']== 'on') ? 1 : 0),
					'rateLunchAdult' => (isset($_POST['rateLunchAdult']) ? $_POST['rateLunchAdult'] : 0),
					'rateLunchMoreThanFive' => (isset($_POST['rateLunchMoreThanFive']) ? $_POST['rateLunchMoreThanFive'] : 0),
					'rateLunchLessThanFive' => (isset($_POST['rateLunchLessThanFive']) ? $_POST['rateLunchLessThanFive'] : 0),
					'rateDinnerAdult' => (isset($_POST['rateLunchAdult']) ? $_POST['rateLunchAdult'] : 0),
					'rateDinnerMoreThanFive' => (isset($_POST['rateDinnerMoreThanFive']) ? $_POST['rateDinnerMoreThanFive'] : 0),
					'rateDinnerLessThanFive' => (isset($_POST['rateDinnerLessThanFive']) ? $_POST['rateDinnerLessThanFive'] : 0)
				);
			$database->update('ps_property', $valuesmeal, $wheremeal);


			}else
			{
				$errorMessage="Error Occured dsfdf0".$propertyid;
			}
			if($error!=0)
			{
				$errorMessage="Error Occured error".$error;
			}
			
			if($propertyid){
				$submitFlag = 1;
				if(isset($propertyid) && !empty($propertyid))
				$database->insert('ps_property_facility', array('id_property' => $propertyid, 'date_add' => date('Y-m-d H:i:s'), 'date_upd' => date('Y-m-d H:i:s')));
				$message=  file_get_contents('mails/property-submit.html');
				$template_vars = array(
							'{shop_name}' => 'Buddies Technologies..!!',
							'{shop_url}' => 'https://hotel.buddiestechnologies.com/dashboard/',
							'{shop_logo}' => 'https://hotel.buddiestechnologies.com/img/admin/logo.png',
						);
				$message = str_replace(array_keys($template_vars),array_values($template_vars),$message);
				$mail = sendConfirmationMail($_POST['txtEmail'],'Property Submission',$message);//$_POST['txtEmail']
				$submitFlag = 1;
				if($mail)
					$submitFlag = 1;
				$message = 'Greetings from Buddies Technologies! Your property details has been submitted and you will be notified via email once verification process has completed. Thanks and Regards, Buddies Tech Team.';
				if(isset($_POST['txtMobile']) && !empty($_POST['txtMobile']))
					smsAPICall($message,$_POST['txtMobile']);
			}
		}
	
	}

	$statesList = $database->query("select * from ps_state order by name asc")->fetchAll();
	global $propertyTypes;
	global $starRatings;
	global $roomCategory;
	global $roomBedSize;

?>


<?php 
$currentpage="signup";
include 'include/header.php';
?>
<script type="text/javascript" src="<?php echo $root_dir;?>hotel/js/fileuploader/custom.js"></script>
<script type="text/javascript" src="<?php echo $root_dir;?>hotel/js/fileuploader/jquery.fileuploader.min1.js"></script>
<link rel="stylesheet" href="<?php echo $root_dir;?>hotel/css/jquery.fileuploader.css">
<link rel="stylesheet" href="<?php echo $root_dir;?>hotel/css/jquery.fileuploader-theme-onebutton.css">
<?php if($submitFlag!=0){ ?>
			<script>
			$( document ).ready(function() {
					$.confirm({
						title: "Congratulations!",
						content: "<p>Property details has been submitted successfully.</p><p>You will be notified via email once verification process has done.</p>",
						type: "green",
						typeAnimated: true,
						alignMiddle: true,
						backgroundDismiss: true,
						onDestroy: function () {
							window.location = "https://www.staysinn.com";
						},
						buttons: {
							ok: function () {
							}
						}
					});
				});
			</script>
	<?php } ?>
<style>
.add_room
{
	position: absolute;
	bottom:0px;
	right: 0px;
}
.form5
{
	position: relative;
}

</style>
<div class="container" style="margin-top: 87px;">
<div class="col-sm-12 col-md-10 col-md-offset-1 contain_hotelsignup"  >
	<form id="property_form" class="defaultForm form-horizontal" action="<?php echo $_SERVER['PHP_SELF']; ?>"  enctype="multipart/form-data" method="post" >
	<h4 class="sign_up_hot" >New Hotelier Sign Up</h4>
<div class="tabss col-sm-12" style="padding: 0;">



	<a class="active" href="javascript:openform('1')" id="tab1" value="1">Property Details</a>
	<a  href="javascript:openform('2')" id="tab2" value="0">Property Amentities</a>
	<a href="javascript:openform('3')" id="tab3" value="0">Bank Details</a>
	<a href="javascript:openform('4')" id="tab4" value="0">GST & TAC</a>
	<a href="javascript:openform('5')" id="tab5" value="0">Room Details</a>
	<a href="javascript:openform('6')" id="tab6" value="0">Rate Details</a>


</div>
<div class="clearfix"></div>	
	<div class="form1 formss" id="form1">
		<div class="row">
	<div class="col-xs-12 col-sm-4"  >
	<div class="inner_sm_class">
	<p class="heading_for_input"><label  class="control-label 	required">Property Type</label></p>
	<p class="input_box_bord" ><select name="selPropertyTypeID" class="form-control fixed-width-xl" id="selPropertyTypeID">
					<option value=""></option>
					<?php foreach($propertyTypes as $pKey => $pItem){ 
						echo '<option value="'.$pKey.'" '.(isset($_POST['selPropertyTypeID']) && $_POST['selPropertyTypeID'] == $pKey ? 'selected' : '').'>'.$pItem.'</option>';
					} ?>
				</select>
				<label > Select a Property Type</label></p>
				
				</div>

		
			
			
		
		</div>
		<div class="col-xs-12 col-sm-4">
		<div class="inner_sm_class">
		<p class="heading_for_input"><label class="control-label required">Star Rating</label></p>
			<p class="input_box_bord" >
			
				  <select name="selStarRating" class="form-control fixed-width-xl" id="selStarRating">
					<option value=""></option>
					<?php foreach($starRatings as $sKey => $sItem){ 
						echo '<option value="'.$sKey.'" '.(isset($_POST['selStarRating']) && $_POST['selStarRating'] == $sKey ? 'selected' : '').'>'.$sItem.'</option>';
					} ?>
				</select>
				<label>Select star rating</label>
			</p>
		</div>
		</div>
				<div class="col-xs-12 col-sm-4">
		<div class="inner_sm_class">
		<p class="heading_for_input">
			<label class="control-label required ">Total No of Guest Rooms</label>
			</p>
			<p class="input_box_bord">
				<input class="form-control"  type="text" name="txtNoOfGuestRooms" id="txtNoOfGuestRooms" size="17" maxlength="4" value="" onkeypress="return event.charCode >= 48 && event.charCode <= 57;">
				<label>(Max. 4 Digits)</label></p>
			
		</div></div>
		<div class="clearfix"></div>
		<div class="col-xs-12 col-sm-4 ">
		<div class="inner_sm_class">
		<p class="heading_for_input">

			<label class="control-label required">Property Name</label>
				</p>
			<p class="input_box_bord">
			
				<input class="form-control"  type="text" name="txtPropertyName" id="txtPropertyName" size="40" maxlength="128" value="">
				<label>(Max. 128 Chars)</label>
				</p>
			
		</div>
		</div>
		<div class="col-xs-12 col-sm-8 	">
		<div class="inner_sm_class">
		<p class="heading_for_input">
			<label class="control-label  required">Location</label></p>
			<div class="row input_box_bord" style="margin-top: 0px;">
				<div class="col-sm-3">
					<select style="width:auto;" class="form-control"  name="selCountryId" id="selCountryId">
					<option value="110">India</option>
				</select>
					<label >Country</label>
				</div>
				<div class="col-sm-6">
					<select  class="form-control half_inputs"  name="selStateId" id="selStateId" onchange="javascript:getCityByState(this.value);">
					<option value="">Select</option>
					<?php foreach($statesList as $state){ 
						echo '<option value="'.$state['id_state'].'" '.(isset($_POST['selStateId']) && $_POST['selStateId'] == $state['id_state'] ? 'selected' : '').'>'.$state['name'].'</option>';
					} ?>
				</select>
				<label class="half_inputs">State</label>
				</div>
				<div class="col-sm-3">
					<select  class="form-control half_inputs"  name="selCityId" id="selCityId"  onchange="javascript:getLandmark(this.value);">
					<option value=""></option>
				</select>
		

		<label class="half_inputs">City</label>
				</div>

			
			
				
			
				
			
				</div>
			
		</div>

		</div>
		<div class="clearfix"></div>


		<div class="col-xs-12 col-sm-4">
	<div class="inner_sm_class">
		<p class="heading_for_input">
			<label class="control-label required">Landmark</label>
			</p>
			
			<p class="input_box_bord">
				<select  class="form-control half_inputs"  name="selLandmark" id="selLandmark">
					<option value=""></option>
				</select>
				<!--<input class="form-control"  type="text" name="selLandmark" size="40" maxlength="256" value="<?php echo (isset($_POST['selLandmark']) ? $_POST['selLandmark'] : ''); ?>">-->
			</p>
			</div>
		
		</div>
		
		<div class="col-xs-12 col-sm-4 ">
		<div class="inner_sm_class">
		<p class="heading_for_input">
			<label class="control-label required">Address 1</label>
			</p>
			
			<p class="input_box_bord">
				<input class="form-control"  type="text" name="txtAddress1" id="txtAddress1" size="40" maxlength="256" value="">
				<label>(Max. 256 Chars)</label></p>
			</div>
		
		</div>
		<div class="col-xs-12 col-sm-4">
		<div class="inner_sm_class">
		<p class="heading_for_input">
			<label class="control-label ">Address 2</label>
		</p>
			
			<p class="input_box_bord">
				<input class="form-control"  type="text" name="txtAddress2" size="40" maxlength="256" value="">
				<label>(Max. 256 Chars)</label></p>
		
		</div>
		</div>
		<div class="col-xs-12 col-sm-4">
	<div class="inner_sm_class">
	<p class="heading_for_input">
			<label class="control-label required ">Zip / Pin Code</label>
			</p>
			
			<p class="input_box_bord">
				<input class="form-control"  type="text" name="txtZip" id="txtZip" size="40" maxlength="9" value="" onkeypress="return event.charCode >= 48 && event.charCode <= 57;">
				<label>(Max. 9 Chars)</label></p>
			</div>
		
		</div>
		<div class="col-xs-12 col-sm-4 ">
		<div class="inner_sm_class">
	<p class="heading_for_input">
			<label class="control-label required">Telephone</label>
			</p>
			<p class="input_box_bord">
				<input class="form-control"  type="text" name="txtPhone" id="txtPhone" size="40" maxlength="15" value="" onkeypress="return event.charCode >= 48 && event.charCode <= 57;">
				<label>(Max. 10 Chars)</label></p>
			
		</div>
		</div>
		<div class="col-xs-12 col-sm-4 ">
		<div class="inner_sm_class">
			<p class="heading_for_input">
			<label class="control-label required">Mobile</label>
			</p>
			<p class="input_box_bord">
				<input class="form-control"  type="text" name="txtMobile" id="txtMobile" size="40" maxlength="10" value="" onkeypress="return event.charCode >= 48 && event.charCode <= 57;">
				<label>(Max. 10 Chars)</label></p>
			
		</div>
		</div>
		<div class="col-xs-12 col-sm-4">
		<div class="inner_sm_class">
	<p class="heading_for_input">
			<label class="control-label ">Fax</label>
				</p>
			<p class="input_box_bord">
				<input class="form-control"  type="text" name="txtFax" size="40" maxlength="32" value="">
				<label>Enter Valid Fax Number</label>
		</p>
		</div>
		</div>
	
		<div class="col-xs-12 col-sm-4">
		<div class="inner_sm_class">
		<p class="heading_for_input">
			<label class="control-label required">Email Address</label>
			</p>
			<p class="input_box_bord">
				<input class="form-control" type="text" name="txtEmail" id="txtEmail" size="40" maxlength="256" value=""><label>Enter Valid Email Address</label></p>
				
		
		</div>
		</div>
		
		<div class="col-xs-12 col-sm-4 ">
		<div class="inner_sm_class">
		<p class="heading_for_input">
			<label class="control-label ">Property Website</label>
				</p>
			<p class="input_box_bord">
				<input class="form-control"  type="text" name="txtWebSite" id="txtWebSite" size="40" maxlength="256" value="">
				<label>Enter Valid Site Name</label>
			</p>
		</div>
		</div>
		<div class="col-xs-12 col-sm-6">
			<div class="inner_sm_class">
		<p class="heading_for_input">
			<label class="control-label required ">Property Description</label>
			</p>
			<p class="input_box_bord">
			
				<textarea class="form-control"   name="txtPropertyDescription" id="txtPropertyDescription"></textarea>
				<label>(Min. 256 Chars)</label></p>
			</div>
		
		</div>	


		<div class="col-xs-12 col-sm-6">
			<div class="inner_sm_class">
		<p class="heading_for_input">
			<label class="control-label required ">Photo Gallery</label>
			</p>
			<p class="input_box_bord">
			<input type="file" name="propertygallery"></p>
			</div>
		
		</div>
		<div class="form-group">
				<div class="col-lg-12">
					<label class="control-label col-lg-3"></label>
					<div class="col-lg-8">
						
					</div>
				</div>
		</div>

		<div class="col-xs-12 col-sm-12 text-right " >
		
			
			
			
			
				<button type="button" name="next" onclick="calldiv('form2',2)" >NEXT</button>
			
		
			
				<!--<button type="button" value="1" name="submitProperty" id="submitProperty" class="btn btn-default submi_hotelsignup">Next</button>-->
				<input type="hidden" name="submitPropertyHidden" value="1">
			
		
		</div>
	</div>
		</div>
	<div class="form2 formss" style=";" >
		<h3>Facility Details</h3>
		<div class="propertyDetailsDiv">
			<div class="panel clearfix">
				<div class="panel-heading">Activities</div>
				<div class="form-horizontal">
					<div class="col-sm-12 border_for_inner">
						<div class="row">
					
						<div class="col-xs-12 col-sm-4 col-md-4">
							<input type="checkbox" name="facilities[bicycling]" id="bicycling">
							<label class="control-label" for="bicycling">Bicycling</label>
						</div>
						<div class="col-xs-12 col-sm-4 col-md-4">
							<input type="checkbox" name="facilities[children_activities]" id="children_activities">
							<label class="control-label" for="children_activities">Children Activities</label>
						</div>
						<div class="col-xs-12 col-sm-4 col-md-4">
							<input type="checkbox" name="facilities[fine_dining]" id="fine_dining">
							<label class="control-label" for="fine_dining">Fine Dining</label>
						</div>
					
				
						<div class="col-xs-12 col-sm-4 col-md-4">
							<input type="checkbox" name="facilities[fitness_center]" id="fitness_center">
							<label class="control-label" for="fitness_center">Fitness Center</label>
						</div>
						<div class="col-xs-12 col-sm-4 col-md-4">
							<input type="checkbox" name="facilities[hiking]" id="hiking">
							<label class="control-label" for="hiking">Hiking</label>
						</div>
						<div class="col-xs-12 col-sm-4 col-md-4">
							<input type="checkbox" name="facilities[horseback_riding]" id="horseback_riding">
							<label class="control-label" for="horseback_riding">Horseback Riding</label>
						</div>
					
				
						<div class="col-xs-12 col-sm-4 col-md-4">
							<input type="checkbox" name="facilities[snow_skiing]" id="snow_skiing">
							<label class="control-label" for="snow_skiing">Snow Skiing</label>
						</div>
						<div class="col-xs-12 col-sm-4 col-md-4">
							<input type="checkbox" name="facilities[swimming_pool]" id="swimming_pool">
							<label class="control-label" for="swimming_pool">Swimming Pool</label>
						</div>
						<div class="col-xs-12 col-sm-4 col-md-4">
							<input type="checkbox" name="facilities[tour_bus]" id="tour_bus">
							<label class="control-label" for="tour_bus">Tour Bus</label>
						</div>
					
					
						<div class="col-xs-12 col-sm-4 col-md-4">
							<input type="checkbox" name="facilities[volleyball]" id="volleyball">
							<label class="control-label" for="volleyball">Volleyball</label>
						</div>
						<div class="col-xs-12 col-sm-4 col-md-4">
							<input type="checkbox" name="facilities[basketball_court]" id="basketball_court">
							<label class="control-label" for="basketball_court">Basketball Court</label>
						</div>
						<div class="col-xs-12 col-sm-4 col-md-4">
							<input type="checkbox" name="facilities[boating]" id="boating">
							<label class="control-label" for="boating">Boating</label>
						</div>
				
					
						<div class="col-xs-12 col-sm-4 col-md-4">
							<input type="checkbox" name="facilities[bowling]" id="bowling">
							<label class="control-label" for="bowling">Bowling</label>
						</div>
						<div class="col-xs-12 col-sm-4 col-md-4">
							<input type="checkbox" name="facilities[exercise_gym]" id="exercise_gym">
							<label class="control-label" for="exercise_gym">Exercise Gym</label>
						</div>
						<div class="col-xs-12 col-sm-4 col-md-4">
							<input type="checkbox" name="facilities[fishing]" id="fishing">
							<label class="control-label" for="fishing">Fishing</label>
						</div>
				
				
						<div class="col-xs-12 col-sm-4 col-md-4">
							<input type="checkbox" name="facilities[golf_driving_range]" id="golf_driving_range">
							<label class="control-label" for="golf_driving_range">Golf-Driving Range</label>
						</div>
						<div class="col-xs-12 col-sm-4 col-md-4">
							<input type="checkbox" name="facilities[golf_putting_green]" id="golf_putting_green">
							<label class="control-label" for="golf_putting_green">Golf-Putting Green</label>
						</div>
						<div class="col-xs-12 col-sm-4 col-md-4">
							<input type="checkbox" name="facilities[health_club]" id="health_club">
							<label class="control-label" for="health_club">Health Club</label>
						</div>
				
					
						<div class="col-xs-12 col-sm-4 col-md-4">
							<input type="checkbox" name="facilities[jogging_track]" id="jogging_track">
							<label class="control-label" for="jogging_track">Jogging Track</label>
						</div>
						<div class="col-xs-12 col-sm-4 col-md-4">
							<input type="checkbox" name="facilities[skiing_snow]" id="skiing_snow">
							<label class="control-label" for="skiing_snow">Skiing-Snow</label>
						</div>
						<div class="col-xs-12 col-sm-4 col-md-4">
							<input type="checkbox" name="facilities[tennis]" id="tennis">
							<label class="control-label" for="tennis">Tennis</label>
						</div>
					</div>
					</div>
					
				</div>
			</div>
			<div class="panel clearfix">
				<div class="panel-heading">Amenities</div>
				<div class="form-horizontal">
					<div class="col-sm-12 border_for_inner">
						<div class="row">
					
						<div class="col-xs-12 col-sm-4 col-md-4">
							<input type="checkbox" name="facilities[front_desk]" id="front_desk">
							<label class="control-label" for="front_desk">Front Desk</label>
						</div>
						<div class="col-xs-12 col-sm-4 col-md-4">
							<input type="checkbox" name="facilities[business_center]" id="business_center">
							<label class="control-label" for="business_center">Business Center</label>
						</div>
						<div class="col-xs-12 col-sm-4 col-md-4">
							<input type="checkbox" name="facilities[complimentary_breakfast]" id="complimentary_breakfast">
							<label class="control-label" for="complimentary_breakfast">Complimentary Breakfast</label>
						</div>
				
					
						<div class="col-xs-12 col-sm-4 col-md-4">
							<input type="checkbox" name="facilities[concierge]" id="concierge">
							<label class="control-label" for="concierge">Concierge</label>
						</div>
						<div class="col-xs-12 col-sm-4 col-md-4">
							<input type="checkbox" name="facilities[elevator]" id="elevator">
							<label class="control-label" for="elevator">Elevator</label>
						</div>
						<div class="col-xs-12 col-sm-4 col-md-4">
							<input type="checkbox" name="facilities[free_newspaper]" id="free_newspaper">
							<label class="control-label" for="free_newspaper">Free Newspaper</label>
						</div>
				
					
						<div class="col-xs-12 col-sm-4 col-md-4">
							<input type="checkbox" name="facilities[free_parking]" id="free_parking">
							<label class="control-label" for="free_parking">Free Parking</label>
						</div>
						<div class="col-xs-12 col-sm-4 col-md-4">
							<input type="checkbox" name="facilities[bar]" id="bar">
							<label class="control-label" for="bar">Bar</label>
						</div>
						<div class="col-xs-12 col-sm-4 col-md-4">
							<input type="checkbox" name="facilities[banquet]" id="banquet">
							<label class="control-label" for="banquet">Banquet</label>
						</div>
				
				
						<div class="col-xs-12 col-sm-4 col-md-4">
							<input type="checkbox" name="facilities[restaurant]" id="restaurant">
							<label class="control-label" for="restaurant">Restaurant</label>
						</div>
						<div class="col-xs-12 col-sm-4 col-md-4">
							<input type="checkbox" name="facilities[safe_deposit_box]" id="safe_deposit_box">
							<label class="control-label" for="safe_deposit_box">Safe Deposit Box</label>
						</div>
						<div class="col-xs-12 col-sm-4 col-md-4">
							<input type="checkbox" name="facilities[casino]" id="casino">
							<label class="control-label" for="casino">Casino</label>
						</div>
					
				
						<div class="col-xs-12 col-sm-4 col-md-4">
							<input type="checkbox" name="facilities[disco]" id="disco">
							<label class="control-label" for="disco">Disco</label>
						</div>
						<div class="col-xs-12 col-sm-4 col-md-4">
							<input type="checkbox" name="facilities[lounge]" id="lounge">
							<label class="control-label" for="lounge">Lounge</label>
						</div>
						<div class="col-xs-12 col-sm-4 col-md-4">
							<input type="checkbox" name="facilities[night_club]" id="night_club">
							<label class="control-label" for="night_club">Night Club</label>
						</div>
					
				
						<div class="col-xs-12 col-sm-4 col-md-4">
							<input type="checkbox" name="facilities[coffee_shop]" id="coffee_shop">
							<label class="control-label" for="coffee_shop">Coffee Shop</label>
						</div>
						<div class="col-xs-12 col-sm-4 col-md-4">
							<input type="checkbox" name="facilities[room_service]" id="room_service">
							<label class="control-label" for="room_service">Room Service</label>
						</div>
						<div class="col-xs-12 col-sm-4 col-md-4">
							<input type="checkbox" name="facilities[room_service_24_hours]" id="room_service_24_hours">
							<label class="control-label" for="room_service_24_hours">Room-Service 24 Hours</label>
						</div>
					
				
						<div class="col-xs-12 col-sm-4 col-md-4">
							<input type="checkbox" name="facilities[handicap_facilities]" id="handicap_facilities">
							<label class="control-label" for="handicap_facilities">Handicap Facilities</label>
						</div>
						<div class="col-xs-12 col-sm-4 col-md-4">
							<input type="checkbox" name="facilities[wheel_chair_access]" id="wheel_chair_access">
							<label class="control-label" for="wheel_chair_access">Wheel Chair Access</label>
						</div>
						<div class="col-xs-12 col-sm-4 col-md-4">
							<input type="checkbox" name="facilities[bus_parking]" id="bus_parking">
							<label class="control-label" for="bus_parking">Bus Parking</label>
						</div>
				
					
						<div class="col-xs-12 col-sm-4 col-md-4">
							<input type="checkbox" name="facilities[indoor_parking]" id="indoor_parking">
							<label class="control-label" for="indoor_parking">Indoor Parking</label>
						</div>
						<div class="col-xs-12 col-sm-4 col-md-4">
							<input type="checkbox" name="facilities[outdoor_parking]" id="outdoor_parking">
							<label class="control-label" for="outdoor_parking">Outdoor Parking</label>
						</div>
						<div class="col-xs-12 col-sm-4 col-md-4">
							<input type="checkbox" name="facilities[parking]" id="parking">
							<label class="control-label" for="parking">Parking</label>
						</div>
					
						<div class="col-xs-12 col-sm-4 col-md-4">
							<input type="checkbox" name="facilities[valet_parking]" id="valet_parking">
							<label class="control-label" for="valet_parking">Valet Parking</label>
						</div>
						<div class="col-xs-12 col-sm-4 col-md-4">
							<input type="checkbox" name="facilities[heated_pool]" id="heated_pool">
							<label class="control-label" for="heated_pool">Heated Pool</label>
						</div>
						<div class="col-xs-12 col-sm-4 col-md-4">
							<input type="checkbox" name="facilities[indoor_pool]" id="indoor_pool">
							<label class="control-label" for="indoor_pool">Indoor Pool</label>
						</div>
				
					
						<div class="col-xs-12 col-sm-4 col-md-4">
							<input type="checkbox" name="facilities[jacuzzi]" id="jacuzzi">
							<label class="control-label" for="jacuzzi">Jacuzzi</label>
						</div>
						<div class="col-xs-12 col-sm-4 col-md-4">
							<input type="checkbox" name="facilities[outdoor_pool]" id="outdoor_pool">
							<label class="control-label" for="outdoor_pool">Outdoor Pool</label>
						</div>
						<div class="col-xs-12 col-sm-4 col-md-4">
							<input type="checkbox" name="facilities[sauna]" id="sauna">
							<label class="control-label" for="sauna">Sauna</label>
						</div>
					
					
						<div class="col-xs-12 col-sm-4 col-md-4">
							<input type="checkbox" name="facilities[conference_facilities]" id="conference_facilities">
							<label class="control-label" for="conference_facilities">Conference Facilities</label>
						</div>
						<div class="col-xs-12 col-sm-4 col-md-4">
							<input type="checkbox" name="facilities[internet_access_in_rooms]" id="internet_access_in_rooms">
							<label class="control-label" for="internet_access_in_rooms">Internet Access in rooms</label>
						</div>
						<div class="col-xs-12 col-sm-4 col-md-4">
							<input type="checkbox" name="facilities[meeting_facilities]" id="meeting_facilities">
							<label class="control-label" for="meeting_facilities">Meeting Facilities</label>
						</div>
					
					
						<div class="col-xs-12 col-sm-4 col-md-4">
							<input type="checkbox" name="facilities[secretarial_service]" id="secretarial_service">
							<label class="control-label" for="secretarial_service">Secretarial Service</label>
						</div>
						<div class="col-xs-12 col-sm-4 col-md-4">
							<input type="checkbox" name="facilities[hair_dryer_in_room]" id="hair_dryer_in_room">
							<label class="control-label" for="hair_dryer_in_room">Hair Dryer In Room</label>
						</div>
						<div class="col-xs-12 col-sm-4 col-md-4">
							<input type="checkbox" name="facilities[non_smoking]" id="non_smoking">
							<label class="control-label" for="non_smoking">Non Smoking</label>
						</div>
				
					
						<div class="col-xs-12 col-sm-4 col-md-4">
							<input type="checkbox" name="facilities[live_band]" id="live_band">
							<label class="control-label" for="live_band">Live Band</label>
						</div>
						<div class="col-xs-12 col-sm-4 col-md-4">
							<input type="checkbox" name="facilities[squash]" id="squash">
							<label class="control-label" for="squash">Squash</label>
						</div>
						<div class="col-xs-12 col-sm-4 col-md-4">
							<input type="checkbox" name="facilities[satellite_tv]" id="satellite_tv">
							<label class="control-label" for="satellite_tv">Satellite TV</label>
						</div>
					</div>
					</div>
					
				</div>
			</div>
			<div class="panel clearfix">
				<div class="panel-heading">Services</div>
				<div class="form-horizontal">
					<div class="col-sm-12 border_for_inner">
						<div class="row">
					
						<div class="col-xs-12 col-sm-4 col-md-4">
							<input type="checkbox" name="facilities[post]" id="post">
							<label class="control-label" for="post">Post</label>
						</div>
						<div class="col-xs-12 col-sm-4 col-md-4">
							<input type="checkbox" name="facilities[whirlpool]" id="whirlpool">
							<label class="control-label" for="whirlpool">Whirlpool</label>
						</div>
						<div class="col-xs-12 col-sm-4 col-md-4">
							<input type="checkbox" name="facilities[pool]" id="pool">
							<label class="control-label" for="pool">Pool</label>
						</div>
					
				
						<div class="col-xs-12 col-sm-4 col-md-4">
							<input type="checkbox" name="facilities[childcare]" id="childcare">
							<label class="control-label" for="childcare">Childcare</label>
						</div>
						<div class="col-xs-12 col-sm-4 col-md-4">
							<input type="checkbox" name="facilities[barber]" id="barber">
							<label class="control-label" for="barber">Barber</label>
						</div>
						<div class="col-xs-12 col-sm-4 col-md-4">
							<input type="checkbox" name="facilities[laundry]" id="laundry">
							<label class="control-label" for="laundry">Laundry</label>
						</div>
				
				
						<div class="col-xs-12 col-sm-4 col-md-4">
							<input type="checkbox" name="facilities[medical]" id="medical">
							<label class="control-label" for="medical">Medical</label>
						</div>
						<div class="col-xs-12 col-sm-4 col-md-4">
							<input type="checkbox" name="facilities[pet]" id="pet">
							<label class="control-label" for="pet">Pet</label>
						</div>
						<div class="col-xs-12 col-sm-4 col-md-4">
							<input type="checkbox" name="facilities[internet]" id="internet">
							<label class="control-label" for="internet">Internet</label>
						</div>
					
						<div class="col-xs-12 col-sm-4 col-md-4">
							<input type="checkbox" name="facilities[travel_desk]" id="travel_desk">
							<label class="control-label" for="travel_desk">Travel Desk</label>
						</div>
						<div class="col-xs-12 col-sm-4 col-md-4">
							<input type="checkbox" name="facilities[babysitting]" id="babysitting">
							<label class="control-label" for="babysitting">Babysitting</label>
						</div>
						<div class="col-xs-12 col-sm-4 col-md-4">
							<input type="checkbox" name="facilities[barber_shop]" id="barber_shop">
							<label class="control-label" for="barber_shop">Barber Shop</label>
						</div>
					
						<div class="col-xs-12 col-sm-4 col-md-4">
							<input type="checkbox" name="facilities[beauty_salon]" id="beauty_salon">
							<label class="control-label" for="beauty_salon">Beauty Salon</label>
						</div>
						<div class="col-xs-12 col-sm-4 col-md-4">
							<input type="checkbox" name="facilities[boutiques]" id="boutiques">
							<label class="control-label" for="boutiques">Boutiques</label>
						</div>
						<div class="col-xs-12 col-sm-4 col-md-4">
							<input type="checkbox" name="facilities[concierge_desk]" id="concierge_desk">
							<label class="control-label" for="concierge_desk">Concierge Desk</label>
						</div>
					
						<div class="col-xs-12 col-sm-4 col-md-4">
							<input type="checkbox" name="facilities[currency_exchange]" id="currency_exchange">
							<label class="control-label" for="currency_exchange">Currency Exchange</label>
						</div>
						<div class="col-xs-12 col-sm-4 col-md-4">
							<input type="checkbox" name="facilities[in_room_telephone_service]" id="in_room_telephone_service">
							<label class="control-label" for="in_room_telephone_service">In Room Telephone Service</label>
						</div>
						<div class="col-xs-12 col-sm-4 col-md-4">
							<input type="checkbox" name="facilities[express_check_in]" id="express_check_in">
							<label class="control-label" for="express_check_in">Express Check-In</label>
						</div>
				
						<div class="col-xs-12 col-sm-4 col-md-4">
							<input type="checkbox" name="facilities[express_check_out]" id="express_check_out">
							<label class="control-label" for="express_check_out">Express Check-Out</label>
						</div>
						<div class="col-xs-12 col-sm-4 col-md-4">
							<input type="checkbox" name="facilities[florist]" id="florist">
							<label class="control-label" for="florist">Florist</label>
						</div>
						<div class="col-xs-12 col-sm-4 col-md-4">
							<input type="checkbox" name="facilities[front_desk_24_hours]" id="front_desk_24_hours">
							<label class="control-label" for="front_desk_24_hours">Front Desk-24 Hours</label>
						</div>
				
						<div class="col-xs-12 col-sm-4 col-md-4">
							<input type="checkbox" name="facilities[gift_shop]" id="gift_shop">
							<label class="control-label" for="gift_shop">Gift Shop</label>
						</div>
						<div class="col-xs-12 col-sm-4 col-md-4">
							<input type="checkbox" name="facilities[guest_laundromat]" id="guest_laundromat">
							<label class="control-label" for="guest_laundromat">Guest Laundromat</label>
						</div>
						<div class="col-xs-12 col-sm-4 col-md-4">
							<input type="checkbox" name="facilities[luggage_storage]" id="luggage_storage">
							<label class="control-label" for="luggage_storage">Luggage Storage</label>
						</div>
				
						<div class="col-xs-12 col-sm-4 col-md-4">
							<input type="checkbox" name="facilities[maid_service]" id="maid_service">
							<label class="control-label" for="maid_service">Maid Service</label>
						</div>
						<div class="col-xs-12 col-sm-4 col-md-4">
							<input type="checkbox" name="facilities[spa]" id="spa">
							<label class="control-label" for="spa">Spa</label>
						</div>
						<div class="col-xs-12 col-sm-4 col-md-4">
							<input type="checkbox" name="facilities[wake_up_service]" id="wake_up_service">
							<label class="control-label" for="wake_up_service">Wake up service</label>
						</div>
				
						<div class="col-xs-12 col-sm-4 col-md-4">
							<input type="checkbox" name="facilities[airport_shuttle]" id="airport_shuttle">
							<label class="control-label" for="airport_shuttle">Airport Shuttle</label>
						</div>
						<div class="col-xs-12 col-sm-4 col-md-4">
							<input type="checkbox" name="facilities[yoga]" id="yoga">
							<label class="control-label" for="yoga">Yoga</label>
						</div>
						<div class="col-xs-12 col-sm-4 col-md-4">
							<input type="checkbox" name="facilities[ayurveda_spa]" id="ayurveda_spa">
							<label class="control-label" for="ayurveda_spa">Ayurveda Spa</label>
						</div>
				
					
						<div class="col-xs-12 col-sm-4 col-md-4">
							<input type="checkbox" name="facilities[9_hole_golf_course]" id="9_hole_golf_course">
							<label class="control-label" for="9_hole_golf_course">9 hole Golf Course</label>
						</div>
						<div class="col-xs-12 col-sm-4 col-md-4 ">
							<input type="checkbox" name="facilities[rooms_suites_and_villas]" id="rooms_suites_and_villas">
							<label class="control-label" for="rooms_suites_and_villas">Rooms Suites and Villas</label>
						</div>
					</div>
					</div>
				</div>
			</div>

		<div class="col-xs-12 text-right " >
		
				<button type="button" name="prev" onclick="calldiv('form1',1)">PREV</button>
			
		
				<button type="button" name="next" onclick="calldiv('form3',3)" >NEXT</button>
		
		</div>
		<div class="clearfix"></div>
		
		</div>
		
	</div>
	<div class="form3 formss" style=";">
		<h3>Bank Details</h3>
		<div class="propertyDetailsDiv">
			<div class="row">
			<div class="col-sm-3">
			
				<label class="control-label  required">Bank Acc No.</label>
				
					<input type="text" name="txtBankAccNo" id="txtBankAccNo" size="20" maxlength="32" value="" onkeypress="return event.charCode >= 48 && event.charCode <= 57;">
				</div>
			
			<div class="col-sm-3">
				<label class="control-label required">Bank IFSC</label>
				
					<input type="text" name="txtBankIFSC" id="txtBankIFSC" size="20" maxlength="32" value="">
				</div>
		
			<div class="col-sm-3">
				<label class="control-label required">Beneficiary Name</label>
				
					<input type="text" name="txtBeneficiaryName" id="txtBeneficiaryName" size="20" value="">
				</div>
		
		
			<div class="col-sm-3">
				<label style="width: 100%;visibility: hidden;" class="control-label ">input button</label>
				<button type="button" name="prev" onclick="calldiv('form2',2)">PREV</button>
		
				<button type="button" name="next" onclick="calldiv('form4',4)" >NEXT</button>
			</div>
		</div>
	
		</div>
		
		</div>
	
		<div class="form4 formss" style=";">
			<h3>GST & TAC Details</h3>
		<div class="propertyDetailsDiv">
			<div class="row">

	
			<div class="showhide">
				<div class="col-sm-3">

					<label class="control-label" style="font-weight: bold!important;">From Rs 1000-2499</label>
						<div class="row">
							<div class="col-sm-6">
								<label class="control-label  ">CGST (%)</label>
								<input type="Text" name="" size="10" value="<?php echo $getHotelConfigValues[1]['value']?>" readonly="">
							</div>
							<div class="col-sm-6">
								<label class="control-label">SGST (%)</label>
								<input type="Text" name="" size="10" value="<?php echo $getHotelConfigValues[4]['value']?>" readonly="">
							</div>
						</div>
				</div>
				<div class="col-sm-3">

					<label class="control-label" style="font-weight: bold!important;">From Rs 2500-7499</label>
						<div class="row">
							<div class="col-sm-6">
								<label class="control-label  ">CGST (%)</label>
								<input type="Text" name="" size="10" value="<?php echo $getHotelConfigValues[2]['value']?>" readonly="" >
							</div>
							<div class="col-sm-6">
								<label class="control-label">SGST (%)</label>
								<input type="Text" name="" size="10" value="<?php echo $getHotelConfigValues[7]['value']?>" readonly="">
							</div>
						</div>
				</div>
				<div class="col-sm-3">

					<label class="control-label" style="font-weight: bold!important;">From Rs 2500-7499</label>
						<div class="row">
							<div class="col-sm-6">
								<label class="control-label  ">CGST (%)</label>
								<input type="Text" name="" size="10" value="<?php echo $getHotelConfigValues[2]['value']?>" readonly="" >
							</div>
							<div class="col-sm-6">
								<label class="control-label">SGST (%)</label>
								<input type="Text" name="" size="10" value="<?php echo $getHotelConfigValues[7]['value']?>" readonly="">
							</div>
						</div>
				</div>

				<div class="col-sm-3">

					<label class="control-label" style="font-weight: bold!important;">Rs 7500 and above</label>
						<div class="row">
							<div class="col-sm-6">
								<label class="control-label  ">CGST (%)</label>
								<input type="Text" name="" size="10" value="<?php echo $getHotelConfigValues[3]['value']?>" readonly="">
							</div>
							<div class="col-sm-6">
								<label class="control-label">SGST (%)</label>
								<input type="Text" name="" size="10"  value="<?php echo $getHotelConfigValues[5]['value']?>" readonly="">
							</div>
						</div>
				</div>
				
	
		</div>

		<div class="col-sm-3">

					<label class="control-label" style="font-weight: bold!important;">TAC (%) MIN(15%)</label>
						<div class="row">
							<div class="col-sm-6">
								
									<input type="text" name="txtTAC" size="10" maxlength="2" value="" onkeypress="return event.charCode >= 48 &amp;&amp; event.charCode <= 57;" onkeyup="checknumber(this.value);">
							</div>
							
						</div>
				</div>


		<div class="col-sm-9">

					<label class="control-label" style="visibility: hidden;" >TAC (%) MIN(15%)</label>
						<div class="row">
							<div class="col-sm-12 ">
								<label style="visibility: hidden;" class="control-label  ">CGST (%)</label>
								<div class="pull-left">
									<input  type="checkbox" name="gstavailable" value="1" onclick="hideshowgst()"><label class="control-label">We Dont have GST</label>
								</div>
								
							</div>
							
						</div>
				</div>
				<div class="clearfix"></div>
	
		<div class="col-sm-12">
					<div class="panel-heading">Cancellation Policy</div>
			</div>
			
				
					<div class="panel" >
						
							<div class="col-sm-4 ">
								<label class="control-label">More than 7 days before check-in</label>
								<input type="Text" name="cancellation_policy[]" id="cancellationpolicy1" value="" onkeypress="javascript:return event.charCode >= 48 &amp;&amp; event.charCode <= 57;" maxlength="3">
								<span style="font-size:9px;">Refund %</span>
							</div>
							
								
						
							<div class="col-sm-3">
								<label class="control-label">7 days before check-in</label>
							
								<input type="Text" name="cancellation_policy[]" value=""  id="cancellationpolicy2"  onkeypress="javascript:return event.charCode >= 48 &amp;&amp; event.charCode <= 57;" maxlength="3">
								<span style="font-size:9px;">Refund %</span>
							</div>
					
						
							<div class="col-sm-3">
								<label class="control-label">3 days before check-in</label>
							
								<input type="Text" name="cancellation_policy[]" value="" id="cancellationpolicy3" onkeypress="javascript:return event.charCode >= 48 &amp;&amp; event.charCode <= 57;" maxlength="3">
								<span style="font-size:9px;">Refund %</span>
							</div>
					
						
							<div class="col-sm-12">
								<label class="control-label">1 day before check-in <span style="font-weight: 600	;">(No Refund)</span></label><br>
							
								<label class="control-label" ></label>
							</div>
					
					</div>
				
		<div class="clearfix"></div>
		<div class="col-sm-6">
				<label style="width: 100%;" class="control-label ">Terms and Conditions <a  style="float:right;font-size: 15px;color:#e31e24;" href="#terms_modal" data-toggle="modal" data-dismiss="modal" > <i class="fa fa-question-circle"></i></a></label>
				
					<textarea placeholder="Eg: You must be at least 18 years of age to check-in. " name="terms_and_conditions" id="terms_and_conditions0"></textarea>
				
		</div>
		
		<div class="col-sm-6 text-left">
			<label class="control-label " style="width: 100%; visibility: hidden;margin-top: 100px;">For Buttons</label>
			<button type="button" name="prev" onclick="calldiv('form3',3)">PREV</button>
			<button   type="button" name="next" onclick="calldiv('form5',5)">NEXT</button>
		</div>
		</div>
		</div>
		
	</div>

	<div class="form5 formss" style=''>
		<h3>Room Details</h3>
		<div class="row">
		<div class="data0 datas" data='0' >

		<div class="col-sm-3">	
				<label class="control-label  required">Room Category</label>
				<select name="selRoomCategory[0]" id="selRoomCategory0"  class="roomcategory form-control" onchange="javascript:getMaxOccupancy(this.value);">
				<option value="">-Choose-</option>
				<?php foreach($roomCategory as $key => $val){ ?>
				<option value="<?php echo $key; ?>"><?php echo $val;?></option>
				<?php } ?>
				</select>
		</div>
		<div class="col-sm-3 othercat" style="display:none">
				<label class="control-label  required">Enter Category Name</label>
				<input type="text" value="" placeholder="Eg: Cottage" name="otherCategory[0]" id="otherCategory0" >

		</div>
		<div class="col-sm-9 idroomtype">
			<label class="control-label required">Room Type</label>
			<div class="row">
				<div class="col-sm-3">
					<input type="checkbox" class="id_room_types" name="id_room_type[0][]" value="1" ><label class="control-label" >Single</label>
				</div>
				<div class="col-sm-3">
						<input type="checkbox"  class="id_room_types" name="id_room_type[0][]" value="2" ><label class="control-label" >Double</label>
				</div>
				<div class="col-sm-3">
						<input type="checkbox" class="id_room_types" name="id_room_type[0][]" value="3" ><label class="control-label" >Triple</label>
  				
				</div>
				<div class="col-sm-3">
					<input type="checkbox" class="id_room_types" name="id_room_type[0][]" value="4" ><label class="control-label" >Quadruple</label>
				</div>


			</div>
				

		</div><div class="clearfix"></div>

		<div class="col-sm-3 ">
			<label class="control-label ">Room Display Name</label>	
			<input type="text" placeholder="Eg: Quadruple" name="txtRoomName[0]" id="txtRoomName" size="40" maxlength="40" value="">
				 <span class="pull-left small">(Max. 40 Chars)</span> 
		</div>
	


	
	
	
		<div class="max_count_details">
			<div class="col-sm-6 ">
				<div class="pull-left width50_percent">
					<label class="control-label  required">Min. Guest Allowed</label><br>
					<select class="form-control px_width60" name="minNoOfGuest[0]" id="minNoOfGuest0">
						<?php for($i=1; $i<=15;$i++){ ?>
							<option value="<?php echo $i;?>"><?php echo $i;?></option>
						<?php } ?>
						
					</select>
				

		</div>
		<div class="pull-left width50_percent">
			<label class="control-label  required">Max. Guests Allowed</label><br>
			<select class="form-control px_width60" name="selMaxNoOfGuest[0]" id="selMaxNoOfGuest0">
						<?php for($i=1; $i<=15;$i++){ ?>
							<option value="<?php echo $i;?>"><?php echo $i;?></option>
						<?php } ?>
					</select>
				

		</div>
	</div>
		<div class="col-sm-3">
			<label class="control-label  required">Total No of Rooms</label>
			<input type="text" name="noofRooms[0]" value="" id="noofRooms0" onkeypress="return event.charCode >= 48 && event.charCode <= 57;">
				

		</div>
		<div class="clearfix"></div>
		<div class="col-sm-3">
					<label class="control-label required">Size of Bed(s)</label>
					<select class="form-control" name="selRoomBedSize[0]" id="selRoomBedSize0">
						<option value="">-Choose-</option>
					<?php foreach($roomBedSize as $key => $val){ ?>
					<option value="<?php echo $key; ?>"><?php echo $val;?></option>
					<?php } ?>
					</select>

		</div>
	<div class="col-sm-6 isbreakfast">
					<label class="control-label required">Breakfast Inclusion</label>
					<input type="checkbox" name="is_breakfast[0][1]" class="breakfast" value="1" >Include
  				<input type="checkbox" name="is_breakfast[0][2]" class="breakfast" value="2" >Not Include

		</div>

				
					
			
			
		</div>
		<div class="clearfix"></div>
		
			<div class="col-sm-12">
				<div class="panel-heading">Room Amenities</div>
			
			
			<div class="col-sm-12 border_for_inner"   >
				<div class="row">

			<div class="col-xs-12 col-sm-4 col-md-4">
							<input type="checkbox" name="roomamentities[0][medical]" id="medical0">
							<label class="control-label" for="medical0">Medical</label>
			</div>
			<div class="col-xs-12 col-sm-4 col-md-4">
				<input type="checkbox" name="roomamentities[0][ac]" id="ac0">
						<label class="control-label" for="ac0">AC</label>
							
			</div>
			<div class="col-xs-12 col-sm-4 col-md-4">
				<input type="checkbox" name="roomamentities[0][cable_tv]" id="cable_tv0">
						<label class="control-label" for="cable_tv0">Cable TV</label>
							
			</div>
			
			
				
				
					<div class="col-xs-12 col-sm-4 col-md-4">
						<input type="checkbox" name="roomamentities[0][direct_phone]" id="direct_phone0">
						<label class="control-label" for="direct_phone0">Direct Phone</label>
					</div>
					<div class="col-xs-12 col-sm-4 col-md-4">
						<input type="checkbox" name="roomamentities[0][channel_music]" id="channel_music0">
						<label class="control-label" for="channel_music0">Channel Music</label>
					</div>
				
			
					<div class="col-xs-12 col-sm-4 col-md-4">
						<input type="checkbox" name="roomamentities[0][attached_bathroom]" id="attached_bathroom0">
						<label class="control-label" for="attached_bathroom0">Attached Bathroom</label>
					</div>
					<div class="col-xs-12 col-sm-4 col-md-4">
						<input type="checkbox" name="roomamentities[0][shower]" id="shower0">
						<label class="control-label" for="shower0">Shower</label>
					</div>
				
			
					<div class="col-xs-12 col-sm-4 col-md-4">
						<input type="checkbox" name="roomamentities[0][bath_tub]" id="bath_tub0">
						<label class="control-label" for="bath_tub0">Bath Tub</label>
					</div>
					<div class="col-xs-12 col-sm-4 col-md-4">
						<input type="checkbox" name="roomamentities[0][shower_bath_tub]" id="shower_bath_tub0">
						<label class="control-label" for="shower_bath_tub0">Shower/Bath Tub</label>
					</div>
				
			
					<div class="col-xs-12 col-sm-4 col-md-4">
						<input type="checkbox" name="roomamentities[0][minibar]" id="minibar0">
						<label class="control-label" for="minibar0">Minibar</label>
					</div>
					<div class="col-xs-12 col-sm-4 col-md-4">
						<input type="checkbox" name="roomamentities[0][work_desk]" id="work_desk0">
						<label class="control-label" for="work_desk0">Work Desk</label>
					</div>
				
				
					<div class="col-xs-12 col-sm-4 col-md-4">
						<input type="checkbox" name="roomamentities[0][balcony]" id="balcony0">
						<label class="control-label" for="balcony0">Balcony</label>
					</div>
					<div class="col-xs-12 col-sm-4 col-md-4">
						<input type="checkbox" name="roomamentities[0][radio]" id="radio0">
						<label class="control-label" for="radio0">Radio</label>
					</div>
				
				
					<div class="col-xs-12 col-sm-4 col-md-4">
						<input type="checkbox" name="roomamentities[0][clock]" id="clock0">
						<label class="control-label" for="clock0">Clock</label>
					</div>
					<div class="col-xs-12 col-sm-4 col-md-4">
						<input type="checkbox" name="roomamentities[0][hair_dryer]" id="hair_dryer0">
						<label class="control-label" for="hair_dryer0">Hair Dryer</label>
					</div>
				
					<div class="col-xs-12 col-sm-4 col-md-4">
						<input type="checkbox" name="roomamentities[0][fire_place]" id="fire_place0">
						<label class="control-label" for="fire_place0">Fire Place</label>
					</div>
					<div class="col-xs-12 col-sm-4 col-md-4">
						<input type="checkbox" name="roomamentities[0][safe_deposit_boxs]" id="safe_deposit_boxs0">
						<label class="control-label" for="safe_deposit_boxs0">Safe Deposit Box</label>
					</div>
			
					<div class="col-xs-12 col-sm-4 col-md-4">
						<input type="checkbox" name="roomamentities[0][smoke_alarms]" id="smoke_alarms0">
						<label class="control-label" for="smoke_alarms0">Smoke alarms</label>
					</div>
					<div class="col-xs-12 col-sm-4 col-md-4">
						<input type="checkbox" name="roomamentities[0][sprinklers]" id="sprinklers0">
						<label class="control-label" for="sprinklers0">Sprinklers</label>
					</div>
			
					<div class="col-xs-12 col-sm-4 col-md-4">
						<input type="checkbox" name="roomamentities[0][double_bed]" id="double_bed0">
						<label class="control-label" for="double_bed0">Double Bed</label>
					</div>
					<div class="col-xs-12 col-sm-4 col-md-4">
						<input type="checkbox" name="roomamentities[0][king_bed]" id="king_bed0">
						<label class="control-label" for="king_bed0">King Bed</label>
					</div>
				
					<div class="col-xs-12 col-sm-4 col-md-4">
						<input type="checkbox" name="roomamentities[0][high_speed_wi_fi_internet_access]" id="high_speed_wi_fi_internet_access0">
						<label class="control-label" for="high_speed_wi_fi_internet_access0">High speed Wi-Fi internet access</label>
					</div>
					<div class="col-xs-12 col-sm-4 col-md-4">
						<input type="checkbox" name="roomamentities[0][lcd_tv]" id="lcd_tv0">
						<label class="control-label" for="lcd_tv0">LCD TV</label>
					</div>
			
					<div class="col-xs-12 col-sm-4 col-md-4">
						<input type="checkbox" name="roomamentities[0][sound_proof_windows]" id="sound_proof_windows0">
						<label class="control-label" for="sound_proof_windows0">Sound proof windows</label>
					</div>
					<div class="col-xs-12 col-sm-4 col-md-4">
						<input type="checkbox" name="roomamentities[0][24_hour_room_service]" id="24_hour_room_service0">
						<label class="control-label" for="24_hour_room_service0">24 hour room service</label>
					</div>
				
					<div class="col-xs-12 col-sm-4 col-md-4">
						<input type="checkbox" name="roomamentities[0][electronic_lock]" id="electronic_lock0">
						<label class="control-label" for="electronic_lock0">Electronic lock</label>
					</div>
					<div class="col-xs-12 col-sm-4 col-md-4">
						<input type="checkbox" name="roomamentities[0][electronic_laptop_compatible_safe]" id="electronic_laptop_compatible_safe0">
						<label class="control-label" for="electronic_laptop_compatible_safe0">Electronic laptop compatible safe</label>
					</div>
			
					<div class="col-xs-12 col-sm-4 col-md-4">
						<input type="checkbox" name="roomamentities[0][marble_flooring]" id="marble_flooring0">
						<label class="control-label" for="marble_flooring0">Marble Flooring</label>
					</div>
					<div class="col-xs-12 col-sm-4 col-md-4">
						<input type="checkbox" name="roomamentities[0][study_table]" id="study_table0">
						<label class="control-label" for="study_table0">Study Table</label>
					</div>
			
					<div class="col-xs-12 col-sm-4 col-md-4">
						<input type="checkbox" name="roomamentities[0][free_local_phone_calls]" id="free_local_phone_calls0">
						<label class="control-label" for="free_local_phone_calls0">Free local phone calls</label>
					</div>
					<div class="col-xs-12 col-sm-4 col-md-4">
						<input type="checkbox" name="roomamentities[0][iron_with_ironing_board]" id="iron_with_ironing_board0">
						<label class="control-label" for="iron_with_ironing_board0">Iron with ironing board (on request)</label>
					</div>
				
					<div class="col-xs-12 col-sm-4 col-md-4">
						<input type="checkbox" name="roomamentities[0][full_length_mirror]" id="full_length_mirror0">
						<label class="control-label" for="full_length_mirror0">Full length mirror</label>
					</div>
					<div class="col-xs-12 col-sm-4 col-md-4">
						<input type="checkbox" name="roomamentities[0][complimentary_toiletries]" id="complimentary_toiletries0">
						<label class="control-label" for="complimentary_toiletries0">Complimentary toiletries</label>
					</div>
				
					<div class="col-xs-12 col-sm-4 col-md-4">
						<input type="checkbox" name="roomamentities[0][internets]" id="internets0">
						<label class="control-label" for="internet0">Internet</label>
					</div>
					<div class="col-xs-12 col-sm-4 col-md-4">
						<input type="checkbox" name="roomamentities[0][tea_coffee_maker]" id="tea_coffee_maker0">
						<label class="control-label" for="tea_coffee_make0r">Tea/Coffee Maker</label>
					</div>
				
					<div class="col-xs-12 col-sm-4 col-md-4">
						<input type="checkbox" name="roomamentities[0][complimentary_tea_coffee]" id="complimentary_tea_coffee0">
						<label class="control-label" for="complimentary_tea_coffee0">Complimentary Tea/Coffee</label>
					</div>
					<div class="col-xs-12 col-sm-4 col-md-4">
						<input type="checkbox" name="roomamentities[0][complimentary_packed_water_bottles]" id="complimentary_packed_water_bottles0">
						<label class="control-label" for="complimentary_packed_water_bottles0">Complimentary Packed Water Bottles</label>
					</div>
			
					<div class="col-xs-12 col-sm-4 col-md-4">
						<input type="checkbox" name="roomamentities[0][carpet_flooring]" id="carpet_flooring0">
						<label class="control-label" for="carpet_flooring0">Carpet Flooring</label>
					</div>
					<div class="col-xs-12 col-sm-4 col-md-4">
						<input type="checkbox" name="roomamentities[0][complimentary_fruit_basket]" id="complimentary_fruit_basket0">
						<label class="control-label" for="complimentary_fruit_basket0">Complimentary Fruit Basket</label>
					</div>
			
					<div class="col-xs-12 col-sm-4 col-md-4">
						<input type="checkbox" name="roomamentities[0][high_speed_wi_fi_internet]" id="high_speed_wi_fi_internet0">
						<label class="control-label" for="high_speed_wi_fi_internet0">High Speed Wi-Fi Internet (chargeable)</label>
					</div>
					<div class="col-xs-12 col-sm-4 col-md-4">
						<input type="checkbox" name="roomamentities[0][double_twin_beds]" id="double_twin_beds0">
						<label class="control-label" for="double_twin_beds0">Double/Twin Beds</label>
					</div>
			
					<div class="col-xs-12 col-sm-4 col-md-4">
						<input type="checkbox" name="roomamentities[0][direct_dialing_phone]" id="direct_dialing_phone0">
						<label class="control-label" for="direct_dialing_phone0">Direct-Dialing Phone</label>
					</div>
					<div class="col-xs-12 col-sm-4 col-md-4">
						<input type="checkbox" name="roomamentities[0][smoke_detector_alarms]" id="smoke_detector_alarms0">
						<label class="control-label" for="smoke_detector_alarms0">Smoke Detector Alarms</label>
					</div>
				
			
		</div></div>
	</div>
		<div class="clearfix"></div>
		<div >
			<div class="col-sm-12">
				<div class="panel-heading col-sm-12">Room Views</div>
			
			
		
			<div class="col-sm-12 border_for_inner " >
				<div class="row">
				
					<div class="col-xs-12 col-sm-4 col-md-4">
						<input type="checkbox" name="roomamentities[0][on_the_bay]" id="on_the_bay0">
						<label class="control-label" for="on_the_bay0">On the bay</label>
					</div>
					<div class="col-xs-12 col-sm-4 col-md-4">
						<input type="checkbox" name="roomamentities[0][on_the_beach]" id="on_the_beach0">
						<label class="control-label" for="on_the_beach0">On the beach</label>
					</div>
				
					<div class="col-xs-12 col-sm-4 col-md-4">
						<input type="checkbox" name="roomamentities[0][on_the_garden]" id="on_the_garden0">
						<label class="control-label" for="on_the_garden0">On the garden</label>
					</div>
					<div class="col-xs-12 col-sm-4 col-md-4">
						<input type="checkbox" name="roomamentities[0][on_the_lake]" id="on_the_lake0">
						<label class="control-label" for="on_the_lake0">On the lake</label>
					</div>
				
					<div class="col-xs-12 col-sm-4 col-md-4">
						<input type="checkbox" name="roomamentities[0][on_the_ocean]" id="on_the_ocean0">
						<label class="control-label" for="on_the_ocean0">On the ocean</label>
					</div>
					<div class="col-xs-12 col-sm-4 col-md-4">
						<input type="checkbox" name="roomamentities[0][on_the_park]" id="on_the_park0">
						<label class="control-label" for="on_the_park0">On the park</label>
					</div>
			
					<div class="col-xs-12 col-sm-4 col-md-4">
						<input type="checkbox" name="roomamentities[0][on_the_river]" id="on_the_river0">
						<label class="control-label" for="on_the_river0">On the river</label>
					</div>
					<div class="col-xs-12 col-sm-4 col-md-4">
						<input type="checkbox" name="roomamentities[0][poolside_room]" id="poolside_room0">
						<label class="control-label" for="poolside_room0">Poolside Room</label>
					</div>
				
					<div class="col-xs-12 col-sm-4 col-md-4">
						<input type="checkbox" name="roomamentities[0][garden_room]" id="garden_room0">
						<label class="control-label" for="garden_room0">Garden Room</label>
					</div>
					<div class="col-xs-12 col-sm-4 col-md-4">
						<input type="checkbox" name="roomamentities[0][city_view]" id="city_view0">
						<label class="control-label" for="city_view0">City view</label>
					</div>
			
					<div class="col-xs-12 col-sm-4 col-md-4">
						<input type="checkbox" name="roomamentities[0][mountain_view]" id="mountain_view0">
						<label class="control-label" for="mountain_view0">Mountain view</label>
					</div>
					<div class="col-xs-12 col-sm-4 col-md-4">
						<input type="checkbox" name="roomamentities[0][sea_facing_room]" id="sea_facing_room0">
						<label class="control-label" for="sea_facing_room0">Sea Facing Room</label>
					</div>
			
			</div></div>
		</div>
		</div>
		<div class="col-sm-6">
			<label class="control-label  required">Room Description</label>
			
				<textarea name="txtRoomDescription[0]" id="txtRoomDescription0" class="autoload_rte"></textarea>
			
		</div>
		<div class="col-sm-6">
			
				<label class="control-label required ">Photo Gallery</label>
			
					<input type="file" name="roomgallery0" required>
			
			
		</div>
	</div>	
		</div>
		
		
		<div class="forMoreRoom">
			

		</div>
		<div class="col-sm-12">
			
				<label class="control-label" style="visibility: hidden;">Add Room</label>
			
					<button value="1"  type="button" class="add_room button_addremove" onclick="AddRoom(this.value)"><i class="fa fa-plus-square	"></i></button>
			
			
		</div>
		<div class="row">

		<div class="col-sm-12 text-right">
			
				<label class="control-label" style="visibility: hidden;">Buttons</label><br>
				<button type="button" name="prev" onclick="calldiv('form4',4)">PREV</button>
			
					<button type="button" name="next" onclick="calldiv('form6',6)">NEXT</button>
			
			
		</div>
	</div>
	
	</div>
	<div class="form6 formss" style=''>

		<div class="panel" id="fieldset_0">
			<div class="clearfix"></div>
	<div class="panel-heading">Room Rates</div>
	
	
		<input type="Hidden" name="id_property" value="129">
		<div class="table-responsive clearfix">
			<table class="table">
				<thead>
					<tr>
						<th class="col-lg-2">Room Type</th>
						<th class="col-lg-2">Occupancy</th>
						<th class="col-lg-2"></th>
						<th>Rate Per Day</th>
						
					</tr>
				</thead>
				<tbody class="roomrates">
										
									
				</tbody>
			</table>
		</div>
		
</div>

<div class="panel" id="fieldset_1">
	<div class="panel-heading">
	<i></i>Extra Bed Rates 
	</div>
	
	
		<input type="Hidden" name="id_property" value="129">
		<div class="table-responsive clearfix">
			<table class="table">
				<thead>
					<tr>
						<th class="col-lg-2">Room Type</th>
						<!--<th class="col-lg-2">Occupancy</th>-->
						<th class="col-lg-2">Per Person</th>
						<th class="col-lg-2">Per Kid(5-12 years)</th>
					</tr>
				</thead>
				<tbody class="extrabedrates">
					
									
								</tbody>
			</table>
		</div>
		
</div>

<div class="panel" id="fieldset_2">
	<div class="panel-heading">
	<i></i><label class="control-label">Meal Plans Incl.</label>&nbsp;&nbsp;<input type="checkbox" class="switchMealPlans" name="is_meal_plan">
	</div>
	
	
		<input type="Hidden" name="id_property" value="129">
		<div class="table-responsive clearfix">
			<table class="table" id="mealPlansTable">
				<thead>
					<tr>
						<th class="col-xs-12 col-sm-4 col-md-4">Session</th>
						<th class="col-lg-2">Extra Adult</th>
						<th class="col-lg-2">Child Rate (5-12 years)</th>
						
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>Lunch</td>
						<td>
							<div class="col-lg-6">
								<input type="text" name="rateLunchAdult" size="17" maxlength="5" value="" disabled="" onkeypress="return event.charCode >= 48 && event.charCode <= 57;">
							</div>
						</td>
						<td>
							<div class="col-lg-6">
								<input type="text" name="rateLunchMoreThanFive" size="17" maxlength="5" value="" disabled="" onkeypress="return event.charCode >= 48 && event.charCode <= 57;">
							</div>
						</td>
						
					</tr>
					<tr>
						<td>Dinner</td>
						<td>
							<div class="col-lg-6">
								<input type="text" name="rateDinnerAdult" size="17" maxlength="5" value="" disabled="" onkeypress="return event.charCode >= 48 && event.charCode <= 57;">
							</div>
						</td>
						<td>
							<div class="col-lg-6">
								<input type="text" name="rateDinnerMoreThanFive" size="17" maxlength="5" value="" disabled="" onkeypress="return event.charCode >= 48 && event.charCode <= 57;">
							</div>
						</td>
						
					</tr>
					
				</tbody>
			</table>
		</div>
		
	
</div><div class="row">
		<div class="col-sm-12 text-right">
			
				<button type="button" name="prev" onclick="calldiv('form5',5)">PREV</button>
			
			
				<button type="submit" name="submitproperty" id="submitProperty" >SAVE</button>
			
			
		</div>
	</div>

	</div>

		<div class="clearfix"></div>
		<input type="hidden" id="selectedcat" value="">
	</form>
	</div>

		
		
	</div>

<!-- fOR moDAL; -->

  <div class="modal" id="terms_modal" >
  <div class="modal-dialog" style="width:85%;position: relative;top: 0%;transform: translateY(-0%)!important;
"  >
   <div class="modal-content" style="position: relative;">
      <div class="modal-header " >
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Terms & Conditions</h4>
      </div>


           <div class="modal-body" >            
               

<h3>INTRODUCTION</h3>
<p>
The purpose of this document is to regulate the GENERAL TERMS or CONDITIONS OF PROCUREMENT of the Pre-booking and Online Booking services (hereafter, and interchangeably, ‘Pre-booking’ and ‘Online Booking’ services, or ‘services’)  with registered offices in established by Public Deed executed before, The terms “You” and “User” are used here to refer to all individuals and/or entities who for whatever reason access www.nh-hotels.com or use its services. </p>
<p>
The use of these services implies full and unconditional acceptance and validity of each and all the General Terms and/or Conditions – which are deemed to be automatically incorporated into the contract concluded with NH, without the need for your written transcription in the same – included in the latest version of these General Terms and/or Conditions.
2. USE OF THE NH PRE-BOOKING AND ONLINE BOOKING SERVICES</p>

<h3>Pre-booking Services </h3>
<p>
The online Pre-booking services are for information only, and their sole purpose is to offer the user the possibility of checking the availability of a room in their preferred hotel or city. THE USER WILL RECEIVE AN EMAIL WITHIN 24 HRS, CONFIRMING IF THEIR BOOKING HAS BEEN PROCESSED, AND WILL CONSEQUENTLY ONLY BECOME BINDING ONCE NH HAS ACCEPTED THE RESERVATION BY MEANS OF EMAIL CONFIRMATION AND SUBSEQUENT PAYMENT BY THE USER. The user must verify the booking confirmation and notify NH of any error immediately in writing. 
</p>
<h3>Online Booking Services </h3>

<p>a. The purpose of the Online Booking services is for the reservation of a room in any NH hotel. THE USE OF THESE SERVICES IMPLIES FULL AND UNCONDITIONAL ACCEPTANCE AND VALIDITY OF EACH AND ALL THE GENERAL TERMS AND/OR CONDITIONS – WHICH ARE DEEMED TO BE AUTOMATICALLY INCORPORATED INTO THE CONTRACT CONCLUDED WITH NH, WITHOUT THE NEED FOR YOUR WRITTEN TRANSCRIPTION IN THE SAME – INCLUDED IN THE LATEST VERSION OF THESE GENERAL TERMS AND/OR CONDITIONS. </p>
<p>
b. Procurement procedure: When using the service, the user will receive an email confirmation in which is included the confirmation that their purchase order is in the process of being confirmed. If you are a customer, once the corresponding debit for your reservation has been made, you will receive an email confirming this; this email serves as proof of your booking. </p>
<p>
c. Guarantee: The booking is confirmed and guaranteed overnight with a credit card. In the event of a no-show without prior notice, the first night will be charged (including VAT and taxes). 
</p>
	<p>
d. Termination of the contract or cancellation of the booking: The credit card is solely a means of guarantee. Cancellation of the booking by the user will not incur any charges for advance cancellation, provided that this is done prior to the specified deadline in each country (local time at the hotel) on the day of arrival. Once this threshold has passed, NH will make a cancellation charge as compensation, which will amount to the cost of the first night (including VAT and taxes), with the exception of hotels in Germany, where 10% of the stay may be refunded as compensation for expenses not incurred. 
</p>
	<p>
This clause does not apply to bookings made with special rates. In this case, the respective specified conditions shall apply. 


</p>
              </div>
           </div>
        </div>
      </div>

     <!--  ************* -->

	<script>
		function hideshowgst(data)
		{//('input[name="gstavailable"]:checkbox:checked')
			var ischecked = $('input[name="gstavailable"]:checkbox:checked').length;
			if(ischecked==1)
			{
				$(".showhide").hide();
			}else{
				$(".showhide").show();
			}
		}
		function checknumber(val)
		{
			if(val<15)
			{
				$("input[name='txtTAC']").css("border-color","red");
				$('#submitProperty').attr('disabled',true);
				$(".form4 button[name='next']").attr('disabled',true);
				

			}else{
				$("input[name='txtTAC']").css("border-color","#ccc");
				$('#submitProperty').attr('disabled',false);
				$(".form4 button[name='next']").attr('disabled',false);
				
			}

		}
function calldiv(classname,n)
{
	$(".formss").css("display","none");
	if(classname=='form3')
	{
		$("."+classname).css("display","block");
		$("#tab"+n).attr('value',1);
		$(".tabss a").removeClass("active");
		$("#tab"+n).addClass("active");

	}
	if(classname=='form1')
	{
		$("."+classname).css("display","block");
		$("#tab"+n).attr('value',1);
		$(".tabss a").removeClass("active");
		$("#tab"+n).addClass("active");
	}
	if(classname=='form4')
	{
		var errorMsg = '';
			if(!$('#txtBankAccNo').val())
				errorMsg = errorMsg + 'Enter Bank Account Number<br>';
			if(!$('#txtBankIFSC').val())
				errorMsg = errorMsg + 'Enter Bank IFSC Number<br>';
			if(!$('#txtBeneficiaryName').val())
				errorMsg = errorMsg + 'Enter Beneficiary Name<br>';
			
			
			if(errorMsg){
				$.confirm({
					title: 'Invalid Details!',
					content: '<p>'+errorMsg+'</p>',
					type: 'red',
					typeAnimated: true,
					backgroundDismiss: true,
					buttons: {
						ok: function () {
						}
					}
				});
				$('#submitProperty').attr('disabled',false);
				$(".form3").css("display","block");
				$("#tab"+n).attr('value',0);
				return false;
			}else{
				$('#submitProperty').attr('disabled',true);
				$("."+classname).css("display","block");
				$("#tab"+n).attr('value',1);
				$(".tabss a").removeClass("active");
				$("#tab"+n).addClass("active");
				return true;
			}
	}
	if(classname=='form2')
	{
			var errorMsg = '';
			if(!$('#selPropertyTypeID').val())
				errorMsg = errorMsg + 'Please select Property Type<br>';
			if(!$('#selStarRating').val())
				errorMsg = errorMsg + 'Please select Star Rating<br>';
			
			if(!$('#txtNoOfGuestRooms').val())
				errorMsg = errorMsg + 'Please enter No of Guest Rooms<br>';
			if(!$('#txtPropertyName').val())
				errorMsg = errorMsg + 'Please enter Property Name<br>';
			if(!$('#selStateId').val())
				errorMsg = errorMsg + 'Please select a State<br>';
			if(!$('#selCityId').val())
				errorMsg = errorMsg + 'Please select a City<br>';
			if(!$('#selLandmark').val())
				errorMsg = errorMsg + 'Please Select Landmark<br>';
			if(!$('#txtAddress1').val())
				errorMsg = errorMsg + 'Please enter Address<br>';
			
			if(!$('#txtZip').val())
				errorMsg = errorMsg + 'Please enter Pincode<br>';
			if(!$('#txtPhone').val())
				errorMsg = errorMsg + 'Please enter Telephone Number<br>';
			if(!$('#txtMobile').val())
				errorMsg = errorMsg + 'Please enter Mobile Number<br>';
			if(!validateEmail($('#txtEmail').val()))
				errorMsg = errorMsg + 'Please enter valid Email ID<br>';
			if(!$('#txtPropertyDescription').val())
				errorMsg = errorMsg + 'Please enter Property Description<br>';
			
			
			if(!$('input[name="fileuploader-list-propertygallery"]').val())
				errorMsg = errorMsg + '\nUpload Property Images<br>';
			
			if(errorMsg){
				$.confirm({
					title: 'Invalid Details!',
					content: '<p>'+errorMsg+'</p>',
					type: 'red',
					typeAnimated: true,
					backgroundDismiss: true,
					buttons: {
						ok: function () {
						}
					}
				});
				$('#submitProperty').attr('disabled',false);
				$(".form1").css("display","block");
				$("#tab"+n).attr('value',0);
				return false;
			}else{
				$('#submitProperty').attr('disabled',true);
				$("."+classname).css("display","block");
				$("#tab"+n).attr('value',1);
				$(".tabss a").removeClass("active");
				$("#tab"+n).addClass("active");
				return true;
			}
	}
	if(classname=='form5')
	{
		var errorMsg = '';
			if(!$('input[name="txtTAC"]').val())
				errorMsg = errorMsg + 'Enter TAC Percentage<br>';
			
			if(!$('#cancellationpolicy1').val())
				errorMsg = errorMsg + 'Cancellation Policy for More than 7 days before check-in<br>';
			if(!$('#cancellationpolicy2').val())
				errorMsg = errorMsg + 'Cancellation Policy for 7 days before check-in<br>';
			if(!$('#cancellationpolicy3').val())
				errorMsg = errorMsg + 'Cancellation Policy for 3 day before check-in<br>';
			if(!$('#terms_and_conditions0').val())
				errorMsg = errorMsg + 'Enter Terms and Conditions<br>';
			if(errorMsg){
				$.confirm({
					title: 'Invalid Details!',
					content: '<p>'+errorMsg+'</p>',
					type: 'red',
					typeAnimated: true,
					backgroundDismiss: true,
					buttons: {
						ok: function () {
						}
					}
				});
				$('#submitProperty').attr('disabled',false);
				$("#tab"+n).attr('value',0);
				$(".form4").css("display","block");
			return false;
			}else{
				$('#submitProperty').attr('disabled',false);
				$("."+classname).css("display","block");
				$("#tab"+n).attr('value',1);
				$(".tabss a").removeClass("active");
				$("#tab"+n).addClass("active");
				return true;
			}
	}

	if(classname=='form6')
	{
		var errorMsg = '';
			var selectedcat=$("#selectedcat").val();
	selectedcat=selectedcat.split(",");
	var count=selectedcat.length;
	$.each(selectedcat, function( index, value ) {
			$('.roomcategory').each(function() {		    

    		sel=$(this).val();
    		if(sel==value)
	    	{
	    		data=$(this).closest('.datas').attr("data");
	    	}		  
			 	
			});
		var checkkjdsf=breakfast=0;
		
		if(!$('#selRoomCategory'+data).val())
		errorMsg = 'Please choose Room category<br>';
		if(!$('#minNoOfGuest'+data).val())
		errorMsg = errorMsg + '\nPlease Select MIN Number Of Guest<br>';
		if(!$('#selMaxNoOfGuest'+data).val())
		errorMsg = errorMsg + '\nPlease Select MAX Number Of Guest<br>';
		if(!$('#noofRooms'+data).val())
		errorMsg = errorMsg + '\nPlease Enter Total No of Rooms Available<br>';
		if(!$('#selRoomBedSize'+data).val())
		errorMsg = errorMsg + '\nSelect Bed Size<br>';
		if(!$('#txtRoomDescription'+data).val())
		errorMsg = 'Enter Room Description<br>';
		if(!$('input[name="fileuploader-list-roomgallery'+data+'"]').val())
		errorMsg = errorMsg + '\nUpload Room Images<br>';
		$.each($(".data"+data+" .breakfast:checked"), function() {
				breakfast++;
			});
			
			if(breakfast==0)
			{
				errorMsg = errorMsg + '\n Check Any of the Breakfast Option<br>';
			}
		if($('#selRoomCategory'+data).val()==13)
		{
			if(!$("#otherCategory"+data).val())
				errorMsg = errorMsg + '\nEnter Category Name<br>';
		}else{
			$.each($(".data"+data+" .id_room_types:checked"), function() {
				checkkjdsf++;
			});
			
			if(checkkjdsf==0)
			{
				errorMsg = errorMsg + '\n Select any Room Types<br>';
			}
		}
		
		});
		
			if(errorMsg){
				$.confirm({
					title: 'Invalid Details!',
					content: '<p>'+errorMsg+'</p>',
					type: 'red',
					typeAnimated: true,
					backgroundDismiss: true,
					buttons: {
						ok: function () {
						}
					}
				});
				$('#submitProperty').attr('disabled',false);
				$(".form5").css("display","block");
				$("#tab"+n).attr('value',0);
			return false;
			}else{
				$(".tabss a").removeClass("active");
				$("#tab"+n).addClass("active");
// alert("success");
// return false;
	var roomrates=extrabedrates='';
	var selectedcat=$("#selectedcat").val();
	selectedcat=selectedcat.split(",");
	var category = {
		    '1' : 'Standard AC',
				'7' : 'Standard Non-AC',
				'2' : 'Premium AC',
				'8' : 'Premium Non-AC',
				'3' : 'Executive AC',
				'9' : 'Executive Non-AC',
				'4' : 'Suite AC',
				'10' : 'Suite Non-AC',
				'5' : 'Deluxe AC',
				'11' : 'Deluxe Non-AC',
				'6' : 'Superior AC',
				'12' : 'Superior Non-AC',
				'13' : 'Others'
		};
		var arra = {
		    '1': 'Single',
		    '2': 'Double',
		    '3': 'Triple',
		    '4': 'Quadruple',
		};
		var s=1;
		var sel='';
		var data="";
		$.each(selectedcat, function( index, value ) {
			$('.roomcategory').each(function() {		    

    		sel=$(this).val();
    		if(sel==value)
	    	{
	    		data=$(this).closest('.datas').attr("data");
	    	}		  
			 	
			});
			var category_name='';
			var name="";
			var brfat='';
			breakfst = new Array();
			$.each($(".data"+data+" .breakfast:checked"), function() {
				brfat=$(this).val();
				breakfst.push(brfat);
			});
			console.log(breakfst);
			console.log(breakfst.includes("1"));
			console.log(breakfst.includes("2"));

			if(value!='13')
			{
				
			  $.each($(".data"+data+" .id_room_types:checked"), function() {			  	
					var option=$(this).val();	
					var name=arra[option];
					category_name=category[value];
					name=name+' ';
					//'+(breakfst.includes("1"))?"inline":""+'
					var display=(breakfst.includes("1"))?"table-row":"none";
					roomrates+='<tr style="display:'+display+'"><td><br><em>('+category_name+')</em></td><td>'+name+'</td><td>With Breakfast</td><td><input type="text" name="txtMasterRate['+value+']['+option+'][with][]" size="17" maxlength="5" value="" onkeypress="return event.charCode >= 48 && event.charCode <= 57;"></tr>';
					var display=(breakfst.includes("2"))?"table-row":"none";
					roomrates+='<tr style="display:'+display+'"><td><br><em>('+category_name+')</em></td><td>'+name+'</td><td>Without Breakfast</td><td><input type="text" name="txtMasterRate['+value+']['+option+'][without][]" size="17" maxlength="5" value="" onkeypress="return event.charCode >= 48 && event.charCode <= 57;"></tr>';

					
			//	s++;
				});
			}
			else
			{


				var option=$(".data"+data+" #otherCategory"+data).val();
				name=option;
				option=option.split(' ');
				option=option.join('_');
				category_name=category[value];
				var display=(breakfst.includes("1"))?"table-row":"none";
				roomrates+='<tr style="display:'+display+'"><td ><br><em>('+category_name+': '+name+')</em></td><td>'+name+'</td><td>With Breakfast</td><td><input type="text" name="txtMasterRate['+value+']['+option+'][with][]" size="17" maxlength="5" value="" onkeypress="return event.charCode >= 48 && event.charCode <= 57;"></td></tr>';
				var display=(breakfst.includes("2"))?"table-row":"none";
				roomrates+='<tr style="display:'+display+'"><td ><br><em>('+category_name+': '+name+')</em></td><td>'+name+'</td><td>Without Breakfast</td><td><input type="text" name="txtMasterRate['+value+']['+option+'][without][]" size="17" maxlength="5" value="" onkeypress="return event.charCode >= 48 && event.charCode <= 57;"></td></tr>';
				

			}
			extrabedrates+='<tr><td><br><em>'+category_name+'('+name+')</em></td><td><div class="col-lg-6"><input onkeypress="return event.charCode >= 48 && event.charCode <= 57;" type="text" name="txtExtraBedAdult['+value+']" size="17" maxlength="5" value=""></div></td><td><div class="col-lg-6"><input onkeypress="return event.charCode >= 48 && event.charCode <= 57;" type="text" name="txtExtraBedMoreThanFive['+value+']" size="17" maxlength="5" value=""></div></td></tr>';
		});
				
				
				$(".roomrates").html(roomrates);
				$(".extrabedrates").html(extrabedrates);
				$('#submitProperty').attr('disabled',false);
				$("."+classname).css("display","block");
				return true;
			}
	}
}

$('.switchMealPlans').click(function(){
			if($(this).prop("checked"))
				$("#mealPlansTable").find("input").prop("disabled", false);
			else
				$("#mealPlansTable").find("input").prop("disabled", true);
		});
	function AddRoom(num)
	{
		var selectedcat=$("#selectedcat").val();
		selectedcat = selectedcat.split(",");
		//selectedcat=selectedcat.join(',');
		//$("#selectedcat").val(selectedcat);

		var addroom='<div data="'+num+'" class="data'+num+' datas" ><div class="col-sm-12 " style="margin:20px 0;padding:0;"><div class="row"><div class="col-sm-12" style="padding:0;"><button style="float:right;" class="button_addremove" type="buutton" value="'+num+'" onclick="removeroom(this.value);"><i class="fa fa-window-close"></i></button></div></div><div class="col-xs-12 col-sm-4 col-md-4"><label class="control-label  required">Room Category</label>';
		addroom+='<div >';
				addroom+='<select class="roomcategory form-control" name="selRoomCategory['+num+']" id="selRoomCategory'+num+'"  onchange="javascript:getMaxOccupancy(this.value,'+num+');">';
					addroom+='<option value="">-Choose-</option>';
					addroom+='<?php foreach($roomCategory as $key => $val){ ?>';
					var key="<?php echo $key; ?>";
					var disabled='';
					if($.inArray(key, selectedcat) >  -1)
					{

						disabled='disabled';
					}
					addroom+='<option value="<?php echo $key; ?>"  '+disabled+' ><?php echo $val;?></option>';
					addroom+='<?php } ?>';
				addroom+='</select>';
				
			addroom+='</div>';
		
		addroom+='</div>';
		addroom+='<div class="col-xs-12 col-sm-4 col-md-4 othercat" style="display: none">';
			addroom+='<label class="control-label  required">Enter Category Name</label>';
			addroom+='<div >';
			addroom+='<input type="text" value="" name="otherCategory['+num+']" id="otherCategory'+num+'" >';
			addroom+='</div>';
		addroom+='</div>';
		addroom+='<div class="col-xs-12 col-sm-9 idroomtype">';
			addroom+='<label class="control-label ">Room Type</label>';
			addroom+='<div ><div class="row">';
				addroom+='<div class="col-sm-3"><input type="checkbox" class="id_room_types" name="id_room_type['+num+'][]" value="1" ><label class="control-label" >Single</label></div>';
  				addroom+='<div class="col-sm-3"><input type="checkbox" class="id_room_types" name="id_room_type['+num+'][]" value="2" ><label class="control-label" >Double</label></div>';
  				addroom+='<div class="col-sm-3"><input type="checkbox" class="id_room_types" name="id_room_type['+num+'][]" value="3" ><label class="control-label" >Triple</label></div>';
  				addroom+='<div class="col-sm-3"><input type="checkbox" class="id_room_types" name="id_room_type['+num+'][]" value="4" ><label class="control-label" >Quadruple</label></div>';

			addroom+='</div></div>';
		addroom+='</div><div class="clearfix"></div>';
		
		addroom+='<div class="col-xs-12 col-sm-4 col-md-4">';
			addroom+='<label class="control-label ">Room Display Name</label>';
			addroom+='<div >';
				addroom+='<input type="text" name="txtRoomName['+num+']" id="txtRoomNames'+num+'" size="40" maxlength="40" value="">';
				addroom+='<span>(Max. 40 Chars)</span>';
			addroom+='</div>';
		addroom+='</div>';
		addroom+='<div class="max_count_details">';
			addroom+='<div class="col-xs-12 col-sm-6 "><div >';
				
				addroom+='<div class="pull-left width50_percent" >';
				addroom+='<label class="control-label  required">Min. Guest Allowed</label>';
					addroom+='<select class="form-control px_width60" name="minNoOfGuest['+num+']" id="minNoOfGuest'+num+'">';
						addroom+='<?php for($i=1; $i<=15;$i++){ ?>';
							addroom+='<option value="<?php echo $i;?>"><?php echo $i;?></option>';
						addroom+='<?php } ?>';
						
					addroom+='</select>';
				addroom+='</div>';
			addroom+='</div>';
			addroom+='<div > ';
				
				addroom+='<div class="pull-left width50_percent" >';
				addroom+='<label class="control-label  required">Max. Guests Allowed</label>';
					addroom+='<select  class="form-control px_width60" name="selMaxNoOfGuest['+num+']" id="selMaxNoOfGuest'+num+'">';
						addroom+='<?php for($i=1; $i<=15;$i++){ ?>';
							addroom+='<option value="<?php echo $i;?>"><?php echo $i;?></option>';
						addroom+='<?php } ?>';
					addroom+='</select>';
				addroom+='</div></div>';
			addroom+='</div><div class="clearfix"></div>';
			
			addroom+='<div class="col-xs-12 col-sm-4 col-md-4">';
				addroom+='<label class="control-label required">Total No of Rooms</label>';
				addroom+='<div >';
					addroom+='<input type="text" name="noofRooms['+num+']" value="" id="noofRooms'+num+'" onkeypress="return event.charCode >= 48 && event.charCode <= 57;">';
				addroom+='</div>';
			addroom+='</div>';
			addroom+='<div class="col-xs-12 col-sm-4 col-md-4">';
				addroom+='<label class="control-label required ">Size of Bed(s)</label>';
				addroom+='<div >';
					addroom+='<select class="form-control" name="selRoomBedSize['+num+']" id="selRoomBedSize'+num+'">';
						addroom+='<option value="">-Choose-</option>';
					addroom+='<?php foreach($roomBedSize as $key => $val){ ?>';
					addroom+='<option value="<?php echo $key; ?>"><?php echo $val;?></option>';
					addroom+='<?php } ?>';
					addroom+='</select>';
				addroom+='</div>';
			addroom+='</div>';
			addroom+='<div class="col-xs-12 col-sm-4 col-md-4">';
				addroom+='<label class="control-label required ">Size of Bed(s)</label>';
				addroom+='<div >';
					addroom+='<label class="control-label required">Breakfast Inclusion</label>';
					addroom+='<input type="checkbox" name="is_breakfast['+num+'][1]" class="breakfast" value="1" >Include';
  				addroom+='<input type="checkbox" name="is_breakfast['+num+'][2]" class="breakfast" value="2" >Not Include';
				addroom+='</div>';
			addroom+='</div>';
		addroom+='</div>';
		addroom+='<div class="clearfix"></div><div class="col-sm-12" >';
			addroom+='<div class="panel-heading">Room Amenities</div>';
			addroom+='<div class="col-sm-12 border_for_inner" >';
				addroom+='<div class="row"><div >';

					addroom+='<div class="col-xs-12 col-sm-4 col-md-4">';
								addroom+='<input type="checkbox" name="roomamentities['+num+'][medical]" id="medical'+num+'">';
								addroom+='<label class="control-label" for="medical'+num+'">Medical</label>';
					addroom+='</div>';
					addroom+='<div class="col-xs-12 col-sm-4 col-md-4">';
						addroom+='<input type="checkbox" name="roomamentities['+num+'][ac]" id="ac'+num+'">';
						addroom+='<label class="control-label" for="ac'+num+'">AC</label>';
					addroom+='</div>';
					addroom+='<div class="col-xs-12 col-sm-4 col-md-4">';
						addroom+='<input type="checkbox" name="roomamentities['+num+'][cable_tv]" id="cable_tv'+num+'">';
						addroom+='<label class="control-label" for="cable_tv'+num+'">Cable TV</label>';
					addroom+='</div>';
				addroom+='</div>';
				addroom+='<div >';
					addroom+='<div class="col-xs-12 col-sm-4 col-md-4">';
						addroom+='<input type="checkbox" name="roomamentities['+num+'][direct_phone]" id="direct_phone'+num+'">';
						addroom+='<label class="control-label" for="direct_phone'+num+'">Direct Phone</label>';
				addroom+='</div>';
					addroom+='<div class="col-xs-12 col-sm-4 col-md-4">';
						addroom+='<input type="checkbox" name="roomamentities['+num+'][channel_music]" id="channel_music'+num+'">';
						addroom+='<label class="control-label" for="channel_music'+num+'">Channel Music</label>';
					addroom+='</div>';
				addroom+='</div>';
				addroom+='<div >';
					addroom+='<div class="col-xs-12 col-sm-4 col-md-4">';
						addroom+='<input type="checkbox" name="roomamentities['+num+'][attached_bathroom]" id="attached_bathroom'+num+'">';
						addroom+='<label class="control-label" for="attached_bathroom'+num+'">Attached Bathroom</label>';
					addroom+='</div>';
					addroom+='<div class="col-xs-12 col-sm-4 col-md-4">';
						addroom+='<input type="checkbox" name="roomamentities['+num+'][shower]" id="shower'+num+'">';
						addroom+='<label class="control-label" for="shower'+num+'">Shower</label>';
					addroom+='</div>';
				addroom+='</div>';
				addroom+='<div >';
					addroom+='<div class="col-xs-12 col-sm-4 col-md-4">';
						addroom+='<input type="checkbox" name="roomamentities['+num+'][bath_tub]" id="bath_tub'+num+'">';
						addroom+='<label class="control-label" for="bath_tub'+num+'">Bath Tub</label>';
					addroom+='</div>';
					addroom+='<div class="col-xs-12 col-sm-4 col-md-4">';
						addroom+='<input type="checkbox" name="roomamentities['+num+'][shower_bath_tub]" id="shower_bath_tub'+num+'">';
						addroom+='<label class="control-label" for="shower_bath_tub'+num+'">Shower/Bath Tub</label>';
					addroom+='</div>';
				addroom+='</div>';
				addroom+='<div >';
					addroom+='<div class="col-xs-12 col-sm-4 col-md-4">';
						addroom+='<input type="checkbox" name="roomamentities['+num+'][minibar]" id="minibar'+num+'">';
						addroom+='<label class="control-label" for="minibar'+num+'">Minibar</label>';
					addroom+='</div>';
					addroom+='<div class="col-xs-12 col-sm-4 col-md-4">';
						addroom+='<input type="checkbox" name="roomamentities['+num+'][work_desk]" id="work_desk'+num+'">';
						addroom+='<label class="control-label" for="work_desk'+num+'">Work Desk</label>';
					addroom+='</div>';
				addroom+='</div>';
				addroom+='<div >';
					addroom+='<div class="col-xs-12 col-sm-4 col-md-4">';
					addroom+='	<input type="checkbox" name="roomamentities['+num+'][balcony]" id="balcony'+num+'">';
						addroom+='<label class="control-label" for="balcony'+num+'">Balcony</label>';
					addroom+='</div>';
					addroom+='<div class="col-xs-12 col-sm-4 col-md-4">';
						addroom+='<input type="checkbox" name="roomamentities['+num+'][radio]" id="radio'+num+'">';
					addroom+='	<label class="control-label" for="radio'+num+'">Radio</label>';
					addroom+='</div>';
				addroom+='</div>';
				addroom+='<div >';
					addroom+='<div class="col-xs-12 col-sm-4 col-md-4">';
					addroom+='	<input type="checkbox" name="roomamentities['+num+'][clock]" id="clock'+num+'">';
					addroom+='	<label class="control-label" for="clock'+num+'">Clock</label>';
					addroom+='</div>';
					addroom+='<div class="col-xs-12 col-sm-4 col-md-4">';
					addroom+='	<input type="checkbox" name="roomamentities['+num+'][hair_dryer]" id="hair_dryer'+num+'">';
					addroom+='<label class="control-label" for="hair_dryer'+num+'">Hair Dryer</label>';
					addroom+='</div>';
				addroom+='</div>';
				addroom+='<div >';
				addroom+='	<div class="col-xs-12 col-sm-4 col-md-4">';
					addroom+='	<input type="checkbox" name="roomamentities['+num+'][fire_place]" id="fire_place'+num+'">';
					addroom+='	<label class="control-label" for="fire_place'+num+'">Fire Place</label>';
					addroom+='</div>';
					addroom+='<div class="col-xs-12 col-sm-4 col-md-4">';
					addroom+='	<input type="checkbox" name="roomamentities['+num+'][safe_deposit_boxs]" id="safe_deposit_boxs'+num+'">';
					addroom+='	<label class="control-label" for="safe_deposit_boxs'+num+'">Safe Deposit Box</label>';
					addroom+='</div>';
				addroom+='</div>';
				addroom+='<div >';
					addroom+='<div class="col-xs-12 col-sm-4 col-md-4">';
						addroom+='<input type="checkbox" name="roomamentities['+num+'][smoke_alarms]" id="smoke_alarms'+num+'">';
						addroom+='<label class="control-label" for="smoke_alarms'+num+'">Smoke alarms</label>';
					addroom+='</div>';
					addroom+='<div class="col-xs-12 col-sm-4 col-md-4">';
						addroom+='<input type="checkbox" name="roomamentities['+num+'][sprinklers]" id="sprinklers'+num+'">';
						addroom+='<label class="control-label" for="sprinklers'+num+'">Sprinklers</label>';
					addroom+='</div>';
				addroom+='</div>';
				addroom+='<div >';
					addroom+='<div class="col-xs-12 col-sm-4 col-md-4">';
						addroom+='<input type="checkbox" name="roomamentities['+num+'][double_bed]" id="double_bed'+num+'">';
					addroom+='	<label class="control-label" for="double_bed'+num+'">Double Bed</label>';
					addroom+='</div>';
					addroom+='<div class="col-xs-12 col-sm-4 col-md-4">';
						addroom+='<input type="checkbox" name="roomamentities['+num+'][king_bed]" id="king_bed'+num+'">';
						addroom+='<label class="control-label" for="king_bed'+num+'">King Bed</label>';
					addroom+='</div>';
				addroom+='</div>';
				addroom+='<div >';
					addroom+='<div class="col-xs-12 col-sm-4 col-md-4">';
						addroom+='<input type="checkbox" name="roomamentities['+num+'][high_speed_wi_fi_internet_access]" id="high_speed_wi_fi_internet_access'+num+'">';
					addroom+='	<label class="control-label" for="high_speed_wi_fi_internet_access'+num+'">High speed Wi-Fi internet access</label>';
					addroom+='</div>';
					addroom+='<div class="col-xs-12 col-sm-4 col-md-4">';
					addroom+='	<input type="checkbox" name="roomamentities['+num+'][lcd_tv]" id="lcd_tv'+num+'">';
						addroom+='<label class="control-label" for="lcd_tv'+num+'">LCD TV</label>';
					addroom+='</div>';
				addroom+='</div>';
				addroom+='<div >';
					addroom+='<div class="col-xs-12 col-sm-4 col-md-4">';
						addroom+='<input type="checkbox" name="roomamentities['+num+'][sound_proof_windows]" id="sound_proof_windows'+num+'">';
						addroom+='<label class="control-label" for="sound_proof_windows'+num+'">Sound proof windows</label>';
					addroom+='</div>';
					addroom+='<div class="col-xs-12 col-sm-4 col-md-4">';
						addroom+='<input type="checkbox" name="roomamentities['+num+'][24_hour_room_service]" id="24_hour_room_service'+num+'">';
						addroom+='<label class="control-label" for="24_hour_room_service'+num+'">24 hour room service</label>';
					addroom+='</div>';
				addroom+='</div>';
				addroom+='<div >';
					addroom+='<div class="col-xs-12 col-sm-4 col-md-4">';
					addroom+='	<input type="checkbox" name="roomamentities['+num+'][electronic_lock]" id="electronic_lock'+num+'">';
						addroom+='<label class="control-label" for="electronic_lock'+num+'">Electronic lock</label>';
					addroom+='</div>';
					addroom+='<div class="col-xs-12 col-sm-4 col-md-4">';
						addroom+='<input type="checkbox" name="roomamentities['+num+'][electronic_laptop_compatible_safe]" id="electronic_laptop_compatible_safe'+num+'">';
						addroom+='<label class="control-label" for="electronic_laptop_compatible_safe'+num+'">Electronic laptop compatible safe</label>';
					addroom+='</div>';
				addroom+='</div>';
				addroom+='<div >';
					addroom+='<div class="col-xs-12 col-sm-4 col-md-4">';
						addroom+='<input type="checkbox" name="roomamentities['+num+'][marble_flooring]" id="marble_flooring'+num+'">';
						addroom+='<label class="control-label" for="marble_flooring'+num+'">Marble Flooring</label>';
				addroom+='	</div>';
					addroom+='<div class="col-xs-12 col-sm-4 col-md-4">';
					addroom+='	<input type="checkbox" name="roomamentities['+num+'][study_table]" id="study_table'+num+'">';
					addroom+='	<label class="control-label" for="study_table'+num+'">Study Table</label>';
					addroom+='</div>';
				addroom+='</div>';
				addroom+='<div >';
					addroom+='<div class="col-xs-12 col-sm-4 col-md-4">';
					addroom+='	<input type="checkbox" name="roomamentities['+num+'][free_local_phone_calls]" id="free_local_phone_calls'+num+'">';
					addroom+='	<label class="control-label" for="free_local_phone_calls'+num+'">Free local phone calls</label>';
					addroom+='</div>';
					addroom+='<div class="col-xs-12 col-sm-4 col-md-4">';
						addroom+='<input type="checkbox" name="roomamentities['+num+'][iron_with_ironing_board]" id="iron_with_ironing_board'+num+'">';
					addroom+='	<label class="control-label" for="iron_with_ironing_board'+num+'">Iron with ironing board (on request)</label>';
					addroom+='</div>';
				addroom+='</div>';
				addroom+='<div >';
					addroom+='<div class="col-xs-12 col-sm-4 col-md-4">';
						addroom+='<input type="checkbox" name="roomamentities['+num+'][full_length_mirror]" id="full_length_mirror'+num+'">';
						addroom+='<label class="control-label" for="full_length_mirror'+num+'">Full length mirror</label>';
					addroom+='</div>';
					addroom+='<div class="col-xs-12 col-sm-4 col-md-4">';
					addroom+='	<input type="checkbox" name="roomamentities['+num+'][complimentary_toiletries]" id="complimentary_toiletries'+num+'">';
						addroom+='<label class="control-label" for="complimentary_toiletries'+num+'">Complimentary toiletries</label>';
					addroom+='</div>';
				addroom+='</div>';
				addroom+='<div >';
					addroom+='<div class="col-xs-12 col-sm-4 col-md-4">';
						addroom+='<input type="checkbox" name="roomamentities['+num+'][internets]" id="internets'+num+'">';
						addroom+='<label class="control-label" for="internet'+num+'">Internet</label>';
					addroom+='</div>';
					addroom+='<div class="col-xs-12 col-sm-4 col-md-4">';
					addroom+='	<input type="checkbox" name="roomamentities['+num+'][tea_coffee_maker]" id="tea_coffee_maker'+num+'">';
					addroom+='	<label class="control-label" for="tea_coffee_maker'+num+'">Tea/Coffee Maker</label>';
					addroom+='</div>';
				addroom+='</div>';
				addroom+='<div >';
					addroom+='<div class="col-xs-12 col-sm-4 col-md-4">';
						addroom+='<input type="checkbox" name="roomamentities['+num+'][complimentary_tea_coffee]" id="complimentary_tea_coffee'+num+'">';
						addroom+='<label class="control-label" for="complimentary_tea_coffee'+num+'">Complimentary Tea/Coffee</label>';
					addroom+='</div>';
					addroom+='<div class="col-xs-12 col-sm-4 col-md-4">';
						addroom+='<input type="checkbox" name="roomamentities['+num+'][complimentary_packed_water_bottles]" id="complimentary_packed_water_bottles'+num+'">';
						addroom+='<label class="control-label" for="complimentary_packed_water_bottles'+num+'">Complimentary Packed Water Bottles</label>';
					addroom+='</div>';
				addroom+='</div>';
				addroom+='<div >';
				addroom+='	<div class="col-xs-12 col-sm-4 col-md-4">';
					addroom+='	<input type="checkbox" name="roomamentities['+num+'][carpet_flooring]" id="carpet_flooring'+num+'">';
						addroom+='<label class="control-label" for="carpet_flooring'+num+'">Carpet Flooring</label>';
					addroom+='</div>';
					addroom+='<div class="col-xs-12 col-sm-4 col-md-4">';
						addroom+='<input type="checkbox" name="roomamentities['+num+'][complimentary_fruit_basket]" id="complimentary_fruit_basket'+num+'">';
						addroom+='<label class="control-label" for="complimentary_fruit_basket'+num+'">Complimentary Fruit Basket</label>';
					addroom+='</div>';
				addroom+='</div>';
				addroom+='<div >';
				addroom+='	<div class="col-xs-12 col-sm-4 col-md-4">';
						addroom+='<input type="checkbox" name="roomamentities['+num+'][high_speed_wi_fi_internet]" id="high_speed_wi_fi_internet'+num+'">';
						addroom+='<label class="control-label" for="high_speed_wi_fi_internet'+num+'">High Speed Wi-Fi Internet (chargeable)</label>';
					addroom+='</div>';
					addroom+='<div class="col-xs-12 col-sm-4 col-md-4">';
					addroom+='	<input type="checkbox" name="roomamentities['+num+'][double_twin_beds]" id="double_twin_beds'+num+'">';
						addroom+='<label class="control-label" for="double_twin_beds'+num+'">Double/Twin Beds</label>';
					addroom+='</div>';
				addroom+='</div>';
				addroom+='<div >';
					addroom+='<div class="col-xs-12 col-sm-4 col-md-4">';
						addroom+='<input type="checkbox" name="roomamentities['+num+'][direct_dialing_phone]" id="direct_dialing_phone'+num+'">';
						addroom+='<label class="control-label" for="direct_dialing_phone'+num+'">Direct-Dialing Phone</label>';
					addroom+='</div>';
					addroom+='<div class="col-xs-12 col-sm-4 col-md-4">';
						addroom+='<input type="checkbox" name="roomamentities['+num+'][smoke_detector_alarms]" id="smoke_detector_alarms'+num+'">';
						addroom+='<label class="control-label" for="smoke_detector_alarms'+num+'">Smoke Detector Alarms</label>';
					addroom+='</div></div>';
				addroom+='</div>';
			addroom+='</div>';
		addroom+='</div>';
		addroom+='<div class="clearfix"></div><div class="col-sm-12" >';
			addroom+='<div class="panel-heading">Room Views</div>';
			addroom+='<div class="col-sm-12 border_for_inner" >';
				addroom+='<div class="row"><div  >';
					addroom+='<div class="col-xs-12 col-sm-4 col-md-4">';
					addroom+='<input type="checkbox" name="roomamentities['+num+'][on_the_bay]" id="on_the_bay'+num+'">';
					addroom+='	<label class="control-label" for="on_the_bay'+num+'">On the bay</label>';
					addroom+='</div>';
					addroom+='<div class="col-xs-12 col-sm-4 col-md-4">';
					addroom+='	<input type="checkbox" name="roomamentities['+num+'][on_the_beach]" id="on_the_beach'+num+'">';
					addroom+='	<label class="control-label" for="on_the_beach'+num+'">On the beach</label>';
					addroom+='</div>';
				addroom+='</div>';
				addroom+='<div >';
					addroom+='<div class="col-xs-12 col-sm-4 col-md-4">';
					addroom+='	<input type="checkbox" name="roomamentities['+num+'][on_the_garden]" id="on_the_garden'+num+'">';
					addroom+='	<label class="control-label" for="on_the_garden'+num+'">On the garden</label>';
					addroom+='</div>';
					addroom+='<div class="col-xs-12 col-sm-4 col-md-4">';
						addroom+='<input type="checkbox" name="roomamentities['+num+'][on_the_lake]" id="on_the_lake'+num+'">';
					addroom+='	<label class="control-label" for="on_the_lake'+num+'">On the lake</label>';
				addroom+='	</div>';
				addroom+='</div>';
				addroom+='<div >';
					addroom+='<div class="col-xs-12 col-sm-4 col-md-4">';
						addroom+='<input type="checkbox" name="roomamentities['+num+'][on_the_ocean]" id="on_the_ocean'+num+'">';
						addroom+='<label class="control-label" for="on_the_ocean'+num+'">On the ocean</label>';
					addroom+='</div>';
					addroom+='<div class="col-xs-12 col-sm-4 col-md-4">';
						addroom+='<input type="checkbox" name="roomamentities['+num+'][on_the_park]" id="on_the_park'+num+'">';
						addroom+='<label class="control-label" for="on_the_park'+num+'">On the park</label>';
					addroom+='</div>';
				addroom+='</div>';
				addroom+='<div >';
					addroom+='<div class="col-xs-12 col-sm-4 col-md-4">';
						addroom+='<input type="checkbox" name="roomamentities['+num+'][on_the_river]" id="on_the_river'+num+'">';
						addroom+='<label class="control-label" for="on_the_river'+num+'">On the river</label>';
					addroom+='</div>';
					addroom+='<div class="col-xs-12 col-sm-4 col-md-4">';
						addroom+='<input type="checkbox" name="roomamentities['+num+'][poolside_room]" id="poolside_room'+num+'">';
						addroom+='<label class="control-label" for="poolside_room'+num+'">Poolside Room</label>';
					addroom+='</div>';
				addroom+='</div>';
				addroom+='<div >';
					addroom+='<div class="col-xs-12 col-sm-4 col-md-4">';
						addroom+='<input type="checkbox" name="roomamentities['+num+'][garden_room]" id="garden_room'+num+'">';
						addroom+='<label class="control-label" for="garden_room'+num+'">Garden Room</label>';
					addroom+='</div>';
					addroom+='<div class="col-xs-12 col-sm-4 col-md-4">';
						addroom+='<input type="checkbox" name="roomamentities['+num+'][city_view]" id="city_view'+num+'">';
						addroom+='<label class="control-label" for="city_view'+num+'">City view</label>';
					addroom+='</div>';
				addroom+='</div>';
				addroom+='<div >';
					addroom+='<div class="col-xs-12 col-sm-4 col-md-4">';
						addroom+='<input type="checkbox" name="roomamentities['+num+'][mountain_view]" id="mountain_view'+num+'">';
						addroom+='<label class="control-label" for="mountain_view'+num+'">Mountain view</label>';
					addroom+='</div>';
					addroom+='<div class="col-xs-12 col-sm-4 col-md-4">';
					addroom+='	<input type="checkbox" name="roomamentities['+num+'][sea_facing_room]" id="sea_facing_room'+num+'">';
					addroom+='	<label class="control-label" for="sea_facing_room'+num+'">Sea Facing Room</label>';
				addroom+='	</div>';
			addroom+='	</div>';
		addroom+='	</div>';
		addroom+='</div>';
		addroom+='<div class="row"><div class="col-sm-6">';
		addroom+='	<label class="control-label  required">Room Description</label>';
			addroom+='<div >';
			addroom+='	<textarea name="txtRoomDescription['+num+']" id="txtRoomDescription'+num+'" class="autoload_rte"></textarea>';
		addroom+='	</div>';
		addroom+='</div>';
		addroom+='<div class="col-sm-6">';
			addroom+='<div >';
			addroom+='	<label class="control-label">Photo Gallery</label>';
				addroom+='<div >';
				addroom+='<input type="file" name="roomgallery'+num+'" class="roomgallery'+num+'" >';
			addroom+='</div>';
		addroom+='</div></div>';
		addroom+='</div></div></div></div>';

		$(".forMoreRoom").append(addroom);
		setTimeout(function(){

			$(".forMoreRoom .roomgallery"+num).fileuploader({
	        addMore: true
	        
	    }); 
	num=parseInt(num)+1 ;	$(".add_room").val(num);	

	}, 1000);


		
	}
	function getMaxOccupancy(room_category,num=0){

		var selectedcat=$("#selectedcat").val();
		selectedcat = selectedcat.split(",");
	
		if(room_category)
		{
			$(".submitRoom").removeAttr("disabled");
			if(room_category==13)
			{				
					$('.data'+num+' input[type="checkbox"]').removeAttr('checked');
					$(".data"+num+" .othercat").css("display","block");
					$(".data"+num+" .othercat input").attr("required","true");
					$(".data"+num+" .idroomtype").css("display","none");
					$(".data"+num+" .othercat input").attr("required");
					$(".data"+num+" .id_room_types").removeAttr("required");		

				
			}else{
								
					$(".data"+num+" .idroomtype").css("display","block");
					$(".data"+num+" .othercat").css("display","none");
					$(".data"+num+" .othercat input").removeAttr("required");	
					$(".data"+num+" .id_room_types").attr("required");		
			}
		
		

			
		}
		selectedcat.push(room_category);
		selectedcat=selectedcat.filter(function(entry) { return entry.trim() != ''; });
		selectedcat=selectedcat.join(',');
		$("#selectedcat").val(selectedcat);
		
		//$("#selRoomCategory"+num).prop('disabled', 'disabled');
	}
	function removeroom(val)
	{
		
		var room_category=$(".data"+val+" select.roomcategory").val();
		$("select.roomcategory").each(function(index){		
			$(this).find("option[value='" + room_category + "']").removeAttr('disabled');
		   
		});
		var selectedcat=$("#selectedcat").val();
		selectedcat = selectedcat.split(",");
		selectedcat=removeA(room_category, selectedcat);
		selectedcat=selectedcat.filter(function(entry) { return entry.trim() != ''; });
		selectedcat=selectedcat.join(',');
		$("#selectedcat").val(selectedcat);
		$(".data"+val).remove();
		return false;
	
	}
		function removeA(cat,selectedcat) {
		   var index = selectedcat.indexOf(cat);
			if (index >= 0) {
			  selectedcat.splice( index, 1 );
			}
			return selectedcat;
		}
		function getCityByState(id_state){
			$.ajax({
				url: "hotel_signup.php?ajaxAction=getCityByState",
				data: "id_state="+id_state+"&id_city="+"<?php echo (isset($_POST['selCityId']) ? $_POST['selCityId'] : ''); ?>",
				type: "POST",
				success: function(r){
					$('#selCityId').html(r);
				}}
			);
		}
		function getLandmark(id_city)
		{
			$.ajax({
				url: "hotel_signup.php?ajaxAction=getLandmark",
				data: "selCityId="+id_city+"&id_landmark="+"<?php echo (isset($_POST['id_landmark']) ? $_POST['id_landmark'] : ''); ?>",
				type: "POST",
				success: function(r){
					$('#selLandmark').html(r);
				}}
			);
		}
		function validateEmail(email) {
            var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            return re.test(email);
        }
		function validateForm(){
				return true;
		}
		$( document ).ready(function() {
			setTimeout(function(){$('#selStateId').trigger('change');}, 2000);
			<?php if(!empty($errorMessage)){ ?>
			var errMsg = '<?php echo $errorMessage; ?>';
			$.confirm({
				title: '',
				content: '<p><?php echo $errorMessage; ?></p>',
				type: 'red',
				typeAnimated: true,
				buttons: {
					ok: function () {
					}
				}
			});
			<?php } ?>
		});
	function openform(formnumber)
	{
		var formnum=$("#tab"+formnumber).attr('value');
		if(formnum==1)
		{
			$(".formss").hide();
			$(".tabss a").removeClass("active");
			$("#tab"+formnumber).addClass("active");
			$(".form"+formnumber).show();
			

			
		}

	}
	</script>
<?php
include("include/footer.php")
?>