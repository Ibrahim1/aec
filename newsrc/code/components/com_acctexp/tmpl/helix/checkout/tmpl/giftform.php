<table width="100%" id="giftbox">
	<tr>
		<td class="giftinfo">
			<strong><?php echo JText::_('CHECKOUT_GIFT_HEAD') ?></strong>
		</td>
	</tr>
	<tr>
		<td class="giftdetails">
			<?php if ( !empty( $InvoiceFactory->invoice->params['target_user'] ) ) { ?>
				<p>This purchase will be gifted to: <?php echo $InvoiceFactory->invoice->params['target_username'] ?> (<a href="<?php echo AECToolbox::deadsureURL( 'index.php?option=com_acctexp&task=InvoiceRemoveGift&invoice='.$InvoiceFactory->invoice->invoice_number.'&'. JUtility::getToken() .'=1', $tmpl->cfg['ssl_signup'] ) ?>">undo?</a>)</p>
			<?php } else { ?>
			<p><?php echo JText::_('CHECKOUT_GIFT_INFO') ?></p>
			<form action="<?php echo AECToolbox::deadsureURL( 'index.php?option=com_acctexp&amp;task=InvoiceMakeGift', $tmpl->cfg['ssl_signup'] ) ?>" method="post">
				<input type="text" size="20" name="user_ident" class="inputbox" value="" />
				<input type="hidden" name="option" value="<?php echo $option ?>" />
				<input type="hidden" name="task" value="InvoiceMakeGift" />
				<input type="hidden" name="invoice" value="<?php echo $InvoiceFactory->invoice->invoice_number ?>" />
				<input type="submit" class="button" value="<?php echo JText::_('BUTTON_APPLY') ?>" />
				<?php echo JHTML::_( 'form.token' ) ?>
			</form>
			<?php } ?>
		</td>
	</tr>
</table>
