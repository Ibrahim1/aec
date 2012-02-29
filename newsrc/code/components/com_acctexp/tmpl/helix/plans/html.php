<?php
/**
 * @version $Id: exception/html.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Main Frontend
 * @copyright 2012 Copyright (C) David Deutsch
 * @author David Deutsch <skore@valanx.org> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.3 http://www.gnu.org/licenses/gpl.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' );

$tmpl->addDefaultCSS();

$tmpl->setTitle( JText::_('PAYPLANS_HEADER') );

$imgroot = JURI::root(true) . '/media/com_acctexp/images/site/';

$csslist = array();
foreach ( $list as $li => $lv ) {
	if ( $lv['type'] == 'group' ) {
		continue;
	}

	foreach ( $lv['gw'] as $gwid => $pp ) {
		$btnarray = array();

		if ( strtolower( $pp->processor_name ) == 'add_to_cart' ) {
			$btnarray['option']		= 'com_acctexp';
			$btnarray['task']		= 'addtocart';
			$btnarray['class'] = 'aec-btn aec-btn-processor';
			$btnarray['content'] = JText::_('AEC_BTN_ADD_TO_CART');

			$btnarray['usage'] = $lv['id'];

			if ( $tmpl->cfg['additem_stayonpage'] ) {
				$btnarray['returngroup'] = $group;
			}
		} else {
			$btnarray['view'] = '';

			if ( $register ) {
				if ( GeneralInfoRequester::detect_component( 'anyCB' ) ) {
					$btnarray['option']	= 'com_comprofiler';
					$btnarray['task']	= 'registers';
				} elseif ( GeneralInfoRequester::detect_component( 'JOMSOCIAL' ) ) {
					$btnarray['option']	= 'com_community';
					$btnarray['task']	= '';
					$btnarray['view'] 	= 'register';
				} else {
					if ( defined( 'JPATH_MANIFESTS' ) ) {
						$btnarray['option']	= 'com_users';
						$btnarray['task']	= '';
						$btnarray['view'] 	= 'registration';
					} else {
						$btnarray['option']	= 'com_user';
						$btnarray['task']	= '';
						$btnarray['view'] 	= 'register';
					}
				}
			} else {
				$btnarray['option']		= 'com_acctexp';
				$btnarray['task']		= 'confirm';
			}

			$btnarray['class'] = 'aec-btn aec-btn-processor';

			if ( $pp->processor_name == 'free' ) {
				$btnarray['content'] = JText::_('AEC_PAYM_METHOD_FREE');
			} elseif( is_object($pp->processor) ) {
				if ( $pp->processor->getLogoFilename() == '' ) {
					$btnarray['content'] = '<span class="aec-btn-tallcontent">'.$pp->info['longname'].'</span>';
				} else {
					if ( !in_array($pp->processor_name, $csslist) ) {
						$css = '.aec-btn-processor-' . $pp->processor_name . ' {
							background-image: url(' . $pp->getLogoPath() .  ') !important;
						}';

						$tmpl->addCSSDeclaration( $css );

						$csslist[] = $pp->processor_name;
					}
				}
			}

			if ( !empty( $pp->settings['generic_buttons'] ) ) {
				if ( !empty( $pp->recurring ) ) {
					$btnarray['content'] = JText::_('AEC_PAYM_METHOD_SUBSCRIBE');
				} else {
					$btnarray['content'] = JText::_('AEC_PAYM_METHOD_BUYNOW');
				}
			} else {
				$btnarray['class'] .= ' aec-btn-processor-'.$pp->processor_name;
				if ( isset( $pp->info['recurring_buttons'] ) ) {
					if ( $pp->recurring ) {
						$btnarray['content'] = '<i class="aec-btn-overlay">' . JText::_('AEC_PAYM_METHOD_RECURRING_BILLING') . '</i>';
					} else {
						$btnarray['content'] = '<i class="aec-btn-overlay">' . JText::_('AEC_PAYM_METHOD_ONE_TIME_BILLING') . '</i>';
					}
				}
			}

			if ( !empty( $pp->recurring ) ) {
				$btnarray['recurring'] = 1;
			} else {
				$btnarray['recurring'] = 0;
			}

			$btnarray['processor'] = $pp->processor_name;

			$btnarray['usage'] = $lv['id'];
		}

		$btnarray['userid'] = ( $userid ? $userid : 0 );

		// Rewrite Passthrough
		if ( !empty( $passthrough ) ) {
			$btnarray['aec_passthrough'] = $passthrough;
		}

		$list[$li]['gw'][$gwid]->btn = $btnarray;
	}
}

@include( $tmpl->tmpl( 'plans' ) );
