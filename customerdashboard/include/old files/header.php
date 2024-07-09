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
<title>Buddies Tours-A Leading B2B Travel Agency</title>
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
<div id="wrapper">
     <!-- Navigation -->
        <nav class="top1 navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
               <a class="navbar-brand" href="../home.php"><img src="images/logo.png" width="250" style="margin:10px 0 0 0;"></a>
			   
			   
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
		<li class="abb"> <b style="color:#333333;">Balance Amount : &#8377;</b> <b style="color:#DB0B0B;  font-size:14px;"><?php echo (isset($available_balance) && !empty($available_balance) ? number_format($available_balance, 2, ',', '') : '0,00'); ?></b></li>
		
			<ul class="nav navbar-nav navbar-right">
			
			<li class="dropdown">
  
	<a href="#" class="dropdown-toggle dcser" data-toggle="dropdown"><span class="glyphicon glyphicon-user pull-left cur"></span> <?php echo $_SESSION['username']; ?> <span class="glyphicon caret pull-right"></span></a>
	
	<a href="#" class="did">Agent-Id <?php echo $_SESSION['refid']; ?></a>
    <ul class="dropdown-menu">
     <!-- <li>
        <a href="#acc">Account Settings <span class="glyphicon glyphicon-cog pull-right"></span></a>
      </li>
      <li class="divider"></li>
      <li>
        <a href="#fav">Favourites<span class="glyphicon glyphicon-heart pull-right"></span></a>
      </li>
      <li class="divider"></li>
      <li>
        <a href="#status">Status <span class="glyphicon glyphicon-stats pull-right"></span></a>
      </li>-->
      
      <li><a href="../logout.php">Logout<span class="glyphicon glyphicon-log-out pull-right"></span></a></li>
	  
	  
    </ul>
  </li>
			</ul>
		<div id="div_top_hypers">
			<ul id="ul_top_hypers">
				<li><a href="<?php echo $root_dir; ?>home.php" class="a_top_hypers"> Home</span></a></li>
				<li>
					<a href="#" class="a_top_hypers"> Profile<span class="caret"></span></a>
					<ul class="dropdown-menu1">
	        <li class="dropdown-submenu">
	            <a href="profile.php">Edit Profile</a>
	            <a href="passwdchg.php" class="a_top_hypers"> Change Password</a>
	        </li>
	    </ul>
				</li>
				<li><a href="#" class="a_top_hypers"> Reports<span class="caret"></span></a>
					<ul class="dropdown-menu1">
	        <li class="dropdown-submenu">
	            <a href="graphs.html">Booking Reports</a>
							<a href="TransactReport.php">Payment Reports</a>
	        </li>
	    </ul>
				</li>
				<li><a href="#" class="a_top_hypers"> Manage Booking<span class="caret"></span></a>
					<ul class="dropdown-menu1">
	        <li class="dropdown-submenu">
	            <a href="ViewBooking.php">View Booking</a>
							<a href="CancelledBooking.php">Cancelled Booking</a>
	        </li>
	    </ul>
				</li>
				<li><a href="recharge.php" class="a_top_hypers"> Easy Recharge</a></li>
				<li><a href="#" class="a_top_hypers"> Last Carts</a></li>
			</ul>
		</div>
              <div class="navbar-default sidebar" role="navigation" style="display:none;">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">
                        <li>
                            <a href="index.php"><i class="fa fa-dashboard fa-fw nav_icon"></i>Dashboard</a>
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-user nav_icon"></i>Profile<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="profile.php">Change Profile</a>
                                </li>
                            </ul>
                            <!-- /.nav-second-level -->
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-bug nav_icon"></i>Reports<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="graphs.html">Booking Reports</a>
                                </li>
                                <li>
                                    <a href="TransactReport.php">Payment Reports</a>
                                </li>
                            </ul>
                            <!-- /.nav-second-level -->
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-book nav_icon"></i>Manage Booking<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="inbox.html">View Booking</a>
                                </li>
                                <li>
                                    <a href="compose.html">Cancelled Booking</a>
                                </li>
                            </ul>
                            <!-- /.nav-second-level -->
                        </li>
                        <li>
                            <a href="recharge.php"><i class="fa fa-flask nav_icon"></i>Easy Recharge</a>
                        </li>
                         <li>
                            <a href="passwdchg.php"><i class="fa fa-unlock-alt nav_icon"></i>Change Password<span class="fa arrow"></span></a>
                          <!--  <ul class="nav nav-second-level">
                                <li>
                                    <a href="forms.html">Basic Forms</a>
                                </li>
                                <li>
                                    <a href="validation.html">Validation</a>
                                </li>
                            </ul>-->
                            <!-- /.nav-second-level -->
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-shopping-cart nav_icon"></i>Last Carts<span class="fa arrow"></span></a>
                         <!--   <ul class="nav nav-second-level">
                                <li>
                                    <a href="basic_tables.html">Basic Tables</a>
                                </li>
                            </ul>-->
                            <!-- /.nav-second-level -->
                        </li>
                        <!--<li>
                            <a href="#"><i class="fa fa-sitemap fa-fw nav_icon"></i>Css<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="media.html">Media</a>
                                </li>
                                <li>
                                    <a href="login.html">Login</a>
                                </li>
                            </ul>
                            
                        </li>-->
                    </ul>
                </div>
                <!-- /.sidebar-collapse -->
            </div>
            <!-- /.navbar-static-side -->
            <div class="header_links">
			<?php if(basename($_SERVER['PHP_SELF']) != 'index.php'){ ?>
			<a href="index.php">Dashboard</a>
			<?php } ?>
			<?php if(basename($_SERVER['PHP_SELF']) == 'profile.php'){ ?>
			> <a href="profile.php">Profile</a>
			<?php }else if(basename($_SERVER['PHP_SELF']) == 'passwdchg.php'){ ?>
			> <a href="passwdchg.php">Change Password</a>
			<?php }else if(basename($_SERVER['PHP_SELF']) == 'graphs.html'){ ?>
			> <a href="graphs.html">Booking Reports</a>
			<?php }else if(basename($_SERVER['PHP_SELF']) == 'TransactReport.php'){ ?>
			> <a href="TransactReport.php">Payment Reports</a>
			<?php }else if(basename($_SERVER['PHP_SELF']) == 'ViewBooking.php'){ ?>
			> <a href="ViewBooking.php">View Booking</a>
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
			<?php } ?>
		</div>
        </nav>