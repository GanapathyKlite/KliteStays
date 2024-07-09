

<!DOCTYPE html>

<html>
<link href="hotel/css/jquery.fileuploader.css" media="all" rel="stylesheet">
<link href="hotel/css/jquery.fileuploader-theme-onebutton.css" media="all" rel="stylesheet">
		<!-- js -->
		<script src="https://code.jquery.com/jquery-3.1.1.min.js" crossorigin="anonymous"></script>
		<script src="hotel/js/fileuploader/jquery.fileuploader.min1.js" type="text/javascript"></script>
		<script>
			$(document).ready(function() {
	
	// enable fileuploader plugin
			$('input[name="fileToUpload"]').fileuploader({
		        addMore: true
		        
		    });
		   
        
    });
		</script>
<body>



<form action="upload.php" method="post" enctype="multipart/form-data">

    Select image to upload:

    <input type="file" name="fileToUpload" id="fileToUpload">

		<input type="submit">
</form>



</body>

</html>