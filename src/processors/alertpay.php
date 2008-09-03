<?php
/**
 * @version $Id: alertpay.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Processors - Alertpay
 * @copyright 2006-2008 Copyright (C) David Deutsch
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.globalnerd.org
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */

// Dont allow direct linking
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

class processor_alertpay extends POSTprocessor
{
	function info()
	{
		$info = array();
		$info['name']					= 'alertpay';
		$info['longname']				= _AEC_PROC_INFO_AP_LNAME;
		$info['statement']				= _AEC_PROC_INFO_AP_STMNT;
		$info['description']			= _DESCRIPTION_ALERTPAY;
		$info['currencies']				= 'USD';
		$info['cc_list']				= 'visa,mastercard,discover,americanexpress,echeck';
		$info['recurring']				= 0;
		$info['notify_trail_thanks']	= 1;

		return $info;
	}

	function settings()
	{
		$settings = array();
		$settings['testmode']		= 0;
		$settings['merchant']		= 'merchant';
		$settings['securitycode']	= 'security code';
		$settings['currency']		= 'EUR';
		$settings['testmode']		= 0;
		$settings['item_name']		= sprintf( _CFG_PROCESSOR_ITEM_NAME_DEFAULT, '[[cms_live_site]]', '[[user_name]]', '[[user_username]]' );
		$settings['customparams']	= "";

		return $settings;
	}

	function backend_settings()
	{
		$settings = array();
		$settings['testmode']		= array( 'list_yesno' );
		$settings['merchant']		= array( 'inputC' );
		$settings['securitycode']	= array( 'inputC' );
		$settings['currency']		= array( 'list_currency' );
		$settings['item_name']		= array( 'inputE' );
		$settings['customparams']	= array( 'inputD' );

		$settings					= AECToolbox::rewriteEngineInfo( null, $settings );

		return $settings;
	}

	function createGatewayLink( $request )
	{
		global $mosConfig_live_site;

		$var['post_url']	= "http://www.alertpay.com/PayProcess.aspx";
		if ( $this->settings->testmode ) {
			$var['ap_test'] = '1';
		}

		$var['ap_purchasetype']	= 'Item'; //Item or Subscription - Subscription not supported yet

		//$var['ap_purchasetype']	= 'Subscription'; //Item and Subscription - Right now no subscription but it will be built into system

		$var['ap_merchant']		= $this->settings['merchant'];
		$var['ap_itemname']		= $request->int_var['invoice'];
		$var['ap_currency']		= $this->settings['currency_code'];
		$var['ap_returnurl']	= AECToolbox::deadsureURL( "/index.php?option=com_acctexp&amp;task=thanks" );
		$var['ap_quantity']		= '';
		$var['ap_description']	= sprintf( _CFG_PROCESSOR_ITEM_NAME_DEFAULT, $mosConfig_live_site, $request->metaUser->cmsUser->name, $request->metaUser->cmsUser->username );

		if ( !empty( $this->settings['tax'] ) ) {
			$tax = $request->int_var['amount'] / ( 100 + $this->settings['tax'] ) * 100;
			$var['ap_amount'] 	= round( $tax, 2 );
		} else {
			$var['ap_amount'] 	= $request->int_var['amount'];
		}

		$var['ap_cancelurl']	= AECToolbox::deadsureURL( "/index.php?option=com_acctexp&amp;task=cancel" );

		$var['apc_1']			= $request->metaUser->cmsUser->id;
		$var['apc_2']			= AECToolbox::rewriteEngineRQ( $this->settings['item_name'], $request );
		$var['apc_3']			= $request->int_var['usage'];

		return $var;
	}

	function parseNotification( $post )
	{
		$security_code			= $post['ap_securitycode'];
		$description			= $post['ap_description'];
		$total					= $post['ap_amount'];
		$userid					= $post['apc_1'];
		$invoice_number			= $post['ap_itemname'];
		$planid					= $post['apc_3'];

		$response = array();
		$response['invoice'] = $invoice_number;

		return $response;
	}

	function validateNotification( $response, $post, $invoice )
	{
		$response['valid'] = false;

		if ( !( strcmp( $post['ap_status'], "Success" ) === 0 ) ) {
			$response['error'] = 'ap_status: ' . $post['ap_status'];
		} elseif( $post['ap_securitycode'] != $this->settings['ap_securitycode'] ) {
			$response['error'] = 'Security Code Mismatch: ' . $post['ap_securitycode'] . ' != ' . $this->settings['ap_securitycode'];
		} else {
			$response['valid'] = true;
		}

		return $response;
	}

}
?>