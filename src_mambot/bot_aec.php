<?php
/**
 * @version $Id: bot_aec.php
 * @package AEC - Account Control Expiration - Subscription component for Joomla! OS CMS
 * @subpackage Mambot
 * @copyright 2006-2008 Copyright (C) David Deutsch
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.globalnerd.org 
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */

defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );
$_MAMBOTS->registerFunction( 'onAfterStart', 'checkLoginLinkForIntrusion' );

function checkLoginLinkForIntrusion ()
{
	$option = mosGetParam($_REQUEST, "option", '');
	$task	= mosGetParam($_REQUEST, "task", '');

	if( trim(strtolower($option)) == 'com_registration' && trim(strtolower($task)) == 'register' ) {
		mosRedirect(sefRelToAbs("index.php?option=com_acctexp&task=subscribe&itemid=0"));
	}
}


?>
