<?
/**
 * @version $Id: cart.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Main Frontend
 * @copyright 2012 Copyright (C) David Deutsch
 * @author David Deutsch <skore@valanx.org> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' )?>

<?
/**
 * @version $Id: access_denied.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Main Frontend
 * @copyright 2012 Copyright (C) David Deutsch
 * @author David Deutsch <skore@valanx.org> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */

<div class="componentheading"><?= JText::_('CART_TITLE')?></div>
<?
if ( !empty( $tmpl->cfg['tos'] ) ) { ?>
	<script type="text/javascript">
		/* <![CDATA[ */
		function submitPayment() {
			if ( document.confirmForm.tos.checked ) {
				document.confirmForm.submit();
			} else {
				alert("<?= html_entity_decode( JText::_('CONFIRM_TOS_ERROR') )?>");
			}
		}
		/* ]]> */
	</script>
	<?
} ?>
<div id="confirmation">
	<div id="confirmation_info">
		<? if ( empty( $InvoiceFactory->cart ) ) { ?>
		<p>Your Shopping Cart is empty!</p>
		<? } else { ?>
		<p>&nbsp;</p>
		<div id="clear_button"><a href="<?= AECToolbox::deadsureURL( 'index.php?option=' . $option . '&task=clearCart&'. JUtility::getToken() .'=1', $tmpl->cfg['ssl_signup'] )?>"><?= JText::_('CART_CLEAR_ALL')?></a></div>
		<form name="updateForm" action="<?= AECToolbox::deadsureURL( 'index.php?option=' . $option . '&task=updateCart', $tmpl->cfg['ssl_signup'] )?>" method="post">
		<table>
			<tr>
				<th>Item</th>
				<th>Cost</th>
				<th>Amount</th>
				<th>Total</th>
				<th>&nbsp;</th>
			</tr>
			<? foreach ( $InvoiceFactory->cart as $bid => $bitem ) {
				if ( !empty( $bitem['name'] ) ) {
					?><tr>
						<td><?= $bitem['name']?></td>
						<td><?= $bitem['cost']?></td>
						<td><input type="inputbox" type="text" size="2" name="cartitem_<?= $bid?>" value="<?= $bitem['quantity']?>" /></td>
						<td><?= $bitem['cost_total']?></td>
						<td><a href="<?= AECToolbox::deadsureURL( 'index.php?option=' . $option . '&task=clearCartItem&item='.$bid.'&'. JUtility::getToken() .'=1', $tmpl->cfg['ssl_signup'] )?>"><?= JText::_('CART_DELETE_ITEM')?></a></td>
					</tr><?
				} else {
					?><tr>
						<td><strong><?= JText::_('CART_ROW_TOTAL')?></strong></td>
						<td></td>
						<td></td>
						<td><strong><?= $bitem['cost']?></strong></td>
						<td></td>
					</tr><?
				}
			} ?>
		</table>
		<input type="hidden" name="option" value="<?= $option?>" />
		<input type="hidden" name="userid" value="<?= $user->id ? $user->id : 0?>" />
		<input type="hidden" name="task" value="updateCart" />
		<div id="update_button"><input type="image" src="<?= JURI::root(true) . '/media/com_acctexp/images/site/update_button.png'?>" border="0" name="submit" alt="submit" /></div>
		<?= JHTML::_( 'form.token' )?>
		</form>
		<? } ?>
		<? if ( empty( $InvoiceFactory->userid ) ) { ?>
		<p>Save Registration to Continue Shopping:</p>
		<? } else {
			if ( !empty( $tmpl->cfg['customlink_continueshopping'] ) ) {
				$continueurl = $tmpl->cfg['customlink_continueshopping'];
			} else {
				$continueurl = AECToolbox::deadsureURL( 'index.php?option=' . $option . '&task=subscribe', $tmpl->cfg['ssl_signup'] );
			}
		?>
		<div id="continue_button">
			<form name="continueForm" action="<?= $continueurl?>" method="post">
				<input type="image" src="<?= JURI::root(true) . '/media/com_acctexp/images/site/continue_shopping_button.png'?>" border="0" name="submit" alt="submit" />
			</form>
		</div>
		<? } ?>
	</div>
	<? if ( !empty( $InvoiceFactory->cart ) ) { ?>
	<form name="confirmForm" action="<?= AECToolbox::deadsureURL( 'index.php?option=' . $option . '&task=confirmCart', $tmpl->cfg['ssl_signup'] )?>" method="post">
	<table>
		<tr>
			<td id="confirmation_extra">
				<? if ( !empty( $InvoiceFactory->mi_form ) ) {
					if ( !empty( $InvoiceFactory->mi_error ) ) {
						echo '<div id="confirmation_error">';
						foreach ( $InvoiceFactory->mi_error as $error ) {
							echo '<p>' . $error . '</p>';
						}
						echo '</div>';
					}
					echo '<div id="confirmation_extra">' . $InvoiceFactory->mi_form . '</div>';
				} ?>
				<?
				if ( $tmpl->cfg['customtext_confirm'] ) { ?>
					<p><?= $tmpl->cfg['customtext_confirm']?></p>
					<?
				}
				if ( $tmpl->cfg['customtext_confirm_keeporiginal'] ) { ?>
					<p><?= JText::_('CART_INFO')?></p>
					<?
				} ?>
			</td>
		</tr>
		<?
		$makegift = false;

		if ( !empty( $tmpl->cfg['confirm_as_gift'] ) ) {
			if ( !empty( $tmpl->cfg['checkout_as_gift_access'] ) ) {
				if ( $InvoiceFactory->metaUser->hasGroup( $tmpl->cfg['checkout_as_gift_access'] ) ) {
					$makegift = true;
				}
			} else {
				$makegift = true;
			}
		}

		if ( $makegift ) { ?>
				<tr>
					<td class="couponinfo">
						<strong><?= JText::_('CHECKOUT_GIFT_HEAD')?></strong>
					</td>
				</tr>
				<tr>
					<td class="giftdetails">
						<? if ( !empty( $InvoiceFactory->invoice->params['target_user'] ) ) { ?>
							<p>This purchase will be gifted to: <?= $InvoiceFactory->invoice->params['target_username']?> (<a href="<?= AECToolbox::deadsureURL( 'index.php?option=com_acctexp&task=InvoiceRemoveGiftCart&invoice='.$InvoiceFactory->invoice->invoice_number.'&'. JUtility::getToken() .'=1', $tmpl->cfg['ssl_signup'] )?>">undo?</a>)</p>
						<? } else { ?>
							<p><?= JText::_('CHECKOUT_GIFT_INFO')?></p>
							<input type="text" size="20" name="user_ident" class="inputbox" value="" />
						<? } ?>
					</td>
				</tr>
			<?
		} ?>
		<tr>
			<td id="confirmation_button">
			<div id="confirmation_button">
				<input type="hidden" name="option" value="<?= $option?>" />
				<input type="hidden" name="userid" value="<?= $user->id ? $user->id : 0?>" />
				<input type="hidden" name="task" value="confirmCart" />
				<? if ( isset( $InvoiceFactory->recurring ) ) { ?>
				<input type="hidden" name="recurring" value="<?= $InvoiceFactory->recurring ?>" />
				<? }
				if ( !empty( $tmpl->cfg['tos_iframe'] ) && !empty( $tmpl->cfg['tos'] ) ) { ?>
					<iframe src="<?= $tmpl->cfg['tos']?>" width="100%" height="150px"></iframe>
					<p><input name="tos" type="checkbox" /><?= JText::_('CONFIRM_TOS_IFRAME')?></p>
					<input type="button" onClick="javascript:submitPayment()" class="button" value="<?= JText::_('BUTTON_CONFIRM')?>" />
					<?
				} elseif ( !empty( $tmpl->cfg['tos'] ) ) { ?>
					<p><input name="tos" type="checkbox" /><?= sprintf( JText::_('CONFIRM_TOS'), $tmpl->cfg['tos'] )?></p>
					<input type="button" onClick="javascript:submitPayment()" class="button" value="<?= JText::_('BUTTON_CONFIRM')?>" />
					<?
				} else { ?>
					<input type="submit" class="button" value="<?= JText::_('BUTTON_CONFIRM')?>" />
					<?
				} ?>
			</div>
			</td>
		</tr>
		<tr><td>
			<table>
				<?
				if ( !empty( $InvoiceFactory->pp ) ) {
					if ( is_object( $InvoiceFactory->pp ) ) {
						HTML_frontEnd::processorInfo( $option, $InvoiceFactory->pp, $tmpl->cfg['displayccinfo'] );
					}
				} ?>
			</table>
		</td></tr>
	</table>
	<?= JHTML::_( 'form.token' )?>
	</form>
	<? } ?>
</div>
