<?php
 session_start();
    $currentpage="page";
    include('include/header.php');
    error_reporting(E_ALL);
  


  ?>
<div class="container" style="margin-top: 127px; margin-bottom:20px;">
  <div class="col-sm-12 terms_privacy_about">
  	<div class="col-sm-12 terms_privacy_inner">
  		<div class="">
			    <h1 class=""><?php echo $content_pages_static[0]['txtTitle'];?></h1>
			    <div><?php echo $content_pages_static[0]['txtcontent'];?></div>
		</div>
  </div>
</div>

</div>
<?php include('include/footer.php'); ?>