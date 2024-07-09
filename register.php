<?php
   error_reporting(0);
   session_start();
   include ('include/header2.php');
   include('include/database/config.php'); 
   require_once 'securimage/securimage.php';

   
   ?>


<!DOCTYPE html>
<html lang="en">
   <head>
      <title>OTO CABS</title>
      <meta charset="UTF-8" />
      <link rel="shortcut icon" href="images/icon.png" type="image/x-icon" />
      <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  
    
      <link href="css/alertify.css" type="text/css" rel="stylesheet" media="all">
      <link href="css/alertify.min.css" type="text/css" rel="stylesheet" media="all">
      
     
   
      <script type="text/javascript" src="js/bootstrapValidator.js"></script>
      <link rel="stylesheet" href="css/jquery-confirm.min.css">
      <script src="js/jquery-confirm.min.js"></script>
      
      <style>
        
      </style>
   </head>
   <body >
     
   
         <div  id="content" >
            <form id="defaultForm" method="post"  class="form-horizontal">
               <input type="hidden" id="pagname" value=<?php echo $pname[0];?>>
               <div class="row  " style="   padding:0;width:100%;margin:auto;  ">
                  <div class="col-md-12 addcustomer  " style="background:#f7f7f6;">
                     <h3 class="margin_topbot_20" style="   font-variant-caps: petite-caps;text-align:center;color:#555;font-weight: bold; font-size:18px;">Registration Form</h3>
                     <fieldset>
                        <div class="full_val">
                           
                           <div class="row">
                              <div class="col-xs-12 col-sm-4 form-group groupstyle">
                                 <label>First name&nbsp;&nbsp;<span>*</span></label>
                                 <input class="form-control" type="text" id="first_name" name="first_name" autocomplete="off"/>
                              </div>
                              <div class="col-xs-12 col-sm-4 form-group groupstyle">
                                 <label>Last name&nbsp;&nbsp;<span>*</span></label>
                                 <input class="form-control" type="text" id="last_name" name="last_name" autocomplete="off"/>
                              </div>
                              <div class="col-xs-12 col-sm-4 form-group groupstyle">
                                 <label>Email id&nbsp;&nbsp;<span>*</span></label>
                                 <input class="form-control" type="text" id="email_id" name="email_id"/>
                              </div>
                              <div class="clearfix"></div>
                             
                              <div class="col-xs-12 col-sm-4  form-group groupstyle">
                                 <label>Password&nbsp;&nbsp;<span>*</span></label>
                                 <input class="form-control" type="password" id="password" name="password" autocomplete="off"/>
                              </div>
                              <div class="col-xs-12 col-sm-4 form-group groupstyle">
                                 <label>Re-enter password&nbsp;&nbsp;<span>*</span></label>
                                 <input class="form-control" type="password" id="confirmPassword" name="confirmPassword" autocomplete="off"/>
                              </div>
                              <div class="col-xs-12 col-sm-4 form-group groupstyle">
                                 <label>Mobile No&nbsp;&nbsp;<span>*</span></label>
                                 <input class="form-control" type="text" id="mobile_no" name="mobile_no"/>
                              </div>
                              <div class="clearfix"></div>
                              <div class="col-xs-12  col-sm-4 form-group groupstyle">
                                 <label>Address&nbsp;&nbsp;<span>*</span></label>
                                 <input class="form-control" type="text" id="address" name="address" autocomplete="off"/>
                              </div>
                              <div class="col-xs-12 col-sm-4 form-group groupstyle">
                                 <label>Pin Code&nbsp;&nbsp;<span>*</span></label> 
                                 <input class="form-control" type="text" id="pin_code" name="pin_code"/>
                              </div>
                              <div class="col-xs-12 col-sm-4 form-group groupstyle">
                                 <label>City&nbsp;&nbsp;<span>*</span></label>
                                 <input class="form-control" type="text" id="city" name="city"/>
                              </div>
                              <div class="clearfix"></div>
                              <div class="col-xs-12 col-sm-4 form-group groupstyle">
                                 <label>State&nbsp;&nbsp;<span>*</span></label>
                                 <select id="state" name="state" class="form-control">
                                    <option value="0">--Choose--</option>
                                    <?php 
                                       $states = $database->query("select * FROM ps_state where active=1 ORDER BY name")->fetchAll();
                                       if(count($states) > 0)
                                        foreach($states as $state)
                                       
                                          echo '<option value="'.$state['id_state'].'" '.(isset($id_state) && $state['id_state'] == $id_state ? 'selected' : '').'>'.$state['name'].'</option>';
                                       ?>
                                 </select>
                                 <!--<input class="form-control" type="text" id="state" name="state"/>-->
                              </div>
                              
                                 <?php echo Securimage::getCaptchaHtml() ?>
                              
                           </div>
                        </div>
                        
                     </fieldset>
                     <div class="col-md-12" style="text-align:right;">
                        <button style="width: 100px;"    type="submit" class=" hvr-shutter-in-vertical btn_for_sendq">Submit</button>
                        <button  style="width: 100px;" type="button" class=" hvr-shutter-in-vertical btn_for_sendq" onclick="reload_form();">Reset</button>
                     </div>
                     <div class="col-md-12" style=" text-align:center; height:15px;"></div>
                  </div>
               </div>
            </form>
         </div>

     
      <script>
         $('#defaultForm').bootstrapValidator({
                 message: 'This value is not valid',
                    feedbackIcons: {
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
            },
                 //live: 'enabled',
              
                 fields: {
                  first_name: {
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
                 last_name: {
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
         
                      password: {
          message: 'Field value is not valid',
                         validators: {
                             notEmpty: {
                                 message: 'The field is required and can\'t be empty'
                             },
                             regexp: {
                                 regexp: /^(?=.*[0-9])(?=.*[!@#$%^&*])[a-zA-Z0-9!@#$%^&*]{6,16}$/,
                                 message: 'Invalid Characters detected'
                             }
                             
                         }
                     },
         
         
         confirmPassword: {
          message: 'Field value is not valid',
                         validators: {
                             notEmpty: {
                                 message: 'The field is required and can\'t be empty'
                             },
                             regexp: {
                                 regexp: /^(?=.*[0-9])(?=.*[!@#$%^&*])[a-zA-Z0-9!@#$%^&*]{6,16}$/,
                                 message: 'Invalid Characters detected'
                             },
                              identical: {
                                field: 'password',
                                message: 'The password and its confirm are not the same'
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
                      pin_code: {
                        message: 'Field value is not valid',
                         validators: {
                             notEmpty: {
                                 message: 'The field is required and can\'t be empty'
                             },
                             stringLength: {
                                 min: 6,
                                 max: 6,
                                 message: 'The field must contain 6 digits'
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
         
         
         
         
         
         
                 },

                 submitHandler: function(validator, form) {
         
         
         save_customerdetails();
         
         
                 },
             });
         
         
         
         
      </script>
      <script src="js/script_createcustomer.js"></script>
      <script src="js/alertify.js"></script>
   </body>
</html>