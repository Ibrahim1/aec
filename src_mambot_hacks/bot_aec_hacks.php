<?php
/**
 * @version $Id: bot_aec_hacks.php
 * @package AEC - Account Control Expiration - Joomla 1.0 Plugins
 * @subpackage Hacks Bot
 * @copyright 2006-2008 Copyright (C) David Deutsch
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */

( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' );

if ( !defined( '_JEXEC' ) ) {

$aecClass = JPATH_SITE . "/components/com_acctexp/acctexp.class.php";

if( file_exists( $aecClass ) ) {
	include_once( $aecClass );

	$_MAMBOTS->registerFunction( 'onAfterStart', 'aecBotRouting' );

function aecBotRouting()
{
	global $option, $aecConfig;

	$task	= mosGetParam( $_REQUEST, 'task', '' );
	$usage	= aecGetParam( 'usage', 0, true, array( 'word', 'string', 'clear_nonalnum' ) );
	$submit	= mosGetParam( $_POST, 'submit', '' );

	$username = aecGetParam( 'username', true, array( 'string', 'clear_nonalnum' ) );

	$nu		= $usage == 0;

	$creg	= $option == 'com_registration';
	$ccb	= $option == 'com_comprofiler';
	$cu		= $option == 'com_user';
	$olo	= $option == 'login';
	$acc	= $option == 'com_acctexp';

	$treg	= $task == 'register';
	$tregs	= $task == 'registers';
	$tcregs	= $task == 'saveregisters';
	$tsregs	= $task == 'saveRegistration';
	$tsue	= $task == 'saveUserEdit';
	$tlo	= $task == 'login';
	$tcon	= $task == 'confirm';

	$joomreg	= ( $creg && $treg );
	$cbreg		= ( $ccb && $tregs );
	$cbsreg		= ( $ccb && ( $tcregs || $tsue ) );

	$pfirst		= $aecConfig->cfg['plans_first'];
	$intreg		= $aecConfig->cfg['integrate_registration'];

	if ( ( $joomreg || $cbreg ) && $intreg ) {
		// Joomla or CB registration...
		if ( ( $pfirst && !$nu ) || ( !$pfirst && $nu ) ) {
			// Plans First and selected or not first and not selected
			// Both cases = redirect to AEC on the next page

			$option = "com_acctexp";

			$_REQUEST['option'] = $option;
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
		AECToolbox::VerifyUsername( $username );
	}
}

}

}

?>
