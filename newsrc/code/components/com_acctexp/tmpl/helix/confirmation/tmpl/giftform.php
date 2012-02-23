<?php
/**
 * @version $Id: giftform.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Main Frontend
 * @copyright 2012 Copyright (C) David Deutsch
 * @author David Deutsch <skore@valanx.org> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' ) ?>
<tr>
	<td class="giftinfo">
		<strong><?php echo JText::_('CHECKOUT_GIFT_HEAD') ?></strong>
	</td>
</tr>
<tr>
	<td class="giftdetails">
		<?php if ( !empty( $InvoiceFactory->invoice->params['target_user'] ) ) { ?>
			<p>This purchase will be gifted to: <?php echo $InvoiceFactory->invoice->params['target_username'] ?> (<a href="<?php echo AECToolbox::deadsureURL( 'index.php?option=com_acctexp&amp;task=InvoiceRemoveGiftConfirm&amp;invoice='.$InvoiceFactory->invoice->invoice_number, $tmpl->cfg['ssl_signup'] ) ?>">undo?</a>)</p>
			<input type="hidden" name="user_ident" value="<?php echo $InvoiceFactory->invoice->params['target_username'] ?>" />
		<?php } else { ?>
			<p><?php echo JText::_('CHECKOUT_GIFT_INFO') ?></p>
			<input type="text" size="20" name="user_ident" class="inputbox" value="" />
		<?php } ?>
	</td>
</tr>
