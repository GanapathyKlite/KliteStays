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
<link rel="shortcut icon" type="image/png" href="images/icon.png" />
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
	
	 
 <script type="text/javascript" src="js/bootstrapValidator.js"></script> 
 <script type="text/javascript" src="js/agentpasswdupte.js"></script> 
 
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
</head>
<body>
<div class="logo">

<div class="container logo_container">
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 logos logoss">
        <a  href="../home.php"><img src="images/logo.png" "></a>
    </div>
            <?php
			$id=	$_SESSION['refid'];
							include('../include/database/config.php');
							$gen_balance=$database->query("select available_balance from ps_agents where reference='$id'")->fetchAll();
							$available_balance = $gen_balance[0][0];
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
        <b >Balance Amount : &#8377;</b> 
        <b>
        <?php echo (isset($available_balance) && !empty($available_balance) ? number_format($available_balance, 2, ',', '') : '0,00'); ?>

        </b>
        </li> 
    <div class="dropdown drop_agentid"  >
         <button class="btn btn-primary dropdown-toggle username_button" type="button" data-toggle="dropdown">
     
             <li class="username"> <a > <span class="glyphicon glyphicon-user userna"></span><?php echo $_SESSION['username']; ?> <span class="caret"></span></a></li>
            <li class="agentid">
              <a href="#">Agent-Id <?php echo $_SESSION['refid']; ?></a></li>
        
        
       
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
                                    <li><a href="profile.php">Edit Profile<span class="glyphicon glyphicon-edit glyphi"> </span></a></li>
                                    <li class="divider"></li>
                                     <li> <a href="passwdchg.php" > Change Password<span  class="glyphicon glyphicon-wrench glyphi"></span></a></li>
                                </ul>
                          </li>
                           
                           <li class="dropdown" >
                                <a class="dropdown-toggle" data-toggle="dropdown"   href="#" > Reports<span class="caret"></span></a>
                                <ul class="dropdown-menu dropmenu ">
                                    <li> <a href="graphs.html">Booking Reports<span class="glyphicon glyphicon-list-alt glyphi"></span></a></li>
                                    <li class="divider"></li>
                                     <li> <a href="TransactReport.php">Payment Reports<span class="glyphicon glyphicon-list-alt glyphi"></span></a></li>
                                </ul>
                          </li>
						  <li class="dropdown">
                                <a class="dropdown-toggle " data-toggle="dropdown" href="#" > Flight<span class="caret"></span></a> 
                                <ul class="dropdown-menu  dropmenu">
                                    <li> <a href="flightviewbooking.php">Confirmed Booking<span class="glyphicon glyphicon-ok glyphi"></span></a></li>
                                    <li class="divider"></li>
                                     <li> <a href="flightcancelledbooking.php">Cancelled Booking<span class="glyphicon glyphicon-remove glyphi"></span></a></li>
                                     <li class="divider"></li>
                                     <li> <a href="flightmanagebooking.php">Manage Booking<span class="glyphicon glyphicon-pencil glyphi"></span></a></li>
                                </ul>
                          </li>
                           <li class="dropdown">
                                <a class="dropdown-toggle " data-toggle="dropdown" href="#" >  Bus<span class="caret"></span></a> 
                                <ul class="dropdown-menu  dropmenu">
                                    <li> <a href="ViewBooking.php"><span class="glyphicon glyphicon-ok glyphi"></span>Confirmed Booking</a></li>
                                    <li class="divider"></li>
                                     <li> <a href="CancelledBooking.php"><span class="glyphicon glyphicon-remove glyphi"></span>Cancelled Booking</a></li>
                                     <li class="divider"></li>
                                     <li> <a href="ManageBooking.php"><span class="glyphicon glyphicon-pencil glyphi"></span>Manage Booking</a></li>
                                </ul>
                          </li>
                             <li>
                            <a   href="recharge.php">    Easy Recharge</a>
                        </li>
                         <li>
                            <a    href="#">     Last Carts<span class="fa arrow"></span></a>
                         </li>

    </ul>
    </div>
  </div>
</nav>
</div>
</div>

             

        

            <!-- /.navbar-static-side -->
          <div class="tophead">
             <div class="container heads">
               
        			<?php if(basename($_SERVER['PHP_SELF']) != 'index.php'){ ?>
        			<a href="index.php">Dashboard</a>
        			<?php } ?>
        			<?php if(basename($_SERVER['PHP_SELF']) == 'profile.php'){ ?>
        			> <a href="profile.php">EditProfile</a>
        			<?php }else if(basename($_SERVER['PHP_SELF']) == 'passwdchg.php'){ ?>
        			> <a href="passwdchg.php">Change Password</a>
        			<?php }else if(basename($_SERVER['PHP_SELF']) == 'graphs.html'){ ?>
        			> <a href="graphs.html">Booking Reports</a>
        			<?php }else if(basename($_SERVER['PHP_SELF']) == 'TransactReport.php'){ ?>
        			> <a href="TransactReport.php">Payment Reports</a>
        			<?php }else if(basename($_SERVER['PHP_SELF']) == 'ViewBooking.php'){ ?>
        			> <a href="ViewBooking.php">Confirmed Booking</a>
        			<?php }else if(basename($_SERVER['PHP_SELF']) == 'CancelledBooking.php'){ ?>
        			> <a href="CancelledBooking.php">Cancelled Booking</a>
        			<?php }else if(basename($_SERVER['PHP_SELF']) == 'inbox.html'){ ?>
        			> <a href="inbox.html">View Booking</a>
        			<?php }else if(basename($_SERVER['PHP_SELF']) == 'compose.html'){ ?>
        			> <a href="compose.html">Cancel Booking</a>
        			<?php }else if(basename($_SERVER['PHP_SELF']) == 'recharge.php'){ ?>
        			> <a href="recharge.php">Quick Recharge</a>
        			<?php }else if(basename($_SERVER['PHP_SELF']) == 'recharge.php'){ ?>
        			> <a href="recharge.php">Last Carts</a>
        			<?php }else if(basename($_SERVER['PHP_SELF']) == 'flightviewbooking.php'){ ?>
					> <a href="flightviewbooking.php">View Booking</a>
					<?php }else if(basename($_SERVER['PHP_SELF']) == 'flightmanagebooking.php'){ ?>
					> <a href="flightmanagebooking.php">Manage Booking</a>
					<?php } ?>
             </div>
            </div>
		
        </nav>