<?php
	include('include/header.php');
?>
			<div id="page-wrapper">
				<div class="graphs">
					<div class="payment-success">
						<p>Thank you for your process. Your account has been recharged with the amount Rs. <?php echo (isset($_SESSION['lastRechargeAmt']) && !empty($_SESSION['lastRechargeAmt']) ? $_SESSION['lastRechargeAmt'] : ''); ?> successfully.
						</p>
					</div>
				</div>
			</div>
<?php
	include('include/footer.php');
?>