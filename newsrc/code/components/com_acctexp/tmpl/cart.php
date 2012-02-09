<?php
$user = &JFactory::getUser();

HTML_frontend::aec_styling( $option );
?>

<div class="componentheading"><?php echo JText::_('CART_TITLE'); ?></div>
<?php
if ( !empty( $cfg->cfg['tos'] ) ) { ?>
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
		<?php if ( empty( $InvoiceFactory->cart ) ) { ?>
		<p>Your Shopping Cart is empty!</p>
		<?php } else { ?>
		<p>&nbsp;</p>
		<div id="clear_button"><a href="<?php echo AECToolbox::deadsureURL( 'index.php?option=' . $option . '&task=clearCart&'. JUtility::getToken() .'=1', $cfg->cfg['ssl_signup'] ); ?>"><?php echo JText::_('CART_CLEAR_ALL'); ?></a></div>
		<form name="updateForm" action="<?php echo AECToolbox::deadsureURL( 'index.php?option=' . $option . '&task=updateCart', $cfg->cfg['ssl_signup'] ); ?>" method="post">
		<table>
			<tr>
				<th>Item</th>
				<th>Cost</th>
				<th>Amount</th>
				<th>Total</th>
				<th>&nbsp;</th>
			</tr>
			<?php
			foreach ( $InvoiceFactory->cart as $bid => $bitem ) {
				if ( !empty( $bitem['name'] ) ) {
					?><tr>
						<td><?php echo $bitem['name']; ?></td>
						<td><?php echo $bitem['cost']; ?></td>
						<td><input type="inputbox" type="text" size="2" name="cartitem_<?php echo $bid; ?>" value="<?php echo $bitem['quantity']; ?>" /></td>
						<td><?php echo $bitem['cost_total']; ?></td>
						<td><a href="<?php echo AECToolbox::deadsureURL( 'index.php?option=' . $option . '&task=clearCartItem&item='.$bid.'&'. JUtility::getToken() .'=1', $cfg->cfg['ssl_signup'] ); ?>"><?php echo JText::_('CART_DELETE_ITEM'); ?></a></td>
					</tr><?php
				} else {
					?><tr>
						<td><strong><?php echo JText::_('CART_ROW_TOTAL'); ?></strong></td>
						<td></td>
						<td></td>
						<td><strong><?php echo $bitem['cost']; ?></strong></td>
						<td></td>
					</tr><?php
				}
			}
			?>
		</table>
		<input type="hidden" name="option" value="<?php echo $option; ?>" />
		<input type="hidden" name="userid" value="<?php echo $user->id ? $user->id : 0; ?>" />
		<input type="hidden" name="task" value="updateCart" />
		<div id="update_button"><input type="image" src="<?php echo JURI::root(true) . '/media/com_acctexp/images/site/update_button.png'; ?>" border="0" name="submit" alt="submit" /></div>
		<?php echo JHTML::_( 'form.token' ); ?>
		</form>
		<?php } ?>
		<?php if ( empty( $InvoiceFactory->userid ) ) { ?>
		<p>Save Registration to Continue Shopping:</p>
		<?php } else {
			if ( !empty( $cfg->cfg['customlink_continueshopping'] ) ) {
				$continueurl = $cfg->cfg['customlink_continueshopping'];
			} else {
				$continueurl = AECToolbox::deadsureURL( 'index.php?option=' . $option . '&task=subscribe', $cfg->cfg['ssl_signup'] );
			}
		?>
		<div id="continue_button">
			<form name="continueForm" action="<?php echo $continueurl; ?>" method="post">
				<input type="image" src="<?php echo JURI::root(true) . '/media/com_acctexp/images/site/continue_shopping_button.png'; ?>" border="0" name="submit" alt="submit" />
			</form>
		</div>
		<?php } ?>
	</div>
	<?php if ( !empty( $InvoiceFactory->cart ) ) { ?>
	<form name="confirmForm" action="<?php echo AECToolbox::deadsureURL( 'index.php?option=' . $option . '&task=confirmCart', $cfg->cfg['ssl_signup'] ); ?>" method="post">
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
				if ( $cfg->cfg['customtext_confirm'] ) { ?>
					<p><?php echo $cfg->cfg['customtext_confirm']; ?></p>
					<?php
				}
				if ( $cfg->cfg['customtext_confirm_keeporiginal'] ) { ?>
					<p><?php echo JText::_('CART_INFO'); ?></p>
					<?php
				} ?>
			</td>
		</tr>
		<?php
		$makegift = false;

		if ( !empty( $cfg->cfg['confirm_as_gift'] ) ) {
			if ( !empty( $cfg->cfg['checkout_as_gift_access'] ) ) {
				if ( $InvoiceFactory->metaUser->hasGroup( $cfg->cfg['checkout_as_gift_access'] ) ) {
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
							<p>This purchase will be gifted to: <?php echo $InvoiceFactory->invoice->params['target_username']; ?> (<a href="<?php echo AECToolbox::deadsureURL( 'index.php?option=com_acctexp&task=InvoiceRemoveGiftCart&invoice='.$InvoiceFactory->invoice->invoice_number.'&'. JUtility::getToken() .'=1', $cfg->cfg['ssl_signup'] ); ?>">undo?</a>)</p>
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
				<input type="hidden" name="userid" value="<?php echo $user->id ? $user->id : 0; ?>" />
				<input type="hidden" name="task" value="confirmCart" />
				<?php if ( isset( $InvoiceFactory->recurring ) ) { ?>
				<input type="hidden" name="recurring" value="<?php echo $InvoiceFactory->recurring;?>" />
				<?php }
				if ( !empty( $cfg->cfg['tos_iframe'] ) && !empty( $cfg->cfg['tos'] ) ) { ?>
					<iframe src="<?php echo $cfg->cfg['tos']; ?>" width="100%" height="150px"></iframe>
					<p><input name="tos" type="checkbox" /><?php echo JText::_('CONFIRM_TOS_IFRAME'); ?></p>
					<input type="button" onClick="javascript:submitPayment()" class="button" value="<?php echo JText::_('BUTTON_CONFIRM'); ?>" />
					<?php
				} elseif ( !empty( $cfg->cfg['tos'] ) ) { ?>
					<p><input name="tos" type="checkbox" /><?php echo sprintf( JText::_('CONFIRM_TOS'), $cfg->cfg['tos'] ); ?></p>
					<input type="button" onClick="javascript:submitPayment()" class="button" value="<?php echo JText::_('BUTTON_CONFIRM'); ?>" />
					<?php
				} else { ?>
					<input type="submit" class="button" value="<?php echo JText::_('BUTTON_CONFIRM'); ?>" />
					<?php
				} ?>
			</div>
			</td>
		</tr>
		<tr><td>
			<table>
				<?php
				if ( !empty( $InvoiceFactory->pp ) ) {
					if ( is_object( $InvoiceFactory->pp ) ) {
						HTML_frontEnd::processorInfo( $option, $InvoiceFactory->pp, $cfg->cfg['displayccinfo'] );
					}
				} ?>
			</table>
		</td></tr>
	</table>
	<?php echo JHTML::_( 'form.token' ); ?>
	</form>
	<?php } ?>
</div>
