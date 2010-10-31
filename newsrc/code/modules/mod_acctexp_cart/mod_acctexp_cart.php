<?php
/**
 * @version $Id: mod_acctexp_cart.php
 * @package AEC - Account Control Expiration - Subscription component for Joomla! OS CMS
 * @subpackage Cart Module
 * @copyright 2010 Copyright (C) David Deutsch
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' );

require_once( JApplicationHelper::getPath( 'class', 'com_acctexp' ) );

$class_sfx	= $params->get( 'moduleclass_sfx', "");
$pretext 	= $params->get( 'pretext' );
$posttext 	= $params->get( 'posttext' );
$mode		= $params->get( 'mode', 'abridged' );
$button		= $params->get( 'button', 1 );

$user = &JFactory::getUser();

if ( $user->id ) {

	echo '<div class="aec_cart_module_inner' . $class_sfx . '">';

	if ( !empty( $pretext ) ) {
		echo $pretext;
	}

	$cart = aecCartHelper::getCartbyUserid( $user->id );

	switch ( $mode ) {
		default:
		case 'abridged':
			?><p>Items in cart: <?php echo count( $cart ) - 1; ?></p>
			<p>Total:
			<?php
			break;
		case 'full':
			?><table>
				<tr>
					<th><?php echo _AEC_CART_MODULE_ROW_ITEM; ?></th>
					<th><?php echo _AEC_CART_MODULE_ROW_COST; ?></th>
					<th><?php echo _AEC_CART_MODULE_ROW_AMOUNT; ?></th>
					<th><?php echo _AEC_CART_MODULE_ROW_TOTAL; ?></th>
				</tr><?php
			foreach ( $cart as $bid => $bitem ) {
				if ( !empty( $bitem['name'] ) ) {
					?><tr>
						<td><?php echo $bitem['name']; ?></td>
						<td><?php echo $bitem['cost']; ?></td>
						<td><?php echo $bitem['quantity']; ?></td>
						<td><?php echo $bitem['cost_total']; ?></td>
					</tr><?php
				} else {
					?><tr>
						<td><strong><?php echo _AEC_CART_MODULE_ROW_TOTAL; ?></strong></td>
						<td></td>
						<td></td>
						<td><strong><?php echo $bitem['cost']; ?></strong></td>
					</tr><?php
				}
			}

			?></table><?php

			break;
	}

	if ( $button ) {
		?><a href="<?php echo AECToolbox::deadsureURL( 'index.php?option=' . $option . '&task=cart', $aecConfig->cfg['ssl_signup'] ); ?>"><?php echo JURI::root() . 'media/com_acctexp/images/site/your_cart_button.png'; ?></a><?php
	}

	if ( !empty( $posttext ) ) {
		echo $posttext;
	}

	echo '</div>';
}

?>