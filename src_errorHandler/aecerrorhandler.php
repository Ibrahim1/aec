<?php
/**
 * @version $Id: aecErrorHandler.php
 * @package AEC - Account Control Expiration - Joomla 1.5 Plugins
 * @subpackage User
 * @copyright 2006-2008 Copyright (C) David Deutsch
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */

// Check to ensure this file is included in Joomla!
defined( '_JEXEC' ) or die( 'Restricted access' );

// Import library dependencies
jimport('joomla.event.plugin');

/**
 * AEC Error Handler
 *
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.valanx.org
 * @package AEC Component
 */
class plgSystemAECerrorhandler extends JPlugin
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
	function plgSystemAECerrorhandler( &$subject, $config )
	{
		parent::__construct( $subject, $config );
	}

	function onAfterRender()
	{
		if ( strpos( JPATH_BASE, '/administrator' ) ) {
			// Don't act when on backend
			return true;
		}

		if ( file_exists( JPATH_ROOT.DS."components".DS."com_acctexp".DS."acctexp.class.php" ) ) {
			$document	=& JFactory::getDocument();
//print_r($document);print_r($this);exit;
			if ( $document->getType() == 'error' ) {
				if ( $this->_error->code == '403' ) {
					$this->redirectNotAllowed( $this->_error );
				}
			}
		}
	}

	function onAfterInitialise()
	{
		global $mainframe;

		if ( strpos( JPATH_BASE, '/administrator' ) ) {
			// Don't act when on backend
			return true;
		}

		if ( file_exists( JPATH_ROOT.DS."components".DS."com_acctexp".DS."acctexp.class.php" ) ) {
			// handle login redirect
			$this->handleLoginRedirect();
		}
	}

	/**
	 * check if we are at the login page & there is a return URI set.
	 * if so, check if the return was to com_content (regarless of the view) & redirect to NotAllowed.
	 */
	function handleLoginRedirect()
	{
		global $mainframe;

		$uri	= &JFactory::getURI();
		$task	= $uri->getVar( 'task' );
		$option	= $uri->getVar( 'option' );
		$view	= $uri->getVar( 'view' );

		if ( empty( $task ) ) {
			$task	= JRequest::getVar( 'task', null );
		}

		if ( empty( $option ) ) {
			$option	= JRequest::getVar( 'option', null );
		}

		if ( empty( $view ) ) {
			$view	= JRequest::getVar( 'view', null );
		}

		if ( $return = JRequest::getVar( 'return', '', 'method', 'base64' ) )
		{
			$return = base64_decode( $return );

			if ( function_exists( 'JURI::isInternal' ) )
			{
				if ( !JURI::isInternal( $return ) ) {
					$return = '';
				}
			} else {
				// Copied for pre-1.5.7 compatibility
				$uri =& JURI::getInstance($return);
				$base = $uri->toString(array('scheme', 'host', 'port', 'path'));
				$host = $uri->toString(array('scheme', 'host', 'port'));
				if ( ( strpos( strtolower($base), strtolower(JURI::base()) ) !== 0 ) && !empty($host) ) {
					$return = '';
				}
			}
		}

		if( ( $option == 'com_user' ) && ( $view == 'login' ) && ( $return!='' ) )
		{
			$uri = new JURI( $return );
			$option = $uri->getVar( 'option' );

			if ( $option == 'com_content' ) {
				$error = new stdClass();
				$error->code = 403;

				$this->redirectNotAllowed( $error );
			}
		}
	}


	function redirectNotAllowed( $error )
	{
		if ( $error->code == 403 ) {
			global $mainframe;

			$mainframe->redirect( "index.php?option=com_acctexp&task=NotAllowed" );
		} else {
			JError::customErrorPage( $error );
		}
	}

}

?>
