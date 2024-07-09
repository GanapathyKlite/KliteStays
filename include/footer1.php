<?php 


   require_once 'securimage/securimage.php'; ?>
<!-- <button id="modaltest" class="feedback-button hvr-sweep-to-right" type="button" data-toggle="modal" data-target="#sendquerymodal">Send query <i style="font-size:13px;margin-left:6px;" class="fa fa-paper-plane" aria-hidden="true" ></i></button> -->
  
           <!-- Modal Form for Sign In/Log In -->
           
<head>
           <style type="text/css">

             .input-group .form-control{
              border-radius: 1px!important;
             }

           </style>

</head>



<div id="sendquerymodal" class="modal" role="dialog" >
   <div class="modal-dialog" style="width: 290px; margin-top: 100px;" >
      <!-- Modal content-->
      <div class="modal-content" style="border-radius: 0px;position: relative;">
         <div class="modal-body " >
            <div class="card_for_boxxs  " style="" >
        
               <div >
                      <button  type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="send_queryhead" >Send Query </h4>
                  <form action="" name="send_query" id="send_query">
                     <div class=" " style="padding-top:10px;" >
                      <div class="relatives" >

                       <div class="input-group">
                       <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                     <input class="input-sm form-control " type="text" name="name" placeholder="Name" id="name">
                      <label id="name_error"  class="enq_error">Enter Name</label>
                    </div>
                        
                          
                          
                          
                        </div>

                        <div class="relatives" >

                       <div class="input-group">
                       <span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>
                    <input class=" input-sm form-control"  type="email" name="email" placeholder="Email Id" id="email1" required="" oninvalid="setCustomValidity('Enter valid Email id')" oninput="setCustomValidity('')"  >
                                      <label id="email_error" class="enq_error">Invalid Email</label>
                    </div>
                        
                          
                          
                          
                        </div>
                         <div class="relatives" >

                       <div class="input-group">
                       <span class="input-group-addon"><i class="glyphicon glyphicon-phone"></i></span>
                    <input  class=" form-control input-sm myid " id="mobile" name="mobile" type="text" maxlength="10" placeholder="Mobile Number" required="" oninvalid="setCustomValidity('Enter valid Mobile Number)" oninput="setCustomValidity('')">
                           
                         <label id="man" style="top: -17px; right: 0px; position: absolute; font-size: 10px; color: rgb(219, 11, 11); display: none;"></label>
                           <label id="mobile_error"  class="enq_error">Enter Mobile Number</label>
                    </div>
                        
                          
                          
                          
                        </div>


                        <div class="relatives" >

                       <div class="input-group">
                       <span class="input-group-addon"><i class="fa fa-comments" aria-hidden="true"></i></span>
                    <textarea  id="my_text_area"   rows="4" class=" input-sm form-control  " name="message" placeholder="Enter Your Message Here" ></textarea>
                    </div>
                        
                          
                          
                          
                        </div>
                      
                      
                        
                        
                        <p style="text-align: right;"><button  type="submit" id="sendquery" class="btn btn-primary" style="width:100px;margin-bottom: 20px;
                           " >Send Now</button></p>

                     </div>
                     <span id="loader-spin" style="display:none;position: absolute;bottom: 14px; right: 144px;"><img width=" 25px" src="<?php echo $root_dir;?>images/spinner.gif"></span>
                    
                  </form>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<div id="message_mail" class="modal" role="dialog" >
   <div class="modal-dialog"  >
      <!-- Modal content-->
      <div class="modal-content" style="width: 60%;
    margin: 20%;background-color: transparent;box-shadow: none!important;border:none;position: relative;">
         <div class="modal-body " >
            <div class="card_for_boxxs  " style="" >
        
               <div class="col-sm-12" style="background:#fff;">
                      <button  type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4   style="    font-size: 18px;
    color: #555;
    margin: 20px 0px 0 0;font-weight: normal;">Message</h4>
                 
                  <div class="col-md-12" style="padding:20px 10px;">   <p>Enquiry Sent Successfully, Our Team Will Contact you ASAP</p>
                     <h4><center style="color: red;">Thank You!</center></h4></div>
                 
               </div>
            </div>
         </div>
      </div>
   </div>
</div>


<?php 
if($currentpage=='home'||$currentpage=='contactus'){  ?>
<div class="container-fluid footer_under_cities " >
  <div class="container contain_most_cities  "  style="margin-top:40px;">
   <div class="col-sm-12 inner_contain_most" style="padding: 0;">
   

    
      <h3 class="most_popu_cities most1" style="text-align: center;font-size:37px;color:#4c4c4c;" >Most Popular Destinations 
      </h3>
      <?php 
$search=isset($_SESSION['search'])?$_SESSION['search']:'Cab';
if((isset($from)&&$from!=''&&isset($to)&&$to!='')||((isset($_GET['source_city'])&&$_GET['source_city']!='')&&(isset($_GET['destination_city'])&&$_GET['destination_city'])))
{


     function make($city_listv,$from,$search,$root_dir)
      {
         $links='';
         $links.='<div class="col-sm-3 cabs_value_cities">';
         foreach($city_listv as $keys=>$values)
         {
            $strr=explode('/',$values['name']);
            $values['name']=trim($strr[0]);
            $links.="<a href='".$root_dir."/hotel/".$from."-".$values['name']."-".$search."'>".ucwords($from)." to ".ucwords($values['name'])." ".ucwords($search)."</a><br>";
         }
         $links.='</div>';
        
         return $links;
      }  
      $city_list = $database->query("SELECT c.name from ps_pages p left join ps_city c on(c.id_city=p.selCityId) where p.status=0 and p.action='' ORDER BY RAND() limit 20")->fetchAll(PDO::FETCH_ASSOC);
      
      $result='';$i=1;
      if(isset($city_list) && !empty($city_list)){
         $city_list=array_chunk($city_list, 5);     
         foreach($city_list as $city_listk => $city_listv){
            if($i==5)
            {
              // $result.='<div id="panelss"  style="display:none;">';
            }
               if($from!='')
               {
                  $result.=make($city_listv,$from,$search,$root_dir);                  
               }else{
                  $strr=explode('/',$_GET['source_city']);
                  $source_city=trim($strr[0]);
                  $result.=make($city_listv,$source_city,$search,$root_dir);
               }             
                    
               $i++;  
            }
            if($i>5)
            {
               //$result.='</div>';
            }
      }
      $city_list = $database->query("SELECT c.name from ps_pages p left join ps_city c on(c.id_city=p.selCityId) where p.status=0 and p.action='' ORDER BY RAND() limit 20")->fetchAll(PDO::FETCH_ASSOC);
      
      $result1='';$i=1;
      if(isset($city_list) && !empty($city_list)){
         $city_list=array_chunk($city_list, 5);    
         $result.='<div id="panelss"  style="display:none;">';  
         foreach($city_list as $city_listk => $city_listv){
            
         if($to!='')
         {
            $result.=make($city_listv,$to,$search,$root_dir);  
         }else{
            $strr=explode('/',$_GET['destination_city']);
            $destination_city=trim($strr[0]);
            $result.=make($city_listv,$destination_city,$search,$root_dir);
         } 
                 
           
         }
          $result.='</div>';
      }
   echo $result;
  }else {?>
  <div class="clearfix"></div>
<div class="row" style="text-align: left;" >
<div class="col-sm-3 cabs_value_cities">
         <h4>Hotels in Tamilnadu</h4>
         <a class="footText" href="<?php echo $root_dir;?>Chennai/hotels-in-Chennai">Hotels in Chennai</a><br>
         <a class="footText" href="<?php echo $root_dir;?>Pondicherry/hotels-in-Pondicherry">Hotels in Pondicherry</a><br>
         <a class="footText" href="<?php echo $root_dir;?>Ooty/hotels-in-Ooty">Hotels in Ooty </a><br>
         <a class="footText" href="<?php echo $root_dir;?>Kodaikanal/hotels-in-Kodaikanal">Hotels in Kodaikanal</a><br>
          <a class="footText" href="<?php echo $root_dir;?>Coonoor/hotels-in-Coonoor"> Hotels in Coonoor</a><br>
      </div>    
                             
          <div class="col-sm-3 cabs_value_cities">
            <h4>Hotels in goa</h4>
            <a class="footText" href="<?php echo $root_dir;?>panaji/hotels-in-panaji">Hotels in panaji </a><br>
            <a class="footText" href="<?php echo $root_dir;?>baga/hotels-in-baga">Hotels in baga </a><br>
            <a class="footText" href="<?php echo $root_dir;?>calangute/hotels-in-calangute">Hotels in calangute </a><br>
            <a class="footText" href="<?php echo $root_dir;?>margao/hotels-in-margao">Hotels in margao </a><br>
            <a class="footText" href="<?php echo $root_dir;?>vagator/hotels-in-vagator">Hotels in vagator </a><br>
         </div>
      <div class="col-sm-3 cabs_value_cities">
         <h4>Hotels in Andhra Pradesh</h4>
         <a class="footText" class="footText"  href="<?php echo $root_dir;?>Tirupati/hotels-in-Tirupati">Hotels in Tirupati </a><br>
         <a class="footText" href="<?php echo $root_dir;?>Vijayawada/hotels-in-Vijayawada">Hotels in Vijayawada </a><br>
         <a class="footText" href="<?php echo $root_dir;?>Visakhapatnam/hotels-in-Visakhapatnam">Hotels in Visakhapatnam </a><br>
         <a class="footText" href="<?php echo $root_dir;?>Vellore/hotels-in-Vellore">Hotels in Vellore </a><br>
         <a class="footText" href="<?php echo $root_dir;?>Nellore/hotels-in-Nellore">Hotels in Nellore </a><br>
      </div>
      <div class="col-sm-3 cabs_value_cities">
        <h4>Hotels in Telengana</h4>
        <a class="footText"  href="<?php echo $root_dir;?>Hyderabad/hotels-in-Hyderabad">Hotels in Hyderabad </a><br>
        <a class="footText" href="<?php echo $root_dir;?>Secunderabad/hotels-in-Secunderabad">Hotels in Secunderabad </a><br>
        <a class="footText" href="<?php echo $root_dir;?>Bhadrachalam/hotels-in-Bhadrachalam">Hotels in Bhadrachalam </a>
        <br>
        <a class="footText" href="<?php echo $root_dir;?>Warangal/hotels-in-Warangal">Hotels in Warangal </a><br>
        <a class="footText" href="<?php echo $root_dir;?>Nizamabad/hotels-in-Nizamabad">Hotels in Nizamabad </a><br>
      </div>
      
     
      <div class="clearfix"></div>
          <br>
        <div id="panelss"  style="display:none;"> 
      <div class="col-sm-3 cabs_value_cities">
         <h4>Hotels in Gujarat</h4>
         <a class="footText"  href="<?php echo $root_dir;?>Ahmedabad/hotels-in-Ahmedabad"> Hotels in Ahmedabad</a><br>
         <a class="footText" href="<?php echo $root_dir;?>Rajkot/hotels-in-Rajkot">Hotels in Rajkot</a><br>
         <a class="footText" href="<?php echo $root_dir;?>Ghandhinagar/hotels-in-Ghandhinagar">Hotels in Ghandhinagar s</a><br>
         <a class="footText" href="<?php echo $root_dir;?>Surat/hotels-in-Surat">Hotels in Surat </a><br>
         <a class="footText" href="<?php echo $root_dir;?>Bhuj/hotels-in-Bhuj">Hotels in Bhuj </a><br>
      </div>  
       <div class="col-sm-3 cabs_value_cities">
            <h4>Hotels in kerala</h4>
            <a class="footText" href="<?php echo $root_dir;?>kochi/hotels-in-kochi">Hotels in kochi </a><br>
            <a class="footText" href="<?php echo $root_dir;?>munnar/hotels-in-munnar">Hotels in munnar </a><br>
            <a class="footText" href="<?php echo $root_dir;?>thiruvananthapuram/hotels-in-thiruvananthapuram">Hotels in thiruvananthapuram </a><br>
            <a class="footText" href="<?php echo $root_dir;?>kozhikode/hotels-in-kozhikode">Hotels in kozhikode </a><br>
            <a class="footText" href="<?php echo $root_dir;?>kovalam/hotels-in-kovalam">Hotels in kovalam </a><br>
         </div>
         <div class="col-sm-3 cabs_value_cities">
            <h4>Hotels in meghalaya</h4>
            <a class="footText" href="<?php echo $root_dir;?>shillong/hotels-in-shillong">Hotels in shillong </a><br>
            <a class="footText" href="<?php echo $root_dir;?>dawki/hotels-in-dawki">Hotels in dawki </a><br>
            <a class="footText" href="<?php echo $root_dir;?>umiam/hotels-in-umiam">Hotels in umiam </a><br>
            <a class="footText" href="<?php echo $root_dir;?>mawsynram/hotels-in-mawsynram">Hotels in mawsynram </a><br>
            <a class="footText" href="<?php echo $root_dir;?>cherrapunji/hotels-in-cherrapunji">Hotels in cherrapunji </a><br>
         </div>
        
         <div class="col-sm-3 cabs_value_cities">
            <h4>Hotels in Bihar</h4>
            <a class="footText" href="<?php echo $root_dir;?>Bodh Gaya/hotels-in-Bodh Gaya">Hotels in Bodh Gaya </a><br>
            <a class="footText" href="<?php echo $root_dir;?>Patna/hotels-in-Patna">Hotels in Patna </a><br>
            <a class="footText" href="<?php echo $root_dir;?>Rajgir/hotels-in-Rajgir">Hotels in Rajgir </a><br>
            <a class="footText" href="<?php echo $root_dir;?>sasaram/hotels-in-sasaram">Hotels in sasaram </a><br>
            <a class="footText" href="<?php echo $root_dir;?>Darbhanga/hotels-in-Darbhanga">Hotels in Darbhanga </a><br>
         </div>
         <div class="" style="clear: both;"></div>
          <br>
         <div class="col-sm-3 cabs_value_cities">
            <h4>Hotels in Assam</h4>
            <a class="footText" href="<?php echo $root_dir;?>Guwahati/hotels-in-Guwahati">Hotels in Guwahati </a><br>
            <a class="footText" href="<?php echo $root_dir;?>Tezpur/hotels-in-Tezpur">Hotels in Tezpur </a><br>
            <a class="footText" href="<?php echo $root_dir;?>Majuli/hotels-in-Majuli">Hotels in Majuli </a><br>
            <a class="footText" href="<?php echo $root_dir;?>silchar/hotels-in-silchar">Hotels in silchar </a><br>
            <a class="footText" href="<?php echo $root_dir;?>Dimapur/hotels-in-Dimapur">Hotels in Dimapur </a><br>
         </div>
         <div class="col-sm-3 cabs_value_cities">
            <h4>Hotels in West Bengal</h4>
            <a class="footText" href="<?php echo $root_dir;?>Kolkata/hotels-in-Kolkata">Hotels in Kolkata </a><br>
            <a class="footText" href="<?php echo $root_dir;?>Darjeeling/hotels-in-Darjeeling">Hotels in Darjeeling </a><br>
            <a class="footText" href="<?php echo $root_dir;?>Digha/hotels-in-Digha">Hotels in Digha </a><br>
            <a class="footText" href="<?php echo $root_dir;?>Howrah/hotels-in-Howrah">Hotels in Howrah </a><br>
            <a class="footText" href="<?php echo $root_dir;?>Kalimpong/hotels-in-Kalimpong">Hotels in Kalimpong </a><br>
         </div>
         <div class="col-sm-3 cabs_value_cities">
            <h4>Hotels in Tripura</h4>
            <a class="footText" href="<?php echo $root_dir;?>Udaipur/hotels-in-Udaipur">Hotels in Udaipur </a><br>
            <a class="footText" href="<?php echo $root_dir;?>Agartala/hotels-in-Agartala">Hotels in Agartala </a><br>
            <a class="footText" href="<?php echo $root_dir;?>Kailashahar/hotels-in-Kailashahar">Hotels in Kailashahar </a><br>
            <a class="footText" href="<?php echo $root_dir;?>Narsinghgarh/hotels-in-Narsinghgarh">Hotels in Narsinghgarh </a><br>
            <a class="footText" href="<?php echo $root_dir;?>Amarpur/hotels-in-Amarpur">Hotels in Amarpur </a><br>
         </div>
         <div class="col-sm-3 cabs_value_cities">
            <h4>Hotels in Maharashtra</h4>
            <a class="footText" href="<?php echo $root_dir;?>Mumbai/hotels-in-Mumbai">Hotels in Mumbai </a><br>
            <a class="footText" href="<?php echo $root_dir;?>Pune/hotels-in-Pune">Hotels in Pune </a><br>
            <a class="footText" href="<?php echo $root_dir;?>Aurangabad/hotels-in-Aurangabad">Hotels in Aurangabad </a><br>
            <a class="footText" href="<?php echo $root_dir;?>Nagpur/hotels-in-Nagpur">Hotels in Nagpur </a><br>
            <a class="footText" href="<?php echo $root_dir;?>Shirdi/hotels-in-Shirdi">Hotels in Shirdi </a><br>
         </div>
         <div class="" style="clear: both;"></div>
          <br>
         <div class="col-sm-3 cabs_value_cities">
            <h4>Hotels in Punjab</h4>
            <a class="footText" href="<?php echo $root_dir;?>Amritsar/hotels-in-Amritsar">Hotels in Amritsar </a><br>
            <a class="footText" href="<?php echo $root_dir;?>Patiala/hotels-in-Patiala">Hotels in Patiala </a><br>
            <a class="footText" href="<?php echo $root_dir;?>Jalandhar/hotels-in-Jalandhar">Hotels in Jalandhar </a><br>
            <a class="footText" href="<?php echo $root_dir;?>Ludhiana/hotels-in-Ludhiana">Hotels in Ludhiana </a><br>
            <a class="footText" href="<?php echo $root_dir;?>Mohali/hotels-in-Mohali">Hotels in Mohali </a><br>
         </div>
         <div class="col-sm-3 cabs_value_cities">
            <h4>Hotels in Uttar Pradesh</h4>
            <a class="footText" href="<?php echo $root_dir;?>Agra/hotels-in-Agra">Hotels in Agra </a><br>
            <a class="footText" href="<?php echo $root_dir;?>Allahabad/hotels-in-Allahabad">Hotels in Allahabad </a><br>
            <a class="footText" href="<?php echo $root_dir;?>Varanasi/hotels-in-Varanasi">Hotels in Varanasi </a><br>
            <a class="footText" href="<?php echo $root_dir;?>Lucknow/hotels-in-Lucknow">Hotels in Lucknow </a><br>
            <a class="footText" href="<?php echo $root_dir;?>Noida/hotels-in-Noida">Hotels in Noida </a><br>
         </div>
         <div class="col-sm-3 cabs_value_cities">
            <h4>Hotels in Karnataka</h4>
            <a class="footText" href="<?php echo $root_dir;?>Bengaluru/hotels-in-Bengaluru">Hotels in Bengaluru </a><br>
            <a class="footText" href="<?php echo $root_dir;?>Mysore/hotels-in-Mysore">Hotels in Mysore </a><br>
            <a class="footText" href="<?php echo $root_dir;?>Mangalore/hotels-in-Mangalore">Hotels in Mangalore </a><br>
            <a class="footText" href="<?php echo $root_dir;?>Srirangapatna/hotels-in-Srirangapatna">Hotels in Srirangapatna </a><br>
            <a class="footText" href="<?php echo $root_dir;?>Chikmagalur/hotels-in-Chikmagalur">Hotels in Chikmagalur </a><br>
         </div>
         <div class="col-sm-3 cabs_value_cities">
            <h4>Hotels in Jammu and Kashmir</h4>
            <a class="footText" href="<?php echo $root_dir;?>Pangong Tso/hotels-in-Pangong Tso">Hotels in Pangong Tso </a><br>
            <a class="footText" href="<?php echo $root_dir;?>Jammu/hotels-in-Jammu">Hotels in Jammu </a><br>
            <a class="footText" href="<?php echo $root_dir;?>Zanskar/hotels-in-Zanskar">Hotels in Zanskar </a><br>
            <a class="footText" href="<?php echo $root_dir;?>Nyak Tso/hotels-in-Nyak Tso">Hotels in Nyak Tso </a><br>
            <a class="footText" href="<?php echo $root_dir;?>Saboo/hotels-in-Saboo">Hotels in Saboo </a><br>
         </div>
         <div class="" style="clear: both;"></div>
          <br>
         <div class="col-sm-3 cabs_value_cities">
            <h4>Hotels in Rajasthan</h4>
            <a class="footText" href="<?php echo $root_dir;?>Jaipur/hotels-in-Jaipur">Hotels in Jaipur </a><br>
            <a class="footText" href="<?php echo $root_dir;?>Jodhpur/hotels-in-Jodhpur">Hotels in Jodhpur </a><br>
            <a class="footText" href="<?php echo $root_dir;?>Udaipur/hotels-in-Udaipur">Hotels in Udaipur </a><br>
            <a class="footText" href="<?php echo $root_dir;?>Ajmer/hotels-in-Ajmer">Hotels in Ajmer </a><br>
            <a class="footText" href="<?php echo $root_dir;?>Bharatpur/hotels-in-Bharatpur">Hotels in Bharatpur </a><br>
         </div>
         <div class="col-sm-3 cabs_value_cities">
            <h4>Hotels in Haryana</h4>
            <a class="footText" href="<?php echo $root_dir;?>New delhi/hotels-in-New delhi">Hotels in New delhi </a><br>
            <a class="footText" href="<?php echo $root_dir;?>Gurugram/hotels-in-Gurugram">Hotels in Gurugram </a><br>
            <a class="footText" href="<?php echo $root_dir;?>Ambala/hotels-in-Ambala">Hotels in Ambala </a><br>
            <a class="footText" href="<?php echo $root_dir;?>Faridabad/hotels-in-Faridabad">Hotels in Faridabad </a><br>
            <a class="footText" href="<?php echo $root_dir;?>Parwanoo/hotels-in-Parwanoo">Hotels in Parwanoo </a><br>
         </div>
         <div class="col-sm-3 cabs_value_cities">
            <h4>Hotels in Madhya pradesh</h4>
            <a class="footText" href="<?php echo $root_dir;?>Jhansi/hotels-in-Jhansi">Hotels in Jhansi </a><br>
            <a class="footText" href="<?php echo $root_dir;?>Gwalior/hotels-in-Gwalior">Hotels in Gwalior </a><br>
            <a class="footText" href="<?php echo $root_dir;?>Jabalpur/hotels-in-Jabalpur">Hotels in Jabalpur </a><br>
            <a class="footText" href="<?php echo $root_dir;?>Ujjain/hotels-in-Ujjain">Hotels in Ujjain </a><br>
            <a class="footText" href="<?php echo $root_dir;?>Satna/hotels-in-Satna">Hotels in Satna </a><br>
         </div>
         <div class="col-sm-3 cabs_value_cities">
            <h4>Hotels in Uttarakhand</h4>
            <a class="footText" href="<?php echo $root_dir;?>Haridwar/hotels-in-Haridwar">Hotels in Haridwar </a><br>
            <a class="footText" href="<?php echo $root_dir;?>Badrinath/hotels-in-Badrinath">Hotels in Badrinath </a><br>
            <a class="footText" href="<?php echo $root_dir;?>Dehradun/hotels-in-Dehradun">Hotels in Dehradun </a><br>
            <a class="footText" href="<?php echo $root_dir;?>Mussoorie/hotels-in-Mussoorie">Hotels in Mussoorie </a><br>
            <a class="footText" href="<?php echo $root_dir;?>Kausani/hotels-in-Kausani">Hotels in Kausani </a><br>
         </div>
         <div class="" style="clear: both;"></div>
          <br>
         <div class="col-sm-3 cabs_value_cities">
            <h4>Hotels in Jharkhand</h4>
            <a class="footText" href="<?php echo $root_dir;?>Ranchi/hotels-in-Ranchi">Hotels in Ranchi </a><br>
            <a class="footText" href="<?php echo $root_dir;?>Dhanbad/hotels-in-Dhanbad">Hotels in Dhanbad </a><br>
            <a class="footText" href="<?php echo $root_dir;?>Deoghar/hotels-in-Deoghar">Hotels in Deoghar </a><br>
            <a class="footText" href="<?php echo $root_dir;?>Hazaribagh/hotels-in-Hazaribagh">Hotels in Hazaribagh </a><br>
            <a class="footText" href="<?php echo $root_dir;?>Ghatshila/hotels-in-Ghatshila">Hotels in Ghatshila </a><br>
         </div>
         <div class="col-sm-3 cabs_value_cities">
            <h4>Hotels in Sikkim</h4>
            <a class="footText" href="<?php echo $root_dir;?>gangtok/hotels-in-gangtok">Hotels in gangtok </a><br>
            <a class="footText" href="<?php echo $root_dir;?>Aritar/hotels-in-Aritar">Hotels in Aritar </a><br>
            <a class="footText" href="<?php echo $root_dir;?>Dzuluk/hotels-in-Dzuluk">Hotels in Dzuluk </a><br>
            <a class="footText" href="<?php echo $root_dir;?>Namchi/hotels-in-Namchi">Hotels in Namchi </a><br>
            <a class="footText" href="<?php echo $root_dir;?>Ravangla/hotels-in-Ravangla">Hotels in Ravangla </a><br>
         </div>
         <div class="col-sm-3 cabs_value_cities">
            <h4>Hotels in Himachal pradesh</h4>
            <a class="footText" href="<?php echo $root_dir;?>Manali/hotels-in-Manali">Hotels in Manali </a><br>
            <a class="footText" href="<?php echo $root_dir;?>Shimla/hotels-in-Shimla">Hotels in Shimla </a><br>
            <a class="footText" href="<?php echo $root_dir;?>Kullu/hotels-in-Kullu">Hotels in Kullu </a><br>
            <a class="footText" href="<?php echo $root_dir;?>Manikaran/hotels-in-Manikaran">Hotels in Manikaran </a><br>
            <a class="footText" href="<?php echo $root_dir;?>Chamba/hotels-in-Chamba">Hotels in Chamba </a><br>
         </div>
         <div class="col-sm-3 cabs_value_cities">
            <h4>Hotels in Manipur</h4>
            <a class="footText" href="<?php echo $root_dir;?>Imphal/hotels-in-Imphal">Hotels in Imphal </a><br>
            <a class="footText" href="<?php echo $root_dir;?>Moirang/hotels-in-Moirang">Hotels in Moirang </a><br>
            <a class="footText" href="<?php echo $root_dir;?>Tamenglong/hotels-in-Tamenglong">Hotels in Tamenglong </a><br>
            <a class="footText" href="<?php echo $root_dir;?>Andro/hotels-in-Andro">Hotels in Andro </a><br>
            <a class="footText" href="<?php echo $root_dir;?>Bishnupur/hotels-in-Bishnupur">Hotels in Bishnupur </a><br>
         </div>
         <div class="" style="clear: both;"></div>
          <br>
         <div class="col-sm-3 cabs_value_cities">
            <h4>Hotels in Chattisgarh</h4>
            <a class="footText" href="<?php echo $root_dir;?>Raipur/hotels-in-Raipur">Hotels in Raipur </a><br>
            <a class="footText" href="<?php echo $root_dir;?>Durg/hotels-in-Durg">Hotels in Durg </a><br>
            <a class="footText" href="<?php echo $root_dir;?>Bhilai/hotels-in-Bhilai">Hotels in Bhilai </a><br>
            <a class="footText" href="<?php echo $root_dir;?>Jagdalpur/hotels-in-Jagdalpur">Hotels in Jagdalpur </a><br>
            <a class="footText" href="<?php echo $root_dir;?>Korba/hotels-in-Korba">Hotels in Korba </a><br>
         </div>
         <div class="col-sm-3 cabs_value_cities">
            <h4>Hotels in Mizoram</h4>
            <a class="footText" href="<?php echo $root_dir;?>Aizawl/hotels-in-Aizawl">Hotels in Aizawl </a><br>
            <a class="footText" href="<?php echo $root_dir;?>Serchhip/hotels-in-Serchhip">Hotels in Serchhip </a><br>
            <a class="footText" href="<?php echo $root_dir;?>Lunglei/hotels-in-Lunglei">Hotels in Lunglei </a><br>
            <a class="footText" href="<?php echo $root_dir;?>Thenzawl/hotels-in-Thenzawl">Hotels in Thenzawl </a><br>
            <a class="footText" href="<?php echo $root_dir;?>Murlen/hotels-in-Murlen">Hotels in Murlen </a><br>
         </div>
       </div>
       <div class="clearfix"></div>
        <Span  id="more_cities" class=" footer_show more_citiss" > <a>+ Show More</a>
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

<?php } else{ ?>

<div class="container-fluid">
<?php  }?>
  


            <div class="modal" id="myModal">
                  <div class="modal-dialog" style="width: 290px; margin-top: 135px;">
                    <div class="modal-content" style="border-radius: 0px;">
                      <div class="modal-header signin_fulls" style="border-bottom: none;">
                        <button type="button"  class="close" data-dismiss="modal" aria-hidden="true"><i class="material-icons" style="margin-left: -7px;font-size: 18px;">close</i></button>
                        <h4 class="modal-title" id="h4Sty" >Sign In</h4>
                      </div>
                      <div class="modal-body  signinmodal">
                        
                      

                       <div class="form-group ">
                        
                        <div class="input-group">
                         <div class="input-group-addon">
                        <span class="glyphicon glyphicon-user" ></span> 
                         </div>
                         <input class="form-control" id="username" name="uname" onfocus="if(this.value=='Email Id / Mobile Number') this.value='';" onblur="if(this.value=='') this.value='Email Id / Mobile Number';" placeholder="Email Id / Mobile Number" type="text" tabindex="1">
                        </div>
                       </div>

                       <div class="form-group ">
                        
                        <div class="input-group">
                         <div class="input-group-addon">
                        <span class="glyphicon glyphicon-lock"></span> 
                         </div>
                         <input class="form-control" id="password" name="pword" onfocus="if(this.value=='Password') this.value='';" onblur="if(this.value=='') this.value='Password';"  placeholder="Password" tabindex="2" type="password">
                         <input type="hidden" name="search" id="search" value="<?php echo $search_page;?>">
                              <input type="hidden" name="url" id="url" value="<?php echo $root_dir;?>">
                        </div>
                       </div>

                     
                          
                            <div class="col-sm-12" style="background-color: transparent;padding: 0">
                            <a style="font-size: 12px; cursor: pointer;" data-target="#modal-3" data-toggle="modal" id="closeSignIn">Forgot password?</a> <button style="float:right;" href="#" class="btn btn-primary" onclick="authenticate_user();">Log-In</button>
                            
                            
                             
                            </div>
                          
                        
                      <div class="alert alert-danger" id="invalidpassword" style="width: 220px; margin:10px  0; text-align: left; padding: 5px; font-size: 8pt; background-color: white; display:none;">Invalid Username and Password</div>
                              <div class="alert alert-success" id="correctpassword" style="width: 210px;margin:10px  0;background-color: white; padding: 5px; font-size: 8pt; display:none;"><button class="close" data-dismiss="alert">×</button><strong>Success!</strong>Login Successfull </div>
                              <?php if(isset($_GET['status']) && ($_GET['status'] == 'timeout')){ ?> 
                              <div class="alert alert-success" style="width:275px; margin: 0 auto; text-align:left;"><button class="close" data-dismiss="alert">×</button><strong>Your Session has been expired.</strong> </div>
                              <br><?php } ?>
                              <div class="alert alert-success" id="logout_success" style="width: 185px; height: 30px; margin: 0; text-align: left; background-color: white; padding: 5px; font-size: 8pt;display:none;"><strong>Successfully logged out.</strong> </div>


                               
                      </div>
                      <div class="modal-footer" >
                         <div class="form-group " style="padding-bottom: 15px;">
                          
                          <div class=" full_notmember" > <span>Not a Member</span><a  href="#modal-2" data-toggle="modal" data-dismiss="modal" >Sign up Now?</a></div>
                           
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
<div class="modal-dialog" style=" margin-top: 100px;" >
 <div class="modal-content" style=" background-color: transparent;box-shadow: none!important;border:none; position: relative;">
         <div class="modal-body" style="padding: 10px;position: relative;">            
               <form name="registrationform" id="registrationform" method="post" action="">
                  <!-- <input type="hidden" id="pagname" value=>-->
                  <div class="row  " >
                     <div class="col-md-12  addcustomer  " style="background:#fff; font-weight: normal;">
                      <br>
                        <button type="button"  class=" close1" data-dismiss="modal" aria-hidden="true"><i class="material-icons" style="margin-left: -7px;font-size: 20px;margin-top: 6px;">close</i></button>
                        
                          <h3 class="reg_headings"> Registration Form</h3>
                       
                        
  
                        <fieldset>
                          
                           <div class="full_val">
                              <div class="row">
                                  <div class="col-xs-12 col-sm-6 form-group groupstyle has-feedback has-error">
                                    <label>Email id&nbsp;&nbsp;<span style="color: #dd3236;">*</span></label>
                                    <input class="form-control" type="text" placeholder="Enter valid Email id" id="email_id" name="email_id" style="border-radius: 1px;" >
                                 </div>
                                  <div class="col-xs-12 col-sm-6 form-group groupstyle has-feedback has-error">
                                    <label>Mobile no&nbsp;&nbsp;<span style="color: #dd3236; ">*</span></label>
                                    <input placeholder="Enter valid Mobile No" class="form-control" type="text" id="mobile_no" name="mobile_no" style="border-radius: 1px;">
                                </div>
                                <div class="clearfix"></div>
                                <div class="col-xs-12 col-sm-6  form-group groupstyle has-feedback has-error">
                                    <label>Password&nbsp;&nbsp;<span style="color: #dd3236;">*</span></label>
                                    <input class="form-control" type="password" id="rpassword" name="password" autocomplete="off" style="border-radius: 1px;">
                                 </div>
                                 <div class="col-xs-12 col-sm-6 form-group groupstyle has-feedback has-error">
                                    <label>Re-enter password&nbsp;&nbsp;<span style="color: #dd3236; ">*</span></label>
                                    <input class="form-control" type="password" id="confirmPassword" name="confirmPassword" autocomplete="off" style="border-radius: 1px;" >
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
                          <span class="loader_spin" style="font-size:20px; display:none;"> <i class="fa fa-spinner fa-spin" aria-hidden="true"></i></span>
                          <button style="width: 100px;border:none; background:#dd3236; " type="submit" class="btn btn-primary" >Submit</button>

                           <button style="width: 100px;border:none; background:#dd3236 " type="button" class="btn btn-primary" onclick="reload_form();">Reset</button>
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

<div class="modal" id="modal-3" style=" margin-top: 100px; ">
  <div class="modal-dialog">
    <div class="modal-content" style="padding: 10px;border-radius: 0px;">
     
      <div class="modal-body">
         <button type="button"  class=" close1" data-dismiss="modal" aria-hidden="true" id="closeforgot" style="margin-top: -25px;margin-right: -25px;"><i class="material-icons" style="margin-left: -7px;font-size: 20px;margin-top: 6px;">close</i></button>
        <div class="row">
          <label><span class="glyphicon glyphicon-lock"></span>Recover Password</label>
        </div>
        
        <div class="row">
          <div class="col-sm-12" style="padding-left: 30px; padding-right: 30px;">
              <h5>Type in your registered email address</h5>
              <input class="form-control" type="" name="Email Address" placeholder="" style="width: 100%; text-align: left;">
          </div>
          
        </div>

         <div class="row">
          <div class="col-sm-12" style="padding-left: 30px; padding-right: 30px; padding-top: 10px; padding-bottom: -10px; text-align: right">

             
            <button type="button"   class="btn btn-primary">Submit</button>
          </div>
        </div>

      </div>
      
    </div><!-- /.modal-content 3-->
  </div><!-- /.modal-dialog3 -->
</div><!-- /.modal3 -->  
<!-- End Modal Form3 -->
<div class="clearfix">
</div>           
       
            <div class="footer__copyright footcopy" >

      <div class="container" style="margin-top: 20px;">
       <div class="row footer_rows_up">  
       


         <div class="col-xs-6 col-sm-2  footer_content_columns" >
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
         <div class="col-xs-6 col-sm-2 footer_content_columns" >
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
         <div class="col-xs-6 col-sm-2 footer_content_columns" >
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
         <div class="col-xs-6 col-sm-2 footer_content_columns" >
         <ul class="foot_content">
              <li class="foot_list">
                <a href="about_us.php">About Us</a>
              </li>
              <li class="foot_list">
                <a href="cancellation_policy.php">Cancellation Policy</a>
              </li> 
              <li class="foot_list">
                <a href="#">Customer service help</a>
              </li> 
               <li class="foot_list">
                <a href="#">Give website feedback</a>
              </li>             
            </ul>
        </div>
         <div class="col-xs-6 col-sm-2 footer_content_columns" >
           <ul class="foot_content">
              <li class="foot_list">
                <a href="terms_and_conditions.php">Terms and conditions</a>
              </li>
              <li class="foot_list">
                <a href="privacy_policy.php">Privacy and cookies</a>
              </li> 
              <li class="foot_list">
                <a href="#">Contact us</a>
              </li>  
              <li class="foot_list">
                <a href="https://staysinn.com/hotel_signup.php" style="color: #12ebcc;">Enroll Here</a>
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

      


       <div class="col-sm-4"><div class=" widget widget-sidebar ">
           
            <div class="widget_wysija_cont">
               <div id="msg-form-wysija-2" class="wysija-msg ajax"></div>
               <form id="form-wysija-2" method="post" action="#wysija" class="widget_wysija">


                  <p class="wysija-paragraph" style="padding: 0;position: relative;">
                     <label>Email <span class="wysija-required">*</span></label>
                     <input placeholder="Subscribe to our Newsletter" type="text" name="wysija[user][email]" class="myinput_text wysija-input validate[required,custom[email]]" title="Email"  value=""   />
                     
                    
                  </p>

                <!--   <input class="wysija-submit wysija-submit-field" type="submit" value="Subscribe!" /> -->
                  <input type="hidden" name="form_id" value="1" />
                  <input type="hidden" name="action" value="save" />
                  <input type="hidden" name="controller" value="subscribers" />
                  <input type="hidden" value="1" name="wysija-page" />
                  <input type="hidden" name="wysija[user_list][list_ids]" value="1" />
                   
               </form>
            </div>
         </div></div>
         <div class="col-sm-2">
            <h6 class="follow_uss"></h6>
            <input    class="wysija-submit myinput_button wysija-submit-field" type="submit" value="Subscribe!" style="margin-top: 13px;"/>
        </div>

         <div class="col-sm-6 "> <div class="widget widget-sidebar ">
            <article class="byt_social_widget BookYourTravel_Social_Widget">
               <h6 class="follow_uss">Follow us</h6>
               <ul class="social">
                  <li><a href="#" title="facebook" style="background: #3b5998;"><i class="fa fa-facebook fa-fw" style="color:#fff;"></i></a>
                  </li>
                  <li><a href="#" title="twitter" style="background: #1da1f2;"><i class="fa fa-twitter fa-fw" style="color:#fff;"></i></a>
                  </li>
                  
                  <li><a href="#" title="linkedin" style="background: #0073b1;"><i class="fa fa-linkedin fa-fw" ></i></a></li>
                  <li><a href="#" title="googleplus" style="background: #dd3236;"><i class="fa fa-google-plus fa-fw" style="color:#fff;"></i></a></li>
                  
                  
               </ul>
            </article>
         </div></div>
        
   </div>
            <p class="copy">© staysinn.com 2018. All rights reserved.Desinged by Buddies Technologies</p>
            <!--footer navigation-->            
            
            <!--//footer navigation-->
         </div>
      </div>
   </div>
</footer>
</div></div>

           
     
       
     


 <script type="text/javascript">
          $(document).ready(function(){
              $("#closeforgot").click(function(){
                  $("#modal-3").modal('hide');
              });
          });
      </script>




      <script type="text/javascript">
          $(document).ready(function(){
              $("#closeSignIn").click(function(){
                  $("#myModal").modal('hide');
              });
          });
      </script>






<script type="text/javascript">
    $(document).ready(function(e) {
        
        $( "#hotels_month" ).datepicker({
            changeMonth: true,
            changeYear: true,
            numberOfMonths: 2,
            dateFormat: "dd-mm-yy",
            minDate: 0,
            onSelect: function( selectedDate ) {
                //var tomorrow = new Date('10-12-2017');
                //console.log(tomorrow);
                console.log(selectedDate);
                var da = selectedDate.substring(0, 2);
                var mn = selectedDate.substring(3, 5);
                var yr = selectedDate.substring(6, 10);
                var newD = mn+'/'+(parseInt(da)+1)+'/'+yr;
                console.log(new Date(newD));
                /*
                
                var tomorrow = new Date('10/12/2017');//new Date('"'+selectedDate.getMonth()+'/'+selectedDate.getDate()+'/'+selectedDate.getFullYear()+'"');
                console.log(tomorrow);
                tomorrow.setDate(tomorrow.getDate() + 1);
                console.log(tomorrow);*/
                $( "#hotels_month1" ).datepicker( "option", "minDate", selectedDate );
                //$( "#hotels_month1" ).focus();
            }
        });
        $( "#hotels_month1" ).datepicker({
            changeMonth: true,
            changeYear: true,
            numberOfMonths: 2,
            dateFormat: "dd-mm-yy",
            onSelect: function( selectedDate ) {
                $( "#hotels_month" ).datepicker( "option", "maxDate", selectedDate );   
            }
        });
        $( "#hotels_month_book" ).datepicker({
            changeMonth: true,
            changeYear: true,
            numberOfMonths: 2,
            dateFormat: "dd-mm-yy",
            minDate: 0,
            maxDate: $("#hotels_month1_book").val(),
            onSelect: function( selectedDate ) {
                $( "#hotels_month1_book" ).datepicker( "option", "minDate", selectedDate );
                //$( "#hotels_month1" ).focus();
            }
        });
        $( "#hotels_month1_book" ).datepicker({
            changeMonth: true,
            changeYear: true,
            numberOfMonths: 2,
            dateFormat: "dd-mm-yy",
            minDate: $("#hotels_month_book").val(),
            onSelect: function( selectedDate ) {
                $( "#hotels_month_book" ).datepicker( "option", "maxDate", selectedDate );
                if($( "#hotels_month_book" ).val() != $('#hotel_month').val() || $('#hotel_month1').val() != selectedDate){
                    $('#hotel_month1').val(selectedDate);
                    $('.book-hotel-form').submit();
                    //$('.done').trigger("click");
                }
            }
        });
    });
      window.onscroll = function() {scrollFunction()};
   
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
        $('html, body').animate({scrollTop:0}, 'slow');
   }
   
   $(document).ready(function(){
   
      $(window).scroll(function(){ 
   
        if (window.matchMedia('(max-width: 768px)').matches) {
      $(".journey_detail").css('position','relative');
          $(".journey_detail").css('top','0');
   
        }else if(parseInt($(window).height())-parseInt($(".foo").height())<=parseInt($(window).scrollTop()))
        {
          $(".journey_detail").css('position','absolute');
          //$(".journey_detail").css('top',$(".detail_container").height()-$(".journey_detail").height());
          $(".journey_detail").css('top','253px');
        }else{
          $(".journey_detail").css('position','fixed');
          $(".journey_detail").css('top','');
   
        }
   
       });
   });
   
</script>
<script>
  jQuery.validator.setDefaults({
      debug: true,
      success: "valid"
    });
 
  jQuery.validator.addMethod("password_reg", function(value, element){
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
               
                 mobile_no:{              
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
                   required: true ,
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
              success: function(label) {  
            label.addClass("valid");
     },
        
           
   

           submitHandler: function(validator, form) {
      $(".loader_spin").css("display","inline-block");
           
          save_customerdetails();
   
   
           },
       });
   
   
  


     $(".myid").keypress(function (e) {
   
     if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
        
        $("#man").html("Numbers Only").show().fadeOut("slow");
               return false;
    }
   });


     $("#my_text_area").click(function()
   {
    $(this).text("");
    
   
   });  
 /* function sortfor(class_id, for_class, value)
  {
   // $("#"+class_id).val(value);
   // $("."+for_class+"."+class_id).css("color","red");
   //$('.dropdown-menus li a').trigger('click');

  }*/
    $(window).ready(function() {
      $('.grid-view').click(function(e) {
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
      
      $('.list-view').click(function(e) {
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
      
     $("#sendquery").click(function(e)
     {
    function validateEmail(email) {
    var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
    }
        
       var error=0;
       if($("#name").val()=='')
       {
         $("#name_error").css("display","block");
         error++;
       }
        if($("#email1").val()=='')
       {

         $("#email_error").css("display","block");
         error++;
       }else{
         if (validateEmail($("#email1").val())) {
          
        } else {
          $("#email_error").css("display","block");
            error++;
        }
       }
       if($("#mobile").val()=='')
       {
         $("#mobile_error").css("display","block");
         error++;
       }
  
     if(error!=0)
     {
       return false;
     }
       $("#loader-spin").css("display","block");
       e.preventDefault();
       $.ajax({
         url:"<?php echo $root_dir;?>include/email.php",
         type:"POST",
         data:$("#send_query").serialize(),
          success: function (result) { 
             $("#loader-spin").css("display","none");
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
             setTimeout(function(){$("#mesage").hide();},1000);
          }     
       });
   return false;
     });
     }); 
 $("#captcha_code").attr("placeholder", "Type the captcha").val("").focus().blur();
  $("#captcha_code").addClass("col-xs-12 col-sm-4 form-control");
</script>
<button onclick="topFunction()" id="myBtn" title="Go to top" style="position: fixed;bottom: 40px;
    right: 1px;padding:0 0; background:transparent!important;background-position: cover; border: 0px solid transparent;"><img src="<?php echo $root_dir; ?>images/arr2.png" style="width:40px; height: 40px;"></button>
    </body>
</html>

<!-- <script> 
  $( document ).ready(function()
   {
           $(".cabs_value_cities>a").addClass("hvr-underline-from-left");
           $(".foot_list>a").addClass("hvr-underline-from-left");
       });
   </script> -->



<script type="text/javascript">
  // $("#more_cities").click(function(){
  //         $(this).text($(this).text() == "- Hide All Cities" ? "+ Show All Cities" : "- Hide All Cities");
  //          $("#panelss").slideToggle("slow");
  //               });


  $("#more_cities").click(function(){

            $(this).text($(this).text() == "(-)Show less" ? " Show More..." : "(-)Show less");
             $("#panelss").slideToggle("slow");

           

           
         });
</script>

<!-- staysinn/hotels/search/index and indexajax.php js-->

<script src="https://cdnjs.cloudflare.com/ajax/libs/css-element-queries/1.0.1/ResizeSensor.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/theia-sticky-sidebar@1.7.0/dist/theia-sticky-sidebar.min.js"></script>


<!-- staysinn/hotels/search/index and indexajax.php js end-->