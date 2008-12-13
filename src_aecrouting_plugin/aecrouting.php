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

if ( !class_exists( 'mosDBTable' ) ) {
	// We have a problem - the legacy bot is not published (yet).
	// Issue error end do not load anything.

	$db =& JFactory::getDBO();

	$date	= date( 'Y-m-d H:i:s' );
	$short	= 'Plugin could not be loaded';
	$tags	= 'system,plugins,fatal';
	$event	= 'One of the AEC Plugins could not be loaded because the Legacy Plugin not published or published after AEC plugins. MUST be published before AEC plugins!';
	$level	= 128;
	$notify	= 1;

	$query = 'INSERT INTO #__acctexp_eventlog'
			. ' (`datetime`, `short`, `tags`, `event`, `level`, `notify` )'
			. ' VALUES (\'' . $date . '\', \'' . $short . '\', \'' . $tags . '\', \'' . $event . '\', \'' . $level . '\', \'' . $notify . '\')';

	$db->setQuery( $query );
	$db->query();
} else {

jimport('joomla.event.plugin');

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
		if ( file_exists( JPATH_ROOT.DS."components".DS."com_acctexp".DS."acctexp.class.php" ) ) {
			include_once( JPATH_ROOT.DS."components".DS."com_acctexp".DS."acctexp.class.php" );

			global $aecConfig;

			$task	= mosGetParam( $_REQUEST, 'task', '' );
			$option	= mosGetParam( $_REQUEST, 'option', '' );

			if ( empty( $task ) ) {
				$task = JRequest::getVar( 'task', null );
			}

			if ( empty( $option ) ) {
				$option = JRequest::getVar( 'option', null );
			}

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
					global $mainframe;
					$mainframe->redirect( 'index.php?option=com_acctexp&task=subscribe' );
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

}

}

?>
