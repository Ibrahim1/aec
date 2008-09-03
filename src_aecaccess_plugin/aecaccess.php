<?php
/**
 * @version $Id: aecaccess.php
 * @package AEC - Account Control Expiration - Joomla 1.5 Plugins
 * @subpackage Access
 * @copyright 2006-2008 Copyright (C) David Deutsch
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.globalnerd.org
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport('joomla.event.plugin');

/**
 * AEC Authentication plugin
 *
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.globalnerd.org
 * @package AEC Component
 */
class plgAuthenticationAECaccess extends JPlugin
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
	function plgAuthenticationAECaccess(& $subject, $config) {
		parent::__construct($subject, $config);
	}

	/**
	 * This method should handle any authentication and report back to the subject
	 *
	 * @access	public
	 * @param   array 	$credentials Array holding the user credentials
	 * @param 	array   $options     Array of extra options
	 * @param	object	$response	 Authentication response object
	 * @return	boolean
	 * @since 1.5
	 */
	function onAuthenticate( $credentials, $options, &$response )
	{
		jimport('joomla.user.helper');

		// ---- Modified Copy of joomla access plugin until we have authorization layer ----
		// COPY START

		// Joomla does not like blank passwords
		if (empty($credentials['password']))
		{
			$response->status = JAUTHENTICATE_STATUS_FAILURE;
			$response->error_message = 'Empty password not allowed';
			return false;
		}

		// Initialize variables
		$conditions = '';

		// Get a database object
		$db =& JFactory::getDBO();

		$query = 'SELECT `id`, `password`, `gid`'
			. ' FROM `#__users`'
			. ' WHERE username=' . $db->Quote( $credentials['username'] )
			;
		$db->setQuery( $query );
		$result = $db->loadObject();

		if($result)
		{
			$parts	= explode( ':', $result->password );
			$crypt	= $parts[0];
			$salt	= @$parts[1];
			$testcrypt = JUserHelper::getCryptedPassword($credentials['password'], $salt);

			if ($crypt == $testcrypt) {
				$user = JUser::getInstance($result->id); // Bring this in line with the rest of the system
				$response->email = $user->email;
				$response->fullname = $user->name;
				$response->status = JAUTHENTICATE_STATUS_SUCCESS;
				$response->error_message = '';
			} else {
				$response->status = JAUTHENTICATE_STATUS_FAILURE;
				$response->error_message = 'Invalid password';
				return false;
			}
		}
		else
		{
			$response->status = JAUTHENTICATE_STATUS_FAILURE;
			$response->error_message = 'User does not exist';
			return false;
		}

		// COPY END
		// ---- Copy of joomla access plugin until we have authorization layer ----

		if ( strpos( JPATH_BASE, '/administrator' ) ) {
			$response->status = JAUTHENTICATE_STATUS_SUCCESS;
			return true;
		}

		$savetask = '';
		if ( isset( $_REQUEST['task'] ) ) {
			$_REQUEST['task'] = '';
			$savetask = $_REQUEST['task'];
		}

		include_once( JPATH_ROOT .DS.'components'.DS.'com_acctexp'.DS.'acctexp.php' );

		$_REQUEST['task'] = $savetask;

		$verification = AECToolbox::VerifyUser( $credentials['username'] );

		if ( $verification === true ) {
			$response->status = JAUTHENTICATE_STATUS_SUCCESS;
		} else {
			$this->_error = $verification;
			define( 'AEC_AUTH_ERROR_MSG', $this->_error );
			define( 'AEC_AUTH_ERROR_UNAME', $credentials['username'] );
			$response->status = JAUTHENTICATE_STATUS_FAILURE;
		}
	}

	function onLoginFailure( &$response )
	{
		$db =& JFactory::getDBO();

		$query = 'SELECT id'
		. ' FROM #__users'
		. ' WHERE username = \'' . AEC_AUTH_ERROR_UNAME . '\''
		;
		$db->setQuery( $query );
		$id = $db->loadResult();

		$redirect = false;

		switch( AEC_AUTH_ERROR_MSG ) {
			case 'pending':
			case 'open_invoice':
				$redirect = JURI::base( true ) . '/index.php?option=com_acctexp&task=pending&userid=' . $id;
				break;
			case 'expired':
				$redirect = JURI::base( true ) . '/index.php?option=com_acctexp&task=expired&userid=' . $id ;
				break;
		}

		if ( $redirect ) {
			$app =& JFactory::getApplication();
			$app->redirect( $redirect );
		}
	}
}

?>
