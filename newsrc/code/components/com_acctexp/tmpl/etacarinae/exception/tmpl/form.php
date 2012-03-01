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
<div id="checkout-box">
	<h5><?php echo JText::_('CONFIRM_TITLE') ?></h5>
	<div class="checkout-action">
		<input type="hidden" name="option" value="<?php echo $option ?>" />
		<input type="hidden" name="task" value="addressException" />
		<?
		if ( !empty( $InvoiceFactory->invoice->invoice_number ) ) {
			?><input type="hidden" name="invoice" value="<?php echo $InvoiceFactory->invoice->invoice_number ?>" /><?
		}
		if ( !empty( $InvoiceFactory->cartobject->id ) ) {
			?><input type="hidden" name="cart" value="<?php echo $InvoiceFactory->cartobject->id ?>" /><?
		}
		?>
		<input type="hidden" name="userid" value="<?php echo $InvoiceFactory->metaUser->userid ?>" />
		<input type="submit" class="button btn" value="<?php echo JText::_('BUTTON_CONFIRM') ?>" />
	</div>
</div>
