<?php
error_reporting(E_ALL);


/*if($_SESSION['username']!="" && $_SESSION['username']!=null || (strpos($_SERVER['REQUEST_URI'], 'Createcustomer') !== false))
{
  
}
else{
   header("Location:".$root_dir."logout.php");
}*/
$inactive = 7200; // Set timeout period in seconds

if (isset($_SESSION['timeout'])) {
  $session_life = time() - $_SESSION['timeout'];
  if ($session_life > $inactive) {
    session_destroy();

    header("Location:" . $root_dir . "logout.php?status=timeout");
  }
} ?>

<?php
include('database/config.php');
$actual_link = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
//$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$cash_back = 0;
if (isset($_SESSION['authtnid']) && $_SESSION['authtnid'] != 0) {
  $id = $_SESSION['authtnid'];

  $cash_back = $database->query("select cash_back from ps_customers where id_customer='$id'")->fetchAll(PDO::FETCH_ASSOC);

  if (!empty($cash_back)) {
    $cash_back = $cash_back[0]['cash_back'];
  }
}

$REDIRECT_URIPART = '';
if (isset($_SERVER["REDIRECT_URIPART"])) {
  $REDIRECT_URIPART = $_SERVER["REDIRECT_URIPART"];
}
if ($REDIRECT_URIPART != '') {
  $name = implode(' ', explode('-', $REDIRECT_URIPART));

  $content_pages_static = $database_car->query("select * from ps_page_static where txtTitle='" . $name . "' and status=0 and action=''")->fetchAll(PDO::FETCH_ASSOC);
  $desc = $content_pages_static[0]['txtDescription'];
  $key = $content_pages_static[0]['txtKeywords'];
  $title = $content_pages_static[0]['txtTitle'];

}
$destination = $country = '';
$search = $search_remove = "";
$search_Add = $star = '';
$landmarks = $landmark = '';
$property_name = $selStarRating = $landmark = $address = '';

if (isset($_GET['property_name'])) {
  $property = explode('-in-', $_GET['property_name']);
  if (($pos = strpos($property[0], "_")) !== FALSE) {
    $whatIWant = substr($property[0], $pos + 1);
  }

  $hotel_name = str_replace('-', ' ', ($whatIWant));

  $qry = "SELECT p.*,l.txtLandmark from ps_property p left join ps_landmark l on(l.id_landmark=p.selLandmark) where txtPropertyName like '%" . $hotel_name . "%'";
  $hotel_p = $database->query($qry)->fetchAll(PDO::FETCH_ASSOC);
  $_GET['id_property'] = $hotel_p[0]['id_property'];

  $htl = array('hotel' => 1, 'resorts' => 2, 'apartment' => 3, 'villa' => 4, 'homestays' => 5, 'dormitory' => 6, 'service-apartment' => 3, 'guest-house' => 7, 'beach-resorts' => 8);

  $star_cat = array('Budget and Cheap' => 1, 'Budget and Cheap ' => 2, '3 Star' => 3, '4 Star' => 4, '5 Star' => 5, 'Budget 3 Star' => 3, 'Cheap 3 Star' => 3, 'Budget 4 Star' => 4, 'Cheap 4 Star' => 4);

  if ($hotel_p[0]['selPropertyTypeID']) {
    if (in_array($hotel_p[0]['selPropertyTypeID'], $htl)) {
      $hotel_type = array_search($hotel_p[0]['selPropertyTypeID'], $htl);
    }
  }
  if ($hotel_p[0]['selStarRating']) {
    if (in_array($hotel_p[0]['selStarRating'], $star_cat)) {
      $selStarRating = array_search($hotel_p[0]['selStarRating'], $star_cat);
    }
  }
  $property_name = $hotel_p[0]['txtPropertyName'];
  $landmark = $hotel_p[0]['txtLandmark'];
  $address = $hotel_p[0]['txtAddress1'];
}
?>
<?php


$description = "Klitestays Online Hotels Booking – You Get Budget, Cheap And Best Hotels at klitestays.com";
$keywords = "klitestays, stays inn, klitestays.com, Book Hotels, Rooms, Resorts, Apartment, Villas, Accommodation at klitestays";
$landmark = isset($_GET['landmark']) ? implode(' ', explode('-', $_GET['landmark'])) : '';

if (isset($_GET['country'])) {
  $country = explode('hotels-in-', $_GET['country'])[1];
}
if (isset($_GET['city'])) {
  $destination = $_GET['city'] = isset($_GET['goingto']) ? explode(',', $_GET['goingto'])[0] : $_GET['city'];

  if (isset($_GET['star'])) {
    $hotel_type = $_GET['hotel_type'] = $hotel_type = 'hotels';

    $star = explode('-', $_GET['star'])[0];
    if (($star == 'cheap') || ($star == 'budget')) {
      $star = explode('-', $_GET['star'])[1];

    }

  }
  if (isset($_GET['s_l_d'])) {

    $s_l_d = explode('-near-', explode('-in-', $_GET['s_l_d'])[0]);
    $_GET['star'] = $s_l_d[0];
    $star = explode('-', $s_l_d[0])[0];
    $_GET['landmark'] = $landmark = $s_l_d[1];

    $hotel_type = $_GET['hotel_type'] = $hotel_type = 'hotels';
  }
  if (isset($_GET['h_l_d'])) {
    $h_l_d = explode('-near-', explode('-in-', $_GET['h_l_d'])[0]);
    $_GET['hotel_type'] = $hotel_type = $h_l_d[0];
    $_GET['landmark'] = $landmark = $h_l_d[1];

  }
  if (isset($_GET['hotel_type'])) {
    $hotel_type = $_GET['hotel_type'];

  }

  $q = "SELECT c.*,s.name as state, co.name as country from ps_city c left join ps_state s on(c.id_state=s.id_state) left join  ps_country_lang co on(co.id_country=c.id_country) where c.name like '%" . $destination . "%' and status=0";
  $details = $database->query($q)->fetchAll(PDO::FETCH_ASSOC);
  $city_with_state = ucwords($destination) . ', ' . $details[0]['state'];
  if (isset($_GET['star'])) {
    $str = $_GET['star'];
    if ($_GET['star'] == 1 || $_GET['star'] == 2) {
      $str = 'Budget and Cheap';
    }
    $search_remove = $search_Add = $search = trim(implode(' ', explode('-', $str)));

  } else if ($_GET['hotel_type']) {
    $search_remove = $search_Add = $search = trim(implode(' ', explode('-', $_GET['hotel_type'])));

  }

  $pos = strpos($search, 'hotels');

  /* if(!$pos)
   {
     $search_Add=$search.' Hotels';
     $search_remove=$search_remove.' ';
   }
   if($pos)
   {
      $search_remove=trim(str_replace(' hotels','',$search));
      $search_remove=$search_remove.' ';

   }*/
  if (isset($_GET['landmark']) && $_GET['landmark'] != '') {
    $landmark = ucwords(implode(' ', explode('-', $_GET['landmark'])));
    $landmarks = ' Near ' . $landmark;
  }
  $description = ucwords($search_Add) . " in " . ucwords($destination) . $landmarks . " – Best Deal you can Get upto 70% off for All " . ucwords($search_Add) . " in " . ucwords($destination) . ", Book at Klitestays.com";
  $keywords = ucwords($search_remove) . " in " . ucwords($destination) . $landmarks . ", Booking Hotels in " . ucwords($destination) . ", Cheap Hotels in " . ucwords($destination) . ", Best Hotel Deals in " . ucwords($destination) . ", Hotels in " . ucwords($destination) . ", " . ucwords($destination) . ", Hotels";
  if ($property_name != '') {
    $description = ucwords($hotel_type) . ' ' . ucwords($property_name) . ucwords($search_Add) . " in " . ucwords($destination) . " – Best " . $selStarRating . " in " . ucwords($destination) . " you can Get upto 70% off for Each Booking  , Book  Hotel " . ucwords($property_name) . " at Klitestays.com";
    $keywords = ucwords($hotel_type) . " " . ucwords($property_name) . " , " . ucwords($property_name) . " " . ucwords($hotel_type) . " in " . ucwords($destination) . ", " . ucwords($property_name) . ", " . $selStarRating . " " . $property_name . "  , " . ucwords($hotel_type) . " " . ucwords($property_name) . " tariff, " . ucwords($hotel_type) . " " . ucwords($property_name) . " " . $landmark . ", " . ucwords($hotel_type) . " " . ucwords($property_name) . " " . $address . " , " . ucwords($hotel_type) . " " . ucwords($property_name) . " Deals";
  }

} else {
  $link = $_SERVER['PHP_SELF'];
  $link_array = explode('/', $link);
  $pagess = end($link_array);
  $pagename = explode('.', $pagess);

  if ($pagename[0] == 'contact') {
    $res = $database->query("select * from ps_page_contact where action=''");

  } else if ($pagename[0] == 'index') {
    $res = $database->query("select * from ps_page_home where action=''");

  } else if ($pagename[0] == 'home') {
    $res = $database->query("select * from ps_page_home where action=''");
  } else {

    $res = '';
  }
  if ($res != '') {
    $contact = $res->fetchAll(PDO::FETCH_ASSOC);

    $desc = $contact[0]['txtDescription'];

    $key = $contact[0]['txtKeywords'];
  }


}
if (isset($_GET['from_seo']) && $_GET['from_seo'] == 1) {

  $pg_title = $database->query("SELECT * from ps_seohotel where url='" . $actual_link . "'  and status=0 and action=''")->fetchAll(PDO::FETCH_ASSOC);
  $destination = $_GET['city'];
  $keyword = $_GET['keyword'];
  $description = $pg_title[0]['txtDescription'];
  $keywords = $pg_title[0]['txtKeywords'];
  $title = $pg_title[0]['txtTitle'];
  $hotel_type = 'hotels';
}
if ((isset($_GET['property_name']) && $_GET['property_name'] != '') && (isset($_GET['keyword']) && $_GET['keyword'])) {
  $actual_link = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
  $pg_title = $database->query("SELECT * from ps_seohotel where url='" . $actual_link . "'  and status=0 and action=''")->fetchAll(PDO::FETCH_ASSOC);
  $destination = $_GET['city'];
  $keyword = $_GET['keyword'];
  $description = $pg_title[0]['txtDescription'];
  $keywords = $pg_title[0]['txtKeywords'];
  $title = $pg_title[0]['txtTitle'];
  $hotel_type = 'hotels';
}
$pg_titlesafd = $database->query("SELECT * from ps_seohotel where url='" . $actual_link . "'  and status=0 and action=''")->fetchAll(PDO::FETCH_ASSOC);

if (!empty($pg_titlesafd)) {
  $description = $pg_titlesafd[0]['txtDescription'];
  $keywords = $pg_titlesafd[0]['txtKeywords'];
  $title = $pg_titlesafd[0]['txtTitle'];
}


$sitename = $database->query("select * from ps_page_home where action=''");
$sitename = $sitename->fetchAll(PDO::FETCH_ASSOC);
$sitename = $sitename[0]['txtTitle'];

if (!defined('SITENAME'))
  define('SITENAME', $sitename);
//print_r($contact);

?>
<!DOCTYPE html>
<html>

<head>

  <?php
  if ($property_name != '') {
    $tit = ucwords($hotel_type) . ' ' . ucwords($property_name) . "  in " . ucwords($destination) . " - Best " . ucwords($hotel_type) . ' ' . ucwords($property_name) . " Online Booking Klitestays.com";
  } else if ($search != '') {

    $tit = ucwords($search) . ' in ' . ucwords($destination) . $landmarks . ' -Best Deal Get upto to 70% off Online Booking Klitestays.com';

  } else {
    $tit = 'Klitestays - A Leading Online Hotel Booking Portal';
  }
 
  // if ($title != '') {
  //   $tit = $title;
  // }
  $extendtitle = $database->query("select * from ps_extendtitle where action=''")->fetchAll(PDO::FETCH_ASSOC);

  ?>
  <title>
    <?php echo $tit; ?> -
    <?php echo $extendtitle[0]['extendtitle'] ?>
  </title>

  <link rel="shortcut icon" href="<?php echo $root_dir ?>favicon.png" type="image/x-icon" />
  <meta name="description" content="<?php echo $description; ?>">
  <meta name="keywords" content="<?php echo $keywords; ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">


  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Roboto:300" rel="stylesheet">



  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

  <script type='text/javascript' src='<?php echo $root_dir; ?>js/jquery.blockUI.min.js?ver=2.70'></script>
  <script type='text/javascript' src='<?php echo $root_dir; ?>js/jquery.validate.min.js?ver=1.0'></script>
  <script type='text/javascript' src='<?php echo $root_dir; ?>js/jquery.prettyPhoto.js?ver=1.0'></script>
  <script type='text/javascript' src='<?php echo $root_dir; ?>js/jquery.lightSlider.js?ver=1.0'></script>
  <script type='text/javascript' src='<?php echo $root_dir; ?>js/accommodations.js?ver=7.21'></script>
  <script type='text/javascript' src='<?php echo $root_dir; ?>js/inquiry.js?ver=7.21'></script>
  <script type='text/javascript' src='<?php echo $root_dir; ?>js/hoverIntent.min.js?ver=1.8.1'></script>
  <script type='text/javascript' src='<?php echo $root_dir; ?>js/maxmegamenu.js?ver=2.3.8'></script>


  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/smoothscroll/1.4.6/SmoothScroll.min.js"></script>
  <script type="text/javascript" src="<?php echo $root_dir; ?>js/stickynav.js"></script>
  <script type="text/javascript" src="<?php echo $root_dir; ?>js/plugins/fancybox/jquery.fancybox.min.js"></script>
  <script src='https://cdn.rawgit.com/pguso/jquery-plugin-circliful/master/js/jquery.circliful.min.js'></script>
  <script type="text/javascript" src="<?php echo $root_dir; ?>js/jquery.validate.min.js"></script>
  <script src="<?php echo $root_dir; ?>js/loadingoverlay.min.js"></script>
  <script src="<?php echo $root_dir; ?>js/jquery-confirm.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css" />
  <script src="<?php echo $root_dir; ?>js/owl.carousel.js"></script>
  <script src="<?php echo $root_dir; ?>js/script_createcustomer.js"></script>
  <script src="<?php echo $root_dir; ?>js/alertify.js"></script>
  <script src="<?php echo $root_dir; ?>js/script.js"></script>
  <script type="text/javascript"
    src="https://cdnjs.cloudflare.com/ajax/libs/jquery.bootstrapvalidator/0.5.3/js/bootstrapValidator.min.js"> </script>


  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

  <!-- Font Style Link -->
  <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
  <!-- Including jQuery Date UI with CSS -->
  <link href="https://code.jquery.com/ui/1.10.2/themes/smoothness/jquery-ui.min.css" rel="Stylesheet">
  </link>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/hover.css/2.1.1/css/hover-min.css">
  <link href="<?php echo $root_dir; ?>jquery-ui-1.12.1.custom/jquery-ui.css" rel="stylesheet">
  <link href="<?php echo $root_dir; ?>jquery-ui-1.12.1.custom/jquery-ui.min.css" rel="stylesheet">
  <link href="<?php echo $root_dir; ?>jquery-ui-1.12.1.custom/jquery-ui.structure.css" rel="stylesheet">
  <link href="<?php echo $root_dir; ?>jquery-ui-1.12.1.custom/jquery-ui.structure.min.css" rel="stylesheet">
  <link href="<?php echo $root_dir; ?>jquery-ui-1.12.1.custom/jquery-ui.theme.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <link type="text/css" rel="stylesheet" href="<?php echo $root_dir; ?>js/plugins/fancybox/jquery.fancybox.css" />
  <link rel="stylesheet" href="<?php echo $root_dir; ?>css/owl.carousel.min.css">

  <link rel="stylesheet" href="<?php echo $root_dir; ?>css/prettyPhoto.min.css">
  <link rel="stylesheet" href="<?php echo $root_dir; ?>css/owl.theme.default.min.css">
  <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/jquery.bootstrapvalidator/0.5.3/css/bootstrapValidator.min.css" />


  <link href="<?php echo $root_dir; ?>css/ss.css" rel="stylesheet">
  <link href='<?php echo $root_dir; ?>css/styles.css' type='text/css' />

  <link href="<?php echo $root_dir; ?>css/style.css" rel="stylesheet">
  <script type='text/javascript' src='<?php echo $root_dir; ?>js/scripts.js'></script>
   <?php if (isset($payment_page)&&$payment_page) { ?>
  <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
   <?php }?>

</head>

<body data-rsssl=1
  class="home page-template page-template-byt_home page-template-byt_home-php page page-id-477 mega-menu-primary-menu">
  <input type="hidden" value="<?php echo $root_dir; ?>" id="root_dir">

  <!--header-->
  <header class="header" role="banner">
    <div class="wrap">
      <!--logo-->
      <div class="logo"><a href="<?php echo $root_dir; ?>"><img src="<?php echo $root_dir; ?>staysinn.png"
            alt="Company Name" /></a></div>
      <!--//logo-->
      <!--ribbon-->
      <div class="ribbon section_bg">
        <nav>


          <?php
          if (isset($_SESSION['authtnid']) && $_SESSION['authtnid'] != '0') { ?>
          
            <?php echo $_SESSION['first_name']; ?>


            <ul class="profile-nav">
              <li class="active"><a href="javascript:void(0);" title="My Account">My Account</a></li>


              <li>
                <a class=" fn login_lightbox toggle_lightbox" href="javascript:void(0)"
                  style="margin-left: -10px;margin-right: -6px;">customer ID
                  <?php echo $_SESSION['reference']; ?>
                </a>
              </li>
              <li>
                <a class=" fn login_lightbox toggle_lightbox" href="<?php echo $root_dir; ?>customerdashboard/profile.php"
                  style="margin-left: -10px;">Dashboard<span
                    class="glyphicon glyphicon-dashboard pull-right glyph_dash"></span></a>
              </li>
              <li>
                <a class=" fn login_lightbox toggle_lightbox" href="<?php echo $root_dir; ?>logout.php">Logout<span
                    class="glyphicon glyphicon-log-out pull-right glyph_dash"></span></a>
              </li>
            </ul>



            <?php


          } else { ?>

            <!--  <li><a class="fn register_lightbox toggle_lightbox" href="javascript:void(0);" title="Register">Register</a></li>
       <li><a class="fn Dashboard_lightbox toggle_lightbox" href="javascript:void(0);" title="Dashboard">Dashboard</a></li> -->
            <!--   <li><a class="fn Logout_lightbox toggle_lightbox" href="javascript:void(0);" title="Logout">Logout</a></li> -->
            <!-- <li><a class="fn Customer_id_lightbox toggle_lightbox" href="javascript:void(0);" title="Customer Id">Customer Id</a></li> -->


            <a class="login_signup" data-toggle="modal" href="#myModal" title="Login">Log In / Sign Up</a>









          <?php }
          ?>








        </nav>
      </div>
      <!--//ribbon-->
      <!--search-->
      <div class="search">
        <!-- <form id="searchform" method="get" action="">
                           <input type="search" placeholder="Search entire site here" name="s" id="search_custom" /> 
                           <input type="submit" id="searchsubmit" value="" name="searchsubmit" style="background: transparent!important;">
                        </form> -->

        <a href="<?php echo $root_dir; ?>hotel_signup.php" style="padding: 7px 18px;font-size: 15px;"
          class="text_color">Hoteliers ? SignUp</a>
      </div>
      <!--//search-->
      <!--contact-->
      <div class="contact">
        <span>24/7 Support number</span>
        <span class="number">0413 - 2650500</span>
      </div>
      <!--//contact-->
    </div>
    <!--primary navigation-->
    <!-- <div id="mega-menu-wrap-primary-menu" class="mega-menu-wrap">
                     <div class="mega-menu-toggle" tabindex="0">
                        <div class='mega-toggle-block mega-menu-toggle-block mega-toggle-block-left mega-toggle-block-1' id='mega-toggle-block-1'></div>
                     </div>

                     <ul id="mega-menu-primary-menu" class="mega-menu mega-menu-horizontal mega-no-js" data-event="hover_intent" data-effect="disabled" data-effect-speed="200" data-second-click="close" data-document-click="collapse" data-vertical-behaviour="standard" data-breakpoint="1040" data-unbind="true">
                        <li class='mega-menu-item mega-menu-item-type-post_type mega-menu-item-object-page mega-align-bottom-left mega-menu-flyout mega-menu-item-487' id='mega-menu-item-487'><a class="mega-menu-link" href="search.php">Hotels</a></li>

                        
                     </ul>
                  </div> -->
    <!--//primary navigation-->
    <div class="clearfix"></div>

    <div class="container">
      <div class="col-sm-2"></div>
      <div class="col-sm-10">
        <ul class="menu_newbus col-sm-12">
          <li><a href=""><i class="fa fa-plane" aria-hidden="true"></i>Flight</a></li>
          <li><a class="active  " href=""><i class="fa fa-bed" aria-hidden="true"></i>Hotel</a></li>
          <li><a href=""><i class="fa fa-bus" aria-hidden="true"></i>Bus</a></li>
          <li><a href=""><i class="fa fa-car" aria-hidden="true"></i>Car</a></li>
        </ul>
      </div>



    </div>



  </header>





  <style>
    .menu_newbus {
      margin: 0;
      margin-left: 30px;
      padding: 10px 0 0 0;
    }

    .menu_newbus li .fa {
      display: block;
      font-size: 24px;
      margin-bottom: 10px;
    }


    .menu_newbus li {
      margin-right: 40px;
      font-size: 14px;
      float: left;
      text-align: center;
    }

    .menu_newbus li a {
      font-size: 14px;
      text-decoration: none !important;
    }

    .menu_newbus li a:hover,
    .menu_newbus li a:active,
    .menu_newbus li a:focus {
      color: #8e8e8e;
    }

    .menu_newbus li a>.active {
      color: #000;
    }
  </style>

  <!--//header-->
  <!--  -->

  <script type="text/javascript">
    $(document).ready(function () {
      $("#closeSignIn").click(function () {
        $("#myModal").modal('hide');
      });
    });
  </script>