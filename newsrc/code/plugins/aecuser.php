<?php
/**
 * @version $Id: aecuser.php
 * @package AEC - Account Control Expiration - Joomla 1.5 Plugins
 * @subpackage User
 * @copyright 2006-2013 Copyright (C) David Deutsch
 * @author David Deutsch <skore@valanx.org> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.3 http://www.gnu.org/licenses/gpl.html or, at your option, any later version
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport('joomla.plugin.plugin');

/**
 * AEC User plugin
 *
 * @author David Deutsch <skore@valanx.org> & Team AEC - http://www.valanx.org
 * @package AEC Component
 */
class plgUserAECuser extends JPlugin
{
	function plgUserAECuser(& $subject, $config) {
		parent::__construct($subject, $config);
	}

	function onUserBeforeSave( $user, $isnew, $new )
	{
		$this->onBeforeStoreUser( $user, $isnew );
	}

	function onBeforeStoreUser( $user, $isnew )
	{
		if ( file_exists( JPATH_ROOT."/components/com_acctexp/acctexp.class.php" ) ) {
			include_once( JPATH_ROOT."/components/com_acctexp/acctexp.class.php" );

			if ( !$isnew ) {
				$mih = new microIntegrationHandler();
				$mih->userchange( $user, $_POST, 'user' );
			}
		}
	}

	function onUserAfterSave( $user, $isnew, $success, $msg )
	{
		$this->onAfterStoreUser( $user, $isnew, $success, $msg );
	}

	function onAfterStoreUser( $user, $isnew, $success, $msg )
	{
		if ( file_exists( JPATH_ROOT."/components/com_acctexp/acctexp.class.php" ) ) {
			include_once( JPATH_ROOT."/components/com_acctexp/acctexp.class.php" );

			if ( $isnew ) {
				$mih = new microIntegrationHandler();
				$mih->userchange( $user, $_POST, 'registration' );
			}
		}
	}

}

?>
