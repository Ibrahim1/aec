<?php
/**
 * @version $Id: eyowo.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Processors - Eyowo
 * @copyright 2013 Copyright (C) David Deutsch
 * @author David Deutsch <skore@valanx.org> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.3 http://www.gnu.org/licenses/gpl.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' );

class processor_eyowo extends POSTprocessor
{
	function info()
	{
		$info = array();
		$info['name']					= 'eyowo';
		$info['longname']				= JText::_('CFG_EYOWO_LONGNAME');
		$info['statement']				= JText::_('CFG_EYOWO_STATEMENT');
		$info['description']			= JText::_('CFG_EYOWO_DESCRIPTION');
		$info['currencies']				= 'NGN';
		$info['cc_list']				= 'mastercard';
		$info['recurring']				= 0;
		$info['notify_trail_thanks']	= 1;

		return $info;
	}

	function settings()
	{
		$settings = array();
		$settings['walletcode']		= 'RH14615';
		$settings['item_name']		= sprintf( JText::_('CFG_PROCESSOR_ITEM_NAME_DEFAULT'), '[[cms_live_site]]', '[[user_name]]', '[[user_username]]' );
		$settings['item_desc']		= sprintf( JText::_('CFG_PROCESSOR_ITEM_DESC_DEFAULT'), '[[cms_live_site]]', '[[user_name]]', '[[user_username]]' );
		$settings['customparams']	= "";

		return $settings;
	}

	function backend_settings()
	{
		$settings = array();
		$settings['walletcode']		= array( 'inputC' );
		$settings['item_name']		= array( 'inputE' );
		$settings['item_desc']		= array( 'inputE' );
		$settings['customparams']	= array( 'inputD' );

		$settings					= AECToolbox::rewriteEngineInfo( null, $settings );

		return $settings;
	}

	function createGatewayLink( $request )
	{
		$var['post_url']	= "https://www.eyowo.com/gateway/pay";

		$var['eyw_walletcode']		= $this->settings['walletcode'];
		$var['eyw_transactionref']	= $request->invoice->invoice_number;

		$var['eyw_item_name_1'] 		= AECToolbox::rewriteEngineRQ( $this->settings['item_name'], $request );
		$var['eyw_item_description_1'] 	= AECToolbox::rewriteEngineRQ( $this->settings['item_desc'], $request );
		$var['eyw_item_price_1'] 		= $request->int_var['amount'];

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
		$path = '/api/gettransactionstatus';

		$url = 'https://www.eyowo.com' . $path;

		$vars = array(	'format' => 'json',
						'walletcode' => $this->settings['walletcode'],
						'transactionref' => $invoice->invoice_number
				);

		$array = array();
		foreach ( $post as $k => $v ) {
			$array[] = $k."=".$v;
		}

		$path .= '?' . implode( "&", $array );

		$res = (array) json_decode( $this->transmitRequest( $url, $path ) );

		$response['valid'] = false;

		if ( ( $post['ap_status'] == "Success" && $post['ap_purchasetype'] != "subscription" ) || ( $post['ap_status'] == "Subscription-Payment-Success" ) )  {
			if ( $post['ap_securitycode'] != $this->settings['securitycode'] ) {
				$response['error'] = 'Security Code Mismatch: ' . $post['ap_securitycode'];
			} else {
				$response['valid'] = true;
			}
		} elseif ( $post['ap_status'] == "Success" && $post['ap_purchasetype'] == "subscription" ) {
			$response['null']				= 1;
			$response['explanation']		= 'Duplicate Notification';
		} elseif ( $post['ap_status'] == "Subscription-Payment-Canceled" ) {
			$response['cancel'] = 1;
		} else {
			$response['error'] = 'ap_status: ' . $post['ap_status'];
		}

		return $response;
	}

}
?>