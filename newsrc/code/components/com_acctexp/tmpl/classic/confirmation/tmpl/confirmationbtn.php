<div id="confirmation_button">
	<input type="hidden" name="option" value="<?php echo $option; ?>" />
	<input type="hidden" name="userid" value="<?php echo $userid ? $userid : 0; ?>" />
	<input type="hidden" name="task" value="saveSubscription" />
	<input type="hidden" name="usage" value="<?php echo $InvoiceFactory->usage; ?>" />
	<input type="hidden" name="processor" value="<?php echo $InvoiceFactory->processor; ?>" />
	<?php if ( isset( $InvoiceFactory->recurring ) ) { ?>
	<input type="hidden" name="recurring" value="<?php echo $InvoiceFactory->recurring;?>" />
	<?php }
	if ( !empty( $tmpl->cfg['tos_iframe'] ) && !empty( $tmpl->cfg['tos'] ) ) { ?>
		<iframe src="<?php echo $tmpl->cfg['tos']; ?>" width="100%" height="150px"></iframe>
		<p><input name="tos" type="checkbox" /><?php echo JText::_('CONFIRM_TOS_IFRAME'); ?></p>
		<input type="button" onClick="javascript:submitPayment()" class="button" value="<?php echo JText::_('BUTTON_CONFIRM'); ?>" />
		<?php
	} elseif ( !empty( $tmpl->cfg['tos'] ) ) { ?>
		<p><input name="tos" type="checkbox" /><?php echo JText::sprintf( 'CONFIRM_TOS', $tmpl->cfg['tos'] ); ?></p>
		<input type="button" onClick="javascript:submitPayment()" class="button" value="<?php echo JText::_('BUTTON_CONFIRM'); ?>" />
		<?php
	} else { ?>
		<input type="submit" class="button" value="<?php echo JText::_('BUTTON_CONFIRM'); ?>" />
		<?php
	} ?>
	<?php if ( $passthrough != false ) { ?>
		<input type="hidden" name="aec_passthrough" value="<?php echo $passthrough; ?>" />
	<?php } ?>
</div>
