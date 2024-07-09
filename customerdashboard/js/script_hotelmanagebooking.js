$(document).ready(function(){
	$("#fromdate").datepicker({dateFormat: 'dd-mm-yy'});
	$("#todate").datepicker({dateFormat: 'dd-mm-yy'});
	get_modules_table();
});

function get_modules_table()
{
	$("#ajaxloader").css("display","block");
	var http = new XMLHttpRequest();
	var postdata="getmods=fetchreporthotel&manage=1";
	http.open("POST", "mods_functions_manager_viewbooking.php", true);
	http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	http.setRequestHeader("X-Requested-With", "XMLHttpRequest");
	http.onreadystatechange = function() {//Call a function when the state changes.
		if(http.readyState == 4 && http.status == 200) {
			$("#ajaxloader").css("display","none");
			$(".datatable tbody").html("");
			$(".datatable tbody").html(http.responseText);
			$('.datatable').dataTable({
					 "pagingType": "full_numbers",
					 //"aaSorting": [[1,"desc"]]
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

$("#searchreport").click(function(){
	if($("#fromdate").val() && $("#todate").val()){}
	else return;
	$("#datatable").dataTable().fnDestroy();
	$("#ajaxloader").css("display","block");
	var http = new XMLHttpRequest();
	var postdata="getmods=fetchreporthotel&manage=1&fromdate="+$("#fromdate").val()+"&todate="+$("#todate").val();
	//alert(postdata);
	http.open("POST", "mods_functions_manager_viewbooking.php", true);
	http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	http.setRequestHeader("X-Requested-With", "XMLHttpRequest");
	http.onreadystatechange = function() {//Call a function when the state changes.
		if(http.readyState == 4 && http.status == 200) {
			$("#ajaxloader").css("display","none");
			$(".datatable tbody").html("");
			$(".datatable tbody").html(http.responseText);
			$('.datatable').dataTable({
					 "pagingType": "full_numbers",
					 //"aaSorting": [[3,"desc"]]
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
});
function cancelBooking(id){
	$.confirm({
		title: '',
		content: 'Are you sure to cancel this booking?',
		theme: 'supervan',
		buttons: {
			ok: function(){
				$.ajax({
					url: 'hotelmanagebooking.php',
					data: 'id='+id,
					type: 'post',
					async: 'false',
					success: function(result){
						var data = JSON.parse(result);
						if(data.status == 'success'){
							$.alert({
								title: '',
								content: data.message,
								theme: 'supervan',
								backgroundDismiss: true,
								onDestroy: function () {
									window.location = 'hotelmanagebooking.php';
								},
								buttons: {ok: function(){}}
							});
						}else if(data.status == 'fail'){
							$.alert({
								title: '',
								content: data.message,
								theme: 'supervan',
								buttons: {ok: function(){}}
							});
						}
					}
				});
			},
			cancel: function(){}
		}
	});
	return false;
}