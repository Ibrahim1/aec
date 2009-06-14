<?php
/**
 * @version $Id: aecrouting.php
 * @package AEC - Account Control Expiration - Joomla 1.5 Plugins
 * @subpackage Routing Plugin
 * @copyright 2006-2008 Copyright (C) David Deutsch
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.valanx.org
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
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.valanx.org
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

	function onAfterRoute()
	{
		if ( file_exists( JPATH_ROOT.DS."components".DS."com_acctexp".DS."acctexp.class.php" ) ) {
			include_once( JPATH_ROOT.DS."components".DS."com_acctexp".DS."acctexp.class.php" );

			global $mainframe, $aecConfig;

			$uri = &JFactory::getURI();
			$task	= $uri->getVar( 'task' );
			$option	= $uri->getVar( 'option' );
			$view	= $uri->getVar( 'view' );

			if ( empty( $task ) ) {
				$task = JRequest::getVar( 'task', null );
			}

			if ( empty( $option ) ) {
				$option = JRequest::getVar( 'option', null );
			}

			$usage	= intval( JRequest::getVar( 'usage', '0' ) );
			$submit	= JRequest::getVar( 'submit', '' );

			$username = aecGetParam( 'username', true, array( 'string', 'clear_nonalnum' ) );

			$no_usage	= $usage == 0;

			// Community Builder
			$ccb		= $option == 'com_comprofiler';
			// JomSocial
			$joms		= $option == 'com_community';
			// Standard Joomla
			$cu			= $option == 'com_user';

			$j_reg		= $task == 'register';
			$j15_reg	= $view == 'register';
			$cb_reg		= $task == 'registers';
			$tcregs		= $task == 'saveregisters';
			$tsregs		= $task == 'saveRegistration';
			$tsue		= $task == 'saveUserEdit';
			$tsu		= $task == 'save';

			$cbsreg		= ( ( $ccb && $tsue ) || ( $cu && $tsu ) );

			$pfirst		= $aecConfig->cfg['plans_first'];

			if ( ( $j15_reg || $j_reg || $cb_reg ) && $aecConfig->cfg['integrate_registration'] ) {
				// Joomla or CB registration...
				if ( ( $pfirst && !$no_usage ) || ( !$pfirst && $no_usage ) ) {
					$mainframe->redirect( 'index.php?option=com_acctexp&task=rerouteregister' );
				} elseif ( $pfirst && $no_usage ) {
					// Plans first and not yet selected
					// Immediately redirect to plan selection
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

	/**
	 * Example prepare content method
	 *
	 * Method is called by the view
	 *
	 * @param 	object		The article object.  Note $article->text is also available
	 * @param 	object		The article params
	 * @param 	int			The 'page' number
	 */
	function onPrepareContent( &$article, &$params, $limitstart )
	{
		global $mainframe, $aecConfig;

		
	}

	function addAECvars( $search, $text )
	{
		

		$replace = '<input type="hidden" name="planid" value="<?php echo $_POST[\'planid\'];?>" />' . "\n"
						. '<input type="hidden" name="processor" value="<?php echo $_POST[\'processor\'];?>" />' . "\n"

		return $text;
	}
}

}

?>
