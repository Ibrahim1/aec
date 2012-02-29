<?
/**
 * @version $Id: cancel.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Main Frontend
 * @copyright 2012 Copyright (C) David Deutsch
 * @author David Deutsch <skore@valanx.org> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.3 http://www.gnu.org/licenses/gpl.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' ) ?>

<div class="componentheading"><?php echo JText::_('CANCEL_TITLE') ?></div>

<?php $tmpl->custom( 'customtext_cancel' ) ?>

<?php if ( $tmpl->cfg['customtext_cancel_keeporiginal'] ) { ?>
	<div id="cancel_page">
		<p><?php echo JText::_('CANCEL_MSG') ?></p>
	</div>
<?php } ?>
<div class="aec_clearfix"></div>
