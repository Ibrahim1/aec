<?php
// Copyright (C) 2006-2007 David Deutsch
// All rights reserved.
// This source file is part of the Account Expiration Control Component, a  Joomla
// custom Component By Helder Garcia and David Deutsch - http://www.globalnerd.org
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License (GPL)
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.
//
// Please note that the GPL states that any headers in files and
// Copyright notices as well as credits in headers, source files
// and output (screens, prints, etc.) can not be removed.
// You can extend them with your own credits, though...
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program; if not, write to the Free Software
// Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.

// Dont allow direct linking
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

class processor_alertpay
{
	function processor_alertpay()
	{
		global $mosConfig_absolute_path;

		if ( !defined( '_AEC_LANG_PROCESSOR' ) ) {
			$langPath = $mosConfig_absolute_path . '/components/com_acctexp/processors/com_acctexp_language_processors/';
			if (file_exists( $langPath . $GLOBALS['mosConfig_lang'] . '.php' )) {
				include_once( $langPath . $GLOBALS['mosConfig_lang'] . '.php' );
			} else {
				include_once( $langPath . 'english.php' );
			}
		}
	}

	function info()
	{
		$info = array();
		$info['name']				= 'alertpay';
		$info['longname']			= _AEC_PROC_INFO_AP_LNAME;
		$info['statement']			= _AEC_PROC_INFO_AP_STMNT;
		$info['description']		= _DESCRIPTION_ALERTPAY;
		$info['cc_list']			= 'visa,mastercard,discover,americanexpress,echeck';
		$info['recurring']			= 0;

		return $info;
	}

	function settings()
	{
		$settings = array();
		$settings['merchant']		= 'merchant';
		$settings['securitycode']	= 'security code';
		$settings['testmode']		= 0;
		$settings['item_name']		= sprintf( _CFG_PROCESSOR_ITEM_NAME_DEFAULT, '[[cms_live_site]]', '[[user_name]]', '[[user_username]]' );
		$settings['rewriteInfo']	= ''; // added mic

		return $settings;
	}

	function backend_settings()
	{
		$settings = array();
		$settings['testmode']		= array( 'list_yesno' );
		$settings['merchant']		= array( 'inputC' );
		$settings['securitycode']	= array( 'inputC' );
		$settings['item_name']		= array( 'inputE' );
 		$rewriteswitches			= array( 'cms', 'user', 'expiration', 'subscription', 'plan' );
        $settings['rewriteInfo']	= array( 'fieldset', _AEC_MI_REWRITING_INFO, AECToolbox::rewriteEngineInfo( $rewriteswitches ) );

		return $settings;
	}

	function createGatewayLink( $int_var, $cfg, $metaUser, $new_subscription )
	{
		global $mosConfig_live_site;

		$post_url	= "http://www.alertpay.com/PayProcess.aspx";
		if ( $cfg->testmode ) {
			$var['ap_test'] = '1';
		}

		$var['ap_purchasetype']	= 'Item'; //Item or Subscription - Subscription not supported yet

		//$var['ap_purchasetype']	= 'Subscription'; //Item and Subscription - Right now no subscription but it will be built into system

		$var['ap_merchant']		= $cfg['merchant'];
		$var['ap_itemname']		= $int_var['invoice'];
		$var['ap_currency']		= $cfg['currency_code'];
		$var['ap_returnurl']	= AECToolbox::deadsureURL("/index.php?option=com_acctexp&amp;task=thanks");
		$var['ap_quantity']		= '';
		$var['ap_description']	= sprintf( _CFG_PROCESSOR_ITEM_NAME_DEFAULT, $mosConfig_live_site,
									$metaUser->cmsUser->name, $metaUser->cmsUser->username );
		if ( isset( $cfg['tax'] ) && @$cfg['tax'] > 0) {
			$tax = $int_var['amount']/(100+$cfg['tax'])*100;
			$var['ap_amount'] 	= round($tax, 2);
		} else {
			$var['ap_amount'] 	= $int_var['amount'];
		}
		$var['ap_cancelurl']	= AECToolbox::deadsureURL("/index.php?option=com_acctexp&amp;task=cancel");

		$var['apc_1']			= $metaUser->cmsUser->id;
		$var['apc_2']			= AECToolbox::rewriteEngine( $cfg['item_name'], $metaUser, $new_subscription );
		$var['apc_3']			= $int_var['usage'];

		return $var;
	}

	function parseNotification( $post, $cfg )
	{
		$security_code			= $post['ap_securitycode'];
		$description			= $post['ap_description'];
		$total					= $post['ap_amount'];
		$userid					= $post['apc_1'];
		$invoice_number			= $post['ap_itemname'];
		$planid					= $post['apc_3'];

		$response = array();
		$response['invoice'] = $invoice_number;
		$response['valid'] = ( ( strcmp( $_POST['ap_status'], "Success" ) === 0 ) && ( strcmp( $_POST['ap_securitycode'], $cfg->ap_securitycode ) === 0 ) );

		return $response;
	}
}
?>