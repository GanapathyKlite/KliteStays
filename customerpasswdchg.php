<?php
error_reporting(0);
session_start();
$currentpage='';
include ('include/header.php');
include('include/database/config.php'); 
if (isset($_GET['key'])) {
    $customerid=$_GET['key'];
}



?>
 <script src="js/jquery-1.10.2.js"></script>
<script src="<?php echo $root_dir;?>js/alertify.js"></script>
           <!--  <script src="js/customerpasswdupte.js"></script>-->
<style>
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
color: #fff;
}

.alertify .ajs-footer .ajs-buttons .ajs-button {
//background-color: transparent;
color: #fff;
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
</style>

<!DOCTYPE html>
<html lang="en">
    
<head>
        <title>OTO CABS</title><meta charset="UTF-8" />
        <link rel="shortcut icon" href="images/icon.png" type="image/x-icon" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
		
		

		 <link href="css/stylelogin.css" rel="stylesheet">
		 
		 <!--<link rel="stylesheet" href="css/bootstrapValidator.css"/>
<link href="plugins/datatables/css/jquery.datatables.min.css" rel="stylesheet" type="text/css"/>	
        <link href="plugins/datatables/css/jquery.datatables_themeroller.css" rel="stylesheet" type="text/css"/>	
        <link rel="stylesheet" href="css/datatables.css" id="toggleCSS" />
<link rel="stylesheet" href="css/awesome/css/font-awesome.min.css"/>-->



<script type="text/javascript" src="js/bootstrapValidator.js"></script>

<style>
.wwd b{
	font-weight: bold;
}
.form-group label{
	
	font-weight: normal;
}
</style>

    </head>
    <body>
       
          
<div class="">
  
	
	<div class="container container_changepass">
      <div class="new_heading">
            Reset new password
      </div>
	<div class="contentconta" id="content" > 
	
		<form id="defaultForm" method="post"  class="form-horizontal">
			

  <div class="form-group " style="margin-top:15px;">
  
    
<div class="col-sm-offset-1 col-sm-2 ">
    <label for="inputEmail3" >New password</label>
    </div>
    <div class="col-sm-4 ">
     <input type="password" class="form-control" id="password_e" name="password_e" placeholder="Change Password" >
    </div>
    <div class="col-sm-5">

    <p style="font-size:11px;">Eg : <Span style="color:#ed1c24; ">Pass@123</Span></p>
    </div>
  </div>
  <div class="form-group ">
   
    
  <div class="col-sm-offset-1 col-sm-2 ">
   <label for="inputPassword3" >Reconfirm password</label>
    </div>
    
    <div class="col-sm-4 ">
     	<input type="password" class="form-control" id="confirmpassword_e" name="confirmpassword_e" placeholder="Confirm Password">
		 <input class="form-control" type="hidden" id="customerrefid" name="customerid" value="<?php echo $customerid ?>"/>
    </div>
  </div>
  
  <div class="form-group">
    <div class="col-sm-7" style="text-align: right;">
      
	  <button  style="width:100px;" type="submit" class="hvr-shutter-in-vertical btn_for_sendq ">Reset</button>
    </div>
  </div>
</form>
			
                  
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
           
            fields: {
               
				  password_e: {
                  	message: 'Field value is not valid',
                    validators: {
                        notEmpty: {
                            message: 'The field is required and can\'t be empty'
                        },
                        regexp: {
                            regexp: /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[$@$!%*?])[A-Za-z\d$@$!%*?]{6,16}$/,
                            message: 'Your Password must be <br> 1 Uppercase Alphabet<br> 1 Lowercase Alphabet <br> 1 Number <br> 1 Special Character '
                        },
                        identical: {
              field: 'confirmpassword_e',
              message: 'The password does not match'
            },
						  stringLength: {
                            min: 6,
                          
                            message: 'Your password must be <br>Minimum:6 character  '
                        },
                        stringLength: {
                          
                            max: 16,
                            message: ' Maximum:16 character '
                        }
						
                    }
                },
                confirmpassword_e: {
                  	message: 'Field value is not valid',
                    validators: {
                        notEmpty: {
                            message: 'The field is required and can\'t be empty'
                        },
                        regexp: {
                            regexp: /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[$@$!%*?])[A-Za-z\d$@$!%*?]{6,16}$/,
                            message: 'Your Password must be 1 Uppercase Alphabet, 1 Lowercase Alphabet, 1 Number and 1 Special Character'
                        },
						identical: {
							field: 'password_e',
							message: 'The password does not match'
						},
						  stringLength: {
                            min: 6,
                            max: 30,
                            message: 'The password must be Minimum:6 character'
                        }
                    }
                }
				},
                  submitHandler: function(validator, form) {

                update_customerupdtepasswwd();
            
            },
           
				
				
				
				
            
        });
		
	   </script>
<div id="confirm_div" class="modal  fade" style="display: none; 
    width: 30%;
    margin: auto;
    background-color: #fff;
    height: 119px;">
  <div class="modal-body">
    Are you sure To change the Password?
  </div>
  <div class="modal-footer">
    <button type="button" data-dismiss="modal" class="btn btn-red" style="width:20%" id="ok">ok</button>
    <button type="button" data-dismiss="modal" class="btn">Cancel</button>
  </div>
</div>

  <div id="sucess_div" class="modal  fade" style="display: none; 
    width: 30%;
    margin: auto;
    background-color: #fff;
    height: 119px;">
  <div class="modal-body">
   Password Updated Sucessfully
  </div>
  <div class="modal-footer">
    <button type="button" data-dismiss="modal" class="btn btn-red" style="width:20%" id="succ">Close</button>
  </div>
</div>



    </body>

</html>
<?php include("include/footer.php"); ?>
