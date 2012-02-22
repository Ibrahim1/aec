<div id="confirmation_button">
	<input type="hidden" name="option" value="<?= $option ?>" />
	<input type="hidden" name="userid" value="<?= $userid ? $userid : 0 ?>" />
	<input type="hidden" name="task" value="saveSubscription" />
	<input type="hidden" name="usage" value="<?= $InvoiceFactory->usage ?>" />
	<input type="hidden" name="processor" value="<?= $InvoiceFactory->processor ?>" />
	<? if ( isset( $InvoiceFactory->recurring ) ) { ?>
	<input type="hidden" name="recurring" value="<?= $InvoiceFactory->recurring ?>" />
	<? }
	if ( !empty( $tmpl->cfg['tos_iframe'] ) && !empty( $tmpl->cfg['tos'] ) ) { ?>
		<iframe src="<?= $tmpl->cfg['tos'] ?>" width="100%" height="150px"></iframe>
		<p><input name="tos" type="checkbox" /><?= JText::_('CONFIRM_TOS_IFRAME') ?></p>
		<input type="button" onClick="javascript:submitPayment()" class="button" value="<?= JText::_('BUTTON_CONFIRM') ?>" />
		<?
	} elseif ( !empty( $tmpl->cfg['tos'] ) ) { ?>
		<p><input name="tos" type="checkbox" /><?= JText::sprintf( 'CONFIRM_TOS', $tmpl->cfg['tos'] ) ?></p>
		<input type="button" onClick="javascript:submitPayment()" class="button" value="<?= JText::_('BUTTON_CONFIRM') ?>" />
		<?
	} else { ?>
		<input type="submit" class="button" value="<?= JText::_('BUTTON_CONFIRM') ?>" />
		<?
	} ?>
	<? if ( $passthrough != false ) { ?>
		<input type="hidden" name="aec_passthrough" value="<?= $passthrough ?>" />
	<? } ?>
</div>
