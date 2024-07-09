<?php
$currentpage = "home";
include('include/header.php');

?>
<?php

$cityNames = $database->query("SELECT c.id_city,c.name,s.name as stateName from ps_city c left join ps_state s on(s.id_state = c.id_state) where c.status=0 and is_for_hotel=1")->fetchAll();
if (isset($cityNames) && !empty($cityNames)) {
  foreach ($cityNames as $cityK => $city) {
    $cityNamesArr[$cityK]['id'] = $city['id_city'];
    $cityNamesArr[$cityK]['label'] = $city['name'] . ', ' . $city['stateName'];
  }
}
$majorCityNames = $database->query("SELECT c.id_city,c.name,s.name as stateName from ps_city c left join ps_state s on(s.id_state = c.id_state) where c.status=0 and is_for_hotel=1 limit 30")->fetchAll();
?>
<style>
  .ui-state-hover,
  .ui-widget-content .ui-state-hover,
  .ui-widget-header .ui-state-hover,
  .ui-state-focus,
  .ui-widget-content .ui-state-focus,
  .ui-widget-header .ui-state-focus {
    border: none !important;
    background: #d92525 !important;
    font-weight: normal !important;
    color: #fff;
  }

  .ui-menu .ui-menu-item div {
    padding: 5px;
  }

  .ui-menu .ui-menu-item .ui-state-focus,
  .ui-menu .ui-menu-item .ui-state-active {
    font-weight: normal;
    margin: -1px;
    border: none !important;
    background: #d92525 !important;
    font-weight: normal !important;
    color: #fff !important;
  }

  .ui-menu .ui-state-disabled {
    font-weight: normal;
    margin: .4em 0 .2em;
    line-height: 1.5;
  }

  .ui-menu .ui-state-disabled a {
    cursor: default;
  }
</style>
<script type="text/javascript">
  var leavingfrom = <?php echo json_encode($cityNamesArr); ?>;

  $(document).ready(function() {
    $("#goingto").autocomplete({

      source: function(request, response) {
        var matcher = new RegExp("^" + $.ui.autocomplete.escapeRegex(request.term), "i");
        response($.grep(leavingfrom, function(item) {
          return matcher.test(item.label);
        }));
        $('.ui-autocomplete').css('width', '230px');
        // $('.ui-autocomplete').css('margin-left', '40px'); // HERE
      },
      select: function(event, ui) {

        $("#to_going1").val(ui.item.id);
        $("#name_goingto1").val(ui.item.label);

      },
      minLength: 2,
      scroll: true,
    });
  });
</script>

<div class="slider-wrap" style="position: relative;">
  <div id="rev_slider_2_1_wrapper" class="rev_slider_wrapper fullscreen-container" style="height:490px;padding: 0;background-repeat: no-repeat;background-position: center;background-image: url(images/hotel_bg1.jpg);background-attachment: fixed">


  </div>



  <div class="container " style="position: relative;">
    <div class="col-sm-12" style="position: absolute;left: 0;bottom: 0;">
      <div>
        <!--main content-->
        <div class="trans_parent">
          <section class=" tranaparent_div_class">




            <article class="">
              <form class="widget-search" class="hotel_search" action="hotels/search/book.php" method="get" id="hotel_search">

                <div>
                  <input type="hidden" name="id_property" value="2590">
                  <input type="hidden" name="goingto" value="Pondicherry, Pondicherry">
                  <input type="hidden" name="to_hidden" value="10">
                  <!-- <div class="col-sm-3">
                    <div class="where dt txt_color">
                      <p class="texttt" style="margin-left: 5px;">Where?</p>
                    </div>
                    <div class="where dd">

                      <div class="destination">
                        <label for="search_widget_term" class="txt_color">Search location</label>

                        <input type="text" placeholder="City Name"
                          class="typeahead1 input-md form-control tt-input ui-autocomplete-input" required=""
                          oninvalid="setCustomValidity('Enter City')" oninput="setCustomValidity('')" id="goingto"
                          autocomplete="off" name="goingto" style="width:100%; font-family: 'Montserrat', sans-serif;">
                        <input type="hidden" id="to_going1" name="to_hidden">
                        <input type="hidden" id="name_goingto1" name="to_name_hidden">
                      </div>
                    </div>
                  </div> -->
                  <div class="col-sm-6 col-md-6">
                    <div class="when dt txt_color">
                      <p class="texttt" style="margin-left: 5px;">When?</p>
                    </div>
                    <div class="when dd">
                      <div class="row" style="margin-top: 0;">

                        <div class="col-xs-12 col-sm-6">

                          <div class="datepicker">
                            <label for="search_widget_date_from" class="txt_color">Check-in date</label>
                            <div class="datepicker-wrap"><input type="text" id="checkin" name="hotel_month" style="" placeholder="Check In" oninvalid="setCustomValidity(this.willValidate?'':'Select Checkin Date')" value="<?php echo date('d-m-Y', strtotime('+24 hours')) ?>" onchange="setCustomValidity('')" /></div>
                            <input type="hidden" id="from" name="from" />
                          </div>
                        </div>
                        <div class="col-xs-12 col-sm-6">
                          <div class="datepicker">
                            <label for="search_widget_date_to" class="txt_color">Check-out date</label>
                            <div class="datepicker-wrap"><input type="text" placeholder="Check Out" id="checkout" name="hotel_month1" class="form-control" placeholder="Check Out" oninvalid="setCustomValidity(this.willValidate?'':'Select Checkout Date')" onchange="setCustomValidity('')" value="<?php echo date('d-m-Y', strtotime('+48 hours')) ?>" /></div>
                            <input type="hidden" id="to" name="to" />
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="">

                    <div class="">

                      <div class="col-xs-12  col-md-3 ">


                        <div>
                          <div class="when dt txt_color">
                            <p class=" texttt1" style="margin-left: 5px;">How many?</p>
                          </div>

                          <div class="section_room" style="cursor:pointer;position: relative; width: 100%; ">
                            <input type="hidden" id="guest" name="guest" value="">
                            <input type="hidden" id="guestval" value="0">
                            <input type="hidden" id="roomval" value="1">
                            <div id="button_add_roo" type="button" data-toggle="collapse" data-target="#demo" style="margin-top: 32px;text-align: left;"><span class="total_guest_room">1 Rooms,2
                                Guest</span><Span style="float: right;"><i class="fa fa-angle-down arrows_addroom" aria-hidden="true" style="position: absolute;top: 10px;right: 10px;"></i></Span></div>

                            <div class="collapse room_res1 " style="" id="demo">
                              <div class="inner_demo outer_pax">
                                <div class="col-sm-12 pax_container" data="1">
                                  <div class="row" style="margin-top: 4px;">
                                    <div class="col-sm-3 detail_pax detail_pax_1">
                                      <p class="detail_pax_p" style="font-family: 'Roboto',sans-serif;">Room
                                        <span class="roomnumber">1</span>
                                      </p>

                                    </div>

                                    <div class="col-sm-8 content_pax content_pax_1" style="margin-top: -20px;">
                                      <span id="mesg">only 3 guest allowed</span>
                                      <div class="row">
                                        <div class="col-sm-5 col-xs-6">
                                          <p class="head_title_room" style="font-family: 'Roboto',sans-serif;">Adult</p>

                                          <select class="adultlist adultlist_1 actives" onchange="changeadult(this.value,this)">
                                            <option value="1">1</option>
                                            <option selected="seletcted" value="2">2</option>
                                            <option value="3">3</option>

                                          </select>
                                          <input type="hidden" id="oldAdult_1" value="2">
                                        </div>

                                        <div class="col-sm-6 col-xs-6">
                                          <p class="head_title_room" style="margin-left: -34px;">Childern (5-12 yrs)</p>

                                          <select class="childlist childlist_1 actives" onchange="changechild(this.value,this)">
                                            <option selected="seletcted" value="0">0</option>
                                            <option value="1">1</option>
                                            <option value="2">2</option>

                                          </select>
                                          <input type="hidden" id="oldChild_1" value="0">
                                        </div>
                                      </div>
                                    </div>




                                    <div class="clearfix"></div>
                                  </div>
                                </div>
                              </div>
                              <div class="con_for_but"></div>
                              <button class="rooms_in_hotel " data="2" type="button" onclick="addroom(this)" style="font-family: 'Roboto',sans-serif;background: transparent!important;color: #dd3236!important;border:none!important;margin-top: -10px;padding: 5px 10px!important;font-size: 11px!important;font-weight: 500!important;">+
                                Add Room</button>
                              <span class="done" onclick="ondone()" style="color:#ad1f1f; font-family: 'Roboto',sans-serif;margin-right: 2px;">Done</span>
                            </div>
                          </div>
                        </div>



                        <div class="clearfix"></div>
                      </div>


                    </div>

                  </div>
                </div>
                <input style="position: absolute;
    left: 41.5%;
    bottom: 0px;
    width: 150px;
    height: 34px;
    border: none;
    -webkit-border-radius: 17px 17px 0 0;
    -moz-border-radius: 17px 17px 0 0;
    border-radius: 17px 17px 0 0;" type="submit" value="Search " class="gradient-button" id="search-submit" />
              </form>
            </article>
            <div class="clearfix"></div>







          </section>
        </div>
        <!--//main content-->
      </div>
      <!--//wrap-->
    </div>
  </div>


</div>

<div class="clearfix"></div>



<div class="book_with_us">
  <div class="container">
    <h1 class="hotel_book_htag">Why Book With Us?</h1>
    <div class="col-sm-3">
      <img src="images/bookus/hotel.png">
      <h3>Guaranteed hotel booking</h3>
      <p>Assured hotel booking with quality services</p>

    </div>
    <div class="col-sm-3">
      <img src="images/bookus/support.png">
      <h3>24 X 7 Customer Support</h3>
      <p>Happy to assist you at all times</p>


    </div>
    <div class="col-sm-3">
      <img src="images/bookus/pricetag.png">
      <h3>Best rates</h3>
      <p>Get a hotel within your budget for best prices</p>


    </div>
    <div class="col-sm-3">
      <img src="images/bookus/trusted.png">
      <h3>Happy customers</h3>
      <p>Over 2000 + happy travelers vouch for our dedicated service</p>

    </div>


  </div>



</div>
<div class="hotel_destination_div">

  <div class="container">
    <h1 class="hotel_offers_style">Trending Destinations</h1>

    <div class="owl-carousel owl-theme carousel_trending">

      <div class="item active  ">

        <img src="images/trending/chennai.jpg">
        <a href="https://klitestays.com/chennai/hotels-in-chennai" class="trans">
          <p class="place">Chennai</p>

        </a>

      </div>
      <div class="item   ">

        <img src="images/trending/mumbai.jpg">
        <a href="https://klitestays.com/mumbai/hotels-in-mumbai" class="trans">
          <p class="place">Mumbai</p>
        </a>


      </div>
      <div class="item  ">
        <img src="images/trending/hyderabad.jpg">
        <a href="https://klitestays.com/hyderabad/hotels-in-hyderabad" class="trans">
          <p class="place">Hyderabad</p>
        </a>

      </div>

      <div class="item   ">

        <img src="images/trending/delhi.jpg">
        <a href="https://klitestays.com/delhi/hotels-in-delhi" class="trans">
          <p class="place">Delhi</p>
        </a>


      </div>
      <div class="item  ">

        <img src="images/trending/bangalore.jpg">
        <a href="https://klitestays.com/bangalore/hotels-in-bangalore" class="trans">
          <p class="place">Bangalore</p>
        </a>


      </div>


      <div class="item   ">

        <img src="images/trending/goa.jpg">
        <a href="https://klitestays.com/goa/hotels-in-goa" class="trans">
          <p class="place">Goa</p>
        </a>


      </div>




    </div>


    <div style="text-align: center;"><button class="more_destinations" type="button"> View More Destinations</button>
    </div>





  </div>



</div>




<div class="reviews_divs">

  <div class="container">
    <div class="row">

      <h1 class="hotel_offers_style">Customer Reviews</h1>




      <div class="col-sm-4 ">
        <div class="reviews_dives col-sm-12">
          <img src="images/person1.jpg">
          <p>I have used Klitestays twice and had zero problems. Each time the hotel had my reservations correct and the
            rates were better than I found on other sites.</p>
          <h4>RISHABH</h4>
          <span>Booked on</span>
          <span>27-Nov-2017</span>

        </div>

      </div>
      <div class="col-sm-4 ">
        <div class="reviews_dives col-sm-12">
          <img src="images/person2.jpg">
          <p>It's convenience and easy to find the hotel you like in this website, can find the most cheap price of the
            wonderful hotel!
            valuable ,useful,and easy to use! :))) </p>
          <h4>RISWANA</h4>
          <span>Booked on</span>
          <span>27-Nov-2017</span>

        </div>

      </div>
      <div class="col-sm-4 ">
        <div class="reviews_dives col-sm-12">
          <img src="images/person1.jpg">
          <p class="reviews_p_scroll">It was great to choose Klitestays. The Klitestays is easy to book hotels. Moreover, it
            gives much information which I need. It was easy to search and it shows many pictures about the hotel, room
            types, and services which hotel’s offer. I would like to recommend Klitestays. The company is the best to
            search hotels with affordable price.</p>
          <h4>YOGESH</h4>
          <span>Booked on</span>
          <span>27-Nov-2017</span>

        </div>

      </div>
    </div>

  </div>



</div>




<div class="container">
  <section class="para_contents" style="text-align: left;">
    <h1 style="padding: 10px 0;color: #dd3236;">
      <?php echo SITENAME; ?>
    </h1>


    <p>Klitestays is a leading online hotel booking portal, Klitestays is issued and maintained by Buddies e-com solutions
      Pvt. Ltd. Company, the company popularly goes by the name Buddies Technologies which undertakes the management of
      various travel related streams that makes our company a complete travel companion. From our famous Buddies
      Holidays and Oto cabs to the latest Klitestays.com, we got it all covered to satiate the wander thirst of our
      customers.</p>
    <div id="paness" style="display:none;">

      <p>Klitestays is our vogue establishment with rich annexing to various categories of hotels in India. From luxurious
        accommodations to budget hotels, a well planned trip or an eleventh hour plan, Klitestays.com helps you have a
        tussle free sojourn anywhere in India.</p>

      <p>Using one active search, you can get all the information you want to know and you need to know about all the
        best possible options based on your preferences. Klitestays.com is a user friendly site that fetches you adequate
        and reliable sources to enjoy your stay across the board.</p>
    </div> <span class="testread">Read More...</span>



  </section>



  <script>
    $(".testread").click(function() {

      $(this).text($(this).text() == "...Show less" ? "Read More..." : "...Show less");
      $("#paness").slideToggle("slow");




    });
  </script>
</div>
<div class="clearfix"></div>

<!--  <section id="contact">
          <div class="content">
            <div id="form">
              <form action="" id="contactForm" method="post">
                <span>Name</span>
                <input type="text" name="name" class="name" placeholder="Enter your name" tabindex=1 />
                <span>Email</span>
                <input type="text" name="email" class="email" placeholder="Enter your email" tabindex=2 />
                <span id="captcha"></span>
                <input type="text" name="captcha" class="captcha" maxlength="4" size="4" placeholder="Enter captcha code" tabindex=3 />
                <span>Message</span>
                <textarea class="message" placeholder="Enter your message" tabindex=4></textarea>
                <input type="submit" name="submit" value="Send e-mail" class="submit" tabindex=5>
              </form>
            </div>
      </section>

      <section>
                 <!Datepicker -->
<!-- h2 class="demoHeaders">Datepicker</h2>
          <div id="datepicker"></div>
          <script type="text/javascript">
            $( "#datepicker" ).datepicker({
  inline: true
});

          </script>
      </section> -->



<?php include('include/footer.php'); ?>





<script type="text/javascript">
  (function($) {
    function floatLabel(inputType) {
      $(inputType).each(function() {
        var $this = $(this);
        // on focus add cladd active to label
        $this.focus(function() {
          $this.next().addClass("active");
        });
        //on blur check field and remove class if needed
        $this.blur(function() {
          if ($this.val() === '' || $this.val() === 'blank') {
            $this.next().removeClass();
          }
        });
      });
    }
    // just add a class of "floatLabel to the input field!"
    floatLabel(".floatLabel");
  })(jQuery);
</script>



<!-- Left and right controls -->

<!--  <footer class="footer__copyright">
         <div class="container">
          <div class="row">
                                
             <p style="color:#fff;opacity:1;" class="text-center padding-top-20 padding-bottom-20 ">Copyright © 2017 - All rights reserved by <a style="color:white;" href="javascript:void(0)">Buddies Technologies</a></p>
            </div>
         </div>
      </footer> -->

<!-- <script type="text/javascript">
   var _gaq = _gaq || [];
   _gaq.push(['_setAccount', 'UA-36251023-1']);
   _gaq.push(['_setDomainName', 'jqueryscript.net']);
   _gaq.push(['_trackPageview']);
   
   (function() {
     var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
     ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
     var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
   })();
   
</script> -->
<!-- <script type="text/javascript">
$(window).load(function() {
  // The slider being synced must be initialized first
  $('#carousel').flexslider({
    animation: "slide",
    controlNav: false,
    animationLoop: false,
    slideshow: false,
    itemWidth: 210,
    itemMargin: 5,
    asNavFor: '#slider'
  });
 
  $('#slider').flexslider({
    animation: "slide",
    controlNav: false,
    animationLoop: false,
    slideshow: false,
    sync: "#carousel"
  });
});

</script>
 -->



<script type="text/javascript">
  $(document).ready(function() {

    $("#checkin").datepicker({
      numberOfMonths: 2,
      minDate: 1,
      dateFormat: "dd-m-yy",
      onSelect: function(selected) {

        $("#checkout").datepicker("option", "minDate", selected)

      }

    });
    $("#checkout").datepicker({
      numberOfMonths: 2,
      minDate: $("#checkin").val(),
      dateFormat: "dd-m-yy",
      onSelect: function(selected) {
        //  $(".datepicker").datepicker("option","maxDate", selected)
      }
    });
    var adult = $('.actives').text();
    $('.total_guest_room').text('1 Room, 2 Guests');
  });

  function changeadult(value, identifier) {

    var guest = 0;
    var currentpax = $(identifier).closest(".pax_container").attr("data");
    var oldval = $("#oldAdult_" + currentpax).val();
    var checkchild = $(".childlist_" + currentpax).find(":selected").val();
    var tot = parseInt(checkchild) + parseInt(value);

    $("#oldAdult_" + currentpax).val(oldval);
    console.log(currentpax);
    console.log(oldval);
    if (tot > 3) {
      $(".adultlist_" + currentpax).val(oldval).attr("selected");
      $("#oldAdult_" + currentpax).val(oldval);
      $("#mesg").css("display", "block");
      setTimeout(function() {
        $('#mesg').fadeOut('fast');
      }, 1000);

      return false;
    }

    $(".adultlist_" + currentpax + " li").removeAttr("selected");
    $(identifier).addClass("actives");

    /* $('.actives').each(function(){
       console.log(guest);
       console.log(parseInt($(this).find(":selected").val()));
     guest=guest+parseInt($(this).find(":selected").val());
     });*/
    $("select option:selected").each(function() {
      guest = guest + parseInt($(this).val());
    });
    var room = $("#roomval").val();

    $('.detail_pax_' + currentpax + ' .this_adult').text(value);
    $('.total_guest_room').text(room + ' Room, ' + guest + ' Guests');

  }

  function changechild(value, identifier) {
    var guest = 0;



    var currentpax = $(identifier).closest(".pax_container").attr("data");
    var oldval = $("#oldChild_" + currentpax).val();
    var checkadult = $(".adultlist_" + currentpax).find(":selected").val();
    var tot = parseInt(checkadult) + parseInt(value);
    $("#oldChild_" + currentpax).val(value);

    if (tot > 3) {
      $("#mesg").css("display", "block");
      $(".childlist_" + currentpax).val(oldval).attr("selected");
      $("#oldChild_" + currentpax).val(oldval);
      setTimeout(function() {
        $('#mesg').fadeOut('fast');
      }, 1000);
      return false;
    }
    $(".childlist_" + currentpax + " li").removeAttr("selected");
    $(identifier).addClass("actives");

    $("select option:selected").each(function() {
      guest = guest + parseInt($(this).val());
    });
    var room = $("#roomval").val();

    $('.detail_pax_' + currentpax + ' .this_child').text(value);
    $('.total_guest_room').text(room + ' Room, ' + guest + ' Guests');

    /*$('.child_age_div_'+currentpax).remove();
    var ageSelect = '<div class="child_age_div_'+currentpax+'">';
    for(c=1;c<=value;c++){
      ageSelect = ageSelect + '<div class="clearfix"><p class="head_title_room">Child '+c+' Age</p><select style="width:50px;border:1px solid #ccc;" class="child_age" data-room="'+currentpax+'"><option>1</option><option>2</option><option>3</option><option>4</option><option>5</option><option>6</option><option>7</option><option>8</option><option>9</option><option>10</option><option>11</option><option>12</option></select></div>';
    }
    ageSelect = ageSelect + '</div>';
    $(".content_pax_"+currentpax).append(ageSelect);*/

  }

  function ondone() {

    $("#demo").removeClass("in");
  }

  function editval(val) {
    var currentpax = $(val).closest(".pax_container").attr("data");
    $(".content_pax_" + currentpax).toggle("slow");
    var text = $(".edit[data='" + currentpax + "'] i").text();
    if (text == "Edit") {
      $(".edit[data='" + currentpax + "'] i").text("Minimize");
    } else {
      $(".edit[data='" + currentpax + "'] i").text("Edit");
    }

  }

  function minimize(val) {
    var currentpax = $(val).closest(".pax_container").attr("data");
    $(".content_pax_" + currentpax).hide("slow");
  }

  function removeroom(data) {
    var s = 0;
    var currentpax = $(data).closest(".pax_container").attr("data");
    var guest = 0;
    $(".pax_container_" + currentpax).remove();
    var i = 1;
    $('.pax_container').each(function() {
      if ($(this).hasClass("test")) {
        var item = $(this).attr("data");
        $(this).attr("data", i);
        $(".pax_container_" + item + ' .roomnumber').text(i);

        $(this).parent().find('.detail_pax_' + item).removeClass('detail_pax_' + item).addClass('detail_pax_' + i);
        $(this).parent().find('.content_pax_' + item).removeClass('content_pax_' + item).addClass('content_pax_' + i);
        $(this).parent().find('.adultlist_' + item).removeClass('adultlist_' + item).addClass('adultlist_' + i);
        $(this).parent().find('.childlist_' + item).removeClass('childlist_' + item).addClass('childlist_' + i);
        $(this).parent().find('.pax_container_' + item).removeClass('pax_container_' + item).addClass('pax_container_' + i);
        $(this).parent().find('.edit').attr("data", i);


      }
      s++;
      i++;

    });
    $(".rooms_in_hotel").attr('data', i);
    $("select option:selected").each(function() {
      guest = guest + parseInt($(this).val());
    });
    $("#roomval").val(s);
    $('.total_guest_room').text(s + ' Room, ' + guest + ' Guests');
  }

  function addroom(value) {

    var intId = parseInt($(value).attr('data'));
    var inc = intId + 1;
    $(".rooms_in_hotel").attr('data', inc);
    var append = '<div  class="col-sm-12 pax_container test pax_container_' + intId + '" data="' + intId + '"><div class="removepax" onclick="removeroom(this)"><i class="fa fa-times" aria-hidden="true"></i></div>';
    append += '<div class="row" style="margin-top: 4px;">';
    append += '<div class="col-sm-3 detail_pax detail_pax_' + intId + '">';
    append += '<p class="detail_pax_p">Room <span class="roomnumber">' + intId + '</span></p>';
    append += '';
    append += '</div>';
    append += '<div class="col-sm-8 content_pax content_pax_1' + intId + '" style="margin-top: -20px;">';
    append += '<div class="row">';
    append += '<div class="col-sm-5 col-xs-6">';
    append += '<p class="head_title_room" style="margin-left: -5px;">Adult</p>';
    append += '<select class="select1 actives adultlist adultlist_' + intId + '" onchange="changeadult(this.value,this)">';
    append += '<option value="1">1</option>';
    append += '<option selected value="2">2</option>';
    append += '<option value="3">3</option>';
    append += '</select>';
    append += '<input type="hidden" id="oldAdult_' + intId + '" value="2">';

    append += '</div>';
    append += '<div class="col-sm-6 col-xs-6">';
    append += '<p class="head_title_room" style="margin-left: -34px;">Childern (5-12 yrs)</p>';
    append += '<select class="select1 actives childlist childlist_' + intId + '" onchange="changechild(this.value,this)">';
    append += '<option selected value="0" >0</option>'
    append += '<option  value="1">1</option>';
    append += ' <option  value="2">2</option>';

    append += ' </select><input type="hidden" id="oldChild_' + intId + '" value="0">';
    append += '</div>';
    append += '</div>';
    append += '</div>';
    append += '<div class="clearfix"></div>';
    append += ' </div>';
    append += ' </div>';

    $(".outer_pax").append(append);

    var guest = 0;
    $('.actives').each(function() {
      guest = guest + parseInt($(this).val());
    });
    $("#roomval").val(intId);
    $('.total_guest_room').text(intId + ' Room, ' + guest + ' Guests');

  }
  $(document).ready(function() {
    var room = 0;
    var noofadult = 0;
    var noofchild = 0;
    var obj = {};
    var items = [];
    $("#hotel_search").submit(function(e) {

      $('.pax_container').each(function() {

        if ($(this).hasClass("pax_container")) {
          room = $(this).attr("data");
          noofadult = $(this).parent().find(".adultlist_" + room + ' :selected').val();
          noofchild = $(this).parent().find(".childlist_" + room + ' :selected').val();
          // obj[room]=[noofadult,noofchild];

          obj[room] = {
            'adult': noofadult,
            'child': noofchild
          };

          //console.log(obj[room]);
          // items.push({'adult':noofadult,'child':noofchild});

        }
      });
      $("#guest").val(JSON.stringify(obj));
      return;
    });

  });



  $(document).ready(function() {

    $("#button_add_roo").click(function() {
      $('.arrows_addroom').toggleClass('fa-angle-up fa-angle-down');

    });


    $(".done").click(function() {
      var childAgeJSON = [];
      $('.child_age').each(function() {
        var object = {};
        object[$(this).attr('data-room')] = $(this).val();
        childAgeJSON.push(object);
      });
      $('#child_age_json').val(JSON.stringify(childAgeJSON));
      $('.arrows_addroom').toggleClass('fa-angle-up fa-angle-down');
    });


  });
</script>

<script>
  function captchaCode() {
    var Numb1, Numb2, Numb3, Numb4, Code;
    Numb1 = (Math.ceil(Math.random() * 10) - 1).toString();
    Numb2 = (Math.ceil(Math.random() * 10) - 1).toString();
    Numb3 = (Math.ceil(Math.random() * 10) - 1).toString();
    Numb4 = (Math.ceil(Math.random() * 10) - 1).toString();

    Code = Numb1 + Numb2 + Numb3 + Numb4;
    $("#captcha span").remove();
    $("#captcha input").remove();
    $("#captcha").append("<span id='code'>" + Code + "</span><input type='button' onclick='captchaCode();'>");
  }

  $(function() {
    $(".done").click(function() {
      var childAgeJSON = [];
      $('.child_age').each(function() {
        var object = {};
        object[$(this).attr('data-room')] = $(this).val();
        childAgeJSON.push(object);
      });
      $('#child_age_json').val(JSON.stringify(childAgeJSON));
      $('.arrows_addroom').toggleClass('fa-angle-up fa-angle-down');
    });
    captchaCode();

    $('#contactForm').submit(function() {
      var captchaVal = $("#code").text();
      var captchaCode = $(".captcha").val();
      if (captchaVal == captchaCode) {
        $(".captcha").css({
          "color": "#609D29"
        });
      } else {
        $(".captcha").css({
          "color": "#CE3B46"
        });
      }

      var emailFilter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,10})+$/;
      var emailText = $(".email").val();
      if (emailFilter.test(emailText)) {
        $(".email").css({
          "color": "#609D29"
        });
      } else {
        $(".email").css({
          "color": "#CE3B46"
        });
      }

      var nameFilter = /^([a-zA-Z \t]{3,15})+$/;
      var nameText = $(".name").val();
      if (nameFilter.test(nameText)) {
        $(".name").css({
          "color": "#609D29"
        });
      } else {
        $(".name").css({
          "color": "#CE3B46"
        });
      }

      var messageText = $(".message").val().length;
      if (messageText > 50) {
        $(".message").css({
          "color": "#609D29"
        });
      } else {
        $(".message").css({
          "color": "#CE3B46"
        });
      }

      if ((captchaVal !== captchaCode) || (!emailFilter.test(emailText)) || (!nameFilter.test(nameText)) || (messageText < 50)) {
        return false;
      }
      if ((captchaVal == captchaCode) && (emailFilter.test(emailText)) && (nameFilter.test(nameText)) && (messageText > 50)) {
        $("#contactForm").css("display", "none");
        $("#form").append("<h2>Message sent!</h2>");
        return false;
      }
    });
  });
</script>


<script type="text/javascript">
  // Instantiate the Bootstrap carousel
  $('.carousel[data-type="multi"] .item').each(function() {
    var next = $(this).next();
    if (!next.length) {
      next = $(this).siblings(':first');
    }
    next.children(':first-child').clone().appendTo($(this));

    for (var i = 0; i < 2; i++) {
      next = next.next();
      if (!next.length) {
        next = $(this).siblings(':first');
      }

      next.children(':first-child').clone().appendTo($(this));
    }
  });
</script>

<script src="https://owlcarousel2.github.io/OwlCarousel2/assets/vendors/jquery.mousewheel.min.js"></script>
<script>
  var owl = $('.carousel_trending');
  owl.owlCarousel({
    loop: true,

    margin: 10,
    responsive: {
      0: {
        items: 1
      },
      600: {
        items: 2
      },
      960: {
        items: 3
      }
    }
  });
  owl.on('mousewheel', '.owl-stage', function(e) {
    if (e.deltaY > 0) {
      owl.trigger('next.owl');
    } else {
      owl.trigger('prev.owl');
    }
    e.preventDefault();
  });
</script>

<script>
  $(document).ready(function() {
    $(".rooms_in_hotel ").click(function() {

      $(".room_res1").addClass("room_res2");
    });
  });
</script>