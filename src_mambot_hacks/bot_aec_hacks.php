<?php
/**
 * @version $Id: bot_aec_hacks.php
 * @package AEC - Account Control Expiration - Joomla 1.0 Plugins
 * @subpackage Hacks Bot
 * @copyright 2006-2008 Copyright (C) David Deutsch
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.globalnerd.org
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */

( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' );

global $mosConfig_absolute_path;

if ( defined( 'JPATH_SITE' ) ) {
	$mosConfig_absolute_path = JPATH_SITE;
}

include_once( $mosConfig_absolute_path . "/components/com_acctexp/acctexp.class.php" );

$_MAMBOTS->registerFunction( 'onAfterStart', 'aecBotRouting' );

function aecBotRouting()
{
	global $option, $aecConfig;

	$task	= mosGetParam( $_REQUEST, 'task', '' );
	$usage	= intval( mosGetParam( $_POST, 'usage', '0' ) );
	$submit	= mosGetParam( $_POST, 'submit', '' );

	$username = aecGetParam( 'username', true, array( 'string', 'clear_nonalnum' ) );

	$nu		= $usage == 0;

	$creg	= $option == 'com_registration';
	$ccb	= $option == 'com_comprofiler';
	$cu		= $option == 'com_user';
	$olo	= $option == 'login';

	$treg	= $task == 'register';
	$tregs	= $task == 'registers';
	$tcregs	= $task == 'saveregisters';
	$tsregs	= $task == 'saveRegistration';
	$tsue	= $task == 'saveUserEdit';
	$tlo	= $task == 'login';

	$joomreg	= ( $creg && $treg );
	$cbreg		= ( $ccb && $tregs );
	$cbsreg		= ( $ccb && ( $tcregs || $tsue ) );

	$pfirst		= $aecConfig->cfg['plans_first'];

	if ( ( $joomreg || $cbreg ) && $aecConfig->cfg['integrate_registration'] ) {
		// Joomla or CB registration...
		if ( ( $pfirst && !$nu ) || ( !$pfirst && $nu ) ) {
			// Plans First and selected or not first and not selected
			// Both cases = redirect to AEC on the next page
			$_REQUEST['option'] = "com_acctexp";
			// Just to be sure
			$option = "com_acctexp";
		} elseif ( $pfirst && $nu ) {
			// Plans first and not yet selected
			// Immediately redirect to plan selection
			mosRedirect( sefRelToAbs( 'index.php?option=com_acctexp&task=subscribe' ) );
		}
	} elseif ( ( $creg && $tsregs ) || ( $cu && $tsue ) || $cbsreg ) {
		// Any kind of user profile edit = trigger MIs

		$row = new stdClass();
		$row->username = $username;

		$mih = new microIntegrationHandler();
		$mih->userchange( $row, $_POST, 'registration' );
	}

	if ( $olo || ( $ccb && $tlo ) ) {
		$verification = AECToolbox::VerifyUsername( $username );

		if ( $verification === false ) {
			// No Login for you. General purpose block.
			die('Invalid Access.');
		}
	}
}

?>
