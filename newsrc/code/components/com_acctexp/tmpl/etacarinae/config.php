<?php
/**
 * @version $Id: etacarinae/config.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Template Config - Eta Carinae
 * @copyright 2012 Copyright (C) David Deutsch
 * @author David Deutsch <skore@valanx.org> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.3 http://www.gnu.org/licenses/gpl.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' );

class template_etacarinae extends aecTemplate
{
	function info()
	{
		$info = array();
		$info['name']			= 'etacarinae';
		$info['longname']		= "Eta Carinae";
		$info['description']	= "The standard AEC 1.0 template";

		return $info;
	}

	function settings()
	{
		$tab_data = array();

		$params = array();

		$params[] = array( 'userinfobox', 33.225 );
		$params = array_merge( $params, $this->stdSettings() );
		$params[] = array( 'userinfobox_sub', JText::_('CFG_GENERAL_SUB_REGFLOW') );
		$params['displayccinfo']				= array( 'toggle', 0 );
		$params[] = array( 'div_end', 0 );
		$params[] = array( 'userinfobox_sub', JText::_('CFG_CUSTOMIZATION_SUB_BUTTONS_SUB') );
		$params['renew_button_never']				= array( 'toggle', '' );
		$params['renew_button_nolifetimerecurring']	= array( 'toggle', '' );
		$params['continue_button']					= array( 'toggle', '' );
		$params[] = array( 'div_end', 0 );
		$params[] = array( 'userinfobox_sub', 'Shopping Cart' );
		$params['customlink_continueshopping']	= array( 'inputC', '' );
		$params[] = array( 'div_end', 0 );
		$params[] = array( '2div_end', 0 );

		$params[] = array( 'userinfobox', 33.225 );
		$params[] = array( 'userinfobox_sub', JText::_('CFG_GENERAL_SUB_CONFIRMATION') );
		$params['confirmation_changeusername']	= array( 'toggle', '' );
		$params['confirmation_changeusage']		= array( 'toggle', '' );
		$params['confirmation_display_descriptions']	= array( 'toggle', '' );
		$params['tos']							= array( 'inputC', '' );
		$params['tos_iframe']					= array( 'toggle', '' );
		$params[] = array( 'div_end', 0 );
		$params[] = array( 'userinfobox_sub', JText::_('CFG_GENERAL_SUB_CHECKOUT') );
		$params['enable_coupons']				= array( 'toggle', 0 );
		$params['checkout_display_descriptions']	= array( 'toggle', '' );
		$params[] = array( 'div_end', 0 );
		$params[] = array( 'userinfobox_sub', JText::_('CFG_GENERAL_SUB_SUBSCRIPTIONDETAILS') );
		$params['subscriptiondetails_menu']		= array( 'toggle', 1 );
		$params[] = array( 'div_end', 0 );
		$params[] = array( '2div_end', 0 );

		$params[] = array( 'userinfobox', 33.225 );
		$params[] = array( 'userinfobox_sub', JText::_('CFG_CUSTOMIZATION_SUB_CAPTCHA') );
		$params['use_recaptcha']					= array( 'toggle', '' );
		$params['recaptcha_privatekey']				= array( 'inputC', '' );
		$params['recaptcha_publickey']				= array( 'inputC', '' );
		$params[] = array( 'div_end', 0 );
		$params[] = array( '2div_end', 0 );

		@end( $params );
		$tab_data[] = array( JText::_('CFG_TAB_CUSTOMIZATION_TITLE'), key( $params ), '<h2>' . JText::_('CFG_TAB_CUSTOMIZATION_SUBTITLE') . '</h2>' );

		return array( 'params' => $params, 'tab_data' => $tab_data );
	}

}
?>
