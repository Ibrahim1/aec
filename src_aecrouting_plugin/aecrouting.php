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

		$vars['j_reg']		= $vars['cu']	&& ( $vars['view'] == 'register' );
		$vars['cb_reg']		= $vars['ccb']	&& ( $vars['task'] == 'registers' );
		$vars['joms_reg']	= $vars['joms']	&& ( $vars['view'] == 'register' ) && ( $vars['task'] != 'registerProfile' );
		$vars['joms_regp']	= $vars['joms']	&& ( $vars['view'] == 'register' ) && ( $vars['task'] == 'registerProfile' );
		$vars['tcregs']		= $vars['task'] == 'saveregisters';
		$vars['tsregs']		= $vars['task'] == 'saveRegistration';
		$vars['tsue']		= $vars['task'] == 'saveUserEdit';
		$vars['tsu']		= $vars['task'] == 'save';

		$vars['isreg']		= ( $vars['j_reg'] || $vars['cb_reg'] || $vars['joms_reg'] );

		$vars['cbsreg']		= ( ( $vars['ccb'] && $vars['tsue'] ) || ( $vars['cu'] && $vars['tsu'] ) );

		$vars['pfirst']		= $aecConfig->cfg['plans_first'];
		$vars['int_reg']	= $aecConfig->cfg['integrate_registration'];

		return $vars;
	}

	function onAfterRoute()
	{
		if ( strpos( JPATH_BASE, '/administrator' ) ) {
			// Don't act when on backend
			return true;
		}

		if ( file_exists( JPATH_ROOT.DS."components".DS."com_acctexp".DS."acctexp.class.php" ) ) {
			include_once( JPATH_ROOT.DS."components".DS."com_acctexp".DS."acctexp.class.php" );
$session =& JFactory::getSession();aecDebug($session);
			$vars = $this->getVars();
aecDebug($vars);
			if ( ( $vars['joms_regp'] || $vars['joms_reg'] ) && !$vars['has_usage'] ) {
				global $database;

				$session =& JFactory::getSession();
				$token = $session->getToken();
aecDebug("loadtoken");aecDebug($token);
				if ( !empty( $token ) ) {
					$temptoken = new aecTempToken( $database );
					$temptoken->getByToken( $token );
aecDebug($temptoken);//exit;
					if ( !empty( $temptoken->content ) ) {
						$vars['has_usage']	= true;
						$vars['usage']		= $temptoken->content['usage'];
						$vars['processor']	= $temptoken->content['processor'];
						$vars['recurring']	= $temptoken->content['recurring'];
					}
				}
			}

			if ( $vars['isreg'] && $vars['int_reg'] ) {
				// Joomla or CB registration...
				if ( $vars['pfirst'] && !$vars['has_usage'] ) {
					// Plans first and not yet selected -> select!
					global $mainframe;
					$mainframe->redirect( 'index.php?option=com_acctexp&task=subscribe' );
				} elseif ( $vars['has_usage'] && $vars['joms_reg'] && empty( $temptoken ) ) {
					global $database;

					$session =& JFactory::getSession();
					$token = $session->getToken();

					$content = array();
					$content['usage']		= $vars['usage'];
					$content['processor']	= $vars['processor'];
					$content['recurring']	= $vars['recurring'];

					$temptoken = new aecTempToken( $database );
					$temptoken->create( $token, $content );aecDebug("savetoken");aecDebug($token);aecDebug($temptoken);
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
		if ( strpos( JPATH_BASE, '/administrator' ) ) {
			// Don't act when on backend
			return true;
		}

		if ( file_exists( JPATH_ROOT.DS."components".DS."com_acctexp".DS."acctexp.class.php" ) ) {
			include_once( JPATH_ROOT.DS."components".DS."com_acctexp".DS."acctexp.class.php" );

			global $mainframe, $aecConfig;

			$body = JResponse::getBody();

			$vars = $this->getVars();

			if ( ( $vars['joms_regp'] || $vars['joms_reg'] ) && !$vars['has_usage'] ) {
				global $database;

				$token = JUtility::getToken();

				$temptoken = new aecTempToken( $database );
				$temptoken->getByToken( $token );

				if ( !empty( $temptoken->content ) ) {
					$vars['has_usage']	= true;
					$vars['usage']		= $temptoken->content['usage'];
					$vars['processor']	= $temptoken->content['processor'];
					$vars['recurring']	= $temptoken->content['recurring'];
				}
			}

			// Check whether we have a registration situation...
			if ( !$vars['int_reg'] ) {
				return;
			}

			if ( !( $vars['j_reg'] || $vars['ccb'] || $vars['joms_regp'] || $vars['joms_reg'] ) ) {
				return;
			}

			// Check whether we have plans first, but no usage (or vice versa)
			if ( ( $vars['pfirst'] != $vars['has_usage'] ) && !$vars['joms_regp'] ) {
				return;
			}

			// Plans first and selected or not first and not selected -> register
			$search		= array();
			$replace	= array();

			if ( $vars['ccb'] ) {
				$addinmarker = '<input type="hidden" name="task" value="saveregisters" />';

				$search[]	= '<form action="' . JURI::root() . 'index.php?option=com_comprofiler" method="post"'
								 . ' id="cbcheckedadminForm" name="adminForm" enctype="multipart/form-data">';
				$replace[]	= '<form action="' . JURI::root() . 'index.php?option=com_acctexp&amp;task=subscribe" method="post" enctype="multipart/form-data">';

				$search[]	= '<input type="hidden" name="option" value="com_comprofiler" />';
				$replace[]	= '<input type="hidden" name="option" value="com_acctexp" />';

				$search[]	= $addinmarker;
				$replace[]	= '<input type="hidden" name="task" value="subscribe" />';
			} elseif ( $vars['joms_regp'] ) {
				//$addinmarker = '<input type="hidden" name="task" value="registerUpdateProfile" />';

				//$search[]	= '<form action="/index.php?option=com_community&amp;view=register&amp;task=registerProfile';
				//$replace[]	= '<form action="/index.php?option=com_acctexp&amp;task=subscribe';

				//$search[]	= $addinmarker;
				//$replace[]	= '<input type="hidden" name="task" value="subscribe" />';
			} elseif ( $vars['joms_reg'] ) {
				//$addinmarker = '<input type="hidden" name="task" value="register_save" />';
			} elseif ( $vars['j_reg'] ) {
				$addinmarker = '<input type="hidden" name="task" value="register_save" />';

				$search[]	= $addinmarker;
				$replace[]	= '<input type="hidden" name="task" value="subscribe" />'
								. '<input type="hidden" name="option" value="com_acctexp" />';

				$search[]	= '<form action="' . JRoute::_( 'index.php?option=com_user' ) . '" method="post"';
				$replace[]	= '<form action="' . JRoute::_( 'index.php?option=com_acctexp' ) . '" method="post"';

				if ( $aecConfig->cfg['use_recaptcha'] && !empty( $aecConfig->cfg['recaptcha_publickey'] ) ) {
					global $mosConfig_absolute_path;

					require_once( $mosConfig_absolute_path . '/components/com_acctexp/lib/recaptcha/recaptchalib.php' );

					$search[]	= 'type="password" id="password2" name="password2" size="40" value="" />';
					$replace[]	= 'type="password" id="password2" name="password2" size="40" value="" />'
									. '<tr><td height="40"><label></label></td><td>'
									. recaptcha_get_html( $aecConfig->cfg['recaptcha_publickey'] ) . '</td></tr>';
				}
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
		$add = "\n";

		if ( isset( $vars['usage'] ) ) {
			$add .= '<input type="hidden" name="usage" value="' . $vars['usage'] . '" />' . "\n";
		}

		if ( isset( $vars['processor'] ) ) {
			$add .= '<input type="hidden" name="processor" value="' . $vars['processor'] . '" />' . "\n";
		}

		if ( isset( $vars['recurring'] ) ) {
			$add .= '<input type="hidden" name="recurring" value="' . $vars['recurring'] . '" />' . "\n";
		}

		return str_replace( $search, $search.$add, $text );
	}

}

}

?>
