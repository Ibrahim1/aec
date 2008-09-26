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

		// Get the message queue
		$messages = $mainframe->getMessageQueue();
		
		// search for the needed message in the queue
		if (is_array($messages) && count($messages)) {
			foreach ($messages as $msg)
			{
				if (isset($msg['type']) && isset($msg['message'])) {
					if($msg['message'] == JText::_("YOU MUST LOGIN FIRST")) {
						$mainframe->redirect("index.php?option=com_acctexp&task=NotAllowed" );
					}					
				}
			}
		}

		// set the new ErrorHendler
		JError::setErrorHandling(E_ALL, 'callback', array($this, 'aecErrorHandler'));
	}
	
	// catch the Error & redirect if needed
	function aecErrorHandler(& $error){
		if (file_exists( JPATH_ROOT.DS."components".DS."com_acctexp".DS."acctexp.class.php" )) {
			global $mainframe;
			
			if($error->message == JText::_("ALERTNOTAUTH")) {
				$mainframe->redirect("index.php?option=com_acctexp&task=NotAllowed" );
			}

		}
	}

}

?>
