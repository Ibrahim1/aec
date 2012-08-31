<?php
/**
 * @version $Id: mi_aecuserdetails.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Micro Integrations - AEC Donations
 * @copyright 2006-2012 Copyright (C) David Deutsch
 * @author David Deutsch <skore@valanx.org> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.3 http://www.gnu.org/licenses/gpl.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' );

class mi_aecuserdetails
{
	function Info()
	{
		$info = array();
		$info['name'] = JText::_('AEC_MI_NAME_AECUSERDETAILS');
		$info['desc'] = JText::_('AEC_MI_DESC_AECUSERDETAILS');
		$info['type'] = array( 'aec.membership', 'vendor.valanx' );

		return $info;
	}

	function Settings()
	{
		$db = &JFactory::getDBO();

		$settings = array();
		$settings['emulate_reg']	= array( 'toggle' );
		$settings['settings']		= array( 'inputB' );

		$types = array( "p", "inputA", "inputB", "inputC", "inputD", "list", "list_language", "checkbox" );

 		$typelist = array();
 		foreach ( $types as $type ) {
 			$typelist[] = JHTML::_('select.option', $type, $type );
 		}

		$types = array( 0 => "No Skipping", 1 => "Skip if already existing", 2 => "Skip if existing, or default not empty" );

 		$xtypelist = array();
 		foreach ( $types as $type ) {
 			$xtypelist[] = JHTML::_('select.option', $type, $type );
 		}

		if ( !empty( $this->settings['settings'] ) ) {
			$settings['lists']		= array();

			for ( $i=0; $i<$this->settings['settings']; $i++ ) {
				$p = $i . '_';

				if ( !isset( $this->settings[$p.'type'] ) ) {
					$this->settings[$p.'type'] = null;
				}

				$settings['lists'][$p.'type']	= JHTML::_('select.genericlist', $typelist, $p.'type', 'size="' . max( 10, min( 20, count( $types ) ) ) . '"', 'value', 'text', $this->settings[$p.'type'] );

				$settings[$p.'short']		= array( 'inputC', sprintf( JText::_('MI_MI_AECUSERDETAILS_SET_SHORT_NAME'), $i+1 ), JText::_('MI_MI_AECUSERDETAILS_SET_SHORT_DESC') );

				if ( $this->settings[$p.'type'] != "checkbox" ) {
					$settings[$p.'mandatory']	= array( 'toggle', sprintf( JText::_('MI_MI_AECUSERDETAILS_SET_MANDATORY_NAME'), $i+1 ), JText::_('MI_MI_AECUSERDETAILS_SET_MANDATORY_DESC') );
				}

				$settings[$p.'name']		= array( 'inputC', sprintf( JText::_('MI_MI_AECUSERDETAILS_SET_NAME_NAME'), $i+1 ), JText::_('MI_MI_AECUSERDETAILS_SET_NAME_DESC') );

				$settings[$p.'desc']		= array( 'inputC', sprintf( JText::_('MI_MI_AECUSERDETAILS_SET_DESC_NAME'), $i+1 ), JText::_('MI_MI_AECUSERDETAILS_SET_DESC_DESC') );
				$settings[$p.'type']		= array( 'list', sprintf( JText::_('MI_MI_AECUSERDETAILS_SET_TYPE_NAME'), $i+1 ), JText::_('MI_MI_AECUSERDETAILS_SET_TYPE_DESC') );

				if ( $this->settings[$p.'type'] == "list" ) {
					$settings[$p.'list']	= array( 'inputD', "List Items", "Provide a newline separated list with items like: item1|Description of first item" );
					$settings[$p.'ltype']	= array( 'toggle', "Radio List", "Select Yes to display a radio button list instead of a dropdown box." );
				}

				$settings[$p.'default']		= array( 'inputC', sprintf( JText::_('MI_MI_AECUSERDETAILS_SET_DEFAULT_NAME'), $i+1 ), JText::_('MI_MI_AECUSERDETAILS_SET_DEFAULT_DESC') );
			}
		}

		return $settings;
	}

	function saveParams( $params )
	{
		foreach ( $params as $n => $v ) {
			if ( !empty( $v ) && ( strpos( $n, '_short' ) ) ) {
				$params[$n] = preg_replace( '/[^a-z0-9._+-]+/i', '', trim( strtolower( $v ) ) );
			}
		}

		return $params;
	}

	function verifyMIform( $request )
	{
		$return = array();

		if ( !empty( $this->settings['settings'] ) ) {
			for ( $i=0; $i<$this->settings['settings']; $i++ ) {
				$p = $i . '_';

				if ( !empty( $this->settings[$p.'mandatory'] ) ) {
					if ( empty( $request->params[$this->settings[$p.'short']] ) && ( $this->settings[$p.'type'] != 'checkbox' ) ) {
						$return['error'] = JText::_('MI_MI_AECUSERDETAILS_PLEASE_FILL_REQUIRED');
					} else {
						$request->params[$this->settings[$p.'name']] = 0;
					}
				}

			}
		}

		if ( !empty( $return['error'] ) ) {
			return $return;
		}

		$params = array();
		if ( !empty( $this->settings['settings'] ) ) {
			for ( $i=0; $i<$this->settings['settings']; $i++ ) {
				$p = $i . '_';

				if ( !empty( $this->settings[$p.'short'] ) ) {
					if ( empty( $request->params[$this->settings[$p.'short']] ) && ( $this->settings[$p.'type'] == 'checkbox' ) ) {
						$params[$this->settings[$p.'short']] = 'null';
					} else {
						$params[$this->settings[$p.'short']] = $request->params[$this->settings[$p.'short']];
					}
				}
			}
		}

		$request->metaUser->meta->addCustomParams( $params );
		$request->metaUser->meta->storeload();

		return $return;
	}

	function admin_form( $request )
	{
		return $this->getMIform( $request );
	}

	function getMIform( $request )
	{
		$language_array = AECToolbox::getISO3166_1a2_codes();

		$language_code_list = array();
		foreach ( $language_array as $language ) {
			$language_code_list[] = JHTML::_('select.option', $language, JText::_( 'COUNTRYCODE_' . $language ) );
		}

		$settings	= array();
		$lists		= array();

		if ( !empty( $this->settings['emulate_reg'] ) ) {
			if ( defined( 'JPATH_MANIFESTS' ) ) {
				// Joomla 1.6+ Registration
				$lang =& JFactory::getLanguage();

				$lang->load( 'com_users', JPATH_SITE, 'en-GB', true );
				$lang->load( 'com_users', JPATH_SITE, $lang->get('tag'), true );

				$settings['name'] = array( 'inputC', JText::_( 'COM_USERS_PROFILE_NAME_LABEL' ), 'name', '' );
				$settings['username'] = array( 'inputC', JText::_( 'COM_USERS_PROFILE_USERNAME_LABEL' ), 'username', '' );
				$settings['email'] = array( 'inputC', JText::_( 'COM_USERS_PROFILE_EMAIL1_LABEL' ), 'email', '' );
				$settings['email2'] = array( 'inputC', JText::_( 'COM_USERS_PROFILE_EMAIL2_LABEL' ), 'email', '' );
				$settings['password'] = array( 'password', JText::_( 'COM_USERS_REGISTER_PASSWORD1_LABEL' ), 'password', '' );
				$settings['password2'] = array( 'password', JText::_( 'COM_USERS_REGISTER_PASSWORD2_LABEL' ), 'password2', '' );

				$settings['validation']['rules'] = array();
				$settings['validation']['rules']['name'] = array( 'minlength' => 2, 'alphanumericwithbasicpunc' => true, 'required' => true );
				$settings['validation']['rules']['username'] = array( 'minlength' => 3, 'alphanumeric' => true, 'required' => true );
				$settings['validation']['rules']['email'] = array( 'email' => true, 'required' => true );
				$settings['validation']['rules']['email2'] = array( 'email' => true, 'required' => true, 'equalTo' => '#mi_'.$this->id.'_email' );
				$settings['validation']['rules']['password'] = array( 'minlength' => 6, 'maxlength' => 98, 'required' => true );
				$settings['validation']['rules']['password2'] = array( 'minlength' => 6, 'maxlength' => 98, 'required' => true, 'equalTo' => '#mi_'.$this->id.'_password' );
			} else {
				// Joomla 1.5 Registration
				$settings['name'] = array( 'inputC', JText::_( 'Name' ), 'name', '' );
				$settings['username'] = array( 'inputC', JText::_( 'User name' ), 'username', '' );
				$settings['email'] = array( 'inputC', JText::_( 'Email' ), 'email', '' );
				$settings['password'] = array( 'password', JText::_( 'Password' ), 'password', '' );
				$settings['password2'] = array( 'password', JText::_( 'Verify Password' ), 'password2', '' );

				$settings['validation']['rules'] = array();
				$settings['validation']['rules']['name'] = array( 'minlength' => 2, 'required' => true );
				$settings['validation']['rules']['username'] = array( 'minlength' => 3, 'required' => true );
				$settings['validation']['rules']['email'] = array( 'email' => true, 'required' => true );
				$settings['validation']['rules']['password'] = array( 'minlength' => 2, 'required' => true );
				$settings['validation']['rules']['password2'] = array( 'minlength' => 2, 'required' => true, 'equalTo' => '#mi_'.$this->id.'_password' );
			}
		}

		if ( !empty( $this->settings['settings'] ) ) {
			for ( $i=0; $i<$this->settings['settings']; $i++ ) {
				$p = $i . '_';

				if ( !empty( $request->params[$p.'name'] ) ) {
					$content = $request->params[$p.'name'];
				} elseif ( !empty( $_POST['mi_'.$request->parent->id.'_'.$this->settings[$p.'short']] ) ) {
					$content = aecGetParam( 'mi_'.$request->parent->id.'_'.$this->settings[$p.'short'], true, array( 'string', 'badchars' ) );
				} else {
					$content = AECToolbox::rewriteEngineRQ( $this->settings[$p.'default'], $request );
				}

				if ( !empty( $this->settings[$p.'short'] ) ) {
					if ( $this->settings[$p.'type'] == 'list' ) {
						$extra = explode( "\n", $this->settings[$p.'list'] );

						if ( !count( $extra ) ) {
							continue;
						}

						$fields = array();
						foreach ( $extra as $ex ) {
							$fields[] = explode( "|", $ex );
						}

						if ( $this->settings[$p.'ltype'] ) {
							$settings[$this->settings[$p.'short'].'_desc'] = array( 'p', "", $this->settings[$p.'name'] );

							$settings[$this->settings[$p.'short']] = array( 'hidden', null, 'mi_'.$this->id.'_'.$this->settings[$p.'short'] );

							foreach ( $fields as $id => $field ) {
								if ( !empty( $field[1] ) ) {
									$settings[$this->settings[$p.'short'].$id] = array( 'radio', 'mi_'.$this->id.'_'.$this->settings[$p.'short'], trim( $field[0] ), true, trim( $field[1] ) );
								}
							}

							continue;
						} else {
							$options = array();
							foreach ( $fields as $field ) {
								if ( !empty( $field[1] ) ) {
									$options[] = JHTML::_('select.option', trim( $field[0] ), trim( $field[1] ) );
								}
							}

							$lists[$this->settings[$p.'short']]	= JHTML::_('select.genericlist', $options, $this->settings[$p.'short'], 'size="1"', 'value', 'text', 0 );
						}
					}

					if ( $this->settings[$p.'type'] == 'list_language' ) {
						$lists[$this->settings[$p.'short']] = JHTML::_('select.genericlist', $language_code_list, $this->settings[$p.'short'], 'size="10"', 'value', 'text', $content );

						$this->settings[$p.'type'] = 'list';
					}

					if ( $this->settings[$p.'type'] == 'checkbox' ) {
						$settings[$this->settings[$p.'short']] = array( $this->settings[$p.'type'], 'mi_'.$this->id.$this->settings[$p.'short'], 1, $content, $this->settings[$p.'name'] );
					} elseif ( ( $this->settings[$p.'type'] == 'list' ) ) {
						$settings[$this->settings[$p.'short']] = array( $this->settings[$p.'type'], $this->settings[$p.'name'], $this->settings[$p.'name'], 'mi_'.$this->id.'_'.$this->settings[$p.'short'] );
					} else {
						$settings[$this->settings[$p.'short']] = array( $this->settings[$p.'type'], $this->settings[$p.'name'], $this->settings[$p.'name'], $content );
					}
				}
			}
		}

		if ( !empty( $lists ) ) {
			$settings['lists'] = $lists;
		}

		return $settings;
	}

	function before_invoice_confirm( $request )
	{
		if ( !empty( $this->settings['emulate_reg'] ) ) {
			if ( defined( 'JPATH_MANIFESTS' ) ) {
				$vars = array( 'username' => 'username', 'name' => 'name', 'email' => 'email', 'email2' => 'email2', 'password' => 'password', 'password2' => 'password2' );
				
				foreach ( $vars as $k => $v ) {
					if ( isset( $request->add->passthrough['mi_'.$this->id.'_'.$k] ) ) {
						$request->add->passthrough[$v] = $request->add->passthrough['mi_'.$this->id.'_'.$k];
						

						unset( $request->add->passthrough['mi_'.$this->id.'_'.$k] );
					}
				}
			} else {
				$vars = array( 'username', 'name', 'email', 'password', 'password2' );
				
				foreach ( $vars as $k ) {
					if ( isset( $request->add->passthrough['mi_'.$this->id.'_'.$k] ) ) {
						$request->add->passthrough[$k] = $request->add->passthrough['mi_'.$this->id.'_'.$k];
						

						unset( $request->add->passthrough['mi_'.$this->id.'_'.$k] );
					}
				}
			}
		}

		checkUsernameEmail( $request->add->passthrough['username'], $request->add->passthrough['email'] );
	}
}
?>
