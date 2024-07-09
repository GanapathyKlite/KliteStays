function update_customerupdtepasswwd()
{


	$('#confirm_div').modal({
      backdrop: 'static',	
      keyboard: false
    })

    .one('click', '#ok', function(e) {
     update_confirm();
    });
	/*alertify.confirm('Hello World!').setHeader('<i class="fa fa-edit fa-lg" style="color:white;"></i><span style="color:#fff;">&nbsp;&nbsp;Confirmation</span>').set({transition:'zoom',message: 'Do you want to change password?'}).setting({'closable':false}).set('onok', function(closeEvent){ alertify.success('Updating data..');update_confirm();} ).show();
	 $(".ajs-header").css("backgroundColor","#940105");*/
	
	
}

function update_confirm()
{

	var http = new XMLHttpRequest();
	var postdata="getmods=customerpaswdup&customerpaswd="+$("#password_e").val()+"&customerpaswdconfirm="+$("#confirmpassword_e").val()+"&customerid="+$("#customerid").val();
	//alert(postdata);
	http.open("POST", "mods_functions_manager_adashpasswdupdated.php", true);
	http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	http.setRequestHeader("X-Requested-With", "XMLHttpRequest");
	http.onreadystatechange = function() {//Call a function when the state changes.
	if(http.readyState == 4 && http.status == 200) {
		if(http.responseText==1)
		{
			
			$('#sucess_div').modal({
		      backdrop: 'static',
		      keyboard: false
		    })
		    .one('click', '#succ', function(e) {
		    window.location.reload();
		   // window.location.href = "index.php";
		    });
			//alert("oks");
			/*$("#datatable").dataTable().fnDestroy();
			gettabledata();*/
			/*$(".ajs-header").css("backgroundColor","#940105");
			alertify.alert('Hello World!').setHeader('<i class="fa fa-database"></i> Information').set({transition:'zoom',message: 'Password Reset Successfully.'}).setting({'closable':true}).show();*/
			//$(".datalist").css("display","block");
			//$(".editcustomer").css("display","none");
			
			
		
		//sendmail();
		}
		//alert("faliled");
		//window.location.reload();
		
	}
	}
	http.send(postdata);
}
function reload_form()
{
	window.location.reload();
}

function sendmail()
{
	var http = new XMLHttpRequest();
	var postdata="password="+$("#password_e").val()+"&username="+$("#usernameval").val()+"&email="+$("#useremailval").val();
//alert(postdata);
	http.open("POST", "passwordupdatemail.php", true);
	http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	http.setRequestHeader("X-Requested-With", "XMLHttpRequest");
	http.onreadystatechange = function() {//Call a function when the state changes.
	if(http.readyState == 4 && http.status == 200) {
		//alert(http.responseText);
		 alertify.dismissAll();
			$("#password_e").val('');$("#confirmpassword_e").val('');
			$("form")[0].reset();
		$('#defaultForm').bootstrapValidator('resetForm', true);
		
			alertify.alert('Hello World!').setHeader('<i class="fa fa-database"></i> Information').set({transition:'zoom',message: 'Mail sent Successfully.'}).setting({'closable':false}).show();
		
	}
	}
	http.send(postdata);
}

function update_customer_details()
{
	var http = new XMLHttpRequest();
	var postdata="getmods=customerdetailsupdate&customermob="+$("#mobno").val()+"&customerlandno="+$("#landno").val()+"&customeraltmobno="+$("#altmobno").val()+"&customeremail="+$("#email").val()+"&customerref="+$("#customerrefid").val();
	//alert(postdata);
	http.open("POST", "mods_functions_manager_adashpasswdupdated.php", true);
	http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	http.setRequestHeader("X-Requested-With", "XMLHttpRequest");
	http.onreadystatechange = function() {//Call a function when the state changes.
	if(http.readyState == 4 && http.status == 200) {
		//alert(http.responseText);
		alert("customer Details Updated Successfully");
	}
	}
	http.send(postdata);
}