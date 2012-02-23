<?
/**
 * @version $Id: upgrade_button.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Main Frontend
 * @copyright 2012 Copyright (C) David Deutsch
 * @author David Deutsch <skore@valanx.org> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' ) ?>
<div id="upgrade_button">
<?php echo $tmpl->btn( array(	'task' => 'renewSubscription',
								'userid' => $metaUser->userid
								), JText::_('PENDING_NOINVOICE_BUTTON') ) ?>
</div>
