<?php

session_start();
if($_SESSION['username']!="" && $_SESSION['username']!=null)
{
	include('../include/database/config.php');
	$date= new DateTime('now', new DateTimeZone('Asia/Kolkata'));
}
else{
	 header("Location:../logout.php");
}

$inactive = 1800; // Set timeout period in seconds

if (isset($_SESSION['timeout'])) 
{
    $session_life = time() - $_SESSION['timeout'];
    if ($session_life > $inactive) {
        session_destroy();
		
        header("Location: ../logout.php");
    }
}
 $_SESSION['timeout'] = time();
?>

<!DOCTYPE HTML>
<html>
<head>
<title>OTO CABS</title>
<link rel="shortcut icon" type="image/png" href="images/favicon.png" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="Modern Responsive web template, Bootstrap Web Templates, Flat Web Templates, Andriod Compatible web template, 
Smartphone Compatible web template, free webdesigns for Nokia, Samsung, LG, SonyErricsson, Motorola web design" />
<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
 <!-- Bootstrap Core CSS -->
<link href="css/bootstrap.min.css" rel='stylesheet' type='text/css' media='all' />
<!-- Custom CSS -->


<link rel='stylesheet' type='text/css' href='css/alertify.css' media='all' />
<link href="css/style.css" rel='stylesheet' type='text/css' media='all' />
<!--<link href="../css/stylelogin.css" rel='stylesheet' type='text/css' media='all' />-->
<link href="css/sticky-footer-navbar.css" rel='stylesheet' type='text/css' />
<!-- Graph CSS -->
<link href="css/lines.css" rel='stylesheet' type='text/css' />
<link href="css/animate.css" rel='stylesheet' type='text/css' />
<link type="text/css" rel="stylesheet" href="css/font-awesome.css" />
<!-- jQuery -->
<script src="js/jquery.min.js"></script>
<!----webfonts--->
<link href='https://fonts.googleapis.com/css?family=Roboto:400,100,300,500,700,900' rel='stylesheet' type='text/css'>
<!---//webfonts--->  
<!-- Nav CSS -->
<link href="css/custom.css" rel="stylesheet">
<!-- Metis Menu Plugin JavaScript -->
<script src="js/metisMenu.min.js"></script>
<script src="js/custom.js"></script>
<!-- Graph JavaScript -->
<script src="js/d3.v3.js"></script>
<script src="js/rickshaw.js"></script>
<script src="js/alertify.js"></script>
<script src="js/bootstrap.min.js"></script>

<link href="<?=$root_dir;?>js/plugins/fancybox/jquery.fancybox.css" rel="stylesheet">
<script src="<?=$root_dir;?>js/plugins/fancybox/jquery.fancybox.js"></script>
	
	 
 <script type="text/javascript" src="../js/bootstrapValidator.js"></script> 
 <script type="text/javascript" src="../js/customerpasswdupte.js"></script> 
 
 <script>
$( document ).ready(function() {
	$("#ul_top_hypers li").hover(function(){
		$(this).siblings().children("ul").hide();
		var numrelated=$('.dropdown-menu1 > li:visible').length;
		$(this).children("ul").fadeIn();
	   },function() {
			$('#ul_top_hypers').on("mouseleave", function() {
				$(this).children("li").find("ul").fadeOut();
			});
	});
});
</script>

<!--<link href="css/payment/base.css" rel="stylesheet">
<link href="css/payment/extracss.css" rel="stylesheet">
<link href="css/payment/helpers.css" rel="stylesheet">
<link href="css/payment/screen.css" rel="stylesheet">-->
<link rel="stylesheet" href="<?php echo $root_dir; ?>css/jquery-confirm.min.css">
<script src="<?php echo $root_dir; ?>js/jquery-confirm.min.js"></script>
<script src="<?php echo $root_dir; ?>js/loadingoverlay.min.js"></script>
<script src="<?php echo $root_dir; ?>js/loadingoverlay_progress.min.js"></script>
</head>
<body>
<input type="hidden" value="<?php echo $root_dir;?>" id="root_dir">

<div class="logo">

<div class="container logo_container">
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 logos logoss">
        <a  href="../home.php"><img src="images/logo.png" "></a>
    </div>
            <?php
			$id=	$_SESSION['reference'];
							include('../include/database/config.php');
							$gen_balance=$database->query("select cash_back from ps_customers where reference='$id'")->fetchAll();
              if(!empty($gen_balance))
              {
                $cash_back = $gen_balance[0][0];
              }
							
							/*$c=0;
							foreach($gen_balance as $balanceamt)
							{
								
								$amountcalc=$balanceamt['amount']*100-($balanceamt['amount']*1.8);
								$amount=$amountcalc/100;
								$c=intval($c)+intval($amount);
							}*/
							
							
							
						?>
    <!--<b style="color:#DB0B0B;"><?php echo $c.".00"; ?></b>-->
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 head_balance logoss" >

        <li class="balance_amount"> 
       Cash Back :  <i class="fa fa-inr" aria-hidden="true"></i>
       
        <?php echo (isset($cash_back) && !empty($cash_back) ? number_format($cash_back, 2, '.', '') : '0.00'); ?>

        
        </li> 
    <div class="dropdown drop_customerid"  >
         <button class="btn btn-primary dropdown-toggle username_button" type="button" data-toggle="dropdown">
     
            <li class="customerid">
              <a href="#"><span class="glyphicon glyphicon-user userna"></span><?php echo $_SESSION['first_name']; ?> </a><span style="margin:0 7px" class="caret"></span></li>
        
        
        
        </button>
    
        <ul class="dropdown-menu inner_dropdown">
            <li>
                <a href="../logout.php">Logout <span class="glyphicon glyphicon-log-out "></span>
                </a>
            </li>

        </ul>
    </div>
    </div>
    </div>
</div>

<div class="clearfix"></div>


   


<div class="clearfix"></div>

<div class="pagefull" >
<div class="container head_container" >

<nav class="navbar navbar-default full_navbar">
  <div class="container-fluid">
    <div class="navbar-header">
       <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>

      </button>
    
    </div>
    <div class="collapse navbar-collapse" id="myNavbar" data-hover="dropdown" data-animations="fadeInDown " >
    <ul class="nav navbar-nav inner_nav">
       <li ><a  href="<?php echo $root_dir; ?>home.php" > Home</a></li>
                           <li class="dropdown">
                               
                                  <a class="dropdown-toggle" data-toggle="dropdown"   href="#" >Profile
                                  <span class="caret"></span></a>
                                <ul class="dropdown-menu dropmenu">
                                    <li><a href="profile.php">Edit Profile</a></li>
                                    <li class="divider"></li>
                                     <li> <a href="passwdchg.php" > Change Password</a></li>
                                </ul>
                          </li>
                           
                         <!--  <li class="dropdown" >
                                <a class="dropdown-toggle" data-toggle="dropdown"   href="#" > Reports<span class="caret"></span></a>
                                <ul class="dropdown-menu dropmenu ">
                                    <li> <a href="BookingReport.php">Cash Back</a></li>
                                    <li class="divider"></li>
                                     <li> <a href="TransactReport.php">Payment Reports</a></li>
                                </ul>
                          </li>-->
						  
                <li class="dropdown">
                                <a class="dropdown-toggle " data-toggle="dropdown" href="#" >Bookings<span class="caret"></span></a> 
                                  <ul class="dropdown-menu  dropmenu">
                                    <li> <a href="carviewbooking.php">Confirmed Booking</a></li>
                                    <li class="divider"></li>
                                     <li> <a href="carcancelledbooking.php">Cancelled Booking</a></li>
                                     <li class="divider"></li>
                                     <li> <a href="carmanagebooking.php">Manage Booking</a></li>
                            
                                    
                                  
                                </ul>
                          </li>
              
                            
    </ul>
    </div>
  </div>
</nav>
</div>
</div>

             

        

            <!-- /.navbar-static-side -->
          <div class="tophead">
          <?php 
        $class='';
        if(basename($_SERVER['PHP_SELF']) == 'recharge.php')
        {
          $class="quick_rec";
        }
          ?>
             <div class="container heads <?php echo $class;?>">
               
        			<?php if(basename($_SERVER['PHP_SELF']) != 'index.php'){ ?>
        			<a href="index.php">Dashboard</a>
        			<?php } ?>
        			<?php if(basename($_SERVER['PHP_SELF']) == 'profile.php'){ ?>
        			> <a href="profile.php">EditProfile</a>
        			<?php }else if(basename($_SERVER['PHP_SELF']) == 'passwdchg.php'){ ?>
        			> <a href="passwdchg.php">Change Password</a>
        			<?php }else if(basename($_SERVER['PHP_SELF']) == 'BookingReport.php'){ ?>
        			> <a href="graphs.html">Booking Reports</a>
        			<?php }else if(basename($_SERVER['PHP_SELF']) == 'TransactReport.php'){ ?>
        			> <a href="TransactReport.php">Payment Reports</a>
					<?php }else if(basename($_SERVER['PHP_SELF']) == 'ManageBooking.php'){ ?>
					> <a href="ManageBooking.php">Bus Manage Booking</a>
					<?php }else if(basename($_SERVER['PHP_SELF']) == 'flightmanagebooking.php'){ ?>
					> <a href="flightmanagebooking.php">Flight Manage Booking</a>
					<?php }else if(basename($_SERVER['PHP_SELF']) == 'guidemanagebooking.php'){ ?>
					> <a href="guidemanagebooking.php">Guide Manage Booking</a>
					<?php }else if(basename($_SERVER['PHP_SELF']) == 'hotelmanagebooking.php'){ ?>
					> <a href="hotelmanagebooking.php">Hotel Manage Booking</a>
        			<?php }else if(basename($_SERVER['PHP_SELF']) == 'CancelledBooking.php'){ ?>
        			> <a href="CancelledBooking.php">Bus Cancelled Booking</a>
					<?php }else if(basename($_SERVER['PHP_SELF']) == 'flightcancelledbooking.php'){ ?>
					> <a href="flightcancelledBooking.php">Flight Cancelled Booking</a>
        			<?php }else if(basename($_SERVER['PHP_SELF']) == 'guidecancelledbooking.php'){ ?>
					> <a href="guidecancelledBooking.php">Guide Cancelled Booking</a>
					<?php }else if(basename($_SERVER['PHP_SELF']) == 'hotelcancelledbooking.php'){ ?>
					> <a href="hotelcancelledBooking.php">Hotel Cancelled Booking</a>
					<?php }else if(basename($_SERVER['PHP_SELF']) == 'ViewBooking.php'){ ?>
        			> <a href="ViewBooking.php">Bus Confirmed Booking</a>
					<?php }else if(basename($_SERVER['PHP_SELF']) == 'flightviewbooking.php'){ ?>
        			> <a href="flightviewbooking.php">Flight Confirmed Booking</a>
					<?php }else if(basename($_SERVER['PHP_SELF']) == 'guideviewbooking.php'){ ?>
        			> <a href="guideviewbooking.php">Guide Confirmed Booking</a>
					<?php }else if(basename($_SERVER['PHP_SELF']) == 'hotelviewbooking.php'){ ?>
        			> <a href="hotelviewbooking.php">Hotel Confirmed Booking</a>
					<?php }else if(basename($_SERVER['PHP_SELF']) == 'inbox.html'){ ?>
        			> <a href="inbox.html">View Booking</a>
        			<?php }else if(basename($_SERVER['PHP_SELF']) == 'compose.html'){ ?>
        			> <a href="compose.html">Cancel Booking</a>
        			<?php }else if(basename($_SERVER['PHP_SELF']) == 'recharge.php'){ ?>
        			> <a href="recharge.php" >Quick Recharge</a>
        			<?php }else if(basename($_SERVER['PHP_SELF']) == 'paymentlink.php'){ ?>
        			> <a href="recharge.php">Payment Link</a>
        			
					<?php }else if(basename($_SERVER['PHP_SELF']) == 'flightmanagebooking.php' || basename($_SERVER['PHP_SELF']) == 'guidemanagebooking.php'){ ?>
					> <a href="flightmanagebooking.php">Manage Booking</a>
					<?php } ?>
             </div>
            </div>
		
        </nav>