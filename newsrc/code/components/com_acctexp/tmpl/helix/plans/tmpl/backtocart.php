<?
 ?>
<div id="checkout">
	<table id="aec_checkout">
		<form name="confirmForm" action="<?php echo AECToolbox::deadsureURL( 'index.php?option=' . $option . '&task=cart', $tmpl->cfg['ssl_signup'] ) ?>" method="post">
			<div id="update_button">You can always go back to: <input type="image" src="<?php echo JURI::root(true) . '/media/com_acctexp/images/site/your_cart_button.png' ?>" border="0" name="submit" alt="submit" /></div>
			<?php echo JHTML::_( 'form.token' ) ?>
		</form><br /><br />
	</table>
</div>
<div class="aec_clearfix"></div>
