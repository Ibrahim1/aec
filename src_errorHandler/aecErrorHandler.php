<?php
/**
 * @version $Id: aecErrorHandler.php
 * @package AEC - Account Control Expiration - Joomla 1.5 Plugins
 * @subpackage User
 * @copyright 2006-2008 Copyright (C) David Deutsch
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.globalnerd.org
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */


// Check to ensure this file is included in Joomla!
defined( '_JEXEC' ) or die( 'Restricted access' );

// Import library dependencies
jimport('joomla.event.plugin');


/**
 * AEC Error Handler
 *
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.globalnerd.org
 * @package AEC Component
 */
class plgSystemAECErrorHandler extends JPlugin
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
	function plgSystemAECErrorHandler(& $subject, $config) 
	{
		parent::__construct($subject, $config);
	}
	
	function onAfterInitialise()
	{
		global $mainframe;
		
		if (file_exists( JPATH_ROOT.DS."components".DS."com_acctexp".DS."acctexp.class.php" )) {
		
			// handle login redirect
			$this->handleLoginRedirect();
		
			// set the new ErrorHendler
			JError::setErrorHandling(E_ALL, 'callback', array($this, 'aecErrorHandler'));
			
		}
	}
	
	/* check if we are at the login page & there is a return URI set.
	*  if so, check if the return was to com_content (regarless of the view) & redirect to NotAllowed.
	*/
	function handleLoginRedirect()
	{
		global $mainframe;

		$uri = &JFactory::getURI();
		$option = $uri->getVar('option');
		$view = $uri->getVar('view');

		if ($return = JRequest::getVar('return', '', 'method', 'base64')) {
			$return = base64_decode($return);
			if (!JURI::isInternal($return)) {
				$return = '';
			}
		}
		
		if($option == 'com_user' && $view == 'login' && $return!='')
		{
			$uri = new JURI($return);
			$option = $uri->getVar('option');

			if($option == 'com_content')
			{
				$mainframe->redirect("index.php?option=com_acctexp&task=NotAllowed" );
			}
		}
	}
	
	// catch the Error & redirect if needed
	function aecErrorHandler(& $error)
	{
		global $mainframe;
		
		if($error->message == JText::_("ALERTNOTAUTH")) {
			$mainframe->redirect("index.php?option=com_acctexp&task=NotAllowed" );
		}
	}

}

?>
