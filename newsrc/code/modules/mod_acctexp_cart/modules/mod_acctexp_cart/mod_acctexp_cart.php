<?php
/**
 * @version $Id: mod_acctexp_cart.php
 * @package AEC - Account Control Expiration - Subscription component for Joomla! OS CMS
 * @subpackage Cart Module
 * @copyright 2011 Copyright (C) David Deutsch
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
	$lang =& JFactory::getLanguage();

	$lang->load( 'mod_acctexp_cart', JPATH_SITE );

	echo '<div class="aec_cart_module_inner' . $class_sfx . '">';

	if ( !empty( $pretext ) ) {
		echo $pretext;
	}

	$c = aecCartHelper::getCartbyUserid( $user->id );

	$metaUser = new metaUser( $user->id );

	$cart = $c->getCheckout( $metaUser );

	if ( empty( $c->content ) ) {
		?><p><?php echo JText::_('AEC_CART_MODULE_CART_EMPTY'); ?></p><?php
	} else {
		switch ( $mode ) {
			default:
			case 'abridged':
				$quantity = 0;
				$total = 0;
				foreach ( $cart as $bid => $bitem ) {
					if ( !empty( $bitem['obj'] ) ) {
						$quantity += $bitem['quantity'];
					} else {
						$total = $bitem['cost_total'];
					}
				}
				?><p><?php echo JText::_('AEC_CART_MODULE_ITEMS_IN_CART'); ?>: <?php echo $quantity; ?></p>
				<p><?php echo JText::_('AEC_CART_MODULE_ROW_TOTAL'); ?>: <?php echo $total; ?></p>
				<?php
				break;
			case 'full':
				?><table>
					<tr>
						<th><?php echo JText::_('AEC_CART_MODULE_ROW_ITEM'); ?></th>
						<th><?php echo JText::_('AEC_CART_MODULE_ROW_COST'); ?></th>
						<th><?php echo JText::_('AEC_CART_MODULE_ROW_AMOUNT'); ?></th>
						<th><?php echo JText::_('AEC_CART_MODULE_ROW_TOTAL'); ?></th>
					</tr><?php

					foreach ( $cart as $bid => $bitem ) {
						if ( !empty( $bitem['obj'] ) ) {
							?><tr>
								<td><?php echo $bitem['name']; ?></td>
								<td><?php echo $bitem['cost']; ?></td>
								<td><?php echo $bitem['quantity']; ?></td>
								<td><?php echo $bitem['cost_total']; ?></td>
							</tr><?php
						} else {
							?><tr>
								<td><strong><?php echo JText::_('AEC_CART_MODULE_ROW_TOTAL'); ?></strong></td>
								<td></td>
								<td></td>
								<td><strong><?php echo $bitem['cost_total']; ?></strong></td>
							</tr><?php
						}
					}
				?></table><?php
				break;
		}
	}

	if ( $button ) {
		global $aecConfig;

		?><a href="<?php echo AECToolbox::deadsureURL( 'index.php?option=com_acctexp&task=cart', $aecConfig->cfg['ssl_signup'] ); ?>"><img src="<?php echo JURI::root() . 'media/com_acctexp/images/site/your_cart_button.png'; ?>" /></a><?php
	}

	if ( !empty( $posttext ) ) {
		echo $posttext;
	}

	echo '</div>';
}

?>