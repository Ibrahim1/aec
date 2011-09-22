<?php
/**
 * @version $Id: aecuser.php
 * @package AEC - Account Control Expiration - Joomla 1.5 Plugins
 * @subpackage User
 * @copyright 2006-2010 Copyright (C) David Deutsch
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport('joomla.plugin.plugin');

/**
 * AEC User plugin
 *
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.valanx.org
 * @package AEC Component
 */
class plgUserAECuser extends JPlugin
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
	function plgUserAECuser(& $subject, $config) {
		parent::__construct($subject, $config);
	}


	/**
	 * Store user method - propagating the change on to the MI Handler
	 *
	 * Method is called after user data is stored in the database
	 *
	 * @param 	array		holds the new user data
	 * @param 	boolean		true if a new user is stored
	 * @param	boolean		true if user was succesfully stored in the database
	 * @param	string		message
	 */
	function onBeforeStoreUser($user, $isnew, $succes, $msg)
	{
		if ( file_exists( JPATH_ROOT.DS."components".DS."com_acctexp".DS."acctexp.class.php" ) ) {
			include_once( JPATH_ROOT.DS."components".DS."com_acctexp".DS."acctexp.class.php" );

			if ( $isnew ) {
				$trace = 'registration';
			} else {
				$trace = 'user';
			}

			$mih = new microIntegrationHandler();
			$mih->userchange( $user, $_POST, $trace );
		}
	}

}

?>
