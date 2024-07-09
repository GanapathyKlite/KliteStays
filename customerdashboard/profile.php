<?php
error_reporting(0);
session_start();
//include ('include/header2.php');
include('../include/database/config.php'); 



		
			
	
?>

<!DOCTYPE html>
<html lang="en">
    
<head>
        <title>OTO CABS</title><meta charset="UTF-8" />
        <link rel="shortcut icon" href="images/icon.png" type="image/x-icon" />

        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
		
	<!--	<link href="../css/stylelogin.css" rel="stylesheet">-->

		<!-- <link href="../css/stylelogin.css" rel="stylesheet">
		 
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
<script type="text/javascript" src="https://netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/bootstrapValidator.js"></script>-->
<style >

.has-feedback .form-control {
padding-right: 8.5px;
}

.widget-title {
font-family: 'Open Sans', sans-serif;
background-color: #E7E7E7;
border-radius: 4px;
height: auto;
padding: 5px 5px;
color:black;
margin-bottom: 10px;
}

.content{
	max-width: 98% !important;
position: relative;
background-color: #FFF;
/*padding: 45px;*/
box-shadow: 0px 0px 5px #CCC;
margin: 0px auto;
border-radius: 5px;
min-height:450px;
background:#fff;border:2px solid #c4c4c4;;box-shadow:none;height:auto;margin-top:50px;
overflow:auto !important;
	/*background:rgba(0,0,0,0.2);*/
}

.btn-red{
	display: inline-block;
    padding: 6px 12px;
    margin-bottom: 0px;
    font-size: 14px;
    font-weight: normal;
    line-height: 1.42857;
    text-align: center;
    white-space: nowrap;
    vertical-align: middle;
    cursor: pointer;
    -moz-user-select: none;
    border: 1px solid transparent;
    border-radius: 4px;
	color:#FFF;
background-color: #307dc0;
	}
	
	.ba label{
			font-family: 'Roboto',; sans-serif;
			font-size:12px;
	}
	.addcustomer .form-control{
		
		font-size:14px;
		
	}
	
	
.alertify .ajs-header {
color: #000;
font-weight: 700;

border-radius: 2px 2px 0 0;
}

.alertify .ajs-commands button {
display: none;
width: 10px;
height: 10px;
margin-left: 10px;
padding: 10px;
border: 0;
//background-color: transparent;
background-repeat: no-repeat;
background-position: center;
cursor: pointer;
}
.alertify .ajs-footer {
background: #fbfbfb;

border-radius: 0 0 2px 2px;
}

.alertify .ajs-footer .ajs-buttons .ajs-button.ajs-ok {
color:#fff;
outline: none!important;
border:none!important;
border-radius:4px;
background:#ed1c24!important;
}



.alertify .ajs-footer {
padding: 4px;
margin-left: -24px;
margin-right: -24px;
min-height: 43px;
background-color: #fff;
}

.alertify .ajs-footer .ajs-buttons .ajs-button {
width:100px;
text-transform: uppercase;
}
	
.wwd{
font-size: 15px;
}

</style>
    </head>
    <body>
       
          <?php include('include/header.php');?>		

     <div class="profile_whole">
	<div class="container profile_container">
  
	<!--<div class="logo">
    	<a href="#"><img src="images/logo.png" alt="buddiestourslogo" ></a>
    </div>-->
	
	
		
		<?php
		$refid =$_SESSION['reference'];
			$get_customers=$database->query("select * from ps_customers where reference='$refid'")->fetchAll();
		foreach($get_customers as $customers)
		{
			$username=$customers['username'];
			$password=$customers['password'];
			$first_name=$customers['first_name'];
			$last_name=$customers['last_name'];
			$address=$customers['address'];
			$city=$customers['city'];
			$pincode=$customers['pincode'];
			$pan_no=$customers['pan_no'];
			$id_state=$customers['id_state'];
			$id_country=$customers['id_country'];
			$mobile=$customers['mobile'];
			$email=$customers['email'];
			$id_customer=$customers['id_customer'];				
							
			
			
		}
		?>

	
		
			<input type="hidden" id="pagname" value=<?php echo $pname[0];?>>

		<div class="col-xs-12 ">
			
						
			
				<div class="row  " >
						<legend >Customer Details</legend>
				</div>
		</div>

	<form name="" id="defaultForm" action="">	
	
		<div class="row  ">

				
				<div class="col-sm-1"></div>
				<div class="col-xs-12 col-sm-4 ">
				<label>First_name<span>*</span></label>
				<input class="form-control cc" type="text" id="first_name" name="first_name"  value="<?php echo $first_name ?>"  />
				</div>
				
				<div class="col-sm-1"></div>


			<div class="col-xs-12 col-sm-4  ">
				<label>Last Name<span>*</span></label>
				<input class="form-control" type="text" id="last_name" name="last_name"  value="<?php echo $last_name ?>" />
				</div>
<!--	<div class="col-sm-1"></div>


			<div class="col-xs-12 col-sm-4  ">
				<label>PAN Number<span>*</span></label>
				<input class="form-control" type="text" id="pan_number" name="pan_number"  value="<?php echo $pan_no ?>" disabled/>
				</div>-->
				
		</div>

		<div class="row ">
			<div class="col-sm-1"></div>

				<div class="col-xs-12 col-sm-4 ">
				<label>Address<span>*</span></label>
				<input class="form-control" type="text" id="address" name="address"  value="<?php echo $address ?>" />
				</div>
				
<div class="col-sm-1"></div>
				<div class="col-xs-12 col-sm-4  ">
				<label>Pin Code<span>*</span></label> 
				<input class="form-control" type="text" id="pin_code" name="pin_code"  value="<?php echo $pincode ?>" />
				</div>
				

		</div>
		<div class="row ">
		<div class="col-sm-1"></div>
				<div class="col-xs-12 col-sm-4  ">
				<label>Pan No:<span>*</span></label>
				<input class="form-control" type="text" id="pan_no" name="pan_no"  value="<?php echo $pan_no ?>"/>
				</div>		
<div class="col-sm-1"></div>
				<div class="col-xs-12 col-sm-4  ">
				<label>City<span>*</span></label>
				<input class="form-control" type="text" id="city" name="city"  value="<?php echo $city ?>"/>
				</div>
				

				

		</div>
		<div class="row">
			<div class="col-sm-1"></div>
				<div class="col-xs-12 col-sm-4  ">
				<label>State<span>*</span></label>
				<select id="state" name="state" class="form-control">
				<option value="0">--Choose--</option>
				<?php 

				$states = $database->query("select * FROM ps_state where active=1 ORDER BY name")->fetchAll();
				if(count($states) > 0)
				foreach($states as $state)

				echo '<option value="'.$state['id_state'].'" '.($state['id_state'] == $id_state ? "selected" : '').'>'.$state['name'].'</option>';
				?>
				</select>
				<!--<input class="form-control" type="text" id="state" name="state"/>-->
				</div>	
<div class="col-sm-1"></div>
				<div class="col-xs-12 col-sm-4  ">
				<label>Country<span>*</span></label>
				<select id="country" name="country" class="form-control">
				<option value="0">--Choose--</option>
				
				<option value="110" <?php echo ($id_country == '110' ? 'selected' : ''); ?>>India</option>
				</select>
				</div>	
				



				<input type="hidden" id="customerrefid" value="<?php echo $refid; ?>">
				<input type="hidden" id="id_customer" value="<?php echo $id_customer; ?>">
				<div class="col-sm-1"></div>
				
		</div>
		
			
							
			
		<div class="bottom-profile">	

			<div class="row ">
					
					
		
					<div class="col-xs-12 col-sm-12 ">
						<legend>Customer Contact Details</legend>
					</div>
		</div>
	
		<div class="row ">
			<div class="col-sm-1"></div>
			<div class="col-xs-12 col-sm-4 ">
			<label>Mobile No<span>*</span></label>
			<input class="form-control" type="text" id="mobile_no" name="mobile_no"  value="<?php echo $mobile ?>"/>
			</div>
			
			<div class="col-sm-1"></div>
			<div class="col-xs-12 col-sm-4  ">
			<label>Email ID<span>*</span></label>
			<input class="form-control" type="text" id="email_id"  name="email_id"  value="<?php echo $email ?>"/>
			</div>
		
		</div>
		


			<div class="row ">
				<div class="col-sm-8"></div>
				

				<div class="col-xs-12 col-sm-4 offset-sm-8 ">
				<button type="submit" class="btn-red update">update</button>
					
				</div>
			</div>
			</div>
		
</form>
			



			
		
	


				
				<fieldset style="display:none;">
					<legend class="as">User Login Details</legend>
					<table cellspacing="0" cellpadding="0" border="0" width="35%">
						<tr>
							<td>
								<div class="col-xs-12 ">
									<label>Username<span>*</span></label>
									<input class="form-control" type="text" id="username" name="username" disabled  value="<?php echo $username ?>" />
								</div>
							</td>
						</tr>
						<tr>
							<td>
								<div class="col-xs-12 ">
									<label>Password<span>*</span></label>
									<input class="form-control" type="text" id="password" name="password"  value="<?php echo $password ?>"/>
								</div>
							</td>
						</tr>
						<!--<tr>
							<td>
								<div class="col-xs-12 ">
									<label>Confirm Password<span>*</span></label>
									<input class="form-control" type="text" id="confirm_password" name="confirm_password"/>
								</div>
							</td>
						</tr>-->
					</table>
				</fieldset>
	
   

                   
				

	                 
        </div>
 </div>   
   
    
    
</div>

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
				
			
			
			update_customer_details();
		

            },
            fields: {
             
           
                mobile_no: {
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
                            regexp: /^[0-9-]+$/,
                            message: 'The field can only consist of numbers'
                        }
                    }
                },
				email_id: {
					validators: {
						notEmpty: {
							message: 'The email address is required and can\'t be empty'
						},
						emailAddress: {
							message: 'The input is not a valid email address'
						}
					}
				},
				address: {
					message: 'Field value is not valid',
                    validators: {
                        notEmpty: {
                            message: 'The field is required and can\'t be empty'
                        },
                        regexp: {
                            regexp: /^[a-zA-z0-9, \/.-]+$/,
                            message: 'Invalid Characters detected'
                        }
                    }
                },
				city: {
					message: 'Field value is not valid',
                    validators: {
                        notEmpty: {
                            message: 'The field is required and can\'t be empty'
                        },
                        regexp: {
                            regexp: /^[a-zA-z ]+$/,
                            message: 'Invalid Characters detected'
                        }
                    }
                },
                pin_code: {
                  	message: 'Field value is not valid',
                    validators: {
                        notEmpty: {
                            message: 'The field is required and can\'t be empty'
                        },
                        regexp: {
                            regexp: /^[0-9]+$/,
                            message: 'The field can only consist of numbers'
                        }
                    }
                },
				state: {
					message: 'Field value is not valid',
                    validators: {
                        notEmpty: {
                            message: 'The field is required and can\'t be empty'
                        },
                        regexp: {
                          
							regexp: /^[1-9][0-9]*$/,
                            message: 'Please select state'
                        }
                    }
                },
				country: {
					message: 'Field value is not valid',
                    validators: {
                        notEmpty: {
                            message: 'The field is required and can\'t be empty'
                        },
                        regexp: {
                            regexp: /^[1-9][0-9]*$/,
                            message: 'Please select state'
                        }
                    }
                },
				
				
				
				
				
				
				
            }
        });
		
	   </script>

 <script src="../js/script_createcustomer.js"></script>
 <script src="../js/jquery.dataTables.min.js"></script>

	   <?php include('include/footer.php');?>
    </body>


</html>
