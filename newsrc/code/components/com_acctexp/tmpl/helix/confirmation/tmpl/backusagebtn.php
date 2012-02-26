<?php
/**
 * @version $Id: backusagebtn.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Main Frontend
 * @copyright 2012 Copyright (C) David Deutsch
 * @author David Deutsch <skore@valanx.org> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.3 http://www.gnu.org/licenses/gpl.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' ) ?>
<form class="aectextright" name="backFormUserPlan" action="<?php echo AECToolbox::deadsureURL( 'index.php?option=' . $option, $tmpl->cfg['ssl_signup'] ) ?>" method="post">
	<input type="hidden" name="option" value="<?php echo $option ?>" />
	<input type="hidden" name="userid" value="<?php echo $userid ? $userid : 0 ?>" />
	<input type="hidden" name="task" value="subscribe" />
	<input type="hidden" name="forget" value="usage" />
	<?php if ( $passthrough != false ) { ?>
		<input type="hidden" name="aec_passthrough" value="<?php echo $InvoiceFactory->getPassthrough( 'usage' ) ?>" />
	<?php } ?>
	<button class="aeclink" type="submit"><span><?php echo JText::_('CONFIRM_DIFFERENT_ITEM') ?></span></button>
	<?php echo JHTML::_( 'form.token' ) ?>
</form>
