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
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' ) ?>

<div class="componentheading"><?php echo JText::_('CART_TITLE') ?></div>
<div id="confirmation">
	<div id="confirmation_info">
		<?php if ( empty( $InvoiceFactory->cart ) ) { ?>
		<p>Your Shopping Cart is empty!</p>
		<?php } else { ?>
		<p>&nbsp;</p>
		<div id="clear_button"><a href="<?php echo AECToolbox::deadsureURL( 'index.php?option=' . $option . '&task=clearCart&'. JUtility::getToken() .'=1', $tmpl->cfg['ssl_signup'] ) ?>"><?php echo JText::_('CART_CLEAR_ALL') ?></a></div>
		<form name="updateForm" action="<?php echo AECToolbox::deadsureURL( 'index.php?option=' . $option . '&task=updateCart', $tmpl->cfg['ssl_signup'] ) ?>" method="post">
		<table>
			<tr>
				<th>Item</th>
				<th>Cost</th>
				<th>Amount</th>
				<th>Total</th>
				<th>&nbsp;</th>
			</tr>
			<?php foreach ( $InvoiceFactory->cart as $bid => $bitem ) {
				if ( !empty( $bitem['name'] ) ) {
					?><tr>
						<td><?php echo $bitem['name'] ?></td>
						<td><?php echo $bitem['cost'] ?></td>
						<td><input type="inputbox" type="text" size="2" name="cartitem_<?php echo $bid ?>" value="<?php echo $bitem['quantity'] ?>" /></td>
						<td><?php echo $bitem['cost_total'] ?></td>
						<td><a href="<?php echo AECToolbox::deadsureURL( 'index.php?option=' . $option . '&task=clearCartItem&item='.$bid.'&'. JUtility::getToken() .'=1', $tmpl->cfg['ssl_signup'] ) ?>"><?php echo JText::_('CART_DELETE_ITEM') ?></a></td>
					</tr><?
				} else {
					?><tr>
						<td><strong><?php echo JText::_('CART_ROW_TOTAL') ?></strong></td>
						<td></td>
						<td></td>
						<td><strong><?php echo $bitem['cost'] ?></strong></td>
						<td></td>
					</tr><?
				}
			} ?>
		</table>
		<input type="hidden" name="option" value="<?php echo $option ?>" />
		<input type="hidden" name="userid" value="<?php echo $user->id ? $user->id : 0 ?>" />
		<input type="hidden" name="task" value="updateCart" />
		<div id="update_button"><input type="image" src="<?php echo JURI::root(true) . '/media/com_acctexp/images/site/update_button.png' ?>" border="0" name="submit" alt="submit" /></div>
		<?php echo JHTML::_( 'form.token' ) ?>
		</form>
		<?php } ?>
		<?php if ( empty( $InvoiceFactory->userid ) ) { ?>
		<p>Save Registration to Continue Shopping:</p>
		<?php } else {
			if ( !empty( $tmpl->cfg['customlink_continueshopping'] ) ) {
				$continueurl = $tmpl->cfg['customlink_continueshopping'];
			} else {
				$continueurl = AECToolbox::deadsureURL( 'index.php?option=' . $option . '&task=subscribe', $tmpl->cfg['ssl_signup'] );
			}
		?>
		<div id="continue_button">
			<form name="continueForm" action="<?php echo $continueurl ?>" method="post">
				<input type="image" src="<?php echo JURI::root(true) . '/media/com_acctexp/images/site/continue_shopping_button.png' ?>" border="0" name="submit" alt="submit" />
			</form>
		</div>
		<?php } ?>
	</div>
	<?php if ( !empty( $InvoiceFactory->cart ) ) { ?>
	<form name="confirmForm" action="<?php echo AECToolbox::deadsureURL( 'index.php?option=' . $option . '&task=confirmCart', $tmpl->cfg['ssl_signup'] ) ?>" method="post">
	<table>
		<tr>
			<td id="confirmation_extra">
				<?
				@include( $tmpl->tmpl( 'miform' ) );
				$tmpl->custom( 'customtext_confirm' );
				?>
			</td>
		</tr>
		<?php if ( $makegift ) { @include( $tmpl->tmpl( 'confirmation.giftform' ) ); } ?>
		<tr>
			<td id="confirmation_button"><?php $tmpl->hijack( 'confirmation.confirmationbtn', 'saveSubscription', 'confirmCart' ) ?></td>
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
	<?php echo JHTML::_( 'form.token' ) ?>
	</form>
	<?php } ?>
</div>
