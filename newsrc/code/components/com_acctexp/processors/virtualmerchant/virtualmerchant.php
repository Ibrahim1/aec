<?php
/**
 * @version $Id: virtualmerchant.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Processors - Virtual Merchant
 * @copyright 2007-2012 Copyright (C) David Deutsch
 * @author David Deutsch <skore@valanx.org> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.3 http://www.gnu.org/licenses/gpl.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' );

class processor_virtualmerchant extends POSTprocessor
{
	function info()
	{
		$info = array();
		$info['name']					= "virtualmerchant";
		$info['longname']				= "VirtualMerchant";
		$info['statement']				= "Make payments with VirtualMerchant!";
		$info['description']			= 'Virtual Merchant is your global source for innovative payment solutions - the one company that clients and partners everywhere trust to securely and reliably manage their payments business.';
		$info['currencies']				= "USD";
		$info['cc_list']				= "visa,mastercard,discover,americanexpress,echeck,giropay";
		$info['recurring']				= 0;
		$info['notify_trail_thanks']	= 1;

		return $info;
	}

	function settings()
	{
		$settings = array();
		$settings['currency']		= "USD";
		$settings['accountid']		= "your account id";
		$settings['userid']			= "your user id";
		$settings['pin']			= "your pin";
		$settings['testmode']		= 0;
		$settings['item_name']		= sprintf( JText::_('CFG_PROCESSOR_ITEM_NAME_DEFAULT'), '[[cms_live_site]]', '[[user_name]]', '[[user_username]]' );
		$settings['customparams']	= "";

		return $settings;
	}

	function backend_settings()
	{
		$settings = array();
		$settings['aec_experimental']	= array( 'p' );
		$settings['aec_insecure']		= array( 'p' );
		$settings['testmode']			= array( 'toggle' );
		$settings['currency']			= array( 'list_currency' );
		$settings['accountid']			= array( 'inputC' );
		$settings['userid']				= array( 'inputC' );
		$settings['pin']				= array( 'inputC' );
		$settings['item_name']			= array( 'inputE' );
		$settings['customparams']		= array( 'inputD' );

		$settings = AECToolbox::rewriteEngineInfo( null, $settings );

		return $settings;
	}

	function createGatewayLink( $request )
	{
		$var['post_url']				= "https://www.myvirtualmerchant.com/VirtualMerchant/process.do";
		$var['ssl_test_mode']			= $this->settings['testmode'] ? "true" : "false";
        $var['ssl_transaction_type']	= "ccsale";
		$var['ssl_merchant_id']			= $this->settings['accountid'];
		$var['ssl_user_id']				= $this->settings['userid'];
		$var['ssl_pin']					= $this->settings['pin'];
		$var['ssl_invoice_number']		= $request->invoice->invoice_number;
        $var['ssl_customer_code']		= $request->metaUser->cmsUser->username;
		$var['ssl_salestax']			= "0";
		$var['ssl_result_format']		= "HTML";
		$var['ssl_receipt_link_method']	= "POST";
		$var['ssl_receipt_link_url']	= AECToolbox::deadsureURL("index.php?option=com_acctexp&amp;task=virtualmerchantnotification");
		$var['ssl_receipt_link_text']	= "Continue";
		$var['ssl_amount']				= $request->int_var['amount'];
		$var['currency_code']			= $this->settings['currency_code'];

		$var['item_number']				= $row->id;
		$var['item_name']				= AECToolbox::rewriteEngineRQ( $this->settings['item_name'], $request );
		$var['custom']					= $request->int_var['usage'];

		return $var;
	}

	function parseNotification( $post )
	{
		$ssl_result				= $post['ssl_result'];
		$ssl_result_message		= $post['ssl_result_message'];
		$ssl_txn_id				= $post['ssl_txn_id'];
		$ssl_approval_code		= $post['ssl_approval_code'];
		$ssl_cvv2_response		= $post['ssl_cvv2_response'];
		$ssl_avs_response		= $post['ssl_avs_response'];
		$ssl_transaction_type	= $post['ssl_transaction_type'];

		$ssl_amount				= $post['ssl_amount'];
		$ssl_email				= $post['ssl_email'];
		$ssl_description		= $post['ssl_description'];
		$ssl_customer_code		= $post['ssl_customer_code'];

		$response = array();
		$response['invoice']	= $post['ssl_invoice_number'];

		return $response;
	}

	function validateNotification( $response, $post, $invoice )
	{
		$response['valid'] = ( $post['ssl_result'] == 0 ) && ( strcmp ( $post['ssl_result_message'], "APPROVED") == 0 );

		return $response;
	}

}
?>