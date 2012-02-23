<form class="aectextright" name="backFormUserDetails" action="<?php echo AECToolbox::deadsureURL( 'index.php?option=' . $option, $tmpl->cfg['ssl_signup'] ) ?>" method="post">
	<input type="hidden" name="option" value="<?php echo $option ?>" />
	<input type="hidden" name="userid" value="<?php echo $userid ? $userid : 0 ?>" />
	<input type="hidden" name="task" value="subscribe" />
	<input type="hidden" name="usage" value="<?php echo $InvoiceFactory->usage ?>" />
	<input type="hidden" name="processor" value="<?php echo $InvoiceFactory->processor ?>" />
	<input type="hidden" name="recurring" value="<?php echo $InvoiceFactory->recurring ?>" />
	<input type="hidden" name="forget" value="userdetails" />
	<?php if ( $passthrough != false ) { ?>
		<input type="hidden" name="aec_passthrough" value="<?php echo $InvoiceFactory->getPassthrough( 'userdetails' ) ?>" />
	<?php } ?>
	<button class="aeclink" type="submit"><span><?php echo JText::_('CONFIRM_DIFFERENT_USER_DETAILS') ?></span></button>
	<?php echo JHTML::_( 'form.token' ) ?>
</form>
