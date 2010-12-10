<?php
/**
 * @version $Id: aecrouting.php
 * @package AEC - Account Control Expiration - Joomla 1.5 Plugins
 * @subpackage Routing Plugin
 * @copyright 2006-2010 Copyright (C) David Deutsch
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

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

		$vars['usage']		= aecGetParam( 'usage', 0, true, array( 'word', 'string', 'clear_nonalnum' ) );
		$vars['processor']	= aecGetParam( 'processor', '', true, array( 'word', 'string', 'clear_nonalnum' ) );
		$vars['recurring']	= aecGetParam( 'recurring', 0, true, array( 'word', 'int' ) );

		$vars['tcregs']			= $vars['task'] == 'saveregisters';
		$vars['tregs']			= $vars['task'] == 'registers';
		$vars['tsregs']			= $vars['task'] == 'saveRegistration';
		$vars['tsue']			= $vars['task'] == 'saveUserEdit';
		$vars['tsu']			= $vars['task'] == 'save';
		$vars['lostpw']			= $vars['task'] == 'lostPassword';

		$vars['forget']		= JRequest::getVar( 'forget', '' );

		$vars['submit']		= JRequest::getVar( 'submit', '' );

		$vars['k2']			= JRequest::getVar( 'K2UserForm', 0 );

		// Community Builder
		$vars['ccb']		= $vars['option'] == 'com_comprofiler';
		$vars['ccb12']		= GeneralInfoRequester::detect_component( 'CB1.2' );

		// JomSocial
		$vars['joms']		= $vars['option'] == 'com_community';

		// AlphaRegistration
		$vars['alpha']		= $vars['option'] == 'com_alpharegistration';

		// Standard Joomla
		$vars['cu']			= ( ( $vars['option'] == 'com_user' ) || $vars['option'] == 'com_users' );

		$vars['aec']			= $vars['option'] == 'com_acctexp';

		$vars['j_reg']			= $vars['cu']		&& ( ( $vars['view'] == 'register' ) || ( $vars['view'] == 'registration' ) || ( $vars['task'] == 'register' ) );

		$vars['cb_reg']			= $vars['ccb']		&& $vars['tregs'];

		$vars['joms_reg']		= $vars['joms']		&& ( $vars['view'] == 'register' ) && empty( $vars['task'] );
		$vars['joms_regp']		= $vars['joms']		&& ( $vars['view'] == 'register' ) && ( $vars['task'] == 'registerProfile' );
		$vars['joms_regs']		= $vars['joms']		&& ( $vars['view'] == 'register' ) && ( $vars['task'] == 'registerSucess' );
		$vars['joms_regsv']		= $vars['joms']		&& ( $vars['view'] == 'register' ) && ( $vars['task'] == 'register_save' );

		$vars['alpha_reg']		= $vars['alpha']	&& ( $vars['view'] == 'register' ) && empty( $vars['task'] );
		$vars['alpha_regsv']	= $vars['alpha']	&& ( $vars['task'] == 'register_save' );

		$vars['k2_regsv']		= $vars['k2']		&& ( $vars['task'] == 'register_save' );

		$vars['joms_any']	= ( $vars['joms_regsv'] || $vars['joms_regs'] || $vars['joms_regp'] || $vars['joms_reg'] );

		$vars['isreg']		= ( $vars['j_reg'] || $vars['cb_reg'] || $vars['joms_any'] );

		$vars['cbsreg']		= ( ( $vars['ccb'] && $vars['tsue'] ) || ( $vars['cu'] && $vars['tsu'] ) );
		$vars['cb_sregsv']	= ( ( $vars['ccb'] && $vars['tcregs'] ) );

		$vars['pfirst']		= $aecConfig->cfg['plans_first'];
		$vars['int_reg']	= $aecConfig->cfg['integrate_registration'];

		$vars['username']	= aecGetParam( 'username', "", true, array( 'string', 'clear_nonalnum' ) );

		$vars['has_usage']	= !empty( $vars['usage'] );

		if ( ( $vars['joms_any'] || $vars['ccb12'] || $vars['k2_regsv'] || $vars['alpha_regsv'] ) && !$vars['has_usage'] ) {
			$db = &JFactory::getDBO();

			if ( $vars['joms_any'] ) {
				$vars['username']	= aecGetParam( 'jsusername', "", true, array( 'string', 'clear_nonalnum' ) );
			} else {
				$vars['username']	= aecGetParam( 'username', "", true, array( 'string', 'clear_nonalnum' ) );
			}

			$temptoken = new aecTempToken( $db );
			$temptoken->getComposite();

			if ( !empty( $temptoken->content['usage'] ) ) {
				$vars['has_usage']	= true;
				$vars['usage']		= $temptoken->content['usage'];
				$vars['processor']	= $temptoken->content['processor'];

				if ( isset( $temptoken->content['recurring'] ) ) {
					$vars['recurring']	= $temptoken->content['recurring'];
				} else {
					$vars['recurring']	= 0;
				}

				if ( isset( $temptoken->content['username'] ) ) {
					$vars['username']	= $temptoken->content['username'];
					$vars['has_user']	= true;
				}

				if ( !empty( $_REQUEST['username'] ) && !empty( $_REQUEST['password'] ) && !empty( $_REQUEST['email'] )
					&& empty( $temptoken->content['username'] ) && empty( $temptoken->content['password'] ) && empty( $temptoken->content['email'] ) ) {
					$db = &JFactory::getDBO();

					$temptoken = new aecTempToken( $db );
					$temptoken->getComposite();

					$content = array();
					$temptoken->content['username']		= $_REQUEST['username'];
					$temptoken->content['password']		= $_REQUEST['password'];

					if ( isset( $_REQUEST['password2'] ) ) {
						$temptoken->content['password2']	= $_REQUEST['password2'];
					} elseif ( isset( $_REQUEST['password__verify'] ) ) {
						$temptoken->content['password2']	= $_REQUEST['password__verify'];
					}

					$temptoken->content['email']		= $_REQUEST['email'];

					if ( $vars['k2_regsv'] ) {
						$temptoken->content['handler']	= 'k2';
					} elseif ( $vars['joms_regsv'] ) {
						$temptoken->content['handler']	= 'jomsocial';
					} elseif ( $vars['cb_sregsv'] ) {
						$temptoken->content['handler']	= 'cb';
					}

					$temptoken->storeload();
				}
			} elseif ( !empty( $temptoken->content['username'] ) ) {
				if ( isset( $temptoken->content['username'] ) ) {
					$vars['username']	= $temptoken->content['username'];
					$vars['has_user']	= true;
				}
			}
		}

		$vars['has_user']	= !empty( $vars['username'] );

		switch ( $vars['forget'] ) {
			case 'userdetails':
				$vars['has_user']	= false;
				break;
			case 'usage':
				$vars['pfirst']		= false;
				$vars['usage']		= null;
				$vars['has_usage']	= false;
				break;
			default:
				break;
		}

		return $vars;
	}

	function onAfterRoute()
	{
		if ( strpos( JPATH_BASE, '/administrator' ) ) {
			// Don't act when on backend
			return true;
		}

		if ( !file_exists( JPATH_ROOT.DS."components".DS."com_acctexp".DS."acctexp.class.php" ) ) {
			return;
		}

		include_once( JPATH_ROOT.DS."components".DS."com_acctexp".DS."acctexp.class.php" );

		$vars = $this->getVars();

		if ( ( $vars['isreg'] || $vars['cb_sregsv'] || $vars['k2_regsv'] || $vars['alpha_regsv'] ) && $vars['int_reg'] ) {
			// Joomla or CB registration...
			if ( $vars['pfirst'] && !$vars['has_usage'] ) {
				// Plans first and not yet selected -> select!
				$app = JFactory::getApplication();
				$app->redirect( AECToolbox::deadsureURL( 'index.php?option=com_acctexp&task=subscribe', false, true ) );
			} elseif ( !$vars['has_user'] && !$vars['has_usage'] && $vars['joms_regs'] && !$vars['pfirst'] ) {
				$this->redirectToken();
			} elseif ( $vars['has_user'] && !$vars['has_usage'] && $vars['joms_regs'] ) {
				$this->redirectToken();
			} elseif ( $vars['has_user'] && $vars['has_usage'] && $vars['joms_regs'] ) {
				$this->redirectToken();
			} elseif ( $vars['has_user'] && !$vars['has_usage'] && $vars['k2_regsv'] ) {

			} elseif ( $vars['has_user'] && $vars['has_usage'] && $vars['k2_regsv'] ) {

			} elseif ( $vars['has_user'] && ( $vars['alpha_regsv'] || $vars['joms_regsv'] || $vars['cb_sregsv'] ) ) {
				if ( $vars['joms_regsv'] ) {
					$username	= aecGetParam( 'jsusername', "", true, array( 'string', 'clear_nonalnum' ) );
					$password	= aecGetParam( 'jspassword', "", true, array( 'string', 'clear_nonalnum' ) );
					$password2	= aecGetParam( 'jspassword2', "", true, array( 'string', 'clear_nonalnum' ) );
					$email		= aecGetParam( 'jsemail', "", true, array( 'string', 'clear_nonalnum' ) );
				} else {
					$username	= aecGetParam( 'username', "", true, array( 'string', 'clear_nonalnum' ) );
					$password	= aecGetParam( 'password', "", true, array( 'string', 'clear_nonalnum' ) );
					$password2	= aecGetParam( 'password2', "", true, array( 'string', 'clear_nonalnum' ) );

					if ( empty( $password2 ) ) {
						$password2	= aecGetParam( 'password__verify', "", true, array( 'string', 'clear_nonalnum' ) );
					}

					$email		= aecGetParam( 'email', "", true, array( 'string', 'clear_nonalnum' ) );
				}

				if ( !empty( $username ) && !empty( $password ) && !empty( $email ) ) {
					$db = &JFactory::getDBO();

					$temptoken = new aecTempToken( $db );
					$temptoken->getComposite();

					$skip = array( 'coupon_code', 'task', 'option' );
					
					foreach ( $_POST as $k => $v ) {
						if ( !in_array( $k, $skip ) ) {
							$temptoken->content[$k]	= aecGetParam( $k, "", true, array( 'string', 'clear_nonalnum' ) );
						}
					}

					$temptoken->content['username']		= $username;
					$temptoken->content['password']		= $password;
					$temptoken->content['password2']	= $password2;
					$temptoken->content['email']		= $email;

					if ( $vars['k2_regsv'] ) {
						$temptoken->content['handler']	= 'k2';
					} elseif ( $vars['joms_regsv'] ) {
						$temptoken->content['handler']	= 'joomla';
					} elseif ( $vars['cb_sregsv'] ) {
						$temptoken->content['handler']	= 'cb';
					}

					$temptoken->storeload();

					if ( $vars['cb_sregsv'] ) {
						$this->redirectToken();
					}
				}

				if ( $vars['alpha_regsv'] ) {
					$this->redirectToken();
				}
			} elseif ( $vars['has_usage'] ) {
				$db = &JFactory::getDBO();

				$temptoken = new aecTempToken( $db );
				$temptoken->getComposite();

				$content = array();
				$content['usage']		= $vars['usage'];
				$content['processor']	= $vars['processor'];
				$content['recurring']	= $vars['recurring'];

				if ( empty( $temptoken->id ) ) {
					$temptoken->create( $content );
				} else {
					$temptoken->content = array_merge( $temptoken->content, $content );
				}
			}
		} elseif ( $vars['cbsreg'] ) {
			// Any kind of user profile edit = trigger MIs

			$row = new stdClass();
			$row->username = $vars['username'];

			$mih = new microIntegrationHandler();
			$mih->userchange( $row, $_POST, 'registration' );
		}
	}

	function onAfterRender()
	{
		if ( strpos( JPATH_BASE, '/administrator' ) ) {
			// Don't act when on backend
			return true;
		}

		if ( !file_exists( JPATH_ROOT.DS."components".DS."com_acctexp".DS."acctexp.class.php" ) ) {
			return;
		}

		include_once( JPATH_ROOT.DS."components".DS."com_acctexp".DS."acctexp.class.php" );

		global $aecConfig;

		$app = JFactory::getApplication();

		$body = JResponse::getBody();

		$vars = $this->getVars();

		// Check whether we have a registration situation...
		if ( !$vars['int_reg'] ) {
			return;
		}

		if ( $vars['j_reg'] ) {
			$vars['k2'] = strpos( $body, '<input type="hidden" name="K2UserForm" value="1" />' ) !== false;
		} else {
			$vars['k2'] = 0;
		}

		$activation = $app->getCfg( 'useractivation' );

		if ( ( strpos( $body, '<dt class="message">Message</dt>' ) !== false ) && !$vars['aec'] ) {
			$db = &JFactory::getDBO();

			$temptoken = new aecTempToken( $db );
			$temptoken->getComposite();

			if ( !empty( $temptoken->content['password2'] ) ) {
				$this->redirectToken();
			}
		}

		if ( !( $vars['j_reg'] || $vars['ccb'] ) || $vars['lostpw'] ) {
			return;
		}

		// Plans first and selected or not first and not selected -> register
		$search		= array();
		$replace	= array();
		$change		= false;
aecDebug($vars);
		if ( $vars['ccb'] && !$vars['ccb12'] ) {
			$addinmarker = '<input type="hidden" name="task" value="saveregisters" />';

			$search[]	= '<form action="' . JURI::root() . 'index.php?option=com_comprofiler" method="post"'
							 . ' id="cbcheckedadminForm" name="adminForm" enctype="multipart/form-data">';
			$replace[]	= '<form action="' . JURI::root() . 'index.php?option=com_acctexp&amp;task=subscribe" method="post" enctype="multipart/form-data">';

			$search[]	= '<input type="hidden" name="option" value="com_comprofiler" />';
			$replace[]	= '<input type="hidden" name="option" value="com_acctexp" />';

			$search[]	= $addinmarker;
			$replace[]	= '<input type="hidden" name="task" value="subscribe" />';
		} elseif ( $vars['ccb'] && $vars['ccb12'] && $vars['tcregs'] ) {
			if ( strpos( $body, '<script type="text/javascript">alert(' ) === false ) {
				$this->redirectToken();
			}
		} elseif ( $vars['j_reg'] && !$vars['k2'] ) {
			if ( defined( 'JPATH_MANIFESTS' ) ) {
				$addinmarker = '<input type="hidden" name="task" value="registration.register" />';
			} else {
				$addinmarker = '<input type="hidden" name="task" value="register_save" />';
			}

			$search[]	= $addinmarker;
			$replace[]	= '<input type="hidden" name="task" value="subscribe" />'
							. '<input type="hidden" name="option" value="com_acctexp" />';

			if ( defined( 'JPATH_MANIFESTS' ) ) {
				$search[]	= '<form id="member-registration" action="' . JRoute::_( 'index.php?option=com_users&task=registration.register' ) . '" method="post"';
			} else {
				$search[]	= '<form action="' . JRoute::_( 'index.php?option=com_users' ) . '" method="post"';
			}

			$replace[]	= '<form action="' . JRoute::_( 'index.php?option=com_acctexp' ) . '" method="post"';

			if ( $aecConfig->cfg['use_recaptcha'] && !empty( $aecConfig->cfg['recaptcha_publickey'] ) ) {
				require_once( JPATH_SITE . '/components/com_acctexp/lib/recaptcha/recaptchalib.php' );

				if ( defined( 'JPATH_MANIFESTS' ) ) {
					$search[]	= 'type="password" id="password2" name="password2" size="40" value="" /> *';
					$replace[]	= 'type="password" id="password2" name="password2" size="40" value="" /> *</td></tr>'
									. '<tr><td height="40"><label></label></td><td>'
									. recaptcha_get_html( $aecConfig->cfg['recaptcha_publickey'] );
				} else {
					$search[]	= 'jform_email2" value="" class="validate-email required" size="30"/></dd>';
					$replace[]	= 'jform_email2" value="" class="validate-email required" size="30"/></dd><dt>'
									. '<label id="jform_recaptcha-lbl" for="recaptcha_challenge_field" class="hasTip required" title="ReCAPTCHA">ReCAPTCHA:</label>'
									. '<dd>' . recaptcha_get_html( $aecConfig->cfg['recaptcha_publickey'] ) . '</dd>';
				}
			}
		} elseif ( $vars['k2'] ) {
			$db = &JFactory::getDBO();

			$content = array();
			$content['usage']		= $vars['usage'];
			$content['processor']	= $vars['processor'];
			$content['recurring']	= $vars['recurring'];

			$temptoken = new aecTempToken( $db );
			$temptoken->create( $content );
		}

		if ( !empty( $addinmarker ) ) {
			$body	= $this->addAECvars( $addinmarker, $body, $vars );
			$change	= true;
		}

		if ( !empty( $search ) ) {
			$body	= str_replace( $search, $replace, $body );
			$change	= true;
		}

		if ( $change ) {
			JResponse::setBody( $body );
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

	function redirectToken()
	{
		$app = JFactory::getApplication();
		$app->redirect( AECToolbox::deadsureURL( 'index.php?option=com_acctexp&task=subscribe&aectoken=1', false, true ) );
	}
}

?>
