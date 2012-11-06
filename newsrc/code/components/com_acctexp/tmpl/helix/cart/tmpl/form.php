<?php
/**
 * @version $Id: form.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Main Frontend
 * @copyright 2012 Copyright (C) David Deutsch
 * @author David Deutsch <skore@valanx.org> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.3 http://www.gnu.org/licenses/gpl.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' ) ?>
<div id="confirmation_info">
	<?php if ( empty( $InvoiceFactory->cart ) ) { ?>
	<p>Your Shopping Cart is empty!</p>
	<?php } else { ?>
		<form name="updateForm" action="<?php echo AECToolbox::deadsureURL( 'index.php?option=' . $option . '&task=updateCart', $tmpl->cfg['ssl_signup'] ) ?>" method="post">
			<?php @include( $tmpl->tmpl( 'list' ) ); ?>
			<p>&nbsp;</p>
			<div id="clear_button"><?php echo $tmpl->lnk( array('task' => 'clearCart'), JText::_('CART_CLEAR_ALL') ) ?></div>
			<input type="hidden" name="option" value="<?php echo $option ?>" />
			<input type="hidden" name="userid" value="<?php echo $user->id ? $user->id : 0 ?>" />
			<input type="hidden" name="task" value="updateCart" />
			<div id="update_button"><button type="submit" class="aec-btn"><?php echo JText::_('AEC_BTN_UPDATE') ?></button></div>
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
			<button type="submit" class="aec-btn"><?php echo JText::_('AEC_BTN_CONTINUE_SHOPPING') ?></button>
		</form>
	</div>
	<?php } ?>
</div>
