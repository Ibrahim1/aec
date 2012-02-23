<?php if ( $tmpl->cfg['customtext_confirm_keeporiginal'] ) { ?>
	<p><?php echo JText::_('CONFIRM_INFO') ?></p>
<?php }

if ( $InvoiceFactory->coupons['active'] ) {
	if ( !empty( $tmpl->cfg['confirmation_coupons'] ) ) {
		?><p><?php echo JText::_('CONFIRM_COUPON_INFO_BOTH') ?></p><?
	} else {
		?><p><?php echo JText::_('CONFIRM_COUPON_INFO') ?></p><?
	}
} ?>
<?php if ( !empty( $tmpl->cfg['confirmation_coupons'] ) ) { ?>
	<strong><?php echo JText::_('CHECKOUT_COUPON_CODE') ?></strong>
	<input type="text" size="20" name="coupon_code" class="inputbox" value="" />
<?php }