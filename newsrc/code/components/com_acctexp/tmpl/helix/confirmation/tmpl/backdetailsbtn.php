<form class="aectextright" name="backFormUserDetails" action="<?= AECToolbox::deadsureURL( 'index.php?option=' . $option, $tmpl->cfg['ssl_signup'] )?>" method="post">
	<input type="hidden" name="option" value="<?= $option?>" />
	<input type="hidden" name="userid" value="<?= $userid ? $userid : 0?>" />
	<input type="hidden" name="task" value="subscribe" />
	<input type="hidden" name="usage" value="<?= $InvoiceFactory->usage?>" />
	<input type="hidden" name="processor" value="<?= $InvoiceFactory->processor?>" />
	<input type="hidden" name="recurring" value="<?= $InvoiceFactory->recurring ?>" />
	<input type="hidden" name="forget" value="userdetails" />
	<? if ( $passthrough != false ) { ?>
		<input type="hidden" name="aec_passthrough" value="<?= $InvoiceFactory->getPassthrough( 'userdetails' )?>" />
	<? } ?>
	<button class="aeclink" type="submit"><span><?= JText::_('CONFIRM_DIFFERENT_USER_DETAILS')?></span></button>
	<?= JHTML::_( 'form.token' )?>
</form>
