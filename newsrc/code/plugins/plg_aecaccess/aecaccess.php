<?php
/**
 * @version $Id: aecaccess.php
 * @package AEC - Account Control Expiration - Joomla 1.5 Plugins
 * @subpackage Access
 * @copyright 2006-2015 Copyright (C) David Deutsch
 * @author David Deutsch <skore@valanx.org> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.3 http://www.gnu.org/licenses/gpl.html or, at your option, any later version
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport('joomla.plugin.plugin');

/**
 * AEC Authentication plugin
 *
 * @author David Deutsch <skore@valanx.org> & Team AEC - http://www.valanx.org
 * @package AEC Component
 */
class plgUserAECaccess extends JPlugin
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
	public function plgUserAECaccess( &$subject, $config ) {
		parent::__construct( $subject, $config );
	}

	public function onUserLogin( $user, $options )
	{
		return $this->onLoginUser( $user, null );
	}

	/**
	 * This method should handle any authentication and report back to the subject
	 *
	 * @access	public
	 * @param   array 	$credentials Array holding the user credentials
	 * @return	boolean
	 * @since 1.5
	 */
	public function onLoginUser( $credentials, $remember )
	{
		if ( strpos( JPATH_BASE, '/administrator' ) ) {
			// Don't act when entering admin area
			return true;
		}

		if ( file_exists( JPATH_ROOT."/components/com_acctexp/acctexp.class.php" ) ) {
			include_once( JPATH_ROOT."/components/com_acctexp/acctexp.class.php" );

			// process AEC verifications
			return $this->verify( $credentials );
		} else {
			return true;
		}
	}

	public function onUserLoginFailure( $response, $options=null )
	{
		return $this->onLoginFailure( $response, $options );
	}

	public function onLoginFailure( $response, $options=null )
	{
		$db = JFactory::getDBO();

		$query = 'SELECT id'
		. ' FROM #__users'
		. ' WHERE username = \'' . AEC_AUTH_ERROR_UNAME . '\''
		;
		$db->setQuery( $query );
		$id = $db->loadResult();

		$redirect = false;

		$aec_root = JURI::root() . 'index.php?option=com_acctexp&task=';

		switch ( AEC_AUTH_ERROR_MSG ) {
			case 'pending':
			case 'open_invoice':
				$redirect = $aec_root . 'pending&userid=' . $id;
				break;
			case 'expired':
				$redirect = $aec_root . 'expired&userid=' . $id;
				break;
			case 'hold':
				$redirect = $aec_root . 'hold&userid=' . $id;
				break;
			case 'subscribe':
				$redirect = $aec_root . 'subscribe&userid=' . $id;
				break;
			case 'blocked':
				JError::raiseWarning('SOME_ERROR_CODE', JText::_('JERROR_NOLOGIN_BLOCKED'));

				return false;
				break;
		}

		$app = JFactory::getApplication();

		$app->logout();

		if ( $redirect ) {
			$app->redirect( $redirect );
		}

		return true;
	}

	public function verify( $credentials )
	{
		global $aecConfig;

		$savetask = '';
		if ( isset( $_REQUEST['task'] ) ) {
			$_REQUEST['task'] = '';
			$savetask = $_REQUEST['task'];
		}

		$_REQUEST['task'] = $savetask;

		if ( !$aecConfig->cfg['require_subscription'] ) {
			return true;
		}

		$verification = AECToolbox::VerifyUser( $credentials['username'] );

		if ( $verification === true ) {
			return true;
		} else {
			define( 'AEC_AUTH_ERROR_MSG', $verification );
			define( 'AEC_AUTH_ERROR_UNAME', $credentials['username'] );

			$v = new JVersion();

			if ( $v->isCompatible('3.0') ) {
				$this->onUserLoginFailure(null);
			}

			return false;
		}
	}
}
