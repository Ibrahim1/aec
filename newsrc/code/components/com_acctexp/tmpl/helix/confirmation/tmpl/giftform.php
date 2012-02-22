<? if ( $makegift ) { ?>
<tr>
	<td class="giftinfo">
		<strong><?= JText::_('CHECKOUT_GIFT_HEAD')?></strong>
	</td>
</tr>
<tr>
	<td class="giftdetails">
		<? if ( !empty( $InvoiceFactory->invoice->params['target_user'] ) ) { ?>
			<p>This purchase will be gifted to: <?= $InvoiceFactory->invoice->params['target_username']?> (<a href="<?= AECToolbox::deadsureURL( 'index.php?option=com_acctexp&amp;task=InvoiceRemoveGiftConfirm&amp;invoice='.$InvoiceFactory->invoice->invoice_number, $tmpl->cfg['ssl_signup'] )?>">undo?</a>)</p>
			<input type="hidden" name="user_ident" value="<?= $InvoiceFactory->invoice->params['target_username']?>" />
		<? } else { ?>
			<p><?= JText::_('CHECKOUT_GIFT_INFO')?></p>
			<input type="text" size="20" name="user_ident" class="inputbox" value="" />
		<? } ?>
	</td>
</tr>
<? } ?>
