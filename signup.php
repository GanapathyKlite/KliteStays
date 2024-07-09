<?php
error_reporting(0);
session_start();

if(isset($_SESSION['authtnid']))
  {//header("Location:home.php");
}
else{
  session_unset();
  // Finally, destroy the session.
  session_destroy();
}
?><!DOCTYPE html>
<html lang="en">
    
<head>
        <title>OTO CABS</title><meta charset="UTF-8" />
        <link rel="shortcut icon" href="images/icon.png" type="image/x-icon" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="css/bootstrap.min.css" />
    
     
     <link href="css/stylelogin.css" rel="stylesheet">
     <link rel='stylesheet' type='text/css' href='css/alertify.css' />
         <link href="css/font-awesome.css" rel="stylesheet"> 
<Style>

</Style>
    </head>
    <body>
       
<div class="row  " style=" padding:0;width:100%;margin:auto;  ">
                  <div class="col-md-12 customers_sign " style="background:#f7f7f6;">
  
   
  
  
      <div class="" >
          <form  action="" method="post" name="loginform" class="form-harz" id="loginform">
      <div class="">
            
            <h2 style="font-size:18px;color:#555;    font-variant-caps: petite-caps;margin:20px 0px 0 0;">Customer Login</h2><br>
            <div >
            <label style="color:#555;">Customer id:</label><br>
           <input id="username" name="uname" onfocus="if(this.value=='Username') this.value='';" onblur="if(this.value=='') this.value='Username';" value="Username"  type="text" tabindex="1"><br>
              <label style="color:#555;">Password:</label><br>
           <input id="password" name="pword" onfocus="if(this.value=='*********') this.value='';" onblur="if(this.value=='') this.value='*********';" value="" tabindex="2" type="password"><br>
         <div class="checkbox">
      <label style="font-variant-caps: petite-caps;
    font-weight: normal;
    color: #555;
    font-size: 13px;" class="rem"> <input style="height:12px;   "   type="checkbox" name="rem">Remember Me</label>
    </div>
          
            
            <p >
        <a class="btn_for_sendq hvr-shutter-in-vertical" style="text-align: center;

        width:100px;" onclick="authenticate_user();" tabindex="3"  >Login</a>
      </p>
      
            
            <p><a onclick="showpr();" href="#" style="color:red;">Forgot Password</a></p>
      <div class="alert alert-danger" id="invalidpassword" style="width: 220px; margin: 0; text-align: left; padding: 5px; font-size: 10pt; background-color: white; display:none;">Invalid Username and Password</div>
  
  <div class="alert alert-success" id="correctpassword" style="width: 210px; background-color: white; padding: 5px; font-size: 10pt; margin: 0;display:none;"><button class="close" data-dismiss="alert">×</button><strong>Success!</strong>Login Successfull </div>
  <?php if($_GET['status'] == 'timeout'){ ?> <div class="alert alert-success" style="width:275px; margin: 0 auto; text-align:left;"><button class="close" data-dismiss="alert">×</button><strong>Your Session has been expired.</strong> </div><br><?php } ?>

        <div class="alert alert-success" id="logout_success" style="width: 185px; height: 30px; margin: 0; text-align: left; background-color: white; padding: 5px; font-size: 10pt;display:none;"><strong>Successfully logged out.</strong> </div><br>
        </div>
      </div>
      
      </form>
      
      
      <!--<div class="lock"></div>-->
      
      
      
      
     <div class="clear"></div>
        </div>
    
  
   
    
  
</div>
 </div>
  
    </div>
   <script src="js/jquery-1.10.2.js"></script>
    <script src="js/bootstrap.min.js"></script>
   <script src="js/script.js"></script>
   <script src="js/alertify.js"></script>
   <script>
  $(document).ready(function(){
    /*setTimeout(function(){
      $('.alert-success').fadeOut();
    }, 1000);*/
    <?php if($_GET['status'] == 'logout'){ ?>
      $('#logout_success').fadeIn();
      setTimeout(function(){
        $('#logout_success').fadeOut();
      }, 1000);
    <?php } ?>
  });
   </script>
    </body>

</html>
