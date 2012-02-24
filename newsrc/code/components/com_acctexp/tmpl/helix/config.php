<?php
/**
 * @version $Id: helix/config.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Template Config - Helix
 * @copyright 2012 Copyright (C) David Deutsch
 * @author David Deutsch <skore@valanx.org> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' );

class template_helix
{
	function info()
	{
		$info = array();
		$info['name']			= 'helix';
		$info['longname']		= JText::_('AEC_TEMPLATE_HELIX_NAME');
		$info['description']	= JText::_('AEC_TEMPLATE_HELIX_DESC');

		return $info;
	}

	function settings()
	{
		$tab_data = array();

		$params = array();

		$params[] = array( 'userinfobox', 33.225 );
		$params[] = array( 'userinfobox_sub', JText::_('CFG_GENERAL_SUB_REGFLOW') );
		$params['skip_confirmation']			= array( 'toggle', 0 );
		$params['displayccinfo']				= array( 'toggle', 0 );
		$params[] = array( 'div_end', 0 );
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
		$params[] = array( '2div_end', 0 );

		$params[] = array( 'userinfobox', 33.225 );
		$params[] = array( 'userinfobox_sub', 'Shopping Cart' );
		$params['customlink_continueshopping']	= array( 'inputC', '' );
		$params[] = array( 'div_end', 0 );
		$params[] = array( 'userinfobox_sub', JText::_('CFG_GENERAL_SUB_SUBSCRIPTIONDETAILS') );
		$params['subscriptiondetails_menu']		= array( 'toggle', 1 );
		$params[] = array( 'div_end', 0 );
		$params[] = array( '2div_end', 0 );

		@end( $params );
		$tab_data[] = array( JText::_('CFG_TAB1_TITLE'), key( $params ), '<h2>' . JText::_('CFG_TAB1_SUBTITLE') . '</h2>' );

		$params[] = array( 'userinfobox', 49.8 );
		$params[] = array( 'userinfobox_sub', JText::_('CFG_CUSTOMIZATION_SUB_FORMAT_DATE') );
		$params['display_date_frontend']			= array( 'inputC', '%a, %d %b %Y %T %Z' );
		$params[] = array( 'div_end', 0 );
		$params[] = array( 'userinfobox_sub', JText::_('CFG_CUSTOMIZATION_SUB_BUTTONS_SUB') );
		$params['renew_button_never']				= array( 'toggle', '' );
		$params['renew_button_nolifetimerecurring']	= array( 'toggle', '' );
		$params['continue_button']					= array( 'toggle', '' );
		$params[] = array( 'div_end', 0 );
		$params[] = array( '2div_end', 0 );

		$params[] = array( 'userinfobox', 49.8 );
		$params[] = array( 'userinfobox_sub', JText::_('CFG_CUSTOMIZATION_SUB_FORMAT_PRICE') );
		$params['amount_currency_symbol']			= array( 'toggle', 0 );
		$params['amount_currency_symbolfirst']		= array( 'toggle', 0 );
		$params['amount_use_comma']					= array( 'toggle', 0 );
		$params[] = array( 'div_end', 0 );
		$params[] = array( 'userinfobox_sub', JText::_('CFG_CUSTOMIZATION_SUB_CAPTCHA') );
		$params['use_recaptcha']					= array( 'toggle', '' );
		$params['recaptcha_privatekey']				= array( 'inputC', '' );
		$params['recaptcha_publickey']				= array( 'inputC', '' );
		$params[] = array( 'div_end', 0 );
		$params[] = array( '2div_end', 0 );

		@end( $params );
		$tab_data[] = array( JText::_('CFG_TAB_CUSTOMIZATION_TITLE'), key( $params ), '<h2>' . JText::_('CFG_TAB_CUSTOMIZATION_SUBTITLE') . '</h2>' );

		$params[] = array( 'userinfobox_sub', JText::_('CFG_CUSTOMIZATION_INVOICE_PRINTOUT_DETAILS') );
		$params[] = array( 'accordion_start', '' );
		$params[] = array( 'accordion_itemstart', JText::_('CFG_GENERAL_INVOICE_HEADER_NAME') );
		$params['invoice_page_title']				= array( 'inputD', '' );
		$params['invoice_header']					= array( 'editor', '' );
		$params[] = array( 'accordion_itemend', '' );
		$params[] = array( 'accordion_itemstart', JText::_('CFG_GENERAL_INVOICE_AFTER_HEADER_NAME') );
		$params['invoice_after_header']				= array( 'editor', '' );
		$params[] = array( 'accordion_itemend', '' );
		$params[] = array( 'accordion_itemstart', JText::_('CFG_GENERAL_INVOICE_ADDRESS_NAME') );
		$params['invoice_address_allow_edit']		= array( 'toggle', '' );
		$params['invoice_address']					= array( 'inputD', '' );
		$params[] = array( 'accordion_itemend', '' );
		$params[] = array( 'accordion_itemstart', JText::_('CFG_GENERAL_INVOICE_BEFORE_CONTENT_NAME') );
		$params['invoice_before_content']			= array( 'editor', '' );
		$params[] = array( 'accordion_itemend', '' );
		$params[] = array( 'accordion_itemstart', JText::_('CFG_GENERAL_INVOICE_AFTER_CONTENT_NAME') );
		$params['invoice_after_content']			= array( 'editor', '' );
		$params[] = array( 'accordion_itemend', '' );
		$params[] = array( 'accordion_itemstart', JText::_('CFG_GENERAL_INVOICE_BEFORE_FOOTER_NAME') );
		$params['invoice_before_footer']			= array( 'editor', '' );
		$params[] = array( 'accordion_itemend', '' );
		$params[] = array( 'accordion_itemstart', JText::_('CFG_GENERAL_INVOICE_FOOTER_NAME') );
		$params['invoice_footer']					= array( 'editor', '' );
		$params[] = array( 'accordion_itemend', '' );
		$params[] = array( 'accordion_itemstart', JText::_('CFG_GENERAL_INVOICE_AFTER_FOOTER_NAME') );
		$params['invoice_after_footer']				= array( 'editor', '' );
		$params[] = array( 'accordion_itemend', '' );
		$params[] = array( 'div_end', '' );
		$params[] = array( 'div_end', 0 );

		$params[] = array( 'userinfobox_sub', "" );
		$params = AECToolbox::rewriteEngineInfo( array(), $params );
		$params[] = array( 'div_end', '' );
		$params[] = array( '2div_end', 0 );

		@end( $params );
		$tab_data[] = array( JText::_('CFG_TAB_CUSTOMINVOICE_TITLE'), key( $params ), '<h2>' . JText::_('CFG_TAB_CUSTOMINVOICE_SUBTITLE') . '</h2>' );

		$params[] = array( 'userinfobox', 49.8 );
		$params[] = array( 'userinfobox_sub', JText::_('CFG_CUSTOMIZATION_SUB_CREDIRECT') );
		$params['customintro']						= array( 'inputC', '' );
		$params['customintro_userid']				= array( 'toggle', '' );
		$params['customintro_always']				= array( 'toggle', '' );
		$params['customthanks']						= array( 'inputC', '' );
		$params['customcancel']						= array( 'inputC', '' );
		$params['customnotallowed']					= array( 'inputC', '' );
		$params[] = array( 'div_end', 0 );
		$params[] = array( 'userinfobox_sub', "" );
		$rewriteswitches							= array( 'cms', 'invoice' );
		$params = AECToolbox::rewriteEngineInfo( $rewriteswitches, $params );
		$params[] = array( 'div_end', '' );
		$params[] = array( '2div_end', 0 );

		$params[] = array( 'userinfobox', 100 );
		$params[] = array( 'userinfobox_sub', JText::_('Custom Text') );
		$params[] = array( 'accordion_start', '' );
		$params[] = array( 'accordion_itemstart', JText::_('CFG_GENERAL_CUSTOMTEXT_PLANS_NAME') );
		$params['customtext_plans']					= array( 'editor', '' );
		$params[] = array( 'accordion_itemend', '' );
		$params[] = array( 'accordion_itemstart', JText::_('CFG_GENERAL_CUSTOMTEXT_CONFIRM_NAME') );
		$params['custom_confirm_userdetails']		= array( 'editor', '' );
		$params['customtext_confirm_keeporiginal']	= array( 'toggle', '' );
		$params['customtext_confirm']				= array( 'editor', '' );
		$params[] = array( 'accordion_itemend', '' );
		$params[] = array( 'accordion_itemstart', JText::_('CFG_GENERAL_CUSTOMTEXT_CHECKOUT_NAME') );
		$params['customtext_checkout_keeporiginal']	= array( 'toggle', '' );
		$params['customtext_checkout']				= array( 'editor', '' );
		$params[] = array( 'accordion_itemend', '' );
		$params[] = array( 'accordion_itemstart', JText::_('CFG_GENERAL_CUSTOMTEXT_EXCEPTION_NAME') );
		$params['customtext_exception_keeporiginal']	= array( 'toggle', '' );
		$params['customtext_exception']				= array( 'editor', '' );
		$params[] = array( 'accordion_itemend', '' );
		$params[] = array( 'accordion_itemstart', JText::_('CFG_GENERAL_CUSTOMTEXT_NOTALLOWED_NAME') );
		$params['customtext_notallowed_keeporiginal']	= array( 'toggle', '' );
		$params['customtext_notallowed']			= array( 'editor', '' );
		$params[] = array( 'accordion_itemend', '' );
		$params[] = array( 'accordion_itemstart', JText::_('CFG_GENERAL_CUSTOMTEXT_PENDING_NAME') );
		$params['customtext_pending_keeporiginal']	= array( 'toggle', '' );
		$params['customtext_pending']				= array( 'editor', '' );
		$params[] = array( 'accordion_itemend', '' );
		$params[] = array( 'accordion_itemstart', JText::_('CFG_GENERAL_CUSTOMTEXT_HOLD_NAME') );
		$params['customtext_hold_keeporiginal']		= array( 'toggle', '' );
		$params['customtext_hold']					= array( 'editor', '' );
		$params[] = array( 'accordion_itemend', '' );
		$params[] = array( 'accordion_itemstart', JText::_('CFG_GENERAL_CUSTOMTEXT_EXPIRED_NAME') );
		$params['customtext_expired_keeporiginal']	= array( 'toggle', '' );
		$params['customtext_expired']				= array( 'editor', '' );
		$params[] = array( 'accordion_itemend', '' );
		$params[] = array( 'accordion_itemstart', JText::_('CFG_GENERAL_CUSTOMTEXT_THANKS_NAME') );
		$params['customtext_thanks_keeporiginal']	= array( 'toggle', '' );
		$params['customtext_thanks']				= array( 'editor', '' );
		$params[] = array( 'accordion_itemend', '' );
		$params[] = array( 'accordion_itemstart', JText::_('CFG_GENERAL_CUSTOMTEXT_CANCEL_NAME') );
		$params['customtext_cancel_keeporiginal']	= array( 'toggle', '' );
		$params['customtext_cancel']				= array( 'editor', '' );
		$params[] = array( 'accordion_itemend', '' );
		$params[] = array( 'div_end', '' );
		$params[] = array( 'div_end', '' );
		$params[] = array( 'div_end', '' );

		@end( $params );
		$tab_data[] = array( JText::_('CFG_TAB_CUSTOMPAGES_TITLE'), key( $params ), '<h2>' . JText::_('CFG_TAB_CUSTOMPAGES_SUBTITLE') . '</h2>' );

		return $settings;
	}

}
?>
