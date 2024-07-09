/*$(document).ready(function(){
//alert("ready");
var curpage=$("#pagname").val();
$('li').removeClass('active');
$("#"+curpage).addClass("active");
$("#agentname").focus();
$(".editagent").css("display","none");
gettabledata();
});*/

	$(document).ready(function() {
		$('#upload').on('click', function() {
			//alert("ook");
			if($("#sortpicture").val()=="")
	{
		alert("Fill in the upload fields!!");
	}
	else
	{
	
			var file_data = $('#sortpicture').prop('files')[0];   
			var form_data = new FormData();                  
			form_data.append('file', file_data);
			$.ajax({
					//url: 'index.php?controller=AdminAgents&token=00fe12174c54d5aa7c80dec3a72e217e&fileUpload=1&id_agent='+$('#id_agent').val(), // point to server-side PHP script 
					url: 'mods_functions_manager_createagent.php?getmods=uploadimg&sortpicture="+$("#sortpicture").val()',
					dataType: 'text',  // what to expect back from the PHP script, if anything
					cache: false,
					contentType: false,
					processData: false,
					data: form_data,                         
					type: 'post',
					async: 'false',
					success: function(result){
						var obj = JSON.parse(result);
						alert(obj);
						if(obj.success){
						alert('File uploaded successfully.');
							$('#sortpicturehidden').val(obj.success);
							var sortph=$("#sortpicturehidden").val();
							alert(sortph);
						}else
							alert(obj.error); // display response from the PHP script, if any
					}
			});
			return false;
	}
			
		});
		
		});
		
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
	http.open("POST", "mods_functions_manager_createagent.php", true);
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
function save_agentdetails()
{
	//alert("ok");
	
			var services_offered = $('input:checkbox:checked.services_offered').map(function () {
      return this.value;
    }).get();
	
	alert(services_offered);
	alertify.set('notifier','position', 'bottom-right');
	var initial=alertify.success('Saving. Please wait..');
	var http = new XMLHttpRequest();
	var postdata="getmods=saveagent&agent_name="+$("#agent_name").val()+"&pan_number="+$("#pan_number").val()+"&address="+$("#address").val()+"&pin_code="+$("#pin_code").val()+"&city="+$("#city").val()+"&state="+$("#state").val()+"&country="+$("#country").val()+"&telephone_no="+$("#telephone_no").val()+"&mobile_no="+$("#mobile_no").val()+"&fax_no="+$("#fax_no").val()+"&email_id="+$("#email_id").val()+"&website="+$("#website").val()+"&username="+$("#username").val()+"&password="+$("#password").val()+"&desc="+$("#desc").val()+"&sortpicture="+$("#sortpicturehidden").val()+"&services_offered="+services_offered;
	alert(postdata);
	http.open("POST", "mods_functions_manager_createagent.php", true);
	http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	http.setRequestHeader("X-Requested-With", "XMLHttpRequest");
	http.onreadystatechange = function() {//Call a function when the state changes.
	if(http.readyState == 4 && http.status == 200) {
		alert(http.responseText);
		if(http.responseText==1)
		{
		initial.dismiss();
		  alertify.alert('Hello World!').setHeader('<i class="fa fa-database"></i> Information').set({transition:'zoom',message: 'Agent Added Successfully. <a href="ViewAgent.php">View</a>'}).setting({'closable':false}).show();
		//$("#datatable").dataTable().fnDestroy();
		//gettabledata();
		//$("form")[0].reset();
		$('#defaultForm').bootstrapValidator('resetForm', true);
		//window.location.reload();
		}
		
		else{
			alert("Unable to delete Agent. Please contact Administrator");
		}
	}
	}
	http.send(postdata);
}

function gettabledata()
{
	var http = new XMLHttpRequest();
	var postdata="getmods=gettabledatas";
	//alert(postdata);
	http.open("POST", "mods_functions_manager_createagent.php", true);
	http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	http.setRequestHeader("X-Requested-With", "XMLHttpRequest");
	http.onreadystatechange = function() {//Call a function when the state changes.
	if(http.readyState == 4 && http.status == 200) {
		//alert(http.responseText);
		$(".datatable tbody").html("");
			  $(".datatable tbody").html(http.responseText);
			  $('.datatable').dataTable({
						 "pagingType": "full_numbers",
						 "aaSorting": [[1,"desc"]]
						 
					});	
			  $('.datatable').each(function(){
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

function confirm_edit_agent(i)
{
	var http = new XMLHttpRequest();
	var postdata="getmods=getagentname&userid="+i;
	//var cusername='';
	//alert(postdata);
	http.open("POST", "mods_functions_manager_createagent.php", true);
	http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	http.setRequestHeader("X-Requested-With", "XMLHttpRequest");
	http.onreadystatechange = function() {//Call a function when the state changes.
	if(http.readyState == 4 && http.status == 200) {
		//cusername=http.responseText;
		var editconfirm=alertify.confirm('Hello World!').setHeader('<i class="fa fa-edit fa-lg" style="color:white;"></i><span style="color:#fff;">&nbsp;&nbsp;Confirmation</span>').set({transition:'zoom',message: 'Do you want to edit agent '+http.responseText+"?"}).setting({'closable':false}).set('onok', function(closeEvent){ alertify.success('Fetching data..');get_agentdetails_edit(i);} ).show();
		 $(".ajs-header").css("backgroundColor","#FBBC05");
	
	}
	}
	http.send(postdata);
	
	
}

function get_agentdetails_edit(id)
{
	
	
	
	var http = new XMLHttpRequest();
	var postdata="getmods=geteditagentdetailsdata&userid="+id;
	//alert(postdata);
	http.open("POST", "mods_functions_manager_createagent.php", true);
	http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	http.setRequestHeader("X-Requested-With", "XMLHttpRequest");
	http.onreadystatechange = function() {//Call a function when the state changes.
	if(http.readyState == 4 && http.status == 200) {
		//alert(http.responseText);
		$(".datalist").css("display","none");
		$(".editagent").css("display","block");
		 alertify.dismissAll();
		var formdata=http.responseText;
		$("#eid").val(formdata.split("||")[0]);
		$("#referenceid_e").val(formdata.split("||")[1]);
		$("#agentname_e").val(formdata.split("||")[2]);
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
		$("#agentusername_e").val(formdata.split("||")[21]);
		$("#alternateno_e").val(formdata.split("||")[22]);
		
		 $("html, body").animate({ scrollTop: 0 }, "slow");
		
		
		
	
	}
	}
	http.send(postdata);
}

function update_agentdetails()
{
	var http = new XMLHttpRequest();
	var postdata="getmods=updateagent&agentname="+$("#agentname_e").val()+"&registerno="+$("#registerno_e").val()+"&contactperson="+$("#contactperson_e").val()+"&landlineno="+$("#landlineno_e").val()+"&mobileno="+$("#mobileno_e").val()+"&fax="+$("#fax_e").val()+"&emailid="+$("#emailid_e").val()+"&websitelink="+$("#websitelink_e").val()+"&address="+$("#address_e").val()+"&city="+$("#city_e").val()+"&pincode="+$("#pincode_e").val()+"&state="+$("#state_e").val()+"&country="+$("#country_e").val()+"&pancardno="+$("#pancardno_e").val()+"&bankname="+$("#bankname_e").val()+"&accountno="+$("#accountno_e").val()+"&accholdername="+$("#accholdername_e").val()+"&ifsccode="+$("#ifsccode_e").val()+"&branchname="+$("#branchname_e").val()+"&username="+$("#agentusername_e").val()+"&imagename="+"&eid="+$("#eid").val()+"&alternateno="+$("#alternateno_e").val();
	alert(postdata);
	http.open("POST", "mods_functions_manager_createagent.php", true);
	http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	http.setRequestHeader("X-Requested-With", "XMLHttpRequest");
	http.onreadystatechange = function() {//Call a function when the state changes.
	if(http.readyState == 4 && http.status == 200) {
		alert(http.responseText);
		if(http.responseText!=0)
		{
			$("#datatable").dataTable().fnDestroy();
			gettabledata();
			 alertify.alert('Hello World!').setHeader('<i class="fa fa-database"></i> Information').set({transition:'zoom',message: 'Agent Updated Successfully.'}).setting({'closable':false}).show();
			$(".datalist").css("display","block");
			$(".editagent").css("display","none");
			
			
			$("#eid").val('');$("#referenceid_e").val('');$("#agentname_e").val('');$("#registerno_e").val('');$("#contactperson_e").val('');$("#landlineno_e").val('');$("#mobileno_e").val('');$("#fax_e").val('');$("#emailid_e").val('');$("#websitelink_e").val('');$("#address_e").val('');$("#city_e").val('');$("#pincode_e").val('');$("#state_e").val('');$("#country_e").val('');$("#pancardno_e").val('');$("#bankname_e").val('');$("#accountno_e").val('');$("#accholdername_e").val('');$("#ifsccode_e").val('');$("#branchname_e").val('');
			$("form")[0].reset();
		$('#defaultForm').bootstrapValidator('resetForm', true);
		}
		//window.location.reload();
	}
	}
	http.send(postdata);
}

function confirm_delete_agent(du)
{
	var http = new XMLHttpRequest();
	var postdata="getmods=getusername&userid="+du;
	//var cusername='';
	//alert(postdata);
	http.open("POST", "mods_functions_manager_createagent.php", true);
	http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	http.setRequestHeader("X-Requested-With", "XMLHttpRequest");
	http.onreadystatechange = function() {//Call a function when the state changes.
	if(http.readyState == 4 && http.status == 200) {
		//cusername=http.responseText;
		var deleteconfirm=alertify.confirm('Hello World!').setHeader('<i class="fa fa-times-circle fa-lg" style="color:white;"></i><span style="color:#fff;">&nbsp;&nbsp;Confirmation</span>').set({transition:'zoom',message: 'Do you want to delete agent '+http.responseText+"?"}).setting({'closable':false}).set('onok', function(closeEvent){ alertify.success('Fetching data..');delete_agentdetails(du);} ).show();
		 $(".ajs-header").css("backgroundColor","#EA4335"); 
	
	}
	}
	http.send(postdata);
	
	
}

function delete_agentdetails(d)
{

	var http = new XMLHttpRequest();
	var postdata="getmods=deleteagentdetailsdata&userid="+d;
	//alert(postdata);
	http.open("POST", "mods_functions_manager_createagent.php", true);
	http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	http.setRequestHeader("X-Requested-With", "XMLHttpRequest");
	http.onreadystatechange = function() {//Call a function when the state changes.
	if(http.readyState == 4 && http.status == 200) {
		//alert(http.responseText);
		if(http.responseText==0)
		{
			$("#datatable").dataTable().fnDestroy();
			gettabledata();
			alertify.dismissAll();
			 alertify.alert('Hello World!').setHeader('<i class="fa fa-database"></i> Information').set({transition:'zoom',message: 'Agent Deleted Successfully.'}).setting({'closable':false}).show();
		}
		else
		{
			alert("Unable to delete Agent. Please contact Administrator");
		}
		
		
	
	}
	}
	http.send(postdata);
}
function reload_form()
{
	window.location.reload();
}