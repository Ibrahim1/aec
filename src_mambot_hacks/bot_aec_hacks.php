<?php
/**
 * @version $Id: bot_aec_hacks.php
 * @package AEC - Account Control Expiration - Joomla 1.0 Plugins
 * @subpackage Hacks Bot
 * @copyright 2006-2008 Copyright (C) David Deutsch
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.globalnerd.org
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */

defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

		die('MtK');
if (file_exists( $mosConfig_absolute_path . "/components/com_acctexp/acctexp.class.php")) {
	include_once($mosConfig_absolute_path . "/components/com_acctexp/acctexp.class.php");
	if( aecJoomla15check() ) {
//		die('MtK');
		$_MAMBOTS->registerFunction( 'onAfterStart', 'checkUserSubscription' ); //joomla.php Hack #4
		$_MAMBOTS->registerFunction( 'onAfterStart', 'planFirst' ); //registration.php Hack #6
		$_MAMBOTS->registerFunction( 'onAfterStart', 'planRegistration' ); //registration.php Hack #2 + comprofiler.php Hack #2
		$_MAMBOTS->registerFunction( 'onAfterStart', 'notifyMI' ); //registration.php Hack #1 + user.php Hack + comprofiler.php Hack #7 + comprofiler.php Hack #8
	}
}

//Some Micro Integrations rely on receiving a cleartext password for each user. This hack will make sure that the Micro Integrations will be notified
function notifyMI()
{
	global $mosConfig_absolute_path, $option;

	$task = mosGetParam( $_REQUEST, 'task', '' );

	if (($option == 'com_registration' && $task == 'saveRegistration')
		||
		($option == 'com_user' && $task == 'saveUserEdit')
		||
		($option == 'com_comprofiler' && $task == 'saveregisters')
		||
		($option == 'com_comprofiler' && strcasecmp($task,'saveUserEdit')==0)
		)
	{
		if (file_exists( $mosConfig_absolute_path . "/components/com_acctexp/acctexp.class.php")) {
			include_once($mosConfig_absolute_path . "/components/com_acctexp/acctexp.class.php");
			$username = mosGetParam($_REQUEST, 'username', '');
			$row = new stdClass();
			$row->username = $username;
			$mih = new microIntegrationHandler();
			$mih->userchange($row, $_POST, 'registration');
		}
	}
}

//This will redirect a registering user to the payment plans after filling out the registration form (and in CB).
function planRegistration()
{
	global $mosConfig_absolute_path, $option;

	$task = mosGetParam( $_REQUEST, 'task', '' );
	$usage = intval(mosGetParam( $_POST, 'usage', '0' ));

	if ($usage == 0 && (($option == 'com_registration' && $task == 'register') || ($option == 'com_comprofiler' && $task == 'registers')))
	{
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

	if (($option == 'com_registration' && $task == 'register') || ($option == 'com_comprofiler' && $task == 'registers'))
	{
		if (file_exists( $mosConfig_absolute_path . "/components/com_acctexp/acctexp.class.php")) {
			include_once($mosConfig_absolute_path . "/components/com_acctexp/acctexp.class.php");
			if($aecConfig->cfg['plans_first']){
				$usage = intval(mosGetParam($_POST,'usage','0'));
				if($usage == 0 || $option != 'com_comprofiler')
					mosRedirect( sefRelToAbs( 'index.php?option=com_acctexp&task=subscribe' ) );
			}
		}
	}
}

//This will make sure a user has a subscription in order to log in - Joomla hack#4
function checkUserSubscription()
{
	global $mosConfig_absolute_path, $option;

	$task = mosGetParam( $_REQUEST, 'task', '' );
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
