	<?php  $currentpage="car";
	include('include/header.php');?>		
		  
           
        <div id="page-wrapper">
     
<?php if(isset($_SESSION['lastRechargeAmt']) && !empty($_SESSION['lastRechargeAmt'])){ ?>
			<div class="payment-success">
				<p>Thanks for your process. Your account has been credited with the amount Rs. <?php echo (isset($_SESSION['lastRechargeAmt']) && !empty($_SESSION['lastRechargeAmt']) ? $_SESSION['lastRechargeAmt'] : ''); ?>
				</p>
			</div>
			<a href="" target="_parent" style="float:right;"><button class="btn cubtn">Go back to Home!</button></a>
			<?php unset($_SESSION['lastRechargeAmt']);} ?>
		
		<div class="clearfix"> </div>
	    </div>
		<?php include('include/footer.php')?>
		
		