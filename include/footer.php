<?php


require_once 'securimage/securimage.php'; ?>
<!-- <button id="modaltest" class="feedback-button hvr-sweep-to-right" type="button" data-toggle="modal" data-target="#sendquerymodal">Send query <i style="font-size:13px;margin-left:6px;" class="fa fa-paper-plane" aria-hidden="true" ></i></button> -->

<!-- Modal Form for Sign In/Log In -->

<head>
  <style type="text/css">
    .input-group .form-control {
      border-radius: 1px !important;
    }
  </style>

</head>



<div id="sendquerymodal" class="modal" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header ">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title send_queryhead">Send Query</h4>
      </div>
      <div class="modal-body ">
        <div class="card_for_boxxs  " style="">



          <form action="" name="send_query" id="send_query">
            <div class=" " style="padding-top:10px;">
              <div class="relatives">

                <div class="input-group">
                  <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                  <input class="input-sm form-control " type="text" name="name" placeholder="Name" id="name">
                  <label id="name_error" class="enq_error">Enter Name</label>
                </div>




              </div>

              <div class="relatives">

                <div class="input-group">
                  <span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>
                  <input class=" input-sm form-control" type="email" name="email" placeholder="Email Id" id="email1"
                    required="" oninvalid="setCustomValidity('Enter valid Email id')" oninput="setCustomValidity('')">
                  <label id="email_error" class="enq_error">Invalid Email</label>
                </div>




              </div>
              <div class="relatives">

                <div class="input-group">
                  <span class="input-group-addon"><i class="glyphicon glyphicon-phone"></i></span>
                  <input class=" form-control input-sm myid " id="mobile" name="mobile" type="text" maxlength="10"
                    placeholder="Mobile Number" required="" oninvalid="setCustomValidity('Enter valid Mobile Number)"
                    oninput="setCustomValidity('')">

                  <label id="man"
                    style="top: -17px; right: 0px; position: absolute; font-size: 10px; color: rgb(219, 11, 11); display: none;"></label>
                  <label id="mobile_error" class="enq_error">Enter Mobile Number</label>
                </div>




              </div>


              <div class="relatives">

                <div class="input-group">
                  <span class="input-group-addon"><i class="fa fa-comments" aria-hidden="true"></i></span>
                  <textarea id="my_text_area" rows="4" class=" input-sm form-control  " name="message"
                    placeholder="Enter Your Message Here"></textarea>
                </div>




              </div>




              <p style="text-align: right;"><button type="submit" id="sendquery" class="btn btn-primary" style="width:100px;margin-bottom: 20px;
                             ">Send Now</button></p>

            </div>
            <span id="loader-spin" style="display:none;position: absolute;bottom: 14px; right: 144px;"><img
                width=" 25px" src="<?php echo $root_dir; ?>images/spinner.gif"></span>

          </form>

        </div>
      </div>
    </div>
  </div>
</div>
<div id="message_mail" class="modal" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content" style="width: 60%;
      margin: 20%;background-color: transparent;box-shadow: none!important;border:none;position: relative;">

      <div class="modal-header ">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title ">Message</h4>
      </div>
      <div class="modal-body ">
        <div class="card_for_boxxs  " style="">

          <div class="col-sm-12" style="background:#fff;">



            <div class="col-md-12" style="padding:20px 10px;">
              <p>Enquiry Sent Successfully, Our Team Will Contact you ASAP</p>
              <h4>
                <center style="color: red;">Thank You!</center>
              </h4>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>
</div>


<?php
if ($currentpage == 'home' || $currentpage == 'contactus') { ?>
  <div class="container-fluid footer_under_cities ">
    <div class="container contain_most_cities  ">
      <div class="col-sm-12 inner_contain_most" style="padding: 0;">

        <h1 class="hotel_offers_style ">Most Popular Destinations </h1>


        <?php
        $search = isset($_SESSION['search']) ? $_SESSION['search'] : 'Cab';
        if ((isset($from) && $from != '' && isset($to) && $to != '') || ((isset($_GET['source_city']) && $_GET['source_city'] != '') && (isset($_GET['destination_city']) && $_GET['destination_city']))) {


          function make($city_listv, $from, $search, $root_dir)
          {
            $links = '';
            $links .= '<div class="col-sm-3 cabs_value_cities">';
            foreach ($city_listv as $keys => $values) {
              $strr = explode('/', $values['name']);
              $values['name'] = trim($strr[0]);
              $links .= "<a href='" . $root_dir . "/hotel/" . $from . "-" . $values['name'] . "-" . $search . "'>" . ucwords($from) . " to " . ucwords($values['name']) . " " . ucwords($search) . "</a><br>";
            }
            $links .= '</div>';

            return $links;
          }
          $city_list = $database->query("SELECT c.name from ps_pages p left join ps_city c on(c.id_city=p.selCityId) where p.status=0 and p.action='' ORDER BY RAND() limit 20")->fetchAll(PDO::FETCH_ASSOC);

          $result = '';
          $i = 1;
          if (isset($city_list) && !empty($city_list)) {
            $city_list = array_chunk($city_list, 5);
            foreach ($city_list as $city_listk => $city_listv) {
              if ($i == 5) {
                // $result.='<div id="panelss"  style="display:none;">';
              }
              if ($from != '') {
                $result .= make($city_listv, $from, $search, $root_dir);
              } else {
                $strr = explode('/', $_GET['source_city']);
                $source_city = trim($strr[0]);
                $result .= make($city_listv, $source_city, $search, $root_dir);
              }

              $i++;
            }
            if ($i > 5) {
              //$result.='</div>';
            }
          }
          $city_list = $database->query("SELECT c.name from ps_pages p left join ps_city c on(c.id_city=p.selCityId) where p.status=0 and p.action='' ORDER BY RAND() limit 20")->fetchAll(PDO::FETCH_ASSOC);

          $result1 = '';
          $i = 1;
          if (isset($city_list) && !empty($city_list)) {
            $city_list = array_chunk($city_list, 5);
            $result .= '<div id="panelss"  style="display:none;">';
            foreach ($city_list as $city_listk => $city_listv) {

              if ($to != '') {
                $result .= make($city_listv, $to, $search, $root_dir);
              } else {
                $strr = explode('/', $_GET['destination_city']);
                $destination_city = trim($strr[0]);
                $result .= make($city_listv, $destination_city, $search, $root_dir);
              }


            }
            $result .= '</div>';
          }
          echo $result;
        } else { ?>
          <div class="clearfix"></div>
          <div class="row full_rows_cities" style=" ">
            <?php
            $all_comib_arr = array();
            $states = $database->query("SELECT * from ps_state where active=1")->fetchAll(PDO::FETCH_ASSOC);
            foreach ($states as $state_key => $state_val) {
              $city_5 = $database->query("SELECT * from ps_city where id_state=" . $state_val['id_state'] . " and is_for_hotel=1 and status=0 order by RAND() Limit 5")->fetchAll(PDO::FETCH_ASSOC);
              $all_comib_arr[$state_val['name']] = $city_5;

            }



            //echo '<pre>';print_r($all_comib_arr);echo '</pre>';
        



            foreach ($all_comib_arr as $state_value => $city_names) {

              if (count($city_names) >= 1) {
                ?>

                <div class="col-sm-3 cabs_value_cities " style="margin-bottom: 20px;">
                  <h4>Hotels in
                    <?php echo $state_value; ?>
                  </h4>

                  <?php

                  for ($i = 0; $i < count($city_names); $i++) {

                    $state_names = $city_names[$i]['name'];
                    $state_names_lower = strtolower(implode('-', explode(' ', $state_names))); ?>
                    <a class="footText text_color"
                      href="<?php echo $root_dir; ?><?php echo $state_names_lower; ?>/hotels-in-<?php echo $state_names_lower; ?>">Hotels
                      in
                      <?php echo $state_names; ?>
                    </a><br>



                  <?php
                  }


                  ?>


                  <div class="clearfix"></div>

                </div>
                <?php

              }
            }

            ?>



          </div>
          <div class="clearfix"></div>
          <Span style="position: relative;bottom: 10px;" id="more_cities" class="text_color footer_show more_citiss"> Show
            More...
          </Span>
        </div>
        <?php
        }

        // echo '<div class="col-md-12">'.$result.'</div>';
        // echo '<div class="col-md-6">'.$result1.'</div>';
      
        ?>


    </div>
    <div class="clearfix"></div>

  </div>
  </div>

<?php } else { ?>

  <div class="container-fluid">
  <?php } ?>



  <div class="modal" id="myModal">
    <div class="modal-dialog">
      <div class="modal-content" style="border-radius: 0px;">

        <div class="modal-header signin_fulls">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Sign In</h4>
        </div>

        <div class="modal-body  signinmodal">



          <div class="form-group ">

            <div class="input-group">
              <div class="input-group-addon">
                <span class="glyphicon glyphicon-user"></span>
              </div>
              <input class="form-control" id="username" name="uname"
                onfocus="if(this.value=='Email Id / Mobile Number') this.value='';"
                onblur="if(this.value=='') this.value='Email Id / Mobile Number';"
                placeholder="Email Id / Mobile Number" type="text" tabindex="1">
            </div>
          </div>

          <div class="form-group ">

            <div class="input-group">
              <div class="input-group-addon">
                <span class="glyphicon glyphicon-lock"></span>
              </div>
              <input class="form-control" id="password" name="pword" onfocus="if(this.value=='Password') this.value='';"
                onblur="if(this.value=='') this.value='Password';" placeholder="Password" tabindex="2" type="password">
              <input type="hidden" name="search" id="search" value="<?php echo $search_page; ?>">
              <input type="hidden" name="url" id="url" value="<?php echo $root_dir; ?>">
            </div>
          </div>



          <div class="col-sm-12" style="background-color: transparent;padding:0 ;margin-bottom: 10px;">
            <a style="font-size: 12px; cursor: pointer;" data-target="#modal-3" data-toggle="modal"
              id="closeSignIn">Forgot password?</a> <button style="float:right;" href="#" class="btn button_color"
              onclick="authenticate_user();">Log-In</button>

          </div>


          <div class="clearfix"></div>

          <div class="alert alert-danger" id="invalidpassword">Invalid Username and Password</div>
          <div class="alert alert-success" id="correctpassword"><strong>Success!</strong>Login Successfull </div>
          <?php if (isset($_GET['status']) && ($_GET['status'] == 'timeout')) { ?>
            <div class="alert alert-success"><button class="close" data-dismiss="alert">×</button><strong>Your Session has
                been expired.</strong> </div>
            <br>
          <?php } ?>
          <div class="alert alert-success" id="logout_success"><strong>Successfully logged out.</strong> </div>



        </div>
        <div class="modal-footer">
          <div class="form-group " style="padding-bottom: 15px;">

            <div class=" full_notmember"> <span>Not a Member</span><a href="#modal-2" data-toggle="modal"
                data-dismiss="modal"> Sign up Now?</a></div>

          </div>

          <!-- <a href="#" data-dismiss="modal" class="btn">Close</a> -->

        </div>
      </div>
    </div>
  </div>

  <!--  End of Modal Form for Sign In/ Log In -->
  <!-- Modal Form for registeration -->
  <!-- #modal fade-scale  2 -->
  <div class="modal" id="modal-2">
    <div class="modal-dialog">
      <div class="modal-content" style="position: relative;">
        <div class="modal-header ">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Registration Form</h4>
        </div>


        <div class="modal-body" style="position: relative;padding-top: 0;">
          <form name="registrationform" id="registrationform" method="post" action="">
            <!-- <input type="hidden" id="pagname" value=>-->
            <div class="row  " style="margin-top: 0;">
              <div class="col-md-12  addcustomer  " style="background:#fff; font-weight: normal;">

                <fieldset>

                  <div class="full_val">
                    <div class="row">
                      <div class="col-xs-12 col-sm-6 form-group groupstyle has-feedback has-error">
                        <label>Email id&nbsp;&nbsp;<span style="color: #dd3236;">*</span></label>
                        <input class="form-control" type="text" placeholder="Enter valid Email id" id="email_id"
                          name="email_id" style="border-radius: 1px;">
                      </div>
                      <div class="col-xs-12 col-sm-6 form-group groupstyle has-feedback has-error">
                        <label>Mobile no&nbsp;&nbsp;<span style="color: #dd3236; ">*</span></label>
                        <input placeholder="Enter valid Mobile No" class="form-control" type="text" id="mobile_no"
                          name="mobile_no" style="border-radius: 1px;">
                      </div>
                      <div class="clearfix"></div>
                      <div class="col-xs-12 col-sm-6  form-group groupstyle has-feedback has-error">
                        <label>Password&nbsp;&nbsp;<span style="color: #dd3236;">*</span></label>
                        <input class="form-control" type="password" id="rpassword" name="password" autocomplete="off"
                          style="border-radius: 1px;">
                      </div>
                      <div class="col-xs-12 col-sm-6 form-group groupstyle has-feedback has-error">
                        <label>Re-enter password&nbsp;&nbsp;<span style="color: #dd3236; ">*</span></label>
                        <input class="form-control" type="password" id="confirmPassword" name="confirmPassword"
                          autocomplete="off" style="border-radius: 1px;">
                      </div>





                      <!--  <div class="clearfix"></div>
                                   <div class="col-xs-12  col-sm-4 form-group groupstyle has-feedback has-error">
                                      <label>Address&nbsp;&nbsp;<span style="color: #dd3236; ">*</span></label>
                                      <input class="form-control" type="text" id="address" name="address" autocomplete="off" >
                                  </div>
                                   <div class="col-xs-12 col-sm-4 form-group groupstyle has-feedback has-error">
                                      <label>Pin code&nbsp;&nbsp;<span style="color: #dd3236; ">*</span></label> 
                                      <input class="form-control" type="text" id="pin_code" name="pin_code">
                                   </div>
                                   <div class="col-xs-12 col-sm-4 form-group groupstyle has-feedback has-error">
                                      <label>City&nbsp;&nbsp;<span style="color: #dd3236; ">*</span></label>
                                      <input class="form-control" type="text" id="city" name="city" >
                                   </div> -->
                      <div class="clearfix"></div>
                      <!-- <div class="col-xs-12 col-sm-4 form-group groupstyle has-feedback has-error">
                                      <label>State&nbsp;&nbsp;<span style="color: #dd3236; ">*</span></label>
                                      <select id="state" name="state" class="form-control" >
                                         <option >--Choose--</option>
                                         <option value="341">Andaman and Nicobar Islands</option><option value="313">Andhra Pradesh</option><option value="314">Arunachal Pradesh</option><option value="315">Assam</option><option value="316">Bihar</option><option value="342">Chandigarh</option><option value="317">Chhattisgarh</option><option value="343">Dadra and Nagar Haveli</option><option value="344">Daman and Diu</option><option value="345">Delhi</option><option value="318">Goa</option><option value="319">Gujarat</option><option value="320">Haryana</option><option value="321">Himachal Pradesh</option><option value="322">Jammu and Kashmīr</option><option value="323">Jharkhand</option><option value="324">Karnataka</option><option value="325">Kerala</option><option value="346">Lakshadweep</option><option value="326">Madhya Pradesh</option><option value="327">Maharashtra</option><option value="328">Manipur</option><option value="329">Meghalaya</option><option value="330">Mizoram</option><option value="331">Nagaland</option><option value="332">Orissa</option><option value="347">Pondicherry</option><option value="333">Punjab</option><option value="334">Rajasthan</option><option value="335">Sikkim</option><option value="336">Tamil Nadu</option><option value="348">Telangana</option><option value="337">Tripura</option><option value="339">Uttar Pradesh</option><option value="349">Uttarakhand</option><option value="338">Uttaranchal</option><option value="340">West Bengal</option>                                    </select>
                                    
                                   </div>
                                   <div class="col-xs-12 col-sm-4 form-group groupstyle has-feedback has-success">
                                      <label>Pan No&nbsp;&nbsp;</label>
                                      <input class="form-control" type="text" id="pan_no" name="pan_no" >
                                      
                                    </div>
                                   <div class="col-xs-12 col-sm-4 form-group groupstyle has-feedback has-success">
                                      <label>GST&nbsp;&nbsp;</label>
                                      <input class="form-control" type="text" id="gst_no" name="gst_no" maxlength="15" >
                                   </div>
                                   <div class="clearfix"></div> -->
                      
                                   <?php echo Securimage::getCaptchaHtml(); ?>

                      <div class="clearfix"></div>

                      <div class="col-md-12" style="text-align:right; margin-top: 15px; margin-bottom: 15px; ">
                        <input type="hidden" name="search" value="0">
                        <input type="hidden" name="url" id="url" value="">
                        <span class="loader_spin" style="font-size:20px; display:none;"> <i
                            class="fa fa-spinner fa-spin" aria-hidden="true"></i></span>
                        <button style="width: 100px;border:none;" type="submit" class="btn button_color">Submit</button>

                        <button style="width: 100px;border:none;" type="button" class="btn button_color"
                          onclick="reload_form();">Reset</button>
                      </div>
                    </div>
                  </div>

                </fieldset>

                <div class="col-md-12" style=" text-align:center;  height:15px;"></div>

              </div>

            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <!-- <script type="text/javascript">
          $(document).ready(function() {
      $('#registrationform').bootstrapValidator({
          container: '#messages',
          feedbackIcons: {
              valid: 'glyphicon glyphicon-ok',
              invalid: 'glyphicon glyphicon-remove',
              validating: 'glyphicon glyphicon-refresh'
          },
          fields: {
              first_name: {
                  validators: {
                      notEmpty: {
                          message: 'The full name is required and cannot be empty'
                      }
                  }
              },
              email: {
                  validators: {
                      notEmpty: {
                          message: 'The email address is required and cannot be empty'
                      },
                      emailAddress: {
                          message: 'The email address is not valid'
                      }
                  }
              },
              title: {
                  validators: {
                      notEmpty: {
                          message: 'The title is required and cannot be empty'
                      },
                      stringLength: {
                          max: 100,
                          message: 'The title must be less than 100 characters long'
                      }
                  }
              },
              content: {
                  validators: {
                      notEmpty: {
                          message: 'The content is required and cannot be empty'
                      },
                      stringLength: {
                          max: 500,
                          message: 'The content must be less than 500 characters long'
                      }
                  }
              }
          }
      });
  });
        </script>
   -->
</div>



<!-- End Modal2 Form for registration -->
<!-- Start Modal3 Form  -->
<!-- #modal3 glyphicon glyphicon-lock-->

<div class="modal" id="modal-3">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header ">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><span class="glyphicon glyphicon-lock"></span> Recover Password</h4>
      </div>

      <div class="modal-body">


        <div class="row">
          <div class="col-sm-12" style="padding-left: 30px; padding-right: 30px;">
            <h5>Type in your registered email address</h5>
            <input class="form-control" type="" name="Email Address" placeholder=""
              style="width: 100%; text-align: left;">
          </div>

        </div>

        <div class="row">
          <div class="col-sm-12"
            style="padding-left: 30px; padding-right: 30px; padding-top: 10px; padding-bottom: -10px; text-align: right">


            <button type="button" class="btn btn-primary">Submit</button>
          </div>
        </div>

      </div>

    </div><!-- /.modal-content 3-->
  </div><!-- /.modal-dialog3 -->
</div><!-- /.modal3 -->
<!-- End Modal Form3 -->
<div class="clearfix">
</div>

<div class="footer__copyright footcopy">

  <div class="container" style="margin-top: 20px;">
    <div class="row footer_rows_up">



      <div class="col-xs-6 col-sm-2  footer_content_columns">
        <ul class="foot_content">
          <li class="foot_list">
            <a href="#">Countries</a>
          </li>
          <li class="foot_list">
            <a href="#">Regions</a>
          </li>
          <li class="foot_list">
            <a href="#">Cities</a>
          </li>
          <li class="foot_list">
            <a href="#">Districts</a>
          </li>
        </ul>
      </div>
      <div class="col-xs-6 col-sm-2 footer_content_columns">
        <ul class="foot_content">
          <li class="foot_list">
            <a href="#">Holiday rentals</a>
          </li>
          <li class="foot_list">
            <a href="#">Apartments</a>
          </li>
          <li class="foot_list">
            <a href="#">Resorts</a>
          </li>
          <li class="foot_list">
            <a href="#">Villas</a>
          </li>
        </ul>
      </div>
      <div class="col-xs-6 col-sm-2 footer_content_columns">
        <ul class="foot_content">
          <li class="foot_list">
            <a href="#">All property types</a>
          </li>
          <li class="foot_list">
            <a href="#">All themes</a>
          </li>
          <li class="foot_list">
            <a href="#">All destination</a>
          </li>
          <li class="foot_list">
            <a href="#">Reviews</a>
          </li>
        </ul>
      </div>
      <div class="col-xs-6 col-sm-2 footer_content_columns">
        <ul class="foot_content">
          <li class="foot_list">
            <a href="<?php echo $root_dir; ?>about_us.php">About Us</a>
          </li>
          <li class="foot_list">
            <a href="<?php echo $root_dir; ?>cancellation_policy.php">Cancellation Policy</a>
          </li>
          <li class="foot_list">
            <a href="#">Customer service help</a>
          </li>
          <li class="foot_list">
            <a href="#">Give website feedback</a>
          </li>
        </ul>
      </div>
      <div class="col-xs-6 col-sm-2 footer_content_columns">
        <ul class="foot_content">
          <li class="foot_list">
            <a href="<?php echo $root_dir; ?>terms_and_conditions.php">Terms and conditions</a>
          </li>
          <li class="foot_list">
            <a href="<?php echo $root_dir; ?>privacy_policy.php">Privacy and cookies</a>
          </li>
          <li class="foot_list">
            <a href="#">Contact us</a>
          </li>
          <li class="foot_list">
            <a href="<?php echo $root_dir; ?>hotel_signup.php" style="color: #12ebcc;">Enroll Here</a>
          </li>
        </ul>
      </div>

    </div>
    <div class="clearfix"></div>
  </div>
</div>
</div>




<div class="container-fluid footer">
  <div class="container">
    <footer class="container" role="contentinfo">


      <!-- #secondary -->
      <div class="wrap">
        <div class="row" style="margin-top: 0;">
          <div class="full-width">

            <div id="footer-sidebar" class="footer-sidebar widget-area wrap" role="complementary">


              <ul>







              </ul>




              <div class="col-sm-4">
                <div class=" widget widget-sidebar ">

                  <div class="widget_wysija_cont">
                    <div id="msg-form-wysija-2" class="wysija-msg ajax"></div>
                    <form id="form-wysija-2" method="post" action="#wysija" class="widget_wysija">


                      <p class="wysija-paragraph" style="padding: 0;position: relative;">
                        <label>Email <span class="wysija-required">*</span></label>
                        <input placeholder="Subscribe to our Newsletter" type="text" name="wysija[user][email]"
                          class="myinput_text wysija-input validate[required,custom[email]]" title="Email" value="" />


                      </p>

                      <!--   <input class="wysija-submit wysija-submit-field" type="submit" value="Subscribe!" /> -->
                      <input type="hidden" name="form_id" value="1" />
                      <input type="hidden" name="action" value="save" />
                      <input type="hidden" name="controller" value="subscribers" />
                      <input type="hidden" value="1" name="wysija-page" />
                      <input type="hidden" name="wysija[user_list][list_ids]" value="1" />

                    </form>
                  </div>
                </div>
              </div>
              <div class="col-sm-2">
                <h6 class="follow_uss"></h6>
                <input class="wysija-submit myinput_button wysija-submit-field button_color" type="submit"
                  value="Subscribe!" style="margin-top: 13px;" />
              </div>

              <div class="col-sm-6 ">
                <div class="widget widget-sidebar ">
                  <article class="byt_social_widget BookYourTravel_Social_Widget">
                    <h6 class="follow_uss">Follow us</h6>
                    <ul class="social">
                      <li><a href="https://www.facebook.com/Klitestayscom-1765894383422184/" title="facebook"
                          style="background: #3b5998;"><i class="fa fa-facebook fa-fw" style="color:#fff;"></i></a>
                      </li>
                      <li><a href="#" title="twitter" style="background: #1da1f2;"><i class="fa fa-twitter fa-fw"
                            style="color:#fff;"></i></a>
                      </li>

                      <li><a href="#" title="linkedin" style="background: #0073b1;"><i
                            class="fa fa-linkedin fa-fw"></i></a></li>
                      <li><a href="#" title="googleplus" style="background: #dd3236;"><i class="fa fa-google-plus fa-fw"
                            style="color:#fff;"></i></a></li>


                    </ul>
                  </article>
                </div>
              </div>

            </div>
            <p class="copy">© klitestays.com 2018. All rights reserved.Desinged by Buddies Technologies</p>
            <!--footer navigation-->

            <!--//footer navigation-->
          </div>
        </div>
      </div>
    </footer>
  </div>
</div>







<script type="text/javascript">
  $(document).ready(function () {
    $("#closeforgot").click(function () {
      $("#modal-3").modal('hide');
    });
  });
</script>




<script type="text/javascript">
  $(document).ready(function () {
    $("#closeSignIn").click(function () {
      $("#myModal").modal('hide');
    });
  });
</script>






<script type="text/javascript">
  $(document).ready(function (e) {

    $("#hotels_month").datepicker({
      changeMonth: true,
      changeYear: true,
      numberOfMonths: 2,
      dateFormat: "dd-mm-yy",
      minDate: 0,
      onSelect: function (selectedDate) {
        //var tomorrow = new Date('10-12-2017');
        //console.log(tomorrow);
        console.log(selectedDate);
        var da = selectedDate.substring(0, 2);
        var mn = selectedDate.substring(3, 5);
        var yr = selectedDate.substring(6, 10);
        var newD = mn + '/' + (parseInt(da) + 1) + '/' + yr;
        console.log(new Date(newD));
        /*
        
        var tomorrow = new Date('10/12/2017');//new Date('"'+selectedDate.getMonth()+'/'+selectedDate.getDate()+'/'+selectedDate.getFullYear()+'"');
        console.log(tomorrow);
        tomorrow.setDate(tomorrow.getDate() + 1);
        console.log(tomorrow);*/
        $("#hotels_month1").datepicker("option", "minDate", selectedDate);
        //$( "#hotels_month1" ).focus();
      }
    });
    $("#hotels_month1").datepicker({
      changeMonth: true,
      changeYear: true,
      numberOfMonths: 2,
      dateFormat: "dd-mm-yy",
      onSelect: function (selectedDate) {
        $("#hotels_month").datepicker("option", "maxDate", selectedDate);
      }
    });
    $("#hotels_month_book").datepicker({
      changeMonth: true,
      changeYear: true,
      numberOfMonths: 2,
      dateFormat: "dd-mm-yy",
      minDate: 0,
      maxDate: $("#hotels_month1_book").val(),
      onSelect: function (selectedDate) {
        $("#hotels_month1_book").datepicker("option", "minDate", selectedDate);
        //$( "#hotels_month1" ).focus();
      }
    });
    $("#hotels_month1_book").datepicker({
      changeMonth: true,
      changeYear: true,
      numberOfMonths: 2,
      dateFormat: "dd-mm-yy",
      minDate: $("#hotels_month_book").val(),
      onSelect: function (selectedDate) {
        $("#hotels_month_book").datepicker("option", "maxDate", selectedDate);
        if ($("#hotels_month_book").val() != $('#hotel_month').val() || $('#hotel_month1').val() != selectedDate) {
          $('#hotel_month1').val(selectedDate);
          $('.book-hotel-form').submit();
          //$('.done').trigger("click");
        }
      }
    });
  });
  window.onscroll = function () { scrollFunction() };

  function scrollFunction() {
    if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
      document.getElementById("myBtn").style.display = "block";
    } else {
      document.getElementById("myBtn").style.display = "none";
    }
  }

  // When the user clicks on the button, scroll to the top of the document
  function topFunction() {
    // document.body.scrollTop = 0;
    //document.documentElement.scrollTop = 0;
    $('html, body').animate({ scrollTop: 0 }, 'slow');
  }

  $(document).ready(function () {

    $(window).scroll(function () {

      if (window.matchMedia('(max-width: 768px)').matches) {
        $(".journey_detail").css('position', 'relative');
        $(".journey_detail").css('top', '0');

      } else if (parseInt($(window).height()) - parseInt($(".foo").height()) <= parseInt($(window).scrollTop())) {
        $(".journey_detail").css('position', 'absolute');
        //$(".journey_detail").css('top',$(".detail_container").height()-$(".journey_detail").height());
        $(".journey_detail").css('top', '253px');
      } else {
        $(".journey_detail").css('position', 'fixed');
        $(".journey_detail").css('top', '');

      }

    });
  });

</script>
<script>
  jQuery.validator.setDefaults({
    debug: true,
    success: "valid"
  });

  jQuery.validator.addMethod("password_reg", function (value, element) {
    if (/^(?=.*[0-9])(?=.*[!@#$%^&*])[a-zA-Z0-9!@#$%^&*]$/.test(value)) {
      return false;
    } else {
      return true;
    };
  }, "Your password must be Minimum<br> 1 capital letter<br>1 Number<br>1 Special char(eg:!@#$%^&*)");
  $('#registrationform').validate({
    focusInvalid: false,
    focusCleanup: true,

    rules: {
      //  simple rule, converted to {required:true}
      // first_name: "required",
      // last_name:"required",
      // city: "required",
      // state: "required",
      email_id: {
        required: true,
        email: true
      },

      mobile_no: {
        required: true,
        minlength: 10,
        maxlength: 10
      },
      //  pin_code:{              
      //    required: true,
      //   minlength: 6,
      //   maxlength:6
      // },
      // address: "required",
      password: {
        required: true,
        password_reg: true,
        minlength: 8,
        equalTo: "#confirmPassword"
      },
      confirmPassword: {
        required: true,
        password_reg: true,
        equalTo: "#rpassword"
      },



    },

    messages: {
      // first_name: "Please Enter your First Name only",
      // last_name: "Please Enter your Last Name",
      // state: "Select state",

      email_id: {
        required: "Enter your valid Email Id only",
        email: "Format must be name@domain.com"
      }
    },
    success: function (label) {
      label.addClass("valid");
    },




    submitHandler: function (validator, form) {
      $(".loader_spin").css("display", "inline-block");
      save_customerdetails();
    },
  });





  $(".myid").keypress(function (e) {

    if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {

      $("#man").html("Numbers Only").show().fadeOut("slow");
      return false;
    }
  });


  $("#my_text_area").click(function () {
    $(this).text("");


  });
  /* function sortfor(class_id, for_class, value)
   {
    // $("#"+class_id).val(value);
    // $("."+for_class+"."+class_id).css("color","red");
    //$('.dropdown-menus li a').trigger('click');

   }*/
  $(window).ready(function () {
    $('.grid-view').click(function (e) {
      var currentClass = $(".three-fourth article").attr("class");
      if (typeof currentClass != 'undefined' && currentClass.length > 0) {
        currentClass = currentClass.replace('last', '');
        currentClass = currentClass.replace('full-width', window.itemClass);
        $(".three-fourth article").attr("class", currentClass);
        $(".view-type li").removeClass("active");
        $(this).addClass("active");

        staysinn_script.resizeFluidItems();
      }
      e.preventDefault();
    });

    $('.list-view').click(function (e) {
      var currentClass = $(".three-fourth article").attr("class");
      if (typeof currentClass != 'undefined' && currentClass.length > 0) {
        currentClass = currentClass.replace('last', '');
        currentClass = currentClass.replace(window.itemClass, 'full-width');
        $(".three-fourth article").attr("class", currentClass);
        $(".view-type li").removeClass("active");
        $(this).addClass("active");
      }
      e.preventDefault();
    });

    $("#sendquery").click(function (e) {
      function validateEmail(email) {
        var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(email);
      }

      var error = 0;
      if ($("#name").val() == '') {
        $("#name_error").css("display", "block");
        error++;
      }
      if ($("#email1").val() == '') {

        $("#email_error").css("display", "block");
        error++;
      } else {
        if (validateEmail($("#email1").val())) {

        } else {
          $("#email_error").css("display", "block");
          error++;
        }
      }
      if ($("#mobile").val() == '') {
        $("#mobile_error").css("display", "block");
        error++;
      }

      if (error != 0) {
        return false;
      }
      $("#loader-spin").css("display", "block");
      e.preventDefault();
      $.ajax({
        url: "<?php echo $root_dir; ?>include/email.php",
        type: "POST",
        data: $("#send_query").serialize(),
        success: function (result) {
          $("#loader-spin").css("display", "none");
          $("#send_query")[0].reset();
          $("#sendquerymodal").modal("hide");
          $.confirm({
            title: 'Thank You',
            content: 'Your enquiry sent to our Team, They will Contact you as soon as possible',
            type: 'green',
            typeAnimated: true,
            buttons: {
              ok: function () {
                window.location.reload();
              }
            }
          });
          setTimeout(function () { $("#mesage").hide(); }, 1000);
        }
      });
      return false;
    });
  });
  $("#captcha_code").attr("placeholder", "Type the captcha").val("").focus().blur();
  $("#captcha_code").addClass("col-xs-12 col-sm-4 form-control");
</script>


<button onclick="topFunction()" id="myBtn" title="Go to top"
  style="position: fixed;bottom: 40px;
      right: 1px;padding:0 0; background:transparent!important;background-position: cover; border: 0px solid transparent;"><img src="<?php echo $root_dir; ?>images/arr2.png"
    style="width:40px; height: 40px;"></button>
</body>

</html>





<script type="text/javascript">
  $(document).ready(function () {




    $("#more_cities").click(function () {

      $(this).text($(this).text() == "...Show less" ? " Show More..." : "...Show less");
      $(".full_rows_cities").toggleClass("show_less_cities");

    });


    //scripts for refine search results for hotels/search/index.php file

    $(".toggle_filters").click(function () {
      $("#secondary").slideToggle("slow");
      $(this).toggleClass("rotate_lines");


    });


    $(window).resize(function () {

      if ($(window).width() >= 768) {

        $("#secondary").css("display", "block");
        $(".dropdown .collapse").addClass("in");
        $(".dropdown .collapse").css("height", "auto");
      } else {

        $("#secondary").css("display", "none");
        $(".dropdown .collapse").removeClass("in");
      }
    }).resize();

    $(".show_atag_hotel").click(function () {
      var tex = $(this).text() == "Show" ? "Hide" : "Show";
      $(this).text(tex);


    });

    $(".more_infos").click(function () {
      // var data=$(this).attr('data');

      $(this).css("display", "none");
      // $(".data_"+data).toggleClass("discription_overflow_toggle" );

      $(this).prev(".discription_overflow ").toggleClass("discription_overflow_toggle");

    });



  });

</script>

<!-- klitestays/hotels/search/index and indexajax.php js-->

<script src="https://cdnjs.cloudflare.com/ajax/libs/css-element-queries/1.0.1/ResizeSensor.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/theia-sticky-sidebar@1.7.0/dist/theia-sticky-sidebar.min.js"></script>


<!-- klitestays/hotels/search/index and indexajax.php js end-->