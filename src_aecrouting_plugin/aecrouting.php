<?php
/**
 * @version $Id: bot_aec_hacks.php
 * @package AEC - Account Control Expiration - Joomla 1.5 Plugins
 * @subpackage Hacks Plugin
 * @copyright 2006-2008 Copyright (C) David Deutsch
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.globalnerd.org
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport('joomla.event.plugin');

include_once( JPATH_SITE . "/components/com_acctexp/acctexp.class.php" );

$_MAMBOTS->registerFunction( 'onAfterStart', 'aecBotRouting' );

/**
 * AEC Authentication plugin
 *
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.globalnerd.org
 * @package AEC Component
 */
class plgSystemAECrouting extends JPlugin
{

	/**
	 * Constructor
	 *
	 * For php4 compatability we must not use the __constructor as a constructor for plugins
	 * because func_get_args ( void ) returns a copy of all passed arguments NOT references.
	 * This causes problems with cross-referencing necessary for the observer design pattern.
	 *
	 * @param object $subject The object to observe
	 * @param array  $config  An array that holds the plugin configuration
	 * @since 1.5
	 */
	function plgSystemAECrouting( &$subject, $config ) {
		parent::__construct( $subject, $config );
	}


function onAfterInitialise ()
{
	global $option, $aecConfig;

	$task	= mosGetParam( $_REQUEST, 'task', '' );
	$usage	= intval( mosGetParam( $_POST, 'usage', '0' ) );
	$submit	= mosGetParam( $_POST, 'submit', '' );

	$username = aecGetParam( 'username', true, array( 'string', 'clear_nonalnum' ) );

	$nu		= $usage == 0;

	$ccb	= $option == 'com_comprofiler';
	$cu		= $option == 'com_user';

	$treg	= $task == 'register';
	$tregs	= $task == 'registers';
	$tcregs	= $task == 'saveregisters';
	$tsregs	= $task == 'saveRegistration';
	$tsue	= $task == 'saveUserEdit';

	$cbreg		= ( $ccb && $tregs );
	$cbsreg		= ( $ccb && ( $tcregs || $tsue ) );

	$pfirst		= $aecConfig->cfg['plans_first'];

	if ( ( $treg || $cbreg ) && $aecConfig->cfg['integrate_registration'] ) {
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
	} elseif ( $cbsreg ) {
		// Any kind of user profile edit = trigger MIs

		$row = new stdClass();
		$row->username = $username;

		$mih = new microIntegrationHandler();
		$mih->userchange( $row, $_POST, 'registration' );
	}

}

}

?>
