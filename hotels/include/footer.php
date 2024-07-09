<script type="text/javascript">
	$(document).ready(function(e) {
		$( "#hotels_month" ).datepicker({
			changeMonth: true,
			changeYear: true,
			numberOfMonths: 2,
			dateFormat: "dd-mm-yy",
			minDate: 0,
			onSelect: function( selectedDate ) {
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
	});
</script>
</body>
</html>
