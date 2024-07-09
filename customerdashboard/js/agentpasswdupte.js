function update_customerupdtepasswwd()
{
	//alert("ok");
	
	var editconfirm=alertify.confirm('Hello World!').setHeader('<i class="fa fa-edit fa-lg" style="color:white;"></i><span style="color:#fff;">&nbsp;&nbsp;Confirmation</span>').set({transition:'zoom',message: 'Do you want to change password?'}).setting({'closable':false}).set('onok', function(closeEvent){ alertify.success('Updating data..');update_confirm();} ).show();
	 $(".ajs-header").css("backgroundColor","#940105");
	 $(".ajs-ok").html("yes");
	  $(".ajs-cancel").html("No");
	
	
}

function update_confirm()
{
	var http = new XMLHttpRequest();
	var postdata="getmods=agentpaswdup&agentpaswd="+$("#password_e").val()+"&agentpaswdconfirm="+$("#confirmpassword_e").val()+"&agentid="+$("#agentid").val();
	//alert(postdata);
	http.open("POST", "mods_functions_manager_adashpasswdupdated.php", true);
	http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	http.setRequestHeader("X-Requested-With", "XMLHttpRequest");
	http.onreadystatechange = function() {//Call a function when the state changes.
	if(http.readyState == 4 && http.status == 200) {
		//alert(http.responseText);
		if(http.responseText!=0)
		{
			
			//alert("oks");
			/*$("#datatable").dataTable().fnDestroy();
			gettabledata();*/
		
			//alertify.alert('Hello World!').setHeader('<i class="fa fa-database"></i> Information <button type="button" class="close" onclick="Custombox.close();"></button>').set({transition:'zoom',message: 'Password changed sucessfully'}).setting({'closable':false}).show();
			alertify.alert('Hello World!').setHeader('<i class="fa fa-database"></i> Information  ' ).set({transition:'zoom',message: 'Password changed sucessfully. '}).setting({'closable':true}).show();
				$(".ajs-ok").html("ok");
				$(".ajs-header").css("backgroundColor","#940105");
			//$(".datalist").css("display","block");
			//$(".editagent").css("display","none");
			
			
		
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

function update_agent_details()
{
	var http = new XMLHttpRequest();
	var postdata="getmods=agentdetailsupdate&agentmob="+$("#mobno").val()+"&agentlandno="+$("#landno").val()+"&agentaltmobno="+$("#altmobno").val()+"&agentemail="+$("#email").val()+"&agentref="+$("#agentrefid").val();
	//alert(postdata);
	http.open("POST", "mods_functions_manager_adashpasswdupdated.php", true);
	http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	http.setRequestHeader("X-Requested-With", "XMLHttpRequest");
	http.onreadystatechange = function() {//Call a function when the state changes.
	if(http.readyState == 4 && http.status == 200) {
		//alert(http.responseText);
		alert("Agent Details Updated Successfully");
	}
	}
	http.send(postdata);
}