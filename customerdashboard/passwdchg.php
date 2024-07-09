<?php 
error_reporting(0);
session_start();
include('../include/database/config.php'); 
include('include/header.php')

?>	
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



.alertify .ajs-footer .ajs-buttons .ajs-button {
background-color: #ed1c24;
color: #fff!important;
border: 0!important ;
font-size: 14px;
font-weight: 700;
text-transform: uppercase;
outline:none!important;
}


.alertify .ajs-footer {
padding: 4px;
margin-left: -24px;
margin-right: -24px;
min-height: 43px;
background-color: #fff;
}
.alertify .ajs-commands button.ajs-close {
    outline: none;
    background-color: rgb(255, 0, 0)!important;
    color: rgb(255, 255, 255);
    position: absolute;
    top: -25px;
    right: -45px;
    border-radius: 50%;
    background-image: url(../images/x.png)!important;
}

.xs h4{
font-size: 15px;
}
</style>
<?php
$refid =$_SESSION['reference'];
           
        ?>

  <div id="page-wrapper">
<div class="password_main">
    <div class="container contain" >

<form name="" id="defaultForm">
        <div class="row">
            <div class="col-sm-1"></div>
                <label  class="col-sm-2">New Password</label>
            <div class="col-sm-4">
                  <input type="password" class="form-control" id="password_e" name="password_e" placeholder="Change Password" >
            </div>
            <div class="col-sm-5">
                 <!--<input type="password" class="form-control" placeholder="For Example: John@123" disabled >-->
                <p class="cep"><em>Eg:</em> John@123</p>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-1"></div>
                 <label  class="col-sm-2 ">Confirm Password</label>
            <div class="col-sm-4">
                <input type="password" class="form-control" id="confirmpassword_e" name="confirmpassword_e" placeholder="Confirm Password" >
                 <input type="hidden" name="customerid" id="customerid" value="<?php echo $_SESSION['authtnid']; ?>">
            </div>

        </div>
        <div class="row">
            <div class="col-sm-1"></div>

            <div class="col-sm-2 col-sm-offset-5 ">
            <input type="hidden" id="customerrefid" value="<?php echo $refid; ?>">
                 <button type="submit" class="btn cubtn">Update</button>
                 <!--<button class="btn-default btn" onclick="reload_form();">Cancel</button>-->
            </div>
        </div>
</form>
    </div>
</div>
</div>
	 
	 <div class="clearfix"> </div>
	
  
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
               
				
				 update_customerupdtepasswwd();
				

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
                            message: 'Your Password must be 1 Uppercase Alphabet, 1 Lowercase Alphabet, 1 Number and 1 Special Character '
                        },
						  stringLength: {
                            min: 6,
                            max: 16,
                            message: 'Your password must be <br>Minimum:6 character <br> Maximum:16 character '
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
                            max: 16,
                            message: 'The password must be Minimum:6 character'
                        }
                    }
                },
				emailid_e: {
					validators: {
						notEmpty: {
							message: 'The email address is required and can\'t be empty'
						},
						emailAddress: {
							message: 'The input is not a valid email address'
						}
					}
				},
                userrole_e: {
                  	message: 'Field value is not valid',
                    validators: {
                        notEmpty: {
                            message: 'The field is required and can\'t be empty'
                        }
                    }
                },
                mobileno_e: {
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
                }
            }
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

	   <?php include('include/footer.php')?>