<?php
/**
 * @version $Id: bot_aec_hacks.php
 * @package AEC - Account Control Expiration - Subscription component for Joomla! OS CMS
 * @subpackage Mambot
 * @copyright Copyright (C) 2004-2007, All Rights Reserved, David Deutsch
 * @author Mati Kochen <mtk@smartmtk.com> - http://www.smartmtk.com
 * @license GNU/GPL v.2 http://www.gnu.org/copyleft/gpl.html
 */

defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );
$_MAMBOTS->registerFunction( 'onAfterStart', 'checkUserSubscription' );

//This will make sure a user has a subscription in order to log in.
function checkUserSubscription()
{
	global $mosConfig_live_site, $mosConfig_absolute_path, $option, $mainframe;
	
	$submit = mosGetParam($_POST,'submit', '');
	if ($option == 'login' ||
         ($option == 'com_comprofiler' && $task == 'login') &&
         ($submit == 'Login')) { 
		if (file_exists( $mosConfig_absolute_path . "/components/com_acctexp/acctexp.class.php")) {
			include_once($mosConfig_absolute_path . "/components/com_acctexp/acctexp.class.php");
			$username = mosGetParam($_POST,'username', '');
			$verification = AECToolbox::VerifyUser( $username );
die($verification);
			if ( $verification === true ) {
				$status = JAUTHENTICATE_STATUS_SUCCESS;
			} else {
				define( 'AEC_AUTH_ERROR_MSG', $verification );
				define( 'AEC_AUTH_ERROR_UNAME', $username );
				$status = JAUTHENTICATE_STATUS_FAILURE;
				onLoginFailure();
			}
		}
	}
}

function onLoginFailure()
{
	global $database, $mainframe;
	
	$query = 'SELECT id'
	. ' FROM #__users'
	. ' WHERE username = \'' . AEC_AUTH_ERROR_UNAME . '\''
	;
	$database->setQuery( $query );
	$id = $database->loadResult();

	$redirect = false;
	
	switch( AEC_AUTH_ERROR_MSG ) {
		case 'open_invoice':
			$redirect = 'pending';
			break;
		default:
			$redirect = AEC_AUTH_ERROR_MSG;
			break;
	}
	
	if ( $redirect ) {
		$url = "index.php?option=com_acctexp&task=$redirect&userid=$id";
		mosRedirect( sefRelToAbs( $url ) );
	}
}



?>