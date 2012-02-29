<?
/**
 * @version $Id: cart.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Main Frontend
 * @copyright 2012 Copyright (C) David Deutsch
 * @author David Deutsch <skore@valanx.org> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.3 http://www.gnu.org/licenses/gpl.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' ) ?>

<div class="componentheading"><?php echo JText::_('CART_TITLE') ?></div>
<div id="confirmation">
	<?php
	@include( $tmpl->tmpl( 'info' ) );
	if ( !empty( $InvoiceFactory->cart ) ) {
		@include( $tmpl->tmpl( 'form' ) );
	} ?>
</div>
