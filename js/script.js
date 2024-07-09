$(document).keypress(function(event){
	    if(event.keyCode === 13){
          authenticate_user();
          }
          event.cancelBubble = true;
             if (event.stopPropagation) event.stopPropagation();
         });
function authenticate_user()
{
	//alert("test");//$("#loginresponse").css("display","none");
	var http = new XMLHttpRequest();
	var postdata="getmods=authenticateuser&username="+$("#username").val()+"&password="+$("#password").val()+"&search="+$("#search").val();
	//alert(postdata);
	http.open("POST", $("#url").val()+"mods_functions_manager_login.php", true);
	http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	http.setRequestHeader("X-Requested-With", "XMLHttpRequest");
	http.onreadystatechange = function() {//Call a function when the state changes.
	if(http.readyState == 4 && http.status == 200) {	
var respos=JSON.parse(http.responseText);

		if(respos.status=='1')
		{
			$("#invalidpassword").fadeOut();
			$("#correctpassword").css("display","block");
			setTimeout(function(){window.location.reload();},1000);
		}
		else
		{
			$("#correctpassword").css("display","none");
			$("#invalidpassword").css("display","block"); 
			setTimeout(function(){
			  $('#invalidpassword').fadeOut();
			}, 1000);
			//alert("ready");
		}
	}
	}
	http.send(postdata);
}

$(document).ready(function(){
	/* This code is executed after the DOM has been completely loaded */

	/* Changing thedefault easing effect - will affect the slideUp/slideDown methods: */
	$.easing.def = "easeOutBounce";

	/* Binding a click event handler to the links: */
	$('li.button a').click(function(e){
	
		/* Finding the drop down list that corresponds to the current section: */
		var dropDown = $(this).parent().next();
		
		/* Closing all other drop down sections, except the current one */
		$('.dropdown').not(dropDown).slideUp('slow');
		dropDown.slideToggle('slow');
		
		/* Preventing the default event (which would be to navigate the browser to the link's address) */
		e.preventDefault();
	})
	
});

function showpr()
{
	
	
	
	alertify.prompt('Type in your registered email address', '', 
    function(evt, value){ 
	if(validateEmail(value)==true)
	{
		
		 $(".ajs-header").css("backgroundColor","#fff");
		alertify.alert('Hello World!').setHeader('<i class="fa fa-database"></i> Information ').set({transition:'zoom',message: 'Sending Mail. Please wait..'}).setting({'closable':false}).show();
		var http = new XMLHttpRequest();
		var postdata="email="+value;
		http.open("POST", "passwordrecoverymail.php", true);
		http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		http.setRequestHeader("X-Requested-With", "XMLHttpRequest");
		http.onreadystatechange = function() {//Call a function when the state changes.
		if(http.readyState == 4 && http.status == 200) {
		if(http.responseText==1)
		{
		alertify.dismissAll();
			  $(".ajs-header").css("backgroundColor","#fff");
			 alertify.alert('Hello World!').setHeader('<i class="fa fa-database"></i> Information').set({transition:'zoom',message: 'Mail sent Successfully.'}).setting({'closable':false}).show();
		}
		
	else if(http.responseText=="notexist"){
		//alert("Mail Id Not Registered Please contact Administrator");
		alertify.alert('Hello World!').setHeader('<i class="fa fa-database"></i> Information').set({transition:'zoom',message: 'Mail Id Not Registered Please contact Administrator'}).setting({'closable':false}).show();
		
	}
		
		else{
		alertify.alert('Hello World!').setHeader('<i class="fa fa-database"></i> Information').set({transition:'zoom',message: 'Mail Not sent Successfully. Please contact Administrator'}).setting({'closable':false}).show();

		}
			 
			
		}
		}
		http.send(postdata);
	}
	else
	{
		 alertify.dismissAll();setTimeout(function(){showpr();},1000);
	}
	}).setHeader('<i class="fa fa-lock"></i> Recover Password');
	$(".ajs-input").css("width","100%");
	 $(".ajs-header").css("backgroundColor","#fff");
	  $("button.ajs-header").css("color","##000");
	   
	      
    $(".ajs-ok").addClass("btn_for_sendq hvr-shutter-in-vertical ");
       $(".ajs-cancel").addClass("btn_for_sendq hvr-shutter-in-vertical ");
   

    
}
function validateEmail(email) {
    var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
}
function showCancelTicket(b_id,partial)
{
	
	var seats = $('#'+b_id+'_seats').val();
	if(partial){
		$('.booked_seats').text(seats);
		$('input[name=booking_id]').val(b_id);
		//$('#showCancelTicket').html('');
		var content = $('#showCancelTicket').html();
		$.fancybox({
			'content': content,
			'autoSize': true,
			'padding': 20,
			'margin': 0,
			'minWidth': 400,
			'scrolling': 'no'
		});
	}else
		validateCancellation(b_id,seats,partial);
}
function validateCancellation(b_id,value,partial){
	if(partial){
		var b_id = $('input[name=booking_id]').val();//$('#booking_id').val();
		var value = $('.fancybox-inner input[name=to_cancel_seats]').val();
	}
	if(value && value != ''){
		$.ajax({
			url: 'ManageBooking.php',
			data: 'b_id='+b_id+'&value='+value+'&partial='+partial,                         
			type: 'post',
			async: 'false',
			success: function(result){
				var data = JSON.parse(result);
				if(data.status == 'success'){
					alert('Your ticket is successfully cancelled.');
					window.location = 'ManageBooking.php';
				}else if(data.status == 'fail'){
					alert('Ticket cancellation failed.');
					$.fancybox.close();
				}else if(data.error == 'Invalid Seats'){
					alert('Invalid Seat Nos. given');
				}
			}
		});
	}else
		$('input[name=to_cancel_seats]').focus();
}
