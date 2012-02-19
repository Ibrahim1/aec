<?php
/**
 * @version $Id: robokassa.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Processors - PayPal Buy Now
 * @copyright 2007-2011-2012 Copyright (C) David Deutsch
 * @author David Deutsch <skore@valanx.org> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' );

class processor_robokassa extends POSTprocessor
{
	function info()
	{
		$info = array();
		$info['name']			= 'robokassa';
		$info['longname']		= JText::_('CFG_ROBOKASSA_LONGNAME');
		$info['statement']		= JText::_('CFG_ROBOKASSA_STATEMENT');
		$info['description']	= JText::_('CFG_ROBOKASSA_DESCRIPTION');
		$info['currencies']		= 'RUB,USD,EUR,GBP,CAD,AUD,BGN,CZK,DKK,EEK,HKD,HUF,LTL,MYR,NZD,NOK,PLN,ROL,SGD,ZAR,SEK,CHF';
		$info['languages']		= AECToolbox::getISO639_1_codes();
		$info['cc_list']		= 'visa,mastercard,maestro';
		$info['recurring']		= 0;
		$info['custom_notify_trail']	= 1;

		return $info;
	}

	function settings()
	{
		$settings = array();
		$settings['testmode']		= 0;
		$settings['login']			= 'Merchant Login ID';
		$settings['pass']			= 'Merchant Password';
		$settings['notify_pass']	= 'Notification Password';
		$settings['currency']		= 'EUR';
		$settings['language']		= 'RU';
		$settings['item_name']		= sprintf( JText::_('CFG_PROCESSOR_ITEM_NAME_DEFAULT'), '[[cms_live_site]]', '[[user_name]]', '[[user_username]]' );
		$settings['customparams']	= "";

		return $settings;
	}

	function backend_settings()
	{
		$settings = array();
		$settings['testmode']		= array( 'toggle' );
		$settings['login']			= array( 'inputC' );
		$settings['pass']			= array( 'inputC' );
		$settings['notify_pass']			= array( 'inputC' );
		$settings['currency']		= array( 'list_currency' );
		$settings['language']		= array( 'list_language' );
		$settings['item_name']		= array( 'inputE' );
		$settings['customparams']	= array( 'inputD' );

		$settings					= AECToolbox::rewriteEngineInfo( null, $settings );

		return $settings;
	}

	function createGatewayLink( $request )
	{
		if ( $this->settings['testmode'] ) {
			$var['post_url']		= 'http://test.robokassa.ru/Index.aspx ';
		} else {
			$var['post_url']		= "https://merchant.roboxchange.com/Index.aspx";
		}

		$var['MrchLogin']			= trim($this->settings['login']);
		$var['OutSum']				= $request->int_var['amount'];
		$var['InvId']				= $request->invoice->id;
		$var['Desc']				= AECToolbox::rewriteEngineRQ( $this->settings['item_name'], $request );
		$var['SignatureValue']		= $this->getHash( $request->invoice );			
		$var['Culture']				= $this->settings['language'];

		return $var;
	}

	function parseNotification( $post )
	{
		$response = array();
		$response['amount_paid']	= number_format( $post['OutSum'], 2 );
		$response['invoice'] 		= AECfetchfromDB::InvoiceNumberfromId( $post['InvId'] );

		return $response;
	}

	function validateNotification( $response, $post, $invoice )
	{
		$response['valid'] = false;

		$invoice->amount = $post['OutSum'];

		if ( strtoupper( $post['SignatureValue'] ) != strtoupper( $this->getHash( $invoice, false ) ) ) {
			$response['error'] = 'Security Code Mismatch';
		} else {
			$response['valid'] = true;
		}

		return $response;
	}

	function getHash( $invoice, $send=true, $notification=false )
	{
		// MD5 signature formed from the parameters, separated by ':' with sMerchantPass2 added at the end
		// i.e. nOutSum:nInvId:sMerchantPass2[:sorted_merchant_parameters]

		$vars = array();

		if ( $send ) {
			$vars[] = trim($this->settings['login']);
		}

		$vars[] = $invoice->amount;
		$vars[] = $invoice->id;

		if ( $send ) {
			$vars[] = trim($this->settings['notify_pass']);
		} else {
			$vars[] = trim($this->settings['pass']);
		}

		return md5( implode( ':', $vars ) );
	}

	function notify_trail( $InvoiceFactory, $response )
	{
		echo $_POST['InvId'];exit;
	}

}
?>
