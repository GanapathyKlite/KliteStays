/*$(document).ready(function(){

//alert("ready");

var curpage=$("#pagname").val();

$('li').removeClass('active');

$("#"+curpage).addClass("active");

$("#customername").focus();

$(".editcustomer").css("display","none");

gettabledata();

});*/






/*

$("#upload").click(function()

{

alert("ready");

	

if($("#sortpicture").val()=="")

{

alert("Fill in all the fields!!");

}

else

{

//alert($("#modulesname").val());

var http = new XMLHttpRequest();

	

var file_data = $('#sortpicture').prop('files')[0];   

	var form_data = new FormData();                  

	form_data.append('file', file_data);

//var postdata="getmods=uploadimg&sortpicture="+$("#sortpicture").val();

var postdata="getmods=uploadimg&sortpicture="+form_data;

alert(postdata);

http.open("POST", "mods_functions_manager_createcustomer.php", true);

http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

http.setRequestHeader("X-Requested-With", "XMLHttpRequest");

http.onreadystatechange = function() {//Call a function when the state changes.

if(http.readyState == 4 && http.status == 200) {

alert(http.responseText);

if(http.responseText=="success")

{

	alert("Slider data saved Successfully");

	/*$("#datatable").dataTable().fnDestroy();

	get_modules_table();

	$("#hyperlink").val('');

	//$("#hyperlink_e").val('');

	$("#eid").val('');

}

else 

{

	alert("Slider data already exists!!");

}

	

	

}

}

http.send(postdata);

}

});





*/


var root_dir = $("#root_dir").val();
function reloadCaptcha() {
	jQuery('#captcha_image').prop('src', root_dir + 'include/securimage/securimage_show.php?sid=' + Math.random());
}


function save_customerdetails() {


	alertify.set('notifier', 'position', 'bottom-right');



	var http = new XMLHttpRequest();

	//var postdata="getmods=savecustomer&first_name="+$("#first_name").val()+"&last_name="+$("#last_name").val()+"&address="+$("#address").val()+"&pin_code="+$("#pin_code").val()+"&city="+$("#city").val()+"&state="+$("#state").val()+"&mobile_no="+$("#mobile_no").val()+"&email_id="+$("#email_id").val()+"&username="+$("#email_id").val()+"&password="+$("#rpassword").val()+"&captcha_code="+$("#captcha_code").val()+"&pan_no="+$("#pan_no").val()+"&gst_no="+$("#gst_no").val();

	var postdata = "getmods=savecustomer&email=" + $("#email_id").val() + "&mobile=" + $("#mobile_no").val() + "&password=" + $("#rpassword").val() + "&captcha_code=" + $("#captcha_code").val();

	//	http.open("POST", "mods_functions_manager_createcustomer.php", true);
	http.open("POST", "mods_functions_manager_registercustomer.php", true);
	http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

	http.setRequestHeader("X-Requested-With", "XMLHttpRequest");

	http.onreadystatechange = function () {//Call a function when the state changes.

		if (http.readyState == 4 && http.status == 200) {
			console.log("Output Response:");
			console.log(http.responseText);
			var text = jQuery.parseJSON(http.responseText);


			
			/*if(text.msg=="customer")
			{
	
				//alert("customer name already Exists");.
				$.confirm({
					title: '',
					content: 'customer name already Exists!',
					type: 'red',
					typeAnimated: true,
					buttons: {
						close: function () {
						}
					}
				});
				reloadCaptcha();
				$(".loader_spin").css("display","none");
			}*/

			if (text.msg == "mobile") {

				//alert("Mobile number already Exists");
				$.confirm({
					title: '',
					content: 'Mobile number already Exists!',
					type: 'red',
					typeAnimated: true,
					buttons: {
						close: function () {
						}
					}
				});
				reloadCaptcha();
				$(".loader_spin").css("display", "none");

			}

			else if (text.msg == "email") {

				//alert("Email already Exists");
				$.confirm({
					title: '',
					content: 'Email already Exists!',
					type: 'red',
					typeAnimated: true,
					buttons: {
						close: function () {
						}
					}
				});
				reloadCaptcha();
				$(".loader_spin").css("display", "none");


			}

			/*else if(text.msg=="usern")
	
			{
	
				//alert("username already Exists");
				$.confirm({
					title: '',
					content: 'Username already Exists!',
					type: 'red',
					typeAnimated: true,
					buttons: {
						close: function () {
						}
					}
				});
	
			}
			else if(text.msg=="captcha")
			{
				//alert("username already Exists");
				$.confirm({
					title: '',
					content: 'Incorrect Captcha Code',
					type: 'red',
					typeAnimated: true,
					buttons: {
						close: function () {
						}
					}
				});
				 reloadCaptcha();
				$(".loader_spin").css("display","none");
				$("#captcha_code").val("");
			}*/



			else if (text.msg == 'success') {
				$(".loader_spin").css("display", "none");

				var initial = alertify.success('Saving. Please wait..');
				initial.dismiss();

				//alertify.alert('Hello World!').setHeader('<i class="fa fa-database"></i> Information  <button type="button" class="close" onclick="Custombox.close();"></button>' ).set({transition:'zoom',message: 'Your Details submited successfully. '}).setting({'closable':true}).show();
				$.confirm({
					title: 'Conratz!',
					content: 'Your details has been submited successfully.',
					type: 'green',
					typeAnimated: true,
					buttons: {
						ok: function () {
							window.location.reload();
						}
					}
				});

				//$("#datatable").dataTable().fnDestroy();

				//gettabledata();

				$("form")[0].reset();
				reloadCaptcha();
				$(".loader_spin").css("display", "none");

				$('#registrationform').validator.resetForm();

				//window.location.reload();

			}



			else {

				alert("Unable to delete customer. Please contact Administrator");

			}

		}

	}

	http.send(postdata);

}







function update_customer_details() {



	var services_offered = $('input:checkbox:checked.services_offered').map(function () {

		return this.value;

	}).get();

	var http = new XMLHttpRequest();

	//	var postdata="getmods=customerdetailsupdate&customermob="+$("#mobno").val()+"&customerlandno="+$("#landno").val()+"&customeraltmobno="+$("#altmobno").val()+"&customeremail="+$("#email").val()+"&customerref="+$("#customerrefid").val();

	var postdata = "getmods=customerdetailsupdate&first_name=" + $("#first_name").val() + "&last_name=" + $("#last_name").val() + "&pan_no=" + $("#pan_no").val() + "&address=" + $("#address").val() + "&pin_code=" + $("#pin_code").val() + "&city=" + $("#city").val() + "&state=" + $("#state").val() + "&mobile_no=" + $("#mobile_no").val() + "&email_id=" + $("#email_id").val() + "&customerrefid=" + $("#customerrefid").val() + "&id_customer=" + $("#id_customer").val() + "&id_country=" + $("#country").val();



	http.open("POST", root_dir + "mods_functions_manager_createcustomer.php", true);

	http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

	http.setRequestHeader("X-Requested-With", "XMLHttpRequest");

	http.onreadystatechange = function () {//Call a function when the state changes.

		if (http.readyState == 4 && http.status == 200) {


			if (http.responseText == 1) {





				//initial.dismiss();

				alertify.alert('Hello World!').setHeader('<i class="fa fa-database"></i> Information').set({ transition: 'zoom', message: 'Details updated Successfully.' }).setting({ 'closable': false }).show();

				//$("#datatable").dataTable().fnDestroy();

				//gettabledata();

				//$("form")[0].reset();

				$('#defaultForm').bootstrapValidator('resetForm', true);

				//window.location.reload();

			}



			else {

				alert("Unable to update customer. Please contact Administrator");

			}





		}

	}

	http.send(postdata);

}


function update_customerupdtepasswwd() {


	var http = new XMLHttpRequest();

	//var postdata="getmods=customerdetailsupdate&customermob="+$("#mobno").val()+"&customerlandno="+$("#landno").val()+"&customeraltmobno="+$("#altmobno").val()+"&customeremail="+$("#email").val()+"&customerref="+$("#customerrefid").val();

	var postdata = "getmods=updatepassword&password=" + $("#password_e").val() + "&customerrefid=" + $("#customerrefid").val();



	http.open("POST", root_dir + "mods_functions_manager_createcustomer.php", true);

	http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

	http.setRequestHeader("X-Requested-With", "XMLHttpRequest");

	http.onreadystatechange = function () {//Call a function when the state changes.

		if (http.readyState == 4 && http.status == 200) {


			if (http.responseText == 1) {


				//initial.dismiss();
				alertify.alert('Hello World!').setHeader('<i class="fa fa-database"></i> Information').set({ transition: 'zoom', message: 'Password updated Successfully.' }).setting({ 'closable': false }).show();
				//alertify.alert('Hello World!').setHeader('<i class="fa fa-database"></i> Information').set({transition:'zoom',message: 'Password updated Successfully.'}).setting({'closable':false}).show();

				//$("#datatable").dataTable().fnDestroy();

				//gettabledata();

				$("form")[0].reset();

				//$('#defaultForm').bootstrapValidator('resetForm', true);

				//setTimeout(function(){window.location = "index.php";}, 2000);

			}



			else {

				alert("Unable to update customer. Please contact Administrator");

			}





		}

	}

	http.send(postdata);

}

function gettabledata() {

	var http = new XMLHttpRequest();

	var postdata = "getmods=gettabledatas";

	//alert(postdata);

	http.open("POST", "mods_functions_manager_createcustomer.php", true);

	http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

	http.setRequestHeader("X-Requested-With", "XMLHttpRequest");

	http.onreadystatechange = function () {//Call a function when the state changes.

		if (http.readyState == 4 && http.status == 200) {

			//alert(http.responseText);

			$(".datatable tbody").html("");

			$(".datatable tbody").html(http.responseText);

			$('.datatable').dataTable({

				"pagingType": "full_numbers",

				"aaSorting": [[1, "desc"]]



			});

			$('.datatable').each(function () {

				var datatable = $(this);

				// SEARCH - Add the placeholder for Search and Turn this into in-line form control

				var search_input = datatable.closest('.dataTables_wrapper').find('div[id$=_filter] input');

				search_input.attr('placeholder', 'Search');

				search_input.addClass('form-control input-sm');

				// LENGTH - Inline-Form control

				var length_sel = datatable.closest('.dataTables_wrapper').find('div[id$=_length] select');

				length_sel.addClass('form-control input-sm');

			});

			/*$(".datatable tbody tr").live('click',function(event) {

				$(".datatable tbody tr").removeClass('row_selected');        

				$(this).addClass('row_selected');

			});*/

			$('body').delegate('.datatable tbody tr', "click", function () {

				if ($(this).hasClass('row_selected')) $(this).removeClass('row_selected');

				else {

					$(this).siblings('.row_selected').removeClass('row_selected');

					$(this).addClass('row_selected');



				}

			});



		}

	}

	http.send(postdata);

}



function confirm_edit_customer(i) {

	var http = new XMLHttpRequest();

	var postdata = "getmods=getcustomername&userid=" + i;

	//var cusername='';

	//alert(postdata);

	http.open("POST", "mods_functions_manager_createcustomer.php", true);

	http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

	http.setRequestHeader("X-Requested-With", "XMLHttpRequest");

	http.onreadystatechange = function () {//Call a function when the state changes.

		if (http.readyState == 4 && http.status == 200) {

			//cusername=http.responseText;

			var editconfirm = alertify.confirm('Hello World!').setHeader('<i class="fa fa-edit fa-lg" sty	', function (closeEvent) { alertify.success('Fetching data..'); get_customerdetails_edit(i); }).show();

			$(".ajs-header").css("backgroundColor", "#FBBC05");



		}

	}

	http.send(postdata);





}



function get_customerdetails_edit(id) {







	var http = new XMLHttpRequest();

	var postdata = "getmods=geteditcustomerdetailsdata&userid=" + id;

	//alert(postdata);

	http.open("POST", "mods_functions_manager_createcustomer.php", true);

	http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

	http.setRequestHeader("X-Requested-With", "XMLHttpRequest");

	http.onreadystatechange = function () {//Call a function when the state changes.

		if (http.readyState == 4 && http.status == 200) {

			//alert(http.responseText);

			$(".datalist").css("display", "none");

			$(".editcustomer").css("display", "block");

			alertify.dismissAll();

			var formdata = http.responseText;

			$("#eid").val(formdata.split("||")[0]);

			$("#referenceid_e").val(formdata.split("||")[1]);

			$("#customername_e").val(formdata.split("||")[2]);

			$("#registerno_e").val(formdata.split("||")[3]);

			$("#contactperson_e").val(formdata.split("||")[4]);

			$("#landlineno_e").val(formdata.split("||")[5]);

			$("#mobileno_e").val(formdata.split("||")[6]);

			$("#fax_e").val(formdata.split("||")[7]);

			$("#emailid_e").val(formdata.split("||")[8]);

			$("#websitelink_e").val(formdata.split("||")[9]);

			$("#address_e").val(formdata.split("||")[10]);

			$("#city_e").val(formdata.split("||")[11]);

			$("#pincode_e").val(formdata.split("||")[12]);

			$("#state_e").val(formdata.split("||")[13]);

			$("#country_e").val(formdata.split("||")[14]);

			$("#pancardno_e").val(formdata.split("||")[15]);

			$("#bankname_e").val(formdata.split("||")[16]);

			$("#accountno_e").val(formdata.split("||")[17]);

			$("#accholdername_e").val(formdata.split("||")[18]);

			$("#ifsccode_e").val(formdata.split("||")[19]);

			$("#branchname_e").val(formdata.split("||")[20]);

			$("#customerusername_e").val(formdata.split("||")[21]);

			$("#alternateno_e").val(formdata.split("||")[22]);



			$("html, body").animate({ scrollTop: 0 }, "slow");









		}

	}

	http.send(postdata);

}



function update_customerdetails() {

	var http = new XMLHttpRequest();

	var postdata = "getmods=updatecustomer&customername=" + $("#customername_e").val() + "&registerno=" + $("#registerno_e").val() + "&contactperson=" + $("#contactperson_e").val() + "&landlineno=" + $("#landlineno_e").val() + "&mobileno=" + $("#mobileno_e").val() + "&fax=" + $("#fax_e").val() + "&emailid=" + $("#emailid_e").val() + "&websitelink=" + $("#websitelink_e").val() + "&address=" + $("#address_e").val() + "&city=" + $("#city_e").val() + "&pincode=" + $("#pincode_e").val() + "&state=" + $("#state_e").val() + "&country=" + $("#country_e").val() + "&pancardno=" + $("#pancardno_e").val() + "&bankname=" + $("#bankname_e").val() + "&accountno=" + $("#accountno_e").val() + "&accholdername=" + $("#accholdername_e").val() + "&ifsccode=" + $("#ifsccode_e").val() + "&branchname=" + $("#branchname_e").val() + "&username=" + $("#customerusername_e").val() + "&imagename=" + "&eid=" + $("#eid").val() + "&alternateno=" + $("#alternateno_e").val();

	alert(postdata);

	http.open("POST", "mods_functions_manager_createcustomer.php", true);

	http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

	http.setRequestHeader("X-Requested-With", "XMLHttpRequest");

	http.onreadystatechange = function () {//Call a function when the state changes.

		if (http.readyState == 4 && http.status == 200) {

			alert(http.responseText);

			if (http.responseText != 0) {

				$("#datatable").dataTable().fnDestroy();

				gettabledata();

				alertify.alert('Hello World!').setHeader('<i class="fa fa-database"></i> Information').set({ transition: 'zoom', message: 'customer Updated Successfully.' }).setting({ 'closable': false }).show();

				$(".datalist").css("display", "block");

				$(".editcustomer").css("display", "none");





				$("#eid").val(''); $("#referenceid_e").val(''); $("#customername_e").val(''); $("#registerno_e").val(''); $("#contactperson_e").val(''); $("#landlineno_e").val(''); $("#mobileno_e").val(''); $("#fax_e").val(''); $("#emailid_e").val(''); $("#websitelink_e").val(''); $("#address_e").val(''); $("#city_e").val(''); $("#pincode_e").val(''); $("#state_e").val(''); $("#country_e").val(''); $("#pancardno_e").val(''); $("#bankname_e").val(''); $("#accountno_e").val(''); $("#accholdername_e").val(''); $("#ifsccode_e").val(''); $("#branchname_e").val('');

				$("form")[0].reset();

				$('#defaultForm').bootstrapValidator('resetForm', true);

			}

			//window.location.reload();

		}

	}

	http.send(postdata);

}



function confirm_delete_customer(du) {

	var http = new XMLHttpRequest();

	var postdata = "getmods=getusername&userid=" + du;

	//var cusername='';

	//alert(postdata);

	http.open("POST", "mods_functions_manager_createcustomer.php", true);

	http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

	http.setRequestHeader("X-Requested-With", "XMLHttpRequest");

	http.onreadystatechange = function () {//Call a function when the state changes.

		if (http.readyState == 4 && http.status == 200) {

			//cusername=http.responseText;

			var deleteconfirm = alertify.confirm('Hello World!').setHeader('<i class="fa fa-times-circle fa-lg" style="color:white;"></i><span style="color:#fff;">&nbsp;&nbsp;Confirmation</span>').set({ transition: 'zoom', message: 'Do you want to delete customer ' + http.responseText + "?" }).setting({ 'closable': false }).set('onok', function (closeEvent) { alertify.success('Fetching data..'); delete_customerdetails(du); }).show();

			$(".ajs-header").css("backgroundColor", "#EA4335");



		}

	}

	http.send(postdata);





}



function delete_customerdetails(d) {



	var http = new XMLHttpRequest();

	var postdata = "getmods=deletecustomerdetailsdata&userid=" + d;

	//alert(postdata);

	http.open("POST", "mods_functions_manager_createcustomer.php", true);

	http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

	http.setRequestHeader("X-Requested-With", "XMLHttpRequest");

	http.onreadystatechange = function () {//Call a function when the state changes.

		if (http.readyState == 4 && http.status == 200) {

			//alert(http.responseText);

			if (http.responseText == 0) {

				$("#datatable").dataTable().fnDestroy();

				gettabledata();

				alertify.dismissAll();

				alertify.alert('Hello World!').setHeader('<i class="fa fa-database"></i> Information').set({ transition: 'zoom', message: 'customer Deleted Successfully.' }).setting({ 'closable': false }).show();

			}

			else {

				alert("Unable to delete customer. Please contact Administrator");

			}







		}

	}

	http.send(postdata);

}

function reload_form() {

	$('#defaultForm')[0].reset();

}