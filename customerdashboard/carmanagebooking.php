<?php 
	error_reporting(0);
	if(isset($_POST['id']) && !empty($_POST['id'])){
		include('../include/database/config.php');
		if($_POST['type']=='c'){
				$res = $database->exec("update ps_car_booking set is_cancel=1 where id=".(int)$_POST['id']);
		}elseif($_POST['type']=='ct')
		{
			$res = $database->exec("update ps_car_tailor_booking set is_cancel=1 where id_car_tailor_booking=".(int)$_POST['id']);
		}else
		{
			$res = $database->exec("update ps_carbooking_oneway set is_cancel=1 where id_car_oneway=".(int)$_POST['id']);
		}
		

		
		if($res) echo json_encode(array('status' => 'success', 'message' => 'Cancelled Request Successful.'));
		else echo json_encode(array('status' => 'fail', 'message' => 'Error occured.'));
		exit;
	}
	include('include/header.php');
?>	
<link href="plugins/datatables/css/jquery.datatables.min.css" rel="stylesheet" type="text/css"/>	
<link href="plugins/datatables/css/jquery.datatables_themeroller.css" rel="stylesheet" type="text/css"/>	
<link rel="stylesheet" href="css/datatables.css" id="toggleCSS" />
 
<div class="viewbook">
	<div class="container contain">
		<form class="form-inline top_form" onsubmit="javascript:return false;">
			<div class="form-group">
				<label class="from">From</label>
				<input type="text" class="form-control" id="fromdate" name="fromdate" placeholder="dd-mm-yyyy" autocomplete="off" required >
			</div>
			<div class="form-group">
				<label>To</label>
				<input type="text" class="form-control" id="todate" name="todate" placeholder="dd-mm-yyyy" autocomplete="off" required >
			</div>
			<button id="searchreport" class="btn search_button"><i class="fa fa-search"></i>&nbsp;Search</button>
			<!--<div class="form-group pdf_icon" >
				<a href="#"  onclick="javascript:downloadReport('pdf');"><img src="images/pdf.png" title="Download Invoice" alt="" >Export</a>
			</div>
			<div class="form-group pdf_icon">
				<a href="#"  onclick="javascript:downloadReport('excel');"><img src="images/excel.png" title="Download Invoice" alt="" >Export</a>
			</div>-->
		</form>
		<div class="table-responsive">
			<table id="datatable" class="table  table-hover profile datatable tablestyle">
				<thead class="thead-inverse thead_inverse">
					<tr>
						<th>Sl.No.</th>
						<th>Booking Date</th>
						<th>Booking ID</th>
						<th>Customer</th>
						<th>Contact</th>
						<th>Arrival City</th>
						<th>Departure City</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody id="tablecontent"></tbody>
			</table>
		</div>
		<div class="clearfix"> </div>
	</div>
</div>

<script src="js/jquery.dataTables.min.js"></script>
<script src="js/datatables.js"></script>
<script type="text/javascript" src="js/jquery.datepick.js"></script>
<link rel="stylesheet" type="text/css" href="css/jquery.datepick.css">
<link rel="stylesheet" href="css/jquery-ui.css">
<script src="js/jquery-ui.js"></script>
<script src="js/script_carmanagebooking.js"></script>
<script src="<?=$root_dir;?>js/script.js"></script>
<!--Start of Zopim Live Chat Script -->
<script type="text/javascript">
window.$zopim||(function(d,s){var z=$zopim=function(c){z._.push(c)},$=z.s=
d.createElement(s),e=d.getElementsByTagName(s)[0];z.set=function(o){z.set.
_.push(o)};z._=[];z.set._=[];$.async=!0;$.setAttribute('charset','utf-8');
$.src='//v2.zopim.com/?1QkEwkuxE0ZXXPAx1k46Ymsdrgigmd51';z.t=+new Date;$.
type='text/javascript';e.parentNode.insertBefore($,e)})(document,'script');

function downloadReport(type){
	if(type == 'excel')
		var url = 'excel-report/carviewBooking.php?';
	else if(type == 'pdf')
		var url = 'pdf1/code/carviewBooking.php?';
		
	window.open(url+"id=<?php echo (isset($_SESSION['authtnid']) ? $_SESSION['authtnid'] : ''); ?>&cancel=0&pending=1&from="+$('#fromdate').val()+"&to="+$('#todate').val(), "_blank");
	return false;
}
</script>
<?php include('include/footer.php'); ?>