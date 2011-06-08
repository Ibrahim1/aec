<?php
/**
 * @version $Id: realex_redirect.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Processors - Realex - Redirect Mode
 * @copyright 2011 Copyright (C) David Deutsch
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' );

class processor_realexrd extends POSTprocessor
{
	function info()
	{
		$info = array();
		$info['name']			= 'realexrd';
		$info['longname']		= _AEC_PROC_INFO_RXRD_LNAME;
		$info['statement']		= _AEC_PROC_INFO_RXRD_STMNT;
		$info['description']	= _DESCRIPTION_REALEX;
		$info['currencies']		= 'EUR,USD,GBP,AUD,CAD,JPY,NZD,CHF,HKD,SGD,SEK,DKK,PLN,NOK,HUF,CZK,MXN,ILS,BRL,MYR,PHP,TWD,THB,ZAR';
		$info['languages']		= AECToolbox::getISO3166_1a2_codes();
		$info['cc_list']		= 'visa,mastercard,laser';
		$info['recurring']		= 0;

		return $info;
	}

	function settings()
	{
		$settings = array();
		$settings['merchantid']	= 'yourmerchantid';
		$settings['account']	= 'youraccount';
		$settings['secret']		= 'yoursecret';
		$settings['testmode']	= 1;
		$settings['currency']	= 'EUR';
		
		return $settings;
	}

	function backend_settings()
	{
		$settings = array();

		$settings['merchantid']	= array( 'inputC' );
		$settings['account']	= array( 'inputC' );
		$settings['secret']		= array( 'inputC' );
		$settings['testmode']	= array( 'list_yesno' );
		$settings['currency']	= array( 'list_currency' );

		$settings = AECToolbox::rewriteEngineInfo( null, $settings );

		return $settings;
	}

	function createGatewayLink( $request )
	{
		if ( $this->settings['testmode'] ) {
			$var['post_url']	= 'https://epage.payandshop.com/epage.cgi';
		} else {
			$var['post_url']	= 'https://epage.payandshop.com/epage.cgi';
		}

		//The code below is used to create the timestamp format required by Realex Payments
		$timestamp = strftime("%Y%m%d%H%M%S");
		mt_srand((double)microtime()*1000000);


		$orderid = $timestamp.mt_rand(1, 999);
		$amt = round(100*$request->items->total->cost['amount']);
		$curr = $this->settings['currency']	;

		/*-----------------------------------------------
		Below is the code for creating the digital signature using the MD5 algorithm provided
		by PHP. you can use the SHA1 algorithm alternatively. 
		*/
		$mid=$this->settings['merchantid'];
		$ss= $this->settings['secret'];
		
		$tmp = "$timestamp.$mid.$orderid.$amt.$curr";
		$md5hash = md5($tmp);
		$tmp = "$md5hash.$ss";
		$md5hash = md5($tmp);		
		
		$var['MERCHANT_ID'] = $this->settings['merchantid'];
		$var['ORDER_ID'] = $orderid;
		$var['ACCOUNT'] = $this->settings['account'];
		$var['CURRENCY'] = $curr;
		$var['AMOUNT']= $amt;
		$var['TIMESTAMP'] = $timestamp;
		$var['MD5HASH'] = $md5hash;
		$var['AUTO_SETTLE_FLAG'] = 1;

		return $var;
	}

	function parseNotification( $post )
	{
		$db = &JFactory::getDBO();

		$response = array();
		$response['invoice'] = $post['invoice'];
		$response['amount_currency'] = $post['mc_currency'];

		switch ( $post['txn_type'] ) {
			case "web_accept":
			case "subscr_payment":
				$response['amount_paid'] = $post['mc_gross'];
				break;
			case "subscr_signup":
			case "subscr_cancel":
			case "subscr_modify":
				// Docs suggest mc_amount1 is set with signup, cancel or modify
				// Testing shows otherwise
				$response['amount_paid'] = isset($post['mc_amount1']) ? $post['mc_amount1'] : null;
			break;
			case "subscr_failed":
			case "subscr_eot":
				// May create a problem somewhere donw the line, but NULL
				// is a more representative value
			break;
			default:
			// Either a fraud attempt, or PayPal has changed its API
			// TODO: Raise Error
			$response['amount_paid'] = null;
		}

		return $response;
	}

}
?>
