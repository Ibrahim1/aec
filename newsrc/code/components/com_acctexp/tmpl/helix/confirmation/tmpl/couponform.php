<? if ( $tmpl->cfg['customtext_confirm_keeporiginal'] ) { ?>
	<p><?= JText::_('CONFIRM_INFO') ?></p>
<? }

if ( $InvoiceFactory->coupons['active'] ) {
	if ( !empty( $tmpl->cfg['confirmation_coupons'] ) ) {
		?><p><?= JText::_('CONFIRM_COUPON_INFO_BOTH') ?></p><?
	} else {
		?><p><?= JText::_('CONFIRM_COUPON_INFO') ?></p><?
	}
} ?>
<? if ( !empty( $tmpl->cfg['confirmation_coupons'] ) ) { ?>
	<strong><?= JText::_('CHECKOUT_COUPON_CODE') ?></strong>
	<input type="text" size="20" name="coupon_code" class="inputbox" value="" />
<? }