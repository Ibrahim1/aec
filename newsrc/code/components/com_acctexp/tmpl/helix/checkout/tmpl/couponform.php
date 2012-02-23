<?php if ( !empty( $InvoiceFactory->checkout['enable_coupons'] ) ) { ?>
	<table width="100%" id="couponsbox">
		<tr>
			<td class="couponinfo">
				<strong><?php echo JText::_('CHECKOUT_COUPON_CODE') ?></strong>
			</td>
		</tr>
		<?php if ( !empty( $InvoiceFactory->errors ) ) {
			foreach ( $InvoiceFactory->errors as $err ) { ?>
				<tr>
					<td class="couponerror">
						<p><strong><?php echo JText::_('COUPON_ERROR_PRETEXT') ?></strong>&nbsp;<?php echo $err ?></p>
					</td>
				</tr>
			<?php }
		} ?>
		<tr>
			<td class="coupondetails">
				<p><?php echo JText::_('CHECKOUT_COUPON_INFO') ?></p>
				<form action="<?php echo AECToolbox::deadsureURL( 'index.php?option=com_acctexp&amp;task=InvoiceAddCoupon', $tmpl->cfg['ssl_signup'] ) ?>" method="post">
					<input type="text" size="20" name="coupon_code" class="inputbox" value="" />
					<input type="hidden" name="option" value="<?php echo $option ?>" />
					<input type="hidden" name="task" value="InvoiceAddCoupon" />
					<input type="hidden" name="invoice" value="<?php echo $InvoiceFactory->invoice->invoice_number ?>" />
					<input type="submit" class="button" value="<?php echo JText::_('BUTTON_APPLY') ?>" />
					<?php echo JHTML::_( 'form.token' ) ?>
				</form>
			</td>
		</tr>
	</table>
<?php } ?>
