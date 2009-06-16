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

	function getVars()
	{
		global $aecConfig;

		$vars = array();

		$uri = &JFactory::getURI();
		$vars['task']	= $uri->getVar( 'task' );
		$vars['option']	= $uri->getVar( 'option' );
		$vars['view']	= $uri->getVar( 'view' );

		if ( empty( $vars['task'] ) ) {
			$vars['task']	= JRequest::getVar( 'task', null );
		}

		if ( empty( $vars['option'] ) ) {
			$vars['option'] = JRequest::getVar( 'option', null );
		}

		if ( empty( $vars['view'] ) ) {
			$vars['view']	= JRequest::getVar( 'view', null );
		}

		$vars['usage']		= intval( JRequest::getVar( 'usage', '0' ) );
		$vars['processor']	= JRequest::getVar( 'processor', '' );
		$vars['recurring']	= intval( JRequest::getVar( 'recurring', '0' ) );

		$vars['submit']		= JRequest::getVar( 'submit', '' );

		$vars['username']	= aecGetParam( 'username', true, array( 'string', 'clear_nonalnum' ) );

		$vars['has_usage']	= !empty( $vars['usage'] );

		// Community Builder
		$vars['ccb']		= $vars['option'] == 'com_comprofiler';
		// JomSocial
		$vars['joms']		= $vars['option'] == 'com_community';
		// Standard Joomla
		$vars['cu']			= $vars['option'] == 'com_user';

		$vars['j_reg']		= $vars['cu']	&& ( $vars['task'] == 'register' );
		$vars['j15_reg']	= $vars['cu']	&& ( $vars['view'] == 'register' );
		$vars['cb_reg']		= $vars['ccb']	&& ( $vars['task'] == 'registers' );
		$vars['joms_reg']	= $vars['joms']	&& ( $vars['view'] == 'register' );
		$vars['joms_regp']	= $vars['joms']	&& ( $vars['view'] == 'register' ) && ( $vars['task'] == 'registerProfile' );
		$vars['tcregs']		= $vars['task'] == 'saveregisters';
		$vars['tsregs']		= $vars['task'] == 'saveRegistration';
		$vars['tsue']		= $vars['task'] == 'saveUserEdit';
		$vars['tsu']		= $vars['task'] == 'save';

		$vars['isreg']		= ( $vars['j15_reg'] || $vars['j_reg'] || $vars['cb_reg'] || $vars['joms_reg'] || $vars['joms_regp'] );

		$vars['cbsreg']		= ( ( $vars['ccb'] && $vars['tsue'] ) || ( $vars['cu'] && $vars['tsu'] ) );

		$vars['pfirst']		= $aecConfig->cfg['plans_first'];
		$vars['int_reg']	= $aecConfig->cfg['integrate_registration'];

		return $vars;
	}

	function onAfterRoute()
	{
		if ( file_exists( JPATH_ROOT.DS."components".DS."com_acctexp".DS."acctexp.class.php" ) ) {
			include_once( JPATH_ROOT.DS."components".DS."com_acctexp".DS."acctexp.class.php" );

			$vars = $this->getVars();

			if ( $vars['isreg'] && $vars['int_reg'] ) {
				// Joomla or CB registration...
				if ( $vars['pfirst'] && !$vars['has_usage'] ) {
					// Plans first and not yet selected -> select!
					$mainframe->redirect( 'index.php?option=com_acctexp&task=subscribe' );
				}
			} elseif ( $vars['cbsreg'] ) {
				// Any kind of user profile edit = trigger MIs

				$row = new stdClass();
				$row->username = $vars['username'];

				$mih = new microIntegrationHandler();
				$mih->userchange( $row, $_POST, 'registration' );
			}
		}
	}

	function onAfterRender()
	{
		global $mainframe, $aecConfig;

		$body = JResponse::getBody();

		if ( file_exists( JPATH_ROOT.DS."components".DS."com_acctexp".DS."acctexp.class.php" ) ) {
			include_once( JPATH_ROOT.DS."components".DS."com_acctexp".DS."acctexp.class.php" );

			$vars = $this->getVars();

			// Check whether we have a registration situation...
			if ( !$vars['isreg'] && !$vars['int_reg'] ) {
				return;
			}

			// Check whether we have plans first, but no usage (or vice versa)
			if ( $vars['pfirst'] != $vars['has_usage'] ) {
				return;
			}

			if ( !( $vars['ccb'] || $vars['joms_regp'] || $vars['joms_reg'] ) ) {
				return;
			}

			// Plans first and selected or not first and not selected -> register
			$search		= array();
			$replace	= array();

			if ( $vars['ccb'] ) {
				$addinmarker = '<input type="hidden" name="task" value="saveregisters" />';

				$search[]	= '<form action="http://dev5.websgold.com/index.php?option=com_comprofiler" method="post"'
								 . ' id="cbcheckedadminForm" name="adminForm" enctype="multipart/form-data">';
				$replace[]	= '<form action="/index.php?option=com_acctexp&task=subscribe" method="post" enctype="multipart/form-data">';

				$search[]	= '<input type="hidden" name="option" value="com_comprofiler" />';
				$replace[]	= '<input type="hidden" name="option" value="com_acctexp" />';

				$search[]	= $addinmarker;
				$replace[]	= '<input type="hidden" name="task" value="subscribe" />';
			} elseif ( $vars['joms_regp'] ) {
				$addinmarker = '<input type="hidden" name="task" value="registerUpdateProfile" />';

				$search[]	= '<form action="/index.php?option=com_community&view=register&task=registerProfile';
				$replace[]	= '<form action="/index.php?option=com_acctexp&task=subscribe';

				$search[]	= $addinmarker;
				$replace[]	= '<input type="hidden" name="task" value="subscribe" />';
			} elseif ( $vars['joms_reg'] ) {
				$addinmarker = '<input type="hidden" name="task" value="register_save" />';
			} elseif ( $vars['j15_reg'] ) {
				$search[]	= '<form action="' . JRoute::_( 'index.php?option=com_acctexp' ) . '" method="post"';
				$replace[]	= '<form action="' . JRoute::_( 'index.php?option=com_acctexp' ) . '" method="post"';
			}

			if ( !empty( $addinmarker ) ) {
				$body = $this->addAECvars( $addinmarker, $body, $vars );
			}

			if ( !empty( $search ) ) {
				$body = str_replace( $search, $replace, $body );
			}

			JResponse::setBody($body);
		}
	}

	function addAECvars( $search, $text, $vars )
	{
		$replace = "";

		if ( isset( $vars['usage'] ) ) {
			$replace .= '<input type="hidden" name="usage" value="' . $vars['usage'] . '" />' . "\n";
		}

		if ( isset( $vars['processor'] ) ) {
			$replace .= '<input type="hidden" name="processor" value="' . $vars['processor'] . '" />' . "\n";
		}

		if ( isset( $vars['recurring'] ) ) {
			$replace .= '<input type="hidden" name="recurring" value="' . $vars['recurring'] . '" />' . "\n";
		}

		return str_replace( $search, $search.$replace, $text );
	}

}

}

?>
