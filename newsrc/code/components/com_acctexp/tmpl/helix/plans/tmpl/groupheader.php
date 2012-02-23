<?
/**
 * @version $Id: groupheader.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Main Frontend
 * @copyright 2012 Copyright (C) David Deutsch
 * @author David Deutsch <skore@valanx.org> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' ) ?>
<?
// Double check that it's not the root group
if ( $list['group']['id'] > 1 ) { ?>
	<div class="aec_group_backlink">
		<?php echo Payment_HTML::planpageButton( $option, 'subscribe', '', JURI::root(true) . '/media/com_acctexp/images/site/back_button.png', array(), $userid, $passthrough, 'func_button' ) ?>
	</div>
	<h2><?php echo $list['group']['name'] ?></h2>
	<p><?php echo $list['group']['desc'] ?></p>
	<?
}
 ?>
