

 <?php include 'include/header.php' ?>

 <form id="index_search" action="book.php" method="post">
<div class="container-fluid" style="background: url(images/hotelimage.jpg);min-height: 580px;background-size:cover;">
 <div class="col-sm-6 col-sm-offset-4 container_index_hotel">

   <div class="col-sm-4">
                                          <label>Select Check-in date</label>
                                          <input name="checkindate"  placeholder="Check In" type="text" class="datepicker" required="">
                                          
                                          </div>
                                          <div class="col-sm-4">
                                          <label>Select Check-out date</label>
                                          <input name="checkoutdate" type="text"  placeholder="Check Out" class="datepicker2"  required="" >
                                          
                                          </div>
 <div class="col-sm-4">
 <label>Rooms</label>
    <div class="section_rooms_All" >
       <input type="hidden" id="guest" name="guest" value="">
         <input type="hidden" id="guestval" value="0">
       <input type="hidden" id="roomval" value="1">
											 <input type="hidden" id="child_age_json" name="child_age_json" value="">


    <button id="button_add_rooms_all" class="button_add_roo"  type="button"  data-toggle="collapse"  data-target="#demo"><span class="total_guest_room"></span><Span style="float: right;"><i class="fa fa-angle-down arrows_addroom_all" aria-hidden="true"></i></Span></button>
                                             <div class="collapse collapse_ful_all"  id="demo" >
                                                <div class="inner_demo outer_pax">
                                                   <div  class="pax_container" data="1">
                                                      <div class="detail_pax detail_pax_1">
                                                         <p class="detail_pax_p">Room <span class="roomnumber">1</span></p>
                                                         <p ><span class="this_adult">2</span> Adults, <span class="this_child">0</span>  Child</p>
                                                      </div>
                                                      <div class="content_pax content_pax_1" style="display: none">
                                                         <p class="head_title_room">Adult (+12 yrs)</p>
                                                         <ul class="pagination pagination-sm pagination_lists adultlist adultlist_1">
                                                            <li onclick="changeadult(1,this)" >1</li>
                                                            <li class="actives" onclick="changeadult(2,this)" >2</li>
                                                            <li onclick="changeadult(3,this)" >3</li>
                                                            <li onclick="changeadult(4,this)" >4</li>
                                                            <li onclick="changeadult(5,this)" >5</li>
                                                            <li onclick="changeadult(6,this)" >6</li>
                                                         </ul>
                                                         <p class="head_title_room">Childern (1-12 yrs)</p>
                                                         <ul class="pagination pagination-sm pagination_lists childlist childlist_1">
                                                            <li class="actives" onclick="changechild(0,this)">0</li>
                                                            <li onclick="changechild(1,this)">1</li>
                                                            <li onclick="changechild(2,this)">2</li>
                                                            <li onclick="changechild(3,this)">3</li>
                                                            <li onclick="changechild(4,this)">4</li>
                                                         </ul>
                                                      </div>
                                                      <a class="edit"  onclick="editval(this)"  data="1"><i 
                                                      class="edit_minimize" style="font-size:10px;" >Edit</i></a>
                                                      <div class="minimize"  onclick="minimize(this)"  data="1"><!--<i class="fa fa-compress" aria-hidden="true"></i>--></div>
                                                      <div class="clearfix"></div>
                                                   </div>
                                                </div>
                                                <div class="con_for_but"></div>
                                                <button class="rooms_in_hotel" data="2" type="button" onclick="addroom(this)" >Add Room</button>
                                                <span class="done" onclick="ondone()">Done</span>
                                             </div>
                                          </div>
                                          </div>
                                          <div class="clearfix"></div>
                                         
                                         
<div class="col-sm-12">  <button  type="submit" class="btn btn-red margin_topbot_10 submi_in_index">Search</button>
                                          
                                          </div>
                                          </div>
                                       
                                          </form>

 

<script type="text/javascript">
   $(document).ready(function () {
   $(".datepicker").datepicker(
    { numberOfMonths: 2 ,
     minDate: 1,
    dateFormat: "dd-m-yy",
    onSelect: function(selected) {
   
          $(".datepicker2").datepicker("option","minDate", selected)
   
        }
   
    }); 
     $(".datepicker2").datepicker(
    { numberOfMonths: 2 ,
       minDate: $(".datepicker").val(),
       dateFormat: "dd-m-yy",
      onSelect: function(selected) {
         //  $(".datepicker").datepicker("option","maxDate", selected)
   
        }
   
   
    });
     var adult=$('.actives').text();
     $('.total_guest_room').text('1 Room, 2 Guests');
     
   
   
   
   });
   
   function changeadult(value,identifier)
   {
     var guest=0;
     var currentpax=$(identifier).closest(".pax_container").attr("data");
     $(".adultlist_"+currentpax+" li").removeClass("actives");
     $(identifier).addClass("actives");
   
     $('.actives').each(function(){
     guest=guest+parseInt($(this).text());
     });
    var room=$("#roomval").val();
   
     $('.detail_pax_'+currentpax+' .this_adult').text(value);
     $('.total_guest_room').text(room+' Room, '+guest+' Guests');
   
   }
   function changechild(value,identifier)
   {
     var guest=0;
    
    
     var currentpax=$(identifier).closest(".pax_container").attr("data");
     $(".childlist_"+currentpax+" li").removeClass("actives");
     $(identifier).addClass("actives");
   
     $('.actives').each(function(){
     guest=guest+parseInt($(this).text());
     });
    var room=$("#roomval").val();
      $('.detail_pax_'+currentpax+' .this_child').text(value);
     $('.total_guest_room').text(room+' Room, '+guest+' Guests');
	 
	 $('.child_age_div_'+currentpax).remove();
	 var ageSelect = '<div class="child_age_div_'+currentpax+'">';
	 for(c=1;c<=value;c++){
		 ageSelect = ageSelect + '<div class="clearfix"><p class="head_title_room">Child '+c+' Age</p><select style="width:25%;" class="child_age" data-room="'+currentpax+'"><option>1</option><option>2</option><option>3</option><option>4</option><option>5</option><option>6</option><option>7</option><option>8</option><option>9</option><option>10</option><option>11</option><option>12</option></select></div>';
	 }
	 ageSelect = ageSelect + '</div>';
	 $(".content_pax_"+currentpax).append(ageSelect);
   
   }
   function ondone()
   {
   
     $(".in").removeClass("in");
   }
   function editval(val)
   {
     var currentpax=$(val).closest(".pax_container").attr("data");
     $(".content_pax_"+currentpax).toggle("slow");
     var text=$(".edit[data='"+currentpax+"'] i").text();
     if(text=="Edit")
     {
      $(".edit[data='"+currentpax+"'] i").text("Minimize");
     }else{
      $(".edit[data='"+currentpax+"'] i").text("Edit");
     }
      
   }
   function minimize(val)
   {
      var currentpax=$(val).closest(".pax_container").attr("data");
     $(".content_pax_"+currentpax).hide("slow");
   }
   function removeroom(data)
   { var s=0;
     var currentpax=$(data).closest(".pax_container").attr("data");
     var guest=0;
     $(".pax_container_"+currentpax).remove();
     var i=1;
     $('.pax_container').each(function(){
        if($(this).hasClass("test"))
        {
          var item=$(this).attr("data");  
           $(this).attr("data",i);
            $(".pax_container_"+item+' .roomnumber').text(i);
             
   $(this).parent().find('.detail_pax_'+item).removeClass('detail_pax_'+item).addClass('detail_pax_'+i);
   $(this).parent().find('.content_pax_'+item).removeClass('content_pax_'+item).addClass('content_pax_'+i);
   $(this).parent().find('.adultlist_'+item).removeClass('adultlist_'+item).addClass('adultlist_'+i);
   $(this).parent().find('.childlist_'+item).removeClass('childlist_'+item).addClass('childlist_'+i);
   $(this).parent().find('.pax_container_'+item).removeClass('pax_container_'+item).addClass('pax_container_'+i);$(this).parent().find('.edit').attr("data",i);
   
   
        }
   s++;
        i++;
   
     });
   $(".rooms_in_hotel").attr('data',i);
   $('.actives').each(function(){
        guest=guest+parseInt($(this).text());
        });
   $("#roomval").val(s);
   $('.total_guest_room').text(s+' Room, '+guest+' Guests');
   }
   function addroom(value)
   {
   
        var intId=parseInt($(value).attr('data'));
        var inc=intId+1;
        $(".rooms_in_hotel").attr('data',inc);
       var append='<div  class="pax_container test pax_container_'+intId+'" data="'+intId+'"><div class="removepax" onclick="removeroom(this)"><i class="fa fa-times" aria-hidden="true"></i></div>';
           append+='<div class="detail_pax detail_pax_'+intId+'">';
           append+='<p class="detail_pax_p">Room <span class="roomnumber">'+intId+'</span></p>';
           append+='<p  ><span class="this_adult">2</span> Adults, <span class="this_child">0</span>  Child</p>';
           append+='</div>';
           append+='<div class="content_pax content_pax_'+intId+'" style="display:none">';
           append+='<p class="head_title_room">Adult (+12 yrs>)</p>';
           append+='<ul class="pagination pagination-sm pagination_lists adultlist adultlist_'+intId+'">';
           append+='<li onclick="changeadult(1,this)" >1</li>';
           append+='<li class="actives" onclick="changeadult(2,this)" >2</li>';
           append+='<li onclick="changeadult(3,this)" >3</li>';
           append+='<li onclick="changeadult(4,this)" >4</li>';
           append+='<li onclick="changeadult(5,this)" >5</li>';
           append+=' <li onclick="changeadult(6,this)" >6</li>';
           append+='</ul>';
           append+='<p class="head_title_room">Childern (1-12 yrs>)</p>';
           append+='<ul class="pagination pagination-sm pagination_lists childlist childlist_'+intId+'">';
           append+=' <li class="actives" onclick="changechild(0,this)">0</li><li onclick="changechild(1,this)">1</li>';
           append+=' <li onclick="changechild(2,this)" >2</li>';
           append+=' <li onclick="changechild(3,this)">3</li>';
           append+=' <li onclick="changechild(4,this)">4</li>';
   
           append+=' </ul>';
           append+='</div>';
           append+='<a class="edit " onclick="editval(this)" data="'+intId+'"><i  style="font-size:10px;" >Edit</i></a><a class="minimize"  onclick="minimize(this)"  data="'+intId+'"><!--<i class="fa fa-compress" aria-hidden="true"></i>--></a>';
           append+='<div class="clearfix"></div>';
           append+=' </div>';
   
           $(".outer_pax").append(append);
   
        var guest=0;     
        $('.actives').each(function(){
        guest=guest+parseInt($(this).text());
        });
       $("#roomval").val(intId);
       $('.total_guest_room').text(intId+' Room, '+guest+' Guests');
   
   }
   $(document).ready(function()
   {
     var room=0;
     var noofadult=0;
     var noofchild=0;
     var obj = {};
     var items = [];
     $( "#hotel_search" ).submit(function( e ) {  
   
     $('.pax_container').each(function(){
     if($(this).hasClass("pax_container"))
     {
        room=$(this).attr("data");
        noofadult=$(this).parent().find(".adultlist_"+room+' .actives').text();  
        noofchild=$(this).parent().find(".childlist_"+room+' .actives').text(); 
       // obj[room]=[noofadult,noofchild];
   
       obj[room]={'adult':noofadult,'child':noofchild};
   
      // items.push({'adult':noofadult,'child':noofchild});
   
     }
     });
     $("#guest").val(JSON.stringify(obj));
     return ;
   });
   
   });
   
</script>

<script>

$(document).ready(function(){

  $("#button_add_rooms_all").click(function()
  {
     

     $('.arrows_addroom_all').toggleClass('fa-angle-up fa-angle-down');

  });


  $(".done").click(function()
  {
	 var childAgeJSON = [];
	 $('.child_age').each(function(){
		var object = {}; 
		object[$(this).attr('data-room')] = $(this).val();
		childAgeJSON.push(object);
	 });
	 $('#child_age_json').val(JSON.stringify(childAgeJSON));
     $('.arrows_addroom_all').toggleClass('fa-angle-up fa-angle-down');
  });

   
});
</script>
