<?php
/**
 * @version $Id: list.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Main Frontend
 * @copyright 2012 Copyright (C) David Deutsch
 * @author David Deutsch <skore@valanx.org> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.3 http://www.gnu.org/licenses/gpl.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' ) ?>
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
				<td><?php echo $tmpl->lnk( array('task' => 'clearCartItem','item' => $bid), JText::_('CART_DELETE_ITEM') ) ?></td>
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
