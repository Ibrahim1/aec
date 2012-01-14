<?php
/**
 * @version $Id: payments_gateway.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Processors - Payments Gateway
 * @copyright 2012 Copyright (C) David Deutsch
 * @author David Deutsch <skore@valanx.org> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' );

class processor_payments_gateway extends POSTprocessor
{
	function info()
	{
		$info = array();
		$info['name']			= 'payments_gateway';
		$info['longname']		= JText::_('CFG_PAYMENTS_GATEWAY_LONGNAME');
		$info['statement']		= JText::_('CFG_PAYMENTS_GATEWAY_STATEMENT');
		$info['description']	= JText::_('CFG_PAYMENTS_GATEWAY_DESCRIPTION');
		$info['currencies']		= 'USD';
		$info['languages']		= AECToolbox::getISO639_1_codes();
		$info['cc_list']		= 'visa,mastercard,discover,americanexpress,echeck';
		$info['recurring']		= 2;
		$info['notify_trail_thanks']	= 1;
		$info['recurring_buttons']		= 2;

		return $info;
	}

	function settings()
	{
		$settings = array();
		$settings['api_login_id']	= '112233445566';
		$settings['api_key']		= '112233445566';
		$settings['testmode']		= 0;
		$settings['invoice_tax']	= 0;
		$settings['tax']			= '';
		$settings['currency']		= 'USD';
		$settings['customparams']	= "";

		return $settings;
	}

	function backend_settings()
	{
		$settings = array();

		$settings['api_login_id']			= array( 'inputC' );
		$settings['api_key']				= array( 'inputC' );
		$settings['testmode']				= array( 'list_yesno' );
		$settings['invoice_tax']			= array( 'list_yesno' );
		$settings['tax']					= array( 'inputA' );
		$settings['currency']				= array( 'list_currency' );
		$settings['customparams']			= array( 'inputD' );

		$settings = AECToolbox::rewriteEngineInfo( null, $settings );

		return $settings;
	}

	function createGatewayLink( $request )
	{
		if ( $this->settings['testmode'] ) {
			$var['post_url']	= 'https://sandbox.paymentsgateway.net/swp/co/default.aspx';
		} else {
			$var['post_url']	= 'https://swp.paymentsgateway.net/co/default.aspx';
		}

		$namearray		= $request->metaUser->explodeName();

		$var['pg_billto_postal_name_first']	= $namearray['first'];
		$var['pg_billto_postal_name_last']	= $namearray['last'];

		if ( !empty( $this->settings['invoice_tax'] ) && isset( $request->items->tax ) ) {
			$tax = 0;

			foreach ( $request->items->tax as $itax ) {
				$tax += $itax['cost'];
			}

			$var['pg_sales_tax_amount']			= $tax;

			$var['pg_total_amount']		= $request->items->total->cost['amount'];
		} elseif ( !empty( $this->settings['tax'] ) && $this->settings['tax'] > 0 ) {
			$amount				= $request->int_var['amount'] / ( 100 + $this->settings['tax'] ) * 100;
			$var['pg_sales_tax_amount']			= AECToolbox::correctAmount( ( $request->int_var['amount'] - $amount ), 2 );
			$var['pg_total_amount']		= AECToolbox::correctAmount( $amount, 2 );
		} else {
			$var['pg_total_amount']		= $request->int_var['amount'];
		}

		$var['pg_api_login_id']					= $this->settings['api_login_id'];
		$var['pg_consumerorderid']				= $request->invoice->invoice_number;

		$var['pg_return_url']					= AECToolbox::deadsureURL( 'index.php?option=com_acctexp&amp;task=payments_gatewaynotification' );
		$var['pg_return_method']				= 'AsyncPost';

		$var['pg_version_number']				= '1.0';
		$var['pg_utc_time']						= (int) gmdate('U');
		$var['pg_transaction_order_number']		= $request->invoice->id;

		$var['pg_ts_hash']	= $this->hmac( implode(" | ", array(	$var['pg_api_login_id'],
																	"",
																	$var['pg_version_number'],
																	$var['pg_total_amount'],
																	$var['pg_utc_time'],
																	$var['pg_transaction_order_number']
																)
													), $this->settings['api_key'] ); 

		return $var;
	}

	function parseNotification( $post )
	{
		$response = array();
		aecDebug($post);

		return $response;
	}

	function validateNotification( $response, $post, $invoice )
	{


		return $response;
	}

	function hmac( $key, $data )
	{
	   // RFC 2104 HMAC implementation for php.
	   // Creates an md5 HMAC.
	   // Eliminates the need to install mhash to compute a HMAC
	   // Hacked by Lance Rushing

	   $b = 64; // byte length for md5

	   if (strlen($key) > $b) {
	       $key = pack("H*",md5($key));
	   }
	   $key  = str_pad($key, $b, chr(0x00));
	   $ipad = str_pad('', $b, chr(0x36));
	   $opad = str_pad('', $b, chr(0x5c));
	   $k_ipad = $key ^ $ipad ;
	   $k_opad = $key ^ $opad;

	   return md5($k_opad  . pack("H*",md5($k_ipad . $data)));
	}

}
?>
