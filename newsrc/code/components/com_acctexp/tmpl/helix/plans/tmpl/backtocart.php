<?php
/**
 * @version $Id: backtocart.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Main Frontend
 * @copyright 2012 Copyright (C) David Deutsch
 * @author David Deutsch <skore@valanx.org> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.3 http://www.gnu.org/licenses/gpl.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' ) ?>
<div id="checkout">
	<table id="aec_checkout">
		<form name="confirmForm" action="<?php echo AECToolbox::deadsureURL( 'index.php?option=' . $option . '&task=cart', $tmpl->cfg['ssl_signup'] ) ?>" method="post">
			<div id="update_button">You can always go back to: <button type="submit" class="aec-btn"><?php echo JText::_('AEC_BTN_YOUR_CART') ?></button></div>
			<?php echo JHTML::_( 'form.token' ) ?>
		</form><br /><br />
	</table>
</div>
<div class="aec_clearfix"></div>
