<?php
/**
 * @version $Id: bot_aec.php
 * @package AEC - Account Control Expiration - Subscription component for Joomla! OS CMS
 * @subpackage Mambot
 * @copyright Copyright (C) 2004-2007, All Rights Reserved, David Deutsch
 * @author David Deutsch <skore@skore.de> - http://www.gobalnerd.org
 * @license GNU/GPL v.2 http://www.gnu.org/copyleft/gpl.html
 */

defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );
$_MAMBOTS->registerFunction( 'onAfterStart', 'checkLoginLinkForIntrusion' );

function checkLoginLinkForIntrusion ()
{
	$option = mosGetParam($_REQUEST, "option", '');
	$task	 = mosGetParam($_REQUEST, "task", '');

	if( trim(strtolower($option)) == 'com_registration' && trim(strtolower($task)) == 'register' ) {
		mosRedirect(sefRelToAbs("index.php?option=com_acctexp&task=subscribe&itemid=0"));
	}
}


?>