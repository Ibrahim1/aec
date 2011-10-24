<?php
/**
 * @version $Id: mi_aecuserdetails.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Micro Integrations - AEC Donations
 * @copyright 2006-2011 Copyright (C) David Deutsch
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
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

		return $info;
	}

	function Settings()
	{
		$db = &JFactory::getDBO();

		$settings = array();
		$settings['lists']		= array();
		$settings['settings']	= array( 'inputB' );

		$types = array( "p", "inputA", "inputB", "inputC", "inputD", "list", "list_language", "checkbox" );

 		$typelist = array();
 		foreach ( $types as $type ) {
 			$typelist[] = JHTML::_('select.option', $type, $type );
 		}

		if ( !empty( $this->settings['settings'] ) ) {
			for ( $i=0; $i<$this->settings['settings']; $i++ ) {
				$p = $i . '_';

				if ( !isset( $this->settings[$p.'type'] ) ) {
					$this->settings[$p.'type'] = null;
				}

				$settings['lists'][$p.'type']	= JHTML::_('select.genericlist', $typelist, $p.'type', 'size="' . max( 10, min( 20, count( $types ) ) ) . '"', 'value', 'text', $this->settings[$p.'type'] );

				$settings[$p.'short']		= array( 'inputC', sprintf( JText::_('MI_MI_AECUSERDETAILS_SET_SHORT_NAME'), $i+1 ), JText::_('MI_MI_AECUSERDETAILS_SET_SHORT_DESC') );

				if ( $this->settings[$p.'type'] != "checkbox" ) {
					$settings[$p.'mandatory']	= array( 'list_yesno', sprintf( JText::_('MI_MI_AECUSERDETAILS_SET_MANDATORY_NAME'), $i+1 ), JText::_('MI_MI_AECUSERDETAILS_SET_MANDATORY_DESC') );
				}

				$settings[$p.'name']		= array( 'inputC', sprintf( JText::_('MI_MI_AECUSERDETAILS_SET_NAME_NAME'), $i+1 ), JText::_('MI_MI_AECUSERDETAILS_SET_NAME_DESC') );

				$settings[$p.'desc']		= array( 'inputC', sprintf( JText::_('MI_MI_AECUSERDETAILS_SET_DESC_NAME'), $i+1 ), JText::_('MI_MI_AECUSERDETAILS_SET_DESC_DESC') );
				$settings[$p.'type']		= array( 'list', sprintf( JText::_('MI_MI_AECUSERDETAILS_SET_TYPE_NAME'), $i+1 ), JText::_('MI_MI_AECUSERDETAILS_SET_TYPE_DESC') );

				if ( $this->settings[$p.'type'] == "list" ) {
					$settings[$p.'list']	= array( 'inputD', "List Items", "Provide a newline separated list with items like: item1|Description of first item" );
					$settings[$p.'ltype']	= array( 'list_yesno', "Radio List", "Select Yes to display a radio button list instead of a dropdown box." );
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
						$return['error'] = "Please fill in the required fields";
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

	function getMIform( $request )
	{
		$language_array = AECToolbox::getISO3166_1a2_codes();

		$language_code_list = array();
		foreach ( $language_array as $language ) {
			$language_code_list[] = JHTML::_('select.option', $language, JText::_( 'AEC_LANG_' . $language ) );
		}

		$settings	= array();
		$lists		= array();

		if ( !empty( $this->settings['settings'] ) ) {
			for ( $i=0; $i<$this->settings['settings']; $i++ ) {
				$p = $i . '_';

				if ( !empty( $request->params[$p.'name'] ) ) {
					$content = $request->params[$p.'name'];
				} else {
					$content = $this->settings[$p.'default'];
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

}
?>
