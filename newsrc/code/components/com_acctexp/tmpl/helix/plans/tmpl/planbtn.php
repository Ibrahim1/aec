<?
/**
 * @version $Id: planbtn.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Main Frontend
 * @copyright 2012 Copyright (C) David Deutsch
 * @author David Deutsch <skore@valanx.org> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' ) ?>
<h2><?php echo $litem['name'] ?></h2>
<p><?php echo $litem['desc'] ?></p>
<div class="aec_groupbutton">
	<?php echo Payment_HTML::planpageButton( $option, 'subscribe', '', JURI::root(true) . '/media/com_acctexp/images/site/select_button.png', array( array( 'group', $litem['id'] ) ), $userid, $passthrough ) ?>
</div>
