<?php
	error_reporting(E_ALL);
	include('include/database/config.php');
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
	$errorMessage = '';
	if(isset($_POST['submitPropertyHidden']) && !empty($_POST['submitPropertyHidden'])){
		$emailExists = $database->query('select txtEmail from ps_property where txtEmail=\''.$_POST['txtEmail'].'\' and is_delete=0')->fetchAll();
		if(isset($emailExists[0]) && !empty($emailExists[0])){
			$errorMessage = 'Email already exists!';
		}else{
			unset($_POST['submitProperty'],$_POST['submitPropertyHidden']);
			$values = array_merge($_POST,array('status' => 1,'date_add' => date('Y-m-d H:i:s'),'date_upd' => date('Y-m-d H:i:s')));
			$res = $database->insert('ps_property', $values);
			$submitFlag = 0;
			if($res){
				$lastIDArr = $database->query('select id_property from ps_property order by id_property desc limit 1')->fetchAll();
				if(isset($lastIDArr[0][0]) && !empty($lastIDArr[0][0]))
				$database->insert('ps_property_facility', array('id_property' => $lastIDArr[0][0], 'date_add' => date('Y-m-d H:i:s'), 'date_upd' => date('Y-m-d H:i:s')));
				$message=  file_get_contents('mails/property-submit.html');
				$template_vars = array(
							'{shop_name}' => 'Buddies Technologies..!!',
							'{shop_url}' => 'https://hotel.buddiestechnologies.com/dashboard/',
							'{shop_logo}' => 'https://hotel.buddiestechnologies.com/img/admin/logo.png',
						);
				$message = str_replace(array_keys($template_vars),array_values($template_vars),$message);
				$mail = sendConfirmationMail($_POST['txtEmail'],'Property Submission',$message);//$_POST['txtEmail']
				if($mail)
					$submitFlag = 1;
				$message = 'Greetings from Buddies Technologies! Your property details has been submitted and you will be notified via email once verification process has completed. Thanks and Regards, Buddies Tech Team.';
				if(isset($_POST['txtMobile']) && !empty($_POST['txtMobile']))
					smsAPICall($message,$_POST['txtMobile']);
			}
		}
	}

	$statesList = $database->query("select * from ps_state order by name asc")->fetchAll();
	
	$propertyTypes = array(
				1 => 'Hotel',
				2 => 'Resort',
				3 => 'Appartment',
				4 => 'Villa',
				5 => 'Home Stay',
				6 => 'Dormitory',
				7 => 'Guest House'
			);

	$starRatings = array(
					1 => '1 Star',
					2 => '2 Star',
					3 => '3 Star',
					4 => '4 Star',
					5 => '5 Star',
					6 => '7 Star',
					7 => 'Standard',
					8 => 'Deluxe'
				);
?>


<?php 
$currentpage="signup";
include 'include/header.php';
?>
	<?php if($submitFlag){ ?>
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

			.defaultForm label.required:after {
				content: " *";
				color: red;
				font-size: 14px;
				position: relative;
				line-height: 12px;
			}
			.form-group{
				margin-right:0 !important;
			}
		</style>
<div class="container" style="margin-top: 87px;">
<div class="contain_hotelsignup"  >
	<form id="property_form" class="defaultForm form-horizontal" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" onsubmit="javascript:return validateForm();">
	
	<div class="col-xs-12 col-sm-12 inner_hotelsignup">
	
	<h4 class="sign_up_hot" >Sign Up</h4>
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
			<label class="control-label ">Total No of Guest Rooms</label>
			</p>
			<p class="input_box_bord">
				<input class="form-control"  type="text" name="txtNoOfGuestRooms" id="txtNoOfGuestRooms" size="17" maxlength="4" value="<?php echo (isset($_POST['txtNoOfGuestRooms']) ? $_POST['txtNoOfGuestRooms'] : ''); ?>">
				<label>(Max. 4 Digits)</label></p>
			
		</div></div>
		<div class="clearfix"></div>
		<div class="col-xs-12 col-sm-4 ">
		<div class="inner_sm_class">
		<p class="heading_for_input">

			<label class="control-label required">Property Name</label>
				</p>
			<p class="input_box_bord">
			
				<input class="form-control"  type="text" name="txtPropertyName" id="txtPropertyName" size="40" maxlength="128" value="<?php echo (isset($_POST['txtPropertyName']) ? $_POST['txtPropertyName'] : ''); ?>">
				<label>(Max. 128 Chars)</label>
				</p>
			
		</div>
		</div>
		<div class="col-xs-12 col-sm-8 	">
		<div class="inner_sm_class">
		<p class="heading_for_input">
			<label class="control-label  required">Location</label></p>
			
			<p class="input_box_bord form-inline">
				<select style="width:auto;" class="form-control"  name="selCountryId" id="selCountryId">
					<option value="110">India</option>
				</select>
			
				<select  class="form-control half_inputs"  name="selStateId" id="selStateId" onchange="javascript:getCityByState(this.value);">
					<option value="">Select</option>
					<?php foreach($statesList as $state){ 
						echo '<option value="'.$state['id_state'].'" '.(isset($_POST['selStateId']) && $_POST['selStateId'] == $state['id_state'] ? 'selected' : '').'>'.$state['name'].'</option>';
					} ?>
				</select>
			
				<select  class="form-control half_inputs"  name="selCityId" id="selCityId">
					<option value=""></option>
				</select><br>
		
	<label style="margin-left:5px;width:10%;">Country</label><label class="half_inputs">State</label>
		<label class="half_inputs">City</label></p>
			
		</div>

		</div>


		<div class="col-xs-12 col-sm-4">
	<div class="inner_sm_class">
		<p class="heading_for_input">
			<label class="control-label">Landmark</label>
			</p>
			
			<p class="input_box_bord">
				<input class="form-control"  type="text" name="selLandmark" size="40" maxlength="256" value="<?php echo (isset($_POST['selLandmark']) ? $_POST['selLandmark'] : ''); ?>">
				<label>(Max. 256 Chars)</label></p>
			</div>
		
		</div>
		
		<div class="col-xs-12 col-sm-4 ">
		<div class="inner_sm_class">
		<p class="heading_for_input">
			<label class="control-label required">Address 1</label>
			</p>
			
			<p class="input_box_bord">
				<input class="form-control"  type="text" name="txtAddress1" id="txtAddress1" size="40" maxlength="256" value="<?php echo (isset($_POST['txtAddress1']) ? $_POST['txtAddress1'] : ''); ?>">
				<label>(Max. 256 Chars)</label></p>
			</div>
		
		</div>
		<div class="col-xs-12 col-sm-4">
		<div class="inner_sm_class">
		<p class="heading_for_input">
			<label class="control-label ">Address 2</label>
		</p>
			
			<p class="input_box_bord">
				<input class="form-control"  type="text" name="txtAddress2" size="40" maxlength="256" value="<?php echo (isset($_POST['txtAddress2']) ? $_POST['txtAddress2'] : ''); ?>">
				<label>(Max. 256 Chars)</label></p>
		
		</div>
		</div>
		<div class="col-xs-12 col-sm-4">
	<div class="inner_sm_class">
	<p class="heading_for_input">
			<label class="control-label ">Zip / Pin Code</label>
			</p>
			
			<p class="input_box_bord">
				<input class="form-control"  type="text" name="txtZip" size="40" maxlength="9" value="<?php echo (isset($_POST['txtZip']) ? $_POST['txtZip'] : ''); ?>">
				<label>(Max. 9 Chars)</label></p>
			</div>
		
		</div>
		<div class="col-xs-12 col-sm-4 ">
		<div class="inner_sm_class">
	<p class="heading_for_input">
			<label class="control-label required">Telephone</label>
			</p>
			<p class="input_box_bord">
				<input class="form-control"  type="text" name="txtPhone" id="txtPhone" size="40" maxlength="15" value="<?php echo (isset($_POST['txtPhone']) ? $_POST['txtPhone'] : ''); ?>">
				<label>(Max. 10 Chars)</label></p>
			
		</div>
		</div>
		<div class="col-xs-12 col-sm-4 ">
		<div class="inner_sm_class">
			<p class="heading_for_input">
			<label class="control-label required">Mobile</label>
			</p>
			<p class="input_box_bord">
				<input class="form-control"  type="text" name="txtMobile" id="txtMobile" size="40" maxlength="10" value="<?php echo (isset($_POST['txtMobile']) ? $_POST['txtMobile'] : ''); ?>">
				<label>(Max. 10 Chars)</label></p>
			
		</div>
		</div>
		<div class="col-xs-12 col-sm-4">
		<div class="inner_sm_class">
	<p class="heading_for_input">
			<label class="control-label ">Fax</label>
				</p>
			<p class="input_box_bord">
				<input class="form-control"  type="text" name="txtFax" size="40" maxlength="32" value="<?php echo (isset($_POST['txtFax']) ? $_POST['txtFax'] : ''); ?>">
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
				<input class="form-control" type="text" name="txtEmail" id="txtEmail" size="40" maxlength="256" value="<?php echo (isset($_POST['txtEmail']) ? $_POST['txtEmail'] : ''); ?>"><label>Enter Valid Email Address</label></p>
				
		
		</div>
		</div>
		
		<div class="col-xs-12 col-sm-4 ">
		<div class="inner_sm_class">
		<p class="heading_for_input">
			<label class="control-label required">Property Website</label>
				</p>
			<p class="input_box_bord">
				<input class="form-control"  type="text" name="txtWebSite" id="txtWebSite" size="40" maxlength="256" value="<?php echo (isset($_POST['txtWebSite']) ? $_POST['txtWebSite'] : ''); ?>">
				<label>Enter Valid Site Name</label>
			</p>
		</div>
		</div>
		<div class="col-xs-12 col-sm-8">
			<div class="inner_sm_class">
		<p class="heading_for_input">
			<label class="control-label ">Property Description</label>
			</p>
			<p class="input_box_bord">
			
				<textarea class="form-control"   name="txtPropertyDescription"><?php echo (isset($_POST['txtPropertyDescription']) ? $_POST['txtPropertyDescription'] : ''); ?></textarea>
				<label>(Min. 256 Chars)</label></p>
			</div>
		
		</div>	
		<div class="col-xs-12 col-sm-12 ">
		<div class="form-group" style="text-align:right;">
			
			
				<button type="submit" value="1" name="submitProperty" id="submitProperty" class="btn btn-default submi_hotelsignup">Submit</button>
				<input type="hidden" name="submitPropertyHidden" value="1">
			
		</div>
		</div>
		</div>
		<div class="clearfix"></div>
	</form>
	</div>

		
		
	</div>

		

	<script>
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
		function validateEmail(email) {
            var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            return re.test(email);
        }
		function validateForm(){
			var errorMsg = '';
			if(!$('#selPropertyTypeID').val())
				errorMsg = errorMsg + 'Please select Property Type<br>';
			if(!$('#selStarRating').val())
				errorMsg = errorMsg + 'Please select Star Rating<br>';
			if(!$('#txtPropertyName').val())
				errorMsg = errorMsg + 'Please enter Property Name<br>';
			if(!$('#selStateId').val())
				errorMsg = errorMsg + 'Please select a State<br>';
			if(!$('#selCityId').val())
				errorMsg = errorMsg + 'Please select a City<br>';
			if(!$('#txtAddress1').val())
				errorMsg = errorMsg + 'Please enter Address<br>';
			if(!$('#txtPhone').val())
				errorMsg = errorMsg + 'Please enter Telephone Number<br>';
			if(!$('#txtMobile').val())
				errorMsg = errorMsg + 'Please enter Mobile Number<br>';
			if(!validateEmail($('#txtEmail').val()))
				errorMsg = errorMsg + 'Please enter valid Email ID<br>';
			if(!$('#txtWebSite').val())
				errorMsg = errorMsg + 'Please enter Website URL';
			
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
				return false;
			}else{
				$('#submitProperty').attr('disabled',true);
				return true;
			}
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
	</script>
<?php
include("include/footer.php")
?>