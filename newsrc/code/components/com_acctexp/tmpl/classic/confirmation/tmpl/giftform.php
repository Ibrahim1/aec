<?php if ( $makegift ) { ?>
<tr>
	<td class="giftinfo">
		<strong><?php echo JText::_('CHECKOUT_GIFT_HEAD'); ?></strong>
	</td>
</tr>
<tr>
	<td class="giftdetails">
		<?php if ( !empty( $InvoiceFactory->invoice->params['target_user'] ) ) { ?>
			<p>This purchase will be gifted to: <?php echo $InvoiceFactory->invoice->params['target_username']; ?> (<a href="<?php echo AECToolbox::deadsureURL( 'index.php?option=com_acctexp&amp;task=InvoiceRemoveGiftConfirm&amp;invoice='.$InvoiceFactory->invoice->invoice_number, $tmpl->cfg['ssl_signup'] ); ?>">undo?</a>)</p>
			<input type="hidden" name="user_ident" value="<?php echo $InvoiceFactory->invoice->params['target_username']; ?>" />
		<?php } else { ?>
			<p><?php echo JText::_('CHECKOUT_GIFT_INFO'); ?></p>
			<input type="text" size="20" name="user_ident" class="inputbox" value="" />
		<?php } ?>
	</td>
</tr>
<?php } ?>
