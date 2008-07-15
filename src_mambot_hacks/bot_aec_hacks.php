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
$_MAMBOTS->registerFunction( 'onAfterStart', 'checkUserSubscription' ); //joomla.php Hack #4
$_MAMBOTS->registerFunction( 'onAfterStart', 'planFirst' ); //registration.php Hack #6
$_MAMBOTS->registerFunction( 'onAfterStart', 'planRegistration' ); //registration.php Hack #2

//This will redirect a registering user to the payment plans after filling out the registration form. Leave this alone to have plan selection only on login (if 'Require Subscription' is active), or completely voluntary (without requiring a subscription).
function planRegistration()
{
	global $mosConfig_absolute_path, $option;
	
	$task = mosGetParam( $_REQUEST, 'task', '' );

	if ($option == 'com_registration' && $task == 'register'){
		if (file_exists( $mosConfig_absolute_path . "/components/com_acctexp/acctexp.class.php")) {
			$option = "com_acctexp";
		}
	}
}

//This will make the Plans First feature possible - you need to set the switch for this in the settings as well! 
function planFirst()
{
	global $mosConfig_absolute_path, $option;
	
	$task = mosGetParam( $_REQUEST, 'task', '' );
	
	if ($option == 'com_registration' && $task == 'register'){
		if (file_exists( $mosConfig_absolute_path . "/components/com_acctexp/acctexp.class.php")) {
			include_once($mosConfig_absolute_path . "/components/com_acctexp/acctexp.class.php");
			if($aecConfig->cfg['plans_first']){
				mosRedirect( sefRelToAbs( 'index.php?option=com_acctexp&task=subscribe' ) );
			}
		}
	}
}

//This will make sure a user has a subscription in order to log in - Joomla hack#4
function checkUserSubscription()
{
	global $mosConfig_absolute_path, $option;
	
	$submit = mosGetParam($_POST,'submit', '');
	if ($option == 'login' ||
         ($option == 'com_comprofiler' && $task == 'login') &&
         ($submit == 'Login')) { 
		if (file_exists( $mosConfig_absolute_path . "/components/com_acctexp/acctexp.class.php")) {
			include_once($mosConfig_absolute_path . "/components/com_acctexp/acctexp.class.php");
			
			$username = mosGetParam($_POST,'username', '');
			$verification = AECToolbox::VerifyUser( $username );

			if ( $verification !== true ) {
				define( 'AEC_AUTH_ERROR_MSG', $verification );
				define( 'AEC_AUTH_ERROR_UNAME', $username );
				onLoginFailure();
			}
		}
	}
}

function onLoginFailure()
{
	global $database;
	
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