	<?php
 session_start();
    $currentpage="contact";
    include('include/header.php');
    error_reporting(E_ALL);
  
 
?>
<div class="container_fluid" style=" background:url(images/images/contact_back2.png)no-repeat center "  >
<div   style="min-height:260px;background-color:rgba(0, 0, 0, 0.09); ">
</div></div>
   <div class="container contan " >
   
   <h1 class="margin_topbot_20  wow zoomIn" data-wow-offset="100" data-wow-duration="2s"  style="" ><?php echo $contact[0]['txtTitle'];?></h1>
    <div class="col-md-12 "><iframe  src="<?php echo strip_tags($contact[0]['txtMap']);?>" width="100%" height="400px" frameborder="0" style="border:0" allowfullscreen></iframe></div>
   <div class="col-sm-12 wow zoomIn" data-wow-offset="100" data-wow-duration="2s"  style="margin-top:20px;">
   <?php echo $contact[0]['txtcontent']; ?>

   
   </div>
   </div>
    
<?php include('include/footer.php'); ?>
 <Script>
$(document).ready(function()
{
 $(".contan a").addClass("hvr-underline-from-left");
});
  
 </Script>