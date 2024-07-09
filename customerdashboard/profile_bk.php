<?php
error_reporting(0);
session_start();
//include ('include/header2.php');
include('../include/database/config.php'); 



		
			
	
?>

<!DOCTYPE html>
<html lang="en">
    
<head>
        <title>Buddies Tours</title><meta charset="UTF-8" />
        <link rel="shortcut icon" href="images/icon.png" type="image/x-icon" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
		
	<!--	<link href="../css/stylelogin.css" rel="stylesheet">-->

		<!-- <link href="../css/stylelogin.css" rel="stylesheet">
		 
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
<script type="text/javascript" src="https://netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/bootstrapValidator.js"></script>-->
<style type="text/css">
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
	background-image: -o-linear-gradient(bottom, #DB0B0B 19%, #910005 56%);
	background-image: -moz-linear-gradient(bottom, #DB0B0B 19%, #910005 56%);
	background-image: -webkit-linear-gradient(bottom, #DB0B0B 19%, #910005 56%);
	background-image: -ms-linear-gradient(bottom, #DB0B0B 19%, #910005 56%);
	background-image: linear-gradient(to bottom, #DB0B0B 19%, #910005 56%);
	}
	
	.ba label{
			font-family: 'Roboto',; sans-serif;
			font-size:12px;
	}
	.addagent .form-control{
		
		font-size:14px;
		
	}
	
	
.alertify .ajs-header {
color: white;
font-weight: 700;
background-image: -webkit-linear-gradient(bottom, #DB0B0B 19%, #910005 56%);
border-bottom: #eee 1px solid;
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
border-top: #eee 1px solid;
border-radius: 0 0 2px 2px;
}

.alertify .ajs-footer .ajs-buttons .ajs-button.ajs-ok {
color: black;
}

.alertify .ajs-footer .ajs-buttons .ajs-button {
//background-color: transparent;
color: black;
border: 0;
font-size: 14px;
font-weight: 700;
text-transform: uppercase;
}


.alertify .ajs-footer {
padding: 4px;
margin-left: -24px;
margin-right: -24px;
min-height: 43px;
background-color: #fff;
}

.alertify .ajs-footer .ajs-buttons .ajs-button {
color: #000;
border: 0;
font-size: 14px;
font-weight: 700;
text-transform: uppercase;
}
	
.wwd{
font-size: 15px;
}

</style>
    </head>
    <body>
       
          <?php include('include/header.php')?>		
<div class="wrapper">
  
	<!--<div class="logo">
    	<a href="#"><img src="images/logo.png" alt="buddiestourslogo" ></a>
    </div>-->
	
	<div class="content" id="content">
		<div class="widget-title wwd" style="margin-bottom:20px;">
            <b>Edit Profile</b>
  		</div>
		<?php
		$refid =$_SESSION['refid'];
			$get_agents=$database->query("select * from ps_agents where reference='$refid'")->fetchAll();
		foreach($get_agents as $agents)
		{
			$username=$agents['username'];
			$password=$agents['password'];
			$agentname=$agents['agentname'];
			$pan_no=$agents['pan_no'];
			$address=$agents['address'];
			$city=$agents['city'];
			$pincode=$agents['pincode'];
			$id_state=$agents['id_state'];
			$id_country=$agents['id_country'];
			$telephone=$agents['telephone'];
			$mobile=$agents['mobile'];
			$fax=$agents['fax'];
			$email=$agents['email'];
			$website=$agents['website'];
			$description=$agents['description'];
			$logo_ext=$agents['logo_ext'];
			$services_offered=$agents['services_offered'];
			$id_agent=$agents['id_agent'];
			
			$imgeu= $agentname._.$id_agent.'.'.$logo_ext;
			
					
							
			
			
		}
		?>
		<form id="defaultForm" method="post"  class="form-horizontal">
			<input type="hidden" id="pagname" value=<?php echo $pname[0];?>>
			<div class="col-md-12 addagent ba">
				<fieldset>
					<em>Agent Details</em>
					<table cellspacing="0" cellpadding="0" border="0" width="70%">
					<tr>
						<td width="50%">
								<div class="col-xs-12 form-group groupstyle">
									<label>Agent Name<span>*</span></label>
									<input class="form-control cc" type="text" id="agent_name" name="agent_name"  value="<?php echo $agentname ?>" disabled />
								</div>
							</td>
							<td width="50%">
								<div class="col-xs-12 form-group groupstyle">
									<label>PAN Number<span>*</span></label>
									<input class="form-control" type="text" id="pan_number" name="pan_number"  value="<?php echo $pan_no ?>" disabled/>
								</div>
							</td>
						</tr>
						<tr>
							<td>
								<div class="col-xs-12 form-group groupstyle">
									<label>Address<span>*</span></label>
									<input class="form-control" type="text" id="address" name="address"  value="<?php echo $address ?>" />
								</div>
							</td>
							<td>
								<div class="col-xs-12 form-group groupstyle">
									<label>Pin Code<span>*</span></label> 
									<input class="form-control" type="text" id="pin_code" name="pin_code"  value="<?php echo $pincode ?>" />
								</div>
							</td>
						</tr>
						<tr>
							<td>
								<div class="col-xs-12 form-group groupstyle">
									<label>City<span>*</span></label>
									<input class="form-control" type="text" id="city" name="city"  value="<?php echo $city ?>"/>
								</div>
							</td>
							<td>
								<div class="col-xs-12 form-group groupstyle">
									<label>State<span>*</span></label>
									<select id="state" name="state" class="form-control">
										<option value="0">--Choose--</option>
										<?php 
										
											$states = $database->query("select * FROM ps_state ORDER BY name")->fetchAll();
											if(count($states) > 0)
												foreach($states as $state)
											
													echo '<option value="'.$state['id_state'].'">'.$state['name'].'</option>';
										?>
									</select>
									<!--<input class="form-control" type="text" id="state" name="state"/>-->
								</div>
							</td>
						</tr>
						<tr>
							<td>
								<div class="col-xs-12 form-group groupstyle">
									<label>Country<span>*</span></label>
									<select id="country" name="country" class="form-control">
										<option value="0">--Choose--</option>
										<?php 
											/*$states = $database->query("select * FROM country ORDER BY name")->fetchAll();
											if(count($states) > 0)
												foreach($states as $state)
													echo '<option value="'.$state['id_country'].'">'.$state['name'].'</option>';
													*/
										?>
										<option value="10">India</option>
									</select>
									<!--<input class="form-control" type="text" id="country" name="country"/>-->
								</div>
							</td>
							
							
							<input type="hidden" id="agentrefid" value="<?php echo $refid; ?>">
							<input type="hidden" id="id_agent" value="<?php echo $id_agent; ?>">
							<td>
							<div class="col-xs-12 form-group groupstyle">
  <label>about your company<span>*</span></label>
  <textarea class="form-control" rows="3" id ="desc" name="desc"><?php echo $description; ?></textarea>
</div>
							
							</td>	
							
						</tr>
						
						
						
		
					</table>
					
					<tr>
					
				
							<td>
								<div class="col-xs-12 form-group groupstyle pack">
								<div class="col-md-2">
								<label style="margin: 15px 0 0 0; font-weight: bold; font-size: 17px;">Our services<span>*</span></label>
								</div>
								
									<div class="col-md-10">
<?php


$services = explode(',',$agents['services_offered']);



     
    $servicesArr = array('Flights','Hotels','Holiday Packages','Bus','Car','Activities');
foreach($servicesArr as $key=>$value)
{
  //echo $key;
 echo  '<label class="checkbox-inline"><input type="checkbox" id="inlineCheckbox1" class="services_offered" name="inlineCheckbox1[]" value="'.$key.'"'.(count($services) > 0 && in_array($key,$services) ? 'checked' : '').'><p style="margin: 10px 30px 0 0;">'.$value.'</p></label>';
}
			
//	print_r($services);			
					
?>
</div>

								</div>
							
						<td>
						</tr>
				</fieldset>
				<fieldset>
					<legend class="as">Agent Contact Details</legend>
					<table cellspacing="0" cellpadding="0" border="0" width="70%">
						<tr>
							<td width="50%">
								<div class="col-xs-12 form-group groupstyle">
									<label>Telephone No<span>*</span></label>
									<input class="form-control" type="text" id="telephone_no" name="telephone_no"  value="<?php echo $telephone ?>"/>
								</div>
							</td>
							<td width="50%">
								<div class="col-xs-12 form-group groupstyle">
									<label>Mobile No<span>*</span></label>
									<input class="form-control" type="text" id="mobile_no" name="mobile_no"  value="<?php echo $mobile ?>"/>
								</div>
							</td>
						</tr>
						<tr>
							<td>
								<div class="col-xs-12 form-group groupstyle">
									<label>Fax No</label>
									<input class="form-control" type="text" id="fax_no" name="fax_no"  value="<?php echo $fax ?>"/>
								</div>
							</td>
							<td>
								<div class="col-xs-12 form-group groupstyle">
									<label>Email ID<span>*</span></label>
									<input class="form-control" type="text" id="email_id" name="email_id"  value="<?php echo $email ?>"/>
								</div>
							</td>
						</tr>
						<tr>
							<td>
								<div class="col-xs-12 form-group groupstyle">
									<label>Website</label>
									<input class="form-control" type="text" id="website" name="website"  value="<?php echo $website ?>"/>
								</div>
							</td>
							
							
							<td>
								<div class="col-xs-12 form-group groupstyle">
								<!--<form id="myForm1" action="upload_file.php" method="post" enctype="multipart/form-data">-->
								<div class="col-md-6">
									<label>file upload</label>
								 <input type="file" id="sortpicture" name="ImageFile">
								<?php echo  '<img style="float:right;" src="../uploads/'.$imgeu.'" width="50" height="50">'?>
								 <input class="form-control" type="hidden" id="sortpicturehidden" name="sortpicturehidden"  value="<?php echo $logo_ext ?>" />
								 </div>
								<div class="col-md-6">
							 <input type="submit" id="upload" class="btn btn-default" value="Upload"></button>
							 
							 
								 </div>
						
								</div>
							</td>
							
							
							
						</tr>
					</table>
				</fieldset>
				<fieldset>
					<legend class="as">User Login Details</legend>
					<table cellspacing="0" cellpadding="0" border="0" width="35%">
						<tr>
							<td>
								<div class="col-xs-12 form-group groupstyle">
									<label>Username<span>*</span></label>
									<input class="form-control" type="text" id="username" name="username" disabled  value="<?php echo $username ?>" />
								</div>
							</td>
						</tr>
						<tr>
							<td>
								<div class="col-xs-12 form-group groupstyle">
									<label>Password<span>*</span></label>
									<input class="form-control" type="text" id="password" name="password"  value="<?php echo $password ?>"/>
								</div>
							</td>
						</tr>
						<!--<tr>
							<td>
								<div class="col-xs-12 form-group groupstyle">
									<label>Confirm Password<span>*</span></label>
									<input class="form-control" type="text" id="confirm_password" name="confirm_password"/>
								</div>
							</td>
						</tr>-->
					</table>
				</fieldset>
	
				<div class="col-md-12" style="border:0px solid red; text-align:center;">
					
					<button type="submit" class="btn-red">update</button>
					<!--<button type="button" class="btn-red" onclick="reload_form();">Reset</button>-->
				</div>
				<div class="col-md-12" style="border:0px solid red; text-align:center; height:15px;"></div>
			</div>
		</form>	
	
   

                   
				
                  
        </div>
    
   
    
    <div class="footer">
     Copyright © 2017 Buddies Tours and Travels private limited. All Rights Reserved.
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
				
				
				
		

             
				console.log("sdfsdf123456");
				
			
			update_agent_details();
		

            },
            fields: {
                agent_name: {
					message: 'Field value is not valid',
                    validators: {
                        notEmpty: {
                            message: 'The field is required and can\'t be empty'
                        },
                        regexp: {
                            regexp: /^[a-zA-z \']+$/,
                            message: 'Invalid Characters detected'
                        }
                    }
                },
				pan_number: {
					message: 'Field value is not valid',
                    validators: {
                        notEmpty: {
                            message: 'The field is required and can\'t be empty'
                        },
                        regexp: {
                           // regexp: /^[a-zA-z0-9]+$/,
						   regexp: /[a-zA-z]{5}\d{4}/,
                            message: 'Invalid Characters detected'
                        }
                    }
                },
               /* telephone_no: {
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
                },*/
				
				
				  
                telephone_no: {
						message: 'Field value is not valid',
                    validators: {
						  notEmpty: {
                            message: 'The field is required and can\'t be empty'
                        },
						
						
				/*
                        phone: {
                            country: 'IN',
                            message: 'The value is not valid %s phone number'
                        },
					*/

				stringLength: {
                            min: 11,
                            max: 11,
                            message: 'The field must contain 11 digits'
                        },
                        regexp: {
                            regexp: /^[0-9-]+$/,
                            message: 'The field can only consist of numbers'
                        }					
					
                    }
                },
				
           
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
                            regexp: /^[1-9][0-9]?$|^100$/,
                            message: 'Please select state'
                        }
                    }
                },
				
				desc: {
					message: 'Field value is not valid',
                    validators: {
                        notEmpty: {
                            message: 'The field is required and can\'t be empty'
                        },
						
					stringLength: {
                        max: 120,
                        message: 'The content must be less than 120 characters long'
                    },
                        regexp: {
							 
                            regexp: /^[a-zA-z0-9, \/.-]+$/,
                            message: 'Invalid Characters detected'
                        }
                    }
                },
				
			/*	
				ImageFile: {
					message: 'Field value is not valid',
                    validators: {
                        notEmpty: {
                            message: 'Please select an image'
                        },
                        file: {
                        extension: 'jpeg,jpg,png',
                        type: 'image/jpeg,image/png',
                        maxSize: 2097152,   // 2048 * 1024
                        message: 'The selected file is not valid'
                    }
                    }
                },*/
				username: {
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
				password: {
					message: 'Field value is not valid',
                    validators: {
						stringLength: {
                            min: 6,
                            message: 'The field must contain minimum 6 characters'
                        },
                        notEmpty: {
                            message: 'The field is required and can\'t be empty'
                        },
                        regexp: {
                            regexp: /^[ A-Za-z0-9_@./$%^*!#&+-]*$/,
                            message: 'Invalid Characters detected'
                        }
                    }
                },
				confirm_password: {
					message: 'Field value is not valid',
                    validators: {
                        notEmpty: {
                            message: 'The field is required and can\'t be empty'
                        },
                        regexp: {
                            regexp: /^[ A-Za-z0-9_@./$%^*!#&+-]*$/,
                            message: 'Invalid Characters detected'
                        }
                    }
                },
				fax_no: {
					message: 'Field value is not valid',
                    validators: {
						  notEmpty: {
                            message: 'The field is required and can\'t be empty'
                        },
						
                        regexp: {
                            regexp: /^[0-9]+$/,
                            message: 'Invalid Characters detected'
                        }
                    }
                },
				/*website: {
					message: 'Field value is not valid',
                    validators: {
						  notEmpty: {
                            message: 'The field is required and can\'t be empty'
                        },
						uri: {
                        message: 'The website address is not valid EX. http://www.google.com'
                    }
                       
                    }
                },*/
				
				website: {
					message: 'Field value is not valid',
                    validators: {
                        notEmpty: {
                            message: 'The field is required and can\'t be empty'
                        },
                        regexp: {
                            regexp: /^[a-zA-z \.']+$/,
                            message: 'The website address is not valid'
                        }
                    }
                },
				
				
				
				
				 'inlineCheckbox1[]': {
                validators: {
                    choice: {
                        min: 1,
                        max: 6,
                        message: 'Please choose 1 our services you are good at'
                    }
                }
            }
				
				
				
				
            }
        });
		
	   </script>

 <script src="js/script_createagent.js"></script>
 <script src="js/jquery.dataTables.min.js"></script>
<script src="js/datatables.js"></script>
	
    </body>

</html>
