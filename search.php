<?php 
   session_start();
   
   if(isset($_SESSION['authtnid'])!=0){
   $currentpage="car";
    include('include/header.php');
    error_reporting(E_ALL);
   include('car/config.php');
   } 
   else
   {
    header("Location: home.php");
   }

   $Vechicle = $database->query("SELECT * from ps_car where status=0 and action=''")->fetchAll(PDO::FETCH_ASSOC);
if(isset($Vechicle) && !empty($Vechicle)){
  foreach($Vechicle as $Vechiclek => $VechicleN){
    $vechicleArr[$VechicleN['id_car']]=$VechicleN['txtVechicleName'];
    }
  }

 ?> 








<link href="car/css/style.css" rel="stylesheet" type="text/css">
<script type="text/javascript">
   $(document).ready(function() {


    $("#add").click(function() {

     // var id=$(this).attr("data");
      //var did=$("#field"+id).prev().attr("data");
     // var sid=$(this).parent().next().attr("data");
     // 
       var lastid=$(".fieldwrapper" ).last().attr("data");
      var attr=$("#add").attr("data");
      var vari=parseInt(attr)-1;
       var destinationval=$("#text_desti-"+lastid).val();
      if(typeof destinationval== 'undefined')
      {
        destinationval=$("#text_desti-0").val();
        
      }{
        destinationval=destinationval;
      }
      var intId = $("#buildyourform div").length + 1;
      var funccall=intId+1;
      var fieldWrapper = $("<div class=\"fieldwrapper\" data="+ intId +" id=\"field" + intId + "\"/>");
      var fName = $("<input name='sourcecity[]' value='" + destinationval + "' readonly type=\"text\"  id=\"sourseid-"+ intId +"\" placeholder=\"Source City\" style=\"\" class=\"col-xs-12 col-sm-5 col-md-4 fieldname fieldname0\" />");
      var fType =$("<input name='destinationcity[]' type=\"text\" placeholder=\"Destination City\" style=\"\" class=\"col-xs-12 col-sm-5 col-md-4 destination_city ui-autocomplete-input fieldname fieldname1\" id=\"text_desti-" + intId + "\" onclick=\"changesource_city(this.value," + funccall + ")\"/>");
      var removeButton = $("<button type=\"button\" style=\"background-color:transparent;color:white;\" class=\"  btn col-xs-6 col-sm-1 col-md-1 remove\" id=\"remove" + intId + "\"  data="+intId+"><span class=\"glyphicon glyphicon-remove\"></span></button><div class=\"clearfix\"></div>");
      attr++;
      $("#add").attr("data",attr);

      removeButton.click(function() {

      var id=$(this).attr("data");
      var did=$("#field"+id).prev().attr("data");
      var sid=$(this).parent().next().attr("data");
        if(typeof did=='undefined')
        {
        var destinationval=$("#text_desti-0").val();

        }
        else
        {
        var destinationval=$("#text_desti-"+did).val();

        }
      $("#sourseid-"+sid).val(destinationval);
      $(this).parent().remove();
      });

      fieldWrapper.append(fName);
      fieldWrapper.append(fType);
      fieldWrapper.append(removeButton);
      $("#buildyourform").append(fieldWrapper);


      var attrd=$(".remove").attr("data");
      $("#add").attr("data",attr);


      });
   
   
    

   });
   
</script>
<script type="text/javascript">
   $(document).ready(function(){
       $("#test1").click(function()
       {
           $("#form2").hide();
           $("#form1").show();
           $(".searching").val(1);
       });
       $("#test2").click(function()
       {
           $("#form1").hide();
           $("#form2").show();
            $(".searching").val(2);
   
       });
   
   });
     $(document).ready(function() {
      $('#pickuptime').timepicker({
        timeFormat: 'h:mm p',
        interval: 60,
        minTime: '1',
        maxTime: '11:00pm',
        defaultTime: '9',
        startTime: '1:00am',
        dynamic: false,
        dropdown: true,
        scrollbar: true
    });
     });
   $(function() {
    $(".datepicker").datepicker(
    { numberOfMonths: 2 ,
     minDate: 1,
    dateFormat: "dd-m-yy",
    onSelect: function(selected) {

          $(".datepicker1").datepicker("option","minDate", selected)

        }

    }); 
     $(".datepicker1").datepicker(
    { numberOfMonths: 2 ,
       minDate: $(".datepicker").val(),
       dateFormat: "dd-m-yy",
      onSelect: function(selected) {
         //  $(".datepicker").datepicker("option","maxDate", selected)

        }

  
    });
 });
 
    
   
</script>
<div class="banner car-banner">
   <div class="container conta_for_car">
      <div class="col-md-4 banner-left" style="position:relative;">
       
         <div id="wrapper">
            <!--<div class="slider-wrapper theme-default">
               <div id="slider" class="nivoSlider">
                  <?php 
                    include('include/database/config.php');
                     $bus_slider_details = $database->query("SELECT * from ps_sliders where modid='5' and status=1")->fetchAll();
                     if(isset($bus_slider_details) && !empty($bus_slider_details)){
                      foreach($bus_slider_details as $busslider){
                        echo '<img src="'._BO_IMG_DIR_.'car/'.$busslider['imagedata1'].'" data-thumb="'._BO_IMG_DIR_.'car/'.$busslider['imagedata1'].'" alt="" />';
                      }
                     }
                     ?>
                
               </div>
               <div id="htmlcaption" class="nivo-html-caption"></div>
            </div>-->
         </div>

      </div>
      <div class="col-md-8 banner-right">
         <div class="sap_tabs">
            <div class="row" style="float:none;">
               <div class="booking-info about-booking-info">
                  <div class="col-md-12">
                     <h2>Book Car </h2>
                  </div>
               </div>
            </div>




            <div class="row title_fixed" >
               <div class="col-xs-12 col-sm-5 col-md-4">
                  <div class="col-xs-1 col-sm-2 col-md-2">
                     <input class="radio_fixed" type="radio" name="test" value="test1" id="test1" checked>
                  </div>
                  <label class="tailor_iterinary">Fixed Iterinary</label>
               </div>
               <div class=" col-xs-12 col-sm-7 col-md-4">
                  <div class="col-xs-1  col-sm-2 col-md-2">
                     <input class="radio_fixed" type="radio" name="test" value="test1" id="test2">
                  </div>
                  <label class="tailor_iterinary">Tailor Made Iterinary</label>
               </div>
            </div>
            <div class="col-md-12 fixed_contain_values" id="form1" style=" ">
               <form action="car/carbookfixedsearch.php" method="GET" class="car_search">
                  <div class="row">
                     <div class=" col-xs-12 col-sm-6 col-md-6">
                        <input type="text" class="form-control source_city" id="source_city" placeholder="Arrival city"  name="source_city" required="" oninvalid="setCustomValidity('Enter Arrival City')" oninput="setCustomValidity('')"  >
                        <input type="hidden" class="form-control source_id_city" id="source_id_city"  name="source_id_city" value="">
                        <input type="hidden" class="form-control source_name" id="source_name"  name="source_name" value="">
                     </div>
                     <div class="col-xs-12 col-sm-6 col-md-6 depart_cit">
                        <input type="text" class="form-control destination_city"  id="destination_city" placeholder="Destination City" name="destination_city" required="" oninvalid="setCustomValidity('Enter Destination City')" oninput="setCustomValidity('')" >
                        <input type="hidden" class="form-control destination_id_city" id="destination_id_city"  name="destination_id_city" value="">
                        <input type="hidden" class="form-control destination_name" id="destination_name"  name="destination_name" value="">
                     </div>
                  </div>
                  <div class="row">
                     <div class=" col-xs-12  col-sm-6 col-md-3">
                     <span style="" class="glyphicon glyphicon-calendar calender_glyphi" aria-hidden="true"></span>
                        <input style="position:relative;" type="text" id="datepicker" class="form-control start_date datepicker"   placeholder="Start Date" name="start_date" required="" oninvalid="setCustomValidity(this.willValidate?'':'Select Departure Date')" onchange="setCustomValidity('')" >
                     </div>
                     <div class="col-xs-12   col-sm-6 col-md-3">
                      <span style="" class="glyphicon glyphicon-calendar calender_glyphi"  aria-hidden="true"></span>
                        <input  style="position:relative;" type="text" id="datepicker1" class="end_date form-control datepicker1"  placeholder="End Date" name="end_date" required="" oninvalid="setCustomValidity(this.willValidate?'':'Select Departure Date')" onchange="setCustomValidity('')" >
                     </div>
                 <!--   <div class=" col-xs-12  col-sm-6 col-md-3 selectors ">
                        <div class="col-xs-6  col-sm-6 col-md-2 days_night"  >
                           <input style="border:none;" type="text" class="  form-control" maxlength="2"   placeholder="Days" name="noofdays" >
                        </div>
                        <div class=" col-xs-6 col-sm-6 col-md-2 days_night night_only"  >
                           <input style="border:none;" type="text" class="  form-control"  maxlength="2"  placeholder="Night" name="noofnights"> 
                        </div>
                     </div>-->
                     <div class="col-xs-12  col-sm-6 col-md-4 " >
                        <select class=" selectors"  id="input_for_car"  style="color:#555!important;height:29px;padding-left:12px;" required=""  oninvalid="setCustomValidity('select Car Type')" oninput="setCustomValidity('')" name="VechicleIds" >
                           <option value="" >Select Car Type</option>
                           <?php foreach($vechicleArr as $key=>$value)
                           {
                            echo  '<option value="'.$key.'">'.$value.'</option>';
                          }
                           ?>
                        </select>
                     </div>
                 
                     <div class="col-md-2 col-sm-6 col-xs-12  ">
                     <input type="hidden" name="searching" class="searching" value="1">
                     <input class="submit_car pull-right btn-red" type="submit" value="search" > 
                     </div>
                  </div>
               </form>
            </div>
            <div class="col-md-12 container_nam" id="form2"  style="display:none;" >
             <div class="row">
                     <!-- <div class="col-md-12">
                <div class="tabbable" style=" margin-bottom: 13px;">
                  <ul class="">
                     <li><a  class="active" href="javascript:void(0);" id="searchOpt1" onclick="searchOption(this.id, '1');">Outstation</a></li>
                     <li><a class="" href="javascript:void(0);" id="searchOpt2" onclick="searchOption(this.id, '2');">Local</a></li>
                     <li><a href="javascript:void(0);" id="searchOpt3" onclick="searchOption(this.id, '3');">Transfers</a></li>
                  </ul>
                 <div class="clearfix"></div>
                  </div>
                  </div>-->
                  </div>
                  <div class="clearfix"></div>

                  <div class="" style=" margin-bottom: 13px;">
                    <div class="row">
                      <div class="col-md-12">
                     <div class="remains" id="R_O_M_type" style="display: block">
                          <ul class=" inner_tab" >
                             <li ><a class="active tripOpt1 tripTypeOptions" href="javascript:void(0);" onclick="tripTypeOptionFun('tripOpt1','1');">Roundtrip</a></li>
                             <li><a class="tripTypeOptions tripOpt2" href="javascript:void(0);" onclick="tripTypeOptionFun('tripOpt2','2');">Oneway</a></li>
                             <li><a class="tripTypeOptions tripOpt3" href="javascript:void(0);" onclick="tripTypeOptionFun('tripOpt3','3');">Multicity</a></li>
                              <div class="clearfix"></div>
                          </ul>
                      </div>
                        <div class="remains" id="h_f_day" style="display: none">
                         <ul class="inner_tab">
                           <li><a class="active tripOpt1" href="javascript:void(0);" class="tripTypeOptions" onclick="tripTypeOptionFun('tripOpt1',1);">Full Day</a></li>
                           <li><a class="active tripOpt2" href="javascript:void(0);" class="tripTypeOptions" onclick="tripTypeOptionFun('tripOpt2',2);">Half Day</a></li>
                        </ul>
                        </div><div class="clearfix"></div>
                      <div class="remains" id="A_R_A_Trans" style="display: none">
                          <ul class="inner_tab">
                          <li ><a class="active tripOpt1" href="javascript:void(0);" class="tripTypeOptions"  onclick="tripTypeOptionFun('tripOpt1',1);">Airport</a></li>
                          <li><a class="tripOpt2" href="javascript:void(0);" class="tripTypeOptions"  onclick="tripTypeOptionFun('tripOpt2',2);">Railway Station</a></li>
                          <li><a class="tripOpt3" href="javascript:void(0);" class="tripTypeOptions" onclick="tripTypeOptionFun('tripOpt3',3);">Area</a></li>
                          </ul>
                      </div><div class="clearfix"></div>
                  </div>
                    </div><div class="clearfix"></div>
                  </div>
 <div class="clearfix"></div>
                  <form action="car/carbooking.php" method="GET" class="fixed_contain_values car_search custom_itin">
                    <input name="tripTypeOption" id="tripTypeOption" type="hidden" value="1">
                     <div class="cont-add-remove"><div class="row">
                        <div class="col-xs-11 col-sm-5 col-md-4 col-lg-4 mar_bot_mobsize">
                           <input type="text" class="form-control source_city"  oninvalid="setCustomValidity('Enter Source City')" oninput="setCustomValidity('')" placeholder="Source city" name="source_city">
                           <input type="hidden" class="form-control source_id_city" name="source_id_city" value="">
                    <input type="hidden" class="form-control source_name"   name="source_name" value="">
                        </div>
                        <div class="col-xs-11 col-sm-5 col-md-4 col-lg-4 mar_bot_mobsize tets">
                           <input id="text_desti-0" type="text" class="form-control destination_city" placeholder="Destination City" name="destination_city" required="" oninvalid="setCustomValidity('Enter Destination City')" oninput="setCustomValidity('')" onchange="changesource_city(this.value,1)">
                          <input type="hidden" class="form-control destination_id_city"  name="destination_id_city" value="">
                          <input type="hidden" class="form-control destination_name"   name="destination_name" value="">
                        </div>
                     </div>
                      <fieldset id="buildyourform"  style="display: none;  " class="col-xs-12 col-sm-12 col-md-12 col-lg-12 build_your_form">
                      </fieldset>
                      <button type="button"  style="display: none"  class="col-sm-1 col-md-1 add_ad_but" id="add" data="1" style=""><span class="glyphicon glyphicon-plus"></span></button>
                      <div class="clearfix"></div></div>

                     <div class="row">
                        <div class="col-xs-11 col-sm-5 col-md-4 mar_bot_mobsize">
                           <input type="text" class="form-control datepicker fromdate" class="form-control start_date"   placeholder="Start Date" name="start_date" required="" oninvalid="setCustomValidity(this.willValidate?'':'Select Departure Date')" onchange="setCustomValidity('')" placeholder="Start Date" >
                        </div>
                        <div class="col-xs-11 col-sm-5 col-md-4">
                           <input type="text"  class="form-control datepicker1 returndate end_date"  placeholder="Return Date" oninvalid="setCustomValidity(this.willValidate?'':'Select Return Date')" name="end_date" onchange="setCustomValidity('')" >
                        </div>
                     </div>   <div class="clearfix"></div>
                     <div class="row">
                        <div class="col-xs-11 col-sm-5 col-md-4 mar_bot_mobsize">
                           <div class='input-group bootstrap-timepicker timepicker' id='timepicker'>
                                        <input name="time" type="text" class="form-control input-small" placeholder="Enter Time" id="pickuptime">
                                         <span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
                                    </div>
                        </div>
                        <div class="col-xs-11 col-sm-5 col-md-4">
                            <select class=" selectors"  id="input_for_car"  style="color:#555!important;height:29px;padding-left:12px;" required=""  oninvalid="setCustomValidity('select Car Type')" oninput="setCustomValidity('')" name="VechicleIds" >
                            <option value="" >Select Car Type</option>

                            <?php foreach($vechicleArr as $key=>$value)
                            {
                            echo  '<option value="'.$key.'">'.$value.'</option>';
                            }
                            ?>
                            </select>
                        </div>
                        
                        <div class="col-xs-12 col-sm-12 col-md-2">
                         <input type="hidden" name="searching" class="searching" value="2">
                          <input class="submit_car btn-red" type="submit" value="search" > </div>
                     </div>
                  </form>
            </div> <!-- col-md-12 container_nam -->
            <div class="clearfix"></div>
     </div>
    </div>
    </div>
    <!-- //container -->
    </div>
    <div class="clearfix"> </div>


















 <style>
  body {
      position: relative; 
  }
  .affix {
      top:0;
      width: 100%;
      z-index: 9999 !important;
  }
  .navbar {
      margin-bottom: 0px;
  }

  .affix ~ .container-fluid {
     position: relative;
     top: 50px;
  }
  #section1 {padding-top:50px;height:500px;color: #fff; background-color: #1E88E5;}
  #section2 {padding-top:50px;height:500px;color: #fff; background-color: #673ab7;}
  #section3 {padding-top:50px;height:500px;color: #fff; background-color: #ff9800;}
  #section41 {padding-top:50px;height:500px;color: #fff; background-color: #00bcd4;}
  #section42 {padding-top:50px;height:500px;color: #fff; background-color: #009688;}
  </style>

<body data-spy="scroll" data-target=".navbar" data-offset="50">



<nav class="navbar navbar-inverse" data-spy="affix" data-offset-top="197">
  <div class="container-fluid">
    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>                        
      </button>
      <a class="navbar-brand" href="#">WebSiteName</a>
    </div>
    <div>
      <div class="collapse navbar-collapse" id="myNavbar">
        <ul class="nav navbar-nav">
          <li><a href="#section1">Section 1</a></li>
          <li><a href="#section2">Section 2</a></li>
          <li><a href="#section3">Section 3</a></li>
          <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#">Section 4 <span class="caret"></span></a>
            <ul class="dropdown-menu">
              <li><a href="#section41">Section 4-1</a></li>
              <li><a href="#section42">Section 4-2</a></li>
            </ul>
          </li>
        </ul>
      </div>
    </div>
  </div>
</nav>    

<div id="section1" class="container-fluid">
  <h1>Section 1</h1>
  <p>Try to scroll this section and look at the navigation bar while scrolling! Try to scroll this section and look at the navigation bar while scrolling!</p>
  <p>Try to scroll this section and look at the navigation bar while scrolling! Try to scroll this section and look at the navigation bar while scrolling!</p>
</div>
<div id="section2" class="container-fluid">
  <h1>Section 2</h1>
  <p>Try to scroll this section and look at the navigation bar while scrolling! Try to scroll this section and look at the navigation bar while scrolling!</p>
  <p>Try to scroll this section and look at the navigation bar while scrolling! Try to scroll this section and look at the navigation bar while scrolling!</p>
</div>
<div id="section3" class="container-fluid">
  <h1>Section 3</h1>
  <p>Try to scroll this section and look at the navigation bar while scrolling! Try to scroll this section and look at the navigation bar while scrolling!</p>
  <p>Try to scroll this section and look at the navigation bar while scrolling! Try to scroll this section and look at the navigation bar while scrolling!</p>
</div>
<div id="section41" class="container-fluid">
  <h1>Section 4 Submenu 1</h1>
  <p>Try to scroll this section and look at the navigation bar while scrolling! Try to scroll this section and look at the navigation bar while scrolling!</p>
  <p>Try to scroll this section and look at the navigation bar while scrolling! Try to scroll this section and look at the navigation bar while scrolling!</p>
</div>
<div id="section42" class="container-fluid">
  <h1>Section 4 Submenu 2</h1>
  <p>Try to scroll this section and look at the navigation bar while scrolling! Try to scroll this section and look at the navigation bar while scrolling!</p>
  <p>Try to scroll this section and look at the navigation bar while scrolling! Try to scroll this section and look at the navigation bar while scrolling!</p>
</div>

</div>  





















 <div class="container">
 <div class="route-heading">
      <h3 class="title">Frequently Used Packages</h3> 
      </div>

<?php
 include('include/database/config.php');
$seleteed = $database->query("SELECT * from ps_car_routes where action='' and status=0");
if($seleteed)
{
  $seleteed=$seleteed->fetchAll();
}

$packagecode=explode(',',$seleteed[0]['txtPackageCode']);
$image=explode(',',$seleteed[0]['image']);
foreach($packagecode as $key=>$code)
{

$packages = $database->query("SELECT v.id_car, ss.name as sstatename, sd.name as dstatename,  fc.*,c.name as sourcename, c.id_city as source_id, dc.id_city as dest_id, dc.name as destinationname  from ps_car v, ps_fixedcar  fc left join ps_city c on(fc.selArrivalCityId=c.id_city) left join ps_state ss on(fc.selArrivalStateId=ss.id_state) left join ps_city dc  on(fc.selDepartureCityId=dc.id_city) left join ps_state sd on(fc.selArrivalStateId=sd.id_state) where fc.txtFixedPackageCode='".$code."' and fc.status=0 and v.txtVechicleName = 'Sedan'")->fetchAll();


if($packages)
{
  $date=date('Y-m-d');
$fromdate=date('d-m-Y', strtotime($date. ' + 7 days'));
$todate=date('d-m-Y', strtotime($fromdate.' + '.$packages[0]['txtFixedNoOfNights'].' days '));
  echo '<div class="col-md-3 col-lg-3  col-sm-6" style="position: relative; margin-bottom:10px;"><div class=""  style="position:relative;">';
echo '<div class="dightdays-title">'.$packages[0]['txtFixedNoOfDays'].' Days / '.$packages[0]['txtFixedNoOfNights'].' Night ('.$packages[0]['sourcename'].' to '.$packages[0]['destinationname'].')</div>';
echo '<img width="100%" src="'._BO_IMG_DIR_.'car/'.$image[$key].'" data-thumb="'._BO_IMG_DIR_.'car/'.$image[$key].'" alt="" />';
echo '<div class="route-title"><form name="car-booking" method="GET" action="car/carbookfixedsearch.php">
<input type="hidden" name="source_city" value="'.$packages[0]['sourcename'].'">
<input type="hidden" value="'.$packages[0]['source_id'].'" name="source_id_city" >
<input type="hidden" name="source_name" value="'.$packages[0]['sourcename'].' , '.$packages[0]['sstatename'].'">
<input  type="hidden" name="destination_city" value="'.$packages[0]['destinationname'].'"><input name="destination_id_city" value="'.$packages[0]['dest_id'].'" type="hidden"><input name="destination_name" value="'.$packages[0]['destinationname'].' , '.$packages[0]['dstatename'].'" type="hidden"><input type="hidden"  name="start_date" value="'.$fromdate.'"><input type="hidden" name="end_date" value="'.$todate.'"><input type="hidden" name="VechicleIds" value="'.$packages[0]['id_car'].'"><input type="submit" value="View More" class="btn-red" style="font-size: 17px; background-image: -webkit-linear-gradient(bottom, #DB0B0B 19%, #910005 56%) !important;; border:1px solid #df3136;"></form></div>';

echo '<div class="clearfix"></div></div></div>';
}


 
}
  ?>
     

</div>
<!-- //container -->
<?php include('include/footer.php'); ?>
<script type="text/javascript">
    $(window).load(function() {
          $('#slider').nivoSlider({
            effect: 'fade'
          });
   });
        
        
   $(function() {
        $( ".source_city" ).autocomplete({
          minLength:1,
          scroll:true,
                source: function( request, response ) {
                  $.ajax({
                url: "car/readCity.php",
                dataType: 'json',
                type:'POST',
                data: 'city='+request.term,
                success: function (data) {               
                    response( $.map( data, function( item ) {
                  return {
                    label: item.name+" , "+item.statename,
                    value: item.name,
                    id_city:item.id_city
                  }
                }));
                }   
            });
           },
          select:function(event , ui){

          $(".source_id_city").val(ui.item.id_city);
          $(".source_name").val(ui.item.label);
          $(".destination_city").focus();
          
          }
         });
    
        $( ".destination_city" ).autocomplete({
          minLength:1,
          scroll:true,
                source: function( request, response ) {
                  $.ajax({
                url: "car/readCity.php",
                dataType: 'json',
                type:'POST',
                data: 'city='+request.term,
                success: function (data) {               
                    response( $.map( data, function( item ) {
                  return {
                    label: item.name+" , "+item.statename,
                    value: item.name,
                    id_city:item.id_city
                  }
                }));
                }   
            });
           },
          select:function(event , ui){
          $(".destination_id_city").val(ui.item.id_city);
          $(".destination_name").val(ui.item.label);
          $("#sourseid-1").val(ui.item.value);
          
          }
         });
      });
   
        //To select country name
        function selectCountry(val) {
        $("#search-box").val(val);
        $("#suggesstion-box").hide();
        }


    
function tripTypeOptionFun(name,id)
{
  
  $(".inner_tab a").removeClass("active");
  $("."+name).addClass("active");
  $("#tripTypeOption").val(id);

  $(".add_ad_but").css("display","none");
  $("#buildyourform").css("display","none");
  $(".remains").css("display","none");
// var traveltype=$("#travelTypeOption").val();
 $("#R_O_M_type").css("display","block");
 if(id==1||id==2)
    {
      $(".add_ad_but").css("display","none");
      $("#buildyourform").css("display","none");
    }
    if(id==1||id==3)
    {
      $(".destination_city").css("display","inline");
      $(".destination_city").attr("required","required");

      $(".returndate").attr("required","required");
      $(".returndate").css("display","inline");
    }
    if(id==2)
    {
      $(".destination_city").css("display","inline");
      $(".destination_city").attr("required","required");

      $(".returndate").css("display","none");
      $(".returndate").removeAttr("required");
    }
    if(id==3)
    {
      $(".add_ad_but").css("display","block");
      $("#buildyourform").css("display","block");

    }
 
 
function changesource_city(value,id)
{

     $( ".destination_city" ).autocomplete({
          minLength:1,
          scroll:true,
                source: function( request, response ) {
                  $.ajax({
                url: "car/readCity.php",
                dataType: 'json',
                type:'POST',
                data: 'city='+request.term,
                success: function (data) {               
                    response( $.map( data, function( item ) {
                  return {
                    label: item.name+" , "+item.statename,
                    value: item.name,
                    id_city:item.id_city
                  }
                }));
                }   
            });
           },
          select:function(event , ui){

          $(".destination_id_city").val(ui.item.id_city);
          $(".destination_name").val(ui.item.label);
           $("#sourseid-"+id).val(ui.item.value);
          
          }
         });
     
    }
</script> 
<script type="text/javascript">
   var _gaq = _gaq || [];
   _gaq.push(['_setAccount', 'UA-36251023-1']);
   _gaq.push(['_setDomainName', 'jqueryscript.net']);
   _gaq.push(['_trackPageview']);
   
   (function() {
     var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
     ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
     var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
   })();
   
</script>

 












