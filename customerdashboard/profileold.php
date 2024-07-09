<?php include('include/header.php')?>	

		<?php
			 $refid =$_SESSION['refid'];
			$get_agents=$database->query("select * from agents where refid='$refid'")->fetchAll();
		foreach($get_agents as $agents)
		{
			$agentname=$agents['agentname'];
			$registerno=$agents['registerno'];
			$contactperson=$agents['contactperson'];
			$landlineno=$agents['landlineno'];
			$mobileno=$agents['mobileno'];
			$fax=$agents['fax'];
			$emailid=$agents['emailid'];
			$websitelink=$agents['websitelink'];
			$address=$agents['address'];
			$city=$agents['city'];
			$pincode=$agents['pincode'];
			$state=$agents['state'];
			$country=$agents['country'];
			$alternateno=$agents['alternateno'];
			
			
		}
			
			?>
		
        <div id="page-wrapper">
        <div class="graphs">
		<div class="xs">
		 <h4>Manage Your Profile Settings</h4>
		<form id="defaultForm" method="post"  class="form-horizontal">
   
	
	<div class="form-group">
  <div class="col-md-6 ">
			
			<label>Agent Name</label>
				<input type="text" class="form-control"  name="agentname" id="agentname"  value="<?php echo $agentname ?>" disabled />
				
			
			</div>
			

	<div class="col-md-6">		
			<label>Registration Number</label>
				<input class="form-control" type="text" id="regno" name="regno" value="<?php echo $registerno ?>" disabled />
			</div>
</div>

<div class="form-group">
  <div class="col-md-6 ">
			
			<label>Contact Person<span class="cedit">(Edit)</span></label>
				<input class="form-control1" type="text" id="cntprsn" name="cntprsn" value="<?php echo $contactperson ?>" disabled/>
			</div>
			

	<div class="col-md-6">		
			<label>Landline Number<span class="cedit">(Edit)</span></label>
				<input class="form-control1" type="text" id="landno" name="landno" value="<?php echo $landlineno ?>"/>
			</div>
</div>




<div class="form-group">
  <div class="col-md-6 ">
			
				<label>Mobile Number<span class="cedit">(Edit)</span></label>
				<input class="form-control1" type="text" id="mobno" name="mobno" value="<?php echo $mobileno ?>" />
			</div>
			

	<div class="col-md-6">		
			<label>Mobile alternate Number<span class="cedit">(Edit)</span></label>
				<input class="form-control1" type="text" id="altmobno" name="altmobno" value="<?php echo $alternateno ?>"/>
			</div>
</div>



<div class="form-group">
  <div class="col-md-6 ">
			
			<label>Fax</label>
				<input class="form-control" type="text" id="fax" name="fax" disabled="disabled" value="<?php echo $fax ?>" disabled/>
			</div>
			

	<div class="col-md-6">		
			<label>Email id<span class="cedit">(Edit)</span></label>
				<input class="form-control1" type="text" id="email" name="email"  value="<?php echo $emailid ?>" />
			</div>
</div>



<div class="form-group">
  <div class="col-md-6 ">
			
			<label>Webiste Link</label>
				<input class="form-control" type="text" id="weburl" name="weburl" disabled="disabled" value="<?php echo $websitelink ?>" disabled/>
			</div>
			

	<div class="col-md-6">		
			<label>Address</label>
				<input class="form-control" type="text" id="addrss" name="addrss" disabled="disabled" value="<?php echo $address ?>" disabled/>
			</div>
</div>



<div class="form-group">
  <div class="col-md-6 ">
			
			<label>City</label>
				<input class="form-control" type="text" id="city" name="city" disabled="disabled" value="<?php echo $city ?>" disabled/>
			</div>
			

	<div class="col-md-6">		
			<label>Pincode</label>
				<input class="form-control" type="text" id="pincode" name="pincode" disabled="disabled" value="<?php echo $pincode ?>" disabled/>
			</div>
</div>



<div class="form-group">
  <div class="col-md-6 ">
			
			<label>State</label>
				<input class="form-control" type="text" id="state" name="state" disabled="disabled" value="<?php echo $state ?>" disabled/>
			</div>
			

	<div class="col-md-6">		
			<label>Country</label>
				<input class="form-control" type="text" id="cntry" name="cntry" disabled="disabled" value="<?php echo $country ?>" disabled/>
			</div>
</div>
<input type="hidden" id="agentrefid" value="<?php echo $refid; ?>">







<div class="form-group ">
        <div class="col-md-12 btcenter">
            <button type="submit" class="btn cubtn">Update</button>
        </div>
    </div>

   
</form>


		<div class="clearfix"> </div>
	
        </div>
		
 <script type="text/javascript" src="js/bootstrapValidator.js"></script> 
  <script type="text/javascript" src="js/agentpasswdupte.js"></script> 
		<script>
	  
	   $('#defaultForm').bootstrapValidator({
            message: 'This value is not valid',
            //live: 'enabled',
            feedbackIcons: {
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
            },
            submitHandler: function(validator, form) {
               
				//alert("redy");
				 update_agent_details();
				

            },
            fields: {
                email: {
					validators: {
						notEmpty: {
							message: 'The email address is required and can\'t be empty'
						},
						emailAddress: {
							message: 'The input is not a valid email address'
						}
					}
				},
                mobno: {
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
                },
				altmobno: {
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
                },
				landno: {
                    message: 'Field value is not valid',
                    validators: {
                        notEmpty: {
                            message: 'The field is required and can\'t be empty'
                        },
                        stringLength: {
                            min: 11,
                            max: 11,
                            message: 'The field must contain 11 digits'
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
	   

<?php include('include/footer.php')?>
     
	  

	
	 


 
    



  