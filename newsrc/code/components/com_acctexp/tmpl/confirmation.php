<?php
global $aecConfig;

if ( !empty( $user->id ) ) {
	$userid = $user->id;
} else {
	$userid = 0;
}

HTML_frontend::aec_styling( $option );
?>

<div class="componentheading"><?php echo JText::_('CONFIRM_TITLE'); ?></div>
<?php
if ( !empty( $aecConfig->cfg['tos'] ) ) { ?>
	<script type="text/javascript">
		/* <![CDATA[ */
		function submitPayment() {
			if ( document.confirmForm.tos.checked ) {
				document.confirmForm.submit();
			} else {
				alert("<?php echo html_entity_decode( JText::_('CONFIRM_TOS_ERROR') ); ?>");
			}
		}
		/* ]]> */
	</script>
	<?php
} ?>
<div id="confirmation">
	<div id="confirmation_info">
		<table>
			<tr>
				<th><?php echo JText::_('CONFIRM_COL1_TITLE'); ?></th>
				<th><?php echo JText::_('CONFIRM_COL2_TITLE'); ?></th>
				<th><?php echo JText::_('CONFIRM_COL3_TITLE'); ?></th>
			</tr>
			<tr>
				<td><?php echo $InvoiceFactory->userdetails; ?></td>
				<td><p><?php echo $InvoiceFactory->plan->name; ?></p></td>
				<td><p><?php echo $InvoiceFactory->payment->amount_format ?></p></td>
			</tr>
			<?php if ( empty( $userid ) && $aecConfig->cfg['confirmation_changeusername'] ) { ?>
			<tr>
				<td>
					<form class="aectextright" name="backFormUserDetails" action="<?php echo AECToolbox::deadsureURL( 'index.php?option=' . $option, $aecConfig->cfg['ssl_signup'] ); ?>" method="post">
						<input type="hidden" name="option" value="<?php echo $option; ?>" />
						<input type="hidden" name="userid" value="<?php echo $userid ? $userid : 0; ?>" />
						<input type="hidden" name="task" value="subscribe" />
						<input type="hidden" name="usage" value="<?php echo $InvoiceFactory->usage; ?>" />
						<input type="hidden" name="processor" value="<?php echo $InvoiceFactory->processor; ?>" />
						<input type="hidden" name="recurring" value="<?php echo $InvoiceFactory->recurring;?>" />
						<input type="hidden" name="forget" value="userdetails" />
						<?php if ( $passthrough != false ) { ?>
							<input type="hidden" name="aec_passthrough" value="<?php echo $InvoiceFactory->getPassthrough( 'userdetails' ); ?>" />
						<?php } ?>
						<button class="aeclink" type="submit"><span><?php echo JText::_('CONFIRM_DIFFERENT_USER_DETAILS'); ?></span></button>
						<?php echo JHTML::_( 'form.token' ); ?>
					</form>
				</td>
				<?php if ( empty( $userid ) && $aecConfig->cfg['confirmation_changeusage'] ) { ?>
				<td colspan="2">
					<form class="aectextright" name="backFormUserPlan" action="<?php echo AECToolbox::deadsureURL( 'index.php?option=' . $option, $aecConfig->cfg['ssl_signup'] ); ?>" method="post">
						<input type="hidden" name="option" value="<?php echo $option; ?>" />
						<input type="hidden" name="userid" value="<?php echo $userid ? $userid : 0; ?>" />
						<input type="hidden" name="task" value="subscribe" />
						<input type="hidden" name="forget" value="usage" />
						<?php if ( $passthrough != false ) { ?>
							<input type="hidden" name="aec_passthrough" value="<?php echo $InvoiceFactory->getPassthrough( 'usage' ); ?>" />
						<?php } ?>
						<button class="aeclink" type="submit"><span><?php echo JText::_('CONFIRM_DIFFERENT_ITEM'); ?></span></button>
						<?php echo JHTML::_( 'form.token' ); ?>
					</form>
				</td>
				<?php } ?>
			</tr>
			<?php } ?>
			<?php if ( !empty( $InvoiceFactory->plan->desc ) && $aecConfig->cfg['confirmation_display_descriptions'] ) { ?>
			<tr>
				<td colspan="3" class="aec_left"><strong><?php echo JText::_('CONFIRM_YOU_HAVE_SELECTED'); ?>:</strong><br /><?php echo stripslashes( $InvoiceFactory->plan->desc ); ?></td>
			</tr>
			<?php } ?>
			<?php if ( $aecConfig->cfg['confirmation_changeusage'] && !( empty( $userid ) && $aecConfig->cfg['confirmation_changeusername'] ) ) { ?>
			<tr>
				<td colspan="3" class="aec_left">
					<form name="backFormUserDetails" action="<?php echo AECToolbox::deadsureURL( 'index.php?option=' . $option, $aecConfig->cfg['ssl_signup'] ); ?>" method="post">
						<input type="hidden" name="option" value="<?php echo $option; ?>" />
						<input type="hidden" name="userid" value="<?php echo $userid ? $userid : 0; ?>" />
						<input type="hidden" name="task" value="subscribe" />
						<input type="hidden" name="forget" value="usage" />
						<?php if ( $passthrough != false ) { ?>
							<input type="hidden" name="aec_passthrough" value="<?php echo $InvoiceFactory->getPassthrough( 'usage' ); ?>" />
						<?php } ?>

						<button class="aeclink" type="submit"><span><?php echo JText::_('CONFIRM_DIFFERENT_ITEM'); ?></span></button>
					</form>
				</td>
			</tr>
			<?php } ?>
		</table>
	</div>
	<form name="confirmForm" action="<?php echo AECToolbox::deadsureURL( 'index.php?option=' . $option . '&task=saveSubscription', $aecConfig->cfg['ssl_signup'] ); ?>" method="post">
	<table>
		<tr>
			<td id="confirmation_extra">
				<?php if ( !empty( $InvoiceFactory->mi_form ) ) {
					if ( !empty( $InvoiceFactory->mi_error ) ) {
						echo '<div id="confirmation_error">';
						foreach ( $InvoiceFactory->mi_error as $error ) {
							echo '<p>' . $error . '</p>';
						}
						echo '</div>';
					}
					echo '<div id="confirmation_extra">' . $InvoiceFactory->mi_form . '</div>';
				} ?>
				<?php
				if ( $aecConfig->cfg['customtext_confirm'] ) { ?>
					<p><?php echo $aecConfig->cfg['customtext_confirm']; ?></p>
					<?php
				}
				if ( $aecConfig->cfg['customtext_confirm_keeporiginal'] ) { ?>
					<p><?php echo JText::_('CONFIRM_INFO'); ?></p>
					<?php
				}
				if ( $InvoiceFactory->coupons['active'] ) {
					if ( !empty( $aecConfig->cfg['confirmation_coupons'] ) ) {
						?><p><?php echo JText::_('CONFIRM_COUPON_INFO_BOTH'); ?></p><?php
					} else {
						?><p><?php echo JText::_('CONFIRM_COUPON_INFO'); ?></p><?php
					}
				} ?>
				<?php if ( !empty( $aecConfig->cfg['confirmation_coupons'] ) ) { ?>
					<strong><?php echo JText::_('CHECKOUT_COUPON_CODE'); ?></strong>
					<input type="text" size="20" name="coupon_code" class="inputbox" value="" />
				<?php } ?>
			</td>
		</tr>
		<?php
		$makegift = false;

		if ( !empty( $aecConfig->cfg['confirm_as_gift'] ) ) {
			if ( !empty( $aecConfig->cfg['checkout_as_gift_access'] ) ) {
				if ( $InvoiceFactory->metaUser->hasGroup( $aecConfig->cfg['checkout_as_gift_access'] ) ) {
					$makegift = true;
				}
			} else {
				$makegift = true;
			}
		}

		if ( $makegift ) { ?>
				<tr>
					<td class="couponinfo">
						<strong><?php echo JText::_('CHECKOUT_GIFT_HEAD'); ?></strong>
					</td>
				</tr>
				<tr>
					<td class="giftdetails">
						<?php if ( !empty( $InvoiceFactory->invoice->params['target_user'] ) ) { ?>
							<p>This purchase will be gifted to: <?php echo $InvoiceFactory->invoice->params['target_username']; ?> (<a href="<?php echo AECToolbox::deadsureURL( 'index.php?option=com_acctexp&amp;task=InvoiceRemoveGiftConfirm&amp;invoice='.$InvoiceFactory->invoice->invoice_number, $aecConfig->cfg['ssl_signup'] ); ?>">undo?</a>)</p>
							<input type="hidden" name="user_ident" value="<?php echo $InvoiceFactory->invoice->params['target_username']; ?>" />
						<?php } else { ?>
							<p><?php echo JText::_('CHECKOUT_GIFT_INFO'); ?></p>
							<input type="text" size="20" name="user_ident" class="inputbox" value="" />
						<?php } ?>
					</td>
				</tr>
			<?php
		} ?>
		<tr>
			<td id="confirmation_button">
			<div id="confirmation_button">
				<input type="hidden" name="option" value="<?php echo $option; ?>" />
				<input type="hidden" name="userid" value="<?php echo $userid ? $userid : 0; ?>" />
				<input type="hidden" name="task" value="saveSubscription" />
				<input type="hidden" name="usage" value="<?php echo $InvoiceFactory->usage; ?>" />
				<input type="hidden" name="processor" value="<?php echo $InvoiceFactory->processor; ?>" />
				<?php if ( isset( $InvoiceFactory->recurring ) ) { ?>
				<input type="hidden" name="recurring" value="<?php echo $InvoiceFactory->recurring;?>" />
				<?php }
				if ( !empty( $aecConfig->cfg['tos_iframe'] ) && !empty( $aecConfig->cfg['tos'] ) ) { ?>
					<iframe src="<?php echo $aecConfig->cfg['tos']; ?>" width="100%" height="150px"></iframe>
					<p><input name="tos" type="checkbox" /><?php echo JText::_('CONFIRM_TOS_IFRAME'); ?></p>
					<input type="button" onClick="javascript:submitPayment()" class="button" value="<?php echo JText::_('BUTTON_CONFIRM'); ?>" />
					<?php
				} elseif ( !empty( $aecConfig->cfg['tos'] ) ) { ?>
					<p><input name="tos" type="checkbox" /><?php echo JText::sprintf( 'CONFIRM_TOS', $aecConfig->cfg['tos'] ); ?></p>
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
			</td>
		</tr>
		<tr><td>
			<table>
				<?php if ( is_object( $InvoiceFactory->pp ) ) {
					HTML_frontEnd::processorInfo( $option, $InvoiceFactory->pp, $aecConfig->cfg['displayccinfo'] );
				} ?>
			</table>
		</td></tr>
	</table>
	<?php echo JHTML::_( 'form.token' ); ?>
	</form>
</div>
<div class="aec_clearfix"></div>
<?php
?>
